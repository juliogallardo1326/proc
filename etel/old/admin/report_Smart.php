<?
$pageConfig['Title'] = 'Transaction Lookup';
include("includes/sessioncheck.php");
//require_once("../includes/dbconnection.php");
//require_once('../includes/function.php');
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();
include 'includes/header.php';
include_once "../includes/completion.php";
require_once('../includes/subFunctions/smart_search.php');
require_once("../includes/transaction.class.php");
require_once("../includes/companySubView.php");
require_once("../includes/JSON_functions.php");

/**************
Define functions to process form
**************/
?>
<script language="javascript">

	function check_additional(obj)
	{
		name = obj.name;
		id = name.split('_')[2];
		if(obj.value=='smart_processRefund')
		{
			$('frm_refund_request_'+id).value = prompt("Please enter the reason for this Refund Request",$('frm_refund_request_'+id).value);
			if($('frm_refund_request_'+id).value)
			{
				$('frm_refund_request_'+id).style.visibility = 'visible';
				$('frm_refund_request_'+id).style.height = '50';
			}
		}
		else
		{
			$('frm_refund_request_'+id).style.value = '';
			$('frm_refund_request_'+id).style.visibility = 'hidden';
			$('frm_refund_request_'+id).style.height = '1';
		}
	}

</script>
<?



function smart_processTransactions($form_res,$action,&$results)
{	
	$status = array();
	if(isset($form_res['entries']))
		foreach($form_res['entries'] as $key => $values)
			if($values['value'] !="")
				$status[] = $values['value']($values,$action);
	return $status;
}

function smart_processVoid($values,$action)
{	
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$RF = new rates_fees();
	$r = $RF->void_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $transID . " Voided.","status"=>($r['status']?"Success":"Fail"));
}

function smart_processRefund($values,$action)
{
	$reason = $_POST['frm_refund_request_'.$values['append']];
	if(strlen($reason)>3)
	{
		$trans = new transaction_class(false);
		$trans->pull_transaction($values['append']);
		$status = $trans->process_refund_request(array("actor"=>'Administrator','notes'=>"$reason"));
		return array("action"=>"Created A Refund Request for Transaction Reference " . $trans->row['transactionTable']['reference_number'] . ".","status"=>($status['status']));
	}
	return array("action"=>"Failed to Create Refund Request (Invalid Reason).","status"=>('fail'));
}

function smart_setApproved($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET `status` = 'A',`td_bank_deducted`=0,`td_merchant_deducted`=0,`td_reseller_deducted`=0 WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	Process_Transaction($values['append'],'approve',false,'transactionId');
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " set Approved.","status"=>"success");
}


function smart_setDeclined($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET `status` = 'D',`td_bank_deducted`=0,`td_merchant_deducted`=0,`td_reseller_deducted`=0 WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	Process_Transaction($values['append'],'decline',false,'transactionId');
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " set Declined.","status"=>"success");
}


function smart_setChargeback($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET `td_is_chargeback` = '1', cancellationDate = NOW(), status='A',`td_bank_deducted`=0,`td_merchant_deducted`=0,`td_reseller_deducted`=0 WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	smart_cancelRebill($values,$action);
	Process_Transaction($values['append'],'chargeback',false,'transactionId');
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " issued chargeback.","status"=>"success");
}

function smart_removeChargeback($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET `td_is_chargeback` = '0',`td_bank_deducted`=1,`td_merchant_deducted`=1,`td_reseller_deducted`=1  WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " chargeback removed.","status"=>"success");
}


function smart_setRefund($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET  cancellationDate=COALESCE(cancellationDate,now()), `cancelstatus` = 'Y',status='A',`td_bank_deducted`=0,`td_merchant_deducted`=0,`td_reseller_deducted`=0 WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	smart_cancelRebill($values,$action);
	Process_Transaction($values['append'],'refund',false,'transactionId');
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " marked as Refunded.","status"=>"success");
}

function smart_removeRefund($values,$action)
{
	global $curUserInfo;
	if(!$curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD]) 
		return array("action"=>"Invalid Access.","status"=>"Fail");
	$transID = $values['append'];
	$qry_details="UPDATE cs_transactiondetails SET `cancelstatus` = 'N',`td_bank_deducted`=1,`td_merchant_deducted`=1,`td_reseller_deducted`=1  WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$qry_details="DELETE FROM cs_callnotes WHERE `cn_type` = 'refundrequest' AND `transaction_id` = '$transID'";
	$rst_details=sql_query_write($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$RF = new rates_fees();
	$r = $RF->update_transaction_profit($transID);
	return array("action"=>"Transaction ID " . $values['append'] . " refund removed.","status"=>"success");
}

function smart_cancelRebill($values,$action)
{
	$trans = new transaction_class(false);
	$trans->pull_transaction($values['append']);
	$status = $trans->process_cancel_request(array("actor"=>'Administrator'));
	return array("action"=>"Subscription ID " . $trans->row['subscriptionTable']['ss_subscription_ID'] . " cancelled.","status"=>($status?"success":"fail"));
}

function smart_restartRebill($values,$action)
{
	$trans = new transaction_class(false);
	$trans->pull_transaction($values['append']);
	$status = $trans->process_subscription_restart(array("actor"=>'Administrator'));
	return array("action"=>"Subscription ID " . $trans->row['subscriptionTable']['ss_subscription_ID'] . " restarted.","status"=>($status?"success":"fail"));
}

/****************
Define Search Fields and Action Fields
****************/
if(!$_REQUEST['frm_testmode']) $_REQUEST['frm_testmode']=0;
$transaction_table = ($_REQUEST['frm_testmode'] ? "cs_test_transactiondetails" : "cs_transactiondetails");
$test_mode = intval($_REQUEST['frm_testmode']);

