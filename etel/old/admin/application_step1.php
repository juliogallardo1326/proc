<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// editCompanyProfile1.php:	This admin page functions for editing the company details.

$pageConfig['Title'] = "Merchant Application :: Step #1";
$headerInclude="startHere";
require_once("includes/header.php");


//$access = getMerchantAccess();
$access = getAccessInfo("

userId,

'General Info' as access_header,
ReferenceNumber, companyname, url1 , 1 as Merchant_Websites, 1 as Merchant_Documents, transaction_type, username,  password,  email,   phonenumber,address,city,state,country,zipcode,
fax_number,how_about_us,company_type, legal_name, incorporated_country, incorporated_number,fax_dba,physical_address,
cellular,technical_contact_details,admin_contact_details,

'Personal Info' as access_header,
first_name,family_name,job_title,contact_email,contact_phone,customer_service_phone,stitle,sdateofbirth,
ssex,sAddress,sPostCode,sResidenceTelephone,sFax,

'Processing Info' as access_header,
preprocess, cd_previous_processor, cd_processing_reason, cd_previous_discount, cd_previous_transaction_fee, recurbilling, currprocessing,
goods_list, max_ticket_amt,min_ticket_amt,volume_last_month,volume_prev_30days,volume_prev_60days,totals,forecast_volume_1month,
forecast_volume_2month,forecast_volume_3month,current_anti_fraud_system,customer_service_program,refund_policy,

'Merchant Notes' as access_header,
cd_notes,

'Status/Contract Info' as access_header,

cd_completion, gateway_id,

activeuser,
0 as Email_Active_Notification,

cd_merchant_show_contract,
0 as Email_Contract_Notification,
cd_pay_status,
cd_ignore,
send_mail,
 cd_custom_contract, 
if(merchant_contract_agree='1','Yes','No') as Contract_Signed,
Date_Format(FROM_UNIXTIME(cd_contract_date),'%W %M %D %Y') as Date_Signed,
cd_contract_ip as Signed_IP,


'Merchant Settings' as access_header,
cd_allow_rand_pricing, cd_enable_price_points, cd_max_transaction,cd_max_volume,
cd_pay_bimonthly, cd_payperiod, cd_paydelay, cd_wirefee, cd_rollover, cd_appfee, 
cd_appfee_upfront, cs_monthly_charge, cd_next_pay_day, cd_orderpage_settings,
 cd_approve_timelimit, cd_fraudscore_limit

",

"cs_companydetails",
"userId = $company_id",
array('Size'=>30,'Rows'=>2));

if($access==-1) dieLog("Invalid Company","Invalid Company");

$data['email'] = $access['Data']['email']['Value'];
$data['companyname'] = $access['Data']['companyname']['Value'];
$data['full_name'] = $access['Data']['companyname']['Value'];
$data['username'] = $access['Data']['username']['Value'];
$data['password'] = $access['Data']['password']['Value'];
$data['Reference_ID'] = $access['Data']['ReferenceNumber']['Value'];
$data["gateway_select"] = $access['Data']['gateway_id']['Value'];

$access['Data']['activeuser']['Input']='checkbox';
$access['Data']['activeuser']['DisplayName']='Merchant Live';
$access['Data']['activeuser']['InputAdditional']="onclick='".
'if(this.checked) {addNotes("Merchant Turned Active."); $(Email_Active_Notification).checked=true;$(cd_completion).value = 10;}'.
' else {addNotes("Merchant Turned Inactive."); $(cd_completion).value = 9;}'."'";

$access['Data']['cd_pay_status']['DisplayName']='Company Payable';
$access['Data']['cd_pay_status']['InputAdditional']="onchange='".
'addNotes("Merchant Payable Status Changed to "+this.value+".");'."'";

$access['Data']['cd_ignore']['Input']='checkbox';
$access['Data']['cd_ignore']['DisplayName']='Ignore Merchant';
$access['Data']['cd_ignore']['InputAdditional']="onchange='".
'addNotes("Merchant Ignore Set to "+(this.value?1:0)+".");'."'";

