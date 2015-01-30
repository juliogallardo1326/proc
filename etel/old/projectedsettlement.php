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
require_once("includes/dbconnection.php");
$headerInclude="reports";
$periodhead="Ledgers";
include 'includes/header.php';

beginTable();
echo "Projected Settlement View is currently undergoing an extensive update and is currently unavailable. There will be no delays in payout as a result of the update.";
endTable("Projected Settlement Updating",'');
die();
$bank_ids = array();
$sql = "SELECT bank_id FROM cs_bank WHERE bk_ignore=0 order by bank_id asc";
$res = sql_query_read($sql) or dieLog(mysql_error());
while($bankInfo = mysql_fetch_assoc($res))
	$bank_ids[] = $bankInfo['bank_id'];
	
$str_adminapproval="";

$sessionlogin = $companyInfo['userId'];
$companyId = $companyInfo['userId'];
$str_month_from = (isset($_GET["cbo_from_month"])?quote_smart($_GET["cbo_from_month"]):"");
$str_year_from = (isset($_GET["cbo_from_year"])?quote_smart($_GET["cbo_from_year"]):"");
if($str_month_from == ""){
	$str_day_from = date("j");
	$str_month_from = date("m");
	$str_year_from = date("Y");
} 

include ("includes/projSetCalc.php");

$projdate = time()+($companyInfo['cd_payperiod'])*(60*60*24);
$startdate = strtotime(pushBackOnePeriod(0,true));
$period = $companyInfo['cd_payperiod'];

if( $companyInfo['cd_pay_bimonthly']=='bimonthly')
{
	$thisMonth = mktime(0,0,0,$str_month_from,1,$str_year_from);
	//$projSet[intval(date('ymd',$thisMonth))]=calcReal($thisMonth);
	$thisMonth = mktime(0,0,0,$str_month_from,15,$str_year_from);
	//$projSet[intval(date('ymd',$thisMonth))]=calcReal($thisMonth);
	unset($thisMonth);
}
else
{
	if($period<=0)$period=7;
	for($curdate=$startdate;$curdate<=$projdate;$curdate+=$period*(60*60*24))
	{
		$projSet[intval(date('ymd',$curdate))]=calcReal($curdate);
	}
}


$sql="select * from `cs_merchant_invoice` where mi_company_id = '$companyId' ORDER BY `mi_paydate` ASC";
$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
while($InvoiceDetails = mysql_fetch_assoc($result))
{
	$paydate=strtotime($InvoiceDetails['mi_paydate']);
	$mi_pay_info[intval(date('ymd',$paydate))] = unserialize($InvoiceDetails['mi_pay_info']);
	$mi_pay_info[intval(date('ymd',$paydate))]['mi_ID'] = $InvoiceDetails['mi_ID'];
}
$date_hold = 0;
$date_delay = 0;
$nextPayDay = strtotime($companyInfo['cd_next_pay_day']);
if($mi_pay_info[intval(date('ymd',$nextPayDay))])$nextPayDay = strtotime(pushBackOnePeriod());
$projPayDay[intval(date('ymd',$nextPayDay))]=calcReal($nextPayDay);

?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" >
<tr>
    <td width="90%" valign="top" align="center"  > <br>	
<form action="projectedsettlement.php" method="GET" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View Projected Settlement <?=$forcomp?>
    </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10">
	<table align="center"><tr>
	  <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '<?=formatMoney( $companyInfo['cd_rollover'])?>', then the amount will roll over to the next pay period. </font></td>
                </tr></table>
	<table border="0"  align="center" cellpadding="2">
	<tr valign="middle" > <?php  

		
			
	  ?>
	  
					  <td width="102" align="right"  valign="middle"> 
						<select name="cbo_from_month" ><?php func_fill_month($str_month_from) ?></select></td>
					  <td width="102" >
						<select name="cbo_from_year"><?php func_fill_year($str_year_from,$i_start_year,$i_end_year) ?></select></td>
					  <td width="127">
						<input type="image" name="add" src="images/submit.jpg"></input></td>
				
	  
	  </tr>
	</table>
	  
	  </td>
      </tr>
	  <tr><td colspan="5" class="lgnbd">
	  <table width="100%" border="0" cellpadding="2" align="center" class="invoice">
  <tr align="center" valign="middle" height='20'> 
    <td width="14%" height="20" class="infoHeader">SUNDAY</td>
                <td width="14%" class="infoHeader">MONDAY</td>
                <td width="14%" class="infoHeader">TUESDAY</td>
                <td width="15%" class="infoHeader">WEDNESDAY</td>
                <td width="14%" class="infoHeader">THURSDAY</td>
                <td width="14%" class="infoHeader">FRIDAY</td>
                <td width="15%" class="infoHeader">SATURDAY</td>
  </tr>
  <?php  
 
 