$my_sql['tables'] = array("$transaction_table AS td");
$my_sql['joins'] = array(
		'cd' => array("table"=>"cs_companydetails AS cd",
				"on"=>
					array(
						array("field_a"=>"td.userId","field_b"=>"cd.userId","compare"=>"=")
					)
				),
		'cs' => array("table"=>"cs_company_sites AS cs",
				"on"=>
					array(
						array("field_a"=>"td.td_site_ID","field_b"=>"cs.cs_ID","compare"=>"=")
					)
				),
		'ss' => array("table"=>"cs_subscription AS ss",
				"on"=>
					array(
						array("field_a"=>"td.td_ss_ID","field_b"=>"ss.ss_ID","compare"=>"=")
					)
				),
		'bk' => array("table"=>"cs_bank AS bk",
				"on"=>
					array(
						array("field_a"=>"td.bank_id","field_b"=>"bk.bank_id","compare"=>"=")
					)
				)
		);

$my_sql['return']["00|Transaction ID"] = array("source" => "td.transactionid","column"=>"transactionid","hidden"=>1);
$my_sql['return']["00|Subscription ID"] = array("source" => "ss.ss_rebill_status","column"=>"ss_rebill_status","hidden"=>1);
$my_sql['return']["00|Company ID"] = array("source" => "td.userid","column"=>"userid","hidden"=>1);
$my_sql['return']["00|Amount"] = array("source" => "td.amount","column"=>"amount","hidden"=>1);
$my_sql['return']["00|td_is_chargeback"] = array("source"=>"td.td_is_chargeback","column"=>"td_is_chargeback","hidden"=>1);
$my_sql['return']["00|trans_status"] = array("source"=>"td.status as trans_status","column"=>"trans_status","hidden"=>1);
$my_sql['return']["00|CancelStatus"] = array("source" => "IF(cancelstatus='Y',1,0) AS is_refunded","column"=>"is_refunded","hidden"=>1);



$my_sql['return']["02|Transaction Date"] = array("source" => "DATE_FORMAT(td.transactionDate,'%m/%d/%Y %h:%i:%s %p') as transaction_date_formatted","column"=>"transaction_date_formatted");

$my_sql['return']["01|Reference Number"] = array("source" => "td.reference_number","column"=>"reference_number");
$my_sql['return']["01|Reference Number"]["link"]["destination"] = "viewTransaction.php";
$my_sql['return']["01|Reference Number"]["link"]["parameters"] = array(
		array("name"=>"ref","value"=>"reference_number","source"=>"result"),
		array("name"=>"test","value"=>"$test_mode"),
		);

$my_sql['return']["02|Company Name"] = array("source" => "cd.companyname","column"=>"companyname","crop"=>30);
$my_sql['return']["02|Company Name"]["link"]["destination"] = "editCompanyProfileAccess.php";
$my_sql['return']["02|Company Name"]["link"]["parameters"] = array(array("name"=>"company_id","value"=>"userid","source"=>"result"));

$my_sql['return']["03|Description"] = 
array("source" => "concat(
						if(td.td_username != '',CONCAT('U:',td.td_username,'<BR>P:',td.td_password,'<BR>'),''),
						if(td.from_url != '',CONCAT('URL:<a href=\"',td.from_url,'\">',td.from_url,'</a><BR>'),''),
						if(td.productdescription != '',CONCAT('Desc: ',td.productdescription,'<BR>'),'')
					) as description",
	"column"=>"description",
	"disp_clip"=>array('overflow'=>true)

	);

$my_sql['return']["04|Name"] = array("source" => "concat(td.surname,', ',td.name) AS full_name","column"=>"full_name");
$my_sql['return']["07|Amount"] = array("source" => "CONCAT('\$',format(td.amount,2),'<BR>',td.cardtype,'-',td.bank_id) AS txt_amount","column"=>"txt_amount");
$my_sql['return']["09|Status"] = array("source" => "
														CONCAT(	
															if(td.status!='D',
																if(td.status='P','<b>Pending</b>', '<b>Approved</b>'),
																'<b>Declined</b>'
															),
															if(td.td_is_a_rebill=1,' (Rebilled)',' (New)'),
															if(td.status='D', CONCAT('<BR>',td.td_process_msg),''),
															if(td.cancelstatus='Y', CONCAT('<BR><b> - Refunded - </b>'),''),
															if(td.td_is_chargeback=1, CONCAT('<BR><b> - Charged Back - </b>'),''),
															if(ss.ss_subscription_ID is not null,
																CONCAT(
																	'<BR><a href=\"viewSubscription.php?subscription_ID=',ss_subscription_ID,'\">',ss_subscription_ID,'</a> is ',
																	if(ss.ss_rebill_status='active',
																		CONCAT(
																			'Active<BR>Next Rebill Date:<BR>' , 					              																		
																			DATE_FORMAT( ss_rebill_next_date, '%m-%d-%y %H:%i:%s' ) 
																		),
																		'Inactive')
																),
																'<BR>No Subscription'
															)
														) as status","column"=>"status",
	"disp_clip"=>array('overflow'=>true));

    
															
$my_sql['orderby'] = array("transactionId desc");
$my_sql['user_orderby']['txt_amount'] = "amount";
$my_sql['user_orderby']['status'] = "status";
$my_sql['user_orderby']['full_name'] = "full_name";
$my_sql['user_orderby']['companyname'] = "companyname";
$my_sql['user_orderby']['transaction_date_formatted'] = "transaction_date_formatted";

//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_transactions",
						"max_offset_source"=>"result");


	
$my_sql['sql_config'] = array('TimeOut'=>10);

if($_REQUEST['frm_timelimit'][0])
	$my_sql['sql_config'] = array('TimeOut'=>600);
	
$my_sql['search']['td.userId'] = array("input_type" => "company_search", "compare"=> "IN","required"=>0,"display" => "Company Name");

