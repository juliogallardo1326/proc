<?php

function cc_check_banlist($transInfo,$banInfo="")
{
			//(SELECT group_concat(`bl_type`,'=',`bl_data` SEPARATOR  '&') as ban FROM `cs_banlist` where `bl_group` is not null group by bl_group) union (SELECT concat(`bl_type`,'=',`bl_data`) as ban FROM `cs_banlist` where `bl_group` is null)
	$bl_type = func_get_enum_data('cs_banlist','bl_type');
	$ban_sql = " 0 ";
	foreach($bl_type as $key)
		$ban_sql .= " OR (bl_type='$key' AND '".$transInfo[$key]."' LIKE bl_data) \n";
			// select sum(ban) as bansfound from (SELECT count(bl_ID) = sum((bl_type='name' AND bl_data = 'sebastian') OR (bl_type='address' AND bl_data = 'mancini')) as ban FROM `cs_banlist` group by `bl_group`) as bans
	$sql = "select sum(ban) as bansfound , group_concat(if(ban,banInfo,NULL)) as banInfo from (
				SELECT count(bl_ID) = sum($ban_sql) as ban, concat('bl_group=',`bl_group`,'&',group_concat(`bl_type`,'=',`bl_data` SEPARATOR  '&')) as banInfo
				FROM `cs_banlist` group by `bl_group`
				) as bans";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>~$sql");
	$bans = mysql_fetch_assoc($result);
	$banInfo=$bans['bansfound']." Ban(s) Found: \n";
	$banarray = explode(",",$bans['banInfo']);
	foreach($banarray as $data)
		{
			if(!$data) break;
			parse_str($data,$data);
			$banInfo.="  Group ".$data['bl_group']." found ";
			unset($data['bl_group']);
			foreach($data as $bl_type => $bl_data)
				$banInfo.=$bl_type."='".$bl_data."' and ";
			$banInfo = substr($banInfo,0,-5);
			$banInfo.="\n";
		}
	return ($bans['bansfound'] > 0);
			
}

function cc_check_previous_24h_approve($transInfo,$hours=24)
{ 
	if($transInfo['checkorcard'] == 'C') return;
	if($transInfo['td_is_a_rebill']) return 0 ;
	$hours = intval($hours);
	if($hours<=1) $hours=1;
	$sql="SELECT transactionDate
FROM `cs_transactiondetails`
WHERE (`CCnumber` = '".etelEnc($transInfo['CCnumber'])."' or `ipaddress` = '".$transInfo['ipaddress']."')
AND `status` != 'D' AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$numrows = mysql_num_rows($result);
	//if($numrows > 0) toLog('erroralert','customer',$hours." ~ ".$sql);
	return ($numrows > 0);
	
}

function cc_check_previous_decline($transInfo,$hours=24)
{
	if($transInfo['td_is_a_rebill']) return 0 ;
	$sql="SELECT *
FROM `cs_transactiondetails`
WHERE `CCnumber` = '".etelEnc($transInfo['CCnumber'])."' AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)
AND (`status` != 'A' or `cancelstatus` = 'Y' or `td_is_chargeback`=1) && (`td_bank_recieved` = 'yes' or `td_bank_recieved` = 'fraudscrubbing')";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$numrows = mysql_num_rows($result);
	return ($numrows >= 3);
}


