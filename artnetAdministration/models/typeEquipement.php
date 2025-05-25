<?php
class TypeEquipementDMXModel extends Model
{
    public function index()
    {
        // Récupère la liste des types d'équipements
        $this->query("
			SELECT *
			FROM typeEquipementDMX
			ORDER BY typeEquipement
		");

        $type = $this->getResults();

        return $type;
    }

    public function add()
    {
        // Le formulaire a été soumis ?
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            // Récupère les données du formulaire
            $nom = trim($_POST['nom']);
            $nbCanaux = trim($_POST['nbCanaux']);

            // Vérifie les données du formulaire
            if (empty($nom)) {
                Messages::setMsg("Le nom est requis !", "erreur");
                return ACTION_ERREUR;
            }

            if (empty($nbCanaux)) {
                Messages::setMsg("Le nombre de canaux est requis et doit être un nombre !", "error");
                return ACTION_ERREUR;
            }

            // Insère le type d'équipement dans la base de données
            try {
                $this->query("INSERT INTO typeEquipementDMX (typeEquipement,nbCanaux) VALUES (:typeEquipement, :nbCanaux)");
                $this->bind(':typeEquipement', $nom);
                $this->bind(':nbCanaux', $nbCanaux);
                $this->execute();
                $idType = $this->getLastInsertId();
                Messages::setMsg("Type d'équipement ajouté avec succès !", "success");
                return ACTION_SUCCESS;
            } catch (PDOException $e) {
                Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
                return ACTION_ERREUR;
            }
        }
        return ACTION_ENCOURS;
    }

    public function edit($idTypeEquipement)
    {
        // Le formulaire a été soumis ?
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            // Récupère les données du formulaire
            $idTypeEquipement = trim($_POST['idTypeEquipement']);
            $nom = trim($_POST['nom']);
            $nbCanaux = trim($_POST['nbCanaux']);

            // Vérifie les données du formulaire
            if (empty($nom)) {
                Messages::setMsg("Le nom est requis !", "erreur");
                return ACTION_ERREUR;
            }

            if (empty($nbCanaux)) {
                Messages::setMsg("Le nombre de canaux est requis !", "error");
                return ACTION_ERREUR;
            }

            // Modifie le type d'équipement dans la base de données
            try {
                $this->query("UPDATE typeEquipementDMX SET typeEquipement = :typeEquipement, nbCanaux = :nbCanaux WHERE idTypeEquipement = :idTypeEquipement");
                $this->bind(':typeEquipement', $nom);
                $this->bind(':nbCanaux', $nbCanaux);
                $this->bind(':idTypeEquipement', $idTypeEquipement, PDO::PARAM_INT);
                $this->execute();
                Messages::setMsg("Type d'équipement modifié avec succès !", "success");
                return ACTION_SUCCESS;
            } catch (PDOException $e) {
                Messages::setMsg("Erreur lors de la modification  : " . $e->getMessage(), "error");
                return ACTION_ERREUR;
            }
        } else {
            // Récupère l'équipement à modifier
            $type = $this->getTypeEquipementDMX($idTypeEquipement);
            return $type ?? ACTION_ERREUR;
        }
    }

    public function delete($idTypeEquipement)
    {
        // La demande de suppresion a été soumise ?
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            // Récupère les données du formulaire
            $idTypeEquipement = trim($_POST['idTypeEquipement']);
            $confirmation = trim($_POST['submit']);

            // Vérifie les données du formulaire
            if (!empty($confirmation) && $confirmation == "Non") {
                return ACTION_ERREUR;
            }

            if (empty($idTypeEquipement) || !is_numeric($idTypeEquipement)) {
                Messages::setMsg("L'ID est requis et doit être un nombre !", "error");
                return ACTION_ERREUR;
            }

            if ($idTypeEquipement != $idTypeEquipement) {
                Messages::setMsg("ID invalide !", "error");
                return ACTION_ERREUR;
            }

            if (!$this->existeIdTypeEquipementDMX($idTypeEquipement)) {
                Messages::setMsg("Le type d'équipement n'existe pas !", "error");
                return ACTION_ERREUR;
            }

            // Supprime le type d'équipement de la base de données
            try {
                $this->query("DELETE FROM typeEquipementDMX WHERE idTypeEquipement = :idTypeEquipement");
                $this->bind(':idTypeEquipement', $idTypeEquipement, PDO::PARAM_INT);
                $this->execute();
                Messages::setMsg("Type d'équipement supprimé avec succès !", "success");
                return ACTION_SUCCESS;
            } catch (PDOException $e) {
                Messages::setMsg("Erreur lors de l'insertion : " . $e->getMessage(), "error");
                return ACTION_ERREUR;
            }
        }
        return ACTION_ENCOURS;
    }

    public function getTypeEquipementDMX($idTypeEquipement)
    {
        // Récupère l'equipement à modifier
        $this->query("SELECT * 
            FROM typeEquipementDMX 
			WHERE idTypeEquipement = :idTypeEquipement
            ORDER BY typeEquipement");
        $this->bind(':idTypeEquipement', $idTypeEquipement, PDO::PARAM_INT);
        $type = $this->getResult();

        return $type ?? null;
    }

    public function existeIdTypeEquipementDMX($idTypeEquipement)
    {
        $this->query("SELECT typeEquipement FROM typeEquipementDMX WHERE idTypeEquipement = :idTypeEquipement");
        $this->bind(':idTypeEquipement', $idTypeEquipement);
        $this->execute();
        $nom = $this->getResult();
        if (!$nom) {
            return false;
        }
        return true;
    }
}
