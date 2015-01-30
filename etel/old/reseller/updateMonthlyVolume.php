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
// viewcompanyNext.php:	This admin page functions for displaying the company details. 

include ("includes/sessioncheck.php");
require_once("../includes/function.php");
include("includes/message.php");

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$company_id = isset($HTTP_GET_VARS['companyId'])?$HTTP_GET_VARS['companyId']:"";
$companyIdValue = isset($HTTP_POST_VARS["company"])?trim($HTTP_POST_VARS["company"]):"";
$volume = isset($HTTP_POST_VARS["volume"])?trim($HTTP_POST_VARS["volume"]):"";
if($companyIdValue !="") {
	$update_merchant_fees = "Update cs_companydetails set volumenumber ='$volume' where userId=$companyIdValue ";
	if(!($show_sql = mysql_query($update_merchant_fees)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<script>
opener.document.location.reload();
window.close();
</script>
<?php 
}
?>
<body leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr  bgcolor='#78B6C2'>
  	 <td colspan="2" align="center" height="20" valign="middle"><font face="verdana" size="1" color="#FFFFFF"><strong>Select Merchant Volume</strong></font></td>
  </tr>
  <form name="FrmMerchant" method="post" action="updateMonthlyVolume.php" >
   <input type="hidden" name="company" value="<?=$company_id?>">
  <tr>
    <td align="right" height="50" valign="middle"><font face="verdana" size="1">Merchant Volume</font>&nbsp;</td>
    <td>&nbsp;<select name="volume" style="font-family:arial;font-size:10px;width:120px">
<?php		func_select_merchant_volume(''); ?>
		 </select>
	</td>
  </tr>
  <tr>
    <td colspan="2" height="50" align="center" valign="middle"><input	type="image" src="../images/submit.jpg"></td>
  </tr>
  </form>
</table>
</body>