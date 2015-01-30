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
chdir("..");
session_start();
session_unset();
$_SESSION['mt_posted_variables'] = serialize($_REQUEST);
//$_SESSION["gw_database"]='etel_dbsmain';
//require_once("includes/dbconnection.php");
require('includes/etel_config.php');
$cnn_cs = mysql_connect($database["server"],$database["user"],$database["password"]) 
   or dieLog("Could not find server"); 
mysql_select_db('etel_dbsmain',$cnn_cs) or dieLog("Unable to connect database"); 
require_once('includes/function.php');
$post_header="";
$reference_id = (isset($_REQUEST["mt_reference_id"])?trim($_REQUEST["mt_reference_id"]):"");
$trans_type = (isset($_REQUEST["mt_transaction_type"])?trim($_REQUEST["mt_transaction_type"]):"");
$mt_return_url = (isset($_REQUEST["mt_return_url"])?trim($_REQUEST["mt_return_url"]):"");
$mt_subAccount = (isset($_REQUEST["mt_subAccount"])?trim($_REQUEST["mt_subAccount"]):"");
$mt_prod_desc = (isset($_REQUEST["mt_prod_desc"])?trim($_REQUEST["mt_prod_desc"]):"");
$mt_prod_price = (isset($_REQUEST["mt_prod_price"])?trim($_REQUEST["mt_prod_price"]):"");
$mt_etel900_subAccount = (isset($_REQUEST["mt_etel900_subAccount"])?trim($_REQUEST["mt_etel900_subAccount"]):"");

$mt_checksum = (isset($_REQUEST["mt_checksum"])?trim($_REQUEST["mt_checksum"]):"");

