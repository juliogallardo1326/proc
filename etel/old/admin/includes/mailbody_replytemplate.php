<?php


function func_getreplymailbody_admin_contact($contact_email,$contact_phone,$companyname,$username,$password,$questions_charge,$user_reference_num,$transactiontype ,$voulmeNumber){

	$str_mailbody = "";
	$str_mailbody .= "Dear Administrator,\r\n\r\n";
	$str_mailbody .= "Subject : Registration of New Company  \r\n";
	$str_mailbody .= "A new company has been added  with the following details.\r\n";
	$str_mailbody .= "Company Name: $companyname\r\n";
	$str_mailbody .= "Username: $username\r\n";
	$str_mailbody .= "Password: $password\r\n\r\n";
	$str_mailbody .= "Reference No: $user_reference_num\r\n";
	$str_mailbody .= "Contact E-mail : $contact_email\r\n";
	$str_mailbody .= "Contact Phone : $contact_phone\r\n";
	$str_mailbody .= "Expected Monthly Volume :$voulmeNumber\r\n";
	$str_mailbody .= "Merchant Type :$transactiontype\r\n";
	$str_mailbody .= "Question : $questions_charge\r\n";
	return $str_mailbody;
}


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
	$str_mailbody .= "How heard about us: $how_about_us\r\n";
	return $str_mailbody;
}


function func_getreplymailbody($strCompanyName,$strUserName,$strPassword,$str_reference_num) {
	$str_mailbody = "";
	$str_mailbody .= "Dear $strCompanyName,\r\n\r\n";
	$str_mailbody .= "Thank you for filling out the etelegate.com pre-application. You are now ready to login to your offshore account and complete the application process. The entire process should take about 10 minutes. You'll need the following information to hand:\r\n\r\n";
	$str_mailbody .= "	Your company's registration number and registered address (if applicable)\r\n";

	$str_mailbody .= "	Your company's bank account details\r\n";

	$str_mailbody .= "	Copy of Driver's License or Passport\r\n";

	$str_mailbody .= "	Articles of Incorporation.\r\n\r\n";

	$str_mailbody .= "etelegate.com is one of the largest European credit card processors today, helping company's worldwide process Credit Cards, ACH payments, and WEB900 from an offshore environment.  If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help your business accept payments today. Located and operating from the UK allows us to process all of your payments from an offshore environment and ensure your business is kept private.  We offer the following features:\r\n\r\n";


	$str_mailbody .= "	No upfront fees (Start-up costs taken from processing) \r\n";
	$str_mailbody .= "	Hassle free same day set up (Start processing TODAY)\r\n";
	$str_mailbody .= "	Password-Member Management\r\n";
	$str_mailbody .= "	Process Credit Cards, Checks, Web 900, and Dialer Transactions\r\n";
	$str_mailbody .= "	Timely and Reliable Settlements \r\n";
	$str_mailbody .= "	Weekly payments by Wire transfer, Check or ACH\r\n";
	$str_mailbody .= "	Real-Time reporting on your transactions\r\n";
	$str_mailbody .= "	Advanced antifraud protection\r\n";
	$str_mailbody .= "	24/7/365 customer support for Vendors and Customers\r\n";
	$str_mailbody .= "	One time & recurring payments\r\n";
	$str_mailbody .= "	Low rates and fees\r\n\r\n";


	$str_mailbody .= "Your login details to your admin area are listed below:\r\n\r\n";

	$str_mailbody .= "https://www.etelegate.com\r\n\r\n";


	$str_mailbody .= "Username: $strUserName\r\n";
	$str_mailbody .= "Password: $strPassword\r\n\r\n";

	$str_mailbody .= "Once logged in, please click   \"Start Here\" and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at ".$_SESSION['gw_title']." or call us toll free at (866) OFFSHORE 24/7/365.  We look forward to getting your offshore merchant account set up and processing your company's payments. Welcome to etelegate.com\r\n\r\n";

	$str_mailbody .= "Sincerely,\r\n\r\n";

	$str_mailbody .= "www.etelegate.com\r\n";
	return $str_mailbody;
}

