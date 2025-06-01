--
-- Base de données : `artnet`
--

DROP TABLE IF EXISTS `sequence`;
DROP TABLE IF EXISTS `scene`;
DROP TABLE IF EXISTS `scenario`;
DROP TABLE IF EXISTS `equipementDMX`;
DROP TABLE IF EXISTS `typeEquipementDMX`;
DROP TABLE IF EXISTS `moduleDMXWiFi`;
DROP TABLE IF EXISTS `brokerMQTT`;

-- --------------------------------------------------------

--
-- Structure de la table `brokerMQTT`
--

CREATE TABLE IF NOT EXISTS `brokerMQTT` (
  `idBrokerMQTT` int AUTO_INCREMENT PRIMARY KEY,
	`hostname`	varchar(255) NOT NULL,
	`port`	int NOT NULL DEFAULT '1883',
	`username`	varchar(64) DEFAULT NULL,
	`password`	varchar(64) DEFAULT NULL,
	`actif` tinyint(1) NOT NULL DEFAULT '0',
  CONSTRAINT Unique_Broker UNIQUE (hostname,port)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `moduleDMXWiFi`
--

CREATE TABLE IF NOT EXISTS `moduleDMXWiFi` (
  `univers` int NOT NULL,
  `nomBoitier` varchar(255) NOT NULL,
  `adresseIP` varchar(39) NOT NULL,
  `adresseMAC` varchar(17) NOT NULL,
  `rssi` int NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `moduleDMXWiFi`
  ADD PRIMARY KEY (`univers`);

-- --------------------------------------------------------

--
-- Structure de la table `typeEquipementDMX`
--

CREATE TABLE IF NOT EXISTS `typeEquipementDMX` (
  `idTypeEquipement` int AUTO_INCREMENT PRIMARY KEY,
  `typeEquipement` varchar(255) NOT NULL,
  `nbCanaux` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipementDMX`
--

CREATE TABLE IF NOT EXISTS `equipementDMX` (
  `idEquipement` int AUTO_INCREMENT PRIMARY KEY,
  `univers` int NOT NULL,
  `nomEquipement` varchar(255) NOT NULL,
  `idTypeEquipement` int NOT NULL,
  `canalInitial` int NOT NULL,
  `canaux` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `equipementDMX`
  ADD CONSTRAINT `lienUnivers` FOREIGN KEY (`univers`) REFERENCES `moduleDMXWiFi` (`univers`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lienTypeEquipement` FOREIGN KEY (`idTypeEquipement`) REFERENCES `typeEquipementDMX` (`idTypeEquipement`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Structure de la table `scenario`
--

CREATE TABLE IF NOT EXISTS `scenario` (
  `idScenario` int AUTO_INCREMENT PRIMARY KEY,
  `nomScenario` varchar(255) NOT NULL,
  `creationScenario` datetime NOT NULL,
  `modificationScenario` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scene`
--

CREATE TABLE IF NOT EXISTS `scene` (
  `idScene` int AUTO_INCREMENT PRIMARY KEY,
  `univers` int NOT NULL,
  `nomScene` varchar(255) NOT NULL,
  `canaux` json NOT NULL,
  `creationScene` datetime NOT NULL,
  `modificationScene` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `scene`
  ADD CONSTRAINT `lienUniversScene` FOREIGN KEY (`univers`) REFERENCES `moduleDMXWiFi` (`univers`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Structure de la table `sequence`
--

CREATE TABLE IF NOT EXISTS `sequence` (
  `idSequence` int AUTO_INCREMENT PRIMARY KEY,
  `idScenario` int NOT NULL,
  `idScene` int NOT NULL,
  `numeroScene` int NOT NULL,
  `temporisation` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `sequence`
  ADD CONSTRAINT `lienScenario` FOREIGN KEY (`idScenario`) REFERENCES `scenario` (`idScenario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lienScene` FOREIGN KEY (`idScene`) REFERENCES `scene` (`idScene`) ON DELETE CASCADE ON UPDATE CASCADE;
