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
// editCompanyProfile.php:	This admin page functions for editing the company details. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");



include("../includes/function2.php");
$user_reference_num="";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	if ($username != "") {
		$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
		$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");

		$qry_select_user = "select username,companyname from cs_companydetails where ( username='$username' or companyname='$companyname' or email='$email')";
		//print($qry_select_user);
		if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		elseif(mysql_num_rows($show_sql) >0) 
		{
			if (mysql_result($show_sql, 0, 0) == $username) {
				$msgtodisplay="user name ".$username." already exists";
			} 
			elseif((mysql_result($show_sql, 0, 1) == $companyname)) 
			{
				$msgtodisplay="company name ".$companyname." already exists";
			}
			else{$msgtodisplay="Email Address ".$email." already exists";}
			
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
		else 
		{
			$suspend = (isset($HTTP_POST_VARS['suspend'])?quote_smart($HTTP_POST_VARS['suspend']):"NO");

			$first_name = (isset($HTTP_POST_VARS['first_name'])?quote_smart($HTTP_POST_VARS['first_name']):"");
			$family_name = (isset($HTTP_POST_VARS['family_name'])?quote_smart($HTTP_POST_VARS['family_name']):"");
			$job_title = (isset($HTTP_POST_VARS['job_title'])?quote_smart($HTTP_POST_VARS['job_title']):"");
			$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
			$confirm_contact_email = (isset($HTTP_POST_VARS['confirm_contact_email'])?quote_smart($HTTP_POST_VARS['confirm_contact_email']):"");
			$contact_phone = (isset($HTTP_POST_VARS['contact_phone'])?quote_smart($HTTP_POST_VARS['contact_phone']):"");
			$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
			$reseller_other = (isset($HTTP_POST_VARS['reseller_other'])?quote_smart($HTTP_POST_VARS['reseller_other']):"");

			$sTitle 				= (isset($HTTP_POST_VARS['cboTitle'])?quote_smart($HTTP_POST_VARS['cboTitle']):"");
			$sYear 					= (isset($HTTP_POST_VARS['cboYear'])?quote_smart($HTTP_POST_VARS['cboYear']):"");
			$sMonth					= (isset($HTTP_POST_VARS['cboMonth'])?quote_smart($HTTP_POST_VARS['cboMonth']):"");
			$sDay					= (isset($HTTP_POST_VARS['cboDay'])?quote_smart($HTTP_POST_VARS['cboDay']):"");	
			$sDateOfBirth			= ($sYear."-".$sMonth."-".$sDay);
			$sSex					= (isset($HTTP_POST_VARS['cboSex'])?quote_smart($HTTP_POST_VARS['cboSex']):"");
			$sAddress				= (isset($HTTP_POST_VARS['txtAddress'])?quote_smart($HTTP_POST_VARS['txtAddress']):"");
			$sPostCode				= (isset($HTTP_POST_VARS['txtPostCode'])?quote_smart($HTTP_POST_VARS['txtPostCode']):"");
			$sResidenceTelephone	= (isset($HTTP_POST_VARS['residence_telephone'])?quote_smart($HTTP_POST_VARS['residence_telephone']):"");
			$sFax					= (isset($HTTP_POST_VARS['fax'])?quote_smart($HTTP_POST_VARS['fax']):"");
			$setupFees				= (isset($HTTP_POST_VARS['txtSetupFee'])?quote_smart($HTTP_POST_VARS['txtSetupFee']):"");		
			if ($setupFees=="") 
				$setupFees=0;
			
			$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
			$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
			$faxnumber = (isset($HTTP_POST_VARS['faxnumber'])?quote_smart($HTTP_POST_VARS['faxnumber']):"");
			$address = (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");;
			$city = (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
			$state = (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
			$ostate = (isset($HTTP_POST_VARS['ostate'])?quote_smart($HTTP_POST_VARS['ostate']):"");
			$country = (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
			$zipcode = (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");

			$url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
			$url2 = (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
			$url3 = (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
			$url4 = (isset($HTTP_POST_VARS['url4'])?quote_smart($HTTP_POST_VARS['url4']):"");
			$url5 = (isset($HTTP_POST_VARS['url5'])?quote_smart($HTTP_POST_VARS['url5']):"");
			
			$strMerchantName = (isset($HTTP_POST_VARS['txtMerchantName'])?quote_smart($HTTP_POST_VARS['txtMerchantName']):"");
			$strTollFreeNumber = (isset($HTTP_POST_VARS['txtTollFreeNumber'])?quote_smart($HTTP_POST_VARS['txtTollFreeNumber']):"");
			$strRetrievalNumber = (isset($HTTP_POST_VARS['txtRetrievalNumber'])?quote_smart($HTTP_POST_VARS['txtRetrievalNumber']):"");
			$strSecurityNumber = (isset($HTTP_POST_VARS['txtSecurityNumber'])?quote_smart($HTTP_POST_VARS['txtSecurityNumber']):"");
			$strProcessor = (isset($HTTP_POST_VARS['txtProcessor'])?quote_smart($HTTP_POST_VARS['txtProcessor']):"");
			$strtxtBillingdescriptor = (isset($HTTP_POST_VARS['txtBillingdescriptor'])?quote_smart($HTTP_POST_VARS['txtBillingdescriptor']):"");
			
			$strChargeBack = (isset($HTTP_POST_VARS['txtChargeBack'])?quote_smart($HTTP_POST_VARS['txtChargeBack']):"0");
			$strCredit =   (isset($HTTP_POST_VARS['txtCredit'])?quote_smart($HTTP_POST_VARS['txtCredit']):"0");
			$strDiscountRate  = (isset($HTTP_POST_VARS['txtDiscountRate'])?quote_smart($HTTP_POST_VARS['txtDiscountRate']):"0");
			$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"0");
			$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoicefee'])?quote_smart($HTTP_POST_VARS['txtVoicefee']):"0");
			$strReserve  = (isset($HTTP_POST_VARS['txtReserve'])?quote_smart($HTTP_POST_VARS['txtReserve']):"");
			$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"0");
			$transaction_type = (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");
			$strAutoCancel = (isset($HTTP_POST_VARS['chk_auto_cancel'])?quote_smart($HTTP_POST_VARS['chk_auto_cancel']):"N");
			$iTimeFrame  = (isset($HTTP_POST_VARS['time_frame'])?quote_smart($HTTP_POST_VARS['time_frame']):"-1");
			$strShippingCancel  = (isset($HTTP_POST_VARS['chk_shipping_cancel'])?quote_smart($HTTP_POST_VARS['chk_shipping_cancel']):"N");
			$iShippingTimeFrame  = (isset($HTTP_POST_VARS['shipping_time_frame'])?quote_smart($HTTP_POST_VARS['shipping_time_frame']):"-1");
			$strAutoApprove  = (isset($HTTP_POST_VARS['chk_auto_approve'])?quote_smart($HTTP_POST_VARS['chk_auto_approve']):"N");
			$strUnsubscribe  = (isset($HTTP_POST_VARS['chk_unsubscribe'])?quote_smart($HTTP_POST_VARS['chk_unsubscribe']):"1");
				
			if($trans_activity =="") 
				$trans_activity =0;
		
			if($strVoiceauthFee =="")
				$strVoiceauthFee =0;

			if($iShippingTimeFrame == "")
				$iShippingTimeFrame = "-1";

			if($iTimeFrame == "")
				$iTimeFrame = "-1";

			$company_type = (isset($HTTP_POST_VARS['company_type'])?quote_smart($HTTP_POST_VARS['company_type']):"");
			$other_company_type = (isset($HTTP_POST_VARS['other_company_type'])?quote_smart($HTTP_POST_VARS['other_company_type']):"");
			$customerservice_phone = (isset($HTTP_POST_VARS['customerservice_phone'])?quote_smart($HTTP_POST_VARS['customerservice_phone']):"");

			$volume= (isset($HTTP_POST_VARS['volume'])?quote_smart($HTTP_POST_VARS['volume']):"0");
			$avgticket= (isset($HTTP_POST_VARS['avgticket'])?quote_smart($HTTP_POST_VARS['avgticket']):"0");
			$chargeper= (isset($HTTP_POST_VARS['chargeper'])?quote_smart($HTTP_POST_VARS['chargeper']):"0");
			$prepro= (isset($HTTP_POST_VARS['prepro'])?quote_smart($HTTP_POST_VARS['prepro']):"");
			$rebill= (isset($HTTP_POST_VARS['rebill'])?quote_smart($HTTP_POST_VARS['rebill']):"");
			$currpro= (isset($HTTP_POST_VARS['currpro'])?quote_smart($HTTP_POST_VARS['currpro']):"");

			if($volume=="") 
				$volume=0;
			if($avgticket=="")
				$avgticket=0;
			if($chargeper=="")
				$chargeper=0;

			$txtPackagename = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"");
			$txtPackageProduct= (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"");
			$txtPackagePrice= (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"0");
			$txtRefundPolicy= (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"");
			$txtDescription= (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"");

			if($txtPackagePrice=="") 
				$txtPackagePrice=0;

			$currentBank = (isset($HTTP_POST_VARS['currentBank'])?quote_smart($HTTP_POST_VARS['currentBank']):"");
			$bank_other = (isset($HTTP_POST_VARS['bank_other'])?quote_smart($HTTP_POST_VARS['bank_other']):"");
			$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?quote_smart($HTTP_POST_VARS['beneficiary_name']):"");
			$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?quote_smart($HTTP_POST_VARS['bank_account_name']):"");
			$bank_address = (isset($HTTP_POST_VARS['bank_address'])?quote_smart($HTTP_POST_VARS['bank_address']):"");
			$bank_country = (isset($HTTP_POST_VARS['bank_country'])?quote_smart($HTTP_POST_VARS['bank_country']):"");
			$bank_phone = (isset($HTTP_POST_VARS['bank_phone'])?quote_smart($HTTP_POST_VARS['bank_phone']):"");
			$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?quote_smart($HTTP_POST_VARS['bank_sort_code']):"");
			$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?quote_smart($HTTP_POST_VARS['bank_account_number']):"");
			$bank_swift_code = (isset($HTTP_POST_VARS['bank_swift_code'])?quote_smart($HTTP_POST_VARS['bank_swift_code']):"");
			$send_ecommercemail = (isset($HTTP_POST_VARS['chk_sendecommerce'])?quote_smart($HTTP_POST_VARS['chk_sendecommerce']):0);
			
			
			
			

			$qry_update_user  = " insert into cs_companydetails (first_name,family_name,";
			$qry_update_user .= "job_title,contact_email,contact_phone,how_about_us,";
			$qry_update_user .= "stitle,sdateofbirth,ssex,sAddress,sPostCode,sResidenceTelephone,sFax,";		
			$qry_update_user .= "username,password,companyname,phonenumber,address,city,state,ostate,";
			$qry_update_user .= "merchantName,tollFreeNumber,retrievalNumber,securityNumber,processor,";
			$qry_update_user .= "chargeback,credit,discountrate,transactionfee,reserve,voiceauthfee,auto_cancel,"; $qry_update_user .= "time_frame,auto_approve,transaction_type,shipping_cancel,shipping_timeframe,"; 
			$qry_update_user .= "send_mail,country,zipcode,suspenduser,activeuser,url1,";
			$qry_update_user .= "url2,url3,billingdescriptor,fax_number,reseller_other,";
			$qry_update_user .= "company_type,other_company_type,customer_service_phone,email,";
			$qry_update_user .= "volumenumber,avgticket,chargebackper,preprocess,recurbilling,currprocessing,";
			$qry_update_user .= "telepackagename,telepackageprod,telepackageprice,telerefundpolicy,";
			$qry_update_user .= "teledescription,company_bank,other_company_bank,beneficiary_name,";
			$qry_update_user .= "bank_account_name,bank_address,bank_country,bank_phone,bank_sort_code,"; 
			$qry_update_user .= "bank_account_number,bank_swift_code,completed_merchant_application,setupfees,send_ecommercemail,url4,url5) ";

			$qry_update_user .= "values ('$first_name','$family_name','$job_title','$contact_email',";
			$qry_update_user .= "'$contact_phone','$how_about_us','$sTitle','$sDateOfBirth','$sSex','$sAddress',";
			$qry_update_user .= "'$sPostCode','$sResidenceTelephone','$sFax','$username','$password',";
			$qry_update_user .= "'$companyname','$phonenumber','$address','$city','$state','$ostate',";
			$qry_update_user .= "'$strMerchantName','$strTollFreeNumber','$strRetrievalNumber',";
			$qry_update_user .= "'$strSecurityNumber','$strProcessor',$strChargeBack,$strCredit,$strDiscountRate,";
			$qry_update_user .= "$strTransactionFee,$strReserve,$strVoiceauthFee,'$strAutoCancel',$iTimeFrame,";
			$qry_update_user .= "'$strAutoApprove','$transaction_type','$strShippingCancel',$iShippingTimeFrame,";
			$qry_update_user .= "$strUnsubscribe,'$country','$zipcode','$suspend',$trans_activity,'$url1',";
			$qry_update_user .= "'$url2','$url3','$strtxtBillingdescriptor','$faxnumber','$reseller_other',";
			$qry_update_user .= "'$company_type','$other_company_type','$customerservice_phone','$email',";
			$qry_update_user .= "'$volume','$avgticket','$chargeper','$prepro','$rebill','$currpro',";
			$qry_update_user .= "'$txtPackagename','$txtPackageProduct',$txtPackagePrice,'$txtRefundPolicy',";
			$qry_update_user .= "'$txtDescription','$currentBank','$bank_other','$beneficiary_name',";
			$qry_update_user .= "'$bank_account_name','$bank_address','$bank_country','$bank_phone',";
			$qry_update_user .= "'$bank_sort_code','$bank_account_number','$bank_swift_code',1,$setupFees,$send_ecommercemail,'$url4','$url5')";
		//	print $qry_update_user."<br>";
			if(!($show_sql=mysql_query($qry_update_user)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}		
			else 
			{	$is_success=0;	
				$user_id=mysql_insert_id();
				$user_reference_num=func_User_Ref_No($user_id);
				$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,$cnn_cs,'userId',$user_id);
				if($is_success==1){
				////////
				$email_from = $_SESSION['gw_emails_sales'];
				$email_to = $_SESSION['gw_emails_sales'];
				$email_subject = "Registration Confirmation";
				$transactiontype=func_get_merchant_name($transaction_type);
				$email_message = func_getreplymailbody_admin($companyname,$username,$password,$user_reference_num,$transactiontype ,$how_about_us,$volume);
				func_send_mail($email_from,$email_to,$email_subject,$email_message);
				//echo $email_message	;
					/////////////
				
				
				$msgtodisplay="New Company '".$companyname."' has been added your user Reference No is $user_reference_num ";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
				}
			}
					
	  }
	}



	$script_display ="";
?>
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">
function emailsubmit() {
	//document.Frmcompany.action="viewBottom.php";
	document.Frmcompany.method="POST";
	document.Frmcompany.submit();
}

function validator(){
	if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
		document.Frmcompany.ostate.disabled= true;
		document.Frmcompany.ostate.value= "";
		document.Frmcompany.state.disabled = false;
	} else {
		document.Frmcompany.state.disabled = true;
		document.Frmcompany.state.value= "";
		document.Frmcompany.ostate.disabled= false;
	}
	
	return false;
}

function displayverification(){
	document.getElementById('auto_sendecommerce').style.display = "none";
  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
	 	document.getElementById('script').style.display = "";
	 	document.getElementById('auto_cancel').style.display = "";
		document.getElementById('auto_sendecommerce').style.display = "none";
		document.Frmcompany.chk_sendecommerce.checked = false;
	}else {
	 	document.getElementById('script').style.display = "none";
	 	document.getElementById('auto_cancel').style.display = "none";
		document.getElementById('auto_sendecommerce').style.display = "";
		document.Frmcompany.chk_sendecommerce.checked = true;
	}
	return false;
}

