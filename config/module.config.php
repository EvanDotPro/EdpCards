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
            'EdpCards\Controller\Rounds' => 'EdpCards\Controller\RoundsController',
            'EdpCards\Controller\Decks' => 'EdpCards\Controller\DecksController',
        ],
    ],
    'router' => [
        'routes' => [
            'rest' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/api',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'decks' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/decks',
                            'defaults' => [
                                'controller' => 'EdpCards\Controller\Decks',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'players' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/players[/:player_id]',
                            'defaults' => [
                                'controller' => 'EdpCards\Controller\Players',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'games' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/games[/:game_id]',
                            'defaults' => [
                                'controller' => 'EdpCards\Controller\Games',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'rounds'  => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/rounds[/:round_id]',
                                    'defaults' => [
                                        'controller' => 'EdpCards\Controller\Rounds',
                                    ],
                                ],
                            ],
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
