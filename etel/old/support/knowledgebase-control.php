<?php /* ***** Orca Knowledgebase - Control Panel ************* */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A simple and multi-use knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*************************************************************** */

/* ***** User Variables *************************************** */
$admin = "admin";
$password = "etelcscs";

$dData['hostname'] = "localhost";
$dData['username'] = "etel_root";
$dData['password'] = "WSD%780=";
$dData['database'] = "etel_dbsmain";
$dData['tablename'] = "cs_FAQ";

$dData['rlistmax'] = 20;
$dData['qlistmax'] = 20;
$dData['emailtitle'] = $_SESSION["gw_title"];
$dData['email'] = $_SESSION["gw_emails_customerservice"];
$dData['userask'] = true;
$dData['pagetitle'] = $_SESSION["gw_title"];

$fData['filemax'] = 1024000;
$fData['resource'] = "orca/resource";
$fData['fileext'] = array("gif", "jpg", "jpeg", "png", "txt", "nfo", "doc", "rtf", "htm", "zip", "rar", "gz", "exe");

$sData['orderby'] = "visited";		// QID, visited, date, question

$dData['stylesheet'] = "orca/okb_style.css";
include "orca/okb_lang_en.php";


/* ***** Do not cache this page ******************************* */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


/* ***** MySQL Setup ****************************************** */
$dData['tableq'] = $dData['tablename']."q";
$dData['tablec'] = $dData['tablename']."c";

$db = mysql_connect($dData['hostname'], $dData['username'], $dData['password']) or dieLog("Could not connect to the MySQL server!");
mysql_select_db($dData['database'], $db) or dieLog("Could not connect to the database!");

$create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tableq']}` (
  `category` text,
  `subcategory` text,
  `QID` int(11),
  `date` text,
  `question` text,
  `answer` text,
  `keywords` text,
  `online` tinytext,
  `visited` int(11)
) TYPE=MyISAM;") or dieLog("Could not create Questions table!");

$create = mysql_query("CREATE TABLE IF NOT EXISTS `{$dData['tablec']}` (
  `category` text,
  `subcategory` text
) TYPE=MyISAM;") or dieLog("Could not create Categories table!");


/* ***** Functions ******************************************** */
function dateStamp($time) {
  global $pageEncoding, $lang, $sData;

  switch ($pageEncoding) {
    case 1: $timeStr = utf8_encode(strftime($sData['dateformat'], $time)); break;
    case 2: $timeStr = strftime($sData['dateformat'], $time); break;
    default: $timeStr = @htmlentities(strftime($sData['dateformat'], $time), ENT_COMPAT, $lang['charset']); break;
  }
  return $timeStr;
}

function slashes($data) {
  $data = str_replace(chr(13), "", $data);
  return addslashes($data);
}

function orderLink($order, $name) {
  global $dData, $lang;

  if (strpos($dData['orderby'], $order) === false) {
    return "<a href=\"{$_SERVER['PHP_SELF']}?action=OrderList&amp;order=$order\" title=\"".sprintf($lang['misc1'], $name)."\">$name</a>";
  } else return "<em>$name</em>";
}

function getExt($toget) {
  return substr($toget, strrpos($toget, ".") + 1);
}

function unhtmlentities($string)  {
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip($trans_tbl);
   $ret = strtr($string, $trans_tbl);
   return preg_replace('/&#(\d+);/me', "chr('\\1')", $ret);
}


error_reporting(E_ALL);

/* ***** Magic Quotes Fix ************************************* */
if (get_magic_quotes_gpc()) {
  $fsmq = create_function('&$mData, $fnSelf', 'if (is_array($mData)) foreach ($mData as $mKey=>$mValue) $fnSelf($mData[$mKey], $fnSelf); else $mData = stripslashes($mData);');
  $fsmq($_POST, $fsmq);
  $fsmq($_GET, $fsmq);
  $fsmq($_ENV, $fsmq);
  $fsmq($_SERVER, $fsmq);
  $fsmq($_COOKIE, $fsmq);
}
set_magic_quotes_runtime(0);


/* ***** Prepare Workspace ************************************ */
$sData['logged'] = false;
$sData['start'] = (isset($_GET['start'])) ? $_GET['start'] : 1;
$sData['action'] = (isset($_POST['action'])) ? $_POST['action'] : ((isset($_GET['action'])) ? $_GET['action'] : "");
$sData['section'] = "Edit";

$dData['orderby'] = " ORDER BY `QID`";
$dData['wcategory'] = "";
$dData['wsubcateg'] = "";

if (isset($_COOKIE['orca_ctrl'])) {
  $chk = unserialize(stripslashes(base64_decode($_COOKIE['orca_ctrl'])));

  if ($chk[0] == $admin && $chk[1] == $password) {
    $sData['logged'] = true;
    $dData['wcategory'] = $chk[2];
    $dData['wsubcateg'] = $chk[3];
    $dData['orderby'] = $chk[4];
    $sData['section'] = $chk[5];
  }
}


