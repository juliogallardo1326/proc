<?php
/**
* This file has the ViewAll Referrer functions in it. If there are more than 'x' referrers per domain name, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @version     $Id: viewall_referrers.php,v 1.19 2005/10/20 03:32:14 chris Exp $
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
* This class has the ViewAll Referrer functions in it. If there are more than 'x' referrers per domain name, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class ViewAll_Referrers extends TrackPoint_Functions {

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
	function ViewAll_Referrers() {
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
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');

		$domain = (isset($_GET['Domain'])) ? stripslashes(urldecode($_GET['Domain'])) : false;
		$url = false;

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;

		$GLOBALS['Sort'] = 'Name';
		$GLOBALS['Image'] = 'searchengineicon.gif';

		$this->RememberCurrentPage();

		$viewalltitle = GetLang('ReferrersStats');
		if ($domain !== false) {
			$viewalltitle .= ': ' . $domain;
		}
		$GLOBALS['ViewAllTitle'] = $viewalltitle;

		$GLOBALS['Help_Intro'] = GetLang('Help_Referrers');

		$searchdetails = '';
		if ($domain !== false) $searchdetails .= '&Domain=' . urlencode($domain);
		if ($url !== false) $searchdetails .= '&URL=' . urlencode($url);
		$GLOBALS['SearchDetails'] = $searchdetails;

		$formaction = 'Action=ProcessDate';
		if ($domain !== false) $formaction .= '&Domain=' . urlencode($domain);
		if ($url !== false) $formaction .= '&URL=' . urlencode($url);
		$this->SetupCalendar($formaction);

		$subqueries = array($this->SearchUserID);
		if ($this->CalendarRestrictions) $subqueries[] = $this->CalendarRestrictions;
		if ($ignoreips) $subqueries[] = $ignoreips;
		if ($ignorereferrers) $subqueries[] = $ignorereferrers;
		if ($domain !== false) $subqueries[] = "domain='" . addslashes($domain) . "'";
		if ($url !== false) $subqueries[] = "url='" . addslashes($url) . "'";
		$total_subquery = ' WHERE ';
		$total_subquery .= implode(' AND ', $subqueries);

		$query = "select COUNT(DISTINCT url) AS count from " . TRACKPOINT_TABLEPREFIX . "referrers" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumResults = $row['count'];

		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($domain !== false) $sortdetails .= '&Domain=' . urlencode($domain);
		if ($url!== false) $sortdetails .= '&URL=' . urlencode($url);
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($domain !== false) $formaction .= '&Domain=' . urlencode($domain);
		if ($url!== false) $formaction .= '&URL=' . urlencode($url);
		$this->SetupPagingHeader($NumResults, $DisplayPage, $perpage, $formaction);

		$GLOBALS['Title'] = GetLang('Referrers');

		$template = $this->ParseTemplate('ViewAll', true, false);

		if ($domain === false) {
			$GLOBALS['Name'] = GetLang('AllReferrers');
		} else {
			if ($domain == '') {
				$GLOBALS['Name'] = GetLang('DirectVisit');
			} else {
				if (strtolower(substr($domain, 0, 4)) == 'http') {
					$GLOBALS['Name'] = '<a href="' . $domain . '" target="_blank">' . $this->TruncateName($domain) . '</a>';
				} else {
					$GLOBALS['Name'] = $this->TruncateName($domain);
				}
			}
		}

		$query = "SELECT COUNT(referrerid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['ReferrerTotal'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$query = "SELECT COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers " . $total_subquery . " GROUP BY domain";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Referrers&Domain=' . urlencode($domain);

		$viewallrows_header = $this->ParseTemplate('ViewAllRows_Header', true, false);

		$base_landing_page = 'index.php?Page=LandingPages_Referrers&Domain=' . urlencode($domain);
		$GLOBALS['LandingPageLink'] = $base_landing_page;

		$query = "SELECT COUNT(referrerid) AS visits, url AS name, SUM(amount) AS revenue, sum(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers" . $total_subquery . " GROUP BY url";
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
			$GLOBALS['LandingPageURL'] = $base_landing_page . '&URL=' . urlencode($row['name']);
			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$name = $row['name'];
			$alttitle = $name;
			$name = $this->TruncateName($name);
			$GLOBALS['Name'] = '<a href="' . $alttitle . '" target="_blank">' . $name . '</a>';
			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$display .= $this->ParseTemplate('ViewAllRows', true, false);
			$rowid++;
		}

		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Referrers';

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Referrer';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$referrerresults_footer = $this->ParseTemplate('ReferrerResultsFooter', true);
		$template = str_replace('%%TPL_ResultsFooter%%', $referrerresults_footer, $template);

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
