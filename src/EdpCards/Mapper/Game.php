<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class Game extends AbstractDbMapper
{
    protected $tableName  = 'game';

    public function findActiveGames()
    {
        $select = $this->getSelect()
                       ->where(array('status' => 'active'));

        return $this->select($select);
    }

    public function findById($gameId)
    {
        $select = $this->getSelect()
                       ->where(array('id' => $gameId));

        return $this->select($select);
    }

    public function insertGame($name)
    {
        $game = clone $this->getEntityPrototype();
        $game->setName($name);
        $result = $this->insert($game);
        $game->setId($result->getGeneratedValue());
        return $game;
    }
}
