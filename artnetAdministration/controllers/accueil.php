<?php

class Accueil extends Controller
{
	private $viewmodel;

	public function __construct($action, $request)
	{
		parent::__construct($action, $request);
		$this->viewmodel = new AccueilModel();
	}

	protected function index()
	{
		$this->viewmodel->index();
		$this->display();
	}
}
