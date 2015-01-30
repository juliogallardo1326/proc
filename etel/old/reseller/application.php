<?php


$pageConfig['Title'] = "Reseller Application";
if(!$headerInclude)
	$headerInclude="startHere";
require_once("includes/header.php");
require_once("../includes/updateAccess.php");

$en_ID = intval($companyInfo['en_ID']);
$step = intval($_REQUEST['step']);
	
if($companyInfo['en_info']['Reseller']['Completion']<2)
{
	step_1($en_ID);
	
	if($companyInfo['en_info']['Reseller']['Completion']<1)
	{
		$companyInfo['en_info']['Reseller']['Completion']=1;
	}
	//, 1 as 'User Job Title',  1 as 'Monthly Affiliate Volume' 
	
	step_2($en_ID);
	$update['Reseller']['Completion']=2;
	
	etel_update_serialized_field('cs_entities','en_info'," en_ID = '$en_ID'",$update);
		
	toLog('completedapplication','reseller', '', $en_ID);
	beginTable();
	echo "<b>You have successfully completed your Reseller Application.</b>";
	endTable("Reseller Application Complete!","resellerContract.php",true,false,true);
}
else
{
	if($step==2) step_2($en_ID,false);
	else step_1($en_ID,false);
}
die();

function draw_step_buttons($step=1,$disable=false)
{
	if($disable) $disabled = ' disabled';
	else $disabled = '';
	$buttons[$step]['html'] .= "style='background-color:#333333'";
	$buttons[1]['html'] .= $disabled;
	$buttons[2]['html'] .= $disabled;
	$buttons[3]['html'] .= $disabled;
?>
	<div align='center'><table class='report'><tr class="large">
	  <td class="header" align="center">Step #1 </td>
	<td class="header" align="center">Step #2 </td>
	</tr>
	    <tr class="large" >
	      <td class="row1"><input type="button" name="Submit" value="General Info" class="bigbutton" onclick="document.location.href='application.php?step=1'" <?=$buttons[1]['html']?> /></td>
          <td class="row2"><input type="button" name="Submit" value="Banking Info" class="bigbutton" onclick="document.location.href='application.php?step=2'" <?=$buttons[2]['html']?> /></td>
      </tr>
	</table>
	</div>
<?

}

