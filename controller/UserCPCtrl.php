<?php

require_once("view/ShowUserCP.php");
require_once("model/UserCP.php");
require_once("controller/GetPostMgr.php");

Class UserCPCtrl {
	
	private $gpm;
	private $view;
	private $userCP;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		
		$this->userCP = new UserCP();
		switch($this->gpm->get("action")) {
			
		}
		
		$this->view = new ShowUserCP($this->userCP);
		$this->view->printPage();
	}
	
}

?>