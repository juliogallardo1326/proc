<?php

//$noHeaderOutput = true;
$etel_debug_mode = 1;
include("includes/sessioncheck.php");
$headerInclude = "companies";
include("includes/header.php");
set_time_limit(0);
require_once("../includes/subscription.class.php");
require_once("../includes/transaction.class.php");
require_once("../includes/subFunctions/rates_fees.php");
//require_once("manip2.php");


$sql="SELECT * FROM `cs_merchant_invoice` left join cs_companydetails on mi_company_id = userId
where gateway_id in (5,6) and mi_paydate between '2006-11-16' and '2006-12-16' ";
$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
while($cs_merchant_invoice = mysql_fetch_assoc($result))
{
	$invoice = unserialize($cs_merchant_invoice['mi_pay_info']);
	$monthlyTotal +=$invoice['MonthlyFee'];
	echo $cs_merchant_invoice['userId'].",".str_replace(',','.',$cs_merchant_invoice['companyname']).",".$cs_merchant_invoice['mi_paydate'].",".$invoice['MonthlyFee']."\n";
}
	echo "Total,,,".$monthlyTotal."\n";
die();


function phpformat($str,$l)
{
	$i = 0;
	$s = '';
	if(!is_array($str)) return "'".quote_smart($str)."'";
	else
	{
		$s .= "\n".str_repeat("\t",$l)."array(\n";
		foreach($str as $k=>$d)
		{
			$s .= ($i++>0?",\n":'').str_repeat("\t",$l+1)."'$k' => ".phpformat($d,$l+1);
		}
		$s .= "\n".str_repeat("\t",$l).")";
	}
	return $s;
}

$sql = "SELECT m.en_ID AS mer, r.en_ID AS res
FROM `cs_entities` m
LEFT JOIN cs_companydetails cd ON cd.userId = m.en_type_id
AND m.en_type = 'merchant'
LEFT JOIN cs_entities r ON reseller_id = r.en_type_id
AND r.en_type = 'reseller' where r.en_ID is not null and reseller_id > 0";

$result = mysql_query($sql) or die($sql);

while($info = mysql_fetch_assoc($result))
{	

	$sql = "insert into cs_entities_affiliates 
	set ea_en_ID = '".$info['mer']."', ea_affiliate_ID = '".$info['res']."', ea_type = 'Reseller'";
	mysql_query($sql) or die($sql);

}
die();

$sql = "select * from cs_entities, cs_resellerdetails where en_type='reseller' and en_type_ID = reseller_id";

$result = mysql_query($sql) or die($sql);

while($info = mysql_fetch_assoc($result))
{	
	$update['en_info'] = @unserialize($info['en_info']);
	unset($update['en_info']['General_Info']);
	$update['en_info']['General_Info']['Address'] = $info['reseller_address'];
	$update['en_info']['General_Info']['Zip_Code'] = $info['reseller_zipcode'];
	$update['en_company'] = $info['reseller_companyname'];
	$update['en_ref'] = $info['rd_referenceNumber'];
	$update['en_info']['General_Info']['Contact_Phone'] = $info['reseller_phone'];
	$update['en_info']['General_Info']['Contact_Fax'] = $info['reseller_faxnumber'];
	$update['en_info']['General_Info']['Personal_Phone'] = $info['reseller_res_phone'];
	$update['en_info']['General_Info']['Company_Url'] = $info['reseller_url'];
	$update['en_info']['General_Info']['Monthly_Affiliate_Volume'] = $info['reseller_monthly_volume'];
	$update['en_info']['General_Info']['User_Job_Title'] = $info['reseller_jobtitle'];
	$update['en_info']['Reseller']['Default_Trans_Markup'] = '.10';
	$update['en_info']['Reseller']['Default_Disc_Markup'] = '1';
	$update['en_info']['Reseller']['Signed_Contract'] = $info['general_merchant_sign'];
	$update['en_info']['Reseller']['Completion'] = $info['rd_completion'];

	$sql = "update cs_entities set en_info = '".quote_smart(serialize($update['en_info']))."', en_company = '".quote_smart($update['en_company'])."', en_ref = '".quote_smart($update['en_ref'])."' where en_ID =".$info['en_ID'];
	mysql_query($sql) or die($sql);

}
die();

	$sql = "DELETE FROM etel_eventum.ev_issue";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$sql = "DELETE FROM etel_eventum.ev_support_email";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$sql = "DELETE FROM etel_eventum.ev_support_email_body";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	$sql = "DELETE FROM etel_eventum.ev_issue_custom_field";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	
