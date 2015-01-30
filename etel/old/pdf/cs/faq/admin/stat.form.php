<?php
/**
* $Id: stat.form.php,v 1.2.2.3.2.1 2006/03/03 06:12:53 thorstenr Exp $
*
* Form for the session search
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

$dir = opendir(PMF_ROOT_DIR."/data");

while ($dat = readdir($dir)) {
    if ($dat != "." && $dat != "..") {
        $arrDates[] = FileToDate($dat);
    }
}

$statstart = reset($arrDates);
$statend = end($arrDates);
closedir($dir);
?>
	<h2><?php print $PMF_LANG["ad_sess_sfs"]; ?></h2>
	<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
	<input type="hidden" name="aktion" value="sessionsearch" />
    
    <fieldset>
    <legend><?php print $PMF_LANG["ad_stat_sess"]; ?></legend>
    
        <label class="left"><?php print $PMF_LANG["ad_sess_s_ip"]; ?></label>
        <input name="sip" size="30" /><br />
    
        <label class="left"><?php print $PMF_LANG["ad_sess_s_date"]; ?></label><br />        
    
        <label class="left"><?php print $PMF_LANG["ad_sess_s_after"]; ?></label>
        <input name="nach_datum" size="14" value="<?php print date("d.m.Y", $statstart); ?>" />&nbsp;<input name="nach_zeit" size="14" value="<?php print date("H:i:s", $statstart); ?>" /><br />
    
        <label class="left"><?php print $PMF_LANG["ad_sess_s_before"]; ?></label>
        <input name="vor_datum" size="14" value="<?php print date("d.m.Y", $statend); ?>" />&nbsp;<input name="vor_zeit" size="14" value="<?php print date("H:i:s", $statend); ?>" /><br />

        <div align="center"><input class="submit" type="submit" value="<?php print $PMF_LANG["ad_sess_s_search"]; ?>" />&nbsp;<input class="submit" type="reset" value="<?php print $PMF_LANG["ad_gen_reset"]; ?>" /></div>

    </fieldset>
	</form>