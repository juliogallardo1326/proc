<?php
	if(!$_SESSION["gw_database"]) { ini_set("session.save_handler", "files"); session_start();}
	$autologin = $_REQUEST["autologin"];
	if($autologin)
	{
		$etel_debug_mode = 0;
		require_once("../includes/dbconnection.php");
		$loginInfo = explode("|",etelDec($autologin));
		general_login($loginInfo['0'],$loginInfo['1'],$loginInfo['2'],$loginInfo['3']);	
		die();
	}
	
	if(	$_SESSION["gw_admin_info"])
	{
		$etel_debug_mode = 0;
		require_once("../includes/function.php");
		$loginInfo = explode("|",etelDec($_SESSION["gw_admin_info"]));
		$_SESSION["loginredirect"]=$loginInfo[0];
		$_SESSION["gw_user_username"]=$loginInfo[0];
		$_SESSION["gw_user_hash"]=$loginInfo[1];
		$_SESSION["userType"]=$loginInfo[2];
		$_SESSION['gw_id']=$loginInfo[3];
		$_SESSION["sessionAdmin"] = true;
		unset($_SESSION["gw_admin_info"]);
		require_once("../includes/dbconnection.php");
	}
		
	if($_SESSION["userType"]!="Admin")
	{
		$index = $_SESSION["gw_index"];
		if(!$index) $index = "../index.php";
		if($_GET['nr']) header("location:".$index);
		else header("location:".$index."?login_redir=".base64_encode($_SERVER['REQUEST_URI']));
		die();
	}?>