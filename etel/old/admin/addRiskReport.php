<?php
$allowBank=true;
require_once("includes/sessioncheck.php");


$headerInclude = "riskassesment";
require_once("includes/header.php");
//print_r($_POST);
$sql_options = "";
$sql_comparison = "";

$global_row = 1;
function gen_row($change = false)
{
	global $global_row;
	if($change) $global_row = 3-$global_row;
	return $global_row;
}
// left join cs_companydetails as cd on t.userId = cd.userId
$trans_table = '
cs_companydetails as cd
left join cs_transactiondetails as t on cd.userId = t.userId
left join cs_transactiondetails as td on [transactionDate] 
and t.`transactionId` = td.`transactionId` 
left join  cs_transactiondetails as tdc ON [cancellationDate] 
and t.`transactionId` = tdc.`transactionId`';

$company_table = 'cs_companydetails as cd ';

$sql_options['total']['sql'] = " CONCAT(COUNT(td.`amount`),'|',SUM(td.`amount`),'|tper') as 'Total' "; 
$sql_options['total']['option'] = "Total"; 
$sql_options['sales']['sql'] = " CONCAT(SUM(td.`status` = 'A'),'|',SUM((td.`status` = 'A') * td.`amount`),'|per') AS Sales "; 
$sql_options['sales']['option'] = "Sales"; 
$sql_options['unique']['sql'] = " CONCAT(SUM(td.`status` = 'A' AND td.`td_non_unique`=0),'|',SUM((td.`status` = 'A' AND td.`td_non_unique`=0) * td.`amount`),'|per') AS 'Unique Sales' "; 
$sql_options['unique']['option'] = "Unique Sales"; 
$sql_options['nonunique']['sql'] = " CONCAT(SUM(td.`status` = 'A' AND td.`td_non_unique`<>0),'|',SUM((td.`status` = 'A' AND td.`td_non_unique`<>0) * td.`amount`),'|per') AS 'Non-Unique Sales' "; 
$sql_options['nonunique']['option'] = "Non-Unique Sales"; 
$sql_options['decline']['sql'] = " CONCAT(SUM(td.`status` = 'D' AND td.`td_bank_recieved` = 'yes'),'|',SUM((td.`status` = 'D' AND td.`td_bank_recieved` = 'yes') * td.`amount`),'|tper') AS Declines "; 
$sql_options['decline']['option'] = "Declines"; 
$sql_options['uniquedecline']['sql'] = " CONCAT(SUM(td.`status` = 'D' AND td.`td_non_unique`=0 AND td.`td_bank_recieved` = 'yes'),'|',SUM((td.`status` = 'D' AND td.`td_non_unique`=0 AND td.`td_bank_recieved` = 'yes') * td.`amount`),'|tper') AS UniqueDecline "; 
$sql_options['uniquedecline']['option'] = "UniqueDecline"; 

$sql_options['creditcard']['sql'] = " CONCAT(SUM(td.`status` = 'A' AND td.`checkorcard`='H'),'|',SUM((td.`status` = 'A' AND td.`checkorcard`='H') * td.`amount`),'|per') AS 'Credit Card' "; 
$sql_options['creditcard']['option'] = "Credit Card";
$sql_options['check']['sql'] = " CONCAT(SUM(td.`status` = 'A' AND td.`checkorcard`='C'),'|',SUM((td.`status` = 'A' AND td.`checkorcard`='C') * td.`amount`),'|per') AS 'Check' "; 
$sql_options['check']['option'] = "Check";
$sql_options['web900']['sql'] = " CONCAT(SUM(td.`status` = 'A' AND td.`checkorcard`='W'),'|',SUM((td.`status` = 'A' AND td.`checkorcard`='W') * td.`amount`),'|per') AS 'Web900' "; 
$sql_options['web900']['option'] = "Web900";

