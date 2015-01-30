<?php
/**
* $Id: user.edit.php,v 1.2.2.5.2.3 2006/03/04 18:54:57 thorstenr Exp $
*
* Edits user preferences and permissions
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       2003-02-21
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

if ($permission["edituser"]) {
	adminlog("Useredit, ".$_GET["id"]);
	$row = $db->fetch_object($db->query("SELECT name, pass, realname, email, rights FROM ".SQLPREFIX."faquser WHERE id = ".$_GET["id"]));
?>
    <h2><?php print $PMF_LANG["ad_menu_user_administration"]; ?></h2>
    <form name="userRights" action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <fieldset>
    <legend><?php print $PMF_LANG["ad_user_profou"]; ?> <span style="color: Red; font-style: italic;"><?php print $row->name; ?></span></legend>
	<input type="hidden" name="aktion" value="usersave" />
	<input type="hidden" name="id" value="<?php print $_REQUEST["id"]; ?>" />

	<label class="left"><?php print $PMF_LANG["ad_user_name"]; ?>:</label>
<?php
	if (strtolower($row->name) != "admin") {
		print '<input type="text" name="name" size="30" value="'.$row->name.'" />';
	} else {
		print $row->name;
		print '<input type="hidden" name="name" size="30" value="'.$row->name.'" />';
	}
?><br />

	<label class="left"><?php print $PMF_LANG["ad_user_realname"]; ?></label>
    <input type="text" style="width: 200px;" name="realname" value="<?php print $row->realname; ?>" /><br />

	<label class="left"><?php print $PMF_LANG["ad_entry_email"]; ?></label>
    <input type="text" style="width: 200px;" name="email" value="<?php print $row->email; ?>" /><br />

<?php
	if ($user != $row->name) {
?>
	<label class="left"><?php print $PMF_LANG["ad_user_password"]; ?>:</label>
    <input name="npass" style="width: 200px;" type="password" /><br />

	<label class="left"><?php print $PMF_LANG["ad_user_confirm"]; ?>:</label>
    <input name="nupass" style="width: 200px;" type="password" /><br />

<?php
	} else {
?>
	<label class="left"><?php print $PMF_LANG["ad_user_password"]; ?>:</label>
    <?php print $PMF_LANG["ad_user_chpw"]; ?><br />

<?php
    }
    if (strtolower($row->name) != "admin") {
?>
	</fieldset>
    
    <fieldset>
    <legend><?php print $PMF_LANG["ad_user_rights"]; ?> <span style="color: Red; font-style: italic;"><?php print $row->name; ?></span></legend>
    <div class="userrights">
<?php
        for ($i = 1; $i <= strlen($row->rights); $i++) {
            if (substr($row->rights, ($i - 1), 1) == 1) {
                $suf = ' checked="checked"';
            } else {
			    unset($suf);
			}
?>
        <input type="checkbox" name="right[<?php print $i-1; ?>]" value="1"<?php if (isset($suf)) { print $suf; } ?> /> <?php print $PMF_LANG["rightsLanguage"][$i-1]; ?><br />
<?php
        }
?>
        <p><input type="checkbox" onClick="checkAll(this);" /> <?php print $PMF_LANG["ad_user_checkall"]; ?></p>
    </div>
<?php
	}
?>
	<div align="center"><input class="submit" type="submit" value="<?php print $PMF_LANG["ad_gen_save"]; ?>" /> <input type="reset" value="<?php print $PMF_LANG["ad_gen_reset"]; ?>" class="submit" /></div>

    </fieldset>
	</form>
<?php
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\" /> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=user\">".$PMF_LANG["ad_menu_user_administration"]."</a></p>\n";
} else {
	print $PMF_LANG["err_NotAuth"];
}