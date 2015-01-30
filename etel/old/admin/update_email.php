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
// update_email.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude = "mail";
include("includes/message.php");

$i_user_id = (isset($HTTP_POST_VARS["hid_user_id"])?quote_smart($HTTP_POST_VARS["hid_user_id"]):"");
$str_email = (isset($HTTP_POST_VARS["email"])?quote_smart($HTTP_POST_VARS["email"]):"");

if ($i_user_id != "") {
	$str_query = "update cs_companydetails set email = '$str_email' where userId = $i_user_id";
	if(!($rstSelect =mysql_query($str_query)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_query = "delete from cs_bad_emails  where user_id = $i_user_id";
	if(!($rstSelect =mysql_query($str_query)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	header("location:bad_emails.php");
}
?>
