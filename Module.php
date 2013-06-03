<?php
namespace EdpCards;

use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\ClassMethods;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'edpcards_gamemapper' => function($sm) {
                    $mapper = new Mapper\Game;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Game);
                    $hydrator = new ClassMethods;
                    foreach (array('getPlayers', 'getPlayerCount', 'getDecks', 'getCards') as $method) {
                        $hydrator->addFilter($method, new MethodMatchFilter($method), FilterComposite::CONDITION_AND);
                    }
                    $mapper->setHydrator($hydrator);
                    return $mapper;
                },
                'edpcards_playermapper' => function($sm) {
                    $mapper = new Mapper\Player;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Player);
                    $hydrator = new ClassMethods;
                    $hydrator->addFilter('getEmailHash', new MethodMatchFilter('getEmailHash'), FilterComposite::CONDITION_AND);
                    $hydrator->addFilter('getCards', new MethodMatchFilter('getCards'), FilterComposite::CONDITION_AND);
                    $mapper->setHydrator($hydrator);
                    return $mapper;
                },
                'edpcards_cardmapper' => function($sm) {
                    $mapper = new Mapper\Card;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new Entity\Card);
                    return $mapper;
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
