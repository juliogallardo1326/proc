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

$allowBank=true;
$headerInclude="ledgers";
$display_stat_wait = true;
$forceProfitUpdate = true;
include("includes/header.php");
require_once ("../includes/projSetCalc.php");

//print_r(func_get_enum_data('cs_banlist','bl_type',''));


if(@strtotime($_REQUEST['from_date'])<1 || !$_REQUEST['from_date'])$_REQUEST['from_date']= date("m/1/Y");
if(@strtotime($_REQUEST['to_date'])<1 || !$_REQUEST['to_date'])$_REQUEST['to_date']= date("m/t/Y");
$to_date=strtotime($_REQUEST['to_date']);
$from_date=strtotime($_REQUEST['from_date']);
$_REQUEST['from_date']= @date("m/d/Y",$from_date);
$_REQUEST['to_date']= @date("m/d/Y",$to_date);

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
//print_r($_REQUEST);
$show_rollover = $_REQUEST['show_rollover'];
$show_active = $_REQUEST['show_active'];
if(!$show_rollover) $show_rollover = 'p';
if(!$show_active) $show_active = 'a';
$forceProfitView = false;
$bankInfo = NULL;



$invoice_options="";
$bank_options="";
$bank_select=NULL;

?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
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
<p>&nbsp;</p>
<div align="center">
                <?php  

$bank_select['bank_id']=15;
if(!$_REQUEST['bank_id']) $_REQUEST['bank_id']=$bank_select['bank_id'];
$bank_id = intval($_REQUEST['bank_id']);
if($adminInfo['li_level'] == 'full')
{
}
else
{
	if ($adminInfo['li_level'] == 'bank') $bank_id = $adminInfo['li_bank'];
	else if ($resellerInfo['isMasterMerchant']) $bank_id = $resellerInfo['rd_subgateway_bank_id'];
	else dieLog("You do not have access to this page.");
}
//$bank_id_sql = " AND bank_id = $bank_id";
if (!$adminInfo['li_show_all_profit']) $bank_id_sql = " and 0";//bank_id = '".$adminInfo['li_bank']."' ";


