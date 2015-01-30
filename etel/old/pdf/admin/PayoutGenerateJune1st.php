<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// resellerLedger.php:	The admin page functions for viewing the company transactions as a summary. 
if(!$_GET['debug']) $etel_debug_mode = 0;
include("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");

//include("includes/header.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";

$mib_ID=quote_smart($_REQUEST['mib_ID']);
$mi_ID=quote_smart($_REQUEST['mi_ID']);

$bi_ID=quote_smart($_REQUEST['bi_ID']);

$thisdate_id = date("Ymd");
$filename = "ETEL.".$thisdate_id.".PO.Release.csv";
	

if($bi_ID)
{
$bi_ID_list = explode("|",$bi_ID);
	foreach($bi_ID_list as $bi_ID)
	{
		$sql = "update cs_bank_invoice set `bi_download_count` = `bi_download_count`+1 where bi_ID ='$bi_ID'";
		mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
		
		$sql = "select * from cs_bank_invoice where bi_ID ='$bi_ID'";
		$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
		
		$invoiceInfo=mysql_fetch_assoc($inv_details);
		$thisdate_id = date("Ymd",strtotime($invoiceInfo['bi_date']));
	
		if($invoiceInfo['bi_bank_id']==28 || $invoiceInfo['bi_bank_id']==29)
		{
			
			$payID="Etel_".$thisdate_id;
			$invoiceInfo['userId']=0;
			
			$full_cntry = func_get_country("USA",'co_full');
			$char3_cntry = func_get_country("USA",'co_3char');
				
			$invoiceInfo['first_name']= 'Etelegate';
			
			$invoiceInfo['company_bank']='Bank One';
			$invoiceInfo['bank_address']='Phoenix';
			
			$invoiceInfo['bank_city']='Phoenix';
			$invoiceInfo['bank_state']='Arizona';
			$invoiceInfo['bank_zipcode']='85073';
			$invoiceInfo['cd_bank_routingnumber']='122100024';
			$invoiceInfo['bank_account_number']='698829611';
			$invoiceInfo['email']='sales@etelegate.com';
			
			$balance=round($invoiceInfo['bi_balance']*.6,2)+1; // +1 for wireFee
			$paymentMethod = 5;
			if($payout) $payout.="\n";
			$payout.=genInvoice(&$invoiceInfo);
			
					
			$payID="iBill_".$thisdate_id;
			$invoiceInfo['userId']=0;
			
			$full_cntry = func_get_country("USA",'co_full');
			$char3_cntry = func_get_country("USA",'co_3char');
				
			$invoiceInfo['first_name']= 'Interactive Brand Development Inc.';
			
			$invoiceInfo['company_bank']='WACHOVIA BANK, N.A.';
			$invoiceInfo['bank_address']='401 LINDEN STREET';
			
			$invoiceInfo['bank_city']='WINSTON-SALEM';
			$invoiceInfo['bank_state']='NC';
			$invoiceInfo['bank_zipcode']='27150';
			$invoiceInfo['cd_bank_routingnumber']='063000021';
			$invoiceInfo['bank_account_number']='2000028168582';
			$invoiceInfo['email']='sales@iBill.com';
			
			$balance=round($invoiceInfo['bi_balance']*.4,2)+1; // +1 for wireFee
			$paymentMethod = 5;
			if($payout) $payout.="\n";
			$payout.=genInvoice(&$invoiceInfo);
		}
		else
		{
			$payID="Etel_".$thisdate_id;
			$invoiceInfo['userId']=0;
			
			$full_cntry = func_get_country("USA",'co_full');
			$char3_cntry = func_get_country("USA",'co_3char');
				
			$invoiceInfo['first_name']= 'Etelegate';
			
			$invoiceInfo['company_bank']='Bank One';
			$invoiceInfo['bank_address']='Phoenix';
			
			$invoiceInfo['bank_city']='Phoenix';
			$invoiceInfo['bank_state']='Arizona';
			$invoiceInfo['bank_zipcode']='85073';
			$invoiceInfo['cd_bank_routingnumber']='122100024';
			$invoiceInfo['bank_account_number']='698829611';
			$invoiceInfo['email']='sales@etelegate.com';
			
			$balance=$invoiceInfo['bi_balance']+1; // +1 for wireFee
			$paymentMethod = 5;
			if($payout) $payout.="\n";
			$payout.=genInvoice(&$invoiceInfo);
		}
	}
}
$mib_ID = '-1';
$sql = "SELECT mib_ID FROM `cs_merchant_invoice_banksub` as mib 	
left join cs_bank as bk on bk.bank_id=mib.mib_bank_id
left join cs_merchant_invoice as mi on mi.mi_ID=mib.mib_mi_ID	
left join `cs_companydetails` as cs on cs.userId=mib.mib_company_id 
WHERE (((`mi_paydate` = '2006-06-01' and `mib_bank_id` in(30)) OR (`mi_paydate` = '2006-06-15' and `mib_bank_id` in(15,28,29,30)) OR (`mi_paydate` = '2006-06-01' and `mib_bank_id` in(15,28,29) and `mib_balance` <0)) and (userId in (133684,124796,119597,116529,114466,114062,113608,113323,112126,139576,1328) or cs.gateway_id=3)) ";
$result = mysql_query($sql) or die(mysql_error().$sql);
while($id = mysql_fetch_assoc($result))
{
	$mib_ID .= "|".$id['mib_ID'];
}
if($mib_ID)
{
	$mib_ID_list = explode("|",$mib_ID);
	foreach( $mib_ID_list as $mib_ID)
	{
		$mib_wire_type = 'non-us';
		$sql = "SELECT * FROM `cs_merchant_invoice_banksub` as mib 
		left join cs_bank as bk on bk.bank_id=mib.mib_bank_id
		left join cs_merchant_invoice as mi on mi.mi_ID=mib.mib_mi_ID
		left join `cs_companydetails` as cs on cs.userId=mib.mib_company_id WHERE `mib_ID` = '$mib_ID' and `mib_bank_id` in(15,28,29,30)";

		$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());

		if(!mysql_num_rows($inv_details)) continue;
		
		$invoiceInfo=mysql_fetch_assoc($inv_details);
		
		if($invoiceInfo['bank_country']=='US' || $invoiceInfo['bank_country']=='United States') $mib_wire_type = 'us';

		$thisdate_id = date("Ymd",strtotime($invoiceInfo['mi_paydate']));
		$payID=$invoiceInfo['userId']."_".$thisdate_id;
		$wireFee = $invoiceInfo['bk_fee_us_wire'];  	
		if($invoiceInfo['mib_wire_type']== 'non-us') $wireFee = $invoiceInfo['bk_fee_nonus_wire'];
		
		$balance=$invoiceInfo['mib_balance'];//+$wireFee;
		$full_cntry = func_get_country($invoiceInfo['bank_country'],'co_full');
		$char3_cntry = func_get_country($invoiceInfo['bank_country'],'co_3char');
	
		//$filename = "ETEL.".$thisdate_id.".PO.Release.cvs";
		$paymentMethod = 1;
		if($invoiceInfo['mib_wire_type']== 'us') $paymentMethod = 5;
		
		if($merchant[$invoiceInfo['userId']]) $balance += $merchant[$invoiceInfo['userId']]['balance'];
		
		//$payout.=genInvoice(&$invoiceInfo);
		$merchant[$invoiceInfo['userId']]=array('payout'=>genInvoice(&$invoiceInfo),'balance'=>$balance);
	}
}
foreach($merchant as $key=>$data)
{
	if($data['balance']>20)
	{
		if($payout) $payout.="\n";
		$payout.=$data['payout'];
	}
}

