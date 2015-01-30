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
// editCompanyProfile.php:	This admin page functions for editing the company details. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
$iCheckBankId ="";
$iCreditBankId="";
if($sessionAdmin!="")
{
	if ($str_update == "yes") {
		$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
		$strChargeBack = (isset($HTTP_POST_VARS['txtChargeBack'])?quote_smart($HTTP_POST_VARS['txtChargeBack']):"0");
		$strCredit =   (isset($HTTP_POST_VARS['txtCredit'])?quote_smart($HTTP_POST_VARS['txtCredit']):"0");
		$strDiscountRate  = (isset($HTTP_POST_VARS['txtDiscountRate'])?quote_smart($HTTP_POST_VARS['txtDiscountRate']):"0");
		$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"0");
		$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoicefee'])?quote_smart($HTTP_POST_VARS['txtVoicefee']):"0");
		$strReserve  = (isset($HTTP_POST_VARS['txtReserve'])?quote_smart($HTTP_POST_VARS['txtReserve']):"0");
		$strBankShopId  = (isset($HTTP_POST_VARS['txtShopeId'])?quote_smart($HTTP_POST_VARS['txtShopeId']):"");
		$strBankUsername  = (isset($HTTP_POST_VARS['txtBankUsername'])?quote_smart($HTTP_POST_VARS['txtBankUsername']):"");
		$strBankPassword  = (isset($HTTP_POST_VARS['txtBankPassword'])?quote_smart($HTTP_POST_VARS['txtBankPassword']):"");

		$merchant_discount = (isset($HTTP_POST_VARS['merchant_discount'])?quote_smart($HTTP_POST_VARS['merchant_discount']):"0");
		$reseller_discount = (isset($HTTP_POST_VARS['reseller_discount'])?quote_smart($HTTP_POST_VARS['reseller_discount']):"0");
		$total_discount = (isset($HTTP_POST_VARS['total_discount'])?quote_smart($HTTP_POST_VARS['total_discount']):"0");
		$merchant_transfee = (isset($HTTP_POST_VARS['merchant_transfee'])?quote_smart($HTTP_POST_VARS['merchant_transfee']):"0");
		$reseller_transfee = (isset($HTTP_POST_VARS['reseller_transfee'])?quote_smart($HTTP_POST_VARS['reseller_transfee']):"0");
		$total_transfee = (isset($HTTP_POST_VARS['total_transfee'])?quote_smart($HTTP_POST_VARS['total_transfee']):"0");
		$suspend = (isset($HTTP_POST_VARS['suspend'])?quote_smart($HTTP_POST_VARS['suspend']):"NO");
		$strtxtBillingdescriptor = (isset($HTTP_POST_VARS['txtBillingdescriptor'])?quote_smart($HTTP_POST_VARS['txtBillingdescriptor']):"");
		$processingcurrency = (isset($HTTP_POST_VARS['currency'])?quote_smart($HTTP_POST_VARS['currency']):"");
		$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"0");

		$strUnsubscribe  = (isset($HTTP_POST_VARS['chk_unsubscribe'])?quote_smart($HTTP_POST_VARS['chk_unsubscribe']):"1");
		$send_ecommercemail = (isset($HTTP_POST_VARS['chk_sendecommerce'])?quote_smart($HTTP_POST_VARS['chk_sendecommerce']):"0");
		$cancelecommerce_checked = (isset($HTTP_POST_VARS['chk_cancelecommerce'])?quote_smart($HTTP_POST_VARS['chk_cancelecommerce']):"0");
		$iCheckBankId		=	(isset($HTTP_POST_VARS["cboCheckBank"])?quote_smart($HTTP_POST_VARS["cboCheckBank"]):"");
		$iCreditBankId		=	(isset($HTTP_POST_VARS["cboCrditBank"])?quote_smart($HTTP_POST_VARS["cboCrditBank"]):"");
		$atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?quote_smart($HTTP_POST_VARS['atm_verify']):"N");

		if($strVoiceauthFee =="")$strVoiceauthFee =0;
		if($strChargeBack =="")$strChargeBack =0;
		if($strCredit =="")$strCredit =0;
		if($strDiscountRate =="")$strDiscountRate =0;
		if($strTransactionFee =="")$strTransactionFee =0;
		if($strReserve =="")$strReserve =0;
		if($merchant_discount =="")$merchant_discount =0;
		if($reseller_discount =="")$reseller_discount =0;
		if($total_discount =="")$total_discount =0;
		if($reseller_transfee =="")$reseller_transfee =0;
		if($total_transfee =="")$total_transfee =0;
		if($send_ecommercemail =="")$send_ecommercemail =0;
		if($cancelecommerce_checked =="")$cancelecommerce_checked =0;
		if($iCheckBankId =="")$iCheckBankId =0;
		if($iCreditBankId =="")$iCreditBankId =0;

		$qry_update_user  = " update cs_companydetails set chargeback=$strChargeBack,credit=$strCredit,discountrate=$strDiscountRate,transactionfee=$strTransactionFee,reserve=$strReserve,voiceauthfee=$strVoiceauthFee,";
		$qry_update_user .=  "merchant_discount_rate=$merchant_discount, reseller_discount_rate=$reseller_discount, total_discount_rate =$total_discount, merchant_trans_fees=$merchant_transfee , reseller_trans_fees=$reseller_transfee , total_trans_fees=$total_transfee,";
		$qry_update_user .=  "suspenduser='$suspend',billingdescriptor='$strtxtBillingdescriptor',processing_currency='$processingcurrency',activeuser=$trans_activity,send_mail=$strUnsubscribe,send_ecommercemail = $send_ecommercemail,bank_shopId = '$strBankShopId',bank_Username='$strBankUsername',bank_Password='$strBankPassword',bank_Creditcard=$iCreditBankId,bank_check=$iCheckBankId, cancel_ecommerce_letter = $cancelecommerce_checked, atm_verify = '$atm_verify' ";
		$qry_update_user .= "  where userId=$userid";
		if(!mysql_query($qry_update_user))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
	
	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid=$company_id";
	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
