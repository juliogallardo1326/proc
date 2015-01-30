<?php
/**
 * $Id: session.keepalive.php,v 1.1.2.5 2006/05/10 06:10:35 thorstenr Exp $
 *
 * A dummy page used within an IFRAME for warning the user about his next
 * session expiration and to give him the contextual possibility for 
 * refreshing the session by clicking <OK>
 *
 * @package     phpMyFAQ
 * @access      private
 * @author      Matteo Scaramuccia <matteo@scaramuccia.com>
 * @author      Thorsten Rinne <thorsten@phpmyfaq.de>
 * @since       2006-05-08
 * @copyright   (c) 2006 phpMyFAQ Team
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

require_once('../inc/functions.php');
require_once('../inc/init.php');
define('IS_VALID_PHPMYFAQ', null);
PMF_Init::cleanRequest();
require_once('../inc/constants.php');
require_once('../inc/config.php');
require_once('../inc/data.php');
require_once('../inc/db.php');
define('SQLPREFIX', $DB['prefix']);
$db = db::db_select($DB['type']);
$db->connect($DB['server'], $DB['user'], $DB['password'], $DB['db']);
require_once('../lang/language_en.php');

$auth_user = null;
$auth_pass = null;

if (isset($_GET['lang']) && PMF_Init::isASupportedLanguage($_GET['lang'])) {
    require_once('../lang/language_'.$_GET['lang'].'.php');
}

if (isset($_GET['hash']) && $_GET['hash'] != '') {
    $pmf_hash = $db->escape_string($_GET['hash']);
    $auth_user_pass = explode(',', base64_decode($pmf_hash));
    if (is_array($auth_user_pass)) {
        $auth_user = $auth_user_pass[0];
        $auth_pass = $auth_user_pass[1];
    }
} else {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

if (isset($_GET['uin']) && preg_match('/^(\w{32})$/i', substr($_GET['uin'], 0, 32)) && !is_null($auth_user) && !is_null($auth_pass)) {
    $uin = $db->escape_string($_GET['uin']);
	$query = sprintf("SELECT usr, pass FROM %sfaqadminsessions WHERE uin = '%s'", SQLPREFIX, $uin);
	if (isset($PMF_CONF['ipcheck']) && $PMF_CONF['ipcheck'] == 'TRUE') {
		$query .= sprintf(" AND ip = '%s'", $_SERVER['REMOTE_ADDR']);
	}
    $row = $db->fetch_object($db->query($query));
    
    if (isset($row) && $auth_user == $row->usr && $auth_pass == $row->pass) {
        $query = sprintf("UPDATE %sfaqadminsessions SET time = %d WHERE uin = '%s'", SQLPREFIX, time(), $uin);
        $db->query($query);
    } else {
        header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
        exit();
    }
    $db->dbclose();
}

$refreshTime = (PMF_AUTH_TIMEOUT - PMF_AUTH_TIMEOUT_WARNING) * 60;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $PMF_LANG["metaLanguage"]; ?>" lang="<?php print $PMF_LANG["metaLanguage"]; ?>">
<head>
    <title>phpMyFAQ - "Welcome to the real world."</title>
    <meta name="copyright" content="(c) 2001-2006 phpMyFAQ Team" />
    <meta http-equiv="Content-Type" content="text/html; charset=<?php print $PMF_LANG["metaCharset"]; ?>" />
    <link rel="shortcut icon" href="../template/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="../template/favicon.ico" type="image/x-icon" />
<?php
if ($refreshTime > 0) {
?>
        <script type="text/javascript">
        <!--
        function _PMFSessionTimeoutWarning()
        {
            if (window.confirm('<?php printf($PMF_LANG['ad_session_expiring'], PMF_AUTH_TIMEOUT_WARNING); ?>')) {
                // Reload this iframe: session refreshed!
                window.location.reload();
            }
        }
        window.setTimeout("_PMFSessionTimeoutWarning()", <?php print $refreshTime; ?> * 1000);
        //-->
        </script>
<?php
}
?>
</head>
<body>
</body>
</html>
