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
// gatewayList.php:	
include("includes/sessioncheck.php");


$headerInclude = "transactions";
include("includes/header.php");

include("includes/message.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?quote_smart($HTTP_SESSION_VARS["sessionAdmin"]):"";
if($sessionAdmin!="")
{
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$gatewayCompanyId = isset($HTTP_POST_VARS["gatewayCompanies"])?quote_smart($HTTP_POST_VARS["gatewayCompanies"]):"A";

if($gatewayCompanyId != "A") {
	$qrt_select_company = "select userId, companyname from  cs_companydetails where gateway_id =$gatewayCompanyId order by companyname";
} else {
	$qrt_select_company = "select userId, companyname from  cs_companydetails where gateway_id !=-1 order by companyname";
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
			objForm = document.dates;
			var strCompany;
			strCompany = "";
			for($i=0;$i<objForm.companyname.length;$i++)
			{
				if(objForm.companyname.options[$i].selected == true)
				{
					if(strCompany =="" ) {
						strCompany = objForm.companyname.options[$i].value;
					}else{
						strCompany = strCompany +","+objForm.companyname.options[$i].value;
					}
				}	
			}
			objForm.hid_companies.value = strCompany;
			document.dates.action="viewGatewayTransactions.php";
		}
		return true;
	}
}

function Displaycompanytype() {
	document.dates.trans_type.value="Submit";
	document.dates.action = "gatewayList.php";
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
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.dates;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center" >
    &nbsp;
		<table width="60%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Gateway Company List</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">
		<form name="dates" action="viewGatewayTransactions.php"  method="POST" onsubmit="return validate();">
		<input type="hidden" name="trans_type" value="">
		<input type="hidden" name="hid_companies" value="">
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		<tr>
			<td width="100%"  valign="top">
					<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="206">
						<tr><td width="40%" height="40" align="right"><font face="verdana" size="1"> 
						Start Date :&nbsp;
						</font></td><td width="60%">
                    <select name="opt_from_month" style="font-size:10px">
                      <?php func_fill_month($i_from_month); ?>
                    </select> <select name="opt_from_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_from_day); ?>
                    </select>
                    <select name="opt_from_year" style="font-size:10px">
                      <?php func_fill_year($i_from_year); ?>
                    </select>
					<input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(650,105,document.dates.from_date)">
				</td></tr>
				<tr><td width="40%" align="right"><font face="verdana" size="1">Start Date :&nbsp;</font>
				</td><td width="60%">
				  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_month($i_to_month); ?>
                    </select> <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_to_day); ?>
                    </select> <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_year($i_to_year); ?>
                    </select> 
					 <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(650,135,document.dates.from_to)">

				</td></tr>
						<tr>
					    <td height="40" valign="middle" align="right" width="40%"> 
                          <font face="verdana" size="1">Gateway Companies&nbsp;:&nbsp;</font> 
                        </td>
						<td width="60%"> 
                          <select name="gatewayCompanies" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
						<?php 
						 print func_select_all_gatewaycompany(); ?>
						</select>&nbsp;
<?PHP 				if($gatewayCompanyId !="") { ?>
						<script>
						document.dates.gatewayCompanies.value='<?=$gatewayCompanyId?>'
						</script>
<?php				} ?>						
						</td>
						</tr>
					<tr>
					    <td height="30" valign="middle" align="right" width="40%"> 
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
			<tr><td align="center" colspan="2" valign="bottom" height="30">&nbsp;&nbsp;&nbsp;<input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input></td></tr>
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

?>

