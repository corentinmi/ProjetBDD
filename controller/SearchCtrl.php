<?php

require_once("view/ShowSearch.php");

Class SearchCtrl {

	private $gpm;
	private $view;

	public function __construct($gpm) {
		$this->gpm = $gpm;
		$this->view = new ShowSearch();
		$this->view->printPage();
	}

}
?>