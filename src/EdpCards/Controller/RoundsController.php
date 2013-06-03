<?php
namespace EdpCards\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class RoundsController extends AbstractRestfulController
{
    protected $identifierName = 'round_id';

    public function getList()
    {
        $games = $this->getGameService()->getCurrentRound($this->params('game_id'));

        return new JsonModel($games);
    }

    public function get($id)
    {
        if ($id == 'latest') {
            $round = $this->getGameService()->getRoundInfo($this->params('game_id'));
        }

        return $this->jsonModel($round);
    }

    public function create($data)
    {
        $this->getGameService()->startRound($this->params('game_id'));
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