?>
<?	
	if($showval = mysql_fetch_row($show_sql)) 
	{
		if($showval[7]=="") 
		{
			$state=str_replace("\n",",\t",$showval[12]);
		} 
		else 
		{
			$state=str_replace("\n",",\t",$showval[7]);
		}
		if($showval[27] == "tele") {
			$script_display ="yes";
			$sendecommerce_diplay = "none";
		}else {
			$script_display ="none";
			$sendecommerce_diplay = "yes";
		}
		if($showval[84] == 1) {
			$sendecommerce_checked = "checked";
		}else {
			$sendecommerce_checked = "";
		}
		if($showval[119] == 1) {
			$cancelecommerce_checked = "checked";
		}else {
			$cancelecommerce_checked = "";
		}
		$str_selected_value = $showval[83];
		$str_currency = $showval[91];
?>
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">
function emailsubmit() {
	//document.Frmcompany.action="viewBottom.php";
	document.Frmcompany.method="POST";
	document.Frmcompany.submit();
}


function addRatesFees(field) {
	if(document.Frmcompany.reseller_discount.value==""){ document.Frmcompany.reseller_discount.value=0;}
	if(document.Frmcompany.total_discount.value==""){ document.Frmcompany.total_discount.value=0;}
	if(document.Frmcompany.reseller_transfee.value==""){ document.Frmcompany.reseller_transfee.value=0;}
	if(document.Frmcompany.total_transfee.value==""){ document.Frmcompany.total_transfee.value=0;}

	if(field=="disc") {
		document.Frmcompany.merchant_discount.value = parseFloat(document.Frmcompany.reseller_discount.value) + parseFloat(document.Frmcompany.total_discount.value)
		return false;		  
	} else {
		document.Frmcompany.merchant_transfee.value = parseFloat(document.Frmcompany.reseller_transfee.value) + parseFloat(document.Frmcompany.total_transfee.value)
		return false;		  
	}
}

function funcOpen3VT(iCompanyId) {
	window.open("vtusers.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenTSR(iCompanyId) {
	window.open("tsruserlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
function funcOpenEcom(iCompanyId) {
	window.open("ecomlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
}
</script>
<table border="0" align="center" cellpadding="0" width="98%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View / Edit&nbsp; 
            Rates And Fees </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="editCompanyProfile3.php"  name="Frmcompany" method="post">
	<table style="margin-top:10" align="center">
	<tr>
	<td>
	<a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
	<?= $script_display == "yes" ? "<a href='editCompanyProfile4.php?company_id=". $company_id ."'><IMG SRC='../images/lettertemplate_tab.gif' WIDTH='128' HEIGHT='32' BORDER='0' ALT=''></a>" : "" ?>
	<a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	</td>
	</tr>
	</table>

		  <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
		  <input type="hidden" name="update" value="yes"></input>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top">
			
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">
			  <tr>
				<td height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
				  & Fees</strong>&nbsp;</font></td>
					
				<td height="30" align="left" class='cl1'>&nbsp;</td>
				</tr>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Charge 
				  Back - <?= $str_currency?></font></strong></td>
				<td height="30" class='cl1'   width="250"></font>
				  &nbsp;<input type="text" name="txtChargeBack" class="normaltext" style="width:100px" value="<?=$showval[18]?>">
				</td>
			  </tr>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Credit - 
				  <?= $str_currency?> </font></strong></td>
				<td height="30" class='cl1'>
				  &nbsp;<input type="text" name="txtCredit" class="normaltext" style="width:100px" value="<?=$showval[19]?>">
				  </td>
			  </tr>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
				  Fee - <?= $str_currency?></font></strong></td>
				<td height="30" class='cl1'>
				  &nbsp;<input type="text" name="txtTransactionFee" class="normaltext" style="width:100px" value="<?=$showval[21]?>">
				</td>
			  </tr>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Discount 
				  Rate - %</font></strong></td>
				<td height="30" class='cl1'>
				  &nbsp;<input type="text" name="txtDiscountRate" class="normaltext" style="width:100px" value="<?=$showval[20]?>">
				</td>
			  </tr>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Reserve 
				  - %</font></strong></td>
				<td height="30" class='cl1'>
				  &nbsp;<input type="text" name="txtReserve" class="normaltext" style="width:100px" value="<?=$showval[22]?>">
				</td>
			  </tr>
			  <?
			  if($showval[27] == "tele") {
			  ?>
			  <tr> 
				<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Voice 
				  Authorization Fee - <?= $str_currency?></font></strong></td>
				<td height="30" class='cl1'>&nbsp;<input type="text" name="txtVoicefee" class="normaltext" style="width:100px" value="<?=$showval[23]?>">
				</td>
			  </tr>	
			 <?
				}
			 ?>
		  <tr> 
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Check Bank&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			&nbsp;<select name="cboCheckBank" style="font-family:arial;font-size:10px;width:120px">
				<option value="">Select Bank</option>
				<?php
					func_fill_combo_conditionally("select bank_id,bank_name from cs_bank",$showval[118],$cnn_cs);
				?>
			</select>
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Credit Card Bank&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			&nbsp;<select name="cboCrditBank" style="font-family:arial;font-size:10px;width:120px">
				<option value="">Select Bank</option>
				<?php
					func_fill_combo_conditionally("select bank_id,bank_name from cs_bank",$showval[117],$cnn_cs);
				?>
			</select>
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Bank Shop Id&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			&nbsp;<input type="text" name="txtShopeId" style="font-family:arial;font-size:10px;width:130px" value="<?= $showval[114] ?>">
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Bank Username&nbsp;</font></strong><font face="verdana" size="1">(For Volpay)</font></td>
			<td align="left" height="30" class='cl1' valign="middle">
			&nbsp;<input type="text" name="txtBankUsername" style="font-family:arial;font-size:10px;width:130px" value="<?= $showval[115] ?>">
			</td>
		  </tr>
		  <tr> 
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Bank Password&nbsp;</font></strong><font face="verdana" size="1">(For Volpay)</font></td>
			<td align="left" height="30" class='cl1'>
			&nbsp;<input type="text" name="txtBankPassword" style="font-family:arial;font-size:10px;width:130px" value="<?= $showval[116] ?>">
			</td>
		  </tr>
		  <?php if($str_selected_value!="") { ?>				
				<tr> 
				  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Reseller Rates & Fees 
					Informations</strong></font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> discount rate - %</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="total_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[87] ?>" onChange="addRatesFees('disc');"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller discount rate - %</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="reseller_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[86] ?>" onChange="addRatesFees('disc');"></td>
				</tr>
				<tr> 
				        <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total 
                          merchant discount rate - %</strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="merchant_discount" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[85] ?>" onChange="addRatesFees('disc');" readonly></td>
				</tr>
				<tr>
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total <?=$_SESSION['gw_title']?> transaction fee - <?= $str_currency?></strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="total_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[90] ?>" onChange="addRatesFees('trans');"></td>
				</tr>
				<tr> 
				 <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total reseller transaction fee - <?= $str_currency?></strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="reseller_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[89] ?>" onChange="addRatesFees('trans');"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" class="cl1" ><font face="verdana" size="1">&nbsp;<strong>Total merchant transaction fee - <?= $str_currency?></strong>&nbsp;</font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text" maxlength="15" name="merchant_transfee" style="font-family:arial;font-size:10px;width:100px" value="<?= $showval[88] ?>" onChange="addRatesFees('trans');" readonly></td>
				</tr>
			<?php 		} 	?>		
			</table>
		</td>
		<td align="center" width="50%" valign="top">
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="top">
			<tr> 
			<td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>ATM Verification </strong>&nbsp;</font></td>
			<td height="30" class='cl1' align="left">&nbsp;</td>
		  </tr>
		  <tr> 
			<td height="30" align="left"  class='cl1' ><font face="verdana" size="1">	
			  <strong>&nbsp;ATM Verify?</strong></font></td>
			<td height="30" align="left"  class='cl1' >
			  &nbsp;<input name="atm_verify" type="checkbox" value="Yes" <?=$showval[113] == "Y" ? "checked" : ""?>>
			 </td>
			</tr>
			<tr> 
               <td width="233" height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Users</strong>&nbsp;</font></td>
               <td height="30" class='cl1' align="left">&nbsp;</td>
			</tr>					  
			<tr height='30'>
               <td height="30" align='left' class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;3 VT Users</b></font></td>
               <td height="30" align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<a href="javascript:funcOpen3VT(<?=$showval[0]?>)">Show Users</a></font></td>
            </tr>
			<?php
				$sTransactionType = funcGetValueByQuery("select transaction_type  from cs_companydetails where  userId  = $showval[0]",$cnn_cs);
				if ($sTransactionType == "tele") {
			?>
					<tr height='30'>
					   <td height="30" align='left' class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;Call Center user and TSR</b></font></td>
					   <td height="30" align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<a href="javascript:funcOpenTSR(<?=$showval[0]?>)">Show Users</a></font>
					   </td>
					</tr>
			<?php
				}
				if ($sTransactionType != "tele") {
			?>
				<tr height='30'>
					   <td height="30" align='left' class='cl1' width="50%"><font face='verdana' size='1'><b>&nbsp;Web sites</b></font></td>
					   <td height="30" align='left'  width="50%" class='cl1'><font face='verdana' size='1'>&nbsp;<a href="javascript:funcOpenEcom(<?=$showval[0]?>)">Show Users</a></font>
					   </td>
					</tr>
			<? } ?>		
					<tr>
					<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Suspend 
					  User?</b></font></td>
					<td height="30" class='cl1' align='left'><input type="checkbox" name="suspend" class="normaltext" <?=$showval[11] == "YES" ? "checked" : ""?> value="YES">
					</td>
				  </tr>
				  <tr>
					<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant Active</font></strong></td>
					<td height="30" class='cl1'>
					  <input name="rad_trans_activity" type="checkbox" value="1" <?=$showval[28] == 1 ? "checked" : ""?>>
					</td>
				  </tr>
					<tr> 
					<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Billing 
					  Descriptor Name</font></strong></td>
					<td height="30" class='cl1'>&nbsp;<input type="text" name="txtBillingdescriptor" class="normaltext" style="width:150px" value="<?=$showval[48]?>">
					</td>
				  </tr>
					<tr> 
					<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Processing Currency 
					  </font></strong></td>
					<td height="30" class='cl1'>&nbsp;<select name="currency" style="font-family:verdana;font-size:10px;width:125px">
					  <option value="EUR">Euro</option>
					  <option value="GBP">UK Pound</option>
					  <option value="USD">US Dollar</option>
					  <option value="CAD">Canadian Dollar</option>
					  <option value="AUD">Australian Dollar</option>
					</select> 
					</td>
					<script language="javascript">
						 document.Frmcompany.currency.value='<?=$showval[91]?>';
					</script>    
				  </tr>
				  <tr>
					<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Unsubscribe from mailing list?</b></font></td>
					<td height="30" align='left' class='cl1'><input type="checkbox" name="chk_unsubscribe" class="normaltext" <?=$showval[76] == 0 ? "checked" : ""?> value="0">
					</td>
				  </tr>
				  <tr>
					<td colspan="2"> 
					<div id="auto_sendecommerce" style="display:<?=$sendecommerce_diplay?>"> 
					<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
					 <tr> 
						<td height="30" class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;Send 
						  Ecommerce Letter?</b></font></td>
						<td height="30" class='cl1'> 
						  <input type="checkbox" name="chk_sendecommerce" class="normaltext" value="1" <?=$sendecommerce_checked?>></td>
					  </tr>	
					  </table></div>
				  </td>
				  </tr>
				  <tr>
					<td colspan="2"> 
					<div id="auto_sendecommerce" style="display:<?=$sendecommerce_diplay?>"> 
					<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
					 <tr> 
						<td height="30" class='cl1' width="54%"><font face='verdana' size='1'><b>&nbsp;Send Cancel 
						  Ecommerce Letter?</b></font></td>
						<td height="30" class='cl1'> 
						  <input type="checkbox" name="chk_cancelecommerce" class="normaltext" value="1" <?=$cancelecommerce_checked?>></td>
					  </tr>	
					  </table></div>
				  </td>
				  </tr>		  <?php /*
		  		$qrySelect 		=	"select * from cs_invoice_setup where company_id = $company_id";
				$rstSelect		=	mysql_query($qrySelect,$cnn_cs);
				$iFreequency 	= "";
				$iNumDaysBack	= "";
				$iFromWeekDay 	= "";
				$iToWeekDay 	= "";
				$iMiscFee		= "";	
				
				if (mysql_num_rows($rstSelect) > 0 ) {
					$iFreequency 	= mysql_result($rstSelect,0,2);
					$iNumDaysBack	= mysql_result($rstSelect,0,3);
					$iFromWeekDay 	= mysql_result($rstSelect,0,4);
					$iToWeekDay 	= mysql_result($rstSelect,0,5);
					$iMiscFee		= mysql_result($rstSelect,0,6);
				}*/
		  
		  ?>
		<!--<tr> 
			<td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC" width="42%"><font face="verdana" size="1" color="#FFFFFF"><strong>Invoice 
			  Details</strong>&nbsp;</font></td>
			<td width="58%" height="30" align="left" class='cl1'>&nbsp;</td>
		</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Frequency</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboFreequency" style="font-family:verdana;font-size:10px;width:100px">
			<?php 
				funcFillFreequency($iFreequency);
			?> 
			</select>
			
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Number of days back</b></font></td>
			<td height="30" align='left' class='cl1'>
			<input type="text" name="txtNumberOfDays" value="<?= $iNumDaysBack ?>" maxlength="5" style="font-family:verdana;font-size:10px;width:75px">
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;From week day</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboFromWeekDay" style="font-family:verdana;font-size:10px;width:150px">
			<?php
				
				funcFillWeekDays($iFromWeekDay);
			?>	
			</select>
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;To week day</b></font></td>
			<td height="30" align='left' class='cl1'>
			<select name="cboToWeekDay" style="font-family:verdana;font-size:10px;width:150px">
			<?php
			
				funcFillWeekDays($iToWeekDay);
			?>	
			<script>
				document.Frmcompany.cboToWeekDay.value = "7";
			</script>
			</select>
			
			</td>
    	</tr>
		<tr>
			<td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;Misc Fee</b></font></td>
			<td height="30" align='left' class='cl1'>
			<input type="text" name="txtMiscFee" value="<?= $iMiscFee ?>" maxlength="10" style="font-family:verdana;font-size:10px;width:75px">
			</td>
    	</tr>-->
		</table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>	
		</table>
		</center>

<?php 
		}
?>
        </form>
		</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table><br>
    </td>
    </tr>
</table>
<?php
include("includes/footer.php");
}
//-------------	Function for filling freequency --------------
//------------------------------------------------------------
function funcFillFreequency($iFreequency) {
	$arrFre[1] = "Daily";
	$arrVal[1] = "D";
	$arrFre[2] = "Weekly";
	$arrVal[2] = "W";
	$arrFre[3] = "Monthly";
	$arrVal[3] = "M";
	for ( $iLoop = 1 ;$iLoop < 4 ;$iLoop++ ) {
		if ( $iLoop == $iFreequency ) {
			echo("<option value=\"$arrVal[$iLoop]\" selected>$arrFre[$iLoop]</option>");
		} else {
			echo("<option value=\"$arrVal[$iLoop]\">$arrFre[$iLoop]</option>");
		}
	}
}

//------------- Function for filling week days ----------------
//-------------------------------------------------------------

function funcFillWeekDays($iWeekDay) {
	$arrWeekDays[1] = "Monday";
	$arrWeekDays[2] = "Tuesday";
	$arrWeekDays[3] = "Wednesday";
	$arrWeekDays[4] = "Thursday";
	$arrWeekDays[5] = "Friday";
	$arrWeekDays[6] = "Saturday";
	$arrWeekDays[7] = "Sunday";
	
	for ($iLoop = 1;$iLoop < 8;$iLoop++ ) {
		if ( $iLoop == $iWeekDay ) {
			echo("<option value=\"$iLoop\" selected>$arrWeekDays[$iLoop]</option> ");
		} else {
			echo("<option value=\"$iLoop\">$arrWeekDays[$iLoop]</option> ");
		}
	}
	
}


?>