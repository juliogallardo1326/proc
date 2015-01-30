<?php
/**
* This file does the charting for the main index page.
*
* @version     $Id: tpchart.php,v 1.8 2005/10/20 03:32:14 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
* @filesource
*/

/**
* Since we are calling this file differently, we need to include init ourselves and then include the base trackpoint functions.
*/
require(dirname(__FILE__) . '/init.php');
require_once(dirname(__FILE__) . '/trackpoint_functions.php');


/**
* This file does the charting for the main index page.
* The class is called in this file (chart wouldn't work by passing it like other Trackpoint pages).
* Doing it this way means easy access to all regular trackpoint functions and restrictions (eg userid's etc).
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class TPChart extends TrackPoint_Functions {

	/**
	* Constructor
	* Sets up the database connection.
	*
	* @see GetDatabase
	*
	* @return void
	*/
	function TPChart() {
		$db = &GetDatabase();
		$this->Db = &$db;
	}
	
	/**
	* Process
	* Does all of the work. Includes the chart, works out the data, prints it out.
	*
	* @return void
	*/
	function Process() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$this->CalculateCalendarRestrictions();
		$this->GetSearchUser();

		$ignoreips = $this->GetIgnoreDetails();
		$ignorereferrers = $this->GetIgnoreDetails('Referrers');
		$ignorekeywords = $this->GetIgnoreDetails('Keywords');

		// http://www.maani.us/charts/index.php?menu=Reference&submenu=chart_value
		include(dirname(__FILE__) . '/charts/charts.php');
		$chart['chart_type'] = 'column';

		$chart [ 'series_switch' ] = true;

		//hide the legend
		$chart [ 'legend_rect' ] = array ( 'x'=>-1000 , 'y'=>-1000 ); 

		$chart['chart_data'] = array();

		$chart ['chart_data'][0][0] = '';

		$graph_choice = $thisuser->GetSettings('GraphChoice');

		// since getsettings returns an array by default, we have to check for it.
		// if it's an array or if it's empty, then we'll set it to the default (revenue).
		// this will get set properly if/when the choice is changed.
		if (is_array($graph_choice) || !$graph_choice) $graph_choice = '';

		switch(strtolower($graph_choice)) {
			case 'visits':
				$prefix = '';
				$chart_title = GetLang('Chart_Title_Visits');

				$chart_value = $graph_choice;
				$chart_data = GetLang(ucwords($chart_value));
			break;

			case 'conversions':
				$prefix = '';
				$chart_title = GetLang('Chart_Title_Conversions');

				$chart_value = $graph_choice;
				$chart_data = GetLang(ucwords($chart_value));

			break;

			default:
				$prefix = GetLang('CurrencySymbol');

				$chart_title = GetLang('Chart_Title') . " (". GetLang('CurrencySymbol') . ")";

				$chart_value = 'revenue';
				$chart_data = GetLang(ucwords($chart_value));
			break;
		}

		$count = 1;

		foreach($this->ExportTypes as $p => $type) {
			$chart['chart_data'][0][] = GetLang($type);
			$chart['chart_data'][1][] = $chart_data;

			if ($type == 'campaign' || $type == 'ppc') {
				$qry = "SELECT COUNT(" . $this->ExportDbInfo[$type]['key'] . ") AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions, SUM(cost) AS cost, (SUM(amount) / SUM(cost)*100) AS roi FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$type]['table'] . " WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
				if ($ignoreips) $qry .= " AND " . $ignoreips;
			} else {
				$qry = "SELECT COUNT(" . $this->ExportDbInfo[$type]['key'] . ") AS visits, SUM(amount) AS revenue, SUM(hasconversion) AS conversions FROM " . TRACKPOINT_TABLEPREFIX . $this->ExportDbInfo[$type]['table'] . " WHERE " . $this->SearchUserID . ($this->CalendarRestrictions ? ' AND ' . $this->CalendarRestrictions : '');
				if ($ignoreips) $qry .= " AND " . $ignoreips;
				if ($type == 'referrer') {
					if ($ignorereferrers) $qry .= " AND " . $ignorereferrers;
				}
				if ($type == 'search') {
					if ($ignorekeywords) $qry .= " AND " . $ignorekeywords;
				}
			}

			$result = $this->Db->Query($qry);
			$row = $this->Db->Fetch($result);
			
			$chart['chart_data'][1][$count] = $row[$chart_value];
			$chart['chart_value_text'][2][$count] = $this->FormatNumber($row[$chart_value], 2);
			$count++;
		}

		$chart['chart_value'] = array(
			'prefix' => $prefix,
			'decimals' => 2,
			'decimal_char' => GetLang('NumberFormat_Dec'),
			'separator' => GetLang('NumberFormat_Thousands'),
			'color' => 'FFFFFF',
			'bold' => false,
			'position' => 'inside',
			'hide_zero' => true
		);
		
		$chart[ 'chart_bg' ] = array ( 'positive_color'=>"000000", 'positive_alpha'=>0, 'negative_color'=>"FFFFFF",  'negative_alpha'=>0 );
		
		$chart['chart_grid_h'] = array(
			'thickness' => 1
		);
		
		$chart['chart_grid_v'] = array(
			'thickness' => 1
		);
		
		$chart['draw_text'] = array(
			array(
				'x' => 0,
				'y' => 0,
				'width' => 400,
				'height' => 40,
				'h_align' => 'center',
				'v_align' => 'top',
				'text' => $chart_title,
				'color' => '000000',
				'size' => 11,
				'alpha' => 100
			)
		);

		$chart [ 'series_color' ] = array ( "E74D00", "FFBE21", "84B221", "6379AD" ); 

		SendChartData($chart);
	}
}

/**
* We need to call the chart ourselves because of the way it's included and set up.
*/
$TPChart = &new TPChart();
$TPChart->Process();

?>
