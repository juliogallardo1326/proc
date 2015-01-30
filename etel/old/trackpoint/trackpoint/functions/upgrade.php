<?php
/**
* This file has the upgrade process in it.
*
* @version     $Id: upgrade.php,v 1.4 2005/11/08 05:35:44 chris Exp $
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
* Class for the upgrade process.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Upgrade extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Upgrade() {
	}

	/**
	* Process
	* Works out what's going on.
	*
	* @return void
	*/
	function Process() {
		$action = (isset($_GET['Action'])) ? $_GET['Action'] : '';
		
		if ($action != 'Upgrade' && $action != 'Backup') {
			$this->PrintHeader();
			echo '<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">';
		}

		$user = &GetUser();
		if (!$user->Admin()) {
			$GLOBALS['Error'] = 'An admin user must log in to perform a system upgrade. Please contact your administrator.<br/>Click <a href="index.php?Page=Logout">here to logout.</a><br/>';
			$this->ParseTemplate('ErrorMsg');
			$this->PrintFooter();
			return;
		}

		switch($action) {
			case 'UpdateConfig':
				$this->UpdateConfig();
			break;

			case 'Upgrade_Finished':
				?>
				<script language="javascript">
					window.opener.document.location='index.php?Page=Upgrade&Action=UpdateConfig';
					window.close();
				</script>
				<?php
				exit();
			break;
			
			case 'Upgrade':
				$this->UpgradeDb();
			break;

			case 'Backup_Finished':
				?>
				<script language="javascript">
					window.opener.document.location='index.php?Page=Upgrade&Action=Step2';
					window.close();
				</script>
				<?php
				exit();
			break;
			
			case 'Backup':
				$this->Backup();
			break;

			case 'Step2':
				$error = false;
				if (isset($_GET['Error'])) $error = $_GET['Error'];
				$this->PrintStep2($error);
			break;

			default:
				$session = &GetSession();
				$session->Remove('BackupsDone');
				$session->Remove('TableNames');
				$session->Remove('BackupFilename');
				$session->Remove('UpdatesDone');
				$error = false;
				if (isset($_GET['Error'])) $error = $_GET['Error'];
				$this->PrintStep1($error);
			break;
		}
		
		if ($action != 'Upgrade' && $action != 'Backup') {
			$this->PrintFooter();
		}
	}

	function UpdateConfig() {
		$api = $this->GetApi('Settings');
		$settings = array();
		foreach($api->Areas as $p => $area) {
			if (defined('TRACKPOINT_' . $area)) {
				$settings[$area] = constant('TRACKPOINT_' . $area);
			} else {
				$val = '';
				if ($area == 'SERVERTIMEZONE') $val = 'GMT';
				$settings[$area] = $val;
			}
		}
		$api->Set('Settings', $settings);
		$result = $api->Save();

		$this->PrintUpgradeHeader('3', 'Your system has been updated successfully.<br/>Please check the settings page for new options and the users area for new options.');
		$this->PrintUpgradeFooter();
	}

	function UpgradeDb() {
		$queries_todo = array();
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'search CHANGE COLUMN cookieid cookieid varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'referrers CHANGE COLUMN cookieid cookieid varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'campaigns CHANGE COLUMN cookieid cookieid varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'payperclicks CHANGE COLUMN cookieid cookieid varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'conversions CHANGE COLUMN cookieid cookieid varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'cookies CHANGE COLUMN cookieid cookieid varchar(255)';

		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'payperclicks ADD COLUMN ip varchar(20)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'campaigns ADD COLUMN ip varchar(20)';

		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'users ADD COLUMN usertimezone varchar(255)';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'users ADD COLUMN ignoreips text';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'users ADD COLUMN ignoresites text';
		$queries_todo[] = 'ALTER TABLE ' . TRACKPOINT_TABLEPREFIX . 'users ADD COLUMN ignorekeywords text';

		$queries_todo[] = 'UPDATE ' . TRACKPOINT_TABLEPREFIX . 'users SET usertimezone=\'GMT\'';

		$queries_todo[] = 'CREATE INDEX conversion_cookie on ' . TRACKPOINT_TABLEPREFIX . 'conversions (cookieid)';
		$queries_todo[] = 'CREATE INDEX conversion_session on ' . TRACKPOINT_TABLEPREFIX . 'conversions (sessionid)';

		$queries_todo[] = 'CREATE TABLE ' . TRACKPOINT_TABLEPREFIX . 'loghistory_sequence (id int not null auto_increment primary key)';
		$queries_todo[] = 'CREATE TABLE ' . TRACKPOINT_TABLEPREFIX . 'loghistory (logid int, file varchar(255), line int,   userid int, logtime int, logtype varchar(255), loglevel varchar(255), ip varchar(20), logentry text, sessionid varchar(255))';

		$queries_todo[] = 'INSERT INTO ' . TRACKPOINT_TABLEPREFIX . 'loghistory_sequence VALUES(1)';
		$queries_todo[] = 'CREATE INDEX loghistory_time ON ' . TRACKPOINT_TABLEPREFIX . 'loghistory(logtime)';

		$session = &GetSession();

		$this->PrintHeader(true);

		?>
			<script language="javascript">
				window.focus();
			</script>
		<?php

		$updates_done = $session->Get('UpdatesDone');
		if (!$updates_done) $updates_done = array();

		if ($updates_done == $queries_todo) {
			?>
			<script language="javascript">
				document.location='index.php?Page=Upgrade&Action=Upgrade_Finished';
			</script>
			<?php
			return;
		}

		$db = &GetDatabase();

		echo '<span class="MessageWhite"><b>Upgrading your database, please wait...</b><br/><br/>';

		foreach($queries_todo as $p => $query) {
			if (in_array($query, $updates_done)) {
				echo 'Query ' . $query . ' has been done.<br/>';
				continue;
			}
			echo '<br/>Running query ' . $query . '. Please wait...';

			echo '</span>';
			$this->PrintFooter(true);

			$result = $db->Query($query);
			if ($result) {
				$updates_done[] = $query;
			} else {
				list($reason, $errorlevel) = $db->GetError();
				$error = urlencode('Unable to run database query ' . $query . ': ' . $reason);
				?>
				<script language="javascript">
					window.opener.document.location = 'index.php?Page=Upgrade&Action=Step2&Error=<?php echo $error; ?>';
					window.opener.focus();
					window.close();
				</script>
				<?php
			}
			break;
		}
		$session->Set('UpdatesDone', $updates_done);
		?>
		<script language="javascript">
			setTimeout("document.location='index.php?Page=Upgrade&Action=Upgrade'", 1);
		</script>
		<?php
	}

	function StartUpgrade() {
		?>
			Click "Upgrade" to start upgrading up your database.<br/>
			<input type="button" class="field150" value="Upgrade" onclick="window.open('index.php?Page=Upgrade&Action=Upgrade', 'upgradewindow', 'width=350,height=270,left=400,top=270');">
		<?php
	}

	/**
	* Backup
	* Handles database backup.
	*
	* @return void
	*/
	function Backup() {
		$session = &GetSession();
		$this->PrintHeader(true);
		?>
			<script language="javascript">
				window.focus();
			</script>
		<?php

		$backupfile = TEMP_DIRECTORY . '/' . $session->Get('BackupFilename');
		$backups_done = $session->Get('BackupsDone');
		if (!$backups_done) $backups_done = array();

		$tables_todo = $session->Get('TableNames');
		if ($backups_done == $tables_todo) {
			?>
			<script language="javascript">
				document.location='index.php?Page=Upgrade&Action=Backup_Finished';
			</script>
			<?php
			return;
		}

		echo '<span class="MessageWhite"><b>Backing up your database, please wait...</b><br/><br/>';

		foreach($tables_todo as $p => $table) {
			if (in_array($table, $backups_done)) {
				echo 'Table ' . $table . ' has been backed up.<br/>';
				continue;
			}
			echo '<br/>Backing up table ' . $table . '. Please wait...';
			echo '</span>';
			$this->PrintFooter(true);

			$backedup = $this->BackupTable($table, $backupfile);
			if ($backedup) {
				$backups_done[] = $table;
			} else {
				$error = urlencode('Unable to backup database table ' . $table);
				?>
				<script language="javascript">
					window.opener.document.location = 'index.php?Page=Upgrade&Action=&Error=<?php echo $error; ?>';
					window.opener.focus();
					window.close();
				</script>
				<?php
			}
			break;
		}
		$session->Set('BackupsDone', $backups_done);
		?>
		<script language="javascript">
			setTimeout("document.location='index.php?Page=Upgrade&Action=Backup'", 1);
		</script>
		<?php
	}

	function BackupTable($tablename='', $filename='') {
		if ($tablename == '' || $filename == '') return false;
		if (!$fp = fopen($filename, 'a+')) return false;

		$drop_table = "DROP TABLE " . $tablename . ";\n";
		fputs($fp, $drop_table, strlen($drop_table));

		$qry = "SHOW CREATE TABLE " . $tablename;
		$result = mysql_query($qry);
		$create_table = mysql_result($result, 0, 1) . ";\n";

		fputs($fp, $create_table);

		$qry = "SELECT * FROM " . $tablename;
		$result = mysql_query($qry);
		while($row = mysql_fetch_assoc($result)) {
			$insert_query_fields = $insert_query_values = array();
			foreach($row as $name => $val) {
				$insert_query_fields[] = $name;
				$insert_query_values[] = str_replace("'", "\'", stripslashes($val));
			}
			$insert_query = "INSERT INTO " . $tablename . "(" . implode(',', $insert_query_fields) . ") VALUES ('" . implode("','", $insert_query_values) . "');\n";
			fputs($fp, $insert_query, strlen($insert_query));
		}

		$empty_lines = "\n";
		fputs($fp, $empty_lines, strlen($empty_lines));
		fclose($fp);
		return true;
	}

	function CreateBackup() {
		if (!is_dir(TEMP_DIRECTORY)) {
			if (!mkdir(TEMP_DIRECTORY, 0777)) {
				$error = urlencode('Temp directory (' . TEMP_DIRECTORY . ') is not writable. Please try again');
				?>
					<script language="javascript">
						window.location = 'index.php?Page=Upgrade&Action=Step1&Error=<?php echo $error; ?>';
					</script>
				<?php
				exit();
			}
		}
		if (!is_writable(TEMP_DIRECTORY)) {
			$error = urlencode('Temp directory (' . TEMP_DIRECTORY . ') is not writable. Please chmod this directory to 777 and try again');
			?>
				<script language="javascript">
					window.location = 'index.php?Page=Upgrade&Action=Step1&Error=<?php echo $error; ?>';
				</script>
			<?php
			exit();
		}

		if (!is_writable(TRACKPOINT_INCLUDES_DIRECTORY . '/config.php')) {
			$error = urlencode('Config file (' . TRACKPOINT_INCLUDES_DIRECTORY . '/config.php) is not writable. chmod this file to 777 and try again');
			?>
				<script language="javascript">
					window.location = 'index.php?Page=Upgrade&Action=Step1&Error=<?php echo $error; ?>';
				</script>
			<?php
			exit();
		}

		$starttime = date('d-m-Y.H-i', time());
		$backupfile = 'backup-' . $starttime . '.txt';

		$tables = $this->GetTableNames();
		$session = &GetSession();

		$session->Set('TableNames', $tables);
		$session->Set('BackupFilename', $backupfile);

		?>
			Click "Backup" to start backing up your database.<br/>
			<input type="button" class="field150" value="Backup" onclick="window.open('index.php?Page=Upgrade&Action=Backup', 'backupwindow', 'width=350,height=270,left=400,top=270');">
		<?php
	}

	function PrintStep2($error=false) {
		if (!$error) {
			$session = &GetSession();
			$backupfile = $session->Get('BackupFilename');
			$link = str_replace(TRACKPOINT_BASE_DIRECTORY, TRACKPOINT_APPLICATION_URL, TEMP_DIRECTORY . '/' . $backupfile);
			$msg = 'Your database has been backed up successfully. You can download it from here: <a href="' . $link . '" target="_blank">' . $link . '</a>';
			$this->PrintUpgradeHeader('2', $msg);
			$this->StartUpgrade();
		} else {
			$user = &GetUser();
			$msg = 'Problem updating your database:<br/>' . urldecode($error) . '<br/>';
			$msg .= 'Please post a support ticket through http://www.interspire.com/clientarea and include the error message above.<br/>';
			$this->PrintUpgradeHeader('2', $msg);
		}
		$this->PrintUpgradeFooter();
	}

	function PrintStep1($error=false) {
			if (!$error) {
				$this->PrintUpgradeHeader('1', '');
				$this->CreateBackup();
			} else {
				$this->PrintUpgradeHeader('1', 'Problem creating a backup of your database:<br/>' . urldecode($error) . '<br/><a href="index.php?Page=Upgrade">Click here to try again.</a><br/>');
			}
			$this->PrintUpgradeFooter();
	}

	function PrintUpgradeHeader($step='1', $msg) {
		?>
			<br/>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="heading1">Trackpoint NX Upgrade - Step <?php echo $step; ?> of 3</td>
			  </tr>
			<tr>
				<td class=body>
					<br>
					<?php echo $msg; ?>
				</td>
			</tr>
			  <tr>
				<td class="body"><br>
		<?php
	}

	function PrintUpgradeFooter() {
		?>
				<br><br>
			</td>
		  </tr>
		</table>
		<?php
	}

	function GetTableNames() {
		// get a list of tables that this copy of AL uses
		$qry = "SHOW TABLES LIKE '" . TRACKPOINT_TABLEPREFIX . "%'";
		$result = mysql_query($qry);
		$return = array();
		while($row = mysql_fetch_assoc($result)) {
			$return[] = $row['Tables_in_' . TRACKPOINT_DATABASE_NAME . ' (' . TRACKPOINT_TABLEPREFIX . '%)'];
		}
		return $return;
	}
}

?>
