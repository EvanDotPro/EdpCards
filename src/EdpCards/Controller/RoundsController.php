<?php
namespace EdpCards\Controller;

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
        } else {
            $round = $this->getGameService()->getRoundInfo($this->params('game_id'), $id);
        }

        return $this->jsonModel($round);
    }

    public function create($data)
    {
        $this->getGameService()->startRound($this->params('game_id'));
        $this->getResponse()->setStatusCode(201);
        return new JsonModel;
    }

    public function update($roundId, $data)
    {
        // this is messy -- not really updating a "round" exactly...
        $result = $this->getGameService()->submitAnswers($roundId, $data['player_id'], $data['card_ids'], $this->params('game_id'));
        return new JsonModel();
    }

    public function delete($id)
    {
    }
}
