<?php

class Accueil extends Controller
{
	protected function index()
	{
		$viewmodel = new AccueilModel();
		$this->returnView($viewmodel->index(), true);
	}
}
