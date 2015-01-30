<?php
/**
* The Util API.
*
* @package API
* @subpackage TPUtil
*/

/**
* Include the base API class.
*/
require_once(dirname(__FILE__) . '/api.php');

/**
* Basic utility class. It sets & deletes cookies, works out the right client ip, a few other things.
*
* @see SetTPCookie
* @see DelTPCookie
* @see RemoveCookie
* @see IsValidSession
* @see GetKeywords
* @see SetCookieInfo
* @see GetCookieInfo
* @see UpdateSessionCookie
* @see GetRealIP
* @see TrackCampaign
* @see UpdateCampaign
* @see TrackPPC
* @see UpdatePPC
*
* @package API
* @subpackage TPUtil
*/
class TPUtil extends API {

	/**
	* Constructor
	* Does nothing.
	*/
	function TPUtil() {
	}

	/**
	* SetTPCookie
	* Sets a header cookie based on the name, value and time passed in.
	* If one of these details are missing, this function returns false.
	*
	* @param string Name of the cookie
	* @param string Value to set the cookie to
	* @param int How long the cookie lasts in seconds
	*
	* @return boolean returns false if one of the variables are missing, otherwise sets the cookie and returns true.
	*/
    function SetTPCookie($Name=false, $Value=false, $Time=false) {
		if (!$Name || !$Value || !$Time) return false;

		$Name = addslashes($Name);
		$Value = urlencode($Value);
		$Time = (int)$Time;
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		return setcookie($Name, $Value, $Time, '/');
    }

	/**
	* DelTPCookie
	* Deletes the users cookie based on the name passed in.
	*
	* @param string Name of the cookie to delete.
	* @param string Cookieid to delete (from the database).
	*
	* @return boolean Whether the deletion of the cookie works.
	*/
	function DelTPCookie($Name=false, $cookieid=false) {
		if (!$Name) return false;

		$db = &GetDatabase();
		if (!$db) return false;

		if ($cookieid) {
			$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "cookies SET remove='1' WHERE cookieid='" . addslashes($cookieid) . "'";
			$result = $db->Query($qry);
		}
		return true;
	}

	/**
	* RemoveCookie
	* This function checks whether we need to remove the cookie from the users browser.
	* If we don't, it returns false.
	* If we do, then it will update the cookie to let us know it has been reset and will then return true.
	*
	* @param string The cookie to check for. This must be passed in.
	* @param string The session id to check for. If it's present, the session table will be cleaned up.
	*
	* @return boolean Returns true if we need to set a new cookie, otherwise returns false.
	*/
	function RemoveCookie($cookieid=false, $sessionid=false) {
		if (!$cookieid) return false;

		$db = &GetDatabase();
		if (!$db) return false;

		$qry = "SELECT 1 AS remove FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE cookieid='" . addslashes($cookieid) . "' AND remove='1'";
		$result = $db->Query($qry);
		$remove = $db->FetchOne($result, 'remove');
		if (!$remove) return false;

		$qry = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE cookieid='" . addslashes($cookieid) . "'";
		$db->Query($qry);
		
		if ($sessionid) {
			$qry = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE sessionid='" . addslashes($sessionid) . "'";
			$db->Query($qry);
		}

		$time = time() - (365 * 24 * 3600);
		$cookiechange = setcookie(TRACKPOINT_COOKIE_NAME, '', $time, '/');
		return true;
	}

	/**
	* IsValidSession
	*
	* Checks whether we have a session (id passed in) in the database.
	* If we do, this means we are tracking a conversion (since tracking is done first in the top of the page)
	* If it returns false, we're doing a regular track - eg referrer or search engine click.
	*
	* @param string Session id to check for in the database.
	*
	* @see GetDatabase
	*
	* @return boolean Return true if we are tracking a session, false if we're not.
	*/
	function IsValidSession($sessionid=false) {
		if (!$sessionid) return false;
		$db = &GetDatabase();
		if (!$db) return false;
		$qry = "SELECT 1 FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE sessionid='" . addslashes($sessionid) . "'";
		$result = $db->Query($qry);
		$row = $db->Fetch($result);
		if (empty($row)) return false;
		return true;
	}

