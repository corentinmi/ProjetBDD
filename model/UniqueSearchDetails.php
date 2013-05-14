<?php

Class UniqueSearchDetails {
	
	private $id;
	
	private $sql;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function makeUniqueArticleRequest() {
		$this->sql = "SELECT title, url, year, publisher, volume, number, pages, journal_name, journal_year FROM Publications p, Article a WHERE ((p.DBLP_KEY = ".$this->id.") AND (a.DBLP_KEY_PUBL = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeUniqueBookRequest() {
		$this->sql = "SELECT title, url, year, publisher, isbn FROM Publications p, Book b WHERE ((p.DBLP_KEY = ".$this->id.") AND (b.DBLP_KEY = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeUniqueThesisRequest() {
		$this->sql = "SELECT title, url, year, publisher, isbnPhd, masterifTrue FROM Publications p, Thesis t WHERE ((p.DBLP_KEY = ".$this->id.") AND (t.DBLP_KEY = ".$this->id."))";
		return $this->sql;
	}
	
	public function makeModificationRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		switch($type) {
			case "article":
				$this->sql = "UPDATE Publications p, Article a SET p.title='".$title."', 
																   p.url='".$url."',
																   p.publisher='".$publisher."',
																   a.volume='".$volume."',
																   a.number='".$number."',
																   a.pages='".$pages."',
																   a.journal_name='".$journal_name."',
																   a.journal_year='".$journal_year."',
																   p.year='".$year."' WHERE ((p.DBLP_KEY = ".$this->id.") AND (a.DBLP_KEY_PUBL = ".$this->id."))";
				break;
			case "book":
				$this->sql = "UPDATE Publications p, Book b SET p.title='".$title."',
															   p.url='".$url."',
															   p.publisher='".$publisher."',
															   b.isbn='".isbn."',
															   p.year='".$year."' WHERE ((p.DBLP_KEY = ".$this->id.") AND (b.DBLP_KEY = ".$this->id."))";
				break;
			case "thesis":
				$this->sql = "UPDATE Publications p, Thesis t SET p.title='".$title."',
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
				$this->sql .= "DELETE FROM Article WHERE DBLP_KEY_PUBL = ".$id." LIMIT 1; ";
				break;
			case "book":
				$this->sql .= "DELETE FROM Book WHERE DBLP_KEY = ".$id." LIMIT 1; ";
				break;
			case "thesis":
				$this->sql .= "DELETE FROM Thesis WHERE DBLP_KEY = ".$id." LIMIT 1; ";
				break;
		}
		
		return $this->sql;
	}
	
	public function makeAuthorsRequest() {
		$this->sql = "SELECT Aname FROM PublicationsAuthor p, Author a WHERE ((p.DBLP_KEY=".$this->id.") AND (p.DBLP_KEY_AUTHOR = a.DBLP_KEY_AUTHOR))";
		return $this->sql;
	}
	
	public function makeEditorsRequest() {
		$this->sql = "SELECT Ename FROM EditorPublication p, Editor e WHERE ((p.DBLP_KEY=".$this->id.") AND (p.DBLP_KEY_EDITOR = a.DBLP_KEY))";
		return $this->sql;
	}
	
	public function deletePublicationsAuthors() {
		$this->sql = "DELETE FROM PublicationsAuthor WHERE DBLP_KEY=".$this->id;
		return $this->sql;
	}
	
	public function deletePublicationsEditors() {
		$this->sql = "DELETE FROM EditorPublication WHERE DBLP_KEY=".$this->id;
		return $this->sql;
	}
	
}

?>