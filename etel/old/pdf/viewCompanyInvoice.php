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
include("includes/header.php");
require_once("includes/function.php");
$headerInclude="transactions";

$bank_ids = array();
$sql = "SELECT bank_id FROM cs_bank WHERE bk_ignore=0 order by bank_id asc";
$res = sql_query_read($sql) or dieLog(mysql_error());
while($bankInfo = mysql_fetch_assoc($res))
	$bank_ids[] = $bankInfo['bank_id'];
	
$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$companyId = $sessionlogin;

//print_r($_REQUEST);
require_once("includes/projSetCalc.php");

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$msg = "Invalid Invoice";

if($_REQUEST['focus'] == 'invoice')
{
	
	$mi_ID = $_REQUEST['mi_ID'];
	$invoice_sql="select * from cs_merchant_invoice where mi_ID ='$mi_ID'";
	$result=mysql_query($invoice_sql,$cnn_cs) or dieLog("Cannot execute query");
	if($invoiceDetails = mysql_fetch_assoc($result))
	{
		$mi_pay_info = unserialize($invoiceDetails['mi_pay_info']);
		$mi_company_info = unserialize($invoiceDetails['mi_company_info']);
		$companyId = $mi_company_info['userId'];
		$msg = $invoiceDetails['mi_title'];
	}
	$forcomp = "Invoice ".$forcomp;
} 
else if($_REQUEST['focus'] == 'payDay' && $_REQUEST['thisdate']>0)
{
	$nextPayDay = strtotime(pushBackOnePeriod());
	$mi_pay_info=calcReal(intval($_REQUEST['thisdate']));
	$date=date("F j, Y",$_REQUEST['thisdate']);
	$msg = "Projected Payday";
	$forcomp = $msg." ".$forcomp;
	$msg .= " for $date";
}
else if($_REQUEST['focus'] == 'projSet' && $_REQUEST['thisdate']>0)
{
	$last_pay_info=calcReal($_REQUEST['thisdate']-($companyInfo['cd_payperiod'])*(60*60*24));
	$mi_pay_info=calcReal($_REQUEST['thisdate']);
	$mi_pay_info['NewBalance']=$mi_pay_info['ProjectedSales']-$last_pay_info['ProjectedSales'];
	$mi_pay_info['Rollover']=$last_pay_info['ProjectedSales'];
	$mi_pay_info['Balance']=0;
	$date=date("F j, Y",$_REQUEST['thisdate']);
	$mi_company_info = $companyInfo;
	$msg = "Projected Balance Estimate";
	$forcomp = $msg." ".$forcomp;
	$msg .= " for $date";
}
else $forcomp = "Information ".$forcomp;

// List Reports

ob_start();
?>
<select name="mi_ID" size="5" id="mi_ID">
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally("select mi_ID, mi_title from `cs_merchant_invoice` where mi_company_id = '$companyId' ORDER BY `mi_date` DESC ",$mi_ID,$cnn_cs); ?>
</select>
<BR>
<input type="hidden" name="focus" value="invoice">
<input name="Submit" type="submit" value="View Invoice">
<?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","");

//$mi_pay_info = calcReal();

?>
  <style type="text/css">