function func_getreplymailbody_gateway($strCompanyName,$strUserName,$strPassword,$strcompanyname1,$stremail1,$strurl1,$str_reference_num) {
	$str_mailbody = "";
	$str_mailbody .= "Dear $strCompanyName,\r\n\r\n";
	$str_mailbody .= "Thank you for filling out the $strcompanyname1 pre-application. You are now ready to login to your offshore account and complete the application process. The entire process should take about 10 minutes. You'll need the following information to hand:\r\n\r\n";
	$str_mailbody .= "	Your company's registration number and registered address (if applicable)\r\n";

	$str_mailbody .= "	Your company's bank account details\r\n";

	$str_mailbody .= "	Copy of Driver's License or Passport\r\n";

	$str_mailbody .= "	Articles of Incorporation\r\n\r\n";

	$str_mailbody .= "$strcompanyname1 is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments from an offshore environment.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mailbody .= "http://24.244.141.134/login.htm\r\n\r\n";


	$str_mailbody .= "Username: $strUserName\r\n";
	$str_mailbody .= "Password: $strPassword\r\n\r\n";
	$str_mailbody .= "Reference No : $str_reference_num\r\n";

	$str_mailbody .= "Once logged in, please click   \"Start Here\" and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at";
	$str_mailbody .= "$strurl1.\r\n\r\n";

	$str_mailbody .= "If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments from an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to $strcompanyname1!\r\n\r\n";

	$str_mailbody .= "Sincerely,\r\n\r\n";

	$str_mailbody .= "$strcompanyname1\r\n";
	return $str_mailbody;
}

function func_getreplymailbody_gateway_htmlformat($strCompanyName,$strUserName,$strPassword,$strcompanyname1,$stremail1,$strurl1)
{
	$str_mailbody = "";
	$str_mailbody .= "<html>";
	$str_mailbody .= "<head>";
	$str_mailbody .= "	<title>Payment Gateway</title>";
	$str_mailbody .= "	<style>";
	$str_mailbody .= "	.TextBox{";
	$str_mailbody .= "	font-family:verdana;font-size:14px }";
	$str_mailbody .= "	</style>";
	$str_mailbody .= "</head>";
	$str_mailbody .= "<body topmargin='0' leftmargin='0'>";
	$str_mailbody .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mailbody .= "<tr>";
	$str_mailbody .= "	<td ></td>";
	$str_mailbody .= "</tr>";
	$str_mailbody .= "</table>";
	$str_mailbody .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center'  height='343'>";
	$str_mailbody .= "<tr>";
	$str_mailbody .= "	<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'>&nbsp;</td>";
	$str_mailbody .= "	<td height='83' valign='top' align='center' width='701' class='TextBox' bgcolor='#F1F5FA' colspan='2'>";
	$str_mailbody .= "   	<p align='left'><br>Dear $strCompanyName,<br><br>";
	$str_mailbody .= "   	Thank you for filling out the $strcompanyname1 pre-application.  You are now ready to login to your offshore account and complete the application process.  The entire process should take about 10 minutes. You'll need the following information to hand:<br><br>";
	$str_mailbody .= "	</td>";
	$str_mailbody .= "	<td height='1' valign='top' align='center' width='33' bgcolor='#FFFFFF' rowspan='3'><img border='0' src='http://24.244.141.134/admin/".$_SESSION["sessionCompanyLogoName"]."'></td>";
	$str_mailbody .= "</tr>";
	$str_mailbody .= "<tr>";
	$str_mailbody .= "	<td height='64' valign='top' align='center' width='72' class='TextBox' bgcolor='#F1F5FA'>";
	$str_mailbody .= "		<p align='left'>&nbsp;";
	$str_mailbody .= "	</td>";
	$str_mailbody .= "	<td height='64' valign='top' align='center' width='631' class='TextBox' bgcolor='#F1F5FA'>";
	$str_mailbody .= "	<ul>";
	$str_mailbody .= "		<li><p align='left'>Your company's registration number and registered address (if applicable)</li>";
	$str_mailbody .= "       &nbsp;";
	$str_mailbody .= "		<li><p align='left'>Your company's bank account details</li>";
	$str_mailbody .= "       &nbsp;";
	$str_mailbody .= "       <li><p align='left'>Copy of Driver's License or Passport</li>";
	$str_mailbody .= "       &nbsp;";
	$str_mailbody .= "		<li><p align='left'>Articles of Incorporation</li>";
	$str_mailbody .= "	</ul>";
	$str_mailbody .= "	</td>";
	$str_mailbody .= "</tr>";
	$str_mailbody .= "<tr>";
	$str_mailbody .= "	<td height='250' valign='top' align='center' width='701' class='TextBox' bgcolor='#F1F5FA' colspan='2'>";
	$str_mailbody .= "		<p align='left'>$strcompanyname1 is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments from an offshore environment.  Your login details to your admin area are listed below:<br><br>";
	$str_mailbody .= "		<font color='#0000FF'><a href='https://24.244.141.134'>https://24.244.141.134</a></font><br>";
	$str_mailbody .= "		<br>";
	$str_mailbody .= "       Username: $strUserName<br>";
	$str_mailbody .= "		Password:  $strPassword<br>";
	$str_mailbody .= "		<br>";
	$str_mailbody .= "		Once logged in, please click <b><font color='#FF0000'>  \"Start Here\"</font></b> and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at";
	$str_mailbody .= "		<font color='#0000FF'><a href='mailto:$stremail1'>$stremail1</a>.</font><br>";
	$str_mailbody .= "		<br>";
	$str_mailbody .= "		If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments from an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to $strcompanyname1!<br>";
	$str_mailbody .= "		<br>";
	$str_mailbody .= "		Sincerely,<br>";
	$str_mailbody .= "		<br>";
	$str_mailbody .= "		<font color='#0000FF'>$strcompanyname1</font><br>";
	$str_mailbody .= "	</td>";
	$str_mailbody .= "</tr>";
	$str_mailbody .= "</table>";
	$str_mailbody .= "</body>";
	$str_mailbody .= "</html>";
	return $str_mailbody;
}

