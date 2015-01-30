<?
require_once("subFunctions/http.post.php");

function execute_refund($transInfo,$reason,$mode='Live')
{
	global $cnn_cs;
	global $etel_debug_mode;
	
	$reason = addslashes($reason);
	
	$etel_debug_mode=0;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	$gw_emails_sales = $_SESSION['gw_emails_sales'];
	
	$int_table = "cs_transactiondetails";
	$trans_id = $transInfo['transactionId'];

	if(!$transInfo['userId']) {$response['errormsg'] = "Invalid Merchant Id"; return $response;}

	if(!$transInfo['checkorcard'])  {$response['errormsg'] = "Invalid Payment Type"; return $response;}

	//if($transInfo['checkorcard'] == 'H') $company_bank_id ='c.bank_Creditcard';
	//if($transInfo['checkorcard'] == 'C') $company_bank_id ='c.bank_check';
	//if($transInfo['checkorcard'] == 'W') $company_bank_id ='c.cd_web900bank';

	$company_bank_id = $transInfo['bank_id'];
	
	$sql="SELECT * FROM `cs_companydetails` as c left join `cs_company_sites` as s on s.cs_company_id = c.`userId` WHERE c.`userId` = '".$transInfo['userId']."' and s.`cs_ID` = '".$transInfo['td_site_ID']."'";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	if(mysql_num_rows($result)<1 && $mode=="Live") {$response['errormsg'] = "Invalid Company/Website"; return $response;}
	$companyInfo = mysql_fetch_assoc($result);	
	
	$sql="SELECT b.* FROM `cs_bank` as b left join `cs_companydetails` as c on b.bank_id = $company_bank_id WHERE c.`userId` = '".$transInfo['userId']."' ";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	if(mysql_num_rows($result)<1 && $mode=="Live") {$response['errormsg'] = "Invalid Bank"; return $response;}
	$bankInfo = mysql_fetch_assoc($result);
	if(!$bankInfo['bk_int_function'] && $mode=="Live") {$response['errormsg'] = "Invalid Bank Integration"; return $response;}
	$transInfo['companyname'] = $companyInfo['companyname'];
	$transInfo['cs_enable_passmgmt'] = $companyInfo['cs_enable_passmgmt'];
	$transInfo['reason'] = $reason;
	$int_op = $bankInfo['bk_int_refund_function'];
	if (function_exists($int_op)) $response = $int_op(&$transInfo,&$bankInfo,&$companyInfo);
	else
	{
		$response['errormsg'] = "Integration Function '$int_op' not found";
		$response['success'] = false;
	}
	toLog('order','customer',"Attempting to refund transaction ID ".$transInfo['transactionId'].". Query = ".$response['td_process_query'].". Response = ".$response['td_process_result'].".",$transInfo['transactionId']);
	
	$transInfo['td_process_result']=$response['td_process_result'];
	$transInfo['td_process_query']=$response['td_process_query'];
	$transInfo['cancelstatus']=$response['cancelstatus'];
	if(!$transInfo['cancelstatus']) $transInfo['cancelstatus'] = 'N';
	if($response['success'])
	{
		$ref_number = strtoupper("R".substr(md5(time()),0,4).$trans_id.substr(md5(rand(0,100000)),0,4));
		
		$qrt_update_details = "update $int_table 
		set `td_process_result` = CONCAT(`td_process_result`,'\n".$transInfo['td_process_result']."'),
		`td_process_query` = CONCAT(`td_process_query`,'\n".$transInfo['td_process_query']."'),
		`cancelstatus` = '".$response['cancelstatus']."',
		`td_is_chargeback` = '0',
		`td_bank_deducted`=1,
		`td_merchant_deducted`=1,
		`td_reseller_deducted`=1,
		`cancellationDate` = NOW(),
		`reason` = '$reason', 
		`cancel_refer_num` = '$ref_number'
		 WHERE transactionId = '".$transInfo['transactionId']."'";
		//die($qrt_update_details);
		$qrt_update_details =mysql_query($qrt_update_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>".$qrt_update_details);

		// Email
		$useEmailTemplate = 'customer_refund_confirmation';
		$data['site_URL'] = $companyInfo['cs_URL'];
		$data['reference_number'] = $transInfo['reference_number'];
		$data['full_name'] = $transInfo['name']." ".$transInfo['surname'];
		$data['email'] = $transInfo['email'];
		$data['cancel_reference_number'] = $ref_number;
		$data["gateway_select"] = $companyInfo['gateway_id'];
		send_email_template($useEmailTemplate,$data);

		$data['email'] = $bankInfo['bank_email'];
		if($bankInfo['bk_cc_bank_enabled']) send_email_template($useEmailTemplate,$data,"(Bank Copy) ");

		if($companyInfo['cd_recieve_order_confirmations'])
		{	
			$data['email'] = $companyInfo['cd_recieve_order_confirmations'];
			send_email_template($useEmailTemplate,$data,"(Merchant Copy) ");
		}
	}
	if (!$response['errormsg']) $response['errormsg']=$response['td_process_result'];
	return $response;
}


function gkard_refund_integration_052($transInfo,$bankInfo,$companyInfo)
{
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	
	$additional_array = explode('|',$bankInfo['bk_additional_id']);
	parse_str($transInfo['td_process_result'],$old_result);
	
	$Pinfo="";
	$Pinfo.="CID=".$additional_array[0];
	$Pinfo.="&CUSR=".$bankInfo['bk_username'];
	$Pinfo.="&CPWD=".$bankInfo['bk_password'];
	
	$Pinfo.="&FUNC=052";
	$Pinfo.="&MSGID=".rand(0,400000000);
	$Pinfo.="&P1=".urlencode(etelDec($transInfo['td_gcard']));//	The credit card number. (dashes allowed)	ALL	(16,20) AN	y
	$Pinfo.="&P2=".$transInfo['amount']; 
	$Pinfo.="&P3=".$old_result['P15'];
	$Pinfo.="&P4=".$transInfo['name']." ".$transInfo['surname']; 
	$Pinfo.="&P5=".urlencode($transInfo['address']); 		//	Cardholder’s street address	ALL	AN, variable	y
	$Pinfo.="&P6=".urlencode($transInfo['city']); 		//	Cardholder’s city 	ALL	AN, variable	y
	$Pinfo.="&P7=".urlencode($transInfo['state']); 		//	Cardholder’s state/province	ALL	AN, variable	y
	$Pinfo.="&P8=".$cust_cntry; 			//	Cardholder’s zip or postal code	ALL	AN, variable	y
	$Pinfo.="&P9=".$transInfo['td_bank_transaction_id'];

//https://trans.symmetrex.com//i24card/i24cardapi?CID=5&CUSR=myuseraccount&CPWD=passwordassignedtome&FUNC=IssueCredit&MSGID=00005539228226466&transid=w#######
	// Uncomment below for live
	$output_url = "https://trans.symmetrex.com/i24card/i24cardapi?".$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);
	die($output_url);
	$process_result = http_post('ssl://trans.symmetrex.com', 443, $output_url, $Pinfo);
	//$process_result = "CAPTURED:000000:57127438:NA:Aug 13 2005:95:NLS:NLS:NLS:57212065:57127438:CARD LOAD:4185867567056685:28-1006:57212064:credit to electron card: 0";
	//$parsed_result = explode(":",$process_result);
	
	$response['td_process_result'] = $process_result;

	$response['td_process_query'] = $output_url;
	$response['cancelstatus'] = 'N';
	$response['success'] = false;
	
	if($P2=="Success")
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	
	return $response;
}

