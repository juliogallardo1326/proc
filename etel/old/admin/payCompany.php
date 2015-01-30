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

require_once("../includes/projSetCalc.php");

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$companyId = (isset($_REQUEST["companyId"])?quote_smart($_REQUEST["companyId"]):"");
	
$qry_company="select * from cs_companydetails where userId ='$companyId'";
$gatewayid=-1;
$rst_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
$companyInfo=mysql_fetch_assoc($rst_details);
$paydate = strtotime($companyInfo['cd_next_pay_day']);
if($_REQUEST['custom_pay_day']) $paydate =strtotime($_REQUEST['custom_pay_day']);
if($_POST['Submit'] == 'Pay Company')
{
	$date_hold=0;
	$mi_notes = $_POST['mi_notes'];
	$bank_ids = $_POST['paybank'];
	$deduct_bank = $_POST['deductbank'];
	$paid = payCompany($paydate);
	$mi_ID = $paid;
	if ($paid!=-1) $msg = "Merchant Paid Successfully (Invoice #$mi_ID Created)";
	else $msg = "Error paying Merchant! ~ $error_msg";
}
unset($bank_ids);
unset($date_hold);
unset($date_delay);
$paydate = strtotime($companyInfo['cd_next_pay_day']);
$mi_pay_info = calcReal($paydate);

?>
<script language="javascript">
<!--
var BankList = new Array();;
var BankInfo = new Array();;
var EtelDeductions = '<?=$mi_pay_info['EtelDeductions']?>';
var BankInc = 0;

function payChange(obj)
{
	if(!obj.checked) document.getElementById('deductbank_'+obj.value).checked = false;
	document.getElementById('deductbank_'+obj.value).disabled = !obj.checked;
	selectGreatestPayout(-1);
}
function selectGreatestPayout(select_bank_id)
{
	sum = 0;
	bank_id = 0;
	deductsum = 0;
	for(i=0;i<BankList.length;i++)
	{
		document.getElementById('balanceBank_'+BankList[i]['bank_id']).innerHTML = BankList[i]['FormatBalance'];
		banksum = parseFloat(BankList[i]['Balance']);
		if(select_bank_id==-1 && banksum>sum && document.getElementById('paybank_'+BankList[i]['bank_id']).checked) 
		{
			sum = banksum;
			bank_id = BankList[i]['bank_id'];
			deductsum = BankList[i]['FormatBalanceDeduction'];
		}
		if(select_bank_id == BankList[i]['bank_id'])
		{
			sum = banksum;
			bank_id = BankList[i]['bank_id'];
			deductsum = BankList[i]['FormatBalanceDeduction'];
		}
	}
	
	if(bank_id==0)
	{
		document.getElementById('Submit').disabled = true;
	}
	else
	{
		document.getElementById('Submit').disabled = false;
		document.getElementById('deductbank_'+bank_id).checked = true;
		document.getElementById('balanceBank_'+bank_id).innerHTML = deductsum;
	}
}

