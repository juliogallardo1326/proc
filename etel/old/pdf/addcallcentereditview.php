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
?>
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
		<table border="0" cellspacing="0" cellpadding="0" height="100%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Add Call Center User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="adduser" action="addcallcenteruserfb.php"  method="post" onsubmit="javascript:return validation();">
	 <input type="hidden" name="companyid" value="<?=$sessioncompanyid?>">
	 <table width="100%" valign="top" align="left" class="lgnbd" cellspacing="1">
	 <tr bgcolor="#78B6C2">
		<td><span class="subhd">No.</span></td>
		<td><span class="subhd">Company Name</span></td>
		<td><span class="subhd">Contact no</span></td>
		<td><span class="subhd">Username</span></td>
		<td><span class="subhd">Edit</span></td>
		<td><span class="subhd">Delete</span></td>
	</tr>
<?php
	$qry_select = "select cc_usersid,comany_name,company_conatct_no,address,amount,user_name,user_password from cs_callcenterusers where company_id=$sessioncompanyid";
	$rst_select = mysql_query($qry_select,$cnn_cs);
	if (mysql_num_rows($rst_select)>0)
	{
		for($i=0;$i<mysql_num_rows($rst_select);$i++)
		{
			$i_cc_userid = mysql_result($rst_select,$i,0);
			$str_company_name = mysql_result($rst_select,$i,1);
			$str_conatctno = mysql_result($rst_select,$i,2);
			$str_username = mysql_result($rst_select,$i,5);
	?>
			<tr>
				<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i+1?></font></td>
				<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$str_company_name?></font></td>
				<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$str_conatctno?></font></td>
				<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$str_username?></font></td>
				<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="addtsruser.php?uid=<?=$i_userid?>">Edit</a></font></td>
				<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="deleteteleuser.php?uid=<?=$i_userid?>">Delete</a></font></td>
			</tr>
<?php 	}
	}else{
		print "<tr><td colspan='6' class='ltbtbd'><font face='verdana' size='1'>No Records found in the database</font></td></tr>";
	}
?>
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