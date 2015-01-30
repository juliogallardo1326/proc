<?
$headerInclude="ledgers";
$periodhead="Ledgers";

require_once("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once('../includes/subFunctions/smart_search.php');
require_once('../includes/subFunctions/color_manip.php');
require_once("../includes/log.class.php");

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

/**************
Define functions to process form
**************/

$log = new log_class();

/****************
Process Form
****************/




/**************
Define pairs
**************/

	
$my_sql['pairs']['actors'] = array(
		array("display" => "Any", "value"=>"","default"=>1),
		array("display" => "misc", "value"=>"misc"),
		array("display" => "customer", "value"=>"customer"),
		array("display" => "merchant", "value"=>"merchant"),
		array("display" => "reseller", "value"=>"reseller"),
		array("display" => "admin", "value"=>"admin"),
		array("display" => "system", "value"=>"system"),
		array("display" => "service", "value"=>"service"),
		array("display" => "bank", "value"=>"bank")
		);


$my_sql['pairs']['actions'] = array(
		array("display" => "Any", "value"=>"","default"=>1),
		array("display" => "rebill", "value"=>"rebill"),
		array("display" => "email", "value"=>"email"),
		array("display" => "misc", "value"=>"misc"),
		array("display" => "error", "value"=>"error"),
		array("display" => "order", "value"=>"order"),
		array("display" => "login", "value"=>"login"),
		array("display" => "notify", "value"=>"notify"),
		array("display" => "hackattempt", "value"=>"hackattempt"),
		array("display" => "erroralert", "value"=>"erroralert"),
		array("display" => "pendingwebsite", "value"=>"pendingwebsite"),
		array("display" => "pendingdocuments", "value"=>"pendingdocuments"),
		array("display" => "requestrates", "value"=>"requestrates"),
		array("display" => "resellerrequestrates", "value"=>"resellerrequestrates"),
		array("display" => "requestlive", "value"=>"requestlive"),
		array("display" => "completedapplication", "value"=>"completedapplication"),
		array("display" => "turnedlive", "value"=>"turnedlive"),
		array("display" => "requestmarkup", "value"=>"requestmarkup")
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

/****************
Define Search Fields and Action Fields
****************/

$my_sql['search']['lg_actor'] = array("input_type" => "selectmulti","display" => "Actors");
$my_sql['search']['lg_actor']['options']['source']['pairs'] = "actors";

$my_sql['search']['lg_action'] = array("input_type" => "selectmulti","display" => "Actions");
$my_sql['search']['lg_action']['options']['source']['pairs'] = "actions";

$my_sql['search']['lg_txt'] = array("input_type" => "textarea","display" => "Text");

$my_sql['search']['limit_to'] = array("input_type" => "text","display" => "Limit Result","value"=>100);

$my_sql['search']['displayrange'] = array("input_type" => "select","display"=>"Display Range");
$my_sql['search']['displayrange']['options']['source']['pairs'] = "DisplayRange";

$my_sql['search']['lg_timestamp'] = array("input_type" => "date","display"=>"Date","date_format" => "Y-n-j");


$my_sql['search']['page_count'] = array("input_type" => "select","display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
	
$my_sql['postpage'] = $_SERVER['PHP_SELF'];
$my_sql['title'] = "Log Lookup";

$my_sql['result_actions']['postpage'] =  $_SERVER['PHP_SELF'];
$my_sql['result_actions']['title'] = "Logs Found";

/****************
Process and Render Forms
****************/
require_once('includes/header.php');

smart_search_form($my_sql);


if(smart_process_mysql_form($my_sql))
{
	$_REQUEST = smart_scrub_params($_REQUEST);
	
	$log->set_actors($_REQUEST['frm_lg_actor']);
	$log->set_actions($_REQUEST['frm_lg_action']);
	$log->set_text($_REQUEST['frm_lg_txt']);
	$log->set_limit($_REQUEST['frm_page_offset'],$_REQUEST['frm_page_count']);
	if($_REQUEST['frm_displayrange'])
		$log->set_date_range($_REQUEST['frm_lg_timestamp_from'],$_REQUEST['frm_lg_timestamp_to']);
	$log_entries = $log->get_logs();
	echo "<center>" . $log->render_logs() . "</center>";
}


require_once("includes/footer.php");
?>
