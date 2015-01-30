<?php
/**
* Track everything.
* This file does all of the tracking. It works out whether it's a campaign, ppc, search engine referrer or generic referrer and takes the right action.
*
* @version     $Id: t.php,v 1.29 2005/11/09 23:44:02 chris Exp $
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
* In case we need to update the cookie's expiry date, lets do it.
*/
$CookieExpiry = (time() + ( TRACKPOINT_COOKIE_TIME * 60 * 60 ));

/**
* This is an extra check in case we need to delete the cooke then re-create it.
*/
$neednewcookie = false;

/**
* Work out where we are.
*/
$referrer = (isset($_GET['r'])) ? urldecode($_GET['r']) : false;
$landingpage = (isset($_GET['l'])) ? str_replace('??', '?', urldecode($_GET['l'])) : false;
$userid = (isset($_GET['u'])) ? urldecode($_GET['u']) : 1;

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Query String: ' . $_SERVER['QUERY_STRING'] . ' (referrer: ' . $referrer . '; landing page: ' . $landingpage . ')', $userid);

if (!$landingpage) {
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'No landing page - exiting', $userid, 'referrer', 'critical');
	exit();
}

/**
* If we already have a cookie, we need to check a couple of things.
* First, we update the cookie to have our (possibly new) session id.
* Then we check if we need to remove the cookie and re-set it (in case they have finished a conversion, then continued browsing the site).
* If we do need to re-set the cookie, we destroy the old session and re-start it. We may or may not get a new session id depending on the php version, that doesn't really matter.
* If that's ok (we don't need to re-set the cookie), we don't need to do anything else.
*
* @see TPUtil::UpdateSessionCookie
* @see TPUtil::RemoveCookie
* @see MySessionStart
*/
if((isset($_COOKIE[TRACKPOINT_COOKIE_NAME]))) {

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Cookie is set (' . $_COOKIE[TRACKPOINT_COOKIE_NAME] . ')', $userid);

	$TPUtil->UpdateSessionCookie($trackpoint_sessionid, $_COOKIE[TRACKPOINT_COOKIE_NAME]);

	if ($TPUtil->RemoveCookie($_COOKIE[TRACKPOINT_COOKIE_NAME], $trackpoint_sessionid)) {
		LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Cookie is set for removal', $userid);

		session_destroy();
		MySessionStart();
		$trackpoint_sessionid = session_id();
		$neednewcookie = true;
	}

	if (!$neednewcookie) {
		exit();
	}
}

/**
* Break up the url. We use special placeholders in the get string to mark whether it's a campaign, ppc or "other".
*/
$original_url = parse_url($landingpage);
if (isset($original_url['query'])) {
	parse_str($original_url['query'], $urlparts);
} else {
	$urlparts = array();
}

/**
* If it's a campaign, either 'cp' (non-encrypted) or 'cpe' is set. 'tpcenc' is old but keeping it for b/c.
* Then we break it up so we can keep it in the cookie properly.
*/
if (isset($urlparts['cp']) || isset($urlparts['cpe']) || isset($urlparts['tpcenc'])) {
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Found campaign', $userid);

	/**
	* Track the campaign in the database.
	* This parses and tells us what campaign, site we are coming from.
	* It also returns the campaignid so we can put it in the cookie.
	*
	* @see TPUtil::TrackCampaign
	*/
	list($campaignid, $site, $campaign) = $TPUtil->TrackCampaign($landingpage, $ip);

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Campaign details: id' . $campaignid . '; site: ' . $site . '; campaign name: ' . $campaign, $userid);

	/**
	* Encode the cookie with the new details.
	*/
	$cookieid = EncodeCookie('campaign', $campaignid);

	/**
	* Update the campaign with the encoded cookie.
	*
	* @see TPUtil::UpdateCampaign
	*/
	$TPUtil->UpdateCampaign($campaignid, $cookieid);

	$cookieinfo = array();
	$cookieinfo['type'] = 'campaign';
	$cookieinfo['from'] = $site;
	$cookieinfo['details'] = $campaign;

	/**
	* set a new cookie - this lets us track just this campaign through the site and leaves the original cookie alone.
	*
	* @see TPUtil::SetTPCookie
	*/
	$TPUtil->SetTPCookie(TRACKPOINT_COOKIE_NAME, $cookieid, $CookieExpiry);

	/**
	* Finally, set the cookie info in the database and in the browser.
	*
	* @see TPUtil::SetCookieInfo
	*/
	$TPUtil->SetCookieInfo($trackpoint_sessionid, $cookieid, $cookieinfo);
	exit();
}

