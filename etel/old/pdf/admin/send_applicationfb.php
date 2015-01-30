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
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,application.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//transactionverification.php:	The admin page functions for selecting the type of report view  for the company. 

include("includes/sessioncheck.php");


$headerInclude = "mail";
include("includes/header.php");
include("includes/mailbody_replytemplate.php");
include("includes/zipclass.php");

include("includes/message.php");


set_time_limit(240);
ignore_user_abort(true);
ini_set("max_execution_time",0);

$company_name = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$other_email = isset($HTTP_POST_VARS['txt_to'])?$HTTP_POST_VARS['txt_to']:"";
$is_app = isset($HTTP_POST_VARS['chk_app'])?$HTTP_POST_VARS['chk_app']:"";
$is_doc = isset($HTTP_POST_VARS['chk_doc'])?$HTTP_POST_VARS['chk_doc']:"";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"tele";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$completed_uploading = isset($HTTP_POST_VARS['completed_uploading'])?quote_smart($HTTP_POST_VARS['completed_uploading']):"";
$completed_application = isset($HTTP_POST_VARS['completed_application'])?quote_smart($HTTP_POST_VARS['completed_application']):"";
$ready_to_wire = isset($HTTP_POST_VARS['ready_to_wire'])?quote_smart($HTTP_POST_VARS['ready_to_wire']):"";