$sql_options['refund']['sql'] = " CONCAT(SUM(tdc.`cancelstatus` = 'Y'),'|',SUM((tdc.`cancelstatus` = 'Y') * tdc.`amount`),'|per') AS Refunds "; 
$sql_options['refund']['option'] = "Refunds"; 
$sql_options['chargebacks']['sql'] = " CONCAT(SUM(tdc.`td_is_chargeback`),'|',SUM((tdc.`td_is_chargeback`) * tdc.`amount`),'|per') AS Chargebacks "; 
$sql_options['chargebacks']['option'] = "Chargebacks"; 
$sql_options['chargebacksVisa']['sql'] = " CONCAT(SUM(tdc.`td_is_chargeback` AND tdc.`cardtype`='visa'),'|',SUM((tdc.`td_is_chargeback` AND tdc.`cardtype`='visa') * tdc.`amount`),'|per') AS 'Chrgback - Visa' "; 
$sql_options['chargebacksVisa']['option'] = "Chrgback - Visa"; 
$sql_options['chargebacksMastercard']['sql'] = " CONCAT(SUM(tdc.`td_is_chargeback` AND tdc.`cardtype`='master'),'|',SUM((tdc.`td_is_chargeback` AND tdc.`cardtype`='master') * tdc.`amount`),'|per') AS 'Chrgback - MC' "; 
$sql_options['chargebacksMastercard']['option'] = "Chrgback - MC"; 
 
$sql_options['spider']['sql'] = " CONCAT(0,'|',(select MAX(cs.`cs_spider_report_score`) from cs_company_sites as cs where [site_sql]),'|scr') AS 'Spider' "; 
$sql_options['spider']['option'] = "Spider"; 
 
$customerservice_sql = "select group_concat(issue separator '\n') from (SELECT concat(count(tickets_issue ),' x ',tickets_issue) as issue, count(tickets_issue ) as cnt FROM `cs_transactiondetails` left join `tickets_tickets` on `td_transactionId` =`transactionId` where tickets_issue is not null and [company_userId] group by tickets_issue) as t group by null order by cnt desc";
 
$sql_options['customerservice']['sql'] = " CONCAT(0,'|',($customerservice_sql),'|str') AS 'Customer Service' "; 
$sql_options['customerservice']['option'] = "Customer Service"; 
 

foreach($sql_options as $key=>$option)
$data_options .="<option value='$key'>$option[option]</option>\n";


$sql_destination['max_sales']['sql'] = " cd.cd_max_volume "; 
$sql_destination['max_sales']['option'] = "Monthly Sales Limit"; 
$sql_destination['max_sales']['type'] = "apm"; 

foreach($sql_destination as $key=>$option)
$data_destination .="<option value='$key'>$option[option]</option>\n";


$sql_destination_value_type['p']['sql'] = ""; 
$sql_destination_value_type['p']['option'] = " Percent"; 
$sql_destination_value_type['p']['option2'] = "%"; 
$sql_destination_value_type['apm']['sql'] = ""; 
$sql_destination_value_type['apm']['option'] = " Amount Per Month"; 
$sql_destination_value_type['apm']['option2'] = " Amount Per Month"; 

foreach($sql_destination_value_type as $key=>$option)
$data_destination_value_type .="<option value='$key'>$option[option]</option>\n";


$preset_reports['amountPerMonth']['title'] = "Attempted Over Limit";
$preset_reports['amountPerMonth']['desc'] = "Alert when monthly volume approaching the Monthly Limit";
$preset_reports['amountPerMonth']['type'] = "apm";
$preset_reports['amountPerMonth']['signif'] = 0;
$preset_reports['amountPerMonth']['default'] = array(70,80,90,100);
$preset_reports['amountPerMonth']['s_sql'] = $sql_options['sales']['sql'];
$preset_reports['amountPerMonth']['d_sql'] = $sql_destination['max_sales']['sql'];
$preset_reports['amountPerMonth']['s_option'] = $sql_options['sales']['option'];

$preset_reports['declinesAboveLimit']['title'] = "Declines Above Limit";
$preset_reports['declinesAboveLimit']['desc'] = "Alert when Declines are above Percentage";
$preset_reports['declinesAboveLimit']['type'] = "p";
$preset_reports['declinesAboveLimit']['signif'] = 4;
$preset_reports['declinesAboveLimit']['default'] = array(15,25,35,45);
$preset_reports['declinesAboveLimit']['s_sql'] = $sql_options['decline']['sql'];
$preset_reports['declinesAboveLimit']['s_option'] = $sql_options['decline']['option'];

