<html>
<head>
<title>INVOICE</title>
<style>
.blueheader{font-family:verdana;font-size:20px;color:#00CCDF;font-weight:bold}
.bluetext{font-family:verdana;font-size:12px;color:#00CCDF;font-weight:bold}
.orangetext{font-family:verdana;font-size:12px;color:#FF6600;font-weight:bold}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php


?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.ledger;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
</script>
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
	</table><br>
<?php
$i_from_day = date("d");
$i_from_month = date("m")-1;
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$str_companyname = "";
$iUserId = isset($HTTP_GET_VARS["id"])?quote_smart($HTTP_GET_VARS["id"]):"";
if ($iUserId != "" and is_numeric($iUserId))
{
	$qry_selectdetails = "Select companyname from cs_companydetails where userId=$iUserId";
	$rst_selectdetails = mysql_query($qry_selectdetails,$cnn_cs);
	if (mysql_num_rows($rst_selectdetails)>0)
	{
		$str_companyname = mysql_result($rst_selectdetails,0,0);
	}
?>	<form name="ledger" action="view_invoicedetails.php" method="POST">
	<input type="hidden" name="hid_id" value="<?=$iUserId?>">
	<table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<br>
	   <tr>
		  <td height="30" valign="middle" align="right" width="25%"><font face="verdana" size="2"><strong>Company Name :</strong></font></td>
		  <td align="left" width="75%"  height="30" >&nbsp;<font face="verdana" size="2"><strong><?=$str_companyname?></strong></font></td>
		</tr>
	   <tr>
		  <td height="30" valign="middle" align="right"><font face="verdana" size="1">Start date :</font></td>
		  <td align="left" height="30" >&nbsp;
		<!--	 <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;"> -->
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select></font>
			 <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		   <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(340,235,document.ledger.from_date)">
		   </td>
		</tr>
        <tr>
		  <td   height="30" valign="middle" align="right"><font face="verdana" size="1">End date :</font></td>
		  <td align="left" height="30">&nbsp;
		<!--	<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()"> -->
			  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
			  </select>
			<select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
			  </select>
			  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
			  </select>
			  <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
			  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(340,260,document.ledger.from_to)">
		 </td>   
        </tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="image" id="reportview" SRC="<?=$tmpl_dir?>/images/view.jpg"></td>
		</tr>
	</table>
	</form>		
<?php
} ?>
</body>
</html>
