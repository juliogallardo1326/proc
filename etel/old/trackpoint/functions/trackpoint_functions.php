<?php
/**
* This file has the base functions in it. For example, headers, footers.
*
* @version     $Id: trackpoint_functions.php,v 1.39 2005/11/16 07:05:28 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
* @filesource
*/

/**
* Make sure nobody is doing a sneaky and trying to go to the page directly.
*/
if (!defined('TRACKPOINT_BASE_DIRECTORY')) {
	header('Location: ../index.php');
}

/**
* Base class for TrackPoint Functions.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class TrackPoint_Functions {

	/**
	* @var PageTitle Title of the page. Used by PrintHeader to display the correct info.
	*
	* @see PrintHeader
	*/
	var $PageTitle = 'TrackPoint';


	/**
	* @var GlobalAreas You can set global areas by putting them in this array. If they are in here, they will be used by ParseTemplate
	*
	* @see ParseTemplate
	*/
	var $GlobalAreas = array();


	/**
	* @var _RandomStrings - an array of helptip id's that have been generated. By remembering them here, we can ensure that they are unique.
	*
	* @see _GenerateHelpTip
	*/
	var $_RandomStrings = array();


	/**
	* @var _SortDirections - an array of directions we can sort by. This just makes sure that nobody is going to try and cause any sql errors and thus see any code.
	*
	* @see GetSortDetails
	*/
	var $_SortDirections = array('Up', 'Down');


	/**
	* @var _SortTypes A list of valid sort types. This is used by most Process functions to make sure that people aren't going to get any invalid sort types (and thus show any code).
	*
	* @see Search::Process
	* @see Referrers::Process
	* @see Engines::Process
	*/
	var $_SortTypes = array('revenue', 'conv', 'searchenginename', 'visits', 'landingpage', 'keywords', 'percent');


	/**
	* @var _SortTypes A list of valid sort types. This is used by Search::Process, Referrers::Process, Engines::Process to make sure that people aren't going to get any invalid sort types (and thus show any code).
	*
	* @see Search::Process
	* @see Referrers::Process
	* @see Engines::Process
	*/
	var $SpecialSortTypes = array('revenue', 'conv', 'convpercent');

	/**
	* @var _MaxNameLength The maximum length of a name (eg keywords, url, etc). If a name is longer than this length, it is chopped off (4 chars early) and has ' ...' appended to it.
	*
	* @see TruncateName
	*/
	var $_MaxNameLength = 45;


	/**
	* @var _PagesToShow This controls how many pages we show when we are creating the paging. This includes the current page. For example, if we are on page 7 of 20, we will see pages 5,6,7,8,9.
	* It should be an odd number so we get an even amount either side of the current page.
	*
	* @see SetupPagingHeader
	*/
	var $_PagesToShow = 7;


	/**
	* @var _SubSearchLimit This controls how many sub-elements we show under each 'category'.
	*
	* @see Search::Process
	* @see Engines::Process
	* @see Referrers::Process
	*/
	var $_SubSearchLimit = 5;


	/**
	* @var _PerPageDefault Default number to show per page. This is used if the user hasn't set anything before (in session).
	*
	* @see GetPerPage
	*/
	var $_PerPageDefault = 10;


	/**
	* @var _PagingMinimum Number of records to show before we start showing the paging at the bottom of the screen.
	*
	* @see SetupPagingHeader
	*/
	var $_PagingMinimum = 5;


	/**
	* @var Db Reference to the database so the other pages don't have to keep fetching it.
	*
	* @see GetDatabase
	*/
	var $Db = null;


	/**
	* @var CalendarRestrictions Store the calendar restrictions in the class - easy reference.
	*
	* @see CalculateCalendarRestrictions
	*/
	var $CalendarRestrictions = false;


	/**
	* @var searchuserid Store the searchuserid in the class - easy reference.
	*
	* @see GetSearchUser
	*/
	var $SearchUserID = null;


	/**
	* @var SortDetails Sort Details - what we are sorting by and what direction we are sorting.
	*
	* @see GetSortDetails
	*/
	var $SortDetails = array();


	/**
	* @var PrimarySort Primary sort order. This is the default for when you first visit a page.
	*
	* @see GetSortDetails
	*/
	var $PrimarySort = 'Visits';


	/**
	* @var SearchEngines An array of search engines loaded from the se.ini file in the includes directory.
	*
	* @see FetchSearchEngineLink
	*/
	var $SearchEngines = array();
	
	
	/**
	* @var Months An array of months. This lets us quickly grab the right language pack variable.
	*
	* @see SetupCalendar
	* @see GetLang
	*/
	var $Months = array(
		'1' => 'Jan',
		'2' => 'Feb',
		'3' => 'Mar',
		'4' => 'Apr',
		'5' => 'May',
		'6' => 'Jun',
		'7' => 'Jul',
		'8' => 'Aug',
		'9' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Dec'
	);

	/**
	* @var ExportTypes A list of data to export. This is used both for printing & exporting.
	*
	* @see PrintReport::Process
	* @see Export::Process
	* @see ExportDbInfo
	*/
	var $ExportTypes = array('campaign', 'ppc', 'search', 'referrer');
	
	/**
	* @var ExportDbInfo This lists data for each ExportType so we have the key, the table name and the toplevel so we can separate the data properly.
	*
	* @see PrintReport::Process
	* @see Export::Process
	* @see ExportTypes
	*/
	var $ExportDbInfo = array(
		'ppc' => array('key' => 'ppcid', 'table' => 'payperclicks', 'toplevel' => 'searchenginename'),
		'campaign' => array('key' => 'campaignid', 'table' => 'campaigns', 'toplevel' => 'campaignsite'),
		'search' => array('key' => 'searchid', 'table' => 'search', 'toplevel' => 'searchenginename'),
		'referrer' => array('key' => 'referrerid', 'table' => 'referrers', 'toplevel' => 'domain')
	);

	/**
	* @var GraphOptions A list of graph options we give to the person who logs in.
	*
	* @see Index::Process
	* @see TPChart::Process
	*/
	var $GraphOptions = array('visits', 'revenue', 'conversions');

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function TrackPoint_Functions() {
	}


	/**
	* Process
	* Base process function prints the header, prints the page and the footer.
	* If there is any functionality to provide, it must be overridden by the children objects.
	*
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return void
	*/
	function Process() {
		$this->PrintHeader();
		$this->ParseTemplate('Menu');
		$template = strtolower(get_class($this));
		$this->ParseTemplate($template);
		$this->PrintFooter();
	}


	/**
	* GetApi
	* Gets the API we pass in. If we don't pass in an API to fetch, it will fetch the API based on the class.
	*
	* @param api The name of the API to fetch. If there is nothing passed in, it will fetch the API based on this class.
	*
	* @return mixed Returns an object if it can find the API, otherwise returns false.
	*/
	function GetApi($api=false) {
		if (!$api) $api = get_class($this);
		$api = strtolower($api);
		$api_file = TRACKPOINT_API_DIRECTORY.'/' . $api . '.php';
		if (!is_file($api_file)) return false;
		require_once($api_file);
		$api .= '_API';
		$myapi = &New $api();
		return $myapi;
	}


	/**
	* ParseTemplate
	* Loads the template that you pass in. Replaces any placeholders that you set in GlobalAreas and then goes through, looks for language placeholders, request vars, global vars and replaces them all.
	*
	* @param Template The name of the template to load and then display.
	* @param Return Whether to return the template or just display it. Default is to display it.
	* @param Recurse Whether to recurse into other templates that are included or not.
	*
	* @see GetLang
	* @see GlobalAreas
	* @see _GenerateHelpTip
	* @see GetSession
	* @see Session::LoggedIn
	* @see Session::Get
	* @see User::Admin
	*
	* @return mixed Returns the template if specified otherwise it returns nothing.
	*/
	function ParseTemplate($templatename=false, $return=false, $recurse=true) {
		if (!$templatename) return false;
		$templatename = strtolower($templatename);
		$template_file = TRACKPOINT_TEMPLATE_DIRECTORY . '/' . $templatename . '.tpl';
		if (!is_file($template_file)) {
			trigger_error(sprintf(GetLang('ErrCouldntLoadTemplate'), ucwords($templatename)), E_USER_ERROR);
		}
		$template = implode('', file($template_file));

		$GLOBALS['TrackPointURL'] = TRACKPOINT_APPLICATION_URL;

		$session = &GetSession();
		if (!$session->LoggedIn()) {
			$template = str_replace('%%GLOBAL_MenuTable%%', '', $template);
			$template = str_replace('%%GLOBAL_TextLinks%%', '', $template);
		}

		list($license_error, $msg) = tpQmz44Rtt();

		if ($session->LoggedIn()) {
			$user = $session->Get('UserDetails');
			if (!isset($GLOBALS['TrackPointUserID'])) $GLOBALS['TrackPointUserID'] = $user->userid;
			$GLOBALS['UserFullName'] = $user->fullname;
			$GLOBALS['UserUserName'] = $user->username;
			$GLOBALS['UserEmailAddress'] = $user->emailaddress;

			if ($templatename == 'header') {
				$switched_user = $session->Get('SwitchUser');
				$switched_username = $session->Get('SwitchUserName');

				$username = $user->username;
				if ($switched_username) {
					$username = $switched_username;
				}

				$textlink = sprintf(GetLang('ViewingStatsAs'), $username) . '&nbsp;&nbsp;|&nbsp;&nbsp;';

				// if we're viewing the users page = don't show who we're viewing stats as. This may cause confusion as you change between users and it doesn't reflect in the header.
				if (isset($_GET['Page']) && strtolower($_GET['Page']) == 'users') {
					$textlink = '';
				}

				$textlink .= '<a class="menu" href="index.php">' . GetLang('Home') . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="menu" href="index.php?Page=Track">' . GetLang('GetTrackingCode') . '</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="menu" href="index.php?Page=Conversion">' . GetLang('GetConversionCode') . '</a>&nbsp;&nbsp;|';

				if (!$user->Admin()) {
					$textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=ManageAccount">' . GetLang('MyAccount') . '</a>&nbsp;&nbsp;|';
				}

				foreach(array('Users', 'Settings') as $area) {
					if ($user->HasAccess($area)) $textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=' . $area . '">' . GetLang($area) . '</a>&nbsp;&nbsp;|';
				}

				if (!isset($_GET['Page']) || $_GET['Page'] != 'Settings') {
					if ($license_error) {
						$textlink = '';
						if ($user->HasAccess('Settings')) {
							$textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=Settings">' . GetLang('Settings') . '</a>&nbsp;&nbsp;|';
						}
						$textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=Logout">' . GetLang('Logout') . '</a>';

						$template = str_replace('%%GLOBAL_TextLinks%%', $textlink, $template);
						$template .= '<div class="body" style="font-size: 13px">' . $msg . '. <a class="menu" href="index.php?Page=Settings" style="font-size: 13px; color: blue;">Click here to update your settings</a>.</div><table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">';

						// Parse out the language pack variables in the template file
						preg_match_all("/(?siU)(%%LNG_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);
				
						foreach($matches[0] as $match)
						{
							$langvar = str_replace(array('%', 'LNG_'), '', $match);
							$template = str_replace($match, GetLang($langvar), $template);
						}

						echo $template;
						$this->PrintFooter();
						exit();
					}
				}

				if ($license_error && (!isset($_GET['Page']) || $_GET['Page'] == 'Settings')) {
					$textlink = '';
					if ($user->HasAccess('Settings')) {
						$textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=Settings">' . GetLang('Settings') . '</a>&nbsp;&nbsp;|';
					}
				}

				$textlink .= '&nbsp;&nbsp;<a class="menu" href="index.php?Page=Logout">' . GetLang('Logout') . '</a>';

				$template = str_replace('%%GLOBAL_TextLinks%%', $textlink, $template);
			}
		}

		if ($license_error && $templatename == 'menu') {
			$template = '<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">';
		}

		foreach($this->GlobalAreas as $area => $val) {
			$template = str_replace('%%GLOBAL_' . $area . '%%', $val, $template);
		}

		$matches = array();
		// Parse out the language pack help variables in the template file
		preg_match_all("/(?siU)(%%LNG_HLP_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach($matches[0] as $match)
		{
			$HelpTip = $this->_GenerateHelpTip($match);
			$template = str_replace($match, $HelpTip, $template);
		}

		// Parse out the language pack variables in the template file
		preg_match_all("/(?siU)(%%LNG_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach($matches[0] as $match)
		{
			$langvar = str_replace(array('%', 'LNG_'), '', $match);
			$template = str_replace($match, GetLang($langvar), $template);
		}

		// Parse out the request variables in the template file
		preg_match_all("/(?siU)(%%REQUEST_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach($matches[0] as $match)
		{
			$request_var = str_replace(array('%', 'REQUEST_'), '', $match);
			$request_value = (isset($_REQUEST[$request_var])) ? $_REQUEST[$request_var] : '';
			$template = str_replace($match, $request_value, $template);
		}

		// Parse out the global variables in the template file
		preg_match_all("/(?siU)(%%GLOBAL_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

		foreach($matches[0] as $match)
		{
			$global_var = str_replace(array('%', 'GLOBAL_'), '', $match);
			$global_value = (isset($GLOBALS[$global_var])) ? $GLOBALS[$global_var] : '';
			if (is_array($global_value)) continue;
			$template = str_replace($match, $global_value, $template);
		}

		if ($recurse) {
			// Parse out the global variables in the template file
			preg_match_all("/(?siU)(%%TPL_[a-zA-Z0-9_]{1,}%%)/", $template, $matches);

			foreach($matches[0] as $match)
			{
				$template_var = str_replace(array('%', 'TPL_'), '', $match);
				$subtemplate = $this->ParseTemplate($template_var, true, $recurse);
				$template = str_replace($match, $subtemplate, $template);
			}
		}

		// Parse out the static variables in the template file
		$template = str_replace('%%PAGE_TITLE%%', GetLang('PageTitle'), $template);

		// this is for the 'Page' part for links. Eg -
		// index.php?Page=Stats
		$thispage = get_class($this);
		$template = str_replace('%%PAGE%%', $thispage, $template);

		if ($return) return $template;
		echo $template;
	}

	/**
	* PrintHeader
	* Prints out the header info. Uses this->PageTitle for the page title. You can also set menuareas up with the MenuAreas array.
	*
	* @param PopupWindow Pass in whether this is a popup window or not. This can be used to work out whether to display the menu at the top of the page.
	*
	* @see Generate
	* @see PageTitle
	* @see MenuAreas
	*
	* @return void
	*/
	function PrintHeader($PopupWindow=false) {
		$GLOBALS['PageTitle'] = $this->PageTitle;
		if ($PopupWindow) {
			$this->ParseTemplate('Header_Popup');
			return;
		}
		$this->ParseTemplate('Header');
	}

	/**
	* PrintFooter
	* Prints out the footer info.
	*
	* @param PopupWindow Pass in whether this is a popup window or not. This can be used to work out whether to display the copyright info at the bottom of the page.
	*
	* @return void
	*/
	function PrintFooter($PopupWindow=false) {
		if ($PopupWindow) {
			$this->ParseTemplate('Footer_Popup');
			return;
		}
		$this->ParseTemplate('Footer');
		$this->DeleteOldCookies();
	}
	
	/**
	* DeleteOldCookies
	*
	* This will delete the cookies that have expired from the database. It checks their time compared to the setting of the cookie time (in the config file).
	* It also cleans up old sessions from the database that have expired or not been used.
	* It also cleans up old logs bsaed on TRACKPOING_LOGHISTORY_TIME. It does this whether the option is on or off, if the option is off it's not going to take any time to delete 0 records.
	*
	* @see TRACKPOINT_ISSETUP
	* @see TRACKPOINT_DATABASE_TYPE
	* @see GetDatabase
	* @see Db::Query
	* @see TRACKPOINT_LOGHISTORY_TIME
	* @see TRACKPOINT_COOKIE_TIME
	*
	* @return void
	*/
	function DeleteOldCookies() {
		if (!TRACKPOINT_ISSETUP) return;
		$limit = 50;

		$time = time() - (TRACKPOINT_COOKIE_TIME * 60 * 60);
		$logtime = time() - (TRACKPOINT_LOGHISTORY_TIME * 30 * 24 * 60 * 60);

		if (strtolower(TRACKPOINT_DATABASE_TYPE) == 'pgsql') {
			$cookie_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE EXISTS (SELECT cookietime FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE cookietime < " . $time . " LIMIT " . $limit . ")";
			$session_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "sessions WHERE EXISTS (SELECT sessiontime FROM " . TRACKPOINT_TABLEPREFIX . "sessions WHERE sessiontime < " . $time . " LIMIT " . $limit . ")";
			$log_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "loghistory WHERE EXISTS (SELECT logid FROM " . TRACKPOINT_TABLEPREFIX . "loghistory WHERE logtime < " . $logtime . " LIMIT " . $limit . ")";
		} else {
			$cookie_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE cookietime < " . $time . " LIMIT " . $limit;
			$session_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "sessions WHERE sessiontime < " . $time . " LIMIT " . $limit;
			$log_query = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "loghistory WHERE logtime < " . $logtime . " LIMIT " . $limit;
		}
		$db = &GetDatabase();
		$db->Query($cookie_query);
		$db->Query($session_query);
		$db->Query($log_query);
	}


	/**
	* _GenerateHelpTip
	* Generates a help tip dynamically.
	* Stores any random helptip id's in this->_RandomStrings so we can make sure there are no duplicates.
	* If you pass in 'LNG_HLP_Status' - the tiptitle is 'LNG_Status', the description is 'LNG_HLP_Status'.
	*
	* @see ParseTemplate
	* @see _RandomStrings
	*
	* @param TipName The name of the tip to create. This will get the variable from the language file and replace it and the title as necessary. The helptip title is the tipname.
	*
	* @return string The help tip that is generated.
	*/
	function _GenerateHelpTip($tipname=false) {
		if (!$tipname) return false;

		$tipname = str_replace(array('%%', 'LNG_'), '', $tipname);

		$chars = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'0','1','2','3','4','5','6','7','8','9'
		);

		while(true) {
			$rand = 'tp';
			$max = sizeof($chars) - 1;
			while(strlen($rand) < 10) {
				$randchar = rand(0, $max);
				$rand .= $chars[$randchar];
			}
			if (!in_array($rand, $this->_RandomStrings)) {
				$this->_RandomStrings[] = $rand;
				break;
			}
		}

		$tiptitle = str_replace('HLP_', '', $tipname);

		$helptip = '<img onMouseOut="HideHelp(\'' . $rand . '\');" onMouseOver="ShowHelp(\'' . $rand . '\', \'' . GetLang($tiptitle) . '\', \'' . GetLang($tipname) . '\');" src="images/help.gif" width="24" height="16" border="0"><div style="display:none" id="' . $rand . '"></div>';
		return $helptip;
	}

	/**
	* SetupPagingHeader
	* Sets up the paging header with page numbers (using $this->_PagesToShow), sets up the 'Next/Back' links, 'First Page/Last Page' links and so on - based on how many records there are, which page you are on currently and the number of records to display per page.
	* Gets settings from the session if it can (based on what you've done previously).
	* Sets the $GLOBALS['DisplayPage'] and $GLOBALS['PerPageDisplayOptions'] so the template can be parsed properly.
	*
	* @param numrecords The number of records to calculate pages for
	* @param currentpage The current page that we're on (so we can highlight the right one)
	* @param perpage Number of records per page we're going to show so we can calculate the right page
	*
	* @see _PagesToShow
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	* @see Engines::Process
	* @see Referrers::Process
	* @see Search::Process
	* @see ParseTemplate
	*
	* @return void 
	*/
	function SetupPagingHeader($numrecords=0, $currentpage=1, $perpage=20, $formaction=null) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$display_settings = $thisuser->GetSettings('DisplaySettings');
		if (empty($display_settings)) {
			$display_settings = array('NumberToShow' => 10);
		}

		$userid = $thisuser->userid;
		$user = &GetUser($userid);
		$user->settings = $thisuser->settings;
		$user->SaveSettings();
		unset($user);

		$PerPageDisplayOptions = '';
		foreach(array('5', '10', '20', '30', '50') as $numtoshow) {
			$PerPageDisplayOptions .= '<option value="' . $numtoshow . '"';
			if ($numtoshow == $display_settings['NumberToShow']) {
				$PerPageDisplayOptions .= ' SELECTED';
			}
			$PerPageDisplayOptions .= '>' . $numtoshow . '</option>';
		}
		$GLOBALS['PerPageDisplayOptions'] = $PerPageDisplayOptions;

		if (!$numrecords || $numrecords < 0) {
			$GLOBALS['PagingTemplate'] = '';
			$GLOBALS['PagingTemplate_Bottom'] = '';
			return;
		}
		if ($currentpage < 1) $currentpage = 1;
		if ($perpage < 1) $perpage = 10;

		$num_pages = ceil($numrecords / $perpage);
		if ($currentpage > $num_pages) $currentpage = $num_pages;

		$prevpage = ($currentpage > 1) ? ($currentpage - 1) : 1;
		$nextpage = (($currentpage+1) > $num_pages) ? $num_pages : ($currentpage+1);

		$sortdetails = (isset($GLOBALS['SortDetails'])) ? $GLOBALS['SortDetails'] : '';

		$string = '(' . GetLang('Page') . ' ' . $this->FormatNumber($currentpage) . ' ' . GetLang('Of') . ' ' . $this->FormatNumber($num_pages) . ')&nbsp;&nbsp;&nbsp;&nbsp;';

		if ($currentpage > 1) {
			$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&DisplayPage=1" title="' . GetLang('GoToFirst') . '">&laquo;</a>&nbsp;|&nbsp;';

			$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&DisplayPage=' . $prevpage;
			$string .= '">' . GetLang('Back') . '</a>&nbsp;|';

		} else {
			$string .= '&laquo;&nbsp;|&nbsp;';
			$string .= GetLang('Back') . '&nbsp;|';
		}

		if ($num_pages > $this->_PagesToShow) {
			$start_page = $currentpage - (floor($this->_PagesToShow/2));
			if ($start_page < 1) $start_page = 1;

			$end_page = $currentpage + (floor($this->_PagesToShow/2));
			if ($end_page > $num_pages) $end_page = $num_pages;
			if ($end_page < $this->_PagesToShow) $end_page = $this->_PagesToShow;

			$pagestoshow = ($end_page - $start_page);
			if (($pagestoshow < $this->_PagesToShow) && ($num_pages > $this->_PagesToShow)) $start_page = ($end_page - $this->_PagesToShow+1);
		} else {
			$start_page = 1;
			$end_page = $num_pages;
		}

		for ($pageid = $start_page; $pageid <= $end_page; $pageid++) {
			if ($pageid > $num_pages) break;

			$string .= '&nbsp;';
			if ($pageid == $currentpage) {
				$string .= '<b>' . $pageid . '</b>';
			} else {
				$string .= '<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&DisplayPage=' . $pageid;
				$string .= '">' . $pageid . '</a>';
			}
			$string .= '&nbsp;|';
		}

		if ($currentpage == $num_pages) {
			$string .= '&nbsp;' . GetLang('Next') . '&nbsp;|';
			$string .= '&nbsp;&raquo;';
		} else {
			$string .= '&nbsp;<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&DisplayPage=' . $nextpage;
			$string .= '">' . GetLang('Next') . '</a>&nbsp;|';
			$string .= '&nbsp;<a href="index.php?Page=%%PAGE%%' . $sortdetails . '&DisplayPage=' . $num_pages;
			$string .= '" title="' . GetLang('GoToLast') . '">&raquo;</a>';
		}

		$GLOBALS['DisplayPage'] = $string;

		if (is_null($formaction)) {
			$GLOBALS['FormAction'] = 'Action=ProcessPaging';
		} else {
			$GLOBALS['FormAction'] = $formaction;
		}
		$paging = $this->ParseTemplate('Paging', true, false);
		if ($numrecords > $this->_PagingMinimum && $perpage > $this->_PagingMinimum) {
			$paging_bottom = $this->ParseTemplate('Paging_Bottom', true, false);
		} else {
			$paging_bottom = '<br />';
		}
		$GLOBALS['PagingTemplate'] = $paging;
		$GLOBALS['PagingTemplate_Bottom'] = $paging_bottom;
	}


	/**
	* SetupCalendar
	* This sets up the calendar according to what's already been shown. This way, the calendar is persistent across all pages.
	* It sets up the global variables ready for it to be parsed and printed.
	*
	* @see ParseTemplate
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	* @see GetLang
	*
	* @return void
	*/
	function SetupCalendar($formaction=null, $calendarinfo=array()) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!empty($calendarinfo)) {
			$calendar_settings = $calendarinfo;
		} else {
			$calendar_settings = $thisuser->GetSettings('Calendar');
		}

		$userid = $thisuser->userid;
		$user = &GetUser($userid);
		$user->settings = $thisuser->settings;
		$user->SaveSettings();
		unset($user);

		$thisyear = date('Y');

		if (empty($calendar_settings)) {
			$calendar_settings = array(
					'DateType' => 'AllTime',
					'From' => array(
						'Day' => 1,
						'Mth' => 1,
						'Yr' => $thisyear
					),
					'To' => array(
						'Day' => 1,
						'Mth' => 1,
						'Yr' => $thisyear
					)
			);
		}

		$date_options = array('Today', 'Yesterday', 'Last24Hours', 'Last7Days', 'Last30Days', 'ThisMonth', 'LastMonth', 'AllTime', 'Custom');

		$date_format = GetLang('DateFormat');
		$time_format = GetLang('TimeFormat');

		$viewing_results_for = GetLang('ViewingResultsFor');
		$datetoshow = $viewing_results_for . ' ';

		$timenow = time();

		switch($calendar_settings['DateType']) {
			case 'Today':
				$datetoshow .= date($date_format, $timenow);
			break;

			case 'Yesterday':
				$yesterday = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
				$datetoshow .= date($date_format, $yesterday);
			break;

			case 'Last24Hours':
				$tf_hours_ago = $timenow - 86400;
				$datetoshow .= '<br/>&nbsp;&nbsp;' . date(GetLang('TimeFormat'), $tf_hours_ago) . ' - ' . date(GetLang('TimeFormat'), $timenow);
			break;

			case 'Last7Days':
				$seven_daysago = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
				$datetoshow .= date($date_format, $seven_daysago);
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;

			case 'Last30Days':
				$thirty_daysago = mktime(0, 0, 0, date('m'), date('d') - 30, date('Y'));
				$datetoshow .= date($date_format, $thirty_daysago);
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;

			case 'ThisMonth':
				$startofmonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow .= date($date_format, $startofmonth);
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;

			case 'LastMonth':
				$lastmonth = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
				$thismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow .= date($date_format, $lastmonth);
				$datetoshow .= ' - ' . date($date_format, $thismonth);
			break;

			case 'AllTime':
				$datetoshow = '';
			break;

			case 'Custom':
				$start = mktime(0, 0, 0, date($calendar_settings['From']['Mth']), date($calendar_settings['From']['Day']), date($calendar_settings['From']['Yr']));
				$end = mktime(0, 0, 0, date($calendar_settings['To']['Mth']), date($calendar_settings['To']['Day']), date($calendar_settings['To']['Yr']));
				$datetoshow .= date($date_format, $start) . ' - ' . date($date_format, $end);
			break;
		}

		$calendar_options = '';
		$CustomDateDisplay = 'none';
		$ShowDateDisplay = '';

		foreach($date_options as $option) {
			$calendar_options .= '<option value="' . $option . '"';
			if ($calendar_settings['DateType'] == $option) {
				$calendar_options .= ' SELECTED';
			}
			$calendar_options .= '>' . GetLang($option) . '</option>';
		}

		if ($calendar_settings['DateType'] == 'Custom') {
			$CustomDateDisplay = '';
			$ShowDateDisplay = 'none';
		}
		
		if ($calendar_settings['DateType'] == 'AllTime') {
			$ShowDateDisplay = 'none';
		}

		// first we do the "From" stuff.
		$CustomDayFrom = '';
		for ($i = 1; $i <= 31; $i++) {
			$CustomDayFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Day']) $CustomDayFrom .= ' SELECTED';
			$CustomDayFrom .= '>' . $i . '</option>';
		}
		$CustomDayFrom .= '';

		$CustomMthFrom = '';
		for ($i = 1; $i <= 12; $i++) {
			$CustomMthFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Mth']) $CustomMthFrom .= ' SELECTED';
			$CustomMthFrom .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		$CustomMthFrom .= '</select>';

		$CustomYrFrom = '';
		for ($i = ($thisyear - 2); $i <= ($thisyear + 5); $i++) {
			$CustomYrFrom .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['From']['Yr']) $CustomYrFrom .= ' SELECTED';
			$CustomYrFrom .= '>' . $i . '</option>';
		}
		$CustomYrFrom .= '';


		// now we do the "To" stuff.
		$CustomDayTo = '';
		for ($i = 1; $i <= 31; $i++) {
			$CustomDayTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Day']) $CustomDayTo .= ' SELECTED';
			$CustomDayTo .= '>' . $i . '</option>';
		}
		$CustomDayTo .= '';

		$CustomMthTo = '';
		for ($i = 1; $i <= 12; $i++) {
			$CustomMthTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Mth']) $CustomMthTo .= ' SELECTED';
			$CustomMthTo .= '>' . GetLang($this->Months[$i]) . '</option>';
		}
		$CustomMthTo .= '';

		$CustomYrTo = '';
		for ($i = ($thisyear - 2); $i <= ($thisyear + 5); $i++) {
			$CustomYrTo .= '<option value="' . $i . '"';
			if ($i == $calendar_settings['To']['Yr']) $CustomYrTo .= ' SELECTED';
			$CustomYrTo .= '>' . $i . '</option>';
		}
		$CustomYrTo .= '';


		$GLOBALS['CustomDayFrom'] = $CustomDayFrom;
		$GLOBALS['CustomMthFrom'] = $CustomMthFrom;
		$GLOBALS['CustomYrFrom'] = $CustomYrFrom;

		$GLOBALS['CustomDayTo'] = $CustomDayTo;
		$GLOBALS['CustomMthTo'] = $CustomMthTo;
		$GLOBALS['CustomYrTo'] = $CustomYrTo;

		$GLOBALS['ShowDateDisplay'] = $ShowDateDisplay;
		$GLOBALS['CustomDateDisplay'] = $CustomDateDisplay;
		$GLOBALS['CalendarOptions'] = $calendar_options;

		$GLOBALS['DateRange'] = $datetoshow;

		if (is_null($formaction)) {
			$GLOBALS['FormAction'] = 'Action=ProcessDate';
		} else {
			$GLOBALS['FormAction'] = $formaction;
		}
		$GLOBALS['Calendar'] = $this->ParseTemplate('calendar', true, false);
	}
	
	
	/**
	* GetCalendarInfo
	* Gets calendar information from the array passed in, makes it 'human-readable'.
	*
	* @param Calendar Array of calendar settings to process.
	* @param DateOnly Whether to get the date only (ignore whether it's yesterday, today etc).
	*
	* @return string The calendar date.
	*/
	function GetCalendarInfo($calendar=array(), $dateonly=false) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		if (!empty($calendar)) {
			$calendar_settings = $calendar;
		} else {
			$calendar_settings = $thisuser->GetSettings('Calendar');
		}

		$date_format = GetLang('DateFormat');

		$timenow = time();
		$timenow = $this->AdjustTime($timenow);

		switch($calendar_settings['DateType']) {
			case 'Yesterday':
				$yesterday = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
				$datetoshow = date($date_format, $this->AdjustTime($yesterday));
			break;
			
			case 'Last24Hours':
			case 'Today':
				$datetoshow = date($date_format);
			break;

			case 'Last7Days':
				$seven_daysago = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
				$datetoshow = date($date_format, $this->AdjustTime($seven_daysago));
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;

			case 'Last30Days':
				$thirty_daysago = mktime(0, 0, 0, date('m'), date('d') - 30, date('Y'));
				$datetoshow = date($date_format, $this->AdjustTime($thirty_daysago));
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;
			
			case 'ThisMonth':
				$startofmonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow = date($date_format, $this->AdjustTime($startofmonth));
				$datetoshow .= ' - ' . date($date_format, $timenow);
			break;

			case 'Custom':
				$start = mktime(0, 0, 0, date($calendar_settings['From']['Mth']), date($calendar_settings['From']['Day']), date($calendar_settings['From']['Yr']));
				$end = mktime(0, 0, 0, date($calendar_settings['To']['Mth']), date($calendar_settings['To']['Day']), date($calendar_settings['To']['Yr']));
				$datetoshow = date($date_format, $this->AdjustTime($start)) . ' - ' . date($date_format, $this->AdjustTime($end));
			break;

			case 'LastMonth':
				$lastmonth = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
				$thismonth = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$datetoshow = date($date_format, $this->AdjustTime($lastmonth));
				$datetoshow .= ' - ' . date($date_format, $this->AdjustTime($thismonth));
			break;
			
			case 'AllTime':
				$datetoshow = GetLang('AllTime');
			break;

		}

		if ($dateonly) {
			return $datetoshow;
		}
		
		$datetype = $calendar_settings['DateType'];

		if ($datetype == 'Custom' || $datetype == 'AllTime') {
			$readableformat = $datetoshow;
		} else {
			$readableformat = GetLang($datetype) . ' (' . $datetoshow . ')';
		}
		return GetLang('DateRange') . ': ' . $readableformat;
	}

	/**
	* FormatNumber
	* Formats the number passed in according to language variables and returns the value.
	*
	* @param number Number to format
	* @param DecimalPlaces Number of decimal places to format to
	*
	* @see GetLang
	*
	* @return string The number formatted
	*/
	function FormatNumber($number=0, $DecimalPlaces=0) {
		return number_format($number, $DecimalPlaces, GetLang('NumberFormat_Dec'), GetLang('NumberFormat_Thousands'));
	}


	/**
	* CalculateCalendarRestrictions
	* Returns a partial query which can be appended to an existing query to restrict searching to the dates you have searched before (which are retrieved from the session).
	*
	* @param calendarinfo Pass in calendar info if you want to use that instead of the session information.
	* @param enddateonly  Whether to only return the end-date. This is used for campaigns so we can calculate the number of days since the start of a campaign properly. Returns it as an integer (epoch time).
	*
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	* @see Campaigns::Process
	* @see ViewAll_Campaigns::Process
	*
	* @return string The partial query to be appended or the end date (as an int) depending on the second parameter.
	*/
	function CalculateCalendarRestrictions($calendarinfo=array(), $enddateonly=false) {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');

		$ConversionPage = (isset($_GET['Page']) && strtolower($_GET['Page']) == 'view_conversions') ? true : false;
		
		if (!$calendarinfo) {
			$calendar_settings = $thisuser->GetSettings('Calendar');
		} else {
			$calendar_settings = $calendarinfo;
		}

		if (!isset($calendar_settings['DateType'])) $calendar_settings['DateType'] = 'AllTime';

		$calendar_settings['DateType'] = strtolower($calendar_settings['DateType']);

		$rightnow = time();
		$rightnow = $this->AdjustTime($rightnow, true);

		$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$yesterday = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));

