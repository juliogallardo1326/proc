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
include("includes/sessioncheck.php");
$headerInclude="ledgers";
$allowBank=true;
include("includes/header.php");
require_once("../includes/transaction.class.php");
require_once("../includes/updateAccess.php");


$nolink=$_REQUEST['nolink'];
$addvar = '';
if($nolink) $addvar .= '&nolink='.$nolink;
if($_REQUEST['hide_header']) $addvar .= '&hide_header='.$_REQUEST['hide_header'];

$ss_subscription_ID = intval($_REQUEST['subscription_ID']);

if($_POST['cancel'])
{
	$trans = new transaction_class(false);
	$trans->pull_subscription($ss_subscription_ID,'ss_subscription_ID');
	$status = $trans->process_cancel_request(array("actor"=>'Administrator'));
	if($status['success'])
		$msg .= "Subscription ID " . $trans->row['subscriptionTable']['ss_subscription_ID'] . " cancelled.<BR>";
}

//$access = getMerchantAccess();
$access = getAccessInfo("
	ss_ID,
	
'Customer Info' as access_header,
	CONCAT(ss_billing_lastname,', ',ss_billing_mi,' ',ss_billing_firstname) as Full_Name,
	CONCAT(ss_billing_address,' ',ss_billing_address2) as Address,
	ss_billing_city as City,
	ss_billing_state as State,
	ss_billing_zipcode as Zipcode,
	co_full as Country,
	ss_billing_last_ip as IP_Address,
	
'Account Notes' as access_header,
	ss_account_notes,
	
'Billing Info' as access_header,
	companyname,
	ss_user_ID,
	rd_subName,
	ss_billing_type,
	ss_billing_card,
	ss_billing_check_account,   	  
	ss_billing_check_routing,
	  
	Date_Format(ss_rebill_next_date,'%W %b %D %Y %H:%i:%s') as Next_Rebill_Date,
	ss_rebill_amount,
	CONCAT(ss_rebill_status_text,' (',ss_rebill_status,') (frozen=',ss_rebill_frozen,')') as Rebill_Status,
	ss_rebill_status,
	ss_rebill_attempts as Rebill_Attempts,
	ss_rebill_count as Rebill_Count,
	ss_cancel_id,
	Date_Format(ss_last_rebill,'%W %b %D %Y %H:%i:%s') as Last_Rebill_Date,
	td.reference_number as Last_Transaction,
	
'Account Info' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	ss_productdescription,
	ss_cust_email,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	0 as update_manager,
	ss_account_expire_date,
	cs.cs_name as Website,
	cs.cs_member_url as WebsiteMembers,

'Transaction History' as access_header_spanned,
	1 as trans_log,

'Profit Info' as access_header_spanned,
	'' as Breakdown
	
",

"cs_subscription
left join cs_transactiondetails as td on transactionID = ss_transaction_id
left join cs_companydetails as cd on cd.userId = ss_user_ID 
left join cs_rebillingdetails as rd on rd_subaccount = ss_rebill_ID
left join cs_company_sites as cs on cs_ID = ss_site_ID
left join cs_country as co on ss_billing_country = co_ISO
",
"ss_subscription_ID = '$ss_subscription_ID'",
array('disable'=>true)
);

if($access==-1) dieLog("Invalid Subscription","Invalid Subscription");
$ss_ID = $access['Data']['ss_ID']['Value'];
$subscription = new transaction_class(false);
$subscription->pull_subscription($ss_ID);

$access['EnablePlusMinus'] = true;
$access['Data']['Profit Info']['PlusMinus'] = 'Closed';
//$access['Data']['Email']['Value'] = "<a href='mailto:".$access['Data']['Email']['Value']."'>".$access['Data']['Email']['Value']."</a>";

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
if(!$access['Data']['State']['Value']) unset($access['Data']['State']);

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
$access['Data']['ss_account_notes']['Rows']='24';
//$access['Data']['ss_account_notes']['ExcludeQuery']=true;
//$access['Data']['ss_account_notes']['ReadOnly']=true;

