<?php
/**
* $Id: stat.query.php,v 1.3.2.5.2.1 2006/02/06 12:14:53 thorstenr Exp $
*
* build the query to search through the sessions
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-24
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

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}
if ($permission["viewlog"]) {
	$query = "SELECT IP,TIME,SID FROM ".SQLPREFIX."faqsessions WHERE ";
	if ($_REQUEST["sip"]) {
		$query .= "IP LIKE '%".$_REQUEST["sip"]."%' AND ";
		$linkext .= "&amp;sip=".rawurlencode($_REQUEST["sip"]);
		}
	if ($_REQUEST["nach_datum"] || $_REQUEST["nach_zeit"]) {
		$tss = mkts($_REQUEST["nach_datum"], $_REQUEST["nach_zeit"]);
		$query .= "TIME > ".$tss." AND ";
		$linkext .= "&amp;nach_datum=".rawurlencode($_REQUEST["nach_datum"])."&amp;nach_zeit=".rawurlencode($_REQUEST["nach_zeit"]);
		}
	if ($_REQUEST["vor_datum"] || $_REQUEST["vor_zeit"]) {
		$ts = mkts($_REQUEST["vor_datum"], $_REQUEST["vor_zeit"]);
		$query .= "TIME < ".$ts." ";
		$linkext .= "&amp;vor_datum=".rawurlencode($_REQUEST["vor_datum"])."&amp;vor_zeit=".rawurlencode($_REQUEST["vor_zeit"]);
		}
	
	$query .= " ORDER BY TIME DESC ";
	
	$perpage = 25;
	$topic = "Suche nach ".$_REQUEST["sip"]." von ".date("d.m.Y H:i:s", $tss)." bis ".date("d.m.Y H:i:s", $ts);
	if (!isset($_REQUEST["pages"])) {
		$anz = $db->num_rows($db->query($query));
		$pages = ceil($anz / $perpage);
		if($pages < 1) {
			$pages = 1;
			}
		}
	else {
		$pages = $_REQUEST["pages"];
		}
	
	if (!isset($_REQUEST["page"])) {
		$page = 1;
		}
	else {
		$page = $_REQUEST["page"];
		}
	
	$start = ($page - 1) * $perpage;
	
	$PageSpan = PageSpan("<a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=sessionsearch&amp;page=<NUM>&amp;pages=".$pages."\">", 1, $pages, $page);
	
	$result = $db->query($query);
?>
	<h2><?php print $topic ?></h2>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
		<td align="right"><?php print $PageSpan ?></td>
	</tr>
	<tr bgcolor="#778899">
		<td>
		<table width="100%" border="0" cellspacing="1" cellpadding="5">
        <tr bgcolor="#b0c4de">
			<td><strong><?php print $PMF_LANG["ad_sess_time"]; ?></strong></td>
			<td><strong><?php print $PMF_LANG["ad_sess_sid"]; ?></strong></td>
			<td><strong><?php print $PMF_LANG["ad_sess_ip"]; ?></strong></td>
		</tr>
<?php
    $counter = 0;
    $displayedCounter = 0;
    while (($row = $db->fetch_object($result)) && $displayedCounter < $perpage) {
        $counter++;
        if ($counter <= $start){
            continue;
        }
        $displayedCounter;
?>
		<tr bgcolor="#f5f5f5">
			<td><?php print date("H:i:s d.m.Y", $row->time) ?></td>
			<td><a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&aktion=viewsession&id=<?php print $row->sid; ?>"><?php print $row->sid ?></a></td>
			<td><?php print $row->ip ?></td>
		</tr>
<?php
    }
?>
		</table>
		</td>
	</tr>
	<tr>
		<td align="right"><?php print $PageSpan ?></td>
	</tr>
	</table>
<?php
} else {
    print $PMF_LANG["err_NotAuth"];
}