function func_getreplymailbody_htmlformat($strCompanyName,$strUserName,$strPassword,$str_reference_num)
{										
	
	return nl2br(func_getreplymailbody($strCompanyName,$strUserName,$strPassword,$str_reference_num));
}

function func_getecommerce_mailbody_htmlformat()
{
	$str_ecommerce_mailbody = "";
	$str_ecommerce_mailbody .="<html>";
	$str_ecommerce_mailbody .="<head>";
	$str_ecommerce_mailbody .="<title>ecommerce_reciept</title>";
	$str_ecommerce_mailbody .="<style type='text/css'>";
	$str_ecommerce_mailbody .=".tx1{font-family:verdana;font-size:12px;color:black}";
	$str_ecommerce_mailbody .=".txred{font-family:verdana;font-size:12px;color:red;font-weight:bold}";
	$str_ecommerce_mailbody .=".tx2{font-family:verdana;font-size:10px;color:black}";
	$str_ecommerce_mailbody .=".txlink{font-family:verdana;font-size:12px;color:blue;text-decoration:none;font-weight:bold}";
	$str_ecommerce_mailbody .=".txlink:hover{font-family:verdana;font-size:12px;color:red;text-decoration:none;font-weight:bold}";
	$str_ecommerce_mailbody .=".mar{margin-left: 20px; margin-right: 20px; margin-top: 10px; margin-bottom: 10px;line-height:120%}";
	$str_ecommerce_mailbody .="</style>";
	$str_ecommerce_mailbody .="</head>";
	$str_ecommerce_mailbody .="<body topmargin='0' leftmargin='10'>";
	$str_ecommerce_mailbody .="<table border='0' cellpadding='0' cellspacing='0' width='700' >";
	$str_ecommerce_mailbody .="<tr><td align='right'><img alt='' border='0' src='http://www.etelegate.com/images/logo2os.jpg'></td>";
	$str_ecommerce_mailbody .="</tr></table>";
	$str_ecommerce_mailbody .="<table border='0' cellpadding='0' cellspacing='0' width='700' >";
	$str_ecommerce_mailbody .="<tr><td align='center' height='100' valign='middle' class='tx1'>Online billing receipt</td>";
	$str_ecommerce_mailbody .="</tr></table>";
	$str_ecommerce_mailbody .="<table border='0' cellpadding='0' cellspacing='0' width='700' >";
	$str_ecommerce_mailbody .="<tr><td align='left' height='30' valign='middle' class='tx1'>";
	$str_ecommerce_mailbody .=" <p class='mar'>Dear [customername],</p>";
	$str_ecommerce_mailbody .=" </td></tr>";
	$str_ecommerce_mailbody .=" <tr><td align='left' height='100' valign='top' class='tx1'>";
	$str_ecommerce_mailbody .="  <p class='mar' align='justify'>Thank you for your recent internet purchase.  You have made a purchase from [companyname] and your [cardtype] has been successfully charged $ [amount].<br><br>";
	$str_ecommerce_mailbody .="   On your current or next months credit card statement, [billingdescriptor] will appear. etelegate.com is the authorized billing company used to process credit card orders on behalf of [companyname], and confirms that the charge has been approved and debited from your account.";
	$str_ecommerce_mailbody .="   <br><br> eTelegate.com prides itself on customer service, customer satisfaction, and fraud prevention. For the protection of you, your financial institution and [companyname], an email copy was sent to your bank at the time of the sale for quality assurance and fraud prevention so that all parties involved understand that authorization was given by you the consumer.";
	$str_ecommerce_mailbody .="    </td>  </tr></table>";
	$str_ecommerce_mailbody .=" <table border='0' cellpadding='0' cellspacing='0' width='700' >";
	$str_ecommerce_mailbody .="  <tr>   <td align='center' height='40' valign='middle' class='txred'>WARNING</td>";
	$str_ecommerce_mailbody .="  </tr>  <tr>";
	$str_ecommerce_mailbody .="    <td align='left' height='137' valign='top' class='tx1'>";
	$str_ecommerce_mailbody .="      <p class='mar' align='justify'>etelegate.com reports all <u>Fraudulent Credit or Bankcard</u> transactions. The National District Attorney Fraud Task Force has been formed to assist merchants in recovering losses from the fraudulent use of Credit or Bankcards in Internet or phone commerce. Complaints will be filed with the <strong><u>District Attorney Fraud Unit in your city or jurisdiction for prosecution.</u></strong><br>";
	$str_ecommerce_mailbody .=" <br> <strong>For assistance with your purchase, please contact  [companyname]&nbsp;<br>";
	$str_ecommerce_mailbody .=" customer service at [companyemailaddress]</strong> </td> </tr>";
	$str_ecommerce_mailbody .="  <tr>  <td align='left' valign='top' class='tx1'> <p class='mar' align='left'><span class='tx2'>For billing inquiries and to contact etelegate.com directly, please email customer service at</span>";
	$str_ecommerce_mailbody .="   <a href='mailto:customerservice@etelegate.com' class='txlink'>customerservice@etelegate.com</a>";
	$str_ecommerce_mailbody .="    </td>  </tr>  <tr>";
	$str_ecommerce_mailbody .="    <td align='left' valign='middle' class='tx1' height='100'>";
	$str_ecommerce_mailbody .="    <p class='mar' align='left'>Charge Amount $ [chargeamount]<br>";
	$str_ecommerce_mailbody .="    Customer Info [name] , [address], [city], [state], [zip], [ccnumber] #";
	$str_ecommerce_mailbody .="    </td>  </tr></table></body></html>";
	return $str_ecommerce_mailbody;
}

