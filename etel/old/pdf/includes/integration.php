<?php
require_once("processor.class.php");
require_once("transaction.class.php");
require_once("fraud.class.php");
require_once("subFunctions/http.post.php");
//require_once('matureIntegration.php');

function execute_transaction($transInfo,$mode)
{
	$processor = new processor_class($mode);
	return $processor->process_transaction($transInfo);
}

function servpay_integration(&$transInfo,&$bankInfo,&$companyInfo)
{
	require_once('lib/nusoap/lib/nusoap.php'); 
	require_once("subFunctions/banks.servpay.php");
	
	$processor = new servpay_Client();
	return $processor->Execute_Sale(&$transInfo,&$bankInfo,&$companyInfo);
}

function intabill_integration(&$transInfo,&$bankInfo,&$companyInfo)
{
	global $etel_fraud_limit;
		
	require_once("SOAP/Client.php");
	require_once("subFunctions/banks.intabill.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$MID = "ca7e5842-dd33-1029-bf92-0013723bba63";
	//if($bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']]) $TID = $bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']];

	$DESC = "www.pay-support.com 1-800-511-2457";
	//if($bankInfo['cb_config']['custom']['desc_sites'][$transInfo['td_site_ID']]) $DESC = $bankInfo['cb_config']['custom']['desc_sites'][$transInfo['td_site_ID']];

	//$transInfo['billing_descriptor']=$DESC;

	$cust_cntry = $transInfo['country'];//urlencode(func_get_country($transInfo['country'],'co_full'));
	$transInfo['cardtype'] = 'Visa';
	if(substr($transInfo['CCnumber'],0,1) == '5') $transInfo['cardtype'] = 'Mastercard';
	if(!$transInfo['state']) $transInfo['state'] = 'a';
	$params = array(
	"merchantId" => strval($MID), 						// Mandatory   xsd:string    Your merchant ID to process this transaction with (eg: abcde-abcde-abcd-adcde-abcdefg) 
	"IPAddress" => strval($transInfo['ipaddress']), 	// Mandatory   xsd:string    The IP address of the customer processing this transaction (i.e. 200.123.456.789)  
	"submittedAmount" => doubleval($transInfo['amount']), // Mandatory   xsd:double   The amount of the transaction (for example, for $10.00 USD: 10.00)   
	"merchantReference" => strval($transInfo['reference_number']), 	// Mandatory   xsd:string    Your reference for this transaction, such as product ID or type.  
	"clientFirstname" => strval($transInfo['name']), 	// Mandatory   xsd:string    First name of the customer   
	"clientSurname" => strval($transInfo['surname']), 	// Mandatory   xsd:string    Last name of the customer   
	"clientPhone" => strval($transInfo['phonenumber']), // Mandatory   xsd:string    Customer’s phone number  
	"clientStreet" => strval($transInfo['address']), 	// Optional   xsd:string    Customer’s street address  
	"clientCity" => strval($transInfo['city']), 		// Optional   xsd:string    Customer’s city  
	"clientState" => strval($transInfo['state']), 		// Optional   xsd:string    Customer’s state (two-letter state code for U.S.A. customers)  
	"clientCountry" => strval($cust_cntry), 			// Mandatory   xsd:string    Customer’s ISO 3166 two-letter country code (i.e. US)  
	"clientPostcode" => strval($transInfo['zipcode']), 	// Mandatory   xsd:string    Customer’s zip code   
	"clientEmail" => strval($transInfo['email']), 		// Mandatory   xsd:string    Customer’s email address  
	"product" => strval($transInfo['productdescription']), 	// Mandatory   xsd:string    Product  name or description of product (eg, Viagra).  
	"cardNumber" => strval($transInfo['CCnumber']), 	// Mandatory   xsd:string    Credit card number (i.e. 4111111111111111)  
	"cardName" => strval($transInfo['name'] . " " . $transInfo['surname']), 		// Mandatory   xsd:string    The name that appears on the customer’s credit card (i.e. John Doe)  
	"expiryMonth" => strval($expMonth), 				// Mandatory   xsd:int   The expiry month (i.e. 10 for October)   
	"expiryYear" => strval($expYear), 					// Mandatory   xsd:int  The expiry year (i.e. 2008)  
	"cardCVV" => strval($transInfo['cvv']) 			// Mandatory   xsd:string  CVV/security code (i.e. 506)  
	);
	if(strlen($params['clientState'])!=2)	$params['clientState'] = 'ZZ';
	$processor = new intabill_Client($bankInfo);
	$process_result = (array)$processor->Execute_Sale($params);

	//print_r($process_result);
	$response=NULL;
	
	$response['errormsg'] = $process_result['Message'];
	if(!isset($process_result['Status'])) $response['errormsg'] = "Declined (SoEx)";
	else $response['td_process_msg'] = $process_result['Code'] . ": " . $process_result['Message'];
	
	$response['td_bank_transaction_id'] = $process_result['TransactionID'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = serialize($params);
	$response['td_bank_recieved'] = 'yes';
	if(in_array($process_result['Status'],array('5','4','2'))) 
		$response['td_bank_recieved'] = 'no';
	$response['status'] = "D";
	if($process_result['Status'] == '1')
	{
		$response['status'] = "A";
		$response['td_bank_recieved'] = 'yes';
	}
	return $response;
}

function tri_hub_integration(&$transInfo,&$bankInfo,&$companyInfo)
{

	$type = 'VISA';
	if($transInfo['cardtype'] == 'Mastercard') $type = 'MCRD';
	if($transInfo['cardtype'] == 'JCB') $type = 'JCBC';
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];
	$exp = $expMonth.$expYear;
	
	$Pinfo="";
	$Pinfo.="websiteID=".urlencode($transInfo['']); // 
	$Pinfo.="&password=".urlencode($transInfo['']); // 
	$Pinfo.="&orderID=".urlencode($transInfo['reference_number']); // 
	$Pinfo.="&customerIP=".urlencode($transInfo['ipaddress']); // 
	$Pinfo.="&orderAmount=".round($transInfo['amount']*100); // 
	$Pinfo.="&currency=USD"; //.urlencode($transInfo['']); // 
	$Pinfo.="&cardHolderName=".urlencode($transInfo['name']." ".$transInfo['surname']); // 
	$Pinfo.="&cardHolderAddress=".urlencode($transInfo['address']); // 
	$Pinfo.="&dardHolderZipcode=".urlencode($transInfo['zipcode']); // 
	$Pinfo.="&cardHolderCity=".urlencode($transInfo['city']); // 
	$Pinfo.="&cardHolderState=".urlencode($transInfo['state']); // 
	$Pinfo.="&cardHolderCountryCode=".urlencode($transInfo['country']); // 
	$Pinfo.="&cardHolderPhone=".urlencode($transInfo['phonenumber']); // 
	$Pinfo.="&cardHolderEmail=".urlencode($transInfo['email']); // 
	$Pinfo.="&cardNumber=".$transInfo['CCnumber']; // 
	$Pinfo.="&cardType=".$type; // 
	$Pinfo.="&cardSecurityCode=".urlencode($transInfo['cvv']); // 
	$Pinfo.="&cardExpireMonth=".urlencode($expMonth); // 
	$Pinfo.="&cardExpireYear=".urlencode($expYear); // 
	$Pinfo.="&userVar1=".urlencode($companyInfo['cs_name']); // 
	$Pinfo.="&userVar2=".urlencode($companyInfo['cs_support_email']); // 
	$Pinfo.="&userVar3=na";//.urlencode($transInfo['']); // 
	$Pinfo.="&userVar4=1";//.urlencode($transInfo['']); // 
	
	// Uncomment below for live
	$query_url = "https://pay.tri-hub.com/Interfaces/payment3/index.php?";
	$output_url = $query_url.$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	//toLog('order','customer', "NovaPay Post for ".$transInfo['reference_number'].":  ".$output_url,$transInfo['transactionId']);
	$process_result = http_post2('ssl://pay.tri-hub.com', 443, $output_url, $Pinfo);
	toLog('order','customer', "Tri-Hub Result for ".$transInfo['reference_number'].":  ".$process_result,$transInfo['transactionId']);

	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url;

}


function cc_2000Charge_EuroDebit_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("subFunctions/banks.2000charge.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

//	if (stristr($bankInfo['bk_trans_types'],"discover")===FALSE)
//	{
//		$response['errormsg'] = "This bank does not support Discover Cards. Please contact an administrator.";
//		return $response;
//	}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$params = array(
		"City"=>"Berlin",
		"RemoteHost" => $_SERVER['REMOTE_ADDR'],
		"Amount" => $transInfo['amount'],
		"FirstName" => $transInfo['name'],
		"LastName" => $transInfo['surname'],
		"Address1" => $transInfo['address'],
		"State" => $transInfo['state'],
		"Zip" => $transInfo['zipcode'],
		"Country" => $transInfo['country'],
		"Email" => $transInfo['email'],
		"CKABA" => $transInfo['bankroutingcode'],
		"CKAcct" => $transInfo['bankaccountnumber'],
		"UserAgent" => $_SERVER['HTTP_USER_AGENT'],
		"IDField1" => "",
		"IDField2" => "",
		"IDField3" => "",
		"IDField4" => "",
		"TEST" => "FALSE",
		"Account" => $bankInfo['bk_username']
	);
	$processor = new Charge2000_Client($bankInfo,"Live");
	$process_result = $processor->EuroDebit_Charge($params);

	$response=NULL;
	$response['errormsg'] = $process_result['X'] . "\r\n" . $process_result['D'] . "\r\n" . $process_result['N'] . "\r\n" . $process_result['Y'];
	$response['td_bank_transaction_id'] = $process_result['Y'] . $process_result['N'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = $process_result['post_url'];
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if($process_result['Y'] != NULL)
	{
		$response['errormsg'] = "Card Accepted";
		$response['status'] = "A";
	}
	return $response;
}

function cc_2000Charge_Discover_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("subFunctions/banks.2000charge.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

//	if (stristr($bankInfo['bk_trans_types'],"discover")===FALSE)
//	{
//		$response['errormsg'] = "This bank does not support Discover Cards. Please contact an administrator.";
//		return $response;
//	}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$params = array(
		"CCName" => $transInfo['name'] . " " . $transInfo['surname'],
		"CCNum" => $transInfo['CCnumber'],
		"CCType" => $transInfo['cardtype'],
		"CVV2" => $transInfo['cvv'],
		"CCExpireMonth" => $expMonth,
		"CCExpireYear" => $expYear,
		"Address1" => $transInfo['address'],
		"Country" => $transInfo['country'],
		"City" => $transInfo['city'],
		"State" => $transInfo['state'],
		"Zip" => $transInfo['zipcode'],
		"Email" => $transInfo['email'],
		"Phone" => $transInfo['phonenumber'],
		"Amount" => $transInfo['amount'],
		"RemoteHost" => $_SERVER['REMOTE_ADDR'],
		"AddUser" => "0",
		"Account" => $bankInfo['bk_username']
	);

	$processor = new Charge2000_Client($bankInfo,"Live");
	$process_result = $processor->Discover_Charge($params);

	$response=NULL;
	$response['errormsg'] = $process_result['X'] . "\r\n" . $process_result['D'] . "\r\n" . $process_result['N'] . "\r\n" . $process_result['Y'];
	$response['td_bank_transaction_id'] = $process_result['Y'] . $process_result['N'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = $process_result['post_url'];
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if($process_result['Y'] != NULL)
	{
		$response['errormsg'] = "Card Accepted";
		$response['status'] = "A";
	}
	return $response;
}

function cc_2000Charge_JCB_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("subFunctions/banks.2000charge.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

//	if (stristr($bankInfo['bk_trans_types'],"discover")===FALSE)
//	{
//		$response['errormsg'] = "This bank does not support Discover Cards. Please contact an administrator.";
//		return $response;
//	}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$params = array(
		"CCName" => $transInfo['name'] . " " . $transInfo['surname'],
		"CCNum" => $transInfo['CCnumber'],
		"CCType" => $transInfo['cardtype'],
		"CVV2" => $transInfo['cvv'],
		"CCExpireMonth" => $expMonth,
		"CCExpireYear" => $expYear,
		"Address1" => $transInfo['address'],
		"Country" => $transInfo['country'],
		"City" => $transInfo['city'],
		"State" => $transInfo['state'],
		"Zip" => $transInfo['zipcode'],
		"Email" => $transInfo['email'],
		"Phone" => $transInfo['phonenumber'],
		"Amount" => $transInfo['amount'],
		"RemoteHost" => $_SERVER['REMOTE_ADDR'],
		"AddUser" => "0",
		"Account" => $bankInfo['bk_username']
	);

	$processor = new Charge2000_Client($bankInfo,"Live");
	$process_result = $processor->JCB_Charge($params);

	$response=NULL;
	$response['errormsg'] = $process_result['X'] . "\r\n" . $process_result['D'] . "\r\n" . $process_result['N'] . "\r\n" . $process_result['Y'];
	$response['td_bank_transaction_id'] = $process_result['Y'] . $process_result['N'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = $process_result['post_url'];
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if($process_result['Y'] != NULL)
	{
		$response['errormsg'] = "Card Accepted";
		$response['status'] = "A";
	}
	return $response;
}


function cc_iPayGate_integration(&$transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;

	
	require_once("SOAP/Client.php");
	require_once("subFunctions/banks.ipaygate.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$TID = "ETELEGATE01";
	$MID = "ca7e5842-dd33-1029-bf92-0013723bba63";
	//if($bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']]) $TID = $bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']];

	$DESC = "www.pay-support.com 1-800-511-2457";
	//if($bankInfo['cb_config']['custom']['desc_sites'][$transInfo['td_site_ID']]) $DESC = $bankInfo['cb_config']['custom']['desc_sites'][$transInfo['td_site_ID']];

	//$transInfo['billing_descriptor']=$DESC;

	$cust_cntry = $transInfo['country'];//urlencode(func_get_country($transInfo['country'],'co_full'));
	$transInfo['cardtype'] = 'Visa';
	if(substr($transInfo['CCnumber'],0,1) == '5') $transInfo['cardtype'] = 'Mastercard';
	$params = array(
	"customerPaymentPageText" => strval($TID),
	"orderDescription" => strval($MID),
	"currencyText" => strval("USD"),
	"taxAmount" => strval("0"),
	"dutyAmount" => strval("0"),
	"purchaseAmount" => "" . (number_format($transInfo['amount'],2)) . "",
	"cardHolderName" => strval($transInfo['name'] . " " . $transInfo['surname']),
	"firstName" => strval($transInfo['name']),
	"lastName" => strval($transInfo['surname']),
	"cardNo" => strval($transInfo['CCnumber']),
	"cardTypeText" => strval(strtoupper($transInfo['cardtype'])),
	"securityCode" => strval($transInfo['cvv']),
	"cardExpireMonth" => strval($expMonth),
	"cardExpireYear" => strval($expYear),
	"cardIssueMonth" => strval('0'),
	"cardIssueYear" => strval('0'),
	"country" => strval($cust_cntry),
	"address" => strval($transInfo['address']),
	"city" => strval($transInfo['city']),
	"state" => strval($transInfo['state']),
	"zip" => strval($transInfo['zipcode']),
	"email" => strval('1'.$transInfo['email']),
	"phone" => strval($transInfo['phonenumber']),
	"cardHolderIP" => strval($transInfo['ipaddress'])
	);

	$Pinfo.="&P5=".urlencode($transInfo['address']); //Address1	Y	(1,30) Varchar
	$Pinfo.="&P6=".urlencode($transInfo['address2']); //Address2	N	(1,30) Varchar
	$Pinfo.="&P7=".urlencode($transInfo['city']); //City	Y	(1,25) Varchar
	$Pinfo.="&P8=".urlencode($transInfo['state']); //State	Y	(1,8) Varchar
	$Pinfo.="&P9=".urlencode($transInfo['zipcode']); //Zip Code	Y	(5,9) Varchar

	$processor = new iPayGate_Client($bankInfo);
	$process_result = $processor->Execute_Sale($params);
	$process_result_array = xml2array($process_result);
	foreach($process_result_array['wddxPacket']['data']['struct']['var'] as $varinfo)
		$process_result_var[$varinfo['attributes']['name']] = @array_pop($varinfo['string']);

	//print_r($process_result_var);
	//print_r($params);
	$response=NULL;
	list($ResponseNum,$ResponseMsg) = split(' - ',$process_result_var['RESPONSECODE']);
	
	$response['errormsg'] = $process_result_var['ERRORCODE'] . ": $ResponseNum " . $process_result_var['ERRORMESSAGE'];
	if($response['errormsg']==":  ")$response['errormsg'] = "Declined (SoEx)";
	if($processor->resultCodes[$ResponseNum]) $response['td_process_msg'] = "$ResponseNum: ".$processor->resultCodes[$ResponseNum];
	else $response['td_process_msg'] = $response['errormsg'];
	
	$response['td_bank_transaction_id'] = $process_result_var['ORDERREFERENCE'];
	$response['td_process_result'] = serialize($process_result_var);
	$response['td_process_query'] = serialize($params);
	$response['td_bank_recieved'] = 'yes';
	if($process_result_var['ERRORCODE'] != '11063') $response['td_bank_recieved'] = 'no';
	$response['status'] = "D";
	if($process_result_var['ERRORCODE'] == '000' || $process_result_var['RESPONSECODE'] == '000 - APPROVED')
	{
		$response['errormsg'] = "Card Accepted";
		$response['status'] = "A";
		$response['td_bank_recieved'] = 'yes';
	}
	return $response;
}

function cc_Discover_integration($transInfo,$bankInfo,$companyInfo)
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

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$params['TransactionAmount'] = $transInfo['amount'];
	$params['MerchantAmount'] = $transInfo['amount'];
	$params['ConvenienceFee'] = 0;
	$params['AccountNumber'] =  $transInfo['CCnumber'];
	$params['CVV2'] =  $transInfo['cvv'];
	$params['ExpirationMonth'] = $expMonth;
	$params['ExpirationYear'] = $expYear;

	$processor = new Discover_Client($bankInfo,"Live");
	$process_result = $processor->Credit_Card_Charge($params);
	
	$response=NULL;
	$response['errormsg'] = $process_result['RC'] . ": " . $process_result['RCString'];
	if(!$process_result['RCString']) $response['errormsg'] = $process_result['desc'];
	$response['td_bank_transaction_id'] = $process_result['TransactionID'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = $process_result['post_url'];
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if($process_result['RC'] == '0')
	{
		$response['errormsg'] = "Card Accepted";
		$response['status'] = "A";
	}
	return $response;
}

function cc_i24Card_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "No Error 15";
	$response['success'] = false;
	$response['td_bank_recieved'] = 'no';


	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);

	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));

	$i24_msg_id = rand(0,400000000000);
	$response['td_bank_transaction_id'] = $i24_msg_id;

	$transInfo['additional_funds'] = intval($transInfo['additional_funds']);
	if($transInfo['additional_funds']>20) $transInfo['additional_funds'] = 20;
	if($transInfo['additional_funds']<0) $transInfo['additional_funds'] = 0;

	$additional_array = explode('|',$bankInfo['bk_additional_id']);

	//FunctionID	002	Y
	$Pinfo="";
	$Pinfo.="CID=".$additional_array[0];
	$Pinfo.="&CUSR=".$bankInfo['bk_username'];
	$Pinfo.="&CPWD=".$bankInfo['bk_password'];

	if(!is_numeric($transInfo['td_gcard'])) $transInfo['td_is_a_rebill'] = 0;
	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&FUNC=002";
	else
		$Pinfo.="&FUNC=003";

	$Pinfo.="&MSGID=".$i24_msg_id;
	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&P1=".rand(0,400000000); //Client Card Holder ID	Y	(1,9) Numeric
	else
		$Pinfo.="&P1=".$transInfo['td_gcard']; //Electron Card  Y	(1,9) Numeric

	$Pinfo.="&P2=".urlencode($transInfo['name']); //First Name	Y	(1,20) Varchar
	$Pinfo.="&P3=".urlencode($transInfo['initial']); //Middle Initial	N	(1,1) Varchar
	$Pinfo.="&P4=".urlencode($transInfo['surname']); //Last Name	Y	(1,20) Varchar
	$Pinfo.="&P5=".urlencode($transInfo['address']); //Address1	Y	(1,30) Varchar
	$Pinfo.="&P6=".urlencode($transInfo['address2']); //Address2	N	(1,30) Varchar
	$Pinfo.="&P7=".urlencode($transInfo['city']); //City	Y	(1,25) Varchar
	$Pinfo.="&P8=".urlencode($transInfo['state']); //State	Y	(1,8) Varchar
	$Pinfo.="&P9=".urlencode($transInfo['zipcode']); //Zip Code	Y	(5,9) Varchar
	$Pinfo.="&P10=".$cust_cntry; //Country Code (ISO 3166)	Y	(2) Varchar Alpha
	$Pinfo.="&P16=".urlencode($transInfo['email']); //	Email	Y	(1,40) varchar
	$Pinfo.="&P17=".urlencode(round(($transInfo['amount']+$transInfo['additional_funds'])*100)); //	Load Amount – Represents the amount of money on the initial load when assigning the card number	Y	Numeric (See 1.1.5)
	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&P18=".($transInfo['CCnumber']?$transInfo['CCnumber']:$transInfo['td_gcard']); //	Credit Card – Card number to charge for the Amount (P17) if a load is required.	Y	(1,26) Numeric
	else
		$Pinfo.="&P18="; //	Credit Card – Card number to charge for the Amount (P17) if a load is required.	Y	(1,26) Numeric

	$Pinfo.="&P19=".$expDate; //	Credit Card Expiry Date – Expiration date of the credit card. MMYY	Y	(1,4) Numeric

	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&P20="; //	CVD – Cardholder Verification Data (CVV2,CVC2)	Y	(1,3) Numeric
	else
		$Pinfo.="&P20=".urlencode($transInfo['cvv']); //	CVD – Cardholder Verification Data (CVV2,CVC2)	Y	(1,3) Numeric
	$Pinfo.="&P21=".round($transInfo['amount']*100); //	Transfer Amount – Amount to transfer from Electron Card to Web Master.	Y 	(1,9) Varchar
	$Pinfo.="&P22=".$additional_array[1]; //	Merchant Vendor ID -  Virtual ID number linked to a Merchant ID and/or a Merchant Debit Card.	Y	(1,20) Varchar
	$Pinfo.="&P23=".$transInfo['ipaddress']; //	Cardholder IP Address	Y	(1,15) varchar
	$Pinfo.="&P24=0"; //	RSID – Reseller/Referrer ID	Y
	$Pinfo.="&P27=".urlencode($transInfo['productdescription']); //	Transaction Description	N
	$Pinfo.="&P28=".urlencode($transInfo['reference_number']); //	Ibill Transaction Tracking ID	Y
	$Pinfo.="&P29=".$transInfo['td_is_a_rebill']; //	RecurID	Y	0 = not recurring 1 = recurring transaction
	$Pinfo.="&P39="; //	Embossing Data – used for embossing the name of the company on the card	Y	(1,40) Varchar
	if(!$transInfo['td_is_a_rebill'])
	$Pinfo.="&issue_physical_card=0"; //	Whether or not to issue plastic	Y	Boolean

	// Uncomment below for live
	$query_url = "https://trans.symmetrex.com/ibill/ibillapi?";
	$output_url = $query_url.$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	toLog('order','customer', "Symmetrex Post for ".$transInfo['reference_number'].":  ".$output_url,$transInfo['transactionId']);
	$process_result = http_post2('ssl://trans.symmetrex.com', 443, $output_url, $Pinfo);
	toLog('order','customer', "Symmetrex Result for ".$transInfo['reference_number'].":  ".$process_result,$transInfo['transactionId']);

	$response['td_process_result'] = $process_result;

	parse_str(trim($process_result));
	$response['errormsg'] = $P2;
	$response['errorcode'] = $P1;
	if(!$P3) $response['td_gcard']="NULL" ;
	else $response['td_gcard'] = "'".etelEnc($P3)."'";

	if (!$response['errormsg']) $response['errormsg'] = "Declined";

	$response['success'] = true;
	$response['status'] = "D";
	$response['td_process_query'] = $output_url;
	$response['td_bank_recieved'] = 'yes';
	if($process_result=='0: Success') $response['td_bank_recieved'] = 'internalerror';

	if($P2=="Success")
		$response['status'] = "A";

	$response['td_bank_transaction_id'] = $P8;
	$response['td_gcard_pass'] = $WalletPassword;
	return $response;
}

