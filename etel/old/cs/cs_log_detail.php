<?

require_once("../admin/includes/sessioncheck.php");

$etel_debug_mode= 1;

require_once("ivr.class.php");
require_once("lookup.class.php");
require_once('../includes/dbconnection.php');
require_once('../includes/transaction.class.php');

$_REQUEST = array_merge($_POST,$_GET);


$log = new ivr_class();

$details = $log->get_call_details($_REQUEST['callid']);

/*
            [iv_call_id] => 13073306
            [iv_datetime] => 2006-08-03 14:02:03
            [iv_phone] => 3144508965
            [iv_page_name] => angel_greeting
            [iv_query] => a:5:{s:12:"CallDuration";s:1:"1";s:8:"CallGUID";s:8:"13073306";s:4:"page";s:2:"10";s:12:"subscriberID";s:5:"32235";s:8:"CallerID";s:10:"3144508965";}
            [iv_duration] => 1

*/

$callid = $details[1]['iv_call_id'];
$phone = $details[1]['iv_phone'];

$today = $log->get_calls_today($phone);

$add_calls = $log->get_phone_summary($phone);

echo "<p><a href='cs_log.php'>View All Calls</a></p>";

echo "<center><h3>$phone ($today)</h3><table width='800px'>";
echo "
	<tr>
		<td><b>Call ID</b></td>
		<td><b>Start</b></td>
		<td><b>End</b></td>
		<td><b>Phone Number</b></td>
		<td><b>Duration</b></td>
	</tr>
";

foreach($add_calls as $index => $sum)
	if($sum['iv_call_id'] != $callid)
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
	else
		echo "
			<tr>
				<td><b>" . $sum['iv_call_id'] . "</b></td>
				<td>" . $sum['call_start'] . "</td>
				<td>" . $sum['call_end'] . "</td>
				<td>" . $sum['iv_phone'] . "</td>
				<td>" . $sum['call_duration'] . "</td>
			</tr>
		";
	

echo "</table></center>";


echo "<center><h3>$callid</h3><table width='800px'>";
echo "
	<tr>
		<td><b>Date</b></td>
		<td><b>Page</b></td>
		<td><b>Query</b></td>
		<td><b>Duration</b></td>
	</tr>
";

foreach($details as $index=>$detail)
{
	$detail['iv_query'] = stripslashes($detail['iv_query']);
	$detail['iv_query'] = unserialize($detail['iv_query']);
	$detail['iv_query'] = "<pre>" . print_r($detail['iv_query'],true) . "</pre>";
	
	echo "
		<tr>
			<td valign='top'>" . $detail['iv_datetime'] . "</td>
			<td valign='top'>" . $detail['iv_page_name'] . "</td>
			<td valign='top'>" . $detail['iv_query'] . "</td>
			<td valign='top'>" . $detail['iv_duration'] . "</td>
		</tr>
	";
}

echo "</table></center>";


?>