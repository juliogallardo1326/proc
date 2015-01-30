<?
$headerInclude="reports";
$periodhead="Ledgers";
require_once('includes/sessioncheckuser.php');
require_once("includes/dbconnection.php");
require_once('includes/function.php');
require_once('includes/subFunctions/smart_search.php');
require_once('includes/subFunctions/color_manip.php');
require_once("includes/transaction.class.php");
require_once("includes/subscription.class.php");
require_once("includes/calendar.class.php");
require_once("includes/rebill.class.php");

$sessionlogin = $companyInfo['userId'];
$companyId = $companyInfo['userId'];
/**************
Define functions to process form
**************/

$rebills = new rebill_class();
$rebills->set_user($sessionlogin);

/****************
Process Form
****************/


if($_POST['frm_cancel_rebills'] == "1")
{
	foreach($_POST['frm_cancel_ids'] as $id)
	{
	
		$trans = new transaction_class(false);
		$trans->pull_subscription( $id,'ss_subscription_id');
		$status = $trans->process_cancel_request(array("actor"=>'Merchant','verifyuserId'=>$companyInfo['userId']));

	}
	unset($_REQUEST['frm_cancel_ids']);
	unset($_REQUEST['frm_cancel_rebills']);
	
	//$params = $rebills->request_params("frm_");
	
	//header("Location: rebillSummary_smart.php?$params");
	//exit();
}
	
$rebill_info = $rebills->get_rebill_info();

/**************
Define pairs
**************/

$my_sql['pairs']['SubAccounts'] = array();
$my_sql['pairs']['SubAccounts'][] = array(
					"display"=>"All",
					"value"=>""
				);
				
foreach($rebill_info as $info)
	$my_sql['pairs']['SubAccounts'][] = array(
						"display"=>$info['rd_subname'],
						"value"=>$info['rd_subaccount']
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


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "50", "value"=>"50"),
	array("display" => "100", "value"=>"100")
	);

$my_sql['pairs']['DisplayRange'] = array(
	array("display" => "Display All", "value"=>""),
	array("display" => "Selected Dates", "value"=>"1","default"=>1)
	);

$my_sql['pairs']['SortBy'] = array(
	array("display" => "Name", "value"=>"ss_billing_lastname","default"=>1),
	array("display" => "Subscription ID", "value"=>"ss_subscription_id"),
	array("display" => "Amount", "value"=>"ss_rebill_amount"),
	array("display" => "Next Rebill", "value"=>"next_rebill_timestamp")
	);

$my_sql['pairs']['RebillTypes'] = array(
	array("display" => "All", "value"=>"","default"=>1),
	array("display" => "Active", "value"=>"active","default"=>1),
	array("display" => "Inactive", "value"=>"inactive")
	//array("display" => "Processing", "value"=>"processing")
	);

$my_sql['pairs']['DisplayTypes'] = array(
	array("display" => "Status Summary", "value"=>"1","default"=>1),
	array("display" => "Summary", "value"=>"2","default"=>1),
	array("display" => "Details", "value"=>"4","default"=>1),
	array("display" => "Calendar", "value"=>"8"),
	array("display" => "Charge Types", "value"=>"16")
	);


/****************
Define Search Fields and Action Fields
****************/

$my_sql['search']['site'] = array("input_type" => "selectmulti", "display"=>"Web Site");
$my_sql['search']['site']['style'] = array("size"=>3,"style"=>"width:250px;");
$my_sql['search']['site']['options']['source']['script'] = "smart_getSites";
$my_sql['search']['site']['options']['source']['parameters']['userid'] = $sessionlogin;

$my_sql['search']['subaccount'] = array("input_type" => "selectmulti", "display"=>"SubAccount");
$my_sql['search']['subaccount']['options']['source']['pairs'] = "SubAccounts";
$my_sql['search']['subaccount']['style'] = array("size"=>3,"style"=>"width:250px;");

$my_sql['search']['sort_by'] = array("input_type" => "select", "display"=>"Sort By");
$my_sql['search']['sort_by']['options']['source']['pairs'] = "SortBy";

$my_sql['search']['displayrange'] = array("input_type" => "select","display"=>"Display Range");
$my_sql['search']['displayrange']['options']['source']['pairs'] = "DisplayRange";

$my_sql['search']['tran_date'] = array("input_type" => "date","display"=>"Date","date_format" => "Y-n-j");

$my_sql['search']['account_status'] = array("input_type" => "radio","display"=>"Membership Status");
$my_sql['search']['account_status']['options']['source']['pairs'] = "AccountTypes";

$my_sql['search']['rebill_type'] = array("input_type" => "radio","display"=>"Rebill Status");
$my_sql['search']['rebill_type']['options']['source']['pairs'] = "RebillTypes";

$my_sql['search']['display_type'] = array("input_type" => "checkbox","display"=>"Display");
$my_sql['search']['display_type']['options']['source']['pairs'] = "DisplayTypes";

$my_sql['search']['page_count'] = array("input_type" => "select","display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['where']['ss_rebill_frozen'] = array("value" => "no", "compare" => "=");

$my_sql['postpage'] = "rebillSummary_smart.php";
$my_sql['title'] = "Rebill Lookup";

$my_sql['result_actions']['postpage'] = "rebillSummary_smart.php";
$my_sql['result_actions']['title'] = "Rebills Found";

/****************
Process and Render Forms
****************/
require_once('includes/header.php');

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{
	$rebills->set_limit_offset($_REQUEST['frm_page_count'],$_REQUEST['frm_page_offset']);
	$rebills->set_site_id($_REQUEST['frm_site']);
	$rebills->set_sort_by($_REQUEST['frm_sort_by']);
	$rebills->set_subaccount($_REQUEST['frm_subaccount']);
	$rebills->set_account_status($_REQUEST['frm_account_status']);
	$rebills->set_rebill_status($_REQUEST['frm_rebill_type']);
	$rebills->set_frozen_status('no');
	if(isset($_REQUEST['frm_displayrange']) && $_REQUEST['frm_displayrange'] == 1)
		$rebills->set_date_range($_REQUEST['frm_tran_date_from'],$_REQUEST['frm_tran_date_to']);
	
	$rebills->get_rebilldetails();
	
	if(isset($_REQUEST['frm_display_type']))
	{
		
		beginTable();
		echo in_array(1,$_REQUEST['frm_display_type']) ? $rebills->render_status_summary() . "<br><br>" : "";
		echo in_array(2,$_REQUEST['frm_display_type']) ? $rebills->render_rebill_summary() . "<br><br>" : "";
		echo in_array(4,$_REQUEST['frm_display_type']) ? $rebills->render_transactions() . "<br><br>" : "";
		echo in_array(16,$_REQUEST['frm_display_type']) ? $rebills->render_pay_details() . "<br><br>" : "";
		echo in_array(8,$_REQUEST['frm_display_type']) ? $rebills->render_calendar() . "<br><br>" : "";
		endTable("Rebill Info",'');
	}
}


require_once("includes/footer.php");
?>
