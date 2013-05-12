<?php

require_once("view/ShowAdmin.php");
require_once("model/Admin.php");
require_once("controller/GetPostMgr.php");

Class AdminCtrl {

	private $gpm;
	private $view;
	private $admin;

	public function __construct($gpm) {
		$this->gpm = $gpm;

		$this->admin = new Admin();
		$this->view = new ShowAdmin($this->admin);
		
		if ($this->gpm->post("finalize")) {
			switch($this->gpm->get("action")) {
				case "add":
					if (($this->gpm->post("type") == "article") || ($this->gpm->post("type") == "book") || ($this->gpm->post("type") == "thesis")) {
						$this->admin->addData( $this->gpm->post("type"),
											   $this->gpm->post("title"),
											   $this->gpm->post("url"),
											   $this->gpm->post("year"),
											   $this->gpm->post("publisher"),
											   $this->gpm->post("isbn"),
											   $this->gpm->post("editor_name"),
											   $this->gpm->post("volume"),
											   $this->gpm->post("number"),
											   $this->gpm->post("pages"),
											   $this->gpm->post("journal_name"),
											   $this->gpm->post("journal_year"),
											   $this->gpm->post("masterifTrue"),
											   $this->gpm->post("isbnPhd"));
						$this->view->printAddFinish();
					}
					else
						$this->view->printAddFormError();
					break;
				case "edit":
					$this->admin->editData($this->gpm->post("id"),
										   $this->gpm->post("type"),
										   $this->gpm->post("title"),
										   $this->gpm->post("url"),
										   $this->gpm->post("year"),
										   $this->gpm->post("publisher"),
										   $this->gpm->post("isbn"),
										   $this->gpm->post("editor_name"),
										   $this->gpm->post("volume"),
										   $this->gpm->post("number"),
										   $this->gpm->post("pages"),
										   $this->gpm->post("journal_name"),
										   $this->gpm->post("journal_year"),
										   $this->gpm->post("masterifTrue"),
										   $this->gpm->post("isbnPhd"));
					$this->view->printEditFinish();
					break;
				default:
					$this->view->printActionRequired();
			}
		}
		else {
			switch($this->gpm->get("action")) {
				case "add":
					$this->view->printAddForm();
					break;
				case "edit":
					$this->admin->setUniqueSearch($this->gpm->get("id"));
					$this->view->printEditForm();
					break;
				case "delete":
					$this->admin->deleteData($this->gpm->get("id"));
					$this->view->printDeleteFinish();
					break;
				default:
					$this->view->printActionRequired();
			}
		}
	}

}

?>