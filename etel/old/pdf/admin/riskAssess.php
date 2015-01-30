<?php
$allowBank=true;
include("includes/sessioncheck.php");


$headerInclude = "riskassesment";
include("includes/header.php");

$global_row = 1;

$report_periods[0] = 'Less Than a Day';
$report_periods[1] = 'Daily';
$report_periods[7] =  'Weekly';
$report_periods[15] = 'BiMonthly';
$report_periods[30] = 'Monthly';
$report_periods[365] = 'Yearly';

//print_r($_POST);

//$rp_period_options = func_get_enum_values('cs_reports','rp_period');
//$rp_severity_options = func_get_enum_values('cs_reports','rp_severity');
//$rp_projected_severity_options = func_get_enum_values('cs_reports','rp_projected_severity');

$rp_ID = $_POST['rp_ID'];
$rp_type = $_POST['rp_type'];
$rp_severity = $_POST['rp_severity'];
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
	if($rp_info) $mode = "view";
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
doTable($addRemoveReports,"Available Reports","riskAssessment.php");

// Update Unique Transactions
/*
$cc_cc=NULL;
$sql = "SELECT `transactionId`,`CCnumber`,`ipaddress` FROM `cs_transactiondetails` WHERE `status` = 'A' ";
$result = mysql_query($sql);
while($ccInfo = mysql_fetch_assoc($result))
{
	if(!$cc_cc[$ccInfo['ipaddress']])$cc_cc[$ccInfo['ipaddress']] = 0;
	$sql = "update `cs_transactiondetails` set `td_non_unique` = '".$cc_cc[$ccInfo['ipaddress']]."' where transactionId = ".$ccInfo['transactionId'];
	$result2 = mysql_query($sql) or dieLog(mysql_error());
	$cc_cc[$ccInfo['ipaddress']]++;
}
print_r($cc_cc); die();

// `transactionId`%7
*/

// Generate Report

