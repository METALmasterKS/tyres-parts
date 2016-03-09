<?php
return array(
    'router' => array(
        'routes' => array(
            'auth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Auth\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    ), 
                ),
                'may_terminate' => false, 
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '[/:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'admin' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/admin',
                            'defaults' => array(
                                'controller' => 'Index',
                                'action' => 'index'
                            ), 
                        ),
                        'may_terminate' => false, 
                        'child_routes' => array(
                            'login' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/login',
                                    'defaults' => array(
                                        'action' => 'index'
                                    )
                                )
                            ),
                            'logout' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/logout',
                                    'defaults' => array(
                                        'action' => 'logout'
                                    )
                                )
                            ),
                        ),
                    ),
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                'controller' => 'User',
                                'action' => 'index'
                            ), 
                        ),
                        'may_terminate' => false, 
                        'child_routes' => array(
                            'login' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/login',
                                    'defaults' => array(
                                        'action' => 'index'
                                    )
                                )
                            ),
                            'logout' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/logout',
                                    'defaults' => array(
                                        'action' => 'logout'
                                    )
                                )
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => 'Auth\Controller\IndexController',
            'Auth\Controller\User' => 'Auth\Controller\UserController',
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'auth/index/index' => __DIR__ . '/../view/auth/index/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
);
