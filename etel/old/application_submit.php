<?php 
//******************************************************************//
//  This file is part of the  Zerone development package.        //
//  Copyleft (C) Etelegate.com 2003-2004, All lefts Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// companyEdit.php:	The  page used to modify the company profile. 
include 'includes/sessioncheck.php';
$headerInclude="startHere";
require_once("includes/header.php");
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";


if($sessionlogin!="" && isset($HTTP_POST_VARS['volume'])) {
	$volume= (isset($HTTP_POST_VARS['volume'])?quote_smart($HTTP_POST_VARS['volume']):"");
	$avgticket= (isset($HTTP_POST_VARS['avgticket'])?quote_smart($HTTP_POST_VARS['avgticket']):"");
	$chargeper= (isset($HTTP_POST_VARS['chargeper'])?quote_smart($HTTP_POST_VARS['chargeper']):"");
	$rad_order_type= (isset($HTTP_POST_VARS['rad_order_type'])?quote_smart($HTTP_POST_VARS['rad_order_type']):"");
	$prepro= (isset($HTTP_POST_VARS['prepro'])?quote_smart($HTTP_POST_VARS['prepro']):"");
	$rebill= (isset($HTTP_POST_VARS['rebill'])?quote_smart($HTTP_POST_VARS['rebill']):"");
	$currpro= (isset($HTTP_POST_VARS['currpro'])?quote_smart($HTTP_POST_VARS['currpro']):"");
	$billingdesc = (isset($HTTP_POST_VARS['billingdesc'])?quote_smart($HTTP_POST_VARS['billingdesc']):"");
	//$processingcurrency = (isset($HTTP_POST_VARS['currency'])?quote_smart($HTTP_POST_VARS['currency']):"");
	$mastercurrency= (isset($HTTP_POST_VARS['currencymaster'])?quote_smart($HTTP_POST_VARS['currencymaster']):"");
	 $visacurrency = (isset($HTTP_POST_VARS['currencyvisa'])?quote_smart($HTTP_POST_VARS['currencyvisa']):"");
	 $cd_processing_reason = (isset($HTTP_POST_VARS['cd_processing_reason'])?quote_smart($HTTP_POST_VARS['cd_processing_reason']):"");
	 $cd_previous_processor = (isset($HTTP_POST_VARS['cd_previous_processor'])?quote_smart($HTTP_POST_VARS['cd_previous_processor']):"");
	 $cd_previous_discount = (isset($HTTP_POST_VARS['cd_previous_discount'])?quote_smart($HTTP_POST_VARS['cd_previous_discount']):"");
	 $cd_previous_transaction_fee = (isset($HTTP_POST_VARS['cd_previous_transaction_fee'])?quote_smart($HTTP_POST_VARS['cd_previous_transaction_fee']):"");

	if($volume=="") 
		$volume=0;
	if($avgticket=="")
		$avgticket=0;
	if($chargeper=="")
		$chargeper=0;
		
	if($companyInfo['cd_completion']<=1 && $companyInfo['gateway_id']==5)
	{
		$completion=" ,cd_completion=4 ";
		$msgtodisplay.="<BR><strong>Please Read your new Etelegate.com Contract. Once finished, please print out, sign, scan, and upload the contract.</strong>";
		toLog('completedapplication','merchant', '', $sessionlogin);
		
	}
	else if($companyInfo['cd_completion']<=1)
	{
		$completion=" ,cd_completion=2 ";
		$msgtodisplay.="<BR><strong>You may now request your rates and fees. Please proceed to the next section.</strong>";
		toLog('completedapplication','merchant', '', $sessionlogin);
		
	}
			
		$str_update_query  = "update cs_companydetails set cd_previous_transaction_fee='$cd_previous_transaction_fee', cd_previous_discount='$cd_previous_discount', cd_processing_reason='$cd_processing_reason',cd_previous_processor='$cd_previous_processor',volumenumber = '$volume', avgticket = '$avgticket', chargebackper = '$chargeper', ";
		$str_update_query .= "transaction_type = '$rad_order_type', preprocess = '$prepro', recurbilling = '$rebill', currprocessing = '$currpro', billingdescriptor='$billingdesc' $completion  ";
		$str_update_query .= "where userid=$sessionlogin";
		if(isset($HTTP_POST_VARS['volume'])) mysql_query($str_update_query,$cnn_cs) or dieLog(mysql_error());
	


}

