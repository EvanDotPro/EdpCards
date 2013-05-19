CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('white','black') NOT NULL,
  `deck` enum('other','v2') NOT NULL DEFAULT 'other',
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
  `status` enum('available','player','used') NOT NULL DEFAULT 'available',
  UNIQUE KEY `game_id` (`game_id`,`card_id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `player_card` (
  `player_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  UNIQUE KEY `player_id` (`player_id`,`card_id`),
  KEY `player_id_2` (`player_id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `game_card`
  ADD CONSTRAINT `game_card_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_card_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE;

ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE;

ALTER TABLE `player_card`
  ADD CONSTRAINT `player_card_ibfk_2` FOREIGN KEY (`card_id`) REFERENCES `card` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_card_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE;
