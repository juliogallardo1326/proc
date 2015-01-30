<?php
/**
* $Id: search.php,v 1.3.2.12.2.12 2006/05/10 19:55:06 thorstenr Exp $
*
* The fulltext search page
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Periklis Tsirakidis <tsirakidis@phpdevel.de>
* @since        2002-09-16
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

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

// HACK: (re)evaluate the Category object w/o passing the user language
//       so the result set of a Search will have the Category Path
//       for any of the multilanguage faq records and the Category list
//       on the left pane will not be affected
$tree = new Category();
$tree->transform(0);

if (isset($_GET['suchbegriff']) || isset($_GET['search'])) {
	if (isset($_GET['suchbegriff'])) {
		$suchbegriff = $db->escape_string(strip_tags($_GET['suchbegriff']));
		$searchcategory = isset($_GET['searchcategory']) ? $db->escape_string(strip_tags($_GET['searchcategory'])) : '%';
	}
	if (isset($_GET['search'])) {
		$suchbegriff = $db->escape_string(strip_tags($_GET['search']));
		$searchcategory = isset($_GET['searchcategory']) ? $db->escape_string(strip_tags($_GET['searchcategory'])) : '%';
	}
	$printResult = searchEngine($suchbegriff, $searchcategory);
} else {
	$printResult = $PMF_LANG['help_search'];
    $suchbegriff = '';
}

Tracking('fulltext_search', $suchbegriff);

$tree->buildTree();

$baseUrl = 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER["HTTP_HOST"].str_replace ('/index.php', '', $_SERVER['PHP_SELF']);
$firefoxPluginTitle = '';
$MSIEPluginTitle = '';

if (file_exists(dirname(__FILE__).'/'.$_SERVER['HTTP_HOST'].'.pmfsearch.src')) {
    $firefoxPluginTitle = '<p><a class="searchplugin" href="javascript:addEngine(\''.$baseUrl.'\', \''.$_SERVER['HTTP_HOST'].'.pmfsearch\', \'png\', \'Web\')">'.$PMF_LANG['ad_search_plugin_install'].'</a></p>';
}
if (file_exists(dirname(__FILE__).'/'.$_SERVER['HTTP_HOST'].'.pmfsearch.xml')) {
    $MSIEPluginTitle = '<p><a class="searchplugin" href="#" onclick="window.external.AddSearchProvider(&quot;'.$baseUrl.'/'.$_SERVER['HTTP_HOST'].'.pmfsearch.xml&quot;);">'.$PMF_LANG['ad_msiesearch_plugin_install'].'</a></p>';
}

$tpl->processTemplate('writeContent', array(
				      'msgSearch' => $PMF_LANG['msgSearch'],
                      'searchString' => $suchbegriff,
                      'selectCategories' => $PMF_LANG['msgSelectCategories'],
                      'allCategories' => $PMF_LANG['msgAllCategories'],
                      'printCategoryOptions' => $tree->printCategoryOptions(0),
				      'writeSendAdress' => $_SERVER['PHP_SELF'].'?'.$sids.'action=search',
				      'msgSearchWord' => $PMF_LANG['msgSearchWord'],
				      'printResult' => $printResult,
                      'msgFirefoxPluginTitle' => $firefoxPluginTitle,
                      'msgMSIEPluginTitle' => $MSIEPluginTitle));

$tpl->includeTemplate('writeContent', 'index');
