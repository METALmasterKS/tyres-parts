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
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
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
            'Admin\Controller\Index'   => 'Admin\Controller\IndexController',
            'Admin\Controller\Tyres'   => 'Admin\Controller\TyresController',
            'Admin\Controller\Brands'  => 'Admin\Controller\BrandsController',
            'Admin\Controller\Models'  => 'Admin\Controller\ModelsController',
            'Admin\Controller\Content'  => 'Admin\Controller\ContentController',
            
            'Admin\Controller\Users'   => 'Admin\Controller\UsersController',
            
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'AdminNavigationTree' => 'Admin\Controller\Plugin\AdminNavigationTree',
            'ModelImageCreator' => 'Admin\Controller\Plugin\ModelImageCreator',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/admin' => __DIR__.'/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'admin' => __DIR__.'/../view',
        ),
    ),
    'module_layouts' => array(
        'Admin' => array(
            'default' => 'layout/admin',
        )
    ),
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'AdminNavigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <-- add this
        ),
    ),
    
);
