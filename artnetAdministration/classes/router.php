<?php

/**
 * @file router.php
 * @brief Définit la classe Router du modèle MVC
 * @version 1.0
 */

/**
 * @class Router
 * @brief Déclaration de la classe Router
 * @details C'est le point d'entrée de l'application. Elle est chargée d'instancier le contrôleur approprié et d'exécuter l'action associée. L'URL, après réécriture, est de la forme : http://root/controleur/action/id
 */
class Router
{
	private $controller;
	private $action;
	private $request;

	public function __construct($request)
	{
		$this->request = $request;

		if (!empty($this->request['controleur'])) {
			$this->controller = $this->request['controleur'];
		} else {
			$this->controller = 'accueil';
		}

		if (!empty($this->request['action'])) {
			$this->action = $this->request['action'];
		} else {
			$this->action = 'index';
		}

		if (DEBUG) {
			$message = 'class Router<br />' . 'controleur : ' . $this->controller . '<br />' . 'action : ' . $this->action . '<br />';
			$debug = print_r($request, true);
			$message .= 'request : ' . $debug;
			Message::afficher($message, 'debug');
		}
	}

	public function createController()
	{
		if (class_exists($this->controller)) {
			$parents = class_parents($this->controller);
			if (in_array("Controller", $parents)) {
				if (method_exists($this->controller, $this->action)) {
					return new $this->controller($this->action, $this->request);
				} else {
					Message::afficher('Action ' . $this->action . ' introuvable !', 'erreur');
					return;
				}
			} else {
				Message::afficher('Classe mère Controller introuvable !', 'erreur');
				return;
			}
		} else {
			Message::afficher('Classe contrôleur ' . $this->controller . ' introuvable !', 'erreur');
			return;
		}
	}
}
