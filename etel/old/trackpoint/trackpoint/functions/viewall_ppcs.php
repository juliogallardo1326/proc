<?php
/**
* This file has the ViewAll PPC campaign functions in it. If there are more than 'x' campaigns per site, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @version     $Id: viewall_ppcs.php,v 1.10 2005/10/20 03:32:14 chris Exp $
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
* This class has the ViewAll PPC campaign functions in it. If there are more than 'x' campaigns per site, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class ViewAll_PPCs extends TrackPoint_Functions {

	/**
	* @var _SortTypes An array of sort types. This is overwritten from the parent class.
	*
	* @see Process
	*/
	var $_SortTypes = array('revenue', 'conv', 'percent', 'visits', 'name', 'roi', 'cost');

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'Visits', 'visits' => 'name', 'name' => 'Visits', 'percent' => 'Visits', 'roi' => 'Visits', 'cost' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function ViewAll_PPCs() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all of the work. Works out the referrers for the domain, gets all of the referrers from that domain, calculates revenue etc.
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see GetDatabase
	* @see User::SetSettings
	* @see User::GetSettings
	* @see CalculateCalendarRestrictions
	* @see SetupCalendar
	* @see FormatNumber
	* @see SetupPagingHeader
	* @see _SubSearchLimit
	* @see PrintFooter
	*
	* @return void
	*/
	function Process() {
		$this->PrintHeader();
		$this->ParseTemplate('Menu');

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$searchuserid = $this->GetSearchUser();
		$ignoreips = $this->GetIgnoreDetails();

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		switch($action) {
			case 'processpaging':
				if (!isset($_POST['PerPageDisplay'])) break;
				$perpage = $_POST['PerPageDisplay'];
				$display_settings = array('NumberToShow' => $perpage);
				$thisuser->SetSettings('DisplaySettings', $display_settings);
			break;

			case 'processdate':
				if (!isset($_POST['Calendar'])) break;
				$calendar_settings = $_POST['Calendar'];
				$thisuser->SetSettings('Calendar', $calendar_settings);
			break;
		}

		if (!isset($perpage)) {
			$perpage = $this->GetPerPage();
		}

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;

		$this->CalculateCalendarRestrictions();

		$engine = (isset($_GET['Engine'])) ? stripslashes(urldecode($_GET['Engine'])) : false;

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;

		$GLOBALS['Sort'] = 'Name';
		$GLOBALS['Image'] = 'ppcicon.gif';

		$GLOBALS['ViewAllTitle'] = sprintf(GetLang('PPC_Specific'), $engine);

		$GLOBALS['Help_Intro'] = GetLang('Help_PPC');

		$searchdetails = '';
		if ($engine !== false) $searchdetails .= '&Engine=' . urlencode($engine);
		$GLOBALS['SearchDetails'] = $searchdetails;

		$formaction = 'Action=ProcessDate';
		if ($engine !== false) $formaction .= '&Engine=' . urlencode($engine);
		$this->SetupCalendar($formaction);

		$subqueries = array($this->SearchUserID);
		if ($this->CalendarRestrictions) $subqueries[] = $this->CalendarRestrictions;
		if ($ignoreips) $subqueries[] = $ignoreips;
		if ($engine !== false) $subqueries[] = "searchenginename='" . addslashes($engine) . "'";
		$total_subquery = ' WHERE ';
		$total_subquery .= implode(' AND ', $subqueries);

		$query = "select COUNT(DISTINCT ppcname) AS count from " . TRACKPOINT_TABLEPREFIX . "payperclicks" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumResults = $row['count'];
		
		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($engine !== false) $sortdetails .= '&Engine=' . urlencode($engine);
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($engine !== false) $formaction .= '&Engine=' . urlencode($engine);
		$this->SetupPagingHeader($NumResults, $DisplayPage, $perpage, $formaction);

		$GLOBALS['Title'] = GetLang('PPCName');

		$template = $this->ParseTemplate('ViewAll_PPCs', true, false);

		$GLOBALS['Name'] = $engine;
		
		$query = "SELECT COUNT(ppcid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi, SUM(cost) AS cost FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		
		$GLOBALS['TotalVisits'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversions'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['TotalCost'] = $this->FormatNumber($row['cost'], 2);
		$GLOBALS['TotalROI'] = $this->FormatNumber($row['roi'], 2);

		$query = "SELECT COUNT(ppcid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, CASE WHEN SUM(cost) = 0 THEN 0 ELSE SUM(amount) / SUM(cost) END AS roi, SUM(cost) AS cost FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks " . $total_subquery . " GROUP BY searchenginename";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['Cost'] = $this->FormatNumber($row['cost'], 2);
		$GLOBALS['ROI'] = $this->FormatNumber($row['roi'], 2);

		$viewallrows_header = $this->ParseTemplate('ViewAllRows_PPCs_Header', true, false);

		$query = "SELECT ppcname AS name, COUNT(ppcid) AS visits, sum(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, SUM(amount) AS revenue, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi, SUM(cost) AS cost FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks " . $total_subquery . " GROUP BY ppcname";
		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);
		
		$result = $this->Db->Query($query);
		
		if (!$result) {
			return false;
		}

		$rowid = 1;
		$display = '';

		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$name = $row['name'];
			$alttitle = $name;
			$name = $this->TruncateName($name);
			$GLOBALS['Name'] = $name;
			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Cost'] = $this->FormatNumber($row['cost'], 2);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
			$GLOBALS['ROI'] = $this->FormatNumber($row['roi'], 2);

			$display .= $this->ParseTemplate('ViewAllRows_PPCs', true, false);
			$rowid++;
		}

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=PPC';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$results_footer = $this->ParseTemplate('PPCFooter', true);
		$template = str_replace('%%TPL_ResultsFooter%%', $results_footer, $template);

		$template = str_replace('%%TPL_ViewAllRows_Header%%', $viewallrows_header, $template);

		$template = str_replace('%%TPL_ViewAllRows%%', $display, $template);

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		echo $template;

		$this->PrintFooter();

	}

}

?>
