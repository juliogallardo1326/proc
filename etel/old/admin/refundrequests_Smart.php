<?

include("includes/sessioncheck.php");
$headerInclude="customerservice"; 

$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();

include 'includes/header.php';
include_once "../includes/completion.php";
require_once('../includes/subFunctions/smart_search.php');

/**************
Define functions to process form
**************/

function smart_processTransactions($form_res,$action,&$results)
{	
	$status = array();
	if(isset($form_res['entries']))
		foreach($form_res['entries'] as $key => $values)
			if($values['value'] !="")
				$status[] = $values['value']($values,$action);
	return $status;
}

function smart_deleteRefund($values,$action)
{
	global $adminInfo;
	
	$sql="Select * FROM `cs_callnotes` WHERE note_id = '".$values['append']."'";
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	$callNoteInfo = mysql_fetch_assoc($result);

	if($adminInfo['li_level'] == 'full')
	{
		$sql="DELETE FROM `cs_callnotes` WHERE note_id = '".$values['append']."'";
		$result=sql_query_write($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	
		return array("action"=>"Transaction ID " . $callNoteInfo['transaction_id'] . " no longer being refunded.","status"=>"success");
	}
	return array("action"=>"Transaction ID " . $callNoteInfo['transaction_id'] . " no longer being refunded.","status"=>"failed.  insufficient privilages.");
}

function smart_issueRefund($values,$action)
{
	global $adminInfo;

	$sql="Select * FROM `cs_callnotes` WHERE note_id = '".$values['append']."'";
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	$callNoteInfo = mysql_fetch_assoc($result);

	if($adminInfo['li_level'] == 'full')
	{
		require_once ('../includes/int.refund.php');
		$sql="Select * FROM `cs_callnotes` WHERE note_id = '".$values['append']."'";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$callNoteInfo = mysql_fetch_assoc($result);
		
		$sql = "Select * from `cs_transactiondetails` where transactionId = '".$callNoteInfo['transaction_id']."'";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$transInfo = mysql_fetch_assoc($result);
		$response = execute_refund($transInfo,$callNoteInfo['service_notes'].": ".$callNoteInfo['customer_notes']);
		
		$error_msg = "success";
		if($response['success']==false) $error_msg = "ERROR: Refund Not successful ~ ".$response['errormsg'];
			
		return array("action"=>"Transaction ID " . $transInfo['reference_number'] . " refund attempted:","status"=>"$error_msg");
	
	}
	return array("action"=>"Transaction ID " . $transInfo['reference_number'] . " refund attempted:","status"=>"failed.  insufficient privilages.");
}

/****************
Define Search Fields and Action Fields
****************/

/*
	Select 
		N.*,
		B.reference_number,
		C.userId,
		C.companyname 
	from 
		cs_callnotes N,
		cs_transactiondetails B,
		cs_companydetails C 
	where 
		N.transaction_id = B.transactionId 
		AND N.`cn_type` = 'refundrequest' 
		and B.userId = C.userId 
		AND N.call_date_time BETWEEN '2003-4-22 00:00:00' AND '2006-4-22 23:59:59' 
	Order by 
		C.companyname, 
		N.call_date_time desc
*/
$my_sql['tables'] = array("cs_transactiondetails AS td","cs_callnotes AS cn","cs_companydetails AS cd");

//$my_sql['return']["01|Username"] = array("source" => "td.td_username","column"=>"td_username");
//$my_sql['return']["04|Password"] = array("source" => "td.td_password","column"=>"td_password");
//$my_sql['return']["03|Transaction ID"] = array("source" => "td.reference_number","column"=>"reference_number");
//$my_sql['return']["02|Transaction Date"] = array("source" => "DATE_FORMAT(td.transactionDate,'%m/%d/%Y %h:%i:%s') as transaction_date_formatted","column"=>"transaction_date_formatted");

$my_sql['return']["00|Transaction ID"] = array("source" => "td.transactionid","column"=>"transactionid","hidden"=>1);
$my_sql['return']["00|Note ID"] = array("source" => "cn.note_id","column"=>"note_id","hidden"=>1);
//$my_sql['return']["00|Site ID"] = array("source" => "td.td_site_ID","column"=>"td_site_ID","hidden"=>1);

$my_sql['return']["01|Reference Number"] = array("source" => "td.reference_number","column"=>"reference_number");
$my_sql['return']["01|Reference Number"]["link"]["destination"] = "viewTransaction.php";
$my_sql['return']["01|Reference Number"]["link"]["parameters"] = array(array("name"=>"ref","value"=>"reference_number","source"=>"result"));
$my_sql['return']["02|Amount"] = array("source" => "td.amount","column"=>"amount","display_type"=>"currency");
$my_sql['return']["03|Name"] = array("source" => "CONCAT(td.surname,', ',td.name) AS full_name","column"=>"full_name");
$my_sql['return']["04|Contact"] = array("source" => "td.email","column"=>"email");
$my_sql['return']["05|Request Date/Time"] = array("source" => "cn.call_date_time","column"=>"call_date_time");
$my_sql['return']["06|Reason"] = array("source" => "CONCAT(cn.service_notes,'<br>',cn.customer_notes) AS notes","column"=>"notes",
	"disp_clip"=>array('overflow'=>true,'w'=>'200px'));
	

if($export_data)
{
	if(in_array($export_data,array('full','transaction')))
		$my_sql['limit']['forcelimit']=30000;
		
	$my_sql['sql_config'] = array('TimeOut'=>60);

	if(in_array($export_data,array('full','transaction')))
		$my_sql['limit']['forcelimit']=30000;
}

$my_sql['orderby'] = array("LOWER(companyname) ASC");
$my_sql['user_orderby']['full_name'] = 1;
$my_sql['user_orderby']['amount'] = 1;
$my_sql['user_orderby']['status'] = 1;

$my_sql['key']["companyname"] = array("display" => "Company Name: ");
$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"number_transactions",
						"max_offset_source"=>"result");

