<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        \Zend\Navigation\Page\Mvc::setDefaultRouter ($e->getRouter());
        
        \Zend\Validator\AbstractValidator::setDefaultTranslator($e->getApplication()->getServiceManager()->get('Translator'));
        
        \Locale::setDefault('ru_RU');
        
        $this->getSettings($e->getApplication());
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getSettings($application){
        $config = $application->getServiceManager()->get('config');
        $settings = $config['settings'];
        $viewModel = $application->getMvcEvent()->getViewModel();
        $viewModel->settings = $settings;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
