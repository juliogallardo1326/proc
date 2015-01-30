<?php
include("includes/sessioncheck.php");


$headerInclude = "riskassesment";
include("includes/header.php");

$global_row = 1;


//print_r($_POST);

$rp_period_options = func_get_enum_values('cs_reports','rp_period');
$rp_severity_options = func_get_enum_values('cs_reports','rp_severity');
$rp_projected_severity_options = func_get_enum_values('cs_reports','rp_projected_severity');

$rp_ID = $_POST['rp_ID'];
$rp_type = $_POST['rp_type'];
if(!$rp_type) $rp_type = 'full';
if($_POST['Submit'] == "Update Report Settings")
{
	$_POST['Submit'] = "View Report";
}
if($_POST['Submit'] == "View Report")
{
	$sql="SELECT * from `cs_reports` WHERE `rp_ID` = '$rp_ID'";
	$result = mysql_query($sql) or dieLog(mysql_error." ~ $sql");
	$rp_info = mysql_fetch_assoc($result);
	$mode = "view";
}



// List Reports

ob_start();
?>

<select name="rp_ID" size="5" id="rp_ID">
  <?php func_fill_combo_conditionally("select rp_ID, rp_title from `cs_reports` ORDER BY `rp_title` ASC ",$_POST['rp_ID'],$cnn_cs); ?>
</select>
<BR>
<input name="Submit" type="submit" value="View Report">
<?php 
$addRemoveReports= ob_get_contents();
ob_end_clean();
doTable($addRemoveReports,"Available Reports","riskAssess.php");


// Generate Report

if ($mode == "view")
{
	//print_r($rp_info);
	$rp_source_query = $rp_info['rp_source_query']. " WHERE 1 ";
	$rp_destination_query = $rp_info['rp_destination_query']. " WHERE 1 ";
	if($adminInfo['li_level'] == 'bank') $_POST['bank_Creditcard'] = $adminInfo['li_bank'];
	if($_POST['bank_Creditcard'] != '')	$bank_sql_t = " AND t.bank_id = '".$_POST['bank_Creditcard']."' ";
	if($_POST['bank_Creditcard'] != '')	$bank_sql_cd = " AND cd.bank_Creditcard = '".$_POST['bank_Creditcard']."' ";

	$rp_period = $rp_info['rp_period'];
	if($_POST['rp_period']) $rp_period = $_POST['rp_period'];
	
	// Overall Report

	$report = genReport($rp_source_query.$bank_sql_t,$rp_destination_query,$rp_period);
	$report['title']="General Report - All companys";
	$report['header']="Overall Report";
	$report['priority']=99999;
	$report_list['z'] = $report;
	
	$company_list_sql = "Select userId, companyname from `cs_companydetails` as cd WHERE cd.activeuser=1 $bank_sql_cd";
	$result = mysql_query($company_list_sql) or dieLog(mysql_error()." ~ $company_list_sql");
	while($companyId = mysql_fetch_assoc($result))
	{
		$s_sql = $rp_source_query.$bank_sql_t." AND t.userId = '".$companyId['userId']."'";
		$d_sql = $rp_destination_query.$bank_sql_cd." AND cd.userId = '".$companyId['userId']."'";

		$report = genReport($s_sql,$d_sql,$rp_period);
		if($report['priority']==0)$report['header']="Company Reports (No Alerts)";
		else $report['header']="Company Reports (Contain Alerts)";
		$report['title']=ucfirst($rp_type)." Report - ".$companyId['companyname'];
		$report_list[$report['priority']."_".$companyId['companyname']] = $report;
	}
	// Sorting List
	krsort($report_list);
	
	
}

// End Generate Report

ob_start();
?>
<script language="javascript">
function updateDestination(value,id)
{
	//document.getElementById('destination_value_type_'+id).disabled=(value!='');
	//document.getElementById('destination_value_'+id).disabled=(value!='');
	//document.getElementById('compare_span1_'+id).style.visibility=(value==''?'visible':'hidden');
	document.getElementById('compare_span3_'+id).style.visibility=(value==''?'visible':'hidden');
}
function updateComparison(value,id)
{
	if(value!='none'&&value!='')
	{
		document.getElementById('compare_span1_'+id).style.visibility='visible';
		document.getElementById('compare_span2_'+id).style.visibility='visible';
		updateDestination(document.getElementById('destination_'+id).value,id);
	}
	else
	{
		document.getElementById('compare_span1_'+id).style.visibility='hidden';
		document.getElementById('compare_span2_'+id).style.visibility='hidden';
		document.getElementById('compare_span3_'+id).style.visibility='hidden';
	}
}

