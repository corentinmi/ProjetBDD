<?php

class Sql extends mysqli {
	
	public function __construct() {
		parent::connect(localhost, root, "", dblp);
	}
	
}

?>