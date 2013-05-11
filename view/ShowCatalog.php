<?php

require_once("view/Template.php");
require_once("view/Html.php");

require_once("model/Catalog.php");

Class ShowCatalog extends Template {
	
	private $catalog;
	private $html;
	
	public function __construct($catalog) {
		$this->catalog = $catalog;
		$this->html = new Html();
	}
	
	public function printPage() {
		$this->printHeader();
		$this->printCatalog();
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	private function printCatalog() {
		$table = $this->catalog->getTable();
		$table = $this->makeTableHeader($table);
		if ($table)
			echo $this->html->makeTable($table);
		else
			echo "No Records Found";
	}
	
	private function makeTableHeader($table) {
		for ($i = 0; $i < count($table[0]); $i++) {
			$table[0][$i] = (($this->catalog->getSearchDetails()->getOrderBy() == $table[0][$i]) && ($this->catalog->getSearchDetails()->getAsc())) ?
					$this->html->makeLink("index.php?page=catalog&orderBy=".$table[0][$i].
																"&asc=0".
																"&type=".$this->catalog->getSearchDetails()->getType().
																"&field=".$this->catalog->getSearchDetails()->getField().
																"&value=".$this->catalog->getSearchDetails()->getValue(), $table[0][$i]) :
					$this->html->makeLink("index.php?page=catalog&orderBy=".$table[0][$i].
																"&asc=1".
																"&type=".$this->catalog->getSearchDetails()->getType().
																"&field=".$this->catalog->getSearchDetails()->getField().
																"&value=".$this->catalog->getSearchDetails()->getValue(), $table[0][$i]);
		}
		return $table;
	}
	
}
