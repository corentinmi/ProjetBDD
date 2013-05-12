<?php

Class UniqueSearchDetails {
	
	private $id;
	
	private $sql;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function makeUniqueArticleRequest() {
		$this->sql = "SELECT title, url, year, publisher, volume, number, pages, journal_name, journal_year, editor_name FROM publications p, article a WHERE ((p.DBLP_KEY = ".$this->id.") AND (a.DBLP_KEY_PUBL = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeUniqueBookRequest() {
		$this->sql = "SELECT title, url, year, publisher, isbn, editor_name FROM publications p, book b WHERE ((p.DBLP_KEY = ".$this->id.") AND (b.DBLP_KEY = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeUniqueThesisRequest() {
		$this->sql = "SELECT title, url, year, publisher, isbnPhd, masterifTrue FROM publications p, thesis t WHERE ((p.DBLP_KEY = ".$this->id.") AND (t.DBLP_KEY = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeModificationRequest($type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		switch($type) {
			case "article":
				$this->sql = "UPDATE publications p, article a SET p.title='".$title."', 
																   p.url='".$url."',
																   p.publisher='".$publisher."',
																   a.editor_name='".$editor_name."',
																   a.volume='".$volume."',
																   a.number='".$number."',
																   a.pages='".$pages."',
																   a.journal_name='".$journal_name."',
																   a.journal_year='".$journal_year."',
																   p.year='".$year."' WHERE ((p.DBLP_KEY = ".$this->id.") AND (a.DBLP_KEY_PUBL = ".$this->id."))";
				break;
			case "book":
				$this->sql = "UPDATE publications p, book b SET p.title='".$title."',
															   p.url='".$url."',
															   p.publisher='".$publisher."',
															   b.editor_name='".$editor_name."',
															   b.isbn='".isbn."',
															   p.year='".$year."' WHERE ((p.DBLP_KEY = ".$this->id.") AND (b.DBLP_KEY = ".$this->id."))";
				break;
			case "thesis":
				$this->sql = "UPDATE publications p, thesis t SET p.title='".$title."',
														   p.url='".$url."',
														   p.publisher='".$publisher."',
														   p.year='".$year."',
														   t.masterifTrue='".$masterifTrue."',
														   t.isbnPhd='".$isbnPhd."' WHERE ((p.DBLP_KEY = ".$this->id.") AND (t.DBLP_KEY = ".$this->id."))";
				break;
		}
		return $this->sql;
	}
	
	public function makeDeleteRequest($id, $type) {
		$this->id = $id;
		
		$this->sql = "DELETE FROM publications WHERE DBLP_KEY = ".$id." LIMIT 1; ";
		switch($type) {
			case "article":
				$this->sql .= "DELETE FROM article WHERE DBLP_KEY_PUBL = ".$id." LIMIT 1; ";
				break;
			case "book":
				$this->sql .= "DELETE FROM book WHERE DBLP_KEY = ".$id." LIMIT 1; ";
				break;
			case "thesis":
				$this->sql .= "DELETE FROM thesis WHERE DBLP_KEY = ".$id." LIMIT 1; ";
				break;
		}
		
		return $this->sql;
	}
	
}

?>