<?php
/**
* Sets up all initial variables (BASE_DIRECTORY, DATA_DIRECTORY and so on).
* Turns error tracking on, sets memory limit.
* Sets up a bunch of defines to we know where things are.
* Sets up some helper functions to retrieve the session, database.
*
* @see GetSession
* @see GetDatabase
* @see GetAuthenticationSystem
* @see GetUser
* @see TrackpointErrorHandler
*
* @version     $Id: init.php,v 1.18 2005/11/16 07:03:59 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @filesource
*/

error_reporting(E_ALL);

ini_set('short_tags', false);
ini_set('memory_limit', '8M');
ini_set('track_errors', true);
ini_set('display_errors', true);

$basedir = dirname(dirname(__FILE__));

/**
* Set up some locations. This allows us to quickly see where things are.
*/
define('TRACKPOINT_BASE_DIRECTORY', $basedir);
define('TRACKPOINT_INCLUDES_DIRECTORY', $basedir . '/includes');
define('TRACKPOINT_LANGUAGE_DIRECTORY', $basedir . '/language');
define('TRACKPOINT_TEMPLATE_DIRECTORY', TRACKPOINT_INCLUDES_DIRECTORY . '/templates');
define('TRACKPOINT_FUNCTION_DIRECTORY', $basedir . '/functions');
define('TRACKPOINT_LIB_DIRECTORY', TRACKPOINT_FUNCTION_DIRECTORY . '/lib');
define('TRACKPOINT_API_DIRECTORY', TRACKPOINT_FUNCTION_DIRECTORY . '/api');

define('TEMP_DIRECTORY', $basedir.'/temp');

/**
* Check for safe-mode. This is only used when sending 'forgot password' reminders.
*/
$safe_mode = (bool) ini_get('safe_mode');
define('TRACKPOINT_SAFE_MODE', $safe_mode);

/**
* Include the config file if it's present.
*/
if (is_file(TRACKPOINT_INCLUDES_DIRECTORY . '/config.php') && is_readable(TRACKPOINT_INCLUDES_DIRECTORY . '/config.php')) {
	require(TRACKPOINT_INCLUDES_DIRECTORY . '/config.php');
}

/**
* If the config file isn't present, set up some 'dummy' values.
*/
if (!is_file(TRACKPOINT_INCLUDES_DIRECTORY . '/config.php') || !defined('TRACKPOINT_ISSETUP')) {
	define('TRACKPOINT_ISSETUP', false);
	define('TRACKPOINT_DATABASE_TYPE', false);
	define('TRACKPOINT_APPLICATION_URL', false);
	define('TRACKPOINT_LICENSEKEY', false);
}

/**
* Include the language file.
*/
require(TRACKPOINT_LANGUAGE_DIRECTORY . '/language.php');

/**
* Include some other functions.
*/
require(TRACKPOINT_FUNCTION_DIRECTORY . '/process.php');

/**
* This is the number of <b>months</b> that log history is kept (if switched on in the settings page).
*/
define('TRACKPOINT_LOGHISTORY_TIME', 3);

/**
* This is the number of <b>minutes</b> we use to check for duplicate orders.
*/
define('TRACKPOINT_DUPLICATE_ORDER_TIME', 2);

/**
* Name of the cookie we set in the browser.
* Depending on which user we're tracking it will be slightly different
* This lets us track multiple sites with different cookie names.
* We have to do this because each cookie will be under the domain trackpoint is installed on
* NOT under the domain that is being tracked.
*/
$cookiename = 'TrackpointTrack';
if (isset($_GET['u'])) {
	$u = (int)$_GET['u'];
	if ($u > 1) {
		$cookiename .= (int)$_GET['u'];
	}
}
define('TRACKPOINT_COOKIE_NAME', $cookiename);

/**
* Name of the session we check for.
*/
define('SET_SESSION_NAME', 'TrackPointSession');

define('ERROR_FATAL', E_USER_ERROR);
define('ERROR_ERROR', E_USER_WARNING);
define('ERROR_WARNING', E_USER_NOTICE);
if (!is_dir(TEMP_DIRECTORY)) {
        if (!mkdir(TEMP_DIRECTORY, 0755)) {
                trigger_error('Unable to create temp directory: ' . $php_errormsg, ERROR_FATAL);
        }
}

/**
* We're always going to be using the user file to check permissions. let's load 'er up.
* We need to do this before the session, because the session references the user object in some cases.
*/
require(TRACKPOINT_API_DIRECTORY . '/user.php');

require(TRACKPOINT_LIB_DIRECTORY . '/general/general.php');

