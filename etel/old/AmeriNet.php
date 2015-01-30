<?
$etel_debug_mode = 1;

require_once("includes/integration.php");
require_once("includes/dbconnection.php");

function Get_Bank_Information($bank_name)
{
	$qry_company="SELECT * FROM cs_bank WHERE bank_name = '$bank_name';";
	$bank_details=sql_query_read($qry_company) or dieLog("Cannot execute query");
	return mysql_fetch_assoc($bank_details);
}

$bankInfo = Get_Bank_Information("BankAN30 (Checks)");
$transInfo = getTransactionInfo("ND46691PF",false,"reference_number");

$processor = new AmeriNet_Client($bankInfo);

echo "<pre>";
print_r($processor->ExecutePeekUserExists("ND46691PF"));
print_r($r = ch_AmeriNet_integration($transInfo,$bankInfo,NULL));
echo "</pre>";



?>