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
//addcheck.php:		The page functions for saving the check details. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
if($sessionCompanyUser !="") 
{
	$headerInclude="sessionCompanyUser";
} 
else 
{
	$headerInclude="home";
}
include 'includes/topheader.php';
$sender ="admin@companysetup.co.uk";
$opt_chk_day = date("d");
$opt_chk_month = date("m");
$opt_chk_year = date("Y");
$opt_bill_day = date("d");
$opt_bill_month = date("m");
$opt_bill_year = date("Y");

$firstname = (isset($HTTP_POST_VARS['firstname'])?Trim($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?Trim($HTTP_POST_VARS['lastname']):"");
$address= (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?Trim($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?Trim($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
$zip= (isset($HTTP_POST_VARS['zip'])?Trim($HTTP_POST_VARS['zip']):"");
$phonenumber= (isset($HTTP_POST_VARS['phonenumber'])?Trim($HTTP_POST_VARS['phonenumber']):"");
// $invoiceid = (isset($HTTP_POST_VARS['invoiceid'])?Trim($HTTP_POST_VARS['invoiceid']):"");
$chequenumber= (isset($HTTP_POST_VARS['chequenumber'])?Trim($HTTP_POST_VARS['chequenumber']):"");
// $i_chk_year = (isset($HTTP_POST_VARS["opt_chk_year"])?Trim($HTTP_POST_VARS["opt_chk_year"]):$opt_chk_year);
// $i_chk_month = (isset($HTTP_POST_VARS["opt_chk_month"])?Trim($HTTP_POST_VARS["opt_chk_month"]):$opt_chk_month);
// $i_chk_day = (isset($HTTP_POST_VARS["opt_chk_day"])?Trim($HTTP_POST_VARS["opt_chk_day"]):$opt_chk_day);
// $chequedate= (isset($HTTP_POST_VARS['chequedate'])?Trim($HTTP_POST_VARS['chequedate']):"");
// $chequedate= "$i_chk_year-$i_chk_month-$i_chk_day";
$chequetype= (isset($HTTP_POST_VARS['chequetype'])?Trim($HTTP_POST_VARS['chequetype']):"");
// $payto= (isset($HTTP_POST_VARS['payto'])?Trim($HTTP_POST_VARS['payto']):"");
$amount = (isset($HTTP_POST_VARS['amount'])?Trim($HTTP_POST_VARS['amount']):"");
$accounttype= (isset($HTTP_POST_VARS['accounttype'])?Trim($HTTP_POST_VARS['accounttype']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?Trim($HTTP_POST_VARS["opt_bill_year"]):$opt_bill_year);
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?Trim($HTTP_POST_VARS["opt_bill_month"]):$opt_bill_month);
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?Trim($HTTP_POST_VARS["opt_bill_day"]):$opt_bill_day);
//$setbilldate= (isset($HTTP_POST_VARS['setbilldate'])?Trim($HTTP_POST_VARS['setbilldate']):"");
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
// $memo= (isset($HTTP_POST_VARS['memo'])?Trim($HTTP_POST_VARS['memo']):"");
$bankname = (isset($HTTP_POST_VARS['bankname'])?Trim($HTTP_POST_VARS['bankname']):"");
$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?Trim($HTTP_POST_VARS['bankroutingcode']):"");
$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?Trim($HTTP_POST_VARS['bankaccountno']):"");
//$email =  (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?Trim($HTTP_POST_VARS['authorizationno']):"");
$shipping= (isset($HTTP_POST_VARS['shippingno'])?Trim($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?Trim($HTTP_POST_VARS['securityno']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?Trim($HTTP_POST_VARS['driverlicense']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?Trim($HTTP_POST_VARS['licensestate']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?Trim($HTTP_POST_VARS['misc']):"");
//			$signature= (isset($HTTP_POST_VARS['signature'])?Trim($HTTP_POST_VARS['signature']):"");
//			$misc2= (isset($HTTP_POST_VARS['misc2'])?Trim($HTTP_POST_VARS['misc2']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?Trim($HTTP_POST_VARS['domain1']):"");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
//$dateToEnter = func_get_current_date_time(); //EST Time.
$i_rebill_year = (isset($HTTP_POST_VARS["opt_rebill_year"])?Trim($HTTP_POST_VARS["opt_rebill_year"]):date("Y"));
$i_rebill_month = (isset($HTTP_POST_VARS["opt_rebill_month"])?Trim($HTTP_POST_VARS["opt_rebill_month"]):date("m"));
$i_rebill_day = (isset($HTTP_POST_VARS["opt_rebill_day"])?Trim($HTTP_POST_VARS["opt_rebill_day"]):date("d"));
$setrebilldate= "$i_rebill_year-$i_rebill_month-$i_rebill_day";

if($amount)
{
$yyyy=date("Y");
$mm=date("m");
$dd=date("d");
$hr=date("G");
$mn=date("i");
$tt=date("A");

	$auth_status = func_isauthorisationno_exists($voiceauth,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$cnn_cs);
	}
	if ($auth_status != "")
	{
			$msgtodisplay="Voice authorization id with transaction status $auth_status already exist.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();
	}
	
	if ($sessionlogin !="") 
	{
		$qrt_select_suspend = "Select suspenduser from cs_companydetails where userid='$sessionlogin'";
	}
	elseif($sessionCompanyUser !="") 
	{
		$qrt_select_suspend = "Select suspenduser from cs_companydetails where userid='$sessionCompanyUser'";
	}
	
	if(!($show_suspend_Sql= mysql_query($qrt_select_suspend,$cnn_cs))) 
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		$suspend =  mysql_fetch_array($show_suspend_Sql,$cnn_cs);
		if($suspend[0]=="YES") {
			$msgtodisplay="Your transaction have been suspended by the administrator";
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
	if ($sessionlogin !="") 
	{
		$qrt_select_company = "Select companyname from cs_companydetails where userid='$sessionlogin'";
	}
	elseif($sessionCompanyUser !="") 
	{
		$qrt_select_company = "Select companyname from cs_companydetails where userid='$sessionCompanyUser'";
		$sessionlogin = $sessionCompanyUser;
	}
	
	if(!($show_sql_run = mysql_query($qrt_select_company,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		$company_name = mysql_fetch_array($show_sql_run,$cnn_cs);
		if(mysql_num_rows($show_sql_run)== 0) 
		{
			$msgtodisplay="You are not a valid user";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
		} 
		else 
		{
		//	$qrt_insert_details = "insert into cs_transactiondetails (Invoiceid,name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,Checkto,amount,transactionDate,memodet,bankname,bankroutingcode,bankaccountnumber,misc,email,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,chequedate,billingDate,passStatus,pass_count) values('$invoiceid','$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype','$payto',$amount,'$dateToEnter','$memo','$bankname','$bankroutingcode','$bankaccountno','$misc','$email','N','P',$sessionlogin,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$chequedate','$setbilldate','PE',0)"; 
			$qrt_insert_details = "insert into cs_rebillingdetails (name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,licensestate) values('$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype',$amount,'$setrebilldate','$bankname','$bankroutingcode','$bankaccountno','$misc','N','P',$sessionlogin,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','$licensestate')"; 
			if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				$transid = mysql_insert_id(); 
				$msgtodisplay="Check with number ".$chequenumber." has been added";			
				$outhtml="y";		
				message($msgtodisplay,$outhtml,$headerInclude);				
			}
		}
	}

/*	if($email !="") 
	{
		$numLen = strlen($chequenumber);
		$frNum = $numLen-4;
		$lastFour = substr($chequenumber,$frNum,$numLen);
		$headers = "";
		$headers .= "From: Companysetup <admin@guaranteedprocessing.com>\n";
		$headers .= "X-Sender: Admin Companysetup\n"; 
		$headers .= "X-Mailer: PHP\n"; // mailer
		$headers .= "X-Priority: 1\n"; // Urgent message!
		$headers .= "Return-Path: <admin@companysetup.com>\n";  // Return path for errors
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Mime type
		$message="Transaction details of $company_name[0]<table style='border:1px solid #d1d1d1'><tr><td> Transaction ID : $transid </td></tr><tr><td> Name : $firstname  $lastname </td></tr><tr><td> Address : $address </td></tr><tr><td>Country : $country </td></tr><tr><td> State : $state </td></tr><tr><td> City : $city </td></tr><tr><td> Zipcode : $zip </td></tr><tr><td> Phone Number : $phonenumber </td></tr><tr><td> Account Type : $accounttype </td></tr><tr><td> Check Type : $chequetype </td></tr><tr><td> Check No : $lastFour </td></tr><tr><td> Pay To : $payto  </td></tr><tr><td> Amount : $amount </td></tr><tr><td> Bank Name : $bankname </td></tr><tr><td> Bank Routing Code : $bankroutingcode </td></tr><tr><td> Bank Account Number : $bankaccountno  </td></tr><tr><td> Date : $dateToEnter </td></tr><tr><td> Misc1 : $misc1 </td></tr><tr><td> Misc2 : $misc2 </td></tr><tr><td> IP Address : $domain1 </td></tr></table><br><b>Your checking account has been charged the above amount TODAY</b>";
		mail($email,"Transaction Confirmation",$message,$headers);
	}
*/	
	exit();					     
}


?>

