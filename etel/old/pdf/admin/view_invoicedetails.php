<html>
<head>
<title>INVOICE</title>
<style>
.blueheader{font-family:verdana;font-size:20px;color:#00CCDF;font-weight:bold}
.bluetext{font-family:verdana;font-size:12px;color:#00CCDF;font-weight:bold}
.orangetext{font-family:verdana;font-size:12px;color:#FF6600;font-weight:bold}
</style>
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php
include("../includes/function1.php");

?>
	<table align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left"><img SRC="<?=$tmpl_dir?>/images/invoicelogo.jpg" border="0"></td>
		<td align="right">
			<table>
			<tr>
				<td>
				<font face="verdana" size="1">
				ETELEGATE PAYMENT PROCESSING<br>
				CARIOCCA BUSINESS PARK<br>
				2 SAWLEY ROAD<br>
				MANCHESTER, ENGLAND M4O 8BB<br>
				1-866-OFFSHORE<br>
				</font>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><font face="verdana" size="4"><strong>STATEMENT OVERVIEW</strong></font></td>
	</tr>
	</table>
<?php
$str_companyname = "";
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$iUserId = isset($HTTP_POST_VARS["hid_id"])?quote_smart($HTTP_POST_VARS["hid_id"]):"";
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

if ($iUserId != "" and is_numeric($iUserId))
{
	$qry_selectdetails = "Select companyname from cs_companydetails where userId=$iUserId";
	$rst_selectdetails = mysql_query($qry_selectdetails,$cnn_cs);
	if (mysql_num_rows($rst_selectdetails)>0)
	{
		$str_companyname = mysql_result($rst_selectdetails,0,0);
	}
	
	$querystr="SELECT checkorcard, STATUS , cancelstatus, companyname, chargeback, credit, discountrate, transactionfee, reserve, accounttype, cardtype,reason,passstatus,amount,voiceauthfee FROM cs_transactiondetails, cs_companydetails WHERE ";
	$querystr1="cs_transactiondetails.userid = cs_companydetails.userid and transactionDate >= '$dateToEnter' and transactionDate <= '$dateToEnter1' and checkorcard='H'"; // GROUP BY checkorcard, STATUS , cancelstatus ORDER BY checkorcard"; 
	$str_query=$querystr." cs_transactiondetails.userid=$iUserId and $querystr1";
	$qrt_voice_select =" select count(*) from cs_voice_system_upload_log where user_id=$iUserId and upload_date_time >= '$dateToEnter' and upload_date_time <= '$dateToEnter1'";
	$crorcq="A";
	$str_type="A";
	func_show_invoicedetails_details($str_query,$cnn_cs,$crorcq,$str_type,$iUserId,$qrt_voice_select);
	
?>
	<BR>	
	<table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<tr>
		<td align="left"><font face="verdana" size="1">Etelegate.com VALUES YOUR BUSINESS CALL 1.866.OFFSHORE WITH ANY QUESTIONS RELATED TO YOUR MERCHANT ACCOUNT</font></td>
	</tr>
	<tr>
		<td align="right"><font face="verdana" size="1"><?php print date("d/m/Y h:i:s A");?></font></td>
	</tr>
	<tr>
		<td align="right">&nbsp;</td>
	</tr>
	<tr>
		<td align="right"><span class="bluetext">[</span><a href="javascript:window.location='view_invoice.php?id=<?=$iUserId?>';" class="orangetext">Back</a><span class="bluetext">]</span>&nbsp;&nbsp;<span class="bluetext">[</span><a href="javascript:window.close();" class="orangetext">Close</a><span class="bluetext">]</span></td>
	</tr>
	</table>
<?php
} ?>
</body>
</html>
