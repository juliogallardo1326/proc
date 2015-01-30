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
$periodhead="Ledgers";
$display_stat_wait = true;
include("includes/header.php");
require_once("../includes/function.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?Trim($HTTP_GET_VARS['ptype']):"";


$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

chdir("../");
include ("includes/projSetCalc.php");
?>
  <style type="text/css">
<!--
.style1 {font-size: 10px}
.style4 {font-size: 12px; font-weight: bold; }
.style5 {
	color: #FFFFFF;
}
.red {
	font-size: 12px;
	color: #CC3300;
	font-weight: bold;
}
.blue {
	font-size: 12px;
	color:#0000FF;
	font-weight: bold;
}
-->
  </style>
<?php

$show_active = (isset($_GET["show_active"])?trim($_GET["show_active"]):"");
$show_rollover = (isset($_GET["show_rollover"])?trim($_GET["show_rollover"]):"");

$str_month_from = (isset($_GET["cbo_from_month"])?trim($_GET["cbo_from_month"]):"");
$str_year_from = (isset($_GET["cbo_from_year"])?trim($_GET["cbo_from_year"]):"");
$str_day_from = (isset($_GET["cbo_from_week"])?trim($_GET["cbo_from_week"]):"");
$str_day_from = ($str_day_from)*7-7;

$no_display=false;
if($str_month_from == ""){
	$str_day_from = date("j");
	$str_month_from = date("m");
	$str_year_from = date("Y");
	$no_display=true;
}  

$thisdate=strtotime($str_year_from."-".$str_month_from."-".$str_day_from." 12:00:00");
$thisdate+=7*24*60*60;
$thisdate_id=intval(date("ymd",$thisdate));
$curmonth = strtotime("$str_year_from-$str_month_from-$str_day_from 12:00:00");
$curmonthinfo = getdate($curmonth);
$startweek = $curmonth-($curmonthinfo['wday']+1)*24*60*60;
$weekbasedate = 932832000;
$weekid=floor(($startweek-$weekbasedate)/(7*24*60*60));
$endweek = $startweek+7*24*60*60;
$days_over_month = $curmonthinfo['mday']+$curmonthinfo['wday'];
if ($days_over_month > 30) $days_over_month = 1;
$weeknum = floor(($days_over_month)/7+1);
	

?>



  <p>&nbsp;</p>
  <div align="center">
  <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Invoice
      Report
    </span></td>
    <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
    <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
    <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
  </tr>
  <tr>
    <td class="lgnbd" colspan="5" height="10">
	
	<form action="" method="GET" name="FrmMerchant" >
  <table border="0"  align="center" cellpadding="2">
    <tr valign="middle" >     
	 <td width="102" align="right"  valign="middle"><select name="cbo_from_month" >
          <?php func_fill_month($str_month_from) ?>
      </select></td>
      <td ><select name="cbo_from_year">
          <?php func_fill_year($str_year_from,$i_start_year,$i_end_year) ?>
      </select></td>
      <td ><select name="cbo_from_week">
          <option value="1" <?=($weeknum%5==1?"selected":"")?>>Week 1</option>
          <option value="2" <?=($weeknum%5==2?"selected":"")?>>Week 2</option>
          <option value="3" <?=($weeknum%5==3?"selected":"")?>>Week 3</option>
          <option value="4" <?=($weeknum%5==4?"selected":"")?>>Week 4</option>
          <option value="5" <?=($weeknum==5?"selected":"")?>>Week 5</option>
      </select></td>
      <td width="127"><input type="image" name="add" src="../images/submit.jpg"></td>
    </tr>
	<tr>
	<td colspan="4"><select name="show_rollover" id="show_rollover" >
	  <option value="a" <?=($show_rollover=='a'?"selected":"")?>>Show All </option>
	  <option value="h" <?=($show_rollover=='h'?"selected":"")?>>Show 'To Be Paid' Companies</option>
	  <option value="p" <?=($show_rollover=='p'?"selected":"")?>>Show 'Already Paid' Companies</option>
	  <option value="s" <?=($show_rollover=='s'?"selected":"")?>>Show Rollover Companies</option>
            </select>
			<select name="show_active" id="show_active" >
	  <option value="a" <?=($show_active=='a'?"selected":"")?>>Show Active Companies</option>
	  <option value="s" <?=($show_active=='s'?"selected":"")?>>Show All </option>
	  <option value="i" <?=($show_active=='i'?"selected":"")?>>Show Inactive Companies</option>
            </select>
</td>
	</tr>
  </table>
</form>
	</td>
  </tr>
  <tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center">
        <tr align="center" valign="middle" height='20' bgcolor="#448A99">
          <td height="20" class="whitehd">&nbsp;</td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  
		  
		  <table width="100%" border="0">
            <tr bgcolor="#78B6C2">
              <td bgcolor="#78B6C2"><div align="right" class="style5">Company</div></td>
              <td><span class="style5">Payday</span></td>
              <td><span class="style5">Balance</span></td>
              <td><span class="style5">Status</span></td>
              <td><span class="style5">&nbsp;</span></td>
            </tr>
      <?php  

set_time_limit(20);
	
$activity="";
if($show_active=='a') $activity = " AND activeuser=1 ";
if($show_active=='i') $activity = " AND activeuser=0 ";
$display_none = "1";
if($no_display==true) $display_none = "0";
$qry_company="select * from cs_companydetails where $display_none $activity AND cd_has_been_active=1";
$gatewayid=-1;
$company_details=mysql_query($qry_company,$cnn_cs) or die("Cannot execute query");
while($companyInfo=mysql_fetch_assoc($company_details))
{
		
$thisdate=strtotime($str_year_from."-".$str_month_from."-".$str_day_from." 12:00:00");
$thisdate+=7*24*60*60;
$hide_company=false;
$forcomp = "";
if ($companyInfo['companyname']) $forcomp = $companyInfo['companyname'];
		
$projSettlement="";
$projSettlementPeriods="";

$payment_made=false;
$inv_details="";
$userid_val=intval($companyInfo['userId']);
$sql = "select * from `cs_invoice_history` where `ih_weekid` = '$weekid' AND `userId`='".$userid_val."'";
$inv_details=mysql_query($sql,$cnn_cs) or die(" Cannot execute query. $sql Error:".mysql_error());
if ($invoiceInfo=mysql_fetch_assoc($inv_details)) 
{
	$payment_made = true;
	$balance = $invoiceInfo['ih_balance'];
	
	$projSettlement[$weekid]['monthlyfee'] = $invoiceInfo['ih_monthlyfee'];
	$projSettlement[$weekid]['wirefee'] = $invoiceInfo['ih_wirefee'];
	$projSettlement[$weekid]['balance'] = $invoiceInfo['ih_balance'];
	$projSettlement[$weekid]['rollover'] = $invoiceInfo['ih_rollover'];
	$projSettlement[$weekid]['Net'] = $invoiceInfo['ih_net'];
	$projSettlement[$weekid]['timestamp'] = $invoiceInfo['ih_date'];
	$projSettlement[$weekid]['date'] = date("l, F j, Y",strtotime($invoiceInfo['ih_date_payed']));
	$projSettlement[$weekid]['rollover'] = $invoiceInfo['ih_rollover'];
	$projSettlement['rollover?']=0;
	$projSettlement[$weekid]['ih_inv_ID'] = $invoiceInfo['ih_inv_ID'];
	$projSettlement[$weekid]['invoice'] = "Payed on ".date("m-d-y",strtotime($invoiceInfo['ri_date']));
	if($show_rollover=='h') $hide_company = true;
	if($show_rollover=='r') $hide_company = true;
}
else
{

$pSKey='weekid';
projSetCalc();

$payment = "";
if ($projSettlement[$weekid]['pay?'] == 1) $payment = "<a href=InvoiceSummary.php?mode=makepayment&companyId=".$companyInfo['userId']."&paydate=".$projSettlement[$weekid]['timestamp']."' >Pay</a>";
$payment_made=($projSettlement[$weekid]['ih_inv_ID']!=-1);
if($show_rollover=='p') $hide_company = true;
}
if(($show_rollover=='s')!=($projSettlement[$weekid]['rollover?']==1)) $hide_company = true;
if ($show_rollover=='a') $hide_company = false;
		if (!$hide_company){
	?>
            <tr>
              <td><span class="style4"><?=$forcomp?></span></td>
              <td><span class="style4"><?=$projSettlement[$weekid]['date']?></span></td>
              <td><span class="style4">
			  
			  <?php if($projSettlement[$weekid]['balance']!=0) { ?> <a href='InvoiceSummary.php?companyId=<?=$companyInfo['userId']?>&paydate=<?=$projSettlement[$weekid]['timestamp']?>' > <? } ?>
			  
			  <?=($projSettlement[$weekid]['balance']==0?"- None -":formatMoney( $projSettlement[$weekid]['balance']))?>
			  
			  <?php if($projSettlement[$weekid]['balance']!=0) { ?></a><? } ?>
			  
			  </span></td>
              <td><?=$projSettlement[$weekid]['invoice']?></td>
              <td><span class='style4'><?=$payment?></style></td>
            </tr>

  <?php
  		}
	}

?>

          </table></td>
        </tr>
     
    </table></td>
  </tr>
  <tr>
    <td width="1%"><img src="images/menubtmleft.gif"></td>
    <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
    <td width="1%" ><img src="images/menubtmright.gif"></td>
  </tr>
</table></div>
  <p>&nbsp;  </p>
<?php

chdir("admin");
include("includes/footer.php");
?>