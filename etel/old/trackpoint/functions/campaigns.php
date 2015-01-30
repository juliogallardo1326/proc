<?php
/**
* This file has the conversion tracking-code functions in it.
*
* @version     $Id: campaigns.php,v 1.22 2005/11/04 00:35:52 chris Exp $
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
* Class for the campaigns page.
* Handles sorting, processing, paging, changing of dates etc etc.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Campaigns extends TrackPoint_Functions {

	/**
	* var _SortTypes An array of valid sorting types for this page.
	*/
	var $_SortTypes = array('revenue', 'conv', 'percent', 'visits', 'site');

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('site' => 'Visits', 'visits' => 'Site', 'revenue' => 'Visits', 'percent' => 'Visits', 'cost' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function Campaigns() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all of the work.
	* Sets up the session, prints out the results, handles paging, changing dates and so on.
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
	*
	* @return void
	*/
	function Process() {

		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$this->GetSearchUser();

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';

		if ($action == 'generatexml') {
			$this->GenerateXml();
			exit();
		}

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

		$this->SetupCalendar();

		$this->GetSortDetails();

		$ignoreips = $this->GetIgnoreDetails();
		
		$this->RememberCurrentPage(true);

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;
		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;

		$query = "SELECT COUNT(DISTINCT campaignsite) AS campaigncount FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumCampaigns = $row['campaigncount'];

		$this->SetupPagingHeader($NumCampaigns, $DisplayPage, $perpage, $formaction);

		$template = $this->ParseTemplate('Campaigns', true, false);

		$query = "SELECT campaignsite AS site, COUNT(campaignid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(campaignid)+0.0) * 100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		$query .= " GROUP BY campaignsite";
		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);

		$rowid = 1;
		$display = '';

		$base_fetchlink = 'SortBy=' . urlencode($sortby) . '&Direction=' . urlencode($direction);

		$to_date = $this->CalculateCalendarRestrictions(false, true);

		$result = $this->Db->Query($query);
		while($row = $this->Db->Fetch($result)) {
			$cost = 0; $roi = 0;

			$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN period=0 THEN cost ELSE cost/period END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '') . " AND campaignsite='" . addslashes($row['site']) . "'";
			if ($ignoreips) $cost_query .= " AND " . $ignoreips;
			$cost_query .= " GROUP BY campaignname, startdate, period, cost";

			$cost_result = $this->Db->Query($cost_query);

			while($cost_row = $this->Db->Fetch($cost_result)) {
				// if there's no period it's a one off cost. Which means we just take it at face value.
				if ($cost_row['period'] == 0) {
					$cost += $cost_row['cost_per_day'];
				} else {
					$cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
				}
			}

			$roi = ($cost == 0) ? 0 : (($row['revenue'] / $cost) * 100);

			$GLOBALS['RowID'] = $rowid;
			$GLOBALS['FetchLink'] = $base_fetchlink . '&Site=' . urlencode($row['site']);
			$GLOBALS['CampaignSite'] = stripslashes($row['site']);
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
			$GLOBALS['Conversions'] = $this->FormatNumber($row['conv']);

			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['Percent'] = $this->FormatNumber($row['percent'], 2);

			$GLOBALS['Cost'] = $this->FormatNumber($cost, 2);

			$GLOBALS['ROI'] = $this->FormatNumber($roi, 2);

			$display .= $this->ParseTemplate('CampaignsRows', true, false);
			$rowid++;
		}
		
		if ($rowid == 1) {
			// if there are no rows, we'll add a "blank" row.
			$display .= $this->ParseTemplate('CampaignsRows_Blank', true, false) . '<br/>';
		}

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		$template = str_replace('%%TPL_CampaignsRows%%', $display, $template);


		$query = "SELECT COUNT(campaignid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(campaignid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

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

		$GLOBALS['TotalVisits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['TotalConversions'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['TotalPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalCost'] = $this->FormatNumber($total_cost, 2);
		$GLOBALS['TotalROI'] = $this->FormatNumber($roi, 2);

		if ($row['visits'] > 0) {
			$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Campaign';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);
		} else {
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter_Disabled', true, false);
		}
		$campaign_footer = $this->ParseTemplate('CampaignsFooter', true);
		$template = str_replace('%%TPL_CampaignsFooter%%', $campaign_footer, $template);

		echo $template;

		$this->PrintFooter();
	}

	/**
	* GenerateXml
	* Generates XML for the hidden information for campaigns. Works out costs, visits, conversions and so on depending on the campaign site.
	*
	* @see Process
	* @see CalculateCalendarRestrictions
	* @see GetSortDetails
	* @see Db
	* @see FormatNumber
	* @see GetLang
	*
	* @return void Doesn't return the results, simply prints them out.
	*/
	function GenerateXml() {
		header('Content-Type: text/xml');
		?>
		<data>
		<?php

		$this->CalculateCalendarRestrictions();

		$ignoreips = $this->GetIgnoreDetails();

		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$campaignsite = stripslashes(urldecode($_GET['Site']));

		if (strtolower($sortby) == 'site') $sortby = $this->_Secondary_SortTypes['site'];
		if (strtolower($second_sortby) == 'site') $second_sortby = false;

		$to_date = $this->CalculateCalendarRestrictions(false, true);

		$baseaction = '';

		$query = "SELECT campaignname AS name, COUNT(campaignid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(campaignid)+0.0) *100) AS percent, SUM(amount) AS revenue FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID;

		if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$query .= " AND campaignsite='" . addslashes($campaignsite) . "'";
		$query .= " GROUP BY name";
		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= " LIMIT " . ($this->_SubSearchLimit + 1);

		$result = $this->Db->Query($query);

		$rowcount = 1;

		while($row = $this->Db->Fetch($result)) {
			if ($rowcount > $this->_SubSearchLimit) {
				?>
				<item>
					<contents>
						<?php echo GetLang('ViewAll'); ?>
					</contents>
					<visits>
						0
					</visits>
					<cost>
						0
					</cost>
					<conversions>
						0
					</conversions>
					<conversionspercent>
						0
					</conversionspercent>
					<revenue>
						0
					</revenue>
					<roi>
						0
					</roi>
					<action>
						index.php?Page=ViewAll_Campaigns&amp;Site=<?php echo urlencode($campaignsite); ?>
					</action>
					<viewall>
						1
					</viewall>
				</item>
				<?php
				continue;
			}

			$cost = 0; $roi = 0;

			$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN sum(period)=0 THEN cost ELSE sum(cost)/sum(period) END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '') . " AND campaignsite='" . addslashes($campaignsite) . "' AND campaignname='" . addslashes($row['name']) . "'";
			if ($ignoreips) $cost_query .= " AND " . $ignoreips;
			$cost_query .= " GROUP BY startdate, period, cost";

			$cost_result = $this->Db->Query($cost_query);

			while($cost_row = $this->Db->Fetch($cost_result)) {
				if ($cost_row['period'] == 0) {
					$cost += $cost_row['cost_per_day'];
				} else {
					$cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
				}
			}

			$roi = ($cost == 0) ? 0 : (($row['revenue'] / $cost) * 100);

			$revenue = $row['revenue'];
			$conversions = $row['conv'];

			$conversionspercent = $row['percent'];

			?>
				<item>
					<contents>
						<?php echo htmlentities($row['name']); ?>
					</contents>
					<visits>
						<?php echo $this->FormatNumber($row['visits']); ?>
					</visits>
					<cost>
						<?php echo $this->FormatNumber($cost, 2); ?>
					</cost>
					<conversions>
						<?php echo $this->FormatNumber($conversions, 0); ?>
					</conversions>
					<conversionspercent>
						<?php echo $this->FormatNumber($conversionspercent, 2); ?>
					</conversionspercent>
					<revenue>
						<?php echo $this->FormatNumber($revenue, 2); ?>
					</revenue>
					<roi>
						<?php echo $this->FormatNumber($roi, 2); ?>
					</roi>
					<action>
						0
					</action>
					<viewall>
						0
					</viewall>
				</item>
			<?php
			$rowcount++;
		}

		?>
		</data>
		<?php
	}

}

?>
