<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\ServiceManager as SM;
use Zend\Db\Sql\Expression;
use Zend\Stdlib\Hydrator\ArraySerializable;

class Game extends AbstractDbMapper implements SM\ServiceLocatorAwareInterface
{
    /**
     * @var SM\ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    protected $tableName  = 'game';

    protected $playerMapper;

    public function findActiveGames()
    {
        $select = $this->getSelect()
                       ->columns(array('id', 'name', 'player_count' => new Expression('COUNT(game_player.player_id)')))
                       ->join('game_player', 'game_player.game_id = game.id', array())
                       ->group('game.id')
                       ->where(array('game.status' => 'active'));

        return $this->resultSetToArray($this->select($select));
    }

    public function findById($gameId)
    {
        $select = $this->getSelect()
                       ->columns(array('id', 'name', 'player_count' => new Expression('COUNT(game_player.player_id)')))
                       ->join('game_player', 'game_player.game_id = game.id', array())
                       ->group('game.id')
                       ->where(array('id' => $gameId));
        $game = $this->select($select)->current();

        return $game;
    }

    public function insertGame($name)
    {
        $game = clone $this->getEntityPrototype();
        $game->setName($name);
        $result = $this->insert($game);
        $game->setId($result->getGeneratedValue());

        return $game;
    }

    public function getCurrentRound($gameId)
    {
        $select = $this->getSelect('game_round')
             ->where(array('game_id' => $gameId))
             ->order('id DESC')
             ->limit(1);

        return $this->select($select, new \ArrayObject, new ArraySerializable)->current();
    }

    public function insertRound($gameId, $blackCardId, $judgeId)
    {
        $data = array(
            'game_id'  => $gameId,
            'card_id'  => $blackCardId,
            'judge_id' => $judgeId,
        );

        $this->insert($data, 'game_round');
    }

    protected function getPlayerMapper()
    {
        if (!$this->playerMapper) {
            $this->playerMapper = $this->getServiceLocator()->get('edpcards_playermapper');
        }

        return $this->playerMapper;
    }

    protected function resultSetToArray($resultSet)
    {
        $return = array();

        foreach ($resultSet as $result) {
            $return[] = $result;
        }

        return $return;
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