function func_get_application_form($merchant_type)
{
	//global $HTTP_SESSION_VARS["sessionCompanyLogoName"];
	$str_application_form="";

	$str_application_form = "";
	$str_application_form .="<html>";
	$str_application_form .="<head>";
	$str_application_form .="<title>Application</title>";
	$str_application_form .="<style type='text/css'>";
	$str_application_form .=" .title {font-family:verdana;font-size:14px;font-weight:bold;color:black;}";
	$str_application_form .=" .tx1{font-family:verdana;font-size:12px;color:black}";
	$str_application_form .=" .tx1bold{background-color:lightblue;font-family:verdana;font-size:12px;font-weight:bold;color:black}";
	$str_application_form .=" .txred{font-family:verdana;font-size:12px;color:red;font-weight:bold}";
	$str_application_form .=" .tx2{font-family:verdana;font-size:10px;color:black}";
	$str_application_form .=" .txlink{font-family:verdana;font-size:12px;color:blue;text-decoration:none;font-weight:bold}";
	$str_application_form .=" .txlink:hover{font-family:verdana;font-size:12px;color:red;text-decoration:none;font-weight:bold}";
	$str_application_form .=" .mar{margin-left: 20px; margin-right: 20px; margin-top: 10px; margin-bottom: 10px;line-height:120%}";
	$str_application_form .="</style>";
	$str_application_form .="</head>";
	$str_application_form .="<body topmargin='0' leftmargin='10'>";
	$str_application_form .="<table border='0' cellpadding='0' cellspacing='0' width='700' >";
	$str_application_form .="<tr><td align='right'><img alt='' border='0' src='https://24.244.141.134/admin/".$_SESSION["sessionCompanyLogoName"]."'></td></tr>";
	$str_application_form .="</table><table border='1' cellpadding='0' cellspacing='0' width='700' bordercolor='lightblue'>";
	$str_application_form .="<tr><td align='center'>";

	$str_application_form .="<table border='0' cellpadding='0' cellspacing='5' width='650' >";
	$str_application_form .="<tr><td align='center' class='title'> Company Application Form";
	$str_application_form .="</td></tr></table>";

	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'> Company Information</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Company Name </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[str_company_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Legal Company Name </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[str_legal_company_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Type Of Company   </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[company_type] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Address  </td>";
	$str_application_form .="<td align='left' class='tx2'>[address] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>City    </td>";
	$str_application_form .="<td align='left' class='tx2'>[city] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Country  </td>";
	$str_application_form .="<td align='left' class='tx2'>[country] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>State    </td>";
	$str_application_form .="<td align='left' class='tx2'>[state] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Incorporated Country  </td>";
	$str_application_form .="<td align='left' class='tx2'>[inc_country] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Incorporated Number  </td>";
	$str_application_form .="<td align='left' class='tx2'>[inc_number] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Zipcode  </td>";
	$str_application_form .="<td align='left' class='tx2'>[zip_code] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Physical Company Address </td>";
	$str_application_form .="<td align='left' class='tx2'>[physical_company_address] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Phone  </td>";
	$str_application_form .="<td align='left' class='tx2'> [phone]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Cellular  </td>";
	$str_application_form .="<td align='left' class='tx2'> [cellular]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Fax Number   </td>";
	$str_application_form .="<td align='left' class='tx2'> [fax_number]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Fax DBA   </td>";
	$str_application_form .="<td align='left' class='tx2'> [fax_dba]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Maximum Ticket Amount  </td>";
	$str_application_form .="<td align='left' class='tx2'> [max_ticket_amt]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Minimum Ticket Amount  </td>";
	$str_application_form .="<td align='left' class='tx2'> [min_ticket_amt]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Technical Contact Details  </td>";
	$str_application_form .="<td align='left' class='tx2'> [tech_contact_details]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Administrative Contact Details  </td>";
	$str_application_form .="<td align='left' class='tx2'> [admin_contact_details]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Goods/Services List And Description  </td>";
	$str_application_form .="<td align='left' class='tx2'> [goods_list]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Currently Used Anti Fraud System  </td>";
	$str_application_form .="<td align='left' class='tx2'> [current_anti_fraud_system]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Customer Service Program  </td>";
	$str_application_form .="<td align='left' class='tx2'> [cust_service_program]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Customer Service Phone  </td>";
	$str_application_form .="<td align='left' class='tx2'> [cust_service_phone]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Refund Policy  </td>";
	$str_application_form .="<td align='left' class='tx2'> [refund_policy]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'><b> Previous Sales Volume </b></td>";
	$str_application_form .="<td align='left' class='tx2'> &nbsp;</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Last Month  </td>";
	$str_application_form .="<td align='left' class='tx2'> [volume_last_month]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> 30 days previous  </td>";
	$str_application_form .="<td align='left' class='tx2'> [volume_prev_30days]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> 60 days previous  </td>";
	$str_application_form .="<td align='left' class='tx2'> [volume_prev_60days]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Totals  </td>";
	$str_application_form .="<td align='left' class='tx2'> [totals]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'><b> Forecasted Volume With eTeleGate.com </b></td>";
	$str_application_form .="<td align='left' class='tx2'> &nbsp;</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> First Month  </td>";
	$str_application_form .="<td align='left' class='tx2'> [forecast_first_month]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Second Month  </td>";
	$str_application_form .="<td align='left' class='tx2'> [forecast_second_month]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Third Month  </td>";
	$str_application_form .="<td align='left' class='tx2'> [forecast_third_month]</td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'> User Information</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>First Name </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[title] [first_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Family Name  </td>";
	$str_application_form .="<td align='left' class='tx2'>[family_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Job title </td>";
	$str_application_form .="<td align='left' class='tx2'>[job_title] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Date of birth</td>";
	$str_application_form .="<td align='left' class='tx2'>[date_of_birth] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Sex</td>";
	$str_application_form .="<td align='left' class='tx2'>[sex] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Address</td>";
	$str_application_form .="<td align='left' class='tx2'>[user_address] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Zipcode</td>";
	$str_application_form .="<td align='left' class='tx2'>[user_zipcode] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Job title</td>";
	$str_application_form .="<td align='left' class='tx2'>[job_title] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Contact email address:  </td>";
	$str_application_form .="<td align='left' class='tx2'>[contact_email] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Telephone number    </td>";
	$str_application_form .="<td align='left' class='tx2'>[contact_phone] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Residence number</td>";
	$str_application_form .="<td align='left' class='tx2'>[user_residence_number] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Fax number</td>";
	$str_application_form .="<td align='left' class='tx2'>[user_fax_number] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Where u heard about etelegate.com?</td>";
	$str_application_form .="<td align='left' class='tx2'>[how_about_us] </td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'> Website Information</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Email </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[email] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>URL  </td>";
	$str_application_form .="<td align='left' class='tx2'>[urls] </td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

/*		$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'> E-mail Template Setup </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Merchant Name  </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[merchant_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Toll Free Number   </td>";
	$str_application_form .="<td align='left' class='tx2'> [toll_free_number]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Retrieval Number     </td>";
	$str_application_form .="<td align='left' class='tx2'> [retrieval_number]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Security Number   </td>";
	$str_application_form .="<td align='left' class='tx2'>[security_number] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Processor    </td>";
	$str_application_form .="<td align='left' class='tx2'>[processor] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Billing Descriptor      </td>";
	$str_application_form .="<td align='left' class='tx2'>[billing_descriptor] </td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'> Ledger Constants  </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Charge Back $  </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[charge_back] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Credit $ </td>";
	$str_application_form .="<td align='left' class='tx2'> [credit]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Transaction Fee $  </td>";
	$str_application_form .="<td align='left' class='tx2'>[transaction_fee] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Voice Authorization Fee $  </td>";
	$str_application_form .="<td align='left' class='tx2'>[voice_authorization_fee] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Discount Rate %  </td>";
	$str_application_form .="<td align='left' class='tx2'>[discount_rate] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Reserve %  </td>";
	$str_application_form .="<td align='left' class='tx2'>[reserve] </td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";
*/
	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'>Process Information  </td></tr>";
//		$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Company Status </td>";
//		$str_application_form .="<td align='left' class='tx2' width='60%'>[company_status] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Merchant Type </td>";
	$str_application_form .="<td align='left' class='tx2'> [merchant_type]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Projected monthly sales   volume $   </td>";
	$str_application_form .="<td align='left' class='tx2'> [volumenumber]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Average ticket  </td>";
	$str_application_form .="<td align='left' class='tx2'> [avgticket]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Charge back % </td>";
	$str_application_form .="<td align='left' class='tx2'> [charge_back_per]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Billing Descriptor</td>";
	$str_application_form .="<td align='left' class='tx2'>[billing_descriptor]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Previous processing  </td>";
	$str_application_form .="<td align='left' class='tx2'>[previous_processing]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Recurring billing  </td>";
	$str_application_form .="<td align='left' class='tx2'>[recurring_billing]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Currently Processing</td>";
	$str_application_form .="<td align='left' class='tx2'>[currently_processing]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Currency</td>";
	$str_application_form .="<td align='left' class='tx2'>[currency]</td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

	if ($merchant_type == "Telemarketing") {
		$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
		$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'>Verification Script  </td></tr>";
		$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Package Name</td>";
		$str_application_form .="<td align='left' class='tx2' width='60%'>[package_name] </td></tr>";
		$str_application_form .="<tr><td align='left' class='tx1'>Package Product Service</td>";
		$str_application_form .="<td align='left' class='tx2'> [package_service]</td></tr>";
		$str_application_form .="<tr><td align='left' class='tx1'>Package Price</td>";
		$str_application_form .="<td align='left' class='tx2'> [package_price]</td></tr>";
		$str_application_form .="<tr><td align='left' class='tx1'>Refund Policy</td>";
		$str_application_form .="<td align='left' class='tx2'> [script_refund_policy]</td></tr>";
		$str_application_form .="<tr><td align='left' class='tx1'>Description</td>";
		$str_application_form .="<td align='left' class='tx2'> [package_description]</td></tr>";
		$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
		$str_application_form .="</table>";

	}

	$str_application_form .="<table border='0' cellpadding='2' cellspacing='5' width='550' >";
	$str_application_form .="<tr><td align='center' class='tx1bold' colspan='2'>Bank Process Information  </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Bank name </td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[company_bank] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1' width='40%'>Beneficiary Name</td>";
	$str_application_form .="<td align='left' class='tx2' width='60%'>[beneficiary_name] </td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Name On Bank Account</td>";
	$str_application_form .="<td align='left' class='tx2'> [name_on_account]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Country </td>";
	$str_application_form .="<td align='left' class='tx2'> [bank_country]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'>Telephone number </td>";
	$str_application_form .="<td align='left' class='tx2'> [bank_phone]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Sort Code/Branch Number  </td>";
	$str_application_form .="<td align='left' class='tx2'> [bank_sort_code]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Bank Account number </td>";
	$str_application_form .="<td align='left' class='tx2'> [bank_account_number]</td></tr>";
	$str_application_form .="<tr><td align='left' class='tx1'> Bank Swift Code</td>";
	$str_application_form .="<td align='left' class='tx2'> [bank_swift_code]</td></tr>";
	$str_application_form .="<tr><td class='tx1' colspan='2'> <hr noshade> </td></tr>";
	$str_application_form .="</table>";

	$str_application_form .="</td></tr></table>";
	$str_application_form .="</body></html>";

	return $str_application_form;
}

