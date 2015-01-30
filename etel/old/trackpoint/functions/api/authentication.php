<?php
/**
* @package API
* @subpackage AuthenticationSystem
* This is the authentication system object.
*/

/**
* Include the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
*
* This class does authentication.
*
* @package API
* @subpackage AuthenticationSystem
*/
class AuthenticationSystem extends API {

	/**
	* Constructor
	* Sets up the database object for easy use.
	*
	* @return void
	*/
	function AuthenticationSystem() {
		$this->GetDb();
	}

	/**
	* Authenticate
	* Authenticates the user. Will return false if the user doesn't exist or the passwords don't match.
	*
	* @param username Username to authenticate.
	* @param password Password to use to authenticate the user.
	*
	* @return mixed Returns false if the user doesn't exist or can't authenticate, otherwise it will return the UserID of the user it found.
	*/
	function Authenticate($username=false, $password=false) {
		if (!$username || !$password) return false;
		$qry = "SELECT userid FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE username='" . addslashes($username) . "' AND password='" . addslashes(md5($password)) . "' AND status=1";

		$result = $this->Db->Query($qry);
		if (!$result) {
			return false;
		}
		$details = $this->Db->Fetch($result);
		return $details;
	}
}

?>
