<?php

namespace V1\Controller\Factory;

use V1\Service\ToDoManager;
use Interop\Container\ContainerInterface;
use V1\Controller\ToDoController;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

class ToDoControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ToDoController($container->get(AuthenticationService::class), $container->get(ToDoManager::class));
    }
}
