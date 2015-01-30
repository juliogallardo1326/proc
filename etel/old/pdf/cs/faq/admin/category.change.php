<?php
/**
* $Id: category.change.php,v 1.1.2.7 2006/01/02 12:47:10 thorstenr Exp $
*
* Changes two category IDs
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2004-04-29
* @copyright    (c) 2004-2006 phpMyFAQ Team
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

print "<h2>".$PMF_LANG["ad_categ_paste"]."</h2>\n";

if ($permission["editcateg"]) {
    
    $categoryToMove = $_POST["cat"];
    $categoryToRemove = $_POST["change"];
    $categoryTemp = rand(200000, 400000);
    
    $tables = array(array('faqcategories' => 'id'),
                    array('faqcategories' => 'parent_id'),
                    array('faqcategoryrelations' => 'category_id'),
                    array('faqfragen' => 'ask_rubrik'));
    
    $result = true;
    foreach ($tables as $pair) {
        foreach ($pair as $_table => $_field) {
            $result = $result && $db->query(sprintf("UPDATE %s SET %s = %d WHERE %s = %d", SQLPREFIX.$_table,
                             $_field, $categoryTemp,
                             $_field, $categoryToRemove));
            $result = $result && $db->query(sprintf("UPDATE %s SET %s = %d WHERE %s = %d", SQLPREFIX.$_table,
                             $_field, $categoryToRemove,
                             $_field, $categoryToMove));
            $result = $result && $db->query(sprintf("UPDATE %s SET %s = %d WHERE %s = %d", SQLPREFIX.$_table,
                             $_field, $categoryToMove,
                             $_field, $categoryTemp));
        }
    }
    if ($result) {
        print "<p>".$PMF_LANG["ad_categ_updated"]."</p>\n";
    } else {
        print "<p>".$PMF_LANG["ad_categ_paste_error"]."</p>\n";
    }
    print "<p><img src=\"images/arrow.gif\" width=\"11\" height=\"11\" alt=\"\" border=\"0\"> <a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=category\">".$PMF_LANG["ad_menu_categ_edit"]."</a></p>\n";
} else {
    print $PMF_LANG["err_NotAuth"];
}