<?php
/**
* This file handles ppc functionality. It handles sorting, grouping, paging etc.
*
* @version     $Id: ppc.php,v 1.19 2005/10/20 03:32:14 chris Exp $
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
* This class handles ppc functionality. It handles sorting, grouping, paging etc.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class PPC extends TrackPoint_Functions {

	/**
	* @var _SortTypes An array of sort types. This is overwritten from the parent class.
	*
	* @see Process
	*/
	var $_SortTypes = array('revenue', 'conv', 'percent', 'visits', 'roi', 'cost');

	/**
	* @var _Secondary_SortTypes An array of secondary sort types. The first element is the key sort, the value is what to sort by next.
	*
	* @see Process
	*/
	var $_Secondary_SortTypes = array('revenue' => 'Visits', 'percent' => 'Visits');

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	* Does nothing.
	*
	* @return void
	*/
	function PPC() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}

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

		$query = "SELECT COUNT(DISTINCT searchenginename) AS ppccount FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);
		$NumPPCs = $row['ppccount'];

		$this->SetupPagingHeader($NumPPCs, $DisplayPage, $perpage, $formaction);

		$template = $this->ParseTemplate('PPC', true, false);

		$query = "SELECT searchenginename AS searchengine, SUM(cost) AS cost, COUNT(ppcid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;
		$query .= " GROUP BY searchenginename";
		$query .= " ORDER BY " . $sortby . " " . $direction;
		if ($second_sortby) $query .= ", " . $second_sortby . " " . $second_sortdirection;
		$query .= $this->Db->AddLimit(($perpage * ($DisplayPage - 1)), $perpage);
		
		$rowid = 1;
		$display = '';

		$base_fetchlink = 'SortBy=' . urlencode($sortby) . '&Direction=' . urlencode($direction);

		$result = $this->Db->Query($query);
		while($row = $this->Db->Fetch($result)) {
			$GLOBALS['RowID'] = $rowid;
			$GLOBALS['FetchLink'] = $base_fetchlink . '&Engine=' . urlencode($row['searchengine']);
			$GLOBALS['PPCEngine'] = stripslashes($row['searchengine']);
			$GLOBALS['Visits'] = $this->FormatNumber($row['visits']);
			$GLOBALS['Conversions'] = $this->FormatNumber($row['conv']);

			$GLOBALS['Revenue'] = $this->FormatNumber($row['revenue'], 2);
			$GLOBALS['Percent'] = $this->FormatNumber($row['percent'], 2);
			$GLOBALS['Cost'] = $this->FormatNumber($row['cost'], 2);
			$GLOBALS['ROI'] = $this->FormatNumber($row['roi'], 2);

			$display .= $this->ParseTemplate('PPCRows', true, false);
			$rowid++;
		}
		
		if ($rowid == 1) {
			// if there are no rows, we'll add a "blank" row.
			$display .= $this->ParseTemplate('PPCRows_Blank', true, false) . '<br/>';
		}

		$template = str_replace('%%TPL_Paging%%', $GLOBALS['PagingTemplate'], $template);
		$template = str_replace('%%TPL_Paging_Bottom%%', $GLOBALS['PagingTemplate_Bottom'], $template);

		$template = str_replace('%%TPL_Calendar%%', $GLOBALS['Calendar'], $template);

		$template = str_replace('%%TPL_PPCRows%%', $display, $template);


		$query = "SELECT COUNT(ppcid) AS visits, SUM(hasconversion) AS conv, SUM(amount) AS revenue, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, SUM(cost) AS ppccost, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$result = $this->Db->Query($query);
		$row = $this->Db->Fetch($result);

		$GLOBALS['TotalVisits'] = $this->FormatNumber($row['visits']);
		$GLOBALS['TotalConversions'] = $this->FormatNumber($row['conv']);
		$GLOBALS['TotalRevenue'] = $this->FormatNumber($row['revenue'], 2);
		$GLOBALS['TotalPercent'] = $this->FormatNumber($row['percent'], 2);
		$GLOBALS['TotalCost'] = $this->FormatNumber($row['ppccost'], 2);
		$GLOBALS['TotalROI'] = $this->FormatNumber($row['roi'], 2);

		if ($row['visits'] > 0) {
			$GLOBALS['ExportSection'] = $GLOBALS['PrintSection'] = '&Area=PPC';
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter', true, false);
		} else {
			$GLOBALS['PrintExportFooter'] = $this->ParseTemplate('PrintExportFooter_Disabled', true, false);
		}

		$ppc_footer = $this->ParseTemplate('PPCFooter', true);
		$template = str_replace('%%TPL_PPCFooter%%', $ppc_footer, $template);

		echo $template;

		$this->PrintFooter();
	}

	function GenerateXml() {
		header('Content-Type: text/xml');
		?>
		<data>
		<?php

		$this->CalculateCalendarRestrictions();

		$this->GetSortDetails();

		$ignoreips = $this->GetIgnoreDetails();

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$engine = stripslashes(urldecode($_GET['Engine']));

		$baseaction = '';

		$query = "SELECT ppcname AS name, COUNT(ppcid) AS visits, SUM(cost) AS cost, SUM(hasconversion) AS conv, (SUM(hasconversion) / (COUNT(ppcid)+0.0)*100) AS percent, SUM(amount) AS revenue, CASE WHEN SUM(cost) = 0 THEN 0 ELSE (SUM(amount) / SUM(cost)*100) END AS roi FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE " . $this->SearchUserID;

		if ($this->CalendarRestrictions) $query .= ' AND ' . $this->CalendarRestrictions;
		if ($ignoreips) $query .= " AND " . $ignoreips;

		$query .= " AND searchenginename='" . addslashes($engine) . "'";
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
						index.php?Page=ViewAll_PPCs&amp;Engine=<?php echo urlencode($engine); ?>
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
			
			$cost = $row['cost'];

			$conversionspercent = $row['percent'];

			$roi = $row['roi'];

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
