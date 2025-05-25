<?php
require('config.php');
require('classes/communicationBroker.php');
require('classes/model.php');
require('models/broker.php');
require('models/moduleDMXWiFi.php');
require '/var/www/html/artnet-2025/artnetAdministration/vendor/autoload.php';

// Gestion des modules DMXWiFi
// Code de base par Thierry VAIRA

// Ce script est démarré par le service artnet-php-mqtt.service
// $ sudo systemctl start artnet-php-mqtt.service
// État du service :
// $ systemctl status artnet-php-mqtt.service
// Journal du service :
// $ journalctl -u artnet-php-mqtt.service
//
// Installation du service :
// $ sudo cp artnet-php-mqtt.service /lib/systemd/system/artnet-php-mqtt.service
// $ sudo systemctl daemon-reload
// $ sudo systemctl enable artnet-php-mqtt.service

// La journalisation de ce script se trouve dans le fichier
// /var/log/artnet/gestionModulesDMXWiFi.log
function journaliser($message)
{
    $fichier = "/var/log/artnet/gestionModulesDMXWiFi.log";
    if (file_exists($fichier)) {
        $tag = '[' . date("d/m/Y H:i:s") . '] ';
        $message = $tag . $message;
        file_put_contents($fichier, $message . PHP_EOL, FILE_APPEND);
    }
}

// Programme principal
// Sélectionne le topic pour les nouveaux modules WiFi DMX
$topic = "artnet/config/#";
$qos = 0;
$timeout = 1; // période de récupération des messages en secondes (sinon 0 pour aucun timeout)

// Récupère le broker actif
$brokerModel = new BrokerModel();
$broker = $brokerModel->getBrokerMQTTActif();
$moduleDMXWiFiModel = new ModuleDMXWiFiModel();

// Il y a un broker actif ?
if ($broker) {
    // Instancie le broker
    $communicationBroker = new CommunicationBroker($broker);

    // Connecte le broker
    journaliser("Connexion au broker " . $broker["hostname"] . ":" . $broker["port"] . " ...");
    $resultatConnexion = $communicationBroker->connecter();
    if ($resultatConnexion) {
        if ($communicationBroker->estConnecte()) {
            journaliser("Broker connecté");
            // Souscrit au topic
            journaliser("Souscription au topic \"" . $topic . "\"");
            $resultatSouscription = $communicationBroker->souscrire($topic, $qos);
            if ($resultatSouscription) {
                // C'est un service ...
                while (true) {
                    // Reception des messages publiés sur le topic
                    $retour = $communicationBroker->recevoirMessages(null, $timeout);
                    if ($retour) {
                        // Récupère tous les messages reçus
                        $messages = $communicationBroker->getMessages();
                        if ($messages != null) {
                            foreach ($messages as $topicMessage => $listeMessages) {
                                foreach ($listeMessages as $message) {
                                    journaliser("Réception du message : \"" . $message . "\" sur le topic \"" . $topicMessage . "\"");
                                    $ajout = $moduleDMXWiFiModel->add($message);
                                    if ($ajout) {
                                        journaliser("Modules DMXWiFi ajouté !");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // Déconnecte le broker
            $communicationBroker->deconnecter();
        }
    } else {
        journaliser("Erreur de connexion au broker ! \"" . $topic . "\"");
    }
} else {
    journaliser("Aucun broker actif !");
}
