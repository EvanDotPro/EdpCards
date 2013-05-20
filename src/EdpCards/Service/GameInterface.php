<?php
namespace EdpCards\Service;

interface GameInterface
{
    public function createGame($name, $decks, $displayName, $email = false);

    public function getActiveGames();

    public function getGame($gameId);

    public function getPlayersInGame($gameId);

    public function joinGame($gameId, $displayName, $email = false);
}
