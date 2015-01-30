<?php
/**
* This has the Base Trackpoint API class in it.
* Includes the init file (to set up database and so on) if it needs to.
* @package TrackPoint
* @subpackage TrackPoint_API
*/

/**
* Check whether the initialization file has been included.
*/
if (!defined('TRACKPOINT_API_DIRECTORY')) {
	require(dirname(__FILE__) . '/../init.php');
}

/**
* This has the Base API class in it.
* Sets up the database object for use.
*
* @see GetDatabase
*
* @package API
*/
class API {

	/**
	* @var Db Database object is stored here. Is null by default, the constructor sets it up.
	* @see Trackpoint_API
	*/
	var $Db = null;

	/**
	* API
	* Sets up the database object for this and the child objects to use.
	*
	* @see GetDb
	*/
	function API() {
		$this->GetDb();
	}

	/**
	* GetDb
	* Sets up the database object for this and the child objects to use.
	* If the Db var is null it will fetch it and store it for easy reference.
	* If it's unable to setup the database (or it's null or false) it will trigger an error.
	*
	* @see Db
	* @see GetDatabase
	*
	* @return boolean True if it works or false if it fails. Failing also triggers a fatal error.
	*/
	function GetDb() {
		if (is_null($this->Db)) {
			$Db = &GetDatabase();
			$this->Db = &$Db;
		}
		if (!$this->Db) {
			trigger_error('Unable to connect to database', ERROR_FATAL);
			return false;
		}
		return true;
	}

	/**
	* Set
	* This sets the class var to the value passed in.
	*
	* @param varname Name of the class var to set.
	* @param value The value to set the class var.
	*
	* @return boolean True if it works, false if the var isn't present.
	*/
	function Set($varname='', $value='') {
		if ($varname == '') return false;
		$this->$varname = $value;
		return true;
	}

	/**
	* Get
	* Returns the class variable.
	*
	* @param varname Name of the class variable to return.
	*
	* @return mixed Returns false if the class variable doesn't exist, otherwise it will return the value in the variable.
	*/
	function Get($varname='') {
		if ($varname == '') return false;
		if (!isset($this->$varname)) return false;
		return $this->$varname;
	}

}

?>