function cc_novapay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	$response['td_bank_recieved'] = 'no';


	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];

	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	
	//if(!$transInfo['cvv']) $transInfo['cvv'] = rand(120,999);

	//FunctionID	002	Y
	$Pinfo="";
	$Pinfo.="merchant_id=".urlencode($bankInfo['bk_username']); //	  	
	$Pinfo.="&secret_key=".urlencode($bankInfo['bk_password']); //	 	
	$Pinfo.="&merchant_orderid=".urlencode($transInfo['reference_number']); //	 	
	$Pinfo.="&customer_country=".$cust_cntry; //	 	
	$Pinfo.="&customer_language=ENG"; //	 	
	$Pinfo.="&customer_email=".urlencode($transInfo['email']); //	 	novapaycustomer@etelegate.com
	//$Pinfo.="&pay_cents=".urlencode(round(($transInfo['amount'])*100)); //	 
	$Pinfo.="&amount=".number_format($transInfo['amount'],2,'.',''); //	 	
	$Pinfo.="&currency=USD"; //	 	
	$Pinfo.="&contracttext=123"; //	 	
	$Pinfo.="&card_number=".urlencode($transInfo['CCnumber']); //	 	
	$Pinfo.="&card_expiry_MM=".urlencode($expMonth); //	 	
	$Pinfo.="&card_expiry_YYYY=".urlencode($expYear); //
	$Pinfo.="&cardholder_firstname=".urlencode($transInfo['name']); //	
	$Pinfo.="&cardholder_lastname=".urlencode($transInfo['surname']); //	
	$Pinfo.="&card_cvc=".urlencode($transInfo['cvv']); //
	//$Pinfo.="&_merchant_css=123"; //	 	
	//$Pinfo.="&_merchant_timeout=123"; //	 	
	//$Pinfo.="&_merchant_security_data=123"; //	 	 	 	
	//$Pinfo.="&_customer_phone=".urlencode($transInfo['phonenumber']); //	 	
	//$Pinfo.="&_customer_firstname=".urlencode($transInfo['name']); //	 	
	//$Pinfo.="&_customer_lastname=".urlencode($transInfo['surname']); //	 	
	//$Pinfo.="&_customer_address1=".urlencode($transInfo['address']); //	 	
	//$Pinfo.="&_customer_address2=".urlencode($transInfo['address2']); //	 	
	//$Pinfo.="&_customer_zip=".urlencode($transInfo['zipcode']); //	 	
	//$Pinfo.="&_customer_city=".urlencode($transInfo['city']); //	 		 
	//$Pinfo.="&_pay_prodid=123"; //	 		
	
	// Uncomment below for live
	$query_url = "https://paynova.securenpay.com/Service.asmx/ProcessPayment?";
	$output_url = $query_url.$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	//toLog('order','customer', "NovaPay Post for ".$transInfo['reference_number'].":  ".$output_url,$transInfo['transactionId']);
	$process_result = http_post2('ssl://paynova.securenpay.com', 443, $output_url, $Pinfo);
	toLog('order','customer', "NovaPay Result for ".$transInfo['reference_number'].":  ".$process_result,$transInfo['transactionId']);

	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url;
	
	$result_array = xml2array( $process_result);
	//$response['result_array'] = $result_array;
	
	$response['errormsg'] = $result_array['ProcessTransactionResponse']['ResponseMessage'];
	$response['errorcode'] = $result_array['ProcessTransactionResponse']['ResponseCode'];
	if(strpos($response['errormsg'],"The cardtype is not allowed for this merchant")) $response['errormsg'] = "Declined (SoEx)";
	if (!$response['errormsg']) $response['errormsg'] = "Declined";

	$response['success'] = true;
	$response['status'] = "D";
	$response['td_bank_recieved'] = 'yes';

	if($result_array['ProcessTransactionResponse']['ResponseCode'] == "1")
		$response['status'] = "A";

	$response['td_bank_transaction_id'] = $result_array['ProcessTransactionResponse']['PaynovaTransactionId'];
	return $response;
}


