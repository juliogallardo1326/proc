<?php 

chdir("..");
session_start();
include 'includes/dbconnection.php';
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
$amount = $_SESSION['amount'];
$td_recur_next_date = $_SESSION['td_recur_next_date'];
if($amount <=0) die("Invalid Charge Amount");

$cardTypeScanOrder="";
$insertionSuccess = "";
$cardTypeBr = "";
$transaction_type = "";
$bank_CreditcardId="";

$return_message="";
$send_mails=0; 
if($companyid =="") {
	$msgdisplay="<font face='verdana' size='2' color='black'>You are not a valid user.</font>";			
	$msgtodisplay="<table width='350' height='100' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'>$msgdisplay</td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
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
$phone =(isset($HTTP_POST_VARS['phonenumber'])?trim($HTTP_POST_VARS['phonenumber']):"");
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
$ipaddress=$_SESSION['ipaddress'];

$from_url = (isset($HTTP_POST_VARS['from_url'])?trim($HTTP_POST_VARS['from_url']):"");

$transInfo['rd_subaccount']=$_SESSION['mt_etel900_subAccount'];
include("includes/integration.php");
web900_request_integration($transInfo);
?>
		<style type="text/css">
<!--
.fieldname {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #009999;
	text-decoration: underline;
}
.normaltext {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #009999;
	text-decoration: none;
}
.style1 {color: #009999}
.terms {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #009999;
	font-style: italic;
}
-->
        </style>
		<body>
		<table border="0" cellpadding="0" cellspacing="0" width="650" align="center">
          <tr>
      <td width="100%" valign="top" align="left">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="60%"><img alt='' border='0' src='<?=$logopath?>'></td>
                    <td align="right">&nbsp;&nbsp;&nbsp;&nbsp; </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td align="right">&nbsp;</td>
                  <tr>
                    <td class="normaltext" valign="middle" height="10">Special Phone Instructions </td>
                  </tr>
                  <tr> </tr>
                  <tr>
                    <td bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
                    <td align="right" bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
                  </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top">&nbsp;</td>
                    <td align="left" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="85%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
                      <tr>
                        <td colspan="2" align="left" valign="top" class="terms"><ol>
                            <li>You must call from a US landline phone (cellphones will not work)</li>
                            <li>You must be able to place direct long-distance calls</li>
                            <li>Your phone account must allow charges (Check with your phone company if you are not sure) </li>
                            <li>You must call from the phone listed below:</li>
                          </ol>
                            <p align="center" class="normaltext">
                              <?=$phone?>
                          </p></td>
                      </tr>
                      <?php if($trans_type=="tele"){ ?>
                      <?php }else{?>
                      <?php } ?>
                    </table>
                      <table width="100%"  border="0" cellspacing="4" cellpadding="0">
                        <tr>
                          <td colspan="2" align="left" valign="top" class="terms"><div align="center"><a href="<?=$i_return_url?>">Return to '
                          <?=$i_return_url?>
                          '</a> </div></td>
                        </tr>
                        <?php if($trans_type=="tele"){ ?>
                        <?php }else{?>
                        <?php } ?>
                      </table></td>
                    <td width="15%" align="left" valign="top"><p>&nbsp;</p>
                    </td>
                  </tr>
                </table>
				</td>
          </tr>
        </table>
		<p>&nbsp;</p>


