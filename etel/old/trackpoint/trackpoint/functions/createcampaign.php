<?php
/**
* This file has the create-campaign functions in it.
*
* @version     $Id: createcampaign.php,v 1.13 2005/10/20 03:32:14 chris Exp $
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
* Class for the Create Campaign page.
* Handles everything for you, depending on the action. Processes information in an iframe for an easy way to keep creating campaigns without too much retyping.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class CreateCampaign extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function CreateCampaign() {
	}
	
	/**
	* Process
	* Processes the creation of the campaign.
	* Uses an iframe to post the results when a particular action is set.
	* Makes it nice and easy to keep creating campaigns and not worrying about retyping any of the information.
	*
	* @see Trackpoint_Functions::Process()
	*
	* @return void
	*/
	function Process() {
		$today = date('d');
		$thismonth = date('m');
		$thisyear = date('y');

		if (isset($_POST['StartDay'])) $today = $_POST['StartDay'];
		if (isset($_POST['StartMonth'])) $thismonth = $_POST['StartMonth'];
		if (isset($_POST['StartYear'])) $thisyear = $_POST['StartYear'];

		$days = '';
		for ($i = 1; $i <= 31; $i++) {
			$days .= '<option value="' . sprintf('%02d', $i) . '"';
			if ($i == $today) $days .= ' SELECTED';
			$days .= '>' . sprintf('%02d', $i) . '</option>';
		}
		
		$months = '';
		for ($i = 1; $i <= 12; $i++) {
			$months .= '<option value="' . GetLang($this->Months[$i]) . '"';
			if ($i == $thismonth) $months .= ' SELECTED';
			$months .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		
		$years = '';
		for ($i = ($thisyear - 1); ($i <= $thisyear+5);  $i++) {
			$years .= '<option value="' . sprintf('%02d', $i) . '"';
			if ($i == $thisyear) $years .= ' SELECTED';
			$years .= '>' . sprintf('%02d', $i) . '</option>';
		}
		
		$GLOBALS['StartDay'] = $days;
		$GLOBALS['StartMonth'] = $months;
		$GLOBALS['StartYear'] = $years;

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$userid = $thisuser->userid;

		$switched_user = $session->Get('SwitchUser');
		if ($switched_user) {
			$userid = $switched_user;
			$switched_username = $session->Get('SwitchUserName');
			$GLOBALS['WarningMessage'] = sprintf(GetLang('CampaignCodeForUser'), $switched_username);
		}

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : null;

		switch($action) {
			case 'campaignlink':
				if (isset($_GET['Process'])) {
					$querystring = '';
					if ($userid != 1) {
						$querystring .= 'u=' . $userid . '&';
					}
					if (isset($_POST['EncodeInfo'])) {
						$querystring .= 'cp=' . stripslashes($_POST['CampaignName']);
						$querystring .= '&s=' . stripslashes($_POST['CampaignSite']);
		
						if (is_numeric($_POST['CampaignCost'])) {
							$querystring .= '&c=' . (float)$_POST['CampaignCost'];
						}
		
						if (isset($_POST['CampaignCostType'])) {
							if (is_numeric($_POST['PeriodDate'])) {
								$querystring .= '&p=' . (int)$_POST['PeriodDate'];
							} else {
								if ($_POST['PeriodDate'] == 'custom') {
									$querystring .= '&p=' . (int)$_POST['Days'];
								}
							}
							$GLOBALS['Period' . $_POST['PeriodDate'] . '_Selected'] = ' SELECTED';
							$querystring .= '&d=' . $_POST['StartDay'] . $_POST['StartMonth'] . $_POST['StartYear'];
						}
						$querystring = 'cpe=' . urlencode(base64_encode($querystring));
					} else {
						$querystring .= 'cp=' . urlencode(stripslashes($_POST['CampaignName']));
						$querystring .= '&site=' . urlencode(stripslashes($_POST['CampaignSite']));
		
						if (is_numeric($_POST['CampaignCost'])) {
							$querystring .= '&cost=' . (float)$_POST['CampaignCost'];
						}
		
						if (isset($_POST['CampaignCostType'])) {
							if (is_numeric($_POST['PeriodDate'])) {
								$querystring .= '&period=' . (int)$_POST['PeriodDate'];
							} else {
								if ($_POST['PeriodDate'] == 'custom') {
									$querystring .= '&period=' . (int)$_POST['days'];
								}
							}
							$querystring .= '&date=' . $_POST['StartDay'] . $_POST['StartMonth'] . $_POST['StartYear'];
						}
					}
					if (strpos($_POST['CampaignURL'], '?') !== false) {
						$url = stripslashes($_POST['CampaignURL']) . '&' . $querystring;
					} else {
						$campaignurl = stripslashes($_POST['CampaignURL']);
						$urlparts = explode('/', $campaignurl);
						$filename = array_pop($urlparts);
						if (strpos($filename, '.') !== false || substr($campaignurl, -1) == '/') {
							$url = $campaignurl . '?' . $querystring;
						} else {
							$url = $campaignurl . '/?' . $querystring;
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
