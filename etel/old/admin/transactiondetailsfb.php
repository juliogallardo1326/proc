<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
?>
<?php 
$str_fromdate = isset($HTTP_POST_VARS["txtDate"])?$HTTP_POST_VARS["txtDate"]:"";
$str_todate = isset($HTTP_POST_VARS["txtDate1"])?$HTTP_POST_VARS["txtDate1"]:"";
$i_userid = isset($HTTP_POST_VARS["opt_company"])?$HTTP_POST_VARS["opt_company"]:"";
$i_count = isset($HTTP_POST_VARS["hid_count"])?$HTTP_POST_VARS["hid_count"]:"0";
if ($i_count != 0)
{
	for($i=0;$i<$i_count;$i++)
	{
		$str_passstatus = isset($HTTP_POST_VARS["rd_passstatus_".$i])?$HTTP_POST_VARS["rd_passstatus_".$i]:"PE";
		$i_transactionid = isset($HTTP_POST_VARS["hid_transactionid_".$i])?$HTTP_POST_VARS["hid_transactionid_".$i]:"0";
		if ($str_passstatus != "" && $i_transactionid !="")
		{
			$qry_update = "Update cs_transactiondetails set passStatus = '".$str_passstatus."' where transactionId = ".$i_transactionid ;
			if (!mysql_query($qry_update,$cnn_cs))
			{
				echo "cannot execute query<br>". $qry_update;
				exit();
			}
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled</title>
</head>
<body onload='func_submit();'>
<form name=frm_form method="post" action="transactiondetails.php">
	<input type="hidden" name="txtDate" value="<?php print $str_fromdate; ?>">
	<input type="hidden" name="txtDate1" value="<?php print $str_todate; ?>">
	<input type="hidden" name="opt_company" value="<?php print $i_userid; ?>">
</form>
</body>
</html>
<script language="javascript">
 function func_submit()
 {
	document.frm_form.submit();
 }	
</script> 