	/**
	* GetKeywords
	* Extracts keywords from the url passed in.
	* Need to supply the URL and an array of words you want to check for in that url.
	*
	* @param string URL to parse
	* @param array Words to look for in the query (eg q, or search)
	*
	* @return mixed Returns false if one of the params are not passed in or if the url passed in doesn't have a query string. Otherwise returns an array of keywords for that URL.
	*/
	function GetKeywords($url=false, $querywords=array()) {
		if (!is_array($querywords)) $querywords = array($querywords);
		if (!$url || empty($querywords)) return false;
		$url = str_replace('?&', '?', $url);
		$url_bits = parse_url($url);
		if (!isset($url_bits['query'])) return false;

		$query = $url_bits['query'];

		if (!strpos($query, '&')) {
			$queryparts = array($query);
		} else {
			$queryparts = explode('&', $query);
		}

		$keywords = array();
		foreach($queryparts as $qrysection) {
			if (empty($qrysection)) continue;
			if (strpos($qrysection, '=') === false) continue;
			list($var, $key) = explode('=', $qrysection);
			if (in_array($var, $querywords)) $keywords[] = trim($key);
		}
		return $keywords;
	}

	/**
	* SetCookieInfo Sets cookie details in the database.
	*
	* @param string The session id to set the cookie with.
	* @param string The cookieid to set. Created as an md5 of a uniqid().
	* @param array Cookie info. Includes the type (search, referrer), where it's from (referring domain, search engine) and details (keywords or full referrer). See t.php and tp.php for use.
	*
	* @return boolean Returns false if there is no cookieid or the info is empty, or if there is no database connection or if the query doesn't work.
	*
	* @see GetDatabase
	*/
	function SetCookieInfo($sessionid = false, $cookieid = false, $info = array()) {
		if (!$sessionid || !$cookieid || empty($info)) return false;
		$db = &GetDatabase();
		if (!$db) return false;

		$qry = "DELETE FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE sessionid='" . addslashes($sessionid) . "' AND cookieid='" . addslashes($cookieid) . "'";
		$updateresult = $db->Query($qry);

		$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "cookies(sessionid, cookieid, cookietype, cookiefrom, cookiedetails) VALUES ('" . addslashes($sessionid) . "', '" . addslashes($cookieid) . "', '" . addslashes($info['type']) . "', '" . addslashes($info['from']) . "', '" . addslashes($info['details']) . "')";
		$result = $db->Query($qry);

		return $result;
	}

	/**
	* GetCookieInfo Retrieves the cookie info from the database based on the cookieid passed in, or the sessionid.
	* We only use the sessionid in case there is no cookie passed in (eg it's on a 3rd party page and we can't get the cookie).
	*
	* @param string The cookieid to retrieve info for.
	* @param string The sessionid to retrieve info for.
	*
	* @return boolean Returns false if there is no cookie or session or no database connection. Otherwise returns the row based on the cookie/session id.
	*/
	function GetCookieInfo($cookieid=false, $sessionid=false) {
		if (!$cookieid && !$sessionid) return false;
		$db = &GetDatabase();
		if (!$db) return false;
		if ($cookieid) {
			$qry = "SELECT * FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE cookieid='" . addslashes($cookieid) . "'";
			$result = $db->Query($qry);
			$row = $db->Fetch($result);
			if ($row) return $row;
		}
		$qry = "SELECT * FROM " . TRACKPOINT_TABLEPREFIX . "cookies WHERE sessionid='" . addslashes($sessionid) . "'";
		$result = $db->Query($qry);
		$row = $db->Fetch($result);
		return $row;
	}

