<?
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'admin/includes/mailbody_replytemplate.php';

$email_from = "";
$email_to = "jayakrishnan.v@zerone-projects.co.uk";
$email_subject = "Txt Mail Test";
$strCompanyName = "Gale";
$strUserName = "jk";
$strPassword = "jk";

func_send_transaction_success_mail("2023");
/*$email_message = func_getreplymailbody_txt();
$email_message = str_replace("[CompanyName]", "Gale Technologies Pvt. Ltd.", $email_message);
$email_message = str_replace("[CustomerName]", "Jayakrishnan.V", $email_message);
$email_message = str_replace("[CreditCardNumber]", "411111111111111", $email_message);
$email_message = str_replace("[CardExpiry]", "12/04", $email_message);
$email_message = str_replace("[BillingDescriptor]", "Test Billing Descriptor", $email_message);
$email_message = str_replace("[OrderTime]", "2004-07-22 06:56:22", $email_message);
$email_message = str_replace("[ChargeAmount]", "1250.00", $email_message);
$email_message = str_replace("[Currency]", "USD", $email_message);
if(!func_send_mail_txtformat($email_from,$email_to,$email_subject,$email_message))
{
	print "An error encountered while sending the mail.";
	exit();				
} else {
	print "mail sent successfully";
}*/
?>
<?
function func_send_transaction_success_mail($trans_id) {
	$headers = "";	
	$headers .= "From: Companysetup <customerservice@etelegate.com>\n";
	$headers .= "X-Sender: Admin Companysetup\n"; 
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "Return-Path: <customerservice@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$sender ="customerservice@etelegate.com";

	$str_qry = "select a.companyname, b.transactionId, b.voiceAuthorizationno, b.name, b.surname, b.address,  b.country, b.state, b.city, b.zipcode, b.CCnumber, b.cvv, b.cardtype, b.amount, b.transactionDate, b.validupto, b.misc, b.ipaddress, a.transaction_type, a.billingdescriptor, b.email, a.send_mail, a.send_ecommercemail, a.email, a.userId, a.processing_currency from cs_companydetails a, cs_transactiondetails b where a.userId = b.userId and b.transactionId = $trans_id";

	if(!($show_sql_run = mysql_query($str_qry)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$company_name = mysql_result($show_sql_run,0,0);
	$trans_id = mysql_result($show_sql_run,0,1);
	$voiceauth = mysql_result($show_sql_run,0,2);
	$firstname = mysql_result($show_sql_run,0,3);
	$lastname = mysql_result($show_sql_run,0,4);
	$address = mysql_result($show_sql_run,0,5);
	$country = mysql_result($show_sql_run,0,6);
	$state = mysql_result($show_sql_run,0,7);
	$city = mysql_result($show_sql_run,0,8);
	$zipcode = mysql_result($show_sql_run,0,9);
	$number = etelDec(mysql_result($show_sql_run,0,10));
	$cvv2 = mysql_result($show_sql_run,0,11);
	$cardtype = mysql_result($show_sql_run,0,12);
	$amount = mysql_result($show_sql_run,0,13);
	$dateToEnter = mysql_result($show_sql_run,0,14);
	$validupto = mysql_result($show_sql_run,0,15);
	$misc = mysql_result($show_sql_run,0,16);
	$domain1 = mysql_result($show_sql_run,0,17);
	$transaction_type = mysql_result($show_sql_run,0,18);
	$billingdescriptor = mysql_result($show_sql_run,0,19);
	$email = mysql_result($show_sql_run,0,20);
	$send_mails = mysql_result($show_sql_run,0,21);
	$send_ecommercemail = mysql_result($show_sql_run,0,22);
	$fromaddress = mysql_result($show_sql_run,0,23);
	$company_id = mysql_result($show_sql_run,0,24);
	$str_currency = mysql_result($show_sql_run,0,25);
	$typeofcard = "";
	if($cardtype == "Master") 
	{	
		$typeofcard = "Master card Order";
	} 
	else 
	{	
		$typeofcard = "Visa card Order";
	}
	$subject = "Transaction Confirmation of ".$firstname." ".$lastname;
	$numLen = strlen($number);
	$frNum = $numLen-4;
	$lastFour = substr($number,$frNum,$numLen);
	$message = "Transaction details of $company_name\r\n\r\n";
	$message .= "Transaction ID : $trans_id \r\n\r\n";
	$message .= "Voice Authorization ID : $voiceauth\r\n\r\n";
	$message .= "Name : $firstname  $lastname\r\n\r\n";
	$message .= "Address : $address\r\n\r\n";
	$message .= "Country : $country\r\n\r\n";
	$message .= "State : $state\r\n\r\n";
	$message .= "City : $city\r\n\r\n";
	$message .= "Zipcode : $zipcode\r\n\r\n";
	$message .= "Credit Card No : $lastFour\r\n\r\n";
	$message .= "CVV2 : $cvv2\r\n\r\n";
	$message .= "Card Type : $typeofcard\r\n\r\n";
	$message .= "Amount : $amount $str_currency\r\n\r\n";
	$message .= "Date : $dateToEnter\r\n\r\n";
	$message .= "Expiry Date : $validupto\r\n\r\n";
	$message .= "Misc : $misc\r\n\r\n";
	$message .= "IP Address : $domain1\r\n\r\n";
	$message .= "Your credit card  has been charged the above amount TODAY\r\n";
	if($send_mails ==1) {
		//$ecommerce_letter = func_get_value_of_field($cnn_cs,"cs_registrationmail","mail_sent","mail_id",2);
		$ecommerce_letter = 1;
		if($email !="" && $transaction_type !="tele" && $ecommerce_letter==1 && $send_ecommercemail == 1) {
				$str_email_content = func_getecommerce_mailbody();

				$str_email_content = str_replace("[CompanyName]", $company_name, $str_email_content);
				$str_email_content = str_replace("[CustomerName]", $firstname." ".$lastname, $str_email_content);
				$str_email_content = str_replace("[CreditCardNumber]", $number, $str_email_content);
				$str_email_content = str_replace("[CardExpiry]", $validupto, $str_email_content);
				$str_email_content = str_replace("[BillingDescriptor]", $billingdescriptor, $str_email_content);
				$str_email_content = str_replace("[OrderTime]", $dateToEnter, $str_email_content);
				$str_email_content = str_replace("[ChargeAmount]", $amount, $str_email_content);
				$str_email_content = str_replace("[Currency]", $str_currency, $str_email_content);

			//	echo $str_email_content;
				$b_mail = func_send_mail($sender,$email,"Ecommerce Transaction Letter",$str_email_content);
		}

		if($email !="") 
		{
			mail($email,$subject,$message,$headers);
		}
		func_sendMail($company_id,$subject,$message,$headers);
	}
}

function func_getreplymailbody_txt() {
	$str_mail_string = "CC-Service Team:   customerservice@etelegate.com \r\n\r\n"; 
	$str_mail_string .= "Dear [CustomerName], \r\n\r\n";
	$str_mail_string .= "Thank you for your online order. \r\n\r\n"; 
	$str_mail_string .= "The payment due shall be charged to the following credit card:\r\n"; 
	$str_mail_string .= "[CreditCardNumber], expiring [CardExpiry] \r\n\r\n";
	$str_mail_string .= "Your credit card statement will contain the following information regarding this transaction: \r\n";
	$str_mail_string .= "[BillingDescriptor] \r\n\r\n"; 
	$str_mail_string .= "Your order was processed at : [OrderTime] CET \r\n\r\n";
	$str_mail_string .= "Your order total is: [ChargeAmount] [Currency] \r\n\r\n";
	$str_mail_string .= "You may receive an additional confirmation mail directly from the online shop.\r\n"; 
	$str_mail_string .= "For further questions please reply to this email or contact our CC-Service Team on customerservice@etelegate.com or to lookup your transaction online, please visit our automatic customer service transaction lookup center at www.etelegate.net .  \r\n\r\n";
	$str_mail_string .= "Kind regards, \r\n";
	$str_mail_string .= "CC-Service Team \r\n";
	return $str_mail_string;
}


	//function for sending mail in txt format
	function func_send_mail_txtformat($str_from,$str_to,$str_subject,$str_message)
	{
		if($str_from=="") {
			$str_from = "customerservice@etelegate.com";
		}
		$headers = "From: $str_from\n";  // Who the email was sent from
	    $headers .= "Reply-To: $str_from\n"; // Reply to address
		$headers .= "X-Mailer: PHP\n"; // mailer
	    $headers .= "X-Priority: 1\n"; // The priority of the mail
		$headers .= "Return-Path: <$str_from>\n";  // Return path for errors
		$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	//    mail($str_to, $str_subject, $str_message, $headers);
		$ok = @mail($str_to, $str_subject, $str_message, $headers); 
		if($ok) {
			return true;
		//echo "<font face=verdana size=2>The file was successfully sent!</font>"; 
		} else { 
			return false;
		//die("Sorry but the email could not be sent. Click <a href='Javascript:history.back()'>here</a> to go back and try again!"); 
		}
}

?>