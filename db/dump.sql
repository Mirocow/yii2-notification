CREATE TABLE `tbl_notification_send_to` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'ID Пользователя',
  `status` int(1) NOT NULL COMMENT 'Статус',
  `data` text COMMENT 'Данные на отсылку',
  `send_status` int(1) NOT NULL COMMENT 'Статус доставки',
  `errors` text COMMENT 'Ошибки',
  `create_time` datetime DEFAULT NULL COMMENT 'Дата нотификации',
  PRIMARY KEY (`id`),
  KEY `fk_request_send_to_user_id` (`user_id`),
  CONSTRAINT `fk_request_send_to_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127853 DEFAULT CHARSET=utf8
