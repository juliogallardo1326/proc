<?php
/**
* The User API.
*
* @package API
* @subpackage User
*/

/**
* Include the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* This will load a user, save a user, set details and get details.
* It will also check access areas.
*
* @package API
* @subpackage User
*/
class User extends API {

	/**
	* @var Db Database object reference.
	*/
	var $Db = null;

	/**
	* @var userid The User that is loaded. By default is 0 (no user).
	*/
	var $userid = 0;

	/**
	* @var username Username of the user that we've loaded.
	*/
	var $username = '';

	/**
	* @var fullname Full name of the user.
	*/
	var $fullname = '';

	/**
	* @var emailaddress Email address of the user.
	*/
	var $emailaddress = '';

	/**
	* @var Status Whether this user is active or not.
	*/
	var $status = false;

	/**
	* @var Admin Whether this user is an administrator or not. An Administrator has access to all functions.
	*/
	var $admin = false;

	/**
	* @var Permissions The array of user permissions.
	*/
	var $permissions = array();

	/**
	* @var Settings The array of user settings.
	* eg Calendar defaults.
	*/
	var $settings = array();

	/**
	* @var Password The users password. This is only set by the users area after updating.
	*/
	var $password = null;

	/**
	* The timezone the user is in.
	*
	* @var string
	*/
	var $usertimezone = 'GMT';

	/**
	* @var Quickstart The users 'quickstart' status.
	*/
	var $quickstart = false;

	/**
	* @var IgnoreSites A list of sites this user ignores when tracking. Comma separated.
	*/
	var $ignoresites = '';

	/**
	* @var IgnoreIPs A list of ips this user ignores when tracking. Comma separated.
	*/
	var $ignoreips = '';

	/**
	* @var IgnoreKeywords A list of keywords this user ignores when tracking. Comma separated.
	*/
	var $ignorekeywords = '';

	/**
	* Constructor
	* Sets up the database object, loads the user if the ID passed in is not 0.
	* @param userid int The userid of the user to load. If it is 0 then you get a base class only. Passing in a userid > 0 will load that user.
	*
	* @return true
	*/
	function User($userid=0) {
		if (is_null($this->Db)) {
			$Db = &GetDatabase();
			if ($Db) {
				$this->Db = $Db;
			}
		}
		if ($userid >= 0) {
			return $this->Load($userid);
		}
		return true;
	}

	/**
	* Load
	* Loads up the user and sets the appropriate class variables.
	*
	* @param userid The userid to load up. If the userid is not present then it will not load up.
	*
	* @return boolean Will return false if the userid is not present, or the user can't be found, otherwise it set the class vars and return true.
	*/
	function Load($userid=0) {
		if ($userid <= 0) return false;
		if (is_null($this->Db)) return;
		$query = 'SELECT * FROM ' . TRACKPOINT_TABLEPREFIX . 'users WHERE userid=\'' . $userid . '\'';
		$result = $this->Db->Query($query);
		if (!$result) return false;
		$user = $this->Db->Fetch($result);
		if (empty($user)) return false;

		$this->userid = $user['userid'];
		$this->username = htmlspecialchars($user['username']);
		$this->admin = ($user['admin']) ? true : false;
		$this->status = ($user['status']) ? true : false;
		$this->fullname = htmlspecialchars($user['fullname']);
		$this->emailaddress = htmlspecialchars($user['emailaddress']);
		
		/**
		* Load these if they are available. We may need to run the upgrade wizard to add them.
		*/
		if (isset($user['usertimezone'])) {
			$this->usertimezone = $user['usertimezone'];
		}
		if (isset($user['ignoresites'])) {
			$this->ignoresites = htmlspecialchars($user['ignoresites']);
		}
		if (isset($user['ignoreips'])) {
			$this->ignoreips = htmlspecialchars($user['ignoreips']);
		}
		if (isset($user['ignorekeywords'])) {
			$this->ignorekeywords = htmlspecialchars($user['ignorekeywords']);
		}

		$this->quickstart = ($user['quickstart'] == 1) ? true : false;

		if ($user['settings'] != '') {
			$this->settings = unserialize(stripslashes($user['settings']));
		}
		return true;
	}

