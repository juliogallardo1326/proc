<?php
/**
* $Id: plugins.firefoxsearch.php,v 1.1.2.8.2.5 2006/05/01 16:30:13 thorstenr Exp $
*
* This is search plugin for Mozilla Firefox.
*
* @author       Periklis Tsirakidis <tsirakidis@phpdevel.de>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2005-09-19
* @copyright:   (c) 2006 phpMyFAQ Team
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

printf('<h2>%s</h2>', $PMF_LANG['ad_search_plugin_title']);

if (isset($_POST['aktion']) && $_POST['aktion'] == 'firefoxsearch') {
    // From the spec standpoint these two names below must match except for the extension
    // See: http://mycroft.mozdev.org/deepdocs/quickstart.html
    // TODO: Better handle of unique name, now we are limited to one PMF plugin for each different hostname. Request it into the plugin generation form?
    $uniquePluginName = $_SERVER['HTTP_HOST'].'.pmfsearch';
    $plugin_file = $uniquePluginName.'.src';
    $plugin_icon = $uniquePluginName.'.png';

    $_SERVER['PHP_SELF'] = str_replace('%2F', '/', rawurlencode($_SERVER['PHP_SELF']));
    $baseUrl = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER["HTTP_HOST"].str_replace ('/admin/index.php', '', $_SERVER['PHP_SELF']);
    $search_url = $baseUrl.'/index.php';
    $src_url    = $baseUrl;

    // Firefox Search Plugin SRC file Header
    $search  = "<search version=\"7.1\""
    ."\n        name=\"".$_POST["sptitlei"]."\""
    ."\n        description=\"".$_POST['spdesci']."\""
    ."\n        action=\"".$search_url."\""
    ."\n        searchForm=\"".$search_url."\""
    ."\n        method=\"GET\">";
    // Firefox Search Plugin SRC file Keys
    $search .= "\n    <input name=\"search\" user>"
    ."\n    <input name=\"action\" value=\"search\">";
    // Firefox Search Plugin SRC file Style
    $search .= "\n    <interpret browserResultType=\"result\""
    ."\n               resultListStart=\"<ul>\""
    ."\n               resultListEnd=\"</ul>\""
    ."\n               resultItemStart=\"<li>\""
    ."\n               resultItemEnd=\"</li>\">";
    $search .= "\n</search>";

    $search.= "\n<browser update=\"".$src_url."/".$plugin_file."\""
    ."\n         updateIcon=\"".$src_url."/images/".$plugin_icon."\""
    ."\n         updateCheckDays=\"3\">";

    // Set the SRC file
    // Prepare a TMP file
    $tmp_file_name = tempnam(dirname(dirname(__FILE__)), "pmf_");
    // Remove the previous SRC file
    if (file_exists(dirname(dirname(__FILE__)).'/'.$plugin_file)) {
        unlink(dirname(dirname(__FILE__)).'/'.$plugin_file);
    }
    // Write the TMP file
    $tmp_file_handle = fopen($tmp_file_name, "w");
    fwrite($tmp_file_handle, $search);
    fclose($tmp_file_handle);
    // Set the TMP file as the new SRC file
    copy($tmp_file_name, dirname(dirname(__FILE__)).'/'.$plugin_file);
    chmod(dirname(dirname(__FILE__)).'/'.$plugin_file, 0755);
    unlink($tmp_file_name);

    // Set the ICON file
    if (file_exists(dirname(dirname(__FILE__)).'/'.$plugin_icon)) {
        unlink(dirname(dirname(__FILE__)).'/'.$plugin_icon);
    }
    copy(dirname(dirname(__FILE__)).'/images/pmfsearch.png', dirname(dirname(__FILE__)).'/images/'.$plugin_icon);
    chmod(dirname(dirname(__FILE__)).'/images/'.$plugin_icon, 0644);

    print $PMF_LANG['ad_search_plugin_success'];
}