<?

$pageConfig['Title'] = 'Transaction Information';
include("includes/sessioncheck.php");
$headerInclude="ledgers";
$allowBank=true;
include("includes/header.php");
require_once("../includes/transaction.class.php");
require_once("../includes/updateAccess.php");
require_once("../includes/fraud.class.php");

$userId =$companyInfo['userId'];

$nolink=$_REQUEST['nolink'];
$addvar = '';
if($nolink) $addvar .= '&nolink='.$nolink;
if($_REQUEST['hide_header']) $addvar .= '&hide_header='.$_REQUEST['hide_header'];
if($_REQUEST['test']) $addvar .= '&test='.$_REQUEST['test'];

$ref=quote_smart($_REQUEST['ref']);

$id = $ref;
$field = 'reference_number';

$transTable = 'cs_transactiondetails';
if($_REQUEST['test']) $transTable = 'cs_test_transactiondetails';

if($_POST['refund'])
{
	$refund_reason = $_POST['refund_reason'];
	if(strlen($refund_reason)>3)
	{
		$trans = new transaction_class(false);
		$trans->pull_transaction($ref,$field);
		$status = $trans->process_refund_request(array("actor"=>'Administrator','notes'=>"$refund_reason"));
		$msg.=$status['status']."<BR>";
	}
}
if($_POST['charged_back'])
{
	$qry_details="UPDATE $transTable SET `td_is_chargeback` = '1', cancellationDate = NOW(), `td_bank_deducted`=0,`td_merchant_deducted`=0,`td_reseller_deducted`=0 WHERE $field = '$ref'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$msg.="$ref Set as Charged Back<BR>";
}

//$access = getMerchantAccess();
$access = getAccessInfo("

'Customer Info' as access_header,
	CONCAT(surname,', ',name) as Full_Name,
	$transTable.address as Address,
	$transTable.city,
	$transTable.state,
	$transTable.zipcode,
	co_full as Country,
	ipaddress as IP_Address,
	$transTable.email,
	$transTable.phonenumber as 'Phone',
	
'Billing Info' as access_header,
	rd_subName,
	amount,
	CONCAT(	
		if(status!='D',
			if(status='P','Pending', 'Approved'),
			'Declined'
		)		
	) as status,
	td_process_msg as 'Processor_Response',
	0 as 'Ban_Info',
	if(td_is_a_rebill=1,' Rebilled Transaction',' New Order') as Type_Of_Purchase,
	cardtype,
	CCnumber,
	bankaccountnumber,
	bankroutingcode,
	bank_name as 'Bank',
	td_bank_transaction_id as 'Bank_Trans_ID',
	if(cancelstatus='Y',CONCAT('Refunded - ',cancel_refer_num),'') as Refunded,
	customer_notes as Refund_Requested,
	if(td_is_chargeback='1','Charged Back','') as Chargeback,
	
'Purchase Info' as access_header,
	cd.userId as userId,
	companyname,
	transactionId,
	reference_number as Reference_ID,
	Date_Format(transactionDate,'%W %b %D %Y %H:%i:%s') as Transaction_Date,
	CONCAT(td_product_id,': ',productdescription) as Product_Description,
	cs.cs_name as Website,
	from_url as WebsiteURL,
	
'Subscription Info' as access_header,
	ss_subscription_ID as Subscription_ID,
	Date_Format(ss_account_start_date,'%W %b %D %Y %H:%i:%s') as Account_Started,
	Date_Format(ss_account_expire_date,'%W %b %D %Y %H:%i:%s') as Account_Expiration,
	ss_cancel_id,
	ss_cust_username,
	ss_cust_password,
	ss_account_status,
	CONCAT(ss_rebill_status_text,' (',ss_rebill_status,')') as Rebill_Status,
	Date_Format(ss_last_rebill,'%W %b %D %Y %H:%i:%s') as Last_Rebill_Date,
	
'Tracking Info' as access_header,
	cd_enable_tracking,
	td_enable_tracking,
	td_tracking_id,
	td_tracking_link,
	td_tracking_order_id,
	td_tracking_company,
	td_tracking_ship_date,
	td_tracking_ship_est,
	td_tracking_info,
	
'Profit Info' as access_header_spanned,
	'' as Breakdown,
	
'Misc Info' as access_header_spanned,
	td_non_unique,
	td_merchant_fields as Merchant_Data,
	td_process_query as Debug_Query,
	td_process_result as Debug_Result,
	td_process_duration as Query_Time
	
	
",

"$transTable
left join cs_subscription on td_ss_ID = ss_ID
left join cs_rebillingdetails as rd on rd_subaccount = td_rebillingID
left join cs_company_sites as cs on cs_ID = td_site_ID
left join cs_companydetails as cd on cd.userId = $transTable.userId
left join cs_country as co on $transTable.country = co_ISO
left join cs_callnotes as cn on transaction_id = transactionId and cn_type='refundrequest'
left join cs_profit_action on pa_trans_id = transactionId 
left join cs_bank on cs_bank.bank_id = $transTable.bank_id
",
"$field = '$id'",
array('disable'=>true)
);

