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
require_once( '../includes/projSetCalc.php');
include '../includes/function1.php';
$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$qry_company="select * from cs_resellerdetails where reseller_id ='$sessionlogin'";
$gatewayid=-1;
$rst_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
$companyInfo=mysql_fetch_assoc($rst_details);

$proj_ri_pay_info = calcResellerReal();

$str_month_from = (isset($_GET["cbo_from_month"])?trim($_GET["cbo_from_month"]):"");
$str_year_from = (isset($_GET["cbo_from_year"])?trim($_GET["cbo_from_year"]):"");
if($str_month_from == ""){
	$str_day_from = date("j");
	$str_month_from = date("m");
	$str_year_from = date("Y");
} 
		   
if($gatewayid==-1)
{
?>
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
<form action="projectedsettlement.php" method="GET" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View Transactions</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10">
	<table align="center"><tr>
	  <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Net is under '
	    <?=number_format( $companyInfo['rd_rollover'], 2,".",",")?>', then the amount will roll over to the next pay period. </font></td>
                </tr></table>
	<table border="0"  align="center" cellpadding="2">
	<tr valign="middle" >
	  
					  <td width="102" align="right"  valign="middle"> 
						<select name="cbo_from_month" ><?php func_fill_month($str_month_from) ?></select></td>
					  <td width="102" >
						<select name="cbo_from_year"><?php func_fill_year($str_year_from,$i_start_year,$i_end_year) ?></select></td>
					  <td width="127">
						<input type="image" name="add" src="../images/submit.jpg"></input></td>
				
	  
	  </tr>
	</table>
	  
	  </td>
      </tr>
	  <tr><td colspan="5" class="lgnbd">
	  <table width="100%" border="0" cellpadding="2" align="center">
  <tr align="center" valign="middle" height='20' bgcolor="#448A99"> 
    <td width="14%" height="20" class="whitehd">SUNDAY</td>
                <td width="14%" class="whitehd">MONDAY</td>
                <td width="14%" class="whitehd">TUESDAY</td>
                <td width="15%" class="whitehd">WEDNESDAY</td>
                <td width="14%" class="whitehd">THURSDAY</td>
                <td width="14%" class="whitehd">FRIDAY</td>
                <td width="15%" class="whitehd">SATURDAY</td>
  </tr>
  <?php  
 //set_time_limit(10);


 
  $iStartDate =	date("w", mktime(0,0,0,$str_month_from,1,$str_year_from));
 $iDaysInMonth =  date("t", mktime(0,0,0,$str_month_from,1,$str_year_from));
$iColCount=1;
  $iMaxColCount = ($iDaysInMonth + $iStartDate -  (($iDaysInMonth + $iStartDate) % 7) + 7);
if ((($iDaysInMonth + $iStartDate) % 7) == 0){
  $iMaxColCount = $iMaxColCount -7;
 }
	
	
	//$fdaysback = $companyInfo['cd_paydelay']+$companyInfo['cd_paystartday'];
	$startdate=strtotime('2002-01-01');
	$numOfMonths=$str_month_from+($str_year_from-2002)*12-2;
	$carryoverbalance = "N";
	// Start Info
	$thisdate=$_GET['paydate'];
	$rd_paydelay =$companyInfo['rd_paydelay'];
	if($rd_paydelay <1) $rd_paydelay = 7;
	
  while($iColCount<=$iMaxColCount)

  {
    $futureamount="";$thisdate="";$rollover = "";$Net="";$balance="N";$payment_made=false;
	$strTdBgColor = "#000000";	
	  	if (($iColCount>$iStartDate) && ($iDaysInMonth >= ($iColCount-$iStartDate))){
  		$iDisplayNumber = $iColCount - $iStartDate;
		 $i_days=$iDisplayNumber; 
		//$strTdBgColor = funcChooseColor($strWorkSendDate,$i_userid,$cnn_pm);
		  
		//$strWorkLogSent=$strWorkSendDate."//".date("Y-n-d");
		if(date("Y-m-d", mktime(0,0,0,$str_month_from,($iColCount-$iStartDate),$str_year_from)) > date("Y-m-d"))
		{
			//"<strong>X</strong>".$strWorkSendDate."//".date("Y-n-d");
		}
		
	}else{
		$iDisplayNumber = "";
		$i_days=0;
	}


	if(($iColCount % 7) == 1){
		print("<tr align=center valign=middle bgcolor='#E6F2F2'>");
		
	}
	
	$thisdate=strtotime($str_year_from."-".$str_month_from."-".$iDisplayNumber);

	
	
	if(($thisdate== $proj_ri_pay_info['thisdate'])){
		$balance=$proj_ri_pay_info['Balance'];
		if($balance!=='N') $balanceDisplay = formatMoney( $balance);
		//$lastbalance=$ri_pay_info['ProjectedSales'];
		$bk='bk3';

		$color="Black";
	}	

	$color="Black";
	if($payment_made==true) $rollover = "Payed";
	//if( $rollover){	
	print("<td  align='center' width='16%' height='30' class='maintx'  >&nbsp;  <span style=\"font-family:verdana;color:$color;font-size:11px;\">$iDisplayNumber</span>&nbsp;&nbsp;<br><span style=\"font-family:verdana;color:blue;font-size:11px;\">$rollover</span><br><font size='1' color='blue' >".(($balance==="N")?"":number_format( $balance, 2,".",","))."</font> <br><font color='red'>$futureamount</font></td>");
	//}
	//else
	//print("<td  align='center' width='16%' height='30' class='maintx'  >&nbsp;  <span style=\"font-family:verdana;color:$color;font-size:11px;\">$iDisplayNumber</span>&nbsp;&nbsp;<br><a href='javascript:func_submit($iDisplayNumber,$str_month_from,$str_year_from);'  class='link1'><span style=\"font-family:verdana;color:blue;font-size:11px;\">$amountarray[$i_days] </span><br><font size='1' color='red'>$approvedamt[$i_days]</font></a><br><a href='javascript:func_submitfuture($str_year_from,$str_month_from,$iDisplayNumber);' class='link1'><font color='red'>$futureamount</font></a></td>");
	if(($iColCount % 7) == 0)		{
		print("</tr>");
			}
	 $iColCount = $iColCount + 1;
  }

  ?>
  </table>
	  </td>
	  </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table>
	</form>
    </td>
     </tr>	 
</table>
<?php
}
else
{
$headerInclude="blank";
//$periodhead="Ledgers";
//include 'includes/topheader.php';
}

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

?>