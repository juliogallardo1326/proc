<?
$pageConfig['Title'] = 'Transaction Lookup';
$pageConfig['SubHeader'] = 'reports';
include 'includes/sessioncheckuser.php';
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();
include 'includes/header.php';
require_once('includes/subFunctions/smart_search.php');
require_once("includes/transaction.class.php");


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

function smart_processRefund($values,$action)
{
	$reason = $_POST['frm_refund_request_'.$values['append']];
	if(strlen($reason)>3)
	{
		$trans = new transaction_class(false);
		$trans->pull_transaction($values['append']);
		$status = $trans->process_refund_request(array("actor"=>'Merchant','notes'=>"$reason",'verifyuserId'=>$companyInfo['userId']));
		return array("action"=>"Created A Refund Request for Transaction Reference " . $trans->row['transactionTable']['reference_number'] . ".","status"=>($status['status']));
	}
	return array("action"=>"Failed to Create Refund Request (Invalid Reason).","status"=>('fail'));
}

function smart_cancelRebill($values,$action)
{
	$trans = new transaction_class(false);
	$trans->pull_transaction($values['append']);
	$status = $trans->process_cancel_request(array("actor"=>'Merchant','verifyuserId'=>$companyInfo['userId']));
	return array("action"=>"Subscription ID " . $trans->row['subscriptionTable']['ss_subscription_ID'] . " cancelled.","status"=>($status?"success":"fail"));
}
/****************
Define Search Fields and Action Fields
****************/

if(!$_REQUEST['frm_testmode']) $_REQUEST['frm_testmode']=0;

$transaction_table = ($_REQUEST['frm_testmode'] ? "cs_test_transactiondetails" : "cs_transactiondetails");
$test_mode = intval($_REQUEST['frm_testmode']);

$display_rebill_info = isset($_REQUEST['frm_td_td_enable_rebill']) ? $_REQUEST['frm_td_td_enable_rebill'] : "frm_td_td_enable_rebill";
if(is_array($display_rebill_info))
	$display_rebill_info = in_array("1",$display_rebill_info) && !in_array("0",$display_rebill_info);