$my_sql['search']['company_select_by'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Search By");
$my_sql['search']['company_select_by']['options']['source']['pairs'] = "Company_Search_By";
$my_sql['search']['company_select_by']['style'] = array("disableOnSubmit"=>1,'onchange'=>'func_company_fill();');

$my_sql['search']['transaction_type'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Merchant Type");
$my_sql['search']['transaction_type']['options']['source']['script'] = "smart_getMerchantTypes";
$my_sql['search']['transaction_type']['style'] = array("disableOnSubmit"=>1,'onchange'=>'func_company_fill();');

//$my_sql['search']['company_type'] = array("input_type" => "select", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Company Type");
//$my_sql['search']['company_type']['options']['source']['script'] = "smart_getCompanyTypes";
//$my_sql['search']['company_type']['style'] = array("disableOnSubmit"=>1,'onchange'=>'func_company_fill();');

$my_sql['search']['limit_to'] = array("input_type" => "text", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Limit Result","value"=>100);

$my_sql['onload'] = "func_company_fill();";

$my_sql['search']['company_search'] = array("input_type" => "text", "compare"=> "=","required"=>0,"in_query"=>false,"display" => "Search With");
$my_sql['search']['company_search']['options']['source']['ajax'] = "smart_AJAX_company_search";
$my_sql['search']['company_search']['options']['source']['parameters']['on_action'] = "onKeyUp";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_call'] = "func_company_fill";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_response'] = "func_company_fill_response";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['location'] = "/admin/admin_JOSN.php";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_url']['parameters'] = array(

/*	func=getCompanyInfo
	search='+company_select_str;
	searchby='+company_select_by+'
	tt='+transaction_type+'
	cp='+cd_completion+'
	bi='+bank_id+'
	limit_to='+limit_to+'
	gi='+gateway_id+'
	ig='+cd_ignore;
*/
	array("name"=>"func","value"=>"getCompanyInfo","source"=>"given","url_name"=>"func"),
	array("name"=>"company_search","value"=>"company_search","source"=>"form","url_name"=>"search"),
	array("name"=>"company_select_by","value"=>"company_select_by","source"=>"form","url_name"=>"searchby"),
	array("name"=>"limit_to","value"=>"limit_to","source"=>"form","url_name"=>"limit_to"),
	//array("name"=>"company_type","value"=>"company_type","source"=>"form","url_name"=>"company_type"),
	array("name"=>"transaction_type","value"=>"transaction_type","source"=>"form","url_name"=>"tt")
);
$my_sql['search']['company_search']['options']['source']['parameters']['search'] = "company_search";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_form_element'] = "td.userId";
$my_sql['search']['company_search']['options']['source']['parameters']['ajax_form_element_b'] = "td.td_site_ID";


$my_sql['search']['td.userId'] = array("input_type" => "selectmulti", "compare"=> "IN","display" => "Company Name");
$my_sql['search']['td.userId']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);


