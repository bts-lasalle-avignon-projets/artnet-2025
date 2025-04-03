<?php

/**
 * @file controller.php
 * @brief Définit la classe abstraite Controller du modèle MVC
 * @version 1.0
 */

/**
 * @class Controller
 * @brief Déclaration de la classe Controller
 * @details C'est la classe mère de tous les contrôleurs. Elle contient les méthodes communes à tous les contrôleurs.
 */
abstract class Controller
{
	protected $request;
	protected $action;

	public function __construct($action, $request)
	{
		$this->action = $action;
		$this->request = $request;
	}

	public function executeAction()
	{
		return $this->{$this->action}();
	}

	protected function display($datas = null)
	{
		// la vue ajoutée dans la page principale (main.php)
		$view = 'views/' . get_class($this) . '/' . $this->action . '.php';

		require('views/main.php');
	}
}
