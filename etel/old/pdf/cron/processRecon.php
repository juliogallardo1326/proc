<?php

chdir("..");
set_time_limit(500);
$gateway_db_select = 3;
$etel_debug_mode = 1;
$etel_disable_https = 1;
include("includes/dbconnection.php");
include("includes/subFunctions/banks.checkgateway.php");


$log = " Reconciliation for CheckGateway.\n";
$sql = " Select * from cs_bank where bank_id = 18";
$result = sql_query_read($sql) or dieLog($sql);
$bankInfo = mysql_fetch_assoc($result);
parse_str($bankInfo['bk_additional_id'],$loginparams);

$sql = " 
SELECT DATE_FORMAT( date, '%m/%d/%Y' ) AS datefrom, count( * ) AS cnt
FROM (

	SELECT `transactionDate` AS date
	FROM `cs_transactiondetails`
	WHERE `status` = 'P'
	AND `bank_id` =18
	AND `transactionDate` < subdate( now( ) , INTERVAL 5
	DAY )
) AS t
GROUP BY datefrom
 ";
$result = sql_query_read($sql) or dieLog($sql);
while($dateInfo[] = mysql_fetch_assoc($result));

$CGC = new CheckGateway_Client($bankInfo);
$params = array();
$params['Format'] = "TXT";
$params['Incremental'] = "False";
foreach($dateInfo as $date)
{
	
	$log .= " Checking Date: ".$date['date']."\n";

	$params['DateFrom'] = $date['datefrom'];
	$params['DateTill'] = $date['datefrom'];
	$CGC->default_params['MerchantNumber'] = $loginparams['mid'];
	$CGC->default_params['Password'] = $loginparams['mpw'];
	$log.=$CGC->process_transactions($params);
	
	$CGC->default_params['MerchantNumber'] = $loginparams['rmid'];
	$CGC->default_params['Password'] = $loginparams['rmpw'];
	$log.=$CGC->process_transactions($params);
}
	
toLog('misc','system', $log);
?>