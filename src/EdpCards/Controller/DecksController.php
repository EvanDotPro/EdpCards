<?php
namespace EdpCards\Controller;

class DecksController extends AbstractRestfulController
{
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
