<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace V1\Controller;

use V1\Serializer\ToDoCollectionSerializer;
use V1\Form\ToDoCreate;
use V1\Serializer\ToDoSerializer;
use V1\Service\ToDoManager;
use Zend\Authentication\AuthenticationService;
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
        $toDos = $this->toDoManager->getForUser($this->auth->getIdentity());

        return new ToDoCollectionSerializer(['resources' => $toDos]);
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
        }

        return new ToDoSerializer(['resource' => $todo]);
    }
}
