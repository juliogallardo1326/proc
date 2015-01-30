<?php 
session_start();
require_once("includes/dbconnection.php");
require_once('includes/function.php');
?>
<!--
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// independent_merchant.php: This page functions for creating the test users company in the site. 
-->

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
	 
  if(document.Frmcompany.txtVoiceauthFee.value==""){
    alert("Please enter processor")
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

  </script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%" align="center">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="50%" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #A6A6A6">

	<tr>
	<td class="lgnbd" colspan="5">
		<form action="add_merchant.php" method="post" onsubmit="return validation()" name="Frmcompany" >
  	<table height="100%" width="100%" cellspacing="0" cellpadding="0">
  	<tr>
  		<td  width="100%" valign="center" align="center"><br>
			<table border="0" cellpadding="0"  height="100">
			<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Company Name &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>	
		  	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">User Name &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="30" name="username" style="font-family:arial;font-size:10px;width:240px" ></input></td></tr>
		  	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Password &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
           	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Confirm Password &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="30" name="password1" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>		  
          	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Address &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" name="address" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
		 	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">City &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" name="city" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
			<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td><td align="left" height="30" width="250">
				<select name="country"  style="font-family:arial;font-size:10px;width:240px" onchange=" return validator()"> 
					<?=func_get_country_select($showval[8]) ?>
				  </select> 
				  <script language="javascript">
					document.Frmcompany.country.value='<?=$showval[8]?>';	
				  </script>
				</select>	
			</td></tr>
        	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">State&nbsp;&nbsp;</font></td><td align="left" height="30" width="250">
				<select name="state" style="font-family:arial;font-size:10px;width:240px"><option>- - -Select- - -</option>
				<?=func_get_state_select($showval[7]) ?>
			  </select> 
			</td></tr><input type="hidden" name="company" value="company"></input>
			<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Other State&nbsp;&nbsp;</font></td><td align="left" height="30" width="250"><input type="text" name="ostate" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
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
			<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td><td align="left" height="30" width="250"><input type="text" name="zipcode" style="font-family:arial;font-size:10px;width:140px"></input></td></tr>
			<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Phone Number &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="25" name="phonenumber" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
           	<tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Email &nbsp;</font></td><td align="left" height="30" width="250"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px"></input></td></tr>
			          <tr> 
                        <td width="150" height="20" align="right" valign="center" bgcolor="#78B6C2"><font face="verdana" size="1" color="#FFFFFF"><strong>E-mail 
                          Template Setup</strong>&nbsp;</font></td>
                        <td height="20" align="left"></input></td>
						</tr>
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Merchant 
                          Name&nbsp;</font></td>
						<td align="left" height="30" width="250"></input>
                          <input name="txtMerchantName" type="text" id="txtMerchantName" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Toll 
                          Free Number&nbsp;</font></td>
						<td align="left" height="30" width="250"></input>
                          <input name="txtTollFreeNumber" type="text" id="txtTollFreeNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Retrieval 
                          Number&nbsp;</font></td>
						<td align="left" height="30" width="250"></input>
                          <input name="txtRetrievalNumber" type="text" id="txtRetrievalNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Security 
                          Number&nbsp;</font></td>
						<td align="left" height="30" width="250"></input>
                          <input name="txtSecurityNumber" type="text" id="txtSecurityNumber" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Processor&nbsp;</font></td>
						<td align="left" height="30" width="250"></input>
                          <input name="txtProcessor" type="text" id="txtProcessor" style="font-family:arial;font-size:10px;width:240px"></td></tr>
						<tr> 
                       <td width="150" height="20" align="right" valign="center" bgcolor="#78B6C2"><font face="verdana" size="1" color="#FFFFFF"><strong>Ledger 
                          Constants</strong>&nbsp;</font></td> 
	                        <td width="250" height="20" align="left">&nbsp;</td>
						</tr>
			
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Charge 
                          Back $ &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtChargeBack" type="text" id="txtChargeBack" style="font-family:arial;font-size:10px;width:240px">
                        </td>
						</tr>
						
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Credit 
                          $ &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtCredit" type="text" id="txtCredit" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
					
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Transaction 
                          Fee $ &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtTransactionFee" type="text" id="txtTransactionFee" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="right" valign="middle" height="30" width="150"><font face="verdana" size="1">Voice Authorization 
						Fee $ &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtVoiceauthFee" type="text" id="txtVoiceauthFee" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="right" valign="middle" height="30" width="150"><font face="verdana" size="1">Discount 
                          Rate % &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtDiscountRate" type="text" id="txtDiscountRate" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>
						
						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Reserve 
                          % &nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="txtReserve" type="text" id="txtReserve" style="font-family:arial;font-size:10px;width:240px"></td>
						</tr>

						<tr>
                        <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Telemarketing&nbsp;&nbsp;</font></td>
						<td align="left" height="30" width="250"><input name="rad_trans_type" type="radio" value="tele" checked><font face="verdana" size="1">&nbsp;&nbsp;Ecommerce&nbsp;</font><input name="rad_trans_type" type="radio" value="ecom"></td>
						</tr>

						<tr><td align="center" valign="center" height="30" colspan="2"><input type="submit" name="addcompany" value="Submit" style="font-size:10px"></input></td></tr>
	  </table>
 	 </td></tr></table></form>
	 </td>
      </tr>
   </table><br>
    </td>
     </tr>
</table>