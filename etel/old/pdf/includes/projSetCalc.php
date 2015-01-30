<?php

function date_compare($date1,$date2,$mode="LE")
{
	if($mode=="LE")
		return(intval(date("ymd",$date1))<=intval(date("ymd",$date2)));
	if($mode=="GE")
		return(intval(date("ymd",$date1))>=intval(date("ymd",$date2)));
	return false;
}

function projSetCalc()
{

	set_time_limit(30);
	global $projSettlement;
	global $projSettlementPeriods;
	global $monthlyChargebackPerc;
	global $companyInfo;
	global $thisdate;
	global $cnn_cs;
	global $pSKey;
	global $pSAllCompanys;
	
		
	$userid_val=intval($companyInfo['userId']);
		
	$dateadded_time = strtotime($companyInfo['date_added']);
	$dateadded = getdate($dateadded_time);
	$dateadded_time += ($companyInfo['cd_paystartday']+$companyInfo['cd_paydelay']-$dateadded['wday']-1)*60*60*24;
	
  	if($companyInfo['cd_payperiod'] <1) $companyInfo['cd_payperiod'] = 7;
 
 	$datejump = (24*60*60*$companyInfo['cd_payperiod']);
	

	$fdaysback = $companyInfo['cd_payperiod']+$companyInfo['cd_paydelay'];
	$tdaysback = $companyInfo['cd_paydelay'];
	
	$chargeEachMonth = $dateadded_time+24*60*60*30;
	$startdate=$dateadded_time;

	// Start Info
	
	$weekbasedate = 932832000;

	$balance = -$companyInfo['cd_appfee'];
	if($datejump < (24*60*60)) $datejump = (24*60*60*7);
	if ($startdate > $thisdate) $balance = 0;
	
	$monthlyChargebackPerc = "";
	
	$matching = 1;
	
	if (date_compare(time() + $fdaysback*24*60*60,$thisdate,"LE")) $thisdate = time()+$fdaysback*24*60*60;
	for($i=$startdate;date_compare($i,$thisdate,"LE");$i+=$datejump)
	{	// For each Pay Period
		$Net="";$payment_made=false;
		$curMonthStamp = intval(date("ym",$fdate));
		if(!isset($monthlyChargebackPerc[$curMonthStamp]))
		{
			$ch_year = intval(date("Y",$fdate));
			$ch_month = intval(date("m",$fdate))+1;
			if($ch_month>12){$ch_month=1; $ch_year++;}
			$qry_details="SELECT COUNT(`amount`) as cnt, SUM(`r_chargeback`) as sum FROM `cs_transactiondetails` as t WHERE (`userId` = '$userid_val') AND `td_is_chargeback` = '1' AND `cancellationDate` between '".date("Y-m",$fdate)."-01 00:00:00' and '$ch_year-$ch_month-01 00:00:01'";	
			$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$monthlyChargebacks = mysql_fetch_assoc($rst_details);
				
			$qry_details="SELECT COUNT(`amount`) as cnt FROM `cs_transactiondetails` as t WHERE (`userId` = '$userid_val') AND `transactionDate` between '".date("Y-m",$fdate)."-01 00:00:00' and '$ch_year-$ch_month-01 00:00:01'";	
			$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$monthlyTotal = mysql_fetch_assoc($rst_details);
	
			if($monthlyTotal['cnt']<=0) $monthlyChargebackPerc[$curMonthStamp]['ch_perc'] = 0;
			else $monthlyChargebackPerc[$curMonthStamp]['ch_perc'] = $monthlyChargebacks['cnt']/$monthlyTotal['cnt'];
			$monthlyChargebackPerc[$curMonthStamp]['ch_pentalty'] = $monthlyChargebacks['sum'];
		}
		
		
		$fdate = $i-60*60*24*($fdaysback);
		$tdate = $i-60*60*24*($tdaysback+1);
		$weekid=floor(($i-$weekbasedate)/(7*24*60*60));
		
		$projPayPeriod="";
		$projPayPeriod['br'] = "<br>";
		$projPayPeriod['timestamp'] = $i;
		$projPayPeriod['date'] = date("l, F j, Y",$i);
		$projPayPeriod['rollover'] = $rollOverAppliedNext;
		$projPayPeriod['monthlyfee'] = 0;
		$projPayPeriod['wirefee'] = 0;
		$projPayPeriod['startdate'] = date("l, F j, Y, ",$fdate);
		$projPayPeriod['enddate'] = date("l, F j, Y, ",$tdate);
		$projPayPeriod['balance'] = 0;
		$projPayPeriod['invoice'] = "";
		$projPayPeriod['pay?'] = 0;
		$projPayPeriod['rollover?'] = 0;
		$projPayPeriod['ih_inv_ID'] = -1;
		$projPayPeriod['Net'] = 0;
		$projPayPeriod['weekid'] = $weekid;
		$projPayPeriod['matching'] = $matching; $matching++; if($matching>4) $matching = 1;
		$rollOverAppliedNext = 0;	// Roll over for next round
		
		$payment_made=false;
		$inv_details="";
		$sql = "select * from `cs_invoice_history` where `ih_weekid` = '$weekid' AND `userId`='".$userid_val."'";
		$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());

		if ($invoiceInfo=mysql_fetch_assoc($inv_details)) 
		{
			$payment_made = true;
			$balance = 0;
			$rollOverAppliedNext = 0;
			 
			$projPayPeriod['balance'] = $invoiceInfo['ih_balance'];
			$projPayPeriod['monthlyfee'] = $invoiceInfo['ih_monthlyfee'];
			$projPayPeriod['wirefee'] = $invoiceInfo['ih_wirefee'];
			$projPayPeriod['rollover'] = $invoiceInfo['ih_rollover'];
			$projPayPeriod['Net'] = $invoiceInfo['ih_net'];
			$projPayPeriod['timestamp'] = $invoiceInfo['ih_date'];
			$projPayPeriod['pay?'] = 0;
			$projPayPeriod['ih_inv_ID'] = $invoiceInfo['ih_inv_ID'];
			$projPayPeriod['invoice'] = "Payed on ".date("m-d-y",strtotime($invoiceInfo['ih_date_payed']));
			
			if($projPayPeriod['monthlyfee']>0) $chargeEachMonth= $i + 24*60*60*30;
		}
	
		
		if(!$payment_made){	
			if(date_compare($i,$chargeEachMonth,"GE"))
			{	// If a month has passed, charge monthly fee
				if($chargeEachMonth<time())
				{
					$balance -= $companyInfo['cs_monthly_charge'];
					$projPayPeriod['monthlyfee'] = $companyInfo['cs_monthly_charge'];
					$chargeEachMonth= $i + 24*60*60*30;
				}
				else $chargeEachMonth= 9999999999999999999;
			}

			
			$Net = calcNet($fdate,$tdate);
			$projPayPeriod['Net'] = $Net;
			$balance+=$Net;
			$projPayPeriod['balance'] = $balance;
			$rollOverAppliedNext = 0;
			if ($balance>=$companyInfo['cd_rollover'] && $balance > 0) 
			{
				$projPayPeriod['balance'] = $balance-$companyInfo['cd_wirefee'];
				$projPayPeriod['wirefee'] = $companyInfo['cd_wirefee'];
				$balance = 0;
				$projPayPeriod['invoice'] = "Not Payed";
				$projPayPeriod['pay?'] = 1;
			}
			else
			{
				if ($balance !=0) 
				{
					$projPayPeriod['invoice'] = "Rollover";
					$projPayPeriod['rollover?'] = 1;
				}
				
				else $projPayPeriod['invoice'] = "No Balance";
				$rollOverAppliedNext = $balance;
			}
		}
		$keyval=intval(date("ymd",$projPayPeriod['timestamp']));
		if($pSKey=='weekid') $keyval=$projPayPeriod['weekid'];
		if ($pSAllCompanys) $projPayPeriod['invoice']="";
		
		$projSettlement[$keyval]=$projPayPeriod;
		$projSettlementPeriods[intval(date("ymd",$fdate))]['txt']="Starts ->";
		$projSettlementPeriods[intval(date("ymd",$tdate))]['txt'].="<- Ends";
		$projSettlementPeriods[intval(date("ymd",$fdate))]['matching']=$matching;
	}
	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump))]['txt']="Starts ->";
	$projSettlementPeriods[intval(date("ymd",$tdate+$datejump))]['txt'].="<- Ends";
	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump))]['matching']=$matching; $matching++; if($matching>4) $matching = 1;
	
	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump*2))]['txt']="Starts ->";
	$projSettlementPeriods[intval(date("ymd",$tdate+$datejump*2))]['txt'].="<- Ends";
	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump*2))]['matching']=$matching; $matching++; if($matching>4) $matching = 1;

	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump*3))]['txt']="Starts ->";
	$projSettlementPeriods[intval(date("ymd",$tdate+$datejump*3))]['txt'].="<- Ends";
	$projSettlementPeriods[intval(date("ymd",$fdate+$datejump*3))]['matching']=$matching;
	if ($pSAllCompanys) $projSettlementPeriods="";
	return $projSettlement;
}	

function calcNet($fdate,$tdate)
{
	global $cnn_cs;
	global $companyInfo;
	global $pSAllCompanys;
		
	$compSQL = "";
	$compID = $companyInfo['userId'];
	if (!$compID) $compID = -1;
	$compSQL = "AND `userId` = '$compID'";
	if ($pSAllCompanys) $compSQL = "";
	
	$stats_list = "";
	
	$fromdate = date("Y-m-d ",$fdate)." 00:00:00";
	$todate = date("Y-m-d ",$tdate)." 23:59:59";
			
	$drange = "'".$fromdate."' and '".$todate."'";	
	$includeCHW = false;
	include('netcalc.php');
	return $Net;

}

// New


