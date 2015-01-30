<?php 

chdir("..");
include("admin/includes/sessioncheck.php");
include("includes/dbconnection.php");
$headerInclude = "companies";
include("admin/includes/mailbody_replytemplate.php");
include("includes/message.php");
require_once("includes/function.php");
include("includes/function2.php");
include("includes/integration.php");
		$sql= "SELECT *
FROM `cs_transactiondetails` AS t, `cs_rebillingdetails` AS r
WHERE rd_subaccount = `td_rebillingID` AND t.`status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' AND `td_is_chargeback` = '0' ";
		$result=mysql_query($sql,$cnn_cs) or die(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
	while($trans = mysql_fetch_assoc($result))
	{
		$period =$trans['rd_trial_days'];
		if($trans['td_is_a_rebill'] == 1 || !$trans['rd_trial_days'])$period =$trans['recur_day'];
		$newdate = strtotime($trans['transactionDate'])+$period*24*60*60;
		$validRecur = 0;
		if($trans['recur_day'] && $trans['recur_charge']) $validRecur = 1;
		$sql2= "UPDATE `cs_transactiondetails` set  td_recur_next_date = '".date("Y-m-d",$newdate)."' WHERE transactionId = ".$trans['transactionId'];
		print($sql2."<br>");
		$result2=mysql_query($sql2,$cnn_cs) or die(mysql_errno().": ".mysql_error()."<br>Cannot execute query");

	}
