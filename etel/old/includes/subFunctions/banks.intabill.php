<?
class intabill_Client 
{

    function intabill_Client() 
	{
    }
 	
	function Execute_Sale(&$transInfo,&$bankInfo,&$companyInfo)
	{
		$client = new nusoapclient('https://service.merchlogin.com/?wsdl', true);  
		$sid  = 20115;  
		$rcode= $bankInfo['bk_additional_id'];  
		$uip  = strval($transInfo['ipaddress']); 	
		if($bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']]) 
			$sid = $bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']];
	
		
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
		$process_result = $client->call('ProcessCreditCard', $params); 
		$response=array();
		
		$response['td_bank_transaction_id'] = $process_result['TransactionID'];
		$response['td_process_result'] = serialize($process_result);
		$response['td_process_query'] = serialize($params);
		$response['td_bank_recieved'] = 'yes';
		//if(in_array($process_result['Status'],array('5','4','2'))) 
		//	$response['td_bank_recieved'] = 'no';
		if($process_result['Status'] == '1')
		{
			$response['status'] = "A";
			$response['td_bank_recieved'] = 'yes';
			$response['td_process_msg'] = $process_result['Message'] . ": Approved" ;
		}
		else
		{
			if(in_array($process_result['Status'],array('5','4','2'))) 
				$response['td_bank_recieved'] = 'no';
			$response['status'] = "D";
			$response['errormsg'] = $process_result['Message'];
			if(!isset($process_result['Status'])) $response['errormsg'] = "Declined (SoEx)";
		}
		return $response;
		
	}

}


?>