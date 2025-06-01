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
            // JSON invalide
            return false;
        }

        if (!isset($data['mac'])) {
            return false; // MAC manquante
        }

        // Vérifier si le module existe déjà dans la base de données par MAC
        if ($this->existeModuleDMXWiFi($data['mac'])) {
            return $this->updateModule($data);
        } else {
            return $this->insertModule($data);
        }
    }

    private function existeModuleDMXWiFi($mac): bool
    {
        $this->query("SELECT COUNT(*) FROM moduleDMXWiFi WHERE adresseMAC = :adresseMAC");
        $this->bind(':adresseMAC', $mac);
        $this->execute();
        return $this->count() > 0; // Retourne true si le module existe
    }

    private function updateModule($data): bool
    {
        // Modifie le module dans la base de données
        $this->query("UPDATE moduleDMXWiFi SET univers = :univers, adresseIP = :adresseIP, rssi = :rssi WHERE adresseMAC = :adresseMAC");
        $this->bind(':univers', $data['univers']);
        $this->bind(':adresseIP', $data['ip']);
        $this->bind(':rssi', $data['rssi']);
        $this->bind(':adresseMAC', $data['mac']);
        return $this->execute();
    }

    private function insertModule($data): bool
    {
        // Construction du nom Module_<4 derniers caractères de la MAC>
        $mac = $data['mac'];
        $macClean = str_replace(':', '', $mac);
        $nom = 'Module_' . substr($macClean, -4);

        // Insert le module dans la base de données
        $this->query("INSERT INTO moduleDMXWiFi (univers, nomBoitier, adresseIP, adresseMAC, rssi) VALUES (:univers, :nomBoitier, :adresseIP, :adresseMAC, :rssi)");
        $this->bind(':univers', $data['univers']);
        $this->bind(':nomBoitier', $nom);
        $this->bind(':adresseIP', $data['ip']);
        $this->bind(':adresseMAC', $mac);
        $this->bind(':rssi', $data['rssi']);
        return $this->execute();
    }
}
