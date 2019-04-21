<?php

namespace Auth\Controller;

use Auth\Serializer\UserSerializer;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    private $auth;

    /**
     * UserController constructor.
     *
     * @param AuthenticationService $authenticationService
     */
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->auth = $authenticationService;
    }

    /**
     * @return JsonModel
     */
    public function selfAction(): JsonModel
    {
        return new UserSerializer(['resource' => $this->auth->getIdentity()]);
    }
}
