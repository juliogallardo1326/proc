<?php

function general_login($username,$password,$usertype,$gatewayid,$reset_session=true,$redirect=true)
{
	global $cnn_cs;
	global $etel_domain_path;
	global $etel_debug_mode;
	global $database;
	//mysql_select_db($database["database_main"],$cnn_cs) or dieLog("Unable to connect database"); 
	
	$sql = "SELECT * FROM {$database['database_main']}.`etel_gateways` where `gw_id`= '$gatewayid'";
	
	$result=sql_query_read($sql,$cnn_cs) or die(mysql_error(). " $sql");
	$gw=mysql_fetch_assoc($result);
	//mysql_select_db($gw["gw_database"],$cnn_cs) or die("Unable to connect database ".$gw["gw_database"]); 
	
	$username = strtolower($username);
	$password = strtolower($password);
	
	if($reset_session) session_unset();
	if(!$_SESSION["gw_switch"]) $_SESSION["gw_switch"]=$gatewayid;
	$_SESSION["gw_database"] = $gw['gw_database'];
	$_SESSION["gw_id"] = $gw['gw_id'];
	$_SESSION["gw_template"] = $gw['gw_template'];
	$_SESSION["gw_links"] = $gw['gw_links'];
	$_SESSION["gw_folder"] = $gw['gw_folder'];
	$_SESSION["gw_index"] = $gw['gw_index'];
	$_SESSION["gw_title"] = $gw['gw_title'];
	$_SESSION["gw_emails_sales"] = $gw['gw_emails_sales'];
	
	$_SESSION["gw_user_username"] = $username;
	$_SESSION["gw_user_password"] = $password;
	$_SESSION["gw_user_hash"] = md5($username.$password);
	$redir_add = "&nr=1";
	if(!strpos(base64_decode($_REQUEST['login_redir']),"?")) $redir_add = "?nr=1";
	
	// Find Entity
	
	$sql="
	select 
		en.*
	from 
		cs_entities as en 
	where 
		en_username='$username' 
		and en_password='".md5($username.$password)."' 
	";
	
	$ip = getRealIp();
	$result = sql_query_read($sql) or dieLog(mysql_error());
	if($userInfo = mysql_fetch_assoc($result))
	{
		sql_query_write("update cs_entities set en_last_IP = '$ip', en_last_login = NOW() where en_ID = '".$userInfo['en_ID']."'") or dieLog(mysql_error());
		
		toLog('login',$userInfo['en_type'], "Login: U:$username, IP:".getRealIp(), $_SESSION["sessionlogin"]);
		
		if($userInfo['en_gateway_ID'] && $userInfo['en_gateway_ID']!=$_SESSION["gw_id"]) $_SESSION["gw_switch"] = $userInfo['en_gateway_ID'];
	
		switch ($userInfo['en_type']) 
		{
			case "merchant":
				$_SESSION["sessionlogin"] = $userInfo['en_type_ID'];
				$_SESSION["userType"]="Merchant";

				if(is_string($redirect)) header("location:".$etel_domain_path.$redirect);
				else if ($_REQUEST['login_redir']) header("location:".$_SESSION["gw_domain"].base64_decode($_REQUEST['login_redir']).$redir_add);
				else if($show_val['cd_completion']<=9) header("location:".$etel_domain_path."/SmartProjection.php");
				else header("location:".$etel_domain_path."/SmartProjection.php");
				exit();
				break;
					
			case "reseller":
				$_SESSION["sessionReseller"] = $userInfo['en_type_ID'];
				$_SESSION["userType"]="Reseller";
				
				if(is_string($redirect)) header("location:".$etel_domain_path.$redirect);
				else if ($_REQUEST['login_redir']) header("location:".$_SESSION["gw_domain"].base64_decode($_REQUEST['login_redir']).$redir_add);
				else header("location:".$etel_domain_path."/reseller/blank.php");
				exit();
				break;
			case "admin":
				$_SESSION["userType"]="Admin";
				$_SESSION["gw_user_username"] = $username;
				$_SESSION["gw_user_password"] = $password;
				$_SESSION["gw_user_hash"] = md5($username.$password);
				if($redirect===true)
				{
					if ($_REQUEST['login_redir']) header("location:".$_SESSION["gw_domain"].base64_decode($_REQUEST['login_redir']).$redir_add);
					else if($userInfo['li_level'] == 'singleview' || $userInfo['li_singleview_allow']) 
					{
						$pages = explode("|",$userInfo['li_singleview_allow']);
						header("location:".$etel_domain_path."/admin/".$pages[0]);
					}
					else header("location:".$etel_domain_path."/admin/blank.php");
					exit();
				}
				else if($redirect != "None")
				{
					header("location:".$etel_domain_path.$redirect);
					exit();
				}
				break;
		}
	}
	else
	{
		toLog('login','misc', "Login Failed: U:$username, IP:".getRealIp(), $_SESSION["sessionlogin"]);
		return array('status'=>false,'msg'=>"Invalid Username/Password.");
	}
}

function etel_smarty_display($file)
{
	global $smarty;
	global $etel_root_path;

	if(!file_exists($smarty->template_dir.$file))
	{
		$curtemplate = 'default';
		$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
		$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
		$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
	}
	$smarty->display($file);
}

?>