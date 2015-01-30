<?php 
//******************************************************************//
//  This file is part of the Zerone Consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Etelegate.com
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//creditcard.php:	The page functions for entering the creditcard details.
include '../includes/dbconnection.php';
include 'includes/sessioncheck.php';
require_once( '../includes/function.php');
include('../includes/function1.php');
$headerInclude="home";	
include 'includes/header.php';


	$iShopNumber		= $HTTP_GET_VARS["SHOP_NUMBER"];

	$selectBankUpdates = "Select * from cs_scanorder where transactionId = $iShopNumber";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_status = "";
	$str_decline_reason = "";

	if (mysql_num_rows($run_Select_Qry) != 0) {
		$str_status = mysql_result($run_Select_Qry, 0, 3);
		$decline_reason = $str_status == "yes" ? "" : mysql_result($run_Select_Qry, 0, 4);
	}
	$approval_status = $str_status == "yes" ? "A" : "D";
	$pass_status = "PA";

	func_update_approval_status($cnn_cs, $iShopNumber, $approval_status, $pass_status, $decline_reason);
	$referenceNumber = func_get_value_of_field($cnn_cs,"cs_transactiondetails","reference_number","transactionId",$iShopNumber);

	if($str_status == "yes" ) {
	$strMessage = "SUC";
		$strMessage = "<center><br><br><h3>Thank-you for your order</h3>Your order number is $referenceNumber. Please refer to this in any correspondence.</center>";
	}else{
	$strMessage = "DEC";
		$strMessage = "<p style='margin-left:40;margin-right:40'><b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $referenceNumber has been declined - ".$decline_reason.".</p>";
	}
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="600" class="disbd">
		<tr>
		  <td width="100%" valign="top" align="center" bgcolor="#999999" height="20">
		  <img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="middle" align="left" height="35" class="disctxhd">&nbsp; Message</td>
		</tr>
		<tr>
		 <td width="100%" valign="top" align="center">
		 <table width="500" border="0" cellpadding="0"  >
		  <tr><td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx"><?=$strMessage?></span></td></tr>
		  <tr><td align="center" valign="middle" height="30">&nbsp;<!--<a href="creditcard.php"><img border="0" src="images/back.gif"></a>--></td></tr>
		  </table>
		  </td>
		</tr>
        </table>
		</td>
     </tr>
</table>
<?php 
include 'includes/footer.php';
?>