$iStartDate =	date("w", mktime(0,0,0,$str_month_from,1,$str_year_from));
$iDaysInMonth =  date("t", mktime(0,0,0,$str_month_from,1,$str_year_from));
$iColCount=1;
$iMaxColCount = ($iDaysInMonth + $iStartDate -  (($iDaysInMonth + $iStartDate) % 7) + 7);
if ((($iDaysInMonth + $iStartDate) % 7) == 0){
	$iMaxColCount = $iMaxColCount -7;
}
$cellcolorArray = array("e6f2f2", "e6f2f2", "c2dede", "e6f2f2", "e6f2f2");
$cellcolor = "82bcbb";
  while($iColCount<=$iMaxColCount)

  {
  	$balance="N";$rollover="";$PayStartEnd="";$invoice="";$bk='bk1';$iDisplayNumber=0;$balanceDisplay="";

	$strTdBgColor = "#000000";	
	if (($iColCount>$iStartDate) && ($iDaysInMonth >= ($iColCount-$iStartDate))){
		$iDisplayNumber = $iColCount - $iStartDate;
		$i_days=$iDisplayNumber; 
	}else{
		$iDisplayNumber = 0;
		$i_days=0;
	}
	
	if($iDisplayNumber)$bk='bk2';
	else $bk='bk1';
	
	$thisdate=strtotime($str_year_from."-".$str_month_from."-".$iDisplayNumber." 12:00:00");
	$thisdate_id=intval(date("ymd",$thisdate));


	$cellcolor = $cellcolorArray[1];
	if(($iColCount % 7) == 1){
		print("<tr align=center valign=middle bgcolor='#E6F2F2'>");
	}


	if($mi_pay_info[$thisdate_id]){
		$invoice = "Payment Invoice";
		$balance=$mi_pay_info[$thisdate_id]['Balance'];
		if($balance!=='N') $balanceDisplay = "<a href='viewCompanyInvoice.php?mi_ID=".$mi_pay_info[$thisdate_id]['mi_ID']."&focus=invoice'>".formatMoney( $balance)."</a>";
		$bk='bk4';
	}	
	else if($projPayDay[$thisdate_id]){
		$balance=$projPayDay[$thisdate_id]['Balance'];
		if($balance!=='N') $balanceDisplay = "<a href='viewCompanyInvoice.php?focus=payDay&thisdate=$thisdate'>".formatMoney( $balance)."</a>";
		$rollover=$projPayDay[$thisdate_id]['Status'];
		$bk='bk5';
		$lastbalance=0;
	}	
	else if($projSet[$thisdate_id]){
		$balance=$projSet[$thisdate_id]['ProjectedSales']-$lastbalance;		
		if($balance!=='N') $balanceDisplay = "<a href='viewCompanyInvoice.php?focus=projSet&thisdate=$thisdate'>".formatMoney( $balance)."</a>";
		$lastbalance=$projSet[$thisdate_id]['ProjectedSales'];
		$bk='bk3';
	}	
	$color="Black";
	

	
	print("<td  align='center' width='16%' height='30' class='$bk' >&nbsp;");  
	
	if($iDisplayNumber){
		print("<span style='font-size:11px;'>$iDisplayNumber</span>&nbsp;&nbsp;<br>
		$invoice<br>$balanceDisplay<br><span class='$rollover'>$rollover</span>");  
	}
	
	print("</td>");
	//}
	//else
	//print("<td  align='center' width='16%' height='30' class='maintx'  >&nbsp;  <span style=\"font-family:verdana;color:$color;font-size:11px;\">$iDisplayNumber</span>&nbsp;&nbsp;<br><a href='javascript:func_submit($iDisplayNumber,$str_month_from,$str_year_from);'  class='link1'><span style=\"font-family:verdana;color:blue;font-size:11px;\">$amountarray[$i_days] </span><br><font size='1' color='red'>$approvedamt[$i_days]</font></a><br><a href='javascript:func_submitfuture($str_year_from,$str_month_from,$iDisplayNumber);' class='link1'><font color='red'>$futureamount</font></a></td>");
	if(($iColCount % 7) == 0)		{
		print("</tr>");
			}
	 $iColCount = $iColCount + 1;
  }

  ?>
    <tr align="center" valign="middle" height='20'> 
    <td height="20" colspan="1" class="infoBold">Legend: 
	</td>
                <td height="20" colspan="6" class="infoBold" align="right">Invoice: <img style="width:12; height:12" class="bk4" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Projected Payday: <img style="width:12; height:12" class="bk5" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Sales: <img style="width:12; height:12" class="bk3" > </td>
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

?>
<script language="JavaScript" type="text/JavaScript">
var str_approval;
function func_submit(iday,imonth,iyear)
{
	var str_approval='<?= $str_adminapproval?>';
	obj_form = document.FrmMerchant;
	//obj_form.method="GET";
	//obj_form.action="viewdetails.php?day="+iday;	
	window.open("viewdetails.php?frommonth="+imonth+"&day="+iday+"&fromyear="+iyear+"&adminapprove="+str_approval,"ViewDetails","'status=1,scrollbars=1,width=800,height=500,left=0,top=0");

	obj_form.submit();
}

</script>
<?php 
include("includes/footer.php");
?>