function ch_check_previous_decline($transInfo,$hours=24)
{
	return 0 ;
	$sql="SELECT *
FROM `cs_transactiondetails`
WHERE `bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."' AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)
AND (`status` != 'A' or `cancelstatus` = 'Y' or `td_is_chargeback`=1) && (`td_bank_recieved` = 'yes' or `td_bank_recieved` = 'fraudscrubbing')";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$numrows = mysql_num_rows($result);
	return ($numrows > 0);
}

function cc_check_for_gkard($transInfo)
{
	$sql="SELECT td_gcard
FROM `cs_transactiondetails`
WHERE `CCnumber` LIKE '".etelEnc($transInfo['CCnumber'])."'
AND (`status` = 'A' ) AND (`td_gcard` IS NOT NULL )";

	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	if (mysql_num_rows($result)<=0) return 0;
	$td_gcard = mysql_fetch_assoc($result);
	return (etelDec($td_gcard['td_gcard']));
}

function cc_check_unique($table,$transInfo)
{
	$sql="SELECT CCnumber
FROM `$table`
WHERE `CCnumber` = '".$transInfo['CCnumber']."'
";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$numrows = mysql_num_rows($result);
	return $numrows;
}

function fraud_scrub($transInfo,$bankInfo,$companyInfo)
{
 	global $etel_fraud_response;
	require_once('fraud/CreditCardFraudDetection.php');
	$ccfs = new CreditCardFraudDetection;
	
	// Set inputs and store them in a hash
	// See http://www.maxmind.com/app/ccv for more details on the input fields
	
	// Enter your license key here (non registered users limited to 20 lookups per day)
	 $h["license_key"] = "UHccvlc5aVqk";
	
	// Required fields
	
	$h["i"] = $transInfo['ipaddress'];             // set the client ip address
	$h["city"] = $transInfo['city'];             // set the billing city
	$h["region"] = $transInfo['state'];                 // set the billing state
	$h["postal"] = $transInfo['zipcode'];              // set the billing zip code
	$h["country"] = $transInfo['country'];                // set the billing country
	
	// Recommended fields
	$h["domain"] = substr(strstr($transInfo['email'], '@'),1);		// Email domain
	$h["bin"] = substr($transInfo['CCnumber'],0,6);			// bank identification number
	$h["forwardedIP"] = $transInfo['ipaddress'];	// X-Forwarded-For or Client-IP HTTP Header
	$h["custPhone"] = substr($transInfo['phonenumber'],0,3)."-".substr($transInfo['phonenumber'],4,6);		// Area-code and local prefix of customer phone number
	
	// Optional fields
	//$h["binName"] = "MBNA America Bank";	// bank name
	$h["binPhone"] = $transInfo['td_bank_number'];	// bank customer service phone number on back of credit card
	$h["requested_type"] = "premium";	// Which level (free, city, premium) of CCFD to use
	$h["emailMD5"] = md5(strtolower($transInfo['email'])); // CreditCardFraudDetection.php will take
	// MD5 hash of e-mail address passed to emailMD5 if it detects '@' in the string
	$h["shipAddr"] = $transInfo['address'];	// Shipping Address
	//$h["txnID"] = "1234";			// Transaction ID
	$h["sessionID"] = session_id();		// Session ID
	// If you want to disable Secure HTTPS or don't have Curl and OpenSSL installed
	// uncomment the next line
	// $ccfs->isSecure = 0;
	
	//set the time out to be five seconds
	$ccfs->timeout = 5;
	
	//uncomment to turn on debugging
	$ccfs->debug = 0;
	
	//next we pass the input hash to the server
	$ccfs->input($h);
	
	//then we query the server
	$ccfs->query();
	
	//then we get the result from the server
	$ho = $ccfs->output();
	//then finally we print out the result
	$outputkeys = array_keys($ho);
	$numoutputkeys = count($ho);
  	$noCity=0;
	for ($i = 0; $i < $numoutputkeys; $i++) {
	  $key = $outputkeys[$i];
	  $value = $ho[$key];
	  $tolog.= $key . " = " . $value . "\n";
	  if($key == 'err' && $value == 'CITY_NOT_FOUND') 
	  {
		//toLog('erroralert','customer', "Fraud Scrubbing Can't find City '".$h["city"]."' ".serialize($h)." ".$tolog);
  	  	$noCity=1;
	  }
	}
	toLog('order','customer', "Fraud Scrubbing Result for ".$transInfo['reference_number'].": ".$tolog,$transInfo['transactionId']);
	$etel_fraud_response=$tolog;
	return floatval($ho['score']-$noCity*2.60);
}

function execute_scrub_tests($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	global $etel_fraud_response;
	global $etel_disable_fraud_scrubbing;
	
	$etel_fraud_response=NULL;
	
	if($transInfo['td_is_a_rebill']) return -1;
	if($etel_disable_fraud_scrubbing) return -1;
	if($companyInfo['cd_orderpage_disable_fraud_checks']) return -1;
	
	$response['errormsg'] = "No Error (FS)";
	
	$banInfo = "";
	if(cc_check_banlist(&$transInfo,&$banInfo))
	{
		$response['errormsg'] = "Credit Card Declined.";
		$response['success'] = true;
		$response['td_process_result']="$banInfo.";
		$response['td_process_query']="Checking Ban List";
		$response['status'] = "D";
		$response['td_bank_recieved'] = 'banlist';
		
		return $response;
	}
	
	if(cc_check_previous_decline(&$transInfo,$companyInfo['cd_approve_timelimit']))
	{
		$response['errormsg'] = "Credit Card Declined.";
		$response['success'] = true;
		$response['td_process_result']="Credit Card Previously Declined in the last ".$companyInfo['cd_approve_timelimit']." hours. Will not try again.";
		$response['td_process_query']="Checking for Previous Declines";
		$response['status'] = "D";
		$response['td_bank_recieved'] = 'previousdecline';
		
		return $response;
	}
	
	if($etel_fraud_limit>0)	$transInfo['td_fraud_score'] = fraud_scrub(&$transInfo,&$bankInfo,&$companyInfo);
	if($transInfo['td_fraud_score']>$etel_fraud_limit)
	{
		$response['td_process_result']="Fraud Score: ".$transInfo['td_fraud_score']." Response: $etel_fraud_response";
		$response['td_process_query']="Fraud Scrubbing...";
		$response['td_bank_transaction_id']="";
		$response['status'] = "D";
		$response['success'] = true;
		$response['errormsg'] = "Credit Card Declined.";
		$response['td_bank_recieved'] = 'fraudscrubbing';
		
		return $response;
	}		
	return -1;
	
}

function http_post2($server, $port, $url, $params, $username="", $password="") 
{
	$ch = curl_init();

	$parseurl =	parse_url($url);
	$postmet = $parseurl['scheme'];
	$postser = $parseurl['host'];
	$is_ssl = stristr($postmet,"https") !== FALSE;
	//$postport = stristr($postmet,"https") !== FALSE ? "443" : "80";
	//$postser = (stristr($postmet,"https") !== FALSE ? "ssl://" : "") . $postser;
	
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	if($username || $password)
	{
		curl_setopt($ch, CURLOPT_USERPWD, '$username:$password');
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	}
	
	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$content = curl_exec ($ch);

	return $content;
}

function http_post($server, $port, $url, $urlencoded, $username="", $password="") 
{

// example:
//  http_post(
//	"www.fat.com",
//	80,
//	"/weightloss.pl",
//	array("name" => "obese bob", "age" => "20")
//	);

	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";

$base_64_auth = base64_encode($username.":".$password);
	$content_length = strlen($urlencoded);
	$headers = "POST $url HTTP/1.1
Accept: */*
Accept-Language: en-au
Content-Type: application/x-www-form-urlencoded
User-Agent: $user_agent
Host: $server
Connection: Keep-Alive
Cache-Control: no-cache
Authorization: Basic $base_64_auth
Content-Length: $content_length

";
	$time = microtime_float();
	$fp = fsockopen($server, $port, &$errno, &$errstr,45);
	if (!$fp) {
		return "$errno: $errstr";
	}
	fputs($fp, $headers);
	fputs($fp, $urlencoded);

	$ret = "";
	$body = false;
     while (!feof($fp)) {
       $s = @fgets($fp, 1024);
	   
       if ( $body)
           $ret .= $s;
       if ( $s == "\r\n" )
           $body = true;
	   if( (microtime_float()-$time)>45) return "Timeout: $ret";
   }
	fclose($fp);
	
	return $ret;
}

function execute_transaction($transInfo,$mode)
{
	global $cnn_cs;
	global $etel_fraud_limit;

	$process_trans = new rates_fees();

	ignore_user_abort(true);
	set_time_limit(500);
	
	if(!$_SESSION['tmpl_language']) $_SESSION['tmpl_language'] = 'eng';
	
	$response="";
	$response['errormsg'] = "No Error";
	$response['success'] = false;
	$gw_emails_sales = $_SESSION['gw_emails_sales'];
	
	foreach($transInfo as $key=>$data)
		$transInfo[$key] = str_replace("'","`",urldecode($data));
		
	if(!$transInfo['checkorcard']) dieLog("Error. No Transaction Type Selected. ".serialize($transInfo));
	
	if(!$transInfo['reference_number']) $transInfo['reference_number'] = genRefId("transaction",$transInfo['checkorcard']);
	if(!$transInfo['td_subscription_id'])  $transInfo['td_subscription_id'] = genRefId("subscription","S");
		
	if(!$transInfo['reference_number']) {$response['errormsg'] = "Invalid Reference Number"; return $response;}
	if(!$transInfo['userId']) {$response['errormsg'] = "Invalid Merchant Id #" . $transInfo['userId']; return $response;}
	if(!$transInfo['checkorcard'])  {$response['errormsg'] = "Invalid Payment Type"; return $response;}

	$sql="SELECT * FROM `cs_companydetails` as c left join `etel_dbsmain`.`cs_company_sites` as s on s.cs_company_id = c.`userId` WHERE c.`userId` = '".$transInfo['userId']."' and s.`cs_ID` = '".$transInfo['td_site_ID']."'";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	if(mysql_num_rows($result)<1 && $mode=="Live") {$response['errormsg'] = "Invalid Company/Website"; return $response;}
	$companyInfo = mysql_fetch_assoc($result);	

	////// find a valid bank that the merchant is using that can process for the cardtype
	$bank_ids = merchant_getBanksForTransType($transInfo['userId'],$transInfo['cardtype']);
	if(sizeof($bank_ids) == 0) {$response['errormsg'] = "Invalid Card Type"; return $response;}
	$company_bank_id = $bank_ids[0];
	$transInfo['bank_id'] = $company_bank_id;
	///////////

	//todo:
	if(isset($transInfo['wallet_additional_funds']))
	{
		//addtowallet $transInfo['wallet_additional_funds'] $transInfo['wallet_id'] $transInfo['wallet_pass']
	}
	//	
	
	if(!$etel_fraud_limit) $etel_fraud_limit = floatval($companyInfo['cd_fraudscore_limit']);
	
	$sql="SELECT b.* FROM `cs_bank` as b where b.bank_id = $company_bank_id ";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."User: ".$companyInfo['userId'].", checkorcard=".$transInfo['checkorcard']);
	if(mysql_num_rows($result)<1 && $mode=="Live") {$response['errormsg'] = "Invalid Bank. Use Credit Card Ordering instead."; toLog('erroralert','misc',$sql."User: ".$companyInfo['userId'].", checkorcard=".$transInfo['checkorcard']); return $response;}
	$bankInfo = mysql_fetch_assoc($result);
	if(!$bankInfo['bk_int_function'] && $mode=="Live") {$response['errormsg'] = "Invalid Bank Integration"; return $response;}
	$transInfo['companyname'] = $companyInfo['companyname'];
	$transInfo['cs_enable_passmgmt'] = $companyInfo['cs_enable_passmgmt'];

	$transInfo['billing_descriptor'] = $bankInfo['bk_descriptor_visa'];
	if($transInfo['cardtype']=="Master") $transInfo['billing_descriptor'] = $bankInfo['bk_descriptor_master'];

	if(!$transInfo['td_is_a_rebill'])
	{
		if($transInfo['cs_enable_passmgmt'] && $transInfo['td_rebillingID'] != -1)
		{
			if(strlen($transInfo['td_username'])<6) {$response['errormsg'] = "Invalid UserName (Must be greater than 5 characters)"; return $response;}
			if(strlen($transInfo['td_password'])<6) {$response['errormsg'] = "Invalid Password (Must be greater than 5 characters)"; return $response;}
		}

		if(!$transInfo['name']) {$response['errormsg'] = "Invalid Name"; return $response;}
		if(!$transInfo['surname']) {$response['errormsg'] = "Invalid Last Name"; return $response;}
		if(!$transInfo['address']) {$response['errormsg'] = "Invalid Address"; return $response;}
		if(!$transInfo['city']) {$response['errormsg'] = "Invalid City"; return $response;}
		if(!$transInfo['phonenumber']) {$response['errormsg'] = "Invalid Phone Number"; return $response;}
		//if(!$transInfo['state']) {$response['errormsg'] = "Invalid State"; return $response;}
		if(!$transInfo['zipcode']) {$response['errormsg'] = "Invalid ZipCode"; return $response;}
		if(!$transInfo['country']) {$response['errormsg'] = "Invalid Country"; return $response;}
		if(!$transInfo['email']) {$response['errormsg'] = "Invalid Email"; return $response;}
		$email_info = infoListEmail($transInfo['email']);
		if($email_info['cnt']>0) {$response['errormsg'] = "Unsubscribed Email Address ".$transInfo['email'].".<BR>Reason: ".$email_info['ec_reason'].".<BR>Please use a different email address."; return $response;}
		if(!$transInfo['amount']) {$response['errormsg'] = "Invalid Charge Amount"; return $response;}
		if(!$transInfo['ipaddress']) {$response['errormsg'] = "Invalid IP Address"; return $response;}
	
		if(!$transInfo['productdescription']) {$response['errormsg'] = "Invalid Product Description"; return $response;}
		//if(!$transInfo['td_product_id']) {$response['errormsg'] = "Invalid Transaction Tracking ID"; return $response;}
	}
	
	$credit_card_formatted = "Payment";
	$payment_type = "-";
	//Credit Card
	$trans_mode = NULL;
	//$process_trans->array_print($transInfo);

	if($transInfo['checkorcard'] == 'H')
	{
		$trans_mode = 'cc';
		if($transInfo['cardtype'] != 'wallet')
		{
			$credit_card_formatted = substr($transInfo['CCnumber'],-4,4);
			$payment_type = "Credit Card (Last 4 Digits)";
			if(!$transInfo['td_is_a_rebill'])
			{
				if(!$transInfo['td_bank_number']&&$transInfo['country']=="US") {$response['errormsg'] = "Invalid Bank Phone Number"; return $response;}
				if(!$transInfo['CCnumber']) {$response['errormsg'] = "Invalid Credit Card Number"; return $response;}
				if(!$transInfo['validupto']) {$response['errormsg'] = "Invalid cvv Number"; return $response;}
			}
		}
	}
		
	//Check
	if($transInfo['checkorcard'] == 'C')
	{
		$trans_mode = 'ch';
		$credit_card_formatted = substr($transInfo['bankaccountnumber'],-4,4);
		$payment_type = "Account Number (Last 4 Digits)";
		if(!$transInfo['td_is_a_rebill'])
		{
			if(!$transInfo['bankname']) {$response['errormsg'] = "Invalid Name on Account"; return $response;}
			if(!$transInfo['bankaccountnumber']) {$response['errormsg'] = "Invalid Bank Account Number"; return $response;}
			if(!$transInfo['bankroutingcode']) {$response['errormsg'] = "Invalid Routing Code"; return $response;}
		}
	}
	
	if (!$trans_mode) {$response['errormsg'] = "Invalid Payment Method. "; return $response;}
	if ($transInfo['amount']>$companyInfo['cd_max_transaction'] && $companyInfo['cd_max_transaction'] > 0) {$response['errormsg'] = "Invalid Charge Amount. Charges may be no higher than ".$companyInfo['cd_max_transaction']."."; return $response;}
	
	if(!$transInfo['td_send_email']=='no') $transInfo['td_send_email'] = 'yes';
	
	if(!$transInfo['td_gcard']) $transInfo['td_gcard'] = "NULL";
	$transInfo['td_fraud_score'] = -1;
	if($transInfo['td_customer_fee']) $transInfo['amount'] += $transInfo['td_customer_fee'];

	$response = array();
	$ap_limit = intval($companyInfo['cd_approve_timelimit']);
	if($ap_limit<1) $ap_limit = 1;
	if(cc_check_previous_24h_approve(&$transInfo,$ap_limit))
	{
		//$response['errormsg'] = "Credit Card has been used in the last ".$companyInfo['cd_approve_timelimit']." hours. The order was successful. If you did not get an order confirmation email, or you have any other questions about your order, please contact Etelegate Customer Service. Otherwise, please wait until ".$companyInfo['cd_approve_timelimit']." hours has passed since your last purchase.";
		//$response['success'] = true;
		//$response['td_process_result']="Credit Card Previously Approved in the last ".$companyInfo['cd_approve_timelimit']." hours. Will not try again so soon.";
		//$response['td_process_query']="Checking for Previous Approves";
		//$response['status'] = "D";
		//$response['td_bank_recieved'] = 'approvelimit';
		$response['errormsg'] = "Credit Card has been used in the last $ap_limit hour(s). The order was successful. If you did not get an order confirmation email, or you have any other questions about your order, please contact Etelegate Customer Service. Otherwise, please wait until $ap_limit hour(s) has passed since your last purchase.";
		return $response;
	}
		
	if($_SESSION['etel_trans_pending']==true && !$transInfo['td_is_a_rebill']) {$response['errormsg'] = "Error: Transaction Pending. Please wait until current transaction has completed."; return $response;}
	$_SESSION['etel_trans_pending']=true;
	$_SESSION['etel_trans_pending_ref']=$transInfo['reference_number'];
	
	// Start Pending Trans
	$transInfo = $process_trans->update_TransactionRates($transInfo['userId'],$transInfo,$trans_mode,$mode);
	$trans_id = $process_trans->insert_TransactionWithRates($transInfo,$mode);
	
	$int_table = "cs_test_transactiondetails";
	if ($mode == "Live") $int_table = "cs_transactiondetails";
	
