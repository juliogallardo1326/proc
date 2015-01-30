<?php
/**
* This file has the user editing forms in it.
*
* @version     $Id: users.php,v 1.20 2005/11/03 00:33:01 chris Exp $
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
* Class for the users page.
* This handles processing of users (including 'Manage Account' if the user is not an admin user), creating, editing and so on.
* Also handles Deletion of statistics for each user.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Users extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Users() {
	}

	/**
	* Process
	* Works out what's going on.
	* The API does the loading, saving, updating - this page just displays the right form(s), checks password validation and so on.
	* After that, it'll print a success/failure message depending on what happened.
	* It also checks to make sure that you're an admin before letting you add or delete.
	* It also checks you're not going to delete your own account.
	* If you're not an admin user, it won't let you edit anyone elses account and it won't let you delete your own account either.
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see GetUser
	* @see GetLang
	* @see User::Set
	* @see PrintEditForm
	* @see CheckUserSystem
	* @see PrintManageUsers
	* @see DeleteStatistics
	* @see User::Find
	* @see User::Admin
	* @see PrintFooter
	*
	* @return void
	*/
	function Process() {
		
		$action = (isset($_GET['Action'])) ? $_GET['Action'] : '';
		
		if ($action != 'DelStats') {
			$this->PrintHeader();
			$this->ParseTemplate('Menu');
		} else {
			$this->PrintHeader(true);
		}

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$db = &GetDatabase();

		switch($action) {

			case 'SwitchTo':
				if (!$thisuser->Admin()) {
					$GLOBALS['Error'] = GetLang('NoAccess');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					break;
				}

				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;

				$session->Remove('SwitchUser');
				$session->Remove('SwitchUserName');
				$session->Remove('FirstHit');
				$session->Remove('IgnoreDetails');

				$user = &GetUser($userid);
				$username = $user->Get('username');

				if ($userid != $thisuser->userid) {
					$session->Set('SwitchUser', $userid);
					$session->Set('SwitchUserName', $user->username);
				}

				$GLOBALS['Success'] = sprintf(GetLang('SwitchUser_Success'), $username);
				$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);
				$this->PrintManageUsers();
			break;

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

				$to_check = array();
				foreach(array('status' => 'LastActiveUser', 'admin' => 'LastAdminUser') as $area => $desc) {
					if (!isset($_POST[$area])) $to_check[] = $desc;
				}

				$error = $this->CheckUserSystem($userid, $to_check);

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
							// In case we change ip's or domains we're going to ignore, we need to recalculate the first hit and ignore criteria.
							$session->Remove('FirstHit');
							$session->Remove('IgnoreDetails');
						}

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

			case 'Add':
				$this->PrintEditForm(0);
			break;

			case 'Delete':
				if (!$thisuser->Admin()) {
					$template = 'ErrorMsg';
					$GLOBALS['Error'] = GetLang('NoAccess');
				} else {
					$error = false;
					$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;
					if ($userid == $thisuser->userid) {
						$template = 'ErrorMsg';
						$GLOBALS['Error'] = GetLang('User_CantDeleteOwn');
					} else {
						$user = &GetUser($userid);
						if (!$user) {
							$template = 'ErrorMsg';
							$GLOBALS['Error'] = GetLang('UserNotDeleted');
						}
						if ($user) {
							$error = $this->CheckUserSystem($userid);
							if (!$error) {
								$result = $user->Delete();
								if ($result) {
									$template = 'SuccessMsg';
									$GLOBALS['Success'] = GetLang('UserDeleted');
								} else {
									$template = 'ErrorMsg';
									$GLOBALS['Error'] = GetLang('UserNotDeleted');
								}
							} else {
								$template = 'ErrorMsg';
								$GLOBALS['Error'] = $error;
							}
						}
					}
				}
				$GLOBALS['Message'] = $this->ParseTemplate($template, true, false);
				$this->PrintManageUsers();
			break;

			case 'Create':
				$user = &New User();
				if (!$user->Find(stripslashes($_POST['tp_username']))) {
					foreach(array('fullname', 'emailaddress', 'status', 'admin', 'ignoresites', 'ignoreips', 'ignorekeywords', 'usertimezone') as $area) {
						$val = (isset($_POST[$area])) ? $_POST[$area] : '';
						$user->Set($area, stripslashes($val));
					}

					$username = (isset($_POST['tp_username'])) ? $_POST['tp_username'] : '';
					$user->Set('username', $username);

					$pass = (isset($_POST['tp_password'])) ? $_POST['tp_password'] : '';
					$user->Set('password', $pass);

					$result = $user->Create();
					if ($result) {
						$GLOBALS['Success'] = GetLang('UserCreated');
						$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg', true, false);
						$this->PrintManageUsers();
						break;
					}
					$GLOBALS['Error'] = GetLang('UserNotCreated');
				} else {
					$GLOBALS['Error'] = GetLang('UserAlreadyExists');
				}
				$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);

				$details = array();
				foreach(array('FullName', 'EmailAddress', 'Status', 'Admin', 'IgnoreSites', 'IgnoreIPs', 'IgnoreKeywords', 'UserTimeZone') as $area) {
					$lower = strtolower($area);
					$val = (isset($_POST[$lower])) ? $_POST[$lower] : '';
					$details[$area] = stripslashes($val);
				}

				$this->PrintEditForm(0, $details);
			break;

			case 'DelStats_Cancelled':
			case 'Edit':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;
				$this->PrintEditForm($userid);
			break;
			
			case 'DelStats_Finished':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;
				?>
				<script language="javascript">
					window.opener.document.location='index.php?Page=Users&Action=Edit&StatsCleaned=1&UserID=<?php echo $userid; ?>';
					window.close();
				</script>
				<?php
				exit();
			break;
			
			case 'DelStats':
				$userid = (isset($_GET['UserID'])) ? $_GET['UserID'] : 0;
				$this->DeleteStats($userid);
			break;

			default:
				$this->PrintManageUsers();
			break;
		}
		
		if ($action != 'DelStats') {
			$this->PrintFooter();
		} else {
			$this->PrintFooter(true);
		}
	}

	/**
	* DeleteStats
	* Handles deleting of statistics for the user specified. If there is no user specified, it's for "this" user (one logged in).
	* Deletes 500 at a time.
	*
	* @param userid Userid to delete statistics for. If none is present, gets it from the session.
	*
	* @see GetSession
	* @see Session::Get
	*
	* @return void
	*/
	function DeleteStats($userid=0) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$NumberRecordsToDelete = 500;

		if ($userid <= 0 || !$thisuser->Admin()) {
			if ($userid != $thisuser->userid) {
				$GLOBALS['ErrorMessage'] = GetLang('UnableToDeleteStats');
				?>
				<script language="javascript">
				window.opener.document.location='index.php?Page=Users&Action=Edit&Error=<?php echo urlencode($GLOBALS['ErrorMessage']); ?>&UserID=<?php echo $userid; ?>';
				window.close();
				</script>
				<?php
				return false;
			}
		}
		
		$GLOBALS['UserID'] = $userid;
		
		// this relates to the database tables (see below where we set 'tablename'), so be careful changing this.
		// it is used in the session to see which stats we have already cleared.
		$stats_to_clear = array('campaigns', 'referrers', 'search', 'payperclicks', 'conversions');
		
		$statscleared = $session->Get('StatsCleared');
		if (!$statscleared) {
			$statscleared = array();
		}

		if ($statscleared == $stats_to_clear) {
			?>
			<script language="javascript">
				document.location='index.php?Page=Users&Action=DelStats_Finished&UserID=<?php echo $userid; ?>';
			</script>
			<?php
			return;
		}

		$statsdeleted_report = $session->Get('StatsDeletedReport');
		if (!$statsdeleted_report) {
			$statsdeleted_report = array();
		}
		
		$statscleared_report = $session->Get('StatsClearedReport');
		if (!$statscleared_report) {
			$statscleared_report = array();
		}

		foreach($stats_to_clear as $stattype) {
			// if we've already done it, find the next one.
			if (in_array($stattype, $statscleared)) {
				continue;
			}
			switch($stattype) {
				case 'campaigns':
					$key = 'campaignid';
					$table = 'campaigns';
				break;
				case 'referrers':
					$key = 'referrerid';
					$table = 'referrers';
				break;
				case 'search':
					$key = 'searchid';
					$table = 'search';
				break;
				case 'payperclicks':
					$key = 'ppcid';
					$table = 'payperclicks';
				break;
				case 'conversions':
					$key = 'conversionid';
					$table = 'conversions';
				break;
			}
			break;
		}
		$db = &GetDatabase();

		if (strtolower(TRACKPOINT_DATABASE_TYPE) == 'pgsql') {
			$delete_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . $table . " WHERE EXISTS (SELECT " . $key . " FROM " . TRACKPOINT_TABLEPREFIX . $table . " WHERE userid='" . addslashes($userid) . "' LIMIT " . $NumberRecordsToDelete . ")";
		} else {
			$delete_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . $table . " WHERE userid='" . addslashes($userid) . "' LIMIT " . $NumberRecordsToDelete;
		}

		if (!in_array($table, array_keys($statscleared_report))) {
			$count_query = "SELECT COUNT(" . $key . ") AS count FROM " . TRACKPOINT_TABLEPREFIX . $table . " WHERE userid='" . addslashes($userid) . "'";
			$count_check_result = $db->Query($count_query);
			$count_check = $db->FetchOne($count_check_result, 'count');
			
			$statscleared_report[$table] = $count_check;
			$session->Set('StatsClearedReport', $statscleared_report);
		} else {
			$count_check = $statscleared_report[$table];
		}

		if (!in_array($table, array_keys($statsdeleted_report))) {
			$statsdeleted_report[$table] = 0;
		}

		$statsdeleted_report[$table] += $NumberRecordsToDelete;
		
		if (($statsdeleted_report[$table] - $NumberRecordsToDelete) > $count_check) {
			$statscleared[] = $table;
			$session->Set('StatsCleared', $statscleared);
		} else {
			$deletereport = '';
			foreach($stats_to_clear as $stattype) {
				if ($table == $stattype) {
					$total = $statscleared_report[$table];
					$left = ($count_check - $statsdeleted_report[$table]);
					$numleft = ($total-$left);
					if ($numleft > $total) $numleft = $total;
					$GLOBALS['Report'] = sprintf(GetLang('ClearingStats_InProgress_' . $stattype), $this->FormatNumber($numleft), $this->FormatNumber($total));
				} else {
					if (in_array($stattype, array_keys($statscleared_report))) {
						$GLOBALS['Report'] = sprintf(GetLang('ClearingStats_Done_' . $stattype), $this->FormatNumber($statscleared_report[$stattype])); 
					} else {
						$GLOBALS['Report'] = GetLang('ClearingStats_Todo_' . $stattype);
					}
				}
				$deletereport .= $this->ParseTemplate('StatsClearingWindow_Entry', true, false);
			}
			$GLOBALS['Report'] = $deletereport;
			$this->ParseTemplate('StatsClearingWindow');
			$db->Query($delete_query);
		}
		
		if (($statsdeleted_report[$table] - $NumberRecordsToDelete) > $count_check) {
			$statsdeleted_report[$table] = $count_check;
		}
		$session->Set('StatsDeletedReport', $statsdeleted_report);
		
		?>
		<script language="javascript">
			setTimeout("document.location='index.php?Page=Users&Action=DelStats&UserID=<?php echo $userid; ?>'", 1);
		</script>
		<?php
	}

	/**
	* PrintManageUsers
	* Prints a list of users in the system.
	* If you are not an admin, then it will only print your user in the list.
	* If you are an admin, it will also check your license to make sure you're not exceeding your allowed limit.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	*
	* @return void
	*/
	function PrintManageUsers() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$db = &GetDatabase();

		if ($thisuser->Admin()) {
			$licensecheck = tpk23twgezm2();
			$GLOBALS['UserReport'] = $licensecheck;
			$qry = "SELECT * FROM " . TRACKPOINT_TABLEPREFIX . "users ORDER BY username";
		} else {
			$qry = "SELECT * FROM " . TRACKPOINT_TABLEPREFIX . "users WHERE userid='" . $thisuser->userid . "'";
		}

		$result = $db->Query($qry);
		if (!$result) {
			list($error, $level) = $db->GetError();
			trigger_error($error, $level);
			return false;
		}

		$switched_userid = $thisuser->userid;
		$switch_user = $session->Get('SwitchUser');
		if ($switch_user && $thisuser->Admin()) {
			$switched_userid = $switch_user;
		}

		$display = '';
		while($row = $db->Fetch($result)) {
			$GLOBALS['UserName'] = $row['username'];
			$GLOBALS['FullName'] = $row['fullname'];
			$GLOBALS['Status'] = ($row['status'] == 1) ? 'Active' : 'Inactive';
			$GLOBALS['Admin'] = ($row['admin'] == 1) ? 'Administrator' : 'Regular User';

			$action = '<a href="index.php?Page=Users&Action=Edit&UserID=' . $row['userid'] . '">' . GetLang('Edit') . '</a>';
			if ($thisuser->Admin()) {
				$action .= '&nbsp;&nbsp;<a href="javascript: ConfirmDelete(' . $row['userid'] . ');">' . GetLang('Delete') . '</a>';
				if ($row['userid'] == $switched_userid) {
					$action .= '&nbsp;&nbsp;<a class="Disabled" title="' . GetLang('SwitchTo_Disabled_Title') . '">' . GetLang('SwitchTo') . '</a>';
				} else {
					$action .= '&nbsp;&nbsp;<a href="index.php?Page=Users&Action=SwitchTo&UserID=' . $row['userid'] . '" title="' . GetLang('SwitchTo_Title') . '">' . GetLang('SwitchTo') . '</a>';
				}
			}
			$GLOBALS['UserAction'] = $action;

			$template = $this->ParseTemplate('Users_List_Row', true, false);
			$display .= $template;
		}

		if ($thisuser->Admin()) {
			$GLOBALS['Users_AddButton'] = '<form name="" method="" action=""><input id="createAccountButton" type="button" class="smallbutton" value="' . GetLang('UserAdd') . '" onclick=\'document.location="index.php?Page=Users&Action=Add"\';></form>';
		}

		$user_list = $this->ParseTemplate('Users', true, false);

		$user_list = str_replace('%%TPL_Users_List_Row%%', $display, $user_list);

		echo $user_list;
	}

	/**
	* PrintEditForm
	* Prints the user editing form in full. Unlike 'ManageAccount', you can also set permissions here.
	* Also handles creating users. If there is no userid passed in, it makes sure you're an admin and assumes you're going to add a new user.
	* If you are not an admin, you get your own form to edit (same as ManageAccount).
	*
	* @param userid Userid to edit. If none is present, it will check your permissions and either display the 'New User' or the 'Edit Own User' form.
	* @param details In case an element was missing (eg name), this holds previous data so it can prefill the form for you.
	*
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see GetUser
	* @see User_API::Admin
	*/
	function PrintEditForm($userid=0, $details = array()) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!$thisuser->Admin()) {
			if ($userid != $thisuser->userid) {
				$GLOBALS['ErrorMessage'] = GetLang('NoAccess');
				$this->ParseTemplate('AccessDenied');
				return false;
			}
		}
		
		if (isset($_GET['Error'])) {
			$GLOBALS['Error'] = stripslashes(urldecode($_GET['Error']));
			$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg');
		}

		if (isset($_GET['StatsCleaned'])) {
			$report = '';
			$stats_report = $session->Get('StatsDeletedReport');
			if ($stats_report) {
				foreach($stats_report as $type => $count) {
					if ($count == 1) {
						$report .= GetLang('RemovedRecord_' . strtolower($type)) . '<br/>';
					} else {
						$report .= sprintf(GetLang('RemovedRecords_' . strtolower($type)), $this->FormatNumber($count)) . '<br/>';
					}
				}
				$GLOBALS['Success'] = $report;
				$GLOBALS['Message'] = $this->ParseTemplate('SuccessMsg');
			}
			$session->Remove('StatsClearedReport');
			$session->Remove('StatsDeletedReport');
			$session->Remove('StatsCleared');
		}

		if ($userid > 0) {
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

			if (!$thisuser->Admin()) {
				$this->ParseTemplate('User_Edit_Own');
				return true;
			}

			$GLOBALS['StatusChecked'] = ($user->Status()) ? ' CHECKED' : '';
			$GLOBALS['AdminChecked'] = ($user->Admin()) ? ' CHECKED' : '';

			$this->ParseTemplate('User_Edit');
		} else {
			$GLOBALS['FormAction'] = 'Action=Create';

			if (!empty($details)) {
				foreach($details as $area => $val) {
					$GLOBALS[$area] = stripslashes($val);
				}
			}

			$timezone = (isset($details['UserTimeZone'])) ? $details['UserTimeZone'] : TRACKPOINT_SERVERTIMEZONE;
			$GLOBALS['TimeZoneList'] = $this->TimeZoneList($timezone);

			$this->ParseTemplate('User_Add');
		}
	}

	/**
	* CheckUserSystem
	* Checks that the user you're editing or deleting isn't the last 'X' in the system.
	*
	* @param userid Userid to check. Must pass in a userid to check.
	* @param to_check Area(s) to check.
	*
	* @see GetUser
	* @see User_API::LastAdminUser
	* @see User_API::LastActiveUser
	* @see User_API::LastUser
	*
	* @return mixed Returns false if there is no error, otherwise returns the appropriate error message depending on what you're checking.
	*/
	function CheckUserSystem($userid=0, $to_check = array('LastActiveUser', 'LastUser', 'LastAdminUser')) {
		$return_error = false;

		$user_system = &GetUser($userid);
		
		if (in_array('LastAdminUser', $to_check)) {
			if (!$return_error && $user_system->LastAdminUser()) {
				$return_error = GetLang('LastAdminUser');
			}
		}

		if (in_array('LastActiveUser', $to_check)) {
			if ($user_system->LastActiveUser()) {
				$return_error = GetLang('LastActiveUser');
			}
		}

		if (in_array('LastUser', $to_check)) {
			if (!$return_error && $user_system->LastUser()) {
				$return_error = GetLang('LastUser');
			}
		}

		return $return_error;
	}
}

?>
