<?php 
chdir("..");
die(1);
$gateway_db_select = 3;
include("includes/dbconnection.php");
include("admin/includes/mailbody_replytemplate.php");
require_once("includes/function.php");
include("includes/function2.php");
include("includes/integration.php");
	
$testonly = false;
if($_GET['test']) $testonly  = true;
$etel_disable_fraud_scrubbing = true;
$attempts_max=3;

$sql = $_GET['sql'];

set_time_limit(0);

$sql="SELECT td.* FROM `cs_transactiondetails` as td left join cs_companydetails as cd on td.userId = cd.userId 
WHERE activeuser = 1 and `td_recur_attempts` < $attempts_max AND `td_recur_processed` = 0 AND 
`status`= 'A' AND `td_rebillingID` <> -1 AND `cancelstatus` = 'N' AND `td_is_chargeback` = '0'
AND `td_recur_next_date`<=CURDATE() $sql order by td_recur_attempts asc limit 100";	
if($sql) echo $sql;
$transactions=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");

$num = mysql_num_rows($transactions);

$output = "Running a search for all recurring transactions to be billed today...\n\r";
$output .= "Found $num Transactions to be rebilled.\n\r";
toLog('rebill','system', $output, -1);
while($transaction = mysql_fetch_assoc($transactions))
{
	

		// Grab transaction info.
	$oldTransId = $transaction['transactionId'];
	$newTransId = NULL;
	
	$transactionInfo=getTransactionInfo($oldTransId);
	
	$suboutput = "";
	$suboutput .= "-----------------------\n\r";
	$suboutput .= "Found Transaction ID '".$transactionInfo['reference_number']."'.\n\r";
	
	if($transactionInfo['td_recur_processed'] || $transactionInfo['cancelstatus'] != 'N' || $transactionInfo['td_is_chargeback'] || $transactionInfo['chargeAmount']<3.95)
	{
		$suboutput .= "Transaction Cannot be Processed. Ignoring this Transaction. Charge=".$transactionInfo['chargeAmount']."\n\r";
		$sql= "UPDATE `cs_transactiondetails` SET `td_enable_rebill` = '0', `td_recur_processed` = '1' WHERE `transactionId` = '$oldTransId' LIMIT 1"; 
		if(!$testonly) $result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
		//toLog('erroralert','customer',"Shouldn't happen: ".$suboutput);
	}
	else
	{
		if(!$transactionInfo['td_enable_rebill'])
		{
			$suboutput .= "Transaction has expired on '".$transactionInfo['td_recur_next_date']."'\n\r";
			$sql= "UPDATE `cs_transactiondetails` SET `td_recur_processed` = '1' WHERE `transactionId` = '$oldTransId' LIMIT 1"; 
			if(!$testonly) $result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");

		// Expiration Notification

			if(!$testonly) Process_Transaction($transactionInfo['transactionId'],"expiration");

		// Email
			
			sendTransactionEmail($transactionInfo['transactionId'],"customer_expire_confirmation",$testonly);
/*			
			$useEmailTemplate = "customer_expire_confirmation";
			
			$data['site_URL'] = $transactionInfo['cs_URL'];
			$data['reference_number'] = $transactionInfo['reference_number'];
			$data['full_name'] = $transactionInfo['surname'].", ".$transactionInfo['name'];
			$data['customer_email'] = $transactionInfo['email'];
			$data['email'] = $transactionInfo['email'];
			$data['amount'] = "$".formatMoney($transactionInfo['amount']-$transactionInfo['td_customer_fee'])." USD";
			$data['customer_fee'] = "$".formatMoney($transactionInfo['td_customer_fee'])." USD";
			$data['final_amount'] = "$".formatMoney($transactionInfo['amount'])." USD";
			$data['transaction_date'] = date("F j, Y",strtotime($transactionInfo['transactionDate']));
			$data['customer_support_email'] = $transactionInfo['cs_support_email'];
			$data['password'] = $transactionInfo['td_password'];
			$data['payment_schedule'] = $transactionInfo['payment_schedule'];
			$data["gateway_select"] = $transactionInfo['gateway_id'];
			if(!$testonly) send_email_template($useEmailTemplate,$data,""); // Send Customer Email.

			if($transactionInfo['cd_recieve_order_confirmations'])
			{	
				$data['email'] = $transactionInfo['cd_recieve_order_confirmations'];
				if(!$testonly) send_email_template($useEmailTemplate,$data,"( Merchant Copy) ");
			}
			$data['email'] = "support@etelegate.com";
			if(!$testonly) send_email_template($useEmailTemplate,$data,"( Merchant Copy) ");
			// End Email
*/
		}
		else
		{
			$nextRecurCharge = $transactionInfo['chargeAmount'];
				
			$suboutput .= "Transaction will be rebilled for '".formatMoney($nextRecurCharge)."' because '".$transaction['td_recur_next_date']."' is <= NOW(). Next Date = '".$transactionInfo['td_recur_next_date_next']."'\n\r";
		
				// New Transaction built here. Date set to next date, and it is a rebill.
			$transaction['td_recur_next_date'] = $transactionInfo['td_recur_next_date_next'];
			$transaction['td_is_a_rebill'] = 1;
			$transaction['transactionId'] = "";
			$transaction['td_process_query'] = "";
			$transaction['td_process_result'] = "";
			$transaction['td_recur_attempts'] = 0;
			
			$transaction['CCnumber'] = etelDec($transaction['CCnumber']);
			if($transaction['td_gcard']) $transaction['td_gcard'] = etelDec($transaction['td_gcard']);
			if($transaction['bankroutingcode']) $transaction['bankroutingcode'] = etelDec($transaction['bankroutingcode']);
			if($transaction['bankaccountnumber']) $transaction['bankaccountnumber'] = etelDec($transaction['bankaccountnumber']);
			
			
			$transaction['amount'] = $nextRecurCharge;
			$transaction['reference_number']=genRefId("transaction",$transaction['checkorcard']);
			if($transaction['checkorcard']=='H') $transaction['bank_id'] = $transactionInfo['bank_Creditcard'];
			
			//TODO BANK SELECTION
			
			foreach($transaction as $key=>$data)
				$transaction[$key] = quote_smart($data);
				
				
			$sql= "UPDATE `cs_transactiondetails` SET `td_recur_processed` = '2' WHERE `transactionId` = '$oldTransId' LIMIT 1"; 
			if(!$testonly) $result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
			
			$etel_fraud_limit = 2.5;
			$transaction['td_recur_num']++;
			if(!$testonly) $response = execute_transaction(&$transaction,"Live");
			if($response['status']=='A' || ($response['status']=='P' && $transaction['checkorcard']=='C')) 
			{
				$suboutput .= "Transaction Approved/Accepted.\n\r";
				$suboutput .= "Next Payment for this rebilling account will fall on '".$transaction['td_recur_next_date']."'\n\r";
							// Disable Recur Billing for this transaction
				$sql= "UPDATE `cs_transactiondetails` SET `td_recur_processed` = '1' WHERE `transactionId` = '$oldTransId' LIMIT 1"; 
				if(!$testonly) $result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
				
				$nextRecurCharge=$transactionInfo['chargeAmount'];
				$suboutput .= "Old Transaction Rebilling Disabled.\n\r";
				$newTransId = $response['transactionId'];
				$suboutput .= "New Transaction (ID=$newTransId) Created Successfully.\n\r";
				$suboutput .= "Customer Charged successfully.\n\r";
	
			}
			else 
			{
				$try_again_date=date("Y-m-d",time()+1*60*60*24);
				$newTransId = $response['transactionId'];
		
				$sql= "UPDATE `cs_transactiondetails` SET `td_recur_next_date` = '$try_again_date', `td_recur_processed` = '0', `td_recur_attempts` = `td_recur_attempts`+1 WHERE `transactionId` = '$oldTransId' LIMIT 1"; 
				if(!$testonly) $result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
				$suboutput .= "Transaction Declined (ID=$newTransId), Error: ".$response['errormsg'];
				if(intval($transaction['td_recur_attempts'])>=$attempts_max) 
				{
					$suboutput .= "No more rebilling attempts. Attempt #".intval($transaction['td_recur_attempts'])." >= $attempts_max.\n\r";
					sendTransactionEmail($newTransId,"customer_rebill_decline_confirmation",$testonly);
				}
				else 
				{
					$suboutput .= "Will try billing again on $try_again_date. Attempt #".intval($transaction['td_recur_attempts'])."\n\r";
				}
			}
			//die($sql);
			// Charge customer here.
		
		}
		
	}
	toLog('rebill','system', $suboutput, $newTransId);

	
	$output.=$suboutput;
}
print(nl2br($output));
?>