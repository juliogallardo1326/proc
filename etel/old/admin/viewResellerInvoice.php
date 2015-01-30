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
//print_r($_REQUEST);


$ri_ID = $_REQUEST['ri_ID'];
$ri_status = quote_smart($_REQUEST['status']);

if($ri_status && $_REQUEST['action']=='changestatus' && $ri_ID && $adminInfo['li_level'] == 'full')
{
	$invoice_sql="update cs_reseller_invoice set ri_status = '$ri_status' where ri_ID ='$ri_ID'";
	$result=mysql_query($invoice_sql,$cnn_cs) or dieLog("Cannot execute query");
	$msg = "Invoice #$ri_ID status changed to '$ri_status'.";
}

$invoice_sql="select * from cs_reseller_invoice where ri_ID ='$ri_ID'";
$result=mysql_query($invoice_sql,$cnn_cs) or dieLog(mysql_error()." ~ $invoice_sql");
if($invoiceDetails = mysql_fetch_assoc($result))
{
	$ri_pay_info = unserialize($invoiceDetails['ri_pay_info']);
	$ri_company_info = unserialize($invoiceDetails['ri_company_info']);
	if(!$msg) $msg = $invoiceDetails['ri_title'];
}
else $msg = "Invalid Invoice";
$reseller_id = $ri_company_info['reseller_id'];

// List Reports

ob_start();
?>

<select name="ri_ID" size="5" id="ri_ID">
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally("select ri_ID, ri_title from `cs_reseller_invoice` where ri_reseller_id = '$reseller_id' ORDER BY `ri_date` ASC ",$ri_ID,$cnn_cs); ?>
</select>
<BR>
<input type="hidden" name="companyId" value="<?=$companyId?>">
<input name="Submit" type="submit" value="View Invoice">
<?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","viewResellerInvoice.php");

//$ri_pay_info = calcReal();

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
    
<script language="javascript">
function changeStatus(ri_ID,status)
{
	document.location.href="viewResellerInvoice.php?ri_ID="+ri_ID+"&action=changestatus&status="+status;
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
          <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '<?=formatMoney( $ri_company_info['rd_rollover'])?>', then the amount will roll over to this pay period. Likewise, if this Pay period's Balance is under '<?=formatMoney( $ri_company_info['rd_rollover'])?>', it will be rolled over to the next period.</font></td>
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
              <td class="infoHeader"><?=date("l - F j, Y",strtotime($invoiceDetails['ri_date']))?>
              </td>
            </tr>
            <tr height="20">
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td >Sales</td>
              <td >
                <?=($ri_pay_info['Sales']==0?"- None -":formatMoney($ri_pay_info['Sales']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Reseller Transaction Fees</td>
              <td >
                <?=($ri_pay_info['ResTransactionFees']==0?"- None -":formatMoney($ri_pay_info['ResTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td >Gross</td>
              <td >
                <?=($ri_pay_info['Gross']==0?"- None -":formatMoney($ri_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Transaction Fees</td>
              <td >
                <?=($ri_pay_info['TransactionFees']==0?"- None -":formatMoney(-$ri_pay_info['TransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td>Customer Transaction Fees</td>
              <td>
                <?=($ri_pay_info['CustomerTransactionFees']==0?"- None -":formatMoney(-$ri_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Reserve</td>
              <td >
                <?=($ri_pay_info['ReserveFees']==0?"- None -":formatMoney(-$ri_pay_info['ReserveFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Discount Fees</td>
              <td >
                <?=($ri_pay_info['DiscountFees']==0?"- None -":formatMoney(-$ri_pay_info['DiscountFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Refunds</td>
              <td >
                <?=($ri_pay_info['Refunds']==0?"- None -":formatMoney(-$ri_pay_info['Refunds']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Chargebacks</td>
              <td >
                <?=($ri_pay_info['Chargebacks']==0?"- None -":formatMoney(-$ri_pay_info['Chargebacks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Voided Checks</td>
              <td >
                <?=($ri_pay_info['VoidChecks']==0?"- None -":formatMoney(-$ri_pay_info['VoidChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Returned Checks</td>
              <td >
                <?=($ri_pay_info['ReturnedChecks']==0?"- None -":formatMoney(-$ri_pay_info['ReturnedChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Refund Fees</td>
              <td >
                <?=($ri_pay_info['RefundFees']==0?"- None -":formatMoney(-$ri_pay_info['RefundFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Chargeback Fees</td>
              <td >
                <?=($ri_pay_info['ChargebackFees']==0?"- None -":formatMoney(-$ri_pay_info['ChargebackFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td >Main Deductions</td>
              <td >
                <?=($ri_pay_info['Deductions']==0?"- None -":formatMoney(-$ri_pay_info['Deductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Monthly Fee</td>
              <td >
                <?=($ri_pay_info['MonthlyFee']==0?"- None -":formatMoney(-$ri_pay_info['MonthlyFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Wire Fee</td>
              <td >
                <?=($ri_pay_info['WireFee']==0?"- None -":formatMoney(-$ri_pay_info['WireFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Setup Fee</td>
              <td >
                <?=($ri_pay_info['SetupFee']==0?"- None -":formatMoney(-$ri_pay_info['SetupFee']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td >Fee Deductions</td>
              <td >
                <?=($ri_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$ri_pay_info['EtelDeductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Gross</td>
              <td >
                <?=($ri_pay_info['Gross']==0?"- None -":formatMoney($ri_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Deductions</td>
              <td >
                <?=($ri_pay_info['Deductions']==0?"- None -":formatMoney(-$ri_pay_info['Deductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Fee Deductions</td>
              <td >
                <?=($ri_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$ri_pay_info['EtelDeductions']))?>
              </td>
            </tr>
            <tr>
              <td class="infoBold">Balance</td>
              <td class="infoBold">
                <?=($ri_pay_info['Balance']==0?"- None -":formatMoney($ri_pay_info['Balance']))?>
              </td>
            </tr>
            <tr height="25">
              <td class="infoBold">Status</td>
              <td class='<?=$ri_pay_info['Status']?>'>
                <?=$ri_pay_info['Status']?> 
              </td>
            </tr>
            <tr class="infoSubSection">
              <td >Invoice Status</td>
              <td>
				<?php if($adminInfo['li_level'] == 'full') { ?>
				<select name="" onChange="changeStatus('<?=$ri_ID?>',this.value)">
				  <?=func_get_enum_values('cs_reseller_invoice','ri_status',$invoiceDetails['ri_status']) ?>
				</select>
				<? } ?>
				</td>
              <td>&nbsp;</td>
            </tr>
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