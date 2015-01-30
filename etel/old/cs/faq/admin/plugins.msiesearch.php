<?php
/**
* $Id: plugins.msiesearch.php,v 1.1.2.1 2006/05/01 16:30:13 thorstenr Exp $
*
* This is search plugin for Microsoft Internet Explorer 7.
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2006-05-01
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

if (isset($_POST['aktion']) && $_POST['aktion'] == 'msiesearch') {
    
    $uniquePluginName = $_SERVER['HTTP_HOST'].'.pmfsearch';
    $plugin_file = $uniquePluginName.'.xml';
    
    $_SERVER['PHP_SELF'] = str_replace('%2F', '/', rawurlencode($_SERVER['PHP_SELF']));
    $baseUrl = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER["HTTP_HOST"].str_replace ('/admin/index.php', '', $_SERVER['PHP_SELF']);
    $search_url = $baseUrl.'/index.php';
    $src_url    = $baseUrl;
    
    // OpenSearch XML file
    $search  = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG['metaCharset']."\"?>
<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\">
<ShortName>".$_POST['sptitlei']."</ShortName>
<Description>".$_POST['spdesci']."</Description>
<Url type=\"text/html\" template=\"".$search_url."\" />
<Language>".$PMF_LANG['metaLanguage']."</Language>
<OutputEncoding>".$PMF_LANG['metaCharset']."</OutputEncoding>
<Contact>".$PMF_CONF['adminmail']."</Contact>
</OpenSearchDescription>";
    
    // Set the XML file
    // Prepare a TMP file
    $tmp_file_name = tempnam(dirname(dirname(__FILE__)), "pmf_");
    // Remove the previous XML file
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
    print $PMF_LANG['ad_msiesearch_plugin_success'];
}