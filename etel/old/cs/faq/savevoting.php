<?php
/**
* $Id: savevoting.php,v 1.8.2.9 2006/01/02 12:47:09 thorstenr Exp $
*
* Saves a user voting
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2002-09-16
* @copyright    (c) 2001-2006 phpMyFAQ Team
* 
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

$record = (isset($_POST["artikel"])) ? intval($_POST["artikel"]) : '';
$vote = (isset($_POST["vote"])) ? intval($_POST["vote"]) : 0;
$userip = (isset($_POST["userip"])) ? strip_tags($_POST["userip"]) : '';

if (isset($vote) && $vote != "" && votingCheck($record, $userip) && intval($_POST["vote"]) > 0 && intval($_POST["vote"]) < 6) {
    
	$noUser = "0";
	$datum = date("YmdHis");
	Tracking("save_voting", $record);
    
	if ($result = $db->query("SELECT usr FROM ".SQLPREFIX."faqvoting WHERE artikel = ".$record)) {
		while ($row = $db->fetch_object($result)) {
			$noUser = $row->usr;
		}
	}
    
	if ($noUser == "0" || $noUser == "") {
		$db->query("INSERT INTO ".SQLPREFIX."faqvoting (id, artikel, vote, usr, datum, ip) VALUES (".$db->nextID(SQLPREFIX."faqvoting", "id").", ".$record.", ".$vote.", '1', ".time().", '".$userip."');");
	}  else {
		$db->query("UPDATE ".SQLPREFIX."faqvoting SET vote = vote + ".$vote.", usr = usr + 1, datum = ".time().", ip = '".$userip."' where artikel = ".$record);
	}
    
	$tpl->processTemplate ("writeContent", array(
				"msgVoteThanks" => $PMF_LANG["msgVoteThanks"]
				));
    
} elseif (isset($_POST["vote"])  && !votingCheck($record, $userip)) {
    
    Tracking("error_save_voting", $record);
	$tpl->processTemplate ("writeContent", array(
				"msgVoteThanks" => $PMF_LANG["err_VoteTooMuch"]
				));
    
} else {
    
	Tracking("error_save_voting", $record);
	$tpl->processTemplate ("writeContent", array(
				"msgVoteThanks" => $PMF_LANG["err_noVote"]
				));
    
}

$tpl->includeTemplate("writeContent", "index");
?>