/**
* If it's a payperclick, either 'ppc' (non-encrypted) or 'ppce' is set. 'ppcenc' is old but keeping it for b/c.
* Then we break it up so we can keep it in the cookie properly.
*/
if (isset($urlparts['ppc']) || isset($urlparts['ppce']) || isset($urlparts['ppcenc'])) {

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'Found ppc', $userid);

	/**
	* Track the PPC in the database.
	* This parses and tells us what search engine, ppc name we are coming from.
	* It also returns the ppcid so we can put it in the cookie.
	*
	* @see TPUtil::TrackPPC
	*/
	list($ppcid, $engine, $name) = $TPUtil->TrackPPC($landingpage, $ip);

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'PPC details: id' . $ppcid . '; engine: ' . $engine . '; ppc name: ' . $name, $userid);

	/**
	* Encode the cookie with the new details.
	*/
	$cookieid = EncodeCookie('ppc', $ppcid);

	/**
	* Update the ppc with the encoded cookie.
	*
	* @see TPUtil::UpdatePPC
	*/
	$TPUtil->UpdatePPC($ppcid, $cookieid);

	$cookieinfo = array();
	$cookieinfo['type'] = 'ppc';
	$cookieinfo['from'] = $engine;
	$cookieinfo['details'] = $name;

	/**
	* set a new cookie - this lets us track just this ppc through the site and leaves the original cookie alone.
	*
	* @see TPUtil::SetTPCookie
	*/
	$TPUtil->SetTPCookie(TRACKPOINT_COOKIE_NAME, $cookieid, $CookieExpiry);
	
	/**
	* Finally, set the cookie info in the database and in the browser.
	*
	* @see TPUtil::SetCookieInfo
	*/
	$TPUtil->SetCookieInfo($trackpoint_sessionid, $cookieid, $cookieinfo);
	exit();
}

/**
* If we don't have a cookie set, then we need to set it. It doesn't matter whether the person is browsing the site (after the cookie has been deleted) or not, we need a cookie to be set.
*/
if (!isset($_COOKIE[TRACKPOINT_COOKIE_NAME])) {

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie NOT set', $userid);

	$cookie = $TPUtil->GetCookieInfo(false, $trackpoint_sessionid);

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie from session is ' . array_contents($cookie), $userid);

	if (!$cookie || !isset($cookie['cookieid'])) {
		$neednewcookie = true;
	} else {
		$cookieid = $cookie['cookieid'];
	}
}

/**
* Since it's not a campaign or ppc, we check the referrer. We use that to see whether it's coming from a search engine or whether it's a regular referrer.
*/
preg_match('%^(http[s]*://.*?)/%', $referrer, $matches);
$referrer_domain = (isset($matches[1])) ? $matches[1] : $referrer;

$FoundSearchEngine = false;
$SearchEngines = parse_ini_file(TRACKPOINT_INCLUDES_DIRECTORY . '/se.ini', true);

foreach($SearchEngines as $key => $details) {
	
	/**
	* If the search engine url is the same as the referrer domain, we have a match!
	* We can track this back to a search engine and that's all we need to do.
	*/
	if ($referrer_domain == 'http://' . $details['url'] || $referrer_domain == 'https://' . $details['url']) {
		$FoundSearchEngine = true;
	}

	/**
	* If the search engine url doesn't have "www" - see whether the referrer matches if we put it in.
	* If they do match (with the 'www' at the start), we found our search engine.
	*/
	if (!$FoundSearchEngine && strpos($details['url'], 'www') !== false) {
		if ($referrer_domain == 'http://www.' . $details['url'] || $referrer_domain == 'https://www.' . $details['url']) {
			$FoundSearchEngine = true;
		}
	}

	/**
	* If we still haven't found our search engine, maybe we're using a 'regular-expression' url.
	* If the url contains '*', we break it up into sections and then check each part.
	* If any of them match, we found our search engine.
	*/
	if (!$FoundSearchEngine) {
		if (strpos($details['url'], '*') !== false) {
			$parts = explode('*', $details['url']);
			$se_part = ($parts[0] != '') ? $parts[0] : $parts[1];
			preg_match('%' . $se_part . '%', $referrer_domain, $se_match);
			if (!empty($se_match)) {
				$FoundSearchEngine = true;
			}
		}
	}
	
	/**
	* If we still don't have a search engine after all of that, try the next one.
	*/
	if (!$FoundSearchEngine) continue;

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'found search engine ' . $key . ' with url ' . $details['url'], $userid);

	/**
	* Since we have our search engine, we look for keywords now.
	*
	* @see TPUtil::GetKeywords
	*/
	$keywords = $TPUtil->GetKeywords($referrer, $details['var']);
	if ($keywords) {
		LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'found search engine ' . $key . ' found keyword(s): ' . $keywords, $userid);
		$searchenginename = $key;
		break;
	}
	
	/**
	* If we didn't find keywords with the first variable, we'll check the next one (some search engines have multiple keyword placeholders).
	*/
	if (isset($details['var2'])) {
		$keywords = $TPUtil->GetKeywords($referrer, $details['var2']);
		if ($keywords) {
			LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'found search engine ' . $key . ' found secondary keyword(s): ' . $keywords, $userid);
			$searchenginename = $key;
			break;
		}
	}

	/**
	* Since we haven't found search engine keywords, we might be matching a wildcard.
	* Even if that's the case, we'll change this to be a referrer instead so we don't have blanks showing up in the results.
	*/
	$FoundSearchEngine = false;
	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'found search engine ' . $key . ' but didnt find keywords', $userid);
}

