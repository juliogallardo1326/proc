<?php
/**
* Track conversions.
* This file does all of the conversion tracking. It works out where a conversion came from originally so it can mark it back to the original tracking point.
*
* @version     $Id: tp.php,v 1.20 2005/11/03 02:15:18 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @filesource
*/

/**
* Include our base file.
*/
require('functions/init.php');

$trackpoint_sessionid = session_id();

$db = &GetDatabase();
if (!$db) {
	error_log("Unable to get database, date: " . date('d M Y H:i:s') . "\n", 3, TEMP_DIRECTORY . '/tracking_errors.log');
	exit();
}

/**
* Include the utility file, this lets us quickly grab keywords etc so we can update properly.
* This also handles processing of cookies etc.
*
* @see TPUtil
*/
require(TRACKPOINT_API_DIRECTORY . '/tputil.php');
$TPUtil = &New TPUtil();
$ip = $TPUtil->GetRealIp();

/**
* Work everything out before we do anything else.
*/
$name = (isset($_GET['name'])) ? urldecode(stripslashes($_GET['name'])) : false;
$amt = (isset($_GET['amount'])) ? (float)$_GET['amount'] : 0;
$userid = (isset($_GET['u'])) ? urldecode($_GET['u']) : false;

// if there's no user id - assume it's for the main user.
if (!$userid) {
	$userid = 1;
}

$cookielist = trim(str_replace("\n", "", array_contents($_COOKIE)));
LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Query String: ' . $_SERVER['QUERY_STRING'] . '; cookies: ' . $cookielist, $userid, 'conversion');

$primaryid = false;

$decoded = false;

$cookietype = $cookiefrom = $cookiedetails = false;
$cookieid = false;

$origtime = false;

$cookieinfo = $TPUtil->GetCookieInfo(false, $trackpoint_sessionid);

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookieinfo (from db): ' . array_contents($cookieinfo), $userid, 'conversion');

if (isset($_COOKIE[TRACKPOINT_COOKIE_NAME])) {
	$cookieid = $_COOKIE[TRACKPOINT_COOKIE_NAME];
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie is set in browser (' . $cookieid . ')', $userid, 'conversion');
} else {
	if (!$cookieinfo) {
		LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie is NOT set, making it up', $userid, 'conversion', 'critical');
		$cookieinfo = array('cookieid' => 'unknowncookie', 'cookietype' => 'referrer', 'cookiefrom' => '', 'cookiedetails' => '');
	} else {
		$cookieid = $cookieinfo['cookieid'];
		LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie is not set in browser but found from session: ' . $cookieid, $userid, 'conversion');
	}
}

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie we are going to use: ' . $cookieid, $userid, 'conversion');

$origuser = 1;

$decoded = base64_decode(str_replace('____', '-', $cookieid));
if (!$decoded) {
	$cookieid = $cookieinfo['cookieid'];
	$cookietype = $cookieinfo['cookietype'];
	$cookiefrom = $cookieinfo['cookiefrom'];
	$cookiedetails = $cookieinfo['cookiedetails'];
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie not decoded. cookieid: ' . $cookieid . '; type: ' . $cookietype . '; from: ' . $cookieffrom . '; details: ' . $cookiedetails, $userid, 'conversion', 'warn');
} else {
	$cookieinfo = explode('-', $decoded);
	$cookietrack = unserialize(base64_decode($cookieinfo[1]));

	$cookietype = $cookietrack['type'];

	$primaryid = (int)$cookietrack['id'];

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie decoded. type: ' . $cookietype . '; id: ' . $primaryid, $userid, 'conversion');

	switch(strtolower($cookietype)) {
		case 'ppc':
			$query = "SELECT searchenginename AS cookiefrom, ppcname AS details, currtime AS origtime, userid FROM " . TRACKPOINT_TABLEPREFIX . "payperclicks WHERE ppcid='" . (int)$primaryid . "'";
		break;
		case 'campaign':
			$query = "SELECT campaignsite AS cookiefrom, campaignname AS details, currtime AS origtime, userid FROM " . TRACKPOINT_TABLEPREFIX . "campaigns WHERE campaignid='" . (int)$primaryid . "'";
		break;
		case 'referrer':
			$query = "SELECT domain AS cookiefrom, url AS details, currtime AS origtime, userid FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE referrerid='" . (int)$primaryid . "'";
		break;
		case 'search':
			$query = "SELECT searchenginename AS cookiefrom, keywords AS details, currtime AS origtime, userid FROM " . TRACKPOINT_TABLEPREFIX . "search WHERE searchid='" . (int)$primaryid . "'";
		break;
	}

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'query: ' . $query, $userid, 'conversion');

	$result = $db->Query($query);
	if ($result) {
		$row = $db->Fetch($result);
		if ($row) {
			$cookiefrom = $row['cookiefrom'];
			$cookiedetails = $row['details'];
			$origtime = $row['origtime'];
			$origuser = $row['userid'];
		}
	}
}

