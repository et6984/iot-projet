-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 20 mars 2025 à 15:39
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetiot`
--

-- --------------------------------------------------------

--
-- Structure de la table `capteur`
--

DROP TABLE IF EXISTS `capteur`;
CREATE TABLE IF NOT EXISTS `capteur` (
  `ID_CAPTEUR` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE_CAPTEUR` char(1) NOT NULL,
  `MESURE` decimal(11,2) DEFAULT NULL,
  `DATE_CAPTEUR` date DEFAULT NULL,
  `HEURE_CAPTEUR` time DEFAULT NULL,
  PRIMARY KEY (`ID_CAPTEUR`),
  KEY `FK_ASSOCIATION_6` (`TYPE_CAPTEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `ID_SALLE` int(11) NOT NULL AUTO_INCREMENT,
  `ID_USER` int(11) DEFAULT NULL,
  `TYPE_SALLE` char(1) DEFAULT NULL,
  `ID_CAPTEUR` int(11) DEFAULT NULL,
  `LIBELLE_SALLE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_SALLE`),
  KEY `FK_ASSOCIATION_10` (`TYPE_SALLE`),
  KEY `FK_ASSOCIATION_7` (`ID_CAPTEUR`),
  KEY `FK_ASSOCIATION_8` (`ID_USER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_capteur`
--

DROP TABLE IF EXISTS `type_capteur`;
CREATE TABLE IF NOT EXISTS `type_capteur` (
  `TYPE_CAPTEUR` char(1) NOT NULL,
  `LIBELLE_TYPE_CAPTEUR` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TYPE_CAPTEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_salle`
--

DROP TABLE IF EXISTS `type_salle`;
CREATE TABLE IF NOT EXISTS `type_salle` (
  `TYPE_SALLE` char(1) NOT NULL,
  `LIBELLE_TYPE_SALLE` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TYPE_SALLE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_user`
--

DROP TABLE IF EXISTS `type_user`;
CREATE TABLE IF NOT EXISTS `type_user` (
  `TYPE_USER` char(1) NOT NULL,
  `LIBELLE_TYPE_USER` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`TYPE_USER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID_USER` int(11) NOT NULL AUTO_INCREMENT,
  `TYPE_USER` char(1) NOT NULL,
  `NOM_USER` varchar(255) DEFAULT NULL,
  `PRENOM_USER` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID_USER`),
  KEY `FK_ASSOCIATION_9` (`TYPE_USER`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
