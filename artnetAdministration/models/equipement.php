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

		$equipement = $this->getResults();

		return $equipement;
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
            $nbCanaux = trim($_POST['nbCanaux']);

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

            if (empty($nbCanaux) || !is_numeric($nbCanaux)) {
				Messages::setMsg("Le nombre de canaux est requis et doit être un nombre !", "erreur");
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