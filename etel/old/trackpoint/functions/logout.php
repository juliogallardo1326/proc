<?php
/**
* This file has the logout functions in it. After logging you out it will redirect you back to the login form.
*
* @version     $Id: logout.php,v 1.8 2005/07/20 05:09:04 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
* @filesource
*/

/**
* Include the base trackpoint functions.
*/
require_once(dirname(__FILE__) . '/trackpoint_functions.php');

/**
* Class for the logout page. After logging you out it will redirect you back to the login form.
* Gets rid of session variables, cookies etc.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Logout extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Logout() {
	}
	
	/**
	* Process
	* Logs you out and redirects you back to the login page.
	* If you are automatically logged in,
	* this will also remove the cookie (sets the time back a year)
	* so you're not automatically logged in anymore.
	*
	* @see Login::Process
	* @see GetSession
	* @see Session::Set
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$sessionuser = $session->Get('UserDetails');
		$userid = $sessionuser->userid;
		$user = &GetUser($userid);
		$user->settings = $sessionuser->settings;
		$user->SaveSettings();
		unset($user);
		$session->Set('UserDetails', '');
		if (isset($_COOKIE['TrackPointLogin'])) {
			$oneyear = time() - (3600 * 265 * 24);
			setcookie('TrackPointLogin', '', $oneyear, '/');
		}
		$_SESSION = array();
		session_destroy();
		header('Location: ' . $_SERVER['PHP_SELF'] . '?Page=Login&Action=Logout');
	}
}

?>
