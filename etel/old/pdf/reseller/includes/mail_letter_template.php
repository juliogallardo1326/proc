<?php
function func_getreplymailbody_admin($strCompanyName,$strUserName,$strPassword,$str_reference_num,$transaction_type ,$how_about_us,$voulmeNumber) {
	$str_mailbody = "";
	$str_mailbody .= "Dear Administrator,\r\n\r\n";
	$str_mailbody .= "Subject : Registration of New Company  \r\n";
	$str_mailbody .= "A new company has been added  with the following details.\r\n";
	$str_mailbody .= "Company Name: $strCompanyName\r\n";
	$str_mailbody .= "Username: $strUserName\r\n";
	$str_mailbody .= "Password: $strPassword\r\n\r\n";
	$str_mailbody .= "Reference No: $str_reference_num\r\n";
	$str_mailbody .= "Expected Monthly Volume :$voulmeNumber\r\n";
	$str_mailbody .= "Merchant Type :$transaction_type\r\n";
	//$str_mailbody .= "How heard about us: $how_about_us\r\n";
	return $str_mailbody;
}

function func_reseller_loginletter() {
	$str_mail_string = "<html><head><title>::eTelegate.com::</title>";
	$str_mail_string .= "<style>.TextBox{font-family:verdana;font-size:14px}</style></head>";
	$str_mail_string .= "<body topmargin='0' leftmargin='0'><table border='0' cellpadding='0' cellspacing='0' width='772' align='center' >";
	$str_mail_string .= "<tr><td></td></tr></table><table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mail_string .= "<tr><td height='15' width='766'>&nbsp;</td></tr></table>";
	$str_mail_string .= "<table width='772' height='10' border='0' align='center' cellpadding='0' cellspacing='0'><tr>";
	$str_mail_string .= "<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'>&nbsp;";
	$str_mail_string .= "<td height='15' valign='top' align='left' width='701' class='TextBox' bgcolor='#F1F5FA'  > </td>";
	$str_mail_string .= "<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'></td></tr>";
	$str_mail_string .= "<tr><td height='250' valign='top' align='left' width='701' class='TextBox' bgcolor='#F1F5FA' >";
	$str_mail_string .= "<p>Dear [ResellerCompanyName],<img src='https://www.etelegate.com/images/biglogo.gif' alt='eTelegate Logo' width='150' height='109' align='right'></p>";
	$str_mail_string .= "<p>You have made the right choice when choosing eTeleGate.com as your global partner for referring your medium to high risk merchants for credit card and ACH processing.  As a preferred reseller of eTeleGate.com you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.</p>";
	$str_mail_string .= "<p>Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.</p>";
	$str_mail_string .= "<p>Please login to your account and finish your new reseller registration.  Your login details to your admin area are listed below:</p>";
	$str_mail_string .= "<p><a href='https://www.etelegate.com'>https://www.etelegate.com</a></p>";
	$str_mail_string .= "<p>Username: [UserName]<br>Password:  [PassWord]<br>click the dropdown box and select 'Reseller'</p><p>Once logged in, please click <strong><font color='#FF0000'>'Start Here'</font></strong>";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred etelegate partner. If you have any questions, please feel free to email ";
	$str_mail_string .= "our sales department at <a href='mailto:partners@etelegate.com'>partners@etelegate.com</a>.</p>";

	$str_mail_string .= "<p>Etelegate.com has built its reputation as being “The Offshore Processor”.  You will not find a more reliable or reseller friendly processor in the industry today.  We offer unique features like 24/7/365 merchant and reseller support, so day or night we are here to help you or your merchants with questions, payroll, integration, or anything needed.</p>";

	$str_mail_string .= "<p>In closing, we have hundreds of successful resellers making tens of thousands of dollars or more month after month, and so can you.  Our one of a kind gateway lets you view your referred merchants and how much they are processing, as well as calculating your commissions in real-time!  On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to etelegate.com from the month prior.  Let us work together and benefit from your referrals, and our merchant processing experience.  Again, there is no other processor that offers resellers such a streamline way to refer new business and make sky is the limit paychecks.  We look forward to working with you.</p>";
	$str_mail_string .= "<p>Sincerely,</p><p><a href='www.eTeleGate.com'>www.eTeleGate.com</a></p><p></p></td></tr></table>";
	$str_mail_string .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mail_string .= "<tr><td height='15' width='766'>&nbsp;</td></tr></table></body></html>";

	return $str_mail_string;
}
//mail to reseller
function func_newreseller_loginletter() {
	$str_mail_string = "Dear [ResellerCompanyName],\r\n\r\n";
	$str_mail_string .= "You have made the right choice when choosing eTeleGate.com as your global partner for referring your medium to high risk merchants for credit card and ACH processing.  As a preferred reseller of eTeleGate.com you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.\r\n\r\n";
	$str_mail_string .= "Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.\r\n\r\n";
	$str_mail_string .= "Please login to your account and finish your new reseller registration.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mail_string .= "https://www.etelegate.com \r\n\r\n";
	$str_mail_string .= "Username: [UserName]\r\n";
	$str_mail_string .= "Password:  [PassWord]\r\n\r\n";
	$str_mail_string .= "Once logged in, please click 'Start Here'";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred etelegate partner. If you have any questions, please feel free to email";
	$str_mail_string .= "our sales department at partners@etelegate.com. \r\n\r\n";
	$str_mail_string .= "Etelegate.com has built its reputation as being “The Offshore Processor”.  You will not find a more reliable or reseller friendly processor in the industry today.  We offer unique features like 24/7/365 merchant and reseller support, so day or night we are here to help you or your merchants with questions, payroll, integration, or anything needed.\r\n\r\n";
	$str_mail_string .= "In closing, we have hundreds of successful resellers making tens of thousands of dollars or more month after month, and so can you.  Our one of a kind gateway lets you view your referred merchants and how much they are processing, as well as calculating your commissions in real-time!  On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to etelegate.com from the month prior.  Let us work together and benefit from your referrals, and our merchant processing experience.  Again, there is no other processor that offers resellers such a streamline way to refer new business and make sky is the limit paychecks.  We look forward to working with you.\r\n\r\n";
	$str_mail_string .= "Sincerely,\r\n\r\n";
	$str_mail_string .= "www.eTeleGate.com\r\n";

	return $str_mail_string;
}


