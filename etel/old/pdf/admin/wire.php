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
//ledger.php:	The admin page functions for selecting the type of report view  for the company. 
include("includes/sessioncheck.php");

$headerInclude="ledgers";
$periodhead="Ledgers";
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$action="reportBottomSummary.php";


?>
<script language="JavaScript">
function func_popup_invoice(par_id)
{
   	advtWnd=window.open("view_invoice.php?id="+par_id+"","advtWndName","'status=1,scrollbars=1,width=700,height=650,left=0,top=0");
	advtWnd.focus();
}
function func_popup_wire(par_id)
{
   	advtWnd=window.open("view_wireinstruction.php?id="+par_id+"","advtWndName","'status=1,scrollbars=1,width=400,height=500");
	advtWnd.focus();
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table width="70%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Wire</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" align="center">
		<form name="wire" action="#" method="POST" onsubmit="return validation();">
		<br>
<?php	if($sessionAdmin!="")
		{
			$qry_selectcompany = "Select userId,companyname,transaction_type from cs_companydetails where 1 and completed_uploading='Y' and activeuser=1 order by transaction_type, companyname";
			$rst_selectcompany = mysql_query($qry_selectcompany,$cnn_cs);
			if (mysql_num_rows($rst_selectcompany) != 0) { ?>
				<table width="100%" border="1" cellspacing="0" cellpadding="0">
				<tr bgcolor="#CCCCCC"> 
				  <td width="50%" height="20" align="center" valign="middle"><span class="subhd">Company Name</span></td>
				  <td width="20%" align="center" valign="middle"><span class="subhd">Company Type</span></td>
				  <td width="10%" align="center" valign="middle"><span class="subhd">Owed $</span></td>
				  <td width="10%" align="center" valign="middle"><span class="subhd">Invoice</span></td>
				  <td width="10%" align="center" valign="middle"><span class="subhd">Wire Instruction</span></td>
				</tr>
				<?
				for($i=0;$i<mysql_num_rows($rst_selectcompany);$i++)
				{
					$iUserId = mysql_result($rst_selectcompany,$i,0);
					$strCompanyName = mysql_result($rst_selectcompany,$i,1);
					$strCompanyType = func_get_merchant_name(mysql_result($rst_selectcompany,$i,2));
	?>	
				<tr> 
				  <td height="25" valign="middle"><font face="verdana" size="1"><?=$strCompanyName?></font></td>
				  <td height="25" valign="middle"><font face="verdana" size="1"><?=$strCompanyType?></font></td>
				  <td height="25" align="center" valign="middle"><font face="verdana" size="1">&nbsp;</font></td>
				  <td align="center" valign="middle"><font face="verdana" size="1"><a href="javascript:func_popup_invoice(<?=$iUserId?>)">view</a></font></td>
				  <td align="center" valign="middle"><font face="verdana" size="1"><a href="javascript:func_popup_wire(<?=$iUserId?>)">view</a></font></td>
				</tr>
	<?php   	} ?>
			</table>
		<?	} else { ?>
				<center><font face="verdana" size="2"><b>No Companies to display</b></font></center>
			<? } ?>
        </form>
	</td>
 </tr>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table><br>
</td>
</tr>
</table>
	
<?php
include("includes/footer.php");
}		
?>