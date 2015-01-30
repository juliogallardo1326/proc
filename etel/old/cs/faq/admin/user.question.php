<?php
/**
* $Id: user.question.php,v 1.2.2.6.2.1 2006/05/08 16:18:10 thorstenr Exp $
*
* Ask, if an user should be deleted
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-22
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

if ($permission["deluser"]) {
    $id = (int)$_GET['id'];
	adminlog('Userdel, '.$id);
	$query = sprintf('SELECT name FROM %sfaquser WHERE id = %d', SQLPREFIX, $id);
	$row = $db->fetch_object($db->query($query));
    $user = $row->name;
?>
    <h2><?php print $PMF_LANG["ad_user"]; ?></h2>
	<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <fieldset>
    <legend><?php print $PMF_LANG["ad_user_del_1"]; ?> <em><?php print $user; ?></em> <?php print $PMF_LANG["ad_user_del_2"]; ?></legend>
	<input type="hidden" name="aktion" value="deluser" />
	<input type="hidden" name="id" value="<?php print $id; ?>" />
    <div align="center"><?php print $PMF_LANG["ad_user_del_3"]; ?><br /><input class="submit" type="submit" value="<?php print $PMF_LANG["ad_gen_yes"]; ?>" name="sicher" /> <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_gen_no"]; ?>" name="sicher" /></div>
    </fieldset>
	</form>
<?php
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\"> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=user\">".$PMF_LANG["ad_menu_user_administration"]."</a></p>\n";
} else {
	print $PMF_LANG["err_NotAuth"];
}
