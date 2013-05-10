<?php

Class Html {
	
	public function makeLink($target, $name) {
		$html = "<a href='".$target."'>".$name."</a>";
		return $html;
	}
	
	public function makeList($content) {
		$html = "<ul>";
		foreach ($content as $item) {
			$html .= "<li>$item</li>";
		}
		$html .= "</ul>";
		return $html;
	}
	
}

?>