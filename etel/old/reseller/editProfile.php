<?php

$headerInclude="profile";
include('application.php');
die();

include ("includes/sessioncheck.php");
$headerInclude="profile";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php");
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
if($resellerLogin!="")
{
	

	$login = (isset($HTTP_GET_VARS['login'])?Trim($HTTP_GET_VARS['login']):"");
	if($login)
	{
		$email = $HTTP_GET_VARS['email'];
		$oldemail = $HTTP_GET_VARS['oldemail'];

		$phone = (isset($HTTP_GET_VARS['phone'])?Trim($HTTP_GET_VARS['phone']):"");
		$address = (isset($HTTP_GET_VARS['address'])?Trim($HTTP_GET_VARS['address']):"");
			$show_sql =mysql_query("update cs_resellerdetails set reseller_email='$email',reseller_phone='$phone',reseller_address='$address' where reseller_id=$resellerLogin");
			$msgtodisplay="Profile has been changed for reseller.";			
	}


	$qry_select_user = "select reseller_username,reseller_companyname,reseller_contactname,reseller_email,reseller_address,reseller_phone from cs_resellerdetails where reseller_id=$resellerLogin";
	if(!($show_sql = mysql_query($qry_select_user,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}	
	if($show_val = mysql_fetch_array($show_sql)) 
	{	
		$usernameO = $show_val[0];
		$companyname = $show_val[1];
		$conatctname = $show_val[2];
		$reselleremail = $show_val[3];
	}	
?>
<script language="javascript">
function validation(){ 
  
  if(document.Frmlogin.email.value==""){
    alert("Please enter the email address")
    document.Frmlogin.email.focus();
	return false;
  }
   if (document.Frmlogin.email.value  != "") 
	{
		if (document.Frmlogin.email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmlogin.email.focus();
			return(false);
		}
	}
	
	if (document.Frmlogin.email.value  != "") 
	{
		if (document.Frmlogin.email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.Frmlogin.email.focus();
			return(false);
		}
	}
	
	if (document.Frmlogin.email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.Frmlogin.email.focus();
		return(false);
	}
  if(document.Frmlogin.confirmemail.value==""){
    alert("Please confirm the new email address")
    document.Frmlogin.confirmemail.focus();
	return false;
  }
  if(document.Frmlogin.email.value != document.Frmlogin.confirmemail.value){
    alert("Please enter correct email address")
    document.Frmlogin.confirmemail.focus();
	return false;
  }
}
</script>
<script language="javascript" src="../scripts/general.js"></script>

 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr><td width="83%" valign="top" align="center"  >
<br>	<form action="editProfile.php" method="GET" onsubmit="return validation()" name="Frmlogin" >
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit&nbsp;Profile</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
		<?php
			if(!isset($invalidlogin))
			{
				$invalidlogin = "";
			}	
		?>
	  <table height="100%" width="100%" cellspacing="0" cellpadding="0"><tr><td  width="100%" valign="center" align="center">
		  <table width="400" border="0" cellpadding="0"  height="100">	  <?=$invalidlogin?>
			          <tr align="center" valign="middle"> 
                        <td height="30" colspan="2"><font face="verdana" size="1" color="#FF0000">&nbsp;</font></td>
</tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Company Name &nbsp;</font></td><td align="left" height="30" width="250"><font face="verdana" size="1"><B><?=$companyname?></B></font></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Contact Name &nbsp;</font></td><td align="left" height="30" width="250"><font face="verdana" size="1"><B><?=$conatctname?></B></font></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">User Name &nbsp;</font></td><td align="left" height="30" width="250"><font face="verdana" size="1"><B><?=$usernameO?></B></font></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">New Email Address &nbsp;</font></td><td align="left" height="30" width="250"><input name="email" type="text" style="font-family:arial;font-size:10px;width:160px" value="<?=$show_val['reseller_email']?>"></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Confirm Email Address &nbsp;</font></td><td align="left" height="30" width="250"><input name="confirmemail" type="text" style="font-family:arial;font-size:10px;width:160px" value="<?=$show_val['reseller_email']?>"></td></tr>
			  <tr>
			    <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Phone&nbsp;</font></td>
			    <td align="left" height="30" width="250"><input name="phone" type="text" id="phone" style="font-family:arial;font-size:10px;width:160px" value="<?=$show_val['reseller_phone']?>"></td></tr>
			  <tr>
			    <td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Address&nbsp;</font></td>
			    <td align="left" height="30" width="250"><input name="address" type="text" id="address" style="font-family:arial;font-size:10px;width:160px" value="<?=$show_val['reseller_address']?>"></td></tr>

			  <tr><td align="center" valign="center" height="30" colspan="2">
			   <input type="image" name="add" id="useaccount" src="../images/submit.jpg"></td></tr>
			   <input type="hidden" name="login" value="login">
		  </table>
	  </td></tr>
	  </table>
	  <br>
	  </td>
      </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table></form>
    </td>
     </tr>
</table>
<?php
include("includes/footer.php");
}
?>	
