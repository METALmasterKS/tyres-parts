<?php
return array(
    'default' => array(
        'home' => array(
            'id' => 'home', 
            'label' => 'Главная',
            'route' => 'home',
            'pages' => [
                /*
                'about' => [
                    'id' => 'home-about', 
                    'label' => 'О нас',
                    'route' => 'home'
                ],
                */
                
                'tyres' => [
                    'id' => 'home-tyres',
                    'label' => 'Шины',
                    'route' => 'home/tyres',
                    'pages' => [
                        'brands' => [
                            'id' => 'home-tyres-brands',
                            'label' => 'Бренды',
                            'route' => 'home/tyres/brands',
                        ]
                    ]
                ],
                
                /*
                'contacts' => [
                    'id' => 'home-contacts', 
                    'label' => 'Контакты',
                    'route' => 'home/content/section',
                    'params' => [ 'alias' => 'contacts', ],
                ],
                */
            ],
        ),
        'admin' => [
            'label' => 'Админ Панель',
            'id' => 'admin',
            'route' => 'admin',
            'pages' => array(
                'tyres' => array(
                    'id' => 'admin-tyres',
                    'label' => 'Шины',
                    'route' => 'admin/default',
                    'controller' => 'tyres',
                    'action' => 'index',
                    'pages' => array(
                        'brands' => array(
                            'id' => 'admin-tyres-brands',
                            'label' => 'Бренды',
                            'route' => 'admin/default',
                            'controller' => 'brands',
                            'action' => 'index',
                            'pages' => array(
                                'search' => array(
                                    'id' => 'admin-tyres-brand-search',
                                    'label' => 'Поиск',
                                    'route' => 'admin/default',
                                    'controller' => 'brands',
                                    'action' => 'index',
                                    'params' => [ 'sub' => 'search', ],
                                ),
                                'add' => array(
                                    'id' => 'admin-tyres-brand-add',
                                    'label' => 'Добавление бренда',
                                    'route' => 'admin/default',
                                    'controller' => 'brands',
                                    'action' => 'add',
                                ),
                                'edit' => array(
                                    'id' => 'admin-tyres-brand-edit',
                                    'label' => 'Редактирование бренда',
                                    'route' => 'admin/default',
                                    'controller' => 'brands',
                                    'action' => 'edit',
                                ),
                            ),
                        ),
                        array(
                            'label' => 'Поставщики',
                            'route' => 'admin/default',
                            'controller' => 'tyres',
                            'action' => 'providers',
                        ),
                        array(
                            'label' => 'Импорт',
                            'route' => 'admin/default',
                            'controller' => 'tyres',
                            'action' => 'import',
                        ),
                    ),
                ),
                'users' => array(
                    'label' => 'Пользователи',
                    'route' => 'admin/default',
                    'controller' => 'users',
                    'action' => 'index',
                ),
                'orders' => array(
                    'label' => 'Заказы',
                    'route' => 'admin/default',
                    'controller' => 'orders',
                    'action' => 'index',
                ),
                'content' => array(
                    'id' => 'admin-content',
                    'label' => 'Инфо страницы',
                    'route' => 'admin/default',
                    'controller' => 'content',
                    'action' => 'index',
                ),
            ),
        ],
    ),

);
