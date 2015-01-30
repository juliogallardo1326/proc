<?php
require_once("includes/sessioncheck.php");
$headerInclude = "risk_smart";
require_once("includes/header.php");
require_once("../includes/fraud.class.php");

$wl_ID = intval($_REQUEST['wl_ID']);
$daylimit = intval($_REQUEST['daylimit']);

$fraud = new fraud_class();

//$transInfo = array('td_process_msg' => 'rooofl','ipaddress'=>'151.27.53.42','surname'=>'asdf','name'=>'ralph','email'=>'ari@etelegate.com',);
//$watchInfo = $fraud->check_watchlist($transInfo);
//etelPrint($transInfo);
	



if($_REQUEST['submit'] == 'Add Watch') $wl_ID = 0;
if($_REQUEST['submit'] == 'Remove Watch')
{
	if($wl_ID) 
	{
		$sql = "Delete from cs_watchlist where wl_ID = '$wl_ID'";
		if($wl_ID) sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	}
}
if($_REQUEST['submit'] == 'Update')
{

	$data['wl_ID'] = $wl_ID;
	$data['wl_data'] = quote_smart($_REQUEST['wl_data']);
	$data['wl_type'] = quote_smart($_REQUEST['wl_type']);
	$data['wl_action'] = quote_smart($_REQUEST['wl_action']);
	if(!in_array($data['wl_data'],array('%','%%','%%%'))) $data = $fraud->update_watchlist($data);
	$wl_ID = $data['wl_ID'];

	//$_REQUEST['submit'] = 'Edit Watch';
}


$sql = "SELECT * from cs_watchlist";

$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");

while($watch = mysql_fetch_assoc($result))
	$cs_watchlist[$watch['wl_ID']] = $watch;
	
beginTable();
?><select name="wl_ID" size="10" style="width:500px;"><?
if(mysql_num_rows($result))
{
	foreach($cs_watchlist as $id => $data)
	{
		parse_str($data['banInfo'],$elements);
		$elcnt = 0;
		$banText ="";
		echo "<option value='$id'>$id: When ".$fraud->wl_types[$data['wl_type']]." is '".$data['wl_data']."', ".$fraud->wl_actions[$data['wl_action']]."</option>";
	}
}

?>
</select>
<input type="submit" name="submit" value="Add Watch" />
<input type="submit" name="submit" value="Edit Watch" />
<input type="submit" name="submit" value="Remove Watch" />

<?
endTable("Current Watches",'');
		
if(!$daylimit) $daylimit = 5;
$transSql = "
select 
	src, (wl_ID is not null) as used, cnt, group_concat(wl_ID SEPARATOR ', #') as wl_ID
from 
	(select 
		transactionId as tid, td_process_msg as src, count(*) as cnt
	from 
		cs_transactiondetails
	where 
		status='D' and 
		transactionDate > subdate(now(),interval 30 day) 
	group by 
		td_process_msg
	) as t
left join cs_watchlist on src like wl_data
group by
	tid
order by 
	used desc, cnt desc ";
if($_REQUEST['submit'] == 'Edit Watch') $CurrentData = $cs_watchlist[$wl_ID];
if($_REQUEST['submit'] == 'Add Watch') $CurrentData = array('banInfo'=>'');
if($CurrentData)
{
	beginTable();
	?><table class="report" width="500px"><?
	?><tr>
  <td colspan="3">Showing Last 30 Days of Transactions Decline Messages (+Frequency) <br />
	<select size="10" style="width:500px;" onchange="$('wl_type').value = 'td_process_msg'; $('wl_data').value = this.value;">
	<optgroup label='Monitored Decline Messages'>
		<?
		$result = sql_query_read($transSql) or dieLog(mysql_error()." ~ $transSql");
		$used = 1;
		while($transInfo = mysql_fetch_assoc($result))
		{
			$transInfo['val'] = preg_replace('/[^a-zA-Z0-9]+/','%',$transInfo['src']);
			$transInfo['disp'] = preg_replace('/[^a-zA-Z0-9]+/',' ',$transInfo['src']);
			if($used != $transInfo['used'])
			{
				$used = $transInfo['used'];
				echo "</optgroup><optgroup label='UnMonitored Decline Messages'>\n";
			}
			echo "<option value='%".$transInfo['val']."%' style='".($used?"font-weight:bold;":"")."'>".$transInfo['disp']." (".$transInfo['cnt'].")".($transInfo['wl_ID']?" - Monitored by Watch #".$transInfo['wl_ID']:"")."</option>\n";
		}
		?>
	</optgroup>
	</select>
	<input type="hidden" name="wl_ID" value="<?=$wl_ID?>" />	</td></tr><?

	?><tr><td>Type</td><td>Search</td><td>Action</td></tr><?
	?><tr><td>
		<select name="wl_type" id="wl_type" />
			<?
			foreach($fraud->wl_types as $type=>$data)
				echo "<option value='$type' ".($type==$CurrentData['wl_type']?"selected":"").">$data</option>\n";
			?>
		</select>
	</td><td>
		<input type="text" name="wl_data" id="wl_data" value="<?=$CurrentData['wl_data']?>" />
	</td><td>
		<select name="wl_action" id="wl_action" />
			<?
			foreach($fraud->wl_actions as $type=>$data)
				echo "<option value='$type' ".($type==$CurrentData['wl_action']?"selected":"").">$data</option>\n";
			?>
		</select>
	</td></tr><?

	?><tr>
	  <td align="left">Wild Card (0 or More):<br />
	    Wild Card  (1 Letter):</td>
	  <td align="left">% <br />
      _</td>
	  <td align="left"><input type="submit" value="Update" name="submit" /></td>
	</tr><?
	?></table>
<?
	endTable(($wl_ID?"Edit Watch #$wl_ID":"Create New Watch"),'');
}


include("includes/footer.php");

?>