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
$headerInclude="ordermanagement";
include("includes/topheader.php");
include("includes/message.php");
$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($_SESSION["sessionlogin_type"] == "tele")
{
	$i_userid="";
	$str_username="";
	$str_password="";
	
	$i_userid=isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"";
	if ($i_userid!="" && is_numeric($i_userid))
	{
		$qry_getdetails = "Select user_name,password from cs_telemarketingusers where user_id=$i_userid";
		$rst_getdetails = mysql_query($qry_getdetails,$cnn_cs);
		if(mysql_num_rows($rst_getdetails)>0)
		{
			$str_username=mysql_result($rst_getdetails,0,0);
			$str_password=mysql_result($rst_getdetails,0,1);
		}
	
	}
?>
<script language="JavaScript" >
function validation() {
	trimSpace(document.adduser.username)
	if (document.adduser.username.value ==""){
		alert("Please enter the User name.");
		document.adduser.username.focus();
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
		<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Add&nbsp;
		  User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="adduser" action="addtsruserfb.php"  method="post" onsubmit="javascript:return validation();">
	 <input type="hidden" name="companyid" value="<?=$sessioncompanyid?>">
	 <input type="hidden" name="userid" value="<?=$i_userid?>">
	 <input type="hidden" name="hid_username" value="<?=$str_username?>">
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="username" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_username?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_password?>"></td>
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
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
      </tr>
	 <tr><td>
	<br>
	<table width="100%" valign="top" align="left" class="lgnbd" cellspacing="1">
<tr bgcolor="#78B6C2">
			    <td><span class="subhd">No.</span></td>
				<td><span class="subhd">User Name</span></td>
			    <td><span class="subhd">Password</span></td>
			    <td><span class="subhd">Edit</span></td>
			    <td><span class="subhd">Delete</span></td>
		</tr>
<?php
		$qry_select = "Select user_id,user_name,password from cs_telemarketingusers where user_type='0' and company_id=$sessioncompanyid";
		$rst_select = mysql_query($qry_select,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$i = 0;
			for ($i_loop=0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
			{
			$i=$i+1;
				$i_userid = mysql_result($rst_select,$i_loop,0);
				$str_username = mysql_result($rst_select,$i_loop,1);
				$str_password = mysql_result($rst_select,$i_loop,2);	
?>
			<tr>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$str_username?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$str_password?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="addtsruser.php?uid=<?=$i_userid?>">Edit</a></font></td>
			<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="deleteteleuser.php?uid=<?=$i_userid?>">Delete</a></font></td>
			</tr>
<?php		}
		}
?>
		</table>
		</td></tr>
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