	/**
	* UpdateSessionCookie Updates the session id to the one passed in for the cookie id passed in.
	*
	* We need to do this because each time you restart your browser, it gives you a new session id (but not a new cookie id). We need to make sure this is in sync so when it comes to tracking the conversion, we can match up the session id to the cookie id.
	* Also we update the cookie time to 'now' so we know it's active. That way we can keep this particular table's size in check...
	*
	* @param string The (possibly new) session id to update the cookie to.
	* @param string The cookie to update.
	*
	* @return boolean Returns false if there is no session, no cookie or no database connection. Returns true if the update worked ok.
	*/
	function UpdateSessionCookie($sessionid=false, $cookieid=false) {
		if (!$cookieid || !$sessionid) return false;
		$db = &GetDatabase();
		if (!$db) return false;
		$qry = "UPDATE " . TRACKPOINT_TABLEPREFIX . "cookies SET sessionid='" . addslashes($sessionid) . "', cookietime='" . time() . "' WHERE cookieid='" . addslashes($cookieid) . "'";
		$result = $db->Query($qry);
		return $result;
	}


	/**
	* GetRealIp
	*
	* Gets the IP from the users web browser. It checks if there is a proxy etc in front of the browser.
	*
	* @return string The IP address of the user.
	*/
    function GetRealIp() {
        $ip = false;

        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }

