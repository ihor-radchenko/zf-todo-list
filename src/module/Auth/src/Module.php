<?php

namespace Auth;

use Auth\Utils\BearerTokenParser;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
//        $e->setViewModel(new JsonModel);

        $app    = $e->getApplication();
        $em     = $app->getEventManager();
        $sm     = $app->getServiceManager();
        /** @var $auth AuthenticationService */
        $auth   = $sm->get(AuthenticationService::class);
        $em->attach(MvcEvent::EVENT_ROUTE, static function(MvcEvent $e) use ($auth, $sm) {
            $match      = $e->getRouteMatch();
            $request    = $e->getRequest();
            $authHeader = $request->getHeader('authorization');
            if ($authHeader) {
                $jwt = BearerTokenParser::parse($authHeader->getFieldValue());
                $auth->getAdapter()->setToken($jwt);
            }
            $auth->authenticate();
            $protectedRoutes = $sm->get('Config')['protectedRoutes'];
            $name = $match->getMatchedRouteName();
            if (in_array($name, $protectedRoutes, true) && !$auth->hasIdentity()) {
                header('HTTP/1.1 401 Unauthorized');
                die();
            }
        });
    }
}