function func_reseller_merchant_loginletter_htmlformat() {
	$str_mail_string = "<html><head><title>::eTelegate.com::</title><style>";
	$str_mail_string .= ".TextBox{font-family:verdana;font-size:14px}</style></head><body topmargin='0' leftmargin='0'>";
	$str_mail_string .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center' ><tr><td ></td></tr>";
	$str_mail_string .= "</table><table border='0' cellpadding='0' cellspacing='0' width='772' align='center'><tr>";
	$str_mail_string .= "<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'>&nbsp;";
	$str_mail_string .= "<td height='102' valign='top' align='center' width='701' class='TextBox' bgcolor='#F1F5FA' colspan='2'>";

	$str_mail_string .= "<p align='left'>Dear [CompanyName],<br><br>[ResellerCompanyName] has registered you into the etelegate system. You ";
	$str_mail_string .= "are now ready to login to the system and apply for your new offshore merchant account by completing the application process. The entire process should ";
	$str_mail_string .= "take about 10 minutes, and you'll need the following information on hand:<br></td><td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'><img src='https://www.etelegate.com/images/logo2os.jpg' alt='eTelegate Logo' width='80' height='58'>";
	$str_mail_string .= "</td></tr><tr><td height='64' valign='top' align='center' width='72' class='TextBox' bgcolor='#F1F5FA'>";
	$str_mail_string .= "<p align='left'>&nbsp;</td><td height='64' valign='top' align='left' width='631' class='TextBox' bgcolor='#F1F5FA'>";
	$str_mail_string .= "<ul><br><li>Your company's registration number and registered address (if applicable)</li>";
	$str_mail_string .= "<li>Your company's bank account details</li><li>Copy of Driver's License or Passport</li>";
	$str_mail_string .= "<li>Articles of Incorporation</li></ul></td></tr><tr><td height='250' valign='top' align='center' width='701' class='TextBox' bgcolor='#F1F5FA' colspan='2'>";
	$str_mail_string .= "<p align='left'>etelegate.com is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments form an offshore environment.  Your login details to your admin area are listed below:<br>";
	$str_mail_string .= "<br><font color='#0000FF'><a href='https://www.etelegate.com'>https://www.etelegate.com</a></font><br>";
	$str_mail_string .= "<br>Username: [UserName]<br>Password:  [PassWord]<br>Reference No:  [ReferenceNo]<br><br>Once logged in, please click <b><font color='#FF0000'>  'Start Here'</font></b> and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at ";
	$str_mail_string .= "<font color='#0000FF'><a href='mailto:sales@etelegate.com'>sales@etelegate.com</a>.</font><br>";
	$str_mail_string .= "Etelegate.com has built its reputation as being “The Offshore Processor”.  You will not find a more reliable or reseller friendly processor in the industry today.  We offer unique features like 24/7/365 merchant and reseller support, so day or night we are here to help you or your merchants with questions, payroll, integration, or anything needed.<br>";
	$str_mail_string .= "In closing, we have hundreds of successful resellers making tens of thousands of dollars or more month after month, and so can you.  Our one of a kind gateway lets you view your referred merchants and how much they are processing, as well as calculating your commissions in real-time!  On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to etelegate.com from the month prior.  Let us work together and benefit from your referrals, and our merchant processing experience.  Again, there is no other processor that offers resellers such a streamline way to refer new business and make sky is the limit paychecks.  We look forward to working with you.<br>";

	$str_mail_string .= "<br>Sincerely,<br><br><font color='#0000FF'><a href='https://www.etelegate.com'>www.etelegate.com</a></font>";
	$str_mail_string .= "</td></tr></table><table border='0' cellpadding='0' cellspacing='0' width='772' align='center' bgcolor='' >";
	$str_mail_string .= "<tr><td height='15'  bgcolor='' width='766'>&nbsp;</td></tr></table></body></html>";



	return $str_mail_string;
}

