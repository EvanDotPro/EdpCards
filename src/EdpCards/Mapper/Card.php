<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;

class Card extends AbstractDbMapper
{
    protected $tableName  = 'card';

    public function findByDecks($decks)
    {
        $select = $this->getSelect()
            ->where(array('deck' => $decks));

        return $this->select($select);
    }

    public function copyDecksToGame($gameId, $decks)
    {
        $select = $this->getSelect()
            ->columns(array(
                'game_id' => new Expression( (string) $gameId),
                'card_id' => 'id',
            ))
            ->where(array('deck' => $decks));
        $select = $select->getSqlString($this->getDbAdapter()->getPlatform());
        $insert = 'INSERT INTO `game_card` (`game_id`, `card_id`) ' . $select . ';';
        $result = $this->getDbAdapter()->query($insert, Adapter::QUERY_MODE_EXECUTE);
    }
}
