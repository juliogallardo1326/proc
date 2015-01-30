<?php 
if(!function_exists('http_build_query')) {
   function http_build_query( $formdata, $numeric_prefix = null, $key = null ) {
       $res = array();
       foreach ((array)$formdata as $k=>$v) {
           $tmp_key = (is_int($k) ? $numeric_prefix.$k : $k);
           if ($key) {
               $tmp_key = $key.'['.$tmp_key.']';
           }
           if ( is_array($v) || is_object($v) ) {
               $res[] = http_build_query($v, null, $tmp_key);
           } else {
               $res[] = $tmp_key."=".($v);
           }
       }
	   $return = implode("&", $res);
       return stripslashes($return);
   }
}
chdir("..");
require_once("includes/dbconnection.php");
$etel_debug_mode=0;
require_once("includes/integration.php");
		
		$transInfo = null;
		
		$responseInfo = null;
		$responseInfo['error']=0;
		
		$queryInfo = null;
		
		$queryInfo['mt_reference_id'] = quote_smart($_REQUEST['mt_reference_id']);
		//$queryInfo['mt_subAccount'] = quote_smart($_REQUEST['mt_subAccount']);
		$queryInfo['mt_prod_desc'] = quote_smart($_REQUEST['mt_prod_desc']);
		$queryInfo['mt_product_id'] = quote_smart($_REQUEST['mt_product_id']);
		$queryInfo['mt_amount'] = quote_smart($_REQUEST['mt_amount']);
		$queryInfo['mt_firstname'] = quote_smart($_REQUEST['mt_firstname']);
		$queryInfo['mt_lastname'] = quote_smart($_REQUEST['mt_lastname']);
		$queryInfo['mt_address'] = quote_smart($_REQUEST['mt_address']);
		$queryInfo['mt_address2'] = quote_smart($_REQUEST['mt_address2']);
		$queryInfo['mt_country'] = quote_smart($_REQUEST['mt_country']);
		$queryInfo['mt_city'] = quote_smart($_REQUEST['mt_city']);
		$queryInfo['mt_state'] = quote_smart($_REQUEST['mt_state']);
		$queryInfo['mt_zipcode'] = quote_smart($_REQUEST['mt_zipcode']);
		$queryInfo['mt_telephone'] = quote_smart($_REQUEST['mt_telephone']);
		$queryInfo['mt_email'] = quote_smart($_REQUEST['mt_email']);
		$queryInfo['mt_ip'] = quote_smart($_REQUEST['mt_ip']);
		$queryInfo['mt_live_mode'] = quote_smart($_REQUEST['mt_live_mode']);
		$queryInfo['mt_md5_checksum'] = quote_smart($_REQUEST['mt_md5_checksum']);
		$queryInfo['mt_chargetype'] = quote_smart($_REQUEST['mt_chargetype']);
		
		if(!$queryInfo['mt_ip']) $queryInfo['mt_ip'] = $_SERVER['REMOTE_ADDR'];
		if($queryInfo['mt_live_mode'] != "Live") $queryInfo['mt_live_mode'] = "Test";
		
		
		// CreditCard
		
		if( strtolower($queryInfo['mt_chargetype']) == 'credit')
		{
			$queryInfo['mt_creditcard'] = quote_smart($_REQUEST['mt_creditcard']);
			$queryInfo['mt_cardtype'] = quote_smart($_REQUEST['mt_cardtype']);
			if($queryInfo['mt_cardtype'] == 'master') $queryInfo['mt_cardtype'] = 'mastercard';
			$queryInfo['mt_cvs'] = quote_smart($_REQUEST['mt_cvs']);
			$queryInfo['mt_expire'] = quote_smart($_REQUEST['mt_expire']);
			$queryInfo['mt_bankphone'] = quote_smart($_REQUEST['mt_bankphone']);	
			if( strtolower($queryInfo['mt_cardtype']) == 'mastercard' || strtolower($queryInfo['mt_cardtype']) == 'visa' )
			{
			
			}
			else
			{
				$responseInfo['error']=1;
				$responseInfo['errormsg'][]="Only 'mt_cardtype' Type 'mastercard' or 'visa' is allowed at this time";
			}
			
		}
		else
		{
			$responseInfo['error']=1;
			$responseInfo['errormsg'][]="Only 'mt_chargetype' Type 'credit' is allowed at this time";
		}		
		
		$sql="SELECT en_ID,userId,bank_Creditcard,cs_ID,cc_customer_fee,cd_allow_gatewayVT,cd_secret_key FROM `cs_companydetails` as c left join `cs_entities` as en on en.en_type_ID = c.`userId` and en.en_type='Merchant' left join `cs_company_sites` as s on s.cs_company_id = c.`userId` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND s.`cs_reference_ID` = '".$queryInfo['mt_reference_id']."'";
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		if(mysql_num_rows($result)<1) 
		{
			$responseInfo['error']=1;
			$responseInfo['errormsg'][]= "Invalid 'mt_reference_id' Company/Website";
		}
		$companyInfo = mysql_fetch_assoc($result);	
		
		if(!$companyInfo['cd_allow_gatewayVT'])
		{
			$responseInfo['error']=1;
			$responseInfo['errormsg'][]= "Your Company may not use this Gateway Terminal. Please Contact Administration.";				
		}
		
		$md5_checksum=md5($queryInfo['mt_reference_id'].$queryInfo['mt_amount'].$queryInfo['mt_firstname'].$queryInfo['mt_country'].$queryInfo['mt_email'].$companyInfo['cd_secret_key']);
		if($md5_checksum != $queryInfo['mt_md5_checksum'])
		{
			$responseInfo['error']=1;
			$responseInfo['errormsg'][]= "Invalid 'mt_md5_checksum' Checksum. Please verify your checksum calculations.";		
			if($etel_debug_mode) $responseInfo['errormsg'][]= "$md5_checksum";		
		}
		
		if($responseInfo['error']!=0)
		{
				
			toLog('login','customer', "Merchant Gateway Error:".print_r($responseInfo,true), -1);
			die(http_build_query($responseInfo));
		}
		toLog('login','customer', "Merchant Gateway Access:".print_r($_REQUEST,true), $companyInfo['userId']);
		
		$transInfo['checkorcard']='H';	
		$transInfo['transactionId']='';
		$transInfo['Invoiceid']='';
		$transInfo['name']=$queryInfo['mt_firstname'];
		$transInfo['surname']=$queryInfo['mt_lastname'];
		$transInfo['phonenumber']=$queryInfo['mt_telephone'];
		$transInfo['bankname']=$queryInfo['mt_lastname'];
		$transInfo['td_bank_number']=$queryInfo['mt_bankphone'];
		//$transInfo['bankroutingcode']=$queryInfo['mt_lastname'];
		//$transInfo['bankaccountnumber']=$queryInfo['mt_lastname'];
		$transInfo['address']=$queryInfo['mt_address'];
		$transInfo['address2']=$queryInfo['mt_address2'];
		$transInfo['CCnumber']=$queryInfo['mt_creditcard'];
		$transInfo['cvv']=$queryInfo['mt_cvs'];
		$transInfo['country']=$queryInfo['mt_country'];
		$transInfo['city']=$queryInfo['mt_city'];
		$transInfo['state']=$queryInfo['mt_state'];
		$transInfo['ostate']=$queryInfo['mt_state'];
		$transInfo['zipcode']=$queryInfo['mt_zipcode'];
		$transInfo['amount']=$queryInfo['mt_amount'];
		$transInfo['memodet']='';
		$transInfo['signature']='';
		$transInfo['accounttype']='';
		$transInfo['misc']=$misc;
		$transInfo['email']=$queryInfo['mt_email'];
		$transInfo['td_send_email']="no";
		$transInfo['cancelstatus']='N';
		$transInfo['status']='D';
		$transInfo['userId']=$companyInfo['userId'];
		$transInfo['en_ID']=$companyInfo['en_ID'];
		$transInfo['Checkto']='';
		$transInfo['cardtype']=$queryInfo['mt_cardtype'];
		$transInfo['checktype']='';
		$transInfo['validupto']=$queryInfo['mt_expire'];
		$transInfo['reason']='';
		$transInfo['other']='';
		$transInfo['ipaddress']=$queryInfo['mt_ip'];
		$transInfo['cancellationDate']='';
		$transInfo['voiceAuthorizationno']='-1';
		$transInfo['billingDate']='';
		$transInfo['passStatus']='PA';
		$transInfo['chequedate']='';
		$transInfo['pass_count']='0';
		$transInfo['approvaldate']='';
		$transInfo['nopasscomments']='';
		$transInfo['approval_count']='0';
		$transInfo['declinedReason']='';
		$transInfo['service_user_id']='0';
		$transInfo['admin_approval_for_cancellation']='';
		$transInfo['company_usertype']='4';
		$transInfo['company_user_id']=$companyInfo['userId'];
		$transInfo['callcenter_id']='0';
		$transInfo['productdescription']=$queryInfo['mt_prod_desc'];
		$transInfo['reference_number']='';
		$transInfo['currencytype']="USD";
		$transInfo['cancel_refer_num']='0';
		$transInfo['cancel_count']='0';
		//$transInfo['return_url']=$i_return_url;
		//$transInfo['from_url']=$from_url;
		
		
		
		$transInfo['td_rebillingID']=-1;
		$transInfo['td_is_a_rebill']='0';
		$transInfo['td_enable_rebill']=0;
		$transInfo['td_voided_check']='0';
		$transInfo['td_returned_checks']='0';
		$transInfo['td_site_ID']=$companyInfo['cs_ID'];
		$transInfo['payment_schedule']='';
		$transInfo['nextDateInfo']='';
		$transInfo['td_one_time_subscription']='';
		$transInfo['billing_descriptor'] = '';
		//$transInfo['td_ca_ID'] = $_SESSION['ca_ID'];
		
		$transInfo['td_is_affiliate']='0';
		$transInfo['td_is_pending_check']='0';
		$transInfo['td_is_chargeback']='0';
		$transInfo['td_recur_processed']='0';
		//$transInfo['td_recur_next_date']=$td_recur_next_date;
		//$transInfo['td_username']=$td_username;
		//$transInfo['td_password']=$td_password;
		$transInfo['td_product_id']=$queryInfo['mt_product_id'];
		$transInfo['td_customer_fee']=$companyInfo['cc_customer_fee'];
		
		$bank_ids = merchant_getTransTypes($transInfo['en_ID'],&$transInfo);
		foreach($bank_ids as $bank)
		{
			if(strtolower($bank['bank_description']) == $transInfo['cardtype'])
				$transInfo['bank_id']=$bank['bank_id'];
		}
				
		$responseInfo['mode']=$queryInfo['mt_live_mode'];
		$response = execute_transaction(&$transInfo,$queryInfo['mt_live_mode']);
		$responseInfo['td_reference_id']=$response['reference_number'];
		if($response['status']=='A') 
		{
			$responseInfo['error']=0;
			$responseInfo['approved']=1;
			$responseInfo['amount']=$transInfo['amount'];
		
		}
		else 
		{
			$responseInfo['error']=1;
			if($response['success'])
			{
				$responseInfo['approved']=0;
				$responseInfo['amount']=$transInfo['amount'];		
				$responseInfo['error']=0;
				
			}
			$responseInfo['errormsg'][]=$response['errormsg'];
		}
		
		die(http_build_query($responseInfo));
	
?>