/*	
	$qrt_insert_details = "insert into $int_table set `status` = 'P',`Invoiceid` = '".$transInfo['Invoiceid']."', `transactionDate` = NOW(), `name` = '".$transInfo['name']."', `surname` = '".$transInfo['surname']."', `phonenumber` = '".$transInfo['phonenumber']."', `address` = '".$transInfo['address']."', `CCnumber` = '".etelEnc($transInfo['CCnumber'])."', `cvv` = '".$transInfo['cvv']."', `checkorcard` = '".$transInfo['checkorcard']."', `country` = '".$transInfo['country']."', `city` = '".$transInfo['city']."', `td_bank_number` = '".$transInfo['td_bank_number']."',
		 `state` = '".$transInfo['state']."', `zipcode` = '".$transInfo['zipcode']."', `amount` = '".$transInfo['amount']."', `memodet` = '".$transInfo['memodet']."', `signature` = '".$transInfo['signature']."', `bankname` = '".$transInfo['bankname']."', `bankroutingcode` = '".$transInfo['bankroutingcode']."', `bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."', `accounttype` = '".$transInfo['accounttype']."', `misc` = '".$transInfo['misc']."', `email` = '".$transInfo['email']."', `cancelstatus` = '".$transInfo['cancelstatus']."', 
		  `userId` = '".$transInfo['userId']."', `Checkto` = '".$transInfo['Checkto']."', `cardtype` = '".$transInfo['cardtype']."', `checktype` = '".$transInfo['checktype']."', `validupto` = '".$transInfo['validupto']."', `reason` = '".$transInfo['reason']."', `other` = '".$transInfo['other']."', `ipaddress` = '".$transInfo['ipaddress']."', `cancellationDate` = NULL, `voiceAuthorizationno` = '".$transInfo['voiceAuthorizationno']."', `shippingTrackingno` = '".$transInfo['shippingTrackingno']."', `socialSecurity` = '".$transInfo['socialSecurity']."',
		   `driversLicense` = '".$transInfo['driversLicense']."', `billingDate` = NOW(), `passStatus` = '".$transInfo['passStatus']."', `chequedate` = '".$transInfo['chequedate']."', `pass_count` = '".$transInfo['pass_count']."', `approvaldate` = '".$transInfo['approvaldate']."', `nopasscomments` = '".$transInfo['nopasscomments']."', `licensestate` = '".$transInfo['licensestate']."', `approval_count` = '".$transInfo['approval_count']."', `declinedReason` = '".$transInfo['declinedReason']."', `service_user_id` = '".$transInfo['service_user_id']."',
		    `admin_approval_for_cancellation` = '".$transInfo['admin_approval_for_cancellation']."', `company_usertype` = '".$transInfo['company_usertype']."', `company_user_id` = '".$transInfo['company_user_id']."', `callcenter_id` = '".$transInfo['callcenter_id']."', `productdescription` = '".$transInfo['productdescription']."', `reference_number` = '".$transInfo['reference_number']."', `currencytype` = '".$transInfo['currencytype']."', `r_reseller_discount_rate` = '".$transInfo['r_reseller_discount_rate']."', `r_total_discount_rate` = '".$transInfo['r_total_discount_rate']."',
			 `td_ca_ID` = '".$transInfo['td_ca_ID']."', `td_fraud_score` = '".$transInfo['td_fraud_score']."',`r_chargeback` = '".$transInfo['r_chargeback']."', `r_credit` = '".$transInfo['r_credit']."', `r_transactionfee` = '".$transInfo['r_transactionfee']."', `r_reserve` = '".$transInfo['r_reserve']."', `r_merchant_discount_rate` = '".$transInfo['r_merchant_discount_rate']."', `r_total_trans_fees` = '".$transInfo['r_total_trans_fees']."', `r_reseller_trans_fees` = '".$transInfo['r_reseller_trans_fees']."', `r_discountrate` = '".$transInfo['r_discountrate']."', `r_merchant_trans_fees` = '".$transInfo['r_merchant_trans_fees']."', `cancel_refer_num` = '".$transInfo['cancel_refer_num']."',
			  `cancel_count` = '".$transInfo['cancel_count']."', `return_url` = '".$transInfo['return_url']."', `from_url` = '".$transInfo['from_url']."', `bank_id` = '".$transInfo['bank_id']."', `td_rebillingID` = '".$transInfo['td_rebillingID']."', `td_is_a_rebill` = '".$transInfo['td_is_a_rebill']."', `td_enable_rebill` = '".$transInfo['td_enable_rebill']."', `td_voided_check` = '".$transInfo['td_voided_check']."', `td_returned_checks` = '".$transInfo['td_returned_checks']."', `td_site_ID` = '".$transInfo['td_site_ID']."', `td_is_affiliate` = '".$transInfo['td_is_affiliate']."',
			   `td_send_email` = '".$transInfo['td_send_email']."', `td_customer_fee` = '".$transInfo['td_customer_fee']."', `td_is_pending_check` = '".$transInfo['td_is_pending_check']."', `td_is_chargeback` = '".$transInfo['td_is_chargeback']."', `td_recur_processed` = '".$transInfo['td_recur_processed']."', `td_recur_next_date` = '".$transInfo['td_recur_next_date']."', `td_username` = '".$transInfo['td_username']."', `td_password` = '".$transInfo['td_password']."', `td_product_id` = '".$transInfo['td_product_id']."', `td_non_unique` = '".$transInfo['td_non_unique']."',
			   td_merchant_fields = '" . $transInfo['td_merchant_fields'] . "', td_subscription_id = '" . $transInfo['td_subscription_id'] . "' ;";
		
	//die($qrt_insert_details);
	$show_insert_run =mysql_query($qrt_insert_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>".$qrt_insert_details);

	$trans_id = mysql_insert_id();
	$transInfo['transactionId'] = $trans_id;
	func_update_rate($transInfo['userId'],&$transInfo,$cnn_cs,$trans_mode,$mode);
*/

	toLog('order','customer',"Pending Transaction '".$transInfo['reference_number']."' Created.",$trans_id);
	
	// End Pending Trans
	
	
	if($mode=="Live")
	{
			$start_transaction = microtime_float();
			$int_op = $bankInfo['bk_int_function'];
			if (function_exists($int_op)) $response = $int_op($transInfo,$bankInfo,$companyInfo);
			else
			{
				$response['errormsg'] = "Integration Function '$int_op' not found";
				$response['success'] = false;
			}
			toLog('order','customer',"Transaction '".$transInfo['reference_number']."' Integration Response: ".$response['td_process_result']." ~ Integration Query: ".$response['td_process_query']." ~ Response Info: ".serialize($response),$trans_id);
			$transInfo['status'] = $response['status'];
			$transInfo['td_process_result']=$response['td_process_result'];
			$transInfo['td_process_query']=$response['td_process_query'];
			$transInfo['td_bank_transaction_id']=$response['td_bank_transaction_id'];
			if($response['td_gcard']) $transInfo['td_gcard']=$response['td_gcard'];
			//$transInfo['td_gcard'] = $transInfo['td_gcard'];
			if(!$transInfo['td_gcard']) $transInfo['td_gcard'] = "NULL";
			$transInfo['td_bank_recieved']=$response['td_bank_recieved'];
	}
	else
	{

		$response['errormsg'] = "Success";
		$response['success'] = true;
		$transInfo['Invoiceid'] = $response['Invoiceid'];
		$transInfo['td_process_result']=$response['td_process_result'];
		$transInfo['td_process_query']=$response['td_process_query'];
		$response['status'] = "A";
		$transInfo['td_bank_recieved']='no';
		//$transInfo['td_gcard'] = substr($transInfo['td_gcard'],0,4)."********".substr($transInfo['td_gcard'],0,-4);

	}
	$transInfo['status'] = $response['status'];
	$transInfo['td_process_msg'] = $response['errormsg'];

	if($transInfo['status']!="A")
	{
		$transInfo['td_username']="";
		$transInfo['td_password']="";
	}


		
	
		
	$transInfo['td_process_duration'] = microtime_float()-$start_transaction;
	$transInfo['td_non_unique']=cc_check_unique($int_table,&$transInfo);
	
	$transInfo['declinedReason'] = $transInfo['errormsg'];
	$qrt_update_details = "update $int_table set 
			`td_gcard` = ".$transInfo['td_gcard'].", 
			`td_bank_recieved` = '".$transInfo['td_bank_recieved']."',
			`td_fraud_score` = '".$transInfo['td_fraud_score']."',
			`status` = '".$transInfo['status']."',
			`td_username` = '".$transInfo['td_username']."',
			`td_process_msg` = '".$transInfo['td_process_msg']."',
			`td_password` = '".$transInfo['td_password']."',
			`td_bank_transaction_id` = '".$transInfo['td_bank_transaction_id']."',
			`td_process_query` = '".quote_smart($transInfo['td_process_query'])."',
			`td_process_result` = '".quote_smart($transInfo['td_process_result'])."',
			`td_process_duration` = '".quote_smart($transInfo['td_process_duration'])."' 
			where transactionId = '$trans_id';";
	//die($qrt_insert_details);			
	toLog('order','customer',"Transaction '".$transInfo['reference_number']."' Update Query: ".$qrt_update_details,$trans_id);
	$show_insert_run =mysql_query($qrt_update_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>".$qrt_update_details);
	

	if($response['success'] == true)
	{
		//if($transInfo['status'] != 'A') $transInfo['status'] = 'D';



		//func_ins_bankrates($trans_id,$bank_CreditcardId,$cnn_cs);
		$response['transactionId'] = $trans_id;
		// Update Rates here?

		if(!$trans_id) {
			$response['errormsg'] = "Failed to store Transaction in Database";
			$response['success'] = false;
		}
		
		$email_to = $transInfo['email'];


		if($transInfo['status'] == 'A' || ($transInfo['status'] == 'P' && $transInfo['checkorcard'] == 'C'))
		{
		// Email
			
			$useEmailTemplate = "customer_recur_subscription_confirmation_cc";
			if($transInfo['td_enable_rebill'] == 0) $useEmailTemplate = "customer_order_confirmation_cc";

			if($transInfo['td_one_time_subscription']) $useEmailTemplate = "customer_subscription_confirmation_cc";
			if($transInfo['td_is_a_rebill'] == 1) $useEmailTemplate = "customer_rebill_confirmation_cc";
			$data = array();
			$data['payment_type'] = $payment_type;
			$data['billing_descriptor'] = $transInfo['billing_descriptor'];
			$data['site_URL'] = $companyInfo['cs_URL'];
			$data['reference_number'] = $transInfo['reference_number'];
			$data['full_name'] = $transInfo['surname'].", ".$transInfo['name'];
			$data['product_info'] = $transInfo['td_product_id'].": ". $transInfo['productdescription'];
			$data['email'] = $email_to;
			$data['customer_email'] = $email_to;
			$data['credit_card_formatted'] = $credit_card_formatted;
			$data['amount'] = "$".formatMoney($transInfo['amount']-$transInfo['td_customer_fee'])." USD";
			$data['customer_fee'] = "$".formatMoney($transInfo['td_customer_fee'])." USD";
			$data['final_amount'] = "$".formatMoney($transInfo['amount'])." USD";
			$data['username'] = $transInfo['td_username'];
			$data['password'] = $transInfo['td_password'];
			$data['payment_schedule'] = $transInfo['payment_schedule'];
			$data['transaction_date'] = date("F j, Y",strtotime($transInfo['transactionDate']));
			$data['next_bill_date'] = $transInfo['nextDateInfo'];
			$data['site_access_URL'] = $companyInfo['cs_member_url'];
			$data['customer_support_email'] = $companyInfo['cs_support_email'];
			$data['tmpl_language'] = $_SESSION['tmpl_language'];
			$data['gateway_select'] = $companyInfo['gateway_id'];

			if($transInfo['td_send_email'] == 'yes')
			{
	
				$str_is_test = "THIS IS A TEST TRANSACTION ";
				if($mode=="Live") $str_is_test = "";
				if(!$transInfo['td_is_a_rebill']) send_email_template($useEmailTemplate,$data,$str_is_test); // Send Customer Email.
				if($mode=="Live" && $bankInfo['bk_cc_bank_enabled']==1)
				{	
					$data['email'] = $bankInfo['bank_email'];
					send_email_template($useEmailTemplate,$data,"(Bank Copy) ");
				}
				if($companyInfo['cd_recieve_order_confirmations'])
				{	
					$data['email'] = $companyInfo['cd_recieve_order_confirmations'];
					send_email_template($useEmailTemplate,$data,$str_is_test."(Merchant Copy) ");
				}
			}
			// End Email
		}
		

		if($mode!="Live")
		{
			$sql = "UPDATE cs_companydetails set `cd_completion` = 7 WHERE `cd_completion` = 6 AND `userId` = '".$transInfo['userId']."'";
			if($companyInfo['cd_completion']==6) mysql_query($sql) or dieLog(mysql_error()); 
			$transInfo['td_product_id'] = "TEST MODE";
			$transInfo['amount'] = "TEST MODE";
		}
		
		$_SESSION['etel_trans_pending']=true;
		
		// Post Notification
		
		$notify = 'decline';
		if($transInfo['status']=='A' || ($transInfo['status'] == 'P' && $transInfo['checkorcard'] == 'C'))
		{
			$notify = 'approve';
			if($transInfo['td_is_a_rebill'])
				$notify = 'rebill';
		}
		Process_Transaction($transInfo['reference_number'],$notify,($mode!="Live")); //approved or declined
	}
	$response['transactionId'] = $transInfo['transactionId'];
	$_SESSION['etel_trans_pending']=false;
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
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	
	$params['TransactionAmount'] = $transInfo['amount'];
	$params['MerchantAmount'] = 0;
	$params['ConvienenceFee'] = 0;
	$params['AccountNumber'] =  $transInfo['CCnumber'];
	$params['CVV2'] =  $transInfo['cvv'];
	$params['ExpirationMonth'] = $expMonth;
	$params['ExpirationYear'] = $expYear;

	$processor = new Discover_Client($bankInfo,"test");
	$process_result = $processor->Credit_Card_Charge($params);

	$response=NULL;
	$response['errormsg'] = $process_result['RC'] . ": " . $process_result['RCString'];
	$response['td_bank_transaction_id'] = $process_result['TransactionID'];
	$response['td_process_result'] = serialize($process_result);
	$response['td_process_query'] = "";
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
	//include('snoopy/Snoopy.class.php');
	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	
	
	if ($bankInfo['bk_cc_support']!=1){$response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}
	//$cust_state = func_get_state($transInfo['state'],'st_full');

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	//if(!$transInfo['td_is_a_rebill'])
		$expDate=$expMonth.$expYear;
	//else $expDate='';

	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));

	//foreach($transInfo as $key => $info)
		//$transInfo[$key] = urlencode($info);


	$i24_msg_id = rand(0,400000000000);
	$response['td_bank_transaction_id'] = $i24_msg_id;

	
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
	//$Pinfo.="&P11=123"; //UDF1	N	(255) varchar
	//$Pinfo.="&P12=123"; //UDF2	N	(255) varchar
	//$Pinfo.="&P13=123"; //UDF3	N	(255) varchar
	//$Pinfo.="&P14=123"; //UDF4	N	(255) varchar
	//$Pinfo.="&P15=123"; //UDF5	N	(255) varchar
	$Pinfo.="&P16=".urlencode($transInfo['email']); //	Email	Y	(1,40) varchar
	$Pinfo.="&P17=".urlencode(round($transInfo['amount']*100)); //	Load Amount – Represents the amount of money on the initial load when assigning the card number	Y	Numeric (See 1.1.5)
	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&P18=".($transInfo['CCnumber']?$transInfo['CCnumber']:$transInfo['td_gcard']); //	Credit Card – Card number to charge for the Amount (P17) if a load is required.	Y	(1,26) Numeric
	else
		$Pinfo.="&P18="; //	Credit Card – Card number to charge for the Amount (P17) if a load is required.	Y	(1,26) Numeric

	//if(!$transInfo['cvv']) $transInfo['cvv']= 123;
	$Pinfo.="&P19=".$expDate; //	Credit Card Expiry Date – Expiration date of the credit card. MMYY	Y	(1,4) Numeric
	
	if(!$transInfo['td_is_a_rebill'])
		$Pinfo.="&P20="; //	CVD – Cardholder Verification Data (CVV2,CVC2)	Y	(1,3) Numeric
	else
		$Pinfo.="&P20=".urlencode($transInfo['cvv']); //	CVD – Cardholder Verification Data (CVV2,CVC2)	Y	(1,3) Numeric
	$Pinfo.="&P21=".round($transInfo['amount']*100); //	Transfer Amount – Amount to transfer from Electron Card to Web Master.	Y 	(1,9) Varchar
	$Pinfo.="&P22=".$additional_array[1]; //	Merchant Vendor ID -  Virtual ID number linked to a Merchant ID and/or a Merchant Debit Card.	Y	(1,20) Varchar
	$Pinfo.="&P23=".$transInfo['ipaddress']; //	Cardholder IP Address	Y	(1,15) varchar
	$Pinfo.="&P24=0"; //	RSID – Reseller/Referrer ID	Y
	//$Pinfo.="&P25=".$transInfo['company_info']; //	Merchant Descriptor 1	N
	//$Pinfo.="&P26=".$transInfo['company_info']; //	Merchant Descriptor 2	N
	$Pinfo.="&P27=".urlencode($transInfo['productdescription']); //	Transaction Description	N
	$Pinfo.="&P28=".urlencode($transInfo['reference_number']); //	Ibill Transaction Tracking ID	Y
	$Pinfo.="&P29=".$transInfo['td_is_a_rebill']; //	RecurID	Y	0 = not recurring 1 = recurring transaction
	//$Pinfo.="&P30=123"; //	DOB	N	Date (i.e., mm/dd/yyyy or m/d/yyyy)
	//$Pinfo.="&P31=123"; //	Phone	N	(7,15) Varchar
	//$Pinfo.="&P32=123"; //	Sale Flag	N	NOSALE- will not send transaction to sale
	//$Pinfo.="&P33=123"; //	Security Field 1 can include mother’s maiden name or license number	N	(1,22) varchar
	//$Pinfo.="&P34=123"; //	Security Field 1 can include mother’s maiden name or license number	N	(1,22) varchar
	//$Pinfo.="&P35=123"; //	Not Used	N
	//$Pinfo.="&P36=123"; //	User defined field.  It handles special requirements for the client	N	(1,16) varchar
	//$Pinfo.="&P37=123"; //	User defined field.  It handles special requirements for the client l	N	(1,16) varchar
	//$Pinfo.="&P38=123"; //	Social Security Nbr.	N	(1,9) Varchar
	$Pinfo.="&P39="; //	Embossing Data – used for embossing the name of the company on the card	Y	(1,40) Varchar
	//$Pinfo.="&P40=123"; //	Merchant City	N	(1,30) Varchar
	//$Pinfo.="&P41=123"; //	Merchant State	N	(1,30) Varchar
	//$Pinfo.="&P42=123"; //	Merchant Zip	N	(1,30) Varchar
	//$Pinfo.="&P43=123"; //	Merchant Country	N	(1,30) Varchar
	//$Pinfo.="&distributorcode=123"; //	Distributor Code	N	(1,30) Varchar
	if(!$transInfo['td_is_a_rebill'])
	$Pinfo.="&issue_physical_card=0"; //	Whether or not to issue plastic	Y	Boolean
	//$Pinfo.="&Remote_Host=".$transInfo['ipaddress']; //	Whether or not to issue plastic	Y	Boolean
	//$Pinfo.="&password=0"; //	Whether or not to issue plastic	Y	Boolean

	// Uncomment below for live
	$query_url = "https://trans.symmetrex.com/ibill/ibillapi?";
	$output_url = $query_url.$Pinfo;
	$output_url = str_replace(" ","%20",$output_url);

	
	//$process_result = file_get_contents($output_url);
	//print($output_url."@");
	//$process_result = file($output_url);
	//print($process_result."2@");
	toLog('order','customer', "Symmetrex Post for ".$transInfo['reference_number'].":  ".$output_url,$transInfo['transactionId']);
	$process_result = http_post2('ssl://trans.symmetrex.com', 443, $output_url, $Pinfo);
	toLog('order','customer', "Symmetrex Result for ".$transInfo['reference_number'].":  ".$process_result,$transInfo['transactionId']);

	//if(!$process_result)
	//{
	
	//$Pinfo="";
	//$Pinfo.="FUNC=004";
	//$Pinfo.="&P1=".$i24_msg_id;
		
	//$echo_result = http_post('ssl://trans.symmetrex.com', 443, $query_url.$Pinfo, $Pinfo);
	
	//toLog('order','customer', "Querying server for info on ".$transInfo['reference_number'].":  ".$echo_result." Query: ".$query_url.$Pinfo,$transInfo['transactionId']);
	
	//}

	$response['td_process_result'] = $process_result;
	
	
	
	//$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result['0']))));
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
	{
		$response['status'] = "A";
	}
	$response['td_bank_transaction_id'] = $P8;
	return $response;
}

