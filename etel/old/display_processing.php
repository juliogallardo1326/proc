<?php
		$strMessage = "<center><br><img src='images/progressbar.gif'><br><h3>Please wait. Transaction in Progress....</center>";

?>

<html>
<head>
<title>Volpay display result</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<script type="text/javascript">
function func_close()
{
window.opener = top;
window.setTimeout("window.close()",10*1000);

//window.close();
}
</script>
</head>
<body onLoad="func_close()">
<table border="0" cellpadding="0" width="50%" cellspacing="0" height="50%" align="center" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="70%" class="disbd">
		
		 <tr>
		  <td width="100%" valign="top" align="center"  height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="middle" align="left" height="35" class="disctxhd">&nbsp; Message</td>
		</tr>
		<tr>
		 <td width="100%" valign="top" align="center">
		 <table width="500" border="0" cellpadding="0"  height="150" >
		  <tr><td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx"><?=$strMessage?></span></td></tr>
		  <tr><td><br><br> Please click here to <a href="javascript:window.close()">close</a> the window</td></tr>
		  </table>
		  </td>
		</tr>
        </table>
		</td>
     </tr>
</table>
</body>
</html>
