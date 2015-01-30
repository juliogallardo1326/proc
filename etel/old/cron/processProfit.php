<?php

chdir("..");
set_time_limit(500);
$gateway_db_select = 3;
$etel_disable_https = 1;
include("includes/dbconnection.php");

$RF = new rates_fees();

$log .= "Updating Pending Profit Actions.\n";
$sql="SELECT pa_trans_id FROM `cs_profit_action` WHERE `pa_status` = 'pending' and `pa_type` = 'Transaction' order by pa_ID desc LIMIT 5000";

$result=sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
if(mysql_num_rows($result)==0) $log .= "No Transaction Updates\n";
else
	while($cs_profit_action=mysql_fetch_assoc($result))
	{
		$log .= "  Updating Transaction Profit for '".$cs_profit_action['pa_trans_id']."'.\n";
		$r = $RF->update_transaction_profit($cs_profit_action['pa_trans_id']);
		$log .= "  Result: ".$r['msg']."\n";
	}
//toLog('misc','system', $log);

$sql="SELECT * FROM `cs_profit_action` WHERE `pa_status` = 'pending' and `pa_type` = 'Payout' order by pa_ID desc LIMIT 1000";

$result=sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
if(mysql_num_rows($result)==0) $log .= "No Payout Updates\n";
else
{
	$log .= "\n Updating Pending Payout Actions.\n";
	while($cs_profit_action=mysql_fetch_assoc($result))
	{
		$log .= "  Updating Payout Profit for Entity '".$cs_profit_action['pa_en_ID']."'.\n";
		$r = $RF->commit_payout($cs_profit_action['pa_en_ID'],array('date_entered'=>$cs_profit_action['pa_date']));
		$log .= "  Result: ".$r['msg']."\n";
	}
}

$sql="SELECT * FROM `cs_profit_action` WHERE `pa_status` = 'delete' order by pa_ID desc LIMIT 1000";

$result=sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
if(mysql_num_rows($result)==0) $log .= "\nNo Voided Entries\n";
else
{
	$log .= "\n Updating Void Actions.\n";
	while($cs_profit_action=mysql_fetch_assoc($result))
	{
		$log .= "  Updating Void Action '".$cs_profit_action['pa_ID']."'.\n";
		$r = $RF->undo_transfer($cs_profit_action['pa_ID']);
		$log .= "  Result: ".$r['msg']."\n";
	}
}


echo nl2br($log);
?>