<?php
/**
* This file has the settings page in it.
*
* @version     $Id: settings.php,v 1.14 2005/11/03 02:15:19 chris Exp $
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
* This class handles the settings page. It mostly uses the API to check for problems and pre-fills the form.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Settings extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Settings() {
	}


	/**
	* Process
	* Does all the work.
	* Saves settings, Checks details, calls the API to save the actual settings and checks whether it worked or not.
	*
	* @see GetApi
	* @see Api::Set
	* @see Api::Save
	* @see GetLang
	* @see ParseTemplate
	* @see TrackPoint_Functions::Process
	*
	*/
	function Process() {
		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';
		switch($action) {
			case 'save':
				$api = $this->GetApi();
				$result = false;
				$errormsg = false;
				if ($api) {
					$settings = array();
					foreach($api->Areas as $p => $area) {
						$val = (isset($_POST[strtolower($area)])) ? $_POST[strtolower($area)] : false;
						if ($area == 'DATABASE_PASS') {
							if ($_POST['database_pass_confirm'] != '') {
								if ($_POST['database_pass_confirm'] != $_POST['database_pass']) {
									$result = false;
									$errormsg = GetLang('DatabasePasswordsDontMatch');
									break;
								}
							}
						}
						if ($area == 'APPLICATION_URL') {
							if (substr($val, -1) == '/') $val = substr($val, 0, -1);
						}
						$settings[$area] = addslashes($val);
						$var = 'TRACKPOINT_' . $area;
						$$var = $val;
					}
					if (!$errormsg) {
						$settings['ISSETUP'] = 'true';
						$api->Set('Settings', $settings);
						$result = $api->Save();
					}
				}

				if ($result) {
					$GLOBALS['Success'] = GetLang('SettingsSaved');
					$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);
				} else {
					$GLOBALS['Error'] = GetLang('SettingsNotSaved');
					if ($errormsg) {
						$GLOBALS['Error'] .= '<br/>' . $errormsg;
					}
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}

				if ($_POST['licensekey'] != $_POST['licensekey_old']) {
					header('Location: index.php?Page=Logout');
					exit();
				}

			default:
				$TRACKPOINT_DATABASE_TYPE = (isset($TRACKPOINT_DATABASE_TYPE)) ? $TRACKPOINT_DATABASE_TYPE : TRACKPOINT_DATABASE_TYPE;
				$TRACKPOINT_DATABASE_USER = (isset($TRACKPOINT_DATABASE_USER)) ? $TRACKPOINT_DATABASE_USER : TRACKPOINT_DATABASE_USER;
				$TRACKPOINT_DATABASE_HOST = (isset($TRACKPOINT_DATABASE_HOST)) ? $TRACKPOINT_DATABASE_HOST : TRACKPOINT_DATABASE_HOST;
				$TRACKPOINT_DATABASE_NAME = (isset($TRACKPOINT_DATABASE_NAME)) ? $TRACKPOINT_DATABASE_NAME : TRACKPOINT_DATABASE_NAME;
				$TRACKPOINT_DATABASE_PASS = (isset($TRACKPOINT_DATABASE_PASS)) ? $TRACKPOINT_DATABASE_PASS : TRACKPOINT_DATABASE_PASS;
				$TRACKPOINT_TABLEPREFIX = (isset($TRACKPOINT_TABLEPREFIX)) ? $TRACKPOINT_TABLEPREFIX : TRACKPOINT_TABLEPREFIX;
				$TRACKPOINT_APPLICATION_URL = (isset($TRACKPOINT_APPLICATION_URL)) ? $TRACKPOINT_APPLICATION_URL : TRACKPOINT_APPLICATION_URL;

				$TRACKPOINT_EMAIL_ADDRESS = (isset($TRACKPOINT_EMAIL_ADDRESS)) ? $TRACKPOINT_EMAIL_ADDRESS : TRACKPOINT_EMAIL_ADDRESS;
				
				$TRACKPOINT_LICENSEKEY = (isset($TRACKPOINT_LICENSEKEY)) ? $TRACKPOINT_LICENSEKEY : TRACKPOINT_LICENSEKEY;

				$TRACKPOINT_COOKIE_TIME = (isset($TRACKPOINT_COOKIE_TIME)) ? $TRACKPOINT_COOKIE_TIME : TRACKPOINT_COOKIE_TIME;

				if (isset($TRACKPOINT_DELETECOOKIE)) {
					$delcookie = $TRACKPOINT_DELETECOOKIE;
				} else {
					$delcookie = TRACKPOINT_DELETECOOKIE;
				}
				$deletecookie = '';
				if ($delcookie) $deletecookie = ' CHECKED';

				if (isset($TRACKPOINT_TRACKINGLOGS)) {
					$tracklogs = $TRACKPOINT_TRACKINGLOGS;
				} else {
					$tracklogs = TRACKPOINT_TRACKINGLOGS;
				}
				$trackinglogs = '';
				if ($tracklogs) $trackinglogs = ' CHECKED';

				$timezone = (isset($TRACKPOINT_SERVERTIMEZONE)) ? $TRACKPOINT_SERVERTIMEZONE : TRACKPOINT_SERVERTIMEZONE;
				$GLOBALS['ServerTimeZoneList'] = $this->TimeZoneList($timezone);

				$GLOBALS['DatabaseType'] = $TRACKPOINT_DATABASE_TYPE;
				$GLOBALS['DatabaseUser'] = $TRACKPOINT_DATABASE_USER;
				$GLOBALS['DatabaseHost'] = $TRACKPOINT_DATABASE_HOST;
				$GLOBALS['DatabaseName'] = $TRACKPOINT_DATABASE_NAME;
				$GLOBALS['DatabasePass'] = $TRACKPOINT_DATABASE_PASS;
				$GLOBALS['DatabaseTablePrefix'] = $TRACKPOINT_TABLEPREFIX;
				$GLOBALS['EmailAddress'] = $TRACKPOINT_EMAIL_ADDRESS;
				$GLOBALS['ApplicationURL'] = $TRACKPOINT_APPLICATION_URL;
				$GLOBALS['LicenseKey'] = $TRACKPOINT_LICENSEKEY;
				$GLOBALS['CookieTime'] = $TRACKPOINT_COOKIE_TIME;
				$GLOBALS['DeleteCookie'] = $deletecookie;
				$GLOBALS['TrackingLogs'] = $trackinglogs;
				$GLOBALS['FormAction'] = 'Action=Save';
				TrackPoint_Functions::Process();
			break;
		}
	}
	
}

?>
