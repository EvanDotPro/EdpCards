<?php
namespace EdpCards\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use EdpCards\Service\GameServiceAwareTrait;

class DecksController extends AbstractRestfulController
{
    use GameServiceAwareTrait;
    use HydratorAwareTrait;

    public function getList()
    {
        $decks = $this->getGameService()->getDecks();

        return $this->jsonModel($decks);
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
