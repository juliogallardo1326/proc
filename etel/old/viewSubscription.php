<?
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

$pageConfig['Title'] = 'Subscription Information';
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
require_once('includes/header.php');
require_once("includes/transaction.class.php");
require_once("includes/updateAccess.php");

$userId =$companyInfo['userId'];

$ss_subscription_ID = intval($_REQUEST['subscription_ID']);

//$access = getMerchantAccess();
$access = getAccessInfo("
ss_ID,
'Customer Information' as access_header,
	CONCAT(ss_billing_lastname,', ',ss_billing_mi,' ',ss_billing_firstname) as Full_Name,
	CONCAT(ss_billing_address,' ',ss_billing_address2) as Address,
	ss_billing_city as City,
	ss_billing_state as State,
	ss_billing_zipcode as Zipcode,
	ss_billing_country as Country,
	ss_billing_last_ip as IP_Address,
	ss_cust_email as Email,
'Account Notes' as access_header,
	ss_account_notes,
	
'Billing Information' as access_header,
	rd_subName,
	ss_billing_type,
	ss_billing_card,
	ss_billing_check_account,   	  
	ss_billing_check_routing,
	Date_Format(ss_rebill_next_date,'%W %b %D %Y %H:%i:%s') as Next_Rebill_Date,
	ss_rebill_amount,
	CONCAT(ss_rebill_status_text,' (',ss_rebill_status,')') as Rebill_Status,
	ss_rebill_attempts as Rebill_Attempts,
	ss_rebill_count as Rebill_Count,
	ss_cancel_id,
	Date_Format(ss_last_rebill,'%W %b %D %Y %H:%i:%s') as Last_Rebill_Date,
	td.reference_number as Last_Transaction,
	
'Account Information' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	ss_productdescription,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	0 as update_manager,
	ss_account_expire_date,
	cs.cs_name as Website,
	cs.cs_ID as WebsiteID,

'Transaction History' as access_header_spanned,
	1 as trans_log

",

"cs_subscription
left join cs_transactiondetails as td on transactionID = ss_transaction_id
left join cs_rebillingdetails as rd on rd_subaccount = ss_rebill_ID
left join cs_company_sites as cs on cs_ID = ss_site_ID
",
"ss_subscription_ID = '$ss_subscription_ID' && ss_user_ID = '$userId'",
array('disable'=>true)
);

if($access==-1) dieLog("Invalid Subscription","Invalid Subscription");

$subscription = new transaction_class(false);
$ss_ID = $access['Data']['ss_ID']['Value'];
$subscription->pull_subscription($ss_ID);

$access['Data']['Email']['Value'] = "<a href='mailto:".$access['Data']['Email']['Value']."'>".$access['Data']['Email']['Value']."</a>";

unset($access['Data']['ss_ID']);

$access['Data']['ss_productdescription']['disable']=false;
$access['Data']['ss_productdescription']['DisplayName']='Product Description';
$access['Data']['ss_productdescription']['Size']='30';
$access['Data']['ss_cust_username']['disable']=false;
$access['Data']['ss_cust_username']['DisplayName']='UserName';
$access['Data']['ss_cust_password']['disable']=false;
$access['Data']['ss_cust_password']['DisplayName']='Password';
$access['Data']['ss_account_status']['disable']=false;
$access['Data']['ss_account_status']['DisplayName']='Account Status';

$access['Data']['update_manager']['DisplayName']='Update PW Management';
$access['Data']['update_manager']['Input']='checkbox';
$access['Data']['update_manager']['disable']=false;
$access['Data']['update_manager']['ExcludeQuery']=true;

$access['Data']['ss_account_expire_date']['disable']=false;
$access['Data']['ss_account_expire_date']['DisplayName']='Expire Date';
$access['Data']['ss_account_expire_date']['Size']='20';
$access['Data']['ss_account_notes']['disable']=false;
$access['Data']['ss_account_notes']['DisplayName']='Account Notes';
$access['Data']['ss_account_notes']['Size']='30';
$access['Data']['ss_account_notes']['Rows']='19';
$access['Data']['ss_account_notes']['ExcludeQuery']=true;
$access['Data']['ss_account_notes']['ReadOnly']=true;

$access['Data']['rd_subName']['DisplayName']='Price Point';
$access['Data']['rd_subName']['Value'] = "<a href='editPricePoint.php?rd_subName=".$access['Data']['rd_subName']['Value']."'>".$access['Data']['rd_subName']['Value']."</a>";

$access['Data']['ss_billing_type']['DisplayName']='Billing_Type';

$access['Data']['ss_billing_card']['DisplayName']='Credit Card';
$access['Data']['ss_billing_check_account']['DisplayName']='Check Account';
$access['Data']['ss_billing_check_routing']['DisplayName']='Check Routing';

$access['Data']['ss_billing_card']['Value']=$subscription->row['Custom']['CreditCardFormatted'];
$access['Data']['ss_billing_check_account']['Value']=$subscription->row['Custom']['CheckAccountFormatted'];
$access['Data']['ss_billing_check_routing']['Value']=$subscription->row['Custom']['CheckRoutingFormatted'];

