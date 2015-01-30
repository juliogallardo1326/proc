<?php session_start();
require_once("includes/function.php");
$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
$user_type = (isset($HTTP_POST_VARS['usertype'])?quote_smart($HTTP_POST_VARS['usertype']):"");
session_start();
$username = strtolower($username);
	if($username == "demo") {header("location:Demo/index.php"); exit();}
require_once("includes/dbconnection.php");

general_login($username,$password,$user_type,3);

require $rootdir.'smarty/libs/Smarty.class.php';

$smarty = new Smarty;

$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("HackerSafe", $HackerSafe);
	$smarty->display('main_contactus.tpl');


?>