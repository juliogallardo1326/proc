<?php


function genRefId($type='transaction',$prefix = "T")
{
	switch($type)
	{
		case "subscription":
			if(!$prefix) $prefix = "S";
			$sql = "select ss_subscription_ID from cs_subscription where ss_subscription_ID = ";
			$length = 17;

			$prefix = substr($prefix,0,1);
			$found = 1;
			while($found)
			{
				$ref_number = substr(rand(0,pow(10,$length)),0,$length);
				$finalsql = $sql."'$ref_number'";
				$result = mysql_query($finalsql) or dieLog(mysql_error()." ~ $finalsql");
				if (mysql_num_rows($result)) $found = 1;
				else $found = 0;
			}
		break;
		case "transaction":
		default:
			if(!$prefix) $prefix = "T";
			$sql = "select reference_number from cs_transactiondetails where reference_number = ";
			$length = 14;

			$prefix = substr($prefix,0,1);
			$found = 1;
			while($found)
			{
				$ref_number = strtoupper($prefix.substr(md5(time()+rand(0,100)),0,$length));
				$finalsql = $sql."'$ref_number'";
				$result = mysql_query($finalsql) or dieLog(mysql_error()." ~ $finalsql");
				if (mysql_num_rows($result)) $found = 1;
				else $found = 0;
			}
		break;
	}
	return $ref_number;
}


function checkIsOverMonthlyMaximum($companyId,$maximum)
{
	$today = getdate();
	
 	$drange = "'".$today['year']."-".$today['mon']."-01 00:00:00' and '".$today['year']."-".$today['mon']."-".$today['mday']." 23:59:59'";	
	$daterange = "AND `transactionDate` between $drange";	
	$sql="SELECT SUM(`amount`) as amt FROM `cs_transactiondetails` WHERE `userId`='$companyId' AND `td_is_chargeback` = '0' AND `cancelstatus` = 'N' AND `status` = 'A' $daterange";		
	$rst_details=mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$NewSales = mysql_fetch_assoc($rst_details);
	if($NewSales['amt']>$maximum) return true;
	return false;
}

function getRebillInfo($rd_subaccount,$time,$is_trial)
{
	global $cnn_cs;
	$sql = "SELECT * FROM `cs_rebillingdetails` WHERE `rd_subaccount` = '$rd_subaccount'" ;
	$result = mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql<BR>");
	if (!mysql_num_rows($result)) return -1;
	$subAcc = mysql_fetch_assoc($result);
	$subAccountName = $subAcc['rd_subName'];
	$schedule = "";
	$subAcc['chargeAmount']=0;
		
	$subAcc['td_enable_rebill']=0;
	$subAcc['td_one_time_subscription']=0;		
	
	if (!$subAcc['rd_initial_amount'] || !$is_trial) 
	{
		if($subAcc['recur_day']>1 && $subAcc['recur_charge'])
		{
			$subAcc['td_enable_rebill']=1;		
			$nextRecurDate=time()+60*60*24*$subAcc['recur_day'];
			$nextDateInfo = date("F j, Y",$nextRecurDate)." for $".formatMoney($subAcc['recur_charge']);
			$schedule = "Recurring Payments of $".formatMoney($subAcc['recur_charge'])." once every ".$subAcc['recur_day']." day(s)";
			$subAcc['chargeAmount']=$subAcc['recur_charge'];
		}
		else
		{
			$subAcc['td_enable_rebill']=-1;
			$schedule = "No Payment Schedule";
			$nextDateInfo = "No Recuring Payments";
			$nextRecurDate=-1;
			$subAcc['rd_subName'] = "Independent Pricing";
		}
	}
	else 
	{
		$nextRecurDate=time()+60*60*24*$subAcc['rd_trial_days'];
		$schedule = "One Time Charge of $".formatMoney($subAcc['rd_initial_amount']);
		$subAcc['chargeAmount']=$subAcc['rd_initial_amount'];
		if($subAcc['recur_day'] && $subAcc['recur_charge'])
		{
			$subAcc['td_enable_rebill']=1;		
			$nextDateInfo = date("F j, Y",$nextRecurDate)." for $".formatMoney($subAcc['recur_charge']);
			$schedule .= ",\n and then every (".$subAcc['recur_day'].") days for $".formatMoney($subAcc['recur_charge']);
		}
		else
		{
			$subAcc['td_enable_rebill']=0;	
			$subAcc['td_one_time_subscription']=1;		
			
			$nextDateInfo = $schedule;
		}
		
	}
	if($nextRecurDate != -1) $subAcc['td_recur_next_date']=date("Y-m-d",$nextRecurDate);
	$subAcc['nextDateInfo']=$nextDateInfo;
	$subAcc['nextRecurDate']=$nextRecurDate;
	$subAcc['payment_schedule'] = $schedule;
	
	return $subAcc;
}

