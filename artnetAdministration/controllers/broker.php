<?php

class Broker extends Controller
{
	private $id;
	private $viewmodel;

	public function __construct($action, $request)
	{
		parent::__construct($action, $request);
		$this->viewmodel = new BrokerModel();
	}

	protected function index()
	{
		// Récupère la liste des brokers
		$listeBrokers = $this->viewmodel->index();
		// Affiche la liste des brokers
		$this->display($listeBrokers);
	}

	protected function add()
	{
		if (NO_LOGIN) {
			$result = $this->viewmodel->add();
			$this->display();
			if ($result == ACTION_SUCCESS) {
				// Retour à la liste des brokers
				header('Location: ' . URL_PATH . 'broker');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'broker');
			} else {
				// @todo
			}
		}
	}

	protected function edit()
	{
		if (NO_LOGIN) {
			$idBroker = $this->getID();
			if ($idBroker > 0) {
				$broker = $this->viewmodel->edit($idBroker);
				if (is_array($broker)) {
					// Affiche le formulaire de modification
					$this->display($broker);
				} else {
					// Retour à la liste des brokers
					header('Location: ' . URL_PATH . 'broker');
				}
			} else {
				header('Location: ' . URL_PATH . 'broker');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'broker');
			} else {
				// @todo
			}
		}
	}

	protected function delete()
	{
		if (NO_LOGIN) {
			$idBroker = $this->getID();
			if ($idBroker > 0) {
				$result = $this->viewmodel->delete($idBroker);
				// Affiche le message de confirmation
				$this->display($idBroker);
				if ($result == ACTION_SUCCESS) {
					// Retour à la liste des brokers
					header('Location: ' . URL_PATH . 'broker');
				}
			} else {
				header('Location: ' . URL_PATH . 'broker');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'broker');
			} else {
				// @todo
			}
		}
	}

	protected function test()
	{
		if (NO_LOGIN) {
			$idBroker = $this->getID();
			if ($idBroker > 0) {
				$broker = $this->viewmodel->test($idBroker);
				if (is_array($broker)) {
					// Affiche le formulaire de test de connexion
					$this->display($broker);
				} else {
					// Retour au formulaire de test de connexion
					header('Location: ' . URL_PATH . 'broker' . '/test/' . $idBroker);
				}
			} else {
				header('Location: ' . URL_PATH . 'broker');
			}
		} else {
			if (!isset($_SESSION['is_logged_in'])) {
				header('Location: ' . URL_PATH . 'broker');
			} else {
				// @todo
			}
		}
	}
  
	private function getID()
	{
		if (!isset($this->request['id']) || empty($this->request['id'])) {
			Messages::setMsg("ID broker manquant !", "error");
			return -1;
		}
		if ($this->request['id'] < 0) {
			Messages::setMsg("ID broker invalide !", "error");
			return -1;
		}
		return (int) $this->request['id'];
	}
}