//$my_sql['search']['td.userId']['action'] = "onChange=\"documentd.getElementById('frm_company_search').value = this.options[this.selectedIndex].text; func_company_fill();\"";
//$my_sql['search']['td.userId']['options']['source']['script'] = "smart_getCompanies";
$my_sql['search']['td.userId']['options']['source']['parameters'] = NULL;

$my_sql['search']['td.td_site_ID'] = array("input_type" => "selectmulti", "compare"=> "IN","display"=>"Web Site");
$my_sql['search']['td.td_site_ID']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);

$my_sql['search']['td.reference_number'] = array("input_type" => "text", "compare"=> "IN","required"=>0,"display" => "Reference Number");

$my_sql['search']['td.bank_id'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display" => "Charge Bank");
$my_sql['search']['td.bank_id']['options']['source']['script'] = "smart_getBanks";
$my_sql['search']['td.bank_id']['style'] = array("size"=>10,
		"style"=>"width: 350px;height: 40px;",
		"onfocus"=>'this.style.height=150;'
);
$my_sql['search']['td.transactiondate'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-n-j");
$my_sql['search']['request_days_old'] = array("input_type" => "text","in_query" => false, "required"=>0,"display"=>"Days Old","value" => 6);

$six_day = isset($_REQUEST['frm_request_days_old']) ? $_REQUEST['frm_request_days_old'] : 6;
$six_day = date("Y-m-d 23:59:59",time() - $six_day*24*60*60);
$my_sql['where']['cn.call_date_time'] = array("value" => "\"$six_day\"", "compare" => "<");

if($_REQUEST['frm_td_reference_number']!="" )
{
	$my_sql['search']['td.transactiondate']['in_query'] = false;
	$my_sql['search']['td.transactiondate']['required'] = false;
	unset($my_sql['where']['cn.call_date_time']);
	$my_sql['search']['td.td_site_ID']['in_query'] = false;
	$my_sql['search']['td.td_site_ID']['required'] = false;
	$my_sql['search']['td.userId']['in_query'] = false;
	$my_sql['search']['td.userId']['required'] = false;
}

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

$my_sql['where']['cd.userId'] = array("value" => "td.userId", "compare" => "=");
$my_sql['where']['cn.cn_type'] = array("value" => "'refundrequest'", "compare" => "=");
$my_sql['where']['cn.transaction_id'] = array("value" => "td.transactionId", "compare" => "=");
$my_sql['where']['td.cancelstatus'] = array("value" => "'N'", "compare" => "=");
$my_sql['where']['td.status'] = array("value" => "'D'", "compare" => "!=");
$my_sql['where']['td.td_is_chargeback'] = array("value" => "0", "compare" => "=");




$my_sql['subquery']['title'] = "Refund Request Summary";
$my_sql['subquery']['queries']['01|Total Value'] = array("name"=>"total_transactions", "source" => "SUM(td.amount)","display_type"=>"currency");
$my_sql['subquery']['queries']['02|Transactions'] = array("name"=>"number_transactions", "source" => "COUNT(td.amount)");
$my_sql['subquery']['queries']['03|Approved'] = array("name"=>"number_approved", "source" => "SUM(if(td.status ='A',1,0))");
$my_sql['subquery']['queries']['04|Declined'] = array("name"=>"number_declined", "source" => "SUM(if(td.status <> 'A',1,0))");
$my_sql['subquery']['queries']['05|Rebilled'] = array("name"=>"number_rebilled", "source" => "SUM(if(td.td_is_a_rebill = 1,1,0))");

$my_sql['subquery']['queries']['03|Approved Amount'] = array("name"=>"amount_approved", "source" => "SUM(if(td.status ='A',td.amount,0))",'disp_append_front'=>'$','disp_decimal'=>true);
$my_sql['subquery']['queries']['04|Declined Amount'] = array("name"=>"amount_declined", "source" => "SUM(if(td.status <> 'A',td.amount,0))",'disp_append_front'=>'$','disp_decimal'=>true);
$my_sql['subquery']['queries']['05|Rebilled Amount'] = array("name"=>"amount_rebilled", "source" => "SUM(if(td.td_is_a_rebill = 1,td.amount,0))",'disp_append_front'=>'$','disp_decimal'=>true);

$my_sql['subquery']['queries']['05|Cancelled'] = array("name"=>"number_cancelled", "source" => "SUM(if(td.td_enable_rebill = 1,0,1))");

$my_sql['postpage'] = "refundrequests_Smart.php";
$my_sql['title'] = "Find Refund Requests";

$my_sql['result_actions']['postpage'] = "refundrequests_Smart.php";
$my_sql['result_actions']['title'] = "Refund Requests Found";
$my_sql['result_actions']['resulttitle'] = "Refund Requests Processed";

//$my_sql['result_actions']['actions']['userid'] = array("input_type" =>"hidden", "compare"=> "=","required"=>1,"value" => $sessionlogin);
$my_sql['result_actions']['actions']['entries'] = array("input_type"=>"select","display"=>"","required" => 1);
$my_sql['result_actions']['actions']['entries']['options']['source']['pairs'] = "Actions";
$my_sql['result_actions']['process'] = "smart_processTransactions";
$my_sql['result_actions']['append'] = array("name"=>"note_id","source"=>"result");

$my_sql['pairs']['PendingCheck'][] = array("display" => "Pending Check", "value"=>1);
$my_sql['pairs']['CancelStatus'][] = array("display" => "Is Refunded", "value"=>"Y");
$my_sql['pairs']['ChargeBack'][] = array("display" => "Is a Chargeback", "value"=>"1");

$my_sql['pairs']['Rebilling'] = array(
		array("display" => "Enabled or Disabled", "value"=>""),
		array("display" => "Enabled", "value"=>"1"),
		array("display" => "Disabled", "value"=>"0")
		);


$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "100", "value"=>"100"),
	array("display" => "1000", "value"=>"1000"),
	array("display" => "None", "value"=>"-1")
	);


