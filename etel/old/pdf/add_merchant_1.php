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
// companyAdd.php:	The admin page functions for the New Company setup. 
require_once("includes/dbconnection.php");
require_once("includes/function.php");

include("admin/includes/mailbody_replytemplate.php"); //for getting the reply mail content

	$company = (isset($HTTP_POST_VARS['company'])?Trim($HTTP_POST_VARS['company']):"");
	$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?Trim($HTTP_POST_VARS['companyname']):"");
	$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?Trim($HTTP_POST_VARS['phonenumber']):"");
	$address = (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
	$city = (isset($HTTP_POST_VARS['city'])?Trim($HTTP_POST_VARS['city']):"");
	$state = (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
	$ostate = (isset($HTTP_POST_VARS['ostate'])?Trim($HTTP_POST_VARS['ostate']):"");
	$country = (isset($HTTP_POST_VARS['country'])?Trim($HTTP_POST_VARS['country']):"");
	$zipcode = (isset($HTTP_POST_VARS['zipcode'])?Trim($HTTP_POST_VARS['zipcode']):"");
	$email = (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
	
	$contactName = (isset($HTTP_POST_VARS['contact'])?Trim($HTTP_POST_VARS['contact']):"");
	$transaction_type = (isset($HTTP_POST_VARS['rad_order_type'])?Trim($HTTP_POST_VARS['rad_order_type']):"");

	$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?Trim($HTTP_POST_VARS['how_about_us']):"");
	$voulmeNumber = (isset($HTTP_POST_VARS['voulme'])?Trim($HTTP_POST_VARS['voulme']):"");
	$current_date_time = func_get_current_date_time();
	if($company)
	{
		$qry_select_user = "select username  from cs_companydetails where (username='$username' or companyname='$companyname') and email='$email'";
		//print $qry_select_user;
		//exit();
		if(!($show_sql = mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		elseif(mysql_num_rows($show_sql) >0) 
		{
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing username !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
		}
        else
		{
			if($state=="- - -Select- - -") 
			{
				$state=null;
			}
			$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,phonenumber,address,city,state,";
			$qry_insert_user .= " ostate,country,zipcode,email,contactname,volumenumber,activeuser,transaction_type,how_about_us,date_added)";
			$qry_insert_user .= " values('$username','$password','$companyname','$phonenumber','$address','$city',";
			$qry_insert_user .= "'$state','$ostate','$country','$zipcode','$email','$contactName','$voulmeNumber',0,'$transaction_type','$how_about_us','$current_date_time')";
			if(!($show_sql =mysql_query($qry_insert_user)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query <br>");
				exit();
			}
			else 
			{
				/************** to sent registration mail to the  company*********/
				$qry_select_sent = "Select mail_id,mail_sent from cs_registrationmail";
				$rst_select_sent = mysql_query($qry_select_sent,$cnn_cs);
				if (mysql_num_rows($rst_select_sent)>0)
				{
					$mail_sent = mysql_result($rst_select_sent,0,1);
				}
				if($mail_sent==1)
				{
					$arrFiles[0] = "";
					$arrFileNames[0] = "";
					$email_from = $_SESSION["gw_emails_sales"];
					$email_to = $email;
					$email_subject = "Registration Confirmation";
					$email_message = func_getreplymailbody($companyname,$username,$password);
		/*			if(!sendMail($email_from,$email_subject,$email_message,$email_to,$arrFiles,$arrFileNames))
					{
						print "An error encountered while sending the mail.";
						exit();				
					}
		*/		}
				/*************************************************************************/
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>An email has been sent with your offshore merchant account infomation</font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
			}
				
		}		     
	}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<title>Company Setup</title>

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

<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1">
  <tr>
    <td class="whitebtbd" ><img border="0" src="images/logo2os.jpg" width="80" height="58"><img border="0" src="images/cards.gif" width="199" height="23"></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" bgcolor="#658343" class="blkbd1">
  <tr>
    <td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="460">
  <tr>
    <td height="25" valign="top" align="center" width="165" bgcolor="#FFFFFF">
      <table border="0" cellpadding="0" width="100%" height="249">
        <tr>
          <td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
          <td width="1%" ><img border="0" src="images/spacer.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td width="100%" height="178" colspan="2" valign="top"><!--<img border="0" src="images/service.jpg" width="160" height="176">--></td>
        </tr>
      </table>
    </td>
    <td height="25" valign="top" align="center" width="607">
&nbsp;

 <?php 
 // print $msgtodisplay;
 	print func_getreplymailbody($companyname,$username,$password);
    ?>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="20">
  <tr>
    <td height="25" bgcolor="#2F6468"></td>
  </tr>
</table>




</body>

</html>
