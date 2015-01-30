<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//addcallcenteruserfb.php:		The page functions for callcenter users for this usertype = 1. 
include("includes/sessioncheck.php");

if(!$headerInclude) $headerInclude="reseller";
include("includes/header.php");
require_once("../includes/function1.php");

foreach($HTTP_POST_VARS as $k => $c)
	$postback.= "<input type='hidden' name='$k' value='$c' >";
	
$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
if($companyname!="")
{
	$contactname = (isset($HTTP_POST_VARS['contactname'])?quote_smart($HTTP_POST_VARS['contactname']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	//$password= (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	//$repassword= (isset($HTTP_POST_VARS['repassword'])?quote_smart($HTTP_POST_VARS['repassword']):"");
	$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$confirmemail= (isset($HTTP_POST_VARS['confirmemail'])?quote_smart($HTTP_POST_VARS['confirmemail']):"");
	$merchantmonthly= (isset($HTTP_POST_VARS['merchantmonthly'])?quote_smart($HTTP_POST_VARS['merchantmonthly']):"");
	$phone= (isset($HTTP_POST_VARS['phone'])?quote_smart($HTTP_POST_VARS['phone']):"");
	$url= (isset($HTTP_POST_VARS['url'])?quote_smart($HTTP_POST_VARS['url']):"");
	$password = strtolower(substr(md5(time),0,6));
	
	if($companyname == "" || $contactname == ""){
		$msgtodisplay="Insufficient data.";
		message($msgtodisplay,"","Insufficient data","addReseller.php",false);  
		include("includes/footer.php");
		exit();
	}
	if($username == "") {
		message("Please Enter a UserName.".$postback,"","UserName","addReseller.php",false);
		include("includes/footer.php");
		exit();
	}
	if(func_checkUsernameExistInAnyTable($username,$cnn_cs)) {
		message("UserName Exists. Please Enter a different UserName.".$postback,"","UserName","addReseller.php",false);
		include("includes/footer.php");
		exit();
	}

/*	if($password == "") {
		$msgtodisplay="Please enter Password.";
		message($msgtodisplay,"","Insufficient data");  
		exit();
	}*/
	$user_companyexist=func_checkCompanynameExistInAnyTable($companyname,$cnn_cs);
	if($user_companyexist==1){
		message("Company Name Exists. Please Enter a different Company Name.".$postback,"","Company Name","addReseller.php",false);
		include("includes/footer.php");
		exit();
	}
	$user_mailidexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
	if($user_mailidexist==1){
		message("Existing email id. Please Enter a different Email.".$postback,"","Email","addReseller.php",false);
		include("includes/footer.php");
		exit();
	}
	$current_date_time = func_get_current_date_time();
	
	$rd_subgateway_id = 'NULL';	// Gateway Reseller?
	if($resellerInfo['reseller_id']==$resellerInfo['rd_subgateway_id'] && $resellerInfo['rd_subgateway_id']>0) $rd_subgateway_id = "'".$resellerInfo['rd_subgateway_id']."'";
	
	$qry_insert = "Insert into cs_resellerdetails (reseller_username, reseller_password, reseller_date_added, reseller_companyname, reseller_contactname, reseller_email, reseller_phone, reseller_url, reseller_monthly_volume, rd_subgateway_id) values ('$username', '$password', '$current_date_time', '$companyname', '$contactname', '$email', '$phone', '$url', '$merchantmonthly', $rd_subgateway_id)";
	if(mysql_query($qry_insert,$cnn_cs))
	{
	
		$user_id=mysql_insert_id();
		$user_reference_num=func_User_Ref_No($user_id);
		$is_success=func_update_single_field('cs_resellerdetails','rd_referenceNumber',$user_reference_num,'reseller_id',$user_id,$cnn_cs);

		//$email_from = "partners@etelegate.com";
		$email_subject = "Registration Confirmation";
		$email_message = $msgtodisplay;
		$email_to= $email;
		
		$emailData["email"] = $email;
		$emailData["full_name"] = $contactname;
		$emailData["companyname"] = $companyname;
		$emailData["username"] = $username;
		$emailData["password"] = $password;
		$emailData["gateway_select"] = $companyInfo['gateway_id'];
		

		$emailContents = get_email_template("reseller_welcome_letter",$emailData);
		send_email_template("reseller_welcome_letter",$emailData);
		
		message("$companyname Created Successfully. Email sent to $email","Success","Success",false);

	}
	else
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
}
include("includes/footer.php");
?>