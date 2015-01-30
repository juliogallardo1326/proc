<?php

$company_id = intval($_REQUEST['company_id']);
$en_ID = intval($_REQUEST['entity_id']);
if(!$en_ID) $en_ID = intval($_REQUEST['en_ID']);
if(!$company_id) $company_id = intval($_REQUEST['userId']);
if(!$company_id) $company_id = intval($_REQUEST['userIdList']);

$pageConfig['AllowBank'] = true;
$pageConfig['Title'] = "Merchant Details";
require_once("includes/sessioncheck.php");
require_once("../includes/completion.php");
require_once("../includes/updateAccess.php");

$markComp = "Mark this Company";

$headerInclude = "companies";
include("includes/header.php");


$gw_options=NULL;
if($adminInfo['li_level'] == 'full')
{
	foreach($etel_gw_list as $gw)
	{
		if($gw['gw_database']==$etel_gw_list[$_SESSION['gw_id']]['gw_database']) $gw_options[$gw['gw_id']]=$gw['gw_title'];
	}
	if(is_array($gw_options)) if (sizeof($gw_options)<2) $gw_options = NULL;
}

$search = "userId = $company_id";
if($en_ID)
	$search = "en_ID = $en_ID";
//$access = getMerchantAccess();

$access['SerializedData']['Source'] = 'en_info';
$access['SerializedData']['Data'] = array(
	'General_Notes'=> array('General_Notes'),
	'Company_Url'=> array('General_Info','Company_Url'),
	'Company_Legal_Name'=> array('General_Info','Company_Legal_Name'),
	'Company_Address'=> array('General_Info','Company_Address'),
	'Company_Fax_DBA'=> array('General_Info','Company_Fax_DBA'),
	'Company_Tech_Contact'=> array('General_Info','Company_Tech_Contact'),
	'Incorporated_Country'=> array('General_Info','Incorporated_Country'),
	'Incorporated_Number'=> array('General_Info','Incorporated_Number'),
	'Sex'=> array('General_Info','Sex'),
	'Date_of_Birth'=> array('General_Info','Date_of_Birth'),
	'Address'=> array('General_Info','Address'),
	'Zip_Code'=> array('General_Info','Zip_Code'),
	'City'=> array('General_Info','City'),
	'State'=> array('General_Info','State'),
	'Country'=> array('General_Info','Country'),
	'Contact_Phone'=> array('General_Info','Contact_Phone'),
	'Cell_Phone'=> array('General_Info','Cell_Phone'),
	'Contact_IM'=> array('General_Info','Contact_IM'),
	'Time_Zone'=> array('General_Info','Time_Zone'),
	'Contact_Fax'=> array('General_Info','Contact_Fax'),
	'Personal_Phone'=> array('General_Info','Personal_Phone'),
	'Personal_Fax'=> array('General_Info','Personal_Fax'),
	'Hear_About_Us'=> array('General_Info','Hear_About_Us'),
	'Service_List'=> array('Processing_Info','Service_List'),
	'Anti_Fraud_System'=> array('Processing_Info','Anti_Fraud_System'),
	'CS_Program'=> array('Processing_Info','CS_Program'),
	'Refund_Policy'=> array('Processing_Info','Refund_Policy'),
	'Volume_Last_month'=> array('Processing_Info','Volume_Last_month'),
	'Volume_Prev_30Days'=> array('Processing_Info','Volume_Prev_30Days'),
	'Volume_Prev_60Days'=> array('Processing_Info','Volume_Prev_60Days'),
	'Volume_Forcast_1Month'=> array('Processing_Info','Volume_Forcast_1Month'),
	'Volume_Forcast_2Month'=> array('Processing_Info','Volume_Forcast_2Month'),
	'Volume_Forcast_3Month'=> array('Processing_Info','Volume_Forcast_3Month'),
	'Projected_Monthly_Sales'=> array('Processing_Info','Projected_Monthly_Sales'),
	'Average_Ticket_Price'=> array('Processing_Info','Average_Ticket_Price'),
	'Chargeback_%'=> array('Processing_Info','Chargeback_%'),
	'Previous_Processor_Trans_Fee'=> array('Processing_Info','Previous_Processor_Trans_Fee'),
	'Previous_Processor_Disc_Fee'=> array('Processing_Info','Previous_Processor_Disc_Fee'),
	'Previous_Processing'=> array('Processing_Info','Previous_Processing'),
	'Previous_Processor_Reason'=> array('Processing_Info','Previous_Processor_Reason'),
	'Recur_Billing'=> array('Processing_Info','Recur_Billing'),
	'Currently_Processing'=> array('Processing_Info','Currently_Processing')
);
		
