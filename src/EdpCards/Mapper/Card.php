<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ArraySerializable;

class Card extends AbstractDbMapper
{
    protected $tableName  = 'card';

    public function findByDecks($decks)
    {
        $select = $this->getSelect()
            ->where(['deck' => $decks]);

        return $this->select($select);
    }

    public function copyDecksToGame($gameId, $decks)
    {
        $select = $this->getSelect()
            ->columns([
                'game_id' => new Expression( (string) $gameId),
                'card_id' => 'id',
            ])
            ->where(['deck' => $decks]);
        $select = $select->getSqlString($this->getDbAdapter()->getPlatform());
        $insert = 'INSERT INTO `game_card` (`game_id`, `card_id`) ' . $select . ';';
        $result = $this->getDbAdapter()->query($insert, Adapter::QUERY_MODE_EXECUTE);
    }

    public function dealCardsToPlayer($gameId, $playerIds, $numberOfCards = 10)
    {
        foreach ($playerIds as $playerId) {
            $select = $this->getSelect('game_card')
                ->columns(['count' => new Expression('COUNT(1)')])
                ->where(['game_id' => $gameId, 'player_id' => $playerId]);
            $cardsInHand = (int) $this->select($select, new \ArrayObject, new ArraySerializable)->current()['count'];

            $cardsToDeal = $numberOfCards - $cardsInHand;

            $select = $this->getSelect('game_card')
                ->join('card', 'card.id = game_card.card_id')
                ->where(['game_card.game_id' => $gameId, 'game_card.status' => 'available', 'card.type' => 'white'])
                ->limit($cardsToDeal)
                ->order(new Expression('RAND()')); // TODO: This might get slow once this table is large
            $results = $this->select($select, new \ArrayObject, new ArraySerializable)->toArray();

            if (!count($results)) return;

            $cardsToAssign = [];
            foreach ($results as $result) {
                $cardsToAssign[] = (int) $result['card_id'];
            }

            $where = [
                'card_id' => $cardsToAssign,
                'game_id' => $gameId,
                'status'  => 'available',
            ];
            $data = [
                'player_id' => $playerId,
                'status'    => 'player',
            ];
            $this->update($data, $where, 'game_card');
        }
    }

    public function pickBlackCard($gameId)
    {
        // TODO: Check if there are no more black cards, and re-shuffle
        $select = $this->getSelect('game_card')
            ->join('card', 'card.id = game_card.card_id')
            ->where(['game_card.game_id' => $gameId, 'game_card.status' => 'available', 'card.type' => 'black'])
            ->limit(1)
            ->order(new Expression('RAND()'));
        $card = $this->select($select)->current();

        $where = [
            'game_id' => $gameId,
            'card_id' => $card->getId(),
        ];

        $this->update(['status' => 'used'], $where, 'game_card');

        return $card;
    }
}
