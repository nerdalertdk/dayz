-- 
-- Structure for table `log_tool`
-- 

DROP TABLE IF EXISTS `log_tool`;
CREATE TABLE IF NOT EXISTS `log_tool` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `user` varchar(50) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;

-- 
-- Structure for table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` char(3) NOT NULL DEFAULT '',
  `permissions` varchar(255) NOT NULL DEFAULT 'whitelist, table, map, control, manage, log, tools, feed',
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 
-- Data for table `users`
-- 

-- USERNAME: admin
-- PASSWORD: adminadmin

INSERT INTO `users` (`id`, `login`, `password`, `salt`, `permissions`, `lastlogin`) VALUES
  ('1', 'admin', 'c80246fb03b32bfa3293bf4d017824a4', 'oy*', 'manage, log, control, table, map, tools, feed, user, whitelist', '2013-04-13 17:05:10');

ALTER TABLE `character_data`
    ADD `lastactiv` timestamp on update current_timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;