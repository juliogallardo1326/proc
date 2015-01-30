<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
<title>Tracker - <?=((ValidVar($MetaTitle))?$MetaTitle:strip_tags($PageTitle))?></title>
<link rel="stylesheet" href="<?=FileLink("style.css");?>">
<link rel="shortcut icon" href="<?=FileLink("images/favicon.ico");?>" type="image/x-icon" />


<SCRIPT LANGUAGE="JavaScript">
<!--
dom = (document.getElementById) ? true : false; 
ns5 = ((navigator.userAgent.indexOf("Gecko")>-1) && dom) ? true: false; 
ie5 = ((navigator.userAgent.indexOf("MSIE")>-1) && dom) ? true : false; 
ns4 = (document.layers && !dom) ? true : false; 
ie4 = (document.all && !dom) ? true : false; 
nodyn = (!ns5 && !ns4 && !ie4 && !ie5) ? true : false; 

function GetObj(id) { 
	return document.getElementById(id);
  return (ns4) ? document.layers[id] : (ie4) ? document.all[id] : (ie5||ns5) ? document.getElementById(id) : null; 
}
function ShowHide(Name) {
	Obj=GetObj(Name);
	if (Obj.style.display=='')Obj.style.display='none';
	else Obj.style.display='';
}


//-->
</SCRIPT>
</head>

<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" onload="<?=$AdditionalOnload?>">

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
<td width="100%" height="62">


<table border="0" cellpadding="0" cellspacing="0" width="100%" height="62">
<tr><td width="25%" height="62">

<p><a href="<?=getURL("default", "", "admin")?>"><?if (!$nsProduct->WHITE || !$nsProduct->WHITE_NO_LOGO) {?><img src="<?=FileLink("images/logo_01.gif");?>" width="177" height="62" border="0"><?}?>
<?if ($nsProduct->WHITE&&$nsProduct->WHITE_NO_LOGO&&$nsProduct->WHITE_LOGO) {?><IMG SRC="<?=$nsProduct->WHITE_LOGO?>" BORDER="0" ALT=""><?}?></a></p>
</td>
<td height=62 bgcolor="#707070" valign="bottom" width=20>
	<p><img src="<?=FileLink("images/corn_01.gif");?>" width="20" height="13" border="0"></p>
</td>
<td width="50%" height="62" bgcolor="#707070" align=center>
<span style="color:#ffffff;font-size:16px;font-family:Arial;">

<!-- title -->
<?=$PageTitle?>
</span>
<br><span  class=HeaderMenu><?=$Lang['CurVer'].$nsProduct->VERSION?>
 | <a href="<?=getURL("default", "", "admin")?>"><?=$Lang['BackToHome']?></a>
</span>


</td>
</tr>
</table>



</td>
</tr>






<tr><td style="padding:10px;">

<table width=100% cellpadding=0 cellspacing=0 border=0 height=100%>
<tr><td height=50 style="padding-left:10px;">
<span style="font-family:Arial;color:#777777;font-size:16px;"><?=$SubTitle?></span>
</td></tr>


<tr><td valign=top style="padding-left:10px;">
<?if ($Logs->HaveErr()) {?>
	<table width=100% cellpadding=0 cellspacing=0 border=0 class=ErrorTable><tr><td>
	<p><IMG SRC="<?=FileLink("images/icon_error.gif");?>" WIDTH="25" HEIGHT="32" BORDER="0" ALT=""></p>
	</td><td width=100%>
	<?for ($i=0;$i<count($Logs->Errors);$i++) {?>
		<p class=GlobalErr><?=$Logs->Errors[$i]?></p>
	<?}?>
	
	</td></tr></table><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">
	
<?}?>

<?if ($Logs->HaveMsg()) {?>
	<table width=100% cellpadding=0 cellspacing=0 border=0 class=MsgTable><tr><td>
	<p><IMG SRC="<?=FileLink("images/icon_message.gif");?>" WIDTH="25" HEIGHT="32" BORDER="0" ALT=""></p>
	</td><td width=100%>

	<?for ($i=0;$i<count($Logs->Messages);$i++) {?>
		<p class=GlobalMsg><?=$Logs->Messages[$i]?></p>
	<?}?>
	</td></tr></table><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?}?>

<?if ($Logs->HaveAlert()) {?>
	<table width=100% cellpadding=0 cellspacing=0 border=0 class=AlertTable><tr><td>
	<p><IMG SRC="<?=FileLink("images/icon_alert.gif");?>" WIDTH="25" HEIGHT="32" BORDER="0" ALT=""></p>
	</td><td width=100%>

	<?for ($i=0;$i<count($Logs->Alerts);$i++) {?>
		<p class=GlobalAlert><?=$Logs->Alerts[$i]?></p>
	<?}?>
	</td></tr></table><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?}?>