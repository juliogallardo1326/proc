<?php 

require_once("includes/indexheader.php");
	etel_smarty_display('main_header.tpl');

$email = isset($HTTP_POST_VARS['email'])?$HTTP_POST_VARS['email']:"";
$invalidlogin="";
$headers="";
	if($email !=""){
		$show_sql =mysql_query("select password,username,companyname from cs_companydetails where email='$email'",$cnn_cs);
		if(mysql_num_rows($show_sql) == 0) {	
			$invalidlogin="Invalid Email ID.";
		} else {
			while($sql_res = mysql_fetch_array($show_sql)) {
				$password = $sql_res[0];
				$username = $sql_res[1];
				$name = $sql_res[2];
				$invalidlogin="An email has been sent with your login details.";
				
				$useEmailTemplate = "password_retrieval";
				$data['site_access_URL'] = $_SESSION['gw_domain'];
				$data['site_URL'] = $_SESSION['gw_title'];
				$data['full_name'] = $name;
				$data['email'] = $email;
				$data['username'] = $username;
				$data['password'] = $password;
				$data["gateway_select"] = $companyInfo['gateway_id'];
				send_email_template($useEmailTemplate,$data);
		
				break;
			} 
		}
		
	} 
	else	$invalidlogin="Please Enter Your Email Address.";
message('<font face="verdana" size="2" color="red">'.$invalidlogin.'</font><BR>Enter Your Email Address: <input type="text" name="email" style="font-family:arial;font-size:10px;width:190px">',"","Forgot Password","forgotpassword.php",false);

etel_smarty_display('main_footer.tpl');
?>
