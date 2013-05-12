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

	/*public function getTable() {
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$j = 0;
			$array = Array(Array(), Array());
			$line = $this->sql->getResult()->fetch_assoc();
			foreach($line as $item => $value) {
					$array[0][$j] = $item;
					$array[1][$j] = $value;
					$j++;		
			}
			for ($i = 2; $i < $this->sql->getResult()->num_rows + 1; $i++) {
				$j = 0;
				$line = $this->sql->getResult()->fetch_assoc();
				foreach ($line as $item => $value) {
					$array[$i][$j] = $value;
					$j++;
				}
				
			}
			return $array;
		}
		else
			return false;
	}*/
	
	public function getTable() {
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$array = Array();
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
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