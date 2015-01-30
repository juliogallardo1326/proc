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
// Addmail.php:	This admin page functions for adding  the emails to the company. 
include("includes/sessioncheck.php");

$headerInclude="emailReceipts";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$edit_username ="";
	$edit_password ="";
	$emailid="";
	$Mode ="";
	$Mode_Type = "";
	$company_id = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
	$userIdVal = (isset($HTTP_GET_VARS["companyid"])?$HTTP_GET_VARS["companyid"]:"");
	
	$str_userName = (isset($HTTP_GET_VARS["txt_email"])?$HTTP_GET_VARS["txt_email"]:"");
	$usernam = (isset($HTTP_GET_VARS["txt_emailaddress"])?$HTTP_GET_VARS["txt_emailaddress"]:"");
	if ($str_userName != "")
	{
		$username = $str_userName;
    }
	else
	{
		$username = $usernam;
	}
	if ($company_id != "")
	{
		$companyid = $company_id;
	}
	else
	{
		$companyid  = $userIdVal;
	}		

	if ($companyid==""){
		$companyid =(isset($HTTP_GET_VARS['companyid'])?quote_smart($HTTP_GET_VARS['companyid']):"");
	}
	$Mode = 	(isset($HTTP_GET_VARS["Mode"])?$HTTP_GET_VARS["Mode"]:"");
	$userid  = 	(isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"");
	$Mode_Type = (isset($HTTP_GET_VARS["Mode_Type"])?$HTTP_GET_VARS["Mode_Type"]:"");
	$Edit_id = (isset($HTTP_GET_VARS["Edit_id"])?$HTTP_GET_VARS["Edit_id"]:"");
	$flag = 0;
	$str_getemail = func_select_email($username,$companyid);
	$i_totalemail = func_select_totemail($companyid);
	
		
	$qry_select_users ="select id,emailaddress,userid from cs_orderemail where userid=$companyid and gateway_id = -1";
	if(!($show_sql_qry =mysql_query($qry_select_users)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if ($Mode == "Edit")
	{
	    $qry_select_mail = "SELECT id,emailaddress FROM cs_orderemail where id = " .$userid. "";
		$rssel_qry = mysql_query($qry_select_mail);
		$flag = 1;
		if (mysql_num_rows($rssel_qry)>0)
		{
			$id_val = mysql_result($rssel_qry,0,0);
			$emailid = mysql_result($rssel_qry,0,1);
			
		}
		if ($username != "")
		{
			$qry_update = "UPDATE cs_orderemail set emailaddress = '" .$username. "' where id = " .$userid;
			mysql_query($qry_update);
			$outhtml="y";
			$Mode ="";
			$emailid ="";
			$username="Email Id has been modified";
			message($username,$outhtml,$headerInclude);					
			exit();
		}
	}else if ($username != "" && $userIdVal != "")
	{
		if ($str_getemail == 0)
		{
		//	if($i_totalemail != 5)
		//	{
				$query_insert_mail = "INSERT INTO cs_orderemail(emailaddress,userid) values('" .$username. "'," .$userIdVal. ")";
				mysql_query($query_insert_mail);
				$emailid ="";
				$outhtml="y";
				$username="Email Id has been Added";
				message($username,$outhtml,$headerInclude);					
				exit();
		/*	} else {
				$outhtml="y";
				$username="Maximum of 5 email addresses are allowed.";
				message($username,$outhtml,$headerInclude);					
				exit();
			} */	  	
		}
		else
		{
			$outhtml="y";
			$username="Email id already exist.";
			message($username,$outhtml,$headerInclude);					
			exit();
		}
	
	}
	
	if ($Mode == "Delete")
	{
		$qry_del = "DELETE FROM cs_orderemail where id = " .$userid;
		mysql_query($qry_del);
		$emailid ="";
		$Mode ="";
		$outhtml="y";
		$username= "Email Address  has been Deleted";
		message($username,$outhtml,$headerInclude);					
		exit(); 
		
	}	
	 		

?>
<script language="JavaScript" >
function validation() {
	if (document.adduser.txt_email.value=="")
		{
			alert("Enter Email Address");
			document.adduser.txt_email.focus();
			return false;			
	    }
		
		if (document.adduser.txt_email.value  != "") 
 	 {
		if (document.adduser.txt_email.value.indexOf('@')==-1) 
		{
		alert("Please enter valid email id");
		document.adduser.txt_email.focus();
		return(false);
		}
 	 }
	  if (document.adduser.txt_email.value  != "") 
  {
		if (document.adduser.txt_email.value.indexOf('.')==-1) 
		{
		alert("Please enter valid email id");
		document.adduser.txt_email.focus();
		return(false);
		}
  }
  
		
		
	}

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="80%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd"><?php if ($flag==1) { ?>Edit  
                  Email Receipt <?php } else { ?> Add Email Receipt <?php } ?></span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
		<form name="adduser" action="addmail.php"  method="GET" onsubmit="javascript:return validation();">
		<input type="hidden" name="companyid" value="<?=$companyid?>"></input>
		
		
		<input type="hidden" name="txt_emailaddress" value="<?=$username?>"></input>
	<!--	<input type="hidden" name="Mode_Type" value="<?=$Mode?>"></input>
		<input type="hidden" name="Edit_id" value="<?=$uid?>"></input>-->
		<input type="hidden" name="uid" value="<?=$userid?>"></input>
		<input type="hidden" name="Mode" value="<?=$Mode?>"></input>
		
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%"><font face="verdana" size="1">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			                  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Email 
                                Address :&nbsp;</font></td>
								
								
			<td align="left" valign="center" height="30" width="50%"><input name="txt_email" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$emailid?>"></td>
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
				<td><span class="subhd">Email Address</span></td>
			    <td><span class="subhd">Edit</span></td>
			    <td><span class="subhd">Delete</span></td>
		</tr>
<?php
		$i_Loop=0;
		while($show_val = mysql_fetch_array($show_sql_qry)) 
		{
			$i_Loop=$i_Loop+1;	
?>
			<tr>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i_Loop?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[1]?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="addmail.php?Mode=Edit&uid=<?=$show_val[0]?>&companyid=<?=$companyid?>">Edit</a></font></td>
			<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="addmail.php?Mode=Delete&uid=<?=$show_val[0]?>&companyid=<?=$companyid?>">Delete</a></font></td>
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
function func_select_email($username,$companyid)
{
	$i_returnemail = 0;
	$qry_select_email = "Select emailaddress from cs_orderemail where emailaddress = '".$username."' and userid = $companyid";
	$rst_select_email = mysql_query($qry_select_email);
	if (mysql_num_rows($rst_select_email)>0)
	{
		$i_returnemail = 1;
	}
	return $i_returnemail;

}	
function func_select_totemail($userid)
{
	$i_totemail = 0;
	$qry_select_totemail = "Select emailaddress from cs_orderemail where userid = '".$userid."'";
	$rst_select_totemail = mysql_query($qry_select_totemail);
	if (mysql_num_rows($rst_select_totemail)>0)
	{
		$i_totemail = mysql_num_rows($rst_select_totemail);
	}
	return $i_totemail;

}	
	
	
	

?>