$my_sql['tables'] = array("$transaction_table AS td");
$my_sql['joins'] = array(
		array("table"=>"cs_companydetails AS cd",
				"on"=>
					array(
						array("field_a"=>"td.userId","field_b"=>"cd.userId","compare"=>"=")
					)
				),
		array("table"=>"cs_callnotes AS cn",
				"on"=>
					array(
						array("field_a"=>"td.transactionId","field_b"=>"cn.transaction_id","compare"=>"=")
					)
				),
		array("table"=>"cs_company_sites AS cs",
				"on"=>
					array(
						array("field_a"=>"td.td_site_ID","field_b"=>"cs.cs_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_subscription AS ss",
				"on"=>
					array(
						array("field_a"=>"td.td_ss_ID","field_b"=>"ss.ss_ID","compare"=>"=")
					)
				)
		);

//$my_sql['return']["01|Username"] = array("source" => "td.td_username","column"=>"td_username");
//$my_sql['return']["04|Password"] = array("source" => "td.td_password","column"=>"td_password");
//$my_sql['return']["03|Transaction ID"] = array("source" => "td.reference_number","column"=>"reference_number");

$my_sql['return']["02|Transaction Date"] = array("source" => "DATE_FORMAT(td.transactionDate,'%m/%d/%Y %l:%i:%s %p') as transaction_date_formatted","column"=>"transaction_date_formatted");
$my_sql['return']["00|td_process_msg"] = array("source" => "td.td_process_msg","column"=>"td_process_msg","hidden"=>1);
$my_sql['return']["00|Amount"] = array("source" => "td.amount","column"=>"amount","hidden"=>1);
$my_sql['return']["00|Subscription ID"] = array("source" => "ss.ss_rebill_status","column"=>"ss_rebill_status","hidden"=>1);
$my_sql['return']["00|Transaction ID"] = array("source" => "td.transactionid","column"=>"transactionid","hidden"=>1);
$my_sql['return']["00|Status Text"] = array("source" => "td.td_process_result","column"=>"td_process_result","hidden"=>1);
$my_sql['return']["00|Site ID"] = array("source" => "td.td_site_ID","column"=>"td_site_ID","hidden"=>1);

$my_sql['return']["01|Reference Number"] = array("source" => "td.reference_number","column"=>"reference_number");
$my_sql['return']["01|Reference Number"]["link"]["destination"] = "viewTransaction.php";
$my_sql['return']["01|Reference Number"]["link"]["parameters"] = array(
		array("name"=>"ref","value"=>"reference_number","source"=>"result"),
		array("name"=>"test","value"=>"$test_mode"),
		);

$my_sql['return']["02|Website"] = array("source" => "cs.cs_name","column"=>"cs_name","crop"=>20);

$my_sql['return']["02|Website"]["link"]["destination"] = "addwebsiteuser.php";
$my_sql['return']["02|Website"]["link"]["parameters"] = array(array("name"=>"cs_ID","value"=>"td_site_ID","source"=>"result"),array("name"=>"mode","value"=>"edit"));

$my_sql['return']["04|Name"] = array("source" => "CONCAT(td.surname,', ',td.name) AS full_name","column"=>"full_name");
$my_sql['return']["05|Description"] = 
array("source" => "concat(
						if(td.td_username != '',CONCAT('U:',td.td_username,'<BR>P:',td.td_password,'<BR>'),''),
						if(td.from_url != '',CONCAT('URL:<a href=\"',td.from_url,'\">',td.from_url,'</a><BR>'),''),
						if(td.productdescription != '',CONCAT('Desc: ',td.productdescription,'<BR>'),'')
					) as description",
	"column"=>"description",
	"disp_clip"=>array('overflow'=>true)

	);
$my_sql['return']["07|Amount"] = array("source" => "CONCAT('\$',format(td.amount,2),' ',td.cardtype) AS txt_amount","column"=>"txt_amount");
$my_sql['return']["09|Status"] = array("source" => "CONCAT(	
		if(td.status!='D',
			if(td.status='P','<b>Pending</b>', '<b>Approved</b>'),
			'<b>Declined</b>'
		),
		if(td.td_is_a_rebill=1,' (Rebilled)',' (New)'),
		if(td.cancelstatus='Y', CONCAT('<BR><b> - Refunded - </b>'),''),
		if(td.td_is_chargeback=1, CONCAT('<BR><b> - Charged Back - </b>'),''),
		if(td.status='D', CONCAT('<BR>',td.td_process_msg),''),
		if(ss.ss_subscription_ID is not null,
			CONCAT(
				'<BR><a href=\"viewSubscription.php?subscription_ID=',ss_subscription_ID,'\">Subscription</a> is ',
				if(ss.ss_rebill_status='active',
					CONCAT(
						'Active<BR>Next Rebill Date: ' , 						              																		
						DATE_FORMAT( ss_rebill_next_date, '%m-%d-%y %H:%i:%s' ) 
					),
					'Inactive')
			),
			'<BR>No Subscription'
		)
	) as status","column"=>"status",
	"disp_clip"=>array('overflow'=>true)
	);
//		'<BR>', ss_subscription_ID, ' is ',
if($display_rebill_info == "1" && 0)
{
$my_sql['joins'][] = 
		array("table"=>"cs_rebillingdetails AS rd",
				"on"=>
					array(
						array("field_a"=>"td.td_rebillingid","field_b"=>"rd.rd_subaccount","compare"=>"=")
					)
				);
	$my_sql['return']["10|Rebills Every"] = array("source" => "CONCAT(rd.recur_day,' Days') AS recur_day","column"=>"recur_day");
	$my_sql['return']["11|Next Rebill"] = array("source" => "td.td_recur_next_date","column"=>"td_recur_next_date");;
}

$my_sql['orderby'] = array("transactionId desc");
$my_sql['user_orderby']['txt_amount'] = "amount";
$my_sql['user_orderby']['status'] = "status";
$my_sql['user_orderby']['full_name'] = "full_name";
$my_sql['user_orderby']['cs_name'] = "cs_name";
$my_sql['user_orderby']['transaction_date_formatted'] = "transaction_date_formatted";


//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_transactions",
						"max_offset_source"=>"result");

$my_sql['search']['td.userId'] = array("input_type" => "hidden", "compare"=> "=","required"=>1,"value" => $companyInfo['userId']);

