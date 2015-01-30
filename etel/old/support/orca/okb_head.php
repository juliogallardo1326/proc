<?php /* ***** Orca Knowledgebase - Head File ***************** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A small and efficient knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*************************************************************** */

/* ***** User Variables *************************************** */
$dData['hostname'] = "localhost";
$dData['username'] = "etel_root";
$dData['password'] = "WSD%780=";
$dData['database'] = "etel_dbsmain";
$dData['tablename'] = "cs_FAQ";

$dData['rlistmax'] = 20;
$dData['emailtitle'] = $_SESSION["gw_title"];
$dData['email'] = $_SESSION["gw_emails_customerservice"];
$dData['userask'] = true;
$dData['pagetitle'] = $_SESSION["gw_title"];


/* ***** Function List **************************************** */
function dateStamp($time) {
  global $pageEncoding, $lang;

  switch ($pageEncoding) {
    case 1: $timeStr = utf8_encode(strftime("%x", $time)); break;
    case 2: $timeStr = strftime("%x", $time); break;
    default: $timeStr = @htmlentities(strftime("%x", $time), ENT_COMPAT, $lang['charset']); break;
  }
  return $timeStr;
}

function slashes($data) {
  $data = str_replace(chr(13), "", $data);
  return addslashes($data);
}

function searchhold($lead) {
  return ($_GET['q']) ? (($lead) ? "?" : "&amp;")."q=".urlencode($_GET['q']) : "";
}

function www_nl2br($input) {
  do {
    $holder = $input;
    $input = preg_replace("/((\r\n|\r|\n){2}|^)([^<].*?)((\r\n|\r|\n){2}|$)/is", "$1<p>$3</p>$4", $input);
  } while ($holder != $input);
  return $input;
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


/* ***** MySQL Operations ************************************* */
$dData['tblquest'] = $dData['tablename']."q";
$dData['tblcateg'] = $dData['tablename']."c";

$db = mysql_connect($dData['hostname'], $dData['username'], $dData['password']) or dieLog("Could not connect to the MySQL server!");
mysql_select_db($dData['database'], $db) or dieLog("Could not connect to the database!");


/* ***** Prepare Workspace ************************************ */
$_SERVER['PHP_SELF'] = preg_replace("/\?.*$/i", "", $_SERVER['REQUEST_URI']);

if (!isset($_GET['start'])) $_GET['start'] = 1;
if (!isset($_GET['f'])) $_GET['f'] = "keep";
$_GET['q'] = (!isset($_GET['q'])) ? "" : trim($_GET['q']);

$dData['UA'] = "Orca Knowledgebase v2.1b";
$dData['usercat'] = "";
$dData['usersub'] = "";


/* ***** Unpack Filter Cookie ********************************* */
if (isset($_COOKIE['orca_user'])) {
  $unpack = unserialize(stripslashes(base64_decode($_COOKIE['orca_user'])));
  $dData['usercat'] = $unpack[0];
  $dData['usersub'] = $unpack[1];
}


/* ***** Handle Incoming Events ******************************* */
if (isset($_POST['question'])) {
  @ini_set(sendmail_from, $dData['email']);

  $headers = "From: {$dData['emailtitle']} <{$dData['email']}>\r\n";
  $headers .= "X-Sender: <{$dData['email']}>\r\n";
  $headers .= "Return-Path: <{$dData['email']}>\r\n";
  $headers .= "Errors-To: <{$dData['email']}>\r\n";
  $headers .= "X-Mailer: PHP - {$dData['UA']}\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "Date: ".date("r")."\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8";

  $message = sprintf($lang['email1']['message'], $_POST['email'], $dData['emailtitle'], $_POST['question']);

  if (!@mail($dData['email'], sprintf($lang['email1']['subject'], $dData['emailtitle']), $message, $headers, "-f{$dData['email']}"))
    @mail($dData['email'], sprintf($lang['email1']['subject'], $dData['emailtitle']), $message, $headers);

  @ini_restore(sendmail_from);

} else if ($_GET['f'] == "clear" || (isset($_POST['category']) && $_POST['category'] == "")) {
  $_POST['category'] = "";
  $_POST['subcategory'] = "";
  $dData['usercat'] = "";
  $dData['usersub'] = "";

} else if (isset($_POST['category']) && $_POST['category']) {
  if ($dData['usercat'] != $_POST['category']) {
    $subsa = mysql_query("SELECT * FROM `{$dData['tblcateg']}` WHERE `category`='".slashes($_POST['category'])."';");
    $dData['usercat'] = (mysql_numrows($subsa)) ? $_POST['category'] : "";
    $dData['usersub'] = "";

  } else if (isset($_POST['subcategory'])) {
    if ($_POST['subcategory'] != "") {
      $subsa = mysql_query("SELECT * FROM `{$dData['tblcateg']}` WHERE `category`='".slashes($dData['usercat'])."';");
      $subsb = unserialize(stripslashes(mysql_result($subsa, 0, "subcategory")));
      $dData['usersub'] = (in_array($_POST['subcategory'], $subsb)) ? $_POST['subcategory'] : "";
    } else $dData['usersub'] = "";
  }
}

setcookie("orca_user", base64_encode(serialize(array($dData['usercat'], $dData['usersub']))), time() + 18600, $_SERVER['PHP_SELF']);


/* ***** Unpack Subcategories for Selected Category *********** */
$dData['categories'] = mysql_query("SELECT * FROM `{$dData['tblcateg']}` ORDER BY `category`;");
if ($dData['usercat']) {
  $grabSubs = mysql_query("SELECT * FROM `{$dData['tblcateg']}` WHERE `category`='".slashes($dData['usercat'])."';");
  $dData['subcategories'] = unserialize(stripslashes(mysql_result($grabSubs, 0, "subcategory")));
} else $dData['usersub'] = "";


/* ***** Get Selected Question ******************************** */
if (isset($_GET['qid'])) {
  $aData['action'] = true;
  $qQry = mysql_query("SELECT * FROM `{$dData['tblquest']}` WHERE `QID`='{$_GET['qid']}' AND `online`='Yes';");
  if (mysql_numrows($qQry) && preg_match("/\d/", $_GET['qid'])) {
    $qIncre = mysql_query("UPDATE `{$dData['tblquest']}` SET `visited`='".(mysql_result($qQry, 0, "visited") + 1)."' WHERE `QID`='{$_GET['qid']}';");
    $aData['question'] = htmlspecialchars(mysql_result($qQry, 0, "question"));
    $aData['date'] = dateStamp(mysql_result($qQry, 0, "date"));
    $aData['category'] = htmlspecialchars(mysql_result($qQry, 0, "category"));
    $aData['subcategory'] = htmlspecialchars(mysql_result($qQry, 0, "subcategory"));
    $aData['answer'] = www_nl2br(mysql_result($qQry, 0, "answer"));

    $dData['pagetitle'] = $aData['question'];
  } else $aData['action'] = false;
}


/* ***** Do not cache this page ******************************* */
if (!isset($aData['action']) || $aData['action']) {
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  header("Cache-Control: no-store, no-cache, must-revalidate");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
} else header("HTTP/1.1 404 Not Found"); ?>