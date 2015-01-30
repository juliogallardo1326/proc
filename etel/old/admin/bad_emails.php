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
// bad_emails.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");

$headerInclude = "mail";
include("includes/header.php");

include("includes/message.php");

$i_user_id = (isset($HTTP_POST_VARS["hid_user_id"])?quote_smart($HTTP_POST_VARS["hid_user_id"]):"");
$str_email = (isset($HTTP_POST_VARS["hid_email"])?quote_smart($HTTP_POST_VARS["hid_email"]):"");

if ($i_user_id == "") {
	$str_query = "select a.user_id, b.companyname, b.email from cs_bad_emails a, cs_companydetails b where a.user_id = b.userId and b.gateway_id = -1 order by b.companyname";
	if(!($rstSelect =mysql_query($str_query)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($rstSelect) == 0) {
		$msgtodisplay="No bad emails";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	}
}
?>
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">
function editWindow(userId, email) {
	var obj_form = document.bad_emails_form;
	obj_form.hid_user_id.value = userId;
	obj_form.hid_email.value = email;
	obj_form.submit();
}

function validateEmail() {
	var obj_form = document.bad_emails_form;
	if (obj_form.email.value == "") {
		alert("Please enter an Email");
		obj_form.email.focus();
		return false;
	} else if(!func_isEmail(obj_form.email.value)) {
		alert("Please enter a valid Email");
		obj_form.email.focus();
		return false;
	}
	return true;
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
<tr>
   <td width="95%" valign="top" align="center" ><br>

<?php
if ($i_user_id == "") {
?>
<table width="68%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Invalid Emails</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5"><br>
<form name="bad_emails_form" action="bad_emails.php" method="post">
<table  cellpadding='0' cellspacing='0' width='100%'  align="center" ID='Table1'>
	<tr height='20' bgcolor='#CCCCCC'>
	<td align='left' class='cl1'><span class="subhd">&nbsp;Sl. No:</span></td>
	<td align='left' class='cl1'><span class="subhd">&nbsp;Company</span></td>
	<td align='left' class='cl1'><span class="subhd">&nbsp;Email</span></td>
	<td align='center' class='cl1' width="70"><span class="subhd">&nbsp;Edit</span></td>
	</tr>
	<?php
		for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++)
		{ 
			$i_user_id = mysql_result($rstSelect,$iLoop,0);
			$str_company_name = mysql_result($rstSelect,$iLoop,1);
			$str_email_id = mysql_result($rstSelect,$iLoop,2);
		?>
			<tr height='30' >
				<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;<?= ($iLoop + 1) ?></font></a></td>
				<td align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<?= $str_company_name ?></font></td>
				<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?= $str_email_id ?></font></td>
				<td align='center' class='cl1' ><font face='verdana' size='1'>&nbsp;<a href="Javascript:editWindow('<?= $i_user_id?>', '<?= $str_email_id?>');">Edit</a></font></td>
				</tr>
	<?php	
		}
	?>
	</table>
	<input type="hidden" name="hid_user_id">
	<input type="hidden" name="hid_email">
	</form>
	</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table> 
<?php
} else {
?>
<form name="bad_emails_form" action="update_email.php" method="post" onSubmit="Javascript:return validateEmail()">
<table width="50%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit Email</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5"><br>
<table cellpadding='0' cellspacing='0' width='70%'  align="center" ID='Table1' height="100">
	<tr height='20'>
	<td align='left'><font size="1" face="verdana">Enter New Email Id</font></td>
	<td align='left'><input type="text" name="email" value="<?= $str_email?>" style="font-family:verdana;font-size:10px;width:220px"></td>
	</tr>
	<tr><td align="center" colspan="10" height="50" valign="middle"><input type="image" border="0" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td></tr>
	</table>
	</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	<input type="hidden" name="hid_user_id" value="<?= $i_user_id?>">
	</form>
	</table> 
<?php
}
?>
	<br>
   </td>
</tr>
</table> 
<?php
include("includes/footer.php");
?>