function gkard_refund_integration_old($transInfo,$bankInfo,$companyInfo)
{
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	
	$additional_array = explode('|',$bankInfo['bk_additional_id']);
	parse_str($transInfo['td_process_result'],$old_result);
	
	$Pinfo="";
	$Pinfo.="CID=".$additional_array[0];
	$Pinfo.="&CUSR=".$bankInfo['bk_username'];
	$Pinfo.="&CPWD=ibillgk";//.$bankInfo['bk_password'];
	
	$Pinfo.="&FUNC=IssueCredit";
	$Pinfo.="&MSGID=".rand(0,400000000);
	$Pinfo.="&transid=".$transInfo['td_bank_transaction_id'];

//https://trans.symmetrex.com//i24card/i24cardapi?CID=5&CUSR=myuseraccount&CPWD=passwordassignedtome&FUNC=IssueCredit&MSGID=00005539228226466&transid=w#######
	// Uncomment below for live
	$output_url = "https://trans.symmetrex.com/i24card/i24cardapi?".$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);
	
	$process_result = http_post('ssl://trans.symmetrex.com', 443, $output_url, $Pinfo);
	//$process_result = "CAPTURED:000000:57127438:NA:Aug 13 2005:95:NLS:NLS:NLS:57212065:57127438:CARD LOAD:4185867567056685:28-1006:57212064:credit to electron card: 0";
	//$parsed_result = explode(":",$process_result);
	
	parse_str($process_result,$new_result);
	$response['td_process_result'] = $process_result;

	$response['td_process_query'] = $output_url;
	$response['cancelstatus'] = 'N';
	$response['success'] = false;
	
	if($new_result['P2']=="Success")
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	
	return $response;
}


