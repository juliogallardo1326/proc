<?php 


chdir("..");
session_start();

$refhost = parse_url($_SERVER['HTTP_REFERER']);
if($refhost['host'] != $_SERVER['HTTP_HOST'])
	$_SESSION = array();

if($_GET['if'] && $_SESSION['mt_posted_variables']) $_POST = $_REQUEST = unserialize($_SESSION['mt_posted_variables']);

if(!isset($testmode))
	$testmode=$_REQUEST["test"];
if(!isset($_SESSION['testmode']))
	$_SESSION['testmode'] = $testmode;
else
	$testmode = $_SESSION['testmode'];

if($_REQUEST['mt_IBILL_refer_url'])
{
	$from_url = $_REQUEST['mt_IBILL_refer_url'];
	$from_url_info = parse_url($from_url);

		
	$_SERVER["HTTP_REFERER"] = $_REQUEST['mt_IBILL_refer_url'];
	
	$gateway_db_select=5;
	require_once("includes/dbconnection.php");
	$mt_reference_id = (isset($_REQUEST["mt_reference_id"])?quote_smart($_REQUEST["mt_reference_id"]):"");
	$mt_subAccount = substr($mt_reference_id,0,-3)."-00".substr($mt_reference_id,strlen(substr($mt_reference_id,0,-3)),3);
	//$sql = "SELECT rd_subaccount FROM `cs_rebillingdetails` WHERE (`rd_subName` = '$mt_subAccount') AND `company_user_id` = " .$companyid ;
	$mt_subAccount = str_replace('--','-',$mt_subAccount);
	$_REQUEST["mt_subAccount"] = $mt_subAccount;
	//if(!($result = mysql_query($sql,$cnn_cs)))
	
	$sql = "
		SELECT 
			cs_reference_ID 
		FROM 
			`cs_company_sites` 
		WHERE 
			cs_URL like '%".quote_smart($from_url_info['host'])."%' and cs_hide=0
		";
		
	$result = mysql_query($sql) or dieLog(mysql_error());
	$ref = mysql_result($result,0,0);
	$_REQUEST["mt_reference_id"] = $ref;
	$testmode=$_REQUEST["test"];
	//$testmode = true;
	//https://secure.etelegate.com/testintegration.php?mt_reference_id=126024102&mt_IBILL_refer_url=http://www.junglegirls.com
	$xtra = " If you are an IBill Merchant, please check your merchant email for further integration instructions.";
}

require_once("includes/dbconnection.php");
require_once("includes/integration.php");
require_once("includes/transaction.class.php");
require_once('includes/function.php');
require_once($rootdir.'smarty/libs/Smarty.class.php');

echo "<!-- Header -->";

$_SESSION['stat'] = (isset($_SESSION['stat']) ? $_SESSION['stat'] : 1);
$gateway_db_select=$_SESSION["gw_id"];