function cc_novapay_integration_rebill($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	$response['td_bank_recieved'] = 'no';


	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];

	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	
	//if(!$transInfo['cvv']) $transInfo['cvv'] = rand(120,999);

	//FunctionID	002	Y
	$Pinfo="";
	$Pinfo.="merchantId=".urlencode($bankInfo['bk_username']); //	  	
	$Pinfo.="&validationCode=".urlencode($bankInfo['bk_password']); //	 	
	$Pinfo.="&referenceId=".urlencode($transInfo['reference_number']); //	 	
	$Pinfo.="&customer_country=".$cust_cntry; //	 	
	$Pinfo.="&customer_language=ENG"; //	 	
	$Pinfo.="&customer_email=".urlencode($transInfo['email']); //	 	novapaycustomer@etelegate.com
	//$Pinfo.="&pay_cents=".urlencode(round(($transInfo['amount'])*100)); //	 
	$Pinfo.="&amount=".number_format($transInfo['amount'],2,'.',''); //	 	
	$Pinfo.="&currency=USD"; //	 	
	//$Pinfo.="&contracttext=123"; //	 	
	$Pinfo.="&cardNumber=".urlencode($transInfo['CCnumber']); //	 	
	$Pinfo.="&cardExpiration=".urlencode($expMonth).'/'.urlencode(substr($expYear,2,2)); //	 	
	//$Pinfo.="&cardholder_firstname=".urlencode($transInfo['name']); //	
	//$Pinfo.="&cardholder_lastname=".urlencode($transInfo['surname']); //	
	//$Pinfo.="&card_cvc=".urlencode($transInfo['cvv']); //
	//$Pinfo.="&_merchant_css=123"; //	 	
	//$Pinfo.="&_merchant_timeout=123"; //	 	
	//$Pinfo.="&_merchant_security_data=123"; //	 	 	 	
	//$Pinfo.="&_customer_phone=".urlencode($transInfo['phonenumber']); //	 	
	$Pinfo.="&firstName=".urlencode($transInfo['name']); //	 	
	$Pinfo.="&lastName=".urlencode($transInfo['surname']); //	 	
	$Pinfo.="&addrStreet1=".urlencode($transInfo['address']); //	 	
	$Pinfo.="&addrCountry=".urlencode($transInfo['country']); //	 	
	$Pinfo.="&addrPostalCode=".urlencode($transInfo['zipcode']); //	 	
	$Pinfo.="&productDescription=".urlencode($transInfo['city']); //	 		 
	//$Pinfo.="&_pay_prodid=123"; //	 		
	
	// Uncomment below for live
	$query_url = "https://secure.securenpay.com/SNPWebService.asmx/RequestCardAuthCapture?";
	$output_url = $query_url.$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	$process_result = http_post2('ssl://secure.securenpay.com', 443, $output_url, $Pinfo);
	toLog('order','customer', "NovaPay Result for ".$transInfo['reference_number'].":  ".$process_result,$transInfo['transactionId']);

	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url;
	
	$result_array = xml2array( $process_result);
	//$response['result_array'] = $result_array;
	
	$response['errormsg'] = $result_array['SnpResponse']['ResponseMessage'];
	$response['errorcode'] = $result_array['SnpResponse']['ResponseCode'];
	
	if (!$response['errormsg']) $response['errormsg'] = "Declined";

	$response['success'] = true;
	$response['status'] = "D";
	$response['td_bank_recieved'] = 'yes';

	if($response['errorcode'] == "1")
		$response['status'] = "A";

	$response['td_bank_transaction_id'] = $result_array['SnpResponse']['RequestAuthCapture']['TransactionId'];
	return $response;
}

