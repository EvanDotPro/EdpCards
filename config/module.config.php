<?php
return [
    'service_manager' => [
        'invokables' => [
            'edpcards_gameservice' => 'EdpCards\Service\Game',
        ],
        'aliases' => [
            'edpcards_db' => 'Zend\Db\Adapter\Adapter',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'EdpCards\Controller\Games' => 'EdpCards\Controller\GamesController',
            'EdpCards\Controller\Players' => 'EdpCards\Controller\PlayersController',
        ],
    ],
    'router' => [
        'routes' => [
            'rest' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'games' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => 'games[/:game_id]',
                            'defaults' => [
                                'controller' => 'EdpCards\Controller\Games',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'players' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/players[/:player_id]',
                                    'defaults' => [
                                        'controller' => 'EdpCards\Controller\Players',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'swagger' => [
        'paths' => [
           __DIR__ . '/../src/EdpCards/Controller',
        ],
    ]
];
