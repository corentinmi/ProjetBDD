<?php

require_once("view/Html.php");

Class Template {
	
	private $html;
	
	public function getHtml() {
		return $this->html;
	}
	
	public function __construct() {
		//ob_start();
		$this->html = new Html();
	}
	
	public function printHeader() {
		include("view/templates/header.php"); 
	}
	
	public function printFooter() {
		include("view/templates/sidebar&footer.php"); 
	}
	
	public function printSideMenu() {
		
	}
	
	public function finalize() {
		//ob_end_flush();
	}
	
}

?>