$my_sql['search']['td.td_site_ID'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display"=>"Web Site");
$my_sql['search']['td.td_site_ID']['style'] = array("size"=>3);
$my_sql['search']['td.td_site_ID']['options']['source']['script'] = "smart_getSites";
$my_sql['search']['td.td_site_ID']['options']['source']['parameters']['userid'] = $companyInfo['userId'];
$my_sql['search']['td.td_site_ID']['style'] = array("size"=>10,
		"style"=>"width: 250px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);



$my_sql['search']['td.transactiondate'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-n-j");

if(		$_REQUEST['frm_td_email']!="" 
	|| $_REQUEST['frm_td_surname']!="" 
	|| $_REQUEST['frm_td_name']!="" 
	|| $_REQUEST['frm_td_reference_number']!="" 
	|| $_REQUEST['frm_ss_subscription_id']!="" 
	|| $_REQUEST['frm_td_CCnumber']!="" 
	|| $_REQUEST['frm_td_phonenumber']!="" 
	)
		
{
	$my_sql['search']['td.transactiondate']['in_query'] = false;
	$my_sql['search']['td.transactiondate']['required'] = false;
}


$my_sql['sql_config'] = array('TimeOut'=>10);
if($export_data)
{
	unset($my_sql['return']);
	$my_sql['return'] = array(
		"10|Reference Id"=> array("source" => "td.reference_number as ReferenceID","column"=>"ReferenceID"),
		"10|Website Id"=> array("source" => "cs.cs_reference_ID as WebsiteID","column"=>"WebsiteID"),
		"10|Date"=> array("source" => "DATE_FORMAT(td.transactionDate,'%Y-%m-%d %H:%i:%s') as TransDate","column"=>"TransDate"),
		"10|Product Description"=> array("source" => "td.productdescription as ProductDescription","column"=>"ProductDescription"),
		"10|Full Name"=> array("source" => "concat(td.surname,', ',td.name) AS FullName","column"=>"FullName"),
		"10|Charge Type"=> array("source" => "td.cardtype AS ChargeType","column"=>"ChargeType"),
		"10|Amount"=> array("source" => "td.amount AS Amount","column"=>"Amount"),
		"10|Status"=> array("source" => "if(td.status!='D', if(td.status='P','Pending', 'Approved'), 'Declined'	) as Status","column"=>"Status"),
		"10|Chargeback?"=> array("source" => "if(td.td_is_chargeback=1,'Yes','No') as Chargeback","column"=>"Chargeback"),
		"10|Refund?"=> array("source" => "if(td.cancelstatus='Y','Yes','No') as Refund","column"=>"Refund"),
		"10|Subscription Id"=> array("source" => "ss.ss_subscription_ID as SubscriptionId","column"=>"SubscriptionId"),
		"10|Subscription Status"=> array("source" => "ss.ss_rebill_status as SubscriptionStatus","column"=>"SubscriptionStatus"),
		);
	if(in_array($export_data,array('full','transaction','transaction2')))
		$my_sql['limit']['forcelimit']=30000;
		
	$my_sql['sql_config'] = array('TimeOut'=>30);
		
	if($export_data=='transaction2')
		$my_sql['return']["11|MerchantData"] = array("source" => "td_merchant_fields as MerchantData","column"=>"MerchantData");
	
}

$my_sql['search']['td.bankaccountnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Account Number","swap"=>"etelEnc");
$my_sql['search']['td.bankroutingcode'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Routing Number","swap"=>"etelEnc");

$my_sql['search']['td.CCnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Credit Card Number","swap"=>"etelEnc");

$my_sql['search']['td.reference_number'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Reference ID");
$my_sql['search']['ss_subscription_id'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Subscription ID");

$my_sql['search']['td.name'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"First Name");
$my_sql['search']['td.surname'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Last Name");
$my_sql['search']['td.email'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"E-Mail");
$my_sql['search']['td.phonenumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Phone");

$my_sql['search']['td.cardtype'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display" => "Charge Type");
$my_sql['search']['td.cardtype']['options']['source']['script'] = "smart_getChargeTypes";

$my_sql['search']['td.status'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display"=>"Status");
$my_sql['search']['td.status']['options']['source']['pairs'] = "Status";

$my_sql['search']['td.cancelstatus'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
$my_sql['search']['td.cancelstatus']['options']['source']['pairs'] = "CancelStatus";

$my_sql['search']['td.td_is_chargeback'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
$my_sql['search']['td.td_is_chargeback']['options']['source']['pairs'] = "ChargeBack";

$my_sql['search']['td.td_is_a_rebill'] = array("input_type" => "checkbox", "compare"=> "in","required"=>0,"display"=>"");
$my_sql['search']['td.td_is_a_rebill']['options']['source']['pairs'] = "IsRebill";

$my_sql['search']['testmode'] = array("input_type" => "radio", "in_query" => false,"display"=>"Transaction Mode");
$my_sql['search']['testmode']['options']['source']['pairs'] = "TestModes";

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";

$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

$my_sql['where']['1'] = array("value" => "1 and td.bank_id>0 and (status!='D' || td.td_non_unique=0) and (td.status!='P' || td.cardtype='Check')  $bank_sql_limit", "compare" => "=");
//and (td.td_non_unique=0 or status!='D')


$detail = intval($_REQUEST['frm_subquery_detail']);
$subquery_group = quote_smart($_REQUEST['frm_subquery_group']);
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
$my_sql['subquery']['title'] = "Transaction Summary";
//if($detail)
//{
	$my_sql['subquery']['queries']['01|Transactions'] = array("name"=>"number_transactions", "source" => "COUNT(td.amount)","hidden"=>1);
	$my_sql['subquery']['queries']['02|Approved'] = array("name"=>"amount_approved", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status ='A',td.amount,0)),2),' (' , SUM(if(td.status ='A',1,0)), ')')");
	$my_sql['subquery']['queries']['03|Declined'] = array("name"=>"amount_declined", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status = 'D',td.amount,0)),2),' (' ,SUM(if(td.status = 'D',1,0)) , ')')");
	$my_sql['subquery']['queries']['04|Total Attempted'] = array("name"=>"total_transactions", "source" => "CONCAT('\$',FORMAT(SUM(td.amount),2),' (',count(*),')')");
	$my_sql['subquery']['queries']['05|Rebilled'] = array("name"=>"amount_rebilled", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 1 && td.status ='A',td.amount,0)),2),' (' ,SUM(if(td.td_is_a_rebill = 1 && td.status ='A',1,0)) , ')')");
	$my_sql['subquery']['queries']['06|Percent Approved'] = array("name"=>"percent_approved", "source" => "CONCAT(FORMAT(SUM(if(td.status ='A',td.amount,0))*100/SUM(if(td.status <> 'P',td.amount,0)),2),'%')");
	if($detail>1)
	{
		$my_sql['subquery']['queries']['07|Checks Submit'] = array("name"=>"submit_checks", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype = 'Check' && td.status ='P',td.amount,0)),2),' (',SUM(if(td.cardtype = 'Check' && td.status ='P',1,0)),')')");
		$my_sql['subquery']['queries']['08|Credit Cards'] = array("name"=>"amount_credit", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype != 'Check' && td.status ='A',td.amount,0)),2),' (',SUM(if(td.cardtype != 'Check' && td.status ='A',1,0)),')')");
		$my_sql['subquery']['queries']['09|New Sales'] = array("name"=>"amount_newsales", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 0 && td.status ='A',td.amount,0)),2),' (',SUM(if(td.td_is_a_rebill = 0 && td.status ='A',1,0)),')')");
		$my_sql['subquery']['queries']['10|Checks Approved'] = array("name"=>"amount_checks", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype = 'Check' && td.status ='A',td.amount,0)),2),' (',SUM(if(td.cardtype = 'Check' && td.status ='A',1,0)),')')");
	}
