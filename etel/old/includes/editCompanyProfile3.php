<?php
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
// editCompanyProfile3.php:	This admin page functions for editing the company details.

$allowBank=true;
include("includes/sessioncheck.php");
include("../includes/completion.php");

$headerInclude = "companies";
include("includes/header.php");

/*
$symmetrexDefaults['cd_pay_bimonthly']='bimonthly';
$symmetrexDefaults['cc_visa_billingdescriptor']="gkard load etelegate 1-877-8676904";
$symmetrexDefaults['cc_master_billingdescriptor']="cnp*gkard load etelegate 1-877-8676904";
$symmetrexDefaults['cc_discountrate']=5;
$symmetrexDefaults['ch_discountrate']=5;
$symmetrexDefaults['web_discountrate']=5;
$symmetrexDefaults['cc_reserve']=10;
$symmetrexDefaults['ch_reserve']=10;
$symmetrexDefaults['web_reserve']=10;
$symmetrexDefaults['cd_paydelay']=28;
$symmetrexDefaults['cs_monthly_charge']=59;
die(serialize($symmetrexDefaults));
*/

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
$iCheckBankId ="";
$bank_Creditcard="";$val="";
$numrows=0;$modified=0;

$gw_options=NULL;
if($adminInfo['li_level'] == 'full')
{
	foreach($etel_gw_list as $gw)
	{
		if($gw['gw_database']==$etel_gw_list[$_SESSION['gw_id']]['gw_database']) $gw_options[$gw['gw_id']]=$gw['gw_title'];
	}
	if(is_array($gw_options)) if (sizeof($gw_options)<2) $gw_options = NULL;
}

