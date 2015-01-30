<?php 
$rootdir = "../";
require_once($rootdir."includes/dbconnection.php");
require_once($rootdir."includes/function2.php");

$sql="select count(userId) as cnt from cs_companydetails as cd where cd.reseller_id='".$resellerInfo['reseller_id']."' and cd.cd_reseller_rates_request=1";
$result = mysql_query($sql,$cnn_cs) or dieLog(mysql_error());
$pendingRequestRates = mysql_fetch_assoc($result);
$pendingRequestRates = $pendingRequestRates['cnt'];

require $rootdir.'smarty/libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->debugging = false;

$curtemplate = $_SESSION["gw_template"];
if(!$curtemplate) $curtemplate = "default";

if($curtemplate_overwrite)
	if(file_exists($etel_root_path."/tmpl/".$curtemplate_overwrite."/cp_header.tpl"))
		$curtemplate = $curtemplate_overwrite;
	else etelPrint($etel_root_path."/tmpl/".$curtemplate_overwrite."/cp_header.tpl Not Found");

$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";

$tmpl_dir = $etel_domain_path."/tmpl/".$curtemplate."/";

$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $tmpl_dir);
$smarty->assign("display_stat_wait", $display_stat_wait);
$smarty->assign("gw_phone_support", $_SESSION["gw_phone_support"]);
$smarty->assign("page_title", ':: '.$_SESSION["gw_title"].' Payment Gateway :: '.$pageConfig['Title']);

if(!$pageConfig['HideHeader'])
{
	require_once($rootdir.'includes/links.php');
}

?>