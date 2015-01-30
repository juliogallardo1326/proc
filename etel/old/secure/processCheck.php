<?php
chdir("..");
session_start();
include 'includes/dbconnection.php';
include 'includes/function2.php';
require_once('includes/function.php');
include 'admin/includes/mailbody_replytemplate.php';

$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$td_enable_rebill = 0;
if($mt_subAccount > -1) $td_enable_rebill = 1;
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$integration_mode = $_SESSION['integration_mode'];
$companyid = $_SESSION['companyid'];
$site_id = $_SESSION['cs_ID'];
$amount = $_SESSION['amount'];
$from_url = $_SESSION['from_url'];
$td_product_id = $_SESSION['td_product_id'];

$param_1 = $_SESSION['param_1'];
$param_2 = $_SESSION['param_2'];
$param_3 = $_SESSION['param_3'];

$td_recur_next_date = $_SESSION['td_recur_next_date'];
if($amount <=0) die("Invalid Charge Amount");
$sender ="sales@etelegate.com";

$bank_check="";
$return_message="";
if($companyid =="") {
	$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	print $msgtodisplay;
	$return_message = "UIN";
	exit();	
}

$opt_chk_day = date("d");
$opt_chk_month = date("m");
$opt_chk_year = date("Y");
$opt_bill_day = date("d");
$opt_bill_month = date("m");
$opt_bill_year = date("Y");
$trans_id=0;
$send_mails=0;
$bank_return="";
$firstname = (isset($HTTP_POST_VARS['firstname'])?Trim($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?Trim($HTTP_POST_VARS['lastname']):"");
$td_username= (isset($HTTP_POST_VARS['td_username'])?trim($HTTP_POST_VARS['td_username']):"");
$td_password= (isset($HTTP_POST_VARS['td_password'])?trim($HTTP_POST_VARS['td_password']):"");
$address= (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?Trim($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?Trim($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
$zip= (isset($HTTP_POST_VARS['zip'])?Trim($HTTP_POST_VARS['zip']):"");
$phonenumber= (isset($HTTP_POST_VARS['phonenumber'])?Trim($HTTP_POST_VARS['phonenumber']):"");
$chequenumber= (isset($HTTP_POST_VARS['chequenumber'])?Trim($HTTP_POST_VARS['chequenumber']):"");
$chequetype= (isset($HTTP_POST_VARS['chequetype'])?Trim($HTTP_POST_VARS['chequetype']):"");
$accounttype= (isset($HTTP_POST_VARS['accounttype'])?Trim($HTTP_POST_VARS['accounttype']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?Trim($HTTP_POST_VARS["opt_bill_year"]):$opt_bill_year);
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?Trim($HTTP_POST_VARS["opt_bill_month"]):$opt_bill_month);
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?Trim($HTTP_POST_VARS["opt_bill_day"]):$opt_bill_day);
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
$bankname = (isset($HTTP_POST_VARS['bankname'])?Trim($HTTP_POST_VARS['bankname']):"");
$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?Trim($HTTP_POST_VARS['bankroutingcode']):"");
$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?Trim($HTTP_POST_VARS['bankaccountno']):"");
$email =  (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?Trim($HTTP_POST_VARS['authorizationno']):"");
$shipping= (isset($HTTP_POST_VARS['shippingno'])?Trim($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?Trim($HTTP_POST_VARS['securityno']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?Trim($HTTP_POST_VARS['driverlicense']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?Trim($HTTP_POST_VARS['licensestate']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?Trim($HTTP_POST_VARS['misc']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?Trim($HTTP_POST_VARS['domain1']):"");

$ipaddress=$_SESSION['ipaddress'];

$dateToEnter = func_get_current_date_time(); //EST Time.
$str_atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?Trim($HTTP_POST_VARS['atm_verify']):"");
$productDescription= (isset($HTTP_POST_VARS['txtproductDescription'])?Trim($HTTP_POST_VARS['txtproductDescription']):"");
$str_currency = (isset($HTTP_POST_VARS['currency_code'])?Trim($HTTP_POST_VARS['currency_code']):"");
//rebilling details
$str_recur_date = (isset($HTTP_POST_VARS['chk_recur_date'])?trim($HTTP_POST_VARS['chk_recur_date']):"");
$str_recurdate_mode = (isset($HTTP_POST_VARS['recurdatemode'])?trim($HTTP_POST_VARS['recurdatemode']):"");
$i_recur_day = (isset($HTTP_POST_VARS['recur_day'])?trim($HTTP_POST_VARS['recur_day']):"");
$i_recur_week = (isset($HTTP_POST_VARS['recur_week'])?trim($HTTP_POST_VARS['recur_week']):"");
$i_recur_month = (isset($HTTP_POST_VARS['recur_month'])?trim($HTTP_POST_VARS['recur_month']):"");
$i_recur_year_day = (isset($HTTP_POST_VARS['recur_year_day'])?trim($HTTP_POST_VARS['recur_year_day']):"");
$i_recur_year_month = (isset($HTTP_POST_VARS['recur_year_month'])?trim($HTTP_POST_VARS['recur_year_month']):"");
$i_recur_start_month = (isset($HTTP_POST_VARS['opt_recur_month'])?trim($HTTP_POST_VARS['opt_recur_month']):"");
$i_recur_start_day = (isset($HTTP_POST_VARS['opt_recur_day'])?trim($HTTP_POST_VARS['opt_recur_day']):"");
$i_recur_start_year = (isset($HTTP_POST_VARS['opt_recur_year'])?trim($HTTP_POST_VARS['opt_recur_year']):"");
$str_recur_start_date = "$i_recur_start_year-$i_recur_start_month-$i_recur_start_day";
$i_recur_charge = (isset($HTTP_POST_VARS['recur_charge'])?trim($HTTP_POST_VARS['recur_charge']):"");
$i_recur_times = (isset($HTTP_POST_VARS['recur_times'])?trim($HTTP_POST_VARS['recur_times']):"");
$str_atm_verify = (isset($HTTP_POST_VARS['atm_verify'])?trim($HTTP_POST_VARS['atm_verify']):"");
if($accounttype=="checking") {
	$account_type ="C";
} else {
	$account_type ="S";
}

if($td_username){
	// Check to see if user and pass exist in system
	$sql="SELECT * FROM `cs_transactiondetails` WHERE `td_site_ID` = '$site_id' AND `td_username` = '$td_username' AND `td_password` = '$td_password' ";
	$result=mysql_query($sql,$cnn_cs) or die(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
	$num = mysql_num_rows($result);
	if($num>=1) 
	{
		$msgdisplay="<font face='verdana' size='2' color='black'>Username and Password exist. Please choose another Username and Password.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay; 
		die();
	}
}

if($amount)
{
$yyyy=date("Y");
$mm=date("m");
$dd=date("d");
$hr=date("G");
$mn=date("i");
$tt=date("A");

	if ($str_atm_verify == "Y") {
		//$ret_integration_result = func_bank_integration_result($firstname,$lastname,$amount,$account_type,$bankaccountno,$bankroutingcode);
		// Not live
		$ret_integration_result = "A";
		$ret_integration_resultarray=split(",",$ret_integration_result);
		$decline_response_code = func_decline_responsecode($ret_integration_resultarray[2]);
		if($ret_integration_result[0] =='A') {
			$bank_return = "Success.";
		}else if($ret_integration_resultarray[0] =='D' && $decline_response_code !="Insufficient funds") {
			$bank_return = "Declined.";
			$msgdisplay="<font face='verdana' size='2' color='black'>ATM Verification declined from bank due to $ret_integration_resultarray[1] $decline_response_code, $ret_integration_resultarray[2].</font>";			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			print $msgtodisplay;
			$return_message = "BDC";
			exit();	
		}else if($ret_integration_resultarray[0] =='E') {
			$bank_return = "Error.";
			$msgdisplay="<font face='verdana' size='2' color='black'>ATM Verification processing error.</font>";			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			print $msgtodisplay;
			$return_message = "BER";
			exit();	
		}else if($decline_response_code !="Insufficient funds"){
			$bank_return = "Error.";
			$msgdisplay="<font face='verdana' size='2' color='black'>System processing error.</font>";			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			print $msgtodisplay;
			$return_message = "SER";
			exit();	
		}
	}
if($voiceauth!="") {	
	$auth_status = func_isauthorisationno_exists($voiceauth,$phonenumber,$companyid,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$phonenumber,$companyid,$cnn_cs);
	}
	if ($auth_status != "")
	{
		$msgdisplay="<font face='verdana' size='2' color='black'>Voice authorization id already exist.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		$return_message = "VID";
		exit();	
	}
}	
		$qrt_select_suspend = "Select suspenduser from cs_companydetails where userid='$companyid'";
	
	if(!($show_suspend_Sql= mysql_query($qrt_select_suspend,$cnn_cs))) 
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		$return_message = "INT";
		exit();
	}
	else
	{
		$suspend =  mysql_fetch_array($show_suspend_Sql,$cnn_cs);
		if($suspend[0]=="YES") {
			$msgdisplay="<font face='verdana' size='2' color='black'>Your transaction has been suspended by the administrator.</font>";			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			print $msgtodisplay;
			$return_message = "SUP";
			exit();	
		}
	}
	if($state=="- - -Select- - -") 
	{
		$state=null;
	}
	if($licensestate=="- - -Select- - -") 
	{
		$licensestate=null;
	}
	
	$nextRecurDate = strtotime( $transaction['td_recur_next_date'])+60*60*24*$subAcc['recur_day'];
	
	
		$qrt_select_company = "Select companyname,transaction_type,ch_billingdescriptor,email,send_mail,send_ecommercemail,gateway_id,bank_check from cs_companydetails where userid='$companyid'";
	
	if(!($show_sql_run = mysql_query($qrt_select_company,$cnn_cs)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		$return_message = "INT";
		exit();
	}
	else
	{
		$company_name = mysql_fetch_array($show_sql_run,$cnn_cs);
		$transaction_type = mysql_result($show_sql_run,0,1);
		$billingdescriptor = mysql_result($show_sql_run,0,2);
		$fromaddress = mysql_result($show_sql_run,0,3);
		$send_mails = mysql_result($show_sql_run,0,4);
		$send_ecommercemail = mysql_result($show_sql_run,0,5);
		$str_gateway_id = mysql_result($show_sql_run,0,6);
		$bank_check = mysql_result($show_sql_run,0,7);
		if(mysql_num_rows($show_sql_run)== 0) 
		{
		$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		$return_message = "UIN";
		exit();	
		} 
		else 
		{
			$int_table = "cs_test_transactiondetails";
			if ($integration_mode == "Live") $int_table = "cs_transactiondetails";
			$qrt_insert_details = "insert into $int_table (td_product_id,name,surname,phonenumber,address,checkorcard,CCnumber,accounttype,country,city,state,zipcode,checktype,amount,transactionDate,bankname,bankroutingcode,bankaccountnumber,misc,cancelstatus,status,userid ,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,passStatus,pass_count,licensestate,email,productdescription,company_usertype,company_user_id,currencytype,td_is_pending_check,return_url,from_url,bank_id,td_site_ID,td_username,td_password,td_recur_next_date,td_rebillingID,td_enable_rebill) 
			values('$td_product_id','$firstname','$lastname','$phonenumber','$address','C','$chequenumber','$accounttype','$country','$city','$state','$zip','$chequetype','$amount','$dateToEnter','$bankname','$bankroutingcode','$bankaccountno','$misc','N','',$companyid,'$ipaddress','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','PE',0,'$licensestate','$email','$productDescription',4,$companyid,'$str_currency','1','$i_return_url','$from_url','$bank_CreditcardId','$site_id','$td_username','$td_password','$td_recur_next_date','$mt_subAccount','$td_enable_rebill')"; 

			if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query");
				$return_message = "INT";
				exit();
			}
			else
			{
		//changed
		$trans_id = mysql_insert_id(); 
		func_update_rate($companyid,$trans_id,$cnn_cs,"ch");
		//func_ins_bankrates($trans_id,$bank_check,$cnn_cs);
		$ref_number=func_Trans_Ref_No($trans_id );
		$updateSuccess="";
		$updateSuccess=func_update_single_field($int_table,'reference_number',$ref_number,'transactionId',$trans_id,$cnn_cs);
		if($updateSuccess=1){
			$reference_number=$ref_number;
		}	
		if($reference_number !="") {
			$return_message="SUC";
		}
		//inserting data into rebilling section
		$str_fields = "";
		$str_values = "";

		if($str_recur_date == "Y")
		{
			if($str_recurdate_mode != "")
			{
				$str_fields = ",recur_mode";
				$str_values = ",'".$str_recurdate_mode."'";
				if($str_recurdate_mode == "D")
				{
					if($i_recur_day != "")
					{
						$str_fields .= ",recur_day";
						$str_values .= ",".$i_recur_day;
					}
				}
				else if($str_recurdate_mode == "W")
				{
					if($i_recur_week != "")
					{
						$str_fields .= ",recur_week";
						$str_values .= ",".$i_recur_week;
					}
				}
				else if($str_recurdate_mode == "M")
				{
					if($i_recur_month != "")
					{
						$str_fields .= ",recur_day";
						$str_values .= ",".$i_recur_month;
					}
				}
				else if($str_recurdate_mode == "Y")
				{
					if($i_recur_year_month != "" && $i_recur_year_day != "")
					{
						$str_fields .= ",recur_month,recur_day";
						$str_values .= ",".$i_recur_year_month.",".$i_recur_year_day;
					}
				}
				$str_fields .= ",recur_start_date";
				$str_values .= ",'".$str_recur_start_date."'";

				if($i_recur_charge != "")
				{
					$str_fields .= ",recur_charge";
					$str_values .= ",".$i_recur_charge;
				}
				else
					{
					$str_fields .= ",recur_charge";
					$str_values .= ",".$amount;
				}
				if($i_recur_times != "")
				{
					$str_fields .= ",recur_times";
					$str_values .= ",".$i_recur_times;
				}
			}
		}
		$i_service_user_id="NULL";
		
		$headers = "";
		if ($str_gateway_id == -1) {
			
		} else {
			$gateway_company_name = "";
			$qrt_select_gateway = "Select companyname,email from cs_companydetails where userid='$str_gateway_id'";
			if(!($show_sql_gateway = mysql_query($qrt_select_gateway,$cnn_cs)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query");
				exit();
			}
			else
			{
				$gateway_company_name = mysql_result($show_sql_gateway,0,0);
				$sender = mysql_result($show_sql_gateway,0,1);
			}
			
		}
		
		if($send_mails==1) {
			$ecommerce_letter = func_get_value_of_field($cnn_cs,"cs_registrationmail","mail_sent","mail_id",2);
			if($email !="" && $transaction_type !="tele" && $ecommerce_letter==1 && $send_ecommercemail == 1) {
				$str_email_content = func_getecommerce_mailbody();
				$str_email_content = str_replace("[customername]", $firstname." ".$lastname, $str_email_content );
				$str_email_content = str_replace("[companyname]", $company_name[0], $str_email_content );
				$str_email_content = str_replace("[amount]", $amount, $str_email_content );
				$str_email_content = str_replace("[billingdescriptor]", $billingdescriptor, $str_email_content );
				$str_email_content = str_replace("[ProductDescription]", $productDescription, $str_email_content );
				$str_email_content = str_replace("[companyemailaddress]", $fromaddress, $str_email_content );
				$str_email_content = str_replace("[chargeamount]", $amount, $str_email_content );
				$str_email_content = str_replace("[cardtype]", "Check", $str_email_content );
				$str_email_content = str_replace("[name]", $firstname, $str_email_content );
				$str_email_content = str_replace("[address]", $address, $str_email_content );
				$str_email_content = str_replace("[city]", $city, $str_email_content );
				$str_email_content = str_replace("[state]", $state, $str_email_content );
				$str_email_content = str_replace("[zip]", $zip, $str_email_content );
				$str_email_content = str_replace("[ccnumber]", substr($chequenumber,strlen($chequenumber)-4,4) , $str_email_content);
			//	echo $str_email_content;
				$b_mail = func_send_mail($sender,$email,"Ecommerce Transaction Letter",$str_email_content);
		}
		if($email !="") 
		{
			//mail($email,$subject,$message,$headers);
		}
		
		//func_sendMail($companyid,$subject,$message,$headers);
	}
		}
	}
}					     
}
?>
<body>
<form name="Frmname" action="<?=$i_return_url?>" method="post">
<input type="hidden" name="mt_transaction_result" value="<?=$return_message?>">
<input type="hidden" name="mt_reference_number" value="<?=$ref_number?>">
<input type="hidden" name="mt_total_amount" value="<?=$amount?>">
<input type="hidden" name="mt_product_id" value="<?=$td_product_id?>">
<input type="hidden" name="param_1" value="<?=$param_1?>">
<input type="hidden" name="param_2" value="<?=$param_2?>">
<input type="hidden" name="param_3" value="<?=$param_3?>">
</form>
<script language="JavaScript">
document.Frmname.submit();
</script>

</body>
</html>
<?php
session_destroy();
?>