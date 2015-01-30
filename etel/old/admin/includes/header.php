<?php 
$rootdir = "../";


require_once($rootdir."includes/dbconnection.php");
$bank_sql_limit="";
$bank_sql_bank_id="";

$adminConfig = @unserialize( gzuncompress($adminInfo['li_config']));

if ($adminInfo['li_level'] != 'full') $bank_sql_limit="ERROR";
if ($adminInfo['li_level'] != 'full') $bank_sql_bank_id="ERROR";

if($etel_singleview_allowed) $pageConfig['AllowBank'] = $etel_singleview_allowed;

if(!$pageConfig['SingleViewAllowed'] && $adminInfo['li_singleview_allow'])
{
	$allowed_pages = explode("|",$adminInfo['li_singleview_allow']);
	$baseName = basename($_SERVER['SCRIPT_FILENAME']);
	if(!in_array($baseName,$allowed_pages)) dieLog("You may not view this page");

	unset($baseName,$allowed_pages);
}


		

if ($adminInfo['li_level'] == 'bank') 
{
	$bank_id = $adminInfo['li_bank'];
	$bank_sql_bank_id = " and bank_id = ".$adminInfo['li_bank'];
	$result=sql_query_read("select bank_name from `cs_bank` WHERE bank_id='$bank_id'");
	$bankName = mysql_fetch_assoc($result);
	$for_bank = " for '".$bankName['bank_name']."'";
	$bank_sql_limit = " AND bank_Creditcard='".$adminInfo['li_bank']."' ";
	
	if($adminInfo['li_type'] == 'credit') $bank_sql_limit=" and bank_Creditcard = '$bank_id' ";
	if($adminInfo['li_type'] == 'check') $bank_sql_limit=" and bank_check = '$bank_id' ";
}	

if ($adminInfo['li_level'] == 'gateway') 
{
	$li_gw_ID = $adminInfo['li_gw_ID'];
	//$bank_sql_bank_id = " and gateway_id = ".$adminInfo['li_gw_ID'];
	$result=sql_query_read("select bank_name from `cs_bank` WHERE bank_id='$bank_id'");
	$bankName = mysql_fetch_assoc($result);
	$for_bank = " for '".$bankName['bank_name']."'";
	$bank_sql_limit = " and gateway_id = ".$adminInfo['li_gw_ID'];
	$bank_sql_bank_id="";
	
}

if($allowBank) $pageConfig['AllowBank'] = $allowBank;

if($pageConfig['AllowBank']!=true && $adminInfo['li_level'] == 'bank') dieLog("Bank ".$adminInfo['username']." tried to view Page.","You may not view this page.");

if($noHeaderOutput) $pageConfig['HideHeader'] = $noHeaderOutput;

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
	
	
	require_once($rootdir."includes/links.php");
	if($profitMsg) print "<font size='1'>$profitMsg</font><BR>";

}

	
?>