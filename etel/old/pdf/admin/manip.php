<?php

//$noHeaderOutput = true;
$etel_debug_mode = 1;
include("includes/sessioncheck.php");
$headerInclude = "companies";
include("includes/header.php");
set_time_limit(0);
require_once("../includes/subscription.class.php");
require_once("../includes/transaction.class.php");
//require_once("manip2.php");
ignore_user_abort(1);

$RF = new rates_fees();


$sql = "SELECT *
FROM `cs_transactiondetails`
LEFT JOIN cs_profit_action ON pa_trans_ID = transactionID
WHERE `userId` =141705
AND pa_ID IS NULL ";
echo "$sql<br>";
$result = sql_query_read($sql) or dieLog($sql);
while($transInfo = mysql_fetch_assoc($result))
	$transIDs[]=$transInfo;
foreach($transIDs as $transInfo)
{
	$r = $RF->update_transaction_profit($transInfo['transactionId']);
	if($r['status']!='success')
		print_r($r );
	echo($j." ");
	$j++;
}
die();

$bk_array = array(
	15 => .3787,
	20 => .1910,
	22 => .1520,
	23 => .1520,
	24 => .1520,
	28 => .5756,
	29 => .5756,
	29 => .20,
	
	32 => .2376,
	33 => .2376,
	37 => .1055,
	38 => .1055
);
foreach ($bk_array as $bankID=>$rate)
{
	//$bankID = 15;
	$dateEntered = "2007-03-01";
	//$rate = .3787;
	
	$sql = "select pa_en_ID as en_ID, sum(pt_amount) as amt,
	(select en_ID from cs_entities where en_type_ID = $bankID and en_type = 'bank') as bank_en_ID
	from cs_profit_action
	left join cs_profit on pa_ID = pt_action_ID and pt_type = 'Sale Funds'
	where pa_bank_ID in ($bankID) and pa_type='Transaction'
	group by pa_en_ID
	
	";
	echo $sql;
	$result = sql_query_read($sql) or dieLog(mysql_error().$sql);
	while($profitInfo = mysql_fetch_assoc($result))
	{
		$sql = "select pa_ID from cs_profit_action pas where pas.pa_en_ID = ".$profitInfo['en_ID']." and pas.pa_bank_id = $bankID and pas.pa_date = '$dateEntered' and pas.pa_type = 'Withheld'";
		$result2 = sql_query_read($sql) or dieLog(mysql_error().$sql);
		$old_pa_ID = @mysql_result($result2,0,0);
		$data = array();
		$data['type'] = 'Withheld';
		$data['date_entered'] = $dateEntered;
		$data['description'] = "Withheld by Bank";
		$data['en_ID'] = $profitInfo['en_ID'];
		$data['bank_ID'] = $bankID;
		
		$transfer = array();
		$transfer['amount'] = $profitInfo['amt']*($rate);
		$transfer['from_entity'] = $profitInfo['en_ID'];
		$transfer['to_entity'] = $profitInfo['bank_en_ID'];	
		$transfer['date_effective'] = $dateEntered;
		$transfer['transfer_type'] = 'Bank Withheld';
		$data['transfers'][] = $transfer;
		if($transfer['amount']>10)
		{
			if($old_pa_ID) 
			{
				echo "<BR>Undoing $old_pa_ID<BR>";
				print_r($RF->undo_transfer($old_pa_ID));
				$data['pa_ID'] = $old_pa_ID;
			}
			$pa_ID = $RF->commit_transfer($data,true);
			
			print_r($profitInfo);
			print_r($data);
			echo "<BR><BR>";
		}
		else
			echo "Skipped<BR>";
	}
}

die();
// Add wire fees!
$RF = new rates_fees();
$sql = " select * from cs_profit_action
	left join cs_profit on pa_ID = pt_action_ID and pt_type = 'Funds Transfer Fee'
