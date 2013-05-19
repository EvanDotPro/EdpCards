<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class Player extends AbstractDbMapper
{
    protected $tableName = 'player';

    public function findPlayersByGame($gameId)
    {
        $select = $this->getSelect()
                       ->where(array('game_id' => $gameId));

        return $this->select($select);
    }

    public function insertPlayer($player)
    {
        $result = $this->insert($player);
        $entity->setId($result->getGeneratedValue());
        return $result;
    }
}
