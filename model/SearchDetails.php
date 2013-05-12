<?php

Class SearchDetails {
	
	private $orderBy;
	private $asc = true;
	private $field;
	private $value;
	private $type;
	private $index;
	
	private $id;
	
	private $sql;
	
	public function __construct($orderBy, $asc, $field, $value, $index, $type) {
		$this->asc = $asc;
		$this->orderBy = ($orderBy == false) ? "title" : $orderBy;
		$this->field = $field;
		$this->value = $value;
		$this->index = $index;
		$this->type = $type;
	}
	
	private function selectType() {
		switch($this->type) {
			case "article":
				$this->sql = "SELECT DBLP_KEY, title, url, year, publisher, volume, number, pages, journal_name, journal_year ";
				$this->sql .= "FROM article a, publications p WHERE (a.DBLP_KEY_PUBL = p.DBLP_KEY) ";
				break;
			case "book":
				$this->sql = "SELECT DBLP_KEY, title, url, year, publisher, isbn ";
				$this->sql .= "FROM book b, publications p WHERE (b.DBLP_KEY = p.DBLP_KEY) ";
				break;
			case "master":
				$this->sql = "SELECT DBLP_KEY, title, url, year, publisher ";
				$this->sql .= "FROM thesis t, publications p WHERE ((t.DBLP_KEY = p.DBLP_KEY) AND (t.masterifTrue = 1)) ";
				break;
			case "phd":
				$this->sql = "SELECT DBLP_KEY, title, url, year, publisher, isbnPhd ";
				$this->sql .= "FROM thesis t, publications p WHERE ((t.DBLP_KEY = p.DBLP_KEY) AND (t.masterifTrue = 0)) ";
				break;
			default:
				$this->sql = "SELECT DBLP_KEY, title, url, year, publisher ";
				$this->sql .= "FROM publications p WHERE 1 ";
		}
	}
	
	private function selectField() {
		if ($this->field != false) {
			$this->sql .= "AND ".$this->field." LIKE '%".$this->value."%' ";
		}
	}
	
	private function setOrderBy() {
		$this->sql .= "ORDER BY ".$this->orderBy." ";
		$this->sql .= ($this->asc) ? "DESC " : "ASC ";
	}
	
	public function getOrderBy() {
		return $this->orderBy;
	}
	
	public function getAsc() {
		return $this->asc;
	}
	
	public function getField() {
		return $this->field;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getIndex() {
		return $this->index;
	}
	
	private function selectIndex() {
		if ($this->index != false) {
			$this->sql .= "LIMIT ". $this->index*30 .", 30 ";
		}
		else
			$this->sql .= "LIMIT 0, 30";
	}
	
	public function makeRequest() {
		$this->selectType();
		$this->selectField();
		$this->setOrderBy();
		$this->selectIndex();
		
		return $this->sql;
	}
	
}

?>