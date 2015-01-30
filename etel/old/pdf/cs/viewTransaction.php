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
$pageConfig['Title'] = 'Transaction Information';
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
transactionId,

'Customer Information' as access_header,
	CONCAT(surname,', ',name) as Full_Name,
	cs_transactiondetails.country,
	cs_transactiondetails.email,
	
'Billing Information' as access_header,
	amount,
	CONCAT(	
		if(status!='D',
			if(status='P','Pending', 'Approved'),
			'Declined'
		)		
	) as status,
	if(td_is_a_rebill=1,' Rebilled Transaction',' New Order') as Type_Of_Purchase,
	cardtype,
	CCnumber,
	bankaccountnumber,
	bankroutingcode,
	bk_descriptor_visa as Billing_Descriptor,
	if(cancelstatus='Y',CONCAT('Refunded - ',cancel_refer_num),'') as Refunded,
	if(td_is_chargeback='1','Charged Back','') as Chargeback,
	
'Purchase Information' as access_header,
	reference_number as Reference_ID,
	Date_Format(transactionDate,'%W %b %D %Y %H:%i:%s') as Transaction_Date,
	productdescription as Product_Description,
	cs.cs_name as Website,
	cs.cs_member_url as WebsiteURL,
	
'Subscription Information' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	Date_Format(ss_account_expire_date,'%W %b %D %Y %H:%i:%s') as Account_Expiration,
	ss_cancel_id,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	Date_Format(ss_last_rebill,'%W %b %D %Y %H:%i:%s') as Last_Rebill_Date,
	
'Tracking Information' as access_header,
	cd_enable_tracking,
	td_enable_tracking,
	td_tracking_id,
	td_tracking_link,
	td_tracking_order_id,
	td_tracking_company,
	td_tracking_ship_date,
	td_tracking_ship_est,
	td_tracking_info,
	
'Refund Request' as access_header,
	'' as refund_select,
	'' as refund_reason,
	'' as refund_request,
	note_id,
	Date_Format(call_date_time,'%W %b %D %Y %H:%i:%s') as Refund_Requested_On,
	customer_notes
	
",

"cs_transactiondetails
left join cs_subscription on td_ss_ID = ss_ID
left join cs_bank as bk on cs_transactiondetails.bank_id = bk.bank_id
left join cs_rebillingdetails as rd on rd_subaccount = td_rebillingID
left join cs_company_sites as cs on cs_ID = td_site_ID
LEFT JOIN `cs_callnotes` as cn ON `transactionId` = cn.`transaction_id` 
left join cs_companydetails as cd on cd.userId = cs_transactiondetails.userId
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

$transaction = new transaction_class(false);
$transaction->pull_transaction($access['Data']['transactionId']['Value']);

$style_refund_select = '';
$style_refund_reason = '';
if($_POST['refund_request'] && !$access['Data']['Refunded']['Value'] && !$access['Data']['note_id']['Value'])
{
	$notes = quote_smart($_POST['refund_select']." - ".$_POST['refund_reason']);
	
	$msg = "<b>Please Enter a Detailed Reason for the Refund Request.</b>";
	if(strlen($_POST['refund_reason'])>10 && $_POST['refund_select'])
	{
		$refInfo = $transaction->process_refund_request(array("actor"=>'.NET','notes'=>$notes));
		$error_msg = $refInfo['status'];
		$msg = "Transaction Reference " . $transaction->row['transactionTable']['reference_number'] . " $error_msg.";
		$access['Data']['note_id']['Value']=1;
		unset($access['Data']['Refund_Requested_On']);
		$access['Data']['customer_notes']['Value'] = $notes;
	}
	else
	{
		if(!$_POST['refund_select']) $style_refund_select = " style='color:#FF0000;'";
		if(!(strlen($_POST['refund_reason'])>10)) $style_refund_reason = " style='color:#FF0000;'";
	}
}


$access['Data']['customer_notes']['Size']='30';

