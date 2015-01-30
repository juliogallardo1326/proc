<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// reportBottom.php:The page functions for report view company. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
//require_once('includes/function2.php');
$headerInclude="reports";
$periodhead="Ledgers";
include 'includes/header.php';


$tinc = 50;
$curinc = 0;

if($_POST['tinc']) $tinc = intval($_POST['tinc']);
if($_POST['curinc']) $curinc = intval($_POST['curinc']);
if($_POST['next']) $curinc+=$tinc;
if($_POST['last']) $curinc-=$tinc;
if($curinc<0) $curinc=0;

$companyBlocked = $int_get_permission;

$reason ="";
$trans_status_qry = "";
$trans_date_qry = "";
$trans_querycc = "";
$app_aud="";
$app_eur="";
$app_cad="";
$app_usd="";
$app_gbp="";
$tot_aud="";
$tot_eur="";
$tot_cad="";
$tot_usd="";
$tot_gbp="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$crorcq = isset($HTTP_GET_VARS['crorcq'])?$HTTP_GET_VARS['crorcq']:"";
$company_site =(isset($HTTP_GET_VARS['company_site'])?quote_smart($HTTP_GET_VARS['company_site']):"");
$str_firstname =(isset($HTTP_GET_VARS['firstname'])?quote_smart($HTTP_GET_VARS['firstname']):"");
$str_lastname =(isset($HTTP_GET_VARS['lastname'])?quote_smart($HTTP_GET_VARS['lastname']):"");
$str_telephone =(isset($HTTP_GET_VARS['telephone'])?quote_smart($HTTP_GET_VARS['telephone']):"");
$trans_pass =(isset($HTTP_GET_VARS['trans_pass'])?quote_smart($HTTP_GET_VARS['trans_pass']):"");
$trans_nopass =(isset($HTTP_GET_VARS['trans_nopass'])?quote_smart($HTTP_GET_VARS['trans_nopass']):"");
$trans_pending =(isset($HTTP_GET_VARS['trans_ptype'])?quote_smart($HTTP_GET_VARS['trans_ptype']):"");
$trans_canceled =(isset($HTTP_GET_VARS['trans_ctype'])?quote_smart($HTTP_GET_VARS['trans_ctype']):"");
$trans_declined =(isset($HTTP_GET_VARS['trans_dtype'])?quote_smart($HTTP_GET_VARS['trans_dtype']):"");
$trans_pend_checks =(isset($HTTP_GET_VARS['trans_pend_checks'])?quote_smart($HTTP_GET_VARS['trans_pend_checks']):"");
$trans_approved = (isset($HTTP_GET_VARS['trans_atype'])?quote_smart($HTTP_GET_VARS['trans_atype']):"");
$trans_daterange =(isset($HTTP_GET_VARS['daterange'])?quote_smart($HTTP_GET_VARS['daterange']):"");
$trans_period = (isset($HTTP_GET_VARS['period'])?quote_smart($HTTP_GET_VARS['period']):"");
$display_test_transactions = (isset($HTTP_GET_VARS['display_test_transactions'])?quote_smart($HTTP_GET_VARS['display_test_transactions']):"");
$active_subscriptions = (isset($HTTP_GET_VARS['active_subscriptions'])?quote_smart($HTTP_GET_VARS['active_subscriptions']):"");
$untracked_orders = (isset($HTTP_GET_VARS['untracked_orders'])?quote_smart($HTTP_GET_VARS['untracked_orders']):"");

$trans_table_name = "cs_transactiondetails";
if($display_test_transactions == 1) $trans_table_name = "cs_test_transactiondetails";

$trans_recur = (isset($HTTP_GET_VARS['trans_recur'])?quote_smart($HTTP_GET_VARS['trans_recur']):"");
$trans_chargeback = (isset($HTTP_GET_VARS['trans_chargeback'])?quote_smart($HTTP_GET_VARS['trans_chargeback']):"");

$compID = $sessionlogin;
if (!$compID) $compID = -1;

if(($_POST['Submit'] == "Issue Refund") && (!$display_test_transactions))
{
	$transID = intval($_POST['id']);
	$etel_debug_mode = 0;
	etelPrint($transID);
	$msg = exec_refund_request($transID,"Merchant Refund","");
}
if(($_POST['Submit'] == "Cancel Rebill") && (!$display_test_transactions))
{
		$trans = new transaction_class(false);
		$trans->pull_transaction($_POST['id']);
		$status = $trans->process_cancel_request(array("actor"=>'Administrator'));
}

