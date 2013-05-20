<?php

require_once("view/Template.php");

Class ShowSearch extends Template {
	
	public function printPage() {
		$this->printHeader();
		$this->printSearchForm();
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printSearchForm() {
		$form = Array(Array("Type", "type", "select", Array("none" => "All", "article" => "Article", "book" => "Book", "master" => "Master Thesis", "phd" => "PhD Thesis")),
					  Array("Field", "field", "select", Array("title" => "Title", "url" => "Url", "year" => "Year", "publisher" => "Publisher", "volume" => "Volume", "number" => "Number", "pages" => "Pages", "journal_name" => "Journal Name", "journal_year" => "Journal Year", "isbn" => "ISBN", "isbnPhD" => "ISBN PHD")),
					  Array("Value", "value", "text", ""),
					  Array("", "page", "hidden", "catalog"));
		echo $this->getHtml()->makeGetForm($form, "Rechercher");
	}
	
}

?>