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
// viewGatewaycompany.php:	The admin page functions for viewing the company.
include("includes/sessioncheck.php");


$headerInclude = "companies";
include("includes/header.php");


include("includes/message.php");

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$gatewayCompanyId = isset($HTTP_GET_VARS['gatewayCompanies'])?$HTTP_GET_VARS['gatewayCompanies']:"A";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
 if ($Transtype == "Submit") {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}
	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "and $qrt_select_subqry $qrt_select_merchant_qry";
	}
	$qrt_select_gateway_qry = "";
	if($gatewayCompanyId == "A") {
		$qrt_select_gateway_qry = "gateway_id <> -1";
	} else {
		$qrt_select_gateway_qry = "gateway_id = '$gatewayCompanyId'";
	}

	$qrt_select_company="select distinct userId,companyname from cs_companydetails where $qrt_select_gateway_qry $str_total_query order by companyname";
} else {
	$qrt_select_company ="select distinct userId,companyname from cs_companydetails where gateway_id <> -1 order by companyname";
}

if(!($show_company_sql =mysql_query($qrt_select_company)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

?>
<script language="JavaScript">
var company_id = new Array();
var company_name = new Array();

function validate() {
	if(document.dates.companyname.value=="") {
	 alert("Please select the company.");
	 return false;
	} else {
		var selected_company = document.dates.companyname[document.dates.companyname.selectedIndex].value;
		if (selected_company!="A") {
			document.dates.GatewayCompany.value="Gateway";
			document.dates.action="editCompanyProfile1.php";
		}
		return true;
	}
}

function Displaycompanytype() {
	document.dates.trans_type.value="Submit";
	document.dates.action = "viewGatewayCompany.php";
	document.dates.submit();
}
function func_fillcompanyname()
{
	var str_comparison;
	obj_element = document.dates.companyname;
	var str_search = document.dates.txt_companyname.value;
	var i_length = str_search.length;
	var i_arraylength = company_name.length
	func_removeitem();
	if(str_search == ""){
		obj_element.options.length=obj_element.options.length+1;
		obj_element.options[obj_element.options.length-1].value="A";
		obj_element.options[obj_element.options.length-1].text="All Companies";
		obj_element.options[0].selected=true;
	}
	for (i=0;i<i_arraylength;i++)
	{
		str_comparison = company_name[i].substring(0, i_length);
		if(str_search.toLowerCase()==str_comparison.toLowerCase())
		{
			obj_element.options.length=obj_element.options.length+1;
			obj_element.options[obj_element.options.length-1].value=company_id[i];
			obj_element.options[obj_element.options.length-1].text=company_name[i];
		}
	}
}
function func_removeitem()
{
	obj_element = document.dates.companyname;
	obj_element.options.length=0;
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center" >
    &nbsp;
		<table width="77%" border="0" cellspacing="0" cellpadding="0">
<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Company List</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
		<form name="dates" action="viewGatewayCompanyNext.php"  method="get" onsubmit="return validate();">
		<input type="hidden" name="period"></input>
		<input type="hidden" name="trans_type" value="">
		<input type="hidden" name="GatewayCompany" value="">
				<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		<tr>
			      <td width="51%"  valign="top"> 
<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
					<tr>
					 <td height="40" valign="middle" align="right" width="50%">
						<font face="verdana" size="1">Gateway Company&nbsp;:&nbsp;</font>
					  </td>
					  <td>
                          <select name="gatewayCompanies" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
						<?php 
						 print func_select_all_gatewaycompany(); ?>
						</select>
<?php 				if($gatewayCompanyId !="") { ?>
						<script>
						document.dates.gatewayCompanies.value='<?=$gatewayCompanyId?>'
						</script>
<?php				} ?>						
					</td>
					   </tr>
					<tr>
					 <td height="40" valign="middle" align="right" width="50%">
						<font face="verdana" size="1">Company Type&nbsp;:&nbsp;</font>
					  </td>
					  <td>
					 <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
						<?php print func_select_gateway_company_type($gatewayCompanyId,$companytype);  ?>
						</select>&nbsp;
					</td>
					   </tr>
					<tr>
					 <td height="30" valign="middle" align="right" width="50%">
						<font face="verdana" size="1">Merchant Type&nbsp;:&nbsp;</font>
						</td>
						<td>
						<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
						<?php print func_select_gateway_companytrans_type($companytrans_type); ?>
							</select>&nbsp;
						</td>
						</tr>
						<tr>
						<td align="right" height="30"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Uploaded all documents </font>: 
						</td>
						<td>
								<input type="checkbox" name="chkUploadedDocuments" value="1">
							</td>
						</tr>
						<tr>
						  <td align="right" height="30"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Completed 
							Application :</font> 
						  </td>
						  <td>
								<input type="checkbox" name="chkCompletedApplication" value="1">
							</td>
						</tr>
						<tr>
						  <td align="right" height="30"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Ready 
							to wire :</font> 
						   </td>	
						   <td>
								<input type="checkbox" name="chkReadyToWire" value="1">
							</td>
						</tr>
						</table>
				</td>
				  <td width="49%" valign="top"> 
                    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
					<tr>
					    <td height="40" valign="middle" align="right" width="40%"> 
                          <font face="verdana" size="1">Company Name&nbsp;:&nbsp;</font> 
                        </td>
						<td width="60%"><input type="text" name="txt_companyname" size="10" style="font-family:verdana;font-size:10px;WIDTH: 210px" onKeyUp="javascript:func_fillcompanyname();"> </td>
						</tr>
						<tr>
						<td height="40"  valign="top" align="right">
						<font face="verdana" size="1">Select Company&nbsp;:&nbsp;</font>
						</td>
						<td>
						<select name="companyname" size="10" id="all" style="font-family:verdana;font-size:10px;WIDTH: 210px">
						<?php func_multiselect_transaction_jsarray($qrt_select_company);?>
						</select>
						</td>
					</tr>			 
					</table>
				</td>	
			</tr>
			<tr><td align="center" colspan="2">&nbsp;&nbsp;&nbsp;<input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input></td></tr>
			</table>													
		</form>
	 </td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>
   </td>
  </tr>
</table>
<?php
include("includes/footer.php");
}
?>
<?php
function func_multiselect_transaction_jsarray($qrt_select_company) {
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($show_nonactive_sql) > 0) {
		print"<option value='A' selected>All Companies</option>";	  
	}
	$icount=0;
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	
?>
		<script language="JavaScript">
		company_id[<?=$icount?>] = "<?=$show_nonactive_val[0]?>";
		company_name[<?=$icount?>] = "<?=$show_nonactive_val[1]?>";
		</script>
<?php	
		$icount = $icount + 1; 
	}
}

