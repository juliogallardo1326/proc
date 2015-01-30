<?
require_once("../admin/includes/sessioncheck.php");

$etel_debug_mode= 1;

require_once("ivr.class.php");
require_once("lookup.class.php");
require_once('../includes/dbconnection.php');
require_once('../includes/transaction.class.php');

$_REQUEST = array_merge($_POST,$_GET);


$log = new ivr_class();

$page_sums = $log->get_page_summary(strtotime("1/1/1980"),strtotime("12/31/2030"));

echo "<center><table width='800px' cellspacing=0 cellpadding=0>";
echo "
	<tr>
		<td><b>Page</b></td>
		<td><b>Count</b></td>
	</tr>
";

$j = 0;
$cols = array('#E6E6E6','#EEEEEE');

foreach($page_sums as $index=>$page_sum)
{
	$bgcolor=$cols[(++$j)%2];
	
	echo "
		<tr bgcolor='$bgcolor'>
			<td>" . $page_sum['iv_page_name'] . "</td>
			<td>" . $page_sum['views'] . "</td>
		</tr>
	";
}

echo "</table></center>";


$sums = $log->get_summary(strtotime("1/1/1980"),strtotime("12/31/2030"));



echo "<center><table width='800px'>";
echo "
	<tr>
		<td><b>Call ID</b></td>
		<td><b>Start</b></td>
		<td><b>End</b></td>
		<td><b>Phone Number</b></td>
		<td><b>Duration</b></td>
	</tr>
";

foreach($sums as $index => $sum)
{
	echo "
		<tr>
			<td><a href='cs_log_detail.php?callid=" . $sum['iv_call_id'] . "'>" . $sum['iv_call_id'] . "</a></td>
			<td>" . $sum['call_start'] . "</td>
			<td>" . $sum['call_end'] . "</td>
			<td>" . $sum['iv_phone'] . "</td>
			<td>" . $sum['call_duration'] . "</td>
		</tr>
	";
}

echo "</table></center>";


?>