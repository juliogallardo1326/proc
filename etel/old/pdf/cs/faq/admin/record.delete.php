<?php
/**
* $Id: record.delete.php,v 1.4.2.6.2.2 2006/03/19 08:53:55 thorstenr Exp $
*
* Deletes a record
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-23
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

print "<h2>".$PMF_LANG["ad_entry_aor"]."</h2>\n";
if ($permission["delbt"]) {
	if ($_REQUEST["subm"] == $PMF_LANG["ad_gen_yes"]) {
		// "yes" -> delete it
		adminlog("Beitragdel, ".$_REQUEST["id"]);
		if (@is_dir(PMF_ROOT_DIR."/attachments/".$_REQUEST["id"]."/")) {
			$do = dir(PMF_ROOT_DIR."/attachments/".$_REQUEST["id"]."/");
			while ($dat = $do->read()) {
				if ($dat != "." && $dat != "..") {
					unlink(PMF_ROOT_DIR."/attachments/".$_REQUEST["id"]."/".$dat);
				}
			}
			rmdir (PMF_ROOT_DIR."/attachments/".$_REQUEST["id"]."/");
		}
		// save the latest version in revision table for historical reasons
		$db->query(sprintf("INSERT INTO %sfaqdata_revisions SELECT * FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, SQLPREFIX, $_POST["id"], $_POST["language"]));
		// delete the entry
		$db->query(sprintf("DELETE FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, $_POST["id"], $_POST["language"]));
		$db->query(sprintf("DELETE FROM %sfaqvoting WHERE artikel = %d", SQLPREFIX, $_POST["id"]));
		$db->query(sprintf("DELETE FROM %sfaqcomments WHERE id = %d", SQLPREFIX, $_POST["id"]));
		$db->query(sprintf("DELETE FROM %sfaqvisits WHERE id = %d AND lang = '%s'", SQLPREFIX, $_POST["id"], $_POST["language"]));
		$db->query(sprintf("DELETE FROM %sfaqchanges WHERE beitrag = %d AND lang = '%s'", SQLPREFIX, $_POST["id"], $_POST["language"]));
        $db->query(sprintf("DELETE FROM %sfaqcategoryrelations WHERE record_id = %d AND record_lang = '%s'", SQLPREFIX, $_POST["id"], $_POST["language"]));
		print "<p>".$PMF_LANG["ad_entry_delsuc"]."</p>\n";
		}
	if ($_REQUEST["subm"] == $PMF_LANG["ad_gen_no"]) {
		print "<p>".$PMF_LANG["ad_entry_delfail"]."<br />&nbsp;<br /><a href=\"javascript:history.back()\">".$PMF_LANG["ad_entry_back"]."</p></a>\n";
	}
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\"> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=view\">".$PMF_LANG["ad_entry_aor"]."</a></p>\n";
} else {
	print $PMF_LANG["err_NotAuth"];
}
