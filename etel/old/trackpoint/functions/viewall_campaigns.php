<?php
/**
* This file has the ViewAll Campaigns functions in it. If there are more than 'x' campaigns per site, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @version     $Id: viewall_campaigns.php,v 1.13 2005/10/20 03:32:14 chris Exp $
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
* This file has the ViewAll Campaigns functions in it. If there are more than 'x' campaigns per site, this file handles displaying all of them in a list. It handles sorting, paging and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class ViewAll_Campaigns extends TrackPoint_Functions {

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
	function ViewAll_Campaigns() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all of the work. Works out the campaign site, gets all of the campaigns for that site, calculates cost, ROI etc.
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

		$site = (isset($_GET['Site'])) ? stripslashes(urldecode($_GET['Site'])) : false;

		$backlink = $this->GetBackPage();
		$GLOBALS['BackLink'] = $backlink;

		$GLOBALS['Sort'] = 'Name';
		$GLOBALS['Image'] = 'campaignicon.gif';

		$GLOBALS['ViewAllTitle'] = sprintf(GetLang('Campaign_Specific'), $site);

		$GLOBALS['Help_Intro'] = GetLang('Help_Campaigns');

		$searchdetails = '';
		if ($site !== false) $searchdetails .= '&Site=' . urlencode($site);
		$GLOBALS['SearchDetails'] = $searchdetails;

		$formaction = 'Action=ProcessDate';
		if ($site !== false) $formaction .= '&Site=' . urlencode($site);
		$this->SetupCalendar($formaction);

		$subqueries = array($this->SearchUserID);
		if ($this->CalendarRestrictions) $subqueries[] = $this->CalendarRestrictions;
		if ($ignoreips) $subqueries[] = $ignoreips;
		if ($site !== false) $subqueries[] = "campaignsite='" . addslashes($site) . "'";
		$total_subquery = ' WHERE ';
		$total_subquery .= implode(' AND ', $subqueries);

		$query = "select COUNT(DISTINCT campaignname) AS count from " . TRACKPOINT_TABLEPREFIX . "campaigns" . $total_subquery;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumResults = $row['count'];
		
		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($site !== false) $sortdetails .= '&Site=' . urlencode($site);
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		if ($site !== false) $formaction .= '&Site=' . urlencode($site);
		$this->SetupPagingHeader($NumResults, $DisplayPage, $perpage, $formaction);

		$GLOBALS['Title'] = GetLang('CampaignName');

		$template = $this->ParseTemplate('ViewAll_Campaigns', true, false);

		$GLOBALS['Name'] = $site;

		$to_date = $this->CalculateCalendarRestrictions(false, true);
		
		// this is for the footer for "All Campaigns".
		$query = "SELECT COUNT(campaignid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(campaignid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['CampaignTotal'] = $this->FormatNumber($row['totalvisits']);
		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$total_cost = 0;
		$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN sum(period)=0 THEN cost ELSE sum(cost)/sum(period) END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $cost_query .= " AND " . $ignoreips;
		$cost_query .= " GROUP BY campaignname, campaignsite, startdate, period, cost";

		$cost_result = $this->Db->Query($cost_query);

		while($cost_row = $this->Db->Fetch($cost_result)) {
			if ($cost_row['period'] == 0) {
				$total_cost += $cost_row['cost_per_day'];
			} else {
				$total_cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
			}
		}
		$roi = ($total_cost == 0) ? 0 : (($row['revenue'] / $total_cost) * 100);
		$GLOBALS['TotalROI'] = $this->FormatNumber($roi, 2);
		$GLOBALS['TotalCost'] = $this->FormatNumber($total_cost, 2);

		// this is for the header line - just for this campaign site.
		$query = "SELECT COUNT(campaignid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(campaignid)+0.0)*100) AS percent, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi, period, CASE WHEN period=0 THEN cost ELSE cost/period END AS cost FROM " . TRACKPOINT_TABLEPREFIX . "campaigns " . $total_subquery . " GROUP BY campaignsite, period, cost";
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
		$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);

		$cost = 0; $roi = 0;

		$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN period=0 THEN cost ELSE cost/period END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns " . $total_subquery . " GROUP BY campaignname, startdate, period, cost";

		$cost_result = $this->Db->Query($cost_query);

		while($cost_row = $this->Db->Fetch($cost_result)) {
			if ($cost_row['period'] == 0) {
				$cost = $cost_row['cost_per_day'];
			} else {
				$cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
			}
		}

		$roi = ($cost == 0) ? 0 : (($row['revenue'] / $cost) * 100);
		$GLOBALS['ROI'] = $this->FormatNumber($roi, 2);
		$GLOBALS['Cost'] = $this->FormatNumber($cost, 2);

		$viewallrows_header = $this->ParseTemplate('ViewAllRows_Campaigns_Header', true, false);

		$query = "SELECT campaignname AS name, COUNT(campaignid) AS visits, sum(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(campaignid)+0.0)*100) AS percent, SUM(amount) AS revenue FROM " . TRACKPOINT_TABLEPREFIX . "campaigns " . $total_subquery . " GROUP BY campaignname";
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
			$cost = 0; $roi = 0;

			$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN period=0 THEN cost ELSE cost/period END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns " . $total_subquery . " AND campaignname='" . addslashes($row['name']) . "' GROUP BY startdate, period, cost";

			$cost_result = $this->Db->Query($cost_query);

			while($cost_row = $this->Db->Fetch($cost_result)) {
				if ($cost_row['period'] == 0) {
					$cost = $cost_row['cost_per_day'];
				} else {
					$cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
				}
			}

			$roi = ($cost == 0) ? 0 : (($row['revenue'] / $cost) * 100);

			$GLOBALS['RowID'] = $rowid;
			$detail_rowid = 1;
			$name = $row['name'];
			$alttitle = $name;
			$name = $this->TruncateName($name);
			$GLOBALS['Name'] = $name;
			$GLOBALS['AltTitle'] = $alttitle;
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);
			$GLOBALS['ROI'] = $this->FormatNumber($roi, 2);
			$GLOBALS['Cost'] = $this->FormatNumber($cost, 2);

			$display .= $this->ParseTemplate('ViewAllRows_Campaigns', true, false);
			$rowid++;
		}

		$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Campaign';
		$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);

		$results_footer = $this->ParseTemplate('CampaignResultsFooter', true);
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
