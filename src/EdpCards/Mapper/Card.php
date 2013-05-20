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

    public function dealCardsToPlayer($gameId, $playerId, $numberOfCards = 1)
    {
        // TODO: Clean this up, use transactions to prevent race conditions
        $select = $this->getSelect('game_card')
            ->join('card', 'card.id = game_card.card_id')
            ->where(array('game_id' => $gameId, 'status' => 'available', 'type' => 'white'));
        $results = $this->select($select, new \ArrayObject, new \Zend\Stdlib\Hydrator\ArraySerializable)->toArray();

        $keys = array_rand($results, $numberOfCards);
        foreach ($keys as $key) {
            print_r($results[$key]);
            $where = array(
                'card_id' => $results[$key]['card_id'],
                'game_id' => $gameId,
                'status' => 'available',
            );
            $data = array(
                'player_id' => $playerId,
                'status' => 'player',
            );

            $this->update($data, $where, 'game_card');
        }
    }
}
