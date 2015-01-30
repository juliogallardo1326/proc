<?
$pageConfig['Title'] = "Merchant Wire Instructions";
$headerInclude="profile";
require_once("includes/header.php");
require_once("includes/updateAccess.php");

$userId = intval($companyInfo['userId']);

$access = array();
$access['SerializedData']['Source'] = 'en_info';
	
$access['SerializedData']['Data'] = array(
	'Method'=> array('Payment_Data','Method'),

	'ACH_Bank_Name'=> array('Payment_Data','ACH','Bank_Name'),
	'ACH_Bank_Address'=> array('Payment_Data','ACH','Bank_Address'),
	'ACH_Bank_ZipCode'=> array('Payment_Data','ACH','Bank_ZipCode'),
	'ACH_Bank_City'=> array('Payment_Data','ACH','Bank_City'),
	'ACH_Bank_State'=> array('Payment_Data','ACH','Bank_State'),
	'ACH_Bank_Country'=> array('Payment_Data','ACH','Bank_Country'),
	'ACH_Bank_Phone'=> array('Payment_Data','ACH','Bank_Phone'),
	'ACH_Bank_Beneficiary_Name'=> array('Payment_Data','ACH','Bank_Beneficiary_Name'),
	'ACH_Bank_Account_Name'=> array('Payment_Data','ACH','Bank_Account_Name'),
	'ACH_Bank_Account_Number'=> array('Payment_Data','ACH','Bank_Account_Number'),
	'ACH_Bank_Routing_Number'=> array('Payment_Data','ACH','Bank_Routing_Number'),
	'ACH_Bank_Additional_Notes'=> array('Payment_Data','ACH','Bank_Additional_Notes'),
	
	'Wire_Bank_Name'=> array('Payment_Data','Wire','Bank_Name'),
	'Wire_Bank_Address'=> array('Payment_Data','Wire','Bank_Address'),
	'Wire_Bank_ZipCode'=> array('Payment_Data','Wire','Bank_ZipCode'),
	'Wire_Bank_City'=> array('Payment_Data','Wire','Bank_City'),
	'Wire_Bank_State'=> array('Payment_Data','Wire','Bank_State'),
	'Wire_Bank_Country'=> array('Payment_Data','Wire','Bank_Country'),
	'Wire_Bank_Phone'=> array('Payment_Data','Wire','Bank_Phone'),
	'Wire_Bank_Beneficiary_Name'=> array('Payment_Data','Wire','Bank_Beneficiary_Name'),
	'Wire_Bank_Account_Name'=> array('Payment_Data','Wire','Bank_Account_Name'),
	'Wire_Bank_Account_Number'=> array('Payment_Data','Wire','Bank_Account_Number'),
	'Wire_Bank_Routing_Number'=> array('Payment_Data','Wire','Bank_Routing_Number'),
	'Wire_Bank_Routing_Type'=> array('Payment_Data','Wire','Bank_Routing_Type'),
	'Wire_Bank_Sort_Code'=> array('Payment_Data','Wire','Bank_Sort_Code'),
	'Wire_Bank_VAT_Number'=> array('Payment_Data','Wire','Bank_VAT_Number'),
	'Wire_Bank_Registration_Number'=> array('Payment_Data','Wire','Bank_Registration_Number'),
	'Wire_Bank_Additional_Notes'=> array('Payment_Data','Wire','Bank_Additional_Notes'),
	
	'Wire_Intermediary_Bank_Routing_Type'=> array('Payment_Data','Wire','Intermediary_Bank_Routing_Type'),
	'Wire_Intermediary_Bank_Routing_Number'=> array('Payment_Data','Wire','Intermediary_Bank_Routing_Number'),
	'Wire_Intermediary_Bank_Name'=> array('Payment_Data','Wire','Intermediary_Bank_Name'),
	'Wire_Intermediary_Bank_City'=> array('Payment_Data','Wire','Intermediary_Bank_City'),
	'Wire_Intermediary_Bank_State'=> array('Payment_Data','Wire','Intermediary_Bank_State')
);