function calcMonthlyFee()
{
	global $companyInfo;
	
	$sql = "SELECT min(transactionDate) 
FROM `cs_companydetails` AS cd
LEFT JOIN `cs_transactiondetails` AS td ON cd.userId = td.userId
WHERE cd.`userId` ='".$companyInfo['userId']."'
ORDER BY transactionDate ASC 
LIMIT 1";

	$result = mysql_query($sql);
	$transactionDate = strtotime(mysql_result($result,0));
	$added = strtotime($companyInfo['date_added']);
	if($transactionDate>$added) $added = $transactionDate;
	//else return 0;
	
	$lastpaid = strtotime($companyInfo['cd_last_paid_monthlyfee']);
	if($added>$lastpaid) $lastpaid = $added;
	$durationMonths = round((time()-$lastpaid)/(60*60*24*30));
	$fee=$companyInfo['cs_monthly_charge']*$durationMonths;
	//$fee=$companyInfo['cs_monthly_charge'];
	return $fee;
}

function pastPayPeriod()
{
	global $companyInfo;
	$added = strtotime($companyInfo['date_added']);
	$lastpaid = strtotime($companyInfo['cd_next_pay_day']);
	if($added>$lastpaid) $lastpaid = $added;
	//$lastPaidDays = $lastpaid;
	return(time()>$lastpaid+24*60*60);
}

function resellerPastPayPeriod()
{
	global $companyInfo;
	$added = strtotime($companyInfo['reseller_date_added']);
	$lastpaid = strtotime($companyInfo['rd_next_pay_day']);
	if($added>$lastpaid) $lastpaid = $added;
	//print date('y-m-d',$lastpaid)."<BR>".(time()>$lastpaid)."<BR>";
	//$lastPaidDays = $lastpaid;
	return(time()>$lastpaid+24*60*60);
}

function pushBackOnePeriod($lastpaid=0,$startday=false)
{
	global $companyInfo;
	if(!$lastpaid)
	{
		$added = strtotime($companyInfo['date_added']);
		if(!$startday) $lastpaid = strtotime($companyInfo['cd_next_pay_day']);
		if($added>$lastpaid) $lastpaid = $added;
	}
	if($companyInfo['cd_payperiod']<=1)$companyInfo['cd_payperiod']=7;
	//$lastPaidDays = $lastpaid;
	if($companyInfo['cd_pay_bimonthly']=='bimonthly')	// Bimonthly Pay Schedule
	{
		$nextPayDay =$lastpaid + (18*60*60*24);
		$dayOfMonth = intval(date('d',$nextPayDay));
		if($dayOfMonth>=15) $overlap = ($dayOfMonth-15);
		else $overlap = ($dayOfMonth-1);
		$nextPayDay-=($overlap*60*60*24);
	}
	else if($companyInfo['cd_pay_bimonthly']=='trimonthly')	// Bimonthly Pay Schedule
	{
		$nextPayDay =$lastpaid + (10*60*60*24);
		$dayOfMonth = intval(date('d',$nextPayDay));
		if($dayOfMonth<=5) $nextPayDay = strtotime(date("Y-m-1",$nextPayDay + (30*60*60*24)));
		else if($dayOfMonth<=15) $nextPayDay = strtotime(date("Y-m-10",$nextPayDay));
		else if($dayOfMonth<=25) $nextPayDay = strtotime(date("Y-m-20",$nextPayDay));
		else $nextPayDay = strtotime(date("Y-m-1",$nextPayDay + (30*60*60*24)));
		
	}	
	else	// Weekly Pay Schedule
	{
		$nextPayDay =$lastpaid + ($companyInfo['cd_payperiod'])*(60*60*24);
		
		$overlap = (($companyInfo['cd_paydaystartday'])-(intval(date("w",$nextPayDay))));
		while($overlap<=-2)$overlap+=7;
		while($overlap>=5)$overlap-=7;
		$nextPayDay+=$overlap*(60*60*24);
	}
	return date('Y-m-d',$nextPayDay);
}

function resellerPushBackOnePeriod($lastpaid=0)
{
	global $companyInfo;	
	if(!$lastpaid)
	{
		$added = strtotime($companyInfo['reseller_date_added']);
		$lastpaid = strtotime($companyInfo['rd_next_pay_day']);
		if($added>$lastpaid) $lastpaid = $added;
		//$lastPaidDays = $lastpaid;
	}
	$nextPayDay =$lastpaid;
	while($nextPayDay<time())
		$nextPayDay = $nextPayDay + (60*60*24*30);
		
	$nextPayDay = date('Y-m-',$nextPayDay).$companyInfo['rd_paydelay'];
	return $nextPayDay;
}


function calcReal($thisdate=0)
{

	set_time_limit(30);
	global $cnn_cs;
	global $companyInfo;
	global $date_hold;
	global $date_delay;
	global $bank_ids;
	global $error_msg;
	
	//$fdaysback = $companyInfo['cd_payperiod']+$companyInfo['cd_paydelay'];
	$fdaysback = $companyInfo['cd_paydelay'];
	$tdaysback = $companyInfo['cd_paydelay'];

	if($companyInfo['cd_pay_bimonthly']=='trimonthly')
	{
		$monthDay = intval(date("d",$thisdate));
		$payableToday = false;
		if($monthDay==10 || $monthDay==20 || $monthDay==1) $payableToday = true;
		if($monthDay<5)
		{
			$last20th = strtotime(date("Y-m-20",$thisdate-10*60*60*24));
		}
		else if($monthDay<15)
		{
			$last20th = strtotime(date("Y-m-01",$thisdate))-(24*60*60);
		}
		else if($monthDay<25)
		{

			$last20th = strtotime(date("Y-m-10",$thisdate));
		}
		else
		{
			$last20th = strtotime(date("Y-m-20",$thisdate+20*60*60*24));
		}
		$fdaysback = ($thisdate-$last20th)/(60*60*24);
	}
		
	$fdate = $fdaysback*24*60*60;
	if($fdate<0) $fdate = 0;
	if(!$thisdate) $thisdate = time();
	if(!$date_hold) $date_hold = date("Y-m-d 23:59:59",$thisdate-$fdate);
	if(!$date_delay) $date_delay = date("Y-m-d 23:59:59",$thisdate-60);
	// Grab Banks
	
	$bank_id_sql = "";
		
	if(is_array($bank_ids))
	{
		$bank_id_sql = " AND bk.bank_id in (".implode(",",$bank_ids).")";
	}

	// Date Range Info
	//$RangeInfo = "From First Unpaid Transaction ";
	//if($calcInfo['last_date_hold']) 
	$RangeInfo = "All unpaid transactions to ".date("m-d-Y",strtotime($date_hold));
		
	$sql = "SELECT distinct bk.`bank_id`, `bank_name`, `bk_payout_support` 
	FROM `cs_bank` as bk left join `cs_transactiondetails` as td on bk.`bank_id` = td.bank_id 
	WHERE td.userId='".$companyInfo['userId']."' $bank_id_sql";
	
	$bank_result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");

	$bank_info_sql="";
	while($bank_info = mysql_fetch_assoc($bank_result))
	{		
		// Grab Data
		$bank_id = $bank_info['bank_id'];

		$sql = "SELECT 
		 SUM((td.`status` = 'A') * (td.`amount`)) AS Sales ,
		 SUM(td.`r_merchant_trans_fees`) AS TransactionFees ,
		 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_merchant_discount_rate`/100)*(td.`status` = 'A')) AS DiscountFees ,
		 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_reserve`/100)*(td.`status` = 'A')) AS ReserveFees ,
		 
	 	 SUM((td.`td_customer_fee`)*(td.`status` = 'A')) AS CustomerTransactionFees, 		 

		 SUM((tdc.`cancelstatus` = 'Y') * tdc.`r_credit`) AS RefundFees ,
		 SUM((tdc.`td_is_chargeback` = '1') * tdc.`r_chargeback`) AS ChargebackFees ,
		 
		 SUM((tdc.`cancelstatus` = 'Y') * tdc.`amount`) AS Refunds ,
		 SUM((tdc.`td_is_chargeback` = '1') * tdc.`amount`) AS Chargebacks ,
		 SUM((tdc.`td_voided_check` = '1') * tdc.`amount`) AS VoidChecks ,
		 SUM((tdc.`td_returned_checks` = '1') * tdc.`amount`) AS ReturnedChecks,
		 
		 SUM((tdc.`amount`-tdc.`td_customer_fee`)*(tdc.`r_reserve`/100)*(tdc.`cancelstatus` = 'Y' && tdc.`status` = 'A')) AS RefundReserveRate
		 
		 from cs_transactiondetails as t 
	left join cs_transactiondetails as td on td.td_merchant_paid = 0
	and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold'
	left join  cs_transactiondetails as tdc ON tdc.td_merchant_deducted = 0
	and t.`transactionId` = tdc.`transactionId` and tdc.cancellationDate < '$date_delay'
	 WHERE t.userId='".$companyInfo['userId']."' and t.bank_id = '$bank_id'";
	 
		$result=sql_query_read($sql) or dieLog(mysql_error($cnn_cs)." ~ $sql");
		$mi_pay_info_bank = mysql_fetch_assoc($result);
		$mi_pay_info_bank = array_merge($mi_pay_info_bank,$bank_info);
				
		$mi_pay_info_bank['Deductions'] =
						  $mi_pay_info_bank['TransactionFees'] 
						+ $mi_pay_info_bank['DiscountFees'] 
						+ $mi_pay_info_bank['ReserveFees'] 
						+ $mi_pay_info_bank['Refunds']  
						+ $mi_pay_info_bank['Chargebacks']  
						+ $mi_pay_info_bank['VoidChecks']  
						+ $mi_pay_info_bank['ReturnedChecks'] 
						+ $mi_pay_info_bank['RefundFees'] 
						+ $mi_pay_info_bank['CustomerTransactionFees'] 						
						+ $mi_pay_info_bank['ChargebackFees'];
		
		$mi_pay_info_bank['Gross'] = 
						  $mi_pay_info_bank['Sales']
						+ $mi_pay_info_bank['RefundReserveRate'];
		
		$mi_pay_info_bank['ProjectedSales'] =
						  $mi_pay_info_bank['Sales'] 
						- $mi_pay_info_bank['DiscountFees'] 
						- $mi_pay_info_bank['TransactionFees'] 
						- $mi_pay_info_bank['CustomerTransactionFees'] 
						- $mi_pay_info_bank['ReserveFees'];
						
		$mi_pay_info_bank['Balance'] = $mi_pay_info_bank['Gross'] - $mi_pay_info_bank['Deductions'];
						

		$mi_pay_info['BankInfo'][$bank_id] = $mi_pay_info_bank;
	}
			
	if(is_array($mi_pay_info['BankInfo']))
	{
		foreach($mi_pay_info['BankInfo'] as $bk=>$bd)
		{
			foreach($mi_pay_info['BankInfo'][$bk] as $k=>$d)
				if (isset($mi_pay_info[$k])) $mi_pay_info[$k]+=floatval($d); else $mi_pay_info[$k] = $d;
			unset($mi_pay_info['bank_id']);
			unset($mi_pay_info['bank_name']);
		}
	}
	
		
