-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 17 mai 2021 à 08:02
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
-- Base de données : `projet_slam1`
--

-- --------------------------------------------------------

--
-- Structure de la table `score`
--

DROP TABLE IF EXISTS `score`;
CREATE TABLE IF NOT EXISTS `score` (
  `idJoueur` varchar(250) COLLATE latin1_bin NOT NULL,
  `idPartie` int(11) NOT NULL AUTO_INCREMENT,
  `nbTour` int(4) NOT NULL,
  `datePartie` date NOT NULL,
  `resultat` tinyint(1) NOT NULL,
  PRIMARY KEY (`idPartie`),
  KEY `idJoueur` (`idJoueur`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `score`
--

INSERT INTO `score` (`idJoueur`, `idPartie`, `nbTour`, `datePartie`, `resultat`) VALUES
('test2', 2, 10, '2005-11-21', 1),
('test2', 3, 10, '2005-11-21', 1),
('test2', 4, 10, '2005-11-21', 1),
('test2', 5, 10, '2005-11-21', 1),
('test2', 6, 20, '2005-11-21', 1),
('test2', 7, 5, '2005-11-21', 1),
('test2', 8, 3, '2005-11-21', 1),
('okok', 9, 20, '2005-11-21', 1),
('hacker', 10, 1, '2005-11-21', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(250) COLLATE latin1_bin NOT NULL,
  `Mdp` varchar(250) COLLATE latin1_bin NOT NULL,
  `Partie` tinyint(1) NOT NULL,
  `NbTour` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `Nom`, `Mdp`, `Partie`, `NbTour`) VALUES
(1, 'az', 'az', 0, 0),
(2, 'ae', '$2y$10$OPZ6rNcNxR4wERXx4f.q5.58Nz7SdtWQxs0U6zaoAwvxQjFEpG6WW', 0, 0),
(3, 'ae', '$2y$10$.guDU5Z2f8Mniah/JU8zReUY9IjNE0c8cm.kCJqNGwvkeoHfRUgYe', 0, 0),
(4, 'test', '$2y$10$C.6RXRf1hUgRfBylaCKbCO7EflibyhkPwyG0CPZs3C3on7LGU5XF2', 0, 0),
(5, 'test', '$2y$10$3XzVAo5CqXOXXd.PsU54VukRiNnbtfWpgU1l0hbmNPS1BFTIuqMt6', 0, 0),
(6, 'toi', '$2y$10$k6RpPzmdxtwtDqRN.Jp20OsQwypmG/Yxpk9FU0WvwB9RnfFNFLYf6', 0, 0),
(7, 'toi', '$2y$10$gejaQ6H5pN4S.3ngEccteuerIqmWIEsfh3BE4SA2miVD5qA.CHd3q', 0, 0),
(8, 'toi', '$2y$10$nDc0crWBIYbNiu/kvWzayO0D7jKrg4u6hfsrHJgMG6L/Jb.az4LQi', 0, 0),
(9, 'Vince', '$2y$10$Oi/VzBvQ2r0gexpOvpSjc.koz9/uh6oMFVQXkhPCBNg0GHUdrEkeC', 0, 0),
(10, 'test2', '0', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
