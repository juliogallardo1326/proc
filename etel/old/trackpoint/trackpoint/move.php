<?php
/**
* Move Referrers
* If you update the search engine file, run this file to move referrers to search engine statistics.
*
* @version     $Id: move.php,v 1.2 2005/04/29 02:56:40 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @filesource
*/

/**
* Include our base file.
*/
require('functions/init.php');

/**
* Include the utility file, this lets us quickly grab keywords etc so we can update properly.
*/
require('functions/api/tputil.php');
$TPUtil = &New TPUtil();

$Db = &GetDatabase();

$SearchEngines = parse_ini_file(TRACKPOINT_INCLUDES_DIRECTORY . '/se.ini', true);

$qry = "SELECT domain FROM " . TRACKPOINT_TABLEPREFIX . "referrers GROUP BY domain ORDER BY domain";
$result = $Db->Query($qry);
while($row = $Db->Fetch($result)) {
	$FoundSearchEngine = false;
	foreach($SearchEngines as $key => $detail) {
		// if the search engine url is the same as the referrer domain, we have a match!
		if ($row['domain'] == 'http://' . $detail['url'] || $row['domain'] == 'https://' . $detail['url']) {
			$FoundSearchEngine = $key;
			break;
		}
		
		// if the search engine url doesn't have "www" - see whether the referrer matches if we put it in.
		// that way we can check for something like:
		// overture.com/.... and www.overture.com/....
		if (strpos($detail['url'], 'www') !== false) {
			if ($row['domain'] == 'http://www.' . $detail['url'] || $row['domain'] == 'https://www.' . $detail['url']) {
				$FoundSearchEngine = $key;
				break;
			}
		}
		
		// if we still haven't found a search engine, see whether the ini file has an '*' in it for a partial match.
		if (strpos($detail['url'], '*') !== false) {
			$parts = explode('*', $detail['url']);
			$se_part = ($parts[0] != '') ? $parts[0] : $parts[1];
			preg_match('%' . $se_part . '%', $row['domain'], $se_match);
			if (!empty($se_match)) {
				$FoundSearchEngine = $key;
				break;
			}
		}
	}
	
	if (!$FoundSearchEngine) {
		continue;
	}
	
	$referrer_qry = "SELECT * FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE domain='" . addslashes($row['domain']) . "'";
	$referrer_result = $Db->Query($referrer_qry);
	while($referrer_row = $Db->Fetch($referrer_result)) {
		$keywords = $TPUtil->GetKeywords($referrer_row['url'], $detail['var']);
		if (!$keywords) {
			if (isset($details['var2'])) {
				$keywords = $TPUtil->GetKeywords($referrer_row['url'], $detail['var2']);
			}
		}
		if (!$keywords) continue;
		
		error_log('domain: ' . $row['domain'] . '; keywords: ' . implode(':', $keywords) . '; Search Engine: ' . $FoundSearchEngine);
		
		$id = $db->NextId(TRACKPOINT_TABLEPREFIX . 'search_sequence');
		$qry = "INSERT INTO " . TRACKPOINT_TABLEPREFIX . "search(searchid, keywords, searchenginename, currtime, ip, landingpage, cookieid, userid, hasconversion, amount) VALUES ('" . addslashes($id) . "', '" . addslashes(implode(':', $keywords)) . "', '" . addslashes($FoundSearchEngine) . "', " . $referrer_row['currtime'] . ", '" . addslashes($referrer_row['ip']) . "', '" . addslashes($referrer_row['landingpage']) . "', '" . addslashes($referrer_row['cookieid']) . "', '" . $referrer_row['userid'] . "', '" . $referrer_row['hasconversion'] . "', '" . $referrer_row['amount'] . "')";
		
		$Db->Query($qry);
		
		$Db->Query("DELETE FROM " . TRACKPOINT_TABLEPREFIX . "referrers WHERE referrerid='" . $referrer_row['referrerid'] . "'");
	}
}

?>

