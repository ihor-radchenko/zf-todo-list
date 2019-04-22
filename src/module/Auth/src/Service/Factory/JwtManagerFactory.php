<?php

namespace Auth\Service\Factory;

use Auth\Service\BlacklistManager;
use Auth\Service\JwtManager;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\Helper\ServerUrl;

class JwtManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new JwtManager(
            new Builder,
            new Parser,
            new ServerUrl,
            $container->get('Config')['jwt'],
            $container->get(BlacklistManager::class)
        );
    }
}
