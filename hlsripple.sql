-- Adminer 4.8.0 MySQL 5.7.25 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `bddv_show`;
CREATE TABLE `bddv_show` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `m3u8` varchar(255) NOT NULL,
  `key_auth` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `bddv_show`;
INSERT INTO `bddv_show` (`id`, `nama`, `m3u8`, `key_auth`) VALUES
(1,	'Contoh AES Encrpytion',	'https://www.radiantmediaplayer.com/media/rmp-segment/bbb-abr-aes/chunklist_b607794.m3u8',	'MnIW1ohEVvGp5qbYqighig==');

DROP TABLE IF EXISTS `wegy_settings`;
CREATE TABLE `wegy_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `wegy_settings`;
INSERT INTO `wegy_settings` (`id`, `item`, `value`) VALUES
(1,	'note',	'cccc'),
(2,	'cache_time',	'0');

DROP TABLE IF EXISTS `xezc_user`;
CREATE TABLE `xezc_user` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('Admin','User','Disable') NOT NULL DEFAULT 'User',
  `cur_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `xezc_user`;
INSERT INTO `xezc_user` (`id`, `username`, `password`, `level`, `cur_token`) VALUES
(1,	'mimin',	'$2y$10$XDqBRzrd/6/WK5ck890IwuzP50ArnbnWAQ8s2WC3PcrVN4.IulB/O',	'Admin',	NULL),
(2,	'user',	'$2y$10$43ycggFkxlP.w9td.iCbuOxqOUhjfZxc6w1Xar6lTSSRUOuSxo71.',	'User',	'9a88797785e64beb5f31c3401d087ee94298a8a8');

-- 2021-10-08 00:21:37
