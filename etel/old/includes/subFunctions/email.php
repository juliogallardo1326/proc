<?php

	function sendTransactionEmail($transid,$emailtemplate,$testonly=false)
	{
			//exit($transid . " " . $emailtemplate);
			dieLog("SHOULD NOT BE USED!");
			$transactionInfo=getTransactionInfo($transid);
			$data = array();
			switch($emailtemplate)
			{
				case "customer_rebill_decline_confirmation":
				case "customer_expire_confirmation":
					$data['site_URL'] = $transactionInfo['cs_URL'];
					$data['process_msg'] = $transactionInfo['td_process_msg'];
					$data['reference_number'] = $transactionInfo['reference_number'];
					$data['full_name'] = $transactionInfo['surname'].", ".$transactionInfo['name'];
					$data['customer_email'] = $transactionInfo['email'];
					$data['amount'] = "$".formatMoney($transactionInfo['amount']-$transactionInfo['td_customer_fee'])." USD";
					$data['customer_fee'] = "$".formatMoney($transactionInfo['td_customer_fee'])." USD";
					$data['final_amount'] = "$".formatMoney($transactionInfo['amount'])." USD";
					$data['transaction_date'] = date("F j, Y",strtotime($transactionInfo['transactionDate']));
					$data['customer_support_email'] = $transactionInfo['cs_support_email'];
					$data['password'] = $transactionInfo['td_password'];
					$data['payment_schedule'] = $transactionInfo['payment_schedule'];
					$data["gateway_select"] = $transactionInfo['gateway_id'];

					$sendto['email'][] = array("email" => $transactionInfo['email']);
				break;
				case "customer_cancel_confirmation":
					$data['site_URL'] = $transactionInfo['cs_URL'];
					$data['reference_number'] = $transactionInfo['reference_number'];
					$data['full_name'] = $transactionInfo['name']." ".$transactionInfo['surname'];
					$data['cancel_reference_number'] = $transactionInfo['cancel_refer_num'];
					$data["gateway_select"] = $transactionInfo['gateway_id'];

					$sendto['email'][] = array("email" => $transactionInfo['email'], "copy" => "");
				break;
				case "customer_refund_confirmation":
					$data['site_URL'] = $transactionInfo['cs_URL'];
					$data['reference_number'] = $transactionInfo['reference_number'];
					$data['full_name'] = $transactionInfo['name']." ".$transInfo['surname'];
					$data['cancel_reference_number'] = $transactionInfo['cancel_refer_num'];
					$data["gateway_select"] = $transactionInfo['gateway_id'];
			
					$sendto['email'][] = array("email" => $transactionInfo['email'], "copy" => "");
					$sendto['email'][] = array("email" => $bankInfo['bank_email'], "copy" => "( Bank Copy )");
				break;
			}
	
			if($transactionInfo['cd_recieve_order_confirmations'])
				$sendto['email'][] = array("email" => $transactionInfo['cd_recieve_order_confirmations'], "copy" => "( Merchant Copy )");

			//$sendto['email'][] = array("email" => "support@etelegate.com", "copy" => "( Merchant Copy )");

			foreach($sendto['email'] as $email)
			{
				$data['email'] = $email['email'];
				if(!$testonly) send_email_template($emailtemplate,$data,$email['copy']); // Send Customer Email.
			}
				
	}
	
	function addListEmail($email,$reason,$item_ID=-1,$type='unknown',$action='unsubscribe')
	{
		$sql = "INSERT INTO `cs_email_lists` set 
		ec_email = '$email', 
		ec_action = '$action', 
		ec_item_ID = '$item_ID', 
		ec_reason = '$reason', 
		ec_type = '$type'";
		if($email) 
		{
			$result = sql_query_write($sql);
			if (!$result) toLog('error','misc',"Failed to add Email '$email' to Unsubscribe List. Likely already added.",-1);
			else toLog('misc','misc',"Added Email '$email' to Unsubscribe List",-1);
		}
		else toLog('erroralert','misc',"Blank email '$email'! not added to Unsubscribe List. What gives?",-1);
	}
	
	function removeListEmail($email)
	{
		$sql = "DELETE FROM `cs_email_lists` where 
		ec_email = '$email'";
		if($email) 
		{
			$result = sql_query_write($sql);
			if (!$result) toLog('erroralert','misc',"Failed to delete Email '$email' from Unsubscribe List ~ $sql",-1);
			else toLog('misc','misc',"Removed Email '$email' from Unsubscribe List",-1);
		}
		else toLog('erroralert','misc',"Blank email '$email'! What gives?",-1);
	}
	
	function infoListEmail($email='',$item_ID='',$type='',$action='unsubscribe',$reason='')
	{
		$options = "";
		if ($email) $options .=" AND ec_email = '".quote_smart($email)."'";
		if ($reason) $options .=" AND ec_reason = '".quote_smart($reason)."'";
		if ($item_ID) $options .=" AND ec_item_ID = '".quote_smart($item_ID)."'";
		if ($type) $options .=" AND ec_type = '".quote_smart($type)."'";
		if ($action) $options .=" AND ec_action = '".quote_smart($action)."'";
		$sql = "
			SELECT 
				count(ec_email) as cnt, 
				ec_reason, 
				ec_type 
			FROM 
				cs_email_lists 
			WHERE 
				1  
				$options 
			GROUP BY 
				ec_email
			";
		$result = sql_query_read($sql) or die(mysql_error() . "<pre>$sql</pre>");
		$info = mysql_fetch_assoc($result);
		return $info;	
	}
	
	function emailIsUnsubscribed($email)
	{
		$info = infoListEmail($email);
		return ($info['cnt']>0);
	}

	function get_email_template($template,$data)
	{	
		global $cnn_cs, $etel_gw_list;
		$lang = $data['tmpl_language'];
		$tmpl_custom_id = $data['tmpl_custom_id'];
		$custom_id_sql_orderby = "";
		$custom_id_sql_where = "";
		if($tmpl_custom_id) 
		{
			$custom_id_sql_orderby = "(`et_custom_id` = '$tmpl_custom_id') DESC, ";
			$custom_id_sql_where = "and (`et_custom_id` = '$tmpl_custom_id' OR `et_custom_id` is null)";
		} 
		if(!$lang) $lang = 'eng';
		$lang_sql = "(`et_language` = '$lang') DESC";
		
		$sql = "SELECT * FROM `cs_email_templates` WHERE `et_name` = '$template' $custom_id_sql_where order by $custom_id_sql_orderby $lang_sql Limit 1";

		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
		if (mysql_num_rows($result))
		{
			$emailInfo = mysql_fetch_assoc($result);
				
			
			$keysAvailable = "";
			
			//foreach($emailInfo as $key => $info)
			//	if($key != 'et_vars') $emailInfo[$key] = stripslashes($info);
			$use_gw = $_SESSION['gw_id'];
			if($data['gateway_select']) $use_gw = $data['gateway_select'];
			unset($data['gateway_select']);
			if(!$data['gateway_folder']) $data['gateway_folder'] = $etel_gw_list[$use_gw]['gw_folder'];
			if(!$data['gateway_template']) $data['gateway_template'] = $etel_gw_list[$use_gw]['gw_template'];
			if(!$data['gateway_domain']) $data['gateway_domain'] = $etel_gw_list[$use_gw]['gw_domain'];
			if(!$data['gateway_title']) $data['gateway_title'] = $etel_gw_list[$use_gw]['gw_title'];
			if(!$data['gateway_title_full']) $data['gateway_title_full'] = $etel_gw_list[$use_gw]['gw_fulltitle'];
			if(!$data['gateway_abrev']) $data['gateway_abrev'] = $etel_gw_list[$use_gw]['gw_abrev'];
			if(!$data['gateway_phone_customerservice']) $data['gateway_phone_customerservice'] = $etel_gw_list[$use_gw]['gw_phone_customerservice'];
			if(!$data['gateway_emails_sales']) $data['gateway_emails_sales'] = $etel_gw_list[$use_gw]['gw_emails_sales'];
			if(!$data['gateway_emails_support']) $data['gateway_emails_support'] = $etel_gw_list[$use_gw]['gw_emails_support'];
			if(!$data['gateway_emails_customerservice']) $data['gateway_emails_customerservice'] = $etel_gw_list[$use_gw]['gw_emails_customerservice'];
			if(!$data['gateway_customerservice_site']) $data['gateway_customerservice_site'] = $etel_gw_list[$use_gw]['gw_customerservice_site'];

  			$replace_array = array('et_title','et_subject','et_from','et_from_title','et_to','et_to_title','et_textformat','et_htmlformat','et_catagory');

			if(is_array($data))
			{
				foreach($data as $key => $item)
				{
					$item = stripslashes($item);
					if(!$item) $item = "N/A";
					foreach($emailInfo as $emailkey => $emailContents)
					{
						if(in_array($emailkey,$replace_array)) 
						{
							if($emailkey == 'et_htmlformat')
								$emailInfo[$emailkey] = str_replace("[".$key."]",nl2br($item),stripslashes($emailContents));
							else $emailInfo[$emailkey] = str_replace("[".$key."]",$item,stripslashes($emailContents));
						}
					}
					//$emailInfo['et_textformat'] = str_replace("[".$key."]",$item,$emailInfo['et_textformat']);
					//$emailInfo['et_subject'] = str_replace("[".$key."]",$item,$emailInfo['et_subject']);
					//$emailInfo['et_from_title'] = str_replace("[".$key."]",$item,$emailInfo['et_from_title']);
					if($keysAvailable) $keysAvailable.=", ";
					$keysAvailable.="[".$key."]";
				}
			}
			$sql = "UPDATE `cs_email_templates` set `et_vars` = '$keysAvailable' WHERE `et_name` = '$template'";
			if($keysAvailable != $emailInfo['et_vars']) $result = sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

			return $emailInfo;
		}
		else
		{
			dieLog("Error: Email Template Not Found: '$template'");
		}
	}
	
	function send_email_data($emailInfo,$attachments)
	{
		//global $etel_debug_mode;
		require_once('phpmailer/class.phpmailer.php');
		$return = array('status'=>false,'msg'=>'Mail could not be sent');
		
		$mail = new PHPMailer();
		$mail->From     = $emailInfo['et_from'];
		$mail->FromName = $emailInfo['et_from_title'];
		$mail->Subject  = $emailInfo['et_subject'];
		$mail->Host     = "smtp.etelegate.com";
		$mail->Mailer   = "smtp";
		$mail->Username   = "mailer+etelegate.com";
		$mail->Password   = "sda90f87sdfa";
		//$emailInfo["et_to"].=",support@etelegate.com";
		$emailsToSend = explode(",",$emailInfo["et_to"]);
		
		// HTML body
		$body  = $emailInfo['et_htmlformat'];
	
		// Plain text body (for mail clients that cannot read HTML)
		$text_body  = $emailInfo['et_textformat'];
		
		$mail->Body    = $body;
		$mail->AltBody = $text_body;
		if(is_array($attachments))
		{
			foreach($attachments as $atch)
				$mail->AddAttachment($atch['path'],$atch['name'],$atch['encoding'],$atch['type']);
		}
  		$mail->SetLanguage("en", "./phpmailer/");  // Added this line for English

		foreach($emailsToSend as $email)
		{
			if(!emailIsUnsubscribed($email))
			{
				$mail->AddAddress(trim($email), $emailInfo["et_to_title"]);
				$mail->AddBCC('emailreceipts@etelegate.com', $emailInfo["et_to_title"]);
				
				if(!$etel_debug_mode) $result = $mail->Send();
				else $result = 1;
				
				if(!$result && !$etel_debug_mode)
					toLog('error','misc', "Error sending Email to " . $email . " ~ ".$mail->ErrorInfo." ~ Subject: ".$mail->Subject);
				else
					if(!$etel_debug_mode) toLog('email','misc', "Email sent to " . $email . ": Subject:".$mail->Subject);
					else toLog('email','misc', "Email Test successful to " . $email . ": Subject:".$mail->Subject);
				// Clear all addresses and attachments for next loop
				$mail->ClearAddresses();
				if($result) 
				{
					if(!$return['status'])
						$return = array('status'=>true,'msg'=>"Mail Sent Successfully to '$email'");
					else
						$return['msg'] .= ", $email";
				}
			}
			else
			{
				toLog('error','misc', "Email '$email' is unsubscribed. Subject: ".$mail->Subject);
				if(!$return['status'])
					$return['msg'] = "'$email' is unsubscribed";
			}
		}
		// Clear all addresses and attachments for next loop
		$mail->ClearAttachments();
		
		return $return;
	}
	
	function send_email_template($template,$data,$addsubject = "")
	{
		require_once('phpmailer/class.phpmailer.php');
	
		$emailInfo = get_email_template($template,$data);
		$emailInfo['et_subject'] = $addsubject.$emailInfo['et_subject'];		
		$ach = array();
		return send_email_data($emailInfo,$ach);
	}
	
	function func_weekdays($day,$print=true)
	{
		$html = "";
		for($key=0;$key<7;$key++)
		{
			$selected = "";
			if ($key == $day) $selected = "selected";
			$html .= "<option value='$key' $selected>".date('l',(4+$key)*60*60*24)."</option>";
		}
		if(!$print)
			return $html;
		echo $html;
			
	}
	
	function findTicketUser($email,$gw_id = 0)
	{
		$sql = "
		SELECT tickets_users_username FROM `tickets_users` as tt 
		where tt.tickets_users_email = '".quote_smart($email)."'";
		$result = sql_query_read($sql) or dieLog(mysql_error());
		if(mysql_num_rows($result))
			$tickets_users_username = mysql_result($result,0);
		if (!$tickets_users_username) $tickets_users_username = createTicketUser($email,$gw_id);
		return $tickets_users_username;	
	}
	
	function createTicketUser($email,$gw_id = 0)
	{
		$pass = strtolower(substr(md5(time().rand(1,1000)),0,10));
		$name = str_replace('@',' at ',$email);
		if(!$gw_id) $gw_id = $_SESSION['gw_id'];
		
		$sql = "
		Insert Into `tickets_users`
		set tickets_users_email = '".quote_smart($email)."',
		tickets_users_username = '".quote_smart($email)."',
		tickets_users_password = '$pass',
		tickets_users_name = '$name',
		cs_gateway_id = '$gw_id'
		";
		$result = sql_query_write($sql) or dieLog(mysql_error());
		return quote_smart($email);	
	}
	
	function findTicketThread($emailInfo,$tickets_users_username)
	{
		$tickets_reference = substr($emailInfo['ToEmail'],strlen("Ticket-"),16);
		$tickets_subject = str_replace("Re: ","",$emailInfo['Subject']);
		
		$sql = "
		SELECT tickets_id, tickets_reference FROM `tickets_tickets` as tt 
		left join `tickets_users` as tu on tt.tickets_username = tu.tickets_users_username 
		where tickets_users_username = '$tickets_users_username' and (
			tickets_reference = '$tickets_reference' or
			tickets_subject = '$tickets_subject'
			) and tickets_reference is not null
		";
		$result = sql_query_read($sql) or dieLog(mysql_error());
		$ticket = NULL;
		if(mysql_num_rows($result))
			$ticket = mysql_fetch_assoc($result);
		return $ticket;	
	}
	
	function createNewTicket($emailInfo,$tickets_users_username,$tickets_thread,$tickets_category=1)
	{
		$ticket = array();
		$ticket['tickets_reference'] = 'NULL';
		if(!$tickets_thread['tickets_id'])	$ticket['tickets_reference'] = "'".strtoupper(substr(md5(time().rand(0,10000000)),0,16))."'";
		$tickets_subject = str_replace("Re: ","",$emailInfo['Subject']);
		
		$sql = "
		Insert ignore Into `tickets_tickets`
		set tickets_username = '$tickets_users_username',
		tickets_child = '".$tickets_thread['tickets_id']."',
		tickets_subject = '$tickets_subject',
		tickets_reference = ".$ticket['tickets_reference'].",
		tickets_name = '".quote_smart($emailInfo['FromEmail'])."',
		tickets_email = '".quote_smart($emailInfo['FromEmail'])."',
		tickets_question = '".quote_smart($emailInfo['body'])."',
		tickets_category = '".quote_smart($tickets_category)."',
		tickets_timestamp = '".time()."'";
		
		$result = sql_query_write($sql) or dieLog(mysql_error());
		$ticket['id'] = mysql_insert_id();
		
		if ($tickets_thread['tickets_id']>0)
			sql_query_write("update `tickets_tickets` set tickets_status='Open' where tickets_id = '".$tickets_thread['tickets_id']."'") or dieLog(mysql_error());
		
		if($tickets_thread['tickets_id']) $ticket['tickets_reference'] = $tickets_thread['tickets_reference'];
		return $ticket;	
	}
	
	function isValidEmail( $email )
	{
		return(eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,6}$", $email));
	} 
	
	
	/////
	
	function genResellerContract($companyInfo)
	{
		global $weekdays;
		$thisdate = time();

		$data['email'] = 			$companyInfo['reseller_email'];
		$data['fax_number'] = 		$companyInfo['reseller_faxnumber'];
		$data['phone_number'] = 	$companyInfo['reseller_phone'];
		$data['companyname'] = 		$companyInfo['reseller_companyname'];
		$data['full_name'] = 		$companyInfo['reseller_contactname'];
		$data['title'] = 			$companyInfo['reseller_jobtitle'];
		$data['date'] = 			"the ".date("jS",$thisdate)." day of ".date("F",$thisdate).", the year ".date("Y",$thisdate); 
		//$data['address'] = 			$companyInfo['address'].",".$companyInfo['city'].",".$companyInfo['state'].$companyInfo['country'].$companyInfo['zipcode'];     
		$data['tmpl_custom_id'] = $companyInfo['reseller_id'];
		$contract = get_email_template('reseller_contract',$data);
		return $contract;
	}
		
	function genMerchantContract($userId)
	{
		dieLog("genMerchantContract Depreciated");
	}	
?>