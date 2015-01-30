<?php 
chdir("..");
$gateway_db_select = 3;
include("includes/dbconnection.php");
include("includes/function2.php");
include("includes/integration.php");
	
$testonly = false;

$sql="SELECT * FROM `cs_companydetails` WHERE `cd_has_been_active` = 1";	

$companyResult=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");

$num = mysql_num_rows($companyResult);

$output = "Validating Teired Rates for all companys...\n\r";
$output .= "Found $num Companys to be checked.\n\r";
toLog('rebill','system', $output, -1);
while($companyInfo = mysql_fetch_assoc($companyResult))
{
	$company_id = $companyInfo['userId'];
	$sql="SELECT *
	FROM `cs_company_rates`
	WHERE `cr_userId` = $company_id";
	
	$ratesResult = mysql_query($sql) or dieLog(mysql_error(). " ~ $sql");
	
	while($rateInfo = mysql_fetch_assoc($ratesResult))
	{
		$rateInfo
		 cr_teir_top
	 cr_teir_bottom
	 
		$suboutput = "";
		$suboutput .= "-----------------------\n\r";
		$suboutput .= "Found Transaction ID '".$transaction['transactionId']."'.\n\r";


	
	}
	toLog('rebill','system', $suboutput, $newTransId);

	
	$output.=$suboutput;
}
print(nl2br($output));
?>