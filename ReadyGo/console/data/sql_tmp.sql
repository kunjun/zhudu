
		
-- 
-- 表的结构 `chengdu_bus`
-- 

CREATE TABLE `chengdu_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_buscompany`
-- 

CREATE TABLE `chengdu_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_buslinesuffix`
-- 

CREATE TABLE `chengdu_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_bus_station`
-- 

CREATE TABLE `chengdu_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_station`
-- 

CREATE TABLE `chengdu_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanjing_bus`
-- 

CREATE TABLE `nanjing_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_buscompany`
-- 

CREATE TABLE `nanjing_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_buslinesuffix`
-- 

CREATE TABLE `nanjing_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_bus_station`
-- 

CREATE TABLE `nanjing_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_station`
-- 

CREATE TABLE `nanjing_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `wuhan_bus`
-- 

CREATE TABLE `wuhan_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_buscompany`
-- 

CREATE TABLE `wuhan_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_buslinesuffix`
-- 

CREATE TABLE `wuhan_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_bus_station`
-- 

CREATE TABLE `wuhan_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_station`
-- 

CREATE TABLE `wuhan_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `zhuhai_bus`
-- 

CREATE TABLE `zhuhai_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_buscompany`
-- 

CREATE TABLE `zhuhai_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_buslinesuffix`
-- 

CREATE TABLE `zhuhai_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_bus_station`
-- 

CREATE TABLE `zhuhai_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_station`
-- 

CREATE TABLE `zhuhai_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `dongguan_bus`
-- 

CREATE TABLE `dongguan_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_buscompany`
-- 

CREATE TABLE `dongguan_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_buslinesuffix`
-- 

CREATE TABLE `dongguan_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_bus_station`
-- 

CREATE TABLE `dongguan_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_station`
-- 

CREATE TABLE `dongguan_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `haikou_bus`
-- 

CREATE TABLE `haikou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_buscompany`
-- 

CREATE TABLE `haikou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_buslinesuffix`
-- 

CREATE TABLE `haikou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_bus_station`
-- 

CREATE TABLE `haikou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_station`
-- 

CREATE TABLE `haikou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `guiyang_bus`
-- 

CREATE TABLE `guiyang_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_buscompany`
-- 

CREATE TABLE `guiyang_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_buslinesuffix`
-- 

CREATE TABLE `guiyang_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_bus_station`
-- 

CREATE TABLE `guiyang_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_station`
-- 

CREATE TABLE `guiyang_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `guilin_bus`
-- 

CREATE TABLE `guilin_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_buscompany`
-- 

CREATE TABLE `guilin_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_buslinesuffix`
-- 

CREATE TABLE `guilin_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_bus_station`
-- 

CREATE TABLE `guilin_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_station`
-- 

CREATE TABLE `guilin_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanning_bus`
-- 

CREATE TABLE `nanning_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_buscompany`
-- 

CREATE TABLE `nanning_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_buslinesuffix`
-- 

CREATE TABLE `nanning_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_bus_station`
-- 

CREATE TABLE `nanning_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_station`
-- 

CREATE TABLE `nanning_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `shantou_bus`
-- 

CREATE TABLE `shantou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_buscompany`
-- 

CREATE TABLE `shantou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_buslinesuffix`
-- 

CREATE TABLE `shantou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_bus_station`
-- 

CREATE TABLE `shantou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_station`
-- 

CREATE TABLE `shantou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `liuzhou_bus`
-- 

CREATE TABLE `liuzhou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_buscompany`
-- 

CREATE TABLE `liuzhou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_buslinesuffix`
-- 

CREATE TABLE `liuzhou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_bus_station`
-- 

CREATE TABLE `liuzhou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_station`
-- 

CREATE TABLE `liuzhou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanchang_bus`
-- 

CREATE TABLE `nanchang_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_buscompany`
-- 

CREATE TABLE `nanchang_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_buslinesuffix`
-- 

CREATE TABLE `nanchang_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_bus_station`
-- 

CREATE TABLE `nanchang_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_station`
-- 

CREATE TABLE `nanchang_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `changsha_bus`
-- 

CREATE TABLE `changsha_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_buscompany`
-- 

CREATE TABLE `changsha_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_buslinesuffix`
-- 

CREATE TABLE `changsha_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_bus_station`
-- 

CREATE TABLE `changsha_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_station`
-- 

CREATE TABLE `changsha_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		

		
-- 
-- 表的结构 `chengdu_bus`
-- 

CREATE TABLE `chengdu_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_buscompany`
-- 

CREATE TABLE `chengdu_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_buslinesuffix`
-- 

CREATE TABLE `chengdu_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_bus_station`
-- 

CREATE TABLE `chengdu_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `chengdu_station`
-- 

CREATE TABLE `chengdu_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanjing_bus`
-- 

CREATE TABLE `nanjing_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_buscompany`
-- 

CREATE TABLE `nanjing_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_buslinesuffix`
-- 

CREATE TABLE `nanjing_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_bus_station`
-- 

CREATE TABLE `nanjing_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanjing_station`
-- 

CREATE TABLE `nanjing_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `wuhan_bus`
-- 

