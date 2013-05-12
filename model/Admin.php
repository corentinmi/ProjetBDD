<?php

require_once("model/Sql.php");
require_once("model/UniqueSearchDetails.php");
require_once("model/AddDetails.php");

class Admin {

	private $search;
	private $sql;
	
	private $item;
	private $type;
	private $id;
	
	public function getID() {
		return $this->id;
	}
	
	public function getItem() {
		return $this->item;
	}
	
	public function setItem($item) {
		$this->item = $item;
	}
	
	public function getType() {
		return $this->type;
	}

	public function __construct() {
		$this->sql = new Sql();
	}
	
	public function editData($id, $type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		$this->search = new UniqueSearchDetails($id);
		
		$this->sql->query($this->search->makeModificationRequest($type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd));
	}
	
	public function addData($type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		$this->search = new AddDetails();
		$this->sql->multi_query($this->search->makeAddRequest($type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd));
	}
	
	public function setUniqueSearch($id) {
		$this->id = $id;
		$this->search = new UniqueSearchDetails($id);
		
		$this->sql->query($this->search->makeUniqueArticleRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->type = "article";
			return;
		}
		
		$this->sql->query($this->search->makeUniqueBookRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->type = "book";
			return;
		}
		
		$this->sql->query($this->search->makeUniqueThesisRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->type = "thesis";
			return;
		}
	}
	
	public function deleteData($id) {
		$this->id = $id;
		$this->setUniqueSearch($id);
		
		$this->sql->multi_query($this->search->makeDeleteRequest($this->id, $this->type));
	}

	public function getSearchDetails() {
		return $this->search;
	}

}

?>