if(!$access['Data']['Refunded']['Value'])
{
	$access['Data']['refund_select']['DisplayName']='Please select a reason';
	$access['Data']['refund_select']['AddHtml']="<span $style_refund_select class='small'><BR>Please select a reason.</span>";
	$access['Data']['refund_select']['Value']=
	"<select name='refund_select'>
		<option value=''>- Select -</option>
		<option value='Can not get in touch with Merchant (.Net Refund Request)'>Can not get in touch with Merchant</option>
		<option value='Can not access Membership (.Net Refund Request)'>Can not access Membership</option>
		<option value='Fraudulent (.Net Refund Request)'>Fraudulent </option>
		<option value='Spouse (.Net Refund Request)'>Spouse</option>
		<option value='Did not receive Product/Package (.Net Refund Request)'>Did not receive Product/Package</option>
	</select>";
	$access['Data']['refund_reason']['Input']='textarea';
	$access['Data']['refund_reason']['Size']='30';
	$access['Data']['refund_reason']['disable']=false;
	$access['Data']['refund_reason']['DisplayName']='Detailed Reason';	
	$access['Data']['refund_reason']['AddHtml']="<span $style_refund_reason class='small'><BR>Please provide a detailed explaination.</span>";

	$access['Data']['refund_request']['Value']="<input type='submit' name='refund_request' value='Request Refund'>";
	$access['Data']['refund_request']['AddHtml']="<span class='small'><BR>Click this button to submit request.</span>";
}
else
{
	unset($access['Data']['refund_select']);
	unset($access['Data']['refund_reason']);
	$access['Data']['refund_request']['Value']="Transaction Refunded";
}
if(!$access['Data']['note_id']['Value'])
{
	unset($access['Data']['Refund_Requested_On']);
	unset($access['Data']['customer_notes']);
}
else
{
	unset($access['Data']['refund_select']);
	unset($access['Data']['refund_reason']);
	$access['Data']['refund_request']['Value']="Refund Requested";
}
if(!$access['Data']['Website']['Value'])
	unset($access['Data']['Website']);
else
	$access['Data']['Website']['Value']="<a target='_blank' href='".$access['Data']['WebsiteURL']['Value']."'>".$access['Data']['Website']['Value']."</a><span class='small'><BR>Click here to access your membership.</span>";
unset($access['Data']['note_id']);
unset($access['Data']['transactionId']);
unset($access['Data']['WebsiteURL']);
if(!$access['Data']['Product_Description']['Value']) unset($access['Data']['Product_Description']);

$access['Data']['cardtype']['DisplayName']='Billing Type';
$access['Data']['amount']['Value']='$'.formatMoney($access['Data']['amount']['Value']);
$access['Data']['amount']['DisplayName']='Amount Charged';

if(!$access['Data']['Chargeback']['Value']) unset($access['Data']['Chargeback']);
if(!$access['Data']['Refunded']['Value']) unset($access['Data']['Refunded']);

$ss_subscription_ID = $access['Data']['Subscription_ID']['Value'];
$access['Data']['Subscription_ID']['Value'] = "<a href='viewSubscription.php?$link_info'>$ss_subscription_ID</a><span class='small'><BR>Click this link for subscription management.</span>";
$access['Data']['Reference_ID']['Value'] .= "<span class='small'><BR>Your Transaction Reference ID.</span>";


$access['Data']['ss_cust_username']['DisplayName']='UserName';
$access['Data']['ss_cust_password']['DisplayName']='Password';
$access['Data']['ss_account_status']['DisplayName']='Account Status';

$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);


$access['Data']['CCnumber']['DisplayName']='Credit Card';
$access['Data']['bankaccountnumber']['DisplayName']='Check Account';
$access['Data']['bankroutingcode']['DisplayName']='Check Routing';