if($_REQUEST['companyname'][0]=='AL')
{
	$sql_info = JSON_getCompanyInfo_build($_REQUEST);
	$company_table_sql = "Select userID from ". $sql_info['sql_from']." Where ".$sql_info['sql_where'];
	//$my_sql['joins']['cd']['table'] = "($company_table_sql) as cd ";
	$_REQUEST['companyname'] = NULL;
	$my_sql['where']['cd.userid'] = array("value" => "($company_table_sql)", "compare" => "IN");

	
}
else
if($_REQUEST['companyname'][0]=='A')
	$_REQUEST['companyname'] = NULL;

$_REQUEST['frm_td_userId'] = $_REQUEST['companyname'];
//$my_sql['search']['td.userId']['action'] = "onChange=\"documentd.getElementById('frm_company_search').value = thics.options[thics.selectedIndex].text; func_company_fill();\"";
//$my_sql['search']['td.userId']['options']['source']['script'] = "smart_getCompanies";
//$my_sql['search']['td.userId']['options']['source']['parameters'] = NULL;

/*
$my_sql['search']['td.td_site_ID'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display"=>"Web Site");
$my_sql['search']['td.td_site_ID']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'

);
*/

$my_sql['search']['td.transactiondate'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-n-j");


if(		$_REQUEST['frm_td_email']!="" 
	|| $_REQUEST['frm_td_surname']!="" 
	|| $_REQUEST['frm_td_name']!="" 
	|| $_REQUEST['frm_td_reference_number']!="" 
	|| $_REQUEST['frm_ss_subscription_ID']!="" 
	|| $_REQUEST['frm_td_CCnumber']!="" 
	|| $_REQUEST['frm_td_bankaccountnumber']!="" 
	|| $_REQUEST['frm_td_bankroutingnumber']!="" 
	|| $_REQUEST['frm_td_bank_transaction_id']!="" 
	|| $_REQUEST['frm_td_phonenumber']!="" 
	|| $_REQUEST['frm_td_merchant_paid']!="" 
	|| $_REQUEST['frm_td_merchant_deducted']!="" 
	)
{
	$my_sql['search']['td.transactiondate']['required'] = false;
	$my_sql['search']['td.transactiondate']['in_query'] = false;
}
	
$my_sql['search']['td.reference_number'] = array("input_type" => "text", "compare"=>"in","required"=>0,"display"=>"Reference ID");
$my_sql['search']['ss_subscription_ID'] = array("input_type" => "text", "compare"=>"in","required"=>0,"display"=>"Subscription ID");
$my_sql['search']['td.amount'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Amount");

$my_sql['search']['td.name'] = array("input_type" => "text", "compare"=>"like","required"=>0,"display"=>"First Name");
$my_sql['search']['td.surname'] = array("input_type" => "text", "compare"=>"like","required"=>0,"display"=>"Last Name");
$my_sql['search']['td.email'] = array("input_type" => "text", "compare"=>"like","required"=>0,"display"=>"E-Mail");
$my_sql['search']['td.phonenumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Phone");

$my_sql['search']['td.CCnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Credit Card Number","swap"=>"etelEnc");

$my_sql['search']['td.bankaccountnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Account Number","swap"=>"etelEnc");
$my_sql['search']['td.bankroutingcode'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Routing Number","swap"=>"etelEnc");
$my_sql['search']['td_bank_transaction_id'] = array("input_type" => "text", "compare"=>"in","required"=>0,"display"=>"Bank Trans ID");

$my_sql['search']['td.td_process_msg'] = array("input_type" => "text", "compare"=>"like","required"=>0,"display"=>"Decline Reason");
$my_sql['search']['td_merchant_paid'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Invoice ID (Profit)");
$my_sql['search']['td_merchant_deducted'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Invoice ID (Deductions)");
//$my_sql['search']['n.note_id'] = array("input_type" => "checkbox", "compare"=> "<>","required"=>0,"display"=>"Is Refunded","value"=>" ");
//$my_sql['search']['n.note_id']['options']['source']['pairs'] = "is_refunded";

$my_sql['search']['td.bank_id'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display" => "Charge Bank");
$my_sql['search']['td.bank_id']['options']['source']['script'] = "smart_getBanks";
$my_sql['search']['td.bank_id']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);

$my_sql['search']['cd.gateway_id'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display" => "Company Gateway");
$my_sql['search']['cd.gateway_id']['options']['source']['script'] = "smart_getGateways";
$my_sql['search']['cd.gateway_id']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=100;'
);

$my_sql['search']['td.cardtype'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display" => "Charge Type");
$my_sql['search']['td.cardtype']['options']['source']['script'] = "smart_getChargeTypes";

$my_sql['search']['td.status'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display"=>"Status");
$my_sql['search']['td.status']['options']['source']['pairs'] = "Status";

$my_sql['search']['td.cancelstatus'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
$my_sql['search']['td.cancelstatus']['options']['source']['pairs'] = "CancelStatus";

$my_sql['search']['td.td_is_a_rebill'] = array("input_type" => "checkbox", "compare"=> "in","required"=>0,"display"=>"");
$my_sql['search']['td.td_is_a_rebill']['options']['source']['pairs'] = "IsRebill";

$my_sql['search']['td.td_is_chargeback'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
$my_sql['search']['td.td_is_chargeback']['options']['source']['pairs'] = "td_is_chargeback";

$my_sql['search']['UniqueDeclines'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"Show Only Unique Declines","in_query"=>false,"value"=>'1');

$my_sql['search']['td.td_bank_recieved'] = array("input_type" => "checkbox", "compare"=> "in","required"=>0,"display"=>"");
$my_sql['search']['td.td_bank_recieved']['options']['source']['pairs'] = "BankRecieved";

$my_sql['search']['testmode'] = array("input_type" => "radio", "in_query" => false,"display"=>"Transaction Mode");
$my_sql['search']['testmode']['options']['source']['pairs'] = "TestModes";

$my_sql['search']['timelimit'] = array("input_type" => "checkbox", "in_query" => false, "compare"=> "in","required"=>0,"display"=>"Disable Time Limit");
$my_sql['search']['timelimit']['options']['source']['pairs'] = "TimeLimit";

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";

$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

$UniqueDeclines = "and (status!='D' || td.td_non_unique=0)";
if(!$_REQUEST['frm_UniqueDeclines']) $UniqueDeclines = '';

$my_sql['where']['1'] = array("value" => "1 and td.bank_id>0 $UniqueDeclines $bank_sql_limit", "compare" => "=");
$my_sql['where']['cd.userId'] = array("value" => "not null", "compare" => "is");
//and (td.td_non_unique=0 or status!='D')

 
$detail = intval($_REQUEST['frm_subquery_detail']);
if($detail)
{
	
	$my_sql['subquery']['title'] = "Transaction Summary";
	$my_sql['subquery']['queries']['01|Total Value'] = array("name"=>"total_transactions", "source" => "CONCAT('\$',FORMAT(SUM(td.amount),2),' (',count(*),')')");
	$my_sql['subquery']['queries']['01|#Total Value'] = array("name"=>"number_transactions", "source" => "SUM(if(td.status !='P',1,0))","hidden"=>1);
	$my_sql['subquery']['queries']['03|Approved'] = array("name"=>"amount_approved", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status ='A',td.amount,0)),2),' (' , SUM(if(td.status ='A',1,0)), ')')");
	$my_sql['subquery']['queries']['03|#Approved'] = array("name"=>"number_approved", "source" => "SUM(if(td.status ='A',1,0))","hidden"=>1);
	$my_sql['subquery']['queries']['04|Declined'] = array("name"=>"amount_declined", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status = 'D',td.amount,0)),2),' (' ,SUM(if(td.status = 'D',1,0)) , ')')");
	$my_sql['subquery']['queries']['04|#Declined'] = array("name"=>"number_declined", "source" => "SUM(if(td.status = 'D',1,0))","hidden"=>1);
	$my_sql['subquery']['queries']['05|Rebilled'] = array("name"=>"amount_rebilled", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 1 && td.status ='A',td.amount,0)),2),' (' ,SUM(if(td.td_is_a_rebill = 1 && td.status ='A',1,0)) , ')')");
	$my_sql['subquery']['queries']['06|New Sales'] = array("name"=>"amount_newsales", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 0 && td.status ='A',td.amount,0)),2),' (',SUM(if(td.td_is_a_rebill = 0 && td.status ='A',1,0)),')')");
	if($detail>1)
	{
	//$my_sql['subquery']['queries']['06|Checks Approved'] = array("name"=>"amount_checks", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype = 'Check' && td.status ='A',td.amount,0)),2),' (',SUM(if(td.cardtype = 'Check' && td.status ='A',1,0)),')')");
	//$my_sql['subquery']['queries']['07|Credit Cards'] = array("name"=>"amount_credit", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype != 'Check' && td.status ='A',td.amount,0)),2),' (',SUM(if(td.cardtype != 'Check' && td.status ='A',1,0)),')')");
	$my_sql['subquery']['queries']['07|Min/Max/Avg'] = array("name"=>"min_max_avg", "source" => "CONCAT('\$',FORMAT(MIN(td.amount),2),'/\$',FORMAT(MAX(td.amount),2),'/\$',FORMAT(AVG(if(td.status ='A',td.amount,NULL)),2))");
	$my_sql['subquery']['queries']['08|Checks Submit'] = array("name"=>"submit_checks", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype = 'Check' && td.status ='P',td.amount,0)),2),' (',SUM(if(td.cardtype = 'Check' && td.status ='P',1,0)),')')");
	$my_sql['subquery']['queries']['10|Percent Approved'] = array("name"=>"percent_approved", "source" => "CONCAT(FORMAT(SUM(if(td.status ='A',td.amount,0))*100/SUM(if(td.status <> 'P',td.amount,0)),2),'%')");
	$my_sql['subquery']['queries']['11|Refunded'] = array("name"=>"refunded", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cancelstatus='Y',td.amount,0)),2),' (',SUM(if(td.cancelstatus='Y',1,0)),')')");
	$my_sql['subquery']['queries']['11|#Refunded'] = array("name"=>"refunded_num", "source" => "SUM(if(td.cancelstatus='Y',1,0))","hidden"=>1);
	$my_sql['subquery']['queries']['12|ChargedBack'] = array("name"=>"charged_back", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_chargeback = 1,td.amount,0)),2),' (',SUM(if(td.td_is_chargeback = 1,1,0)),')')");
	$my_sql['subquery']['queries']['12|#ChargedBack'] = array("name"=>"charged_back_num", "source" => "SUM(if(td.td_is_chargeback = 1,1,0))","hidden"=>1);
		if($detail>2)
		{
		$my_sql['subquery']['queries']['13|TransactionFees'] = array("name"=>"trans_fee", "source" => "CONCAT('\$',FORMAT(SUM(td.r_total_trans_fees-r_bank_trans_fee),2),' (',COUNT(*),')')");
		$my_sql['subquery']['queries']['14|DiscountFees'] = array("name"=>"discount_fee", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status = 'A', (td.r_total_discount_rate-r_bank_discount_rate)*(td.amount-td_customer_fee)/100,0)),2),' (',SUM(if(td.status = 'A',1,0)),')')");
		$my_sql['subquery']['queries']['15|CustomerFees'] = array("name"=>"cust_fee", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status = 'A',td.td_customer_fee,0)),2),' (',SUM(if(td.status = 'A' && td.td_customer_fee>0,1,0)),')')");
		$my_sql['subquery']['queries']['16|RefundFees'] = array("name"=>"refund_fee", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cancelstatus = 'Y',td.r_credit,0)),2),' (',SUM(if(td.cancelstatus='Y',1,0)),')')");
		$my_sql['subquery']['queries']['17|ChargebackFees'] = array("name"=>"chargeback_fee", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_chargeback = 1,td.r_chargeback,0)),2),' (',SUM(if(td.td_is_chargeback = 1,1,0)),')')");
		}
	}
}

