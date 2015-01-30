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
$int_get_permission = 1;
if($str_company_id !=""){
	//require_once( 'includes/function.php');
	//$int_get_permission = func_get_value_of_field($cnn_cs,"cs_companydetails","block_virtualterminal","userid",$str_company_id);
} 

if(!$printable_version){
?>
<html>
<head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?=$usedir?>styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="<?=$usedir?>styles/style.css" type="text/css" rel="stylesheet">
<link href="<?=$usedir?>styles/text.css" type="text/css" rel="stylesheet">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0"  marginheight="0" onLoad="MM_preloadImages('../images/bug1.gif')">
<!--header-->
<div id="testMode" style="position:absolute;width:150px;height:40px;z-index:0; overflow: hidden;visibility:visible;left:160;top:85">
<table><tr>
<td><span style="font-face:verdana;font-weight:bold;Color:#448A99;"><strong><font face='verdana' color='#448A99' size='2'><?=$_SESSION["sessionactivity_type"]?></font></strong></span></td>
</tr></table>
</div>
<div style="position:absolute; left: 302px; top: 30px;" ><a href="<?=$usedir?>support/index.php?caseid=NewTicket" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('bug','','<?=$usedir?>images/bug1.gif',1)"><img src="<?=$usedir?>images/bug2.gif" alt="Bug Report" name="bug" width="100" height="100" border="0"></a></div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%">&nbsp;<img alt='' border='0' src='<?=$CompanyLogoName?>'></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="<?=$usedir?>images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="<?=$usedir?>images/top2.jpg" width="217" height="63"><img alt="" border="0" src="<?=$usedir?>images/top3.jpg" width="160" height="63"><br>
<img alt="" border="0" src="<?=$usedir?>images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="<?=$usedir?>images/top5.jpg" width="217" height="63"><img alt="" border="0" src="<?=$usedir?>images/top6.jpg" width="160" height="63"></td>
</tr>
</table>
<!--header ends here-->

<?php
	if(isset($_SESSION["sessionService"]) || isset($_SESSION["sessionServiceUser"]))
	{ ?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_ser1.gif" width="65" height="9"><img border="0" src="<?=$usedir?>images/02_ser.gif" width="133" height="9"><img border="0" src="<?=$usedir?>images/03_ser.gif" width="50" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="90" height="9"></td>
</tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>service/customerservice.php"><img border="0" src="<?=$usedir?>images/search.gif" width="42" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>service/changepassword.php"><img border="0" src="<?=$usedir?>images/changepassword.gif" width="109" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
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
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_ser.gif" width="124" height="9"><img border="0" src="<?=$usedir?>images/02.gif" width="86" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="72" height="9"></td>
</tr>
<tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>home.php"><img border="0" src="<?=$usedir?>images/virtualterminal.gif" width="102" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
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
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_tele1.gif" width="79" height="9"><img border="0" src="<?=$usedir?>images/02_tele1.gif" width="86" height="9"><img border="0" src="<?=$usedir?>images/03_tele1.gif" width="59" height="9"><img border="0" src="<?=$usedir?>images/04_tele1.gif" width="70" height="9"><img border="0" src="<?=$usedir?>images/05_tele1.gif" width="66" height="9"><img border="0" src="<?=$usedir?>images/06_tele1.gif" width="103" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="50" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="88" height="9"></td>
</tr>
<tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>Listdetails.php"><img border="0" src="<?=$usedir?>images/starthere.gif" width="57" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>profile_blank.php"><img border="0" src="<?=$usedir?>images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?><?=($_SESSION["sessionlogin_type"] != "tele"?"websetup_blank.php":"customerservice_blank.php") ?>"><img border="0" src="<?=$usedir?>images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>shipping.php"><img border="0" src="<?=$usedir?>images/shipping.gif" width="49" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>ledger.php"><img border="0" src="<?=$usedir?>images/ledgers.gif" width="44" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>report.php?period=p"><img border="0" src="<?=$usedir?>images/transactions.gif" width="80" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
</tr>
</table>
<!--topmenu ends-->
<? } else {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
 <tr>
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_tele.gif" height="9"><img border="0" src="<?=$usedir?>images/02_tele.gif" height="9"><img border="0" src="<?=$usedir?>images/03_tele.gif" height="9"><img src="<?=$usedir?>images/04_tele.gif" height="9" border="0"><img src="<?=$usedir?>images/05_tele.gif" height="9" border="0"><img border="0" src="<?=$usedir?>images/06_tele.gif" height="9"></td></tr>
<tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>profile_blank.php"><img border="0" src="<?=$usedir?>images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>report.php?period=p"><img border="0" src="<?=$usedir?>images/transactions.gif" width="80" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>ledger.php"><img border="0" src="<?=$usedir?>images/ledgers.gif" width="44" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?><?=($_SESSION["sessionlogin_type"] != "tele"?"websetup_blank.php":"customerservice_blank.php") ?>"><img border="0" src="<?=$usedir?>images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>shipping.php"><img border="0" src="<?=$usedir?>images/shipping.gif" ></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
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
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_test.gif" width="79" height="9"><img border="0" src="<?=$usedir?>images/02_test.gif" width="86" height="9"><img border="0" src="<?=$usedir?>images/04_test.gif" width="59" height="9"><img border="0" src="<?=$usedir?>images/05_test_ns.gif" width="66" height="9"><img border="0" src="<?=$usedir?>images/06_test_ns.gif" width="103" height="9"><img border="0" src="<?=$usedir?>images/07_test_ns.gif" width="50" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="88" height="9"></td>
</tr>
<tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>Listdetails.php"><img border="0" src="<?=$usedir?>images/starthere.gif" width="57" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>profile_blank.php"><img border="0" src="<?=$usedir?>images/profile.gif" width="64" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?><?=($_SESSION["sessionlogin_type"] != "tele"?"websetup_blank.php":"customerservice_blank.php") ?>"><img border="0" src="<?=$usedir?>images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>ledger.php"><img border="0" src="<?=$usedir?>images/ledgers.gif" width="44" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>report.php?period=p"><img border="0" src="<?=$usedir?>images/transactions.gif" width="80" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
</tr>
</table>
<!--topmenu ends-->
<? } else {
?>
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
  <tr>
<td height="9" align="center" background="<?=$usedir?>images/menutopbg.gif"><img border="0" src="<?=$usedir?>images/01_user1.gif" width="86" height="9"><?php if ($int_get_permission != 1) { ?>  <img border="0" src="<?=$usedir?>images/02_user1.gif" width="124" height="9"><?php } ?><img border="0" src="<?=$usedir?>images/03_user1.gif" width="59" height="9"><img border="0" src="<?=$usedir?>images/04_user2.gif" width="67" height="9"><img border="0" src="<?=$usedir?>images/05_user2.gif" width="103" height="9"><img border="0" src="<?=$usedir?>images/06_user2.gif" width="50" height="9"><img border="0" src="<?=$usedir?>images/07_tele1.gif" width="88" height="9"></td>
  </tr>
<tr>
<td background="<?=$usedir?>images/midbg.gif" align="center"><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>profile_blank.php"><img border="0" src="<?=$usedir?>images/profile.gif" width="64" height="27"></a>  <?php if ($int_get_permission != 1) { ?><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27">
  <a href="<?=$usedir?>VT_blank.php"><img border="0" src="<?=$usedir?>images/virtualterminal.gif" width="102" height="27"></a>  <?php } ?><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?><?=($_SESSION["sessionlogin_type"] != "tele"?"websetup_blank.php":"customerservice_blank.php") ?>"><img border="0" src="<?=$usedir?>images/cust_ser.gif" width="37" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><!--<a href="<?=$usedir?>shipping.php"><img border="0" src="<?=$usedir?>images/shipping.gif" width="49" height="27"></a><img alt="" border="0" src="<?=$usedir?>images/break.gif" width="22" height="27">--><a href="<?=$usedir?>ledger.php"><img border="0" src="<?=$usedir?>images/ledgers.gif" width="44" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>report.php?period=p"><img border="0" src="<?=$usedir?>images/transactions.gif" width="80" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href="<?=$usedir?>help.php"><img border="0" src="<?=$usedir?>images/helpgfc.gif" width="28" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"><a href=<?=$usedir?><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"logoutadmin.php":"logout.php"?>><img border="0" src="<?=$usedir?>images/logout.gif" width="45" height="27"></a><img border="0" src="<?=$usedir?>images/break.gif" width="22" height="27"></td>
</tr> 
</table>
<!--topmenu ends-->
<? }

} 
?>

<?php include("includes/displaytimer.php");

}	// Printable?
?>
