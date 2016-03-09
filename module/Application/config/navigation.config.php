<?php
return array(
    'default' => array(
        'home' => array(
            'id' => 'home', 
            'label' => 'Главная',
            'route' => 'home',
            'pages' => [
                'about' => [
                    'id' => 'home-about', 
                    'label' => 'О нас',
                    'route' => 'home'
                ],
                
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
                
                'parts' => [
                    'id' => 'home-parts', 
                    'label' => 'Запчасти',
                    'route' => 'home'
                ],
                
                'service' => [
                    'id' => 'home-service', 
                    'label' => 'Сервис',
                    'route' => 'home'
                ],
                
                'optionalequipment' => [
                    'id' => 'home-optionalequipment', 
                    'label' => 'Доп. оборудование',
                    'route' => 'home'
                ],
                
                'paymentdelivery' => [
                    'id' => 'home-paymentdelivery', 
                    'label' => 'Оплата и доставка',
                    'route' => 'home'
                ],
                
                'contacts' => [
                    'id' => 'home-contacts', 
                    'label' => 'Контакты',
                    'route' => 'home'
                ],
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
                    'label' => 'Инфо страницы',
                    'route' => 'admin/default',
                    'controller' => 'content',
                    'action' => 'index',
                ),
            ),
        ],
    ),

);
