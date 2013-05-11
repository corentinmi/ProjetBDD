<?php

Class PageSelector {
	
	private $gpm;
	private $pageName;
	
	public function __construct($gpm) {
		$this->gpm = $gpm;
		$this->selectPage();
	}
	
	private function selectPage() {
		$this->pageName = $this->gpm->get("page");
		if ($this->pageName) {
			if (file_exists("controller/".$this->pageName."Ctrl.php")) {
				require_once("controller/".$this->pageName."Ctrl.php");
				$className = $this->pageName . "Ctrl";
				$pageCtrl = new $className($this->gpm);
			}
			else {
				$this->pageName = "Home";
				require_once("controller/".$this->pageName."Ctrl.php");
				$className = $this->pageName . "Ctrl";
				$pageCtrl = new $className($this->gpm);
			}
		}
		else {
			$this->pageName = "Home";
			require_once("controller/".$this->pageName."Ctrl.php");
			$className = $this->pageName . "Ctrl";
			$pageCtrl = new $className($this->gpm);
		}
	}
	
}

?>