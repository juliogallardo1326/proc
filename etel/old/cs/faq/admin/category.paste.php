<?php
/**
* $Id: category.paste.php,v 1.1.2.4 2006/01/02 12:47:10 thorstenr Exp $
*
* Pastes a category
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-04-28
* @copyright    (c) 2003-2006 phpMyFAQ Team
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

if ($permission["editcateg"]) {
    print "<h2>".$PMF_LANG["ad_categ_paste"]."</h2>\n";
    $category = $_GET["cat"];
    $rootcat = $_GET["after"];
    
    $cat = new category;
    if ($category != $rootcat) {
        $result = $db->query("UPDATE ".SQLPREFIX."faqcategories SET parent_id = ".$rootcat." WHERE id = ".$category);
        print "<p>".$PMF_LANG["ad_categ_updated"]."</p>\n";
    } else {
        print "<p>".$PMF_LANG["ad_categ_paste_error"]."<br />".$db->error()."</p>\n";
    }
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\"> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=category\">".$PMF_LANG["ad_menu_categ_edit"]."</a></p>\n";
} else {
	print $PMF_LANG["err_NotAuth"];
}
?>