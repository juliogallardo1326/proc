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
include("includes/sessioncheck.php");
$headerInclude="ledgers";
$allowBank=true;
$display_stat_wait = true;
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?Trim($HTTP_GET_VARS['ptype']):"";
$headerInclude="transactions";

//print_r($_REQUEST);
require_once("../includes/projSetCalc.php");

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$bi_ID = $_REQUEST['bi_ID'];
$bank_id = intval($_REQUEST['bank_id']);
$bi_date = intval($_REQUEST['bi_date']);
$bi_pay_info = unserialize(stripslashes($_REQUEST['bi_pay_info']));


if ($adminInfo['li_level'] == 'bank') $bi_bank_id_sql = " and bi_bank_id = '".$adminInfo['li_bank']."' ";

$invoice_sql="select * from cs_bank_invoice where bi_ID ='$bi_ID' $bi_bank_id_sql";



$result=mysql_query($invoice_sql,$cnn_cs) or dieLog(mysql_error());
if($invoiceDetails = mysql_fetch_assoc($result))
{
	$bi_pay_info = unserialize($invoiceDetails['bi_pay_info']);
	$bi_bank_info = unserialize($invoiceDetails['bi_bank_info']);
	$bankId = $bi_bank_info['bank_id'];
	$bi_date = strtotime($invoiceDetails['bi_date']);

	$msg = $invoiceDetails['bi_title'];
}
else if(is_array($bi_pay_info))
{
	if ($adminInfo['li_level'] == 'full') $bank_id = intval($_REQUEST['bank_id']);
	$bank_id_sql = " and bank_id = '$bank_id' ";
	$qry_company="SELECT * FROM `cs_bank` WHERE 1 $bank_id_sql";
	$bank_details=mysql_query($qry_company,$cnn_cs) or dieLog(mysql_error());
	$bi_bank_info=mysql_fetch_assoc($bank_details);
	$bankId = $bi_bank_info['bank_id'];
	$msg = "Estimated Profit for ".date("l - F j, Y",$bi_date);
}
else $msg = "Invalid Invoice";
// List Reports


