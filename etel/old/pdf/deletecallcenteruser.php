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
// deleteteleUser.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");


$userid =isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"";
if ($userid!="" && is_numeric($userid))
{
	$qry_delete = "Delete from cs_callcenterusers where cc_usersid=$userid";
	if(!mysql_query($qry_delete,$cnn_cs))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_delete."<br>");
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
	window.location = "editcallcenteruserview.php";
}
</script>
</body>
</html>