if($export_data)
{
	unset($my_sql['return']);
	$my_sql['return'] = array(
		"10|Reference Id"=> array("source" => "td.reference_number as ReferenceID","column"=>"ReferenceID"),
		"10|Company Id"=> array("source" => "cd.userid as CompanyID","column"=>"CompanyID"),
		"10|Company Name"=> array("source" => "cd.companyname as CompanyName","column"=>"CompanyName"),
		"10|Beneficiary Name"=> array("source" => "cd.beneficiary_name as BeneficiaryName","column"=>"BeneficiaryName"),
		"10|Website Name"=> array("source" => "cs.cs_name as WebsiteName","column"=>"WebsiteName"),
		"10|Date"=> array("source" => "DATE_FORMAT(td.transactionDate,'%Y-%m-%d %H:%i:%s') as TransDate","column"=>"TransDate"),
		"10|Product Description"=> array("source" => "td.productdescription as ProductDescription","column"=>"ProductDescription"),
		"10|Full Name"=> array("source" => "concat(td.surname,', ',td.name) AS FullName","column"=>"FullName"),
		"10|IP Address"=> array("source" => "ipaddress","column"=>"ipaddress"),
		"10|Phone"=> array("source" => "td.phonenumber as PhoneNumber","column"=>"PhoneNumber"),
		"10|CC Hash"=> array("source" => "CCnumber","column"=>"CCnumber"),
		"10|Street Address"=> array("source" => "td.address","column"=>"address"),
		"10|Charge Type"=> array("source" => "td.cardtype AS ChargeType","column"=>"ChargeType"),
		"10|Amount"=> array("source" => "td.amount AS Amount","column"=>"Amount"),
		"10|Status"=> array("source" => "if(td.status!='D', if(td.status='P','Pending', 'Approved'), 'Declined'	) as Status","column"=>"Status"),
		"10|Chargeback?"=> array("source" => "if(td.td_is_chargeback=1,'Yes','No') as Chargeback","column"=>"Chargeback"),
		"10|Refund?"=> array("source" => "if(td.cancelstatus='Y','Yes','No') as Refund","column"=>"Refund"),
		"10|Subscription Id"=> array("source" => "ss.ss_subscription_ID as SubscriptionId","column"=>"SubscriptionId"),
		"10|Subscription Status"=> array("source" => "ss.ss_rebill_status as SubscriptionStatus","column"=>"SubscriptionStatus"),
		//"11|Processor Query"=> array("source" => "td.td_process_query as ProcessorQuery","column"=>"ProcessorQuery"),
		);
	if(in_array($export_data,array('full','transaction')))
		$my_sql['limit']['forcelimit']=30000;
		
	if($_REQUEST['frm_timelimit'][0])
		$my_sql['sql_config'] = array('TimeOut'=>600);
	
	if($my_sql['subquery']['queries']['07|Min/Max/Avg'])
	{
		unset($my_sql['subquery']['queries']['07|Min/Max/Avg']);
		$my_sql['subquery']['queries']['07|Min'] = array("name"=>"min", "source" => "CONCAT('\$',FORMAT(MIN(td.amount),2))");
		$my_sql['subquery']['queries']['07|Max'] = array("name"=>"max", "source" => "CONCAT('\$',FORMAT(MAX(td.amount),2))");
		$my_sql['subquery']['queries']['07|Avg'] = array("name"=>"avg", "source" => "CONCAT('\$',FORMAT(AVG(if(td.status ='A',td.amount,NULL)),2))");
	}
	if($my_sql['subquery']['queries']['03|#Approved']) $my_sql['subquery']['queries']['03|#Approved']['hidden']=false;
	if($my_sql['subquery']['queries']['01|#Total Value']) $my_sql['subquery']['queries']['01|#Total Value']['hidden']=false;
	if($my_sql['subquery']['queries']['04|#Declined']) $my_sql['subquery']['queries']['04|#Declined']['hidden']=false;
	if($my_sql['subquery']['queries']['11|#Refunded']) $my_sql['subquery']['queries']['11|#Refunded']['hidden']=false;
	if($my_sql['subquery']['queries']['12|#ChargedBack']) $my_sql['subquery']['queries']['12|#ChargedBack']['hidden']=false;

}


