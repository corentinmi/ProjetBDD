<?php

require_once("model/SearchDetails.php");
require_once("model/UniqueSearchDetails.php");
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
	
	public function addAuthorsTable($table) {
		
		foreach ($table as &$line) {
			$search = new UniqueSearchDetails($line['DBLP_KEY']);
			$this->sql->query($search->makeAuthorsRequest());
			$authors = "";
			if ($this->sql->getResult()) {
				for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
					$cur = $this->sql->getResult()->fetch_assoc();
					$authors .= $cur['Aname'];
					if ($i != $this->sql->getResult()->num_rows - 1)
						$authors .= ", ";
				}
			}
			$line['Authors'] = $authors;
		}
		return $table;
	}
	
	public function addEditorsTable($table) {
	
		foreach ($table as &$line) {
			$search = new UniqueSearchDetails($line['DBLP_KEY']);
			$this->sql->query($search->makeEditorsRequest());
			$editors = "";
			if ($this->sql->getResult()) {
				for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
					$cur = $this->sql->getResult()->fetch_assoc();
					$editors .= $cur['Ename'];
					if ($i != $this->sql->getResult()->num_rows - 1)
						$editors .= ", ";
				}
			}
			$line['Editors'] = $editors;
		}
		return $table;
	}
	
	public function addSchoolsTable($table) {
	
		foreach ($table as &$line) {
			$search = new UniqueSearchDetails($line['DBLP_KEY']);
			$this->sql->query($search->makeSchoolsRequest());
			$schools = "";
			if ($this->sql->getResult()) {
				for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
					$cur = $this->sql->getResult()->fetch_assoc();
					$schools .= $cur['Sname'];
					if ($i != $this->sql->getResult()->num_rows - 1)
						$schools .= ", ";
				}
			}
			$line['School'] = $schools;
		}
		return $table;
	}
	
	public function getTable() {
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$array = Array();
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
				$line = $this->sql->getResult()->fetch_assoc();
				$array[$i] = $line;
			}
			if (($this->search->getType() == "article") || ($this->search->getType() == "book"))
				$array = $this->addEditorsTable($array);
			if (($this->search->getType() == "phd") || ($this->search->getType() == "master"))
				$array = $this->addSchoolsTable($array);
			$array = $this->addAuthorsTable($array);
			return $array;
		}
		else
			return false;
	}
	
}

?>