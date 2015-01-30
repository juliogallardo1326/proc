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
// editCompanyProfile.php:	This admin page functions for editing the company details. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
	
	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid=$company_id";
	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
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

function addRatesFees(field) {
	if(document.Frmcompany.reseller_discount.value==""){ document.Frmcompany.reseller_discount.value=0;}
	if(document.Frmcompany.merchant_discount.value==""){ document.Frmcompany.merchant_discount.value=0;}
	if(document.Frmcompany.reseller_transfee.value==""){ document.Frmcompany.reseller_transfee.value=0;}
	if(document.Frmcompany.merchant_transfee.value==""){ document.Frmcompany.merchant_transfee.value=0;}

	if(field=="disc") {
		document.Frmcompany.total_discount.value = parseInt(document.Frmcompany.reseller_discount.value) + parseInt(document.Frmcompany.merchant_discount.value)
		return false;		  
	} else {
		document.Frmcompany.total_transfee.value = parseInt(document.Frmcompany.reseller_transfee.value) + parseInt(document.Frmcompany.merchant_transfee.value)
		return false;		  
	}
}

/*
function validateForm() {
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
	if(document.Frmcompany.customerservice_phone.value==""){
		alert("Please enter customer service phone number")
		document.Frmcompany.customerservice_phone.focus();
		return false;
	}
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

	if(document.Frmcompany.volume.value==""){
		alert("Please enter the projected monthly volume.")
		document.Frmcompany.volume.focus();
		return false;
	}
	if(document.Frmcompany.avgticket.value==""){
		alert("Please enter the average ticket value.")
		document.Frmcompany.avgticket.focus();
		return false;
	}

	if(document.Frmcompany.currentBank.value==""){
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
	}

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
	if(document.Frmcompany.txtBillingdescriptor.value==""){
		alert("Please enter the billing descriptor.")
		document.Frmcompany.txtBillingdescriptor.focus();
		return false;
	} 
	if(document.Frmcompany.email.value==""){
		alert("Please enter email")
		document.Frmcompany.email.focus();
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
		}else if(document.Frmcompany.txtPackagePrice.value <= 0){
			alert("Please enter a valid price.")
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

	if(document.Frmcompany.first_name.value == "") {
		alert("Please enter the first name.")
		document.Frmcompany.first_name.focus();
		return false;
	}
	if(document.Frmcompany.family_name.value == "") {
		alert("Please enter the family name.")
		document.Frmcompany.family_name.focus();
		return false;
	}
	if(document.Frmcompany.job_title.value == "") {
		alert("Please enter the job_title.")
		document.Frmcompany.job_title.focus();
		return false;
	}
	if(document.Frmcompany.contact_email.value == "") {
		alert("Please enter the contact email address.")
		document.Frmcompany.contact_email.focus();
		return false;
	}
	if(document.Frmcompany.confirm_contact_email.value == "") {
		alert("Please confirm the contact email address.")
		document.Frmcompany.confirm_contact_email.focus();
		return false;
	}
	if(document.Frmcompany.confirm_contact_email.value != document.Frmcompany.contact_email.value) {
		alert("Contact email ids do not match.")
		document.Frmcompany.confirm_contact_email.focus();
		return false;
	}
	if(document.Frmcompany.contact_phone.value == "") {
		alert("Please enter the telephone number.")
		document.Frmcompany.contact_phone.focus();
		return false;
	}
	if(document.Frmcompany.how_about_us.value == "") {
		alert("Please select where you heard about <?=$_SESSION['gw_title']?>.")
		document.Frmcompany.how_about_us.focus();
		return false;
	}

} */
function SelectMerchanttype() {
	if(document.Frmcompany.how_about_us.value=='rsel' || document.Frmcompany.how_about_us.value=='other') {
		document.Frmcompany.reseller_other.disabled=false;
	}else {
		document.Frmcompany.reseller_other.disabled=true;
	}
}
function funcOpen3VT(iCompanyId) {
	window.open("vtusers.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenTSR(iCompanyId) {
	window.open("tsruserlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenEcom(iCompanyId) {
	window.open("ecomlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
</script>
<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit&nbsp; 
            Details </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="editCompanyNext.php"  name="Frmcompany" method="post">
	<?	
		if($showval = mysql_fetch_row($show_sql)) 
		{
			if($showval[7]=="") 
			{
				$state=str_replace("\n",",\t",$showval[12]);
			} 
			else 
			{
				$state=str_replace("\n",",\t",$showval[7]);
			}
			if($showval[27] == "tele") {
				$script_display ="yes";
				$sendecommerce_diplay = "none";
			}else {
				$script_display ="none";
				$sendecommerce_diplay = "yes";
			}
			if($showval[84] == 1) {
				$sendecommerce_checked = "checked";
			}else {
				$sendecommerce_checked = "";
			}
			
		 ?>
		  <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top" height="600">
			
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center"  height="600">
		<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>					  
						<tr height='30'>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b> 
                          &nbsp;Company Name</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'>
                          &nbsp;<input type="text" name="companyname" class="normaltext" style="width:200px" value="<?=$showval[3]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;User 
                          Name</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="username" class="normaltext" style="width:200px" value="<?=$showval[1]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Password</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="password" class="normaltext" style="width:200px" value="<?=$showval[2]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Confirm 
                          Password</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="password1" class="normaltext" style="width:200px" value="<?=$showval[2]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Address</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="address" class="normaltext" style="width:200px" value="<?=str_replace("\n",",\t",$showval[5]);?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;City</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="city" class="normaltext" style="width:200px" value="<?=str_replace("\n",",\t",$showval[6]);?>">
                        </td>
                      </tr>

                      <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Country</b></font></td>
                        <td height="30" align='left'  class='cl1'> 
                          &nbsp;<select name="country"  style="font-family:arial;font-size:10px;width:150px" onchange="return validator()"> 
						<?=func_get_country_select($showval[8],1) ?>
                          </select> 
						  <script language="javascript">
					     	document.Frmcompany.country.value='<?=$showval[8]?>';	
						  </script>

						</td>
                      </tr>
                      <tr> 
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;State</b></font></td>
                        <td height="30" align='left'  class='cl1'> 
                          &nbsp;<select name="state"  style="font-family:arial;font-size:10px;width:150px"> 
							<?=func_get_state_select($showval[7]) ?>
						  </select> 
						  </td>
                      </tr> 
					  <tr> 
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Other 
                          State</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="ostate"  class="normaltext" style="width:200px" value="<?=$showval[12]?>"></input></td>
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
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="zipcode" class="normaltext" style="width:200px" value="<?=str_replace("\n",",\t",$showval[9]);?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Fax 
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="faxnumber" class="normaltext" style="width:200px" value="<?=$showval[51]?>">
                        </td>
                      </tr>
						<tr> 
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Phone 
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="phonenumber" class="normaltext" style="width:200px" value="<?=$showval[4]?>">
                        </td>
                      </tr>
					  <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Type 
                          Of Company</strong> &nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' > 
                          &nbsp;<select name="company_type"  style="font-family:arial;font-size:10px;width:160px" >
						<option value="">--Choose one --</option>
						<option value="part" <?= $showval[52] == "part" ? "selected" : ""?>>Limited Partnership</option>
						<option value="ltd" <?= $showval[52] == "ltd" ? "selected" : ""?>>Limited Liability Company</option>
						<option value="corp" <?= $showval[52] == "corp" ? "selected" : ""?>>Corporation</option>
						<option value="sole" <?= $showval[52] == "sole" ? "selected" : ""?>>Sole Proprietor</option>
						<option value="other" <?= $showval[52] == "other" ? "selected" : ""?>>Other</option>
						</select>      
						</td>
                      </tr>
					  <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;If 
                          'Other', please specify:</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="other_company_type" class="normaltext" style="width:200px" value="<?=$showval[54]?>">
                        </td>
                      </tr>					  					  
					  <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Customer 
                          services phone number</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="customerservice_phone" class="normaltext" style="width:200px" value="<?=$showval[54]?>">
                        </td>
                      </tr>					  

						<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant Active</font></strong></td>
                        <td height="30" class='cl1'>
                          <input name="rad_trans_activity" type="checkbox" value="1" <?=$showval[28] == 1 ? "checked" : ""?>>
						</td>
                      </tr>
						<tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Billing 
						  Descriptor Name</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtBillingdescriptor" class="normaltext" style="width:150px" value="<?=$showval[48]?>">
                        </td>
                      </tr>
						<tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Set up fees paid</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtSetupFee" class="normaltext" style="width:75px" value="<?=$showval[82]?>">
                        </td>
                      </tr>					  
					  <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
                          Type </font></strong></td>
                        <td height="30" class='cl1'>
                          &nbsp;<select name="rad_trans_type" style="font-family:arial;font-size:10px;width:100px" onChange="displayverification();">
<?php						print func_select_merchant_type($showval[27]); ?>
						  </select>
						  </td>
                      </tr>
					<tr>
                        <td width="100%" colspan="2"> <div id="auto_cancel" style="display:<?=$script_display?>"> 
					<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
					  <tr> 
                         <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Customer 
                         Service Cancel(auto)</font></strong></td>
                        <td align="left" height="25" class='cl1' width="274"><input name="chk_auto_cancel" type="checkbox" value="Y"  <?=$showval[24] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;&nbsp;<input type="text" name="time_frame" class="normaltext" size="2" value="<?=$showval[25] == -1 ? "" : $showval[25]?>" style="font-family:arial;font-size:10px">
						</td>
                      </tr>
                      <tr> 
				  <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Shipping 
					Cancel(auto)</font></strong></td>
					<td align="left" height="30" width="191" class='cl1'><input name="chk_shipping_cancel" type="checkbox" value="Y" <?=$showval[31] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;&nbsp;<input type="text" name="shipping_time_frame" size="2" value="<?=$showval[32] == -1 ? "" : $showval[32]?>" style="font-family:arial;font-size:10px">
					</td>
                      </tr>
                      <tr> 
                              <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Auto 
                                Approve Pass Orders&nbsp;</font></strong></td>
                        <td align="left" height="25" class='cl1'><input name="chk_auto_approve" type="checkbox" value="Y" <?=$showval[26] == "Y" ? "checked" : ""?>>
						</td>
                      </tr>
					  </table></div>
 					  </td>
					  </tr>
                     <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Expected 
                          Monthly Volume ($)&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
                          &nbsp;<select name="volume" style="font-family:arial;font-size:10px;width:120px">
<?php						func_select_merchant_volume($showval[30]); ?>
						  </select>
						</td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Average 
                          Ticket&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
                          &nbsp;<input type="text" name="avgticket" class="normaltext" style="width:100px" value="<?=$showval[38]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Charge 
                          Back %&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
                          &nbsp;<input type="text" name="chargeper" class="normaltext" style="width:100px" value="<?=$showval[39]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Previous 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'><input name="prepro" type="checkbox" value="Yes" <?=$showval[40] == "Yes" ? "checked" : ""?>>
						</td>
                      </tr>
					  
					  <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Recurring 
                          Billing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
                         <input name="rebill" type="checkbox" value="Yes" <?=$showval[41] == "Yes" ? "checked" : ""?>>
						</td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Currently 
                          Processing&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
                          <input name="currpro" type="checkbox" value="Yes" <?=$showval[42] == "Yes" ? "checked" : ""?>>
						</td>
                      </tr>
						<!--  Bank details integrating starts -->
						<?php
							$qrySelect = "select * from cs_bank_company where company_id =  $company_id";
							$rstSelect = mysql_query($qrySelect,$cnn_cs);
							$iCheckBankId = "";
							$iCreditBankId = "";
							if ( mysql_num_rows($rstSelect) > 0 ) {
								$iCheckBankId = mysql_result($rstSelect,0,2);
								$iCreditBankId = mysql_result($rstSelect,0,3);
							}
						
						?>
					  <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Check Bank&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
						<select name="cboCheckBank" style="font-family:arial;font-size:10px;width:120px">
							<option value="">Select Bank</option>
							<?php
								func_fill_combo_conditionally("select bank_id,bank_name from cs_bank where bk_hide=0 ",$iCheckBankId,$cnn_cs);
							?>
						</select>
						
						</td>
                      </tr>
					  
					  <tr> 
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Credit Card Bank&nbsp;</font></strong></td>
                        <td align="left" height="30" class='cl1'>
						<select name="cboCrditBank" style="font-family:arial;font-size:10px;width:120px">
 							<option value="">Select Bank</option>
							<?php
								func_fill_combo_conditionally("select bank_id,bank_name from cs_bank where bk_hide=0 ",$iCreditBankId,$cnn_cs);
							?>
						</select>
						</td>
                      </tr>
					   <!--  Bank details integrating ends -->



                      <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Bank 
                          Processing Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><strong><font face="verdana" size="1">With 
                          which bank do you hold a company account?</font></strong></td>
                        <td height="30" align="left"  class='cl1' >
                          <select name="currentBank" style="font-family:verdana;font-size:10px;width:270px" >
					<?=func_get_bank_select($showval[55])?>
                          </select> 
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left" class='cl1' ><font face="verdana" size="1"><strong>If 'Other', please specify</strong></font></td>
                        <td height="30" align="left" class='cl1' >&nbsp;<input type="text" maxlength="100" name="bank_other" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[56]?>"> 
						</td>
                      </tr>
					<tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Beneficiary Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="100" name="beneficiary_name" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[79]?>">
						</td>
                      </tr>                      
					<tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Name On Bank Account</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="100" name="bank_account_name" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[80]?>">
						</td>
                      </tr>					  <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="100" name="bank_address" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[57]?>">
						</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          country</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<select name="bank_country"  style="font-family:verdana;font-size:10px;width:170px">
  					  <option value="">---------- Please select -----------</option>
					<script language="javascript">
						 showCountries();	
					</script>
						
                    </select>
					<script language="javascript">
						 document.Frmcompany.bank_country.value='<?=$showval[58]?>';	
					</script>
					</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          telephone number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="10" name="bank_phone" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[59]?>">
						</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          sort code / Branch Code</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="10" name="bank_sort_code" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[60]?>">
						</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Account number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="10" name="bank_account_number" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[61]?>">
						</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>Bank 
                          Swift Code</strong></font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;<input type="text" maxlength="10" name="bank_swift_code" style="font-family:verdana;font-size:10px;width:150px" value="<?=$showval[62]?>">
						</td>
                      </tr>
<?php 
	/*	$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$mydirLicense = dir($str_current_path.'..//UserDocuments//License//');
		$mydirArticles = dir($str_current_path.'..//UserDocuments//Articles//');
		$mydirHistory = dir($str_current_path.'..//UserDocuments//History//');
		$mydirContract = dir($str_current_path.'..//UserDocuments//Contract//');
/*
		$mydirLicense = dir($str_current_path.'..\\UserDocuments\\License\\');
		$mydirArticles = dir($str_current_path.'..\\UserDocuments\\Articles\\');
		$mydirHistory = dir($str_current_path.'..\\UserDocuments\\History\\');
		$mydirContract = dir($str_current_path.'..\\UserDocuments\\Contract\\');
*/
	/*	$myLicenceFileList = func_read_file_uploaded_name($mydirLicense,$company_id);
		$myArticlesFileList = func_read_file_uploaded_name($mydirArticles,$company_id);
		$myHistoryFileList = func_read_file_uploaded_name($mydirHistory,$company_id);
		$myContractFileList = func_read_file_uploaded_name($mydirContract,$company_id);
		
		$myLicenceFileArray = split(",",$myLicenceFileList);
		$myArticlesFileArray = split(",",$myArticlesFileList);
		$myHistoryFileArray = split(",",$myHistoryFileList);
		$myContractFileArray = split(",",$myContractFileList);
	
		$myLicenceFileArray = array();
		$myArticlesFileArray = array();
		$myHistoryFileArray = array();
		$myProfessionalReferenceFileArray = array();
		$str_qry = "select file_type, file_name from cs_uploaded_documents where user_id = $company_id";
		if(!($show_sql1 =mysql_query($str_qry,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		if(mysql_num_rows($show_sql1)>0)
		{
			while($showval1 = mysql_fetch_row($show_sql1)) 
			{
				if ($showval1[0] == "License") {
					$myLicenceFileArray[] = $showval1[1];
				} else if ($showval1[0] == "Articles") {
					$myArticlesFileArray[] = $showval1[1];
				} else if ($showval1[0] == "History") {
					$myHistoryFileArray[] = $showval1[1];
				} else if ($showval1[0] == "Professional_Reference") {
					$myProfessionalReferenceFileArray[] = $showval1[1];
				}
			}
		}*/	
?>					
          </table>
		  <br>
		  <?php
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
				}
		  
		  ?>
		  
		  
		  
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center" width="100%">
		<tr> 
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
			</select>
			
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Misc Fee</b></font></td>
			<td height="30" align='left' class='cl1'>
			<input type="text" name="txtMiscFee" value="<?= $iMiscFee ?>" maxlength="10" style="font-family:verdana;font-size:10px;width:75px">
			</td>
    	</tr>
		</table>
		</td>
		<td align="center" width="50%" valign="top"  height="600">
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center" width="100%">
			<tr> 
               <td width="233" height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Users</strong>&nbsp;</font></td>
               <td height="30" class='cl1' align="left">&nbsp;</td>
			</tr>					  
			<tr height='30'>
               <td height="30" align='left' class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;3 VT Users</b></font></td>
               <td height="30" align='center' class='cl1'><font face='verdana' size='1'><a href="javascript:funcOpen3VT(<?=$showval[0]?>)">Show Users</a></font></td>
            </tr>
			<?php
				$sTransactionType = funcGetValueByQuery("select transaction_type  from cs_companydetails where  userId  = $showval[0]",$cnn_cs);
				if ($sTransactionType == "tele") {
			?>
					<tr height='30'>
					   <td height="30" align='left' class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;Call Center user and TSR</b></font></td>
					   <td height="30" align='center' class='cl1'><font face='verdana' size='1'>
					   <a href="javascript:funcOpenTSR(<?=$showval[0]?>)">Show Users</a></font>
					   </td>
					</tr>
			<?php
				}
				if ($sTransactionType != "tele") {
			?>
				<tr height='30'>
					   <td height="30" align='left' class='cl1' width="50%"><font face='verdana' size='1'><b>&nbsp;Web sites</b></font></td>
					   <td height="30" align='center'  width="50%" class='cl1'><font face='verdana' size='1'> 
					   <a href="javascript:funcOpenEcom(<?=$showval[0]?>)">Show Users</a></font>
					   </td>
					</tr>
			<? } ?>		
			</table>
				<table  width="100%"  height="600" class='lefttopright' cellpadding='0' cellspacing='0' valign="center" style='margin-top: 15; margin-bottom: 5'>
						<tr>
						<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Suspend 
						  User?</b></font></td>
						<td height="30" class='cl1' align='left'><input type="checkbox" name="suspend" class="normaltext" <?=$showval[11] == "YES" ? "checked" : ""?> value="YES">
						</td>
					  </tr>
					  <tr>
						<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Unsubscribe from mailing list?</b></font></td>
						<td height="30" align='left' class='cl1'><input type="checkbox" name="chk_unsubscribe" class="normaltext" <?=$showval[76] == 0 ? "checked" : ""?> value="0">
						</td>
					  </tr>
					  <tr>
                        <td colspan="2"> 
						<div id="auto_sendecommerce" style="display:<?=$sendecommerce_diplay?>"> 
						<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
						 <tr> 
							<td height="30" class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;Send 
							  Ecommerce Letter?</b></font></td>
							<td height="30" class='cl1'> 
							  <input type="checkbox" name="chk_sendecommerce" class="normaltext" value="1" <?=$sendecommerce_checked?>></td>
						  </tr>	
						  </table></div>
 					  </td>
					  </tr>
					  <tr>
                        <td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
                          & Fees</strong>&nbsp;</font></td>
	                        
                        <td height="30" align="left" class='cl1'>&nbsp;</td>
						</tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge 
                          Back - $</font></strong></td>
                        <td height="30" class='cl1'></font>
                          &nbsp;<input type="text" name="txtChargeBack" class="normaltext" style="width:100px" value="<?=$showval[18]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Credit 
                          $ </font></strong></td>
                        <td height="30" class='cl1'>
                          &nbsp;<input type="text" name="txtCredit" class="normaltext" style="width:100px" value="<?=$showval[19]?>">
                          </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
                          Fee - $</font></strong></td>
                        <td height="30" class='cl1'>
                          &nbsp;<input type="text" name="txtTransactionFee" class="normaltext" style="width:100px" value="<?=$showval[21]?>">
                        </td>
                      </tr>
					  <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Discount 
                          Rate - %</font></strong></td>
                        <td height="30" class='cl1'>
                          &nbsp;<input type="text" name="txtDiscountRate" class="normaltext" style="width:100px" value="<?=$showval[20]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve 
                          - %</font></strong></td>
                        <td height="30" class='cl1'>
                          &nbsp;<input type="text" name="txtReserve" class="normaltext" style="width:100px" value="<?=$showval[22]?>">
                        </td>
                      </tr>
					  <tr> 
                        <td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Voice 
                          Authorization Fee - $</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtVoicefee" class="normaltext" style="width:100px" value="<?=$showval[23]?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Web 
                          Site Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>                      
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Email&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="email" class="normaltext" style="width:200px" value="<?=$showval[10]?>">
                        </td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL1&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>
                          &nbsp;<input type="text" maxlength="100" name="url1" class="normaltext" style="width:200px" value="<?=$showval[43]?>">
                        </td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL2&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>
                          &nbsp;<input type="text" maxlength="100" name="url2" class="normaltext" style="width:200px" value="<?=$showval[44]?>">
                        </td>
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;URL3&nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>
                          &nbsp;<input type="text" maxlength="100" name="url3" class="normaltext" style="width:200px" value="<?=$showval[45]?>">
                        </td>
						</tr>
					<tr>
                        <td width="100%" colspan="2"> 
					<div id="script" style="display:<?=$script_display?>">
						<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
					  <tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="258"><font face="verdana" size="1" color="#FFFFFF"><strong>Letter 
                          template setup</strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
						</tr>
					  
                      <tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant 
						  Name</font></strong></td>
                        <td height="30" class='cl1'> &nbsp;<input type="text" name="txtMerchantName" class="normaltext" style="width:200px" value="<?=$showval[13]?>">
                        </td>
                      </tr>
                      <tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Toll 
						  Free Number</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtTollFreeNumber" class="normaltext" style="width:200px" value="<?=$showval[14]?>">
                        </td>
                      </tr>
                      <tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Retrieval 
						  Number </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtRetrievalNumber" class="normaltext" style="width:200px" value="<?=$showval[15]?>">
                        </td>
                      </tr>
                      <tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Security 
						  Number </font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtSecurityNumber" class="normaltext" style="width:200px" value="<?=$showval[16]?>">
                        </td>
                      </tr>
                      <tr> 
						<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processor</font></strong></td>
                        <td height="30" class='cl1'>&nbsp;<input type="text" name="txtProcessor" class="normaltext" style="width:200px" value="<?=$showval[17]?>">
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
						<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackagename" class="normaltext" style="width:200px" value="<?=$showval[33]?>">
                        </td>
						</tr>
						<tr>
						<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
						  Product Service &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackageProduct" class="normaltext" style="width:200px" value="<?=$showval[34]?>">
                        </td>
						</tr>
						<tr>
						<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Package 
						  Price &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<input type="text" name="txtPackagePrice" class="normaltext" style="width:200px" value="<?=$showval[35]?>">
                        </td>
						</tr>
						<tr>
						<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Refund 
						  Policy &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<textarea name="txtRefundPolicy" class="normaltext" style="width:200px" rows="4" cols="30"><?=$showval[36]?></textarea>
                        </td>
						</tr>
						<tr>
						<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Description 
						  &nbsp;</font></strong></td>
						<td align="left" height="30" class='cl1'>&nbsp;<textarea name="txtDescription" class="normaltext" style="width:200px" rows="4" cols="30"><?=$showval[37]?></textarea>	
                        </td>
						</tr>
						</table>
						</div>
						</td></tr>
				<tr> 
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>User 
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;Your First Name</strong></font></td>
                        <td height="30" align="left"   class='cl1' > 
                          &nbsp;<select name="cboTitle" style="font-family:verdana;font-size:10px;width:50px">
						<?php 
							funcFillComboWithTitle ( $showval[69] ); 
						?>
						</select>&nbsp;
						&nbsp;<input type="text" name="first_name" class="normaltext" style="width:100px" value="<?=$showval[63]?>">
                          </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;Your Last Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="family_name" class="normaltext" style="width:158px" value="<?=$showval[64]?>">
                         </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Date 
                          of birth</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          <?php
							$iYear = "";
							$iMonth = "";
							$iDay = "";
							if ($showval[70] !=""){
								list($iYear,$iMonth,$iDay) = split("-",$showval[70]);
							}
							print("&nbsp;");
							funcFillDate ( $iDay,$iMonth,$iYear );
						?>
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Sex</strong></font></td>
                        <td height="30" align="left"  class='cl1' > 
                          &nbsp;<select name="cboSex" style="font-family:verdana;font-size:10px;width:70px">
						<option value='Male' <?= $showval[71] == "Male" ? "selected" : ""?>>Male</option>
						<option value='Female' <?= $showval[71] == "Female" ? "selected" : ""?>>Female</option>
						</select>
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="txtAddress" class="normaltext" style="width:200px" rows="4" cols="30"><?=$showval[72]?></textarea>
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Zipcode</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="txtPostCode"  maxlength="7" class="normaltext" style="width:200px" value="<?=$showval[73]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
                          <strong>&nbsp;What is your job title or position?</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="job_title" class="normaltext" style="width:200px" value="<?=$showval[65]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Contact 
                          email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="contact_email" class="normaltext" style="width:200px" value="<?=$showval[66]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Please 
                          confirm email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="confirm_contact_email" class="normaltext" style="width:200px" value="<?=$showval[66]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Telephone 
                          number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="contact_phone" class="normaltext" style="width:200px" value="<?=$showval[67]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Residence 
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="residence_telephone" class="normaltext" style="width:200px" value="<?=$showval[74]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Fax 
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="fax" class="normaltext" style="width:200px" value="<?=$showval[75]?>">
                        </td>
                      </tr>
                      <tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Where 
                          did you hear about <?=$_SESSION['gw_title']?></strong></font></td>
                        <td height="30" align="left"  class='cl1' > 
                          &nbsp;<select name="how_about_us" style="font-family:verdana;font-size:10px;width:120px" onchange="SelectMerchanttype();">
							<?= func_fill_info_source_combo($cnn_cs, $showval[47]) ?>
						</select>						
						</td>
                      </tr>
						<tr> 
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Reseller Company Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="reseller_other"  style="font-family:verdana;font-size:10px;width:150px">
<?php 						$str_qry_resellers = "select reseller_id,reseller_companyname from cs_resellerdetails order by reseller_companyname";		
							$str_selected_value = $showval[81];
							func_fill_combo_conditionally($str_qry_resellers,$str_selected_value,$cnn_cs); 
?>
						  </select>
		                 </td>
                      </tr>
<?php if($str_selected_value!="") { ?>				
				<tr> 
				  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Reseller Rates & Fees 
					Informations</strong></font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
				</tr>
				<tr> 
				        <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total 
                          merchant discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="merchant_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[85] ?>" onChange="addRatesFees('disc');"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="reseller_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[86] ?>" onChange="addRatesFees('disc');"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total discount rate</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="total_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[87] ?>" onChange="addRatesFees('disc');"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="merchant_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[88] ?>" onChange="addRatesFees('trans');"></td>
				</tr>
				<tr> 
				 <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="reseller_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[89] ?>" onChange="addRatesFees('trans');"></td>
				</tr>
				<tr>
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total transaction fee</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="total_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[90] ?>" onChange="addRatesFees('trans');"></td>
				</tr>
<?php 		} 	?>				
				</table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="viewCompany.php"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input>&nbsp;&nbsp;&nbsp;<a href="completeAccounting.php?company_id=<?= $company_id?>&companymode=<?= $companytype?>&companytrans_type=<?= $companytrans_type?>"><img  SRC="<?=$tmpl_dir?>/images/completeaccounting.gif" border="0"></a></td></tr>	
		</table>
		</center>

<?php 
		}
?>
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


?>