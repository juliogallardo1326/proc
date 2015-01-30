<?php
/**
* $Id: config.edit.php,v 1.3.2.3.2.5 2006/04/25 11:54:15 matteo Exp $
*
* Frontend to edit the configuration
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
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

function printInputFieldByType($key, $type)
{
    global $PMF_CONF, $PMF_LANG; //$PMF_ROOT_DIR, $languageCodes;
    switch($type) {
        case 'area':
            printf('<textarea name="edit[%s]" cols="60" rows="6">%s</textarea>', $key, $PMF_CONF[$key]);
            printf("<br />\n");
            break;

        case 'input':
            printf('<input type="text" name="edit[%s]" size="80" value="%s" />', $key, $PMF_CONF[$key]);
            printf("<br />\n");
            break;

        case 'select':
            printf('<select name="edit[%s]" size="1">', $key);
            $languages = getAvailableLanguages();
            if (count($languages) > 0) {
                print languageOptions(str_replace(array("language_", ".php"), "", $PMF_CONF['language']), false, true);
            } else {
                print '<option value="language_en.php">English</option>';
            }
            print '</select>';
            printf("<br />\n");
            break;

        case 'checkbox':
            printf('<input type="checkbox" name="edit[%s]" value="TRUE"', $key);
            if (isset($PMF_CONF[$key]) && $PMF_CONF[$key] == 'TRUE') {
                print ' checked="checked"';
            }
            printf(' />&nbsp;%s', $PMF_LANG["ad_entry_active"]);
            printf("<br />\n");
            break;

        case 'print':
            print $PMF_CONF[$key];
            printf('<input type="hidden" name="edit[%s]" size="80" value="%s" />', $key, $PMF_CONF[$key]);
            printf("<br />\n");
            break;
    }
}

if ($permission['editconfig']) {
?>
    <h2><?php print $PMF_LANG["ad_config_edit"]; ?></h2>
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <fieldset>
    <legend><?php print $PMF_LANG["ad_config_edit"]; ?></legend>
    <input type="hidden" name="aktion" value="saveconfig" />
<?php
    foreach ($LANG_CONF as $key => $value) {
        if (false === strpos($key, "spam")) {
?>
        <label><?php print $value[1]; ?></label><br />
<?php
            printInputFieldByType($key, $value[0]);
        }
    }
?>
    </fieldset>
    <fieldset>
        <legend><?php print $PMF_LANG['spamControlCenter']; ?></legend>
<?php
    foreach ($LANG_CONF as $key => $value) {
        if (0 === strpos($key, "spam")) {
?>
            <label><?php print $value[1]; ?></label><br />
<?php
            printInputFieldByType($key, $value[0]);
        }
    }
?>
    </fieldset>
    <p align="center">
        <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_config_save"]; ?>" />
        <input class="submit" type="reset" value="<?php print $PMF_LANG["ad_config_reset"]; ?>" />
    </p>
    </form>
<?php
} else {
    print $PMF_LANG["err_NotAuth"];
}
?>
