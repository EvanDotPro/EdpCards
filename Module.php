<?php
namespace EdpCards;

use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\ClassMethods;

class Module
{
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'edpcards_gamemapper' => function($sm) {
                    $mapper = new Mapper\Game;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Game);
                    $hydrator = new ClassMethods;
                    $hydrator->addFilter('getPlayers', new MethodMatchFilter('getPlayers'), FilterComposite::CONDITION_AND);
                    $hydrator->addFilter('getPlayerCount', new MethodMatchFilter('getPlayerCount'), FilterComposite::CONDITION_AND);
                    $mapper->setHydrator($hydrator);
                    return $mapper;
                },
                'edpcards_playermapper' => function($sm) {
                    $mapper = new Mapper\Player;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Player);
                    return $mapper;
                },
                'edpcards_cardmapper' => function($sm) {
                    $mapper = new Mapper\Card;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Card);
                    return $mapper;
                },
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
}
