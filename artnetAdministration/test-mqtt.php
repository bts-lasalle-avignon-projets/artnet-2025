<?php
require('config.php');
require('classes/communicationBroker.php');
require('classes/model.php');
require './vendor/autoload.php';

class ReceptionMQTT extends Model
{
    private CommunicationBroker $communicationBroker;
    private array $broker;

    public function __construct()
    {
        parent::__construct();
        // Récupère le broker actif
        $this->broker = $this->getBrokerMQTTActif();
        // Instancie le broker
        $this->communicationBroker = new CommunicationBroker($this->broker);
    }

    public function recevoir($topic)
    {
        // Connecte le broker
        $resultatConnexion = $this->communicationBroker->connecter();
        if ($resultatConnexion) {
            if ($this->communicationBroker->estConnecte()) {
                // Souscrit au topic de test
                $resultatSouscription = $this->communicationBroker->souscrire($topic, 0);
                if ($resultatSouscription) {
                    // Reçoit le message
                    $retour = $this->communicationBroker->recevoirMessage($topic, 15); // 10 secondes
                    var_dump($retour);
                    if ($retour) {
                        // Récupère le premier message reçu sur le topic de test
                        $message = $this->communicationBroker->getMessage($topic);
                        if ($message != null) {
                            echo ("Réception du message : \"" . $message . "\" sur le topic \"" . $topic . "\"");
                        }
                    }
                }
                // Déconnecte le broker
                $this->communicationBroker->deconnecter();
            }
        }
    }

    public function getBrokerMQTTActif()
    {
        // Récupère le broker actif
        $this->query("SELECT * FROM brokerMQTT WHERE actif = 1");
        $broker = $this->getResult();
        return $broker ?? null;
    }
}

$receptionMQTT = new ReceptionMQTT();
$receptionMQTT->recevoir("artnet/test");
die;
