<?php
$etel_disable_https = 1;
chdir(dirname(__FILE__));
chdir("..");
$gateway_db_select = 3;
$etel_debug_mode=1;
include("includes/dbconnection.php");
require_once ("includes/projSetCalc.php");

$qry_company="SELECT * FROM `cs_bank` WHERE 1";

$bank_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");

while($bankInfo=mysql_fetch_assoc($bank_details))
{
	echo 1;

	unset($bankInfo['bk_defaults']);
	$cs_bank_invoice = NULL;
	$bi_pay_info = NULL;
	$date_hold = 0;
	$date_delay = 0;
	
	$bi_pay_info = updateBankInvoice(time());
	print_r($bi_pay_info);

	sleep(2);
	
}
?>