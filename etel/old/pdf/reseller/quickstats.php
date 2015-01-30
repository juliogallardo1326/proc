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

if(!$noheader) $headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';
echo "Quick Stats Temporarily Unavailable.";
include("includes/footer.php");

require_once( '../includes/function.php');
include '../includes/function1.php';

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionReseller =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";

$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE `reseller_id` = '$sessionReseller' AND activeuser=1 ORDER BY `companyname` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");


$compSQL = "AND `reseller_id` = '$sessionReseller' AND activeuser=1 ";
$compID = $_POST['selectComp'];
if (!$compID) $compID = -1;
if ($compID != -1)  $compSQL .= "AND (t.`userId` = '$compID')";

while($comp = mysql_fetch_assoc($rst_details))
{
	$compList.= "<option value='".$comp['userId']."' ".($comp['userId']==$compID?"selected":"").">".$comp['companyname']."</option>";
}

$qry_details="SELECT * FROM `cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_company_id` = '$compID' AND cs_hide = '0'";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteSQL = "";
$siteID = intval($_REQUEST['selectSite']);
if (!$siteID) $siteID = -1;
if ($siteID != -1)  $siteSQL = "AND td_site_ID = $siteID";
$siteList = "";

while($site = mysql_fetch_assoc($rst_details))
{
	$siteList.= "<option value='".$site['cs_ID']."' >".str_replace('http://','',$site['cs_URL'])."</option>";
}

$today = getdate();
$day = getdate();

$stats_list = "";


$stats_list[0]['title'] = "Today";
$stats_list[0]['from'] = $today['year']."-".$today['mon']."-".$today['mday']." 00:00:00";
$stats_list[0]['to'] = $today['year']."-".$today['mon']."-".$today['mday']." 23:59:59";

$day = getdate($today[0]-60*60*24*1);

$stats_list[1]['title'] = "Yesterday";
$stats_list[1]['from'] = $day['year']."-".$day['mon']."-".$day['mday']." 00:00:00";
$stats_list[1]['to'] = $day['year']."-".$day['mon']."-".$day['mday']." 23:59:59";

$day = getdate($today[0]-60*60*24*7);

$stats_list[2]['title'] = "One Week to date";
$stats_list[2]['from'] = $day['year']."-".$day['mon']."-".$day['mday']." 00:00:00";
$stats_list[2]['to'] = $today['year']."-".$today['mon']."-".$today['mday']." 23:59:59";

$lastmonth = $today['mon']-1;
if ($lastmonth <= 0) $lastmonth = 12;

$stats_list[3]['title'] = "One Month to date";
$stats_list[3]['from'] = $today['year']."-".$lastmonth."-".$today['mday']." 00:00:00";
$stats_list[3]['to'] = $today['year']."-".$today['mon']."-".$today['mday']." 23:59:59";

$stats_list[4]['title'] = "Since January 1st ($today[year])";
$stats_list[4]['from'] = ($today['year'])."-01-01 00:00:00";
$stats_list[4]['to'] = $today['year']."-".$today['mon']."-".$today['mday']." 23:59:59";

			
$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);

$forsite = "";

if ($companyInfo['companyname']) $forsite = " for ".$companyInfo['companyname'];

;?>
<script language="JavaScript">
function func_submitfuture(futureyear,futuremonth,futureday){
	obj_form = document.FrmMerchant;
	obj_form.method="post";
	obj_form.action="viewfuturedetails.php?futyear="+futureyear+"&futmonth="+futuremonth+"&futday="+futureday;
	obj_form.submit();

}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
    <td width="90%" valign="top" align="center"  > <br>	
