<?php
/**
* This file has the landing page functions for general referrers in it.
* It handles viewing all landing pages for a particular domain (eg sitescripts.com)
* And it also handles viewing all landing pages for a particular domain and url.
*
* @version     $Id: landingpages_referrers.php,v 1.19 2005/10/20 03:32:14 chris Exp $
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
* This class has the landing page functions for general referrers in it.
* It handles viewing all landing pages for a particular domain (eg sitescripts.com)
* And it also handles viewing all landing pages for a particular domain and url.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class LandingPages_Referrers extends TrackPoint_Functions {

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'Visits', 'visits' => 'Domain', 'domain' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function LandingPages_Referrers() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all of the work.
	* Sets up the session, prints out the results, handles paging, changing dates and so on.
	* Prints out the results you want based on the get variables (site & possibly keywords).
	*
	* @see Db
	* @see GetSession
	* @see Session::Get
	* @see GetSearchUser
	* @see GenerateXml
	* @see PrintHeader
	* @see ParseTemplate
	* @see User::SetSettings
	* @see GetPerPage
	* @see CalculateCalendarRestrictions
	* @see SetupCalendar
	* @see GetSortDetails
	* @see RememberCurrentPage
	* @see FormatNumber
	* @see SetupPagingHeader
	* @see PrintFooter
	* @see GetLang
	* @see GetBackPage
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$this->GetSearchUser();

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		$this->PrintHeader();
		$this->ParseTemplate('Menu');

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
		$url = (isset($_GET['URL'])) ? stripslashes(urldecode($_GET['URL'])) : false;

		$GLOBALS['HeadingDescription'] = GetLang('LandingPages');
		if ($domain === false) {
			$heading = GetLang('AllLandingPages');
			$fullurl = $heading;
		} else {
			$heading = ($domain == '') ? GetLang('DirectVisit') : $domain;
			$fullurl = $heading;
			$heading = ($url == '') ? $heading : $url;
		}

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;

		$GLOBALS['FullURL'] = $heading;
		if (strtolower(substr($domain, 0, 4)) == 'http') {
			$GLOBALS['LandingPageName'] = '<a href="' . $domain . '" target="_blank">' . $this->TruncateName($heading) . '</a>';
		} else {
			$GLOBALS['LandingPageName'] = $this->TruncateName($heading);
		}
		$GLOBALS['Image'] = 'searchengineicon.gif';

		$GLOBALS['TopHeading'] = $fullurl;
		$GLOBALS['TopHeading_Truncate'] = $this->TruncateName($fullurl);

		if ($url) {
			$GLOBALS['TopHeading_Detail'] = stripslashes($url);
			$GLOBALS['TopHeading_Detail_Truncate'] = stripslashes($this->TruncateName($url));
		}

		$GLOBALS['Help'] = GetLang('HelpLandingPage_Referrers');
		$GLOBALS['Title'] = GetLang('LandingPages');

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

		$query = "select COUNT(DISTINCT landingpage) AS count from " . TRACKPOINT_TABLEPREFIX . "referrers" . $total_subquery;

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

		$query = "SELECT COUNT(referrerid) AS totalvisits FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$ReferrerTotalVisits = $row['totalvisits'];

		$template = $this->ParseTemplate('LandingPages', true, false);

		$query = "SELECT COUNT(referrerid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['TotalVisits'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalConv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$landingpage_header = $this->ParseTemplate('LandingPagesRows_Header', true, false);

		$query = "SELECT landingpage AS name, COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers" . $total_subquery . " GROUP BY name, domain";

		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$rowid = 1;
		$display = '';
		if ($domain !== false && $domain == '') {
			$name = GetLang('DirectVisit');
		} else {
			$name = ($url) ? $url : $domain;
		}
		
		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$landingpagename = $row['name'];
			$alttitle = $landingpagename;
			$landingpagename = $this->TruncateName($landingpagename, 70);
			if (strtolower(substr($row['name'], 0, 4)) == 'http') {
				$GLOBALS['LandingPageName'] = '<a href="' . $row['name'] . '" title="' . $row['name'] . '" target="_blank">' . $landingpagename . '</a>';
			} else {
				$GLOBALS['LandingPageName'] = $landingpagename;
			}
			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['LinkURL'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$display .= $this->ParseTemplate('LandingPagesRows', true, false);
			$rowid++;
		}

		$query = "SELECT COUNT(referrerid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Referrers';
		$GLOBALS['TotalVisits'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalConv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Referrer';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$results_footer = $this->ParseTemplate('LandingPageResultsFooter', true);
		$template = str_replace('%%TPL_LandingPageResultsFooter%%', $results_footer, $template);

		$template = str_replace('%%TPL_LandingPagesRows_Header%%', $landingpage_header, $template);
		
		$template = str_replace('%%TPL_LandingPagesRows%%', $display, $template);

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		echo $template;

		$this->PrintFooter();
	}
}

?>