</script>
<table width="600"  border="1" cellspacing="2" cellpadding="2" class="report">
  <tr class="header">
    <th scope="col">Report Details: </th>
    <th scope="col"><input name="rp_ID" type="hidden" id="rp_ID" value="<?=$_POST['rp_ID']?>"></th>
  </tr>
  <tr class="row<?=gen_row(1)?>">
    <td>Report Format </td>
    <td><select name="rp_type" id="rp_type" class="levels">
        <option value="full">Full Report</option>
        <option value="brief">Brief Report</option>
      </select>    </td>
  </tr>
  <?php if($adminInfo['li_level'] == 'full') { ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Report on Bank </td>
    <td><span class="cl1">
      <select name="bank_Creditcard" id="bank_Creditcard" class="levels">
        <option value="">All Banks</option>
        <?php 
					func_fill_combo_conditionally("select bank_id, bank_name from `cs_bank` WHERE bk_cc_support=1 ORDER BY `bank_name` ASC ","",$cnn_cs);
				?>
      </select>
    </span> </td>
  </tr>
  <?php } ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Period</td>
    <td><select name="rp_period" id="rp_period" class="levels">
        <?=$rp_period_options?>
      </select>    </td>
  </tr>
  <tr align="center" class="row<?=gen_row(1)?>">
    <td colspan="2"><input type="submit" name="Submit" value="Update Report Settings"></td>
  </tr>
  <?php if ($rp_type=='full') { ?>
  <tr class="header">
    <th scope="col">Report Details: </th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php if ($rp_info['rp_content']) { ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Details:</td>
    <td><pre><?=$rp_info['rp_content']?>
</pre></td>
  </tr>
  <?php } ?>
  <tr class="header">
    <th scope="col">Report Overview: </th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php } ?>
  <tr class="row<?=gen_row(1)?>">
    <td colspan="2">
	<?php if(is_array($report_list)) foreach($report_list as $report) {
	if($rp_type!='full' && $report['priority'] == 0) continue;
	 ?>
	  <table width="100%"  border="1" cellspacing="2" cellpadding="2" class="report">
		<?php if ($header != $report['header']) { $header = $report['header']; ?>
        <tr class="row<?=gen_row(1)?>">
          <th scope="col" colspan="4" class="header"><?=$header?></th>
        </tr>
		<?php } ?>
        <tr class="row<?=gen_row(1)?>">
          <th scope="col" colspan="4"><?=$report['title']?></th>
        </tr>
		<?php if ($rp_type=='full') { ?>
        <tr class="row<?=gen_row(1)?>">
          <td width="16%" class="subheader">Data:</th>
          <td width="28%" class="subheader">Last Period <span class="subheader2">(<?=ucfirst($rp_period)?>)</span> </th>
          <td width="28%" class="subheader">This Period <span class="subheader2">(<?=ucfirst($rp_period)?>)</span></th>
          <td width="28%" class="subheader">Next Period <span class="subheader2">(<?=ucfirst($rp_period)?> Est)</span> </th>
        </tr>
		<?php foreach($report['lastreport']['report'] as $key=>$data) { 
		if(!is_array($data)) continue;
		?>
        <tr class="row<?=gen_row(1)?>">
          <td><?=$key?>:</td>
		  <?php 
		  $report_types = array('lastreport','thisreport','nextreport');
		  foreach($report_types as $reptyp){
		  $data_val = $report[$reptyp]['report'][$key];
		  ?>
          <td class="data"><span class="<?=$data_val['amt']['css']?>">$<?=formatMoney($data_val['amt']['val'])?></span> / <span class="<?=$data_val['dper']['css']?>"><?=formatMoney($data_val['dper']['val'])?>%</span> (<span class="<?=$data_val['cnt']['css']?>"><?=($data_val['cnt']['val'])?></span>)</td>
          <?php } ?>
		</tr>
		<?php } ?>
		<?php } ?>
        <tr class="row<?=gen_row(1)?>">
          <td colspan="4" class="data"><pre><?=$report['str_result']?><BR><BR></pre></td>
        </tr>
      </table>
	<?php } ?>
	</td>
  </tr>
  <script language="javascript">
		document.getElementById('rp_type').value = '<?=$_POST['rp_type']?>';
		document.getElementById('rp_period').value = '<?=$rp_period?>';
		document.getElementById('bank_Creditcard').value = '<?=$_POST['bank_Creditcard']?>';
	</script>
