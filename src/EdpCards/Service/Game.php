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
        $player = $this->joinGame($game->getId(), $displayName, $email, false);
        $this->startRound($game->getId());
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
    public function joinGame($gameId, $displayName, $email = false, $dealCards = true)
    {
        $player = $this->getPlayerMapper()->insertPlayer($gameId, $displayName, $email);
        if ($dealCards) {
            $this->getCardMapper()->dealCardsToPlayer($gameId, [$player->getId()], 10);
        }

        return $player;
    }

    protected function startRound($gameId)
    {
        $blackCard = $this->getCardMapper()->pickBlackCard($gameId);
        $players = $this->getPlayersInGame($gameId);
        $this->getGameMapper()->insertRound($gameId, $blackCard->getId(), null); // @TODO: pick a judge
        $playerIds = [];
        foreach ($players as $player) {
            $playerIds[] = $player->getId();
        }
        // @TODO: If a player skips a round with 12 cards (draw 2, pick 3), they'll still have 12 cards.
        $cardsToDeal = ($blackCard->getBlankCount() === 3) ? 12 : 10;
        $this->getCardMapper()->dealCardsToPlayer($gameId, $playerIds, $cardsToDeal);
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
