<?php

class Catalog {
	
	private $search;
	
	public function __construct() {
		
	}
	
	public function setSearchDetails($orderBy, $asc, $field, $value, $index, $type) {
		$this->search = new SearchDetails($orderBy, $asc, $field, $value, $index, $type);
	}
	
}

?>