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
		$linkList = array(	$this->getHtml()->makeLink("index.php?page=Catalog", "Catalog"),
							$this->getHtml()->makeLink("index.php?page=Catalog&type=article", "Articles"),
							$this->getHtml()->makeLink("index.php?page=Catalog&type=book", "Books"),
							$this->getHtml()->makeLink("index.php?page=Catalog&type=master", "Master Thesis"),
							$this->getHtml()->makeLink("index.php?page=Catalog&type=phd", "PhD Thesis"),
							$this->getHtml()->makeLink("index.php?page=Search", "Search"),
					 	  	$this->getHtml()->makeLink("index.php?page=UserCP", "User Control Panel"));
		echo $this->getHtml()->makeList($linkList);
	}
	
}

?>