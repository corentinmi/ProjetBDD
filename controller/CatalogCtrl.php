<?php

require_once("controller/GetPostMgr.php");
require_once("view/ShowCatalog.php");
require_once("model/Catalog.php");

Class CatalogCtrl {
	
	private $gpm;
	private $view;
	private $catalog;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		$this->catalog = new Catalog();
		$this->catalog->setSearchDetails($this->gpm->get("orderBy"),
										 $this->gpm->get("asc"),
										 $this->gpm->get("field"),
										 $this->gpm->get("value"),
										 $this->gpm->get("index"),
										 $this->gpm->get("type"));
		$this->view = new ShowCatalog($this->catalog);
		$this->view->printPage();
	}
	
}