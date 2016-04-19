<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Content;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
//models
use Content\Model\Text;
use Content\Model\TextTable;
use Content\Model\Section;
use Content\Model\SectionTable;

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
        
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'navigationTree'), 99);
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
                'ContentModelTextTable' => function($sm) {
                    return new TextTable($sm->get('TextTableGateway'));
                },
                'TextTableGateway' => function ($sm) {
                    return new TableGateway('content_texts', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Text())
                    );
                },
                    
                'ContentModelSectionTable' => function($sm) {
                    return new SectionTable($sm->get('SectionTableGateway'));
                },
                'SectionTableGateway' => function ($sm) {
                    return new TableGateway('content_sections', $sm->get('Zend\Db\Adapter\Adapter'), 
                        null,
                        new ResultSet(null, new Section())
                    );
                },
                
            ),
        );
    }
    
    public function navigationTree($e) {
        new \Content\Navigation\Content($e->getApplication()->getServiceManager());
    }

}