/* ***** Accept Incoming Actions ****************************** */
switch ($sData['action']) {

  /* ***** Login ********************************************** */
  case "Login":
    if ($_POST['login'] == $admin && $_POST['password'] == $password) {
      setcookie("orca_ctrl", base64_encode(serialize(array($_POST['login'], $_POST['password'] , "", "", " ORDER BY `QID`", "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
      $sData['logged'] = true;
      $dData['wcategory'] = "";
      $dData['wsubcateg'] = "";
      $sData['section'] = "Edit";
    }
    break;

  /* ***** Logout ********************************************* */
  case "Logout":
    if (!$sData['logged']) break;

    setcookie("orca_ctrl", "", time() - 18600, $_SERVER['PHP_SELF']);
    $sData['logged'] = false;
    break;

  /* ***** Switch to Editing Mode ***************************** */
  case "SectionEdit":
    if (!$sData['logged']) break;

    setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password , $dData['wcategory'], $dData['wsubcateg'], " ORDER BY `QID`", "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
    $sData['section'] = "Edit";
    break;

  /* ***** Switch to Upload Mode ****************************** */
  case "SectionUpload":
    if (!$sData['logged']) break;

    setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password , $dData['wcategory'], $dData['wsubcateg'], " ORDER BY `QID`", "Upload"))), time() + 18600, $_SERVER['PHP_SELF']);
    $sData['section'] = "Upload";
    break;

  /* ***** Change Question List Order ************************* */
  case "OrderList":
    if (!$sData['logged']) break;

    switch ($_GET['order']) {
      case "category":
        $dData['orderby'] = " ORDER BY `category`, `subcategory`";
        break;

      case "date": case "visited":
        $dData['orderby'] = " ORDER BY `{$_GET['order']}` DESC";
        break;

      case "QID": case "question":
        $dData['orderby'] = " ORDER BY `{$_GET['order']}`";

    }
    setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $dData['wcategory'], $dData['wsubcateg'], $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
    break;

  /* ***** Delete an Uploaded File **************************** */
  case "DeleteFile":
    if (!$sData['logged']) break;

    if (isset($_GET['file'])) @unlink($fData['resource']."/".base64_decode($_GET['file']));
    break;

  /* ***** Upload a File ************************************** */
  case "UploadFile":
    if (!$sData['logged']) break;

    if (isset($_FILES['file'])) {
      if (!in_array(getExt($_FILES['file']['name']), $fData['fileext'])) {
        $eData[] = sprintf($lang['err1'], getExt($_FILES['file']['name']));

      } else if ($_FILES['file']['size'] > $fData['filemax']) {
        $eData[] = sprintf($lang['err2'], $fData['filemax'], round(($fData['filemax'] / 1024), 2));

      } else if ($_FILES['file']['tmp_name'] != 'none' && $_FILES['file']['tmp_name'] != '') {
        if (file_exists($fData['resource']."/".$_FILES['file']['name'])) {
          $eData[] = $lang['err3'];

        } else if (!copy($_FILES['file']['tmp_name'], $fData['resource']."/".$_FILES['file']['name']))
          $eData[] = $lang['err4'];

      }
    }
    break;

  /* ***** Add a Category ************************************* */
  case "AddCategory":
    if (!$sData['logged']) break;

    if ($_POST['category'] != "") {
      if (preg_match("/[^\w\s'.\-&#?\/]/", $_POST['category'])) {
        $eData[] = $lang['err5'];

      } else if (mysql_numrows(mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."';"))) {
        $eData[] = $lang['err6'];

      } else {
        $add = mysql_query("INSERT INTO `{$dData['tablec']}` values('".slashes($_POST['category'])."', '".serialize(array())."');");
      }
    }
    break;

  /* ***** Add a Subcategory ********************************** */
  case "AddSubcategory":
    if (!$sData['logged']) break;

    if ($_POST['subcategory'] != "" && $dData['wcategory']) {
      if (preg_match("/[^\w\s'.\-&#\/]/", $_POST['subcategory'])) {
        $eData[] = $lang['err7'];
      } else {

        $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($dData['wcategory'])."';");
        $wrksub = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));
        if (in_array($_POST['subcategory'], $wrksub)) {
          $eData[] = $lang['err8'];
        } else {

          $wrksub[] = $_POST['subcategory'];
          for ($i = 0; $i < count($wrksub); $i++) if (!$wrksub[$i]) unset($wrksub[$i]);
          sort($wrksub);

          $add = mysql_query("UPDATE `{$dData['tablec']}` SET `subcategory`='".serialize($wrksub)."' WHERE `category`='".slashes($dData['wcategory'])."';");
        }
      }
    }
    break;

  /* ***** Add a Question ************************************* */
  case "AddQuestion":
    if (!$sData['logged']) break;

    $addConfirm = false;

    if (isset($_POST['question'])) {
      $grabQID = mysql_query("SELECT * FROM `{$dData['tableq']}` ORDER BY `QID`;");
      for ($QID = 0; $QID < mysql_numrows($grabQID); $QID++) if ($QID != mysql_result($grabQID, $QID, "QID")) break;

      $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."';");
      $wrksub = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));

      if ($_POST['subcategory'] == "") {
        if ($_POST['newsubcategory'] == "") {
          $eData[] = $lang['err9'];

        } else if (preg_match("/[^\w\s'.\-&#\/]/", $_POST['newsubcategory'])) {
          $eData[] = $lang['err7'];

        } else if (in_array($_POST['newsubcategory'], $wrksub))
          $eData[] = $lang['err8'];

        $_POST['subcategory'] = $_POST['newsubcategory'];
        $wrksub[] = $_POST['subcategory'];
      }

      if (!trim($_POST['question'])) $eData[] = $lang['errd'];
      if (!trim($_POST['answer'])) $eData[] = $lang['erre'];

      if (!isset($eData)) {
        for ($i = 0; $i < count($wrksub); $i++) if (!$wrksub[$i]) unset($wrksub[$i]);
        sort($wrksub);

        $dupeChk = mysql_query("SELECT * FROM `{$dData['tableq']}` WHERE `category`='".slashes($_POST['category'])."' AND `subcategory`='".slashes($_POST['subcategory'])."' AND `question`='".slashes($_POST['question'])."';");
        if (mysql_numrows($dupeChk)) {
          $eData[] = $lang['erra'];

        } else {
          $insOnline = (isset($_POST['online']) && $_POST['online'] == "true") ? "Yes" : "No";

          $edit = mysql_query("UPDATE `{$dData['tablec']}` SET `subcategory`='".serialize($wrksub)."' WHERE `category`='".slashes($_POST['category'])."';");
          $addQ = mysql_query("INSERT INTO `{$dData['tableq']}` VALUES ('".slashes($_POST['category'])."', '".slashes($_POST['subcategory'])."', '$QID', '".time()."', '".slashes($_POST['question'])."', '".slashes($_POST['answer'])."', '".slashes($_POST['keywords'])."', '{$insOnline}', '0');");

          setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $_POST['category'], $_POST['subcategory'], $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
          $dData['wcategory'] = $_POST['category'];
          $dData['wsubcateg'] = $_POST['subcategory'];

          $addConfirm = true;
        }
      }
    }
    break;

  /* ***** Select Working Category **************************** */
  case "WorkingCategory":
    if (!$sData['logged']) break;

    if ($_POST['category'] != "") {
      $work = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."';");
      if (!mysql_numrows($work)) $_POST['category'] = "";
      setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $_POST['category'], "", $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
      $dData['wcategory'] = $_POST['category'];
      $dData['wsubcateg'] = "";
    }
    break;

  /* ***** Select Working Subcategory ************************* */
  case "WorkingSubcategory":
    if (!$sData['logged']) break;

    if ($_POST['subcategory'] != "") {
      $work = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($dData['wcategory'])."';");
      $wor_ = unserialize(stripslashes(mysql_result($work, 0, "subcategory")));

      if (!in_array($_POST['subcategory'], $wor_)) $_POST['subcategory'] = "";
      setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $dData['wcategory'], $_POST['subcategory'], $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
      $dData['wsubcateg'] = $_POST['subcategory'];
    }
    break;

  /* ***** Rename a Category ********************************** */
  case "EditCategory":
    if (!$sData['logged']) break;

    if ($_POST['category'] != "" && $_POST['newcategory'] != "") {
      if (preg_match("/[^\w\s'.\-&#?\/]/", $_POST['newcategory'])) {
        $eData[] = $lang['err5'];

      } else if (mysql_numrows(mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['newcategory'])."';"))) {
        $eData[] = $lang['err6'];

      } else {
        $edit = mysql_query("UPDATE `{$dData['tablec']}` SET `category`='".slashes($_POST['newcategory'])."' WHERE `category`='".slashes($_POST['category'])."';");
        $edit = mysql_query("UPDATE `{$dData['tableq']}` SET `category`='".slashes($_POST['newcategory'])."' WHERE `category`='".slashes($_POST['category'])."';");

        if ($_POST['category'] == $dData['wcategory']) {
          setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $_POST['category'], "", $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
          $dData['wcategory'] = $_POST['newcategory'];
          $dData['wsubcateg'] = "";
        }
      }
    }
    break;

  /* ***** Rename a Subcategory ******************************* */
  case "EditSubcategory":
    if (!$sData['logged']) break;

    if ($_POST['subcategory'] != "" && $_POST['newsubcategory'] != "" && $dData['wcategory']) {
      if (preg_match("/[^\w\s'.\-&#?\/]/", $_POST['newsubcategory'])) {
        $eData[] = $lang['err7'];

      } else {
        $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($dData['wcategory'])."';");
        $wrksub = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));

        if (in_array($_POST['newsubcategory'], $wrksub)) {
          $eData[] = $lang['err8'];

        } else {
          $wrksub[array_search($_POST['subcategory'], $wrksub)] = $_POST['newsubcategory'];
          sort($wrksub);
          $edit = mysql_query("UPDATE `{$dData['tablec']}` SET `subcategory`='".serialize($wrksub)."' WHERE `category`='".slashes($dData['wcategory'])."';");
          $edit = mysql_query("UPDATE `{$dData['tableq']}` SET `subcategory`='".slashes($_POST['newsubcategory'])."' WHERE `category`='".slashes($dData['wcategory'])."' AND `subcategory`='".slashes($_POST['subcategory'])."';");

          if ($_POST['subcategory'] == $dData['wsubcateg']) {
            setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $dData['wcategory'], $_POST['newsubcategory'], $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
            $dData['wsubcateg'] = $_POST['newsubcategory'];
          }
        }
      }
    }
    break;

  /* ***** Edit a Question ************************************ */
  case "EditQuestion":
    if (!$sData['logged']) break;
    if (!preg_match("/\d/", $_POST['QID'])) {
      $sData['action'] = "";
      break;
    }

    $editConfirm = false;
    $thisQ = mysql_query("SELECT * FROM `{$dData['tableq']}` WHERE `QID`='{$_POST['QID']}' LIMIT 1;");
    if (mysql_numrows($thisQ) == 0) {
      $eData[] = sprintf($lang['errb'], $_POST['QID']);
      unset($thisQ);
      $sData['action'] = "";

    } else if (isset($_POST['question'])) {

      if ($_POST['category'] != mysql_result($thisQ, 0, "category") && !isset($_POST['moving'])) {
        $editMoving = true;

      } else {
        $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."';");
        $wrksub = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));

        if ($_POST['subcategory'] == "") {
          if ($_POST['newsubcategory'] == "") {
            $eData[] = $lang['errc'];

          } else if (preg_match("/[^\w\s'.\-&#\/]/", $_POST['newsubcategory'])) {
            $eData[] = $lang['err7'];

          } else if (in_array($_POST['newsubcategory'], $wrksub))
            $eData[] = $lang['err8'];

          $_POST['subcategory'] = $_POST['newsubcategory'];
          $wrksub[] = $_POST['subcategory'];
        }

        if (!trim($_POST['question'])) $eData[] = $lang['errd'];
        if (!trim($_POST['answer'])) $eData[] = $lang['erre'];

        if (!isset($eData)) {
          for ($i = 0; $i < count($wrksub); $i++) if (!$wrksub[$i]) unset($wrksub[$i]);
          sort($wrksub);

          $insOnline = (isset($_POST['online']) && $_POST['online'] == "true") ? "Yes" : "No";

          $edit = mysql_query("UPDATE `{$dData['tablec']}` SET `subcategory`='".serialize($wrksub)."' WHERE `category`='".slashes($_POST['category'])."';");
          $edit = mysql_query("UPDATE `{$dData['tableq']}` SET `category`='".slashes($_POST['category'])."', `subcategory`='".slashes($_POST['subcategory'])."', `date`='".time()."', `question`='".slashes($_POST['question'])."', `answer`='".slashes($_POST['answer'])."', `keywords`='".slashes($_POST['keywords'])."', `online`='{$insOnline}' WHERE `QID`='{$_POST['QID']}';");

          setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $_POST['category'], $_POST['subcategory'], $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
          $dData['wcategory'] = $_POST['category'];
          $dData['wsubcateg'] = $_POST['subcategory'];

          $editConfirm = true;
        }
      }
    }
    break;

  /* ***** Delete a Category ********************************** */
  case "DeleteCategory":
    if (!$sData['logged']) break;

    if ($_POST['category'] != "") {
      if (isset($_POST['confirm']) && count($_POST['confirm']) == 3) {
        $delete = mysql_query("DELETE FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."' LIMIT 1;");
        $delete = mysql_query("DELETE FROM `{$dData['tableq']}` WHERE `category`='".slashes($_POST['category'])."';");

        if ($_POST['category'] == $dData['wcategory']) {
          setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, "", "", $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
          $dData['wcategory'] = "";
        }
      }
    }
    break;

  /* ***** Delete a Subcategory ******************************* */
  case "DeleteSubcategory":
    if (!$sData['logged']) break;

    if ($_POST['subcategory'] != "") {
      if (isset($_POST['confirm']) && count($_POST['confirm']) == 3) {
        $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($dData['wcategory'])."';");
        $wrksub = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));
        unset($wrksub[array_search($_POST['subcategory'], $wrksub)]);
        sort($wrksub);

        $delete = mysql_query("UPDATE `{$dData['tablec']}` SET `subcategory`='".serialize($wrksub)."' WHERE `category`='".slashes($dData['wcategory'])."';");
        $delete = mysql_query("DELETE FROM `{$dData['tableq']}` WHERE `subcategory`='".slashes($_POST['subcategory'])."';");

        if ($_POST['subcategory'] == $dData['wsubcateg']) {
          setcookie("orca_ctrl", base64_encode(serialize(array($admin, $password, $dData['wcategory'], "", $dData['orderby'], "Edit"))), time() + 18600, $_SERVER['PHP_SELF']);
          $dData['wsubcateg'] = "";
        }
      }
    }
    break;

  /* ***** Delete a Question ********************************** */
  case "DeleteQuestion":
    if (!$sData['logged']) break;

    if ($_POST['QID'] != "" && preg_match("/\d+/", $_POST['QID'])) {
      if (isset($_POST['confirm']) && count($_POST['confirm']) == 3) {
        $findQ = mysql_query("SELECT * FROM `{$dData['tableq']}` WHERE `QID`='{$_POST['QID']}';");

        if (mysql_numrows($findQ) == 0) {
          $eData[] = sprintf($lang['errb'], $_POST['QID']);

        } else {
          $delete = mysql_query("DELETE FROM `{$dData['tableq']}` WHERE `QID`='{$_POST['QID']}';");
        }
      }
    }
    break;

  default:
}

