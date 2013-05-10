<?php

require_once("view/Template.php");

Class Home extends Template {
	
	public function printPage() {
		$this->printHeader();
		$this->printHomeMenu();
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printHomeMenu() {
		$linkList = array($this->getHtml()->makeLink("index.php?page=Catalog", "Catalogue"),
					 	  $this->getHtml()->makeLink("index.php?page=UserCP", "User Control Panel"));
		echo $this->getHtml()->makeList($linkList);
	}
	
}

?>