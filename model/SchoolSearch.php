<?php

Class SchoolSearch {
	
	private $sname;
	private $req;
	
	public function __construct($sname) {
		$this->sname = $sname;
	}
	
	public function makeExistRequest() {
		$this->req = "SELECT * FROM School WHERE Sname = '".$this->sname."'";
		return $this->req;
	}
	
	public function makeInsertRequest() {
		$this->req = "INSERT INTO School (Sname) VALUES ('".$this->sname."')";
		return $this->req;
	}
	
	public function makePublicationsSchoolInsertRequest($id, $sid) {
		$this->req = "INSERT INTO SchoolThesis(DBLP_KEY, DBLP_KEY_SCH) VALUES (".$id.", ".$sid.")";
		return $this->req;
	}
	
}

?>