function setMerchantDetails()
{
	$validPosts = array(
		"mt_reference_id",
		"mt_subAccount",
		"mt_prod_desc",
		"mt_product_id",
		"mt_amount",
		"mt_language",
		"firstname",
		"lastname",
		"address",
		"country",
		"city",
		"state",
		"otherstate",
		"td_username",
		"td_password",
		"zipcode",
		"telephone",
		"email",
		"mt_checksum",
		"number",
		"cvv2",
		"routing",
		"account",
		"yyyy",
		"mm",
		"cardtype",
		"crosssales",
		"bank_id",
		"td_bank_number",
		"bankname",
		"checktype",
		"additional_funds",
		"wallet_id",
		"wallet_pass"
		);
	$gw_id = $_SESSION["gw_id"];
	$allowed = array();
	
	if($_REQUEST['mt_subAccount'] == "-1")
		$_REQUEST['mt_subAccount'] = "";
	
	
	
	foreach($validPosts as $val)
	{
		if(isset($_REQUEST[$val]) && $_REQUEST[$val] != "")
			$allowed[$val] = $_REQUEST[$val];
		if(isset($_REQUEST[$_REQUEST['cardtype']."_".$val]) && $_REQUEST[$_REQUEST['cardtype']."_".$val] != "")
			$allowed[$val] = $_REQUEST[$_REQUEST['cardtype']."_".$val];
		IF($allowed[$val]) $allowed[$val] = preg_replace('/[\'"]/','`',$allowed[$val]);
	}
	
	$allowed['telephone'] = preg_replace('/[^0-9]/','',$allowed['telephone']);
	$_SESSION = array_merge($_SESSION,$allowed);
	$_SESSION['telephone'] = $allowed['telephone'];
	
	$refhost = parse_url($_SERVER['HTTP_REFERER']);
	
	if($refhost['host'] != $_SERVER['HTTP_HOST'])
	{
		if(!$_SESSION['mt_posted_variables']) 	
			$_SESSION['mt_posted_variables'] = serialize($_REQUEST);
		if(!$_SESSION['from_url']) 				
			$_SESSION['from_url'] = $_SERVER["HTTP_REFERER"];
	}
	$_SESSION["gw_id"] = $gw_id;
	
	return $_SESSION;
}

function getInitError($subId = FALSE,$row)
{
	
	$error = NULL; // set error array...burrito
	
	if($_SESSION['orderArr']['url_verified']===false)
		$error[] = $_SESSION['orderArr']['url_verified_text'];
		
	if(!is_array($_SESSION['orderArr']))
		$error[] = "Order Information could not be processed. Please check your integration.<br> Either your Reference ID '".$_REQUEST['mt_reference_id']."' could not be found, or PricePoint/SubAccount '".$_REQUEST['mt_subAccount']."' could not be found.";
		
	if(!isset($_SESSION['mt_reference_id']) || $_SESSION['mt_reference_id'] == "")
		$error[] = "No site reference ID passed. Unable to Process Transaction. Please refer to your integration guide.";
	else if((!isset($_SESSION['mt_amount']) || $_SESSION['mt_amount'] == "") && !$subId)
		$error[] = "Missing PricePoint information. Please refer to your Integration Guide for information on PricePoint pricing.";
	else
	{
		//check for valid reference id
		$sql="
			SELECT 
				* 
			FROM 
				`cs_company_sites` as s
			LEFT JOIN `cs_companydetails` as c on s.cs_company_id = c.`userId` 
			WHERE 
				s.`cs_reference_id` = '".$_SESSION["mt_reference_id"]."'
			";

		$result=sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");

		if(mysql_num_rows($result)<1 && !$testmode) 
			$error[] = "Invalid Company/Website"; 
		else
		{
			$companyInfo = mysql_fetch_assoc($result);	

			if(isset($_SESSION['mt_amount']) && !$companyInfo['cd_allow_rand_pricing'])
				$error[] = "Independent Pricing is Not Allowed";
				
			
			if(!$companyInfo['activeuser'] && !$_SESSION['testmode']) 
				$error[] = "Merchant Account Not Live. Please contact Administrator.";
	
			if(!in_array($companyInfo['cs_verified'],array("approved","non-compliant")) && !$_SESSION['testmode']) 
				$error[] = "Merchant Website Not Approved. Please contact Administrator."; 
				
			if($_SESSION['mt_amount'] && !checkCheckSum($companyInfo))
				$error[] = "Price checksum failed. Please refer to your integration guide.";
			
			if(!$companyInfo['cs_allow_testmode'] && $_SESSION['testmode']) 
				$error[] = "Test Mode is <b>disabled</b> for this site. To enable Test Mode, please edit this website's settings in your Merchant Gateway Interface."; 
		}
	}
	return $error;
}

