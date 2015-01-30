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
// add_Merchant.php:	The admin page functions for the New Company setup. 
require_once("includes/dbconnection.php");
require_once("includes/function.php");
include("includes/function2.php");
include("admin/includes/mailbody_replytemplate.php");   //for getting the reply mail content
$user_reference_num="";
	$company = (isset($HTTP_POST_VARS['gateway'])?Trim($HTTP_POST_VARS['gateway']):"");
	$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?Trim($HTTP_POST_VARS['companyname']):"");
	$email = (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
	$gatewayid=(isset($HTTP_POST_VARS['gatewayid'])?Trim($HTTP_POST_VARS['gatewayid']):"");
	//$username = str_replace("'","",$username);
	//$password = str_replace("'","",$password);
	//$companyname = str_replace("'","",$companyname);	

	$transaction_type = (isset($HTTP_POST_VARS['rad_order_type'])?Trim($HTTP_POST_VARS['rad_order_type']):"");
	$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?Trim($HTTP_POST_VARS['how_about_us']):"");
	$voulmeNumber = (isset($HTTP_POST_VARS['merchant_voulme'])?Trim($HTTP_POST_VARS['merchant_voulme']):"");
	$reseller = (isset($HTTP_POST_VARS['reseller'])?Trim($HTTP_POST_VARS['reseller']):"");
	
	$msgtodisplay = "";
	if ($transaction_type == "tele")
	{
		$send_ecommercemail = 0;
	}
	else
	{
		$send_ecommercemail = 1;
	}

$qry_select1="Select companyname,email ,url1  from cs_companydetails where userid='$gatewayid'";
$rst_select1 = mysql_query($qry_select1,$cnn_cs);
$rst_result1=mysql_fetch_array($rst_select1);
$companyname1	=$rst_result1['companyname'];
$email1				=$rst_result1['email'];
$url1					=$rst_result1['url1'];

	$current_date_time = func_get_current_date_time();
	$user_nameexist=0;
	if($company)
	{		
    	$user_nameexist =func_checkUsernameExistInAnyTable($username,$cnn_cs);
		$user_companyexist=func_checkCompanynameExistInAnyTable($companyname,$cnn_cs);
		$user_mailidexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
	
		$qry_select_user = "select username  from cs_companydetails where ( companyname='$companyname' or email='$email' )";
		//print $qry_select_user;
		
		if($user_nameexist==1) {
			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing username !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
		}
		elseif($user_companyexist==1 ){
		
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing Company name !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
		}
		elseif($user_mailidexist==1 ){
		
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing Mail ID !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
		}
        else
		{
			$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,email,volumenumber,activeuser,transaction_type,how_about_us,reseller_other,date_added,send_ecommercemail,gateway_id)";
			$qry_insert_user .= " values('$username','$password','$companyname','$email','$voulmeNumber',0,'$transaction_type','$how_about_us','$reseller','$current_date_time',$send_ecommercemail,$gatewayid)";
			if(!($show_sql =mysql_query($qry_insert_user)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query <br>");
				print($qry_insert_user);
				exit();
			}
			else 
			{
			$is_success=0;	
				$user_id=mysql_insert_id();
				$user_reference_num=func_User_Ref_No($user_id);
				$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,'userId',$user_id,$cnn_cs);
				if($is_success==1){
			
				/************** to sent registration mail to the  company*********/
				$qry_select_sent = "Select mail_id,mail_sent from cs_registrationmail";
				$rst_select_sent = mysql_query($qry_select_sent,$cnn_cs);
				if (mysql_num_rows($rst_select_sent)>0)
				{
					$mail_sent = mysql_result($rst_select_sent,0,1);
				}
					$email_from = $email1;
					$email_to = $email1;
					$email_subject = "Registration Confirmation";
					$transactiontype=func_get_merchant_name($transaction_type);
					$email_message = func_getreplymailbody_admin($companyname,$username,$password,$user_reference_num,$transactiontype ,$how_about_us,$voulmeNumber);
					
					if(!func_send_mail($email_from,$email_to,$email_subject,$email_message))
					{
						print "An error encountered while sending the mail.";
										
					}
					
				if($mail_sent==1)
				{
					$email_from = $email1;
					$email_to = $email;
					$email_subject = "Registration Confirmation";
					$email_message = func_getreplymailbody_gateway($companyname,$username,$password,$companyname1,$email1,$url1,$user_reference_num);
					if(!func_send_mail($email_from,$email_to,$email_subject,$email_message))
					{
						print "An error encountered while sending the mail.";
						exit();				
					}
				}
			}
				/*************************************************************************/
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>An email has been sent with your offshore merchant account infomation</font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
			//	$msgtodisplay = $email_message;
			}
				
		}		     
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<title></title>

<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: black;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #CCCCCC 
}
</style>

</head>

<body topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="773" align="center" class="blkbd1">
  <tr>
    <td class="whitebtbd" ><img border="0" src="images/cards.gif" width="199" height="23"></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="773" align="center" bgcolor="#658343" class="blkbd1">
  <tr>
    <td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="773" align="center" class="blkbd1" height="460">
  <tr>
      <td height="25" valign="top" align="center" width="100%">
&nbsp;

 <?php 
 	 print $msgtodisplay;
 	//print func_getreplymailbody_htmlformat($companyname,$username,$password);
    ?>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="773" align="center" class="blkbd1" height="20">
  <tr>
    <td height="25" bgcolor="#2F6468"></td>
  </tr>
</table>




</body>

</html>
