<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// dnc_emails.php:	This admin page functions for adding emails to which the DNC's will be sent. 
include("includes/sessioncheck.php");

$headerInclude="emailReceipts";	
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$str_dnc_email = (isset($HTTP_GET_VARS["dncEmail"])?$HTTP_GET_VARS["dncEmail"]:"");
	$str_self_email = (isset($HTTP_GET_VARS["dncSelfEmail"])?$HTTP_GET_VARS["dncSelfEmail"]:"");
	$str_mode = (isset($HTTP_GET_VARS["mode"])?$HTTP_GET_VARS["mode"]:"Add");
	$i_dnc_id = (isset($HTTP_GET_VARS["dncId"])?$HTTP_GET_VARS["dncId"]:"");
	$str_action = (isset($HTTP_GET_VARS["action"])?$HTTP_GET_VARS["action"]:"");
	if($str_action == "submit") 
	{
		if($str_mode == "Add") {
			if(func_dnc_email_exists($str_dnc_email,"",$cnn_cs)) {
				$message = "email id '".$str_dnc_email."' already exists.";		
				$outhtml="y";
				message($message,$outhtml,$headerInclude);					
				exit();
			}
			$qry_insert = "Insert into cs_dnc_emails (dnc_email) values ('$str_dnc_email')"; 
			if(!($show_sql =mysql_query($qry_insert)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			} 
			else 
			{
				$message = "New DNC email id '".$str_dnc_email."' has been added.";		
				$outhtml="y";
				message($message,$outhtml,$headerInclude);					
				exit();
			}
		} else if($str_mode == "Edit") {
				if(func_dnc_email_exists($str_dnc_email,$str_self_email,$cnn_cs)) {
					$message = "email id '".$str_dnc_email."' already exists.";		
					$outhtml="y";
					message($message,$outhtml,$headerInclude);					
					exit();
				}
				$qry_insert = "Update cs_dnc_emails set dnc_email = '$str_dnc_email' where dnc_id = $i_dnc_id"; 
				if(!($show_sql =mysql_query($qry_insert)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} 
				else 
				{
					$message = "DNC email id '".$str_self_email."' has been modified to '".$str_dnc_email."'.";		
					$outhtml="y";
					$str_dnc_email = "";
					$str_mode = "";
					message($message,$outhtml,$headerInclude);					
					exit();
				}

		} else if($str_mode == "Delete") {
				$qry_insert = "Delete from cs_dnc_emails where dnc_id = $i_dnc_id"; 
				if(!($show_sql =mysql_query($qry_insert)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} 
				else 
				{
					$message = "DNC email id '".$str_dnc_email."' has been deleted.";		
					$outhtml="y";
					$str_dnc_email = "";
					$str_mode = "";
					message($message,$outhtml,$headerInclude);					
					exit();
				}

		}
	}
	$qry_select ="select dnc_id, dnc_email from cs_dnc_emails where 1 order by dnc_email";
	if(!($show_sql =mysql_query($qry_select)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}

?>
<script language="JavaScript" src="../scripts/general.js"></script>
<script language="JavaScript" >
function validation() {
	isValid = true;
	trimSpace(document.addDNCEmail.dncEmail)
	if (document.addDNCEmail.dncEmail.value ==""){
		alert("Please enter the email id.");
		document.addDNCEmail.dncEmail.focus();
		isValid = false;
	}
	return isValid;
}

function confirmDelete(dncId, dncEmail) {
	if(window.confirm("Are you sure you want to delete this email id?")) {
		self.location = "dnc_emails.php?mode=Delete&action=submit&dncId="+dncId+"&dncEmail="+dncEmail;
	}
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0" width="80%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd"><?= $str_mode == "Edit" ? "Edit DNC EMail" : "Add DNC Email" ?></span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
		<form name="addDNCEmail" action="dnc_emails.php"  method="GET" onsubmit="javascript:return validation();">
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="center" height="30" width="40%"><font face="verdana" size="1">Email Id:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="60%"><input name="dncEmail" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_dnc_email?>"></td>
		  </tr>
		</table>
		  </td>
		  </tr>
		  <tr><td align="center" height="40" valign="bottom">&nbsp;&nbsp;&nbsp;<input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input>
		   </td>
		</tr>
		</table>
		<input type="hidden" name="dncId" value="<?=$i_dnc_id?>"></input>
		<input type="hidden" name="mode" value="<?=$str_mode?>">
		<input type="hidden" name="action" value="submit">
		<input type="hidden" name="dncSelfEmail" value="<?=$str_dnc_email?>">
	</form>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
      </tr>
	 <tr><td>
	<br>
	<table width="80%" valign="top" align="left" class="lgnbd" cellspacing="1">
		<tr bgcolor="#CCCCCC">
			    <td><span class="subhd">No.</span></td>
				<td><span class="subhd">Emai Id</span></td>
			    <td><span class="subhd">Edit</span></td>
			    <td><span class="subhd">Delete</span></td>
		</tr>
<?php
		$i=0;
		while($show_val = mysql_fetch_array($show_sql)) 
		{
			$i=$i+1;	
?>
			<tr>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[1]?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="dnc_emails.php?mode=Edit&dncId=<?=$show_val[0]?>&dncEmail=<?=$show_val[1]?>">Edit</a></font></td>
			<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="javascript:confirmDelete('<?=$show_val[0]?>','<?=$show_val[1]?>')">Delete</a></font></td>
			</tr>
<?php
		}
		if ($i == 0) {
?>
			<tr>
			<td valign="middle" align="center" class="ltbtbd" colspan="4"><font face="verdana" size="1">No DNC emails to display</font></td>
			</tr>
<?
		}
?>
		</table>
		</td></tr>
    </table>
	   </td>
	</tr>
</table>

<?php
include("includes/footer.php");
}

function func_dnc_email_exists($email,$self_email,$cnn_connection)
{
	$b_exists = false;
	$qry_select = "Select dnc_email from cs_dnc_emails where dnc_email = '".$email."' and dnc_email <> '".$self_email."'";
	$rst_select = mysql_query($qry_select,$cnn_connection);
	if (mysql_num_rows($rst_select)>0)
	{
		$b_exists = true;
	}
	return $b_exists;
}

?>