function gkard_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	parse_str($transInfo['td_process_result']);
	if($P15) $expDate=$P15;
	
	$additional_array = explode('|',$bankInfo['bk_additional_id']);
	
	
	$Pinfo="";
	$Pinfo.="vid=".$additional_array[1]; 									//	Vendor ID – A processing profile. 	ALL	AN	y
	$Pinfo.="&password=".$additional_array[2]; 							//	Password for authenticating the VID.	ALL	AN	y
	$Pinfo.="&action=2"; 									//	Transaction Action Type	ALL	N	y
	$Pinfo.="&trackid=".urlencode($transInfo['td_bank_transaction_id']); 		//	The tracking identification number.  	 	ALL	N	y
	$Pinfo.="&card=".urlencode(etelDec($transInfo['td_gcard']));//	The credit card number. (dashes allowed)	ALL	(16,20) AN	y

	//if($expDate)
		$Pinfo.="&exp=".urlencode($expDate); 			//	Expiration date (format: MMYY)	ALL	(4) N	y
	//else 
	//	$Pinfo.="&exp=1208"; 			//	Expiration date (format: MMYY)	ALL	(4) N	y

	$Pinfo.="&amount=".round($transInfo['amount']*100); 		//	Amount in minor units, always an integer.  (e.g. cent 100 = $1.00)	ALL	N	y
	$Pinfo.="&member=".urlencode($transInfo['name']." ".$transInfo['surname']); 		//	Carholder’s full name	ALL	AN, variable	y
	$Pinfo.="&addr=".urlencode($transInfo['address']); 		//	Cardholder’s street address	ALL	AN, variable	y
	$Pinfo.="&city=".urlencode($transInfo['city']); 		//	Cardholder’s city 	ALL	AN, variable	y
	$Pinfo.="&state=".urlencode($transInfo['state']); 		//	Cardholder’s state/province	ALL	AN, variable	y
	$Pinfo.="&zip=".urlencode($transInfo['zipcode']); 			//	Cardholder’s zip or postal code	ALL	AN, variable	y
	$Pinfo.="&country=".urlencode($cust_cntry); 		//	Cardholder’s country	ALL	AN, variable	y
	$Pinfo.="&cardip=".urlencode($transInfo['ipaddress']); 		//	Cardholder’s ip number	ALL	AN, variable	y
	$Pinfo.="&email=".urlencode($transInfo['email']); 		//	Cardholder’s email address. Must be verifiable.	ALL	AN, variable	y
	$Pinfo.="&version=".urlencode('4.6.2”'); 		//	API Version of document – “4.6.2”. Your request may not be processed properly without the version data element included.	ALL	AN, variable	y
	$Pinfo.="&transid=".urlencode($transInfo['td_bank_transaction_id']); 		//	The parent transactions transaction id.  See Parent/Child transaction relationship.	2,3,5,6,7,8	N	y
	//$Pinfo.="&ref=".urlencode($transInfo['td_bank_transaction_id']); 		//	The parent transactions transaction id.  See Parent/Child transaction relationship.	2,3,5,6,7,8	N	y


	// Uncomment below for live
	$output_url = "https://trans.symmetrex.com/i24gateway/securecharge?".$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	$process_result = http_post('ssl://trans.symmetrex.com', 443, $output_url, $Pinfo);
	//$process_result = "CAPTURED:000000:57127438:NA:Aug 13 2005:95:NLS:NLS:NLS:57212065:57127438:CARD LOAD:4185867567056685:28-1006:57212064:credit to electron card: 0";

	$parsed_result = explode(":",$process_result);
	
	$response['td_process_result'] = $process_result;

	$response['td_process_query'] = $output_url;
	$response['cancelstatus'] = 'N';
	$response['success'] = false;
	if($parsed_result[0]=='CAPTURED')
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	
	return $response;
}

