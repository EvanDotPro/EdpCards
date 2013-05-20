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

    public function insertPlayer($gameId, $displayName, $email)
    {
        $player = clone $this->getEntityPrototype();
        $player->setGameId($gameId);
        $player->setDisplayName($displayName);
        $player->setEmail($email);
        $result = $this->insert($player);
        $player->setId($result->getGeneratedValue());
        return $player;
    }
}
