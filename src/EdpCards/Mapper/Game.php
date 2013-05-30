<?php
namespace EdpCards\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Zend\ServiceManager as SM;
use Zend\Db\Sql\Expression;

class Game extends AbstractDbMapper implements SM\ServiceLocatorAwareInterface
{
    use SM\ServiceLocatorAwareTrait;

    protected $tableName  = 'game';

    protected $playerMapper;

    public function findActiveGames()
    {
        $select = $this->getSelect()
                       ->columns(['id', 'name', 'player_count' => new Expression('COUNT(game_player.player_id)')])
                       ->join('game_player', 'game_player.game_id = game.id', [])
                       ->group('game.id')
                       ->where(['game.status' => 'active']);

        return $this->select($select);
    }

    public function findById($gameId)
    {
        $select = $this->getSelect()
                       ->columns(['id', 'name', 'player_count' => new Expression('COUNT(game_player.player_id)')])
                       ->join('game_player', 'game_player.game_id = game.id', [])
                       ->group('game.id')
                       ->where(['id' => $gameId]);

        $game = $this->select($select)->current();
        if(!$game)
        	return false;
        
        $players = $this->getPlayerMapper()->findPlayersByGame($game->getId());
        $game->setPlayers($players);
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

    public function insertRound($gameId, $blackCardId, $judgeId)
    {
        $data = [
            'game_id'  => $gameId,
            'card_id'  => $blackCardId,
            'judge_id' => $judgeId,
        ];

        $this->insert($data, 'game_round');
    }

    protected function getPlayerMapper()
    {
        if (!$this->playerMapper) {
            $this->playerMapper = $this->getServiceLocator()->get('edpcards_playermapper');
        }

        return $this->playerMapper;
    }
}
