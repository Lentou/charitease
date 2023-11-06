<?php

namespace lib;

use \PDO;
use \PDOException;

class dbpdo {

	public $pdo;
	private $error;

	public function __construct(
		private $dbhost = 'localhost', 
		private $dbname = 'charitease', 
		private $dbuser = 'root',
		private $dbpass = ''
	) {
		$this->connect();
	}

	public function prep_query($query) {
		return $this->getpdo()->prepare($query);
	}

	public function getpdo() : PDO {
		return $this->pdo;
	}

	public function connect() {
		if (!$this->pdo) {
			$dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->dbhost . ';charset=utf8';
			$user = $this->dbuser;
			$password = $this->dbpass;

			try {
                $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_PERSISTENT => true]);
				return true;
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				die($this->error);
				return false;
			}
		} else {
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			return true;
		}
	}

	public function table_exists($table_name) : bool {
		$stmt = $this->prep_query('SHOW TABLES LIKE ?');
		$stmt->execute(array($table_name));
		return $stmt->rowCount() > 0;
	}

	public function execute($query, $values = null) {
		if ($values == null) {
			$values = array();
		} else if(!is_array($values)) {
			$values = array($values);
		}
		$stmt = $this->prep_query($query);
		$stmt->execute($values);
		return $stmt;
	}

	public function fetch($query, $values = null) {
		if ($values == null) {
			$values = array();
		} else if(!is_array($values)) {
			$values = array($values);
		}
		$stmt = $this->execute($query, $values);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function fetchAll($query, $values = null, $key = null) {
		if ($values == null) {
			$values = array();
		} else if(!is_array($values)) {
			$values = array($values);
		}
		$stmt = $this->execute($query, $values);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Allows the user to retrieve results using a
		// column from the results as a key for the array
		if($key != null && $results[0][$key]){
			$keyed_results = array();
			foreach($results as $result){
				$keyed_results[$result[$key]] = $result;
			}
			$results = $keyed_results;
		}
		return $results;
	}

	public function lastInsertId() {
		return $this->getpdo()->lastInsertId();
	}


}


