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

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

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

$show_active = (isset($_GET["show_active"])?quote_smart($_GET["show_active"]):"");
$show_rollover = (isset($_GET["show_rollover"])?quote_smart($_GET["show_rollover"]):"");

$str_month_from = (isset($_GET["cbo_from_month"])?quote_smart($_GET["cbo_from_month"]):"");
$str_year_from = (isset($_GET["cbo_from_year"])?quote_smart($_GET["cbo_from_year"]):"");
$str_day_from = (isset($_GET["cbo_from_week"])?quote_smart($_GET["cbo_from_week"]):"");
$str_day_from = ($str_day_from)*7-7;
$monthid=$str_year_from.$str_month_from;
$no_display=false;
if($str_month_from == ""){
	$str_day_from = date("j");
	$str_month_from = date("m");
	$str_year_from = date("Y");
	$no_display=true;
}  

$curmonth = strtotime("$str_year_from-$str_month_from-$str_day_from 12:00:00");
$curmonthinfo = getdate($curmonth);
$numOfMonths=$curmonthinfo['mon']+($curmonthinfo['year']-2002)*12-2;
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
      <td width="127"><input type="image" name="add" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td>
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
        <tr align="center" valign="middle" height='20' bgcolor="#999999">
          <td height="20" class="whitehd">&nbsp;</td>
        </tr>
        <tr align="center" valign="middle" height='20' >
          <td height="20" class="whitehd">
		  
		  
		  <table width="100%" border="0">
            <tr bgcolor="#CCCCCC">
              <td bgcolor="#CCCCCC"><div align="right" class="style5">Company</div></td>
              <td><span class="style5">Payday</span></td>
              <td><span class="style5">Balance</span></td>
              <td><span class="style5">Status</span></td>
              <td><span class="style5">&nbsp;</span></td>
            </tr>
      <?php  

set_time_limit(20);
	
$activity="";

if($show_active == 'a') $active = "completed_reseller_application=1 AND";
if($show_active == 'i') $active = "completed_reseller_application=0 AND";

