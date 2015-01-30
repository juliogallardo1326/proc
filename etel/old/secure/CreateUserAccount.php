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
if(!$mt_language) $mt_language = $_SESSION['tmpl_language'];
if(!$mt_language) $mt_language = 'eng';
$_SESSION['tmpl_language'] = $mt_language;
$smarty->assign("mt_language",$_SESSION['tmpl_language']);
$smarty->assign("tmpl_language",$_SESSION['tmpl_language']);
$smarty->assign("ShowSubmitButton", 1);

$smarty->assign("opt_Countrys", func_get_country_select($str_country));
$smarty->assign("opt_States", func_get_state_select($str_state));
$smarty->assign("accountCreated",0);

foreach($_REQUEST as $k => $c)
	$str_posted_variables.= "<input type='hidden' name='".quote_smart($k)."' value='".quote_smart($c)."' >";
	
$name = (isset($HTTP_POST_VARS['firstname'])?quote_smart($HTTP_POST_VARS['firstname']):"");
$surname= (isset($HTTP_POST_VARS['lastname'])?quote_smart($HTTP_POST_VARS['lastname']):"");
$password= (isset($HTTP_POST_VARS['td_password'])?quote_smart($HTTP_POST_VARS['td_password']):"");
$address= (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
$address2= (isset($HTTP_POST_VARS['address2'])?quote_smart($HTTP_POST_VARS['address2']):"");
$city= (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
$otherstate =  (isset($HTTP_POST_VARS['otherstate'])?quote_smart($HTTP_POST_VARS['otherstate']):"");
$zipcode= (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");
$phone =(isset($HTTP_POST_VARS['telephone'])?quote_smart($HTTP_POST_VARS['telephone']):"");
$td_bank_number =(isset($HTTP_POST_VARS['td_bank_number'])?quote_smart($HTTP_POST_VARS['td_bank_number']):"");
$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
$CCnumber= (isset($HTTP_POST_VARS['number'])?quote_smart($HTTP_POST_VARS['number']):"");
$cvv2= (isset($HTTP_POST_VARS['cvv2'])?quote_smart($HTTP_POST_VARS['cvv2']):"");
$cardtype= (isset($HTTP_POST_VARS['cardtype'])?quote_smart($HTTP_POST_VARS['cardtype']):"");
$td_bank_number =(isset($HTTP_POST_VARS['td_bank_number'])?quote_smart($HTTP_POST_VARS['td_bank_number']):"");
$mm= (isset($HTTP_POST_VARS['mm'])?quote_smart($HTTP_POST_VARS['mm']):"");
if($mm < 10) $mm = "0".$mm;
$yyyy= (isset($HTTP_POST_VARS['yyyy'])?quote_smart($HTTP_POST_VARS['yyyy']):"");
$validupto="$yyyy/$mm";

if($edit_mode && $_POST['submitForm'] != 'ProcessAccount')
{
	$username= quote_smart($HTTP_POST_VARS['username']);
	$password= quote_smart($HTTP_POST_VARS['password']);
	$acc_MD5 = md5($username.$password);
	if(!$username || !$password) $errormsg = "Your Account Could not be found. Please Try Again.";
	
	$sql = "SELECT * FROM `cs_customeraccount` where 
	ca_email = '$username' AND
	ca_password='$acc_MD5'	";
	$result = mysql_query($sql);
	if(!$result) $errormsg = "Your Account Could not be found. Please Try Again.";
	else
	{
		$cs_customeraccount = mysql_fetch_assoc($result);
		
		$_POST['email'] = $cs_customeraccount['ca_email'];
		$_POST['acc_MD5'] = $cs_customeraccount['ca_password'];
		$_POST['firstname'] = $cs_customeraccount['ca_name'];
		$_POST['lastname'] = $cs_customeraccount['ca_surname'];
		$_POST['address'] = $cs_customeraccount['ca_address'];
		$_POST['address2'] = $cs_customeraccount['ca_address2'];
		$_POST['city'] = $cs_customeraccount['ca_city'];
		$_POST['state'] = $cs_customeraccount['ca_state'];
		$_POST['zipcode'] = $cs_customeraccount['ca_zipcode'];
		$_POST['country'] = $cs_customeraccount['ca_country'];
		$_POST['telephone'] = $cs_customeraccount['ca_phone'];
		$_POST['cvv2'] = $cs_customeraccount['ca_cvv2'];
		$_POST['cardtype'] = $cs_customeraccount['ca_cardtype'];
		$_POST['CCnumber'] = $cs_customeraccount['ca_CCNumber'];
		$_POST['validupto'] = $cs_customeraccount['ca_validto'];
		$_POST['td_bank_number'] = $cs_customeraccount['ca_bankPhone'];
		$smarty->assign("opt_States", func_get_state_select($cs_customeraccount['ca_state']));

		$_SESSION['ca_ID']=$cs_customeraccount['ca_ID'];
		$_SESSION['ca_email']=$cs_customeraccount['ca_email'];

			
		
		
		$smarty->assign("POST", $_POST);
		
	}
	
	if ($errormsg) 
	{
		$msgdisplay="$errormsg";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		toLog('error','customer', "Customer could not Login in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		print $msgtodisplay;
		die();
	}

}

if($_POST['submitForm'] == 'ProcessAccount')
{
	
	$sql = "INSERT INTO ";
	$sql_end = "";
	
	if($_SESSION['ca_ID'] && $edit_mode)
	{
		$sql = "UPDATE ";
		$email = $_SESSION['ca_email'];
		$sql_end = "WHERE ca_ID = '".$_SESSION['ca_ID']."'";
	}
	
	if(!$name) 		$errormsg = "Invalid Name";
	if(!$surname) 	$errormsg = "Invalid Last Name";
	if(!$password) 	$errormsg = "Invalid Password";
	if(!$address) 	$errormsg = "Invalid Address";
	if(!$city) 		$errormsg = "Invalid City";
	if(!$zipcode) 	$errormsg = "Invalid ZipCode";
	if(!$country) 	$errormsg = "Invalid Country";
	if(!$email) 	$errormsg = "Invalid Email";
	if(!$phone) 	$errormsg = "Invalid Phone";
	if(!$cvv2) 		$errormsg = "Invalid cvv2";
	if(!$cardtype) 	$errormsg = "Invalid Card Type";
	if(!$CCnumber) 	$errormsg = "Invalid Credit Card";
	if(!$yyyy || !$mm) 	$errormsg = "Invalid Expiration Date";
	
	$email_info = infoListEmail($email);
	if($email_info['cnt']>0) 	$errormsg = "Unsubscribed Email Address ".$email.".<BR>Reason: ".$email_info['ec_reason'].".<BR>Please use a different email address.";

	$acc_MD5 = md5($email.$password);

	

	$sql .= "`cs_customeraccount` set 
	ca_email = '$email', 
	ca_password='$acc_MD5',
	ca_name='$name',
	ca_surname='$surname',
	ca_address='$address',
	ca_address2='$address2',
	ca_city='$city',
	ca_state='$state',
	ca_zipcode='$zipcode',
	ca_country='$country',
	ca_phone='$phone',
	ca_cvv2='$cvv2',
	ca_cardtype='$cardtype',
	ca_CCNumber='$CCnumber',
	ca_validto='$validupto',
	ca_bankPhone='$td_bank_number'	
	$sql_end";
	
	if($_SESSION['ca_ID'] && $edit_mode)
	{
	}

	
	if(!$errormsg)
	{
		$result = mysql_query($sql);
		if(!$result) $errormsg = "This Email Address is already in use, Please try a different one.";
		$ca_ID = mysql_insert_id();
	}
	
	if ($errormsg) 
	{
		$smarty->assign("accountCreated",0);
		$smarty->assign("accountMsg","Error: $errormsg<BR>Please go back and fix your entry");
		$smarty->assign("POST", $_POST);
	}
	else
	{
		$_SESSION['ca_ID']=$ca_ID;
		$_SESSION['ca_email']=$email;
		$smarty->assign("accountMsg","Your Account $email has been Created/Updated Successfully.<BR> If you are placing an order at this time, please return to the order page and enter your new Etelegate Account Information.<BR><a href='https://www.etelegate.biz'><img border='0' src='/images/back.jpg'></a>");
		toLog('misc','customer',"Customer Account $ca_ID created with email $email");
		$smarty->assign("accountCreated",1);
		$smarty->assign("ShowSubmitButton", 0);
		$success=1;
	}

}

if($_SESSION['ca_ID'] && !$success && !$edit_mode)
{
	$email = $_SESSION['ca_email'];
	$smarty->assign("accountMsg","Your Account $email has already been Created.");
	toLog('misc','customer',"Customer Account $ca_ID created with email $email");
	$smarty->assign("accountCreated",1);
	$smarty->assign("ShowSubmitButton", 0);
}
	


$smarty->assign("edit_mode", $edit_mode);
etel_smarty_display('int_create_account.tpl');
etel_smarty_display('int_footer.tpl');
?>



