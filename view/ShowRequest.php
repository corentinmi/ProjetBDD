<?php

require_once("model/Request.php");

Class ShowRequest extends Template {
	
	private $request;
	
	private $page = "";
	
	public function __construct($request) {
		parent::__construct();
		$this->request = $request;
		if ($this->request->getID())
			$this->printResult();
		else
			$this->showSelectForm();
	}
	
	private function printResult() {
		$this->page .= $this->getHtml()->makeTable($this->request->getTable());
	}
	
	public function printPage() {
		$this->printHeader();
		
		echo $this->page;
		
		$this->printSideMenu();
		$this->printFooter();
		$this->finalize();
	}
	
	private function showSelectForm() {
		$this->page .= $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°1");
		$this->page .= $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°2");
		
		$this->page .= $this->getHtml()->makeGetForm(Array(Array("Author", "name", "text", ""),
				Array("", "page", "hidden", "request"),
				Array("", "id", "hidden", "3")), "Requête n°2");
		$this->page .= $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°4");
		$this->page .= $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°5");
		$this->page .= $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°6");
	}
	
}

?>