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
//addcallcenteruserfb.php:		The page functions for callcenter users for this usertype = 1. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="callcenter";
include 'includes/topheader.php';

$companyname = (isset($HTTP_POST_VARS['companyname'])?Trim($HTTP_POST_VARS['companyname']):"");
$companycontact = (isset($HTTP_POST_VARS['contactnumber'])?Trim($HTTP_POST_VARS['contactnumber']):"");
$address = (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
$amount = (isset($HTTP_POST_VARS['amount'])?Trim($HTTP_POST_VARS['amount']):"");
$voiceauthfee = (isset($HTTP_POST_VARS['voiceauthfee'])?Trim($HTTP_POST_VARS['voiceauthfee']):"");
$hidcompanyname=(isset($HTTP_POST_VARS['hidcompanyname'])?Trim($HTTP_POST_VARS['hidcompanyname']):"");
$password= (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
$repassword= (isset($HTTP_POST_VARS['repassword'])?Trim($HTTP_POST_VARS['repassword']):"");
$company_id = (isset($HTTP_POST_VARS['companyid'])?Trim($HTTP_POST_VARS['companyid']):"");
$iCCUserId = (isset($HTTP_POST_VARS['hid_CCUserId'])?Trim($HTTP_POST_VARS['hid_CCUserId']):"");

if($companyname == "" || $companycontact == "" || $address == "" || $amount == "" || $voiceauthfee == ""){
	$msgtodisplay="Insufficient data.";
	$outhtml="Y";				
	message($msgtodisplay,$outhtml,$headerInclude);  
	exit();
}

if($companyname !=$hidcompanyname)
{
if(func_checkCompanynameExistInAnyTable($companyname,$cnn_cs)){
$msgtodisplay="Company Name already exist.";
	$outhtml="Y";				
	message($msgtodisplay,$outhtml,$headerInclude);  
	exit();
}
}
if($password == "") {
	$msgtodisplay="Please enter Password.";
	$outhtml="Y";				
	message($msgtodisplay,$outhtml,$headerInclude);  
	exit();
}

$qry_update = "Update cs_callcenterusers set comany_name='$companyname',company_conatct_no='$companycontact',address='$address',amount=$amount,user_password='$password',voice_auth_fee=$voiceauthfee where cc_usersid = $iCCUserId";
if(mysql_query($qry_update,$cnn_cs))
{
	$msgtodisplay="User details updated successfully";
	$outhtml="Y";				
	message($msgtodisplay,$outhtml,$headerInclude);  
	exit();
}
else
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print ($qry_insert."<br>");
	print("Cannot execute query");
	exit();
}
?>
<html>
<head>
</head>
<body onLoad="javascript:func_submit();">
<script language="JavaScript">
function func_submit()
{
	window.location = "addcallcenteruser.php";
}
</script>
</body>
</html>

