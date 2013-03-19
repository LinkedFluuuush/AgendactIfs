-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 19 Mars 2013 à 18:10
-- Version du serveur: 5.1.63
-- Version de PHP: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jeanbaptiste_lou`
--
DROP DATABASE `aci_bdd`;
CREATE DATABASE `aci_bdd` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `aci_bdd`;

-- --------------------------------------------------------

--
-- Structure de la table `aci_composer`
--

DROP TABLE IF EXISTS `aci_composer`;
CREATE TABLE IF NOT EXISTS `aci_composer` (
  `IDUTILISATEUR` int(6) NOT NULL,
  `IDGROUPE` int(6) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDUTILISATEUR`,`IDGROUPE`),
  KEY `I_FK_aci_composer_ACI_UTILISAT` (`IDUTILISATEUR`),
  KEY `I_FK_aci_composer_aci_groupe` (`IDGROUPE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `aci_composer`
--

INSERT INTO `aci_composer` (`IDUTILISATEUR`, `IDGROUPE`, `DATEINSERT`) VALUES
(1, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `aci_contenir`
--

DROP TABLE IF EXISTS `aci_contenir`;
CREATE TABLE IF NOT EXISTS `aci_contenir` (
  `IDGROUPE` int(6) NOT NULL,
  `IDGROUPE_1` int(6) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDGROUPE`,`IDGROUPE_1`),
  KEY `I_FK_aci_contenir_aci_groupe` (`IDGROUPE`),
  KEY `I_FK_aci_contenir_aci_groupe1` (`IDGROUPE_1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_destgroupe`
--

DROP TABLE IF EXISTS `aci_destgroupe`;
CREATE TABLE IF NOT EXISTS `aci_destgroupe` (
  `IDEVENEMENT` int(6) NOT NULL,
  `IDGROUPE` int(6) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDEVENEMENT`,`IDGROUPE`),
  KEY `I_FK_aci_destgroupe_ACI_EVENEM` (`IDEVENEMENT`),
  KEY `I_FK_aci_destgroupe_aci_groupe` (`IDGROUPE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_destutilisateur`
--

DROP TABLE IF EXISTS `aci_destutilisateur`;
CREATE TABLE IF NOT EXISTS `aci_destutilisateur` (
  `IDUTILISATEUR` int(6) NOT NULL,
  `IDEVENEMENT` int(6) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDUTILISATEUR`,`IDEVENEMENT`),
  KEY `I_FK_aci_destutilisateur_ACI_U` (`IDUTILISATEUR`),
  KEY `I_FK_aci_destutilisateur_ACI_E` (`IDEVENEMENT`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_evenement`
--

DROP TABLE IF EXISTS `aci_evenement`;
CREATE TABLE IF NOT EXISTS `aci_evenement` (
  `IDEVENEMENT` int(6) NOT NULL,
  `IDUTILISATEUR` int(6) NOT NULL,
  `IDPRIORITE` int(2) NOT NULL,
  `IDLIEU` varchar(50) DEFAULT NULL,
  `LIBELLELONG` char(32) NOT NULL,
  `LIBELLECOURT` char(10) NOT NULL,
  `DESCRIPTION` char(255) DEFAULT NULL,
  `DATEDEBUT` datetime NOT NULL,
  `DATEFIN` datetime DEFAULT NULL,
  `ESTPUBLIC` int(1) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDEVENEMENT`),
  KEY `I_FK_aci_evenement_ACI_UTILISA` (`IDUTILISATEUR`),
  KEY `I_FK_aci_evenement_ACI_PRIORIT` (`IDPRIORITE`),
  KEY `I_FK_aci_evenement_aci_lieu` (`IDLIEU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_groupe`
--

DROP TABLE IF EXISTS `aci_groupe`;
CREATE TABLE IF NOT EXISTS `aci_groupe` (
  `IDGROUPE` int(6) NOT NULL,
  `LIBELLE` char(50) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDGROUPE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_lieu`
--

DROP TABLE IF EXISTS `aci_lieu`;
CREATE TABLE IF NOT EXISTS `aci_lieu` (
  `IDLIEU` int(6) NOT NULL,
  `LIBELLE` char(50) NOT NULL,
  `CATEGORIE` char(32) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDLIEU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_priorite`
--

DROP TABLE IF EXISTS `aci_priorite`;
CREATE TABLE IF NOT EXISTS `aci_priorite` (
  `IDPRIORITE` int(2) NOT NULL,
  `LIBELLEPRIORITE` char(20) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDPRIORITE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_rappel`
--

DROP TABLE IF EXISTS `aci_rappel`;
CREATE TABLE IF NOT EXISTS `aci_rappel` (
  `IDRAPPEL` int(6) NOT NULL,
  `IDEVENEMENT` int(6) NOT NULL,
  `IDUTILISATEUR` int(6) NOT NULL,
  `DATERAPPEL` datetime DEFAULT NULL,
  PRIMARY KEY (`IDRAPPEL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `aci_rappeler`
--

DROP TABLE IF EXISTS `aci_rappeler`;
CREATE TABLE IF NOT EXISTS `aci_rappeler` (
  `IDUTILISATEUR` int(6) NOT NULL,
  `IDPRIORITE` int(2) NOT NULL,
  `DATERAPPEL` datetime DEFAULT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDUTILISATEUR`,`IDPRIORITE`),
  KEY `I_FK_aci_rappeler_ACI_UTILISAT` (`IDUTILISATEUR`),
  KEY `I_FK_aci_rappeler_aci_priorite` (`IDPRIORITE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `aci_se_situer_dans`
--

DROP TABLE IF EXISTS `aci_se_situer_dans`;
CREATE TABLE IF NOT EXISTS `aci_se_situer_dans` (
  `IDLIEU` int(6) NOT NULL,
  `IDLIEU_1` int(6) NOT NULL,
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDLIEU`,`IDLIEU_1`),
  KEY `I_FK_aci_se_situer_dans_ACI_LI` (`IDLIEU`),
  KEY `I_FK_aci_se_situer_dans_ACI_L1` (`IDLIEU_1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `aci_utilisateur`
--

DROP TABLE IF EXISTS `aci_utilisateur`;
CREATE TABLE IF NOT EXISTS `aci_utilisateur` (
  `IDUTILISATEUR` int(6) NOT NULL,
  `NOM` char(32) NOT NULL,
  `PRENOM` char(32) NOT NULL,
  `ADRESSE_MAIL` char(50) NOT NULL,
  `IDENTIFIANT_DE_CONNEXION` char(32) NOT NULL,
  `PASS` char(32) NOT NULL,
  `RAPPELACTIVE` int(1) NOT NULL,
  `NOTIFICATIONACTIVE` int(1) NOT NULL,
  `RAPPELHAUTE` varchar(14) CHARACTER SET utf8 NOT NULL DEFAULT '00 00 00 12 00',
  `RAPPELMOYENNE` varchar(14) CHARACTER SET utf8 NOT NULL DEFAULT '01 00 00 00 00',
  `RAPPELBASSE` varchar(14) CHARACTER SET utf8 NOT NULL DEFAULT '00 00 00 00 00',
  `DATEINSERT` datetime DEFAULT NULL,
  PRIMARY KEY (`IDUTILISATEUR`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;