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
$etel_debug_mode = 0;
include("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");

//include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

$bi_ID=quote_smart($_REQUEST['bi_ID']);



$sql = "update cs_bank_invoice set `bi_download_count` = `bi_download_count`+1 where bi_ID ='$bi_ID'";
mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());

$sql = "select * from cs_bank_invoice where bi_ID ='$bi_ID'";
$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());

$invoiceInfo=mysql_fetch_assoc($inv_details);

foreach ($invoiceInfo as $key=>$data)
	$invoiceInfo[$key] = quote_smart($data);
$cust_cntry = func_get_country("USA",'co_full');
		
$thisdate_id = date("Ymd");

$payout.="Y,";					// 1	RecordType	C	1 char N,Y,A	See Additional Chart	Y	Y	Y
$payout.=date("m/d/Y").","; // 2	Release Date	D 	Date	Date funds are released  (blank = no release)	N	N	N
$payout.="Etel_".$thisdate_id.","; // 3	PaymentID	C	50 chars	User Defined	Y	Y	Y
$payout.=","; // 4	PaymentReference	C	150 chars	User Defined	N	N	N
$payout.=","; // 5	PTSMID	I	9999999999	Payout MemberID	Y	Y	Y
$payout.=","; // 6	PTAccountID	I	9999999999	Payout Member Account ID	Y	Y	Y
$payout.="'Etelegate',"; // 7	PTPayeeName	C	50 chars	Beneficiary Name	N	Y	Y
$payout.="'Bank One',"; // 8	PTBankName	C	100 chars	Beneficiary Bank Name	N	N	Y
$payout.="Phoenix"; // 9	PTBankAddress1	C	100 chars	Beneficiary Bank Address	N	N	Y
$payout.=","; // 10	PTBankAddress2	C	100 chars	Beneficiary Bank Address	N	N	Y
$payout.="Phoenix,"; // 11	PTBankCity	C	100 chars	Beneficiary Bank City	N	N	Y
$payout.="Arizona,"; // 12	PTBankStateProvince	C	100 chars	Beneficiary Bank State or Province	N	N	Y
$payout.="85073,"; // 13	PTBankZipPostalCode	C	50 chars	Beneficiary Bank Postal Code	N	N	Y
$payout.=$cust_cntry.","; // 14	PTBankCountry	C	50 chars	Beneficiary Bank Country	N	Y	Y
$payout.="'".$invoiceInfo['bank_country']."',"; // 15	PTBankCountryISO	C	3 chars	Beneficiary Bank Country ISO	N	Y	Y
$payout.="WIRE,"; // 16	PTPaymentMethodID	I	1or 5	See Additional Chart	N	Y	Y
$payout.="USD,"; // 17	PTCurrencyCode	C	3 chars	Currency payment is to be made in 	N	Y	Y
$payout.=$invoiceInfo['cd_bank_routingtype'].","; // 18	PTRoutingCodeType	I	9	See Additional Chart	N	Y	Y
$payout.="122100024,"; // 19	PTRoutingCode	C	50 chars	Beneficiary Bank Routing Number	N	Y	Y
$payout.="698829611,"; // 20	PTAccountNumber	C	50 chars	Beneficiary Bank Account Number	N	Y	Y
$payout.="'sales@etelegate.com',"; // 21	PTNotifyEmail	C	150 chars	Email address to notify transaction status	N	Y	Y
$payout.="'sales@etelegate.com',"; // 22	PTAdvisoryEmail	C	150 chars	Email address to notify transaction status	N	N	N
$payout.=$invoiceInfo[''].","; // 23	IBName	C	100 chars	Beneficiary Intermediary Bank Name	N	C	C
$payout.=$invoiceInfo[''].","; // 24	IBAddress1	C	100 chars	Beneficiary Intermediary Bank Address	N	N	C
$payout.=$invoiceInfo[''].","; // 25	IBAddress2	C	100 chars	Beneficiary Intermediary Bank Address	N	N	C
$payout.=$invoiceInfo[''].","; // 26	IBCity	C	100 chars	Beneficiary Intermediary Bank City	N	N	C
$payout.=$invoiceInfo[''].","; // 27	IBStateProvince	C	100 chars	Beneficiary Intermediary Bank State or Province	N	N	C
$payout.=$invoiceInfo[''].","; // 28	IBZipPostalCode	C	50 chars	Beneficiary Intermediary Bank Postal Code	N	N	C
$payout.="'".$cust_cntry."',"; // 29	IBCountry	C	50 chars	Beneficiary Intermediary Bank Country	N	C	C
$payout.=$cust_cntry.","; // 30	IBCountryISO	C	3 chars	Beneficiary Intermediary Bank Country ISO	N	C	C
$payout.=$invoiceInfo[''].","; // 31	IBRoutingCodeType	C	9	See Additional Chart	N	C	C
$payout.=$invoiceInfo[''].","; // 32	IBRoutingCode	C	50 chars	Beneficiary Intermediary Bank Routing Number	N	C	C
$payout.=formatMoney($invoiceInfo['bi_balance']).","; // 33	PTTotalAmount	M	99999999.99	Total amount of transaction	Y	Y	Y
$payout.=$invoiceInfo[''].","; // 34	PTAmount	M	99999999.99	Amount for Benificiary	Y	Y	Y
$payout.=$invoiceInfo[''].","; // 35	PTDuplexAmount	M	99999999.99	Amount for Fees	Y	Y	Y
$payout.=","; // 36	PTDuplexID	I	9999999999	Acccount ID for Fees	Y	Y	Y

	
$date = date("ymd");
header('Content-type: text/plain');
header('Content-Disposition: attachment; filename="etel_'.$date.'_payout.txt"');
print $payout;
die();
	?>