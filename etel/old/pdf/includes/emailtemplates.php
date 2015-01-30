<?php

include('/home/etel/public_html/includes/phpmailer/class.phpmailer.php');

function send_email_template($template,$data)
{
	$sql = "SELECT * FROM `cs_email_templates` WHERE `et_name` = '$template'";
	$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	if (mysql_num_rows($result))
	{
		$emailInfo = mysql_fetch_assoc($result);
		
		foreach($data as $key => $item)
		{
			$emailInfo['et_htmlformat'] = str_replace("[".$key."]",$item,stripslashes($emailInfo['et_htmlformat']));
			$emailInfo['et_textformat'] = str_replace("[".$key."]",$item,stripslashes($emailInfo['et_textformat']));
			$emailInfo['et_subject'] = str_replace("[".$key."]",$item,stripslashes($emailInfo['et_subject']));
		}
		$mail = new PHPMailer();
		$mail->From     = $emailInfo['et_from'];
		$mail->FromName = $emailInfo['et_from_title'];
		$mail->Subject  = $emailInfo['et_subject'];
		$mail->Host     = "smtp.etelegate.com";
		$mail->Mailer   = "smtp";
		
		// HTML body
		$body  = $emailInfo['et_htmlformat'];
	
		// Plain text body (for mail clients that cannot read HTML)
		$text_body  = $emailInfo['et_textformat'];
	
		$mail->Body    = $body;
		$mail->AltBody = $text_body;
		$mail->AddAddress($data["email"], $data["full_name"]);
	
		if(!$mail->Send())
			echo "There has been a mail error sending to " . $row["email"] . "<br>";
	
		// Clear all addresses and attachments for next loop
		$mail->ClearAddresses();
		$mail->ClearAttachments();

	}
	else
	{
		die('Error: Email Template Not Found');
	}
}

?>