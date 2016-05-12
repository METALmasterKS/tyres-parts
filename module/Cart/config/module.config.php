<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Cart\Controller\Index' => 'Cart\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__.'/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Cart' => 'Cart\Service\StorageCartFactory',
        ),
    ),
);
