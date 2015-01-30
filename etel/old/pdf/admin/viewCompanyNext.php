<?php

$allowBank=true;
include("includes/sessioncheck.php");
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();

$headerInclude = "companies";
include("includes/header.php");
include_once "../includes/completion.php";
require_once('../includes/subFunctions/smart_search.php');
require_once("../includes/transaction.class.php");
require_once("../includes/companySubView.php");
require_once("../includes/JSON_functions.php");

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$level = 'minimal';
if($adminInfo['li_level']=='full') $level = 'full';
if($adminInfo['username']=='etel1') {$_GET['showall']=1;$level = 'medium';}


$my_sql['tables'] = array("cs_companydetails AS cd");

$my_sql['subquery']['title'] = "Transaction Summary";
$my_sql['subquery']['queries']['01|Total Companys'] = array("name"=>"total_companys", "source" => "count(userId)");
$my_sql['subquery']['queries']['02|Live Companys'] = array("name"=>"active_companys", "source" => "sum(activeuser=1)");
$my_sql['subquery']['queries']['03|Payable Companys'] = array("name"=>"payable_companys", "source" => "sum(cd_pay_status='payable')");

$my_sql['return']["00|Company ID"] = array("source" => "cd.userid","column"=>"userid","hidden"=>1);
$my_sql['return']["00|Ref ID"] = array("source" => "cd.ReferenceNumber","column"=>"ReferenceNumber");
$my_sql['return']["01|Company Name"] = array("source" => "cd.companyname","column"=>"companyname","crop"=>25);
$my_sql['return']["01|Company Name"]["link"]["destination"] = "editCompanyProfileAccess.php";
$my_sql['return']["01|Company Name"]["link"]["parameters"] = array(array("name"=>"company_id","value"=>"userid","source"=>"result"));

$my_sql['return']["02|Sites"] = array("source" => "(select count(cs_ID) as sites from cs_company_sites AS cs where cs_company_id = cd.userId group by cs_company_id) as sites","column"=>"sites");
$my_sql['return']["02|Sites"]["link"]["destination"] = "confirmWebsite.php";
$my_sql['return']["02|Sites"]["link"]["parameters"] = array(
array("name"=>"search","value"=>"userid","source"=>"result"),
array("name"=>"searchby","value"=>"id"),
array("name"=>"cd_view","value"=>"AL")
);

$my_sql['return']["03|Docs"] = array("source" => "(select count(distinct file_type) as docs from cs_uploaded_documents AS ud where ud.user_Id = cd.userId group by ud.user_Id) as docs","column"=>"docs");
$my_sql['return']["03|Docs"]["link"]["destination"] = "confirmUploads.php";
$my_sql['return']["03|Docs"]["link"]["parameters"] = array(
array("name"=>"search","value"=>"userid","source"=>"result"),
array("name"=>"searchby","value"=>"id"),
array("name"=>"cd_view","value"=>"AL")
);

$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"total_companys",
						"max_offset_source"=>"result");
						
$my_sql['sql_config'] = array('TimeOut'=>10);
if($export_data)
{
	if(in_array($export_data,array('full','company','company2')))
	{
		$my_sql['limit']['forcelimit']=30000;
		
		if(in_array($export_data,array('company2')))
		{
			$my_sql['return']["02|SiteInfo"] = array("source" => "(select group_concat(distinct `cs_name` SEPARATOR ', ') as site_info from cs_company_sites AS cs where cs_company_id = cd.userId group by cs_company_id) as site_info","column"=>"site_info");
			$my_sql['return']["03|Submitted Docs"] = array("source" => "(select group_concat(distinct file_type SEPARATOR ', ') as doc_info from cs_uploaded_documents AS ud where ud.user_Id = cd.userId group by ud.user_Id) as doc_info","column"=>"doc_info");
			$my_sql['return']["10|Username"] = array("source" => "cd.username","column"=>"username");
			$my_sql['return']["10|Password"] = array("source" => "cd.password","column"=>"password");
			$my_sql['return']["10|Phonenumber"] = array("source" => "cd.phonenumber","column"=>"phonenumber");
			$my_sql['return']["10|Address"] = array("source" => "cd.address","column"=>"address");
			$my_sql['return']["10|City"] = array("source" => "cd.city","column"=>"city");
			$my_sql['return']["10|State"] = array("source" => "cd.state","column"=>"state");
			$my_sql['return']["10|Country"] = array("source" => "cd.country","column"=>"country");
			$my_sql['return']["10|Zipcode"] = array("source" => "cd.zipcode","column"=>"zipcode");
			$my_sql['return']["10|General Email"] = array("source" => "cd.email","column"=>"email");
			$my_sql['return']["10|Contact Name"] = array("source" => "cd.contactname","column"=>"contactname");
			$my_sql['return']["10|First Name"] = array("source" => "cd.first_name","column"=>"first_name");
			$my_sql['return']["10|Family Name"] = array("source" => "cd.family_name","column"=>"family_name");
			$my_sql['return']["10|Job Title"] = array("source" => "cd.job_title","column"=>"job_title");
			$my_sql['return']["10|Contact Email"] = array("source" => "cd.contact_email","column"=>"contact_email");
			$my_sql['return']["10|Contact Phone"] = array("source" => "cd.contact_phone","column"=>"contact_phone");
			$my_sql['return']["10|Time Zone"] = array("source" => "cd.cd_timezone","column"=>"cd_timezone");
			$my_sql['return']["10|Instant Messenger"] = array("source" => "cd.cd_contact_im","column"=>"cd_contact_im");
		}
	}
	$my_sql['sql_config'] = array('TimeOut'=>30);
	
}