function checkCheckSum($row)
{
	if( !$row['cd_verify_rand_price']) return TRUE;
	$check = md5($row['cd_secret_key'].$row['cs_reference_ID'].$_SESSION['mt_amount'].$_SESSION['mt_product_id']);
	etelPrint("Checksum:<br>" . $check."&nbsp;&nbsp;".$_SESSION['mt_checksum']."&nbsp;");
	etelPrint($row['cd_secret_key']."-".$row['cs_reference_ID']."-".$_SESSION['mt_amount']."-".$_SESSION['mt_product_id']);
	if($_SESSION['mt_checksum'] != $check)
		return FALSE;				

	return TRUE;
}

function getTransPhase($pass_manage=true)
{
	if(!isset($_REQUEST['submit_form']))
		return 0;
	
	$emailmatch = '/^(([a-z0-9!#$%&*+-=?^_`{|}~][a-z0-9!#$%&*+-=?^_`{|}~.]*[a-z0-9!#$%&*+-=?^_`{|}~])|[a-z0-9!#$%&*+-?^_`{|}~]|("[^"]+"))[@]([-a-z0-9]+\.)+([a-z]{2}|com|net|edu|org|gov|mil|int|biz|pro|info|arpa|aero|coop|name|museum)$/ix';
				 
	$ferror = array(); // set friendly errors...burrito

	// check for missing information server side...burrito
	$reqFields = array(
		"firstname"=>"You're Missing Your First Name",
		"lastname"=>"You're Missing You're Last Name",
		"address"=>"You're Missing Your Address",
		"country"=>"You Need To Select Your Country",
		"city"=>"You're Missing Your City",
		"telephone"=>"You're Missing Your Phone Number",
		"email"=>"You're Missing Your Email Address",
		"bank_id"=>"You Need To Select The Credit Card Type"
		);

	if($_SESSION['country'] == 'US')
	{
		$reqFields["state"] = "You Need To Select Your State";
		$reqFields["zipcode"] = "You Need To Select Your Zipcode";
	}
	
	$reqBankFields = array(
		"routing"=>"You're Missing The Bank Routing Number",
		"account"=>"You're Missing The Bank Account Number"
		);
		
	$reqRebillFields = array(
		"td_username"=>"You're Missing Your Username",
		"td_password"=>"You're Missing Your Password"
		);
		
	$reqCreditFields = array(
		"number"=>"You're Missing Your Credit Card Number",
		"cvv2"=>"You're Missing Your CVV2 Code",
		"yyyy"=>"You Need To Select The Expiration Year",
		"mm"=>"You Need To Select The Expiration Month",
		"cardtype"=>"You Need To Select The Credit Card Type"
		);

	if(isset($_SESSION['cardtype']))
		if($_SESSION['cardtype'] == "check")
			$reqFields = array_merge($reqFields,$reqBankFields);
	
	if((!$_SESSION['email']) ||(!preg_match($emailmatch, trim($_SESSION['email']))) )
	{
		$ferror[] = "Please enter a Valid Email (ex. name@website.com)";
		//if(strpos($_SESSION['email'],'@'))
		//	toLog('erroralert','customer',"Customer's Email failed to match: ".$_SESSION['email']);
	}
	if($pass_manage)
	{
		$qry	=	"SELECT *
					FROM
					`cs_subscription`
					WHERE
					`ss_cust_username` = '{$_SESSION['td_username']}'
					AND
					`ss_site_ID` = '$siteId'";
		$result = sql_query_read($qry)
			or dieLog(mysql_error());
		if($row = mysql_fetch_assoc($result))
			$ferror[] = "The Username You've Entered Is Already Taken, Please Try A Different Username";
		$reqFields = array_merge($reqFields,$reqRebillFields);
	}

	foreach($reqFields as $key => $val)
		if(!isset($_SESSION[$key]) || $_SESSION[$key] == "")
			$ferror[] = $val;

	if(count($ferror) == 0 && $_SESSION['stat'] != 3)
		return 1;
	else
		return $ferror;
}

