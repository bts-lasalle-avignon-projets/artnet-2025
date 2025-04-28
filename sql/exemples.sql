--
-- Exemple de données 
--

INSERT INTO brokerMQTT (idBrokerMQTT,hostname,port,username,password,actif) VALUES (1, '192.168.1.104', 1883, NULL, NULL, 1);

INSERT INTO moduleDMXWiFi (univers,nomBoitier,adresseIP,adresseMAC,rssi,actif) VALUES(50,'Artnet-051C','192.168.1.1','24:62:AB:F3:05:1C',-50,1);

INSERT INTO typeEquipementDMX (typeEquipement,nbCanaux) VALUES
('PAR LED 1', 4),
('SCANNER', 3),
('LYRE', 5),
('PAR LED 2', 3);

INSERT INTO equipementDMX (`idEquipement`,`univers`,`nomEquipement`,`idTypeEquipement`,`canalInitial`) VALUES
(1,50,'PAR LED Sud',1,1),
(2,50,'Scanner',2,31),
(3,50,'LYRE au sol',3,132),
(4,50,'PAR LED Nord',4,10);

INSERT INTO scenario (nomScenario,creationScenario) VALUES ('Salle 1',NOW());

INSERT INTO scene (univers,nomScene,canaux,creationScene) VALUES
(50,'Scène 1','[{"canal":10, "valeur":255},{"canal":11, "valeur":10},{"canal":12, "valeur":55}]',NOW()),

INSERT INTO `sequence` (idScenario,idScene,numeroScene,temporisation) VALUES (1,1,1,5);
