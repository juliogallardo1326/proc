<?php


$pageConfig['Title'] = "Merchant Application";
$headerInclude="startHere";
include("includes/sessioncheck.php");
require_once("includes/header.php");
require_once("includes/updateAccess.php");

$en_ID = intval($curUserInfo['en_ID']);
$userId = intval($curUserInfo['userId']);
$step = intval($_REQUEST['step']);

if($curUserInfo['cd_completion']<2)
{
	step_1($en_ID);
	if($curUserInfo['cd_completion']<1)
	{
		$sql = "update cs_companydetails set cd_completion=1 where userId='$userId'";
		sql_query_write($sql) or dieLog(mysql_error()." ~$sql");	
	}
	step_2($en_ID);
	step_3($en_ID);
	toLog('completedapplication','merchant', '', $en_ID);
			
	$sql = "update cs_companydetails set cd_completion=3 where userId='$userId'";
	sql_query_write($sql) or dieLog(mysql_error()." ~$sql");
	en_status_change_notify($en_ID);

	toLog('requestrates','merchant', '', $curUserInfo['userId']);
		
	beginTable();
	echo "<b>You have successfully completed your Merchant Application. Please proceed to the Request Rates section to submit your Rates Request.</b>";
	endTable("Merchant Application Complete!","merchantContract.php",true,false,true);
}
else
{
	if($step==3) step_3($en_ID,false);
	else if($step==2) step_2($en_ID,false);
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
	<td class="header" align="center">Step #3 </td>
	</tr>
	    <tr class="large" >
	      <td class="row1"><input type="button" name="Submit" value="Personal Info" class="bigbutton" onclick="document.location.href='application.php?step=1'" <?=$buttons[1]['html']?> /></td>
          <td class="row2"><input type="button" name="Submit" value="Company Info" class="bigbutton" onclick="document.location.href='application.php?step=2'" <?=$buttons[2]['html']?> /></td>
          <td class="row3"><input type="submit" name="Submit" value="Processing Info" class="bigbutton" onclick="document.location.href='application.php?step=3'" <?=$buttons[3]['html']?> /></td>
      </tr>
	</table>
	</div>
<?

}

