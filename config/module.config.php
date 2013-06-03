<?php
return array(
    'service_manager' => array(
        'invokables' => array(
            'edpcards_gameservice' => 'EdpCards\Service\Game',
        ),
        'aliases' => array(
            'edpcards_db' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'EdpCards\Controller\Games'   => 'EdpCards\Controller\GamesController',
            'EdpCards\Controller\Players' => 'EdpCards\Controller\PlayersController',
            'EdpCards\Controller\Rounds'  => 'EdpCards\Controller\RoundsController',
            'EdpCards\Controller\Decks'   => 'EdpCards\Controller\DecksController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'rest' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/api',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'decks' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/decks',
                            'defaults' => array(
                                'controller' => 'EdpCards\Controller\Decks',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'games-decks' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/games/decks',
                            'defaults' => array(
                                'controller' => 'EdpCards\Controller\Decks',
                            ),
                        ),
                        'may_terminate' => true,
                        'priority' => 100,
                    ),
                    'players' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/playersarray(/:player_id)',
                            'defaults' => array(
                                'controller' => 'EdpCards\Controller\Players',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'games' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/gamesarray(/:game_id)',
                            'defaults' => array(
                                'controller' => 'EdpCards\Controller\Games',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'rounds'  => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/roundsarray(/:round_id)',
                                    'defaults' => array(
                                        'controller' => 'EdpCards\Controller\Rounds',
                                    ),
                                ),
                            ),
                            'players' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/playersarray(/:player_id)',
                                    'defaults' => array(
                                        'controller' => 'EdpCards\Controller\Players',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'swagger' => array(
        'paths' => array(
           __DIR__ . '/../src/EdpCards/Controller',
        ),
    )
);
