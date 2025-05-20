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
			$typeEquipements = $this->viewmodel->add();
			if (is_array($typeEquipements)) {
				// Affiche le formulaire d'ajout
				$this->display($typeEquipements);
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
					// Retour au formulaire de test de connexion
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
}
