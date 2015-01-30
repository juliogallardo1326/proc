<?php
/**
* This file has the install procedure functions in it.
*
* @version     $Id: install.php,v 1.11 2005/11/08 05:49:39 chris Exp $
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
* Class for the installation process. Handles license key checks, database creation, table setup and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Install extends TrackPoint_Functions {

	/**
	* var ErrorFound Whether there was a problem with the installation.
	*
	* @see Install_Step1
	* @see Install_Step2
	* @see Install_Step3
	* @see Install_Step4
	*/
	var $ErrorFound = false;

	/**
	* var PermissionError Whether there was a problem with the installation - permissions in particular.
	*
	* @see CheckPermissions
	* @see Install_Step1
	*/
	var $PermissionError = false;
	
	/**
	* Constructor
	* Checks whether trackpoint is set up. If it thinks it is, it will redirect back to the login page.
	*
	* @return void
	*/
	function Install() {
		if (defined('TRACKPOINT_ISSETUP') && TRACKPOINT_ISSETUP == true) {
			header('Location: index.php');
		}
	}

	/**
	* Process
	* Works out which step everything is up to. Handles error detection etc.
	*
	* @see Install_Step1
	* @see Install_Step2
	* @see Install_Step3
	* @see Install_Step4
	* @see CheckPermissions
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$installsettings = $session->Get('InstallSettings');
		
		if (!$installsettings) {
			$installsettings = array();
		}
		
		$this->PrintHeader();
		
		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : false;
		
		switch($action) {
			case 'step2':
				if (!isset($_POST['LicenseKey'])) {
					$this->ErrorFound = 'The specified license key is invalid, please contact <a href=\'mailto:trackpoint@interspire.com\' style=\'color: blue\'>trackpoint@interspire.com</a> for a new key.';
					$this->Install_Step1();
					break;
				}
				
				list($license_error, $msg) = tpQmz44Rtt($_POST['LicenseKey']);
				if ($license_error) {
					$this->ErrorFound = 'The specified license key is invalid, please contact <a href=\'mailto:trackpoint@interspire.com\' style=\'color: blue\'>trackpoint@interspire.com</a> for a new key.';
					$this->Install_Step1();
					break;
				}
				
				$installsettings['LICENSEKEY'] = $_POST['LicenseKey'];
				$session->Set('InstallSettings', $installsettings);
				$this->Install_Step2();
				
			break;
			
			case 'step3':
				$installsettings['TRACKINGLOGS'] = false;
				$installsettings['SERVERTIMEZONE'] = $_POST['servertimezone'];
				$installsettings['APPLICATION_URL'] = $_POST['application_url'];
				$installsettings['COOKIE_TIME'] = '2190';
				$installsettings['DELETECOOKIE'] = false;
				$installsettings['EMAIL_ADDRESS'] = $_POST['email_address'];
				$session->Set('InstallSettings', $installsettings);
				$this->Install_Step3();
			break;
			
			case 'step4':
				$dbtype = $_POST['dbtype'];
				require(TRACKPOINT_LIB_DIRECTORY . '/database/' . $dbtype . '.php');
				$db_type = $dbtype . 'Db';
				$db = &new $db_type();
				
				$hostname = $_POST['databaseserver'];
				$username = $_POST['databaseuser'];
				$password = $_POST['databasepass'];
				$database = $_POST['databasename'];
				$connection = $db->Connect($hostname, $username, $password, $database);
				if (!$connection) {
					list($msg, $level) = $db->GetError();
					$this->ErrorFound = $msg;
					$this->Install_Step3();
					break;
				}
				$GLOBALS['TablePrefix'] = $_POST['tableprefix'];
				$schema = trim($this->ParseTemplate('schema.' . $dbtype, true));
				$queries = explode(';', $schema);
				$errors = array();
				foreach($queries as $qry) {
					if (!$qry) continue;
					$result = $db->Query($qry);
					if (!$result) {
						list($msg, $level) = $db->GetError();
						$errors[] = $msg;
					}
				}
				if (!empty($errors)) {
					$this->ErrorFound = implode('<br/>', $errors);
					$this->Install_Step3();
					break;
				}
				$installsettings['DATABASE_TYPE'] = $dbtype;
				$installsettings['DATABASE_USER'] = $username;
				$installsettings['DATABASE_PASS'] = $password;
				$installsettings['DATABASE_HOST'] = $hostname;
				$installsettings['DATABASE_NAME'] = $database;
				$installsettings['TABLEPREFIX'] = $_POST['tableprefix'];
				$session->Set('InstallSettings', $installsettings);
				
				$this->Install_Step4();
			break;
			
			case 'step1':
				$this->CheckPermissions();
				$this->Install_Step1();
			break;
			
			default:
				$session->Remove('InstallSettings');
				$this->Install_Start();
		}
		
		$this->PrintFooter();
	}
	
	/**
	* CheckPermissions
	* Makes sure permissions for the config file and the temp directory are set properly.
	*
	* @see ErrorFound
	* @see PermissionError
	*
	* @return void
	*/
	function CheckPermissions() {
		$configfile = TRACKPOINT_INCLUDES_DIRECTORY . '/config.php';
		$errors = array();
		if (is_file($configfile)) {
			if (!is_writable($configfile)) {
				$errors[] = 'The config file (' . $configfile . ') is not writable. Please CHMOD this file to 777 and then click on the "Try Again" button below.';
			}
		} else {
			if (!is_writable(TRACKPOINT_INCLUDES_DIRECTORY)) {
				$errors[] = 'The includes directory (' . TRACKPOINT_INCLUDES_DIRECTORY . ') is not writable. Please CHMOD this directory to 777 and then click on the "Try Again" button below.';
			}
		}
		if (is_dir(TEMP_DIRECTORY)) {
			if (!is_writable(TEMP_DIRECTORY)) {
				$errors[] = 'The temp directory (' . TEMP_DIRECTORY . ') is not writable. Please CHMOD this directory to 777 and then click on the "Try Again" button below.';
			}
		} else {
			$errors[] = 'The temp directory (' . TEMP_DIRECTORY . ') doesn\'t exist. Please create this directory and CHMOD this directory to 777 and then click on the "Try Again" button below.';
		}
		if (!empty($errors)) {
			$this->PermissionError = true;
			$this->ErrorFound = implode('<br/>', $errors);
		}
	}

	/**
	* Install_Start
	* Prints out the 'welcome' page to the installation.
	*
	* @return void
	*/
	function Install_Start() {
		$this->ParseTemplate('Install_Start');
	}

	/**
	* Install_Step1
	* Prints out the 'step 1' page to the installation.
	* Will print an error if permissions or license key problems occur.
	*
	* @see ErrorFound
	* @see PermissionError
	* @see Install_Start
	*
	* @return void
	*/
	function Install_Step1() {
		if (!$this->ErrorFound) {
			$GLOBALS['PermissionErrorPanel'] = 'none';
			$GLOBALS['HideErrorPanel'] = 'none';
			$GLOBALS['ShowStep1'] = '';
		} else {
			if (!$this->PermissionError) {
				$GLOBALS['PermissionErrorPanel'] = 'none';
				$GLOBALS['HideErrorPanel'] = '';
				$GLOBALS['ShowStep1'] = '';
			} elseif ($this->PermissionError) {
				$GLOBALS['PermissionErrorPanel'] = '';
				$GLOBALS['HideErrorPanel'] = 'none';
				$GLOBALS['ShowStep1'] = 'none';
			}
			$GLOBALS['Error'] = $this->ErrorFound;
		}
		$this->ParseTemplate('Install_Step1');
	}

	/**
	* Install_Step2
	* Prints out the 'step 2' page to the installation (url, cookie time, email address).
	*
	* @see Install_Step1
	*
	* @return void
	*/
	function Install_Step2() {
		if (!isset($_POST['application_url'])) {
			$rest = preg_replace('%/index.php%', '', $_SERVER['PHP_SELF']);
			$http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
			$url = $http . '://' . $_SERVER['HTTP_HOST'] . $rest . '/';
			if (substr($url, -1) == '/') $url = substr($url, 0, -1);
			$GLOBALS['application_url'] = $url;
		} else {
			$GLOBALS['application_url'] = htmlspecialchars($_POST['application_url']);
		}

		$GLOBALS['ServerTimeZoneList'] = $this->TimeZoneList('GMT');

		$this->ParseTemplate('Install_Step2');
	}

	/**
	* Install_Step2
	* Prints out the 'step 3' page to the installation (sets up the database) if there are no errors from step 2.
	*
	* @see Install_Step2
	* @see ErrorFound
	*
	* @return void
	*/
	function Install_Step3() {
		if (!$this->ErrorFound) {
			$GLOBALS['HideErrorPanel'] = 'none';
			$GLOBALS['mysql'] = ' SELECTED';
			$GLOBALS['tableprefix'] = 'tp_';
			$GLOBALS['databaseserver'] = 'localhost';
		} else {
			$GLOBALS['mysql'] = ($_POST['dbtype'] == 'mysql') ? ' SELECTED' : '';
			$GLOBALS['pgsql'] = ($_POST['dbtype'] == 'pgsql') ? ' SELECTED' : '';
			$GLOBALS['databaseserver'] = htmlspecialchars($_POST['databaseserver']);
			$GLOBALS['databaseuser'] = htmlspecialchars($_POST['databaseuser']);
			$GLOBALS['databasepass'] = htmlspecialchars($_POST['databasepass']);
			$GLOBALS['databasename'] = htmlspecialchars($_POST['databasename']);
			$GLOBALS['tableprefix'] = htmlspecialchars($_POST['tableprefix']);
			$GLOBALS['Error'] = $this->ErrorFound;
		}
		$this->ParseTemplate('Install_Step3');
	}
	
	/**
	* Install_Step4
	* Saves the settings from each step in the config file.
	*
	* @see Install_Step3
	* @see GetSession
	* @see GetApi
	* @see Api::Set
	* @see Settings::Save
	*
	* @return void
	*/
	function Install_Step4() {
		$session = &GetSession();
		$installsettings = $session->Get('InstallSettings');
		$api = $this->GetApi('Settings');
		$settings = array();
		foreach($installsettings as $area => $val) {
			$settings[$area] = addslashes($val);
		}
		$settings['ISSETUP'] = 'true';
		$api->Set('Settings', $settings);
		$result = $api->Save();
		$this->ParseTemplate('Install_Step4');
	}
}

?>
