<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace V1\Controller;

use Doctrine\ORM\EntityManager;
use V1\Form\ToDoUpdate;
use V1\Serializer\ToDoCollectionSerializer;
use V1\Form\ToDoCreate;
use V1\Serializer\ToDoSerializer;
use V1\Service\ToDoManager;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ToDoController extends AbstractRestfulController
{
    /**
     * @var AuthenticationService
     */
    private $auth;

    /**
     * @var ToDoManager
     */
    private $toDoManager;

    /**
     * IndexController constructor.
     *
     * @param AuthenticationService $authenticationService
     * @param ToDoManager $toDoManager
     */
    public function __construct(AuthenticationService $authenticationService, ToDoManager $toDoManager)
    {
        $this->auth = $authenticationService;
        $this->toDoManager = $toDoManager;
    }

    /**
     * @return JsonModel
     */
    public function getList(): JsonModel
    {
        $toDos = $this->toDoManager->getForUser($this->auth->getIdentity(), $this->params()->fromQuery());

        return new ToDoCollectionSerializer(['resources' => $toDos]);
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     */
    public function get($id): JsonModel
    {
        $todo = $this->toDoManager->checkExists($id, $this->auth->getIdentity());
        if ($todo === null) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return new JsonModel();
        }

        return new ToDoSerializer(['resource' => $todo]);
    }

    /**
     * @param mixed $data
     *
     * @return ToDoSerializer
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create($data): JsonModel
    {
        $form = new ToDoCreate;
        $form->setData($data);
        $todo = null;
        if ($form->isValid()) {
            $data = $form->getData();
            $data['user_id'] = $this->auth->getIdentity()->getId();
            $todo = $this->toDoManager->create($data);
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_201);

            return new ToDoSerializer(['resource' => $todo]);
        }

        $this->getResponse()->setStatusCode(Response::STATUS_CODE_422);
        return new JsonModel($form->getMessages());
    }

    /**
     * @param mixed $id
     * @param mixed $data
     *
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($id, $data): JsonModel
    {
        $todo = $this->toDoManager->checkExists($id, $this->auth->getIdentity());
        if ($todo === null) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return new JsonModel();
        }

        $form = new ToDoUpdate(false);
        $form->setData($data ?: []);
        if ($form->isValid()) {
            $todo = $this->toDoManager->update($todo, $data);

            return new ToDoSerializer(['resource' => $todo]);
        }
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_422);

        return new JsonModel($form->getMessages());
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($id): JsonModel
    {
        $todo = $this->toDoManager->checkExists($id, $this->auth->getIdentity());
        if ($todo === null) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_404);
            return new JsonModel();
        }

        $this->toDoManager->delete($todo);
        $this->getResponse()->setStatusCode(Response::STATUS_CODE_204);

        return new JsonModel([]);
    }
}
