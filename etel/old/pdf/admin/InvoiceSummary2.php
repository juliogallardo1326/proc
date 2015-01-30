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

include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";
$headerInclude="transactions";

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$companyId = (isset($_GET["companyId"])?quote_smart($_GET["companyId"]):"");
	
$qry_company="select * from cs_companydetails where userId ='$companyId'";
$gatewayid=-1;
$rst_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
$companyInfo=mysql_fetch_assoc($rst_details);

$thisdate=$_GET['paydate'];
$thisdate_id=intval(date("ymd",$thisdate));

$weekbasedate = 932832000;
$weekid=floor(($thisdate-$weekbasedate)/(7*24*60*60));
$payment_made=false;
$inv_details="";
$userid_val=($companyInfo['userId']);
$sql = "select * from `cs_invoice_history` where `ih_weekid` = '$weekid' AND `userId`='".$userid_val."'";
$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());

if ($invoiceInfo=mysql_fetch_assoc($inv_details)) 
{
	$payment_made = true;
	$balance = $invoiceInfo['ih_balance'];
	
	$projSettlement[$thisdate_id]['monthlyfee'] = $invoiceInfo['ih_monthlyfee'];
	$projSettlement[$thisdate_id]['wirefee'] = $invoiceInfo['ih_wirefee'];
	$projSettlement[$thisdate_id]['balance'] = $invoiceInfo['ih_balance'];
	$projSettlement[$thisdate_id]['rollover'] = $invoiceInfo['ih_rollover'];
	$projSettlement[$thisdate_id]['Net'] = $invoiceInfo['ih_net'];
	$projSettlement[$thisdate_id]['rollover'] = $invoiceInfo['ih_rollover'];
	$projSettlement[$thisdate_id]['ih_inv_ID'] = $invoiceInfo['ih_inv_ID'];
	$projSettlement[$thisdate_id]['invoice'] = "Payed on ".date("m-d-y",strtotime($invoiceInfo['ri_date']));
	
}
else
{
chdir("../");
include ("includes/projSetCalc.php");

$projSettlement="";
$projSettlementPeriods="";
projSetCalc();

$payment_made=($projSettlement[$thisdate_id]['ih_inv_ID']!=-1);
}
	if(($_GET['mode']=='makepayment') && (!$payment_made))
	{
		$companyInfo['ih_weekid'] = $weekid;
		$companyInfo['ih_net'] = $projSettlement[$thisdate_id]['Net'];
		$companyInfo['ih_rollover'] = $projSettlement[$thisdate_id]['rollover'];
		$companyInfo['ih_monthlyfee'] = $projSettlement[$thisdate_id]['monthlyfee'];
		$companyInfo['ih_wirefee'] = $projSettlement[$thisdate_id]['wirefee'];
		$companyInfo['ih_balance'] = $projSettlement[$thisdate_id]['balance'];
		$companyInfo['ih_date'] = $projSettlement[$thisdate_id]['timestamp'];

		$sql="userId='".$companyInfo['userId']."', ih_date=".$companyInfo['ih_date'].", ih_date_payed=NOW(), ih_weekid='".$companyInfo['ih_weekid']."', companyname='".$companyInfo['companyname']."', merchantName='".$companyInfo['merchantName']."', credit='".$companyInfo['credit']."', discountrate='".$companyInfo['discountrate']."', 
		ih_net='".$companyInfo['ih_net']."', ih_rollover='".$companyInfo['ih_rollover']."', ih_monthlyfee='".$companyInfo['ih_monthlyfee']."', ih_wirefee='".$companyInfo['ih_wirefee']."', ih_balance='".$companyInfo['ih_balance']."', 
		transactionfee='".$companyInfo['transactionfee']."', reserve='".$companyInfo['reserve']."', voiceauthfee='".$companyInfo['voiceauthfee']."', contactname='".$companyInfo['contactname']."', cd_payperiod='".$companyInfo['cd_payperiod']."', cd_paystartday='".$companyInfo['cd_paystartday']."', 
		cd_paydelay='".$companyInfo['cd_paydelay']."', cd_rollover='".$companyInfo['cd_rollover']."', cd_wirefee='".$companyInfo['cd_wirefee']."', cd_appfee='".$companyInfo['cd_appfee']."', cd_paydaystartday='".$companyInfo['cd_paydaystartday']."', cd_enable_price_points='".$companyInfo['cd_enable_price_points']."', 
		cd_enable_rand_pricing='".$companyInfo['cd_enable_rand_pricing']."', cc_chargeback='".$companyInfo['cc_chargeback']."', cc_discountrate='".$companyInfo['cc_discountrate']."', cc_reserve='".$companyInfo['cc_reserve']."', ch_chargeback='".$companyInfo['ch_chargeback']."', ch_discountrate='".$companyInfo['ch_discountrate']."', 
		ch_reserve='".$companyInfo['ch_reserve']."', web_chargeback='".$companyInfo['web_chargeback']."', web_discountrate='".$companyInfo['web_discountrate']."', web_reserve='".$companyInfo['web_reserve']."', cc_merchant_discount_rate='".$companyInfo['cc_merchant_discount_rate']."', 
		cc_reseller_discount_rate='".$companyInfo['cc_reseller_discount_rate']."', cc_total_discount_rate='".$companyInfo['cc_total_discount_rate']."', cc_merchant_trans_fees='".$companyInfo['cc_merchant_trans_fees']."', cc_reseller_trans_fees='".$companyInfo['cc_reseller_trans_fees']."', 
		cc_total_trans_fees='".$companyInfo['cc_total_trans_fees']."', ch_merchant_discount_rate='".$companyInfo['ch_merchant_discount_rate']."', ch_reseller_discount_rate='".$companyInfo['ch_reseller_discount_rate']."', ch_total_discount_rate='".$companyInfo['ch_total_discount_rate']."', 
		ch_merchant_trans_fees='".$companyInfo['ch_merchant_trans_fees']."', ch_reseller_trans_fees='".$companyInfo['ch_reseller_trans_fees']."', ch_total_trans_fees='".$companyInfo['ch_total_trans_fees']."', web_merchant_trans_fees='".$companyInfo['web_merchant_trans_fees']."', 
		web_reseller_trans_fees='".$companyInfo['web_reseller_trans_fees']."', web_total_trans_fees='".$companyInfo['web_total_trans_fees']."', cc_billingdescriptor='".$companyInfo['cc_billingdescriptor']."', ch_billingdescriptor='".$companyInfo['ch_billingdescriptor']."', cs_monthly_charge='".$companyInfo['cs_monthly_charge']."'";

		$sql = "REPLACE INTO `cs_invoice_history` SET $sql ";
		$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
		$payment_made = true;
		$new_invoice = true;
		$projSettlement[$thisdate_id]['invoice'] = "Payment Submitted!";
	}

	
			if($new_invoice==true) $newinvoicemsg = "New Invoice Submitted Successfully";
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
                <?=formatMoney( $companyInfo['cd_rollover'])?>', it will be rolled over to the next period.</font></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
        <tr align="center" valign="middle" height='20' bgcolor="#999999">
          <td height="20" class="whitehd">&nbsp;<span class="style5"><?=$newinvoicemsg?></span></td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  
		  
		  <table width="100%" border="0">
            <tr bgcolor="#CCCCCC">
              <td bgcolor="#CCCCCC"><div align="right" class="style5">Date</div></td>
              <td></td>
              <td><span class="style5">
                <?=date("l - F j, Y",$thisdate)?>
              </span></td>
            </tr>
            <tr height="20">
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Net Applied </span></div></td>
              <td></td>
              <td><span class="style1">
                <?=($projSettlement[$thisdate_id]['Net']==0?"- None -":formatMoney($projSettlement[$thisdate_id]['Net']))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Roll Over </span></div></td>
              <td></td>
              <td><span class="style1">
                <?=($projSettlement[$thisdate_id]['rollover']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['rollover']))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Monthly Fee </span></div></td>
              <td></td>
              <td><span class="style1">
                <?=($projSettlement[$thisdate_id]['monthlyfee']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['monthlyfee']))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Wire Fee </span></div></td>
              <td></td>
              <td><span class="style1">
                <?=($projSettlement[$thisdate_id]['wirefee']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['wirefee']))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style4">Balance </span></div></td>
              <td></td>
              <td><span class="style4">
                <?=($projSettlement[$thisdate_id]['balance']==0?"- None -":formatMoney( $projSettlement[$thisdate_id]['balance']))?>
              </span></td>
            </tr>
            <tr height="25">
              <td><div align="right"><span class="style4">Status</span></div></td>
              <td></td>
              <td><span class="style7">
                <?=$projSettlement[$thisdate_id]['invoice']?> 
                <?=(!$payment_made?" - <a href='InvoiceSummary.php?mode=makepayment&companyId=$companyId&paydate=$thisdate' >Pay Amount</a>":"")?>
              </span></td>
            </tr>
          </table></td>
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
chdir("admin");
include("includes/footer.php");
?>