where pa_type='Payout' and pt_ID is null 
";
$result = sql_query_read($sql) or dieLog(mysql_error().$sql);
while($invoiceInfo = mysql_fetch_assoc($result))
{
	$en_ID = $invoiceInfo['pa_en_ID'];
	$entityInfo = en_get_general_info($en_ID);
	$ratesInfo = $RF->get_MerchantRates($en_ID,array(0));
	// Wire/ACH Fee
	$usefee = 'wirefee';
	if($entityInfo['en_info']['Payment_Data']['Method']=='ACH') $usefee = 'achfee';
	$amount = $ratesInfo[0]['default']['Processor'][$usefee];
	if(!$amount) $amount = 50;
	$sql = " insert into cs_profit set 
		pt_amount = '$amount',
		pt_date_effective = '".$invoiceInfo['pa_date']."',
		pt_action_ID = '".$invoiceInfo['pa_ID']."',
		pt_to_entity_ID = '2',
		pt_from_entity_ID = '$en_ID',
		pt_type = 'Funds Transfer Fee'
	";
	sql_query_read($sql) or dieLog(mysql_error().$sql);
}
die();
$sql = "Select en.en_ID, en.en_company, `mi_ID` , `mi_company_id` , `mi_date` , `mi_paydate`, `mi_notes` , `mi_title` , `mi_balance` , `mi_deduction` , `mi_status`  from cs_merchant_invoice as mi left join cs_entities as en on en_type='merchant' and en_type_ID =  mi_company_id and mi_status in ('WireSent', 'WireSuccess') ";
$result = sql_query_read($sql) or dieLog(mysql_error().$sql);
while($invoiceInfo = mysql_fetch_assoc($result))
{
	$data = array();
	$data['type'] = 'Payout';
	$data['date_entered'] = $invoiceInfo['mi_paydate'];
	$data['description'] = $invoiceInfo['mi_title'];
	$data['en_ID'] = $invoiceInfo['en_ID'];
	
	$transfer = array();
	$transfer['amount'] = floatval($invoiceInfo['mi_balance']);
	$transfer['from_entity'] = $invoiceInfo['en_ID'];
	$transfer['to_entity'] = 2;	
	$transfer['date_effective'] = $invoiceInfo['mi_paydate'];
	$transfer['transfer_type'] = 'Payout';
	if($invoiceInfo['mi_notes']) $data['information']['Notes'] = $invoiceInfo['mi_notes'];
	$data['transfers'][] = $transfer;
	$RF = new rates_fees();
	$pa_ID = $RF->commit_transfer($data,true);

	$sql = "
		Update 
			cs_profit_action
		Set
			pa_status = 'pa_status'
		Where
			pa_ID = '$pa_ID'
		";
		
	sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	
}

/*
delete
FROM `cs_profit`
WHERE `pt_type` = 'Payout';

delete
FROM `cs_profit_action`
WHERE pa_type = 'Payout'
*/

die();
$sql = "Select 
*
 from cs_companydetails
 ";

