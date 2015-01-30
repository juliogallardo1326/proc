<?php session_start();
require_once("includes/function.php");
$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
$user_type = (isset($HTTP_POST_VARS['usertype'])?Trim($HTTP_POST_VARS['usertype']):"");
$securityno = (isset($HTTP_POST_VARS['securitycode'])?Trim($HTTP_POST_VARS['securitycode']):"");
$securityno_original = (isset($HTTP_POST_VARS['securitycode_original'])?Trim($HTTP_POST_VARS['securitycode_original']):"");
$invalidlogin = "";
$activitytype="";
if($password != "" && $username !="" && $securityno_original!=""){// && $securityno_original==$securityno){
session_destroy();
session_start();
$username = strtolower($username);
	if($username == "demo") {header("location:Demo/index.php"); exit();}
require_once("includes/dbconnection.php");

general_login($username,$password,$user_type,3);

}
?>
<html>
<head>
<title>etelegate.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
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
                      <td width="34%" height="60" align="center"><a href="ecommerce.php"><img src="images_new/ecommerce_merchantes.gif" width="84" height="32" border="0"></a></td>
                      <td width="33%" align="center"><a href="tele.php"><img src="images_new/tele_sales.gif" width="82" height="32" border="0"></a></td>
                      <td width="33%" align="center"><a href="gateway.php"><img src="images_new/gateway.gif" width="68" height="32" border="0"></a></td>
                    </tr>
                    <tr bgcolor="#008FEA">
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr align="left" valign="top" bgcolor="#E5F4FD">
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td height="120" align="left" valign="top">
                              <ul>
							  	<li>Online Sales<br></li><br>
                                <li>128 Bit encryption<br></li><br>
                                <li>Sell with your merchant account or ours</li>
                              </ul>
                              </td>
                          </tr>
                          <tr>
                            <td align="left" valign="top"><div align="center"><a href="Demo/ecommercedemo.htm"><img src="images_new/click_here_02.gif" width="76" height="24" border="0"></a></div></td>
                          </tr>
                        </table></td>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td height="120" align="left" valign="top">
                              <ul>
							  	<li>Compliance<br></li><br>
                                <li>Security<br></li><br>
                                <li> Privacy</li>
                              </ul></td>
                          </tr>
                          <!--<tr>
                            <td align="left" valign="top"><div align="center"><a href="Demo/teledemo.htm"><img src="images_new/click_here_02.gif" width="76" height="24" border="0"></a></div></td>
                          </tr>-->
                        </table></td>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td height="120" align="left" valign="top">
                              <ul>
							  	<li> Private Label Solutions<br></li><br>
                                <li> Account Management<br></li><br>
                                <li> Custom Solutions</li>
                              </ul></td>
                          </tr>
                          <tr>
                            <td align="left" valign="top"><div align="center"><a href="gateway.php"><img src="images_new/click_here_02.gif" width="76" height="24" border="0"></a></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr bgcolor="#008FEA">
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr bgcolor="#E5F4FD">
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td><div align="justify">As truly one of the most
                                unique international payment processors, <strong>Etelegate.com</strong>
                                is a worldwide leader in Online Payment Processing.
                                We specialize in issuing merchant accounts and
                                processing online transactions for both Internet
                                and traditional businesses around the globe.
                                <p>We offer banks, processors, re-sellers, payment
                                  gateways, call centers, and investors a turnkey,
                                  state-of-the-art gateway interface to manage
                                  their business with minimal time and effort
                                  while providing an arsenal of fraud detection
                                  and prevention technologies.</p>
                                <p>When you decide to process with us, you are
                                  taking the first step to a successful processing
                                  relationship. Unlike any other payment processor,
                                  we understand the bottom line, and what it takes
                                  to successfully process payment transactions.
                                </p>
                                <p>Please click one of the following options listed
                                  above for a more in-depth look at what Etelegate.com;
                                  can do for your business.<br>
                                </p>
                              </div></td>
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
<?php
function func_get_gatewayLogo($gateway_id) {
	if($gateway_id !="") {
		$str_logo_qry = "select a.companyname, b.logo_filename from cs_companydetails a, cs_logo b where  a.userId = b.Logo_company_id and b.Logo_company_id ='$gateway_id'";
		$rst_select_logo = mysql_query($str_logo_qry);
		if(mysql_num_rows($rst_select_logo)>0) {
			$str_gateway_name = mysql_result($rst_select_logo,0,0);
			$str_logo_name = mysql_result($rst_select_logo,0,1);
		} else {
			$str_gateway_name = "";
			$str_logo_name = "";
		}
	} else {
		$str_logo_name = "";
	}
	return $str_logo_name;
}

?>