$subquery_group = quote_smart($_REQUEST['frm_subquery_group']);
$export_subname="";
if($subquery_group) 
{
	$my_sql['subgroupby'] = array("subgroup_by");
	$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
	$my_sql['subrollup'] = true;
	$my_sql['subgrouprolluptitle'] = "CONCAT('Total - ',daterange)";
	$sql_date_range = "' From ', DATE_FORMAT(min(transactionDate),'%b %D'),' To ', DATE_FORMAT(max(transactionDate),'%b %D'),'  (',(TO_DAYS(max(transactionDate))-TO_DAYS(min(transactionDate))+1),' Days)'";
	$my_sql['subquery']['queries']['00|Range'] = array("name"=>"daterange", "source" => "CONCAT($sql_date_range)",'hidden'=>1);
	switch($subquery_group)
	{		
		case 'CI':
			$my_sql['subgroupby'] = "CONCAT(td.userId,'-',td.td_merchant_paid)";
			$my_sql['subgrouptitle'] = "CONCAT(cd.companyname,' - ',td.td_merchant_paid)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
			$export_subname.="ByInvoiceID";

			break;
		case 'CB':
			$my_sql['subgroupby'] = "CONCAT(td.userId,'-',td.bank_id)";
			$my_sql['subgrouptitle'] = "CONCAT(cd.companyname,' - ',bk.bank_name)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
			$export_subname.="ByCompanyBank";
			
			$my_sql['subquery']['queries']['00|BankID'] = array("name"=>"bank_id", "source" => "td.bank_id","column"=>"bank_id","hidden"=>true);
			$my_sql['subquery']['queries']['00|EntityID'] = array("name"=>"en_ID", "source" => "(select en_ID from cs_entities where en_type_id = td.userId and en_type = 'merchant')","column"=>"en_ID","hidden"=>true);

			break;	
		case 'CD':
			$my_sql['subgroupby'] = "CONCAT(cd.userId,DATE_FORMAT( transactionDate , '-%y-%m-%d' ))";
			$my_sql['subgrouptitle'] = "CONCAT(cd.companyname,' - ',DATE_FORMAT( transactionDate , '%W, %M %D %Y' ))";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
			$export_subname.="ByCompanyMonth";
			
			//TODO: Custom columns
			//unset($my_sql['subquery']['queries']);
			break;	
		case 'CW':
			$my_sql['subgroupby'] = "CONCAT(cd.userId,DATE_FORMAT( transactionDate , '-%y-%U' ))";
			$my_sql['subgrouptitle'] = "CONCAT(cd.companyname,' - ',DATE_FORMAT( transactionDate , '%M, Week #%U -' ))";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
			$export_subname.="ByCompanyMonth";
			break;	
		case 'CM':
			$my_sql['subgroupby'] = "CONCAT(cd.userId,DATE_FORMAT( transactionDate , '-%y-%m' ))";
			$my_sql['subgrouptitle'] = "CONCAT(cd.companyname,' - ',DATE_FORMAT( transactionDate , '%y-%M' ))";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
			$export_subname.="ByCompanyMonth";
			break;	
		case 'C':
			$my_sql['subgroupby'] = "cd.userId";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',cd.companyname)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total for all Companies')";
			$my_sql['suborderby'] = "is_rollup desc, number_transactions desc";
			$export_subname.="ByCompany";
			if($export_data)
			{
				$my_sql['subquery']['queries']['24|Beneficiary Name'] = array("name"=>"BeneficiaryName", "source" => "cd.beneficiary_name","column"=>"BeneficiaryName");
				$my_sql['subquery']['queries']['25|First Name'] = array("name"=>"FirstName", "source" => "cd.first_name","column"=>"FirstName");
				$my_sql['subquery']['queries']['26|Last Name'] = array("name"=>"LastName", "source" => "cd.family_name","column"=>"LastName");
				$my_sql['subquery']['queries']['27|Address'] = array("name"=>"Address", "source" => "cd.address","column"=>"Address");
				$my_sql['subquery']['queries']['28|City'] = array("name"=>"city", "source" => "cd.city","column"=>"city");
				$my_sql['subquery']['queries']['29|State'] = array("name"=>"state", "source" => "cd.state","column"=>"state");
				$my_sql['subquery']['queries']['30|Country'] = array("name"=>"country", "source" => "cd.country","column"=>"country");
				$my_sql['subquery']['queries']['31|Zipcode'] = array("name"=>"zipcode", "source" => "cd.zipcode","column"=>"zipcode");
			}
			break;		
		case 'D':
			$my_sql['subgroupby'] = "td_process_msg";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',td_process_msg)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total Transactions')";
			$my_sql['suborderby'] = "is_rollup desc, number_transactions desc";
			$export_subname.="ByDeclineReason";
			$detail=1;
			break;
		case 'S':
			$my_sql['subgroupby'] = "cs.cs_ID";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',cs.cs_name)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',number_transactions,') Total for all Websites')";
			$my_sql['suborderby'] = "is_rollup desc, number_transactions desc";
			$export_subname.="ByWebsite";
			if($export_data)
			{
				$my_sql['subquery']['queries']['0|Site_Ref'] = array("name"=>"csref", "source" => "cs.cs_reference_ID");
				$my_sql['subquery']['queries']['20|URL'] = array("name"=>"csurl", "source" => "cs.cs_URL");
				$my_sql['subquery']['queries']['20|Email'] = array("name"=>"csurl", "source" => "cd.email");
				$my_sql['subquery']['queries']['21|MembersURL'] = array("name"=>"murl", "source" => "cs.cs_member_url");
				$my_sql['subquery']['queries']['22|MembersUser'] = array("name"=>"muser", "source" => "cs.cs_member_username");
				$my_sql['subquery']['queries']['23|MembersPass'] = array("name"=>"mpass", "source" => "cs.cs_member_password");
				$my_sql['subquery']['queries']['24|Beneficiary Name'] = array("name"=>"BeneficiaryName", "source" => "cd.beneficiary_name","column"=>"BeneficiaryName");
				$my_sql['subquery']['queries']['25|First Name'] = array("name"=>"FirstName", "source" => "cd.first_name","column"=>"FirstName");
				$my_sql['subquery']['queries']['26|Last Name'] = array("name"=>"LastName", "source" => "cd.family_name","column"=>"LastName");
				$my_sql['subquery']['queries']['27|Address'] = array("name"=>"Address", "source" => "cd.address","column"=>"Address");
				$my_sql['subquery']['queries']['28|City'] = array("name"=>"city", "source" => "cd.city","column"=>"city");
				$my_sql['subquery']['queries']['29|State'] = array("name"=>"state", "source" => "cd.state","column"=>"state");
				$my_sql['subquery']['queries']['30|Country'] = array("name"=>"country", "source" => "cd.country","column"=>"country");
				$my_sql['subquery']['queries']['31|Zipcode'] = array("name"=>"zipcode", "source" => "cd.zipcode","column"=>"zipcode");
			}
			break;
		case 1:
			$my_sql['subgroupby'] = "DATE_FORMAT( transactionDate , '%y-%m-%d' )";
			$my_sql['subgrouptitle'] = "DATE_FORMAT( transactionDate , '%W, %M %D %Y' ) ";
			$export_subname.="ByDay";
			break;
		case 7:
			$my_sql['subgroupby'] = "DATE_FORMAT( transactionDate , '%y-%U' )";
			$my_sql['subgrouptitle'] = "CONCAT(DATE_FORMAT( transactionDate , '%M, Week #%U -'),$sql_date_range ) ";
			$export_subname.="ByWeek";
			break;
		case 30:
			$my_sql['subgroupby'] = "DATE_FORMAT( transactionDate , '%y-%M' ) ";
			$my_sql['subgrouptitle'] = "CONCAT( DATE_FORMAT( transactionDate , '%M -' ),$sql_date_range) ";
			$export_subname.="ByMonth";
			break;
		case 60:
		case 90:
		case 180:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "ROUND(MONTH( transactionDate )/".intval($subquery_group/30).") ";
			$my_sql['subgrouptitle'] = "CONCAT($sql_date_range )";
			$export_subname.="By".$subquery_group."Days";
			break;
		case 360:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "DATE_FORMAT( transactionDate , '%Y' )";
			$my_sql['subgrouptitle'] = "CONCAT(DATE_FORMAT( transactionDate , '%Y -' ),$sql_date_range)";
			$export_subname.="ByYear";
			break;
		default:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "FLOOR(TO_DAYS(transactionDate)/($subquery_group))";
			$my_sql['subgrouptitle'] = "CONCAT($sql_date_range )";
			$export_subname.="By".$subquery_group."Days";
			break;
	}
}

