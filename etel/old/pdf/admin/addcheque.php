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
//addcheck.php:		The page functions for saving the check details. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
include 'includes/header.php';

include '../includes/function2.php';
require_once( '../includes/function.php');
$headerInclude="transactions";

include 'includes/mailbody_replytemplate.php';
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
$sender =$_SESSION['gw_emails_sales'];
$opt_chk_day = date("d");
$opt_chk_month = date("m");
$opt_chk_year = date("Y");
$opt_bill_day = date("d");
$opt_bill_month = date("m");
$opt_bill_year = date("Y");
$trans_id=0;
$send_mails=0;
$bank_return = "";
$firstname = (isset($HTTP_POST_VARS['firstname'])?quote_smart($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?quote_smart($HTTP_POST_VARS['lastname']):"");
$address= (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
$zip= (isset($HTTP_POST_VARS['zip'])?quote_smart($HTTP_POST_VARS['zip']):"");
$phonenumber= (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
$chequenumber= (isset($HTTP_POST_VARS['chequenumber'])?quote_smart($HTTP_POST_VARS['chequenumber']):"");
$chequetype= (isset($HTTP_POST_VARS['chequetype'])?quote_smart($HTTP_POST_VARS['chequetype']):"");
$amount = (isset($HTTP_POST_VARS['amount'])?quote_smart($HTTP_POST_VARS['amount']):"");
$accounttype= (isset($HTTP_POST_VARS['accounttype'])?quote_smart($HTTP_POST_VARS['accounttype']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?quote_smart($HTTP_POST_VARS["opt_bill_year"]):$opt_bill_year);
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?quote_smart($HTTP_POST_VARS["opt_bill_month"]):$opt_bill_month);
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?quote_smart($HTTP_POST_VARS["opt_bill_day"]):$opt_bill_day);
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
$productDescription= (isset($HTTP_POST_VARS['txtproductDescription'])?quote_smart($HTTP_POST_VARS['txtproductDescription']):"");
$bankname = (isset($HTTP_POST_VARS['bankname'])?quote_smart($HTTP_POST_VARS['bankname']):"");
$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?quote_smart($HTTP_POST_VARS['bankroutingcode']):"");
$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?quote_smart($HTTP_POST_VARS['bankaccountno']):"");
$email =  (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?quote_smart($HTTP_POST_VARS['authorizationno']):"");
$shipping= (isset($HTTP_POST_VARS['shippingno'])?quote_smart($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?quote_smart($HTTP_POST_VARS['securityno']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?quote_smart($HTTP_POST_VARS['driverlicense']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?quote_smart($HTTP_POST_VARS['licensestate']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?quote_smart($HTTP_POST_VARS['misc']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?quote_smart($HTTP_POST_VARS['domain1']):"");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$dateToEnter = func_get_current_date_time(); //EST Time.
$str_recur_date = (isset($HTTP_POST_VARS['chk_recur_date'])?quote_smart($HTTP_POST_VARS['chk_recur_date']):"");
$str_recurdate_mode = (isset($HTTP_POST_VARS['recurdatemode'])?quote_smart($HTTP_POST_VARS['recurdatemode']):"");
$i_recur_day = (isset($HTTP_POST_VARS['recur_day'])?quote_smart($HTTP_POST_VARS['recur_day']):"");
$i_recur_week = (isset($HTTP_POST_VARS['recur_week'])?quote_smart($HTTP_POST_VARS['recur_week']):"");
$i_recur_month = (isset($HTTP_POST_VARS['recur_month'])?quote_smart($HTTP_POST_VARS['recur_month']):"");
$i_recur_year_day = (isset($HTTP_POST_VARS['recur_year_day'])?quote_smart($HTTP_POST_VARS['recur_year_day']):"");
$i_recur_year_month = (isset($HTTP_POST_VARS['recur_year_month'])?quote_smart($HTTP_POST_VARS['recur_year_month']):"");
$i_recur_start_month = (isset($HTTP_POST_VARS['opt_recur_month'])?quote_smart($HTTP_POST_VARS['opt_recur_month']):"");
$i_recur_start_day = (isset($HTTP_POST_VARS['opt_recur_day'])?quote_smart($HTTP_POST_VARS['opt_recur_day']):"");
$i_recur_start_year = (isset($HTTP_POST_VARS['opt_recur_year'])?quote_smart($HTTP_POST_VARS['opt_recur_year']):"");
$str_recur_start_date = "$i_recur_start_year-$i_recur_start_month-$i_recur_start_day";
$i_recur_charge = (isset($HTTP_POST_VARS['recur_charge'])?quote_smart($HTTP_POST_VARS['recur_charge']):"");
$i_recur_times = (isset($HTTP_POST_VARS['recur_times'])?quote_smart($HTTP_POST_VARS['recur_times']):"");
$str_atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?quote_smart($HTTP_POST_VARS['atm_verify']):"");
$i_company_id = (isset($HTTP_POST_VARS['hid_company_id'])?quote_smart($HTTP_POST_VARS['hid_company_id']):"");
$str_currency= (isset($HTTP_POST_VARS['currency_code'])?quote_smart($HTTP_POST_VARS['currency_code']):"");

if($i_recur_charge == "")
{
	$i_recur_charge = $amount;
}
if($accounttype=="checking") {
	$account_type ="C";
} else {
	$account_type ="S";
}

if($amount)
{
$yyyy=date("Y");
$mm=date("m");
$dd=date("d");
$hr=date("G");
$mn=date("i");
$tt=date("A");

	if ($str_atm_verify == "Y") {
		$ret_integration_result = func_bank_integration_result($firstname,$lastname,$amount,$account_type,$bankaccountno,$bankroutingcode);
		$ret_integration_resultarray=split(",",$ret_integration_result);
		$decline_response_code = func_decline_responsecode($ret_integration_resultarray[2]);
		if($ret_integration_resultarray[0] =='A') {
			$bank_return = "Success.";
		}else if($ret_integration_resultarray[0] =='D' && $decline_response_code !="Insufficient funds") {
			$bank_return = "Declined.";
			$msgtodisplay="ATM Verification declined from bank due to $ret_integration_resultarray[1] $decline_response_code , $ret_integration_resultarray[2].";
			$outhtml="Y";				
			message($msgtodisplay,$outhtml,$headerInclude);  
			exit();
		}else if($ret_integration_resultarray[0] =='E') {
			$bank_return = "Error.";
			$msgtodisplay="ATM Verification processing error.";
			$outhtml="Y";				
			message($msgtodisplay,$outhtml,$headerInclude);  
			exit();
		}else if($decline_response_code !="Insufficient funds"){
			$bank_return = "Error.";
			$msgtodisplay="System processing error.";
			$outhtml="Y";				
			message($msgtodisplay,$outhtml,$headerInclude);  
			exit();
		}
	}

if($voiceauth !="") {
	$auth_status = func_isauthorisationno_exists($voiceauth,$phonenumber,$i_company_id,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$phonenumber,$i_company_id,$cnn_cs);
	}
	if ($auth_status != "")
	{
			$msgtodisplay="Voice authorization id already exist.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();
	}
}
	$qrt_select_suspend="Select suspenduser from cs_companydetails where userid='$i_company_id'";
	if(!($show_suspend_Sql= mysql_query($qrt_select_suspend)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		$suspend =  mysql_fetch_array($show_suspend_Sql);
		if($suspend[0]=="YES") 
		{
			$msgtodisplay="This Company has been suspended by the administrator";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
	}

	if($state=="- - -Select- - -") 
	{
		$state=null;
	}
	if($licensestate=="- - -Select- - -") 
	{
		$licensestate=null;
	}
	
	$qrt_select_company = "Select companyname,transaction_type,billingdescriptor,email,send_mail,send_ecommercemail,bank_check from cs_companydetails where userid='$i_company_id'";
	
	if(!($show_sql_run = mysql_query($qrt_select_company,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		if(mysql_num_rows($show_sql_run)== 0) 
		{
			$msgtodisplay="This Company is not valid";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
		} 
		else 
		{
			$company_name = mysql_result($show_sql_run,0,0);
			$transaction_type = mysql_result($show_sql_run,0,1);
			$billingdescriptor = mysql_result($show_sql_run,0,2);
			$fromaddress = mysql_result($show_sql_run,0,3);
		    $send_mails = mysql_result($show_sql_run,0,4);
			$send_ecommercemail = mysql_result($show_sql_run,0,5);
			$bank_check = mysql_result($show_sql_run,0,6);
			//	$qrt_insert_details = "insert into cs_transactiondetails (Invoiceid,name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,Checkto,amount,transactionDate,memodet,bankname,bankroutingcode,bankaccountnumber,misc,email,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,chequedate,billingDate,passStatus,pass_count) values('$invoiceid','$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype','$payto',$amount,'$dateToEnter','$memo','$bankname','$bankroutingcode','$bankaccountno','$misc','$email','N','P',$sessionlogin,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$chequedate','$setbilldate','PE',0)"; 

		$str_fields = "";
		$str_values = "";

		if($str_recur_date == "Y")
		{
			if($str_recurdate_mode != "")
			{
				$str_fields = ",recur_mode";
				$str_values = ",'".$str_recurdate_mode."'";
				if($str_recurdate_mode == "D")
				{
					if($i_recur_day != "")
					{
						$str_fields .= ",recur_day";
						$str_values .= ",".$i_recur_day;
					}
				}
				else if($str_recurdate_mode == "W")
				{
					if($i_recur_week != "")
					{
						$str_fields .= ",recur_week";
						$str_values .= ",".$i_recur_week;
					}
				}
				else if($str_recurdate_mode == "M")
				{
					if($i_recur_month != "")
					{
						$str_fields .= ",recur_day";
						$str_values .= ",".$i_recur_month;
					}
				}
				else if($str_recurdate_mode == "Y")
				{
					if($i_recur_year_month != "" && $i_recur_year_day != "")
					{
						$str_fields .= ",recur_month,recur_day";
						$str_values .= ",".$i_recur_year_month.",".$i_recur_year_day;
					}
				}
				$str_fields .= ",recur_start_date";
				$str_values .= ",'".$str_recur_start_date."'";

				if($i_recur_charge != "")
				{
					$str_fields .= ",recur_charge";
					$str_values .= ",".$i_recur_charge;
				}
				if($i_recur_times != "")
				{
					$str_fields .= ",recur_times";
					$str_values .= ",".$i_recur_times;
				}
			}
		}

	$qrt_insert_details = "insert into cs_transactiondetails (name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,passStatus,pass_count,licensestate, productdescription,currencytype) values('$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype',$amount,'$dateToEnter','$bankname','$bankroutingcode','$bankaccountno','$misc','N','P',$i_company_id,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','PE',0,'$licensestate','$productDescription','$str_currency')"; 
	if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{	$trans_id = mysql_insert_id();
		func_update_rate($i_company_id,$trans_id,$cnn_cs);
		if($str_fields != "")
		{
			$qrt_insert_details = "insert into cs_rebillingdetails (rebill_transactionid,name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,licensestate,productdescription".$str_fields.") values($trans_id,'$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype',$amount,'$dateToEnter','$bankname','$bankroutingcode','$bankaccountno','$misc','N','P',$i_company_id,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','$licensestate','$productDescription'".$str_values.")"; 
			if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}

		}
	//changed
	//$transid = mysql_insert_id(); 
	$ref_number=func_Trans_Ref_No($trans_id );
	func_ins_bankrates($trans_id,$bank_check,$cnn_cs);

	$updateSuccess="";
	$updateSuccess=func_update_single_field('cs_transactiondetails','reference_number',$ref_number,'transactionId',$trans_id,$cnn_cs);
	if($updateSuccess=1){
		$reference_number=$ref_number;
	}
		
	$headers = "";
	$headers .= "From: Etelegate <sales@etelegate.com>\n";
	$headers .= "X-Sender: Admin Etelegate\n"; 
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "Return-Path: <sales@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$subject = "Transaction Confirmation of ".$firstname." ".$lastname;
	$numLen = strlen($chequenumber);
	$frNum = $numLen-4;
	$lastFour = substr($chequenumber,$frNum,$numLen);
	$message = "Transaction details of $company_name[0]\r\n\r\n";
	$message .= "Reference No: $reference_number\r\n\r\n";
	$message .= "Voice Authorization ID : $voiceauth\r\n\r\n";
	$message .= "Name : $firstname  $lastname\r\n\r\n";
	$message .= "Address : $address\r\n\r\n";
	$message .= "Country : $country\r\n\r\n";
	$message .= "State : $state\r\n\r\n";
	$message .= "City : $city\r\n\r\n";
	$message .= "Zipcode : $zip\r\n\r\n";
	$message .= "Phone Number : $phonenumber\r\n\r\n";
	$message .= "Account Type : $accounttype\r\n\r\n";
	$message .= "Check Type : $chequetype\r\n\r\n";
	$message .= "Check No : $lastFour\r\n\r\n";
	$message .= "Amount : $amount\r\n\r\n";
	$message .= "Bank Verification : $bank_return\r\n\r\n";
	$message .= "Bank Name : $bankname\r\n\r\n";
	$message .= "Bank Routing Code : $bankroutingcode\r\n\r\n";
	$message .= "Bank Account Number : $bankaccountno\r\n\r\n";
	$message .= "Date : $dateToEnter\r\n\r\n";
	$message .= "Misc : $misc\r\n\r\n";
	$message .= "IP Address : $domain1\r\n\r\n\r\n";
	$message .= "Your checking account has been charged the above amount TODAY\r\n";
	if($send_mails==1) {
	
		$ecommerce_letter = func_get_value_of_field($cnn_cs,"cs_registrationmail","mail_sent","mail_id",2);
		if($email !="" && $transaction_type !="tele" && $ecommerce_letter ==1 && $send_ecommercemail==1) {
				$str_email_content = func_getecommerce_mailbody();
				$str_email_content = str_replace("[customername]", $firstname." ".$lastname, $str_email_content );
				$str_email_content = str_replace("[companyname]", $company_name, $str_email_content );
				$str_email_content = str_replace("[amount]", $amount, $str_email_content );
				$str_email_content = str_replace("[billingdescriptor]", $billingdescriptor, $str_email_content );
				$str_email_content = str_replace("[companyemailaddress]", $fromaddress, $str_email_content );
				$str_email_content = str_replace("[chargeamount]", $amount, $str_email_content );
				$str_email_content = str_replace("[cardtype]", "Check", $str_email_content );
				$str_email_content = str_replace("[name]", $firstname, $str_email_content );
				$str_email_content = str_replace("[address]", $address, $str_email_content );
				$str_email_content = str_replace("[city]", $city, $str_email_content );
				$str_email_content = str_replace("[state]", $state, $str_email_content );
				$str_email_content = str_replace("[zip]", $zip, $str_email_content );
				$str_email_content = str_replace("[ccnumber]", substr($chequenumber,strlen($chequenumber)-4,4) , $str_email_content);
		//		echo $str_email_content;
	
				$b_mail = func_send_mail($sender,$email,"Ecommerce Transaction Letter",$str_email_content);
		}
			
		if($email !="") 
		{
			//mail($email,$subject,$message,$headers);
		}
	
			//func_sendMail($i_company_id,$subject,$message,$headers);
	}
			//$msgtodisplay="Order number ".$voiceauth." entered";			
			$msgtodisplay="The Order number $reference_number details have been entered successfully";			
			$outhtml="y";		
			message($msgtodisplay,$outhtml,$headerInclude);				
		}
	}
}

exit();
}
}
?>
