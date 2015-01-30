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
// add_Merchant.php:	The admin page functions for the New Company setup.

if($_REQUEST['gw_id']) $gateway_db_select = intval($_REQUEST['gw_id']);
if($gateway_db_select==1)$gateway_db_select=3;
if($_SESSION["gw_id"]==1)$_SESSION["gw_id"]=3;
require_once("includes/indexheader.php");
require_once($rootdir."includes/function2.php");
	etel_smarty_display('main_header.tpl');

foreach($_POST as $k => $c)
	$postback.= "<input type='hidden' name='$k' value='$c' >";


	$companyInfo['en_company'] = quote_smart($_POST['newcompany']);
	$companyInfo['en_ref'] = substr(strtoupper(md5(time()+rand(1,1000000))),0,8);
	$companyInfo['en_email'] = quote_smart($_POST['email']);
	$companyInfo['en_username'] = trim(strtolower(quote_smart($_POST['username'])));
	$companyInfo['en_password'] = strtolower(substr(md5(time()),0,6));
	$companyInfo['how_about_us'] = quote_smart($_POST['how_about_us']);
	if($_POST['reseller']) $companyInfo['how_about_us'] = quote_smart($_POST['reseller']);
	$companyInfo['volumenumber'] = quote_smart($_POST['merchant_volume']);
	$companyInfo['url1'] = quote_smart($_POST['url1']);
	$companyInfo['phonenumber'] = quote_smart($_POST['phonenumber']);
	$companyInfo['contact_phone'] = quote_smart($_POST['phonenumber']);
	$companyInfo['cd_timezone'] = quote_smart($_POST['cd_timezone']);
	$companyInfo['en_gateway_ID'] = $_SESSION['gw_id'];
	if(!$companyInfo['cd_timezone']) $companyInfo['cd_timezone'] = '-7.0';
	$companyInfo['cd_contact_im'] = quote_smart($_POST['cd_contact_im_type'].$_POST['cd_contact_im']);

	$companyInfo['transaction_type'] = (isset($_POST['rad_order_type'])?quote_smart($_POST['rad_order_type']):"");
	if(!$companyInfo['transaction_type']) $transaction_type = 'Adult';
	
	$companyInfo['etel_reseller_ref'] = $_COOKIE['etel_reseller_ref'];	
	$companyInfo['etel_merchant'] = true;
	
	$result = add_new_merchant($companyInfo,true,true);
	
	if($result['status'] == true)
	{
		$msgtodisplay = $result['email_info']['et_htmlformat'];
				
		print "<div  style='border:1;padding:30; ' align='left' >$msgtodisplay</div>
			<!-- Start Conversion Code -->
			<img src='/trackpoint/tp.php?name=Etelegate%20Signup&u=1&amount=5' width='1' height='1'>
			<!-- End Conversion Code -->
		";
	}
	else
	{
		message($result['msg'].$postback,"","Error","content.php?show=main_applynow",false);
		etel_smarty_display('main_footer.tpl');
		die();
	}
etel_smarty_display('main_footer.tpl');
?>