$my_sql['postpage'] = "report_Smart.php";
$my_sql['title'] = "Find Transactions";

$my_sql['result_actions']['postpage'] = "report_Smart.php";
$my_sql['result_actions']['title'] = "Transactions Found";
$my_sql['result_actions']['resulttitle'] = "Transactions Processed";

//$my_sql['result_actions']['actions']['userid'] = array("input_type" =>"hidden", "compare"=> "=","required"=>1,"value" => $sessionlogin);
$my_sql['result_actions']['actions']['entries'] = array("input_type"=>"select","display"=>"","required" => 1);
$my_sql['result_actions']['actions']['entries']['options']['source']['pairs'] = "Actions";
$my_sql['result_actions']['actions']['entries']['style']['style'] = "width:88;";
$my_sql['result_actions']['actions']['entries']['style']['onchange'] = "check_additional(this);";//$().style.visibility=alert('test')

$my_sql['result_actions']['actions']['refund_request']['input_type']='textarea';
$my_sql['result_actions']['actions']['refund_request']['style']['style']='visibility:hidden;width:88;height:1;';

$my_sql['result_actions']['process'] = "smart_processTransactions";
$my_sql['result_actions']['append'] = array("name"=>"transactionid","source"=>"result");

$my_sql['pairs']['PendingCheck'][] = array("display" => "Pending Check", "value"=>1);
$my_sql['pairs']['is_refunded'][] = array("display" => "Is Refunded", "value"=>"1");
$my_sql['pairs']['IsRebill'][] = array("display" => "Is A Rebill", "value"=>"1");
$my_sql['pairs']['IsRebill'][] = array("display" => "Is a New Order", "value"=>"0");
$my_sql['pairs']['td_is_chargeback'][] = array("display" => "Is a Chargeback", "value"=>"1");
$my_sql['pairs']['CancelStatus'][] = array("display" => "Is Refunded", "value"=>"Y");

