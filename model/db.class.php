<?php

/**
 * Abstract DB Class - can be instantiatiated by static getInstance method
 * 
 * @author Chima Ijeoma
 *
 **/

class DBConnectionException extends Exception {
}

class DBQueryExecutionException extends Exception {
}

class db extends ADOConnection {

	// Store the single instance of DB()
	private static $instance;

	private $server = __DB_SERVER;
	private $dbname = __DB_NAME;
	private $username = __DB_USER;
	public $password = __DB_PASSWORD;
	private $_handle = null;

	private $new_db = null;

	public static function getInstance() {

		if (!self::$instance) {
			self::$instance = new db();
		}
		return self::$instance;
	}

	private function __construct() {
		try {
			$this -> new_db = NewADOConnection("mysql");

			$this -> new_db -> debug = false;

			$this -> new_db -> FetchMode = ADODB_FETCH_ASSOC;

			$this -> _handle = $this -> new_db -> Connect($this -> server, $this -> username, $this -> password, $this -> dbname);

		} catch (DBConnectionException $ex) {
			echo("The Connection to server: " . $this -> server . " failed. " . $ex -> getMessage());
		}
		return $this -> _handle;
	}

	/**
	 * Clone is set to private to stop cloning
	 */
	private function __clone() {
	}

	/**
	 * @GetResult executes sql returning Recordset array
	 * @param string $sql
	 */

	public function getResult($sql = '') {

		try {

			$result = $this -> new_db -> Execute($sql) or die("Error in query: $sql. ");

			if ($result) {
				return $result;
			}
		} catch (DBQueryExecutionException $ex) {

			echo("Unable to run query: " . $sql . ": " . $ex -> getMessage());
			exit();
		}
	}

	/*
	 * Execute the sql query
	 */

	public function Execute($sql, $params) {

		try {
			$result = $this -> new_db -> Execute($sql, $params);

			if ($result) {
				return $result;
			} else {
				Throw new Exception("Error in executing query" . $ex -> getMessage());
			}

		} catch( Exception $ex ) {
			Throw new Exception($ex -> getCode());
		}
	}

	function sql_numrows($query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$result = @mysql_num_rows($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_affectedrows() {
		if ($this -> db_connect_id) {
			$result = @mysql_affected_rows($this -> db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_numfields($query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$result = @mysql_num_fields($query_id);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fieldname($offset, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fieldtype($offset, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		} else {
			return false;
		}
	}

	function sql_fetchrow($query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$this -> row[$query_id] = @mysql_fetch_array($query_id);
			return $this -> row[$query_id];
		} else {
			return false;
		}
	}

	public function sql_fetchrowset($query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			unset($this -> rowset[$query_id]);
			unset($this -> row[$query_id]);
			while ($this -> rowset[$query_id] = @mysql_fetch_array($query_id)) {
				$result[] = $this -> rowset[$query_id];
			}
			return $result;
		} else {
			return false;
		}
	}

	public function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			if ($rownum > -1) {
				$result = @mysql_result($query_id, $rownum, $field);
			} else {
				if (empty($this -> row[$query_id]) && empty($this -> rowset[$query_id])) {
					if ($this -> sql_fetchrow()) {
						$result = $this -> row[$query_id][$field];
					}
				} else {
					if ($this -> rowset[$query_id]) {
						$result = $this -> rowset[$query_id][$field];
					} else if ($this -> row[$query_id]) {
						$result = $this -> row[$query_id][$field];
					}
				}
			}
			return $result;
		} else {
			return false;
		}
	}

	public function sql_rowseek($rownum, $query_id = 0) {
		if (!$query_id) {
			$query_id = $this -> query_result;
		}
		if ($query_id) {
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else {
			return false;
		}
	}

	public function sql_nextid() {
		if ($this -> db_connect_id) {
			$result = @mysql_insert_id($this -> db_connect_id);
			return $result;
		} else {
			return false;
		}
	}

	public function handle() {
		return $this -> _handle;
	}

}
?>