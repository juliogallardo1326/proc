<?
$etel_debug_mode = 0;

require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/risk_report.php');

$t = time();
echo (time()-$t) . " Ready to Report<BR>";
$report = new risk_report_main();

if(isset($_GET['install']))
	$report->install_risk_reporting();

echo (time()-$t) . " Running Report<BR>";
$report->run_cron();

echo (time()-$t) . " seconds to process";
	
?>