if ($company_name == "") {
	$outhtml="y";
	$msgtodisplay="Please select a company";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
}
$str_where_condition = "";
$str_company_ids = "";
$str_failure_companies = "";
$error_msg = "";
$arr_result = array();
if ($company_name[0] == "A") {
	if ($companytype == "A") {
		if ($companytrans_type == "A") {
			$str_where_condition = "";
		} else {
			$str_where_condition = "where transaction_type = '$companytrans_type' ";
		}
	} else if ($companytype == "AC") {
		if ($companytrans_type == "A") {
			$str_where_condition = "where activeuser = 1 ";
		} else {
			$str_where_condition = "where activeuser = 1 and transaction_type = '$companytrans_type' ";
		}
	} else if ($companytype == "NC") {
		if ($companytrans_type == "A") {
			$str_where_condition = "where activeuser = 0 ";
		} else {
			$str_where_condition = "where activeuser = 0 and transaction_type = '$companytrans_type' ";
		}
	} else if ($companytype == "RE") {
		if ($companytrans_type == "A") {
			$str_where_condition = "where reseller_id <> '' ";
		} else {
			$str_where_condition = "where reseller_id <> '' and transaction_type = '$companytrans_type' ";
		}
	} else if ($companytype == "ET") {
		if ($companytrans_type == "A") {
			$str_where_condition = "where reseller_id is null ";
		} else {
			$str_where_condition = "where reseller_id is null and transaction_type = '$companytrans_type' ";
		}
	}

	 if($completed_uploading ==1) {
	 	if($str_where_condition =="") {
		    $str_where_condition = "where num_documents_uploaded=4";	
		}else {
		    $str_where_condition .= "and num_documents_uploaded=4";	
		}
	 }
	 
	 if($completed_application ==1) {
	 	if($str_where_condition =="") {
			$str_where_condition = " where completed_merchant_application=1";
		} else {
		  	$str_where_condition .= " and completed_merchant_application=1";
		}
	 }

	 if($ready_to_wire ==1) {
	 	if($str_where_condition =="") {
			$str_where_condition = " where completed_uploading='Y'";
		} else {
		  	$str_where_condition .= " and completed_uploading='Y'";
		}
	 }

} else {
	for ($i = 0; $i < count($company_name); $i++) {
		$str_company_ids .= $company_name[$i] . ", ";
	}
	$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 2);
	$str_where_condition = "where userId in ($str_company_ids)";
}
if($str_where_condition == "") {
	$str_where_condition = " where 1 ";
} else {
	$str_where_condition .= " and gateway_id = -1";
}
$str_qry_company ="select * from cs_companydetails $str_where_condition order by companyname";
//print($str_qry_company);
$zipfile = new zipfile();  
$rst_qry_company = mysql_query($str_qry_company);
while($arr_qry_company = mysql_fetch_array($rst_qry_company))
{
	$str_company_name =  $arr_qry_company['companyname'];
	$company_id = $arr_qry_company['userId'];
	$i_max_file_size_MB = 3; 
	$dir_company_name = func_replace_invalid_literals($str_company_name);
	$zipfile -> add_dir($dir_company_name."/");
	if($is_app != "")
	{
		$str_legal_company_name =  $arr_qry_company['legal_name'];
		$company_type  = $arr_qry_company['company_type'];
		$other_company_type  = $arr_qry_company['other_company_type'];
		$address = $arr_qry_company['address'];
		$city = $arr_qry_company['city'];
		$state = $arr_qry_company['state'];
		$zip_code = $arr_qry_company['zipcode'];
		$country=$arr_qry_company['country'];
		$inc_country=$arr_qry_company['incorporated_country'];
		$inc_number=$arr_qry_company['incorporated_number'];
		$phone=$arr_qry_company['phonenumber'];
		$email=$arr_qry_company['email'];
		$fax_number = $arr_qry_company['fax_number'];
		$fax_dba = $arr_qry_company['fax_dba'];
		$physical_address = $arr_qry_company['physical_address'];
		$cellular = $arr_qry_company['cellular'];
		$tech_contact_details = $arr_qry_company['technical_contact_details'];
		$admin_contact_details = $arr_qry_company['admin_contact_details'];
		$max_ticket_amt = $arr_qry_company['max_ticket_amt'];
		$min_ticket_amt = $arr_qry_company['min_ticket_amt'];
		$goods_list = $arr_qry_company['goods_list'];
		$volume_last_month = $arr_qry_company['volume_last_month'];
		$volume_prev_30days = $arr_qry_company['volume_prev_30days'];
		$volume_prev_60days = $arr_qry_company['volume_prev_60days'];
		$totals = $arr_qry_company['totals'];
		$forecast_volume_1month = $arr_qry_company['forecast_volume_1month'];
		$forecast_volume_2month = $arr_qry_company['forecast_volume_2month'];
		$forecast_volume_3month = $arr_qry_company['forecast_volume_3month'];
		$current_anti_fraud_system = $arr_qry_company['current_anti_fraud_system'];
		$customer_service_program = $arr_qry_company['customer_service_program'];
		$customer_service_phone = $arr_qry_company['customer_service_phone'];
		$refund_policy = $arr_qry_company['refund_policy'];

		$url1=$arr_qry_company['url1'];
		$url2=$arr_qry_company['url2'];
		$url3=$arr_qry_company['url3'];
		$merchant_name=$arr_qry_company['merchantName'];
		$toll_free_number=$arr_qry_company['tollFreeNumber'];
		$retrieval_number=$arr_qry_company['retrievalNumber'];
		$security_number=$arr_qry_company['securityNumber'];
		$processor=$arr_qry_company['processor'];
		$billing_descriptor=$arr_qry_company['billingdescriptor'];
		$charge_back=$arr_qry_company['chargeback'];
		$credit=$arr_qry_company['credit'];
		$transaction_fee=$arr_qry_company['transactionfee'];
		$voice_authorization_fee=$arr_qry_company['voiceauthfee'];
		$discount_rate=$arr_qry_company['discountrate'];
		$reserve=$arr_qry_company['reserve'];

		$company_status =$arr_qry_company['activeuser'];
		$merchant_type =func_get_merchant_name($arr_qry_company['transaction_type']);
		$customer_service_cancel=$arr_qry_company['auto_cancel'];
		$customer_service_cancel_dt=$arr_qry_company['time_frame'];
		$shipping_cancel=$arr_qry_company['shipping_cancel'];
		$shipping_cancel_dt=$arr_qry_company['shipping_timeframe'];
		$auto_approve=$arr_qry_company['auto_approve'];
		$volumenumber  = $arr_qry_company['volumenumber'];
		$avgticket = $arr_qry_company['avgticket'];

		$charge_back_per = $arr_qry_company['chargebackper'];
		$previous_processing = $arr_qry_company['preprocess'];
		$recurring_billing = $arr_qry_company['recurbilling'];
		$currently_processing = $arr_qry_company['currprocessing'];

		$title = $arr_qry_company['stitle'];
		$first_name = $arr_qry_company['first_name'];
		$family_name = $arr_qry_company['family_name'];
		$date_of_birth = $arr_qry_company['sdateofbirth'];
		$sex = $arr_qry_company['ssex'];
		$user_address = $arr_qry_company['saddress'];
		$user_zipcode = $arr_qry_company['spostcode'];
		$contact_email = $arr_qry_company['contact_email'];
		$contact_phone 	= $arr_qry_company['contact_phone'];
		$job_title 	= $arr_qry_company['job_title'];
		$user_residence_number 	= $arr_qry_company['sresidencetelephone'];
		$user_fax_number 	= $arr_qry_company['sfax'];
		$how_about_us = $arr_qry_company['how_about_us'];
		if ($how_about_us == "other") {
			$how_about_us = $arr_qry_company['reseller_other'];
		} else if ($how_about_us == "rsel") {
			$show_sql = "select reseller_name from cs_resellerdetails where reseller_id = ".$arr_qry_company['reseller_id'];
			$how_about_us = funcGetValueByQuery($show_sql,$cnn_cs);
		}

		$package_name = $arr_qry_company['telepackagename'];
		$package_service = $arr_qry_company['telepackageprod'];
		$package_price = $arr_qry_company['telepackageprice'];
		$script_refund_policy = $arr_qry_company['telerefundpolicy'];
		$package_description = $arr_qry_company['teledescription'];

		$company_bank = $arr_qry_company['company_bank'];
		$beneficiary_name = $arr_qry_company['beneficiary_name'];
		$name_on_account = $arr_qry_company['bank_account_name'];
		$bank_address = $arr_qry_company['bank_address'];
		$bank_country = $arr_qry_company['bank_country'];
		$bank_phone = $arr_qry_company['bank_phone'];
		$bank_sort_code = $arr_qry_company['bank_sort_code'];
		$bank_account_number = $arr_qry_company['bank_account_number'];
		$bank_swift_code  = $arr_qry_company['bank_swift_code'];
		$currency = $arr_qry_company['processing_currency'];
		$str_currency = "";
		switch ($currency) {
			case "AUD" : 
						$str_currency = "Australian Dollar";
						break;
			case "GBP" : 
						$str_currency = "UK Pound";
						break;
			case "USD" : 
						$str_currency = "US Dollar";
						break;
			case "CAD" : 
						$str_currency = "Canadian Dollar";
						break;
			case "EUR" : 
						$str_currency = "Euro";
						break;
		}

		$str_applilcation = func_get_application_form($merchant_type);
		$str_applilcation = str_replace("[str_company_name]",$str_company_name,$str_applilcation);
		$str_applilcation = str_replace("[str_legal_company_name]",$str_company_name,$str_applilcation);
		$str_applilcation = str_replace("[company_type]",$company_type,$str_applilcation); 
		$str_applilcation = str_replace("[address]",$address,$str_applilcation);
		$str_applilcation = str_replace("[city]",$city,$str_applilcation);
		$str_applilcation = str_replace("[state]",$state,$str_applilcation);
		$str_applilcation = str_replace("[zip_code]",$zip_code,$str_applilcation);
		$str_applilcation = str_replace("[country]",$country,$str_applilcation);
		$str_applilcation = str_replace("[inc_country]",$inc_country,$str_applilcation);
		$str_applilcation = str_replace("[inc_number]",$inc_number,$str_applilcation);
		$str_applilcation = str_replace("[phone]",$phone,$str_applilcation);
		$str_applilcation = str_replace("[cellular]",$cellular,$str_applilcation);
		$str_applilcation = str_replace("[email]",$email,$str_applilcation);
		$str_applilcation = str_replace("[fax_number]",$fax_number,$str_applilcation);
		$str_applilcation = str_replace("[fax_dba]",$fax_dba,$str_applilcation);
		$str_applilcation = str_replace("[physical_company_address]",$physical_address,$str_applilcation);
		$str_applilcation = str_replace("[tech_contact_details]",$tech_contact_details,$str_applilcation);
		$str_applilcation = str_replace("[admin_contact_details]",$admin_contact_details,$str_applilcation);
		$str_applilcation = str_replace("[max_ticket_amt]",$max_ticket_amt,$str_applilcation);
		$str_applilcation = str_replace("[min_ticket_amt]",$min_ticket_amt,$str_applilcation);
		$str_applilcation = str_replace("[goods_list]",$goods_list,$str_applilcation);
		$str_applilcation = str_replace("[volume_last_month]",$volume_last_month,$str_applilcation);
		$str_applilcation = str_replace("[volume_prev_30days]",$volume_prev_30days,$str_applilcation);
		$str_applilcation = str_replace("[volume_prev_60days]",$volume_prev_60days,$str_applilcation);
		$str_applilcation = str_replace("[totals]",$totals,$str_applilcation);
		$str_applilcation = str_replace("[forecast_first_month]",$forecast_volume_1month,$str_applilcation);
		$str_applilcation = str_replace("[forecast_second_month]",$forecast_volume_2month,$str_applilcation);
		$str_applilcation = str_replace("[forecast_third_month]",$forecast_volume_3month,$str_applilcation);
		$str_applilcation = str_replace("[current_anti_fraud_system]",$current_anti_fraud_system,$str_applilcation);
		$str_applilcation = str_replace("[cust_service_program]",$customer_service_program,$str_applilcation);
		$str_applilcation = str_replace("[cust_service_phone]",$customer_service_phone,$str_applilcation);
		$str_applilcation = str_replace("[refund_policy]",$refund_policy,$str_applilcation);

		$str_applilcation = str_replace("[title]",$title,$str_applilcation);
		$str_applilcation = str_replace("[first_name]",$first_name,$str_applilcation);
		$str_applilcation = str_replace("[family_name]",$family_name,$str_applilcation);
		$str_applilcation = str_replace("[date_of_birth]",$date_of_birth,$str_applilcation);
		$str_applilcation = str_replace("[sex]",$sex,$str_applilcation);
		$str_applilcation = str_replace("[user_address]",$user_address,$str_applilcation);
		$str_applilcation = str_replace("[user_zipcode]",$user_zipcode,$str_applilcation);
		$str_applilcation = str_replace("[job_title]",$job_title,$str_applilcation);
		$str_applilcation = str_replace("[contact_email]",$contact_email,$str_applilcation);
		$str_applilcation = str_replace("[contact_phone]",$contact_phone,$str_applilcation);
		$str_applilcation = str_replace("[user_residence_number]",$user_residence_number,$str_applilcation);
		$str_applilcation = str_replace("[user_fax_number]",$user_fax_number,$str_applilcation);
		$str_applilcation = str_replace("[how_about_us]",$how_about_us,$str_applilcation);

		$str_applilcation = str_replace("[url1]",$url1,$str_applilcation);
		$str_applilcation = str_replace("[url2]",$url2,$str_applilcation);
		$str_applilcation = str_replace("[url3]",$url3,$str_applilcation);

		$str_applilcation = str_replace("[merchant_name]",$merchant_name,$str_applilcation);
		$str_applilcation = str_replace("[toll_free_number]",$toll_free_number,$str_applilcation);
		$str_applilcation = str_replace("[retrieval_number]",$retrieval_number,$str_applilcation);
		$str_applilcation = str_replace("[security_number]",$security_number,$str_applilcation);


		$str_applilcation = str_replace("[processor]",$processor,$str_applilcation);
		//$str_applilcation = str_replace("[billing_descriptor]",$billing_descriptor,$str_applilcation);
		//$str_applilcation = str_replace("[company_status]",$company_status,$str_applilcation);


		$str_applilcation = str_replace("[charge_back]",$charge_back,$str_applilcation);
		$str_applilcation = str_replace("[credit]",$credit,$str_applilcation);
		$str_applilcation = str_replace("[transaction_fee]",$transaction_fee,$str_applilcation);
		$str_applilcation = str_replace("[voice_authorization_fee]",$voice_authorization_fee,$str_applilcation);
		$str_applilcation = str_replace("[discount_rate]",$discount_rate,$str_applilcation);
		$str_applilcation = str_replace("[reserve]",$reserve,$str_applilcation);

		//$str_applilcation = str_replace("[company_status]",$company_status,$str_applilcation);
		$str_applilcation = str_replace("[merchant_type]",$merchant_type,$str_applilcation);
		$str_applilcation = str_replace("[volumenumber]",$volumenumber,$str_applilcation);
		$str_applilcation = str_replace("[avgticket]",$avgticket,$str_applilcation);
		$str_applilcation = str_replace("[charge_back_per]",$charge_back_per,$str_applilcation);
		$str_applilcation = str_replace("[billing_descriptor]",$billing_descriptor,$str_applilcation);
		$str_applilcation = str_replace("[previous_processing]",$previous_processing,$str_applilcation);
		$str_applilcation = str_replace("[recurring_billing]",$recurring_billing,$str_applilcation);
		$str_applilcation = str_replace("[currently_processing]",$currently_processing,$str_applilcation);
		$str_applilcation = str_replace("[currency]",$str_currency,$str_applilcation);

		if ($merchant_type == "Telemarketing") {
			$str_applilcation = str_replace("[package_name]",$package_name,$str_applilcation);
			$str_applilcation = str_replace("[package_service]",$package_service,$str_applilcation);
			$str_applilcation = str_replace("[package_price]",$package_price,$str_applilcation);
			$str_applilcation = str_replace("[script_refund_policy]",$script_refund_policy,$str_applilcation);
			$str_applilcation = str_replace("[package_description]",$package_description,$str_applilcation);
		}

		$str_applilcation = str_replace("[company_bank]",$company_bank,$str_applilcation);
		$str_applilcation = str_replace("[beneficiary_name]",$beneficiary_name,$str_applilcation);
		$str_applilcation = str_replace("[name_on_account]",$name_on_account,$str_applilcation);
		$str_applilcation = str_replace("[bank_address]",$bank_address,$str_applilcation);
		$str_applilcation = str_replace("[bank_country]",$bank_country,$str_applilcation);
		$str_applilcation = str_replace("[bank_phone]",$bank_phone,$str_applilcation);
		$str_applilcation = str_replace("[bank_sort_code]",$bank_sort_code,$str_applilcation);
		$str_applilcation = str_replace("[bank_account_number]",$bank_account_number,$str_applilcation);
		$str_applilcation = str_replace("[bank_swift_code]",$bank_swift_code,$str_applilcation);

		$zipfile -> add_dir($dir_company_name."/Application/");
		$filedata = $str_applilcation; 
		$zipfile -> add_file($filedata, $dir_company_name."/Application/application_". $company_id .".html");  
	}

	if($is_doc != "")
	{
		$myLicenceFileArray = array();
		$myArticlesFileArray = array();
		$myHistoryFileArray = array();
		$myProfessionalReferenceFileArray = array();
		$str_qry = "select file_type, file_name from cs_uploaded_documents where user_id = $company_id and status = 'A'";
		if(!($show_sql =mysql_query($str_qry,$cnn_cs)))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query");
			print($str_qry);
			exit();
		}
		if(mysql_num_rows($show_sql)>0)
		{
			while($showval = mysql_fetch_row($show_sql)) 
			{
				if ($showval[0] == "License") {
					$myLicenceFileArray[] = $showval[1];
				} else if ($showval[0] == "Articles") {
					$myArticlesFileArray[] = $showval[1];
				} else if ($showval[0] == "History") {
					$myHistoryFileArray[] = $showval[1];
				} else if ($showval[0] == "Professional_Reference") {
					$myProfessionalReferenceFileArray[] = $showval[1];
				}
			}
		}
		//print("company= ".$dir_company_name);
		// add the subdirectory ... important!
		//print("comp. file size= ".$zipfile -> get_file_size()."<br>");
			//	print($str_company_name."<br>");

		if (count($myArticlesFileArray) > 0) {
			for($i_loop=0;$i_loop<count($myArticlesFileArray);$i_loop++) 
			{	
				
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/Articles/".$myArticlesFileArray[$i_loop]);
				$compressed_file_size = func_get_compressed_file_size($filedata);
				if (($zipfile -> get_file_size()) + $compressed_file_size >= $i_max_file_size_MB) {
					//print("articles");
					$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg);
					$zipfile = new zipfile();  
					$zipfile -> add_dir($dir_company_name."/Articles/");
				} else {
					if ($i_loop == 0) {
						$zipfile -> add_dir($dir_company_name."/Articles/");
					}
				}
				$zipfile -> add_file($filedata, $dir_company_name."/Articles/".$myArticlesFileArray[$i_loop]);  
			}
			//print("comp. file size= ".$zipfile -> get_file_size()."<br>");
		}

		if (count($myHistoryFileArray) > 0) {
			for($i_loop=0;$i_loop<count($myHistoryFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/History/".$myHistoryFileArray[$i_loop]);
				$compressed_file_size = func_get_compressed_file_size($filedata);
				if (($zipfile -> get_file_size()) + $compressed_file_size >= $i_max_file_size_MB) {
					$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg);
					$zipfile = new zipfile();  
					//print("in History");
					$zipfile -> add_dir($dir_company_name."/History/");
				} else {
					if ($i_loop == 0) {
						$zipfile -> add_dir($dir_company_name."/History/");
					}
				}
				$zipfile -> add_file($filedata, $dir_company_name."/History/".$myHistoryFileArray[$i_loop]);  
			}
			//print("comp. file size= ".$zipfile -> get_file_size()."<br>");
		}

		if (count($myLicenceFileArray) > 0) {
			for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/License/".$myLicenceFileArray[$i_loop] );
				$compressed_file_size = func_get_compressed_file_size($filedata);
				if (($zipfile -> get_file_size()) + $compressed_file_size >= $i_max_file_size_MB) {
					$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg);
					$zipfile = new zipfile();  
					$zipfile -> add_dir($dir_company_name."/License/");
				} else {
					if ($i_loop == 0) {
						$zipfile -> add_dir($dir_company_name."/License/");
					}
				}
				$zipfile -> add_file($filedata, $dir_company_name."/License/".$myLicenceFileArray[$i_loop]);
			}
			//print("comp. file size= ".$zipfile -> get_file_size()."<br>");
		}

		if (count($myProfessionalReferenceFileArray) > 0) {
			for($i_loop=0;$i_loop<count($myProfessionalReferenceFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/Professional_Reference/".$myProfessionalReferenceFileArray[$i_loop] );
				$compressed_file_size = func_get_compressed_file_size($filedata);
				if (($zipfile -> get_file_size()) + $compressed_file_size >= $i_max_file_size_MB) {
					$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg);
					$zipfile = new zipfile();  
					$zipfile -> add_dir($dir_company_name."/Professional_Reference/");
				} else {
					if ($i_loop == 0) {
						$zipfile -> add_dir($dir_company_name."/Professional_Reference/");
					}
				}
				$zipfile -> add_file($filedata, $dir_company_name."/Professional_Reference/".$myProfessionalReferenceFileArray[$i_loop]);
			}
			//print("comp. file size= ".$zipfile -> get_file_size()."<br>");
		}
	}

	if ($zipfile -> get_file_size() > 0) {
		$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg);
	}

}