function validateForm() {
	trimSpace(document.Frmcompany.companyname);
	if(document.Frmcompany.companyname.value==""){
		alert("Please enter company name")
		document.Frmcompany.companyname.focus();
		return false;
	}
	trimSpace(document.Frmcompany.username);
	if(document.Frmcompany.username.value==""){
		alert("Please enter username")
		document.Frmcompany.username.focus();
		return false;
	}
	trimSpace(document.Frmcompany.username);
	if(document.Frmcompany.username.value!="" &&(!func_vali_pass(document.Frmcompany.username)))
  	{
  		alert ("Special characters not allowed"); 
  		document.Frmcompany.username.focus();
  		document.Frmcompany.username.select();
  		return false;
  	}
	trimSpace(document.Frmcompany.password);
	if(document.Frmcompany.password.value==""){
		alert("Please enter correct password")
		document.Frmcompany.password.focus();
		return false;
	}
	trimSpace(document.Frmcompany.password);
	if(document.Frmcompany.password.value!="" &&(!func_vali_pass(document.Frmcompany.password)))
  	{
  		alert ("Special characters not allowed"); 
  		document.Frmcompany.password.focus();
  		document.Frmcompany.password.select();
  		return false;
  	}
	trimSpace(document.Frmcompany.password1);
	if(document.Frmcompany.password1.value==""){
		alert("Please enter correct password")
		document.Frmcompany.password1.focus();
		return false;
	}
	
	if(document.Frmcompany.password1.value != document.Frmcompany.password.value){
		alert("Please enter correct password")
		document.Frmcompany.password1.focus();
		return false;
	}
	trimSpace(document.Frmcompany.address);
	if(document.Frmcompany.address.value==""){
		alert("Please enter address")
		document.Frmcompany.address.focus();
		return false;
	}
	trimSpace(document.Frmcompany.city);
	if(document.Frmcompany.city.value==""){
		alert("Please enter city")
		document.Frmcompany.city.focus();
		return false;
	}
	trimSpace(document.Frmcompany.zipcode);
	if(document.Frmcompany.zipcode.value==""){
		alert("Please enter zipcode")
		document.Frmcompany.zipcode.focus();
		return false;
	}
	trimSpace(document.Frmcompany.phonenumber);
	if(document.Frmcompany.phonenumber.value==""){
		alert("Please enter phone number")
		document.Frmcompany.phonenumber.focus();
		return false;
	}
	trimSpace(document.Frmcompany.company_type);
	if(document.Frmcompany.company_type.value==""){
		alert("Please select a company type")
		document.Frmcompany.company_type.focus();
		return false;
	}
	if(document.Frmcompany.company_type.value=="other" && document.Frmcompany.other_company_type.value==""){
		alert("Please enter a company type")
		document.Frmcompany.other_company_type.focus();
		return false;
	}
	trimSpace(document.Frmcompany.customerservice_phone);
	if(document.Frmcompany.customerservice_phone.value==""){
		alert("Please enter customer service phone number")
		document.Frmcompany.customerservice_phone.focus();
		return false;
	}
	trimSpace(document.Frmcompany.rad_trans_type);
	if(document.Frmcompany.rad_trans_type.value==""){
		alert("Please select the merchant type.")
		document.Frmcompany.rad_trans_type.focus();
		return false;
	}

	if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
  	  if(document.Frmcompany.chk_auto_cancel.checked){
		if(document.Frmcompany.time_frame.value==""){
			alert("Please enter the Timeframe")
			document.Frmcompany.time_frame.focus();
			return false;
		}
		else if(isNaN(document.Frmcompany.time_frame.value)){
		alert("Please enter numeric values")
		document.Frmcompany.time_frame.focus();
		return false;
		}
		else if(document.Frmcompany.time_frame.value<1){
		alert("Please enter a number greater than zero")
		document.Frmcompany.time_frame.focus();
		return false;
		}
	  }
	  if(document.Frmcompany.chk_shipping_cancel.checked) {
		if(document.Frmcompany.shipping_time_frame.value==""){
			alert("Please enter the Timeframe")
			document.Frmcompany.shipping_time_frame.focus();
			return false;
		}
		else if(isNaN(document.Frmcompany.shipping_time_frame.value)){
		alert("Please enter numeric values")
		document.Frmcompany.shipping_time_frame.focus();
		return false;
		}
		else if(document.Frmcompany.shipping_time_frame.value<1){
		alert("Please enter a number greater than zero")
		document.Frmcompany.shipping_time_frame.focus();
		return false;
		}
	  }
	}
	trimSpace(document.Frmcompany.volume);
	if(document.Frmcompany.volume.value==""){
		alert("Please enter the projected monthly volume.")
		document.Frmcompany.volume.focus();
		return false;
	}
		trimSpace(document.Frmcompany.txtSetupFee);
	
	 if (isNaN(document.Frmcompany.txtSetupFee.value))
	{
		alert("Please enter numeric value.")
		document.Frmcompany.txtSetupFee.focus();
		return false;
		}
	trimSpace(document.Frmcompany.avgticket);
	if(document.Frmcompany.avgticket.value==""){
		alert("Please enter the average ticket value.")
		document.Frmcompany.avgticket.focus();
		return false;
	}
	else if (isNaN(document.Frmcompany.avgticket.value))
	{
		alert("Please enter numeric value.")
		document.Frmcompany.avgticket.focus();
		return false;
		}
	
	
	trimSpace(document.Frmcompany.currentBank);
	trimSpace(document.Frmcompany.bank_address);
	trimSpace(document.Frmcompany.bank_country);
	trimSpace(document.Frmcompany.bank_phone);
	trimSpace(document.Frmcompany.bank_sort_code);
	trimSpace(document.Frmcompany.bank_account_number);
	trimSpace(document.Frmcompany.bank_swift_code);
	/*if(document.Frmcompany.currentBank.value==""){
		alert("Please select a bank.")
		document.Frmcompany.currentBank.focus();
		return false;
	}
	if(document.Frmcompany.currentBank.value=="other" && document.Frmcompany.bank_other.value==""){
		alert("Please enter a bank name.")
		document.Frmcompany.bank_other.focus();
		return false;
	}
		
	if(document.Frmcompany.bank_address.value==""){
		alert("Please enter the bank address.")
		document.Frmcompany.bank_address.focus();
		return false;
	}
	
	if(document.Frmcompany.bank_country.value==""){
		alert("Please select the country.")
		document.Frmcompany.bank_country.focus();
		return false;
	}
	
	if(document.Frmcompany.bank_phone.value==""){
		alert("Please enter the phone number.")
		document.Frmcompany.bank_phone.focus();
		return false;
	}
	
	if(document.Frmcompany.bank_sort_code.value==""){
		alert("Please enter the sort code / branch number.")
		document.Frmcompany.bank_sort_code.focus();
		return false;
	}
	
	if(document.Frmcompany.bank_account_number.value==""){
		alert("Please enter the account number.")
		document.Frmcompany.bank_account_number.focus();
		return false;
	}
	
	if(document.Frmcompany.bank_swift_code.value==""){
		alert("Please enter the swift code.")
		document.Frmcompany.bank_swift_code.focus();
		return false;
	}*/
