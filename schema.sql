DROP TABLE IF EXISTS `filesystemEntries`;

CREATE TABLE `filesystemEntries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` varchar(255) DEFAULT '',
  `path` varchar(2048) NOT NULL DEFAULT '',
  `created_time` datetime NOT NULL,
  `modified_time` datetime NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
