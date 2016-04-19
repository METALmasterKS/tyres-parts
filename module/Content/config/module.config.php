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
            'Content\Controller\Index' => 'Content\Controller\IndexController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            //'ContentNavigationTree' => 'Content\Controller\Plugin\ContentNavigationTree',
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