function cc_Discover_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	
	global $etel_fraud_limit;
	require_once("subFunctions/banks.discover.php");
	$response="";
	$response['errormsg'] = "Transaction could not be Refunded.";
	
	$params['OriginalTransactionId'] = $transInfo['td_bank_transaction_id'];

	$processor = new Discover_Client($bankInfo,"Live");
	$process_result = $processor->Refund($params);
	$response['td_process_result'] = $process_result;

	$response['td_process_query'] = $process_result['post_url'];
	$response['cancelstatus'] = 'N';
	$response['success'] = false;
	$response['errormsg'] = $process_result['RCString'];
	if($process_result['RCString']=='APPROVED')
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	else
	{
		$status_result = $processor->Transaction_Status($params);
		$response['errormsg'] = "Failed to Refund";
		if($status_result['RCString']=='REFUNDED')
		{
			$response['cancelstatus'] = 'Y';
			$response['success'] = true;
			$response['errormsg'] = $status_result['RCString'].": Already Refunded";
		}
	}
	
	return $response;
}

function forceTronix_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be Refunded.";

	$Pinfo="";
	$Pinfo.="InternalOrderId=".$transInfo['reference_number']; 				//  (Your own unique identifier for this purchase)
	$Pinfo.="&MerchantId=".$bankInfo['bk_username']; 		//  (The EGE unique identifier for the account)
	$Pinfo.="&Secret=".$bankInfo['bk_additional_id']; 			//  (The EGE security code for the account)
	$Pinfo.="&TransType=3"; 		//  (The Transaction Code)
	
	$output_url = "https://eccpro.com/author.php";

	$process_result = http_post('ssl://eccpro.com', 443, $output_url, $Pinfo);

	$xml = xml2array( $process_result);

	$statusCode = $xml['ECCPro']['Response']['StatusCode'];
	$ProcessStatusText = $xml['ECCPro']['Response']['ProcessStatusText'];

	$response['errormsg'] = $ProcessStatusText;
	
	$response['success'] = false;
	$response['cancelstatus'] = 'N';
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_process_result'] = $process_result;
	if($statusCode == "000") 
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	return $response;
}

