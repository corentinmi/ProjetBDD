<?php

Class EditorSearch {
	
	private $ename;
	private $req;
	
	public function __construct($ename) {
		$this->ename = $ename;
	}
	
	public function makeExistRequest() {
		$this->req = "SELECT * FROM Editor WHERE Ename = '".$this->ename."'";
		return $this->req;
	}
	
	public function makeInsertRequest() {
		$this->req = "INSERT INTO Editor (Ename) VALUES ('".$this->ename."')";
		return $this->req;
	}
	
	public function makePublicationsEditorInsertRequest($id, $eid) {
		$this->req = "INSERT INTO EditorPublication(DBLP_KEY, DBLP_KEY_EDITOR) VALUES (".$id.", ".$eid.")";
		return $this->req;
	}
	
}

?>