function func_select_all_gatewaycompany() {
	$qry_select = "select userId,companyname from cs_companydetails where transaction_type='pmtg' order by companyname";
	if(!$qry_value = mysql_query($qry_select)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(mysql_num_rows($qry_value) >0) {
		print "<option value='A'>All Companies</option>";
	}
	while($show_selectdetails = mysql_fetch_array($qry_value)) {
		print "<option value='$show_selectdetails[0]'>$show_selectdetails[1]</option>";
	}
}

function func_select_gateway_company_type($gatewayCompanyId,$companytype) {
	$all = 0;$active = 0;$nonactive = 0;$reseller_ref = 0;$gateway_ref = 0;
	if ($gatewayCompanyId == "A") {
		$qrt_select_company = "select activeuser, reseller_id from cs_companydetails where gateway_id <> -1";
	} else {
		$qrt_select_company = "select activeuser, reseller_id from cs_companydetails where gateway_id = '$gatewayCompanyId'";
	}
   	if(!($show_sql = mysql_query($qrt_select_company)))	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($show_val = mysql_fetch_array($show_sql)) {
		if ($show_val[0] == 1) {
			$active++;
		} else {
			$nonactive++;
		}
		if ($show_val[1] != "") {
			$reseller_ref++;
		} else {
			$gateway_ref++;
		}
		$all++;
	}
	if($companytype=="A")print "<option value='A' selected>All Companies ($all)</option>";else print "<option value='A'>All Companies ($all)</option>";
	if($companytype=="AC")print "<option value='AC' selected>Active Companies ($active)</option>";else print "<option value='AC'>Active Companies ($active)</option>";
	if($companytype=="NC")print "<option value='NC' selected>Non active Companies ($nonactive)</option>";else print "<option value='NC'>Non active Companies ($nonactive)</option>";
	if($companytype=="RE")print "<option value='RE' selected>Reseller Referrals ($reseller_ref)</option>";else print "<option value='RE'>Reseller Referrals ($reseller_ref)</option>";
	if($companytype=="ET")print "<option value='ET' selected>Gateway Referrals ($gateway_ref)</option>";else print "<option value='ET'>Gateway Referrals ($gateway_ref)</option>";
}
?>