$preset_reports['uniqueDeclinesAboveLimit']['title'] = "Unique Dec. Above Limit";
$preset_reports['uniqueDeclinesAboveLimit']['desc'] = "Alert when Unique Declines are above Percentage";
$preset_reports['uniqueDeclinesAboveLimit']['type'] = "p";
$preset_reports['uniqueDeclinesAboveLimit']['signif'] = 2;
$preset_reports['uniqueDeclinesAboveLimit']['default'] = array(17,18,19,20);
$preset_reports['uniqueDeclinesAboveLimit']['s_sql'] = $sql_options['uniquedecline']['sql'];
$preset_reports['uniqueDeclinesAboveLimit']['s_option'] = $sql_options['uniquedecline']['option'];

if($adminInfo['li_level'] != 'bank' || $adminInfo['li_type'] == 'credit')
{
	$preset_reports['chargebacksVisa']['title'] = "Chargebacks - Visa";
	$preset_reports['chargebacksVisa']['desc'] = "Alert when Visa Transaction Chargebacks are over a limit";
	$preset_reports['chargebacksVisa']['type'] = "p";
	$preset_reports['chargebacksVisa']['signif'] = 0;
	$preset_reports['chargebacksVisa']['default'] = array(.5,1,1.5,2.0);
	$preset_reports['chargebacksVisa']['s_sql'] = $sql_options['chargebacksVisa']['sql'];
	$preset_reports['chargebacksVisa']['s_option'] = $sql_options['chargebacksVisa']['option'];
	
	$preset_reports['chargebacksMastercard']['title'] = "Chargebacks - MC";
	$preset_reports['chargebacksMastercard']['desc'] = "Alert when Mastercard Transaction Chargebacks are over a limit";
	$preset_reports['chargebacksMastercard']['type'] = "p";
	$preset_reports['chargebacksMastercard']['signif'] = 0;
	$preset_reports['chargebacksMastercard']['default'] = array(.25,.5,.75,1);
	$preset_reports['chargebacksMastercard']['s_sql'] = $sql_options['chargebacksMastercard']['sql'];
	$preset_reports['chargebacksMastercard']['s_option'] = $sql_options['chargebacksMastercard']['option'];
}
$preset_reports['refunds']['title'] = "Refunds over limit";
$preset_reports['refunds']['desc'] = "Alert when Refunds are over a limit";
$preset_reports['refunds']['type'] = "p";
$preset_reports['refunds']['signif'] = 5;
$preset_reports['refunds']['default'] = array(5,6,7,8);
$preset_reports['refunds']['s_sql'] = $sql_options['refund']['sql'];
$preset_reports['refunds']['s_option'] = $sql_options['refund']['option'];

$preset_reports['nonunique']['title'] = "Non-Unique Transactions";
$preset_reports['nonunique']['desc'] = "Alert when Non-Unique Transactions are over a limit";
$preset_reports['nonunique']['type'] = "p";
$preset_reports['nonunique']['signif'] = 5;
$preset_reports['nonunique']['default'] = array(15,20,25,30);
$preset_reports['nonunique']['s_sql'] = $sql_options['nonunique']['sql'];
$preset_reports['nonunique']['s_option'] = $sql_options['nonunique']['option'];

$preset_reports['unique']['title'] = "Unique Transactions";
$preset_reports['unique']['desc'] = "Alert when Unique Approved Transactions are over a limit";
$preset_reports['unique']['type'] = "p";
$preset_reports['unique']['signif'] = 5;
$preset_reports['unique']['default'] = array(0,0,0,0);
$preset_reports['unique']['s_sql'] = $sql_options['unique']['sql'];
$preset_reports['unique']['s_option'] = $sql_options['unique']['option'];

