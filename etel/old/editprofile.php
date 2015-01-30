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
// AddCompanyUser.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

include("includes/header.php");
$headerInclude="callcenter";
include("includes/topheader.php");
include("includes/message.php");
$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($_SESSION["sessionlogin_type"] == "tele")
{
	$iCCUserId = isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"";
	$qry_selectdetails = "Select cc_usersid,comany_name,company_conatct_no,address,amount,user_name,user_password from cs_callcenterusers where cc_usersid=$iCCUserId";	
	$rst_selectdetails = mysql_query($qry_selectdetails);
	if (mysql_num_rows($rst_selectdetails)>0)
	{
		$i_cc_userid = mysql_result($rst_selectdetails,0,0);
		$str_company_name = mysql_result($rst_selectdetails,0,1);
		$str_conatctno = mysql_result($rst_selectdetails,0,2);
		$str_address = mysql_result($rst_selectdetails,0,3);
		$str_amount = mysql_result($rst_selectdetails,0,4);
		$str_username = mysql_result($rst_selectdetails,0,5);
		$str_password = mysql_result($rst_selectdetails,0,6);
	}
?>
<script language="JavaScript" >
function validation() {
	trimSpace(document.adduser.companyname)
	if (document.adduser.companyname.value =="") {
		alert("Please enter the Company name.");
		document.adduser.companyname.focus();
		return false;
	}
	trimSpace(document.adduser.contactnumber)
	if (document.adduser.contactnumber.value =="") {
		alert("Please enter the Contact number.");
		document.adduser.contactnumber.focus();
		return false;
	}
	trimSpace(document.adduser.address)
	if (document.adduser.address.value =="") {
		alert("Please enter address.");
		document.adduser.address.focus();
		return false;
	}
	trimSpace(document.adduser.amount)
	if (document.adduser.amount.value =="") {
		alert("Please enter amount per sale.");
		document.adduser.amount.focus();
		return false;
	}
	if (isNaN(document.adduser.amount.value)) {
		alert("Please enter valid amount per sale.");
		document.adduser.amount.focus();
		return false;
	}
	trimSpace(document.adduser.password)
	if (document.adduser.password.value =="") {
		alert("Please enter the Password.");
		document.adduser.password.focus();
		return false;
	}
	trimSpace(document.adduser.repassword)
	if (document.adduser.repassword.value =="") {
		alert("Please re-enter the Password.");
		document.adduser.repassword.focus();
		return false;
	}
	trimSpace(document.adduser.password)
	if(document.adduser.password.value !="") {
		if(document.adduser.password.value != document.adduser.repassword.value ) {
			alert("Please retype the correct Password.");
			document.adduser.repassword.focus();
			return false;
		} else {
			return true;
		} 
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
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="80%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		        <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Edit 
                  Call Center User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="adduser" action="editcallcenteruserfb.php"  method="post" onsubmit="javascript:return validation();">
	 <input type="hidden" name="companyid" value="<?=$sessioncompanyid?>">
	 <input type="hidden" name="hid_CCUserId" value="<?=$i_cc_userid?>">
	 <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Company name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="companyname" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_company_name?>" maxlength="250"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Contact number:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="contactnumber" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_conatctno?>" maxlength="100"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Address:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><textarea name="address" rows="5" cols="50" style="font-family:arial;font-size:10px;width:200px"><?=$str_address?></textarea></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Amount per sale $:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="amount" type="text" style="font-family:arial;font-size:10px;width:100px" value="<?=$str_amount?>" maxlength="15"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"><strong><?=$str_username?></strong></font></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_password?>" maxlength="50"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Retype Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="repassword" type="text" style="font-family:arial;font-size:10px;width:200px" ></td>
		  </tr>
		</table>
		  </td>
		  </tr>
		  <tr><td align="center">&nbsp;&nbsp;&nbsp;<input type="image" id="adduser" src="images/submit.jpg"></input>
		   </td>
		</tr>
		</table>
	</form>
	</td>
	</tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
    </tr>
	 </table>
	 </td>
	</tr>
</table>

<?php
}
include("includes/footer.php");
?>
<?php
function func_user_exists($username,$cnn_connection)
{
	$i_returnstring = 0;
	$qry_select_user = "Select userid from cs_companyusers where username = '".$username."'";
	$rst_select_user = mysql_query($qry_select_user,$cnn_connection);
	if (mysql_num_rows($rst_select_user)>0)
	{
		$i_returnstring = 1;
	}
	return $i_returnstring;
}

?>