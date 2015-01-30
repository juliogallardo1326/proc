<?php
/******************************************************************************
 * File:				cookie.check.php
 * Description:			check cookie
 * Authors:				Thorsten Rinne <thorsten@phpmyfaq.de>
 * Date:				2003-02-23
 * Last change:			2004-06-15
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
print "<h2>".$PMF_LANG["ad_cookie"]."</h2>";
?>
<div align="center">
<?php
if (isset($_COOKIE["cuser"]) && $_COOKIE["cuser"] == $user) {
	print "<p>".$PMF_LANG["ad_cookie_already"]."</p>";
?>
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <input type="hidden" name="aktion" value="setcookie" />
    <input type="hidden" name="cuser" value="<?php print $user; ?>" />
    <input type="hidden" name="cpass" value="<?php print $pass; ?>" />
    <input type="submit" name="submit" class="submit" value="<?php print $PMF_LANG["ad_cookie_again"]; ?>" />
    </form>
    
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <input type="hidden" name="aktion" value="delcookie" />
    <input type="hidden" name="cuser" value="<?php print $user; ?>" />
    <input type="hidden" name="cpass" value="<?php print $pass; ?>" />
    <input type="submit" name="submit" class="submit" value="<?php print $PMF_LANG["ad_cookie_delete"]; ?>" />
    </form>
<?php
	}
else {
	print "<p>".$PMF_LANG["ad_cookie_no"]."</p>";
?>
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <input type="hidden" name="aktion" value="setcookie" />
    <input type="hidden" name="cuser" value="<?php print $user; ?>" />
    <input type="hidden" name="cpass" value="<?php print $pass; ?>" />
    <input type="submit" name="submit" class="submit" value="<?php print $PMF_LANG["ad_cookie_set"]; ?>" />
    </form>
<?php
	}
?>
</div>