function func_reseller_merchant_loginletter() {
	$str_mail_string = "Dear [CompanyName],\r\n\r\n";
	$str_mail_string .= "'[ResellerCompanyName]' has registered you into the etelegate system. You are now ready to login to the system and apply for your new offshore merchant account by completing the application process. The entire process should take about 10 minutes, and you'll need the following information on hand:\r\n\r\n";
	$str_mail_string .= "		Your company's registration number and registered address (if applicable)\r\n";
	$str_mail_string .= "		Your company's bank account details\r\n";
	$str_mail_string .= "		Copy of Driver's License or Passport\r\n";
	$str_mail_string .= "		Articles of Incorporation\r\n\r\n";
	$str_mail_string .= "etelegate.com is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments form an offshore environment.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mail_string .= "https://www.etelegate.com\r\n\r\n";
	$str_mail_string .= "Username: [UserName]\r\n";
	$str_mail_string .= "Password:  [PassWord]\r\n\r\n";
	$str_mail_string .= "Once logged in, please click   'Start Here' and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at ";
	$str_mail_string .= "sales@etelegate.com.\r\n\r\n";
	$str_mail_string .= "If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments form an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to etelegate.com!\r\n\r\n";
	$str_mail_string .= "Sincerely,\r\n\r\n";
	$str_mail_string .= "www.etelegate.com\r\n";

	return $str_mail_string;
}