/* ***** Continue Setup *************************************** */
if ($dData['wcategory']) {
  $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($dData['wcategory'])."';");
  $dData['wrkSubs'] = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));

} else if (isset($thisQ)) {
  $wrk = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes(mysql_result($thisQ, 0, "category"))."';");
  $dData['wrkSubs'] = unserialize(stripslashes(mysql_result($wrk, 0, "subcategory")));
}

$dData['questions'] = mysql_query("SELECT * FROM `{$dData['tableq']}`;");

if ($sData['logged']) {
  $buildQry = (($dData['wcategory']) ? " WHERE `category`='".slashes($dData['wcategory'])."'" : "").(($dData['wsubcateg']) ? " AND `subcategory`='".slashes($dData['wsubcateg'])."'" : "");
  $dData['qlist'] = mysql_query("SELECT * FROM `{$dData['tableq']}`{$buildQry}{$dData['orderby']};");
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title><?php echo $dData['pagetitle'].$lang['misc2']; ?></title>
  <link rel="stylesheet" type="text/css" href="<?php echo $dData['stylesheet']; ?>" />
  <meta http-equiv="Content-type" content="text/html; charset=<?php echo ($pageEncoding == 1) ? "UTF-8" : $lang['charset']; ?>;" />

  <script type="text/javascript"><!--

function okbc_hideDiv() {
  if (document.getElementById('okbc_tableCat')) document.getElementById('okbc_tableCat').style.display = "none";
  if (document.getElementById('okbc_tableSub')) document.getElementById('okbc_tableSub').style.display = "none";
}

function okbc_showDiv(divName) {
  if (document.getElementById(divName))
    document.getElementById(divName).style.display = (document.getElementById(divName).style.display == "none") ? "block" : "none";
}

function okbc_tabs() {
  document.write("<div class=\"okbc_row\">");
  <?php $tabs = array();
  $opendir = @opendir($fData['resource']);
  while ($readdir = @readdir($opendir)) if ($readdir != "." && $readdir != ".." && $readdir != "index.html") $tabs[] = $readdir;
  if (count($tabs)) {
    asort($tabs); ?> 
    document.write("  <select size=\"1\" id=\"okbc_filelist\">");
    document.write("    <option value=\"\"><?php echo $lang['mis_r']; ?></option>");
    <?php while (list($key, $value) = each($tabs)) { ?>
      document.write("    <option value=\"<?php echo $value; ?>\"><?php echo $value; ?></option>");
    <?php } ?> 
    document.write("  </select>");
  <?php } ?> 
  document.write("  <input type=\"button\" value=\"<?php echo $lang['mis_s']; ?>\" onclick=\"document.getElementById('answer').value+='<a href=&quot;<?php echo $fData['resource']; ?>/<?php echo (count($tabs)) ? "' + document.getElementById('okbc_filelist').value + '" : "FILENAME"; ?>&quot;>Link</a>';\" />");
  document.write("  <input type=\"button\" value=\"<?php echo $lang['mis_t']; ?>\" onclick=\"document.getElementById('answer').value+='<img src=&quot;<?php echo $fData['resource']; ?>/<?php echo (count($tabs)) ? "' + document.getElementById('okbc_filelist').value + '" : "FILENAME"; ?>&quot; alt=&quot;&quot; />';\" />");
  document.write("</div>");
}

  // --></script>
</head>
<body id="okbc_body" onload="okbc_hideDiv();">

<?php if (!$sData['logged']) { ?> 
  <div class="okbc_box">
    <h3><?php echo $lang['misc3']; ?></h3>
    <div class="okbc_block">
      <div class="okbc_row">
        <span><?php echo $lang['misc4']; ?></span>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="text" name="login" size="10" />
          <input type="password" name="password" size="10" />
          <input type="hidden" name="action" value="Login" />
          <input type="submit" value="<?php echo $lang['misc5']; ?>" />
        </form>
      </div>
    </div>
  </div>

<?php } else { ?> 
  <div class="okbc_box">
    <h3><?php echo $lang['misc3']; ?></h3>
    <div class="okbc_block">
      <div class="okbc_row">
        <span><?php echo $lang['misc6']; ?></span>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="action" value="Logout" />
          <input type="submit" value="<?php echo $lang['misc8']; ?>" />
        </form>
      </div>    
      <?php if ($sData['section'] == "Upload") { ?> 
        <div class="okbc_row">
          <span><?php echo $lang['misc9']; ?></span>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="action" value="SectionEdit" />
            <input type="submit" value="<?php echo $lang['misca']; ?>" />
          </form>
        </div>
      <?php } else { ?> 
        <div class="okbc_row">
          <span><?php echo $lang['miscb']; ?></span>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="action" value="SectionUpload" />
            <input type="submit" value="<?php echo $lang['miscc']; ?>" />
          </form>
        </div>
      <?php } ?> 
    </div>
  </div>


  <?php if (isset($eData)) { ?> 
    <div class="okbc_box">
      <h3 class="okbc_warn"><?php echo $lang['miscd']; ?></h3>
      <div class="okbc_block">
        <div class="okbc_row">
          <?php echo $lang['misce']; ?> 
          <ul>
            <?php foreach ($eData as $err_inst) { ?>
              <li><?php echo $err_inst; ?></li>
            <?php } ?>
          </ul>
          <a href="<?php echo $_SERVER['PHP_SELF']; ?>" title="<?php echo $lang['miscf']; ?>"><?php echo $lang['miscg']; ?></a>
        </div>
      </div>
    </div>
  <?php } ?> 


  <?php if ($sData['section'] == "Upload") {
    if (!eregi("777", decoct(@fileperms($fData['resource'])))) { ?> 
      <h4 class="okbc_warn"><?php printf($lang['misch'], $fData['resource']); ?></h4>
      »<a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $lang['misci']; ?></a>

    <?php } else { ?> 
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="okbc_form">
        <table cellpadding="3" cellspacing="0" border="0" id="okbf_table">
          <tr>
            <th colspan="2">
              <strong>File Upload Manager</strong> <span>v1.2</span>
              <a href="http://www.mtnpeak.net">&copy; thepeak</a>
            </th>
          </tr>
          <tr>
            <td class="okbf_table_td1"><?php echo $lang['miscj']; ?></td>
            <td class="okbf_table_td3"><input type="file" name="file" size="30" /></td>
          </tr>
          <tr>
            <td class="okbf_table_td1"><?php echo $lang['misck']; ?></td>
            <td class="okbf_table_td3"><?php echo $fData['resource']."/"; ?></td>
          </tr>
          <tbody>
            <tr style="font-size:85%;">
              <td class="okbf_table_td1"><?php echo $lang['miscl']; ?></td>
              <td class="okbf_table_td3"><?php while (list($key, $value) = each($fData['fileext'])) echo $value.(($key < count($fData['fileext']) - 1) ? ", ": ""); ?></td>
            </tr>
            <tr style="font-size:85%;">
              <td class="okbf_table_td1"><?php echo $lang['miscm']; ?></td>
              <td class="okbf_table_td3"><?php echo $fData['filemax']; ?> bytes (<?php echo round(($fData['filemax'] / 1024), 2); ?> kB, <?php echo round(($fData['filemax'] / 1024000), 3); ?> MB)</td>
            </tr>
            <tr style="font-size:85%;">
              <td colspan="2" class="okbf_table_td2">
                <input type="hidden" name="action" value="UploadFile" />
                <input type="submit" value="<?php echo $lang['miscn']; ?>" />
                <input type="reset" value="<?php echo $lang['misco']; ?>" />
              </td>
            </tr>
          </tbody>
        </table>
      </form>

      <?php $opendir = @opendir($fData['resource']);
      while ($readdir = @readdir($opendir)) if ($readdir != "." && $readdir != ".." && $readdir != "index.html") $sort[] = $readdir;

      if (isset($sort) && count($sort) >= 1) { 
        asort($sort); ?> 
        <table cellspacing="0" cellpadding="1" border="0" id="okbf_files">
          <tr>
            <th id="okbf_col1"><?php echo $lang['miscp']; ?></th>
            <th><?php echo $lang['miscq']; ?></th>
            <th><?php echo $lang['miscr']; ?></th>
            <th><?php echo $lang['miscs']; ?></th>
          </tr>
          <?php $bkg = 0;
          while (list($key, $value) = each($sort)) { ?> 
            <tr<?php echo (($bkg++ % 2 == 0) ? " class=\"okbf_line\"" : ""); ?>>
              <td class="okbf_table_td1"><a href="<?php echo "{$fData['resource']}/$value"; ?>" onclick="window.open('<?php echo "{$fData['resource']}/$value"; ?>', 'fum_viewfile', 'resizable=yes,width=640,height=480,scrollbars=yes,status=no'); return false;"><?php echo $value; ?></a></td>
              <td class="okbf_table_td2"><?php echo strtoupper(getExt($value)); ?></td>
              <td class="okbf_table_td2">
                <?php $file_size = filesize($fData['resource']."/$value");
                if ($file_size >= 1073741824) {
                  echo number_format(($file_size / 1073741824), 2)." GB";
                } else if ($file_size >= 1048576) {
                  echo number_format(($file_size / 1048576), 2)." MB";
                } else if ($file_size >= 1024) {
                  echo number_format(($file_size / 1024), 2)." kB";
                } else if ($file_size >= 0) {
                  echo $file_size . " bytes";
                } else echo "0 bytes"; ?> 
              </td>
              <td class="okbf_table_td2">
                <a href="?action=DeleteFile&amp;file=<?php echo base64_encode($value); ?>" title="<?php echo $lang['misct']; ?>" onclick="cf = confirm('<?php echo addcslashes(unhtmlentities($lang['miscu']), "\0..\37!@\177..\377"); ?>'); if (cf) window.location='?action=DeleteFile&amp;file=<?php echo base64_encode($value); ?>'; return false;"><?php echo $lang['miscs']; ?></a>
              </td>
            </tr>
          <?php } ?> 
        </table>
      <?php } ?> 
      <div id="okbf_refresh"><a href="<?php echo $_SERVER['PHP_SELF']; ?>"><?php echo $lang['miscv']; ?></a></div>
    <?php }


  } else {
    $dData['categories'] = mysql_query("SELECT * FROM `{$dData['tablec']}` ORDER BY `category`;"); ?> 
    <div class="okbc_box">
      <h3 onclick="okbc_showDiv('okbc_tableCat');" class="okbc_on">
        <?php echo $lang['miscw'];
        if ($dData['wcategory']) echo ": <strong>".htmlspecialchars($dData['wcategory'])."</strong>"; ?>
      </h3>

      <div class="okbc_block" id="okbc_tableCat">
        <div class="okbc_row">
          <span><?php echo $lang['miscx']; ?></span>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="category" />
            <input type="hidden" name="action" value="AddCategory" />
            <input type="submit" value="<?php echo $lang['miscy']; ?>" />
          </form>
        </div>
        <?php if (mysql_numrows($dData['categories'])) { ?> 
          <div class="okbc_row">
            <span><strong><?php echo $lang['miscz']; ?></strong></span>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <select name="category" size="1">
                <option value=""><?php echo $lang['mis_1']; ?></option>
                <?php if ($dData['wcategory']) echo "<option value=\"None\">{$lang['mis_2']}</option>\n";
                for ($i = 0; $i < mysql_numrows($dData['categories']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?>"><?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?></option>
                <?php } ?> 
              </select>
              <input type="hidden" name="action" value="WorkingCategory" />
              <input type="submit" value="<?php echo $lang['mis_3']; ?>" />
            </form>
          </div>
          <div class="okbc_row">
            <span><?php echo $lang['mis_4']; ?></span>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <select name="category" size="1">
                <option value=""><?php echo $lang['mis_1']; ?></option>
                <?php for ($i = 0; $i < mysql_numrows($dData['categories']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?>"><?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?></option>
                <?php } ?>
              </select>
              <input type="text" name="newcategory" value="<?php echo $lang['mis_5']; ?>" onfocus="if(this.value='<?php echo $lang['mis_5']; ?>')this.value='';" />
              <input type="hidden" name="action" value="EditCategory" />
              <input type="submit" value="<?php echo $lang['mis_6']; ?>" />
            </form>
          </div>
          <div class="okbc_row">
            <span><?php echo $lang['mis_7']; ?></span>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
              <select name="category" size="1">
                <option value=""><?php echo $lang['mis_1']; ?></option>
                <?php for ($i = 0; $i < mysql_numrows($dData['categories']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?>"><?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?></option>
                <?php } ?>
              </select>
              <input type="checkbox" name="confirm[]" value="true" />
              <input type="checkbox" name="confirm[]" value="true" />
              <input type="checkbox" name="confirm[]" value="true" />
              <input type="hidden" name="action" value="DeleteCategory" />
              <input type="submit" value="<?php echo $lang['mis_8']; ?>" />
            </form>
          </div>
        <?php } ?>
      </div>
    </div>


    <?php if (mysql_numrows($dData['categories'])) { ?>
      <div class="okbc_box">
        <h3 onclick="okbc_showDiv('okbc_tableSub');"<?php if ($dData['wcategory']) echo " class=\"okbc_on\""; ?>>
          <?php echo $lang['mis_9'];
          if ($dData['wcategory'] && $dData['wsubcateg']) echo ": <strong>".htmlspecialchars($dData['wsubcateg'])."</strong>"; ?> 
        </h3>

        <div class="okbc_block" id="okbc_tableSub">
          <?php if ($dData['wcategory']) { ?> 
            <div class="okbc_row">
              <span><?php echo $lang['mis_a']; ?></span>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="subcategory" />
                <input type="hidden" name="action" value="AddSubcategory" />
                <input type="submit" value="<?php echo $lang['miscy']; ?>" />
              </form>
            </div>
            <?php if (count($dData['wrkSubs'])) { ?> 
              <div class="okbc_row">
                <span><strong><?php echo $lang['mis_b']; ?></strong></span>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <select name="subcategory" size="1">
                    <option value=""><?php echo $lang['mis_c']; ?></option>
                    <?php if ($dData['wsubcateg']) echo "<option value=\"None\">{$lang['mis_2']}</option>\n";
                    for ($i = 0; $i < count($dData['wrkSubs']); $i++) { ?>
                      <option value="<?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?>"><?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?></option>
                    <?php } ?>
                  </select>
                  <input type="hidden" name="action" value="WorkingSubcategory" />
                  <input type="submit" value="<?php echo $lang['mis_3']; ?>" />
                </form>
              </div>
              <div class="okbc_row">
                <span><?php echo $lang['mis_d']; ?></span>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <select name="subcategory" size="1">
                    <option value=""><?php echo $lang['mis_c']; ?></option>
                    <?php for ($i = 0; $i < count($dData['wrkSubs']); $i++) { ?>
                      <option value="<?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?>"><?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?></option>
                    <?php } ?>
                  </select>
                  <input type="text" name="newsubcategory" value="<?php echo $lang['mis_5']; ?>" onfocus="if(this.value='<?php echo $lang['mis_5']; ?>')this.value='';" />
                  <input type="hidden" name="action" value="EditSubcategory" />
                  <input type="submit" value="<?php echo $lang['mis_6']; ?>" />
                </form>
              </div>
              <div class="okbc_row">
                <span><?php echo $lang['mis_e']; ?></span>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <select name="subcategory" size="1">
                    <option value=""><?php echo $lang['mis_c']; ?></option>
                    <?php for ($i = 0; $i < count($dData['wrkSubs']); $i++) { ?>
                      <option value="<?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?>"><?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?></option>
                    <?php } ?>
                  </select>
                  <input type="checkbox" name="confirm[]" value="true" />
                  <input type="checkbox" name="confirm[]" value="true" />
                  <input type="checkbox" name="confirm[]" value="true" />
                  <input type="hidden" name="action" value="DeleteSubcategory" />
                  <input type="submit" value="<?php echo $lang['mis_8']; ?>" />
                </form>
              </div>
            <?php }
          } ?>
        </div>
      </div>
    <?php } ?>


    <div class="okbc_box">
      <h3>
        <?php echo $lang['mis_f']; ?> 
      </h3>

      <?php if (isset($editMoving) && $editMoving == true) {
        $mov = mysql_query("SELECT * FROM `{$dData['tablec']}` WHERE `category`='".slashes($_POST['category'])."';");
        $dData['movSubs'] = unserialize(stripslashes(mysql_result($mov, 0, "subcategory"))); ?> 
        <div class="okbc_block">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="okbc_row">
              <strong><?php echo $lang['mis_g']; ?></strong><br />
              <?php echo $lang['mis_h']; ?> 
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_n']; ?></span>
              <strong><?php echo htmlspecialchars(stripslashes($_POST['category'])); ?></strong>
              <input type="hidden" name="category" value="<?php echo htmlspecialchars(stripslashes($_POST['category'])); ?>" />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_i']; ?></span>
              <select name="subcategory" size="1">
                <option value=""><?php echo $lang['mis_j']; ?> &gt;&gt;</option>
                <?php for ($i = 0; $i < count($dData['movSubs']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars($dData['movSubs'][$i]); ?>"><?php echo htmlspecialchars($dData['movSubs'][$i]); ?></option>
                <?php } ?>
              </select>
              <input type="text" name="newsubcategory" />
            </div>
            <div class="okbc_row">
              <span><a href="<?php echo $_SERVER['PHP_SELF']; ?>" title="<?php echo $lang['mis_k']; ?>"><?php echo $lang['mis_l']; ?></a></span>
              <input type="hidden" name="QID" value="<?php echo $_POST['QID']; ?>" />
              <?php if (isset($_POST['online']) && $_POST['online'] == "true") { ?><input type="hidden" name="online" value="true" /><?php } ?> 
              <input type="hidden" name="question" value="<?php echo htmlspecialchars(stripslashes($_POST['question'])); ?>" />
              <input type="hidden" name="answer" value="<?php echo htmlspecialchars(stripslashes($_POST['answer'])); ?>" />
              <input type="hidden" name="keywords" value="<?php echo htmlspecialchars(stripslashes($_POST['keywords'])); ?>" />
              <input type="hidden" name="moving" value="true" />
              <input type="hidden" name="action" value="EditQuestion" />
              <input type="submit" value="<?php echo $lang['mis_m']; ?>" />
            </div>
          </form>
        </div>

      <?php } else if ($sData['action'] == "AddQuestion" && $addConfirm == false) { ?> 
        <div class="okbc_block">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="okbc_row">
              <span><?php echo $lang['mis_n']; ?></span>
              <strong><?php echo $dData['wcategory']; ?></strong>
              <input type="hidden" name="category" value="<?php echo $dData['wcategory']; ?>" />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_i']; ?></span>
              <select name="subcategory" size="1">
                <option value=""><?php echo $lang['mis_j']; ?> &gt;&gt;</option>
                <?php for ($i = 0; $i < count($dData['wrkSubs']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?>"<?php if ($dData['wrkSubs'][$i] == $dData['wsubcateg']) echo " selected=\"selected\""; ?>><?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?></option>
                <?php } ?>
              </select>
              <input type="text" name="newsubcategory" <?php if (isset($_POST['newsubcategory'])) echo "value=\"".htmlspecialchars($_POST['newsubcategory'])."\" "; ?>/>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_o']; ?></span>
              <input type="checkbox" name="online" value="true" <?php echo ($_POST['action'] == "AddQuestion" && !isset($_POST['online'])) ? "" : "checked=\"checked\" "; ?>/>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_p']; ?></span>
              <input type="text" name="question" size="60" <?php if (isset($_POST['question'])) echo "value=\"".htmlspecialchars($_POST['question'])."\" "; ?>/>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_q']; ?></span>
              <textarea name="answer" id="answer" cols="50" rows="8"><?php if (isset($_POST['answer'])) echo htmlspecialchars($_POST['answer']); ?></textarea>
            </div>
            <script type="text/javascript"><!--
              okbc_tabs();
            // --></script>
            <div class="okbc_row">
              <span><?php echo $lang['mis_u']; ?></span>
              <textarea name="keywords" cols="50" rows="3"><?php if (isset($_POST['keywords'])) echo htmlspecialchars($_POST['keywords']); ?></textarea>
            </div>
            <div class="okbc_row">
              <span><a href="<?php echo $_SERVER['PHP_SELF']; ?>" title="<?php echo $lang['mis_v']; ?>"><?php echo $lang['mis_l']; ?></a></span>
              <input type="hidden" name="action" value="AddQuestion" />
              <input type="submit" value="<?php echo $lang['miscy']; ?>" />
            </div>
          </form>
        </div>

      <?php } else if ($sData['action'] == "EditQuestion" && $editConfirm == false) { ?>
        <div class="okbc_block">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="okbc_row">
              <span><?php echo $lang['mis_w']; ?></span>
              <strong><?php echo $_POST['QID']; ?></strong>
              <input type="hidden" name="QID" value="<?php echo $_POST['QID']; ?>" />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_x']; ?></span>
              <strong><?php echo dateStamp(mysql_result($thisQ, 0, "date")); ?></strong>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_y']; ?></span>
              <strong><?php echo mysql_result($thisQ, 0, "visited"); ?></strong>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_n']; ?></span>
              <select name="category" size="1">
                <?php for ($i = 0; $i < mysql_numrows($dData['categories']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?>"<?php if (mysql_result($dData['categories'], $i, "category") == mysql_result($thisQ, 0, "category")) echo "selected=\"selected\""; ?>><?php echo htmlspecialchars(mysql_result($dData['categories'], $i, "category")); ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_i']; ?></span>
              <select name="subcategory" size="1">
                <option value=""><?php echo $lang['mis_j']; ?> &gt;&gt;</option>
                <?php for ($i = 0; $i < count($dData['wrkSubs']); $i++) { ?>
                  <option value="<?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?>"<?php if ($dData['wrkSubs'][$i] == mysql_result($thisQ, 0, "subcategory")) echo "selected=\"selected\""; ?>><?php echo htmlspecialchars($dData['wrkSubs'][$i]); ?></option>
                <?php } ?>
              </select>
              <input type="text" name="newsubcategory" />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_o']; ?></span>
              <input type="checkbox" name="online" value="true"<?php if (mysql_result($thisQ, 0, "online") == "Yes") echo " checked=\"checked\""; ?> />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_p']; ?></span>
              <input type="text" name="question" size="60" value="<?php echo htmlspecialchars(mysql_result($thisQ, 0, "question")); ?>" />
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mis_q']; ?></span>
              <textarea name="answer" id="answer" cols="50" rows="8"><?php echo htmlspecialchars(mysql_result($thisQ, 0, "answer")); ?></textarea>
            </div>
            <script type="text/javascript"><!--
              okbc_tabs();
            // --></script>
            <div class="okbc_row">
              <span><?php echo $lang['mis_u']; ?></span>
              <textarea name="keywords" cols="50" rows="3"><?php echo htmlspecialchars(mysql_result($thisQ, 0, "keywords")); ?></textarea>
            </div>
            <div class="okbc_row">
              <span><a href="<?php echo $_SERVER['PHP_SELF']; ?>" title="<?php echo $lang['mis_k']; ?>"><?php echo $lang['mis_l']; ?></a></span>
              <input type="hidden" name="action" value="EditQuestion" />
              <input type="submit" value="<?php echo $lang['mis_m']; ?>" />
            </div>
          </form>
        </div>

      <?php } else { ?> 
        <div class="okbc_block">
          <?php if ($dData['wcategory']) { ?> 
            <div class="okbc_row">
              <?php if (count($dData['wrkSubs']) && $dData['wsubcateg']) { ?> 
                <span><?php echo $lang['mis_z']; ?></span>
              <?php } else { ?> 
                <span><?php echo $lang['mi__1']; ?></span>
              <?php } ?>                 
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="action" value="AddQuestion" />
                <input type="submit" value="<?php echo $lang['miscy']; ?>" />
              </form>
            </div>

          <?php }

          if (mysql_numrows($dData['questions'])) { ?> 
            <div class="okbc_row">
              <span><?php echo $lang['mi__2']; ?></span>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="QID" size="5" />
                <input type="checkbox" name="confirm[]" value="true" />
                <input type="checkbox" name="confirm[]" value="true" />
                <input type="checkbox" name="confirm[]" value="true" />
                <input type="hidden" name="action" value="DeleteQuestion" />
                <input type="submit" value="<?php echo $lang['mis_8']; ?>" />
              </form>
            </div>
            <div class="okbc_row">
              <span><?php echo $lang['mi__3']; ?></span>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="QID" size="5" />
                <input type="hidden" name="action" value="EditQuestion" />
                <input type="submit" value="<?php echo $lang['mi__4']; ?>" />
              </form>
            </div>
          <?php } ?> 
        </div>
      <?php } ?> 
    </div>


    <?php if (@mysql_numrows($dData['qlist'])) { ?>
      <div class="okbc_box">
        <h3>
          <?php echo $lang['mi__5']; ?><br />
          <?php if ($dData['wcategory']) { ?>
            <?php echo $lang['mi__6']; ?> <strong><?php echo htmlspecialchars($dData['wcategory']); ?></strong>
            <?php if ($dData['wsubcateg']) { ?>
               &gt;&gt; <strong><?php echo htmlspecialchars($dData['wsubcateg']); ?></strong>
            <?php } ?>
          <?php } else { ?>
            <?php echo $lang['mi__7']; ?> 
          <?php } ?>
        </h3>

        <?php $listMax = (mysql_numrows($dData['qlist']) > $dData['qlistmax'] + $sData['start'] - 1) ? $dData['qlistmax'] + $sData['start'] - 1 : mysql_numrows($dData['qlist']); ?> 
        <div class="okbc_block">
          <table cellpadding="2" cellspacing="0" border="0" id="okbc_table">
            <tr>
              <th><?php echo orderLink("QID", $lang['mi__8']); ?></th>
              <th><?php echo orderLink("category", $lang['mis_n']); ?></th>
              <th><?php echo $lang['mis_i']; ?></th>
              <th><?php echo orderLink("question", $lang['mis_p']); ?></th>
              <th><?php echo orderLink("date", $lang['mis_x']); ?></th>
              <th>Online</th>
              <th><?php echo orderLink("visited", $lang['mis_y']); ?></th>
              <th><?php echo $lang['mi__4']; ?></th>
            </tr>
            <?php for ($i = $sData['start'] - 1; $i < $listMax; $i++) { ?> 
              <tr>
                <td class="okbc_td2"><?php echo mysql_result($dData['qlist'], $i, "QID"); ?></td>
                <td class="okbc_td1"><?php echo htmlspecialchars(mysql_result($dData['qlist'], $i, "category")); ?></td>
                <td class="okbc_td1"><?php echo htmlspecialchars(mysql_result($dData['qlist'], $i, "subcategory")); ?></td>
                <td class="okbc_td1"><?php echo htmlspecialchars(mysql_result($dData['qlist'], $i, "question")); ?></td>
                <td class="okbc_td2"><?php echo dateStamp(mysql_result($dData['qlist'], $i, "date")); ?></td>
                <td class="okbc_td2"><?php echo mysql_result($dData['qlist'], $i, "online"); ?></td>
                <td class="okbc_td2"><?php echo mysql_result($dData['qlist'], $i, "visited"); ?></td>
                <td class="okbc_td2">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="QID" value="<?php echo mysql_result($dData['qlist'], $i, "QID"); ?>" />
                    <input type="hidden" name="action" value="EditQuestion" />
                    <input type="submit" value="<?php echo $lang['mi__9']; ?>" />
                  </form>
                </td>
              </tr>
            <?php }

            if (mysql_numrows($dData['qlist']) > $dData['qlistmax']) { ?> 
              <tr>
                <td class="okbc_td1" colspan="3">
                  <?php if ($sData['start'] > 1) {
                    $listPrev = ($sData['start'] - $dData['qlistmax'] <= 1) ? "" : "?start=".($sData['start'] - $dData['qlistmax']); ?> 
                    <a href="<?php echo $_SERVER['PHP_SELF'].$listPrev; ?>" title="<?php echo $lang['mi__a']; ?>">&lt;&lt; <?php echo $lang['mi__b']; ?></a>
                  <?php } else echo "&nbsp;"; ?> 
                </td>
                <td class="okbc_td2">
                  <?php printf($lang['mi__c'], $sData['start'], $i); ?> 
                </td>
                <td class="okbc_td3" colspan="4">
                  <?php if ($i < mysql_numrows($dData['qlist'])) {
                    $listNext = "?start=".($sData['start'] + $dData['qlistmax']); ?> 
                    <a href="<?php echo $_SERVER['PHP_SELF'].$listNext; ?>" title="<?php echo $lang['mi__d']; ?>"><?php echo $lang['mi__e']; ?> &gt;&gt;</a>
                  <?php } else echo "&nbsp;"; ?> 
                </td>
              </tr>
            <?php } ?> 
          </table>
        </div>
      </div>

    <?php } else { ?>
      <div class="okbc_box">
        <h3>
          <?php if (mysql_numrows($dData['questions'])) {
            echo ($dData['wsubcateg']) ? $lang['mi__f'] : $lang['mi__g'];
          } else echo $lang['mi__h']; ?> 
        </h3>
      </div>
    <?php }
  }
} ?> 

</body>
</html>