if(!$access['Data']['ss_billing_card']['Value']) unset($access['Data']['ss_billing_card']);
if(!$access['Data']['ss_billing_check_account']['Value']) unset($access['Data']['ss_billing_check_account']);
if(!$access['Data']['ss_billing_check_routing']['Value']) unset($access['Data']['ss_billing_check_routing']);

$access['Data']['ss_rebill_amount']['Value']='$'.formatMoney($access['Data']['ss_rebill_amount']['Value']);
$access['Data']['ss_rebill_amount']['DisplayName']='Next Rebill Amount';

$access['Data']['Website']['Value'] = "<a href='addwebsiteuser.php?mode=edit&cs_ID=".$access['Data']['WebsiteID']['Value']."'>".$access['Data']['Website']['Value']."</a>";
unset($access['Data']['WebsiteID']);

$access['Data']['Last_Transaction']['Value'] = "<a href='viewTransaction.php?ref=".$access['Data']['Last_Transaction']['Value']."'>".$access['Data']['Last_Transaction']['Value']."</a> - <a href='report_Smart.php?frm_ss_subscription_id=$ss_subscription_ID'>All</a>";


$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);
if(!$access['Data']['ss_billing_state']['Value']) unset($access['Data']['ss_billing_state']);
if(!$access['Data']['Last_Rebill_Date']['Value']) unset($access['Data']['Last_Rebill_Date']);

$access['Data']['trans_log']['InputAdditional']='multiple="multiple" onchange=document.location.href="viewTransaction.php?ref="+this.value';
$access['Data']['trans_log']['Input']='selectcustom';
$access['Data']['trans_log']['Style']='width:538px;';
$access['Data']['trans_log']['DisplayName']='Transaction Log';
$access['Data']['trans_log']['Rows']='10';
$access['Data']['trans_log']['ExcludeQuery']=true;
$access['Data']['trans_log']['disable']=false;
$access['Data']['trans_log']['Input_Custom']="Select reference_number, 	  
CONCAT( 
	Date_Format(transactionDate,'%W, %b %D, %Y at %k:%i:%s'),
	' - (',
	reference_number,
	') ',
	
	if(td.status!='D',
		CONCAT(
			if(td.status='P','Pending','Approved'),
			CONCAT(' $',format(td.amount,2),' - ',td.cardtype)
		),
		'Declined'
	)
) as info
From cs_transactiondetails as td where td_ss_ID = '$ss_ID' order by transactionDate desc limit 40";
if(!$ss_ID) unset($access['Data']['trans_log']);

if($_POST['submit_access'] == 'Submit')
{
	$msg='';
	$result = processAccessForm(&$access);
	if($result) $msg .= "Subscription Successfully (".$result['cnt']." Field(s))<BR>";
	else $msg .= "No Updates Detected<BR>";
	
	if($_POST['update_manager'])
	{
		$subscription = new transaction_class(false);
		$subscription->pull_subscription($ss_subscription_ID,'ss.ss_subscription_ID');
		$submsg = '';
		
		$res = $subscription->update_account_status();
		if($res[0]['succeeded']) $submsg .= " postNotify:".htmlentities(substr(($res[0]['response']['body']),0,20));
		if($res[1]['succeeded']) $submsg .= " htaccess:".htmlentities(substr(($res[1]['response']['body']),0,20));
		if($res) $msg .= "Account Status Updated Successfully ( $submsg)<BR>";
			
		if(!$subscription->row['transactionTable']['transactionId'] && $subscription->row['subscriptionTable']['ss_account_status'] == 'active')
		{		
			$new_user = preg_replace('/[^a-zA-Z0-9_]/','',$subscription->row['subscriptionTable']['ss_cust_username']);
			$new_pass = preg_replace('/[^a-zA-Z0-9_]/','',$subscription->row['subscriptionTable']['ss_cust_password']);
			$new_group = preg_replace('/[^a-zA-Z0-9_]/','',$subscription->row['rebillingTable']['rd_description']);
			if(!$new_group) $new_group = preg_replace('/[^a-zA-Z0-9_]/','',$subscription->row['rebillingTable']['rd_subName']);
			
			$msg .= "  Adding User '$new_user' to htpasswd file...<BR>";
			$res = post_passwordmgmt_query($subscription->row['websiteTable']['cs_member_updateurl'],array('authpwd'=>$subscription->row['websiteTable']['cs_member_secret'],'reqtype'=>'add','username'=>$new_user,'password'=>$new_pass,'groupaccess'=>$new_group),-1);
			$result_val = intval($res['response']['body']);
			
			if($res && $etel_PW_response[$result_val])  $msg .= "   ".$result_val.": ".$etel_PW_response[$result_val].".<BR>";
			if($etel_PW_response[$result_val]=='201')  $summary['added']=intval($summary['added']) + 1;
		}
		
		
		
		$access['Data']['ss_account_status']['Value'] = $subscription->row['subscriptionTable']['ss_account_status'];
		$access['Data']['ss_account_notes']['Value'] = $subscription->row['subscriptionTable']['ss_account_notes'];
	}
}

$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Subscription Info - $ss_subscription_ID",'');

include("includes/footer.php");
?>