$access['Data']['send_mail']['Input']='checkbox';
$access['Data']['send_mail']['DisplayName']='Allow Email';
$access['Data']['send_mail']['InputAdditional']="onchange='".
'if(this.checked) addNotes("Merchant ReSubscribed."); else addNotes("Merchant Unsubscribed.");'."'";

$access['Data']['Email_Active_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Active_Notification']['Input']='checkbox';

$access['Data']['cd_merchant_show_contract']['Input']='checkbox';
$access['Data']['cd_merchant_show_contract']['DisplayName']='Display Contract';
$access['Data']['cd_merchant_show_contract']['InputAdditional']="onclick='".
'if(this.checked) {addNotes("Merchant Contract Displayed."); $(Email_Contract_Notification).checked=true; }'.
' else {addNotes("Merchant Contract Disabled."); }'.
'if($(cd_completion).value<=3) $(cd_completion).value = 4;}'.
"'";

$access['Data']['Email_Contract_Notification']['ExcludeQuery']=true;
$access['Data']['Email_Contract_Notification']['Input']='checkbox';


$access['Data']['cd_custom_contract']['Input']='checkbox';
$access['Data']['cd_custom_contract']['DisplayName']='Custom Contract';
if($access['Data']['cd_custom_contract']['Value'] || $_POST['cd_custom_contract']) 
	$access['Data']['cd_custom_contract']['AddHtml']="<a target='_blank' href='email_edit.php?et_custom_id=".$company_id."&et_name=merchant_contract'>Edit Contract</a>";
$access['Data']['Date_Signed']['disable']=true;

$access['Data']['gateway_id']['DisplayName']="Gateway";
$access['Data']['gateway_id']['Input']="selectcustomarray";	
$access['Data']['gateway_id']['Input_Custom'] = $gw_options;
$access['Data']['gateway_id']['InputAdditional']="onchange='".
'addNotes("Merchant Gateway Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['cd_completion']['DisplayName']="Completion";
$access['Data']['cd_completion']['Input']="selectcustomarray";	
$access['Data']['cd_completion']['Input_Custom'] = $cd_completion_array;
$access['Data']['cd_completion']['InputAdditional']="onchange='".
'addNotes("Merchant Completion Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['Contract_Signed']['disable']=true;
$access['Data']['Signed_IP']['disable']=true;

if($access==-1) dieLog("Invalid Company","Invalid Company");
$access['Data']['legal_name']['Length'] = 50;


$access['Data']['cd_notes']['Rows']=28;
$access['Data']['cd_notes']['InputAdditional']="onmousedown='addNotes(\"\")'";
$access['Data']['cd_notes']['DisplayName']="Notes";

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
'addNotes("Merchant WireFee Changed to $"+this.value+".");'."'";

$access['Data']['cd_rollover']['DisplayName']="Rollover Amount";
$access['Data']['cd_appfee']['DisplayName']="Out of Processing Application Fee";
$access['Data']['cd_appfee']['InputAdditional']="onchange='".
'addNotes("Merchant Application Fee Changed to $"+this.value+".");'."'";

$access['Data']['cd_appfee_upfront']['DisplayName']="Up Front Application Fee";
$access['Data']['cs_monthly_charge']['DisplayName']="Monthly Fee";
$access['Data']['cs_monthly_charge']['InputAdditional']="onchange='".
'addNotes("Merchant Monthly Fee Changed to $"+this.value+".");'."'";

$access['Data']['cd_next_pay_day']['DisplayName']="Next Payday";
$access['Data']['cd_next_pay_day']['InputAdditional']="onchange='".
'addNotes("Merchant Next Payday Changed to $"+this.value+".");'."'";

$access['Data']['cd_orderpage_settings']['DisplayName']="Order Page Method";
$access['Data']['cd_approve_timelimit']['DisplayName']="Approve Time Limit (In Hours)";
$access['Data']['cd_fraudscore_limit']['DisplayName']="Fraud Score Limit (0 to 10)";

