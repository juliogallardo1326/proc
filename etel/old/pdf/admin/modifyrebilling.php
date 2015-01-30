<?php
include '../includes/dbconnection.php';
$rebill_strt_date="";
$recur_day="";
$state="";
$recur_month="";
 $userid = (isset($HTTP_POST_VARS["userid"])?quote_smart($HTTP_POST_VARS["userid"]):"");
    $transid= (isset($HTTP_POST_VARS["transid"])?quote_smart($HTTP_POST_VARS["transid"]):"");
	$checkorcard=(isset($HTTP_POST_VARS["checkorcard"])?quote_smart($HTTP_POST_VARS["checkorcard"]):"");
	$email=(isset($HTTP_POST_VARS["email22"])?quote_smart($HTTP_POST_VARS["email22"]):"");
	$zip=(isset($HTTP_POST_VARS["zip2"])?quote_smart($HTTP_POST_VARS["zip2"]):"");
	$phonenumber=(isset($HTTP_POST_VARS["phonenumber2"])?quote_smart($HTTP_POST_VARS["phonenumber2"]):"");
	$ostate=(isset($HTTP_POST_VARS["ostate"])?quote_smart($HTTP_POST_VARS["ostate"]):"");
	$state=(isset($HTTP_POST_VARS["state"])?quote_smart($HTTP_POST_VARS["state"]):"");

	$country=(isset($HTTP_POST_VARS["country"])?quote_smart($HTTP_POST_VARS["country"]):"");
	$city=(isset($HTTP_POST_VARS["city2"])?quote_smart($HTTP_POST_VARS["city2"]):"");
	$firstname=(isset($HTTP_POST_VARS["firstname12"])?quote_smart($HTTP_POST_VARS["firstname12"]):"");
	$lastname=(isset($HTTP_POST_VARS["lastname12"])?quote_smart($HTTP_POST_VARS["lastname12"]):"");
	$number=(isset($HTTP_POST_VARS["number"])?quote_smart($HTTP_POST_VARS["number"]):"");
	$cvv=(isset($HTTP_POST_VARS["cvv2"])?quote_smart($HTTP_POST_VARS["cvv2"]):"");
	$cardtype=(isset($HTTP_POST_VARS["cardtype"])?quote_smart($HTTP_POST_VARS["cardtype"]):"");
	$opt_exp_month=(isset($HTTP_POST_VARS["opt_exp_month"])?quote_smart($HTTP_POST_VARS["opt_exp_month"]):"");
	$opt_exp_year=(isset($HTTP_POST_VARS["opt_exp_year"])?quote_smart($HTTP_POST_VARS["opt_exp_year"]):"");
	$amount=(isset($HTTP_POST_VARS["amount2"])?quote_smart($HTTP_POST_VARS["amount2"]):"");
	$billmm=(isset($HTTP_POST_VARS["select1"])?quote_smart($HTTP_POST_VARS["select1"]):"");
	$billdd=(isset($HTTP_POST_VARS["select2"])?quote_smart($HTTP_POST_VARS["select2"]):"");
	$billyyyy=(isset($HTTP_POST_VARS["select3"])?quote_smart($HTTP_POST_VARS["select3"]):"");
	$recur_mode=(isset($HTTP_POST_VARS["recurdatemode"])?quote_smart($HTTP_POST_VARS["recurdatemode"]):"");
	$recur_day=(isset($HTTP_POST_VARS["recur_day2"])?quote_smart($HTTP_POST_VARS["recur_day2"]):"");
	$recur_week=(isset($HTTP_POST_VARS["selectWeekDay"])?quote_smart($HTTP_POST_VARS["selectWeekDay"]):"");
	$recur_month=(isset($HTTP_POST_VARS["selectym"])?quote_smart($HTTP_POST_VARS["selectym"]):"");
	$selyear_day=(isset($HTTP_POST_VARS["selyear_day"])?quote_smart($HTTP_POST_VARS["selyear_day"]):"");
	$selectm=(isset($HTTP_POST_VARS["selMonth"])?quote_smart($HTTP_POST_VARS["selMonth"]):"");
	$recur_charge=(isset($HTTP_POST_VARS["recur_charge2"])?quote_smart($HTTP_POST_VARS["recur_charge2"]):"");
	$recur_times=(isset($HTTP_POST_VARS["recur_times2"])?quote_smart($HTTP_POST_VARS["recur_times2"]):"");
	$select_re_mon=(isset($HTTP_POST_VARS["select_re_mon"])?quote_smart($HTTP_POST_VARS["select_re_mon"]):"");
	$select_re_day=(isset($HTTP_POST_VARS["select_re_day"])?quote_smart($HTTP_POST_VARS["select_re_day"]):"");
	$select_re_year=(isset($HTTP_POST_VARS["select_re_year"])?quote_smart($HTTP_POST_VARS["select_re_year"]):"");
	$address=(isset($HTTP_POST_VARS["address2"])?quote_smart($HTTP_POST_VARS["address2"]):"");
	$checknumber=(isset($HTTP_POST_VARS["checknumber"])?quote_smart($HTTP_POST_VARS["checknumber"]):"");
	$chk_amount=(isset($HTTP_POST_VARS["amount"])?quote_smart($HTTP_POST_VARS["amount"]):"");
	
	$chequetype=(isset($HTTP_POST_VARS["chequetype"])?quote_smart($HTTP_POST_VARS["chequetype"]):"");
	$accounttype=(isset($HTTP_POST_VARS["accounttype"])?quote_smart($HTTP_POST_VARS["accounttype"]):"");
	$opt_bill_month=(isset($HTTP_POST_VARS["opt_bill_month"])?quote_smart($HTTP_POST_VARS["opt_bill_month"]):"");
	$opt_bill_day=(isset($HTTP_POST_VARS["opt_bill_day"])?quote_smart($HTTP_POST_VARS["opt_bill_day"]):"");
	$opt_bill_year=(isset($HTTP_POST_VARS["opt_bill_year"])?quote_smart($HTTP_POST_VARS["opt_bill_year"]):"");
	$bankname=(isset($HTTP_POST_VARS["bankname"])?quote_smart($HTTP_POST_VARS["bankname"]):"");
	$bankroutingcode=(isset($HTTP_POST_VARS["bankroutingcode"])?quote_smart($HTTP_POST_VARS["bankroutingcode"]):"");
	$bankaccountno=(isset($HTTP_POST_VARS["bankaccountno"])?quote_smart($HTTP_POST_VARS["bankaccountno"]):"");
	$authorizationno=(isset($HTTP_POST_VARS["authorizationno"])?quote_smart($HTTP_POST_VARS["authorizationno"]):"");
	$shippingno=(isset($HTTP_POST_VARS["shippingno"])?quote_smart($HTTP_POST_VARS["bankaccountno"]):"");
	$securityno=(isset($HTTP_POST_VARS["securityno"])?quote_smart($HTTP_POST_VARS["securityno"]):"");
	$licensestate=(isset($HTTP_POST_VARS["licensestate"])?quote_smart($HTTP_POST_VARS["licensestate"]):"");
	$driverlicense=(isset($HTTP_POST_VARS["driverlicense"])?quote_smart($HTTP_POST_VARS["driverlicense"]):"");
	$misc=(isset($HTTP_POST_VARS["misc"])?quote_smart($HTTP_POST_VARS["misc"]):"");
	$recur_week2=$recur_week=(isset($HTTP_POST_VARS["recur_week2"])?quote_smart($HTTP_POST_VARS["recur_week2"]):"");
	$txtproductDescription2=(isset($HTTP_POST_VARS["txtproductDescription2"])?quote_smart($HTTP_POST_VARS["txtproductDescription2"]):"");
	//rebilling date
	$opt_recur_day=(isset($HTTP_POST_VARS["opt_recur_day"])?quote_smart($HTTP_POST_VARS["opt_recur_day"]):"");
	$opt_recur_month=(isset($HTTP_POST_VARS["opt_recur_month"])?quote_smart($HTTP_POST_VARS["opt_recur_month"]):"");
	$opt_recur_year=(isset($HTTP_POST_VARS["opt_recur_year"])?quote_smart($HTTP_POST_VARS["opt_recur_year"]):"");
		
	if ($recur_mode=="D"){$recur_day=$recur_day;}
	elseif ($recur_mode=="M" ){$recur_day=$selectm;}
	elseif ($recur_mode=="Y"){$recur_day=$selyear_day;}
	if(substr($number,0,1)==4){$cardtype="Visa";} else {$cardtype="Master";}
	 //substr($number,0,1);exit();
	
	if($state==""){$state=$ostate;}
	//echo "rec day".$recur_day."        rec mon".$recur_month; 
	//echo  ;2011/03  2004-08-23
	$rebill_strt_date=$select_re_year."-".$select_re_mon."-".$select_re_day;
	$validupto=$opt_exp_year."/".$opt_exp_month;
	//echo $rebill_strt_date;
	// $validupto;
	$billingdate=$billyyyy."-".$billmm."-".$billdd;

	if($checkorcard=="H"){

	$qry_update= "update cs_rebillingdetails  set  name ='$firstname',surname='$lastname',address='$address',country='$country',state='$state',city='$city',zipcode='$zip',CCnumber='".etelEnc($number)."',cvv='$cvv',amount='$amount',cardtype='$cardtype',validupto='$validupto',email='$email',phonenumber='$phonenumber',billingDate='$billingdate',recur_mode='$recur_mode',recur_day='$recur_day',recur_week='$recur_week',recur_month='$recur_month',recur_start_date='$rebill_strt_date',recur_charge='$recur_charge',recur_times='$recur_times',productdescription='$txtproductDescription2' where rebill_transactionid ='$transid'";
	
//	echo $qry_update; 
	if(!($update_sql =mysql_query($qry_update,$cnn_cs)))
		   {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		   }
	}//==h
	
	else {
	
	if($state==""){$state=$ostate;}
	$opt_bill_month=(isset($HTTP_POST_VARS["opt_bill_month"])?quote_smart($HTTP_POST_VARS["opt_bill_month"]):"");
	$opt_bill_day=(isset($HTTP_POST_VARS["opt_bill_day"])?quote_smart($HTTP_POST_VARS["opt_bill_day"]):"");
	$opt_bill_year=(isset($HTTP_POST_VARS["opt_bill_year"])?quote_smart($HTTP_POST_VARS["opt_bill_year"]):"");
	$billingdate=trim($opt_bill_year."-".$opt_bill_month."-".$opt_bill_day);
	$rec_day=(isset($HTTP_POST_VARS["recur_day1"])?quote_smart($HTTP_POST_VARS["recur_day1"]):"");
	$recur_day1=(isset($HTTP_POST_VARS["recur_mday"])?quote_smart($HTTP_POST_VARS["recur_mday"]):"");
	$recur_month=(isset($HTTP_POST_VARS["recur_year_month"])?quote_smart($HTTP_POST_VARS["recur_year_month"]):"");
	$txtproductDescription=(isset($HTTP_POST_VARS["txtproductDescription"])?quote_smart($HTTP_POST_VARS["txtproductDescription"]):"");
	$recur_year_day=(isset($HTTP_POST_VARS["recur_year_day"])?quote_smart($HTTP_POST_VARS["recur_year_day"]):"");
	if ($recur_mode=="D"){$recur_day=$rec_day;}
	elseif ($recur_mode=="M" ){$recur_day=$recur_day1;}
	elseif ($recur_mode=="Y"){$recur_day=$recur_year_day;}

	$rebill_strt_date=trim($opt_recur_year."-".$opt_recur_month."-".$opt_recur_day);
	
	$qry_update_chk="update cs_rebillingdetails  set   name ='$firstname',surname='$lastname',address='$address',country='$country',state='$state',city='$city',zipcode='$zip',CCnumber='".etelEnc($checknumber)."',amount='$chk_amount',email='$email',phonenumber='$phonenumber',billingDate='$billingdate',recur_mode='$recur_mode',recur_day='$recur_day',recur_week='$recur_week',recur_month='$recur_month',recur_start_date='$rebill_strt_date',recur_charge='$recur_charge',recur_times='$recur_times',misc ='$misc',bankname ='$bankname' ,bankroutingcode ='$bankroutingcode',bankaccountnumber ='$bankaccountno' ,accounttype ='$accounttype' ,voiceAuthorizationno ='$authorizationno', shippingTrackingno ='$shippingno',socialSecurity  ='$securityno',driversLicense ='$driverlicense' ,licensestate  ='$licensestate',checktype ='$chequetype', productdescription  ='$txtproductDescription'  where rebill_transactionid ='$transid'";
	//echo $qry_update_chk;
		
	if(!($update_sql =mysql_query($qry_update_chk,$cnn_cs)))
		   {
						dieLog(mysql_errno().": ".mysql_error()."<BR>");

		   }
}
 header ("Location: rebillinglist.php");?>


