<?php

/**
 * @file model.php
 * @brief Définit la classe abstraite Model du modèle MVC
 * @version 1.0
 */

/**
 * @class Model
 * @brief Déclaration de la classe Model
 * @details C'est la classe mère de tous les modèles. Elle contient les méthodes communes à tous les modèles.
 */
abstract class Model
{
	protected $dbh = null; // ?PDO
	protected $stmt; // PDOStatement

	public function __construct()
	{
		if (DB_DRIVER) {
			if (!in_array("PDO", get_loaded_extensions()))
				die("L’extension PDO n’est pas présente !<br><br>");
			if (!in_array("pdo_mysql", get_loaded_extensions()))
				die("L’extension pdo_mysql n’est pas présente !<br><br>");
			$this->dbh = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS) or die("Echec de la création de l’instance PDO !");
		}
	}

	public function query($query)
	{
		if ($this->dbh) {
			$this->stmt = $this->dbh->prepare($query);
		}
	}

	public function bind($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}

	public function execute()
	{
		$this->stmt->execute();
	}

	public function getResults($mode = PDO::FETCH_ASSOC)
	{
		$this->execute();
		return $this->stmt->fetchAll($mode);
	}

	public function getResult($mode = PDO::FETCH_ASSOC)
	{
		$this->execute();
		return $this->stmt->fetch($mode);
	}

	public function count()
	{
		$this->execute();
		return $this->stmt->fetchColumn();
	}

	public function getLastInsertId()
	{
		return $this->dbh->lastInsertId();
	}
}
