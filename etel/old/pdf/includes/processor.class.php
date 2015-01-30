<?
// 114466

class processor_class
{
	var $mode;
	var $transInfo;
	var $trans_mode;
	var $credit_card_formatted;
	var $payment_type;
	var $response;
	var $email_info;
	var $companyInfo;
	var $bankInfo;
	var $int_function;
	var $fraud;
	
	function processor_class($mode = "Live")
	{
		$this->mode = $mode;
		$this->transInfo = NULL;
		$this->response = NULL;
		$this->trans_mode = NULL;
		$this->fraud = new fraud_class();
	}
	
	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}
		
	function set_transmode()
	{
		//Credit Card
		if(in_array($this->transInfo['cardtype'],array('Visa','Mastercard','Discover','JCB')))
		{
			$this->trans_mode = 'cc';
			$this->credit_card_formatted = substr($this->transInfo['CCnumber'],-4,4);
			$this->payment_type = "Credit Card (Last 4 Digits):   ";
			if(!$this->transInfo['td_is_a_rebill'])
			{
				if(!$this->transInfo['td_bank_number']&&$this->transInfo['country']=="US") $this->response['errormsg'] = "Invalid Bank Phone Number";
				if(!$this->transInfo['cvv']) $this->response['errormsg'] = "Invalid Cvv2";
			}
			if(!$this->transInfo['CCnumber']) $this->response['errormsg'] = "Invalid Credit Card Number";
			if(!$this->transInfo['validupto']) $this->response['errormsg'] = "Invalid Expiration Date";

			$exp = explode("/",$this->transInfo['validupto']);
			if($exp[0] < date("Y")) $this->response['errormsg'] = "Card Expired";
			if($exp[0] == date("Y")) 
				if(intval($exp[1]) < date("n")) 
					$this->response['errormsg'] = "Card Expired";
		}
		else
		if($this->transInfo['cardtype'] == 'Check')
		{
			$this->trans_mode = 'ch';
			$this->credit_card_formatted = substr($this->transInfo['bankaccountnumber'],-4,4);
			$this->payment_type = "Account Number (Last 4 Digits):";
			if(!$this->transInfo['td_is_a_rebill'])
			{
				if(!$this->transInfo['bankname']) $this->response['errormsg'] = "Invalid Name on Account";
				if(!$this->transInfo['bankaccountnumber']) $this->response['errormsg'] = "Invalid Bank Account Number";
				if(!$this->transInfo['bankroutingcode']) $this->response['errormsg'] = "Invalid Routing Code";
			}
		}
		else $this->response['errormsg'] = "Invalid Card Type: ".$this->transInfo['cardtype'];
	}
	
	function create_pending_transaction()
	{
		////// update the rates and fees of the transaction based on the bank
		$rates = new rates_fees();
		$this->transInfo = $rates->update_TransactionRates($this->transInfo['en_ID'],$this->transInfo,$this->trans_mode,$this->mode);
		$this->trans_id = $rates->insert_TransactionWithRates($this->transInfo,$this->mode);
		$this->transInfo['transactionId'] = $this->trans_id;
		toLog('order','customer',"Pending Transaction '".$this->transInfo['reference_number']."' Created.",$this->trans_id);
		if(!$this->trans_id) $this->response['errormsg'] = "Failed to store Transaction in Database";
	}
	
	function set_bank()
	{
		////// find a valid bank that the merchant is using that can process for the cardtype
		if(!$this->transInfo['en_ID'])
		{
			$this->response['errormsg'] = "Invalid Merchant ID";
			return;
		}

		$bank_info = merchant_getBank($this->transInfo['en_ID'],$this->transInfo['bank_id']);
		if($bank_info == NULL) 
		{
			$this->response['errormsg'] = "Invalid Bank Info (MID=" . $this->transInfo['en_ID'] . ")";
			return;
		}

		$this->transInfo['bank_id'] = $bank_info['bank_id'];
		$this->transInfo['cardtype'] = $bank_info['bk_trans_types'];
		$this->bankInfo = $bank_info;

		if(!$this->bankInfo['bk_int_function'] && $this->mode=="Live") $this->response['errormsg'] = "Invalid Bank Integration for Bank: ".$this->transInfo['bank_id'];
	}
	
	function get_company_info()
	{
		$sql="
			SELECT 
				* 
			FROM 
				`cs_companydetails` as c 
			left join `cs_company_sites` as s on s.cs_company_id = c.`userId` 
			WHERE 
				c.`userId` = '".$this->transInfo['userId']."' 
				and s.`cs_ID` = '".$this->transInfo['td_site_ID']."'
			";
			
		$result=sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		if(mysql_num_rows($result)<1 && $this->mode=="Live") 
			$this->response['errormsg'] = "Invalid Company/Website"; 
		else		
			$this->companyInfo = mysql_fetch_assoc($result);	

		if(!$this->companyInfo['activeuser'] && $this->mode=="Live") 
			$this->response['errormsg'] = "Merchant Account Not Live. Please contact Administrator.";

		if(!in_array($this->companyInfo['cs_verified'],array("approved","non-compliant")) && $this->mode=="Live") 
			$this->response['errormsg'] = "Merchant Website Not Approved. Please contact Administrator."; 
	}
	
	function set_integration_function()
	{
		//$response = $int_op($transInfo,$bankInfo,$companyInfo);
		$this->int_function = $this->bankInfo['bk_int_function'];
		if (!function_exists($this->int_function)) 
			$this->response['errormsg'] = "Integration Function \"" . $this->int_function . "\" not found";
	}
	
	function verify_username($value)
	{
		if($value == "")
			return true;
			
		if (preg_match("/^[a-zA-Z0-9_]+$/", $value)) 
			return true;
		return false;
	}

	function check_errors()
	{
		if(!$this->transInfo['checkorcard']) dieLog("Error. No Transaction Type Selected. ".serialize($this->transInfo));
		if(!$this->transInfo['reference_number']) $this->response['errormsg'] = "Invalid Reference Number";
		if(!$this->transInfo['userId']) $this->response['errormsg'] = "Invalid Merchant Id #" . $this->transInfo['userId'];
		if(!$this->transInfo['checkorcard']) $this->response['errormsg'] = "Invalid Payment Type";

		if(!$this->verify_username($this->transInfo['td_username'])) $this->response['errormsg'] = "Invalid Username '" . $this->transInfo['td_username'] . "': Only numbers and letters are allowed.";

		if(!$this->transInfo['td_is_a_rebill'])
		{
			if($this->transInfo['cs_enable_passmgmt'] && $this->transInfo['td_rebillingID'] != -1)
			{
				if(strlen($this->transInfo['td_username'])<6) $this->response['errormsg'] = "Invalid UserName (Must be greater than 5 characters)";
				if(strlen($this->transInfo['td_password'])<6) $this->response['errormsg'] = "Invalid Password (Must be greater than 5 characters)";
			}
	
			if(!$this->transInfo['name']) $this->response['errormsg'] = "Invalid Name";
			if(!$this->transInfo['surname']) $this->response['errormsg'] = "Invalid Last Name";
			if(!$this->transInfo['address']) $this->response['errormsg'] = "Invalid Address";
			if(!$this->transInfo['city']) $this->response['errormsg'] = "Invalid City";
			if(!$this->transInfo['phonenumber']) $this->response['errormsg'] = "Invalid Phone Number";
			if(!$this->transInfo['zipcode']) $this->response['errormsg'] = "Invalid ZipCode";
			if(!$this->transInfo['country']) $this->response['errormsg'] = "Invalid Country";
			if(!$this->transInfo['email']) $this->response['errormsg'] = "Invalid Email";

			$this->email_info = infoListEmail($this->transInfo['email']);
			if($this->email_info['cnt']>0) $this->response['errormsg'] = "Unsubscribed Email Address ".$this->transInfo['email'].".<BR>Reason: ".$this->email_info['ec_reason'].".<BR>Please use a different email address.";

			if(!$this->transInfo['amount']) $this->response['errormsg'] = "Invalid Charge Amount";
			if(!$this->transInfo['ipaddress']) $this->response['errormsg'] = "Invalid IP Address";
		
//			if(!$this->transInfo['productdescription']) $this->response['errormsg'] = "Invalid Product Description";
		}
		
		if (!$this->trans_mode) $this->response['errormsg'] = "Invalid Payment Method.";
		if ($this->transInfo['amount']>$this->companyInfo['cd_max_transaction'] && $this->companyInfo['cd_max_transaction'] > 0) $this->response['errormsg'] = "Invalid Charge Amount. Charges may be no higher than ".$this->companyInfo['cd_max_transaction'].".";

		$ap_limit = intval($this->companyInfo['cd_approve_timelimit']);
		if($ap_limit)
			if($this->fraud->check_previous_24h_approve(&$this->transInfo,$ap_limit))
				$this->response['errormsg'] = "Credit Card has been used in the last $ap_limit hour(s). The order was successful. If you did not get an order confirmation email, or you have any other questions about your order, please contact Etelegate Customer Service. Otherwise, please wait until $ap_limit hour(s) has passed since your last purchase.";

		if($_SESSION['etel_trans_pending']==true && !$this->transInfo['td_is_a_rebill']) $this->response['errormsg'] = "Error: Transaction Pending. Please wait until current transaction has completed.";
	}
	
	function process_transaction($transInfo)
	{
		
		foreach($transInfo as $key=>$data)
			$transInfo[$key] = quote_smart($data);
			
		$this->init_transaction($transInfo);
				
		if($this->response)	return $this->response;
		$this->execute_transaction();
		return $this->response;
	}
	
	function init_transaction($transInfo)
	{
		if(!$transInfo)
		{
			$this->response['errormsg'] = "No Transaction Information";
			return;
		}
		$this->transInfo = $transInfo;
		$this->set_bank();
		if(!$this->response['errormsg']) $this->set_transmode();
		if(!$this->response['errormsg']) $this->get_company_info();
		if(!$this->response['errormsg']) $this->set_integration_function();

		if(!$this->response['errormsg'])
		{
			if(!$this->transInfo['reference_number']) $this->transInfo['reference_number'] = genRefId("transaction",$this->transInfo['checkorcard']);
			if(!$this->transInfo['td_subscription_id']) $this->transInfo['td_subscription_id'] = genRefId("subscription","S");
		}
		if($this->mode == "Test") $this->transInfo['td_subscription_id'] = '';

		if(!$this->response['errormsg']) $this->check_errors();

		if(!$this->response['errormsg']) $this->create_pending_transaction();
	}
	
	function sanitizeChargeInfo(&$info)
	{
		$cc = $this->transInfo['CCnumber'];
		$an = $this->transInfo['bankaccountnumber'];
		$rn = $this->transInfo['bankroutingcode'];
		$info = str_replace($cc,'[credit_card]',$info);
		$info = str_replace($an,'[account_number]',$info);
		$info = str_replace($rn,'[routing_number]',$info);
	}
	
	function execute_transaction()
	{
		global $cnn_cs;
		global $etel_fraud_limit;

		ignore_user_abort(true);
		set_time_limit(500);
		
		if(!$_SESSION['tmpl_language']) $_SESSION['tmpl_language'] = 'eng';
		$rates = new rates_fees();
		
		$gw_emails_sales = $_SESSION['gw_emails_sales'];

		if(!$etel_fraud_limit) $etel_fraud_limit = floatval($this->companyInfo['cd_fraudscore_limit']);
		$this->transInfo['td_bank_recieved'] = 'no';
		$this->transInfo['companyname'] = $this->companyInfo['companyname'];
		$this->transInfo['cs_enable_passmgmt'] = $this->companyInfo['cs_enable_passmgmt'];
	
		if(!$this->transInfo['billing_descriptor']) 
			$this->transInfo['billing_descriptor'] = $this->bankInfo['bk_descriptor_visa'];
		$this->transInfo['cardtype'] = $this->bankInfo['bk_trans_types'];
	
		
		if(!$this->transInfo['td_send_email']=='no') $this->transInfo['td_send_email'] = 'yes';
		
		if(!$this->transInfo['td_gcard']) $this->transInfo['td_gcard'] = "NULL";
		$this->transInfo['td_fraud_score'] = -1;
		//if($this->transInfo['td_customer_fee']) $this->transInfo['amount'] += $this->transInfo['td_customer_fee'];
		// INCORRECT. customer fee is already included in amount


		$_SESSION['etel_trans_pending']=true;
		$_SESSION['etel_trans_pending_ref']=$this->transInfo['reference_number'];
		
		$start_transaction = microtime_float();

		if($this->mode=="Live")
		{
		
		
			$this->response = $this->fraud->execute_scrub_tests($this->transInfo,$this->bankInfo,$this->companyInfo);
			if($this->response == -1) 
			{	
				$int_func = $this->int_function;
				
				$int_func_response = $int_func($this->transInfo,$this->bankInfo,$this->companyInfo);
				$log = "Transaction '".$this->transInfo['reference_number']."' Integration Response: ".$int_func_response['td_process_result']." ~ Integration Query: ".$int_func_response['td_process_query']." ~ Response Info: ".serialize($int_func_response);
				
				$this->sanitizeChargeInfo($log);
				toLog('order','customer',$log,$this->trans_id);
	
				$this->response = $int_func_response;
			}
			$this->response['success'] = true;

			$this->transInfo['td_process_result']=$int_func_response['td_process_result'];
			$this->transInfo['td_process_query']=$int_func_response['td_process_query'];
			$this->transInfo['td_bank_transaction_id']=$int_func_response['td_bank_transaction_id'];
			if($int_func_response['td_gcard']) $this->transInfo['td_gcard']=$int_func_response['td_gcard'];
			if(!$this->transInfo['td_gcard']) $this->transInfo['td_gcard'] = "NULL";
			$this->transInfo['td_bank_recieved']=$int_func_response['td_bank_recieved'];
		}
		else
		{
			$this->response['errormsg'] = "Success";
			$this->response['success'] = true;
			$this->response['status'] = "A";
			$this->transInfo['td_process_result']="test";
			$this->transInfo['td_process_query']="test";
			$this->transInfo['td_bank_recieved']='no';
		}
		$this->transInfo['status'] = $this->response['status'];
		$this->transInfo['td_process_msg'] = $this->response['td_process_msg'];
		if(!$this->transInfo['td_process_msg']) 
			$this->transInfo['td_process_msg'] = $this->response['errormsg'];
	
		if($this->transInfo['status']=="D")
		{
			$this->transInfo['td_username']="";
			$this->transInfo['td_password']="";
		}
		else
		{
			if(!$this->transInfo['td_ss_ID'] && $this->transInfo['td_rebillingID']>1 && $this->mode == "Live" && $this->transInfo['status']!="D") //if there isn't a subscription for the trans then make one
			{
				$this->createSubscription();
				$this->set_transaction_subid();
				//$this->transInfo['td_ss_ID'] = $subsciption->transInfo['td_ss_ID'];
			}
		}
		$int_table = "cs_test_transactiondetails";
		if ($this->mode == "Live") $int_table = "cs_transactiondetails";

		$this->transInfo['td_process_duration'] = microtime_float()-$start_transaction;
		$this->transInfo['td_non_unique']=$this->fraud->check_unique($int_table,$this->transInfo);
		
		$this->sanitizeChargeInfo($this->transInfo['td_process_query']);
		$this->sanitizeChargeInfo($this->transInfo['td_process_result']);
		
		$qrt_update_details = "
			update 
				$int_table 
			set 
				`td_gcard` = '".$this->transInfo['td_gcard']."', 
				`td_bank_recieved` = '".$this->transInfo['td_bank_recieved']."',
				`td_fraud_score` = '".$this->transInfo['td_fraud_score']."',
				`status` = '".$this->transInfo['status']."',
				`td_username` = '".$this->transInfo['td_username']."',
				`td_process_msg` = '".quote_smart($this->transInfo['td_process_msg'])."',
				`td_password` = '".$this->transInfo['td_password']."',
				`td_bank_transaction_id` = '".$this->transInfo['td_bank_transaction_id']."',
				`td_process_query` = '".quote_smart($this->transInfo['td_process_query'])."',
				`td_process_result` = '".quote_smart($this->transInfo['td_process_result'])."',
				`td_process_duration` = '".quote_smart($this->transInfo['td_process_duration'])."',
				`td_non_unique` = '".quote_smart($this->transInfo['td_non_unique'])."',
				`td_ss_ID` = '".quote_smart($this->transInfo['td_ss_ID'])."'
			where 
				transactionId = '" . $this->trans_id . "'
			LIMIT 1	
			;
			";

		toLog('order','customer',"Transaction '".$this->transInfo['reference_number']."' Update Query: ".$qrt_update_details,$this->trans_id);
		$show_insert_run = sql_query_write($qrt_update_details) or dieLog(mysql_errno().": ".mysql_error()."<pre>$qrt_update_details</pre>");

		$rates->update_transaction_profit($this->trans_id,true);
		
		if($this->response['success'] == true)
		{
			$this->response['transactionId'] = $this->trans_id;
			
			if($this->transInfo['status'] == 'A' || ($this->transInfo['status'] == 'P' && $this->transInfo['checkorcard'] == 'C'))
			if($this->transInfo['td_send_email'] == 'yes')
			{
				// Email
				$email_to = $this->transInfo['email'];
				
				//$useEmailTemplate = "customer_recur_subscription_confirmation_cc";
				$useEmailTemplate = "customer_order_confirmation_cc";
				//if($this->transInfo['td_one_time_subscription']) $useEmailTemplate = "customer_subscription_confirmation_cc";
				//if($this->transInfo['td_is_a_rebill'] == 1) $useEmailTemplate = "customer_rebill_confirmation_cc";

				$data = array();
				$data['payment_type'] = $this->payment_type;
				$data['billing_descriptor'] = $this->transInfo['billing_descriptor'];
				$data['site_URL'] = $this->companyInfo['cs_URL'];
				$data['reference_number'] = $this->transInfo['reference_number'];
				$data['subscription_id'] = $this->transInfo['td_subscription_id'];
				$data['full_name'] = $this->transInfo['surname'].", ".$this->transInfo['name'];
				$pInfo = $this->transInfo['td_product_id'];
				$data['product_info'] = $pInfo.($pInfo?": ":''). $this->transInfo['productdescription'];
				$data['email'] = $email_to;
				$data['customer_email'] = $email_to;
				$data['credit_card_formatted'] = $this->credit_card_formatted;
				$data['amount'] = "$".formatMoney($this->transInfo['amount']-$this->transInfo['td_customer_fee'])." USD";
				$data['customer_fee'] = "$".formatMoney($this->transInfo['td_customer_fee'])." USD";
				$data['final_amount'] = "$".formatMoney($this->transInfo['amount'])." USD";
				$data['username'] = $this->transInfo['td_username'];
				$data['password'] = $this->transInfo['td_password'];
				$data['payment_schedule'] = $this->transInfo['payment_schedule'];
				if(!$data['payment_schedule']) $data['payment_schedule'] = 'No Schedule';
				$data['transaction_date'] = date("F j, Y G:i:s",strtotime($this->transInfo['transactionDate']));
				$data['next_bill_date'] = $this->transInfo['nextDateInfo'];
				$data['site_access_URL'] = $this->companyInfo['cs_member_url'];
				$data['customer_support_email'] = $this->companyInfo['cs_support_email'];
				$data['tmpl_language'] = $_SESSION['tmpl_language'];
				$data['gateway_select'] = $this->companyInfo['gateway_id'];
	
				$str_is_test = "THIS IS A TEST TRANSACTION ";
				if($this->mode=="Live") $str_is_test = "";
				if($this->transInfo['td_is_a_rebill']) $useEmailTemplate = "customer_recur_subscription_confirmation_cc";
				
				if(!$this->transInfo['td_is_a_rebill']) send_email_template($useEmailTemplate,$data,$str_is_test); // Send Customer Email.
				
				if($this->mode=="Live" && $this->bankInfo['bk_cc_bank_enabled']==1)
				{	
					$data['email'] = $this->bankInfo['bank_email'];
					send_email_template($useEmailTemplate,$data,"(Bank Copy) ");
				}
				if($this->companyInfo['cd_recieve_order_confirmations'])
				{	
					$data['email'] = $this->companyInfo['cd_recieve_order_confirmations'];
					send_email_template($useEmailTemplate,$data,$str_is_test."(Merchant Copy) ");
				}
			}

			if($mode!="Live")
			{
				$sql = "
					UPDATE 
						cs_companydetails 
					set 
						`cd_completion` = 7 
					WHERE 
						`cd_completion` = 6 
						AND `userId` = '".$this->transInfo['userId']."'
					";
				if($this->companyInfo['cd_completion']==6) sql_query_write($sql) or dieLog(mysql_error() . "<pre>$sql</pre>"); 
				$this->transInfo['td_product_id'] = "TEST MODE";
				$this->transInfo['amount'] = "TEST MODE";
			}
			
			$_SESSION['etel_trans_pending']=true;
			
			// Post Notification
			
			$notify = 'decline';
			if($this->transInfo['status']=='A' || ($this->transInfo['status'] == 'P' && $this->transInfo['checkorcard'] == 'C'))
				if($this->transInfo['td_is_a_rebill'])
					$notify = 'rebill';
				else
					$notify = 'approve';

			Process_Transaction($this->transInfo['reference_number'],$notify,($this->mode!="Live")); //approved or declined
		}
		$this->transInfo['transactionId'] = $this->trans_id;
		$this->response['transactionId'] = $this->transInfo['transactionId'];
		$this->response['reference_number'] = $this->transInfo['reference_number'];
		$this->response['td_subscription_id'] = $this->transInfo['td_subscription_id'];
		$this->response['watchInfo'] = $this->fraud->check_watchlist($this->transInfo);
		$_SESSION['etel_trans_pending']=false;
	}
	
	
	
	function set_transaction_subid()
	{
		$tran_table = "cs_transactiondetails";
		$sql = "
			UPDATE 
				$tran_table
			SET
				td_rebillingID = " . $this->transInfo['td_rebillingID'] . ",
				td_ss_ID = " . $this->transInfo['td_ss_ID'] . ",
				td_subscription_id = '" . $this->transInfo['td_subscription_id'] . "'
			WHERE
				transactionId = '" . $this->transInfo['transactionId'] . "'
			";
		$res = sql_query_write($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		if(!mysql_affected_rows())
			toLog('erroralert','customer',"set_transaction_subid() failed to update transaction: $sql");
	}
	
	function createSubscription()
	{
		if($this->transInfo['ss_account_status']=='inactive' && $this->transInfo['ss_rebill_status']=='inactive')
		{
			return $this->transInfo;
		}	
		$expD = explode("/",$this->transInfo['validupto']);
		$expYear = $expD[0];
		$expMonth = $expD[1];
		$salt = md5(mt_rand(1,2000000000));			
		
		//if($this->transInfo['checkorcard']=='C') $ss_billing_type = 'Check';
		//else if($this->transInfo['cardtype'] == 'Visa') $ss_billing_type = 'Visa';
		//else if($this->transInfo['cardtype'] == 'Master') $ss_billing_type = 'Mastercard';
		//else 
		$ss_billing_type = $this->transInfo['cardtype'];
		
		$ss_subscription_ID = $this->transInfo['td_subscription_id'];
		if(!$ss_subscription_ID) $ss_subscription_ID = genRefId("subscription","S");
		$this->transInfo['td_subscription_id'] = $ss_subscription_ID;

		if(!$this->transInfo['ss_account_status']) $this->transInfo['ss_account_status']='active';
		if(!$this->transInfo['ss_account_expire_date']) $this->transInfo['ss_account_expire_date']=$this->transInfo['td_recur_next_date'];
		if(!$this->transInfo['ss_rebill_status']) $this->transInfo['ss_rebill_status']=($this->transInfo['td_enable_rebill'] == 1 ? "active" : "inactive");
		if(!$this->transInfo['ss_rebill_status_text']) $this->transInfo['ss_rebill_status_text']='';
		if(!$this->transInfo['ss_rebill_amount']) $this->transInfo['ss_rebill_amount']=$this->transInfo['td_recur_charge'];
		if(!$this->transInfo['ss_rebill_next_date']) $this->transInfo['ss_rebill_next_date']=$this->transInfo['td_recur_next_date'];
		
		//$exists = $this->subscription_exists($this->transInfo['transactionId']);
		//if($exists) 																		// Why would this happen? -Ari
		//{
		//	$sql_action = "UPDATE";
		//	$sql_where = "WHERE ss_transaction_id = '" . $this->transInfo['transactionId']."'";  
		//}
		//else
		//{
			$sql_action = "INSERT INTO";
			$sql_where = "";
		//}
		$ss_rebill_status = 'active';
		
		$subscription	=	"$sql_action
							`cs_subscription`
							SET
							 `ss_subscription_ID`='".quote_smart($ss_subscription_ID)."',
							 `ss_billing_firstname` = '".quote_smart($this->transInfo['name'])."',
							 `ss_billing_mi` = '',
							 `ss_billing_lastname` = '".quote_smart($this->transInfo['surname'])."',
							 `ss_billing_address` = '".quote_smart($this->transInfo['address'])."',
							 `ss_billing_address2` =  '',
							 `ss_billing_city` = '".quote_smart($this->transInfo['city'])."',
							 `ss_billing_state` = '".quote_smart($this->transInfo['state'])."',
							 `ss_billing_country` = '".quote_smart($this->transInfo['country'])."',
							 `ss_billing_zipcode` = '".quote_smart($this->transInfo['zipcode'])."',
							 `ss_billing_last_ip` = '".quote_smart($this->transInfo['ipaddress'])."',
							 `ss_billing_type` = '".$ss_billing_type."',
							 `ss_billing_card` = '".transaction_class::etelEncSalted($this->transInfo['CCnumber'],$salt)."',
							 `ss_billing_exp` = '".($this->transInfo['checkorcard'] == 'H' ? date("Y-m-d",strtotime($expYear."-".$expMonth."-01")) : "")."',
							 `ss_billing_cvv2` = '".transaction_class::etelEncSalted($this->transInfo['cvv'],$salt)."',
							 `ss_billing_check_account` = '".transaction_class::etelEncSalted($this->transInfo['bankaccountnumber'],$salt)."',
							 `ss_billing_check_routing` = '".transaction_class::etelEncSalted($this->transInfo['bankroutingcode'],$salt)."',
							 `ss_salt` = '$salt',
							 `ss_cust_email` = '".quote_smart($this->transInfo['email'])."',
							 `ss_cust_phone` = '".quote_smart($this->transInfo['phonenumber'])."',
							 `ss_cust_username` = '".quote_smart($this->transInfo['td_username'])."',
							 `ss_cust_password` = '".quote_smart($this->transInfo['td_password'])."',
							 `ss_rebill_ID` = '".quote_smart($this->transInfo['td_rebillingID'])."',
							 `ss_rebill_next_date` = '".$this->transInfo['ss_rebill_next_date']."',
							 `ss_rebill_amount` = '".$this->transInfo['td_recur_charge']."',
							 `ss_rebill_status` = '".$this->transInfo['ss_rebill_status']."',
							 `ss_rebill_status_text` = '".$this->transInfo['ss_rebill_status_text']."',
							 `ss_rebill_attempts` = 0,
							 `ss_rebill_count` = 0,
							 `ss_account_status` = '".$this->transInfo['ss_account_status']."',
							 `ss_account_start_date` = now(),
							 `ss_account_notes` = CONCAT(NOW(), ': Transaction (".$this->transInfo['reference_number'].") Approved. Creating Subscription...\nNext Rebill Date: ".$this->transInfo['td_recur_next_date']."'),
							 `ss_account_expire_date` = '".$this->transInfo['ss_account_expire_date']."',
							 `ss_transaction_id` = '".$this->transInfo['transactionId']."',
							 `ss_productdescription` = '".quote_smart($this->transInfo['productdescription'])."',
							 `ss_site_ID` = '".$this->transInfo['td_site_ID']."',
							 `ss_user_id` ='".$this->transInfo['userId']."',
							 `ss_bank_id` ='".$this->transInfo['bank_id']."'
							 $sql_where 
							";

			
		sql_query_write($subscription)
			or dieLog(mysql_error());
		
		$this->transInfo['td_ss_ID'] = mysql_insert_id();

		return $this->transInfo;
	}
}
?>