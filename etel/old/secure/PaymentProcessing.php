<?php 
chdir("..");
session_start();
$gateway_db_select=$_SESSION["gw_id"];
require_once("includes/dbconnection.php");
require_once($rootdir.'smarty/libs/Smarty.class.php');
$smarty = new Smarty;


$smarty->compile_check = true;
$smarty->debugging = false;
$curtemplate = $_SESSION['gw_template'];
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("GET", $_GET);
$smarty->assign("ShowPaymentInfo", 1);
$smarty->assign("ShowSubmitButton", 1);


$mt_language = (isset($_REQUEST['mt_language'])?quote_smart($_REQUEST['mt_language']):"");

$initial_request = @unserialize($_SESSION['mt_posted_variables']);
if(is_array($initial_request)) $_REQUEST = array_merge($_REQUEST,$initial_request);

foreach($_REQUEST as $k => $c)
	$str_posted_variables.= "<input type='hidden' name='".quote_smart($k)."' value='".quote_smart($c)."' >";
	
$_SESSION['card_declined_reset_output'] = "";
require_once('includes/function.php');

$reference_id = $_SESSION['mt_reference_id'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$cc_customer_fee = $_SESSION['cc_customer_fee'];
$cs_URL = $_SESSION['cs_URL'];
$companyid = $_SESSION['companyid'];
$str_ipaddress = $_SESSION['ipaddress'];
$testmode = ("Test"==$_SESSION['integration_mode']);
if($testmode) $testIntegration = " - Test Mode";
//print_r($_SESSION);



$blockrebill = (isset($_REQUEST['hid_blockrebill'])?quote_smart($_REQUEST['hid_blockrebill']):"");

$product_description = (isset($_REQUEST['mt_prod_desc'])?quote_smart($_REQUEST['mt_prod_desc']):"");
$blockrebill = (isset($_REQUEST['hid_blockrebill'])?quote_smart($_REQUEST['hid_blockrebill']):"");
//Modifications to get user data automatically
$str_firstname = (isset($_REQUEST['firstname'])?quote_smart($_REQUEST['firstname']):"");
$str_lastname = (isset($_REQUEST['lastname'])?quote_smart($_REQUEST['lastname']):"");
$str_address = (isset($_REQUEST['address'])?quote_smart($_REQUEST['address']):"");
$str_country = (isset($_REQUEST['country'])?quote_smart($_REQUEST['country']):"");
$str_city = (isset($_REQUEST['city'])?quote_smart($_REQUEST['city']):"");
$str_state = (isset($_REQUEST['state'])?quote_smart($_REQUEST['state']):"select");
$str_otherstate = (isset($_REQUEST['otherstate'])?quote_smart($_REQUEST['otherstate']):"");
$str_username= (isset($_REQUEST['td_username'])?quote_smart($_REQUEST['td_username']):"");
$str_password= (isset($_REQUEST['td_password'])?quote_smart($_REQUEST['td_password']):"");
$str_zipcode = (isset($_REQUEST['zipcode'])?quote_smart($_REQUEST['zipcode']):"");
$str_phonenumber = (isset($_REQUEST['telephone'])?quote_smart($_REQUEST['telephone']):"");
$str_emailaddress = (isset($_REQUEST['email'])?quote_smart($_REQUEST['email']):"");
$ProcessingMode = (isset($_REQUEST['ProcessingMode'])?quote_smart($_REQUEST['ProcessingMode']):"");
if(!$mt_language) $mt_language = (isset($_REQUEST['mt_language'])?quote_smart($_REQUEST['mt_language']):"");

if(!$mt_language) $mt_language = $_SESSION['tmpl_language'];
if(!$mt_language) $mt_language = 'eng';
$_SESSION['tmpl_language'] = $mt_language;
$smarty->assign("mt_language",$_SESSION['tmpl_language']);
$smarty->assign("tmpl_language",$_SESSION['tmpl_language']);

$str_currency = func_get_cardcurrency('Master',$companyid,$cnn_cs);
$mastercurrency=$str_currency;
$visacurrency=func_get_cardcurrency('Visa',$companyid,$cnn_cs); 

if(!$ProcessingMode) $ProcessingMode = $_SESSION['ProcessingMode'];

$_SESSION['card_declined_reset']=false;
if(strpos(strtolower($ProcessingMode),"check")!==false) 
{
	$_SESSION['ProcessingMode']='Check';
	$company_bank_sql = ', b.bank_check as company_bank_id';
	$company_bank_sql_join = 'bank_check';
}
else if(strpos(strtolower($ProcessingMode),"credit")!==false)

{
	$_SESSION['ProcessingMode']='Credit';
	$company_bank_sql = ', b.bank_Creditcard as company_bank_id';
	$company_bank_sql_join = 'bank_Creditcard';
}
else if(strpos(strtolower($ProcessingMode),"useraccount")!==false)
{
	$_SESSION['ProcessingMode']='UserAccount';
	$company_bank_sql = ', b.bank_Creditcard as company_bank_id';
	$company_bank_sql_join = 'bank_Creditcard';
}
else
{
	$msgdisplay="The Payment Method '$ProcessingMode' is not available at this time. Please return to the merchant order page";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='black'>$msgdisplay</font></td></tr><tr><td align='center'><a href='javascript:window.history.back()'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();	
}


$chargeAmount = $_SESSION['amount']+$cc_customer_fee;
//modifications end here
//$trans_type ="tele";
if($companyid ==""){ 
	$msgdisplay="Your Session has expired. Please return to the merchant order page";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='black'>$msgdisplay</font></td></tr><tr><td align='center'><a href='javascript:window.history.back()'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();	
}
$qry_select ="select * from cs_companydetails as cd left join cs_bank as bk on cd.$company_bank_sql_join=bk.bank_id where userId= $companyid";
$select =mysql_query($qry_select) or dieLog(mysql_error());;
$companyInfo = mysql_fetch_assoc($select);
$gateway_id=$companyInfo['gateway_id'];
//$bill_des=$companyInfo['cc_billingdescriptor'];
$companyname=$companyInfo['companyname'];
$transaction_type=$companyInfo['transaction_type'];
//echo $gateway_id;

if($_SESSION['ProcessingMode']=='Credit') $cc_visa_billingdescriptor = $companyInfo['bk_descriptor_visa'];
if($_SESSION['ProcessingMode']=='Credit') $cc_master_billingdescriptor = $companyInfo['bk_descriptor_master'];
if($_SESSION['ProcessingMode']=='Check') $bill_des = $companyInfo['ch_billingdescriptor'];
if($_SESSION['ProcessingMode']=='Web900') $bill_des = $companyInfo['we_billingdescriptor'];



if (!$mt_subAccount) $mt_subAccount = -1;
if ($mt_subAccount != -1)
{
	$sql = "SELECT c.*,b.transaction_type,b.cd_password_mgmt $company_bank_sql FROM `cs_rebillingdetails` as c, `cs_companydetails` as b WHERE b.userId = `company_user_id` AND `rd_subName` = '$mt_subAccount' AND `company_user_id` = " .$companyid ;
	$result = mysql_query($sql,$cnn_cs) or  dieLog(mysql_errno().": ".mysql_error()." ~ $sql");
	$subAcc = mysql_fetch_assoc($result);
	$nextDate = $_SESSION['nextDateInfo'];
	$_SESSION['recur_charge'] = $subAcc['recur_charge'];
}

$company_bank_id = $subAcc['company_bank_id'];
$_SESSION['bank_id'] = $company_bank_id;

$InitialAmount = "$".formatMoney($_SESSION['amount']);
if ($cc_customer_fee) $InitialAmount.= " (+ $".formatMoney($cc_customer_fee).")";
$TrialDays = $subAcc['rd_trial_days']." Day(s)";
if(!$subAcc['rd_trial_days']) $TrialDays = "One Time Payment";
$RecurDays = $subAcc['recur_day']." Day(s)";

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

if ( $company_bank_id == -1 && !$testmode)
{
	$strMessage = "INV";
	$msgdisplay="This company has an invalid ".$_SESSION['ProcessingMode']." bank selected. Please contact your administrator.";				
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='black'>$msgdisplay</font></td></tr><tr><td align='center'><a href='javascript:window.history.back()'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";	toLog('error','customer', "Customer Fails to enter Order Page from '$from_url' because the bank was not set for this company (CC). Values: IP:$ipaddress  mt_reference_id=$reference_id, mt_transaction_type=$ProcessingMode, mt_subAccount=$mt_subAccount, mt_prod_desc=$mt_prod_desc, mt_prod_price=$mt_prod_price, mt_etel900_subAccount=$mt_etel900_subAccount", $companyid);
	print $msgtodisplay;
	exit();
}
$smarty->assign("str_posted_variables",$str_posted_variables);

$smarty->assign("TestMode", $testIntegration);
$smarty->assign("cs_URL", $cs_URL);
$smarty->assign("bill_des", $bill_des);
$smarty->assign("bill_des_master", $cc_master_billingdescriptor);
$smarty->assign("bill_des_visa", $cc_visa_billingdescriptor);
$smarty->assign("URL", $_SESSION['cs_URL']);
$smarty->assign("Description", $subAcc['rd_description']);
$smarty->assign("ProdDescription", $mt_prod_desc);
$smarty->assign("isSubAccount", $mt_subAccount != -1);
$smarty->assign("isSubscription", $subAcc['rd_initial_amount'] && $subAcc['rd_trial_days'] > 0);
$smarty->assign("InitialAmount", $InitialAmount);
$smarty->assign("TrialDays", $TrialDays);
$smarty->assign("isRecurring", $subAcc['recur_charge']>0 && $subAcc['recur_day']>0);
$smarty->assign("RecurAmount", "$".formatMoney($subAcc['recur_charge']));
$smarty->assign("RecurDays", $RecurDays);
$smarty->assign("TotalCharge", formatMoney($chargeAmount));
$smarty->assign("NextDate", $nextDate);
$smarty->assign("isPasswordManagement", $_SESSION['cs_enable_passmgmt']==1);
$smarty->assign("str_ipaddress", $str_ipaddress);

//CreditCard
if($str_country=="")
	$str_country="United States";



if ($cc_customer_fee) $smarty->assign("CustomerFee", " ($".formatMoney($_SESSION['amount'])." charge + $".formatMoney($cc_customer_fee)." processing fee)");
$smarty->assign("Subscription", $subscription);
$smarty->assign("Recurring", $subAcc['recur_charge']>0);
$smarty->assign("Adult", $transaction_type == 'adlt');

$HackerSafe = "<a target='_blank' href='https://www.scanalert.com/RatingVerify?ref=www.etelegate.com'><img width='115' height='32' border='0' src='//images.scanalert.com/meter/www.etelegate.com/22.gif' alt='HACKER SAFE certified sites prevent over 99.9% of hacker crime.' oncontextmenu='alert(\"Copying Prohibited by Law - HACKER SAFE is a Trademark of ScanAlert\"); return false;'></a>";



$smarty->assign("mt_hide_logo",$_REQUEST['mt_hide_logo']||$_SESSION['mt_hide_logo']);
$smarty->assign("HackerSafe", $HackerSafe);
$smarty->assign("Bullets", $Bullets);
$smarty->assign("str_firstname", $str_firstname);
$smarty->assign("str_lastname", $str_lastname);
$smarty->assign("str_username", $str_username);
$smarty->assign("str_password", $str_password);
$smarty->assign("str_address", $str_address);
$smarty->assign("str_country", $str_country);
$smarty->assign("str_city", $str_city);
$smarty->assign("str_state", $str_state);
$smarty->assign("str_otherstate", $str_otherstate);
$smarty->assign("str_zipcode", $str_zipcode);
$smarty->assign("str_phonenumber", $str_phonenumber);
$smarty->assign("str_emailaddress", $str_emailaddress);

$smarty->assign("opt_Countrys", func_get_country_select($str_country));
$smarty->assign("opt_States", func_get_state_select($str_state));
$smarty->assign("str_emailaddress", $str_emailaddress);
$smarty->assign("gkard_support", $companyInfo['bk_gkard']);

if($companyInfo['bank_Creditcard']==19 && ($_SESSION['ProcessingMode']=='UserAccount' || $_SESSION['ProcessingMode']=='Credit'))
{
	$custom_text = "Forcetronix Inc.<BR>
	U12 Gamma Commercial Complex, #47<BR>
	Rizal Highway cor. Manila Avenue,<BR>
	Subic Bay Freeport, Olongapo City<BR>
	Philippines<BR>
	Is an authorized payment service provider

<strong>$companyInfo[cs_name]</strong><BR>
$companyInfo[cs_support_email]<BR>
$companyInfo[cs_support_phone]
	";
	$smarty->assign("custom_text", $custom_text);

}

etel_smarty_display('int_header.tpl');
if($_SESSION['ProcessingMode']=='Check') etel_smarty_display('int_check.tpl');
else if($_SESSION['ProcessingMode']=='Web900') etel_smarty_display('int_web900.tpl');
else if($_SESSION['ProcessingMode']=='UserAccount') etel_smarty_display('int_account.tpl');
else etel_smarty_display('int_creditcard.tpl');


if(!$_SESSION['mt_prod_desc']) $_SESSION['mt_prod_desc']=formatMoney($chargeAmount);

?>

<?php
etel_smarty_display('int_footer.tpl');
?>



