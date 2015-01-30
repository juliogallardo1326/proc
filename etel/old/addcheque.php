<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//addcheck.php:		The page functions for saving the check details. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

include 'includes/function2.php';
require_once('includes/function.php');
include 'admin/includes/mailbody_replytemplate.php';

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$sessionCompanyUserId =isset($HTTP_SESSION_VARS["sessionCompanyUserId"])?$HTTP_SESSION_VARS["sessionCompanyUserId"]:0;
$sessionService=isset($HTTP_SESSION_VARS["sessionService"])?$HTTP_SESSION_VARS["sessionService"]:"";
$sessionServiceUser =isset($HTTP_SESSION_VARS["sessionServiceUser"])?$HTTP_SESSION_VARS["sessionServiceUser"]:"";
$send_mails =0;
$i_service_user_id = "null";
if($sessionService != "")
{
	$i_service_user_id = 0;
}
else if($sessionServiceUser != "")
{
	$i_service_user_id = isset($HTTP_SESSION_VARS["sessionServiceUserId"])?$HTTP_SESSION_VARS["sessionServiceUserId"]:"";
}

$company_id = $sessionlogin;
if ($company_id == "")
{
	$company_id = $sessionCompanyUser;
}
if ($company_id == "") // when it comes from the service section
{
	$company_id = (isset($HTTP_POST_VARS['hid_company_id'])?trim($HTTP_POST_VARS['hid_company_id']):"");
}

$company_usertype = "null";
if ($sessionCompanyUser != "")
{
	$RstCompanyUser  = mysql_query("select teleusertype from cs_companyusers where id=". $sessionCompanyUserId );
	if ($ArrCompanyUser = mysql_fetch_array($RstCompanyUser))
	{
		$company_usertype = $ArrCompanyUser[0];
	}
}
//print("id= ".$company_id);
if($sessionCompanyUser !="") 
{
	$headerInclude="sessionCompanyUser";
} 
else 
{
	$headerInclude="home";
}
include 'includes/topheader.php';
$sender ="sales@etelegate.com";