function func_reseller_loginletter_htmlformat() {
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
	$str_mail_string .= "<p>You have made the right choice when choosing eTeleGate.com as your global partner for referring your medium to high risk merchants for Offshore Credit Card, ACH, and WEB900 Billing. As a preferred reseller of eTeleGate.com, you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.</p>";
	$str_mail_string .= "<p>Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.</p>";
	$str_mail_string .= "<p>Please login to your account and finish your new reseller registration. Your login details to your admin area are listed below:</p>";
	$str_mail_string .= "<p><a href='https://www.etelegate.com'>https://www.etelegate.com</a></p>";
	$str_mail_string .= "<p>Username: [UserName]<br>Password:  [PassWord]</p><p>Once logged in, please click <strong><font color='#FF0000'>'Start Here'</font></strong>";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred etelegate partner. If you have any questions, please feel free to email ";
	$str_mail_string .= "our sales department at <a href='mailto:partners@etelegate.com'>partners@etelegate.com</a>.</p>";
	$str_mail_string .= "<p>Etelegate.com has built its reputation as being “The Offshore Processor”.  You will not find a more reliable or reseller friendly processor in the industry today.  We offer unique features like 24/7/365 merchant and reseller support, so day or night we are here to help you or your merchants with questions, payroll, integration, or anything needed. </p> ";
	$str_mail_string .= "<p>In closing, we have hundreds of successful resellers making tens of thousands of dollars or more month after month, and so can you.  Our one of a kind gateway lets you view your referred merchants and how much they are processing, as well as calculating your commissions in real-time!  On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to etelegate.com from the month prior.  Let us work together and benefit from your referrals, and our merchant processing experience.  Again, there is no other processor that offers resellers such a streamline way to refer new business and make sky is the limit paychecks.  We look forward to working with you. </p>";
	$str_mail_string .= "<p>Sincerely,</p><p><a href='https://www.eTeleGate.com'>www.eTeleGate.com</a></p><p></p></td></tr></table>";
	$str_mail_string .= "<table border='0' cellpadding='0' cellspacing='0' width='772' align='center'>";
	$str_mail_string .= "<tr><td height='15' width='766'>&nbsp;</td></tr></table></body></html>";

	return $str_mail_string;
}

