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

        // Décode le JSON
        $data = json_decode($json, true);
        if (!$data) {
            // Json invalide
            return false;
        }

        if (!isset($data['mac'])) {
            return false;
        }

        // Vérifier si le module existe déjà dans la base de données par MAC
        $this->query("SELECT COUNT(*) FROM moduleDMXWiFi WHERE adresseMAC = :adresseMAC");
        $this->bind(':adresseMAC', $data['mac']);
        $this->execute();
        $count = $this->count();

        if ($count > 0) {
            // Modifie le module dans la base de données
            $this->query("UPDATE moduleDMXWiFi SET univers = :univers, adresseIP = :adresseIP, rssi = :rssi WHERE adresseMAC = :adresseMAC");
            $this->bind(':univers', $data['univers']);
            $this->bind(':adresseIP', $data['ip']);
            $this->bind(':rssi', $data['rssi']);
            $this->bind(':adresseMAC', $data['mac']);
            return $this->execute();
        } else {
            // Construction du nom Module_<4 derniers caractères de la MAC>
            $mac = $data['mac'];
            $macClean = str_replace(':', '', $mac);
            $nom = 'Module_' . substr($macClean, -4);

            // Insert le module dans la base de données
            $this->query("INSERT INTO moduleDMXWiFi (univers, nomBoitier, adresseIP, adresseMAC, rssi) VALUES (:univers, :nomBoitier, :adresseIP, :adresseMAC, :rssi)");
            $this->bind(':univers', $data['univers']);
            $this->bind(':nomBoitier', $nom);
            $this->bind(':adresseIP', $data['ip']);
            $this->bind(':adresseMAC', $mac,);
            $this->bind(':rssi', $data['rssi']);
            return $this->execute();
        }
    }


    public function existeModuleDMXWiFi()
    {

        return true;
    }
}
