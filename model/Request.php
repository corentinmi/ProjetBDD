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
				$this->req = "	SELECT Aname FROM Author WHERE
								EXISTS (SELECT PublicationsAuthor pa, Publications p
								WHERE pa.DBLP KEY=p.DBLP KEY AND p.Year=2008) AND
								EXISTS (SELECT PublicationsAuthor pa, Publications p
								WHERE pa.DBLP KEY=p.DBLP KEY AND p.Year=2009) AND
								EXISTS (SELECT PublicationsAuthor pa, Publications p
								WHERE pa.DBLP KEY=p.DBLP KEY AND p.Year=2010) AND
								EXISTS (SELECT PublicationsAuthor pa, Publications p
								WHERE pa.DBLP KEY=p.DBLP KEY AND p.Year=2011)";
				break;
			case 2:
				$this->req = "	SELECT DISTINCT Aname FROM Author a PublicationsAuthor pa
								Publications p WHERE
								COUNT(*)> 1 GROUP BY a.Aname,p.Year
								USING a.DBLP KEY AUTHOR=pa.DBLP KEY AUTHOR AND
								pa.DBLP KEY=p.DBLP KEY";
				break;
			case 3:
				$this->req = "  SELECT DISTINCT a.Aname FROM PublicationsAuthor pa,
								Publications p, Author a WHERE (p.DBLP KEY = pa.DBLP KEY)
								AND (pa.DBLP KEY AUTHOR = a.DBLP KEY AUTHOR)
								AND p.DBLP KEY IN ( SELECT DISTINCT p.DBLP KEY FROM
								PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP KEY
								= pa.DBLP KEY) AND (pa.DBLP KEY AUTHOR = a.DBLP KEY AUTHOR)
								AND a.DBLP KEY AUTHOR IN ( SELECT DISTINCT a.DBLP KEY AUTHOR
								FROM PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP KEY
								= pa.DBLP KEY) AND (pa.DBLP KEY AUTHOR = a.DBLP KEY AUTHOR)
								AND p.DBLP KEY IN ( SELECT DISTINCT p.DBLP KEY FROM
								PublicationsAuthor pa, Publications p, Author a WHERE (p.DBLP KEY
								= pa.DBLP KEY) AND (pa.DBLP KEY AUTHOR = a.DBLP KEY AUTHOR)
								AND (a.Aname = '".$this->sql->real_escape_string($this->name)."'))))";
				break;
			case 4:
				$this->req = "	SELECT * FROM Article a WHERE NOT EXISTS(
								SELECT * FROM PublicationsAuthor pa WHERE
								pa.DBLP KEY = a.DBLP KEY AND pa.DBLP KEY AUTHOR IN (
								SELECT pa2.DBLP KEY AUTHOR FROM Thesis t2, PublicationsAuthor pa2
								WHERE pa2.DBLP KEY = t2.DBLP KEY AND t2.DBLP KEY IN (
								SELECT t.DBLP KEY FROM Thesis t, PublicationsAuthor pa3
								WHERE COUNT(*) = 1 GROUP BY t.DBLP KEY
								USING t.DBLP KEY = pa3.DBLP KEY)))";
				break;
			case 5:
				$this->req = "	SELECT a.Aname FROM Author a, Article ar,PublicationAuthor pa
								WHERE
								COUNT (DISTINCT Journal)=MAX(COUNT(DISTINCT Journal))
								GROUP BY a.AName
								USING pa.DBLP KEY=p.DBLP KEY AND
								a.DBLP KEY AUTHOR=p.DBLP KEY AUTHOR";
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
								GROUP BY a.DBLP KEY USING a.DBLP KEY=pa.DBLP KEY;";
				break;
		}
	}
	
}

?>