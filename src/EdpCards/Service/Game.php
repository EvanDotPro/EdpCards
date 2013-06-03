<?php
namespace EdpCards\Service;

use Zend\ServiceManager as SM;
use \Traversable;

class Game implements SM\ServiceLocatorAwareInterface
{
    protected $gameMapper;

    protected $playerMapper;

    protected $cardMapper;

    /**
     * @var SM\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @return EdpCards\Entity\Game
     */
    public function createGame($name, $decks, $playerId)
    {
        $game = $this->getGameMapper()->insertGame($name);
        $this->getCardMapper()->copyDecksToGame($game->getId(), $decks);
        $this->joinGame($game->getId(), $playerId, false);
        $this->startRound($game->getId());
        return $this->getGame($game->getId()); // not very efficient, but oh well
    }

    /**
     * @param string $displayName
     * @param string $email
     */
    public function createPlayer($displayName, $email)
    {
        $email = strtolower($email);
        if ($player = $this->getPlayerMapper()->findPlayerByEmail($email)) {
            $player->setDisplayName($displayName);
            $this->getPlayerMapper()->updatePlayer($player);
            return $player;
        }
        return $this->getPlayerMapper()->insertPlayer($displayName, $email);
    }

    /**
     * @return EdpCards\Entity\Game[]
     */
    public function getActiveGames()
    {
        $games = $this->getGameMapper()->findActiveGames();

        foreach ($games as $game) {
            $players = $this->getPlayerMapper()->findPlayersByGame($game->getId());
            $game->setPlayers($players);
        }

        return $games;
    }

    public function getRoundInfo($gameId, $roundId = null)
    {
        if (!$roundId) {
            $currentRound = $this->getGameMapper()->getCurrentRound($gameId);
        } else {
            // todo: specific rounds
        }

        $data = array(
            'black_card' => $this->getCardMapper()->findById($currentRound['card_id']),
        );

        return $data;
    }

    /**
     * @return EdpCards\Entity\Game
     */
    public function getGame($gameId)
    {
        $game = $this->getGameMapper()->findById($gameId);
        $players = $this->getPlayerMapper()->findPlayersByGame($game->getId());
        $game->setPlayers($players);
        return $game;
    }

    /**
     * @return array
     */
    public function getDecks()
    {
        return $this->getCardMapper()->getDecks();
    }

    /**
     * @return EdpCards\Entity\Player
     */
    public function getPlayer($playerId)
    {
        return $this->getPlayerMapper()->findPlayerById($playerId);
    }

    /**
     * @return EdpCards\Entity\Player
     */
    public function getPlayerWithCards($gameId, $playerId)
    {
        $player = $this->getPlayerMapper()->findPlayerById($playerId);
        $cards = $this->getCardMapper()->findCardsByGameAndPlayer($gameId, $playerId);
        $player->setCards($cards);
        return $player;
    }

    /**
     * @return EdpCards\Entity\Player[]
     */
    public function getPlayersInGame($gameId = null)
    {
        if (!$gameId) {
            return $this->getPlayerMapper()->findPlayersInActiveGames();
        }
        return $this->getPlayerMapper()->findPlayersByGame($gameId);
    }

    /**
     * @return EdpCards\Entity\Player
     */
    public function joinGame($gameId, $playerId, $dealCards = true)
    {
        $players = $this->getPlayerMapper()->findPlayersByGame($gameId, $playerId);
        if (count($players)) return $players->current(); // slightly messy to use current() here
        $player = $this->getPlayerMapper()->insertPlayerToGame($gameId, $playerId);
        if ($dealCards) {
            // @TODO: Check the current black card in play and see if we need 10 or 12 cards
            $this->getCardMapper()->dealCardsToPlayer($gameId, [$player->getId()], 10);
        }

        return $player;
    }

    public function startRound($gameId) // should it be protected? maybe...
    {
        // @TODO Pick winner, close previous round
        $blackCard = $this->getCardMapper()->pickBlackCard($gameId);
        $players = $this->getPlayersInGame($gameId);
        $this->getGameMapper()->insertRound($gameId, $blackCard->getId(), null); // @TODO: pick a judge
        $playerIds = array();
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

    /**
     * Set service locator
     *
     * @param SM\ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(SM\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return SM\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
