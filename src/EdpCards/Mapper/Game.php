<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\ServiceManager as SM;

class Game extends AbstractDbMapper implements SM\ServiceLocatorAwareInterface
{
    use SM\ServiceLocatorAwareTrait;

    protected $tableName  = 'game';

    public function findActiveGames()
    {
        $select = $this->getSelect()
                       ->where(['status' => 'active']);

        return $this->select($select);
    }

    public function findById($gameId)
    {
        $select = $this->getSelect()
                       ->where(['id' => $gameId]);

        return $this->select($select);
    }

    public function insertGame($name)
    {
        $game = clone $this->getEntityPrototype();
        $game->setName($name);
        $result = $this->insert($game);
        $game->setId($result->getGeneratedValue());
        return $game;
    }

    public function insertRound($gameId, $blackCardId, $judgeId)
    {
        $data = [
            'game_id'  => $gameId,
            'card_id'  => $blackCardId,
            'judge_id' => $judgeId,
        ];

        $this->insert($data, 'game_round');
    }
}
