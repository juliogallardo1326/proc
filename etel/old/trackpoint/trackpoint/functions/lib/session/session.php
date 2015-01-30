<?php
/**
* This file only has the Session class in it.
* The session class handles reading, writing, garbage collection, getting, setting of session data.
* All session related work goes on in the database to stop the temp directory from filling up.
* It checks whether the product is set up before using the database.
*
* @version     $Id: session.php,v 1.10 2005/08/09 04:36:15 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package Library
* @subpackage Session
* @filesource
*/

/**
* This is the class for the session system.
*
* @package Library
* @subpackage Session
*/
class Session {

	/**
	* var DBConnection Class variable for the database connection.
	*
	* @see _open
	*/
	var $DbConnection = false;

	/**
	* var SessionName Easily change the name of the session.
	*
	* @see Session
	*/
	var $SessionName = 'Session';

	/**
	* var _ExternalVars Where user information and external data is remembered.
	*
	* @see Set
	* @see Get
	*/
	var $_ExternalVars = array();

	/**
	* Constructor
	* Sets the session name if it's passed in.
	*
	* @param SessionName Name of the session if it's different to the class variable.
	*
	* @see SessionName
	*
	* @return true
	*/
	function Session($SessionName='') {
		if ($SessionName) $this->SessionName = $SessionName;
		return true;
	}

	/**
	* _open
	* Checks the database connection is valid, if it's not it tries to open it up.
	*
	* @see GetDatabase
	* @see DbConnection
	*
	* @return boolean Returns false if it can't connect to the database, otherwise true.
	*/
	function _open() {
		if (!$this->DbConnection) {
			$db = &GetDatabase();
			if (!$db) return false;
			$this->DbConnection = &$db;
		}
		return true;
	}

	/**
	* _close
	* Does nothing.
	*
	* @return void
	*/
	function _close() {
		return true;
	}

	/**
	* _read
	* Gets the session data from the database based on the sessionid passed in.
	*
	* @param sessionid Sessionid to get from the database.
	*
	* @see DBConnection
	*
	* @return string Returns an empty string if there is no row, otherwise returns a serialized 'object'.
	*/
	function _read($sessionid=false) {
		$qry = "SELECT * FROM " . TABLEPREFIX . "sessions WHERE sessionid='" . addslashes($sessionid) . "'";
		$result = $this->DbConnection->Query($qry);
		if (!$result) return '';
		$row = $this->DbConnection->Fetch($result);
		if (empty($row)) return '';
		return stripslashes($row['sessiondata']);
	}

	/**
	* _write
	* Sets the session data in the database based on the sessionid and data passed in.
	* Checks whether the session already exists or not and either creates a new one or updates the old one.
	*
	* @param sessionid Sessionid to get from the database.
	* @param data Serialized 'object' of the data to store.
	*
	* @see DBConnection
	*
	* @return boolean Returns true if it was saved ok, otherwise false.
	*/
	function _write($sessionid=false, $data=false) {
		$qry = "SELECT COUNT(sessionid) AS count FROM " . TABLEPREFIX . "sessions WHERE sessionid='" . addslashes($sessionid) . "'";
		$result = $this->DbConnection->Query($qry);
		$row = $this->DbConnection->Fetch($result);
		if ($row['count'] > 0) {
			$qry = "UPDATE " . TABLEPREFIX . "sessions SET sessiontime=" . time() . ", sessiondata='" . addslashes($data) . "' WHERE sessionid='" . addslashes($sessionid) . "'";
			$result = $this->DbConnection->Query($qry);
			return $result;
		}
		$qry = "INSERT INTO " . TABLEPREFIX . "sessions(sessionid, sessiontime, sessionstart, sessiondata) VALUES ('" . addslashes($sessionid) . "', " . time() . ", " . time() . ", '" . addslashes($data) . "')";
		$result = $this->DbConnection->Query($qry);
		return $result;
	}

	/**
	* _destroy
	* Destroys session data in the database based on the sessionid passed in.
	*
	* @param sessionid Sessionid to get from the database.
	*
	* @see DBConnection
	*
	* @return boolean Returns true if it was deleted ok, otherwise false.
	*/
	function _destroy($sessionid=false) {
		$qry = "DELETE FROM " . TABLEPREFIX . "sessions WHERE sessionid='" . addslashes($sessionid) . "'";
		$result = $this->DbConnection->Query($qry);
		return $result;
	}

	/**
	* _gc
	* Removes old session information (garbage collection).
	*
	* @param life How long ago to delete sessions.
	*
	* @see DBConnection
	*
	* @return boolean Returns true if it was deleted ok, otherwise false.
	*/
	function _gc($life=false) {
		$sessionlife = ($life) ? $life : strtotime('-10 minutes');
		$qry = "DELETE FROM " . TABLEPREFIX . "sessions WHERE sessiontime < " . $sessionlife;
		$result = $this->DbConnection->Query($qry);
		return $result;
	}

	/**
	* Set
	* Sets session info based on the variables passed in. Unlike remove this can set a value to be empty ('').
	*
	* @param var Variable name to store it under.
	* @param val Value of the variable - can be any type of variable.
	*
	* @see Remove
	*
	* @return boolean Returns true if it was set ok, otherwise false.
	*/
	function Set($var='', $val='') {
		if ($var == '') return false;
		$this->_ExternalVars[$var] = $val;
		return true;
	}

	/**
	* Get
	* Gets session info based on the variables passed in.
	*
	* @param var Variable name to fetch.
	*
	* @return mixed Returns false if the variable doesn't exist, otherwise returns the variable's value.
	*/
	function Get($var='') {
		if ($var == '') return false;
		if (!isset($this->_ExternalVars[$var])) return false;
		return $this->_ExternalVars[$var];
	}
	
	/**
	* Remove
	* Removes session information totally. Set can change something to be blank but it doesn't remove it.
	*
	* @param var Variable name to fetch.
	*
	* @see Set
	*
	* @return boolean Returns false if the variable doesn't exist, otherwise unsets the data and returns true.
	*/
	function Remove($var='') {
		if ($var == '') return false;
		unset($this->_ExternalVars[$var]);
		return true;
	}

	/**
	* LoggedIn
	* Returns whether the user is logged in or not.
	*
	* @see Get
	*
	* @return boolean Returns whether a user is logged in.
	*/
	function LoggedIn() {
		return $this->Get('UserDetails');
	}
}

/**
* MySessionStart
* This function sets up and starts the session.
* We do it like this so other areas (eg tracking) can do a session_destroy and then session_start - but we need to set up the save_handlers again before we can do it.
*
* @see t.php
*/
function MySessionStart() {
	if (defined('SET_SESSION_NAME')) session_name(SET_SESSION_NAME);
	
	if (TRACKPOINT_ISSETUP) {
		if (!defined('TABLEPREFIX')) define('TABLEPREFIX', TRACKPOINT_TABLEPREFIX);
		$ses_class = new Session();
		session_set_save_handler(
			array(&$ses_class, '_open'),
			array(&$ses_class, '_close'),
			array(&$ses_class, '_read'),
			array(&$ses_class, '_write'),
			array(&$ses_class, '_destroy'),
			array(&$ses_class, '_gc')
		);
	} else {
		ini_set('session.save_handler', 'files');
		if (defined('TEMP_DIRECTORY') && is_writable(TEMP_DIRECTORY)) ini_set('session.save_path', TEMP_DIRECTORY);
	}
	session_start();
}

/**
* Start the session using our custom 'sessionstart' function.
*/
MySessionStart();

?>
