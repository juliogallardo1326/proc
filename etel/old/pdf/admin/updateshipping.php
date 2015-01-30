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
// CompanyUser.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


include("includes/message.php");

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$i_time_frame=0;
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$i_count = (isset($HTTP_POST_VARS["icount"])?quote_smart($HTTP_POST_VARS["icount"]):"");
$update_no =0;
for($i=1;$i<=$i_count;$i++) {
	$trans_id = (isset($HTTP_POST_VARS["tid$i"])?quote_smart($HTTP_POST_VARS["tid$i"]):"");
	$shipping_no = (isset($HTTP_POST_VARS["shipping$i"])?quote_smart($HTTP_POST_VARS["shipping$i"]):"");
	if($shipping_no !="") {
		$update_no = $update_no +1;
		$qrt_update_details = "Update cs_transactiondetails set shippingTrackingno ='$shipping_no' where transactionId=$trans_id";
		if(!mysql_query($qrt_update_details,$cnn_cs)) {
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Can not execute query pass update query");
			exit();
		}
	}
}
?>
<body onload="document.dates1.submit();">
		<form name="dates1" action="shippingdetails.php"  method="POST">
			<input type="hidden" name="updated"	value="Yes">
			<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
			<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
			<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
			<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
			<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
			<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
		</form>
</body>			
		