if ($mode == "view" )//$rp_info['rp_source_query']
{
	//print_r($rp_info);
	$rp_source_query = $rp_info['rp_source_query']. " WHERE 1 ";
	$rp_destination_query = $rp_info['rp_destination_query']. " WHERE 1 ";
	$bank_select_sql="";
	$bank_transaction_sql="";
	$bank_id = $_POST['bank_Creditcard'];
	if($adminInfo['li_level'] == 'bank') 
	{
		$bank_id = $adminInfo['li_bank'];
		if($adminInfo['li_type'] == 'credit') $bank_select_sql=" cd.bank_Creditcard = '$bank_id' ";
		if($adminInfo['li_type'] == 'check') $bank_select_sql=" cd.bank_check = '$bank_id' ";
		$bank_transaction_sql=" AND t.bank_id = '$bank_id' ";
	}
	else 
	{
		if (intval($bank_id)>0) $bank_select_sql=" (cd.bank_Creditcard = '$bank_id' OR cd.bank_check = '$bank_id') ";
		if (intval($bank_id)>0) $bank_transaction_sql=" AND t.bank_id = '$bank_id' ";
	}
	
	if($bank_id != '')	$bank_sql_t = " AND t.bank_id = '$bank_id' ";
	if($bank_id != '')	$bank_sql_cd = " AND $bank_select_sql ";
	
	if($_POST['rp_period']) $rp_info['rp_period'] = $_POST['rp_period'];
	$rp_period = getHalfProcessingTime(-1,$rp_info['rp_period']);
	
	// Overall Report
	
	$report = genReport($rp_source_query.$bank_sql_t,$rp_destination_query,$rp_period,true);
	if($report != -1)
		{
		$duration = "";
		if($rp_period>=1) $duration = " (".round($rp_period*2)." Day Duration)";
		if($rp_period<1) $duration = " (Less Than One Day)";
		$report['title']="General Report - All companys";
		$report['header']="Overall Report $duration";
		$report['priority']=99999;
		
		$report['period'] = $report_periods[round($rp_period)];
		if(!$report['period']) $report['period']=round($rp_period)." Day";
				
		$report_list['z'] = $report;
		
		if(!$_POST['rp_inactive']) $company_sql=" AND cd.activeuser=1";
		
		$company_list_sql = "Select cd.userId, companyname from `cs_companydetails` as cd left join `cs_transactiondetails` as t on t.userId = cd.userId WHERE 1 $company_sql $bank_sql_t group by companyname ";
		$result = mysql_query($company_list_sql) or dieLog(mysql_error()." ~ $company_list_sql");
		while($companyId = mysql_fetch_assoc($result))
		{
			$s_sql = $rp_source_query.$bank_sql_t." AND cd.userId = '".$companyId['userId']."'";
			$d_sql = $rp_destination_query.$bank_sql_cd." AND cd.userId = '".$companyId['userId']."'";
	
			$rp_cmp_period = getHalfProcessingTime($companyId['userId'],$rp_period);
			
			$report = genReport($s_sql,$d_sql,$rp_cmp_period,false,$companyId['userId']);
			if($report != -1)
			{
				$duration = "";
				if($rp_cmp_period>=1) $duration = " (".round($rp_cmp_period*2)." Day Duration)";
				if($rp_cmp_period<1) $duration = " (Less Than One Day)";
				if(intval($report['priority'])==0)$report['header']="Company Reports (No Alerts)";
				else $report['header']="Company Reports (Contain Alerts)";
				
				$report['period'] = $report_periods[round($rp_cmp_period)];
				if(!$report['period']) $report['period']=round($rp_cmp_period)." Day";
				$report['title']=ucfirst($rp_type)." Report - <a href='editCompanyProfileAccess.php?company_id=".$companyId['userId']."'>".$companyId['companyname']."</a>".$duration ;
				$report_list[intval($report['priority'])."_".$companyId['companyname']] = $report;
			}
		}
		// Sorting List
		krsort($report_list);
	
	}
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
  <tr class="row<?=gen_row(1)?>">
    <td>Report Severity </td>
    <td><select name="rp_severity" id="rp_severity" class="levels">
      <option value="mild">All Alerts</option>
      <option value="moderate">Moderate Alerts and up</option>
      <option value="severe">Severe Alerts and up</option>
      <option value="goal">Limit Point Alerts Only</option>
      </select>    </td>
  </tr>
  <?php if($adminInfo['li_level'] == 'full') { ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Report on Bank </td>
    <td><span class="cl1">
      <select name="bank_Creditcard" id="bank_Creditcard" class="levels">
        <option value="">All Banks</option>
        <?php 
					func_fill_combo_conditionally("select bank_id, bank_name from `cs_bank` ORDER BY `bank_name` ASC ","",$cnn_cs);
				?>
      </select>
    </span> </td>
  </tr>
  <?php } ?>
  <tr class="row<?=gen_row(1)?>">
    <td>Report Period</td>
    <td><select name="rp_period" id="rp_period" class="levels">
      <option value="30" <?=$_POST['rp_period']==30?"selected":""?> >Monthly</option>
      <option value="1" <?=$_POST['rp_period']==1?"selected":""?> >Daily</option>
      <option value="7" <?=$_POST['rp_period']==7?"selected":""?> >Weekly</option>
      <option value="365" <?=$_POST['rp_period']==365?"selected":""?> >Yearly</option>
      </select>    </td>
  </tr>
  <tr class="row<?=gen_row(1)?>">
    <td>Show Inactive Companys</td>
    <td><input name="rp_inactive" type="checkbox" value="1" <?=$_POST['rp_inactive']?"checked":""?>></td>
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
        <tr class="row<?=gen_row(1)?>">
          <td width="16%" class="subheader">Data:</th>
          <td width="28%" class="subheader">Last Period <span class="subheader2">(<?=ucfirst($report['period'])?>)</span> </th>
          <td width="28%" class="subheader">This Period <span class="subheader2">(<?=ucfirst($report['period'])?>)</span></th>
          <td width="28%" class="subheader">Next Period <span class="subheader2">(<?=ucfirst($report['period'])?> Est)</span> </th>
        </tr>
		<?php if ($rp_type=='full') 
		{ 
			foreach($report['thisreport']['report'] as $key=>$data) { 
				if(!is_array($data)) continue;

		?>
        <tr class="row<?=gen_row(1)?>">
          <td><?=$key?>:</td>
		  <?php 
				if($data['type']=='str')
				{
				
				  ?><td colspan="3" class="data"><?=nl2br($data['str']['val'])?></td> <?php
			
				} else if($report['thisreport']['report'][$key]['type']=='scr')
				{
					$data_val = $report['thisreport']['report'][$key];
				
				  ?><td colspan="3" class="data"><span class="<?=$data_val['amt']['css']?>"><?=($data_val['amt']['val'])?></span></td> <?php
			
				}
				else
				{
				$report_types = array('lastreport','thisreport','nextreport');
				foreach($report_types as $reptyp)
				{
				  $data_val = $report[$reptyp]['report'][$key];

			  ?><td class="data"><span class="<?=$data_val['amt']['css']?>">$<?=formatMoney($data_val['amt']['val'])?></span> / <span class="<?=$data_val['dper']['css']?>"><?=formatMoney($data_val['dper']['val'])?>%</span> (<span class="<?=$data_val['cnt']['css']?>"><?=($data_val['cnt']['val'])?></span>)</td> <?php
			  	} ?>
		</tr>
		<?php 
			}
			} 
		 } ?>
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
		document.getElementById('rp_severity').value = '<?=$rp_severity?>';
		document.getElementById('bank_Creditcard').value = '<?=$_POST['bank_Creditcard']?>';
	</script>
</table>
<?php 
$addRemoveReports= ob_get_contents();
ob_end_clean();
if($_POST['rp_ID']) doTable($addRemoveReports,$_POST['rp_title'],"riskAssessment.php");

include("includes/footer.php");

function gen_row($change = false)
{
	global $global_row;
	if($change) $global_row = 3-$global_row;
	return $global_row;
}

function genReportSection($rp_source_query)
{
	$result=mysql_query($rp_source_query) or dieLog("".mysql_error()." ~ $rp_source_query");
	$source_data = mysql_fetch_assoc($result);
	
	list($Total_Transactions,$Total_Amount) = explode("|",$source_data['Total']);
	list($Approved_Transactions,$Approved_Amount) = explode("|",$source_data['Sales']);
	foreach($source_data as $key=>$data)
	{
		$data_parts = explode("|",$data);
		
		if(!is_numeric($data_parts[1]))
		 $section[$key]['str']['val'] = $data_parts[1];
		 
		$data_parts[0] = floatval($data_parts[0]);
		$data_parts[1] = floatval($data_parts[1]);
		$section[$key]['cnt']['val'] = $data_parts[0];
		$section[$key]['amt']['val'] = $data_parts[1];
		
		if($Approved_Transactions>0) $section[$key]['per']['val'] = round($data_parts[0]/$Approved_Transactions,4)*100;
		if($Approved_Amount>0) $section[$key]['apr']['val'] = round($data_parts[1]/$Approved_Amount,4)*100;
		if($Total_Transactions>0) $section[$key]['tper']['val'] = round($data_parts[0]/$Total_Transactions,4)*100;
		if($Total_Amount>0) $section[$key]['tapr']['val'] = round($data_parts[1]/$Total_Amount,4)*100;

		$section[$key]['type']=$data_parts[2];
		
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
function genReport($rp_source_query,$rp_destination_query,$total_days,$overview=false,$userId=NULL)
{
	global $rp_info;
	global $rp_severity;

	$drange['last']['from'] = 	date("Y-m-d 00:00:00",time()-60*60*24*$total_days*2+1);
	$drange['last']['to'] = 	date("Y-m-d g:i:s",time()-60*60*24*$total_days);		
	$drange['this']['from'] = 	date("Y-m-d g:i:s",time()-60*60*24*$total_days+1);	
	$drange['this']['to'] = 	date("Y-m-d g:i:s");

	$drange['last']['sql'] = "'".$drange['last']['from']."' and '".$drange['last']['to']."'";	
	$drange['this']['sql'] = "'".$drange['this']['from']."' and '".$drange['this']['to']."'";	
	
	$last_source_query_td= " td.transactionDate between ".$drange['last']['sql'];
	$last_source_query_tdc= " tdc.cancellationDate between ".$drange['last']['sql'];
	
	$this_source_query_td= " td.transactionDate between ".$drange['this']['sql'];
	$this_source_query_tdc= " tdc.cancellationDate between ".$drange['this']['sql'];
	
	$days = (@strtotime($drange['this']['to'])-@strtotime($drange['this']['from']))/(60*60*24);
	if ($total_days<=1)$day_percent=1;
	else $day_percent = $days/$total_days;
	
	$site_sql = "1";
	if($userId) $site_sql = "cs.cs_company_id = $userId";
	$company_userId = "1";
	if($userId) $company_userId = "userId = $userId";
	
	$sql = $rp_source_query;
	$sql = str_replace('[transactionDate]',$last_source_query_td,$sql);
	$sql = str_replace('[cancellationDate]',$last_source_query_tdc,$sql);
	$sql = str_replace('[site_sql]',$site_sql,$sql);
	$sql = str_replace('[company_userId]',$company_userId,$sql);
	$lastreport = genReportSection($sql);
	if($lastreport==-1) return -1;
	
	$sql = $rp_source_query;
	$sql = str_replace('[transactionDate]',$this_source_query_td,$sql);
	$sql = str_replace('[cancellationDate]',$this_source_query_tdc,$sql);
	$sql = str_replace('[site_sql]',$site_sql,$sql);
	$sql = str_replace('[company_userId]',$company_userId,$sql);
	$thisreport = genReportSection($sql);
	if($thisreport==-1) return -1;
	
	$nextreport = array();
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
		$nextreport[$key]['type']=$lastreport[$key]['type'];
		if($nextreport[$key]['cnt']['val']<=0 || $nextreport[$key]['amt']['val']<=0) { $nextreport[$key]['cnt']['val']=0; $nextreport[$key]['dper']['val']=0; $nextreport[$key]['amt']['val']=0;}
		$nextreport[$key]['cnt']['val'] = round($nextreport[$key]['cnt']['val']);
	}
	$result=mysql_query($rp_destination_query);
	if(!$result) { dieLog("".mysql_error()." ~ $rp_destination_query");}
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
	if(is_array($destination_data))
		foreach($destination_data as $key=>$sect)
		{
			list($mild,$moderate,$severe,$goal,$type,$ext_value,$signif) = explode("|",$sect);
			
			foreach($final_report as $which_report=>$rp_report)
			{
				$str_assoc="";
				if($type=='p')
				{
					if($rp_report['report'][$key]['dper']['val']>$mild) {$str_assoc = "Mild Alert: "; $severity='mild'; $value = $mild; }
					if($rp_report['report'][$key]['dper']['val']>$moderate) {$str_assoc = "Moderate Alert: "; $severity='moderate'; $value = $moderate; }
					if($rp_report['report'][$key]['dper']['val']>$severe) {$str_assoc = "Severe Alert: "; $severity='severe'; $value = $severe; }
					if($rp_report['report'][$key]['dper']['val']>$goal) {$str_assoc = "Limit Point Reached: "; $severity='goal'; $value = $goal; }
				}
				if($type=='scr')
				{
					if($which_report!='thisreport') continue;
					if($rp_report['report'][$key]['amt']['val']>$mild) {$str_assoc = "Mild Alert: "; $severity='mild'; $value = $mild; }
					if($rp_report['report'][$key]['amt']['val']>$moderate) {$str_assoc = "Moderate Alert: "; $severity='moderate'; $value = $moderate; }
					if($rp_report['report'][$key]['amt']['val']>$severe) {$str_assoc = "Severe Alert: "; $severity='severe'; $value = $severe; }
					if($rp_report['report'][$key]['amt']['val']>$goal) {$str_assoc = "Limit Point Reached: "; $severity='goal'; $value = $goal; }
				}
				if($type=='str')
				{
					if($which_report!='thisreport') continue;
				}
				if($type=='apm' && $overview==false)
				{ 
					if ($total_days<=0) $apm_fraction =1;
					else $apm_fraction = (30/$total_days);
					$apm_monthly = $rp_report['report'][$key]['amt']['val']*$apm_fraction;
					if($apm_monthly>($mild/100)*$ext_value) {$str_assoc = "Mild Alert: "; $severity='mild'; $value = ($mild/100)*$ext_value; }
					if($apm_monthly>($moderate/100)*$ext_value) {$str_assoc = "Moderate Alert: "; $severity='moderate'; $value = ($moderate/100)*$ext_value; }
					if($apm_monthly>($severe/100)*$ext_value) {$str_assoc = "Severe Alert: "; $severity='severe'; $value = ($severe/100)*$ext_value; }
					if($apm_monthly>($goal/100)*$ext_value) {$str_assoc = "Limit Point Alert: "; $severity='goal'; $value = ($goal/100)*$ext_value; }
				}
				if($mild==0 && $moderate==0 && $severe==0 && $goal==0) {$str_assoc = NULL; $severity=NULL; $value = NULL; }
			
			
				if($type=='p')$fvalue = formatMoney($value)."%";
				if($type=='a')$fvalue = "$".formatMoney($value);
				if($type=='scr')$fvalue = "".formatMoney($value)." pts";
				if($type=='apm')$fvalue = "$".formatMoney($value)."/mo";
	
				if($str_assoc)
				{
					if($type=='scr') $str_assoc.= "'$key' Score is greater than '$fvalue'"; 
					else $str_assoc.= "'$key' Percentage is greater than '$fvalue'"; 
					if($type=='p') $final_report[$which_report]['report'][$key]['dper']['css'] = $severity;
					if($type=='apm' || $type=='scr') $final_report[$which_report]['report'][$key]['amt']['css'] = $severity;
					$txt_severity = ucfirst($severity);
					if($severity=='goal')$txt_severity='Limit Point';
					if($rp_report['report'][$key]['cnt']['val']>$signif) 
					{
						$str_result[$severity].="<span class='$severity'>$key $str_assoc for ".$final_report[$which_report]['assoc'].".</span>\n";
						$priority++;
					}
					else $priority+=.5;
				}
			}
		}
		
		$str_final_result = $str_result['goal'];
		if ($rp_severity!='goal')
		{
			$str_final_result .= $str_result['severe'];
			if ($rp_severity!='severe')
			{
				$str_final_result .= $str_result['moderate'];
				if ($rp_severity!='moderate')
				{
					$str_final_result .= $str_result['mild'];
				}				
			}
		}
		
	$final_report['str_result'] = $str_final_result;
	$final_report['priority'] = $priority;	
	
	//print "<PRE>";
	//print_r($final_report); print "<BR>";
	//print_r($destination_data); print "<BR>";
	//print "</PRE>";
	
	return $final_report;
}
function getHalfProcessingTime($compId,$period)
{
	global $bank_transaction_sql;
	$comp_sql = "";
	if(intval($compId) >-1) $comp_sql = " AND `userId` = '$compId'";
	$sql = "SELECT `transactionDate` as firstdate
		FROM `cs_transactiondetails` as t where 1 $comp_sql $bank_transaction_sql
		ORDER BY `transactionDate` ASC";
	$result=mysql_query($sql) or dieLog(mysql_error());

	if(!mysql_num_rows($result) && intval($compId) >-1) 
	{
		$result=mysql_query("SELECT `date_added` as firstdate
			FROM `cs_companydetails` where `userId` = '$compId'");
	}
	
	if($result) 
	{
		$firstTrans = mysql_fetch_assoc($result);
		$time_so_far = (time() - strtotime($firstTrans['firstdate']))/(60*60*24);
		$time_so_far /= 2;
		if($time_so_far>$period) $time_so_far = $period;
		return $time_so_far;
	}
	return $period;
}
?>