$preset_reports['spider']['title'] = "Spider Score";
$preset_reports['spider']['desc'] = "Alert when website has a high Spider Score";
$preset_reports['spider']['type'] = "scr";
$preset_reports['spider']['signif'] = -1;
$preset_reports['spider']['default'] = array(0,0,0,0);
$preset_reports['spider']['s_sql'] = $sql_options['spider']['sql'];
$preset_reports['spider']['s_option'] = $sql_options['spider']['option'];

$preset_reports['customerservice']['title'] = "Customer Support";
$preset_reports['customerservice']['desc'] = "Report on Customer Service";
$preset_reports['customerservice']['type'] = "str";
$preset_reports['customerservice']['signif'] = -1;
$preset_reports['customerservice']['default'] = -1;
$preset_reports['customerservice']['s_sql'] = $sql_options['customerservice']['sql'];
$preset_reports['customerservice']['s_option'] = $sql_options['customerservice']['option'];

//$preset_reports['affiliate']['title'] = "Affiliate Transactions";
//$preset_reports['affiliate']['desc'] = "Report on Affiliate (vs Non Affiliate) Sales";
//$preset_reports['affiliate']['type'] = "n";
//$preset_reports['affiliate']['default'] = -1;
//$preset_reports['affiliate']['s_sql'] = "";

if($adminInfo['li_level'] != 'bank')
{
	$preset_reports['creditcard']['title'] = "CreditCard Transactions";
	$preset_reports['creditcard']['desc'] = "Report on CreditCard Sales";
	$preset_reports['creditcard']['type'] = "n";
	$preset_reports['creditcard']['default'] = -1;
	$preset_reports['creditcard']['s_sql'] = $sql_options['creditcard']['sql'];
	$preset_reports['creditcard']['s_option'] = $sql_options['creditcard']['option'];

	$preset_reports['check']['title'] = "Check Transactions";
	$preset_reports['check']['desc'] = "Report on Check Sales";
	$preset_reports['check']['type'] = "n";
	$preset_reports['check']['default'] = -1;
	$preset_reports['check']['s_sql'] = $sql_options['check']['sql'];
	$preset_reports['check']['s_option'] = $sql_options['check']['option'];

	$preset_reports['web900']['title'] = "Web900 Transactions";
	$preset_reports['web900']['desc'] = "Report on Web900 Sales";
	$preset_reports['web900']['type'] = "n";
	$preset_reports['web900']['default'] = -1;
	$preset_reports['web900']['s_sql'] = $sql_options['web900']['sql'];
	$preset_reports['web900']['s_option'] = $sql_options['web900']['option'];
}
	
//$preset_reports['amountPerTransaction']['title'] = "Amount Per Transactions";
//$preset_reports['amountPerTransaction']['desc'] = "Report on transactions attempted above the charge Limit";
//$preset_reports['amountPerTransaction']['type'] = "n";
//$preset_reports['amountPerTransaction']['default'] = -1;
//$preset_reports['amountPerTransaction']['s_sql'] = $sql_options['sales']['sql'];
//$preset_reports['amountPerTransaction']['s_option'] = $sql_options['sales']['option'];


//$rp_period_options = func_get_enum_values('cs_reports','rp_period');
//$rp_severity_options = func_get_enum_values('cs_reports','rp_severity');
//$rp_projected_severity_options = func_get_enum_values('cs_reports','rp_projected_severity');

$rp_ID = $_POST['rp_ID'];
$rp_content = $_POST['rp_content'];
$mode = "add";
	