$display_none = "1";
if($no_display==true)$display_none = "0";
$qry_company="select * from cs_resellerdetails where $active $display_none";
$gatewayid=-1;
$company_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
while($companyInfo=mysql_fetch_assoc($company_details))
{

$thisdate = strtotime($curmonthinfo['year']."-".$curmonthinfo['mon']."-".$companyInfo['rd_paydelay']." 12:00:00");


$payment_made=false;
$inv_details="";
$sql = "select * from `cs_reseller_invoice_history` where `ri_monthid` = '$monthid' AND `ri_reseller_id`=".$companyInfo['reseller_id'];
$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
if ($invoiceInfo=mysql_fetch_assoc($inv_details)) 
{
	$payment_made = true;
	
	$NetApplied = $invoiceInfo['ri_net'];
	$rollOverApplied = $invoiceInfo['ri_rollover'];
	$monthlyFeeApplied = $invoiceInfo['ri_monthlyfee'];
	$wireFeeApplied = $invoiceInfo['ri_wirefee'];
	$balance = $invoiceInfo['ri_balance'];
}

$forcomp = "";
if ($companyInfo['reseller_companyname']) $forcomp = $companyInfo['reseller_companyname'];
	$dateadded_time = strtotime($companyInfo['date_added']);
	$dateadded = getdate($dateadded_time);
	$dateadded_time -= $dateadded['wday']*60*60*24;

if(!$payment_made)
{

  if($companyInfo['cd_payperiod'] <1) $companyInfo['cd_payperiod'] = 7;
 
	$chargeEachMonth = $dateadded_time+24*60*60*30;
	$startdate=$dateadded_time;
	$enddate=time()+24*60*60*($companyInfo['cd_paydelay']+$companyInfo['cd_payperiod']+7);

	$carryoverbalance = "N";
	
	//if(($iColCount % $companyInfo['cd_payperiod']) == (($companyInfo['cd_paydelay']+$companyInfo['cd_paystartday'])%7)){

	$hide_company = false;
	// Start Info

		$curmonth+(($companyInfo['rd_paydelay']-1))*24*60*60;
		$thisdateinfo=getdate($thisdate);
			// Start Date Code
		if(!$payment_made){
		
			if($carryoverbalance != "N")$balance = $carryoverbalance;
			else $balance = -$companyInfo['rd_appfee'];
			
			if($datejump < (24*60*60)) $datejump = (24*60*60*7);
			if ($startdate > $thisdate) $balance = 0;
			
				//print("N=".$numOfMonths);
			for($i=0;$i<=$numOfMonths;$i++)
			{
				$rollOverApplied = $rollOverAppliedNext;
				if ($balance>=$companyInfo['rd_rollover']) 
				{
					$balance = 0;
				}
				else
				{
					$rollOverAppliedNext = $balance;
				}
				$thismonth = $i % 12 + 1;
				$thisyear = floor($i/12);
				$Net = calcNet($thismonth,$thisyear,$companyInfo);
				$NetApplied=$Net;
				$balance+=$Net;
				$finalbalance = $balance;
			}
			$startdate = $i;
			$startdate = $i;
			$carryoverbalance = $balance;

			$balance = $finalbalance;
			
			$rollover = "No Balance";		
			if(($balance < $companyInfo['rd_rollover']))
			{
				if($balance != 0)$rollover = "Rollover";
				$wireFeeApplied=0;
			}
			else 
			{
				if($balance != 0)$rollover = "To Be Payed";
				if($balance != 0) $payment = "<a href='ResellerInvoiceSummary.php?mode=makepayment&reseller_id=".$companyInfo['reseller_id']."&paydate=$thisdate' >Pay Company</a>";
				$balance -= $companyInfo['rd_wirefee'];
				$wireFeeApplied = -$companyInfo['rd_wirefee'];
			}
		}
	
}
		if($payment_made==true) $payment = "";
		if($payment_made==true) $rollover = "<span class='red'>Payed</span>";
		
		// End Date Code
	
	$color="Black";
	
		if (!$hide_company){
	?>
            <tr>
              <td><span class="style4"><?=$forcomp?></span></td>
              <td><span class="style4"><?=date("F jS",$thisdate);?></span></td>
              <td><span class="style4"><a href='ResellerInvoiceSummary.php?reseller_id=<?=$companyInfo['reseller_id']?>&paydate=<?=$thisdate?>' ><?=($balance==0?"- None -":number_format( $balance, 2,".",","))?></a></span></td>
              <td><?=$rollover?></td>
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
    <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
    <td width="1%" ><img src="images/menubtmright.gif"></td>
  </tr>
</table></div>
  <p>&nbsp;  </p>
<?php
include("includes/footer.php");

function func_dayofweek($iday)
{
	$strday="";
	switch ($iday) { 
     case 1: 
      $strday="Sunday";
       break; 
     case 2: 
      $strday="Monday";
       break; 
     case 3: 
      $strday="Tuesday";
       break;
	   case 4: 
      $strday="Wednesday";
       break;
	   case 5: 
      $strday="Thursday";
       break; 
     case 6: 
      $strday="Friday";
       break;
	   case 7: 
      $strday="Saturday";
       break;
	   
	} 
	return $strday;
}
//function for roundidng of
function func_roundidndg( $float, $precision ) 
{ 
  // $float is really a float value here! 
  // e.g. $float === 3.0249999999; 
  $float = round( $float*100 )/100; 
  return $float; 
} 

function calcNet($thismonth,$thisyear,$companyInfo)
{
	global $cnn_cs;
	global $sessionlogin;
	
	$table = "`cs_transactiondetails` as t left join `cs_companydetails` as c on (`company_user_id` = c.userId OR t.`userId` = c.userId)";
	
	$compSQL = "AND `reseller_id` = '".$companyInfo['reseller_id']."' AND activeuser=1 ";
	$compID = $sessionlogin;
	//if (!$compID) $compID = -1;
	//$compSQL = "AND (`company_user_id` = '$compID' OR `userId` = '$compID')";
	
	//print($thismonth." ".$thisyear."<BR>");
	$toyear = $thisyear;
	$tomonth = $thismonth+1;
	if($tomonth>12) $toyear = $thisyear+1;
	if($tomonth>12) $tomonth = 1;
	
	$fdate = getdate(mktime(0,0,0,$thismonth,1,$thisyear+2002));//getdate($thisdate-60*60*24*($fdaysback));
	$tdate = getdate(mktime(0,0,0,$tomonth,0,$toyear+2002));
	$pdate = getdate($thisdate);
	
	$stats_list = "";
	$fromdate = $fdate['year']."-".$fdate['mon']."-".$fdate['mday']." 00:00:00";
	$todate = $tdate['year']."-".$tdate['mon']."-".$tdate['mday']." 23:59:59";
	$paydate = $pdate['year']."-".$pdate['mon']."-".$pdate['mday'];
			
  	$selection = "*(`r_reseller_discount_rate`/100)";
	$drange = "'".$fromdate."' and '".$todate."'";	
	//print($thismonth." ".$thisyear." ".$drange."<BR>");
	$includeCHW = false;
	include('../includes/netcalc.php');
  
		$res_Discount_Net = $Net;
		$res_Trans_Fee = $transactionfee['resamt']-$declined_transactionfee['resamt'];
		$res_Amount_Earned = $res_Discount_Net+$res_Trans_Fee;
	return $res_Amount_Earned;
	//$futureamount=number_format( $Net,2,".",",");//func_futureamount($cnn_cs,$str_year_from,$str_month_from,$iDisplayNumber,$sessionlogin);

}
?>