//}
$my_sql['postpage'] = "report_Smart.php";
$my_sql['title'] = "Find Transactions";

$my_sql['result_actions']['postpage'] = "report_Smart.php";
$my_sql['result_actions']['title'] = "Transactions Found";
$my_sql['result_actions']['resulttitle'] = "Transactions Processed";


$my_sql['result_actions']['actions']['userid'] = array("input_type" =>"hidden", "compare"=> "=","required"=>1,"value" => $companyInfo['userId']);
$my_sql['result_actions']['actions']['entries'] = array("input_type"=>"select","display"=>"","required" => 1);
$my_sql['result_actions']['actions']['entries']['options']['source']['pairs'] = "Actions";
$my_sql['result_actions']['actions']['entries']['style']['style'] = "width:88;";
$my_sql['result_actions']['actions']['entries']['style']['onchange'] = "check_additional(this);";

$my_sql['result_actions']['actions']['refund_request']['input_type']='textarea';
$my_sql['result_actions']['actions']['refund_request']['style']['style']='visibility:hidden;width:88;height:1;';

$my_sql['result_actions']['process'] = "smart_processTransactions";
$my_sql['result_actions']['append'] = array("name"=>"transactionid","source"=>"result");

$my_sql['pairs']['PendingCheck'][] = array("display" => "Pending Check", "value"=>1);
$my_sql['pairs']['CancelStatus'][] = array("display" => "Is Refunded", "value"=>"Y");
$my_sql['pairs']['ChargeBack'][] = array("display" => "Is a Chargeback", "value"=>"1");
$my_sql['pairs']['IsRebill'][] = array("display" => "Is A Rebill", "value"=>"1");
$my_sql['pairs']['IsRebill'][] = array("display" => "Is Not Rebill", "value"=>"0");