$access['Data']['CCnumber']['Value']=$transaction->row['Custom']['CreditCardFormatted'];
$access['Data']['bankaccountnumber']['Value']=$transaction->row['Custom']['CheckAccountFormatted'];
$access['Data']['bankroutingcode']['Value']=$transaction->row['Custom']['CheckRoutingFormatted'];

if(!$access['Data']['CCnumber']['Value'] || $access['Data']['cardtype']['Value']=='check') unset($access['Data']['CCnumber']);
if(!$access['Data']['bankaccountnumber']['Value'] || $access['Data']['cardtype']['Value']!='check') unset($access['Data']['bankaccountnumber']);
if(!$access['Data']['bankroutingcode']['Value'] || $access['Data']['cardtype']['Value']!='check') unset($access['Data']['bankroutingcode']);

$access['Data']['td_tracking_id']['DisplayName']='Tracking ID';
$access['Data']['td_tracking_id']['Size']=30;
$access['Data']['td_tracking_link']['DisplayName']='Tracking HyperLink';
$access['Data']['td_tracking_link']['Size']=30;
$access['Data']['td_tracking_order_id']['DisplayName']='Tracking Order ID';
$access['Data']['td_tracking_order_id']['Size']=30;
$access['Data']['td_tracking_company']['DisplayName']='Shipping Company';
$access['Data']['td_tracking_company']['Size']=30;
$access['Data']['td_tracking_ship_date']['DisplayName']='Date Shipped';
$access['Data']['td_tracking_ship_date']['Size']=30;
$access['Data']['td_tracking_ship_est']['DisplayName']='Estimated Arrival';
$access['Data']['td_tracking_ship_est']['Size']=30;
$access['Data']['td_tracking_info']['DisplayName']='Tracking Information (For Customers)';
$access['Data']['td_tracking_info']['Size']=23;
$access['Data']['td_tracking_info']['Rows']=5;



	
if(!$ss_subscription_ID) 
{
	$access['Data']['Subscription_ID']['DisplayName'] = 'Subscription';
	$access['Data']['Subscription_ID']['Value'] = 'No Subscription';
	unset($access['Data']['Account_Started']);
	unset($access['Data']['Account_Expiration']);
	unset($access['Data']['ss_cust_username']);
	unset($access['Data']['ss_cust_password']);
	unset($access['Data']['ss_cancel_id']);
	unset($access['Data']['ss_account_status']);
	unset($access['Data']['Rebill_Status']);
	unset($access['Data']['Last_Rebill_Date']);
}

if($access['Data']['cd_enable_tracking']['Value'] != 'on' || $access['Data']['td_enable_tracking']['Value'] != 'on') 
{
	$access['Data']['td_tracking_id']['DisplayName'] = 'Shipping';
	$access['Data']['td_tracking_id']['Value'] = 'No Shipping Information';
	unset($access['Data']['td_tracking_link']);
	unset($access['Data']['td_tracking_order_id']);
	unset($access['Data']['td_tracking_company']);
	unset($access['Data']['td_tracking_ship_date']);
	unset($access['Data']['td_tracking_ship_est']);
	unset($access['Data']['td_tracking_info']);
	
}
else
{
	$access['Data']['td_tracking_id']['disable']=false;
	$access['Data']['td_tracking_link']['disable']=false;
	$access['Data']['td_tracking_order_id']['disable']=false;
	$access['Data']['td_tracking_company']['disable']=false;
	$access['Data']['td_tracking_ship_date']['disable']=false;
	$access['Data']['td_tracking_ship_est']['disable']=false;
	$access['Data']['td_tracking_info']['disable']=false;
}

unset($access['Data']['cd_enable_tracking']);
unset($access['Data']['td_enable_tracking']);

$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);
if(!$access['Data']['ss_billing_state']['Value']) unset($access['Data']['ss_billing_state']);
if(!$access['Data']['Last_Rebill_Date']['Value']) unset($access['Data']['Last_Rebill_Date']);

$access['Columns']=1;
$access['Submitable']=false;


$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Transaction Info","viewTransaction.php?$link_info");

?>
</body></html>