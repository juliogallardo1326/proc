<?php
	include("../includes/sessioncheckserviceuser.php");
	
	require_once("../includes/function.php");
?>	
<html>
<head>
<title>Call Back Setting</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#F2F2F2">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" height="100%">
  <tr>
    <td align="center">
	<?php
			$iTransactionId = (isset($HTTP_GET_VARS["id"])?Trim($HTTP_GET_VARS["id"]):"");
			if($iTransactionId != "")
			{
				$qryGetUser = "select userid from cs_transactiondetails where transactionId =".$iTransactionId;
				$iUserId = 	funcGetValueByQuery($qryGetUser,$cnn_cs);
				$qryGetUserName = "select  companyname from cs_companydetails where userId =".$iUserId;
				$iUserName = funcGetValueByQuery($qryGetUserName,$cnn_cs);
				$strCurrentDateTime = func_get_current_date_time();
				$qryInsert = "insert into cs_callback (userid,transactionid,dateandtime) values (";
				$qryInsert .= "'$iUserId','$iTransactionId','$strCurrentDateTime')";
				if(!mysql_query($qryInsert))
				{
					print("Can not execute query");
					exit();
				} ?>	
				<font face="Verdana, Arial, Helvetica, sans-serif" size="2">A call back message is set for <?= $iUserName ?></font>
				<br><br>
				<input type="button" value="Close" onClick="window.close();">
<?php			}		
	?>			
	</td>
  </tr>
</table>
</body>
</html>
