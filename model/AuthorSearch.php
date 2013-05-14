<?php

Class AuthorSearch {
	
	private $aname;
	private $req;
	
	public function __construct($aname) {
		$this->aname = $aname;
	}
	
	public function makeExistRequest() {
		$this->req = "SELECT * FROM Author WHERE Aname = '".$this->aname."'";
		return $this->req;
	}
	
	public function makeInsertRequest() {
		$this->req = "INSERT INTO Author (Aname) VALUES ('".$this->aname."')";
		return $this->req;
	}
	
	public function makePublicationsAuthorInsertRequest($id, $aid) {
		$this->req = "INSERT INTO PublicationsAuthor(DBLP_KEY, DBLP_KEY_AUTHOR) VALUES (".$id.", ".$aid.")";
		return $this->req;
	}
	
}