<?
$etel_debug_mode = 1;

$headerInclude="ledgers";
$periodhead="Ledgers";

require_once("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/smart_search.php');
require_once('../includes/subFunctions/color_manip.php');
require_once("../includes/transaction.class.php");
require_once("../includes/subscription.class.php");
require_once("../includes/calendar.class.php");
require_once("../includes/rebill.class.php");

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

/**************
Define functions to process form
**************/

$rebills = new rebill_class();

/****************
Process Form
****************/


if($_REQUEST['frm_cancel_rebills'] == "1")
{
	$rebills->set_access("admin");
	$rebills->cancel_rebills($_REQUEST['frm_cancel_ids']);
	unset($_REQUEST['frm_cancel_ids']);
	unset($_REQUEST['frm_cancel_rebills']);
	
	$params = $rebills->request_params("frm_");
	
	header("Location: rebillSummary.php?$params");
	exit();
}


/**************
Define pairs
**************/

$my_sql['pairs']['SubAccounts'] = array();
$my_sql['pairs']['SubAccounts'][] = array(
					"display"=>"All",
					"value"=>""
				);
				
$my_sql['pairs']['Rebilling'] = array(
		array("display" => "Either", "value"=>"0","default"=>1),
		array("display" => "Pending", "value"=>"1"),
		array("display" => "Processed", "value"=>"2")
		);


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "50", "value"=>"50"),
	array("display" => "100", "value"=>"100")
	);

$my_sql['pairs']['DisplayRange'] = array(
	array("display" => "Display All", "value"=>"","default"=>1),
	array("display" => "Selected Dates", "value"=>"1")
	);

$my_sql['pairs']['HideDuplicates'] = array(
	array("display" => "Yes", "value"=>"1"),
	array("display" => "No", "value"=>"","default"=>1)
	);

$my_sql['pairs']['SortBy'] = array(
	array("display" => "Name", "value"=>"ss_billing_lastname","default"=>1),
	array("display" => "Reference Number", "value"=>"ss_subscription_id"),
	array("display" => "Amount", "value"=>"ss_rebill_amount"),
	array("display" => "Next Rebill", "value"=>"next_rebill_timestamp")
	);

$my_sql['pairs']['RebillTypes'] = array(
	array("display" => "Active", "value"=>"active","default"=>1),
	array("display" => "Inactive", "value"=>"inactive"),
	array("display" => "Processing", "value"=>"processing")
	);

$my_sql['pairs']['AccountTypes'] = array(
	array("display" => "All", "value"=>"","default"=>1),
	array("display" => "Active", "value"=>"active"),
	array("display" => "Inactive", "value"=>"inactive")
	);
	
$my_sql['pairs']['FrozenStatus'] = array(
	array("display" => "Not Frozen", "value"=>"no","default"=>1),
	array("display" => "Frozen (No Reason)", "value"=>"yes"),
	array("display" => "Frozen (No CVV2)", "value"=>"nocvv2"),
	array("display" => "Frozen (Inactive)", "value"=>"inactive_company")
	);

$my_sql['pairs']['DisplayTypes'] = array(
	array("display" => "Status Summary", "value"=>"1","default"=>1),
	array("display" => "Summary", "value"=>"2","default"=>1),
	array("display" => "Details", "value"=>"4","default"=>1),
	array("display" => "Calendar", "value"=>"8"),
	array("display" => "Charge Types", "value"=>"16")
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

$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50","default"=>1),
	array("display" => "25", "value"=>"25"),
	array("display" => "10", "value"=>"10"),
	array("display" => "100", "value"=>"100"),
	array("display" => "All", "value"=>"1000")
	);

/****************
Define Search Fields and Action Fields
****************/

$my_sql['search']['company_select_by'] = array("input_type" => "select","display" => "Search By");
$my_sql['search']['company_select_by']['options']['source']['pairs'] = "Company_Search_By";

$my_sql['search']['cd_completion'] = array("input_type" => "select","display" => "Completion Status");
$my_sql['search']['cd_completion']['options']['source']['script'] = "smart_getStatus";

$my_sql['search']['transaction_type'] = array("input_type" => "select","display" => "Merchant Type");
$my_sql['search']['transaction_type']['options']['source']['script'] = "smart_getMerchantTypes";

$my_sql['search']['cd_ignore'] = array("input_type" => "checkbox","display" => "Show Ignored Companies","value"=>1);
$my_sql['search']['resellerMarkedUp'] = array("input_type" => "checkbox","display" => "Reseller has Submitted Rates and Fees Markup","value"=>1);
$my_sql['search']['resellerRatesRequest'] = array("input_type" => "checkbox","display" => "Merchant had Requested that Reseller Markup Rates and Fees","value"=>1);
$my_sql['search']['last24h'] = array("input_type" => "checkbox","display" => "Company Joined in the Last 24 Hours","value"=>1);
$my_sql['search']['pay_status'] = array("input_type" => "checkbox","display" => "Company is Payable (deductable by bank).","value"=>1);

$my_sql['onload'] = "func_company_fill();";

$my_sql['search']['company_search'] = array("input_type" => "text","display" => "Search With");
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
	array("name"=>"transaction_type","value"=>"transaction_type","source"=>"form","url_name"=>"tt"),
	array("name"=>"cd_completion","value"=>"cd_completion","source"=>"form","url_name"=>"cp"),
	array("name"=>"cd_ignore","value"=>"cd_ignore","source"=>"form","url_name"=>"ig")
);

