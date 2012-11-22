-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Ven 16 Novembre 2012 à 11:22
-- Version du serveur: 5.5.28
-- Version de PHP: 5.3.18-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `calendrier`
--

-- --------------------------------------------------------

--
-- Structure de la table `contests`
--

CREATE TABLE `contests` (
  `contest` varchar(200) NOT NULL,
  `contest_name` varchar(200) DEFAULT NULL,
  `description` text,
  `date_begin` date NOT NULL,
  `date_end` date NOT NULL,
  UNIQUE KEY `contest` (`contest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_name` varchar(60) DEFAULT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `contest` varchar(100) DEFAULT NULL,
  `love` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`img_id`),
  KEY `contest` (`contest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `image_IP`
--

CREATE TABLE `image_IP` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id_fk` int(11) DEFAULT NULL,
  `ip_add` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ip_id`),
  KEY `img_id_fk` (`img_id_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`contest`) REFERENCES `contests` (`contest`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `image_IP`
--
ALTER TABLE `image_IP`
  ADD CONSTRAINT `image_IP_ibfk_2` FOREIGN KEY (`img_id_fk`) REFERENCES `images` (`img_id`) ON DELETE CASCADE ON UPDATE CASCADE;
