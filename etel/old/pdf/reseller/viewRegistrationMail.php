<?php

include ("includes/sessioncheck.php");
include("includes/mail_letter_template.php");

	$companyname = isset($HTTP_GET_VARS['company'])?trim($HTTP_GET_VARS['company']):"[CompanyName]";
	$username = isset($HTTP_GET_VARS['user'])?trim($HTTP_GET_VARS['user']):"[UserName]";
	$password = isset($HTTP_GET_VARS['pass'])?trim($HTTP_GET_VARS['pass']):"[PassWord]";
	$reseller = isset($HTTP_GET_VARS['resel'])?trim($HTTP_GET_VARS['resel']):"";

	//$msgtodisplay = func_reseller_merchant_loginletter();
	$msgtodisplay =  str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", nl2br(func_reseller_merchant_loginletter()));

	if($companyname !="" && $username !="" && $password !="" && $reseller !="") {
		$msgtodisplay = str_replace("[ResellerCompanyName]",$reseller,$msgtodisplay);
		$msgtodisplay = str_replace("[CompanyName]",$companyname,$msgtodisplay);
		$msgtodisplay = str_replace("[UserName]",$username,$msgtodisplay);
	}
	print $msgtodisplay;
?>