<?php
require_once("includes/sessioncheck.php");
$headerInclude = "risk_smart";
require_once("includes/header.php");
require_once("../includes/fraud.class.php");

$bl_group = intval($_REQUEST['bl_group'][0]);

$fraud = new fraud_class();

if($_REQUEST['submit'] == 'Add Ban') $bl_group = 0;
if($_REQUEST['submit'] == 'Remove Ban')
{
	if(sizeof($_REQUEST['bl_group'])) 
	{
		foreach($_REQUEST['bl_group'] as $key=>$bl_group_del)
		{
			$bl_group_del = intval($bl_group_del);
			$sql = "Delete from cs_banlist where bl_group = '$bl_group_del'";
			if($bl_group_del) sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		}
	}
}
if($_REQUEST['submit'] == 'Update')
{

	foreach( $_REQUEST['bl_type'] as $key => $bl_type)
	{
		$bl_type = quote_smart($bl_type);
		$bl_data = quote_smart($_REQUEST['bl_data'][$key]);
		if($fraud->bl_types[$bl_type] && $bl_data)  $data[$bl_type]=$bl_data;
	}
	$data = $fraud->update_banlist($data,$bl_group);
	$bl_group = $data['bl_group'];
	$_REQUEST['submit'] = 'Edit Ban';
}

//$transInfo = array('ipaddress' => '151.27.53.42');
//$banInfo = $fraud->check_banlist($transInfo);
//etelPrint($transInfo);
$sql = "SELECT bl_group, group_concat( `bl_type` , '=', `bl_data`
SEPARATOR '&' ) AS banInfo from cs_banlist group by bl_group order by bl_group desc";

$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
$numBans = intval(mysql_num_rows($result));
$banInfo = array();
while($ban = mysql_fetch_assoc($result))
	$banInfo[$ban['bl_group']] = $ban;
	
beginTable();
?><select name="bl_group[]" size="15" multiple="multiple" style="width:500px;"><?
if($numBans)
{
	foreach($banInfo as $group => $data)
	{
		parse_str($data['banInfo'],$elements);
		$elcnt = 0;
		$banText ="";
		foreach($elements as $key=>$search)
			$banText.= ($banText?" and ":"").$fraud->bl_types[$key]." = '".$search."'";
		echo "<option value='$group'>$group: $banText</option>";
	}
}

?>
</select>
<input type="submit" name="submit" value="Add Ban" />
<input type="submit" name="submit" value="Edit Ban" />
<input type="submit" name="submit" value="Remove Ban" />

<?
endTable("Current Bans ($numBans)",'');
		


if($_REQUEST['submit'] == 'Edit Ban') $CurrentData = $banInfo[$bl_group];
if($_REQUEST['submit'] == 'Add Ban') $CurrentData = array('banInfo'=>'');
if($CurrentData)
{
	beginTable();
	?><table class="report" width="500px"><?

	?><tr><td></td><td>Type</td><td>Search</td></tr><?
	parse_str($CurrentData['banInfo'],$elements);
	$elcnt = 0;
	$elements['new'] = '';
	foreach($elements as $key=>$search)
	{
	
	?><tr>
		<td align="right"><?=($elcnt++>0?"AND":"")?></td>
		<td><input type="hidden" name="bl_group" value="<?=$bl_group?>" />
			<select name="bl_type[]">
			<option value="" style="font-weight:bold;"><?=($key=='new'?"Select Type":"Remove")?></option>
			<?
			foreach($fraud->bl_types as $type=>$data)
				echo "<option value='$type' ".($type==$key?"selected":"").">$data</option>\n";
			?>
			</select> 
		=</td>
		<td><input type="text" value="<?=$search?>" name="bl_data[]" /></td>
	</tr><?
	
	}
	?><tr>
	  <td align="left">Wild Card (0 or More):<br />
	    Wild Card  (1 Letter):</td>
	  <td align="left">% <br />
      _</td>
	  <td align="left"><input type="submit" value="Update" name="submit" /></td>
	</tr><?
	?></table><?
	endTable(($bl_group?"Edit Ban #$bl_group":"Create New Ban"),'');
}


include("includes/footer.php");

?>