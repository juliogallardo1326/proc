<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewcompanyNext.php:	This admin page functions for displaying the company details. 

$headerInclude="startHere";
include("includes/header.php");

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$userId = isset($_POST['userId'])?$_POST['userId']:"";

if($_POST['update'])
{

	$request['request_cc_reseller_discount_rate'] = floatval(isset($_POST['cc_reseller_discount_rate'])?$_POST['cc_reseller_discount_rate']:0);
	//$request['request_ch_reseller_discount_rate'] = isset($_POST['ch_reseller_discount_rate'])?$_POST['ch_reseller_discount_rate']:"";
	//$request['request_web_reseller_trans_fees'] = isset($_POST['web_reseller_trans_fees'])?$_POST['web_reseller_trans_fees']:"";
	$request['request_cc_reseller_trans_fees'] = floatval(isset($_POST['cc_reseller_trans_fees'])?$_POST['cc_reseller_trans_fees']:0);
	//$request['request_ch_reseller_trans_fees'] = isset($_POST['ch_reseller_trans_fees'])?$_POST['ch_reseller_trans_fees']:"";
	
	$requestSer=serialize($request);
	$sql ="update cs_companydetails as cd set cd.cd_reseller_rates_request = '$requestSer' where cd.reseller_id='".$resellerInfo['reseller_id']."' and cd.userId='$userId'";
	$result=mysql_query($sql) or dieLog($sql);
	$msg = "Rates Requested Successfully";
	toLog('resellerrequestrates','merchant', '', $userId);
	$userId = NULL;
}


 beginTable();
?>
<script language="javascript">
function addRatesFees() {
	document.getElementById('cc_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('cc_total_trans_fees').value)+parseFloat(document.getElementById('cc_reseller_trans_fees').value)))*.01;
	//document.getElementById('ch_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('ch_total_trans_fees').value)+parseFloat(document.getElementById('ch_reseller_trans_fees').value)))*.01;
	//document.getElementById('web_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('web_total_trans_fees').value)+parseFloat(document.getElementById('web_reseller_trans_fees').value)))*.01;
	
	document.getElementById('cc_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('cc_total_discount_rate').value)+parseFloat(document.getElementById('cc_reseller_discount_rate').value)))*.01;
	//document.getElementById('ch_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('ch_total_discount_rate').value)+parseFloat(document.getElementById('ch_reseller_discount_rate').value)))*.01;
}
</script>
<table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" height="50" align="center" valign="middle"><?=$msg?></td>
    </tr>
    <tr>
      <td align="right" height="50" valign="middle"><font face="verdana" size="1">Select Merchant</font>&nbsp;</td>
      <td>&nbsp;
        <select name="userId" id="userId">
          <?=func_fill_combo_conditionally("select userId, companyname  from cs_companydetails as cd where cd.reseller_id='".$resellerInfo['reseller_id']."' and cd.cd_reseller_rates_request=1",$cnn_cs); ?>
        </select>
		<script>
			document.getElementById('userId').value = <?=$userId?>;
		</script>
      </td>
    </tr>
    <tr>
      <td colspan="2" height="50" align="center" valign="middle"><input	type="image" src="../images/submit.jpg"></td>
    </tr>
</table>
<?php endTable("Select Company","") ;

if($userId)
{
	$sql ="select * from cs_companydetails as cd where cd.reseller_id='".$resellerInfo['reseller_id']."' and cd.userId='$userId'";
	$result=mysql_query($sql) or dieLog($sql);
	$companyInfo=mysql_fetch_assoc($result);
	beginTable(); 
?>
<table width="100%"  border="1" cellspacing="2" cellpadding="2">
  <tr>
    <th scope="col"><font size="-1">Rates for 
      <?=$companyInfo['companyname']?>
    </font></th>
    <th scope="col"><font size="-1">
      <?=$_SESSION['gw_title']?>
    </font></th>
    <th scope="col"><font size="-1">Your Reseller Markup Rate </font></th>
    <th scope="col"><font size="-1">Final Merchant Rate </font></th>
  </tr>
  <tr>
    <td><span class="cl1"><font face="verdana" size="1"><strong>Discount rate (Percent)</strong></font></span></td>
    <td><span class="cl1">
      <input name="cc_total_discount_rate" type="text" disabled id="cc_total_discount_rate" style="border:0; color:#000000; background-color:#FFFFFF; text-align:right;" value="<?= formatMoney($companyInfo['cc_total_discount_rate']) ?>" size="3" maxlength="5"> 
      %
    </span></td>
    <td align="left" valign="center" class="cl1"><input type="text" maxlength="5" name="cc_reseller_discount_rate" id="cc_reseller_discount_rate" value="<?= formatMoney($companyInfo['cc_reseller_discount_rate']) ?>" onChange="addRatesFees();" ></td>
    <td><span class="cl1">
<input name="cc_merchant_discount_rate" type="text" disabled id="cc_merchant_discount_rate" style="border:0; color:#000000; background-color:#FFFFFF; text-align:right;" value="<?=formatMoney($companyInfo['cc_merchant_discount_rate']) ?>" size="3">      
%
    </span></td>
  </tr>
  <tr>
    <td><span class="cl1"><font face="verdana" size="1"><strong>T<font face="verdana" size="1"><strong>ransaction fee <font face="verdana" size="1"><strong>(USD)</strong></font></strong></font></strong></font></span></td>
    <td><span class="cl1">
      <input name="cc_total_trans_fees" type="text" disabled id="cc_total_trans_fees" style="border:0; color:#000000; background-color:#FFFFFF; text-align:right;" value="<?= formatMoney($companyInfo['cc_total_trans_fees']) ?>" size="3" maxlength="5">
    </span></td>
    <td align="left" valign="center" class="cl1"><input type="text" maxlength="5" name="cc_reseller_trans_fees" id="cc_reseller_trans_fees" value="<?= formatMoney($companyInfo['cc_reseller_trans_fees']) ?>" onChange="addRatesFees();" ></td>
    <td><span class="cl1">
      <input name="cc_merchant_trans_fees" type="text" disabled id="cc_merchant_trans_fees" style="border:0; color:#000000; background-color:#FFFFFF; text-align:right;" value="<?=formatMoney($companyInfo['cc_merchant_trans_fees'])?>" size="3">
    </span></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle" colspan="4"><p>
      <input	type="image" src="../images/submit.jpg">
    </p>
    <p><font size="-1">*Please Update the Merchant's rates with this form. <?=$_SESSION["gw_title"]?>'s rate + Your Marked up rate = Merchant's Final Rate. </font></p></td>
  </tr>
</table>

    <input type="hidden" name="userId" value="<?=$userId?>">
  <input type="hidden" name="update" value="1">
<?php 
endTable("Mark Up Company Rates and Fees","");


}

include 'includes/footer.php';
?>
