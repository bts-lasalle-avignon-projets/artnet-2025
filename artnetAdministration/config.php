<?php

// Pour le debug
define("DEBUG", true);

// Pour les tests
define("NO_LOGIN", true);

// Pour le broker MQTT
define("broker_HOST", "127.0.0.1");
define("broker_Port", 1883);

// Pour la base de données
define("DB_DRIVER", false); // true pour MySQL, false sans base de données
define("DB_HOST", "127.0.0.1");
define("DB_USER", "artnet_User");
define("DB_PASS", "password");
define("DB_NAME", "artnet");

// URL
define("ROOT_PATH", "/artnet-2025/artnetAdministration/");
//define("URL_PATH", "http://" . $_SERVER['HTTP_HOST'] . "/artnet/");
define("URL_PATH", "http://" . $_SERVER['HTTP_HOST'] . "/artnet-2025/artnetAdministration/");

// Divers
define("TITRE_SITE", "ArtNet");