$mt_amount = (isset($_REQUEST["mt_amount"])?trim($_REQUEST["mt_amount"]):"");
$td_product_id = (isset($_REQUEST["mt_product_id"])?trim($_REQUEST["mt_product_id"]):"");

	$sql = "SELECT * FROM etel_dbsmain.`cs_company_sites` left join etel_dbsmain.`etel_gateways` on `cs_gatewayId` = `gw_id` where cs_reference_ID='$reference_id'";
	if(!($result = mysql_query($sql))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(!$gw_info = mysql_fetch_array($result))
	{
			$invalidlogin="<font face='verdana' color='red'>This function is not enabled for your website. Please check your Reference Number</font>";
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$invalidlogin</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			//toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the reference ID was not found. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
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
	
$_SESSION['param_1'] = $param_1;
$_SESSION['param_2'] = $param_2;
$_SESSION['param_3'] = $param_3;
$_SESSION['td_product_id'] = $td_product_id;
$_SESSION['mt_reference_id'] = $reference_id;
$_SESSION['mt_transaction_type'] = $trans_type;
$_SESSION['mt_subAccount'] = $mt_subAccount;
$_SESSION['mt_prod_desc'] = $mt_prod_desc;
$_SESSION['mt_prod_price'] = $mt_prod_price;
$_SESSION['mt_etel900_subAccount'] = $mt_etel900_subAccount;
$_SESSION['integration_mode'] = "Test";

$ipaddress = GetHostByName($_SERVER["REMOTE_ADDR"]); 
$_SESSION['ipaddress']=$ipaddress;

$from_url = (isset($HTTP_SERVER_VARS["HTTP_REFERER"])?trim($HTTP_SERVER_VARS["HTTP_REFERER"]):"");
if (!$testmode) 
{
	$_SESSION['integration_mode'] = "Live";
}


	$select_sql = "select * from cs_companydetails left join `etel_dbsmain`.`cs_company_sites` as s on cs_company_id=userId where `cs_gatewayId` = ".$_SESSION["gw_id"]." AND cs_reference_ID='$reference_id' and suspenduser='NO'";
	if(!($show_sql_val = mysql_query($select_sql))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(mysql_num_rows($show_sql_val)>0){
		if($show_val = mysql_fetch_array($show_sql_val)) {
			$companyid = $show_val['userId'];
			$_SESSION['companyid'] = $companyid;
			$_SESSION['cs_URL'] = $show_val['cs_URL'];
			$_SESSION['cd_secret_key'] = $show_val['cd_secret_key'];
			$_SESSION['cd_verify_rand_price'] = $show_val['cd_verify_rand_price'];
			$_SESSION['cs_enable_passmgmt'] = $show_val['cs_enable_passmgmt'];
			$_SESSION['cs_ID'] = $show_val['cs_ID'];
			$_SESSION['cc_customer_fee'] = $show_val['cc_customer_fee'];
			if(!$mt_return_url) $mt_return_url = $show_val['cs_return_page'];
			$_SESSION['mt_return_url'] = $mt_return_url;
			
			
			if(strtolower($trans_type)=="check") 
			{
				$_SESSION['ProcessingMode']='Check';
				$company_bank_id = $show_val['bank_check'];
			}
			else //if(strtolower($trans_type)=="credit")
			{
				$_SESSION['ProcessingMode']='Credit';
				$company_bank_id = $show_val['bank_Creditcard'];
			}
			//else if(strtolower($trans_type)=="web900")
				//$_SESSION['ProcessingMode']='WEB900';
			if(!$company_bank_id) $company_bank_id = -1;
			
			$login_trans_type= $show_val['transaction_type'];
			$activeuser= $show_val['activeuser'];
			
			if ($show_val['cs_verified']=='declined' && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>This website has been <strong>declined</strong> for live integration. Please Log into your merchant account and resubmit the website for reapproval.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the website was . Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}						
			if ($show_val['cs_verified']!='approved' && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>This website has not yet been approved for live integration. You will recieve an email when it has been approved.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the website was . Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}			
			if ( $show_val['completed_merchant_application']==0 && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Please complete your merchant application before integrating in live mode. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the application was not completed. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}			
			if(($activeuser==0 || $suspenduser=="YES") && !$testmode)
			{
				$strMessage = "SUP";
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Order page not available. This site may not be live or it may be suspended. Please contact your administrator.</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the company is not live or suspended. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			} 
				
			if ( $company_bank_id == -1 && !$testmode)
			{
				$strMessage = "INV";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>This company has an invalid ".$_SESSION['ProcessingMode']." bank selected. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the bank was not set for this company (CC). Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
				print $msgtodisplay;
				exit();
			}
			
			if (!(($_SESSION['ProcessingMode']=='Credit' && $show_val['cs_creditcards']== 1) || ($_SESSION['ProcessingMode']=='Check' && $show_val['cs_echeck']== 1) || ($_SESSION['ProcessingMode']=='Web900' && $show_val['cs_web900']== 1)))

			{
				$strMessage = "DIS";
				$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>The '$trans_type' transaction method is not enabled for this website. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the transaction method is not enabled for this website. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
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
			if($activeuser==0 or $suspenduser=="YES" ){
				$strMessage = "SUP";
				$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Order page not available. This site may not be live or it may be suspended. Please contact your administrator.</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
				toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the company is not live or suspended. Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$trans_type, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
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
	

$from_url_parts = parse_url($from_url);
$cs_url_parts = parse_url($_SESSION['cs_URL']);
$gw_url_parts = parse_url($_SESSION['gw_URL']);
$gw_int_parts = parse_url($_SESSION['gw_integration_site']);
$_SESSION['from_url']=$from_url;
$host1 = strtolower(str_replace("www.","",$cs_url_parts['host']));
$host2 = strtolower(str_replace("www.","",$from_url_parts['host']));
$host3 = strtolower(str_replace("www.","",$gw_url_parts['host']));
$host4 = strtolower(str_replace("www.","",$gw_int_parts['host']));

if($host1 != $host2 && $host1 != $host3 && $host1 != $host4)
{
	$strMessage = "REF";
	$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Invalid HTTP Referer<br>The Payment Gateway for '$host2' was accessed from '$host1'. Please contact your administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
	print $msgtodisplay;
	exit();

}
	
if (!$mt_subAccount) $mt_subAccount = -1;

if ($trans_type=="WEB900")
{

}
else if (($mt_subAccount) == -1)
{
	if($show_val['cd_enable_rand_pricing'] == 0 || $show_val['cd_allow_rand_pricing'] == 0 ) 
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
		if($show_val['cd_enable_price_points'] == 0 || $subAcc['rd_subaccount'] == -1)
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

if ($_SESSION['amount']>$show_val['cd_max_transaction'] && $show_val['cd_max_transaction'] > 0)
{
	$strMessage = "INV";
	$msgdisplay = "This charge amount is too high. Charges must be below '".$show_val['cd_max_transaction']."'. Please contact your administrator.";
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "INV";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();
}
if (checkIsOverMonthlyMaximum($companyid,$show_val['cd_max_volume']))
{
	$strMessage = "INV";
	$msgdisplay = "The maximum Monthly Volume for this company has been reached. Please contact your administrator.";
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();
}
?>

</head>
<body topmargin="0" leftmargin="0" bgcolor="#EDF2E6"  marginheight="0" marginwidth="0">
<?php 
if($msgtodisplay=="") {
?>
<form name="Frmname" action="<?=$post_header?>" method="post">

</form>
<script language="JavaScript">
document.location.href='<?=$post_header?>';
</script>

<?
} elseif($return_url!="") { ?>

<form name="Frmname" action="<?=$return_url?>" method="post">		
<input type="hidden" name="mt_reference_number" value="-1">
<input type="hidden" name="mt_product_id" value="<?=$td_product_id?>">		
<input type="hidden" name="mt_product_id" value="<?=$td_product_id?>">
</form>
<script language="JavaScript">
document.Frmname.submit();
</script>
<?php 
}else { ?>
<!--header-->
<table border="0" cellpadding="0" cellspacing="0" width="780" height="125" class="bdbtm" align="center">
<tr>
<td valign="middle" align="center" bgcolor="#ffffff" width="35%">&nbsp;</td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="https://www.etelegate.com/images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="https://www.etelegate.com/images/top2.jpg" width="217" height="63"><img border="0" src="https://www.etelegate.com/images/top3.jpg" width="138" height="63"><br>

<img alt="" border="0" src="https://www.etelegate.com/images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="https://www.etelegate.com/images/top5.jpg" width="217" height="63"><img border="0" src="https://www.etelegate.com/images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
<tr>
<td height="5" background="images/menubtmbg.gif"><img alt="" src="https://www.etelegate.com/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">&nbsp;

</td>
</tr>
</table>
<!--submenu ends-->
<!--content part starts-->
<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
<tr>
<td width="180" align="left" valign="top" height="50">
<img border="0" src="https://www.etelegate.com/images/leftside_tr.jpg" width="178" height="300">
</td>
<td width="600" align="center" valign="middle" bgcolor="#FFFFFF" height="50">
<table align="center" width="350" style="border:2px solid #d1d1d1;">
  <tr bgcolor="#CCCCCC"> 
	<td align="left" height="15">&nbsp;&nbsp; Message</td>
  </tr>
	<tr><td height="60" valign="middle" align="center"><?=$msgtodisplay;?></td></tr>
</table>
</td>
</tr>
</table>
<!--content parts ends-->
<!--footer-->
<table border="0" cellpadding="0" cellspacing="0" width="780" height="40" align="center">
<tr>
<td bgcolor="#000000" height="20" valign="middle" align="right">&nbsp;

</td>
</tr>
<tr>
<td height="1"><img alt="" src="https://www.etelegate.com/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#7D9103" class="blackbd" height="19">&nbsp;</td>
</tr>
</table>
<!--footer-->

</body>
</html>
<? } ?>