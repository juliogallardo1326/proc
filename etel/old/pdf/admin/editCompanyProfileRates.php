<?


$en_ID = intval($_REQUEST['entity_id']);
if(!$en_ID) $en_ID = intval($_REQUEST['en_ID']);

$pageConfig['AllowBank'] = true;
$pageConfig['Title'] = "Merchant Rates";

include("includes/sessioncheck.php");
include("../includes/completion.php");
require_once('../includes/subFunctions/rates_fees.php');

$headerInclude = "companies";
include("includes/header.php");


?>
	<table>
	<tr>
	<td><a href="editCompanyProfileAccess.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileWire.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileRates.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="confirmUploads.php?userIdList=<?= $company_id?>&searchby=id&search=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	</tr>
	</table>
<?

$access['SerializedData']['Source'] = 'en_info';
$access['SerializedData']['Data'] = array(
	'General_Notes'=> array('General_Notes')
);
	
$access = getAccessInfo("

userId,
en_ID,
en_info,

'Merchant Status' as access_header,

activeuser,
0 as Email_Active_Notification,

en_pay_type,
en_pay_data,

'Merchant Contract' as access_header,
en_company,
cd_merchant_show_contract,
0 as Email_Contract_Notification,
 cd_custom_contract, 
cd_completion,

'Notes' as access_header_spanned,
1 as 'General_Notes'
",

"cs_entities left join cs_companydetails on en_type='merchant' and en_type_ID = userId",
"en_ID = '$en_ID'",
array('Size'=>30,'Rows'=>2),$access);



if($access==-1) dieLog("Invalid Company","Invalid Company");

$company_id = $access['Data']['userId']['Value'];
$en_ID = $access['Data']['en_ID']['Value'];

$sql = "Select en_ID,en_company From cs_entities, cs_entities_affiliates where en_ID = ea_affiliate_ID and ea_type = 'Reseller' and ea_en_ID = '$en_ID'";
$result = sql_query_read($sql) or dieLog($sql);
while($aff = mysql_fetch_assoc($result))
	$ress[$aff['en_ID']]=$aff['en_company'];
if(!sizeof($ress)) $ress['']='No Reseller';

$access['Data']['en_pay_type']['DisplayName']='Pay Mode';
$access['Data']['en_pay_data']['AddHtml']="<span class='small'><br>Monthly (1-28) ('1,15' = paid 1st and 15th)<br>OR Weekly (0-6) ('1,5' = paid Mon and Fri)</span>";
$access['Data']['en_pay_data']['DisplayName']='Pay Days';
$access['Data']['en_pay_data']['Length']='200';
$access['Data']['en_pay_data']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Payout Schedule Changed to `"+this.value+"`.");'."'";


if(isset($_POST['en_pay_data'])) 
{
	$week_replace =array('0'=>'Sunday','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday');
	foreach($week_replace as $key=>$name)
		$_POST['en_pay_data'] = str_replace($name,$key,$_POST['en_pay_data']);
		
	$data = 0;
	preg_match_all('/[0-9]+/',$_POST['en_pay_data'],$match);
	foreach(array_unique($match['0']) as $day)
		if($day>=0 && $day<=28 && is_numeric($day)) $data += pow(2,$day);
	$_POST['en_pay_data'] = $data; 
}

$access['Data']['Notes']['PlusMinus']='Closed';

$access['Data']['en_company']['disable']=true;
$access['Data']['en_company']['DisplayName']='Company Name';
$access['Data']['userId']['Input']="hidden";
$access['Data']['en_ID']['Input']="hidden";
$access['EntityManager'] = true;
$access['EnablePlusMinus'] = true;

$access['Data']['activeuser']['Input']='checkbox';
$access['Data']['activeuser']['DisplayName']='Merchant Live';
$access['Data']['activeuser']['InputAdditional']="onclick='".
'if(this.checked) {addElementNotes($("General_Notes"),"Merchant Turned Active."); $(Email_Active_Notification).checked=true;$(cd_completion).value = 10;}'.
' else {addElementNotes($("General_Notes"),"Merchant Turned Inactive."); $(cd_completion).value = 9;}'."'";

$access['Data']['Email_Active_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Active_Notification']['Input']='checkbox';

