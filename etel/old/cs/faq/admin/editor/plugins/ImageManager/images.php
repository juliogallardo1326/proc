<?php
/**
 * Show a list of images in a long horizontal table.
 * @author $Author: thorstenr $
 * @version $Id: images.php,v 1.3.2.4.2.1 2006/05/03 10:14:49 thorstenr Exp $
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

    //default path is /
    $relative = '/';
    $manager = new ImageManager($IMConfig);

    //process any file uploads
    $manager->processUploads();

    $manager->deleteFiles();

    $refreshDir = false;
    //process any directory functions
    if($manager->deleteDirs() || $manager->processNewDir())
    $refreshDir = true;

    //check for any sub-directory request
    //check that the requested sub-directory exists
    //and valid
    if(isset($_REQUEST['dir']))
    {
        $path = rawurldecode($_REQUEST['dir']);
        if($manager->validRelativePath($path))
        $relative = $path;
    }


    $manager = new ImageManager($IMConfig);


    //get the list of files and directories
    $list = $manager->getFiles($relative);


    /* ================= OUTPUT/DRAW FUNCTIONS ======================= */

    /**
 * Draw the files in an table.
 */
    function drawFiles($list, &$manager)
    {
        global $relative;

        foreach($list as $entry => $file)
	{ ?>
		<td><table width="100" cellpadding="0" cellspacing="0"><tr><td class="block">
		<a href="javascript:;" onclick="selectImage('<?php print $file['relative'];?>', '<?php print $entry; ?>', <?php print $file['image'][0];?>, <?php print $file['image'][1]; ?>);"title="<?php print $entry; ?> - <?php print Files::formatSize($file['stat']['size']); ?>"><img src="<?php print $manager->getThumbnail($file['relative']); ?>" alt="<?php print $entry; ?> - <?php print Files::formatSize($file['stat']['size']); ?>"/></a>
		</td></tr><tr><td class="edit">
			<a href="images.php?uin=<?php print $_REQUEST["uin"]; ?>&amp;dir=<?php print $relative; ?>&amp;delf=<?php print rawurlencode($file['relative']);?>" title="Trash" onclick="return confirmDeleteFile('<?php print $entry; ?>');"><img src="img/edit_trash.gif" height="15" width="15" alt="Trash"/></a>
		<?php if($file['image']){ echo $file['image'][0].'x'.$file['image'][1]; } else echo $entry;?>
		</td></tr></table></td> 
	  <?php 
	}//foreach
    }//function drawFiles


    /**
 * Draw the directory.
 */
    function drawDirs($list, &$manager)
    {
        global $relative;

        foreach($list as $path => $dir)
	{ ?>
		<td><table width="100" cellpadding="0" cellspacing="0"><tr><td class="block">
		<a href="images.php?uin=<?php print $_REQUEST["uin"]; ?>&amp;dir=<?php print rawurlencode($path); ?>" onclick="updateDir('<?php print $path; ?>')" title="<?php print $dir['entry']; ?>"><img src="img/folder.gif" height="80" width="80" alt="<?php print $dir['entry']; ?>" /></a>
		</td></tr><tr>
		<td class="edit">
			<a href="images.php?uin=<?php print $_REQUEST["uin"]; ?>&amp;dir=<?php print $relative; ?>&amp;deld=<?php print rawurlencode($path); ?>" title="Trash" onclick="return confirmDeleteDir('<?php print $dir['entry']; ?>', <?php print $dir['count']; ?>);"><img src="img/edit_trash.gif" height="15" width="15" alt="Trash"/></a>
			<?php print $dir['entry']; ?>
		</td>
		</tr></table></td>
	  <?php 
	} //foreach
    }//function drawDirs


    /**
 * No directories and no files.
 */
    function drawNoResults()
    {
?>
<table width="100%">
  <tr>
    <td class="noResult">No Images Found</td>
  </tr>
</table>
<?php	
    }

    /**
 * No directories and no files.
 */
    function drawErrorBase(&$manager)
    {
?>
<table width="100%">
  <tr>
    <td class="error">Invalid base directory: <?php print $manager->config['base_dir']; ?></td>
  </tr>
</table>
<?php	
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Image List</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="assets/imagelist.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/

if(window.top)
I18N = window.top.I18N;

function hideMessage()
{
    var topDoc = window.top.document;
    var messages = topDoc.getElementById('messages');
    if(messages)
    messages.style.display = "none";
}

init = function()
{
    hideMessage();
    var topDoc = window.top.document;

    <?php
    //we need to refesh the drop directory list
    //save the current dir, delete all select options
    //add the new list, re-select the saved dir.
    if($refreshDir)
    {
        $dirs = $manager->getDirs();
        ?>
        var selection = topDoc.getElementById('dirPath');
        var currentDir = selection.options[selection.selectedIndex].text;

        while(selection.length > 0)
        {	selection.remove(0); }

        selection.options[selection.length] = new Option("/","<?php print rawurlencode('/'); ?>");
        <?php foreach($dirs as $relative=>$fullpath) { ?>
        selection.options[selection.length] = new Option("<?php print $relative; ?>","<?php print rawurlencode($relative); ?>");
        <?php } ?>

        for(var i = 0; i < selection.length; i++)
        {
            var thisDir = selection.options[i].text;
            if(thisDir == currentDir)
            {
                selection.selectedIndex = i;
                break;
            }
        }
        <?php } ?>
}

function editImage(image)
{
    var url = "editor.php?img="+image;
    Dialog(url, function(param)
    {
        if (!param) // user must have pressed Cancel
        return false;
        else
        {
            return true;
        }
    }, null);
}

/*]]>*/
</script>
<script type="text/javascript" src="assets/images.js"></script>
</head>

<body>
<?php if ($manager->isValidBase() == false) { drawErrorBase($manager); } 
	elseif(count($list[0]) > 0 || count($list[1]) > 0) { ?>
<table>
	<tr>
	<?php drawDirs($list[0], $manager); ?>
	<?php drawFiles($list[1], $manager); ?>
	</tr>
</table>
<?php } else { drawNoResults(); } ?>
</body>
</html>
<?php
}
else {
    print $PMF_LANG["err_NotAuth"];
}
?>
