<?php

use PHPUnit\Framework\TestCase;

class ArtnetAdministrationTest extends TestCase
{
    private $urlAccueil = 'http://localhost:8000/';
    private $urlBroker = 'http://localhost:8000/index.php?controleur=broker';
    private $urlCreationBroker = 'http://localhost:8000/index.php?controleur=broker&action=add';

    /** 🔹 Test 1 : Vérifier que la page Accueil est accessible */
    public function testPageAccueilIsAccessible()
    {
        $response = $this->httpGet($this->urlAccueil);
        $response = preg_replace('/\s+/', ' ', trim($response));
        $this->assertNotFalse($response, "La page Accueil n'est pas accessible.");
    }

    /** 🔹 Test 2 : Vérifier que la page Broker est accessible */
    public function testUrlBrokerIsAccessible()
    {
        $response = $this->httpGet($this->urlBroker);
        $response = preg_replace('/\s+/', ' ', trim($response));
        $this->assertNotFalse($response, "La page Broker n'est pas accessible.");
        $this->assertStringContainsString("href=\"/broker/add\"", $response, "Le lien pour Ajouter un broker n'est pas présent sur la page.");
        // etc ...
    }

    /** 🔹 Test 3 : Vérifier que l'on peut ajouter un broker */
    public function testAjouterBroker()
    {
        $postData = [
            "hostname" => "127.0.0.1",
            "port" => "1883",
            "submit" => "Envoyer"
        ];
        $response = $this->httpPost($this->urlCreationBroker, $postData);
        $this->assertStringContainsString("Broker ajouté avec succès !", $response, "Impossible d'ajouter un broker.");
    }

    /** 🔹 Fonction GET pour récupérer la page */
    private function httpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /** 🔹 Fonction POST pour soumettre un formulaire */
    private function httpPost($url, $postData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
