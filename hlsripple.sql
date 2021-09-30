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


DROP TABLE IF EXISTS `bwca_token`;
CREATE TABLE `bwca_token` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `show` int(12) NOT NULL DEFAULT '0',
  `cur_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `xezc_user`;
CREATE TABLE `xezc_user` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

TRUNCATE `xezc_user`;
INSERT INTO `xezc_user` (`id`, `username`, `password`) VALUES
(1,	'mimin',	'$2y$10$XDqBRzrd/6/WK5ck890IwuzP50ArnbnWAQ8s2WC3PcrVN4.IulB/O');

-- 2021-09-30 16:08:51