function makeExpDate()
{
	$expDate = "<select name=\"mm\">";
	for($i=1;$i<13;$i++)
		$expDate .= "<option value=\"$i\">$i</option>";
	$expDate .= "</select><select name=\"yyyy\">";
	for($i=2006;$i<2020;$i++)
		$expDate .= "<option value=\"$i\">$i</option>";
	$expDate .= "</select>";
	return $expDate;
}

function getTemplate($ttype,$subact,$siteid,$userid)
{
	$qry	=	"SELECT 
				*,((`tp_rd_subAccount`='$subact')*2 + (`tp_cs_ID`='$siteid')*1) as rel 
				FROM 
				`cs_templates` 
				WHERE 
				`tp_template_type` = '$ttype' 
				AND tp_userId = '$userid' 
				ORDER BY rel DESC 
				LIMIT 1";
	$result = sql_query_read($qry)
		or dieLog(mysql_error() . "<pre>" . $qry . "</pre>");
	if($row = mysql_fetch_assoc($result))
		$fn = $row['tp_filename'];
	else
		$fn = "int_orderhead.tpl";
	return $fn;
}
$md = setMerchantDetails();
//if($_SESSION['mt_reference_id'] == 'C2B8DDD7DDDC') print_r($md["telephone"]);

//$from_url = (isset($_SERVER["HTTP_REFERER"])?quote_smart($_SERVER["HTTP_REFERER"]):"");
$url_info = parse_url($_SESSION['from_url']);

if(!$_SESSION['mt_reference_id'])
{
	$hashURL=str_replace("www.","",$url_info['host']);
	if($url_info['host']) $_SESSION['mt_reference_id']=substr(strtoupper(md5($hashURL)),0,12);
}

$order = new transaction_class($testmode);	

//if(!isset($_SESSION['orderArr']) || !$_SESSION['orderArr'])
	$_SESSION['orderArr'] = $order->buildOrderArr($_SESSION);

		
$_SESSION['cs_URL'] = $_SESSION['orderArr']['cs_URL'];


$smarty = new Smarty;
$smarty->compile_check = true;
$smarty->debugging = false;
$curtemplate = $_SESSION['gw_template'];
$smarty->template_dir = $etel_root_path."/tmpl/".$curtemplate."/";
$smarty->compile_dir = $etel_root_path."/tmpl/".$curtemplate."_c/";
$smarty->config_dir = $etel_root_path."/tmpl/".$curtemplate."/config/";
$smarty->assign("rootdir", $etel_domain_path);
$smarty->assign("tempdir", $etel_domain_path."/tmpl/".$curtemplate."/");
$smarty->assign("GET", $_GET);
$mt_language = (isset($_SESSION['mt_language']) ? quote_smart($_SESSION['mt_language']) : "eng");
if(!in_array($mt_language,array('eng','fre','ger','ita','kor','por','spa'))) $mt_language = 'eng';
$smarty->assign("tmpl_language",$mt_language);
$smarty->assign("mt_language",$mt_language);



$error = getInitError($_SESSION['mt_subAccount'],$_SESSION['orderArr']);

if(!sizeof($error))
{
	$order->init_transaction($_SESSION['orderArr']['cs_ID'],"cs.`cs_ID`",FALSE,$_SESSION['orderArr'],$md);
	$transphase = getTransPhase($order->row['websiteTable']['cs_enable_passmgmt'] == 1); // set the transaction phase...burrito
	if($order->amount-$order->customerfee<1)
		$error[] = "Charge Amount is too low. Charge values must be greater than 1.";
		
}

if(sizeof($error))
{
	$errbody = "Errors:<BR>";
	foreach($error as $err)
		if($err) $errbody .= $err."<BR>";
	toLog('error','customer', "Customer failed to view Order Page: $err<BR>".print_r($_REQUEST,true).$_SERVER['HTTP_REFERER']);
	
	$smarty->assign("body","<center>$errbody</center>");
	etel_smarty_display("int_orderhead.tpl");
	exit;
}

