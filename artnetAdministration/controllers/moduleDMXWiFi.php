<?php

class ModuleDMXWiFi extends Controller
{
    private $viewmodel;
    
    public function __construct($action, $request)
	{
		parent::__construct($action, $request);
		$this->viewmodel = new ModuleDMXWiFiModel();
	}

    protected function index()
	{
		// Récupère la liste des module DMX WIFI
		$listeModuleDMXWiFi = $this->viewmodel->index();
		// Affiche la liste des module DMX WIFI
		$this->display($listeModuleDMXWiFi);
	}
	
	public function testConnection()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['submit']) && $_POST['submit'] === "Connecter") {
				return $this->test();
			}
		}
	}
}