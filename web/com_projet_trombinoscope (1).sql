-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 30 Mars 2016 à 00:18
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
  `promotion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `promotion` (`promotion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=583 ;

--
-- Contenu de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `noEtudiant`, `nom`, `prenom`, `url_photo`, `promotion`) VALUES
(499, 21522624, 'ALIOT', 'MICKAEL', 'img/photos/default.gif', 84),
(500, 21522917, 'AMBRY', 'MAXIME', 'img/photos/default.gif', 84),
(501, 21424448, 'AMILHAUD', 'MARTIN', 'img/photos/default.gif', 84),
(502, 21523281, 'AUBE', 'MATHIEU', 'img/photos/default.gif', 84),
(503, 21522236, 'AUSSAGUEL', 'LAMBERT', 'img/photos/default.gif', 84),
(504, 21521417, 'BARAMA', 'RAYAN', 'img/photos/default.gif', 84),
(505, 21524290, 'BATTON', 'HUGO', 'img/photos/default.gif', 84),
(506, 21524496, 'BECHARI', 'BILAL', 'img/photos/default.gif', 84),
(507, 21520082, 'BERARD', 'DYLAN', 'img/photos/default.gif', 84),
(508, 21520009, 'BILLON', 'EVA', 'img/photos/default.gif', 84),
(509, 21523840, 'BLAZY', 'ENZO', 'img/photos/default.gif', 84),
(510, 21520838, 'BOGACZYK', 'PIERRE', 'img/photos/default.gif', 84),
(511, 21521010, 'BOUREAU', 'EMMANUEL', 'img/photos/default.gif', 84),
(512, 21520152, 'BRET', 'XAVIER', 'img/photos/default.gif', 84),
(513, 21524112, 'BRISAC', 'ALFRED', 'img/photos/default.gif', 84),
(514, 21520073, 'CABROL', 'BENJAMIN', 'img/photos/default.gif', 84),
(515, 21524357, 'CANSEV', 'ALI', 'img/photos/default.gif', 84),
(516, 21520398, 'CARMICHAEL', 'JADE', 'img/photos/default.gif', 84),
(517, 21523929, 'CHASSAING', 'LUKA', 'img/photos/default.gif', 84),
(518, 21520016, 'CLUSEL', 'MATHIEU', 'img/photos/default.gif', 84),
(519, 21526441, 'COSTE', 'CLEMENT', 'img/photos/default.gif', 84),
(520, 21521967, 'COSTECHAREYRE', 'BENOIT', 'img/photos/default.gif', 84),
(521, 21520010, 'DAZY', 'NICODEME', 'img/photos/default.gif', 84),
(522, 21522036, 'DELARBRE', 'MORGAN', 'img/photos/default.gif', 84),
(523, 21528998, 'DELAUNAY', 'JEAN', 'img/photos/default.gif', 84),
(524, 21524014, 'DESTRAIT', 'CHARLOTTE', 'img/photos/default.gif', 84),
(525, 21520144, 'DURIEUX', 'ALEXANDRE', 'img/photos/default.gif', 84),
(526, 21523890, 'FAYANT', 'DYLAN', 'img/photos/default.gif', 84),
(527, 21524131, 'FERRANT', 'HUGO', 'img/photos/default.gif', 84),
(528, 21522304, 'FLUCHAIRE', 'VICTOR', 'img/photos/default.gif', 84),
(529, 21522993, 'FOEX', 'SIMON', 'img/photos/default.gif', 84),
(530, 21521554, 'GEORGE--LEXCELLENT', 'ADRIEN', 'img/photos/default.gif', 84),
(531, 21524480, 'GERIN', 'GAUTHIER', 'img/photos/default.gif', 84),
(532, 21520029, 'GODART', 'QUENTIN', 'img/photos/default.gif', 84),
(533, 21523805, 'GOLMARD', 'JOHANN', 'img/photos/default.gif', 84),
(534, 21523847, 'GRAVIER', 'AUDRAN', 'img/photos/default.gif', 84),
(535, 21520143, 'GRIMAUD', 'CEDRIC', 'img/photos/default.gif', 84),
(536, 21522195, 'HASSAINE', 'ABDERRAHMANE', 'img/photos/default.gif', 84),
(537, 21523723, 'HENRION', 'JULIEN', 'img/photos/default.gif', 84),
(538, 21520088, 'JACOB', 'JULIEN', 'img/photos/default.gif', 84),
(539, 21523851, 'JOURDAN', 'NICOLAS', 'img/photos/default.gif', 84),
(540, 21523268, 'LABAN', 'BENOIT', 'img/photos/default.gif', 84),
(541, 21520129, 'LAPCHIK', 'ANDREY', 'img/photos/default.gif', 84),
(542, 21521940, 'LECOINTE', 'JEREMY', 'img/photos/default.gif', 84),
(543, 21520423, 'LECOMTE', 'LOIC', 'img/photos/default.gif', 84),
(544, 21520087, 'LEFEUVRE', 'VALENTIN', 'img/photos/default.gif', 84),
(545, 21420362, 'LOGUT', 'NICOLAS', 'img/photos/default.gif', 84),
(546, 21520038, 'LORETTE-FROIDEVAUX', 'THEO', 'img/photos/default.gif', 84),
(547, 21520728, 'LUCIANO', 'RAPHAEL', 'img/photos/default.gif', 84),
(548, 21520826, 'MAHE', 'FLORIAN', 'img/photos/default.gif', 84),
(549, 21523568, 'MAHIR', 'MEHDI', 'img/photos/default.gif', 84),
(550, 21521524, 'MARTIN', 'GUILLAUME', 'img/photos/default.gif', 84),
(551, 21523014, 'MARTIN', 'THIBAUT', 'img/photos/default.gif', 84),
(552, 21520049, 'MATHLOUTHI', 'SARAH', 'img/photos/default.gif', 84),
(553, 21520121, 'MORDOHAY', 'WILLIAM', 'img/photos/default.gif', 84),
(554, 21523307, 'MURER', 'RUDY', 'img/photos/default.gif', 84),
(555, 21520076, 'NEYRET', 'OLIVIER', 'img/photos/default.gif', 84),
(556, 21524090, 'PALMIER', 'BENJAMIN', 'img/photos/default.gif', 84),
(557, 21520160, 'PASQUIOU', 'PAUL', 'img/photos/default.gif', 84),
(558, 21422055, 'PETIT', 'THOMAS', 'img/photos/default.gif', 84),
(559, 21521748, 'PEYRONNET', 'CEDRIC', 'img/photos/default.gif', 84),
(560, 21421378, 'PIGNARD', 'FLORIAN', 'img/photos/default.gif', 84),
(561, 21522528, 'PINTRAND', 'THIBAUT', 'img/photos/default.gif', 84),
(562, 21520093, 'POPEK', 'NICOLAS', 'img/photos/default.gif', 84),
(563, 21521390, 'PRADES', 'MICKAEL', 'img/photos/default.gif', 84),
(564, 21520080, 'PREVOST', 'NICOLAS', 'img/photos/default.gif', 84),
(565, 21523993, 'RAMOS', 'CYRIL', 'img/photos/default.gif', 84),
(566, 21520066, 'REDON', 'RAPHAEL', 'img/photos/default.gif', 84),
(567, 21520097, 'REY', 'QUENTIN', 'img/photos/default.gif', 84),
(568, 21527872, 'REYNAUD', 'LOUIS', 'img/photos/default.gif', 84),
(569, 21520372, 'RIVOIRE', 'GASPARD', 'img/photos/default.gif', 84),
(570, 21523409, 'ROSTAING', 'MEHDI', 'img/photos/default.gif', 84),
(571, 21521688, 'SABOURIN', 'YOANN', 'img/photos/default.gif', 84),
(572, 21523814, 'SAJIDE', 'ADIL', 'img/photos/default.gif', 84),
(573, 21523875, 'SERRAZ', 'MATTHIEU', 'img/photos/default.gif', 84),
(574, 21523443, 'SOUBEYRAND', 'JULES', 'img/photos/default.gif', 84),
(575, 21520090, 'STITI', 'ILIAS', 'img/photos/default.gif', 84),
(576, 21524007, 'TAGHAT', 'RACHID', 'img/photos/default.gif', 84),
(577, 21520083, 'TURPIN', 'ANGELIQUE', 'img/photos/default.gif', 84),
(578, 21520150, 'VALAYER', 'BAPTISTE', 'img/photos/default.gif', 84),
(579, 21520154, 'VEYRE', 'AURELIEN', 'img/photos/default.gif', 84),
(580, 21525212, 'VIDAL', 'FLORENT', 'img/photos/default.gif', 84),
(581, 236598, 'LACONDEMINE', 'Virgil', '.', 85),
(582, 365984, 'LEPRUNIER', 'Hugo', '.', 86);

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
(36, 'TP4', 4, 30);

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` int(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Contenu de la table `promotion`
--

INSERT INTO `promotion` (`id`, `annee`) VALUES
(84, 2018),
(85, 2017),
(86, 2016);

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
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`promotion`) REFERENCES `promotion` (`id`);

--
-- Contraintes pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `groupe_ibfk_1` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id`),
  ADD CONSTRAINT `groupe_ibfk_2` FOREIGN KEY (`id_pere`) REFERENCES `groupe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
