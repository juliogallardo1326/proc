<?php 

	if (isset($_REQUEST['printable'])) $printable_version = true;

	//ini_set("session.save_handler", "files"); // Bug fix for a weird php bug.
	@session_start();	
	ignore_user_abort(true);

	if ($_GET['debug']) $etel_debug_mode = 1;
	error_reporting(0);
	
	if (!isset($_SESSION['initiated'])) 
	{ 
	   session_regenerate_id(); 
	   $_SESSION['initiated'] = true; 
	} 
	
	if($_SESSION["gw_switch"] && !$gateway_db_select)
	{
		$gateway_db_select=$_SESSION["gw_switch"];
	}
	
	if(!(strpos($_SERVER['HTTP_HOST'],"lazerpay.net")=== false)) $etel_disable_https = true;
	
	require_once("subFunctions/db.functions.php");
	require_once("constants.php");
	require('config/etel_config.php');
	$cnn_cs = mysql_connect($database["server"],$database["user"],$database["password"]) 
       or dieLog("Could not find server '".$database["server"]."' ".mysql_error()); 

	if (isset($_GET['printable'])) $printable_version = true;
	
	// select Gateway
   	if(!$gateway_db_select ) 
	{
		if($_SESSION['gw_id'] == '1' || ($_SESSION['gw_user_username'] == 'demo' && $_SESSION['gw_user_password'] == 'demo'))
		{
			//$_SESSION["gw_database"]="etel_gwEcomGlobal";	
			$gateway_db_select = 1;
		}		
		else if(!(strpos($_SERVER['HTTP_HOST'],"maturebill.com")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 4;
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"nichebill.com")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 7;
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"etelegate.com")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 3;
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"etelegate.biz")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			print_r($_SERVER);
			$gateway_db_select = 3;
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"etelegate.net")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 3;
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"etelegate.biz")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 3;
			$curtemplate_overwrite = "etelegate_ewallet";
		}
		else if(!(strpos($_SERVER['HTTP_HOST'],"lazerpay.net")=== false))
		{
			//$_SESSION["gw_database"]="etel_dbscompanysetup";	
			$gateway_db_select = 3;
			$curtemplate_overwrite = "etelegate_lazerpay";
			$gw_title_overwrite = "LazerPay.net";
		}
	}
   //	if(!$_SESSION["gw_database"]) 
	//{
		//$_SESSION["gw_database"]="etel_dbscompanysetup";	
		if (!$gateway_db_select) die("Invalid Access $gateway_db_select");
	
		mysql_select_db($database["database_main"],$cnn_cs) or dieLog("Unable to connect database"); 
		
		$sql = "SELECT * FROM `etel_gateways`";
		$result=sql_query_read($sql,$cnn_cs) or dieLog(mysql_error(). " $sql");
		while($gwInfo=mysql_fetch_assoc($result))
			$etel_gw_list[$gwInfo['gw_id']]=$gwInfo;
		$gwInfo=$etel_gw_list[$gateway_db_select];
		$_SESSION=array_merge($_SESSION,$gwInfo);
	//}
	
	$etel_current_ip = getRealIp();
	//if(ip2long($etel_current_ip)==$gwInfo['gw_debug_ip'] && !isset($etel_debug_mode)&& ip2long($etel_current_ip)!='1193664307') $etel_debug_mode = 1;
	if($gw_title_overwrite) $_SESSION["gw_title"] = $gw_title_overwrite;
	$database["database"] = $_SESSION["gw_database"];
	   
	mysql_select_db($database["database"],$cnn_cs) or dieLog("Unable to connect database"); 
	$redirect_home = false;
	if($_SESSION["userType"]=="Admin")
	{
		unset($_SESSION["gw_switch"]);
		$sql="
		select 
			*
		from 
			cs_entities as en 
		where 
			en_username='".$_SESSION["gw_user_username"]."' 
			and en_password='".$_SESSION["gw_user_hash"]."' 
		";
		$result=sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query $sql");
		if (mysql_num_rows($result)<1) $redirect_home = true;
		else
		{
			$curUserInfo = mysql_fetch_assoc($result);
			if($curUserInfo['en_type']!='admin') { session_unset(); dieLog("Not an Admin: $sql");};
			$curUserInfo['li_level']='full';
			$curUserInfo['en_info'] = etel_unserialize($curUserInfo['en_info']);
			$_SESSION["gw_user_email"] = $curUserInfo['en_email'];
			$_SESSION["gw_user_en_ID"] = $curUserInfo['en_ID'];
			$_SESSION["gw_user_en_type"] = $curUserInfo['en_type'];
			$adminInfo = $curUserInfo; // Phase out adminInfo
			$companyInfo = $curUserInfo; // Phase out companyInfo
		}
	}	
	if($_SESSION["userType"]=="Merchant")
	{
			$sql="
		select 
			cd.*,en.*
		from 
			cs_entities as en 
			left join cs_companydetails as cd on en_type='merchant' and en_type_ID = cd.userId
		where 
			en_username='".$_SESSION["gw_user_username"]."' 
			and en_password='".$_SESSION["gw_user_hash"]."' 
		";
		$result=sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query $sql");
		if (mysql_num_rows($result)<1) $redirect_home = true;
		$curUserInfo = mysql_fetch_assoc($result);
		$curUserInfo['en_info'] = etel_unserialize($curUserInfo['en_info']);
		$_SESSION["gw_user_email"] = $curUserInfo['en_email'];
		$_SESSION["gw_user_en_ID"] = $curUserInfo['en_ID'];
		$_SESSION["gw_user_en_type"] = $curUserInfo['en_type'];
		$companyInfo = $curUserInfo; // Phase out companyInfo
		
	} 
	else if($_SESSION["userType"]=="Reseller")
	{
			$sql="
		select 
			*
		from 
			cs_entities as en 
		where 
			en_username='".$_SESSION["gw_user_username"]."' 
			and en_password='".$_SESSION["gw_user_hash"]."' 
		";		
		$result=sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query $sql");
		if (mysql_num_rows($result)<1) $redirect_home = true;
		$curUserInfo = mysql_fetch_assoc($result);
		$curUserInfo['en_info'] = etel_unserialize($curUserInfo['en_info']);
		$_SESSION["gw_user_email"] = $curUserInfo['en_email'];
		$_SESSION["gw_user_en_ID"] = $curUserInfo['en_ID'];
		$_SESSION["gw_user_en_type"] = $curUserInfo['en_type'];
		$resellerInfo = $curUserInfo;
		$companyInfo = $curUserInfo; // Phase out companyInfo
	} 
	else
	{
		//$index = $_SESSION["gw_index"];
		//if(!$index) $index = $config_default_index;
		//session_destroy();
		//header("location:$index");
		//exit();	
	}
	
	if($curUserInfo['en_access'])
	{
		$access_bin = strrev(base_convert($curUserInfo['en_access'],10,2));
		$curUserInfo['en_access'] = array();
		for($i=0;$i<64;$i++) $curUserInfo['en_access'][$i]=substr($access_bin,$i,1)==1;
		unset($access_bin);
		if($curUserInfo['en_access'][ACCESS_DEBUG_MODE]) $_SESSION["gw_user_en_debug"] = 1;
	}

	unset($sql);
	if($redirect_home)
	{
		$index = $_SESSION["gw_index"];
		if(!$index) $index = $config_default_index;
		session_destroy();
		header("location:".$index."?login_redir=".base64_encode($_SERVER['REQUEST_URI']));
		exit();
	}
	
	if($companyInfo['en_gateway_ID'] && $companyInfo['en_gateway_ID']!=$_SESSION["gw_id"]) $_SESSION["gw_switch"] = $companyInfo['en_gateway_ID'];

	if(!$_SESSION["gw_switch"]) $_SESSION["gw_switch"]=$gateway_db_select;
	
	
	if($_SESSION["gw_user_en_debug"] && !isset($etel_debug_mode)) 
	{
		if(ip2long($etel_current_ip)!=$gwInfo['gw_debug_ip']) sql_query_write("update etel_dbsmain.".$database["database_main"]." set gw_debug_ip = '". ip2long($etel_current_ip)."' where gw_id = '".$gwInfo['gw_id']."'");
		$etel_debug_mode=1; 
	}
	if(ip2long($etel_current_ip)==$gwInfo['gw_debug_ip'] && $_COOKIE['etel_debug_enable']==2 && !isset($etel_debug_mode)) 
	{
		$etel_debug_mode=1; 
	}
	
			
	// Debug Mode
	if($etel_debug_mode==1&&!$printable_version) 
	{
		error_reporting(E_ALL ^ E_NOTICE);
		print "<span style='font-size: 10px;'>";
		print_r($_REQUEST);
		print "<br>";
		print_r($_SESSION);
		print "</span>";
	}	
	else
	{
		$etel_error_info = set_error_handler('etel_error_handler');
	}
	require_once("function.php");
	
	$etel_generate_page_time = microtime_float();
	
	
	if(!$disablePostChecks && !$etel_debug_mode)
	{
		if(0 && !$disableInjectionChecks)
			{
			$SQLInjectionRegex = '/[\'")]* *[oO][rR] *.*(.)(.) *= *\\2(?:--)?\\1?/';
			$suspiciousQueryItems = preg_grep($SQLInjectionRegex, $_REQUEST);
			if(sizeof($suspiciousQueryItems)>0) 
			{
				$hostname = gethostbyaddr($_SERVER["REMOTE_ADDR"]).":".$etel_current_ip; 
				toLog('hackattempt','misc', $_SESSION["userType"]." Attempted to use an SQL Injection Attack in ".basename(__FILE__)." from $hostname - ".implode("|",$suspiciousQueryItems).print_r($_SERVER,true), $companyid);
				/*
				foreach($suspiciousQueryItems as $key=>$item)
				{
					$_REQUEST[$key] = NULL;
					$_POST[$key] = NULL;
					$_GET[$key] = NULL;
					$HTTP_POST_VARS[$key] = NULL;
					$HTTP_GET_VARS[$key] = NULL;
				}
				*/
			}
		}
		
		if(!$disableHTMLChecks)
		{
			if(is_array($_REQUEST))
			{
				foreach($_REQUEST as $key=>$item)
				{
					if (strpos($item,"<")!==FALSE)
					{
						$_REQUEST[$key] = str_replace("<","&lt;",$_REQUEST[$key]);
						$_POST[$key] = str_replace("<","&lt;",$_POST[$key]);
						$_GET[$key] = str_replace("<","&lt;",$_GET[$key]);
						$HTTP_POST_VARS[$key] = str_replace("<","&lt;",$HTTP_POST_VARS[$key]);
						$HTTP_GET_VARS[$key] = str_replace("<","&lt;",$HTTP_GET_VARS[$key]);
					}
				}
			}
		}
	}
	
	if(sizeof($_POST)>0)
	{
		$post_md5 = md5(implode($_POST));
		if($_SESSION['last_post_md5'] == $post_md5) $etel_repost_warning = true;
		else $_SESSION['last_post_md5'] = $post_md5;
	} else $_SESSION['last_post_md5'] = "";
	
	// Tracking
	//registerClick();

	
	$HackerSafe = "<a target='_blank' href='https://www.scanalert.com/RatingVerify?ref=www.etelegate.com'><img width='115' height='32' border='0' src='//images.scanalert.com/meter/www.etelegate.com/22.gif' alt='HACKER SAFE certified sites prevent over 99.9% of hacker crime.' oncontextmenu='alert(\"Copying Prohibited by Law - HACKER SAFE is a Trademark of ScanAlert\"); return false;'></a>";


	?>