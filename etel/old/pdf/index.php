<?php 
$etel_disable_https=1;
$redirects = array('etelegate.net'=>'/cs/');

foreach($redirects as $key=>$data)
	if(!(strpos($_SERVER['HTTP_HOST'],$key)=== false))
	{
		$newurl = "https://" . $_SERVER["HTTP_HOST"] .$data;
		header("location: $newurl");
		die();
	}
	
ini_set("session.save_handler", "files"); // Bug fix for a weird php bug.
session_start();
//unset($_SESSION["gw_database"]);


if($_POST['username'] == "demo") {header("location:Demo/index.php"); exit();}

if($_REQUEST['demo'] || $_REQUEST['username']) 
{
	$etel_debug_mode = 0;
}

require_once("includes/dbconnection.php");

$username = quote_smart($_REQUEST['username']);
$password = quote_smart($_REQUEST['password']);
$user_type = quote_smart($_REQUEST['usertype']);

$username = strtolower($username);


$curtemplate = $_SESSION["gw_template"];


if($_GET['demo'])
{
	$username = 'eteldemo';
	$password = 'eteldemo';
	$user_type = 'merchant';
	general_login($username,$password,$user_type,3);	
}
else
if($username) 
{
	general_login($username,$password,$user_type,3);
}
require $rootdir.'smarty/libs/Smarty.class.php';

$smarty = new Smarty;

$smarty->compile_check = true;
$smarty->debugging = false;

$template_page_tpl = 'main.tpl';
if($curtemplate_overwrite)
	if(file_exists($etel_root_path."/tmpl/".$curtemplate_overwrite."/".$template_page_tpl))
		$curtemplate = $curtemplate_overwrite;

		
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("HackerSafe", $HackerSafe);

$smarty->assign("gw_phone_support", $_SESSION["gw_phone_support"]);
$smarty->assign("_GET", $_GET);
$smarty->assign("_POST", $_POST);
$smarty->display($template_page_tpl);


?>