-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 avr. 2025 à 13:00
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `salle_serveur`
--

-- --------------------------------------------------------

--
-- Structure de la table `capteur`
--

DROP TABLE IF EXISTS `capteur`;
CREATE TABLE IF NOT EXISTS `capteur` (
  `ID_CAPTEUR` int NOT NULL AUTO_INCREMENT,
  `TYPE_CAPTEUR` char(1) NOT NULL,
  `MESURE` decimal(11,2) DEFAULT NULL,
  `CAPTEUR_DATE_HEURE` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_CAPTEUR`),
  KEY `FK_ASSOCIATION_6` (`TYPE_CAPTEUR`)
) ENGINE=MyISAM AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `capteur`
--

INSERT INTO `capteur` (`ID_CAPTEUR`, `TYPE_CAPTEUR`, `MESURE`, `CAPTEUR_DATE_HEURE`) VALUES
(66, 'H', 36.00, '2025-04-01 14:51:48'),
(65, 'T', 15.00, '2025-04-01 14:51:48'),
(68, 'H', 65.00, '2025-04-01 14:52:48'),
(67, 'T', 21.00, '2025-04-01 14:52:48'),
(64, 'H', 35.00, '2025-04-01 14:48:46'),
(63, 'T', 18.00, '2025-04-01 14:48:46'),
(62, 'H', 51.00, '2025-04-01 14:48:01'),
(61, 'T', 22.00, '2025-04-01 14:48:01'),
(60, 'H', 62.00, '2025-04-01 14:47:46'),
(59, 'T', 23.00, '2025-04-01 14:47:46'),
(58, 'H', 54.00, '2025-04-01 14:47:01'),
(57, 'T', 18.00, '2025-04-01 14:47:01'),
(70, 'H', 51.00, '2025-04-01 14:54:31'),
(69, 'T', 24.00, '2025-04-01 14:54:31'),
(72, 'H', 40.00, '2025-04-01 14:55:31'),
(71, 'T', 30.00, '2025-04-01 14:55:31'),
(78, 'H', 45.00, '2025-04-01 17:08:42'),
(77, 'T', 21.00, '2025-04-01 17:08:42'),
(80, 'H', 35.00, '2025-04-01 17:08:55'),
(79, 'T', 25.00, '2025-04-01 17:08:55'),
(82, 'H', 35.00, '2025-04-01 17:09:01'),
(81, 'T', 25.00, '2025-04-01 17:09:01'),
(84, 'H', 35.00, '2025-04-01 17:09:13'),
(83, 'T', 25.00, '2025-04-01 17:09:13'),
(86, 'H', 30.00, '2025-04-01 17:09:37'),
(85, 'T', 20.00, '2025-04-01 17:09:37'),
(87, 'T', 35.00, '2025-04-01 17:09:51'),
(88, 'H', 25.00, '2025-04-01 17:09:51'),
(89, 'T', 25.00, '2025-04-01 17:10:21'),
(90, 'H', 35.00, '2025-04-01 17:10:21'),
(91, 'T', 20.00, '2025-04-01 17:10:36'),
(92, 'H', 30.00, '2025-04-01 17:10:36'),
(93, 'T', 35.00, '2025-04-01 17:10:40'),
(94, 'H', 25.00, '2025-04-01 17:10:40'),
(95, 'T', 25.00, '2025-04-01 17:10:44'),
(96, 'H', 35.00, '2025-04-01 17:10:44'),
(97, 'T', 20.00, '2025-04-01 17:10:47'),
(98, 'H', 30.00, '2025-04-01 17:10:47'),
(99, 'T', 25.00, '2025-04-01 17:10:50'),
(100, 'H', 35.00, '2025-04-01 17:10:50'),
(101, 'T', 20.00, '2025-04-01 17:10:53'),
(102, 'H', 30.00, '2025-04-01 17:10:53'),
(103, 'T', 35.00, '2025-04-01 17:10:54'),
(104, 'H', 25.00, '2025-04-01 17:10:54'),
(105, 'T', 25.00, '2025-04-01 17:10:56'),
(106, 'H', 35.00, '2025-04-01 17:10:56'),
(107, 'T', 20.00, '2025-04-01 17:10:58'),
(108, 'H', 30.00, '2025-04-01 17:10:58'),
(109, 'T', 35.00, '2025-04-01 17:11:00'),
(110, 'H', 25.00, '2025-04-01 17:11:00'),
(111, 'T', 25.00, '2025-04-01 17:11:01'),
(112, 'H', 35.00, '2025-04-01 17:11:01'),
(113, 'T', 20.00, '2025-04-01 17:11:02'),
(114, 'H', 30.00, '2025-04-01 17:11:02'),
(115, 'T', 35.00, '2025-04-01 17:11:03'),
(116, 'H', 25.00, '2025-04-01 17:11:03'),
(117, 'T', 25.00, '2025-04-01 17:11:05'),
(118, 'H', 35.00, '2025-04-01 17:11:05'),
(119, 'T', 20.00, '2025-04-01 17:11:07'),
(120, 'H', 30.00, '2025-04-01 17:11:07'),
(121, 'T', 35.00, '2025-04-01 17:11:08'),
(122, 'H', 25.00, '2025-04-01 17:11:08'),
(123, 'T', 25.00, '2025-04-01 17:11:09'),
(124, 'H', 35.00, '2025-04-01 17:11:09'),
(125, 'T', 20.00, '2025-04-01 17:11:10'),
(126, 'H', 30.00, '2025-04-01 17:11:10'),
(127, 'T', 35.00, '2025-04-01 17:11:12'),
(128, 'H', 25.00, '2025-04-01 17:11:12'),
(129, 'T', 20.00, '2025-04-01 17:13:17'),
(130, 'H', 30.00, '2025-04-01 17:13:17'),
(131, 'T', 19.50, '2025-04-02 15:09:59'),
(132, 'H', 35.00, '2025-04-02 15:09:59'),
(133, 'T', 25.00, '2025-04-02 15:11:05'),
(134, 'H', 35.00, '2025-04-02 15:11:05'),
(135, 'T', 25.00, '2025-04-02 15:12:14'),
(136, 'H', 35.00, '2025-04-02 15:12:14'),
(137, 'T', 20.00, '2025-04-02 16:54:00'),
(138, 'H', 40.00, '2025-04-02 16:54:00'),
(139, 'T', 25.00, '2025-04-02 16:54:20'),
(140, 'H', 45.00, '2025-04-02 16:54:20'),
(141, 'T', 21.00, '2025-04-02 17:00:34'),
(142, 'H', 45.00, '2025-04-02 17:00:34'),
(143, 'T', 25.00, '2025-04-02 18:41:12'),
(144, 'H', 35.00, '2025-04-02 18:41:12'),
(145, 'T', 23.00, '2025-04-03 11:25:50'),
(146, 'H', 34.00, '2025-04-03 11:25:50');

-- --------------------------------------------------------

--
-- Structure de la table `historique_donne`
--

DROP TABLE IF EXISTS `historique_donne`;
CREATE TABLE IF NOT EXISTS `historique_donne` (
  `id` int NOT NULL AUTO_INCREMENT,
  `annee` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mois` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jour` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `heure` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `donne` float(100,2) DEFAULT NULL,
  `type_capteur` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_capteur` (`type_capteur`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique_donne`
--

INSERT INTO `historique_donne` (`id`, `annee`, `mois`, `jour`, `heure`, `donne`, `type_capteur`) VALUES
(1, '25', '01', '', '', 18.50, 'T'),
(2, '25', '02', '', '', 18.00, 'T'),
(3, '25', '03', '', '', 17.50, 'T'),
(4, '25', '04', '', '', 19.50, 'T'),
(5, '25', '05', '', '', 21.50, 'T'),
(6, '25', '06', '', '', 21.00, 'T'),
(7, '25', '07', '', '', 22.00, 'T'),
(8, '25', '08', '', '', 23.00, 'T'),
(9, '25', '09', '', '', 23.50, 'T'),
(10, '25', '10', '', '', 19.00, 'T'),
(11, '25', '11', '', '', 18.50, 'T'),
(12, '25', '12', '', '', 18.00, 'T'),
(13, '25', '01', '', '', 35.50, 'H'),
(14, '25', '02', '', '', 35.00, 'H'),
(15, '25', '03', '', '', 50.50, 'H'),
(16, '25', '04', '', '', 45.50, 'H'),
(17, '25', '05', '', '', 60.50, 'H'),
(18, '25', '06', '', '', 60.00, 'H'),
(19, '25', '07', '', '', 65.00, 'H'),
(20, '25', '08', '', '', 70.00, 'H'),
(21, '25', '09', '', '', 40.50, 'H'),
(22, '25', '10', '', '', 45.00, 'H'),
(23, '25', '11', '', '', 45.50, 'H'),
(24, '25', '12', '', '', 30.00, 'H');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `ID_SALLE` int NOT NULL AUTO_INCREMENT,
  `ID_USER` int DEFAULT NULL,
  `TYPE_SALLE` char(1) DEFAULT NULL,
  `ID_CAPTEUR` int DEFAULT NULL,
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

--
-- Déchargement des données de la table `type_capteur`
--

INSERT INTO `type_capteur` (`TYPE_CAPTEUR`, `LIBELLE_TYPE_CAPTEUR`) VALUES
('T', 'temperature'),
('H', 'humidite');

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

--
-- Déchargement des données de la table `type_user`
--

INSERT INTO `type_user` (`TYPE_USER`, `LIBELLE_TYPE_USER`) VALUES
('A', 'administrateur'),
('U', 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `ID_USER` int NOT NULL AUTO_INCREMENT,
  `TYPE_USER` char(1) NOT NULL,
  `NOM_USER` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `PRENOM_USER` varchar(255) DEFAULT NULL,
  `PASSWORD_USER` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`ID_USER`),
  KEY `FK_ASSOCIATION_9` (`TYPE_USER`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`ID_USER`, `TYPE_USER`, `NOM_USER`, `PRENOM_USER`, `PASSWORD_USER`) VALUES
(5, 'A', 'dehondt', 'gabin', '$argon2i$v=19$m=65536,t=4,p=1$WHo1ckpLSkd6TWI5MG9ueg$zXPNsV0V7TlelYjk84YXSg24E92emyojobTJSQ8PwP0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
