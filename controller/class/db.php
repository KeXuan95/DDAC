<?php

class DB {
	private static $_instance = null;
	public $_pdo, $_query, $_error = false, $_result, $_count = 0, $_lastID;
	
	//db connection
	private function __construct() {
		$conn = 'mysql:host='.Config::get('db_host').';port='.Config::get('db_port').';dbname='.Config::get('db_name');
		$username = Config::get('db_username');
		$password = Config::get('db_password');
		$opt = [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_PERSISTENT => true
		];

		try {
			$this->_pdo = new PDO($conn,$username,$password,$opt);
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}
	
	//check if connection has been established and return the DB connection
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	//SQL querying
	private function query($sql, $params = array()) {
		$this->_error = false; //reset error in case previous queries return error
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x=1;
			if(count($params)) {
				foreach($params as $param) {
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}
			
			if($this->_query->execute()) {
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		
		return $this;
	}
	
	//prepare and execute statement
	public function execute($sql) {
		$this->_query = $this->_pdo->prepare($sql);
		
		if($this->_query->execute()) {
			$this->_lastID = $this->_pdo->lastInsertID();
			$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();
		} else {
			$this->_error = true;
		}
	}
	
	//function for simplified SQL operation
	private function action($action, $table, $where = array()) {
		if(count($where)=== 3) {
			$operators = array('=','>','<','>=','<=');
			
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?"; //? is binded value
				
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}
	
	//simplified SQL read query
	public function select($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}
	
	//simplified SQL delete query
	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}
	
	//simplified SQL insert query
	public function insert($table, $fields=array()) {
		if(count($fields)) {
			$keys = array_keys($fields);
			
			$sql = "INSERT INTO {$table} (".implode(', ', $keys).") VALUES (".implode(',', array_fill(0, count($fields), '?')).")";
			
			if(!$this->query($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}
	
	//simplified SQL update query
	public function update($table, $field, $val, $fields=array()) {
		$set = '';
		$x = 1;
		
		foreach($fields as $name=>$value) {
			$set .= "{$name} = ?";
			if($x<count($fields)) {
				$set .= ', ';
			}
			$x++;
		}
		
		if(!is_numeric($val)) {
			$val="'".$val."'";
		}
		
		$sql = "UPDATE {$table} SET {$set} WHERE {$field} = {$val}";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
	
	//return result in array
	public function result() {
		return $this->_result;
	}
	
	//return first value of the in the array of result
	public function first() {
		return $this->result()[0];
	}
	
	//return error
	public function error() {
		return $this->_error;
	}
	
	//return number of row
	public function count() {
		return $this->_count;
	}
	
	//return last inserted ID
	public function lastID() {
		return $this->_lastID;
	}
}