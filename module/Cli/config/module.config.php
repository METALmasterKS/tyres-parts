<?php

return array(
    'settings' => array(
        'import' => [
            'tyres' => [
                'path' => 'data/import/tyres/',
                'mask' => '*.csv',
            ],
        ],
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'index' => [
                    'options' => array(
                        'route'    => 'index',
                        'defaults' => array(
                            'controller' => 'Cli\Controller\Index',
                            'action'     => 'index'
                        )
                    )
                ],
                'taskManagerDaemon' => [
                    'options' => array(
                        'route'    => 'taskManagerDaemon',
                        'defaults' => array(
                            'controller' => 'Cli\Controller\Index',
                            'action'     => 'taskManagerDaemon'
                        )
                    )
                ],
                'importTyres' => array(
                    'options' => array(
                        'route'    => 'importTyres',
                        'defaults' => array(
                            'controller' => 'Cli\Controller\Import',
                            'action'     => 'Tyres'
                        )
                    )
                ),
                
            )
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Cli\Controller\Index'     => 'Cli\Controller\IndexController',
            'Cli\Controller\Import'     => 'Cli\Controller\ImportController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'CliTaskManager'   => 'Cli\Controller\Plugin\CliTaskManager',
            'GetImportFiles'    => 'Cli\Controller\Plugin\GetImportFiles',
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'log' => array(
        'Log\Console' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'priority' => 1000,
                    'options' => array(
                        'stream' => 'data/logs/console.log',
                    ),
                ),
            ),
        ),
    ),
    
);