$detail = intval($_REQUEST['frm_subquery_detail']);
$subquery_group = quote_smart($_REQUEST['frm_subquery_group']);
if($subquery_group) 
{
	$my_sql['subgroupby'] = "subgroup_by";
	$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
	$my_sql['subrollup'] = true;
	$my_sql['subgrouprolluptitle'] = "CONCAT('Total - ',daterange)";
	
	switch($subquery_group)
	{
		case 'M':
			$my_sql['subgroupby'] = "cd.transaction_type";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',cd.transaction_type)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',total_companys,') Total Companys')";
			$my_sql['suborderby'] = "is_rollup desc";
			$export_subname.="ByMerchantType";
			$detail=1;
			break;
		case 'G':
			$my_sql['subgroupby'] = "cd.gateway_id";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',(select gw_title from etel_gateways where gw_id =  cd.gateway_id))";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',total_companys,') Total Companys')";
			$my_sql['suborderby'] = "is_rollup desc";
			$export_subname.="ByGateway";
			break;
	}
}
$my_sql['subquery']['title'] = "Company Listing";


$my_sql['return']["04|Signed Up"] = array("source" => "Date_Format(date_added,'%m-%d-%y') as date_added","column"=>"date_added");
$my_sql['return']["06|Source"] = array("source" => "concat(how_about_us,'/',reseller_other) as source","column"=>"source");

$my_sql['orderby'] = array("cd.userId desc");
$my_sql['groupby'] = array("cd.userId");
$my_sql['user_orderby']['companyname'] = "companyname";
$my_sql['user_orderby']['source'] = "source";
$my_sql['user_orderby']['date_added'] = "date_added";
$my_sql['user_orderby']['url1'] = "url1";
$my_sql['user_orderby']['sites'] = "sites";
$my_sql['user_orderby']['docs'] = "docs";
$my_sql['user_orderby']['ReferenceNumber'] = "ReferenceNumber";


//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
						
$my_sql['search']['cd.userId'] = array("input_type" => "company_search", "compare"=> "IN","required"=>0,"display" => "Company Name");

if($_REQUEST['userIdList']) $_REQUEST['companyname'] = explode('|',$_REQUEST['userIdList']);

if($_REQUEST['companyname'][0]=='AL')
{
	$sql_info = JSON_getCompanyInfo_build($_REQUEST);
	$company_table_sql = "Select cd.* from ". $sql_info['sql_from']." Where ".$sql_info['sql_where'];
	$my_sql['tables'] = array("($company_table_sql) as cd ");
	
	$my_sql['posts_not_required'] = 1;
	$_REQUEST['companyname'] = NULL;
}
else
if($_REQUEST['companyname'][0]=='A')
{
	$my_sql['posts_not_required'] = 1;
	$_REQUEST['companyname'] = NULL;
	$my_sql['where']['cd_ignore'] = array("value" => "0", "compare" => "=");
}

$_REQUEST['frm_cd_userId'] = $_REQUEST['companyname'];

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";

$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

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


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "100", "value"=>"100")
	);


$my_sql['pairs']['SubGroupTypes'] = array(
	array("display" => "All", "value"=>"0"),
	array("display" => "By Merchant Type", "value"=>"M"),
	array("display" => "By Gateway", "value"=>"G")
	);

$my_sql['pairs']['SubGroupDetails'] = array(
	array("display" => "Full", "value"=>"2"),
	array("display" => "Minimal", "value"=>"1"),
	array("display" => "None", "value"=>"0")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['ExportDetails'] = array(
	array("display" => "Disabled", "value"=>""),
	array("display" => "Full Export", "value"=>"full"),
	array("display" => "Summary", "value"=>"summary"),
	array("display" => "Company Data", "value"=>"company"),
	array("display" => "Company Data (Extended)", "value"=>"company2")
	//array("display" => "Extended", "value"=>"4")
	);
	
//and (td.td_non_unique=0 or status!='D')
$my_sql['subquery']['title'] = "Company Info";

$my_sql['postpage'] = "viewCompanyNext.php";
$my_sql['title'] = "Find Companys";

$my_sql['result_actions']['postpage'] = "viewCompanyNext.php";
$my_sql['result_actions']['title'] = "Companys Found";

	
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
		if(in_array($export_data,array('company','company2'))) {$my_sql['skip_subquery']=true;  $export_subname = '';}
	}
	
	$result = smart_search($my_sql);
	
	if($export_data)
	{	
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