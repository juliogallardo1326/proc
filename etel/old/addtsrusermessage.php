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
$headerInclude="tsr";
include("includes/topheader.php");
include("includes/message.php");
$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$msg = (isset($HTTP_GET_VARS["msg"])?trim($HTTP_GET_VARS["msg"]):"");
$sShowMessage = "";
if ( $msg == "add" ) {
	$sShowMessage = "TSR user added successfully";
}
if ( $msg == "edit" ) {
	$sShowMessage = "TSR user edited successfully";
}
if ( $msg == "delete" ) {
	$sShowMessage = "TSR user deleted successfully";
}
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print $sShowMessage; ?></font>
</td></tr></table></td></tr>
</table>
<tr>
<td width="1%"><img src="images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif"></td>
</tr>
</td></tr>
</table>
</td></tr>
</table>

<?php
	include("includes/footer.php");
?>
