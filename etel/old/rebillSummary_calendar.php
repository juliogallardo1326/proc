<?
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

if($_REQUEST['frm_cancel_rebills'] == "1")
{
	$rebills->cancel_rebills($_REQUEST['frm_cancel_ids']);
	unset($_REQUEST['frm_cancel_ids']);
	unset($_REQUEST['frm_cancel_rebills']);
	
	$params = $rebills->request_params("frm_");
	
	header("Location: rebillSummary_smart.php?$params");
	exit();
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

$my_sql['search']['tran_date'] = array("input_type" => "date_simple","display"=>"Month to View","date_format" => "Y-n-j","min_year"=>date("Y")-10,"max_year"=>date("Y")+2);

$my_sql['postpage'] = "rebillSummary_calendar.php";
$my_sql['title'] = "Rebill Calendar";

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
	$rebills->set_rebill_status('active');
	$rebills->set_month($_REQUEST['frm_tran_date_month'],$_REQUEST['frm_tran_date_year']);
	
	//$rebills->get_rebilldetails();
	
	echo $rebills->render_status_summary() . "<br><br>";
	echo $rebills->render_rebill_summary() . "<br><br>";
	echo $rebills->render_calendar(false) . "<br><br>";
}


require_once("includes/footer.php");
?>