// Misc Fees

	$mi_pay_info['RangeInfo'] = $RangeInfo;
	
	$mf_date = date("Y-m-d",$thisdate);
	$sql = "select SUM(mf_amount) as mf_amount, group_concat(mf_ID) as mf_ID_list from `cs_misc_fees` where mf_entity = '".$companyInfo['userId']."' AND mf_date ='$mf_date' AND mf_invoice_type ='merchant' AND mf_paid=0";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	$cs_misc_fees = mysql_fetch_assoc($result);	
	if($cs_misc_fees['mf_ID_list'])
	{
		$mi_pay_info['MiscFees'] = $cs_misc_fees['mf_amount'];
		$mi_pay_info['mf_ID_list'] = $cs_misc_fees['mf_ID_list'];
	}
	
	
	if($mi_pay_info['Sales']!=0)
	{
		$mi_pay_info['MonthlyFee'] = calcMonthlyFee();
		$mi_pay_info['WireFee'] = $companyInfo['cd_wirefee'];
		$mi_pay_info['SetupFee'] = (!$companyInfo['cd_paid_setup_fee'])*$companyInfo['cd_appfee'];
	}
	$mi_pay_info['Status'] = "";
	$mi_pay_info['pay'] = 0;
	$mi_pay_info['pay_msg'] = "Error";

	$mi_pay_info['EtelDeductions'] = $mi_pay_info['WireFee'] + $mi_pay_info['MonthlyFee'] + $mi_pay_info['SetupFee'] - $mi_pay_info['MiscFees'];
	$mi_pay_info['TotalDeductions'] = $mi_pay_info['Deductions'] + $mi_pay_info['EtelDeductions'];
					
	$mi_pay_info['Balance'] = $mi_pay_info['Gross'] - $mi_pay_info['TotalDeductions'];

	if($mi_pay_info['Balance'] + $mi_pay_info['WireFee'] == 0)
	{
		$mi_pay_info['Status'] = "Nothing";
		$mi_pay_info['Balance'] += $mi_pay_info['WireFee'];
		$mi_pay_info['EtelDeductions'] -= $mi_pay_info['WireFee'];
		$mi_pay_info['WireFee'] = "N/A";
		$mi_pay_info['pay_msg'] = "Nothing to Wire";
	}
	else if($mi_pay_info['Balance']<	$companyInfo['cd_rollover']) 
	{
		$mi_pay_info['Status'] = "Rollover";
		$mi_pay_info['Balance'] += $mi_pay_info['WireFee'];
		$mi_pay_info['EtelDeductions'] -= $mi_pay_info['WireFee'];
		$mi_pay_info['WireFee'] = "N/A";	
		$mi_pay_info['pay_msg'] = "Rollover";
	}
	else
	{
		$mi_pay_info['Status'] = "Payable";
		$mi_pay_info['pay'] = 1;
	}
	$error_msg = $mi_pay_info['pay_msg'];
	foreach($mi_pay_info as $key=>$data)
		if(is_numeric($mi_pay_info[$key])) $mi_pay_info[$key]=round($data,2);
	return $mi_pay_info;

}

