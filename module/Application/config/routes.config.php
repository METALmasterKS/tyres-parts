<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' => 'Index',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'default' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => 'app[/:controller[/:action]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                    ),
                ),
            ),
            'tyres' => [
                'type' => 'Literal',
                'options' => [
                    'route' => 'tyres',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Tyres\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'search' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '[/:season][/:brandName[/model/:modelName]][/:width][/:height][/R:diameter][/:spikes][/last[/:lastId]]',
                            'constraints' => array(
                                'season' => 'summer|winter|allseason',
                                'brandName' => '(%[0-9A-Z%]+)|([A-Za-z]{3,}[^\/]*)',
                                'width' => '[0-9]{3}',
                                'height' => '[0-9]{2}',
                                'diameter' => '[0-9]{2}[C]?',
                                'modelName' => '[^\/]*',
                                'spikes' => '^(spikes|unspikes)$',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'index',
                                'lastId' => '',
                            ),
                        ],
                    ],
                    'tyre' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/model/:brandName/:modelName[/:width/:height/R:diameter][/:spikes]',
                            'constraints' => array(
                                'brandName' => '(%[0-9A-Z%]+)|([A-Za-z]{3,}[^\/]*)',
                                'modelName' => '[^\/]+',
                                'width' => '[0-9]{3}',
                                'height' => '[0-9]{2}',
                                'diameter' => '[0-9]{2}[C]?',
                                'spikes' => '^(spikes|unspikes)$',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'tyre',
                            ),
                        ],
                    ],
                    'getbrandmodels' => [
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/getbrandmodels',
                            'defaults' => array(
                                'action' => 'getbrandmodels',
                            ),
                        ),
                    ],
                    'brands' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/brands',
                            'defaults' => array(
                                'controller' => 'Brands',
                                'action' => 'index',
                            ),
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'brand' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '[/:name[/last/[:lastId]]]',
                                    'defaults' => array(
                                        'controller' => 'Brands',
                                        'action' => 'brand',
                                        'lastId' => '',
                                    ),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'cart' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => 'cart',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cart\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:controller[/:action][/id/[:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'content' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => 'page',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Content\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'section' => [
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 's/:alias',
                            'constraints' => array(
                                'alias'     => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Content\Controller',
                                'controller' => 'Index',
                                'action' => 'index',
                            ),
                        ),
                    ],
                    'page' => [
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:alias',
                            'constraints' => array(
                                'alias'     => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Content\Controller',
                                'controller' => 'Index',
                                'action' => 'text',
                            ),
                        ),
                    ],
                ),
            ),
            
            
        ),
    ),
    
);
