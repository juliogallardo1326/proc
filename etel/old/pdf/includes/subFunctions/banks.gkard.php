<?

class gkard_Client
{
	var $mode;

	function gkard_Client($mode = "Test")
	{
		$this->mode = $mode;
	}

	function array_print($text)
	{
		echo "<table width='100%'><tr><td><pre>";
		print_r($text);
		echo "</pre></td></tr></table>";
	}
	
	function encrypt_password($pass)
	{
		return $pass;
	}
	
	function get_BillingInfo($wallet_id,$wallet_pass,&$transInfo)
	{
		$pass = $this->encrypt_password($pass);
		
		if($this->mode == "Live")
			$tran_table = "cs_transactiondetails";
		else
			$tran_table = "cs_test_transactiondetails";
					
		$sql = "
			SELECT
				name,
				surname,
				address,
				city,
				phonenumber,
				state,
				zipcode,
				country,
				email,
				MAX(transactiondate)
			FROM
				$tran_table
			WHERE
				LOWER(td_gcard) = LOWER('$wallet_id')
				AND	LOWER(td_gcardpass) = LOWER('$wallet_pass')
			GROUP BY td_gcard,td_gcardpass
		";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		if($prev_trans = mysql_fetch_assoc($res))
		{
			$transInfo['firstname'] = $prev_trans['name'];
			$transInfo['lastname'] = $prev_trans['surname'];
			$transInfo['address'] = $prev_trans['address'];
			$transInfo['city'] = $prev_trans['city'];
			$transInfo['phonenumber'] = $prev_trans['phonenumber'];
			$transInfo['state'] = $prev_trans['state'];
			$transInfo['zipcode'] = $prev_trans['zipcode'];
			$transInfo['country'] = $prev_trans['country'];
			$transInfo['email'] = $prev_trans['email'];
			
			$transInfo['td_gcard'] = $wallet_id;
			$transInfo['td_gcardpass'] = $wallet_pass;
		}
	}
}

?>