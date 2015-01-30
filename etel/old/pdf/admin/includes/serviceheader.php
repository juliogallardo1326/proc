<?php	
	$CompanyLogoName = isset($HTTP_SESSION_VARS["sessionCompanyLogoName"])?quote_smart($HTTP_SESSION_VARS["sessionCompanyLogoName"]):"";
?>
<html>
<head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<!--header-->
<div id="testMode" style="position:absolute;width:150px;height:40px;z-index:0; overflow: hidden;visibility:visible;left:10;top:85">
<table><tr>
<td><span style="font-face:verdana;font-weight:bold;Color:#006633;"><?=$_SESSION["sessionactivity_type"]?></span></td>
</tr></table>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%">&nbsp;<img alt='' border='0' src='<?=$CompanyLogoName?>'></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top1.jpg" width="238" height="63" ><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top2.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top4.jpg" width="238" height="63"><img  alt="" border="0" SRC="<?=$tmpl_dir?>/images/top5.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
<td height="9" align="center" background="../images/menutopbg.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/01_ser1.gif" width="89" height="9"></td>
</tr>
<td background="../images/midbg.gif" align="center"><img border="0" SRC="<?=$tmpl_dir?>/images/break.gif" width="22" height="27"><a href=<?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"../logoutadmin.php":"../logout.php"?>><img border="0" SRC="<?=$tmpl_dir?>/images/logout.gif" width="45" height="27"></a><img border="0" SRC="<?=$tmpl_dir?>/images/break.gif" width="22" height="27"></td>
</tr> 
</table>
<!--topmenu ends-->
<?php include("../includes/displaytimer.php");?>