<?php
$allowBank=true;
include("includes/sessioncheck.php");

$headerInclude="affiliates";
include("includes/header.php");

if(!$_POST['addcompany'])
{
	$disc_markup = $curUserInfo['en_info']['Merchant']['Default_Aff_Disc_Markup'];
	if(!$disc_markup) $disc_markup = '10.00'; 
	$trans_markup = $curUserInfo['en_info']['Merchant']['Default_Aff_Trans_Markup'];
	if(!$trans_markup) $trans_markup = '0.00'; 
	$fields = array(
		'<strong>Discount Markup</strong>'=>array('name'=>'discount_markup','value'=>$disc_markup,'detail'=>"(As % of Purchase)"),
		'<strong>Transaction Markup</strong><br>'=>array('name'=>'transaction_markup','value'=>$trans_markup,'detail'=>"(As USD Flat-Fee)")
	);
	$app = array('title'=>'New Affiliate Signup','fields'=>$fields,'submit'=>'add_affiliate.php');
	$smarty->assign("etel_hear_about_us", $etel_hear_about_us);
	$smarty->assign("etel_timezone", $etel_timezone);
	$smarty->assign("app", $app);
	etel_smarty_display('main_applynow_form.tpl');
	include("includes/footer.php");
	die();
}
unset($_POST['addcompany']);
foreach($_POST as $k => $c)
$postback.= "<input type='hidden' name='$k' value='$c' >";


$newCompanyInfo['en_company'] = quote_smart($_POST['newcompany']);
$newCompanyInfo['en_ref'] = substr(strtoupper(md5(time()+rand(1,1000000))),0,8);
$newCompanyInfo['en_email'] = quote_smart($_POST['email']);
$newCompanyInfo['en_username'] = trim(strtolower(quote_smart($_POST['username'])));
$newCompanyInfo['en_password'] = strtolower(substr(md5(time()),0,6));
$newCompanyInfo['how_about_us'] = quote_smart($_POST['how_about_us']);
if($_POST['reseller']) $newCompanyInfo['how_about_us'] = quote_smart($_POST['reseller']);
$newCompanyInfo['volumenumber'] = quote_smart($_POST['merchant_volume']);
$newCompanyInfo['url1'] = quote_smart($_POST['url1']);
$newCompanyInfo['phonenumber'] = quote_smart($_POST['phonenumber']);
$newCompanyInfo['contact_phone'] = quote_smart($_POST['phonenumber']);
$newCompanyInfo['cd_timezone'] = quote_smart($_POST['cd_timezone']);
$newCompanyInfo['en_gateway_ID'] = $_SESSION['gw_id'];
if(!$newCompanyInfo['cd_timezone']) $newCompanyInfo['cd_timezone'] = '-7.0';
$newCompanyInfo['cd_contact_im'] = quote_smart($_POST['cd_contact_im_type'].$_POST['cd_contact_im']);

$newCompanyInfo['transaction_type'] = (isset($_POST['rad_order_type'])?quote_smart($_POST['rad_order_type']):"");
if(!$newCompanyInfo['transaction_type']) $transaction_type = 'Adult';

$newCompanyInfo['discount_markup'] = floatval($_POST['discount_markup']);
$newCompanyInfo['transaction_markup'] = floatval($_POST['transaction_markup']);
$newCompanyInfo['etel_affiliate_ref'] = $curUserInfo['en_ref'];	


$result = add_new_merchant($newCompanyInfo,true,true);

if($result['status'] == true)
{
	message($result['msg'],"","Success","selectMerchant.php",false);
}
else
{
	message($result['msg'].$postback,true,"Error","",false);
}

include("includes/footer.php");

?>