$search_date_type = "transactionDate";

$email = (isset($HTTP_GET_VARS["email"])?quote_smart($HTTP_GET_VARS["email"]):"");
if ($companyBlocked != 1)
{
	$check_number = (isset($HTTP_GET_VARS['check_number'])?quote_smart($HTTP_GET_VARS['check_number']):"");
	$credit_number = (isset($HTTP_GET_VARS['credit_number'])?quote_smart($HTTP_GET_VARS['credit_number']):"");
	$account_number = (isset($HTTP_GET_VARS['account_number'])?quote_smart($HTTP_GET_VARS['account_number']):"");
	$routing_code = (isset($HTTP_GET_VARS['routing_code'])?quote_smart($HTTP_GET_VARS['routing_code']):"");
	$decline_reason=(isset($HTTP_GET_VARS['decline_reasons'])?($HTTP_GET_VARS['decline_reasons']):"");
	$cancel_reason=(isset($HTTP_GET_VARS['cancel_reasons'])?($HTTP_GET_VARS['cancel_reasons']):"");
	$str_type =(isset($HTTP_GET_VARS['type'])?quote_smart($HTTP_GET_VARS['type']):"");
}
else
{
	$check_number = "";
	$credit_number = "";
	$account_number = "";
	$routing_code = "";
	$decline_reason = "";
	$cancel_reason = "";
	$str_type = "";
}

$transactionId = (isset($HTTP_GET_VARS["transactionId"])?quote_smart($HTTP_GET_VARS["transactionId"]):"");
$user_type = (isset($HTTP_GET_VARS['usertype'])?($HTTP_GET_VARS['usertype']):"");
$callcenters = (isset($HTTP_GET_VARS['callcenters'])?($HTTP_GET_VARS['callcenters']):"");
$tsrusers = (isset($HTTP_GET_VARS['tsrusers'])?($HTTP_GET_VARS['tsrusers']):"");

if($sessionlogin!=""||$sessionCompanyUser!="")
{
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_GET_VARS["opt_from_year"])?quote_smart($HTTP_GET_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_GET_VARS["opt_from_month"])?quote_smart($HTTP_GET_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_GET_VARS["opt_from_day"])?quote_smart($HTTP_GET_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_GET_VARS["opt_to_year"])?quote_smart($HTTP_GET_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_GET_VARS["opt_to_month"])?quote_smart($HTTP_GET_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_GET_VARS["opt_to_day"])?quote_smart($HTTP_GET_VARS["opt_to_day"]):$i_to_day);

$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

