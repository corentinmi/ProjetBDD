<?php

class Catalog {
	
	private SearchDetails search;
	
	public function __construct() {
		
	}
	
	public function setSearchDetails($orderBy, $asc, $field, $value, $index, $type) {
		$this->searchDetails = new SearchDetails($orderBy, $asc, $field, $value, $index, $type);
	}
	
}

?>