CREATE TABLE `wuhan_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_buscompany`
-- 

CREATE TABLE `wuhan_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_buslinesuffix`
-- 

CREATE TABLE `wuhan_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_bus_station`
-- 

CREATE TABLE `wuhan_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `wuhan_station`
-- 

CREATE TABLE `wuhan_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `zhuhai_bus`
-- 

CREATE TABLE `zhuhai_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_buscompany`
-- 

CREATE TABLE `zhuhai_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_buslinesuffix`
-- 

CREATE TABLE `zhuhai_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_bus_station`
-- 

CREATE TABLE `zhuhai_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `zhuhai_station`
-- 

CREATE TABLE `zhuhai_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `dongguan_bus`
-- 

CREATE TABLE `dongguan_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_buscompany`
-- 

CREATE TABLE `dongguan_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_buslinesuffix`
-- 

CREATE TABLE `dongguan_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_bus_station`
-- 

CREATE TABLE `dongguan_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `dongguan_station`
-- 

CREATE TABLE `dongguan_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `haikou_bus`
-- 

CREATE TABLE `haikou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_buscompany`
-- 

CREATE TABLE `haikou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_buslinesuffix`
-- 

CREATE TABLE `haikou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_bus_station`
-- 

CREATE TABLE `haikou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `haikou_station`
-- 

CREATE TABLE `haikou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `guiyang_bus`
-- 

CREATE TABLE `guiyang_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_buscompany`
-- 

CREATE TABLE `guiyang_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_buslinesuffix`
-- 

CREATE TABLE `guiyang_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_bus_station`
-- 

CREATE TABLE `guiyang_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guiyang_station`
-- 

CREATE TABLE `guiyang_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `guilin_bus`
-- 

CREATE TABLE `guilin_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_buscompany`
-- 

CREATE TABLE `guilin_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_buslinesuffix`
-- 

CREATE TABLE `guilin_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_bus_station`
-- 

CREATE TABLE `guilin_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `guilin_station`
-- 

CREATE TABLE `guilin_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanning_bus`
-- 

CREATE TABLE `nanning_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_buscompany`
-- 

CREATE TABLE `nanning_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_buslinesuffix`
-- 

CREATE TABLE `nanning_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_bus_station`
-- 

CREATE TABLE `nanning_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanning_station`
-- 

CREATE TABLE `nanning_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `shantou_bus`
-- 

CREATE TABLE `shantou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_buscompany`
-- 

CREATE TABLE `shantou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_buslinesuffix`
-- 

CREATE TABLE `shantou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_bus_station`
-- 

CREATE TABLE `shantou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `shantou_station`
-- 

CREATE TABLE `shantou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `liuzhou_bus`
-- 

CREATE TABLE `liuzhou_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_buscompany`
-- 

CREATE TABLE `liuzhou_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_buslinesuffix`
-- 

CREATE TABLE `liuzhou_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_bus_station`
-- 

CREATE TABLE `liuzhou_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `liuzhou_station`
-- 

CREATE TABLE `liuzhou_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `nanchang_bus`
-- 

CREATE TABLE `nanchang_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_buscompany`
-- 

CREATE TABLE `nanchang_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_buslinesuffix`
-- 

CREATE TABLE `nanchang_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_bus_station`
-- 

CREATE TABLE `nanchang_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `nanchang_station`
-- 

CREATE TABLE `nanchang_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
		
-- 
-- 表的结构 `changsha_bus`
-- 

CREATE TABLE `changsha_bus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(128) NOT NULL,
  `company` varchar(128) DEFAULT NULL,
  `start_time` varchar(32) DEFAULT NULL,
  `end_time` varchar(32) DEFAULT NULL,
  `sequences` varchar(512) NOT NULL,
  `type` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_name` (`bus_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_buscompany`
-- 

CREATE TABLE `changsha_buscompany` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_buslinesuffix`
-- 

CREATE TABLE `changsha_buslinesuffix` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company` varchar(128) DEFAULT NULL,
  `bus_line` varchar(128) DEFAULT NULL,
  `suffix` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company` (`company`,`bus_line`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_bus_station`
-- 

CREATE TABLE `changsha_bus_station` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bus_id` int(11) NOT NULL,
  `bus_name` varchar(128) NOT NULL,
  `station_id` int(11) NOT NULL,
  `station_name` varchar(128) NOT NULL,
  `sequence` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_id` (`bus_id`,`station_id`,`sequence`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `changsha_station`
-- 

CREATE TABLE `changsha_station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_name` varchar(64) NOT NULL,
  `wgs84ns` float(9,6) DEFAULT NULL,
  `wgs84ew` float(9,6) DEFAULT NULL,
  `pass_buses` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_name` (`station_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		
