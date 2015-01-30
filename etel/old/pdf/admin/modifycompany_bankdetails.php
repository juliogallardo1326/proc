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
// bankcompany.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude="bank1";
include("includes/header.php");

include("includes/message.php");


?>
<script language="JavaScript">
function funcSubmit() {
	objForm	=	document.frmBank;
	sBank	=	objForm.cboBanks.options[objForm.cboBanks.selectedIndex].value;
	if ( sBank != "" ) {
		objForm.method	=	"post";
		objForm.action	=	"company_banklist.php?id="+sBank;
		objForm.submit();
	
	}
}	
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="63%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="50%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Modify Bank 
            Details </span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" width="987" >
		<form name="frmBank" action="#"  method="GET">
		<table border="0" cellpadding="3" cellspacing="3" align="center" width="90%">
			<tr>
				  <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Name</font></td>
				<td>
				<select name="cboBanks" style="width:200">
				<option value="">Select Bank</option>
				<?php
					func_fill_combo_conditionally("select bank_id,bank_name from cs_bank where 1 ","",$cnn_cs);
				?>
				</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<a href="javascript:funcSubmit()"><img SRC="<?=$tmpl_dir?>/images/submit.jpg" alt="submit" border="0"></a>
				</td>
			</tr>
			</table>
		</form>
		</td>
	</tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
	</table>
    </td>
    </tr>
</table>
<?php 
include("includes/footer.php");
?>