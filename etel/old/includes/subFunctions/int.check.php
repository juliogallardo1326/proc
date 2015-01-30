<?

function ch_check_previous_decline($transInfo,$hours=24)
{
	return 0 ;
	$sql="
		SELECT 
			*
		FROM 
			`cs_transactiondetails`
		WHERE 
			`bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."' 
			AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)
			AND (`status` != 'A' or `cancelstatus` = 'Y' or `td_is_chargeback`=1) 
			AND (`td_bank_recieved` = 'yes' or `td_bank_recieved` = 'fraudscrubbing')
		";
	$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
	$numrows = mysql_num_rows($result);
	return ($numrows > 0);
}

?>