<?php

require_once("model/Sql.php");

Class Request {
	
	private $id;
	private $name;
	
	private $sql;
	private $req;
	
	public function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
		
		$this->sql = new Sql();
		
		$this->setRequest();
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getTable() {
		$table = Array();
		if ($this->id != 6) {
			$this->sql->query($this->req);
			for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
				$table[$i] = $this->sql->getResult()->fetch_assoc();
			}
		}
		else {
			$this->sql->multi_query($this->req);
			$j = 0;
			while ($this->sql->next_result()) {
				$result = $this->sql->store_result();
				for ($i = 0; $i < $this->result->num_rows; $i++) {
					$table[$j] = $this->result->fetch_assoc();
					$j++;
				}
				$table[$j] = Array("------------");
				$j++;
			}
		}
		
		return $table;
	}
	
	private function setRequest() {
		switch($this->id) {
			case 1:
				$this->req = "	SELECT Aname FROM Author a WHERE
								EXISTS (SELECT * FROM PublicationsAuthor pa, Publications p
								WHERE a.DBLP_KEY_AUTHOR=pa.DBLP_KEY_AUTHOR AND pa.DBLP_KEY=p.DBLP_KEY AND p.Year=2008) AND
								EXISTS (SELECT * FROM PublicationsAuthor pa, Publications p
								WHERE a.DBLP_KEY_AUTHOR=pa.DBLP_KEY_AUTHOR AND pa.DBLP_KEY=p.DBLP_KEY AND p.Year=2009) AND
								EXISTS (SELECT * FROM PublicationsAuthor pa, Publications p
								WHERE a.DBLP_KEY_AUTHOR=pa.DBLP_KEY_AUTHOR AND pa.DBLP_KEY=p.DBLP_KEY AND p.Year=2010) AND
								EXISTS (SELECT * FROM PublicationsAuthor pa, Publications p
								WHERE a.DBLP_KEY_AUTHOR=pa.DBLP_KEY_AUTHOR AND pa.DBLP_KEY=p.DBLP_KEY AND p.Year=2011)";
				break;
			case 2:
				$this->req = "	SELECT DISTINCT Aname FROM Author a, PublicationsAuthor pa,
								Publications p WHERE a.DBLP_KEY_AUTHOR=pa.DBLP_KEY_AUTHOR AND
								pa.DBLP_KEY=p.DBLP_KEY
								GROUP BY a.Aname,p.Year
								HAVING COUNT(*)> 1";
				break;
			case 3:
				$this->req = "  SELECT DISTINCT a.Aname FROM PublicationsAuthor pa,
								Publications p, Author a WHERE (a.Aname != '".$this->sql->real_escape_string($this->name)."') AND
								(p.DBLP_KEY = pa.DBLP_KEY)
								AND (pa.DBLP_KEY_AUTHOR = a.DBLP_KEY_AUTHOR)
								AND p.DBLP_KEY IN ( SELECT DISTINCT p.DBLP_KEY FROM
								PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP_KEY
								= pa.DBLP_KEY) AND (pa.DBLP_KEY_AUTHOR = a.DBLP_KEY_AUTHOR)
								AND a.DBLP_KEY_AUTHOR IN ( SELECT DISTINCT a.DBLP_KEY_AUTHOR
								FROM PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP_KEY
								= pa.DBLP_KEY) AND (pa.DBLP_KEY_AUTHOR = a.DBLP_KEY_AUTHOR)
								AND p.DBLP_KEY IN ( SELECT DISTINCT p.DBLP_KEY FROM
								PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP_KEY
								= pa.DBLP_KEY) AND (pa.DBLP_KEY_AUTHOR = a.DBLP_KEY_AUTHOR)
								AND (a.Aname = '".$this->sql->real_escape_string($this->name)."'))))";
				break;
			case 4:
				$this->req = "	SELECT * FROM Article a, Publications p WHERE a.DBLP_KEY_PUBL = p.DBLP_KEY AND NOT EXISTS(
								SELECT * FROM PublicationsAuthor pa WHERE
								pa.DBLP_KEY = a.DBLP_KEY_PUBL AND pa.DBLP_KEY_AUTHOR IN (
								SELECT pa2.DBLP_KEY_AUTHOR FROM Thesis t2, PublicationsAuthor pa2
								WHERE pa2.DBLP_KEY = t2.DBLP_KEY AND t2.DBLP_KEY IN (
								SELECT t.DBLP_KEY FROM Thesis t, PublicationsAuthor pa3
								WHERE t.DBLP_KEY = pa3.DBLP_KEY
								GROUP BY t.DBLP_KEY
								HAVING COUNT(*) = 1)))";
				break;
			case 5:
				$this->req = "	SELECT Aname, MAX(counted) FROM (
				SELECT a.Aname, COUNT(DISTINCT ar.Journal_name) AS counted
				FROM Article ar, PublicationsAuthor pa, Author a
				WHERE ar.DBLP_KEY_PUBL = pa.DBLP_KEY AND a.DBLP_KEY_AUTHOR = pa.DBLP_KEY_AUTHOR
				GROUP BY a.Aname) as counts";
				break;
			case 6:
				$this->req = "	SELECT Journal name , COUNT(*) FROM Article a
								WHERE a.Journal name IN (SELECT DISTINCT Journal name FROM
								Article ar WHERE Volume > AVG(Volume) GROUP BY ar.Journal name)
								GROUP BY a.Journal name;
								SELECT Journal name , AVG(COUNT(*)) FROM Article a, Pub-
								lications p WHERE a.Journal name IN (SELECT DISTINCT Jour-
								nal name FROM Article ar WHERE Volume > AVG(Volume) GROUP
								BY ar.Journal name) GROUP BY a.Journal name, p.Year;
								SELECT Journal name , AVG(COUNT(*))
								FROM Article a, PublicationAuthor pa
								WHERE ar.Journal name IN (SELECT DISTINCT Journal name FROM
								8
								Article ar WHERE Volume > AVG(Volume) GROUP BY ar.Journal name)
								GROUP BY a.DBLP_KEY USING a.DBLP_KEY=pa.DBLP_KEY;";
				SELECT Journal_name , COUNT(*) FROM Article a WHERE a.Journal_name IN (SELECT DISTINCT ar.Journal_name FROM Article ar GROUP BY ar.Journal_name HAVING Volume > AVG(Volume)) GROUP BY a.Journal_name;
				SELECT Journal_name , AVG(COUNT(*)) FROM Article a, Publications p WHERE a.Journal_name IN (SELECT DISTINCT ar.Journal_name FROM Article ar GROUP BY ar.Journal_name HAVING Volume > AVG(Volume)) GROUP BY a.Journal_name) GROUP BY a.Journal_name, p.Year;
				SELECT Journal_name , AVG(COUNT(*)) FROM Article a, PublicationAuthor pa WHERE ar.Journal_name IN (SELECT DISTINCT ar.Journal_name FROM Article ar GROUP BY ar.Journal_name HAVING Volume > AVG(Volume)) GROUP BY a.Journal_name) GROUP BY a.DBLP_KEY USING a.DBLP_KEY=pa.DBLP_KEY;
				
				SELECT Journal_name, AVG( counted )
				FROM (
				
				SELECT a.Journal_name, COUNT( * ) AS counted
				FROM Article a, Publications p
				WHERE a.DBLP_KEY_PUBL = p.DBLP_KEY
				GROUP BY a.Journal_name, p.Year
				) AS counts
				WHERE Journal_name IN (SELECT DISTINCT Journal_name FROM Article ar GROUP BY ar.Journal_name HAVING Volume > AVG(Volume))
				break;
		}
	}
	
}

?>