$sql = "SELECT a.*, en_ID,FROM_UNIXTIME(a.tickets_timestamp) as time
FROM tickets_tickets a left join tickets_users on tickets_username = `tickets_users_username` left join cs_entities on en_type = 'merchant' and en_type_id = `cs_userId` where a.tickets_child =0";
$result = mysql_query($sql) or die(mysql_error());
while($ticket = mysql_fetch_assoc($result))
{
	$cat = array(1=>9,2=>12,4=>8,6=>10,0=>14,8=>10);
	$proj = array(1=>2,2=>3,4=>2,6=>3,0=>4,8=>3);
	$sql='';
	$issue = array();
	$issue['iss_id'] = $ticket['tickets_id'];
	if(!$ticket['en_ID'])
		$ticket['en_ID'] = 14218;
	$issue['iss_usr_id'] = $ticket['en_ID'];
	$issue['iss_prj_id'] = $proj[intval($ticket['tickets_category'])];
	$issue['iss_prc_id'] = $cat[intval($ticket['tickets_category'])];
	$issue['iss_sta_id'] = ($ticket['tickets_status']=='Open'?1:7);
	$issue['iss_created_date'] = $ticket['time'];
	$issue['iss_updated_date'] = $ticket['tickets_latest'];
	$issue['iss_last_response_date'] = $ticket['tickets_latest'];
	$issue['iss_first_response_date'] = $ticket['time'];
	$issue['iss_summary'] = $ticket['tickets_subject'];
	$issue['iss_description'] = $ticket['tickets_question'];
	$issue['iss_trigger_reminders'] = 0;
	$issue['iss_last_public_action_date'] = $ticket['tickets_latest'];
		
	foreach($issue as $field=>$val)
		$sql .=($sql?",\n":"")." $field = '".quote_smart($val)."' ";
	
	$sql = "INSERT IGNORE INTO etel_eventum.ev_issue SET \n $sql;\n";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	
	$sql='';
	$ref = array();
	$ref['icf_iss_id'] = $ticket['tickets_id'];
	$ref['icf_fld_id'] = 1;
	$ref['icf_value'] = $ticket['td_transactionId'];
			
	foreach($ref as $field=>$val)
		$sql .=($sql?",\n":"")." $field = '".quote_smart($val)."' ";
	
	$sql = "INSERT IGNORE INTO etel_eventum.ev_issue_custom_field SET \n $sql;\n";
	if($ticket['td_transactionId'])
		mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

$sql = "SELECT a.*, en_ID,FROM_UNIXTIME(a.tickets_timestamp) as time
FROM tickets_tickets a left join tickets_users on tickets_username = `tickets_users_username` left join cs_entities on en_type = 'merchant' and en_type_id = `cs_userId` 
where a.tickets_child >0
";
	echo "$sql\n";
$noteresult = mysql_query($sql) or die(mysql_error());
while($ticketnotes = mysql_fetch_assoc($noteresult))
{
	$sql='';
	$issuenotes = array();
	$issuenotes['sup_id'] = $ticketnotes['tickets_id'];
	$issuenotes['sup_iss_id'] = $ticketnotes['tickets_child'];
	if(!$ticketnotes['en_ID'])
		$ticketnotes['en_ID'] = 14218;
	$issuenotes['sup_usr_id'] = $ticketnotes['en_ID'];
	$issuenotes['sup_date'] = $ticketnotes['time'];
	$issuenotes['sup_ema_id'] = '2';	
	//$issuenotes['maq_status'] = 'sent';	
	$issuenotes['sup_subject'] = $ticketnotes['tickets_subject'];			
	
	foreach($issuenotes as $field=>$val)
		$sql .=($sql?",\n":"")." $field = '".quote_smart($val)."' ";

	$sql = "INSERT IGNORE INTO etel_eventum.ev_support_email SET \n $sql;\n";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");


	$sql='';
	$issuenotes = array();
	$issuenotes['seb_sup_id'] = $ticketnotes['tickets_id'];	
	$issuenotes['seb_body'] = $ticketnotes['tickets_question'];	
	$issuenotes['seb_full_email'] = $ticketnotes['tickets_question'];	
	foreach($issuenotes as $field=>$val)
		$sql .=($sql?",\n":"")." $field = '".quote_smart($val)."' ";
	$sql = "INSERT IGNORE INTO etel_eventum.ev_support_email_body SET \n $sql;\n";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
}
die();


	$sql="SELECT `ss_ID`
	FROM `cs_subscription` 
	WHERE `ss_billing_type` = 'Check'

";
$cnt = 0;
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
while($cs_subscription = mysql_fetch_assoc($result))
{
	$subs  = new transaction_class(false);
	$subs->pull_subscription($cs_subscription['ss_ID']);		
	$transInfo['ss_billing_check_routing'] = $subs->etelDecSalted($subs->row['subscriptionTable']['ss_billing_check_routing']);	
	$transInfo['ss_billing_check_account'] = $subs->etelDecSalted($subs->row['subscriptionTable']['ss_billing_check_account']);	
	
	
	if(!is_numeric($transInfo['ss_billing_check_routing']))
	{
		echo $cs_subscription['ss_ID'].': '.$transInfo['ss_billing_check_account'].' '.$transInfo['ss_billing_check_routing']."<BR>";
		echo 'Fixed: '.etelDec($transInfo['ss_billing_check_routing'])."<BR>";
		$fixed = etelDec($transInfo['ss_billing_check_routing']);
		if(is_numeric($fixed))
		{
			$ss_rebill_status = '';
			if( $subs->row['subscriptionTable']['ss_rebill_status_text']) 
				$ss_rebill_status = ", ss_rebill_status = 'active', ss_rebill_attempts = '1', ss_rebill_status_text='Fixed Encoding Error. Attempting Rebill - 100906' ";
			$sql = "update cs_subscription set ss_billing_check_routing = '".$subs->etelEncSalted($fixed)."'  $ss_rebill_status

			where ss_ID = '".$cs_subscription['ss_ID']."'";
			sql_query_write($sql) or dieLog(mysql_error(). " ~ $sql");
			$cnt++;
			//echo( $subs->row['subscriptionTable']['ss_rebill_status_text']."<BR>".$sql);
		}
		else
			echo 'Failed: '.($fixed)."<BR>";
	}

	if(!is_numeric($transInfo['ss_billing_check_account']))
	{
		echo $cs_subscription['ss_ID'].': '.$transInfo['ss_billing_check_account'].' '.$transInfo['ss_billing_check_routing']."<BR>";
		echo 'Fixed: '.etelDec($transInfo['ss_billing_check_account'])."<BR>";
		$fixed = etelDec($transInfo['ss_billing_check_account']);
		if(is_numeric($fixed))
		{
			$ss_rebill_status = '';
			if( $subs->row['subscriptionTable']['ss_rebill_status_text']) 
				$ss_rebill_status = ", ss_rebill_status = 'active', ss_rebill_attempts = '1', ss_rebill_status_text='Fixed Encoding Error. Attempting Rebill - 100906'";
			$sql = "update cs_subscription set ss_billing_check_account = '".$subs->etelEncSalted($fixed)."'  $ss_rebill_status
			where ss_ID = '".$cs_subscription['ss_ID']."'";
			sql_query_write($sql) or dieLog(mysql_error(). " ~ $sql");
			$cnt++;
			//echo( $subs->row['subscriptionTable']['ss_rebill_status_text']."<BR>".$sql);
		}
		else
			echo 'Failed: '.($fixed)."<BR>";
	}
	flush();
}	
echo $cnt;
die();

$sql = " SELECT * FROM cs_subscription AS sub LEFT JOIN cs_rebillingdetails AS r ON r.rd_subaccount = sub.ss_rebill_id LEFT JOIN cs_transactiondetails AS t ON sub.ss_transaction_id =
t.transactionid LEFT JOIN cs_companydetails AS cd on cd.userId = ss_user_ID LEFT JOIN etel_dbsmain.cs_company_sites AS cs on cs_ID = ss_site_ID WHERE 1 AND rd_enabled = 'Yes' AND activeuser = '1' AND cs_verified in
('approved','non-compliant') AND sub.ss_user_id IN
(130852,139955,137109,132248,132248,136418,137250,136324,114394,116850,137250,116137,126152,139544,137250,126402,136324,116275,130420,121493,130852,125190,136806,134900,137250,135499,137703,118966,137250,112653,136806,117533,137250,125458,137250,137250,137250,113298,139623,115007,117806,136806,118886,137109,130852,114786,116661,136806,136806,137250,119875,114836,133465,137250,137250,116337,137250,115120,137250,137250,112421,139920,114798,137494,110708,135682,137250,137109,136806,137109,125035,114706,137250,137250,137250,131596,114760,117215,119339,135682,135239,136806,137250,136039,119475,138625,135682,119724,116640,122634,117901,136806,137250,137250,136806,137250,137250,114901,123557,110745,132059,135041,137250,137250,137250,115842,119966,130852,136806,136806,137250,110758,121527,114073,117700,117176,126402,119074,134436,137250,137250,115044,115345,120309,132059,131304,136040,134815,111227,136806,137250,137250,118243,131596,126402,126402,113027,134606,136806,117700,130852,133988,115120,111019,120349,119155,130852,136806,133988,137250,116457,113697,113166,119538,111741,116335,117318,136806,136806,137250,117186,116850,137612,120469,136653,130084,121091,134606,137250,137250,137250,111409,132059,124530) AND
sub.ss_rebill_frozen IN ('nocvv2') limit 30000 ";

$result = sql_query_read($sql) or dieLog(mysql_error());
while($subInfo = mysql_fetch_assoc($result))
{
	$ss_bank_id = 37;
	if($subInfo['ss_billing_type']=='MasterCard') $ss_bank_id = 38;
	$add_data = array('billing_descriptor' => 'NovaPay');
	$subs  = new transaction_class(false);
	$subs->pull_subscription($subInfo['ss_ID']);	
	$subs->send_email('customer_newbank_notification',$add_data);
	$sql = "Update cs_subscription set ss_rebill_frozen = 'no', ss_bank_id = '$ss_bank_id' where ss_ID = '".$subInfo['ss_ID']."'";
	//echo $sql.'<br>';
	sql_query_write($sql) or dieLog(mysql_error());
}
die();

	$sql="SELECT CCnumber, bankroutingcode, bankaccountnumber,transactionId
FROM cs_transactiondetails
";
$cnt = 0;
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
$unique = array();
while($transInfo = mysql_fetch_assoc($result))
{
	$key = $transInfo['CCnumber'].$transInfo['bankroutingcode'].$transInfo['bankaccountnumber'];
	
	$sql = "Update cs_transactiondetails set td_non_unique='".$unique[$key]."' where transactionId = ".$transInfo['transactionId'];
	mysql_query($sql) or die(mysql_error());
	$cnt+=mysql_affected_rows();
	
	if( !isset($unique[$key])) 
		$unique[$key] = $transInfo['transactionId'];
}
echo $cnt;
die();


$sql = "
select
	ss_billing_gkard,ss_salt,ss_ID,ss_billing_type
from 
	cs_subscription 
where
	ss_billing_gkard is not null and ss_billing_gkard != ''
	";
$result = sql_query_read($sql) or dieLog(mysql_error());

while($sub = mysql_fetch_assoc($result))
{
	$gkard = transaction_class::etelDecSalted($sub['ss_billing_gkard'],$sub['ss_salt']);
	if(!is_numeric($gkard)) $gkard = etelDec($gkard);
	
	if($GI[$gkard])
	{
		$sub['ss_billing_type'] = 'Visa';
		if(substr($GI[$gkard][0],0,1) == '5') $sub['ss_billing_type'] = 'MasterCard';
		$exp = '20'.substr($GI[$gkard][1],2,2).'-'.substr($GI[$gkard][1],0,2).'-01';
		$sql = "
		Update 
			cs_subscription 
		set 
			ss_rebill_frozen = 'nocvv2',
			ss_billing_card = '".transaction_class::etelEncSalted($GI[$gkard][0],$sub['ss_salt'])."',
			  	 ss_billing_exp = '".$exp."',
			ss_billing_type = '".$sub['ss_billing_type']."'
		where 
			ss_ID = ".$sub['ss_ID'];
			
		sql_query_read($sql) or dieLog(mysql_error());
		$ia+= mysql_affected_rows();
		$i++;
	}
	//else
	//die(print_r($sub));
}
echo "Total = $i Affected = $ia";
die();


$access = NULL;
$access['Script_Access']['Method']='All';

$routingTypes[1] = 'ABA';
$routingTypes[2] = 'SWIFT';
$routingTypes[3] = 'Chips ID';
$routingTypes[4] = 'Sort Code';
$routingTypes[5] = 'Transit Number';
$routingTypes[6] = 'BLZ Code';
$routingTypes[7] = 'BIC Code';
$routingTypes[8] = 'Other';

$sql = "SELECT * from cs_resellerdetails";
$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
while($companyInfo = mysql_fetch_assoc($result))
{
$companyInfo['username'] = strtolower($companyInfo['username']);
$companyInfo['password'] = strtolower($companyInfo['password']);
	$sql = "replace into cs_entities
		set 
			en_username = '".quote_smart($companyInfo['reseller_username'])."',
			en_password = MD5('".quote_smart($companyInfo['reseller_username'].$companyInfo['reseller_password'])."'),
			en_firstname = '".quote_smart($companyInfo['reseller_firstname'])."',
			en_lastname = '".quote_smart($companyInfo['reseller_lastname'])."',
			en_email = '".quote_smart($companyInfo['reseller_email'])."',
			en_gateway_ID = '".quote_smart($companyInfo['rd_gateway_id'])."',
			en_access = '".quote_smart(serialize($access))."',
			en_type = 'reseller',
			en_type_id = '".quote_smart($companyInfo['reseller_id'])."'

	";
	mysql_query($sql);
	echo mysql_insert_id();
}

$sql = "SELECT * from cs_companydetails";
$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
while($companyInfo = mysql_fetch_assoc($result))
{

$payment = NULL;
$payment['Payment_Data']['ACH']=
	array('Beneficiary_Name'=>$companyInfo['beneficiary_name'],
	'Bank_Account_Name'=>$companyInfo['bank_account_name'],'Bank_Account_Number'=>$companyInfo['bank_account_number'],
	'Bank_Routing_Number'=>$companyInfo['cd_bank_routingnumber'],
	'Bank_Name'=>$companyInfo['company_bank'], 'Bank_Address'=>$companyInfo['bank_address'],
	'Bank_City'=>$companyInfo['bank_city'],'Bank_State'=>$companyInfo['bank_address'],
	'Bank_Country'=>$companyInfo['bank_country'],'Bank_ZipCode'=>$companyInfo['bank_zipcode'],
	'Bank_Phone'=>$companyInfo['bank_phone'],'Bank_Additional_Notes'=>$companyInfo['cd_bank_instructions']
	);
$payment['Payment_Data']['Wire'] = $payment['Payment_Data']['ACH'];	 

if($companyInfo['cd_bank_routingcode']) $payment['Payment_Data']['Wire']['Bank_Routing_Type'] = $routingTypes[$companyInfo['cd_bank_routingcode']];
if($companyInfo['bank_sort_code']) $payment['Payment_Data']['Wire']['Iban_Number'] = $companyInfo['bank_sort_code'];
if($companyInfo['VATnumber']) $payment['Payment_Data']['Wire']['VAT_Number'] = $companyInfo['VATnumber'];
if($companyInfo['registrationNo']) $payment['Payment_Data']['Wire']['Registration_Number'] = $companyInfo['registrationNo'];

if($companyInfo['bank_IBRoutingCodeType']) $payment['Payment_Data']['Wire']['Intermediary_Bank_Routing_Type'] = $routingTypes[$companyInfo['bank_IBRoutingCodeType']];
if($companyInfo['bank_IBRoutingCode']) $payment['Payment_Data']['Wire']['Intermediary_Bank_Routing_Number'] = $companyInfo['bank_IBRoutingCode'];
if($companyInfo['bank_IBName']) $payment['Payment_Data']['Wire']['Intermediary_Bank_Name'] = $companyInfo['bank_IBName'];
if($companyInfo['bank_IBCity']) $payment['Payment_Data']['Wire']['Intermediary_Bank_City'] = $companyInfo['bank_IBCity'];
if($companyInfo['bank_IBState']) $payment['Payment_Data']['Wire']['Intermediary_Bank_State'] = $companyInfo['bank_IBState'];
$companyInfo['username'] = strtolower($companyInfo['username']);
$companyInfo['password'] = strtolower($companyInfo['password']);
	$sql = "replace into cs_entities
		set 
			en_username = '".quote_smart($companyInfo['username'])."',
			en_password = MD5('".quote_smart($companyInfo['username'].$companyInfo['password'])."'),
			en_firstname = '".quote_smart($companyInfo['first_name'])."',
			en_lastname = '".quote_smart($companyInfo['family_name'])."',
			en_email = '".quote_smart($companyInfo['email'])."',
			en_gateway_ID = '".quote_smart($companyInfo['gateway_id'])."',
			en_access = '".quote_smart(serialize($access))."',
			en_payment = '".quote_smart(serialize($payment))."',
			en_type = 'merchant',
			en_type_id = '".quote_smart($companyInfo['userId'])."'

	";
	mysql_query($sql);
	echo mysql_insert_id();
}



$sql = "SELECT * from cs_login";
$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
while($companyInfo = mysql_fetch_assoc($result))
{

$companyInfo['username'] = strtolower($companyInfo['username']);
$companyInfo['password'] = strtolower($companyInfo['password']);
	$sql = "replace into cs_entities
		set 
			en_username = '".quote_smart($companyInfo['username'])."',
			en_password = '".quote_smart($companyInfo['password'])."',
			en_email = '".quote_smart($companyInfo['li_email'])."',
			en_gateway_ID = '".quote_smart($companyInfo['li_gw_ID'])."',
			en_access = '".quote_smart(serialize($access))."',
			en_type = 'admin',
			en_type_id = '".quote_smart($companyInfo['userid'])."'

	";
	mysql_query($sql);
	echo mysql_insert_id();
}
die();
	$sql = "SELECT * from cs_transactiondetails  where transactionId = '180087511' limit 1";
	$res2 = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
	$transInfo = mysql_fetch_assoc($res2);
	
	$reparray = array('[credit_card]','[account_number]','[routing_number]');
	if(!is_numeric($transInfo['CCnumber'])) $transInfo['CCnumber'] = etelDec($transInfo['CCnumber']);
	if(!is_numeric($transInfo['bankaccountnumber'])) $transInfo['bankaccountnumber'] = etelDec($transInfo['bankaccountnumber']);
	if(!is_numeric($transInfo['bankroutingcode'])) $transInfo['bankroutingcode'] = etelDec($transInfo['bankroutingcode']);
	$serarray = array( $transInfo['CCnumber'],$transInfo['bankaccountnumber'],$transInfo['bankroutingcode']);
	
	$transInfo['td_process_query'] = str_replace($reparray,$serarray,$transInfo['td_process_query']);
	$transInfo['td_process_result'] = str_replace($reparray,$serarray,$transInfo['td_process_result']);
	etelPrint($transInfo);
	//etelPrint($transInfo['td_process_result']);
	$sql = "update cs_transactiondetails set 
	td_process_query = '".quote_smart($transInfo['td_process_query'])."',
	td_process_result = '".quote_smart($transInfo['td_process_result'])."' 
	where transactionId = '".intval($transInfo['transactionId'])."' limit 1";
	//mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
	//echo mysql_affected_rows().'<BR>';
	$affected += mysql_affected_rows();

die();
$Rates = new rates_fees();
$ratesInfo = $Rates->get_MerchantRates(1328);
$output = "<table style='report' border='1' width='100%'>\n";
$disp_array = array('trans' => 'Transaction Fee','disct' => 'Discount Rate','decln' => 'Decline Fee','refnd' => 'Refund Fee','chgbk' => 'Chargeback Fee','rserv' => 'Reserve Rate','cstsv' => 'Customer Service Fee');
$ratesInfo['Default Rates']['trans_type'] = "All other forms of Payment.";
foreach($ratesInfo as $key=>$banks)
{
	$output .= "<tr><td colspan='2'><b>Transaction Type: ".$banks['trans_type']."</b></td></tr>\n";
	foreach($banks['default']['Processor'] as $transtype=>$rate)
		if($disp_array[$transtype]) $output .= "<tr><td>".$disp_array[$transtype]."</td><td>$rate</td></tr>\n";
}
$output .= "</table >\n";
echo $output;
print_r($ratesInfo);
die();
$sql="SELECT cs_name,cs_ID 
FROM etel_dbsmain.cs_company_sites ;
";
$rep0 = array('mature'=>' Mature ','nude'=>' Nude ','teen'=>' Teen ','porn'=>' Porn ','sex'=>' Sex ','online'=>' Online ','pay'=>' Pay ','party'=>' Party ','girls'=>' Girls ','sweet'=>' Sweet ','amateur'=>' Amateur ', 'adult' => ' Adult ', 'adult' => ' Adult ','fuck'=>' Fuck ','bondage' => ' Bondage ','anal'=> ' Anal ','wife'=> ' Wife ', 'deep'=> ' Deep ');
$rep1 = array('1'=>' One ','2'=>' Two ','3'=>' Three ','4'=>' Four ','5'=>' Five ','6'=>' Six ','7'=>' Seven ','8'=>' Eight ','9'=>' Nine ','0'=>' Zero ');
$rep2 = array('.com'=>' Dot Com ','.net'=>' Dot Net ','.org'=>' Dot Org ','.'=>' Dot ');
$rep3 = array('www1.'=>'','www.'=>'','www'=>'');
$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
while($cs_company_sites = mysql_fetch_assoc($result))
{
	$cs_name = strtolower($cs_company_sites['cs_name']);
	echo($cs_name." ___________ ");
	foreach($rep0 as $key=>$data) $cs_name = str_replace($key,$data,$cs_name);
	foreach($rep3 as $key=>$data) $cs_name = str_replace($key,$data,$cs_name);
	foreach($rep1 as $key=>$data) $cs_name = str_replace($key,$data,$cs_name);
	foreach($rep2 as $key=>$data) $cs_name = str_replace($key,$data,$cs_name);
	$cs_name = ucwords(trim(preg_replace('/[^a-zA-Z]/',' ',$cs_name)));
	etelPrint($cs_name);
	$sql = "update etel_dbsmain.cs_company_sites set cs_title = '".quote_smart($cs_name)."' where cs_ID = '".$cs_company_sites['cs_ID']."' limit 1";
	mysql_query($sql);
}
die();
$sql="SELECT transactionId
FROM cs_transactiondetails limit 1
";

$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
while($transInfo = mysql_fetch_assoc($result))
{
	$sql = "SELECT transactionId, `CCnumber`, `bankaccountnumber`, `bankroutingcode`, td_process_query,td_process_result from cs_transactiondetails  where transactionId = '180093199' limit 1";
	$res2 = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
	$transInfo = mysql_fetch_assoc($res2);
	
	$reparray = array('[credit_card]','[account_number]','[routing_number]');
	if(!is_numeric($transInfo['CCnumber'])) $transInfo['CCnumber'] = etelDec($transInfo['CCnumber']);
	if(!is_numeric($transInfo['bankaccountnumber'])) $transInfo['bankaccountnumber'] = etelDec($transInfo['bankaccountnumber']);
	if(!is_numeric($transInfo['bankroutingcode'])) $transInfo['bankroutingcode'] = etelDec($transInfo['bankroutingcode']);
	$serarray = array( $transInfo['CCnumber'],$transInfo['bankaccountnumber'],$transInfo['bankroutingcode']);
	
	$transInfo['td_process_query'] = str_replace($serarray,$reparray,$transInfo['td_process_query']);
	$transInfo['td_process_result'] = str_replace($serarray,$reparray,$transInfo['td_process_result']);
	//etelPrint($transInfo);
	//etelPrint($transInfo['td_process_result']);
	$sql = "update cs_transactiondetails set 
	td_process_query = '".quote_smart($transInfo['td_process_query'])."',
	td_process_result = '".quote_smart($transInfo['td_process_result'])."' 
	where transactionId = '".intval($transInfo['transactionId'])."' limit 1";
	mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
	echo mysql_affected_rows().'<BR>';
	$affected += mysql_affected_rows();
}
die('Done: '.$affected);


// Rebill Data
$unique = array();
$file = file('./ibill/RebillData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data1)
{
	$assoc = array();
	$data = explode('|',$data1);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);

}

			print_r($assoc);die();



require_once("../includes/subFunctions/banks.ipaygate.php");
$processor = new iPayGate_Client($bankInfo);
	$sql="SELECT ss_ID
FROM cs_subscription where ss_billing_type in ('Check');
";
$cnt = 0;
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
while($cs_subscription = mysql_fetch_assoc($result))
{
	$subs  = new transaction_class(false);
	$subs->pull_subscription($cs_subscription['ss_ID']);		
	$transInfo['ss_billing_check_routing'] = $subs->etelDecSalted($subs->row['subscriptionTable']['ss_billing_check_routing']);	
	$transInfo['ss_billing_check_account'] = $subs->etelDecSalted($subs->row['subscriptionTable']['ss_billing_check_account']);	
	

	if(!is_numeric($transInfo['ss_billing_check_routing']))
	{ 
		$transInfo['ss_billing_check_routing'] = etelDec($subs->row['transactionTable']['bankroutingcode']);
		
		if(is_numeric($transInfo['ss_billing_check_routing']))
		{
			echo "update cs_subscription set ss_billing_check_routing = '".$subs->etelEncSalted($transInfo['ss_billing_check_routing'])."' 
			
			where ss_ID = '".$cs_subscription['ss_ID']."';<br>";
			
		}
		else
		{
			echo $cs_subscription['ss_ID'].' PROBLEM '.$transInfo['ss_billing_check_account'].'<BR>';
		}
	}
	else
	{

	
	}
	
	flush();
}	
die($sqlchange);


$sql = "select * from  `cs_subscription` left join cs_transactiondetails on ss_transaction_id = transactionId where ss_bank_id in (15,28,29)";
$result = mysql_query($sql) or die($sql);
while($transInfo = mysql_fetch_assoc($result))
{
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = $expDate[0];
	$expMonth = $expDate[1];
	
	$transInfo['CCnumber'] = etelDec($transInfo['CCnumber']);
	$resultinfo['P15'] = '20'.substr($resultinfo['P15'],2,4).'/'.substr($resultinfo['P15'],0,2);

	if(is_numeric($transInfo['CCnumber']))
	{
		$sql = "UPDATE cs_subscription set ss_billing_card = '".transaction_class::etelEncSalted($transInfo['CCnumber'],$transInfo['ss_salt'])."',
		 ss_billing_exp  = '$expYear-$expMonth-01',
		 ss_billing_cvv2  = '".transaction_class::etelEncSalted($transInfo['cvv'],$transInfo['ss_salt'])."' where ss_ID = '".$transInfo['td_ss_ID']."'";
		mysql_query($sql) or die($sql);
		echo mysql_affected_rows()." $sql<BR>";
	}

}
die();
/*
$sql = "Select * from etel_dbsmain.cs_company_sites";
$result=mysql_query($sql) or die($sql);
while($cs_company_sites = mysql_fetch_assoc($result))
{
	$cs_member_data= array();
	$cs_member_data['passdir']=$cs_company_sites['cs_member_datadir'];
	$sql = "update etel_dbsmain.cs_company_sites set cs_member_data = '".serialize($cs_member_data)."' where cs_ID = ".$cs_company_sites['cs_ID'];
	mysql_query($sql);
}


die();
*/
$S = new subscription_class();
$S->bulk_update();
die();


$sql = "Select * from cs_companydetails ";
$cresult = mysql_query($sql) or die(mysql_error());
while($companyInfo = mysql_fetch_assoc($cresult))
{
	if($companyInfo['bank_Creditcard']>0 && $companyInfo['cc_discountrate']>0)
	{
			$rateinfo = array('bank_id'=>$companyInfo['bank_Creditcard'],
			'default'=>
				array('Processor'=>
					array('trans'=>$companyInfo['cc_merchant_trans_fees'],'disct'=>$companyInfo['cc_merchant_discount_rate'],'decln'=>$companyInfo['cc_merchant_trans_fees'],'refnd'=>$companyInfo['cc_discountrate'],'rserv'=>$companyInfo['cc_reserve'],'chgbk'=>$companyInfo['cc_underchargeback'],'cstsv'=>$companyInfo['cc_customer_fee']
					)
				)
			);
			
			if($companyInfo['reseller_id'])
				$rateinfo['Reseller'] = array('Processor'=>
					array('trans'=>$companyInfo['cc_reseller_trans_fees'],'disct'=>$companyInfo['cc_reseller_discount_rate'],'decln'=>$companyInfo['cc_reseller_trans_fees'],'refnd'=>0,'rserv'=>0,'chgbk'=>0,'cstsv'=>0
					)
				);
				
			$sql = "Replace into cs_company_banks set userId = ".$companyInfo['userId'].",bank_id=".$companyInfo['bank_Creditcard'].",cb_config='".serialize($rateinfo)."'";
		$result = mysql_query($sql) or die(mysql_error().$sql);
	
	}
	
	if($companyInfo['bank_check']>0 && $companyInfo['ch_discountrate']>0)
	{
			$rateinfo = array('bank_id'=>$companyInfo['bank_check'],
			'default'=>
				array('Processor'=>
					array('trans'=>$companyInfo['ch_merchant_trans_fees'],'disct'=>$companyInfo['ch_merchant_discount_rate'],'decln'=>$companyInfo['ch_merchant_trans_fees'],'refnd'=>$companyInfo['cc_discountrate'],'rserv'=>$companyInfo['cc_reserve'],'chgbk'=>$companyInfo['cc_underchargeback'],'cstsv'=>$companyInfo['cc_customer_fee']
					)
				)
			);
			if($companyInfo['reseller_id'])
				$rateinfo['Reseller'] = array('Processor'=>
					array('trans'=>$companyInfo['ch_reseller_trans_fees'],'disct'=>$companyInfo['ch_reseller_discount_rate'],'decln'=>$companyInfo['ch_reseller_trans_fees'],'refnd'=>0,'rserv'=>0,'chgbk'=>0,'cstsv'=>0
					)
				);
				
			$sql = "Replace into cs_company_banks set userId = ".$companyInfo['userId'].",bank_id=".$companyInfo['bank_check'].",cb_config='".serialize($rateinfo)."'";
		$result = mysql_query($sql) or die(mysql_error().$sql);
	}
}
die();

$sql = "
SELECT td.* FROM `cs_transactiondetails` as td left join cs_companydetails as cd on td.userId = cd.userId 
WHERE  `td_recur_processed` = 0 AND  `td_enable_rebill`= '1' and
`status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' AND `td_is_chargeback` = '0' and td.userId = 123171
";

$csv = '';
$result = mysql_query($sql) or die(mysql_error());
while($transInfo = mysql_fetch_assoc($result))
{

	$transInfo=getTransactionInfo($transInfo['transactionId']);
			if(!is_numeric($transInfo['CCnumber'])) $transInfo['CCnumber'] = etelDec($transInfo['CCnumber']);
			if($transInfo['td_gcard']) $transInfo['td_gcard'] = etelDec($transInfo['td_gcard']);
			//if($transInfo['bankroutingcode']) $transInfo['bankroutingcode'] = etelDec($transInfo['bankroutingcode']);
			if($transInfo['bankaccountnumber']) $transInfo['bankaccountnumber'] = etelDec($transInfo['bankaccountnumber']);
			
	$transArray = array();
	$transArray['firstname'] = $transInfo['name'];
	$transArray['lastname'] = $transInfo['surname'];
	$transArray['address'] = $transInfo['address'];
	$transArray['city'] = $transInfo['city'];
	$transArray['state'] = $transInfo['state'];
	$transArray['zip'] = $transInfo['zipcode'];
	$transArray['country'] = $transInfo['country'];
	$transArray['UserName'] = $transInfo['td_username'];
	$transArray['Password'] = $transInfo['td_password'];
	$transArray['emailaddress'] = $transInfo['email'];
	$transArray['cardnumber'] = $transInfo['CCnumber'];
	$transArray['cardexpires'] = $transInfo['validto'];
	$transArray['AccountNumber'] = $transInfo['bankaccountnumber'];
	$transArray['RoutingNumber'] = $transInfo['bankroutingcode'];
	$transArray['CheckMICRLine'] = $transInfo[''];
	$transArray['RebillStartDate'] = date('Y-m-d',strtotime($transInfo['td_recur_next_date'])-($transInfo['subAcc']['recur_day']*60*60*24));
	$transArray['recurringperiod'] = $transInfo['subAcc']['recur_day'];
	$transArray['price'] = $transInfo['subAcc']['recur_charge'];
	$transArray['nextbilldate'] = $transInfo['td_recur_next_date'];
	$transArray['revshare_id'] = $transInfo[''];
	$transArray['Subscription_id'] = $transInfo['td_subscription_id'];
	//print_r($transArray);
	$datas = array();
foreach($transArray as $key=>$data)
	$datas[] =  '"'.$data.'"';	
	$csv .= implode(',',$datas)."\n";
	

}
foreach($transArray as $key=>$data)
	$keys[] = '"'.$key.'"';
	$csv = "\n".implode(',',$keys)."\n".$csv;
	echo $csv;
die();
$sql = "
SELECT td.* FROM `cs_transactiondetails` as td 
WHERE `td_recur_processed` = 0 AND 
`status`= 'A' AND `td_rebillingID` >0 
";



$result = mysql_query($sql) or die(mysql_error());
while($transInfo = mysql_fetch_assoc($result))
{
	
	$transInfo=getTransactionInfo($transInfo['transactionId']);
	$salt = md5(time()+rand(0,2000));
	$ss_billing_type = 'Credit';
	
	if($transInfo['checkorcard']=='C') $ss_billing_type = 'Check';
	if($transInfo['cardtype'] == 'Visa') $ss_billing_type = 'Visa';
	else if($transInfo['cardtype'] == 'Master') $ss_billing_type = 'Mastercard';
	else if($transInfo['checkorcard']=='C') $ss_billing_type = 'Check';
	
	if(!is_numeric($transInfo['bankaccountnumber'])) $transInfo['bankaccountnumber'] =eteldec($transInfo['bankaccountnumber']);
	if(!is_numeric($transInfo['CCnumber'])) $transInfo['CCnumber'] =eteldec($transInfo['CCnumber']);
	$ss_account_status = 'inactive';
	$ss_rebill_status = 'inactive';
	$ss_rebill_status_text = 'Rebill Subscription Canceled/Inactive';
	if($transInfo['td_enable_rebill']) $ss_rebill_status = 'active';
	if($transInfo['td_enable_rebill']) $ss_rebill_status_text = '';
	if(!$transInfo['td_subscription_id']) $transInfo['td_subscription_id'] = genRefId("subscription","S");
	
	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	
	$expMonth = $expDate[1];
	
	$sql = "
	insert into `cs_subscription`  set 
	 `ss_subscription_ID`='".$transInfo['td_subscription_id']."',
	 `ss_billing_firstname`='".$transInfo['name']."',
	 `ss_billing_mi`='".$transInfo['']."',
	 `ss_billing_lastname`='".$transInfo['surname']."',
	 `ss_billing_address`='".$transInfo['address']."',
	 `ss_billing_address2`='".$transInfo['']."',
	 `ss_billing_city`='".$transInfo['city']."',
	 `ss_billing_state`='".$transInfo['state']."',
	 `ss_billing_country`='".$transInfo['country']."',
	 `ss_billing_zipcode`='".$transInfo['zipcode']."',
	 `ss_billing_last_ip`='".$transInfo['ipaddress']."',
	 `ss_billing_card`='".transaction::etelEncSalted($transInfo['CCnumber'],$salt)."',
	 `ss_billing_card_type`='".$ss_billing_card_type."',
	 `ss_billing_gkard`='".transaction::etelEncSalted($transInfo['td_gcard'],$salt)."',
	 `ss_billing_type`='".$ss_billing_type."',
	 `ss_billing_exp`='".date("Y-m-d",mktime(0,0,0,$expMonth,1,$expYear))."',
	 `ss_billing_cvv2`='".transaction::etelEncSalted($transInfo['cvv'],$salt)."',
	 `ss_billing_check_account`='".transaction::etelEncSalted($transInfo['bankaccountnumber'],$salt)."',
	 `ss_billing_check_routing`='".transaction::etelEncSalted($transInfo['bankroutingcode'],$salt)."',
	 `ss_salt`='".$salt."',
	 `ss_cust_email`='".$transInfo['email']."',
	 `ss_cust_phone`='".$transInfo['phonenumber']."',
	 `ss_cust_username`='".$transInfo['td_username']."',
	 `ss_cust_password`='".$transInfo['td_password']."',
	 `ss_rebill_ID`='".$transInfo['td_rebillingID']."',
	 `ss_rebill_next_date`='".$transInfo['td_recur_next_date']."',
	 `ss_rebill_amount`='".$transInfo['chargeAmount']."',
	 `ss_rebill_status`='".$ss_rebill_status."',
	 `ss_rebill_status_text`='".$ss_rebill_status_text."',
	 `ss_rebill_attempts`='".$transInfo['td_recur_attempts']."',
	 `ss_rebill_count`='".$transInfo['td_recur_num']."',
	 `ss_account_status`='".$ss_account_status."',
	 `ss_account_start_date`='".$transInfo['transactionDate']."',
	 `ss_account_expire_date`='".$transInfo['td_recur_next_date']."',
	 `ss_transaction_id`='".$transInfo['transactionId']."',
	 `ss_last_rebill`='".$transInfo['transactionDate']."',
	 `ss_productdescription`='".$transInfo['productdescription']."',
	 `ss_site_ID` ='".$transInfo['td_site_ID']."',
	 `ss_user_ID` ='".$transInfo['userId']."'
	
	";

	die($sql);
}
/*
set_time_limit(0);
	$sql="SELECT CCnumber,transactionId
FROM cs_transactiondetails
";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
while($transInfo = mysql_fetch_assoc($result))
{
	if( isset($unique[$transInfo['CCnumber']])) $unique[$transInfo['CCnumber']]++;
	else $unique[$transInfo['CCnumber']]=0;
	$sql = "Update cs_transactiondetails set td_non_unique='".$unique[$transInfo['CCnumber']]."' where transactionId = ".$transInfo['transactionId'];
	//die($sql);
	mysql_query($sql) or die(mysql_error());
}
*/
die();
/* 
$sql = "SELECT * FROM `cs_rebillingdetails` WHERE `rd_ibill_landing_html` is not null" ;
$result = mysql_query($sql);
while($subAcc = mysql_fetch_assoc($result))
{
	$landingContent = $subAcc['rd_ibill_landing_html'];
	if($landingContent)
	{
		//$sql = "update `cs_rebillingdetails` set rd_ibill_landing_html = '".quote_smart($landingContent)."' where rd_subName = '".trim($subAcc['rd_subName'])."';" ;
		//echo "$sql\n";
		echo $landingContent;
		//mysql_query($sql) or dieLog($sql);
	}
		//toLog('error','customer', "Landing page failed to display for: ".$subAcc['rd_subName']." HTML:".$subAcc['rd_ibill_landing_html'], $companyid);
}
die();
*/
set_time_limit(0);
/*
$sql = "SELECT a.tickets_child, COUNT( * ) AS ticket_total, MAX( tickets_timestamp ) as max ,  SUBSTRING( MAX( CONCAT( LPAD( `tickets_id` , 8, '0' ) , `tickets_admin` ) ) , 9 ) AS admin
FROM tickets_tickets a where a.tickets_child !=0
GROUP BY tickets_child ";
$result = mysql_query($sql) or die(mysql_error());
while($ticket = mysql_fetch_assoc($result))
{
	echo 2;
	if($ticket['admin']!='Closed')
	{
		if($ticket['admin']=='Admin')
		{
			echo 1;
			mysql_query("Update tickets_tickets set tickets_responses='".$ticket['ticket_total']."', tickets_latest='".$ticket['max']."', tickets_status = 'Answered' where tickets_id = ".$ticket['tickets_child']) or die(mysql_error());
		
		}
		else 
		{
			mysql_query("Update tickets_tickets set tickets_responses='".$ticket['ticket_total']."', tickets_latest='".$ticket['max']."', tickets_status = 'Open' where tickets_id = ".$ticket['tickets_child']) or die(mysql_error());
			echo 3;
		}
	}

}
die();
*/
/*
$file = file('./ibill/ClientLookupData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
		
	$subAccount = $assoc['account']."-00".$assoc['sub_account'];
				
	$assoc_lookup[$subAccount]=$assoc;
}
*/
/*

$sub_account_list = array();
$file = file('./ibill/ClientAccountData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();

$update_user = array();

foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
		
	$sql_pricepoint = array();
	$sql_website = array();
	
	
				$subAccount = $assoc['ItemAccount']."-00".$assoc['ItemSubAccount'];
				$userId = $assoc_lookup[$subAccount]['sponsored_merchant_id'];

				//$sql_pricepoint['userId'] = $assoc['ItemUsesCodes']; // 0
				//$sql_pricepoint['userId'] = $assoc['ItemUsesNames']; // 0
				$sql_pricepoint['PaymentFactor'] = 100*(1-$assoc['PaymentFactor']); // 0.86809999999999998
				$sql_pricepoint['checkPaymentFactor'] = 100*(1-$assoc['checkPaymentFactor']); // 0.84999999999999998
				$sql_pricepoint['HoldFactor'] = 100*($assoc['HoldFactor']); // 0.84999999999999998
				//$sql_website['userId'] = $assoc['ItemUsesPM']; // 0
				if($update_user[$userId]['cc_merchant_discount_rate']<$sql_pricepoint['PaymentFactor']) 
					$update_user[$userId]['cc_merchant_discount_rate'] = $sql_pricepoint['PaymentFactor'];
				if($update_user[$userId]['ch_merchant_discount_rate']<$sql_pricepoint['checkPaymentFactor']) 
					$update_user[$userId]['ch_merchant_discount_rate'] = $sql_pricepoint['checkPaymentFactor'];
				if($update_user[$userId]['cc_total_discount_rate']<$sql_pricepoint['PaymentFactor']) 
					$update_user[$userId]['cc_total_discount_rate'] = $sql_pricepoint['PaymentFactor'];
				if($update_user[$userId]['ch_total_discount_rate']<$sql_pricepoint['checkPaymentFactor']) 
					$update_user[$userId]['ch_total_discount_rate'] = $sql_pricepoint['checkPaymentFactor'];
				if($update_user[$userId]['cc_reserve']<$sql_pricepoint['HoldFactor']) 
					$update_user[$userId]['cc_reserve'] = $sql_pricepoint['HoldFactor'];
				if($update_user[$userId]['ch_reserve']<$sql_pricepoint['HoldFactor']) 
					$update_user[$userId]['ch_reserve'] = $sql_pricepoint['HoldFactor'];
}

	foreach($update_user as $userId=>$data)
	{
		   $sql = NULL;
		   foreach($data as $key=>$data)
		   		$sql .= ($sql?',':'')."$key = '". mysql_real_escape_string($data)."'";
		   $sql = 'update cs_companydetails set  '.$sql.' where userId = "'.$userId.'";';
		   if($userId) mysql_query($sql) ;
		   echo $sql."<BR>";
	}
die();
*/
/*
$file = file('./ibill/ApprovalPages');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
		
	$subAccount = $assoc['account']."-00".$assoc['sub_account'];
				
	$assoc_lookup[$subAccount]=$assoc;
}

die();
*/
/*
$sub_account_list = array();
$file = file('./ibill/PMPincodeData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = mysql_real_escape_string( trim($data[$row]));
	
	//CodeAccount|CodeSubAccount|CodeNumber|Code|CodeUsed
	$subAccount = $assoc['CodeAccount']."-00".$assoc['CodeSubAccount'];
	//$cs_company_ID = $assoc_lookup[$subAccount]['sponsored_merchant_id'];
	
	//$sql = "insert into cs_pincodes set pc_subName = '$subAccount',  pc_type  = 'pincode', pc_code = '".$assoc['Code']."', pc_used = '".$assoc['CodeUsed']."'";
		$sql = "insert into cs_pincodes set pc_subName = '$subAccount',  pc_type  = 'userpass', pc_code = '".$assoc['NameUsername']."',  pc_pass = '".$assoc['NamePassword']."', pc_used = '".$assoc['NameUsed']."'";
	mysql_query($sql) or die(mysql_error());
	
}

die();

$file = file('./ibill/ClientSmidData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
	
	$sql_assoc = array();
	
			//$sql_assoc['userId'] = $assoc_lookup[$assoc['Sponsored_Merchant_ID']]['account'];
			$sql_assoc['userId'] = $assoc['Sponsored_Merchant_ID'];
			
			$sql_assoc['companyname'] = $assoc['Company_Name'];
			if(strlen($sql_assoc['companyname'])<2) $sql_assoc['companyname'] = $sql_assoc['userId'];
			$sql_assoc['username'] = substr(strtolower(preg_replace('/[^a-zA-Z0-9]/','',$sql_assoc['companyname'])),0,12);
			$sql_assoc['password'] = substr(md5(rand(0,3432423243)),0,6);
			$sql_assoc['transaction_type'] = 'adlt';
			$sql_assoc['how_about_us'] = 'Transfered from IBill';
            $sql_assoc['first_name'] = $assoc['Principal_First_Name']; // Michael
            $sql_assoc['family_name'] = $assoc['Principal_Last_Name']; // Michael
            $sql_assoc['ReferenceNumber'] = $sql_assoc['userId']; // Michael
            $sql_assoc['gateway_id'] = 5; // Michael
            $sql_assoc['bank_Creditcard'] = 28; // Michael
			
			  	   
			
			$sql_assoc['email'] = $assoc['Contact_Email'];
            $sql_assoc['phonenumber'] = $assoc['Contact_Phone']; // 574-272-6344
            $sql_assoc['customer_service_phone'] = $assoc['Contact_Phone']; // 574-272-6344
            $sql_assoc['contact_phone'] = $assoc['Contact_Phone']; // 574-272-6344
            $sql_assoc['sresidencetelephone'] = $assoc['Contact_Phone']; // 574-272-6344
            $sql_assoc['fax_number'] = $assoc['Contact_Fax']; // 
            $sql_assoc['sfax'] = $assoc['Contact_Fax']; // 
            $sql_assoc['merchantName'] = $assoc['Principal_First_Name'].' '.$assoc['Principal_Last_Name']; // Michael
            $sql_assoc['contactname'] = $assoc['Principal_First_Name'].' '.$assoc['Principal_Last_Name']; // Michael
            $sql_assoc['stitle'] = $assoc['Principal_title']; // 
            $sql_assoc['address'] = $assoc['Principal_Street_Address_1'].' '.$assoc['Principal_Street_Address_2']; // 52285 Lookout Pointe CT
            $sql_assoc['city'] = $assoc['Principal_City']; // Granger
            $sql_assoc['state'] = $assoc['Principal_State']; // IN
            $sql_assoc['zipcode'] = $assoc['Principal_Zip']; // 46530
            $sql_assoc['country'] = $assoc['Principal_Country']; // US
            //$sql_assoc['userId'] = $assoc['DBA']; // The model show
            $sql_assoc['bank_address'] = $assoc['Payout_Address_1'].' '.$assoc['Payoutl_Address_2']; // 52285 Lookout Pointe CT
            $sql_assoc['bank_city'] = $assoc['Payout_City']; // Granger
            //$sql_assoc['userId'] = $assoc['Payout_State']; // IN
            $sql_assoc['bank_zipcode'] = $assoc['Payout_Zip']; // 46530
            $sql_assoc['bank_country'] = $assoc['Payout_Country']; // US
            $sql_assoc['bank_account_number'] = $assoc['Payout_Account_Number']; // 1407089
            $sql_assoc['bank_swift_code'] = $assoc['Payout_Routing_Number']; // 071212128
            //$sql_assoc['userId'] = $assoc['Payout_Method']; // ACH
            //$sql_assoc['userId'] = $assoc['']; // 
            //$sql_assoc['userId'] = $assoc['Payment_Method_Code']; // 
            //$sql_assoc['userId'] = $assoc['Rec_Financial_Instit_Qual']; // 
            //$sql_assoc['userId'] = $assoc['Receiver_Account_Type']; // 
            $sql_assoc['company_bank'] = $assoc['Beneficiary_Bank_Name']; // 
            $sql_assoc['bank_address'] = $assoc['Beneficiary_Bank_Addr']; // 
            $sql_assoc['bank_city'] = $assoc['Beneficiary_Bank_City']; // 
            //$sql_assoc['userId'] = $assoc['Beneficiary_Bank_State']; // 
            $sql_assoc['bank_zipcode'] = $assoc['Beneficiary_Bank_Zip']; // 
            $sql_assoc['bank_country'] = $assoc['Beneficiary_Bank_Country']; // 
            $sql_assoc['bank_account_number'] = $assoc['Beneficiary_Bank_Acct_No']; // 
            $sql_assoc['bank_swift_code'] = $assoc['Beneficiary_Bank_ABA_No']; // 
            $sql_assoc['bank_IBName'] = $assoc['Intermediary1_Bank_Name']; // 
           // $sql_assoc['userId'] = $assoc['Intermediary1_Bank_Addr']; // 
            $sql_assoc['bank_IBCity'] = $assoc['Intermediary1_Bank_City']; // 
            $sql_assoc['bank_IBState'] = $assoc['Intermediary1_Bank_State']; // 
           // $sql_assoc['userId'] = $assoc['Intermediary1_Bank_Zip']; // 
            //$sql_assoc['userId'] = $assoc['Intermediary1_Bank_Country']; // 
            $sql_assoc['bank_IBRoutingCode'] = $assoc['Intermediary1_Bank_ABA_No']; // 
           // $sql_assoc['userId'] = $assoc['Intermediary1_Bank_ID_Code']; // 
           // $sql_assoc['userId'] = $assoc['Intermediary1_Bank_RFD_Code']; // 
		   //print_r($sql_assoc);
		   $sql = NULL;
		   foreach($sql_assoc as $key=>$data)
		   		$sql .= ($sql?',':'')."$key = '". mysql_real_escape_string($data)."'";
		   $sql = 'insert into cs_companydetails set date_added = now(), '.$sql;
		   if($sql_assoc['userId']) mysql_query($sql) ;
		  // print(mysql_error());
	
}

*/
/*
$sub_account_list = array();
$file = file('./ibill/ClientAccountData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data)
{
	$assoc = array();
	$data = explode('|',$data);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
		
	$sql_pricepoint = array();
	$sql_website = array();
				$subAccount = $assoc['ItemAccount']."-00".$assoc['ItemSubAccount'];
				$userId = $assoc_lookup[$subAccount]['sponsored_merchant_id'];
				//$sql_pricepoint['userId'] = $assoc['']; // pay
				//$sql_pricepoint['userId'] = $assoc['CardsSupported']; // 1
				//$sql_pricepoint['userId'] = $assoc['CheckHoldFactor']; // 0.10000000000000001
				//$sql_pricepoint['userId'] = $assoc['CheckTrialAmount']; // .0000
				//$sql_pricepoint['userId'] = $assoc['CheckTrialLength']; // 0
				//$sql_pricepoint['userId'] = $assoc['ChecksRebill']; // 
				//$sql_pricepoint['userId'] = $assoc['ChecksRebillCycle']; // 
				//$sql_pricepoint['userId'] = $assoc['HoldFactor']; // 5.0000000000000003E-2
				//$sql_pricepoint['userId'] = $assoc['IsNotify']; // 
				//$sql_pricepoint['userId'] = $assoc['ItemAdult']; // 1
				$sql_pricepoint['rd_initial_amount'] = $assoc['ItemAmount']; // 49.9500
				$sql_pricepoint['rd_trial_days'] = $assoc['ItemOneTimeDuration']; // 0
				//$sql_pricepoint['userId'] = $assoc['ItemReBill']; // 1
				$sql_pricepoint['recur_day'] = $assoc['ItemReBillCycle']; // 90
				$sql_pricepoint['rd_description'] = $assoc['ItemReBillPeriod']; // 90 days
				$sql_pricepoint['recur_charge'] = $assoc['ItemRecurringFee']; // 49.9500
				$sql_pricepoint['rd_subName'] = $subAccount; // 246
				$sql_pricepoint['rd_trial_days'] = $assoc['ItemTrialPeriod']; // 
			    $sql_pricepoint['company_user_id'] = $userId; // 0130
				//$sql_pricepoint['userId'] = $assoc['ItemUsesCodes']; // 0
				//$sql_pricepoint['userId'] = $assoc['ItemUsesNames']; // 0
				//$sql_pricepoint['userId'] = $assoc['PaymentFactor']; // 0.86809999999999998
				//$sql_pricepoint['userId'] = $assoc['checkPaymentFactor']; // 0.84999999999999998
				//$sql_website['userId'] = $assoc['ItemUsesPM']; // 0
				$sql_website['cs_support_email'] = $assoc['ItemCustServiceEmail']; // hausawebmaster@yahoo.com
				$sql_website['cs_support_phone'] = $assoc['CustServPhone']; // 4074937678
				$sql_website['cs_notify_retry'] = $assoc['HTTPPostRetries']; // 
				$url_info = parse_url($assoc['ItemURL']);
				
				$hashURL=str_replace("www.","",$url_info['host']);
			    $sql_website['cs_reference_ID'] = substr(strtoupper(md5($hashURL)),0,12); // 0130
			    $sql_website['cs_company_id'] = $userId; // 0130
				$sql_website['cs_URL'] = $url_info['scheme'].'://'.$url_info['host']; // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_name'] = $url_info['host']; // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_order_page'] = $assoc['ItemURL']; // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_return_page'] = $assoc['ItemURL']; // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_member_url'] = $assoc['ItemURL']; // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_enable_passmgmt'] = intval($assoc['ItemUsesPM']); // http://www.siriusquest.com/red/pussy/pussy.html
				$sql_website['cs_notify_pass'] = $assoc['Notification_Password']; // 
				$sql_website['cs_notify_url'] = $assoc['Notification_URL']; // 
				$sql_website['cs_notify_eventurl'] = $assoc['Notification_URL']; // 
				$sql_website['cs_notify_eventuser'] = $assoc['Notification_User']; // 
				$sql_website['cs_notify_event'] = 0; //  
				$sql_website['cs_notify_key'] = $assoc['NotifyEncryptionKey']; // 
				$sql_website['cs_member_secret'] = $assoc['NotifyEncryptionKey']; // 
				$sql_website['cs_gatewayId'] = 5; // 
				$sql_website['cs_return_page'] = $assoc['RedirectURL']; // 
				$sql_website['cs_echeck'] = 1; // 
				$sql_website['cs_web900'] = 1; // 
				$sql_website['cs_creditcards'] = 1; // 
				$sql_website['cs_reason'] = 'Transfered from IBILL'; // 
				$sql_website['cs_creditcards'] = 1; // 
				$sql_website['cs_verified'] = 'approved'; // 
				
		   $sql = NULL;
		   foreach($sql_website as $key=>$data)
		   		$sql .= ($sql?',':'')."$key = '". mysql_real_escape_string($data)."'";
		   $sql = 'insert into etel_dbsmain.cs_company_sites set cs_created = now(), '.$sql;
		  //$result = mysql_query($sql) ;
		   //if(mysql_error()) print(mysql_error()." ~ $sql");

			$sql = 'select cs_ID from etel_dbsmain.cs_company_sites where cs_reference_ID = "'.$sql_website['cs_reference_ID'].'"';
			$result = mysql_query($sql) ; 
			
			$sql_website['cs_ID'] = mysql_result($result,0,0);
			
		   $sql = NULL;
		   foreach($sql_pricepoint as $key=>$data)
		   		$sql .= ($sql?',':'')."$key = '". mysql_real_escape_string($data)."'";
		   $sql = 'insert into cs_rebillingdetails set '.$sql;
		 // $result = mysql_query($sql) ;
		  
		  
			$sql = 'select rd_subaccount from cs_rebillingdetails where rd_subName = "'.$sql_pricepoint['rd_subName'].'"';
			$result = mysql_query($sql) ; 
			
			$sql_pricepoint['rd_subaccount'] = mysql_result($result,0,0);
			
			
		  //if(mysql_error()) print(mysql_error()." ~ $sql");
		  
		  $sub_account_list[$sql_pricepoint['rd_subName']] = array('site'=>$sql_website,'subaccount'=>$sql_pricepoint);
	
}

	*/

// Rebill Data
$unique = array();
$file = file('./ibill/RebillData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data1)
{
	$assoc = array();
	$data = explode('|',$data1);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
			//print_r($assoc);die();
			$sql_trans = array();
			
			$sql_trans['CCnumber'] = etelEnc($assoc['RebillCardNumber']); // 5.9500
			$sql_trans['validupto'] = '20'.substr($assoc['RebillCardExpires'],2,4).'/'.substr($assoc['RebillCardExpires'],0,2); // 5.9500

			$sql = "update cs_transactiondetails set validupto = '".$sql_trans['validupto']."' where CCnumber = '".$sql_trans['CCnumber']."'";
			//echo($sql."<BR>");
			if($sql_trans['CCnumber']) mysql_query($sql) ;
			else echo 'blank';
			if(mysql_error()) echo(mysql_error()." ~ $sql<BR>");
			if(rand(1,50)==4) sleep(1);
			echo mysql_affected_rows();
			flush();
}






//print_r($assoc_final);
die();

/*
// Historic
$unique = array();
$file = file('./ibill/HistoricalData.txt');
$keys = explode('|',$file[0]);
unset($file[0]);
$assoc_final = array();
foreach($file as $data1)
{
	$assoc = array();
	$data = explode('|',$data1);
	foreach($keys as $row=>$key)
		$assoc[trim($key)] = trim($data[$row]);
		
			$sql_trans = array();
			$subAccount = $assoc['Account']."-00".$assoc['Sub_Account'];
			$userId = $assoc_lookup[$subAccount]['sponsored_merchant_id'];
			$sql_trans['userId'] = $userId; // 128685
			//$sql_trans['rd_initial_amount'] = $assoc['Sub_Account']; // 102
			//$sql_trans['transactionId'] = $assoc['trans_id']; // 178215591
			$sql_trans['reference_number'] = $assoc['trans_id']; // 178215591
			$sql_trans['td_subscription_id'] = $assoc['subscription_id']; // 1041413478
			$sql_trans['transactionDate'] = date('y-m-d g:i:s',strtotime(substr($assoc['Trans_Date'],0,-4))); // 2006-01-01 03:39:32.000
			if($assoc['network']=='credit card') $assoc['network'] = 'H';
			if($assoc['network']=='check') $assoc['network'] = 'C';			
			$sql_trans['checkorcard'] = $assoc['network']; // credit card
			$sql_trans['td_is_a_rebill'] = $assoc['Source']=='rebiller'; // gkard
			//$sql_trans['rd_initial_amount'] = $assoc['Authorized']; // 1
			$sql_trans['CCnumber'] = $assoc['Card_Number']; // 5.9500
			$sql_trans['amount'] = $assoc['Trans_Amount']; // 5.9500
			$sql_trans['td_customer_fee'] = $assoc['Trans_Fee']; // 5.9500
			$sql_trans['name'] = $assoc['First_Name']; // 5.9500
			$sql_trans['surname'] = $assoc['Last_Name']; // 5.9500
			$sql_trans['email'] = $assoc['Email']; // 5.9500
			$sql_trans['ipaddress'] = $assoc['ip_address']; // 172.195.216.249
			$sql_trans['td_process_result'] = $assoc['Response_Code'].' '.$assoc['Log_Error'].' '.$assoc['Detailed_Response_Code']; // 000
			//$sql_trans['rd_initial_amount'] = $assoc['Log_Error']; // GKard::APPROVED 654321
			//$sql_trans['rd_initial_amount'] = $assoc['Detailed_Response_Code']; // 
			$sql_trans['td_site_ID'] = $sub_account_list[$subAccount]['site']['cs_ID']; // 5.9500
			$sql_trans['td_rebillingID'] = $sub_account_list[$subAccount]['subaccount']['rd_subaccount']; // 5.9500
			
			//$sql_trans['amount'] = $assoc['Trans_Amount']; // 5.9500
			//$sql_trans['amount'] = $assoc['Trans_Amount']; // 5.9500
			$sql_trans['productdescription'] = $data1; // 5.9500
			
			
			if(isset($unique[$sql_trans['ipaddress']])) $unique[$sql_trans['ipaddress']]++;
			else $unique[$sql_trans['ipaddress']] = 0;
			  	  
			$sql_trans['td_non_unique'] = $unique[$sql_trans['ipaddress']];
			
			$sql_trans['td_merchant_paid'] = 1; // 5.9500
			$sql_trans['td_merchant_deducted'] = 1; // 5.9500
			$sql_trans['td_reseller_paid'] = 1; // 5.9500
			$sql_trans['td_reseller_deducted'] = 1; // 5.9500
			$sql_trans['td_bank_paid'] = 1; // 5.9500
			$sql_trans['td_bank_deducted'] = 1; // 5.9500
			$sql_trans['td_bank_decline'] = 1; // 5.9500
			$sql_trans['td_bank_recieved'] = 1; // 5.9500
			$sql_trans['td_merchant_fields'] = 1; // 5.9500
			$sql_trans['td_bank_invoice'] = 1; // 5.9500
			$sql_trans['td_recur_processed'] = 1; // 5.9500
			$sql_trans['cardtype'] = 'visa'; // 5.9500
            $sql_trans['bank_id'] = 28; // Michael

						
			$sql_trans['status'] = 'A'; // sale
			   $sql = NULL;

			if($assoc['Trans_Type']=='refund')
			{
				$sql_trans['cancelstatus'] = 'Y'; // 172.195.216.249
				$sql_trans['cancellationDate'] = $sql_trans['transactionDate']; // 172.195.216.249
			}			
			
			if($assoc['Trans_Type']=='chargeback')
			{
				$sql_trans['cancellationDate'] = $sql_trans['transactionDate']; // 172.195.216.249
				$sql_trans['td_is_chargeback'] = 1; // 172.195.216.249
			}
			foreach($sql_trans as $key=>$data)
				$sql .= ($sql?',':'')."$key = '". mysql_real_escape_string($data)."'";
			$sql = "insert IGNORE into cs_transactiondetails set $sql";
			if(1) mysql_query($sql) ;
			if(mysql_error()) echo(mysql_error()." ~ $sql<BR>");
}






//print_r($assoc_final);
die();

*/
/*

$sql = "SELECT * FROM `cs_transactiondetails` WHERE transactionId in (28074,29796,29797,29798,29799,29800,29801,29802,29803,29804,29805,29806,29807,29808,29809,29810,29811,29812,29813,29814,29815,29816,29817,29818,29819,29820,29821,29822,29823,29824,29825,29826,29827,29828,29829,29830,29831,29832,29833,29834,29835,29836,29837,29838,29839,29840,29841,29842,29843,29844,29845,29846,29847,29848,29849,29850,29851,29852,29853,29854,29855,29856,29857,29858,29859,29860,29861,29862,29863,29864,29865,29866,29867,29868,29869,29870,29871,29872,29873,29874,29875,29876,29877,29878,29879,29880,29881,29882,29883,29884,29885,29886,29887,29888,29889,29890,29891,29892,29893,29894,29895,29896,29897,29898,30995,30999,31708,31694,31697,31796,31777,31727,31784,31749,31790,31810,31751,31743,31772,31748,31715,31791,31679,31760,31801,31726,31763,31669,31707,31672,31717,31765,31674,31684,31664,31699,31770,31807,31731,31774,31808,31689,31676,31813,31809,31769,31806,31734,31812,31698,31666,31767,31688,31776,31658,31696,31663,31702,31766,31764,31798,31803,31690,31762,31683,31732,31706,31733,31788,31692,31799,31677,31678,31747,31792,31744,31693,31735,31758,31680,31789,31705)";
$mode="Live";
$result = mysql_query($sql) or dieLog(mysql_error());

while($transInfo = mysql_fetch_assoc($result))
{

	$sql="SELECT * FROM `cs_companydetails` as c left join `etel_dbsmain`.`cs_company_sites` as s on s.cs_company_id = c.`userId` WHERE c.`userId` = '".$transInfo['userId']."' and s.`cs_ID` = '".$transInfo['td_site_ID']."'";
	$comresult=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	if(mysql_num_rows($comresult)<1 && $mode=="Live") {die("Invalid Company/Website $sql");}
	$companyInfo = mysql_fetch_assoc($comresult);	

	$trans_mode = 'cc';
	$payment_type = "Account Number (Last 4 Digits)";
	if($transInfo['checkorcard'] != 'H') {$trans_mode = 'ch';}
	func_update_rate($transInfo['userId'],&$transInfo,$cnn_cs,$trans_mode,"Live");
	
	
		$email_to = $transInfo['email'];

		if($transInfo['status'] == 'A')
		{
		// Email
			
			$email_to = $transInfo['email'];
			$useEmailTemplate = "customer_recur_subscription_confirmation_cc";
			if($transInfo['td_enable_rebill'] == 0) $useEmailTemplate = "customer_order_confirmation_cc";

			if($transInfo['td_one_time_subscription']) $useEmailTemplate = "customer_subscription_confirmation_cc";
			if($transInfo['td_is_a_rebill'] == 1) $useEmailTemplate = "customer_rebill_confirmation_cc";
			$data = array();
			$data['payment_type'] = $payment_type;
			$data['billing_descriptor'] = $transInfo['billing_descriptor'];
			$data['site_URL'] = $companyInfo['cs_URL'];
			$data['reference_number'] = $transInfo['reference_number'];
			$data['full_name'] = $transInfo['surname'].", ".$transInfo['name'];
			$data['email'] = $email_to;
			$data['customer_email'] = $email_to;
			$data['credit_card_formatted'] = $credit_card_formatted;
			$data['amount'] = "$".formatMoney($transInfo['amount']-$transInfo['td_customer_fee'])." USD";
			$data['customer_fee'] = "$".formatMoney($transInfo['td_customer_fee'])." USD";
			$data['final_amount'] = "$".formatMoney($transInfo['amount'])." USD";
			$data['username'] = $transInfo['td_username'];
			$data['password'] = $transInfo['td_password'];
			$data['payment_schedule'] = $transInfo['payment_schedule'];
			$data['transaction_date'] = date("F j, Y",strtotime($transInfo['transactionDate']));
			$data['next_bill_date'] = $transInfo['nextDateInfo'];
			$data['site_access_URL'] = $companyInfo['cs_member_url'];
			$data['customer_support_email'] = $companyInfo['cs_support_email'];
			$data['tmpl_language'] = $_SESSION['tmpl_language'];
			$data['gateway_select'] = $companyInfo['gateway_id'];

			if($transInfo['td_send_email'] == 'yes')
			{
	
				$str_is_test = "THIS IS A TEST TRANSACTION ";
				if($mode=="Live") $str_is_test = "";
				send_email_template($useEmailTemplate,$data,$str_is_test); // Send Customer Email.
				if($mode=="Live" && $bankInfo['bk_cc_bank_enabled']==1)
				{	
					$data['email'] = $bankInfo['bank_email'];
					send_email_template($useEmailTemplate,$data,"(Bank Copy) ");
				}
				if($companyInfo['cd_recieve_order_confirmations'])
				{	
					$data['email'] = $companyInfo['cd_recieve_order_confirmations'];
					send_email_template($useEmailTemplate,$data,$str_is_test."(Merchant Copy) ");
				}
			}
			// End Email
		}
}






*/






die();

$forcetronixDefaults['cd_pay_bimonthly']=0;
$forcetronixDefaults['cc_visa_billingdescriptor']="WM-TEL_877-884-5514 SINGAPORE";
$forcetronixDefaults['cc_master_billingdescriptor']="WM-TEL_877-884-5514 SINGAPORE";
$forcetronixDefaults['cc_discountrate']=5;
$forcetronixDefaults['ch_discountrate']=5;
$forcetronixDefaults['web_discountrate']=5;
$forcetronixDefaults['cc_reserve']=10;
$forcetronixDefaults['ch_reserve']=10;
$forcetronixDefaults['web_reserve']=10;
$forcetronixDefaults['cd_paydelay']=21;
$forcetronixDefaults['cs_monthly_charge']=59;
$forcetronixDefaults['cc_overchargeback']=150;
$forcetronixDefaults['cc_underchargeback']=50;
$forcetronixDefaults['cd_paydaystartday']=5;
$forcetronixDefaults['cd_payperiod']=7;
$forcetronixDefaults['cd_paystartday']=1;
$forcetronixDefaults['cd_paydelay']=25;
$forcetronixDefaults['cd_orderpage_useraccount']=0;
die(serialize($forcetronixDefaults));
die();
$EuroPay['cc_visa_billingdescriptor']="www.wcspay.com";
$EuroPay['cc_master_billingdescriptor']="www.wcspay.com";
die(serialize($EuroPay));
$checkGateway['ch_billingdescriptor']="Etelegate.net*8885571548";
die(serialize($checkGateway));
die();

$sql = "Select * from cs_bank_invoice where bi_bank_id = 15";
$result = mysql_query($sql) or die(mysql_error());
while($invoiceInfo = mysql_fetch_assoc($result))
{
	$bi_pay_info = unserialize($invoiceInfo['bi_pay_info']);
	$bi_pay_info['ProfitMode']['all'] = $bi_pay_info['Sales'] + $bi_pay_info['MiscFees'] - ($bi_pay_info['Deductions'] + $bi_pay_info['RefundAmount'] + $bi_pay_info['RollingReserve']);
	$bi_pay_info['ProfitMode']['profit'] = $bi_pay_info['Profit'] - ($bi_pay_info['Deductions']);
	$bi_pay_info['ProfitMode']['realprofit'] = $bi_pay_info['ProfitMode']['profit']-$bi_pay_info['ResellerDiscountFees'];

	$sql = "UPDATE `cs_bank_invoice` set bi_pay_info='".serialize($bi_pay_info)."' WHERE bi_ID=".$invoiceInfo['bi_ID'];

	print $dif;
	print_r($bi_pay_info);
	mysql_query($sql) or die(mysql_error());
}

print "<pre>";
$forcetronixDefaults['cd_pay_bimonthly']=0;
$forcetronixDefaults['cc_visa_billingdescriptor']="Forcetronix.com";
$forcetronixDefaults['cc_master_billingdescriptor']="Forcetronix.com";
$forcetronixDefaults['cc_discountrate']=5;
$forcetronixDefaults['ch_discountrate']=5;
$forcetronixDefaults['web_discountrate']=5;
$forcetronixDefaults['cc_reserve']=10;
$forcetronixDefaults['ch_reserve']=10;
$forcetronixDefaults['web_reserve']=10;
$forcetronixDefaults['cd_paydelay']=28;
$forcetronixDefaults['cs_monthly_charge']=59;
$forcetronixDefaults['cc_overchargeback']=150;
$forcetronixDefaults['cc_underchargeback']=50;
$forcetronixDefaults['cd_paydaystartday']=5;
$forcetronixDefaults['cd_payperiod']=7;
$forcetronixDefaults['cd_paystartday']=1;
$forcetronixDefaults['cd_paydelay']=25;
$forcetronixDefaults['cd_orderpage_useraccount']=1;
die(serialize($forcetronixDefaults));

die();
$sql = "Select * from cs_bank_invoice where bi_bank_id=15";
$result = mysql_query($sql) or die(mysql_error());
while($invoiceInfo = mysql_fetch_assoc($result))
{
	$bi_pay_info = unserialize($invoiceInfo['bi_pay_info']);

	$title = date('l, F j, Y',strtotime($invoiceInfo[bi_date]))." - $".round($bi_pay_info[TotalProfit],2);
	print("
	$title

	Sales = 		".round($bi_pay_info[Sales],2)."

	Profit = 		".round($bi_pay_info[Profit],2)."
	Deductions = 	  -	".round($bi_pay_info[Deductions],2)."
	                        _________
	Final Profit =		".round($bi_pay_info[TotalProfit],2)."




	");
	$sql = "UPDATE `cs_bank_invoice` set bi_title='$title' WHERE bi_ID=".$invoiceInfo['bi_ID'];
	mysql_query($sql) or die(mysql_error());

	$total += $bi_pay_info[TotalProfit];
}

print("Total Profit = 	$total");
print "</pre>";
die();


$sql = "Select * from cs_bank_invoice";
$result = mysql_query($sql) or die(mysql_error());
while($invoiceInfo = mysql_fetch_assoc($result))
{
	$bi_pay_info = unserialize($invoiceInfo['bi_pay_info']);
	print_r($bi_pay_info);
	$bi_pay_info['BankHighRiskDiscountFees_Num']+=$bi_pay_info['BankLowRiskDiscountFees_Num'];
	$bi_pay_info['BankLowRiskDiscountFees_Num']=0;
	$dif = ($bi_pay_info['BankLowRiskDiscountFees']/3.55)*5.50-$bi_pay_info['BankLowRiskDiscountFees'];
	$bi_pay_info['BankHighRiskDiscountFees'] += $dif+$bi_pay_info['BankLowRiskDiscountFees'];
	$bi_pay_info['BankLowRiskDiscountFees'] = 0;
	$bi_pay_info['TotalProfit'] -= $dif;
	$bi_pay_info['Deductions'] +=$dif;
	$sql = "UPDATE `cs_bank_invoice` set bi_pay_info='".serialize($bi_pay_info)."' WHERE bi_ID=".$invoiceInfo['bi_ID'];

	print $dif;
	print_r($bi_pay_info);
	mysql_query($sql) or die(mysql_error());
}


print "</pre>";
die();
$sql = "SELECT reseller_id,reseller_email FROM `cs_resellerdetails` WHERE reseller_sendmail=0";

$result = mysql_query($sql) or dieLog(mysql_error());

while($companyInfo = mysql_fetch_assoc($result))
{
	addListEmail($companyInfo['reseller_email'],"Company Email is Unsubscribed",$companyInfo['reseller_id'],'reseller','unsubscribe');
}
die();
$sql = "SELECT * FROM `cs_transactiondetails` WHERE 1";

$result = mysql_query($sql) or dieLog(mysql_error());

while($transInfo = mysql_fetch_assoc($result))
{
die();
	$P3 = "";
	parse_str(trim($transInfo['td_process_result']));
	if(!$P3) $response['td_gcard']="NULL" ;
	else $response['td_gcard'] = "'".etelEnc($P3)."'";
	$sql = "UPDATE `cs_transactiondetails` set td_gcard=".$response['td_gcard']." WHERE transactionId=".$transInfo['transactionId'];
	mysql_query($sql) or dieLog(mysql_error());
}

/*
$sql = "SELECT

userId,

volumenumber,

volume_last_month,

volume_prev_30days,

volume_prev_60days,

forecast_volume_1month,

forecast_volume_2month,

forecast_volume_3month

FROM `cs_companydetails` WHERE 1";

$result = mysql_query($sql) or dieLog(mysql_error());

while($Info = mysql_fetch_assoc($result))
{
	$userid = $Info['userId'];
	unset($Info['userId']);
	foreach($Info as $key=>$data)
	{

		$valArray = explode("-",$data);
		$val = $valArray[1];
		if(!$val) $val = $valArray[0];
		$val=str_replace("$","",$val);
		$val=str_replace(",","",$val);
		$val=str_replace("5 Mil+","10000000",$val);
		$val=str_replace("1Mil","1000000",$val);
		$val=str_replace("2Mil","2000000",$val);
		$val=str_replace("5Mil","5000000",$val);
		print intval($val)." ".$Info[$key]."<BR>";
		$Info[$key] = $val;
		$sql = "UPDATE `cs_companydetails` set `$key`='$val' WHERE userId='$userid'";
		mysql_query($sql) or dieLog(mysql_error());

	}

		$max = $Info['forecast_volume_1month'];
		if($max<25000) $max = 25000;
		$sql = "UPDATE `cs_companydetails` set `cd_max_volume`='$max' WHERE userId='$userid'";
		mysql_query($sql) or dieLog(mysql_error());
}
/*
		print "<option value='select'>Select</option>";
		print "<option value='2500' ".($volume<5000?"selected":"").">0-$5,000</option>";
		print "<option value='10000' ".($volume<10000&&$volume>=5000?"selected":"").">$5,000-$10,000</option>";
		print "<option value='25000' ".($volume<25000&&$volume>=10000?"selected":"").">$10,000-$25,000</option>";
		print "<option value='50000' ".($volume<50000&&$volume>=25000?"selected":"").">$25,000-$50,000</option>";
		print "<option value='100000' ".($volume<100000&&$volume>=50000?"selected":"").">$50,000-$100,000</option>";
		print "<option value='250000' ".($volume<250000&&$volume>=100000?"selected":"").">$100,000-$250,000</option>";
		print "<option value='500000' ".($volume<500000&&$volume>=250000?"selected":"").">$250,000-$500,000</option>";
		print "<option value='1000000' ".($volume<1000000&&$volume>=500000?"selected":"").">$500,000-1MIL</option>";
		print "<option value='2000000' ".($volume<2000000&&$volume>=1000000?"selected":"").">1Mil-2Mil</option>";
		print "<option value='5000000' ".($volume<5000000&&$volume>=2000000?"selected":"").">2Mil-5Mil</option>";
		print "<option value='10000000' ".($volume>=5000000?"selected":"").">5 Mil+</option>";


*/
?>