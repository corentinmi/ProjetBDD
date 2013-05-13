<?php

require_once("view/ShowUserCP.php");
require_once("model/UserCP.php");
require_once("controller/GetPostMgr.php");

Class UserCPCtrl {
	
	private $gpm;
	private $view;
	private $userCP;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		
		$this->userCP = new UserCP();
		$this->view = new ShowUserCP($this->userCP);
		
		if ($this->userCP->isLogged()) {
			switch($this->gpm->get("action")) {
				case "logout":
					$this->userCP->logout();
					$this->view->printLogout();
					break;
				case "addUser":
					if ($this->userCP->isAdmin()) {
						if ($this->gpm->get("finalize")) {
							$this->userCP->addUser($this->gpm->post("email"), $this->gpm->post("password"), ($this->gpm->isSetPost("admin")));
							$this->view->printAddUserComplete();
						}
						else
							$this->view->printAddUser();
					}
					else
						$this->view->printUserCP();
					break;
				case "cpanel":
				default:
					$this->view->printUserCP();
					break;
			}
		}
		else {
			switch($this->gpm->get("action")) {
				case "login":
				default:
					if ($this->gpm->get("finalize")) {
						if ($this->userCP->login($this->gpm->post("email"), $this->gpm->post("password")))
							$this->view->printLoginFinish();
						else {
							$this->view->printLoginError($this->gpm->post("email"));
						}
					}
					else {
						$this->view->printLogin();
					}
					break;
			}
		}
		
		$this->view->printPage();
	}
	
}

?>