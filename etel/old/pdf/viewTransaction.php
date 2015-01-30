<?

$pageConfig['Title'] = 'Transaction Information';
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
require_once('includes/header.php');
require_once("includes/transaction.class.php");
require_once("includes/updateAccess.php");

$userId =$companyInfo['userId'];

$transactionId=intval($_REQUEST['id']);
$ref=quote_smart($_REQUEST['ref']);
$transTable = 'cs_transactiondetails';
if($_REQUEST['test']) $transTable = 'cs_test_transactiondetails';
$id = $ref;
$field = 'reference_number';

//$access = getMerchantAccess();
$access = getAccessInfo("
transactionId,

'Customer Information' as access_header,
	CONCAT(surname,', ',name) as Full_Name,
	$transTable.address as Address,
	$transTable.city,
	$transTable.state,
	$transTable.zipcode,
	$transTable.country,
	ipaddress as IP_Address,
	$transTable.email,
	
'Billing Information' as access_header,
	rd_subName,
	amount,
	CONCAT(	
		if(status!='D',
			if(status='P','Pending', 'Approved'),
			'Declined'
		)		
	) as status,
	td_process_msg as 'Processor_Response',
	if(td_is_a_rebill=1,' Rebilled Transaction',' New Order') as Type_Of_Purchase,
	cardtype,
	CCnumber,
	bankaccountnumber,
	bankroutingcode,
	if(cancelstatus='Y',CONCAT('Refunded - ',cancel_refer_num),'') as Refunded,
	if(td_is_chargeback='1','Charged Back','') as Chargeback,
	
'Purchase Information' as access_header,
	reference_number as Reference_ID,
	Date_Format(transactionDate,'%W %b %D %Y %H:%i:%s') as Transaction_Date,
	CONCAT(td_product_id,': ',productdescription) as Product_Description,
	cs.cs_name as Website,
	cs.cs_ID as WebsiteID,
	
'Subscription Information' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	Date_Format(ss_account_expire_date,'%W %b %D %Y %H:%i:%s') as Account_Expiration,
	ss_cancel_id,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	CONCAT(ss_rebill_status_text,' (',ss_rebill_status,')') as Rebill_Status,
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
	
'Misc Information' as access_header_spanned,
	td_non_unique
	
",

"$transTable
left join cs_subscription on td_ss_ID = ss_ID
left join cs_rebillingdetails as rd on rd_subaccount = td_rebillingID
left join cs_company_sites as cs on cs_ID = td_site_ID
left join cs_companydetails as cd on cd.userId = $transTable.userId
",
"$field = '$id' && $transTable.userId = '$userId'",
array('disable'=>true)
);

if($access==-1) dieLog("Invalid Transaction ~ ($id)","Invalid Transaction ~ ($id)");
$transactionId = $access['Data']['transactionId']['Value'];

$transaction = new transaction_class(false);
$transaction->pull_transaction($transactionId);

unset($access['Data']['transactionId']);


$access['Data']['email']['Value'] = "<a href='mailto:".$access['Data']['email']['Value']."'>".$access['Data']['email']['Value']."</a>";

$access['Data']['cardtype']['DisplayName']='Billing Type';
$access['Data']['amount']['Value']='$'.formatMoney($access['Data']['amount']['Value']);
$access['Data']['amount']['DisplayName']='Amount Charged';

$access['Data']['Website']['Value'] = "<a href='addwebsiteuser.php?mode=edit&cs_ID=".$access['Data']['WebsiteID']['Value']."'>".$access['Data']['Website']['Value']."</a>";
unset($access['Data']['WebsiteID']);

if(!$access['Data']['Chargeback']['Value']) unset($access['Data']['Chargeback']);
if(!$access['Data']['Refunded']['Value']) unset($access['Data']['Refunded']);

$ss_subscription_ID = $access['Data']['Subscription_ID']['Value'];
$access['Data']['Subscription_ID']['Value'] = "<a href='viewSubscription.php?subscription_ID=$ss_subscription_ID'>$ss_subscription_ID</a>";

if($access['Data']['rd_subName']['Value'])
{
	$access['Data']['rd_subName']['DisplayName']='Price Point';
	$access['Data']['rd_subName']['Value'] = "<a href='editPricePoint.php?rd_subName=".$access['Data']['rd_subName']['Value']."'>".$access['Data']['rd_subName']['Value']."</a>";
}
else unset($access['Data']['rd_subName']);

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


$td_non_unique = $access['Data']['td_non_unique']['Value'];
if(!$td_non_unique) $td_non_unique = -1;
$access['Data']['td_non_unique']['InputAdditional']='multiple="multiple" onchange=document.location.href="viewTransaction.php?ref="+this.value';
$access['Data']['td_non_unique']['Input']='selectcustom';
$access['Data']['td_non_unique']['Style']='width:478px;';
$access['Data']['td_non_unique']['Rows']='10';
$access['Data']['td_non_unique']['DisplayName']='Similar Transactions';
$access['Data']['td_non_unique']['ExcludeQuery']=true;
$access['Data']['td_non_unique']['disable']=false;
$access['Data']['td_non_unique']['Input_Custom']="

(

Select reference_number, 	  
CONCAT( 
	Date_Format(transactionDate,'%W, %b %D, %Y at %k:%i:%s'),
	' - (',
	reference_number,
	') ',
	if(td.status!='D',
		CONCAT(
			if(td.status='P','Pending','Initial Approve'),
			CONCAT(' $',format(td.amount,2),' - ',td.cardtype)
		),
		'Initial Decline'
	)
) as info
From $transTable as td where (transactionId ='$td_non_unique' ) limit 1
)
UNION
 (
Select reference_number, 	  
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
From $transTable as td where (td_non_unique in('$transactionId','$td_non_unique') && transactionId != '$transactionId') order by transactionDate desc limit 40
)
";

	
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

if($access['Data']['cd_enable_tracking']['Value'] != 'on') 
{
	$access['Data']['td_tracking_id']['DisplayName'] = 'Shipping';
	$access['Data']['td_tracking_id']['Value'] = 'No Shipping Requirements';
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

if($_POST['submit_access'] == 'Submit')
{
	$msg='';
	$result = processAccessForm(&$access);
	if($result) $msg .= "Transaction Updated Successfully (".$result['cnt']." Field(s))<BR>";
	else $msg .= "No Updates Detected<BR>";

}

$access['HeaderMessage']=$msg;

beginTable();
writeAccessForm(&$access);
endTable("Transaction Info - ".$access['Data']['Reference_ID']['Value'],'');

include("includes/footer.php");
?>