function getDirList ($dirName,$i_company) 
{
	$d = dir($dirName);
	$filename="";
	while($entry = $d->read()) {	
		//echo strpos($entry,$i_company);
		if($entry != "." && $entry != "..")
		{
			if(strpos($entry,$i_company)>=0)
			{
				//echo $dirName."\\".$entry."\n";
				$filename= $entry;
				break;
			}
		}
		}	
	
$d->close();
return $filename;
}

function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}

function func_read_file($filename)
{
	$handle = fopen ($filename, "rb"); 
	$contents = fread ($handle, filesize ($filename)); 
	fclose ($handle); 
	return $contents; 
}

function fsize($file) {
   $a = array("B", "KB", "MB", "GB", "TB", "PB");
   $pos = 2;
   $size = filesize($file);
   while ($pos > 0) {
		   $size /= 1024;
		   $pos--;
   }
 //  return round($size,2)." ".$a[$pos];
    return $size;

}

function func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, $i_max_file_size_MB, $error_msg) {
	$arr_result = array(2);
	$zip_file_name = func_replace_invalid_literals($str_company_name);
	$filename = "csv/".$zip_file_name."_document.zip";
	$fd = fopen ($filename, "wb");
	$out = fwrite ($fd, $zipfile -> file());
	fclose ($fd);
	$file_size = fsize($filename);
	//print($file_size."<br>");
	if($file_size < $i_max_file_size_MB) {
		$mailbody = "<html><head></head><body>";
		$mailbody .= "Application / Documents for ". $str_company_name . " are being attached herewith." ;
		$mailbody .= "</body></html>";
	
		$mail_response ="";
		$arrFiles = array($filename);
		$arrFileNames = array("application_document.zip");
		if($other_email != "") {
		//print("$str_company_name<br>");
			if(!sendMail($_SESSION['gw_emails_sales'],"Application/Documents",$mailbody,$other_email,$arrFiles,$arrFileNames)) {
				//print("failure: $str_company_name<br>");
				$str_failure_companies .= "<br> $str_company_name";
			} else {
				//print("success: $str_company_name<br>");
			}
		}
	} else {
		$error_msg .= "<br> $str_company_name";
	}
	delete ($filename);
	$arr_result[0] = $str_failure_companies;
	$arr_result[1] = $error_msg;
	return $arr_result;
}