if($_GET['opt_from_full'])$dateToEnter=$_GET['opt_from_full'];
if($_GET['opt_to_full'])$dateToEnter1=$_GET['opt_to_full'];

  $decline_condition="";
  $cancel_condition ="";
  $i_dec=0;
  $i_cancel=0;
  $str_where_query = "";
  $str_user_qry ="";

	
	if($trans_chargeback){
		$search_date_type = 'cancellationDate';
		if($trans_status_qry == "")
			$trans_status_qry .= " a.td_is_chargeback=1";
		else
			$trans_status_qry .= " or a.td_is_chargeback=1";
	}
	
	$str_or_query = "";
	if($trans_canceled){
		$search_date_type = 'cancellationDate';
		if($str_or_query == ""){
			$str_or_query .= " ( a.cancelstatus='Y'";
		}
		else{
			$str_or_query .= " or a.cancelstatus='Y'";
		}
	}

 if($cancel_reason !=""){
  	for($i_cancel = 0;$i_cancel < count($cancel_reason);$i_cancel++) {
		if($cancel_reason[$i_cancel] !="") {
			if($cancel_condition =="") {
				$cancel_condition = "a.reason ='".$cancel_reason[$i_cancel]."'";
			} else {
				$cancel_condition = $cancel_condition ." or a.reason ='".$cancel_reason[$i_cancel]."'";
			}
		}
	}
  }

  if($decline_reason !=""){
  	for($i_dec = 0;$i_dec < count($decline_reason);$i_dec++) {
		if($decline_reason[$i_dec] !="") {
			if($decline_condition =="") {
				//$decline_condition = "a.declinedReason ='".$decline_reason[$i_dec]."'";
			} else {
				//$decline_condition = $decline_condition ." or a.declinedReason ='".$decline_reason[$i_dec]."'";
			}
		}
	}
  }
		if($trans_daterange == "S" ){
			$trans_date_qry = "a.billingDate between '$dateToEnter' and '$dateToEnter1' ";
		}else {
			$trans_date_qry = "a."."$search_date_type between '$dateToEnter' and '$dateToEnter1' ";
		}
	if($str_telephone == "" && $email == "" && $transactionId == "" && $check_number == "" && $credit_number == "" && $account_number == "" && $routing_code == "")
	{
		$str_where_query .= $trans_date_qry;
	}
	if($crorcq)
	{
	  if($crorcq!="A")
	  {
		  $trans_querycc = " a.checkorcard='$crorcq' ";
		  if($str_type != "A")
		  {
			if($crorcq == "C")
		    {
			  if($str_type == "S")
			  {
				$trans_querycc .= " and a.accounttype='savings' ";
			  }
			  else if($str_type == "C")
			  {
				$trans_querycc .= " and a.accounttype='checking' ";
			  }
		    }
			else if($crorcq == "H")
			{
			  if($str_type == "M")
			  {
				$trans_querycc .= " and a.cardtype='Master' ";
			  }
			  else if($str_type == "V")
			  {
				$trans_querycc .= " and a.cardtype='Visa' ";
			  }
			}
		  }
	  }
	}

	if($trans_querycc != "")
	{
		if($str_where_query == ""){
			$str_where_query .= $trans_querycc;
		}
		else{
			$str_where_query .= " and $trans_querycc";
		}
	}


	if($str_firstname != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.name = '".$str_firstname."'"; 
		}
		else{
			$str_where_query .= " and a.name = '".$str_firstname."'"; 
		}
	}
	
	if($str_lastname != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.surname = '".$str_lastname."'"; 
		}
		else{
			$str_where_query .= " and a.surname = '".$str_lastname."'"; 
		}
	}
	
	if($untracked_orders != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.td_tracking_id is null and td_enable_tracking = 'on' and `status`= 'A' AND `cancelstatus` = 'N' "; 
		}
		else{
			$str_where_query .= " and a.td_tracking_id is null and td_enable_tracking = 'on' and `status`= 'A' AND `cancelstatus` = 'N' "; 
		}
	}
	
	if($active_subscriptions != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.td_recur_processed=0 and a.td_enable_rebill=1 and `status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' "; 
		}
		else{
			$str_where_query .= " and a.td_recur_processed=0 and a.td_enable_rebill=1 and `status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' "; 
		}
	}
	
	if($str_telephone != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.phonenumber = '".$str_telephone."'"; 
		}
		else{
			$str_where_query .= " and a.phonenumber = '".$str_telephone."'"; 
		}
	}
	
	
	if($trans_recur){
		if($trans_status_qry == "")
			$trans_status_qry .= " a.td_enable_rebill=1";
		else
			$trans_status_qry .= " or a.td_enable_rebill=1";
	}


	if($trans_status_qry != ""){
		if($str_or_query == ""){
			$str_or_query .= " (". $trans_status_qry;
		}
		else{
			$str_or_query .= " or ". $trans_status_qry; 
		}
	}
	if($cancel_condition != ""){
		if($str_or_query == ""){
			$str_or_query .= " (". $cancel_condition; 
		}
		else{
			$str_or_query .= " or ".$cancel_condition; 
		}
	}

	if($decline_condition != ""){
		if($str_or_query == ""){
			$str_or_query .= " (". $decline_condition; 
		}
		else{
			$str_or_query .= " or ".$decline_condition; 
		}
	}

	$strApprovalConditions = "";

	if($trans_declined){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or a.status = 'D' ";	 	
		}else{
			$strApprovalConditions .= " a.status = 'D' ";	 	
		}
	}

	if($trans_pending){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or a.status = 'P' ";	 	
		}else{
			$strApprovalConditions .= " a.status = 'P' ";	 	
		}
	}
	
	if($trans_pend_checks){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or a.td_is_pending_check = '1' ";	 	
		}else{
			$strApprovalConditions .= " a.td_is_pending_check = '1' ";	 	
		}
	}
	
	if($trans_approved){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or a.status = 'A' ";	 	
		}else{
			$strApprovalConditions .= " a.status = 'A' ";	 	
		}
	}

	if($strApprovalConditions != ""){
		if($str_or_query == ""){
			$str_or_query .= " (". $strApprovalConditions; 
		}
		else{
			$str_or_query .= " or ". $strApprovalConditions; 
		}
	}


	if ($str_or_query != "") {
		if ($str_where_query == "") {
			$str_where_query .= " $str_or_query )";
		} else {
			$str_where_query .= " and $str_or_query )";
		}
	}
	if($email != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.email ='$email' ";
		}
		else{
			$str_where_query .= " and a.email ='$email' ";
		}
	}

	if($company_site > 0)
	{
		if($str_where_query == "")
		{
			$str_where_query .= " a.td_site_ID ='$company_site' ";
		}
		else{
			$str_where_query .= " AND a.td_site_ID ='$company_site' ";
		}
	}
	
	if($transactionId != ""){
		if($str_where_query == ""){
			$str_where_query .= " a.reference_number = '$transactionId' ";
		}
		else{
			$str_where_query .= " and a.reference_number = '$transactionId' ";
		}
	}	
	
	if($credit_number != ""){
		if($str_where_query != ""){
			$str_where_query .= " and a.cardtype != 'Check' and a.CCnumber = '".etelEnc($credit_number)."' ";
		}else{
			$str_where_query .= " a.cardtype != 'Check' and a.CCnumber = '".etelEnc($credit_number)."' ";
		}
	} else if($check_number != ""){
		if ($account_number == "" || $routing_code == "") {
			$outhtml="y";
			$msgtodisplay="Please enter the account number and bank routing code";
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
		if($str_where_query != ""){
			$str_where_query .= " and a.cardtype = 'Check' and a.CCnumber = '".etelEnc($check_number)."' and a.bankaccountnumber = '$account_number' and a.bankroutingcode = '$routing_code' ";
		}else{
			$str_where_query .= " a.cardtype = 'Check' and a.CCnumber = '".etelEnc($check_number)."' and a.bankaccountnumber = '$account_number' and a.bankroutingcode = '$routing_code' ";
		}
	}
	if($str_where_query != ""){
		$str_where_query .= " and ";
	}
	if(!$trans_period)
	{		
		$dateToEnter="$yyyyCur-$mmCur-$ddCur";
		$dateToEnter1="$yyyyCur-$mmCur-$ddCur 23:59:59";
		$qrt_select_details="select a.status,a.transactionId,a.userId,b.companyname,a.name,a.surname,a.checkorcard,a.cardtype,a.amount,a.transactionDate,a.status,a.cancelstatus,a.td_is_chargeback,b.billingdescriptor,a.reference_number,a.currencytype,a.cardtype,a.td_is_chargeback,b.cc_billingdescriptor,b.ch_billingdescriptor,b.cc_visa_billingdescriptor,b.cc_master_billingdescriptor,b.we_billingdescriptor   from $trans_table_name as a,cs_companydetails as b,cs_rebillingdetails as c where $str_where_query a.userid=b.userid";
		$qrt_total_select ="select sum(IF(a.status='A',a.amount,0)) as approved,sum(a.amount*(a.status='D')) as declined,count(*) as cnt from $trans_table_name as a,cs_companydetails as b where $str_where_query a.userid=b.userid";
	}
	if($trans_period=="p" )
	{
			   
		   if(!isset($trans_querycc))
		   {
				$trans_querycc = "";
		   }
		   $qrt_select_details="select a.status,a.transactionId,a.userId,b.companyname,a.name,a.surname,a.checkorcard,a.cardtype,a.amount,a.transactionDate,a.status,a.cancelstatus,a.td_is_chargeback,b.billingdescriptor,a.reference_number,a.currencytype,a.cardtype,a.td_is_chargeback,b.cc_billingdescriptor,b.ch_billingdescriptor,b.cc_visa_billingdescriptor,b.cc_master_billingdescriptor,b.we_billingdescriptor,a.td_enable_rebill from $trans_table_name as a,cs_companydetails as b where $str_where_query a.userid=b.userid ";
		  $qrt_total_select ="select sum(a.amount*(a.status='A')) as approved,sum(a.amount*(a.status='D')) as declined,count(*) as cnt from $trans_table_name as a,cs_companydetails as b where $str_where_query a.userid=b.userid";
			$qrt_approved_amount=" select sum(a.amount),a.currencytype from $trans_table_name as a,cs_companydetails as b where $str_where_query a.userid=b.userid and a.status='A' and a.cancelstatus='N'"; 
  			 $qrt_total_currselect ="select sum(a.amount),a.currencytype  from $trans_table_name as a,cs_companydetails as b where $str_where_query $str_where_query a.userid=b.userid";

	}
			
 
	if($trans_period=="p")
	{	  
   		$periodhead="Periodic Transaction Report";
	}
	
	$qrt_select_details=$qrt_select_details." $str_user_qry and a.userid=" . $sessionlogin." order by a.transactionId desc limit $curinc, $tinc";
	$qrt_total_select = $qrt_total_select." $str_user_qry and a.userid=" . $sessionlogin." order by a.transactionId desc";
	$qrt_approved_amount = $qrt_approved_amount." $str_user_qry and a.userid=" . $sessionlogin." group by a.currencytype";
	$qrt_currency_totalamount=$qrt_total_currselect." $str_user_qry and a.userid=" . $sessionlogin." group by a.currencytype";

	
	if(!($show_total_val =mysql_query($qrt_total_select)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	
	if(!($show_select_sql =mysql_query($qrt_select_details)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>$qrt_select_details");

	}
	
	else
	{
		if(mysql_num_rows($show_select_sql)==0)
		{
				$outhtml="y";
				$msgtodisplay="No transactions for this period";
				message($msgtodisplay,$outhtml,$headerInclude);									
				//exit();			
		}
	 }
	 $trans_info = mysql_fetch_assoc($show_total_val);
	 $trans_total_approved =  $trans_info['approved'];
	 $trans_total_declined = $trans_info['declined'];
  	 $trans_total_count = $trans_info['cnt'];
	 
	 //procedure to find approved amount

	//procedure to show find the output string
	if($tot_aud!=""){
	 	$str_aud="&nbsp;&nbsp;AUD: " .formatMoney($app_aud,2,'.',',')."/".formatMoney($tot_aud);
	 }else{
	 	$str_aud="";
	 }
	 if($tot_cad!=""){
	 	$str_cad="&nbsp;&nbsp;CAD: " .formatMoney($app_cad,2,'.',',')."/".formatMoney($tot_cad);
	 }else{
	 	$str_cad="";
	 }
	 if($tot_eur!=""){
	 	$str_eur="&nbsp;&nbsp;EUR: " .formatMoney($app_eur,2,'.',',')."/".formatMoney($tot_eur);
	 }else{
	 	$str_eur="";
	 }
	 if($tot_usd!=""){
	 	$str_usd="&nbsp;&nbsp;USD: " .formatMoney($app_usd,2,'.',',')."/".formatMoney($tot_usd);
	 }else{
	 	$str_usd="";
	 }
	 if($tot_gbp!=""){
	 	$str_gbp="&nbsp;&nbsp;GBP: " .formatMoney($app_gbp,2,'.',',')."/".formatMoney($tot_gbp);
	 }else{
	 	$str_gbp="";
	 }

beginTable();	
?>

						<table  cellpadding='0' cellspacing='0' width='100%'  valign="left"  ID='Table1'>
							<tr><td colspan="7"><?=$msg?><br><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><strong>
<?php 	print "&nbsp;Approved: $".formatMoney($trans_total_approved).", Declined: $".formatMoney($trans_total_declined).", Total Records: $trans_total_count"; ?><br>
<?php 	//print "Amount in". $str_aud . $str_cad .$str_eur.$str_gbp.$str_usd   ; ?>
</strong></font></td></tr>
<?php

	$totamount=0;
	$pending=0;
	$pendingAmt=0;
	$approved=0;
	$approvedAmt=0;
	$declined=0;
	$declinedAmt=0;
	$creditcard=0;
	$cheque=0;
	$chequeAmt=0;
	$creditcardAmt=0;
	$i = 0;
	while($show_select_val = mysql_fetch_assoc($show_select_sql)) 
	{
		$transactionInfo=getTransactionInfo($show_select_val['transactionId'],$display_test_transactions);
		$sql = "SELECT service_notes,customer_notes FROM `cs_callnotes` WHERE `transaction_id` ='".$show_select_val['transactionId']."' AND cn_type='refundrequest'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result)>0)
		{
			$refundNoteArray = mysql_fetch_assoc($result);
			$refundReason = $refundNoteArray['customer_notes'];
			$refundNote = $refundNoteArray['service_notes'];
			$refundrequest = 1;
		}
		else $refundrequest = 0;
		
		$is_chargeback=$show_select_val['td_is_chargeback']; 
		$amount=$show_select_val['amount']; 
		$totamount=$totamount+$show_select_val['amount'];
//		$strPassStatus = $show_select_val['td_is_chargeback'];
		$strPendingStatus = $show_select_val['status'];
		$strCancelled = $show_select_val['cancelstatus'];
//		$strCancellReason = $show_select_val[13];
//		$strCancellOther = $show_select_val[14];
		$str_company_type = $show_select_val['company_type'];
		$i_user_id = $show_select_val['userId'];
		
		//$ideclineReason = $show_select_val['declinedReason'];
		if($show_select_val['cardtype'] != 'Check' && $show_select_val['cardtype']=="Visa") $billing_descriptor = $show_select_val['cc_visa_billingdescriptor'];
		if($show_select_val['cardtype'] != 'Check' && $show_select_val['cardtype']=="Master") $billing_descriptor = $show_select_val['cc_master_billingdescriptor'];
		if($show_select_val['cardtype'] == 'Check') $billing_descriptor = $show_select_val['ch_billingdescriptor'];
		if($show_select_val['cardtype'] != 'Web900') $billing_descriptor = $show_select_val['we_billingdescriptor'];
		
		if($ideclineReason == "Transaction Incomplete"){
			$ideclineReason ="";
		}if($ideclineReason!="") {$ideclineReason="(".$ideclineReason.")";}
		$str_user_name = "";
		$str_user_query = "";
		//procedure to show Transaction mode
		$iUserType=$str_company_type;
		if($iUserType == 1) 	
			{
				$str_user_type = "TSR user";
			}
		else if($iUserType == 2)
			{
				$str_user_type = "Call center";	
			}
		else if($iUserType == 3)
			{
				$str_user_type = "Websites";	
			}
		else if($iUserType == 4)
			{
				$str_user_type = "website order";								
			}
		else if($iUserType == 5)
			{
				$str_user_type = "Batch ";								
			}
		else if($iUserType == 6)
			{
				$str_user_type = "Recurring";								
			}
		else if($iUserType == 7)
			{
				$str_user_type = "Rebilling";								
			}
							//}
		else
			$str_user_type = "VT ";

		if ($str_company_type == 1) {
			$str_user_query = "select tsr_first_name, tsr_last_name from cs_tsrusers where tsr_user_id = \"$i_user_id\"";
			echo "user id $i_user_id";
			if(!($result_set =mysql_query($str_user_query)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute $str_user_query");
				exit();
			}
			if (mysql_num_rows($result_set) > 0) {
				$str_user_name = mysql_result($result_set, 0, 0) ." ". mysql_result($result_set, 0, 1);
			}
		} else if ($str_company_type == 2) {
			$str_user_query = "select comany_name from cs_callcenterusers where cc_usersid = $i_user_id";
			if(!($result_set =mysql_query($str_user_query)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query3");
				exit();
			}
			if (mysql_num_rows($result_set) > 0) {
				$str_user_name = mysql_result($result_set, 0, 0);
			}
		}
		if ($str_user_query != "") {
		}
		
		  if($i==0)
		  {
 ?>
			<tr align="center" bgcolor='#CCCCCC' height='30'>
				  <td width='79' class='cl1'><span class="subhd">Reference Number</span></font></td>
				  <td width='74' class='cl1'><span class="subhd">Transaction Mode</span></font></td>
				<? if($_SESSION["sessionlogin_type"] == "tele") { ?>					
                  <td  width='68' class='cl1'><span class="subhd">Call Center User</span></font></td>
                  <td  width='58' class='cl1'><span class="subhd">TSR User</span></font></td>
				<? } ?>
				  <td  width='60' class='cl1'><span class="subhd">First name</span></font></td>
				  <td  width='54' class='cl1'><span class="subhd">Last name</span></font></td>
				  <td  width='55' class='cl1'><span class="subhd">Type</span></font></td>
				  <td width='48' class='cl1'><span class="subhd">Amount</span></font></td>
				<? if($companyInfo['cd_enable_tracking']=='on' && $show_select_val['td_enable_tracking']=='on') { ?>
                  <td width='65' class='cl1'><span class="subhd">Tracking ID Status</span></td>
				<? }?>
				  <td width='117' class='cl1'><span class="subhd">Approval Status</span></td>
				  <td width='61' class='cl1'><span class="subhd">Status</span></td>
				  <td width='125' class='cl1'><span class="subhd">Refund 
                    Reason</span></td>
				  <td width='125' class='cl1'><span class="subhd">Action </span></td>
			</tr>
<?php     } 
		  $i=$i+1;
		  if($show_select_val['cardtype'] == 'Check')
		  {
			 $ctype="Check";
		  }
		  else
		  {
			$ctype="Creditcard";
		  }
		  $str_processingcurency=$show_select_val['currencytype'];
		$str_cardtype=$show_select_val[21];
		if($str_processingcurency==""){
				if($ctype == "CreditCard"){							
						$str_processingcurency=func_get_cardcurrency($str_cardtype,$iCompanyId,$cnn_cs);
					}
				else
						$str_processingcurency='USD';
					}
		  if($show_select_val[7]=="") 
		  {
			  $misc="&nbsp;";
		  } 
		  else 
		  {
			  $misc = $show_select_val[7];
		  }
						  
	

?>
		<form action="" method="post">
<input name="curinc" type="hidden" id="curinc" value="<?=$curinc?>">
<tr height='30'>
			      <td align='center' width='79' class='cl1'><font face='verdana' size='1'><a href="viewreportpage.php?date=<?=$show_select_val[8]?>& id=<?=$transactionInfo['transactionId']?>&test=<?=$display_test_transactions?>" class="link">&nbsp;
                    <?=$show_select_val['reference_number']==""?$show_select_val[1]:$show_select_val['reference_number']?>
                    </a><br>
<?=func_get_date_time_12hr($show_select_val[8])?></font></td>
			<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$str_user_type?></font></td>
			<? if($_SESSION["sessionlogin_type"] == "tele") { ?>
				<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$str_company_type == 2 ? $str_user_name : ""?></font></td>
				<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$str_company_type == 1 ? $str_user_name : ""?></font></td>
			<? } ?>
			<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$show_select_val['name']?></font></td>
			<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$show_select_val['surname']?></font></td>
			<td align='left' class='cl1'><font face='verdana' size='1'>
			<font face='verdana' size='1'> 
              <?=$transactionInfo['cardtype']?> 
              <strong> 
              <?php if($transactionInfo['status']=='A') echo ($transactionInfo['subAcc']['recur_day'] && $transactionInfo['subAcc']['recur_charge']?
		($transactionInfo['td_enable_rebill']?
			($transactionInfo['td_is_a_rebill']?"REBILL<BR>":"Rebilling Enabled<BR>").($transactionInfo['td_recur_next_date']?$transactionInfo['td_recur_next_date']:"")
		:"DISABLED REBILL<BR>")
	:"Not a rebill<BR>");?> 
              </strong> </font>
			  			
			</td>
			      <td align='left' class='cl1'  ><font face='verdana' size='1'>&nbsp;(<?=$str_processingcurency?>)&nbsp;<?=formatMoney($amount)?>
                    </font>&nbsp;</td>
				<? if($companyInfo['cd_enable_tracking']=='on' && $transactionInfo['td_enable_tracking']=='on' && $transactionInfo['status']=='A' && $transactionInfo['cancelstatus'] == 'N') { 
						$track_status = "Deadline: ".date("m-d-y",$transactionInfo['Tracking_Deadline']);
						if(!$transactionInfo['td_tracking_id'])
						{
						  if($transactionInfo['Tracking_Days_Left']<=0)
							$track_status .= "<BR><font color='#FF0000'>Past due.</font>";
						  else
							$track_status .= "<BR>".$transactionInfo['Tracking_Days_Left']." days left.";
						}
						else
						{
							$track_status .= "<BR><a href='".$transactionInfo['td_tracking_link']."'>".$transactionInfo['td_tracking_id']."</a>";
						}
					}
					?>
								<td align='center' class='cl1'>
								<font face='verdana' size='1'><?=$track_status?>&nbsp;</font>
								</td>
								<td align='center' class='cl1'>
								<?php
									if($strPendingStatus == "A")
									{
									echo("<font face='verdana' size='1'>Approved</font>");
									}
									else 
									if($strPendingStatus == "P")
									{
									echo("<font face='verdana' size='1'>Pending</font>");
									}
									else 
									if($strPendingStatus == "D")
									{
									echo("<font face='verdana' size='1' color='red'>Declined  $ideclineReason</font>");
									}
									
									
								?>
								</td>
								<td align='center' class='cl1'>
								<?php
								$transnum=$show_select_val['transactionId'];
								$qry_select="Select status,cancelstatus from $trans_table_name where transactionId=$transnum";
								$res_select =sql_query_read($qry_select) or dieLog(mysql_error() . "<pre>$qry_select</pre>");
								$status=mysql_result($res_select,0,0);
								$strCancelled=mysql_result($res_select,0,1);

								?>
								</td>
								<td align='left' class='cl1'><font face='verdana' size='1'>
								<?php
									if ($strPassStatus == "ND" && $strCancelled == "N"){
										echo("&nbsp;");
									}
									else{
										if($strCancelled != "N")
										{
											 if($strCancellOther != "")
												print("".$strCancellOther."&nbsp;"); 
											 else
												print("".$strCancellReason."&nbsp;"); 
										}
										else
										{
											//echo("&nbsp;");
										}
									} ?>
								<?php if((!$display_test_transactions)&&($strCancelled == "N")&&($strPendingStatus == "A")&&(!$is_chargeback)&&(!$refundrequest)) { 

if($transactionInfo['status']=="A")
{
	if($transactionInfo['cancelstatus']=="Y")
	{
		echo "REFUND<BR>";
		//if(($transactionInfo['status']=="A")) echo "<input name='Submit' type='submit' value='Remove Refund' title='Refund'>";
	}
	else
	{
		if($transactionInfo['td_enable_rebill']=="1") echo "<input name='Submit' type='submit' value='Cancel Rebill' title='Cancel'><br>";
		if(($transactionInfo['status']=="A") && (!$transactionInfo['hasRefundRequest'])) echo "<input name='Submit' type='submit' value='Issue Refund' title='Refund'><br>";
	}
}
//								echo '<input name="Submit" type="submit" value="Request Refund">';
		//						if($show_select_val['td_enable_rebill']=="1") echo '<input name="Submit" type="submit" value="Cancel Subscription">';
								} 
								if($refundrequest && $strCancelled == "N") print "Refund Requested:<BR>$refundNote<BR>$refundReason";
								?>
								<input name="id" type="hidden" value="<?=$show_select_val[1]?>">
								</font>
								</td>
				</tr></form>
<?php
	}
	if(!isset($nextstr))
	{
		$nextstr = "";
	}

?>
<tr><td colspan="13" align="center" height="50"><form name="summaryback" method="post" action="">
<input name="next" type="submit" value="Next <?=$tinc?>">
<input name="last" type="submit" value="Last <?=$tinc?>"><select name="tinc" id="tinc">
  <option value="10" <?=(!strcasecmp($tinc,"10")?"selected":"")?>>Show 10</option>
  <option value="25" <?=(!strcasecmp($tinc,"25")?"selected":"")?>>Show 25</option>
  <option value="50" <?=(!strcasecmp($tinc,"50")?"selected":"")?>>Show 50</option>
  <option value="100" <?=(!strcasecmp($tinc,"100")?"selected":"")?>>Show 100</option>
</select>
<input name="show" type="submit" id="show" value="Show">
<input name="curinc" type="hidden" id="curinc" value="<?=$curinc?>">
</form></td></tr>
</table>


<?php
endTable("Transaction Details");
include("includes/footer.php");
}
?>
