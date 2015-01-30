<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com	 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewSelectReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

$headerInclude="reseller";
include("includes/header.php");

include("includes/message.php");

$which = "A";

$companytype = isset($_GET['companymode'])?$_GET['companymode']:"A";
if($companytype == 'active_reseller') {
	$which = "AC";
	$search_reseller_active_status = " AND completed_reseller_application=1 ";
}
if($companytype == 'nonactive_reseller') {
	$which = "IN";
	$search_reseller_active_status = " AND completed_reseller_application=0 ";
}


$qrt_select_allreseller="Select distinct reseller_id , reseller_companyname from cs_resellerdetails where 1 $search_reseller_active_status order by reseller_companyname";
?>
<script language="javascript">
function Displaycompanytype() {
	document.FrmMerchant.action="viewSelectReseller.php";
	document.FrmMerchant.submit();
	//document.location.href="?companymode="+document.FrmMerchant.companymode.value;
}


</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr><td width="83%" valign="top" align="center"  >
<br>	
<form action="viewReseller.php" method="GET" name="FrmMerchant" >
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View Reseller</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	  <table height="100%" width="100%" cellspacing="0" cellpadding="0"><tr><td  width="100%" valign="center" align="center">
		  <table width="400" border="0" cellpadding="0"  height="100">
                      <br>
			 				<tr>
				 <td height="30" valign="middle" align="right"><font face="verdana" size="1">Company Type :</font></td>
				 <td align="left"  width="350" valign="middle" >&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 230px" onChange="Displaycompanytype();">
				<option value="reseller">All Resellers</option>
				<option value="active_reseller" >Active Resellers</option>
				<option value="nonactive_reseller" >Non Active Resellers</option>
					</select>
					<script>
					document.FrmMerchant.companymode.value='<?=$companytype?>';
					</script></td>
				</tr>
			  <tr>
				        <td align="right" valign="center" height="60" width="150"><font face="verdana" size="1">All 
                          Resellers &nbsp;</font></td>
				        <td align="left" height="60" width="250">&nbsp; 
                          <select id="all" name="reseller_id[]" style="font-family:arial;font-size:10px;width:210px" multiple>
<?php					func_fill_combo_conditionally($qrt_select_allreseller); ?>						
				</select>
				</td>
			  </tr>
			  <tr>
				<td align="center" valign="center" height="30" colspan="2"> 
				  <input type="image" name="add" id="view" SRC="<?=$tmpl_dir?>/images/view.jpg"></td>
			  </tr>
			</table>
	  </td></tr>
	  </table>
	  </td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table>
	</form>
    </td>
     </tr>
</table>
<?php

include("includes/footer.php");
?>