<?php

require_once("model/Request.php");

require_once("view/Template.php");

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
		$list[0] = $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°1");
		$list[1] = $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°2");
		
		$list[2] = $this->getHtml()->makeGetForm(Array(Array("Author", "name", "text", ""),
				Array("", "page", "hidden", "request"),
				Array("", "id", "hidden", "3")), "Requête n°3");
		$list[3] = $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°4");
		$list[4] = $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°5");
		$list[5] = $this->getHtml()->makeLink("index.php?page=request&id=1", "Requête n°6");
		
		$this->page .= $this->getHtml()->makeList($list);
	}
	
}

?>