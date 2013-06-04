<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ArraySerializable;

class Card extends AbstractDbMapper
{
    protected $tableName  = 'card';

    protected $deckDescriptions = array(
        'v2' => 'Latest version of the standard CAH deck',
        'php' => 'Funny PHP-related add-on cards',
    );

    public function getDecks()
    {
        $select = $this->getSelect()
                       ->columns(array('id' => 'deck', 'count' => new Expression('COUNT(1)')))
                       ->where(array('enabled' => 1))
                       ->group('deck');
        $decks = $this->select($select, new \ArrayObject, new ArraySerializable)->toArray();

        foreach ($decks as $i => $deck) {
            $decks[$i]['count'] = (int) $decks[$i]['count'];
            $decks[$i]['description'] = isset($this->deckDescriptions[$deck['id']]) ? $this->deckDescriptions[$deck['id']] : null;
        }

        return $decks;
    }

    public function findById($cardId)
    {
        $select = $this->getSelect()
                       ->where(array('id' => $cardId));
        return $this->select($select)->current();
    }

    public function findByDecks($decks)
    {
        $select = $this->getSelect()
            ->where(array('deck' => $decks));

        return $this->select($select);
    }

    public function findCardsByGameAndPlayer($gameId, $playerId)
    {
        $select = $this->getSelect('game_card')
                       ->join('card', 'card.id = game_card.card_id')
                       ->where(array('game_card.game_id' => $gameId, 'game_card.player_id' => $playerId));
        $cards = $this->select($select);
        return $cards;
    }

    public function copyDecksToGame($gameId, $decks)
    {
        $select = $this->getSelect()
            ->columns(array(
                'game_id' => new Expression( (string) $gameId),
                'card_id' => 'id',
            ))
            ->where(array('enabled' => 1, 'deck' => $decks));
        $select = $select->getSqlString($this->getDbAdapter()->getPlatform());
        $insert = 'INSERT INTO `game_card` (`game_id`, `card_id`) ' . $select . ';';
        $result = $this->getDbAdapter()->query($insert, Adapter::QUERY_MODE_EXECUTE);
    }

    public function dealCardsToPlayer($gameId, $playerIds, $numberOfCards = 10)
    {
        foreach ($playerIds as $playerId) {
            $select = $this->getSelect('game_card')
                ->columns(array('count' => new Expression('COUNT(1)')))
                ->where(array('game_id' => $gameId, 'player_id' => $playerId));
            $cardsInHand = $this->select($select, new \ArrayObject, new ArraySerializable)->current();
            $cardsInHand = (int) $cardsInHand['count'];

            $cardsToDeal = $numberOfCards - $cardsInHand;

            $select = $this->getSelect('game_card')
                ->join('card', 'card.id = game_card.card_id')
                ->where(array('game_card.game_id' => $gameId, 'game_card.status' => 'available', 'card.type' => 'white'))
                ->limit($cardsToDeal)
                ->order(new Expression('RAND()')); // TODO: This might get slow once this table is large
            $results = $this->select($select, new \ArrayObject, new ArraySerializable)->toArray();

            if (!count($results)) return;

            $cardsToAssign = array();
            foreach ($results as $result) {
                $cardsToAssign[] = (int) $result['card_id'];
            }

            $where = array(
                'card_id' => $cardsToAssign,
                'game_id' => $gameId,
                'status'  => 'available',
            );
            $data = array(
                'player_id' => $playerId,
                'status'    => 'player',
            );
            $this->update($data, $where, 'game_card');
        }
    }

    public function playerHasCards($gameId, $playerId, $cardIds)
    {
        $select = $this->getSelect('game_card')
            ->where(array(
                'game_id'   => $gameId,
                'player_id' => $playerId,
                'card_id'   => $cardIds
            ));
        $results = $this->select($select, new \ArrayObject, new ArraySerializable)->toArray();

        return (count($cardIds) === count($results));
    }

    public function findCardsInRound($roundId, $playerId)
    {
        $select = $this->getSelect('game_round_card')
                       ->join('card', 'game_round_card.card_id = card.id')
                       ->where(array('game_round_card.round_id' => $roundId, 'game_round_card.player_id' => $playerId))
                       ->order('game_round_card.sort ASC');
        return $this->select($select);
    }

    public function markCardsAsUsed($gameId, $cardIds, $playerId)
    {
            $where = array(
                'game_id'   => $gameId,
                'card_id'   => $cardIds,
                'player_id' => $playerId
            );
            $data = array(
                'player_id' => null,
                'status'    => 'used',
            );

            return $this->update($data, $where, 'game_card');
    }

    public function pickBlackCard($gameId)
    {
        // TODO: Check if there are no more black cards, and re-shuffle
        $select = $this->getSelect('game_card')
            ->join('card', 'card.id = game_card.card_id')
            ->where(array('game_card.game_id' => $gameId, 'game_card.status' => 'available', 'card.type' => 'black', 'card.enabled' => 1))
            ->limit(1)
            ->order(new Expression('RAND()'));
        $card = $this->select($select)->current();

        $where = array(
            'game_id' => $gameId,
            'card_id' => $card->getId(),
        );

        $this->update(array('status' => 'used'), $where, 'game_card');

        return $card;
    }
}
