<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//home.php:	        The page functions for selecting the transaction type for the company. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude="home";	
include 'includes/topheader.php';
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser = isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
if($sessionlogin == "")
{
	$sessionlogin = $sessionCompanyUser;
}	
$qry_select_company = "select companyname from cs_companydetails where userid=$sessionlogin";
   if(!($show_sql = mysql_query($qry_select_company)))
   {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

   }
   else
   {
      if($showval = mysql_fetch_array($show_sql)) {
           $companyname=$showval[0];
	  }
	}

?>
<script language="javascript">
	function callpayment() {
		if(document.payment.paytype.options[document.payment.paytype.selectedIndex].value=="cheque"){
			document.payment.action="rebill_cheque.php";
			document.payment.submit();
		}else if(document.payment.paytype.options[document.payment.paytype.selectedIndex].value=="credit"){
			document.payment.action="rebill_creditcard.php";
			document.payment.submit();
		}
	}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Virtual&nbsp;Terminal</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
       <td valign="top" align="center" class="lgnbd" colspan="5">
 	 <form name="payment" method="get">
	          <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" style="text-indent:30px" height="30" valign="middle">&nbsp;</td>
                  <td align="left"  height="30" valign="middle">&nbsp;</td>
                </tr>
                <tr> 
                  <td align="left" style="text-indent:30px" height="30" valign="middle" width="225"> 
                    <font face="verdana" size="2">Select the Payment Method</font></td>
                  <td align="left"  height="30" valign="middle"><font face="verdana" size="2"> 
                    <select name="paytype" onchange="callpayment()">
                      <option value="Xchoose">Select One</option>
                      <option value="cheque">Check </option>
                      <option value="credit">Credit Card</option>
                    </select>
                    </font> </td>
                </tr>
              </table>
	</form>
	</td>
	</tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif" height="11"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif" height="11"></td>
	</tr>
</table>
</td>
</tr>
</table>
<?php
include("includes/footer.php");

?>	

