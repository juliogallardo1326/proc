<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>NicheBill.com - Your Payment Processor</title>
<meta http-equiv="Content-Type" content="text/php; charset=iso-8859-1">
<link href="{$tempdir}styles/style.css" rel="stylesheet" type="text/css">
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$tempdir}/scripts/prototype.js"></script>
<script language="javascript" src="{$tempdir}/scripts/general.js"></script>
<script language="javascript" src="{$tempdir}/scripts/swfobject.js"></script>
<script language="javascript" src="{$tempdir}/scripts/src/scriptaculous.js"></script>
<script language="javascript">

if(is_in_frame()) window.top.location.href='https://www.nichebill.com';
var tempdir = '{$tempdir}';

</script>
</head>
<body leftmargin="0" topmargin="0"  marginheight="0" marginwidth="0" class="FrontPage">
<div style="position:relative; text-align:center; width:100%;" class="FrontContainer">
<table id="Table_01" width="802" height="600" border="0" cellpadding="0" cellspacing="0" style="margin-right:auto; margin-left:auto;">
	<tr>
		<td colspan="4">
			<img src="{$tempdir}images/frontpage/images/main_01.jpg" width="801" height="138" alt="">
            <div style="position:absolute; top:14px; margin-left:616px;">
				<a id="applynowimg" href="{$rootdir}/content.php?show=main_applynow" style="display:none;" onMouseOver="Effect.Pulsate(this,{ldelim}pulses:1, duration:.5{rdelim})"><img src="{$tempdir}images/frontpage/images/applynowfinal.png" alt=""></a>
            </div>
            <div style="position:absolute; top:102px; margin-left:430px; font-size:10px; text-align:right;">
            {$login_result}
            <form method="post" action="{$rootdir}/index.php">
                <input type="hidden" name="login_redir" value="{$_GET.login_redir}">
                Username: <input name="username" type="text" class="unnamed1" id="username">
               Password:  <input name="password" type="password" class="unnamed1" id="password">
                <input name="imageField" type="submit" border="0" value="Login">
            </form>
            </div>
            <script>Effect.Appear('applynowimg',{ldelim}duration:3{rdelim});</script>
            </td>
		<td>
			<img src="{$tempdir}images/frontpage/images/spacer.gif" width="1" height="138" alt=""></td>
	</tr>
	<tr>
		<td colspan="3">
			<img src="{$tempdir}images/frontpage/images/main_02.jpg" width="178" height="21" alt=""></td>
    <!--Header End -->