</table>
<?php 
$addRemoveReports= ob_get_contents();
ob_end_clean();
doTable($addRemoveReports,$_POST['rp_title'],"riskAssess.php");

include("includes/footer.php");

function gen_row($change = false)
{
	global $global_row;
	if($change) $global_row = 3-$global_row;
	return $global_row;
}

function genReportSection($rp_source_query)
{
	$result=mysql_query($rp_source_query) or dieLog(mysql_error()." ~ $rp_source_query");
	$source_data = mysql_fetch_assoc($result);
	
	list($Total_Transactions,$Total_Amount) = explode("|",$source_data['Total']);
	list($Approved_Transactions,$Approved_Amount) = explode("|",$source_data['Sales']);
	foreach($source_data as $key=>$data)
	{
		$data_parts = explode("|",$data);
		$data_parts[0] = floatval($data_parts[0]);
		$data_parts[1] = floatval($data_parts[1]);
		$section[$key]['cnt']['val'] = $data_parts[0];
		$section[$key]['amt']['val'] = $data_parts[1];
		if($Approved_Transactions>0) $section[$key]['per']['val'] = round($data_parts[0]/$Approved_Transactions,4)*100;
		if($Approved_Amount>0) $section[$key]['apr']['val'] = round($data_parts[1]/$Approved_Amount,4)*100;
		if($Total_Transactions>0) $section[$key]['tper']['val'] = round($data_parts[0]/$Total_Transactions,4)*100;
		if($Total_Amount>0) $section[$key]['tapr']['val'] = round($data_parts[1]/$Total_Amount,4)*100;

		if($data_parts[2] == 'tper')
		{
			$section[$key]['dper']['val']=$section[$key]['tper']['val'];
			$section[$key]['dapr']['val']=$section[$key]['tapr']['val'];
		}
		else
		{
			$section[$key]['dper']['val']=$section[$key]['per']['val'];
			$section[$key]['dapr']['val']=$section[$key]['apr']['val'];
		}
	}
	//$section['Total']['apr']=100;
	return $section;
}
function genReport($rp_source_query,$rp_destination_query,$rp_period)
{
	global $rp_info;

	if($rp_period == 'daily')
	{
		$drange['last']['from'] = 	date("Y-m-d g:i:s",time()-60*60*24*2);
		$drange['last']['to'] = 	date("Y-m-d g:i:s",time()-60*60*24*1);		
		$drange['this']['from'] = 	date("Y-m-d g:i:s",time()-60*60*24*1);	
		$drange['this']['to'] = 	date("Y-m-d g:i:s");
		$total_days = 1;
	}	
	if($rp_period == 'weekly')
	{
		$drange['last']['from'] = 	date("Y-m-d g:i:s",time()-60*60*24*14);
		$drange['last']['to'] = 	date("Y-m-d g:i:s",time()-60*60*24*7);		
		$drange['this']['from'] = 	date("Y-m-d g:i:s",time()-60*60*24*7);	
		$drange['this']['to'] = 	date("Y-m-d g:i:s");
		$total_days = 7;
	}
	if($rp_period == 'monthly')
	{
		$drange['last']['from'] = 	date("Y-m-",time()-60*60*24*30)."1 00:00:01";
		$drange['last']['to'] = 	date("Y-m-")."1 00:00:00";		
		$drange['this']['from'] = 	date("Y-m-")."1 00:00:01";
		$drange['this']['to'] = 	date("Y-m-d g:i:s");
		$total_days = 30;
	}
	if($rp_period == 'yearly')
	{
		$drange['last']['from'] = 	date("Y-",time()-60*60*24*365)."1-1 00:00:01";
		$drange['last']['to'] = 	date("Y-")."1-1 00:00:00";		
		$drange['this']['from'] = 	date("Y-")."1-1 00:00:01";	
		$drange['this']['to'] = 	date("Y-m-d g:i:s");
		$total_days = 365;
	}		
	
	
	$drange['last']['sql'] = "'".$drange['last']['from']."' and '".$drange['last']['to']."'";	
	$drange['this']['sql'] = "'".$drange['this']['from']."' and '".$drange['this']['to']."'";	
	
	$last_source_query_td= " td.transactionDate between ".$drange['last']['sql'];
	$last_source_query_tdc= " tdc.cancellationDate between ".$drange['last']['sql'];
	
	$this_source_query_td= " td.transactionDate between ".$drange['this']['sql'];
	$this_source_query_tdc= " tdc.cancellationDate between ".$drange['this']['sql'];
	
	$days = (strtotime($drange['this']['to'])-strtotime($drange['this']['from']))/(60*60*24);
	$day_percent = $days/$total_days;
	
	$sql = $rp_source_query;
	$sql = str_replace('[transactionDate]',$last_source_query_td,$sql);
	$sql = str_replace('[cancellationDate]',$last_source_query_tdc,$sql);
	$lastreport = genReportSection($sql);
	
	$sql = $rp_source_query;
	$sql = str_replace('[transactionDate]',$this_source_query_td,$sql);
	$sql = str_replace('[cancellationDate]',$this_source_query_tdc,$sql);
	$thisreport = genReportSection($sql);
	
	foreach($lastreport as $key=>$sect)
	{
		foreach($sect as $key2=>$var) // L +((T-L)*2)*p
			{
			$nextreport[$key][$key2]['val']=
			round(
				$lastreport[$key][$key2]['val']+
				(($thisreport[$key][$key2]['val']-$lastreport[$key][$key2]['val'])*2)*$day_percent
			,2);
			if($nextreport[$key][$key2]['val']<0)$nextreport[$key][$key2]['val']=0;
			}
		$nextreport[$key]['cnt']['val'] = round($nextreport[$key]['cnt']['val']);
	}
	$result=mysql_query($rp_destination_query) or dieLog(mysql_error()." ~ $rp_destination_query");
	$destination_data = mysql_fetch_assoc($result);
	$final_report['lastreport']['report'] = $lastreport;
	$final_report['lastreport']['severity'] = $rp_info['rp_projected_severity'];
	$final_report['lastreport']['assoc'] = "Last Report Period";
	$final_report['thisreport']['report'] = $thisreport;
	$final_report['thisreport']['severity'] = $rp_info['rp_severity'];
	$final_report['thisreport']['assoc'] = "This Report Period";
	$final_report['nextreport']['report'] = $nextreport;
	$final_report['nextreport']['severity'] = $rp_info['rp_projected_severity'];
	$final_report['nextreport']['assoc'] = "Projected Report Period";
	
	$str_result = "";
	$priority = 0;
	foreach($destination_data as $key=>$sect)
	{
		list($mild,$moderate,$severe,$goal) = explode("|",$sect);
		foreach($final_report as $which_report=>$rp_report)
		{
			$value_type="";
			if($type=='p')$fvalue = formatMoney($value)."%";
			if($type=='apm')$fvalue = "$".formatMoney($value);
			$severity = $final_report[$which_report]['severity'];
			if($compare=='tpgt' && $rp_report['report'][$key]['tper']['val']>$value) {$value_type='dper'; $str_assoc = "Total Percentage is greater than '$fvalue'"; }
			if($compare=='tplt' && $rp_report['report'][$key]['tper']['val']<$value) {$value_type='dper'; $str_assoc = "Total Percentage is less than '$fvalue'"; }
			if($compare=='apgt' && $rp_report['report'][$key]['per']['val']>$value) {$value_type='dper'; $str_assoc = "Approved Percentage is greater than '$fvalue'"; }
			if($compare=='aplt' && $rp_report['report'][$key]['per']['val']<$value) {$value_type='dper'; $str_assoc = "Approved Percentage is less than '$fvalue'"; }
			if($compare=='mtgt' && $rp_report['report'][$key]['amt']['val']>$value) {$value_type='amt'; $str_assoc = "Approved Amount is greater than '$fvalue'"; }
			if($compare=='mtlt' && $rp_report['report'][$key]['amt']['val']<$value) {$value_type='amt'; $str_assoc = "Approved Amount is less than '$fvalue'"; }

			if($value_type)
			{
				$final_report[$which_report]['report'][$key][$value_type]['css'] = $severity;
				$str_result.="<span class='$severity'>".ucfirst($severity)." Warning: $key $str_assoc for ".$final_report[$which_report]['assoc'].".</span>\n";
				$priority++;
			}
		}
	}
	$final_report['str_result'] = $str_result;
	$final_report['priority'] = $priority;	
	
	
	//print "<PRE>";
	//print_r($final_report); print "<BR>";
	//print_r($destination_data); print "<BR>";
	//print "</PRE>";
	
	return $final_report;
}

?>
