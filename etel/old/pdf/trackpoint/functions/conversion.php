<?php
/**
* This file has the conversion tracking-code functions in it.
*
* @version     $Id: conversion.php,v 1.6 2005/10/20 03:32:14 chris Exp $
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
* Class for the conversion page.
* Doesn't need to override anything, it will simply parse and print out the template.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Conversion extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Conversion() {
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
			$GLOBALS['WarningMessage'] = sprintf(GetLang('ConversionCodeForUser'), $switched_username);
		}

		TrackPoint_Functions::Process();
	}

}

?>