$template = getTemplate('order',$_SESSION['orderArr']['rd_subaccount'],$_SESSION['orderArr']['cs_ID'],$_SESSION['orderArr']['userId']);
$order->transphase = $transphase;

if(is_array($transphase))
{
	if(count($transphase) > 0)
	{
		$ermsg = "";
		foreach($transphase as $error)
			$ermsg .= $error."<br>";
		$_SESSION['response_error'] = $ermsg;
		if(!headers_sent())
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: OrderProcessing.php");
			exit();
		}
	}
	$transphase = 0;
}







if($transphase == 1)// && !$order->haserrors)
{
	$_SESSION['stat'] = 2;

			//todo
	if(strcasecmp($_SESSION['cardtype'],"wallet") == 0)
	{
		$add_fields = array();
		require_once("includes/subFunctions/banks.gkard.php");
		$gcard = new gkard_Client($testmode);
		$gcard->get_BillingInfo($_SESSION['wallet_id'],$_SESSION['wallet_pass'],$add_fields);

		$add_fields["name"] = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : $add_fields["firstname"];
		$add_fields["surname"] = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : $add_fields["lastname"];
		$add_fields["address"] = isset($_SESSION['address']) ? $_SESSION['address'] : $add_fields["address"];
		$add_fields["country"] = isset($_SESSION['country']) ? $_SESSION['country'] : $add_fields["country"];
		$add_fields["state"] = isset($_SESSION['state']) ? $_SESSION['state'] : $add_fields["state"];
		$add_fields["city"] = isset($_SESSION['city']) ? $_SESSION['city'] : $add_fields["city"];
		$add_fields["zipcode"] = isset($_SESSION['zipcode']) ? $_SESSION['zipcode'] : $add_fields["zipcode"];
		$add_fields["telephone"] = isset($_SESSION['telephone']) ? $_SESSION['telephone'] : $add_fields["telephone"];
		$add_fields["email"] = isset($_SESSION['email']) ? $_SESSION['email'] : $add_fields["email"];
	}
	$add_fields["ipaddress"] = $etel_current_ip;
	$add_fields["bank_id"] = $_SESSION['bank_id'];
	
	$_SESSION['response'] = $order->processTransaction($add_fields);
	$result = $_SESSION['response']['result'];
	
	if(isset($_SESSION['response']['result']) && $_SESSION['response']['result'] != 1)
		$ferror[] = $order->errormsg;
		
	if(count($ferror) > 0)
	{
		$ermsg = "";
		foreach($ferror as $error)
			$ermsg .= $error."<br>";
		$_SESSION['response_error'] = $ermsg;
	}
	if($_SESSION['response']['status']=='A' || ($_SESSION['response']['status']=='P' && $_SESSION['cardtype'] == "check"))
		$_SESSION['stat'] = 3;
	else if(!$_SESSION['response']['result'])
		$_SESSION['stat'] = 1;
	if(isset($_POST['crosssales']) && $_SESSION['response']['result'] == 1)
	{
		//echo "first trans number:".$order->transArr['transactionId'];
		$xorder = new transaction_class(TRUE);

		$xorderArr = $xorder->buildOrderArr($_SESSION['xSaleArr']['cs_reference_ID'],$_SESSION['xSaleArr']['rd_subName']);
		$xorder->init_transaction($xorderArr['cs_ID'],"cs.`cs_ID`",FALSE,$xorderArr,$md,TRUE,$order->transArr['transactionId']);	
		$xorder->transphase = $transphase;
		$xorder->processTransaction();
	}
}

	
if(isset($_REQUEST['submit_form']))
{
	if(!headers_sent())
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: OrderProcessing.php");
		exit();
	}

}

