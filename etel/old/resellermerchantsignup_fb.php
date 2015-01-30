<?php

	require_once("includes/indexheader.php");
	etel_smarty_display("main_header.tpl");
	
include("includes/function2.php");
include("admin/includes/mailbody_replytemplate.php");
//include("reseller/includes/mail_letter_template.php");

	$user_reference_num="";
	$sgatewayLogo = (isset($HTTP_POST_VARS['gatewaylogo'])?quote_smart($HTTP_POST_VARS['gatewaylogo']):"");
	$ResellerCompanyName = (isset($HTTP_POST_VARS['ResellerCompanyName'])?quote_smart($HTTP_POST_VARS['ResellerCompanyName']):"");
	$companyname = (isset($HTTP_POST_VARS['company'])?quote_smart($HTTP_POST_VARS['company']):"");
	$reseller_id = (isset($HTTP_POST_VARS['reseller_id'])?quote_smart($HTTP_POST_VARS['reseller_id']):"");
	$gateway_id = (isset($HTTP_POST_VARS['gateway_id'])?quote_smart($HTTP_POST_VARS['gateway_id']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	//$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	
	$user_companyexist =0;	

	$transaction_type = (isset($HTTP_POST_VARS['rad_order_type'])?quote_smart($HTTP_POST_VARS['rad_order_type']):"");
	$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
	$voulmeNumber = (isset($HTTP_POST_VARS['merchant_voulme'])?quote_smart($HTTP_POST_VARS['merchant_voulme']):"");
	$reseller = (isset($HTTP_POST_VARS['reseller'])?quote_smart($HTTP_POST_VARS['reseller']):"");
	$str_pass1 = get_rand_id(1);
	$str_pass2 = get_rand_id(1);
	$str_pass3 = rand(0,9);
	$str_pass4 = get_rand_id(1);
	$str_pass5 = get_rand_id(1);
	$str_pass6 = get_rand_id(1);
	$password = strtolower("$str_pass1$str_pass2$str_pass3$str_pass4$str_pass5$str_pass6");
	$msgtodisplay = "";
	if ($transaction_type == "tele")
	{
		$send_ecommercemail = 0;
	}
	else
	{
		$send_ecommercemail = 1;
	}

	
	$current_date_time = func_get_current_date_time();
	$user_nameexist=0;
	
	if($companyname)
	{		
				$user_nameexist =func_checkUsernameExistInAnyTable($username,$cnn_cs);
				$user_emailexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
				$user_companyexist=func_checkCompanynameExistInAnyTable($companyname,$cnn_cs);
				$qry_select_user = "select username  from cs_companydetails where ( companyname='$companyname' or email='$email' )";
				//print $qry_select_user;
				if(!($show_sql = mysql_query($qry_select_user)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} 
				  elseif($user_nameexist==1) 
				{
					$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing username !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
				} 
				  elseif($user_companyexist==1)
				{
					$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing company name !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
				} 
				  elseif($user_emailexist==1)
				{
					$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing email id !! </font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
				}
				  else
				{
							$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,email,volumenumber,activeuser,transaction_type,how_about_us,reseller_other,date_added,send_ecommercemail,reseller_id , gateway_id )";
							$qry_insert_user .= " values('$username','$password','$companyname','$email','$voulmeNumber',0,'$transaction_type','$how_about_us','$reseller','$current_date_time',$send_ecommercemail,$reseller_id,$gateway_id)";
							if(!($show_sql =mysql_query($qry_insert_user)))
							{
								print(mysql_errno().": ".mysql_error()."<BR>");
								print("Cannot execute query <br>");
								print($qry_insert_user);
								exit();
							}
								else 
							{
								$is_success=0;	
								$user_id=mysql_insert_id();
								$user_reference_num=func_User_Ref_No($user_id);
								$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,'userId',$user_id,$cnn_cs);
								if($is_success==1)
								{
									
												if($gateway_id==-1)
												{	
														/************** to sent registration mail to the  company*********/
														$qry_select_sent = "Select mail_id,mail_sent from cs_registrationmail";
														$rst_select_sent = mysql_query($qry_select_sent,$cnn_cs);
																		if (mysql_num_rows($rst_select_sent)>0)
																		{
																			$mail_sent = mysql_result($rst_select_sent,0,1);
																		}
																		
																		$email_from = "sales@etelegate.com";
																		$email_to ="sales@etelegate.com";
																		$email_subject = "Registration Confirmation";
																		$transactiontype=func_get_merchant_name($transaction_type);
																		$email_message = func_getreplymailbody_admin($companyname,$username,$password,$user_reference_num,$transactiontype ,$how_about_us,$voulmeNumber);
																		
																		if(!func_send_mail($email_from,$email_to,$email_subject,$email_message))
																		{
																			print "An error encountered while sending the mail.";
																							
																		}
																					if($mail_sent==1)
																					{

																					$emailData["full_name"] = $ResellerCompanyName;
																					$emailData["email"] = $email;
																					$emailData["resellername"] = $ResellerCompanyName;
																					$emailData["companyname"] = $companyname;
																					$emailData["username"] = $username;
																					$emailData["password"] = $password;
																					$emailData["gateway_select"] = $companyInfo['gateway_id'];
																					
																					
																					$emailContents = get_email_template("merchant_referral_letter",$emailData);
																					send_email_template("merchant_referral_letter",$emailData);
																					$msgtodisplay =  $emailContents['et_htmlformat'];
																					}

														
											}
								}// is suceess ends here
										
						}
	
			 }
	}	

 	//print func_getreplymailbody_htmlformat($companyname,$username,$password);
 
 	print $msgtodisplay;
	etel_smarty_display("main_footer.tpl");

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
	$str_mail_string .= "<p align='left'></td><td height='64' valign='top' align='left' width='631' class='TextBox' bgcolor='#F1F5FA'>";
	$str_mail_string .= "<ul><br><li>Your company's registration number and registered address (if applicable)</li>";
	$str_mail_string .= "<li>Your company's bank account details</li><li>Copy of Driver's License or Passport</li>";
	$str_mail_string .= "<li>Articles of Incorporation</li></ul></td></tr><tr><td height='250' valign='top' align='center' width='701' class='TextBox' bgcolor='#F1F5FA' colspan='2'>";
	$str_mail_string .= "<p align='left'>etelegate.com is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments form an offshore environment.  Your login details to your admin area are listed below:<br>";
	$str_mail_string .= "<br><font color='#0000FF'><a href='https://www.etelegate.com'>https://www.etelegate.com</a></font><br>";
	$str_mail_string .= "<br>Username: [UserName]<br>Password:  [PassWord]<br>Reference No:  [ReferenceNo]<br><br>Once logged in, please click <b><font color='#FF0000'>  'Start Here'</font></b> and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at ";
	$str_mail_string .= "<font color='#0000FF'><a href='mailto:sales@etelegate.com'>sales@etelegate.com</a>.</font><br>";
	$str_mail_string .= "<br>If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments form an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to etelegate.com!<br>";
	$str_mail_string .= "<br>Sincerely,<br><br><font color='#0000FF'><a href='https://www.etelegate.com'>www.etelegate.com</a></font>";
	$str_mail_string .= "</td></tr></table><table border='0' cellpadding='0' cellspacing='0' width='772' align='center' bgcolor='' >";
	$str_mail_string .= "<tr><td height='15'  bgcolor='' width='766'>&nbsp;</td></tr></table></body></html>";
	
	return $str_mail_string;
}
function func_reseller_merchant_loginletter() {
	$str_mail_string = "Dear [CompanyName],\r\n\r\n";
	$str_mail_string .= "[ResellerCompanyName] has registered you into the etelegate system. You are now ready to login to the system and apply for your new offshore merchant account by completing the application process. The entire process should take about 10 minutes, and you'll need the following information on hand:\r\n\r\n";
	$str_mail_string .= "		Your company's registration number and registered address (if applicable)\r\n";
	$str_mail_string .= "		Your company's bank account details\r\n";
	$str_mail_string .= "		Copy of Driver's License or Passport\r\n";
	$str_mail_string .= "		Articles of Incorporation\r\n\r\n";
	$str_mail_string .= "etelegate.com is one of the largest European credit card processors today, helping company's worldwide process credit cards and ACH payments form an offshore environment.  Your login details to your admin area are listed below:\r\n\r\n";
	$str_mail_string .= "https://www.etelegate.com\r\n\r\n";
	$str_mail_string .= "Username: [UserName]\r\n";
	$str_mail_string .= "Password:  [PassWord]\r\n\r\n";
	$str_mail_string .= "Reference Number: [ReferenceNumber]\r\n\r\n";
	$str_mail_string .= "Once logged in, please click   'Start Here' and follow the step-by-step directions to finalize your new offshore merchant account.  If you have any questions, please feel free to email our sales department at ";
	$str_mail_string .= "sales@etelegate.com.\r\n\r\n";
	$str_mail_string .= "If you're business is considered high risk, high volume, offshore, startup, or anything in between, we can help you today.  Located and operating from the UK allows us to process all of your payments form an offshore environment and ensure your business is kept private. We look forward to getting your offshore merchant account set up and processing your company's payments.  Welcome to etelegate.com!\r\n\r\n";
	$str_mail_string .= "Sincerely,\r\n\r\n";
	$str_mail_string .= "www.etelegate.com\r\n";

	return $str_mail_string;
}

?>