function func_reseller_loginletter() {
	$str_mail_string = "Dear [ResellerCompanyName],\r\n\r\n";
	$str_mail_string .= "You have made the right choice when choosing eTeleGate.com as your global partner for referring your medium to high risk merchants for Offshore Credit Card, ACH, and WEB900 Billing. As a preferred reseller of eTeleGate.com, you will be entitled to refer new business and potential accounts to our company and then receive an ongoing residual commission for your involvement.\r\n\r\n";
	$str_mail_string .= "Your responsibilities as a preferred reseller will include obtaining a clear understanding of how the set up procedure and application are handled and further representing these facts to the new potential merchant.\r\n\r\n";
	$str_mail_string .= "Please login to your account and finish your new reseller registration. Your login details to your admin area are listed below:\r\n\r\n";
	$str_mail_string .= "https://www.etelegate.com \r\n\r\n";
	$str_mail_string .= "Username: [UserName]\r\n";
	$str_mail_string .= "Password:  [PassWord]\r\n\r\n";
	$str_mail_string .= "Click the dropdown box and select 'Reseller'\r\n\r\n";
	$str_mail_string .= "Once logged in, please click 'Start Here'";
	$str_mail_string .= "and follow the step-by-step directions to finalize your status as a preferred etelegate partner. If you have any questions, please feel free to email our sales department at partners@etelegate.com. \r\n\r\n";
	$str_mail_string .= "Etelegate.com has built its reputation as being “The Offshore Processor”.  You will not find a more reliable or reseller friendly processor in the industry today.  We offer unique features like 24/7/365 merchant and reseller support, so day or night we are here to help you or your merchants with questions, payroll, integration, or anything needed.\r\n\r\n";
	$str_mail_string .= "In closing, we have hundreds of successful resellers making tens of thousands of dollars or more month after month, and so can you.  Our one of a kind gateway lets you view your referred merchants and how much they are processing, as well as calculating your commissions in real-time!  On the 10th of each month a wire transfer is sent directly to your bank account for the business that you have brought to etelegate.com from the month prior.  Let us work together and benefit from your referrals, and our merchant processing experience.  Again, there is no other processor that offers resellers such a streamline way to refer new business and make sky is the limit paychecks.  We look forward to working with you. \r\n\r\n";
	$str_mail_string .= "Sincerely,\r\n\r\n";
	$str_mail_string .= "www.eTeleGate.com\r\n";

	return $str_mail_string;
}