function step_1($en_ID,$skipIfComplete=true)
{
	global $companyInfo,$etel_process_volume;
	// Step 1
	$access = array();
	$access['SerializedData']['Source'] = 'en_info';
	$access['SerializedData']['Data'] = array(
		'Company_Url'=> array('General_Info','Company_Url'),
		'Sex'=> array('General_Info','Sex'),
		'Date_of_Birth'=> array('General_Info','Date_of_Birth'),
		'Address'=> array('General_Info','Address'),
		'Zip_Code'=> array('General_Info','Zip_Code'),
		'City'=> array('General_Info','City'),
		'State'=> array('General_Info','State'),
		'Country'=> array('General_Info','Country'),
		'Contact_Phone'=> array('General_Info','Contact_Phone'),
		'Time_Zone'=> array('General_Info','Time_Zone'),
		'Contact_Fax'=> array('General_Info','Contact_Fax'),
		'Personal_Phone'=> array('General_Info','Personal_Phone'),
		'Hear_About_Us'=> array('General_Info','Hear_About_Us'),
		'Monthly_Merchant_Apps'=> array('Reseller_Info','Monthly_Merchant_Apps'),
		'Average_Monthly_Merchant_Volume'=> array('Reseller_Info','Average_Monthly_Merchant_Volume')
	);
	
	$access = getAccessInfo("
	
	en_ID,
	en_info,
	
	'Personal Info' as access_header,
  		en_firstname, en_lastname, 1 as 'Date_of_Birth', 1 as 'Sex', 1 as 'Personal_Phone', 1 as 'Time_Zone',
		
	'Location Info' as access_header,
		1 as 'Address', 1 as 'Zip_Code', 1 as 'City', 1 as 'State', 1 as 'Country', 
		
	'Company Info' as access_header,
		en_company, 1 as 'Company_Url', 1 as 'Contact_Phone', 1 as 'Contact_Fax', 1 as 'Monthly_Merchant_Apps', 1 as 'Average_Monthly_Merchant_Volume', 1 as 'Hear_About_Us', 1 as 'Hear_About_Us_Select'
	",
	
	"cs_entities",
	"en_ID = '$en_ID'",
	array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Valid'=>'req','Length'=>32),
	$access
	);
	
	if($access==-1) dieLog("Invalid Company","Invalid Company");

	$access['Data']['en_ID']['Input']="hidden";
	$access['Data']['en_ID']['disable']=1;
	
	$access['Data']['en_firstname']['DisplayName'] = 'First Name';
	$access['Data']['en_lastname']['DisplayName'] = 'Last Name';
	$access['Data']['en_company']['DisplayName'] = 'Company Name';

	$access['Data']['Date_of_Birth']['Type'] = 'date';
	$access['Data']['Company_Url']['Valid'] = 'url';
	$access['Data']['Zip_Code']['Valid'] = 'zipcode';
	$access['Data']['Contact_Phone']['Valid'] = 'phone';
	$access['Data']['Contact_Phone']['Type'] = 'phone';
	$access['Data']['Personal_Phone']['Valid'] = 'phone';
	$access['Data']['Personal_Phone']['Type'] = 'phone';
	$access['Data']['Personal_Phone']['Type'] = 'phone';
	$access['Data']['Sex']['Input']='selectcustomarray';
	$access['Data']['Sex']['Input_Custom']=array('Male'=>'Male','Female'=>'Female');
	$access['Data']['Country']['Style']='width:205px;';
	$access['Data']['Country']['Input']='selectcustom';
	$access['Data']['Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
	$access['Data']['State']['Input']='selectcustom';
	$access['Data']['State']['Style']='width:205px;';
	$access['Data']['State']['Input_Custom']="Select st_abbrev,st_full From cs_states";
	
	$access['Data']['Monthly_Merchant_Apps']['Input']="selectcustomarray";
	$access['Data']['Monthly_Merchant_Apps']['Input_Custom'] = $etel_process_volume;
	
	$monthlyapps = array(5=>"5 or Less",10=>"6-10",25=>"10-25",50=>"25-50",100=>"50-100",200=>"Greater than 100");

	$access['Data']['Average_Monthly_Merchant_Volume']['Input']="selectcustomarray";
	$access['Data']['Average_Monthly_Merchant_Volume']['Input_Custom'] = $monthlyapps;
	
	$access['Data']['Hear_About_Us']['DisplayName']="How did you hear about us?";
	
	global $etel_hear_about_us;
	$access['Data']['Hear_About_Us_Select']['DisplayName']="Other";
	$access['Data']['Hear_About_Us_Select']['Value']=$access['Data']['Hear_About_Us']['Value'];
	$access['Data']['Hear_About_Us_Select']['Input']='selectcustomarray';
	$access['Data']['Hear_About_Us_Select']['Input_Custom']=$etel_hear_about_us;
	$access['Data']['Hear_About_Us_Select']['InputAdditional']='onchange="$(Hear_About_Us).value=this.value"';
	$access['Data']['Hear_About_Us_Select']['ExcludeQuery']=true;
	$access['Data']['Hear_About_Us_Select']['Valid'] = '';
	
	global $etel_timezone;
	$access['Data']['Time_Zone']['Input_Custom']=$etel_timezone;
	$access['Data']['Time_Zone']['Input']='selectcustomarray';
	$access['Data']['Time_Zone']['DisplayName']='Your Time Zone';
	$access['Data']['Time_Zone']['Style']='width:205px;';
	
	$access['SubmitValue'] = 'Update Information';
	if($skipIfComplete)	
	$access['SubmitValue'] = 'Continue to Step 2';
	$access['SubmitName'] = 'submit_step1';

	// Submit
		
	if($_POST[$access['SubmitName']])
	{
		$result = processAccessForm(&$access);
		$showvalidation = true;
	}
	$access['Columns']=1;	
	
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
	
	if(!$valid || !$skipIfComplete)
	{
		draw_step_buttons(1,$skipIfComplete);
		$access['HeaderMessage']="Please Complete all required fields to continue.";
		beginTable();
		writeAccessForm(&$access);
		endTable("Step #1 - Personal Information","");
		
		include("../includes/footer.php");
		die();
	}
	
}

	
	
function step_2($en_ID,$skipIfComplete=true)
{
	global $companyInfo,$etel_routing_types;
	// Step 2
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
	

//if($companyInfo['cd_bank_routingcode']) $payment['Payment_Data']['Wire']['Bank_Routing_Type'] = $routingTypes[$companyInfo['cd_bank_routingcode']];
//if($companyInfo['bank_IBRoutingCodeType']) $payment['Payment_Data']['Wire']['Intermediary_Bank_Routing_Type'] = $routingTypes[$companyInfo['bank_IBRoutingCodeType']];
	
	$access = getAccessInfo("
	
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
	
	"cs_entities",
	"en_ID = '$en_ID'",
	array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Length'=>32,'Valid'=>'req'),
	$access
	);
	
	if($access==-1) dieLog("Invalid Company","Invalid Company");

	$access['Data']['en_ID']['Input']="hidden";
	$access['Data']['en_ID']['disable']=1;
	
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
	
	$access['SubmitValue'] = 'Update Information';
	if($skipIfComplete)	
	$access['SubmitValue'] = 'Continue to Step 3';
	$access['SubmitName'] = 'submit_step2';

	// Submit
		
	$showvalidation = false;
	if($_POST[$access['SubmitName']] || $_POST['Method'])
	{
		$result = processAccessForm(&$access);
		$showvalidation = true;
	}
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
	// Validate
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
	if(!$valid || !$skipIfComplete)
	{
		draw_step_buttons(2,$skipIfComplete);	
		$access['HeaderMessage']="Please Complete all required fields to continue.";
		beginTable();
		writeAccessForm(&$access);
		endTable("Step #2 - Company Information","");
		
		include("includes/footer.php");
		die();
	}
}





?>