trimSpace(document.Frmcompany.txtChargeBack);
  if(document.Frmcompany.txtChargeBack.value==""){
    alert("Please enter Charge Back Fee")
	document.Frmcompany.txtChargeBack.focus();
	return false;
  }
  else if (document.Frmcompany.txtChargeBack.value!=""){
  if (document.Frmcompany.txtChargeBack.value.charAt(0)=='$')
  {
     
	  if (isNaN(document.Frmcompany.txtChargeBack.value.substr(1,document.Frmcompany.txtChargeBack.length)))
	  {
        alert("Please enter numeric values.");
		document.Frmcompany.txtChargeBack.focus();
		return false;
  	 }
	 else
	 {
	 	document.Frmcompany.txtChargeBack.value = document.Frmcompany.txtChargeBack.value.substr(1,document.Frmcompany.txtChargeBack.length);
		
	}	
  }
  else if(isNaN(document.Frmcompany.txtChargeBack.value)){
    alert("Please enter numeric values.")
    document.Frmcompany.txtChargeBack.focus();
	return false;
  }
 }
 trimSpace(document.Frmcompany.txtCredit);
  if(document.Frmcompany.txtCredit.value==""){
    alert("Please enter Credit Rate")
    document.Frmcompany.txtCredit.focus();
	return false;
  }
  else if (document.Frmcompany.txtCredit.value!= "")
  {
  	if (document.Frmcompany.txtCredit.value.charAt(0)=='$')
	{
		if (isNaN(document.Frmcompany.txtCredit.value.substr(1,document.Frmcompany.txtCredit.length)))
		{
			 alert("Please enter numeric values.");
			 document.Frmcompany.txtCredit.focus();
			 return false;
		}
		else
		{
		   document.Frmcompany.txtCredit.value = document.Frmcompany.txtCredit.value.substr(1,document.Frmcompany.txtCredit.length);
		}
	}
	else if(isNaN(document.Frmcompany.txtCredit.value)){
    alert("Please enter numeric values.")
    document.Frmcompany.txtCredit.focus();
	return false;
  }
 }	   
	trimSpace(document.Frmcompany.txtTransactionFee);
  if(document.Frmcompany.txtTransactionFee.value==""){
    alert("Please enter Transaction Fee")
    document.Frmcompany.txtTransactionFee.focus();
	return false;
  }
  else if (document.Frmcompany.txtTransactionFee.value != "")
  {
  	if (document.Frmcompany.txtTransactionFee.value.charAt(0)=='$')
	{
		if (isNaN(document.Frmcompany.txtTransactionFee.value.substr(1,document.Frmcompany.txtTransactionFee.length)))
		{
			 alert("Please enter numeric values.");
			 document.Frmcompany.txtTransactionFee.focus();
			 return false;
		}
		else
		{
		    document.Frmcompany.txtTransactionFee.value = document.Frmcompany.txtTransactionFee.value.substr(1,document.Frmcompany.txtTransactionFee.length);		
		}	
	}
	else if(isNaN(document.Frmcompany.txtTransactionFee.value)){
    alert("Please enter numeric values.")
    document.Frmcompany.txtTransactionFee.focus();
	return false;
  }
 }	   	
 trimSpace(document.Frmcompany.txtDiscountRate);
  if(document.Frmcompany.txtDiscountRate.value==""){
    alert("Please enter Discount Rate")
    document.Frmcompany.txtDiscountRate.focus();
	return false;
  } 
  else if (document.Frmcompany.txtDiscountRate.value != "")
  {
   
    if (document.Frmcompany.txtDiscountRate.value.charAt(document.Frmcompany.txtDiscountRate.value.length-1)=='%')
	{
	  		if (isNaN(document.Frmcompany.txtDiscountRate.value.substr(0,document.Frmcompany.txtDiscountRate.value.length-1)))
		{
			alert("Please enter numeric values")
		    document.Frmcompany.txtDiscountRate.focus();
			return false;
		}
		else
		{
			document.Frmcompany.txtDiscountRate.value = document.Frmcompany.txtDiscountRate.value.substr(0,document.Frmcompany.txtDiscountRate.value.length-1);
        }
	}
		
   else if(isNaN(document.Frmcompany.txtDiscountRate.value)){
    alert("Please enter numeric values")
    document.Frmcompany.txtDiscountRate.focus();
	return false;
  } 
  }
