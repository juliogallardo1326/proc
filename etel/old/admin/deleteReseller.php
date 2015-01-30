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
// deleteReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

$headerInclude="reseller";
include("includes/header.php");
include("includes/message.php");
$i_reseller_id = (isset($HTTP_GET_VARS["reseller_id"])?quote_smart($HTTP_GET_VARS["reseller_id"]):"");
$qryDelete = "delete from cs_resellerdetails where reseller_id = $i_reseller_id";
if ( !mysql_query($qryDelete,$cnn_cs)) {
	print("Can not execute query");
	exit();
}
$msgtodisplay="Reseller deleted successfully";
message($msgtodisplay,"Y",$headerInclude);  
?>