function step_1($en_ID,$skipIfComplete=true)
{
	global $etel_timezone,$curUserInfo;
	// Step 1
	
$access['SerializedData']['Source'] = 'en_info';
$access['SerializedData']['Data'] = array(
	'Sex'=> array('General_Info','Sex'),
	'Date_of_Birth'=> array('General_Info','Date_of_Birth'),
	'Contact_IM'=> array('General_Info','Contact_IM'),
	'Time_Zone'=> array('General_Info','Time_Zone'),
	//'Personal_Fax'=> array('General_Info','Personal_Fax'),
	'Personal_Phone'=> array('General_Info','Personal_Phone'),
	'Cell_Phone'=> array('General_Info','Cell_Phone'),
	'Personal_Address'=> array('General_Info','Personal_Address'),
	'Personal_Zip_Code'=> array('General_Info','Personal_Zip_Code'),
	'Personal_City'=> array('General_Info','Personal_City'),
	'Personal_State'=> array('General_Info','Personal_State'),
	'Personal_Country'=> array('General_Info','Personal_Country'),
);
	$access = getAccessInfo("
	
	userId,
	en_ID,
	en_info,
	
	'Personal Info' as access_header,
  en_firstname , en_lastname, en_email , '' as 'Contact_IM', '' as 'Date_of_Birth',
'' as 'Sex', '' as 'Personal_Phone', '' as 'Cell_Phone', '' as 'Personal_Address', '' as 'Personal_Zip_Code', '' as 'Personal_City', '' as 'Personal_Country', '' as 'Personal_State', '' as 'Time_Zone'

	
	
	",
	
"cs_entities left join cs_companydetails on en_type='merchant' and en_type_ID = userId",
	"en_ID = '$en_ID'",
	array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Valid'=>'req'),$access);
	
	if($access==-1) dieLog("Invalid Company".print_r($curUserInfo,true),"Invalid Company en_ID = '$en_ID' ");
	
$res = check_merchant_conflict($_POST,$en_ID);
	if(!$res['status'])
	{
		$access['HeaderMessage'] .= $res['msg']."<BR>";
		$access['Data']['en_email']['Highlight'] = true;
		$_POST['en_email'] = $access['Data']['en_email']['Value'];
	}
	$access['Data']['Contact_IM']['DisplayName']='Contact Instant Messenger';
	$access['Data']['Contact_IM']['req']=false;
	$access['Data']['Date_of_Birth']['DisplayName']='Date of Birth';
	$access['Data']['Sex']['DisplayName']='Sex';
	$access['Data']['en_email']['DisplayName']='Personal Email';
	$access['Data']['en_lastname']['DisplayName']='Last Name';
	$access['Data']['en_firstname']['DisplayName']='First Name';
	$access['Data']['userId']['Input']="hidden";
	$access['Data']['userId']['disable']=1;
	$access['Data']['en_ID']['Input']="hidden";
	$access['Data']['en_ID']['disable']=1;
	$access['Data']['Time_Zone']['Input_Custom']=$etel_timezone;
	$access['Data']['Time_Zone']['Input']='selectcustomarray';
	$access['Data']['Time_Zone']['DisplayName']='Your Time Zone';
	$access['Data']['Time_Zone']['Style']='width:205px;';
	$access['Data']['Personal_Country']['Input']='selectcustom';
	$access['Data']['Personal_Country']['Style']='width:205px;';
	$access['Data']['Personal_Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
	$access['Data']['Personal_State']['DisplayName']='State or Province';
	$access['Data']['Personal_State']['req']=false;
	$access['Data']['Personal_State']['Input']='selectcustom';
	$access['Data']['Personal_State']['Style']='width:205px;';
	$access['Data']['Personal_State']['Input_Custom']="Select st_abbrev,st_full From cs_states";

	
	$access['SubmitValue'] = 'Update Information';
	if($skipIfComplete)	
	$access['SubmitValue'] = 'Continue to Step 2';
	$access['SubmitName'] = 'submit_step1';

	// Submit
		
	$showvalidation = false;
	if($_POST[$access['SubmitName']])
	{
		$result = processAccessForm(&$access);
		$showvalidation = true;
	}
	$access['Columns']=1;	
	
	
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
		draw_step_buttons(1,$skipIfComplete);
		$access['HeaderMessage'].="Please Complete all required fields to continue.";
		beginTable();
		writeAccessForm(&$access);
		endTable("Step #1 - Personal Information","");
		
		include("includes/footer.php");
		die();
	}
}

	
	
function step_2($en_ID,$skipIfComplete=true)
{
	// Step 2
	
	$access['SerializedData']['Source'] = 'en_info';
	$access['SerializedData']['Data'] = array(
		'Company_Url'=> array('General_Info','Company_Url'),
		'Address'=> array('General_Info','Address'),
		'Zip_Code'=> array('General_Info','Zip_Code'),
		'City'=> array('General_Info','City'),
		'State'=> array('General_Info','State'),
		'Country'=> array('General_Info','Country'),
		'Contact_Phone'=> array('General_Info','Contact_Phone'),
		'Contact_Fax'=> array('General_Info','Contact_Fax'),
		'Hear_About_Us'=> array('General_Info','Hear_About_Us'),
		'Company_Legal_Name'=> array('General_Info','Company_Legal_Name'),
		'Company_Type'=> array('General_Info','Company_Type'),
		'Incorporated_Country'=> array('General_Info','Incorporated_Country'),
		'Incorporated_Number'=> array('General_Info','Incorporated_Number'),
	);

	$access = getAccessInfo("
	
	userId,
	en_ID,
	en_info,
	
	'Company Info' as access_header,
	en_company, en_ref, en_username, '' as 'Company_Url' , '' as 'Company_Type_Select', '' as 'Company_Type', '' as 'Contact_Phone', '' as 'Contact_Fax', '' as 'Address','' as 'Zip_Code', '' as 'City', '' as 'State', '' as 'Country',
	'' as 'Hear_About_Us_Select', '' as 'Hear_About_Us', transaction_type as 'Transaction_Type', '' as 'Company_Legal_Name', '' as 'Incorporated_Country', '' as 'Incorporated_Number'
	
	
	",
	
"cs_entities left join cs_companydetails on en_type='merchant' and en_type_ID = userId",
	"en_ID = '$en_ID'",
	array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Valid'=>'req'),$access);
	
	if($access==-1) dieLog("Invalid Company","Invalid Company");

	$access['Data']['en_ref']['disable']=true;
	$access['Data']['en_ref']['DisplayName']='Company Reference ID';
	$access['Data']['en_username']['DisplayName']='Username';
	$access['Data']['Company_Url']['DisplayName']='Company URL';
	$access['Data']['Company_Url']['Valid']='url';
	$access['Data']['en_company']['DisplayName']='Company Name';
	//$access['Data']['password']['Input']='password';
	//$access['Data']['password']['Valid']='confirm|cpassword';
	//$access['Data']['password']['disable']=true;
	//$access['Data']['cpassword']['Input']='password';
	//$access['Data']['cpassword']['DisplayName']='Confirm Password';
	//$access['Data']['cpassword']['ExcludeQuery']=true;
	global $etel_timezone;
	$access['Data']['Country']['Input']='selectcustom';
	$access['Data']['Country']['Style']='width:205px;';
	$access['Data']['Country']['Input_Custom']="Select co_ISO,co_full From cs_country";
	$access['Data']['State']['Input']='selectcustom';
	$access['Data']['State']['Style']='width:205px;';
	$access['Data']['State']['Input_Custom']="Select st_abbrev,st_full From cs_states";
	$access['Data']['en_username']['disable']=true;
	$access['Data']['userId']['Input']="hidden";
	$access['Data']['userId']['disable']=1;
	$access['Data']['en_ID']['Input']="hidden";
	$access['Data']['en_ID']['disable']=1;
	$access['Data']['Hear_About_Us_Select']['DisplayName']="How did you hear about us?";
	$access['Data']['Hear_About_Us_Select']['ExcludeQuery']=true;
	$access['Data']['Hear_About_Us']['DisplayName']="Other";

	global $etel_hear_about_us;
	$access['Data']['Hear_About_Us_Select']['Input']='selectcustomarray';
	$access['Data']['Hear_About_Us_Select']['Input_Custom']=$etel_hear_about_us;
	$access['Data']['Hear_About_Us_Select']['InputAdditional']='onchange="$(\'Hear_About_Us\').value=this.value"';
	
	
	$access['Data']['Incorporated_Country']['Valid']='';
	$access['Data']['Incorporated_Number']['Valid']='';
	$access['Data']['Company_Legal_Name']['Valid']='';
	
	$comp_type = array();
	$comp_type['other'] = "Other";
	$comp_type['part'] = "Limited Partnership";
	$comp_type['ltd'] = "Limited Liability Company";
	$comp_type['corp'] = "Corporation";
	$comp_type['sole'] = "Sole Proprietor";
	$access['Data']['Company_Type_Select']['Valid']='';
	$access['Data']['Company_Type_Select']['Input_Custom']=$comp_type;
	$access['Data']['Company_Type_Select']['Input']='selectcustomarray';
	$access['Data']['Company_Type_Select']['InputAdditional']='onchange="$(\'Company_Type\').value=this.value"';
	$access['Data']['Company_Type_Select']['ExcludeQuery']=true;
	$access['Data']['Company_Type']['DisplayName']='Other';
	
	if($access['Data']['en_company']['Value'])
		$access['Data']['en_company']['disable']=true;
	else
		$access['Data']['en_company']['Valid']='req';
		
	$access['SubmitValue'] = 'Update Information';
	if($skipIfComplete)	
		$access['SubmitValue'] = 'Continue to Step 3';
	$access['SubmitName'] = 'submit_step2';

	// Submit
		
	$showvalidation = false;
	if($_POST[$access['SubmitName']])
	{
		$result = processAccessForm(&$access);
		$showvalidation = true;
	}
	$access['Columns']=1;	
	
	$access['Data']['Hear_About_Us_Select']['Value']=$access['Data']['Hear_About_Us']['Value'];
	
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

	
	
function step_3($en_ID,$skipIfComplete=true)
{
	// Step 3
			
	$access['SerializedData']['Source'] = 'en_info';
	$access['SerializedData']['Data'] = array(
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
		'Chargeback_%'=> array('Processing_Info','Chargeback_%'),
		'Average_Ticket_Price'=> array('Processing_Info','Average_Ticket_Price'),
		'Previous_Processor_Trans_Fee'=> array('Processing_Info','Previous_Processor_Trans_Fee'),
		'Previous_Processor_Disc_Fee'=> array('Processing_Info','Previous_Processor_Disc_Fee'),
		'Previous_Processing'=> array('Processing_Info','Previous_Processing'),
		'Previous_Processor_Reason'=> array('Processing_Info','Previous_Processor_Reason'),
		'Recur_Billing'=> array('Processing_Info','Recur_Billing'),
		'Currently_Processing'=> array('Processing_Info','Currently_Processing'),
	);
	
	$access = getAccessInfo("
	
	userId,
	en_ID,
	en_info,
	
	'Processing Info' as access_header,
	'' as Service_List,'' as Anti_Fraud_System,'' as CS_Program,'' as Refund_Policy,
	'' as Volume_Last_month, '' as Volume_Prev_30Days, '' as Volume_Prev_60Days,
	'' as Volume_Forcast_1Month, '' as Volume_Forcast_2Month, '' as Volume_Forcast_3Month,  '' as Projected_Monthly_Sales,  '' as Average_Ticket_Price,  '' as 'Chargeback_%',
	'' as Previous_Processor_Trans_Fee, '' as Previous_Processor_Disc_Fee, '' as Previous_Processing, '' as Previous_Processor_Reason, '' as Recur_Billing, '' as Currently_Processing
	
	
	",
	
	"cs_entities left join cs_companydetails on en_type='merchant' and en_type_ID = userId",
	"en_ID = '$en_ID'",
	array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true,'Valid'=>'req'),$access);
	
	if($access==-1) dieLog("Invalid Company","Invalid Company");
	
	$access['Data']['userId']['Input']="hidden";
	$access['Data']['userId']['disable']=1;
	$access['Data']['en_ID']['Input']="hidden";
	$access['Data']['en_ID']['disable']=1;
	
	$access['Data']['Service_List']['Input']='textarea';
	$access['Data']['Anti_Fraud_System']['Input']='textarea';
	$access['Data']['CS_Program']['Input']='textarea';
	$access['Data']['Refund_Policy']['Input']='textarea';
	
	$access['Data']['Previous_Processor_Trans_Fee']['DisplayName']='Previous Processor Transaction Fee';
	$access['Data']['Previous_Processor_Disc_Fee']['DisplayName']='Previous Processor Discount Rate';
	
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
	$access['Data']['Projected_Monthly_Sales']['Input']="selectvolume";
	$access['Data']['Projected_Monthly_Sales']['DisplayName']="Projected Monthly Sales Volume";
	$access['Data']['Previous_Processor_Trans_Fee']['DisplayName']="Previous Processing";
	$access['Data']['Previous_Processing']['DisplayName']="Previous Processor";
	$access['Data']['Previous_Processor_Reason']['DisplayName']="Reason for Leaving Previous Processor";
	$access['Data']['Previous_Processor_Reason']['Valid']='';
	$access['Data']['Recur_Billing']['DisplayName']="Recuring Billing";
	$access['Data']['Currently_Processing']['DisplayName']="Currently Processing";
	$access['Data']['Average_Ticket_Price']['DisplayName']="Average Ticket Price";
	$access['Data']['Chargeback_%']['DisplayName']="Chargeback %";
	$access['Data']['Service_List']['DisplayName']="Please List Your Products and Services";
	$access['Data']['Anti_Fraud_System']['DisplayName']="Describe your Current Anti Fraud System";
	$access['Data']['CS_Program']['DisplayName']="Describe your Customer Service Program";
	$access['Data']['Refund_Policy']['DisplayName']="Describe your Refund Policy";
	
	$access['SubmitValue'] = 'Update Information';
	if($skipIfComplete)	
	$access['SubmitValue'] = 'Complete Merchant Application';
	$access['SubmitName'] = 'submit_step3';

	// Submit
		
	$showvalidation = false;
	if($_POST[$access['SubmitName']])
	{
		$result = processAccessForm(&$access);
		$showvalidation = true;
	}
	$access['Columns']=1;	
	
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
		draw_step_buttons(3,$skipIfComplete);
		$access['HeaderMessage']="Please Complete all required fields to continue.";
		beginTable();
		writeAccessForm(&$access);
		endTable("Step #3 - Processing Information","");
		include("includes/footer.php");
		die();
	}
}




?>