$access['Data']['cd_merchant_show_contract']['Input']='checkbox';
$access['Data']['cd_merchant_show_contract']['DisplayName']='Display Contract';
$access['Data']['cd_merchant_show_contract']['InputAdditional']="onclick='".
'if(this.checked) {addElementNotes($("General_Notes"),"Merchant Contract Displayed."); $(Email_Contract_Notification).checked=true; }'.
' else {addElementNotes($("General_Notes"),"Merchant Contract Disabled."); }'.
'if($(cd_completion).value<=3) $(cd_completion).value = 4;}'.
"'";

$access['Data']['General_Notes']['Input']='textarea';
$access['Data']['General_Notes']['Rows']=8;
$access['Data']['General_Notes']['Style']='width:100%;';
$access['Data']['General_Notes']['InputAdditional']="onmousedown='addElementNotes($(\"General_Notes\"),\"\")'";
$access['Data']['General_Notes']['DisplayName']="Notes";
$access['Data']['General_Notes']['RowDisplay']='Wide';

$access['Data']['cd_completion']['DisplayName']="Completion";
$access['Data']['cd_completion']['Input']="selectcustomarray";	
$access['Data']['cd_completion']['Input_Custom'] = $etel_completion_array;
$access['Data']['cd_completion']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Completion Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";


$access['Data']['Email_Contract_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Contract_Notification']['Input']='checkbox';

$access['Data']['cd_custom_contract']['Input']='checkbox';
$access['Data']['cd_custom_contract']['DisplayName']='Custom Contract';
if($access['Data']['cd_custom_contract']['Value'] || $_POST['cd_custom_contract']) 
	$access['Data']['cd_custom_contract']['AddHtml']="<a target='_blank' href='email_edit.php?et_custom_id=".$company_id."&et_name=merchant_contract'>Edit Contract</a>";





if($_POST['submit_access'] == 'Submit') 
{
	if($_POST['Email_Contract_Notification'] && $access['Data']['send_mail']['Value']==1)
		send_email_template('contract_notification_email',$data);
	
	if($_POST['Email_Active_Notification'] && $access['Data']['send_mail']['Value']==1)
		send_email_template('active_notification_email',$data);
	

	if($_POST['cd_custom_contract'] && !$access['Data']['cd_custom_contract']['Value'])
	{
		$Rates = new rates_fees();
		$contract = $Rates->get_Merchant_Contract($en_ID);
		$sql = "insert into cs_email_templates 
			set et_name='merchant_contract', 
			et_custom_id='".$company_id."', 
			et_title='".quote_smart($access['Data']['en_company']['Value'])." Contract', 
			et_access='admin', 
			et_to_title='".quote_smart($access['Data']['en_company']['Value'])."', 
			et_subject='Custom Merchant Contract for ".quote_smart($access['Data']['en_company']['Value'])."', 
			et_htmlformat='".quote_smart($contract['et_htmlformat'])."', 
			et_catagory='Merchant'";
		$result=sql_query_write($sql) or dieLog(mysql_error()." ~ sql");
		$_POST['cd_custom_contract'] = mysql_insert_id();
	}
	else if(!$_POST['cd_custom_contract'] && $access['Data']['cd_custom_contract']['Value'])
	{
		$sql = "delete from cs_email_templates where et_name='merchant_contract' and et_custom_id='".$company_id."'";
		$result=sql_query_write($sql) or dieLog(mysql_error()." ~ sql");
		$access['Data']['cd_custom_contract']['AddHtml'] = NULL;
	}
}

$res = check_merchant_conflict($_POST,$en_ID);
if(!$res['status'])
{
	$access['HeaderMessage'] .= nl2br($res['msg']);
	foreach($res['res'] as $key=>$val)
		if($val) {$_POST[$key] = $access['Data'][$key]['Value']; $access['Data'][$key]['Highlight'] = true;}
}	

// Submit
$affiliate_data = en_get_affiliates($en_ID);
$emaildata['email'] = $access['Data']['en_email']['Value'];
if($affiliate_data)
	foreach($affiliate_data as $type=>$group)
		if(in_array($type,array('Reseller','Representative')))
			foreach($group as $id=>$ed);
				$emaildata['email'] .= ', '.$ed['en_email'];
				
$emaildata['companyname'] = $access['Data']['en_company']['Value'];
$emaildata['full_name'] = $access['Data']['en_company']['Value'];
$emaildata['username'] = $access['Data']['en_username']['Value'];
$emaildata['Reference_ID'] = $access['Data']['en_ref']['Value'];
$emaildata["gateway_select"] = $access['Data']['en_gateway_ID']['Value'];

