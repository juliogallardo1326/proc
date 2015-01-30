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
// viewcompanyNext.php:	This admin page functions for displaying the company details. 

include ("includes/sessioncheck.php");
$headerInclude="merchant";
include("includes/header.php");
include("includes/message.php");
include("includes/mail_letter_template.php"); //for getting the reply mail content

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$company_id = isset($HTTP_GET_VARS['companyId'])?$HTTP_GET_VARS['companyId']:"";
$msgtodisplay="Email Not Sent.";
if($company_id !="") {

	$qry_select_user = "select * from cs_companydetails as c left join `cs_resellerdetails`as r on r.reseller_id=c.reseller_id where userId='$company_id'";
	if(!($show_sql = mysql_query($qry_select_user)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$companyInfo = mysql_fetch_assoc($show_sql);
	
	$letterTempate = 'merchant_referral_letter';
				
	$emailData["email"] = $companyInfo['email'];
	$emailData["full_name"] = "Merchant";
	$emailData["companyname"] = $companyInfo['companyname'];
	$emailData["resellername"] = $companyInfo['reseller_companyname'];
	$emailData["reselleremail"] = $companyInfo['reseller_email'];
	$emailData["username"] = $companyInfo['username'];
	$emailData["password"] = $companyInfo['password'];
	$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
	$emailData["gateway_select"] = $companyInfo['rd_gateway_id'];

	send_email_template($letterTempate,$emailData);
	$emailInfo= get_email_template($letterTempate,$emailData);

	$msgtodisplay = "Email sent to '".$companyInfo['companyname']."' at '".$emailData["email"]."' Successfully.";
	$outhtml	  ="Y";
	message($msgtodisplay,$outhtml,$headerInclude);

include("includes/footer.php");


}
?>