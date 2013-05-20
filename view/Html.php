<?php

Class Html {
	
	public function makeLink($target, $name) {
		$html = "<a href='".$target."'>".$name."</a>";
		return $html;
	}
	
	public function makeForm($fields, $validate) {
		$html = "<form method=POST><table>";
		foreach($fields as $item) {
			if ($item[2] != "select") {
				$html .= "<tr><td>".$item[0]."</td><td><input type='".$item[2]."' value='".$item[3]."' name='".$item[1]."' /></td></tr>";
			}
			else {
				$html .= "<tr><td>".$item[0]."</td>";
				$html .= "<td><select size=5 name='".$item[1]."' multiple='yes' >";
				foreach ($item[3] as $id => $name) {
					$html .= "<option value='".$id."'>".$name."</option>";
				}
				$html .= "</select></td></tr>";
			}
		}
		$html .= "<tr><td colspan=2><input type='submit' value='".$validate."' /></td></tr>";
		$html .= "</table></form>";
		return $html;
	}
	
	public function makeGetForm($fields, $validate) {
		$html = "<form method=GET><table>";
		foreach($fields as $item) {
			if ($item[2] != "select") {
				$html .= "<tr><td>".$item[0]."</td><td><input type='".$item[2]."' value='".$item[3]."' name='".$item[1]."' /></td></tr>";
			}
			else {
				$html .= "<tr><td>".$item[0]."</td>";
				$html .= "<td><select name='".$item[1]."' >";
				foreach ($item[3] as $id => $name) {
					$html .= "<option value='".$id."'>".$name."</option>";
				}
				$html .= "</select></td></tr>";
			}
		}
		$html .= "<tr><td colspan=2><input type='submit' value='".$validate."' /></td></tr>";
		$html .= "</table></form>";
		return $html;
	}
	
	public function makeTargetForm($fields, $validate, $target) {
		$html = "<form method=POST action='".$target."'><table>";
		foreach($fields as $item) {
			$html .= "<tr><td>".$item[0]."</td><td><input type='".$item[2]."' value='".$item[3]."' name='".$item[1]."' /></td></tr>";
		}
		$html .= "<tr><td colspan=2><input type='submit' value='".$validate."' /></td></tr>";
		$html .= "</table></form>";
		return $html;
	}
	
	public function makeGetLink($target, $get, $name) {
		$html = "<a href='".$target."?";
		foreach ($get as $field => $value) {
			$html .= $field."=".$value."&";
		}
		$html .= "'>".$name."</a>";
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