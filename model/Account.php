<?php

require_once("model/Session.php");
require_once("model/Sql.php");

Class Account {
	
	private $user;
	private $session;
	private $sql;
	
	public function __construct() {
		$this->session = new Session();
		$this->sql = new Sql();
		$this->user = unserialize(stripslashes($this->session->get("user")));
	}
	
	public function isLogged() {
		return ($this->user);
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function isAdmin() {
		return $this->user["administrator"];
	}
	
	public function login($email, $password) {
		$email = $this->sql->real_escape_string($email);
		$password = $this->sql->real_escape_string($password);
		$req = "SELECT * FROM user WHERE ((email = '".$email."') AND (password = '".$password."'))";
		$this->sql->query($req);
		
		if (($this->sql->getResult()) && ($this->sql->getResult()->num_rows > 0)) {
			$this->user = $this->sql->getResult()->fetch_assoc();
			$this->session->set("user", serialize($this->user));
			return $this->user;
		}
		else 
			return false;
	}
	
	public function logout() {
		$this->session->set("user", false);
		$this->user = false;
	}
	
}

?>