function payCompany($thisdate=0)
{
	global $cnn_cs;
	global $companyInfo;
	global $mi_notes;
	global $bank_ids;
	global $deduct_bank;
	global $error_msg;
	global $date_hold;
	global $date_delay;
	
	$error_msg = "No Error"; 
	
	$fdaysback = $companyInfo['cd_paydelay'];
	$tdaysback = $companyInfo['cd_paydelay'];

	if(!$deduct_bank) $deduct_bank = $companyInfo['bank_Creditcard'];
	if(!is_array($bank_ids))
	{
		$sql = "SELECT group_concat(distinct bk.`bank_id`) as banks
		FROM `cs_bank` as bk left join `cs_transactiondetails` as td on bk.`bank_id` = td.bank_id 
		WHERE td.userId='".$companyInfo['userId']."'";
		$result=sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$bank_ids = explode(",",mysql_result($result,0,0));
		if(!$deduct_bank) $deduct_bank = $companyInfo['bank_Creditcard'];
		if(!$deduct_bank) $deduct_bank = $companyInfo['bank_check'];
		if(!$deduct_bank) $deduct_bank = $companyInfo['cd_web900bank'];
	}
	
	if($companyInfo['cd_pay_bimonthly']=='trimonthly')
	{
		$monthDay = intval(date("d",$thisdate));
		$payableToday = false;
		if($monthDay==10 || $monthDay==20 || $monthDay==1) $payableToday = true;
		if($monthDay==1)
		{
			$last20th = strtotime(date("Y-m-20",$thisdate-10*60*60*24));
		}
		if($monthDay==10)
		{
			$last20th = strtotime(date("Y-m-01",$thisdate))-(24*60*60);
		}
		if($monthDay==20)
		{
			$last20th = strtotime(date("Y-m-10",$thisdate));
		}
		$fdaysback = ($thisdate-$last20th)/(60*60*24);
	}
	
	$fdate = $fdaysback*24*60*60;
	if($fdate<0) $fdate = 0;
	if(!$thisdate) $thisdate = time();
	$date_hold = date("Y-m-d g:i:s",$thisdate-$fdate);
	$date_delay = date("Y-m-d g:i:s",$thisdate-60);
	$nextPayDay = pushBackOnePeriod($thisdate);
	$mi_pay_info = calcReal();
	$mi_pay_info['Status'] = "Paid";
	$mi_pay_info['bank_ids'] = $bank_ids;
	$error_msg = "No Error";
	if(!is_array($bank_ids)) { $error_msg = "Bank IDs Invalid"; return -1;}
	if(!$mi_pay_info['pay']) {$error_msg = "Not Payable: ".$mi_pay_info['pay_msg']."."; return -1; }
// Calculate Etel Deductions
	$bank_id_sql = "";
	foreach($bank_ids as $bank_id)
	{	
		if($deduct_bank==$bank_id) 
		{
			$mi_pay_info['BankInfo'][$bank_id]['Balance'] -= $mi_pay_info['EtelDeductions'];
			$mi_pay_info['BankInfo'][$bank_id]['EtelDeductions'] = $mi_pay_info['EtelDeductions'];
			$mi_pay_info['BankInfo'][$bank_id]['WireFee'] = $mi_pay_info['WireFee'];
		}
	}

// Create an Invoice
	$invoiceName = quote_smart($companyInfo['companyname']." - ".date("l, F j, Y",$thisdate)." - $".formatMoney($mi_pay_info['Balance']));
	$invoice_sql = "INSERT INTO `cs_merchant_invoice` 
	(`mi_title`, `mi_company_id` , `mi_date` , `mi_paydate`, `mi_balance` , `mi_deduction` , `mi_company_info`, `mi_notes` )
VALUES (
'$invoiceName', '".$companyInfo['userId']."', NOW( ) , '".date("Y-m-d",$thisdate)."' , '".$mi_pay_info['Balance']."', '".$mi_pay_info['TotalDeductions']."', '".addslashes(serialize($companyInfo))."', '$mi_notes'
);
";
	$result=sql_query_write($invoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
	
	$invoice_id = mysql_insert_id($cnn_cs);
	
	$mib_wire_type = 'non-us';
	if($companyInfo['bank_wiremethod']=='ACH') $mib_wire_type = 'us';
// Create SubInvoice
	$bank_id_sql = "";
	foreach($bank_ids as $bank_id)
	{	
		$balance = round($mi_pay_info['BankInfo'][$bank_id]['Balance'],2);
		$deduction = round($mi_pay_info['BankInfo'][$bank_id]['EtelDeductions'],2);
		$mib_monthly_fee = round($mi_pay_info['BankInfo'][$bank_id]['MonthlyFee'],2);
		$mib_setup_fee = round($mi_pay_info['BankInfo'][$bank_id]['SetupFee'],2);
		
		$mib_wire_fee = round($mi_pay_info['BankInfo'][$bank_id]['WireFee'],2);;
		$invoiceName = $companyInfo['companyname']." - ".date("l, F j, Y",$thisdate)." - $".formatMoney($mi_pay_info['Balance']);
		$subinvoice_sql = "INSERT INTO `cs_merchant_invoice_banksub` 
		( `mib_mi_ID` , `mib_bank_id`, `mib_company_id`, `mib_wire_fee`, `mib_wire_type`, `mib_monthly_fee`, `mib_setup_fee`,  `mib_balance`, `mib_etelDeduction`)
	VALUES ('$invoice_id', '$bank_id' , '".$companyInfo['userId']."' , '$mib_wire_fee' , '$mib_wire_type' , '$mib_monthly_fee' , '$mib_setup_fee' , '$balance', '$deduction');
	";
		$result=mysql_query($subinvoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
		$mi_pay_info['BankInfo'][$bank_id]['mib_ID'] = mysql_insert_id();
	}
	
	$bank_id_sql = " AND t.bank_id in (".implode(",",$bank_ids).")";
// Update Transactions

	$transaction_sql = "UPDATE cs_transactiondetails as t 
left join cs_transactiondetails as td on td.td_merchant_paid = 0
and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold' and td.td_merchant_paid = 0
left join  cs_transactiondetails as tdc ON tdc.td_merchant_deducted = 0
and t.`transactionId` = tdc.`transactionId`  and tdc.cancellationDate < '$date_delay'
set td.td_merchant_paid = $invoice_id,   tdc.td_merchant_deducted=$invoice_id
 WHERE t.userId='".$companyInfo['userId']."' $bank_id_sql ";
 
	$result=mysql_query($transaction_sql,$cnn_cs) or dieLog(mysql_error()." ~ $transaction_sql");
	
// Update Company

	$company_sql = "UPDATE `cs_companydetails` as cd SET 
`cd_paid_setup_fee` = '1',
`cd_last_paid_monthlyfee` = NOW( ) ,
`cd_next_pay_day` = '$nextPayDay'
 WHERE cd.userId='".$companyInfo['userId']."'";
	$result=mysql_query($company_sql,$cnn_cs) or dieLog(mysql_error()." ~ $company_sql");
	
	$companyInfo['cd_paid_setup_fee']=1;
	$companyInfo['cd_next_pay_day']=$nextPayDay;
	$companyInfo['cd_last_paid_monthlyfee']=date("Y-m-d");
	
	
	$invoice_sql = "Update `cs_merchant_invoice` set `mi_pay_info` = '".addslashes(serialize($mi_pay_info))."' where mi_ID = '$invoice_id'";

	$result=mysql_query($invoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
		
	// Update Misc Fees

	if($mi_pay_info['mf_ID_list'])
	{
		$sql = "update `cs_misc_fees` set mf_paid=$invoice_id where mf_ID in ('".$mi_pay_info['mf_ID_list']."')";
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	}
	
	return $invoice_id;
}

function calcResellerReal($thisdate=0)
{
	global $cnn_cs;
	global $companyInfo;
	global $date_hold;
	global $date_delay;
	global $etel_debug_mode;
	set_time_limit(30);
	
	$nextPayDayTime = strtotime(resellerPushBackOnePeriod());
	if(!$thisdate) $thisdate = $nextPayDayTime;
	if(!$date_hold) $date_hold = date("Y-m-d g:i:s",$thisdate);
	if(!$date_delay) $date_delay = date("Y-m-d g:i:s",$thisdate-60);
		
	$sql = "SELECT 
	 SUM((td.`status` = 'A')*(td.`amount`)) AS TotalSales , 													SUM(td.`status` = 'A') AS TotalSales_Num,
	 SUM((td.`status` = 'A') *(td.`amount`)*(td.`r_reseller_discount_rate`/100)) AS Sales ,
	 SUM(td.`r_merchant_trans_fees`*(td.`r_reseller_discount_rate`/100)) AS TransactionFees ,
	 SUM((td.`status` = 'A') * td.`r_reseller_trans_fees`) AS ResTransactionFees ,
	 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_merchant_discount_rate`/100)*(td.`status` = 'A')*(td.`r_reseller_discount_rate`/100)) AS DiscountFees ,
	 SUM((td.`amount`)*(td.`r_reserve`/100)*(td.`status` = 'A')*(td.`r_reseller_discount_rate`/100)) AS ReserveFees ,
		 
	 SUM((td.`td_customer_fee`)*(td.`status` = 'A')*(td.`r_reseller_discount_rate`/100)) AS CustomerTransactionFees, 		 
		 
	 SUM((tdc.`cancelstatus` = 'Y') * tdc.`r_credit`*(tdc.`r_reseller_discount_rate`/100)) AS RefundFees , 		SUM(tdc.`cancelstatus` = 'Y') AS Refunds_Num,
	 SUM((tdc.`td_is_chargeback` = '1') * tdc.`r_chargeback`*(tdc.`r_reseller_discount_rate`/100)) AS ChargebackFees ,
	 
	 SUM((tdc.`cancelstatus` = 'Y') * tdc.`amount`*(tdc.`r_reseller_discount_rate`/100)) AS Refunds ,
	 SUM((tdc.`td_is_chargeback` = '1') * tdc.`amount`*(tdc.`r_reseller_discount_rate`/100)) AS Chargebacks ,
	 SUM((tdc.`td_voided_check` = '1') * tdc.`amount`*(tdc.`r_reseller_discount_rate`/100)) AS VoidChecks ,
	 SUM((tdc.`td_returned_checks` = '1') * tdc.`amount`*(tdc.`r_reseller_discount_rate`/100)) AS ReturnedChecks
	 
	 
	 
	 from cs_transactiondetails as t 
left join cs_companydetails as cd on cd.userId = t.userId
left join cs_transactiondetails as td on td.td_reseller_paid = 0
and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold'
left join  cs_transactiondetails as tdc ON tdc.td_reseller_deducted = 0
and t.`transactionId` = tdc.`transactionId` and tdc.cancellationDate < '$date_delay'
 WHERE cd.reseller_id='".$companyInfo['reseller_id']."'";
 
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	$ri_pay_info = mysql_fetch_assoc($result);
	
	
	$ri_pay_info['thisdate'] = $thisdate;
	$ri_pay_info['Status'] = "";
	$ri_pay_info['pay'] = 0;
	$ri_pay_info['WireFee'] = $companyInfo['rd_wirefee'];
	
	$ri_pay_info['Deductions'] =
					  $ri_pay_info['TransactionFees'] 
					+ $ri_pay_info['DiscountFees'] 
					+ $ri_pay_info['ReserveFees'] 
					+ $ri_pay_info['Refunds']  
					+ $ri_pay_info['Chargebacks']  
					+ $ri_pay_info['VoidChecks']  
					+ $ri_pay_info['RefundFees'] 
					+ $ri_pay_info['ChargebackFees']
					+ $ri_pay_info['CustomerTransactionFees'] 
					+ $ri_pay_info['ReturnedChecks'];
					
	$ri_pay_info['ProjectedSales'] =
					  $ri_pay_info['Sales'] 
					- $ri_pay_info['DiscountFees'] 
					- $ri_pay_info['ReserveFees'];
					
	$ri_pay_info['EtelDeductions'] = $ri_pay_info['WireFee'];
	$ri_pay_info['TotalDeductions'] = $ri_pay_info['Deductions'] + $ri_pay_info['EtelDeductions'];
	$ri_pay_info['Gross'] = 
					  $ri_pay_info['ResTransactionFees']
	  				+ $ri_pay_info['Sales'];
						  
	$ri_pay_info['Balance'] = $ri_pay_info['Gross'] - $ri_pay_info['TotalDeductions'];
	
	if($ri_pay_info['Balance'] + $ri_pay_info['WireFee'] == 0)
	{
		$ri_pay_info['Status'] = "Nothing";
		$ri_pay_info['Balance'] += $ri_pay_info['WireFee'];
		$ri_pay_info['EtelDeductions'] -= $ri_pay_info['WireFee'];
		$ri_pay_info['WireFee'] = "N/A";
	}
	else if($ri_pay_info['Balance'] < $companyInfo['rd_rollover']) 
	{
		$ri_pay_info['Status'] = "Rollover";
		if($ri_pay_info['Balance'] + $ri_pay_info['WireFee'] < $companyInfo['rd_rollover'])
		{
			$ri_pay_info['EtelDeductions'] -= $ri_pay_info['WireFee'];
			$ri_pay_info['Balance'] += $ri_pay_info['WireFee'];
			$ri_pay_info['WireFee'] = "N/A";
		}
	}
	else
	{
		$ri_pay_info['Status'] = "Payable";
		$ri_pay_info['pay'] = 1;
	}
	
	$date_hold = 0 ;
	$date_delay = 0 ;

	
	foreach($ri_pay_info as $key=>$data)
		if(is_numeric($ri_pay_info[$key])) $ri_pay_info[$key]=round($data,2);
	return $ri_pay_info;

}

function payReseller($thisdate=0)
{
	global $cnn_cs;
	global $companyInfo;
	global $ri_notes;

	$nextPayDayTime = strtotime(resellerPushBackOnePeriod());
	if(!$thisdate) $thisdate = $nextPayDayTime;
	$date_hold = date("Y-m-d g:i:s",$thisdate-$fdate);
	$date_delay = date("Y-m-d g:i:s",$thisdate-60);
	$nextPayDay = date('Y-m-',$nextPayDayTime).$companyInfo['rd_paydelay'];

 
	$ri_pay_info = calcResellerReal(strtotime($nextPayDay));
	$ri_pay_info['Status'] = "Paid";
	if(!$ri_pay_info['pay']) return -1;
	
// Create an Invoice
	$invoiceName = $companyInfo['reseller_companyname']." - ".date("l, F j, Y")." - $".formatMoney($ri_pay_info['Balance']);
	$invoice_sql = "INSERT INTO `cs_reseller_invoice` 
	(`ri_title`, `ri_reseller_id` , `ri_date` , `ri_balance` , `ri_deduction` , `ri_pay_info` , `ri_company_info`, `ri_notes` )
VALUES (
'$invoiceName', '".$companyInfo['reseller_id']."', NOW( ) , '".$ri_pay_info['Balance']."', '".$ri_pay_info['TotalDeductions']."', '".addslashes(serialize($ri_pay_info))."', '".addslashes(serialize($companyInfo))."', '$ri_notes'
);
";
	$result=mysql_query($invoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
	
	$invoice_id = mysql_insert_id($cnn_cs);
	
// Update Transactions

	$transaction_sql = "UPDATE cs_transactiondetails as t 
left join cs_companydetails as cd on cd.userId = t.userId
left join cs_transactiondetails as td on td.td_reseller_paid = 0
and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold'
left join  cs_transactiondetails as tdc ON tdc.td_reseller_deducted = 0
and t.`transactionId` = tdc.`transactionId` and tdc.cancellationDate < '$date_delay'
set td.td_reseller_paid = $invoice_id,   tdc.td_reseller_deducted=$invoice_id
  WHERE cd.reseller_id='".$companyInfo['reseller_id']."'";
 
	$result=mysql_query($transaction_sql,$cnn_cs) or dieLog(mysql_error()." ~ $transaction_sql");
	
// Update Company

	$company_sql = "UPDATE `cs_resellerdetails` as rd SET 
`rd_next_pay_day` = '$nextPayDay'
 WHERE rd.reseller_id='".$companyInfo['reseller_id']."'";
	$result=mysql_query($company_sql,$cnn_cs) or dieLog(mysql_error()." ~ $company_sql");
	
}

function calcBankReal($thisdate,$calcInfo=null)
{
	set_time_limit(300);
	global $cnn_cs;
	global $bankInfo;
	//global $date_hold;
	//global $date_delay;
	//global $limit_to_last_hold;
	//global $limit_to_last_delay;
	//global $includeInvoiceFees;
	//global $forceCalc;
	//global $forceProfitView;
	
	$date_hold = $calcInfo['date_hold'];
	$date_delay = $calcInfo['date_delay'];
	$limit_to_last_hold = $calcInfo['limit_to_last_hold'];
	$limit_to_last_delay = $calcInfo['limit_to_last_delay'];
	//$forceProfitView = $calcInfo['forceProfitView'];
	$forceCalc = $calcInfo['forceCalc'];
	
	$fdaysback = $bankInfo['bk_days_behind'];
	$highRiskDiscount = floatval($bankInfo['bk_fee_high_risk']/100);
	$lowRiskDiscount = floatval($bankInfo['bk_fee_low_risk']/100);
	$ApproveTransFee = floatval($bankInfo['bk_fee_approve']);
	$NonApproveTransFee = floatval($bankInfo['bk_fee_decline']);
	$ChargebackFee = floatval($bankInfo['bk_fee_chargeback']);
	$RefundFee = floatval($bankInfo['bk_fee_refund']);
	$UsWire = floatval($bankInfo['bk_fee_us_wire']);
	$NonUsWire = floatval($bankInfo['bk_fee_nonus_wire']);

	$PayrollDiscount = floatval($bankInfo['bk_payroll_discount']);

  	  
	 	 

	$fdate = $fdaysback*24*60*60;
	if(!$thisdate) $thisdate = time();
	
	$payableToday = true;
	if($bankInfo['bk_paydays_method']=='weekdays')
	{
		$weekDayNum = intval(date("w",$thisdate));
		if($weekDayNum==0 || $weekDayNum==6) $payableToday = false;
		
		$finaldaysback =0;
		for($iday=0;$iday<$fdaysback;$iday++)
		{
			$finaldaysback++;
			$weekDayNum = intval(date("w",$thisdate-$finaldaysback*24*60*60));
			if($weekDayNum==0 || $weekDayNum==6) $iday--;
		}
		$fdate = $finaldaysback*24*60*60;
	}
	if($bankInfo['bk_paydays_method']=='10-20-1')
	{
		$monthDay = intval(date("d",$thisdate));
		$payableToday = false;
		if($monthDay==10 || $monthDay==20 || $monthDay==1) $payableToday = true;
		if($monthDay==1)
		{
			$last20th = strtotime(date("Y-m-20",$thisdate-10*60*60*24));
		}
		if($monthDay==10)
		{
			$last20th = strtotime(date("Y-m-01",$thisdate))-(24*60*60);
		}
		if($monthDay==20)
		{
			$last20th = strtotime(date("Y-m-10",$thisdate));
		}
		$fdaysback = ($thisdate-$last20th)/(60*60*24);
		$finaldaysback = $fdaysback;
		$fdate = $finaldaysback*24*60*60;
	}
	if(!$payableToday && !$calcInfo['forceCalc']) return -1;
	
	if($fdate<0) $fdate = 0;
		
	if(!$date_hold) $date_hold = date("Y-m-d 23:59:59",$thisdate-$fdate);
	if($calcInfo['last_date_hold']) $limit_to_last_hold = $calcInfo['last_date_hold'];
	
	if(!$date_delay) $date_delay = date("Y-m-d 00:00:00",$thisdate);
	if($calcInfo['last_date_delay']) $limit_to_last_delay = $calcInfo['last_date_delay'];

	
	$limit_to_last_hold_sql="";
	if($limit_to_last_hold) $limit_to_last_hold_sql=" and td.transactionDate > '$limit_to_last_hold'";
	if($limit_to_last_delay) $limit_to_last_delay_sql=" and tc.cancellationDate > '$limit_to_last_delay'";
	
	// Date Range Info
	$RangeInfo = "From First Unpaid Transaction ";
	if($calcInfo['last_date_hold']) $RangeInfo = "From ".date("m-d-Y",strtotime($calcInfo['last_date_hold'])+60*60*12)." ";
	$RangeInfo .= "to ".date("m-d-Y",strtotime($date_hold));
	
	$sql_paid = 'td.td_bank_paid = 0';
	$sql_deducted = 'tc.td_bank_deducted = 0';
	if($calcInfo['estimateMode'])
	{
		$sql_paid = '1';
		$sql_deducted = '1';
	}
	
	
	// group_concat( td.`transactionId` ) as TransactionList,
	// group_concat( tc.`transactionId` ) as DeductionsList,
	 
	$sql = "SELECT 
	
	 '1' as TransactionList,
	 '1' as DeductionsList,
	
	 SUM((td.`status` = 'A') * td.`amount`) AS Sales ,
	 SUM((td.`amount`)*(td.`r_reserve`/100)*(td.`status` = 'A')) AS RollingReserve ,
	 
	 SUM((td.`status` = 'A') * td.`r_bank_trans_fee`) AS BankApproveTransFee , 				
	 SUM((td.`status` != 'A' && td.`td_bank_recieved` = 'yes') * td.`r_bank_trans_fee`) AS BankNonApproveTransFee , 		
	 SUM((td.`amount`)*(td.`r_bank_discount_rate`/100)*(td.`status` = 'A' )) AS BankHighRiskDiscountFees ,	 
	 0 AS BankLowRiskDiscountFees ,  
	 																						 
	 SUM((td.`td_customer_fee`)*(td.`status` = 'A')) AS CustomerTransactionFees, 		
	 
	 SUM(td.`r_merchant_trans_fees`) AS TransactionFees , 								
	 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_merchant_discount_rate`/100)*(td.`status` = 'A')) AS DiscountFees , 
	 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_reseller_discount_rate`/100)*(td.`status` = 'A')) AS ResellerDiscountFees, 
	 SUM((td.`r_reseller_trans_fees`)*(td.`status` = 'A')) AS ResellerTransactionFees, 
	 SUM((tc.`amount`-tc.`td_customer_fee`)*(tc.`r_reserve`/100)*(tc.`status` = 'A'&& tc.`cancelstatus` = 'Y')) AS BankCreditReserveFees ,
	 																					

	 SUM((tc.`cancelstatus` = 'Y') * tc.`amount`) AS RefundAmount,
	 SUM((tc.`td_is_chargeback` = '1') * tc.`amount`) AS ChargeBackAmount,
	 SUM(tc.`cancelstatus` = 'Y') * ($RefundFee) AS BankRefundFees , SUM(tc.`cancelstatus` = 'Y') AS BankRefund_Num ,
	 SUM(tc.`td_is_chargeback` = '1') * ($ChargebackFee) AS BankChargebackFees , 	
	 
	 SUM((tc.`cancelstatus` = 'Y') * (tc.`r_credit`)) AS RefundFees ,SUM(tc.`cancelstatus` = 'Y') AS Refund_Num ,
	 SUM((tc.`td_is_chargeback` = '1') * (tc.`r_chargeback`)) AS ChargebackFees, 		
	 	
		
	 SUM((td.`status` = 'A')) AS Approve_Num ,
	 SUM(td.`status` != 'A') AS Decline_Num,
	 SUM(td.`status` != 'A' && td.`td_bank_recieved` = 'yes') AS BankDecline_Num,
	 SUM((td.`status` = 'A' )) AS BankHighRiskDiscountFees_Num ,
	 0 AS BankLowRiskDiscountFees_Num ,
	 COUNT(td.`r_merchant_trans_fees`) AS Total_Num ,
	 SUM((tc.`status` = 'A'&&(tc.`cancelstatus` = 'Y' || tc.`td_is_chargeback` = '1'))) AS BankCreditReserveFees_Num ,
	 SUM(tc.`td_is_chargeback` = '1') AS BankChargeback_Num,
	 SUM(td.`td_is_chargeback` = '1') AS Chargeback_Num

	  
	 from cs_transactiondetails as t 
left join cs_companydetails as cd on cd.userId = t.userId
left join cs_transactiondetails as td on $sql_paid
and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold' $limit_to_last_hold_sql
left join cs_transactiondetails as tc on $sql_deducted
and t.`transactionId` = tc.`transactionId` and tc.cancellationDate < '$date_delay' $limit_to_last_delay_sql
 WHERE t.bank_id='".$bankInfo['bank_id']."'"; // AND cd.`cd_pay_status`='payable'


	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	$bi_pay_info = mysql_fetch_assoc($result);	
	$bi_pay_info['RangeInfo'] = $RangeInfo;
	
// Misc Fees

	$mf_date = date("Y-m-d",$thisdate);
	$sql = "select SUM(mf_amount) as mf_amount, group_concat(mf_ID) as mf_ID_list from `cs_misc_fees` where mf_entity = '".$bankInfo['bank_id']."' AND mf_date ='$mf_date' AND mf_invoice_type ='bank' AND mf_paid=0";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	$cs_misc_fees = mysql_fetch_assoc($result);	
	if($cs_misc_fees['mf_ID_list'])
	{
		$bi_pay_info['MiscFees'] = $cs_misc_fees['mf_amount'];
		$bi_pay_info['mf_ID_list'] = $cs_misc_fees['mf_ID_list'];
	}
// Merchant Invoice Deductions

	$UsWire = floatval($bankInfo['bk_fee_us_wire']);
	$NonUsWire = floatval($bankInfo['bk_fee_nonus_wire']);
	$PayrollDiscount = floatval($bankInfo['bk_payroll_discount']);
	
	$sql = "SELECT 
	 group_concat( mib.`mib_ID` ) as MInvoiceList,
	 
	 SUM((mib.`mib_wire_type` != 'us') * $NonUsWire+(mib.`mib_wire_type` = 'us') * $UsWire) AS BankCompanyWireFee ,
	 SUM(mib.`mib_balance` *($PayrollDiscount/100)) AS BankPayrollDiscount,
	 SUM(mib.`mib_etelDeduction`) AS EtelDeductions ,
	 SUM(mib.`mib_wire_fee`) AS WireFees ,
	 SUM(mib.`mib_monthly_fee`) AS MonthlyFees ,
	 SUM(mib.`mib_setup_fee`) AS SetupFees ,
	 
	 SUM(mib.`mib_wire_fee`!=0) AS WireFees_Num ,
	 SUM(mib.`mib_monthly_fee`!=0) AS MonthlyFees_Num ,
	 SUM(mib.`mib_setup_fee`!=0) AS SetupFees_Num 
	
	 FROM `cs_merchant_invoice_banksub` as mib WHERE mib_paid=0 AND `mib_bank_id` = '".$bankInfo['bank_id']."'";

	if($calcInfo['includeInvoiceFees'])
	{	
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
		$mib_pay_info = mysql_fetch_assoc($result);
		if(is_array($mib_pay_info)) 
		{
			$bi_pay_info = array_merge($bi_pay_info,$mib_pay_info);
			//mysql_query("update `cs_merchant_invoice_banksub` set mib_paid=1 where mib_paid = 0 AND `mib_bank_id` = '".$bankInfo['bank_id']."'");
		}
	}
	
	$bi_pay_info['Status'] = "";
	$bi_pay_info['pay'] = 0;
	$bi_pay_info['WireFee'] = $UsWire;
	if($calcInfo['estimateMode']) $bi_pay_info['WireFee']=0;
	//  	bk_payment_type
	$bi_pay_info['Deductions'] =
					  $bi_pay_info['BankApproveTransFee'] 
					+ $bi_pay_info['BankNonApproveTransFee'] 
					+ $bi_pay_info['BankHighRiskDiscountFees'] 
					+ $bi_pay_info['BankLowRiskDiscountFees'] 
					+ $bi_pay_info['BankRefundFees'] 
					+ $bi_pay_info['BankChargebackFees']
					+ $bi_pay_info['BankCompanyWireFee']
					+ $bi_pay_info['BankPayrollDiscount'];
					
	$bi_pay_info['Profit'] =
					  $bi_pay_info['CustomerTransactionFees'] 
					+ $bi_pay_info['TransactionFees'] 
					+ $bi_pay_info['DiscountFees'] 
					+ $bi_pay_info['RefundFees'] 
					+ $bi_pay_info['ChargebackFees']
					+ $bi_pay_info['EtelDeductions']
					+ $bi_pay_info['MiscFees'];
										
	$bi_pay_info['ProfitMode']['all'] = $bi_pay_info['Sales'] + $bi_pay_info['MiscFees'] - ($bi_pay_info['Deductions'] + $bi_pay_info['RefundAmount'] + $bi_pay_info['RollingReserve'] + $bi_pay_info['BankCreditReserveFees']);
	$bi_pay_info['ProfitMode']['profit'] = $bi_pay_info['Profit'] - ($bi_pay_info['Deductions']);
	$bi_pay_info['ProfitMode']['realprofit'] = $bi_pay_info['ProfitMode']['profit']-$bi_pay_info['ResellerDiscountFees']-$bi_pay_info['ResellerTransactionFees'];
	 
	$bk_payment_type = 'profit';
	if($bankInfo['bk_payment_type']) $bk_payment_type = $bankInfo['bk_payment_type'];
	
	if($bk_payment_type == 'realprofit')
	{	
		$bi_pay_info['DiscountFees'] -= $bi_pay_info['ResellerDiscountFees'];
		$bi_pay_info['DiscountFees'] -= $bi_pay_info['ResellerTransactionFees'];
		$bi_pay_info['BankCreditReserveFees']=0;
	}
	if($bk_payment_type == 'profit')
	{	
		$bi_pay_info['BankCreditReserveFees']=0;
		$bi_pay_info['ResellerDiscountFees']=0;
		$bi_pay_info['ResellerTransactionFees']=0;
	}
	if($bk_payment_type == 'all')
	{
		$bi_pay_info['Deductions'] += $bi_pay_info['RefundAmount'];
		$bi_pay_info['Profit'] = $bi_pay_info['Sales'];
		$bi_pay_info['CustomerTransactionFees'] = 0; 
		$bi_pay_info['TransactionFees']  = 0;
		$bi_pay_info['DiscountFees']  = 0;
		$bi_pay_info['RefundFees']  = 0;
		$bi_pay_info['ChargebackFees'] = 0;
		$bi_pay_info['EtelDeductions'] = 0;
		$bi_pay_info['Total_Num'] = 0;
		$bi_pay_info['Deductions'] += $bi_pay_info['RollingReserve']+$bi_pay_info['BankCreditReserveFees'];
		$bi_pay_info['BankRollingReserveFees'] = $bi_pay_info['RollingReserve'];
		$bi_pay_info['ResellerDiscountFees']=0;
		$bi_pay_info['ResellerTransactionFees']=0;
		
	}
	
	
	//$bi_pay_info['TotalProfit'] = $bi_pay_info['Profit'] - $bi_pay_info['Deductions'];
	$bi_pay_info['TotalProfit'] = $bi_pay_info['ProfitMode'][$bk_payment_type];
	
	if($bi_pay_info['TotalProfit'] == 0)
	{
		$bi_pay_info['Status'] = "Nothing";
		$bi_pay_info['WireFee'] = "N/A";
	}
	else if($bi_pay_info['TotalProfit'] - $bi_pay_info['WireFee'] < $bankInfo['bk_rollover']) 
	{
		$bi_pay_info['Status'] = "Rollover";
		$bi_pay_info['WireFee'] = "N/A";
	}
	else
	{
		$bi_pay_info['Status'] = "Payable";
		$bi_pay_info['TotalProfit'] -= $bi_pay_info['WireFee'];
		if($payableToday) $bi_pay_info['pay'] = 1;
		else $bi_pay_info['Status'] = "Payable, but not a Payday";
	}
	$bi_pay_info['date_hold'] = $date_hold;
	$bi_pay_info['date_delay'] = $date_delay;
	$bi_pay_info['bank_id'] = $bankInfo['bank_id'];
	$bi_pay_info['bank_name'] = $bankInfo['bank_name'];
	$date_hold = 0;
	$date_delay = 0;
	$limit_to_last_hold = 0;
	$limit_to_last_delay = 0;
	$thisdate=0;
	//unset($forceProfitView);
	foreach($bi_pay_info as $key=>$data)
		if(is_numeric($bi_pay_info[$key])) $bi_pay_info[$key]=round($data,2);
	return $bi_pay_info;

}

function updateCompanyPayStatus()
{
	return 0;
	global $companyInfo;
	$time = microtime_float();
	$log = "Running a search for all live companys for Bank #$bankId\n";
	$company_list_sql = "Select cd.* from `cs_companydetails` as cd left join `cs_transactiondetails` as t on t.userId = cd.userId WHERE transactionId is not NULL group by companyname";
	$result = mysql_query($company_list_sql) or dieLog(mysql_error()." ~ $company_list_sql");
	while($companyInfo = mysql_fetch_assoc($result))
	{
		$mi_pay_info=calcReal(time()+600000);
		$cd_pay_status = 'unpayable';
		$balance = $mi_pay_info['Gross'] - $mi_pay_info['Deductions'];
		if($balance>0)
		{
			$cd_pay_status = 'payable';
		}
		mysql_query("Update `cs_companydetails` set `cd_pay_status`='$cd_pay_status' WHERE userId = '".$companyInfo['userId']."'");
		$log .= "  ".$companyInfo['companyname']."=$cd_pay_status, balance=$balance\n";
	}
	
	$time = microtime_float();
	$log .= mysql_num_rows($result)."  Companys Updated in $time Seconds.\n";
	toLog('misc','system',$log);

}

function reverseBankInvoice($bi_ID)
{
	$sql = "SELECT * FROM `cs_bank_invoice` WHERE `bi_ID` = '$bi_ID' ";

	$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
	if (!mysql_num_rows($result)) return 0; 

	$sql = "UPDATE `cs_transactiondetails` set `td_bank_paid` = 0 WHERE `td_bank_paid` ='$bi_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$sql = "UPDATE `cs_transactiondetails` set `td_bank_deducted` = 0 WHERE `td_bank_deducted` ='$bi_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$sql = "UPDATE `cs_merchant_invoice_banksub` set `mib_paid` = 0 WHERE `mib_paid` ='$bi_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$sql = "DELETE FROM `cs_bank_invoice` WHERE `bi_ID` = '$bi_ID' ";
	$result = mysql_query($sql) or dieLog(mysql_error());
		
	$sql = "update `cs_misc_fees` set mf_paid=0 where  mf_paid ='$bi_ID' AND mf_invoice_type ='bank'";
	$result=mysql_query($sql) or dieLog(mysql_error()." ~ $sql");

	return 1;
}

function reverseCompanyInvoice($mi_ID)
{
	$sql = "SELECT * FROM `cs_merchant_invoice` WHERE mi_ID ='$mi_ID' ";

	$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
	if (!mysql_num_rows($result)) return 0; 
	$cs_merchant_invoice = mysql_fetch_assoc($result);
	$mi_pay_info = @unserialize($cs_merchant_invoice['mi_pay_info']);
	
	//$sql = "INSERT INTO `cs_misc_fees` set `mf_entity`= '".$cs_merchant_invoice['mi_company_id']."', `mf_amount`= '".-floatval($mi_pay_info['MonthlyFee'])."', `mf_date`= '".$cs_merchant_invoice['mi_paydate']."', `mf_invoice_type` ='merchant',  `mf_description`='Monthly Fee'";
	if(floatval($mi_pay_info['MonthlyFee'])) 
	{
		$monthly_sql = "cd_last_paid_monthlyfee= SUBDATE(cd_last_paid_monthlyfee, Interval 1 Month),";
		
	}
	//$sql = "INSERT INTO `cs_misc_fees` set `mf_entity`= '".$cs_merchant_invoice['mi_company_id']."', `mf_amount`= '".-floatval($mi_pay_info['SetupFee'])."', `mf_date`= '".$cs_merchant_invoice['mi_paydate']."', `mf_invoice_type` ='merchant',  `mf_description`='Setup Fee'";
	if(floatval($mi_pay_info['SetupFee'])!= 0) 
	{
		$setup_sql = "cd_paid_setup_fee=0,";
	}

	$sql = "UPDATE `cs_companydetails` SET $setup_sql $monthly_sql `cd_next_pay_day` = '".$cs_merchant_invoice['mi_paydate']."' WHERE `userId` ='".$cs_merchant_invoice['mi_company_id']."' LIMIT 1 ";
	mysql_query($sql) or dieLog(mysql_error());

	$sql = "UPDATE `cs_transactiondetails` set `td_merchant_paid`=0 WHERE `td_merchant_paid` =$mi_ID";
	mysql_query($sql) or dieLog(mysql_error());
	$sql = "UPDATE `cs_transactiondetails` set `td_merchant_deducted`=0 WHERE `td_merchant_deducted` =$mi_ID";
	mysql_query($sql) or dieLog(mysql_error());
	$sql = "DELETE FROM `cs_merchant_invoice` where mi_ID ='$mi_ID' limit 1";
	mysql_query($sql) or dieLog(mysql_error());
	$sql = "DELETE FROM `cs_merchant_invoice_banksub` where mib_mi_ID ='$mi_ID'";
	mysql_query($sql) or dieLog(mysql_error());
		
	$sql = "update `cs_misc_fees` set mf_paid=0 where  mf_paid ='$mi_ID' AND mf_invoice_type ='merchant'";
	$result=mysql_query($sql) or dieLog(mysql_error()." ~ $sql");

	return 1;
}

function updateBankInvoice($time = 0)
{
	global $cnn_cs;
	global $bankInfo;
	global $date_hold;
	global $date_delay;
	global $includeInvoiceFees;
	if(!$time) $time = time();

	$sql = "SELECT * FROM `cs_bank_invoice` WHERE `bi_bank_id` = '".$bankInfo['bank_id']."' AND `bi_date` = curdate()";

	$result = mysql_query($sql,$cnn_cs);
	if (mysql_num_rows($result)>=1) 
	{
		$cs_bank_invoice = mysql_fetch_assoc($result);
		$bi_pay_info = @unserialize($cs_bank_invoice['bi_pay_info']);
		$bi_pay_info['bi_ID'] = $cs_bank_invoice['bi_ID'];
		return $bi_pay_info;
	}
 	
	$calcInfo['includeInvoiceFees'] = true;
	$bi_pay_info = calcBankReal($time,$calcInfo);
	
	$date_hold = $bi_pay_info['date_hold'];
	$date_delay = $bi_pay_info['date_delay'];
	
	$calcInfo['includeInvoiceFees'] = false;
	if(!$bi_pay_info['pay']) return $bi_pay_info;
	$bi_pay_info['Status'] = "Paid";
	
	$bi_transactions = $bi_pay_info['TransactionList'];
	$bi_deductions = $bi_pay_info['DeductionsList'];
	$bi_mib_ID_list = $bi_pay_info['MInvoiceList'];
	unset($bi_pay_info['TransactionList']);

	unset($bi_pay_info['DeductionsList']);
	unset($bi_pay_info['MInvoiceList']);

// Create an Invoice
	$invoiceName = date("l, F j, Y")." - ".$bankInfo['bank_name']." - $".formatMoney($bi_pay_info['TotalProfit']);
	$invoice_sql = "INSERT INTO `cs_bank_invoice` 
	(`bi_mib_ID_list`, `bi_transactions`,`bi_deductions`,`bi_title`, `bi_bank_id` , `bi_date` , `bi_balance` , `bi_deduction` , `bi_pay_info` , `bi_bank_info`, `bi_notes` )
VALUES (
'$bi_mib_ID_list', '$bi_transactions','$bi_deductions','$invoiceName', '".$bankInfo['bank_id']."', '".date('y-m-d g:i:s',$time)."' , '".$bi_pay_info['TotalProfit']."', '".$bi_pay_info['Deductions']."', '".addslashes(serialize($bi_pay_info))."', '".addslashes(serialize($bankInfo))."', '$bi_notes'
);
";
	$result=mysql_query($invoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
	
	$invoice_id = mysql_insert_id($cnn_cs);
	
// Update Transactions

	$transaction_sql = "UPDATE cs_transactiondetails as t 
left join cs_companydetails as cd on cd.userId = t.userId
left join cs_transactiondetails as td on td.td_bank_paid = 0
and t.`transactionId` = td.`transactionId` and td.transactionDate < '$date_hold'
left join  cs_transactiondetails as tdc ON tdc.td_bank_deducted = 0
and t.`transactionId` = tdc.`transactionId` and tdc.cancellationDate < '$date_delay'
set td.td_bank_paid = $invoice_id, tdc.td_bank_deducted=$invoice_id
WHERE t.bank_id='".$bankInfo['bank_id']."'"; // AND cd.`cd_pay_status`='payable'
 
	$result=mysql_query($transaction_sql,$cnn_cs) or dieLog(mysql_error()." ~ $transaction_sql");

	$sql = "update `cs_merchant_invoice_banksub` as mib 
	set mib_paid=$invoice_id
	WHERE mib_paid=0 AND `mib_bank_id` = '".$bankInfo['bank_id']."'";

	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error()." ~ $sql");

	
// Update Misc Fees

	if($bi_pay_info['mf_ID_list'])
	{
		$sql = "update `cs_misc_fees` set mf_paid=$invoice_id where mf_ID in ('".$bi_pay_info['mf_ID_list']."')";
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
	}
	
		$bi_pay_info['bi_ID'] = $invoice_id;
	return $bi_pay_info;
}

function calcUserProfit($calcInfo=NULL)
{

	$po_user_ID = intval($calcInfo['po_user_ID']);
	if(!$po_user_ID) dieLog("Invalid Admin User $po_user_ID");
	
	$sql = "SELECT *
	FROM `cs_profit_options`
	WHERE `po_user_ID` = $po_user_ID";

	$calcInfo = array();
	$i = 1;

	$result=mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
	while($cs_profit_options = mysql_fetch_assoc($result))
	{
		$key = $cs_profit_options['po_commission_percent'];
		if (!$key) $key = $i++;
		if(!$calcInfo[$key]) 
		{
			$calcInfo[$key] = array();
			$calcInfo[$key]['sql_conditions'] = array();
		}
		
		switch($cs_profit_options['po_limit_type'])
		{
			case "merchant":
				
				if($cs_profit_options['po_limit_by']=='all') $calcInfo[$key]['sql_conditions']['all'] = true;
				else if($cs_profit_options['po_limit_by']=='include') $calcInfo[$key]['sql_conditions']['include_list'][] = intval($cs_profit_options['po_limit_ID']);
				else if($cs_profit_options['po_limit_by']=='exclude') $calcInfo[$key]['sql_conditions']['exclude_list'][] = intval($cs_profit_options['po_limit_ID']);
				else dieLog("Invalid Limit By");
		
				break;
			default:
				dieLog("Invalid Limit Type");
				break;
		}
	}
	
	$total_commission = 0;	
	$ei_pay_info_final = array();
	foreach($calcInfo as $percent => $cInfo)
	{
		$cInfo['description'] = "Commission based on '$percent%' for";
		if($cInfo['sql_conditions']['all']) 
		{
			$cInfo['sql_conditions']['sql'] .= "cd.userId >= 0 ";
			$cInfo['description'] .= " All Merchants";
			if($cInfo['sql_conditions']['exclude_list']) 
			{
				$cInfo['sql_conditions']['sql'] .= " and cd.userId not in (".implode(",",$cInfo['sql_conditions']['exclude_list']).")";
				$cInfo['description'] .= " Except for IDs:".implode(", ",$cInfo['sql_conditions']['exclude_list']);
			}
		}
		else if($cInfo['sql_conditions']['include_list']) 
		{
			$cInfo['sql_conditions']['sql'] .= "cd.userId in (".implode(",",$cInfo['sql_conditions']['include_list']).")";
			$cInfo['description'] .= " The Merchant IDs:".implode(", ",$cInfo['sql_conditions']['include_list']);
		}
		else dieLog("Invalid Merchant Conditions");
		$cInfo['description'].= ".\n";		
				
		$cInfo['percent'] = floatval($percent);	
		$ei_pay_info_final['options'][] = calcUserProfit_option($cInfo);
	}
	foreach($ei_pay_info_final['options'] as $epi)
		$total_commission += $epi['CommissionProfit'];
	$ei_pay_info_final['TotalCommission'] = $total_commission;
	return $ei_pay_info_final;
}

function calcUserProfit_option($calcInfo=NULL)
{
	set_time_limit(300);
	global $cnn_cs;
	global $adminInfo;		
	
	$conditions = $calcInfo['sql_conditions']['sql'];
	
	if(!$conditions || $conditions == "1") dieLog("No Conditions for Profit");
	$sql = "SELECT 
	 b.bank_name, b.bank_id,
	 SUM((td.`status` = 'A') * td.`amount`) AS Sales ,
	 SUM((td.`amount`)*(td.`r_reserve`/100)*(td.`status` = 'A')) AS RollingReserve ,
	 
	 SUM((td.`status` = 'A') * td.`r_bank_trans_fee`) AS BankApproveTransFee , 				
	 SUM((td.`status` != 'A' && td.`td_bank_recieved` = 'yes') * td.`r_bank_trans_fee`) AS BankNonApproveTransFee , 		
	 SUM((td.`amount`)*(td.`r_bank_discount_rate`/100)*(td.`status` = 'A')) AS BankHighRiskDiscountFees ,	 
	 0 AS BankLowRiskDiscountFees ,  
	 																						 
	 SUM((td.`td_customer_fee`)*(td.`status` = 'A')) AS CustomerTransactionFees, 		
	 
	 SUM(td.`r_merchant_trans_fees`) AS TransactionFees , 								
	 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_merchant_discount_rate`/100)*(td.`status` = 'A')) AS DiscountFees , 
	 SUM((td.`amount`-td.`td_customer_fee`)*(td.`r_reseller_discount_rate`/100)*(td.`status` = 'A')) AS ResellerDiscountFees, 
	 SUM((td.`r_reseller_trans_fees`)*(td.`status` = 'A')) AS ResellerTransactionFees, 
	 SUM((tc.`amount`-tc.`td_customer_fee`)*(tc.`r_reserve`/100)*(tc.`status` = 'A'&& tc.`cancelstatus` = 'Y')) AS BankCreditReserveFees ,
	 																					

	 SUM((tc.`cancelstatus` = 'Y') * tc.`amount`) AS RefundAmount,
	 SUM((tc.`td_is_chargeback` = '1') * tc.`amount`) AS ChargeBackAmount,
	 SUM(tc.`cancelstatus` = 'Y') * (b.bk_fee_refund) AS BankRefundFees , SUM(tc.`cancelstatus` = 'Y') AS BankRefund_Num ,
	 SUM(tc.`td_is_chargeback` = '1') * (b.bk_fee_chargeback) AS BankChargebackFees , 	
	 
	 SUM((tc.`cancelstatus` = 'Y') * (tc.`r_credit`)) AS RefundFees ,SUM(tc.`cancelstatus` = 'Y') AS Refund_Num ,
	 SUM((tc.`td_is_chargeback` = '1') * (tc.`r_chargeback`)) AS ChargebackFees, 		
	 	
		
	 SUM((td.`status` = 'A')) AS Approve_Num ,
	 SUM(td.`status` != 'A') AS Decline_Num,
	 SUM(td.`status` != 'A' && td.`td_bank_recieved` = 'yes') AS BankDecline_Num,
	 SUM((td.`status` = 'A')) AS BankHighRiskDiscountFees_Num ,
	 0 AS BankLowRiskDiscountFees_Num ,
	 COUNT(td.`r_merchant_trans_fees`) AS Total_Num ,
	 SUM((tc.`status` = 'A'&&(tc.`cancelstatus` = 'Y' || tc.`td_is_chargeback` = '1'))) AS BankCreditReserveFees_Num ,
	 SUM(tc.`td_is_chargeback` = '1') AS BankChargeback_Num,
	 SUM(td.`td_is_chargeback` = '1') AS Chargeback_Num,
	 
	 bk_total_deposit_actual
	  
	 from cs_transactiondetails as t 