$my_sql['pairs']['Rebilling'] = array(
		array("display" => "Enabled", "value"=>"1"),
		array("display" => "Disabled", "value"=>"0")
		);
		
$my_sql['pairs']['BankRecieved'] = array(
		array("display" => "Bank Recieved", "value"=>"yes"),
		array("display" => "Bank Did Not Recieve", "value"=>"no")
		);

$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "25", "value"=>"25"),
	array("display" => "10", "value"=>"10"),
	array("display" => "100", "value"=>"100"),
	array("display" => "1000", "value"=>"1000"),
	array("display" => "None", "value"=>"-1")
	);

$my_sql['pairs']['SubGroupTypes'] = array(
	array("display" => "All", "value"=>"0"),
	array("display" => "By Day", "value"=>"1"),
	array("display" => "By Week", "value"=>"7"),
	array("display" => "By 2 Weeks", "value"=>"14"),
	array("display" => "By Month", "value"=>"30"),
	array("display" => "By 2 months", "value"=>"60"),
	array("display" => "By 3 months", "value"=>"90"),
	array("display" => "By 6 months", "value"=>"180"),
	array("display" => "By Year", "value"=>"360"),
	array("display" => "By Decline Reason", "value"=>"D"),
	array("display" => "By Company", "value"=>"C"),
	array("display" => "By Website", "value"=>"S"),
	array("display" => "By Company Per Bank", "value"=>"CB"),
	array("display" => "By Company Per Day", "value"=>"CD"),
	array("display" => "By Company Per Week", "value"=>"CW"),
	array("display" => "By Company Per Month", "value"=>"CM"),
	array("display" => "By Company Per Invoice", "value"=>"CI")
	);

