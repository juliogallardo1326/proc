<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// editCompanyProfile2.php:	This admin page functions for editing the company details.
$allowBank=true;

include("includes/sessioncheck.php");
include("../includes/completion.php");

$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";

if($sessionAdmin!="")
{
	if ($str_update == "yes") {
		$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");

		$currentBank	 = (isset($HTTP_POST_VARS['currentBank'])?quote_smart($HTTP_POST_VARS['currentBank']):"");
		$bank_other		 = (isset($HTTP_POST_VARS['bank_other'])?quote_smart($HTTP_POST_VARS['bank_other']):"");
		$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?quote_smart($HTTP_POST_VARS['beneficiary_name']):"");
		$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?quote_smart($HTTP_POST_VARS['bank_account_name']):"");
		$bank_address	 = (isset($HTTP_POST_VARS['bank_address'])?quote_smart($HTTP_POST_VARS['bank_address']):"");
		$bank_zipcode	 = (isset($HTTP_POST_VARS['bank_zipcode'])?quote_smart($HTTP_POST_VARS['bank_zipcode']):"");
		$bank_city	 = (isset($HTTP_POST_VARS['bank_city'])?quote_smart($HTTP_POST_VARS['bank_city']):"");
		$bank_country	 = (isset($HTTP_POST_VARS['bank_country'])?quote_smart($HTTP_POST_VARS['bank_country']):"");
		$bank_phone		 = (isset($HTTP_POST_VARS['bank_phone'])?quote_smart($HTTP_POST_VARS['bank_phone']):"");
		$bank_sort_code  = (isset($HTTP_POST_VARS['bank_sort_code'])?quote_smart($HTTP_POST_VARS['bank_sort_code']):"");
		$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?quote_smart($HTTP_POST_VARS['bank_account_number']):"");
		$cd_bank_routingcode	 = (isset($HTTP_POST_VARS['cd_bank_routingcode'])?quote_smart($HTTP_POST_VARS['cd_bank_routingcode']):"");
		$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?quote_smart($HTTP_POST_VARS['bank_sort_code']):"");
		$cd_bank_routingnumber = (isset($HTTP_POST_VARS['cd_bank_routingnumber'])?quote_smart($HTTP_POST_VARS['cd_bank_routingnumber']):"");
		$registrationNo = (isset($HTTP_POST_VARS['regnum'])?quote_smart($HTTP_POST_VARS['regnum']):"");
		$bic_code = (isset($HTTP_POST_VARS['bic_code'])?quote_smart($HTTP_POST_VARS['bic_code']):"");
		$VATnumber = (isset($HTTP_POST_VARS['VATnumber'])?quote_smart($HTTP_POST_VARS['VATnumber']):"");
		$company_num_code = (isset($HTTP_POST_VARS['company_num_code'])?quote_smart($HTTP_POST_VARS['company_num_code']):"");
		$cd_bank_instructions = (isset($HTTP_POST_VARS['cd_bank_instructions'])?quote_smart($HTTP_POST_VARS['cd_bank_instructions']):"");
		$bank_IBState = (isset($HTTP_POST_VARS['bank_IBState'])?quote_smart($HTTP_POST_VARS['bank_IBState']):"");
		$bank_IBCity = (isset($HTTP_POST_VARS['bank_IBCity'])?quote_smart($HTTP_POST_VARS['bank_IBCity']):"");
		$bank_IBName = (isset($HTTP_POST_VARS['bank_IBName'])?quote_smart($HTTP_POST_VARS['bank_IBName']):"");
		$bank_IBRoutingCodeType = (isset($HTTP_POST_VARS['bank_IBRoutingCodeType'])?quote_smart($HTTP_POST_VARS['bank_IBRoutingCodeType']):"");
		$bank_IBRoutingCode = (isset($HTTP_POST_VARS['bank_IBRoutingCode'])?quote_smart($HTTP_POST_VARS['bank_IBRoutingCode']):"");


     

		$qry_update_user = "update cs_companydetails set cd_bank_routingnumber='$cd_bank_routingnumber', 
		cd_bank_instructions='$cd_bank_instructions',company_bank = '$currentBank', other_company_bank = '$bank_other', 
		beneficiary_name='$beneficiary_name', bank_account_name='$bank_account_name', bank_address = '$bank_address', 
		bank_city = '$bank_city', bank_zipcode = '$bank_zipcode', bank_country = '$bank_country', bank_phone = '$bank_phone', 
		bank_sort_code = '$bank_sort_code', bank_account_number = '$bank_account_number', VATnumber='$VATnumber',  
		bank_IBState = '$bank_IBState', bank_IBName = '$bank_IBName', bank_IBCity='$bank_IBCity', 
		bank_IBRoutingCodeType = '$bank_IBRoutingCodeType', bank_IBRoutingCode = '$bank_IBRoutingCode',  
		cd_bank_routingcode = '$cd_bank_routingcode',registrationNo='$registrationNo' ";

		$qry_update_user .= "  where userId='$userid' $bank_sql_limit";

		//if ($adminInfo['li_level'] == 'full') 
		etelPrint($qry_update_user);
		mysql_query($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>$qry_update_user");


	}
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";
	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid='$company_id' $bank_sql_limit";
	if($qry_select_companies != "")
	{
		if(!($show_sql =mysql_query($qry_select_companies)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
?>
<?
	if($companyInfo = mysql_fetch_array($show_sql))
	{
		if($companyInfo['state']=="")
		{
			$state=str_replace("\n",",\t",$companyInfo['ostate']);
		}
		else
		{
			$state=str_replace("\n",",\t",$companyInfo['state']);
		}
		if($companyInfo['transaction_type'] == "tele") {
			$script_display ="yes";
			$sendecommerce_diplay = "none";
		}else {
			$script_display ="none";
			$sendecommerce_diplay = "yes";
		}
		if($companyInfo['send_ecommercemail'] == 1) {
			$sendecommerce_checked = "checked";
		}else {
			$sendecommerce_checked = "";
		}


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
	if(document.Frmcompany.merchant_discount.value==""){ document.Frmcompany.merchant_discount.value=0;}
	if(document.Frmcompany.reseller_transfee.value==""){ document.Frmcompany.reseller_transfee.value=0;}
	if(document.Frmcompany.merchant_transfee.value==""){ document.Frmcompany.merchant_transfee.value=0;}

	if(field=="disc") {
		document.Frmcompany.total_discount.value = parseInt(document.Frmcompany.reseller_discount.value) + parseInt(document.Frmcompany.merchant_discount.value)
		return false;
	} else {
		document.Frmcompany.total_transfee.value = parseInt(document.Frmcompany.reseller_transfee.value) + parseInt(document.Frmcompany.merchant_transfee.value)
		return false;
	}
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
            Wire Instructions </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form action="editCompanyProfile2.php"  name="Frmcompany" method="post">
	<table style="margin-top:10" align="center">
	<tr>
	<td align="center">
	<a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
	<a href="editCompanyProfile3.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
	</td>
	</tr>
	<?php
	$status = $etel_completion_array[intval($companyInfo['cd_completion'])]['txt'];
	$bold = $etel_completion_array[intval($companyInfo['cd_completion'])]['style'];
?>
<?php if(1){// $adminInfo['li_level'] == 'full') { ?>
            <tr align="center" valign="middle">
              <td height="30"align="center">
              <span style="font-size:12px; font-weight:<?=$bold?> "><?=ucfirst($companyInfo['companyname'])?></span> - <span style="font-size:10px; font-weight:<?=$bold?> ">
                <?=$status?>
              </span></td>
              </tr>
	<? } ?>
	</table>

              <div align="center" style="font-size: 10px">
                <input type="hidden" name="userid" value="<?=$companyInfo['userId']?>">
                </input>
                <input type="hidden" name="update" value="yes">
                </input>

</div>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top">

		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">
					   <!--  Bank details integrating ends -->
                      <tr>
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Wire Instructions </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left"><?php if(1){// $adminInfo['li_level'] == 'full' || 1) { ?>
                          <a href="<?="editCompanyProfile1.php?username=".$companyInfo['username']."&password=".$companyInfo['password']."&gw_id=".$_SESSION['gw_id']."&company_id=".$companyInfo['userId']?>&loginas=1">Login as
                          <?= $companyInfo['companyname'] ?>
                          </a>
                          <?php } ?></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; With which bank do you hold a company account?</font></td>
                <td align="left" valign="middle" height="30" width="50%"><select title="reqmenu" name="currentBank" id="currentBank" style="font-family:arial;font-size:10px;width:270px" onChange="document.getElementById('bank_other').src=(this.value!='other'?'':'req')">
					<?=func_get_bank_select($companyInfo['company_bank'])?>
					<option value="other">other</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp;If 'Other', please specify:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text" name="bank_other" id="bank_other" style="font-family:arial;font-size:10px;width:270px" value="<?=htmlentities($companyInfo['other_company_bank'])?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Beneficiary Name:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text" alt="req" maxlength="100" name="beneficiary_name" style="font-family:arial;font-size:10px;width:250px" value="<?=htmlentities($companyInfo['beneficiary_name'])?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Name On Bank Account:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text" alt="req" name="bank_account_name" style="font-family:arial;font-size:10px;width:250px" value="<?=htmlentities($companyInfo['bank_account_name'])?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Bank Address:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text" alt="req" name="bank_address" style="font-family:arial;font-size:10px;width:250px" value="<?=htmlentities($companyInfo['bank_address'])?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%"><font face="verdana" size="1">&nbsp; Bank Country: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle"><select title="reqmenu" name="bank_country" id="bank_country" style="font-family:arial;font-size:10px;width:170px" onChange="requireIB()">
                    <?=func_get_country_select($companyInfo['bank_country'],1)?>
                  </select>
                </td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%"><font face="verdana" size="1">&nbsp; Bank City: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle"><input  name="bank_city" type="text" id="bank_city" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_city']?>" alt="req"></td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%"><font face="verdana" size="1">&nbsp; Bank Zipcode: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle"><input  name="bank_zipcode" type="text" id="bank_zipcode" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_zipcode']?>" alt="req"></td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Bank Telephone Number:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input alt="req" type="text"  name="bank_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_phone']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Iban No(EUROPEAN only)&nbsp;</font>:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text"  name="bank_sort_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_sort_code']?>">                  </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1"> &nbsp; Bank Account Number: &nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%"><input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_account_number']?>"></td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;VAT Number: </font></td>
                <td align="left" height="29" width="50%"><input name="VATnumber" type="text" id="VATnumber" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['VATnumber']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Registration Number: </font></td>
                <td align="left" height="29" width="50%"><input type="text" name="regnum" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['registrationNo']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Routing Number: </font></td>
                <td align="left" height="29" width="50%"><input type="text" name="cd_bank_routingnumber" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['cd_bank_routingnumber']?>" alt="req">
                </td>
              </tr>
	          <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Routing Type: </font></td>
                <td align="left" height="29" width="50%">
                  <select name="cd_bank_routingcode" id="cd_bank_routingcode" style="font-family:arial;font-size:10px;width:170px" title="reqmenu">
                    <option value="">- Select -</option>
                    <option value="1" <?=$companyInfo['cd_bank_routingcode']==1?"selected":""?>>ABA</option>
                    <option value="2" <?=$companyInfo['cd_bank_routingcode']==2?"selected":""?>>SWIFT</option>
                    <option value="3" <?=$companyInfo['cd_bank_routingcode']==3?"selected":""?>>Chips ID</option>
                    <option value="4" <?=$companyInfo['cd_bank_routingcode']==4?"selected":""?>>Sort Code</option>
                    <option value="5" <?=$companyInfo['cd_bank_routingcode']==5?"selected":""?>>Transit Number</option>
                    <option value="6" <?=$companyInfo['cd_bank_routingcode']==6?"selected":""?>>BLZ Code</option>
                    <option value="7" <?=$companyInfo['cd_bank_routingcode']==7?"selected":""?>>BIC Code</option>
                    <option value="8" <?=$companyInfo['cd_bank_routingcode']==8?"selected":""?>>Other</option>
				  </select>
				  <script language="javascript">document.getElementById('cd_bank_routingcode').value='<?=$companyInfo['cd_bank_routingcode']?>'</script>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Routing Number (If Applicable): </font></td>
                <td align="left" height="29" width="50%"><input type="text" name="bank_IBRoutingCode" id="bank_IBRoutingCode" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_IBRoutingCode']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Routing Type (If Applicable): </font></td>
                <td align="left" height="29" width="50%"><select name="bank_IBRoutingCodeType" id="bank_IBRoutingCodeType" style="font-family:arial;font-size:10px;width:170px">
                  <option value="">- Select -</option>
                    <option value="1" <?=$companyInfo['bank_IBRoutingCodeType']==1?"selected":""?>>ABA</option>
                    <option value="2" <?=$companyInfo['bank_IBRoutingCodeType']==2?"selected":""?>>SWIFT</option>
                    <option value="3" <?=$companyInfo['bank_IBRoutingCodeType']==3?"selected":""?>>Chips ID</option>
                    <option value="4" <?=$companyInfo['bank_IBRoutingCodeType']==4?"selected":""?>>Sort Code</option>
                    <option value="5" <?=$companyInfo['bank_IBRoutingCodeType']==5?"selected":""?>>Transit Number</option>
                    <option value="6" <?=$companyInfo['bank_IBRoutingCodeType']==6?"selected":""?>>BLZ Code</option>
                    <option value="7" <?=$companyInfo['bank_IBRoutingCodeType']==7?"selected":""?>>BIC Code</option>
                    <option value="8" <?=$companyInfo['bank_IBRoutingCodeType']==8?"selected":""?>>Other</option>
                </select>
				  <script language="javascript">document.getElementById('bank_IBRoutingCodeType').value='<?=$companyInfo['bank_IBRoutingCodeType']?>'</script>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Name (If Applicable): </font></td>
                <td align="left" height="29" width="50%"><input type="text" name="bank_IBName" id="bank_IBName" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_IBName']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank City (If Applicable): </font></td>
                <td align="left" height="29" width="50%"><input type="text" name="bank_IBCity" id="bank_IBCity" style="font-family:arial;font-size:10px;width:150px" value="<?=$companyInfo['bank_IBCity']?>">
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank State (If Applicable): </font></td>
                <td align="left" height="29" width="50%"><select name="bank_IBState" id="bank_IBState"  style="font-family:arial;font-size:10px;width:170px">
                  <?=func_get_state_select($companyInfo['bank_IBState'],1)?>
                </select>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Instructions (Optional): </font></td>
                <td align="left" height="29" width="50%"><textarea name="cd_bank_instructions" cols="30" rows="3"><?=$companyInfo['cd_bank_instructions']?></textarea>
                </td>
                      </tr>

          </table>
		  </td>
<!--		<td align="center" width="50%" valign="top">
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center" width="90%">

		  </table>
		  </td>-->
		</tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="editCompanyProfile1.php?company_id=<?= $company_id?>"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>
		</table>
		</center>

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
}
include("includes/footer.php");
}
?>