$result = sql_query_read($sql) or dieLog($sql);
while($companyInfo = mysql_fetch_assoc($result))
{
	$method = 'Wire';
	if(in_array($companyInfo['bank_country'],array('US','USA','select'))) {$method = 'ACH';$companyInfo['bank_country']='US';}

	
	$update = 
	array(
		'General_Info'=>
		array(
			'Company_Url'=> $companyInfo['url1'],
			'Company_Legal_Name'=> $companyInfo['legal_name'],
			'Company_Address'=> $companyInfo['physical_address'],
			'Company_Fax_DBA'=> $companyInfo['fax_dba'],
			'Incorporated_Country'=> $companyInfo['incorporated_country'],
			'Incorporated_Number'=> $companyInfo['incorporated_number'],
			'Sex'=> $companyInfo['ssex'],
			'Date_of_Birth'=> $companyInfo['sdateofbirth'],
			'Address'=> $companyInfo['sAddress'],
			'Zip_Code'=> $companyInfo['sPostCode'],
			'City'=> $companyInfo['city'],
			'State'=> $companyInfo['company_bank'],
			'Country'=> $companyInfo['country'],
			'Company_Tech_Contact'=> $companyInfo['technical_contact_details'],
			'Contact_Phone'=> $companyInfo['contact_phone'],
			'Cell_Phone'=> $companyInfo['cellular'],
			'Contact_IM'=> $companyInfo['cd_contact_im'],
			'Time_Zone'=> $companyInfo['cd_timezone'],
			'Contact_Fax'=> $companyInfo['sFax'],
			'Personal_Phone'=> $companyInfo['sResidenceTelephone'],
			'Hear_About_Us'=> $companyInfo['how_about_us']
		),
		'Processing_Info'=>
		array(
			'Transaction_Type'=> $companyInfo['transaction_type'],
			'Service_List'=> $companyInfo['goods_list'],
			'Anti_Fraud_System'=> $companyInfo['current_anti_fraud_system'],
			'CS_Program'=> $companyInfo['customer_service_program'],
			'Refund_Policy'=> $companyInfo['refund_policy'],
			'Volume_Last_month'=> $companyInfo['volume_last_month'],
			'Volume_Prev_30Days'=> $companyInfo['volume_prev_30days'],
			'Volume_Prev_60Days'=> $companyInfo['volume_prev_60days'],
			'Volume_Forcast_1Month'=> $companyInfo['forecast_volume_1month'],
			'Volume_Forcast_2Month'=> $companyInfo['forecast_volume_2month'],
			'Volume_Forcast_3Month'=> $companyInfo['forecast_volume_3month'],
			'Projected_Monthly_Sales'=> $companyInfo['volumenumber'],
			'Average_Ticket_Price'=> $companyInfo['avgticket'],
			'Chargeback_%'=> $companyInfo['chargebackper'],
			'Previous_Processor_Trans_Fee'=> $companyInfo['cd_previous_transaction_fee'],
			'Previous_Processor_Disc_Fee'=> $companyInfo['cd_previous_discount'],
			'Previous_Processing'=> $companyInfo['cd_previous_processor'],
			'Previous_Processor_Reason'=> $companyInfo['cd_processing_reason'],
			'Recur_Billing'=> $companyInfo['recurbilling'],
			'Currently_Processing'=> $companyInfo['currprocessing']
		),
		'Payment_Data'=>
		array('Method'=>$method,
			'ACH'=>
			array(
				'Bank_Name'=> $companyInfo['company_bank'],
				'Bank_Address'=> $companyInfo['bank_address'],
				'Bank_ZipCode'=> $companyInfo['bank_zipcode'],
				'Bank_City'=> $companyInfo['bank_city'],
				'Bank_State'=> $companyInfo['bank_state'],
				'Bank_Country'=> $companyInfo['bank_country'],
				'Bank_Phone'=> $companyInfo['bank_phone'],
				'Bank_Beneficiary_Name'=> $companyInfo['beneficiary_name'],
				'Bank_Account_Name'=> $companyInfo['bank_account_name'],
				'Bank_Account_Number'=> $companyInfo['bank_account_number'],
				'Bank_Routing_Number'=> $companyInfo['cd_bank_routingnumber'],
				'Bank_Additional_Notes'=> $companyInfo['cd_bank_instructions']
			),
			'Wire'=>
			array(
				'Bank_Name'=> $companyInfo['company_bank'],
				'Bank_Address'=> $companyInfo['bank_address'],
				'Bank_ZipCode'=> $companyInfo['bank_zipcode'],
				'Bank_City'=> $companyInfo['bank_city'],
				'Bank_State'=> $companyInfo['bank_state'],
				'Bank_Country'=> $companyInfo['bank_country'],
				'Bank_Phone'=> $companyInfo['bank_phone'],
				'Bank_Beneficiary_Name'=> $companyInfo['beneficiary_name'],
				'Bank_Account_Name'=> $companyInfo['bank_account_name'],
				'Bank_Account_Number'=> $companyInfo['bank_account_number'],
				'Bank_Routing_Number'=> $companyInfo['cd_bank_routingnumber'],
				'Bank_Routing_Type'=> $companyInfo['cd_bank_routingcode'],
				'Bank_Sort_Code'=> $companyInfo['bank_sort_code'],
				'Bank_VAT_Number'=> $companyInfo['VATnumber'],
				'Bank_Registration_Number'=> $companyInfo['registrationNo'],
				'Bank_Additional_Notes'=> $companyInfo['cd_bank_instructions'],
				'Intermediary_Bank_Routing_Type'=> $companyInfo['bank_IBRoutingCodeType'],
				'Intermediary_Bank_Routing_Number'=> $companyInfo['bank_IBRoutingCode'],
				'Intermediary_Bank_Name'=> $companyInfo['bank_IBName'],
				'Intermediary_Bank_City'=> $companyInfo['bank_IBCity'],
				'Intermediary_Bank_State'=> $companyInfo['bank_IBState']
			)
		)
	);
	$res = etel_update_serialized_field('cs_entities','en_info'," en_type='merchant' and en_type_ID = '".$companyInfo['userId']."'",$update);
	
	$i++;
}
echo $i;
die();
$RF = new rates_fees();
//for($i=0;$i<1000000000;$i+=10000)
//{
	$j=0;
	$bank_id = 18;
	$sql = "SELECT min( pa_trans_id ) AS mintrans
FROM `cs_profit_action`";
	$result = sql_query_read($sql) or dieLog($sql);
	$min = mysql_result($result,0,0);
	if($min<1)$min = 500000000;
	
	$sql = "SELECT transactionId,bank_id FROM `cs_transactiondetails` where 
	transactionId < $min 
	order by transactionId desc LIMIT 200000";
	echo "$sql<br>";
	$result = sql_query_read($sql) or dieLog($sql);
	while($transInfo = mysql_fetch_assoc($result))
		$transIDs[]=$transInfo;
	foreach($transIDs as $transInfo)
	{
		
		if(in_array(intval($transInfo['bank_id']),array(18,31,32,33,34,35,37,38,39,40)))
		{
		$RF = new rates_fees();
		$r = $RF->update_transaction_profit($transInfo['transactionId']);
		if($r['status']!='success')
			print_r($r );
		//print($transInfo['transactionId'].'_'.$transInfo['userId'].'_'.$transInfo['reference_number'].'_'.$j);
		echo($j." ");
		}
		$j++;
	}
	echo $j;
	//echo "<script> setTimeout(\"document.location.href =  'manip.php?".rand(1,1000)."'\",100);</script>";

?>