<?php

require_once("controller/GetPostMgr.php");
require_once("model/Request.php");
require_once("view/ShowRequest.php");

Class RequestCtrl {
	
	private $gpm;
	private $view;
	private $request;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		$this->request = new Request($this->gpm->get("id"), $this->gpm->get("name"));
		$this->view = new ShowRequest($this->request);
		$this->view->printPage();
	}
	
}