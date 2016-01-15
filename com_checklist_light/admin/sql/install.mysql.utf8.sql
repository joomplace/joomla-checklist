CREATE TABLE IF NOT EXISTS `#__checklist_config` (
  `setting_name` varchar(50) NOT NULL DEFAULT '',
  `setting_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `setting_name` (`setting_name`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_groups` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `checklist_id` int(12) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `ordering` int(10) NOT NULL,
  PRIMARY KEY (`id`,`checklist_id`),
  KEY `checklist_id` (`checklist_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_items` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `checklist_id` int(12) unsigned NOT NULL,
  `group_id` int(12) unsigned NOT NULL,
  `task` varchar(100) NOT NULL,
  `tips` text NOT NULL,
  `optional` int(3) NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `checked` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `checklist_id` (`checklist_id`,`group_id`),
  KEY `group_id` (`group_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_lists` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(18) unsigned NOT NULL,
  `user_id` int(12) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `alias` varchar(250) NOT NULL,
  `description_before` text NOT NULL,
  `description_after` text NOT NULL,
  `default` tinyint(3) NOT NULL,
  `published` tinyint(3) NOT NULL,
  `publish_date` date NOT NULL,
  `list_access` float NOT NULL,
  `comment_access` int(15) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `custom_metatags` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_list_tags` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `checklist_id` int(15) unsigned NOT NULL,
  `tag_id` int(15) unsigned NOT NULL,
  `isnew` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_requests` (
  `id` int(18) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(18) unsigned NOT NULL,
  `checklist_id` int(18) unsigned NOT NULL,
  `confirm` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_setup` (
  `c_par_name` varchar(20) NOT NULL DEFAULT '',
  `c_par_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `c_par_name` (`c_par_name`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_tags` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `default` tinyint(3) unsigned NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_users` (
  `user_id` int(12) unsigned NOT NULL,
  `website_field` varchar(40) NOT NULL,
  `twitter_field` varchar(100) NOT NULL,
  `facebook_field` varchar(100) NOT NULL,
  `google_field` varchar(100) NOT NULL,
  `description_field` text NOT NULL,
  `avatar_field` varchar(150) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__checklist_users_item` (
  `id` int(18) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `item_id` int(12) NOT NULL,
  `checklist_id` int(12) NOT NULL,
  `checked` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `user_id` (`user_id`)
) DEFAULT CHARSET=utf8;