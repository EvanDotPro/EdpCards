<?php
namespace EdpCards\Entity;

class Player extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string;
     */
    protected $email;

    /**
     * @var string;
     */
    protected $emailHash;

    /**
     * @var int
     */
    protected $points = 0;

    /**
     * @var Card[]
     */
    protected $cards = array();

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Player
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return Player
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Player
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setEmailHash(md5($email));
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailHash()
    {
        return $this->emailHash;
    }

    /**
     * @param string $emailHash
     * @return Player
     */
    public function setEmailHash($emailHash)
    {
        $this->emailHash = $emailHash;
        return $this;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points ?: 0;
    }

    /**
     * @param int $points
     * @return Player
     */
    public function setPoints($points)
    {
        $this->points = (int) $points;
        return $this;
    }

    /**
     * @return Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param $cards
     * @return Player
     */
    public function setCards($cards)
    {
        $this->cards = $cards;
        return $this;
    }
}
