<?php


function cc_EuroPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;
	$response['td_bank_recieved'] = 'no';
	
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

	$cardtype = strtolower($transInfo['cardtype']);	
	
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

?>
