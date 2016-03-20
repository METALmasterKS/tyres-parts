<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Account;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Account\Model\Contact;
use Account\Model\ContactTable;
use Account\Model\Company;
use Account\Model\CompanyTable;
use Account\Model\Address;
use Account\Model\AddressTable;
use Account\Model\Wishlist;
use Account\Model\WishlistTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\MasterSlaveFeature;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $e->getApplication()->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($this, 'checkUserIdentity'), 100);
    }
    
    public function checkUserIdentity(MvcEvent $e) {
        if (strpos($e->getRouteMatch()->getParam('controller'), __NAMESPACE__, 0) === 0) {
            $authService = $e->getApplication()->getServiceManager()->get('UserAuthService');
            if (!$authService->hasIdentity()) {
                $url = $e->getRouter()->assemble(array(), array('name' => 'auth/user/login'));
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $url);
                $response->setStatusCode(302)->sendHeaders();
                return $response;
            }
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
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(

            )
        );
    }
}
