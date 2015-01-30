<?php
/**
* $Id: plugins.main.php,v 1.1.2.1 2006/05/01 16:30:13 thorstenr Exp $
*
* This is main plugin page.
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @author       Periklis Tsirakidis <tsirakidis@phpdevel.de>
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

?>
<h2><?php print $PMF_LANG["ad_search_plugin_title"]; ?></h2>
<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
<fieldset>
    <legend><?php print $PMF_LANG["ad_firefoxsearch_plugin_title"]; ?></legend>
    <input type="hidden" name="aktion" value="firefoxsearch" />

    <label class="left" for="sptitle"><?php print $PMF_LANG["ad_search_plugin_ttitle"]; ?></label>
    <input type="text" name="sptitlei" size="40" /><br />

    <label class="left" for="spdesc"><?php print $PMF_LANG["ad_search_plugin_tdesc"]; ?></label>
    <input type="text" name="spdesci" size="40" /><br />

    <div align="center">
    <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_search_plugin_create"]; ?>" />
    </div>
    
</fieldset>
</form>



<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
<fieldset>
    <legend><?php print $PMF_LANG["ad_msiesearch_plugin_title"]; ?></legend>
    <input type="hidden" name="aktion" value="msiesearch" />

    <label class="left" for="sptitle"><?php print $PMF_LANG["ad_msiesearch_plugin_ttitle"]; ?></label>
    <input type="text" name="sptitlei" size="40" /><br />

    <label class="left" for="spdesc"><?php print $PMF_LANG["ad_search_plugin_tdesc"]; ?></label>
    <input type="text" name="spdesci" size="40" /><br />

    <div align="center">
    <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_msiesearch_plugin_create"]; ?>" />
    </div>
    
</fieldset>
</form>