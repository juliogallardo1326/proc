<?php 

include("includes/sessioncheck.php");
$pageConfig['Title'] = 'Entity Manager';

$loginas = intval($_REQUEST["loginas"]);
if($loginas)
{
	$etel_debug_mode=0;
	require_once("../includes/dbconnection.php");
	$_SESSION["gw_admin_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_hash"]."|Admin|".$_SESSION['gw_id']."|editCompanyProfileAccess.php?entity_id=".$_REQUEST['entity_id']);
	$_SESSION["loginredirect"]="None";
	$_SESSION["userType"] = quote_smart(ucfirst($_REQUEST['type']));
	$_SESSION["gw_user_username"] = quote_smart($_REQUEST['username']);
	$_SESSION["gw_user_password"] = '';
	$_SESSION["gw_user_hash"] = quote_smart($_REQUEST['hash']);
	if($_SESSION["userType"]=='Reseller') echo "<script>document.location='../reseller/ledger.php'</script>";
	else echo "<script>document.location='../ledger.php'</script>";
	die();
}

$headerInclude="companies";
include("includes/header.php");



$data = JSON_get_data(array('func'=>'getEntitySearchOptions'));

$json = new Services_JSON();
$output = $json->encode($data);
?>
<script language="javascript" src="<?=$rootdir?>/scripts/dynosearch.js"></script>

<div id="en_search" class="report" align="left" style="background-image:url(<?=$tmpl_dir?>images/row1_bk.png)"></div>
<div id="en_status" class="report" style="background-image:url(<?=$tmpl_dir?>images/row2_bk.png)"></div>
<div id="en_results" style="background-image:url(<?=$tmpl_dir?>images/row3_bk.png)"></div>

<script language="javascript">
var en_search_options = <?=$output?>;
en_build_search();
</script>

<?
include("includes/footer.php");
?>
