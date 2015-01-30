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
//addtsrfb.php:		The page functions for tsr users for this usertype = 0. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="ordermanagement";
include 'includes/topheader.php';


$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
$prev_username = (isset($HTTP_POST_VARS['hid_username'])?Trim($HTTP_POST_VARS['hid_username']):"");
$password= (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
$repassword= (isset($HTTP_POST_VARS['repassword'])?Trim($HTTP_POST_VARS['repassword']):"");
$user_id= (isset($HTTP_POST_VARS['userid'])?Trim($HTTP_POST_VARS['userid']):"");
$company_id = (isset($HTTP_POST_VARS['companyid'])?Trim($HTTP_POST_VARS['companyid']):"");

if($username == "") {
	$msgtodisplay="Please enter user name.";
	$outhtml="Y";				
	message($msgtodisplay,$outhtml,$headerInclude);  
	exit();
}
if ($prev_username != $username)
{
	if(func_checkUsernameExistInAnyTable($username,$cnn_cs)) {
		$msgtodisplay="User name already exist.";
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

if ($user_id=="")
{
	$qry_insert = "Insert into cs_companyusers (username,password,userid,teleusertype) values ('$username','$password',$company_id,'1')";
	if(!mysql_query($qry_insert,$cnn_cs))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_insert."<br>");
		print("Cannot execute query");
		exit();
	}
}
else
{
	$qry_update = "Update cs_companyusers set username='$username',password='$password' where id=$user_id";
	if(!mysql_query($qry_update,$cnn_cs))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_update."<br>");
		print("Cannot execute query");
		exit();
	}
}
?>
<html>
<head>
</head>
<body onLoad="javascript:func_submit();">
<script language="JavaScript">
function func_submit()
{
	window.location = "addtsruser.php";
}
</script>
</body>
</html>