if($_POST['Submit'] == "Remove Report")
{
	$sql="DELETE FROM `cs_reports` WHERE `rp_ID` = '$rp_ID'";
	mysql_query($sql) or dieLog(mysql_error." ~ $sql");
	$_POST = "";
}
if($_POST['Submit'] == "Add Option")
{
	$_POST['rp_data'][count($_POST['option_id'])]=1;
	$_POST['option_id'][]= count($_POST['option_id']);

}
if($_POST['Submit'] == "Submit")
{
	$sql="SELECT `rp_bank_id` from `cs_reports` WHERE `rp_ID` = '$rp_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error." ~ $sql");
	$rp_info = mysql_fetch_assoc($result);
	
	
	$rp_title = $_POST['rp_title'];
	$rp_period = $_POST['rp_period'];
	$rp_severity = $_POST['rp_severity'];
	$rp_projected_severity = $_POST['rp_projected_severity'];
	$rp_POST = serialize($_POST);
	
	$report_str = "<strong>$rp_title</strong> includes: \n  <strong>Reporting</strong>:\n";
	
	$sql_source_str = "SELECT ".$sql_options['total']['sql'].", ".$sql_options['sales']['sql'];
	$report_str .= "    Report on ".$sql_options['total']['option']." Transactions.\n";
	$report_str .= "    Report on Approved ".$sql_options['sales']['option']." Transactions.\n";
			
	foreach($preset_reports as $key=>$pre_rep) { 
		if(!$_POST['enable_'.$key]) continue;
		$format_dollar = ($pre_rep['type']=='a'?"$":"");
		$format_percent = ($pre_rep['type']=='a'?"":"%");
			
		if($preset_reports[$key]['s_sql'])
			if(!strpos($sql_source_str, $preset_reports[$key]['s_sql'])) 
			{		
				$sql_source_str.= ", ".$preset_reports[$key]['s_sql'];
			}
			
		if($preset_reports[$key]['type'] == 'n') $report_str .= "    ".$preset_reports[$key]['desc']."\n";
		else 
		{
			$report_alerts_str .= "    ".$preset_reports[$key]['desc']." (".$format_dollar.$_POST['goal_'.$key].$format_percent.").\n";
			
			if($preset_reports[$key]['s_sql']){
				if(!$sql_destination_str) $sql_destination_str = "SELECT ";
				else $sql_destination_str .= ", ";
				
				$d_sql_str = '-1';
				if ($preset_reports[$key]['d_sql']) $d_sql_str = $preset_reports[$key]['d_sql'];
				$sql_destination_str .= "CONCAT('".$_POST['mild_'.$key]."|".$_POST['moderate_'.$key]."|".$_POST['severe_'.$key]."|".$_POST['goal_'.$key]."|".$preset_reports[$key]['type']."|',$d_sql_str,'|".$preset_reports[$key]['signif']."') as '".$preset_reports[$key]['s_option']."' ";
			}
			
		}
	}
	
		
	if(is_array($_POST['option_id']))
		foreach($_POST['option_id'] as $key=>$op_id)
		{
			$rp_data = $_POST['rp_data'][$key];
			if(!$rp_data) continue;
			
		
			$topic = $sql_options[$rp_data]['option'];
			
			$destination = $_POST['destination'][$key];
			$destination_value = $_POST['destination_value'][$key];
			$destination_value_type = $_POST['destination_value_type'][$key];
			$compare = $_POST['compare'][$key];
			
			
			if(!strpos($sql_source_str, $sql_options[$rp_data]['sql'])) 
			{		
				$report_str .= "    $topic Percent and Total.\n";		
				$sql_source_str.= ", ".$sql_options[$rp_data]['sql'];
			}
			if($compare != 'none')
			{
				if(!$sql_destination_str) $sql_destination_str = "SELECT ";
				else $sql_destination_str .= ", ";
											
				if($destination)
				{
					$destination_str = $sql_destination[$destination]['option'];
					$sql_destination_str .= "CONCAT(".$sql_destination[$destination]['sql'].",'|".$sql_destination[$destination]['type']."|$compare') as '".$sql_options[$rp_data]['option']."' ";
					//$sql_destination_str .= "'".$sql_destination[$destination]['type']."' as '".$sql_options[$rp_data]['option']."_type', ";
				}
				else
				{
					$destination_str = formatMoney($destination_value).$sql_destination_value_type[$destination_value_type]['option2'];
					$sql_destination_str .= "'$destination_value|$destination_value_type|$compare' as '".$sql_options[$rp_data]['option']."' ";
					//$sql_destination_str .= "'$destination_value_type' as '".$sql_options[$rp_data]['option']."_type', ";
				}
				
				//$sql_destination_str .= "'$compare' as '".$sql_options[$rp_data]['option']."_compare' ";
				
				if($compare=='tpgt') {$totalOrPercent = "Total Percent"; $comparison = "Greater Than";}
				if($compare=='tplt') {$totalOrPercent = "Total Percent"; $comparison = "Less Than";}
				if($compare=='apgt') {$totalOrPercent = "Approved Percent"; $comparison = "Greater Than";}
				if($compare=='aplt') {$totalOrPercent = "Approved Percent"; $comparison = "Less Than";}
				if($compare=='mtgt') {$totalOrPercent = "Monthly Total"; $comparison = "Greater Than";}
				if($compare=='mtlt') {$totalOrPercent = "Monthly Total"; $comparison = "Less Than";}
				$report_alerts_str .= "    Alert when $topic $totalOrPercent is $comparison $destination_str.\n";
			}
	
		}
	if(!$sql_destination_str) $sql_destination_str = "SELECT 1 ";
	$sql_destination_str.= " from $company_table ";

	$sql_source_str.= " from $trans_table ";
	
	if($report_alerts_str) $report_str .= "  <strong>Alerts</strong>: \n".$report_alerts_str;
	
	
	$sql = "REPLACE INTO `cs_reports` SET 
`rp_ID` = '$rp_ID',
`rp_title` = '$rp_title',
`rp_content` = '$report_str',
`rp_period` = '$rp_period',
`rp_source_query` = '".addslashes($sql_source_str)."',
`rp_destination_query` = '".addslashes($sql_destination_str)."',
`rp_notify_severity` = '$rp_notify_severity',
`rp_notify_email` = '',
`rp_bank_id` = '".$adminInfo['li_bank']."',
`rp_POST` = '$rp_POST'";

	if ($adminInfo['li_level'] != 'bank' || $rp_info['rp_bank_id']==$adminInfo['li_bank'] || !$rp_info['rp_bank_id']) 
		$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
	
	$rp_ID = mysql_insert_id();
	
	$_POST = false;
	$_POST['Submit'] = "Edit Report";
}
if($_POST['Submit'] == "Edit Report")
{
	$sql="SELECT `rp_POST`,`rp_content` from `cs_reports` WHERE `rp_ID` = '$rp_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error." ~ $sql");
	$rp_info = mysql_fetch_assoc($result);
	$_POST=unserialize($rp_info['rp_POST']);
	$_POST['Submit'] = "";
	$_POST['rp_ID'] = $rp_ID;
	if ($rp_info) $mode = "edit";
	$rp_content = $rp_info['rp_content'];
}
if($_POST['Submit'] == "Add New Report")
{
	$rp_ID=-1;
	$_POST = "";
}
if(!$_POST['option_id']) $_POST['option_id'] = array(0);
if(!$_POST['rp_title']) $_POST['rp_title'] = "New Report ".date("Y-m-d");
// List Reports

