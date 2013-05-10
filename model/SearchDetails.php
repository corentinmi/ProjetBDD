<?php

Class SearchDetails {
	
	private $orderBy;
	private $asc = true;
	private $field;
	private $value;
	private $type;
	private $index;
	
	private $sql;
	
	public function __construct($orderBy, $asc, $field, $value, $index, $type) {
		$this->asc = $asc;
		$this->orderBy = ($orderBy == null) ? "title" : $orderBy;
		$this->field = $field;
		$this->value = $value;
		$this->index = $index;
		$this->type = $type;
	}
	
	private function selectType() {
		switch($this->type) {
			case "article":
				$this->sql = "SELECT * FROM article a, publications p WHERE (a.DBLP_KEY_PUBL = p.DBLP_KEY) ";
				break;
			case "book":
				$this->sql = "SELECT * FROM book b, publications p WHERE (b.DBLP_KEY_PUBL = p.DBLP_KEY) ";
				break;
			case "master":
				$this->sql = "SELECT * FROM thesis t, publications p WHERE ((t.DBLP_KEY_PUBL = p.DBLP_KEY) AND (t.masterifTrue = true)) ";
				break;
			case "master":
				$this->sql = "SELECT * FROM thesis t, publications p WHERE ((t.DBLP_KEY_PUBL = p.DBLP_KEY) AND (t.masterifTrue = false)) ";
				break;
			default:
				$this->sql = "SELECT * FROM publications p WHERE 1 ";
		}
	}
	
	private function selectField() {
		if ($this->field != null) {
			$this->sql += "AND "+$field+" LIKE '%"+$value+"%' ";
		}
	}
	
	private function setOrderBy() {
		$this->sql += "ORDER BY "+$this->orderBy+" ";
		$this->sql += ($this->asc) ? "ASC" : "DESC";
	}
	
	private function selectIndex() {
		if ($this->index != null) {
			$this->sql += "LIMIT "+$this->index*30+", 30 ";
		}
		else
			$this->sql += "LIMIT 0, 30";
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