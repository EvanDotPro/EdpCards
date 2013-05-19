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
            'EdpCards\Controller\Games' => 'EdpCards\Controller\GamesController',
            'EdpCards\Controller\Players' => 'EdpCards\Controller\PlayersController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'rest' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'games' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'games[/:game_id]',
                            'defaults' => array(
                                'controller' => 'EdpCards\Controller\Games',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'players' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/players[/:player_id]',
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
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
