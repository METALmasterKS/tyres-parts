<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

$routes = include __DIR__.'/routes.config.php';
$navigation = include __DIR__.'/navigation.config.php';

return array(
    'settings' => array(
        'mail' => [
            'managers' => [
                'metalmaster@mail.ru',
                //'sahr_gl@mail.ru', 'raceta812@mail.ru',
            ],
        ],
    ),
    
    'router' => array(
        'routes' => $routes
    ),
    
    'navigation' => $navigation, 
    
    'service_manager' => array(
        'abstract_factories' => array(
            
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'ru_RU',
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'RoundPrice'        => 'Application\Controller\Plugin\RoundPrice',
            'Referer'           => 'Application\Controller\Plugin\Referer',
            'NavigationTree'           => 'Application\Controller\Plugin\NavigationTree',
        )
    ),
    'view_manager' => array(
        'base_path' => '/',
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
  
);
