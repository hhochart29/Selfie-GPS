-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 07 Mai 2016 à 17:23
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `selfie_gps_bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `caracterisation`
--

CREATE TABLE IF NOT EXISTS `caracterisation` (
  `id_caracterisation` int(11) NOT NULL AUTO_INCREMENT,
  `idphoto` int(11) NOT NULL,
  `idtag` int(11) NOT NULL,
  PRIMARY KEY (`id_caracterisation`),
  KEY `idphoto` (`idphoto`),
  KEY `idtag` (`idtag`),
  KEY `idphoto_2` (`idphoto`),
  KEY `idtag_2` (`idtag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=208 ;

--
-- Contenu de la table `caracterisation`
--

INSERT INTO `caracterisation` (`id_caracterisation`, `idphoto`, `idtag`) VALUES
(201, 95, 4),
(202, 96, 6),
(203, 97, 1),
(204, 97, 6),
(205, 98, 1),
(206, 99, 8),
(207, 99, 9);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `idphoto` int(11) NOT NULL AUTO_INCREMENT,
  `fichier` text NOT NULL,
  `geo_lat` float NOT NULL,
  `geo_long` float NOT NULL,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`idphoto`),
  KEY `iduser` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`idphoto`, `fichier`, `geo_lat`, `geo_long`, `titre`, `description`, `date`, `email`) VALUES
(95, '2a5b29f975fd6258a31a7c8865ba238e.jpg', 42.9921, -1.01135, 'CrÃªte d''alupigna', 'Vacances dans les pyrÃ©nÃ©es', '2016-05-07 17:03:31', 'pierre@gmail.com'),
(96, '6b486138def7a8cbe03a46e50e35fa4f.jpg', 35.3405, 7.30837, 'Khenchela, AlgÃ©rie', 'Vacances en AlgÃ©rie', '2016-05-07 17:05:18', 'pierre@gmail.com'),
(97, '81e76b326748a168c2f2c1390a3dfc65.jpg', 48.633, -1.51004, 'Mont Saint-Michel', 'balade au Mont Saint-Michel', '2016-05-07 17:06:40', 'pierre@gmail.com'),
(98, '62d13f1536bd2b8e4f0b479110c30758.JPG', 47.8716, -3.91635, 'Vile close Concarneau', 'Weekend en bretagne', '2016-05-07 17:09:34', 'mariedupont@hotmail.fr'),
(99, '34c4ba728444a16bae2add3e5024d136.jpg', 50.291, 2.77743, 'Beffroi d''Arras', 'Vacances de noÃ«l', '2016-05-07 17:11:32', 'mariedupont@hotmail.fr');

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `idtag` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  PRIMARY KEY (`idtag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`idtag`, `description`) VALUES
(1, 'Mer'),
(2, 'Lacs'),
(3, 'Rivi&egrave;res'),
(4, 'Montagnes'),
(5, 'For&ecirc;t'),
(6, 'Nature'),
(7, 'Neige'),
(8, 'Nuit'),
(9, 'Ville'),
(10, 'Parcs'),
(11, 'Rue'),
(12, 'Couch&eacute; de soleil'),
(13, 'Lev&eacute; de soleil'),
(14, 'Ciel'),
(15, 'Fleurs');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `email` varchar(255) NOT NULL,
  `niveau` text NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`email`, `niveau`, `password`) VALUES
('admin', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997'),
('admin2', 'banni', '315f166c5aca63a157f7d41007675cb44a948b33'),
('admin3', 'banni', '33aab3c7f01620cade108f488cfd285c0e62c1ec'),
('admin4', 'authentifie', 'ea053d11a8aad1ccf8c18f9241baeb9ec47e5d64'),
('mariedupont@hotmail.fr', 'authentifie', 'db6d1703ed3a4826b186a1612c9e25a773a251ac'),
('pierre@gmail.com', 'authentifie', 'ff019a5748a52b5641624af88a54a2f0e46a9fb5'),
('thomas@gmail.com', 'authentifie', '5f50a84c1fa3bcff146405017f36aec1a10a9e38');

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `idvote` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `idphoto` int(11) NOT NULL,
  `vote` tinyint(1) NOT NULL,
  PRIMARY KEY (`idvote`),
  KEY `iduser` (`email`),
  KEY `idphoto` (`idphoto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=209 ;

--
-- Contenu de la table `vote`
--

INSERT INTO `vote` (`idvote`, `email`, `idphoto`, `vote`) VALUES
(194, 'mariedupont@hotmail.fr', 99, 1),
(195, 'mariedupont@hotmail.fr', 98, 0),
(196, 'mariedupont@hotmail.fr', 97, 1),
(197, 'mariedupont@hotmail.fr', 96, 0),
(198, 'mariedupont@hotmail.fr', 95, 1),
(199, 'pierre@gmail.com', 99, 1),
(200, 'pierre@gmail.com', 98, 1),
(201, 'pierre@gmail.com', 97, 1),
(202, 'pierre@gmail.com', 96, 0),
(203, 'pierre@gmail.com', 95, 1),
(204, 'thomas@gmail.com', 99, 0),
(205, 'thomas@gmail.com', 98, 1),
(206, 'thomas@gmail.com', 97, 0),
(207, 'thomas@gmail.com', 96, 0),
(208, 'thomas@gmail.com', 95, 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `caracterisation`
--
ALTER TABLE `caracterisation`
  ADD CONSTRAINT `id_photo_caracterisation` FOREIGN KEY (`idphoto`) REFERENCES `photo` (`idphoto`),
  ADD CONSTRAINT `id_tag_caracterisation` FOREIGN KEY (`idtag`) REFERENCES `tag` (`idtag`);

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `email_photo` FOREIGN KEY (`email`) REFERENCES `user` (`email`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `email_vote` FOREIGN KEY (`email`) REFERENCES `user` (`email`),
  ADD CONSTRAINT `idphoto_vote` FOREIGN KEY (`idphoto`) REFERENCES `photo` (`idphoto`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
