<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//check.php:		The page functions for entering the check details. 

include ('OrderProcessing.php');
die();
chdir("..");
session_start();
//if($_SESSION['from_url']) $from_url = $_SESSION['from_url'];
session_unset();
$_SESSION['mt_posted_variables'] = serialize($_REQUEST);
//$_SESSION["gw_database"]='etel_dbsmain';

if($_SESSION['mt_reference_id']) $_REQUEST["mt_reference_id"] = $_SESSION['mt_reference_id'];
if($_SESSION['mt_subAccount']) $_REQUEST["mt_subAccount"] = $_SESSION['mt_subAccount'];

if($_REQUEST['mt_IBILL_refer_url'])
{
	$from_url = $_REQUEST['mt_IBILL_refer_url'];
	$from_url_info = parse_url($from_url);
	
	$HTTP_SERVER_VARS["HTTP_REFERER"] = $_REQUEST['mt_IBILL_refer_url'];
	
	$gateway_db_select=5;
	require_once("includes/dbconnection.php");
	$mt_reference_id = (isset($_REQUEST["mt_reference_id"])?quote_smart($_REQUEST["mt_reference_id"]):"");
	$mt_subAccount = substr($mt_reference_id,0,-3)."-00".substr($mt_reference_id,strlen(substr($mt_reference_id,0,-3)),3);
	//$sql = "SELECT rd_subaccount FROM `cs_rebillingdetails` WHERE (`rd_subName` = '$mt_subAccount') AND `company_user_id` = " .$companyid ;
	$mt_subAccount = str_replace('--','-',$mt_subAccount);
	$_REQUEST["mt_subAccount"] = $mt_subAccount;
	//if(!($result = mysql_query($sql,$cnn_cs)))
	
	$sql = "SELECT cs_reference_ID FROM {$database["database_main"]}.`cs_company_sites` where cs_URL like '%".quote_smart($from_url_info['host'])."%'";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$ref = mysql_result($result,0,0);
	$_REQUEST["mt_reference_id"] = $ref;
	$testmode=$_REQUEST["test"];
	//$testmode = true;
	//https://secure.etelegate.com/testintegration.php?mt_reference_id=126024102&mt_IBILL_refer_url=http://www.junglegirls.com
	$xtra = " If you are an IBill Merchant, please check your merchant email for further integration instructions.";
}

require_once("includes/dbconnection.php");
$cnn_cs = mysql_connect($database["server"],$database["user"],$database["password"]) 
   or dieLog("Could not find server"); 
mysql_select_db($database["database_main"],$cnn_cs) or dieLog("Unable to connect database"); 
require_once('includes/function.php');

require_once($rootdir.'smarty/libs/Smarty.class.php');
$smarty = new Smarty;



$postback = "";
foreach($HTTP_POST_VARS as $k => $c)
	if($k!='PHPSESSID') $postback.= "<input type='hidden' name='$k' value='$c' >";


$smarty->assign("ShowPaymentInfo", 0);

$from_url = (isset($HTTP_SERVER_VARS["HTTP_REFERER"])?quote_smart($HTTP_SERVER_VARS["HTTP_REFERER"]):"");

$post_header="";
$reference_id = (isset($_REQUEST["mt_reference_id"])?quote_smart($_REQUEST["mt_reference_id"]):"");
if(!$reference_id)
{
	$url_info = parse_url($from_url);
	$hashURL=str_replace("www.","",$url_info['host']);
	$reference_id=substr(strtoupper(md5($hashURL)),0,12);
}
//$trans_type = (isset($_REQUEST["mt_transaction_type"])?quote_smart($_REQUEST["mt_transaction_type"]):"");
$mt_return_url = (isset($_REQUEST["mt_return_url"])?quote_smart($_REQUEST["mt_return_url"]):"");
$mt_subAccount = (isset($_REQUEST["mt_subAccount"])?quote_smart($_REQUEST["mt_subAccount"]):"");
$mt_prod_desc = (isset($_REQUEST["mt_prod_desc"])?quote_smart($_REQUEST["mt_prod_desc"]):"");
$mt_prod_price = (isset($_REQUEST["mt_prod_price"])?quote_smart($_REQUEST["mt_prod_price"]):"");
$mt_language = (isset($_REQUEST["mt_language"])?quote_smart($_REQUEST["mt_language"]):"");

