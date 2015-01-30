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
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("GET", $_GET);


require_once('includes/function.php');
include 'includes/function1.php';
$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$cc_customer_fee = $_SESSION['cc_customer_fee'];
$cs_URL = $_SESSION['cs_URL'];
$companyid = $_SESSION['companyid'];
$str_ipaddress = $_SESSION['ipaddress'];
  if("Test"==$_SESSION['integration_mode']) $testIntegration = " - Test Mode";
//print_r($_SESSION);
$blockrebill = (isset($HTTP_POST_VARS['hid_blockrebill'])?trim($HTTP_POST_VARS['hid_blockrebill']):"");

$product_description = (isset($HTTP_POST_VARS['mt_prod_desc'])?trim($HTTP_POST_VARS['mt_prod_desc']):"");
$blockrebill = (isset($HTTP_POST_VARS['hid_blockrebill'])?trim($HTTP_POST_VARS['hid_blockrebill']):"");
//Modifications to get user data automatically
$str_firstname = (isset($HTTP_POST_VARS['firstname'])?trim($HTTP_POST_VARS['firstname']):"");
$str_lastname = (isset($HTTP_POST_VARS['lastname'])?trim($HTTP_POST_VARS['lastname']):"");
$str_address = (isset($HTTP_POST_VARS['address'])?trim($HTTP_POST_VARS['address']):"");
$str_country = (isset($HTTP_POST_VARS['country'])?trim($HTTP_POST_VARS['country']):"");
$str_city = (isset($HTTP_POST_VARS['city'])?trim($HTTP_POST_VARS['city']):"");
$str_state = (isset($HTTP_POST_VARS['state'])?trim($HTTP_POST_VARS['state']):"select");
$str_otherstate = (isset($HTTP_POST_VARS['otherstate'])?trim($HTTP_POST_VARS['otherstate']):"");
$str_username= (isset($HTTP_POST_VARS['td_username'])?trim($HTTP_POST_VARS['td_username']):"");
$str_zipcode = (isset($HTTP_POST_VARS['zipcode'])?trim($HTTP_POST_VARS['zipcode']):"");
$str_phonenumber = (isset($HTTP_POST_VARS['telephone'])?trim($HTTP_POST_VARS['telephone']):"");
$str_emailaddress = (isset($HTTP_POST_VARS['email'])?trim($HTTP_POST_VARS['email']):"");

$str_currency = func_get_cardcurrency('Master',$companyid,$cnn_cs);
$mastercurrency=$str_currency;
$visacurrency=func_get_cardcurrency('Visa',$companyid,$cnn_cs); 

$_SESSION['card_declined_reset']=false;

$chargeAmount = $_SESSION['amount']+$cc_customer_fee;
//modifications end here
//$trans_type ="tele";
if($companyid ==""){ 
	$msgdisplay="You are not a valid user.";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='black'>$msgdisplay</font></td></tr><tr><td align='center'><a href='https://www.etelegate.com'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();	
}
$qry_select ="select gateway_id,cc_billingdescriptor ,companyname,transaction_type  from cs_companydetails where userId= $companyid";
$select =mysql_query($qry_select);
$show_val = mysql_fetch_array($select);
$gateway_id=$show_val[0];
$bill_des=$show_val[1];
$companyname=$show_val[2];
$transaction_type=$show_val['transaction_type'];
//echo $gateway_id;

if (!$mt_subAccount) $mt_subAccount = -1;
if ($mt_subAccount != -1)
{
	$sql = "SELECT c.*,b.transaction_type,b.cd_password_mgmt FROM `cs_rebillingdetails` as c, `cs_companydetails` as b WHERE b.userId = `company_user_id` AND `rd_subName` = '$mt_subAccount' AND `company_user_id` = " .$companyid ;
	if(!($result = mysql_query($sql,$cnn_cs)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_update."<br>");
		$msgdisplay= "Failed to access company Product Database";
		toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		print $msgdisplay;
		exit();
	}
	else
	{
		$subAcc = mysql_fetch_assoc($result);
	}
	$nextDate = $_SESSION['nextDateInfo'];
}

$InitialAmount = "$".formatMoney($subAcc['rd_initial_amount']);
if ($cc_customer_fee) $InitialAmount.= " (+ $".formatMoney($cc_customer_fee).")";
$TrialDays = $subAcc['rd_trial_days']." Day(s)";
if(!$subAcc['rd_trial_days']) $TrialDays = "One Time Payment";
$RecurDays = $subAcc['recur_day']." Day(s)";

$smarty->assign("TestMode", $testIntegration);
$smarty->assign("cs_URL", $cs_URL);
$smarty->assign("bill_des", $bill_des);
$smarty->assign("subscription", $subAcc['recur_charge']>0);
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
$smarty->assign("NextDate", $nextDate);
$smarty->assign("NextDate", $nextDate);
$smarty->assign("NextDate", $nextDate);
$smarty->assign("NextDate", $nextDate);
$smarty->assign("NextDate", $nextDate);
//CreditCard
if($str_country=="")
	$str_country="United States";

$Bullets = "<ul>";
$Bullets .= "<li>You will be charged $<strong>".formatMoney($chargeAmount)."</strong> today";
     
if ($cc_customer_fee) $Bullets .= " ($".formatMoney(formatMoney($subAcc['rd_initial_amount']))." charge + $".formatMoney($cc_customer_fee)." processing fee)";
$Bullets .= "</li>";
$Bullets .= "<li>Your purchase will be billed and appear on your billing statement as: &quot;$bill_des&quot;.</li>";
if ($subscription) {
	$Bullets .= "<li> You can cancel Your subscription at ANY time!<br></li>";
}
$Bullets .= "<li>All Fraudulent Transactions will be prosecuted to the full extent of law. </li>";
if ($subAcc['recur_charge']) {
	$Bullets .= "<li>Your subscription will automatically be renewed for your convenience until you cancel. </li>";
}
if ($transaction_type == 'adlt') {
	$Bullets .= "<li>All Sales are Final. </li>";
} 
$Bullets .= "</ul>";

$smarty->assign("Bullets", $Bullets);
$smarty->assign("str_firstname", $str_firstname);
$smarty->assign("str_lastname", $str_lastname);
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
$smarty->assign("str_emailaddress", $str_emailaddress);


$smarty->display('int_header.tpl');

$smarty->display('int_creditcard.tpl');


if(!$_SESSION['mt_prod_desc']) $_SESSION['mt_prod_desc']=formatMoney($chargeAmount);

?>

<?php
$smarty->display('int_footer.tpl');
?>



