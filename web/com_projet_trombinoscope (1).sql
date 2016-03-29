-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 29 Mars 2016 à 18:50
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `com.projet_trombinoscope`
--

-- --------------------------------------------------------

--
-- Structure de la table `dans`
--

CREATE TABLE IF NOT EXISTS `dans` (
  `id_etudiant` int(11) NOT NULL,
  `id_groupe` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  PRIMARY KEY (`id_etudiant`,`id_groupe`),
  KEY `id_groupe` (`id_groupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `dans`
--

INSERT INTO `dans` (`id_etudiant`, `id_groupe`, `annee`) VALUES
(253, 38, 0),
(254, 38, 0),
(255, 38, 0),
(256, 38, 0),
(257, 38, 0),
(258, 38, 0),
(259, 38, 0),
(260, 38, 0),
(261, 38, 0),
(262, 38, 0),
(263, 38, 0),
(264, 38, 0),
(265, 38, 0),
(266, 38, 0),
(267, 38, 0),
(268, 38, 0),
(269, 38, 0),
(270, 38, 0),
(271, 38, 0),
(272, 38, 0),
(273, 38, 0),
(274, 38, 0),
(275, 38, 0),
(276, 38, 0),
(277, 38, 0),
(278, 38, 0),
(279, 38, 0),
(280, 38, 0),
(281, 38, 0),
(282, 38, 0),
(283, 38, 0),
(284, 38, 0),
(285, 38, 0),
(286, 38, 0),
(287, 38, 0),
(288, 38, 0),
(289, 38, 0),
(290, 38, 0),
(291, 38, 0),
(292, 38, 0),
(293, 38, 0),
(294, 38, 0),
(295, 38, 0),
(296, 38, 0),
(297, 38, 0),
(298, 38, 0),
(299, 38, 0),
(300, 38, 0),
(301, 38, 0),
(302, 38, 0),
(303, 38, 0),
(304, 38, 0),
(305, 38, 0),
(306, 38, 0),
(307, 38, 0),
(308, 38, 0),
(309, 38, 0),
(310, 38, 0),
(311, 38, 0),
(312, 38, 0),
(313, 38, 0),
(314, 38, 0),
(315, 38, 0),
(316, 38, 0),
(317, 38, 0),
(318, 38, 0),
(319, 38, 0),
(320, 38, 0),
(321, 38, 0),
(322, 38, 0),
(323, 38, 0),
(324, 38, 0),
(325, 38, 0),
(326, 38, 0),
(327, 38, 0),
(328, 38, 0),
(329, 38, 0),
(330, 38, 0),
(331, 38, 0),
(332, 38, 0),
(333, 38, 0),
(334, 38, 0);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE IF NOT EXISTS `etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `noEtudiant` int(10) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=335 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `noEtudiant`, `nom`, `prenom`, `url_photo`) VALUES
(253, 21522624, 'ALIOT', 'MICKAEL', 'img/photos/default.gif'),
(254, 21522917, 'AMBRY', 'MAXIME', 'img/photos/default.gif'),
(255, 21424448, 'AMILHAUD', 'MARTIN', 'img/photos/default.gif'),
(256, 21523281, 'AUBE', 'MATHIEU', 'img/photos/default.gif'),
(257, 21522236, 'AUSSAGUEL', 'LAMBERT', 'img/photos/default.gif'),
(258, 21521417, 'BARAMA', 'RAYAN', 'img/photos/default.gif'),
(259, 21524290, 'BATTON', 'HUGO', 'img/photos/default.gif'),
(260, 21524496, 'BECHARI', 'BILAL', 'img/photos/default.gif'),
(261, 21520082, 'BERARD', 'DYLAN', 'img/photos/default.gif'),
(262, 21520009, 'BILLON', 'EVA', 'img/photos/default.gif'),
(263, 21523840, 'BLAZY', 'ENZO', 'img/photos/default.gif'),
(264, 21520838, 'BOGACZYK', 'PIERRE', 'img/photos/default.gif'),
(265, 21521010, 'BOUREAU', 'EMMANUEL', 'img/photos/default.gif'),
(266, 21520152, 'BRET', 'XAVIER', 'img/photos/default.gif'),
(267, 21524112, 'BRISAC', 'ALFRED', 'img/photos/default.gif'),
(268, 21520073, 'CABROL', 'BENJAMIN', 'img/photos/default.gif'),
(269, 21524357, 'CANSEV', 'ALI', 'img/photos/default.gif'),
(270, 21520398, 'CARMICHAEL', 'JADE', 'img/photos/default.gif'),
(271, 21523929, 'CHASSAING', 'LUKA', 'img/photos/default.gif'),
(272, 21520016, 'CLUSEL', 'MATHIEU', 'img/photos/default.gif'),
(273, 21526441, 'COSTE', 'CLEMENT', 'img/photos/default.gif'),
(274, 21521967, 'COSTECHAREYRE', 'BENOIT', 'img/photos/default.gif'),
(275, 21520010, 'DAZY', 'NICODEME', 'img/photos/default.gif'),
(276, 21522036, 'DELARBRE', 'MORGAN', 'img/photos/default.gif'),
(277, 21528998, 'DELAUNAY', 'JEAN', 'img/photos/default.gif'),
(278, 21524014, 'DESTRAIT', 'CHARLOTTE', 'img/photos/default.gif'),
(279, 21520144, 'DURIEUX', 'ALEXANDRE', 'img/photos/default.gif'),
(280, 21523890, 'FAYANT', 'DYLAN', 'img/photos/default.gif'),
(281, 21524131, 'FERRANT', 'HUGO', 'img/photos/default.gif'),
(282, 21522304, 'FLUCHAIRE', 'VICTOR', 'img/photos/default.gif'),
(283, 21522993, 'FOEX', 'SIMON', 'img/photos/default.gif'),
(284, 21521554, 'GEORGE--LEXCELLENT', 'ADRIEN', 'img/photos/default.gif'),
(285, 21524480, 'GERIN', 'GAUTHIER', 'img/photos/default.gif'),
(286, 21520029, 'GODART', 'QUENTIN', 'img/photos/default.gif'),
(287, 21523805, 'GOLMARD', 'JOHANN', 'img/photos/default.gif'),
(288, 21523847, 'GRAVIER', 'AUDRAN', 'img/photos/default.gif'),
(289, 21520143, 'GRIMAUD', 'CEDRIC', 'img/photos/default.gif'),
(290, 21522195, 'HASSAINE', 'ABDERRAHMANE', 'img/photos/default.gif'),
(291, 21523723, 'HENRION', 'JULIEN', 'img/photos/default.gif'),
(292, 21520088, 'JACOB', 'JULIEN', 'img/photos/default.gif'),
(293, 21523851, 'JOURDAN', 'NICOLAS', 'img/photos/default.gif'),
(294, 21523268, 'LABAN', 'BENOIT', 'img/photos/default.gif'),
(295, 21520129, 'LAPCHIK', 'ANDREY', 'img/photos/default.gif'),
(296, 21521940, 'LECOINTE', 'JEREMY', 'img/photos/default.gif'),
(297, 21520423, 'LECOMTE', 'LOIC', 'img/photos/default.gif'),
(298, 21520087, 'LEFEUVRE', 'VALENTIN', 'img/photos/default.gif'),
(299, 21420362, 'LOGUT', 'NICOLAS', 'img/photos/default.gif'),
(300, 21520038, 'LORETTE-FROIDEVAUX', 'THEO', 'img/photos/default.gif'),
(301, 21520728, 'LUCIANO', 'RAPHAEL', 'img/photos/default.gif'),
(302, 21520826, 'MAHE', 'FLORIAN', 'img/photos/default.gif'),
(303, 21523568, 'MAHIR', 'MEHDI', 'img/photos/default.gif'),
(304, 21521524, 'MARTIN', 'GUILLAUME', 'img/photos/default.gif'),
(305, 21523014, 'MARTIN', 'THIBAUT', 'img/photos/default.gif'),
(306, 21520049, 'MATHLOUTHI', 'SARAH', 'img/photos/default.gif'),
(307, 21520121, 'MORDOHAY', 'WILLIAM', 'img/photos/default.gif'),
(308, 21523307, 'MURER', 'RUDY', 'img/photos/default.gif'),
(309, 21520076, 'NEYRET', 'OLIVIER', 'img/photos/default.gif'),
(310, 21524090, 'PALMIER', 'BENJAMIN', 'img/photos/default.gif'),
(311, 21520160, 'PASQUIOU', 'PAUL', 'img/photos/default.gif'),
(312, 21422055, 'PETIT', 'THOMAS', 'img/photos/default.gif'),
(313, 21521748, 'PEYRONNET', 'CEDRIC', 'img/photos/default.gif'),
(314, 21421378, 'PIGNARD', 'FLORIAN', 'img/photos/default.gif'),
(315, 21522528, 'PINTRAND', 'THIBAUT', 'img/photos/default.gif'),
(316, 21520093, 'POPEK', 'NICOLAS', 'img/photos/default.gif'),
(317, 21521390, 'PRADES', 'MICKAEL', 'img/photos/default.gif'),
(318, 21520080, 'PREVOST', 'NICOLAS', 'img/photos/default.gif'),
(319, 21523993, 'RAMOS', 'CYRIL', 'img/photos/default.gif'),
(320, 21520066, 'REDON', 'RAPHAEL', 'img/photos/default.gif'),
(321, 21520097, 'REY', 'QUENTIN', 'img/photos/default.gif'),
(322, 21527872, 'REYNAUD', 'LOUIS', 'img/photos/default.gif'),
(323, 21520372, 'RIVOIRE', 'GASPARD', 'img/photos/default.gif'),
(324, 21523409, 'ROSTAING', 'MEHDI', 'img/photos/default.gif'),
(325, 21521688, 'SABOURIN', 'YOANN', 'img/photos/default.gif'),
(326, 21523814, 'SAJIDE', 'ADIL', 'img/photos/default.gif'),
(327, 21523875, 'SERRAZ', 'MATTHIEU', 'img/photos/default.gif'),
(328, 21523443, 'SOUBEYRAND', 'JULES', 'img/photos/default.gif'),
(329, 21520090, 'STITI', 'ILIAS', 'img/photos/default.gif'),
(330, 21524007, 'TAGHAT', 'RACHID', 'img/photos/default.gif'),
(331, 21520083, 'TURPIN', 'ANGELIQUE', 'img/photos/default.gif'),
(332, 21520150, 'VALAYER', 'BAPTISTE', 'img/photos/default.gif'),
(333, 21520154, 'VEYRE', 'AURELIEN', 'img/photos/default.gif'),
(334, 21525212, 'VIDAL', 'FLORENT', 'img/photos/default.gif');

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(10) NOT NULL,
  `id_semestre` int(11) DEFAULT NULL,
  `id_pere` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_pere` (`id_pere`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Contenu de la table `groupe`
--

INSERT INTO `groupe` (`id`, `libelle`, `id_semestre`, `id_pere`) VALUES
(1, 'TD1', 1, NULL),
(2, 'TP1', 1, 1),
(3, 'TP2', 1, 1),
(4, 'TD2', 1, NULL),
(5, 'TP3', 1, 4),
(6, 'TP4', 1, 4),
(7, 'TD1', 2, NULL),
(8, 'TD2', 2, NULL),
(9, 'TD3', 1, NULL),
(10, 'TP5', 1, 9),
(11, 'TP6', 1, 9),
(12, 'TD3', 2, NULL),
(13, 'TP1', 2, 7),
(14, 'TP2', 2, 7),
(15, 'TP3', 2, 8),
(16, 'TP4', 2, 8),
(17, 'TP5', 2, 12),
(18, 'TP6', 2, 12),
(19, 'TD1', 3, NULL),
(20, 'TD2', 3, NULL),
(21, 'TD3', 3, NULL),
(22, 'TP1', 3, 19),
(23, 'TP2', 3, 19),
(24, 'TP3', 3, 20),
(25, 'TP4', 3, 20),
(26, 'TP5', 3, 21),
(27, 'TP6', 3, 21),
(28, 'AP', 4, NULL),
(29, 'AT1', 4, NULL),
(30, 'AT2', 4, NULL),
(31, 'TP1', 4, 28),
(32, 'TP2', 4, 28),
(33, 'TP1', 4, 29),
(34, 'TP2', 4, 29),
(35, 'TP3', 4, 30),
(36, 'TP4', 4, 30),
(38, 'ARCHIVE', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `semestre`
--

CREATE TABLE IF NOT EXISTS `semestre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `semestre`
--

INSERT INTO `semestre` (`id`, `libelle`) VALUES
(1, 'Semestre 1'),
(2, 'Semestre 2'),
(3, 'Semestre 3'),
(4, 'Semestre 4');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `dans`
--
ALTER TABLE `dans`
  ADD CONSTRAINT `dans_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dans_ibfk_2` FOREIGN KEY (`id_groupe`) REFERENCES `groupe` (`id`);

--
-- Contraintes pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `groupe_ibfk_1` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id`),
  ADD CONSTRAINT `groupe_ibfk_2` FOREIGN KEY (`id_pere`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
