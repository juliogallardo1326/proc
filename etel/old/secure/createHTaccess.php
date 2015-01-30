<?php
chdir('..');
include("includes/dbconnection.php");
require_once("includes/function.php");
require_once("includes/htaccess.php");

$mt_reference_id = addslashes($_POST['mt_reference_id']);
$allow_any_site = $_POST['allow_any_site'];
$testmode = $_POST['testmode'];
if(!$mt_reference_id) $mt_reference_id = addslashes($_GET['mt_reference_id']);
if(!$allow_any_site) $allow_any_site = $_GET['allow_any_site'];
if(!$testmode) $testmode = $_GET['testmode'];

$sql="SELECT * FROM `cs_company_sites` as s WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_reference_id` = '$mt_reference_id' ";
$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
$num = mysql_num_rows($result);
if($num<1) die("SNF");
$siteInfo = mysql_fetch_assoc($result);
if(!$siteInfo['cs_reference_ID']) die("ERR");

$cs_ID = $siteInfo['cs_ID'];
$cs_company_id = $siteInfo['cs_company_id'];

$affiliation = "`td_site_ID` = '$cs_ID'";
if($allow_any_site) $affiliation = "`userId` = '$cs_company_id'";

$trans_table = "`cs_transactiondetails`";
if($testmode) $trans_table = "`cs_test_transactiondetails`";

$sql="SELECT * FROM $trans_table WHERE $affiliation AND `td_recur_processed` = 0 and status='A' AND `td_recur_next_date`>=CURDATE()";
$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
$num = mysql_num_rows($result);
//if($num<1) die("UNF");
$accessDir = "/home/etel/public_html/access/".$siteInfo['cs_reference_ID']."/";

$ht = new htaccess();

// Setting up path of password file
// Setting up path of password file
@mkdir($accessDir);
chdir($accessDir);
$ht->setFPasswd($accessDir."htpasswd");
//$ht->setFHtaccess($accessDir.".htaccess");

// Setting authenification type
// If you don't set, the default type will be "Basic"
$ht->setAuthType("Basic");
	
// Setting authenification area name
// If you don't set, the default name will be "Internal Area"
$ht->setAuthName("Members Area");
while($accountInfo = mysql_fetch_assoc($result))
{
	// Adding user
	$ht->addUser($accountInfo['td_username'],$accountInfo['td_password'],"");
	$tohash.=$accountInfo['td_username'].":".$accountInfo['td_password'];
}
$hash = md5($tohash);
if($hash == $siteInfo['cs_user_checksum'])
{
	die("CUR");
}
$result=mysql_query("UPDATE `cs_company_sites` set `cs_user_checksum`= '$hash' WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_reference_id` = '$mt_reference_id'",$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
//$ht->addLogin();
die("NEW");
?>