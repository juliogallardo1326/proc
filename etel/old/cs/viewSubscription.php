<html>
<head>
<link href="/tmpl/etelegate_1/styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="/tmpl/etelegate_1/styles/style.css" type="text/css" rel="stylesheet">
<link href="/tmpl/etelegate_1/styles/text.css" type="text/css" rel="stylesheet">
<script language="javascript" src="/scripts/formvalid.js"></script>
<script language="javascript" src="/scripts/general.js"></script>

<script language="javascript" src="/scripts/prototype.js"></script>
</head>
<body>
<?
chdir("..");
$pageConfig['HideHeader'] = true;
$pageConfig['Title'] = 'Subscription Information';
require_once('includes/header.php');
require_once("includes/transaction.class.php");
require_once("includes/updateAccess.php");
$allowed = array('sub_phone','sub_email','email','CCnumber','bankaccountnumber','bankroutingcode','reference_number','phonenumber','subscription_ID');
if($_REQUEST['CCnumber'])
{
	if($_REQUEST['email']) $query['cs_transactiondetails.email'] = $_REQUEST['email'];
	if($_REQUEST['CCnumber']) $query['CCnumber'] = etelEnc(quote_smart($_REQUEST['CCnumber']));
}
else if($_REQUEST['bankaccountnumber'])
{
	if($_REQUEST['bankaccountnumber']) $query['bankaccountnumber'] = $_REQUEST['bankaccountnumber'];
	if($_REQUEST['bankroutingcode']) $query['bankroutingcode'] = $_REQUEST['bankroutingcode'];
}
else
{
	if($_REQUEST['email']) $query['cs_transactiondetails.email'] = $_REQUEST['email'];
	if($_REQUEST['reference_number']) $query['reference_number'] = $_REQUEST['reference_number'];
	if($_REQUEST['phonenumber']) $query['cs_transactiondetails.phonenumber'] = $_REQUEST['phonenumber'];
	if($_REQUEST['sub_email']) $query['ss_cust_email'] = $_REQUEST['sub_email'];
	if($_REQUEST['sub_phone']) $query['ss_cust_phone'] = $_REQUEST['sub_phone'];
	if($_REQUEST['subscription_ID']) $query['ss_subscription_ID'] = $_REQUEST['subscription_ID'];
} 

//$access = getMerchantAccess();
$link_info='';
foreach($query as $key=>$data)
	$sql_where.=($sql_where?"and ":"")." $key = '".quote_smart($data)."' ";
foreach($allowed as $key)
	if($_REQUEST[$key]) $link_info.=$key."=".$_REQUEST[$key]."&";
	
if(sizeof($query)<2)
{
	beginTable();
	echo "Insufficient Information. Please try again.";
	endTable('Insufficient Information',"lookup.php?$link_info",false,true);
	die();
}

$access = getAccessInfo("
ss_ID,

'Account Information' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	ss_productdescription,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	ss_account_expire_date,
	cs.cs_name as Website,
	cs.cs_member_url as WebsiteURL,
	
'Customer Information' as access_header,
	CONCAT(ss_billing_lastname,', ',ss_billing_mi,' ',ss_billing_firstname) as Full_Name,
	ss_cust_email as Email,
	
'Billing Information' as access_header,
	ss_billing_type,
	ss_billing_card,
	ss_billing_check_account,   	  
	ss_billing_check_routing,
	Date_Format(ss_rebill_next_date,'%W %b %D %Y %H:%i:%s') as Next_Rebill_Date,
	ss_rebill_amount,
	ss_rebill_status,
	Date_Format(ss_last_rebill,'%W %b %D %Y %H:%i:%s') as Last_Rebill_Date,
	reference_number as Last_Transaction,
	

'Account Notes' as access_header,
	ss_account_notes,

'Cancel Subscription' as access_header,
	ss_cancel_id,
	'' as cancel_subscription_select,
	'' as cancel_subscription_reason,
	'' as cancel_subscription
",

"cs_subscription
left join cs_transactiondetails on transactionID = ss_transaction_id
left join cs_rebillingdetails as rd on rd_subaccount = ss_rebill_ID
left join cs_company_sites as cs on cs_ID = ss_site_ID
",
"$sql_where",
array('disable'=>true,'HideIfEmpty'=>true)
);

if($access==-1) 
{
	beginTable();
	echo "Transaction Information was not found. Please try again.";
	endTable('Transaction Not Found',"lookup.php?$link_info",false,true);
	die();
}

$subscription = new transaction_class(false);
$subscription->pull_subscription($access['Data']['ss_ID']['Value']);

$access['Data']['ss_rebill_amount']['Value']='$'.formatMoney($access['Data']['ss_rebill_amount']['Value']);
$access['Data']['ss_rebill_amount']['DisplayName']='Next Rebill Amount';

