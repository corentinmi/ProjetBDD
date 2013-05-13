<?php

Class Session {
	
	private $session;
	
	public function __construct() {
		session_start();
		$this->session = $_SESSION;
	}
	
	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public function get($key) {
		if (isset($this->session[$key]))
			return addslashes($this->session[$key]);
		else
			return false;
	}
	
}