$mt_etel900_subAccount = (isset($_REQUEST["mt_etel900_subAccount"])?quote_smart($_REQUEST["mt_etel900_subAccount"]):"");

$mt_checksum = (isset($_REQUEST["mt_checksum"])?quote_smart($_REQUEST["mt_checksum"]):"");

$mt_amount = (isset($_REQUEST["mt_amount"])?quote_smart($_REQUEST["mt_amount"]):"");
$td_product_id = (isset($_REQUEST["mt_product_id"])?quote_smart($_REQUEST["mt_product_id"]):"");

	$sql = "SELECT * FROM {$database["database_main"]}.`cs_company_sites` left join {$database["database_main"]}.`etel_gateways` on `cs_gatewayId` = `gw_id` where cs_reference_ID='$reference_id'";

	if(!($result = mysql_query($sql))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(!$gw_info = mysql_fetch_array($result))
	{
			$invalidlogin="<font face='verdana' color='red'>Your website Reference ID was not found in the database. Please check your Reference Number. $xtra</font>";
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$invalidlogin</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			mysql_select_db('etel_dbscompanysetup',$cnn_cs) or dieLog("Unable to connect database"); 
			toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the reference ID was not found. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount");
			if($etel_debug_mode)echo ($sql);
			print $msgtodisplay;
			exit();
	}
	if(!$gw_info['gw_database']) 
	{
			$invalidlogin="<font face='verdana' color='red'>Invalid Website. Please refer to your Merchant Integration Guide. $xtra</font>";
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$invalidlogin</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			mysql_select_db('etel_dbscompanysetup',$cnn_cs) or dieLog("Unable to connect database"); 
			toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the gw_database is invalid was not found. SQL: $sql");
			if($etel_debug_mode)echo ($sql);
			print $msgtodisplay;
			exit();	
	
	}
	mysql_select_db($gw_info['gw_database'],$cnn_cs) or dieLog("Unable to connect database"); 
	$_SESSION["gw_database"] = $gw_info['gw_database'];
	$_SESSION["gw_id"] = $gw_info['gw_id'];
	$_SESSION["gw_template"] = $gw_info['gw_template'];
	$_SESSION["gw_links"] = $gw_info['gw_links'];
	$_SESSION["gw_folder"] = $gw_info['gw_folder'];
	$_SESSION["gw_index"] = $gw_info['gw_index'];
	$_SESSION["gw_title"] = $gw_info['gw_title'];
	$_SESSION["gw_emails_sales"] = $gw_info['gw_emails_sales'];

$_SESSION['td_product_id'] = $td_product_id;
$_SESSION['mt_reference_id'] = $reference_id;
//$_SESSION['mt_transaction_type'] = $trans_type;
$_SESSION['mt_subAccount'] = $mt_subAccount;
$_SESSION['mt_prod_desc'] = $mt_prod_desc;
$_SESSION['mt_prod_price'] = $mt_prod_price;
$_SESSION['mt_etel900_subAccount'] = $mt_etel900_subAccount;
$_SESSION['integration_mode'] = "Test";

$ipaddress = GetHostByName($_SERVER["REMOTE_ADDR"]); 
$_SESSION['ipaddress']=$ipaddress;



$smarty->compile_check = true;
$smarty->debugging = false;
$curtemplate = $_SESSION['gw_template'];
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("GET", $_GET);

if (!$testmode) 
{
	$_SESSION['integration_mode'] = "Live";
}
else $_SESSION['integration_mode'] = "Test";


	$select_sql = "select * from cs_companydetails left join `{$database["database_main"]}`.`cs_company_sites` as s on cs_company_id=userId where `cs_gatewayId` = ".$_SESSION["gw_id"]." AND cs_reference_ID='$reference_id' and suspenduser='NO'";
	if(!($show_sql_val = mysql_query($select_sql))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(mysql_num_rows($show_sql_val)>0){
		if($companyInfo = mysql_fetch_array($show_sql_val)) {
			$companyid = $companyInfo['userId'];
			$_SESSION['companyid'] = $companyid;
			$_SESSION['cs_URL'] = $companyInfo['cs_URL'];
			$_SESSION['cs_return_page'] = $companyInfo['cs_return_page'];
			$_SESSION['cd_secret_key'] = $companyInfo['cd_secret_key'];
			$_SESSION['cd_verify_rand_price'] = $companyInfo['cd_verify_rand_price'];
			$_SESSION['cs_enable_passmgmt'] = $companyInfo['cs_enable_passmgmt'];
			$_SESSION['cs_ID'] = $companyInfo['cs_ID'];
			$_SESSION['cc_customer_fee'] = $companyInfo['cc_customer_fee'];
			$_SESSION['cs_support_email'] = $companyInfo['cs_support_email'];
			$_SESSION['cs_creditcards'] = $companyInfo['cs_creditcards'];
			$_SESSION['cs_echeck'] = $companyInfo['cs_echeck'] && $companyInfo['bank_check'] != -1;
			$_SESSION['cs_web900'] = $companyInfo['cs_web900'] && $companyInfo['cd_web900bank'] != -1;
			$_SESSION['cd_orderpage_useraccount'] = $companyInfo['cd_orderpage_useraccount'];

			if(!$mt_return_url) $mt_return_url = $companyInfo['cs_return_page'];
			$_SESSION['mt_return_url'] = $mt_return_url;

			if(!$company_bank_id) $company_bank_id = -1;
			
			$login_trans_type= $companyInfo['transaction_type'];
			$activeuser= $companyInfo['activeuser'];
			
			if ($companyInfo['cs_verified']=='declined' && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>This website has been <strong>declined</strong> for live integration. Please Log into your merchant account and resubmit the website for reapproval.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the website was declined. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}						
			if ($companyInfo['cs_verified']!='approved' && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>This website has not yet been approved for live integration. You will recieve an email when it has been approved.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the website was net yet approved. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}			
			if (0)// $companyInfo['completed_merchant_application']==0 && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Please complete your merchant application before integrating in live mode. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the application was not completed. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}			
			if(($activeuser!=1 || $suspenduser=="YES") && !$testmode)
			{
				$strMessage = "SUP";
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Order page not available. This site may not be live or it may be suspended. Please contact your administrator.</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the company is not live or suspended. Values: IP:$ipaddress  activeuser=$activeuser,suspenduser=$suspenduser,mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			} 
			
			if ($_SESSION['integration_mode'] == "Test") 
				$activeuser=1;
			else
				toLog('login','customer', "Customer Enters Order Page from '$from_url',IP:$ipaddress Values=param_1=$param_1, param_2=$param_2, param_3=$param_3, td_product_id=$td_product_id, mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);

			//$secure_server = "http://secure.etelegate.com/";
			if($redir_secure) $secure_server = "secure/";
			$post_header=$secure_server."PaymentProcessing.php";
			if($activeuser===0 or $suspenduser=="YES" ){
				$strMessage = "SUP";
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Order page not available. This site may not be live or it may be suspended. Please contact your administrator.</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the company is not live or suspended. Values: IP:$ipaddress  activeuser=$activeuser,suspenduser=$suspenduser,mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			} 
		} else {
			$invalidlogin="<font face='verdana' color='red'>This function is not enabled for your website.</font>";
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$invalidlogin</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the functionis not enabled for this website. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
			print $msgtodisplay;
			exit();
		
		}
	} 
	else 
	{
		$invalidlogin="<font face='verdana' color='red'>Invalid login: Please check your reference number and website.</font>";
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$invalidlogin</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because invalid reference number. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
		print $msgtodisplay;
		exit();
	}
	


if($companyInfo['bank_Creditcard']==19)
{
	$custom_text = "Forcetronix Inc.<BR>
	U12 Gamma Commercial Complex, #47<BR>
	Rizal Highway cor. Manila Avenue,<BR>
	Subic Bay Freeport, Olongapo City<BR>
	Philippines<BR>
	Is an authorized payment service provider for ";

}

$cust_cntry = urlencode(func_get_country($companyInfo['country'],'co_full'));
$custom_text .="<strong>$companyInfo[cs_name]</strong><BR>
$companyInfo[cs_support_email]<BR>
$companyInfo[cs_support_phone]";
if($companyInfo['cd_custom_orderpage']) $custom_text = $companyInfo['cd_custom_orderpage'];
if($_SESSION['cs_support_email']) $custom_text .="<BR>Customer Service Email: <a href='mailto:$_SESSION[cs_support_email]'>$_SESSION[cs_support_email]</a><BR>";
$smarty->assign("custom_text", $custom_text);


$from_url_parts = parse_url($from_url);
$cs_url_parts = parse_url($_SESSION['cs_URL']);
$gw_url_parts = parse_url($_SESSION['gw_domain']);
$gw_int_parts = parse_url($_SESSION['gw_integration_site']);
$_SESSION['from_url']=$from_url;
$hostcur = str_replace("www.","",strtolower($from_url_parts['host']));
$host1 = str_replace("www.","",strtolower($cs_url_parts['host']));
$host2 = str_replace("www.","",strtolower($gw_url_parts['host']));
$host3 = str_replace("www.","",strtolower($gw_int_parts['host']));

if($hostcur != $host1 && $hostcur != $host2 && $hostcur != $host3)
{
	$strMessage = "REF";
	$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Invalid HTTP Referer<br>The Payment Gateway for '$host1' was accessed from '$hostcur'. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
	print $msgtodisplay;
	exit();

}
	
if (!$mt_subAccount) $mt_subAccount = -1;

if (($mt_subAccount) == -1)
{
	if($companyInfo['cd_enable_rand_pricing'] == 0 || $companyInfo['cd_allow_rand_pricing'] == 0 ) 
	{
		$strMessage = "INV";
		$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Random Pricing Not Enabled/Allowed for this site. Please contact an administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
		die($msgtodisplay);

	}
	
	$verify_checksum=md5($_SESSION['cd_secret_key'].$reference_id.$mt_amount.$td_product_id);
	
	if($verify_checksum != $mt_checksum && $_SESSION['cd_verify_rand_price']==1)
	{
		$strMessage = "INV";
		$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Price Checksum Validation failed. Please refer to your integration guide for proper checksum use.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
		toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the checksum was wrong. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_checksum=$mt_checksum, mt_subAccount=$mt_subAccount, td_product_id=$td_product_id, mt_amount=$mt_amount, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
		die($msgtodisplay);
	}
	
	$_SESSION['amount'] = $mt_amount;
	$_SESSION['rd_subaccount']=-1;	
	$_SESSION['payment_schedule'] = "One Time Charge of ".formatMoney($mt_amount);

}
else
{
	// IMPORTANT 5-6-05, DONT ALLOW THIS ANYMORE AFTER A WEEK = CONCAT($companyid,'-',`rd_subName`

	$sql = "SELECT rd_subaccount FROM `cs_rebillingdetails` WHERE (`rd_subName` = '$mt_subAccount') AND `company_user_id` = " .$companyid ;

	if(!($result = mysql_query($sql,$cnn_cs)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_update."<br>");
		print("Failed to access company Product Database");
		exit();
	}
	else
	{
		if(mysql_num_rows($result) <= 0) 
		{
			$strMessage = "INV";
			$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>SubAccount '$mt_subAccount' not found for this company. Please contact an administrator. If you are a merchant seeing this message, please make sure the <strong>Entire</strong> SubAccount is sent (ex. '425-00101', not '00101')</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
			die($msgtodisplay);
	
		}		
		
		$subAccID = mysql_fetch_assoc($result);

		$subAcc=getRebillInfo($subAccID['rd_subaccount'],time(),true);
		
		//print_r($subAcc);
		if($companyInfo['cd_enable_price_points'] == 0 || $subAcc['rd_subaccount'] == -1)
		{
			$strMessage = "INV";
			$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Invalid PricePoint Account or Price Points Not Enabled for this site. Please contact an administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
			die($msgtodisplay);
		}		
		$_SESSION['rd_subaccount']=$subAcc['rd_subaccount'];	
		$_SESSION['td_enable_rebill']=$subAcc['td_enable_rebill'];
		$_SESSION['td_one_time_subscription']=$subAcc['td_one_time_subscription'];
		$_SESSION['td_recur_next_date']=$subAcc['td_recur_next_date'];
		$_SESSION['nextDateInfo']=$subAcc['nextDateInfo'];
		
		$_SESSION['amount'] = $subAcc['chargeAmount'];
		$_SESSION['payment_schedule'] = $subAcc['payment_schedule'];
		if(!$_SESSION['mt_prod_desc']) $_SESSION['mt_prod_desc']=$subAcc['rd_description'];
		
	}
}

if ($_SESSION['amount']>$companyInfo['cd_max_transaction'] && $companyInfo['cd_max_transaction'] > 0)
{
	$strMessage = "INV";
	$msgdisplay = "This charge amount is too high. Charges must be below '".$companyInfo['cd_max_transaction']."'. Please contact your administrator.";
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "INV";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();
}
if ($_SESSION['amount']<1.00)
{
	$strMessage = "INV";
	$msgdisplay = "This charge amount is too low or not set. Charges must be above '1.00'. Please contact your administrator.";
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "INV";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();
}

if (checkIsOverMonthlyMaximum($companyid,$companyInfo['cd_max_volume']))
{
	$strMessage = "INV";
	$msgdisplay = "The maximum Monthly Volume for this company has been reached. Please contact your administrator.";
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();
}

foreach($_REQUEST as $k => $c)
	$str_posted_variables.= "<input type='hidden' name='$k' value='$c' >";
	
if($_REQUEST['mt_hide_logo']) $_SESSION['mt_hide_logo']=$_REQUEST['mt_hide_logo'];
$smarty->assign("mt_hide_logo",$_REQUEST['mt_hide_logo']||$_SESSION['mt_hide_logo']);
$smarty->assign("cs_URL", $_SESSION['cs_URL']);
$smarty->assign("str_posted_variables",$str_posted_variables);
if(!$mt_language) $mt_language = 'eng';
$smarty->assign("mt_language",$mt_language);
$smarty->assign("tmpl_language",$mt_language);

$smarty->assign("ca_email",$_REQUEST['ca_email']);
$smarty->assign("ca_password",$_REQUEST['ca_password']);
$smarty->assign("OP_autologin",$_REQUEST['OP_autologin']);
$smarty->assign("login_ca_email",$_REQUEST['login_ca_email']);
$smarty->assign("login_ca_password",$_REQUEST['login_ca_password']);
$smarty->assign("login_OP_autologin",$_REQUEST['login_OP_autologin']);

$smarty->assign("cs_creditcards",$_SESSION['cs_creditcards']);
$smarty->assign("cs_echeck",$_SESSION['cs_echeck']);
$smarty->assign("cs_web900",$_SESSION['cs_web900']);
$smarty->assign("cd_orderpage_useracount",$_SESSION['cd_orderpage_useraccount']);
$smarty->assign("AccountSignupPage",$etel_domain_path."/secure/CreateUserAccount.php");

$

$_SESSION['tmpl_language'] = $mt_language;
etel_smarty_display('int_header.tpl');
etel_smarty_display('int_entry.tpl');
etel_smarty_display('int_footer.tpl');
?>
