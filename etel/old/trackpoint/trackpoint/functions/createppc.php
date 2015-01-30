<?php
/**
* This file has the create-payperclick functions in it.
*
* @version     $Id: createppc.php,v 1.11 2005/10/20 03:32:14 chris Exp $
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
* Class for the 'Create PPC' page.
* Handles everything for you, depending on the action. Processes information in an iframe for an easy way to keep creating ppc campaigns without too much retyping.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class CreatePPC extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function CreatePPC() {
	}

	/**
	* Process
	* Processes the creation of the payperclick campaign.
	* Uses an iframe to post the results when a particular action is set.
	* Makes it nice and easy to keep creating payperclicks and not worrying about retyping any of the information.
	*
	* @see Trackpoint_Functions::Process()
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$userid = $thisuser->userid;

		$switched_user = $session->Get('SwitchUser');

		if ($switched_user) {
			$userid = $switched_user;
			$switched_username = $session->Get('SwitchUserName');
			$GLOBALS['WarningMessage'] = sprintf(GetLang('PPCCodeForUser'), $switched_username);
		}

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : null;

		switch($action) {
			case 'ppclink':
				if (isset($_GET['Process'])) {
					$querystring = '';
					if ($userid != 1) {
						$querystring .= 'u=' . $userid . '&';
					}
					if (isset($_POST['EncodeInfo'])) {
						$querystring .= 'e=' . stripslashes($_POST['ppcEngine']);
						$querystring .= '&n=' . stripslashes($_POST['ppcName']);

						if (is_numeric($_POST['ppcCost'])) {
							$querystring .= '&c=' . (float)$_POST['ppcCost'];
						}
						$querystring = 'ppce=' . urlencode(base64_encode($querystring));
					} else {
						$querystring .= 'ppc=' . urlencode(stripslashes($_POST['ppcEngine']));
						$querystring .= '&name=' . urlencode(stripslashes($_POST['ppcName']));

						if (is_numeric($_POST['ppcCost'])) {
							$querystring .= '&cost=' . (float)$_POST['ppcCost'];
						}
					}
					$url = stripslashes($_POST['landingURL']) . '/?';

					if (strpos($_POST['landingURL'], '?') !== false) {
						$url = stripslashes($_POST['landingURL']) . '&' . $querystring;
					} else {
						$landingurl = stripslashes($_POST['landingURL']);
						$urlparts = explode('/', $landingurl);
						$filename = array_pop($urlparts);
						if (strpos($filename, '.') !== false || substr($landingurl, -1) == '/') {
							$url = $landingurl . '?' . $querystring;
						} else {
							$url = $landingurl . '/?' . $querystring;
						}
					}

					echo '<span style="font-family: tahoma; font-size: 12px;">' . $url . '</span>';
				}
			break;
			
			default:
				Trackpoint_Functions::Process();
		}
	}

}

?>
