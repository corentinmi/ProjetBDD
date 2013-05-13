<?php

require_once("model/Account.php");
require_once("model/Sql.php");

Class UserCP {
	
	private $account;
	private $sql;
	
	public function __construct() {
		$this->account = new Account();
		$this->sql = new Sql();
	}
	
	public function isLogged() {
		return $this->account->isLogged();
	}
	
	public function isAdmin() {
		return $this->account->isAdmin();
	}
	
	public function login($email, $password) {
		return $this->account->login($email, $password);
	}
	
	public function logout() {
		$this->account->logout();
	}
	
	public function addUser($email, $password, $admin) {
		$req = "INSERT INTO user (email, password, administrator) VALUES ('".$email."', '".$password."', '".$admin."')";
		$this->sql->query($req);
	}
	
}