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

$headerInclude="companies";
include("includes/header.php");


$i_company_id = (isset($HTTP_GET_VARS["company_id"])?quote_smart($HTTP_GET_VARS["company_id"]):"");
if ($i_company_id != "" && is_numeric($i_company_id))
{
	$qry_copydetails = "INSERT IGNORE INTO cs_deletedcompanydetails SELECT * FROM cs_companydetails where  cs_companydetails.userId=$i_company_id";
	mysql_query($qry_copydetails,$cnn_cs) or dieLog("Unable to add deleted company. ".mysql_error());
	$qryDelete = "delete from cs_companydetails where userId = $i_company_id";
	mysql_query($qryDelete,$cnn_cs) or dieLog("Unable to delete company. ".mysql_error());

	$msgtodisplay="Company details deleted successfully";
	message($msgtodisplay,"Y",$headerInclude);  
}
?>