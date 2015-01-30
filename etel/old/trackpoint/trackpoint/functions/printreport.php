<?php
/**
* This file has the print functions in it.
* This handles the popup window, what fields to print, formatting.
* Checks whether there are stats to print and so on.
*
* @version     $Id: printreport.php,v 1.16 2005/10/20 03:32:14 chris Exp $
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
* Class for the print-report page.
* This handles the popup window, what fields to print, formatting.
* Checks whether there are stats to print and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class PrintReport extends TrackPoint_Functions {

	/**
	* @var PrintTypes An array of areas that are printable. This relates to the options below in PrintFields.
	*
	* @see PrintFields
	*/
	var $PrintTypes = array('campaign', 'ppc', 'search', 'referrer');

	/**
	* @var PrintFields An array of fields to print per area.
	*
	* @see PrintTypes
	* @see PrintStats
	* @see Process
	*/
	var $PrintFields = array(
		'ppc' => array('topname' => 'searchenginename', 'name' => 'ppcname', 'visits' => 'visits', 'conversions' => 'conv', 'percent' => 'percent', 'cost' => 'cost', 'revenue' => 'revenue', 'roi' => 'roi'),
		
		'campaign' => array('topname' => 'campaignsite', 'name' => 'campaignname', 'visits' => 'visits', 'conversions' => 'conv', 'percent' => 'percent','cost' => 'cost', 'revenue' => 'revenue', 'roi' => 'roi'),
		
		'search' => array('topname' => 'searchenginename', 'name' => 'keywords', 'visits' => 'visits', 'conversions' => 'conv', 'percent' => 'percent', 'cost' => 'cost', 'revenue' => 'revenue', 'roi' => 'landingpage'),
		
		'referrer' => array('topname' => 'domain', 'name' => 'url', 'visits' => 'visits', 'conversions' => 'conv', 'percent' => 'percent', 'cost' => 'cost', 'revenue' => 'revenue', 'roi' => 'landingpage')
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
		'percent',
		'revenue',
		'cost',
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
	function PrintReport() {
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

		if ($action != 'print') {
			$this->PrintHeader();
			$this->ParseTemplate('Menu');
		} else {
			$this->PrintHeader(true);
		}

		switch($action) {
			case 'print_finished':
				?>
				<script language="javascript">
					window.opener.document.location='index.php?Page=PrintReport&Action=Finished';
					window.close();
				</script>
				<?php
				exit();
			break;
			
			case 'print':
				$this->PrintStats();
			break;
			
			case 'step2':
				$printingreport = GetLang('Print_Stats_Confirm');
				$printingreport .= '<ul>';
				$printareas = array();

				$ignoreips = $this->GetIgnoreDetails();
				$ignorereferrers = $this->GetIgnoreDetails('Referrers');
				$ignorekeywords = $this->GetIgnoreDetails('Keywords');

				foreach($this->PrintTypes as $area) {
					if (isset($_POST[$area])) {
						$printareas[] = $area;
						$printingreport .= '<li>' . GetLang($area) . '</li>';
					}
				}
				
				$printingreport .= '</ul>';
				$printingreport .= $this->GetCalendarInfo($_POST['Calendar']) . '<br/>';

				if (empty($printareas)) {
					$GLOBALS['Error'] = GetLang('Print_ChooseType');
					$GLOBALS['Message'] = $this->ParseTemplate('ErrorMsg', true, false);
					$this->PrintOptions();
					break;
				}
				
				$this->CalculateCalendarRestrictions($_POST['Calendar']);
				$foundstats = false;
				
				// make sure there are some statistics to print!
				foreach($printareas as $k => $area) {
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
					$GLOBALS['Error'] = GetLang('Print_NoStatsFound');
					$GLOBALS['Message'] = str_replace('<br/>', '', $this->ParseTemplate('ErrorMsg', true, false)) . '<br/>';
					$this->PrintOptions();
					break;
				}
				
				$GLOBALS['PrintingReport'] = $printingreport;
				
				$printsettings = array();
				$printsettings['Calendar'] = $_POST['Calendar'];
				$printsettings['Areas'] = $printareas;
				$printsettings['Filename'] = 'print_' . str_replace(' ', '_', strtolower(date(GetLang('DateFormat')))) . '_' . date('His') . '.' . $thisuser->userid . '.html';
				$session->Set('PrintSettings', $printsettings);
				
				$this->PrintStart();
			break;
			case 'finished':
				$printsettings = $session->Get('PrintSettings');

				$GLOBALS['PrintLink'] = '.' . str_replace(TRACKPOINT_BASE_DIRECTORY, '', TEMP_DIRECTORY) . '/' . $printsettings['Filename'];
				$this->ParseTemplate('Print_Finished');
			break;
			
			default:
				foreach($this->PrintTypes as $area) {
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
		
		if ($action != 'print') {
			$this->PrintFooter();
		} else {
			$this->PrintFooter(true);
		}
	}

	/**
	* PrintStart
	* Prints out the template for step 2 of the process.
	*
	* @return void
	*/
	function PrintStart() {
		$this->ParseTemplate('Print_Step2');
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
		$session->Remove('PrintSettings');
		$session->Remove('StatsPrinted');
		$session->Remove('StatsTotals');
		$session->Remove('PrintCampaignCost');
		$session->Set('PrintCampaignCost', 0);

		if (isset($_POST['Calendar'])) {
			$this->SetupCalendar(null, $_POST['Calendar']);
		} else {
			$this->SetupCalendar();
		}
		$this->ParseTemplate('Print');
	}

	/**
	* PrintStats
	* Does all of the work for exporting.
	* Prints out a status report as it goes, saves the file and of course does all of the calculations.
	*
	* @see FormatNumbers_Decimal
	* @see FormatNumbers
	* @see PrintFields
	*
	* @return void
	*/
	function PrintStats() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$printsettings = $session->Get('PrintSettings');
		
		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		$this->CalculateCalendarRestrictions($printsettings['Calendar']);
		
		$StatsToPrint = $printsettings['Areas'];
		
		$NumberRecordsToPrint = 500;

		if (!is_file(TEMP_DIRECTORY . '/' . $printsettings['Filename'])) {
			$firstline = $this->ParseTemplate('PrintReport_Header', true, false);
		} else {
			$firstline = "";
		}
		$printfilefp = fopen(TEMP_DIRECTORY . '/' . $printsettings['Filename'], 'a+');
		fputs($printfilefp, $firstline);

		$statsprinted = $session->Get('StatsPrinted');
		if (!$statsprinted) {
			$statsprinted = array();
		}

		$statstotals = $session->Get('StatsTotals');
		if (!$statstotals) {
			$statstotals = array();
		}

		if ($statsprinted == $StatsToPrint) {
			$totalvisits = array_sum($statstotals['Visits']);
			$totalrevenue = array_sum($statstotals['Revenue']);
			$totalconversions = array_sum($statstotals['Conversions']);
			$totalcost = array_sum($statstotals['Cost']);
			
			$conversionpercent = ($totalconversions / $totalvisits)*100;
			$roipercent = ($totalcost > 0) ? (($totalrevenue / $totalcost)*100) : 0;
			
			$GLOBALS['Visits'] = $this->FormatNumber($totalvisits);
			$GLOBALS['Conversions'] = $this->FormatNumber($totalconversions);
			$GLOBALS['Percent'] = $this->FormatNumber($conversionpercent, 2);
			$GLOBALS['Cost'] = $this->FormatNumber($totalcost, 2);
			$GLOBALS['ROI'] = $this->FormatNumber($roipercent, 2);
			$GLOBALS['Revenue'] = $this->FormatNumber($totalrevenue, 2);
			
			$line = $this->ParseTemplate('PrintReport_Footer', true, false);
			
			fputs($printfilefp, $line);
			fclose($printfilefp);
			?>
			<script language="javascript">
				document.location='index.php?Page=PrintReport&Action=Print_Finished';
			</script>
			<?php
			return;
		}
		
		$print_report = '';
		
		$printarea = false;
		foreach($StatsToPrint as $area) {
			if (in_array($area, $statsprinted)) {
				$GLOBALS['Report'] = GetLang('Print_' . ucwords($area) . '_Finished');
				$print_report .= $this->ParseTemplate('PrintStats_Window_Entry', true, false);
				continue;
			}
			$printarea = $area;
			break;
		}
		
		if (!$printarea) {
			?>
			<script language="javascript">
				document.location='index.php?Page=PrintReport&Action=Print_Finished';
			</script>
			<?php
			return;
		}
		
		if (!isset($printsettings[$printarea . '_TopCount'])) {
			$count_query = "SELECT COUNT(DISTINCT " . $this->ExportDbInfo[$printarea]['toplevel'] . ") AS count FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$printarea]['table'] . " WHERE " . $this->SearchUserID;
			if ($this->CalendarRestrictions) $count_query .= " AND " . $this->CalendarRestrictions;
			if ($ignoreips) {
				$count_query .= " AND " . $ignoreips;
			}
			if ($printarea == 'referrer') {
				if ($ignorereferrers) {
					$count_query .= " AND " . $ignorereferrers;
				}
			}
			if ($printarea == 'search') {
				if ($ignorekeywords) {
					$count_query .= " AND " . $ignorekeywords;
				}
			}
			$result = $this->Db->Query($count_query);
			$TopCount = $this->Db->FetchOne($result, 'count');
			if ($TopCount > 0) {
				$GLOBALS['AreaHeading'] = GetLang('Print_Header_' . ucwords($printarea));
				$printfileheader = $this->ParseTemplate('PrintReport_Area', true);
				fputs($printfilefp, $printfileheader);
			}
		} else {
			$TopCount = $printsettings[$printarea . '_TopCount'];
		}
		
		$MainOffset = (isset($printsettings[$printarea . '_TopStart'])) ? $printsettings[$printarea . '_TopStart'] : 0;

		$GLOBALS['Report'] = sprintf(GetLang('Print_' . ucwords($printarea) . '_InProgress'), $this->FormatNumber($MainOffset), $this->FormatNumber($TopCount));
		$print_report .= $this->ParseTemplate('PrintStats_Window_Entry', true, false);

		if ($MainOffset >= $TopCount) {
			$statsprinted[] = $printarea;
			$session->Set('StatsPrinted', $statsprinted);
			$suboffset = 0;
			$MainOffset = 0;
			$TopCount = 0;
			$SubCount = 0;

			$qry = "SELECT COUNT(" . $this->ExportDbInfo[$printarea]['key'] . ") AS visits, SUM(hasconversion) AS conversions, (SUM(hasconversion) / (COUNT(" . $this->ExportDbInfo[$printarea]['key'] . ")+0.0)*100) AS percent, SUM(amount) AS revenue";
			if ($printarea == 'ppc') {
				$qry .= ", SUM(cost) AS cost, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi";
			} else {
				$qry .= ", 'n/a' AS cost, 'n/a' AS roi";
			}
			$qry .= " FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$printarea]['table'] . " WHERE " . $this->SearchUserID;
			if ($this->CalendarRestrictions) $qry .= " AND " . $this->CalendarRestrictions;
			if ($ignoreips) $qry .= " AND " . $ignoreips;
			if (strtolower($printarea) == 'referrer') {
				if ($ignorereferrers) $qry .= " AND " . $ignorereferrers;
			}
			if (strtolower($printarea) == 'search') {
				if ($ignorekeywords) $qry .= " AND " . $ignorekeywords;
			}
			$result = $this->Db->Query($qry);
			$row = $this->Db->Fetch($result);

			$cost = $row['cost'];
			$roi = $row['roi'];

			if ($printarea == 'campaign') {
				$cost = $session->Get('PrintCampaignCost');
				$roi = 0;
				if ($cost > 0) {
					$roi = (($row['revenue'] / $cost) * 100);
				}
			}
			
			$GLOBALS['SubTotalVisits'] = $this->FormatNumber($row['visits']);
			$GLOBALS['SubTotalConversions'] = $this->FormatNumber($row['conversions']);
			$GLOBALS['SubTotalPercent'] = $this->FormatNumber($row['percent'], 2);
			$GLOBALS['SubTotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['SubTotalCost'] = (is_numeric($cost)) ? $this->FormatNumber($cost, 2) : $cost;
			$GLOBALS['SubTotalROI'] = (is_numeric($roi)) ? $this->FormatNumber($roi, 2) : $roi;

			if ($row['visits'] > 0) {
				$subtotalline = $this->ParseTemplate('PrintReport_ReportSubTotal', true);
				fputs($printfilefp, $subtotalline);
			}
			fclose($printfilefp);
			
			$statstotals['Visits'][] = $row['visits'];
			$statstotals['Conversions'][] = $row['conversions'];
			$statstotals['Revenue'][] = $row['revenue'];
			$statstotals['Cost'][] = $cost;
			$session->Set('StatsTotals', $statstotals);
			
			$printsettings[$printarea . '_SubStart'] = $suboffset;
			$printsettings[$printarea . '_TopStart'] = $MainOffset;
			$printsettings[$printarea . '_TopCount'] = $TopCount;
			$printsettings[$printarea . '_SubCount'] = $SubCount;
			$session->Set('PrintSettings', $printsettings);
			
			?>
			<script language="javascript">
				setTimeout("document.location='index.php?Page=PrintReport&Action=Print'", 1);
			</script>
			<?php
			return;
		}
		
		if (!isset($printsettings[$printarea . '_TopLevelList'])) {
			switch($printarea) {
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
			$printsettings[$printarea . '_TopLevelList'] = $TopLevelList;
		} else {
			$TopLevelList = $printsettings[$printarea . '_TopLevelList'];
		}
		
		$toplevel_name = $TopLevelList[$MainOffset];
		$firstcolumn = $toplevel_name;

		switch($printarea) {
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
				if ($ignorekeywords) $count_query .= " AND " . $ignorekeywords;
				$count_query .= " AND searchenginename='" . addslashes($toplevel_name) . "'";

				$query = "SELECT keywords AS name, COUNT(searchid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent, SUM(amount) AS revenue, 'n/a' AS cost, landingpage AS roi FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;
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
				if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
				$count_query .= " AND domain='" . addslashes($toplevel_name) . "'";

				$query = "SELECT url AS name, COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent, SUM(amount) AS revenue, 'n/a' AS cost, landingpage AS roi FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID;
				if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
				if ($ignoreips) $query .= " AND " . $ignoreips;
				if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
				$query .= " AND domain='" . addslashes($toplevel_name) . "'";
				$query .= " GROUP BY name, landingpage";
				$query .= " ORDER BY name ASC, landingpage ASC";
			break;
		}
		
		if (!isset($printsettings[$printarea . '_SubCount']) || is_null($printsettings[$printarea . '_SubCount'])) {
			$subcount_result = $this->Db->Query($count_query);
			$SubCount = $this->Db->FetchOne($subcount_result, 'count');
			if ($SubCount > 0 && $MainOffset == 0) {
				foreach($this->PrintFields[$printarea] as $column => $entry) {
					$GLOBALS[$column] = GetLang('Print_' . ucwords($entry));
				}
				$header = $this->ParseTemplate('PrintReport_SubHeader', true, false);
				fputs($printfilefp, $header);
			}
		} else {
			$SubCount = $printsettings[$printarea . '_SubCount'];
		}
		
		$suboffset = (isset($printsettings[$printarea . '_SubStart'])) ? $printsettings[$printarea . '_SubStart'] : 0;

		$query .= " " . $this->Db->AddLimit($suboffset, $NumberRecordsToPrint);
		
		if ($suboffset > 0) {
			$GLOBALS['Report'] = sprintf(GetLang('Print_' . ucwords($area) . '_SubProgress'), $toplevel_name, $this->FormatNumber($suboffset), $this->FormatNumber($SubCount));
			$print_report .= $this->ParseTemplate('PrintStats_Window_Entry', true, false);
		}
		
		foreach($StatsToPrint as $area) {
			if (in_array($area, $statsprinted) || $printarea == $area) {
				continue;
			}
			$GLOBALS['Report'] = GetLang('Print_' . ucwords($area) . '_Todo');
			$print_report .= $this->ParseTemplate('PrintStats_Window_Entry', true, false);
		}
		
		$GLOBALS['Report'] = $print_report;
		$this->ParseTemplate('PrintStats_Window');

		$found = 0;
		$result = $this->Db->Query($query);
		while($row = $this->Db->Fetch($result)) {
			if ($row['visits'] <= 0) continue;
			if ($firstcolumn == '') {
				$GLOBALS['topname'] = GetLang('DirectVisit');
			} else {
				$GLOBALS['topname'] = $this->TruncateName($firstcolumn);
			}

			if ($printarea == 'campaign') {
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

				$ccost = $session->Get('PrintCampaignCost');
				$ccost += $cost;
				$session->Set('PrintCampaignCost', $ccost);
			}

			foreach($row as $k => $v) {
				if (in_array($k, $this->FormatNumbers)) {
					if (is_numeric($v)) {
						$GLOBALS[$k] = $this->FormatNumber($v);
					} else {
						$GLOBALS[$k] = '';
					}
				} elseif (in_array($k, $this->FormatNumbers_Decimal)) {
					if (is_numeric($v) || $v == '') {
						$GLOBALS[$k] = $this->FormatNumber($v, 2);
					} else {
						$GLOBALS[$k] = $this->TruncateName(htmlspecialchars($v));
					}
				} else {
					$GLOBALS[$k] = $this->TruncateName(htmlspecialchars($v));
				}
			}
			$printline = $this->ParseTemplate('PrintReport_Row', true, false);
			fputs($printfilefp, $printline);
			$found++;
		}
		
		if ($found == 0 or $found < $NumberRecordsToPrint or (($found + $suboffset) >= $SubCount)) {
			$MainOffset++;
			$SubCount = null;
			$suboffset = 0;
		} else {
			$suboffset += $NumberRecordsToPrint;
		}

		fclose($printfilefp);
		$printsettings[$printarea . '_SubStart'] = $suboffset;
		$printsettings[$printarea . '_TopStart'] = $MainOffset;
		$printsettings[$printarea . '_TopCount'] = $TopCount;
		$printsettings[$printarea . '_SubCount'] = $SubCount;
		$printsettings[$printarea . '_TopLevelList'] = $TopLevelList;
		$session->Set('PrintSettings', $printsettings);
		?>
		<script language="javascript">
			setTimeout("document.location='index.php?Page=PrintReport&Action=Print'", 1);
		</script>
		<?php
	}

}
?>
