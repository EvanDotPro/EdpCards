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
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}
