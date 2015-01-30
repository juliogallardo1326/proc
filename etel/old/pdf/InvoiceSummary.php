<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// resellerLedger.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';

require_once( 'includes/function.php');
include 'includes/function1.php';
$str_adminapproval="";
$sessionlogin = $companyInfo['userId'];
$str_company_id = $companyInfo['userId'];

$thisdate=$_GET['paydate'];

include ("includes/projSetCalc.php");

$projSettlement="";
$projSettlementPeriods="";
projSetCalc();
$thisdate_id=intval(date("ymd",$thisdate));

	?>
<style type="text/css">
<!--
.style1 {font-size: 10px;}
.style4 {font-size: 12px; font-weight: bold; }
.style5 {
	color: #FFFFFF;
}
.style7 {
	font-size: 12px;
	color: #CC3300;
	font-weight: bold;
}
-->
  </style>
<p>&nbsp;</p>
<div align="center">
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Invoice
        <?=$forcomp?>
        </span></td>
      <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
      <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
      <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
    </tr>
    <tr>
      <td class="lgnbd" colspan="5" height="10"><table align="center">
          <tr>
            <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '
              <?=formatMoney( $companyInfo['cd_rollover'])?>
              ', then the amount will roll over to this pay period. Likewise, if this Pay period's Balance is under '
              <?=formatMoney( $companyInfo['cd_rollover'])?>
              ', it will be rolled over to the next period.</font></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
          <tr align="center" valign="middle" height='20' bgcolor="#448A99">
            <td height="20" class="whitehd">&nbsp;</td>
          </tr>
          <tr align="center" valign="middle" height='20' >
            <td height="20" class="whitehd"><table width="100%" border="0">
                <tr bgcolor="#78B6C2">
                  <td width="157" bgcolor="#78B6C2"><div align="right" class="style5">Date</div></td>
                  <td width="46"></td>
                  <td width="275"><span class="style5">
                    <?=date("l - F j, Y",$thisdate)?>
                    </span></td>
                </tr>
                <tr height="20">
                  <td width="157"></td>
                  <td width="46"></td>
                  <td width="275"></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style1">Net Applied </span></div></td>
                  <td width="46"></td>
                  <td><span class="style1">
                    <?=($projSettlement[$thisdate_id]['Net']==0?"- None -":formatMoney($projSettlement[$thisdate_id]['Net']))?>
                    </span></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style1">Roll Over </span></div></td>
                  <td width="46"></td>
                  <td><span class="style1">
                    <?=($projSettlement[$thisdate_id]['rollover']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['rollover']))?>
                    </span></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style1">Monthly Fee </span></div></td>
                  <td width="46"></td>
                  <td><span class="style1">
                    <?=($projSettlement[$thisdate_id]['monthlyfee']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['monthlyfee']))?>
                    </span></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style1">Wire Fee </span></div></td>
                  <td width="46"></td>
                  <td><span class="style1">
                    <?=($projSettlement[$thisdate_id]['wirefee']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['wirefee']))?>
                    </span></td>
                </tr>
                <tr>
                  <td><div align="right"><span class="style4">Balance </span></div></td>
                  <td width="46"></td>
                  <td><span class="style4">
                    <?=($projSettlement[$thisdate_id]['balance']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['balance']))?>
                    </span></td>
                </tr>
                <tr height="25">
                  <td><div align="right"><span class="style4">Status</span></div></td>
                  <td width="46"></td>
                  <td><span class="style7">
                    <?=$projSettlement[$thisdate_id]['invoice']?>
                    </span></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td width="1%"><img src="images/menubtmleft.gif"></td>
      <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
      <td width="1%" ><img src="images/menubtmright.gif"></td>
    </tr>
  </table>
</div>
<p>&nbsp; </p>
<p>
  <?php 
include("includes/footer.php");
?>
