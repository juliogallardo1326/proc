<?php
/**
* This file has the ViewAll Search Engines functions in it. If there are more than 'x' keywords per search engine, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @version     $Id: viewall_engines.php,v 1.18 2005/10/20 03:32:14 chris Exp $
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
* This class has the ViewAll Search Engines functions in it. If there are more than 'x' keywords per search engine, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class ViewAll_Engines extends TrackPoint_Functions {

	/**
	* @var _SortTypes An array of sort types. This is overwritten from the parent class.
	*
	* @see Process
	*/
	var $_SortTypes = array('revenue', 'conv', 'percent', 'visits', 'name');

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'Visits', 'visits' => 'name', 'name' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function ViewAll_Engines() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all of the work. Works out the search engine, gets all of the keywords for that engine, calculates revenue etc.
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

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		switch($action) {
			case 'processpaging':
				$perpage = $_POST['PerPageDisplay'];
				$display_settings = array('NumberToShow' => $perpage);
				$thisuser->SetSettings('DisplaySettings', $display_settings);
			break;

			case 'processdate':
				$calendar_settings = $_POST['Calendar'];
				$thisuser->SetSettings('Calendar', $calendar_settings);
			break;
		}

		if (!isset($perpage)) {
			$perpage = $this->GetPerPage();
		}

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;

		$this->CalculateCalendarRestrictions();
		$ignoreips = $this->GetIgnoreDetails();
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		$enginename = (isset($_GET['Engine'])) ? stripslashes(urldecode($_GET['Engine'])) : false;

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;

		$GLOBALS['Sort'] = 'Name';
		$GLOBALS['Image'] = 'searchengineicon.gif';

		$this->RememberCurrentPage();

		$GLOBALS['ViewAllTitle'] = sprintf(GetLang('SearchStatsEngine_Specific'), $enginename);

		$GLOBALS['Help_Intro'] = GetLang('Help_Search');

		$searchdetails = '';
		if ($enginename !== false) $searchdetails .= '&Engine=' . urlencode($enginename);
		$GLOBALS['SearchDetails'] = $searchdetails;

		$formaction = 'Action=ProcessDate';
		if ($enginename !== false) $formaction .= '&Engine=' . urlencode($enginename);
		$this->SetupCalendar($formaction);

		$subqueries = array($this->SearchUserID);
		if ($this->CalendarRestrictions) $subqueries[] = $this->CalendarRestrictions;
		if ($ignoreips) $subqueries[] = $ignoreips;
		if ($ignorekeywords) $subqueries[] = $ignorekeywords;

		if ($enginename !== false) $subqueries[] = "searchenginename='" . addslashes($enginename) . "'";
		$total_subquery = ' WHERE ';
		$total_subquery .= implode(' AND ', $subqueries);

		$query = "select COUNT(DISTINCT keywords) AS count from " . TRACKPOINT_TABLEPREFIX . "search" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumResults = $row['count'];

		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($enginename !== false) $sortdetails .= '&Engine=' . urlencode($enginename);
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($enginename !== false) $formaction .= '&Engine=' . urlencode($enginename);
		$this->SetupPagingHeader($NumResults, $DisplayPage, $perpage, $formaction);

		$GLOBALS['Title'] = GetLang('Keyword');

		$template = $this->ParseTemplate('ViewAll', true, false);

		$GLOBALS['Name'] = $enginename;

		$query = "SELECT COUNT(searchid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['SearchTotal'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$query = "SELECT COUNT(searchid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search " . $total_subquery . " GROUP BY searchenginename";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Engines&Engine=' . urlencode($enginename);

		$viewallrows_header = $this->ParseTemplate('ViewAllRows_Header', true, false);

		$base_landing_page = 'index.php?Page=LandingPages_Engines&Engine=' . urlencode($enginename);
		$GLOBALS['LandingPageLink'] = $base_landing_page;

		$query = "SELECT COUNT(searchid) AS visits, keywords AS name, SUM(amount) AS revenue, sum(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search" . $total_subquery . " GROUP BY keywords";
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
			$GLOBALS['LandingPageURL'] = $base_landing_page . '&Keywords=' . urlencode($row['name']);
			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$name = strtolower($row['name']);
			$alttitle = $name;
			$name = $this->TruncateName($name);
			$GLOBALS['Name'] = $name;
			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$display .= $this->ParseTemplate('ViewAllRows', true, false);
			$rowid++;
		}

		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Engines';

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Search';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$searchresults_footer = $this->ParseTemplate('SearchResultsFooter', true);
		$template = str_replace('%%TPL_ResultsFooter%%', $searchresults_footer, $template);

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
