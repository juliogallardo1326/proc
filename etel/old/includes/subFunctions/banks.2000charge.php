<?
class Charge2000_Client 
{
	var $default_params;
	var $bank_url;
	var $bank_server;
	var $bank_script;
	var $bank_params;
	
	var $url;
	
	var $csv_fields;
	var $severity_codes;
	var $ach_status;
	
	var $jcb_account;
	var $discover_account;
	var $eurodebit_account;
	
	function Charge2000_Client($bankInfo,$mode = "test")
	{
		if($mode == "test")
			$this->bank_server = "secure.2000charge.com";
		else
			$this->bank_server = "secure.2000charge.com";

		$this->bank_url = "https://" . $this->bank_server . "/secure/";

		parse_str($bankInfo['bk_additional_id'],$bk_additional_vals);
		
		$this->jcb_account = $bk_additional_vals['jcb'];
		$this->discover_account = $bk_additional_vals['discover'];
		$this->eurodebit_account = $bk_additional_vals['eurodebit'];
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
		$temp = array();
		foreach($given as $index => $value)
			$temp[($index)] = $value;

		$def = array();
		foreach($this->default_params as $index => $value)
			$def[($index)] = $value;
			
		$required = "";
		foreach($req as $reqed)
			if(!isset($temp[($reqed)]))
			if(!isset($def[($reqed)]))
				$required .= ($required == "" ? "" : ", ") . $reqed;
		return $required;
	}
	
	function execute_request($params,$req_params,$opt_params = NULL)
	{
		$this->bank_params = $req_params;
//		foreach($this->default_params as $param => $value)
//			$this->bank_params[] = $param;
			
		if($opt_params != NULL)
			$this->bank_params = array_merge($opt_params,$this->bank_params);

		foreach($this->bank_params as $index => $value)
			$this->bank_params[$index] = ($value);

		$res = $this->verify_required($req_params,$params);
		if($res != "")
			return array("status"=>"-1","desc" => "required parameter(s) $res are missing from request");
			
		$res = $this->process_request($params);
		
		$values = $this->process_result($res);
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
		
		$values['post_url'] = $this->url;
		
		return $values;
	}
	
	function process_request($params)
	{	
		if($params == NULL)
			$params = array();

		$post_params = "";
		$params = array_merge($this->default_params,$params);
		
		$lo_params = array();
		foreach($params as $name=>$value)
			$lo_params[($name)] = $value;
		
		foreach($this->bank_params as $index=>$name)
			if(isset($lo_params[$name]))
				$post_params .= ($post_params == "" ? "" : "&") . $name . "=" . urlencode($lo_params[$name]);
		$post_url = $this->bank_url . $this->bank_script . "?$post_params";
		
		$this->url = $post_url;
		
		$res = http_post2($this->bank_server, 443, $post_url, $post_params, "", "");
		return $res;
	}

	function Discover_Charge($params)
	{
		$this->bank_script = "CCRemoteProcess.asp";

		//required parameters
		$req_params = array(
			"Country",
			"Account",
			"Address1",
			"City",
			"State",
			"Zip",
			"Email",
			"Phone",
			"Amount",
			"RemoteHost",
			"AddUser",
			"CCName",
			"CCNum",
			"CCType",
			"CVV2",
			"CCExpireMonth",
			"CCExpireYear"
			);

		//optional parameters
		$opt_params = array(
			"CCBankPhone",
			"Address2",
			"OrderNum",
			"SalesTax",
			"ShipHandleFee"
		);
		
		$params['Account'] = $this->discover_account; 
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}	

	function JCB_Charge($params)
	{
		$this->bank_script = "CCRemoteProcess.asp";

		//required parameters
		$req_params = array(
			"Country",
			"Account",
			"Address1",
			"City",
			"State",
			"Zip",
			"Email",
			"Phone",
			"Amount",
			"RemoteHost",
			"AddUser",
			"CCName",
			"CCNum",
			"CCType",
			"CVV2",
			"CCExpireMonth",
			"CCExpireYear"
			);

		//optional parameters
		$opt_params = array(
			"CCBankPhone",
			"Address2",
			"OrderNum",
			"SalesTax",
			"ShipHandleFee"
		);
		
		$params['Account'] = $this->jcb_account;
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}	
	
	function EuroDebit_Charge($params)
	{
		$this->bank_script = "MasterRemoteProcess.asp";

		$this->default_params['PaymentType'] = "EuroDebit";
		$this->default_params['BankNameCode'] = 10;
		$this->default_params['AddUser'] = "0";
		
		//required parameters
		$req_params = array(
			"PaymentType",
			"BankNameCode",
			"AddUser",
			"Account",
			"City",
			"RemoteHost",
			"Amount",
			"FirstName",
			"LastName",
			"Address1",
			"State",
			"Zip",
			"Country",
			"Email",
			"CKABA",
			"CKAcct",
			"UserAgent"
			);

		//optional parameters
		$opt_params = array(
			"IDField1",
			"IDField2",
			"IDField3",
			"IDField4",
			"TEST"
		);
		
		//$params['Account'] = $this->eurodebit_account;
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}	
	

}
?>