function cc_ChronoPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;

	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}
	$state = $transInfo['state'];
	if ($transInfo['country']!="US") $state = "";
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];

	$cardholder = $transInfo['name']." ".$transInfo['surname'];

	$userinfo = "opcode=1";
	$Pinfo=$userinfo.$functioninfo.$msginfo;
	$Pinfo.="&product_id=".$companyInfo['cd_cc_bank_extra']; // product identifier*  ??????????
	$Pinfo.="&fname=".$transInfo['name']; 		// first name*
	$Pinfo.="&lname=".$transInfo['surname']; 	// laset name*
	$Pinfo.="&cardholder=".$cardholder;	// card holder name
	$Pinfo.="&zip=".$transInfo['zipcode']; 			// ZIP code (required only for USA)
	$Pinfo.="&street=".$transInfo['address']; 	// street address*
	$Pinfo.="&city=".$transInfo['city']; 		// city name*
	$Pinfo.="&state=".$state; 					// state code within 2 symbols (required only for USA)
	$Pinfo.="&country=".$transInfo['country']; 	// country code within 3 symbols*
	$Pinfo.="&email=".$transInfo['email']; 		// e-mail address*
	$Pinfo.="&phone=".$transInfo['phonenumber']; 		// phone number
	$Pinfo.="&ip=".$transInfo['ipaddress']; 		// IP address*
	$Pinfo.="&card_no=".$transInfo['CCnumber']; 	// card number*
	$Pinfo.="&cvv=".$transInfo['cvv']; 			// cvv2 code*
	$Pinfo.="&expirey=".$expYear; 						// year of expire date (YYYY)*
	$Pinfo.="&expirem=".$expMonth; 						// month of expire date (MM)*
	$Pinfo.="&amount=".$transInfo['amount']; 	// amount value*
	$Pinfo.="&currency=USD"; 							// currency code within 3 symbols (now only 'USD' may be accepted)*
	$Pinfo.="&hash=".md5("test"."1".$companyInfo['cd_cc_bank_extra'].$transInfo['name'].$transInfo['surname'].$transInfo['address'].$transInfo['ipaddress'].$transInfo['CCnumber'].$transInfo['amount']);
	// md5 hash. MD5(shared secret + opcode + product_id + fname + lname + street + ip + card_no + amount)*
	// output url - i.e. the absolute url to the paymentsgateway.net script
	//$output_url = "https://www.paymentsgateway.net/cgi-bin/posttest.pl";

	// Uncomment below for live
	$output_url = "https://secure.chronopay.com/gateway.cgi";

	// start output buffer to catch curl return data
	ob_start();

	$ch = curl_init ($output_url);
	curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $Pinfo);
   	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_exec ($ch);
	curl_close ($ch);

	// set variable eq to output buffer
	$actual_result = ob_get_contents();
	$process_result = trim($actual_result);

	// close and clean output buffer
	ob_end_clean();

	// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
	$parsed_result=explode("|",$process_result);
	$response['td_process_result'] = $actual_result;
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['errormsg'] = $parsed_result[1];
	$response['success'] = false;
	if ($parsed_result[0] == 'Y')
	{
		$response['success'] = true;
		$response['errormsg'] = "Credit Card Accepted";
		$response['status'] = "A";
		$response['td_bank_transaction_id'] = $parsed_result[1];
	}
	else
	if ($parsed_result[1] == 'rejected' || $parsed_result[1] == 'Declined by processing')
	{
		$response['success'] = true;
		$response['errormsg'] = "Credit Card Declined";
		$response['status'] = "D";
	}
	else toLog('error','customer', "Customer Recieves error : ".$process_result." PInfo: ?".$Pinfo, $transInfo['userId']);

	return $response;
}

