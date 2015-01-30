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
include '../includes/dbconnection.php';
$headerInclude="ledgers";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';


require_once( '../includes/function.php');
include '../includes/function1.php';
$admin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

//$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE 1 $bank_sql_limit ORDER BY `companyname` ASC";	
//$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
// Need This?

$compSQL_active = "";
$compID = $_GET['selectComp'];
if($_POST['selectComp']) $compID = $_POST['selectComp'];
if ($compID == -2)  $compSQL_active = " AND activeuser=1 ";
if ($compID == -3)  $compSQL_active = " AND activeuser=0 ";
if($compID<-1)$compID=-1;
$qry_details="SELECT companyname,userId FROM `cs_companydetails` WHERE 1 $bank_sql_limit $compSQL_active ORDER BY `companyname` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

while($comp = mysql_fetch_assoc($rst_details))
{
	$compList.= "<option value='".$comp['userId']."' ".($comp['userId']==$compID?"selected":"").">".$comp['companyname']."</option>";
}
$compSQL = "$compSQL_active";
if (!$compID) $compID = -1;
if ($compID > -1) $compSQL = "AND (`company_user_id` = '$compID' OR t.`userId` = '$compID')";
			
$qry_details="SELECT * FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);


if ($compID == -1)  {
  	$companyInfo['cd_payperiod'] = 7;
  	$companyInfo['cd_paydelay'] = 7;
  	$companyInfo['cd_appfee'] = 0;
  	$companyInfo['cs_monthly_charge'] = 0;
  	$companyInfo['cd_paystartday'] = 6;
  	$companyInfo['cd_rollover'] = 0;
  	$companyInfo['cd_wirefee'] = 0;
	$companyInfo['userId'] = -1;
	$companyInfo['date_added'] = "2002-01-01";
	$pSAllCompanys=true;
}

$str_month_from = (isset($_GET["cbo_from_month"])?quote_smart($_GET["cbo_from_month"]):"");
$str_year_from = (isset($_GET["cbo_from_year"])?quote_smart($_GET["cbo_from_year"]):"");
if($str_month_from == ""){
	$str_day_from = date("j");
	$str_month_from = date("m");
	$str_year_from = date("Y");
} 
		
$thisdate=strtotime($str_year_from."-".$str_month_from."-28");
$thisdate+=24*60*60*4;
chdir("../");
include ("includes/projSetCalc.php");

$projSettlement="";
$projSettlementPeriods="";
projSetCalc();
chdir("admin");
$forcomp = "";
if ($companyInfo['companyname']) $forcomp = " for ".$companyInfo['companyname'];

	$dateadded_time = strtotime($companyInfo['date_added']);
	$dateadded = getdate($dateadded_time);
	$dateadded_time += ($companyInfo['cd_paystartday']+$companyInfo['cd_paydelay']-$dateadded['wday']-1)*60*60*24;

if(1)
{
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
    <td width="90%" valign="top" align="center"  > <br>	
<form action="projectedsettlement.php" method="GET" name="FrmMerchant" >
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View Transactions
      <?=$forcomp?>
    </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" height="10">
	<table align="center"><tr>
	  <td ><font color="blue" size="1"  face="Verdana, Arial, Helvetica, sans-serif" >* If Previous Pay period's Balance is under '
	    <?=formatMoney( $companyInfo['cd_rollover'])?>', then the amount will roll over to the next pay period. </font></td>
                </tr></table>
	<table border="0"  align="center" cellpadding="2">
	<tr valign="middle" > <?php  

		
			
	  ?>
	  
					  <td width="50" align="right"  valign="middle"><select name="selectComp" id="selectComp">
                        <option value="-1" <?=($compID=='-1'?"selected":"")?>>All Companies</option>
                        <option value="-2" <?=($compID=='-2'?"selected":"")?>>Active Companies</option>
                        <option value="-3" <?=($compID=='-3'?"selected":"")?>>Inactive Companies</option>
                        <option value="-1">-----------------</option>
                        <?=$compList?>
                      </select></td>
					  <td width="50" align="right"  valign="middle"><select name="cbo_from_month" >
					    <?php func_fill_month($str_month_from) ?>
				      </select></td>
					  <td width="102" >
						<select name="cbo_from_year"><?php func_fill_year($str_year_from,$i_start_year,$i_end_year) ?></select></td>
					  <td width="127">
						<input type="image" name="add" src="images/submit.jpg"></input></td>
				
	  
	  </tr>
	</table>
	  
	  </td>
      </tr>
	  <tr><td colspan="5" class="lgnbd">
	  <table width="100%" border="0" cellpadding="2" align="center">
  <tr align="center" valign="middle" height='20' bgcolor="#999999"> 
    <td width="14%" height="20" class="whitehd">SUNDAY</td>
                <td width="14%" class="whitehd">MONDAY</td>
                <td width="14%" class="whitehd">TUESDAY</td>
                <td width="15%" class="whitehd">WEDNESDAY</td>
                <td width="14%" class="whitehd">THURSDAY</td>
                <td width="14%" class="whitehd">FRIDAY</td>
                <td width="15%" class="whitehd">SATURDAY</td>
  </tr>
  <?php  
 
 
$iStartDate =	date("w", mktime(0,0,0,$str_month_from,1,$str_year_from));
$iDaysInMonth =  date("t", mktime(0,0,0,$str_month_from,1,$str_year_from));
$iColCount=1;
$iMaxColCount = ($iDaysInMonth + $iStartDate -  (($iDaysInMonth + $iStartDate) % 7) + 7);
if ((($iDaysInMonth + $iStartDate) % 7) == 0){
	$iMaxColCount = $iMaxColCount -7;
}

  while($iColCount<=$iMaxColCount)

  {
  	$balance="N";$rollover="";$PayStartEnd="";$invoice="";

	$strTdBgColor = "#000000";	
	if (($iColCount>$iStartDate) && ($iDaysInMonth >= ($iColCount-$iStartDate))){
		$iDisplayNumber = $iColCount - $iStartDate;
		$i_days=$iDisplayNumber; 
	}else{
		$iDisplayNumber = "";
		$i_days=0;
	}
	$thisdate=strtotime($str_year_from."-".$str_month_from."-".$iDisplayNumber." 12:00:00");
	$thisdate_id=intval(date("ymd",$thisdate));


	if(($iColCount % 7) == 1){
		print("<tr align=center valign=middle bgcolor='#E6F2F2'>");
		
	}

	if($projSettlementPeriods[$thisdate_id]){
		$PayStartEnd = $projSettlementPeriods[$thisdate_id]['txt'];
	}

	if($projSettlement[$thisdate_id]){
		$balance=$projSettlement[$thisdate_id]['balance'];
		$invoice=$projSettlement[$thisdate_id]['invoice'];
	}	
	$color="Black";
	
	$theHref="href='InvoiceSummary.php?companyId=".$companyInfo['userId']."&paydate=$thisdate'";
	if($companyInfo['userId'] < 0) $theHref="";
	//if( $rollover){	
	print("<td  align='center' width='16%' height='30' class='maintx'  >&nbsp;  <span style=\"font-family:verdana;color:$color;font-size:11px;\">$iDisplayNumber</span>&nbsp;&nbsp;<br><span style=\"font-family:verdana;color:blue;font-size:11px;\">$invoice</span><br><font size='1' color='blue' ><a $theHref >".(($balance==="N")?"":formatMoney( $balance))."</a></font> <br><font color='red'>$rollover</font> <br><font color='red'>$PayStartEnd</font></td>");
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
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
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
//
}

include("includes/footer.php");
?>
