<?
$pageConfig['Title'] = 'Sales Tracking';
$pageConfig['SubHeader'] = 'affiliates';
include 'includes/sessioncheckuser.php';
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();
include 'includes/header.php';
/****************
Define Search Fields and Action Fields
****************/


$my_sql['tables'] = array("cs_tracking_click AS tc");
$my_sql['joins'] = array(
		array("table"=>"cs_tracking_url ttu",
				"on"=>
					array(
						array("field_a"=>"tc.tc_this_tu_ID","field_b"=>"ttu.tu_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_tracking_host AS tth",
				"on"=>
					array(
						array("field_a"=>"ttu.tu_th_ID","field_b"=>"tth.th_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_tracking_url rtu",
				"on"=>
					array(
						array("field_a"=>"tc.tc_refer_tu_ID","field_b"=>"rtu.tu_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_tracking_host AS rth",
				"on"=>
					array(
						array("field_a"=>"rtu.tu_th_ID","field_b"=>"rth.th_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_tracking_clicker AS tk",
				"on"=>
					array(
						array("field_a"=>"tc.tc_clicker_ID","field_b"=>"tk.tk_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_entities AS aen",
				"on"=>
					array(
						array("field_a"=>"tc.tc_affiliate_ID","field_b"=>"aen.en_ID","compare"=>"=")
					)
				)
		);


$my_sql['return']["00|Click ID"] = array("source" => "tc.tc_ID","column"=>"tc_ID",'hidden'=>1);
$my_sql['return']["01|Time"] = array("source" => "DATE_FORMAT(tc.tc_time,'%m-%d %l:%i:%s %p') as tc_time_formatted","column"=>"tc_time_formatted");
$my_sql['return']["02|Clicker IP"] = array("source" => "INET_NTOA(tk.tk_IP) as clicker_IP_address","column"=>"clicker_IP_address");
$my_sql['return']["03|URL Path"] = array("source" => "ttu.tu_URL as url_path","column"=>"url_path",
	"disp_clip"=>array('overflow'=>true,'h'=>'34px','w'=>'200px'));
$my_sql['return']["04|Refer Site"] = array("source" => "rth.th_host as refer_url_path","column"=>"refer_url_path",
	"disp_clip"=>array('overflow'=>true,'h'=>'34px','w'=>'100px'));
$my_sql['return']["05|Affiliate"] = array("source" => "aen.en_company as affiliate_company","column"=>"affiliate_company");
$my_sql['return']["05|Affiliate Ref"] = array("source" => "aen.en_ref as affiliate_ref","column"=>"affiliate_ref","hidden"=>1);

$my_sql['orderby'] = array("tc_time desc");
$my_sql['user_orderby']['tc_time_formatted'] = "tc_time_formatted";


//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_clicks",
						"max_offset_source"=>"result");

$my_sql['search']['tc.tc_en_ID'] = array("input_type" => "hidden", "compare"=> "=","required"=>1,"value" => $curUserInfo['en_ID']);

$my_sql['search']['tc.tc_time'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-n-j");

$my_sql['search']['aen.en_ref'] = array("input_type" => "select", "compare"=> "=","required"=>0,"display"=>"Affiliates");
$my_sql['search']['aen.en_ref']['options']['source']['pairs'] = "Affiliate";

$my_sql['search']['rth.th_host'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display" => "Referal Website");
$my_sql['search']['rth.th_host']['options']['source']['pairs'] = "Hosts";
$my_sql['search']['rth.th_host']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['sql_config'] = array('TimeOut'=>10);
if($export_data)
{
	unset($my_sql['return']);
	//$my_sql['return'] = array(
	//	"10|Reference Id"=> array("source" => "td.reference_number as ReferenceID","column"=>"ReferenceID"),
	//	);
	//if(in_array($export_data,array('full','transaction','transaction2')))
	//	$my_sql['limit']['forcelimit']=30000;
		
	$my_sql['sql_config'] = array('TimeOut'=>30);
		
}


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
	}
}
$my_sql['subquery']['title'] = "Tracking Summary";

$my_sql['subquery']['queries']['01|Clicks'] = array("name"=>"number_clicks", "source" => "COUNT(tc.tc_ID)");
$my_sql['subquery']['queries']['02|Affiliate Clicks'] = array("name"=>"affiliate_clicks", "source" => "COUNT(tc_affiliate_ID)");

$my_sql['postpage'] = "tracking_Smart.php";
$my_sql['title'] = "Search Tracking";

$my_sql['result_actions']['postpage'] = "tracking_Smart.php";
$my_sql['result_actions']['title'] = "Tracking Information";
$my_sql['result_actions']['resulttitle'] = "Tracking Processed";

$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "100", "value"=>"100"),
	array("display" => "300", "value"=>"300")
	);
	
$my_sql['pairs']['Affiliate'] = array(
	array("display" => "Any Click", "value"=>NULL),
	array("display" => "Ari", "value"=>"3297BB9F")
	);
	
$my_sql['pairs']['Hosts'] = array(
	array("display" => "Any Host", "value"=>NULL)
	);
	
$sql = "SELECT distinct rth.th_host
FROM cs_tracking_click AS tc LEFT JOIN cs_tracking_url rtu ON (tc.tc_refer_tu_ID = rtu.tu_ID) LEFT JOIN cs_tracking_host AS rth ON (rtu.tu_th_ID = rth.th_ID) WHERE (tc.tc_en_ID = '".$curUserInfo['en_ID']."') order by th_host desc";
$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
while($host = mysql_fetch_assoc($result))
	$my_sql['pairs']['Hosts'][] = array("display" => etel_format_variable($host['th_host']), "value"=>$host['th_host']);
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
