CREATE TABLE `oauth2_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(1024) NOT NULL DEFAULT '',
  `refresh_token` varchar(1024) NOT NULL DEFAULT '',
  `expire_time` int(11) NOT NULL DEFAULT '-1',
  `id_character` int(11) NOT NULL,
  `id_forum_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth2_users_UNIQUE` (`id_character`),
  KEY `fk_oauth_users_phpbb_forum_idx` (`id_forum_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
