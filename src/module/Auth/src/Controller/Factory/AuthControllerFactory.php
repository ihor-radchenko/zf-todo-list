<?php

namespace Auth\Controller\Factory;

use Auth\Controller\AuthController;
use Auth\Service\JwtManager;
use Auth\Service\UserManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Http\Request;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthController(
            $container->get(UserManager::class),
            $container->get(JwtManager::class),
            $container->get(EntityManager::class)
        );
    }
}
