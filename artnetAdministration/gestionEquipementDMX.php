<?php
require('config.php');
require('classes/communicationBroker.php');
require('classes/model.php');
require('models/broker.php');
require('models/equipement.php');
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

// Installation du script pour le service:
// $ sudo cp chemin-artnet.sh /usr/local/bin/chemin-artnet.sh
// $ sudo chmod +x /usr/local/bin/chemin-artnet.sh

// La journalisation de ce script se trouve dans le fichier
// /var/log/artnet/gestionEquipementDMX.log
function journaliser($message)
{
    $fichier = "/var/log/artnet/gestionEquipementDMX.log";
    if (file_exists($fichier)) {
        $tag = '[' . date("d/m/Y H:i:s") . '] ';
        $message = $tag . $message;
        file_put_contents($fichier, $message . PHP_EOL, FILE_APPEND);
    }
}

// Programme principal
// Sélectionne le topic pour les équipement DMX
$topicSouscrit = "artnet/bdd/equipements/ecriture/#";
$topicPublish = "artnet/bdd/equipements/lecture";
$qos = 0;
$timeout = 1; // période de récupération des messages en secondes (sinon 0 pour aucun timeout)

// Récupère le broker actif
$brokerModel = new BrokerModel();
$broker = $brokerModel->getBrokerMQTTActif();
$equipementDMXModel = new EquipementDMXModel();

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
            journaliser("Souscription au topic \"" . $topicSouscrit . "\"");
            $resultatSouscription = $communicationBroker->souscrire($topicSouscrit, $qos);
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
                                    $ajout = $equipementDMXModel->addEquipementDepuisTopic($message);
                                    if ($ajout) {
                                        journaliser("Equipement DMX ajouté !");
                                    }
                                }
                            }
                        }
                    }

                    // Publier les données de la base de données
                    $equipements = $equipementDMXModel->getAllEquipementsDMX();

                    foreach ($equipements as $equipement) {
                        $nom = $equipement['nomEquipement'] ?? 'equipement_inconnu';
                        $topic = $topicPublish . '/' . $nom;

                        $jsonEquipement = json_encode($equipement);
                        $communicationBroker->publier($topic, $jsonEquipement, $qos);

                        journaliser("Données publiées : " . $jsonEquipement . " sur le topic \"" . $topic . "\"");

                        sleep(1);
                    }
                }
            }
            // Déconnecte le broker
            $communicationBroker->deconnecter();
        }
    } else {
        journaliser("Erreur de connexion au broker ! \"" . $topicSouscrit . "\"");
    }
} else {
    journaliser("Aucun broker actif !");
}
