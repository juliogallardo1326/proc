<?php 

$etel_disable_https = 1;
chdir(dirname(__FILE__));
$gateway_db_select = 3;
set_time_limit(1000);
include("../includes/dbconnection.php");
require_once("../includes/function.php");
require_once("../includes/html2text.php");
require_once('../includes/phpmailer/imap.inc.php');
require_once('../includes/phpmailer/class.decoder.php');
	$remove_array = array("\r", "\n","\\r", "\\n");
	$ignore_email_array = array("Mailer-Daemon@", "@etelegate.com","email-alert1");
	
	$email_accounts = array();
	$email_accounts[0]=array("user"=>"customerservice@etelegate.com", "pass"=>"etelcscs", "type"=>"Customer Service", "category"=>1, "gw_ID"=>3);
	$email_accounts[1]=array("user"=>"customerservice@maturebill.com", "pass"=>"etelcscs", "type"=>"Customer Service", "category"=>1, "gw_ID"=>4);
	$email_accounts[2]=array("user"=>"support@etelegate.com", "pass"=>"etelcscs", "type"=>"Tech Support", "category"=>4, "gw_ID"=>3);
	$email_accounts[3]=array("user"=>"support@maturebill.com", "pass"=>"etelcscs", "type"=>"Tech Support", "category"=>4, "gw_ID"=>4);
	$email_accounts[4]=array("user"=>"etel", "pass"=>"po4rl3ph", "type"=>"General Sales", "category"=>6, "gw_ID"=>3);
	

	$log = "Searching for new emails...\n";

	echo 'working...';
	foreach($email_accounts as $account)
	{
		$imap=new IMAPMAIL;
		if(!$imap->open("mail.etelegate.com","143"))
		{
			$log.= $imap->get_error();
			print $log;
			continue;
		} 
		$log .= "-Logging in as ".$account['user']."...\n";
		
	
		$imap->login($account['user'],$account['pass']);
		$log .= $imap->error;
		$response=$imap->open_mailbox("INBOX");
		$log .= $imap->error;
		$emailList=$imap->search_mailbox("UNSEEN SINCE ".date("d-M-Y",time()-24*60*60));
		if(is_array($emailList))
		{
			$log .= " Found ".sizeof($emailList)." emails...\n";

			foreach($emailList as $emailId)
			{
				echo '.';
				flush();
				$category = $account['type'];
				$log .= " Checking Email #$emailId...\n";
				
				$response=$imap->fetch_mail($emailId,"BODY[HEADER.FIELDS (SUBJECT FROM TO DATE)]");
				//print $response."<BR>";
				$response_ar = explode("\n",$response);
				$emailInfo = array();
				foreach($response_ar as $line)
				{
					$line = str_replace($remove_array,"",$line);
					if(substr($line,0,strlen("From: ")) == "From: ") $emailInfo['From'] = trim(quote_smart(substr($line,strlen("From: "))));
					if(substr($line,0,strlen("Subject: ")) == "Subject: ") $emailInfo['Subject'] = trim(quote_smart(substr($line,strlen("Subject: "))));
					if(substr($line,0,strlen("To: ")) == "To: ") $emailInfo['To'] = trim(quote_smart(substr($line,strlen("To: "))));
					if(substr($line,0,strlen("Date: ")) == "Date: ") $emailInfo['Date'] = trim(quote_smart(substr($line,strlen("Date: "))));
				}
				$matches = NULL;
				preg_match_all("/([A-Z0-9._-]+@[A-Z0-9.-]+\.[A-Z]{2,6})/i",$emailInfo['From'],&$matches);
				$emailInfo['FromEmail']=$matches[0][0];
				preg_match_all("/([A-Z0-9._-]+@[A-Z0-9.-]+\.[A-Z]{2,6})/i",$emailInfo['To'],&$matches);
				$emailInfo['ToEmail']=$matches[0][0];
				//print_r($emailInfo); die();
				
				$ignore=0;
				
				foreach ($ignore_email_array as $ign)
					if(strpos($emailInfo['FromEmail'],$ign)!==false) $ignore=1;
				
				if(!$ignore)
				{
					
					$gw_id = $account['gw_ID'];
					if(!$gw_id) $gw_id = 3;
					if(strpos($emailInfo['ToEmail'],"@maturebill.com")) $gw_id = 4;
					
					$send_auto_response = 0;
					
					$domain = parse_url($etel_gw_list[$gw_id]['gw_domain']);
					$domain = str_replace("www.","",$domain['host']);
					
					$tickets_users_username = findTicketUser($emailInfo['FromEmail'],$gw_id);
					if($tickets_users_username) $log .= " Created/Found User '$tickets_users_username'\n";
					
					$tickets_thread = findTicketThread(&$emailInfo,$tickets_users_username);
					if($tickets_thread['tickets_id']) 
					{
						$log .= " Found Thread '".$tickets_thread['tickets_reference']."'\n";
						$send_auto_response = 1;
					}
					else $log .= " No Thread Found.\n";
					$content=$imap->get_message($emailId);
					$message = new DecodeMessage();
					$message->InitMessage($content);
					
					$emailInfo = array_merge($message->ResultInfo(),$emailInfo);
					
					
					if($emailInfo['type']=='text/html')
					{
						$asciiText = new Html2Text ($emailInfo['body'], 70); // 900 columns maximum
						$emailInfo['body']= $asciiText->convert();
					}
					
					$new_ticket = createNewTicket(&$emailInfo,$tickets_users_username,$tickets_thread,$account['category']);
					if($new_ticket['tickets_id']) $log .= " Created New Ticket '$new_ticket' on thread '".$new_ticket['tickets_reference']."'\n";
					if(!$new_ticket['tickets_reference']) dieLog("Invalid Reference ID","Invalid Reference ID");
		
					if($send_auto_response)
					{
						$data = array();
						$data['ticket_id'] = $new_ticket['tickets_reference'];
						$data['subject'] = $emailInfo['Subject'];
						$data['category'] = $category;
						$data['full_name'] = $emailInfo['FromEmail'];
						$data['email'] = $emailInfo['FromEmail'];
						$data['gateway_select'] = $gw_id;
						$data['ticket_email_address'] = "Ticket-".$data['ticket_id']."@".$domain;
						
						send_email_template('support_ticket_response',$data);
						$log .= " Sent Email Successfully.\n";
					}
				}
				else
				$log .= " Ignored Email.\n";
			}
		}
	
	
		$log .= $imap->error;
		$imap->close();
	}
	echo nl2br($log);
	toLog('email','system', $log, '');

?>