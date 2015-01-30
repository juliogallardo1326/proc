<?php
/**
* This file has the create-bulk-ppc functions in it.
*
* @version     $Id: createppcbulk.php,v 1.1 2005/10/20 03:27:23 chris Exp $
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
* Class for the Create PPC Bulk page.
* Handles everything for you, depending on the action. Processes information in an iframe for an easy way to keep creating ppc links without too much retyping.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class CreatePPCBulk extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function CreatePPCBulk() {
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
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$userid = $thisuser->userid;
		$switched_user = $session->Get('SwitchUser');
		if ($switched_user) $userid = $switched_user;

		if ($thisuser->Admin()) {
			$db = &GetDatabase();
			$qry = "SELECT userid, username FROM " . TRACKPOINT_TABLEPREFIX . "users ORDER BY username";
			$result = $db->Query($qry);
			$usercount = 0;
			$GLOBALS['UserList'] = '';
			while($row = $db->Fetch($result)) {
				$usercount++;
				$selected = '';
				if ($row['userid'] == $userid) $selected = ' SELECTED';

				$GLOBALS['UserList'] .= '<option value="' . $row['userid'] . '"' . $selected . '>' . $row['username'] . '</option>';
			}
			if ($usercount > 1) {
				$GLOBALS['SelectUser'] = $this->ParseTemplate('CreatePPCBulk_ChooseUser', true, false);
			}
		}

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : null;

		switch($action) {
			case 'upload':
				echo '<span style="font-family: tahoma; font-size: 12px;">';
				$user = $thisuser->userid;
				if (isset($_POST['userid']) && $thisuser->Admin()) {
					$user = $_POST['userid'];
				}

				if (empty($_FILES) || !isset($_FILES['ppcfile'])) {
					echo GetLang('ChooseFileToUpload');
					echo '</span>';
					break;
				}
				$tmpfile = $_FILES['ppcfile']['tmp_name'];
				if (!is_uploaded_file($tmpfile)) {
					echo GetLang('ChooseFileToUpload');
					echo '</span>';
					break;
				}
				$newfilename = TEMP_DIRECTORY . '/trackpoint_ppc_file_' . $user . md5(time()) . '.tmp';
				if (!move_uploaded_file($tmpfile, $newfilename)) {
					echo GetLang('UnableToReadFile');
					echo '</span>';
					break;
				}
				if (!$handle = fopen($newfilename, 'r')) {
					echo GetLang('UnableToReadFile');
					echo '</span>';
					break;
				}

				$outputfile = TEMP_DIRECTORY . '/ppc_file_' . $user . '.' . time() . '.csv';
				$output_filehandle = fopen($outputfile, 'w');

				$fieldseparator = (isset($_POST['fieldseparator']) && $_POST['fieldseparator']) ? htmlentities($_POST['fieldseparator']) : ',';

				if (strtolower($fieldseparator) == "tab") $fielseparator = "\t";

				$invalid_rows = array();

				while (($data = fgetcsv($handle, 1000, $fieldseparator)) !== FALSE) {
					$num = count($data);
					if ($num != 4) {
						array_push($invalid_rows, $data);
						continue;
					}

					$search_engine = trim($data[0]);
					$ppc_name = trim($data[1]);
					$landing_page = trim($data[2]);
					$cpc = 0;
					if (is_numeric($data[3])) {
						$cpc = (float)trim($data[3]);
					}

					$querystring = '';
					if ($user != 1) {
						$querystring .= 'u=' . $user . '&';
					}
					if (isset($_POST['EncodeInfo'])) {
						$querystring .= 'e=' . stripslashes($search_engine);
						$querystring .= '&n=' . stripslashes($ppc_name);
						$querystring .= '&c=' . (float)$cpc;
						$querystring = 'ppce=' . urlencode(base64_encode($querystring));
					} else {
						$querystring .= 'ppc=' . urlencode(stripslashes($search_engine));
						$querystring .= '&name=' . urlencode(stripslashes($ppc_name));
						$querystring .= '&c=' . (float)$cpc;
					}

					$url = stripslashes($landing_page) . '/?';

					if (strpos($landing_page, '?') !== false) {
						$url = stripslashes($landing_page) . '&' . $querystring;
					} else {
						$landingurl = stripslashes($landing_page);
						$urlparts = explode('/', $landingurl);
						$filename = array_pop($urlparts);
						if (strpos($filename, '.') !== false || substr($landingurl, -1) == '/') {
							$url = $landingurl . '?' . $querystring;
						} else {
							$url = $landingurl . '/?' . $querystring;
						}
					}

					$output_data = $data;
					$output_data[] = $url;
					$outputline = implode($fieldseparator, $output_data) . "\n";
					fputs($output_filehandle, $outputline, strlen($outputline));
				}
				fclose($handle);
				fclose($output_filehandle);
				unlink($newfilename);

				chmod($outputfile, 0646);

				if (filesize($outputfile) > 0) {
					$output_url = str_replace(TEMP_DIRECTORY, TRACKPOINT_APPLICATION_URL . '/temp', $outputfile);
					echo sprintf(GetLang('Bulk_Conversion_PPC_Finished'), $output_url) . '<br/>';

					if (!empty($invalid_rows)) echo '<br/>';
				}

				if (!empty($invalid_rows)) {
					echo GetLang('Bulk_PPC_RowsInvalid') . '<br/><br/>';
					foreach($invalid_rows as $p => $data) {
						echo implode('<br/>-', $data) . '<br/>';
					}
				}
				echo '</span>';
				break;
			
			default:
				Trackpoint_Functions::Process();
		}
	}
}
?>
