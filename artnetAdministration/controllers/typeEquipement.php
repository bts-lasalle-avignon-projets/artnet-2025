<?php
class TypeEquipementDMX extends Controller
{
    private $viewmodel;

    public function __construct($action, $request)
    {
        parent::__construct($action, $request);
        $this->viewmodel = new TypeEquipementDMXModel();
    }

    protected function index()
    {
        // Récupère la liste des types d'équipements
        $listeTypeEquipement = $this->viewmodel->index();
        // Affiche la liste des types d'équipements
        $this->display($listeTypeEquipement);
    }

    protected function add()
    {
        if (NO_LOGIN) {
            $result = $this->viewmodel->add();
            $this->display();
            if ($result == ACTION_SUCCESS) {
                // Retour à la liste des types d'équipements
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            }
        } else {
            if (!isset($_SESSION['is_logged_in'])) {
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            } else {
                // @todo
            }
        }
    }

    protected function edit()
    {
        if (NO_LOGIN) {
            $idTypeEquipement = $this->getID();
            if ($idTypeEquipement > 0) {
                $typeEquipement = $this->viewmodel->edit($idTypeEquipement);
                if (is_array($typeEquipement)) {
                    // Affiche le formulaire de modification
                    $this->display($typeEquipement);
                } else {
                    // Retour à la liste des types d'équipements
                    header('Location: ' . URL_PATH . 'typeEquipementDMX');
                }
            } else {
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            }
        } else {
            if (!isset($_SESSION['is_logged_in'])) {
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            } else {
                // @todo
            }
        }
    }

    protected function delete()
    {
        if (NO_LOGIN) {
            $idTypeEquipement = $this->getID();
            if ($idTypeEquipement > 0) {
                $result = $this->viewmodel->delete($idTypeEquipement);
                // Affiche le message de confirmation
                $this->display($idTypeEquipement);
                if ($result == ACTION_SUCCESS) {
                    // Retour à la liste des types d'équipements
                    header('Location: ' . URL_PATH . 'typeEquipementDMX');
                }
            } else {
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            }
        } else {
            if (!isset($_SESSION['is_logged_in'])) {
                header('Location: ' . URL_PATH . 'typeEquipementDMX');
            } else {
                // @todo
            }
        }
    }

    private function getID()
    {
        if (!isset($this->request['id']) || empty($this->request['id'])) {
            Messages::setMsg("ID type d'équipement manquant !", "error");
            return -1;
        }
        if ($this->request['id'] < 0) {
            Messages::setMsg("ID type d'équipement invalide !", "error");
            return -1;
        }
        return (int) $this->request['id'];
    }
}
