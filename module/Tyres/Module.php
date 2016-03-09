<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tyres;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
//models
use Tyres\Model\Tyre;
use Tyres\Model\TyreTable;
use Tyres\Model\Brand;
use Tyres\Model\BrandTable;
use Tyres\Model\Model;
use Tyres\Model\ModelTable;
use Tyres\Model\Provider;
use Tyres\Model\ProviderTable;
use Tyres\Model\Price;
use Tyres\Model\PriceTable;

//db 
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGatewayFeature;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        \Zend\Paginator\Paginator::setDefaultItemCountPerPage(12);
    }

    public function getConfig() {
        return include __DIR__.'/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'TyresModelTyreTable' => function($sm) {
                    return new TyreTable($sm->get('TyreTableGateway'));
                },
                'TyreTableGateway' => function ($sm) {
                    return new TableGateway('tyres', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Tyre())
                    );
                },
                    
                'TyresModelBrandTable' => function($sm) {
                    return new BrandTable($sm->get('BrandTableGateway'));
                },
                'BrandTableGateway' => function ($sm) {
                    return new TableGateway('tyres_brands', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Brand())
                    );
                },
                'TyresModelModelTable' => function($sm) {
                    return new ModelTable($sm->get('ModelTableGateway'));
                },
                'ModelTableGateway' => function ($sm) {
                    return new TableGateway('tyres_models', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Model())
                    );
                },
                    
                    
                'TyresModelProviderTable' => function($sm) {
                    return new ProviderTable($sm->get('ProviderTableGateway'));
                },
                'ProviderTableGateway' => function ($sm) {
                    return new TableGateway('tyres_providers', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null, 
                        new ResultSet(null, new Provider())
                    );
                },
                'TyresModelPriceTable' => function($sm) {
                    return new PriceTable($sm->get('PriceTableGateway'));
                },
                'PriceTableGateway' => function ($sm) {
                    return new TableGateway('tyres_prices', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Price())
                    );
                },
            ),
        );
    }

}
