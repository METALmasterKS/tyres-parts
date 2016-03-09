<?php
namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
//Auth
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\Storage\Session as Session;
//db
use Auth\Model\SystemUser;
use Auth\Model\SystemUserTable;
use Auth\Model\User;
use Auth\Model\UserTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        
        //передача user в view
        $authService = $e->getApplication()->getServiceManager()->get('UserAuthService');
        if ($authService->hasIdentity()) {
            $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
            $viewModel->setVariable('user', $authService->getIdentity());
        }
        //передача AdminUser в view
        $authService = $e->getApplication()->getServiceManager()->get('AdminAuthService');
        if ($authService->hasIdentity()) {
            $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
            $viewModel->setVariable('admin', $authService->getIdentity());
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                //админы
                'AuthModelSystemUserTable' => function($sm) {
                    return new SystemUserTable($sm->get('SystemUserTableGateway'));
                },
                'SystemUserTableGateway' => function ($sm) {
                    return new TableGateway('sys_users', $sm->get('Zend\Db\Adapter\Adapter'), null, new ResultSet(null, new SystemUser()));
                },
                'AdminAuthService' => function ($sm) {
                    return new AuthenticationService(
                        new Session('SystemUserData'), 
                        new DbAuthAdapter($sm->get('Zend\Db\Adapter\Adapter'), 'sys_users', 'email', 'password')
                        );
                },
                //пользователи                            
                'AuthModelUserTable' => function($sm) {
                    return new UserTable($sm->get('UserTableGateway'));
                },
                'UserTableGateway' => function ($sm) {
                    return new TableGateway('users', $sm->get('Zend\Db\Adapter\Adapter'), null, new ResultSet(null, new User()));
                },
                'UserAuthService' => function ($sm) {
                    return new AuthenticationService(
                        new Session('UserData'), 
                        new DbAuthAdapter($sm->get('Zend\Db\Adapter\Adapter'), 'users', 'username', 'password')
                        );
                },
                
            )
        );
    }
}