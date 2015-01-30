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
// modifycompany.php:	The admin page functions for selecting the company for editing company profile. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
$company = (isset($HTTP_POST_VARS['company'])?quote_smart($HTTP_POST_VARS['company']):"");
$companyname = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");

$script_display ="";
	if($company!="")
	{
		$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
		$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
		$qry_select_user = "select username  from cs_companydetails where (username='$username' or companyname='$companyname') and userid<>$userid";
		if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		elseif(mysql_num_rows($show_sql) >0) 
		{
			$invalidlogin="<br><font face='verdana' size='1' color='red'>Existing username !! </font>";
			$companyname=$userid;
		}
		else 
		{
			$suspend = (isset($HTTP_POST_VARS['suspend'])?quote_smart($HTTP_POST_VARS['suspend']):"");
			  if($suspend !="") 
			  {
				$suspend= "YES";
			  } 
			  else 
			  {
				$suspend= "NO";
			  }
			$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
			$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
			$address = (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");;
			$city = (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
			$state = (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
			$ostate = (isset($HTTP_POST_VARS['ostate'])?quote_smart($HTTP_POST_VARS['ostate']):"");
			$country = (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
			$zipcode = (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");

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
			$strDiscountRate  = (isset($HTTP_POST_VARS['txtDiscountRate'])?quote_smart($HTTP_POST_VARS['txtDiscountRate']):"");
			$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"");
			$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoicefee'])?quote_smart($HTTP_POST_VARS['txtVoicefee']):"");
			$strReserve  = (isset($HTTP_POST_VARS['txtReserve'])?quote_smart($HTTP_POST_VARS['txtReserve']):"");
			$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"");
			$transaction_type = (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");
			$strAutoCancel = (isset($HTTP_POST_VARS['chk_auto_cancel'])?quote_smart($HTTP_POST_VARS['chk_auto_cancel']):"N");
			$iTimeFrame  = (isset($HTTP_POST_VARS['time_frame'])?quote_smart($HTTP_POST_VARS['time_frame']):"-1");
			$strShippingCancel  = (isset($HTTP_POST_VARS['chk_shipping_cancel'])?quote_smart($HTTP_POST_VARS['chk_shipping_cancel']):"N");
			$iShippingTimeFrame  = (isset($HTTP_POST_VARS['shipping_time_frame'])?quote_smart($HTTP_POST_VARS['shipping_time_frame']):"-1");
			$strAutoApprove  = (isset($HTTP_POST_VARS['chk_auto_approve'])?quote_smart($HTTP_POST_VARS['chk_auto_approve']):"N");
			$strUnsubscribe  = (isset($HTTP_POST_VARS['chk_unsubscribe'])?quote_smart($HTTP_POST_VARS['chk_unsubscribe']):"1");

			$strDescription  = (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"N");
			$strRefundPolicy  = (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"N");
			$strPackagePrice  = (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"");
			$strPackageProduct  = (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"N");
			$strPackagename  = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"N");
			if($strPackagePrice==""){
				$strPackagePrice=0;
			}
			if($iShippingTimeFrame == "")
				$iShippingTimeFrame = "-1";
		
			if($iTimeFrame == "")
				$iTimeFrame = "-1";
			
			$qry_update_user =  " update cs_companydetails set username='$username',password='$password',companyname='$companyname',";
			$qry_update_user .= " phonenumber='$phonenumber',address='$address', city='$city',state='$state',ostate='$ostate',";
			$qry_update_user .= " merchantName='$strMerchantName',tollFreeNumber='$strTollFreeNumber',retrievalNumber='$strRetrievalNumber',";
			$qry_update_user .= " securityNumber='$strSecurityNumber',processor='$strProcessor',";
			$qry_update_user .= " chargeback=$strChargeBack,credit=$strCredit,discountrate=$strDiscountRate,transactionfee=$strTransactionFee,reserve=$strReserve,voiceauthfee=$strVoiceauthFee,auto_cancel='$strAutoCancel',time_frame=$iTimeFrame,auto_approve='$strAutoApprove',transaction_type='$transaction_type',shipping_cancel='$strShippingCancel',shipping_timeframe=$iShippingTimeFrame,send_mail=$strUnsubscribe,"; 
			$qry_update_user .= " country='$country',email='$email',zipcode='$zipcode',suspenduser='$suspend',activeuser=$trans_activity,";
			$qry_update_user .= " telepackagename='$strPackagename',telepackageprod='$strPackageProduct ',telepackageprice=$strPackagePrice,telerefundpolicy='$strRefundPolicy',teledescription='$strDescription',";
			$qry_update_user .= " url1='$url1', url2='$url2',url3='$url3',billingdescriptor='$strtxtBillingdescriptor' where userid=$userid";
		//	print $qry_update_user."<br>";
			if(!($show_sql=mysql_query($qry_update_user)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}		
			else 
			{
				$msgtodisplay="user name ".$username." has been modified";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
			}
				
		  }
	}		     
	
	if(!$companyname){
		$msgtodisplay="Select a company name";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	}

	$qry_select_user = "select *  from cs_companydetails where userid=$companyname";
	
	if(!($show_sql =mysql_query($qry_select_user)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<script language="javascript" src="../scripts/general.js"></script>

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
  
  if(document.Frmcompany.txtChargeBack.value==""){
    alert("Please enter processor")
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
    alert("Please enter processor")
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
    alert("Please enter processor")
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

  if(document.Frmcompany.txtVoicefee.value==""){
    alert("Please enter processor")
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
 
  
     if(document.Frmcompany.txtDiscountRate.value==""){
    alert("Please enter processor")
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


 if(document.Frmcompany.txtReserve.value==""){
    alert("Please enter reserve value")
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
  	alert("Please select the Merchant type.");
	return false;
  }
  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
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
		if(document.Frmcompany.txtBillingdescriptor.value==""){
			alert("Please enter the billing descriptor.")
			document.Frmcompany.txtBillingdescriptor.focus();
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
		document.Frmcompany.txtMerchantName.value="";
		document.Frmcompany.txtTollFreeNumber.value="";
		document.Frmcompany.txtRetrievalNumber.value="";
		document.Frmcompany.txtSecurityNumber.value="";
		document.Frmcompany.txtProcessor.value="";
		document.Frmcompany.txtBillingdescriptor.value="";
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
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit&nbsp;Details </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
		<form action="modifyCompanyNext.php" method="post" onsubmit="return validation()" name="Frmcompany" >
<?php
		if(!isset($invalidlogin))
		{
			$invalidlogin = "";
		}	
?>	
  		<table height="100%" width="100%" cellspacing="0" cellpadding="0" ><tr><td align="center">
<?   
	print($invalidlogin);
	if($showval = mysql_fetch_array($show_sql)){ 
			if($showval[11]=="YES") 
			{
				$check ="checked";
			}
			else
			{
			$check ="";
			}
			if($showval[27] == "tele") {
				$script_display ="yes";
			}else {
				$script_display ="none";
			}
	  ?>
      <table width="400" border="0" cellpadding="0"  height="100">	 
	  <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Suspend User &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="checkbox" name="suspend" <?=$check?> ></input></td></tr>
		<tr><td width="150" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company Informations </strong>&nbsp;</font></td>
		<td width="250" height="30" align="left"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Company Name &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[3]?>"></input></td></tr>
	    <tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">User Name &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="100" name="username" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[1]?>"></input></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Password &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[2]?>"></input></td></tr>
        <tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Retype Password &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="30" name="password1" style="font-family:arial;font-size:10px;width:240px"  value="<?=$showval[2]?>"></input></td></tr>
        <tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Address &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" name="address" value="<?=$showval[5]?>" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">City &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" name="city" value="<?=$showval[6]?>" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="250">
			<select name="country"  style="font-family:arial;font-size:10px;width:240px" onchange="return validator()"> 
			<?=func_get_country_select($showval[8],1) ?>
			  </select> 
			  <script language="javascript">
				document.Frmcompany.country.value='<?=$showval[8]?>';	
			  </script>
	
		</td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">State&nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="250">
			<select name="state"  style="font-family:arial;font-size:10px;width:240px"> 
			<?=func_get_state_select($showval[7]) ?>
		  </select> 

		</td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Other State&nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" name="ostate" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[12]?>"></input></td></tr>
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
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" name="zipcode" value="<?=$showval[9]?>" style="font-family:arial;font-size:10px;width:140px"></input></td></tr> 
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Phone Number &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="25" name="phonenumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[4]?>"></input></td></tr>
		<tr><td width="150" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web Site Informations </strong>&nbsp;</font></td>
		<td width="250" height="30" align="left"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Email&nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[10]?>"></input></td></tr> 
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">URL &nbsp;</font></td>
		<td align="left" height="30" width="250"><input type="text" maxlength="100" name="url1" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[43]?>"><br><input type="text" maxlength="100" name="url2" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[44]?>"><br><input type="text" maxlength="100" name="url3" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[45]?>"></td></tr>
		<tr><td width="150" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Rates & Fees</strong>&nbsp;</font></td>
		<td width="250" height="30" align="left">&nbsp;</td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Charge Back $ &nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtChargeBack" type="text" id="txtChargeBack" value="<?=$showval[18]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Credit $ &nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtCredit" type="text" id="txtCredit" value="<?=$showval[19]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Transaction Fee $&nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtTransactionFee" type="text" id="txtTransactionFee" value="<?=$showval[21]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Voice Authorization Fee $&nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtVoicefee" type="text" id="txtVoicefee" value="<?=$showval[23]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Discount Rate %&nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtDiscountRate" type="text" id="txtDiscountRate" value="<?=$showval[20]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Reserve % &nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="txtReserve" type="text" id="txtReserve" value="<?=$showval[22]?>" style="font-family:arial;font-size:10px;width:240px"></td></tr>
		<tr><td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process Informations </strong>&nbsp;</font></td>
		<td width="225" height="30" align="left">&nbsp;</td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Active&nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="250"><input name="rad_trans_activity" type="radio" value="1" <?=$showval[28] ==1 ? "checked" : ""?>>
    	<font face="verdana" size="1">&nbsp;&nbsp;Non Active&nbsp;</font><input name="rad_trans_activity" type="radio" value="0" <?=$showval[28] ==0 ? "checked" : ""?>></td></tr>
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Unsubscribe from&nbsp;&nbsp; mailing list</font></td>
		<td align="left" height="30" width="250"><input name="chk_unsubscribe" type="checkbox" value="0" <?=$showval[76] == 0 ? "checked" : ""?>></td></tr>			
		<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Merchant type&nbsp;&nbsp;</font></td>
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
			<script language="javascript">
				 document.Frmcompany.rad_trans_type.value='<?=$showval[27]?>';	
			</script>
		</tr>
		<tr><td colspan="2">
		<div id="script" style="display:<?=$script_display?>">
			<table width="100%"> 
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Automatically Cancel&nbsp;&nbsp; Order, if not replied&nbsp;&nbsp; within the timeframe&nbsp;&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="chk_auto_cancel" type="checkbox" value="Y"  <?=$showval[24] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;<input type="text" name="time_frame" size="2" value="<?=$showval[25] == -1 ? "" : $showval[25]?>" style="font-family:arial;font-size:10px"></td></tr>
			<tr>
			<td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Shipping Cancel (auto) &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="chk_shipping_cancel" type="checkbox" value="Y" <?=$showval[31] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;<input type="text" name="shipping_time_frame" size="2" value="<?=$showval[32] == -1 ? "" : $showval[32]?>" style="font-family:arial;font-size:10px"></td></tr>
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Auto Approve Pass&nbsp;&nbsp; Orders&nbsp;&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="chk_auto_approve" type="checkbox" value="Y" <?=$showval[26] == "Y" ? "checked" : ""?>></td></tr>
			<tr><td width="150" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter template setup</strong>&nbsp;</font></td>
			<td width="250" height="30" align="left"></td></tr>
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Merchant Name&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="txtMerchantName" type="text" id="txtMerchantName" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[13]?>"></td></tr><tr>
			<td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Toll Free Number&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="txtTollFreeNumber" type="text" id="txtTollFreeNumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[14]?>"></td></tr>
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Retrieval Number&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="txtRetrievalNumber" type="text" id="txtRetrievalNumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[15]?>"></td></tr>
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Security Number&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="txtSecurityNumber" type="text" id="txtSecurityNumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[16]?>"></td></tr>
			<tr><td align="left" valign="center" height="30" width="150"><font face="verdana" size="1">Processor&nbsp;</font></td>
			<td align="left" height="30" width="250"><input name="txtProcessor" type="text" id="txtProcessor" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[17]?>"></td></tr>
			<tr><td align="left" valign="center" height="30" width="175"><font face="verdana" size="1">Billing Descriptor &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="txtBillingdescriptor" type="text" id="txtBillingdescriptor" value="<?=$showval[48]?>"  style="font-family:arial;font-size:10px;width:240px"></td></tr>
			<tr><td width="175" height="30" align="right" valign="center" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Verification Script </strong>&nbsp;</font></td>
			<td width="225" height="30" align="left">&nbsp;</td></tr>	
			<tr><td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package Name &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="txtPackagename" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[33]?>"></td></tr>
			<tr><td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package Product Service &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="txtPackageProduct" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[34]?>"></td></tr>
			<tr><td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Package Price &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="txtPackagePrice" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[35]?>"></td></tr>
			<tr><td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Refund Policy &nbsp;</font></td>
			<td align="left" height="30" width="225"><input name="txtRefundPolicy" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[36]?>"></td></tr>
			<tr><td align="left" valign="middle" height="30" width="175"><font face="verdana" size="1">Description &nbsp;</font></td>
			<td align="left" height="30" width="225"><textarea name="txtDescription" type="text" style="font-family:arial;font-size:10px;width:240px" rows="6"><?=$showval[37]?></textarea></td></tr>
			</table>
		</div>
		</td></tr>
		<tr><td colspan="2" align="center">
		<input type="hidden" name="company" value="company"></input>
		<table>
		<tr><td align="center" valign="center" height="30" colspan="2"><a href="#" onclick="window.history.back()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>
		</table></td></tr>
	    </table>
<?
  	 }
?>
  	</td></tr></table>
	</form>
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

