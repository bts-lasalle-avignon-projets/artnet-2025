<?php
$topic = "artnet/config";
$qos = 0;
$timeout = 10; // en secondes (sinon 0 pour aucun timeout)

class ModuleDMXWiFiModel extends Model
{
    public function index()
	{
		// Récupère la liste des module DMX WiFi
		$this->query("
			SELECT *
			FROM moduleDMXWiFi
			ORDER BY nomBoitier
		");

		$moduleDMX = $this->getResults();

		return $moduleDMX;
	}

    public function test()
    {
        // Instancie le module
        $communicationModule = new CommunicationModule($broker);
        
        // Connecte le module
        $connectionResult = $communicationModule->connecter();

        if ($connectionResult) {
            Messages::setMsg("Test de connexion au module réussi !", "success");
            $communicationModule->deconnecter();
            return ACTION_SUCCESS;
        } else {
            Messages::setMsg("Erreur de connexion au module !", "error");
            return ACTION_ERREUR;
        }
    }

    public function sauvegardeTopicConfig()
    {
        // Instancie le module
        $communicationModule = new CommunicationModule($broker);
        
        // Connecte le module
        $communicationModule->connecter();

        // Souscrit au topic artnet/config
        $resultatSouscription = $communicationModule->souscrire($broker['topic'] . $topic, $qos);
        if ($resultatSouscription) {
            Messages::setMsg("Souscription au topic artnet/config réussie !", "success");

            // Recevoir le message
            $result = $communicationModule->recevoirMessage($broker['topic'] . $topic, $timeout); 
            if ($result) {
                // Récupère le message reçu sur le topic artnet/config
                $message = $communicationModule->getMessage($broker['topic'] . $topic);
                if ($message != null) {
                    // Enregistre le message dans la base de données
                    if ($this->enregistrerDansBDD($message)) {
                        Messages::setMsg("Message reçu et enregistré : \"" . $message . "\" sur le topic \"" . $broker['topic'] . $topic, "success");
                        return ACTION_SUCCESS;
                    } else {
                        Messages::setMsg("Erreur lors de l'enregistrement du message dans la base de données !", "error");
                        return ACTION_ERREUR;
                    }
                } else {
                    Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . $topic . " !", "error");
                    return ACTION_ERREUR;
                }
            } else {
                Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . $topic . " !", "error");
                return ACTION_ERREUR;
            }
        } else {
            Messages::setMsg("Erreur lors de la souscription au topic artnet/config !", "error");
            return ACTION_ERREUR;
        }

        $communicationModule->deconnecter();
    }

    private function enregistrerDansBDD($message)
    {
        // Insère le module dans la base de données
        try {
            $this->query("INSERT INTO moduleDMXWiFi (univers,nomBoitier,adresseIP,adresseMAC,rssi,actif) 
                            VALUES (:univers, :nomBoitier, :adresseIP, :adresseMAC, :rssi, :actif)");
            $this->bind(':univers', $univers);
            $this->bind(':nomBoitier', $nomBoitier);
            $this->bind(':adresseIP', $adresseIP);
            $this->bind(':adresseMAC', $adresseMAC);
            $this->bind(':rssi', $rssi);
            $this->bind(':actif', $actif);
            $this->execute();
            Messages::setMsg("Module ajouté avec succès !", "success");
            return ACTION_SUCCESS;
        } catch (PDOException $e) {
            Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
            return ACTION_ERREUR;
        }
    }
}