$access = getAccessInfo("

userId,
en_ID,
en_info,


'General Info' as access_header,
en_username, en_password,
en_ref, 1 as Merchant_Websites, 1 as Merchant_Documents, 1 as Merchant_Payouts,  en_company,1 as 'Company_Url' ,

en_email, 1 as 'Contact_Phone',1 as 'Company_Tech_Contact', 1 as 'Contact_IM',1 as 'Time_Zone', 1 as 'Contact_Fax', 1 as 'Company_Fax_DBA',1 as 'Company_Address',1 as 'Hear_About_Us' ,

'Personal Info' as access_header,
en_firstname,en_lastname,1 as 'Date_of_Birth',
1 as 'Sex',1 as 'Address',1 as 'City', 1 as 'State', 1 as 'Country',1 as 'Zip_Code',1 as 'Personal_Phone',1 as 'Personal_Fax', 1 as 'Cell_Phone',


'Processing/Company Info' as access_header,
 transaction_type as 'Transaction_Type', 1 as 'Company_Legal_Name', 1 as 'Incorporated_Country', 1 as 'Incorporated_Number', 1 as 'Previous_Processing', 1 as 'Previous_Processor_Reason', 1 as 'Previous_Processor_Disc_Fee', 1 as 'Previous_Processor_Trans_Fee', 1 as 'Chargeback_%', 1 as 'Recur_Billing', 1 as 'Currently_Processing', 1 as 'Service_List', 1 as 'Average_Ticket_Price',1 as 'Volume_Last_month',1 as 'Volume_Prev_30Days',1 as 'Volume_Prev_60Days',1 as 'Volume_Forcast_1Month', 1 as 'Volume_Forcast_2Month',1 as 'Volume_Forcast_3Month', 1 as 'Projected_Monthly_Sales', 1 as 'Anti_Fraud_System',1 as 'CS_Program',1 as 'Refund_Policy',
 
'Notes' as access_header,
1 as 'Account_Rep',
1 as 'General_Notes',

'Merchant Status' as access_header,
1 as 'Reseller',
cd_completion, en_gateway_ID,

activeuser,
0 as Email_Active_Notification,

cd_merchant_show_contract,
0 as Email_Contract_Notification,
cd_enable_tracking,
cd_pay_status,
cd_ignore,
 cd_custom_contract, 
if(merchant_contract_agree='1','Yes','No') as Contract_Signed,
Date_Format(FROM_UNIXTIME(cd_contract_date),'%W %M %D %Y') as Date_Signed,
cd_contract_ip as Signed_IP,

'Merchant Settings' as access_header,
cd_allow_rand_pricing, cd_enable_price_points, cd_max_transaction,cd_max_volume,
cd_pay_bimonthly, cd_payperiod, cd_paydelay, cd_wirefee, cd_rollover, cd_appfee, 
cd_appfee_upfront, cs_monthly_charge, cd_next_pay_day, cd_orderpage_settings,
 cd_approve_timelimit, cd_fraudscore_limit, cd_orderpage_disable_fraud_checks, 0 as merchant_log
",

"cs_entities left join cs_companydetails on en_type='merchant' and en_type_ID = userId",
$search,
array('Size'=>30,'Rows'=>2),$access);

if($access==-1) dieLog("Invalid Company","Invalid Company");

	
$access['EntityManager'] = true;
$access['EnablePlusMinus'] = true;
$company_id = $access['Data']['userId']['Value'];
$en_ID = $access['Data']['en_ID']['Value'];
$access['Data']['userId']['Input']="hidden";
$access['Data']['en_ID']['Input']="hidden";

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

