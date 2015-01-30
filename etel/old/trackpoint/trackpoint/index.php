<?php
/**
* @package TrackPoint
* Includes the config files, sets everything up ready to go.
*
* @version     $Id: index.php,v 1.9 2005/11/04 00:36:05 chris Exp $
* @author Chris <chris@interspire.com>
* @filesource
*/

/**
* Include our base file.
*/
require('functions/init.php');

$page = (isset($_GET['Page'])) ? strtolower($_GET['Page']) : 'index';

if (!is_file(TRACKPOINT_FUNCTION_DIRECTORY . '/' . $page . '.php')) {
        $page = 'index';
}

/**
* check whether they are logged in or not first before checking the cookie.
* checking the cookie first meant it was always loading the user from the database, 
* which stuffed up paging & calendar settings.
*/
$session = &GetSession();
if (!$session->LoggedIn()) {
	if (isset($_COOKIE['TrackPointLogin'])) {
		$valid = false;
		// check it's a valid user first.
		$cookie_info = @unserialize(base64_decode($_COOKIE['TrackPointLogin']));
		if (isset($cookie_info['user'])) {
			$userid = $cookie_info['user'];
			$user = &GetUser($userid);
			if ($user->userid && $user->settings['LoginCheck'] == $cookie_info['rand']) {
				$valid = true;
			}
		}
		if ($valid) {
			$session->Set('UserDetails', $user);
		} else {
			$page = 'login';
		}
	} else {
		$page = 'login';
	}
}

if (!defined('TRACKPOINT_ISSETUP') || TRACKPOINT_ISSETUP == false) {
	$page = 'install';
}

if (!in_array($page, array('login', 'install', 'logout'))) {
	if (!defined('TRACKPOINT_TRACKINGLOGS')) {
		$page = 'upgrade';
	}
}
/**
* Now we've worked out what's going on, include the right page so it can do it's processing.
*/
require(TRACKPOINT_FUNCTION_DIRECTORY . '/' . $page . '.php');

$system = &new $page();
$system->Process();

?>
