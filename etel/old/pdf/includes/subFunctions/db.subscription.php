<?
function subscription_get_notes($sub_id)
{
	$sql = "
		SELECT
			ss_account_notes
		FROM
			cs_subscription
		WHERE
			ss_subscription_id = '$sub_id' 
	";
	$res = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
	
	$r = mysql_fetch_assoc($res);
	$notes = array();
	if($r['ss_account_notes']!="")
		$notes = unserialize($r['ss_account_notes']);
	return $notes;
}

function subscription_add_note($sub_id,$sub_notes)
{
	$sql = "
		UPDATE
			cs_subscription
		SET
			ss_account_notes = CONCAT(ss_account_notes,'\n\n',NOW(),': $notes')
		WHERE
			ss_subscription_id = '$sub_id' 
	";
	
	//echo "<pre>$sql</pre>";
		
	$res = sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
}

function subscription_cancel($sub_id,$user_id,$sub_notes="",$sub_status_text="")
{
	$trans = new transaction_class(false);
	$trans->pull_subscription($sub_id,'ss.ss_subscription_id');
	$status = $trans->process_cancel_request(array("actor"=>'System','notes'=>$sub_notes,'verifyuserId'=>$user_id));
	
	if(!$status) return false;
	return $status['ss_cancel_id'];
}

?>