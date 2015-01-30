<?php
/**
* This file has the 'get tracking code' functions in it.
*
* @version     $Id: track.php,v 1.7 2005/10/20 03:32:14 chris Exp $
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
* This class has the 'get tracking code' functions in it.
* It simply shows the template, so there is no Process function here.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Track extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Track() {
	}

	/**
	* Process
	* Check if we are viewing statistics as another user or not so we can change the links created.
	* We will also display a warning about which user we are creating the tracking code for.
	*
	* @see TrackPoint_Functions::Process
	*/
	function Process() {
		$session = &GetSession();
		$switched_user = $session->Get('SwitchUser');
		if ($switched_user) {
			$switched_username = $session->Get('SwitchUserName');
			$GLOBALS['TrackPointUserID'] = $switched_user;
			$GLOBALS['WarningMessage'] = sprintf(GetLang('TrackingCodeForUser'), $switched_username);
		}

		TrackPoint_Functions::Process();
	}

}

?>
