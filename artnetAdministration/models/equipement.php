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
		}
		return ACTION_ENCOURS;
	}

	public function canaux($idEquipement)
	{

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

			// Modifie le broker dans la base de données
			try {
				$this->query("UPDATE equipementDMX SET nom = :nom, type = :type, univers = :univers, canalInitial = :canalInitial WHERE idEquipement = :idEquipement");
				$this->bind(':nomEquipement', $nom);
                $this->bind(':idTypeEquipement', $type);
                $this->bind(':univers', $univers, PDO::PARAM_INT);
                $this->bind(':canalInitial', $canalInitial, PDO::PARAM_INT);
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

			if (!$this->existeIdEquipementDMX($idEquipement)) {
				Messages::setMsg("L'équipement n'existe pas !", "error");
				return ACTION_ERREUR;
			}

			// Supprime le broker de la base de données
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
		// Récupère le broker à modifier
		$this->query("SELECT * FROM equipementDMX WHERE idEquipement = :idEquipement");
		$this->bind(':idEquipement', $idEquipement, PDO::PARAM_INT);
		$equipement = $this->getResult();
		
		return $equipement ?? null;
	}

	public function existeIdEquipementDMX($idEquipement)
	{
		$this->query("SELECT nom FROM equipementDMX WHERE idEquipement = :idEquipement");
		$this->bind(':idEquipement', $idEquipement);
		$this->execute();
		$nom = $this->getResult();
		if (!$nom) {
			return false;
		}
		return true;
	}
}