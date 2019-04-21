<?php

namespace Auth\Controller\Factory;

use Auth\Controller\UserController;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserController($container->get(AuthenticationService::class));
    }
}