if($_POST['cancel_rebill'] && $access['Data']['ss_rebill_status']['Value']=='active')
{
	if($_POST['ss_rebill_status_text'])
	{
		$notes = quote_smart($_POST['ss_rebill_status_text']." - ".$_POST['cancel_subscription_reason']);
		
		$status = $subscription->process_cancel_request(array("actor"=>'.NET','notes'=>$notes));
		if($status)
			$msg = "Subscription ID " . $subscription->row['subscriptionTable']['ss_subscription_ID'] . " cancelled (Ref ID: ".$status['ss_cancel_id'].")";
		else
			$msg = "Subscription ID " . $subscription->row['subscriptionTable']['ss_subscription_ID'] . " failed to cancel.";
			
		$access['Data']['ss_rebill_status']['Value']=$subscription->row['subscriptionTable']['ss_rebill_status'];
		$access['Data']['ss_account_notes']['Value']=$subscription->row['subscriptionTable']['ss_account_notes'];
		$access['Data']['ss_cancel_id']['Value']=$subscription->row['subscriptionTable']['ss_cancel_id'];
	}
	else
		$style_ss_rebill_status_text = " style='color:#FF0000;'";
}

$access['Data']['Email']['Value'] = "<a href='mailto:".$access['Data']['Email']['Value']."'>".$access['Data']['Email']['Value']."</a>";

if($access['Data']['ss_rebill_status']['Value']=='active')
{
	$access['Data']['cancel_subscription_select']['DisplayName']='Please select a reason';
	$access['Data']['cancel_subscription_select']['AddHtml']="<span $style_ss_rebill_status_text class='small'><BR>Please select a reason.</span>";
	$access['Data']['cancel_subscription_select']['Value']=
	"<select name='ss_rebill_status_text'>
		<option value=''>- Select -</option>
		<option value='Can not access Membership (.Net Cancel)'>Can not access Membership</option>
		<option value='Can not get in touch with Merchant (.Net Cancel)'>Can not get in touch with Merchant</option>
		<option value='Changed Mind (.Net Cancel)'>Changed Mind</option>
		<option value='Fraudulent (.Net Cancel)'>Fraudulent </option>
		<option value='Spouse (.Net Cancel)'>Spouse</option>
		<option value='Did not recieve Product/Package (.Net Cancel)'>Did not recieve Product/Package</option>
	</select>";
	$access['Data']['cancel_subscription_reason']['Input']='textarea';
	$access['Data']['cancel_subscription_reason']['Size']='30';
	$access['Data']['cancel_subscription_reason']['disable']=false;
	$access['Data']['cancel_subscription_reason']['DisplayName']='Detailed Reason';
	$access['Data']['cancel_subscription_reason']['AddHtml']="<span class='small'><BR>Please provide an explaination.</span>";
	$access['Data']['cancel_subscription']['Value']="<input type='submit' name='cancel_rebill' value='Cancel Subscription'>";
}
else
{
	unset($access['Data']['Next_Rebill_Date']);
	unset($access['Data']['ss_rebill_amount']);
	unset($access['Data']['cancel_subscription_select']);
	unset($access['Data']['cancel_subscription_reason']);
	$access['Data']['cancel_subscription']['Value']="Subscription Canceled";
}
	
$access['Data']['ss_productdescription']['DisplayName']='Product Description';
$access['Data']['ss_productdescription']['Size']='30';
if(!$access['Data']['ss_productdescription']['Value']) unset($access['Data']['ss_productdescription']);
$access['Data']['ss_cust_username']['DisplayName']='UserName';
$access['Data']['ss_cust_password']['DisplayName']='Password';
$access['Data']['ss_account_status']['DisplayName']='Account Status';
$access['Data']['ss_rebill_status']['DisplayName']='Rebill Status';

if(!$access['Data']['ss_cust_username']['Value'])	unset($access['Data']['ss_cust_username']);
if(!$access['Data']['ss_cust_password']['Value'])	unset($access['Data']['ss_cust_password']);
	
if(!$access['Data']['Website']['Value'])
	unset($access['Data']['Website']);
else
	$access['Data']['Website']['Value']="<a target='_blank' href='".$access['Data']['WebsiteURL']['Value']."'>".$access['Data']['Website']['Value']."</a><span class='small'><BR>Click here to access your membership.</span>";
unset($access['Data']['WebsiteURL']);

$access['Data']['ss_account_expire_date']['DisplayName']='Expire Date';
$access['Data']['ss_account_expire_date']['Size']='20';
$access['Data']['ss_account_notes']['DisplayName']='Account Notes';
$access['Data']['ss_account_notes']['Size']='30';
$access['Data']['ss_account_notes']['Rows']='12';
$access['Data']['ss_account_notes']['ExcludeQuery']=true;
$access['Data']['ss_account_notes']['ReadOnly']=true;

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

if($access['Data']['Last_Transaction']['Value'])
	$access['Data']['Last_Transaction']['Value'] = "<a href='viewTransaction.php?$link_info&reference_number=".$access['Data']['Last_Transaction']['Value']."'>".$access['Data']['Last_Transaction']['Value']."</a><span class='small'><BR>Click this link for Transaction Management.</span>";
else
	unset($access['Data']['Last_Transaction']);

$access['Data']['Subscription_ID']['Value'] .= "<span class='small'><BR>Your Subscription ID.</span>";



$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);
if(!$access['Data']['ss_billing_state']['Value']) unset($access['Data']['ss_billing_state']);
if(!$access['Data']['Last_Rebill_Date']['Value']) unset($access['Data']['Last_Rebill_Date']);

$access['Columns']=1;
$access['Submitable']=false;

unset($access['Data']['ss_ID']);
$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Subscription Info","viewSubscription.php?$link_info");


?>
</body></html>
