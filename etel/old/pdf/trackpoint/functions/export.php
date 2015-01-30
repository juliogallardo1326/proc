<?php
/**
* This file has the CSV export functions in it.
* This handles the popup window, what fields to print, formatting.
* Checks whether there are stats to export and so on.
*
* @version     $Id: export.php,v 1.17 2005/10/20 03:32:14 chris Exp $
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
* Class for the export page.
* This handles the popup window, what fields to print, formatting.
* Checks whether there are stats to export and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Export extends TrackPoint_Functions {

	/**
	* @var ExportFields An array of fields to export per area.
	*
	* @see ExportStats
	* @see Process
	*/
	var $ExportFields = array(
		'campaign' => array('campaignsite', 'campaignname', 'visits', 'conv', 'percent', 'revenue', 'cost', 'roi'),
		'ppc' => array('searchenginename', 'ppcname', 'visits', 'conv', 'percent', 'revenue', 'cost', 'roi'),
		'search' => array('searchenginename', 'keywords', 'visits', 'conv', 'percent', 'revenue', 'cost', 'roi', 'landingpage'),
		'referrer' => array('domain', 'url', 'visits', 'conv', 'percent', 'revenue', 'cost', 'roi', 'landingpage')
	);

	/**
	* @var FormatNumbers Which fields to format (which are numbers).
	*
	* @see ExportStats
	* @see FormatNumber
	* @see Process
	*/
	var $FormatNumbers = array(
		'visits',
		'conv'
	);
	
	/**
	* @var FormatNumbers_Decimal Which fields to format (which are numbers and need decimal points).
	*
	* @see ExportStats
	* @see FormatNumber
	* @see Process
	*/
	var $FormatNumbers_Decimal = array(
		'revenue',
		'percent',
		'roi'
	);
	
	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function Export() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Handles the actions needed at each stage of the process.
	*
	* @see PrintStartExport
	* @see PrintOptions
	* @see ExportStats
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$this->GetSearchUser();

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		if ($action != 'export') {
			$this->PrintHeader();
			$this->ParseTemplate('Menu');
		} else {
			$this->PrintHeader(true);
		}

		switch($action) {
			case 'export_finished':
				?>
				<script language="javascript">
					window.opener.document.location='index.php?Page=Export&Action=Finished';
					window.close();
				</script>
				<?php
				exit();
			break;
			
			case 'export':
				$this->ExportStats();
			break;
			
			case 'step2':
				$exportreport = $this->ParseTemplate('ExportReport_Start', true, false);

				$ignoreips = $this->GetIgnoreDetails();
				$ignorereferrers = $this->GetIgnoreDetails('Referrers');
				$ignorekeywords = $this->GetIgnoreDetails('Keywords');

				$exportareas = array();
				foreach($this->ExportTypes as $area) {
					if (isset($_POST[$area])) {
						$exportareas[] = $area;
						$GLOBALS['Entry'] = GetLang($area);
						$exportreport .= $this->ParseTemplate('ExportReport_Item', true, false);
					}
				}
				
				$GLOBALS['CalendarInfo'] = $this->GetCalendarInfo($_POST['Calendar']);
				$exportreport .= $this->ParseTemplate('ExportReport_Finish', true, false);
				
				if (empty($exportareas)) {
					$GLOBALS['Error'] = GetLang('Export_ChooseType');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->PrintOptions();
					break;
				}
				
				$this->CalculateCalendarRestrictions($_POST['Calendar']);
				$foundstats = false;
				
				// make sure there are some statistics to export!
				foreach($exportareas as $area) {
					$GLOBALS[ucwords($area).'Checked'] = 'CHECKED'; // in case we need to show step 1 again, check the appropriate boxes.
					$query = "SELECT COUNT(" . $this->ExportDbInfo[$area]['key'] . ") AS count FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$area]['table'] . " WHERE " . $this->SearchUserID;
					if ($this->CalendarRestrictions) $query .= " AND " . $this->CalendarRestrictions;
					if ($ignoreips) {
						$query .= " AND " . $ignoreips;
					}
					if ($area == 'referrer') {
						if ($ignorereferrers) {
							$query .= " AND " . $ignorereferrers;
						}
					}
					if ($area == 'search') {
						if ($ignorekeywords) {
							$query .= " AND " . $ignorekeywords;
						}
					}
					$result = $this->Db->Query($query);
					$count = $this->Db->FetchOne($result, 'count');
					if ($count > 0) {
						$foundstats = true;
						break;
					}
				}
				
				if (!$foundstats) {
					$GLOBALS['Error'] = GetLang('Export_NoStatsFound');
					$GLOBALS['Message'] = str_replace('<br/>', '', $this->ParseTemplate('ErrorMsg', true, false)) . '<br/>';
					$this->PrintOptions();
					break;
				}
				
				$GLOBALS['ExportReport'] = $exportreport;
				
				$exportsettings = array();
				$exportsettings['Calendar'] = $_POST['Calendar'];
				$exportsettings['Areas'] = $exportareas;
				$exportsettings['Filename'] = 'export_' . str_replace(' ', '_', strtolower(date(GetLang('DateFormat')))) . '_' . date('His') . '.' . $thisuser->userid . '.csv';
				$session->Set('ExportSettings', $exportsettings);
				
				$this->PrintStartExport();
			break;
			
			case 'finished':
				$exportsettings = $session->Get('ExportSettings');

				$GLOBALS['ExportLink'] = '.' . str_replace(TRACKPOINT_BASE_DIRECTORY, '', TEMP_DIRECTORY) . '/' . $exportsettings['Filename'];
				$this->ParseTemplate('Export_Finished');
			break;
			
			default:
				foreach($this->ExportTypes as $area) {
					if (isset($_GET['Area']) && strtolower($_GET['Area']) == $area) {
						$GLOBALS[ucwords($area).'Checked'] = 'CHECKED';
					}
					if (!isset($_GET['Area'])) {
						$GLOBALS[ucwords($area).'Checked'] = 'CHECKED';
					}
				}
				$this->PrintOptions();
			break;
		}
		
		if ($action != 'export') {
			$this->PrintFooter();
		} else {
			$this->PrintFooter(true);
		}
	}

	/**
	* PrintStartExport
	* Prints out the template for step 2 of the process.
	*
	* @return void
	*/
	function PrintStartExport() {
		$this->ParseTemplate('Export_Step2');
	}
	
	/**
	* PrintOptions
	* Prints out the template for step 1 of the process.
	* Removes old session variables (if they exist) and sets the calendar settings if it needs to.
	*
	* @return void
	*/
	function PrintOptions() {
		$session = &GetSession();
		$session->Remove('ExportSettings');
		$session->Remove('StatsExported');
		$session->Remove('StatsTotals');
		$session->Remove('ExportCampaignCost');
		$session->Set('ExportCampaignCost', 0);

		if (isset($_POST['Calendar'])) {
			$this->SetupCalendar(null, $_POST['Calendar']);
		} else {
			$this->SetupCalendar();
		}
		$this->ParseTemplate('Export');
	}
	
	/**
	* ExportStats
	* Does all of the work for exporting.
	* Prints out a status report as it goes, saves the file and of course does all of the calculations.
	*
	* @see FormatNumbers_Decimal
	* @see FormatNumbers
	* @see ExportFields
	*
	* @return void
	*/
	function ExportStats() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$exportsettings = $session->Get('ExportSettings');

		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		$this->CalculateCalendarRestrictions($exportsettings['Calendar']);
		
		$StatsToExport = $exportsettings['Areas'];
		
		$NumberRecordsToExport = 500;
		
		$statsexported = $session->Get('StatsExported');
		if (!$statsexported) {
			$statsexported = array();
		}

		$statstotals = $session->Get('StatsTotals');
		if (!$statstotals) {
			$statstotals = array();
		}

		$exportfilefp = fopen(TEMP_DIRECTORY . '/' . $exportsettings['Filename'], 'a+');

		if ($statsexported == $StatsToExport) {
			$line = "\r\n" . str_repeat('-', 100) . "\r\n";
			$line .= '"' . GetLang('GrandTotal') . '",,"' . GetLang('Export_Visits') . '","' . GetLang('Export_Conv') . '","' . GetLang('Export_Percent') . '","' . GetLang('Export_Revenue') . '","' . GetLang('Export_Cost') . '","' . GetLang('Export_Roi') . '"';
			$line .= "\r\n" . str_repeat('-', 100) . "\r\n";
			
			$totalvisits = array_sum($statstotals['Visits']);
			$totalrevenue = array_sum($statstotals['Revenue']);
			$totalconversions = array_sum($statstotals['Conversions']);
			$totalcost = array_sum($statstotals['Cost']);
			
			$conversionpercent = ($totalconversions / $totalvisits)*100;
			$roipercent = ($totalcost > 0) ? (($totalrevenue / $totalcost)*100) : 0;
			
			$line .= ',,"' . $this->FormatNumber($totalvisits) . '","' . $this->FormatNumber($totalconversions) . '","' . $this->FormatNumber($conversionpercent, 2) . '","' . $this->FormatNumber($totalrevenue, 2) . '","' . $this->FormatNumber($totalcost, 2) . '","' . $this->FormatNumber($roipercent, 2) . '"' . "\r\n";
			fputs($exportfilefp, $line);
			fclose($exportfilefp);
			?>
			<script language="javascript">
				document.location='index.php?Page=Export&Action=Export_Finished';
			</script>
			<?php
			return;
		}
		
		$export_report = '';
		
		$exportarea = false;
		foreach($StatsToExport as $area) {
			if (in_array($area, $statsexported)) {
				$GLOBALS['Report'] = GetLang('Export_' . ucwords($area) . '_Finished');
				$export_report .= $this->ParseTemplate('ExportStats_Window_Entry', true, false);
				continue;
			}
			$exportarea = $area;
			break;
		}
		
		if (!$exportarea) {
			?>
			<script language="javascript">
				document.location='index.php?Page=Export&Action=Export_Finished';
			</script>
			<?php
			return;
		}
		
		if (!isset($exportsettings[$exportarea . '_TopCount'])) {
			$count_query = "SELECT COUNT(DISTINCT " . $this->ExportDbInfo[$exportarea]['toplevel'] . ") AS count FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$exportarea]['table'] . " WHERE " . $this->SearchUserID;
			if ($this->CalendarRestrictions) $count_query .= " AND " . $this->CalendarRestrictions;
			if ($ignoreips) {
				$count_query .= " AND " . $ignoreips;
			}
			if ($exportarea == 'referrer') {
				if ($ignorereferrers) {
					$count_query .= " AND " . $ignorereferrers;
				}
			}
			if ($exportarea == 'search') {
				if ($ignorekeywords) {
					$query .= " AND " . $ignorekeywords;
				}
			}
			$result = $this->Db->Query($count_query);
			$TopCount = $this->Db->FetchOne($result, 'count');
			if ($TopCount > 0) {
				$exportfileheader = GetLang('Export_Header_' . ucwords($exportarea)) . "\r\n\r\n";
				fputs($exportfilefp, $exportfileheader);
			}
		} else {
			$TopCount = $exportsettings[$exportarea . '_TopCount'];
		}
		
		$MainOffset = (isset($exportsettings[$exportarea . '_TopStart'])) ? $exportsettings[$exportarea . '_TopStart'] : 0;

		$GLOBALS['Report'] = sprintf(GetLang('Export_' . ucwords($exportarea) . '_InProgress'), $this->FormatNumber($MainOffset), $this->FormatNumber($TopCount));
		$export_report .= $this->ParseTemplate('ExportStats_Window_Entry', true, false);

		if ($MainOffset >= $TopCount) {
			$statsexported[] = $exportarea;
			$session->Set('StatsExported', $statsexported);
			$suboffset = 0;
			$MainOffset = 0;
			$TopCount = 0;
			$SubCount = 0;

			$qry = "SELECT COUNT(" . $this->ExportDbInfo[$exportarea]['key'] . ") AS visits, SUM(hasconversion) AS conversions, (SUM(hasconversion) / (COUNT(" . $this->ExportDbInfo[$exportarea]['key'] . ")+0.0)*100) AS percent, SUM(amount) AS revenue";
			if ($exportarea == 'ppc' || $exportarea == 'campaign') {
				$qry .= ", SUM(cost) AS cost, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi";
			} else {
				$qry .= ", '0' AS cost, '0' AS roi";
			}
			$qry .= " FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$exportarea]['table'] . " WHERE " . $this->SearchUserID;
			if ($this->CalendarRestrictions) $qry .= " AND " . $this->CalendarRestrictions;

			if ($ignoreips) $qry .= " AND " . $ignoreips;
			if (strtolower($exportarea) == 'referrer') {
				if ($ignorereferrers) $qry .= " AND " . $ignorereferrers;
			}
			if (strtolower($exportarea) == 'search') {
				if ($ignorekeywords) {
					$query .= " AND " . $ignorekeywords;
				}
			}

			$result = $this->Db->Query($qry);
			$row = $this->Db->Fetch($result);

			if ($exportarea == 'campaign') {
				$row['cost'] = $session->Get('ExportCampaignCost');
				$row['roi'] = 0;
				if ($row['cost'] > 0) {
					$row['roi'] = (($row['revenue'] / $row['cost']) * 100);
				}
			}

			if ($row['visits'] > 0) {
				$line = str_repeat("-", 100) . "\r\n";
				$line .= GetLang('Total') . ",,";
				foreach($row as $k => $v) {
					//if ($k == 'cost') continue; // we need cost to work out the grand total ROI, but not for each sub total.
					if (in_array($k, $this->FormatNumbers) && is_numeric($v)) {
						$line .= '"' . $this->FormatNumber($v) . '",';
					} elseif (in_array($k, $this->FormatNumbers_Decimal) && is_numeric($v)) {
						$line .= '"' . $this->FormatNumber($v, 2) . '",';
					} else {
						$line .= '"' . str_replace('"', "'", $v) . '",';
					}
				}
				$line = substr($line, 0, -1) . "\r\n";
				fputs($exportfilefp, $line);
				fputs($exportfilefp, "\r\n\r\n");
			}
			fclose($exportfilefp);
			
			$statstotals['Visits'][] = $row['visits'];
			$statstotals['Conversions'][] = $row['conversions'];
			$statstotals['Revenue'][] = $row['revenue'];
			$statstotals['Cost'][] = $row['cost'];
			$session->Set('StatsTotals', $statstotals);
			
			$exportsettings[$exportarea . '_SubStart'] = $suboffset;
			$exportsettings[$exportarea . '_TopStart'] = $MainOffset;
			$exportsettings[$exportarea . '_TopCount'] = $TopCount;
			$exportsettings[$exportarea . '_SubCount'] = $SubCount;
			$session->Set('ExportSettings', $exportsettings);
			
			?>
			<script language="javascript">
				setTimeout("document.location='index.php?Page=Export&Action=Export'", 1);
			</script>
			<?php
			return;
		}

		if (!isset($exportsettings[$exportarea . '_TopLevelList'])) {
			switch($exportarea) {
				case 'ppc':
					$query = "SELECT searchenginename AS name FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
					if ($ignoreips) $query .= " AND " . $ignoreips;
					$query .= " GROUP BY searchenginename";
					$query .= " ORDER BY searchenginename ASC";
				break;
				case 'campaign':
					$query = "SELECT campaignsite AS name FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
					if ($ignoreips) $query .= " AND " . $ignoreips;
					$query .= " GROUP BY campaignsite";
					$query .= " ORDER BY campaignsite ASC";
				break;
				
				case 'search':
					$query = "SELECT searchenginename AS name FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
					if ($ignoreips) $query .= " AND " . $ignoreips;
					if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
					$query .= " GROUP BY searchenginename";
					$query .= " ORDER BY searchenginename ASC";
				break;
				
				case 'referrer':
					$query = "SELECT domain AS name FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
					if ($ignoreips) $query .= " AND " . $ignoreips;
					if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
					$query .= " GROUP BY domain";
					$query .= " ORDER BY domain ASC";
				break;
			}
			$TopLevelList = array();
			$result = $this->Db->Query($query);
			while($row = $this->Db->Fetch($result)) {
				$TopLevelList[] = $row['name'];
			}
			$exportsettings[$exportarea . '_TopLevelList'] = $TopLevelList;
		} else {
			$TopLevelList = $exportsettings[$exportarea . '_TopLevelList'];
		}
		
		$toplevel_name = $TopLevelList[$MainOffset];
		$firstcolumn = $toplevel_name;

		switch($exportarea) {
			case 'ppc':
				$count_query = "SELECT COUNT(DISTINCT ppcname) AS count FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $count_query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $count_query .= " AND " . $ignoreips;
				$count_query .= " AND searchenginename='" . addslashes($toplevel_name) . "'";
				
				$query = "SELECT ppcname AS name, COUNT(ppcid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, SUM(amount) AS revenue, SUM(cost) AS cost, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $query .= " AND " . $ignoreips;
				$query .= " AND searchenginename='" . addslashes($toplevel_name) . "'";
				$query .= " GROUP BY name";
				$query .= " ORDER BY name ASC";
			break;
			
			case 'campaign':
				$count_query = "SELECT COUNT(DISTINCT campaignname) AS count FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $count_query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $count_query .= " AND " . $ignoreips;
				$count_query .= " AND campaignsite='" . addslashes($toplevel_name) . "'";
				
				$query = "SELECT campaignname AS name, COUNT(campaignid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(campaignid)+0.0)*100) AS percent, SUM(amount) AS revenue, SUM(cost) AS cost, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $query .= " AND " . $ignoreips;
				$query .= " AND campaignsite='" . addslashes($toplevel_name) . "'";
				$query .= " GROUP BY name";
				$query .= " ORDER BY name ASC";
			break;
			
			case 'search':
				$count_query = "SELECT COUNT(DISTINCT keywords) AS count FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $count_query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $count_query .= " AND " . $ignoreips;
				if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
				$count_query .= " AND searchenginename='" . addslashes($toplevel_name) . "'";
				
				$query = "SELECT keywords AS name, COUNT(searchid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent, SUM(amount) AS revenue, 'n/a' AS cost, 'n/a' AS roi, landingpage FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $query .= " AND " . $ignoreips;
				if ($ignorekeywords) $query .= " AND " . $ignorekeywords;

				$query .= " AND searchenginename='" . addslashes($toplevel_name) . "'";
				$query .= " GROUP BY name, landingpage";
				$query .= " ORDER BY name ASC, landingpage ASC";
			break;
			
			case 'referrer':
				$count_query = "SELECT COUNT(DISTINCT url) AS count FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $count_query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $count_query .= " AND " . $ignoreips;
				if ($ignorereferrers) $count_query .= " AND " . $ignorereferrers;
				$count_query .= " AND domain='" . addslashes($toplevel_name) . "'";

				$query = "SELECT url AS name, COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent, SUM(amount) AS revenue, 'n/a' AS cost, 'n/a' AS roi, landingpage FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $query .= " AND " . $ignoreips;
				if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
				$query .= " AND domain='" . addslashes($toplevel_name) . "'";
				$query .= " GROUP BY name, landingpage";
				$query .= " ORDER BY name ASC, landingpage ASC";
			break;
		}
		
		if (!isset($exportsettings[$exportarea . '_SubCount']) || is_null($exportsettings[$exportarea . '_SubCount'])) {
			$subcount_result = $this->Db->Query($count_query);
			$SubCount = $this->Db->FetchOne($subcount_result, 'count');
			if ($SubCount > 0 && $MainOffset == 0) {
				$header = "";
				foreach($this->ExportFields[$exportarea] as $column) {
					$header .= GetLang('Export_' . ucwords($column)) . ",";
				}
				$header = substr($header, 0, -1) . "\r\n\r\n";
				fputs($exportfilefp, $header);
			}
		} else {
			$SubCount = $exportsettings[$exportarea . '_SubCount'];
		}
		
		$suboffset = (isset($exportsettings[$exportarea . '_SubStart'])) ? $exportsettings[$exportarea . '_SubStart'] : 0;

		$query .= " " . $this->Db->AddLimit($suboffset, $NumberRecordsToExport);
		
		if ($suboffset > 0) {
			$GLOBALS['Report'] = sprintf(GetLang('Export_' . ucwords($area) . '_SubProgress'), $toplevel_name, $this->FormatNumber($suboffset), $this->FormatNumber($SubCount));
			$export_report .= $this->ParseTemplate('ExportStats_Window_Entry', true, false);
		}
		
		foreach($StatsToExport as $area) {
			if (in_array($area, $statsexported) || $exportarea == $area) {
				continue;
			}
			$GLOBALS['Report'] = GetLang('Export_' . ucwords($area) . '_Todo');
			$export_report .= $this->ParseTemplate('ExportStats_Window_Entry', true, false);
		}
		
		$GLOBALS['Report'] = $export_report;
		$this->ParseTemplate('ExportStats_Window');

		$found = 0;
		$result = $this->Db->Query($query);
		while($row = $this->Db->Fetch($result)) {

			if ($exportarea == 'campaign') {
				$to_date = $this->CalculateCalendarRestrictions(false, true);

				$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN period=0 THEN cost ELSE cost/period END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '') . " AND campaignsite='" . addslashes($toplevel_name) . "'";
				if ($ignoreips) $cost_query .= " AND " . $ignoreips;
				$cost_query .= " GROUP BY campaignname, startdate, period, cost";

				$cost_result = $this->Db->Query($cost_query);

				while($cost_row = $this->Db->Fetch($cost_result)) {
					// if there's no period it's a one off cost. Which means we just take it at face value.
					if ($cost_row['period'] == 0) {
						$cost = $cost_row['cost_per_day'];
					} else {
						$cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
					}
				}

				$roi = ($cost == 0) ? 0 : (($row['revenue'] / $cost) * 100);

				$row['cost'] = $cost;
				$row['roi'] = $roi;

				$ccost = $session->Get('ExportCampaignCost');
				$ccost += $cost;
				$session->Set('ExportCampaignCost', $ccost);
			}

			$exportline = '"' . str_replace('"', "'", $firstcolumn) . '",';

			foreach($row as $k => $v) {
				if (in_array($k, $this->FormatNumbers) && is_numeric($v)) {
					$exportline .= '"' . $this->FormatNumber($v) . '",';
				} elseif (in_array($k, $this->FormatNumbers_Decimal) && is_numeric($v)) {
					$exportline .= '"' . $this->FormatNumber($v, 2) . '",';
				} else {
					$exportline .= '"' . str_replace('"', "'", $v) . '",';
				}
			}
			$exportline = substr($exportline, 0, -1) . "\r\n";
			fputs($exportfilefp, $exportline);
			$found++;
		}
		
		if ($found == 0 or $found < $NumberRecordsToExport or (($found + $suboffset) >= $SubCount)) {
			$MainOffset++;
			$SubCount = null;
			$suboffset = 0;
		} else {
			$suboffset += $NumberRecordsToExport;
		}

		fclose($exportfilefp);
		$exportsettings[$exportarea . '_SubStart'] = $suboffset;
		$exportsettings[$exportarea . '_TopStart'] = $MainOffset;
		$exportsettings[$exportarea . '_TopCount'] = $TopCount;
		$exportsettings[$exportarea . '_SubCount'] = $SubCount;
		$exportsettings[$exportarea . '_TopLevelList'] = $TopLevelList;
		$session->Set('ExportSettings', $exportsettings);
		?>
		<script language="javascript">
			setTimeout("document.location='index.php?Page=Export&Action=Export'", 1);
		</script>
		<?php
	}
}
?>