function func_get_compressed_file_size($data) {
	$zdata = gzcompress($data);  
	$zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
	$file_size = strlen($zdata);
	$file_size /= 1024;
	$file_size /= 1024;
	return $file_size;  

}

function func_replace_invalid_literals($str_company_name) {
	$str_company_name = str_replace(" ","_",$str_company_name);
	$str_company_name = str_replace("/","_",$str_company_name);
	$str_company_name = str_replace("\\","_",$str_company_name);
	$str_company_name = str_replace(":","_",$str_company_name);
	$str_company_name = str_replace("*","_",$str_company_name);
	$str_company_name = str_replace("?","_",$str_company_name);
	$str_company_name = str_replace("\"","_",$str_company_name);
	$str_company_name = str_replace("<","_",$str_company_name);
	$str_company_name = str_replace(">","_",$str_company_name);
	$str_company_name = str_replace("|","_",$str_company_name);
	return $str_company_name;
}
$str_failure_companies = $arr_result[0];
$error_msg = $arr_result[1];
if ($str_failure_companies == "" ) {
	$mail_response = " <br> Application / Documents for the selected companies have been sent to " .$other_email . " successfully. <br>";

} else {
	$mail_response = " Sending Application / Documents for some of the companies failed";
}

if ($error_msg != "") {
	$mail_response .= "<br>The file size of the following companies exceeds the limit and hence those documents could not be sent :<br> $error_msg";
}
message($mail_response,"y","");

?>