if($_POST['submit_access'] == 'Submit' && $curUserInfo['en_access'][ACCESS_AUTH_RATES])
{
	if($_POST['Email_Contract_Notification'])
		send_email_template('contract_notification_email',$emaildata);
	
	if($_POST['Email_Active_Notification'])
		send_email_template('active_notification_email',$emaildata);
	
	$result = processAccessForm(&$access);
	if($result) 
	{
		$msg= "Company Updated Successfully (".$result['cnt']." Field(s))";
		$log = ($adminInfo['en_username'])." Updates ".$access['Data']['en_company']['Value']." with ";
		foreach($result['updateInfo'] as $name=>$value)
			$log .= "$name (Old:'".$value['old']."') = '".$value['new']."' ";	
		toLog('misc','merchant',$log,$company_id);
	}
	else $msg= "No Updates Detected";
}

$access['HeaderMessage'].=$msg;

if($access['Data']['en_pay_data']['Value'])
{
	$companyPayoutInfo = array('en_pay_data'=>$access['Data']['en_pay_data']['Value'],'en_pay_type'=>$access['Data']['en_pay_type']['Value']);
	$Schedule = en_get_payout_schedule($companyPayoutInfo);
	$access['Data']['en_pay_data']['Value'] = $Schedule['Days'];
}

beginTable();
writeAccessForm(&$access);
endTable("Update Company - ".$access['Data']['en_company']['Value'],"");

	$rates_class = new rates_fees();

	if(isset($_POST['frmb_process']) && $curUserInfo['en_access'][ACCESS_AUTH_RATES])
	{
		$new_settings = array();
		
		$banks = $rates_class->get_BanksById();
		
		if(isset($_POST['frmb_bankid']))
			foreach($_POST['frmb_bankid'] as $index => $bank_id)
			{
				$bank_name = $banks[$bank_id]['bank_name'];
				$new_settings[$bank_name] = array();
				$new_settings[$bank_name]['bank_id'] = $bank_id;
				
				$custom = get_bank_custom_fields($bank_id,$new_settings[$bank_name]['custom']);
				if($custom['data']) $new_settings[$bank_name]['custom'] = $custom['data'];
			
			}
		
		foreach($_POST as $name => $value)
			if($value != "")
			if(stristr($name,"frmb_tier_")!==FALSE)
			{
				$vals = explode("_",str_replace("frmb_tier_","",$name));
				
				$bank_id = $vals[0];
				$payee = $vals[1];
				$tier = $vals[2];
				$field = $vals[3];
				
				if($field=='secnd')
					if($bank_id == $value)
						$value = "";


				if(isset($_POST['frmb_bankid']))
					if(in_array($bank_id,$_POST['frmb_bankid']))
					{
						$bank_name = $banks[$bank_id]['bank_name'];
						$new_settings[$bank_name]['bank_id'] = $bank_id;
						if($tier == "d")
							$new_settings[$bank_name]['default'][$payee][$field] = round($value,2);
						else
							$new_settings[$bank_name]['tier'][$tier][$payee][$field] = round($value,2);
					}
			}
			$rates_class->post_MerchantRate($en_ID,$new_settings);
	}

	$banks = $rates_class->get_BanksById();

	$company_rates = $rates_class->get_MerchantRates($en_ID);
	$payees_default = $rates_class->get_Payees();
	$rate_cats = $rates_class->get_RateCategories();
	$num_rate_cats = sizeof($rate_cats);
	
	$data = JSON_get_data(array('func'=>'getEntityList','en_search'=>array($en_ID),'en_search_by'=>array('id'),'silent'=>1));
	
	$json = new Services_JSON();
	$JSON_output = $json->encode($data);
?>

