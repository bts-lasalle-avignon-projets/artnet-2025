<?php
// Démarre la session PHP
session_start();

require('config.php');

require('classes/message.php');
require('classes/messages.php');
require('classes/router.php');
require('classes/controller.php');
require('classes/model.php');

require('controllers/accueil.php');

require('models/accueil.php');

// Forme de l'URL, après réécriture : http://root/controleur/action/id
$router = new Router($_GET);
$controller = $router->createController();

if ($controller) {
	$controller->executeAction();
}
