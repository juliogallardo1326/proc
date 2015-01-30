<?php

$etel_debug_mode=0;
$etel_singleview_allowed=1;
$allowBank=true;
$noHeaderOutput=true;

require_once("includes/sessioncheck.php");
$rootdir = "";

require_once($rootdir."includes/JSON.php");
require_once("includes/dbconnection.php");
$userId =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

function purgePincode($data)
{
	global $userId;
	$rd_subaccount = quote_smart($_REQUEST['sa']);
	
	foreach($_REQUEST['pi'] as $key=>$val)
		$_REQUEST['pi'][$key] = intval($val);
	
	$sql = "
		DELETE FROM  
			cs_pincodes
		WHERE
			pc_subAccount = \"" . $rd_subaccount . "\" and 
			pc_ID in (" . implode(',',$_REQUEST['pi']). ") limit 1000
	;";
	$result = mysql_query($sql) or die(mysql_error()." ~ $sql");
	$data['deleted_num'] = mysql_affected_rows();

}

function getSelectedPincodes($data)
{
	global $userId;
	$rd_subaccount = quote_smart($_REQUEST['sa']);
	$pinInfo_list = array();
	$found = 0;
	$pc_ID = "-1";
	
	foreach($_REQUEST['pi'] as $key=>$val)
		$pc_ID .= ($pc_ID?",":"").intval($val);
	$_REQUEST['pi'][$key] = intval($val);
	
	$sql = "
		SELECT 
			pc_ID AS pi,
			pc_code AS pc,
			pc_used AS used,
			pc_trans_ID AS td,
			pc_type AS type,
			pc_pass AS pass,
			
			trans.td_username AS user,
			trans.td_password AS tpass,
			trans.reference_number AS ref
			
		FROM  
			cs_pincodes
		LEFT JOIN cs_rebillingdetails as rd ON rd_subaccount = pc_subAccount
		LEFT JOIN cs_transactiondetails AS trans ON pc_trans_ID = transactionId			
		WHERE
			rd.company_user_id = '$userId'
			and pc_subAccount = \"" . $rd_subaccount . "\"
			and pc_ID in ($pc_ID)
			 limit 1000
	;";
	$result = mysql_query($sql) or dieLog(mysql_error());
//	exit(mysql_error() . "<pre><b>$sql</b></pre>");

	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("Content-Transfer-Encoding: octet");
	header("Content-Disposition: attachment; filename=\"pincodes.txt\"");

	while($pinInfo = mysql_fetch_assoc($result))
	{
		//echo $pinInfo['type'] . "\t";
		echo $pinInfo['pc'] ;
		if($_REQUEST['mode']=='userpass')
		echo "/".$pinInfo['pass'] ;
		//echo (isset($pinInfo['user']) ? $pinInfo['user'] : "") . "\t";
		//echo (isset($pinInfo['tpass']) ? $pinInfo['tpass'] : "") . "\t";
		//echo $pinInfo['ref'] . "\t";
		echo "\r\n";
	}
	exit();
}

function getPincodeInfo($data)
{
	global $userId;
	$rd_subaccount = quote_smart($_REQUEST['sa']);
	if(isset($_REQUEST['mode'])) $mode = intval($_REQUEST['mode']);
	$pinInfo_list = array();
	
	$sql_where = "";
	if(isset($mode)) $sql_where=" and pc_used = '$mode'";
	
	$sql = "
		SELECT 
			pc_ID AS pi,
			pc_code AS pc,
			pc_used AS used,
			pc_trans_ID AS td,
			td_subscription_id AS si,
			pc_pass AS pass
		FROM 
			cs_pincodes 
		LEFT JOIN cs_rebillingdetails as rd ON rd_subaccount = pc_subAccount
		LEFT JOIN cs_transactiondetails ON pc_trans_ID = transactionId
		WHERE 
			rd.company_user_id = '$userId'
			and rd_subaccount = '$rd_subaccount'
			$sql_where
			order by pc_used asc,pc_code asc
			
		;"; 
	$result = mysql_query($sql) or dieLog(mysql_error());
	while($pinInfo = mysql_fetch_assoc($result))
	{
		$found[$pinInfo['used']]++;
		$pinInfo['td'] = intval($pinInfo['td']);
		$pinInfo_list[] = $pinInfo;
	}
	$data['pinInfo_list'] = $pinInfo_list;
	$data['found_num'] = $found;

}

function genPincodes($data)
{
	global $userId;	
	$pinInfo_list = array();
	$rd_subaccount = quote_smart($_REQUEST['sa']);
	$gen_num = intval($_REQUEST['gn']);

	$sql = "
		SELECT 
			count(*) as cnt
		FROM 
			cs_pincodes 
		LEFT JOIN cs_rebillingdetails ON  pc_subAccount = rd_subAccount
		WHERE 
			cs_rebillingdetails.company_user_id = '$userId'
			and rd_subaccount = '$rd_subaccount'
			and pc_used = '0'
		;"; // Only get required pincode info, not *.
	$result = mysql_query($sql) or dieLog(mysql_error());
	$cnt = mysql_result($result,0,0);
	if($gen_num > (1000 - $cnt)) $gen_num = (1000 - $cnt);

	//$pinInfo_list[] = array("pc" => "$subaccount", "used" => "0","pass"=>"subaccount");
	$mode = "pincode";
	if($_REQUEST['mode'] == "userpass")
		$mode = "userpass";

	for($j=0;$j<$gen_num;$j++)
	{
		$code = rand(1000000,9999999);
		$pass = rand(1000000,9999999);
		
		
		$sql = "
			INSERT INTO 
				cs_pincodes
			SET
				pc_subAccount = \"" . $rd_subaccount . "\",
				pc_type = \"" . $mode . "\",
				pc_code = \"" . $code . "\",
				pc_pass = \"" . $pass . "\",
				pc_used = \"0\"
		;";
		$result = mysql_query($sql) or dieLog(mysql_error());
	}
	$data['created_num'] = $gen_num . " " . $_REQUEST['mode'];
}

$data = NULL;
switch($_REQUEST['func'])
{

	case 'purgePincode':            	
		purgePincode(&$data);	
		getPincodeInfo(&$data);
	break;
	case 'getPincodeInfo':       	
		getPincodeInfo(&$data);
	break;
	case 'genPincodes':	
		genPincodes(&$data);
		getPincodeInfo(&$data);
	break;
	case 'downloadPincode':
		getSelectedPincodes(&$data);
	break;
}
$json = new Services_JSON();
$output = $json->encode($data);
print($output);
?>