if (TRACKPOINT_ISSETUP) {
	require(TRACKPOINT_LIB_DIRECTORY . '/database/' . TRACKPOINT_DATABASE_TYPE . '.php');
	$db_type = TRACKPOINT_DATABASE_TYPE . 'Db';
	$db = &new $db_type();
	
	$connection = $db->Connect(TRACKPOINT_DATABASE_HOST, TRACKPOINT_DATABASE_USER, TRACKPOINT_DATABASE_PASS, TRACKPOINT_DATABASE_NAME);
	
	if (!$connection) {
		error_log("Unable to connect to database @ time " . time() . " (date " . date('d M Y H:i:s') . ")\n", 3, TEMP_DIRECTORY . '/db_errors.log');
			list($error, $level) = $db->GetError();
			trigger_error($error, $level);
	}
	$GLOBALS['TrackPoint']['Database'] = $db;
}

/**
* GetDatabase
* Checks whether the global database is present.
*
* @return mixed Will return false if there is no global database system present, otherwise returns a reference to it.
*/
function &GetDatabase() {
        if (!isset($GLOBALS['TrackPoint']['Database'])) return false;
        return $GLOBALS['TrackPoint']['Database'];
}

/**
* GetLang
* Checks whether a language variable exists and returns it if it is.
*
* @return mixed Will return false if there is no language variable present, otherwise returns the language variable for use.
*/
function GetLang($langvar=false) {
	if (!$langvar) {
		trigger_error('Langvar passed in is empty', ERROR_WARNING);
	}
	if (!defined('LNG_' . $langvar)) {
		trigger_error('Langvar \'' . $langvar . '\' doesn\'t exist', ERROR_WARNING);
		return 'LNG_' . $langvar;
	}
	$var = 'LNG_' . $langvar;
	return constant($var);
}

require(TRACKPOINT_LIB_DIRECTORY . '/session/session.php');

/**
* GetUser
* If a userid is passed in, it will create a new user object and return the reference to it.
* If no userid is passed in, it will get the current user from the session.
*
* @param UserID If a userid is passed in, it will create a new user object and return it. If there is no userid it will get the current user from the session.
*
* @see GetSession
* @see Session::Get
* @see User
*
* @return Object The user object.
*/
function &GetUser($userid=0) {
	if ($userid == 0) {
		$session = &GetSession();
		$UserDetails = $session->Get('UserDetails');
		if (!$UserDetails) {
			return false;
		}
		return $UserDetails;
	}

	if ($userid == -1) {
		$user = &new User();
	} else {
		$user = &new User($userid);
	}
	return $user;
}

/**
* GetSession
* Checks whether the session is setup. Will start if it needs to.
*
* @return Object Returns the Trackpoint session object.
*/
function &GetSession() {
        if (!isset($_SESSION['TrackPointSession'])) {
                $_SESSION['TrackPointSession'] = new Session();
        }
        return $_SESSION['TrackPointSession'];
}

/**
* GetAuthenticationSystem
* Checks whether the global Authentication System is present.
* If it's present, will return it.
* If it's not present, it will set it up and then return it.
*
* @return Object Returns the authentication system object.
*/
function &GetAuthenticationSystem() {
	if (isset($GLOBALS['TrackPoint']['Authentication'])) {
		return $GLOBALS['TrackPoint']['Authentication'];
	}
	require(TRACKPOINT_API_DIRECTORY . '/authentication.php');
	$AuthSystem = &new AuthenticationSystem();
	$GLOBALS['TrackPoint']['Authentication'] = $AuthSystem;
	return $AuthSystem;
}

/**
* LogMessage
* Logs a message in the database if the option is turned on. It will allow us to see what actions took place based on the user & ip - the logentry will contain text describing the action taken.
*
* @param string The IP address of the client's browser.
* @param string The log message to keep.
* @param logtype The type of log message this is (referrer or conversion).
* @param int UserID the action is for.
*
* @see TRACKPOINT_LOGHISTORY_TIME
* @see TRACKPOINT_TRACKINGLOGS
* @see GetDatabase
* @see Trackpoint_Functions::DeleteOldCookies
*
* @return void
*/
function LogMessage($file=__FILE__, $line=__LINE__, $ip='', $sessionid='', $message='', $userid=0, $logtype='referrer', $loglevel='info') {
	if (!$ip || !$message) return;
	if (!defined('TRACKPOINT_TRACKINGLOGS') || (int)TRACKPOINT_TRACKINGLOGS == 0) {
		return;
	}
	$db = &GetDatabase();
	$id = $db->NextId(TRACKPOINT_TABLEPREFIX . 'loghistory_sequence');
	$query = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "loghistory (logid, logtime, file, line, sessionid, userid, ip, logtype, logentry, loglevel) VALUES (" . (int)$id . ", '" . time() . "', '" . addslashes($file) . "', '" . addslashes($line) . "', '" . addslashes($sessionid) . "', '" . (int)$userid . "', '" . addslashes($ip) . "', '" . addslashes($logtype) . "', '" . addslashes($message) . "', '" . addslashes(strtoupper($loglevel)) . "')";
	$db->Query($query);
}
?>
