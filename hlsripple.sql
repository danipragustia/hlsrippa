-- Adminer 4.8.0 MySQL 5.7.24 dump

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

DROP TABLE IF EXISTS `bwca_token`;
CREATE TABLE `bwca_token` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `show` int(12) NOT NULL DEFAULT '0',
  `cur_token` varchar(255) DEFAULT NULL,
  `user_id` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `bwca_token`;

DROP TABLE IF EXISTS `wegy_settings`;
CREATE TABLE `wegy_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `wegy_settings`;
INSERT INTO `wegy_settings` (`id`, `item`, `value`) VALUES
(1,	'note',	'');

DROP TABLE IF EXISTS `xezc_user`;
CREATE TABLE `xezc_user` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('Admin','User') NOT NULL DEFAULT 'User',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `xezc_user`;
INSERT INTO `xezc_user` (`id`, `username`, `password`, `level`) VALUES
(1,	'mimin',	'$2y$10$XDqBRzrd/6/WK5ck890IwuzP50ArnbnWAQ8s2WC3PcrVN4.IulB/O',	'Admin'),
(2,	'user',	'$2y$10$r4w8VKf2D5sFf6WWTXWvMOhxs4bQ/7rg0P/UQPUzAlci0s/dK40ZC',	'User');

-- 2021-10-03 13:04:55