left join cs_companydetails as cd on cd.userId = t.userId
left join cs_bank as b on t.bank_id = b.bank_id
left join cs_transactiondetails as td on  t.`transactionId` = td.`transactionId`
left join cs_transactiondetails as tc on t.`transactionId` = tc.`transactionId` 
 WHERE $conditions and bk_ignore=0 group by b.bank_id";
 
	$bk_result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");

	$ei_pay_info_final = array();

	while($ei_pay_info = mysql_fetch_assoc($bk_result))
	{
		$bank_id = $ei_pay_info['bank_id'];
		
		
	// Misc Fees
	
		$mf_date = date("Y-m-d",$thisdate);
		$sql = "select SUM(mf_amount) as mf_amount, group_concat(mf_ID) as mf_ID_list from `cs_misc_fees` where mf_invoice_type ='bank' and mf_entity = '$bank_id'";
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
		$cs_misc_fees = mysql_fetch_assoc($result);	
		if($cs_misc_fees['mf_ID_list'])
		{
			$ei_pay_info['MiscFees'] = $cs_misc_fees['mf_amount'];
			$ei_pay_info['mf_ID_list'] = $cs_misc_fees['mf_ID_list'];
		}
	// Merchant Invoice Deductions
	
		$sql = "SELECT 
		 
		 SUM((mib.`mib_wire_type` != 'us') * b.bk_fee_nonus_wire+(mib.`mib_wire_type` = 'us') * b.bk_fee_us_wire) AS BankCompanyWireFee ,
		 SUM(mib.`mib_balance` *(b.bk_payroll_discount/100)) AS BankPayrollDiscount,
		 SUM(mib.`mib_etelDeduction`) AS EtelDeductions ,
		 SUM(mib.`mib_wire_fee`) AS WireFees ,
		 SUM(mib.`mib_monthly_fee`) AS MonthlyFees ,
		 SUM(mib.`mib_setup_fee`) AS SetupFees ,
		 
		 SUM(mib.`mib_wire_fee`!=0) AS WireFees_Num ,
		 SUM(mib.`mib_monthly_fee`!=0) AS MonthlyFees_Num ,
		 SUM(mib.`mib_setup_fee`!=0) AS SetupFees_Num 
		
		 FROM `cs_merchant_invoice_banksub` as mib
		 left join cs_companydetails as cd on cd.userId = mib.mib_company_id
		 left join cs_bank as b on mib.mib_bank_id = b.bank_id
		 WHERE $conditions and b.bank_id = '$bank_id'";
		 
		//if($calcInfo['includeInvoiceFees'])
		//{
			$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
			$mib_pay_info = mysql_fetch_assoc($result);
			if(is_array($mib_pay_info)) 
			{
				$ei_pay_info = array_merge($ei_pay_info,$mib_pay_info);
				//mysql_query("update `cs_merchant_invoice_banksub` set mib_paid=1 where mib_paid = 0");
			}
		//}
		
	// Bank Invoice Totals
	
		$sql = "SELECT 
		 
		 sum(bi_balance) as EstimatedBalance
		
		 FROM cs_bank_invoice as bi
		 WHERE bi.bi_bank_id = '$bank_id'";
		 
		$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_error($cnn_cs)." ~ $sql");
		$mi_pay_info = mysql_fetch_assoc($result);
		if(is_array($mi_pay_info)) 
		{
			$ei_pay_info['EstimatedBalance'] = $mi_pay_info['EstimatedBalance'];
		}
		
		$ei_pay_info['Status'] = "";
		//  	bk_payment_type
		$ei_pay_info['Deductions'] =
						  $ei_pay_info['BankApproveTransFee'] 
						+ $ei_pay_info['BankNonApproveTransFee'] 
						+ $ei_pay_info['BankHighRiskDiscountFees'] 
						+ $ei_pay_info['BankLowRiskDiscountFees'] 
						+ $ei_pay_info['BankRefundFees'] 
						+ $ei_pay_info['BankChargebackFees']
						+ $ei_pay_info['BankCompanyWireFee']
						+ $ei_pay_info['BankPayrollDiscount'];
						
		$ei_pay_info['Profit'] =
						  $ei_pay_info['CustomerTransactionFees'] 
						+ $ei_pay_info['TransactionFees'] 
						+ $ei_pay_info['DiscountFees'] 
						+ $ei_pay_info['RefundFees'] 
						+ $ei_pay_info['ChargebackFees']
						+ $ei_pay_info['EtelDeductions']
						+ $ei_pay_info['MiscFees'];
		
		$ei_pay_info['ProfitMode']['profit'] = $ei_pay_info['Profit'] - ($ei_pay_info['Deductions']);
		$ei_pay_info['ProfitMode']['realprofit'] = $ei_pay_info['ProfitMode']['profit']-$ei_pay_info['ResellerDiscountFees']-$ei_pay_info['ResellerTransactionFees'];
		 
		$bk_payment_type = 'realprofit';
		if($bankInfo['bk_payment_type']) $bk_payment_type = $bankInfo['bk_payment_type'];
		
		if($bk_payment_type == 'realprofit')
		{	
			$ei_pay_info['DiscountFees'] -= $ei_pay_info['ResellerDiscountFees'];
			$ei_pay_info['DiscountFees'] -= $ei_pay_info['ResellerTransactionFees'];
			$ei_pay_info['BankCreditReserveFees']=0;
		}
		if($bk_payment_type == 'profit')
		{	
			$ei_pay_info['BankCreditReserveFees']=0;
			$ei_pay_info['ResellerDiscountFees']=0;
			$ei_pay_info['ResellerTransactionFees']=0;
		}
		
		
		//$ei_pay_info['TotalProfit'] = $ei_pay_info['Profit'] - $ei_pay_info['Deductions'];
		$ei_pay_info['TotalProfit'] = $ei_pay_info['ProfitMode'][$bk_payment_type];
		$ei_pay_info['CommissionProfit'] = $ei_pay_info['TotalProfit'] * ($calcInfo['percent']/100.0);
	
		$ei_pay_info['Commission_Percent_Available'] = $ei_pay_info['bk_total_deposit_actual']/$ei_pay_info['EstimatedBalance'];
		if($ei_pay_info['Commission_Percent_Available']>1) $ei_pay_info['Commission_Percent_Available'] = 1;
		if($ei_pay_info['Commission_Percent_Available']<0) $ei_pay_info['Commission_Percent_Available'] = 0;
		if($ei_pay_info['Commission_Percent_Available']==0) $ei_pay_info['Commission_Percent_Available'] = 1;
		
		$ei_pay_info['CommissionProfit_Available'] = $ei_pay_info['CommissionProfit'] * $ei_pay_info['Commission_Percent_Available'];
		
		
		foreach($ei_pay_info as $key=>$data)
			if(is_numeric($ei_pay_info[$key])) $ei_pay_info[$key]=round($data,2);
		
		$ei_pay_info_final['bybank'][] = $ei_pay_info;
	
	}
	$ei_pay_info_final['description'] = $calcInfo['description'];	
	
	foreach($ei_pay_info_final['bybank'] as $ei_pay_bank)
	{
		$ei_pay_info_final['CommissionProfit']+=$ei_pay_bank['CommissionProfit_Available'];
	}
	
	if($ei_pay_info_final['CommissionProfit'] == 0)
	{
		$ei_pay_info_final['Status'] = "Nothing";
		$ei_pay_info_final['WireFee'] = "N/A";
	}
	else if($ei_pay_info_final['CommissionProfit'] <50) 
	{
		$ei_pay_info_final['Status'] = "Rollover";
		$ei_pay_info_final['WireFee'] = "N/A";
	}
	else
	{
		$ei_pay_info_final['Status'] = "Payable";
	}
	return $ei_pay_info_final;

}




?>