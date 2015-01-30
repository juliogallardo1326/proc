<?php
/**
* This file has the search engine functions in it.
*
* @version     $Id: engines.php,v 1.29 2005/10/20 03:32:14 chris Exp $
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
* Class for the search engine results page. Organises sorting, paging and so on.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Engines extends TrackPoint_Functions {

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('keywords' => 'Visits', 'visits' => 'Keywords', 'revenue' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function Engines() {
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
		$ignorekeywords = $this->GetIgnoreDetails('keywords');

		$this->RememberCurrentPage(true);

		$query = "SELECT COUNT(DISTINCT searchenginename) AS enginecount FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumKeywords = $row['enginecount'];

		$query = "SELECT COUNT(searchid) AS totalvisits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0) *100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		// we use this number below - so don't format it.
		$TotalVisits = $row['totalvisits'];

		$GLOBALS['TotalConversionPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalConversion'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);

		$query = "SELECT COUNT(searchid) AS totalvisits FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
		$result = $this->Db->Query($query);

		$row = $this->Db->Fetch($result);
		$TotalVisits = $row['totalvisits'];

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$this->SetupPagingHeader($NumKeywords, $DisplayPage, $perpage, $formaction);

		$GLOBALS['SearchTotal'] = $this->FormatNumber($TotalVisits);

		$template = $this->ParseTemplate('Engines', true, false);

		$query = "SELECT searchenginename, COUNT(searchid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(searchid)+0.0) *100) AS percent FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;
		if ($this->CalendarRestrictions) $query .= " AND " . $this->CalendarRestrictions;
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
		$query .= " GROUP BY searchenginename";
		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby != 'keywords') {
			if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		}
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);

		$result = $this->Db->Query($query);
		if (!$result) {
			return false;
		}

		$rowid = 1;
		$display = '';

		$base_fetchlink = 'SortBy=' . urlencode($sortby) . '&Direction=' . urlencode($direction);

		$base_landingpage = 'index.php?Page=LandingPages_Engines';

		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$GLOBALS['LandingPageURL'] = $base_landingpage . '&Engine=' . urlencode($row['searchenginename']);
			$GLOBALS['FetchLink'] = $base_fetchlink . '&Name=' . urlencode($row['searchenginename']);

			$GLOBALS['Conv'] = $this->FormatNumber($row['conv']);
			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['ConvPercent'] = $this->FormatNumber($row['percent'], 2);

			$GLOBALS['EngineName'] = $this->TruncateName($row['searchenginename']);

			$GLOBALS['AltTitle'] = $row['searchenginename'];

			$GLOBALS['SearchVisits'] = $this->FormatNumber($row['visits']);
			$display .= $this->ParseTemplate('EnginesRows', true, false);
			$rowid++;
		}
		
		if ($rowid == 1) {
			// if there are no rows, we'll add a "blank" row.
			$display .= $this->ParseTemplate('EnginesRows_Blank', true, false) . '<br/>';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter_Disabled', true, false);
		} else {
			$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=Search';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);
		}

		$GLOBALS['LandingPageLink'] = 'index.php?Page=LandingPages_Engines';
		$searchresults_footer = $this->ParseTemplate('SearchResultsFooter', true);
		$template = str_replace('%%TPL_SearchResultsFooter%%', $searchresults_footer, $template);

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		$template = str_replace('%%TPL_EnginesRows%%', $display, $template);

		echo $template;
		$this->PrintFooter();
	}


	/**
	* GenerateXml
	* Generates XML for the hidden information for search engines. Works out costs, visits, conversions and so on depending on the search engine.
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
		$ignorekeywords = $this->GetIgnoreDetails('keywords');
		$this->GetSortDetails();
		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$enginename = stripslashes(urldecode($_GET['Name']));

		$baseaction = 'index.php?Page=LandingPages_Engines&amp;Engine=' . urlencode($enginename);

		$query = "SELECT keywords AS name, COUNT(searchid) AS visits, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(searchid)+0.0) *100) AS percent, SUM(amount) AS revenue FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE " . $this->SearchUserID;

		if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
		if ($ignoreips) $query .= " AND " . $ignoreips;
		if ($ignorekeywords) $query .= " AND " . $ignorekeywords;
		$query .= " AND searchenginename='" . addslashes($enginename) . "'";
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
						index.php?Page=ViewAll_Engines&amp;Engine=<?php echo urlencode($enginename); ?>
					</action>
					<viewall>
						1
					</viewall>
				</item>
				<?php
				continue;
			}

			$action = $baseaction . '&amp;Keywords=' . urlencode($row['name']);

			$revenue = $row['revenue'];
			$conversions = $row['conv'];

			$conversionspercent = $row['percent'];

			?>
				<item>
					<contents>
						<?php echo htmlentities(strtolower($row['name'])); ?>
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
