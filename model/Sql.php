<?php

class Sql extends mysqli {
	
	private $result;
	private $req;
	
	public function __construct() {
		//parent::__construct("mysql10.000webhost.com", "a6940219_admin", "polytech2015", "a6940219_dblp");
		parent::__construct("localhost", "root", "", "test");
	}
	
	public function getResult() {
		return $this->result;
	}
	
	public function getResultArray() {
		if ($this->result) {
			$array = Array();
			$line;
			for ($i = 0; $i < $this->result->num_rows; $i++) {
				$line = $this->result->fetch_assoc();
				$array[$i] = $line;
			}
			return $array;
		}
		else
			return false;
	}
	
	public function query($req) {
		$this->req = $req;
		
		$this->result = parent::query($this->req);
	}
	
	public function multi_query($req) {
		$this->req = $req;
		echo $req . "<br /><br />";
		$this->result = parent::multi_query($this->req);
	}
	
}

?>