$access['Data']['en_password']['DisplayName']='Reset Pass';
$access['Data']['en_password']['ExcludeQuery']=true;
$access['Data']['en_password']['InputAdditional']="onchange='this.value=this.value.toLowerCase()'";
$_POST['en_username'] = strtolower($_POST['en_username']);
$_POST['en_password'] = strtolower($_POST['en_password']);
if($_POST['en_password'] || $_POST['en_username'] != $access['Data']['en_username']['Value'])
{
	if($_POST['en_password']) $access['Data']['en_password']['ExcludeQuery']=false;
	if($_POST['en_username'] && !$_POST['en_password']) {unset($_POST['en_username']);$access['HeaderMessage']="Please Enter a new Password if changing the Username<BR>";$access['Data']['en_username']['Highlight'] = true;}
	else $_POST['en_password'] = md5($_POST['en_username'].$_POST['en_password']);
}
$access['Data']['activeuser']['Input']='checkbox';
$access['Data']['activeuser']['DisplayName']='Merchant Live';
$access['Data']['activeuser']['InputAdditional']="onclick='".
'if(this.checked) {addElementNotes($("General_Notes"),"Merchant Turned Active."); $(Email_Active_Notification).checked=true;$(cd_completion).value = 10;}'.
' else {addElementNotes($("General_Notes"),"Merchant Turned Inactive."); $(cd_completion).value = 9;}'."'";

$access['Data']['cd_pay_status']['DisplayName']='Company Payable';
$access['Data']['cd_pay_status']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Payable Status Changed to "+this.value+".");'."'";

$access['Data']['cd_ignore']['Input']='checkbox';
$access['Data']['cd_ignore']['DisplayName']='Ignore Merchant';
$access['Data']['cd_ignore']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Ignore Set to "+(this.checked?"Yes":"No")+".");'."'";

$access['Data']['Email_Active_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Active_Notification']['Input']='checkbox';

// Account Rep

if($_POST['Account_Rep'] == 'add')
{
	$sql = "Insert into cs_entities_affiliates set ea_type = 'Representative',ea_affiliate_ID = '".$companyInfo['en_ID']."',ea_en_ID = '$en_ID'";
	$result = sql_query_write($sql) or dieLog($sql);
	$_POST['Account_Rep'] = $companyInfo['en_ID'];
}
if($_POST['Account_Rep'] == 'rem')
{
	$sql = "delete from cs_entities_affiliates where ea_type = 'Representative' and ea_affiliate_ID = '".$companyInfo['en_ID']."' and ea_en_ID = '$en_ID'";
	$result = sql_query_write($sql) or dieLog($sql);
	$_POST['Account_Rep'] = '';
}

$reps = array();
$sql = "Select en_ID,en_company From cs_entities, cs_entities_affiliates where en_ID = ea_affiliate_ID and ea_type = 'Representative' and ea_en_ID = '$en_ID'";
$result = sql_query_read($sql) or dieLog($sql);
while($aff = mysql_fetch_assoc($result))
	$reps[$aff['en_ID']]=$aff['en_company'];
if(!sizeof($reps)) $reps['']='No Representative';
if(!$reps[$companyInfo['en_ID']]) $reps['add']='Add Myself as Rep';
else $reps['rem']='Remove Myself as Rep';

$access['Data']['Account_Rep']['ExcludeQuery']=true;
$access['Data']['Account_Rep']['Input']='selectcustomarray';
$access['Data']['Account_Rep']['Input_Custom'] = $reps;
$access['Data']['Account_Rep']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Added Self as Rep.");'."'";

// Account Reseller

$ress = array();
$sql = "Select en_ID,en_company From cs_entities, cs_entities_affiliates where en_ID = ea_affiliate_ID and ea_type = 'Reseller' and ea_en_ID = '$en_ID'";
$result = sql_query_read($sql) or dieLog($sql);
while($aff = mysql_fetch_assoc($result))
	$ress[$aff['en_ID']]=$aff['en_company'];