ob_start();
?>

<select name=" rp_ID" size="5">
  <?php func_fill_combo_conditionally("select rp_ID, rp_title from `cs_reports` ORDER BY `rp_title` ASC ",$_POST['rp_ID'],$cnn_cs); ?>
</select>
<BR>
<input name="Submit" type="submit" value="Add New Report">
<input name="Submit" type="submit" value="Edit Report">
<input name="Submit" type="submit" value="Remove Report">
<?php 
$addRemoveReports= ob_get_contents();
ob_end_clean();
doTable($addRemoveReports,"Available Reports","addRiskReport.php");


// Add Report

ob_start();
?>
<script language="javascript">
function updateDestination(value,id)
{
	//document.getElementById('destination_value_type_'+id).disabled=(value!='');
	//document.getElementById('destination_value_'+id).disabled=(value!='');
	//document.getElementById('compare_div1_'+id).style.visibility=(value==''?'visible':'hidden');
	document.getElementById('compare_div3_'+id).style.visibility=(value==''?'visible':'hidden');
}
function updateComparison(value,id)
{
	if(value!='none'&&value!='')
	{
		document.getElementById('compare_div1_'+id).style.visibility='visible';
		document.getElementById('compare_div2_'+id).style.visibility='visible';
		updateDestination(document.getElementById('destination_'+id).value,id);
	}
	else
	{
		document.getElementById('compare_div1_'+id).style.visibility='hidden';
		document.getElementById('compare_div2_'+id).style.visibility='hidden';
		document.getElementById('compare_div3_'+id).style.visibility='hidden';
	}
}

