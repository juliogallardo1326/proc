<?php

chdir("..");
set_time_limit(500);
$gateway_db_select = 3;
$etel_disable_https = 1;
include("includes/dbconnection.php");
$time = time();
$weekNum = date('w',$time);
$monthNum = date('d',$time);
$log = " Updating Profit.\n";
$sql = "
SELECT 
	* 
FROM 
	`cs_entities` 
WHERE 
	(
		(`en_pay_type` = 'Monthly' and en_pay_data >> '$monthNum' & 1) || 
		(`en_pay_type` = 'Weekly' and en_pay_data >> '$weekNum' & 1)
	)
	and en_type in ('merchant','reseller')
";

$RF = new rates_fees();
$result = sql_query_read($sql) or dieLog("$sql ~ ".mysql_error());
while($entityInfo = mysql_fetch_assoc($result))
{
	//$log .= "  Updating Monthly/Setup Fee for '".$entityInfo['en_company']."'.\n";
	//$r = $RF->commit_fees($entityInfo['en_ID']);
	//$log .= "  Result: ".$r['msg']."\n";
	$log .= "  Updating Entity Payout for '".$entityInfo['en_company']."'.\n";
	$r = $RF->commit_payout($entityInfo['en_ID'],array('pending_only'=>true,'date_entered'=>date('Y-m-d',$time)));
	$log .= "  Result: ".$r['msg']."\n";
}
toLog('misc','system', $log);
echo nl2br($log);
?>