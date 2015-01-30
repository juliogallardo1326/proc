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
//addReseler_fb.php:		The page functions for reseller users for this .

require_once("includes/function.php");
include("reseller/includes/mail_letter_template.php");

$companyname = (isset($HTTP_POST_VARS['companyname'])?Trim($HTTP_POST_VARS['companyname']):"");
if($companyname!="")
{
	$contactname 	= (isset($HTTP_POST_VARS['contactname'])?Trim($HTTP_POST_VARS['contactname']):"");
	$username 		= (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$password		= (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
	$repassword		= (isset($HTTP_POST_VARS['repassword'])?Trim($HTTP_POST_VARS['repassword']):"");
	$email			= (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
	$confirmemail	= (isset($HTTP_POST_VARS['confirmemail'])?Trim($HTTP_POST_VARS['confirmemail']):"");
	$sgatewayCompanyId  = (isset($HTTP_POST_VARS['gatewayCompanyName'])?Trim($HTTP_POST_VARS['gatewayCompanyName']):"");	
	$merchantmonthly	= (isset($HTTP_POST_VARS['merchantmonthly'])?Trim($HTTP_POST_VARS['merchantmonthly']):"");
	$phone			= (isset($HTTP_POST_VARS['phone'])?Trim($HTTP_POST_VARS['phone']):"");
	$url			= (isset($HTTP_POST_VARS['url'])?Trim($HTTP_POST_VARS['url']):"");

	if($companyname == "" || $contactname == ""){
		$msgtodisplay="Insufficient data.";
		message($msgtodisplay);  
		exit();
	}
	if($username == "") {
		$msgtodisplay="Please enter user name.";
		message($msgtodisplay);  
		exit();
	}
	if(func_checkUsernameExistInAnyTable($username,$cnn_cs)) {
		$msgtodisplay="User name already exist.";
		message($msgtodisplay);  
		exit();
	}
	if(func_checkEmailExistInAnyTable($email,$cnn_cs)) {
		$msgtodisplay="Email already exist.";
		message($msgtodisplay);  
		exit();
	}
	if(func_checkCompanynameExistInAnyTable($companyname,$cnn_cs)) {
		$msgtodisplay="Company already exist.";
		message($msgtodisplay);  
		exit();
	}
	
	if($password == "") {
		$msgtodisplay="Please enter Password.";
		message($msgtodisplay);  
		exit();
	}
	$qry_select="SELECT cs_companydetails.companyname, cs_companydetails.url1, cs_logo.logo_filename,cs_companydetails.email FROM cs_companydetails, cs_logo WHERE cs_companydetails.userId ='$sgatewayCompanyId' AND cs_companydetails.userId = cs_logo.Logo_company_id";
	$result=mysql_query($qry_select,$cnn_cs);
	
	if($result)
	{
		$rs=mysql_fetch_array($result);
		$sgatewayCoName=$rs[0];
		$sgatewayUrl=$rs[1];
		$sgatewayLogo=$rs[2];
		$sgatewayemail=$rs[3];
			}
	else
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$current_date_time = func_get_current_date_time();
	$qry_insert = "Insert into cs_resellerdetails (reseller_username, reseller_password, reseller_date_added, reseller_companyname, reseller_contactname, reseller_email, reseller_phone, reseller_url, reseller_monthly_volume,gateway_id) values ('$username', '$password', '$current_date_time', '$companyname', '$contactname', '$email', '$phone', '$url', '$merchantmonthly','$sgatewayCompanyId')";
	if(mysql_query($qry_insert,$cnn_cs))
	{
		$msgtodisplay = func_gatewayreseller_loginletter();
		
		$msgtodisplay = str_replace("[GatewayResellerCompanyName]",$sgatewayCoName,$msgtodisplay);
		$msgtodisplay = str_replace("[UserName]",$username,$msgtodisplay);		
		$msgtodisplay = str_replace("[PassWord]",$password,$msgtodisplay);
		$msgtodisplay = str_replace("[Gateway_Logo]",$sgatewayLogo,$msgtodisplay);
		$msgtodisplay = str_replace("[Gateway_Email]",$sgatewayemail,$msgtodisplay);
		$msgtodisplay = str_replace("[GatewayCompanyURL]",$sgatewayUrl,$msgtodisplay);
		
		//$msgtodisplay = str_replace("[Gateway_Url]",$sgatewayUrl,$msgtodisplay);		
		message($msgtodisplay);
		$email_from = "$sgatewayemail";
		$email_subject = "Registration Confirmation";
		$email_message = $msgtodisplay;
		$email_to= $email;
		//if(!sendMail($email_from,$email_subject,$email_message,$email_to,$arrFiles,$arrFileNames))
		if(!func_send_mail($email_from,$email_to,$email_subject,$email_message))
		{
			print "An error encountered while sending the mail.";
			exit();				
		}
		exit();
	}
	else
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
}


function func_checkemail($email,$cnnConnection)
{
	$str_return=0;
	
	$qry_select = "Select reseller_id from cs_resellerdetails where reseller_email='$email'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
	
	
			
	}
		return $str_return;
}	
	
function message($msgtodisplay){
?>
<html>
<head>
<title></title>
<style type="text/css" rel="stylesheet">
.rs_rightbd{border-right:1px solid #CFDBE4}
.rs_bd{border-right:1px solid #CFDBE4;border-left:1px solid #B0BBCA}
.rs_bdwhite{border-bottom:1px solid #F1F6FD}
.rs_fbd{border:1px solid #839EC4}
</style>
</head>

<body topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center" class="rs_bd">
  <tr>
    <td bgcolor="#F2F9F8"><img border="0" src="images/spacer.gif" width="263" height="100" alt=""><img border="0" src="images/2_res.jpg" width="252" height="100" alt="" ><img border="0" src="images/3_res.jpg" width="263" height="100" alt=""></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
  <tr>
    <td bgcolor="#9EB0CD" height="18"></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center" height="400">
  <tr>
    <td width="155" bgcolor="#EEF1F7" class="rs_bd" valign="top" align="left">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="100%" bgcolor="#ACBBD5" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#BECADE" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#D2DBE8" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#DDE4EE" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#E6EBF2" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#F1F4F8" height="20" >&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="621" class="rs_rightbd" valign="middle" align="center">
      <table border="0" cellpadding="0" cellspacing="1" width="550" height="350">
        <tr>
          <td height="20" bgcolor="#698AB8"></td>
        </tr>
        <tr>
          <td height="330" align="center" valign="top" class="rs_fbd">&nbsp; 
            <?=$msgtodisplay?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
  <tr>
    <td bgcolor="#859BC0" height="20"></td>
  </tr>
</table>
</body>
</html>
<?php 
}

?>


