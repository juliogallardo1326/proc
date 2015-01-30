<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway

include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';

require_once( 'includes/function.php');
//include 'includes/function1.php';

$str_adminapproval="";
$sessionlogin = $companyInfo['userId'];

$qry_details="SELECT * FROM `cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_company_id` = '$sessionlogin' AND cs_hide = '0'";	
$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteSQL = "";
$siteID =  intval($_REQUEST['selectSite']);
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


$stats_list[4]['from'] = ($_REQUEST['opt_from_year'])."-".$_REQUEST['opt_from_month']."-".$_REQUEST['opt_from_day']." 00:00:00";
$stats_list[4]['to'] = $_REQUEST['opt_to_year']."-".$_REQUEST['opt_to_month']."-".$_REQUEST['opt_to_day']." 23:59:59";
$stats_list[4]['title'] = $stats_list[4]['from']." to ".$stats_list[4]['to'];

if($_REQUEST['opt_from_full'])$stats_list[4]['from'] =$_REQUEST['opt_from_full'];
if($_REQUEST['opt_to_full'])$stats_list[4]['to'] =$_REQUEST['opt_to_full'];

$compSQL = "";
$compID = $sessionlogin;
if (!$compID) $compID = -1;
$compSQL = "AND `userId` = '$compID' ";
$qry_details="SELECT * FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);

$forcomp = "";
if ($companyInfo['companyname']) $forcomp = " for ".$companyInfo['companyname'];
beginTable();
?>


	
	<form action="" method="POST" name="FrmMerchant" >
	<?
		foreach($_REQUEST as $name => $value)
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td class="lgnbd" colspan="5" height="10">
	<select name="selectSite" id="selectSite">
      <option value="-1">All Sites</option>
      <?=$siteList?>
    </select>
      <input type="submit" value="Update">
Use this Dropdown List to select Statistics for individual sites. </td>
	</tr>
	</table>
	</form>

  <?php 
  
		$i = 4;
  		if ($_REQUEST['crorcq'] == 'A' ) $CHW_Array = array('C','H','W');
		else $CHW_Array = array($_REQUEST['crorcq']);
 		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	
 
 		$includeCHW = true;
 
 		include('includes/netcalc.php');		
  ?>
<table width='100%' border='0'>
    <tr>
    <td valign='top'>
	<?php if (in_array("H",$CHW_Array)) {
	  $total = $CHW['H']['NewSales']['cnt']/100;
	  if ($total <= 0) $total = 1;
	   ?>
	<br>
      <font face='verdana' size='2'>Credit Card Summary</font><br>
      <table width="350" cellpadding='5' cellspacing='0'  bgColor='#ffffff' class='lefttopright'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'  valign='top'>
        <tr height='30' bgcolor='#CCCCCC'>
          <td align='center' class='cl1'><span class='subhd'>Card Details</span></td>
          <td align='center' class='cl1'><span class='subhd'>Quantity </span></td>
          <td align='right' class='cl1'><span class='subhd'>Amount (USD)</span></td>
          <td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['H']['NewSales']['cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b>
            <?=formatMoney( $CHW['H']['NewSales']['amt'])?>
          </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['NewSales']['cnt']/$total)?>
            %</font></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['H']['declined']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['declined']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['declined']['cnt']/$total)?>
            %</font></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Recurring Transactions</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['H']['recurring']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['recurring']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['recurring']['cnt']/$total)?>
            %</font></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['H']['refundsAmount']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['refundsAmount']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['refundsAmount']['cnt']/$total)?>
            %</font></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['H']['chargebacks']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['chargebacks']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['chargebacks']['cnt']/$total)?>
            %</font></td>
        </tr>
      </table>
	  <?php } if (in_array("C",$CHW_Array)) {
	  $total = $CHW['C']['NewSales']['cnt']/100;
	  if ($total <= 0) $total = 1;
	   ?>
      <br>
      <font face='verdana' size='2'>Check Summary</font><br>
      <table width="350" cellpadding='5' cellspacing='0'  bgColor='#ffffff' class='lefttopright'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'  valign='top'>
        <tr height='30' bgcolor='#CCCCCC'>
          <td align='center' class='cl1'><span class='subhd'>Check Details</span></td>
          <td align='center' class='cl1'><span class='subhd'>Quantity</span></td>
          <td align='right' class='cl1' width='50'><span class='subhd'>Amount (USD)</span></td>
          <td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['C']['NewSales']['cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b>
            <?=formatMoney( $CHW['C']['NewSales']['amt'])?>
          </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['NewSales']['cnt']/$total)?>
    %</font></td>
        </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['C']['declined']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['declined']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['declined']['cnt']/$total)?>
    %</font></td>
        </tr>
		<tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Recurring Transactions</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['C']['recurring']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['recurring']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['recurring']['cnt']/$total)?>
            %</font></td>
        </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending Check Transactions</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$pendChecks['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $pendChecks['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $pendChecks['cnt']/$total_amount)?> %</font></td>
      </tr>
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['C']['refundsAmount']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['refundsAmount']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['refundsAmount']['cnt']/$total)?>
    %</font></td>
        </tr>
		        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['C']['chargebacks']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['chargebacks']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['C']['chargebacks']['cnt']/$total)?>
            %</font></td>
        </tr>
      </table>
      <?php } if (0 && in_array("W",$CHW_Array)) {
	  $total = $CHW['W']['total_cnt']/100;
	  if ($total <= 0) $total = 1;
	   ?>
	  <br><font face='verdana' size='2'>
ETEL900 Summary</font><br>
<table width="350" cellpadding='5' cellspacing='0'  bgColor='#ffffff' class='lefttopright'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'  valign='top'>
  <tr height='30' bgcolor='#CCCCCC'>
    <td align='center' class='cl1'><font size="2" face="verdana"><span class='subhd'>Phone Details</span></font></td>
    <td align='right' class='cl1'><font size="2" face="verdana"><span class='subhd'>Quantity</span></font></td>
    <td align='right' class='cl1'><font size="2" face="verdana"><span class='subhd'>Amount (USD)</span></font></td>
    <td align='right' class='cl1'><font size="2" face="verdana"><span class='subhd'>Percentage (%)</span></font></td>
  </tr>
  <tr>
    <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
    <td align='center'  class='cl1'><font face='verdana' size='1'><b>
      <?=$CHW['W']['NewSales']['cnt']?>
    </b></font></td>
    <td align='right' class='cl1'><font face='verdana' size='1'><b>
      <?=formatMoney( $CHW['W']['NewSales']['amt'])?>
    </b></font></td>
    <td align='right'  class='bottom'><font face='verdana' size='1'>
      <?=formatMoney( $CHW['W']['NewSales']['cnt']/$total)?>
    %</font></td>
  </tr>
  <tr>
    <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
    <td align='center'  class='cl1'><font face='verdana' size='1'>
      <?=$CHW['W']['declined']['cnt']?>
    </font></td>
    <td align='right' class='cl1'><font face='verdana' size='1'>
      <?=formatMoney( $CHW['W']['declined']['amt'])?>
    </font></td>
    <td align='right'  class='bottom'><font face='verdana' size='1'>
      <?=formatMoney( $CHW['W']['declined']['cnt']/$total)?>
    %</font></td>
  </tr>
          <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Recurring Transactions</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['H']['recurring']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['recurring']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['H']['recurring']['cnt']/$total)?>
            %</font></td>
        </tr>
  <tr>
    <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
    <td align='center'  class='cl1'><font face='verdana' size='1'>
      <?=$CHW['W']['refundsAmount']['cnt']?>
    </font></td>
    <td align='right' class='cl1'><font face='verdana' size='1'>
      <?=formatMoney( $CHW['W']['refundsAmount']['amt'])?>
    </font></td>
    <td align='right'  class='bottom'><font face='verdana' size='1'>
      <?=formatMoney( $CHW['W']['refundsAmount']['cnt']/$total)?>
    %</font></td>
  </tr>
          <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$CHW['W']['chargebacks']['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['W']['chargebacks']['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $CHW['W']['chargebacks']['cnt']/$total)?>
            %</font></td>
        </tr>
