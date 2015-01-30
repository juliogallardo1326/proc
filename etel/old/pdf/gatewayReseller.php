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
// addReseller.php:	This admin page functions for adding  the company user. 

require_once("includes/function.php");
$companyname = (isset($HTTP_GET_VARS['companyname'])?Trim($HTTP_GET_VARS['companyname']):"");

	if($companyname!="")
	{ 

		$qry_select="SELECT cs_companydetails.companyname,cs_companydetails.userId FROM cs_companydetails WHERE cs_companydetails.companyname ='$companyname' ";
		$result=mysql_query($qry_select,$cnn_cs);
	
		if(mysql_num_rows($result)>0)
		{	
			if($result)
			{
				$rs=mysql_fetch_array($result);
				$sgatewayCoName=$rs[0];
				$sgatewayUserId=$rs[1];
			}
			else
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
		}
		else{
		echo "Invalid Url..!";
		exit();
		}
	}
	else
	{
	echo(" Invalid Url..!");
	exit();
	}
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="JavaScript" >
//new function///returns true if no special chars
function func_vali_pass(frmelement)
{ 
 var invalid="`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
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
}//if
}//for
if (b_flag==true)return true;
}



function validation() {
	trimSpace(document.addResellerForm.companyname)
	if (document.addResellerForm.companyname.value =="") {
		alert("Please enter the Company name.");
		document.addResellerForm.companyname.focus();
		return false;
	}

	trimSpace(document.addResellerForm.contactname)
	if (document.addResellerForm.contactname.value =="") {
		alert("Please enter the Contact name.");
		document.addResellerForm.contactname.focus();
		return false;
	}

	trimSpace(document.addResellerForm.username)
	if (document.addResellerForm.username.value ==""){
		alert("Please enter the User name.");
		document.addResellerForm.username.focus();
		return false;
	}
	trimSpace(document.addResellerForm.username)
	if (document.addResellerForm.username.value !=""&&(!func_vali_pass(document.addResellerForm.username)))
		{
			
		alert("Special characters not allowed for username");
		document.addResellerForm.username.focus();
		return false;
		}
		


	trimSpace(document.addResellerForm.password)
	if (document.addResellerForm.password.value =="") {
		alert("Please enter the Password.");
		document.addResellerForm.password.focus();
		return false;
	}
	trimSpace(document.addResellerForm.password)
	if (document.addResellerForm.password.value !=""&&(!func_vali_pass(document.addResellerForm.password)))
		{
			
		alert("Special characters not allowed for password");
		document.addResellerForm.password.focus();
		return false;
		}


	trimSpace(document.addResellerForm.repassword)
	if (document.addResellerForm.repassword.value =="") {
		alert("Please re-enter the Password.");
		document.addResellerForm.repassword.focus();
		return false;
	}
	trimSpace(document.addResellerForm.password)
	if(document.addResellerForm.password.value !="") {
		if(document.addResellerForm.password.value != document.addResellerForm.repassword.value ) {
			alert("Please retype the correct Password.");
			document.addResellerForm.repassword.focus();
			return false;
		} 
	}
	
	trimSpace(document.addResellerForm.email)
	if (document.addResellerForm.email.value =="") {
		alert("Please enter the email address.");
		document.addResellerForm.email.focus();
		return false;
	}
	
	 if (document.addResellerForm.email.value  != "") 
	{
		if (document.addResellerForm.email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.addResellerForm.email.focus();
			return(false);
		}
	}
	
	if (document.addResellerForm.email.value  != "") 
	{
		if (document.addResellerForm.email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.addResellerForm.email.focus();
			return(false);
		}
	}
	
	if (document.addResellerForm.email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.addResellerForm.email.focus();
		return(false);
	}
	
	trimSpace(document.addResellerForm.confirmemail)
	if (document.addResellerForm.confirmemail.value =="") {
		alert("Please enter the confirm email address.");
		document.addResellerForm.confirmemail.focus();
		return false;
	}
	
	if(document.addResellerForm.email.value != document.addResellerForm.confirmemail.value ) {
		alert("Please enter the correct email address.");
		document.addResellerForm.confirmemail.focus();
		return false;
	} 
		
		
	trimSpace(document.addResellerForm.merchantmonthly)
	if (document.addResellerForm.merchantmonthly.value =="") {
		alert("Please select the monthly merchant application.");
		document.addResellerForm.merchantmonthly.SelectedIndex=1;
		return false;
	}
	trimSpace(document.addResellerForm.phone)
	if (document.addResellerForm.phone.value =="") {
		alert("Please enter the phone number.");
		document.addResellerForm.phone.focus();
		return false;
	}
	
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
<html>

<head>

<title></title>
<style type="text/css" rel="stylesheet">
.rs_rightbd{border-right:1px solid #CFDBE4}
.rs_bd{border-right:1px solid #CFDBE4;border-left:1px solid #B0BBCA}
.rs_bdwhite{border-bottom:1px solid #F1F6FD}
.rs_fbd{border:1px solid #839EC4}
</style>
</head>

<body topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center" class="rs_bd">
  <tr>
    <td bgcolor="#F2F9F8"><img border="0" src="images/spacer.gif" width="263" height="100" alt=""><img border="0" src="images/2_res.jpg" width="252" height="100" alt="" ><img border="0" src="images/3_res.jpg" width="263" height="100" alt=""></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
  <tr>
    <td bgcolor="#9EB0CD" height="18"></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center" height="400">
  <tr>
    <td width="155" bgcolor="#EEF1F7" class="rs_bd" valign="top" align="left">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="100%" bgcolor="#ACBBD5" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#BECADE" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#D2DBE8" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#DDE4EE" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#E6EBF2" height="20" class="rs_bdwhite">&nbsp;</td>
        </tr>
        <tr>
          <td width="100%" bgcolor="#F1F4F8" height="20" >&nbsp;</td>
        </tr>
      </table>
    </td>
    <td width="621" class="rs_rightbd" valign="middle" align="center">
      <table border="0" cellpadding="0" cellspacing="1" width="550" height="350">
        <tr>
          <td height="20" bgcolor="#698AB8"></td>
        </tr>
        <tr>
          <td height="330" class="rs_fbd">
		  <form name="addResellerForm" action="gatewayReseller_fb.php"  method="post" onsubmit="javascript:return validation();">
	 <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Company 
                          name:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="companyname" type="text" style="font-family:arial;font-size:10px;width:200px" value="" maxlength="250"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Contact 
                          name:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="contactname" type="text" style="font-family:arial;font-size:10px;width:200px" value="" maxlength="250"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User 
                          name:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="username" type="text" style="font-family:arial;font-size:10px;width:150px" value="" maxlength="50"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:150px" value="" maxlength="50"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Confirm 
                          password:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="repassword" type="text" style="font-family:arial;font-size:10px;width:150px" ></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Email 
                          address:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="email" type="text" style="font-family:arial;font-size:10px;width:200px" ></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Confirm 
                          email address:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="confirmemail" type="text" style="font-family:arial;font-size:10px;width:200px" ></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">New 
                          merchant applications monthly:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><select name="merchantmonthly" style="font-family:arial;font-size:10px;width:70px" >
                            <option value="">Select</option>
                            <option value="1-5">1-5</option>
                            <option value="5-10">5-10</option>
                            <option value="10-25">10-25</option>
                            <option value="25-50">25-50</option>
                            <option value="50-100">50-100</option>
                            <option value="100+">100+</option>
                          </select></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Phone 
                          number:&nbsp;</font></td>
                        <td align="left" valign="center" height="30" width="50%"><input name="phone" type="text" style="font-family:arial;font-size:10px;width:120px" maxlength="25" ></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="center" height="29" width="50%"><font face="verdana" size="1">URL:&nbsp;</font></td>
                        <td align="left" valign="center" height="29" width="50%"> 
                          <input name="url" type="text" style="font-family:arial;font-size:10px;width:200px" ></td>
                      </tr>
                    </table>
		  </td>
		  </tr>
		  <tr>
                  <td align="center">&nbsp;&nbsp;&nbsp;
<input type="image" id="adduser" src="images/submit.jpg"></input>
<input type="hidden" name="gatewayCompanyName" value="<?php echo $sgatewayUserId;  ?>">
		
		   </td>
		</tr>
		</table>
	</form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
  <tr>
    <td bgcolor="#859BC0" height="20"></td>
  </tr>
</table>

</body>

</html>

