<?php
/**
* The Settings API.
*
* @package API
* @subpackage User
*/

/**
* Include the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load settings, set them and save them all for you.
*
* @package API
* @subpackage Settings_API
*/
class Settings_API extends API {

	var $Settings = array();

	var $ConfigFile = false;

	var $Areas = array('DATABASE_TYPE', 'DATABASE_USER', 'DATABASE_PASS', 'DATABASE_HOST', 'DATABASE_NAME', 'TABLEPREFIX', 'LICENSEKEY', 'APPLICATION_URL', 'COOKIE_TIME', 'DELETECOOKIE', 'ISSETUP', 'EMAIL_ADDRESS', 'SERVERTIMEZONE', 'TRACKINGLOGS');

	/**
	* Constructor
	*
	* Does nothing
	* @return void
	*/
	function Settings_API() {
		$this->ConfigFile = TRACKPOINT_INCLUDES_DIRECTORY . '/config.php';
	}

	/**
	* Load
	* Loads up the settings from the config file.
	*
	* @return boolean Will return false if the config file isn't present, otherwise it set the class vars and return true.
	*/
	function Load() {
		$fp = false;
		if (!$fp = fopen($this->ConfigFile, 'r')) {
			return false;
		}
		$contents = fread($fp, filesize($this->ConfigFile));
		fclose($fp);
		return true;
	}

	/**
	* Save
	* This function saves the current class vars to the user.
	*
	* @return boolean Returns true if it worked, false if it fails.
	*/
	function Save() {
		if (is_file($this->ConfigFile) && !is_writable($this->ConfigFile)) return false;

		$handle = false;
		$tmpfname = tempnam(TEMP_DIRECTORY, 'TP_');
		if (!$handle = fopen($tmpfname, 'w')) {
			return false;
		}

		$contents = '';
		$contents .= '<?' . 'php' . "\n\n";

		foreach($this->Areas as $area) {
			if ($area != 'ISSETUP') {
				$string = 'define(\'TRACKPOINT_' . $area . '\', \'' . $this->Settings[$area] . '\');' . "\n";
			} else {
				$string = 'define(\'TRACKPOINT_' . $area . '\', ' . $this->Settings[$area] . ');' . "\n";
			}
			$contents .= $string;
		}

		$contents .= "\n" . '?>' . "\n";

		fputs($handle, $contents, strlen($contents));
		fclose($handle);

		if (copy($tmpfname, $this->ConfigFile)) {
			unlink($tmpfname);
			return true;
		}
		return false;
	}
}

?>
