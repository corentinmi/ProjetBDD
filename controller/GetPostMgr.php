<?php

require_once("model/sql.php");

Class GetPostMgr {
	
	private $post;
	private $get;
	
	public function __construct() {
		$this->post = $_POST;
		$this->get = $_GET;
	}	
	
	public function get($item) {
		if (isset($this->get[$item])) {
			return addslashes($get($item));
		}
		else
			return false;
	}
	
	public function post($item) {
		if (isset($this->post[$item])) {
			return addslashes($get($item));
		}
		else
			return false;
	}
	
}

?>