-->
</script>
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
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Make Payment <?=$forcomp?>
    </span></td>
    <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
    <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
    <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
  </tr>
  <tr>
    <td class="lgnbd" colspan="5" height="10"><table align="center">
        <tr>
          <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '<?=formatMoney( $companyInfo['cd_rollover'])?>', then the amount will roll over to this pay period. Likewise, if this Pay Period's Balance is under '<?=formatMoney( $companyInfo['cd_rollover'])?>', it will be rolled over to the next period.</font></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
        <tr align="center" valign="middle" height='20' bgcolor="#999999">
          <td height="20" class="whitehd">&nbsp;<?=$msg?></td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  
		  
		  <form name="paycompany" method="post" action="">
		  <input type="hidden" value="<?=$companyid?>">
		  <table width="100%" border="0" class="invoice">
            <tr bgcolor="#CCCCCC">
              <td colspan="3" bgcolor="#CCCCCC" class="infoHeader"><?=$mi_pay_info['RangeInfo']?></td>
              <td colspan="2" class="infoHeader">
                <?=date("l - F j, Y",$paydate)?>
              </td>
            </tr>
            <tr height="20">
              <td colspan="2"></td>
              <td colspan="3"></td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Sales</td>
              <td colspan="3">
                <?=($mi_pay_info['Sales']==0?"- None -":formatMoney($mi_pay_info['Sales']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Refund Reserve</td>
              <td colspan="3">
                <?=($mi_pay_info['RefundReserveRate']==0?"- None -":formatMoney($mi_pay_info['RefundReserveRate']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td colspan="2">Gross</td>
              <td colspan="3">
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Transaction Fees</td>
              <td colspan="3">
                <?=($mi_pay_info['TransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['TransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Customer Transaction Fees</td>
              <td colspan="3">
                <?=($mi_pay_info['CustomerTransactionFees']==0?"- None -":formatMoney(-$mi_pay_info['CustomerTransactionFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Reserve</td>
              <td colspan="3">
                <?=($mi_pay_info['ReserveFees']==0?"- None -":formatMoney(-$mi_pay_info['ReserveFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Discount Fees</td>
              <td colspan="3">
                <?=($mi_pay_info['DiscountFees']==0?"- None -":formatMoney(-$mi_pay_info['DiscountFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Refunds</td>
              <td colspan="3">
                <?=($mi_pay_info['Refunds']==0?"- None -":formatMoney(-$mi_pay_info['Refunds']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Chargebacks</td>
              <td colspan="3">
                <?=($mi_pay_info['Chargebacks']==0?"- None -":formatMoney(-$mi_pay_info['Chargebacks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Voided Checks</td>
              <td colspan="3">
                <?=($mi_pay_info['VoidChecks']==0?"- None -":formatMoney(-$mi_pay_info['VoidChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Returned Checks</td>
              <td colspan="3">
                <?=($mi_pay_info['ReturnedChecks']==0?"- None -":formatMoney(-$mi_pay_info['ReturnedChecks']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Refund Fees</td>
              <td colspan="3">
                <?=($mi_pay_info['RefundFees']==0?"- None -":formatMoney(-$mi_pay_info['RefundFees']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Chargeback Fees</td>
              <td colspan="3">
                <?=($mi_pay_info['ChargebackFees']==0?"- None -":formatMoney(-$mi_pay_info['ChargebackFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td colspan="2">Main Deductions</td>
              <td colspan="3">
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Monthly Fee</td>
              <td colspan="3">
                <?=($mi_pay_info['MonthlyFee']==0?"- None -":formatMoney(-$mi_pay_info['MonthlyFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Wire Fee</td>
              <td colspan="3">
                <?=($mi_pay_info['WireFee']==0?"- None -":formatMoney(-$mi_pay_info['WireFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Setup Fee</td>
              <td colspan="3" >
                <?=($mi_pay_info['SetupFee']==0?"- None -":formatMoney(-$mi_pay_info['SetupFee']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Misc Fee/Credit</td>
              <td colspan="3" >
                <?=($mi_pay_info['MiscFees']==0?"- None -":formatMoney($mi_pay_info['MiscFees']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td colspan="2">Fee Deductions</td>
              <td colspan="3">
                <?=($mi_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$mi_pay_info['EtelDeductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Gross</td>
              <td colspan="3" >
                <?=($mi_pay_info['Gross']==0?"- None -":formatMoney($mi_pay_info['Gross']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Deductions</td>
              <td colspan="3" >
                <?=($mi_pay_info['Deductions']==0?"- None -":formatMoney(-$mi_pay_info['Deductions']))?>
              </td>
            </tr>
            <tr class="infoSubSection">
              <td colspan="2">Fee Deductions</td>
              <td colspan="3">
                <?=($mi_pay_info['EtelDeductions']==0?"- None -":formatMoney(-$mi_pay_info['EtelDeductions']))?>
              </td>
            </tr>
            <tr class="infoSection">
              <td colspan="2">Balance</td>
              <td colspan="3">
                <?=($mi_pay_info['Balance']==0?"- None -":formatMoney($mi_pay_info['Balance']))?>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="info">By Bank</td>
              <td width="137" class="infoBold">&nbsp;
              </td>
              <td width="137" class="infoBold">Deductions From </td>
              <td width="90" class="infoBold">Pay By Bank </td>
            </tr>
			<?php foreach ($mi_pay_info['BankInfo'] as $k=>$d) {
			$balanceDeduction = $d['Balance']-$mi_pay_info['EtelDeductions'];
			?>
            <tr>
              <td width="13" class="info">&nbsp;</td>
              <td width="104" class="infoBold"><?=$d['bank_name']?></td>
              <td class="info">
              <label id="balanceBank_<?=$d['bank_id']?>"><?=($d['Balance']==0?"- None -":formatMoney($d['Balance']))?></label>
              </td>
              <td class="info"><input name="deductbank" type="radio" id="deductbank_<?=$d['bank_id']?>" value="<?=$d['bank_id']?>"  <?=($d['Balance']==0?"disabled":"checked")?> onChange="selectGreatestPayout(<?=$d['bank_id']?>)" ></td>
              <td class="info"><input id="paybank_<?=$d['bank_id']?>" name="paybank[]" type="checkbox" value="<?=$d['bank_id']?>" onClick="payChange(this)" <?=($d['Balance']==0?"disabled":"checked")?>></td>
            </tr>
			<script language="javascript">
			<!--
			BankInfo = new Array();
			BankInfo['Balance']='<?=$d['Balance']?>';
			BankInfo['FormatBalance']='<?=($d['Balance']==0?"- None -":formatMoney($d['Balance']))?>';
			BankInfo['FormatBalanceDeduction']='<?=($balanceDeduction==0?"- None -":formatMoney($balanceDeduction))?>';
			
			BankInfo['bank_id']='<?=$d['bank_id']?>';
			BankList[BankInc] = BankInfo;
			BankInc++;
			-->
			</script>
			<?php } ?>
            <tr height="25">
              <td colspan="2" class="infoBold">Status</td>
              <td colspan="3" class='<?=$mi_pay_info['Status']?>'>
                <?=$mi_pay_info['Status']?> 
              </td>
            </tr>
			<?php  if($mi_pay_info['pay']) { ?>
            <tr height="25">
              <td colspan="2" class="infoBold">Notes</td>
              <td colspan="3"><textarea name="mi_notes" cols="40" rows="2"></textarea></td>
            </tr>
			<?php } ?>
            <tr height="25">
              <td colspan="2" class="infoBold"></td>
              <td colspan="3" class="infoBold"><input name="Submit" type="submit" id="Submit" value="Pay Company" class="infoBold" <?=$mi_pay_info['pay']?"":"disabled"?>>
                Payday
                  <input name="custom_pay_day" type="text" id="custom_pay_day" value="<?=$companyInfo['cd_next_pay_day']?>"></td>
            </tr>
          </table>
			<script language="javascript">
			<!--
		  	selectGreatestPayout(-1);
			-->
			</script>
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


// List Reports

ob_start();
?>

<select name="mi_ID" size="5" id="mi_ID">
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally("select mi_ID, mi_title from `cs_merchant_invoice` where mi_company_id = '$companyId' ORDER BY `mi_date` DESC ",$mi_ID,$cnn_cs); ?>
</select>
<BR>
<input type="hidden" name="companyId" value="<?=$companyId?>">
<input name="ViewInvoice" type="submit" value="View Invoice">
<?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","viewCompanyInvoice.php");

include("includes/footer.php");
?>