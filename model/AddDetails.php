<?php

Class AddDetails {

	public function makeAddRequest($type, $title, $url, $year, $publisher, $isbn, $editor_name, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		switch($type) {
			case "article":
				$this->sql = "BEGIN; ";
				$this->sql .= "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql .= "INSERT INTO article (DBLP_KEY_PUBL, volume, number, pages, journal_name, journal_year, editor_name) VALUES (LAST_INSERT_ID(), '".$volume."', '".$number."', '".$pages."', '".$journal_name."', '".$journal_year."', '".$editor_name."'); ";
				$this->sql .= "COMMIT;";
				break;
			case "book":
				$this->sql = "BEGIN; ";
				$this->sql .= "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql .= "INSERT INTO book (DBLP_KEY, isbn, editor_name) VALUES (LAST_INSERT_ID(), '".$isbn."', '".$editor_name."'); ";
				$this->sql .= "COMMIT;";
				break;
			case "thesis":
				$this->sql = "BEGIN; ";
				$this->sql .= "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql .= "INSERT INTO thesis (DBLP_KEY, masterifTrue, isbnPhd) VALUES (LAST_INSERT_ID(), '".$masterifTrue."', '".$isbnPhd."'); ";
				$this->sql .= "COMMIT;";
				break;
		}
		return $this->sql;
	}

}

?>