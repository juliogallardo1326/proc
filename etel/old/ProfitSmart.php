<?
$headerInclude="reports";
$periodhead="Ledgers";
$pageConfig['Title'] = 'Profit Breakdown';
include 'includes/sessioncheckuser.php';
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();
include 'includes/header.php';
require_once('includes/subFunctions/smart_search.php');
require_once("includes/transaction.class.php");


/**************
Define functions to process form
**************/

/****************
Define Search Fields and Action Fields
****************/


$display_rebill_info = isset($_REQUEST['frm_td_td_enable_rebill']) ? $_REQUEST['frm_td_td_enable_rebill'] : "frm_td_td_enable_rebill";
if(is_array($display_rebill_info))
	$display_rebill_info = in_array("1",$display_rebill_info) && !in_array("0",$display_rebill_info);

$my_sql['tables'] = array("(
select 
	`pt_amount`, `pt_date_effective`, `pt_action_ID`, `pt_type`
from 
	cs_profit 
where 
	pt_to_entity_ID='".$curUserInfo['en_ID']."' 
union 
select 
	-`pt_amount`, `pt_date_effective`, `pt_action_ID`, `pt_type` 
from 
	cs_profit 
where 
	pt_from_entity_ID='".$curUserInfo['en_ID']."'  
) AS pt");
$my_sql['joins'] = array(
		array("table"=>"cs_profit_action AS pa",
				"on"=>
					array(
						array("field_a"=>"pt.pt_action_ID","field_b"=>"pa.pa_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_transactiondetails AS td",
				"on"=>
					array(
						array("field_a"=>"td.transactionId","field_b"=>"pa.pa_trans_ID","compare"=>"=")
					)
				)
		);

$my_sql['return']["01|Amount"] = array("source" => "CONCAT('$',round(pt.pt_amount,2)) as pt_amount","column"=>"pt_amount");
$my_sql['return']["02|Type"] = array("source" => "pt.pt_type","column"=>"pt_type");
$my_sql['return']["03|Effective Date"] = array("source" => "DATE_FORMAT(pt.pt_date_effective,'%m/%d/%Y') as pt_date_effective","column"=>"pt_date_effective");
$my_sql['return']["04|Transaction Date"] = array("source" => "DATE_FORMAT(td.transactionDate,'%m/%d/%Y') as transactionDate","column"=>"transactionDate");
$my_sql['return']["05|Source"] = array("source" => "if(td.reference_number is not null,
CONCAT('<a href=\"viewTransaction.php?ref=',reference_number,'\">',reference_number,'</a>')
,pa.pa_desc) as source","column"=>"source",
	"disp_clip"=>array('overflow'=>true,'w'=>'150px','h'=>'16px')
	);

$my_sql['orderby'] = array("pa_ID desc");
//$my_sql['key']["pa_desc"] = array("display" => "");

$my_sql['user_orderby']['txt_amount'] = "pt_amount";
$my_sql['user_orderby']['status'] = "status";
$my_sql['user_orderby']['full_name'] = "full_name";
$my_sql['user_orderby']['cs_name'] = "cs_name";
$my_sql['user_orderby']['transaction_date_formatted'] = "transaction_date_formatted";


//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_transactions",
						"max_offset_source"=>"result");
						
