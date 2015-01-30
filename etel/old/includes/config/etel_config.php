<?php
if(!isset($etel_debug_mode))$etel_debug_mode=0;

if($HTTP_SERVER_VARS["HTTPS"] != "on" && !$etel_disable_https && 0)
{
	$newurl = "https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	header("location: $newurl");
	die();
}

$etel_domain_path = "";
$etel_root_path = "/home/nichebil/public_html/";
$config_default_index = $etel_domain_path."/gateway/index.php";

$database["server"]	="localhost";
$database["user"]		="nichebil_user";
$database["password"]	="WSD%780="	;
$database["database_main"]	="nichebil_dbsmain"	;
?>