<?
$etel_debug_mode = 0;
$headerInclude="risk_smart";

include("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/risk_report.php');
require_once('../includes/subFunctions/smart_search.php');

//include_once("../includes/subFunctions/risk_install.php");
//$install = new risk_report_install();

include("includes/header.php");

/****************
Define Search Fields and Action Fields
****************/

$my_sql['tables'] = array("cs_risk_cron AS r");
$my_sql['joins'] = array(
		array("table"=>"cs_companydetails AS c",
				"on"=>
					array(
						array("field_a"=>"r.rc_company_id","field_b"=>"c.userId","compare"=>"=")
					)
				)
		);

$my_sql['return']["00|Report Unix Time"] = array("source" => "r.rc_date_time","column"=>"rc_date_time", "hidden"=>1);
$my_sql['return']["00|Company Id"] = array("source" => "r.rc_company_id","column"=>"rc_company_id", "hidden"=>1);
$my_sql['return']["01|Report Date"] = array("source" => "FROM_UNIXTIME(r.rc_date_time,'%M %D %Y') AS report_date","column"=>"report_date");

$my_sql['return']["02|Company Name"] = array("source" => "c.companyname","column"=>"companyname","crop"=>30);
$my_sql['return']["02|Company Name"]["link"]["popup"] = array("script"=>"popUp");
$my_sql['return']["02|Company Name"]["link"]["destination"] = "risk_profile.php";
$my_sql['return']["02|Company Name"]["link"]["parameters"] = array(
			array("name"=>"company_id","value"=>"rc_company_id","source"=>"result"),
			array("name"=>"custom_report","value"=>$_POST['frm_custom_report'],"source"=>"given")
			);

$my_sql['return']["03|Risk Value"] = array("source" => "r.rc_risk_value","column"=>"rc_risk_value");

$my_sql['orderby'] = array("rc_risk_value DESC");
//$my_sql['user_orderby']['companyname'] = "companyname";

//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_reports",
						"max_offset_source"=>"result");

