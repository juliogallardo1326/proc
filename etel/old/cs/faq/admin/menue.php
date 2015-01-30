<?php
/**
* $Id: menue.php,v 1.2.2.10.2.2 2006/05/08 16:58:39 thorstenr Exp $
*
* Navigation menue of the admin area
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Minoru TODA <todam@netjapan.co.jp>
* @since        2003-02-26
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

function addMenuEntry($restrictions = '', $aktion = '', $caption = '')
{
	global $linkext, $permission, $PMF_LANG;

	if ($aktion != '') {
		$aktion = "&amp;aktion=".$aktion;
	}

	$output = '        <li><a href="'.$linkext.$aktion.'">'.$PMF_LANG[$caption]."</a></li>\n";
	if ($restrictions == '') {
		print $output;
		return;
	}

	foreach (explode(',', $restrictions) as $_restriction) {
		if (isset($permission[$_restriction]) && $permission[$_restriction]) {
			print $output;
			return;
		}
	}
}
?>
<div id="menue">
    <ul>
<?php
	addMenuEntry('',                                     '',                 'ad_menu_startpage');
	addMenuEntry('adduser,edituser,deluser',             'user',             'ad_menu_user_administration');
	addMenuEntry('addcateg,editcateg,delcateg',          'category',         'ad_menu_categ_edit');
	addMenuEntry('addbt',                                'editentry',        'ad_entry_add');
	addMenuEntry('editbt,delbt',                         'accept',           'ad_menu_entry_aprove');
	addMenuEntry('editbt,delbt',                         'view',             'ad_menu_entry_edit');
	addMenuEntry('addnews,editnews,delnews',             'news&amp;do=edit', 'ad_menu_news_edit');
	addMenuEntry('delquestion',                          'question',         'ad_menu_open');
	addMenuEntry('viewlog',                              'statistik',        'ad_menu_stat');
	addMenuEntry('',                                     'cookies',          'ad_menu_cookie');
	addMenuEntry('viewlog',                              'viewsessions',     'ad_menu_session');
	addMenuEntry('adminlog',                             'adminlog',         'ad_menu_adminlog');
	addMenuEntry('passwd',                               'passwd',           'ad_menu_passwd');
	addMenuEntry('editconfig',                           'editconfig',       'ad_menu_editconfig');
	addMenuEntry('backup,restore',                       'csv',              'ad_menu_backup');
	addMenuEntry('',                                     'export',           'ad_menu_export');
    addMenuEntry('',                                     'plugins',          'ad_menu_searchplugin');
	addMenuEntry('',                                     'logout',           'ad_menu_logout');
?>
    </ul>
</div>

<div id="langform">
    <form action="<?php print $linkext; ?>" method="post">
    <label for="language"><?php print $PMF_LANG['msgLangaugeSubmit']; ?></label>
    <?php print selectLanguages($LANGCODE); ?><br />
    <input type="submit" name="submit" value="Go" class="submit" />
    </form>
</div>