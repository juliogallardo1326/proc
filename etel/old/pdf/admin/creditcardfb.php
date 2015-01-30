<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//creditcard.php:	The page functions for entering the creditcard details.
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';

require_once( '../includes/function.php');
include '../includes/function1.php';
include '../includes/function2.php';

$companyid = (isset($HTTP_POST_VARS['hid_company_id'])?quote_smart($HTTP_POST_VARS['hid_company_id']):"");
$firstname = (isset($HTTP_POST_VARS['firstname'])?quote_smart($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?quote_smart($HTTP_POST_VARS['lastname']):"");
$td_username= (isset($HTTP_POST_VARS['td_username'])?quote_smart($HTTP_POST_VARS['td_username']):"");
$td_password= (isset($HTTP_POST_VARS['td_password'])?quote_smart($HTTP_POST_VARS['td_password']):"");
$address= (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
$otherstate =  (isset($HTTP_POST_VARS['otherstate'])?quote_smart($HTTP_POST_VARS['otherstate']):"");
$zipcode= (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");
$phone =(isset($HTTP_POST_VARS['telephone'])?quote_smart($HTTP_POST_VARS['telephone']):"");
$td_bank_number =(isset($HTTP_POST_VARS['td_bank_number'])?quote_smart($HTTP_POST_VARS['td_bank_number']):"");
$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
$number= (isset($HTTP_POST_VARS['number'])?quote_smart($HTTP_POST_VARS['number']):"");
$cvv2= (isset($HTTP_POST_VARS['cvv2'])?quote_smart($HTTP_POST_VARS['cvv2']):"");
$cardtype= (isset($HTTP_POST_VARS['cardtype'])?quote_smart($HTTP_POST_VARS['cardtype']):"");
$mm= (isset($HTTP_POST_VARS['mm'])?quote_smart($HTTP_POST_VARS['mm']):"");
if($mm < 10) $mm = "0".$mm;
$yyyy= (isset($HTTP_POST_VARS['yyyy'])?quote_smart($HTTP_POST_VARS['yyyy']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?quote_smart($HTTP_POST_VARS["opt_bill_year"]):date("Y"));
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?quote_smart($HTTP_POST_VARS["opt_bill_month"]):date("m"));
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?quote_smart($HTTP_POST_VARS["opt_bill_day"]):date("d"));
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?quote_smart($HTTP_POST_VARS['authorizationno']):"");			
$shipping= (isset($HTTP_POST_VARS['shippingno'])?quote_smart($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?quote_smart($HTTP_POST_VARS['securityno']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?quote_smart($HTTP_POST_VARS['licensestate']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?quote_smart($HTTP_POST_VARS['driverlicense']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?quote_smart($HTTP_POST_VARS['misc']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?quote_smart($HTTP_POST_VARS['domain1']):"");
$str_3DS = (isset($HTTP_POST_VARS['securepin'])?quote_smart($HTTP_POST_VARS['securepin']):"");
$mt_prod_desc=(isset($HTTP_POST_VARS['productdescription'])?quote_smart($HTTP_POST_VARS['productdescription']):"");
$socialno=(isset($HTTP_POST_VARS['securityno'])?quote_smart($HTTP_POST_VARS['securityno']):"");
$site_id = (isset($HTTP_POST_VARS['selectSite'])?quote_smart($HTTP_POST_VARS['selectSite']):"");
$testmode = (isset($HTTP_POST_VARS['testmode'])?quote_smart($HTTP_POST_VARS['testmode']):"");
if($testmode) $testmode = "Test"; else $testmode = "Live";
$ipaddress = GetHostByName($_SERVER["REMOTE_ADDR"]); 
$dateOfBirth="";
$validupto="$yyyy/$mm";

$i_return_url = 'virtualterminal.php';

	$qrt_select_company = "Select companyname,transaction_type,cc_billingdescriptor,email,send_mail,send_ecommercemail,bank_Creditcard,bank_shopId,bank_Username,bank_Password,sdateofbirth from cs_companydetails where userid='$companyid'";
	if(!($show_sql_run = mysql_query($qrt_select_company)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		$msgdisplay + "Can't find company.";
		toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		$return_message = "INT";
		exit();
	}
	else
	{
		$company_name = mysql_result($show_sql_run,0,0);
		$transaction_type = mysql_result($show_sql_run,0,1);
		$billingdescriptor = "";
		$fromaddress = mysql_result($show_sql_run,0,3);
		$send_mails = mysql_result($show_sql_run,0,4);
		$send_ecommercemail = mysql_result($show_sql_run,0,5);
		$bank_CreditcardId = mysql_result($show_sql_run,0,6);
		$bank_shopId 	= mysql_result($show_sql_run,0,7);
		$bank_Username 	= mysql_result($show_sql_run,0,8);
		$bank_Password 	= mysql_result($show_sql_run,0,9);
		$dateOfBirth 	=  mysql_result($show_sql_run,0,10);
	}

		$transInfo = "";
		$transInfo['transactionId']='';
		$transInfo['Invoiceid']='';
		$transInfo['transactionDate']=$dateToEnter;
		$transInfo['name']=$firstname;
		$transInfo['surname']=$lastname;
		$transInfo['phonenumber']=$phone;
		$transInfo['td_bank_number']=$td_bank_number;
		$transInfo['address']=$address;
		$transInfo['CCnumber']=$number;
		$transInfo['cvv']=$cvv2;
		$transInfo['checkorcard']="H";
		$transInfo['country']=$country;
		$transInfo['city']=$city;
		$transInfo['state']=$state;
		$transInfo['otherstate']=$otherstate;
		$transInfo['zipcode']=$zipcode;
		$transInfo['amount']=$amount;
		$transInfo['memodet']='';
		$transInfo['signature']='';
		$transInfo['bankname']='';
		$transInfo['bankroutingcode']='';
		$transInfo['bankaccountnumber']='';
		$transInfo['accounttype']='';
		$transInfo['misc']=$misc;
		$transInfo['email']=$email;
		$transInfo['cancelstatus']='N';
		$transInfo['status']='';
		$transInfo['userId']=$companyid;
		$transInfo['Checkto']='';
		$transInfo['cardtype']=$cardtype;
		$transInfo['checktype']='';
		$transInfo['validupto']=$validupto;
		$transInfo['reason']='';
		$transInfo['other']='';
		$transInfo['ipaddress']=$ipaddress;
		$transInfo['cancellationDate']='';
		$transInfo['voiceAuthorizationno']='-1';
		$transInfo['shippingTrackingno']=$shipping;
		$transInfo['socialSecurity']=$socialno;
		$transInfo['driversLicense']=$licenceno;
		$transInfo['billingDate']=$setbilldate;
		$transInfo['passStatus']='PA';
		$transInfo['chequedate']='';
		$transInfo['pass_count']='0';
		$transInfo['approvaldate']='';
		$transInfo['nopasscomments']='';
		$transInfo['licensestate']=$licensestate;
		$transInfo['approval_count']='0';
		$transInfo['declinedReason']='';
		$transInfo['service_user_id']='0';
		$transInfo['admin_approval_for_cancellation']='';
		$transInfo['company_usertype']='4';
		$transInfo['company_user_id']=$companyid;;
		$transInfo['callcenter_id']='0';
		$transInfo['productdescription']=$mt_prod_desc;
		$transInfo['reference_number']='';
		$transInfo['currencytype']="USD";
		$transInfo['cancel_refer_num']='0';
		$transInfo['cancel_count']='0';
		$transInfo['return_url']=$i_return_url;
		$transInfo['from_url']=$from_url;
		$transInfo['bank_id']=$bank_CreditcardId;
		$transInfo['td_rebillingID']=$rd_subaccount;
		$transInfo['td_is_a_rebill']='0';
		$transInfo['td_enable_rebill']=$td_enable_rebill;
		$transInfo['td_voided_check']='0';
		$transInfo['td_returned_checks']='0';
		$transInfo['td_site_ID']=$site_id;
		$transInfo['payment_schedule']=$_SESSION['payment_schedule'];
		$transInfo['nextDateInfo']=$_SESSION['nextDateInfo'];
		
		$transInfo['td_is_affiliate']='0';
		$transInfo['td_is_pending_check']='0';
		$transInfo['td_is_chargeback']='0';
		$transInfo['td_recur_processed']='0';
		$transInfo['td_recur_next_date']=$td_recur_next_date;
		$transInfo['td_username']=$td_username;
		$transInfo['td_password']=$td_password;
		$transInfo['td_product_id']=$td_product_id;
		
		include("../includes/integration.php");
		$etel_fraud_limit = 8.5;
		$response = execute_transaction(&$transInfo,$testmode);
			$postback = "";
		if($response['status']=='A') $return_message = "SUC";
		else
		{
			foreach($HTTP_POST_VARS as $k => $c)
				$postback.= "<input type='hidden' name='$k' value='$c' >";
		}
		message($response['errormsg'].$postback,"","Response","creditcard.php");

?>
