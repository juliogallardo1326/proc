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
//atmverify.php:	The page functions for verifying the bank details. 
include 'includes/sessioncheck.php';
require_once( '../includes/function.php');
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
$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?quote_smart($HTTP_POST_VARS['bankroutingcode']):"");
$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?quote_smart($HTTP_POST_VARS['bankaccountno']):"");

if($accounttype=="checking") {
	$account_type ="C";
} else {
	$account_type ="S";
}
	$ret_integration_result = func_bank_integration_result($firstname,$lastname,$amount,$account_type,$bankaccountno,$bankroutingcode);
	$ret_integration_resultarray=split(",",$ret_integration_result);
	if($ret_integration_resultarray[0] =='A') {
		$bank_return = "Success.";
		$msgtodisplay="ATM Verification Success from bank.";
	}else if($ret_integration_resultarray[0] =='D') {
		$bank_return = "Declined.";
		$msgtodisplay="ATM Verification declined from bank <br> due to $ret_integration_resultarray[1].";
	}else if($ret_integration_resultarray[0] =='E') {
		$bank_return = "Error.";
		$msgtodisplay="ATM Verification processing error.";
	}else{
		$bank_return = "Error.";
		$msgtodisplay="System processing error.";
	}
?>
<table style="border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;" cellpadding="0" width="100%" cellspacing="0" height="100">
<tr>
<td width="100%" valign="middle" align="center">&nbsp;
<font face='verdana' size='1'><strong><?php print $msgtodisplay; ?></strong></font>
</td></tr>
<tr><td height="30" valign="center" align="center">
<a href="#" onclick='javascript:window.close()'>Close</a>
</td></tr>
</table>
