SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `contests` (
  `contest` varchar(200) NOT NULL,
  `contest_name` varchar(200) DEFAULT NULL,
  `description` text,
  `date_begin` date NOT NULL,
  `date_end` date NOT NULL,
  `voting_type` varchar(10) NOT NULL,
  UNIQUE KEY `contest` (`contest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `images` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_name` varchar(60) DEFAULT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `contest` varchar(100) DEFAULT NULL,
  `love` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`img_id`),
  KEY `contest` (`contest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `image_IP` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id_fk` int(11) DEFAULT NULL,
  `ip_add` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `contest` varchar(200) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ip_id`),
  KEY `img_id_fk` (`img_id_fk`),
  KEY `contest` (`contest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `settings` (
  `contests_name` varchar(200) NOT NULL,
  `gallery_only` tinyint(1) DEFAULT '0',
  `contest_disp_title` varchar(255) NOT NULL,
  `display_other_contests` tinyint(1) NOT NULL DEFAULT '1',
  `max_length` int(11) NOT NULL DEFAULT '400',
  `language` varchar(15) NOT NULL,
  `date_format` varchar(10) NOT NULL,
  `default_contest` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`contest`) REFERENCES `contests` (`contest`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `image_IP`
  ADD CONSTRAINT `image_IP_ibfk_2` FOREIGN KEY (`img_id_fk`) REFERENCES `images` (`img_id`) ON DELETE CASCADE ON UPDATE CASCADE;
