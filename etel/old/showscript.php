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
// telescript.php:	The  page used to create tha tele script. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude="script";	
include 'includes/topheader.php';
$Packagename = "";
$Packageproduct = "";
$Packageprice = "";
$Packagerefundpolicy = "";
$Packagedescription = "";
$Processor="";
$trans_type =isset($HTTP_GET_VARS["type"])?$HTTP_GET_VARS["type"]:"";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$qrt_select_script ="select userId,username,telepackagename,telepackageprod,telepackageprice,telerefundpolicy,teledescription,processor,companyname,customer_service_phone from cs_companydetails where userid=$sessionlogin";	
//print $qrt_select_script;
if(!($show_script_sql =mysql_query($qrt_select_script,$cnn_cs))) {
	echo mysql_errno().": ".mysql_error()."<BR>";
	exit();
}else {
	$Packagename = mysql_result($show_script_sql,0,2);
	$Packageproduct = mysql_result($show_script_sql,0,3);
	$Packageprice = mysql_result($show_script_sql,0,4);
	$Packagerefundpolicy = mysql_result($show_script_sql,0,5);
	$Packagedescription = mysql_result($show_script_sql,0,6);
	$Processor =  mysql_result($show_script_sql,0,7);
	$companyname =  mysql_result($show_script_sql,0,8);
	$customerservicephone =  mysql_result($show_script_sql,0,9);
}

?>
<style type="text/css">
.txtv{font-family:verdana;font-size:11px;color:black}
.bd{border:1px solid #d2d2d2}
</style>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="59%">
  <tr>
    <td width="83%" valign="top" align="center">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="98%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Verification 
            Script </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
		      <table width="100%" cellspacing="0" cellpadding="0" >
                <tr> 
                  <td align="left" valign="middle">
<?php if($trans_type=="Check") { ?>
				  
<table border="0" cellpadding="0" cellspacing="0" width="98%" height="100%" align="center" class="bd">
  <tr>
    <td width="100%" valign="top" align="left">
      <p style="line-height: 100%; margin-left: 20; margin-right: 20; margin-top: 10; margin-bottom: 10" align="justify"><span class="txtv">Thank you for calling the <?=$Packagename?> verification system.<br><br>
      Please enter your rep ID followed by the # key.<br><br>
      Please enter the customer’s telephone number, followed by the # key.<br><br>The number you have entered is XXXXXXXXXX&nbsp;<br>
		To continue press 1&nbsp;<br>
		To correct, press 2<br>
      <br>
      Customer, the next few questions are for you.  You can press the * key during any question to have that question repeated.  After the tone, please state your full name and address including city, state, and zip code, then press the # key.<br>
      <br>
      Please state YES after the tone to confirm you are under the age of 65?<br>
      <br>
      Customer, even though we have a previous relationship, before we ask for your banking information, I would like to establish that we keep no records of these events.  To confirm that you understand this, please say YES, and then press # to continue.<br>
      <br>
      After the tone, please state your social security number and Drivers license number including the state it was issued in, and then press the # key.<br>
      <br>
      After the tone, please state the name of your bank where your checking or savings account is located and the billing date you would like the funds debited, then press the # key.<br>
      <br>
      At the tone, please ready your account and routing numbers.  These are the numbers on the bottom of your check and read from left to right ignoring spaces, then press the # key.<br>
      <br>
      To confirm that you are the authorized signer on this account, please say YES or NO after the tone then press the # key.<br>
      <br>
      You will be charged <?=$Packageprice?> for the <?=$Packagename?> <?=$Packageproduct?>.  The charge will appear on your next month’s checking account statement as <?=$Processor?>.  Should this draft of EFT be returned or unpaid, a returned item fee may also be debited from your account, or electronically drafted..  You will be charged the maximum allowed by your state.  D0 we have your final authorization to draft the processing fee from your account?  T confirm, at the tone please say YES or NO, then press the # key.<br>
      <br>
      Included in your <?=$Packageproduct?> will be <?=$Packagedescription?>.  Your package will be shipped by UPS, which needs to be signed for by you.  You will also receive a mailed copy of this voice recording in the form of a receipt which outlines the terms and conditions within the next week for your records.  To confirm your order, at the tone please state YES or NO, then press the # key.<br>
      <br>
      Thank you for your phone order.  If you have any questions, please call our customer service number at <?=$customerservicephone?> which is available [Hours].  This call will end with your verification #.  Thank you and have a great day.<br>
      <br>
      The verification # is XXXXXXXXXX<br>
      To repeat, press 1<br>
      To exit the system, press 2<br>
      </span></td>
  </tr>
</table>

<?php } else { ?>

<table border="0" cellpadding="0" cellspacing="0" width="98%" height="100%" align="center" class="bd">
  <tr>
    <td width="100%" valign="top" align="left">
      <p style="line-height: 100%; margin-left: 20; margin-right: 20; margin-top: 10; margin-bottom: 10" align="justify"><span class="txtv">Thank you for calling the <?=$companyname?> verification system.<br><br>
      Please enter your rep ID followed by the # key.<br><br>
      Please enter the customer’s telephone number, followed by the # key.<br>
	  <br>The number you have entered is XXXXXXXXXX&nbsp;<br>
		To continue press 1&nbsp;<br>
		To correct, press 2<br>
      <br>
      Customer, the next few questions are for you. You can press the * key during any question to have that question repeated. After the tone, please state your full name and address including city, state, and zip code, then press the # key.<br>
      <br>
      Please state YES after the tone to confirm you are under the age of 65?<br>
      <br>
      At the tone, please state your credit card number, expiration date, and the 3 digit security code on the back of your credit card.<br>
      <br>
      To confirm that you are the authorized signer on this credit card account, please say YES or NO after the tone then press the # key.<br>
      <br>
      You will be charged <?=$Packageprice?> for the <?=$companyname?>&nbsp;<?=$Packageproduct?>. The charge will appear on your next month’s credit card statement as <?=$Processor?>. Do we have your final authorization to bill your credit card today? To confirm, at the tone please say YES or NO, then press the # key.<br>
      <br>
      Included in your <?=$Packageproduct?> will be <?=$Packagedescription?>. Your <?=$Packageproduct?> will be shipped by UPS, which needs to be signed for by you. You will also receive a mailed copy of this voice recording in the form of a receipt which outlines the terms and conditions within the next week for your records. To confirm your order, at the tone please state YES or NO, then press the # key.<br>
      <br>
      Thank you for your phone order. If you have any questions, please call our customer service number at <?=$customerservicephone?> which is available [Hours]. This call will end with your verification #. Thank you and have a great day.<br>
      <br>
      The verification # is XXXXXXXXXX<br>
      To repeat, press 1<br>
      To exit the system, press 2<br>
      </span></td>
  </tr>
</table>

<?php } ?>

			  </td>
                </tr>
              </table>
		</td>
		  </tr>
			<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
     </tr>
</table><br>
<?
include 'includes/footer.php';
?>