<?php 
$etel_debug_mode=0;
chdir("..");
session_start();
$_SESSION['stat'] = (isset($_SESSION['stat']) ? $_SESSION['stat'] : 1);
$gateway_db_select=$_SESSION["gw_id"];
require_once("includes/dbconnection.php");
include("includes/integration.php");
include("includes/transaction.class.php");
require_once('includes/function.php');
require_once($rootdir.'smarty/libs/Smarty.class.php');

$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->debugging = false;
$curtemplate = $_SESSION['gw_template'];
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("GET", $_GET);
$smarty->assign("tmpl_language",$mt_language);
$mt_language = (isset($_SESSION['mt_language']) ? quote_smart($_SESSION['mt_language']) : "eng");
$smarty->assign("mt_language",$mt_language);


etel_smarty_display("legal_privacy.tpl");

?>