#		echo 'yesterday: ' . $yesterday . '; ' . date(GetLang('TimeFormat'), $yesterday) . '; today: ' . $today . '; ' . date(GetLang('TimeFormat'), $today) . '<br/>';

		$today = $this->AdjustTime($today, true);
		$yesterday = $this->AdjustTime($yesterday, true);

#		echo 'yesterday: ' . $yesterday . '; ' . date(GetLang('TimeFormat'), $yesterday) . '; today: ' . $today . '; ' . date(GetLang('TimeFormat'), $today) . '<br/>';

		switch($calendar_settings['DateType']) {
			case 'alltime':
				$query = '';
				$enddate = $rightnow;
			break;

			case 'today':
				$query = '(currtime >= ' . $today . '';
				if (!$ConversionPage) $query .= ' OR convtime >= ' . $today;
				$query .= ')';
				$enddate = $rightnow;
			break;

			case 'yesterday':
				$query = '((currtime >= ' . $yesterday . ' AND currtime < ' . $today . ')';
				if (!$ConversionPage) $query .= ' OR (convtime >= ' . $yesterday . ' AND convtime < ' . $today . ')';
				$query .= ')';

				$enddate = $today;
			break;

			case 'last24hours':
				$enddate = $rightnow - 86400;

				// since "rightnow" is already adjusted, we don't need to adjust it again.

				$query  = '((currtime >= ' . $enddate . ' AND currtime < ' . $rightnow . ')';
				if (!$ConversionPage) $query .= ' OR (convtime >= ' . $enddate . ' AND currtime < ' . $rightnow . ')';
				$query .= ')';
			break;

			case 'last7days':
				$time = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
				$time = $this->AdjustTime($time, true);

				$query = '(currtime >= ' . $time . '';
				if (!$ConversionPage) $query .= ' OR convtime >= ' . $time;
				$query .= ')';

				$enddate = $rightnow;
			break;

			case 'last30days':
				$time = mktime(0, 0, 0, date('m'), date('d')-30, date('Y'));
				$time = $this->AdjustTime($time, true);

				$query = '(currtime >= ' . $time . '';
				if (!$ConversionPage) $query .= ' OR convtime >= ' . $time;
				$query .= ')';
				$enddate = $rightnow;
			break;

			case 'thismonth':
				$time = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$time = $this->AdjustTime($time, true);
				$query = '(currtime >= ' . $time . '';
				if (!$ConversionPage) $query .= ' OR convtime >= ' . $time;
				$query .= ')';
				$enddate = $rightnow;
			break;

			case 'lastmonth':
				$lastm = mktime(0, 0, 0, date('m')-1, 1, date('Y'));
				$thism = mktime(0, 0, 0, date('m'), 1, date('Y'));
				$lastm = $this->AdjustTime($lastm, true);
				$thism = $this->AdjustTime($thism, true);
				$query  = '((currtime >= ' . $lastm . ' AND currtime < ' . $thism . ')';
				if (!$ConversionPage) $query .= ' OR (convtime >= ' . $lastm . ' AND currtime < ' . $thism . ')';
				$query .= ')';
				$enddate = $thism;
			break;

			case 'custom':
				$fromdate = mktime(0, 0, 0, $calendar_settings['From']['Mth'], $calendar_settings['From']['Day'], $calendar_settings['From']['Yr']);

				// for the "to" part, we want the start of the next day.
				// so if you put From 1/1/04 and To 1/1/04 - it actually finds records from midnight 1/1/04 until 23.59.59 1/1/04 (easier to get the next day and make it before then)..
				$todate = mktime(0, 0, 0, $calendar_settings['To']['Mth'], ($calendar_settings['To']['Day']+1), $calendar_settings['To']['Yr']);

				$fromdate = $this->AdjustTime($fromdate, true);
				$todate = $this->AdjustTime($todate, true);

				$query  = '((currtime >= ' . $fromdate . ' AND currtime < ' . $todate . ')';
				if (!$ConversionPage) $query .= ' OR (convtime >= ' . $fromdate . ' AND convtime < ' . $todate . ')';
				$query .= ')';

				$enddate = $todate;
			break;

			default:
				$query = '';
			break;
		}

		if ($enddateonly) return $enddate;
		$this->CalendarRestrictions = $query;
	}


	/**
	* TruncateName
	* Truncates a name to the _MaxNameLength name (minus 4) - so we can append '...' to the end.
	*
	* @param string String to truncate to the max length.
	* @param length Max length to show. Defaults to _MaxNameLength.
	*
	* @see _MaxNameLength
	*
	* @return string The truncated string or the original string if it's less than MaxNameLength chars long
	*/
	function TruncateName($string='', $length=0) {
		if ($length == 0) $length = $this->_MaxNameLength;
		if (strlen($string) > $length) {
			return substr($string, 0, ($length - 4)) . ' ...';
		}
		return $string;
	}


	/**
	* GetPerPage
	* Gets the number to show based on your session. If you don't have a session, it sets a default of this->_DefaultPerPage
	*
	* @see _DefaultPerPage
	*
	* @return int Number to show per page
	*/
	function GetPerPage() {
		$session = &GetSession();
		$thisuser = $session->Get('UserDetails');
		$display_settings = $thisuser->GetSettings('DisplaySettings');
		if (empty($display_settings)) {
			$perpage = $this->_PerPageDefault;
		} else {
			$perpage = $display_settings['NumberToShow'];
		}
		return $perpage;
	}


	/**
	* GetSortDetails
	* From the get string, it works out what you're sorting, which direction and whether there is a secondary sort to perform.
	* Uses _SortDirections, _SortTypes and _Secondary_SortTypes to validate the details - just to make sure ;)
	*
	* @see _SortDirections
	* @see _SortTypes
	* @see _Secondary_SortTypes
	* @see Search::Process
	* @see Engines::Process
	* @see Referrers::Process
	*
	* @return array The array of what to sort by, which direction (SQL), which direction (Up/Down) and the secondary sort if it exists.
	*/
	function GetSortDetails() {
		if (!empty($this->SortDetails)) return $this->SortDetails;

		$sortby = (isset($_GET['SortBy']) && $_GET['SortBy'] != '') ? stripslashes($_GET['SortBy']) : $this->PrimarySort;

		if (!in_array(strtolower($sortby), $this->_SortTypes)) $sortby = 'Visits';

		$sortdirection = (isset($_GET['Sort']) && $_GET['Sort'] != '') ? stripslashes($_GET['Sort']) : 'Down';

		if (!in_array($sortdirection, $this->_SortDirections)) $sortdirection = 'Down';
		$direction = ($sortdirection == 'Down') ? 'DESC' : 'ASC';

		if (isset($this->_Secondary_SortTypes) && isset($this->_Secondary_SortTypes[strtolower($sortby)])) {
			$second_sortby = strtolower($this->_Secondary_SortTypes[strtolower($sortby)]);
		} else {
			$second_sortby = false;
		}

		$second_sortdirection = 'DESC';

		$this->SortDetails = array(strtolower($sortby), $sortdirection, $direction, $second_sortby, $second_sortdirection);
	}


	/**
	* GetSearchUser
	* Gets the userid we're going to be limiting with for our queries. Gets it from the session and returns a partial query to append onto your regular ones
	*
	* @see GetSession
	* @see Session::Get
	* @see SearchUserID
	*
	* @return string Partial query to append onto your other query
	*/
	function GetSearchUser() {
		$session = &GetSession();
		$switched_user = $session->Get('SwitchUser');
		if (!$switched_user) {
			$thisuser = $session->Get('UserDetails');
			$userid = $thisuser->userid;
		} else {
			$userid = $switched_user;
		}
		$this->SearchUserID = "userid='" . addslashes($userid) . "'";
		return;
	}


	/**
	* GetIgnoreDetails
	* Sets ignore details based on what the user doesn't want to see (sites and ip addresses).
	*
	* @see GetSession
	* @see Session::Get
	* @see IgnoreDetails
	*
	* @return string Partial query to append onto your other query
	*/
	function GetIgnoreDetails($ignoretype='ip') {
		$session = &GetSession();
		$ignoredetails = $session->Get('IgnoreDetails');

		$ignoretype = strtolower($ignoretype);

		if (isset($ignoredetails[$ignoretype])) {
			return $ignoredetails[$ignoretype];
		}

		$thisuser = $session->Get('UserDetails');
		$userid = $thisuser->userid;

		$switched_user = $session->Get('SwitchUser');
		if ($switched_user) $userid = $switched_user;

		$user = &GetUser($userid);

		$ignoreqry = false;

		switch($ignoretype) {
			case 'keywords':
				$ignorekeywords = explode(',', trim(str_replace(' ', '', $user->ignorekeywords)));
				if (!empty($ignorekeywords) && $ignorekeywords[0] != "") {
					$ignoreqry = "(";
					foreach($ignorekeywords as $p => $ignorekeyword) {
						$ignorekeyword = str_replace('*', '', $ignorekeyword);
						if (!$ignorekeyword) continue;
						$ignoreqry .= "keywords NOT LIKE '%" . $ignorekeyword . "%' AND ";
					}
					$ignoreqry = substr($ignoreqry, 0, -5);
					$ignoreqry .= ")";
				}
			break;
			case 'referrers':
				$ignoresites = explode(',', trim(str_replace(' ', '', $user->ignoresites)));
				if (!empty($ignoresites) && $ignoresites[0] != "") {
					$ignoresite_queries = array();
					foreach($ignoresites as $p => $site) {
						// remove any http:// or https://
						$site = str_replace(array('http://', 'https://'), '', $site);

						// remove "*" from the site to ignore. why? so we can just do a "like %site%" query.
						$site = str_replace('*', '', $site);
						if (!$site) continue;
						$ignoresite_queries[] = $site;
					}
					$ignoreqry = "(";
					foreach($ignoresite_queries as $p => $ignoresite) {
						$ignoreqry .= "domain NOT LIKE '%" . $ignoresite . "%' AND ";
					}
					$ignoreqry = substr($ignoreqry, 0, -5);
					$ignoreqry .= ")";
				}
			break;
			default:
				$ignoreips = explode(',', trim(str_replace(' ', '', $user->ignoreips)));
				if (!empty($ignoreips) && $ignoreips[0] != "") {
					$ignoreqry = "(";
					foreach($ignoreips as $p => $ignoreip) {
						$ignoreip = str_replace('*', '', $ignoreip);
						if (!$ignoreip) continue;
						$ignoreqry .= "ip NOT LIKE '" . $ignoreip . "%' AND ";
					}
					$ignoreqry = substr($ignoreqry, 0, -5);
					$ignoreqry .= ")";
				}
		}

		$ignoredetails[$ignoretype] = $ignoreqry;
		$session->Set('IgnoreDetails', $ignoredetails);

		return $ignoreqry;
	}

	/**
	* GetBackPage
	* When you go into a page such as 'landingpages', or a 'viewall' page - this 'backpage' denotes where clicking on the '-' takes you to.
	*
	* @see RememberCurrentPage
	* @see GetSession
	* @see Session::Get
	* @see User::GetSettings
	*
	* @return string A url for the back link.
	*/
	function GetBackPage() {
		$session = &GetSession();

		$page = strtolower($_GET['Page']);
		$base_backpage_link = 'index.php?Page=' . $page . '&';

		$backpage_list = $session->Get('BackPageList');
		$base_backpage_list = $session->Get('BaseBackPageList');

		// get the last url we remembered.
		$backpage = array_pop($backpage_list);
		$base_backpage = array_pop($base_backpage_list);

		// if the last url we remembered isn't the same as the current one..
		// add the backpage back to the end of the list.
		if ($base_backpage != $base_backpage_link) {
			array_push($backpage_list, $backpage);
			array_push($base_backpage_list, $base_backpage);
		}

		// if the current url is the same as the one we just fetched, get the next one back (this solves the problem when you go to 'ViewAll', then 'Landing Pages', then back to view all - it will correctly get the first url in the list.
		if ($base_backpage == $base_backpage_link) {
			$backpage = array_pop($backpage_list);
			$base_backpage = array_pop($base_backpage_list);

			// since we took the last url again, we have to put it back on the stack for the next page view (eg changing displaypage etc).
			array_push($backpage_list, $backpage);
			array_push($base_backpage_list, $base_backpage);
		}

		$session->Set('BaseBackPageList', $base_backpage_list);
		$session->Set('BackPageList', $backpage_list);

		return $backpage;
	}


	/**
	* RememberCurrentPage
	* Remembers the current page and associated variables (sorting, paging) for when you click on 'landing pages' or 'viewall' pages.
	*
	* @param ClearList boolean Whether to reset the whole list or not. This is useful when on a base page so it will always reset the list it might've remembered.
	*
	* @see GetBackPage
	* @see GetSortDetails
	* @see GetSession
	* @see Session::Get
	* @see User::SetSettings
	*
	* @return void Stores the back link in the session.
	*/
	function RememberCurrentPage($ClearList=false) {
		$page = strtolower($_GET['Page']);

		$DisplayPage = (isset($_GET['DisplayPage'])) ? $_GET['DisplayPage'] : 1;

		if (empty($this->SortDetails)) {
			$this->GetSortDetails();
		}

		list($sortby, $sortdirection, $direction, $second_sortby, $second_sortdirection) = $this->SortDetails;

		$session = &GetSession();

		$backpage_list = $session->Get('BackPageList');
		if (!is_array($backpage_list) || $ClearList) {
			$backpage_list = array();
		}
		
		$base_backpage_list = $session->Get('BaseBackPageList');
		if (!is_array($base_backpage_list) || $ClearList) {
			$base_backpage_list = array();
		}

		$base_backpage = 'index.php?Page=' . $page . '&';

		$backpage = $base_backpage;
		$backpage .= 'DisplayPage=' . $DisplayPage . '&';
		if ($sortby) $backpage .= 'SortBy=' . $sortby . '&';
		if ($direction) $backpage .= 'Direction=' . $direction;

		if (isset($_GET['Keywords'])) {
			$backpage .= '&Keywords=' . urlencode(urldecode($_GET['Keywords']));
		}

		if (isset($_GET['Engine'])) {
			$backpage .= '&Engine=' . urlencode(urldecode($_GET['Engine']));
		}

		if (isset($_GET['Domain'])) {
			$backpage .= '&Domain=' . urlencode(urldecode($_GET['Domain']));
		}

		if (!in_array($base_backpage, $base_backpage_list)) {
			array_push($base_backpage_list, $base_backpage);
			array_push($backpage_list, $backpage);
		}

		$session->Set('BaseBackPageList', $base_backpage_list);
		$session->Set('BackPageList', $backpage_list);
	}

	/**
	* FetchSearchEngineLink
	* Gets the url for a search engine from the searchengine ini file.
	*
	* @param searchenginename string The search engine name to look for.
	*
	* @return mixed Returns false if the search engine doesn't exist, otherwise returns the url for the search engine.
	*/
	function FetchSearchEngineLink($searchenginename=false) {
		if (empty($this->SearchEngines)) {
			$this->SearchEngines = LoadSearchEngines();
		}
		$searchenginenames = array_keys($this->SearchEngines);
		if (!in_array($searchenginename, $searchenginenames)) {
			return false;
		}
		$url = $this->SearchEngines[$searchenginename]['url'];
		// if the search engine name contains an asterix, it's a catch all - we can't link to a specific one.
		if (strpos($url, '*') !== false) {
			$url = false;
		}
		return $url;
	}

	/**
	* AdjustTime
	* Adjusts the time based on the users timezone and the server timezone.
	*
	* @param int Timestamp to change.
	* @param bool Whether to convert it from server -> user (default) or user -> server.
	*
	* @return int The adjusted timestamp.
	*/
	function AdjustTime($timestamp=0, $backwards=false) {
		if ((int)$timestamp <= 0) $timestamp = 0;

		$origtimestamp = $timestamp;

		$realdirection = '-';
		if ($backwards) $realdirection = '+';

		$user = &GetUser();

		if ($user->usertimezone == TRACKPOINT_SERVERTIMEZONE) {
			return $timestamp;
		}

		$server_offset = str_replace(':', '.', str_replace('GMT', '', TRACKPOINT_SERVERTIMEZONE));
		$user_offset = str_replace(':', '.', str_replace('GMT', '', $user->usertimezone));

		$server_offset_hours = 0;
		$server_offset_mins = 0;
		$server_offset_mins_dec = 0;
		$server_offset_direction = '+';

		$user_offset_hours = 0;
		$user_offset_mins = 0;
		$user_offset_mins_dec = 0;
		$user_offset_direction = '+';

		if (strpos($server_offset, '.') !== false) {
			$server_offset_parts = explode('.', $server_offset);
			$server_offset_hours = $server_offset_parts[0];
			$server_offset_mins = $server_offset_parts[1];
			$server_offset_mins_dec = ($server_offset_mins / 60) * 10;
			$server_offset_direction = substr($server_offset_hours, 0, 1);
			$server_offset_hours = str_replace($server_offset_direction, '', $server_offset_hours);
		}

		if (strpos($user_offset, '.') !== false) {
			$user_offset_parts = explode('.', $user_offset);
			$user_offset_hours = $user_offset_parts[0];
			$user_offset_mins = $user_offset_parts[1];
			$user_offset_mins_dec = ($user_offset_mins / 60) * 10;
			$user_offset_direction = substr($user_offset_hours, 0, 1);
			$user_offset_hours = str_replace($user_offset_direction, '', $user_offset_hours);
		}

		if ($server_offset_hours == 0 && $server_offset_mins == 0) {
			$timestamp = $timestamp - ("{$realdirection}1" * "{$user_offset_direction}1" * ($user_offset_hours * 3600));
			$timestamp = $timestamp - ("{$realdirection}1" * "{$user_offset_direction}1" * ($user_offset_mins * 60));
			return $timestamp;
		}

		if ($user_offset_hours == 0 && $user_offset_mins == 0) {
			$timestamp = $timestamp + ("{$realdirection}1" * "{$server_offset_direction}1" * $server_offset_hours * 3600);
			$timestamp = $timestamp + ("{$realdirection}1" * "{$server_offset_direction}1" * $server_offset_mins * 60);
			return $timestamp;
		}

#		$total_diff = ("{$server_offset_hours}.{$server_offset_mins_dec}") + ("{$realdirection}1" * "{$user_offset_hours}.{$user_offset_mins_dec}");
		$total_diff = ("{$server_offset_direction}1" * "{$server_offset_hours}.{$server_offset_mins_dec}") + ("{$user_offset_direction}1" * "{$realdirection}1" * "{$user_offset_hours}.{$user_offset_mins_dec}");

		if ($total_diff == 0) {
			return $timestamp;
		}

		$diff_parts = explode('.', $total_diff);
		$diff_hours = (isset($diff_parts[0])) ? $diff_parts[0] : 0;
		$diff_mins = (isset($diff_parts[1])) ? $diff_parts[1] : 0;
		$diff_mins = ($diff_mins / 10) * 60;

		$diff_direction = '+';
		if (strpos($diff_hours, '-') !== false) {
			$diff_direction = '-';
		}

		if ($realdirection == '+') {
			$diff_direction = ($diff_direction == '-') ? '+' : '-';
		}

		$diff_hours = str_replace('-', '', $diff_hours);

		$timestamp = $timestamp + ("{$realdirection}1" * "{$diff_direction}1" * ($diff_hours * 3600));
		$timestamp = $timestamp + ("{$realdirection}1" * "{$diff_direction}1" * ($diff_mins * 60));

		return $timestamp;
	}

	/**
	* PrintDate
	* Prints the date according to the language variables and returns the string value.
	* Uses AdjustTime to convert from server time to local user time before displaying.
	*
	* @param int Timestamp to print.
	*
	* @see GetLang
	* @see AdjustTime
	*
	* @return string This will return the date formatted, adjusted for the users timezone.
	*/
	function PrintDate($timestamp=0) {
		$timestamp = $this->AdjustTime($timestamp);
		return date(GetLang('DateFormat'), $timestamp);
	}

	/**
	* PrintTime
	* Prints the time according to the language variables and returns the string value.
	* Uses AdjustTime to convert from server time to local user time before displaying.
	*
	* @param int Timestamp to print.
	*
	* @see GetLang
	* @see AdjustTime
	*
	* @return string This will return the time formatted, adjusted for the users timezone.
	*/
	function PrintTime($timestamp=0) {
		$timestamp = $this->AdjustTime($timestamp);
		return date(GetLang('TimeFormat'), $timestamp);
	}

	/**
	* TimeZoneList
	* Creates a dropdown list of timezones.
	* These are loaded from the language file (TimeZones) and it creates the list from the options provided.
	*
	* @param string The currently selected timezone (so it can be pre-selected in the list). This corresponds to the GMT offset (eg +10:00).
	*
	* @see LoadLanguageFile
	* @see GetLang
	*
	* @return string Returns an option list of timezones with the timezone pre-selected if possible.
	*/
	function TimeZoneList($selected_timezone='') {
		$list = '';
		foreach($GLOBALS['TrackPointTimeZones'] as $pos => $offset) {
			$selected = ($offset == $selected_timezone) ? ' SELECTED' : '';
			$list .= '<option value="' . $offset . '"' . $selected . '>' . GetLang($offset) . '</option>';
		}
		return $list;
	}

}

?>
