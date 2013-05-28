SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('white','black') NOT NULL,
  `deck` enum('other','v2','php') NOT NULL DEFAULT 'other',
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `deck` (`deck`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','archived') NOT NULL DEFAULT 'active',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `game_card` (
  `game_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `player_id` int(11) DEFAULT NULL,
  `status` enum('available','player','used') NOT NULL DEFAULT 'available',
  UNIQUE KEY `game_id` (`game_id`,`card_id`),
  KEY `player_id` (`player_id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `game_player` (
  `player_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  KEY `player_id` (`player_id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `game_round` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `judge_id` int(11) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `judge_id` (`judge_id`),
  KEY `card_id` (`card_id`),
  KEY `winner_id` (`winner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `game_card`
  ADD CONSTRAINT `game_card_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_card_ibfk_6` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_card_ibfk_7` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE;

ALTER TABLE `game_player`
  ADD CONSTRAINT `game_player_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_player_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE;

ALTER TABLE `game_round`
  ADD CONSTRAINT `game_round_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_round_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`),
  ADD CONSTRAINT `game_round_ibfk_5` FOREIGN KEY (`winner_id`) REFERENCES `player` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `game_round_ibfk_6` FOREIGN KEY (`judge_id`) REFERENCES `player` (`id`) ON DELETE SET NULL;
