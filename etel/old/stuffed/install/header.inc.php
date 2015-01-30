<html>

<head>
<title><?=$PageTitle?></title>


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
function StepTo(Step, Dir)
{
	var oForm=	GetObj('InstallForm');
	oForm.action="<?=$_SERVER['PHP_SELF']?>?Step="+Step;
	if (Dir==1) oForm.action+="&Dir=1";
	oForm.submit();
}

function SetFocus()
{
	if (!document.forms[0].elements) return false;
	for (var i=0; i<document.forms[0].elements.length;i++) {
		if (document.forms[0].elements[i].type!="hidden") {
			document.forms[0].elements[i].focus();
			return true;
		}
	}
}

//-->
</SCRIPT>
<link rel="stylesheet" href="../skins/default/style.css">
</head>

<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" onload="SetFocus();<?=$AdditionalOnload?>">
<form ID="InstallForm" action="<?=$_SERVER['PHP_SELF']?>" method=post>
<?
if (is_array($_REQUEST)) {
	foreach($_REQUEST as $Key=>$Val) {
		echo "<input type=hidden name=\"$Key\" value=\"$Val\">\n";
	}
}
?>
<input type=hidden name=PrevStep value=<?=$Step?>>
<input type=hidden name=FormClicked value=1>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
<td width="100%" height="62">


<table border="0" cellpadding="0" cellspacing="0" width="100%" height="62">
<tr><td width="25%" height="62">

<p><img src="../skins/default/images/logo_01.gif" width="177" height="62" border="0"></p>
</td>
<td height=62 bgcolor="#707070" valign="bottom" width=20>
	<p><img src="../skins/default/images/corn_01.gif" width="20" height="13" border="0"></p>
</td>
<td width="50%" height="62" bgcolor="#707070" align=center>
<span style="color:#ffffff;font-size:16px;font-family:Arial;">
<?=$Lang['Step']?> <B><?=($Step+1)?></B> <?=$Lang['StepOf']?> <B><?=count($StepArr)?></B>
</span>

<?if ($Trial) {?>
	<br><span style="font-family:Arial;color:#ffffff;font-size:10px;"><?=$Lang['TrialVersion']?></span>
<?}?>
<? if ($LcP==3&&!$Trial) {?>
	<br><span style="font-family:Arial;color:#ffffff;font-size:10px;"><?=$Lang['AgencyVersion']?></span>
<?}?>
<? if ($LcP==2&&!$Trial) {?>
	<br><span style="font-family:Arial;color:#ffffff;font-size:10px;"><?=$Lang['MerchantVersion']?></span>
<?}?>
</td>
</tr>
</table>



</td>
</tr>






<tr><td style="padding:10px;">

<table width=100% cellpadding=0 cellspacing=0 border=0 height=100%>
<tr><td height=50 style="padding-left:10px;">
<span style="font-family:Arial;color:#777777;font-size:16px;"><?=$PageTitle?></span>
</td></tr>


<tr><td valign=top>
<?
if (count($Errors)>0) {

	for ($i=0;$i<count($Errors);$i++) {
		echo "<p class=GlobalErr style=\"padding-left:10px;\">".$Errors[$i]."</p>";
	}
}
if (count($Messages)>0) {

	for ($i=0;$i<count($Messages);$i++) {
		echo "<p class=GlobalMsg style=\"padding-left:10px;\">".$Messages[$i]."</p>";
	}
}

if (!$NoForm) {
	}
?>