//$access = getMerchantAccess();
$access = getAccessInfo("

	userId,
	en_ID,
	en_info,
	
	'Payment Method' as access_header,
	1 as 'Method',
	
	'ACH Banking Info' as access_header,
		1 as 'ACH_Bank_Name', 1 as 'ACH_Bank_Address', 1 as 'ACH_Bank_ZipCode', 1 as 'ACH_Bank_City', 1 as 'ACH_Bank_State', 
		1 as 'ACH_Bank_Country', 1 as 'ACH_Bank_Phone', 1 as 'ACH_Bank_Beneficiary_Name', 1 as 'ACH_Bank_Account_Name', 1 as 'ACH_Bank_Account_Number', 
		1 as 'ACH_Bank_Routing_Number', 1 as 'ACH_Bank_Additional_Notes',
		
	'Wire Banking Info' as access_header,
		1 as 'Wire_Bank_Name', 1 as 'Wire_Bank_Address', 1 as 'Wire_Bank_ZipCode', 1 as 'Wire_Bank_City', 1 as 'Wire_Bank_State', 
		1 as 'Wire_Bank_Country', 1 as 'Wire_Bank_Phone', 1 as 'Wire_Bank_Beneficiary_Name', 1 as 'Wire_Bank_Account_Name', 1 as 'Wire_Bank_Account_Number', 
		1 as 'Wire_Bank_Routing_Number', 1 as 'Wire_Bank_Routing_Type', 1 as 'Wire_Intermediary_Bank_Routing_Number',
		1 as 'Wire_Intermediary_Bank_Routing_Type',	1 as 'Wire_Intermediary_Bank_Name', 1 as 'Wire_Intermediary_Bank_City', 1 as 'Wire_Intermediary_Bank_State', 
		1 as 'Wire_Bank_Sort_Code', 1 as 'Wire_Bank_VAT_Number', 1 as 'Wire_Bank_Registration_Number', 1 as 'Wire_Bank_Additional_Notes'

",

"cs_companydetails left join cs_entities on en_type='merchant' and en_type_ID = userId",
"userId = '$userId' ",
array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Length'=>32,'Valid'=>'req'),$access);

/*
echo "<table width='100%'><tr><td>";
echo "<pre>";
print_r($access);
echo "</pre>";
echo "</td></tr></table>";
exit();
*/

if($access==-1) dieLog("Invalid Company","Invalid Company");

$access['Data']['en_ID']['disable']=1;
$access['Data']['en_ID']['Input']="hidden";
$access['Data']['userId']['disable']=1;
$access['Data']['userId']['Input']="hidden";


$access['Data']['Method']['Input']="selectcustomarray";
$access['Data']['Method']['Input_Custom'] = array(''=>'Please Select a Method','Wire'=>'Wire (International)','ACH'=>'ACH (US Only)');
$access['Data']['Method']['InputAdditional']='onchange="this.form.submit()"';

$access['Data']['ACH_Bank_Additional_Notes']['Valid']='';

$access['Data']['Wire_Bank_Additional_Notes']['Input']='textarea';
$access['Data']['Wire_Bank_Additional_Notes']['Size']='50';
$access['Data']['Wire_Bank_Additional_Notes']['Rows']='6';
$access['Data']['ACH_Bank_Additional_Notes']['Input']='textarea';
$access['Data']['ACH_Bank_Additional_Notes']['Size']='50';
$access['Data']['ACH_Bank_Additional_Notes']['Rows']='6';

$access['Data']['Wire_Bank_Sort_Code']['Valid']='';
$access['Data']['Wire_Bank_VAT_Number']['Valid']='';
$access['Data']['Wire_Bank_Registration_Number']['Valid']='';
$access['Data']['Wire_Bank_Additional_Notes']['Valid']='';
$access['Data']['Wire_Bank_Additional_Notes']['RowDisplay']='Wide';
$access['Data']['ACH_Bank_Additional_Notes']['Valid']='';
$access['Data']['ACH_Bank_Additional_Notes']['RowDisplay']='Wide';

$access['Data']['Wire_Bank_Country']['Style']='width:205px;';
$access['Data']['Wire_Bank_Country']['Input']='selectcustom';
$access['Data']['Wire_Bank_Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
$access['Data']['Wire_Bank_Country']['InputAdditional']="onchange='".
"$(\"Wire_Intermediary_Bank_Routing_Number\").setAttribute(\"valid\",(this.value!=\"US\"?\"req\":\"\"));".
"$(\"Wire_Intermediary_Bank_Routing_Type\").setAttribute(\"valid\",(this.value!=\"US\"?\"req\":\"\"));".
"$(\"Wire_Intermediary_Bank_Name\").setAttribute(\"valid\",(this.value!=\"US\"?\"req\":\"\"));".
"$(\"Wire_Intermediary_Bank_City\").setAttribute(\"valid\",(this.value!=\"US\"?\"req\":\"\"));".
"$(\"Wire_Intermediary_Bank_State\").setAttribute(\"valid\",(this.value!=\"US\"?\"req\":\"\"));".
"';".

$access['Data']['Wire_Bank_State']['Input']='selectcustom';
$access['Data']['Wire_Bank_State']['Style']='width:205px;';
$access['Data']['Wire_Bank_State']['Input_Custom']="Select st_abbrev,st_full From cs_states";


$access['Data']['ACH_Bank_Country']['Style']='width:205px;';
$access['Data']['ACH_Bank_Country']['Input']='selectcustom';
$access['Data']['ACH_Bank_Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
$access['Data']['ACH_Bank_State']['Input']='selectcustom';
$access['Data']['ACH_Bank_State']['Style']='width:205px;';
$access['Data']['ACH_Bank_State']['Input_Custom']="Select st_abbrev,st_full From cs_states";

$access['Data']['Wire_Intermediary_Bank_Routing_Type']['Input']="selectcustomarray";
$access['Data']['Wire_Intermediary_Bank_Routing_Type']['Input_Custom']=$etel_routing_types;
$access['Data']['Wire_Bank_Routing_Type']['Input']="selectcustomarray";
$access['Data']['Wire_Bank_Routing_Type']['Input_Custom']=$etel_routing_types;

$showvalidation = false;
if($_POST['Method'])
{
	$result = processAccessForm(&$access);
	if($result) $msg= "Company Updated Successfully (".$result['cnt']." Field(s))";
	else $msg= "No Updates Detected";
	$showvalidation = true;
}

$access['HeaderMessage']=$msg;
$access['Columns']=1;	

// Display Parsing

foreach($access['Data'] as $key=>$data)
{
	$access['Data'][$key]['DisplayName'] = preg_replace('/Wire |ACH /','',$access['Data'][$key]['DisplayName']);
		
	if($access['Data']['Method']['Value']!='Wire')
	{
		if(strpos($key,'Wire_')!==false)
			unset($access['Data'][$key]);
		if($access['Data'][$key]['Name'] == 'access_header' && $access['Data'][$key]['Value']=='Wire Banking Info') unset($access['Data'][$key]);
	}
	
	if($access['Data']['Method']['Value']!='ACH')
	{
		if(strpos($key,'ACH_')!==false)
			unset($access['Data'][$key]);
		if($access['Data'][$key]['Name'] == 'access_header' && $access['Data'][$key]['Value']=='ACH Banking Info') unset($access['Data'][$key]);
	}
}

if($access['Data']['Wire_Bank_Country']['Value']=='US')
{
	$access['Data']['Wire_Intermediary_Bank_Routing_Type']['Valid']='';
	$access['Data']['Wire_Intermediary_Bank_Routing_Number']['Valid']='';
	$access['Data']['Wire_Intermediary_Bank_Name']['Valid']='';
	$access['Data']['Wire_Intermediary_Bank_City']['Valid']='';
	$access['Data']['Wire_Intermediary_Bank_State']['Valid']='';
}

$valid = true;
foreach($access['Data'] as $key=>$data)
{
	if(!$data['Value'] && $data['Valid']) 
	{
		$valid = false;
		if ($showvalidation)
			$access['Data'][$key]['Highlight'] = true;
	}
}

if(!$valid) $access['HeaderMessage']="Please Complete all required fields to continue.";
beginTable();
writeAccessForm(&$access);
endTable("Update Wire Instructions - ".$companyInfo['companyname'],"");

include("includes/footer.php");
?>
