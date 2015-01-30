<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//updategatewayreportpage.php:	The admin page functions for displaying the company transactions. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';


include '../includes/function2.php';
require_once( '../includes/function.php');

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$trans_recur_start_date ="";
$set_recurring ="";
$dayval ="";
$weekval ="";
$monthval ="";
$yearval ="";
$datevalue ="";
$weekvalue="";
$monthvalue="";
$yearmonthvalue ="";
$yeardayvalue="";
$qrt_select_suspend = "";
$headerInclude="transactions";
if($sessionAdmin!="")
{  
	$i_from_day = date("d");
	$i_from_month = date("m");
	$i_from_year = date("Y");
	$i_to_day = date("d");
	$i_to_month = date("m");
	$i_to_year = date("Y");
	
	$str_task = (isset($HTTP_POST_VARS["task"])?quote_smart($HTTP_POST_VARS["task"]):"");
	$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
	$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
	$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
	$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
	$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
	$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
	$crorcq1 = (isset($HTTP_POST_VARS["crorcq1"])?quote_smart($HTTP_POST_VARS["crorcq1"]):"");
	$tid = (isset($HTTP_POST_VARS["id"])?quote_smart($HTTP_POST_VARS["id"]):"");
	$userid = (isset($HTTP_POST_VARS["userid"])?quote_smart($HTTP_POST_VARS["userid"]):"");

		$opt_chk_day = date("d");
		$opt_chk_month = date("m");
		$opt_chk_year = date("Y");
		$opt_bill_day = date("d");
		$opt_bill_month = date("m");
		$opt_bill_year = date("Y");
		$trans_id=0;
		$firstname = (isset($HTTP_POST_VARS['firstname1'])?quote_smart($HTTP_POST_VARS['firstname1']):"");
		$lastname= (isset($HTTP_POST_VARS['lastname1'])?quote_smart($HTTP_POST_VARS['lastname1']):"");
		$address= (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");
		$city= (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
		$country= (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
		$state =  (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
		$zip= (isset($HTTP_POST_VARS['zip'])?quote_smart($HTTP_POST_VARS['zip']):"");
		$phonenumber= (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
		$chequenumber= (isset($HTTP_POST_VARS['chequenumber'])?quote_smart($HTTP_POST_VARS['chequenumber']):"");
		$chequetype= (isset($HTTP_POST_VARS['chequetype'])?quote_smart($HTTP_POST_VARS['chequetype']):"");
		$amount = (isset($HTTP_POST_VARS['amount'])?quote_smart($HTTP_POST_VARS['amount']):"");
		$accounttype= (isset($HTTP_POST_VARS['accounttype'])?quote_smart($HTTP_POST_VARS['accounttype']):"");
		$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?quote_smart($HTTP_POST_VARS["opt_bill_year"]):$opt_bill_year);
		$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?quote_smart($HTTP_POST_VARS["opt_bill_month"]):$opt_bill_month);
		$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?quote_smart($HTTP_POST_VARS["opt_bill_day"]):$opt_bill_day);

		$i_bill_month = strlen($i_bill_month) == 1 ? "0".$i_bill_month : $i_bill_month; 
		$i_bill_day = strlen($i_bill_day) == 1 ? "0".$i_bill_day : $i_bill_day; 

		$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
		$str_bill_date = (isset($HTTP_POST_VARS['billDate'])?quote_smart($HTTP_POST_VARS['billDate']):"");

		$bankname = (isset($HTTP_POST_VARS['bankname'])?quote_smart($HTTP_POST_VARS['bankname']):"");
		$bankroutingcode= (isset($HTTP_POST_VARS['bankroutingcode'])?quote_smart($HTTP_POST_VARS['bankroutingcode']):"");
		$bankaccountno= (isset($HTTP_POST_VARS['bankaccountno'])?quote_smart($HTTP_POST_VARS['bankaccountno']):"");
		$voiceauth = (isset($HTTP_POST_VARS['authorizationno'])?quote_smart($HTTP_POST_VARS['authorizationno']):"");
		$shipping= (isset($HTTP_POST_VARS['shippingno'])?quote_smart($HTTP_POST_VARS['shippingno']):"");
		$socialno = (isset($HTTP_POST_VARS['securityno'])?quote_smart($HTTP_POST_VARS['securityno']):"");
		$licenceno = (isset($HTTP_POST_VARS['driverlicense'])?quote_smart($HTTP_POST_VARS['driverlicense']):"");
		$licensestate = (isset($HTTP_POST_VARS['licensestate'])?quote_smart($HTTP_POST_VARS['licensestate']):"");
		$misc= (isset($HTTP_POST_VARS['misc'])?quote_smart($HTTP_POST_VARS['misc']):"");
		$domain1= (isset($HTTP_POST_VARS['domain1'])?quote_smart($HTTP_POST_VARS['domain1']):"");
		$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
		$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
		$dateToEnter = func_get_current_date_time(); //EST Time.
		$str_recur_date = (isset($HTTP_POST_VARS['chk_recur_date'])?quote_smart($HTTP_POST_VARS['chk_recur_date']):"");
		$str_recurdate_mode = (isset($HTTP_POST_VARS['recurdatemode'])?quote_smart($HTTP_POST_VARS['recurdatemode']):"");
		$i_recur_day = (isset($HTTP_POST_VARS['recur_day'])?quote_smart($HTTP_POST_VARS['recur_day']):"");
		$i_recur_week = (isset($HTTP_POST_VARS['recur_week'])?quote_smart($HTTP_POST_VARS['recur_week']):"");
		$i_recur_month = (isset($HTTP_POST_VARS['recur_month'])?quote_smart($HTTP_POST_VARS['recur_month']):"");
		$i_recur_year_day = (isset($HTTP_POST_VARS['recur_year_day'])?quote_smart($HTTP_POST_VARS['recur_year_day']):"");
		$i_recur_year_month = (isset($HTTP_POST_VARS['recur_year_month'])?quote_smart($HTTP_POST_VARS['recur_year_month']):"");
		$i_recur_start_month = (isset($HTTP_POST_VARS['opt_recur_month'])?quote_smart($HTTP_POST_VARS['opt_recur_month']):"");
		$i_recur_start_day = (isset($HTTP_POST_VARS['opt_recur_day'])?quote_smart($HTTP_POST_VARS['opt_recur_day']):"");
		$i_recur_start_year = (isset($HTTP_POST_VARS['opt_recur_year'])?quote_smart($HTTP_POST_VARS['opt_recur_year']):"");
		$str_recur_start_date = "$i_recur_start_year-$i_recur_start_month-$i_recur_start_day";
		$i_recur_charge = (isset($HTTP_POST_VARS['recur_charge'])?quote_smart($HTTP_POST_VARS['recur_charge']):"");
		$i_recur_times = (isset($HTTP_POST_VARS['recur_times'])?quote_smart($HTTP_POST_VARS['recur_times']):"");
		$gatewayAdminId = isset($HTTP_POST_VARS['gatewayCompanies'])?$HTTP_POST_VARS['gatewayCompanies']:"";
		$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
		$companyids = isset($HTTP_POST_VARS['companyids'])?$HTTP_POST_VARS['companyids']:"";
		$productDescription= (isset($HTTP_POST_VARS['txtproductdescription'])?quote_smart($HTTP_POST_VARS['txtproductdescription']):"");
		if($state=="- - -Select- - -") 
			{
				$state=null;
			}
			if($licensestate=="- - -Select- - -") 
			{
				$licensestate=null;
			}

				$str_fields = "";
				$str_values = "";
		
				if($str_recur_date == "Y")
				{
					if($str_recurdate_mode != "")
					{
						$str_fields = ",recur_mode='$str_recurdate_mode'";
						if($str_recurdate_mode == "D")
						{
							if($i_recur_day != "")
							{
								$str_fields .= ",recur_day=$i_recur_day";
							}
						}
						else if($str_recurdate_mode == "W")
						{
							if($i_recur_week != "")
							{
								$str_fields .= ",recur_week=$i_recur_week";
							}
						}
						else if($str_recurdate_mode == "M")
						{
							if($i_recur_month != "")
							{
								$str_fields .= ",recur_day=$i_recur_month";
							}
						}
						else if($str_recurdate_mode == "Y")
						{
							if($i_recur_year_month != "" && $i_recur_year_day != "")
							{
								$str_fields .= ",recur_month=$i_recur_year_month,recur_day=$i_recur_year_day";
							}
						}
						$str_fields .= ",recur_start_date='$str_recur_start_date'";
		
						if($i_recur_charge != "")
						{
							$str_fields .= ",recur_charge=$i_recur_charge";
						}
						if($i_recur_times != "")
						{
							$str_fields .= ",recur_times=$i_recur_times";
						}
					}
				}	
	
	if($crorcq1 =="C") {
		if($i_recur_charge == "")
		{
			$i_recur_charge = $amount;
		}
		if($amount)
		{
			$qrt_update_details= "update cs_transactiondetails set name='$firstname',surname='$lastname',phonenumber='$phonenumber',address='$address',checkorcard='C',CCnumber='$chequenumber',accounttype='$accounttype',country='$country',city='$city',state='$state',zipcode='$zip',checktype='$chequetype',amount=$amount,bankname='$bankname',bankroutingcode='$bankroutingcode',bankaccountnumber='$bankaccountno',misc='$misc',userid=$userid,voiceAuthorizationno='$voiceauth',shippingTrackingno='$shipping',socialSecurity='$socialno',driversLicense='$licenceno',billingDate='$setbilldate',licensestate='$licensestate',productdescription ='$productDescription' where transactionid=$tid";  
			if(!($show_sql =mysql_query($qrt_update_details,$cnn_cs)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
					if($str_fields != "")
					{
						$qrt_update_details ="update cs_rebillingdetails set name='$firstname',surname='$lastname',phonenumber='$phonenumber',address='$address',checkorcard='C',CCnumber='$chequenumber',accounttype='$accounttype',country='$country',city='$city',state='$state',zipcode='$zip',checktype='$chequetype',amount=$amount,bankname='$bankname',bankroutingcode='$bankroutingcode',bankaccountnumber='$bankaccountno',misc='$misc',userid=$userid,voiceAuthorizationno='$voiceauth',shippingTrackingno='$shipping',socialSecurity='$socialno',driversLicense='$licenceno',billingDate='$setbilldate',licensestate='$licensestate',productdescription ='$productDescription' $str_fields where rebill_transactionid=$tid";
			//			print $qrt_update_details."<br>";
						
						if(!($show_sql =mysql_query($qrt_update_details,$cnn_cs)))
						{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

						}
	
					}
	
/*					$transid = mysql_insert_id(); 
					$msgtodisplay="Check with number ".$chequenumber." has been Updated";			
					$outhtml="y";		
					message($msgtodisplay,$outhtml,$headerInclude);				
*/			}
		}
	}
	else 
	{
		
	//	$phone =(isset($HTTP_POST_VARS['telephone'])?quote_smart($HTTP_POST_VARS['telephone']):"");
		$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
		$number= (isset($HTTP_POST_VARS['number'])?quote_smart($HTTP_POST_VARS['number']):"");
		$cvv2= (isset($HTTP_POST_VARS['cvv2'])?quote_smart($HTTP_POST_VARS['cvv2']):"");
		$cardtype= (isset($HTTP_POST_VARS['cardtype'])?quote_smart($HTTP_POST_VARS['cardtype']):"");
		$mm= (isset($HTTP_POST_VARS['opt_exp_month'])?quote_smart($HTTP_POST_VARS['opt_exp_month']):"");
		$yyyy= (isset($HTTP_POST_VARS['opt_exp_year'])?quote_smart($HTTP_POST_VARS['opt_exp_year']):"");
		
		if($i_recur_charge == "")
		{
			$i_recur_charge = $amount;
		}
		$validupto="$yyyy/$mm";

		$qrt_update_details = "update cs_transactiondetails set name='$firstname',surname='$lastname',phonenumber='$phonenumber',email='$email',address='$address',checkorcard='H',CCnumber='$number',accounttype='$accounttype',country='$country',city='$city',state='$state',zipcode='$zip',cvv=$cvv2,amount=$amount,cardtype='$cardtype',validupto='$validupto',misc='$misc',userid=$userid,voiceAuthorizationno='$voiceauth',shippingTrackingno='$shipping',socialSecurity='$socialno',driversLicense='$licenceno',billingDate='$setbilldate',licensestate='$licensestate',productdescription ='$productDescription' where transactionid=$tid";
	//	print $qrt_update_details ;
		if(!($show_insert_run =mysql_query($qrt_update_details)))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print $qrt_update_details ;
			print("Cannot execute query");
			exit();
		}
		else
		{
			if($str_fields != "")
			{  
				$qrt_update_details = "update cs_rebillingdetails set name='$firstname',surname='$lastname',phonenumber='$phonenumber',email='$email',address='$address',checkorcard='H',CCnumber='$number',accounttype='$accounttype',country='$country',city='$city',state='$state',zipcode='$zip',cvv=$cvv2,amount=$amount,cardtype='$cardtype',validupto='$validupto',misc='$misc',userid=$userid,voiceAuthorizationno='$voiceauth',shippingTrackingno='$shipping',socialSecurity='$socialno',driversLicense='$licenceno',billingDate='$setbilldate',licensestate='$licensestate',productdescription ='$productDescription' $str_values where transactionid=$tid"; 
			//	print($qrt_update_details); 
				if(!($show_sql =mysql_query($qrt_update_details,$cnn_cs)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				}

			}

/*			$msgtodisplay="The Credit card transaction has been updated";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
*/		}	
}

$cancel = (isset($HTTP_POST_VARS['cancel'])?quote_smart($HTTP_POST_VARS['cancel']):"");
$cancelreason = (isset($HTTP_POST_VARS['cancelreason'])?quote_smart($HTTP_POST_VARS['cancelreason']):"");
$id = (isset($HTTP_POST_VARS['id'])?quote_smart($HTTP_POST_VARS['id']):"");

$canceldate = func_get_current_date_time(); 
$strCurrentDateTime = func_get_current_date();

	  if($id=="")
	  {
	  $id = $HTTP_GET_VARS['id'];
	  }
		$other = (isset($HTTP_POST_VARS['other'])?quote_smart($HTTP_POST_VARS['other']):"");

	  if($cancelreason !="" || $other !="") 
	  {
			$iTransactionId = $id;
			$return_insertId = $id;
			$str_is_cancelled = func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancelstatus","transactionId",$return_insertId);
			if($str_is_cancelled == "Y") 
			{
				$outhtml="y";
				$msgtodisplay="This transaction has been already canceled";
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
			} 
			else 
			{	
				/*$strCurrentDateTime = func_get_current_date();
				$str_approval_status = func_get_value_of_field($cnn_cs,"cs_transactiondetails","status","transactionId",$return_insertId);
				if($strCurrentDateTime >= $str_bill_date && $str_approval_status == "A") { 
					$qrt_update_details ="Update cs_transactiondetails set reason='$cancelreason',other='$other',cancellationDate='$canceldate',admin_approval_for_cancellation = 'P' where transactionId=$return_insertId";
					if(!($qrt_update_run = mysql_query($qrt_update_details)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

					} 
					else
					{
						$outhtml="y";
						$msgtodisplay="Selected transaction has been canceled and is awaiting Admin's Approval.";
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();
					//}
					 }
				} else {*/
					$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
					
					$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='$cancelreason',other='$other',cancellationDate='$canceldate' where transactionId=$return_insertId";
					//print($iTransactionId."<br>");
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
					else
					{
						$outhtml="y";
						$msgtodisplay="Selected transaction has been canceled.";
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();
					//}
					}
					$user_id = func_get_value_of_field($cnn_cs,"cs_transactiondetails","userId","transactionId",$return_insertId);
					if($crorcq1 == "C")
					{
						func_send_cancel_mail($user_id,$crorcq1);
					}
					func_canceledTransaction_receipt($user_id, $return_insertId,$cnn_cs);
				//}
			}
		}
}
	$crorcq = (isset($HTTP_POST_VARS["crorcq"])?quote_smart($HTTP_POST_VARS["crorcq"]):"");
	$str_type =(isset($HTTP_POST_VARS['type'])?quote_smart($HTTP_POST_VARS['type']):"");
	$str_firstname =(isset($HTTP_POST_VARS['firstname'])?quote_smart($HTTP_POST_VARS['firstname']):"");
	$str_lastname =(isset($HTTP_POST_VARS['lastname'])?quote_smart($HTTP_POST_VARS['lastname']):"");
	$str_telephone =(isset($HTTP_POST_VARS['telephone'])?quote_smart($HTTP_POST_VARS['telephone']):"");
	$trans_pass =(isset($HTTP_POST_VARS['trans_pass'])?quote_smart($HTTP_POST_VARS['trans_pass']):"");
	$trans_nopass =(isset($HTTP_POST_VARS['trans_nopass'])?quote_smart($HTTP_POST_VARS['trans_nopass']):"");
	$hid_companies = (isset($HTTP_POST_VARS["hid_companies"])?quote_smart($HTTP_POST_VARS["hid_companies"]):"");
	$trans_ptype = (isset($HTTP_POST_VARS["trans_ptype"])?quote_smart($HTTP_POST_VARS["trans_ptype"]):"");
	$trans_ctype = (isset($HTTP_POST_VARS["trans_ctype"])?quote_smart($HTTP_POST_VARS["trans_ctype"]):"");
	$trans_atype = (isset($HTTP_POST_VARS['trans_atype'])?quote_smart($HTTP_POST_VARS['trans_atype']):"");
	$trans_dtype = (isset($HTTP_POST_VARS["trans_dtype"])?quote_smart($HTTP_POST_VARS["trans_dtype"]):"");
	$voiceid = (isset($HTTP_POST_VARS["voiceid"])?quote_smart($HTTP_POST_VARS["voiceid"]):"");
	$transactionId = (isset($HTTP_POST_VARS["transactionId"])?quote_smart($HTTP_POST_VARS["transactionId"]):"");
	$cnumber = (isset($HTTP_POST_VARS["cnumber"])?quote_smart($HTTP_POST_VARS["cnumber"]):"");
	$radRange = (isset($HTTP_POST_VARS["radRange"])?quote_smart($HTTP_POST_VARS["radRange"]):"");
	$decline_reason=(isset($HTTP_POST_VARS['decline_reasons'])?($HTTP_POST_VARS['decline_reasons']):"");
	$declineReasons=(isset($HTTP_POST_VARS['decline_reasons1'])?($HTTP_POST_VARS['decline_reasons1']):"");
	$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
	$cancelReasons=(isset($HTTP_POST_VARS['cancel_reasons1'])?($HTTP_POST_VARS['cancel_reasons1']):"");
	$iCount = (isset($HTTP_POST_VARS["hdCount"])?quote_smart($HTTP_POST_VARS["hdCount"]):"");
	$i_lower_limit = (isset($HTTP_POST_VARS["lower_limit"])?quote_smart($HTTP_POST_VARS["lower_limit"]):"0");
	$i_num_records_per_page = (isset($HTTP_POST_VARS["cbo_num_records"])?quote_smart($HTTP_POST_VARS["cbo_num_records"]):"20");

?>
<html>
<body onLoad="document.dates1.submit();">
		<form name="dates1" action="viewGatewayTransactions.php"  method="POST">
			<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
			<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
			<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
			<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
			<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
			<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
			<input type="hidden" name="crorcq" value="<?= $crorcq?>">
			<input type="hidden" name="type" value="<?= $str_type?>">
			<input type="hidden" name="firstname" value="<?= $str_firstname?>">
			<input type="hidden" name="lastname" value="<?= $str_lastname?>">
			<input type="hidden" name="telephone" value="<?= $str_telephone?>">
			<input type="hidden" name="trans_pass" value="<?= $trans_pass?>">
			<input type="hidden" name="trans_nopass" value="<?= $trans_nopass?>">
			<input type="hidden" name="hid_companies" value="<?= $hid_companies ?>">
			<input type="hidden" name="trans_ptype" value="<?=$trans_ptype ?>">
			<input type="hidden" name="trans_ctype" value="<?=$trans_ctype ?>">
			<input type="hidden" name="trans_atype" value="<?=$trans_atype ?>">
			<input type="hidden" name="trans_dtype" value="<?= $trans_dtype?>">
			<input type="hidden" name="voiceid" value="<?= $voiceid?>">
			<input type="hidden" name="transactionId" value="<?=$transactionId ?>">
			<input type="hidden" name="radRange" value="<?=$radRange ?>">
			<input type="hidden" name="cnumber" value="<?=$cnumber ?>">
			<input type="hidden" name="decline_reasons" value="<?=$decline_reason ?>">
			<input type="hidden" name="decline_reasons1" value="<?=$declineReasons ?>">			
			<input type="hidden" name="cancel_reasons" value="<?=$cancel_reason ?>">
			<input type="hidden" name="cancel_reasons1" value="<?=$cancelReasons ?>">			
			<input type="hidden" name="task" value="<?=$str_task ?>">			
			<input type="hidden" name="lower_limit" value="<?=$i_lower_limit ?>">			
			<input type="hidden" name="cbo_num_records" value="<?=$i_num_records_per_page ?>">	
			<input type="hidden" name="companyname" value="<?= $companyid ?>">
			<input type="hidden" name="companyids" value="<?= $companyids ?>">
			<input type="hidden" name="gatewayCompanies" value="<?= $gatewayAdminId ?>">
		</form>
<script language="JavaScript" type="text/JavaScript">
function func_submit()
{
	//document.dates1.submit();
}
</script>
		
</body>
</html> 