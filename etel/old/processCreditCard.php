<?php 
session_start();
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'includes/function1.php';
include 'includes/function2.php';
include 'admin/includes/mailbody_replytemplate.php';

$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$td_enable_rebill = 0;
if($mt_subAccount > -1) $td_enable_rebill = 1;
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$companyid = $_SESSION['companyid'];
$site_id = $_SESSION['cs_ID'];
$amount = $_SESSION['amount'];
$td_product_id = $_SESSION['td_product_id'];

$param_1 = $_SESSION['param_1'];
$param_2 = $_SESSION['param_2'];
$param_3 = $_SESSION['param_3'];

$td_recur_next_date = $_SESSION['td_recur_next_date'];
if($amount <=0) die("Invalid Charge Amount");

$ipaddress=$_SESSION['ipaddress'];

$cardTypeScanOrder="";
$insertionSuccess = "";
$cardTypeBr = "";
$transaction_type = "";
$bank_CreditcardId="";

$return_message="";
$send_mails=0; 
if($companyid =="") {
	$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='http://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	print $msgtodisplay;
	exit();	
}

$yearval=date("Y");
$monthval=date("m");
$dateval=date("d");
$hr=date("G");
$mn=date("i");
$tt=date("A");
$trans_id=0;
$dateToEnter = func_get_current_date_time(); //EST Time.
$firstname = (isset($HTTP_POST_VARS['firstname'])?trim($HTTP_POST_VARS['firstname']):"");
$lastname= (isset($HTTP_POST_VARS['lastname'])?trim($HTTP_POST_VARS['lastname']):"");
$td_username= (isset($HTTP_POST_VARS['td_username'])?trim($HTTP_POST_VARS['td_username']):"");
$td_password= (isset($HTTP_POST_VARS['td_password'])?trim($HTTP_POST_VARS['td_password']):"");
$address= (isset($HTTP_POST_VARS['address'])?trim($HTTP_POST_VARS['address']):"");
$city= (isset($HTTP_POST_VARS['city'])?trim($HTTP_POST_VARS['city']):"");
$country= (isset($HTTP_POST_VARS['country'])?trim($HTTP_POST_VARS['country']):"");
$state =  (isset($HTTP_POST_VARS['state'])?trim($HTTP_POST_VARS['state']):"");
$otherstate =  (isset($HTTP_POST_VARS['otherstate'])?trim($HTTP_POST_VARS['otherstate']):"");
$zipcode= (isset($HTTP_POST_VARS['zipcode'])?trim($HTTP_POST_VARS['zipcode']):"");
$phone =(isset($HTTP_POST_VARS['telephone'])?trim($HTTP_POST_VARS['telephone']):"");
$email= (isset($HTTP_POST_VARS['email'])?trim($HTTP_POST_VARS['email']):"");
$number= (isset($HTTP_POST_VARS['number'])?trim($HTTP_POST_VARS['number']):"");
$cvv2= (isset($HTTP_POST_VARS['cvv2'])?trim($HTTP_POST_VARS['cvv2']):"");
$cardtype= (isset($HTTP_POST_VARS['cardtype'])?trim($HTTP_POST_VARS['cardtype']):"");
$mm= (isset($HTTP_POST_VARS['mm'])?trim($HTTP_POST_VARS['mm']):"");
if($mm < 10) $mm = "0".$mm;
$yyyy= (isset($HTTP_POST_VARS['yyyy'])?trim($HTTP_POST_VARS['yyyy']):"");
$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?trim($HTTP_POST_VARS["opt_bill_year"]):date("Y"));
$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?trim($HTTP_POST_VARS["opt_bill_month"]):date("m"));
$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?trim($HTTP_POST_VARS["opt_bill_day"]):date("d"));
$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?trim($HTTP_POST_VARS['authorizationno']):"");			
$shipping= (isset($HTTP_POST_VARS['shippingno'])?trim($HTTP_POST_VARS['shippingno']):"");
$socialno = (isset($HTTP_POST_VARS['securityno'])?trim($HTTP_POST_VARS['securityno']):"");
$licensestate = (isset($HTTP_POST_VARS['licensestate'])?trim($HTTP_POST_VARS['licensestate']):"");
$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?trim($HTTP_POST_VARS['driverlicense']):"");
$misc= (isset($HTTP_POST_VARS['misc'])?trim($HTTP_POST_VARS['misc']):"");
$domain1= (isset($HTTP_POST_VARS['domain1'])?trim($HTTP_POST_VARS['domain1']):"");
$str_3DS = (isset($HTTP_POST_VARS['securepin'])?Trim($HTTP_POST_VARS['securepin']):"");
$productdescription=(isset($HTTP_POST_VARS['productdescription'])?Trim($HTTP_POST_VARS['productdescription']):"");
$socialno=(isset($HTTP_POST_VARS['securityno'])?Trim($HTTP_POST_VARS['securityno']):"");
$dateOfBirth="";
//Here recurring details are obtained.
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

