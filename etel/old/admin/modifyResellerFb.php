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
//addcallcenteruserfb.php:		The page functions for callcenter users for this usertype = 1. 
include("includes/sessioncheck.php");

$headerInclude="reseller";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{



	$i_reseller_id = (isset($HTTP_POST_VARS['hid_reseller_id'])?quote_smart($HTTP_POST_VARS['hid_reseller_id']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
	$contactname = (isset($HTTP_POST_VARS['contactname'])?quote_smart($HTTP_POST_VARS['contactname']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
	$password= (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	$repassword= (isset($HTTP_POST_VARS['repassword'])?quote_smart($HTTP_POST_VARS['repassword']):"");
	$email= (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$confirmemail= (isset($HTTP_POST_VARS['confirmemail'])?quote_smart($HTTP_POST_VARS['confirmemail']):"");
	$merchantmonthly= (isset($HTTP_POST_VARS['merchantmonthly'])?quote_smart($HTTP_POST_VARS['merchantmonthly']):"");
	$phone= (isset($HTTP_POST_VARS['phone'])?quote_smart($HTTP_POST_VARS['phone']):"");
	$url1= (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
	$url2= (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
	$url3= (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");

	$rd_paydelay=(isset($HTTP_POST_VARS['rd_paydelay'])?quote_smart($HTTP_POST_VARS['rd_paydelay']):"");
	$rd_rollover=(isset($HTTP_POST_VARS['rd_rollover'])?quote_smart($HTTP_POST_VARS['rd_rollover']):"");
	$rd_wirefee=(isset($HTTP_POST_VARS['rd_wirefee'])?quote_smart($HTTP_POST_VARS['rd_wirefee']):"");

	$cboTitle = (isset($HTTP_POST_VARS['cboTitle'])?quote_smart($HTTP_POST_VARS['cboTitle']):"");
	$first_name = (isset($HTTP_POST_VARS['first_name'])?quote_smart($HTTP_POST_VARS['first_name']):"");
	$family_name = (isset($HTTP_POST_VARS['family_name'])?quote_smart($HTTP_POST_VARS['family_name']):"");
	$cboSex = (isset($HTTP_POST_VARS['cboSex'])?quote_smart($HTTP_POST_VARS['cboSex']):"");
	$txtAddress = (isset($HTTP_POST_VARS['txtAddress'])?quote_smart($HTTP_POST_VARS['txtAddress']):"");
	$txtPostCode = (isset($HTTP_POST_VARS['txtPostCode'])?quote_smart($HTTP_POST_VARS['txtPostCode']):"");
	$job_title = (isset($HTTP_POST_VARS['job_title'])?quote_smart($HTTP_POST_VARS['job_title']):"");
	$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
	$residence_telephone = (isset($HTTP_POST_VARS['residence_telephone'])?quote_smart($HTTP_POST_VARS['residence_telephone']):"");
	$fax = (isset($HTTP_POST_VARS['fax'])?quote_smart($HTTP_POST_VARS['fax']):"");

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

	$unsubscribe_mails = (isset($HTTP_POST_VARS['mail_send'])?quote_smart($HTTP_POST_VARS['mail_send']):"1");
	
	$suspend_user = (isset($HTTP_POST_VARS['suspend_user'])?quote_smart($HTTP_POST_VARS['suspend_user']):"0");
	
	$qry_selectdetails = "select * from cs_resellerdetails where reseller_id = $i_reseller_id";	
	if (!($rst_selectdetails = mysql_query($qry_selectdetails)))
	{			
		dieLog(mysql_errno().": ".mysql_error()."<BR>");
	}
	$resellerInfo = mysql_fetch_array($rst_selectdetails);
	
	if($unsubscribe_mails=="")$unsubscribe_mails=1;
	
	if($unsubscribe_mails!=$resellerInfo['reseller_sendmail'])
	{
		if($unsubscribe_mails) 
		{
			removeListEmail($resellerInfo['reseller_email']);
		}
		else 
		{
			addListEmail($resellerInfo['reseller_email'],"Admin Unsubscribed Email",$resellerInfo['reseller_id'],'reseller','unsubscribe');
		}
	}
	
	
	if($companyname == "" || $contactname == ""){
		$msgtodisplay="Insufficient data.";
		$outhtml="Y";				
		message($msgtodisplay,$outhtml,$headerInclude);  
		exit();
	}

	if($password == "") {
		$msgtodisplay="Please enter Password.";
		$outhtml="Y";				
		message($msgtodisplay,$outhtml,$headerInclude);  
		exit();
	}
	
	/*
	$qry_select_user = "select companyname,email from cs_companydetails where ( companyname='$companyname' or email='$email' ) and userid<>$i_reseller_id";
	if(!($show_sql =mysql_query($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else if(mysql_num_rows($show_sql) >0) 
		{
			 if(mysql_result($show_sql,0,0) == $companyname) {
				$msgtodisplay="company name ".$companyname." already exists";
			}
			else{
			$msgtodisplay="email id ".$email." already exists";
			}
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
	*/
	$qry_update = "Update cs_resellerdetails set reseller_companyname='$companyname', reseller_contactname='$contactname', reseller_password='$password', reseller_email='$email', reseller_phone='$phone',reseller_url ='$url1',reseller_url1 ='$url2',reseller_url2 ='$url3',reseller_monthly_volume='$merchantmonthly', reseller_title = '$cboTitle', reseller_firstname = '$first_name', reseller_lastname = '$family_name', reseller_sex = '$cboSex', reseller_address = '$txtAddress', reseller_zipcode = '$txtPostCode',
				 rd_rollover='$rd_rollover',rd_wirefee='$rd_wirefee',rd_paydelay='$rd_paydelay',reseller_jobtitle = '$job_title', reseller_email = '$contact_email',reseller_res_phone='$residence_telephone', reseller_faxnumber ='$fax',reseller_bankname = '$currentBank', reseller_otherbank = '$bank_other', bank_address = '$bank_address', bank_country = '$bank_country', bank_telephone = '$bank_phone', bank_sortcode = '$bank_sort_code', bank_accountno = '$bank_account_number', bank_swiftcode = '$bank_swift_code',bank_benificiaryname='$beneficiary_name',bank_accountname='$bank_account_name', reseller_sendmail=$unsubscribe_mails,
				 suspend_reseller=$suspend_user  where reseller_id = $i_reseller_id";
	if(mysql_query($qry_update,$cnn_cs))
	{
		$msgtodisplay="User details updated successfully";
		$outhtml="Y";				
		message($msgtodisplay,$outhtml,$headerInclude);  
		exit();
	}
	else
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	?>
	<html>
	<head>
	</head>
	<body onLoad="javascript:func_submit();">
	<script language="JavaScript">
	function func_submit()
	{
		window.location = "addcallcenteruser.php";
	}
	</script>
	</body>
	</html>
<?
}
?>