$my_sql['pairs']['SubGroupDetails'] = array(
	array("display" => "Full", "value"=>"3"),
	array("display" => "Medium", "value"=>"2"),
	array("display" => "Minimal", "value"=>"1"),
	array("display" => "None", "value"=>"0")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['ExportDetails'] = array(
	array("display" => "Disabled", "value"=>""),
	array("display" => "Full Export", "value"=>"full"),
	array("display" => "Just Summary", "value"=>"summary"),
	array("display" => "Just Transaction Data", "value"=>"transaction")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['Status'] = array(
	array("display" => "Approved", "value"=>"A"),
	array("display" => "Declined", "value"=>"D"),
	array("display" => "Pending", "value"=>"P")
	);
	

$my_sql['pairs']['Actions'] = array(
	array("display" => "No Action", "value"=>""),
	array("display" => "Create Refund Request", "value"=>"smart_processRefund","condition_var"=>"is_refunded","condition_val"=>0),
	array("display" => "Cancel Subscription", "value"=>"smart_cancelRebill","condition_var"=>"ss_rebill_status","condition_val"=>'active'),
	array("display" => "Restart Subscription", "value"=>"smart_restartRebill","condition_var"=>"ss_rebill_status","condition_val"=>'inactive')	
	);
						
if($curUserInfo['en_access'][ACCESS_AUTH_TRANS_MOD])
{ 
	$my_sql['pairs']['Actions'][] = array("display" => "Set Chargeback", "value"=>"smart_setChargeback","condition_var"=>"td_is_chargeback","condition_val"=>0);
	$my_sql['pairs']['Actions'][] = array("display" => "Remove Chargeback", "value"=>"smart_removeChargeback","condition_var"=>"td_is_chargeback","condition_val"=>1);
	$my_sql['pairs']['Actions'][] = array("display" => "Set Refunded", "value"=>"smart_setRefund","condition_var"=>"is_refunded","condition_val"=>0);
	$my_sql['pairs']['Actions'][] = array("display" => "Remove Refunded", "value"=>"smart_removeRefund","condition_var"=>"is_refunded","condition_val"=>1);
	$my_sql['pairs']['Actions'][] = array("display" => "Set Approved", "value"=>"smart_setApproved");
	$my_sql['pairs']['Actions'][] = array("display" => "Set Declined", "value"=>"smart_setDeclined");
	$my_sql['pairs']['Actions'][] = array("display" => "Set Voided", "value"=>"smart_processVoid");
}
$my_sql['pairs']['Company_Search_By'] = array(
	array("display" => "Company Name", "value"=>"cn"),
	array("display" => "Reference ID", "value"=>"ri"),
	array("display" => "Login UserName", "value"=>"un"),
	array("display" => "Contact Email", "value"=>"em"),
	array("display" => "Website Name", "value"=>"wn"),
	array("display" => "Website Reference ID", "value"=>"wr"),
	array("display" => "Merchant ID (List)", "value"=>"id")
);

$my_sql['pairs']['TestModes'] = array(
	array("display" => "Live", "value"=>"0"),
	array("display" => "Test", "value"=>"1")
	);

$my_sql['pairs']['TimeLimit'] = array(
	array("display" => "Disable", "value"=>"1")
	);
	
/****************
Process and Render Forms
****************/


if(is_array($_REQUEST['frm_td_reference_number'])) $_REQUEST['frm_td_reference_number'] = implode(",",$_REQUEST['frm_td_reference_number']);
if(is_array($_REQUEST['frm_td_bank_transaction_id'])) $_REQUEST['frm_td_bank_transaction_id'] = implode(",",$_REQUEST['frm_td_bank_transaction_id']);

smart_render_action_results(smart_process_action_form($my_sql['result_actions']),$my_sql['result_actions']['resulttitle']);

smart_search_form($my_sql);

$_REQUEST['frm_td_reference_number'] = explode(",",$_REQUEST['frm_td_reference_number']);
$_REQUEST['frm_td_bank_transaction_id'] = explode(",",$_REQUEST['frm_td_bank_transaction_id']);

if(smart_process_mysql_form($my_sql))
{
	if($export_data)
	{	
		if($export_data=='summary') {$my_sql['skip_query']=true; }
		if($export_data=='transaction') {$my_sql['skip_subquery']=true;  $export_subname = '';}
		ob_end_clean();
		$filename = 'Export'.$export_subname.'.csv';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		echo "\n";
		flush();
	}
	
	$result = smart_search($my_sql);
	
	if($export_data)
	{	
		if($subquery_group == 'CB' && $result['sub_row']) // Get Rates per bank
		{
			$RF = new rates_fees();
			foreach($result['sub_row'] as $key=>$row)
			{
				$rates = @array_pop($RF->get_MerchantRates($row['en_ID'],array(intval($row['bank_id']))));
				$cats = $RF->get_RateCategories();
				
				if(!$rates) 	// Default rates
					$rates = @array_pop($RF->get_MerchantRates($row['en_ID'],array(0)));
					
				//etelPrint($row);etelPrint($rates);etelDie();
				foreach($rates['default']['Processor'] as $rate=>$val)
					$result['sub_row'][$key]['proc_'.$rate] = $cats[$rate]['before'].round($val,2).$cats[$rate]['after'];
					
				foreach($rates['default']['Bank'] as $rate=>$val)
					$result['sub_row'][$key]['bank_'.$rate] = $cats[$rate]['before'].round($val,2).$cats[$rate]['after'];
			}				
			$my_sql['subquery']['queries']['3a|Proc_TransFee'] = array("name"=>"proc_trans");
			$my_sql['subquery']['queries']['3b|Proc_Disc'] = array("name"=>"proc_disct");
			$my_sql['subquery']['queries']['3c|Proc_DeclineFee'] = array("name"=>"proc_decln");
			$my_sql['subquery']['queries']['3d|Proc_RefundFee'] = array("name"=>"proc_refnd");
			$my_sql['subquery']['queries']['3e|Proc_CBFee'] = array("name"=>"proc_chgbk");
			$my_sql['subquery']['queries']['3f|Proc_CustFee'] = array("name"=>"proc_cstsv");
			$my_sql['subquery']['queries']['3g|Proc_Reserve'] = array("name"=>"proc_rserv");
			
			$my_sql['subquery']['queries']['3h|Bank_TransFee'] = array("name"=>"bank_trans");
			$my_sql['subquery']['queries']['3i|Bank_Disc'] = array("name"=>"bank_disct");
			$my_sql['subquery']['queries']['3j|Bank_DeclineFee'] = array("name"=>"bank_decln");
			$my_sql['subquery']['queries']['3k|Bank_RefundFee'] = array("name"=>"bank_refnd");
			$my_sql['subquery']['queries']['3l|Bank_CBFee'] = array("name"=>"bank_chgbk");
			$my_sql['subquery']['queries']['3m|Bank_Reserve'] = array("name"=>"bank_rserv");
			//etelPrint($my_sql['subquery']['queries']);etelPrint($result['sub_row']);etelDie();
			//etelDie();
		}	
	
		smart_render_export($result, $my_sql);
		die();
	}
	smart_render_results($result, $my_sql);
}


include("includes/footer.php");
?>
