<?
class AmeriNet_Client 
{
	var $url;
    var $client;
 	var $nMerchantId;
	var $sMerchantPassword;
	var $sSiteID;
	var $sProductID;
	var $soapFunctions_text;
	var $soapFunctions;
	var $namespace;
 	var $resultCodes;
	
    function AmeriNet_Client ($bankInfo,$is_rebill=false,$is_trial=false) 
	{
		$this->resultCodes = array();
		$this->resultCodes['0100'] = "System Available";
		$this->resultCodes['0101'] = "Accepted for Debit or Credit settlement";
		$this->resultCodes['0102'] = "Accepted Debit or Credit already in queue";
		$this->resultCodes['0103'] = "Successful Validation";
		$this->resultCodes['0104'] = "Successful Void";
		$this->resultCodes['0105'] = "Accepted Cash payment request";
		$this->resultCodes['0106'] = "Accepted Cash payment request already in queue";
		$this->resultCodes['0107'] = "Reserved";
		$this->resultCodes['0108'] = "Order Exists on the System";
		$this->resultCodes['0201'] = "Invalid Production Code value";
		$this->resultCodes['0202'] = "Service Denied - Call AmeriNet";
		$this->resultCodes['0203'] = "Ineligible Cash payment order";
		$this->resultCodes['0204'] = "Credentials conflict";
		$this->resultCodes['0301'] = "Amount exceeds maximum Debit order limit";
		$this->resultCodes['0302'] = "Amount exceeds maximum Credit order limit";
		$this->resultCodes['0303'] = "Amount below minimum (cannot be less than $0.01)";
		$this->resultCodes['0304'] = "Amount exceeds maximum Cash order limit";
		$this->resultCodes['0305'] = "Not configured to allow Credits";
		$this->resultCodes['0306'] = "Not configured to allow Validation";
		$this->resultCodes['0307'] = "Not configured to allow Canadian eChecks";
		$this->resultCodes['0401'] = "Invalid Routing/Transit number (failed algorithm)";
		$this->resultCodes['0402'] = "Routing/Transit number not found (failed lookup)";
		$this->resultCodes['0403'] = "Invalid Account number";
		$this->resultCodes['0404'] = "Invalid Check Sequence number";
		$this->resultCodes['0405'] = "Extra characters in MICR";
		$this->resultCodes['0406'] = "Invalid MICR country code";
		$this->resultCodes['0407'] = "MICR negative history present";
		$this->resultCodes['0408'] = "MICR paper draft only";
		$this->resultCodes['0409'] = "MICR unserviceable";
		$this->resultCodes['0410'] = "Credits only (no Debits)";
		$this->resultCodes['0411'] = "Consumer not of age";
		$this->resultCodes['0501'] = "Invalid First Name";
		$this->resultCodes['0502'] = "Invalid Last Name";
		$this->resultCodes['0503'] = "Invalid Street Address";
		$this->resultCodes['0504'] = "Invalid City";
		$this->resultCodes['0505'] = "Invalid State/Province abbreviation";
		$this->resultCodes['0506'] = "Invalid Zip/Postal code";
		$this->resultCodes['0507'] = "Invalid Address Country code";
		$this->resultCodes['0508'] = "Invalid Date of Birth format";
		$this->resultCodes['0509'] = "Invalid Phone Number format";
		$this->resultCodes['0510'] = "Address parameters conflict";
		$this->resultCodes['0511'] = "Invalid currency format";
		$this->resultCodes['0512'] = "Duplicate item";
		$this->resultCodes['0601'] = "Invalid Cycle Duration";
		$this->resultCodes['0602'] = "Invalid Cycle Frequency";
		$this->resultCodes['0603'] = "Invalid Cycle Multiplier";
		$this->resultCodes['0604'] = "Invalid First Pay Add amount";
		$this->resultCodes['0605'] = "Invalid Type Code";
		$this->resultCodes['0606'] = "Invalid Check Type";
		$this->resultCodes['0607'] = "Invalid Check Source";
		$this->resultCodes['0608'] = "Ineligible transaction request";
		$this->resultCodes['0609'] = "Invalid Cash Order message type";
		$this->resultCodes['0610'] = "Invalid Cash Order UserDef value";
		$this->resultCodes['0611'] = "Cash Order duplicate UserDef value";
		$this->resultCodes['0612'] = "Cash Order not supported";
		$this->resultCodes['0613'] = "Cash Fulfillment Amount mismatch";
		$this->resultCodes['0614'] = "Cash Fulfillment UserDef mismatch";
		$this->resultCodes['0615'] = "Cash Order expired by SwiftPay";
		$this->resultCodes['0616'] = "Cash Order expired by AmeriNet";
		$this->resultCodes['0617'] = "Cash Fulfillment previously posted";
		$this->resultCodes['0618'] = "Cash Fulfillment previously confirmed";
		$this->resultCodes['5001'] = "AmeriNet Internal Error";
		$this->resultCodes['5002'] = "Transaction Server Unavailable";
		$this->resultCodes['5051'] = "Third Party Connection error - Certegy";
		$this->resultCodes['5052'] = "Third Party Connection error - STAR Chek";
		$this->resultCodes['5061'] = "Third Party Connection timeout - Certegy";
		$this->resultCodes['5062'] = "Third Party Connection timeout - STAR Chek";
		$this->resultCodes['5050'] = "Unknown Error";
		$this->resultCodes['9901'] = "Certegy Check Services";
		$this->resultCodes['9902'] = "STAR Chek Systems";

		$this->resultCodes['R01'] = "Insufficient Funds*";
		$this->resultCodes['R02'] = "Account Closed*";
		$this->resultCodes['R03'] = "No Account/Unable to Locate Account*";
		$this->resultCodes['R04'] = "Invalid Account Number*";
		$this->resultCodes['R05'] = "Reserved";
		$this->resultCodes['R06'] = "Returned per ODFI's Request";
		$this->resultCodes['R07'] = "Authorization Revoked by Customer*";
		$this->resultCodes['R08'] = "Payment Stopped or Stop Payment on Item*";
		$this->resultCodes['R09'] = "Uncollected Funds*";
		$this->resultCodes['R10'] = "Customer Advises Not Authorized*";
		$this->resultCodes['R11'] = "Check Truncation Entry Return";
		$this->resultCodes['R12'] = "Branch sold to another DFI";
		$this->resultCodes['R13'] = "RDFI not qualified to participate";
		$this->resultCodes['R14'] = "Representment payee deceased or unable to continue in that capacity";
		$this->resultCodes['R15'] = "Beneficiary of account holder deceased";
		$this->resultCodes['R16'] = "Account Frozen";
		$this->resultCodes['R17'] = "File record edit criteria";
		$this->resultCodes['R20'] = "Non-Transaction Account";
		$this->resultCodes['R21'] = "Invalid company identification";
		$this->resultCodes['R22'] = "Invalid individual ID number";
		$this->resultCodes['R23'] = "Credit entry refused by receiver";
		$this->resultCodes['R24'] = "Duplicate entry";
		$this->resultCodes['R26'] = "Mandatory field error";
		$this->resultCodes['R28'] = "Routing number check digit error";
		$this->resultCodes['R29'] = "Corporate customer advises not authorized";
		$this->resultCodes['R31'] = "Permissible return entry";
		$this->resultCodes['R33'] = "Return of XCK entry";
		$this->resultCodes['R34'] = "Limited participation DFI";
		$this->resultCodes['R36'] = "Return of improper credit entry";
		$this->resultCodes['R51'] = "Item is Ineligible, Notice Not Provided, Signature not genuine";
		$this->resultCodes['R52'] = "Stop Payment on Item";
		$this->resultCodes['R61'] = "Misrouted return";
		$this->resultCodes['R62'] = "Incorrect trace number";
		$this->resultCodes['R63'] = "Incorrect dollar amount";
		$this->resultCodes['R64'] = "Incorrect individual identification";
		$this->resultCodes['R65'] = "Incorrect transaction code";
		$this->resultCodes['R66'] = "Incorrect company identification";
		$this->resultCodes['R67'] = "Duplicate return";
		$this->resultCodes['R68'] = "Untimely Return";
		$this->resultCodes['R69'] = "Multiple Errors";
		$this->resultCodes['R70'] = "Permissible return entry not accepted";
		$this->resultCodes['R71'] = "Misrouted dishonored return";
		$this->resultCodes['R72'] = "Untimely dishonored return";
		$this->resultCodes['R73'] = "Timely original return";
		$this->resultCodes['R74'] = "Corrected return";
		$this->resultCodes['R80'] = "Cross-Border Payment Coding Error";
		$this->resultCodes['R81'] = "Non-Participant in Cross-Border Program";
		$this->resultCodes['R82'] = "Invalid Foreign Receiving DFI Identification";
		$this->resultCodes['R83'] = "Foreign Receiving DFI Unable to Settle";
		$this->resultCodes['R84'] = "Not a negotiable item";
		$this->resultCodes['R85'] = "Blocked account";
		$this->resultCodes['R86'] = "Not drawn on a US bank";
		$this->resultCodes['R87'] = "Cannot handle as cash item";
		$this->resultCodes['R88'] = "Credits only";
		$this->resultCodes['R89'] = "Return stamp unreadable";
		$this->resultCodes['R90'] = "Return item not stamped";
		$this->resultCodes['R99'] = "Unclear return";
		$this->resultCodes['D01'] = "Paper Draft - Insufficient funds";
		$this->resultCodes['D02'] = "Paper Draft - Uncollected funds";
		$this->resultCodes['D03'] = "Paper Draft - Guarantee endorsement";
		$this->resultCodes['D05'] = "Paper Draft - See check: Redeposit item";
		$this->resultCodes['D06'] = "Paper Draft - Stop Payment";
		$this->resultCodes['D07'] = "Paper Draft - Endorsement";
		$this->resultCodes['D08'] = "Paper Draft - Refer to maker";
		$this->resultCodes['D09'] = "Paper Draft - Account closed";
		$this->resultCodes['D10'] = "Paper Draft - Stale date";
		$this->resultCodes['D11'] = "Paper Draft - Forgery";
		$this->resultCodes['D12'] = "Paper Draft - Non-cash";
		$this->resultCodes['D13'] = "Paper Draft - Amount different";
		$this->resultCodes['D14'] = "Paper Draft - Signature";
		$this->resultCodes['D15'] = "Paper Draft - See check (check A/C)";
		$this->resultCodes['D16'] = "Paper Draft - Insufficient funds after 2nd attempt";
		$this->resultCodes['D17'] = "Paper Draft - Uncollected funds after 2nd attempt";
		$this->resultCodes['D18'] = "Paper Draft - Fraud";
		$this->resultCodes['D19'] = "Paper Draft - Counterfeit";
		$this->resultCodes['D20'] = "Paper Draft - No account found";
		$this->resultCodes['E99'] = "Error";

		$this->resultCodes['A'] = "Waiting for payment";
		$this->resultCodes['B'] = "Waiting for payment and confirmation";
		$this->resultCodes['C'] = "Payment received. Waiting for confirmation";
		$this->resultCodes['D'] = "Confirmation received. Waiting for payment";
		$this->resultCodes['E'] = "Payment received";
		$this->resultCodes['F'] = "Payment and confirmation received";
		$this->resultCodes['G'] = "Payment term expired";
		$this->resultCodes['H'] = "Payment term expired. Confirmation received";
		$this->resultCodes['I'] = "Payment term expired. Confirmation not received";
		$this->resultCodes['J'] = "Confirmation term expired. Payment received";
		$this->resultCodes['K'] = "Confirmation term expired. Payment not received";
		$this->resultCodes['L'] = "Submitter cancelled cash request";
		$this->resultCodes['M'] = "Submitter cancelled cash request";
		$this->resultCodes['Y'] = "Consumer was issued refund";
		$this->resultCodes['Z'] = "Consumer was issued refund";
				
		parse_str($bankInfo['bk_additional_id'],$bk_additional_vals);
			

		$network_user=$bk_additional_vals['nu'];
		$network_pass=$bk_additional_vals['np'];
					
		$this->url = "https://" . $network_user . ":" . $network_pass . "@www.tssecure2.com/amerinetlib/Transactionhostdom.asmx";
		$this->namespace = "https://www.tssecure2.com/amerinetlib/";

		$this->sSiteID=$bk_additional_vals['sid'];
		$this->sProductID=$bk_additional_vals['pid'];
		if($is_rebill)
		{
			$this->sSiteID=$bk_additional_vals['rsid'];
			$this->sProductID=$bk_additional_vals['rpid'];
		}
		else if($is_trial)
		{
			$this->sSiteID=$bk_additional_vals['tsid'];
			$this->sProductID=$bk_additional_vals['tpid'];
		}
	
		$this->Set_Merchant_Password($bankInfo['bk_username'],$bankInfo['bk_password']);
        $this->client = new SOAP_Client($this->url);
		$this->client->setOpt('curl',CURLOPT_SSL_VERIFYHOST,0);
		$this->client->setOpt('curl',CURLOPT_SSL_VERIFYPEER,0);
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
 
 	function convert_obj_array($object,&$res)
	{
		foreach($object as $key=>$value)
			if(is_array($value))
				foreach($value as $val)
					if(is_object($value))
						$res[$key][] = $this->convert_obj_array($val,$res);
					else
						$res[$key][] = $value;
			else
				if(is_object($value))
					$res[$key] = $this->convert_obj_array($value,$res);
				else
					$res[$key] = $value;
	}
	
 	function obj_array($object)
	{
		$res = array();
		$this->convert_obj_array($object,$res);
		$res['ResultCodeText'] = $res['ResultCode'] . ": " . $this->resultCodes[$res['ResultCode']];

		return $res;
	}
	
 	function Set_Merchant_Password($id,$password)
	{
		$this->nMerchantId = $id;
		$this->sMerchantPassword = $password;
	}
	
	function Execute_Function($function_Name,$params,$req_params)
	{
		$params['sSiteID'] = $this->sSiteID;
		$params['sProductID'] = $this->sProductID;
		$params["nMerchantID"]= intval($this->nMerchantId);
		$params["sMerchantPassword"] = $this->sMerchantPassword;
		
		$temp = array();
		foreach($params as $index => $value)
			$temp[strtolower($index)] = $value;

		$missing_params = "";
		$send_params = array();
		foreach($req_params as $index => $value)
			if(!isset($temp[strtolower($value)]))
				$missing_params .= $missing_params == "" ? $value : ", $value";
			else
				$send_params[$value] = $temp[strtolower($value)];

		if($missing_params != "")
			return array("missing parameters"=>$missing_params);
		
		return $this->client->call($function_Name,$send_params,array("namespace" => $this->namespace,"soapaction" =>$this->namespace.$function_Name));
	}
	
	function ExecutePeekUserExists($trans_ref_num)
	{
		$req_params = array(
		   "nMerchantID",
		   "sMerchantPassword",
		   "sUserDef"
   		);

        $params["sUserDef"] = $trans_ref_num;

		$res =  $this->Execute_Function("ExecutePeekUserExists",$params,$req_params);
		return $this->obj_array($res);
	}
	
    function ExecuteNoOp() 
	{
		$req_params = array(
			"nMerchantID",
			"sMerchantPassword"
   		);

		$res =  $this->Execute_Function("ExecuteNoOp",$params,$req_params);
		return $this->obj_array($res);
    }
 
 	function ExecuteValidation($params)
	{
		$req_params = array(
			"sUserDef",
			"sMicrLine",
			"sCheckNumber",
			"sCountryCodeMicr",
			"dblAmount",
			"sSiteID",
			"sProductID"
   		);

		$res = $this->Execute_Function("ExecuteValidation",$params,$req_params);
		return $this->obj_array($res);
	}
	
 	function ExecuteOrderVoid($params)
	{
		$req_params = array(
		   "nTransactionID",
		   "nMerchantID",
		   "sMerchantPassword"
   		);

		$res = $this->Execute_Function("ExecuteOrderVoid",$params,$req_params);
		return $this->obj_array($res);
	}
	
    function ExecuteOrderDebit($post_vars) 
	{
		$req_params = array(
			"sFirstName",
			"sLastName",
			"sSuffix",
			"sCompanyName",
			"sGender",
			"sStreetAddress",
			"sCity",
			"sStateAbbr",
			"sZip",
			"sCountry",
			"sPhone",
			"dtDateOfBirth",
			"sSocialSecurity",
			"sDriversLicense",
			"sDriversLicenseState",
			"sEmailAddress",
			"sUserDef",
			"sMicrLine",
			"sCheckNumber",
			"sCheckType",
			"sCountryCodeMicr",
			"dblAmount",
			"nCycleCount",
			"sCycleFrequency",
			"nCycleFrequencyMultiplier",
			"dblFirstPayAdd",
			"nTypeCode",
			"sCheckSource",
			"sSiteID",
			"sProductID"
		);
			
		$res =  $this->Execute_Function("ExecuteOrderDebit",$params,$req_params);
		return $this->obj_array($res);
    }
	
	function ExecuteOrderCredit($params)
	{
		$req_params = array(
			"sFirstName",
			"sLastName",
			"sSuffix",
			"sCompanyName",
			"sGender",
			"sStreetAddress",
			"sCity",
			"sStateAbbr",
			"sZip",
			"sCountry",
			"sPhone",
			"dtDateOfBirth",
			"sSocialSecurity",
			"sDriversLicense",
			"sDriversLicenseState",
			"sEmailAddress",
			"sUserDef",
			"sMicrLine",
			"sCheckNumber",
			"sCheckType",
			"sCountryCodeMicr",
			"dblAmount",
			"sCheckSource",
			"sSiteID",
			"sProductID"
		);

		$res = $this->Execute_Function("ExecuteOrderCredit",$params,$req_params);
		return $this->obj_array($res);
	}
}


?>