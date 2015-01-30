<?


$headerInclude="ledgers";
$periodhead="Ledgers";

require_once("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once("../includes/integration.php");
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


if($_REQUEST['frm_process_rebills'] == "1")
{
	
	echo $rebills->rebill_subscriptions($_REQUEST['frm_process_ids']);
	exit();
	
	unset($_REQUEST['frm_process_ids']);
	unset($_REQUEST['frm_process_rebills']);

	$params = $rebills->request_params("frm_");
	header("Location: " . $_SERVER['PHP_SELF'] . "?$params");
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
	array("display" => "Yes", "value"=>"1","default"=>1),
	array("display" => "No", "value"=>"")
	);

$my_sql['pairs']['SortBy'] = array(
	array("display" => "Name", "value"=>"ss_billing_lastname","default"=>1),
	array("display" => "Reference Number", "value"=>"ss_subscription_id"),
	array("display" => "Amount", "value"=>"ss_rebill_amount"),
	array("display" => "Next Rebill", "value"=>"ss_rebill_next_date")
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


$my_sql['search']['limit_to'] = array("input_type" => "text","display" => "Limit Result","value"=>100);


$my_sql['search']['sort_by'] = array("input_type" => "select", "display"=>"Sort By");
$my_sql['search']['sort_by']['options']['source']['pairs'] = "SortBy";

$my_sql['search']['displayrange'] = array("input_type" => "select","display"=>"Display Range");
$my_sql['search']['displayrange']['options']['source']['pairs'] = "DisplayRange";

$my_sql['search']['tran_date'] = array("input_type" => "date","display"=>"Date","date_format" => "Y-n-j");

$my_sql['search']['page_count'] = array("input_type" => "select","display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
	
$my_sql['postpage'] = $_SERVER['PHP_SELF'];
$my_sql['title'] = "Rebill Lookup";

$my_sql['result_actions']['postpage'] = $_SERVER['PHP_SELF'];
$my_sql['result_actions']['title'] = "Rebills Found";

/****************
Process and Render Forms
****************/
require_once('includes/header.php');

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{
	$rebills->set_sort_by($_REQUEST['frm_sort_by']);
	$rebills->set_limit_offset($_REQUEST['frm_page_count'],$_REQUEST['frm_page_offset']);
	if(isset($_REQUEST['frm_displayrange']) && $_REQUEST['frm_displayrange'] == 1)
		$rebills->set_date_range($_REQUEST['frm_tran_date_from'],$_REQUEST['frm_tran_date_to']);
	
	$rebills->get_rebills_to_run();
	
	echo "<center>" . $rebills->render_rebilling_transactions() . "</center>";
}


require_once("includes/footer.php");
?>
