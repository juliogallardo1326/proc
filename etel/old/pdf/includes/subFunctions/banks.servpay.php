<?
class servpay_Client 
{

    function servpay_Client() 
	{
    }
 	
	function Execute_Sale(&$transInfo,&$bankInfo,&$companyInfo)
	{
		$client = new soapclient('https://soap.Servpay.com/tx2.php?wsdl', true);  
		$sid  = 20115;  
		$rcode= $bankInfo['bk_additional_id'];  
		$uip  = strval($transInfo['ipaddress']); 	
		if($bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']]) 
			$sid = $bankInfo['cb_config']['custom']['tid_sites'][$transInfo['td_site_ID']];
	
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];
	
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_full'));

	$transInfo['cardtype'] = 'VISA';
	if(substr($transInfo['CCnumber'],0,1) == '5') $transInfo['cardtype'] = 'MASTERCARD';
	
		$udetails = array( 
		 'username'  => '', 
		   'password'  => '', 
		   'card_name'  => strval($transInfo['name'] . " " . $transInfo['surname']), 
		   'card_no'  => strval($transInfo['CCnumber']), 
		   'card_type'  => $transInfo['cardtype'], 
		   'card_ccv' => strval($transInfo['cvv']), 
		   'card_exp_month' => strval($expMonth), 
		   'card_exp_year'  => strval($expYear),  
		   'bank_name'   => '',  
		   'bank_phone'   => $transInfo['td_bank_number'], 
		   'firstname' => strval($transInfo['name']), 
		   'lastname'   => strval($transInfo['surname']), 
		   'email'  => strval($transInfo['email']), 
		   'phone'  => strval($transInfo['phonenumber']), 
		   'mobile'   => '', 
		   'address'   => strval($transInfo['address']), 
		   'suburb_city'  => strval($transInfo['city']), 
		   'state'  => strval($transInfo['state']), 
		   'postcode'  => strval($transInfo['zipcode']), 
		   'country'   => strval($cust_cntry), 
		   'ship_firstname' => strval($transInfo['name']), 
		   'ship_lastname'   => strval($transInfo['surname']), 
		   'ship_address' => strval($transInfo['address']), 
		   'ship_suburb_city'=> strval($transInfo['city']), 
		   'ship_state' => strval($transInfo['state']), 
		   'ship_postcode'  => strval($transInfo['zipcode']), 
		   'ship_country'   => strval($cust_cntry) 
		   );  
		
		$txparams = array( 
		 'ref1'  => 'NULL', 
		   'cmd' => '',  
		   'vbv' => NULL 
		 );        
		
		
		$cart = array(       
		'items' => array( 
		  array( 
		 'name'=>'null', 
		 'quantity'=> 1, 
		 'amount_unit'=>round($transInfo['amount'],2), 
		 'item_no'=>'', 
		 'item_desc'=>''    
			)		     
		 ), 
		'summary' => array( 
		   'quantity'=> 1, 
		   'amount_purchase'=>round($transInfo['amount'],2), 
		   'amount_shipping'=>'0', 
		   'currency_code' => 'USD' 
		   ) 
		);  
		$param = array( 
		'sid'  => $sid,  
		'rcode'  => $rcode,  
		'uip'   => $uip, 
		'udetails'   => $udetails,  
		'cart'  => $cart,
		'txparams'  => $txparams    
		);    

		$process_result = $client->call('processSinglePayTx', $param); 
		print_r($param);print_r($process_result);
		
		$response=NULL;
		
		$response['td_bank_transaction_id'] = $process_result['txid'];
		$response['td_process_result'] = serialize($process_result);
		$response['td_process_query'] = serialize($params);
		$response['td_bank_recieved'] = 'yes';
		//if(in_array($process_result['Status'],array('5','4','2'))) 
		//	$response['td_bank_recieved'] = 'no';
		if($process_result['status'] == 'OK')
		{
			$response['status'] = "A";
			$response['td_bank_recieved'] = 'yes';
			$response['td_process_msg'] = $process_result['status'] . ": Approved" ;
		}
		else
		{
			$response['status'] = "D";
			$response['errormsg'] = $process_result['error']['msg'] . " ". $process_result['error']['info'];
			if(!$response['errormsg']) $response['errormsg'] = "Declined (SoEx)";
		}
		return $response;
		
	}

}


?>