	/**
	* Create
	* This function creates a user based on the current class vars.
	*
	* @return boolean Returns true if it worked, false if it fails.
	*/
	function Create() {
		$userid = $this->Db->NextId(TRACKPOINT_TABLEPREFIX . 'users_sequence');

		$query = 'INSERT INTO ' . TRACKPOINT_TABLEPREFIX . 'users (userid, username, password, status, admin, emailaddress, fullname, ignoresites, ignoreips, ignorekeywords, usertimezone, quickstart) VALUES (\'' . $userid . '\', \'' . addslashes($this->username) . '\', \'' . addslashes(md5($this->password)) . '\', \'' . addslashes((int)$this->status) . '\', \'' . addslashes((int)$this->admin) . '\', \'' . addslashes($this->emailaddress) . '\', \'' . addslashes($this->fullname) . '\', \'' . addslashes($this->ignoresites) . '\', \'' . addslashes($this->ignoreips) . '\', \'' . addslashes($this->ignorekeywords) . '\', \'' . addslashes($this->usertimezone) . '\', \'1\')';

		$result = $this->Db->Query($query);
		return $result;
	}

	/**
	* Find
	* This function finds a user based on the username passed in.
	*
	* @return mixed Will return the userid if it's found, otherwise returns false.
	*/
	function Find($username=false) {
		if (!$username) return false;
		$query = "SELECT userid FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE username='" . addslashes($username) . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$userid = $this->Db->Fetch($result);
		return $userid;
	}

	function Delete($userid=0) {
		if ($userid == 0) $userid = $this->userid;
		$query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE UserID='" . $userid . "'";
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$this->userid = 0;
		$this->username = '';
		$this->fullname = '';
		$this->emailaddress = '';
		$this->permissions = array();
		$this->status = false;
		$this->admin = false;
		$this->password = null;
		$this->quickstart = false;
		$this->ignorekeywords = '';
		$this->ignoresites = '';
		$this->ignoreips = '';
		$this->usertimezone = 'GMT';
		return true;
	}

