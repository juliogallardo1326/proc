<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// modifycompany.php:	The admin page functions for selecting the company for editing company profile. 
include("includes/sessioncheck.php");

$headerInclude = "companies";
include("includes/header.php");

include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");

	$qry_select_user = "select username,companyname from cs_companydetails where ( username='$username' or companyname='$companyname' or email='$email') and userid<>$userid";
	//print($qry_select_user);
	if(!($show_sql =mysql_query($qry_select_user)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	elseif(mysql_num_rows($show_sql) >0) 
	{
		if (mysql_result($show_sql, 0, 0) == $username) {
			$msgtodisplay="user name ".$username." already exists";
		} else {
			$msgtodisplay="company name ".$companyname." already exists";
		}
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	}
	else 
	{
		$suspend = (isset($HTTP_POST_VARS['suspend'])?quote_smart($HTTP_POST_VARS['suspend']):"NO");

		$first_name = (isset($HTTP_POST_VARS['first_name'])?quote_smart($HTTP_POST_VARS['first_name']):"");
		$family_name = (isset($HTTP_POST_VARS['family_name'])?quote_smart($HTTP_POST_VARS['family_name']):"");
		$job_title = (isset($HTTP_POST_VARS['job_title'])?quote_smart($HTTP_POST_VARS['job_title']):"");
		$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
		$confirm_contact_email = (isset($HTTP_POST_VARS['confirm_contact_email'])?quote_smart($HTTP_POST_VARS['confirm_contact_email']):"");
		$contact_phone = (isset($HTTP_POST_VARS['contact_phone'])?quote_smart($HTTP_POST_VARS['contact_phone']):"");
		$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
		$reseller_other = (isset($HTTP_POST_VARS['reseller_other'])?quote_smart($HTTP_POST_VARS['reseller_other']):"");

		$sTitle 				= (isset($HTTP_POST_VARS['cboTitle'])?quote_smart($HTTP_POST_VARS['cboTitle']):"");
		$sYear 					= (isset($HTTP_POST_VARS['cboYear'])?quote_smart($HTTP_POST_VARS['cboYear']):"");
		$sMonth					= (isset($HTTP_POST_VARS['cboMonth'])?quote_smart($HTTP_POST_VARS['cboMonth']):"");
		$sDay					= (isset($HTTP_POST_VARS['cboDay'])?quote_smart($HTTP_POST_VARS['cboDay']):"");	
		$sDateOfBirth			= ($sYear."-".$sMonth."-".$sDay);
		$sSex					= (isset($HTTP_POST_VARS['cboSex'])?quote_smart($HTTP_POST_VARS['cboSex']):"");
		$sAddress				= (isset($HTTP_POST_VARS['txtAddress'])?quote_smart($HTTP_POST_VARS['txtAddress']):"");
		$sPostCode				= (isset($HTTP_POST_VARS['txtPostCode'])?quote_smart($HTTP_POST_VARS['txtPostCode']):"");
		$sResidenceTelephone	= (isset($HTTP_POST_VARS['residence_telephone'])?quote_smart($HTTP_POST_VARS['residence_telephone']):"");
		$sFax					= (isset($HTTP_POST_VARS['fax'])?quote_smart($HTTP_POST_VARS['fax']):"");
		$setupFees				= (isset($HTTP_POST_VARS['txtSetupFee'])?quote_smart($HTTP_POST_VARS['txtSetupFee']):"");		
		if ($setupFees=="") 
			$setupFees=0;
		
		$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
		$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
		$faxnumber = (isset($HTTP_POST_VARS['faxnumber'])?quote_smart($HTTP_POST_VARS['faxnumber']):"");
		$address = (isset($HTTP_POST_VARS['address'])?quote_smart($HTTP_POST_VARS['address']):"");;
		$city = (isset($HTTP_POST_VARS['city'])?quote_smart($HTTP_POST_VARS['city']):"");
		$state = (isset($HTTP_POST_VARS['state'])?quote_smart($HTTP_POST_VARS['state']):"");
		$ostate = (isset($HTTP_POST_VARS['ostate'])?quote_smart($HTTP_POST_VARS['ostate']):"");
		$country = (isset($HTTP_POST_VARS['country'])?quote_smart($HTTP_POST_VARS['country']):"");
		$zipcode = (isset($HTTP_POST_VARS['zipcode'])?quote_smart($HTTP_POST_VARS['zipcode']):"");

		$url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
		$url2 = (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
		$url3 = (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
		
		$strMerchantName = (isset($HTTP_POST_VARS['txtMerchantName'])?quote_smart($HTTP_POST_VARS['txtMerchantName']):"");
		$strTollFreeNumber = (isset($HTTP_POST_VARS['txtTollFreeNumber'])?quote_smart($HTTP_POST_VARS['txtTollFreeNumber']):"");
		$strRetrievalNumber = (isset($HTTP_POST_VARS['txtRetrievalNumber'])?quote_smart($HTTP_POST_VARS['txtRetrievalNumber']):"");
		$strSecurityNumber = (isset($HTTP_POST_VARS['txtSecurityNumber'])?quote_smart($HTTP_POST_VARS['txtSecurityNumber']):"");
		$strProcessor = (isset($HTTP_POST_VARS['txtProcessor'])?quote_smart($HTTP_POST_VARS['txtProcessor']):"");
		$strtxtBillingdescriptor = (isset($HTTP_POST_VARS['txtBillingdescriptor'])?quote_smart($HTTP_POST_VARS['txtBillingdescriptor']):"");
		
		$strChargeBack = (isset($HTTP_POST_VARS['txtChargeBack'])?quote_smart($HTTP_POST_VARS['txtChargeBack']):"0");
		$strCredit =   (isset($HTTP_POST_VARS['txtCredit'])?quote_smart($HTTP_POST_VARS['txtCredit']):"0");
		$strDiscountRate  = (isset($HTTP_POST_VARS['txtDiscountRate'])?quote_smart($HTTP_POST_VARS['txtDiscountRate']):"0");
		$strTransactionFee  = (isset($HTTP_POST_VARS['txtTransactionFee'])?quote_smart($HTTP_POST_VARS['txtTransactionFee']):"0");
		$strVoiceauthFee  = (isset($HTTP_POST_VARS['txtVoicefee'])?quote_smart($HTTP_POST_VARS['txtVoicefee']):"0");
		$strReserve  = (isset($HTTP_POST_VARS['txtReserve'])?quote_smart($HTTP_POST_VARS['txtReserve']):"");
		$trans_activity = (isset($HTTP_POST_VARS['rad_trans_activity'])?quote_smart($HTTP_POST_VARS['rad_trans_activity']):"0");
		$transaction_type = (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");
		$strAutoCancel = (isset($HTTP_POST_VARS['chk_auto_cancel'])?quote_smart($HTTP_POST_VARS['chk_auto_cancel']):"N");
		$iTimeFrame  = (isset($HTTP_POST_VARS['time_frame'])?quote_smart($HTTP_POST_VARS['time_frame']):"-1");
		$strShippingCancel  = (isset($HTTP_POST_VARS['chk_shipping_cancel'])?quote_smart($HTTP_POST_VARS['chk_shipping_cancel']):"N");
		$iShippingTimeFrame  = (isset($HTTP_POST_VARS['shipping_time_frame'])?quote_smart($HTTP_POST_VARS['shipping_time_frame']):"-1");
		$strAutoApprove  = (isset($HTTP_POST_VARS['chk_auto_approve'])?quote_smart($HTTP_POST_VARS['chk_auto_approve']):"N");
		$strUnsubscribe  = (isset($HTTP_POST_VARS['chk_unsubscribe'])?quote_smart($HTTP_POST_VARS['chk_unsubscribe']):"1");
			
		if($trans_activity =="") 
			$trans_activity =0;
	
		if($strVoiceauthFee =="")
			$strVoiceauthFee =0;

		if($iShippingTimeFrame == "")
			$iShippingTimeFrame = "-1";

		if($iTimeFrame == "")
			$iTimeFrame = "-1";

		$company_type = (isset($HTTP_POST_VARS['company_type'])?quote_smart($HTTP_POST_VARS['company_type']):"");
		$other_company_type = (isset($HTTP_POST_VARS['other_company_type'])?quote_smart($HTTP_POST_VARS['other_company_type']):"");
		$customerservice_phone = (isset($HTTP_POST_VARS['customerservice_phone'])?quote_smart($HTTP_POST_VARS['customerservice_phone']):"");

		$volume= (isset($HTTP_POST_VARS['volume'])?quote_smart($HTTP_POST_VARS['volume']):"0");
		$avgticket= (isset($HTTP_POST_VARS['avgticket'])?quote_smart($HTTP_POST_VARS['avgticket']):"0");
		$chargeper= (isset($HTTP_POST_VARS['chargeper'])?quote_smart($HTTP_POST_VARS['chargeper']):"0");
		$rad_order_type= (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");
		$prepro= (isset($HTTP_POST_VARS['prepro'])?quote_smart($HTTP_POST_VARS['prepro']):"");
		$rebill= (isset($HTTP_POST_VARS['rebill'])?quote_smart($HTTP_POST_VARS['rebill']):"");
		$currpro= (isset($HTTP_POST_VARS['currpro'])?quote_smart($HTTP_POST_VARS['currpro']):"");

		if($volume=="") 
			$volume=0;
		if($avgticket=="")
			$avgticket=0;
		if($chargeper=="")
			$chargeper=0;

		$txtPackagename = (isset($HTTP_POST_VARS['txtPackagename'])?quote_smart($HTTP_POST_VARS['txtPackagename']):"");
		$txtPackageProduct= (isset($HTTP_POST_VARS['txtPackageProduct'])?quote_smart($HTTP_POST_VARS['txtPackageProduct']):"");
		$txtPackagePrice= (isset($HTTP_POST_VARS['txtPackagePrice'])?quote_smart($HTTP_POST_VARS['txtPackagePrice']):"0");
		$txtRefundPolicy= (isset($HTTP_POST_VARS['txtRefundPolicy'])?quote_smart($HTTP_POST_VARS['txtRefundPolicy']):"");
		$txtDescription= (isset($HTTP_POST_VARS['txtDescription'])?quote_smart($HTTP_POST_VARS['txtDescription']):"");

		if($txtPackagePrice=="") 
			$txtPackagePrice=0;

		$currentBank = (isset($HTTP_POST_VARS['currentBank'])?quote_smart($HTTP_POST_VARS['currentBank']):"");
		$bank_other = (isset($HTTP_POST_VARS['bank_other'])?quote_smart($HTTP_POST_VARS['bank_other']):"");
		$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?quote_smart($HTTP_POST_VARS['beneficiary_name']):"");
		$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?quote_smart($HTTP_POST_VARS['bank_account_name']):"");
		$bank_address = (isset($HTTP_POST_VARS['bank_address'])?quote_smart($HTTP_POST_VARS['bank_address']):"");
		$bank_country = (isset($HTTP_POST_VARS['bank_country'])?quote_smart($HTTP_POST_VARS['bank_country']):"");
		$bank_phone = (isset($HTTP_POST_VARS['bank_phone'])?quote_smart($HTTP_POST_VARS['bank_phone']):"");
		$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?quote_smart($HTTP_POST_VARS['bank_sort_code']):"");
		$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?quote_smart($HTTP_POST_VARS['bank_account_number']):"");
		$bank_swift_code = (isset($HTTP_POST_VARS['bank_swift_code'])?quote_smart($HTTP_POST_VARS['bank_swift_code']):"");
		$send_ecommercemail = (isset($HTTP_POST_VARS['chk_sendecommerce'])?quote_smart($HTTP_POST_VARS['chk_sendecommerce']):0);

		$merchant_discount = (isset($HTTP_POST_VARS['merchant_discount'])?quote_smart($HTTP_POST_VARS['merchant_discount']):"0");
		$reseller_discount = (isset($HTTP_POST_VARS['reseller_discount'])?quote_smart($HTTP_POST_VARS['reseller_discount']):"0");
		$total_discount = (isset($HTTP_POST_VARS['total_discount'])?quote_smart($HTTP_POST_VARS['total_discount']):"0");
		$merchant_transfee = (isset($HTTP_POST_VARS['merchant_transfee'])?quote_smart($HTTP_POST_VARS['merchant_transfee']):"0");
		$reseller_transfee = (isset($HTTP_POST_VARS['reseller_transfee'])?quote_smart($HTTP_POST_VARS['reseller_transfee']):"0");
		$total_transfee = (isset($HTTP_POST_VARS['total_transfee'])?quote_smart($HTTP_POST_VARS['total_transfee']):"0");


		$qry_update_user  = " update cs_companydetails set first_name = '$first_name', family_name = '$family_name', ";
		$qry_update_user .= " job_title = '$job_title', contact_email = '$contact_email', contact_phone = '$contact_phone', how_about_us = '$how_about_us', ";
		$qry_update_user .= " stitle = '$sTitle',sdateofbirth='$sDateOfBirth',ssex='$sSex',sAddress='$sAddress',sPostCode='$sPostCode',sResidenceTelephone='$sResidenceTelephone',sFax='$sFax', ";		
		
		$qry_update_user .=  "username='$username',password='$password',companyname='$companyname',";
		$qry_update_user .= " phonenumber='$phonenumber',address='$address', city='$city',state='$state',ostate='$ostate',";
		$qry_update_user .= " merchantName='$strMerchantName',tollFreeNumber='$strTollFreeNumber',retrievalNumber='$strRetrievalNumber',";
		$qry_update_user .= " securityNumber='$strSecurityNumber',processor='$strProcessor',";
		$qry_update_user .= " chargeback=$strChargeBack,credit=$strCredit,discountrate=$strDiscountRate,transactionfee=$strTransactionFee,reserve=$strReserve,voiceauthfee=$strVoiceauthFee,auto_cancel='$strAutoCancel',time_frame=$iTimeFrame,auto_approve='$strAutoApprove',transaction_type='$transaction_type',shipping_cancel='$strShippingCancel',shipping_timeframe=$iShippingTimeFrame,send_mail=$strUnsubscribe,"; 
		$qry_update_user .= " country='$country',zipcode='$zipcode',suspenduser='$suspend',activeuser=$trans_activity,";
		$qry_update_user .= " url1='$url1', url2='$url2',url3='$url3',billingdescriptor='$strtxtBillingdescriptor',fax_number='$faxnumber',reseller_other='$reseller_other', ";
		
		$qry_update_user .= "company_type = '$company_type', other_company_type = '$other_company_type', customer_service_phone = '$customerservice_phone', ";
		$qry_update_user .= "email = '$email', ";

		$qry_update_user .= "volumenumber = '$volume', avgticket = '$avgticket', chargebackper = '$chargeper', ";
		$qry_update_user .= "preprocess = '$prepro', recurbilling = '$rebill', currprocessing = '$currpro', ";

		$qry_update_user .= "telepackagename = '$txtPackagename', telepackageprod = '$txtPackageProduct', telepackageprice = $txtPackagePrice, ";
		$qry_update_user .= "telerefundpolicy = '$txtRefundPolicy', teledescription = '$txtDescription', ";

		$qry_update_user .= "company_bank = '$currentBank', other_company_bank = '$bank_other', beneficiary_name='$beneficiary_name', bank_account_name='$bank_account_name', bank_address = '$bank_address', bank_country = '$bank_country', bank_phone = '$bank_phone', bank_sort_code = '$bank_sort_code', bank_account_number = '$bank_account_number', bank_swift_code = '$bank_swift_code', completed_merchant_application = 1, setupfees=$setupFees, ";
		$qry_update_user .=  "merchant_discount_rate=$merchant_discount, reseller_discount_rate=$reseller_discount, total_discount_rate =$total_discount, merchant_trans_fees=$merchant_transfee , reseller_trans_fees=$reseller_transfee , total_trans_fees=$total_transfee, send_ecommercemail = $send_ecommercemail";
		$qry_update_user .= "  where userId=$userid";
		
		
		$iFreequency 		= 	(isset($HTTP_POST_VARS["cboFreequency"])?quote_smart($HTTP_POST_VARS["cboFreequency"]):"");
		$iNumberOfDaysBack	=	(isset($HTTP_POST_VARS["txtNumberOfDays"])?quote_smart($HTTP_POST_VARS["txtNumberOfDays"]):"");	
		$iFromWeekDay		=	(isset($HTTP_POST_VARS["cboFromWeekDay"])?quote_smart($HTTP_POST_VARS["cboFromWeekDay"]):"");
		$iToWeekDay			=	(isset($HTTP_POST_VARS["cboToWeekDay"])?quote_smart($HTTP_POST_VARS["cboToWeekDay"]):"");
		$iMiscFee			=	(isset($HTTP_POST_VARS["txtMiscFee"])?quote_smart($HTTP_POST_VARS["txtMiscFee"]):"");
		$iCompanyId			=	(isset($HTTP_POST_VARS["userid"])?quote_smart($HTTP_POST_VARS["userid"]):"");
		
		if ( $iFreequency != "" && $iNumberOfDaysBack != "" && $iFromWeekDay != "" && $iToWeekDay != "" && $iMiscFee != "" ) {
			$qryDelete = "delete from cs_invoice_setup where company_id = $iCompanyId";
			mysql_query($qryDelete,$cnn_cs);
			$qry_insert =  "insert into cs_invoice_setup (company_id,freequency,no_days_back,from_week_day,to_week_day,misc_fee) values ";
			$qry_insert .= "($iCompanyId,'$iFreequency',$iNumberOfDaysBack,$iFromWeekDay,$iToWeekDay,$iMiscFee)";
			mysql_query($qry_insert,$cnn_cs);
		}
		
		$iCheckBankId		=	(isset($HTTP_POST_VARS["cboCheckBank"])?quote_smart($HTTP_POST_VARS["cboCheckBank"]):"");
		$iCreditBankId		=	(isset($HTTP_POST_VARS["cboCrditBank"])?quote_smart($HTTP_POST_VARS["cboCrditBank"]):"");
		
		$qryDelete	=	"delete from cs_bank_company where company_id = $iCompanyId";
		mysql_query($qryDelete,$cnn_cs);
		
		if ( $iCheckBankId != "" || $iCreditBankId != "" ) {
			$qryFields = "company_id";
			$qryValues = $iCompanyId;
			if ( $iCheckBankId != "" ) {
				$qryFields .= ",check_bank_id";
				$qryValues .= ",".$iCheckBankId;
				
			}
			if ( $iCreditBankId != "" ) {
				$qryFields .= ",credit_bank_id";
					$qryValues .= ",".$iCreditBankId;
			}
			$qryInsert = "insert into cs_bank_company ($qryFields) values ($qryValues)";
			mysql_query($qryInsert,$cnn_cs);
		}	
		
		
		
		
	//	print $qry_update_user."<br>";
		if(!($show_sql=mysql_query($qry_update_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}		
		else 
		{
			$msgtodisplay="Company details of '".$companyname."' have been modified";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
				
  }
	
}
?>