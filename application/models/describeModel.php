<?php

class describeModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function getDetails($journal = DEFAULT_JOURNAL, $id) {

		$dbh = $this->db->connect($journal);
		if(is_null($dbh))return null;
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE . ' WHERE journal=:journal AND id=:id');
		$sth->bindParam(':journal', $journal);
		$sth->bindParam(':id', $id);
		
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;
		return $result;
	}

	public function getDetailsByName($name = '', $fetch = 'FELLOW') {

		$dbh = $this->db->connect(GENERAL_DB_NAME);
		if(is_null($dbh))return null;
		
		$bindName = preg_replace('/\_/', ' ', $name);
		$sth = $dbh->prepare('SELECT * FROM ' . constant($fetch . '_TABLE') . ' WHERE name=:name');
		$sth->bindParam(':name', $bindName);
		
		$sth->execute();

		$result = $sth->fetch(PDO::FETCH_OBJ);
		$dbh = null;
		return $result;
	}
	public function listCurrentToc($journal = DEFAULT_JOURNAL, $feature) {
		
		$dbh = $this->db->connect($journal);
		
		$data = array();
		
		$sth = $dbh->prepare('SELECT DISTINCT volume, issue FROM ' . METADATA_TABLE .' ORDER BY volume DESC, issue DESC LIMIT 1');
		$sth->execute();
		$data = $sth->fetch(PDO::FETCH_ASSOC);
		
		
		$sth = $dbh->prepare('SELECT * FROM ' . METADATA_TABLE .' WHERE volume = ' . $data['volume'] . ' AND issue = ' . $data['issue'] . ' AND feature = \'' . $feature . '\'');
			$sth->execute();
		
		$details = [];
		$details = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $details;
	}
}

?>