<!--
.style1 {font-size: 10px}
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
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View <?=$forcomp?>
    </span></td>
    <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
    <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
    <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
  </tr>
  <tr>
    <td class="lgnbd" colspan="5" height="10"><table align="center">
        <tr>
          <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '<?=formatMoney( $mi_company_info['cd_rollover'])?>', then the amount will roll over to this pay period. Likewise, if this Pay period's Balance is under '<?=formatMoney( $mi_company_info['cd_rollover'])?>', it will be rolled over to the next period.</font></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
        <tr align="center" valign="middle" height='20' bgcolor="#999999">
          <td height="20" class="whitehd">&nbsp;<span class="style5"><?=$msg?></span></td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  
		  
		  <form name="paycompany" method="post" action="">
		  <input type="hidden" value="<?=$companyid?>">
		  <table width="100%" border="0" class="invoice">
            <tr bgcolor="#CCCCCC">
              <td bgcolor="#CCCCCC" class="infoHeader">Date</td>
              <td class="infoHeader"><?=$date?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Sales</td>
              <td>
                <?=($mi_pay_info['Sales']==0?"- None -":formatMoney($mi_pay_info['Sales']-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr height="20">
              <td></td>
              <td></td>
            </tr>
			<?php if($_REQUEST['focus'] != 'projSet') { ?>
            <tr class="infoSubSection">
              <td>Refund Reserve</td>
              <td>
                <?=($mi_pay_info['RefundReserveRate']==0?"- None -":formatMoney($mi_pay_info['RefundReserveRate']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td>Gross</td>
              <td>
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Transaction Fees</td>
              <td>
                <?=($mi_pay_info['TransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['TransactionFees']))?>
              </td>
            </tr>
            <!--tr class="infoSubSection">
              <td>Customer Transaction Fees</td>
              <td>
                <?=($mi_pay_info['CustomerTransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr-->
            <tr class="infoSubSection">
              <td>Reserve</td>
              <td>
                <?=($mi_pay_info['ReserveFees']==0?"- None -":formatMoney(-$mi_pay_info['ReserveFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Discount Fees</td>
              <td>
                <?=($mi_pay_info['DiscountFees']==0?"- None -":formatMoney(-$mi_pay_info['DiscountFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Refunds</td>
              <td>
                <?=($mi_pay_info['Refunds']==0?"- None -":formatMoney(-$mi_pay_info['Refunds']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Chargebacks</td>
              <td>
                <?=($mi_pay_info['Chargebacks']==0?"- None -":formatMoney(-$mi_pay_info['Chargebacks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Voided Checks</td>
              <td>
                <?=($mi_pay_info['VoidChecks']==0?"- None -":formatMoney(-$mi_pay_info['VoidChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Returned Checks</td>
              <td>
                <?=($mi_pay_info['ReturnedChecks']==0?"- None -":formatMoney(-$mi_pay_info['ReturnedChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Refund Fees</td>
              <td>
                <?=($mi_pay_info['RefundFees']==0?"- None -":formatMoney(-$mi_pay_info['RefundFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Chargeback Fees</td>
              <td>
                <?=($mi_pay_info['ChargebackFees']==0?"- None -":formatMoney(-$mi_pay_info['ChargebackFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td>Main Deductions</td>
              <td>
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']+$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Monthly Fee</td>
              <td>
                <?=($mi_pay_info['MonthlyFee']==0?"- None -":formatMoney(-$mi_pay_info['MonthlyFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Wire Fee</td>
              <td>
                <?=($mi_pay_info['WireFee']==0?"- None -":formatMoney(-$mi_pay_info['WireFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Setup Fee</td>
              <td >
                <?=($mi_pay_info['SetupFee']==0?"- None -":formatMoney(-$mi_pay_info['SetupFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Misc Fee/Credit</td>
              <td>
                <?=($mi_pay_info['MiscFees']==0?"- None -":formatMoney($mi_pay_info['MiscFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td>Fee Deductions</td>
              <td>
                <?=($mi_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$mi_pay_info['EtelDeductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Gross</td>
              <td >
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Deductions</td>
              <td >
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']+$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Fee Deductions</td>
              <td >
                <?=($mi_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$mi_pay_info['EtelDeductions']))?>
              </td>
            </tr>
			<?php } ?>
			<? if($mi_pay_info['Rollover']) { ?>
            <tr>
              <td class="infoBold">Rollover</td>
              <td class="infoBold"><?=($mi_pay_info['Rollover']==0?"- None -":formatMoney($mi_pay_info['Rollover']))?>
              </td>
            </tr>
			<? } ?>
			<? if($mi_pay_info['NewBalance']!==NULL) { ?>
            <tr>
              <td class="infoBold">New Sales</td>
              <td class="infoBold"><?=($mi_pay_info['NewBalance']==0?"- None -":formatMoney($mi_pay_info['NewBalance']))?>
              </td>
            </tr>
			<? } ?>
            <tr>
              <td class="infoBold">Balance</td>
              <td class="infoBold"><?=($invoiceDetails['mi_balance']==0?"- None -":formatMoney($invoiceDetails['mi_balance']))?>
              </td>
            </tr>
			<?php if($_REQUEST['focus'] != 'projSet') { ?>
            <tr height="25">
              <td class="infoBold">Status</td>
              <td class='<?=$invoiceDetails['mi_status']?>'><?=$invoiceDetails['mi_status']?>
              </td>
            </tr>
			<?php if($_REQUEST['focus'] == 'invoice') { ?>
            <tr height="25">
              <td class="infoBold">Notes</td>
              <td class="info"><?=$invoiceDetails['mi_notes']?></td>
            </tr>
			<?php } ?>
			<? } ?>
          </table>
		  </form></td>
        </tr>
     
    </table></td>
  </tr>
  <tr>
    <td width="1%"><img src="images/menubtmleft.gif"></td>
    <td width="2000%" background="images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
    <td width="1%" ><img src="images/menubtmright.gif"></td>
  </tr>
</table></div>
  <p>&nbsp;  </p>
<p>

<?php 



include("includes/footer.php");
?>