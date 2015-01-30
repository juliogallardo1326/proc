<?
$etel_debug_mode = 0;
include("includes/sessioncheck.php");

require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/risk_report.php');

//$company_id = isset($_GET['company_id']) ? $_GET['company_id'] : (isset($_POST['company_id']) ? $_POST['company_id'] : "");
//$custom_report = isset($_GET['custom_report']) ? $_GET['custom_report'] : (isset($_POST['custom_report']) ? $_POST['custom_report'] : "");

if(isset($_SESSION['company_id']))	$company_id = $_SESSION['company_id']; else exit();
if(isset($_SESSION['custom_report'])) $custom_report = $_SESSION['custom_report'];

$report = new risk_report_main($custom_report);

if(is_array($company_id))
	$report->display_risk_report($company_id);
else
	if($company_id != "")
		$report->display_risk_report(array($company_id));

unset($_SESSION['company_id']);

?>