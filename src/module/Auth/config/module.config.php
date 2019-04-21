<?php

namespace Auth;

use Auth\Controller\AuthController;
use Auth\Controller\Factory\UserControllerFactory;
use Auth\Controller\UserController;
use Auth\Service\Factory\JwtAuthAdapterFactory;
use Auth\Service\JwtAuthAdapter;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Authentication\AuthenticationService;
use Zend\Router\Http\Literal;

return [
    'doctrine'        => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity'],
            ],
            'orm_default'             => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
    'router'          => [
        'routes' => [
            'register' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api/register',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
            'login'    => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'refresh'  => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api/refresh',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'refresh',
                    ],
                ],
            ],
            'self'     => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api/self',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'self',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\UserManager::class   => Service\Factory\UserManagerFactory::class,
            Service\JwtManager::class    => Service\Factory\JwtManagerFactory::class,
            AuthenticationService::class => Service\Factory\AuthServiceFactory::class,
            JwtAuthAdapter::class        => JwtAuthAdapterFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            AuthController::class => Controller\Factory\AuthControllerFactory::class,
            UserController::class => UserControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