</script>
<table width="700"  border="1" cellspacing="2" cellpadding="2" class="report">
  <tr class="header">
    <th width="200" scope="col">Report Details: </th>
    <th width="230" colspan="2" scope="col"><input name="rp_ID" type="hidden" id="rp_ID" value="<?=$_POST['rp_ID']?>"></th>
    <th width="230" colspan="2" scope="col">&nbsp;</th>
  </tr>
  <tr class="row<?=gen_row(1)?>">
    <td>Report Title </td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2"><input name="rp_title" type="text" class="levels" id="rp_title" size="25" value="<?=$_POST['rp_title']?>"></td>
  </tr>
  <?php if ($rp_content) { ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Report Details: <input name="rp_content" type="hidden" id="rp_ID" value="<?=$rp_content?>"></td>
    <td colspan="4"><pre><?=$rp_content?></pre></td>
  </tr>
  <?php } ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Period</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2"><select name="rp_period" id="rp_period" class="levels">
      <option value="30" <?=$_POST['rp_period']==30?"selected":""?> >Monthly</option>
      <option value="1" <?=$_POST['rp_period']==1?"selected":""?> >Daily</option>
      <option value="7" <?=$_POST['rp_period']==7?"selected":""?> >Weekly</option>
      <option value="365" <?=$_POST['rp_period']==365?"selected":""?> >Yearly</option>
      </select> 
    </td>
  </tr>
  <tr class="header">
    <th scope="col">Report on: </th>
    <th colspan="4" scope="col">Choose Severity Levels </th>
  </tr>
  <tr class="row<?=gen_row(1)?>">
    <td>Enable</td>
    <td width="125">Mild</td>
    <td width="125">Moderate</td>
    <td width="125">Severe</td>
    <td width="125">Limit Point </td>
  </tr>
  <?php foreach($preset_reports as $key=>$pre_rep) { 
  	$format_dollar = ($pre_rep['type']=='a'?"$":"");
  	$format_percent = ($pre_rep['type']=='a'?"":"%");
  	if($pre_rep['type']=='s') $format_dollar = "Score:";
  	if($pre_rep['type']=='s') $format_percent = "pts.";
  ?>
  <tr class="row<?=gen_row(1)?>">
    <td><input type="checkbox" name="enable_<?=$key?>" id="enable_<?=$key?>" value="1" <?=$_POST['enable_'.$key]?'checked':''?>><?=$pre_rep['title']?></td>
    <td colspan="4"><?=$pre_rep['desc']?></td>
  </tr>
  <?php if (is_array($pre_rep['default'])) { ?>
  <tr class="row<?=gen_row(1)?>">
    <td>&nbsp;</td>
    <td width="115"><span class="cl1"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$format_dollar?>
      <input name="mild_<?=$key?>" type="text" class="levels" id="mild_<?=$key?>" value="<?=$_POST['mild_'.$key]?$_POST['mild_'.$key]:$pre_rep['default'][0]?>" size="5">
      <?=$format_percent?>
    </font></strong></span></td>
    <td width="115"><span class="cl1"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$format_dollar?>
      <input name="moderate_<?=$key?>" type="text" class="levels" id="moderate_<?=$key?>" value="<?=$_POST['moderate_'.$key]?$_POST['moderate_'.$key]:$pre_rep['default'][1]?>" size="5">
      <?=$format_percent?>
    </font></strong></span> </td>
    <td width="115"><span class="cl1"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$format_dollar?>
      <input name="severe_<?=$key?>" type="text" class="levels" id="severe_<?=$key?>" value="<?=$_POST['severe_'.$key]?$_POST['severe_'.$key]:$pre_rep['default'][2]?>" size="5">
      <?=$format_percent?>
    </font></strong></span> 
    </td>
    <td width="115"><span class="cl1"><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$format_dollar?>
      <input name="goal_<?=$key?>" type="text" class="levels" id="goal_<?=$key?>" value="<?=$_POST['goal_'.$key]?$_POST['goal_'.$key]:$pre_rep['default'][3]?>" size="5">
      <?=$format_percent?>
    </font></strong></span></td>
  </tr>
  <?php } ?>
  <?php } ?>
  <script language="javascript">
		document.getElementById('rp_period').value = '<?=$_POST['rp_period']?>';
		document.getElementById('rp_severity').value = '<?=$_POST['rp_severity']?>';
		document.getElementById('rp_projected_severity').value = '<?=$_POST['rp_projected_severity']?>';
	</script>
  <!--<tr class="header">
    <th scope="col">Report on: </th>
    <th colspan="2" scope="col">Comparison</th>
    <th colspan="2" scope="col">Variable or value </th>
  </tr> -->
  <?php foreach($_POST['option_id'] as $id=>$id_val) { 
  		continue;
		if(!$_POST['rp_data'][$id]) continue;
  ?>
  <tr class="row<?=gen_row(1)?>">
    <td height="60" rowspan="2"><input name="option_id[]" type="hidden" value="<?=$id?>">
      <select name="rp_data[]" class="levels" id="rp_data_<?=$id?>">
        <?=$data_options?>
        <option value="">Nothing (Remove)</option>
      </select></td>
    <td colspan="2" rowspan="2"><select name="compare[]" class="levels" id="compare_<?=$id?>" onChange="updateComparison(this.value,'<?=$id?>')">
      <option value='none' selected>No Comparison</option>
        <option value="tpgt">Total Percent is greater than</option>
        <option value="tplt">Total Percent is less than</option>
        <option value="apgt">Approved Percent is greater than</option>
        <option value="aplt">Approved Percent is less than</option>
        <option value="mtgt">Monthly Total is greater than</option>
        <option value="mtlt">Monthly Total is less than</option>
      </select></td>
    <td colspan="2" id="compare_div1_<?=$id?>" style="visibility:hidden ">variable: 
      <select name="destination[]" class="levels" id="destination_<?=$id?>" onChange="updateDestination(this.value,'<?=$id?>')">
        <option value="" selected>None (set a value below)</option>
        <?=$data_destination?>
    </select></td>
  </tr>
  <tr id="compare_div2_<?=$id?>" style="visibility:hidden ">
    <td colspan="2" class="row<?=gen_row(0)?>"><table id="compare_div3_<?=$id?>" width="100%" border="0" cellpadding="0" cellspacing="0" class="report">
        <tr class="row<?=gen_row(0)?>">
          <td width="62">or value: </td>
          <td width="171"><input name="destination_value[]" type="text" class="levels" id="destination_value_<?=$id?>" size="10">
            <select name="destination_value_type[]" class="levels" id="destination_value_type_<?=$id?>">
        	<?=$data_destination_value_type?>
            </select></td>
        </tr>
		<tr><td></td></tr>
      </table></td>
  </tr>
  
  <script language="javascript">
		document.getElementById('rp_data_<?=$id?>').value = '<?=$_POST['rp_data'][$id]?>';
		document.getElementById('compare_<?=$id?>').value = '<?=$_POST['compare'][$id]?>';
		document.getElementById('destination_<?=$id?>').value = '<?=$_POST['destination'][$id]?>';
		document.getElementById('destination_value_<?=$id?>').value = '<?=$_POST['destination_value'][$id]?>';
		document.getElementById('destination_value_type_<?=$id?>').value = '<?=$_POST['destination_value_type'][$id]?>';
		updateDestination('<?=$_POST['destination'][$id]?>','<?=$id?>');
		updateComparison('<?=$_POST['compare'][$id]?>','<?=$id?>');
	</script>
  <?php } ?>
  <tr>
    <td colspan="5" align="center" class="row<?=gen_row(1)?>"><input name="Submit" type="submit" value="Submit">
      <in1put name="Submit" type="submit" value="Add Option"></td>
  </tr>
</table>
<?php 
$addRemoveReports= ob_get_contents();
ob_end_clean();
doTable($addRemoveReports,ucfirst($mode)." a Report","addRiskReport.php");

include("includes/footer.php");
?>
</p>
