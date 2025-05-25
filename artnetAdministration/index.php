<?php
// Démarre la session PHP
session_start();

require('config.php');

// Les classes du modèle MVC
require('classes/message.php');
require('classes/messages.php');
require('classes/router.php');
require('classes/controller.php');
require('classes/model.php');

// Les controleurs
require('controllers/accueil.php');
require('controllers/broker.php');
require('controllers/moduleDMXWiFi.php');
require('controllers/equipement.php');
require('controllers/typeEquipement.php');

// Les modèles
require('models/accueil.php');
require('models/broker.php');
require('models/moduleDMXWiFi.php');
require('models/equipement.php');
require('models/typeEquipement.php');

// Les classes du projet
require('classes/communicationBroker.php');
require('classes/communicationModuleDMXWiFi.php');
require('classes/communicationEquipementDMX.php');
require('classes/communicationTypeEquipementDMX.php');

require './vendor/autoload.php';

// Forme de l'URL, après réécriture : http://root/controleur/action/id
$router = new Router($_GET);
$controller = $router->createController();

if ($controller) {
	$controller->executeAction();
}
