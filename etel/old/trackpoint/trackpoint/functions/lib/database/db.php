<?php
/**
* This file only has the base Db class in it. This sets up class variables and basic constructs only.
* Each subclass (different database type) overrides most functions (except logging) and handles database specific instances.
*
* @version     $Id: db.php,v 1.8 2005/05/02 03:39:20 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package Library
* @subpackage Db
* @filesource
*/

/**
* Make sure our error reporting levels are set properly.
*/
if (!defined('ERROR_FATAL')) define('ERROR_FATAL', E_USER_ERROR);
if (!defined('ERROR_ERROR')) define('ERROR_ERROR', E_USER_WARNING);
if (!defined('ERROR_WARNING')) define('ERROR_WARNING', E_USER_NOTICE);

/**
* This is the base class for the database system.
* Almost all methods are overwritten in subclasses, except for logging and for the 'FetchOne' method.
*
* @package Library
* @subpackage Db
*/
class Db {

	/**
	* @var connection   The global database connection.
	* @see Connect
	*/
	var $connection = null;


	/**
	* @var _error       Where any database errors are stored.
	* @see GetError
	* @see SetError
	* @access private
	*/
	var $_Error = null;


	/**
	* @var _ErrorLevel What type of error this is.
	* @see GetError
	* @see SetError
	* @access private
	*/
	var $_ErrorLevel = ERROR_FATAL;


	/**
	* @var QueryLog Determines whether a query will be logged or not. If it's false or null it won't log, if it's a filename (or path to a file) it will log.
	* @see LogQuery
	*/
	var $QueryLog = null;


	/**
	* Constructor
	*
	* Sets up the database connection.
	* Since this is the parent class the others inherit from, this returns null when called directly.
	*
	* @return null
	*/
	function Db() {
		return null;
	}


	/**
	* Connect
	*
	* This function will connect to the database based on the details passed in.
	* Since this is the parent class the others inherit from, this returns false when called directly.
	*
	* @return false
	*/
	function Connect() {
		$this->SetError('Cannot call base class method Connect directly');
		return false;
	}


	/**
	* Disconnect
	*
	* This function will disconnect from the database resource passed in.
	* Since this is the parent class the others inherit from, this returns false when called directly.
	*
	* @return false
	*/
	function Disconnect() {
		$this->SetError('Cannot call base class method Disconnect directly');
		return false;
	}


	/**
	* SetError
	*
	* Stores the error in the _error var for retrieval.
	* @param string The error you wish to store for retrieval.
	* @param string The error level you wish to store.
	*
	* @access private
	* @return void
	*/
	function SetError($error='', $errorlevel=ERROR_FATAL) {
		$this->_Error = $error;
		$this->_ErrorLevel = $errorlevel;
	}


	/**
	* GetError
	*
	* This simply returns the $_Error var and it's error level.
	*
	* @access public
	* @return array Returns the error and it's error level.
	* @see SetError
	*/
	function GetError() {
		return array($this->_Error, $this->_ErrorLevel);
	}


	/**
	* Query
	*
	* This runs a query against the database and returns the resource result.
	*
	* @access public
	* @return false Will always return false when called in the base class.
	*/
	function Query() {
		$this->SetError('Cannot call base class method Query directly');
		return false;
	}


	/**
	* Fetch
	*
	* This fetches a result from the result set passed in.
	*
	* @access public
	* @return false Will always return false when called in the base class.
	*/
	function Fetch() {
		$this->SetError('Cannot call base class method Fetch directly');
		return false;
	}


	/**
	* FetchOne
	*
	* This fetches one column from the result set passed in.
	*
	* @access public
	* @return mixed Returns false if the result set is not present, or if you don't pass in an item to fetch. If the item doesn't exist in the result, it will also return false. Otherwise, returns the item value.
	*
	* @see Fetch
	*/
	function FetchOne($result=null, $item=null) {
		if (is_null($result) or is_null($item)) return false;
		$row = $this->Fetch($result);
		if (!$row) return false;
		if (!isset($row[$item])) return false;
		return stripslashes($row[$item]);
	}

	/**
	* Update
	*
	* This function will run a database update. Returns whether the update worked or not.
	*
	* @access public
	* @return false Will always return false when called in the base class.
	*/
	function Update() {
		$this->SetError('Cannot call base class method Update directly');
		return false;
	}


	/**
	* LogQuery
	*
	* This will log all queries if QueryLog is not false or null.
	*
	* @see QueryLog
	*
	* @return boolean Returns whether the query is logged or not. Will return false if there is no query or if the QueryLog variable is set to false or null.
	*/
	function LogQuery($query='') {
		if (!$query) return false;
		if (!$this->QueryLog || is_null($this->QueryLog)) return false;
		if (!$fp = fopen($this->QueryLog, 'a+')) {
			return false;
		}
		$query .= ";\n";
		fputs($fp, $query, strlen($query));
		fclose($fp);
		return true;
	}
}
?>
