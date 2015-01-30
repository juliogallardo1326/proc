<?
class iPayGate_Client 
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
	
    function iPayGate_Client($bankInfo,$is_rebill=false,$is_trial=false) 
	{
		$this->resultCodes = array();
		
		$genArray['000'] = "Approved";
		$genArray['001'] = "Refer to issuer";
		$genArray['002'] = "Refer to issuer-special";
		$genArray['003'] = "Invalid merchant";
		$genArray['004'] = "Pick up card";
		$genArray['005'] = "Do not honor";
		$genArray['006'] = "Error";
		$genArray['007'] = "Pick up card-special";
		$genArray['008'] = "Approved with ID (Not used yet)";
		$genArray['011'] = "VIP approval";
		$genArray['012'] = "Invalid transaction";
		$genArray['013'] = "Invalid amount";
		$genArray['014'] = "Invalid card number";
		$genArray['015'] = "Invalid issuer";
		$genArray['019'] = "Re-enter transaction";
		$genArray['021'] = "No action taken";
		$genArray['025'] = "Unable to locate record";
		$genArray['028'] = "File unavailable";
		$genArray['030'] = "Format error";
		$genArray['039'] = "No credit account";
		$genArray['041'] = "Lost card";
		$genArray['043'] = "Stolen card";
		$genArray['051'] = "Insufficient funds";
		$genArray['052'] = "No checking account";
		$genArray['053'] = "No savings account";
		$genArray['054'] = "Expired card";
		$genArray['055'] = "Invalid PIN";
		$genArray['057'] = "Trans not permitted";
		$genArray['058'] = "Trans not permitted";
		$genArray['061'] = "Amount limit exceeded";
		$genArray['062'] = "Restricted card";
		$genArray['065'] = "Count limit exceeded";
		$genArray['068'] = "Response Received Late";
		$genArray['075'] = "PIN tries exceeded";
		$genArray['076'] = "Unable to locate previous msg";
		$genArray['077'] = "Edit error";
		$genArray['080'] = "Invalid date";
		$genArray['081'] = "Pin crypto error";
		$genArray['082'] = "Incorrect CVV";
		$genArray['083'] = "Unable to verify PIN";
		$genArray['085'] = "No reason to decline";
		$genArray['091'] = "Issuer inoperative";
		$genArray['092'] = "Routing failure";
		$genArray['093'] = "Law violatioin";
		$genArray['096'] = "System error";
		$genArray['099'] = "Transaction or system error";
		$genArray['107'] = "Call Issuer";
		$genArray['502'] = "Insufficient funds - paymetec";
		$genArray['519'] = "On negative file (ACH only)";
		$genArray['548'] = "Not on credit bureau";
		$genArray['801'] = "Invalid effective date";
		$genArray['803'] = "Invalid biller information";
		$genArray['805'] = "Force STIP";
		$genArray['806'] = "CVV2 failure";
		$genArray['807'] = "Invalid card/biller information";
		$genArray['808'] = "Auth cancelled or revoked";
		$genArray['899'] = "Misc. decline";
		$genArray['900'] = "Invalid message type";
		$genArray['905'] = "Invaild cartype";
		$genArray['908'] = "Manual Card Entry Invalid";
		$genArray['909'] = "Invalid Track Information";
		$genArray['912'] = "Invalid Card Format";
		$genArray['913'] = "Invalid Transaction Type";
		$genArray['917'] = "Expired Card";
		$genArray['920'] = "Invalid amount";
		$genArray['923'] = "aba error";
		$genArray['924'] = "Invalid DDA";
		$genArray['926'] = "Invalid Password";
		$genArray['930'] = "Invalid zipcode";
		$genArray['933'] = "Invalid CVV2";
		$genArray['934'] = "PVID missing, invalid, or expired";
		$genArray['935'] = "Invalid biller information";
		$genArray['936'] = "Forward to issuer";
		$genArray['937'] = "Force STP";
		$genArray['938'] = "CVV2 failure";
		$genArray['940'] = "Record Not Found";
		$genArray['942'] = "Refund Not Allowed";
		$genArray['981'] = "Invalid AVS";
		$genArray['987'] = "Issuer Unavailable";
		$genArray['989'] = "Database Error";
		$genArray['992'] = "Transaction Timeout";
		$genArray['996'] = "Bad Terminal ID";
		$genArray['998'] = "Message Not Supported";
		$genArray['999'] = "Communication failure";
		$this->resultCodes = $genArray;
		
		$this->url = "https://www.ipaydna.net/pegasus/gatedna/process/acquirer/";
		$this->page = "";
		$this->namespace = "http://acquirer.process.gatedna.pegasus/";
	
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
		//print_r($send_params);
		$this->query = array($this->url,$this->page,$send_params,$this->namespace.$function_Name);
		
		return $this->client->call($function_Name,$send_params,array("namespace" => $this->namespace,"soapaction" =>$this->namespace.$function_Name));
	}
	
	function Execute_Sale($params)
	{
		$this->page = "webservicepayment.cfc?wsdl";
		
		$param_order = array(
			"customerpaymentpagetext",
			"orderdescription",
			"currencytext",
			"purchaseamount",
			"taxamount",
			"shippingamount",
			"dutyamount",
			"cardholdername",
			"cardno",
			"cardtypetext",
			"securitycode",
			"cardexpiremonth",
			"cardexpireyear",
			"cardissuemonth",
			"cardissueyear",
			"issuername",
			"firstname",
			"lastname",
			"company", 
			"address", 
			"city", 
			"state", 
			"zip", 
			"country", 
			"email", 
			"phone", 
			"shipfirstname", 
			"shiplastname", 
			"shipaddress", 
			"shipcity", 
			"shipstate", 
			"shipzip", 
			"shipcountry",
			"cardholderip"
		);

		$req_params = array(
		   "customerPaymentPageText",
		   "orderDescription",
		   "currencyText",
		   "purchaseAmount",
		   "taxAmount",
		   "cardHolderName",
		   "cardNo",
		   "cardTypeText",
		   "securityCode",
		   "cardExpireMonth",
		   "cardExpireYear",
		   "cardHolderIP"
   		);

		$opt_params = array(
		   "shippingAmount",
		   "dutyAmount",
		   "cardIssueMonth",
		   "cardIssueYear",
		   "issuerName",
		   "firstName",
		   "lastName",
		   "company",
		   "address",
		   "city",
		   "state",
		   "zip",
		   "country",
		   "email",
		   "phone",
		   "shipFirstName",
		   "shipLastName",
		   "shipAddress",
		   "shipCity",
		   "shipState",
		   "shipZip",
		   "shipCountry"
		);
		
		$res = $this->Execute_Function("payment",$params,$req_params,$opt_params,$param_order);
		return $res;
		//return $this->obj_array($res);
	}

	function Execute_Refund($params)
	{
		$this->page = "webservicerefund2.cfc?wsdl";

		$param_order = array(
			"customerpaymentpagetext",
			"orderdescription",
			"refundamount",
			"referralorderreference",
			"comment1"
		);

		$req_params = array(
		   "customerPaymentPageText",
		   "orderDescription",
		   "refundAmount",
		   "referralOrderReference"
   		);

		$opt_params = array(
		   "comment1"
		);
		
		$res = $this->Execute_Function("refund",$params,$req_params,$opt_params,$param_order);
		return $res;
		//return $this->obj_array($res);
	}
}


?>