$my_sql['search']['company_select_by'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Search By");
$my_sql['search']['company_select_by']['options']['source']['pairs'] = "Company_Search_By";

$my_sql['search']['cd_ignore'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Show Ignored Companies","value"=>1);

$my_sql['onload'] = "func_company_fill();";

$my_sql['search']['company_search'] = array("input_type" => "text", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Search With");
$my_sql['search']['company_search']['options']['source']['ajax'] = "smart_AJAX_company_search";
$my_sql['search']['company_search']['options']['source']['parameters']['on_action'] = "onKeyUp";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_call'] = "func_company_fill";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_response'] = "func_company_fill_response";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['location'] = "/admin/admin_JOSN.php";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'] = array(

	array("name"=>"func","value"=>"getCompanyInfo","source"=>"given","url_name"=>"func"),
	array("name"=>"company_search","value"=>"company_search","source"=>"form","url_name"=>"search"),
	array("name"=>"company_select_by","value"=>"company_select_by","source"=>"form","url_name"=>"searchby"),
	array("name"=>"limit_to","value"=>"limit_to","source"=>"form","url_name"=>"limit_to"),
	array("name"=>"cd_ignore","value"=>"cd_ignore","source"=>"form","url_name"=>"ig")
);

if($adminInfo['li_level']=='full')
{
	$my_sql['search']['bank_id'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Bank");
	$my_sql['search']['bank_id']['options']['source']['script'] = "smart_getBanks";

	$my_sql['search']['gateway_id'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Gateway");
	$my_sql['search']['gateway_id']['options']['source']['script'] = "smart_getGateways";
	
	$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'][] = array("name"=>"gateway_id","value"=>"gateway_id","source"=>"form","url_name"=>"gi");
	$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'][] = array("name"=>"bank_id","value"=>"bank_id","source"=>"form","url_name"=>"bi");
}


$my_sql['search']['company_search']['options']['source']['parameters']['search'] = "company_search";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_form_element'] = "r.rc_company_id";

$my_sql['search']['limit_to'] = array("input_type" => "text", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Limit Result","value"=>100);

$my_sql['search']['r.rc_company_id'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display" => "Company Name");
$my_sql['search']['r.rc_company_id']['style'] = array("size"=>10,"style"=>"width: 400px;height: 100px;");

$my_sql['search']['rc_risk_value'] = array("input_type" => "text", "compare"=> ">=","required"=>0,"in_query"=>true,"display" => "Risk Value >=","value"=>0);

$my_sql['search']['custom_report'] = array("input_type" => "select", "in_query" => false,"required"=>0,"display"=>"Report Display");
$my_sql['search']['custom_report']['style'] = array("size"=>1);
$my_sql['search']['custom_report']['options']['source']['script'] = "smart_getCustomReports";


$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['subquery']['title'] = "Report Summary";
$my_sql['subquery']['queries']['02|Reports'] = array("name"=>"number_reports", "source" => "COUNT(r.rc_company_id)","hidden"=>0);

$my_sql['result_actions']['title'] = "Reports Found";

$my_sql['postpage'] = "risk_smart.php";
$my_sql['title'] = "Report Lookup";


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "25", "value"=>"25"),
	array("display" => "10", "value"=>"10"),
	array("display" => "100", "value"=>"100"),
	array("display" => "All", "value"=>"1000")
	);


$my_sql['pairs']['Company_Search_By'] = array(
	array("display" => "Company Name", "value"=>"cn"),
	array("display" => "Reference ID", "value"=>"ri"),
	array("display" => "Login UserName", "value"=>"un"),
	array("display" => "Contact Email", "value"=>"em"),
	array("display" => "Website Name", "value"=>"wn"),
	array("display" => "Website Reference ID", "value"=>"wr"),
	array("display" => "Merchant ID (List)", "value"=>"id")
);
	
/****************
Process and Render Forms
****************/

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{
	$results = smart_search($my_sql);

	$company_ids = array();	
	foreach($results['rows'] as $key => $values)
		foreach($values as $index => $value)
			$company_ids[] = $value['rc_company_id'];

	$sql_limit = $my_sql['limit'];

	unset($my_sql['limit']);
	unset($my_sql['return']);
	unset($my_sql['subquery']);
	
	$my_sql['return']["00|Company Id"] = array("source" => "r.rc_company_id","column"=>"rc_company_id", "hidden"=>1);

	$results = smart_search($my_sql,false);
	foreach($results['rows'] as $key => $values)
		foreach($values as $index => $value)
			$all_company_ids[] = $value['rc_company_id'];


	$_SESSION['summary_company_id'] = $all_company_ids;
	$_SESSION['custom_report'] = $_POST['frm_custom_report'];
	
	beginTable();
	echo sizeof($all_company_ids) . " companies in summary.<br>";
?>
	<iframe src="risk_summary.php" width='500px' height='400px' frameborder="0"></iframe>
<?

	endTable("Report Summary");
	
	
	beginTable();
		$curr_offset = $_POST["frm_" . $sql_limit['offset_source']];
		$max_pages = sizeof($all_company_ids);
		$per_page = $_POST["frm_" . $sql_limit['count_source']];
		$nav_data='';
?>
	<script>
	function submit_page_form(dir)
	{
		var old_offset = <?=(isset($_POST["frm_" . $sql_limit['offset_source']]) ? $_POST["frm_" . $sql_limit['offset_source']] : 0)?>;
		var new_offset = parseInt(dir);
		if(new_offset < 0) new_offset = 0;
		if(new_offset > <?=$max_pages?>) new_offset = <?=$max_pages-$per_page?>;
		if(new_offset < 0) new_offset = 0;

		document.getElementById("<?="frm_" . $sql_limit['offset_source']?>").value = new_offset;	
		document.getElementById("search_form").submit();
	}
	</script>
<?
		if($max_pages > 0)
		{
			$size = 16;
			$this_page = floor($curr_offset/$per_page);
			$nav_data .= "Displaying Results " . ($curr_offset+1) . " - " . (($curr_offset + $per_page) <= $max_pages ? $curr_offset + $per_page : $max_pages) . " of $max_pages in ".ceil($max_pages/$per_page)." pages.<br>";
			$pages = array();
			for($j=0;$j<ceil($max_pages/$per_page);$j++)
				$pages[$j] = $j * $per_page;
			
			if($this_page > 0)
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . ($this_page-1) * $per_page . ");\"><u>Prev</u></a>";
					
			$m = ceil($max_pages/$per_page);
			if($m > 1)
			{
				if($this_page > 0)
					$nav_data .=  "&nbsp;|&nbsp;";
				$startnum = ($this_page < intval($size/2)?0:$this_page-intval($size/2));
				$finishnum = ($this_page > $m-intval($size/2)?$m:$this_page+intval($size/2));
				
				for($j=$startnum;$j<$finishnum;$j++)
				{
					if($this_page != $j)
						$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . $pages[$j] . ");\"><u>" . ($j+1) . "</u></a>";
					else
						$nav_data .=  "<b>" . ($j+1) . "</b>";
					if($j<$finishnum-1)
						$nav_data .=  "&nbsp;|&nbsp;";
				}
			}
			
			if($this_page < $m - 1)
			{
				if($this_page > 0 || $m > 1)
					$nav_data .=  "&nbsp;|&nbsp;";
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . ($this_page+1) * $per_page . ");\"><u>Next</u></a>";
			}	
				
			if($this_page > 0 || $m > 1)
			{	
				$nav_data .=  "&nbsp;|&nbsp;";
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(parseInt(".'$F'."('goto_field')-1)*parseInt(" .  $per_page . "));\"><u>Goto</u></a>&nbsp;<input type='text' style='width:20px;height:20px;' id='goto_field' />";
			}
			echo $nav_data;
		}			

		$_SESSION['company_id'] = $company_ids;
?>
<iframe src="risk_profile.php" width='500px' height='400px' frameborder="0"></iframe>
<?	
	endTable("Company Reports");
}


include("includes/footer.php");

?>
