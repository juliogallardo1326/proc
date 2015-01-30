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

$pageConfig['Title'] = "Merchant Application :: Step #1 - Company Information";
$headerInclude="startHere";
require_once("includes/header.php");
require_once("includes/updateAccess.php");

$userId = intval($companyInfo['userId']);
//$access = getMerchantAccess();
$access = getAccessInfo("

userId,

'General Info' as access_header,
ReferenceNumber, username, password, password as 'cpassword', companyname, url1 , email, phonenumber,address,city,state,country,zipcode,
fax_number,how_about_us,company_type, legal_name, incorporated_country, incorporated_number,fax_dba,physical_address,
cellular,technical_contact_details,admin_contact_details


",

"cs_companydetails",
"userId = '$userId'",
array('Size'=>30,'Rows'=>2,'HideIfEmpty'=>true));

if($access==-1) dieLog("Invalid Company","Invalid Company");

$access['Data']['ReferenceNumber']['disable']=true;
$access['Data']['url1']['DisplayName']='Company URL';
$access['Data']['email']['DisplayName']='Notification Email';
$access['Data']['companyname']['DisplayName']='Company Name';
$access['Data']['password']['Input']='password';
$access['Data']['password']['alt']='confirm|cpassword';
$access['Data']['cpassword']['Input']='password';
$access['Data']['cpassword']['DisplayName']='Confirm Password';
$access['Data']['cpassword']['ExcludeQuery']=true;
$access['Data']['country']['Input']='selectcustom';
$access['Data']['country']['Style']='width:205px;';
$access['Data']['country']['Input_Custom']="Select co_ISO,co_full From {$database["database_main"]}.cs_country";
$access['Data']['state']['Input']='selectcustom';
$access['Data']['state']['Style']='width:205px;';
$access['Data']['state']['Input_Custom']="Select st_abbrev,st_full From {$database["database_main"]}.cs_states";
$access['Data']['username']['disable']=true;
$access['Data']['userId']['Input']="hidden";
$access['Data']['userId']['disable']=1;
$access['Data']['how_about_us']['DisplayName']="How did you hear about us?";

$access['Data']['companyname']['alt']='req';

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
$access['Columns']=1;
$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Step #1 - Company Information","");

include("admin/includes/footer.php");
?>