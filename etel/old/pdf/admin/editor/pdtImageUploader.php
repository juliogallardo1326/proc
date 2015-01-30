
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link REL='stylesheet' TYPE='text/css' HREF='file:///G|/barnclaybarnwood/ExtranetBarclayBarnwoodCoUk/mysheepdog/webmail.css'>

</head>
<body onLoad="window.focus()">
	<form action="pdtFilePost.php" name="uploaderfrm" enctype="multipart/form-data" method="post">
  <table width="100%" border="0" cellspacing="3" cellpadding="2" align="Center">
    <tr > 
      <td align="center" colspan="2"><font face="verdana" size="2"><b>Image Uploader</b></font></td>
    </tr>
    <tr> 
      <td align="right" valign="top" >&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="middle"><font face="verdana" size="2">Select an Image : &nbsp;</font></td>
      <td align="left" valign="middle" >&nbsp;<input name="imgfile" type="file" style="width:200px" class=TextBox>&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;
	  </td>
    </tr>
    <tr> 
      <td align="right" valign="top"></td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr> 
      <td align="right" valign="top" class="blacktext"><a href="#" onclick="subPage()">Upload</a>
</td>
	  <td align="left">&nbsp; &nbsp; <a href="#" onClick="window.opener.focus();window.close()">Close</a> 
      </td>
    </tr>
  </table>
	</form>
</body>
</html> 
<script>
function subPage() {
	if(document.uploaderfrm.imgfile.value =="") {
		alert("Please browse an image");
		return false;
	}
	document.uploaderfrm.submit();
	return true;
}
</script>
