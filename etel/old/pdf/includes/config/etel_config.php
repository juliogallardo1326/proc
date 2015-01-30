<?php
//	$database["database"]	="dbs_companysetup";
if($HTTP_SERVER_VARS["HTTPS"] != "on" && !$etel_disable_https)
{
	$newurl = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	header("location: $newurl");
	die();
}

$etel_domain_path = "";
$etel_root_path = "/home/etel/public_html/";
$config_default_index = $etel_domain_path."/gateway/index.php";

$database["server"]	="localhost";
$database["user"]		="etel_db";
$database["password"]	="WSD%780="	;

/*
	$gateway_db_select=3;
$etel_domain_path = "https://localhost/etelegate.com/public_html/";
$etel_root_path = "D:/public_html/etelegate.com/public_html/";
$config_default_index = $etel_domain_path."/gateway/index.php";

$database["server"]	="localhost";
$database["user"]		="etel_root";
$database["password"]	="WSD%780="	;
//	$database["database"]	="dbs_companysetup";
if(!isset($etel_debug_mode))$etel_debug_mode=1;
*/
?>