if($sessionlogin!="" && isset($HTTP_POST_VARS['username'])){
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$currentBank = (isset($HTTP_POST_VARS['currentBank'])?quote_smart($HTTP_POST_VARS['currentBank']):"");
	$bank_other = (isset($HTTP_POST_VARS['bank_other'])?quote_smart($HTTP_POST_VARS['bank_other']):"");
	$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?quote_smart($HTTP_POST_VARS['beneficiary_name']):"");
	$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?quote_smart($HTTP_POST_VARS['bank_account_name']):"");
	$bank_address = (isset($HTTP_POST_VARS['bank_address'])?quote_smart($HTTP_POST_VARS['bank_address']):"");
	$bank_country = (isset($HTTP_POST_VARS['bank_country'])?quote_smart($HTTP_POST_VARS['bank_country']):"");
	$bank_city = (isset($HTTP_POST_VARS['bank_city'])?quote_smart($HTTP_POST_VARS['bank_city']):"");
	$bank_zipcode = (isset($HTTP_POST_VARS['bank_zipcode'])?quote_smart($HTTP_POST_VARS['bank_zipcode']):"");
	$bank_phone = (isset($HTTP_POST_VARS['bank_phone'])?quote_smart($HTTP_POST_VARS['bank_phone']):"");
	$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?quote_smart($HTTP_POST_VARS['bank_sort_code']):"");
	$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?quote_smart($HTTP_POST_VARS['bank_account_number']):"");
	$bank_swift_code = (isset($HTTP_POST_VARS['bank_swift_code'])?quote_smart($HTTP_POST_VARS['bank_swift_code']):"");
	$BIC_code = (isset($HTTP_POST_VARS['biccode'])?quote_smart($HTTP_POST_VARS['biccode']):"");
	$vatmumber = (isset($HTTP_POST_VARS['vatnum'])?quote_smart($HTTP_POST_VARS['vatnum']):"");
	$regnumber = (isset($HTTP_POST_VARS['regnum'])?quote_smart($HTTP_POST_VARS['regnum']):"");
	$cd_bank_instructions = (isset($HTTP_POST_VARS['cd_bank_instructions'])?quote_smart($HTTP_POST_VARS['cd_bank_instructions']):"");
	$cd_bank_routingnumber = (isset($HTTP_POST_VARS['cd_bank_routingnumber'])?quote_smart($HTTP_POST_VARS['cd_bank_routingnumber']):"");
	$cd_bank_routingcode = (isset($HTTP_POST_VARS['cd_bank_routingcode'])?quote_smart($HTTP_POST_VARS['cd_bank_routingcode']):"");
	$bank_IBRoutingCode = (isset($HTTP_POST_VARS['bank_IBRoutingCode'])?quote_smart($HTTP_POST_VARS['bank_IBRoutingCode']):"");
	$bank_IBRoutingCodeType = (isset($HTTP_POST_VARS['bank_IBRoutingCodeType'])?quote_smart($HTTP_POST_VARS['bank_IBRoutingCodeType']):"");
	$bank_IBName = (isset($HTTP_POST_VARS['bank_IBName'])?quote_smart($HTTP_POST_VARS['bank_IBName']):"");
	$bank_IBCity = (isset($HTTP_POST_VARS['bank_IBCity'])?quote_smart($HTTP_POST_VARS['bank_IBCity']):"");
	$bank_IBState = (isset($HTTP_POST_VARS['bank_IBState'])?quote_smart($HTTP_POST_VARS['bank_IBState']):"");

	if($currentBank=='other') $currentBank=$bank_other;
	
	$msgtodisplay="Merchant Details for '".$companyInfo['username']."' has been modified.";
	

	
	if(isset($HTTP_POST_VARS['bank_account_number']))
	{
		if($bank_other) $currentBank = $bank_other;
		$sql_update_qry = "update cs_companydetails set bank_IBRoutingCodeType='$bank_IBRoutingCodeType', bank_IBName='$bank_IBName', bank_IBCity='$bank_IBCity', bank_IBState='$bank_IBState', bank_zipcode='$bank_zipcode', bank_city='$bank_city', cd_bank_instructions = '$cd_bank_instructions', cd_bank_routingnumber = '$cd_bank_routingnumber', cd_bank_routingcode = '$cd_bank_routingcode', company_bank = '$currentBank', other_company_bank = '$bank_other', bank_address = '$bank_address', bank_country = '$bank_country', bank_phone = '$bank_phone', bank_sort_code = '$bank_sort_code', bank_account_number = '$bank_account_number', bank_swift_code = '$bank_swift_code',beneficiary_name='$beneficiary_name',bank_account_name='$bank_account_name',BICcode='$BIC_code',VATnumber='$vatmumber',registrationNo='$regnumber',bank_IBRoutingCode='$bank_IBRoutingCode', completed_merchant_application = 1 $completion where userid=$sessionlogin";
		if(!($run_update_qry =mysql_query($sql_update_qry,$cnn_cs))) {
			echo mysql_errno().": ".mysql_error()."<BR>";
			echo "Cannot execute query.";
			exit();
		}
	}
?>
<?php beginTable() ?>
<table border="0" cellpadding="0" width="100%" cellspacing="0"  >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center">
		  <table width="500" border="0" cellpadding="0"  >
			<tr>
			<td align="center" valign="center" height="30" width="50%"  bgcolor="#F8FAFC"><font face="verdana" size="1">
			<?= $msgtodisplay ?>
			</td>
			</tr>
		  <tr>
	  	<td align="center" valign="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> 
		&nbsp;&nbsp;<input name="image" type="image" id="modifycompany" src="images/continue.gif">
		<br>
		</td>
	  	</tr>
		</table>
	  </td>
	</tr>
  	</table></td>
     </tr>
</table><br>
<?php 
$redir = "merchantContract.php";
if($_REQUEST['showheader']=='profile') $redir = "application_bpi.php?showheader=profile";
endTable("Merchant Application Complete!",$redir) ?>

<?
include 'includes/footer.php';
}
?>