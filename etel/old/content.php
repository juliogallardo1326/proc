<?php 
	if($_REQUEST['gw_id']) $gateway_db_select = intval($_REQUEST['gw_id']);
	if(!$_POST['url1']) $_POST['url1'] = "http://";
	require_once("includes/indexheader.php");
	$smarty->assign("etel_hear_about_us", $etel_hear_about_us);
	$smarty->assign("etel_timezone", $etel_timezone);
	$out = @$smarty->display($_GET['show'].".tpl");

?>