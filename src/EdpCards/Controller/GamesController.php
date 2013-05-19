<?php
namespace EdpCards\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use EdpCards\Service\GameServiceAwareTrait;

class GamesController extends AbstractRestfulController
{
    use GameServiceAwareTrait;

    protected $identifierName = 'game_id';

    public function getList()
    {
        $games = $this->getGameService()->getActiveGames();

        return new JsonModel($games);
    }

    public function get($id)
    {
        $game = $this->getGameService()->getGame($id);

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
        $game = $this->getGameService()->createGame($data['name'], $data['decks']);
        $this->getResponse()->setStatusCode(201);
        return new JsonModel;
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
