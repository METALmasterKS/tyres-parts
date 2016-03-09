<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Tyres\Controller\Index' => 'Tyres\Controller\IndexController',
            'Tyres\Controller\Brands' => 'Tyres\Controller\BrandsController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__.'/../view',
        ),
    ),
);
