<?php	
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$sessionService =isset($HTTP_SESSION_VARS["sessionService"])?$HTTP_SESSION_VARS["sessionService"]:"";
$sessionServiceUser =isset($HTTP_SESSION_VARS["sessionServiceUser"])?$HTTP_SESSION_VARS["sessionServiceUser"]:"";
require_once($rootdir."includes/dbconnection.php");

$forcomp = " for ".$companyInfo['companyname'];
$str_company_id = $sessionlogin;


if($_REQUEST['showheader']) $headerInclude=$_REQUEST['showheader'];

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
$smarty->assign("gateway_title", ':: '.$_SESSION["gw_title"].' Payment Gateway :: ');
$smarty->assign("page_title", $pageConfig['Title']);

if(!$pageConfig['HideHeader'])
{
	require_once($rootdir.'includes/links.php');
}
if(isProxy()) dieLog("Proxy Server - ".getRealIp(),"Proxy Server Detected. Please do not use a Proxy Server when accessing your Merchant Login.");
?>
