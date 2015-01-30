<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com	 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//					config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// Voicesystemreport.php: 
include("includes/sessioncheck.php");


$headerInclude = "voicesystem";
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
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";

if ($Transtype == "Submit")  {
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
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
}	
//print($qrt_select_companies);
?>
<script>
function Displaycompany(){
	if(document.frm_shipping.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frm_shipping.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frm_shipping.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}
function validation() {
	var isValid = false;
	var obj_element = document.frm_shipping.elements[13];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
		}
	}
	if (isValid) {
		return true;
	} else {
		alert("Please select a company");
		return false;
	}
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_shipping;
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
	document.frm_shipping.trans_type.value="Submit";
	document.frm_shipping.action = "voicesystemreport.php";
	document.frm_shipping.submit();
}

</script>
<form name="frm_shipping" action="voicesystemdetails.php" method="post" onSubmit="return validation();">
<input type="hidden" name="trans_type" value="">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
  <tr>
       <td width="95%" valign="top" align="center">
			<table width="45%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			      
            <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Voice System Report</span></td>
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
			<td   height="40"  valign="middle" align="center" width="50%"> 
			  <font face="verdana" size="1">Start Date</font>
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
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,250,document.frm_shipping.from_date)">
		   </td></tr>
		   <tr>
		    <td height="25"  valign="middle" align="center">&nbsp;&nbsp;<font face="verdana" size="1">End Date</font> 
             
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
		  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,290,document.frm_shipping.from_to)">
		  </td>
		  </tr>
			<tr><td align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td width="34%" height="35" align="right" valign="middle"><font face="verdana" size="1">Company 
				  Type</font>&nbsp;</td>
				<td width="66%" align="left"> 
				  <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				<tr>
				<td width="34%" height="20" align="right" valign="middle"><font face="verdana" size="1">Merchant 
				  Type</font>&nbsp;</td>
				        <td width="66%" align="left"> <select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
                            <?php print func_select_companytrans_type($companytrans_type); ?> 
                          </select></td>
				</tr>
				<tr>
				<td width="34%" align="right" valign="middle" height="70"><font face="verdana" size="1">Select 
				  Company&nbsp;</font></td>
				<td width="66%" align="left" >
				  <select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
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