function avantPay_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	$response="";
	$response['errormsg'] = "Transaction could not be Refunded.";
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	
	//foreach($transInfo as $key=>$data)
	//	$transInfo[$key] = urlencode($data);
		
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	$cust_state = urlencode(func_get_state($transInfo['state'],'st_abbrev'));
	if(strlen($cust_state)>2)$cust_state="";
	
	$cardtype = "VI";	
	if(strtolower($transInfo['cardtype'])=='master') $cardtype = 'MC';
	$cardholder = $transInfo['name']." ".$transInfo['surname'];
		
	$output_url = "https://secure.avantpay.com/webservices/0_9/payment.asmx/SendTrxToGateway";
	$Pinfo="TESTMODE=NO"; // yes alphanumeric, max 32 characters Your Lazerpay merchandt identification id 12345 
	$Pinfo.="&TXNTYPE=CREDIT"; // yes alphanumeric, max 40 characters Your Lazerpay secret merchant key IMPORTANT: Keep this secret key hidden. Do not show it on your website. Make sure that it is not stated in the html code viewable to the clients. v7iTT5yq6_66eQ 
	$Pinfo.="&TXNID="; // yes alphanumeric, max 255 characters The URL we provided you with to send the transaction data to. https://merchants.lazerpay.com/api/processing.cfm 

	$Pinfo.="&MCHTTXNID=".$transInfo['reference_number']; // 	yes 	tr_amount
	$Pinfo.="&MCHTID=".$bankInfo['bk_additional_id']; // 	yes 	tr_amount
	$Pinfo.="&CCHOLDERNAME=".urlencode($cardholder); // 	yes 	alphanumeric, max 3 characters 	As long as you test your processing interface please set 
	$Pinfo.="&CCTYPE=".$cardtype; // 	yes 	alphanumeric, max 20 characters 	Type of Credit Card, Visa or MasterCard 	Visa 	
	$Pinfo.="&CCNUMBER=".$transInfo['CCnumber']; // 	yes 	numeric, max 16 digits 	Credit Card Number 	4111111111111111 	
	$Pinfo.="&CCEXPMONTH=".$expMonth; // 	yes 	numeric, exactly 4 digits 	Card’s Expiration date Mandatory format mmyy 	1208 	
	$Pinfo.="&CCEXPYEAR=20".$expYear; // 	yes 	numeric, exactly 4 digits 	Card’s Expiration date Mandatory format mmyy 	1208 	
	$Pinfo.="&CVV2=".$transInfo['cvv']; // 	yes 	numeric, max 4 digits 	Control Number on the reverse side of card 	123 	
	$Pinfo.="&DESCRIPTION=".str_replace("http://","",$companyInfo['cs_URL']).":Description-".$transInfo['productdescription']; // 	no 	alphanumeric, max 200 characters 	The name / ID of the Submerchant this transaction is being processed for. 	www.webmerchant.com 	
	$Pinfo.="&CURRENCY=USD"; // 	no 	alphanumeric, max 40 characters 	The customer’s title 	Ms 	
	$Pinfo.="&FIRSTNAME=".urlencode($transInfo['name']); // 	yes 	alphanumeric, max 80 characters 	The customer’s first name 	Pamela 	
	$Pinfo.="&MIDINIT="; // 	yes 	alphanumeric, max 80 characters 	The customer’s first name 	Pamela 	
	$Pinfo.="&LASTNAME=".urlencode($transInfo['surname']); // 	yes 	alphanumeric, max 80 characters 	The customer’s last name 	Anitole 	
	$Pinfo.="&STREET1=".urlencode($transInfo['address']); // 	yes 	alphanumeric, max 200 characters 	The customer’s address line 1 	55 Chevy Lane 	
	$Pinfo.="&STREET2=".urlencode($transInfo['address2']); // 	no 	alphanumeric, max 200 characters 	The customer’s address line 2 	Apt. 2020 	
	$Pinfo.="&CITY=".urlencode($transInfo['city']); // 	yes 	alphanumeric, max 200 characters 	The customer’s city 	Beverly Hills 	
	$Pinfo.="&STPROVINCE=".$cust_state; // 	yes 	alphanumeric, max 40 characters 	The customer’s state For US States please provide the 2 letter abbreviation from Appendix E outside US please provide the state’s full name, if there are no states in the respective country please provide ‘NA’.  This field cannot be left blank. 	CA 	
	$Pinfo.="&POSTCODE=".$transInfo['zipcode']; // 	yes 	alphanumeric, max 12 characters 	The customer’s zip code 	99054 	
	$Pinfo.="&COUNTRY=".$cust_cntry; // 	yes 	alphanumeric, exactly 2 characters 	The customer’s country For the USA please provide ‘US’, outside of the US please provide the 2 letter code from Appendix C 	US 	
	$Pinfo.="&HOMEPHONE=".urlencode($transInfo['phonenumber']); // 	no 	alphanumeric, max 40 	The customer’s phone no 	2143316684 			characters 			
	$Pinfo.="&CELLPHONE=".$transInfo['']; // 	no 	alphanumeric, max 80 characters 	The customer’s cell phone no 		
	$Pinfo.="&EMAIL=".$transInfo['email']; // 	yes 	alphanumeric, max 80 characters 	The customer’s email address 	pammya@yahoo.com 	
	$Pinfo.="&TOTALAMT=".number_format($transInfo['amount'],2,'.',''); // 	yes 	tr_amount

	//$process_result = http_post('ssl://secure.avantpay.com', 443, $output_url, $Pinfo);
	//$process_result = file_get_contents($output_url."?". $Pinfo);
	
	toLog('order','customer',"Refund '".$transInfo['reference_number']."' Sending Request to Bank: ".$output_url."?".$Pinfo);
	
	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$Pinfo);
	curl_setopt($ch, CURLOPT_URL,$output_url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
	$result=curl_exec ($ch);
	curl_close ($ch);
	
	$process_result = trim($result);
	
	toLog('order','customer',"Pending Transaction '".$transInfo['reference_number']."' Recieved from Bank: ".$process_result);
	
	$response['td_process_result'] = $process_result;

	$xml = xml2array($process_result);

	$status = $xml['PROCESS_RESPONSE']['PROCESS_RESULT']['TXN_STATUS'];
	$ProcessStatusText = $xml['PROCESS_RESPONSE']['PROCESS_RESULT']['TXN_MSG'];
	$ProcessOrderID = $xml['PROCESS_RESPONSE']['PROCESS_RESULT']['TXN_ID'];
	
	$response['success'] = true;
	$response['status'] = "D";
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_bank_recieved'] = 'yes';
	if($xml['h1']) $response['td_bank_recieved'] = 'internalerror';
	if(!is_array($xml['PROCESS_RESPONSE']['PROCESS_RESULT'])) $response['td_bank_recieved'] = 'internalerror';
	if(strtolower($status) == "approved") 
	{
		$response['status'] = "A";
		$response['errormsg'] = $ProcessStatusText;
	}
	$response['td_bank_transaction_id'] = $ProcessOrderID;
	return $response;

}