if($access==-1) dieLog("Invalid Transaction","Invalid Transaction");
$transactionId = $access['Data']['transactionId']['Value'];

$fraud = new fraud_class();
$transaction = new transaction_class(false);
$transaction->pull_transaction($transactionId);
unset($access['Data']['transactionId']);

$access['EnablePlusMinus'] = true;
$access['Data']['Misc Info']['PlusMinus'] = 'Closed';
$access['Data']['Profit Info']['PlusMinus'] = 'Closed';

$banInfo = $fraud->check_banlist($transaction->row['transactionTable'],true);
if(!$banInfo['bansfound'])
	unset($access['Data']['Ban_Info']);
else
	$access['Data']['Ban_Info']['Value'] = nl2br($banInfo['banText']);
	
$access['Data']['companyname']['DisplayName']='Company';
if(!$nolink)
	$access['Data']['companyname']['Value'] = "<a href='editCompanyProfileAccess.php?company_id=".$access['Data']['userId']['Value']."'>".$access['Data']['companyname']['Value']."</a>";
$access['Data']['userId']['Input']='hidden';

if(!$nolink)
	$access['Data']['email']['Value'] = "<a href='mailto:".$access['Data']['email']['Value']."'>".$access['Data']['email']['Value']."</a>";

$access['Data']['cardtype']['DisplayName']='Billing Type';
$access['Data']['amount']['Value']='$'.formatMoney($access['Data']['amount']['Value']);
$access['Data']['amount']['DisplayName']='Amount Charged';
if(!$access['Data']['state']['Value']) unset($access['Data']['state']);

if(!$nolink)
	$access['Data']['Website']['Value'] = "<a href='".$access['Data']['WebsiteURL']['Value']."'>".$access['Data']['Website']['Value']."</a>";
unset($access['Data']['WebsiteURL']);

if(!$access['Data']['Chargeback']['Value'] && $access['Data']['status']['Value']=='Approved') 
{
		$access['Data']['Refunded']['DisplayName']= "ChargedBack?";
		$access['Data']['Refunded']['Value'] = "<input type='checkbox' name='charged_back' value='1' >";
}
if(!$access['Data']['Refunded']['Value'] && $access['Data']['status']['Value']=='Approved') 
{
	if(!$access['Data']['Refund_Requested']['Value'])
	{
		$access['Data']['Refunded']['DisplayName']= "Create a Refund Request";
		$access['Data']['Refunded']['Value'] = "<input type='checkbox' name='refund' value='1' onclick='$(\"refund_reason_div\").style.display=(this.checked?\"block\":\"none\")' ><div id='refund_reason_div' style='display:none' ><textarea name='refund_reason' ></textarea><br>Enter Reason and Hit Submit Below</div>";
	}
	else
	{
		$access['Data']['Refunded']['DisplayName']= "Refund Requested";
		$access['Data']['Refunded']['Value'] = $access['Data']['Refund_Requested']['Value'];
	}
}
if(!$access['Data']['Chargeback']['Value']) unset($access['Data']['Chargeback']);
if(!$access['Data']['Refunded']['Value']) unset($access['Data']['Refunded']);
unset($access['Data']['Refund_Requested']);
$ss_subscription_ID = $access['Data']['Subscription_ID']['Value'];