if(!sizeof($ress)) $ress['']='No Reseller';

$access['Data']['Reseller']['ExcludeQuery']=true;
$access['Data']['Reseller']['Input']='selectcustomarray';
$access['Data']['Reseller']['Input_Custom']=$ress;

$access['Data']['cd_merchant_show_contract']['Input']='checkbox';
$access['Data']['cd_merchant_show_contract']['DisplayName']='Display Contract';
$access['Data']['cd_merchant_show_contract']['InputAdditional']="onclick='".
'if(this.checked) {addElementNotes($("General_Notes"),"Merchant Contract Displayed."); $(Email_Contract_Notification).checked=true; }'.
' else {addElementNotes($("General_Notes"),"Merchant Contract Disabled."); }'.
'if($(cd_completion).value<=3) $(cd_completion).value = 4;}'.
"'";

$access['Data']['Email_Contract_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Contract_Notification']['Input']='checkbox';

$access['Data']['cd_enable_tracking']['DisplayName']='Enable Shipment Tracking';
$access['Data']['cd_enable_tracking']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Gateway Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['cd_custom_contract']['Input']='checkbox';
$access['Data']['cd_custom_contract']['DisplayName']='Custom Contract';
if($access['Data']['cd_custom_contract']['Value'] || $_POST['cd_custom_contract']) 
	$access['Data']['cd_custom_contract']['AddHtml']="<a target='_blank' href='email_edit.php?et_custom_id=".$company_id."&et_name=merchant_contract'>Edit Contract</a>";
$access['Data']['Date_Signed']['disable']=true;

$access['Data']['en_gateway_ID']['DisplayName']="Gateway";
$access['Data']['en_gateway_ID']['Input']="selectcustomarray";	
$access['Data']['en_gateway_ID']['Input_Custom'] = $gw_options;
$access['Data']['en_gateway_ID']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Gateway Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['cd_completion']['DisplayName']="Completion";
$access['Data']['cd_completion']['Input']="selectcustomarray";	
$access['Data']['cd_completion']['Input_Custom'] = $etel_completion_array;
$access['Data']['cd_completion']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Completion Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['Contract_Signed']['disable']=true;
$access['Data']['Signed_IP']['disable']=true;

$access['Data']['Company_Legal_Name']['Length'] = 50;


$access['Data']['General_Notes']['Input']='textarea';
$access['Data']['General_Notes']['Rows']=28;
$access['Data']['General_Notes']['Style']='width:280px;';
$access['Data']['General_Notes']['InputAdditional']="onmousedown='addElementNotes($(\"General_Notes\"),\"\")'";
$access['Data']['General_Notes']['DisplayName']="Notes";

$access['Data']['cd_allow_rand_pricing']['DisplayName']="Allow Independant Pricing";
$access['Data']['cd_allow_rand_pricing']['Input']='checkbox';
$access['Data']['cd_enable_price_points']['DisplayName']="Allow Price Points";
$access['Data']['cd_enable_price_points']['Input']='checkbox';
$access['Data']['cd_max_transaction']['DisplayName']="Maximum Transaction Price";
$access['Data']['cd_max_volume']['DisplayName']="Maximum Volume Per Month";
$access['Data']['cd_pay_bimonthly']['DisplayName']="Pay Schedule";
$access['Data']['cd_payperiod']['DisplayName']="Pay Period (In Days)";
$access['Data']['cd_paydelay']['DisplayName']="Payout Hold (In Days)";
$access['Data']['cd_wirefee']['DisplayName']="Wire Fee";
$access['Data']['cd_wirefee']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant WireFee Changed to $"+this.value+".");'."'";

$access['Data']['cd_rollover']['DisplayName']="Rollover Amount";
$access['Data']['cd_appfee']['DisplayName']="Out of Processing Application Fee";
$access['Data']['cd_appfee']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Application Fee Changed to $"+this.value+".");'."'";

