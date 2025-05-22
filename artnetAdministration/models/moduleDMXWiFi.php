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

    public function add($json): bool
    {
        // Exemple de json : {"univers":50, "ip":"192.168.1.1", "mac":"24:62:AB:F3:05:1C","rssi":-50}

        // @todo Décoder le JSON

        // @todo Vérifier si le module DMXWiFi existe déjà dans la base de données

        // @todo si le module DMXWiFi n'existe pas dans la base de données alors l'ajouter 

        return false;
    }

    public function existeModuleDMXWiFi()
    {

        return true;
    }
}
