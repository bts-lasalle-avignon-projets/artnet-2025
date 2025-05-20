<?php

class BrokerModel extends Model
{
	public function index()
	{
		// Récupère la liste des brokers MQTT
		$this->query("
			SELECT *
			FROM brokerMQTT
			ORDER BY hostname
		");

		$brokers = $this->getResults();

		return $brokers;
	}

	public function add()
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$hostname = trim($_POST['hostname']);
			$port = trim($_POST['port']);

			// Vérifie les données du formulaire
			if (empty($hostname)) {
				Messages::setMsg("Le hostname est requis !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($port) || !is_numeric($port)) {
				Messages::setMsg("Le port est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			// Insère le broker dans la base de données
			try {
				$this->query("INSERT INTO brokerMQTT (hostname,port,username,password,actif) VALUES (:hostname, :port, :username, :password, :actif)");
				$this->bind(':hostname', $hostname);
				$this->bind(':port', $port, PDO::PARAM_INT);
				$this->bind(':username', $_POST['username'] ?? null);
				$this->bind(':password', $_POST['password'] ?? null);
				$this->bind(':actif', $_POST['actif'] ?? 0, PDO::PARAM_INT);
				$this->execute();
				$idBroker = $this->getLastInsertId();
				Messages::setMsg("Broker ajouté avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		}
		return ACTION_ENCOURS;
	}

	public function edit($idBroker)
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$idBrokerMQTT = trim($_POST['idBrokerMQTT']);
			$hostname = trim($_POST['hostname']);
			$port = trim($_POST['port']);
			$confirmation = trim($_POST['submit']);

			// Vérifie les données du formulaire
			if (!empty($confirmation) && $confirmation == "Annuler") {
				return ACTION_ERREUR;
			}

			if (empty($hostname)) {
				Messages::setMsg("Le hostname est requis !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($port) || !is_numeric($port)) {
				Messages::setMsg("Le port est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			if ($idBrokerMQTT != $idBroker) {
				Messages::setMsg("ID invalide !", "error");
				return ACTION_ERREUR;
			}

			if (!$this->existeIdBrokerMQTT($idBrokerMQTT)) {
				Messages::setMsg("Le broker n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Modifie le broker dans la base de données
			try {
				$this->query("UPDATE brokerMQTT SET hostname = :hostname, port = :port WHERE idBrokerMQTT = :idBroker");
				$this->bind(':hostname', $hostname);
				$this->bind(':port', $port, PDO::PARAM_INT);
				$this->bind(':idBroker', $idBroker, PDO::PARAM_INT);
				$this->execute();
				Messages::setMsg("Broker modifié avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de la modification  : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		} else {
			// Récupère le broker à modifier
			$broker = $this->getBrokerMQTT($idBroker);
			return $broker ?? ACTION_ERREUR;
		}
	}

	public function delete($idBroker)
	{
		// La demande de suppresion a été soumise ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$idBrokerMQTT = trim($_POST['idBrokerMQTT']);
			$confirmation = trim($_POST['submit']);

			// Vérifie les données du formulaire
			if (!empty($confirmation) && $confirmation == "Non") {
				return ACTION_ERREUR;
			}

			if (empty($idBrokerMQTT) || !is_numeric($idBrokerMQTT)) {
				Messages::setMsg("L'ID est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			if ($idBrokerMQTT != $idBroker) {
				Messages::setMsg("ID invalide !", "error");
				return ACTION_ERREUR;
			}

			if (!$this->existeIdBrokerMQTT($idBrokerMQTT)) {
				Messages::setMsg("Le broker n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Supprime le broker de la base de données
			try {
				$this->query("DELETE FROM brokerMQTT WHERE idBrokerMQTT = :idBroker");
				$this->bind(':idBroker', $idBroker, PDO::PARAM_INT);
				$this->execute();
				Messages::setMsg("Broker supprimé avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		}
		return ACTION_ENCOURS;
	}

	public function test($idBroker)
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$idBrokerMQTT = trim($_POST['idBrokerMQTT']);

			// Vérifie les données du formulaire
			if ($idBrokerMQTT != $idBroker) {
				Messages::setMsg("ID invalide !", "error");
				return ACTION_ERREUR;
			}

			// Récupère le broker à tester
			$broker = $this->getBrokerMQTT($idBroker);

			if ($broker == null) {
				Messages::setMsg("Le broker n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Instancie le broker
			$communicationBroker = new CommunicationBroker($broker);
			// Connecte le broker
			$result = $communicationBroker->connecter();
			if ($result) {
				if ($communicationBroker->estConnecte()) {
					// Modifie l'état du broker dans la base de données
					try {
						$this->query("UPDATE brokerMQTT SET actif = 1 WHERE idBrokerMQTT = :idBroker");
						$this->bind(':idBroker', $idBroker, PDO::PARAM_INT);
						$this->execute();
					} catch (PDOException $e) {
					}
					// Les différents tests
					if ($_POST['submit'] == "Connecter") {
						Messages::setMsg("Test de connexion au broker réussi !", "success");
						return ACTION_SUCCESS;
					} else if ($_POST['submit'] == "Publier") {
						// Publie un message sur le topic de test
						$result = $communicationBroker->publier($broker['topic'] . "/test", "artnetAdministration", 0);
						if ($result) {
							Messages::setMsg("Test de publication réussi !", "success");
							return ACTION_SUCCESS;
						} else {
							Messages::setMsg("Erreur lors de la publication du message !", "error");
							return ACTION_ERREUR;
						}
					} else if ($_POST['submit'] == "Souscrire") {
						// Souscrit au topic de test
						$resultatSouscription = $communicationBroker->souscrire($broker['topic'] . "/test", 0);
						if ($resultatSouscription) {
							Messages::setMsg("Test de souscription réussi !", "success");
							return ACTION_SUCCESS;
						} else {
							Messages::setMsg("Erreur lors de la souscription au topic !", "error");
							return ACTION_ERREUR;
						}
					} else if ($_POST['submit'] == "Recevoir") {
						// Souscrit au topic de test
						$resultatSouscription = $communicationBroker->souscrire($broker['topic'] . "/test", 0);
						if ($resultatSouscription) {
							$result = $communicationBroker->recevoirMessage($broker['topic'] . "/test", 10); // 10 secondes
							if ($result) {
								// Récupère le message reçu sur le topic de test
								$message = $communicationBroker->getMessage($broker['topic'] . "/test");
								if ($message != null) {
									Messages::setMsg("Réception du message : \"" . $message . "\" sur le topic \"" . $broker['topic'] . "/test\"", "success");
									return ACTION_SUCCESS;
								} else {
									Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . "/test\"" . " !", "error");
									return ACTION_ERREUR;
								}
							} else {
								Messages::setMsg("Aucun message reçu sur le topic \"" . $broker['topic'] . "/test\"" . " !", "error");
								return ACTION_ERREUR;
							}
						} else {
							Messages::setMsg("Erreur lors de la souscription au topic !", "error");
							return ACTION_ERREUR;
						}
					}
					$communicationBroker->deconnecter();
				} else {
					// Modifie l'état du broker dans la base de données
					try {
						$this->query("UPDATE brokerMQTT SET actif = 0 WHERE idBrokerMQTT = :idBroker");
						$this->bind(':idBroker', $idBroker, PDO::PARAM_INT);
						$this->execute();
					} catch (PDOException $e) {
					}
					Messages::setMsg("Erreur de connexion au broker !", "error");
					return ACTION_ERREUR;
				}
			} else {
				// Modifie l'état du broker dans la base de données
				try {
					$this->query("UPDATE brokerMQTT SET actif = 0 WHERE idBrokerMQTT = :idBroker");
					$this->bind(':idBroker', $idBroker, PDO::PARAM_INT);
					$this->execute();
				} catch (PDOException $e) {
				}
				Messages::setMsg("Erreur de connexion au broker !", "error");
				return ACTION_ERREUR;
			}
		} else {
			// Récupère le broker à tester
			$broker = $this->getBrokerMQTT($idBroker);
			return $broker ?? ACTION_ERREUR;
		}
	}

	public function getBrokerMQTT($idBrokerMQTT)
	{
		// Récupère le broker à modifier
		$this->query("SELECT * FROM brokerMQTT WHERE idBrokerMQTT = :idBrokerMQTT");
		$this->bind(':idBrokerMQTT', $idBrokerMQTT, PDO::PARAM_INT);
		$broker = $this->getResult();
		// @todo Ajouter le topic à la base de données ?
		if ($broker) {
			$broker['topic'] = BROKER_MQTT_TOPIC;
		}
		return $broker ?? null;
	}

	public function getBrokerMQTTActif()
	{
		// Récupère le broker actif
		$this->query("SELECT * FROM brokerMQTT WHERE actif = 1");
		$broker = $this->getResult();
		return $broker ?? null;
	}
  
	public function existeIdBrokerMQTT($idBrokerMQTT)
	{
		$this->query("SELECT hostname FROM brokerMQTT WHERE idBrokerMQTT = :idBrokerMQTT");
		$this->bind(':idBrokerMQTT', $idBrokerMQTT);
		$this->execute();
		$hostname = $this->getResult();
		if (!$hostname) {
			return false;
		}
		return true;
	}
}
