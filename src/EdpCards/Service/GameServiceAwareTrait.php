<?php
namespace EdpCards\Service;

trait GameServiceAwareTrait
{
    protected $gameService;

    protected function getGameService()
    {
        if (!$this->gameService) {
            $this->gameService = $this->getServiceLocator()->get('edpcards_gameservice');
        }

        return $this->gameService;
    }
}
