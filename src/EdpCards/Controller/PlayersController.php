<?php
namespace EdpCards\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use EdpCards\Service\GameServiceAwareTrait;

class PlayersController extends AbstractRestfulController
{
    use GameServiceAwareTrait;
    use HydratorAwareTrait;

    protected $identifierName = 'player_id';

    public function getList()
    {
        $gameId  = $this->params('game_id');
        $players = $this->getGameService()->getPlayersInGame($gameId);

        return $this->jsonModel($players);
    }

    public function get($id)
    {
    }

    public function create($data)
    {
        if (isset($data['email']) && isset($data['display_name'])) {
            $player = $this->getGameService()->createPlayer($data['display_name'], $data['email']);
        }

        if ($this->params('game_id')) {
            if (isset($player) && $player) {
                $playerId = $player->getId();
            } else if (isset($data['player_id'])) {
                $playerId = $data['player_id'];
            } else {
                // error
            }
            $player = $this->getGameService()->joinGame($this->params('game_id'), $playerId);
        }

        $this->getResponse()->setStatusCode(201);
        return $this->jsonModel($player);
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}
