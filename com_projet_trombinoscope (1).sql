-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 29 Mars 2016 à 15:36
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
(1, 4, 0),
(1, 5, 0),
(2, 4, 0),
(2, 6, 0),
(3, 4, 2016),
(3, 5, 2016),
(4, 4, 2016),
(4, 6, 2016),
(88, 1, 0),
(88, 2, 0),
(89, 1, 0),
(89, 2, 0),
(90, 1, 0),
(90, 2, 0),
(91, 1, 0),
(91, 2, 0),
(92, 1, 0),
(92, 2, 0),
(93, 1, 0),
(93, 2, 0),
(94, 1, 0),
(94, 2, 0),
(95, 1, 0),
(95, 2, 0),
(96, 1, 0),
(96, 2, 0),
(97, 1, 0),
(97, 2, 0),
(98, 1, 0),
(98, 2, 0),
(99, 1, 0),
(99, 2, 0),
(100, 1, 0),
(100, 2, 0),
(101, 1, 0),
(101, 2, 0),
(102, 1, 0),
(102, 2, 0),
(103, 1, 0),
(103, 2, 0),
(104, 1, 0),
(104, 2, 0),
(105, 1, 0),
(105, 2, 0),
(106, 1, 0),
(106, 2, 0),
(107, 1, 0),
(107, 2, 0),
(108, 1, 0),
(108, 2, 0),
(109, 1, 0),
(109, 2, 0),
(110, 1, 0),
(110, 2, 0),
(111, 1, 0),
(111, 2, 0),
(112, 1, 0),
(112, 2, 0),
(113, 1, 0),
(113, 2, 0),
(114, 1, 0),
(114, 2, 0),
(115, 1, 0),
(115, 2, 0),
(116, 1, 0),
(116, 2, 0),
(117, 1, 0),
(117, 2, 0),
(118, 1, 0),
(118, 2, 0),
(119, 1, 0),
(119, 2, 0),
(120, 1, 0),
(120, 2, 0),
(121, 1, 0),
(121, 2, 0),
(122, 1, 0),
(122, 2, 0),
(123, 1, 0),
(123, 2, 0),
(124, 1, 0),
(124, 2, 0),
(125, 1, 0),
(125, 2, 0),
(126, 1, 0),
(126, 2, 0),
(127, 1, 0),
(127, 2, 0),
(128, 1, 0),
(128, 2, 0),
(129, 1, 0),
(129, 2, 0),
(130, 1, 0),
(130, 2, 0),
(131, 1, 0),
(131, 2, 0),
(132, 1, 0),
(132, 2, 0),
(133, 1, 0),
(133, 2, 0),
(134, 1, 0),
(134, 2, 0),
(135, 1, 0),
(135, 2, 0),
(136, 1, 0),
(136, 2, 0),
(137, 1, 0),
(137, 2, 0),
(138, 1, 0),
(138, 2, 0),
(139, 1, 0),
(139, 2, 0),
(140, 1, 0),
(140, 2, 0),
(141, 1, 0),
(141, 2, 0),
(142, 1, 0),
(142, 2, 0),
(143, 1, 0),
(143, 2, 0),
(144, 1, 0),
(144, 2, 0),
(145, 1, 0),
(145, 2, 0),
(146, 1, 0),
(146, 2, 0),
(147, 1, 0),
(147, 2, 0),
(148, 1, 0),
(148, 2, 0),
(149, 1, 0),
(149, 2, 0),
(150, 1, 0),
(150, 2, 0),
(151, 1, 0),
(151, 2, 0),
(152, 1, 0),
(152, 2, 0),
(153, 1, 0),
(153, 2, 0),
(154, 1, 0),
(154, 2, 0),
(155, 1, 0),
(155, 2, 0),
(156, 1, 0),
(156, 2, 0),
(157, 1, 0),
(157, 2, 0),
(158, 1, 0),
(158, 2, 0),
(159, 1, 0),
(159, 2, 0),
(160, 1, 0),
(160, 2, 0),
(161, 1, 0),
(161, 2, 0),
(162, 1, 0),
(162, 2, 0),
(163, 1, 0),
(163, 2, 0),
(164, 1, 0),
(164, 2, 0),
(165, 1, 0),
(165, 2, 0),
(166, 1, 0),
(166, 2, 0),
(167, 1, 0),
(167, 2, 0),
(168, 1, 0),
(168, 2, 0),
(169, 1, 0),
(169, 2, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=170 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `noEtudiant`, `nom`, `prenom`, `url_photo`) VALUES
(1, 21423125, 'LACONDEMINE', 'Virgil', 'img/photos/Virgil.jpg'),
(2, 21420192, 'LEPRUNIER', 'Hugo', 'img/photos/Hugo.jpg'),
(3, 21320210, 'HENROT', 'Boris', 'img/photos/Boris.jpg'),
(4, 21421723, 'BARRA', 'Jeremy', 'img/photos/Jerem.jpg'),
(88, 21522624, 'ALIOT', 'MICKAEL', 'img/photos/default.gif'),
(89, 21522917, 'AMBRY', 'MAXIME', 'img/photos/default.gif'),
(90, 21424448, 'AMILHAUD', 'MARTIN', 'img/photos/default.gif'),
(91, 21523281, 'AUBE', 'MATHIEU', 'img/photos/default.gif'),
(92, 21522236, 'AUSSAGUEL', 'LAMBERT', 'img/photos/default.gif'),
(93, 21521417, 'BARAMA', 'RAYAN', 'img/photos/default.gif'),
(94, 21524290, 'BATTON', 'HUGO', 'img/photos/default.gif'),
(95, 21524496, 'BECHARI', 'BILAL', 'img/photos/default.gif'),
(96, 21520082, 'BERARD', 'DYLAN', 'img/photos/default.gif'),
(97, 21520009, 'BILLON', 'EVA', 'img/photos/default.gif'),
(98, 21523840, 'BLAZY', 'ENZO', 'img/photos/default.gif'),
(99, 21520838, 'BOGACZYK', 'PIERRE', 'img/photos/default.gif'),
(100, 21521010, 'BOUREAU', 'EMMANUEL', 'img/photos/default.gif'),
(101, 21520152, 'BRET', 'XAVIER', 'img/photos/default.gif'),
(102, 21524112, 'BRISAC', 'ALFRED', 'img/photos/default.gif'),
(103, 21520073, 'CABROL', 'BENJAMIN', 'img/photos/default.gif'),
(104, 21524357, 'CANSEV', 'ALI', 'img/photos/default.gif'),
(105, 21520398, 'CARMICHAEL', 'JADE', 'img/photos/default.gif'),
(106, 21523929, 'CHASSAING', 'LUKA', 'img/photos/default.gif'),
(107, 21520016, 'CLUSEL', 'MATHIEU', 'img/photos/default.gif'),
(108, 21526441, 'COSTE', 'CLEMENT', 'img/photos/default.gif'),
(109, 21521967, 'COSTECHAREYRE', 'BENOIT', 'img/photos/default.gif'),
(110, 21520010, 'DAZY', 'NICODEME', 'img/photos/default.gif'),
(111, 21522036, 'DELARBRE', 'MORGAN', 'img/photos/default.gif'),
(112, 21528998, 'DELAUNAY', 'JEAN', 'img/photos/default.gif'),
(113, 21524014, 'DESTRAIT', 'CHARLOTTE', 'img/photos/default.gif'),
(114, 21520144, 'DURIEUX', 'ALEXANDRE', 'img/photos/default.gif'),
(115, 21523890, 'FAYANT', 'DYLAN', 'img/photos/default.gif'),
(116, 21524131, 'FERRANT', 'HUGO', 'img/photos/default.gif'),
(117, 21522304, 'FLUCHAIRE', 'VICTOR', 'img/photos/default.gif'),
(118, 21522993, 'FOEX', 'SIMON', 'img/photos/default.gif'),
(119, 21521554, 'GEORGE--LEXCELLENT', 'ADRIEN', 'img/photos/default.gif'),
(120, 21524480, 'GERIN', 'GAUTHIER', 'img/photos/default.gif'),
(121, 21520029, 'GODART', 'QUENTIN', 'img/photos/default.gif'),
(122, 21523805, 'GOLMARD', 'JOHANN', 'img/photos/default.gif'),
(123, 21523847, 'GRAVIER', 'AUDRAN', 'img/photos/default.gif'),
(124, 21520143, 'GRIMAUD', 'CEDRIC', 'img/photos/default.gif'),
(125, 21522195, 'HASSAINE', 'ABDERRAHMANE', 'img/photos/default.gif'),
(126, 21523723, 'HENRION', 'JULIEN', 'img/photos/default.gif'),
(127, 21520088, 'JACOB', 'JULIEN', 'img/photos/default.gif'),
(128, 21523851, 'JOURDAN', 'NICOLAS', 'img/photos/default.gif'),
(129, 21523268, 'LABAN', 'BENOIT', 'img/photos/default.gif'),
(130, 21520129, 'LAPCHIK', 'ANDREY', 'img/photos/default.gif'),
(131, 21521940, 'LECOINTE', 'JEREMY', 'img/photos/default.gif'),
(132, 21520423, 'LECOMTE', 'LOIC', 'img/photos/default.gif'),
(133, 21520087, 'LEFEUVRE', 'VALENTIN', 'img/photos/default.gif'),
(134, 21420362, 'LOGUT', 'NICOLAS', 'img/photos/default.gif'),
(135, 21520038, 'LORETTE-FROIDEVAUX', 'THEO', 'img/photos/default.gif'),
(136, 21520728, 'LUCIANO', 'RAPHAEL', 'img/photos/default.gif'),
(137, 21520826, 'MAHE', 'FLORIAN', 'img/photos/default.gif'),
(138, 21523568, 'MAHIR', 'MEHDI', 'img/photos/default.gif'),
(139, 21521524, 'MARTIN', 'GUILLAUME', 'img/photos/default.gif'),
(140, 21523014, 'MARTIN', 'THIBAUT', 'img/photos/default.gif'),
(141, 21520049, 'MATHLOUTHI', 'SARAH', 'img/photos/default.gif'),
(142, 21520121, 'MORDOHAY', 'WILLIAM', 'img/photos/default.gif'),
(143, 21523307, 'MURER', 'RUDY', 'img/photos/default.gif'),
(144, 21520076, 'NEYRET', 'OLIVIER', 'img/photos/default.gif'),
(145, 21524090, 'PALMIER', 'BENJAMIN', 'img/photos/default.gif'),
(146, 21520160, 'PASQUIOU', 'PAUL', 'img/photos/default.gif'),
(147, 21422055, 'PETIT', 'THOMAS', 'img/photos/default.gif'),
(148, 21521748, 'PEYRONNET', 'CEDRIC', 'img/photos/default.gif'),
(149, 21421378, 'PIGNARD', 'FLORIAN', 'img/photos/default.gif'),
(150, 21522528, 'PINTRAND', 'THIBAUT', 'img/photos/default.gif'),
(151, 21520093, 'POPEK', 'NICOLAS', 'img/photos/default.gif'),
(152, 21521390, 'PRADES', 'MICKAEL', 'img/photos/default.gif'),
(153, 21520080, 'PREVOST', 'NICOLAS', 'img/photos/default.gif'),
(154, 21523993, 'RAMOS', 'CYRIL', 'img/photos/default.gif'),
(155, 21520066, 'REDON', 'RAPHAEL', 'img/photos/default.gif'),
(156, 21520097, 'REY', 'QUENTIN', 'img/photos/default.gif'),
(157, 21527872, 'REYNAUD', 'LOUIS', 'img/photos/default.gif'),
(158, 21520372, 'RIVOIRE', 'GASPARD', 'img/photos/default.gif'),
(159, 21523409, 'ROSTAING', 'MEHDI', 'img/photos/default.gif'),
(160, 21521688, 'SABOURIN', 'YOANN', 'img/photos/default.gif'),
(161, 21523814, 'SAJIDE', 'ADIL', 'img/photos/default.gif'),
(162, 21523875, 'SERRAZ', 'MATTHIEU', 'img/photos/default.gif'),
(163, 21523443, 'SOUBEYRAND', 'JULES', 'img/photos/default.gif'),
(164, 21520090, 'STITI', 'ILIAS', 'img/photos/default.gif'),
(165, 21524007, 'TAGHAT', 'RACHID', 'img/photos/default.gif'),
(166, 21520083, 'TURPIN', 'ANGELIQUE', 'img/photos/default.gif'),
(167, 21520150, 'VALAYER', 'BAPTISTE', 'img/photos/default.gif'),
(168, 21520154, 'VEYRE', 'AURELIEN', 'img/photos/default.gif'),
(169, 21525212, 'VIDAL', 'FLORENT', 'img/photos/default.gif');

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(10) NOT NULL,
  `id_semestre` int(11) NOT NULL,
  `id_pere` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_semestre` (`id_semestre`),
  KEY `id_pere` (`id_pere`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `groupe`
--

INSERT INTO `groupe` (`id`, `libelle`, `id_semestre`, `id_pere`) VALUES
(1, 'TD1', 1, NULL),
(2, 'TP1', 1, 1),
(3, 'TP2', 1, 1),
(4, 'TD2', 1, NULL),
(5, 'TP3', 1, 4),
(6, 'TP4', 1, 4);

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
