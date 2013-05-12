<?php

require_once("model/Sql.php");
require_once("controller/GetPostMgr.php");
require_once("controller/PageSelector.php");

Class GlobalCtrl {
	
	private $gpm;
	private $page;
	private $className;
	private $pageCtrl;
	
	public function __construct() {
		$this->gpm = new GetPostMgr();
		$this->page = new PageSelector($this->gpm);
	}
	
}

?>