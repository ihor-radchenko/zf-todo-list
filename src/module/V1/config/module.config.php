<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace V1;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use V1\Controller\Factory\ToDoControllerFactory;
use V1\Service\Factory\ToDoManagerFactory;
use V1\Service\ToDoManager;
use Zend\Router\Http\Segment;

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
            'todos' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/v1/todos[/:id]',
                    'defaults' => [
                        'controller' => Controller\ToDoController::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\ToDoController::class => ToDoControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            ToDoManager::class => ToDoManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
