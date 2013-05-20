<?php

require_once("view/Template.php");
require_once("view/Html.php");

require_once("model/Catalog.php");
require_once("model/Account.php");

Class ShowCatalog extends Template {
	
	private $catalog;
	private $html;
	private $account;
	
	public function __construct($catalog) {
		$this->catalog = $catalog;
		$this->account = new Account();
		$this->html = new Html();
	}
	
	public function printPage() {
		$this->printHeader();
		$this->printCatalog();
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	private function printNextPageLink() {
		$index = $this->catalog->getSearchDetails()->getIndex();
		echo("<br />");
		if ($index > 0) {
			$get = Array();
					
			$get["page"] = "catalog";
			if ($this->catalog->getSearchDetails()->getType())
				$get["type"] = $this->catalog->getSearchDetails()->getType();
			if ($this->catalog->getSearchDetails()->getField())
				$get["field"] = $this->catalog->getSearchDetails()->getField();
			if ($this->catalog->getSearchDetails()->getValue())
				$get["value"] = $this->catalog->getSearchDetails()->getValue();
			if ($this->catalog->getSearchDetails()->getOrderBy())
				$get["orderBy"] = $this->catalog->getSearchDetails()->getOrderBy();
			if ($this->catalog->getSearchDetails()->getAsc())
				$get["asc"] = $this->catalog->getSearchDetails()->getAsc();
			
			$get["index"] = $index-1;
			
			
			echo $this->html->makeGetLink("index.php", $get, "Previous Page");
			echo (" &middot; ");
		}
		
		$get = Array();
			
		$get["page"] = "catalog";
		if ($this->catalog->getSearchDetails()->getType())
			$get["type"] = $this->catalog->getSearchDetails()->getType();
		if ($this->catalog->getSearchDetails()->getField())
			$get["field"] = $this->catalog->getSearchDetails()->getField();
		if ($this->catalog->getSearchDetails()->getValue())
			$get["value"] = $this->catalog->getSearchDetails()->getValue();
		if ($this->catalog->getSearchDetails()->getOrderBy())
			$get["orderBy"] = $this->catalog->getSearchDetails()->getOrderBy();
		if ($this->catalog->getSearchDetails()->getAsc())
			$get["asc"] = $this->catalog->getSearchDetails()->getAsc();
			
		$get["index"] = $index+1;
			
		echo $this->html->makeGetLink("index.php", $get, "Next Page");
	}
	
	private function printCatalog() {
		$table = $this->catalog->getTable();
		
		if ($table) {
			$output = $this->makeTableHeader($table);
			$output = $this->makeTableRowEnds($table, $output);
			echo $this->html->makeTable($output);
			$this->printNextPageLink();
		}
		else
			echo "No Records Found";
	}
	
	private function makeTableRowEnds($table, $output) {
		
		if ($this->account->isAdmin()) {
			$i = 1;
			foreach ($table as $line) {
				$j = count($line);
				$get = Array("page" => "admin", "action" => "edit", "id" => $line["DBLP_KEY"]);
				$output[$i][$j] = $this->html->makeGetLink("index.php", $get, "Edit");
				$get = Array("page" => "admin", "action" => "delete", "id" => $line["DBLP_KEY"]);
				$output[$i][$j+1] = $this->html->makeGetLink("index.php", $get, "Delete");
				$i++;
			}
		}
		
		return $output;
		
	}
	
	private function makeTableHeader($table) {
		$i = 0;
		foreach ($table[0] as $field => $value) {
			if ($field != "DBLP_KEY") {
				if (($field == "Authors") || ($field == "Editors") || ($field == "School")) {
					$output[0][$i] = $field;
				}
				else {
					$get = Array();
					
					$get["page"] = "catalog";
					if ($this->catalog->getSearchDetails()->getType())
						$get["type"] = $this->catalog->getSearchDetails()->getType();
					if ($this->catalog->getSearchDetails()->getField())
						$get["field"] = $this->catalog->getSearchDetails()->getField();
					if ($this->catalog->getSearchDetails()->getValue())
						$get["value"] = $this->catalog->getSearchDetails()->getValue();
					$get["orderBy"] = $field;
					$get["asc"] = (($this->catalog->getSearchDetails()->getOrderBy() == $field) && (!($this->catalog->getSearchDetails()->getAsc()))) ? 1 : 0;
					
					
					$output[0][$i] = $this->html->makeGetLink("index.php", $get, ucfirst($field));
				}
				$i++;
			}
		}
		$i = 1;
		foreach ($table as $line) {
			$j = 0;
			foreach ($line as $field => $value) {
				if ($field != "DBLP_KEY") {
					$output[$i][$j] = $value;
					$j++;
				}
			}
			$i++;
		}
		return $output;
	}
	
}
