<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
<title>Tracker - <?=((ValidVar($MetaTitle))?$MetaTitle:strip_tags($PageTitle))?></title>
<link rel="stylesheet" href="<?=FileLink("style.css");?>">
<link rel="stylesheet" media="print" href="<?=FileLink("print.css");?>">
<link rel="shortcut icon" href="<?=FileLink("images/favicon.ico");?>" type="image/x-icon" />
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
	if (Obj.style.display=='')Obj.style.display='none';
	else Obj.style.display='';
}

</script> 

<?if ($nsProduct->SEND_USAGE) {?>
<script type="text/javascript" language="javascript">
var ns_amp=unescape('%26');
var ns_version='<?=$nsProduct->VERSION?>';
var ns_counter= new Image();
ns_counter.src="http://007.stuffedguys.com/tracker/track/default.html?v="+ns_version+ns_amp+"rn="+Math.random()+ns_amp+"ref="+escape(parent.document.referrer)+ns_amp+"cur="+escape(window.location.href);
</script>
<?}?>

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

</td>

<td width="50%" height="62">


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

<table width=100% cellpadding=0 cellspacing=0 border=0 class=TabsBorder height=27><tr><td>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="27" border="0"></p>
</td></tr></table>
	
			
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


