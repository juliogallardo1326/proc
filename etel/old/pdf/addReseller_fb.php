<?php

if($_REQUEST['gw_id']) $gateway_db_select = $_REQUEST['gw_id'];
require_once("includes/indexheader.php");
require_once("includes/function2.php");
	etel_smarty_display('main_header.tpl');

foreach($HTTP_POST_VARS as $k => $c)
	$postback.= "<input type='hidden' name='$k' value='$c' >";
	
$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
if($companyname!="")
{
	$contactname = (isset($HTTP_POST_VARS['contactname'])?quote_smart($HTTP_POST_VARS['contactname']):"");
	$username = strtolower(quote_smart($HTTP_POST_VARS['username']));
	//$password= (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	//$repassword= (isset($HTTP_POST_VARS['repassword'])?quote_smart($HTTP_POST_VARS['repassword']):"");
	$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$confirmemail= (isset($HTTP_POST_VARS['confirmemail'])?quote_smart($HTTP_POST_VARS['confirmemail']):"");
	$merchantmonthly= (isset($HTTP_POST_VARS['merchantmonthly'])?quote_smart($HTTP_POST_VARS['merchantmonthly']):"");
	$phone= (isset($HTTP_POST_VARS['phone'])?quote_smart($HTTP_POST_VARS['phone']):"");
	$url= (isset($HTTP_POST_VARS['url'])?quote_smart($HTTP_POST_VARS['url']):"");
	$rd_gateway_id=$_SESSION['gw_id'];
	
	$password = strtolower(substr(md5(time()+rand(0,100000)),0,8));
	
	if($companyname == "" || $contactname == ""){
		$msgtodisplay="Insufficient data.";
		message($msgtodisplay,"","Insufficient data","content.php?show=main_resellers",false);  
		etel_smarty_display('main_footer.tpl');
		exit();
	}
	if($username == "") {
		message("Please Enter a UserName.".$postback,"","UserName","content.php?show=main_resellers",false);
		etel_smarty_display('main_footer.tpl');
		exit();
	}
	if(func_checkUsernameExistInAnyTable($username,$cnn_cs)) {
		message("UserName Exists. Please Enter a different UserName.".$postback,"","UserName","content.php?show=main_resellers",false);
		etel_smarty_display('main_footer.tpl');
		exit();
	}

/*	if($password == "") {
		$msgtodisplay="Please enter Password.";
		message($msgtodisplay,"","Insufficient data");  
		exit();
	}*/
	$user_companyexist=func_checkCompanynameExistInAnyTable($companyname,$cnn_cs);
	if($user_companyexist==1){
		message("Company Name Exists. Please Enter a different Company Name.".$postback,"","Company Name","content.php?show=main_resellers",false);
		etel_smarty_display('main_footer.tpl');
		exit();
	}
	$user_mailidexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
	if($user_mailidexist==1){
		message("Existing email id. Please Enter a different Email.".$postback,"","Email","content.php?show=main_resellers",false);
		etel_smarty_display('main_footer.tpl');
		exit();
	}
	$current_date_time = func_get_current_date_time();
	
	$user_reference_num=substr(md5(time()+rand(1,9999)),0,8);
	$sql = "Insert into cs_entities
		set 
			en_ref = '".($user_reference_num)."',
			en_username = '".($username)."',
			en_password = MD5('".($username.$password)."'),
			en_email = '".quote_smart($email)."',
			en_gateway_ID = '".quote_smart($rd_gateway_id)."',
			en_type = 'reseller',
			en_signup = NOW(),
			en_type_id = '".quote_smart($user_id)."'
		";
	sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");

	//$email_from = "partners@etelegate.com";
	$email_subject = "Registration Confirmation";
	$email_message = $msgtodisplay;
	$email_to= $email;
	
	$emailData["email"] = $email;
	$emailData["full_name"] = $contactname;
	$emailData["companyname"] = $companyname;
	$emailData["username"] = $username;
	$emailData["password"] = $password;
	$emailData["gateway_select"] = $companyInfo['rd_gateway_id'];
	

	$emailContents = get_email_template("reseller_welcome_letter",$emailData);
	send_email_template("reseller_welcome_letter",$emailData);
	print $emailContents['et_htmlformat'];


etel_smarty_display('main_footer.tpl');
}
?>

