<?php

namespace Auth\Service\Factory;

use Auth\Service\BlacklistManager;
use Auth\Service\Storage\CacheStorage;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class BlacklistManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new BlacklistManager($container->get(CacheStorage::class));
    }
}