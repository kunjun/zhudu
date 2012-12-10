CREATE TABLE `u17` (
 `id` int(11) NOT NULL auto_increment,
 `u17_id` int(11) NOT NULL,
 `u17_author_id` int(11) NOT NULL,
 `cover` varchar(1024) NOT NULL,
 `title` varchar(90) NOT NULL,
 `url` varchar(1024) NOT NULL,
 `author` varchar(90) NOT NULL,
 `author_url` varchar(1024) NOT NULL,
 `tag` varchar(90) NOT NULL,
 `renqi` int(11) NOT NULL,
 `yuepiao` int(11) NOT NULL,
 `shoucang` int(11) NOT NULL,
 `summary` text NOT NULL,
 `update_time` varchar(20) NOT NULL,
 `updated_at` int(11) NOT NULL,
 `created_at` int(11) NOT NULL,
 PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8