trimSpace(document.Frmcompany.txtReserve);
 if(document.Frmcompany.txtReserve.value==""){
    alert("Please enter Reserve value")
    document.Frmcompany.txtReserve.focus();
	return false;
  }  else if (document.Frmcompany.txtReserve.value != "")  {
    if (document.Frmcompany.txtReserve.value.charAt(document.Frmcompany.txtReserve.value.length-1)=='%')
	{
	  		if (isNaN(document.Frmcompany.txtReserve.value.substr(0,document.Frmcompany.txtReserve.value.length-1)))
		{
			alert("Please enter numeric values")
		    document.Frmcompany.txtReserve.focus();
			return false;
		}
		else
		{
			document.Frmcompany.txtReserve.value = document.Frmcompany.txtReserve.value.substr(0,document.Frmcompany.txtReserve.value.length-1);
        }
	}	
	else if(isNaN(document.Frmcompany.txtReserve.value)){
		alert("Please enter numeric values")
		document.Frmcompany.txtReserve.focus();
		return false;
	} 
  }
  trimSpace(document.Frmcompany.txtBillingdescriptor);
	if(document.Frmcompany.txtBillingdescriptor.value==""){
		alert("Please enter the billing descriptor.")
		document.Frmcompany.txtBillingdescriptor.focus();
		return false;
	} 
	trimSpace(document.Frmcompany.email);
	if(document.Frmcompany.email.value==""){
		alert("Please enter email")
		document.Frmcompany.email.focus();
		return false;
	}
	
	 if (document.Frmcompany.email.value  != "") 
	{
		if (document.Frmcompany.email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.email.focus();
			return(false);
		}
	}
			
	if (document.Frmcompany.email.value  != "") 
	{
		if (document.Frmcompany.email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.email.focus();
			return(false);
		}
	}
		
	if (document.Frmcompany.email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.Frmcompany.email.focus();
		return(false);
	}
	
	
	

  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
		trimSpace(document.Frmcompany.txtMerchantName);
		if(document.Frmcompany.txtMerchantName.value==""){
			alert("Please enter Merchant Name")
			document.Frmcompany.txtMerchantName.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtTollFreeNumber);
		if(document.Frmcompany.txtTollFreeNumber.value==""){
			alert("Please enter Toll free number Name")
			document.Frmcompany.txtTollFreeNumber.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtRetrievalNumber);
		if(document.Frmcompany.txtRetrievalNumber.value==""){
			alert("Please enter retrieval number Name")
			document.Frmcompany.txtRetrievalNumber.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtSecurityNumber);
		if(document.Frmcompany.txtSecurityNumber.value==""){
			alert("Please enter security number Name")
			document.Frmcompany.txtSecurityNumber.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtProcessor);
		if(document.Frmcompany.txtProcessor.value==""){
			alert("Please enter processor")
			document.Frmcompany.txtProcessor.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtPackagename);
		if(document.Frmcompany.txtPackagename.value==""){
			alert("Please enter the package name.")
			document.Frmcompany.txtPackagename.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtPackageProduct);
		if(document.Frmcompany.txtPackageProduct.value==""){
			alert("Please enter the package product.")
			document.Frmcompany.txtPackageProduct.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtPackagePrice);
		if(document.Frmcompany.txtPackagePrice.value==""){
			alert("Please enter the package price.")
			document.Frmcompany.txtPackagePrice.focus();
			return false;
		}else if(isNaN(document.Frmcompany.txtPackagePrice.value)){
			alert("Please enter the numeric value.")
			document.Frmcompany.txtPackagePrice.focus();
			return false;
		}else if(document.Frmcompany.txtPackagePrice.value <= 0){
			alert("Please enter a valid price.")
			document.Frmcompany.txtPackagePrice.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtRefundPolicy);
		if(document.Frmcompany.txtRefundPolicy.value==""){
			alert("Please enter the refund policy.")
			document.Frmcompany.txtRefundPolicy.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtDescription);
		if(document.Frmcompany.txtDescription.value==""){
			alert("Please enter the description")
			document.Frmcompany.txtDescription.focus();
			return false;
		}
		trimSpace(document.Frmcompany.txtVoicefee);	
		  if(document.Frmcompany.txtVoicefee.value==""){
			alert("Please enter Voice Authorization Fee")
			document.Frmcompany.txtVoicefee.focus();
			return false;
		  }
		   else if (document.Frmcompany.txtVoicefee.value != "")
		  {
			if (document.Frmcompany.txtVoicefee.value.charAt(0)=='$')
			{
				if (isNaN(document.Frmcompany.txtVoicefee.value.substr(1,document.Frmcompany.txtVoicefee.length)))
				{
					 alert("Please enter numeric values.");
					 document.Frmcompany.txtVoicefee.focus();
					 return false;
				}
				else
				{
					document.Frmcompany.txtVoicefee.value = document.Frmcompany.txtVoicefee.value.substr(1,document.Frmcompany.txtVoicefee.length);		
				}	
			}
			else if(isNaN(document.Frmcompany.txtVoicefee.value)){
			alert("Please enter numeric values.")
			document.Frmcompany.txtVoicefee.focus();
			return false;
		  }
		 } 
	}else {
		trimSpace(document.Frmcompany.txtMerchantName);
		trimSpace(document.Frmcompany.txtTollFreeNumber);
		trimSpace(document.Frmcompany.txtRetrievalNumber);
		trimSpace(document.Frmcompany.txtSecurityNumber);
		trimSpace(document.Frmcompany.txtProcessor);
		trimSpace(document.Frmcompany.shipping_time_frame);
		trimSpace(document.Frmcompany.time_frame);
		trimSpace(document.Frmcompany.txtPackagename);
		trimSpace(document.Frmcompany.txtPackageProduct);
		trimSpace(document.Frmcompany.txtPackagePrice);
		trimSpace(document.Frmcompany.txtRefundPolicy);
		trimSpace(document.Frmcompany.txtDescription);
		
		document.Frmcompany.txtMerchantName.value="";
		document.Frmcompany.txtTollFreeNumber.value="";
		document.Frmcompany.txtRetrievalNumber.value="";
		document.Frmcompany.txtSecurityNumber.value="";
		document.Frmcompany.txtProcessor.value="";
		document.Frmcompany.chk_auto_approve.checked = false;
		document.Frmcompany.chk_shipping_cancel.checked = false;
		document.Frmcompany.chk_auto_cancel.checked = false;
		document.Frmcompany.shipping_time_frame.value ="";
		document.Frmcompany.time_frame.value="";
		document.Frmcompany.txtPackagename.value="";
		document.Frmcompany.txtPackageProduct.value="";
		document.Frmcompany.txtPackagePrice.value="";
		document.Frmcompany.txtRefundPolicy.value="";
		document.Frmcompany.txtDescription.value="";
	}
	trimSpace(document.Frmcompany.first_name);
	if(document.Frmcompany.first_name.value == "") {
		alert("Please enter the first name.")
		document.Frmcompany.first_name.focus();
		return false;
	}
	trimSpace(document.Frmcompany.family_name);
	if(document.Frmcompany.family_name.value == "") {
		alert("Please enter the family name.")
		document.Frmcompany.family_name.focus();
		return false;
	}
	trimSpace(document.Frmcompany.job_title);
	if(document.Frmcompany.job_title.value == "") {
		alert("Please enter the job_title.")
		document.Frmcompany.job_title.focus();
		return false;
	}
	trimSpace(document.Frmcompany.contact_email);
	if(document.Frmcompany.contact_email.value == "") {
		alert("Please enter the contact email address.")
		document.Frmcompany.contact_email.focus();
		return false;
	}
	
	 if (document.Frmcompany.contact_email.value  != "") 
	{
		if (document.Frmcompany.contact_email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.contact_email.focus();
			return(false);
		}
	}
			
	if (document.Frmcompany.contact_email.value  != "") 
	{
		if (document.Frmcompany.contact_email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmcompany.contact_email.focus();
			return(false);
		}
	}
		
	if (document.Frmcompany.contact_email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.Frmcompany.contact_email.focus();
		return(false);
	}
	
	
	
	
	
	
	if(document.Frmcompany.confirm_contact_email.value != document.Frmcompany.contact_email.value) {
		alert("Contact email ids do not match.")
		document.Frmcompany.confirm_contact_email.focus();
		return false;
	}
	trimSpace(document.Frmcompany.contact_phone);
	if(document.Frmcompany.contact_phone.value == "") {
		alert("Please enter the telephone number.")
		document.Frmcompany.contact_phone.focus();
		return false;
	}
	trimSpace(document.Frmcompany.how_about_us);
	if(document.Frmcompany.how_about_us.value == "") {
		alert("Please select where you heard about us.")
		document.Frmcompany.how_about_us.focus();
		return false;
	}

}
function SelectMerchanttype() {
	if(document.Frmcompany.how_about_us.value=='rsel' || document.Frmcompany.how_about_us.value=='other') {
		document.Frmcompany.reseller_other.disabled=false;
	}else {
		document.Frmcompany.reseller_other.disabled=true;
	}
}
function func_vali_pass(frmelement)
{
 var invalid="!`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
 var inp=frmelement.value;
 var b_flag=true;
for(var i=0;((i<inp.length)&&b_flag);i++)
{
var temp= inp.charAt(i);
var j=invalid.indexOf(temp);
if(j!=-1)
{

b_flag =false;
return false;
}
}

if (b_flag==true)return true;

}


