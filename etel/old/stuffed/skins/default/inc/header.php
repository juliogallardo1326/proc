<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
<title>Tracker - <?=((ValidVar($MetaTitle))?$MetaTitle:strip_tags($PageTitle))?></title>
<link rel="shortcut icon" href="<?=FileLink("images/favicon.ico")?>" type="image/x-icon" />
<link rel="stylesheet" href="<?=FileLink("style.css")?>">
<script language="JavaScript" src="<?=FileLink("cookies.js");?>"></script>
<script language="javascript"> 
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
	if (Obj.style.display=='') Obj.style.display='none';
	else Obj.style.display='';
}
function UserTZ() {
	var DefTZ=false;
	var d= new Date();
	if (d.getTimezoneOffset) DefTZ=-(d.getTimezoneOffset()/60);
	return DefTZ;
}

<?if ($nsUser->Logged()&&$nsProduct->Section!="error"&&$nsUser->AUTO_TZ) {?>
	var ReloadNeeded = (getCookie('<?=COOKIE_PFX?>auto_tz'))?false:true;
	Expires = new Date();
	Expires.setSeconds(Expires.getSeconds()+31536000);
	setCookie('<?=COOKIE_PFX?>auto_tz', UserTZ(), Expires, '/');
	if (ReloadNeeded) window.location.reload();
<?}?>

</script> 

<?if ($nsProduct->SEND_USAGE) {?>
<script type="text/javascript" language="javascript">
var ns_amp=unescape('%26');
var ns_version='<?=$nsProduct->VERSION?>';
var ns_counter= new Image();
ns_counter.src="http://007.stuffedguys.com/tracker/track/default.html?v="+ns_version+ns_amp+"rn="+Math.random()+ns_amp+"ref="+escape(parent.document.referrer)+ns_amp+"cur="+escape(window.location.href);
</script>
<?}?>

<?=ValidVar($AdditionalHead)?>

</head>



<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">




<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" height="62">

			

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="62">
<tr><td width="25%" height="62">


<p><?if (!$nsProduct->WHITE || !$nsProduct->WHITE_NO_LOGO) {?><img src="<?=FileLink("images/logo_01.gif");?>" width="177" height="62" border="0"><?}?>
<?if ($nsProduct->WHITE&&$nsProduct->WHITE_NO_LOGO&&$nsProduct->WHITE_LOGO) {?><IMG SRC="<?=$nsProduct->WHITE_LOGO?>" BORDER="0" ALT=""><?}?></p>
</td>
<td height=62>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="62"><tr>
<td width="20" valign="bottom" bgcolor="#707070">
<p><img src="<?=FileLink("images/corn_01.gif");?>" width="20" height="13" border="0"></p>
</td><td bgcolor="#707070" valign="top">
<?GetFORM("client_page", "", "admin", "ID=COMP_SEL_FORM");?>
<img src="<?=FileLink("images/0.gif");?>" width="1" height="27" border="0"><br>

<?if (is_array($CompArr)) {?>
<select name=CpId class=ClientSelect onchange="document.all.COMP_SEL_FORM.submit();">
<option class=CompSelect value="nocomp"></option>
<?
for ($i=0;$i<count($CompArr);$i++) {
	$RowName=htmlspecialchars(stripslashes($CompArr[$i]->NAME));
	if (strlen($RowName)>45) $RowName=substr_replace($RowName, "...", 42);
	echo "<option value=".$CompArr[$i]->ID."";
	if ($CompArr[$i]->ID==ValidVar($_COOKIE['CompId'])&&ValidVar($MenuSection)!="admin"&&ValidVar($MenuSection)!="my_tracker") echo " selected";
	echo " class=CompSelect>$RowName</option>";
}
?>
</select>
<?}?>
</form>
</td></tr></table>
</td>

<td width="50%" height="62" bgcolor="#707070">

<?if ($nsUser->Logged()) {?>

<?include $nsTemplate->Inc("inc/user_welcome");?>
<?}?>
<!---->			
</td>
</tr>
</table>			



</td>
</tr>
</table>
	

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" height="37">

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="37">
<tr><td width=25%>


<table border="0" cellpadding="0" cellspacing="0" width="100%" height="37"><tr>
<td width="51" class=TabsBorder>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
</td>
<td width="" class=TabsBorder>
<?if (ValidVar($CurrentCompany->ID)
		&&$nsUser->Logged()
		&&ValidVar($MenuSection)!="admin"
		&&ValidVar($MenuSection)!="my_tracker") {?>
<p class=CompName1><?=$CurrentCompany->NAME?></p>
<?} else {?>

<?if (ValidVar($MenuSection)=="admin") {?><p class=CompName1><?=$Lang['Administr']?></p><?}?>
<?if (ValidVar($MenuSection)=="my_tracker") {?><p class=CompName1><?=$Lang['MyTracker']?></p><?}?>
<?if (!ValidVar($MenuSection)) {?><p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p><?}?>

<?}?>
</td>
</tr></table>

</td>
<td valign=bottom>


<!-- Tabs menu -->			
<?if ($nsUser->Logged()&&ValidVar($MenuSection)!="admin"&&ValidVar($MenuSection)!="my_tracker") include $nsTemplate->Inc("inc/menu");
else {?>
<table width=100% cellpadding=0 cellspacing=0 border=0 class=TabsBorder height=27><tr><td>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="27" border="0"></p>
</td></tr></table>
<?}?>
<!--// Tabs menu -->			
			
</td>
<td width="20" height="27" class="TabsBorder">
<p><img src="<?=FileLink("images/0.gif");?>" width="20" height="1" border="0"></p>
</td>
</tr>
</table>
</td>
</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" height="10" valign="top">

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=58>
<p><img src="<?=FileLink("images/0.gif");?>" width="51" height="1" border="0"><img src="<?=FileLink("images/arrow_01.gif");?>" width="7" height="4" border="0"></p></td>


<td align=right valign=top>
<table width=314 cellpadding=0 cellspacing=0 border=0><tr><td width=314 valign=top>
<?if ($Consult->ShowHelpLink()) include "consult/".$nsLang->CurrentLang."/context_block.php"?>
</td></tr></table>
</td></tr></table>

</td>
</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" height=10>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height=10>
<tr>
<td width="51">
<p><img src="<?=FileLink("images/0.gif");?>" width="51" height="1" border="0"></p>
</td>


<?include $nsTemplate->Inc("inc/path");?>

<td width="20" valign="top">
<p><img src="<?=FileLink("images/0.gif");?>" width="20" height="1" border="0"></p>
</td>
</tr>
</table>
<img src="<?=FileLink("images/0.gif");?>" width="1" height="10" border="0">
</td>
</tr>
</table>





<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" valign="top" style="padding-left:41px;padding-right:20px;">


<?if ($Consult->ShowHelp()) {
	include $Consult->Header;
	include $Consult->Link;
	include $Consult->Footer;
}?>

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