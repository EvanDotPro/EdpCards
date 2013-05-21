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
| GET    | /games                    | List all active games
| POST   | /games                    | Create a new game
| GET    | /games/{id}               | Get a specific game
| GET    | /games/{id}/players       | Get all players in a game
| POST   | /games/{id}/players       | Add/join a player into a game
