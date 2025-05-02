<?php

class AccueilModel extends Model
{
	public function index() 
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$hostname = $_POST['hostname'];
			$port = $_POST['port'];

			$hostname = filter_var($hostname, FILTER_SANITIZE_STRING);
			$port = filter_var($port, FILTER_VALIDATE_INT);

			if ($port === false || $port < 1 || $port > 65535) {
				echo "Port invalide.";
				exit;
			}

			$result = ajoutBroker($hostname, $port);

			if ($result) {
				echo "Broker ajouté.";
			} else {
				echo "Échec de l'ajout du broker.";
			}
			return;
		}
	}	

	private function ajoutBroker($hostname, $port) {
		try {
			$this->query("INSERT INTO brokerMQTT (hostname, port) VALUES (:hostname, :port)");
			$this->bind(':hostname', $hostname);
			$this->bind(':port', $port, PDO::PARAM_INT);
			$this->execute();
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			return false;
		}
	}
}
