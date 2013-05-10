<?php

require_once("view/Home.php");

Class HomeCtrl {
	
	private $gpm;
	private $view;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		$this->view = new Home();
		$this->view->printPage();
	}
	
}
?>