$my_sql['pairs']['Rebilling'] = array(
//		array("display" => "Either", "value"=>""),
		array("display" => "Enabled", "value"=>"1"),
		array("display" => "Disabled", "value"=>"0")
		);


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "100", "value"=>"100")
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
	array("display" => "By Website", "value"=>"S")
	);

$my_sql['pairs']['SubGroupDetails'] = array(
	array("display" => "Full", "value"=>"2"),
	array("display" => "Minimal", "value"=>"1")
	//array("display" => "None", "value"=>"0")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['ExportDetails'] = array(
	array("display" => "Disabled", "value"=>""),
	array("display" => "Full Export", "value"=>"full"),
	array("display" => "Summary", "value"=>"summary"),
	array("display" => "Transaction Data", "value"=>"transaction"),
	array("display" => "Transaction Data (Extended)", "value"=>"transaction2")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['Status'] = array(
	array("display" => "Approved", "value"=>"A"),
	array("display" => "Declined", "value"=>"D"),
	array("display" => "Pending", "value"=>"P")
	);
	

$my_sql['pairs']['Actions'] = array(
	array("display" => "No Action", "value"=>""),
	array("display" => "Create Refund Request", "value"=>"smart_processRefund","condition_var"=>"is_refunded","condition_val"=>0,"condition_src"=>"result"),
	array("display" => "Cancel Subscription", "value"=>"smart_cancelRebill","condition_var"=>"ss_rebill_status","condition_val"=>'active')
	);

$my_sql['pairs']['TestModes'] = array(
	array("display" => "Live", "value"=>"0"),
	array("display" => "Test", "value"=>"1")
	);

/****************
Process and Render Forms
****************/

smart_render_action_results(smart_process_action_form($my_sql['result_actions']),$my_sql['result_actions']['resulttitle']);

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{
	if($export_data)
	{	
		if(in_array($export_data,array('summary'))) $my_sql['skip_query']=true;
		if(in_array($export_data,array('transaction','transaction2'))) {$my_sql['skip_subquery']=true;  $export_subname = '';}
	}
	
	$result = smart_search($my_sql);
	if($export_data)
	{	
	
		foreach($result['rows'] as $krkey=>$keyrow)
			foreach($keyrow as $rowkey=>$row)
				if($row['MerchantData'])
				{
					$md = unserialize($row['MerchantData']);
					foreach($md as $k=>$d) 
					{
						$result['rows'][$krkey][$rowkey][$k]=$d;
						$my_sql['return']["11|".$k] = array("source" => $k,"column"=>$k);
					}
					unset($result['rows'][$krkey][$rowkey]['MerchantData']);
				}		
		ob_clean();
		$filename = 'Export'.$export_subname.'.csv';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		flush();
		
		smart_render_export($result, $my_sql);
		die();
	}
	smart_render_results($result, $my_sql);
}


include("includes/footer.php");
?>