<form action="" method="POST" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Quick Statistics<?=$forsite?></span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10"><select name="selectComp" id="selectComp">
	  <option value="-1">All Companies</option>
	  <?=$compList?>
        </select>	 <br>
		<input type="submit" value="Update">
	  Use this Dropdown List to select Statistics for individual sites. 	</td>
	</tr>
	  <tr><td colspan="5" class="lgnbd">
	  <table width="100%" border="0" cellpadding="2" align="center">
  <tr align="center" valign="middle" height='20' bgcolor="#448A99"> 
    <td  height="20" class="whitehd">&nbsp;</td>
                <td width="90"  class="whitehd">Approved</td>
                <td width="90"  class="whitehd">Declined</td>
                <td width="90"  class="whitehd">Chargebacks</td>
                <td width="90"  class="whitehd">Refund</td>
                <td width="90"  class="whitehd">Gross</td>
                <td width="90"  class="whitehd">Net Earned </td>
                <td  class="whitehd">&nbsp;</td>
  </tr>
  <?php 
  
	$table = "`cs_transactiondetails` as t left join `cs_companydetails` as c on (t.`userId` = c.userId)";
	
  for($i=0;$i<5;$i++)
  { 
 
 
		$drange = "'".$stats_list[$i]['from']."' and '".$stats_list[$i]['to']."'";	
  
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
 		$includeCHW = false; // ?????? Is this right?
 
 		include('../includes/netcalc.php');		
		

		
/*
// Reseller Net Profit Calculations

  		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$amountSum = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `status` = 'A' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$NewSales = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt, SUM(`r_chargeback`*`r_reseller_discount_rate`/100) as ded FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_chargeback` = '1' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$chargeback = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_a_rebill` = 1 AND `status` = 'A'  $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$rebillSum = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_voided_check` = 1 AND `status` = 'P' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$voidChecks = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_returned_checks` = 1 AND `status` = 'P' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$returnedChecks = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt, SUM(`r_credit`*`r_reseller_discount_rate`/100) as ded FROM $table  WHERE 1 $siteSQL $compSQL AND `cancelstatus` = 'Y' AND `reason` <> 'Chargeback' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$refundsAmount = mysql_fetch_assoc($rst_details);
				 
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_affiliate` = 1 AND `status` = 'P' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$referalSum = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`*`r_reseller_discount_rate`/100) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `status` = 'D' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$declined = mysql_fetch_assoc($rst_details);
		
// Deductions

		$qry_details="SELECT SUM(`amount`*(`r_merchant_discount_rate`/100)*`r_reseller_discount_rate`/100) as amt FROM $table  WHERE 1 $siteSQL $compSQL AND `reason` IS NULL AND `status` = 'A' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$discount = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`r_transactionfee`*`r_reseller_discount_rate`/100) as amt, SUM(`r_reseller_trans_fees`) as resamt FROM $table  WHERE 1 $siteSQL $compSQL AND (`status` = 'A' OR `status` = 'D') AND `reason` IS NULL  $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$transactionfee = mysql_fetch_assoc($rst_details);
				
		$qry_details="SELECT SUM(`amount`*(`r_reserve`/100)*`r_reseller_discount_rate`/100) as amt FROM $table  WHERE 1 $siteSQL $compSQL AND `reason` IS NULL AND `status` = 'A' $daterange";				
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$reservefee = mysql_fetch_assoc($rst_details);
		 	
		$TotalDeductions = $chargeback['ded']+$refundsAmount['ded']+$discount['amt']+$transactionfee['amt']+$reservefee['amt'];	
		
		//$Gross = $NewSales['amt']+$rebillSum['amt']+$referalSum['amt'];
		$Gross = $NewSales['amt'];
		$Gross = $Gross-$chargeback['amt']-$voidChecks['amt']-$returnedChecks['amt']-$refundsAmount['amt'];
		$res_Discount_Net = $Gross-$TotalDeductions;
		
		$total_amount = $amountSum['cnt']/100;
		if ($total_amount <= 0) $total_amount = 1;

		$res_Trans_Fee = $transactionfee['resamt'];
		$res_Amount_Earned = $res_Discount_Net+$res_Trans_Fee;
// End Reseller Net Profit


  		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table WHERE 1 $siteSQL $compSQL $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$amountSum = mysql_fetch_assoc($rst_details);

		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `reason` IS NULL AND `status` = 'A' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$NewSales = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt, SUM(`r_chargeback`) as ded FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_chargeback` = '1' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$chargeback = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_a_rebill` = 1 AND `status` = 'A'  $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$rebillSum = mysql_fetch_assoc($rst_details);
		 
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_voided_check` = 1 $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$voidChecks = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_returned_checks` = 1 $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$returnedChecks = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt, SUM(`r_credit`) as ded FROM $table  WHERE 1 $siteSQL $compSQL AND `cancelstatus` = 'Y' AND `reason` <> 'Chargeback' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$refundsAmount = mysql_fetch_assoc($rst_details);
		
		 
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `td_is_affiliate` = 1 $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$referalSum = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`amount`) as amt, COUNT(`amount`) as cnt FROM $table  WHERE 1 $siteSQL $compSQL AND `status` = 'D' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$declined = mysql_fetch_assoc($rst_details);
		
// Deductions

		$qry_details="SELECT SUM(`amount`*`r_merchant_discount_rate`/100) as amt FROM $table  WHERE 1 $siteSQL $compSQL AND `reason` IS NULL AND `status` = 'A' $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$discount = mysql_fetch_assoc($rst_details);
		
		$qry_details="SELECT SUM(`r_transactionfee`) as amt FROM $table  WHERE 1 $siteSQL $compSQL AND (`status` = 'A' OR `status` = 'D') AND `reason` IS NULL  $daterange";		
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$transactionfee = mysql_fetch_assoc($rst_details);
				
		$qry_details="SELECT SUM(`amount`*`r_reserve`/100) as amt FROM $table  WHERE 1 $siteSQL $compSQL AND `reason` IS NULL AND `status` = 'A' $daterange";			
		$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
		$reservefee = mysql_fetch_assoc($rst_details);
		 	
		$TotalDeductions = $chargeback['ded']+$refundsAmount['ded']+$discount['amt']+$transactionfee['amt']+$reservefee['amt'];
		$Gross = $NewSales['amt'];
		$Gross = $Gross-$chargeback['amt']-$voidChecks['amt']-$returnedChecks['amt']-$refundsAmount['amt'];
		$Net = $Gross-$TotalDeductions;
		
		$total_amount = $amountSum['cnt']/100;
		if ($total_amount <= 0) $total_amount = 1;
		
		*/
		
  // Main Calculation Code Ends
		  
  
  ?>
  <tr align="center" valign="middle" height='20' bgcolor="E6F2F2" style="font-size:12px " > 
    <td  height="20" ><?=$stats_list[$i]['title']?></td>
                <td  ><?=number_format( $NewSales['amt'], 2,".",",")?></td>
                <td  ><?=number_format( $declined['amt'],2,".",",")?></td>
                <td  ><?=number_format( $chargeback['amt'],2,".",",")?></td>
                <td  ><?=number_format( $refundsAmount['amt'],2,".",",")?></td>
                <td  ><?=number_format( $Gross,2,".",",")?></td>
                <td  ><a href='reportBottomSummary.php?selectComp=<?=$compID?>&crorcq=A&period=p&opt_from_full=<?=$stats_list[$i]['from']?>&opt_to_full=<?=$stats_list[$i]['to']?>' ><?=number_format( $res_Amount_Earned,2,".",",")?></a></td>
                <td  >&nbsp;</td>
  </tr>
  <?php } ?>
  </table>
	  </td>
	  </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table>
	<p>*Hint - Click on the net profit hyperlinks to see a Full Statistic for those date ranges. </p>
</form>
    </td>
     </tr>	 
</table>
<?php 
include("includes/footer.php");
?>