function genInvoice($invoiceInfo)
{

	global $thisdate_id;
	global $payID;
	global $wireFee;
	global $balance;
	global $full_cntry;
	global $char3_cntry;
	global $paymentMethod;

	foreach ($invoiceInfo as $key=>$data)
		$invoiceInfo[$key] = quote_smart($data);

	
		
	$payout.="Y,";					// 1	RecordType	C	1 char N,Y,A	See Additional Chart	Y	Y	Y
	$payout.=date("m/d/Y").","; // 2	Release Date	D 	Date	Date funds are released  (blank = no release)	N	N	N
	$payout.=$payID.","; // 3	PaymentID	C	50 chars	User Defined	Y	Y	Y
	$payout.=","; // 4	PaymentReference	C	150 chars	User Defined	N	N	N
	$payout.=($invoiceInfo['userId']).","; // 5	PTSMID	I	9999999999	Payout MemberID	Y	Y	Y
	$payout.=($invoiceInfo['userId']).","; // 6	PTAccountID	I	9999999999	Payout Member Account ID	Y	Y	Y
	$payout.="\"".substr($invoiceInfo['beneficiary_name'],0,50)."\","; // 7	PTPayeeName	C	50 chars	Beneficiary Name	N	Y	Y
	$payout.="\"".$invoiceInfo['company_bank']."\","; // 8	PTBankName	C	100 chars	Beneficiary Bank Name	N	N	Y
	$payout.="\"".$invoiceInfo['bank_address']."\","; // 9	PTBankAddress1	C	100 chars	Beneficiary Bank Address	N	N	Y
	$payout.=$invoiceInfo[''].","; // 10	PTBankAddress2	C	100 chars	Beneficiary Bank Address	N	N	Y
	$payout.="\"".$invoiceInfo['bank_city']."\","; // 11	PTBankCity	C	100 chars	Beneficiary Bank City	N	N	Y
	$payout.="\"".$invoiceInfo['bank_state']."\","; // 12	PTBankStateProvince	C	100 chars	Beneficiary Bank State or Province	N	N	Y
	$payout.=substr($invoiceInfo['bank_zipcode'],0,50).","; // 13	PTBankZipPostalCode	C	50 chars	Beneficiary Bank Postal Code	N	N	Y
	$payout.="\"".substr($full_cntry,0,50)."\","; // 14	PTBankCountry	C	50 chars	Beneficiary Bank Country	N	Y	Y
	$payout.="\"".$char3_cntry."\","; // 15	PTBankCountryISO	C	3 chars	Beneficiary Bank Country ISO	N	Y	Y
	$payout.="$paymentMethod,"; // 16	PTPaymentMethodID	I	1or 5	See Additional Chart	N	Y	Y
	$payout.="USD,"; // 17	PTCurrencyCode	C	3 chars	Currency payment is to be made in 	N	Y	Y
	$payout.="\"".$invoiceInfo['cd_bank_routingcode']."\","; // 18	PTRoutingCodeType	I	9	See Additional Chart	N	Y	Y
	$payout.=substr($invoiceInfo['cd_bank_routingnumber'],0,50).","; // 19	PTRoutingCode	C	50 chars	Beneficiary Bank Routing Number	N	Y	Y
	$payout.=substr($invoiceInfo['bank_account_number'],0,50).","; // 20	PTAccountNumber	C	50 chars	Beneficiary Bank Account Number	N	Y	Y
	$payout.="\"".$invoiceInfo['email']."\","; // 21	PTNotifyEmail	C	150 chars	Email address to notify transaction status	N	Y	Y
	$payout.="\"".$invoiceInfo['email']."\","; // 22	PTAdvisoryEmail	C	150 chars	Email address to notify transaction status	N	N	N
	$payout.="\"".$invoiceInfo['bank_IBName']."\","; // 23	IBName	C	100 chars	Beneficiary Intermediary Bank Name	N	C	C
	$payout.=$invoiceInfo[''].","; // 24	IBAddress1	C	100 chars	Beneficiary Intermediary Bank Address	N	N	C
	$payout.=$invoiceInfo[''].","; // 25	IBAddress2	C	100 chars	Beneficiary Intermediary Bank Address	N	N	C
	$payout.="\"".$invoiceInfo['bank_IBCity']."\","; // 26	IBCity	C	100 chars	Beneficiary Intermediary Bank City	N	N	C
	$payout.="\"".$invoiceInfo['bank_IBState']."\","; // 27	IBStateProvince	C	100 chars	Beneficiary Intermediary Bank State or Province	N	N	C
	$payout.=$invoiceInfo[''].","; // 28	IBZipPostalCode	C	50 chars	Beneficiary Intermediary Bank Postal Code	N	N	C
	if($char3_cntry != 'USA')
	{
		$payout.="\"United States\","; // 29	IBCountry	C	50 chars	Beneficiary Intermediary Bank Country	N	C	C
		$payout.="USA,"; // 30	IBCountryISO	C	3 chars	Beneficiary Intermediary Bank Country ISO	N	C	C
	}
	else
	{
		$payout.=","; // 29	IBCountry	C	50 chars	Beneficiary Intermediary Bank Country	N	C	C
		$payout.=","; // 30	IBCountryISO	C	3 chars	Beneficiary Intermediary Bank Country ISO	N	C	C
	}
	$payout.="\"".$invoiceInfo['bank_IBRoutingCodeType']."\","; // 31	IBRoutingCodeType	C	9	See Additional Chart	N	C	C
	$payout.=$invoiceInfo['bank_IBRoutingCode'].","; // 32	IBRoutingCode	C	50 chars	Beneficiary Intermediary Bank Routing Number	N	C	C
	$payout.=number_format($balance, 2, '.', '').","; // 33	PTTotalAmount	M	99999999.99	Total amount of transaction	Y	Y	Y
	$payout.=number_format($balance, 2, '.', '').","; // 34	PTAmount	M	99999999.99	Amount for Benificiary	Y	Y	Y
	$payout.=$invoiceInfo[''].","; // 35	PTDuplexAmount	M	99999999.99	Amount for Fees	Y	Y	Y
	$payout.=($invoiceInfo['userId']*9+2); // 36	PTDuplexID	I	9999999999	Acccount ID for Fees	Y	Y	Y
	$payout.=",,,,,,,,,,,,,,,,,,,,,";
	$payout.="\"".($invoiceInfo['cd_bank_instructions'])."\"";

	return $payout;
}
	
header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="'.$filename.'"');
print $payout;
die();
	?>