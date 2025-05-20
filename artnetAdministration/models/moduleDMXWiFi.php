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
}
