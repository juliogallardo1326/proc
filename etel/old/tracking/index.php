<?php 
chdir('..');
require_once("includes/function.php");
$etel_debug_mode=0;
$etel_disable_https=1;
$cAffiliateRef = preg_replace("/[^0-9A-Za-z]/",'',$_GET['Af_Ref']);
$Merchant_Ref = preg_replace("/[^0-9A-Za-z]/",'',$_GET['Mr_Ref']);
require_once("includes/dbconnection.php");


$data = array();
$data['Clicker_Ref'] = $_COOKIE['cClickerRef'];
$data['Affiliate_Ref'] = $cAffiliateRef;
$data['Merchant_Ref'] = $Merchant_Ref;
$data['host_name'] = gethostbyaddr(getRealIp());
$data['ip_address'] = getRealIp();
$data['tc_time'] = time();
$data['this_url'] = $_SERVER['HTTP_REFERER'];//$_SERVER['REQUEST_URI'];
$data['refer_url'] = rawurldecode($_GET['URL_Ref']);

if(!$data['Affiliate_Ref'])
{
	$url_info = parse_url($data['this_url']);
	parse_str($url_info['query'],$vars);
	if($vars['Af_Ref']) $data['Affiliate_Ref'] = preg_replace("/[^0-9A-Za-z]/",'',$vars['Af_Ref']);
}

etel_record_click($data);

if(!$_COOKIE['cClickerRef'] && $data['Clicker_Ref']) 
	etel_set_cookie("cClickerRef", $data['Clicker_Ref'], time() + 60*60*24*30,'/');
	
if($data['Affiliate_Ref']) 
	etel_set_cookie("cAffiliateRef", $data['Affiliate_Ref'], time() + 60*60*24*30,'/');
	
?>