$access['Data']['cd_appfee_upfront']['DisplayName']="Up Front Application Fee";
$access['Data']['cs_monthly_charge']['DisplayName']="Monthly Fee";
$access['Data']['cs_monthly_charge']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Monthly Fee Changed to $"+this.value+".");'."'";

$access['Data']['cd_next_pay_day']['DisplayName']="Next Payday";
$access['Data']['cd_next_pay_day']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Next Payday Changed to $"+this.value+".");'."'";

$access['Data']['cd_orderpage_settings']['DisplayName']="Order Page Method";
$access['Data']['cd_approve_timelimit']['DisplayName']="Approve Time Limit (In Hours)";
$access['Data']['cd_fraudscore_limit']['DisplayName']="Fraud Score Limit (0 to 10)";
$access['Data']['cd_orderpage_disable_fraud_checks']['DisplayName']="Disable Fraud Scrubbing";
$access['Data']['cd_orderpage_disable_fraud_checks']['Input']='checkbox';

$access['Data']['en_username']['DisplayName']="Username";
$access['Data']['en_username']['InputAdditional']="onchange='this.value=this.value.toLowerCase()'";
$access['Data']['en_ref']['DisplayName']="Reference";
$access['Data']['en_company']['DisplayName']="Company Name";
$access['Data']['Company_Url']['DisplayName']="Company Website";
$access['Data']['Company_Tech_Contact']['DisplayName']="Tech Contact";
$access['Data']['Company_Url']['LinkTo']=true;
$access['Data']['Merchant_Websites']['Value'] = "<a href='confirmWebsite.php?cd_view=AL&searchby=id&search=$company_id'>Merchant Websites</a>";
$access['Data']['Merchant_Websites']['ExcludeQuery']=true;
$access['Data']['Merchant_Websites']['disable']=true;
$access['Data']['Merchant_Websites']['DisplayName']='Websites';
$access['Data']['Merchant_Documents']['Value'] = "<a href='confirmUploads.php?userIdList=$company_id&searchby=id&search=$company_id'>Merchant Documents</a>";
$access['Data']['Merchant_Documents']['ExcludeQuery']=true;
$access['Data']['Merchant_Documents']['disable']=true;
$access['Data']['Merchant_Documents']['DisplayName']='Documents';

$access['Data']['Merchant_Payouts']['Value'] = "<a href='paymentReport.php?cd_view=AL&searchby=id&search=$company_id'>Merchant Payout Information</a>";
$access['Data']['Merchant_Payouts']['ExcludeQuery']=true;
$access['Data']['Merchant_Payouts']['disable']=true;
$access['Data']['Merchant_Payouts']['DisplayName']='Payout';

//$access['Data']['email']['EmailTo']=true;
$access['Data']['en_email']['AddHtml']="<a target='_blank' href='massmail1.php?cd_view=AL&searchby=id&search=$company_id'>Send Mail</a>";

$access['Data']['Time_Zone']['Input_Custom']=$etel_timezone;
$access['Data']['Time_Zone']['Input']='selectcustomarray';
$access['Data']['Time_Zone']['DisplayName']='Your Time Zone';
$access['Data']['Time_Zone']['Style']='width:205px;';

