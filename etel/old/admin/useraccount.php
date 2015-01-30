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
// useraccount.php:	The admin page functions for the company account setup.
$allowBank=true;
include("includes/sessioncheck.php");
$headerInclude="administration";
include("includes/header.php");



$backhref="useraccount.php";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$qry_select_user = "select * from cs_login where username='".$_SESSION["gw_user_username"]."'";
	if(!($show_sql = mysql_query($qry_select_user,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if($show_val = mysql_fetch_array($show_sql))
	{
		 $usernameO = $show_val['username'];
		 $passwordO = $show_val['password'];
	}

	$login = (isset($HTTP_POST_VARS['login'])?quote_smart($HTTP_POST_VARS['login']):"");
	if($login)
	{
		$password = md5($usernameO.$HTTP_POST_VARS['password']);
		$Npassword = $HTTP_POST_VARS['Npassword'];
		if($password == $passwordO)
		{
			$show_sql =mysql_query("update cs_login set password=MD5('".$_SESSION["gw_user_username"]."$Npassword') where username='".$_SESSION["gw_user_username"]."'");
			$msgtodisplay="Password has been changed for administrator ";
			$outhtml="y";
			$_SESSION["gw_user_password"] = $Npassword;
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();
		}
		else
		{
			$invalidlogin="<br><font face='verdana' size='1' color='red'>Please enter correct password in the oldpassword field !! </font>";
		 }
	}



?>
<script language="javascript">
function validation(){

  if(document.Frmlogin.password.value==""){
    alert("Please enter correct password")
    document.Frmlogin.password.focus();
	return false;
  }


  if(document.Frmlogin.Npassword.value==""){
    alert("Please enter new password")
    document.Frmlogin.Npassword.focus();
	return false;
  }
  trimSpace(document.Frmlogin.Npassword);
  if (document.Frmlogin.Npassword.value!=""&&(!func_vali_pass(document.Frmlogin.Npassword)))
		{

		alert("Special characters not allowed for password");
		document.Frmlogin.Npassword.focus();
		document.Frmlogin.Npassword.select();
		return false;
		}
  trimSpace(document.Frmlogin.Npassword);
  if(document.Frmlogin.Npassword1.value != document.Frmlogin.Npassword.value){
    alert("Please enter correct confirm password")
    document.Frmlogin.Npassword1.focus();
	document.Frmlogin.Npassword1.select();
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




function func_vali_pass(frmelement)
{ ///return true if no special chars
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


</script>

 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr><td width="83%" valign="top" align="center"  >
<br>	<form action="useraccount.php" method="post" onsubmit="return validation()" name="Frmlogin" >
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Change&nbsp;Password</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
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
		<?=$invalidlogin?>
		  <table width="400" border="0" cellpadding="0"  height="100">
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">User Name &nbsp;</font></td><td align="left" height="30" width="250"><font face="verdana" size="1"><B><?=$_SESSION["gw_user_username"]?></B></font></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Old Password &nbsp;</font></td><td align="left" height="30" width="250"><input type="password" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:140px"></td></tr>
			  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">New Password &nbsp;</font></td><td align="left" height="30" width="250"><input type="password" maxlength="30" name="Npassword" style="font-family:arial;font-size:10px;width:140px"></td></tr>
			   <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Retype Password &nbsp;</font></td><td align="left" height="30" width="250"><input type="password" maxlength="30" name="Npassword1" style="font-family:arial;font-size:10px;width:140px"></td></tr>
			  <tr><td align="center" valign="center" height="30" colspan="2">
				<input type="hidden" name="login" value="login">
			   <input type="image" name="add" id="useaccount" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td></tr>
		  </table>
	  </td></tr>
	  </table>
	  <br>
	  </td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table></form>
    </td>
     </tr>
</table>
<?php
include("includes/footer.php");
}
?>
