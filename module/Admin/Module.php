<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array($this, 'checkAdminIdentity'), -110);
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', array($this, 'setLayout'), -100);
    }
    
    public function checkAdminIdentity(MvcEvent $e) {
        if (strpos($e->getRouteMatch()->getParam('controller'), __NAMESPACE__, 0) === 0) {
            $authService = $e->getApplication()->getServiceManager()->get('AdminAuthService');
            if (!$authService->hasIdentity()){
                $redirect = $e->getApplication()->getServiceManager()->get('ControllerPluginManager')->get('redirect');
                $redirect->toRoute('auth/admin/login');//->setStatusCode(\Zend\Http\Response::STATUS_CODE_404);
            }
        }
    }

    public function setLayout($e)
    {
        //если текущий модуль совпадает с загруженным
        if (strpos($e->getRouteMatch()->getParam('controller'), __NAMESPACE__, 0) === 0) {
            $config = $e->getApplication()->getServiceManager()->get('config');
            $actionName = strtolower($e->getRouteMatch()->getParam('action', 'not-found'));
            if (isset($config['module_layouts'][__NAMESPACE__][$actionName])) 
                $e->getViewModel()->setTemplate($config['module_layouts'][__NAMESPACE__][$actionName]);
            elseif (isset($config['module_layouts'][__NAMESPACE__]['default']))
                $e->getViewModel()->setTemplate($config['module_layouts'][__NAMESPACE__]['default']);
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
	    'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
