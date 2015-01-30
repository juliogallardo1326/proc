<?php
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude="transactions";
include 'includes/header.php';

	
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$sessionService =isset($HTTP_SESSION_VARS["sessionService"])?$HTTP_SESSION_VARS["sessionService"]:"";
$sessionServiceUser =isset($HTTP_SESSION_VARS["sessionServiceUser"])?$HTTP_SESSION_VARS["sessionServiceUser"]:"";

$str_company_id = $sessionlogin;

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
		$str_total_query = "where gateway_id = -1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where gateway_id = -1";
	}
$qrt_select_company="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
} else {
	$qrt_select_company ="select distinct userId,companyname from cs_companydetails where gateway_id = -1 order by companyname";
}
//print($qrt_select_company);

	if(!($show_sql =mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="63%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="50%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Virtual Terminal</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" width="987" >
		<form name="dates" method="POST" action='vt_payment.php' >
		<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		<tr>
		  <td valign="top" align="center" width="50%"><font face="verdana" size="1">
		  Check <input type="radio" name="checkorcard" value="C" >&nbsp;&nbsp;&nbsp;Credit Card <input type="radio" name="checkorcard" value="H" checked>
		  </td></tr>
		<tr>
		  <td height="40"  valign="bottom" align="center" width="50%">
			<input type="image" src="images/submit.jpg">
		  </td></tr>
		</table>
	</form>
		</td>
	</tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
	</table>
    </td>
    </tr>
</table>
<?php 
include("includes/footer.php");

?>