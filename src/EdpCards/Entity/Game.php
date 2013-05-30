<?php
namespace EdpCards\Entity;

class Game extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Player[]
     */
    protected $players = array();

    /**
     * @var int
     */
    protected $playerCount;

    /**
     * @var array
     */
    protected $decks;

    /**
     * @param int $id
     * @return Game
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array|Traversable
     * @return Game
     */
    public function setPlayers($players)
    {
        $this->players = $players;
        return $this;
    }

    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param int $playerCount
     * @return Game
     */
    public function setPlayerCount($playerCount)
    {
        $this->playerCount = (int) $playerCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlayerCount()
    {
        return $this->playerCount;
    }

    /**
     * @return array
     */
    public function getDecks()
    {
        return $this->decks;
    }

    /**
     * @param array $decks
     * @return Game
     */
    public function setDecks($decks)
    {
        $this->decks = $decks;
        return $this;
    }
}
