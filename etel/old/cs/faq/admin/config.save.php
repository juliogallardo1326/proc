<?php
/******************************************************************************
 * $Id: config.save.php,v 1.4.2.4.2.1 2006/03/26 13:44:41 matteo Exp $
 *
 * File:				config.save.php
 * Description:			save the configuration
 * Authors:				Thorsten Rinne <thorsten@phpmyfaq.de>
 * Date:				2003-02-24
 * Last change:			2006-03-26
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
if ($permission["editconfig"]) {

    print "<h2>".$PMF_LANG["ad_config_edit"]."</h2>\n";
    $fp = @fopen(PMF_ROOT_DIR."/inc/config.php", "w");
    $arrVar = $_REQUEST["edit"];
    if (isset($fp)) {
        @fputs($fp, "<?php\n// Created ".date("Y-m-d H:i:s")."\n\n");
        foreach ($arrVar as $key => $value) {
            @fputs($fp, "// ".$LANG_CONF[$key][1]."\n\$PMF_CONF[\"".$key."\"] = \"".htmlspecialchars($value)."\";\n\n");
            }
        @fputs($fp, "?>");
        @fclose($fp);
        print "<div align=\"center\"><strong>".$PMF_LANG["ad_config_saved"]."</strong></div>";
        }
    else {
        print "<div align=\"center\"><strong>".$PMF_LANG["ad_entryins_fail"]."</strong></div>";
        }
    @fclose($fp);
} else {
    print $PMF_LANG["err_NotAuth"];
}
?>
