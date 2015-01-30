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
// accounts.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude = "transactions";
include("includes/header.php");

include("includes/message.php");
?>
<?php
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

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";

$qrt_select_companies ="select distinct userId,companyname from cs_companydetails order by companyname";

if ($Transtype == "Submit")  {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
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
		$str_total_query = "where $qrt_select_subqry $qrt_select_merchant_qry";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
//print($qrt_select_companies);
}
?>
<script>
function Displaycompany(){
	if(document.frm_accounts.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frm_accounts.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frm_accounts.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
}
function validation() {
	var isValid = false;
	var obj_element = document.frm_accounts.elements[13];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
			break;
		}
	}
	if (isValid) {
		if (document.frm_accounts.frequency.value == "") {
			alert("Please select a frequency");
			return false;
		} else {
			return true;
		}
	} else {
		alert("Please select a company");
		return false;
	}
}
function showNumDaysBack() {
	var obj_form = document.frm_accounts;
	if (obj_form.frequency.value == "W") {
		document.getElementById('num_days').style.display = "";
		document.getElementById('date_range').style.display = "none";
	} else {
		document.getElementById('num_days').style.display = "none";
		document.getElementById('date_range').style.display = "";
	}
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_accounts;
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

function Displaycompanytype() {
	document.frm_accounts.trans_type.value="Submit";
	document.frm_accounts.action = "accounts.php";
	document.frm_accounts.submit();
}


</script>
<form name="frm_accounts" action="accountReports.php" method="post" onSubmit="return validation();">
<input type="hidden" name="trans_type" value="">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
  <tr>
       <td width="95%" valign="top" align="center">
			<table width="45%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			      
            <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Accounts</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">  
			<tr>
			   <td   height="10"  valign="middle" align="center" width="50%">
		  </td>
			</tr>
			<tr>
			<td colspan="2">
			<div name="date_range" id="date_range" style="display:yes">
			<table cellpadding="0" cellspacing="0">
			<tr>
			<td   height="50"  valign="middle" align="center" width="50%"> 
			  <font face="verdana" size="1">Start Date</font>&nbsp; 
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select>
		     <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		    <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,260,document.frm_accounts.from_date)">
		   </td></tr>
		   <tr>
		    <td height="25"  valign="middle" align="center">&nbsp;&nbsp;<font face="verdana" size="1">End Date</font>&nbsp;
		  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
		  </select>
		  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
		  </select>
		  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
		  </select>
		   <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,300,document.frm_accounts.from_to)">
		  </td>
		  </tr>
		  </table>
		  </div>
		  </td>
		  <tr>
		  <tr><td height="5"></td></tr>
			<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
				 <td height="30" valign="middle" align="right"  width="35%"><font face="verdana" size="1">Company Type</font>&nbsp;&nbsp;</td>
				        <td align="left"  width="65%">
                          <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				<tr>
				 <td height="30" valign="middle" align="right"  width="35%"><font face="verdana" size="1">Merchant Type</font>&nbsp;&nbsp;</td>
				 <td align="left"  width="65%">
				  <select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 175px"  onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
				</select></td>
				</tr>
				<tr>
				<td valign="middle" align="right" width="35%" height="65"><font face="verdana" size="1">Select 
				  Company</font>&nbsp;&nbsp;</td>
				<td width="65%" align="left" >
				  <select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 150px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
				</td></tr>
				<tr>
				<td valign="middle" align="right" width="35%" height="30"><font face="verdana" size="1">Select 
				  Frequency</font>&nbsp;&nbsp;</td>
				<td width="65%" align="left" >
				  <select name="frequency" style="font-family:verdana;font-size:10px;WIDTH: 150px">
					<option value="">Select</option>
					<option value="D">Daily</option>
					<option value="W">Weekly</option>
					<option value="M">Monthy</option>
				</select>
				</td></tr>
				<tr>
				<td colspan="2">
				<div name="num_days" id="num_days" style="display:yes">
				<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
				<tr>
				<td valign="middle" align="right" width="35%" height="30"><font face="verdana" size="1">No: of days back</font>&nbsp;&nbsp;</td>
				<td width="65%" align="left" >
				<input type="text" name="num_days_back" style="width:60px;font-family:verdana;font-size:10px">
				</td></tr>
				<tr>
				<td valign="middle" align="right" width="35%" height="30"><font face="verdana" size="1">From:</font>&nbsp;&nbsp;</td>
				<td width="65%" align="left" >
				<select name="from_week_day" style="font-family:verdana;font-size:10px;">
					<option value="1" selected>Monday</option>
					<option value="2">Tuesday</option>
					<option value="3">Wednesday</option>
					<option value="4">Thursday</option>
					<option value="5">Friday</option>
					<option value="6">Saturday</option>
					<option value="7">Sunday</option>
				</select>&nbsp;&nbsp;
				<font face="verdana" size="1">To:</font>&nbsp;
				<select name="to_week_day" style="font-family:verdana;font-size:10px;">
					<option value="1">Monday</option>
					<option value="2">Tuesday</option>
					<option value="3">Wednesday</option>
					<option value="4">Thursday</option>
					<option value="5">Friday</option>
					<option value="6">Saturday</option>
					<option value="7" selected>Sunday</option>
				</select>
				</td></tr>
				</table>
				</div>
				</td>
				</tr>
				<tr>
				<td valign="middle" align="right" width="35%" height="30"><font face="verdana" size="1">Misc. Fee</font>&nbsp;&nbsp;</td>
				<td width="65%" align="left" >
				<input type="text" name="misc_fee" style="width:60px;font-family:verdana;font-size:10px"><font face="verdana" size="1">$&nbsp;(enter -ve value for deduction)</font>
				</td></tr>
			</table>
			</td></tr>		  
		  <tr><td align="center" height="35" valign="middle">
		  &nbsp;&nbsp;&nbsp;<input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
		</td></tr>		  		  
		</table> 
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
</form>

<?php
include("includes/footer.php");
?>
