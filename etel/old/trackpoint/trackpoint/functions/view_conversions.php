<?php
/**
* This file has the conversion display functions in it.
*
* @version     $Id: view_conversions.php,v 1.7 2005/11/16 07:05:28 chris Exp $
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
* This class has the conversion display functions in it.
* It will easily let us see where orders came from, how much and when etc.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class View_Conversions extends TrackPoint_Functions {

	/**
	* @var string The primary sort for this page.
	*/
	var $PrimarySort = 'ordertime';

	/**
	* @var Array An array of sort types. This is overwritten from the parent class.
	*
	* @see Process
	*/
	var $_SortTypes = array('revenue', 'ordertime', 'name', 'origin', 'type');

	/**
	* @var Array An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'OrderTime', 'name' => 'OrderTime', 'origin' => 'OrderTime', 'type' => 'OrderTime');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function View_Conversions() {
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

		$this->CalculateCalendarRestrictions();
		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		$ignorereferrers_case = false;
		if ($ignorereferrers) {
			$ignorereferrers_case = " AND (CASE WHEN origintype='referrer' AND " . str_replace('NOT LIKE', 'LIKE', str_replace('domain', 'originfrom', $ignorereferrers)) . " THEN 1=0 ELSE 1=1 END)";
		}

		$ignorekeywords_case = false;
		if ($ignorekeywords) {
			$ignorekeywords_case = " AND (CASE WHEN origintype='search' AND " . str_replace('NOT LIKE', 'LIKE', str_replace('keywords', 'origindetails', $ignorekeywords)) . " THEN 1=0 ELSE 1=1 END)";
		}

		$query = "SELECT COUNT(conversionid) AS convcount FROM " . TRACKPOINT_TABLEPREFIX . "conversions WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		if ($ignorereferrers && $ignorereferrers_case) {
			$query .= $ignorereferrers_case;
		}
		if ($ignorekeywords && $ignorekeywords_case) {
			$query .= $ignorekeywords_case;
		}

		$result = $this->Db->Query($query);
		$NumConversions = $this->Db->FetchOne($result, 'convcount');

		$DisplayPage = (isset($_GET['DisplayPage'])) ? (int)$_GET['DisplayPage'] : 1;

		$this->SetupCalendar();

		$this->GetSortDetails();

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$sortdetails = '&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$GLOBALS['SortDetails'] = $sortdetails;

		$formaction = 'Action=ProcessPaging&SortBy=' . $sortby . '&Sort=' . $sortdirection;
		$this->SetupPagingHeader($NumConversions, $DisplayPage, $perpage, $formaction);

		$template = $this->ParseTemplate('View_Conversions', true, false);

		$query = "SELECT conversionid, name, amount AS revenue, currtime AS ordertime, origintype AS type, originfrom AS origin, origindetails, ip FROM " . TRACKPOINT_TABLEPREFIX . "conversions WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		if ($ignorereferrers) {
			$query .= $ignorereferrers_case;
		}
		if ($ignorekeywords) {
			$query .= $ignorekeywords_case;
		}

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
			$row['type'] = stripslashes($row['type']);
			$row['origin'] = stripslashes($row['origin']);
			$GLOBALS['Type'] = GetLang('ViewConversions_' . $row['type']);

			$GLOBALS['IPAddress'] = $row['ip'];

			switch($row['type']) {
				case 'referrer':
					if ($row['origin'] == '') {
						$GLOBALS['Origin'] = GetLang('DirectVisit');
						$row['origindetails'] = GetLang('NA');
					} else {
						if (substr($row['origin'], 0, 4) == 'http') {
							$GLOBALS['Origin'] = '<a href="' . $row['origin'] . '" target="_blank">' . $this->TruncateName($row['origin'], 25) . '</a>';
						} else {
							$GLOBALS['Origin'] = $this->TruncateName($row['origin'], 25);
						}
					}
				break;
				default:
					$GLOBALS['Origin'] = $this->TruncateName($row['origin'], 25);
			}

			$GLOBALS['OrderTime'] = date(GetLang('TimeFormat'), $this->AdjustTime($row['ordertime']));

			$details = stripslashes($row['origindetails']);
			$GLOBALS['FullDetails'] = $details;
			if (substr($details, 0, 4) == 'http') {
				$GLOBALS['Details'] = '<a href="' . $details . '" target="_blank">' . $this->TruncateName($details, 40) . '</a>';
			} else {
				$GLOBALS['Details'] = $this->TruncateName($details, 40);
			}

			$GLOBALS['FullName'] = stripslashes($row['name']);
			$GLOBALS['Name'] = $this->TruncateName(stripslashes($row['name']));

			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$display .= $this->ParseTemplate('View_ConversionsRows', true, false);
			$rowid++;
		}

		if ($rowid == 1) {
			// if there are no rows, we'll add a "blank" row.
			$display .= $this->ParseTemplate('View_ConversionsRows_Blank', true, false) . '<br/>';
		}

		$query = "SELECT COUNT(conversionid) AS convcount, SUM(amount) AS revenue FROM " . TRACKPOINT_TABLEPREFIX . "conversions  WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');

		if ($ignoreips) $query .= " AND " . $ignoreips;

		if ($ignorereferrers) {
			$query .= $ignorereferrers_case;
		}
		if ($ignorekeywords) {
			$query .= $ignorekeywords_case;
		}

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$GLOBALS['TotalConversions'] = $this->FormatNumber($row['convcount']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
		$conversion_footer = $this->ParseTemplate('View_Conversions_Footer', true, false);

		$template = str_replace('%%TPL_ConversionFooter%%', $conversion_footer, $template);

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		$template = str_replace('%%TPL_ViewConversions_Rows%%', $display, $template);
		echo $template;
		$this->PrintFooter();
	}
}

?>
