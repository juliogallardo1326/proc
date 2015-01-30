<?
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// editCompanyProfile1.php:	This admin page functions for editing the company details.
$allowBank=true;
include("includes/sessioncheck.php");
include("../includes/completion.php");
require_once("../includes/updateAccess.php");
require_once('../includes/subFunctions/rates_fees.php');

$markComp = "Mark this Company";

$loginas = (isset($HTTP_GET_VARS["loginas"])?trim($HTTP_GET_VARS["loginas"]):"");
if($loginas)
{
	$etel_debug_mode=0;
	require_once("../includes/dbconnection.php");
	$_SESSION["loginredirect"]="None";
	if($resellerInfo['isMasterMerchant'])	
		$_SESSION["gw_masterMerchant_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Reseller|".$_SESSION['gw_id']."|editCompanyProfile.php?company_id=".$_GET['company_id']);
	$_SESSION["gw_admin_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Admin|".$_SESSION['gw_id']."|editCompanyProfileAccess.php?company_id=".$_GET['company_id']);
	
	general_login($_GET['username'],$_GET['password'],"merchant",$_GET['gw_id'],false);
	die();
}

$headerInclude = "companies";
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
$trans_activity="";
$is_Gateway	 = (isset($HTTP_GET_VARS["GatewayCompany"])?quote_smart($HTTP_GET_VARS["GatewayCompany"]):"");

$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
if ($company_id == "") 
	$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");

if ($company_id == "") 
	$company_id = isset($HTTP_POST_VARS['company_id'])?$HTTP_POST_VARS['company_id']:"";

if($company_id == "") 
	$company_id = (isset($_REQUEST['userIdList'])?quote_smart($_REQUEST['userIdList']):"");

if ($company_id == "") 
	$company_id = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");

$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";

	$qry_select_companies = "
	select 
		state,
		ostate,
		transaction_type,
		completed_merchant_application,
		username,
		password,
		companyname,
		userid 
	from 
		cs_companydetails 
	where 
		userid='$company_id' 
		$bank_sql_limit
	";
	if($qry_select_companies != "")
		$show_sql =sql_query_read($qry_select_companies) or dieLog(mysql_error()." ~ $qry_select_companies");

	$companyInfo = mysql_fetch_assoc($show_sql);
	
$userId = intval($_REQUEST['userIdList']?$_REQUEST['userIdList']:$_REQUEST['userId']);


//$access = getMerchantAccess();
$access = getAccessInfo("

userId,

'Reseller Request Rate Markup' as access_header,

'Contract' as access_header,

	cd_merchant_show_contract,
	cd_custom_contract,
	merchant_contract_agree,
	cd_contract_ip,
	cd_contract_date,

'Credit Card Rates & Fees' as access_header,

	cc_customer_fee,
	cc_underchargeback,
	cc_overchargeback,
	cc_discountrate,
	cc_reserve,
	bank_Creditcard,
	cd_cc_bank_extra,
	cc_visa_billingdescriptor,
	cc_master_billingdescriptor,
	cc_total_discount_rate,
	cc_reseller_discount_rate,
	cc_merchant_discount_rate,
	cc_total_trans_fees,
	cc_reseller_trans_fees,
	cc_merchant_trans_fees,

'Check Rates & Fees' as access_header,

ch_chargeback,
ch_discountrate,
ch_reserve,
bank_check,
ch_billingdescriptor,
ch_total_discount_rate,
ch_reseller_discount_rate,
ch_merchant_discount_rate,
ch_total_trans_fees,
ch_reseller_trans_fees,
ch_merchant_trans_fees,

'ETEL900 Rates & Fees' as access_header,

	web_chargeback,
	web_discountrate,
	web_reserve,
	cd_web900bank,
	we_billingdescriptor,
	web_total_trans_fees,
	web_reseller_trans_fees,
	web_merchant_trans_fees,

'Reseller Name' as access_header,

reseller_id,

'Manage Settings' as access_header,

	gateway_id,
	cd_completion,
	block_virtualterminal,
	cd_allow_gatewayVT,
	suspenduser,
	cd_pay_status,
	cd_ignore,
	activeuser,
	contact_email,
	customer_service_phone,
	send_mail,
	send_ecommercemail,
	cancel_ecommerce_letter,
	cd_enable_price_points,
	cd_allow_rand_pricing,
	cd_custom_recur,
	cd_max_volume,
	cd_max_transaction,

'Pay Period Information' as access_header,

cd_pay_bimonthly,cd_payperiod,cd_paydelay,cd_rollover,cd_wirefee,cd_appfee,cd_appfee_upfront,cs_monthly_charge,cd_next_pay_day,

'Order Page Settings' as access_header,

cd_orderpage_settings,cd_orderpage_useraccount,cd_approve_timelimit,cd_fraudscore_limit,cd_custom_orderpage

",

"cs_companydetails",
"userId = $company_id");

if($access==-1) dieLog("Invalid Company","Invalid Company");
$access['Data']['bank_Creditcard']['Input']="selectcc";
$access['Data']['bank_check']['Input']="selectcheckbank";
$access['Data']['cd_web900bank']['Input']="selectetelbank";
$access['Data']['reseller_id']['Input']="selectreseller";
$access['Data']['gateway_id']['Input']="selectgateway";
$access['Data']['cd_completion']['Input']="selectcompletion";
$access['Data']['cd_pay_status']['Input']="selectenum";
$access['Data']['cd_pay_status']['Table']="cs_companydetails";
$access['Data']['cd_pay_status']['Name']="cd_pay_status";

$access['Data']['cd_pay_bimonthly']['Input']="selectenum";
$access['Data']['cd_pay_bimonthly']['Table']="cs_companydetails";
$access['Data']['cd_pay_bimonthly']['Name']="cd_pay_bimonthly";

$access['Data']['cd_payperiod']['Input']="selectpayperiod";

$access['Data']['cd_paydelay']['Input']="selectpaydelay";

$access['Data']['cd_orderpage_settings']['Input']="selectenum";
$access['Data']['cd_orderpage_settings']['Table']="cs_companydetails";
$access['Data']['cd_orderpage_settings']['Name']="cd_orderpage_settings";


$access['Data']['userId']['Input']="hidden";
$access['Data']['userId']['disable']=1;

/*
$access['Data']['reseller_id']['DisplayName']="Reseller";
$access['Data']['reseller_id']['Input']="selectcustom";
$access['Data']['reseller_id']['Input_Custom']="select reseller_id, reseller_companyname from cs_resellerdetails where rd_subgateway_id='".$resellerInfo['reseller_id']."' order by reseller_companyname";
*/

if($_POST['submit_access'] == 'Submit')
{
	$result = processAccessForm(&$access);
	if($result) $msg= "Company Updated Successfully (".$result['cnt']." Field(s))";
	else $msg= "No Updates Detected";
}

$access['HeaderMessage']=$msg;

?>
	<table>
	<tr>
	<td><a href="editCompanyProfileAccess.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileWire.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileRates.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileDocs.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	</tr>
	</table>

	<center>
	<a href="<?="?username=".$companyInfo['username']."&password=".$companyInfo['password']."&gw_id=".$_SESSION['gw_id']."&company_id=".$companyInfo['userid']?>&loginas=1">Login as <?= $companyInfo['companyname'] ?></a>
	</center>

<?
beginTable();
writeAccessForm(&$access);
endTable("Update Rates and Fees - ".$access['Data']['companyname']['Value'],"");

include("includes/footer.php");
?>
