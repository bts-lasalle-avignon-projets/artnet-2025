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

// Les modèles
require('models/accueil.php');
require('models/broker.php');
require('models/moduleDMXWiFi.php');

// Les classes du projet
require('classes/communicationBroker.php');
require('classes/communicationModuleDMXWiFi.php');

require './vendor/autoload.php';

// Forme de l'URL, après réécriture : http://root/controleur/action/id
$router = new Router($_GET);
$controller = $router->createController();

if ($controller) {
	$controller->executeAction();
}
