<?
class intabill_Client 
{
	var $url;
	var $query;
	var $page;
    var $client;
 	var $nMerchantId;
	var $sMerchantPassword;
	var $sSiteID;
	var $sProductID;
	var $soapFunctions_text;
	var $soapFunctions;
	var $namespace;
 	var $resultCodes;
	
    function intabill_Client($bankInfo,$is_rebill=false,$is_trial=false) 
	{
		$this->resultCodes = array();
		
		//$this->resultCodes = $genArray;
		
		//$this->url = "http://service.merchsolutions.com/?wsdl";
		$this->url = "https://service.merchlogin.com/?wsdl";
		$this->page = "";
		$this->namespace = "http://service.merchsolutions.com/";
	
		$this->Set_Merchant_Password($bankInfo['bk_username'],$bankInfo['bk_password']);
    }
 	
	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}
		
	function Post_To_Params($post_vars)
	{
		$params = array();     
		$elements = explode("&",$post_vars);
		foreach($elements as $element)
		{
			$param = explode("=",$element);
			$params[$param[0]] = $param[1];
		}
		return $params;
	}
 
 	function obj_array($object)
	{
		if(is_array($object) && !is_object($object[0])) return $object;
	
		if(is_a($object,'SOAP_Fault'))
			return $object;
			
		$res = array();
		$m = sizeof($object);
		for($j=0;$j<$m;$j++)
			$res[$object[$j]->key] = $object[$j]->value;		
	
		return $res;
	}
	
 	function Set_Merchant_Password($id,$password)
	{
		$this->nMerchantId = $id;
		$this->sMerchantPassword = $password;
	}
	
	function Execute_Function($function_Name,$params,$req_params,$opt_params, $param_order)
	{
		//$params["customerPaymentPageText"]= $this->nMerchantId;
		
		$temp = array();
		foreach($params as $index => $value)
			$temp[strtolower($index)] = $value;

		$missing_params = "";
		$set_params = array();
		foreach($req_params as $index => $value)
			if(!isset($temp[strtolower($value)]))
				$missing_params .= $missing_params == "" ? $value : ", $value";
			else
				$set_params[strtolower($value)] = $temp[strtolower($value)];

		if($missing_params != "")
			return array("missing parameters"=>$missing_params);

		foreach($opt_params as $index => $value)
			if(isset($temp[strtolower($value)]))
				$set_params[strtolower($value)] = $temp[strtolower($value)];
			else
				$set_params[strtolower($value)] = "0";
		
		$send_params = array();
		foreach($param_order as $param_name)
			$send_params[$param_name] = $set_params[$param_name];
		
        $this->client = new SOAP_Client($this->url . $this->page);
		$this->client->setOpt('curl',CURLOPT_SSL_VERIFYHOST,0);
		$this->client->setOpt('curl',CURLOPT_SSL_VERIFYPEER,0);
		$this->client->setOpt('curl',CURLOPT_TIMEOUT,30);
		$this->client->setOpt('curl',CURLOPT_CONNECTTIMEOUT,30);
		$this->client->setOpt('timeout', 30);
		$this->query = array($this->url,$this->page,$send_params,$this->namespace.$function_Name);
		
		return $this->client->call($function_Name,$send_params,array("namespace" => $this->namespace,"soapaction" =>$this->namespace.$function_Name));
	}
	
	function Execute_Sale($params)
	{
		$this->page = "";
		
		$param_order = array(
			"merchantid",
			"ipaddress",
			"submittedamount",
			"merchantreference",
			"clientfirstname",
			"clientsurname",
			"clientphone",
			"clientstreet",
			"clientcity",
			"clientstate",
			"clientcountry",
			"clientpostcode",
			"clientemail",
			"product",
			"cardnumber",
			"cardname",
			"expirymonth",
			"expiryyear",
			"cardcvv",
		);

		$req_params = array(
			"merchantid",
			"ipaddress",
			"submittedamount",
			"merchantreference",
			"clientfirstname",
			"clientsurname",
			"clientphone",
			"clientcountry",
			"clientpostcode",
			"clientemail",
			"product",
			"cardnumber",
			"cardname",
			"expirymonth",
			"expiryyear",
			"cardcvv",
   		);

		$opt_params = array(
			"clientstreet",
			"clientcity",
			"clientstate",
		);
		
		$res = $this->Execute_Function("ProcessCreditCard",$params,$req_params,$opt_params,$param_order);
		return $res;
		//return $this->obj_array($res);
	}

	function Execute_Refund($params)
	{
		$this->page = "";

		$param_order = array(
			"merchantid",
			"transactionid"
		);

		$req_params = array(
		   "merchantid",
		   "transactionid"
   		);

		$opt_params = array(
		);
		
		$res = $this->Execute_Function("ProcessRefund",$params,$req_params,$opt_params,$param_order);
		return $res;
		//return $this->obj_array($res);
	}
}


?>