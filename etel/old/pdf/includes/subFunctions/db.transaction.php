<?
function transaction_get_event()
{
	$sql = "
		SELECT 
			reference_number,
			IF(is_rebill,'rebill',IF()) AS event
		FROM
			cs_transactiondetails
		WHERE
			$sql_limit
	";
	
}

function transaction_cancel_rebill($trans_id)
{
	$ref_no = func_Trans_Ref_No($trans_id);
	
	$trans = new transaction_class(false);
	$trans->pull_transaction($trans_id);
	$status = $trans->process_cancel_request(array("actor"=>'Administrator'));

	return $status;
}

function transaction_customer_cancel($trans)
{
	$id = $trans['transactionId'];
	$userid = $trans['userId'];
	
	$trans = new transaction_class(false);
	$trans->pull_transaction($id);
	$status = $trans->process_refund_request(array("actor"=>'Angel'));
	return $trans->row['transactionTable']['reference_number'];
}

function transaction_get_id_from_ref_num($ref_num)
{
	$sql = "
		select 
			transactionId 
		from 
			`cs_transactiondetails` 
		where 
			reference_number = '$ref_num' 
		";
	$result = sql_query_read($sql) or dieLog(mysql_error()."<pre>$sql</pre>");
	return mysql_result($result,0,0);
}

function transaction_update_user_pass($trans_id,$user,$pass)
{
	$query = "	
		UPDATE 
			`cs_transactiondetails`
		SET 
			td_username = '".$user."',
			td_password = '".$pass."'
		WHERE 
			`transactionId` = '$trans_id'
	";
	
	sql_query_write($query) or dieLog(mysql_error()."<pre>$query</pre>");
}

?>