<?php

require_once($rootdir."includes/function.php");
require_once($rootdir."includes/function1.php");
session_start();
//if($_GET['wut'] )print_r($_SESSION);

require_once("includes/dbconnection.php");

require_once($rootdir.'smarty/libs/Smarty.class.php');

$smarty = new Smarty;


if($curtemplate_overwrite)
	if(file_exists($curtemplate_overwrite."/main_header.tpl") && file_exists($curtemplate_overwrite."/main_footer.tpl"))
		$curtemplate = $curtemplate_overwrite;

$smarty->compile_check = true;
$smarty->debugging = false;
$curtemplate = $_SESSION['gw_template'];
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("HackerSafe", $HackerSafe);
$smarty->assign("etel_gateway", $gwInfo);

$smarty->assign("gw_phone_support", $_SESSION["gw_phone_support"]);
$smarty->assign("_GET", $_GET);
$smarty->assign("_POST", $_POST);

?>