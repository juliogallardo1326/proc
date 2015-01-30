<?php session_start();
$username = (isset($HTTP_POST_VARS['username'])?trim($HTTP_POST_VARS['username']):"");
$password = (isset($HTTP_POST_VARS['password'])?trim($HTTP_POST_VARS['password']):"");
$user_type = (isset($HTTP_POST_VARS['usertype'])?trim($HTTP_POST_VARS['usertype']):"");
$invalidlogin = "";
$activitytype="";
if ($username == "" || $password == ""){
	header("location:login.htm");
	$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
}
if($password){
require_once("includes/dbconnection.php");
$username = strtolower($username); 
session_register("sessionCompanyLogoName");
$_SESSION["sessionCompanyLogoName"] ="images/spacer.gif";
$_SESSION["sessionAdminLogged"] = "OutAdmin";		
	if($username == "admin") {
		$show_sql =mysql_query("select username  from cs_login where username='$username' and binary password='$password'",$cnn_cs);
		if(mysql_num_rows($show_sql) >0) {
			session_register("sessionAdmin");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdmin"] = "yes";	
			$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
			header("location:admin/blank.php");
			exit();
		}else {
			header("location:login.htm");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($username =="service"){
		$show_sql =mysql_query("select username  from cs_login where username='$username' and binary password='$password'",$cnn_cs);
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionService");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionService"] = "logged";
			$_SESSION["sessionactivity_type"]="";
			$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
			header("location:service/customerservice.php");
			exit();
		} else {
			header("location:login.htm");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($user_type == "gateway"){
		session_register("sessionGateway");
		session_register("sessionGatewaylogin");
		session_register("sessionlogin_type");
		session_register("sessionactivity_type");
		session_register("sessionAdminLogged");		         
		$activitymode=1;
		if($username == "gateway") {
			$show_sql =mysql_query("select username from cs_login where username='$username' and binary password='$password'",$cnn_cs);
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
				header("location:login.htm");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
		}else {
			$show_sql =mysql_query("select userId,username,transaction_type,activeuser,gateway_id  from cs_companydetails where transaction_type ='pmtg' and username='$username' and binary password='$password' and suspenduser='NO'",$cnn_cs);
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
				$_SESSION["sessionGatewayId"] = $show_val[4];
				$gateway_id = $show_val[0];
				if($gateway_id !=-1) {
					$gateway_logo = func_get_gatewayLogo($gateway_id);
					if ($gateway_logo != "") {
						$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
					} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
					}
				} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
				}
				header("location:gateway/home.php");
				exit();
			} else {
				header("location:login.htm");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			} 
		}
	} else if($user_type == "customer"){
			session_register("sessionServiceUser");
			session_register("sessionServiceUserId");
			session_register("sessionlogin_type");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "OutAdmin";		
		$show_select_sql =mysql_query("select id,username,company_ids from cs_customerserviceusers where username='$username' and binary password='$password'",$cnn_cs);
		if(mysql_num_rows($show_select_sql) >0) {
			$strvalue = strstr(mysql_result($show_select_sql,0,2), ',');
			if((mysql_result($show_select_sql,0,2) !="A") || ($strvalue!="")) {
				$sql_select_qry ="select a.id,a.username,b.transaction_type,b.activeuser from cs_customerserviceusers as a,cs_companydetails as b where a.company_ids=b.userId and a.username='$username' and binary a.password='$password'";
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
					if($gateway_id !=-1) {
						$gateway_logo = func_get_gatewayLogo($gateway_id);
						if ($gateway_logo != "") {
							$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
						} else {
							$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
						}
					} else {
							$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
					}
					header("location:service/customerservice.php");
					exit();
				}
			} else {
				$sql_select_qry ="select id,username from cs_customerserviceusers where username='$username' and binary password='$password'"; 
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
					if($gateway_id !=-1) {
						$gateway_logo = func_get_gatewayLogo($gateway_id);
						if ($gateway_logo != "") {
							$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
						} else {
							$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
						}
					} else {
							$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
					}
					header("location:service/customerservice.php");
					exit();
				}
			}
		}else {
			header("location:login.htm");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($user_type == "tele"){
		session_register("sessionlogin");
		session_register("sessionlogin_type");
		session_register("sessionactivity_type");
		session_register("sessionAdminLogged");
		$_SESSION["sessionAdminLogged"] = "OutAdmin";		
		$qry_select ="select userId,username,transaction_type,activeuser,gateway_id from cs_companydetails where transaction_type ='tele' and username='$username' and binary password='$password'";
		$rst_select = mysql_query($qry_select,$cnn_cs);
		if (mysql_num_rows($rst_select)>0){
			$_SESSION["sessionlogin"] = mysql_result($rst_select,0,0);
			$_SESSION["sessionlogin_type"] = mysql_result($rst_select,0,2);
			$_SESSION["sessionGatewayId"] =mysql_result($rst_select,0,4);
			$gateway_id =mysql_result($rst_select,0,4);
			if($gateway_id !=-1) {
				$gateway_logo = func_get_gatewayLogo($gateway_id);
				if ($gateway_logo != "") {
					$_SESSION["sessionCompanyLogoName"] = "GatewayLogo/".func_get_gatewayLogo($gateway_id);
				} else {
					$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";
				}
			} else {
					$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";	
			}
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
			$_SESSION["sessionAdminLogged"] = "OutAdmin";		
			$qry_selectuser = "Select cc_usersid,company_id,user_name from cs_callcenterusers where user_name='$username' and binary user_password ='$password'";
			$rst_selectuser = mysql_query($qry_selectuser,$cnn_cs);
			if (mysql_num_rows($rst_selectuser)>0){
				$_SESSION["sessionlogin"] = mysql_result($rst_selectuser,0,0);
				$_SESSION["sessionLoginUser"] = mysql_result($rst_selectuser,0,0);
				$companyid	=	mysql_result($rst_selectuser,0,1);
				$_SESSION["sessionCompanyAdmin"] = mysql_result($rst_selectuser,0,1);
				$_SESSION["sessionlogin_type"] = "call";
				$qry_selectgateid="Select gateway_id from cs_companydetails where userid=$companyid";
				$rst_selectgateid=mysql_query($qry_selectgateid,$cnn_cs);
				if (mysql_num_rows($rst_selectgateid)>0){
					$_SESSION["sessionGatewayId"] = mysql_result($rst_selectgateid,0,0);
					$gateway_id = mysql_result($rst_selectgateid,0,0);
				if($gateway_id !=-1) {
					$gateway_logo = func_get_gatewayLogo($gateway_id);
					if ($gateway_logo != "") {
						$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
					} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
					}
				} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
				}
					header("location:callcenter/blank.php");
					exit();
				}
			}else{
			//************** start code for checking the tsr user ******************
				$qrySelect = "select * from cs_tsrusers where tsr_user_name ='$username'  and binary tsr_password ='$password'"; 
				if ( !$rstSelect = mysql_query($qrySelect,$cnn_cs)) {
					print("Can not execute query");
					exit();
				} else {
					if ( mysql_num_rows($rstSelect) >0) {
						$_SESSION["sessionlogin"] = mysql_result($rstSelect,0,0);
						$companyid =  mysql_result($rstSelect,0,2);
						$_SESSION["sessionLoginUser"] = mysql_result($rstSelect,0,0);
						$_SESSION["sessionCompanyAdmin"] = mysql_result($rstSelect,0,2);
						$_SESSION["sessionlogin_type"] = "tsr";
						$qry_selectgateid="Select gateway_id from cs_companydetails where userid=$companyid";
						$rst_selectgateid=mysql_query($qry_selectgateid,$cnn_cs);
						if (mysql_num_rows($rst_selectgateid)>0){
							$_SESSION["sessionGatewayId"] = mysql_result($rst_selectgateid,0,0);
							$gateway_id = mysql_result($rst_selectgateid,0,0);
						if($gateway_id !=-1) {
							$gateway_logo = func_get_gatewayLogo($gateway_id);
							if ($gateway_logo != "") {
								$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
							} else {
								$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
							}
						} else {
								$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
						}
							header("location:tsr/blank.php");
							exit();
						}
					}else{
						header("location:login.htm");
						$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
					}
				}	
			} 
			header("location:login.htm");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
		}
	} else if($user_type =="reseller"){
		session_register("sessionAdminLogged");		         
		$_SESSION["sessionAdminLogged"] = "OutAdmin";		
		$show_sql =mysql_query("select reseller_id,reseller_username,completed_reseller_application,gateway_id from cs_resellerdetails where reseller_username='$username' and binary reseller_password='$password'");
			if ( mysql_num_rows($show_sql) >0) {
				session_register("sessionReseller");
				session_register("sessionResellerName");
				session_register("sessionResellerApplication");
				$_SESSION["sessionReseller"] = mysql_result($show_sql,0,0);
				$_SESSION["sessionResellerName"] = mysql_result($show_sql,0,1);
				$_SESSION["sessionGatewayId"] = mysql_result($show_sql,0,3);
				$gateway_id = mysql_result($show_sql,0,3);
				if($gateway_id !=-1) {
					$gateway_logo = func_get_gatewayLogo($gateway_id);
					if ($gateway_logo != "") {
						$_SESSION["sessionCompanyLogoName"] = "../GatewayLogo/".func_get_gatewayLogo($gateway_id);
					} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";
					}
				} else {
						$_SESSION["sessionCompanyLogoName"] = "../images/spacer.gif";	
				}
				if(mysql_result($show_sql,0,2)==0) {
					$_SESSION["sessionResellerApplication"] = "nonactive";
				}
				header("location:reseller/blank.php");
				exit();
			} else {
				header("location:login.htm");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
	} else if($username !=""){
		$show_sql =mysql_query("select userId,username,transaction_type,activeuser,gateway_id from cs_companydetails where transaction_type !='pmtg' and username='$username' and binary password='$password'");
		if($show_val = mysql_fetch_array($show_sql)) {
			session_register("sessionlogin");
			session_register("sessionlogin_type");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "OutAdmin";		
			$_SESSION["sessionlogin"] = $show_val[0];
			$_SESSION["sessionlogin_type"] = $show_val[2];
			$activitymode = $show_val[3];
			if($_SESSION["sessionlogin_type"] =="") {
				$_SESSION["sessionlogin_type"] ="tele";
			}
			if($activitymode==0) {
				$_SESSION["sessionactivity_type"]="Test Mode";
			}
			$gateway_id = $show_val[4];
				if($gateway_id !=-1) {
					$gateway_logo = func_get_gatewayLogo($gateway_id);
					if ($gateway_logo != "") {
						$_SESSION["sessionCompanyLogoName"] = "GatewayLogo/".func_get_gatewayLogo($gateway_id);
					} else {
						$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";
					}
				} else {
						$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";	
				}
			header("location:blank.php");
			exit();
		} else {
			$show_user_sql =mysql_query("select a.id, a.userid,a.username,b.transaction_type,b.activeuser,b.gateway_id from cs_companyusers as a,cs_companydetails as b where a.userId=b.userId and b.transaction_type !='pmtg' and a.username='$username' and binary a.password='$password'");
			if($show_user_val = mysql_fetch_array($show_user_sql)) {
			session_register("sessionCompanyUser");
			session_register("sessionactivity_type");
			session_register("sessionAdminLogged");		         
			$_SESSION["sessionAdminLogged"] = "OutAdmin";		
			$_SESSION["sessionCompanyUserId"] = $show_user_val[0];
			$_SESSION["sessionCompanyUser"] = $show_user_val[1];
			$_SESSION["sessionlogin_type"] = $show_user_val[3];
			$activitymode = $show_user_val[4];
			if($_SESSION["sessionlogin_type"] =="") {
				$_SESSION["sessionlogin_type"] ="tele";
			}
			if($activitymode==0) {
				$_SESSION["sessionactivity_type"]="Test Mode";
			}
				$gateway_id = $show_user_val[5];
				if($gateway_id !=-1) {
					$gateway_logo = func_get_gatewayLogo($gateway_id);
					if ($gateway_logo != "") {
						$_SESSION["sessionCompanyLogoName"] = "GatewayLogo/".func_get_gatewayLogo($gateway_id);
					} else {
						$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";
					}
				} else {
						$_SESSION["sessionCompanyLogoName"] = "images/spacer.gif";	
				}
				header("location:home.php");
				exit();
			} else {
				header("location:login.htm");
				$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
			}
		}
			header("location:login.htm");
			$invalidlogin="<font face='verdana' color='red'>Invalid login</font>";
	}
}

function func_get_gatewayLogo($gateway_id) {
	if($gateway_id !="") {
		$str_logo_qry = "select a.companyname, b.logo_filename from cs_companydetails a, cs_logo b where b.Logo_company_id ='$gateway_id' and a.userId = b.Logo_company_id";
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

