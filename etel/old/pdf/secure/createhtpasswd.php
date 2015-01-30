<?php
chdir('..');
$etel_disable_https=true;
$etel_debug_mode = 0;
include("includes/dbconnection.php");
require_once("includes/function.php");
require_once("includes/htaccess.php");

$mt_reference_id = quote_smart($_REQUEST['mt_reference_id']);
$allow_any_site = $_REQUEST['allow_any_site'];
$testmode = $_REQUEST['testmode'];
$mt_username = $_REQUEST['mt_username'];
$mt_password = $_REQUEST['mt_password'];
$mt_access_md5 = $_REQUEST['mt_access_md5'];
$forceupdate = $_REQUEST['forceupdate'];

$sql="SELECT * FROM `cs_companydetails` as c left join `cs_company_sites` as s on cs_company_id=userId WHERE `cs_gatewayId` = '".$_SESSION["gw_id"]."' AND `cs_reference_id` = '$mt_reference_id' and `suspenduser`='NO'";
$result=mysql_query($sql,$cnn_cs) or dieLog("ERR");
$num = mysql_num_rows($result);
if($num<1) die("SNF");
$siteInfo = mysql_fetch_assoc($result);
if(!$siteInfo['cs_reference_ID']) die("ERR");
if(($siteInfo['username'] != $mt_username || $siteInfo['password'] != $mt_password) && (md5($siteInfo['username'].$siteInfo['password']) != $mt_access_md5)) die("PNF");

$cs_ID = $siteInfo['cs_ID'];
$cs_company_id = $siteInfo['cs_company_id'];

$affiliation = "`td_site_ID` = '$cs_ID'";
if($allow_any_site) $affiliation = "`userId` = '$cs_company_id'";

$trans_table = "`cs_transactiondetails`";
if($testmode) $trans_table = "`cs_test_transactiondetails`";

$sql="SELECT * FROM $trans_table WHERE $affiliation AND `td_recur_processed` = 0 and status='A' AND `td_recur_next_date`>=CURDATE()";
$result=mysql_query($sql,$cnn_cs) or dieLog("ERR");
$num = mysql_num_rows($result);
//if($num<1) die("UNF");
$htpasswd = "";
$numPass = 0;
while($accountInfo = mysql_fetch_assoc($result))
{
	// Adding user
	$tohash.=$accountInfo['td_username'].":".$accountInfo['td_password'];
	$username=$accountInfo['td_username'];
	$password=crypt($accountInfo['td_password']);
    $htpasswd.=$username.":".$password."\n";
	$numPass++;
}
$hash = md5($tohash);
if($hash == $siteInfo['cs_user_checksum'] && !$forceupdate)
{
	die("CUR");
}
$testmode_msg = "Live Mode";
if($testmode) $testmode_msg = "Test Mode";
//$showsql = $sql;
toLog('login','merchant', $siteInfo['cs_URL']." Requests .htpasswd Update in $testmode_msg: ($numPass) SQL=$showsql AllowAny=$allow_any_site IP=".getRealIp(), $cs_company_id);

$result=mysql_query("UPDATE `cs_company_sites` set `cs_user_checksum`= '$hash' WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_reference_id` = '$mt_reference_id'",$cnn_cs) or dieLog("ERR");
//$ht->addLogin();
die($htpasswd);
?>