# EdpCards

Version 0.0.1 Created by Evan Coury

## Introduction

This is a simple ZF2 module that exposes a REST server. The REST server enables
playing virtual games of [Cards Against
Humanity](http://www.cardsagainsthumanity.com/).

This project is meant for a conference demonstration and should probably not be
used in production.

## API

Real (Swagger) documentation coming soon.

| Method | Endpoint                  | Description
| ------ | ------------------------- | -------------------------------
| GET    | /decks                    | An array of cards (id, count, description)
| GET    | /players                  | List all players in active games
| GET    | /players/{id}             | Get a specific player
| POST   | /players                  | Create a new player (display\_name, email)
| GET    | /games                    | List all active games
| POST   | /games                    | Create a new game (name, decks[], player\_id)
| GET    | /games/{id}               | Get a specific game (and players)
| GET    | /games/{id}/players       | Get all players in a game
| POST   | /games/{id}/players       | Add/join a player into a game (display\_name, email OR player\_id)
| GET    | /games/{id}/players/{id}  | Get a player with their cards for a particular game
