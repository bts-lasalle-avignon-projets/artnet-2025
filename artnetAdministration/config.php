<?php

// le nom à ajouter dans l'URL
//define("URL_NAME", "/artnet-2025/artnetAdministration/"); // serveur de développement
define("URL_NAME", "/"); // serveur de test GitHub


// Pour le debug
define("DEBUG", false);

// Pour les tests (sans authentification)
define("NO_LOGIN", true);

// Pour le broker MQTT par défaut
define("BROKER_MQTT_HOSTNAME", "127.0.0.1");
define("BROKER_MQTT_PORT", 1883);
define("BROKER_MQTT_TOPIC", "artnet");

// Pour la base de données
define("DB_DRIVER", true); // true pour MySQL, false sans base de données
define("DB_HOST", "127.0.0.1");
define("DB_USER", "artnet_User");
define("DB_PASS", "password");
define("DB_NAME", "artnet");

// Racine et URL
define("ROOT_PATH", URL_NAME);
define("URL_PATH", "http://" . $_SERVER['HTTP_HOST'] . URL_NAME);

// Divers
define("TITRE_SITE", "ArtNet");

// Pour les actions
define("ACTION_ENCOURS", 0);
define("ACTION_SUCCESS", 1);
define("ACTION_ERREUR", -1);

if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
