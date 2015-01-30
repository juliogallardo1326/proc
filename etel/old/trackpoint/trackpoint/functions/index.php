<?php
/**
* This file has the first welcome page functions, including quickstats.
*
* @version     $Id: index.php,v 1.17 2005/11/16 07:07:25 chris Exp $
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
* Class for the welcome page. Includes quickstats and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Index extends TrackPoint_Functions {

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function Index() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}
	
	/**
	* Process
	* Prints the front page. Prints out the first date for stats (so you know roughly when it all started), the quickstart popup, calendar and so on.
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';
		
		if ($action == 'quickstart') {
			if (isset($_GET['KillQuickStart'])) {
				$user = &GetUser($thisuser->userid);
				$user->StopQuickStart();
				$session->Set('UserDetails', $user);
				?>
				<script language="javascript">
					window.opener.focus();
					window.close();
				</script>
				<?php
				return;
			}
			$this->PrintHeader(true);
			$this->ParseTemplate('QuickStart');
			$this->PrintFooter(true);
			return;
		}

		if (isset($_GET['Graph'])) {
			$graph = urldecode($_GET['Graph']);
			$thisuser->SetSettings('GraphChoice', false);
			if (in_array($graph, $this->GraphOptions)) {
				$thisuser->SetSettings('GraphChoice', $graph);
			}
		}

		$this->GetSearchUser();

		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');

		$this->PrintHeader();
		$this->ParseTemplate('Menu');

		switch($action) {
			case 'processdate':
				if (!isset($_POST['Calendar'])) break;
				$calendar_settings = $_POST['Calendar'];
				$thisuser->SetSettings('Calendar', $calendar_settings);
			break;
		}

		$this->CalculateCalendarRestrictions();

		$this->SetupCalendar();

		$campaign_query = "SELECT COUNT(campaignid) AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $campaign_query .= " AND " . $ignoreips;

		$campaign_result = $this->Db->Query($campaign_query);
		$campaign_row = $this->Db->Fetch($campaign_result);
	
		$campaign_cost = 0;
		$to_date = $this->CalculateCalendarRestrictions(false, true);
		$cost_query = "SELECT ((" . $to_date . " - startdate) / 86400) AS num_days, period, CASE WHEN sum(period)=0 THEN cost ELSE sum(cost)/sum(period) END AS cost_per_day FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $cost_query .= " AND " . $ignoreips;
		$cost_query .= " GROUP BY campaignname, campaignsite, startdate, period, cost";

		$cost_result = $this->Db->Query($cost_query);

		while($cost_row = $this->Db->Fetch($cost_result)) {
			if ($cost_row['period'] == 0) {
				$campaign_cost += $cost_row['cost_per_day'];
			} else {
				$campaign_cost += ($cost_row['num_days'] * $cost_row['cost_per_day']);
			}
		}

		$campaign_roi = ($campaign_cost == 0) ? 0 : (($campaign_row['revenue'] / $campaign_cost) * 100);

		$ppc_query = "SELECT COUNT(ppcid) AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions, SUM(cost) AS cost, (SUM(amount) / SUM(cost)*100) AS roi FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $ppc_query .= " AND " . $ignoreips;

		$ppc_result = $this->Db->Query($ppc_query);
		$ppc_row = $this->Db->Fetch($ppc_result);
		
		$search_query = "SELECT COUNT(searchid) AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $search_query .= " AND " . $ignoreips;
		$search_result = $this->Db->Query($search_query);
		$search_row = $this->Db->Fetch($search_result);

		$referrer_query = "SELECT COUNT(referrerid) AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $referrer_query .= " AND " . $ignoreips;
		if ($ignorereferrers) $referrer_query .= " AND " . $ignorereferrers;

		$referrer_result = $this->Db->Query($referrer_query);
		$referrer_row = $this->Db->Fetch($referrer_result);
		
		$campaign_visits = $campaign_row['visits'];
		$campaign_revenue = $campaign_row['revenue'];
		$campaign_conversions = $campaign_row['conversions'];
		$campaign_conversions_percent = ($campaign_conversions / (($campaign_visits > 0) ? $campaign_visits : 1))*100;

		$ppc_visits = $ppc_row['visits'];
		$ppc_revenue = $ppc_row['revenue'];
		$ppc_conversions = $ppc_row['conversions'];
		$ppc_conversions_percent = ($ppc_conversions / (($ppc_visits > 0) ? $ppc_visits : 1))*100;
		$ppc_roi = $ppc_row['roi'];
		$ppc_cost = $ppc_row['cost'];

		$search_visits = $search_row['visits'];
		$search_revenue = $search_row['revenue'];
		$search_conversions = $search_row['conversions'];
		$search_conversions_percent = ($search_conversions / (($search_visits > 0) ? $search_visits : 1))*100;

		$referrer_visits = $referrer_row['visits'];
		$referrer_revenue = $referrer_row['revenue'];
		$referrer_conversions = $referrer_row['conversions'];
		$referrer_conversions_percent = ($referrer_conversions / (($referrer_visits > 0) ? $referrer_visits : 1))*100;
		
		// the totals for the top of the page.
		$TotalVisits = $campaign_visits + $ppc_visits + $search_visits + $referrer_visits;
		$TotalConversions = $campaign_conversions + $ppc_conversions + $search_conversions + $referrer_conversions;
		$GLOBALS['TotalVisits'] = $this->FormatNumber($TotalVisits);
		$GLOBALS['TotalConversions'] = $this->FormatNumber($TotalConversions);
		$perc = $TotalConversions / (($TotalVisits > 0) ? $TotalVisits : 1);
		$GLOBALS['TotalConversionsPercent'] = $this->FormatNumber($perc * 100, 2);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber(($campaign_revenue + $ppc_revenue + $search_revenue + $referrer_revenue), 2);
		$total_cost = ($campaign_cost + $ppc_cost);
		$total_roi = 0;
		if ($total_cost > 0) {
			$total_roi = ($campaign_revenue + $ppc_revenue) / ($total_cost);
		}
		$GLOBALS['TotalROI'] = $this->FormatNumber($total_roi, 2);

		// now we separate out each item.
		$campaign_percent = $campaign_visits / (($TotalVisits > 0) ? $TotalVisits : 1);
		$GLOBALS['CampaignVisits'] = $this->FormatNumber($campaign_visits);
		$GLOBALS['CampaignVisits_Percent'] = $this->FormatNumber($campaign_percent * 100, 2);
		$GLOBALS['CampaignConversions'] = $this->FormatNumber($campaign_conversions);
		$GLOBALS['CampaignConversions_Percent'] = $this->FormatNumber($campaign_conversions_percent, 2);
		$GLOBALS['CampaignRevenue'] = $this->FormatNumber($campaign_revenue, 2);
		$GLOBALS['CampaignROI'] = $this->FormatNumber($campaign_roi, 2);

		$ppc_percent = $ppc_visits / (($TotalVisits > 0) ? $TotalVisits : 1);
		$GLOBALS['PPCVisits'] = $this->FormatNumber($ppc_visits);
		$GLOBALS['PPCVisits_Percent'] = $this->FormatNumber($ppc_percent * 100, 2);
		$GLOBALS['PPCConversions'] = $this->FormatNumber($ppc_conversions);
		$GLOBALS['PPCConversions_Percent'] = $this->FormatNumber($ppc_conversions_percent, 2);
		$GLOBALS['PPCRevenue'] = $this->FormatNumber($ppc_revenue, 2);
		$GLOBALS['PPCROI'] = $this->FormatNumber($ppc_roi, 2);

		$search_percent = $search_visits / (($TotalVisits > 0) ? $TotalVisits : 1);
		$GLOBALS['SearchVisits'] = $this->FormatNumber($search_visits);
		$GLOBALS['SearchVisits_Percent'] = $this->FormatNumber($search_percent * 100, 2);
		$GLOBALS['SearchConversions'] = $this->FormatNumber($search_conversions);
		$GLOBALS['SearchConversions_Percent'] = $this->FormatNumber($search_conversions_percent, 2);
		$GLOBALS['SearchRevenue'] = $this->FormatNumber($search_revenue, 2);

		$referrer_percent = $referrer_visits / (($TotalVisits > 0) ? $TotalVisits : 1);
		$GLOBALS['ReferrerVisits'] = $this->FormatNumber($referrer_visits);
		$GLOBALS['ReferrerVisits_Percent'] = $this->FormatNumber($referrer_percent * 100, 2);
		$GLOBALS['ReferrerConversions'] = $this->FormatNumber($referrer_conversions);
		$GLOBALS['ReferrerConversions_Percent'] = $this->FormatNumber($referrer_conversions_percent, 2);
		$GLOBALS['ReferrerRevenue'] = $this->FormatNumber($referrer_revenue, 2);

		$graph_choice = $thisuser->GetSettings('GraphChoice');
		if (!$graph_choice) $graph_choice = 'revenue';
		$GLOBALS['ChangeGraph'] = '';
		foreach($this->GraphOptions as $p => $option) {
			$selected = '';
			if ($option == $graph_choice) $selected = ' SELECTED';
			$GLOBALS['ChangeGraph'] .= '<option value="' . $option . '" ' . $selected . '>' . GetLang(ucwords($option)) . '</option>';
		}

		// explicitly pass the sessionid across to the chart
		// since it's not the browser but the server making this request, it may get a different session id if we don't, which then means it can't load the data properly.
		// especially applies to windows servers.
		include(dirname(__FILE__) . '/charts/charts.php');
		$GLOBALS['Chart'] = InsertChart(TRACKPOINT_APPLICATION_URL . '/functions/charts/charts.swf', TRACKPOINT_APPLICATION_URL . '/functions/tpchart.php?'.SET_SESSION_NAME.'='.session_id(), 400, 170, 'FFFFFF', false, 'L25E3-1SS818XO2MCIVLN5Y-694C5ISH5U426DY1F7-J');

		$first_hit = $session->Get('FirstHit');

		if (!$first_hit) {
			// find the first tracking hit, so we know when it all started...
			$currtime = time();
			$first_referrer_hit_query = "SELECT currtime from " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID;
			if ($ignoreips) $first_referrer_hit_query .= " AND " . $ignoreips;
			$first_referrer_hit_query .= " ORDER BY currtime ASC LIMIT 1";
			$result = $this->Db->Query($first_referrer_hit_query);
			$first_referrer_hit = $this->Db->FetchOne($result, 'currtime');
			if ($first_referrer_hit == 0) $first_referrer_hit = $currtime;

			$first_search_hit_query = "SELECT currtime from " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;
			if ($ignoreips) $first_search_hit_query .= " AND " . $ignoreips;
			$first_search_hit_query .= " ORDER BY currtime ASC LIMIT 1";
			$result = $this->Db->Query($first_search_hit_query);
			$first_search_hit = $this->Db->FetchOne($result, 'currtime');
			if ($first_search_hit == 0) $first_search_hit = $currtime;

			$first_campaign_hit_query = "SELECT currtime from " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE " . $this->SearchUserID;
			if ($ignoreips) $first_campaign_hit_query .= " AND " . $ignoreips;
			$first_campaign_hit_query .= " ORDER BY currtime ASC LIMIT 1";
			$result = $this->Db->Query($first_campaign_hit_query);
			$first_campaign_hit = $this->Db->FetchOne($result, 'currtime');
			if ($first_campaign_hit == 0) $first_campaign_hit = $currtime;

			$first_ppc_hit_query = "SELECT currtime from " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID;
			if ($ignoreips) $first_ppc_hit_query .= " AND " . $ignoreips;
			$first_ppc_hit_query .= " ORDER BY currtime ASC LIMIT 1";
			$result = $this->Db->Query($first_ppc_hit_query);
			$first_ppc_hit = $this->Db->FetchOne($result, 'currtime');
			if ($first_ppc_hit == 0) $first_ppc_hit = $currtime;

			$first_hit = min($first_referrer_hit, $first_search_hit, $first_campaign_hit, $first_ppc_hit);

			$session->Set('FirstHit', $first_hit);
		}

		$GLOBALS['FirstVisit'] = sprintf(GetLang('FirstVisit'), date(GetLang('DateFormat'), $first_hit));

		$this->ParseTemplate('Index');

		// if we're switching graphs, don't bother with the quickstart guide.
		if ($thisuser->ShowQuickStart() && !isset($_GET['Graph'])) {
			?>
			<script language="javascript">
				launchQS();
			</script>
			<?php
		}

		$this->PrintFooter();
	}
}
?>
