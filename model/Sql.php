<?php

class Sql extends mysqli {
	
	public function __construct() {
		parent::__construct("localhost", "root", "", "dblp");
	}
	
}

?>