function cc_NetMerchants_integration($transInfo,$bankInfo)
{
	$trans_response="";
	$trans_response['errormsg'] = "Transaction could not be processed.";

	if ($bankInfo['bk_cc_support']!=1){$trans_response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	$exp = $expMonth.$expYear;
	$bank_trans_id = substr(time(),0,9);

	foreach($transInfo as $key => $item)
		$transInfo[$key] = urlencode($item);

	$Pinfo="";
	$Pinfo.="type=sale";  // Required sale / auth / credit, sale = Transaction Sale
	$Pinfo.="&username=".$bankInfo['bk_username'];  // Required Username assigned to merchant account
	$Pinfo.="&password=".$bankInfo['bk_password'];  // Required Password assigned to merchant account
	$Pinfo.="&ccnumber=".$transInfo['CCnumber'];  // Required Credit card number
	$Pinfo.="&ccexp=".$exp;  // Required MMYY Credit card expiration (ie. 0705 = 7/2005)
	$Pinfo.="&amount=".number_format($transInfo['amount'],2,".",",");  // Required x.xx Total amount to be charged (i.e. 10.00)
	$Pinfo.="&cvv=".$transInfo['cvv'];  // Recommended Card security code
	$Pinfo.="&orderid=".$bank_trans_id;  // Recommended Order ID
	$Pinfo.="&orderdescription=".$transInfo['productdescription'];  // Optional Order description
	$Pinfo.="&ipaddress=".$transInfo['ipaddress'];  // Recommended xxx.xxx.xxx.xxx IP address of the cardholder
	$Pinfo.="&firstname=".$transInfo['name'];  // Recommended Cardholder’s first name
	$Pinfo.="&lastname=".$transInfo['surname'];  // Recommended Cardholder’s last name
	$Pinfo.="&address1=".$transInfo['address'];  // Recommended Card billing address
	$Pinfo.="&address2=".$transInfo['address2'];  // Optional Card billing address – line 2
	$Pinfo.="&city=".$transInfo['city'];  // Recommended Card billing city
	$Pinfo.="&state=".$transInfo['state'];  // Recommended CC Card billing state (2 character abbrev.)
	$Pinfo.="&zip=".$transInfo['zipcode'];  // Recommended Card billing zip code
	$Pinfo.="&country=".$transInfo['country'];  // Recommended CC (ISO-3166) Card billing country (ie. US)
	$Pinfo.="&phone=".$transInfo['phonenumber'];  // Recommended Billing phone number
	$Pinfo.="&email=".$transInfo['email'];  // Recommended Billing email address

	// Uncomment below for live
	$Pinfo = $Pinfo;
	$output_url = "https://secure.networkmerchants.com/gw/api/transact.php?".$Pinfo;

	$process_result = file_get_contents($output_url);
	//$process_result = http_post('ssl://secure.networkmerchants.com', 443, $output_url, $Pinfo);

	$clean_data = trim($process_result);

	parse_str($clean_data);

	$trans_response="";
	if(!$Mess) $trans_response['errormsg'] = "Credit Card Declined";
	else $trans_response['errormsg'] = $Mess;
	$trans_response['errorcode'] = $Error;
	$trans_response['td_bank_transaction_id'] = $transactionid;
	$trans_response['td_process_result'] = $process_result;
	$trans_response['td_process_query'] = $output_url;
	$trans_response['status'] = "D";
	$trans_response['success'] = false;
	if ($response == 1)
	{
		$trans_response['errormsg'] = "Credit Card Accepted";
		$trans_response['status'] = "A";
		$trans_response['success'] = true;
	}
	if ($response == 2)
	{
		$trans_response['errormsg'] = "Credit Card Declined";
		$trans_response['status'] = "D";
		$trans_response['success'] = true;
	}
	if ($response == 3)
	{
		$trans_response['errormsg'] = "Error: $responsetext";
		$trans_response['status'] = "D";
	}
	$trans_response['Invoiceid'] = $transactionid;
	return $trans_response;
}

function cc_EcomGlobal_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];

	$Pinfo="";
	$Pinfo.="customer_id=".$transInfo['td_product_id']; //   	This field gets returned in the response back  	Optional
	$Pinfo.="&cardnumber=".$transInfo['CCnumber']; //  	Credit Card Number 	Required
	$Pinfo.="&cvv=".$transInfo['cvv']; //  	Credit Card CVV2 Number 	Required
	$Pinfo.="&exp_month=".$expMonth; //  	(2 digit) Credit Card Expiration Month 	Required
	$Pinfo.="&exp_year=".$expYear; //  	(2 digit) Credit Card Expiration Year 	Required
	$Pinfo.="&email=".$transInfo['email']; //  	Email Address 	Required
	$Pinfo.="&phone=".$transInfo['phonenumber']; //  	Phone Number 	Required
	$Pinfo.="&ip=".$transInfo['ipaddress']; //  	IP Address 	Required
	$Pinfo.="&firstname=".$transInfo['name']; //  	First Name 	Required
	$Pinfo.="&lastname=".$transInfo['surname']; //  	Last Name 	Required
	$Pinfo.="&address_one=".$transInfo['address']; //  	Street Address 	Required
	$Pinfo.="&address_two=".$transInfo['address2']; //  	Street Address 	Optional
	$Pinfo.="&city=".$transInfo['city']; //  	City 	Required
	$Pinfo.="&state=".$transInfo['state']; //  	(2 Letter) State 	Required - US/Canada Only
	$Pinfo.="&zip=".$transInfo['zipcode']; //  	Zip Code 	Required
	$Pinfo.="&country=".$transInfo['country']; //  	(2 Letter) Country 	Required
	$Pinfo.="&amount=".number_format($transInfo['amount'],2,".",","); //  	US Dollar Amount (Example: 9.00) 	Required

	// Uncomment below for live
	$output_url = "https://secure.ecommerceglobal.com/gateway/gw1.cgi";
	$process_result = http_post('ssl://secure.ecommerceglobal.com', 443, $output_url, $Pinfo,$bankInfo['bk_username'],$bankInfo['bk_password'] );

	$clean_data = str_replace(";","&",trim($process_result));

	parse_str($clean_data);

	$response="";
	if(!$Mess) $response['errormsg'] = "Credit Card Declined";
	else $response['errormsg'] = $Mess;
	$response['errorcode'] = $Error;
	$response['td_bank_transaction_id'] = $trans_id;
	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['status'] = "D";
	if ($accepted == 1)
	{
		$response['errormsg'] = "Credit Card Accepted";
		$response['status'] = "A";
	}
	$response['Invoiceid'] = $trans_id;
	$response['success'] = true;
	if ($Error) $response['success'] = false;
	return $response;
}

