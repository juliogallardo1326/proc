<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com	 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// CompanyUser.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude="transactions";
include("includes/header.php");

include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";

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
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_company="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
} else {
	$qrt_select_company ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";
}
//print($qrt_select_company);

	if(!($show_sql =mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<script>
function Displaycompany(){
	if(document.dates.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.dates.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.dates.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;
}

function validate(obj_form)
{
	if(document.dates.companyname.value=="") {
	 alert("Please select the company.");
	 return false;
	}
	var action_page ="";
	if(obj_form.rad_payment_type[0].checked)
	{
		action_page = "cheque.php";
	}
	else if(obj_form.rad_payment_type[1].checked)
	{
		action_page = "creditcard.php";
	}
		obj_form.action = action_page;
		obj_form.submit();

}

function Displaycompanytype() {
	document.dates.trans_type.value="Submit";
	document.dates.action = "virtualterminal.php";
	document.dates.submit();
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="63%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="50%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Virtual Terminal</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" width="987" >
		<form name="dates" method="GET" onSubmit="return validate(document.dates)";>
		<input type="hidden" name="trans_type" value="">
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		<tr>
			 <td height="40" valign="middle" align="center" width="50%"><font face="verdana" size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Company Type&nbsp;:&nbsp;</font><select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
		<?php print func_select_mailcompanytype($companytype); ?>
				</select>&nbsp;</td>
                </tr>
			<tr>
			 <td height="40" valign="middle" align="center" width="50%"><font face="verdana" size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Merchant Type &nbsp;:&nbsp;</font><select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
					</select>&nbsp;</td>
                </tr>
				<tr><td>
				<table width="100%"><tr>
				<td   height="40"  valign="middle" align="center" width="50%"><font face="verdana" size="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Company&nbsp;:&nbsp;</font><select id="all" name="companyname" style="font-family:verdana;font-size:10px;WIDTH: 210px">
				<?php func_select_company_from_query($qrt_select_company);
				?>
				</select>
				</td></tr></table>
				</td></tr>
		<tr>
		  <td valign="top" align="center" width="50%"><font face="verdana" size="1">
		  Check <input type="radio" name="rad_payment_type" value="C" disabled >&nbsp;&nbsp;&nbsp;Credit Card <input type="radio" name="rad_payment_type" value="H" checked>
		  </td></tr>
		<tr>
		  <td height="40"  valign="bottom" align="center" width="50%">
			<input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg">
		  </td></tr>
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