function cc_ChronoPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "";
	$response['success'] = false;


	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	
	
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

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $Pinfo);
		
    	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	//execute curl and return result to STDOUT
		curl_exec ($ch);
	//close curl connection
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
	//print_r($parsed_result);print($response['td_process_query']);die();
	$response['errormsg'] = $parsed_result[1];
	$response['success'] = false;
	if ($parsed_result[0] == 'Y')
	{
		$response['success'] = true;
		$response['errormsg'] = "Credit Card Accepted";
		$response['status'] = "A";
		$response['td_bank_transaction_id'] = $parsed_result[1];
	} else if ($parsed_result[1] == 'rejected' || $parsed_result[1] == 'Declined by processing')
	{
		$response['success'] = true;
		$response['errormsg'] = "Credit Card Declined";
		$response['status'] = "D";
	} else toLog('error','customer', "Customer Recieves error : ".$process_result." PInfo: ?".$Pinfo, $transInfo['userId']);


	return $response;
}

function cc_EcomGlobal_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";


	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	

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
	//$Pinfo.="&addrnum=".$transInfo['address']; //  	Address Number 	Optional
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
	//print_r($clean_data);
	//die();
	return $response;
}

function cc_ForceTronix_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";

	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	
	
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
		/*
		$Pinfo="InternalOrderId=".$transInfo['reference_number']; 				//  (Your own unique identifier for this purchase)
		$Pinfo.="&MerchantId=".$bankInfo['bk_username']; 		//  (The EGE unique identifier for the account)
		$Pinfo.="&Secret=".$bankInfo['bk_additional_id']; 			//  (The EGE security code for the account)
		$Pinfo.="&TransType=2"; 		//  (The Transaction Code)
		$process_result_capture = http_post('ssl://eccpro.com', 443, $output_url, $Pinfo);
		
		$xml = xml2array( $process_result_capture);

		$statusCode = $xml['ECCPro']['Response']['StatusCode'];
		if($statusCode == "000") 
		{
			$response['status'] = "A";
			$response['td_process_query'].="\n".$output_url."?".$Pinfo;
			$response['td_process_result'].="\n".$process_result_capture;
		}
		*/
		
	}
	$response['td_bank_transaction_id'] = $ProcessOrderID;
	return $response;
}