$product_description = quote_smart($_SESSION['mt_prod_desc']);
$blockrebill = quote_smart($_SESSION['hid_blockrebill']);
$str_firstname = quote_smart($_SESSION['firstname']);
$str_lastname = quote_smart($_SESSION['lastname']);
$str_address =  quote_smart($_SESSION['address']);
$str_country =  quote_smart($_SESSION['country']);
$str_city =  quote_smart($_SESSION['city']);
$str_state =  quote_smart($_SESSION['state']);
$str_otherstate =  quote_smart($_SESSION['otherstate']);
$str_username=  quote_smart($_SESSION['td_username']);
$str_password=  quote_smart($_SESSION['td_password']);
$str_zipcode =  quote_smart($_SESSION['zipcode']);
$str_phonenumber =  quote_smart($_SESSION['telephone']);
$str_emailaddress =  quote_smart($_SESSION['email']);
$str_additional_funds =  quote_smart($_SESSION['additional_funds']);
$ProcessingMode =  quote_smart($_SESSION['ProcessingMode']);

$totalAmt = $order->amount;

$crossit = (!isset($order->row['websiteTable']['cs_crosssale_niche']) || $order->row['websiteTable']['cs_crosssale_niche'] == 0 ? FALSE : TRUE);
if($crossit)
{
	$xSaleArr = (isset($_SESSION['xSaleArr']) ? $_SESSION['xSaleArr'] : $order->getXSales($order->row['websiteTable']['cs_crosssale_niche']));
	
	$_SESSION['xSaleArr'] = $xSaleArr;
	$xSaleDesc = 'For an additional $'.$xSaleArr['rd_initial_amount'].' get access to <a href="'.$xSaleArr['cs_URL'].'" target="_blank">'.$xSaleArr['cs_URL'].'</a> by checking this box';
	$smarty->assign("flt_xsaleamt",$xSaleArr['rd_initial_amount']);
}
if($order->customerfee)
{
	$am = (isset($_POST['crosssales']) ? (($order->amount -$order->customerfee) + $xSaleArr['rd_initial_amount']) : $order->amount-$order->customerfee);
	$smarty->assign("str_customerfee","($<span id=\"tot3\">".number_format($am, 2, '.', '')."</span> charge + $".formatMoney($order->customerfee)." processing fee)");	
}	
$bank_ids = array();
$bank_ids = merchant_getTransTypes($_SESSION['orderArr']['en_ID'],$_SESSION['orderArr']);
$bank_id = "";

$availWallets = array();

$cnt = 1;
$hasCredit = FALSE;


	
$paytype['types']['visa'] = array('key'=>'1','text'=>'Visa','bdesc'=>'Visa','type'=>'visa','disabled'=>'disabled');
$paytype['types']['mastercard'] = array('key'=>'1','text'=>'MasterCard','bdesc'=>'MasterCard','type'=>'mastercard','disabled'=>'disabled');
foreach($bank_ids as $key=>$val)
{
	if(!$_SESSION['bank_id']) $_SESSION['bank_id'] = $val['bank_id'];
	
	if(!$paytype['selected'] && !$val['bank_disabled'])
	{
		$paytype['selected'] = $val['bank_id'];
		$paytype['bdesc'] = $val['bank_billing_desc'];
	}
	
	if($_SESSION['bank_id']==$val['bank_id'] && !$val['bank_disabled'])
	{
		$paytype['selected'] = $_SESSION['bank_id'];
		$paytype['bdesc'] = $val['bank_billing_desc'];
	}
	
	if($val['bank_wallet'] != "")
		$availWallets[] = array("key"=>$val['bank_id'],"text"=>$val['bank_id']);
	
	
	
	$paytype['types'][$val['bank_type']] = array('key'=>$val['bank_id'],'text'=>$val['bank_description'],'bdesc'=>$val['bank_billing_desc'],'wallet'=>$val['bank_wallet'],'type'=>$val['bank_type']);
	
	if($val['bank_disabled'])
	{
		$paytype['types'][$val['bank_type']]['text'] .= ' (Temporarily Disabled)';
		$paytype['types'][$val['bank_type']]['disabled'] = 'disabled';
	}
	$cnt++;
}