function lazerPay_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	$response['cancelstatus'] = 'Y';
	$response['success'] = true;
		// Email
	$useEmailTemplate = 'customer_refund_confirmation';
	$data['site_URL'] = $companyInfo['cs_URL'];
	$data['reference_number'] = $transInfo['reference_number'];
	$data['full_name'] = $transInfo['name']." ".$transInfo['surname'];
	$data['email'] = "support@lazerpay.com";
	$data['cancel_reference_number'] = $ref_number;
	$data["gateway_select"] = $companyInfo['gateway_id'];
	send_email_template($useEmailTemplate,$data,"(LP Copy) Please Refund ".$data['reference_number']." - ");

	return $response;

}


function EuroPay_refund_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;
		

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));

	//foreach($transInfo as $key => $info)
		//$transInfo[$key] = urlencode($info);

	$cardtype = strtolower($transInfo['cardtype']);	
	
	//$bank_trans_id = rand(0,400000000);
	//$response['td_bank_transaction_id'] = $bank_trans_id;
	
	foreach($transInfo as $key => $item)
		$transInfo[$key] = urlencode($item);

	$Pinfo="";  //  this is an unique ID defined by our system
	$Pinfo.="ResellerID=".$bankInfo['bk_additional_id'];  //  this is an unique ID defined by our system
	$Pinfo.="&MerchantSign=".$bankInfo['bk_username'];  //  also an unique ID defined by our System
	$Pinfo.="&MerchantPassword=".$bankInfo['bk_password'];  //  a password you can specify
	$Pinfo.="&ReferenceNumber=".$transInfo['reference_number'];  //  a (unique) reference transaction number of your system
	$Pinfo.="&TransType=credit";  //  the type of transaction you want to process 
	$Pinfo.="&TransactionID=".$transInfo['td_bank_transaction_id'];  //  our reference transactionID of the sale or auth transaction 
	$Pinfo.="&CreditCardNumber=".$transInfo['CCnumber'];  //  the credit card number you want to bill
	$Pinfo.="&CreditCardType=".$cardtype;  //  the type of the credit card. master and visa is supported  
	$Pinfo.="&CreditCardCVVCode=".$transInfo['cvv'];  //  the 3 digit number code on the back side of the credit card 
	$Pinfo.="&CreditCardExpireMonth=".$expMonth;  //  expire month of the credit card
	$Pinfo.="&CreditCardExpireYear=20".$expYear;  //  expire year of the credit card
	$Pinfo.="&Amount=".number_format($transInfo['amount'],2,".",",");  //  amount of the transaction
	$Pinfo.="&Currency=USD";  //  currency of the transaction. Available currencies are EUR, USD  
	$Pinfo.="&CardholderFirstName=".$transInfo['name'];  //  card holder first name
	$Pinfo.="&CardholderLastName=".$transInfo['surname'];  //  card holder last name
	$Pinfo.="&CardholderAddress=".$transInfo['address'];  //  card holder address
	$Pinfo.="&CardholderZipCode=".$transInfo['zipcode'];  //  card holder zip code
	$Pinfo.="&CardholderCity=".$transInfo['city'];  //  card holder city
	$Pinfo.="&CardholderState=".$transInfo['state'];  //  card holder state (ISO 3166/2)
	$Pinfo.="&CardholderCountry=".$transInfo['country'];  //  card holder country (ISO 3166/2)
	$Pinfo.="&CardholderEmail=".$transInfo['email'];  //  card holder email address
	$Pinfo.="&CardholderIPAddress=".$transInfo['ipaddress'];  //  cardholder IP address
	$Pinfo.="&MerchantInfo=www.maturebill.com;support@maturebill.com;";  //  Please provide us following information always in following format: 
	$Pinfo.="&OrderInfo=".$transInfo['productdescription'];  //  an optional info field 
	
	
	$output_url = "https://paygate.epg-1.com/cc3/start_transaction.php";
	
	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$Pinfo);
	curl_setopt($ch, CURLOPT_URL,$output_url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
	$result=curl_exec ($ch);
	curl_close ($ch);
	
	$process_result = trim($result);
	$clean_data = str_replace(";","&",trim($process_result));

	parse_str($clean_data,$resultAr);
	
	$response['success'] = false;
	$response['cancelstatus'] = 'N';
	$response['errormsg'] = $resultAr['ResultText'];
	$response['errorcode'] = $resultAr['ResultCode'];
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_process_result'] = $process_result;
	if ($resultAr['Result']=="OK")
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	return $response;
}

