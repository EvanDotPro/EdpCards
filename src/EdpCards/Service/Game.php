<?php
namespace EdpCards\Service;

use Zend\ServiceManager as SM;
use Zend\EventManager as EM;

class Game implements GameInterface, SM\ServiceLocatorAwareInterface, EM\EventManagerAwareInterface
{
    use SM\ServiceLocatorAwareTrait;
    use EM\EventManagerAwareTrait;

    protected $gameMapper;

    protected $playerMapper;

    protected $cardMapper;

    /**
     * @return EdpCards\Entity\Game
     */
    public function createGame($name, $decks, $displayName, $email = false)
    {
        $game = $this->getGameMapper()->insertGame($name);
        $this->getCardMapper()->copyDecksToGame($game->getId(), $decks);
        $player = $this->joinGame($game->getId(), $displayName, $email);
        $this->getCardMapper()->dealCardsToPlayer($game->getId(), $player->getId(), 10);
        return $game;
    }

    /**
     * @return EdpCards\Entity\Game[]
     */
    public function getActiveGames()
    {
        return $this->getGameMapper()->findActiveGames();
    }

    /**
     * @return EdpCards\Entity\Game
     */
    public function getGame($gameId)
    {
        return $this->getGameMapper()->findById($gameId);
    }

    /**
     * @return EdpCards\Entity\Player[]
     */
    public function getPlayersInGame($gameId)
    {
        return $this->getPlayerMapper()->findPlayersByGame($gameId);
    }

    /**
     * @return EdpCards\Entity\Player
     */
    public function joinGame($gameId, $displayName, $email = false)
    {
        $player = $this->getPlayerMapper()->insertPlayer($gameId, $displayName, $email);
        $this->getCardMapper()->dealCardsToPlayer($gameId, $player->getId(), 10);
        return $player;
    }

    protected function getGameMapper()
    {
        if (!$this->gameMapper) {
            $this->gameMapper = $this->getServiceLocator()->get('edpcards_gamemapper');
        }

        return $this->gameMapper;
    }

    protected function getPlayerMapper()
    {
        if (!$this->playerMapper) {
            $this->playerMapper = $this->getServiceLocator()->get('edpcards_playermapper');
        }

        return $this->playerMapper;
    }

    protected function getCardMapper()
    {
        if (!$this->cardMapper) {
            $this->cardMapper = $this->getServiceLocator()->get('edpcards_cardmapper');
        }

        return $this->cardMapper;
    }
}