	/**
	* Save
	* This function saves the current class vars to the user.
	*
	* @return boolean Returns true if it worked, false if it fails.
	*/
	function Save() {
		$query = "UPDATE " . TRACKPOINT_TABLEPREFIX . "users set username='" . addslashes($this->username) . "', status='" . (int)$this->status . "', admin='" . (int)$this->admin . "', fullname='" . addslashes($this->fullname) . "', emailaddress='" . addslashes($this->emailaddress) . "', ignoresites='" . addslashes($this->ignoresites) . "', ignoreips='" . addslashes($this->ignoreips) . "', ignorekeywords='" . addslashes($this->ignorekeywords) . "', usertimezone='" . addslashes($this->usertimezone) . "'";

		if (!is_null($this->password)) {
			$query .= ", password='" . addslashes(md5($this->password)) . "'";
		}
		
		$query .= " WHERE userid='" . $this->userid . "'";

		$result = $this->Db->Update($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		return true;
	}

	function SaveSettings() {
		$query = 'UPDATE ' . TRACKPOINT_TABLEPREFIX . 'users SET settings=\'' . addslashes(serialize($this->settings)) . '\' WHERE userid=\'' . $this->userid . '\'';

		$result = $this->Db->Update($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		return true;
	}

	/**
	* Admin
	* Returns whether the current user is an admin or not.
	*
	* @return boolean Whether the user is an admin or not.
	*/
	function Admin() {
		return $this->admin;
	}

	/**
	* Status
	* Returns the users status.
	*
	* @return boolean The users status (active/inactive).
	*/
	function Status() {
		return $this->status;
	}


	/**
	* ShowQuickStart
	* Whether to show the quickstart menu or not.
	*
	* @return boolean The users quickstart status (true/false).
	*/
	function ShowQuickStart() {
		return $this->quickstart;
	}

	/**
	* StopQuickStart
	* Stops the quickstart menu from showing up.
	*
	* @return boolean true
	*/
	function StopQuickStart() {
		$query = "UPDATE " . TRACKPOINT_TABLEPREFIX . "users SET quickstart=0 WHERE userid='" . $this->userid . "'";
		$result = $this->Db->Update($query);
		return true;
	}


	/**
	* HasAccess
	* Make sure the current user has access to this area.
	*
	* @param area Name of the area to check
	*
	* @return boolean True if the user has access, false if not.
	*/
	function HasAccess($area='Home') {
		if ($this->Admin()) return true;
		return in_array($area, $this->permissions);
	}


	/**
	* SetSettings
	* Set the settings to those passed in.
	*
	* @param area Name of the area to set settings for.
	* @param val The settings to set.
	*
	* @return array
	*/
	function SetSettings($area=false, $val=false) {
		if (!$area) return false;
		$this->settings[$area] = $val;

		// now save it in the session too.
		$session = &GetSession();
		$sessionuser = $session->Get('UserDetails');
		if ($sessionuser->userid == $this->userid) {
			$session->Set('UserDetails', $this);
		}

		return $this->GetSettings($area);
	}

	/**
	* GetSettings
	* Return the sub-array of settings based on the name passed in.
	*
	* @param area Name of the area to return settings for.
	*
	* @return array
	*/
	function GetSettings($area=false) {
		if (!$area) return false;
		if (!isset($this->settings[$area])) {
			$this->settings[$area] = array();
		}
		return $this->settings[$area];
	}

	/**
	* LastUser
	* Returns boolean on whether this is the last user or not.
	*
	* @return boolean True if this user is the last one, false if there are others.
	*/
	function LastUser($userid=0) {
		$query = "SELECT COUNT(*) AS count FROM " . TRACKPOINT_TABLEPREFIX . "users";
		if ($userid) {
			$query .= " WHERE UserID NOT IN (" . $userid . ")";
		} else {
			if ($this->userid) $query .= " WHERE UserID NOT IN (" . $this->userid . ")";
		}
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$row = $this->Db->Fetch($result);
		$count = $row['count'];
		if ($count > 0) return false;
		return true;
	}

	/**
	* LastActiveUser
	* Returns boolean on whether this is the last active user or not.
	*
	* @return boolean True if this user is the last one, false if there are others.
	*/
	function LastActiveUser($userid=0) {
		$query = "SELECT COUNT(*) AS count FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE status='1'";
		if ($userid) {
			$query .= " AND UserID NOT IN (" . $userid . ")";
		} else {
			if ($this->userid) $query .= " AND UserID NOT IN (" . $this->userid . ")";
		}
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$row = $this->Db->Fetch($result);
		$count = $row['count'];
		if ($count > 0) return false;
		return true;
	}

	/**
	* LastAdminUser
	* Returns boolean on whether this is the last admin user or not.
	*
	* @return boolean True if this user is the last one, false if there are others.
	*/
	function LastAdminUser($userid=0) {
		$query = "SELECT COUNT(*) AS count FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE status='1' AND admin='1'";
		if ($userid) {
			$query .= " AND UserID NOT IN (" . $userid . ")";
		} else {
			if ($this->userid) $query .= " AND UserID NOT IN (" . $this->userid . ")";
		}
		$result = $this->Db->Query($query);
		if (!$result) {
			list($error, $level) = $this->Db->GetError();
			trigger_error($error, $level);
			return false;
		}
		$row = $this->Db->Fetch($result);
		$count = $row['count'];
		if ($count > 0) return false;
		return true;
	}
}

?>
