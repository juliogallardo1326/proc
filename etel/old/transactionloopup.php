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
require_once('includes/function.php');

$check_card_no= (isset($HTTP_POST_VARS['checkcardno'])?quote_smart($HTTP_POST_VARS['checkcardno']):"");
$voice_auth_id = (isset($HTTP_POST_VARS['voiceauthno'])?quote_smart($HTTP_POST_VARS['voiceauthno']):"");
$telephone_no = (isset($HTTP_POST_VARS['telephoneno'])?quote_smart($HTTP_POST_VARS['telephoneno']):"");
$transaction_no = (isset($HTTP_POST_VARS['transactionno'])?quote_smart($HTTP_POST_VARS['transactionno']):"");
$whereqry ="";
	if($transaction_no !="") {
		$whereqry = " and a.transactionId=$transaction_no";
	}
	if($check_card_no !="") {
		$whereqry .= " and a.CCnumber='".etelEnc($check_card_no)."'";
	}
	if($voice_auth_id !="") {
		$whereqry .= " and a.voiceAuthorizationno='$voice_auth_id'";
	}
	if($telephone_no !="") {
		$whereqry .= " and a.phonenumber='$telephone_no'";
	}
	if($whereqry !="") {
		$select_trans_details ="Select a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.transactionDate,a.voiceAuthorizationno from cs_transactiondetails  as a,cs_companydetails as b where a.userId=b.userId $whereqry"
	} else {
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>No transactions were found based on your search.</font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
	}
?>