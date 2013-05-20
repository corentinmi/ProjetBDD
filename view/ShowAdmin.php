<?php

require_once("view/Template.php");
require_once("model/Admin.php");

Class ShowAdmin extends Template {
	
	private $admin;
	private $page;

	public function printPage() {
		$this->printHeader();
		echo $this->page;
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function __construct($admin) {
		parent::__construct();
		$this->admin = $admin;
	}

	public function printAddForm() {
		$this->printHeader();
		
		$form = Array(Array("Type", "type", "text", ""),
					  Array("Title", "title", "text", ""),
					  Array("Url", "url", "text", ""),
					  Array("Year", "year", "text", ""),
					  Array("Publisher", "publisher", "text", ""),
					  Array("Isbn", "isbn", "text", ""),
					  Array("Volume", "volume", "text", ""),
					  Array("Number", "number", "text", ""),
					  Array("Pages", "pages", "text", ""),
					  Array("Journal_name", "journal_name", "text", ""),
					  Array("Journal_year", "journal_year", "text", ""),
					  Array("MasterifTrue", "masterifTrue", "text", ""),
					  Array("IsbnPhd", "isbnPhd", "text", ""),
					  Array("Authors", "Authors", "text", ""),
					  Array("Editors", "Editors", "text", ""),
					  Array("Schools", "Schools", "text", ""),
					  Array("", "finalize", "hidden", "1"));
		echo $this->getHtml()->makeForm($form, "Ajouter");
		
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printAddFormError() {
		$this->printHeader();
		
		echo "Error : Type must be article, book or thesis";
		
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printEditForm() {
		$this->printHeader();
		
		$form = Array();
		$i = 0;
		
		foreach ($this->admin->getItem() as $field => $value) {
			$form[$i] = Array(ucfirst($field), $field, "text", $value);
			$i++;
		}
		
		$form[$i] = Array("", "type", "hidden", $this->admin->getType());
		$form[$i+1] = Array("", "id", "hidden", $this->admin->getID());
		$form[$i+2] = Array("", "finalize", "hidden", "1");
		
		echo $this->getHtml()->makeForm($form, "Modifier");
		
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printEditFinish() {
		$this->page .= "Modification Successful ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}
	
	public function printAddFinish() {
		$this->page .= "Insertion Successful ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}
	
	public function printDeleteFinish() {
		$this->page .= "Deletion Successful ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}

}

?>