$my_sql['search']['pt.pt_type'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display"=>"Type");
$my_sql['search']['pt.pt_type']['style'] = array("size"=>3);
$my_sql['search']['pt.pt_type']['options']['source']['pairs'] = "ProfitType";
$my_sql['search']['pt.pt_type']['style'] = array("size"=>10,
		"style"=>"width: 250px;height: 140px;",
		"onfocus"=>'this.style.height=150;'
);

$my_sql['search']['pt.pt_date_effective'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-m-d");

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";

$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

//$my_sql['where']['1'] = array("value" => "1 and '".$curUserInfo['en_ID']."' in (pt_from_entity_ID,pt_to_entity_ID) ", "compare" => "=");
//and (td.td_non_unique=0 or status!='D')


	$my_sql['subquery']['queries']['01|Count'] = array("name"=>"total_num", "source" => "COUNT(*)","hidden"=>1);
	$my_sql['subquery']['queries']['02|Total'] = array("name"=>"total_earned", "source" => "CONCAT('\$',FORMAT(SUM(pt.pt_amount),2),' (' , COUNT(*), ')')");
	$my_sql['subquery']['queries']['03|Total Revenue'] = array("name"=>"revenue", "source" => "CONCAT('\$',FORMAT(SUM(if(pt.pt_amount > 0,pt.pt_amount,0)),2),' (' ,SUM(if(pt.pt_amount > 0,1,0)) , ')')");
	$my_sql['subquery']['queries']['04|Total Deductions'] = array("name"=>"deductions", "source" => "CONCAT('\$',FORMAT(SUM(if(pt.pt_amount < 0,pt.pt_amount,0)),2),' (' ,SUM(if(pt.pt_amount < 0,1,0)) , ')')");


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "100", "value"=>"100"),
	array("display" => "All", "value"=>"1000000")
	);
	
$my_sql['pairs']['DisplayRange'] = array(
	array("display" => "Display All", "value"=>"","default"=>1),
	array("display" => "Selected Dates", "value"=>"1")
	);
	
