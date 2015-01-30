<?php
$headerInclude = 'subgatewayusers';
include 'includes/sessioncheck.php';
require_once("../includes/updateAccess.php");
include 'includes/header.php';

$userId = intval($_REQUEST['userIdList']?$_REQUEST['userIdList']:$_REQUEST['userId']);

//$access = getMerchantAccess();
$access = getAccessInfo("

userId,

'General Info' as access_header,
ReferenceNumber, transaction_type, username,  password, companyname, reseller_id, email,   phonenumber,address,city,state,country,zipcode,
fax_number,how_about_us,company_type, legal_name, incorporated_country, incorporated_number,fax_dba,physical_address,
cellular,technical_contact_details,admin_contact_details,

'Personal Info' as access_header,
first_name,family_name,job_title,contact_email,contact_phone,stitle,sdateofbirth,
ssex,sAddress,sPostCode,sResidenceTelephone,sFax,

'Processing Info' as access_header,
goods_list, max_ticket_amt,min_ticket_amt,volume_last_month,volume_prev_30days,volume_prev_60days,totals,forecast_volume_1month,
forecast_volume_2month,forecast_volume_3month,current_anti_fraud_system,customer_service_program,refund_policy

",

"cs_companydetails",
"userId = $userId and cd_subgateway_id = '".$resellerInfo['reseller_id']."'");

if($access==-1) dieLog("Invalid Company","Invalid Company");
$access['Data']['how_about_us']['DisplayName']="How did you hear about us?";
$access['Data']['sAddress']['DisplayName']="Address";
$access['Data']['sPostCode']['DisplayName']="ZipCode";
$access['Data']['sResidenceTelephone']['DisplayName']="Home Phone";
$access['Data']['sFax']['DisplayName']="Home Fax";
$access['Data']['sPostCode']['DisplayName']="ZipCode";
$access['Data']['stitle']['DisplayName']="Title";
$access['Data']['sdateofbirth']['DisplayName']="Date of Birth";
$access['Data']['ssex']['DisplayName']="Sex";
$access['Data']['username']['disable']=1;
$access['Data']['ReferenceNumber']['disable']=1;
$access['Data']['transaction_type']['disable']=1;
$access['Data']['volume_last_month']['Input']="selectvolume";
$access['Data']['volume_prev_30days']['Input']="selectvolume";
$access['Data']['volume_prev_60days']['Input']="selectvolume";
$access['Data']['forecast_volume_1month']['Input']="selectvolume";
$access['Data']['forecast_volume_2month']['Input']="selectvolume";
$access['Data']['forecast_volume_3month']['Input']="selectvolume";

$access['Data']['userId']['Input']="hidden";
$access['Data']['userId']['disable']=1;

$access['Data']['reseller_id']['DisplayName']="Reseller";
$access['Data']['reseller_id']['Input']="selectcustom";
$access['Data']['reseller_id']['Input_Custom']="select reseller_id, reseller_companyname from cs_resellerdetails where rd_subgateway_id='".$resellerInfo['reseller_id']."' order by reseller_companyname";

if($_POST['submit_access'] == 'Submit')
{
	$result = processAccessForm(&$access);
	if($result) $msg= "Company Updated Successfully (".$result['cnt']." Field(s))";
	else $msg= "No Updates Detected";
}

$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Update Company - ".$access['Data']['companyname']['Value'],"");

include("includes/footer.php");
?>