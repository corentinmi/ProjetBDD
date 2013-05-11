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
	
	public function makeTable($content) {
		$html = "<table>";
		foreach ($content as $line) {
			$html .= "<tr>";
			foreach ($line as $item) {
				$html .= "<td>".$item."</td>";
			}
			$html .= "</tr>";
		}
		$html .= "</table>";
		return $html;
	}
	
}

?>