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
        'prices' => [
            'markup' => 20,
        ],
        'images' => [
            'dir' => 'public/img/',
            'host' => '/img/',
            'tyres' => 'tyres/',
            'models' => 'models/',
            'upload-images' => 'upload/images/',
            'upload-files' => 'upload/files/',
        ],
    ),
    
    'router' => array(
        'routes' => $routes
    ),
    
    'navigation' => $navigation, 
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            //'fileCache' => 'Zend\Cache\Service\StorageCacheFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'ru_RU',
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\CKEditor' => 'Application\Controller\CKEditorController',
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
    'view_helpers' => array(
        'invokables' => array(
            'cut' => 'Application\View\Helper\Cut',
        ),
    ),
    'caches' => array(
        'fileCache' => array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
              //  'cache_dir' =>  __DIR__ . '/../../data/cache/',
                'ttl' => 24*60*60,
            ),
            'plugins' => array(
                'serializer',
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
            ),
        ),
        'memcachedService' => array(
            'adapter' => array(
                'name' => 'memcached'
            ),
            'options' => array(
                'servers' => array(
                    array('host' => '127.0.0.1'),
                    //array('host' => '192.168.0.60')
                ),
            ),
        ),
    ),
    'mail' => array(
        'MailSMTP' => array( 
            'default_sender' => 'partspostavka@bk.ru',
            'alias_sender' => 'Поставка Запчастей',
            'transport' => array(
                'type' => 'smtp',
                'options' => array(
                    'host' => 'smtp.mail.ru',
                    'port' => 465,
                    'connection_class'  => 'login',
                    'connection_config' => array(
                        'username' => 'partspostavka@bk.ru',
                        'password' => 'postavka2016',
                        'ssl' => 'ssl'
                    ),
                ),  
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'ru_RU',
        'translation_files' => array(
			array(
				'type' => 'phpArray',
				'filename' =>  'vendor/zendframework/zendframework/resources/languages/ru/Zend_Validate.php',
				'locale'  => 'ru_RU',
				'text_domain' => 'default',
			),
        ),
    ),
  
);
