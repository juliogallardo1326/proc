<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// CompanyUser.php:	The admin page functions for selecting the company for adding company user. 
include("../includes/sessioncheckserviceuser.php");

require_once("../includes/function.php");
include("../admin/includes/serviceheader.php");
$headerInclude = "service";
session_register("sessionactivity_type");
$_SESSION["sessionactivity_type"]="";
include("../admin/includes/topheader.php");
$i_service_user_id = isset($_SESSION["sessionServiceUserId"]) ? $_SESSION["sessionServiceUserId"] : "";
$str_company_ids = "";
$is_valid_service_user = true;
$qry_select_company = "select distinct userid,companyname from cs_companydetails";
if($i_service_user_id != "")
{
	$str_company_ids = func_get_company_ids_for_service_user($cnn_cs,$i_service_user_id);
	$i_gateway_id = func_get_value_of_field($cnn_cs,"cs_customerserviceusers","gateway_id","id",$i_service_user_id);
	if($str_company_ids != "")
	{
		if($str_company_ids != "A")
		{
			$qry_select_company .= " where userid in ($str_company_ids)";
		} else if ($i_gateway_id != -1) {
			$qry_select_company .= " where gateway_id = '$i_gateway_id'";
		}
	}
	else
	{
		$is_valid_service_user = false;
	}
}
$qry_select_company .= " order by companyname";
if(!($show_sql =mysql_query($qry_select_company)))
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print("Cannot execute query");
	exit();
}
?>
<script>
function validate(obj_form)
{
	var action_page = "../cheque.php";
	if(obj_form.rad_payment_type[0].checked)
	{
		action_page = "../cheque.php";
	}
	else if(obj_form.rad_payment_type[1].checked)
	{
		action_page = "../creditcard.php";
	}
	if(obj_form.companyname[obj_form.companyname.selectedIndex].value == "")
	{	
		alert("Please Select a Company");
		return false;
	}
	else
	{
		obj_form.action = action_page;
		return true;
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="63%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="50%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Virtual Terminal</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" width="987" >
		<form name="dates" action="../cheque.php"  method="GET" onSubmit="return validate(document.dates)";>
	  	<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		
		
		<tr>
		  <td height="50"  valign="center" align="center" width="50%"><font face="verdana" size="1">Select Company&nbsp;:&nbsp;</font><select name="companyname" style="font-family:verdana;font-size:10px">
		  <option value="">Select Company</option>
<?		
		if($is_valid_service_user)
		{
			while($show_val = mysql_fetch_array($show_sql)) 
			{
				if($show_val[0] == 8){
?>
				<option value='<?=$show_val[0]?>' selected><?=$show_val[1]?></option>	  
<? 
				} else {
?>
				<option value='<?=$show_val[0]?>'><?=$show_val[1]?></option>
<?				}
			}
		}
?>
		  </select></td></tr>
		<tr>
		  <td height="30"  valign="center" align="center" width="50%"><font face="verdana" size="1">
		  Check <input type="radio" name="rad_payment_type" value="C" checked>&nbsp;&nbsp;&nbsp;Credit Card <input type="radio" name="rad_payment_type" value="H">
		  </td></tr>
		<tr>
		  <td height="40"  valign="bottom" align="center" width="50%">
			<input type="image" src="../images/submit.jpg">
		  </td></tr>
		</table>
	</form>
		</td>
	</tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
	</table>
    </td>
    </tr>
</table>
<?php 
include("../includes/footer.php");
?>