$access['Data']['en_firstname']['DisplayName']="First Name";
$access['Data']['en_lastname']['DisplayName']="Last Name";
$access['Data']['en_email']['DisplayName']="Email Address";
$access['Data']['Country']['Input']='selectcustom';
$access['Data']['Country']['Style']='width:205px;';
$access['Data']['Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
$access['Data']['Incorporated_Country']['Input']='selectcustom';
$access['Data']['Incorporated_Country']['Style']='width:205px;';
$access['Data']['Incorporated_Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
$access['Data']['State']['Input']='selectcustom';
$access['Data']['State']['Style']='width:205px;';
$access['Data']['State']['Input_Custom']="Select st_abbrev,st_full From cs_states";
$access['Data']['Hear_About_Us']['DisplayName']="How did you hear about us?";
$access['Data']['Address']['DisplayName']="Address";
$access['Data']['Zip_Code']['DisplayName']="ZipCode";
$access['Data']['Personal_Phone']['DisplayName']="Personal Phone";
$access['Data']['Personal_Fax']['DisplayName']="Home Fax";
$access['Data']['Date_of_Birth']['DisplayName']="Date of Birth";
$access['Data']['Sex']['DisplayName']="Sex";
$access['Data']['en_ref']['disable']=1;
$access['Data']['Volume_Last_month']['Input']="selectvolume";
$access['Data']['Volume_Last_month']['DisplayName']="Last Month's Volume";
$access['Data']['Volume_Prev_30Days']['Input']="selectvolume";
$access['Data']['Volume_Prev_30Days']['DisplayName']="Volume 2 Months Ago";
$access['Data']['Volume_Prev_60Days']['Input']="selectvolume";
$access['Data']['Volume_Prev_60Days']['DisplayName']="Volume 3 Months Ago";
$access['Data']['Volume_Forcast_1Month']['Input']="selectvolume";
$access['Data']['Volume_Forcast_1Month']['DisplayName']="Forcast Volume This Month";
$access['Data']['Volume_Forcast_2Month']['Input']="selectvolume";
$access['Data']['Volume_Forcast_2Month']['DisplayName']="Forcast Volume Next Month";
$access['Data']['Volume_Forcast_3Month']['Input']="selectvolume";
$access['Data']['Volume_Forcast_3Month']['DisplayName']="Forcast Volume in two Months";
$access['Data']['Transaction_Type']['InputAdditional']="onchange='".
'addElementNotes($("General_Notes"),"Merchant Type Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['Previous_Processor_Reason']['DisplayName']="Previous Processor";
$access['Data']['Previous_Processor_Reason']['DisplayName']="Reason for Leaving Previous Processor";
$access['Data']['Recur_Billing']['DisplayName']="Recuring Billing";
$access['Data']['Currently_Processing']['DisplayName']="Currently Processing";
			

$access['Data']['merchant_log']['InputAdditional']='multiple="multiple"';
$access['Data']['merchant_log']['Input']='selectcustom';
$access['Data']['merchant_log']['Style']='width:280px;';
$access['Data']['merchant_log']['Rows']='40';
$access['Data']['merchant_log']['ExcludeQuery']=true;
$access['Data']['merchant_log']['disable']=true;
$access['Data']['merchant_log']['Input_Custom']="Select lg_ID, 	  
concat( Date_Format(from_unixtime( lg_timestamp ),'%m-%d %H:%i'),' - ',lg_action,': ',if(lg_txt is not null,lg_txt,'')) as log,
lg_txt as title
From cs_log where lg_actor = 'merchant' and lg_item_id = '$company_id' order by lg_ID desc limit 300";
unset($access['Data']['merchant_log']);
// Actions

if($_POST['submit_access'] == 'Submit') 
{
	if($_POST['Email_Contract_Notification'])
		send_email_template('contract_notification_email',$emaildata);
	
	if($_POST['Email_Active_Notification'])
		send_email_template('active_notification_email',$emaildata);
	

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

if($_POST['submit_access'] == 'Submit')
{
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

$access['Data']['en_password']['Value']='';
$access['HeaderMessage'].=$msg;

?>
	<table>
	<tr>
	<td><a href="editCompanyProfileAccess.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileWire.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileRates.php?entity_id=<?= $en_ID?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="confirmUploads.php?userIdList=<?= $company_id?>&searchby=id&search=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	</tr>
	</table>
<script language="javascript" src="<?=$rootdir?>/scripts/dynosearch.js"></script>

<?
beginTable();
writeAccessForm(&$access);
endTable("Update Company - ".$access['Data']['en_company']['Value'],"");

$JSON_data = JSON_get_data(array('func'=>'getEntityList','en_search'=>array($en_ID),'en_search_by'=>array('id'),'silent'=>1));

$json = new Services_JSON();
$JSON_output = $json->encode($JSON_data);
?>

<script language="javascript">
var response = Array();
response.responseText = '<?=str_replace("'","\'",$JSON_output)?>';
en_search_response(response);
</script>
<?

include("includes/footer.php");
?>