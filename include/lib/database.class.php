<?php
/*
 * Mysql database class - only one connection alowed
 */
require_once (__DIR__ . "/session.class.php");
class Database extends Session {
	protected $_connection;
	private static $_instance; // The single instance
	private $_host;
	private $_username;
	private $_password;
	private $_database;
	/*
	 * Get an instance of the Database
	 * @return Instance
	 */
	public static function getInstance() {
		if (! self::$_instance) { // If no instance then make one
			self::$_instance = self ();
		}
		return self::$_instance;
	}
	// Constructor
	function __construct() {
		parent::__construct ();
		$this->_host = MASTER_DB_HOST;
		$this->_username = MASTER_DB_USER;
		$this->_password =MASTER_DB_PASSWORD;
		$this->_database = $this->_get ( 'cmpDtSrc' );
		$this->_connection = mysqli_connect ( $this->_host, $this->_username, $this->_password, $this->_database );
		
		// Error handling
		if (mysqli_connect_error ()) {
			trigger_error ( "Failed to conencto to MySQL: " . mysqli_connect_error (), E_USER_ERROR );
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() {
	}
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
?>