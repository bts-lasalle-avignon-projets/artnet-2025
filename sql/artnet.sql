-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 25 avr. 2025 à 09:49
-- Version du serveur :  8.0.41-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `artnet`
--

-- --------------------------------------------------------

--
-- Structure de la table `accesBrokerMQTT`
--

CREATE TABLE `accesBrokerMQTT` (
  `ipBroker` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '192.168.1.104',
  `portBroker` int NOT NULL DEFAULT '1883'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

CREATE TABLE `equipement` (
  `idEquipement` int NOT NULL,
  `univers` int NOT NULL,
  `nomEquipement` varchar(255) NOT NULL,
  `typeEquipement` varchar(255) NOT NULL,
  `nbCanaux` int NOT NULL,
  `canalInitial` int NOT NULL,
  `canaux` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `moduleDMXWiFi`
--

CREATE TABLE `moduleDMXWiFi` (
  `univers` int NOT NULL,
  `nomBoitier` varchar(255) NOT NULL,
  `adresseIP` varchar(39) NOT NULL,
  `adresseMAC` varchar(17) NOT NULL,
  `rssi` int NOT NULL,
  `actif` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scenario`
--

CREATE TABLE `scenario` (
  `idScenario` int NOT NULL,
  `nomScenario` varchar(255) NOT NULL,
  `tempsScenario` time NOT NULL,
  `sequenceScene` json NOT NULL COMMENT 'idScene + tempsScene'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scene`
--

CREATE TABLE `scene` (
  `idScene` int NOT NULL,
  `nomScene` varchar(255) NOT NULL,
  `canaux` json NOT NULL,
  `creationScene` datetime NOT NULL,
  `modificationScene` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipement`
--
ALTER TABLE `equipement`
  ADD PRIMARY KEY (`idEquipement`),
  ADD UNIQUE KEY `univers` (`univers`);

--
-- Index pour la table `moduleDMXWiFi`
--
ALTER TABLE `moduleDMXWiFi`
  ADD PRIMARY KEY (`univers`);

--
-- Index pour la table `scenario`
--
ALTER TABLE `scenario`
  ADD PRIMARY KEY (`idScenario`);

--
-- Index pour la table `scene`
--
ALTER TABLE `scene`
  ADD PRIMARY KEY (`idScene`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `equipement`
--
ALTER TABLE `equipement`
  ADD CONSTRAINT `lienUnivers` FOREIGN KEY (`univers`) REFERENCES `moduleDMXWiFi` (`univers`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