$from_url = $_SESSION['from_url'];


if($td_username){
	// Check to see if user and pass exist in system
	$sql="SELECT * FROM `cs_transactiondetails` WHERE `td_site_ID` = '$site_id' AND `td_username` = '$td_username' AND `td_password` = '$td_password' ";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<br>Cannot execute query");
	$num = mysql_num_rows($result);
	if($num>=1) 
	{
		$msgdisplay="<font face='verdana' size='2' color='black'>Username and Password exist. Please choose another Username and Password.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='http://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay; 
		die();
	}
}

$i_service_user_id = "null";
//end
if($productdescription==""){
	$productdescription="Service";
	}
if ($str_currency == "EURO") {
	$str_currency = "EUR";
}

$validupto="$yyyy/$mm";
if($voiceauth !="") {
	$auth_status = func_isauthorisationno_exists($voiceauth,$phone,$companyid,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$phone,$companyid,$cnn_cs);
	}
	if ($auth_status != "")
	{
		$msgdisplay="<font face='verdana' size='2' color='black'>Voice authorization id already exist.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='http://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		$return_message = "VID";
		exit();	
	}
}
	$qrt_select_suspend = "Select suspenduser from cs_companydetails where userid='$companyid'";

	if(!($show_suspend_Sql= mysql_query($qrt_select_suspend)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		$return_message = "INT";
		exit();
	}
	else
	{
		$suspend =  mysql_fetch_array($show_suspend_Sql);
		if($suspend[0]=="YES") 
		{
			$msgdisplay="<font face='verdana' size='2' color='black'>Your transaction have been suspended by the administrator.</font>";			
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='http://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			print $msgtodisplay;
			$return_message = "SUP";
			exit();	
		}
	}
	if($state=="- - -Select- - -") 
	{
		$state="";
	}
	if($state=="") { $state = $otherstate;}
	if($licensestate=="- - -Select- - -") 
	{
		$licensestate=null;
	}

	$qrt_select_company = "Select companyname,transaction_type,cc_billingdescriptor,email,send_mail,send_ecommercemail,bank_Creditcard,bank_shopId,bank_Username,bank_Password,sdateofbirth from cs_companydetails where userid='$companyid'";
	if(!($show_sql_run = mysql_query($qrt_select_company)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query");
		$return_message = "INT";
		exit();
	}
	else
	{
		$company_name = mysql_fetch_array($show_sql_run);
		$transaction_type = mysql_result($show_sql_run,0,1);
		$billingdescriptor = mysql_result($show_sql_run,0,2);
		$fromaddress = mysql_result($show_sql_run,0,3);
		$send_mails = mysql_result($show_sql_run,0,4);
		$send_ecommercemail = mysql_result($show_sql_run,0,5);
		$bank_CreditcardId = mysql_result($show_sql_run,0,6);
		$bank_shopId 	= mysql_result($show_sql_run,0,7);
		$bank_Username 	= mysql_result($show_sql_run,0,8);
		$bank_Password 	= mysql_result($show_sql_run,0,9);
		$dateOfBirth 	=  mysql_result($show_sql_run,0,10);
		
			
			if($dateOfBirth ==""){$dateOfBirth="100679";}
			else 
			{
			$dob=explode ("-",$dateOfBirth);
			$year=$dob[0];
			$mon=$dob[1];
			$dat=$dob[2];
			$dateOfBirth = "$mon"."$dat".substr($year,2,3);
			}

		//obtaining rebilling details
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
					}else
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
		if(mysql_num_rows($show_sql_run)== 0) 
		{
		$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='http://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		$return_message = "UIN";
		exit();	
		} 
		else 
		{
		
		$transInfo = "";
		$transInfo['transactionId']='';
		$transInfo['Invoiceid']='';
		$transInfo['transactionDate']=$dateToEnter;
		$transInfo['name']=$firstname;
		$transInfo['surname']=$lastname;
		$transInfo['phonenumber']=$phone;
		$transInfo['address']=$address;
		$transInfo['CCnumber']=$number;
		$transInfo['cvv']=$cvv2;
		$transInfo['checkorcard']="H";
		$transInfo['country']=$country;
		$transInfo['city']=$city;
		$transInfo['state']=$state;
		$transInfo['zipcode']=$zipcode;
		$transInfo['amount']=$amount;
		$transInfo['memodet']='';
		$transInfo['signature']='';
		$transInfo['bankname']='';
		$transInfo['bankroutingcode']='';
		$transInfo['bankaccountnumber']='';
		$transInfo['accounttype']='';
		$transInfo['misc']=$misc;
		$transInfo['email']=$email;
		$transInfo['cancelstatus']='N';
		$transInfo['status']='';
		$transInfo['userId']=$companyid;;
		$transInfo['Checkto']='';
		$transInfo['cardtype']=$cardtype;
		$transInfo['checktype']='';
		$transInfo['validupto']=$validupto;
		$transInfo['reason']='';
		$transInfo['other']='';
		$transInfo['ipaddress']=$ipaddress;
		$transInfo['cancellationDate']='';
		$transInfo['voiceAuthorizationno']='-1';
		$transInfo['shippingTrackingno']=$shipping;
		$transInfo['socialSecurity']=$socialno;
		$transInfo['driversLicense']=$licenceno;
		$transInfo['billingDate']='';
		$transInfo['passStatus']='PA';
		$transInfo['chequedate']='';
		$transInfo['pass_count']='0';
		$transInfo['approvaldate']='';
		$transInfo['nopasscomments']='';
		$transInfo['licensestate']=$licensestate;
		$transInfo['approval_count']='0';
		$transInfo['declinedReason']='';
		$transInfo['service_user_id']='0';
		$transInfo['admin_approval_for_cancellation']='';
		$transInfo['company_usertype']='4';
		$transInfo['company_user_id']=$companyid;;
		$transInfo['callcenter_id']='0';
		$transInfo['productdescription']=$mt_prod_desc;
		$transInfo['reference_number']='';
		$transInfo['currencytype']="USD";
		$transInfo['cancel_refer_num']='0';
		$transInfo['cancel_count']='0';
		$transInfo['return_url']=$i_return_url;
		$transInfo['from_url']=$from_url;
		$transInfo['bank_id']=$bank_CreditcardId;
		$transInfo['td_rebillingID']=$mt_subAccount;
		$transInfo['td_is_a_rebill']='0';
		$transInfo['td_enable_rebill']=$td_enable_rebill;
		$transInfo['td_voided_check']='0';
		$transInfo['td_returned_checks']='0';
		$transInfo['td_site_ID']=$site_id;
		$transInfo['td_is_affiliate']='0';
		$transInfo['td_is_pending_check']='0';
		$transInfo['td_is_chargeback']='0';
		$transInfo['td_recur_processed']='0';
		$transInfo['td_recur_next_date']=$td_recur_next_date;
		$transInfo['td_username']=$td_username;
		$transInfo['td_password']=$td_password;
		$transInfo['td_product_id']=$td_product_id;
		 
		include("includes/integration.php");
		$response = execute_transaction(&$transInfo,$_SESSION['integration_mode']);
		if($response['status']=='A') $return_message = "SUC";
		else 
		{
			$postback = "";
			foreach($HTTP_POST_VARS as $k => $c)
				$postback.= "<input type='hidden' name='$k' value='$c' >";
				
			$msgdisplay="<font face='verdana' size='2' color='black'>Error: ".$response['errormsg'].".</font>";			
			$msgtodisplay="<form name='Frmname' action='integrationCreditCard.php' method='post'><input type='hidden' name='errormsg' value='".$response['errormsg']."' >$postback<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><input name='imageField' type='image' src='http://www.etelegate.com/images/back.jpg' width='43' height='20' border='0'></td></tr></table></form>";
			$return_message = "UIN";
			print $msgtodisplay;
			exit();	
		}
	}	
	//print_r($response); die();
}
//	if(($return_message!="SUC")||($integration_mode != "Live") || (1))	 {
?>
		<body>
		<form name="Frmname" action="<?=$i_return_url?>" method="post">
		<input type="hidden" name="mt_transaction_result" value="<?=$return_message?>">
		<input type="hidden" name="mt_total_amount" value="<?=$transInfo['amount']?>">
		<input type="hidden" name="mt_reference_number" value="<?=$transInfo['reference_number']?>">
		<input type="hidden" name="mt_product_id" value="<?=$td_product_id?>">
		<input type="hidden" name="param_1" value="<?=$param_1?>">
		<input type="hidden" name="param_2" value="<?=$param_2?>">
		<input type="hidden" name="param_3" value="<?=$param_3?>">
		</form>
		<script language="JavaScript">
		//document.Frmname.submit();
		</script>
		</body><?php
	//} 

//session_destroy();
?>

