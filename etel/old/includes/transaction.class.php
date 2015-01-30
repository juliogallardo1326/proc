<?
class transaction_class
{
	var $test, $rebill, $field, $value; // constructor propertires...burrito
	var $row; // row property with most values from the database...burrito
	var $amount;
	var $customerfee;
	var $etel_fraud_response;
	var $errormsg;
	var $transArr;
	var $orderarr;
	var $transphase;
	var $merchantdetails;
	var $isx;
	var $userid;
	var $en_ID;
	var $transid;
	var $joinmethod = 'transaction';
	
	function transaction_class($test = FALSE)
	{
		$this->test = $test;
	}
	
	function pull_transaction($id,$field='td.transactionId')
	{
		$this->joinmethod = 'transaction';
		$this->init_transaction($id,$field,FALSE);
	}

	function pull_subscription($id,$field='ss.ss_ID')
	{
		$this->joinmethod = 'subscription';
		$this->init_transaction($id,$field,TRUE);
	}
	
	function init_transaction(
			$value = FALSE, 	// Select by Value
			$field = FALSE,    	// Select by Field
			$isrebill = FALSE,   	// Is a Rebill
			$orderArr = FALSE, 	// Only if new order
			$md = FALSE,
			$isx = FALSE,
			$transid = FALSE
		)
	{
		$this->attempts = 1;
		$this->isrebill = $isrebill;
		$this->orderarr = $orderArr;
		$this->userid = $orderArr['userId'];
		$this->en_ID = $orderArr['en_ID'];
		$this->field = $field;
		$this->value = $value;
		$this->transphase = $transphase;
		$this->merchantdetails = $md;
		$this->isx = $isx;
		$this->transid = $transid;
		$this->row = $this->getRow();
		$this->rebill = $this->getRebillInfo(!$rebill);
		if($this->row['subscriptionTable']['ss_rebill_amount']) $this->amount = $this->row['subscriptionTable']['ss_rebill_amount'];
		if(!$this->amount) $this->amount = $this->getChargeAmount();
		$this->customerfee = ($this->isx ? 0 : $this->getCustomerFee());
		$this->amount += $this->customerfee; // Total includes fee!!!
	}
	
	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}
		
	function buildOrderArr($orderInfo)
	{
		$refID=$orderInfo['mt_reference_id'];
		$subId=$orderInfo['mt_subAccount'] != "-1" ? $orderInfo['mt_subAccount'] : "";
		
		$qry	=	"SELECT 
						w.cs_ID,
						w.cs_URL,
						w.cs_en_ID as en_ID,
						c.userId,
						".($subId ? "r.rd_subaccount," : "")."
						c.bank_check,
						c.bank_Creditcard,
						c.cd_secret_key,
						c.cd_verify_rand_price
					FROM
						cs_company_sites as w
					LEFT JOIN
						cs_companydetails as c 
						ON c.userId = w.cs_company_id
	  ".($subId ? " LEFT JOIN 
	 					cs_rebillingdetails as r
						on r.company_user_id = c.userId
						" : "")."
					WHERE
						w.cs_reference_ID = '$refID'
						".($subId ? "AND r.rd_subName = '$subId'" : "");
		$result = sql_query_read($qry) or dieLog(mysql_error()."<pre>$qry</pre>");
		
		if(!$row = mysql_fetch_assoc($result)) return FALSE;
		
		$row['refID'] = $refID;
		$row['subId'] = $subId;	
		$row['url_verified'] = true;
				
				
		$cs_url_parts = parse_url($row['cs_URL']);
		$gw_url_parts = parse_url($orderInfo['gw_domain']);
		$gw_int_parts = parse_url($orderInfo['gw_integration_site']);
		$url_info = 	parse_url($orderInfo['from_url']);
		
		$hostcur = 		str_replace("www.","",strtolower($url_info['host']));
		$host1 = 		str_replace("www.","",strtolower($cs_url_parts['host']));
		$host2 = 		str_replace("www.","",strtolower($gw_url_parts['host']));
		$host3 = 		str_replace("www.","",strtolower($gw_int_parts['host']));
			
		if($hostcur == "" || ($hostcur != $host1 && $hostcur != $host2 && $hostcur != $host3))
		{
			$row['url_verified'] = false;
			$row['url_verified_text'] = "The Payment Gateway for '$host1' was accessed from '$hostcur'. Please contact your webmaster.";
		}
		return $row;
	}	

	function buildApproval()
	{
		if($_SESSION['Success_Posted_Vars']) return $_SESSION['Success_Posted_Vars'];
		//if(!$this->row['transactionTable']['reference_number'])
		//	return '';
			
		$postedvars = "";
		//echo $this->merchantdetails['mt_posted_variables'];
		$md = unserialize($this->merchantdetails['mt_posted_variables']);
		$return_message = 'SUC';
		if($this->merchantdetails['response']['status']=='D') $return_message = 'DEC';
		
		foreach($md as $key=>$val)
		{
			$postedvars .= "<input type='hidden' name='$key' value='$val'>\n";
		}
		
		$postedvars .= "<input type='hidden' name='mt_transaction_result' value='$return_message'>\n";
		$postedvars .= "<input type='hidden' name='mt_total_amount' value='".$this->amount."'>\n";
		$postedvars .= "<input type='hidden' name='mt_reference_number' value='".$this->row['transactionTable']['reference_number']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_subscription_id' value='".$this->merchantdetails['response']['td_subscription_id']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_product_id' value='".$this->merchantdetails['mt_product_id']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_useremail' value='".$this->merchantdetails['email']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_usercountry' value='".$this->merchantdetails['country']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_usercity' value='".$this->merchantdetails['city']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_userstate' value='".$this->merchantdetails['state']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_userzip' value='".$this->merchantdetails['zipcode']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_userip' value='{$_SERVER['REMOTE_ADDR']}'>\n"; // BUG: this should come from the transaction info, not server.
		$postedvars .= "<input type='hidden' name='mt_userfirst' value='".$this->merchantdetails['firstname']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_userlast' value='".$this->merchantdetails['lastname']."'>\n";
		$postedvars .= "<input type='hidden' name='mt_charge_type' value='".$this->merchantdetails['cardtype']."'>\n";
		if($this->row['websiteTable']['cs_enable_passmgmt'] == 1)
		{
			$postedvars .= "<input type='hidden' name='mt_username' value='".$this->merchantdetails['td_username']."'>\n";
			$postedvars .= "<input type='hidden' name='mt_password' value='".$this->merchantdetails['td_password']."'>\n";
		}
		if(isset($md['mt_amount']) && $md['mt_amount'] != "")
		{
			$check = md5($this->row['companydetailsTable']['cd_secret_key'].$this->row['websiteTable']['cs_reference_ID'].$md['mt_amount'].$this->row['transactionTable']['reference_number']);
			$postedvars .= "<input type='hidden' name='verify_checksum' value='$check'>\n";
			etelPrint("Verify Checksum:<br>" . $check."&nbsp;&nbsp;".$_SESSION['mt_checksum']."&nbsp");
			etelPrint($this->row['companydetailsTable']['cd_secret_key']."-".$this->row['websiteTable']['cs_reference_ID']."-".$md['mt_amount']."-".$this->row['transactionTable']['reference_number']);
		}
		if($this->row['rebillingTable']['rd_pin_coding_enabled'])
		{
		
			$sql	=	"SELECT * 
						FROM 
						cs_pincodes 
						WHERE 
						pc_subAccount = '{$this->row['rebillingTable']['rd_subaccount']}' and pc_used = 0";
			$result = sql_query_read($sql) 
				or dieLog(mysql_error()." ~ $sql");
			
			if($cs_pincodes = mysql_fetch_assoc($result))
			{
				$postedvars .= "<input type='hidden' name='mt_pincode' value='".$cs_pincodes['pc_code']."'>\n";
				$postedvars .= "<input type='hidden' name='mt_pincode_username' value='".$cs_pincodes['pc_code']."'>\n";
				$postedvars .= "<input type='hidden' name='mt_pincode_password' value='".$cs_pincodes['pc_pass']."'>\n";
				$ibill_array['CODE']=$cs_pincodes['pc_code'];
				$ibill_array['USER']=$cs_pincodes['pc_code'];
				$ibill_array['NAME']=$cs_pincodes['pc_code'];
				$ibill_array['USRENAME']=$cs_pincodes['pc_code'];
				$ibill_array['PASS']=$cs_pincodes['pc_pass'];
				$ibill_array['PASSWORD']=$cs_pincodes['pc_pass'];
			
				$sql	=	"UPDATE
							 cs_pincodes 
							 SET pc_used = 1,
							 pc_trans_ID = '".$this->merchantdetails['response']['transactionId']."'  
							 WHERE pc_ID = '".$cs_pincodes['pc_ID']."' ";
				sql_query_write($sql) 
					or dieLog(mysql_error());
			}
		}
		$_SESSION['Success_Posted_Vars'] = $postedvars;
		return $postedvars;
	}
	
	function getRow() // method to build the row array used throughout the class...burrito
	{
		$transTable = ($this->test ? "cs_test_transactiondetails" : "cs_transactiondetails");
		
		if($this->orderarr && $this->joinmethod == 'transaction')
		{
			$transTable	=	"(
							SELECT 
							1 AS transactionId, 
							'{$this->orderarr['userId']}' AS userId, 
							'{$this->orderarr['rd_subaccount']}' AS td_rebillingID, 
							'{$this->orderarr['bank_Creditcard']}' AS bank_id,
							'{$this->orderarr['cs_ID']}' AS td_site_ID,
							0 AS td_ss_ID
							)"; // pseudo-table for new orders that don't have an existing transaction...burrito
		}
		if(!$this->value || !$this->field)
		{
			dieLog("Error: Transaction Initiated without search fields. ~ ".print_r($this,true));
			//$where	=	"ERROR: Shouldn't happen."; // where clause to loop over subscriptions to rebill...burrito
		}
		else
			$where	=	"{$this->field} = '{$this->value}'";
			
		$from = "$transTable as td
			LEFT JOIN
				cs_subscription as ss ON td.td_ss_ID = ss.ss_ID
			LEFT JOIN
				cs_companydetails as cd ON td.userId = cd.userId
			LEFT JOIN
				cs_entities as en ON en.en_type_ID = cd.userId and en_type = 'merchant'
			LEFT JOIN
				cs_rebillingdetails as rd ON td.td_rebillingID = rd.rd_subaccount
			LEFT JOIN
				cs_bank as bk ON td.bank_id = bk.bank_id
			LEFT JOIN
				cs_company_sites as cs ON td.td_site_ID = cs.cs_ID
				";
					
		if(	$this->joinmethod == 'subscription')
		{
			$from = "cs_subscription as ss
				LEFT JOIN
					$transTable as td ON td.transactionId = ss.ss_transaction_id
				LEFT JOIN
					cs_companydetails as cd ON ss.ss_user_ID = cd.userId
				LEFT JOIN
					cs_entities as en ON en.en_type_ID = cd.userId and en_type = 'merchant'
				LEFT JOIN
					cs_rebillingdetails as rd ON ss.ss_rebill_ID = rd.rd_subaccount
				LEFT JOIN
					cs_bank as bk ON td.bank_id = ss.ss_bank_id
				LEFT JOIN
					cs_company_sites as cs ON ss.ss_site_ID = cs.cs_ID
					";
		}
		
		$tabsArr = array("transactionTable","entityTable","rebillingTable","subscriptionTable","websiteTable","bankTable","companydetailsTable");
		
		$qry	=	"SELECT
					1 as transactionTable,
						td.*,
					1 as entityTable,
						en.*,
					1 as rebillingTable,
						rd.*,
					1 as subscriptionTable,
						ss.*,
					1 as websiteTable,
						cs.*,
					1 as bankTable,
						bk.bk_cc_bank_enabled,
						bk.bank_email,				
						bk.bk_descriptor_master,
						bk.bk_descriptor_visa,	
					1 as companydetailsTable,
						cd.contact_email,
						cd.customer_service_phone,
						cd.cd_recieve_order_confirmations,
						cd.companyname, 
						cd.cd_tracking_init_response, 
						cd.cd_enable_tracking, 
						cd.cc_customer_fee,
						cd.gateway_id,
						cd.cd_secret_key
					FROM
						$from
					WHERE 
						$where					
					LIMIT 1 
				";
				
		$result = sql_query_read($qry) or dieLog(mysql_error() . "$qry".print_r($_REQUEST,true));
		
		if($row = mysql_fetch_assoc($result))
		{
			$theRow = array();
			$whichTable = "";
			foreach($row as $key=>$val) // build row array from results returned...burrito
			{
				if(in_array($key,$tabsArr))
					$whichTable = $key;
				else						
					$theRow[$whichTable][$key] = $val;
			}
		}
		else
			$theRow = FALSE;
		
		
		$this->row['subscriptionTable']['ss_salt'] = $theRow['subscriptionTable']['ss_salt'];
		
		$RF = new Rates_Fees();
		$theRow['ratesTable'] = $RF->get_MerchantRates($theRow['entityTable']['en_ID']);
		
		if(	$this->joinmethod == 'subscription')
		{
			$theRow['Custom']['CreditCardFormatted'] = $this->SanitizeNumber($this->etelDecSalted($theRow['subscriptionTable']['ss_billing_card']));
			$theRow['Custom']['CheckRoutingFormatted'] = $this->SanitizeNumber($this->etelDecSalted($theRow['subscriptionTable']['ss_billing_check_routing']));
			$theRow['Custom']['CheckAccountFormatted'] = $this->SanitizeNumber($this->etelDecSalted($theRow['subscriptionTable']['ss_billing_check_account']));
		}
		else
		{		
			$theRow['Custom']['CreditCardFormatted'] = $this->SanitizeNumber(etelDec($theRow['transactionTable']['CCnumber']));
			$theRow['Custom']['CheckRoutingFormatted'] = $this->SanitizeNumber(etelDec($theRow['transactionTable']['bankroutingcode']));
			$theRow['Custom']['CheckAccountFormatted'] = $this->SanitizeNumber(etelDec($theRow['transactionTable']['bankaccountnumber']));	
		}
		return $theRow;	
	}
	
	// data sanitation methods below....burrito
	function past24Approve($transInfo,$hours=24)
	{ 
		if($transInfo['checkorcard'] == 'C') 
			return;
		if($transInfo['td_is_a_rebill']) 
			return 0;
		$hours = intval($hours);
		if($hours<=1) $hours=1;
		$sql	=	"SELECT transactionDate
					FROM cs_transactiondetails
					WHERE (CCnumber = '".etelEnc($transInfo['CCnumber'])."'
					OR ipaddress = '".$transInfo['ipaddress']."')
					AND status != 'D' 
					AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)";
		$result = sql_query_read($sql) 
			or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		$numrows = mysql_num_rows($result);
		return ($numrows > 0);		
	}
	
	function pastDecline($transInfo,$hours=24)
	{
		if($transInfo['td_is_a_rebill']) 
			return 0 ;
		$sql	=	"SELECT *
					FROM cs_transactiondetails
					WHERE CCnumber = '".etelEnc($transInfo['CCnumber'])."' 
					AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)
					AND (status != 'A' or cancelstatus = 'Y' or td_is_chargeback=1) 
					AND (td_bank_recieved = 'yes' or td_bank_recieved = 'fraudscrubbing')";
		$result = sql_query_read($sql) 
			or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		$numrows = mysql_num_rows($result);
		return ($numrows >= 3);
	}
	
	function checkUnique($table,$transInfo)
	{
		$sql	=	"SELECT *
					FROM $table
					WHERE status = 'A' 
					AND ipaddress = '".$transInfo['ipaddress']."'";
		$result = sql_query_read($sql) 
			or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		$numrows = mysql_num_rows($result);
		return $numrows;
	}
	
	function checkGkard($transInfo)
	{
		$sql	=	"SELECT td_gcard
					FROM cs_transactiondetails
					WHERE CCnumber LIKE '".etelEnc($transInfo['CCnumber'])."'
					AND (status = 'A' ) 
					AND (td_gcard IS NOT NULL )";
	
		$result = sql_query_read($sql) 
			or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		if (mysql_num_rows($result)<=0) 
			return 0;
		$td_gcard = mysql_fetch_assoc($result);
		return (etelDec($td_gcard['td_gcard']));
	}
	
	function getXSales($bin=FALSE,$exist=FALSE)
	{
		if(!isset($this->row['websiteTable']['cs_crosssale_niche']) || $this->row['websiteTable']['cs_crosssale_niche'] == 0)
			return FALSE;
		else
		{
			$qry	=	"SELECT 
						w.*,
						r.*,
						c.*
						FROM
						cs_company_sites as w,
						cs_rebillingdetails as r,
						cs_companydetails as c
						WHERE
						w.cs_crosssale_niche & $bin
						AND
						w.cs_company_id = r.company_user_id
						AND
						w.cs_company_id = c.userId
						AND 
						w.cs_company_id <> '{$this->userid}'
						AND
						w.cs_verified = 'approved'
						ORDER BY w.cs_crosssale_score * rand() DESC
						LIMIT 1";
						
			$result = sql_query_read($qry)
				or dieLog(mysql_error());
			if(!$row = mysql_fetch_assoc($result)) 
				return FALSE;
			else
			{
				$update	=	"UPDATE
							cs_company_sites
							SET
							cs_crosssale_score = cs_crosssale_score - 1,
							cs_crosssale_hits = cs_crosssale_hits + 1
							WHERE
							cs_ID = '{$row['cs_ID']}'";
				sql_query_write($update)
					or dieLog(mysql_error());
					
				$update	=	"UPDATE
							cs_company_sites
							SET
							cs_crosssale_score = cs_crosssale_score + 1
							WHERE
							cs_ID = '{$this->orderarr['cs_ID']}'";
				sql_query_write($update)
					or dieLog(mysql_error());
			}
			return $row;
		}
	}
	
	
	function getCustomerFee()
	{
		$fee = $this->row['ratesTable'][0]['default']['Processor']['cstsv'];
		if(!isset($this->row['ratesTable'][0]['default']['Processor']['cstsv']))
			$fee = $this->row['companydetailsTable']['cc_customer_fee'];
		return $fee;
	}
	
	function getChargeAmount()
	{
		if($this->isrebill)
			$thAmount = $this->rebill['chargeAmount']; //transaction amount for rebill...burrito
		else
		{
			if(isset($this->merchantdetails['mt_amount']) && $this->merchantdetails['mt_amount'] != "")
				$thAmount = $this->merchantdetails['mt_amount'];
			else if($this->rebill)			
				$thAmount = $this->rebill['chargeAmount']; 
			
		}
		return $thAmount;
	}
	
	function buildTrans() // method to build the transaction array...burrito
	{
		$isrebill = $this->isrebill;
		if(!$isrebill)
			$md =& $this->merchantdetails;			
		if($this->isrebill)
			if($this->row['subscriptionTable']['ss_ID'] == "" || !$this->row['subscriptionTable']['ss_ID'] || is_null($this->row['subscriptionTable']['ss_ID']))
				return FALSE; // no subscription found so can't rebill...burrito

		$transInfo['validupto'] = ($isrebill ? date("Y/m",strtotime($this->row['subscriptionTable']['ss_billing_exp'])) : $md['yyyy']."/".($md['mm']>9?$md['mm']:'0'.$md['mm']));

		$transInfo['name'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_firstname'] : $md['firstname']);
		$transInfo['surname'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_lastname'] : $md['lastname']);
		$transInfo['address'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_address'] : $md['address']);
		$transInfo['city'] =  ($isrebill ? $this->row['subscriptionTable']['ss_billing_city'] : $md['city']);
		$transInfo['state'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_state'] : $md['state']);
		$transInfo['country'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_country'] : $md['country']);
		$transInfo['zipcode'] = ($isrebill ? $this->row['subscriptionTable']['ss_billing_zipcode'] : $md['zipcode']);
		$transInfo['phonenumber'] = ($isrebill ? $this->row['subscriptionTable']['ss_cust_phone'] : $md['telephone']);
		$transInfo['checkorcard'] = ($isrebill ? ($this->row['subscriptionTable']['ss_billing_type'] == "Check" ? "C" : "H") : ($md['cardtype'] == "check" ? "C" : "H"));
		$transInfo['bank_id'] = ($isrebill ? $this->row['transactionTable']['bank_id'] : $md['bank_id']);
		$transInfo['bankname'] = ($isrebill ? $this->row['transactionTable']['bankname'] : $md['bankname']);
		$transInfo['td_bank_number'] = ($isrebill ? $this->row['transactionTable']['td_bank_number'] : $md['td_bank_number']);
		$transInfo['amount'] = $this->amount;				
		$transInfo['email'] = ($isrebill ? $this->row['subscriptionTable']['ss_cust_email'] : $md['email']);		
		$transInfo['userId'] =($isrebill ? $this->row['transactionTable']['userId'] : $this->userid);		
		$transInfo['en_ID'] =($isrebill ? $this->row['entityTable']['en_ID'] : $this->en_ID);

		$transInfo['CCnumber'] = ($isrebill ? $this->etelDecSalted($this->row['subscriptionTable']['ss_billing_card'],$this->row['subscriptionTable']['ss_salt']) : $md['number']);
		$transInfo['cvv'] = ($isrebill ? $this->etelDecSalted($this->row['subscriptionTable']['ss_billing_cvv2'],$this->row['subscriptionTable']['ss_salt']) : $md['cvv2']);			
		$transInfo['bankroutingcode'] = ($isrebill ? $this->etelDecSalted($this->row['subscriptionTable']['ss_billing_check_routing'],$this->row['subscriptionTable']['ss_salt']) : $md['routing']);
		$transInfo['bankaccountnumber'] = ($isrebill ? $this->etelDecSalted($this->row['subscriptionTable']['ss_billing_check_account'],$this->row['subscriptionTable']['ss_salt']) : $md['account']);

		if(!is_numeric($transInfo['CCnumber']))
			$transInfo['CCnumber'] = ($isrebill ? etelDec($this->row['transactionTable']['CCnumber']) : $md['number']);
		
		if(!is_numeric($transInfo['cvv']))
			$transInfo['cvv'] = ($isrebill ? $this->row['transactionTable']['cvv'] : $md['cvv2']);			

		if(!is_numeric($transInfo['bankroutingcode']))
			$transInfo['bankroutingcode'] = ($isrebill ? etelDec($this->row['transactionTable']['bankroutingcode']) : $md['routing']);

		if(!is_numeric($transInfo['bankaccountnumber']))
			$transInfo['bankaccountnumber'] = ($isrebill ? etelDec($this->row['transactionTable']['bankaccountnumber']) : $md['account']);

		if($transInfo['amount']<2)	
		{		
			toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - Charge amount is too low: '.print_r($transInfo,true).print_r($this,true));
			return false;
		}
		
		if($transInfo['CCnumber'] && !is_numeric($transInfo['CCnumber']) && $isrebill)
			toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - CCnumber is non-numeric: '.$transInfo['CCnumber']." - Potential Fix:".etelDec($transInfo['CCnumber']));
					
		if($transInfo['cvv'] && !is_numeric($transInfo['cvv']) && $isrebill)
			toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - cvv is non-numeric: '.$transInfo['cvv']." - Potential Fix:".etelDec($transInfo['cvv']));

		if($transInfo['bankroutingcode'] && !is_numeric($transInfo['bankroutingcode']) && $isrebill)
			toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - bankroutingcode is non-numeric: '.$transInfo['bankroutingcode']." - Potential Fix:".etelDec($transInfo['bankroutingcode']));

		if($transInfo['bankaccountnumber'] && !is_numeric($transInfo['bankaccountnumber']) && $isrebill)
			toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - bankaccountnumber is non-numeric: '.$transInfo['bankaccountnumber']." - Potential Fix:".etelDec($transInfo['bankaccountnumber']));

		$transInfo['transactionId']='';
		$transInfo['ostate']="";
		$transInfo['accounttype']='';
		$transInfo['cancelstatus']='N';
		$transInfo['status']='';
		$transInfo['cardtype']=($isrebill ? strtolower($this->row['subscriptionTable']['ss_billing_type']) : strtolower($md['cardtype']));
		$transInfo['ipaddress']=($isrebill ? $this->row['subscriptionTable']['ss_billing_last_ip'] : $md['ipaddress']);
		$transInfo['productdescription']=($isrebill ? $this->row['subscriptionTable']['ss_productdescription'] : $md['mt_prod_desc']);
		$transInfo['reference_number']='';
		$transInfo['currencytype']="USD";
		$transInfo['cancel_refer_num']='';
		$transInfo['return_url']=($isrebill ? $this->row['transactionTable']['return_url'] : $this->row['websiteTable']['cs_return_page']);
		$transInfo['from_url']=($isrebill ? $this->row['transactionTable']['from_url'] : $md['from_url']);
		$transInfo['bank_id']=($isrebill ? $this->row['transactionTable']['bank_id'] : $this->row['transactionTable']['bank_id']);
		$transInfo['td_rebillingID']=($isrebill ? $this->row['transactionTable']['td_rebillingID'] : $this->row['rebillingTable']['rd_subaccount']);
		$transInfo['td_is_a_rebill']=($isrebill ? 1 : 0);
		$transInfo['td_enable_rebill']=($this->row['rebillingTable']['recur_charge'] > 0 ? 1 : 0);
		$transInfo['td_recur_charge'] = $this->row['rebillingTable']['recur_charge'];
		$transInfo['td_voided_check']='0';
		$transInfo['td_returned_checks']='0';
		$transInfo['td_site_ID']=($isrebill ? $this->row['transactionTable']['td_site_ID'] : $this->row['websiteTable']['cs_ID']); 
		$transInfo['payment_schedule']=$this->rebill['schedule'];
		$transInfo['nextDateInfo']=($isrebill ? $this->rebill['td_recur_next_date'] : $this->rebill['td_recur_next_date']);
		$transInfo['td_one_time_subscription']='';
		$transInfo['billing_descriptor'] = '';

		$transInfo['td_merchant_fields'] =$this->td_merchant_fields = ($isrebill ? $this->row['transactionTable']['td_merchant_fields'] : $this->merchantdetails['mt_posted_variables']);
		$transInfo['td_is_affiliate']='0';
		$transInfo['td_is_pending_check']='0';
		$transInfo['td_is_chargeback']='0';
		$transInfo['td_recur_processed']='0';
		$transInfo['td_recur_next_date']=($this->rebill['td_recur_next_date']);
		
		$transInfo['ss_account_status']=$this->rebill['ss_account_status'];
		$transInfo['ss_account_expire_date']=$this->rebill['ss_account_expire_date'];
		$transInfo['ss_rebill_status']=$this->rebill['ss_rebill_status'];
		$transInfo['ss_rebill_status_text']=$this->rebill['ss_rebill_status_text'];
		$transInfo['ss_rebill_amount']=$this->rebill['ss_rebill_amount'];
							
		$transInfo['td_username']=($isrebill ? $this->row['subscriptionTable']['ss_cust_username'] : $md['td_username']);
		$transInfo['td_password']=($isrebill ? $this->row['subscriptionTable']['ss_cust_password'] : $md['td_password']);
		$transInfo['td_product_id']=($isrebill ? $this->row['transactionTable']['td_product_id'] : $md['mt_product_id']);
		$transInfo['td_customer_fee']=($isrebill ? $this->row['transactionTable']['td_customer_fee'] : $this->customerfee);
		$transInfo['td_ss_ID']=($isrebill ? $this->row['subscriptionTable']['ss_ID'] : 0);
		$transInfo['td_subscription_id']=($isrebill ? $this->row['subscriptionTable']['ss_subscription_ID'] : 0);
		$transInfo['td_cross_sale'] = ($isrebill ? FALSE : $this->transid);

		$transInfo['additional_funds'] = $md['additional_funds'];
		$transInfo['wallet_id'] = $md['wallet_id'];
		$transInfo['wallet_pass'] = $md['wallet_pass'];

		$transInfo['transactionDate'] = date("Y-m-d H:i:s",time());
		$transInfo['billingDate'] = date("Y-m-d",time());
		
		if ($isrebill && !is_numeric($transInfo['bankroutingcode'])) $transInfo['bankroutingcode'] = etelDec($transInfo['bankroutingcode']);
		if ($isrebill && !is_numeric($transInfo['bankaccountnumber'])) $transInfo['bankaccountnumber'] = etelDec($transInfo['bankaccountnumber']);
		if ($isrebill && !is_numeric($transInfo['CCnumber'])) $transInfo['CCnumber'] = etelDec($transInfo['CCnumber']);

		return $transInfo;
	}
	
	function processTransaction($add_fields=NULL) // method to process a transaction that is a new order...burrito
	{
		if($this->transphase != 1)
			return FALSE;
		else
		{
			$islive = ($this->test ? "Test" : "Live");
			$this->transArr = $this->buildTrans(); // grab transaction array...burrito
			if($add_fields != NULL && is_array($add_fields))
				foreach($add_fields as $field => $value)
					$this->transArr[$field] = $value;
				
			$response = execute_transaction(&$this->transArr,$islive); // process transaction...burrito

			$this->row['transactionTable']['reference_number'] = $response['reference_number'];
			if($response['status'] == 'A' || ($response['status'] == 'P' && $this->transArr['checkorcard'] == 'C'))
				$result = 1;
			else
			{
				$this->errormsg = $response['errormsg'];
				$result = 0;
			}
			$response['result']=$result;
			return $response;
		}
	}
	
	function getRebillInfo($is_trial=FALSE)
	{	
		if($this->rebill && (!$this->row || $this->row['subscriptionTable']['ss_rebill_ID'] == ''))
			return FALSE;
		else
		{
			$rebillArray = array();
			$rebillArray['subAccountName'] = $this->row['rebillingTable']['rd_subName'];
			$this->formatted['schedule'] = $rebillArray['schedule'] = "";
			$rebillArray['chargeAmount']=0;				
			$rebillArray['td_enable_rebill']=0;
			$rebillArray['td_one_time_subscription']=0;		
			
			
			$rebillArray['ss_account_status'] = 'inactive';
			$rebillArray['ss_account_notes'] = date("Y-m-d G:i:s").': New Subscription Initiated';
			$rebillArray['ss_account_expire_date'] = date("Y-m-d G:i:s");
			$rebillArray['ss_rebill_status'] = 'inactive';
			$rebillArray['ss_rebill_status_text'] = 'Invalid Status';
			$rebillArray['ss_rebill_amount'] = '0.00';
			$rebillArray['ss_rebill_next_date'] = '';
			
			if (!$this->row['rebillingTable']['rd_initial_amount'] || !$is_trial) 
			{
				if($this->row['rebillingTable']['recur_day']>1 && $this->row['rebillingTable']['recur_charge'])
				{
					$rebillArray['td_enable_rebill']=1;		
					$nextRecurDate=time()+60*60*24*$this->row['rebillingTable']['recur_day'];
					$nextDateInfo = date("F j, Y",$nextRecurDate)." for $".formatMoney($this->row['rebillingTable']['recur_charge']);
					$this->formatted['schedule'] = $rebillArray['schedule'] = "Recurring Payments of $".formatMoney($this->row['rebillingTable']['recur_charge'])." once every ".$this->row['rebillingTable']['recur_day']." day(s)";
					$rebillArray['chargeAmount']=$this->row['rebillingTable']['recur_charge'];
					
					$rebillArray['ss_account_status'] = 'active';
					$rebillArray['ss_account_expire_date'] = date("Y-m-d G:i:s",time()+60*60*24*($this->row['rebillingTable']['recur_day']+1));
					$rebillArray['ss_rebill_status'] = 'active';
					$rebillArray['ss_rebill_status_text'] = 'Subscription Enabled';
					$rebillArray['ss_rebill_amount'] = $this->row['rebillingTable']['recur_charge'];
					$rebillArray['ss_rebill_next_date'] = date('Y-m-d G:i:s',time()+60*60*24*($this->row['rebillingTable']['recur_day']));
					
				}
				else
				{
					$rebillArray['td_enable_rebill']=-1;
					$this->formatted['schedule'] = $rebillArray['schedule'] = "No Payment Schedule";
					$nextDateInfo = "No Recuring Payments";
					$nextRecurDate=-1;
					$rebillArray['rd_subName'] = "One Time Payment";
					$rebillArray['chargeAmount']=$this->row['rebillingTable']['rd_initial_amount'];
					//if(!$rebillArray['chargeAmount']) $rebillArray['chargeAmount']=$this->row['rebillingTable']['recur_charge'];
					
					if($this->row['rebillingTable']['recur_day']>0)
					{
						$rebillArray['ss_account_status'] = 'active';
						$rebillArray['ss_account_expire_date'] = date("Y-m-d G:i:s",time()+60*60*24*($this->row['rebillingTable']['recur_day']));
					}
					$rebillArray['ss_rebill_status'] = 'inactive';
					$rebillArray['ss_rebill_status_text'] = 'Subscription Disabled (One Time Payment)';
					$rebillArray['ss_rebill_amount'] = '0.00';
				}
			}
			else 
			{
				$nextRecurDate=time()+60*60*24*$this->row['rebillingTable']['rd_trial_days'];
				$this->formatted['schedule'] = $rebillArray['schedule'] = "One Time Charge of $".formatMoney($this->row['rebillingTable']['rd_initial_amount']);
				$rebillArray['chargeAmount']=$this->row['rebillingTable']['rd_initial_amount'];
				if($this->row['rebillingTable']['recur_day'] && $this->row['rebillingTable']['recur_charge'])
				{
					$rebillArray['td_enable_rebill']=1;		
					$nextDateInfo = date("F j, Y",$nextRecurDate)." for $".formatMoney($this->row['rebillingTable']['recur_charge']);
					$this->formatted['schedule'] = $rebillArray['schedule'] .= " and then every (".$this->row['rebillingTable']['recur_day'].") days for $".formatMoney($this->row['rebillingTable']['recur_charge']);
					
					
					$rebillArray['ss_rebill_status'] = 'active';
					$rebillArray['ss_rebill_status_text'] = 'Subscription Enabled';
					$rebillArray['ss_rebill_amount'] = $this->row['rebillingTable']['recur_charge'];	
					$rebillArray['ss_account_status'] = 'active';
					$rebillArray['ss_account_expire_date'] = date("Y-m-d G:i:s",time()+60*60*24*($this->row['rebillingTable']['rd_trial_days']+1));
					$rebillArray['ss_rebill_next_date'] = date('Y-m-d G:i:s',time()+60*60*24*($this->row['rebillingTable']['rd_trial_days']));
				}
				else
				{
					$rebillArray['td_enable_rebill']=0;	
					$rebillArray['td_one_time_subscription']=1;		
					
					$rebillArray['ss_rebill_status'] = 'inactive';
					$rebillArray['ss_rebill_status_text'] = 'Subscription Disabled (One Time Payment)';
					$rebillArray['ss_rebill_amount'] = '0.00';	
					if($this->row['rebillingTable']['rd_trial_days'])
					{					
						$rebillArray['ss_account_status'] = 'active';
						$rebillArray['ss_account_expire_date'] = date("Y-m-d G:i:s",time()+60*60*24*($this->row['rebillingTable']['rd_trial_days']));
					}	
				}
				
				
				
			}
			if($nextRecurDate != -1) 
				$rebillArray['td_recur_next_date']=date("Y-m-d",$nextRecurDate);
			$rebillArray['nextDateInfo']=$nextDateInfo;
			$rebillArray['nextRecurDate']=$nextRecurDate;
			if($rebillArray['chargeAmount']<1)
			{
				//toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - Rebill: '.(!$is_trial).' - Charge amount is too low: '.print_r($rebillArray,true).print_r($this->row['rebillingTable'],true));
				return false;
			}
			//toLog('erroralert','system',$this->row['subscriptionTable']['ss_ID']. ' - Rebill: '.(!$is_trial).' - Info: '.print_r($rebillArray,true).print_r($this->row['rebillingTable'],true));

			return $rebillArray;
		}
	}
	
	
	function SanitizeNumber($num,$lastdigits = 5)
	{
		if(!$num) return '';
		$len = strlen($num);
		if($len<=$lastdigits) return '';
		return str_repeat('*',$len-$lastdigits).substr($num,$len-$lastdigits,$lastdigits);
	}
	
	function etelEncSalted($string,$salt='') 
	{
		if(!$salt) $salt = $this->row['subscriptionTable']['ss_salt'];
		$intkey = $salt . md5("23l4k23jsjd0f9=");  // I made this change on purpose. Its much harder to figure out a double seed.
		$key = md5($intkey);
		$result = '';
		for($i=0; $i<strlen($string); $i++) 
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}

		return base64_encode($result);
	}

	function etelDecSalted($string,$salt='') 
	{
		if(!$salt) $salt = $this->row['subscriptionTable']['ss_salt'];
		$intkey = $salt . md5("23l4k23jsjd0f9=");  // I made this change on purpose. Its much harder to figure out a double seed.
		$key = md5($intkey);
		$result = '';
		$string = base64_decode($string);
		
		for($i=0; $i<strlen($string); $i++) 
		{
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		
		return $result;
	}
	
	function process_cancel_request($info = array())
	{
		if($this->row['subscriptionTable']['ss_rebill_status']=='inactive')
			return false;
		if(!$this->row['subscriptionTable'])
			return false;
		$notes = '';
		if($info['notes']) $notes = "\nNotes: ".$info['notes'];
		if(!$info['actor']) $info['actor'] = 'System';
		
		if($info['verifyuserId'] && $info['verifyuserId']!=$this->row['subscriptionTable']['ss_user_ID'])
		{
			toLog('erroralert','system','UserId Verification Failed! '.print_r($info,true).print_r($this->row['subscriptionTable'],true));
			return false;
		}
		$ref_no = func_Trans_Ref_No($values['append']);
		$transTable = ($this->test ? "cs_test_transactiondetails" : "cs_transactiondetails");
		
		$sql="UPDATE cs_subscription as ss left join $transTable as td on td.td_ss_ID = ss.ss_ID 
		SET `td_enable_rebill` = '0', 
			cancel_refer_num = '$ref_no',
			ss_cancel_id = '$ref_no',
			ss_rebill_status = 'inactive',
			ss_rebill_status_text = 'Subscription Cancelled by ".$info['actor']."',
			ss_account_notes = CONCAT(ss_account_notes, '\n\n', NOW(), ': Rebill Cancelled (Ref: $ref_no) . Subscription will not Rebill Again.$notes')
		
		WHERE `ss_ID` = '" . $this->row['subscriptionTable']['ss_ID'] . "' AND ss_user_ID = '" . $this->row['subscriptionTable']['ss_user_ID'] . "'";
		$result=sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query<br><b>$sql</b>");
		$this->row = $this->getRow(); // Update Status

		if(mysql_affected_rows())
		{	

			$data['site_URL'] = $this->row['websiteTable']['cs_name'];
			$data['reference_number'] = $this->row['transactionTable']['reference_number'];
			$data['subscription_ID'] = $this->row['subscriptionTable']['ss_subscription_ID'];
			$data['full_name'] = 	$this->row['subscriptionTable']['ss_billing_firstname']." ".
									$this->row['subscriptionTable']['ss_billing_mi']." ".
									$this->row['subscriptionTable']['ss_billing_lastname'];
			$data['email'] = $this->row['subscriptionTable']['ss_cust_email'];
			$data['cancel_reference_number'] = $ref_no;
			$data["gateway_select"] = $this->row['websiteTable']['cs_gatewayId'];
			
			send_email_template('customer_cancel_confirmation',$data);
			if($transInfo['cd_recieve_order_confirmations'])
			{	
				$data['email'] = $transInfo['cd_recieve_order_confirmations'];
				send_email_template('customer_cancel_confirmation',$data,$str_is_test."(Merchant Copy) ");
			}
			return array('success'=>true,'ss_cancel_id'=>$ref_no);
		}
		return false;
	
	}	
	
	function process_subscription_restart($info = array())
	{
		if($this->row['subscriptionTable']['ss_rebill_status']=='active')
			return false;
		
		if(!$info['actor']) $info['actor'] = 'System';
		$transTable = ($this->test ? "cs_test_transactiondetails" : "cs_transactiondetails");
		
		$sql="UPDATE $transTable as td left join cs_subscription as ss on td.td_ss_ID = ss.ss_ID 
		SET `td_enable_rebill` = '1', 
			cancel_refer_num = '',
			ss_rebill_status = 'active',
			ss_account_status = 'active',
			ss_rebill_status_text = 'Subscription Restarted by ".$info['actor']."',
			ss_account_notes = CONCAT(ss_account_notes, '\n\n', NOW(), ': Subscription Restarted by ".$info['actor'].". Subscription is now active.')
		
		WHERE `transactionId` = '" . $this->row['transactionTable']['transactionId'] . "' AND userId = '" . $this->row['transactionTable']['userId'] . "' 
		";
		$result=sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query<br><b>$sql</b>");
		$aff_rows = mysql_affected_rows();
		if($aff_rows)
			return true;
			
		return false;
	
	}
	
	function process_refund_request($info = array())
	{
		$error_msg = "Refund Request Created Successfully";
		if(!$info['actor']) $info['actor'] = 'System';
		$service_notes=$info['actor']." Refund Requested";
		$customer_notes=$info['notes'];
		if($info['verifyuserId']) $userSql = " and userID = '".$info['verifyuserId']."'";
		
		$transID = $this->row['transactionTable']['transactionId'];
		$sql="SELECT td.`transactionId`, td.`reference_number`, cd.`companyname`,cd.`username`,
					cd.`password`,cd.`ReferenceNumber`, cd.`email`, td.`email` as customer_email, `note_id`,cs_URL, name, surname
				
				FROM `cs_transactiondetails` AS td
				LEFT JOIN `cs_callnotes` AS cn ON cn.`transaction_id` = td.`transactionId` AND cn.cn_type = 'refundrequest'
				LEFT JOIN `cs_companydetails` AS cd ON td.`userId` = cd.`userId`  
				LEFT JOIN `cs_company_sites` AS cs ON td.td_site_ID = cs.cs_ID 
				Where  `transactionId` = '$transID' $userSql";
		$result=sql_query_read($sql);
		if (mysql_num_rows($result)==0) return "Error: Transaction $transID Not Found";
		$statusInfo = mysql_fetch_assoc($result);
		if(!$statusInfo['note_id'])
		{
			$this->process_cancel_request($info);
			$sql="REPLACE INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type` )
			VALUES ( '$transID', NOW() , '$service_notes', '', '$customer_notes', '' , '', '', '', '', '', 'refundrequest');";
			$qry_callnotes = sql_query_write($sql) or dieLog("Cannot execute query ");
			
			$data['companyname'] = $statusInfo['companyname'];
			$data['Reference_ID'] = $statusInfo['ReferenceNumber'];
			$data['reference_number'] = $statusInfo['reference_number'];
			$data['username'] = $statusInfo['username'];
			$data['password'] = $statusInfo['password'];
			$data['cancel_reference_number'] = $statusInfo['ReferenceNumber'];
			$data['email'] = $statusInfo['email'];
			$data['reason'] = $service_notes.": ".$customer_notes;
			$data["gateway_select"] = $statusInfo['gateway_id'];
			$data['site_URL'] = $statusInfo['cs_URL'];
			$data['full_name'] = $statusInfo['name']." ".$statusInfo['surname'];
			send_email_template('merchant_refund_request_notification_email',$data);
			
			
			$data['email'] = $statusInfo['customer_email'];
			send_email_template('customer_refund_request_notification',$data);
			return array('success'=>true,'status'=>"Refund Request Created Successfully");
		}
		else $error_msg = "Refund Request Already Exists";
		return array('success'=>false,'status'=>"Refund Request Already Exists");
	}
	
	function setActive() // method to flag a subscription as processing while processing...burrito
	{
		$update	=	"UPDATE
					cs_subscription
					SET
					ss_rebill_status = 'processing'
					WHERE
					ss_ID = {$this->row['subscriptionTable']['ss_ID']}";
		sql_query_write($update)
			or dieLog(mysql_error()."~~$update");
	}
	
	function process_successful_rebill($response) //method to update subscription status and log rebill transaction...burrito
	{
		$upd	=	"UPDATE
					cs_subscription
					left join cs_rebillingdetails on ss_rebill_id = rd_subaccount 
					SET
					ss_last_rebill = NOW(),
					ss_rebill_count = ss_rebill_count + 1,
					ss_rebill_status = 'active',
					ss_transaction_id = '{$response['transactionId']}',
					ss_rebill_next_date = date_add(now(), interval recur_day day),
					ss_account_status = 'active',
					ss_account_expire_date = date_add(now(), interval recur_day+1 day),
					`ss_account_notes` = CONCAT(`ss_account_notes`, '\n\n', NOW(), ': Rebill Approved (Attempt ',ss_rebill_attempts+1,'). Next Rebill Date: ',date_add(now(), interval recur_day day),'.'),
					ss_rebill_attempts = 0
					WHERE
					ss_ID = '{$this->row['subscriptionTable']['ss_ID']}'";
		sql_query_write($upd) or dieLog(mysql_error() . "<pre>$upd</pre>");
		if(!mysql_affected_rows()) dieLog("ERROR: Subscription did not update!! $upd");
		Process_Transaction($response['transactionId'],"rebill",$this->test,"transactionId");
	}
	
	function process_failed_rebill($response = array()) //method to update subscription status and log rebill transaction...burrito
	{
		$status = $response['td_process_msg'];
		$daystowait = intval($response['watchInfo']['ss_rebill_delay']);
		if($daystowait<2) $daystowait = 3;
		$rebillError = "Error encountered while rebilling for:
						{$this->row['subscriptionTable']['ss_billing_firstname']} {$this->row['subscriptionTable']['ss_billing_lastname']} \r\n
						With subscription id of: {$this->row['subscriptionTable']['ss_ID']}\r\n
						This error occured on rebill attempt #{$this->row['subscriptionTable']['ss_rebill_attempts']}";
		toLog('rebill','customer', $rebillError);
		$status = quote_smart($status);
//		echo $this->row['subscriptionTable']['ss_ID'] . " - " . $this->row['subscriptionTable']['ss_rebill_attempts'] . "<br>" ;
		$ss_rebill_attempts = intval($this->row['subscriptionTable']['ss_rebill_attempts']);
		if($ss_rebill_attempts < 3)
		{
			$upd	=	"
					UPDATE
						cs_subscription
					SET
						ss_rebill_status = 'active',
						ss_rebill_attempts = ss_rebill_attempts + 1,
						ss_rebill_status_text = 'Attempt #".($ss_rebill_attempts+1)." Failed ($status)',
						ss_rebill_next_date = adddate( now( ) , INTERVAL $daystowait DAY ),
						ss_account_notes = CONCAT(ss_account_notes, '\n\n', NOW(), ': Rebill Declined ($status) (Attempt ',ss_rebill_attempts,'). Will attempt Rebilling again in $daystowait days.')
					WHERE
						ss_ID = '{$this->row['subscriptionTable']['ss_ID']}'
					";
		}
		else // give up
		{
			$upd	=	"
					UPDATE
						cs_subscription
					SET
						ss_rebill_status = 'inactive',
						ss_rebill_status_text = 'Inactive after 3 failed rebill attempts',
						ss_account_expire_date = NOW(),
						ss_rebill_attempts = 3,
						ss_rebill_next_date = adddate( now( ) , INTERVAL $daystowait DAY ),
						`ss_account_notes` = CONCAT(`ss_account_notes`, '\n\n', NOW(), ': Rebill Declined ($status) (Attempt ',ss_rebill_attempts,') . Will not attempt to rebill again.')
					WHERE
						ss_ID = '{$this->row['subscriptionTable']['ss_ID']}'
					";
		}
		sql_query_write($upd) or dieLog(mysql_error() . "<pre>$upd</pre>");
		if(!mysql_affected_rows()) dieLog("ERROR: Subscription did not update!! $upd");
	}
	
	function process_refund_rebill()
	{
		$upd	=	"UPDATE
					cs_subscription
					SET
					ss_rebill_status = 'inactive',
					ss_account_status = 'inactive',
					ss_rebill_attempts = 0
					WHERE
					ss_ID = {$this->row['subscriptionTable']['ss_ID']}";
		sql_query_write($upd) or dieLog(mysql_error() . "<pre>$upd</pre>");
		return Process_Transaction($this->row['subscriptionTable']['ss_transaction_id'],"refund",$this->test,"transactionId");
	}

	function process_expired_rebill()
	{ // Depreciated
		$upd	=	"UPDATE
					cs_subscription
					SET
					ss_account_status = 'inactive',
					ss_account_notes = CONCAT(`ss_account_notes`, '\n\n', NOW(), ': Account Inactive' )
					WHERE
					ss_ID = {$this->row['subscriptionTable']['ss_ID']}";
		sql_query_write($upd) or dieLog(mysql_error() . "<pre>$upd</pre>");
		return Process_Transaction($this->row['subscriptionTable']['ss_transaction_id'],"expiration",$this->test,"transactionId");
	}

	function update_account_status()
	{
		$ss_account_status = 'active';
		if(strtotime($this->row['subscriptionTable']['ss_account_expire_date'])<time() )
			$ss_account_status = 'inactive';
			
		$ss_account_notes = "\n\n".date('Y-m-d G:i:s').": Account ".ucfirst($ss_account_status);
		
		$upd	=	"UPDATE
					cs_subscription
					SET
					ss_account_status = '$ss_account_status',
					ss_account_notes = CONCAT(`ss_account_notes`, '$ss_account_notes' )
					WHERE
					ss_ID = {$this->row['subscriptionTable']['ss_ID']}";
		sql_query_write($upd) or dieLog(mysql_error() . "<pre>$upd</pre>");
		
		$this->row['subscriptionTable']['ss_account_status'] = $ss_account_status;
		$this->row['subscriptionTable']['ss_account_notes'] .= $ss_account_notes;
		
		if($ss_account_status == 'inactive')
			return Process_Transaction($this->row['subscriptionTable']['ss_transaction_id'],"expiration",$this->test,"transactionId");
		return Process_Transaction($this->row['subscriptionTable']['ss_transaction_id'],"approve",$this->test,"transactionId");
	}
			
	function get_next_rebill($settings = NULL)
	{
		$sql_where = "";
		if($settings['bank_limit']) $sql_where .= " AND ss_bank_id in (".implode(', ',$settings['bank_limit']).")";
		if($settings['bank_exclude']) $sql_where .= " AND ss_bank_id not in (".implode(', ',$settings['bank_exclude']).")";
		if($settings['bank_where']) $sql_where .= " AND ".$settings['bank_where'];
		$sql = "
				SELECT 
					SQL_CALC_FOUND_ROWS
					ss_ID,
					ss_rebill_status,
					ss_rebill_attempts
				FROM
					cs_subscription AS s
					left join cs_companydetails as cd on userId = ss_user_ID
					left join cs_company_sites as cs on cs_ID = ss_site_ID
					left join cs_rebillingdetails on ss_rebill_id = rd_subaccount 
				WHERE
					s.ss_rebill_next_date <= NOW()
					AND	s.ss_rebill_status = 'active'
					AND ss_rebill_frozen = 'no'
					AND activeuser = '1'
					AND rd_enabled = 'Yes'
					AND cs_verified in ('approved','non-compliant')
					$sql_where
				ORDER BY
					ss_rebill_attempts asc,
					ss_rebill_next_date asc
				LIMIT
					1 
				";
				//	AND ( ss_bank_id in (18,30,22,28,29,33,32))
				
				// Inefficient
				//ORDER BY 
				//	ss_rebill_attempts ASC,
				//	ss_ID ASC
				
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		if(!$res) return 0;
		$row = mysql_fetch_assoc($res);
		
		$sql = "select FOUND_ROWS()";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$row['num_rows'] = mysql_result($result,0,0);
		
		return $row;
	}			
	
	function get_next_expired_rebill()
	{
		$sql = "	
				SELECT 
					ss_ID
				FROM
					cs_subscription AS ss
				WHERE
					ss.ss_account_expire_date <= NOW()
					AND ss.ss_rebill_frozen = 'no'
					AND	ss.ss_account_status = 'active'
					AND	ss.ss_rebill_status = 'inactive'
				ORDER BY 
					ss_ID DESC
				LIMIT
					1
				";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		if(!$res) return 0;
		$row = mysql_fetch_assoc($res);
		return $row['ss_ID'];
	}
	
	function processRebill()
	{
		$error = NULL;
		if(!$this->row)
			$error = array("status" => "Transaction not found");
			
		if($this->row['subscriptionTable']['ss_ID'] == "")
			$error = array("status" => "Subscription not found");

		if(!$this->row['subscriptionTable']['ss_ID'])
			$error = array("status" => "Subscription not found");

		if(is_null($this->row['subscriptionTable']['ss_ID']))
			$error = array("status" => "Subscription not found");
			
		if(is_null($this->row['rebillingTable']['rd_subaccount']))
			$error = array("status" => "Invalid Price Point");
			
		if($this->row['rebillingTable']['recur_day']<2)
			$error = array("status" => "No Recur Period Set");			

		if(strtotime($this->row['subscriptionTable']['ss_rebill_next_date']) > time())
			$error = array("status" => "Subscription does not Rebill yet");
		
		if($error)
		{
			$this->process_failed_rebill($error['status']);
			return $error;
		}
		
		$islive = "Live";
		$transArr = $this->buildTrans();
		$this->setActive();
		
		$supported_banks = bank_ChooseSupported($this->row['subscriptionTable']['ss_billing_type'],$transArr['en_ID'],$transArr['bank_id']);
		$transArr['bank_id'] = $supported_banks['chosen'];
		$processor = new processor_class('Live');
		$response = $processor->process_transaction($transArr);
		
		//$response = execute_transaction(&$transArr,$islive);
		
		if($response['status'] == 'A' || $response['status'] == 'P')
			$this->process_successful_rebill($response);
		else
			$this->process_failed_rebill($response);
			
		$this->row = $this->getRow(); // Update Status
		
		if(strtotime($this->row['subscriptionTable']['ss_rebill_next_date'])<time()+24*60*60) 
			dieLog("ERROR: subscription to rebill in less than a day!! ".$this->row['subscriptionTable']['ss_rebill_next_date'].print_r($this,true));
		return $response;		
	}	
	
	function send_email($useEmailTemplate, $additional_data = NULL)
	{
	
		$email_to = $this->row['subscriptionTable']['ss_cust_email'];
		if(!$email_to) $email_to = $this->row['transactionTable']['email'];
		//$email_to = 'ari@etelegate.com';
		//$useEmailTemplate = "customer_recur_subscription_confirmation_cc";
		//$useEmailTemplate = "customer_order_confirmation_cc";
		//if($this->transInfo['td_one_time_subscription']) $useEmailTemplate = "customer_subscription_confirmation_cc";
		//if($this->transInfo['td_is_a_rebill'] == 1) $useEmailTemplate = "customer_rebill_confirmation_cc";
		$data = array();
		$data['payment_type'] = $this->row['transactionTable']['cardtype'];;
		$data['billing_descriptor'] = $this->row['transactionTable']['billing_descriptor'];
		$data['site_URL'] = $this->row['websiteTable']['cs_URL'];
		$data['site_name'] = $this->row['websiteTable']['cs_name'];
		$data['reference_number'] = $this->row['transactionTable']['reference_number'];
		$data['subscription_id'] = $this->row['subscriptionTable']['ss_subscription_ID'];
		$data['full_name'] = $this->row['transactionTable']['surname'].", ".$this->row['transactionTable']['name'];
		$data['first_name'] = $this->row['transactionTable']['name'];
		$pInfo = $this->row['transactionTable']['td_product_id'];
		$data['product_info'] = $pInfo.($pInfo?": ":''). $this->row['transactionTable']['productdescription'];
		$data['email'] = $email_to;
		$data['customer_email'] = $email_to;
		$data['credit_card_formatted'] = $this->row['Custom']['CreditCardFormatted'];
		if(!$data['credit_card_formatted']) $data['credit_card_formatted'] = $this->row['Custom']['CheckAccountFormatted'];
		$data['amount'] = "$".formatMoney($this->row['transactionTable']['amount']-$this->row['transactionTable']['td_customer_fee'])." USD";
		$data['customer_fee'] = "$".formatMoney($this->row['transactionTable']['td_customer_fee'])." USD";
		$data['final_amount'] = "$".formatMoney($this->row['transactionTable']['amount'])." USD";
		$data['username'] = $this->row['transactionTable']['td_username'];
		$data['password'] = $this->row['transactionTable']['td_password'];
		$data['payment_schedule'] = $this->rebill['schedule'];
		if(!$data['payment_schedule']) $data['payment_schedule'] = 'No Schedule';
		$data['transaction_date'] = date("F j, Y G:i:s",strtotime($this->row['transactionTable']['transactionDate']));
		$data['next_bill_date'] = $this->rebill['nextDateInfo'];
		$data['site_access_URL'] = $this->row['websiteTable']['cs_member_url'];
		$data['customer_support_email'] = $this->row['websiteTable']['cs_support_email'];
		$data['tmpl_language'] = $_SESSION['tmpl_language'];
		$data['gateway_select'] = $this->row['companydetailsTable']['gateway_id'];

		if($additional_data)
			foreach($additional_data as $key => $add)
				$data[$key] = $add;
		//$str_is_test = "THIS IS A TEST TRANSACTION ";
		//if($this->mode=="Live") $str_is_test = "";
		//print_r($data);
		send_email_template($useEmailTemplate,$data,$str_is_test); // Send Customer Email.
	}
}

?>