        // proxy
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }

            for($i = 0; $i < count($ips); $i++) {
                if(version_compare(phpversion(), "5.0.0", ">=")) {
                    if(ip2long($ips[$i]) != false) {
                        $ip = $ips[$i];
                        break;
                    } else {
                        if(ip2long($ips[$i]) != -1) {
                            $ip = $ips[$i];
                            break;
                        }
                    }
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

	/**
	* TrackCampaign
	* Using the url passed in, the campaign name will be parsed and decoded. This will add it to the database.
	*
	* @param string The URL to parse and decode.
	* @param string IP address of the browser (so we can store it in the database)
	*
	* @see UpdateCampaign
	*
	* @return mixed Returns false if the database connection isn't present. Otherwise returns an array of campaignid, campaign site and campaign name.
	*/
	function TrackCampaign($url='', $ip='') {
		$original_url = parse_url($url);
		$parts = array();
		parse_str($original_url['query'], $parts);
		if (isset($parts['cpe'])) {
			$url = base64_decode(urldecode($parts['cpe']));
			parse_str($url, $parts);
			$site = $parts['s'];
			$cost = (isset($parts['c'])) ? (float)$parts['c'] : 0;
			$period = (isset($parts['p'])) ? $parts['p'] : 0;
			$cdate = (isset($parts['d'])) ? $parts['d'] : date('dMy');
		} elseif (isset($parts['tpcenc'])) {
			$url = base64_decode(urldecode($parts['tpcenc']));
			parse_str($url, $parts);
			$site = $parts['s'];
			$cost = (isset($parts['c'])) ? (float)$parts['c'] : 0;
			$period = (isset($parts['p'])) ? $parts['p'] : 0;
			$cdate = (isset($parts['d'])) ? $parts['d'] : date('dMy');
		} else {
			foreach($parts as $k => $v) {
				$parts[strtolower($k)] = urldecode($v);
			}
			$site = $parts['site'];
			$cost = (isset($parts['cost'])) ? (float)$parts['cost'] : 0;
			$period = (isset($parts['period'])) ? $parts['period'] : 0;
			$cdate = (isset($parts['date'])) ? $parts['date'] : date('dMy');
		}
		$user = (isset($parts['u'])) ? (int)$parts['u'] : 1; // if there's no userid, assume it's the admin user.
		$campaign = $parts['cp'];

		$matches = array();
		preg_match('%([\d]{2})([\w]{3})([\d]{2})%', $cdate, $matches);
		if (!isset($matches[1])) {
			// invalid date.
			$startdate = time();
		} else {
			$startday  = $matches[1];
			$startmth  = $matches[2];
			$startyr   = $matches[3];

			$startdate = strtotime($startday . " " . $startmth . " " . $startyr);
		}

		$db = &GetDatabase();
		if (!$db) return false;
		$campaignid = $db->NextId(TRACKPOINT_TABLEPREFIX . 'campaigns_sequence');
		$query = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "campaigns(campaignid, campaignsite, campaignname, cost, period, startdate, hasconversion, amount, currtime, userid, ip) VALUES ('" . $campaignid . "', '" . addslashes($site) . "', '" . addslashes($campaign) . "', '" . (float)$cost . "', '" . (int)$period . "', '" . $startdate . "', 0, 0, '" . time() . "', '" . addslashes($user) . "', '" . addslashes($ip) . "')";
		$db->Query($query);

		return array($campaignid, $site, $campaign);
	}

	/**
	* UpdateCampaign
	* Updates the campaign (based on id) to the new cookieid in the database.
	*
	* @param int Campaign to update.
	* @param string New cookie id
	*
	* @see EncodeCookie
	* @see TrackCampaign
	*
	* @return boolean Returns false if the database connection isn't present. Otherwise runs the update and returns true.
	*/
	function UpdateCampaign($campaignid=0, $cookieid='') {
		$db = &GetDatabase();
		if (!$db) return false;
		$query = "UPDATE " . TRACKPOINT_TABLEPREFIX . "campaigns SET cookieid='" . addslashes($cookieid) . "' WHERE campaignid='" . (int)$campaignid . "'";
		$db->Query($query);
		return true;
	}

	/**
	* TrackPPC
	* Using the url passed in, the ppc name will be parsed and decoded. This will add it to the database.
	*
	* @param string The URL to parse and decode.
	* @param string IP address of the browser (so we can store it in the database)
	*
	* @see UpdatePPC
	*
	* @return mixed Returns false if the database connection isn't present. Otherwise returns an array of ppcid, search engine name and ppc name.
	*/
	function TrackPPC($url='', $ip='') {
		$original_url = parse_url($url);
		$parts = array();
		parse_str($original_url['query'], $parts);
		if (isset($parts['ppce'])) {
			$url = base64_decode(urldecode($parts['ppce']));
			parse_str($url, $parts);
			$name = $parts['n'];
			$engine = $parts['e'];
			$cost = (isset($parts['c'])) ? (float)$parts['c'] : 0;
		} elseif (isset($parts['ppcenc'])) {
			$url = base64_decode(urldecode($parts['ppcenc']));
			parse_str($url, $parts);
			$name = $parts['n'];
			$engine = $parts['e'];
			$cost = (isset($parts['c'])) ? (float)$parts['c'] : 0;
		} else {
			foreach($parts as $k => $v) {
				$parts[strtolower($k)] = urldecode($v);
			}
			$name = $parts['name'];
			$engine = $parts['ppc'];
			$cost = (isset($parts['cost'])) ? (float)$parts['cost'] : 0;
		}
		$user = (isset($parts['u'])) ? (int)$parts['u'] : 1; // if there's no userid, assume it's the admin user.

		$db = &GetDatabase();
		if (!$db) return false;
		$ppcid = $db->NextId(TRACKPOINT_TABLEPREFIX . 'payperclicks_sequence');

		$query = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "payperclicks(ppcid, searchenginename, ppcname, cost, hasconversion, amount, currtime, userid, ip) VALUES ('" . $ppcid . "', '" . addslashes($engine) . "', '" . addslashes($name) . "', '" . (float)$cost . "', 0, 0, '" . time() . "', '" . addslashes($user) . "', '" . addslashes($ip) . "')";
		$db->Query($query);

		return array($ppcid, $engine, $name);
	}

	/**
	* UpdatePPC
	* Updates the ppc (based on id) to the new cookieid in the database.
	*
	* @param int PPC to update.
	* @param string New cookie id
	*
	* @see EncodeCookie
	* @see TrackPPC
	*
	* @return boolean Returns false if the database connection isn't present. Otherwise runs the update and returns true.
	*/
	function UpdatePPC($ppcid=0, $cookieid='') {
		$db = &GetDatabase();
		if (!$db) return false;
		$query = "UPDATE " . TRACKPOINT_TABLEPREFIX . "payperclicks SET cookieid='" . addslashes($cookieid) . "' WHERE ppcid='" . (int)$ppcid . "'";
		$db->Query($query);
		return true;
	}

}

?>