function func_gatewayreseller_loginletter() {
	$str_mail_string = "<html><head><title>::[GatewayResellerCompanyName]::</title>";
	$str_mail_string .= "<style>.TextBox{font-family:verdana;font-size:14px}</style></head>";
	$str_mail_string .= "<body topmargin='0' leftmargin='0'><table border='0' cellpadding='0' cellspacing='0' width='772' align='center' >";
	$str_mail_string .= "<tr><td></td></tr></table><table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mail_string .= "<tr><td height='15' width='766'>&nbsp;</td></tr></table>";
	$str_mail_string .= "<table width='772' height='10' border='0' align='center' cellpadding='0' cellspacing='0'><tr>";
	$str_mail_string .= "<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'>&nbsp;";
	$str_mail_string .= "<td height='15' valign='top' align='left' width='701' class='TextBox' bgcolor='#F1F5FA'  > </td>";
	$str_mail_string .= "<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'></td></tr>";
	$str_mail_string .= "<tr><td height='250' valign='top' align='left' width='701' class='TextBox' bgcolor='#F1F5FA' >";
	$str_mail_string .= "<p>Dear [GatewayResellerCompanyName],<img src='GatewayLogo/[Gateway_Logo]' alt='[GatewayResellerCompanyName] Logo' width='150' height='109' align='right'></p>";
	$str_mail_string .= "<p>You have made the right choice when choosing [GatewayResellerCompanyName] as your global partner for referring your medium to high risk merchants for credit card and ACH processing.  As a preferred reseller of [GatewayResellerCompanyName] you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.</p>";
	$str_mail_string .= "<p>Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.</p>";
	$str_mail_string .= "<p>Please login to your account and finish your new reseller registration.  Your login details to your admin area are listed below:</p>";
	$str_mail_string .= "<p><a href='https://24.244.141.134/login.htm'>https://24.244.141.134/login.htm</a></p>";
	$str_mail_string .= "<p>Username: [UserName]<br>Password:  [PassWord]<br>click the dropdown box and select 'Reseller'</p><p>Once logged in, please click <strong><font color='#FF0000'>'Start Here'</font></strong>";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred [GatewayResellerCompanyName] partner. If you have any questions, please feel free to email ";
	$str_mail_string .= "our sales department at <a href='mailto:[Gateway_Email]'>[Gateway_Email]</a>.</p>";
	$str_mail_string .= "<p>Once you are a partner, you can begin referring merchants to us right away and build your portfolio of registered merchants.  You will need to list your merchants in the system by selecting “Add Merchant”.  Each new merchant you ADD will be checked in our database by company name and email address to ensure that it is not already in the system or processing with [GatewayResellerCompanyName].  Upon confirmation of this, an email will be sent to your added merchant advising them to fill out the online merchant application and upload their required documents to approve their account.  Once you add a new merchant, this now becomes your company’s merchant and cannot be registered by any other reseller who promotes [GatewayResellerCompanyName].  Your company will have 30 days to get your new merchant to complete the application process, thus preventing resellers from registering miscellaneous merchants that aren’t directly within their portfolio, making it fair to all [GatewayResellerCompanyName] re-sellers.";
	$str_mail_string .= "</p><p>As your merchant completes its online application and uploads its documents, the [GatewayResellerCompanyName] will update you in real-time the status of your merchants, giving you complete control of your portfolio of merchants.  Once your merchant completes the application process, your company will be given its buy rates and fees which you are able to mark up and keep 100% above the rates and fees! </p>";
	$str_mail_string .= "<p>On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to our company each and every month your merchants are processing.  We look forward to working with you.  </p>";
	$str_mail_string .= "<p>Sincerely,</p><p><a href='[GatewayCompanyURL]'>[GatewayCompanyURL]</a></p><p></p></td></tr></table>";
	$str_mail_string .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mail_string .= "<tr><td height='15' width='766'>&nbsp;</td></tr></table></body></html>";

	return $str_mail_string;
}
//function to send reseller letter from gateway to client
function func_newgatewayreseller_loginletter() {
	$str_mail_string = "Dear [CompanyName],\r\n\r\n";
	$str_mail_string .= "You have made the right choice when choosing [GatewayResellerCompanyName] as your global partner for referring your medium to high risk merchants for credit card and ACH processing.  As a preferred reseller of [GatewayResellerCompanyName] you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.\r\n\r\n";
	$str_mail_string .= "Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.\r\n\r\n";
	$str_mail_string .= "Please login to your account and finish your new reseller registration.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mail_string .= "https://24.244.141.134/login.htm \r\n\r\n";
	$str_mail_string .= "Username: [UserName]\r\n\r\nPassword:  [PassWord]\r\n\r\nclick the dropdown box and select 'Reseller'\r\n\r\nOnce logged in, please click'Start Here'";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred [GatewayResellerCompanyName] partner. If you have any questions, please feel free to email ";
	$str_mail_string .= "our sales department at [Gateway_Email].\r\n\r\n";
	$str_mail_string .= "Once you are a partner, you can begin referring merchants to us right away and build your portfolio of registered merchants.  You will need to list your merchants in the system by selecting “Add Merchant”.  Each new merchant you ADD will be checked in our database by company name and email address to ensure that it is not already in the system or processing with [GatewayResellerCompanyName].  Upon confirmation of this, an email will be sent to your added merchant advising them to fill out the online merchant application and upload their required documents to approve their account.  Once you add a new merchant, this now becomes your company’s merchant and cannot be registered by any other reseller who promotes [GatewayResellerCompanyName].  Your company will have 30 days to get your new merchant to complete the application process, thus preventing resellers from registering miscellaneous merchants that aren’t directly within their portfolio, making it fair to all [GatewayResellerCompanyName] re-sellers.";
	$str_mail_string .= "\r\n\r\nAs your merchant completes its online application and uploads its documents, the [GatewayResellerCompanyName] will update you in real-time the status of your merchants, giving you complete control of your portfolio of merchants.  Once your merchant completes the application process, your company will be given its buy rates and fees which you are able to mark up and keep 100% above the rates and fees! \r\n\r\n";
	$str_mail_string .= "On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to our company each and every month your merchants are processing.  We look forward to working with you.  \r\n\r\n";
	$str_mail_string .= "Sincerely,\r\n\r\n[GatewayCompanyURL]\r\n\r\n";

	return $str_mail_string;
}

