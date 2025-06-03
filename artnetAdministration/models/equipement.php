<?php

class EquipementDMXModel extends Model
{
	public function index()
	{
		// Récupère la liste des équipements
		$this->query("
			SELECT equipementDMX.*, typeEquipementDMX.*
            FROM equipementDMX
            JOIN typeEquipementDMX ON equipementDMX.idTypeEquipement = typeEquipementDMX.idTypeEquipement
            ORDER BY equipementDMX.nomEquipement
		");

		$equipements = $this->getResults();

		return $equipements;
	}

	public function add()
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$nom = trim($_POST['nom']);
			$type = trim($_POST['type']);
			$univers = trim($_POST['univers']);
			$canalInitial = trim($_POST['canalInitial']);

			// Vérifie les données du formulaire
			if (empty($nom)) {
				Messages::setMsg("Le nom est requis !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($type)) {
				Messages::setMsg("Le type est requis !", "error");
				return ACTION_ERREUR;
			}

			if (empty($univers) || !is_numeric($univers)) {
				Messages::setMsg("L'univers est requis et doit être un nombre !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($canalInitial) || !is_numeric($canalInitial)) {
				Messages::setMsg("Le canal initial est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			if ($this->existeIdEquipementDMXParNom($nom, $univers)) {
				Messages::setMsg("Un équipement portant le même nom existe déjà dans cet univers.", "error");
				return ACTION_ERREUR;
			}

			// Insère l'équipement dans la base de données
			try {
				$this->query("INSERT INTO equipementDMX (nomEquipement, idTypeEquipement, univers, canalInitial)
                    			VALUES (:nomEquipement, :idTypeEquipement, :univers, :canalInitial)");
				$this->bind(':nomEquipement', $nom);
				$this->bind(':idTypeEquipement', $type);
				$this->bind(':univers', $univers, PDO::PARAM_INT);
				$this->bind(':canalInitial', $canalInitial, PDO::PARAM_INT);
				$this->execute();
				$idEquipement = $this->getLastInsertId();
				Messages::setMsg("Équipement ajouté avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		} else {
			// Récupère la liste des types d'équipements
			$this->query("
				SELECT typeEquipementDMX.*
				FROM typeEquipementDMX
				ORDER BY typeEquipementDMX.typeEquipement
			");
			$typeEquipements = $this->getResults();

			// Récupère la liste des univers
			$this->query("
				SELECT moduleDMXWiFi.*
				FROM moduleDMXWiFi
				ORDER BY moduleDMXWiFi.nomBoitier
			");
			$universModuleDMXWiFi = $this->getResults();

			return [
				'typeEquipements' => $typeEquipements,
				'universModuleDMXWiFi' => $universModuleDMXWiFi
			];
		}
	}

	public function command($idEquipement)
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère l'équipement à commander
			$equipement = $this->getEquipementDMX($idEquipement);

			if ($equipement == null) {
				Messages::setMsg("L'équipement n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Générer le JSON comme : [{"canal":10, "valeur":255},{"canal":11, "valeur":10},{"canal":12, "valeur":55}]
			$canaux = [];
			for ($i = $equipement['canalInitial']; $i < ($equipement['canalInitial'] + $equipement['nbCanaux']); $i++) {
				$canaux[$i - $equipement['canalInitial']] = [
					'canal' => $i,
					'valeur' => $_POST['canal-' . $i]
				];
			}

			// Encode le tableau en JSON
			$canaux = json_encode($canaux);

			// Vérifie si le JSON est valide
			if (json_last_error() !== JSON_ERROR_NONE) {
				Messages::setMsg("Erreur dans le formatage JSON des canaux !", "error");
				return ACTION_ERREUR;
			}

			// Récupère le broker
			$broker = $this->getBrokerMQTTActif();

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
						$this->query("UPDATE brokerMQTT SET actif = 1 WHERE idBrokerMQTT = :idBrokerMQTT");
						$this->bind(':idBrokerMQTT', $broker['idBrokerMQTT'], PDO::PARAM_INT);
						$this->execute();
					} catch (PDOException $e) {
					}
					if ($_POST['submit'] == "Publier") {
						$topic = $broker['topic'] . "/univers/" . $equipement['univers'];
						$result = $communicationBroker->publier($topic, $canaux, 0);
						if ($result) {
							try {
								$this->query("UPDATE equipementDMX SET canaux = :canaux WHERE idEquipement = :idEquipement");
								$this->bind(':canaux', $canaux);
								$this->bind(':idEquipement', $idEquipement, PDO::PARAM_INT);
								$this->execute();

								Messages::setMsg("Commande de l'équipement réussie et sauvegardée !", "success");
								return ACTION_SUCCESS;
							} catch (PDOException $e) {
								Messages::setMsg("Commande envoyée, mais erreur lors de la sauvegarde des canaux : " . $e->getMessage(), "error");
								return ACTION_ERREUR;
							}
						} else {
							Messages::setMsg("Erreur lors de la commande de l'équipement !", "error");
							return ACTION_ERREUR;
						}
					}
					$communicationBroker->deconnecter();
				} else {
					// Modifie l'état du broker dans la base de données
					try {
						$this->query("UPDATE brokerMQTT SET actif = 0 WHERE idBrokerMQTT = :idBrokerMQTT");
						$this->bind(':idBrokerMQTT', $broker['idBrokerMQTT'], PDO::PARAM_INT);
						$this->execute();
					} catch (PDOException $e) {
					}
					Messages::setMsg("Erreur de connexion au broker !", "error");
					return ACTION_ERREUR;
				}
			} else {
				// Modifie l'état du broker dans la base de données
				try {
					$this->query("UPDATE brokerMQTT SET actif = 0 WHERE idBrokerMQTT = :idBrokerMQTT");
					$this->bind(':idBrokerMQTT', $broker['idBrokerMQTT'], PDO::PARAM_INT);
					$this->execute();
				} catch (PDOException $e) {
				}
				Messages::setMsg("Erreur de connexion au broker !", "error");
				return ACTION_ERREUR;
			}
		} else {
			// Récupère l'équipement à commander
			$equipement = $this->getEquipementDMX($idEquipement);
			$canauxArray = json_decode($equipement['canaux'], true) ?? [];
			$mapCanaux = [];
			foreach ($canauxArray as $item) {
				$mapCanaux[$item['canal']] = $item['valeur'];
			}
			$equipement['canaux'] = $mapCanaux;

			return $equipement ?? ACTION_ERREUR;
		}
	}

	public function edit($idEquipement)
	{
		// Le formulaire a été soumis ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$idEquipement = trim($_POST['idEquipement']);
			$nom = trim($_POST['nom']);
			$type = trim($_POST['type']);
			$univers = trim($_POST['univers']);
			$canalInitial = trim($_POST['canalInitial']);

			// Vérifie les données du formulaire
			if (empty($nom)) {
				Messages::setMsg("Le nom est requis !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($type)) {
				Messages::setMsg("Le type est requis !", "error");
				return ACTION_ERREUR;
			}

			if (empty($univers) || !is_numeric($univers)) {
				Messages::setMsg("L'univers est requis et doit être un nombre !", "erreur");
				return ACTION_ERREUR;
			}

			if (empty($canalInitial) || !is_numeric($canalInitial)) {
				Messages::setMsg("Le canal initial est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			// Modifie l'equipement dans la base de données
			try {
				$this->query("UPDATE equipementDMX SET nomEquipement = :nomEquipement, idTypeEquipement = :idTypeEquipement, univers = :univers, canalInitial = :canalInitial WHERE idEquipement = :idEquipement");
				$this->bind(':nomEquipement', $nom);
				$this->bind(':idTypeEquipement', $type);
				$this->bind(':univers', $univers, PDO::PARAM_INT);
				$this->bind(':canalInitial', $canalInitial, PDO::PARAM_INT);
				$this->bind(':idEquipement', $idEquipement, PDO::PARAM_INT);
				$this->execute();
				Messages::setMsg("Équipement modifié avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de la modification  : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		} else {
			// Récupère l'équipement à modifier
			$equipement = $this->getEquipementDMX($idEquipement);
			return $equipement ?? ACTION_ERREUR;
		}
	}

	public function delete($idEquipement)
	{
		// La demande de suppresion a été soumise ?
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
			// Récupère les données du formulaire
			$idEquipement = trim($_POST['idEquipement']);
			$confirmation = trim($_POST['submit']);

			// Vérifie les données du formulaire
			if (!empty($confirmation) && $confirmation == "Non") {
				return ACTION_ERREUR;
			}

			if (empty($idEquipement) || !is_numeric($idEquipement)) {
				Messages::setMsg("L'ID est requis et doit être un nombre !", "error");
				return ACTION_ERREUR;
			}

			if ($idEquipement != $idEquipement) {
				Messages::setMsg("ID invalide !", "error");
				return ACTION_ERREUR;
			}

			if (!$this->existeIdEquipementDMXParID($idEquipement)) {
				Messages::setMsg("L'équipement n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Supprime l'equipement de la base de données
			try {
				$this->query("DELETE FROM equipementDMX WHERE idEquipement = :idEquipement");
				$this->bind(':idEquipement', $idEquipement, PDO::PARAM_INT);
				$this->execute();
				Messages::setMsg("Équipement supprimé avec succès !", "success");
				return ACTION_SUCCESS;
			} catch (PDOException $e) {
				Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
				return ACTION_ERREUR;
			}
		}
		return ACTION_ENCOURS;
	}

	public function getEquipementDMX($idEquipement)
	{
		// Récupère l'equipement à modifier
		$this->query("SELECT equipementDMX.*, typeEquipementDMX.* 
            FROM equipementDMX 
            JOIN typeEquipementDMX ON equipementDMX.idTypeEquipement = typeEquipementDMX.idTypeEquipement 
			WHERE idEquipement = :idEquipement");
		$this->bind(':idEquipement', $idEquipement, PDO::PARAM_INT);
		$equipement = $this->getResult();
		// Récupère la liste des types d'équipements
		$this->query("
			SELECT typeEquipementDMX.*
            FROM typeEquipementDMX
			ORDER BY typeEquipementDMX.typeEquipement
		");
		$typeEquipements = $this->getResults();
		$equipement['typeEquipements'] = $typeEquipements;

		return $equipement ?? null;
	}

	public function getAllEquipementsDMX()
	{
		$this->query("SELECT
           equipementDMX.univers,
           equipementDMX.nomEquipement,
           equipementDMX.canalInitial,
           equipementDMX.canaux,
           typeEquipementDMX.typeEquipement,
           typeEquipementDMX.nbCanaux,
           moduleDMXWiFi.nomBoitier
       	FROM equipementDMX
       	JOIN typeEquipementDMX ON equipementDMX.idTypeEquipement = typeEquipementDMX.idTypeEquipement
       	JOIN moduleDMXWiFi ON equipementDMX.univers = moduleDMXWiFi.univers
       	ORDER BY equipementDMX.nomEquipement");

		$results = $this->getResults();

		foreach ($results as &$equipement) {
			if (isset($equipement['canaux']) && is_string($equipement['canaux'])) {
				$decoded = json_decode($equipement['canaux'], true);
				if (json_last_error() === JSON_ERROR_NONE) {
					$equipement['canaux'] = $decoded;
				} else {
					journaliser("Erreur de décodage JSON pour l'équipement : " . $equipement['nomEquipement']);
					$equipement['canaux'] = null;
				}
			}
		}

		return $results;
	}

	public function existeIdEquipementDMXParID($idEquipement): bool
	{
		$this->query("SELECT nomEquipement FROM equipementDMX WHERE idEquipement = :idEquipement");
		$this->bind(':idEquipement', $idEquipement);
		$this->execute();
		$nom = $this->getResult();
		if (!$nom) {
			return false;
		}
		return true;
	}

	public function existeIdEquipementDMXParNom($nomEquipement, $univers): bool
	{
		$this->query("SELECT nomEquipement FROM equipementDMX WHERE nomEquipement = :nomEquipement AND univers = :univers");
		$this->bind(':nomEquipement', $nomEquipement);
		$this->bind(':univers', $univers);
		$this->execute();
		$nom = $this->getResult();
		if (!$nom) {
			return false;
		}
		return true;
	}


	public function getBrokerMQTTActif()
	{
		// Récupère le broker actif
		$this->query("SELECT * FROM brokerMQTT WHERE actif = 1");
		$broker = $this->getResult();
		if ($broker) {
			$broker['topic'] = BROKER_MQTT_TOPIC;
		}
		return $broker ?? null;
	}

	public function addEquipementDepuisTopic($json)
	{
		// Exemple de json : {"nomEquipement":"lyre","univers":1,"typeEquipement":"Scanner","nbCanaux":8,"canalInitial":24,"canaux":[{"canal":10, "valeur":255},{"canal":11, "valeur":10},{"canal":12, "valeur":55}]}

		// Décode le JSON
		$data = json_decode($json, true);
		if (!$data) {
			// JSON invalide
			return false;
		}

		if (!isset($data['nomEquipement'])) {
			return false; // nomEquipement manquant
		}

		// Vérifier si l'équipement existe déjà dans la base de données
		if ($this->existeIdEquipementDMXParNom($data['nomEquipement'], $data['univers'])) {
			return $this->updateEquipementTopic($data);
		} else {
			return $this->insertEquipementTopic($data);
		}
	}

	public function updateEquipementTopic($data)
	{
		$this->query("SELECT idEquipement FROM equipementDMX WHERE nomEquipement = :nomEquipement AND univers = :univers");
		$this->bind(':nomEquipement', $data['nomEquipement']);
		$this->bind(':univers', $data['univers']);
		$equipementDMX = $this->getResult();

		// Vérifier si l'idEquipement a été trouvé
		if (!$equipementDMX) {
			journaliser("Aucun équipement trouvé avec nomEquipement: " . $data['nomEquipement'] . " et univers: " . $data['univers']);
			return false; // Équipement non trouvé
		}

		$idEquipement = $equipementDMX['idEquipement'];
		$idTypeEquipement = $this->getIdTypeEquipement($data);
		$canaux = isset($data['canaux']) && is_array($data['canaux']) ? json_encode($data['canaux']) : null;

		// Modifie l'équipement dans la base de données
		$this->query("UPDATE equipementDMX SET nomEquipement = :nomEquipement, univers = :univers, canaux = :canaux, idTypeEquipement = :idTypeEquipement, canalInitial = :canalInitial WHERE idEquipement = :idEquipement");
		$this->bind(':nomEquipement', $data['nomEquipement']);
		$this->bind(':univers', $data['univers']);
		$this->bind(':canaux', $canaux);
		$this->bind(':idTypeEquipement', $idTypeEquipement);
		$this->bind(':canalInitial', $data['canalInitial']);
		$this->bind(':idEquipement', $idEquipement);
		return $this->execute();
	}

	public function insertEquipementTopic($data)
	{
		$idTypeEquipement = $this->getIdTypeEquipement($data);
		$canaux = isset($data['canaux']) && is_array($data['canaux']) ? json_encode($data['canaux']) : null;

		// Insert l'équipement dans la base de données
		$this->query("INSERT INTO equipementDMX (nomEquipement, univers, canaux, idTypeEquipement, canalInitial) VALUES (:nomEquipement, :univers, :canaux, :idTypeEquipement, :canalInitial)");
		$this->bind(':nomEquipement', $data['nomEquipement']);
		$this->bind(':univers', $data['univers']);
		$this->bind(':canaux', $canaux);
		$this->bind(':idTypeEquipement', $idTypeEquipement);
		$this->bind(':canalInitial', $data['canalInitial']);
		return $this->execute();
	}

	public function getIdTypeEquipement($data)
	{
		$this->query("SELECT idTypeEquipement FROM typeEquipementDMX WHERE typeEquipement = :typeEquipement AND nbCanaux = :nbCanaux");
		$this->bind(':typeEquipement', $data['typeEquipement']);
		$this->bind(':nbCanaux', $data['nbCanaux']);
		$this->execute();

		$typeEquipementDMX = $this->getResult();

		if (!is_array($typeEquipementDMX) || !isset($typeEquipementDMX['idTypeEquipement'])) {
			journaliser("Aucun type d'équipement trouvé pour type: " . $data['typeEquipement'] . " et nbCanaux: " . $data['nbCanaux']);
			$idTypeEquipement = $this->insertTypeEquipement($data);
			$typeEquipementDMX['idTypeEquipement'] = $idTypeEquipement;
		}

		return $typeEquipementDMX['idTypeEquipement'] ?? false;
	}

	public function insertTypeEquipement($data)
	{
		$this->query("INSERT INTO typeEquipementDMX (typeEquipement,nbCanaux) VALUES (:typeEquipement, :nbCanaux)");
		$this->bind(':typeEquipement', $data['typeEquipement']);
		$this->bind(':nbCanaux', $data['nbCanaux']);

		if ($this->execute()) {
			$idTypeEquipement = $this->getLastInsertId();
			journaliser("Nouveau type d'équipement inséré : " . $data['typeEquipement'] . " avec id: " . $idTypeEquipement);
			return $idTypeEquipement;
		} else {
			journaliser("Échec de l'insertion du type d'équipement : " . $data['typeEquipement']);
			return false; // Retourne 0 en cas d'échec
		}
	}
}