//$paytype['types']['mastercard']['disabled'] = 'disabled';

$smarty->assign("rad_banks", $paytype);
$smarty->assign("availwallets",$availWallets);

$smarty->assign("cond_xcheck",(isset($_POST['crosssales']) ? TRUE : FALSE));
$smarty->assign("str_xsaledesc",($crossit ? $xSaleDesc : ""));

$expMonth = "";
for($i=1;$i<13;$i++) $expMonth .= "<option value=\"$i\">$i</option>";
$expYear = "";
for($i=date('Y');$i<2020;$i++) $expYear .= "<option value=\"$i\">$i</option>";
	
$smarty->assign("expmonth", $expMonth);
$smarty->assign("expyear", $expYear);

$smarty->assign("cond_hascredit", $hasCredit);
$smarty->assign("cond_crosssales", $crossit);
$smarty->assign("cs_URL", $order->row['websiteTable']['cs_name']);
$smarty->assign("str_bill_des_master", $order->row['bankTable']['bk_descriptor_master']);
$smarty->assign("str_bill_des_visa", $order->row['bankTable']['bk_descriptor_visa']);
$smarty->assign("str_description", $order->row['rebillingTable']['rd_description'] == "" ? nl2br(strip_tags($product_description)) : nl2br(strip_tags($order->row['rebillingTable']['rd_description'])));
$smarty->assign("cond_issubscription", $order->row['rebillingTable']['rd_initial_amount'] && ($order->rebill['td_enable_rebill'] > 0));
$smarty->assign("flt_initialamount", $order->amount-$order->customerfee );
$smarty->assign("cond_isrecurring", $order->row['rebillingTable']['recur_charge']>0 && $order->row['rebillingTable']['recur_charge']>0);
$smarty->assign("flt_recuramount", "$".$order->row['rebillingTable']['recur_charge']);
$smarty->assign("str_recurdays", $order->row['rebillingTable']['recur_day']);
$tc = (isset($_POST['crosssales']) ? ($totalAmt + $xSaleArr['rd_initial_amount']) : $totalAmt);
$smarty->assign("flt_totalcharge", formatMoney($tc));
$smarty->assign("str_nextdate", date("m/d/Y", ($order->rebill['nextRecurDate'])));
$smarty->assign("cond_ispasswordmanagement", $order->row['websiteTable']['cs_enable_passmgmt'] == 1);
$smarty->assign("str_ipaddress", $_SERVER['REMOTE_ADDR']);

//CreditCard
if($str_country=="")
	$str_country="United States";





$smarty->assign("cond_adult", $transaction_type == 'Adult');




$HackerSafe = "<a target='_blank' href='https://www.scanalert.com/RatingVerify?ref=www.etelegate.com'><img width='115' height='32' border='0' src='//images.scanalert.com/meter/www.etelegate.com/22.gif' alt='HACKER SAFE certified sites prevent over 99.9% of hacker crime.' oncontextmenu='alert(\"Copying Prohibited by Law - HACKER SAFE is a Trademark of ScanAlert\"); return false;'></a>";

if($_SESSION['response_error'])
	$smarty->assign("str_errormsg",$_SESSION['response_error']);
	
$smarty->assign("Bullets", $Bullets);
$smarty->assign("mt_hide_logo",$_REQUEST['mt_hide_logo']||$_SESSION['mt_hide_logo']);
$smarty->assign("HackerSafe", $HackerSafe);
$smarty->assign("str_firstname", $str_firstname);
$smarty->assign("str_lastname", $str_lastname);
$smarty->assign("str_username", $str_username);
$smarty->assign("str_password", $str_password);
$smarty->assign("str_address", $str_address);
$smarty->assign("str_country", $str_country);
$smarty->assign("str_city", $str_city);
$smarty->assign("str_state", $str_state);
$smarty->assign("str_otherstate", $str_otherstate);
$smarty->assign("str_zipcode", $str_zipcode);
$smarty->assign("str_phonenumber", $str_phonenumber);
$smarty->assign("str_emailaddress", $str_emailaddress);
$smarty->assign("str_additional_funds", $str_additional_funds);

