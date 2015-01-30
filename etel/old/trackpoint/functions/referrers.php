<?php
/**
* This file has the referrer display functions in it.
*
* @version     $Id: referrers.php,v 1.31 2005/10/20 03:32:14 chris Exp $
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
* This class has the referrer display functions in it.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Referrers extends TrackPoint_Functions {

	/**
	* @var NotClickableLinks A list of links that aren't clickable for whatever reason. Doesn't include direct visits - they are handled separately.
	*
	* @see Process
	*/
	var $NotClickableLinks = array('blockedreferrer', 'hidden-referrer');

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'Visits', 'visits' => 'Domain', 'domain' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	* Adds 'domain' to the list of sort types.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function Referrers() {
		$this->_SortTypes[] = 'domain';
		$db = &GetDatabase();
		$this->Db = &$db;
	}

	/**
	* Process
	* Does all the work.
	* Prints out the menu, sets up the paging, sets up the calendar, sorts results and so on.
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
		$this->SetupCalendar();

		$this->GetSortDetails();

		$this->RememberCurrentPage(true);

		$query = "SELECT COUNT(DISTINCT domain) AS domaincount FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumDomains = $row['domaincount'];

		$query = "SELECT COUNT(referrerid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$TotalVisits = $row['totalvisits'];
		$TotalRevenue = $row['revenue'];
		$TotalConv = $row['conv'];
		$TotalPercent = $row['percent'];

		$GLOBALS['ReferrerTotal'] = $this->FormatNumber($TotalVisits);
		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($TotalPercent, 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($TotalConv);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($TotalRevenue, 2);

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$this->SetupPagingHeader($NumDomains, $DisplayPage, $perpage, $formaction);

		$template = $this->ParseTemplate('Referrers', true, false);

		$query = "SELECT domain, COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
		$query .= " GROUP BY domain";

		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;

		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$base_fetchlink = 'SortBy=' . urlencode($sortby) . '&Direction=' . urlencode($direction);

		$base_landingpage = 'index.php?Page=LandingPages_Referrers';

		$rowid = 1;
		$display = '';

		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$GLOBALS['LandingPageURL'] = $base_landingpage . '&Domain=' . urlencode($row['domain']);

			$GLOBALS['FetchLink'] = $base_fetchlink . '&Domain=' . urlencode($row['domain']);

			if ($row['domain'] == '') {
				$GLOBALS['Link'] = GetLang('DirectVisit');
			} else {
				if (in_array(strtolower($row['domain']), $this->NotClickableLinks)) {
					$GLOBALS['Link'] = $row['domain'];
				} else {
					$GLOBALS['Link'] = '<a href="' . $row['domain'] . '" target="_blank">' . $this->TruncateName($row['domain'], 60) . '</a>';
				}
			}

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$GLOBALS['AltTitle'] = $row['domain'];
			$GLOBALS['Domain'] = $row['domain'];
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);

			$display .= $this->ParseTemplate('ReferrerRows', true, false);
			$rowid++;
		}

		if ($rowid == 1) {
			// if there are no rows, we'll add a "blank" row.
			$display .= $this->ParseTemplate('ReferrerRows_Blank', true, false) . '<br/>';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter_Disabled', true, false);
		} else {
			$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Referrer';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);
		}

		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Referrers';

		$referrerresults_footer = $this->ParseTemplate('ReferrerResultsFooter', true);
		$template = str_replace('%%TPL_ReferrerResultsFooter%%', $referrerresults_footer, $template);

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		$template = str_replace('%%TPL_ReferrerRows%%', $display, $template);
		echo $template;
		$this->PrintFooter();
	}


	function GenerateXml() {
		header('Content-Type: text/xml');
		?>
		<data>
		<?php

		$this->CalculateCalendarRestrictions();
		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');

		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$domain = stripslashes(urldecode($_GET['Domain']));

		$baseaction = 'index.php?Page=LandingPages_Referrers&amp;Domain=' . urlencode($domain);

		$query = "SELECT url, COUNT(referrerid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(referrerid)+0.0)*100) AS percent, SUM(amount) AS revenue FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE " . $this->SearchUserID;

		if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorereferrers) $query .= " AND " . $ignorereferrers;
		$query .= " AND domain='" . addslashes($domain) . "'";
		$query .= " GROUP BY url, domain";
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
					<conversions>
						0
					</conversions>
					<conversionspercent>
						0
					</conversionspercent>
					<revenue>
						0
					</revenue>
					<action>
						index.php?Page=ViewAll_Referrers&amp;Domain=<?php echo urlencode($domain); ?>
					</action>
					<viewall>
						1
					</viewall>
				</item>
				<?php
				continue;
			}

			$action = $baseaction . '&amp;URL=' . urlencode($row['url']);

			$revenue = $row['revenue'];
			$conversions = $row['conv'];

			$conversionspercent = $row['percent'];

			$url = $row['url'];
			if ($url == '') $url = GetLang('DirectVisit');

			?>
				<item>
					<contents>
						<?php echo htmlentities($url); ?>
					</contents>
					<visits>
						<?php echo $this->FormatNumber($row['visits']); ?>
					</visits>
					<conversions>
						<?php echo $this->FormatNumber($conversions, 0); ?>
					</conversions>
					<conversionspercent>
						<?php echo $this->FormatNumber($conversionspercent, 2); ?>
					</conversionspercent>
					<revenue>
						<?php echo $this->FormatNumber($revenue, 2); ?>
					</revenue>
					<action>
						<?php echo $action; ?>
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
