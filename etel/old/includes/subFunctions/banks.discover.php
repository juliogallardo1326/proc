<?

class Discover_Client 
{
	var $default_params;
	var $bank_url;
	var $bank_server;
	var $bank_script;
	var $bank_params;
	
	var $url;
	
	function Discover_Client($bankInfo,$mode = "live")
	{
		//these need to be in the bankinfo table
		$this->default_params['L2GMerchantCode'] = $bankInfo['bk_username'];
		$this->default_params['Password'] = $bankInfo['bk_password'];
		$this->default_params['SettleMerchantCode'] = $bankInfo['bk_additional_id'];
		
		//this is fine here
		//if(!strcasecmp($mode,"test"))
			$this->bank_server = "gate.link2gov.com";//"qa.DiscoverNetPay.com";
		//else
		//	$this->bank_server = "ca.link2gov.com";//"www.DiscoverNetPay.com";
		// Test

		$this->bank_url = "https://" . $this->bank_server . "/api/";
		
	}

	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}
		
	function verify_required($req,$given)
	{
		if(!$req)
			return "";
			
		$temp = array();
		foreach($given as $index => $value)
			$temp[strtolower($index)] = $value;
			
		$required = "";
		foreach($req as $reqed)
			if(!isset($temp[strtolower($reqed)]))
				$required .= ($required == "" ? "" : ", ") . $reqed;
		return $required;
	}
	
	function execute_request($params,$req_params,$opt_params = NULL)
	{
		$this->bank_params = $req_params;
		foreach($this->default_params as $param => $value)
			$this->bank_params[] = $param;
			
		if($opt_params != NULL)
			$this->bank_params = array_merge($opt_params,$this->bank_params);

		foreach($this->bank_params as $index => $value)
			$this->bank_params[$index] = strtolower($value);
			
		$res = $this->verify_required($req_params,$params);
		if($res != "")
			return array("status"=>"-1","desc" => "required parameter(s) $res are missing from request");
			
		$res = $this->process_request($params);
		
		$values = $this->process_result($res);
		$values['post_url'] = $this->url;
		
		return $values;	
	}
	
	function process_result($res)
	{
		$res = urldecode($res);
		$res = explode("&",$res);
		$values = array();
		foreach($res as $val)
		{
			$val = explode("=",$val);
			$values[$val[0]] = $val[1];
		}
		return $values;
	}
	
	function process_request($params)
	{	
		if($params == NULL)
			$params = array();

		$post_params = "";
		$params = array_merge($this->default_params,$params);
		
		foreach($params as $name=>$value)
			if(in_array(strtolower($name),$this->bank_params))
				$post_params .= ($post_params == "" ? "" : "&") . $name . "=" . urlencode($value);
		$post_url = $this->bank_url . $this->bank_script;
		
		$this->url = $post_url . "?" . $post_params;

		$res = http_post2($this->bank_server, 443, $post_url,$post_params, "", "");

		return $res;
	}
	
	function Credit_Card_Charge($params)
	{
		$this->bank_script = "ExecuteCharge.aspx";

		//required parameters
		$req_params = array(
			"TransactionAmount",
			"MerchantAmount",
			"ConvenienceFee",
			"AccountNumber",
			"ExpirationMonth",
			"ExpirationYear"
			);

		//optional parameters
		$opt_params = array(
			"TrackOne",
			"TrackTwo",
			"CVV2",
			"BillingAddress",
			"BillingZip",
			"UserPart1",
			"UserPart2",
			"UserPart3",
			"UserPart4",
			"UserPart5",
			"UserPart6",
			"BillingName",
			"BillingCity",
			"BillingState",
			"BillingEmail",
			"BillingPhone"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}
	
	function Credit_Card_Auth($params)
	{
		$this->bank_script = "ExecuteAuth.aspx";

		//required parameters
		$req_params = array(
			"TransactionAmount",
			"MerchantAmount",
			"ConvenienceFee",
			"AccountNumber",
			"ExpirationMonth",
			"ExpirationYear"
			);

		//optional parameters
		$opt_params = array(
			"TrackOne",
			"TrackTwo",
			"CVV2",
			"BillingAddress",
			"BillingZip",
			"UserPart1",
			"UserPart2",
			"UserPart3",
			"UserPart4",
			"UserPart5",
			"UserPart6",
			"BillingName",
			"BillingCity",
			"BillingState",
			"BillingEmail",
			"BillingPhone"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}
	
	function Credit_Card_Settle($params)
	{
		$this->bank_script = "ExecuteSettle.aspx";

		//required parameters
		$req_params = array(
			"OriginalTransactionId"
			);

		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;	
	}
	
	function Recurring_Credit_Card_Charge($params)
	{
		$this->bank_script = "ExecuteRecurringCharge.aspx";

		//required parameters
		$req_params = array(
			"TransactionAmount",
			"MerchantAmount",
			"ConvenienceFee",
			"OriginalTransactionId"
			);

		//optional parameters
		$opt_params = array(
			"AccountNumber",
			"ExpirationMonth",
			"ExpirationYear",
			"UserPart1",
			"UserPart2",
			"UserPart3",
			"UserPart4",
			"UserPart5",
			"UserPart6"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;	
	}
	
	function Debit_Card($params)
	{
		$this->bank_script = "ExecuteDebit.aspx";

		//required parameters
		$req_params = array(
			"TransactionAmount",
			"MerchantAmount",
			"ConvenienceFee",
			"AccountNumber",
			"ExpirationMonth",
			"ExpirationYear"
			);

		//optional parameters
		$opt_params = array(
			"KSN",
			"PINData",
			"TrackOne",
			"TrackTwo",
			"BillingAddress",
			"BillingZip",
			"UserPart1",
			"UserPart2",
			"UserPart3",
			"UserPart4",
			"UserPart5",
			"UserPart6",
			"BillingName",
			"BillingCity",
			"BillingState",
			"BillingEmail",
			"BillingPhone"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}
	
	function Electronic_Check($params)
	{
		$this->bank_script = "ExecuteCheck.aspx";

		//required parameters
		$req_params = array(
			"TransactionAmount",
			"MerchantAmount",
			"ConvenienceFee",
			"AccountNumber",
			"RoutingNumber",
			"CheckNumber",
			"BillingEmail",
			"BillingPhone"
			);

		//optional parameters
		$opt_params = array(
			"IDCode",
			"IDNumber",
			"UserPart1",
			"UserPart2",
			"UserPart3",
			"UserPart4",
			"UserPart5",
			"UserPart6",
			"BillingName",
			"BillingAddress",
			"BillingCity",
			"BillingState",
			"BillingZip"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}
	
	function Refund($params)
	{
		$this->bank_script = "ExecuteRefund.aspx";

		//required parameters
		$req_params = array(
			"OriginalTransactionId"
			);

		//optional parameters
		$opt_params = array(
			"KSN",
			"PinData",
			"AccountNumber",
			"TrackOne",
			"TrackTwo"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;	
	}
	
	function Transaction_Status($params)
	{
		$this->bank_script = "GetTransactionStatus.aspx";

		//required parameters
		$req_params = array(
			"OriginalTransactionId"
			);

		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;		
	}
	
	function System_Check($params)
	{
		$this->bank_script = "HeartBeat.aspx";
		$values = $this->execute_request($params,NULL,NULL);
		return $values;
	}
}

?>