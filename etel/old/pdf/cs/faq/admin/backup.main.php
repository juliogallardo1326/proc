<?php
/******************************************************************************
 * File:				backup.main.php
 * Description:			main page of backup
 * Authors:				Thorsten Rinne <thorsten@phpmyfaq.de>
 * Date:				2003-02-24
 * Last change:			2004-11-06
 * Copyright:           (c) 2001-2006 phpMyFAQ Team
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

if ($permission["backup"]) {
?>
    <h2><?php print $PMF_LANG["ad_csv_backup"]; ?></h2>
    <fieldset>
      <legend><?php print $PMF_LANG["ad_csv_head"]; ?></legend>
      <p><?php print $PMF_LANG["ad_csv_make"]; ?></p>
      <p align="center"><a href="attachment.php?uin=<?php print $uin; ?>&amp;aktion=sicherdaten"><?php print $PMF_LANG["ad_csv_linkdat"]; ?></a> | <a href="attachment.php?uin=<?php print $uin; ?>&amp;aktion=sicherlog"><?php print $PMF_LANG["ad_csv_linklog"]; ?></a></p>
    </fieldset>

    <form method="post" action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=restore" enctype="multipart/form-data">
      <fieldset>
        <legend><?php print $PMF_LANG["ad_csv_head2"]; ?></legend>
        <p><?php print $PMF_LANG["ad_csv_restore"]; ?></p>
        <div align="center">
        <?php print $PMF_LANG["ad_csv_file"]; ?>:
          <input type="file" name="userfile" size="40" />&nbsp;
          <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_csv_ok"]; ?>" />
        </div>
      </fieldset>
    </form>
<?php
} else {
	print $PMF_LANG["err_NotAuth"];
}