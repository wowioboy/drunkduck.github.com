CREATE TABLE game_sessions
(
  session_id   INT(11)     NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username     VARCHAR(20) NOT NULL,
  game_id      INT(11)     NOT NULL,
  time_expired INT(11)     NOT NULL,

  UNIQUE INDEX(username, game_id),
  INDEX(time_expired)
);


CREATE TABLE `game_info` (
  `game_id` int(11) NOT NULL auto_increment,
  `game_version` int(11) NOT NULL default '1',
  `title` varchar(255) default NULL,
  `description` text,
  `width` int(11) NOT NULL default '640',
  `height` int(11) NOT NULL default '480',
  `pts_to_tokens` float NOT NULL default '1',
  `is_live` tinyint(1) default '0',
  `fps` tinyint(4) NOT NULL default '24',
  `php_game_url` varchar(255) default NULL,
  PRIMARY KEY (`game_id`)
);

CREATE TABLE game_plays
(
  date_year   INT(4),
  date_month  TINYINT,
  date_day    TINYINT,
  game_id     INT(11),
  counter     INT(11),
  primary key(date_year, date_month, date_day, game_id)
);

CREATE TABLE game_launches
(
  date_year   INT(4),
  date_month  TINYINT,
  date_day    TINYINT,
  game_id     INT(11),
  counter     INT(11),
  primary key(date_year, date_month, date_day, game_id)
);

CREATE TABLE `highscore_top_100` (
  `game_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) DEFAULT NULL,
  `highscore` int(11) NOT NULL DEFAULT '0',
  `unix_time` int(11) NOT NULL DEFAULT '0',
  KEY `game_id` (`game_id`),
  KEY `unix_time` (`unix_time`)
);

CREATE TABLE `user_highscores` (
  `game_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `highscore` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`game_id`,`username`),
  KEY `username` (`username`)
);