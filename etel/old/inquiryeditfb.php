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
//inquiryeditfb.php:	The page functions for entering the creditcard details.
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="customerservice";	
include 'includes/topheader.php';
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$i_note_id = (isset($HTTP_POST_VARS['noteid'])?Trim($HTTP_POST_VARS['noteid']):"");
$str_customer_notes = (isset($HTTP_POST_VARS['txt_customer_notes'])?Trim($HTTP_POST_VARS['txt_customer_notes']):"");
$i_solved = (isset($HTTP_POST_VARS['chk_solved'])?Trim($HTTP_POST_VARS['chk_solved']):"0");

$str_query = "update cs_callnotes set customer_notes = '".$str_customer_notes."',solved = ".$i_solved." where note_id = ".$i_note_id;
//print($str_query);
if(!($show_insert_run =mysql_query($str_query,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
else
{
	$msgtodisplay="Thank you for your order";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
}	
?>