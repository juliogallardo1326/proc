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
$display_stat_wait = true;
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";
$headerInclude="transactions";

//print_r($_REQUEST);
require_once("../includes/projSetCalc.php");

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$mi_ID = quote_smart($_REQUEST['mi_ID']);
$mi_status = quote_smart($_REQUEST['mi_status']);
$mi_notes = quote_smart($_REQUEST['mi_notes']);
$mi_notes = str_replace('\r\n',"\n",$mi_notes);
if($mi_status && $_REQUEST['action']=='Update Invoice' && $mi_ID)
{
	$send_invoice_confirm = intval($_POST['send_invoice_confirm']);
	$invoice_sql="update cs_merchant_invoice set mi_status = '$mi_status', mi_notes = '$mi_notes' where mi_ID ='$mi_ID'";
	$result=sql_query_write($invoice_sql) or dieLog(mysql_error()." ~ $invoice_sql");
	$msg = "Invoice #$mi_ID Updated.";
	if($send_invoice_confirm)
	{
		$invoice_sql="select * from cs_merchant_invoice left join cs_companydetails on userId = mi_company_id where mi_ID ='$mi_ID'";
		$result=sql_query_read($invoice_sql) or dieLog(mysql_error()." ~ $invoice_sql");
		$invoiceInfo = mysql_fetch_assoc($result);

		$emailData = array();
		
		$emailData["companyname"] = $invoiceInfo['companyname'];
		$emailData["username"] = $invoiceInfo['username'];
		$emailData["password"] = $invoiceInfo['password'];
		$emailData["Reference_ID"] = $invoiceInfo['ReferenceNumber'];
		$emailData["email"] = $invoiceInfo['email'];
		$emailData["wiredate"] = $invoiceInfo['mi_paydate'];
		$emailData["mi_title"] = $invoiceInfo['mi_title'];
		$emailData["mi_deduction"] = $invoiceInfo['mi_deduction'];
		$emailData["mi_status"] = $mi_status;
		$emailData["mi_balance"] = $invoiceInfo['mi_balance'];
		$emailData["mi_notes"] = $mi_notes;
		
		if($mi_status == 'WireFailure') 
			send_email_template('merchant_wire_failure',$emailData);
		else
			send_email_template('merchant_wire_success',$emailData);	
	}
}

if($_REQUEST['action']=='reverse' && $mi_ID && $adminInfo['li_level'] == 'full' && $etel_debug_mode)
{
	$response = reverseCompanyInvoice(intval($mi_ID));
	if($response) $msg = "Invoice #$mi_ID has been reversed successfully.";
	else $msg = "Invoice #$mi_ID could not be reversed. It may not exist.";
} 
else
{
	$invoice_sql="select * from cs_merchant_invoice where mi_ID ='$mi_ID'";
	$result=sql_query_read($invoice_sql,$cnn_cs) or dieLog("Cannot execute query");
	if($invoiceDetails = mysql_fetch_assoc($result))
	{
		$mi_pay_info = unserialize($invoiceDetails['mi_pay_info']);
		$mi_company_info = unserialize($invoiceDetails['mi_company_info']);
		$companyId = $mi_company_info['userId'];
		if(!$msg) $msg = $invoiceDetails['mi_title'];
	}
	else $msg = "Invalid Invoice";
}
// List Reports

ob_start();
?>
<select name="mi_ID" size="5" id="mi_ID">
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally("select mi_ID, mi_title from `cs_merchant_invoice` where mi_company_id = '$companyId' ORDER BY `mi_date` DESC ",$mi_ID,$cnn_cs); ?>
</select>
<BR>
<input type="hidden" name="companyId" value="<?=$companyId?>">
<input name="Submit" type="submit" value="View Invoice">
<?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","viewCompanyInvoice.php");

//$mi_pay_info = calcReal();

?>

    
<script language="javascript">
function reverseInvoice(mi_ID)
{
	if(!confirm("Are you sure you want to delete this invoice and reverse all calculated data affiliated with it?"))
		return 0;
	document.location.href="viewCompanyInvoice.php?mi_ID="+mi_ID+"&action=reverse";
	
}
function changeStatus(mi_ID,status)
{
	document.location.href="viewCompanyInvoice.php?mi_ID="+mi_ID+"&action=changestatus&status="+status;
}

