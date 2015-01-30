<?php	
	
	$CompanyLogoName = isset($HTTP_SESSION_VARS["sessionCompanyLogoName"])?trim($HTTP_SESSION_VARS["sessionCompanyLogoName"]):"";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$sessionService =isset($HTTP_SESSION_VARS["sessionService"])?$HTTP_SESSION_VARS["sessionService"]:"";
$sessionServiceUser =isset($HTTP_SESSION_VARS["sessionServiceUser"])?$HTTP_SESSION_VARS["sessionServiceUser"]:"";

$str_company_id = $sessionlogin;
if ($str_company_id == "")
{
	$str_company_id = $sessionCompanyUser;
}
if ($str_company_id == "") // when it comes from the service section
{
	$str_company_id = $sessionService;
}
if ($str_company_id == "") // when it comes from the service section
{
	$str_company_id = $sessionServiceUser;
}
$int_get_permission = "";
if($str_company_id !=""){
	require_once( 'includes/function.php');
	$int_get_permission = func_get_value_of_field($cnn_cs,"cs_companydetails","block_virtualterminal","userid",$str_company_id);
} 


?>
<html>
<head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="includes/styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="includes/styles/style.css" type="text/css" rel="stylesheet">
<link href="includes/styles/text.css" type="text/css" rel="stylesheet">
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<!--header-->
<div id="testMode" style="position:absolute;width:150px;height:40px;z-index:0; overflow: hidden;visibility:visible;left:160;top:85">
<table><tr>
<td><span style="font-face:verdana;font-weight:bold;Color:#448A99;"><strong><font face='verdana' color='#448A99' size='2'><?=$_SESSION["sessionactivity_type"]?></font></strong></span></td>
</tr></table>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%">&nbsp;<img alt='' border='0' src='<?=$CompanyLogoName?>'></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="includes/images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="includes/images/top2.jpg" width="217" height="63"><img alt="" border="0" src="includes/images/top3.jpg" width="160" height="63"><br>
<img alt="" border="0" src="includes/images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="includes/images/top5.jpg" width="217" height="63"><img alt="" border="0" src="includes/images/top6.jpg" width="160" height="63"></td>
</tr>
</table>
<!--header ends here-->

<?php
	if(isset($_SESSION["sessionService"]) || isset($_SESSION["sessionServiceUser"]))
	{ ?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_ser1.gif" width="65" height="9"><img border="0" src="includes/images/02_ser.gif" width="133" height="9"><img border="0" src="includes/images/03_ser.gif" width="90" height="9"></td></tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/service/customerservice.php"><img border="0" src="includes/images/search.gif" width="42" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/service/changepassword.php"><img border="0" src="includes/images/changepassword.gif" width="109" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr> 
</table>
<!--topmenu ends-->
<?php
	} 