function cc_AvantPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";


	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	
	toLog('order','customer',"Pending Transaction '".$transInfo['reference_number']."' Passed Scrubbing.");
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	
	//foreach($transInfo as $key=>$data)
	//	$transInfo[$key] = urlencode($data);
		
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	$cust_state = urlencode(func_get_state($transInfo['state'],'st_abbrev'));
	//if(strlen($cust_state)>2)$cust_state="";
	
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

	//$process_result = http_post('ssl://secure.avantpay.com', 443, $output_url, $Pinfo);
	//$process_result = file_get_contents($output_url."?". $Pinfo);
	
	toLog('order','customer',"Pending Transaction '".$transInfo['reference_number']."' Sending Request to Bank: ".$output_url."?".$Pinfo);
	
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


function cc_LaserPay_integration($transInfo,$bankInfo,$companyInfo)
{
	global $etel_fraud_limit;
	$response="";
	$response['errormsg'] = "Transaction could not be processed.";


	// Fraud Scrubbing
	$scrub_response = execute_scrub_tests(&$transInfo,&$bankInfo,&$companyInfo);
	if($scrub_response != -1) return $scrub_response;	
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	
	//foreach($transInfo as $key=>$data)
	//	$transInfo[$key] = urlencode($data);
		
	$cust_cntry = urlencode(func_get_country($transInfo['country'],'co_ISO'));
	
	$cardtype = $transInfo['cardtype'];	
	if(strtolower($transInfo['cardtype'])=='master') $cardtype = 'MasterCard';
		
	$output_url = "https://merchants.lazerpay.com/api/processing.cfm";
	if(!$transInfo['state']) $transInfo['state'] = "NA";
	$Pinfo.="merchant_id=".$bankInfo['bk_username']; // yes alphanumeric, max 32 characters Your Lazerpay merchandt identification id 12345 
	$Pinfo.="&secret_key=".$bankInfo['bk_additional_id']; // yes alphanumeric, max 40 characters Your Lazerpay secret merchant key IMPORTANT: Keep this secret key hidden. Do not show it on your website. Make sure that it is not stated in the html code viewable to the clients. v7iTT5yq6_66eQ 
	//$Pinfo.="&merchant_processing_url =".$output_url; // yes alphanumeric, max 255 characters The URL we provided you with to send the transaction data to. https://merchants.lazerpay.com/api/processing.cfm 

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
	
	//foreach($val as $key=>$data)
		//$val[$key] = urlencode($data);
		
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

	if(ch_check_previous_decline(&$transInfo))
	{
		$response['errormsg'] = "Check Debit Declined";
		$response['success'] = true;
		$response['td_process_result']="Check Previously Declined. Will not try again.";
		$response['td_process_query']="Checking for Previous Declines";
		$response['status'] = "D";
		return $response;
	}
	//foreach($transInfo as $key=>$data)
		//$transInfo[$key] = urlencode($data);
	
	foreach($transInfo as $key => $data)
		$transInfo[$key]=urlencode($data);
	
	$Pinfo="";
	$Pinfo.="Action=Debit"; 		// Required
	$Pinfo.="&MerchantID=".$bankInfo['bk_username'];// Required
	$Pinfo.="&Password=".$bankInfo['bk_password'];// Required
	$Pinfo.="&ReferenceNumber=".$transInfo['reference_number'];// Required
	$Pinfo.="&Amount=".$transInfo['amount']; 			// Required
	$Pinfo.="&AccountNumber=".$transInfo['bankaccountnumber']; 		// Required
	$Pinfo.="&RoutingNumber=".$transInfo['bankroutingcode']; 		// Required
	$Pinfo.="&CheckNumber="; 		// Optional
	$Pinfo.="&FullName=".$transInfo['bankname']; 			// Optional
	$Pinfo.="&Address1=".substr($transInfo['address'],0,35); 			// Optional
	$Pinfo.="&Address2="; 			// Optional
	$Pinfo.="&City=".$transInfo['city']; 				// Optional
	$Pinfo.="&State=".$transInfo['state']; 				// Optional
	$Pinfo.="&Zip=".$transInfo['zipcode']; 				// Optional
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
	$response['errormsg'] = "Check Declined: $Result";	
	$response['td_bank_transaction_id'] = $TransactionID;
	$response['td_process_result'] = $process_result;
	$response['td_process_query'] = $output_url."?".$Pinfo;
	$response['td_bank_recieved'] = 'yes';
	$response['status'] = "D";
	if ($Result == "ACCEPTED")
	{
		$response['errormsg'] = "Check Approved";
		$response['status'] = "P";
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

	if(ch_check_previous_decline(&$transInfo))
	{
		$response['errormsg'] = "Check Debit Declined";
		$response['success'] = true;
		$response['td_process_result']="Check Previously Declined. Will not try again.";
		$response['td_process_query']="Checking for Previous Declines";
		$response['status'] = "D";
		return $response;
	}

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
	

	//print_r($processor->ExecutePeekUserExists("ND46691PF"));
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
	print_r($process_result);
	die();

	// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
	$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result))));

	// parse the string into variablename=variabledata
	parse_str($clean_data);
	print_r($clean_data);
	die();
	//echo "Response Data ".$clean_data;

	// output some of the variables
	//echo "Response Type = ".$pg_response_type."<br />";
	//echo "Response Code = ".$pg_response_code."<br />";
	//echo "Response Description = ".$pg_response_description."<br />";

}

require('matureIntegration.php');

?>
