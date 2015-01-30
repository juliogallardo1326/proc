<?php 

chdir("..");
session_start();
$gateway_db_select=$_SESSION["gw_id"];
include 'includes/dbconnection.php';
require_once('includes/function.php');
include 'includes/function1.php';
include 'includes/function2.php';
include 'admin/includes/mailbody_replytemplate.php';


if($_SESSION['card_declined_reset']==true)
{
	toLog('error','customer', "Customer hits Refresh and is denied in ".basename(__FILE__)." on Line ". __LINE__, $companyid);
	$msgtodisplay=$_SESSION['card_declined_reset_output'];
	print $msgtodisplay;
	exit();
}		
$str_posted_variables = "";
$mt_posted_variables = unserialize($_SESSION['mt_posted_variables']);
if(is_array($mt_posted_variables))
{
	foreach($mt_posted_variables as $key=>$posted_variable)
	{
		$str_posted_variables .= "<input type='hidden' name='$key' value='$posted_variable'>\n";
	}
}
	


$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$rd_subaccount = $_SESSION['rd_subaccount'];
$td_enable_rebill = $_SESSION['td_enable_rebill'];
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$companyid = $_SESSION['companyid'];
$site_id = $_SESSION['cs_ID'];
$amount = $_SESSION['amount'];
$td_product_id = $_SESSION['td_product_id'];
$cc_customer_fee = $_SESSION['cc_customer_fee'];

$param_1 = $_SESSION['param_1'];
$param_2 = $_SESSION['param_2'];
$param_3 = $_SESSION['param_3'];

$td_recur_next_date = $_SESSION['td_recur_next_date'];

$ipaddress=$_SESSION['ipaddress'];
$testmsg = "Test Successful!<br>This was a TEST ORDER. No actual creditcard processing occured.<BR>If you did not expect this message, please contact the administrator.";
if($_SESSION['integration_mode'] == "Live")$testmsg = "Order Successful!";

$cardTypeScanOrder="";
$insertionSuccess = "";
$cardTypeBr = "";
$transaction_type = "";
$bank_CreditcardId="";

$return_message="";
$send_mails=0; 
if($companyid =="") {
	$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
	$return_message = "UIN";
	toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
	print $msgtodisplay;
	exit();	
}

if($amount <=0) die("Invalid Charge Amount");

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
$td_bank_number =(isset($HTTP_POST_VARS['td_bank_number'])?trim($HTTP_POST_VARS['td_bank_number']):"");
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
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
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
			$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
			toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
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
		$msgdisplay + "Can't find company.";
		toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		$return_message = "INT";
		exit();
	}
	else
	{
		$company_name = mysql_result($show_sql_run,0,0);
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
		$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='https://www.etelegate.com/images/back.jpg'></a></td></tr></table>";
		print $msgtodisplay;
		toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		$return_message = "UIN";
		exit();	
		} 
		else 
		{
		
		
		$verify_checksum=md5($_SESSION['cd_secret_key'].$reference_id.$amount.$td_product_id);
		$transInfo = "";
		$transInfo['transactionId']='';
		$transInfo['Invoiceid']='';
		$transInfo['transactionDate']=$dateToEnter;
		$transInfo['name']=$firstname;
		$transInfo['surname']=$lastname;
		$transInfo['phonenumber']=$phone;
		$transInfo['td_bank_number']=$td_bank_number;
		$transInfo['address']=$address;
		$transInfo['CCnumber']=$number;
		$transInfo['cvv']=$cvv2;
		$transInfo['checkorcard']="H";
		$transInfo['country']=$country;
		$transInfo['city']=$city;
		$transInfo['state']=$state;
		$transInfo['otherstate']=$otherstate;
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
		$transInfo['userId']=$companyid;
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
		$transInfo['td_rebillingID']=$rd_subaccount;
		$transInfo['td_is_a_rebill']='0';
		$transInfo['td_enable_rebill']=$td_enable_rebill;
		$transInfo['td_voided_check']='0';
		$transInfo['td_returned_checks']='0';
		$transInfo['td_site_ID']=$site_id;
		$transInfo['payment_schedule']=$_SESSION['payment_schedule'];
		$transInfo['nextDateInfo']=$_SESSION['nextDateInfo'];
		$transInfo['td_one_time_subscription']=$_SESSION['td_one_time_subscription'];
		
		$transInfo['td_is_affiliate']='0';
		$transInfo['td_is_pending_check']='0';
		$transInfo['td_is_chargeback']='0';
		$transInfo['td_recur_processed']='0';
		$transInfo['td_recur_next_date']=$td_recur_next_date;
		$transInfo['td_username']=$td_username;
		$transInfo['td_password']=$td_password;
		$transInfo['td_product_id']=$td_product_id;
		$transInfo['td_customer_fee']=$cc_customer_fee;
		
		include("includes/integration.php");
		$response = execute_transaction(&$transInfo,$_SESSION['integration_mode']);
		if($response['status']=='A') $return_message = "SUC";
		else 
		{
			$_SESSION['card_declined_reset']=true;
			$postback = "";
			foreach($HTTP_POST_VARS as $k => $c)
				$postback.= "<input type='hidden' name='$k' value='$c' >";
				
			$msgdisplay="Error: ".$response['errormsg']."";			
			$msgtodisplay="<form name='Frmname' action='integrationCreditCard.php' method='post'><input type='hidden' name='errormsg' value='".$response['errormsg']."' >$postback<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='black'>$msgdisplay</font><br>Please hit the back button below to ensure that all your fields are correct.</td></tr><tr><td align='center'><input name='imageField' type='image' src='https://www.etelegate.com/images/back.jpg' width='43' height='20' border='0'></td></tr></table></form>";
			$return_message = "UIN";
			toLog('error','customer', "Customer Recieves error in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
			$_SESSION['card_declined_reset_output']=$msgtodisplay;
			print $msgtodisplay;
			exit();	
		}
	}	
	//print_r($response); die();
	
	if($_SESSION['integration_mode'] != "Live")
	{
		$td_product_id = "TEST MODE";
		$transInfo['amount'] = "TEST MODE";
	}
}
//	if(($return_message!="SUC")||($integration_mode != "Live") || (1))	 {
if(!$i_return_url) $i_return_url='https://www.etelegate.com';