$access['Data']['Subscription_ID']['Value'] = "<a href='viewSubscription.php?subscription_ID=$ss_subscription_ID".$addvar."'>$ss_subscription_ID</a>";

$access['Data']['ss_cust_username']['DisplayName']='UserName';
$access['Data']['ss_cust_password']['DisplayName']='Password';
$access['Data']['ss_account_status']['DisplayName']='Account Status';

$access['Data']['ss_cancel_id']['DisplayName']='Cancelation ID';
if(!$access['Data']['ss_cancel_id']['Value']) unset($access['Data']['ss_cancel_id']);

$access['Data']['rd_subName']['DisplayName']='Price Point';

$access['Data']['CCnumber']['DisplayName']='Credit Card';
$access['Data']['bankaccountnumber']['DisplayName']='Check Account';
$access['Data']['bankroutingcode']['DisplayName']='Check Routing';

$access['Data']['CCnumber']['Value']=$transaction->row['Custom']['CreditCardFormatted'];
$access['Data']['bankaccountnumber']['Value']=$transaction->row['Custom']['CheckAccountFormatted'];
$access['Data']['bankroutingcode']['Value']=$transaction->row['Custom']['CheckRoutingFormatted'];

if(!$access['Data']['CCnumber']['Value']) unset($access['Data']['CCnumber']);
if(!$access['Data']['bankaccountnumber']['Value']) unset($access['Data']['bankaccountnumber']);
if(!$access['Data']['bankroutingcode']['Value']) unset($access['Data']['bankroutingcode']);

$td_non_unique = $access['Data']['td_non_unique']['Value'];
if(!$td_non_unique) $td_non_unique = -1;
$access['Data']['td_non_unique']['InputAdditional']='multiple="multiple" onchange=document.location.href="viewTransaction.php?ref="+this.value+"&nolink='.$addvar.'"';
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


$access['Data']['Merchant_Data']['disable']=0;
$access['Data']['Merchant_Data']['ExcludeQuery']=1;
$access['Data']['Merchant_Data']['Rows']=5;
$access['Data']['Merchant_Data']['Style']='width:478px;';
$access['Data']['Merchant_Data']['DisplayName']='Debug Info';
$access['Data']['Merchant_Data']['Value']=
"Merchant Data: 
".print_r(unserialize($access['Data']['Merchant_Data']['Value']),true)."
Query (".$access['Data']['Query_Time']['Value']."s):
".print_r($access['Data']['Debug_Query']['Value'],true)."
Result:
".print_r($access['Data']['Debug_Result']['Value'],true);

unset($access['Data']['Debug_Query']);
unset($access['Data']['Debug_Result']);
unset($access['Data']['Query_Time']);
	
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

$RF = new rates_fees();
$r = $RF->update_transaction_profit($transactionId);
$profit = $RF->get_profit(array('EffectiveOnly'=>false,'where_trans'=>array('pa_trans_ID'=>$transactionId)),2);
if($profit['status']===false) 
{
	//$r = $RF->update_transaction_profit($transactionId);
	//$profit = $RF->get_profit(array('EffectiveOnly'=>false,'where_trans'=>array('pa_trans_ID'=>$transactionId)),2);
}

$row=2;
$access['Data']['Breakdown']['RowDisplay']='Wide';
$access['Data']['Breakdown']['disable']=true;
$access['Data']['Breakdown']['DisplayName']='Breakdown';
$access['Data']['Breakdown']['AddHtml'].=

$smarty->assign("Profit", $profit);
$access['Data']['Breakdown']['AddHtml'] = $smarty->fetch('cp_profitreport.tpl');

if($_POST['submit_access'] == 'Submit')
{
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
