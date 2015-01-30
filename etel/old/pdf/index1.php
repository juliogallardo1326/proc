<?php session_start();

$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
$password = (isset($HTTP_POST_VARS['password'])?Trim($HTTP_POST_VARS['password']):"");
$user_type = (isset($HTTP_POST_VARS['usertype'])?Trim($HTTP_POST_VARS['usertype']):"");
$securityno = (isset($HTTP_POST_VARS['securitycode'])?Trim($HTTP_POST_VARS['securitycode']):"");
$securityno_original = (isset($HTTP_POST_VARS['securitycode_original'])?Trim($HTTP_POST_VARS['securitycode_original']):"");
$invalidlogin = "";
$activitytype="";
if($password != "" && $username !="" && $securityno!="" && $securityno_original!="" && $securityno_original==$securityno){
require_once("includes/dbconnection.php");
$username = strtolower($username); 
	if($username == "admin") {
		$show_sql =mysql_query("select username  from cs_login where username='$username' and binary password = '$password'",$cnn_cs);
		if(mysql_num_rows($show_sql) >0) {
			session_register("sessionAdmin");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "InAdmin";		
			$_SESSION["sessionAdmin"] = "yes";		
			header("location:admin/blank.php");
			exit();
		}else {
			header("location:index.php");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($username =="service"){
		$show_sql =mysql_query("select username  from cs_login where username='$username' and binary password = '$password'",$cnn_cs);
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionService");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "InAdmin";		
			$_SESSION["sessionService"] = "logged";
			$_SESSION["sessionactivity_type"]="";
			header("location:service/customerservice.php");
			exit();
		} else {
			header("location:index.php");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($user_type == "gateway"){
		session_register("sessionGateway");
		session_register("sessionGatewaylogin");
		session_register("sessionlogin_type");
		session_register("sessionactivity_type");
		session_register("sessionAdminLogged");		         
		$_SESSION["sessionAdminLogged"] = "InAdmin";		
		$activitymode=1;
		if($username == "gateway") {
			$show_sql =mysql_query("select username from cs_login where username='$username' and  binary password = '$password'",$cnn_cs);
			if(mysql_num_rows($show_sql) >0) {
				$_SESSION["sessionGateway"] = "yes";
				$_SESSION["sessionGatewaylogin"] = "gateway";
				$_SESSION["sessionlogin_type"] = 'pmtg';
				if($activitymode==0) {
					$_SESSION["sessionactivity_type"]="Test Mode";
				}
				header("location:gateway/home.php");
				exit();
			}else {
				header("location:index.php");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
		}else {
			$show_sql =mysql_query("select userId,username,transaction_type,activeuser from cs_companydetails where transaction_type ='pmtg' and username='$username' and binary password='$password' and suspenduser='NO'",$cnn_cs);
		//	print "select userId,username,transaction_type,activeuser from cs_companydetails where transaction_type ='pmtg' and username='$username' and password='$password'";
			if($show_val = mysql_fetch_array($show_sql)) {
				//$_SESSION["sessionGateway"] = "yes";
				//session_register("sessionlogin");
				$_SESSION["sessionGateway"] = "yes";
				$_SESSION["sessionGatewaylogin"] = $show_val[0];
				$_SESSION["sessionlogin_type"] = 'pmtg';
				if($activitymode==0) {
					$_SESSION["sessionactivity_type"]="Test Mode";
				}
				header("location:gateway/home.php");
				exit();
			} else {
				header("location:index.php");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			} 
		}
	} else if($user_type == "customer"){
			session_register("sessionServiceUser");
			session_register("sessionServiceUserId");
			session_register("sessionlogin_type");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "InAdmin";		
		$show_select_sql =mysql_query("select id,username,company_ids from cs_customerserviceusers where username='$username' and  binary password = '$password'",$cnn_cs);
		if(mysql_num_rows($show_select_sql) >0) {
			$strvalue = strstr(mysql_result($show_select_sql,0,2), ',');
		/*	if((mysql_result($show_select_sql,0,2) !="A") || ($strvalue!="")) {
				$sql_select_qry ="select a.id,a.username,b.transaction_type,b.activeuser from cs_customerserviceusers as a,cs_companydetails as b where a.company_ids=b.userId and a.username='$username' and a.password='$password' and b.suspenduser='NO'";
				print($sql_select_qry);
				exit();
				$show_sql =mysql_query($sql_select_qry,$cnn_cs);
				if($show_val = mysql_fetch_array($show_sql)){
					$_SESSION["sessionServiceUserId"] = $show_val[0];
					$_SESSION["sessionServiceUser"] = $username;
					$_SESSION["sessionlogin_type"] = $show_val[2];
					$activitymode = $show_val[3];
					if($_SESSION["sessionlogin_type"] =="") {
						$_SESSION["sessionlogin_type"] ="tele";
					}
					if($activitymode==0) {
						$_SESSION["sessionactivity_type"]="Test Mode";
					}
					header("location:service/customerservice.php");
					exit();
				}
			} else {*/
				$sql_select_qry ="select id,username from cs_customerserviceusers where username='$username' and  binary password = '$password'"; 
				$show_sql =mysql_query($sql_select_qry,$cnn_cs);
				if($show_val = mysql_fetch_array($show_sql)) {
					$_SESSION["sessionServiceUserId"] = $show_val[0];
					$_SESSION["sessionServiceUser"] = $username;
					$activitymode = 1;
					if($_SESSION["sessionlogin_type"] =="") {
						$_SESSION["sessionlogin_type"] ="tele";
					}
					if($activitymode==0) {
						$_SESSION["sessionactivity_type"]="Test Mode";
					}
					header("location:service/customerservice.php");
					exit();
				}
			//}
		}else {
			header("location:index.php");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	
		
	} else if($user_type == "tele"){
		session_register("sessionAdminLogged");		         
		$_SESSION["sessionAdminLogged"] = "InAdmin";		
		session_register("sessionlogin_type");
		session_register("sessionactivity_type");
		$qry_select ="select userId,username,transaction_type,activeuser from cs_companydetails where transaction_type ='tele' and username='$username' and  binary password = '$password' and suspenduser='NO'";
		$rst_select = mysql_query($qry_select,$cnn_cs);
		if (mysql_num_rows($rst_select)>0){
			session_register("sessionlogin");
			$_SESSION["sessionlogin"] = mysql_result($rst_select,0,0);
			$_SESSION["sessionlogin_type"] = mysql_result($rst_select,0,2);
			$activitymode = mysql_result($rst_select,0,3);
			if($_SESSION["sessionlogin_type"] =="") {
				$_SESSION["sessionlogin_type"] ="tele";
			}
			if($activitymode==0) {
				$_SESSION["sessionactivity_type"]="Test Mode";
			}
			header("location:blank.php");
			exit();
		}
		else {
			session_register("sessionCompanyAdmin");
			session_register("sessionLoginUser");
			$qry_selectuser = "Select cc_usersid,company_id,user_name from cs_callcenterusers where user_name='$username' and binary user_password ='$password'";
			$rst_selectuser = mysql_query($qry_selectuser,$cnn_cs);
			if (mysql_num_rows($rst_selectuser)>0){
				$_SESSION["sessionLoginUser"] = mysql_result($rst_selectuser,0,0);
				$_SESSION["sessionCompanyAdmin"] = mysql_result($rst_selectuser,0,1);
				$_SESSION["sessionlogin_type"] = "call";
				header("location:callcenter/blank.php");
				exit();
			}else{
			//************** start code for checking the tsr user ******************
			$qrySelect = "select * from cs_tsrusers where tsr_user_name ='$username' and  binary tsr_password ='$password'"; 
			if ( !$rstSelect = mysql_query($qrySelect,$cnn_cs)) {
				print("Can not execute query");
				exit();
			} else {
				if ( mysql_num_rows($rstSelect) >0) {
					$_SESSION["sessionLoginUser"] = mysql_result($rstSelect,0,0);
					$_SESSION["sessionCompanyAdmin"] = mysql_result($rstSelect,0,2);
					$_SESSION["sessionlogin_type"] = "tsr";
					header("location:tsr/blank.php");
					exit();
				}else{
					header("location:index.php");
					$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
				}
			}	
				
			// *********************************************************************
			
			}
		}
	} else if($user_type =="reseller"){
		session_register("sessionAdminLogged");		         
		$_SESSION["sessionAdminLogged"] = "InAdmin";		
		$show_sql =mysql_query("select reseller_id,reseller_username,completed_reseller_application from cs_resellerdetails where reseller_username='$username' and binary reseller_password='$password' and suspend_reseller=0");
			if ( mysql_num_rows($show_sql) >0) {
				session_register("sessionReseller");
				session_register("sessionResellerName");
				session_register("sessionResellerApplication");
				$_SESSION["sessionReseller"] = mysql_result($show_sql,0,0);
				$_SESSION["sessionResellerName"] = mysql_result($show_sql,0,1);
				if(mysql_result($show_sql,0,2)==0) {
					$_SESSION["sessionResellerApplication"] = "nonactive";
				}				
				header("location:reseller/blank.php");
				exit();
			}
	} else if($username !=""){
		session_register("sessionAdminLogged");		         
		$_SESSION["sessionAdminLogged"] = "InAdmin";		
		$show_sql =mysql_query("select userId,username,transaction_type,activeuser from cs_companydetails where transaction_type !='pmtg' and username='$username' and  binary password='$password' and suspenduser='NO'");
	//	print "select userId,username,transaction_type,activeuser from cs_companydetails where transaction_type !='pmtg' and username='$username' and password='$password' and suspenduser='NO'";
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionlogin");
			session_register("sessionlogin_type");
			session_register("sessionactivity_type");
			$_SESSION["sessionlogin"] = $show_val[0];
			$_SESSION["sessionlogin_type"] = $show_val[2];
			$activitymode = $show_val[3];
			if($_SESSION["sessionlogin_type"] =="") {
				$_SESSION["sessionlogin_type"] ="tele";
			}
			if($activitymode==0) {
				$_SESSION["sessionactivity_type"]="Test Mode";
			}
			header("location:blank.php");
			exit();
		} else {
			$show_user_sql =mysql_query("select a.id, a.userid,a.username,b.transaction_type,b.activeuser from cs_companyusers as a,cs_companydetails as b where a.userId=b.userId and b.transaction_type !='pmtg' and a.username='$username' and binary a.password='$password' and b.suspenduser='NO'");
			if($show_user_val = mysql_fetch_array($show_user_sql)) {
			session_register("sessionCompanyUser");
			session_register("sessionactivity_type");
			$_SESSION["sessionCompanyUserId"] = $show_user_val[0];
			$_SESSION["sessionCompanyUser"] = $show_user_val[1];
			$_SESSION["sessionlogin_type"] = $show_user_val[2];
			$activitymode = $show_user_val[3];
			if($_SESSION["sessionlogin_type"] =="") {
				$_SESSION["sessionlogin_type"] ="tele";
			}
			if($activitymode==0) {
				$_SESSION["sessionactivity_type"]="Test Mode";
			}
				header("location:home.php");
				exit();
			} else {
				header("location:index.php");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
		} 
	} 
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
                          <tr> 
                            <td align="left" valign="top"><div align="center"><a href="Demo/teledemo.htm"><img src="images_new/click_here_02.gif" width="76" height="24" border="0"></a></div></td>
                          </tr>
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
                            <td align="left" valign="top"><div align="center"><a href="gateway.php"><img src="images_new/applynow.gif" width="76" height="24" border="0"></a></div></td>
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
                                  above for a more in-depth look at what Etelegate.com 
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