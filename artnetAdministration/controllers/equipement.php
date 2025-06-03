<?php

class EquipementDMX extends Controller
{
	private $viewmodel;

	public function __construct($action, $request)
	{
		parent::__construct($action, $request);
		$this->viewmodel = new EquipementDMXModel();
	}

	protected function index()
	{
		// Récupère la liste des équipements
		$listeEquipement = $this->viewmodel->index();
		// Affiche la liste des équipements
		$this->display($listeEquipement);
	}

	protected function add()
	{
		if (NO_LOGIN) {
			$datas = $this->viewmodel->add();
			if (is_array($datas)) {
				// Affiche le formulaire d'ajout
				$this->display($datas);
			} else {
				// Retour à la liste des équipements
				header('Location: ' . URL_PATH . 'equipementDMX');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'equipementDMX');
			} else {
				// @todo
			}
		}
	}

	protected function command()
	{
		if (NO_LOGIN) {
			$idEquipement = $this->getID();
			if ($idEquipement > 0) {
				$equipement = $this->viewmodel->command($idEquipement);
				if (is_array($equipement)) {
					// Affiche le formulaire de commande de canaux
					$this->display($equipement);
				} else {
					// Retour au formulaire de commande de canaux
					header('Location: ' . URL_PATH . 'equipementDMX' . '/command/' . $idEquipement);
				}
			} else {
				header('Location: ' . URL_PATH . 'equipementDMX');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'equipementDMX');
			} else {
				// @todo
			}
		}
	}

	protected function edit()
	{
		if (NO_LOGIN) {
			$idEquipement = $this->getID();
			if ($idEquipement > 0) {
				$equipement = $this->viewmodel->edit($idEquipement);
				if (is_array($equipement)) {
					// Affiche le formulaire de modification
					$this->display($equipement);
				} else {
					// Retour à la liste des brokers
					header('Location: ' . URL_PATH . 'equipementDMX');
				}
			} else {
				header('Location: ' . URL_PATH . 'equipementDMX');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'equipementDMX');
			} else {
				// @todo
			}
		}
	}

	protected function delete()
	{
		if (NO_LOGIN) {
			$idEquipement = $this->getID();
			if ($idEquipement > 0) {
				$result = $this->viewmodel->delete($idEquipement);
				// Affiche le message de confirmation
				$this->display($idEquipement);
				if ($result == ACTION_SUCCESS) {
					// Retour à la liste des brokers
					header('Location: ' . URL_PATH . 'equipementDMX');
				}
			} else {
				header('Location: ' . URL_PATH . 'equipementDMX');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'equipementDMX');
			} else {
				// @todo
			}
		}
	}

	private function getID()
	{
		if (!isset($this->request['id']) || empty($this->request['id'])) {
			Messages::setMsg("ID équipement manquant !", "error");
			return -1;
		}
		if ($this->request['id'] < 0) {
			Messages::setMsg("ID équipement invalide !", "error");
			return -1;
		}
		return (int) $this->request['id'];
	}

	protected function exportJson()
	{
		$equipements = $this->viewmodel->getAllEquipementsDMX();

		foreach ($equipements as &$equipement) {
			unset($equipement['nomBoitier']);
		}

		header('Content-Type: application/json');
		header('Content-Disposition: attachment; filename="equipementsDMX.json"');
		echo json_encode($equipements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		exit;
	}

	protected function importJson()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['importJsonFile'])) {
			$file = $_FILES['importJsonFile'];
			if ($file['error'] === UPLOAD_ERR_OK) {
				$jsonContent = file_get_contents($file['tmp_name']);
				$equipements = json_decode($jsonContent, true);
				if (json_last_error() === JSON_ERROR_NONE && is_array($equipements)) {
					$importedCount = 0;
					foreach ($equipements as $equipement) {
						// Appelle ta méthode dans le modèle pour importer un équipement
						if ($this->viewmodel->addEquipementDepuisTopic($equipement)) {
							$importedCount++;
						}
					}
					Messages::setMsg("$importedCount équipements importés avec succès !", "success");
				} else {
					Messages::setMsg("Fichier JSON invalide.", "error");
				}
			} else {
				Messages::setMsg("Erreur lors du téléchargement du fichier.", "error");
			}
		}
		header('Location: ' . URL_PATH . 'equipementDMX');
		exit;
	}
}
