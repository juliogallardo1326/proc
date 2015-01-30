<?php
$headerInclude = 'subgatewayusers';
include 'includes/sessioncheck.php';
require_once("../includes/updateAccess.php");
include 'includes/header.php';

$reseller_id = intval($_REQUEST['reselIdList']?$_REQUEST['reselIdList']:$_REQUEST['reseller_id']);

//$access = getMerchantAccess();
$access = getAccessInfo("
reseller_id,

'General Info' as access_header,
 rd_referenceNumber,reseller_username,  reseller_password, reseller_companyname, reseller_contactname, reseller_url, reseller_email,   reseller_phone,

'Personal Info' as access_header,
reseller_firstname,reseller_lastname,reseller_jobtitle,reseller_title,
reseller_sex,reseller_address,reseller_zipcode,reseller_res_phone,reseller_faxnumber,

'Processing Info' as access_header,
reseller_monthly_volume

",

"cs_resellerdetails",
"reseller_id = $reseller_id and rd_subgateway_id = '".$resellerInfo['reseller_id']."'");

if($access==-1) dieLog("Invalid Reseller","Invalid Reseller");

$access['Data']['reseller_username']['disable']=1;
$access['Data']['rd_referenceNumber']['disable']=1;

$access['Data']['reseller_monthly_volume']['Input']="selectvolume";

$access['Data']['reseller_id']['Input']="hidden";
$access['Data']['reseller_id']['disable']=1;

foreach($access['Data'] as $key=>$data)
	$access['Data'][$key]['DisplayName'] = str_replace('Reseller ','',$data['DisplayName']);

if($_POST['submit_access'] == 'Submit')
{
	$result = processAccessForm(&$access);
	if($result) $msg= "Reseller Updated Successfully ($result Field(s))";
	else $msg= "No Updates Detected";
}
$access['HeaderMessage']=$msg;


beginTable();
writeAccessForm(&$access);
endTable("Update Reseller - ".$access['Data']['reseller_companyname']['Value'],"");

include("includes/footer.php");
?>