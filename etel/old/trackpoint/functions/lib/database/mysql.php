<?php
/**
* This file handles mysql database connections, queries, procedures etc.
* Most functions are overridden from the base object.
*
* @version     $Id: mysql.php,v 1.13 2005/08/09 04:36:15 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package Db
* @subpackage MySQLDb
* @filesource
*/

/**
* Include the base database class.
*/
require(dirname(__FILE__) . '/db.php');

/**
* This is the class for the MySQL database system.
*
* @package Db
* @subpackage MySQLDb
*/
class MySQLDb extends Db {

	/**
	* Constructor
	*
	* Sets up the database connection.
	* Can pass in the hostname, username, password and database name if you want to.
	* If you don't it will set up the base class, then you'll have to call Connect yourself.
	*
	* @param servername Name of the server to connect to.
	* @param username Username to connect to the server with.
	* @param password Password to connect with.
	* @param databasename Database name to connect to.
	*
	* @return mixed Returns false if no connection can be made - the error can be fetched by the Error() method. Returns the connection result if it can be made. Will return Null if you don't pass in the connection details.
	*
	* @see Connect
	* @see GetError
	*/
	function MySQLDb($hostname='', $username='', $password='', $databasename='') {
		if ($hostname && $username && $databasename) {
			$connection = $this->Connect($hostname, $username, $password, $databasename);
			return $connection;
		}
		return null;
	}

	/**
	* Connect
	*
	* This function will connect to the database based on the details passed in.
	*
	* @param servername Name of the server to connect to.
	* @param username Username to connect to the server with.
	* @param password Password to connect with.
	* @param databasename Database name to connect to.
	* @return mixed Will return the connection if it's successful. Otherwise returns false.
	*
	* @see Error
	*/
	function Connect($hostname='', $username='', $password='', $databasename='') {
		if ($hostname == '') {
			$this->SetError('No server name to connect to');
			return false;
		}

		if ($username == '') {
			$this->SetError('No username name to connect to server ' . $hostname . ' with');
			return false;
		}

		if ($databasename == '') {
			$this->SetError('No database name to connect to');
			return false;
		}

		if (!$connection_result = @mysql_connect($hostname, $username, $password)) {
			$this->SetError(mysql_error());
			return false;
		}
		$this->connection = &$connection_result;
		
		if (!$db_result = @mysql_select_db($databasename, $connection_result)) {
			$this->SetError('Unable to select database \'' . $databasename . '\': ' . mysql_error());
			return false;
		}
		return $this->connection;
	}


	/**
	* Disconnect
	*
	* This function will disconnect from the database handler passed in.
	*
	* @return mixed Will return the connection if it's successful. Otherwise returns false.
	*/
	function Disconnect($resource=null) {
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource ' . $resource . ' is not really a resource');
			return false;
		}
		$close_success = mysql_close($resource);
		return $close_success;
	}

	/**
	* Query
	*
	* This function will run a query against the database and return the result of the query.
	*
	* @return mixed Returns false if the query is empty or if there is no result. Otherwise returns the result of the query.
	*/
	function Query($query='') {
		$this->LogQuery($query);
		if (!$query) {
			$this->SetError('Query passed in is empty');
			return false;
		}
		if (!$this->connection) {
			$this->SetError('No valid connection');
			return false;
		}
		$result = mysql_query($query);
		if (!$result) {
			$this->SetError(mysql_error());
			return false;
		}
		return $result;
	}


	/**
	* Fetch
	*
	* This function will fetch a result from the result set passed in.
	*
	* @return mixed Returns false if the result is empty. Otherwise returns the next result.
	*/
	function Fetch($resource=null) {
		if (is_null($resource)) {
			$this->SetError('Resource is a null object');
			return false;
		}
		if (!is_resource($resource)) {
			$this->SetError('Resource ' . $resource . ' is not really a resource');
			return false;
		}
		return stripslashes_array(mysql_fetch_assoc($resource));
	}


	/**
	* Update
	* Runs an update query against the database.
	*
	* @param Query Query to run
	*
	* @return boolean Returns true if the query is successful. Returns false if there is no query, no valid connection or the query failed.
	*/
	function Update($query='') {
		if (!$query) {
			$this->SetError('Query passed in is empty');
			return false;
		}
		if (!$this->connection) {
			$this->SetError('No valid connection');
			return false;
		}
		$result = $this->Query($query);
		if (!$result) {
			$this->SetError(mysql_error());
			return false;
		}
		return true;
	}

	function NextId($sequencename=false) {
		if (!$sequencename) return false;
		$query = "UPDATE " . $sequencename . " SET id=LAST_INSERT_ID(id+1)";
		$result = $this->Query($query);
		if (!$result) return false;
		return mysql_insert_id();
	}

	function AddLimit($offset=0, $numtofetch=0) {
		if ($offset < 0) $offset = 0;
		if ($numtofetch <= 0) $numtofetch = 10;
		$query = ' LIMIT ' . $offset . ', ' . $numtofetch;
		return $query;
	}
}
?>
