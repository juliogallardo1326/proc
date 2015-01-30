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
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";

$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$reseller_id = (isset($_GET["reseller_id"])?quote_smart($_GET["reseller_id"]):"");
	
$qry_company="select * from cs_resellerdetails where reseller_id ='$reseller_id'";
$gatewayid=-1;
$rst_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
$companyInfo=mysql_fetch_assoc($rst_details);
if($gatewayid==-1)
{
$payment_made = false;
$new_invoice = false;
$newinvoicemsg = "";

$thisdate=$_GET['paydate'];
$thisdateinfo=getdate($thisdate);
$numOfMonths=$thisdateinfo['mon']+($thisdateinfo['year']-2002)*12-2;

$monthid=$thisdateinfo['year'].$thisdateinfo['mon'];

$inv_details="";
$sql = "select * from `cs_reseller_invoice_history` where `ri_monthid` = '$monthid' AND `ri_reseller_id`=".$companyInfo['reseller_id']."";
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
if ($companyInfo['reseller_companyname']) $forcomp = " for ".$companyInfo['reseller_companyname'];
	$dateadded_time = strtotime($companyInfo['date_added']);
	$dateadded = getdate($dateadded_time);
	$dateadded_time -= $dateadded['wday']*60*60*24;
	
if(!$payment_made)
{

  if($companyInfo['cd_payperiod'] <1) $companyInfo['cd_payperiod'] = 7;
 
	set_time_limit(10);
	
	$chargeEachMonth = $dateadded_time+24*60*60*30;
	$startdate=$dateadded_time;
	$enddate=time()+24*60*60*($companyInfo['cd_paydelay']+$companyInfo['cd_payperiod']+7);

	$carryoverbalance = "N";
	
	//if(($iColCount % $companyInfo['cd_payperiod']) == (($companyInfo['cd_paydelay']+$companyInfo['cd_paystartday'])%7)){

	$hide_company = false;
	// Start Info

			// Start Date Code
		if(!$payment_made){
		
			if($carryoverbalance != "N")$balance = $carryoverbalance;
			else $balance = -$companyInfo['rd_appfee'];
			
			if($datejump < (24*60*60)) $datejump = (24*60*60*7);
			if ($startdate > $thisdate) $balance = 0;
			
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
				$balance -= $companyInfo['rd_wirefee'];
				$wireFeeApplied = -$companyInfo['rd_wirefee'];
			}
		}
	
}
		if($payment_made==true) $payment = "";
		if($payment_made==true) $rollover = "<span class='red'>Payed</span>";
		
		
		
		// End Date Code

	$color="Black";
	

	if(($_GET['mode']=='makepayment') && (!$payment_made))
	{
		$companyInfo['ri_monthid'] = $monthid;
		$companyInfo['ri_net'] = $NetApplied;
		$companyInfo['ri_rollover'] = $rollOverApplied;
		$companyInfo['ri_monthlyfee'] = $monthlyFeeApplied;
		$companyInfo['ri_wirefee'] = $wireFeeApplied;
		$companyInfo['ri_balance'] = $balance;

		$sql="ri_reseller_id='".$companyInfo['reseller_id']."', ri_date=NOW(), ri_monthid='".$companyInfo['ri_monthid']."', ri_reseller_companyname='".$companyInfo['reseller_companyname']."', ri_reseller_contactname='".$companyInfo['reseller_contactname']."', 
		ri_net='".$companyInfo['ri_net']."', ri_rollover='".$companyInfo['ri_rollover']."', ri_monthlyfee='".$companyInfo['ri_monthlyfee']."', ri_wirefee='".$companyInfo['ri_wirefee']."', ri_balance='".$companyInfo['ri_balance']."', 
		rd_payperiod='".$companyInfo['rd_payperiod']."', rd_paystartday='".$companyInfo['rd_paystartday']."', 
		rd_paydelay='".$companyInfo['rd_paydelay']."', rd_rollover='".$companyInfo['rd_rollover']."', rd_wirefee='".$companyInfo['rd_wirefee']."', rd_appfee='".$companyInfo['rd_appfee']."'";

		$sql = "REPLACE INTO `cs_reseller_invoice_history` SET $sql ";
		$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
		$payment_made = true;
		$new_invoice = true;
	}
	
		if($payment_made==true) $rollover = "Payed on ".date("m-d-y",strtotime($invoiceInfo['ri_date']));
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
                <?=number_format( $companyInfo['rd_rollover'], 2,".",",")?>
                ', then the amount will roll over to this pay period. Likewise, if this Pay period's Balance is under '
                <?=number_format( $companyInfo['rd_rollover'], 2,".",",")?>', it will be rolled over to the next period.</font></td>
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
              <td width="157" bgcolor="#CCCCCC"><div align="right" class="style5">Date</div></td>
              <td width="275"><span class="style5">
                <?=date("l - F j, Y",$thisdate)?>
              </span></td>
            </tr>
            <tr height="20">
              <td width="157"></td>
              <td width="275"></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Net Applied </span></div></td>
              <td><span class="style1">
                <?=($NetApplied==0?"- None -":number_format( $NetApplied, 2,".",","))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Roll Over </span></div></td>
              <td><span class="style1">
                <?=($rollOverApplied==0?"- None -":number_format( $rollOverApplied, 2,".",","))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Monthly Fee </span></div></td>
              <td><span class="style1">
                <?=($monthlyFeeApplied==0?"- None -":number_format( $monthlyFeeApplied, 2,".",","))?>
              </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style1">Wire Fee </span></div></td>
              <td>
                  <span class="style1">
                <?=($wireFeeApplied==0?"- None -":number_format( $wireFeeApplied, 2,".",","))?>
                  </span></td>
            </tr>
            <tr>
              <td><div align="right"><span class="style4">Balance </span></div></td>
              <td><span class="style4">
                <?=($balance==0?"- None -":number_format( $balance, 2,".",","))?>
                </span></td>
            </tr>
            <tr height="25">
              <td><div align="right"><span class="style4">Status</span></div></td>
              <td><span class="style7">
                <?=$rollover?></span></td>
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
  <?


}
else
{
$headerInclude="blank";
//$periodhead="Ledgers";
//
}

?>
</p>
<p>  
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
</p>
<?php 
include("includes/footer.php");
?>
<?php 

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