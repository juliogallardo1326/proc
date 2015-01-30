<?php
chdir('..');
include("includes/dbconnection.php");
require_once("includes/function.php");

$td_username = addslashes($_REQUEST['td_username']);
$td_password = addslashes($_REQUEST['td_password']);
$td_reference_number = addslashes($_REQUEST['td_reference_number']);
$mt_reference_id = addslashes($_REQUEST['mt_reference_id']);
$allow_any_site = $_REQUEST['allow_any_site'];
$testmode = $_REQUEST['testmode'];

$req='';
foreach(array_merge($_POST,$_GET) as $key=>$data) $req .= ($req?"&":"").$key."=".$data;

$lg_id = toLog('login','customer', "Customer Querys Access Info: $req");

if(!$td_username) die("UNF");
if(!$td_password) die("PNF");

$sql="SELECT cs_ID,cs_company_id FROM `cs_company_sites` as s WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_reference_id` = '$mt_reference_id' ";
$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
$num = mysql_num_rows($result);
if($num<1) die("SNF");

$siteInfo = mysql_fetch_assoc($result);
$cs_ID = $siteInfo['cs_ID'];
$cs_company_id = $siteInfo['cs_company_id'];

$affiliation = "`td_site_ID` = '$cs_ID'";
if($allow_any_site) $affiliation = "`userId` = '$cs_company_id'";

$trans_table = "`cs_transactiondetails`";
if($testmode) $trans_table = "`cs_test_transactiondetails`";

$sql="SELECT * FROM $trans_table WHERE $affiliation AND (reference_number = '$td_reference_number') AND `td_recur_processed` = 0  AND `td_recur_next_date`>=CURDATE()";
$result=mysql_query($sql,$cnn_cs) or dieLog("ERR");
$num = mysql_num_rows($result);
if($num<1) die("UNF");

$accountInfo = mysql_fetch_assoc($result);
if($accountInfo['td_password'] != $td_password && $accountInfo['reference_number'] != $td_reference_number) die("PNF");


$activity = UserActivity(&$accountInfo);
toLogAppend("Activity Request Successful for '".$accountInfo['reference_number']."': $activity",$lg_id,$accountInfo['transactionId']);
print $activity;
die();
?>