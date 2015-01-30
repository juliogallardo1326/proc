<?php
/**
 * The main GUI for the ImageManager.
 * @author $Author: thorstenr $
 * @version $Id: manager.php,v 1.3.2.3.2.2 2006/05/03 10:14:50 thorstenr Exp $
 * @package ImageManager
 */

define("PMF_ROOT_DIR", dirname(dirname(dirname(dirname(dirname(__FILE__))))));

/* read configuration, include classes and functions */
require_once (PMF_ROOT_DIR."/inc/data.php");
require_once (PMF_ROOT_DIR."/inc/db.php");
define("SQLPREFIX", $DB["prefix"]);
$db = db::db_select($DB["type"]);
$db->connect($DB["server"], $DB["user"], $DB["password"], $DB["db"]);
require_once (PMF_ROOT_DIR."/inc/config.php");
require_once (PMF_ROOT_DIR."/inc/constants.php");
require_once (PMF_ROOT_DIR."/inc/category.php");
require_once (PMF_ROOT_DIR."/inc/functions.php");
require_once (PMF_ROOT_DIR."/lang/language_en.php");

// Delete old sessions
$db->query("DELETE FROM ".SQLPREFIX."faqadminsessions WHERE time < ".(time() - (PMF_AUTH_TIMEOUT * 60)));

// Is there an UIN? -> take it for authentication
if (isset($_REQUEST['uin']) && !preg_match('/^[a-z0-9]$/i', $_REQUEST['uin'])) {
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

if (isset($user) && isset($pass)) {
	$result = $db->query("SELECT id, name, realname, email, pass, rights FROM ".SQLPREFIX."faquser WHERE name = '".$user."' AND pass = '".$pass."'");
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

if ($auth && $permission["addatt"]) {
    require_once('config.inc.php');
    require_once('Classes/ImageManager.php');
    $manager = new ImageManager($IMConfig);
    $dirs = $manager->getDirs();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Insert Image</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link href="assets/manager.css" rel="stylesheet" type="text/css" />	
<script type="text/javascript" src="assets/popup.js"></script>
<script type="text/javascript" src="assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
	window.resizeTo(600, 460);

	if(window.opener)
		I18N = window.opener.ImageManager.I18N;

	var thumbdir = "<?php echo $IMConfig['thumbnail_dir']; ?>";
	var base_url = "<?php echo $manager->getBaseURL(); ?>";
/*]]>*/
</script>
<script type="text/javascript" src="assets/manager.js"></script>
</head>
<body>
<div class="title">Insert Image</div>
<form action="images.php?uin=<?php print $uin; ?>" id="uploadForm" method="post" enctype="multipart/form-data">
<fieldset><legend>Image Manager</legend>
<div class="dirs">
	<label for="dirPath">Directory</label>
	<select name="dir" class="dirWidth" id="dirPath" onchange="updateDir(this)">
	<option value="/">/</option>
<?php foreach($dirs as $relative=>$fullpath) { ?>
		<option value="<?php echo rawurlencode($relative); ?>"><?php echo $relative; ?></option>
<?php } ?>
	</select>
	<!-- <a href="#" onclick="javascript: goUpDir('<?php print $uin; ?>');" title="Directory Up"><img src="img/btnFolderUp.gif" height="15" width="15" alt="Directory Up" /></a>
<?php if($IMConfig['safe_mode'] == false && $IMConfig['allow_new_dir']) { ?>
	<a href="#" onclick="newFolder('<?php print $uin; ?>');" title="New Folder"><img src="img/btnFolderNew.gif" height="15" width="15" alt="New Folder" /></a> -->
<?php } ?>
	<div id="messages" style="display: none;"><span id="message"></span><img SRC="img/dots.gif" width="22" height="12" alt="..." /></div>
	<iframe src="images.php?uin=<?php print $uin; ?>" name="imgManager" id="imgManager" class="imageFrame" scrolling="auto" title="Image Selection" frameborder="0"></iframe>
</div>
</fieldset>
<!-- image properties -->
	<table class="inputTable">
		<tr>
			<td align="right"><label for="f_url">Image File</label></td>
			<td><input type="text" id="f_url" class="largelWidth" value="" /></td>
			<td rowspan="3" align="right">&nbsp;</td>
			<td align="right"><label for="f_width">Width</label></td>
			<td><input type="text" id="f_width" class="smallWidth" value="" onchange="javascript:checkConstrains('width');"/></td>
			<td rowspan="2" align="right"><img src="img/locked.gif" id="imgLock" width="25" height="32" alt="Constrained Proportions" /></td>
			<td rowspan="3" align="right">&nbsp;</td>
			<td align="right"><label for="f_vert">V Space</label></td>
			<td><input type="text" id="f_vert" class="smallWidth" value="" /></td>
		</tr>		
		<tr>
			<td align="right"><label for="f_alt">Alt</label></td>
			<td><input type="text" id="f_alt" class="largelWidth" value="" /></td>
			<td align="right"><label for="f_height">Height</label></td>
			<td><input type="text" id="f_height" class="smallWidth" value="" onchange="javascript:checkConstrains('height');"/></td>
			<td align="right"><label for="f_horiz">H Space</label></td>
			<td><input type="text" id="f_horiz" class="smallWidth" value="" /></td>
		</tr>
		<tr>
<?php if($IMConfig['allow_upload'] == true) { ?>
			<td align="right"><label for="upload">Upload</label></td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td><input type="file" name="upload" id="upload"/></td>
                    <td>&nbsp;<button type="submit" name="submit" onclick="doUpload();"/>Upload</button></td>
                  </tr>
                </table>
			</td>
<?php } else { ?>
			<td colspan="2"></td>
<?php } ?>
			<td align="right"><label for="f_align">Align</label></td>
			<td colspan="2">
				<select size="1" id="f_align"  title="Positioning of this image">
				  <option value=""                             >Not Set</option>
				  <option value="left"                         >Left</option>
				  <option value="right"                        >Right</option>
				  <option value="texttop"                      >Texttop</option>
				  <option value="absmiddle"                    >Absmiddle</option>
				  <option value="baseline" selected="selected" >Baseline</option>
				  <option value="absbottom"                    >Absbottom</option>
				  <option value="bottom"                       >Bottom</option>
				  <option value="middle"                       >Middle</option>
				  <option value="top"                          >Top</option>
				</select>
			</td>
			<td align="right"><label for="f_border">Border</label></td>
			<td><input type="text" id="f_border" class="smallWidth" value="" /></td>
		</tr>
		<tr> 
         <td colspan="4" align="right">
				<input type="hidden" id="orginal_width" />
				<input type="hidden" id="orginal_height" />
            <input type="checkbox" id="constrain_prop" checked="checked" onclick="javascript:toggleConstrains(this);" />
          </td>
          <td colspan="5"><label for="constrain_prop">Constrain Proportions</label></td>
      </tr>
	</table>
<!--// image properties -->	
	<div style="text-align: right;"> 
          <hr />
		  <button type="button" class="buttons" onclick="return refresh();">Refresh</button>
          <button type="button" class="buttons" onclick="return onOK();">OK</button>
          <button type="button" class="buttons" onclick="return onCancel();">Cancel</button>
    </div>
</form>
</body>
</html>
<?php
	}
else {
	print $PMF_LANG["err_NotAuth"];
	}
?>