function cc_ForceTronix_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";


	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));

	$Pinfo="";
	$Pinfo.="AmountCleared=".round($transInfo['amount']*100); 	//  (Amount the customer paid. (Amount are specified in cent))
	$Pinfo.="&CreditCardCVC=".$transInfo['cvv']; 		//  (Credit Card security code)
	$Pinfo.="&CreditCardNumber=".$transInfo['CCnumber']; //  (Credit Card Number)
	$Pinfo.="&CurrencyCode=840"; 						//  (ISO 4217 Currency Codes Numbers)
	$Pinfo.="&ExpireMonth=".$expMonth; 					//  (Credit Card expiration month. (MM))
	$Pinfo.="&ExpireYear=".$expYear; 					//  (Credit Card expiration year (YY). Ex: 00 equals year 2000)
	$Pinfo.="&InternalOrderId=".$transInfo['reference_number']; 				//  (Your own unique identifier for this purchase)
	$Pinfo.="&MerchantId=".$bankInfo['bk_username']; 		//  (The EGE unique identifier for the account)
	$Pinfo.="&Secret=".$bankInfo['bk_additional_id']; 			//  (The EGE security code for the account)
	$Pinfo.="&TransType=3"; 		//  (The Transaction Code)

	$Pinfo.="&OwnerAddress=".$transInfo['address']; //
	$Pinfo.="&OwnerAddressNumber=".$transInfo['address2']; //
	$Pinfo.="&OwnerCity=".$transInfo['city']; //
	$Pinfo.="&OwnerCountry=".$cust_cntry; //
	$Pinfo.="&OwnerEmail=".$transInfo['email']; //
	$Pinfo.="&OwnerFirstName=".$transInfo['name']; //
	$Pinfo.="&OwnerLastName=".$transInfo['surname']; //
	$Pinfo.="&OwnerPhone=".$transInfo['phonenumber']; //
	$Pinfo.="&OwnerState=".$transInfo['state']; //
	$Pinfo.="&OwnerZip=".$transInfo['zipcode']; //

	$output_url = "https://eccpro.com/author.php";

	$process_result = http_post('ssl://eccpro.com', 443, $output_url, $Pinfo);

	$xml = xml2array( $process_result);

	$statusCode = $xml['ECCPro']['Response']['StatusCode'];
	$ProcessOrderID = $xml['ECCPro']['Response']['ProcessOrderID'];
	$ProcessStatusText = $xml['ECCPro']['Response']['ProcessStatusText'];

	$response['errormsg'] = "Declined";

	$response['success'] = true;
	$response['status'] = "D";
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_process_result'] = $process_result;
	$response['td_bank_recieved'] = 'yes';
	if($statusCode == "000")
	{
		$response['status'] = "A";
		$response['errormsg'] = $ProcessStatusText;
	}
	$response['td_bank_transaction_id'] = $ProcessOrderID;
	return $response;
}

function cc_AvantPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	$cust_state = urlencode(func_get_state($transInfo['state'],'st_abbrev'));

	$cardtype = "VI";
	if(strtolower($transInfo['cardtype'])=='master') $cardtype = 'MC';
	$cardholder = $transInfo['name']." ".$transInfo['surname'];

	$url = $companyInfo['cs_URL'];
	$url = str_replace(array("http://","https://","HTTP://","HTTPS://"),"",$url);

	$output_url = "https://secure.avantpay.com/webservices/0_9/payment.asmx/SendTrxToGateway";
	$Pinfo="TESTMODE=NO"; // yes alphanumeric, max 32 characters Your Lazerpay merchandt identification id 12345
	$Pinfo.="&TXNTYPE=AUTH"; // yes alphanumeric, max 40 characters Your Lazerpay secret merchant key IMPORTANT: Keep this secret key hidden. Do not show it on your website. Make sure that it is not stated in the html code viewable to the clients. v7iTT5yq6_66eQ
	$Pinfo.="&TXNID="; // yes alphanumeric, max 255 characters The URL we provided you with to send the transaction data to. https://merchants.lazerpay.com/api/processing.cfm

	$Pinfo.="&MCHTTXNID=".$transInfo['reference_number']; // 	yes 	tr_amount
	$Pinfo.="&MCHTID=".$bankInfo['bk_additional_id']; // 	yes 	tr_amount
	$Pinfo.="&CCHOLDERNAME=".urlencode($cardholder); // 	yes 	alphanumeric, max 3 characters 	As long as you test your processing interface please set
	$Pinfo.="&CCTYPE=".$cardtype; // 	yes 	alphanumeric, max 20 characters 	Type of Credit Card, Visa or MasterCard 	Visa
	$Pinfo.="&CCNUMBER=".$transInfo['CCnumber']; // 	yes 	numeric, max 16 digits 	Credit Card Number 	4111111111111111
	$Pinfo.="&CCEXPMONTH=".$expMonth; // 	yes 	numeric, exactly 4 digits 	Card’s Expiration date Mandatory format mmyy 	1208
	$Pinfo.="&CCEXPYEAR=20".$expYear; // 	yes 	numeric, exactly 4 digits 	Card’s Expiration date Mandatory format mmyy 	1208
	$Pinfo.="&CVV2=".$transInfo['cvv']; // 	yes 	numeric, max 4 digits 	Control Number on the reverse side of card 	123
	$Pinfo.="&DESCRIPTION=".$url.":Description-".urlencode($transInfo['productdescription']); // 	no 	alphanumeric, max 200 characters 	The name / ID of the Submerchant this transaction is being processed for. 	www.webmerchant.com
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

	toLog('order','customer',"Pending Transaction '".$transInfo['reference_number']."' Sending Request to Bank: ".$output_url."?".$Pinfo);

	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$Pinfo);
	curl_setopt($ch, CURLOPT_URL,$output_url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
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


function cc_LaserPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";


	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));

	$cardtype = $transInfo['cardtype'];
	if(strtolower($transInfo['cardtype'])=='master') $cardtype = 'MasterCard';

	$output_url = "https://merchants.lazerpay.com/api/processing.cfm";
	if(!$transInfo['state']) $transInfo['state'] = "NA";
	$Pinfo.="merchant_id=".$bankInfo['bk_username']; // yes alphanumeric, max 32 characters Your Lazerpay merchandt identification id 12345
	$Pinfo.="&secret_key=".$bankInfo['bk_additional_id']; // yes alphanumeric, max 40 characters Your Lazerpay secret merchant key IMPORTANT: Keep this secret key hidden. Do not show it on your website. Make sure that it is not stated in the html code viewable to the clients. v7iTT5yq6_66eQ
	$Pinfo.="&tr_id=".$transInfo['reference_number']; // 	yes 	tr_amount
	$Pinfo.="&tr_amount=".round($transInfo['amount']*100); // 	yes 	tr_amount
	$Pinfo.="&tr_callback_url=https://www.etelegate.com"; // 	yes 	tr_amount
	$Pinfo.="&tr_currency=USD"; // 	yes 	atr_currency
	$Pinfo.="&tr_testmode=no"; // 	yes 	alphanumeric, max 3 characters 	As long as you test your processing interface please set
	$Pinfo.="&tr_cc_type=".$cardtype; // 	yes 	alphanumeric, max 20 characters 	Type of Credit Card, Visa or MasterCard 	Visa
	$Pinfo.="&tr_cc_number=".$transInfo['CCnumber']; // 	yes 	numeric, max 16 digits 	Credit Card Number 	4111111111111111
	$Pinfo.="&tr_cc_exp_date=".$expMonth.$expYear; // 	yes 	numeric, exactly 4 digits 	Card’s Expiration date Mandatory format mmyy 	1208
	$Pinfo.="&tr_cvx2=".$transInfo['cvv']; // 	yes 	numeric, max 4 digits 	Control Number on the reverse side of card 	123
	$Pinfo.="&tr_submerchant=".urlencode(quote_smart($companyInfo['cs_URL'])); // 	no 	alphanumeric, max 200 characters 	The name / ID of the Submerchant this transaction is being processed for. 	www.webmerchant.com
	$Pinfo.="&tr_description=".$transInfo['description']; // 	no 	alphanumeric, max 200 characters 	The name / ID of the Submerchant this transaction is being processed for. 	www.webmerchant.com
	$Pinfo.="&cus_title="; // 	no 	alphanumeric, max 40 characters 	The customer’s title 	Ms
	$Pinfo.="&cus_firstname=".$transInfo['name']; // 	yes 	alphanumeric, max 80 characters 	The customer’s first name 	Pamela
	$Pinfo.="&cus_lastname=".$transInfo['surname']; // 	yes 	alphanumeric, max 80 characters 	The customer’s last name 	Anitole
	$Pinfo.="&cus_address1=".$transInfo['address']; // 	yes 	alphanumeric, max 200 characters 	The customer’s address line 1 	55 Chevy Lane
	$Pinfo.="&cus_address2=".$transInfo['address2']; // 	no 	alphanumeric, max 200 characters 	The customer’s address line 2 	Apt. 2020
	$Pinfo.="&cus_city=".$transInfo['city']; // 	yes 	alphanumeric, max 200 characters 	The customer’s city 	Beverly Hills
	$Pinfo.="&cus_state=".$transInfo['state']; // 	yes 	alphanumeric, max 40 characters 	The customer’s state For US States please provide the 2 letter abbreviation from Appendix E outside US please provide the state’s full name, if there are no states in the respective country please provide ‘NA’.  This field cannot be left blank. 	CA
	$Pinfo.="&cus_zip=".$transInfo['zipcode']; // 	yes 	alphanumeric, max 12 characters 	The customer’s zip code 	99054
	$Pinfo.="&cus_country=".$cust_cntry; // 	yes 	alphanumeric, exactly 2 characters 	The customer’s country For the USA please provide ‘US’, outside of the US please provide the 2 letter code from Appendix C 	US
	$Pinfo.="&cus_phone=".$transInfo['phonenumber']; // 	no 	alphanumeric, max 40 	The customer’s phone no 	2143316684 			characters
	$Pinfo.="&cus_cellphone=".$transInfo['']; // 	no 	alphanumeric, max 80 characters 	The customer’s cell phone no
	$Pinfo.="&cus_email=".$transInfo['email']; // 	yes 	alphanumeric, max 80 characters 	The customer’s email address 	pammya@yahoo.com
	$Pinfo.="&cus_ssn=0000"; // 	yes 	numeric, exactly 4 digits 	Last 4 digits of Social Security Number Please note: For non US customers provide 0000 	123456789
	$Pinfo.="&cus_birthday=000000"; // 	yes 	numeric, exactly 6 digits 	The customer’s Birthday Mandatory format: mmddyy 	100972 	API_version 	yes 	numeric, max 2 digits 	The Version number of the API you are using. Please note: This parameter is optional for API versions 7 and lower. 	12
	$Pinfo.="&API_version=12"; // 	yes 	numeric, exactly 6 digits 	The customer’s Birthday Mandatory format: mmddyy 	100972 	API_version 	yes 	numeric, max 2 digits 	The Version number of the API you are using. Please note: This parameter is optional for API versions 7 and lower. 	12
	$Pinfo.="&pay=LazerPay"; // 	yes 	numeric, exactly 6 digits 	The customer’s Birthday Mandatory format: mmddyy 	100972 	API_version 	yes 	numeric, max 2 digits 	The Version number of the API you are using. Please note: This parameter is optional for API versions 7 and lower. 	12

	parse_str($Pinfo, $val);

	$hashstring = ($val['merchant_id'].$val['tr_id'].$val['tr_amount'].$val['tr_currency'].$val['tr_callback_url'].$val['tr_description'].$val['tr_testmode'].$val['tr_cc_type'].$val['tr_cc_number'].$val['tr_cc_exp_date'].$val['tr_cvx2'].$val['tr_submerchant'].$val['cus_title'].$val['cus_firstname'].$val['cus_lastname'].$val['cus_address1'].$val['cus_address2'].$val['cus_city'].$val['cus_state'].$val['cus_zip'].$val['cus_country'].$val['cus_phone'].$val['cus_cellphone'].$val['cus_email'].$val['cus_ssn'].$val['cus_birthday'].$val['secret_key']);

	$md5string = md5($hashstring);
	$Pinfo.="&checksum=$md5string"; // 	yes 	numeric, exactly 6 digits 	The customer’s Birthday Mandatory format: mmddyy 	100972 	API_version 	yes 	numeric, max 2 digits 	The Version number of the API you are using. Please note: This parameter is optional for API versions 7 and lower. 	12

	$process_result = http_post('ssl://merchants.lazerpay.com', 443, $output_url, $Pinfo);

	$xml = xml2array($process_result);

	$status = $xml['ProcessingResult']['TR_SUCCESS'];
	$ProcessStatusText = $xml['ProcessingResult']['TR_RESULT'];
	$ProcessOrderID = $xml['ProcessingResult']['TR_ID'];

	$response['success'] = true;
	$response['status'] = "D";
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_process_result'] = $process_result;
	$response['td_bank_recieved'] = 'yes';
	if($process_result=='0: Success') $response['td_bank_recieved'] = 'internalerror';
	if(strtolower($status) == "yes")
	{
		$response['status'] = "A";
		$response['errormsg'] = $ProcessStatusText;
	}
	$response['td_bank_transaction_id'] = $ProcessOrderID;
	return $response;
}


