<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// updateMerchant.php:	The admin page functions for the New Company setup.

include ("includes/sessioncheck.php");

$headerInclude="merchant";
include("includes/header.php");
require_once("../includes/function.php");
require_once("../includes/function1.php");
require_once("../includes/function2.php");
include("includes/message.php");
include("includes/mail_letter_template.php"); //for getting the reply mail content
	$resellerLogin 	= (isset($HTTP_SESSION_VARS["sessionReseller"])?quote_smart($HTTP_SESSION_VARS["sessionReseller"]):"");
	$resellerName 	= (isset($HTTP_SESSION_VARS["sessionResellerName"])?quote_smart($HTTP_SESSION_VARS["sessionResellerName"]):"");
	$company 		= (isset($HTTP_GET_VARS['company'])?quote_smart($HTTP_GET_VARS['company']):"");
	$username = strtolower(quote_smart($HTTP_POST_VARS['username']));
	//$password 		= (isset($HTTP_GET_VARS['password'])?trim($HTTP_GET_VARS['password']):"");
	$companyname 	= (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
	$email 			= (isset($HTTP_GET_VARS['email'])?quote_smart($HTTP_GET_VARS['email']):"");
	$transaction_type = (isset($HTTP_GET_VARS['rad_order_type'])?quote_smart($HTTP_GET_VARS['rad_order_type']):"");
	$how_about_us 	= (isset($HTTP_GET_VARS['how_about_us'])?quote_smart($HTTP_GET_VARS['how_about_us']):"");
	$volumeNumber 	= (isset($HTTP_GET_VARS['volume'])?quote_smart($HTTP_GET_VARS['volume']):"");
	$reseller 		= (isset($HTTP_GET_VARS['reseller'])?quote_smart($HTTP_GET_VARS['reseller']):"");
	$url 			= (isset($HTTP_GET_VARS['url'])?quote_smart($HTTP_GET_VARS['url']):"");
	$gateway_id 	= (isset($HTTP_GET_VARS['hidgateway_id'])?quote_smart($HTTP_GET_VARS['hidgateway_id']):"");
	$user_reference_num="";

	$password = strtolower(substr(md5(time),0,6));

	if($resellerInfo['completed_reseller_application']==0)
	{
		$msgtodisplay	= "<font color='red'>Please complete the Start Here section.</font>";
		$outhtml		= "Y";
		message($msgtodisplay,$outhtml,$headerInclude);
		exit();	
	}

	if($volumeNumber=="" || $transaction_type=="" || $companyname=="") {
		$msgtodisplay	= "<font color='red'>Insufficient Data.</font>";
		$outhtml		= "Y";
		message($msgtodisplay,$outhtml,$headerInclude);
		exit();
	}
	if ($transaction_type == "tele")
	{
		$send_ecommercemail = 0;
		$block_virtual_terminal = 0;
	}
	else
	{
		$send_ecommercemail = 1;
		$block_virtual_terminal = 1;
	}
	
	$user_nameexist = 0;
	$reseller_companyname = "";
	$current_date_time = func_get_current_date_time();
	if($company)
	{
	if(func_CheckEmailExistinAnyTable($email,$cnn_cs)==1){
	$msgtodisplay = "Email-Id ".$email." already exists";
		$outhtml  = "Y";
		message($msgtodisplay,$outhtml,$headerInclude);
		exit();
	}

		$user_nameexist 	= func_checkUsernameExistInAnyTable($username,$cnn_cs);
		$qry_select_user 	= "select username  from cs_companydetails where (companyname='$companyname' or email='$email')";
		if(!($show_sql = mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		elseif(mysql_num_rows($show_sql) >0)
		{
			$msgtodisplay="<font color='red'><strong>Merchant already listed in the system.</strong></font>";
			$outhtml="Y";
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();
		}elseif($user_nameexist==1) {
			$msgtodisplay="<font color='red'><strong>Merchant already listed in the system.</strong></font>";
			$outhtml="Y";
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();

		}
        else
		{
			$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,email,volumenumber,activeuser,transaction_type,how_about_us,reseller_other,reseller_id,date_added,send_ecommercemail,url1,gateway_id,block_virtualterminal)";
			$qry_insert_user .= " values('$username','$password','$companyname','$email','$volumeNumber',0,'$transaction_type','rsel','$resellerName',$resellerLogin,'$current_date_time',$send_ecommercemail,'$url','$gateway_id',$block_virtual_terminal)";
		//	print $qry_insert_user;
			if(!($show_sql =mysql_query($qry_insert_user)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query <br>");
				exit();
			}
			else
			{

				$is_success=0;
				$user_id=mysql_insert_id();
				$user_reference_num=func_User_Ref_No($user_id);
				$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,NULL,'userId',$user_id,$cnn_cs);
			
			$sql = "Insert into cs_entities
				set 
					en_username = '".($username)."',
					en_password = MD5('".($username.$password)."'),
					en_email = '".quote_smart($email)."',
					en_gateway_ID = '".quote_smart($gateway_id)."',
					en_type = 'merchant',
					en_signup = NOW(),
					en_type_id = '".quote_smart($user_id)."'
				";
			sql_query_write($sql) or dieLog(mysql_error()." ~ $str_qry");
			

				$letterTempate = 'merchant_referral_letter';
		
				$emailData["email"] = $email_to;
				$emailData["reselleremail"] = $resellerInfo['reseller_email'];
				$emailData["full_name"] = "Merchant";
				$emailData["companyname"] = $companyname;
				$emailData["resellername"] = $resellerInfo['reseller_companyname'];
				$emailData["username"] = $username;
				$emailData["password"] = $password;
				$emailData["Reference_ID"] = $user_reference_num;
					
				send_email_template($letterTempate,$emailData);
				$emailInfo= get_email_template($letterTempate,$emailData);

				/*************************************************************************/
				$msgtodisplay="New merchant registered successfully. Confirmation Email sent to '".$email."'";
				$outhtml="Y";
				message($msgtodisplay,$outhtml,$headerInclude);
				exit();
			}

		}
}
include("includes/footer.php");
?>

