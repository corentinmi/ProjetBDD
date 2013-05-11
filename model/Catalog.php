<?php

require_once("model/SearchDetails.php");
require_once("model/Sql.php");

class Catalog {
	
	private $search;
	private $sql;
	
	public function __construct() {
		$this->sql = new Sql();
	}
	
	public function setSearchDetails($orderBy, $asc, $field, $value, $index, $type) {
		$this->search = new SearchDetails($orderBy, $asc, $field, $value, $index, $type);
		$this->sql->query($this->search->makeRequest());
	}
	
	public function getSearchDetails() {
		return $this->search;
	}

	public function getTable() {
		if ($this->sql->getResult()) {
			$array = Array(Array("Title", "Url", "Year", "Publisher"));
			for ($i = 1; $i < $this->sql->getResult()->num_rows + 1; $i++) {
				$line = $this->sql->getResult()->fetch_assoc();
				$array[$i] = $line;
			}
			return $array;
		}
		else
			return false;
	}
	
}

?>