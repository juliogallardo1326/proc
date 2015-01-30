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

$headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';

require_once( '../includes/function.php');
include '../includes/function1.php';

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionReseller =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";


$qry_details="SELECT * FROM `etel_dbsmain`.`cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_company_id` = '$sessionlogin' AND cs_hide = '0'";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteSQL = "";
$siteID = $_POST['selectSite'];
if (!$siteID) $siteID = -1;
if ($siteID != -1)  $siteSQL = "AND td_site_ID = $siteID ";

$siteList = "";

while($site = mysql_fetch_assoc($rst_details))
{
	$siteList.= "<option value='".$site['cs_ID']."' ".($site['cs_ID']==$siteID?"selected":"").">".str_replace('http://','',$site['cs_URL'])."</option>";
}

$today = getdate();
$day = getdate();

$stats_list = "";


$stats_list[4]['from'] = ($_GET['opt_from_year'])."-".$_GET['opt_from_month']."-".$_GET['opt_from_day']." 00:00:00";
$stats_list[4]['to'] = $_GET['opt_to_year']."-".$_GET['opt_to_month']."-".$_GET['opt_to_day']." 23:59:59";
$stats_list[4]['title'] = $stats_list[4]['from']." to ".$stats_list[4]['to'];

if($_GET['opt_from_full'])$stats_list[4]['from'] =$_GET['opt_from_full'];
if($_GET['opt_to_full'])$stats_list[4]['to'] =$_GET['opt_to_full'];

$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE `reseller_id` = '$sessionReseller' AND activeuser=1 ORDER BY `companyname` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$compSQL = "AND `reseller_id` = '$sessionReseller' AND activeuser=1 ";
$compID = $_GET['selectComp'];
if($_POST['selectComp']) $compID = $_POST['selectComp'];
if (!$compID) $compID = -1;
if ($compID != -1)  $compSQL .= "AND (t.`userId` = '$compID')";

while($comp = mysql_fetch_assoc($rst_details))
{
	$compList.= "<option value='".$comp['userId']."' ".($comp['userId']==$compID?"selected":"").">".$comp['companyname']."</option>";
}
$qry_details="SELECT * FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);

$forcomp = "";
if ($companyInfo['companyname']) $forcomp = " for ".$companyInfo['companyname'];
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" >
<tr>
    <td width="90%" valign="top" align="center"  > <br>	
<form action="" method="POST" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Full Statistics<?=$forcomp?></span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10"><select name="selectComp" id="selectComp">
      <option value="-1">All Sites</option>
      <?=$compList?>
    </select>
      <input type="submit" value="Update">
      <br>
      Use this Dropdown List to select Statistics for individual merchants. </td>
	</tr>
	  <tr><td colspan="5" class="lgnbd">

  <?php 
  
		$i = 4;
		$table = "`cs_transactiondetails` as t left join `cs_companydetails` as c on (t.`userId` = c.userId)";
		//$daterange = "AND `transactionDate` between '".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	

		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	
  // Start CC/Check/Web900 Stats - Is this needed?
  
  		$selection = "*(`r_reseller_discount_rate`/100)";
		
		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	
 		$includeCHW = false;
		$resellerconfig=true;
 		include('../includes/netcalc.php');
		
		$res_Discount_Net = $Net;
		$res_Trans_Fee = $transactionfee['resamt']-$declined_transactionfee['resamt'];
		$res_Amount_Earned = $res_Discount_Net+$res_Trans_Fee;
  		if ($_GET['crorcq'] == 'A' ) $CHW_Array = array('C','H','W');
		else $CHW_Array = array($_GET['crorcq']);
 		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	
 		$selection = "";
 		$includeCHW = true;
		$resellerconfig=false;
 
 		include('../includes/netcalc.php');		
    								//+$declined['cnt']
		$total_amount=$NewSales['cnt']+$declined['cnt']+$refundsAmount['cnt']+$chargeback['cnt'];
		if($total_amount==0)$total_amount=1;
	?>
<table width='100%' border='0'>
    <tr>
	<td valign='top'>
    <br>
    <font face='verdana' size='2'>
    Total Summary</font>
    <br>
    <table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>
      <tr height='30' bgcolor='#CCCCCC'>
        <td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>
        <td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>
        <td align='right' class='cl1'><span class='subhd'>Amount (USD)</span></td>
        <td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><b><?=$NewSales['cnt']?></b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $NewSales['amt'])?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( 100*$NewSales['cnt']/$total_amount)?>%</font></td>
       </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$declined['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $declined['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( 100*$declined['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
         <td align='center'  class='cl1'><font face='verdana' size='1'><?=$refundsAmount['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $refundsAmount['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney(100* $refundsAmount['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$chargeback['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $chargeback['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( 100*$chargeback['cnt']/$total_amount)?>%</font></td>
      </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['H']['total_cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b>
            <font face='verdana' size='1'><b>
            <?=formatMoney( $NewSales['amt']+$declined['amt']+$refundsAmount['amt']+$chargeback['amt'])?>
            </b></font>          </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( ($CHW['H']['total_cnt']==0?0:100))?>%</font></td>
        </tr>
      <tr bgcolor='#CCCCCC'>
        <td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>
        <td align='right' class='cl1'><span class='subhd'>Deducted Amount (USD)</span></td>
        <td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Gross Amount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $Gross,2,".",",")?></b>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Total Deduction</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $TotalDeductions)?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $Net)?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
    </table>
    </td>
    <td valign='top'><br><font face='verdana' size='2'>
 Commission Earned</font><br>
      <table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>
        <tr bgcolor='#CCCCCC'>
          <td align='left' class='cl1'><span class='subhd'>Deductions</span></td>
          <td align='right' class='cl1'><span class='subhd'>Deducted Amount (USD)</span></td>
          <td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td>
        </tr>
        <tr>
          <td align='left' class='cl1'><font face='verdana' size='1'><b>Discount</b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $res_Discount_Net)?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=(($compID == -1)?"-":formatMoney( $companyInfo['reseller_discount_rate'])."%")?>
            </font></td>
        </tr>
        <tr>
          <td align='left' class='cl1'><font face='verdana' size='1'><b>Transaction Fee</b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
          <font face='verdana' size='1'>
          <?=formatMoney( $res_Trans_Fee)?>
          </font> </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=(($compID == -1)?"-":formatMoney( $companyInfo['reseller_trans_fees']))?>
          </font></td>
        </tr>
        <tr>
          <td align='left' class='cl1'><font face='verdana' size='1'><b>Total Earned</b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney($res_Amount_Earned)?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;
            
          </font></td>
        </tr>
      </table>
	</td>
    
    </tr>
  </table>
	  </td>
	  </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table>
	</form>
    </td>
     </tr>	 
</table>
<?php
include("includes/footer.php");
?>