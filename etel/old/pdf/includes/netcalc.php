<?php
	$daterange = " AND `transactionDate` between $drange AND (td_non_unique=0 || status!='D')  and (status!='P' || cardtype='Check')";	
	$daterange_cancel = " AND `cancellationDate` between $drange AND (td_non_unique=0 || status='A') and (status!='P' || cardtype='Check')";
	//print($drange." ");
	if(!$table) $table = "`cs_transactiondetails`";
	//if(!$selection) $selection = "(*`r_reseller_discount_rate`/100)";
	if(!$selection) $selection = "";
	
	if($mercRealCalc) $daterange_cancel = " AND `td_merchant_deducted` = 0 ";
	if($resellerRealCalc) $daterange_cancel = " AND `td_reseller_deducted` = 0 ";
	
  // Start CC/Check/Web900 Stats
  	if($includeCHW){
  		foreach( $CHW_Array as $CHW_i )
		{
  
			$qry_details="SELECT SUM(`amount`-`td_customer_fee`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i' AND `status` = 'A' $daterange";	
			//echo $qry_details . "<br>";
			
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": <b>$qry_details</b><br>".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['NewSales'] = mysql_fetch_assoc($rst_details);
			
			$qry_details="SELECT SUM(`td_customer_fee`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i' AND `status` = 'A' $daterange";	
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['customer_fee'] = mysql_fetch_assoc($rst_details);
					
			$qry_details="SELECT SUM(`amount`-`td_customer_fee`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i' AND `td_is_a_rebill` = 1 AND `status` = 'A' $daterange";	
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['recurring'] = mysql_fetch_assoc($rst_details);
			
			$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i' AND `td_is_chargeback` = '1' $daterange_cancel";	
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['chargebacks'] = mysql_fetch_assoc($rst_details);
			
			$qry_details="SELECT SUM(`amount`-`td_customer_fee`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i'  $daterange";		
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['amountSum'] = mysql_fetch_assoc($rst_details);

			$qry_details="SELECT SUM(`amount`-`td_customer_fee`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i' AND `status` != 'A' $daterange";		
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['declined'] = mysql_fetch_assoc($rst_details);
				
			$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt, SUM(`r_credit`) as ded FROM $table WHERE 1 $siteSQL $compSQL AND `checkorcard` = '$CHW_i'  AND `cancelstatus` = 'Y' $daterange_cancel";		
			$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
			$CHW[$CHW_i]['refundsAmount'] = mysql_fetch_assoc($rst_details);

			$CHW[$CHW_i]['total_cnt'] = 
			$CHW[$CHW_i]['NewSales']['cnt']+
			$CHW[$CHW_i]['declined']['cnt']+
			$CHW[$CHW_i]['recurring']['cnt']+
			$CHW[$CHW_i]['refundsAmount']['cnt']+
			$CHW[$CHW_i]['chargebacks']['cnt'];
			//$CHW[$CHW_i]['total_cnt'] = $CHW[$CHW_i]['total_cnt'];
				
			$CHW[$CHW_i]['total_amt'] = 
			$CHW[$CHW_i]['NewSales']['amt']+
			$CHW[$CHW_i]['declined']['amt']+
			$CHW[$CHW_i]['recurring']['amt']+
			$CHW[$CHW_i]['refundsAmount']['amt']+
			$CHW[$CHW_i]['chargebacks']['amt'];
			
		}
	}
		
  // End CC/Check/Web900 Stats		
		
  // Main Calculation Code Starts
  
  		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>$qry_details");
		$amountSum = mysql_fetch_assoc($rst_details);

  		$qry_details="SELECT SUM(`td_customer_fee`$selection) as amt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'A' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$customer_fee = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'A' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$NewSales = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`amount`$selection) as amt, COUNT(`amount`) as cnt, SUM(`r_chargeback`$selection) as ded FROM $table WHERE 1 $siteSQL $compSQL AND `td_is_chargeback` = '1' $daterange_cancel";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$chargeback = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `td_is_a_rebill` = 1 AND `status` = 'A'  $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$rebillSum = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `td_voided_check` = 1 $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$voidChecks = mysql_fetch_assoc($rst_details);		 
		
		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'P' and checkorcard='C' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$pendChecks = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `td_returned_checks` = 1 $daterange";		
		//$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$returnedChecks = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`amount`$selection) as amt, COUNT(`amount`) as cnt, SUM(`r_credit`$selection) as ded FROM $table WHERE 1 $siteSQL $compSQL AND `cancelstatus` = 'Y' $daterange_cancel";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$refundsAmount = mysql_fetch_assoc($rst_details);
				
		$qry_details="SELECT SUM(`amount`$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `td_is_affiliate` = 1 $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$referalSum = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`$selection) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'D' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$declined = mysql_fetch_assoc($rst_details);
	
// Deductions

		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)*(`r_merchant_discount_rate`/100)$selection) as amt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'A' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$discount = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`r_merchant_trans_fees`$selection) as amt,SUM(`r_reseller_trans_fees`) as resamt FROM $table WHERE 1 $siteSQL $compSQL $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$transactionfee = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`r_reseller_trans_fees`) as resamt FROM $table WHERE 1 $siteSQL $compSQL AND `status` != 'A' $daterange";		
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$declined_transactionfee = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM((`amount`-`td_customer_fee`)*(`r_reserve`/100)$selection) as amt FROM $table WHERE 1 $siteSQL $compSQL AND `status` = 'A' $daterange";			
		$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$reservefee = mysql_fetch_assoc($rst_details);
		 	
		$TotalDeductions = $chargeback['ded']+$refundsAmount['ded']+$discount['amt']+$transactionfee['amt']+$reservefee['amt'];	
		//$Gross = $NewSales['amt']+$rebillSum['amt']+$referalSum['amt'];
		$Gross = $NewSales['amt'];
		$Gross = $Gross-$chargeback['amt']-$voidChecks['amt']-$returnedChecks['amt']-$refundsAmount['amt'];
		$Net = $Gross-$TotalDeductions;
		
	//print($daterange."-".$Net."<br>");
		$total_amount = $amountSum['cnt']/100;
		if ($total_amount <= 0) $total_amount = 1;
		
  // Main Calculation Code Ends

  ?>