//
function func_getreplymailbody_gateway($user_reference_num,$strCompanyName,$strUserName,$strPassword,$strcompanyname1,$stremail1,$strurl1) {
	$str_mailbody = "";
	$str_mailbody .= "Dear $strCompanyName,\r\n\r\n";
	$str_mailbody .= "Thank you for filling out the $strcompanyname1 pre-application. You are now ready to login to your offshore account and complete the application process. The entire process should take about 10 minutes. You'll need the following information to hand:\r\n\r\n";
	$str_mailbody .= "	Your company's registration number and registered address (if applicable)\r\n";

	$str_mailbody .= "	Your company's bank account details\r\n";

	$str_mailbody .= "	Copy of Driver's License or Passport\r\n";

	$str_mailbody .= "	Articles of Incorporation\r\n\r\n";

	$str_mailbody .= "$strcompanyname1 is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments from an offshore environment.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mailbody .= "https://24.244.141.134/login.htm\r\n\r\n";

	$str_mailbody .= "Username: $strUserName\r\n";
	$str_mailbody .= "Password: $strPassword\r\n\r\n";
	$str_mailbody .= "Reference Number: $user_reference_num\r\n\r\n";

	$str_mailbody .= "Once logged in, please click   \"Start Here\" and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at";
	$str_mailbody .= "$strurl1.\r\n\r\n";

	$str_mailbody .= "If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments from an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to $strcompanyname1!\r\n\r\n";

	$str_mailbody .= "Sincerely,\r\n\r\n";

	$str_mailbody .= "$strcompanyname1\r\n";
	return $str_mailbody;
}

?>