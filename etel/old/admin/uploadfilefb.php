<html>
<head>
<title>Attachment Uploaded</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script language="JavaScript">
function funcGiveValues()
{
	objForm = document.frmUpload;
	strValue = objForm.hdFile.value;
	if(strValue != ""){
		window.opener.funcAddValue(strValue);
	}
	

}
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="funcGiveValues()">
<form name="frmUpload" method="get" action="#">
<?php
	$str_error = "";
	extract($_FILES['fileUpload'], EXTR_PREFIX_ALL, 'uf');
	if(trim($uf_size) == "0")
	{
		$str_error .= "<li>Uploaded file is not a valid one</li>";
	}
	if($str_error == ""){
		//$svr = $_SERVER["PATH_TRANSLATED"];
		//$path_parts = pathinfo($svr); 
		//$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf_name;
		$str_current_path = "csv/";
		copy($uf_tmp_name,$str_current_path.$str_file_name); ?>
	<table border="1" cellpadding="4" cellspacing="0" align="center" width="100%">
	<tr>
    	<td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>File 
      	uploaded successfully</strong></font></td>
	</tr>
		<tr><td align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?= $str_file_name ?></font></td></tr>
		<tr>
			<td align="center">
				<input type="button" value="Upload Another" onClick="window.location='uploadmailattach.php'">
				<input type="hidden" name="hdFile" value="<?=$str_file_name?>">
				&nbsp;<input type="button" value="Close" onClick="window.close()">	
			</td></tr>
	</table>
<?php
	}else{ ?>
	<table border="1" cellpadding="4" cellspacing="0" align="center" width="100%">
	<tr>
    	<td height="25" align="center" valign="middle" bgcolor="#CCCCCC"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>Error - while uploading file</strong></font></td>
	</tr>
	<tr>
		<td align="center"><input type="button" value="Upload Another" onClick="window.location='uploadmailattach.php'">&nbsp;<input type="button" value="Close" onClick="window.close()"></td>
	</tr>
	<input type="hidden" name="hdFile" value="<?=$str_file_name?>">
	</table>
	
	
	
<?php
	}	
?>
</form>
</body>
</html>