/**
* If we're not tracking a conversion for the same user - don't do anything. We don't care!
* The current user (passed in to this page) *must* match the originally tracked user.
* This is needed if you're tracking a single website by multiple users (eg one tracks sales, one tracks newsletter signups).
*/
if ($origuser != $userid) {
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'user of origin (' . $origuser . ') does not match conversion user (' . $userid . ')', $userid, 'conversion', 'critical');
	exit();
}

$time = time();
$time_before = $time - (TRACKPOINT_DUPLICATE_ORDER_TIME * 60); // 2 minutes = 120 seconds.

/**
* check for an existing conversion with this cookie and session.
* They've probably hit F5 to refresh.
* If we find one, don't keep going. It's already tracked and found.
* If it has been in the last 5 minutes.
*/
$qry = "SELECT COUNT(conversionid) AS count FROM " . TRACKPOINT_TABLEPREFIX . "conversions WHERE cookieid='" . addslashes($cookieid) . "' AND sessionid='" . addslashes($trackpoint_sessionid) . "' AND currtime > " . $time_before . " AND currtime <= " . $time . " AND userid='" . $userid . "'";

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'duplicate check query: ' . $query, $userid, 'conversion');

$result = $db->Query($qry);
$count = $db->FetchOne($result, 'count');
if ($count > 0) {
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'duplicate found', $userid, 'conversion', 'warn');
	exit();
}

// save the conversion for the "conversions" page.
$conversionid = $db->NextId(TRACKPOINT_TABLEPREFIX . 'conversions_sequence');
$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "conversions(conversionid, type, name, amount, cookieid, sessionid, currtime, ip, origintype, originfrom, origindetails, userid) VALUES ('" . addslashes($conversionid) . "', 'sale', '" . addslashes($name) . "', '" . addslashes($amt) . "', '" . addslashes($cookieid) . "', '" . addslashes($trackpoint_sessionid) . "', '" . addslashes($time) . "', '" . addslashes($ip) . "', '" . addslashes($cookietype) . "', '" . addslashes($cookiefrom) . "', '" . addslashes($cookiedetails) . "', '" . addslashes($userid) . "')";
$result = $db->Query($qry);

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'conversion query: ' . $qry, $userid, 'conversion');

switch(strtolower($cookietype)) {
	case 'ppc':
		$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "payperclicks SET hasconversion=hasconversion+1, convtime='" . $time . "', amount=amount+" . (float)$amt . " WHERE ";
		if ($primaryid) {
			$qry .= " ppcid='" . $primaryid . "'";
		} else {
			$qry .= " searchenginename='" . addslashes($cookiefrom) . "' AND ppcname='" . addslashes($cookiedetails) . "' AND cookieid='" . addslashes($cookieid) . "'";
		}
	break;

	case 'campaign':
		$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "campaigns SET hasconversion=hasconversion+1, convtime='" . $time . "', amount=amount+" . (float)$amt . " WHERE ";
		if ($primaryid) {
			$qry .= " campaignid='" . $primaryid . "'";
		} else {
			$qry .= " campaignsite='" . addslashes($cookiefrom) . "' AND campaignname='" . addslashes($cookiedetails) . "' AND cookieid='" . addslashes($cookieid) . "'";
		}
	break;

	case 'search':
		$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "search SET hasconversion=hasconversion+1, convtime='" . $time . "', amount=amount+" . (float)$amt . " WHERE ";
		if($primaryid) {
			$qry .= " searchid='" . $primaryid . "'";
		} else {
			$qry .= " searchenginename='" . addslashes($cookiefrom) . "' AND keywords='" . addslashes($cookiedetails) . "' AND cookieid='" . addslashes($cookieid) . "'";
		}
	break;

	default:
	if ($cookieid != 'unknowncookie') {
		$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "referrers SET hasconversion=hasconversion+1, convtime='" . $time . "', amount=amount+" . (float)$amt . " WHERE ";
		if ($primaryid) {
			$qry .= " referrerid='" . $primaryid . "'";
		} else {
			$qry .= " domain='" . addslashes($cookiefrom) . "' AND url='" . addslashes($cookiedetails) . "' AND cookieid='" . addslashes($cookieid) . "'";
		}
	} else {
		$id = $db->NextId(TRACKPOINT_TABLEPREFIX . 'referrers_sequence');
		$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "referrers(referrerid, domain, url, currtime, ip, landingpage, cookieid, userid, hasconversion, amount, convtime) VALUES ('" . $id . "', '', '', '" . $time . "', '" . addslashes($ip) . "', '', 'unknowncookie', '" . $userid . "', 1, '" . $amt . "', '" . $time . "')";
	}
}

/**
* why do we need to restrict the userid as well?
* If we have different conversions set up on the same site as different users (one tracking newsletter signups, the other tracking product purchases), then even though the cookie may match and the record may match, it might not be for the right user.
*/
if ($cookieid != 'unknowncookie') {
	$qry .= " AND userid='" . $userid . "'";
}

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'updating original visit: ' . $qry, $userid, 'conversion');

$db->Query($qry);

if (TRACKPOINT_DELETECOOKIE == 1) {
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'deleting cookie', $userid, 'conversion');
	$TPUtil->DelTPCookie(TRACKPOINT_COOKIE_NAME, $cookieid);
}

?>