function func_getecommerce_mailbody()
{
	$str_ecommerce_mailbody = "CC-Service Team:   customerservice@etelegate.com \r\n\r\n";
	$str_ecommerce_mailbody .= "Dear [CustomerName], \r\n\r\n";
	$str_ecommerce_mailbody .= "Thank you for your online order. \r\n\r\n";
	$str_ecommerce_mailbody .= "The payment due shall be charged to the following credit card:\r\n";
	$str_ecommerce_mailbody .= "XXXX-XXXX-XXXX-[CreditCardNumber], expiring [CardExpiry] \r\n\r\n";
	$str_ecommerce_mailbody .= "Your credit card statement will contain the following information regarding this transaction: \r\n";
	$str_ecommerce_mailbody .= "[BillingDescriptor] \r\n\r\n";
	$str_ecommerce_mailbody .= "Your order reference number : [OrderReferenceNumber]\r\n\r\n";
	$str_ecommerce_mailbody .= "Your order was processed at : [OrderTime] CET \r\n\r\n";
	$str_ecommerce_mailbody .= "Your product description is : [ProductDescription] \r\n\r\n";
	//$str_ecommerce_mailbody .= "Your order total is: [ChargeAmount] [Currency] \r\n\r\n";
	$str_ecommerce_mailbody .="Your credit card has been charged [Currency] [ChargeAmount] amount Today \r\n\r\n";
	$str_ecommerce_mailbody .= "You may receive an additional confirmation mail directly from the online shop.\r\n";
	$str_ecommerce_mailbody .= "For further questions please reply to this email or contact our CC-Service Team on [customerservicemail] customerservice@etelegate.com and to lookup your transaction online, please visit our automatic customer service transaction lookup center at www.etelegate.net .  \r\n\r\n";
	$str_ecommerce_mailbody .= "Kind regards, \r\n";
	$str_ecommerce_mailbody .= "CC-Service Team \r\n";

	return $str_ecommerce_mailbody;
}
function func_get_gatewayecommerce_mailbody()
{
	$str_ecommerce_mailbody = "CC-Service Team:   [GatewayCompanyMail] \r\n\r\n";
	$str_ecommerce_mailbody .= "Dear [CustomerName], \r\n\r\n";
	$str_ecommerce_mailbody .= "Thank you for your online order. \r\n\r\n";
	$str_ecommerce_mailbody .= "The payment due shall be charged to the following credit card:\r\n";
	$str_ecommerce_mailbody .= "XXXX-XXXX-XXXX-[CreditCardNumber], expiring [CardExpiry] \r\n\r\n";
	$str_ecommerce_mailbody .= "Your credit card statement will contain the following information regarding this transaction: \r\n";
	$str_ecommerce_mailbody .= "[BillingDescriptor] \r\n\r\n";
	$str_ecommerce_mailbody .= "Your order reference number : [OrderReferenceNumber]\r\n\r\n";
	$str_ecommerce_mailbody .= "Your order was processed at : [OrderTime] CET \r\n\r\n";
	$str_ecommerce_mailbody .= "Your product description is : [ProductDescription] \r\n\r\n";
	$str_ecommerce_mailbody .= "[Currency] [ChargeAmount]  has been charged to your credit card today\r\n\r\n";
	$str_ecommerce_mailbody .= "You may receive an additional confirmation mail directly from the online shop.\r\n";
	$str_ecommerce_mailbody .= "For further questions please reply to this email or contact our CC-Service Team on [customerservicemail] [GatewayCompanyMail] and to lookup your transaction online, please visit our automatic customer service transaction lookup center at https://24.244.141.179 .  \r\n\r\n";
	$str_ecommerce_mailbody .= "Kind regards, \r\n";
	$str_ecommerce_mailbody .= "CC-Service Team \r\n";

	return $str_ecommerce_mailbody;
}

?>