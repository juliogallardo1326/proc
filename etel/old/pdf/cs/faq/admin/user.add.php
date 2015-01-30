<?php
/**
* $Id: user.add.php,v 1.1.2.5.2.1 2006/02/09 13:48:35 thorstenr Exp $
*
* Displays a form to add an user
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
if ($permission["adduser"]) {
?>
	<h2><?php print $PMF_LANG["ad_menu_user_administration"]; ?></h2>
	<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="POST">
    <fieldset>
    <legend><?php print $PMF_LANG["ad_adus_adduser"]; ?></legend>
	<input type="hidden" name="aktion" value="addsave" />

	<label class="left" for="name"><?php print $PMF_LANG["ad_adus_name"]; ?></label>
    <input type="text" name="name" style="width: 200px;" /><br />

	<label class="left" for="realname"><?php print $PMF_LANG["ad_user_realname"]; ?></label>
    <input type="text" style="width: 200px;" name="realname" /><br />

	<label class="left" for="email"><?php print $PMF_LANG["ad_entry_email"]; ?></label>
    <input type="text" style="width: 200px;" name="email" /><br />

	<label class="left" for="npass"><?php print $PMF_LANG["ad_adus_password"]; ?></label>
    <input type="password" name="npass" style="width: 200px;" /><br />

	<label class="left" for="bpass"><?php print $PMF_LANG["ad_passwd_con"]; ?></label>
    <input type="password" name="bpass" size="30" /><br />

	<input class="submit" type=submit value="<?php print $PMF_LANG["ad_adus_add"]; ?>" />
    </fieldset>
	</form>
<?php
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\"> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=user\">".$PMF_LANG["ad_menu_user_administration"]."</a></p>\n";
} else {
	print $PMF_LANG["err_NotAuth"];
}