<?php

require_once("view/Template.php");

Class ShowUserCP extends Template {

	public function printPage() {
		$this->printHeader();
		$this->printUserCP();
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}

	public function printUserCP() {
		$linkList = array($this->getHtml()->makeLink("index.php?page=Catalog", "Catalogue"),
				$this->getHtml()->makeLink("index.php?page=UserCP", "User Control Panel"));
		echo $this->getHtml()->makeList($linkList);
	}

}

?>