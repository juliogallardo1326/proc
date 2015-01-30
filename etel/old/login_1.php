<?php session_start();
$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
$customerservice = (isset($HTTP_POST_VARS['cb_customerservice'])?Trim($HTTP_POST_VARS['cb_customerservice']):"");
$invalidlogin = "";
if($password){
'includes/dbconnection.php';
$username = strtolower($username); 
	if($username == "admin") {
		$show_sql =mysql_query("select username  from cs_login where username='$username' and password='$password'",$cnn_cs);
		if(mysql_num_rows($show_sql) >0) {
			session_register("sessionAdmin");		         
			$_SESSION["sessionAdmin"] = "yes";		
			header("location:admin/blank.php");
			exit();
		}else {
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($username =="service"){
		$show_sql =mysql_query("select username  from cs_login where username='$username' and password='$password'",$cnn_cs);
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionService");
			$_SESSION["sessionService"] = "logged";
			header("location:service/customerservice.php");
			exit();
		} else {
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($customerservice == 1){
		$show_sql =mysql_query("select username  from cs_customerserviceusers where username='$username' and password='$password'",$cnn_cs);
		if($show_val = mysql_fetch_array($show_sql)) {
			// session_register("sessionAdmin");
			session_register("sessionServiceUser");
		//	$_SESSION["sessionAdmin"] = "yes";
			$_SESSION["sessionServiceUser"] = $username;
			header("location:service/customerservice.php");
			exit();
		} else {
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($username !=""){
		$show_sql =mysql_query("select userId,username  from cs_companydetails where username='$username' and password='$password'");
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionlogin");
			$_SESSION["sessionlogin"] = $show_val[0];
			header("location:blank.php");
			exit();
		} else {
			$show_user_sql =mysql_query("select userid,username  from cs_companyusers where username='$username' and password='$password'");
			if($show_user_val = mysql_fetch_array($show_user_sql)) {
				session_register("sessionCompanyUser");
				$_SESSION["sessionCompanyUser"] = $show_user_val[0];
				header("location:home.php");
				exit();
			} else {
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
		}
	} 
}?>
<html>
<head>
<title>::Company Setup::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="Styles/text.css" rel="stylesheet">
<script language="javascript">
function validation(){
  if(document.Frmlogin.username.value==""){
    alert("Please enter username")
    document.Frmlogin.username.focus();	
  }
  else if(document.Frmlogin.password.value==""){
    alert("Please enter password")
    document.Frmlogin.password.focus();	
  }
  else{
    document.Frmlogin.submit();
  }
}
</script>
</head>
<body topmargin="0" leftmargin="0" bgcolor="#EDF2E6"  marginheight="0" marginwidth="0">
<form action="index.php" method="post"  name="Frmlogin" onsubmit="javascript:validation()">
<!--header-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="middle" align="center" bgcolor="#ffffff" width="35%"><img src="images/logo.jpg" width="180" height="46" border="0" alt=""></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="images/top2.jpg" width="217" height="63"><img border="0" src="images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" src="images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="images/top5.jpg" width="217" height="63"><img border="0" src="images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
<table width="400" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td width="100%" height="20" valign="middle" align="center"></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<!--content part starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="63%">
<tr>
<td width="100%" align="center" valign="middle" bgcolor="white">
<!---login portion starts-->
      <table width="550" height="144" border="0" cellspacing="0" cellpadding="0">
        <tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="left" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Login</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td height="144" class="lgnbd" colspan="5" align="left">
 <table border="0" cellpadding="0" cellspacing="0" width="100%" height="63">
              <tr>
              <td width="30%" rowspan="6" valign="top" align="left"><img src="images/loginbg.jpg" alt="" border="0" width="272" height="196"></td>
                <td width="70%" height="41" valign="middle" align="left" colspan="2">
                  <p align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Access to the<b> <font color="#000080">Site </font></b>requires
                  a User ID and Password</font></td>
              </tr>
              <tr>
                <td width="30%" valign="middle" align="center" height="1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><strong>User
                  ID:</strong></font></td>
                <td width="40%" valign="middle" align="left" height="1"><input name="username" type="text" style="font-family:arial;font-size:10px;width:120px" size="15" maxlength="30"></td>
              </tr>
              <tr>
                <td width="30%" valign="middle" align="center" height="1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><strong>Password:</strong></font></td>
                <td width="40%" valign="middle" align="left" height="1"><input type="password" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:120px"></td>
              </tr>
			  <tr>
                <td width="30%" valign="middle" align="left" height="1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><strong>&nbsp;</strong></font></td>
                <td width="40%" valign="middle" align="left" height="1"><input name="cb_customerservice" type="checkbox" value="1"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Customer service</font></td>
              </tr>
              <tr>
                <td width="30%" valign="middle" align="center" height="30">&nbsp;</td>
                <td width="40%" valign="middle" align="center" height="30"><input type="image" src="images/login.jpg"></td>
              </tr>
              <tr>
                <td width="70%" valign="middle" align="left" height="20" colspan="2"><A href="forgotpassword.php" class="tlk">Forgot Password ?</a></td>
              </tr>
            </table>
</td>
</tr>
<tr>
<td width="1%"><img src="images/menubtmleft.gif" width="10" height="11"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif" width="10" height="11"></td>
</tr>
</table>

<!--login portion ends-->
</td>
</tr>
</table>
<!--content parts ends-->
<!--footer-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="40">
<tr>
<td bgcolor="#000000" height="20" valign="middle" align="right">
<table border="0" cellpadding="0" cellspacing="0" width="282" height="20">
<tr>
<td width="14" align="left"><img alt="" border="0" src="images/arrow.gif" width="14" height="14"></td>
<td width="60" align="center"><a href="#" class="btmlink">Home</a></td>
<td width="14" align="left"><img alt="" border="0" src="images/arrow.gif" width="14" height="14"></td>
<td width="100" align="center"><a href="#" class="btmlink">Privacy Policy</a></td>
<td width="14" align="left"><img alt="" border="0" src="images/arrow.gif" width="14" height="14"></td>
<td width="80" align="center"><a href="#" class="btmlink">Contact us</a></td>
</tr>
</table>
</td>
</tr>
<tr>
<td height="1"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#7D9103" class="blackbd" height="19">&nbsp;</td>
</tr>
</table>
<!--footer-->
</form>
</body>

</html>	
