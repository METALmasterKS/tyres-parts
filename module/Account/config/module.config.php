<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'account' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/account',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Account\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:controller[/:action][/sub/[:sub]][/page/[:page]][/id/[:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'sub' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'page' => '',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Account\Controller\Index'      => 'Account\Controller\IndexController',
            'Account\Controller\Orders'     => 'Account\Controller\OrdersController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'account' => __DIR__.'/../view',
        ),
        'template_map' => array(
            'layout/tabs' => __DIR__ . '/../view/layout/tabs.phtml',
        ),
    ),

);