if($sessionAdmin!="" && $adminInfo['li_level'] == 'full')
{
	if ($str_update == "yes") {
		$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");

		$reseller_other = (isset($HTTP_POST_VARS['reseller_other'])?quote_smart($HTTP_POST_VARS['reseller_other']):"");

		$cc_underchargeback = (isset($HTTP_POST_VARS['cc_underchargeback'])?quote_smart($HTTP_POST_VARS['cc_underchargeback']):"0");
		$cc_overchargeback = (isset($HTTP_POST_VARS['cc_overchargeback'])?quote_smart($HTTP_POST_VARS['cc_overchargeback']):"0");
		$cc_chargeback = (isset($HTTP_POST_VARS['cc_chargeback'])?quote_smart($HTTP_POST_VARS['cc_chargeback']):"0");
		$cc_discountrate  = (isset($HTTP_POST_VARS['cc_discountrate'])?quote_smart($HTTP_POST_VARS['cc_discountrate']):"0");
		$cc_reserve  = (isset($HTTP_POST_VARS['cc_reserve'])?quote_smart($HTTP_POST_VARS['cc_reserve']):"0");
		$ch_chargeback = (isset($HTTP_POST_VARS['ch_chargeback'])?quote_smart($HTTP_POST_VARS['ch_chargeback']):"0");
		$ch_discountrate  = (isset($HTTP_POST_VARS['ch_discountrate'])?quote_smart($HTTP_POST_VARS['ch_discountrate']):"0");
		$ch_reserve  = (isset($HTTP_POST_VARS['ch_reserve'])?quote_smart($HTTP_POST_VARS['ch_reserve']):"0");
		$web_chargeback = (isset($HTTP_POST_VARS['web_chargeback'])?quote_smart($HTTP_POST_VARS['web_chargeback']):"0");
		$web_discountrate  = (isset($HTTP_POST_VARS['web_discountrate'])?quote_smart($HTTP_POST_VARS['web_discountrate']):"0");
		$web_reserve  = (isset($HTTP_POST_VARS['web_reserve'])?quote_smart($HTTP_POST_VARS['web_reserve']):"0");

		$cc_customer_fee  = (isset($HTTP_POST_VARS['cc_customer_fee'])?quote_smart($HTTP_POST_VARS['cc_customer_fee']):"0");
		$cc_merchant_discount_rate  = (isset($HTTP_POST_VARS['cc_merchant_discount_rate'])?quote_smart($HTTP_POST_VARS['cc_merchant_discount_rate']):"0");
		$cc_reseller_discount_rate  = (isset($HTTP_POST_VARS['cc_reseller_discount_rate'])?quote_smart($HTTP_POST_VARS['cc_reseller_discount_rate']):"0");
		$cc_total_discount_rate  = (isset($HTTP_POST_VARS['cc_total_discount_rate'])?quote_smart($HTTP_POST_VARS['cc_total_discount_rate']):"0");
		$cc_merchant_trans_fees  = (isset($HTTP_POST_VARS['cc_merchant_trans_fees'])?quote_smart($HTTP_POST_VARS['cc_merchant_trans_fees']):"0");
		$cc_reseller_trans_fees  = (isset($HTTP_POST_VARS['cc_reseller_trans_fees'])?quote_smart($HTTP_POST_VARS['cc_reseller_trans_fees']):"0");
		$cc_total_trans_fees  = (isset($HTTP_POST_VARS['cc_total_trans_fees'])?quote_smart($HTTP_POST_VARS['cc_total_trans_fees']):"0");
		$ch_merchant_discount_rate  = (isset($HTTP_POST_VARS['ch_merchant_discount_rate'])?quote_smart($HTTP_POST_VARS['ch_merchant_discount_rate']):"0");
		$ch_reseller_discount_rate  = (isset($HTTP_POST_VARS['ch_reseller_discount_rate'])?quote_smart($HTTP_POST_VARS['ch_reseller_discount_rate']):"0");
		$ch_total_discount_rate  = (isset($HTTP_POST_VARS['ch_total_discount_rate'])?quote_smart($HTTP_POST_VARS['ch_total_discount_rate']):"0");
		$ch_merchant_trans_fees  = (isset($HTTP_POST_VARS['ch_merchant_trans_fees'])?quote_smart($HTTP_POST_VARS['ch_merchant_trans_fees']):"0");
		$ch_reseller_trans_fees  = (isset($HTTP_POST_VARS['ch_reseller_trans_fees'])?quote_smart($HTTP_POST_VARS['ch_reseller_trans_fees']):"0");
		$ch_total_trans_fees  = (isset($HTTP_POST_VARS['ch_total_trans_fees'])?quote_smart($HTTP_POST_VARS['ch_total_trans_fees']):"0");
		$web_merchant_trans_fees  = (isset($HTTP_POST_VARS['web_merchant_trans_fees'])?quote_smart($HTTP_POST_VARS['web_merchant_trans_fees']):"0");
		$web_reseller_trans_fees  = (isset($HTTP_POST_VARS['web_reseller_trans_fees'])?quote_smart($HTTP_POST_VARS['web_reseller_trans_fees']):"0");
		$web_total_trans_fees  = (isset($HTTP_POST_VARS['web_total_trans_fees'])?quote_smart($HTTP_POST_VARS['web_total_trans_fees']):"0");

		$strCredit =   (isset($HTTP_POST_VARS['txtCredit'])?quote_smart($HTTP_POST_VARS['txtCredit']):"0");
		$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"0");
		$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoicefee'])?quote_smart($HTTP_POST_VARS['txtVoicefee']):"0");
		$strBankShopId  = (isset($HTTP_POST_VARS['txtShopeId'])?quote_smart($HTTP_POST_VARS['txtShopeId']):"");
		$strBankUsername  = (isset($HTTP_POST_VARS['txtBankUsername'])?quote_smart($HTTP_POST_VARS['txtBankUsername']):"");
		$strBankPassword  = (isset($HTTP_POST_VARS['txtBankPassword'])?quote_smart($HTTP_POST_VARS['txtBankPassword']):"");

		$merchant_discount = $strDiscountRate ;  // (isset($HTTP_POST_VARS['merchant_discount'])?quote_smart($HTTP_POST_VARS['merchant_discount']):"0");
		$reseller_discount = (isset($HTTP_POST_VARS['reseller_discount'])?quote_smart($HTTP_POST_VARS['reseller_discount']):"0");
		$total_discount = (isset($HTTP_POST_VARS['total_discount'])?quote_smart($HTTP_POST_VARS['total_discount']):"0");
		$merchant_transfee =  $strTransactionFee;  //(isset($HTTP_POST_VARS['merchant_transfee'])?quote_smart($HTTP_POST_VARS['merchant_transfee']):"0");
		$reseller_transfee = (isset($HTTP_POST_VARS['reseller_transfee'])?quote_smart($HTTP_POST_VARS['reseller_transfee']):"0");
		$total_transfee = (isset($HTTP_POST_VARS['total_transfee'])?quote_smart($HTTP_POST_VARS['total_transfee']):"0");
		$suspend = (isset($HTTP_POST_VARS['suspend'])?quote_smart($HTTP_POST_VARS['suspend']):"NO");
		$cc_visa_billingdescriptor = (isset($HTTP_POST_VARS['cc_visa_billingdescriptor'])?quote_smart($HTTP_POST_VARS['cc_visa_billingdescriptor']):"");
		$cc_master_billingdescriptor = (isset($HTTP_POST_VARS['cc_master_billingdescriptor'])?quote_smart($HTTP_POST_VARS['cc_master_billingdescriptor']):"");
		$ch_billingdescriptor = (isset($HTTP_POST_VARS['ch_billingdescriptor'])?quote_smart($HTTP_POST_VARS['ch_billingdescriptor']):"");
		$we_billingdescriptor = (isset($HTTP_POST_VARS['we_billingdescriptor'])?quote_smart($HTTP_POST_VARS['we_billingdescriptor']):"");

		$processingcurrency = (isset($HTTP_POST_VARS['currency'])?quote_smart($HTTP_POST_VARS['currency']):"");
		$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"0");

		$cd_merchant_show_contract = (isset($HTTP_POST_VARS['cd_merchant_show_contract'])?quote_smart($HTTP_POST_VARS['cd_merchant_show_contract']):"0");
		$cd_pay_status = (isset($HTTP_POST_VARS['cd_pay_status'])?quote_smart($HTTP_POST_VARS['cd_pay_status']):"0");

		$strUnsubscribe  = (isset($HTTP_POST_VARS['chk_unsubscribe'])?quote_smart($HTTP_POST_VARS['chk_unsubscribe']):"1");
		$send_ecommercemail = (isset($HTTP_POST_VARS['chk_sendecommerce'])?quote_smart($HTTP_POST_VARS['chk_sendecommerce']):"0");
		$cancelecommerce_checked = (isset($HTTP_POST_VARS['chk_cancelecommerce'])?quote_smart($HTTP_POST_VARS['chk_cancelecommerce']):"0");
		$cd_allow_rand_pricing  = (isset($HTTP_POST_VARS['cd_allow_rand_pricing'])?quote_smart($HTTP_POST_VARS['cd_allow_rand_pricing']):"0");
		$cd_custom_recur  = (isset($HTTP_POST_VARS['cd_custom_recur'])?quote_smart($HTTP_POST_VARS['cd_custom_recur']):"0");
		$cd_enable_price_points = (isset($HTTP_POST_VARS['cd_enable_price_points'])?quote_smart($HTTP_POST_VARS['cd_enable_price_points']):"0");
		//$cd_password_mgmt = (isset($HTTP_POST_VARS['cd_password_mgmt'])?quote_smart($HTTP_POST_VARS['cd_password_mgmt']):"0");
		$cs_monthly_charge = (isset($HTTP_POST_VARS['cs_monthly_charge'])?quote_smart($HTTP_POST_VARS['cs_monthly_charge']):"0");
		$cd_max_transaction = (isset($HTTP_POST_VARS['cd_max_transaction'])?quote_smart($HTTP_POST_VARS['cd_max_transaction']):"0");
		$cd_max_volume = (isset($HTTP_POST_VARS['cd_max_volume'])?quote_smart($HTTP_POST_VARS['cd_max_volume']):"0");


		$bank_check		=	(isset($HTTP_POST_VARS["bank_check"])?quote_smart($HTTP_POST_VARS["bank_check"]):"");
		$bank_Creditcard		=	(isset($HTTP_POST_VARS["bank_Creditcard"])?quote_smart($HTTP_POST_VARS["bank_Creditcard"]):"");
		$cd_web900bank		=	(isset($HTTP_POST_VARS["cd_web900bank"])?quote_smart($HTTP_POST_VARS["cd_web900bank"]):"");
		$atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?quote_smart($HTTP_POST_VARS['atm_verify']):"N");
		$check_integrate = (isset($HTTP_POST_VARS['check_integrate'])?quote_smart($HTTP_POST_VARS['check_integrate']):"");
		$virtualterminal_off = (isset($HTTP_POST_VARS['virtualterminalOff'])?quote_smart($HTTP_POST_VARS['virtualterminalOff']):"");
		$recurtransactionoff=(isset($HTTP_POST_VARS['recurtransactionOff'])?quote_smart($HTTP_POST_VARS['recurtransactionOff']):"");
		$rebilltransactionoff=(isset($HTTP_POST_VARS['rebilltransactionOff'])?quote_smart($HTTP_POST_VARS['rebilltransactionOff']):"");
		$master_currency=(isset($HTTP_POST_VARS['mastercurrency'])?quote_smart($HTTP_POST_VARS['mastercurrency']):"");
		$visa_currency=(isset($HTTP_POST_VARS['visacurrency'])?quote_smart($HTTP_POST_VARS['visacurrency']):"");
		$scanorder_password=(isset($HTTP_POST_VARS['scanorder_password'])?quote_smart($HTTP_POST_VARS['scanorder_password']):"");
		$scanorder_merchantid=(isset($HTTP_POST_VARS['scanorder_id'])?quote_smart($HTTP_POST_VARS['scanorder_id']):"");
		$contact_email=(isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
		$cd_payperiod=(isset($HTTP_POST_VARS['cd_payperiod'])?quote_smart($HTTP_POST_VARS['cd_payperiod']):"");
		$cd_paystartday=(isset($HTTP_POST_VARS['cd_paystartday'])?quote_smart($HTTP_POST_VARS['cd_paystartday']):"");
		$cd_paydelay=(isset($HTTP_POST_VARS['cd_paydelay'])?quote_smart($HTTP_POST_VARS['cd_paydelay']):"");
		$cd_paydaystartday=(isset($HTTP_POST_VARS['cd_paydaystartday'])?quote_smart($HTTP_POST_VARS['cd_paydaystartday']):"");
		$cd_rollover=(isset($HTTP_POST_VARS['cd_rollover'])?quote_smart($HTTP_POST_VARS['cd_rollover']):"");
		$cd_wirefee=(isset($HTTP_POST_VARS['cd_wirefee'])?quote_smart($HTTP_POST_VARS['cd_wirefee']):"");
		$cd_appfee=(isset($HTTP_POST_VARS['cd_appfee'])?quote_smart($HTTP_POST_VARS['cd_appfee']):"");
		$cd_appfee_upfront=(isset($HTTP_POST_VARS['cd_appfee_upfront'])?quote_smart($HTTP_POST_VARS['cd_appfee_upfront']):"");
		$customer_service_phone=(isset($HTTP_POST_VARS['customer_service_phone'])?quote_smart($HTTP_POST_VARS['customer_service_phone']):"");
		$send_notification_email=(isset($HTTP_POST_VARS['send_notification_email'])?quote_smart($HTTP_POST_VARS['send_notification_email']):"");
		$send_activity_email=(isset($HTTP_POST_VARS['send_activity_email'])?quote_smart($HTTP_POST_VARS['send_activity_email']):"");
		$cd_cc_bank_extra=(isset($HTTP_POST_VARS['cd_cc_bank_extra'])?quote_smart($HTTP_POST_VARS['cd_cc_bank_extra']):"");
		$RequestRatesMode=(isset($HTTP_POST_VARS['RequestRatesMode'])?quote_smart($HTTP_POST_VARS['RequestRatesMode']):"");
		$cd_pay_bimonthly=(isset($HTTP_POST_VARS['cd_pay_bimonthly'])?quote_smart($HTTP_POST_VARS['cd_pay_bimonthly']):"");
		$cd_ignore=(isset($HTTP_POST_VARS['cd_ignore'])?quote_smart($HTTP_POST_VARS['cd_ignore']):"");
		$cd_custom_contract=(isset($HTTP_POST_VARS['cd_custom_contract'])?quote_smart($HTTP_POST_VARS['cd_custom_contract']):"");
		$cd_custom_orderpage=(isset($HTTP_POST_VARS['cd_custom_orderpage'])?quote_smart($HTTP_POST_VARS['cd_custom_orderpage']):"");
		$gateway_id=(isset($HTTP_POST_VARS['gateway_id'])?quote_smart($HTTP_POST_VARS['gateway_id']):"");
		
		if(!$gateway_id) $gateway_id = $_SESSION['gw_id'];
		
		if($trans_activity) $bank_check=18;
		
		if($cd_pay_bimonthly=='bimonthly') $cd_payperiod = 14;
		//if($cd_pay_bimonthly=='trimonthly') $cd_payperiod = 10;

		$send_reseller_rates_email=(isset($HTTP_POST_VARS['send_reseller_rates_email'])?quote_smart($HTTP_POST_VARS['send_reseller_rates_email']):"");
		$send_merchant_rates_email=(isset($HTTP_POST_VARS['send_merchant_rates_email'])?quote_smart($HTTP_POST_VARS['send_merchant_rates_email']):"");


		func_company_ext_entry($userid,$master_currency,$visa_currency,$scanorder_merchantid,$scanorder_password,$customerservice_email,$cnn_cs);

		if($strVoiceauthFee =="")$strVoiceauthFee =0;
		if($strChargeBack =="")$strChargeBack =0;
		if($strCredit =="")$strCredit =0;
		if($strDiscountRate =="")$strDiscountRate =0;
		if($strTransactionFee =="")$strTransactionFee =0;
		if($strReserve =="")$strReserve =0;
		if($merchant_discount =="")$merchant_discount =0;
		if($reseller_discount =="")$reseller_discount =0;
		if($total_discount =="")$total_discount =0;
		if($reseller_transfee =="")$reseller_transfee =0;
		if($total_transfee =="")$total_transfee =0;
		if($send_ecommercemail =="")$send_ecommercemail =0;
		if($cancelecommerce_checked =="")$cancelecommerce_checked =0;
		if($iCheckBankId =="")$iCheckBankId =0;
		if($bank_Creditcard =="")$bank_Creditcard =0;
		if($check_integrate=="")$check_integrate=0;
		if($virtualterminal_off==0 || $virtualterminal_off=="")$virtualterminal_off=0;
		if($recurtransactionoff==0 || $recurtransactionoff=="")$recurtransactionoff=0;
		if($rebilltransactionoff==0 || $rebilltransactionoff=="")$rebilltransactionoff=0;
		$date=date("Y-m-d H:i:s");

//////////




		//$qry_sel="SELECT chargeback , credit, total_discount_rate,transactionfee ,reserve,merchant_discount_rate,reseller_discount_rate,total_trans_fees,date,reseller_trans_fees,voiceauthfee FROM cs_rateandfees WHERE userId=$userid";

		///////
		$qry_check_userexist = "select * from cs_companydetails as cd left join cs_resellerdetails as rd on cd.reseller_id = rd.reseller_id where cd.userId='$userid'";
		$result=mysql_query($qry_check_userexist) or dieLog(mysql_error());
		$companyInfo = mysql_fetch_assoc($result);

$completion="";
$bank_update_sql = "";
if($strUnsubscribe!=$companyInfo['send_mail'])
{
	if($strUnsubscribe)
	{
		removeListEmail($companyInfo['email']);
		removeListEmail($companyInfo['contact_email']);
	}
	else
	{
		addListEmail($companyInfo['email'],"Admin Unsubscribed Email",$companyInfo['userId'],'merchant','unsubscribe');
		addListEmail($companyInfo['contact_email'],"Admin Unsubscribed Email",$companyInfo['userId'],'merchant','unsubscribe');
	}
}

if($cd_custom_contract && !$companyInfo['cd_custom_contract'])
{
	$contract = genMerchantContract(&$companyInfo);
	$sql = "insert into cs_email_templates set et_name='merchant_contract', et_custom_id='".$companyInfo['userId']."', et_title='Merchant Contract', et_access='admin', et_to_title='".quote_smart($companyInfo['companyname'])."', et_subject='Custom Merchant Contract for ".quote_smart($companyInfo['companyname'])."', et_htmlformat='".quote_smart($contract['et_htmlformat'])."', et_catagory='Merchant'";
	$result=mysql_query($sql) or etelPrint(mysql_error());
	$cd_custom_contract = mysql_insert_id();
}else if(!$cd_custom_contract && $companyInfo['cd_custom_contract'])
{
	$sql = "delete from cs_email_templates where et_name='merchant_contract' and et_custom_id='".$companyInfo['userId']."'";
	$result=mysql_query($sql) or dieLog(mysql_error());
	$cd_custom_contract = 'null';
} else $cd_custom_contract = intval($companyInfo['cd_custom_contract']);

if($cd_merchant_show_contract==1 && $companyInfo['cd_completion']<=3)
{
	$completion = ' cd_completion=4, ';
}
if($trans_activity==1 && !$companyInfo['activeuser'])
{
	toLog('turnedlive','merchant', '', $companyInfo['userId']);
	$completion = ' cd_completion=10, ';
}
if($trans_activity==0 && $companyInfo['cd_completion']==10)
{
	toLog('requestlive','merchant', '', $companyInfo['userId']);
	$completion = ' cd_completion=9, ';
}
if($bank_Creditcard != $companyInfo['bank_Creditcard'])
{
	$sql = "select * from cs_bank where bank_id='$bank_Creditcard'";
	$result=mysql_query($sql) or dieLog(mysql_error());
	$cc_BankInfo = mysql_fetch_assoc($result);
	$defaultData = unserialize($cc_BankInfo['bk_defaults']);
	$bank_update_sql="";
	if(is_array($defaultData))
	{
		foreach($defaultData as $key=>$data)
		{
			if(!$bank_update_sql) $bank_update_sql = "update cs_companydetails set ";
			else $bank_update_sql.=", ";
			$bank_update_sql .= "`$key`='$data'";
		}
		$bank_update_sql .= " where userId = '".$companyInfo['userId']."' limit 1";
	}
}
if($bank_check != $companyInfo['bank_check'])
{
	$sql = "select * from cs_bank where bank_id='$bank_check'";
	$result=mysql_query($sql) or dieLog(mysql_error());
	$cc_BankInfo = mysql_fetch_assoc($result);
	$defaultData = unserialize($cc_BankInfo['bk_defaults']);
	$bank_update_sql="";
	       
	if(!$defaultData['ch_chargeback']) $defaultData['ch_chargeback'] = $cc_underchargeback;
	if(!$defaultData['ch_discountrate']) $defaultData['ch_discountrate'] = $cc_discountrate;
	if(!$defaultData['ch_total_discount_rate']) $defaultData['ch_total_discount_rate'] = $cc_total_discount_rate;
	if(!$defaultData['ch_reserve']) $defaultData['ch_reserve'] = $cc_reserve;
	if(!$defaultData['ch_merchant_trans_fees']) $defaultData['ch_merchant_trans_fees'] = $cc_merchant_trans_fees;
	if(!$defaultData['ch_reseller_discount_rate']) $defaultData['ch_reseller_discount_rate'] = $cc_reseller_discount_rate;
	if(!$defaultData['ch_merchant_discount_rate']) $defaultData['ch_merchant_discount_rate'] = $cc_merchant_discount_rate;
	if(!$defaultData['ch_total_trans_fees']) $defaultData['ch_total_trans_fees'] = $cc_total_trans_fees;
	if(!$defaultData['ch_reseller_trans_fees']) $defaultData['ch_reseller_trans_fees'] = $cc_reseller_trans_fees;

	if(is_array($defaultData))
	{
		foreach($defaultData as $key=>$data)
		{
			if(!$bank_update_sql) $bank_update_sql = "update cs_companydetails set ";
			else $bank_update_sql.=", ";
			$bank_update_sql .= "`$key`='$data'";
		}
		$bank_update_sql .= " where userId = '".$companyInfo['userId']."' limit 1";
	}
}

// Email Merchant
unset($data);
$data['email'] = $companyInfo['email'];
$data['companyname'] = $companyInfo['companyname'];
$data['full_name'] = $companyInfo['companyname'];
$data['username'] = $companyInfo['username'];
$data['password'] = $companyInfo['password'];
$data['Reference_ID'] = $companyInfo['ReferenceNumber'];
$data["gateway_select"] = $companyInfo['gateway_id'];
$updateRateRequest = "";

if($RequestRatesMode=='Commit')
{
	$updateRateRequest = "cd_reseller_rates_request = '0', ";
	send_email_template('contract_notification_email',$data);
}
if($send_notification_email==1 && $companyInfo['send_mail']==1)
{
	send_email_template('contract_notification_email',$data);
}
if($send_activity_email==1 && $companyInfo['send_mail']==1)
{
	send_email_template('active_notification_email',$data);
}
if($send_merchant_rates_email==1 && $companyInfo['send_mail']==1)
{
	$data['resellername'] = $companyInfo['reseller_companyname'];
	send_email_template('merchant_rates_notification_email',$data);
	$updateRateRequest = "cd_reseller_rates_request = '1', ";
}
if($send_reseller_rates_email==1 && $companyInfo['reseller_sendmail']==1)
{
	$data['email'] = $companyInfo['reseller_email'];
	$data['resellername'] = $companyInfo['reseller_companyname'];
	$data['companyname'] = $companyInfo['companyname'];
	$data['full_name'] = $companyInfo['reseller_contactname'];
	$data['username'] = $companyInfo['reseller_username'];
	$data['password'] = $companyInfo['reseller_password'];
	$data['Reference_ID'] = $companyInfo['ReferenceNumber'];
	send_email_template('reseller_rates_notification_email',$data);
	$updateRateRequest = "cd_reseller_rates_request = '1', ";
}

// End Email



		if ($cd_custom_orderpage) $cd_custom_orderpage = "'".$cd_custom_orderpage."'";
		else $cd_custom_orderpage = "NULL";
		$cd_has_been_active = "";
		if ($trans_activity) $cd_has_been_active = "cd_has_been_active=1, ";
		$qry_update_user  =  "update cs_companydetails left join etel_dbsmain.cs_company_sites on cs_company_id = userId set $completion cc_underchargeback='$cc_underchargeback', cc_overchargeback='$cc_overchargeback', cc_chargeback='$cc_chargeback', cc_discountrate='$cc_discountrate', cc_reserve='$cc_reserve',	ch_chargeback='$ch_chargeback', ch_discountrate='$ch_discountrate', ch_reserve='$ch_reserve', web_chargeback='$web_chargeback', web_discountrate='$web_discountrate', web_reserve='$web_reserve', ";
		$qry_update_user .=  $updateRateRequest.$cd_has_been_active."cc_merchant_discount_rate='$cc_merchant_discount_rate',cc_reseller_discount_rate='$cc_reseller_discount_rate',cc_total_discount_rate='$cc_total_discount_rate',cc_merchant_trans_fees='$cc_merchant_trans_fees',cc_reseller_trans_fees='$cc_reseller_trans_fees',cc_total_trans_fees='$cc_total_trans_fees',ch_merchant_discount_rate='$ch_merchant_discount_rate',ch_reseller_discount_rate='$ch_reseller_discount_rate',ch_total_discount_rate='$ch_total_discount_rate',ch_merchant_trans_fees='$ch_merchant_trans_fees',ch_reseller_trans_fees='$ch_reseller_trans_fees',ch_total_trans_fees='$ch_total_trans_fees', web_merchant_trans_fees='$web_merchant_trans_fees',web_reseller_trans_fees='$web_reseller_trans_fees',web_total_trans_fees='$web_total_trans_fees', ";
		$qry_update_user .=  "`cs_gatewayId`='$gateway_id', cd_pay_bimonthly='$cd_pay_bimonthly',cc_customer_fee='$cc_customer_fee',cd_appfee_upfront='$cd_appfee_upfront',cd_cc_bank_extra='$cd_cc_bank_extra',cd_merchant_show_contract='$cd_merchant_show_contract',credit='$strCredit',transactionfee='$strTransactionFee',voiceauthfee='$strVoiceauthFee',reseller_id='$reseller_other',";
		$qry_update_user .=  "`gateway_id`='$gateway_id', bank_check = '$bank_check', cd_web900bank='$cd_web900bank',suspenduser='$suspend',cs_monthly_charge = '$cs_monthly_charge', we_billingdescriptor='$we_billingdescriptor', cc_visa_billingdescriptor='$cc_visa_billingdescriptor', cc_master_billingdescriptor='$cc_master_billingdescriptor',ch_billingdescriptor='$ch_billingdescriptor',processing_currency='$processingcurrency',activeuser='$trans_activity',send_mail='$strUnsubscribe',send_ecommercemail = '$send_ecommercemail',bank_shopId = '$strBankShopId',bank_Username='$strBankUsername',bank_Password='$strBankPassword',bank_Creditcard='$bank_Creditcard', cancel_ecommerce_letter = '$cancelecommerce_checked', cd_enable_price_points ='$cd_enable_price_points', cd_allow_rand_pricing='$cd_allow_rand_pricing', cd_custom_recur = '$cd_custom_recur', atm_verify = '$atm_verify', integrate_check='$check_integrate',block_virtualterminal='$virtualterminal_off',block_recurtransaction='$recurtransactionoff',block_rebilltransaction='$rebilltransactionoff',";
		$qry_update_user .=  "cd_custom_orderpage=$cd_custom_orderpage, cd_custom_contract='$cd_custom_contract', cd_pay_status='$cd_pay_status',cd_ignore='$cd_ignore', cd_max_transaction='$cd_max_transaction',cd_max_volume='$cd_max_volume',contact_email='$contact_email',customer_service_phone='$customer_service_phone',cd_payperiod='$cd_payperiod',cd_paystartday='$cd_paystartday',cd_paydelay='$cd_paydelay',cd_paydaystartday='$cd_paydaystartday',cd_rollover='$cd_rollover',cd_wirefee='$cd_wirefee',cd_appfee='$cd_appfee'";
		$qry_update_user .=  " where userId='$userid'";

		mysql_query($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

		if($bank_update_sql) mysql_query($bank_update_sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$bank_update_sql");
					/*
		$qry_ins_rate="insert into cs_rateandfees (chargeback , credit, discountrate,transactionfee ,reserve,merchant_discount_rate,reseller_discount_rate,total_trans_fees,date, userId,reseller_trans_fees,voiceauthfee,total_discount_rate,merchant_trans_fees ) values ($strChargeBack,$strCredit,$strDiscountRate,$strTransactionFee,$strReserve,$merchant_discount,$reseller_discount,$total_transfee,'$date',$userid,$reseller_transfee,$strVoiceauthFee,$total_discount,$merchant_transfee)";
		print($qry_ins_rate);

		if(!mysql_query($qry_ins_rate))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query");
			//exit();
		}
		*/

	}

}
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";

	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid='$company_id'";
	$qry_select_company_ext="select * from cs_companydetails_ext where userId='$company_id'";

	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
	if($qry_select_company_ext != "")
	{
		if(!($show_sql_ext =mysql_query($qry_select_company_ext)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}

	if($cs_companydetails_ext=mysql_fetch_array($show_sql_ext))
	{
		$mastercurrency=$cs_companydetails_ext[1];
		$visacurrency=$cs_companydetails_ext[2];
		$customerservice_email=$cs_companydetails_ext[5];
	}
	else
	{
		$mastercurrency="";
		$visacurrency="";
		$customerservice_email="";
	}

	if($companyInfo = mysql_fetch_assoc($show_sql))
	{
		if($companyInfo['state']=="")
		{
			$state=str_replace("\n",",\t",$companyInfo['ostate']);
		}
		else
		{
			$state=str_replace("\n",",\t",$companyInfo['state']);
		}
		if($companyInfo['transaction_type'] == "tele") {
			$script_display ="yes";
			$sendecommerce_diplay = "none";
		}else {
			$script_display ="none";
			$sendecommerce_diplay = "yes";
		}
		if($companyInfo['send_ecommercemail'] == 1) {
			$sendecommerce_checked = "checked";
		}else {
			$sendecommerce_checked = "";
		}
		if($companyInfo['cancel_ecommerce_letter'] == 1) {
			$cancelecommerce_checked = "checked";
		}else {
			$cancelecommerce_checked = "";
		}
		$cd_enable_price_points = $companyInfo['cd_enable_price_points'];
		$cd_allow_rand_pricing = $companyInfo['cd_allow_rand_pricing'];
		$cd_custom_recur = $companyInfo['cd_custom_recur'];
		//$cd_password_mgmt = $companyInfo['cd_password_mgmt'];
		//$str_selected_value = $companyInfo[83];
		$str_currency ="USD";

	$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","userid",$company_id);

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
	$custom_text .="<strong>$companyInfo[companyname]</strong><BR>
	$companyInfo[address] <BR> $companyInfo[city] $companyInfo[state] $companyInfo[zipcode] $cust_cntry<BR>
	$companyInfo[customer_service_phone]<BR>Email: <a href='mailto:$_SESSION[cs_support_email]'>$_SESSION[cs_support_email]</a><BR>";
	if(!$companyInfo['cd_custom_orderpage']) $companyInfo['cd_custom_orderpage'] = $custom_text;

	$str_bank_table = "cs_bank where 1 ";

?>
<script language="javascript" src="../scripts/general.js"></script>
<script type="text/javascript" src="../fckedit/fckeditor.js"></script>
<script language="javascript">

function updatePayDelay()
{
	document.Frmcompany.cd_paydelay.value=parseInt(document.Frmcompany.cd_paydaystartday.value)+parseInt(document.Frmcompany.cd_paydelayweeks.value)-parseInt(document.Frmcompany.cd_paystartday.value);
}

function emailsubmit() {
	//document.Frmcompany.action="viewBottom.php";
	document.Frmcompany.method="POST";
	document.Frmcompany.submit();
}

function func_ischanged()
{ // rad_trans_activity
//varold txtShopeId;txtBankPassword
updatePayDelay();
addRatesFees();
return true;

}

function addRatesFees() {

	document.getElementById('cc_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('cc_reseller_trans_fees').value)+parseFloat(document.getElementById('cc_total_trans_fees').value)))*.01;
	document.getElementById('ch_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('ch_reseller_trans_fees').value)+parseFloat(document.getElementById('ch_total_trans_fees').value)))*.01;
	document.getElementById('web_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('web_reseller_trans_fees').value)+parseFloat(document.getElementById('web_total_trans_fees').value)))*.01;

	document.getElementById('cc_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('cc_reseller_discount_rate').value)+parseFloat(document.getElementById('cc_total_discount_rate').value)))*.01;
	document.getElementById('ch_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('ch_reseller_discount_rate').value)+parseFloat(document.getElementById('ch_total_discount_rate').value)))*.01;
}

function funcOpen3VT(iCompanyId) {
	window.open("vtusers.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenTSR(iCompanyId) {
	window.open("tsruserlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenEcom(iCompanyId) {
	window.open("ecomlist.php?id="+iCompanyId,null,"height=600,width=800,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function updateEmailNotification(obj)
{
	if(document.getElementById('send_notification_email')) document.getElementById('send_notification_email').disabled = !obj.checked;
	if(document.getElementById('send_notification_email')) document.getElementById('send_notification_email').checked = obj.checked;
}
function updateScheduleMethod(obj)
{
	disable = false;
	if(obj) disable = true;
	if(document.getElementById('cd_paystartday')) document.getElementById('cd_paystartday').disabled = disable;
	if(document.getElementById('cd_paydaystartday')) document.getElementById('cd_paydaystartday').disabled = disable;
	if(document.getElementById('cd_payperiod')) document.getElementById('cd_payperiod').disabled = disable;
	if(disable) if(document.getElementById('cd_payperiod')) document.getElementById('cd_payperiod').value = 14;
}
function commitRequest(obj)
{
	document.getElementById('cc_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;
	document.getElementById('ch_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;
	document.getElementById('web_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;

	document.getElementById('cc_reseller_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_discount_rate').value)))/100;
	document.getElementById('ch_reseller_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_discount_rate').value)))/100;
	addRatesFees();
	document.getElementById('RequestRatesMode').value='Commit';
	document.getElementById('Frmcompany').submit();
}
</script>

<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
    <td width="100%" valign="top" align="center"  >&nbsp;
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View / Edit&nbsp; Rates And Fees </span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td class="lgnbd" colspan="5"><form onSubmit ="return func_ischanged()" action="editCompanyProfile3.php"  name="Frmcompany" id="Frmcompany" method="post">
              <table style="margin-top:10" align="center">
                <tr>
                  <td align="center" colspan="2"><a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a> <a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a> <IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
                    <a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
                    <!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
                  </td>
                </tr>
<?php
	$status = $etel_completion_array[intval($companyInfo['cd_completion'])]['txt'];
	$bold = $etel_completion_array[intval($companyInfo['cd_completion'])]['style'];
?>
<?php if( $adminInfo['li_level'] == 'full') { ?>
            <tr align="center" valign="middle">
              <td height="30"align="center">
              <span style="font-size:12px; font-weight:<?=$bold?> "><?=ucfirst($companyInfo['companyname'])?></span> - <span style="font-size:10px; font-weight:<?=$bold?> ">
                <?=$status?>
              </span></td>
              </tr>
	<? } ?>
              </table>
                <input type="hidden" name="userid" value="<?=$companyInfo['userId']?>">
                </input>
                <input type="hidden" name="update" value="yes">
                </input>

              <table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
                <tr>
                  <td align="center" width="50%" valign="top"><table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">

<?php if( $adminInfo['li_level'] == 'full') { ?>

                      <?php
					   $request=@unserialize($companyInfo['cd_reseller_rates_request']);
					  if (is_array($request)) {

					  ?>
                      <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Reseller Requests Rate Markup</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Reseller CreditCard discount rate - %</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                            <input type="text" maxlength="15" id="request_cc_reseller_discount_rate" name="request_cc_reseller_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?= $request['request_cc_reseller_discount_rate'] ?>" ></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Reseller CreditCard Transaction Fee</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                            <input type="text" maxlength="15" id="request_cc_reseller_trans_fees" name="request_cc_reseller_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?= $request['request_cc_reseller_trans_fees'] ?>" ></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Commit Changes</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;                        <input name="Commit" type="button" id="Commit"  onClick="commitRequest()" value="Commit">
                        <input name="Commit" type="button" id="Commit" value="Reject" onClick="document.getElementById('RequestRatesMode').value='Commit';	document.getElementById('Frmcompany').submit();">
                        <input name="RequestRatesMode" type="hidden" id="RequestRatesMode" value="1"></td>
                      </tr>
                      <? } ?>
                      <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Contract</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'><?php if( $adminInfo['li_level'] == 'full' || 1) { ?>
                          <a href="<?="editCompanyProfile1.php?username=".$companyInfo['username']."&password=".$companyInfo['password']."&gw_id=".$_SESSION['gw_id']."&company_id=".$companyInfo['userId']?>&loginas=1">Login as
                          <?= $companyInfo['companyname'] ?>
                          </a>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Display Contract? </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="cd_merchant_show_contract" type="checkbox" value="1" <?=$companyInfo['cd_merchant_show_contract'] == 1 ? "checked" : ""?> onClick="updateEmailNotification(this)">
</td>
                      </tr>
                      <tr>
                        <td width="10" class='cl1'>&nbsp;</td>
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Send Notification Email ? </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="send_notification_email" type="checkbox" id="send_notification_email" value="1" disabled>
</td>
                      </tr>
			<?php if ($companyInfo['reseller_id'] != -1) { ?>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Notify Reseller by Email to Update Reseller Rates? </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="send_merchant_rates_email" type="checkbox" id="send_merchant_rates_email" value="1" >
</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Notify Merchant that Reseller is Marking up Rates? </font></strong></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="send_reseller_rates_email" type="checkbox" id="send_reseller_rates_email" value="1" >
</td>
                      </tr>
			<?php } ?>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Custom Contract? </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="cd_custom_contract" type="checkbox" value="1" <?=$companyInfo['cd_custom_contract'] ? "checked" : ""?>> <?php if ($companyInfo['cd_custom_contract']) echo "<a target='_blank' href='email_edit.php?et_custom_id=".$companyInfo['userId']."&et_name=merchant_contract'>Edit Contract</a>"; ?>
</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Contract Signed ? </font></strong></td>
                        <td height="30" class='cl1'   width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=($companyInfo['merchant_contract_agree']=="1"?"Yes":"No")?></font>
&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;IP Address</font></strong></td>
                        <td height="30" class='cl1'   width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=$companyInfo['cd_contract_ip']?></font>
&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Date Signed </font></strong></td>
                        <td height="30" class='cl1'   width="250"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?=($companyInfo['cd_contract_date']!=0?date("F j, Y, g:i a",$companyInfo['cd_contract_date']):"")?></font>
&nbsp;</td>
                      </tr>

	<? } ?>
					  <?php if(($adminInfo['li_level'] == 'bank' && $adminInfo['li_type'] == 'credit') || $adminInfo['li_level'] == 'full') { ?>
					  <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Credit Card Rates & Fees</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Customer Transaction Fee  -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>&nbsp;
                          <input type="text" name="cc_customer_fee" class="normaltext" style="width:100px" value="<?=$companyInfo['cc_customer_fee']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge Back Under 1%-
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>&nbsp;
                          <input name="cc_underchargeback" type="text" class="normaltext" id="cc_underchargeback" style="width:100px" value="<?=$companyInfo['cc_underchargeback']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge Back<strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> Over 1%</font></strong> -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>&nbsp;
                          <input name="cc_overchargeback" type="text" class="normaltext" id="cc_overchargeback" style="width:100px" value="<?=$companyInfo['cc_overchargeback']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Refund -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="cc_discountrate" class="normaltext" style="width:100px" value="<?=$companyInfo['cc_discountrate']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve - %</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="cc_reserve" class="normaltext" style="width:100px" value="<?=$companyInfo['cc_reserve']?>">
                        </td>
                      </tr>
					  <?php if($adminInfo['li_level'] == 'full') { ?>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class='cl1'><strong><font face="verdana" size="1">&nbsp;Credit Card Bank&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>&nbsp;
                          <select name="bank_Creditcard" style="font-family:arial;font-size:10px;width:120px">
                            <option value="-1">Select Bank</option>
                            <?php
					func_fill_combo_conditionally("select bank_id, bank_name from $str_bank_table and bk_cc_support=1 ORDER BY `bank_name` ASC ",$companyInfo['bank_Creditcard'],$cnn_cs);
				?>
                          </select>
                        </td>
                      </tr>
					  <?php } ?>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class='cl1'><strong><font face="verdana" size="1">Bank Additional ID </font></strong></td>
                        <td align="left" height="30" class='cl1'>&nbsp;
                          <input name="cd_cc_bank_extra" type="text" class="normaltext" id="cd_cc_bank_extra" style="width:100px" value="<?=$companyInfo['cd_cc_bank_extra']?>">
</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Visa Billing Descriptor</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="cc_visa_billingdescriptor" type="text" class="normaltext" id="cc_visa_billingdescriptor" style="width:150px" value="<?=$companyInfo['cc_visa_billingdescriptor']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Master Card Billing Descriptor</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="cc_master_billingdescriptor" type="text" class="normaltext" id="cc_master_billingdescriptor" style="width:150px" value="<?=$companyInfo['cc_master_billingdescriptor']?>">
                        </td>
                      </tr>
                      <!--<tr>
					<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processing Currency
					  </font></strong></td>
					<td height="30" class='cl1'>&nbsp;<select name="currency" style="font-family:verdana;font-size:10px;width:125px">
					  <option value="EUR">Euro</option>
					  <option value="GBP">UK Pound</option>
					  <option value="USD">US Dollar<selected></option>
					  <option value="CAD">Canadian Dollar</option>
					  <option value="AUD">Australian Dollar</option>
					</select>
					</td>
					<script language="javascript">
						 document.Frmcompany.currency.value='<?=$companyInfo[91]?>';
					</script>
				  </tr>-->
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processing Currency(MasterCard ) </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <select name="mastercurrency" style="font-family:verdana;font-size:10px;width:125px">
                            <option value="USD" selected>US Dollar</option>
                            <option value="EUR" >Euro</option>
                            <option value="GBP">UK Pound</option>
                            <option value="CAD">Canadian Dollar</option>
                            <option value="AUD">Australian Dollar</option>
                          </select>
                        </td>
                        <script language="javascript">
						 //document.Frmcompany.mastercurrency.value='<?=$mastercurrency?>';
					</script>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processing Currency(Visa ) </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <select name="visacurrency" style="font-family:verdana;font-size:10px;width:125px">
                            <option value="USD" selected >US Dollar</option>
                            <option value="EUR">Euro</option>
                            <option value="GBP">UK Pound</option>
                            <option value="CAD">Canadian Dollar</option>
                            <option value="AUD">Australian Dollar</option>
                          </select>
                        </td>
                        <script language="javascript">
						 //document.Frmcompany.visacurrency.value='<?=$visacurrency?>';
					</script>
                      </tr>
					  <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> discount rate - %</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input name="cc_total_discount_rate" type="text" id="cc_total_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cc_total_discount_rate'] ?>" onChange="addRatesFees();" maxlength="15"></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller discount rate - %</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input type="text" maxlength="15" name="cc_reseller_discount_rate" id="cc_reseller_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cc_reseller_discount_rate'] ?>" onChange="addRatesFees();"></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant discount rate - %</strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input type="text" name="cc_merchant_discount_rate" id="cc_merchant_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?=$companyInfo['cc_merchant_discount_rate']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> transaction fee -
                          <?= $str_currency?>
                          </strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input type="text" maxlength="15" name="cc_total_trans_fees" id="cc_total_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cc_total_trans_fees'] ?>" onChange="addRatesFees();"></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee -
                          <?= $str_currency?>
                          </strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input type="text" maxlength="15" name="cc_reseller_trans_fees" id="cc_reseller_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cc_reseller_trans_fees'] ?>" onChange="addRatesFees();">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee -
                          <?= $str_currency?>
                          </strong>&nbsp;</font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;
                          <input type="text" name="cc_merchant_trans_fees" id="cc_merchant_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?=$companyInfo['cc_merchant_trans_fees']?>"></td>
                      </tr>

					  <?php } ?>
					  <?php if(($adminInfo['li_level'] == 'bank' && $adminInfo['li_type'] == 'check') || $adminInfo['li_level'] == 'full') { ?>
					   <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Check Rates & Fees</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge Back -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>&nbsp;
                          <input name="ch_chargeback" type="text" class="normaltext" id="ch_chargeback" style="width:100px" value="<?=$companyInfo['ch_chargeback']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Refund -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="ch_discountrate" type="text" class="normaltext" id="ch_discountrate" style="width:100px" value="<?=$companyInfo['ch_discountrate']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve - %</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="ch_reserve" type="text" class="normaltext" id="ch_reserve" style="width:100px" value="<?=$companyInfo['ch_reserve']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align="left" valign="center" class='cl1'><strong><font face="verdana" size="1">&nbsp;Check Bank&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>&nbsp;
                          <select name="bank_check" value="<?=$companyInfo['bank_check']?>" style="font-family:arial;font-size:10px;width:120px">
                            <option value="-1">Select Bank</option>
                            <?php
					func_fill_combo_conditionally("select bank_id,bank_name from $str_bank_table and bk_ch_support=1 ORDER BY `bank_name` ASC ",$companyInfo['bank_check'],$cnn_cs);
				?>
                          </select>
                        </td>
                      </tr>
				       <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Billing Descriptor Name</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="ch_billingdescriptor" type="text" class="normaltext" id="ch_billingdescriptor" style="width:150px" value="<?=$companyInfo['ch_billingdescriptor']?>">
                        </td>
                      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> discount rate - %</strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input name="ch_total_discount_rate" type="text" id="ch_total_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['ch_total_discount_rate'] ?>" onChange="addRatesFees();" maxlength="15"></td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller discount rate - %</strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input type="text" maxlength="15" name="ch_reseller_discount_rate" id="ch_reseller_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['ch_reseller_discount_rate'] ?>" onChange="addRatesFees();"></td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant discount rate - %</strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input type="text" name="ch_merchant_discount_rate" id="ch_merchant_discount_rate" style="font-family:arial;font-size:10px;width:100px" value="<?=$companyInfo['ch_merchant_discount_rate']?>">
                         </td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input type="text" maxlength="15" name="ch_total_trans_fees" id="ch_total_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['ch_total_trans_fees'] ?>" onChange="addRatesFees();"></td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input type="text" maxlength="15" name="ch_reseller_trans_fees" id="ch_reseller_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['ch_reseller_trans_fees'] ?>" onChange="addRatesFees();">
                         </td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input type="text" name="ch_merchant_trans_fees" id="ch_merchant_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?=$companyInfo['ch_merchant_trans_fees']?>"></td>
				      </tr>
					  <?php } ?>
					  <?php if(($adminInfo['li_level'] == 'bank' && $adminInfo['li_type'] == 'web900') || $adminInfo['li_level'] == 'full') { ?>
                      <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>ETEL900 Rates & Fees</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge Back -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'   width="250">&nbsp;
                          <input name="web_chargeback" type="text" class="normaltext" id="web_chargeback" style="width:100px" value="<?=$companyInfo['web_chargeback']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Refund -
                          <?= $str_currency?>
                          </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="web_discountrate" type="text" class="normaltext" id="web_discountrate" style="width:100px" value="<?=$companyInfo['web_discountrate']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve - %</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="web_reserve" type="text" class="normaltext" id="cc_reserve" style="width:100px" value="<?=$companyInfo['web_reserve']?>">
                        </td>

                      </tr>
					  <tr>
                        <td height="30" colspan="2" align="left" valign="center" class='cl1'><strong><font face="verdana" size="1">&nbsp;ETEL900 Bank&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>&nbsp;
                          <select name="cd_web900bank" id="cd_web900bank" style="font-family:arial;font-size:10px;width:120px">
                            <option value="-1">Select Bank</option>
                            <?php
							func_fill_combo_conditionally("select bank_id,bank_name from $str_bank_table and bk_w9_support=1 ORDER BY `bank_name` ASC ",$companyInfo['cd_web900bank'],$cnn_cs);
							?>
                          </select>
                        </td>
                      </tr>
				       <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Billing Descriptor Name</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input name="we_billingdescriptor" type="text" class="normaltext" id="we_billingdescriptor" style="width:150px" value="<?=$companyInfo['we_billingdescriptor']?>">
                        </td>
                      </tr>

					     <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input name="web_total_trans_fees" type="text" id="web_total_trans_fees" style="font-family:arial;font-size:10px;width:100px" onChange="addRatesFees();" value="<?= $companyInfo['web_total_trans_fees'] ?>" maxlength="15"></td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input name="web_reseller_trans_fees" type="text" id="web_reseller_trans_fees" style="font-family:arial;font-size:10px;width:100px" onChange="addRatesFees();" value="<?= $companyInfo['web_reseller_trans_fees'] ?>" maxlength="15">
                         </td>
				      </tr>
					   <tr>
                         <td height="30" colspan="2" align="left" valign="center" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee -
                                 <?= $str_currency?>
                         </strong>&nbsp;</font></td>
                         <td align="left" valign="center" class="cl1">&nbsp;
                             <input name="web_merchant_trans_fees" type="text" id="web_merchant_trans_fees" style="font-family:arial;font-size:10px;width:100px" value="<?=$companyInfo['web_merchant_trans_fees']?>"></td>
				      </tr>

                      <tr>
                        <td height="30" colspan="2" align="center" valign="middle" bgcolor="#CCCCCC" class="cl1"><font face="verdana" size="1" color="#FFFFFF"><strong>Reseller Name </strong></font></td>
                        <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
                      </tr>
					   <tr>
                        <td height="30" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Reseller Company Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp; <select name="reseller_other"  style="font-family:verdana;font-size:10px;width:150px">

						<option value="-1"><?=$_SESSION['gw_title']?></option>
<?php 					$str_qry_resellers = "select reseller_id,reseller_companyname from cs_resellerdetails where 1 order by reseller_companyname";

						$str_selected_value = $companyInfo['reseller_id'];
						func_fill_combo_conditionally($str_qry_resellers,$str_selected_value,$cnn_cs);
?>
						  </select>
		                 </td>
                      </tr>
					  <?php } ?>
                      <?php //		} 	?>
                  </table></td>
                  <td align="center" width="50%" valign="top"><table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="top">
                      <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Manage Settings </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      
                        
						<?php 
						if(is_array($gw_options))
						{
							echo "<tr><td height='30' colspan='2' class='cl1'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;SubGateway</font></strong></td><td height='30' class='cl1'><select name='gateway_id'>";
							foreach($gw_options as $key=>$gw){
								echo "<option value='$key' ".($companyInfo['gateway_id']==$key?"selected":"").">$gw</option>\n";
							}
							echo "</select></td></tr>";
						}
						?>
                        
                        
                      
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Block Virtual Terminal</font></strong></td>
                        <td height="30" class='cl1'><input name="virtualterminalOff" type="checkbox" value="1" <?=$companyInfo['block_virtualterminal'] == 1 ? "checked" : ""?>>
                        </td>
                      </tr>
                      <?php
				if ($companyInfo['transaction_type'] != "tele") {
			?>
                      <tr height='30'>
                        <td width="233" height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Web sites</b></font></td>
                        <td height="30" align='left'  width="50%" class='cl1'><font face='verdana' size='1'>&nbsp;<a href="javascript:funcOpenEcom(<?=$companyInfo['userId']?>)">Show Websites</a></font> </td>
                      </tr>
                      <? } ?>
<?php if( $adminInfo['li_level'] == 'full') { ?>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Suspend User?</b></font></td>
                        <td height="30" class='cl1' align='left'><input type="checkbox" name="suspend" class="normaltext" <?=$companyInfo['suspenduser'] == "YES" ? "checked" : ""?> value="YES">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Company Payable?</b></font></td>
                        <td height="30" class='cl1' align='left'><select name="cd_pay_status"><?=func_get_enum_values('cs_companydetails','cd_pay_status',$companyInfo['cd_pay_status'])?></select>
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Ignore This Company </b></font></td>
                        <td height="30" class='cl1' align='left'><input name="cd_ignore" type="checkbox" class="normaltext" id="cd_ignore" value="1" <?=$companyInfo['cd_ignore'] ? "checked" : ""?>>
                        </td>
                      </tr>
                      <? } ?>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant Active</font></strong></td>
                        <td height="30" class='cl1'><input name="rad_trans_activity" type="checkbox" value="1" <?=$companyInfo['activeuser'] == 1 ? "checked" : ""?> onClick="document.getElementById('send_activity_email').disabled = !this.checked; document.getElementById('send_activity_email').checked = this.checked;">
                        </td>
                      </tr>

                      <tr>
                        <td width="5" class='cl1'>&nbsp;</td>
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Send Activity Notification Email ? </font></strong></td>
                        <td height="30" class='cl1'   width="250"></font>
                          <input name="send_activity_email" type="checkbox" id="send_activity_email" value="1" disabled>
</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Customer service Email</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="contact_email" class="normaltext" style="width:200px" value="<?= $companyInfo['contact_email'] ?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Customer service Telephone Number</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="customer_service_phone" class="normaltext" style="width:200px" value="<?=$companyInfo['customer_service_phone'] ?>">
                        </td>
                      </tr>
<?php if( $adminInfo['li_level'] == 'full') { ?>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Unsubscribe from mailing list?</b></font></td>
                        <td height="30" align='left' class='cl1'><input type="checkbox" name="chk_unsubscribe" class="normaltext" <?=$companyInfo['send_mail'] == 0 ? "checked" : ""?> value="0">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Send Ecommerce Letter?</b></font></font></td>
                        <td height="30" align='left' class='cl1'><input type="checkbox" name="chk_sendecommerce" class="normaltext" value="1" <?=$sendecommerce_checked?>>
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Send Cancel Ecommerce Letter?</b></font></font></td>
                        <td height="30" align='left' class='cl1'><input type="checkbox" name="chk_cancelecommerce" class="normaltext" value="1" <?=$cancelecommerce_checked?>>
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Enable Price Points?</b></font></font></td>
                        <td height="30" align='left' class='cl1'><input name="cd_enable_price_points" type="checkbox" class="normaltext" id="cd_enable_price_points" value="1" <?=($cd_enable_price_points?"checked":"")?>>
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Allow Independent Charging?</b></font></font></td>
                        <td height="30" align='left' class='cl1'><input name="cd_allow_rand_pricing" type="checkbox" class="normaltext" id="cd_allow_rand_pricing" value="1" <?=($cd_allow_rand_pricing?"checked":"")?>>
                        </td>
                      </tr>
		      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Allow Batch Recur Billing?</b></font></font></td>
                        <td height="30" align='left' class='cl1'><input name="cd_custom_recur" type="checkbox" class="normaltext" id="cd_custom_recur" value="1" <?=($cd_custom_recur?"checked":"")?>>
                        </td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Maximum Transaction Volume?<br>
                          Set = 0 for no maximum. </b></font></font></td>
                        <td height="30" align='left' class='cl1'><input name="cd_max_volume" type="text" class="normaltext" id="cd_max_volume" style="width:200px" value="<?=$companyInfo['cd_max_volume'] ?>"></td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" align='left' class='cl1'><font face='verdana' size='1'><font face='verdana' size='1'><font face='verdana' size='1'><font face='verdana' size='1'><b>&nbsp;Maximum Transaction Charge?</b></font></font></font></font></td>
                        <td height="30" align='left' class='cl1'><input name="cd_max_transaction" type="text" class="normaltext" id="cd_max_transaction" style="width:200px" value="<?=$companyInfo['cd_max_transaction'] ?>" size="12"></td>
                      </tr>                      <tr>
                        <td height="30" colspan="2" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Pay Period Information </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" colspan="2" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Pay</font></strong></td>
                        <td height="30" class='cl1'   width="250">&nbsp;</font>
                          <select name="cd_pay_bimonthly" id="cd_pay_bimonthly" style="font-family:arial;font-size:10px;width:120px" onChange="updateScheduleMethod(this.value)">
                            <option value="">None</option>
                            <?=func_get_enum_values('cs_companydetails','cd_pay_bimonthly',$companyInfo['cd_pay_bimonthly'])?>
                          </select>
</td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Pay Period </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <select name="cd_payperiod" id="cd_payperiod">
                            <option value="7" <?=($companyInfo['cd_payperiod']==7?"Selected":"") ?>>1 Week</option>
                            <option value="14" <?=($companyInfo['cd_payperiod']==14?"Selected":"") ?>>2 Weeks</option>
                            <option value="21" <?=($companyInfo['cd_payperiod']==21?"Selected":"") ?>>3 Weeks</option>
                            <option value="28" <?=($companyInfo['cd_payperiod']==28?"Selected":"") ?>>4 Weeks</option>
                          </select>                          </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Pay Period Start </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <select name="cd_paystartday" id="cd_paystartday" onChange="document.Frmcompany.cd_payendday.value=this.value-1;updatePayDelay();">
						  <?php func_weekdays($companyInfo['cd_paystartday']);?>

                          </select><font face="verdana" size="1">to</font><select name="cd_payendday" id="cd_payendday" disabled>
                            <option value="0">Saturday</option>
                            <?php func_weekdays($companyInfo['cd_paystartday']-1);?>
                                                                              </select>
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Pay Delay (Hold) </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
<select name="cd_paydelayweeks" id="cd_paydelayweeks" onChange="updatePayDelay()" onFocus="updatePayDelay()">
                            <option value="7" <?=($companyInfo['cd_paydelay']>=7?"Selected":"") ?>>1 Week</option>
                            <option value="14" <?=($companyInfo['cd_paydelay']>=14?"Selected":"") ?>>2 Weeks</option>
                            <option value="21" <?=($companyInfo['cd_paydelay']>=21?"Selected":"") ?>>3 Weeks</option>
                            <option value="28" <?=($companyInfo['cd_paydelay']>=28?"Selected":"") ?>>4 Weeks</option>
                            <option value="35" <?=($companyInfo['cd_paydelay']>=35?"Selected":"") ?>>5 Weeks</option>
                            <option value="42" <?=($companyInfo['cd_paydelay']>=42?"Selected":"") ?>>6 Weeks</option>
                            <option value="10" <?=($companyInfo['cd_paydelay']==10?"Selected":"") ?>>10 Days</option>
                          </select>
<font face="verdana" size="1">after pay period ends </font></td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Pay D<font face="verdana" size="1"><strong>ay</strong></font></strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;<font face="verdana" size="1">on a

                          </font>
                          <select name="cd_paydaystartday" id="cd_paydaystartday" onChange="updatePayDelay()" onFocus="updatePayDelay()">
						  <?php func_weekdays($companyInfo['cd_paydaystartday'])?>
                          </select>
                        <input name="cd_paydelay" type="text" id="cd_paydelay" size="4" value="<?=$companyInfo['cd_paydelay']?>" style="display:none "></td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Roll Over </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <input type="text" maxlength="15" name="cd_rollover" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cd_rollover'] ?>" onChange="addRatesFees();">
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Wire Fee </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <input type="text" maxlength="15" name="cd_wirefee" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cd_wirefee'] ?>" onChange="addRatesFees();">
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Application Fee </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <input type="text" maxlength="15" name="cd_appfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $companyInfo['cd_appfee'] ?>" onChange="addRatesFees();">
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;Up Front Application Fee </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <input name="cd_appfee_upfront" type="text" id="cd_appfee_upfront" style="font-family:arial;font-size:10px;width:100px" onChange="addRatesFees();" value="<?= $companyInfo['cd_appfee_upfront'] ?>" maxlength="15">
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp; Monthly Fee </strong></font></td>
                        <td height="31" align="left"  class='cl1' >&nbsp;
                          <input name="cs_monthly_charge" type="text" id="cs_monthly_charge" style="font-family:arial;font-size:10px;width:100px" onChange="addRatesFees();" value="<?= $companyInfo['cs_monthly_charge'] ?>" maxlength="15">
                        </td>
                      </tr>
                      <tr>
                        <td height="31" colspan="2" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp; Order Page Text </strong></font></td>
                        <td height="31" align="left"  class='cl1' >
                        <textarea name="cd_custom_orderpage" id="cd_custom_orderpage" cols="28" rows="5"><?=$companyInfo['cd_custom_orderpage']?></textarea>
						</td>
                      </tr>
                      <? } ?>
                      <tr>
                        <td colspan="3"><div id="auto_sendecommerce" style="display:<?=$sendecommerce_diplay?>"> </div></td>
                      </tr>

                      <?php /*
		  		$qrySelect 		=	"select * from cs_invoice_setup where company_id = $company_id";
				$rstSelect		=	mysql_query($qrySelect,$cnn_cs);
				$iFreequency 	= "";
				$iNumDaysBack	= "";
				$iFromWeekDay 	= "";
				$iToWeekDay 	= "";
				$iMiscFee		= "";

				if (mysql_num_rows($rstSelect) > 0 ) {
					$iFreequency 	= mysql_result($rstSelect,0,2);
					$iNumDaysBack	= mysql_result($rstSelect,0,3);
					$iFromWeekDay 	= mysql_result($rstSelect,0,4);
					$iToWeekDay 	= mysql_result($rstSelect,0,5);
					$iMiscFee		= mysql_result($rstSelect,0,6);
				}*/

		  ?>
                      <!--<tr>
			<td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="42%"><font face="verdana" size="1" color="#FFFFFF"><strong>Invoice
			  Details</strong>&nbsp;</font></td>
			<td width="58%" height="30" align="left" class='cl1'>&nbsp;</td>
		</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Frequency</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboFreequency" style="font-family:verdana;font-size:10px;width:100px">
			<?php
				funcFillFreequency($iFreequency);
			?>
			</select>

			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Number of days back</b></font></td>
			<td height="30" align='left' class='cl1'>
			<input type="text" name="txtNumberOfDays" value="<?= $iNumDaysBack ?>" maxlength="5" style="font-family:verdana;font-size:10px;width:75px">
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;From week day</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboFromWeekDay" style="font-family:verdana;font-size:10px;width:150px">
			<?php

				funcFillWeekDays($iFromWeekDay);
			?>
			</select>
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;To week day</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboToWeekDay" style="font-family:verdana;font-size:10px;width:150px">
			<?php

				funcFillWeekDays($iToWeekDay);
			?>
			<script>
				document.Frmcompany.cboToWeekDay.value = "7";
			</script>
			</select>

			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Misc Fee</b></font></td>
			<td height="30" align='left' class='cl1'>
			<input type="text" name="txtMiscFee" value="<?= $iMiscFee ?>" maxlength="10" style="font-family:verdana;font-size:10px;width:75px">
			</td>
    	</tr>-->
                  </table>
                    <br>
<?php if( $adminInfo['li_level'] == 'full') { ?>
                     <a href="editCompanyTeirRates.php?company_id=<?= $company_id?>">Edit Teir Rates </a>
                      <? } ?>
					 </td>
                </tr>
              </table>
              <center>
                <table align="center">
                  <tr>
                    <td align="center" valign="center" height="30" colspan="2" ><a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;
                      <input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg">
                      </input></td>
                  </tr>
                </table>
              </center>
            </form></td>
        </tr>
        <tr>
          <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
          <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
          <td width="3%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
        </tr>
      </table>
      <br>
    </td>
  </tr>
</table>
<script language="javascript">
updateScheduleMethod(<?=$companyInfo['cd_pay_bimonthly']?'true':'false'?>);
var sBasePath = '../fckedit/';
var oFCKeditor = new FCKeditor( 'cd_custom_orderpage','100%','200','Basic' ) ;//( instanceName, width, height, toolbarSet, value )
oFCKeditor.BasePath	= sBasePath ;
oFCKeditor.ReplaceTextarea();
</script>
<?php
}

include("includes/footer.php");
//-------------	Function for filling freequency --------------
//------------------------------------------------------------
function funcFillFreequency($iFreequency) {
	$arrFre[1] = "Daily";
	$arrVal[1] = "D";
	$arrFre[2] = "Weekly";
	$arrVal[2] = "W";
	$arrFre[3] = "Monthly";
	$arrVal[3] = "M";
	for ( $iLoop = 1 ;$iLoop < 4 ;$iLoop++ ) {
		if ( $iLoop == $iFreequency ) {
			echo("<option value=\"$arrVal[$iLoop]\" selected>$arrFre[$iLoop]</option>");
		} else {
			echo("<option value=\"$arrVal[$iLoop]\">$arrFre[$iLoop]</option>");
		}
	}
}

//------------- Function for filling week days ----------------
//-------------------------------------------------------------

function funcFillWeekDays($iWeekDay) {
	$arrWeekDays[1] = "Monday";
	$arrWeekDays[2] = "Tuesday";
	$arrWeekDays[3] = "Wednesday";
	$arrWeekDays[4] = "Thursday";
	$arrWeekDays[5] = "Friday";
	$arrWeekDays[6] = "Saturday";
	$arrWeekDays[7] = "Sunday";

	for ($iLoop = 1;$iLoop < 8;$iLoop++ ) {
		if ( $iLoop == $iWeekDay ) {
			echo("<option value=\"$iLoop\" selected>$arrWeekDays[$iLoop]</option> ");
		} else {
			echo("<option value=\"$iLoop\">$arrWeekDays[$iLoop]</option> ");
		}
	}

}
//------------- Function for entering card currencies in companydetails_ext ----------------
//-------------------------------------------------------------

function func_company_ext_entry($userid,$mastercurrency,$visacurrency,$scanorder_merchantid,$scanorder_password,$customerservice_email,$cnn_cs){
	$qry_exist="select * from cs_companydetails_ext where userid='$userid'";
	if(!$rst_exist=mysql_query($qry_exist,$cnn_cs))
	{
		echo "Cannot execute Query";
	}
	else{
		$num=mysql_num_rows($rst_exist);
		if($num==0)
		{
			$qry_companyext="insert into cs_companydetails_ext (userId,processingcurrency_master,processingcurrency_visa,scanorder_merchantid,scanorder_password, customerservice_email ) values('$userid','$mastercurrency','$visacurrency','$scanorder_merchantid','$scanorder_password','$customerservice_email')";
		}
		else
		{
			$qry_companyext="update cs_companydetails_ext set processingcurrency_master='$mastercurrency',processingcurrency_visa='$visacurrency',scanorder_merchantid='$scanorder_merchantid',scanorder_password='$scanorder_password',customerservice_email='$customerservice_email' where userid='$userid'";
		}
		if(!$rst_update=mysql_query($qry_companyext,$cnn_cs))
		{
			echo "Cannot execute query";
		}
	}

}
?>