$qry = false;

$time = time();
$cookieinfo = array();

/**
* If we don't have a search engine, we look for a referrer.
*/
if (!$FoundSearchEngine) {
	
	/**
	* If the referrering domain is the same domain as the landing page, someone is simply browsing the site.
	* If that's the case, we don't track anything at all.
	*/
	if ($landingpage && $referrer_domain) {
		$pos = strpos($landingpage, $referrer_domain);
	} else {
		$pos = false;
	}

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'need new cookie: ' . $neednewcookie . '; poscheck: ' . $pos . '(type: ' . gettype($pos) . ')', $userid);

	/**
	* If we need a new cookie (because we removed the old one above), we do need to track the referrer. It doesn't matter if it's our own site.
	*/
	if ($neednewcookie || $pos === false) {
		$id = $db->NextId(TRACKPOINT_TABLEPREFIX . 'referrers_sequence');

		/**
		* Encode the cookie with the new details.
		*/
		$cookieid = EncodeCookie('referrer', $id);

		$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "referrers(referrerid, domain, url, currtime, ip, landingpage, cookieid, amount, userid) VALUES ('" . addslashes($id) . "', '" . addslashes($referrer_domain) . "', '" . addslashes($referrer) . "', " . $time . ", '" . addslashes($ip) . "', '" . addslashes($landingpage) . "', '" . addslashes($cookieid) . "', 0, '" . $userid . "')";

		$cookieinfo['type'] = 'referrer';
		$cookieinfo['from'] = $referrer_domain;
		$cookieinfo['details'] = $referrer;
	}
} else {
	$id = $db->NextId(TRACKPOINT_TABLEPREFIX . 'search_sequence');

	/**
	* Encode the cookie with the new details.
	*/
	$cookieid = EncodeCookie('search', $id);

	$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "search(searchid, keywords, searchenginename, currtime, ip, landingpage, cookieid, amount, userid) VALUES ('" . addslashes($id) . "', '" . addslashes(implode(':', $keywords)) . "', '" . addslashes($searchenginename) . "', " . $time . ", '" . addslashes($ip) . "', '" . addslashes($landingpage) . "', '" . addslashes($cookieid) . "', 0, '" . $userid . "')";
	$cookieinfo['type'] = 'search';
	$cookieinfo['from'] = $searchenginename;
	$cookieinfo['details'] = implode(':', $keywords);
}

LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'running query ' . $qry, $userid);
LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'cookie is now ' . $cookieid, $userid);

/**
* We need to set the cookie in the browser and in the database.
*
* @see TPUtil::SetTPCookie
*/
$TPUtil->SetTPCookie(TRACKPOINT_COOKIE_NAME, $cookieid, $CookieExpiry);

if (!empty($cookieinfo)) {
	$TPUtil->SetCookieInfo($trackpoint_sessionid, $cookieid, $cookieinfo);
}

/**
* If we have a query to run, we do it.
*
* @see TPUtil::SetCookieInfo
*/
if ($qry) {
	$result = $db->Query($qry);
}

/**
* EncodeCookie
* Takes a base cookie, adds the extra information so we can track it back to the origin more consistently.
*
* @param string Cookie Type (referrer, search, ppc, campaign)
* @param int The id of the cookie type.
*
* @return string The new encoded string.
*/
function EncodeCookie($cookietype='referrer', $id=0) {
	global $ip, $userid, $trackpoint_sessionid;

	$cookie = array('type' => $cookietype, 'id' => $id);

	$cookieid = md5(uniqid(rand(), true));
	$cookieid .= '-' . base64_encode(serialize($cookie));
	$cookieid = str_replace('-', '____', base64_encode($cookieid));

	LogMessage(__FILE__, __LINE__, $ip, $trackpoint_sessionid, 'encoding cookie. cookie type: ' . $cookietype . '; id: ' . $id . '; encoded cookieid: ' . $cookieid, $userid);

	return $cookieid;
}

?>