function cc_iPayGate_refund($transInfo,$bankInfo,$companyInfo)
{
	require_once("subFunctions/banks.ipaygate.php");
	require_once("SOAP/Client.php");
	
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";
		
	$processor = new iPayGate_Client($bankInfo);
	//$orig_query = explode("?",$transInfo['td_process_query']);
	$orig_result = unserialize(str_replace('[credit_card]',etelDec($transInfo['CCnumber']),$transInfo['td_process_result']));

	$params = array(
		"customerPaymentPageText" => strval($orig_result['CUSTOMERPAYMENTPAGETEXT']),
		"orderDescription" => strval($orig_result['ORDERDESCRIPTION']), 
		"refundamount" => strval($transInfo['amount']), 
		"referralorderreference" => strval($orig_result['ORDERREFERENCE']),
		"comment1"=>strval(substr($transInfo['reason'],0,249))
	);
	
	$process_result = $processor->Execute_Refund($params);
	
	$response['success'] = false;
	$response['cancelstatus'] = 'N';
	$response['errormsg'] = $process_result['TRANSACTIONSTATUSTEXT'].': '.$process_result['ERRORMESSAGE'];
	$response['errorcode'] = $process_result['ERRORCODE'];
	$response['td_process_query'] = serialize($params);
	$response['td_process_result'] = serialize($process_result);
	if ($process_result['RESPONSECODE'] == '000' || $process_result['TRANSACTIONSTATUSTEXT'] == 'SUCCESSFUL')
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	return $response;		
}

function cc_Discover_refund($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("subFunctions/banks.discover.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	if (stristr($bankInfo['bk_trans_types'],"discover")===FALSE)
	{
		$response['errormsg'] = "This bank does not support Discover Cards. Please contact an administrator."; 
		return $response;
	}

	$params['OriginalTransactionId'] = $transInfo['td_bank_transaction_id'];
	
	$processor = new Discover_Client($bankInfo,"test");
	$process_result = $processor->Refund($params);
	
	$response['success'] = false;
	$response['cancelstatus'] = 'N';
	$response['errormsg'] = $process_result['RCString'];
	$response['errorcode'] = $process_result['RC'];
	$response['td_process_query'] = "";
	$response['td_process_result'] = serialize($process_result);
	if ($process_result['RC'] == '0')
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	return $response;	
		
}

function ch_AmeriNet_refund($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("SOAP/Client.php");
	require_once("subFunctions/banks.amerinet.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	if ($bankInfo['bk_ch_support']!=1)
	{
		$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; 
		return $response;
	}

	$processor = new AmeriNet_Client($bankInfo,$transInfo['td_is_a_rebill'],(intval($transInfo['amount'])<10));
	
	$params['nTransactionID'] = $transInfo['td_bank_transaction_id'];

	$processor = new AmeriNet_Client($bankInfo);
	$process_result = $processor->ExecuteOrderVoid($params);
	
	$response['success'] = false;
	$response['cancelstatus'] = 'N';
	$response['errormsg'] = $process_result['ResultCodeText'];
	$response['errorcode'] = $process_result['ResultCode'];
	$response['td_process_query'] = "";
	$response['td_process_result'] = serialize($process_result);
	if ($process_result['ResultCode'] == '0101')
	{
		$response['cancelstatus'] = 'Y';
		$response['success'] = true;
	}
	return $response;		
}

?>