function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}
</script>
<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr> 
    <td width="100%" valign="top" align="center"  > &nbsp; <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="43%" background="../images/menucenterbg.gif" ><span class="whitehd">Add&nbsp; 
            Company </span></td>
          <td height="22" align="left" valign="top" width="10%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr> 
          <td class="lgnbd" colspan="5"> <form action="companyAdd.php"  name="Frmcompany" method="post" onSubmit="return validateForm()">
              <?	
			/*if($showval[7]=="") 
			{
				$state=str_replace("\n",",\t",$showval[12]);
			} 
			else 
			{
				$state=str_replace("\n",",\t",$showval[7]);
			}
			if($showval[27] == "tele") {
				$script_display ="yes";
			}else {
				$script_display ="none";
			}*/
		 ?>
              <input type="hidden" name="userid" value="<?=$showval[0]?>">
              <table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
                <tr> 
                  <td align="center" width="50%" valign="top" height="600"> <table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center"  height="600">
                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="250" ><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr height='30'> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b> 
                          &nbsp;Company Name</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'> 
                          &nbsp;
                          <input type="text" name="companyname" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;User 
                          Name</b></font></td>
                        <td height="30" align='left' class='cl1'> &nbsp;
                          <input type="text" name="username" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Password</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <input type="text" name="password" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Confirm 
                          Password</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <input type="text" name="password1" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Address</b></font></td>
                        <td height="30" align='left' class='cl1'> &nbsp;
                          <input type="text" name="address" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;City</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <input type="text" name="city" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Country</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <select name="country"  style="font-family:arial;font-size:10px;width:150px" onChange="return validator()">
							<?=func_get_country_select($showval[8],1) ?>
                          </select> 
						  <script language="javascript">
					     	document.Frmcompany.country.value='<?=$showval[8]?>';	
						  </script> 
                          <!--<script language="javascript">
							 document.Frmcompany.country.value='<?=$showval[8]?>';	
						</script>-->
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;State</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <select name="state"  style="font-family:arial;font-size:10px;width:150px">
							<?=func_get_state_select($showval[7]) ?>
						  </select> 

                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Other 
                          State</b></font></td>
                        <td height="30" align='left' class='cl1'> &nbsp;
                          <input type="text" name="ostate"  class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""></td>
                      </tr>
                      <script language="javascript">
						if(document.Frmcompany.country.value !="" ) {
							if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
								document.Frmcompany.ostate.disabled= true;
								document.Frmcompany.ostate.value= "";
								document.Frmcompany.state.disabled = false;
							} else {
								document.Frmcompany.state.disabled = true;
								document.Frmcompany.state.value= "";
								document.Frmcompany.ostate.disabled= false;
							}
						} else {
							document.Frmcompany.country.value = "United States";
						}
						</script>
                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Zipcode</b></font></td>
                        <td height="30" align='left'  class='cl1'> &nbsp;
                          <input type="text" name="zipcode" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Fax 
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' > &nbsp;
                          <input type="text" maxlength="25" name="faxnumber" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Phone 
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' > &nbsp;
                          <input type="text" maxlength="25" name="phonenumber" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Type 
                          Of Company</strong> &nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <select name="company_type"  style="font-family:arial;font-size:10px;width:160px" >
                            <option value="">--Choose one --</option>
                            <option value="part">Limited Partnership</option>
                            <option value="ltd">Limited Liability Company</option>
                            <option value="corp">Corporation</option>
                            <option value="sole">Sole Proprietor</option>
                            <option value="other">Other</option>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;If 
                          'Other', please specify:</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="other_company_type" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Customer 
                          services phone number</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="customerservice_phone" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
                          Active</font></strong></td>
                        <td height="30" class='cl1'> <input name="rad_trans_activity" type="checkbox" value="1"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Billing 
                          Descriptor Name</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="txtBillingdescriptor" class="normaltext" style="width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Set 
                          up fees paid</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;
                          <input type="text" name="txtSetupFee" class="normaltext" style="width:75px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
                          Type </font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <select name="rad_trans_type" style="font-family:arial;font-size:10px;width:100px" onChange="displayverification();">
                            <?php						print func_select_merchant_type("ecom"); ?>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td width="100%" colspan="2"> <div id="auto_cancel" style="display:yes"> 
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Voice 
                                  Authorization Fee - $</font></strong></td>
                                <td height="30" class='cl1'> &nbsp;
                                  <input type="text" name="txtVoicefee" class="normaltext" style="width:100px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Customer 
                                  Service Cancel(auto)</font></strong></td>
                                <td align="left" height="25" class='cl1' width="274"><input name="chk_auto_cancel" type="checkbox" value="Y">
                                  &nbsp;<font face="verdana" size="1">Timeframe 
                                  in Days</font>&nbsp;&nbsp;
                                  <input type="text" name="time_frame" class="normaltext" size="2" value="" style="font-family:arial;font-size:10px"> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Shipping 
                                  Cancel(auto)</font></strong></td>
                                <td align="left" height="30" width="191" class='cl1'><input name="chk_shipping_cancel" type="checkbox" value="Y">
                                  &nbsp;<font face="verdana" size="1">Timeframe 
                                  in Days</font>&nbsp;&nbsp;
                                  <input type="text" name="shipping_time_frame" size="2" value="" style="font-family:arial;font-size:10px"> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Auto 
                                  Approve Pass Orders&nbsp;</font></strong></td>
                                <td align="left" height="25" class='cl1'><input name="chk_auto_approve" type="checkbox" value="Y"> 
                                </td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Expected 
                          Monthly Volume ($)&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <select name="volume" style="font-family:arial;font-size:10px;width:120px">
                            <?php						func_select_merchant_volume(''); ?>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Average 
                          Ticket&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" name="avgticket" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Charge 
                          Back %&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" name="chargeper" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Previous 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'><input name="prepro" type="checkbox" value="Yes"> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Recurring 
                          Billing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> <input name="rebill" type="checkbox" value="Yes"> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Currently 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> <input name="currpro" type="checkbox" value="Yes"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Bank 
                          Processing Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><strong><font face="verdana" size="1">With 
                          which bank do you hold a company account?</font></strong></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <select name="currentBank" style="font-family:verdana;font-size:10px;width:270px" >
					<?=func_get_bank_select($showval[55])?>
                          </select> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left" class='cl1' ><font face="verdana" size="1"><strong>If 
                          'Other', please specify</strong></font></td>
                        <td height="30" align="left" class='cl1' >&nbsp;
                          <input type="text" name="bank_other" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Beneficiary 
                          Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="beneficiary_name" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Name 
                          On Bank Account</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="bank_account_name" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text"  name="bank_address" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          country</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <select name="bank_country"  style="font-family:verdana;font-size:10px;width:170px">
                            <option value="">---------- Please select -----------</option>
                            <script language="javascript">
						 showCountries();	
					</script>
                          </select> 
                          <!--<script language="javascript">
						 document.Frmcompany.bank_country.value='<?=$showval[58]?>';	
					</script>-->
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          telephone number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="bank_phone" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          sort code / Branch Code</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="bank_sort_code" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Account number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="bank_account_number" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Swift Code</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;
                          <input type="text" name="bank_swift_code" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                    </table></td>
                  <td align="center" width="50%" valign="top"  height="600"> <table  width="100%"  height="600" class='lefttopright' cellpadding='0' cellspacing='0' valign="center" style='margin-top: 15; margin-bottom: 5'>
                      <tr> 
                        <td height="30" align='left' class='cl1' width="250" ><font face='verdana' size='1'><b>&nbsp;Suspend 
                          User?</b></font></td>
                        <td height="30" class='cl1' align='left'><input type="checkbox" name="suspend" class="normaltext" value="YES"> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Unsubscribe 
                          from mailing list?</b></font></td>
                        <td height="30" align='left' class='cl1'><input type="checkbox" name="chk_unsubscribe" class="normaltext" value="0"></td>
                      </tr>
                      <tr> 
                        <td width="100%" colspan="2"> <div id="auto_sendecommerce" style="display:none"> 
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                              <tr> 
                                <td height="30" class='cl1' width="286" ><font face='verdana' size='1'><b>&nbsp;Send 
                                  Ecommerce Letter?</b></font></td>
                                <td height="30" class='cl1'> <input type="checkbox" name="chk_sendecommerce" class="normaltext" value="1"></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr> 
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
                          & Fees</strong>&nbsp;</font></td>
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge 
                          Back - $</font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <input type="text" name="txtChargeBack" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Credit 
                          $ </font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <input type="text" name="txtCredit" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
                          Fee - $</font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <input type="text" name="txtTransactionFee" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Discount 
                          Rate - %</font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <input type="text" name="txtDiscountRate" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve 
                          - %</font></strong></td>
                        <td height="30" class='cl1'> &nbsp;
                          <input type="text" name="txtReserve" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web 
                          Site Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Email&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>&nbsp;
                          <input type="text" name="email" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL1&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" maxlength="100" name="url1" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL2&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" maxlength="100" name="url2" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL3&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" maxlength="100" name="url3" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
					  <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL4&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" maxlength="100" name="url4" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
					  <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL5&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'> &nbsp;
                          <input type="text" maxlength="100" name="url5" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
					  
                      <tr> 
                        <td width="100%" colspan="2"> <div id="script" style="display:yes"> 
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                              <tr> 
                                <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="286"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter 
                                  template setup</strong>&nbsp;</font></td>
                                <td height="30" class='cl1' align="left">&nbsp;</td>
                              </tr>
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
                                  Name</font></strong></td>
                                <td height="30" class='cl1'> &nbsp;
                                  <input type="text" name="txtMerchantName" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Toll 
                                  Free Number</font></strong></td>
                                <td height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtTollFreeNumber" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Retrieval 
                                  Number </font></strong></td>
                                <td height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtRetrievalNumber" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Security 
                                  Number </font></strong></td>
                                <td height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtSecurityNumber" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processor</font></strong></td>
                                <td height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtProcessor" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Verification 
                                  Script </strong>&nbsp;</font></td>
                                <td height="30" align="left" class='cl1'>&nbsp;</td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
                                  Name &nbsp;</font></strong></td>
                                <td align="left" height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtPackagename" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
                                  Product Service &nbsp;</font></strong></td>
                                <td align="left" height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtPackageProduct" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
                                  Price &nbsp;</font></strong></td>
                                <td align="left" height="30" class='cl1'>&nbsp;
                                  <input type="text" name="txtPackagePrice" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Refund 
                                  Policy &nbsp;</font></strong></td>
                                <td align="left" height="30" class='cl1'>&nbsp;
                                  <textarea name="txtRefundPolicy" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" rows="4" cols="30"></textarea> 
                                </td>
                              </tr>
                              <tr> 
                                <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Description 
                                  &nbsp;</font></strong></td>
                                <td align="left" height="30" class='cl1'>&nbsp;
                                  <textarea name="txtDescription" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" rows="4" cols="30"></textarea>	
                                </td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>User 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;Your First Name</strong></font></td>
                        <td height="30" align="left"   class='cl1' > &nbsp;
                          <select name="cboTitle" style="font-family:verdana;font-size:10px;width:50px">
                            <?php 
							funcFillComboWithTitle ( "" ); 
						?>
                          </select>
                          &nbsp; &nbsp;
                          <input type="text" name="first_name" class="normaltext" style="width:100px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;Your Last Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="family_name" class="normaltext" style="width:158px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Date 
                          of birth</strong></font></td>
                        <td height="30" align="left"  class='cl1' > 
                          <?php
							/*$iYear = "";
							$iMonth = "";
							$iDay = "";
							if ($showval[70] !=""){
								list($iYear,$iMonth,$iDay) = split("-",$showval[70]);
							}*/
							print("&nbsp;");
							funcFillDate ( "","","" );
						?>
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Sex</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <select name="cboSex" style="font-family:verdana;font-size:10px;width:70px">
                            <option value='Male'>Male</option>
                            <option value='Female'>Female</option>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Address</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <textarea name="txtAddress" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" rows="4" cols="30"></textarea> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Zipcode</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="txtPostCode"  maxlength="7" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;What is your job title or position?</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="job_title" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Contact 
                          email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="contact_email" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Please 
                          confirm email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="confirm_contact_email" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Telephone 
                          number</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="contact_phone" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Residence 
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="residence_telephone" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Fax 
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="fax" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Where 
                          did you hear about <?=$_SESSION['gw_title']?></strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <select name="how_about_us" style="font-family:verdana;font-size:10px;width:120px" onChange="SelectMerchanttype();">
                            <?= func_fill_info_source_combo($cnn_cs, "") ?>
                          </select> </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Reseller/Other 
                          Details</strong></font></td>
                        <td height="30" align="left"  class='cl1' > &nbsp;
                          <input type="text" name="reseller_other" class="normaltext" style="font-family:verdana;font-size:10px;width:150px" value=""> 
                        </td>
                      </tr>
                    </table></td>
                </tr>
              </table>
              <center>
                <table align="center">
                  <tr>
                    <td align="center" valign="center" height="30" colspan="2" ><input name="image" type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/addCompany.jpg"></td>
                  </tr>
                </table>
              </center>
            </form></td>
        </tr>
        <tr> 
          <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
          <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
          <td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
        </tr>
      </table>
      <br> </td>
  </tr>
</table>
<script>
if(document.Frmcompany.how_about_us.value !="rsel" || document.Frmcompany.how_about_us.value !="other" ){
	document.Frmcompany.reseller_other.disabled=false;
} else {
	document.Frmcompany.reseller_other.disabled=true;
}
</script>

<?php
include("includes/footer.php");
}
?>