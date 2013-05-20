<?php

Class AddDetails {

	public function makeAddRequest($type, $title, $url, $year, $publisher, $isbn, $volume, $number, $pages, $journal_name, $journal_year, $masterifTrue, $isbnPhd) {
		switch($type) {
			case "article":
				$this->sql = Array();
				$this->sql[0] = "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql[1] = "INSERT INTO article (DBLP_KEY_PUBL, volume, number, pages, journal_name, journal_year) VALUES (LAST_INSERT_ID(), '".$volume."', '".$number."', '".$pages."', '".$journal_name."', '".$journal_year."'); ";
				break;
			case "book":
				$this->sql = Array();
				$this->sql[0] = "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql[1] = "INSERT INTO book (DBLP_KEY, isbn) VALUES (LAST_INSERT_ID(), '".$isbn."'); ";
				break;
			case "thesis":
				$this->sql = Array();
				$this->sql[0] = "INSERT INTO publications (title, url, publisher, year) VALUES ('".$title."', '".$url."', '".$publisher."', '".$year."'); ";
				$this->sql[1] = "INSERT INTO thesis (DBLP_KEY, masterifTrue, isbnPhd) VALUES (LAST_INSERT_ID(), '".$masterifTrue."', '".$isbnPhd."'); ";
				break;
		}
		return $this->sql;
	}

}

?>