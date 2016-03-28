-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 08 Mars 2016 à 22:45
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
(1, 1, 2016),
(1, 2, 2016),
(2, 1, 2016),
(2, 3, 2016),
(3, 4, 2016),
(3, 5, 2016),
(4, 4, 2016),
(4, 6, 2016);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE IF NOT EXISTS `etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `nom`, `prenom`, `url_photo`) VALUES
(1, 'LACONDEMINE', 'Virgil', 'img/photos/Virgil.jpg'),
(2, 'LEPRUNIER', 'Hugo', 'img/photos/Hugo.jpg'),
(3, 'HENROT', 'Boris', 'img/photos/Boris.jpg'),
(4, 'BARRA', 'Jeremy', 'img/photos/Jerem.jpg');

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