$access['Data']['companyname']['DisplayName']="Company Name";
$access['Data']['url1']['DisplayName']="Company Website";
$access['Data']['url1']['LinkTo']=true;
$access['Data']['Merchant_Websites']['Value'] = "<a href='confirmWebsite.php?userIdList=$company_id&searchby=id&search=$company_id'>Merchant Websites</a>";
$access['Data']['Merchant_Websites']['ExcludeQuery']=true;
$access['Data']['Merchant_Websites']['disable']=true;
$access['Data']['Merchant_Websites']['DisplayName']='Websites';
$access['Data']['Merchant_Documents']['Value'] = "<a href='confirmUploads.php?userIdList=$company_id&searchby=id&search=$company_id'>Merchant Documents</a>";
$access['Data']['Merchant_Documents']['ExcludeQuery']=true;
$access['Data']['Merchant_Documents']['disable']=true;
$access['Data']['Merchant_Documents']['DisplayName']='Documents';

//$access['Data']['email']['EmailTo']=true;
$access['Data']['email']['AddHtml']="<a target='_blank' href='massmail1.php?searchby=id&search=$company_id'>Send Mail</a>";
$access['Data']['contact_email']['EmailTo']=true;

$access['Data']['how_about_us']['DisplayName']="How did you hear about us?";
$access['Data']['sAddress']['DisplayName']="Address";
$access['Data']['sPostCode']['DisplayName']="ZipCode";
$access['Data']['sResidenceTelephone']['DisplayName']="Home Phone";
$access['Data']['sFax']['DisplayName']="Home Fax";
$access['Data']['sPostCode']['DisplayName']="ZipCode";
$access['Data']['stitle']['DisplayName']="Title";
$access['Data']['sdateofbirth']['DisplayName']="Date of Birth";
$access['Data']['ssex']['DisplayName']="Sex";
$access['Data']['ReferenceNumber']['disable']=1;
$access['Data']['volume_last_month']['Input']="selectvolume";
$access['Data']['volume_prev_30days']['Input']="selectvolume";
$access['Data']['volume_prev_60days']['Input']="selectvolume";
$access['Data']['forecast_volume_1month']['Input']="selectvolume";
$access['Data']['forecast_volume_2month']['Input']="selectvolume";
$access['Data']['forecast_volume_3month']['Input']="selectvolume";
$access['Data']['transaction_type']['InputAdditional']="onchange='".
'addNotes("Merchant Type Changed to `"+this.options[this.selectedIndex].text+"`.");'."'";

$access['Data']['preprocess']['DisplayName']="Previous Processing";
$access['Data']['cd_previous_processor']['DisplayName']="Previous Processor";
$access['Data']['cd_processing_reason']['DisplayName']="Reason for Leaving Previous Processor";
$access['Data']['recurbilling']['DisplayName']="Recuring Billing";
$access['Data']['currprocessing']['DisplayName']="Currently Processing";

$access['Data']['userId']['Input']="hidden";
$access['Data']['userId']['disable']=1;


// Actions

if($_POST['submit_access'] == 'Submit') 
{
	if($_POST['Email_Contract_Notification'] && $access['Data']['send_mail']['Value']==1)
		send_email_template('contract_notification_email',$data);
	
	if($_POST['Email_Active_Notification'] && $access['Data']['send_mail']['Value']==1)
		send_email_template('active_notification_email',$data);
	

	if($_POST['cd_custom_contract'] && !$access['Data']['cd_custom_contract']['Value'])
	{
		$contract = genMerchantContract($company_id);
		$sql = "insert into cs_email_templates 
			set et_name='merchant_contract', 
			et_custom_id='".$company_id."', 
			et_title='".quote_smart($access['Data']['companyname']['Value'])." Contract', 
			et_access='admin', 
			et_to_title='".quote_smart($access['Data']['companyname']['Value'])."', 
			et_subject='Custom Merchant Contract for ".quote_smart($access['Data']['companyname']['Value'])."', 
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

// Submit

if($_POST['submit_access'] == 'Submit')
{
	$result = processAccessForm(&$access);
	if($result) $msg= "Company Updated Successfully ($result Field(s))";
	else $msg= "No Updates Detected";
}

$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Update Company - ".$access['Data']['companyname']['Value'],"");

include("includes/footer.php");
?>