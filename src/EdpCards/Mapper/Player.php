<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class Player extends AbstractDbMapper
{
    protected $tableName = 'player';

    public function findPlayerByEmail($email)
    {
        $select = $this->getSelect()
                       ->where(array('email' => $email));

        return $this->select($select)->current();
    }

    public function findPlayerById($playerId)
    {
        $select = $this->getSelect()
                       ->where(array('id' => $playerId));

        return $this->select($select)->current();
    }

    public function findPlayersByGame($gameId, $playerId = null)
    {
        $select = $this->getSelect()
                       ->join('game_player', 'game_player.player_id = player.id')
                       ->where(array('game_player.game_id' => $gameId));
        if ($playerId) {
            $select->where(array('player.id' => $playerId));
        }

        return $this->select($select);
    }

    public function findPlayersInActiveGames()
    {
        $select = $this->getSelect()
                       ->join('game_player', 'game_player.player_id = player.id')
                       ->join('game', 'game.id = game_player.game_id')
                       ->where(array('game.status' => 'active'))
                       ->group('player.id');

        return $this->select($select);
    }

    public function findPlayersInRound($roundId)
    {
        $select = $this->getSelect('game_round_card')
                       ->join('player', 'game_round_card.player_id = player.id')
                       ->where(array('game_round_card.round_id' => $roundId))
                       ->group('player.id');
        return $this->select($select);
    }

    public function insertPlayer($displayName, $email)
    {
        $player = clone $this->getEntityPrototype();
        $player->setDisplayName($displayName);
        $player->setEmail($email);
        $result = $this->insert($player);
        $player->setId($result->getGeneratedValue());
        return $player;
    }

    public function updatePlayer($player)
    {
        $this->update($player, array('email' => $player->getEmail()));
    }

    public function insertPlayerToGame($gameId, $playerId)
    {
        $this->insert(array('game_id' => $gameId, 'player_id' => $playerId), 'game_player');
        return $this->findPlayerById($playerId);
    }
}
