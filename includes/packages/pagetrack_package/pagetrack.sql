CREATE TABLE `global_pageviews` (
  `ymd_date`   int(8)     NOT NULL default '0',
  `hour`       int(2)     NOT NULL default '0',
  `counter`    int(11)    NOT NULL default '0',
  `multiplier` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ymd_date`,`hour`)
) TYPE=MyISAM;


CREATE TABLE `unique_tracking` (
  `year_counter`  int(4) NOT NULL default '0',
  `month_counter` int(2) NOT NULL default '0',
  `day_counter`   int(2) NOT NULL default '0',
  `year_date`     int(4) NOT NULL default '0',
  `month_date`    int(2) NOT NULL default '0',
  `day_date`      int(2) NOT NULL default '0',
  PRIMARY KEY  (`year_date`,`month_date`,`day_date`)
) TYPE=MyISAM;

CREATE TABLE `page_statistics` (
  `file_name` varchar(255) NOT NULL default '',
  `page_title` varchar(255) NOT NULL default '',
  `load_time` float NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `multiplier` int(11) NOT NULL default '0',
  `query_count` int(11) NOT NULL default '0',
  `query_time` float NOT NULL default '0',
  `ymd_date` int(11) NOT NULL default '0',
  PRIMARY KEY(`file_name`, `ymd_date`),
  KEY `load_time` (`load_time`),
  KEY `views` (`views`),
  KEY `query_count` (`query_count`),
  KEY `query_time` (`query_time`)
) TYPE=MyISAM;