if($adminInfo['li_level']=='full')
{
	$my_sql['search']['bank_id'] = array("input_type" => "select","display" => "Bank");
	$my_sql['search']['bank_id']['options']['source']['script'] = "smart_getBanks";

	$my_sql['search']['gateway_id'] = array("input_type" => "select","display" => "Gateway");
	$my_sql['search']['gateway_id']['options']['source']['script'] = "smart_getGateways";
	
	$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'][] = array("name"=>"gateway_id","value"=>"gateway_id","source"=>"form","url_name"=>"gi");
	$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'][] = array("name"=>"bank_id","value"=>"bank_id","source"=>"form","url_name"=>"bi");
}


$my_sql['search']['company_search']['options']['source']['parameters']['search'] = "company_search";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_form_element'] = "user_id";

$my_sql['search']['limit_to'] = array("input_type" => "text","display" => "Limit Result","value"=>100);

$my_sql['search']['user_id'] = array("input_type" => "selectmulti","display" => "Company Name");
$my_sql['search']['user_id']['style'] = array("size"=>10,"style"=>"width: 400px;height: 100px;");

$my_sql['search']['subaccount_name'] = array("input_type" => "text", "display"=>"SubAccount");

$my_sql['search']['ss_rebill_frozen'] = array("input_type" => "checkbox", "display"=>"Frozen Status");
$my_sql['search']['ss_rebill_frozen']['options']['source']['pairs'] = "FrozenStatus";

$my_sql['search']['sort_by'] = array("input_type" => "select", "display"=>"Sort By");
$my_sql['search']['sort_by']['options']['source']['pairs'] = "SortBy";

$my_sql['search']['displayrange'] = array("input_type" => "select","display"=>"Display Range");
$my_sql['search']['displayrange']['options']['source']['pairs'] = "DisplayRange";

$my_sql['search']['tran_date'] = array("input_type" => "date","display"=>"Date","date_format" => "Y-n-j");

$my_sql['search']['account_status'] = array("input_type" => "radio","display"=>"Account Status");
$my_sql['search']['account_status']['options']['source']['pairs'] = "AccountTypes";

$my_sql['search']['rebill_type'] = array("input_type" => "radio","display"=>"Rebill Types");
$my_sql['search']['rebill_type']['options']['source']['pairs'] = "RebillTypes";

$my_sql['search']['display_type'] = array("input_type" => "checkbox","display"=>"Display");
$my_sql['search']['display_type']['options']['source']['pairs'] = "DisplayTypes";

$my_sql['search']['show_banks'] = array("input_type" => "selectmulti","display" => "Rebill Banks");
$my_sql['search']['show_banks']['options']['source']['script'] = "smart_getBanks";

$my_sql['search']['hide_duplicates'] = array("input_type" => "radio","display"=>"Hide Duplicates");
$my_sql['search']['hide_duplicates']['options']['source']['pairs'] = "HideDuplicates";


$my_sql['search']['page_count'] = array("input_type" => "select","display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
	
$my_sql['postpage'] = "rebillSummary.php";
$my_sql['title'] = "Rebill Lookup";

$my_sql['result_actions']['postpage'] = "rebillSummary.php";
$my_sql['result_actions']['title'] = "Rebills Found";

/****************
Process and Render Forms
****************/
require_once('includes/header.php');

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{
	$rebills->set_user($_REQUEST['frm_user_id']);
	$rebills->set_limit_offset($_REQUEST['frm_page_count'],$_REQUEST['frm_page_offset']);
	$rebills->set_site_id($_REQUEST['frm_site']);
	$rebills->set_banks($_REQUEST['frm_show_banks']);
	$rebills->set_sort_by($_REQUEST['frm_sort_by']);
	$rebills->set_hide_dupes($_REQUEST['frm_hide_duplicates']);
	$rebills->set_subaccount_byname($_REQUEST['frm_subaccount_name']);
	$rebills->set_account_status($_REQUEST['frm_account_status']);
	$rebills->set_rebill_status($_REQUEST['frm_rebill_type']);
	$rebills->set_frozen_status($_REQUEST['frm_ss_rebill_frozen']);
	
	if(isset($_REQUEST['frm_displayrange']) && $_REQUEST['frm_displayrange'] == 1)
		$rebills->set_date_range($_REQUEST['frm_tran_date_from'],$_REQUEST['frm_tran_date_to']);
	
	$rebills->get_rebilldetails();
	
	if(isset($_REQUEST['frm_display_type']))
	{
		echo in_array(1,$_REQUEST['frm_display_type']) ? $rebills->render_status_summary() . "<br><br>" : "";
		echo in_array(2,$_REQUEST['frm_display_type']) ? $rebills->render_rebill_summary() . "<br><br>" : "";
		echo in_array(4,$_REQUEST['frm_display_type']) ? $rebills->render_transactions() . "<br><br>" : "";
		echo in_array(16,$_REQUEST['frm_display_type']) ? $rebills->render_pay_details() . "<br><br>" : "";
		echo in_array(8,$_REQUEST['frm_display_type']) ? $rebills->render_calendar(true,$_REQUEST['frm_show_banks']) . "<br><br>" : "";
	}
}


require_once("includes/footer.php");
?>
