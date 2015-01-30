<html>
<head>
<title>Wire Instructions</title>
<style>
.blueheader{font-family:verdana;font-size:20px;color:#00CCDF;font-weight:bold}
.bluetext{font-family:verdana;font-size:12px;color:#00CCDF;font-weight:bold}
.orangetext{font-family:verdana;font-size:12px;color:#FF6600;font-weight:bold}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php

$str_bankname = "";
$str_bankaddress = "";
$str_bankcountry = "";
$str_banksortcode = "";
$str_bankaccno = "";
$str_bankswiftcode = "";
$str_bankbenname = "";
$str_bankaccname = "";
$iUserId = isset($HTTP_GET_VARS["id"])?quote_smart($HTTP_GET_VARS["id"]):"";
if ($iUserId != "" and is_numeric($iUserId))
{
	$qry_selectdetails = "Select company_bank,other_company_bank,bank_address,bank_country,bank_sort_code,bank_account_number,bank_swift_code,beneficiary_name,bank_account_name from cs_companydetails where userId=$iUserId";
	$rst_selectdetails = mysql_query($qry_selectdetails,$cnn_cs);
	if (mysql_num_rows($rst_selectdetails)>0)
	{
		$str_bankname = mysql_result($rst_selectdetails,0,0);
		if ($str_bankname == ""){
			$str_bankname = mysql_result($rst_selectdetails,0,1);
		}
		$str_bankaddress = mysql_result($rst_selectdetails,0,2);
		$str_bankcountry = mysql_result($rst_selectdetails,0,3);
		$str_banksortcode = mysql_result($rst_selectdetails,0,4);
		$str_bankaccno = mysql_result($rst_selectdetails,0,5);
		$str_bankswiftcode = mysql_result($rst_selectdetails,0,6);
		$str_bankbenname = mysql_result($rst_selectdetails,0,7);
		$str_bankaccname = mysql_result($rst_selectdetails,0,8);
	}
?>
	<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="center" class="blueheader">Wire Instructions</td>
		<td align="right"><img SRC="<?=$tmpl_dir?>/images/logo2os_L.gif" border="0"></td>
	</tr>
	</table><br>
	<table align="center" border="0">
	<tr>
		<td align="left" class="bluetext" valign="top"  width="40%">Receiver Bank:</td>
		<td align="left" class="orangetext" width="60%"><?=$str_bankname?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Address:</td>
		<td align="left" class="orangetext"><?=$str_bankaddress?><br><?=$str_bankcountry?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >SWIFT Code:</td>
		<td align="left" class="orangetext"><?=$str_bankswiftcode?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >ABA Number:</td>
		<td align="left" class="orangetext">&nbsp;</td>
	</tr>
	<tr>
		<td align="right" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Beneficiary Bank:</td>
		<td align="left" class="orangetext"><?=$str_bankname?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Account Number:</td>
		<td align="left" class="orangetext"><?=$str_bankaccno?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >SWIFT Code:</td>
		<td align="left" class="orangetext"><?=$str_bankswiftcode?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Address:</td>
		<td align="left" class="orangetext"><?=$str_bankaddress?><br><?=$str_bankcountry?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Beneficiary:</td>
		<td align="left" class="orangetext"><?=$str_bankbenname?>&nbsp;</td>
	</tr>
	<tr>
		<td align="left" class="bluetext" valign="top" >Account Number:</td>
		<td align="left" class="orangetext"><?=$str_bankaccno?>&nbsp;</td>
	</tr>
	<tr>
		<td align="right" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td align="right" colspan="2"><span class="bluetext">[</span><a href="javascript:window.close();" class="orangetext">Close</a><span class="bluetext">]</span></td>
	</tr>
	</table>
<?php
} ?>
</body>
</html>
