<?php
namespace EdpCards\Entity;

class Card extends AbstractEntity
{
    protected $id;

    protected $text;

    protected $cardHints = array(
        'What\'s the next superhero/sidekick duo?' => 2,
        'Make a haiku.' => 3,
    );

    /**
     * @param int $id
     * @return Card
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
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Card
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getBlankCount()
    {
        if (isset($this->cardHints[$this->getText()])) return $this->cardHints[$this->getText()];
        return substr_count($this->getText(), '____') ?: 1;
    }
}
