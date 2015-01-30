<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// companyAdd.php:	The admin page functions for the New Company setup. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");
include("includes/message.php");

include("includes/mailbody_replytemplate.php"); //for getting the reply mail content
$backhref="useraccount.php";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$company = (isset($HTTP_POST_VARS['company'])?quote_smart($HTTP_POST_VARS['company']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
	$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
	$address = (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
	$city = (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
	$state = (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
	$ostate = (isset($HTTP_POST_VARS['ostate'])?quote_smart($HTTP_POST_VARS['ostate']):"");
	$country = (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
	$zipcode = (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");
	
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
	$url2 = (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
	$url3 = (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
	
	$strMerchantName = (isset($HTTP_POST_VARS['txtMerchantName'])?quote_smart($HTTP_POST_VARS['txtMerchantName']):"");
	$strTollFreeNumber = (isset($HTTP_POST_VARS['txtTollFreeNumber'])?quote_smart($HTTP_POST_VARS['txtTollFreeNumber']):"");
	$strRetrievalNumber = (isset($HTTP_POST_VARS['txtRetrievalNumber'])?quote_smart($HTTP_POST_VARS['txtRetrievalNumber']):"");
	$strSecurityNumber = (isset($HTTP_POST_VARS['txtSecurityNumber'])?quote_smart($HTTP_POST_VARS['txtSecurityNumber']):"");
	$strProcessor = (isset($HTTP_POST_VARS['txtProcessor'])?quote_smart($HTTP_POST_VARS['txtProcessor']):"");
	$strtxtBillingdescriptor = (isset($HTTP_POST_VARS['txtBillingdescriptor'])?quote_smart($HTTP_POST_VARS['txtBillingdescriptor']):"");
	
	$strChargeBack = (isset($HTTP_POST_VARS['txtChargeBack'])?quote_smart($HTTP_POST_VARS['txtChargeBack']):"");
	$strCredit =   (isset($HTTP_POST_VARS['txtCredit'])?quote_smart($HTTP_POST_VARS['txtCredit']):"");
	$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"");
	$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoiceauthFee'])?quote_smart($HTTP_POST_VARS['txtVoiceauthFee']):"");
	$strDiscountRate  = (isset($HTTP_POST_VARS['txtDiscountRate'])?quote_smart($HTTP_POST_VARS['txtDiscountRate']):"");
	$strReserve  = (isset($HTTP_POST_VARS['txtReserve'])?quote_smart($HTTP_POST_VARS['txtReserve']):"");
	
	$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"");
	$transaction_type = (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");

	$strAutoCancel  = (isset($HTTP_POST_VARS['chk_auto_cancel'])?quote_smart($HTTP_POST_VARS['chk_auto_cancel']):"N");
	$iTimeFrame  = (isset($HTTP_POST_VARS['time_frame'])?quote_smart($HTTP_POST_VARS['time_frame']):"-1");
	$strShippingCancel  = (isset($HTTP_POST_VARS['chk_shipping_cancel'])?quote_smart($HTTP_POST_VARS['chk_shipping_cancel']):"N");
	$iShippingTimeFrame  = (isset($HTTP_POST_VARS['shipping_time_frame'])?quote_smart($HTTP_POST_VARS['shipping_time_frame']):"-1");
	$strAutoApprove  = (isset($HTTP_POST_VARS['chk_auto_approve'])?quote_smart($HTTP_POST_VARS['chk_auto_approve']):"N");

	$strDescription  = (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"N");
	$strRefundPolicy  = (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"N");
	$strPackagePrice  = (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"");
	$strPackageProduct  = (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"N");
	$strPackagename  = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"N");

	$str_current_date_time = func_get_current_date_time();

	if($iShippingTimeFrame == "")
		$iShippingTimeFrame = "-1";
	
	if($iTimeFrame == "")
		$iTimeFrame = "-1";
	if($strPackagePrice=="")
		$strPackagePrice=0;
	if($company)
	{
		$qry_select_user = "select username  from cs_companydetails where (username='$username' or companyname='$companyname') and email='$email'";
	//	print $qry_select_user."<br>";
		if(!($show_sql = mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		elseif(mysql_num_rows($show_sql) >0) 
		{
            $invalidlogin="<br><font face='verdana' size='1' color='red'>Existing username !! </font>";
		}
        else
		{
			if($state=="- - -Select- - -") 
			{
				$state=null;
			}
			$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,phonenumber,address,city,state,";
			$qry_insert_user .= " ostate,country,zipcode,email,merchantName,tollFreeNumber,retrievalNumber,securityNumber,processor,";
			$qry_insert_user .= " chargeback,credit,discountrate,transactionfee,reserve,voiceauthfee,auto_cancel,time_frame,auto_approve,transaction_type,activeuser,shipping_cancel,shipping_timeframe,";
			$qry_insert_user .= " telepackagename,telepackageprod,telepackageprice,telerefundpolicy,teledescription,url1,url2,url3,date_added,billingdescriptor)";
			$qry_insert_user .= " values('$username','$password','$companyname','$phonenumber','$address','$city',";
			$qry_insert_user .= "'$state','$ostate','$country','$zipcode','$email','$strMerchantName','$strTollFreeNumber','$strRetrievalNumber',";
			$qry_insert_user .= "'$strSecurityNumber','$strProcessor',$strChargeBack,$strCredit,$strDiscountRate,$strTransactionFee,$strReserve,$strVoiceauthFee,'$strAutoCancel',$iTimeFrame,'$strAutoApprove','$transaction_type',$trans_activity,'$strShippingCancel',$iShippingTimeFrame,";
			$qry_insert_user .= "'$strPackagename','$strPackageProduct',$strPackagePrice,'$strRefundPolicy','$strDescription','$url1','$url2','$url3','$str_current_date_time','$strtxtBillingdescriptor')";
		//	print $qry_insert_user."<br>";
			if(!($show_sql =mysql_query($qry_insert_user)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query <br>");
				exit();
			}
			else 
			{
				/************** to sent registration mail to the  company*********/
				$qry_select_sent = "Select mail_id,mail_sent from cs_registrationmail";
				$rst_select_sent = mysql_query($qry_select_sent,$cnn_cs);
				if (mysql_num_rows($rst_select_sent)>0)
				{
					$mail_sent = mysql_result($rst_select_sent,0,1);
				}
				if($mail_sent==1)
				{
					$arrFiles[0] = "";
					$arrFileNames[0] = "";
					$email_from = "sales@etelegate.com";
					$email_to = $email;
					$email_subject = "Registration Confirmation";
					$email_message = func_getreplymailbody($companyname,$username,$password);
					if(!sendMail($email_from,$email_subject,$email_message,$email_to,$arrFiles,$arrFileNames))
					{
						print "Sorry! Some of the mails was not able to send";
						exit();				
					}
				}
				/***************************************************************/
				$username="An email has been sent with your offshore merchant account infomation";		
				$outhtml="y";
				message($username,$outhtml,$headerInclude);					
				exit();
			}
				
		}		     
	}
?>
<script language="javascript">
function validation(){
   if(document.Frmcompany.companyname.value==""){
    alert("Please enter company name")
    document.Frmcompany.companyname.focus();
	return false;
  }
 if(document.Frmcompany.username.value==""){
    alert("Please enter username")
    document.Frmcompany.username.focus();
	return false;
  }
  if(document.Frmcompany.password.value==""){
    alert("Please enter correct password")
    document.Frmcompany.password.focus();
	return false;
  }
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
  if(document.Frmcompany.address.value==""){
    alert("Please enter address")
    document.Frmcompany.address.focus();
	return false;
  }
  if(document.Frmcompany.city.value==""){
    alert("Please enter city")
    document.Frmcompany.city.focus();
	return false;
  }
  if(document.Frmcompany.zipcode.value==""){
    alert("Please enter zipcode")
    document.Frmcompany.zipcode.focus();
	return false;
  }
  if(document.Frmcompany.phonenumber.value==""){
    alert("Please enter phone number")
    document.Frmcompany.phonenumber.focus();
	return false;
  }
   if(document.Frmcompany.email.value==""){
    alert("Please enter email")
    document.Frmcompany.email.focus();
	return false;
  }
  if(document.Frmcompany.txtMerchantName.value==""){
    alert("Please enter Merchant Name")
    document.Frmcompany.txtMerchantName.focus();
	return false;
  }
  if(document.Frmcompany.txtTollFreeNumber.value==""){
    alert("Please enter Toll free number Name")
    document.Frmcompany.txtTollFreeNumber.focus();
	return false;
  }
  if(document.Frmcompany.txtRetrievalNumber.value==""){
    alert("Please enter retrieval number Name")
    document.Frmcompany.txtRetrievalNumber.focus();
	return false;
  }
  if(document.Frmcompany.txtSecurityNumber.value==""){
    alert("Please enter security number Name")
    document.Frmcompany.txtSecurityNumber.focus();
	return false;
  }
  if(document.Frmcompany.txtProcessor.value==""){
    alert("Please enter processor")
    document.Frmcompany.txtProcessor.focus();
	return false;
  }   
  if(document.Frmcompany.txtChargeBack.value==""){
    alert("Please enter charge back amount")
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
 
  if(document.Frmcompany.txtCredit.value==""){
    alert("Please enter credit amount")
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
	  
  if(document.Frmcompany.txtTransactionFee.value==""){
    alert("Please enter transaction fee")
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
	 
  if(document.Frmcompany.txtVoiceauthFee.value==""){
    alert("Please enter voice authorization fee")
    document.Frmcompany.txtVoiceauthFee.focus();
	return false;
  }
  else if (document.Frmcompany.txtVoiceauthFee.value != "")
  {
  	if (document.Frmcompany.txtVoiceauthFee.value.charAt(0)=='$')
	{
		if (isNaN(document.Frmcompany.txtVoiceauthFee.value.substr(1,document.Frmcompany.txtVoiceauthFee.length)))
		{
			 alert("Please enter numeric values.");
			 document.Frmcompany.txtVoiceauthFee.focus();
			 return false;
		}
		else
		{
		    document.Frmcompany.txtVoiceauthFee.value = document.Frmcompany.txtVoiceauthFee.value.substr(1,document.Frmcompany.txtVoiceauthFee.length);		
		}	
	}
	else if(isNaN(document.Frmcompany.txtVoiceauthFee.value)){
    alert("Please enter numeric values.")
    document.Frmcompany.txtVoiceauthFee.focus();
	return false;
  }
 }  
 
   if(document.Frmcompany.txtDiscountRate.value==""){
    alert("Please enter discount rate")
    document.Frmcompany.txtDiscountRate.focus();
	return false;
  } 
  
   if(document.Frmcompany.txtBillingdescriptor.value==""){
    alert("Please enter the billing descriptor.")
    document.Frmcompany.txtBillingdescriptor.focus();
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

  if(document.Frmcompany.txtReserve.value==""){
    alert("Please enter processor")
    document.Frmcompany.txtReserve.focus();
	return false;
  }
  
   else if (document.Frmcompany.txtReserve.value != "")
  {
   
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
 
  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="") {
  	alert("Please select the order type.");
	return false;
  }

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
  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
		if(document.Frmcompany.txtPackagename.value==""){
		alert("Please enter the package name.")
		document.Frmcompany.txtPackagename.focus();
		return false;
		}
		if(document.Frmcompany.txtPackageProduct.value==""){
		alert("Please enter the package product.")
		document.Frmcompany.txtPackageProduct.focus();
		return false;
		}
		if(document.Frmcompany.txtPackagePrice.value==""){
		alert("Please enter the package price.")
		document.Frmcompany.txtPackagePrice.focus();
		return false;
		}else if(isNaN(document.Frmcompany.txtPackagePrice.value)){
		alert("Please enter the numeric value.")
		document.Frmcompany.txtPackagePrice.focus();
		return false;
		}	
		if(document.Frmcompany.txtRefundPolicy.value==""){
		alert("Please enter the refund policy.")
		document.Frmcompany.txtRefundPolicy.focus();
		return false;
		}	
		if(document.Frmcompany.txtDescription.value==""){
		alert("Please enter the description")
		document.Frmcompany.txtDescription.focus();
		return false;
		}	
	}else {
		document.Frmcompany.txtPackagename.value="";
		document.Frmcompany.txtPackageProduct.value="";
		document.Frmcompany.txtPackagePrice.value="";
		document.Frmcompany.txtRefundPolicy.value="";
		document.Frmcompany.txtDescription.value="";
	}
  
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
	if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
	 	document.getElementById('script').style.display = "";
	}else {
	 	document.getElementById('script').style.display = "none";
	}
	return false;
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Add&nbsp; 
            Company</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
		<form action="companyAdd.php" method="post" onsubmit="return validation()" name="Frmcompany" >
  	<table height="100%" width="100%" cellspacing="0" cellpadding="0">
  	<tr>
  		<td  width="100%" valign="center" align="center">
		  <?php if(!isset($invalidlogin))
				{
					$invalidlogin = "";
				}
			?>	
			 <?=$invalidlogin?>
			        <table border="0" cellpadding="0"  height="100">
					<tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" align="left">&nbsp;</td>
						</tr>					  <tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Company 
                          Name &nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>	
		  			<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">User 
                          Name &nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" maxlength="30" name="username" style="font-family:arial;font-size:10px;width:240px" ></input></td></tr>
		  	<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Password 
                          &nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
           	<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Confirm 
                          Password &nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" maxlength="30" name="password1" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>		  
          	<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Address 
                          &nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" name="address" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
		 	<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">City 
                          &nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" name="city" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
			<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td>
<td align="left" height="30" width="225">
				<select name="country"  style="font-family:arial;font-size:10px;width:240px" onchange=" return validator()"> 
				<option>Afghanistan </option>
				<option>Albania </option>
				<option>Algeria </option>
				<option>Andorra </option>
				<option>Angola</option>
				<option>Antigua and Barbuda </option>
				<option>Argentina </option>
				<option>Armenia </option>
				<option>Australia </option>
				<option>Austria</option>
				<option>Azerbaijan </option>
				<option>Bahamas</option>
				<option>Bahrain </option>
				<option>Bangladesh </option>
				<option>Barbados </option>
				<option>Belarus </option>
				<option>Belgium </option>
				<option>Belize </option>
				<option>Benin </option>
				<option>Bhutan </option>
				<option>Bolivia </option>
				<option>Bosnia</option>
				<option>Botswana </option>
				<option>Brazil </option>
				<option>Brunei </option>
				<option>Bulgaria </option>
				<option>Burkina Faso </option>
				<option>Burundi </option>
				<option>Cambodia </option>
				<option>Cameroon </option>
				<option>Canada </option>
				<option>Cape Verde </option>
				<option>Central African </option>
				<option>Chad </option>
				<option>Chile </option>
				<option>China </option>
				<option>Colombia </option>
				<option>Comoros</option>
				<option>Congo </option>
				<option>Costa Rica   </option>
				<option>Croatia </option>
				<option>Cuba </option>
				<option>Cyprus  </option>
				<option>Czech Republic </option>
				<option>Côte d'Ivoire </option>
				<option>Denmark</option>
				<option>Djibouti</option>
				<option>Dominica</option>
				<option>Dominican Republic </option>
				<option>East Timor</option>
				<option>Ecuador</option>
				<option>Egypt </option>
				<option>El Salvador</option>
				<option>Equatorial Guinea</option>
				<option>Eritrea</option>
				<option>Estonia </option>
				<option>Ethiopia </option>
				<option>Fiji </option>
				<option>Finland </option>
				<option>France </option>
				<option>Gabon </option>
				<option>Gambia</option>
				<option>Georgia</option>
				<option>Germany </option>
				<option>Ghana </option>
				<option>Greece  </option>
				<option>Grenada </option>
				<option>Guatemala </option>
				<option>Guinea </option>
				<option>Guyana </option>
				<option>Haiti</option>
				<option>Honduras </option>
				<option>Hungary</option>
				<option>Iceland</option>
				<option>India </option>
				<option>Indonesia</option>
				<option>Iran </option>
				<option>Iraq </option>
				<option>Ireland </option>
				<option>Israel </option>
				<option>Italy </option>
				<option>Jamaica </option>
				<option>Japan </option>
				<option>Jordan </option>
				<option>Kazakhstan</option>
				<option>Kenya  </option>
				<option>Kiribati </option>
				<option>Korea</option>
				<option>Kuwait </option>
				<option>Kyrgyzstan </option>
				<option>Laos  </option>
				<option>Latvia  </option>
				<option>Lebanon </option>
				<option>Lesotho</option>
				<option>Liberia </option>
				<option>Libya </option>
				<option>Liechtenstein </option>
				<option>Lithuania </option>
				<option>Luxembourg </option>
				<option>Macedonia</option>
				<option>Madagascar </option>
				<option>Malawi </option>
				<option>Malaysia </option>
				<option>Maldives </option>
				<option>Mali </option>
				<option>Malta  </option>
				<option>Marshall Islands </option>
				<option>Mauritania  </option>
				<option>Mauritius  </option>
				<option>Mexico   </option>
				<option>Micronesia</option>
				<option>Moldova </option>
				<option>Monaco  </option>
				<option>Mongolia  </option>
				<option>Morocco </option>
				<option>Mozambique </option>
				<option>Myanmar </option>
				<option>Namibia  </option>
				<option>Nauru  </option>
				<option>Nepal </option>
				<option>Netherlands  </option>
				<option>New Zealand  </option>
				<option>Nicaragua </option>
				<option>Niger </option>
				<option>Nigeria </option>
				<option>Norway </option>
				<option>Oman </option>
				<option>Pakistan</option>
				<option>Palau </option>
				<option>Panama </option>
				<option>Papua New Guinea </option>
				<option>Paraguay  </option>
				<option>Peru   </option>
				<option>Philippines  </option>
				<option>Poland  </option>
				<option>Portugal   </option>
				<option>Qatar </option>
				<option>Romania  </option>
				<option>Russia </option>
				<option>Rwanda </option>
				<option>Saint Kitts </option>
				<option>Saint Lucia</option>
				<option>Saint Vincent </option>
				<option>Samoa  </option>
				<option>San Marino</option>
				<option>Sao Tome and Principe </option>
				<option>Saudi Arabia </option>
				<option>Senegal  </option>
				<option>Serbia and Montenegro </option>
				<option>Seychelles </option>
				<option>Sierra Leone </option>
				<option>Singapore  </option>
				<option>Slovakia </option>
				<option>Slovenia</option>
				<option>Solomon Islands </option>
				<option>Somalia  </option>
				<option>South Africa </option>
				<option>Spain  </option>
				<option>Sri Lanka </option>
				<option>Sudan  </option>
				<option>Suriname </option>
				<option>Swaziland </option>
				<option>Sweden </option>
				<option>Switzerland </option>
				<option>Syria </option>
				<option>Taiwan </option>
				<option>Tajikistan </option>
				<option>Tanzania </option>
				<option>Thailand </option>
				<option>Togo </option>
				<option>Tonga</option>
				<option>Trinidad and Tobago</option>
				<option>Tunisia  </option>
				<option>Turkey </option>
				<option>Turkmenistan </option>
				<option>Tuvalu </option>
				<option>Uganda </option>
				<option>Ukraine </option>
				<option>United Arab Emirates </option>
				<option>United Kingdom </option>
				<option selected>United States</option>
				<option>Uruguay </option>
				<option>Uzbekistan </option>
				<option>Vanuatu </option>
				<option>Vatican City </option>
				<option>Venezuela </option>
				<option>Vietnam</option>
				<option>Western Sahara </option>
				<option>Yemen </option>
				<option>Zambia </option>
				<option>Zimbabwe </option>
				</select>	
			</td></tr>
        	<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">State&nbsp;&nbsp;</font></td>
<td align="left" height="30" width="225">
				<select name="state" style="font-family:arial;font-size:10px;width:240px"><option>- - -Select- - -</option>
				<option>Alabama</option>
				<option> Alaska</option>
				<option> Arizona</option>
				<option> Arkansas</option>
				<option> California</option>
				<option> Colorado</option>
				<option> Connecticut</option>
				<option> Delaware</option>
				<option> Florida</option>
				<option> Georgia</option>
				<option> Hawaii</option>
				<option> Idaho  </option>
				<option> Illinois</option>
				<option> Indiana</option>
				<option> Iowa</option>
				<option> Kansas</option>
				<option> Kentucky </option>
				<option> Louisiana </option>
				<option> Maine</option>
				<option> Maryland</option>
				<option> Massachusetts</option>
				<option> Michigan</option>
				<option> Minnesota</option>
				<option> Mississippi</option>
				<option> Missouri</option>
				<option> Montana</option>
				<option> Nebraska</option>
				<option> Nevada</option>
				<option> New Hampshire</option>
				<option> New Jersey</option>
				<option> New Mexico</option>
				<option> New York</option>
				<option> North Carolina</option>
				<option> North Dakota</option>
				<option> Ohio</option>
				<option> Oklahoma </option>
				<option> Oregon</option>
				<option> Pennsylvania</option>
				<option> Rhode Island</option>
				<option> South Carolina</option>
				<option> South Dakota</option>
				<option> Tennessee</option>
				<option> Texas</option>
				<option> Utah</option>
				<option> Vermont</option>
				<option> Virginia</option>
				<option> Washington</option>
				<option> West Virginia</option>
				<option> Wisconsin</option>
				<option> Wyoming  </option>
				 </select>	
			</td></tr><input type="hidden" name="company" value="company"></input>
			<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Other 
                          State&nbsp;&nbsp;</font></td>
<td align="left" height="30" width="225"><input type="text" name="ostate" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
				<script language="javascript">
				if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
					document.Frmcompany.ostate.disabled= true;
					document.Frmcompany.ostate.value= "";
					document.Frmcompany.state.disabled = false;
				} else {
					document.Frmcompany.state.disabled = true;
					document.Frmcompany.state.value= "";
					document.Frmcompany.ostate.disabled= false;
				}
				</script>	
			<input type="hidden" name="company" value="company"></input>
			<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td>
				<td align="left" height="30" width="225"><input type="text" name="zipcode" style="font-family:arial;font-size:10px;width:140px"></input></td></tr>
					<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Phone 
                          Number &nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="25" name="phonenumber" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
<tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web 
                          Site Informations</strong>&nbsp;</font></td>
                        <td height="30" align="left">&nbsp;</td>
						</tr>						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Email 
                          &nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">URL&nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="100" name="url1" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">URL&nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="100" name="url2" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">URL&nbsp;</font></td>
					<td align="left" height="30" width="225"><input type="text" maxlength="100" name="url3" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
					  <tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter 
                          template setup</strong>&nbsp;</font></td>
                        <td height="30" align="left">&nbsp;</td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Merchant 
                          Name&nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtMerchantName" type="text" id="txtMerchantName" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Toll 
                          Free Number&nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtTollFreeNumber" type="text" id="txtTollFreeNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Retrieval 
                          Number&nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtRetrievalNumber" type="text" id="txtRetrievalNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Security 
                          Number&nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtSecurityNumber" type="text" id="txtSecurityNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Processor&nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtProcessor" type="text" id="txtProcessor" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Billing 
                          Descriptor &nbsp;</font></td>
						<td align="left" height="30" width="225">
                          <input name="txtBillingdescriptor" type="text" id="txtBillingdescriptor" style="font-family:arial;font-size:10px;width:240px"></td></tr>

						<tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
                          & Fees</strong>&nbsp;</font></td>
	                        <td width="225" height="30" align="left">&nbsp;</td>
						</tr>
			
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Charge 
                          Back $ &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtChargeBack" type="text" id="txtChargeBack" style="font-family:arial;font-size:10px;width:240px">
                        </td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Credit 
                          $ &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtCredit" type="text" id="txtCredit" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
					
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Transaction 
                          Fee $ &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtTransactionFee" type="text" id="txtTransactionFee" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Voice 
                          Authorization Fee $ &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtVoiceauthFee" type="text" id="txtVoiceauthFee" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Discount 
                          Rate % &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtDiscountRate" type="text" id="txtDiscountRate" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Reserve 
                          % &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtReserve" type="text" id="txtReserve" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						<tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process 
                          Informations </strong>&nbsp;</font></td>
	                        <td width="225" height="30" align="left">&nbsp;</td>
						</tr>						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Company 
                          Status&nbsp;&nbsp;</font></td>
						<td align="left" height="30" width="225"><font face="verdana" size="1">Active</font>
                          <input name="rad_trans_activity" type="radio" value="1" >
                          <font face="verdana" size="1">&nbsp;&nbsp;Non Active&nbsp;</font> 
                          <input name="rad_trans_activity" type="radio" value="0" checked></td>
						</tr>

						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Merchant 
                          Type&nbsp;&nbsp;</font></td>
						<td align="left" height="30" width="225"><select name="rad_trans_type" style="font-family:arial;font-size:10px;width:100px" onChange="displayverification();">
							<option value="">Select</option>
							<option value="ecom">General Ecommerce</option>
							<option value="trvl">Travel</option>
							<option value="phrm">Pharmacy</option>
							<option value="game">Gaming</option>
							<option value="adlt">Adult</option>
							<option value="tele">Telemarketing</option>
							<option value="pmtg">Gateway</option>
							<option value="crds">Card swipe</option>
						  </select></td>
<!--						<td align="left" height="30" width="225"><input name="rad_trans_type" type="radio" value="tele" onclick="displayverification('tele');" checked><font face="verdana" size="1">&nbsp;&nbsp;Ecommerce&nbsp;</font><input name="rad_trans_type" type="radio" value="ecom"  onclick="displayverification('ecom');" ><font face="verdana" size="1">&nbsp;&nbsp;Gateway&nbsp;</font><input name="rad_trans_type" type="radio" value="pmtg"  onclick="displayverification('pmtg');" ></td>
-->						</tr>

						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Customer 
                          Service Cancel&nbsp; (auto)&nbsp;&nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="chk_auto_cancel" type="checkbox" value="Y">&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;<input type="text" name="time_frame" size="2" style="font-family:arial;font-size:10px"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Shipping 
                          Cancel (auto) &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="chk_shipping_cancel" type="checkbox" value="Y">&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;<input type="text" name="shipping_time_frame" size="2" style="font-family:arial;font-size:10px"></td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Auto 
                          Approve Pass Orders&nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="chk_auto_approve" type="checkbox" value="Y"></td>
						</tr>
						<tr><td colspan="2" width="100%">
						<div id="script" style="display:none">
						<table width="100%"> 
						<tr> 
                        <td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Verification 
                                  Script </strong>&nbsp;</font></td>
	                        <td width="225" height="30" align="left">&nbsp;</td>
						</tr>						
						<tr>
                                <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package 
                                  Name &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtPackagename" type="text" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                                <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package 
                                  Product Service &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtPackageProduct" type="text" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                                <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package 
                                  Price &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtPackagePrice" type="text" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                                <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Refund 
                                  Policy &nbsp;</font></td>
						<td align="left" height="30" width="225"><input name="txtRefundPolicy" type="text" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                                <td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Description 
                                  &nbsp;</font></td>
						<td align="left" height="30" width="225"><textarea name="txtDescription" style="font-family:arial;font-size:10px;width:240px" rows="6"></textarea></td>
						</tr>
						
						</table>
						</div>
						</td></tr>
						<tr><td align="center" valign="center" height="30" colspan="2"><input type="image" id="addcompany" SRC="<?=$tmpl_dir?>/images/addCompany.jpg"></input></td></tr>
	  </table>
 	 </td></tr></table></form>
	 </td>
      </tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
    </table><br>
    </td>
     </tr>
</table>
<?php
include("includes/footer.php");
}
?>