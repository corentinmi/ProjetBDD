<?php

require_once("view/Template.php");

Class ShowUserCP extends Template {
	
	private $page = "";
	private $email = "";
	
	private $userCP;
	
	public function __construct($userCP) {
		parent::__construct();
		$this->userCP = $userCP;
	}

	public function printPage() {
		$this->printHeader();
		echo $this->page;
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	public function printLoginFinish() {
		$this->page .= "Login Successful ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}
	
	public function printLogout() {
		$this->page .= "Logout Successful ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}
	
	public function printLoginError($email) {
		$this->email = $email;
		$this->page .= "Login Error <br /><br />";
		$this->printLogin();
	}
	
	public function printLogin() {
		$form = Array(	Array("Email", "email", "text", "$this->email"),
						Array("Password", "password", "password", ""));
		$this->page .= $this->getHtml()->makeTargetForm($form, "Login", "index.php?page=UserCP&action=login&finalize=1");
	}

	public function printUserCP() {
		if ($this->userCP->isAdmin()) {
			$this->page .= "Welcome, administrator <br /><br />";
			$linkList = array(	$this->getHtml()->makeLink("index.php?page=UserCP&action=addUser", "Add User"),
								$this->getHtml()->makeLink("index.php?page=UserCP&action=logout", "Logout"));
		}
			
		else  {
			$this->page .= "Welcome, user <br /><br />";
			$linkList = array(	$this->getHtml()->makeLink("index.php?page=UserCP&action=logout", "Logout"));
		}
		$this->page .= $this->getHtml()->makeList($linkList);
	}
	
	public function printAddUser() {
		$form = Array(	Array("Email", "email", "text", ""),
						Array("Password", "password", "password", ""),
						Array("Administrator", "admin", "checkbox", ""));
		$this->page .= $this->getHtml()->makeTargetForm($form, "Add User", "index.php?page=UserCP&action=addUser&finalize=1");
	}
	
	public function printAddUserComplete() {
		$this->page .= "User Added Successfully ! ";
		$this->page .= $this->getHtml()->makeLink("index.php", "Go Back");
	}

}

?>