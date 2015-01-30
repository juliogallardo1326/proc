<?php 
if($HTTP_SERVER_VARS["HTTPS"] != "on")  
{
	$newurl = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	//header("location: $newurl");    
} 
	
$etel_domain_path = "/etelegate.com/public_html";
$etel_root_path = "D:/public_html/etelegate.com/public_html";

$database["server"]	="localhost";
$database["user"]		="etel_root";
$database["password"]	="WSD%780="	;
//	$database["database"]	="dbs_companysetup";
?>