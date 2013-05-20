<?php

require_once("model/Sql.php");
require_once("model/UniqueSearchDetails.php");
require_once("model/AddDetails.php");

require_once("model/AuthorSearch.php");
require_once("model/EditorSearch.php");
require_once("model/SchoolSearch.php");

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
		$req = "DELETE FROM Author WHERE NOT EXISTS (SELECT * FROM PublicationsAuthor WHERE PublicationsAuthor.DBLP_KEY_AUTHOR=Author.DBLP_KEY_AUTHOR)";
		$this->sql->query($req);
	}

	public function deleteUselessEditors() {
		$req = "DELETE FROM Editor WHERE NOT EXISTS (SELECT * FROM EditorPublication WHERE EditorPublication.DBLP_KEY_EDITOR=Editor.DBLP_KEY_EDITOR)";
		$this->sql->query($req);
	}
	
	public function deleteUselessSchools() {
		$req = "DELETE FROM School WHERE NOT EXISTS (SELECT * FROM SchoolThesis WHERE SchoolThesis.DBLP_KEY_SCH=School.DBLP_KEY)";
		$this->sql->query($req);
	}
	
	public function editData($id, $type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd, $authorLine, $editorLine, $schoolLine) {
		$this->search = new UniqueSearchDetails($id);
		$this->sql->query($this->search->makeModificationRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd));
		
		$authors = explode(";", $authorLine);
		$editors = explode(";", $editorLine);
		$schools = explode(";", $schoolLine);
		
		$this->sql->query($this->search->deletePublicationsAuthors());
		if ($authorLine) {
			foreach ($authors as $author) {
				$search = new AuthorSearch($author);
				$this->sql->query($search->makeExistRequest());
				
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$aid = $assoc['DBLP_KEY_AUTHOR'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$aid = $this->sql->insert_id;
				}
				
				$this->sql->query($search->makePublicationsAuthorInsertRequest($id, $aid));
			}
		}
		
		$this->sql->query($this->search->deletePublicationsEditors());
		if ($editorLine) {
			foreach ($editors as $editor) {
				$search = new EditorSearch($editor);
				$this->sql->query($search->makeExistRequest());
					
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$eid = $assoc['DBLP_KEY'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$eid = $this->sql->insert_id;
				}
					
				$this->sql->query($search->makePublicationsEditorInsertRequest($id, $eid));
			}
		}
		
		$this->sql->query($this->search->deletePublicationsSchools());
		if ($schoolLine) {
			foreach ($schools as $school) {
				$search = new SchoolSearch($school);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$sid = $assoc['DBLP_KEY'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$sid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsSchoolInsertRequest($id, $sid));
			}
		}
		
		$this->deleteUselessEditors();
		$this->deleteUselessAuthors();
		$this->deleteUselessSchools();
	}
	
	public function addData($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd, $authorLine, $editorLine, $schoolLine) {
		$this->search = new AddDetails();
		$req = $this->search->makeAddRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd);
		$this->sql->query($req[0]);
		$id = $this->sql->insert_id;
		$this->sql->query($req[1]);
		
		$authors = explode(";", $authorLine);
		$editors = explode(";", $editorLine);
		$schools = explode(";", $schoolLine);
		
		if ($authorLine) {
			foreach ($authors as $author) {
				$search = new AuthorSearch($author);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$aid = $assoc['DBLP_KEY_AUTHOR'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$aid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsAuthorInsertRequest($id, $aid));
			}
		}
		
		if ($editorLine) {
			foreach ($editors as $editor) {
				$search = new EditorSearch($editor);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$eid = $assoc['DBLP_KEY_EDITOR'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$eid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsEditorInsertRequest($id, $eid));
			}
		}
		
		if ($schoolLine) {
			foreach ($schools as $school) {
				$search = new SchoolSearch($school);
				$this->sql->query($search->makeExistRequest());
			
				if ( ($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0) ) {
					$assoc = $this->sql->getResult()->fetch_assoc();
					$sid = $assoc['DBLP_KEY'];
				}
				else {
					$this->sql->query($search->makeInsertRequest());
					$sid = $this->sql->insert_id;
				}
			
				$this->sql->query($search->makePublicationsSchoolInsertRequest($id, $sid));
			}
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
			$this->addSchools();
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
	
	public function addSchools() {
	
		$this->sql->query($this->search->makeSchoolsRequest());
		$schools = "";
		if ($this->sql->getResult()) {
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
				$cur = $this->sql->getResult()->fetch_assoc();
				$schools .= $cur['Sname'];
				if ($i != $this->sql->getResult()->num_rows - 1)
					$schools .= ";";
			}
		}
		$this->item['Schools'] = $schools;
	}

}

?>