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

    public function create($data)
    {
        $email = isset($data['email']) ? $data['email'] : null;
        $game = $this->getGameService()->createGame($data['name'], $data['decks'], $data['displayName'], $email);
        $this->getResponse()->setStatusCode(201);
        // TODO: Return created game entity
        return new JsonModel;
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}
