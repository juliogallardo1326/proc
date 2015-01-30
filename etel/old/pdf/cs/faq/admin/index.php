<?php
/**
* $Id: index.php,v 1.9.2.22.2.16 2006/05/08 16:04:55 thorstenr Exp $
*
* The main admin backend index file
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Bastian Poettner <bastian@poettner.net>
* @author       Meikel Katzengreis <meikel@katzengreis.com>
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

require_once('../inc/functions.php');
require_once('../inc/init.php');
define('IS_VALID_PHPMYFAQ_ADMIN', null);
define('PMF_ROOT_DIR', dirname(dirname(__FILE__)));
PMF_Init::cleanRequest();

// delete cookie before sending a header
if (isset($_REQUEST['aktion']) && $_REQUEST['aktion'] == 'delcookie') {
	setcookie('cuser', '', time() + 30);
	setcookie('cpass', '', time() + 30);
}

// Read configuration, include classes and functions
require_once(PMF_ROOT_DIR.'/inc/data.php');
require_once(PMF_ROOT_DIR.'/inc/db.php');
define('SQLPREFIX', $DB['prefix']);
$db = db::db_select($DB['type']);
$db->connect($DB['server'], $DB['user'], $DB['password'], $DB['db']);
require_once(PMF_ROOT_DIR.'/inc/config.php');
require_once(PMF_ROOT_DIR.'/inc/constants.php');
require_once(PMF_ROOT_DIR.'/inc/category.php');

// Set cookie before sending a header
if (isset($_POST["aktion"]) && $_POST["aktion"] == "setcookie") {
    if (isset($_POST["cuser"])) {
        setcookie("cuser", $_POST["cuser"], time() + (86400 * PMF_AUTH_TIMEOUT));
    }
    if (isset($_POST["cpass"])) {
        setcookie("cpass", $_POST["cpass"], time() + (86400 * PMF_AUTH_TIMEOUT));
    }
}

// HACK: do not use pmf_lang cookie if we're saving the PMF Configuration
if (isset($_REQUEST['aktion']) && ($_REQUEST['aktion'] == 'saveconfig')) {
    // Read the language file that is going to be saved in the PMF configuration
    $PMF_CONF['language'] = $_REQUEST['edit']['language'];
    // Force a POST "lang" key: PMF_Init class will rewrite the pmf_lang cookie
    // according to the new value
    $_POST['language'] = str_replace(array("language_", ".php"), "", $PMF_CONF['language']);
}

// get language (default: english)
$pmf = new PMF_Init();
$LANGCODE = $pmf->setLanguage((isset($PMF_CONF['detection']) ? true : false), $PMF_CONF['language']);
// Preload English strings
require_once ('../lang/language_en.php');

if (isset($LANGCODE) && PMF_Init::isASupportedLanguage($LANGCODE)) {
    // Overwrite English strings with the ones we have in the current language
    require_once('../lang/language_'.$LANGCODE.'.php');
} else {
    $LANGCODE = 'en';
}

// use mbstring extension if available
$valid_mb_strings = array('ja', 'en');
if (function_exists('mb_language') && in_array($PMF_LANG['metaLanguage'], $valid_mb_strings)) {
    mb_language($PMF_LANG['metaLanguage']);
    mb_internal_encoding($PMF_LANG['metaCharset']);
}

unset($auth);

// If the cookie is set, take the data from it
if (isset($_COOKIE['cuser']) && !preg_match('/^[a-z0-9]$/i', $_COOKIE['cuser'])) {
    $user = $_COOKIE['cuser'];
} else {
    unset($user);
}
if (isset($_COOKIE['cpass']) && !preg_match('/^[a-z0-9]$/i', $_COOKIE['cpass'])) {
    $pass = $_COOKIE['cpass'];
} else {
    unset($pass);
}

// Delete old sessions
$db->query("DELETE FROM ".SQLPREFIX."faqadminsessions WHERE time < ".(time() - (PMF_AUTH_TIMEOUT * 60)));

// Is there an UIN? -> take it for authentication
if (isset($_REQUEST['uin']) && preg_match('/^(\w{32})$/i', substr($_REQUEST['uin'], 0, 32))) {
    $uin = $_REQUEST['uin'];
} else {
    unset($uin);
}

if (isset($uin)) {
	$query = "SELECT usr, pass FROM ".SQLPREFIX."faqadminsessions WHERE uin = '".$uin."'";
	if (isset($PMF_CONF["ipcheck"]) && $PMF_CONF["ipcheck"] == "TRUE") {
		$query .= " AND ip = '".$_SERVER["REMOTE_ADDR"]."'";
	}
    $_result = $db->query($query);

    if ($row = $db->fetch_object($_result)) {
        $user = $row->usr;
        $pass = $row->pass;
        $db->query ("UPDATE ".SQLPREFIX."faqadminsessions SET time = ".time()." WHERE uin = '".$uin."'");
    } else {
        adminlog("Session expired\nUIN: ".$uin);
        $error = $PMF_LANG["ad_auth_sess"];
        unset($auth);
        unset($uin);
        $_REQUEST["aktion"] = "";
    }
}

// Authenticate the user from login POST variables
if (isset($_POST["faqusername"])) {
    $user = $db->escape_string($_POST["faqusername"]);
}
if (isset($_POST["faqpassword"])) {
    $pass = md5($db->escape_string($_POST["faqpassword"]));
}

if (isset($user) && isset($pass)) {

    $query = sprintf("SELECT id, name, realname, email, pass, rights FROM %sfaquser WHERE name = '%s' AND pass = '%s'", SQLPREFIX, $user, $pass);
	$result = $db->query($query);

	if ($db->num_rows($result) != 1) {
        // error
        adminlog("Loginerror\nLogin: ".$user."\nPass: ".$pass);
        $error = $PMF_LANG["ad_auth_fail"]." (".$user." / *)";
        unset($auth);
        unset($uin);
        $_REQUEST["aktion"] = "";
	} else {
		// okay, write new session, if not written
		$auth = 1;
		if (!isset($uin)) {
			$ok = 0;
			while (!$ok) {
				srand((double)microtime()*1000000);
                $uin = md5(uniqid(rand()));
                if ($db->num_rows($db->query("SELECT uin FROM ".SQLPREFIX."faqadminsessions WHERE uin = '".$uin."'")) < 1) {
                    $ok = 1;
                } else {
					$ok = 0;
			    }
			}
            $db->query("INSERT INTO ".SQLPREFIX."faqadminsessions (uin, time, ip, usr, pass) VALUES ('".$uin."',".time().",'".$_SERVER["REMOTE_ADDR"]."','".$user."','".$pass."')");
		}
		$linkext = "?uin=".$uin;
        if ($row = $db->fetch_object($result)) {
            $auth_id = $row->id;
            $auth_user = $row->name;
		    $auth_pass = $row->pass;
            $auth_realname = $row->realname;
            $auth_email = $row->email;

            $num_rights = count($faqrights);
            $permission = array();
            for ($i = 1, $j = 0; $i <= $num_rights; $i++, $j++) {
                $permission[$faqrights[$i]] = $row->rights{$j};
            }
        }
	}
}

// Logout - delete session
if (isset($_REQUEST["aktion"]) && $_REQUEST["aktion"] == "logout" && $auth) {
	$db->query("DELETE FROM ".SQLPREFIX."faqadminsessions WHERE uin = '".$uin."'");
	unset($auth);
	unset($uin);
}

// Header of the admin page
require_once ("header.php");
if (isset($auth)) {
	require_once ("menue.php");
}
?>
</div>
<div id="bodyText">
<?php
// User is authenticated
if (isset($auth)) {
	if (isset($_REQUEST["aktion"])) {
        // the various sections of the admin area
		switch ($_REQUEST["aktion"]) {
			// functions for user administration
			case 'user':
			case 'addsave':
			case 'usersave':
			case 'deluser':                 require_once ('user.list.php'); break;
			case "useredit":				require_once ("user.edit.php"); break;
			case "userdel":					require_once ("user.question.php"); break;
			case "useradd":					require_once ("user.add.php"); break;
			// functions for record administration
			case "view":					require_once ("record.show.php"); break;
			case "accept":					require_once ("record.show.php"); break;
			case "zeichan":					require_once ("record.show.php"); break;
			case "takequestion":			require_once ("record.edit.php"); break;
			case "editentry":				require_once ("record.edit.php"); break;
            case "editpreview":             require_once ("record.edit.php"); break;
			case "delcomment":				require_once ("record.delcommentform.php"); break;
			case "deletecomment":			require_once ("record.delcomment.php"); break;
			case "insertentry":				require_once ("record.add.php"); break;
			case "saveentry":				require_once ("record.save.php"); break;
			case "delentry":				require_once ("record.delete.php"); break;
			case "delatt":					require_once ("record.delatt.php"); break;
			case "question":				require_once ("record.delquestion.php"); break;
			// news administraion
			case "news":					require_once ("news.php"); break;
			// category administration
			case 'category':
            case 'savecategory':
            case 'updatecategory':
            case 'removecategory':          require_once ('category.main.php'); break;
            case "deletecategory":          require_once ("category.delete.php"); break;
            case "addcategory":             require_once ("category.add.php"); break;
            case "editcategory":            require_once ("category.edit.php"); break;
            case "cutcategory":             require_once ("category.cut.php"); break;
            case "pastecategory":           require_once ("category.paste.php"); break;
            case "movecategory":            require_once ("category.move.php"); break;
            case "changecategory":          require_once ("category.change.php"); break;
			// functions for cookie administration
			case "setcookie":				require_once ("cookie.check.php"); break;
			case "cookies":					require_once ("cookie.check.php"); break;
			case "delcookie":				require_once ("cookie.check.php"); break;
			// adminlog administration
			case "adminlog":				require_once ("adminlog.php"); break;
			// functions for password administration
			case "passwd":					require_once ("pwd.change.php"); break;
			case "savepwd":					require_once ("pwd.save.php"); break;
			// functions for session administration
			case "viewsessions":			require_once ("stat.main.php"); break;
			case "sessionbrowse":			require_once ("stat.browser.php"); break;
			case "sessionsearch":			require_once ("stat.query.php"); break;
			case "sessionsuche":			require_once ("stat.form.php"); break;
			case "viewsession":				require_once ("stat.show.php"); break;
			case "statistik":				require_once ("stat.ratings.php"); break;
			// functions for config administration
			case "editconfig":				require_once ("config.edit.php"); break;
			case "saveconfig":				require_once ("config.save.php"); break;
			// functions for backup administration
			case "csv":						require_once ("backup.main.php"); break;
			case "restore":					require_once ("backup.import.php"); break;
			case "xml":						require_once ("backup.xml.php"); break;
			// functions for FAQ export
			case "export":					require_once ("export.main.php"); break;
            case 'plugins':                 require_once ('plugins.main.php'); break;
            case 'firefoxsearch':			require_once ('plugins.firefoxsearch.php'); break;
            case 'msiesearch':			    require_once ('plugins.msiesearch.php'); break;
			default:						print "Error"; break;
		}
	} else {
        // start page with some informations about the FAQ
        print '<h2>phpMyFAQ Information</h2>';
        $PMF_TABLE_INFO = $db->getTableStatus();
?>
    <dl class="table-display">
	    <dt><strong><?php print $PMF_LANG["ad_start_visits"]; ?></strong></dt>
        <dd><?php print $PMF_TABLE_INFO[SQLPREFIX."faqsessions"]; ?></dd>
        <dt><strong><?php print $PMF_LANG["ad_start_articles"]; ?></strong></dt>
        <dd><?php print $PMF_TABLE_INFO[SQLPREFIX."faqdata"]; ?></dd>
        <dt><strong><?php print $PMF_LANG["ad_start_comments"]; ?></strong></dt>
        <dd><?php print $PMF_TABLE_INFO[SQLPREFIX."faqcomments"]; ?></dd>
        <dt><strong><?php print $PMF_LANG["msgOpenQuestions"]; ?></strong></dt>
        <dd><?php print $PMF_TABLE_INFO[SQLPREFIX."faqfragen"]; ?></dd>
    </dl>
<?php
        $rg = (1 == @ini_get('register_globals')) ? 'on' : 'off';
        $sm = (1 == @ini_get('safe_mode')) ? 'on' : 'off';
?>
	<h2>System Information</h2>
	<dl class="table-display">
		<dt><strong>phpMyFAQ Version</strong></dt>
		<dd>phpMyFAQ <?php print $PMF_CONF['version']; ?></dd>
		<dt><strong>Server Software</strong></dt>
		<dd><?php print $_SERVER['SERVER_SOFTWARE']; ?></dd>
		<dt><strong>PHP Version</strong></dt>
		<dd>PHP <?php print phpversion(); ?></dd>
		<dt><strong>Register Globals</strong></dt>
		<dd><?php print $rg; ?></dd>
		<dt><strong>Safe Mode</strong></dt>
		<dd><?php print $sm; ?></dd>
		<dt><strong>Database Client Version</strong></dt>
		<dd><?php print $db->client_version(); ?></dd>
		<dt><strong>Database Server Version</strong></dt>
		<dd><?php print $db->server_version(); ?></dd>
		<dt><strong>Webserver Interface</strong></dt>
		<dd><?php print strtoupper(@php_sapi_name()); ?></dd>
    </dl>
	<h2>Online Version Information</h2>
<?php
        if (isset($_POST["param"]) && $_POST["param"] == "version") {
            require_once (PMF_ROOT_DIR."/inc/xmlrpc.php");
            $param = $_POST["param"];
            $xmlrpc = new xmlrpc_client("/xml/version.php", "www.phpmyfaq.de", 80);
            $msg = new xmlrpcmsg("phpmyfaq.version", array(new xmlrpcval($param, "string")));
            $answer = $xmlrpc->send($msg);
            $result = $answer->value();
            if ($answer->faultCode()) {
                print "<p>Error: ".$answer->faultCode()." (" .htmlspecialchars($answer->faultString()).")</p>";
            } else {
                printf('<p>%s <a href="http://www.phpmyfaq.de" target="_blank">www.phpmyfaq.de</a>: <strong>phpMyFAQ %s</strong>', $PMF_LANG['ad_xmlrpc_latest'], $result->scalarval());
                // Installed phpMyFAQ version is outdated
                if (-1 == version_compare($PMF_CONF["version"], $result->scalarval())) {
                    print '<br />'.$PMF_LANG['ad_you_should_update'];
                }
                print '</p>';
            }
        } else {
?>
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <input type="hidden" name="param" value="version" />
    <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_xmlrpc_button"]; ?>" />
    </form>
<?php
        }
	}
} else {
?>
	<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="post">
    <fieldset class="login">
        <legend class="login">phpMyFAQ Login</legend>
<?php
	if (isset($_REQUEST["aktion"]) && $_REQUEST["aktion"] == "logout") {
		print "<p>".$PMF_LANG["ad_logout"]."</p>";
	}
	if (isset($error)) {
		print "<p><strong>".$error."</strong></p>\n";
	} else {
		print "<p><strong>".$PMF_LANG["ad_auth_insert"]."</strong></p>\n";
	}
?>
        <label class="left"><?php print $PMF_LANG["ad_auth_user"]; ?></label>
        <input type="text" name="faqusername" size="20" /><br />

        <label class="left"><?php print $PMF_LANG["ad_auth_passwd"]; ?></label>
        <input type="password" size="20" name="faqpassword" /><br />

        <input class="submit" style="margin-left: 190px;" type="submit" value="<?php print $PMF_LANG["ad_auth_ok"]; ?>" />
        <input class="submit" type="reset" value="<?php print $PMF_LANG["ad_auth_reset"]; ?>" />

        <p><img src="images/arrow.gif" width="11" height="11" alt="<?php print $PMF_LANG["lostPassword"]; ?>" border="0" /> <a href="password.php" title="<?php print $PMF_LANG["lostPassword"]; ?>">
<?php print $PMF_LANG["lostPassword"]; ?>
</a></p>
        <p><img src="images/arrow.gif" width="11" height="11" alt="<?php print $PMF_CONF["title"]; ?> FAQ" border="0" /> <a href="../index.php" title="<?php print $PMF_CONF["title"]; ?> FAQ"><?php print $PMF_CONF["title"]; ?> FAQ</a></p>

    </fieldset>
	</form>

<?php
}

if (DEBUG == true) {
    printf("<p>DEBUG INFORMATION:</p>\n<p>%s</p>", $db->sqllog());
}

require_once ('footer.php');
$db->dbclose();
