<?php
require('config.php');
require('classes/communicationBroker.php');
require('classes/model.php');
require('models/broker.php');
require './vendor/autoload.php';

// Exemple subscribe

// Sélectionne le topic
$topic = "artnet/#";
$qos = 0;
$timeout = 15; // en secondes (sinon 0 pour aucun timeout)

// Récupère le broker actif
$brokerModel = new BrokerModel();
$broker = $brokerModel->getBrokerMQTTActif();
var_dump($broker);

// Instancie le broker
$communicationBroker = new CommunicationBroker($broker);

// Connecte le broker
$resultatConnexion = $communicationBroker->connecter();
if ($resultatConnexion) {
    if ($communicationBroker->estConnecte()) {
        // Souscrit au topic de test
        $resultatSouscription = $communicationBroker->souscrire($topic, $qos);
        if ($resultatSouscription) {
            // Exemple 1 : reçoit un message
            /*
            $retour = $communicationBroker->recevoirMessage($topic, $timeout);
            if ($retour) {
                // Récupère le premier message reçu sur le topic de test
                $message = $communicationBroker->getMessage($topic);
                if ($message != null) {
                    echo "Réception du message : \"" . $message . "\" sur le topic \"" . $topic . "\"" . PHP_EOL;
                }
            }*/
            // Exemple 2 : reçoit plusieurs messages
            $retour = $communicationBroker->recevoirMessages(null, $timeout);
            if ($retour) {
                // Récupère tous les messages reçus
                $messages = $communicationBroker->getMessages();
                if ($messages != null) {
                    foreach ($messages as $topicMessage => $listeMessages) {
                        echo count($listeMessages) . " message(s) reçu(s) sur le topic \"" . $topicMessage . "\"" . PHP_EOL;
                        foreach ($listeMessages as $message) {
                            echo "Réception du message : \"" . $message . "\" sur le topic \"" . $topicMessage . "\"" . PHP_EOL;
                        }
                    }
                }
            }
        }
        // Déconnecte le broker
        $communicationBroker->deconnecter();
    }
} else {
    echo ("Erreur de connexion au broker !");
}
