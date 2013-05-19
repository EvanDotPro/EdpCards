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
        $game = $this->getGameService()->getGame($id);
        echo json_serialize($game);die();

        return new JsonModel($game);
    }

    /**
     * Create a new resource
     *
     * @param  mixed $data
     * @return mixed
     */
    public function create($data)
    {
        //TODO: Implement Method
    }

    /**
     * Update an existing resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return mixed
     */
    public function update($id, $data)
    {
        //TODO: Implement Method
    }

    /**
     * Delete an existing resource
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        //TODO: Implement Method
    }
}
