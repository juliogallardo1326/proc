<?php
/**
* This class has the landing page functions for search engine keywords in it.
* It handles viewing all landing pages for a particular search engine keywords (eg interspire)
* And it also handles viewing all landing pages for a particular search engine keyword from a particular search engine.
*
* @version     $Id: landingpages_search.php,v 1.18 2005/10/20 03:32:14 chris Exp $
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
* This class has the landing page functions for search engine keywords in it.
* It handles viewing all landing pages for a particular search engine keywords (eg interspire)
* And it also handles viewing all landing pages for a particular search engine keyword from a particular search engine.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class LandingPages_Search extends TrackPoint_Functions {

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('visits' => 'revenue', 'revenue' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function LandingPages_Search() {
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
		$ignoreips = $this->GetIgnoreDetails();
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		$keywords = (isset($_GET['Keywords'])) ? stripslashes(urldecode($_GET['Keywords'])) : false;
		$searchengine = (isset($_GET['Engine'])) ? stripslashes(urldecode($_GET['Engine'])) : false;

		$GLOBALS['HeadingDescription'] = GetLang('LandingPages');
		if ($keywords === false) {
			$heading = GetLang('AllLandingPages');
			$fullurl = $heading;
		} else {
			$heading = $keywords;
			$fullurl = $heading;
		}
		$GLOBALS['FullURL'] = $fullurl;
		$GLOBALS['LandingPageName'] = $heading;

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;
		$GLOBALS['Image'] = 'searchengineicon.gif';

		$GLOBALS['TopHeading'] = $fullurl;
		$GLOBALS['TopHeading_Truncate'] = $this->TruncateName($fullurl);

		if ($searchengine) {
			$GLOBALS['TopHeading_Detail'] = stripslashes($searchengine);
			$GLOBALS['TopHeading_Detail_Truncate'] = stripslashes($this->TruncateName($searchengine));
		}

		$GLOBALS['Help'] = GetLang('HelpLandingPage_Keywords');
		$GLOBALS['Title'] = GetLang('LandingPages');

		$searchdetails = '';
		if ($searchengine !== false) $searchdetails .= '&Engine=' . urlencode($searchengine);
		if ($keywords !== false) $searchdetails .= '&Keywords=' . urlencode($keywords);
		$GLOBALS['SearchDetails'] = $searchdetails;

		$formaction = 'Action=ProcessDate';
		if ($searchengine !== false) $searchdetails .= '&Engine=' . urlencode($searchengine);
		if ($keywords !== false) $searchdetails .= '&Keywords=' . urlencode($keywords);
		$this->SetupCalendar($formaction);

		$subqueries = array($this->SearchUserID);
		if ($this->CalendarRestrictions) $subqueries[] = $this->CalendarRestrictions;
		if ($ignoreips) $subqueries[] = $ignoreips;
		if ($ignorekeywords) $subqueries[] = $ignorekeywords;

		if ($searchengine !== false) $subqueries[] = "searchenginename='" . addslashes($searchengine) . "'";
		if ($keywords !== false) $subqueries[] = "keywords='" . addslashes($keywords) . "'";
		$total_subquery = ' WHERE ';
		$total_subquery .= implode(' AND ', $subqueries);

		$query = "SELECT COUNT(searchid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$TotalVisits = $row['totalvisits'];
		$TotalConv = $row['conv'];
		$TotalPercent = $row['percent'];
		$TotalRevenue = $row['revenue'];

		$query = "select COUNT(DISTINCT landingpage) AS count from " . TRACKPOINT_TABLEPREFIX . "search" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumResults = $row['count'];

		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($searchengine !== false) $sortdetails .= '&Engine=' . urlencode($searchengine);
		if ($keywords !== false) $sortdetails .= '&Keywords=' . urlencode($keywords);
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($searchengine !== false) $formaction .= '&Engine=' . urlencode($searchengine);
		if ($keywords !== false) $formaction .= '&Keywords=' . urlencode($keywords);
		$this->SetupPagingHeader($NumResults, $DisplayPage, $perpage, $formaction);

		$query = "SELECT COUNT(searchid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$SearchTotalVisits = $this->FormatNumber($row['totalvisits']);
		$SearchTotalConv = $this->FormatNumber($row['conv']);
		$SearchTotalRevenue = $this->FormatNumber($row['revenue'], 2);
		$SearchTotalPercent = $this->FormatNumber($row['percent'], 2);

		$template = $this->ParseTemplate('LandingPages', true, false);

		$query = "SELECT landingpage AS name, COUNT(searchid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search" . $total_subquery . " GROUP BY name";

		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$GLOBALS['TotalVisits'] = $this->FormatNumber($TotalVisits);
		$GLOBALS['TotalConv'] = $this->FormatNumber($TotalConv);
		$GLOBALS['TotalConvPercent'] = $this->FormatNumber($TotalPercent, 2);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($TotalRevenue, 2);

		$landingpage_header = $this->ParseTemplate('LandingPagesRows_Header', true, false);

		$rowid = 1;
		$display = '';
		$name = ($keywords) ? $keywords : $searchengine;

		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$landingpage = $row['name'];
			$alttitle = $landingpage;
			$landingpagename = $this->TruncateName($landingpage, 70);

			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['LinkURL'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			if (strtolower(substr($row['name'], 0, 4)) == 'http') {
				$GLOBALS['LandingPageName'] = '<a href="' . $row['name'] . '" title="' . $row['name'] . '" target="_blank">' . $landingpagename . '</a>';
			} else {
				$GLOBALS['LandingPageName'] = $landingpagename;
			}

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$display .= $this->ParseTemplate('LandingPagesRows', true, false);
			$rowid++;
		}

		$query = "SELECT COUNT(searchid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['SearchTotal'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Search';

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Search';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$results_footer = $this->ParseTemplate('SearchResultsFooter', true);
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