// Calendar View	

	if(beginCacheTable("BI_".$etel_trans_update_key."_".getRequestHash(),time()+60*60))
	{

$qry_company="SELECT * FROM `cs_bank` WHERE 1 $bank_id_sql";

$bank_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");

//updateCompanyPayStatus();
while($bankInfo=mysql_fetch_assoc($bank_details))
{
	unset($bankInfo['bk_defaults']);
	$bank_options.="<option value='".$bankInfo['bank_id']."' ".($bankInfo['bank_id']==$_REQUEST['bank_id']?"selected":"").">".$bankInfo['bank_name']."</option>\n";

	$cs_bank_invoice = NULL;
	$bi_pay_info = NULL;
	$date_hold = 0;
	$date_delay = 0;


	//$bi_pay_info = updateBankInvoice(time());
	/*
	$sql = "SELECT * FROM `cs_bank_invoice` WHERE `bi_bank_id` = '".$bankInfo['bank_id']."' AND `bi_date` between '".date("Y-m-d",$from_date)."' AND '".date("Y-m-d",$to_date)."'";
	$result = mysql_query($sql,$cnn_cs);
	$invoiceNum = mysql_num_rows($result);
	if($invoiceNum)
	{
		$cs_bank_invoice = NULL;
		$bi_pay_info = NULL;
		while ($cs_bank_invoice = mysql_fetch_assoc($result)) 
		{
			$bi_pay_info_item = @unserialize($cs_bank_invoice['bi_pay_info']);
			
			$bi_pay_info['TotalProfit'] += $bi_pay_info_item['TotalProfit'];
			$bi_pay_info['Profit'] += $bi_pay_info_item['Profit'];
			$bi_pay_info['Deductions'] += $bi_pay_info_item['Deductions']+$bi_pay_info_item['WireFee'];
			
			$invoice_options.="<option value='".$cs_bank_invoice['bi_ID']."'>".$cs_bank_invoice['bi_title']."</option>\n";
			if($invoiceNum==1) 
			{
				$bi_pay_info['bi_ID'] = $cs_bank_invoice['bi_ID']; 
				$bi_pay_info['Status'] = $bi_pay_info_item['Status']; 
			}
		}
	}
	if($bi_pay_info['TotalProfit']>=$bank_select['TotalProfit'])
	{
		$bank_select['bank_id']=$bankInfo['bank_id'];
		$bank_select['TotalProfit']=$bi_pay_info['TotalProfit'];
	}
	?>
                <form name="action" method="post">
                  <input type="hidden" name="userId" value='<?=$bankInfo['bank_id']?>'>
                  <tr>
                    <td class="info"><?php if($adminInfo['li_level'] == 'full') { ?>
                      <a href="company_banklist.php?id=<?=$bankInfo['bank_id']?>">
                      <? } ?>
                      <?=$bankInfo['bank_name']?>
                      <?php if($adminInfo['li_level'] == 'full') { ?>
                      </a>
                      <? } ?>
                      (
                      <?=$invoiceNum?>
                      ) </td>
                    <td class="info"><?=($bi_pay_info['Profit']==0?"- None -":"".formatMoney($bi_pay_info['Profit'])."</a>")?></td>
                    <td class="info"><?=($bi_pay_info['Deductions']==0?"- None -":"".formatMoney($bi_pay_info['Deductions'])."</a>")?></td>
                    <td class="infoBold">
					<?php if($bi_pay_info['bi_ID']) { ?><a href='viewBankInvoice.php?bi_ID=<?=$bi_pay_info['bi_ID']?>' ><?php } ?>
                      <?=($bi_pay_info['TotalProfit']==0?"- None -":"".formatMoney($bi_pay_info['TotalProfit'])."</a>")?>
                      <?php if(!$bi_pay_info['bi_ID']) { ?></a><? } ?>
                    </td>
                    <td class="<?=$bi_pay_info['Status']?>"><?=$bi_pay_info['Status']?>
                    </td>
                  </tr>
                </form>
                <?php
	*/
}


//beginTable();
?> 
<table width="750"  border="1" cellspacing="2" cellpadding="2" class="invoice">
<?php

$qry_company="SELECT * FROM `cs_bank` WHERE `bank_id` = '$bank_id'";
$bank_details=mysql_query($qry_company,$cnn_cs) or dieLog(mysql_error());
$bankInfo=mysql_fetch_assoc($bank_details);
$bk_commission = 100;
if($_REQUEST['bk_commission']) $bk_commission = $_REQUEST['bk_commission'];
if($_REQUEST['bk_payment_type']) $bankInfo['bk_payment_type'] = $_REQUEST['bk_payment_type'];

if($adminInfo['li_show_all_profit']){
$month = date("m"); if ($_REQUEST['month']) $month = $_REQUEST['month']; 
$year = date("Y"); if ($_REQUEST['year']) $year = $_REQUEST['year']; 
?>
  <tr>
    <td height="37" colspan="4">
Choose Bank
<select name="bank_id" id="bank_id">
  <?=$bank_options?>
</select>
<br />
Calculate Projected Payouts: 
<input type="checkbox" name="calc_payout" value="1" />

<br />
<br /></td>
    <td colspan="3">Choose Month:
      <select name="month" id="month">
        <option value="1" <?=$month==1?"Selected":""?>>January</option>
        <option value="2" <?=$month==2?"Selected":""?>>February</option>
        <option value="3" <?=$month==3?"Selected":""?>>March</option>
        <option value="4" <?=$month==4?"Selected":""?>>April</option>
        <option value="5" <?=$month==5?"Selected":""?>>May</option>
        <option value="6" <?=$month==6?"Selected":""?>>June</option>
        <option value="7" <?=$month==7?"Selected":""?>>July</option>
        <option value="8" <?=$month==8?"Selected":""?>>August</option>
        <option value="9" <?=$month==9?"Selected":""?>>September</option>
        <option value="10" <?=$month==10?"Selected":""?>>October</option>
        <option value="11" <?=$month==11?"Selected":""?>>November</option>
        <option value="12" <?=$month==12?"Selected":""?>>December</option>
      </select><BR />Choose Year:
      <select name="year" id="year">
        <option value="2003" <?=$year==2003?"Selected":""?>>2003</option>
        <option value="2004" <?=$year==2004?"Selected":""?>>2004</option>
        <option value="2005" <?=$year==2005?"Selected":""?>>2005</option>
        <option value="2006" <?=$year==2006?"Selected":""?>>2006</option>
        <option value="2007" <?=$year==2007?"Selected":""?>>2007</option>
      </select>
      <input name="from_date" type="hidden" id="from_date" value="<?=$_REQUEST['from_date']?>">
      <input name="to_date" type="hidden" id="to_date" value="<?=$_REQUEST['to_date']?>"></td>
  </tr>
  <tr>
    <td colspan="2" > Show: 
      <select name="bk_payment_type" id="bk_payment_type">
	  <option value="">Default</option>
        <?=func_get_enum_values('cs_bank','bk_payment_type',$_REQUEST['bk_payment_type'])?>
      </select></td>
    <td colspan="2"> With Commission
      <input name="bk_commission" type="text" id="bk_commission" value="<?=$bk_commission?>" size="6">
%</td>
    <td colspan="3">
<input name="Submit" type="submit" value="Update">
  
  </td></tr>
<script language="javascript">
document.getElementById('bank_id').value = '<?=$_REQUEST['bank_id']?>';
</script>
<?php } ?>
<BR>
<?php

  

$month = $_REQUEST['month'];
if(!$month) $month = date("m");
if(!$year) $year = date("Y");
$curMonth = strtotime(date("$year-$month-01"));
$curMonthInfo = getdate($curMonth);
$numberOfDays = intval(date("t",$curMonth));

$from_date=$curMonth+(-$curMonthInfo['wday']+1)*24*60*60;
$to_date=$curMonth+(-$curMonthInfo['wday']+35)*24*60*60;

$sql = "SELECT * FROM `cs_bank_invoice` WHERE `bi_bank_id` = '$bank_id' AND `bi_date` between '".date("Y-m-d",$from_date-15*24*60*60)."' AND '".date("Y-m-d",$to_date)."' order by bi_date asc";
$invoiceTotalBalance = 0;
$invoiceEstBalance = 0;
$result = mysql_query($sql,$cnn_cs);
$invoiceNum = mysql_num_rows($result);
if($invoiceNum)
{
	$cs_bank_invoice = NULL;
	$bi_pay_info_month = NULL;
	while ($cs_bank_invoice = mysql_fetch_assoc($result)) 
	{
		$bi_pay_info_item = @unserialize($cs_bank_invoice['bi_pay_info']);
		$bi_pay_info_item['bi_ID'] = $cs_bank_invoice['bi_ID'];
		$bi_pay_info_item['bi_download_count'] = $cs_bank_invoice['bi_download_count'];
		$invoiceDate = strtotime($cs_bank_invoice['bi_date']);
		$bi_pay_info_month[$invoiceDate]=$bi_pay_info_item;
		$invoice_options.="<option value='".$cs_bank_invoice['bi_ID']."'>".$cs_bank_invoice['bi_title']."</option>\n";
		if(date("m",$curMonth)==date("m",$invoiceDate)) $invoiceTotalBalance+=$cs_bank_invoice['bi_balance'];
	}
}
	$calcInfo=null;
  	$rollOverProfit=0;
	$forceCalc = false;
	$lastInvoiceDate = $invoiceDate;
?>
  <tr class="infoHeader2">
    <td colspan="7"><?=$curMonthInfo['month'].", $year - ".$bankInfo['bank_name']." - Payment Type '".ucfirst($bankInfo['bk_payment_type'])."'"?></td>
  </tr>
  <tr class="infoHeader" align="center">
    <td width="100">Sunday</td>
    <td width="100">Monday</td>
    <td width="100">Tuesday</td>
    <td width="100">Wednesday</td>
    <td width="100">Thursday</td>
    <td width="100">Friday</td>
    <td width="100">Saturday</td>
  </tr>
  <?php
	$calSize = 35;
	if(-$curMonthInfo['wday']-6<=-11) $calSize = 42;
	for($curday = -$curMonthInfo['wday']-21;$curday<=-$curMonthInfo['wday']+$calSize;$curday++)
	{
		$class = 'bk2';
		$content = "";
		$curDayStamp = strtotime(date("Y-m-d",$curMonth+($curday-1)*24*60*60+12*60*60));
		
		
		$curDayInfo = getdate($curDayStamp);
		$curdayDisplay="<span class='infoBold'>".$curDayInfo['mday']."</span>";
		if($curDayInfo['mon']!=$curMonthInfo['mon'])
		{
			$class = 'otherMonth';
		}
		if(is_array($bi_pay_info_month[$curDayStamp]))
		{
			$bi_ID = $bi_pay_info_month[$curDayStamp]['bi_ID'];
			$showProfit = $bi_pay_info_month[$curDayStamp]['TotalProfit'];
			if($bi_pay_info_month[$curDayStamp]['ProfitMode']) $showProfit = $bi_pay_info_month[$curDayStamp]['ProfitMode'][$bankInfo['bk_payment_type']];
			$showProfit *=($bk_commission/100);
			$content = "<a href='viewBankInvoice.php?bi_ID=$bi_ID' class='".$bi_pay_info_month[$curDayStamp]['Status']."'>Daily Profit: ".formatMoney($showProfit)."</a><BR>";
			if($bankInfo['bk_payout_support']){
				$content .= "<input name='' type='button' title='".$bi_pay_info_month[$curDayStamp]['bi_download_count']."' value='Payout File (".$bi_pay_info_month[$curDayStamp]['bi_download_count'].")' class='infoSubSection' onClick=\"document.location.href='PayoutGenerate.php?bi_ID=$bi_ID';this.title++;this.value='Payout File ('+this.title+')';\">";
			}
			$class = 'bk4';
			//etelPrint(++$wut2);
		}
		else
		{
			if($lastInvoiceDate<=$curDayStamp && $_REQUEST['calc_payout'] )
			{
				$date_hold = 0;
				$bi_pay_info_item = calcBankReal($curDayStamp,&$calcInfo); 
				$showEst=true;
				
				if($bi_pay_info_item['pay'])
				{
					$estProfit = $bi_pay_info_item['TotalProfit'];
					$rollOverProfit=0;
					$calcInfo['last_date_hold'] = $bi_pay_info_item['date_hold'];
					$calcInfo['last_date_delay'] = $bi_pay_info_item['date_delay'];
					if(date("m",$curMonth)==date("m",$curDayStamp)) $invoiceEstBalance+=$bi_pay_info_item['TotalProfit'];
				}
				else
				{
					$estProfit = $bi_pay_info_item['TotalProfit'];
					if($rollOverProfit == $estProfit) $showEst = false;
					if($estProfit) $rollOverProfit = $estProfit;
			
				}
				
				if($estProfit!=0 && $showEst) 
				{
					$estProfit *=($bk_commission/100);
					$content = "Est Profit: <BR><a href='viewBankInvoice.php?bi_pay_info=".serialize($bi_pay_info_item)."&bank_id=".$bankInfo['bank_id']."&bi_date=$curDayStamp' class='".$bi_pay_info_item['Status']."'>".formatMoney($estProfit)."<BR>".$bi_pay_info_item['Status']."</a><BR>";
					$class = 'bk5';
				}	
			}

			//else $content = formatMoney($bi_pay_info_item['TotalProfit'])."<BR>";
		}
		$border="";
		
		if($curday >= -$curMonthInfo['wday']+1) //Don't display prior week
		{
		
			if(date("Ymd",$curDayStamp)==date("Ymd")) $border = "style='border-width:3; border-bottom-color:#00CCFF '";
			if($curDayInfo['wday']==0) print("<tr class='calendarDay'>\n");
			print("<td class='$class' $border >$curdayDisplay<BR>$content</td>\n");
			if($curDayInfo['wday']==6) print("</tr>\n");
		}
	}
  ?>
  <tr align="center" valign="middle" height='20'>
    <td height="20" colspan="1" class="infoBold">Legend: </td>
    <td height="20" colspan="3" class="infoBold" align="right"><?php if($adminInfo['li_level'] == 'full') {?>Total Month's Profit: $<?=formatMoney(($invoiceTotalBalance+$invoiceEstBalance)*$bk_commission/100)?><?php }?></td>
    <td height="20" colspan="3" class="infoBold" align="right">Invoice: <img style="width:12; height:12" class="bk4" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Estimated Profit: <img style="width:12; height:12" class="bk5" ></td>
  </tr>
                <tr >
                  <td colspan="7"  class="infoBold">
				  <?php if($adminInfo['li_level'] == 'full' || $adminInfo['li_bank'] == '15') {?>All Bank Deductions are taken out of the next available payday. All other calculations are made 10 days back.<? } ?> </td>
                </tr>
</table>
<?php 
	}
	endCacheTable(array('messageHeader' => "Calendar View", 'redir' => '', 'showTable' => true));

	// List Reports
	
	/*
	beginTable();
	?>
	<select name="bi_ID" size="5" id="bi_ID">
	  <?=$invoice_options?>
	</select>
	<BR>
	<input type="hidden" name="bankId" value="<?=$bankId?>">
	<input name="Submit" type="submit" value="View Invoice">
	<?php 
	endTable("Invoice History","viewBankInvoice.php");
	*/

include("includes/footer.php");
?>
