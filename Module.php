<?php
namespace EdpCards;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'edpcards_gamemapper' => function($sm) {
                    $mapper = new \EdpCards\Mapper\Game;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new \EdpCards\Entity\Game);
                    return $mapper;
                },
                'edpcards_playermapper' => function($sm) {
                    $mapper = new \EdpCards\Mapper\Player;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new \EdpCards\Entity\Player);
                    return $mapper;
                },
                'edpcards_cardmapper' => function($sm) {
                    $mapper = new \EdpCards\Mapper\Card;
                    $mapper->setDbAdapter($sm->get('edpcards_db'));
                    $mapper->setEntityPrototype(new \EdpCards\Entity\Card);
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