$access['Data']['ss_cust_email']['disable']=false;
$access['Data']['ss_cust_email']['DisplayName']='Email';
$access['Data']['ss_cust_email']['Size']='30';

$access['Data']['companyname']['DisplayName']='Company';
if(!$nolink)
	$access['Data']['companyname']['Value'] = "<a href='editCompanyProfileAccess.php?company_id=".$access['Data']['ss_user_ID']['Value']."'>".$access['Data']['companyname']['Value']."</a>";
$access['Data']['ss_user_ID']['Input']='hidden';

$access['Data']['rd_subName']['DisplayName']='Price Point';

$access['Data']['ss_billing_type']['DisplayName']='Billing Type';

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


if(!$nolink)
	$access['Data']['Website']['Value'] = "<a href='".$access['Data']['WebsiteMembers']['Value']."'>".$access['Data']['Website']['Value']."</a>";
unset($access['Data']['WebsiteMembers']);

$access['Data']['Last_Transaction']['Value'] = "<a href='viewTransaction.php?ref=".$access['Data']['Last_Transaction']['Value'].$addvar."'>".$access['Data']['Last_Transaction']['Value']."</a>";

if(!$nolink)
	$access['Data']['Last_Transaction']['Value'] .= " - <a href='report_Smart.php?frm_ss_subscription_id=$ss_subscription_ID'>All</a>";
	
if($access['Data']['ss_rebill_status']['Value']=='active') 
{
	$access['Data']['Rebill_Status']['Value'].="<BR>Cancel Subscription <input type='checkbox' name='cancel' value='1' > ";
}
unset($access['Data']['ss_rebill_status']);
$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);
if(!$access['Data']['ss_billing_state']['Value']) unset($access['Data']['ss_billing_state']);
if(!$access['Data']['Last_Rebill_Date']['Value']) unset($access['Data']['Last_Rebill_Date']);

$access['Data']['trans_log']['InputAdditional']='multiple="multiple" onchange=document.location.href="viewTransaction.php?ref="+this.value+"'.$addvar.'"';
$access['Data']['trans_log']['Input']='selectcustom';
$access['Data']['trans_log']['Style']='width:538px;';
$access['Data']['trans_log']['Rows']='10';
$access['Data']['trans_log']['DisplayName']='History';
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

$RF = new rates_fees();
//$r = $RF->update_transaction_profit($transactionId);
$profit = $RF->get_profit(array('EffectiveOnly'=>false,'where_trans'=>array('td_ss_ID'=>$ss_ID)),2);
$row=2;
$access['Data']['Breakdown']['RowDisplay']='Wide';
$access['Data']['Breakdown']['Input']='';
$access['Data']['Breakdown']['disable']=true;

$smarty->assign("Profit", $profit);
$access['Data']['Breakdown']['AddHtml'] = $smarty->fetch('cp_profitreport.tpl');

if($_POST['submit_access'] == 'Submit')
{
	$result = processAccessForm(&$access);
	if($result) $msg .= "Company Updated Successfully (".$result['cnt']." Field(s))<BR>";
	else $msg .= "No Updates Detected<BR>";
	
	if($_POST['update_manager'])
	{
		$subscription = new transaction_class(false);
		$subscription->pull_subscription($ss_subscription_ID,'ss.ss_subscription_ID');
		$res = $subscription->update_account_status();
		$submsg = '';
		if($res[0]['succeeded']) $submsg .= " postNotify:".htmlentities(substr(($res[0]['response']['body']),0,20));
		if($res[1]['succeeded']) $submsg .= " htaccess:".htmlentities(substr(($res[1]['response']['body']),0,20));
		if($res) $msg .= "Account Status Updated Successfully ( $submsg)<BR>";
		
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
