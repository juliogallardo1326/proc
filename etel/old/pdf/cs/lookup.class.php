<?
class lookup_class
{
	function lookup_class()
	{
	}
	
	function clean_params($params)
	{
		foreach($params as $key=>$value)
			if(is_array($value))
				$params[$key] = clean_params($value);
			else
				$params[$key] = quote_smart(trim($value));
		return $params;
	}
	
	function is_subscription($transid)
	{
		$sql = "
			SELECT
				ss_id
			FROM 
				cs_subscription
				left join cs_transactiondetails on td_ss_ID = ss_ID
			WHERE 
				transactionId = '$transid' AND ss_ID is not null
		";

		$res = sql_query_read($sql);

		if($r = mysql_fetch_assoc($res))
			return true;
		return false;
	}
	
	function search($params)
	{
		$params = $this->clean_params($params);
			
		if(isset($params['CCnumber']) && isset($params['email']))
			return $this->find_cc_trans($params['CCnumber'],$params['email']);

		if(isset($params['bankaccountnumber']) && isset($params['bankroutingcode']))
			return $this->find_check_trans($params['bankaccountnumber'],$params['bankroutingcode']);

		if(isset($params['email']) && isset($params['reference_number']) && isset($params['phonenumber']))
			return $this->find_general_trans($params);
		
		return 0;
	}
	
	function find_cc_trans($CCnumber,$email)
	{
		
		$where = " 
			WHERE 
				td.`CCnumber` = '".etelEnc($CCnumber)."'
				AND td.`email` = '$email'
				AND td.`cardtype` in ('Visa','Mastercard','Discover','JCB')
				$email_sql
			";
		return $this->find_transaction_query("$where");				
	}
	
	function find_check_trans($bankaccountnumber,$bankroutingcode)
	{
		$where = "
			WHERE 
				td.`cardtype` = 'Check' 
				AND td.`bankroutingcode`  = '".etelEnc($bankroutingcode)."'
				AND td.`bankaccountnumber` = '".etelEnc($bankaccountnumber)."' 
		"; 
		return $this->find_transaction_query("$where");				
	}
	
	function find_general_trans($params)
	{
		$query = array();
		if($params['email']) $query['email'] = $params['email'];
		if($params['reference_number']) $query['reference_number'] = $params['reference_number'];
		if($params['phonenumber']) $query['phonenumber'] = $params['phonenumber'];
		if($params['subscription_id']) $query['ss_subscription_id'] = $params['subscription_id'];
		if($params['credit_card']) $query['CCnumber'] = etelEnc($params['credit_card']);
		if($params['checking_account'] && $params['routing_number']) 
		{
			$query['bankaccountnumber'] = etelEnc($params['checking_account']);
			$query['bankroutingcode'] = etelEnc($params['routing_number']);
		}
		
		if(sizeof($query)<1) return 0;
		foreach($query as $key=>$data)
			$sql.=($sql?"and ":"")." $key = '$data' ";
		$where = "
			WHERE 
				$sql
			";
		return $this->find_transaction_query("$where");				
	}
	
	function find_transaction_query($where)
	{
		$_SESSION['no_more_where'] = "false";
		$_SESSION['where'] = $where;
			$sql = "
				SELECT 
					*
				FROM 
					`cs_transactiondetails` as td 
				LEFT JOIN `cs_subscription` as sub ON td.`td_ss_ID`	= sub.`ss_ID` 
				LEFT JOIN `cs_callnotes` as cn ON td.`transactionId` = cn.`transaction_id` 
				LEFT JOIN `cs_company_sites` as comp ON td.`td_site_ID` = comp.`cs_ID` 
				
					$where 
					AND td.`status` != 'D' 
				ORDER BY `td_ss_ID` DESC, `transactionDate` DESC 
			";
		$res = sql_query_read("$sql")  or dieLog(mysql_error() . "<pre>$sql</pre>");	
		$trans = array();
		while($row = mysql_fetch_assoc($res))
			$trans[] = $row;
		return $trans;
		
		// customer_service_phone
		/*if someoen seraches by ref id then we are not ok if it is Suscriptionwith more than one transaction associated with it.
			I am sure there is better way to do this if, but this works. 
		*/
		if(mysql_num_rows($res) ==1 && strlen($row[ss_ID])>0)
		{
			$sql =	"
				SELECT  
					*
				FROM 
					`cs_transactiondetails` 		as td 
				LEFT JOIN `cs_subscription` 		as sub  ON 	td.`td_ss_ID` 		= sub.`ss_ID`
				LEFT JOIN `cs_callnotes` 			as cn 	ON 	td.`transactionId` 	= cn.`transaction_id` 				
				LEFT JOIN `cs_company_sites` 	as comp ON 	td.`td_site_ID` 		= comp.`cs_ID` 
				WHERE 
					td.`td_ss_ID` = '". $row[ss_ID]."' AND td.`status` != 'D'
				ORDER BY 
					`td_ss_ID` DESC,  
					`transactionDate` DESC
				";
				$_SESSION['where'] ="WHERE td.`td_ss_ID` = '". $row[ss_ID]."'";
				$res= sql_query_read("$sql")  or dieLog(mysql_error() . "<pre>$sql</pre>");
		}
		elseif(isset($res))
			if(mysql_num_rows($res)>0)
				mysql_data_seek($res,0);
		return $res;
	}
	
}
?>