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
$display_stat_wait = true;
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_adminapproval="";
$reseller_id =isset($_REQUEST["reseller_id"])?$_REQUEST["reseller_id"]:"";
//print_r($_REQUEST);
$show_rollover = $_REQUEST['show_rollover'];
$show_active = $_REQUEST['show_active'];
require_once ("../includes/projSetCalc.php");
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
	$sql = "SELECT count(`transactionId`) as key1 FROM `cs_transactiondetails` as td, `cs_companydetails` as cd where td.userId = cd.userId AND reseller_id > 0";
	$result=mysql_query($sql) or dieLog(mysql_error());
	$key=mysql_result($result,0,0);
	if(beginCacheTable("BI_".$key."_".getRequestHash(),time()+60*60))
	{
?>
	
	<form action="" method="GET" name="FrmMerchant" >
  <table border="0"  align="center" cellpadding="2">
    <tr valign="middle" >     
	 <td width="102" align="right"  valign="middle"><select name="show_rollover" id="show_rollover" >
       <option value="a" <?=($show_rollover=='a'?"selected":"")?>>Show All </option>
       <option value="p" <?=($show_rollover=='p'?"selected":"")?>>Show Payable Companies</option>
       <option value="s" <?=($show_rollover=='s'?"selected":"")?>>Show Rollover Companies</option>
          </select></td>
      <td width="127"><input type="image" name="add" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td>
    </tr>
	<tr>
	  <td colspan="4">			  <select name="show_active" id="show_active" >
	    <option value="a" <?=($show_active=='a'?"selected":"")?>>Show Active Companies</option>
	    <option value="s" <?=($show_active=='s'?"selected":"")?>>Show All </option>
	    <option value="i" <?=($show_active=='i'?"selected":"")?>>Show Inactive Companies</option>
              </select>
</td></tr>
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
		  
		  
		  <table width="100%" border="0" class="invoice">
            <tr bgcolor="#CCCCCC">
              <td class="infoHeader" bgcolor="#CCCCCC">Reseller</td>
              <td class="infoHeader">Payday</td>
              <td class="infoHeader">Balance</td>
              <td class="infoHeader">Status</td>
              <td class="infoHeader">Action</td>
            </tr>
      <?php  
	
if($_POST['Action']=='Push Back')
{
	$qry_company="select * from cs_resellerdetails where reseller_id = '".$reseller_id."'";
	$company_details=mysql_query($qry_company,$cnn_cs) or dieLog(mysql_error()." ~ $sql");
	$companyInfo=mysql_fetch_assoc($company_details);
	$pushedBackTime = resellerPushBackOnePeriod();
	$pushedBack = date('Y-m-',$pushedBackTime).$companyInfo['rd_paydelay'];
	$qry_company="Update cs_resellerdetails set `rd_next_pay_day` = '$pushedBack' where reseller_id = '".$reseller_id."'";
	mysql_query($qry_company) or dieLog(mysql_error()." ~ $sql");
}

$companyInfo = NULL;

$activity="";
if($show_active == 'a') $active = "AND completed_reseller_application=1 ";
if($show_active == 'i') $active = "AND completed_reseller_application=0 ";
$display_none = "1";
//if($no_display==true) $display_none = "0";
$qry_company="select * from cs_resellerdetails where $display_none $active"; // and rd_next_pay_day<=CURDATE()
$gatewayid=-1;
$company_details=mysql_query($qry_company,$cnn_cs) or dieLog(mysql_error()." ~ $sql");
while($companyInfo=mysql_fetch_assoc($company_details))
{
	$sql = 0;
	if(resellerPastPayPeriod())
	{
		$pushedBack = resellerPushBackOnePeriod();
		$sql="Update cs_resellerdetails set `rd_next_pay_day` = '$pushedBack' where reseller_id = '".$companyInfo['reseller_id']."'";

		$companyInfo['rd_next_pay_day']=$pushedBack;
	}
	if($sql) mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
$date_hold=0;
$ri_pay_info = calcResellerReal();
if($show_rollover=='s' && $ri_pay_info['Status']!='Rollover') continue;
if($show_rollover=='p' && $ri_pay_info['Status']!='Payable') continue;
	?>
			<form name="action" method="post">
			<input type="hidden" name="reseller_id" value='<?=$companyInfo['reseller_id']?>'>
            <tr>
              <td class="info"><a href='modifyReseller.php?reseller_id=<?=$companyInfo['reseller_id']?>'><?=substr($companyInfo['reseller_companyname'],0,25)?></a></td>
              <td class="info"><?=$companyInfo['rd_next_pay_day']=="0000-00-00"?"Not Paid Yet":date("l, F j, Y",strtotime($companyInfo['rd_next_pay_day']))?></td>
              <td class="info">
			  

                <?=($ri_pay_info['Balance']==0?"- None -":"<a href='payReseller.php?reseller_id=".$companyInfo['reseller_id']."' >".formatMoney($ri_pay_info['Balance'])."</a>")?>
</a>



			  </td>
              <td class="<?=$ri_pay_info['Status']?>">
                <?=$ri_pay_info['Status']?>
              </td>
            </tr><?php 
			
			$sql = "SELECT * FROM `cs_reseller_invoice` WHERE `ri_reseller_id` = '".$companyInfo['reseller_id']."' ORDER BY `ri_date` DESC limit 1";
			$result = mysql_query($sql) or dieLog(mysql_error() ." ~ $sql");
			
			if(mysql_num_rows($result)>0){
				$last_invoice = mysql_fetch_assoc($result);
				$last_ri_pay_info = @unserialize($last_invoice['ri_pay_info']);
			
			?>
			<tr>
			<td class="infoSubSection">
			Last Paid:
			</td>
			<td class="infoSubSection">
			<?=date("l, F j",strtotime($last_invoice['ri_date']))?>
			</td>
              <td class="infoSubSection">
			  

                <?=($last_ri_pay_info['Balance']==0?"- None -":"<a href='viewResellerInvoice.php?ri_ID=".$last_invoice['ri_ID']."' >".formatMoney($last_ri_pay_info['Balance'])."</a>")?>
</a>



			  </td>
              <td class='<?=$last_invoice['ri_status']?>'><?=ucfirst($last_invoice['ri_status'])?>
              </td>
			</tr>
			<?php } ?>
			</form>

  <?php
	}

?>

          </table></td>
        </tr>
     
    </table></td>
<?php

	}
	endCacheTable(array('messageHeader' => "Reseller Payment", 'redir' => '', 'showTable' => true));

// List Reports

ob_start();
?>
<select name="ri_ID" size="5" id="ri_ID" class="invoice">
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally("select ri_ID, ri_title, ri_status as class from `cs_reseller_invoice` ORDER BY `ri_status`='WireSuccess' ASC,`ri_date` DESC ",$_POST['rp_ID'],$cnn_cs); ?>
</select>
<BR>
<input name="Submit" type="submit" value="View Invoice">
<?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","viewResellerInvoice.php");


include("includes/footer.php");
?>