function getTransactionInfo($id,$test = false,$by = 'transactionId', $where_sql="")
{

	global $cnn_cs;
	$trans_table_name = "cs_transactiondetails";
	if($test) $trans_table_name = "cs_test_transactiondetails";
	$sql="
			SELECT 
				t.*, DATE_FORMAT(t.transactionDate,'%M %D %Y at %r') as transaction_date_formatted,
				sub.*,
					c.contact_email,
					c.customer_service_phone,
					c.cc_billingdescriptor,
					c.ch_billingdescriptor,
					c.cc_visa_billingdescriptor,
					c.cc_master_billingdescriptor,
					c.we_billingdescriptor, 
					c.cd_recieve_order_confirmations,
					c.we_billingdescriptor,
					c.companyname, 
					c.cd_tracking_init_response, 
					c.cd_enable_tracking, 
					c.gateway_id, 
					c.bank_Creditcard,
					s.*, 
					(r.note_id is not null) as hasRefundRequest,
					 r.call_date_time, 
					 r.service_notes 
			FROM 
				$trans_table_name as t
			LEFT JOIN `cs_subscription` as sub ON sub.`ss_ID` = t.`td_ss_ID`
			LEFT JOIN `cs_companydetails` as c ON c.`userId` = t.`userId`
			LEFT JOIN `cs_company_sites` as s ON `cs_ID` = `td_site_ID`
			left join cs_callnotes as r on r.`transaction_id`=t.`transactionId` AND r.cn_type = 'refundrequest'  
			WHERE 
				`$by` = '$id' 
				$where_sql
				";
									 
	$result = sql_query_read($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql<BR>");
	if(mysql_num_rows($result)<=0) return -1;
	$transactionInfo = mysql_fetch_assoc($result);
	$transactionInfo['CCnumber']= etelDec($transactionInfo['CCnumber']);
	$transactionInfo['bankaccountnumber']= etelDec($transactionInfo['bankaccountnumber']);
	$transactionInfo['bankaccountnumber']= "XXXXXX".substr($transactionInfo['bankaccountnumber'],-4,4);
	
	$transactionInfo['CCnumber_format']= "XXXXXXXXXXXX".substr($transactionInfo['CCnumber'],-4,4);
	$time=strtotime( $transactionInfo['transactionDate']);
	$subAcc = getRebillInfo($transactionInfo['td_rebillingID'],$time,false);
	$transactionInfo['userActiveCode'] = UserActivity(&$transactionInfo);
	$transactionInfo['userRebillCode'] = UserRebill(&$transactionInfo);
	
	//Shipping
	if($transactionInfo['cd_enable_tracking']=='on')
	{
		$transactionInfo['Tracking_Deadline'] = ($time+$transactionInfo['cd_tracking_init_response']*24*60*60);
		$transactionInfo['Tracking_Days_Left'] = floor(($transactionInfo['Tracking_Deadline']-time())/(24*60*60));
	}


	if($transactionInfo['userActiveCode'] == "ACT") $transactionInfo['userActiveMsg'] = "Account is Active, ";
	else if($transactionInfo['userActiveCode'] == "INA") $transactionInfo['userActiveMsg'] = "Account is Inactive, ";
	else if($transactionInfo['userActiveCode'] == "CAN") $transactionInfo['userActiveMsg'] = "Account is Cancelled, ";
	else if($transactionInfo['userActiveCode'] == "CHB") $transactionInfo['userActiveMsg'] = "Account has been Charged Back, ";
	if($transactionInfo['userRebillCode'] == "ACT") $transactionInfo['userActiveMsg'] .= "Rebilling is Active.";
	else if($transactionInfo['userRebillCode'] == "INA") $transactionInfo['userActiveMsg'] .= "Rebilling is Inactive.";

	if($transactionInfo['checkorcard']=='H') 
	{
		$transactionInfo['charge_type_info'] = "Credit Card (".$transactionInfo['cardtype'].")";
		if( $transactionInfo['cardtype']=="Visa") $transactionInfo['billingdescriptor'] = $transactionInfo['cc_visa_billingdescriptor'];
		if($transactionInfo['cardtype']=="Master") $transactionInfo['billingdescriptor'] = $transactionInfo['cc_master_billingdescriptor'];
	}
	if($transactionInfo['checkorcard']=='C') 
	{
		$transactionInfo['charge_type_info'] = "Check Account";
		$transactionInfo['billingdescriptor'] = $transactionInfo['ch_billingdescriptor'];
	}
	if($transactionInfo['checkorcard']=='W') 
	{
		$transactionInfo['charge_type_info'] = "Web900 ";
		$transactionInfo['billingdescriptor'] = $transactionInfo['we_billingdescriptor'];
	}

	// Next Payment
	$transactionInfo['subAccountName'] = $subAcc['rd_subName'];
	$transactionInfo['chargeAmount']=$subAcc['chargeAmount'];
	$transactionInfo['schedule']=$subAcc['schedule'];
	$transactionInfo['nextDateInfo']=$subAcc['nextDateInfo'];
	$transactionInfo['nextRecurDate']=$subAcc['nextRecurDate'];
	if ($transactionInfo['nextRecurDate'] == -1) $transactionInfo['nextRecurDate'] = "N/A";
	if ($transactionInfo['td_enable_rebill'] == 0) $transactionInfo['nextRecurDate'] = "No/Canceled Subscription";
	$transactionInfo['td_recur_next_date_next']=$subAcc['td_recur_next_date'];
	$transactionInfo['subAcc']=$subAcc;
	$recurDate = $subAcc['nextRecurDate'];
	
	$transactionInfo['expires'] = 'N/A';
	$transactionInfo['expired'] = 'N/A';
	if($transactionInfo['ss_account_status']=='inactive')
	{
		$transactionInfo['expired'] = "Expired on ".date("F j, Y",strtotime($transactionInfo['ss_account_expire_date']));
	}
	else 
	if($transactionInfo['ss_account_status']=='active')
		$transactionInfo['expires'] = date("F j, Y",strtotime($transactionInfo['ss_account_expire_date']));

	
	//Formatting
	
	$transactionInfo['phonenumber_format']=formatPhone($transactionInfo['phonenumber']);
	$transactionInfo['fullname']=$transactionInfo['name']." ".$transactionInfo['surname'];
	
	return $transactionInfo;
}

function UserActivity($accountInfo)
{
	if($accountInfo['cancelstatus'] == 'Y') return("REF");

	if($accountInfo['td_is_chargeback'] != '0') return("CHB");
	if($accountInfo['ss_account_status'] != 'active') return("INA");
	return("ACT");
	
	//if($accountInfo['status'] != 'A') return("INA");
	//if(intval(date("ymd",strtotime($accountInfo['td_recur_next_date']))) < intval(date("ymd"))) return("INA");
}

function UserRebill($accountInfo)
{
	if($accountInfo['cancelstatus'] == 'Y') return("REF");

	if($accountInfo['td_is_chargeback'] != '0') return("CHB");
	if($accountInfo['ss_rebill_status'] != 'active') return("INA");
	return("ACT");
	
	//if($accountInfo['status'] != 'A') return("INA");
	//if(intval(date("ymd",strtotime($accountInfo['td_recur_next_date']))) < intval(date("ymd"))) return("INA");
}


?>