$my_sql['pairs']['Status'] = array(
	array("display" => "Approved", "value"=>"A"),
	array("display" => "Declined", "value"=>"D"),
	array("display" => "Pending", "value"=>"P")
	);
	
$my_sql['pairs']['ExportDetails'] = array(
	array("display" => "Disabled", "value"=>""),
	array("display" => "Full Export", "value"=>"full"),
	array("display" => "Just Summary", "value"=>"summary"),
	array("display" => "Just Data", "value"=>"transaction")
	//array("display" => "Extended", "value"=>"4")
	);

$my_sql['pairs']['Actions'] = array(
	array("display" => "No Action", "value"=>""),
	array("display" => "Issue Refund", "value"=>"smart_issueRefund"),
	array("display" => "Delete Refund", "value"=>"smart_deleteRefund")
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

smart_render_action_results(smart_process_action_form($my_sql['result_actions']),$my_sql['result_actions']['resulttitle']);

smart_search_form($my_sql);

$my_sql['sql_config'] = array('TimeOut'=>60);

if(smart_process_mysql_form($my_sql))
{
	if($export_data)
	{	
		unset($my_sql['key']["companyname"]);
		$my_sql['return']["01|Company"] = array("source" => "cd.companyname","column"=>"companyname");
		$my_sql['return']["21|Type"] = array("source" => "td.cardtype","column"=>"cardtype");
		$my_sql['return']["22|OriginalTransDate"] = array("source" => "td.transactionDate","column"=>"transactionDate");
		$my_sql['return']["23|BankTransID"] = array("source" => "td.td_bank_transaction_id","column"=>"td_bank_transaction_id");
		if($export_data=='summary') {$my_sql['skip_query']=true; }
		if($export_data=='transaction') {$my_sql['skip_subquery']=true;  $export_subname = '';}
		ob_clean();
		$filename = 'Export'.$export_subname.'.csv';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		flush();
	}
	
	$result = smart_search($my_sql);
	if($export_data)
	{	
		smart_render_export($result, $my_sql);
		die();
	}
	smart_render_results($result, $my_sql);
}


include("includes/footer.php");
?>
