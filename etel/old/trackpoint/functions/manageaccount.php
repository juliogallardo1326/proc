<?php
/**
* This file has the user editing forms in it.
* Handles permission checks, making sure you only update certain aspects of your account (email, password, name)
*
* @version     $Id: manageaccount.php,v 1.8 2005/11/03 00:32:23 chris Exp $
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
* Class for the manage-own-account page.
* Handles permission checks, making sure you only update certain aspects of your account (email, password, name)
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class ManageAccount extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function ManageAccount() {
	}


	/**
	* Process
	* Lets a user manage their own account - to a certain extent.
	* The API itself manages saving and updating, this just works out displaying of forms etc.
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see GetUser
	* @see User::Set
	* @see GetLang
	* @see PrintEditForm
	* @see PrintFooter
	*
	* @return void
	*/
	function Process() {
		$this->PrintHeader();
		$this->ParseTemplate('Menu');

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$db = &GetDatabase();

		$action = (isset($_GET['Action'])) ? $_GET['Action'] : '';
		switch($action) {
			case 'Save':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;

				$user = &GetUser($userid);
				foreach(array('fullname', 'emailaddress', 'status', 'admin', 'ignoreips', 'ignorekeywords', 'usertimezone') as $p => $area) {
					$val = (isset($_POST[$area])) ? $_POST[$area] : '';
					if (in_array($area, array('status', 'admin'))) {
						if ($userid == $thisuser->userid) $val = $thisuser->$area;
					}
					$user->Set($area, stripslashes($val));
				}
				$ignoresites = (isset($_POST['ignoresites'])) ? $_POST['ignoresites'] : '';
				$ignoresites = str_replace(array('http://', 'https://'), '', $ignoresites);
				$user->Set('ignoresites', $ignoresites);

				$error = false;
				$template = false;

				if (!$error) {
					if ($_POST['tp_password'] != '') {
						if ($_POST['tp_password_confirm'] != '' && $_POST['tp_password_confirm'] == $_POST['tp_password']) {
							$user->Set('password', stripslashes($_POST['tp_password']));
						} else {
							$error = GetLang('PasswordsDontMatch');
						}
					}
				}

				if (!$error) {
					$result = $user->Save();
					if ($result) {

						// update the session if we save everything ok. This is important for "ignoresites" and "ignoreips".
						if ($userid == $thisuser->userid) {
							$session->Set('UserDetails', $user);
						}
						// In case we change ip's or domains we're going to ignore, we need to recalculate the first hit and ignore criteria.
						$session->Remove('FirstHit');
						$session->Remove('IgnoreDetails');

						$GLOBALS['Success'] = GetLang('UserUpdated');
						$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);
					} else {
						$GLOBALS['Error'] = GetLang('UserNotUpdated');
						$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					}
				} else {
					$GLOBALS['Error'] = $error;
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
				}
				$this->PrintEditForm($userid);
			break;

			default:
				$userid = $thisuser->userid;
				$this->PrintEditForm($userid);
			break;
		}
		$this->PrintFooter();
	}


	/**
	* PrintEditForm
	* Prints the editing form for the userid passed in.
	* Also makes sure that the user doesn't try to edit another users' details.
	*
	* @param userid UserID to show the form for. This will load up the user and use their details as the defaults.
	*
	* @see GetSession
	* @see Session::Get
	* @see User::Admin
	* @see GetLang
	* @see GetUser
	*/
	function PrintEditForm($userid=0) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!$thisuser->Admin()) {
			if ($userid != $thisuser->userid) {
				$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
				$this->ParseTemplate('AccessDenied');
				return false;
			}
		}

		$user = &GetUser($userid);
		$GLOBALS['UserID'] = $user->userid;
		$GLOBALS['UserName'] = $user->username;
		$GLOBALS['FullName'] = $user->fullname;
		$GLOBALS['EmailAddress'] = $user->emailaddress;

		$GLOBALS['IgnoreSites'] = $user->ignoresites;
		$GLOBALS['IgnoreIPs'] = $user->ignoreips;
		$GLOBALS['IgnoreKeywords'] = $user->ignorekeywords;

		$timezone = $user->usertimezone;
		$GLOBALS['TimeZoneList'] = $this->TimeZoneList($timezone);

		$GLOBALS['FormAction'] = 'Action=Save&UserID=' . $user->userid;

		$this->ParseTemplate('User_Edit_Own');
	}
}

?>
