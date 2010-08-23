CREATE TABLE community_categories
(
  category_id    INT(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  comic_id       INT(11)       NOT NULL DEFAULT '0',
  category_name  VARCHAR(255)  NOT NULL,
  category_desc  TEXT,
  flags          INT(11)       NOT NULL DEFAULT '0',
  last_post_date INT(11)       NOT NULL DEFAULT '0',
  last_post_user INT(11)       NOT NULL DEFAULT '0',
  post_ct        INT(11)       NOT NULL DEFAULT '0',
  order_id       INT(11)       NOT NULL,
  INDEX(category_name),
  INDEX(order_id),
  INDEX(comic_id)
);

CREATE TABLE community_topics
(
  topic_id       INT(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  category_id    INT(11)       NOT NULL,
  topic_name     VARCHAR(255)  NOT NULL,
  user_id        INT(11)       NOT NULL,
  last_user_id   INT(11)       NOT NULL,
  date_created   INT(11)       NOT NULL,
  last_post_date INT(11)       NOT NULL,
  post_ct        INT(11)       NOT NULL DEFAULT '0',
  flags          INT(11)       NOT NULL,
  sticky         TINYINT       NOT NULL DEFAULT '0',
  INDEX(topic_name),
  INDEX(user_id),
  INDEX(category_id),
  INDEX(last_post_date)
);

CREATE TABLE community_posts
(
  post_id       INT(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  topic_id      INT(11)       NOT NULL,
  user_id       INT(11)       NOT NULL,
  post_body     TEXT          NOT NULL,
  date_created  INT(11)       NOT NULL,
  last_edited    INT(11)       NOT NULL DEFAULT '0',
  flags         INT(11)       NOT NULL DEFAULT '0',
  index(user_id),
  INDEX(topic_id),
  FULLTEXT(post_body)
);

CREATE TABLE `community_sessions` (
  `username` VARCHAR(20) NOT NULL default '0',
  `encoded_data` blob,
  PRIMARY KEY  (`username`)
);