<?php
$return_val ="";
$msg="";
$message ="";
$act="";
if($act=="mail")
{
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$headers .= "To: <$help_email>\r\n";
			$headers .= "From: $contact_name <$contact_email>\r\n";
			mail($help_email, $subject, $msg, $headers);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>etelegate.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script>
function show_page(op)
{
	if(op==6)
	{
		location.href='https://www.etelegate.net';
	}
}
</script>


</head>

<body onLoad="MM_preloadImages('images_new/apply_now_r.gif','images_new/services_r.gif','images_new/contact_us_r.gif','images_new/demos_r.gif','images_new/home_r.gif')" >
<script language="javascript" src="scripts/sublink.js"></script>
<?php include "top.php"; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="214"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="images_new/have_an_account.gif" width="214" height="27"></td>
        </tr>
      </table></td>
    <td width="5"><img src="images_new/transparent.gif" width="5" height="1"></td>
    <td colspan="3" align="left" valign="top" width="100%"><?php include "menu.php"; ?></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="214" align="left" valign="top"><?php include "login.php"; ?>&nbsp;</td>
    <td width="5"><img src="images_new/transparent.gif" width="5" height="1"></td>
    <td width="70%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#008FEA"><table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tr bgcolor="#E5F4FD"> 
                      <td width="100%" bgcolor="#E5F4FD">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                         <tr>
						 <td>
						 <table cellpadding = "0" cellspacing="0" border="0" width="100%">
						 <tr>
						 <td><strong> Services</strong></td>
						 <td>
						 <table cellpadding = "0" cellspacing="0" border="0" width="42%" align="right">
						 <tr>
						 <td align="right" width="50" valign="middle">AIM </td>
						 <td width="25">&nbsp;<img src="images/aol.jpg"></td>
						 <td align="left">: etelegate</td>
						 </tr>
						 <tr>
						 <td align="right">ICQ</td>
						 <td>&nbsp;<img src="images/icq.gif"></td>
						 <td align="left">: 343413391</td>
						 </tr> 
						 </table>
						 </td>
						 </tr>
						 </table>					 
						 </td>
						 </tr>
                          <tr> 
                            <td> 
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td bgcolor="#FFFFFF">
									<table width="100%" border="0" cellspacing="1" cellpadding="3" height="300">
                      					<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Private offshore bank account Enquiry">Private offshore bank account</a></td></tr>
                      					<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Offshore Credit/Debit Card Enquiry">Offshore Credit/Debit Card</a></td></tr>										
                      					<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Offshore Web Hosting Enquiry">Offshore Web Hosting</a></td></tr>
                      					<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Offshore Corporation Enquiry">Offshore Corporation</a></td></tr>
                      					<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Offshore Mail Drop Enquiry">Offshore Mail Drop</a></td></tr>
										<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Satellite Phone Enquiry">Satellite Phone</a></td></tr>
										<tr><td height="30" bgcolor="#E5F4FD">&nbsp;<a href="mailto:sales@etelegate.com?subject=Virtual office Enquiry">Virtual Office</a></td></tr>										
                                     </table></td>
                                  </tr>
                               </table>
                              </td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="5"><img src="images_new/transparent.gif" width="5" height="1"></td>
    <td width="30%" align="left" valign="top"><?php include "news.php"; ?>&nbsp;</td>
  </tr>
</table>
<?php include "bottom.php"; ?>
</body>
</html>