// Check
function ch_CheckGateway_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	if ($bankInfo['bk_ch_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}


	$transInfo['phonenumber'] = preg_replace('/[^0-9]/','',$transInfo['phonenumber']);
	if(strlen($transInfo['phonenumber'])!=10) $transInfo['phonenumber'] = '1231231234';

	foreach($transInfo as $key => $data)
		$transInfo[$key]=urlencode($data);


	parse_str($bankInfo['bk_additional_id'],$loginparams);
	if(!$transInfo['td_is_a_rebill'])
	{
		if($loginparams['mid']) $bankInfo['bk_username'] = $loginparams['mid'];
		if($loginparams['mpw']) $bankInfo['bk_password'] = $loginparams['mpw'];
	}
	else
	{
		if($loginparams['rmid']) $bankInfo['bk_username'] = $loginparams['rmid'];
		if($loginparams['rmpw']) $bankInfo['bk_password'] = $loginparams['rmpw'];
	}	

	$Pinfo="";
	$Pinfo.="Action=Debit"; 		// Required
	$Pinfo.="&MerchantID=".$bankInfo['bk_username'];// Required
	$Pinfo.="&Password=".$bankInfo['bk_password'];// Required
	$Pinfo.="&ReferenceNumber=".$transInfo['reference_number'];// Required
	$Pinfo.="&Amount=".$transInfo['amount']; 			// Required
	$Pinfo.="&AccountNumber=".$transInfo['bankaccountnumber']; 		// Required
	$Pinfo.="&RoutingNumber=".$transInfo['bankroutingcode']; 		// Required
	$Pinfo.="&CheckNumber="; 		// Optional
	$Pinfo.="&FullName=".$transInfo['name']."+".$transInfo['surname']; 			// Optional
	$Pinfo.="&Address1=".substr($transInfo['address'],0,35); 			// Optional
	$Pinfo.="&Address2="; 			// Optional
	$Pinfo.="&City=".$transInfo['city']; 				// Optional
	$Pinfo.="&State=".$transInfo['state']; 				// Optional
	$Pinfo.="&Zip=".substr($transInfo['zipcode'],0,5); 				// Optional
	$Pinfo.="&PhoneNumber=".$transInfo['phonenumber']; 		// Optional
	$Pinfo.="&Email=".$transInfo['email']; 				// Optional
	$Pinfo.="&BirthDate="; 			// Optional
	$Pinfo.="&SSN="; 				// Optional
	$Pinfo.="&DLN="; 				// Optional
	$Pinfo.="&DLNIssueState="; 		// Optional

	// Uncomment below for live
	$output_url = "http://www.checkgateway.com/EPNPublic/ACH.aspx";

	$process_result = file_get_contents($output_url."?". $Pinfo);
	$clean_data = str_replace(";","&",trim($process_result));
	parse_str($process_result);

	$response=NULL;
	$response['errormsg'] = "Check Declined: $ResultText";
	$response['td_bank_transaction_id'] = $TransactionID;
	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_bank_recieved'] = 'no';
	$response['status'] = "D";
	if ($Result == "ACCEPTED")
	{
		$response['errormsg'] = "Check Approved";
		$response['status'] = "P";
		$response['td_bank_recieved'] = 'yes';
	}
	$response['success'] = true;
	//print_r($clean_data);
	//die();
	return $response;
}



function ch_AmeriNet_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	require_once("SOAP/Client.php");
	require_once("subFunctions/banks.amerinet.php");
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	if ($bankInfo['bk_ch_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}


	$Pinfo="";
	if(!$transInfo['phonenumber']) $transInfo['phonenumber'] = '4801234567';
	$Pinfo.="sFirstName=".$transInfo['name']; 					// Consumer's first name   	      20
	$Pinfo.="&sLastName=".$transInfo['surname']; 				// Consumer's Last name   	      35
	$Pinfo.="&sSuffix="; 										// Consumer's name suffix.Example: Jr, III, Esq, MD   	      12
	$Pinfo.="&sCompanyName="; 									// Company name if company check. FirstName and LastName are still required.   	      40
	$Pinfo.="&sGender="; 										// Consumer's gender. "M" = Male, "F" = Female.   	      1
	$Pinfo.="&sStreetAddress=".$transInfo['address']; 			// Consumer's street address of residence.   	      50
	$Pinfo.="&sCity=".$transInfo['city']; 						// Consumer's city of residence.   	      30
	$Pinfo.="&sStateAbbr=".$transInfo['state']; 				// Consumer's state of residence (2-character abbreviation).   	      2
	$Pinfo.="&sZip=".$transInfo['zipcode']; 					// Consumer's zip code, or postal code, of residence.   	      11
	$Pinfo.="&sCountry=".$transInfo['country']; 				// Consumer's country of residence (2-character abbreviation).   	      2
	$Pinfo.="&sPhone=".$transInfo['phonenumber']; 				// Consumer's telephone number.   	      20
	$Pinfo.="&dtDateOfBirth=1970-01-01T17:00:00Z"; 				// Consumer's date of birth. / Hardcode to 01/011970   	             sSocialSecurity   	      Consumer's social security number.   	      11
	$Pinfo.="&sSocialSecurity=000000000"; 						// Consumer's driver's license number.   	      30
	$Pinfo.="&sDriversLicense=R5251197854690"; 					// Consumer's driver's license number.   	      30
	$Pinfo.="&sDriversLicenseState="; 							// Consumer's driver's license state (2-character abbreviation).   	      2
	$Pinfo.="&sEmailAddress=".$transInfo['email']; 				// Consumer's email address.   	      50
	$Pinfo.="&sUserDef=".$transInfo['reference_number']; 		// Merchant's Order ID (alpha-numeric).   	      50
	$Pinfo.="&sMicrLine=".$transInfo['bankroutingcode'].$transInfo['bankaccountnumber']; // Consumer's MICR line. Routing number + account Number   	      35
	$Pinfo.="&sCheckNumber=99999999"; 							// Hardcode to 99999999   	      8
	$Pinfo.="&sCheckType=P"; 									// Acceptable values are "P" = Personal, "C" = Company, "S" = Savings.   	      1
	$Pinfo.="&sCountryCodeMicr=US"; 							// Hardcode to US   	      2
	$Pinfo.="&dblAmount=".$transInfo['amount']; 				// The total decimal dollar amount of the order.
	$Pinfo.="&nCycleCount=1"; 									// For multi-pay orders, an integer to multiply frequencies. exp. a value of 2 and a frequency of "m" would specify that recurring payments be scheduled to occur every 2 months, for the number of payment cycles specified in cycle count
	$Pinfo.="&dblFirstPayAdd=0.00"; 							// An amount to add to or subtract from the first payment of a multi-pay order. Default is 0.00 for single-pay orders.          	             nTypeCode   	      Hardcode to "0"   	             sCheckSource   	      Harcode to WEB   	      3
	$Pinfo.="&sCycleFrequency=yyyy"; 							// For multi-pay orders, an integer to multiply frequencies. exp. a value of 2 and a frequency of "m" would specify that recurring payments be scheduled to occur every 2 months, for the number of payment cycles specified in cycle count          	             dblFirstPayAdd   	      An amount to add to or subtract from the first payment of a multi-pay order. Default is 0.00 for single-pay orders.
	$Pinfo.="&nTypeCode=0"; 									// Hardcode to "0"   	             sCheckSource   	      Harcode to WEB   	      3
	$Pinfo.="&nCycleFrequencyMultiplier=1"; 					// For multi-pay orders, an integer to multiply frequencies. exp. a value of 2 and a frequency of "m" would specify that recurring payments be scheduled to occur every 2 months, for the number of payment cycles specified in cycle count          	             dblFirstPayAdd   	      An amount to add to or subtract from the first payment of a multi-pay order. Default is 0.00 for single-pay orders.          	             nTypeCode   	      Hardcode to "0"
	$Pinfo.="&sCheckSource=WEB"; 								// Harcode to WEB   	      3

	$processor = new AmeriNet_Client($bankInfo,$transInfo['td_is_a_rebill'],(intval($transInfo['amount'])<10));

	$processor = new AmeriNet_Client($bankInfo);
	$process_result = $processor->ExecuteOrderDebit($Pinfo);

	$response=NULL;
	$response['errormsg'] = "Check Declined: " . $process_result['ResultCodeText'];
	$response['td_bank_transaction_id'] = $process_result['TransactionID'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = "sSiteID=".$processor->sSiteID.", sProductID=".$processor->sSiteID.", ".$Pinfo;
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if($process_result['ResultCode'] == '0101')
	{
		$response['errormsg'] = "Check Accepted";
		$response['status'] = "P";
	}
	return $response;
}

// Web900

function web900_request_integration($transInfo)
{
	$genPin = substr(time(),0,5);
	$Pinfo="";
	$Pinfo.="client_id=3B1204";
								// A unique ID assigned to each client by NWNT
	$Pinfo.="&request=ADDPIN";
	$Pinfo.="&dnis=2267275"; 	// The DNIS to assign this PIN on.
	$Pinfo.="&prepin=$genPin";   // This PIN will only be assigned to callers who enter this pre-PIN.
	$Pinfo.="&pin=$genPin"; 		// A PIN to assign to a caller.

	// Uncomment below for live
	$output_url = "http://www.nwnt.com/genpin/genpin_manage.php?".$Pinfo;
	print($output_url."<br>");

	// start output buffer to catch curl return data
	$process_result = @file_get_contents($output_url);
	$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result['0']))));
	parse_str($clean_data);
