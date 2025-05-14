<?php

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

    public function sauvegardeTopicConfig()
    {
        // Instancie le broker
        $communicationBroker = new CommunicationBroker($broker);
        
        // Connecte le broker
        $communicationBroker->connecter();

        // Souscrit au topic artnet/config
        $resultatSouscription = $communicationBroker->souscrire($broker['topic'] . "/artnet/config", 0);
        if ($resultatSouscription) {
            Messages::setMsg("Souscription au topic artnet/config réussie !", "success");

            // Recevoir le message
            $result = $communicationBroker->recevoirMessage($broker['topic'] . "/artnet/config", 10); // 10 secondes
            if ($result) {
                // Récupère le message reçu sur le topic artnet/config
                $message = $communicationBroker->getMessage($broker['topic'] . "/artnet/config");
                if ($message != null) {
                    // Enregistre le message dans la base de données
                    if ($this->enregistrerDansBDD($message)) {
                        Messages::setMsg("Message reçu et enregistré : \"" . $message . "\" sur le topic \"" . $broker['topic'] . "/artnet/config\"", "success");
                        return ACTION_SUCCESS;
                    } else {
                        Messages::setMsg("Erreur lors de l'enregistrement du message dans la base de données !", "error");
                        return ACTION_ERREUR;
                    }
                } else {
                    Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . "/artnet/config\"" . " !", "error");
                    return ACTION_ERREUR;
                }
            } else {
                Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . "/artnet/config\"" . " !", "error");
                return ACTION_ERREUR;
            }
        } else {
            Messages::setMsg("Erreur lors de la souscription au topic artnet/config !", "error");
            return ACTION_ERREUR;
        }

        $communicationBroker->deconnecter();
    }

    // Fonction pour enregistrer le message dans la base de données
    private function enregistrerDansBDD($message)
    {
        // Connexion à la base de données (exemple avec PDO)
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=nom_de_la_base', 'utilisateur', 'mot_de_passe');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparez la requête d'insertion
            $stmt = $pdo->prepare("INSERT INTO votre_table (colonne_message) VALUES (:message)");
            $stmt->bindParam(':message', $message);

            // Exécutez la requête
            return $stmt->execute();
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion ou d'exécution
            Messages::setMsg("Erreur de base de données : " . $e->getMessage(), "error");
            return false;
        }
        try {
            $this->query("INSERT INTO moduleDMXWiFi (univers,nomBoitier,adresseIP,adresseMAC,rssi,actif) VALUES (:univers, :nomBoitier, :adresseIP, :adresseMAC, :rssi, :actif)");
            $this->bind(':univers', $univers);
            $this->bind(':nomBoitier', $nomBoitier);
            $this->bind(':adresseIP', $adresseIP);
            $this->bind(':adresseMAC', $adresseMAC);
            $this->bind(':rssi', $rssi);
            $this->bind(':actif', $actif);
            $this->execute();
            $idBroker = $this->getLastInsertId();
            Messages::setMsg("Broker ajouté avec succès !", "success");
            return ACTION_SUCCESS;
        } catch (PDOException $e) {
            Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
            return ACTION_ERREUR;
        }
    }
}