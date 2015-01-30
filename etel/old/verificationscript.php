<?php
	include("includes/sessioncheck.php");
	
	include("includes/header.php");
	$headerInclude="blank";
	include("includes/topheader.php"); 
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
	
	$qry_select_user = "select *  from cs_companydetails where userid=$sessionlogin";
	
	if(!($show_sql =mysql_query($qry_select_user)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if($showval = mysql_fetch_array($show_sql)){ 
	
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="600" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
             <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
             <tr>
              <td width="100%" valign="middle" align="left" height="35" class="disctxhd">
                           &nbsp; Verification Script
                           </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
  	<form action="savescript.php" method="post" onsubmit="return validation()" name="FrmScript">
		<input type="hidden" name="company" value="<?=$sessionlogin?>">
              <table width="540" border="0" cellpadding="0"  class="mertd">
                <tr>
                    <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Name &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackagename" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[33]?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Product Service &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackageProduct" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[34]?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Package 
                                  Price &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtPackagePrice" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[35]?>"></td>
						</tr>
						
						<tr>
                         <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Refund 
                                  Policy &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><input name="txtRefundPolicy" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[36]?>"></td>
						</tr>
						
						<tr>
                        <td align="left" valign="middle" height="30" width="175"  bgcolor="#F8FAFC"><font face="verdana" size="1">Description 
                                  &nbsp;</font></td>
						<td align="left" height="30" width="225"  bgcolor="#F8FAFC"><textarea name="txtDescription" type="text" style="font-family:arial;font-size:10px;width:240px" rows="6"><?=$showval[37]?></textarea></td>
						</tr>
                <tr valign="middle"> 
                  <td align="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp; 
                    <input type="image" id="submitupload" src="images/continue.gif"></td>
                </tr>
              </table>
  </form>

              </td>
            </tr>
          </table>    </td>
     </tr>
</table>
<?php
}
	include("includes/footer.php");
?>