<?php

namespace Auth\Service\Factory;

use Auth\Entity\User;
use Auth\Service\JwtAuthAdapter;
use Auth\Service\JwtManager;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class JwtAuthAdapterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new JwtAuthAdapter($container->get(JwtManager::class), $container->get(EntityManager::class)->getRepository(User::class));
    }
}