?>
		
		
		<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
-->
        </style>
		<body>
		<form name="Frmname" action="<?=$i_return_url?>" method="post">
		  <div align="center">
		  <table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="3"><span class="style1"><?=$testmsg?></span></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"> <strong>Your order has been processed successfully.</strong> <br>
                <?php if($_SESSION['integration_mode'] == "Live") { ?>You will soon recieve an email reciept at <?=$email?><?php } ?> </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">Redirecting to <a href="javascript:Frmname.submit()"><?=$i_return_url?></a> in ... <label id="timer">5</label>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td></td>
              <td><input type="submit" name="Submit" value="Continue"></td>
            </tr>
          </table>
<?=$str_posted_variables?>
		  <input type="hidden" name="mt_transaction_result" value="<?=$return_message?>">
		    <input type="hidden" name="mt_total_amount" value="<?=$transInfo['amount']?>">
		    <input type="hidden" name="mt_reference_number" value="<?=$transInfo['reference_number']?>">
		    <input type="hidden" name="mt_product_id" value="<?=$td_product_id?>">
		    <input type="hidden" name="mt_useremail" value="<?=$email?>">
		    <input type="hidden" name="mt_usercountry" value="<?=$country?>">
		    <input type="hidden" name="mt_usercity" value="<?=$city?>">
		    <input type="hidden" name="mt_userstate" value="<?=$state?>">
		    <input type="hidden" name="mt_userzip" value="<?=$zipcode?>">
		    <input type="hidden" name="mt_userip" value="<?=$ipaddress?>">
		    <?php if ($_SESSION['cs_enable_passmgmt']==1){?>
		    <input type="hidden" name="mt_username" value="<?=$td_username?>">
		    <input type="hidden" name="mt_password" value="<?=$td_password?>">
		    <?php } ?>
		    <?php if ($_SESSION['cd_verify_rand_price']==1){?>
		    <input type="hidden" name="verify_checksum" value="<?=$verify_checksum?>">
		    <?php } ?>
          </div>
		  </form>
		<script language="JavaScript">
		//document.Frmname.submit();
		time = 5;
		document.getElementById('timer').firstChild.nodeValue = time;
		setInterval("if(time>0) time--;document.getElementById('timer').firstChild.nodeValue = time+' seconds'",1000);
		setTimeout("document.Frmname.submit()", 4100);
		</script>
		</body><?php
	//} 
if($_SESSION['integration_mode'] == "Live") toLog('order','customer', "Customer Completes Order ".$transInfo['reference_number']." Successfully.", $transInfo['transactionId']);
@session_destroy();
?>