if ($adminInfo['li_level'] == 'full') {
	beginTable();
	?>

	<select name="bi_ID" size="5" id="bi_ID">
	  <option value="">Select an Invoice</option>
	  <?php func_fill_combo_conditionally("select bi_ID, bi_title from `cs_bank_invoice` where bi_bank_id = '$bankId' ORDER BY `bi_date` ASC ",$bi_ID,$cnn_cs); ?>
	</select>
	<BR>
	<input name="Submit" type="submit" value="View Invoice">
	<?php
	endTable("Invoice History","viewBankInvoice.php");
	$bi_pay_info['BankCreditReserveFees']+=$bi_pay_info['BankCreditDiscountFees'];
}

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
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View Invoice <?=$forcomp?>
    </span></td>
    <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
    <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
    <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
  </tr>
  <tr>
    <td class="lgnbd" colspan="5" height="10"><table align="center" class="invoice">
        <tr class="info">
          <td >* If Previous Pay period's Balance is under '<?=formatMoney( $bi_bank_info['bk_rollover'])?>', then the amount will roll over to this pay period. Likewise, if this Pay period's Balance is under '<?=formatMoney( $bi_bank_info['bk_rollover'])?>', it will be rolled over to the next period.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
        <tr align="center" valign="middle" height='20' bgcolor="#448A99">
          <td height="20" class="whitehd">&nbsp;<span class="style5"><?=$msg?></span></td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  <?php if($bankId) { ?>
		  <table width="100%" border="0" class="invoice">
            <tr bgcolor="#78B6C2">
              <td bgcolor="#78B6C2" class="infoHeader"><?=date("l - F j, Y",$bi_date)?></td>
              <td class="infoHeader">Amount              </td>
              <td class="infoHeader">Rate</td>
            </tr>
            <tr class="infoSection">
              <td>Processing Total</td>
              <td><?=($bi_pay_info['Sales']==0?"- None -":formatMoney($bi_pay_info['Sales']))?> (<?=intval($bi_pay_info['Approve_Num'])?> apr)
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr class="infoSection">
              <td>Refunds Total</td>
              <td><?=($bi_pay_info['RefundAmount']==0?"- None -":formatMoney($bi_pay_info['RefundAmount']))?> (<?=intval($bi_pay_info['Refund_Num'])?> ref)
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr class="infoSection">
              <td>Rolling Reserve</td>
              <td><?=($bi_pay_info['RollingReserve']==0?"- None -":formatMoney($bi_pay_info['RollingReserve']))?>
              </td>
              <td>&nbsp;</td>
            </tr>
			<tr class="infoSubSection">
              <td>Discount Fees</td>
              <td> <?=($bi_pay_info['DiscountFees']==0?"- None -":formatMoney($bi_pay_info['DiscountFees']))?> (<?=intval($bi_pay_info['Approve_Num'])?>)</td>
              <td>-</td>
            </tr>
			<tr class="infoSubSection">
              <td>Merchant Transaction Fees</td>
              <td><?=($bi_pay_info['TransactionFees']==0?"- None -":formatMoney($bi_pay_info['TransactionFees']))?> (<?=intval($bi_pay_info['Total_Num'])?>)</td>
              <td>-</td>
            </tr>
            <tr class="infoSubSection">
              <td>Customer Transaction Fees</td>
              <td><?=($bi_pay_info['CustomerTransactionFees']==0?"- None -":formatMoney($bi_pay_info['CustomerTransactionFees']))?> (<?=intval($bi_pay_info['Approve_Num'])?>)</td>
              <td>-</td>
            </tr>
			<tr class="infoSubSection">
              <td>Refund Fees</td>
              <td><?=($bi_pay_info['RefundFees']==0?"- None -":formatMoney($bi_pay_info['RefundFees']))?> (<?=intval($bi_pay_info['Refund_Num'])?>)</td>
              <td>-</td>
            </tr>
            <tr class="infoSubSection">
              <td>Chargeback Fees</td>
              <td><?=($bi_pay_info['ChargebackFees']==0?"- None -":formatMoney($bi_pay_info['ChargebackFees']))?> (<?=intval($bi_pay_info['Chargeback_Num'])?>)</td>
              <td>-</td>
            </tr>
            <tr class="infoSubSection">
              <td><?=$_SESSION['gw_title']?> Fees (Setup, Monthly, WireFees)</td>
              <td><?=($bi_pay_info['EtelDeductions']==0?"- None -":formatMoney($bi_pay_info['EtelDeductions']))?> </td>
              <td>-</td>
            </tr>
            <tr class="infoSection">
              <td>Gross Profit from Processing</td>
              <td><?=($bi_pay_info['Profit']==0?"- None -":formatMoney($bi_pay_info['Profit']))?>
              </td>
              <td>&nbsp;</td>
            </tr>

			<tr class="infoSubSection">
              <td>Bank Approved Transaction Fees</td>
              <td><?=($bi_pay_info['BankApproveTransFee']==0?"- None -":formatMoney(-$bi_pay_info['BankApproveTransFee']))?> (<?=intval($bi_pay_info['Approve_Num'])?>)</td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_approve'])?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Non-Approved Transaction Fees</td>
              <td><?=($bi_pay_info['BankNonApproveTransFee']==0?"- None -":formatMoney(-$bi_pay_info['BankNonApproveTransFee']))?> (<?=intval($bi_pay_info['BankDecline_Num'])?>)</td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_decline'])?>
              </td>
            </tr>
			<tr class="infoSubSection">
              <td>Bank High-Risk Discount Fees</td>
              <td><?=($bi_pay_info['BankHighRiskDiscountFees']==0?"- None -":formatMoney(-$bi_pay_info['BankHighRiskDiscountFees']))?> (<?=intval($bi_pay_info['BankHighRiskDiscountFees_Num'])?>)</td>
              <td>
                <?=formatMoney( $bi_bank_info['bk_fee_high_risk'])?>%
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Low-Risk Discount Fees Fees</td>
              <td><?=($bi_pay_info['BankLowRiskDiscountFees']==0?"- None -":formatMoney(-$bi_pay_info['BankLowRiskDiscountFees']))?> (<?=intval($bi_pay_info['BankLowRiskDiscountFees_Num'])?>)</td>
              <td>
                <?=formatMoney( $bi_bank_info['bk_fee_low_risk'])?>%
              </td>
            </tr>
			<tr class="infoSubSection">
              <td>Bank Refund Fees</td>
              <td><?=($bi_pay_info['BankRefundFees']==0?"- None -":formatMoney(-$bi_pay_info['BankRefundFees']))?> (<?=intval($bi_pay_info['BankRefund_Num'])?>)</td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_refund'])?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Chargeback Fees</td>
              <td><?=($bi_pay_info['BankChargebackFees']==0?"- None -":formatMoney(-$bi_pay_info['BankChargebackFees']))?> (<?=intval($bi_pay_info['BankChargeback_Num'])?>)</td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_chargeback'])?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Payout Wire Fees</td>
              <td><?=($bi_pay_info['BankCompanyWireFee']==0?"- None -":formatMoney(-$bi_pay_info['BankCompanyWireFee']))?></td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_us_wire'])?>/$<?=formatMoney( $bi_bank_info['bk_fee_nonus_wire'])?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Payroll Discount Fees</td>
              <td><?=($bi_pay_info['BankPayrollDiscount']==0?"- None -":formatMoney(-$bi_pay_info['BankPayrollDiscount']))?></td>
              <td>
                <?=formatMoney( $bi_bank_info['bk_payroll_discount'])?>%
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Bank Credit Reserve Fees</td>
              <td><?=($bi_pay_info['BankCreditReserveFees']==0?"- None -":formatMoney(-$bi_pay_info['BankCreditReserveFees']))?> (<?=intval($bi_pay_info['BankCreditReserveFees_Num'])?>)</td>
              <td>
                -
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Reseller Discount Fees</td>
              <td><?=($bi_pay_info['ResellerDiscountFees']==0?"- None -":formatMoney(-$bi_pay_info['ResellerDiscountFees']))?> (<?=intval($bi_pay_info['Approve_Num'])?>)</td>
              <td>
                -
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Reseller Transaction Fees</td>
              <td><?=($bi_pay_info['ResellerTransactionFees']==0?"- None -":formatMoney(-$bi_pay_info['ResellerTransactionFees']))?> (<?=intval($bi_pay_info['Approve_Num'])?>)</td>
              <td>
                -
              </td>
            </tr>
            <tr class="infoSection">
              <td><?=$bi_bank_info['bank_name']?> Deductions</td>
              <td><?=($bi_pay_info['Deductions']==0?"- None -":formatMoney(-$bi_pay_info['Deductions']))?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr class="infoSubSection">
              <td><?=$_SESSION['gw_title']?> Profit</td>
              <td><?=($bi_pay_info['Profit']==0?"- None -":formatMoney($bi_pay_info['Profit']))?></td>
              <td>-</td>
            </tr>
			<tr class="infoSubSection">
              <td>Bank Deductions </td>
              <td><?=($bi_pay_info['Deductions']==0?"- None -":formatMoney(-$bi_pay_info['Deductions']))?></td>
              <td>-</td>
            </tr>
            <tr class="infoSubSection">
              <td>Wire Fee</td>
              <td><?=($bi_pay_info['WireFee']==0?"- None -":formatMoney(-$bi_pay_info['WireFee']))?>
              </td>
              <td>
                $<?=formatMoney( $bi_bank_info['bk_fee_us_wire'])?></td>
            </tr>
            <tr class="infoSection">
              <td>Total Profit </td>
              <td class='<?=$bi_pay_info['Status']?>'><?=($bi_pay_info['TotalProfit']==0?"- None -":formatMoney($bi_pay_info['TotalProfit']))?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr class="infoSection">
              <td>Status</td>
              <td class='<?=$bi_pay_info['Status']?>'><?=$bi_pay_info['Status']?>
              </td>
              <td>&nbsp;</td>
            </tr>
            <tr class="infoSubSection">
              <td >Generate Payout File</td>
              <td>
				<?php if($bi_bank_info['bk_payout_support']) { ?>
				<input name="" type="button" value="Payout File  (<?=$invoiceDetails['bi_download_count']?>)" class="infoSubSection" onClick="document.location.href='BankPayoutGenerate.php?bi_ID=<?=$bi_ID?>'">
				<? } ?>
				</td>
            </tr>
          </table>
		  <?php } ?>
		  </td>
        </tr>

    </table></td>
  </tr>
  <tr>
    <td width="1%"><img src="images/menubtmleft.gif"></td>
    <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
    <td width="1%" ><img src="images/menubtmright.gif"></td>
  </tr>
</table></div>
  <p>&nbsp;  </p>
<p>

<?php



include("includes/footer.php");
?>