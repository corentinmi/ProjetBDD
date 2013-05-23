<?php

require_once("model/Sql.php");

Class Request {
	
	private $id;
	private $name;
	
	private $sql;
	private $req;
	
	public function __construct($id, $name) {
		set_time_limit(3600);
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
		$this->sql->query($this->req);
		for ($i = 0; $i < $this->sql->getResult()->num_rows; $i++) {
			$table[$i] = $this->sql->getResult()->fetch_assoc();
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
				$this->req = "	SELECT mix.Journal_name, maxim, aver, amount, aperyear, avgauthperart FROM
								(SELECT Journal_name, AVG(aperart) AS avgauthperart FROM (SELECT Journal_name, COUNT(*) AS aperart FROM Article a, PublicationsAuthor pa WHERE a.DBLP_KEY_PUBL = pa.DBLP_KEY GROUP BY a.Journal_name, pa.DBLP_KEY) as dinga) as donga, 
								(SELECT Journal_name, AVG(yart) AS aperyear FROM (SELECT Journal_name, COUNT(*) as yart FROM Article art, Publications pap WHERE pap.DBLP_KEY = art.DBLP_KEY_PUBL GROUP BY Journal_name, Year) as derp) as herp,
								(SELECT Journal_name, MAX(Volume) AS maxim, COUNT(*) AS amount FROM Article GROUP BY Journal_name) as mix,
								(SELECT AVG(maxim) as aver FROM (SELECT Journal_name, MAX(Volume) AS
								maxim FROM Article GROUP BY Journal_name) as mx) as av WHERE mix.maxim > av.aver AND herp.Journal_name = mix.Journal_name AND donga.Journal_name = mix.Journal_name;";
				break;
		}
	}
	
}

?>