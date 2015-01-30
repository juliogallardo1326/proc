<?php
$str_transactiontype = (isset($HTTP_POST_VARS['mt_transaction_type'])?trim($HTTP_POST_VARS['mt_transaction_type']):"");
$trans_result = (isset($HTTP_POST_VARS['mt_transaction_result'])?trim($HTTP_POST_VARS['mt_transaction_result']):"");
$voice_result = (isset($HTTP_POST_VARS['mt_voiceauth_id'])?trim($HTTP_POST_VARS['mt_voiceauth_id']):"");
$i_totalamt = (isset($HTTP_POST_VARS['mt_total_amount'])?trim($HTTP_POST_VARS['mt_total_amount']):"");
$orderid = (isset($HTTP_POST_VARS['mt_order_id'])?trim($HTTP_POST_VARS['mt_order_id']):"");
$productdes = (isset($HTTP_POST_VARS['mt_product_desc'])?trim($HTTP_POST_VARS['mt_product_desc']):"");


if($trans_result=="SUC") {
$trans_result="Order number  entered.";
}else if($trans_result=="SUP") {
$trans_result="User suspended by the administrator";
}else if($trans_result=="INT") {
$trans_result="System processing error.";
}else if($trans_result=="VID") {
$trans_result="Voice authorization id already exist.";
}else if($trans_result=="UIN") {
$trans_result ="User not allowed to enter the order.";
}else if($trans_result=="DEC") {
$trans_result ="Transcation Declined.";
}

?>
<html>
<head>
<title>::Company Setup::</title>
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: black;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #CCCCCC 
}
</style>

</head>
<body topmargin="0" leftmargin="0" bgcolor="#EDF2E6"  marginheight="0" marginwidth="0">
<!--header-->
<table border="0" cellpadding="0" cellspacing="0" width="780" height="125" class="bdbtm" align="center">
<tr>
<td valign="middle" align="center" bgcolor="#ffffff" width="35%">&nbsp;</td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="images/top2.jpg" width="217" height="63"><img border="0" src="images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" src="images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="images/top5.jpg" width="217" height="63"><img border="0" src="images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
<tr>
<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">&nbsp;

</td>
</tr>
</table>
<!--submenu ends-->
<!--content part starts-->
<table border="0" cellpadding="0" cellspacing="0" width="780" align="center">
<tr>
<td width="180" align="left" valign="top" height="50">
<img border="0" src="images/leftside_tr.jpg" width="178" height="300">
</td>
<td width="600" align="center" valign="middle" bgcolor="#FFFFFF" height="50">
<table align="center" width="350" style="border:2px solid #d1d1d1;">
          <tr bgcolor="#78B6C2"> 
            <td align="left" height="15">&nbsp;&nbsp; Message</td>
          </tr>
			<tr><td height="60" valign="middle" align="center"><?=$trans_result;?></td></tr>
          <tr> 
            <td height="20" align="center" valign="middle"><a href="integrate3rdparty.htm"><img border="0" src="images/back.jpg"></a> </td>
          </tr>
      </table>
</td>
</tr>
</table>
<!--content parts ends-->
<!--footer-->
<table border="0" cellpadding="0" cellspacing="0" width="780" height="40" align="center">
<tr>
<td bgcolor="#000000" height="20" valign="middle" align="right">&nbsp;

</td>
</tr>
<tr>
<td height="1"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#7D9103" class="blackbd" height="19">&nbsp;</td>
</tr>
</table>
<!--footer-->

</body>

</html>	
