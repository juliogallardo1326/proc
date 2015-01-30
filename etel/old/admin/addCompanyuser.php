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

$headerInclude="companies";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$edit_username ="";
	$edit_password ="";
	$companyid = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
	$userIdVal = (isset($HTTP_GET_VARS["userid"])?$HTTP_GET_VARS["userid"]:"");
	$username = (isset($HTTP_GET_VARS["username"])?$HTTP_GET_VARS["username"]:"");
	$password =  (isset($HTTP_GET_VARS["password"])?$HTTP_GET_VARS["password"]:"");
	$repassword =  (isset($HTTP_GET_VARS["repassword"])?$HTTP_GET_VARS["repassword"]:"");
	$uid = 	(isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"");
	$Mode = 	(isset($HTTP_GET_VARS["Mode"])?$HTTP_GET_VARS["Mode"]:"");
	$Mode_Type = (isset($HTTP_GET_VARS["Mode_Type"])?$HTTP_GET_VARS["Mode_Type"]:"");
	$Edit_id = (isset($HTTP_GET_VARS["Edit_id"])?$HTTP_GET_VARS["Edit_id"]:"");
	
	if ($companyid==""){
		$companyid =(isset($HTTP_GET_VARS['companyid'])?quote_smart($HTTP_GET_VARS['companyid']):"");
	}
	if(($userIdVal !="") && ($username !="") && ($password!="")) 
	{
		if ($password != $repassword ) 
		{
			$msgtodisplay="Please enter the correct passwords.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	   
		} 
		else 
		{
			if($Mode_Type=="Edit") 
			{
				$qry_update_user = "Update cs_companyusers set username='$username',password='$password' where id=$Edit_id";
				if(!($show_sql =mysql_query($qry_update_user)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} 
				else 
				{
					$username="New details of user name ".$username." has been set";		
					$outhtml="y";
					message($username,$outhtml,$headerInclude);					
					exit();
				}
			} else {
				
				if(!func_user_exists($username,$cnn_cs))
				{
					$qry_insert_user = "insert into cs_companyusers (userid,username,password) values($userIdVal,'$username','$password')";
					if(!($show_sql =mysql_query($qry_insert_user)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

					}
					else
					{
						$username="User name ".$username." has been added";		
						$outhtml="y";
						message($username,$outhtml,$headerInclude);					
						exit();
					}
				}
				else
				{
					$msg="The username ".$username." already exists";		
					$outhtml="y";
					message($msg,$outhtml,$headerInclude);					
					exit();
				}
			}
		}
	}
	
	if ($Mode == "Delete") {
		$qry_delete_user = "Delete from cs_companyusers where id=$uid";
		if(!($show_sql =mysql_query($qry_delete_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");
		
		}
		else
		{
			$username="User name ".$username." has been deleted";		
			$outhtml="y";
			message($username,$outhtml,$headerInclude);					
			exit();
		}
	}
	
	if(!$companyid){
		$msgtodisplay="Select a company name";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	}
	
	$qry_select_users ="select id,username,password  from cs_companyusers where userid=$companyid order by id";
	if(!($show_sql_qry =mysql_query($qry_select_users)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	
	if ($uid !="") {
		$qry_select_user = "select username,password from cs_companyusers where id=$uid";
		if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else
		{
			while($edit_val = mysql_fetch_array($show_sql)) 
			{
				$edit_username = $edit_val[0];
				$edit_password = $edit_val[1];
			}
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
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Add&nbsp;
		  User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
		<form name="adduser" action="addcompanyuser.php"  method="GET" onsubmit="javascript:return validation();">
		<input type="hidden" name="userid" value="<?=$companyid?>"></input>
		<input type="hidden" name="Mode_Type" value="<?=$Mode?>"></input>
		<input type="hidden" name="Edit_id" value="<?=$uid?>"></input>
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="username" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$edit_username?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$edit_password?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Retype Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="repassword" type="text" style="font-family:arial;font-size:10px;width:200px" ></td>
		  </tr>
		</table>
		  </td>
		  </tr>
		  <tr><td align="center">&nbsp;&nbsp;&nbsp;<input type="image" id="adduser" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input>
		   </td>
		</tr>
		</table>
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
	<table width="100%" valign="top" align="left" class="lgnbd" cellspacing="1">
<tr bgcolor="#CCCCCC">
			    <td><span class="subhd">No.</span></td>
				<td><span class="subhd">User Name</span></td>
			    <td><span class="subhd">Password</span></td>
			    <td><span class="subhd">Edit</span></td>
			    <td><span class="subhd">Delete</span></td>
		</tr>
<?php
		$i=0;
		while($show_val = mysql_fetch_array($show_sql_qry)) 
		{
			$i=$i+1;	
?>
			<tr>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[1]?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[2]?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="addcompanyuser.php?Mode=Edit&uid=<?=$show_val[0]?>&companyid=<?=$companyid?>">Edit</a></font></td>
			<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="addcompanyuser.php?Mode=Delete&uid=<?=$show_val[0]?>&companyid=<?=$companyid?>">Delete</a></font></td>
			</tr>
<?php
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