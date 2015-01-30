<?
$etel_debug_mode = 1;
session_start();
$_SESSION['etel_trans_pending'] = FALSE;

require_once("includes/function.php");
require_once("includes/integration.php");
require_once("includes/dbconnection.php");
require_once("includes/transaction.class.php");
require_once("includes/processor.class.php");
require_once("includes/subscription.class.php");

/*
$s = new subscription_class();
$s->bulk_update();
exit();
*/
/*
subscription_add_note("S2FD4FFED0C0732316","test");
print_r(subscription_get_notes("S2FD4FFED0C0732316"));

exit();
*/
/*
require_once("includes/subFunctions/banks.checkgateway.php");

$bankInfo = bank_GetByID("32");
$processor = new CheckGateway_Client($bankInfo,"Live");
$log = $processor->process_transactions();
echo $log;
*/

require_once("includes/SOAP/Client.php");
require_once("includes/subFunctions/banks.ipaygate.php");

$bankInfo = bank_GetByID(33);

$trans = new transaction_class();
$trans->pull_transaction("10001722");
$processor = new iPayGate_Client($bankInfo);

$transInfo = $trans->row['transactionTable'];
$params = array(
"customerpaymentpagetext" => "0001ETELEG01",
"orderDescription" => "0001etelegate.net 1800-6760127", 
"currencyText" => "USD", 
"taxAmount" => "0",
"purchaseAmount" => "1.95", 
"cardHolderName" => "fdgsgsd sgddstgsdg", 
"cardNo" => "4444333322221111", 
"cardTypeText" => "visa", 
"securityCode" => "123", 
"cardExpireMonth" => "08", 
"cardExpireYear" => "2020"
);

$res = $processor->Execute_Sale($params);


echo "<pre>";
print_r($res);
echo "</pre>";
/*

$compinfo = merchant_getInfo(1328);
$contract = genMerchantContract($compinfo);
echo $contract['et_htmlformat'];
*/

//////////////////////////////////////////
$query_info = "<table width='800px'><tr><td><font style='font-size:8pt;'>";
if(isset($etel_query_info['results']))
{
	$total_time = 0;
	foreach($etel_query_info['results'] as $index => $info)
	{
		$query_info .= "<p>Query " . $info['sql'] . " took " . $info['duration'] . " seconds</p>";
		$total_time += $info['duration'];
	}
	$query_info .= "<p>Total Time: " . $total_time . "seconds</p>";
}
$query_info .= "</font></td></tr></table>";

echo $query_info;

?>