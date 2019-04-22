<?php

namespace Auth\Service\Factory;

use Auth\Service\Storage\CacheStorage;
use Interop\Container\ContainerInterface;
use Zend\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

class CacheStorageFactory implements FactoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $storageFactory = StorageFactory::factory([
            'adapter' => [
                'name'    => Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache',
                    'ttl'       => 60 * 60 * 1,
                ],
            ],
            'plugins' => [
                [
                    'name'    => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ]);

        return new CacheStorage(new SimpleCacheDecorator($storageFactory));
    }
}