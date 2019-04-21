<?php

namespace Auth\Controller;

use Auth\Entity\User;
use Auth\Form\LoginForm;
use Auth\Form\RegistrationForm;
use Auth\Service\JwtManager;
use Auth\Service\UserManager;
use Auth\Utils\BearerTokenParser;
use Doctrine\ORM\EntityManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class AuthController extends AbstractActionController
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var JwtManager
     */
    private $jwtManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AuthController constructor.
     *
     * @param UserManager $userManager
     * @param JwtManager $jwtManager
     * @param EntityManager $entityManager
     */
    public function __construct(UserManager $userManager, JwtManager $jwtManager, EntityManager $entityManager)
    {
        $this->userManager = $userManager;
        $this->jwtManager = $jwtManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function registerAction(): JsonModel
    {
        $form = new RegistrationForm($this->entityManager);

        $response = [];
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());

            if ($form->isValid()) {
                $user = $this->userManager->create($form->getData());
                $response = $this->jwtManager->getFromSubject($user);
                $statusCode = Response::STATUS_CODE_201;
            } else {
                $response = $form->getMessages();
                $statusCode = Response::STATUS_CODE_422;
            }
        } else {
            $statusCode = Response::STATUS_CODE_405;
        }
        $this->getResponse()->setStatusCode($statusCode);

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     * @throws \Exception
     */
    public function loginAction(): JsonModel
    {
        $form = new LoginForm($this->entityManager);

        $response = [];
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
                if ($this->userManager->checkCredentials($user, $data)) {
                    $statusCode = Response::STATUS_CODE_200;
                    $response = $this->jwtManager->getFromSubject($user);
                } else {
                    $statusCode = Response::STATUS_CODE_401;
                    $response['email']['noObjectFound'] = 'Invalid credentials.';
                }
            } else {
                $response = $form->getMessages();
                $statusCode = Response::STATUS_CODE_422;
            }
        } else {
            $statusCode = Response::STATUS_CODE_405;
        }
        $this->getResponse()->setStatusCode($statusCode);

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     * @throws \Auth\Exception\TokenInvalidException
     */
    public function refreshAction(): JsonModel
    {
        $authHeader = $this->getRequest()->getHeader('authorization');
        if ($authHeader && ($token = BearerTokenParser::parse($authHeader->getFieldValue()))) {
            $tokenCredentials = $this->jwtManager->decode($token);
            if (!$tokenCredentials['acc'] && $this->jwtManager->validate($tokenCredentials)) {
                $user = $this->entityManager->getRepository(User::class)->find($tokenCredentials['sub']);
                return new JsonModel($this->jwtManager->getFromSubject($user));
            }
        }

        $this->getResponse()->setStatusCode(Response::STATUS_CODE_400);

        return new JsonModel(['content' => 'Invalid token.']);
    }
}
