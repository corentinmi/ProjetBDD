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
	
	public function deleteUselessAuthors() {
		$req = "DELETE FROM Author a WHERE NOT EXISTS (SELECT * FROM PublicationsAuthor p WHERE p.DBLP_KEY_AUTHOR=a.DBLP_KEY_AUTHOR)";
		$this->sql->query($req);
	}

	public function deleteUselessEditors() {
		$req = "DELETE FROM Editor e WHERE NOT EXISTS (SELECT * FROM EditorPublication p WHERE p.DBLP_KEY_EDITOR=e.DBLP_KEY)";
		$this->sql->query($req);
	}
	
	public function deleteUselessSchools() {
		$req = "DELETE FROM School s WHERE NOT EXISTS (SELECT * FROM SchoolThesis p WHERE p.DBLP_KEY_SCH=s.DBLP_KEY)";
		$this->sql->query($req);
	}
	
	public function editData($id, $type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd, $authors, $editors, $schools) {
		$this->search = new UniqueSearchDetails($id);
		$this->sql->query($this->search->makeModificationRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd));
		
		$this->sql->query($this->search->deletePublicationsAuthors());
		foreach ($authors as $author) {
			$search = new AuthorSearch($author);
			$this->sql->query($search->makeExistRequest());
			
			if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
				$aid = $this->sql->getResult->fetch_assoc['DBLP_KEY_AUTHOR'];
			}
			else {
				$this->sql->query($search->makeInsertRequest());
				$aid = $this->sql->insert_id;
			}
			
			$this->sql->query($search->makePublicationsAuthorInsertRequest($id, $aid));
		}
		
		$this->sql->query($this->search->deletePublicationsEditors());
		foreach ($editors as $editor) {
			$search = new EditorSearch($editor);
			$this->sql->query($search->makeExistRequest());
				
			if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
				$eid = $this->sql->getResult->fetch_assoc['DBLP_KEY'];
			}
			else {
				$this->sql->query($search->makeInsertRequest());
				$eid = $this->sql->insert_id;
			}
				
			$this->sql->query($search->makePublicationsEditorInsertRequest($id, $eid));
		}
		
		$this->deleteUselessEditors();
		$this->deleteUselessAuthors();
	}
	
	public function addData($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd, $authors, $editors, $schools) {
		$this->search = new AddDetails();
		$this->sql->multi_query($this->search->makeAddRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd));
		
		if ($authors) {
			foreach ($authors as $author) {
				$search = new AuthorSearch($author);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$aid = $this->sql->getResult->fetch_assoc['DBLP_KEY_AUTHOR'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$aid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsAuthorInsertRequest($id, $aid));
			}
		}
		
		if ($editors) {
			foreach ($editors as $editor) {
				$search = new EditorSearch($editor);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$sid = $this->sql->getResult->fetch_assoc['DBLP_KEY'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$sid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsSchoolInsertRequest($id, $sid));
			}
		}
		
		if ($schools) {
			foreach ($schools as $school) {
				$search = new SchoolSearch($school);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$eid = $this->sql->getResult->fetch_assoc['DBLP_KEY'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$eid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsEditorInsertRequest($id, $eid));
			}
		}
		foreach ($schools as $school) {
			$search = new EditorSearch($editor);
			$this->sql->query($search->makeExistRequest());
		
			if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
				$eid = $this->sql->getResult->fetch_assoc['DBLP_KEY_AUTHOR'];
			}
			else {
				$this->sql->query($search->makeInsertRequest());
				$eid = $this->sql->insert_id;
			}
		
			$this->sql->query($search->makePublicationsEditorInsertRequest($id, $eid));
		}
	}
	
	public function setUniqueSearch($id) {
		$this->id = $id;
		$this->search = new UniqueSearchDetails($id);
		
		$this->sql->query($this->search->makeUniqueArticleRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->addEditors();
			$this->addAuthors();
			$this->type = "article";
			return;
		}
		
		$this->sql->query($this->search->makeUniqueBookRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->type = "book";
			$this->addEditors();
			$this->addAuthors();
			return;
		}
		
		$this->sql->query($this->search->makeUniqueThesisRequest());
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->item = $this->sql->getResult()->fetch_assoc();
			$this->type = "thesis";
			$this->addAuthors();
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
	
	public function addAuthors() {
	
		$this->sql->query($this->search->makeAuthorsRequest());
		$authors = "";
		if ($this->sql->getResult()) {
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
				$cur = $this->sql->getResult()->fetch_assoc();
				$authors .= $cur['Aname'];
				if ($i != $this->sql->getResult()->num_rows - 1)
					$authors .= ";";
			}
		}
		$this->item['Authors'] = $authors;
	}
	
	public function addEditors() {

		$this->sql->query($this->search->makeEditorsRequest());
		$editors = "";
		if ($this->sql->getResult()) {
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
				$cur = $this->sql->getResult()->fetch_assoc();
				$editors .= $cur['Ename'];
				if ($i != $this->sql->getResult()->num_rows - 1)
					$editors .= ";";
			}
		}
		$this->item['Editors'] = $editors;
	}

}

?>