$opt_chk_day = date("d");
$opt_chk_month = date("m");
$opt_chk_year = date("Y");
$opt_bill_day = date("d");
$opt_bill_month = date("m");
$opt_bill_year = date("Y");
$trans_id=0;
$bank_return="";
$firstname = (isset($HTTP_POST_VARS['firstname'])?trim($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?trim($HTTP_POST_VARS['lastname']):"");
$address= (isset($HTTP_POST_VARS['address'])?trim($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?trim($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?trim($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?trim($HTTP_POST_VARS['state']):"");
$zip= (isset($HTTP_POST_VARS['zip'])?trim($HTTP_POST_VARS['zip']):"");
$phonenumber= (isset($HTTP_POST_VARS['phonenumber'])?trim($HTTP_POST_VARS['phonenumber']):"");
$chequenumber= (isset($HTTP_POST_VARS['chequenumber'])?trim($HTTP_POST_VARS['chequenumber']):"");
$chequetype= (isset($HTTP_POST_VARS['chequetype'])?trim($HTTP_POST_VARS['chequetype']):"");
$amount = (isset($HTTP_POST_VARS['amount'])?trim($HTTP_POST_VARS['amount']):"");
$accounttype= (isset($HTTP_POST_VARS['accounttype'])?trim($HTTP_POST_VARS['accounttype']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?trim($HTTP_POST_VARS["opt_bill_year"]):$opt_bill_year);
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?trim($HTTP_POST_VARS["opt_bill_month"]):$opt_bill_month);
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?trim($HTTP_POST_VARS["opt_bill_day"]):$opt_bill_day);
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
$bankname = (isset($HTTP_POST_VARS['bankname'])?trim($HTTP_POST_VARS['bankname']):"");
$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?trim($HTTP_POST_VARS['bankroutingcode']):"");
$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?trim($HTTP_POST_VARS['bankaccountno']):"");
$email =  (isset($HTTP_POST_VARS['email'])?trim($HTTP_POST_VARS['email']):"");
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?trim($HTTP_POST_VARS['authorizationno']):"");
$shipping= (isset($HTTP_POST_VARS['shippingno'])?trim($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?trim($HTTP_POST_VARS['securityno']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?trim($HTTP_POST_VARS['driverlicense']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?trim($HTTP_POST_VARS['licensestate']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?trim($HTTP_POST_VARS['misc']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?trim($HTTP_POST_VARS['domain1']):"");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$dateToEnter = func_get_current_date_time(); //EST Time.
$str_recur_date = (isset($HTTP_POST_VARS['chk_recur_date'])?trim($HTTP_POST_VARS['chk_recur_date']):"");
$str_recurdate_mode = (isset($HTTP_POST_VARS['recurdatemode'])?trim($HTTP_POST_VARS['recurdatemode']):"");
$i_recur_day = (isset($HTTP_POST_VARS['recur_day'])?trim($HTTP_POST_VARS['recur_day']):"");
$i_recur_week = (isset($HTTP_POST_VARS['recur_week'])?trim($HTTP_POST_VARS['recur_week']):"");
$i_recur_month = (isset($HTTP_POST_VARS['recur_month'])?trim($HTTP_POST_VARS['recur_month']):"");
$i_recur_year_day = (isset($HTTP_POST_VARS['recur_year_day'])?trim($HTTP_POST_VARS['recur_year_day']):"");
$i_recur_year_month = (isset($HTTP_POST_VARS['recur_year_month'])?trim($HTTP_POST_VARS['recur_year_month']):"");
$i_recur_start_month = (isset($HTTP_POST_VARS['opt_recur_month'])?trim($HTTP_POST_VARS['opt_recur_month']):"");
$i_recur_start_day = (isset($HTTP_POST_VARS['opt_recur_day'])?trim($HTTP_POST_VARS['opt_recur_day']):"");
$i_recur_start_year = (isset($HTTP_POST_VARS['opt_recur_year'])?trim($HTTP_POST_VARS['opt_recur_year']):"");
$str_recur_start_date = "$i_recur_start_year-$i_recur_start_month-$i_recur_start_day";
$i_recur_charge = (isset($HTTP_POST_VARS['recur_charge'])?trim($HTTP_POST_VARS['recur_charge']):"");
$i_recur_times = (isset($HTTP_POST_VARS['recur_times'])?trim($HTTP_POST_VARS['recur_times']):"");
$str_atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?trim($HTTP_POST_VARS['atm_verify']):"");
$productDescription= (isset($HTTP_POST_VARS['txtproductDescription'])?Trim($HTTP_POST_VARS['txtproductDescription']):"");
$str_currency= (isset($HTTP_POST_VARS['currency_code'])?Trim($HTTP_POST_VARS['currency_code']):"");
if($accounttype=="checking") {
	$account_type ="C";
} else {
	$account_type ="S";
}
if($i_recur_charge == "")
{
	$i_recur_charge = $amount;
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
			$msgtodisplay="ATM Verification declined from bank due to $ret_integration_resultarray[1] $decline_response_code, $ret_integration_resultarray[2].";
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

if($voiceauth !="" ) {	
	$auth_status = func_isauthorisationno_exists($voiceauth,$phonenumber,$company_id,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$phonenumber,$company_id,$cnn_cs);
	}
	if ($auth_status != "")
	{
			$msgtodisplay="Voice authorization id already exist.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);  
			exit();
	}
}
	
	$qrt_select_suspend = "Select suspenduser from cs_companydetails where userid='$company_id'";
	
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
	$qrt_select_company = "Select companyname,transaction_type,billingdescriptor,email,send_mail,send_ecommercemail,gateway_id,bank_check from cs_companydetails where userid='$company_id'";
	
	if(!($show_sql_run = mysql_query($qrt_select_company,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		if(mysql_num_rows($show_sql_run)== 0) 
		{
			$msgtodisplay="You are not a valid user";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
		} 
		else 
		{
		$company_name = mysql_fetch_array($show_sql_run,$cnn_cs);
		$transaction_type = mysql_result($show_sql_run,0,1);
		$billingdescriptor = mysql_result($show_sql_run,0,2);
		$fromaddress = mysql_result($show_sql_run,0,3);
		$send_mails = mysql_result($show_sql_run,0,4);
		$send_ecommercemail = mysql_result($show_sql_run,0,5);
		$str_gateway_id = mysql_result($show_sql_run,0,6);
		$bank_check= mysql_result($show_sql_run,0,7);
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

			$qrt_insert_details = "insert into cs_transactiondetails (name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,passStatus,pass_count,licensestate,email,service_user_id,company_usertype,company_user_id,productdescription,currencytype ) values('$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype',$amount,'$dateToEnter','$bankname','$bankroutingcode','$bankaccountno','$misc','N','P',$company_id,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','PE',0,'$licensestate','$email',$i_service_user_id,$company_usertype,$sessionCompanyUserId,'$productDescription','$str_currency')"; 
			//print($qrt_insert_details);
			if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{	$trans_id = mysql_insert_id();
				func_update_rate($company_id,$trans_id,$cnn_cs);
				func_ins_bankrates($trans_id,$bank_check,$cnn_cs);
				if($str_fields != "")
				{	
					$qrt_insert_details = "insert into cs_rebillingdetails (company_user_id,rebill_transactionid,name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,licensestate,email,service_user_id,productdescription".$str_fields.") values('$sessionCompanyUserId',$trans_id,'$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype',$amount,'$dateToEnter','$bankname','$bankroutingcode','$bankaccountno','$misc','N','P',$company_id,'$domain1','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','$licensestate','$email',$i_service_user_id,'$productDescription'".$str_values.")"; 
					if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

					}

				}
		//changed
		//$transid = mysql_insert_id(); 
		$ref_number=func_Trans_Ref_No($trans_id );
		$updateSuccess="";
		$updateSuccess=func_update_single_field('cs_transactiondetails','reference_number',$ref_number,'transactionId',$trans_id,$cnn_cs);
		if($updateSuccess=1){
			$reference_number=$ref_number;
		}
		
		$headers = "";
		if ($str_gateway_id == -1) {
			
		} else {
			$gateway_company_name = "";
			$qrt_select_gateway = "Select companyname,email from cs_companydetails where userid='$str_gateway_id'";
			if(!($show_sql_gateway = mysql_query($qrt_select_gateway,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				$gateway_company_name = mysql_result($show_sql_gateway,0,0);
				$sender = mysql_result($show_sql_gateway,0,1);
			}
			
		}
			
		
	if($send_mails ==1) {	
		$ecommerce_letter = func_get_value_of_field($cnn_cs,"cs_registrationmail","mail_sent","mail_id",2);
		if($email !="" && $transaction_type !="tele" && $ecommerce_letter==1 && $send_ecommercemail==1) {
				$str_email_content = func_getecommerce_mailbody();
				$str_email_content = str_replace("[customername]", $firstname." ".$lastname, $str_email_content );
				$str_email_content = str_replace("[companyname]", $company_name[0], $str_email_content );
				$str_email_content = str_replace("[amount]", $amount, $str_email_content );
				$str_email_content = str_replace("[billingdescriptor]", $billingdescriptor, $str_email_content );
				$str_email_content = str_replace("[companyemailaddress]", $fromaddress, $str_email_content );
				$str_email_content = str_replace("[ProductDescription]", $productDescription, $str_email_content );
				$str_email_content = str_replace("[chargeamount]", $amount, $str_email_content );
				$str_email_content = str_replace("[cardtype]", "Check", $str_email_content );
				$str_email_content = str_replace("[name]", $firstname, $str_email_content );
				$str_email_content = str_replace("[address]", $address, $str_email_content );
				$str_email_content = str_replace("[city]", $city, $str_email_content );
				$str_email_content = str_replace("[state]", $state, $str_email_content );
				$str_email_content = str_replace("[zip]", $zip, $str_email_content );
				$str_email_content = str_replace("[ccnumber]", substr($chequenumber,strlen($chequenumber)-4,4) , $str_email_content);
			//	echo $str_email_content;
				$b_mail = func_send_mail($sender,$email,"Ecommerce Transaction Letter",$str_email_content);
		}
		
			if($email !="") 
			{
				//mail($email,$subject,$message,$headers);
			}
	
			//func_sendMail($company_id,$subject,$message,$headers);
		}
		//$msgtodisplay="Order number ".$voiceauth." entered";			
		$msgtodisplay="The Order number $reference_number details have been entered successfully";			
		$outhtml="y";		
		message($msgtodisplay,$outhtml,$headerInclude);				
		}
	}

exit();
}					     
}


?>

