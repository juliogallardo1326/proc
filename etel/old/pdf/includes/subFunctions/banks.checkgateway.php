<?
class CheckGateway_Client 
{
	var $default_params;
	var $bank_url;
	var $bank_server;
	var $bank_script;
	var $bank_params;
	
	var $url;
	
	var $csv_fields;
	var $severity_codes;
	var $ach_status;
	
	function CheckGateway_Client($bankInfo,$mode = "test")
	{
		//these need to be in the bankinfo table
		$this->default_params['MerchantNumber'] = $bankInfo['bk_username'];
		$this->default_params['Password'] = $bankInfo['bk_password'];
		$this->default_params['Version'] = "1.3";
		$this->default_params['XML'] = "False";
		
		// https://epn.CheckGateway.com/EpnPublic/ACH.aspx
		
		//this is fine here
		if($mode == "test")
			$this->bank_server = "epn.CheckGateway.com";
		else
			$this->bank_server = "epn.CheckGateway.com";

		$this->bank_url = "https://" . $this->bank_server . "/EpnPublic/";
		
		$this->severity_codes = array(
			0 => "No Errors",
			1 => "Customer Error",
			2 => "Customer Error, Merchant Preventable",
			3 => "Denied By Provider",
			4 => "Merchant Error, Merchant Should Correct Problem",
			5 => "Merchant Account Issue, Merchant May Need to Contact Check Gateway",
			6 => "Check Gateway Error, Merchant Should Contact Check Gateway"
		);
		
		$this->csv_fields = array(
			"CompanyID",
			"ResponseType",
			"TransID",
			"BillerName",
			"ServiceType",
			"ExternalClientID",
			"BankAccountName",
			"ExtTransID",
			"ResponseDate",
			"EED",
			"TransmitTime",
			"EnteredBy",
			"TransType",
			"Amount",
			"EntryDescription",
			"ItemDescription",
			"TRN Integer",
			"DDA Integer",
			"CheckNumber",
			"ResponseCode",
			"AddInfo"
		);	

		$this->ach_status = array(
			"Processed"=>"Not Downloaded: we have received the transaction from you, but it has not been transmitted to the Federal Reserve for processing yet.",
			"B"=>"Originated: the transaction has been transmitted to the Federal Reserve for processing.",
			"F"=>"Funded: we have paid you on this transaction.",
			"R"=>"Returned: the item was returned, i.e.: credit issued, chargeback, invalid, nsf, etc.",
			"NSF"=>"Non-Sufficient Funds: the item was returned Non-Sufficient Funds",
			"ChargeBack"=>"Customer Initiated Refund: the consumer advised their bank that the transaction was not authorized or revoked the authorization.",
			"Invalid"=>"Invalid Banking Information: not a valid checking account number, account frozen, etc.",
			"Declined"=>"Declined by Authentication: the transaction did not pass our authentication service. (StarChek, Experian, Etc).",
			"Refund"=>"Merchant Issued Refund: this transaction was refunded.",
			"Credit"=>"Merchant Issued Credit: Merchant Issued Credit",
			"Incomplete"=>"Transaction Not Processed: transaction not processed, merchant must resubmit these in order for item to be processed.",
			"Cancelled"=>"Transaction Cancelled by Merchant: Transaction Cancelled by Merchant",
			"BO Exception"=>"Back Office Exception / Error: we were unable to process these transactions for one or more of the following reasons; you can find the code under the Communication History tab for that transaction. Just click on the transaction id and you will be able to see the Communication History button.",
			"Downloaded"=>"Step before Origination: the transaction has been downloaded from the web and we are preparing to transmit the files to the Federal Reserve for processing. A transaction that is labeled \"Downloaded\" will then be updated to \"Originated\" later that same day.",
			"Credit Originated"=>" Credit Originated: this is a credit transaction that has been originated to the receivers account. (This is usually a new transaction created as a result of a separate transaction that was refunded)",
			"Credit Downloaded"=>"Credit Downloaded: the transaction has been downloaded from the web and we are preparing to transmit the files to the Federal Reserve for processing. A transaction that is labeled \"Downloaded\" will then be updated to \"Originated\" later that same day.",
			"Credit Return"=>"Credit Return: the item was returned, i.e.: credit issued, chargeback, invalid, nsf, etc.",
			"Credit Funded"=>"Credit Funded: we have paid you on this transaction."
		);
	}

	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}
		
	function verify_required($req,$given)
	{
		$temp = array();
		foreach($given as $index => $value)
			$temp[strtolower($index)] = $value;
			
		$required = "";
		foreach($req as $reqed)
			if(!isset($temp[strtolower($reqed)]))
				$required .= ($required == "" ? "" : ", ") . $reqed;
		return $required;
	}
	
	function execute_request($params,$req_params,$opt_params = NULL)
	{
		$this->bank_params = $req_params;
		foreach($this->default_params as $param => $value)
			$this->bank_params[] = $param;
			
		if($opt_params != NULL)
			$this->bank_params = array_merge($opt_params,$this->bank_params);

		foreach($this->bank_params as $index => $value)
			$this->bank_params[$index] = strtolower($value);

		$res = $this->verify_required($req_params,$params);
		if($res != "")
			return array("status"=>"-1","desc" => "required parameter(s) $res are missing from request");
		
		$res = $this->process_request($params);
		
		$values = $this->process_result($res);
		return $values;	
	}
	
	function process_result($res)
	{
		$res = urldecode($res);
		$res = explode("&",$res);
		$values = array();
		foreach($res as $val)
		{
			$val = explode("=",$val);
			$values[$val[0]] = $val[1];
		}
		
		if(isset($values['Severity']))
			$values['ServerityText'] = $this->severity_codes[$values['Severity']];
		
		$values['post_url'] = $this->url;
		
		return $values;
	}
	
	function process_request($params)
	{	
		if($params == NULL)
			$params = array();

		$post_params = "";
		$params = array_merge($this->default_params,$params);
		
		foreach($params as $name=>$value)
			if(in_array(strtolower($name),$this->bank_params))
				$post_params .= ($post_params == "" ? "" : "&") . $name . "=" . urlencode($value);
		$post_url = $this->bank_url . $this->bank_script . "?$post_params";
		
		$this->url = $post_url;
		$res = http_post2($this->bank_server, 443, $post_url, $post_params, "", "");
		return $res;
	}

	function Check_Authorize($params)
	{
		$this->bank_script = "ACH.aspx";

		$params['Action'] = "Authorize";

		//required parameters
		$req_params = array(
			"Action",
			"ReferenceNumber",
			"Amount",
			"RoutingNumber",
			"AccountNumber"
			);

		//optional parameters
		$opt_params = array(
			"CheckNumber",
			"Name",
			"Address1",
			"Address2",
			"City",
			"State",
			"Zip",
			"PhoneNumber",
			"Email",
			"BirthDate",
			"SSN",
			"DrvLicNumber",
			"DrvLicState"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}	
	
	function Check_Cancel($params)
	{
		$this->bank_script = "ACH.aspx";

		$params['Action'] = "Cancel";

		//required parameters
		$req_params = array(
			"transactionId"
			);

		//optional parameters
		$opt_params = array(
			"Notes"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}		

	function Check_Credit($params)
	{
		$this->bank_script = "ACH.aspx";

		$params['Action'] = "Credit";

		//required parameters
		$req_params = array(
			"Action",
			"ReferenceNumber",
			"Amount",
			"RoutingNumber",
			"AccountNumber"
			);

		//optional parameters
		$opt_params = array(
			"CheckNumber",
			"Name",
			"Address1",
			"Address2",
			"City",
			"State",
			"Zip",
			"PhoneNumber",
			"Email",
			"BirthDate",
			"SSN",
			"DrvLicNumber",
			"DrvLicState"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}	

	function Check_Debit($params)
	{
		$this->bank_script = "ACH.aspx";

		$params['Action'] = "Debit";

		//required parameters
		$req_params = array(
			"Action",
			"ReferenceNumber",
			"Amount",
			"RoutingNumber",
			"AccountNumber"
			);

		//optional parameters
		$opt_params = array(
			"CheckNumber",
			"Name",
			"Address1",
			"Address2",
			"City",
			"State",
			"Zip",
			"PhoneNumber",
			"Email",
			"BirthDate",
			"SSN",
			"DrvLicNumber",
			"DrvLicState"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}
	
	function Check_Refund($params)
	{
		$this->bank_script = "ACH.aspx";

		$params['Action'] = "Refund";

		//required parameters
		$req_params = array(
			"Action",
			"transactionId"
			);

		//optional parameters
		$opt_params = array(
			"Notes"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		return $values;
	}

	function Status_Download($params)
	{
		$this->bank_script = "FileDownload.aspx";
	
		//required parameters
		$req_params = array(
			"Format",
			"Incremental"
			);

		//optional parameters
		$opt_params = array(
			"DateFrom",
			"DateTill",
			"ReturnsOnly",
			"ShortResponse"
		);
		
		$values = $this->execute_request($params,$req_params,$opt_params);
		$csv_txt = array();

		foreach($values as $csv => $blank)
			$csv_txt[] = $csv;
		$csv_txt = implode("\r\n",$csv_txt);
		$csv_txt = str_replace("\r","\n",$csv_txt);
		$csv_txt = str_replace("\n\n","\n",$csv_txt);
		$csv_txt = explode("\n",$csv_txt);
		

		
		$values = array();
		
		foreach($csv_txt as $index => $row)
		if($row != "")
		{
			$row = explode(",",$row);
			$ordered = array();
			foreach($row as $cnt => $value)
				$ordered[$this->csv_fields[$cnt]] = $value;
			$values[$index] = $ordered;
		}
		
		return $values;
	}

	function process_transactions($params)
	{
		set_time_limit(0);
		if(!$params['Format']) $params['Format'] = "TXT";
		if(!$params['Incremental']) $params['Incremental'] = "True";
		
		$RF = new rates_fees();
		$trans = $this->Status_Download($params);
		$return_affected_rows = 0;
		$approve_affected_rows = 0;
		$log = "";
		foreach($trans as $tran)
		{
			$update = false;
			$chargeback = false;
			$refund = false;
			$status = "";
			
			switch(strtolower($tran['ResponseType']))
			{
				case "processed": 		$update = false; break;
				case "b": 				$update = false; break;
				case "f": 				$update = true; $status = 'A'; break;
				case "r": 				$update = true; $status = 'D'; break;
				case "nsf": 			$update = true; $status = 'D'; break;
				case "chargeback":		$update = true; $status = 'D'; $chargeback = true; break;
				case "invalid": 		$update = true; $status = 'D'; break;
				case "declined": 		$update = true; $status = 'D'; break;
				case "refund": 			$update = true; $status = 'D'; $refund = true; break;
				case "credit": 			$update = true; $status = 'D'; $refund = true; break;
				case "incomplete": 		$update = true; $status = 'D'; break;
				case "cancelled": 		$update = true; $status = 'D'; break;
				case "bo exception":	$update = true; $status = 'D'; break;
				case "downloaded": 		$update = false; break;
				case "credit originated": $update = false; break;
				case "credit downloaded": $update = false; break;
				case "credit return": 	$update = true; $status = 'D'; $chargeback = true; break;
				case "credit funded": 	$update = true; $status = 'A'; break;
			}
			$refid = substr($tran['ExternalClientID'],0,50);
			if(!$refid) continue;
			$sql = "
				select * from 
					cs_transactiondetails left join 
					cs_subscription on 
					td_ss_ID = ss_ID
				WHERE
					reference_number = '$refid'
					AND checkorcard='C'		
				LIMIT 1
			";
			$tranResult = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
			$transInfo = mysql_fetch_assoc($tranResult);
			$transId = $transInfo['transactionId'];
			if($transInfo['status']!='P' && $update==true) $update = false;
			if(!$transId)
			{
				$log .= " Transaction ID Not Found!! ".print_r($tran,true);
				toLog('erroralert','misc',"Transaction ID Not Found!! $transId $sql");
				$update = false;
			}
			
			
			if($update)
			{
				$log .= " Found Response Type (".$tran['ResponseType'].") For ($refid):\n";
				$notify = 'decline';
				$bank_transid =  $tran['TransID'];
				if($tran['ResponseDate'])
					$billingDate = date('Y-m-d',strtotime($tran['ResponseDate']));
				else
					$billingDate = "";
	

					
				if($chargeback)
				{ //is_chargeback
					$sql = "
						UPDATE
							cs_transactiondetails 
							left join cs_subscription on td_ss_ID = ss_ID
							left join cs_profit_action on transactionID = pa_trans_id
						SET
							pa_status = 'pending',
							status='A',
							td_process_msg = 'Approved, then Charged Back',
							td_is_chargeback = 1,
							td_bank_transaction_id = '$bank_transid',
							billingDate = '$billingDate',
							td_merchant_deducted=0,
							ss_rebill_status = 'inactive',
							ss_rebill_status_text = 'Subscription Inactive due to Chargeback'
						WHERE
							transactionId = '$transId'
							AND checkorcard='C'		
					";
					$log .= "  This transaction is a chargeback.\n";
					$notify = 'chargeback';
					$r = $RF->update_transaction_profit($transId);
				}
				else
				if($refund)
				{ // cancel_status = y, cancel subscription
					$sql = "
						UPDATE
							cs_transactiondetails 
							left join cs_subscription on td_ss_ID = ss_ID
							left join cs_profit_action on transactionID = pa_trans_id
						SET
							pa_status = 'pending',
							status='A',
							td_process_msg = 'Approved, then Refunded',
							cancelstatus = 'Y',
							td_bank_transaction_id = '$bank_transid',
							billingDate = '$billingDate',
							td_merchant_deducted=0,
							ss_rebill_status = 'inactive',
							ss_rebill_status_text = 'Subscription Inactive due to Refund'
						WHERE
							transactionId = '$transId'
							AND checkorcard='C'			
					";
					$log .= "  This transaction is a refund.\n";
					$notify = 'refund';
					$r = $RF->update_transaction_profit($transId);
				}
				else
				{
					$ss_rebill_status_sql = ($status=='D'?"ss_rebill_status = 'inactive', ":'');
					$ss_rebill_status_text = ($status=='D'?'Subscription Inactive due to decline ('.$this->ach_status[$tran['ResponseType']].')':'Subscription Active');
					$sql = "
						UPDATE
							cs_transactiondetails 
							left join cs_subscription on td_ss_ID = ss_ID
							left join cs_profit_action on transactionID = pa_trans_id
							
						SET
							pa_status = 'pending',
							td_bank_transaction_id = '$bank_transid',
							status = '$status',
							td_process_msg = '".quote_smart($this->ach_status[$tran['ResponseType']])."',
							billingDate = '$billingDate',
							td_merchant_deducted=0,
							td_merchant_paid=0,
							$ss_rebill_status_sql
							ss_rebill_status_text = '$ss_rebill_status_text'
						WHERE
							transactionId = '$transId'
							AND checkorcard='C'	AND status='P'	
					";
					$log .= "  This transaction's status is (".$this->ach_status[$tran['ResponseType']].").\n";
					$r = $RF->update_transaction_profit($transId);
					

				}
				sql_query_write($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");

				$affected = mysql_affected_rows();
					
				if($status != 'A')
					$return_affected_rows+= $affected;
				else
					$approve_affected_rows+= $affected;
					
				if($status=='A')
					if($transInfo['td_is_a_rebill'])
						$notify = 'rebill';
					else
						$notify = 'approve';
	
				if($affected) Process_Transaction($transId,$notify,0,'transactionId');				
			
			}
			else
				$log .= "  Ignoring Transaction.\n";
		}
		$log .= "CheckGateway Result: ($return_affected_rows) Returns, ($approve_affected_rows) Approves.\n";
		return $log;
	}
}
?>