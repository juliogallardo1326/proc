<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//rebill_creditcard.php:	The page functions for entering the creditcard details as a rebilling one. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="home";	
include 'includes/topheader.php';
//*************************************************************************************************
		$i_to_day = date("d");
		$i_to_month = date("m");
		$i_to_year = date("Y");
		
$cSelect ="";
$vSelect="";
$mSelect="";
$transid = "";
$fname = "";
$sname ="";
$amt  ="";
$billadd = "";
$country ="";
$state ="";
$city ="";
$zipcd ="";
$cardnum ="";
$cvvnum ="";
$cardtyp ="";
$expdd ="";
$expyy ="";
$misc ="";
$emailadd = "";
$modestatus = "";
$user =0;
$ddSelect ="";
$mmSelect="";
$mmv=0;
$ddv=0;
$type = "";
$tid = 0;
$voiceauth	= "";
$shippingno	= "";
$securityno	= "";
$licenseno 	= "";
$phonenumber = "";
//**************************************************************************************************
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sender ="admin@companysetup.co.uk";
if($sessionlogin!="")
{
		$yearval=date("Y");
		$monthval=date("m");
		$dateval=date("d");
		$hr=date("G");
		$mn=date("i");
		$tt=date("A");
	//	$dateToEnter="$yearval-$monthval-$dateval";
			$dateToEnter= func_get_current_date_time(); //EST Time.
			$firstname 	= (isset($HTTP_POST_VARS['firstname'])?Trim($HTTP_POST_VARS['firstname']):"");
			$lastname	= (isset($HTTP_POST_VARS['lastname'])?Trim($HTTP_POST_VARS['lastname']):"");
			$address	= (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
			$country	= (isset($HTTP_POST_VARS['country'])?Trim($HTTP_POST_VARS['country']):"");
			$state 		= (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
			$city		= (isset($HTTP_POST_VARS['city'])?Trim($HTTP_POST_VARS['city']):"");
			$zipcode	= (isset($HTTP_POST_VARS['zipcode'])?Trim($HTTP_POST_VARS['zipcode']):"");
			$amount 	= (isset($HTTP_POST_VARS['amount'])?Trim($HTTP_POST_VARS['amount']):"");
			$number		= (isset($HTTP_POST_VARS['number'])?Trim($HTTP_POST_VARS['number']):"");
			$yyyy		= (isset($HTTP_POST_VARS['yyyy'])?Trim($HTTP_POST_VARS['yyyy']):"");
			$mm			= (isset($HTTP_POST_VARS['mm'])?Trim($HTTP_POST_VARS['mm']):"");
			$state		= (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
			$cvv2		= (isset($HTTP_POST_VARS['cvv2'])?Trim($HTTP_POST_VARS['cvv2']):"");
			$memo		= (isset($HTTP_POST_VARS['memo'])?Trim($HTTP_POST_VARS['memo']):"");
			$cardtype	= (isset($HTTP_POST_VARS['cardtype'])?Trim($HTTP_POST_VARS['cardtype']):"");
			$misc		= (isset($HTTP_POST_VARS['misc'])?Trim($HTTP_POST_VARS['misc']):"");
			$email		= (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
			$domain1	= (isset($HTTP_POST_VARS['domain1'])?Trim($HTTP_POST_VARS['domain1']):"");
			$shipping	= (isset($HTTP_POST_VARS['shippingno'])?Trim($HTTP_POST_VARS['shippingno']):"");
			$phone 		= (isset($HTTP_POST_VARS['telephone'])?Trim($HTTP_POST_VARS['telephone']):"");
			$voiceauth 	= (isset($HTTP_POST_VARS['authorizationno'])?Trim($HTTP_POST_VARS['authorizationno']):"");
			$socialno 	= (isset($HTTP_POST_VARS['securityno'])?Trim($HTTP_POST_VARS['securityno']):"");
			$licenceno 	= (isset($HTTP_POST_VARS['driverlicense'])?Trim($HTTP_POST_VARS['driverlicense']):"");
			$validupto	= "$yyyy/$mm";
			$type		= (isset($HTTP_POST_VARS['type'])?Trim($HTTP_POST_VARS['type']):"");
			$userId 	= (isset($HTTP_POST_VARS['CompanyName'])?Trim($HTTP_POST_VARS['CompanyName']):"");
			$ddval 		= (isset($HTTP_POST_VARS['ddv'])?Trim($HTTP_POST_VARS['ddv']):"");
			//$mmval 		= (isset($HTTP_POST_VARS['mmv'])?Trim($HTTP_POST_VARS['mmv']):"");
			//$domain 	= GetHostByName($_SERVER["REMOTE_ADDR"]); 
			$id 		= (isset($HTTP_GET_VARS['id'])?Trim($HTTP_GET_VARS['id']):"");
			$mode 		= (isset($HTTP_GET_VARS['mode'])?Trim($HTTP_GET_VARS['mode']):"");
			$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?Trim($HTTP_POST_VARS["opt_bill_year"]):date("Y"));
			$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?Trim($HTTP_POST_VARS["opt_bill_month"]):date("m"));
			$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?Trim($HTTP_POST_VARS["opt_bill_day"]):date("d"));
			$setbilldate= "$i_bill_year-$i_bill_month-$i_bill_day";
			
			$i_rebill_year = (isset($HTTP_POST_VARS["opt_rebill_year"])?Trim($HTTP_POST_VARS["opt_rebill_year"]):date("Y"));
			$i_rebill_month = (isset($HTTP_POST_VARS["opt_rebill_month"])?Trim($HTTP_POST_VARS["opt_rebill_month"]):date("m"));
			$i_rebill_day = (isset($HTTP_POST_VARS["opt_rebill_day"])?Trim($HTTP_POST_VARS["opt_rebill_day"]):date("d"));
			$setrebilldate= "$i_rebill_year-$i_rebill_month-$i_rebill_day";
	$auth_status = func_isauthorisationno_exists($voiceauth,$cnn_cs);
	if ($auth_status == "")
	{
		$auth_status = func_isauthorisationno_existsinrebill($voiceauth,$cnn_cs);
	}
	if ($auth_status != "")
	{
			$msgtodisplay="Voice authorization id with transaction status $auth_status already exist.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);
			exit();
	}
	if($amount)
	{
		
		if($state=="- - -Select- - -") 
		{
			$state=null;
		}
		
		if ($type =="Edit") 
		{
			$tid = (isset($HTTP_POST_VARS['tid'])?Trim($HTTP_POST_VARS['tid']):"");
			$qrt_update_details = "update cs_rebillingdetails set date_dd=$ddval,date_mm=$mmval,name='$firstname',surname='$lastname',address='$address',country='$country',state='$state',city='$city',zipcode='$zipcode',checkorcard='H',CCnumber='$number',cvv=$cvv2,amount=$amount,cancelstatus='N',status='P',userid=$sessionlogin,cardtype='$cardtype',validupto='$validupto',misc1='$misc1',misc2='$misc2',email='$email',ipaddress='$domain1' where transactionid=$tid";			
			if(!($qrt_show_sql = mysql_query($qrt_update_details)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				$msgtodisplay="The order details have been updated.";
			}
		} 
		else 
		{
			$qrt_insert_details = "insert into  cs_rebillingdetails (name,surname,address,country,state,city,zipcode,checkorcard,CCnumber,cvv,amount,cancelstatus,status,userid,cardtype,validupto,misc,email,ipaddress,phonenumber,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,transactionDate) values('$firstname','$lastname','$address','$country','$state','$city','$zipcode','H','$number',$cvv2,$amount,'N','P',$sessionlogin,'$cardtype','$validupto','$misc','$email','$domain1','$phone','$voiceauth','$shipping','$socialno','$licenceno','$setbilldate','$setrebilldate')";
			if(!($qrt_show_sql =mysql_query($qrt_insert_details)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				$transid = mysql_insert_id(); 
				$msgtodisplay="Thank you for your order";
			}
		}	
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
				     
	}

	if ($mode =="View") 
	{
		$modestatus = "disabled";
	} 
	else 
	{
		$modestatus = "";
	}
	
	if ($id !="") 
	{
		$qrt_select_details = "Select transactionid,name,surname,amount,address,country,state,city,zipcode,CCnumber,cvv,cardtype,validupto,misc,email,userId,date_dd,date_mm,voiceAuthorizationno,shippingTrackingno,socialSecurity ,driversLicense,phonenumber from cs_rebillingdetails where transactionid=$id";
		if(!($qrt_view_details = mysql_query($qrt_select_details)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else
		{
			while($qrt_show_val = mysql_fetch_row($qrt_view_details)) 
			{
					$transid 	= $qrt_show_val[0];
					$fname 		= $qrt_show_val[1];
					$sname 		= $qrt_show_val[2];
					$amt  		= $qrt_show_val[3];
					$billadd 	= $qrt_show_val[4];
					$country 	= $qrt_show_val[5];
					$state 		= $qrt_show_val[6];
					$city 		= $qrt_show_val[7];
					$zipcd 		= $qrt_show_val[8];
					$cardnum 	= $qrt_show_val[9];
					$cvvnum 	= $qrt_show_val[10];
					$cardtyp 	= $qrt_show_val[11];
					$expdd 		= $qrt_show_val[12];
					$misc 		= $qrt_show_val[13];
					$emailadd 	= $qrt_show_val[14];
					$user 		= $qrt_show_val[15];
					$ddv 		= $qrt_show_val[16];
					$mmv 		= $qrt_show_val[17];
					$voiceauth 	= $qrt_show_val[18];
					$shippingno = $qrt_show_val[19];
					$securityno = $qrt_show_val[20];
					$licenseno 	= $qrt_show_val[21];
					$phonenumber= $qrt_show_val[22];
					
			}
		}
	}
}
//***************************************************************************************************
?>