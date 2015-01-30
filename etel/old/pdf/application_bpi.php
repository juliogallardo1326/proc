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

$headerInclude= "blank";	
include 'includes/topheader.php';
function func_company_ext_entry($userid,$mastercurrency,$visacurrency,$cnn_cs){
	$qry_exist="select * from cs_companydetails_ext where userid='$userid'";
	if(!$rst_exist=mysql_query($qry_exist,$cnn_cs))
	{
		echo "Cannot execute Query";
	}
	else{
		$num=mysql_num_rows($rst_exist);
		if($num==0)
		{
			$qry_companyext="insert into cs_companydetails_ext (userId,processingcurrency_master,processingcurrency_visa) values('$userid','$mastercurrency','$visacurrency')";	
		}
		else
		{
			$qry_companyext="update cs_companydetails_ext set processingcurrency_master='$mastercurrency',processingcurrency_visa='$visacurrency' where userid='$userid'";
		}
		if(!$rst_update=mysql_query($qry_companyext,$cnn_cs))
		{
			echo "Cannot execute query";
		}
	}

}

$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

if($sessionlogin!=""){
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");

if(isset($HTTP_POST_VARS['volume'])) {
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
		
		$str_update_query  = "update cs_companydetails set cd_previous_transaction_fee='$cd_previous_transaction_fee', cd_previous_discount='$cd_previous_discount', cd_processing_reason='$cd_processing_reason',cd_previous_processor='$cd_previous_processor',volumenumber = '$volume', avgticket = '$avgticket', chargebackper = '$chargeper', ";
		$str_update_query .= "transaction_type = '$rad_order_type', preprocess = '$prepro', recurbilling = '$rebill', currprocessing = '$currpro', billingdescriptor='$billingdesc'  ";
		$str_update_query .= "where userid=$sessionlogin";
		func_company_ext_entry($sessionlogin,$mastercurrency,$visacurrency,$cnn_cs);
		if(isset($HTTP_POST_VARS['volume'])) mysql_query($str_update_query,$cnn_cs) or dieLog(mysql_error());

}else {
	$txtPackagename = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"");
	$txtPackageProduct= (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"");
	$txtPackagePrice= (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"");
	$txtRefundPolicy= (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"");
	$txtDescription= (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"");

	if($txtPackagePrice=="") 
		$txtPackagePrice=0;

		$str_update_query  = "update cs_companydetails set telepackagename = '$txtPackagename', telepackageprod = '$txtPackageProduct', telepackageprice = $txtPackagePrice, ";
		$str_update_query .= "telerefundpolicy = '$txtRefundPolicy', teledescription = '$txtDescription' where userid=$sessionlogin";

		if (!mysql_query($str_update_query,$cnn_cs)) {
			echo mysql_errno().": ".mysql_error()."<BR>";
			echo "Cannot execute update query.";
			echo $str_update_query;
			exit();
		}

}

	$sql_select_qry ="select *  from cs_companydetails where userid=$sessionlogin";
	if(!($run_select_qry =mysql_query($sql_select_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}	
if($show_select_value = mysql_fetch_array($run_select_qry)){ 
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">
function HelpWindow() {
   advtWnd=window.open("aboutbank.htm","Help","'status=1,scrollbars=1,width=500,height=450,left=0,top=0'");
   advtWnd.focus();
}
function requireIB()
{
	var cnt = (document.getElementById('bank_country').value!='US');
	document.getElementById('bank_IBRoutingCode').src = cnt?'req':'';
	document.getElementById('bank_IBRoutingCodeType').title = cnt?'reqmenu':'';
	document.getElementById('bank_IBName').src = cnt?'req':'';
	document.getElementById('bank_IBCity').src = cnt?'req':'';
	document.getElementById('bank_IBState').src = cnt?'reqmenu':'';
}
</script>
<?php beginTable() ?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
    <td width="83%" valign="top" align="center"  height="333">&nbsp;
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">
        <tr>
          <td width="100%" valign="top" align="center"><table width="100%"  height="40"  valign="bottom">
              <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourprocess.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank1.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
              </tr>
            </table>
            <input type="hidden" name="username" value="<?=$username?>">
            </input>
            <table border="0" cellpadding="0"  height="399" width="100%">
              <tr>
                <td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Wire Instructions</td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; With which bank do you hold a company account?</font></td>
                <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><select title="reqmenu" name="currentBank" id="currentBank" style="font-family:arial;font-size:10px;width:270px" onChange="document.getElementById('bank_other').src=(this.value!='other'?'':'req')">
					<?=func_get_bank_select($show_select_value['company_bank'])?>
                  </select>                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;If 'Other', please specify:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_other" id="bank_other" style="font-family:arial;font-size:10px;width:270px" value="<?=htmlentities($show_select_value['other_company_bank'])?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Beneficiary Name:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src="req" maxlength="100" name="beneficiary_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['beneficiary_name'])?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Name On Bank Account:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src="req" name="bank_account_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['bank_account_name'])?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Bank Address:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src="req" name="bank_address" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['bank_address'])?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Bank Country: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><select title="reqmenu" name="bank_country" id="bank_country" style="font-family:arial;font-size:10px;width:170px" onChange="requireIB()">
                    <?=func_get_country_select($show_select_value['bank_country'],1)?>
                  </select>                </td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Bank City: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><input  name="bank_city" type="text" id="bank_city" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_city']?>" src="req"></td>
              </tr>
              <tr>
                <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; Bank Zipcode: &nbsp;&nbsp;</font></td>
                <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><input  name="bank_zipcode" type="text" id="bank_zipcode" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_zipcode']?>" src="req"></td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Bank Telephone Number:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text"  name="bank_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_phone']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Iban No(EUROPEAN only)&nbsp;</font>:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text"  name="bank_sort_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_sort_code']?>">
                  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">(this is an international code &#8211; if you do not know what this is pleasecontact your bank)</font></td>
              </tr>
              <tr>
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp; Bank Account Number: &nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_account_number']?>"></td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;VAT Number: </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="vatnum" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['VATnumber']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Registration Number: </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="regnum" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['registrationNo']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Routing Number: </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="cd_bank_routingnumber" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['cd_bank_routingnumber']?>" src="req">                </td>
              </tr>
	          <tr>
                <td height="29" colspan="2" align="left" valign="center" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank information MUST be provided if you are using a NON-US bank. If you do not fill in this information completely, the wires will fail. Contact your bank for this information.</font>
                                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Routing Number (If Applicable): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_IBRoutingCode" id="bank_IBRoutingCode" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_IBRoutingCode']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Routing Type (If Applicable): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><select name="bank_IBRoutingCodeType" id="bank_IBRoutingCodeType" style="font-family:arial;font-size:10px;width:170px">
                  <option value="">- Select -</option>
                  <option value="1">ABA</option>
                  <option value="2">SWIFT</option>
                  <option value="3">Chips ID</option>
                  <option value="4">Sort Code</option>
                  <option value="5">Transit Number</option>
                  <option value="6">BLZ Code</option>
                  <option value="7">BIC Code</option>
                  <option value="8">Other</option>
                </select>
				  <script language="javascript">document.getElementById('bank_IBRoutingCodeType').value='<?=$show_select_value['bank_IBRoutingCodeType']?>'</script>                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank Name (If Applicable): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_IBName" id="bank_IBName" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_IBName']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank City (If Applicable): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_IBCity" id="bank_IBCity" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['bank_IBCity']?>">                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Intermediary Bank State (If Applicable): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><select name="bank_IBState" id="bank_IBState"  style="font-family:arial;font-size:10px;width:170px">
                  <?=func_get_state_select($show_select_value['bank_IBState'],1)?>
                </select>                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Instructions (Optional): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><textarea name="cd_bank_instructions" cols="30" rows="3"><?=$show_select_value['cd_bank_instructions']?></textarea>                </td>
              </tr>
              <input type="hidden" name="company" value="company">
              <tr>
                <td align="center" valign="center" height="30" colspan="2"><a href="javascript:HelpWindow();"><img border="0" src="images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a> &nbsp;
                  <input name="image" type="image" id="modifycompany" src="images/continue.gif">
                  <br>                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script language="javascript">
requireIB();
</script>
<?php endTable("Merchant Application","application_submit.php?showheader=".$_REQUEST['showheader']) ?>
<?
}

include 'includes/footer.php';
}
?>
