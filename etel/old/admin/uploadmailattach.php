<html>
<head>
<title>File Uploading</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script language="JavaScript">
function funcValidate(objForm)
{
	if(objForm.fileUpload.value == ""){
		alert("Please select the file");
		return false;
	}else{
		return true;
	}	
		

}

</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="window.focus()">
<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100" align="center" valign="top"> 
<form name="frmUpload" method="post" enctype="multipart/form-data" action="uploadfilefb.php" onSubmit="return funcValidate(document.frmUpload)">
	    <table width="400" border="1" cellspacing="0" cellpadding="4">
          <tr bgcolor="#CCCCCC"> 
            <td colspan="2" align="center"><font size="2"  color="#FFFFFF" face="Verdana, Arial, Helvetica, sans-serif"><strong>Please 
              select the file to upload</strong></font></td>
        </tr>
        <tr> 
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr> 
          <td width="84" align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">File 
            Name</font></td>
          <td width="300"><input type="file" name="fileUpload"></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td><input type="submit" name="Submit" value="Upload"></td>
        </tr>
      </table>
      </form></td>
  </tr>
</table>
</body>
</html>