$smarty->assign("opt_Countrys", func_get_country_select($str_country));
$smarty->assign("opt_States", func_get_state_select($str_state));
$smarty->assign("str_emailaddress", $str_emailaddress);
$smarty->assign("cond_istest",$order->test);

$ord = $smarty->fetch('int_orderprocess.tpl');
if(isset($_SESSION['response']['result']) && $_SESSION['response']['result'] == 1)
{
	$smarty->assign("str_returnurl",$order->row['websiteTable']['cs_return_page']);
	$smarty->assign("str_posted_variables",$order->buildApproval());
	
	if(strpos($order->row['websiteTable']['cs_return_page'],".htm")!=false)
		$smarty->assign("form_get_post","GET");
	else
		$smarty->assign("form_get_post","POST");
}

$smarty->assign("body",$ord);
if($_SESSION['stat'] == 2)
	$smarty->assign("body","<div align=\"center\">You Currently Have A Pending Transaction, You Must Wait For A Response From The Server</div>");
else if($_SESSION['stat'] == 3)
{
	if($order->row['rebillingTable']['rd_ibill_landing_html'])
	{
		$landingContent = $order->row['rebillingTable']['rd_ibill_landing_html'];
		toLog('notify','customer', "Customer is shown the landing page for: ".$subAcc['rd_subName'], $companyid);
		$ibill_array['CUSTADDR1']=$transInfo['address'];
		$ibill_array['CUSTADDR2']=$transInfo['reference_number'];
		$ibill_array['CUSTADDR']=$transInfo['address'];
		$ibill_array['CUSTCITY']=$transInfo['city'];
		$ibill_array['CUSTCOUNTRY']=$transInfo['country'];
		$ibill_array['CUSTEMAIL']=$transInfo['email'];
		$ibill_array['CUSTFIRSTNAME']=$transInfo['name'];
		$ibill_array['CUSTLASTNAME']=$transInfo['surname'];
		$ibill_array['CUSTPHONE']=$transInfo['phonenumber'];
		$ibill_array['CUSTSTATE']=$transInfo['state'];
		$ibill_array['CUSTZIP']=$transInfo['zipcode'];
		$ibill_array['DESC']=$transInfo['productdescription'];
		$ibill_array['EMAIL']=$transInfo['email'];
		$ibill_array['FIRSTNAME']=$transInfo['name'];
		$ibill_array['LASTNAME']=$transInfo['surname'];
		$ibill_array['REBILL']=$transInfo['td_enable_rebill'];
		$ibill_array['REMOTEIP']=$transInfo['ipaddress'];
		$ibill_array['STATE']=$transInfo['state'];
		$ibill_array['STATEDESC']=$transInfo['state'];
		$ibill_array['TRANS']=$transInfo['reference_number'];
		$ibill_array['TRAN']=$transInfo['reference_number'];
		$ibill_array['firstname']=$transInfo['name'];		
		foreach($ibill_array as $key=>$data)
			$landingContent = preg_replace("/%%".$key."[?]/i",$data,$landingContent);
		die($landingContent);
	}
	$ord = $smarty->fetch('int_orderapproval.tpl');
	$smarty->assign("body",$ord);
}
else 
	if($_SESSION['stat'] == 4)
		$smarty->assign("body","<div align=\"center\">Your Check Has Been Accepted And Is Now Awaiting Approval. This process may take up to 6 days");
$custom = FALSE;




	
etel_smarty_display("int_orderhead.tpl");

//etel_smarty_display('int_order_header.tpl');

//etel_smarty_display('int_order_creditcard.tpl');

//etel_smarty_display('int_footer.tpl');
//echo $_SESSION['companyid'];
?>