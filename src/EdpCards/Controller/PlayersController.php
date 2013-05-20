<?php
namespace EdpCards\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use EdpCards\Service\GameServiceAwareTrait;

class PlayersController extends AbstractRestfulController
{
    use GameServiceAwareTrait;

    protected $identifierName = 'player_id';

    public function getList()
    {
        $gameId  = $this->params('game_id');
        $players = $this->getGameService()->getPlayersInGame($gameId);

        return new JsonModel($players);
    }

    public function get($id)
    {
    }

    public function create($data)
    {
        $email = isset($data['email']) ? $data['email'] : null;
        $gameId  = $this->params('game_id');
        $player = $this->getGameService()->joinGame($gameId, $data['displayName'], $email);
        $this->getResponse()->setStatusCode(201);
        return new JsonModel;
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}