<script language="javascript" src="<?=$rootdir?>/scripts/dynosearch.js"></script>
<script language="javascript">
	function modify_row(id,disp)
	{
		row = "row_" + id;
		anc = "anc_" + id;
	
		row = $(row);
		anc = $(anc);
		
		if(row.style.display=="none")
		{
			row.style.display = "";
			anc.innerHTML = "Hide " + disp;
		}
		else
		{
			row.style.display = "none";
			anc.innerHTML = "Show " + disp;
		}
	}
	function enable_set(id,payee)
	{
<?
		foreach($rate_cats as $cat_field => $cat_info)
		{
			echo "
				if($(id + \"$cat_field\").disabled)
				{
					$(id + \"$cat_field\").disabled = false;
					$(id).innerHTML = \"Disable \" + payee;
				}
				else
				{
					$(id + \"$cat_field\").disabled = true;
					$(id).innerHTML = \"Enable \" + payee;
				}
			";
		}
?>
	}
	
	var response = Array();
	response.responseText = '<?=str_replace("'","\'",$JSON_output)?>';
	en_search_response(response);

</script>	
<?
	beginTable();
	foreach($_POST as $name=>$value)
		if(stristr($name,'frmb_') === FALSE)
			if(!is_array($value))
				echo "<input type='hidden' name='$name' value='$value'>\n";
			else
				foreach($value as $index=>$val)
					echo "<input type='hidden' name='" . $name . "[]' value='$val'>\n";
	
	echo "<input type='hidden' name='company_id' value='$company_id'>\n";
	echo "<input type='hidden' name='entity_id' value='$en_ID'>\n";
	echo "<input type='hidden' name='frmb_process' value='1'>\n";
	echo "<center><h3>" . $company_info['companyname'] . "</h3></center>\n";
	
	echo "<table class='invoice' width='100%'>\n";
	foreach($banks as $bank_id => $bank_info)
	{
		
		$bank_name = $bank_info['bank_name'];
		$info = $company_rates[$bank_id];
		$checked = "";
		if($info != NULL)
			$checked = "checked";
		$payees = $payees_default;
		if($info['default'])  // TODO: Fix Keys. Affiliate rates being overwritten. Search by affiliate entries.
			foreach($info['default'] as $payee_key=>$payee_info)
				if(strpos($payee_key,"Affiliate")=== 0)
					$payees[$payee_key] = array("title"=>$payee_key);


		echo "<tr class='infoHeader'>\n";
		echo "<td><input name='frmb_bankid[]' type='checkbox' $checked value='" . $bank_id . "'/></td>\n";
		echo "<td>" . $bank_name . "</td>\n";
		echo "<td colspan=" . ($num_rate_cats*2) . " align='center'>\n";
		if($checked != "")
		{
			echo "| ";
			foreach($payees as $index => $payee_info)
			{
				$id = $index . "_" . $bank_id;

				$payee = $payee_info['title'];
				if($info['default'][$payee] && !$info['default'][$payee]['default']==true) 
					$payees[$index]['display']='';
				if($payee_info['allowdisable']) 
					$payees[$index]['disabled']=isset($info['default'][$payee]);
				
				$display = $payee_info['display'];
				$payee_disp = strcasecmp($display,"none")==0 ? "Show $payee" : "Hide $payee";
				
				if($payee_info['allowhide'])
					echo "<a id='anc_$id' name=\"\" onClick='modify_row(\"$id\",\"$payee\")' onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\">$payee_disp</a> | ";
				if($payee_info['allowdisable'])
				{
					$frm_root = "frmb_tier_" . $bank_id . "_" . $payee . "_" . "d" . "_";
					$enable_title =  isset($info['default'][$payee]['default']) ? "Enable $payee" : "Disable $payee";
					
					echo "<a id='$frm_root' name=\"\" onClick='enable_set(\"$frm_root\",\"$payee\")' onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\">$enable_title</a> |";		
				}
			}
		}
		echo "</td><td></td>\n";
		echo "</tr>\n";

		if($checked != "")
		{	
			echo "<tr class='row2'>\n";
			echo "<td></td><td></td><td>&nbsp;&nbsp;&nbsp;</td>\n";
			foreach($rate_cats as $cat_field=> $cat_info)
				echo "<td>".$cat_info['title']."</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
			echo "</tr>\n";
			
			foreach($payees as $index => $payee_info)
			{
				$payee = $payee_info['title'];
				$display = $payee_info['display'];
				$disabled = $payee_info['disabled'] ? "disabled" : "";
				
				echo "<tr class='row1' id='row_" . $index . "_" . $bank_id . "' style='display:$display;'>\n";
				echo "<td></td><td>$payee</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
				foreach($rate_cats as $cat_field=> $cat_info)
				{
					$frm_name = "frmb_tier_" . $bank_id . "_" . $payee . "_" . "d" . "_" . $cat_field;
					$frm_value = isset($_POST[$frm_name]) ? $_POST[$frm_name] : $info['default'][$payee][$cat_field];
					echo "<td><input id='$frm_name' name='$frm_name' type='text' size=4 style='border:1px solid #000000' value='$frm_value'/ $disabled></td><td></td>\n";
				}
				echo "</tr>\n";
			}
			echo "<tr class='row2'>\n";
			echo "<td></td><td>Calculations</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
			foreach($rate_cats as $cat_field=> $cat_info)
			{
				echo "<td>\n";
				echo $rates_class->calc_rates($info['default'],$cat_field);
				echo "</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
			}			
			echo "</tr>\n";
			
			echo "<tr class='row1'>\n";
			echo "<td></td><td>Cascade with Bank</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
			echo "<td colspan=" . (sizeof($rate_cats)*2) . ">\n";

			$frm_name = "frmb_tier_" . $bank_id . "_" . "Bank" . "_" . "d" . "_" . "secnd";

			echo "<select name='$frm_name'>\n";
			echo "<option value=''>None</option>\n";
			foreach($banks as $sec_bank_id => $sec_bank_info)
				if($bank_id != $sec_bank_id)
					if($company_rates[$sec_bank_id] != NULL)
						if($info['default']['Bank']['secnd'] != $sec_bank_info['bank_id'])
							echo "<option value='" . $sec_bank_info['bank_id'] . "'>" . $sec_bank_name . "</option>\n";
						else
							echo "<option value='" . $sec_bank_info['bank_id'] . "' selected>" . $sec_bank_name . "</option>\n";
			echo "</select>\n";
			$custom = get_bank_custom_fields($bank_id,$info['custom']);
			if($custom['fields'])
			{
				echo "</td></tr><tr><td colspan='10'><hr />\n";
				
				if($custom['fields']['labels'])
					echo "</td></tr><tr align='center'><td colspan='3'>\n";
				
				foreach($custom['fields']['labels'] as $key=>$data)
				
					echo "</td><td colspan='4'>$data \n";
				
				foreach($custom['fields']['array'] as $key=>$array)
				{
					echo "</td></tr>\n";
					
					echo "<tr><td></td><td>".$array['label']."</td><td>&nbsp;&nbsp;&nbsp;</td>\n";
					foreach($array['group'] as $key=>$data)
					{
						echo "</td><td colspan='4'>\n";
			
						echo "<input name='$key' value='".$data['value']."'>\n";
					}
				}
			}
		}
	}
	echo "</table>\n";
	
	endTable("Update Rates and Fees","editCompanyProfileRates.php",NULL,NULL,true);

include("includes/footer.php");

function get_bank_custom_fields($bank_id,$custom=NULL)
{
	global $en_ID;
	switch(intval($bank_id))
	{
		case 33:
		case 32:
		case 43:
		case 44:
			$sql = "select cs_ID,cs_name from cs_company_sites where cs_en_ID = '$en_ID' and cs_verified in ('approved','non-compliant')";
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$fields['labels']['tid'] = "Website SID/TID";
			//$fields['labels']['mid'] = "Website MID";
			$fields['labels']['desc'] = "Website Descriptor";
			while($site = mysql_fetch_assoc($result))
			{
				$key = "tid_".$bank_id."_".$site['cs_ID'];
				if($_POST[$key])
					$custom['tid_sites'][$site['cs_ID']] = $_POST[$key];
					
				$fields['array'][$site['cs_ID']]['label'] = $site['cs_name'].":";
				$fields['array'][$site['cs_ID']]['group'][$key]['value'] = $custom['tid_sites'][$site['cs_ID']];
				
				//$key = "mid_".$bank_id."_".$site['cs_ID'];
				//if($_POST[$key])
				//	$custom['mid_sites'][$site['cs_ID']] = $_POST[$key];
					
				//$fields['array'][$site['cs_ID']]['group'][$key]['value'] = $custom['mid_sites'][$site['cs_ID']];
				
				
				$key = "desc_".$bank_id."_".$site['cs_ID'];
				if($_POST[$key])
					$custom['desc_sites'][$site['cs_ID']] = $_POST[$key];
					
				$fields['array'][$site['cs_ID']]['group'][$key]['value'] = $custom['desc_sites'][$site['cs_ID']];
			}
		
		break;

	}
	return array('data'=>$custom,'fields'=>$fields);
}

?>