else if(isset($_SESSION["sessionCompanyUser"]))
	{ ?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_ser.gif" width="124" height="9"><img border="0" src="includes/images/02.gif" width="86" height="9"></td></tr>
<tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/home.php"><img border="0" src="includes/images/virtualterminal.gif" width="102" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr>
</table>
<!--topmenu ends-->
<?php
	} else if($_SESSION["sessionlogin_type"] == "tele"){
		if(isset($_SESSION["sessionactivity_type"])) {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_tele1.gif" width="79" height="9"><img border="0" src="includes/images/02_tele1.gif" width="86" height="9"><img border="0" src="includes/images/03_tele1.gif" width="59" height="9"><img border="0" src="includes/images/04_tele1.gif" width="70" height="9"><img border="0" src="includes/images/05_tele1.gif" width="66" height="9"><img border="0" src="includes/images/06_tele1.gif" width="103" height="9"><img border="0" src="includes/images/07_tele1.gif" width="88" height="9"></td></tr>
<tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/Listdetails.php"><img border="0" src="includes/images/starthere.gif" width="57" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/profile_blank.php"><img border="0" src="includes/images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/customerservice_blank.php"><img border="0" src="includes/images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/shipping.php"><img border="0" src="includes/images/shipping.gif" width="49" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/ledger.php"><img border="0" src="includes/images/ledgers.gif" width="44" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/transaction_blank.php"><img border="0" src="includes/images/transactions.gif" width="80" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr>
</table>
<!--topmenu ends-->
<? } else {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
 <tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_tele.gif" height="9"><img border="0" src="includes/images/02_tele.gif" height="9"><img border="0" src="includes/images/03_tele.gif" height="9"><img src="includes/images/04_tele.gif" height="9" border="0"><img src="includes/images/05_tele.gif" height="9" border="0"><img border="0" src="includes/images/06_tele.gif" height="9"></td></tr>
<tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/profile_blank.php"><img border="0" src="includes/images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/transaction_blank.php"><img border="0" src="includes/images/transactions.gif" width="80" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/ledger.php"><img border="0" src="includes/images/ledgers.gif" width="44" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/customerservice_blank.php"><img border="0" src="includes/images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/shipping.php"><img border="0" src="includes/images/shipping.gif" ></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr> 

</table>
<!--topmenu ends-->
<? } 
?>
<?php
} else {
	if(isset($_SESSION["sessionactivity_type"])) {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_test.gif" width="79" height="9"><img border="0" src="includes/images/02_test.gif" width="86" height="9"><img border="0" src="includes/images/03_test.gif" width="124" height="9"><img border="0" src="includes/images/04_test.gif" width="59" height="9"><img border="0" src="includes/images/05_test_ns.gif" width="66" height="9"><img border="0" src="includes/images/06_test_ns.gif" width="103" height="9"><img border="0" src="includes/images/07_test_ns.gif" width="88" height="9"></td>
</tr>
<tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/Listdetails.php"><img border="0" src="includes/images/starthere.gif" width="57" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/profile_blank.php"><img border="0" src="includes/images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/VT_blank.php"><img border="0" src="includes/images/virtualterminal.gif" width="102" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/customerservice_blank.php"><img border="0" src="includes/images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/ledger.php"><img border="0" src="includes/images/ledgers.gif" width="44" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/transaction_blank.php"><img border="0" src="includes/images/transactions.gif" width="80" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr>
</table>
<!--topmenu ends-->
<? } else {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
  <tr>
<td height="9" align="center" background="includes/images/menutopbg.gif"><img border="0" src="includes/images/01_user1.gif" width="86" height="9"><?php if ($int_get_permission != 1) { ?>  <img border="0" src="includes/images/02_user1.gif" width="124" height="9"><?php } ?><img border="0" src="includes/images/03_user1.gif" width="59" height="9"><img border="0" src="includes/images/04_user2.gif" width="67" height="9"><img border="0" src="includes/images/05_user2.gif" width="103" height="9"><img border="0" src="includes/images/06_user2.gif" width="91" height="9"></td></tr>
<tr>
<td background="includes/images/midbg.gif" align="center"><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/profile_blank.php"><img border="0" src="includes/images/profile.gif" width="64" height="27"></a>  <?php if ($int_get_permission != 1) { ?><img alt="" border="0" src="includes/images/break.gif" width="22" height="27">
  <a href="includes/VT_blank.php"><img border="0" src="includes/images/virtualterminal.gif" width="102" height="27"></a>  <?php } ?><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/customerservice_blank.php"><img border="0" src="includes/images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="includes/images/break.gif" width="22" height="27"><!--<a href="shipping.php"><img border="0" src="images/shipping.gif" width="49" height="27"></a><img alt="" border="0" src="images/break.gif" width="22" height="27">--><a href="includes/ledger.php"><img border="0" src="includes/images/ledgers.gif" width="44" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href="includes/transaction_blank.php"><img border="0" src="includes/images/transactions.gif" width="80" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["includes/sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="includes/images/logout.gif" width="45" height="27"></a><img border="0" src="includes/images/break.gif" width="22" height="27"></td>
</tr> 
</table>
<!--topmenu ends-->
<? }

} 
?>

<?php include("includes/includes/displaytimer.php");?>