</script>

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
              <td bgcolor="#CCCCCC" class="infoHeader"><?=$mi_pay_info['RangeInfo']?></td>
              <td class="infoHeader">Invoice Created On <?=date("l - F j, Y",strtotime($invoiceDetails['mi_date']))?>
              </td>
            </tr>
            <tr height="20">
              <td></td>
              <td></td>
            </tr>
            <tr class="infoSection">
              <td>Sales</td>
              <td><?=($mi_pay_info['Sales']==0?"- None -":formatMoney($mi_pay_info['Sales']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Sales</td>
              <td>
                <?=($mi_pay_info['Sales']==0?"- None -":formatMoney($mi_pay_info['Sales']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Refund Reserve</td>
              <td>
                <?=($mi_pay_info['RefundReserveRate']==0?"- None -":formatMoney($mi_pay_info['RefundReserveRate']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td>Gross</td>
              <td>
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Transaction Fees</td>
              <td>
                <?=($mi_pay_info['TransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['TransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Customer Transaction Fees</td>
              <td>
                <?=($mi_pay_info['CustomerTransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
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
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']))?>
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
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Deductions</td>
              <td >
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Fee Deductions</td>
              <td >
                <?=($mi_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$mi_pay_info['EtelDeductions']))?>
              </td>
            </tr>
			<?php if($_REQUEST['focus'] == 'invoice') { ?>
            <tr height="25">
              <td class="info">Notes</td>
              <td class="info"><?=$invoiceDetails['mi_notes']?></td>
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
			<?php if($_REQUEST['focus'] != 'projSet') { ?>
            <tr>
              <td class="infoBold">Balance</td>
              <td class="infoBold"><?=($invoiceDetails['mi_balance']==0?"- None -":formatMoney($invoiceDetails['mi_balance']))?>
              </td>
            </tr>
			<?php foreach ($mi_pay_info['BankInfo'] as $k=>$d) {?>
            <tr class="infoSubSection">
              <td ><?=$d['bank_name']?></td>
              <td>
                <?=($d['Balance']==0?"- None -":formatMoney($d['Balance']))?>
				<?php if($d['bk_payout_support']) { ?>
				<input name="" type="button" value="Payout File" class="infoSubSection" onClick="document.location.href='PayoutGenerate.php?mib_ID=<?=$d['mib_ID']?>'">
				<? } ?>
				</td>
            </tr>
			<? } ?>
            <tr height="25">
              <td class="infoBold">Status</td>
              <td class='<?=$invoiceDetails['mi_status']?>'><?=ucfirst($invoiceDetails['mi_status'])?>
              </td>
            </tr>
            <tr height="25">
              <td class="infoBold">Notes</td>
              <td class='info'><textarea name="mi_notes" cols="40" rows="5"><?=$invoiceDetails['mi_notes']?></textarea>
              </td>
            </tr>
			<? } ?>
			<?php if($bankInfo['bk_payout_support'] && 0) { ?>
            <tr height="25">
              <td class="infoBold">Generate Payout</td>
              <td class="infoBold">
                <?="<a href='payCompany.php?companyId=$companyId&mode=paycompany' >Generate</a>"?>
              </td>
            </tr>
			<?php } ?>
            <tr class="infoSubSection">
              <td >Invoice Status</td>
              <td>
				<?php if($adminInfo['li_level'] == 'full') { ?>
				<select name="mi_status">
				  <?=func_get_enum_values('cs_merchant_invoice','mi_status',$invoiceDetails['mi_status']) ?>
				</select>
              
                <br />
                <input name="action" type="submit" value="Update Invoice" class="infoSubSection"><input type="hidden" name="mi_ID" value="<?=$mi_ID?>" />
                <input name="send_invoice_confirm" type="checkbox" value="1" checked="checked" />
                Send Conf Email

				<? } ?>
				</td>
              <td>&nbsp;</td>
            </tr>
			<?php if($adminInfo['li_level'] == 'full' && $etel_debug_mode) { ?>
            <tr class="infoSubSection">
              <td >Reverse Invoice</td>
              <td>
				<input name="" type="button" value="Reverse Invoice" class="infoSubSection" onClick="reverseInvoice('<?=$mi_ID?>')">

				</td>
              <td>&nbsp;</td>
            </tr>
			<? } ?>
          </table>
		  </form></td>
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