</table>
<?php } ?>
</td>
    <td valign='top'>
    <br>
    <font face='verdana' size='2'>
    Total Summary</font>
    <br>
    <table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>
      <tr height='30' bgcolor='#CCCCCC'>
        <td align='center' bgcolor="#CCCCCC"  class='cl1'><span class='subhd'>Total Details</span></td>
        <td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>
        <td align='right' class='cl1'><span class='subhd'>Amount (USD)</span></td>
        <td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><b><?=$amountSum['cnt']?></b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $amountSum['amt'])?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( ($amountSum['cnt'])/$total_amount)?>%</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><b><?=$NewSales['cnt']?></b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $NewSales['amt'])?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( ($NewSales['cnt'])/$total_amount)?>%</font></td>
       </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$declined['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $declined['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $declined['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
         <td align='center'  class='cl1'><font face='verdana' size='1'><?=$refundsAmount['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $refundsAmount['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $refundsAmount['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Recurring Transactions</b></font></td>
         <td align='center'  class='cl1'><font face='verdana' size='1'><?=$rebillSum['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $rebillSum['amt'],2,".",",")?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $rebillSum['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Affiliate Sales </b></font></td>
         <td align='center'  class='cl1'><font face='verdana' size='1'><?=$referalSum['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $referalSum['amt'],2,".",",")?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $referalSum['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$chargeback['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $chargeback['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $chargeback['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending Check Transactions</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><?=$pendChecks['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $pendChecks['amt'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $pendChecks['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr>
         <td align='left'  class='cl1'><font face='verdana' size='1'><b>Voids</b></font></td>
         <td align='center'  class='cl1'><font face='verdana' size='1'><?=$voidChecks['cnt']?></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $voidChecks['amt'],2,".",",")?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $voidChecks['cnt']/$total_amount)?>%</font></td>
      </tr>
      <tr bgcolor='#CCCCCC'>
        <td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>
        <td align='right' class='cl1'><span class='subhd'>Deducted Amount (USD)</span></td>
        <td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $chargeback['ded'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $companyInfo['chargeback'])?></font></td>
      </tr>
      <tr>  	   	   	   	 
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Refund</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $refundsAmount['ded'])?></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $companyInfo['credit'])?></font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Discount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $discount['amt'])?></font></td>
         <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $companyInfo['cc_total_discount_rate'])?>%</font></td>
     </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Transaction Fee</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $transactionfee['amt'])?></font></td>
         <td align='right'  class='bottom'><font face='verdana' size='1'> - </font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Reserve Fee</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $reservefee['amt'])?></font></td>
         <td align='right'  class='bottom'><font face='verdana' size='1'><?=formatMoney( $companyInfo['reserve'])?>%</font></td>
      </tr>
      <tr bgcolor='#CCCCCC'>
        <td align='left' class='cl1' colspan='2'><span class='subhd'>Net Calculation</span></td>
        <td align='right' class='cl1'><span class='subhd'> Amount (USD)</span></td>
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
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( -$TotalDeductions)?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b><?=formatMoney( $Net)?></b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
    </table>
    </td>
    </tr>
  </table>

<?php
endTable("Full Statistics $forcomp","reportBottomSummary.php",NULL,NULL,TRUE);

include("includes/footer.php");
?>