$my_sql['pairs']['ProfitType'] = array(
 	//array("display" => "Bank Chargeback Fee", "value"=>"Bank Chargeback Fee"),
	//array("display" => "Bank Customer Fee", "value"=>"Bank Customer Fee"),
	//array("display" => "Bank Discount Fee", "value"=>"Bank Discount Fee"),
	//array("display" => "Bank Refund Fee", "value"=>"Bank Refund Fee"),
	//array("display" => "Bank Refund/CB Amount", "value"=>"Bank Refund/CB Amount"),
	//array("display" => "Bank Sale Funds", "value"=>"Bank Sale Funds"),
	//array("display" => "Bank Transaction Fee", "value"=>"Bank Transaction Fee"),
	//array("display" => "Bank Reserve Release", "value"=>"Bank Reserve Release"),
	array("display" => "Chargeback Fee", "value"=>"Chargeback Fee"),
	array("display" => "Discount Fee", "value"=>"Discount Fee"),
	array("display" => "Refund Fee", "value"=>"Refund Fee"),
	array("display" => "Refund/CB Amount", "value"=>"Refund/CB Amount"),
	array("display" => "Reserve Release", "value"=>"Reserve Release"),
	array("display" => "Sale Funds", "value"=>"Sale Funds"),
	array("display" => "Transaction Fee", "value"=>"Transaction Fee"),
	array("display" => "Payout", "value"=>"Payout"),
	array("display" => "Adjustment", "value"=>"Adjustment"),
	array("display" => "Monthly Fee", "value"=>"Monthly Fee"),
	array("display" => "Setup Fee", "value"=>"Setup Fee"),
	array("display" => "Funds Transfer Fee", "value"=>"Funds Transfer Fee"),
	array("display" => "Bank Withheld", "value"=>"Bank Withheld"),
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
	array("display" => "By Transaction", "value"=>"T"),
	array("display" => "By Invoice", "value"=>"I")
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

if($_REQUEST['showdate'])
{
	$_REQUEST['frm_pt_pt_date_effective_from'] = $_REQUEST['showdate'];
	$_REQUEST['frm_pt_pt_date_effective_to'] = $_REQUEST['showdate'];	
}
if($_REQUEST['hideprofit'])
{
	foreach($my_sql['pairs']['ProfitType'] as $type)
		if($type['value'] != 'Payout') $_REQUEST['frm_pt_pt_type'][] = $type['value'];
}
if(is_array($_REQUEST['frm_pt_pt_type']))
	foreach($my_sql['pairs']['ProfitType'] as $type)
		if(in_array($type['value'],$_REQUEST['frm_pt_pt_type']))
			$my_sql['subquery']['queries']['99|'.ucfirst($type['value'])] = array("name"=>preg_replace('/[^a-zA-Z]/','',$type['value']), "source" => "CONCAT('\$',FORMAT(SUM(if(pt.pt_type='".$type['value']."',pt.pt_amount,0)),2),' (' ,SUM(if(pt.pt_type='".$type['value']."',1,0)) , ')')");

$subquery_group = quote_smart($_REQUEST['frm_subquery_group']);
$export_subname="";
if($subquery_group) 
{
	$my_sql['subgroupby'] = array("subgroup_by");
	$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
	$my_sql['subrollup'] = true;
	$my_sql['subgrouprolluptitle'] = "CONCAT('(',total_num,') Total - ',daterange)";
	$sql_date_range = "' From ', DATE_FORMAT(min(pt_date_effective),'%b %D'),' To ', DATE_FORMAT(max(pt_date_effective),'%b %D'),'  (',(TO_DAYS(max(pt_date_effective))-TO_DAYS(min(pt_date_effective))+1),' Days)'";
	$my_sql['subquery']['queries']['00|Range'] = array("name"=>"daterange", "source" => "CONCAT($sql_date_range)",'hidden'=>1);
	switch($subquery_group)
	{		
		case 'I':
			$my_sql['subgroupby'] = "pt.pt_action_ID";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',pa.pa_desc)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('Total for all Invoices')";
			$my_sql['suborderby'] = "is_rollup desc, total_num desc";
			$export_subname.="ByInvoice";
			if($export_data)
			{
			}
			break;	
		case 'T':
			$my_sql['subgroupby'] = "pa.pa_trans_ID";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',pa.pa_desc)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('Total for all Transactions')";
			$my_sql['suborderby'] = "is_rollup desc, total_num desc";
			$export_subname.="ByTransaction";
			if($export_data)
			{
			}
			break;	
		case 1:
			$my_sql['subgroupby'] = "DATE_FORMAT( pt_date_effective , '%y-%m-%d' )";
			$my_sql['subgrouptitle'] = "DATE_FORMAT( pt_date_effective , '%W, %M %D %Y' ) ";
			$export_subname.="ByDay";
			break;
		case 7:
			$my_sql['subgroupby'] = "DATE_FORMAT( pt_date_effective , '%y-%U' )";
			$my_sql['subgrouptitle'] = "CONCAT(DATE_FORMAT( pt_date_effective , '%M, Week #%U -'),$sql_date_range ) ";
			$export_subname.="ByWeek";
			break;
		case 30:
			$my_sql['subgroupby'] = "DATE_FORMAT( pt_date_effective , '%y-%M' ) ";
			$my_sql['subgrouptitle'] = "CONCAT( DATE_FORMAT( pt_date_effective , '%M -' ),$sql_date_range) ";
			$export_subname.="ByMonth";
			break;
		case 60:
		case 90:
		case 180:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "ROUND(MONTH( pt_date_effective )/".intval($subquery_group/30).") ";
			$my_sql['subgrouptitle'] = "CONCAT($sql_date_range )";
			$export_subname.="By".$subquery_group."Days";
			break;
		case 360:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "DATE_FORMAT( pt_date_effective , '%Y' )";
			$my_sql['subgrouptitle'] = "CONCAT(DATE_FORMAT( pt_date_effective , '%Y -' ),$sql_date_range)";
			$export_subname.="ByYear";
			break;
		default:
			$subquery_group = intval($subquery_group);
			$my_sql['subgroupby'] = "FLOOR(TO_DAYS(pt_date_effective)/($subquery_group))";
			$my_sql['subgrouptitle'] = "CONCAT($sql_date_range )";
			$export_subname.="By".$subquery_group."Days";
			break;
	}
}

/****************
Process and Render Forms
****************/

$my_sql['postpage'] = "ProfitSmart.php";
$my_sql['title'] = "Profit Breakdown";

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
