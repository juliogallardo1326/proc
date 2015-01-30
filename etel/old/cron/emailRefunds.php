<?php 

$gateway_db_select = 3;
include("../includes/dbconnection.php");
require_once("../includes/function.php");
require_once('../includes/phpmailer/imap.inc.php');

	$log = "Searching for all bad emails...\n";

	$imap=new IMAPMAIL;
	if(!$imap->open("mail.etelegate.com","143"))
	{
		$log.= $imap->get_error();
		print $log;
		exit;
	} 

	$imap->login("ReturnedEmails+etelegate.com","etelcs88");
	$log .= $imap->error;
	$response=$imap->open_mailbox("INBOX");
	$log .= $imap->error;
	$emailList=$imap->search_mailbox("SINCE ".date("d-M-Y",time()-48*60*60)." OR OR SUBJECT \"delayed 48 hours\" SUBJECT \"Undeliverable: Welcome to Gkard\" SUBJECT \"Mail delivery failed\"");
	//echo $response=$imap->delete_message(9);
	//echo $response=$imap->rollback_delete(9);
	if(is_array($emailList))
	{
		foreach($emailList as $emailId)
		{
			$log .= " Checking Email #$emailId...\n";
			$content=$imap->get_message($emailId);
			//explode("",$content);
			//print_r($content);
			$matches = NULL;
			preg_match_all("/([A-Z0-9._-]+@[A-Z0-9.-]+\.[A-Z]{2,6})/i",$content,&$matches);

			//print_r(array_unique($matches[1]));
			$foundEmails = $matches[1];
			$email = NULL;
			if($foundEmails[2]=='Mailer-Daemon@host.bottom5.com')
			{
				$email = $foundEmails[5];
			}
			if($foundEmails[3]=='Mailer-Daemon@host.bottom5.com')
			{
				$email = $foundEmails[2];
			}
			if($foundEmails[0]=='billing@etelegate.com' && $foundEmails[3]=='postmaster@m2-corp.com')
			{
				$email = $foundEmails[5];
			}
			
			
			if($email)
			{
				$log .= "  Found Email $email\n";
				$sql = "SELECT transactionId FROM `cs_transactiondetails` WHERE 
				`transactionDate` > '".date("Y-m-d",time()-72*60*60)."' 
				AND `status` = 'A' 
				AND `cancelstatus` = 'N' 
				AND `email` = '$email' order by `transactionDate` DESC";
				$result = mysql_query($sql) or dieLog(mysql_error());
				if(mysql_num_rows($result)>=1)
				{
				
					$transInfo = mysql_fetch_assoc($result);
					$log .= "  Found Transaction ID ".$transInfo['transactionId']."\n";

					addListEmail($email,"Customer Email Not Sent",$transInfo['transactionId'],'customer','unsubscribe');
					
					$sql = "SELECT transaction_id FROM `cs_callnotes` WHERE 
					`transaction_id` ='".$transInfo['transactionId']."' 
					AND `cn_type` = 'refundrequest' ";
					$result = mysql_query($sql) or dieLog(mysql_error());
					if(mysql_num_rows($result)==0)
					{				
						$sql="REPLACE INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type` )
						VALUES ( '".$transInfo['transactionId']."', NOW() , 'Auto Refund on Bad Email ', '', '', '' , '', '', '', '', '', 'refundrequest');";
						$qry_callnotes = mysql_query($sql) or dieLog(mysql_error());
						$log .= "  ID has been added to refund requests.\n";
					}
					else
					{
						$log .= "  Transaction ID already has Refund Request.\n";
					}
				}
				else
				{
					// Not found.
					addListEmail($email,"Email Not Sent",-1,'unknown','unsubscribe');
				}
			}
			else
			{
				$log .= "  Invalid Email\n";
			}

		}
	}


	$log .= $imap->error;
	$imap->close();
	//$response=$imap->fetch_mail("3","BODYSTRUCTURE");
	//print_r($response);
	//echo nl2br($response);
	//echo $imap->error;
	toLog('email','system', $log, '');



?>