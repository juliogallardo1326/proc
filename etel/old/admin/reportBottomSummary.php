<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// reportBottomSummary.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "ledgers";
include 'includes/header.php';

require_once( '../includes/function.php');
include '../includes/function1.php';
$qry_company_type="";
$qry_select_user="";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin=="")die();

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$qry_details="SELECT * FROM `cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_company_id` = '".$_POST['selectComp']."' AND cs_hide = '0'";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteSQL = "";
$siteID = intval($_REQUEST['selectSite']);
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

$compSQL_active = "";
$compID = $_GET['selectComp'];
if($_POST['selectComp']) $compID = $_POST['selectComp'];
if ($compID == -2)  $compSQL_active = " AND activeuser=1 ";
if ($compID == -3)  $compSQL_active = " AND activeuser=0 ";

$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE 1 $bank_sql_limit $compSQL_active ORDER BY `companyname` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

while($comp = mysql_fetch_assoc($rst_details))
{
	$compList.= "<option value='".$comp['userId']."' ".($comp['userId']==$compID?"selected":"").">".$comp['companyname']."</option>";
}

$compSQL = "$compSQL_active";
if (!$compID) $compID = -1;
if ($compID > -1 && $compID != "A")  $compSQL = "AND (t.`userId` = '$compID')";
			
$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);

$forcomp = "";
if ($companyInfo['companyname']) $forcomp = " for ".$companyInfo['companyname'];
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
    <td width="90%" valign="top" align="center"  > <br>	
<form action="" method="POST" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Full Statistics<?=$forcomp?> </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10">
		<select name="selectComp" id="selectComp">
	  <option value="-1" <?=($compID=='-1'?"selected":"")?>>All Companies</option>
	  <option value="-2" <?=($compID=='-2'?"selected":"")?>>Active Companies</option>
	  <option value="-3" <?=($compID=='-3'?"selected":"")?>>Inactive Companies</option>
	  <option value="-1">-----------------</option>
	  <?=$compList?>
        </select>
	<select name="selectSite" id="selectSite">
      <option value="-1">All Sites</option>
      <?=$siteList?>
    </select>
      <input type="submit" value="Update">
Use this Dropdown List to select Statistics for individual sites. </td>
	</tr>
	  <tr><td colspan="5" class="lgnbd">

  <?php 
  
		$i = 4;


  
 
		$table = "`cs_transactiondetails` as t left join `cs_companydetails` as c on (t.`userId` = c.userId) ";

  		if ($_GET['crorcq'] == 'A' ) $CHW_Array = array('C','H','W');
		else $CHW_Array = array($_GET['crorcq']);
 		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	

 		$includeCHW = true;
 
 		include('../includes/netcalc.php');		
  
  ?>
<table width='100%' border='0'>
    <tr>
    <td valign='top'>
	  <?php if (in_array("H",$CHW_Array)) {
	  $total = $CHW['H']['total_cnt']/100;
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
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['H']['total_cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b>
            <?=formatMoney( $CHW['H']['total_amt'])?>
          </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( 100)?>
      %</font></td>
        </tr>
      </table>
      <?php } if (in_array("C",$CHW_Array)) {
	  $total = $CHW['C']['total_cnt']/100;
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
          <td align='center'  class='cl1'><font face='verdana' size='1'>
            <?=$pendChecks['cnt']?>
          </font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'>
            <?=formatMoney( $pendChecks['amt'])?>
          </font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( $pendChecks['cnt']/$total_amount)?>
            %</font></td>
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
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['C']['total_cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b> <font face='verdana' size='1'><b>
            <?=formatMoney( $CHW['C']['total_amt'])?>
          </b></font> </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( 100)?>
      %</font></td>
        </tr>
      </table>
      <?php } if (in_array("W",$CHW_Array)) {
	  $total = $CHW['W']['total_cnt']/100;
	  if ($total <= 0) $total = 1;
	   ?>
      <br>
      <font face='verdana' size='2'> ETEL900 Summary</font><br>
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
        <tr>
          <td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>
          <td align='center'  class='cl1'><font face='verdana' size='1'><b>
            <?=$CHW['W']['total_cnt']?>
          </b></font></td>
          <td align='right' class='cl1'><font face='verdana' size='1'><b>
            <?=formatMoney( $CHW['W']['total_amt'])?>
          </b></font></td>
          <td align='right'  class='bottom'><font face='verdana' size='1'>
            <?=formatMoney( 100)?>
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
        <td align='center'  class='cl1'><font face='verdana' size='1'><b>
          <?=$amountSum['cnt']?>
        </b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b>
          <?=formatMoney( $amountSum['amt'])?>
        </b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $amountSum['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'><b>
          <?=$NewSales['cnt']?>
        </b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b>
          <?=formatMoney( $NewSales['amt'])?>
        </b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $NewSales['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$declined['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $declined['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $declined['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Refund</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$refundsAmount['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $refundsAmount['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $refundsAmount['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Recurring Transactions</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$rebillSum['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $rebillSum['amt'],2,".",",")?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $rebillSum['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Affiliate Sales </b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$referalSum['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $referalSum['amt'],2,".",",")?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $referalSum['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargebacks</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$chargeback['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $chargeback['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $chargeback['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending Check Transactions</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$pendChecks['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $pendChecks['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $pendChecks['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Voids</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$voidChecks['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $voidChecks['amt'],2,".",",")?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $voidChecks['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr>
        <td align='left'  class='cl1'><font face='verdana' size='1'><b>Returned Checks</b></font></td>
        <td align='center'  class='cl1'><font face='verdana' size='1'>
          <?=$returnedChecks['cnt']?>
        </font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $returnedChecks['amt'],2,".",",")?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $returnedChecks['cnt']/$total_amount)?>
          %</font></td>
      </tr>
      <tr bgcolor='#CCCCCC'>
        <td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>
        <td align='right' class='cl1'><span class='subhd'>Deducted Amount (USD)</span></td>
        <td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $chargeback['ded'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $companyInfo['chargeback'])?>
        </font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Refund</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $refundsAmount['ded'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $companyInfo['credit'])?>
        </font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Discount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $discount['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $companyInfo['discountrate'])?>
          %</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Transaction Fee</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $transactionfee['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'> - </font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Customer Fee Fee</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney( $customer_fee['amt'])?></font></td>
         <td align='right'  class='bottom'><font face='verdana' size='1'> - </font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Reserve Fee</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'>
          <?=formatMoney( $reservefee['amt'])?>
        </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>
          <?=formatMoney( $companyInfo['reserve'])?>
          %</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Gross Amount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b>
          <?=formatMoney( $Gross,2,".",",")?>
        </b> </font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Total Deduction</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b>
          <?=formatMoney( -$TotalDeductions)?>
        </b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
      <tr>
        <td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>
        <td align='right' class='cl1'><font face='verdana' size='1'><b>
          <?=formatMoney( $Net)?>
        </b></font></td>
        <td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td>
      </tr>
    </table></td>
    </tr>
  </table>
	  </td>
	  </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table>
	</form>
    </td>
     </tr>	 
</table>