//	print_r($process_result);
//	die();

	// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
	$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result))));

	// parse the string into variablename=variabledata
	parse_str($clean_data);
//	print_r($clean_data);
//	die();
	//echo "Response Data ".$clean_data;

	// output some of the variables
	//echo "Response Type = ".$pg_response_type."<br />";
	//echo "Response Code = ".$pg_response_code."<br />";
	//echo "Response Description = ".$pg_response_description."<br />";

}

function cc_EuroPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	$response['td_bank_recieved'] = 'no';
	//include('snoopy/Snoopy.class.php');

	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}
	//$cust_state = func_get_state($transInfo['state'],'st_full');

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);

	$expMonth = $expDate[1];
	$expDate=$expMonth.$expYear;


	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	$cust_state = urlencode(func_get_state($transInfo['state'],'st_abbrev'));
	//if(strlen($cust_state)>2)$cust_state="";
	if($cust_cntry=='United+States') $cust_cntry = 'US';
	//foreach($transInfo as $key => $info)
		//$transInfo[$key] = urlencode($info);

	//$cardtype = strtolower($transInfo['cardtype']);
	//if($cardtype!='mastercard')$cardtype='visa';
	
	$cardtype = 'visa';
	if(substr($transInfo['CCnumber'],0,1) == '5') $cardtype = 'master';
	//$bank_trans_id = rand(0,400000000);
	//$response['td_bank_transaction_id'] = $bank_trans_id;

	foreach($transInfo as $key => $item)
		$transInfo[$key] = urlencode($item);

	if($transInfo['amount']<5)$transInfo['amount']=5;
	$Pinfo="";  //  this is an unique ID defined by our system
	$Pinfo.="ResellerID=".$bankInfo['bk_additional_id'];  //  this is an unique ID defined by our system
	$Pinfo.="&MerchantSign=".$bankInfo['bk_username'];  //  also an unique ID defined by our System
	$Pinfo.="&MerchantPassword=".$bankInfo['bk_password'];  //  a password you can specify
	$Pinfo.="&ReferenceNumber=".$transInfo['reference_number'];  //  a (unique) reference transaction number of your system
	$Pinfo.="&TransType=sale";  //  the type of transaction you want to process
	//$Pinfo.="&TransactionID=".$bank_trans_id;  //  our reference transactionID of the sale or auth transaction
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
	$Pinfo.="&CardholderState=".$cust_state;  //  card holder state (ISO 3166/2)
	$Pinfo.="&CardholderCountry=".$cust_cntry;  //  card holder country (ISO 3166/2)
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

	$response="";
	$response['errormsg'] = $resultAr['ResultText'];
	if ( $response['errormsg'] == 'status Offline') $response['errormsg'] = 'SO Declined';
	if ( $response['errormsg'] == 'transaction type not valid') $response['errormsg'] = 'TTNV Declined';
	$response['errorcode'] = $resultAr['ResultCode'];
	$response['td_bank_transaction_id'] = $resultAr['TransactionID'];
	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['status'] = "D";
	$response['td_bank_recieved'] = 'yes';
	if(!is_array($resultAr)) $response['td_bank_recieved'] = 'internalerror';
	if ($resultAr['Result']=="OK")
	{
		$response['errormsg'] = "Credit Card Accepted";
		$response['status'] = "A";
	}
	$response['success'] = true;
	return $response;
}



function cc_record_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = true;
	$response['td_bank_recieved'] = 'no';

	$response['status'] = "A";
	$response['td_process_query'] = "Recording Transaction";
	$response['td_process_result'] = "Transaction Recorded";
	$response['td_bank_recieved'] = 'yes';
	return $response;
}

?>
