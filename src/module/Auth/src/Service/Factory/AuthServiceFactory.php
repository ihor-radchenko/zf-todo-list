<?php

namespace Auth\Service\Factory;

use Auth\Service\JwtAuthAdapter;
use Interop\Container\ContainerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $adapter = $container->get(JwtAuthAdapter::class);
        $storage = new NonPersistent();

        return new AuthenticationService($storage, $adapter);
    }
}
