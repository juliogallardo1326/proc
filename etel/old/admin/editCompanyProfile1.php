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
// editCompanyProfile1.php:	This admin page functions for editing the company details.
$allowBank=true;
include("includes/sessioncheck.php");
include("../includes/completion.php");

$markComp = "Mark this Company";

$loginas = (isset($HTTP_GET_VARS["loginas"])?trim($HTTP_GET_VARS["loginas"]):"");
if($loginas){
	$etel_debug_mode=0;
	require_once("../includes/dbconnection.php");

	$_SESSION["loginredirect"]="None";
	
	if($resellerInfo['isMasterMerchant'])	$_SESSION["gw_masterMerchant_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Reseller|".$_SESSION['gw_id']."|editCompanyProfile.php?company_id=".$_GET['company_id']);

	$_SESSION["gw_admin_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Admin|".$_SESSION['gw_id']."|editCompanyProfile1.php?company_id=".$_GET['company_id']);

	general_login($_GET['username'],$_GET['password'],"merchant",$_GET['gw_id'],false);
	die();
}

$headerInclude = "companies";
include("includes/header.php");

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_update =isset($HTTP_POST_VARS["update"])?$HTTP_POST_VARS["update"]:"";
$trans_activity="";
	$is_Gateway	 = (isset($HTTP_GET_VARS["GatewayCompany"])?quote_smart($HTTP_GET_VARS["GatewayCompany"]):"");
	if ($str_update == "yes") {
		$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");
		$userid = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
		$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
		$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
		$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
		//$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");


		if($_REQUEST['Submit'] == $markComp) 
		{
			if(!@in_array($userid,$adminConfig['mList']))
			{
				//if($adminConfig['mList']) $adminConfig['mList'].=",";
				$adminConfig['mList'][]=$userid;
				$adminConfigUpdated=true;
			}
		}


		$qry_select_user = "select username,companyname,email from cs_companydetails where 0 and ( username='$username' or companyname='$companyname' or email='$email' ) and userid<>'$userid' $bank_sql_limit";
		//print($qry_select_user);
		if(!($show_sql =sql_query_read($qry_select_user)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		else if(mysql_num_rows($show_sql) >0)
		{
			 if(mysql_result($show_sql,0,1) == $companyname) {
				$msgtodisplay="<font color='red'>company name ".$companyname." already exists</font>";
				}
				elseif (mysql_result($show_sql, 0, 0) == $username) {
					$msgtodisplay="<font color='red'>user name ".$username." already exists</font>";
				}
				else{
					$msgtodisplay="<font color='red'> email id ".$email." already exists</font>";
				}
				$outhtml="y";
				message($msgtodisplay,$outhtml,$headerInclude);
				exit();
		}
		else
		{


			$loginas = (isset($HTTP_GET_VARS['loginas'])?quote_smart($HTTP_GET_VARS['loginas']):"");
			$first_name = (isset($HTTP_POST_VARS['first_name'])?quote_smart($HTTP_POST_VARS['first_name']):"");
			$family_name = (isset($HTTP_POST_VARS['family_name'])?quote_smart($HTTP_POST_VARS['family_name']):"");
			$job_title = (isset($HTTP_POST_VARS['job_title'])?quote_smart($HTTP_POST_VARS['job_title']):"");
			$contact_email = (isset($HTTP_POST_VARS['contact_email'])?quote_smart($HTTP_POST_VARS['contact_email']):"");
			$confirm_contact_email = (isset($HTTP_POST_VARS['confirm_contact_email'])?quote_smart($HTTP_POST_VARS['confirm_contact_email']):"");
			$contact_phone = (isset($HTTP_POST_VARS['contact_phone'])?quote_smart($HTTP_POST_VARS['contact_phone']):"");
			$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
			$how_about_us_other = (isset($HTTP_POST_VARS['how_about_us_other'])?quote_smart($HTTP_POST_VARS['how_about_us_other']):"");

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

			$company_type = (isset($HTTP_POST_VARS['company_type'])?quote_smart($HTTP_POST_VARS['company_type']):"");
			$other_company_type = (isset($HTTP_POST_VARS['other_company_type'])?quote_smart($HTTP_POST_VARS['other_company_type']):"");
			$customerservice_phone = (isset($HTTP_POST_VARS['customerservice_phone'])?quote_smart($HTTP_POST_VARS['customerservice_phone']):"");

			$transaction_type = (isset($HTTP_POST_VARS['rad_trans_type'])?quote_smart($HTTP_POST_VARS['rad_trans_type']):"");
			$strAutoCancel = (isset($HTTP_POST_VARS['chk_auto_cancel'])?quote_smart($HTTP_POST_VARS['chk_auto_cancel']):"N");
			$iTimeFrame  = (isset($HTTP_POST_VARS['time_frame'])?quote_smart($HTTP_POST_VARS['time_frame']):"-1");
			$strShippingCancel  = (isset($HTTP_POST_VARS['chk_shipping_cancel'])?quote_smart($HTTP_POST_VARS['chk_shipping_cancel']):"N");
			$iShippingTimeFrame  = (isset($HTTP_POST_VARS['shipping_time_frame'])?quote_smart($HTTP_POST_VARS['shipping_time_frame']):"-1");
			$strAutoApprove  = (isset($HTTP_POST_VARS['chk_auto_approve'])?quote_smart($HTTP_POST_VARS['chk_auto_approve']):"N");
			 $cd_processing_reason = (isset($HTTP_POST_VARS['cd_processing_reason'])?quote_smart($HTTP_POST_VARS['cd_processing_reason']):"");
			 $cd_previous_processor = (isset($HTTP_POST_VARS['cd_previous_processor'])?quote_smart($HTTP_POST_VARS['cd_previous_processor']):"");
			if($trans_activity =="")
				$trans_activity =0;
			if($iShippingTimeFrame == "")
				$iShippingTimeFrame = "-1";
			if($iTimeFrame == "")
				$iTimeFrame = "-1";

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
			$setupFees = (isset($HTTP_POST_VARS['txtSetupFee'])?quote_smart($HTTP_POST_VARS['txtSetupFee']):"");
			if ($setupFees=="")
				$setupFees=0;

			$legal_companyname= (isset($HTTP_POST_VARS['legal_companyname'])?quote_smart($HTTP_POST_VARS['legal_companyname']):"");
			$inc_country= (isset($HTTP_POST_VARS['inc_country'])?quote_smart($HTTP_POST_VARS['inc_country']):"");
			$inc_number= (isset($HTTP_POST_VARS['inc_number'])?quote_smart($HTTP_POST_VARS['inc_number']):"");
			$physical_address= (isset($HTTP_POST_VARS['physical_address'])?quote_smart($HTTP_POST_VARS['physical_address']):"");
			$fax_dba= (isset($HTTP_POST_VARS['fax_dba'])?quote_smart($HTTP_POST_VARS['fax_dba']):"");
			$cellular= (isset($HTTP_POST_VARS['cellular'])?quote_smart($HTTP_POST_VARS['cellular']):"");
			$tech_contact_details= (isset($HTTP_POST_VARS['tech_contact_details'])?quote_smart($HTTP_POST_VARS['tech_contact_details']):"");
			$admin_contact_details= (isset($HTTP_POST_VARS['admin_contact_details'])?quote_smart($HTTP_POST_VARS['admin_contact_details']):"");
			$max_ticket_amt= (isset($HTTP_POST_VARS['max_ticket_amt'])?quote_smart($HTTP_POST_VARS['max_ticket_amt']):"");
			$min_ticket_amt= (isset($HTTP_POST_VARS['min_ticket_amt'])?quote_smart($HTTP_POST_VARS['min_ticket_amt']):"");
			$goods_list= (isset($HTTP_POST_VARS['goods_list'])?quote_smart($HTTP_POST_VARS['goods_list']):"");
			$current_anti_fraud_system= (isset($HTTP_POST_VARS['current_anti_fraud_system'])?quote_smart($HTTP_POST_VARS['current_anti_fraud_system']):"");
			$customer_service_program= (isset($HTTP_POST_VARS['customer_service_program'])?quote_smart($HTTP_POST_VARS['customer_service_program']):"");
			$refund_policy= (isset($HTTP_POST_VARS['refund_policy'])?quote_smart($HTTP_POST_VARS['refund_policy']):"");
			$volume_last_month= (isset($HTTP_POST_VARS['volume_last_month'])?quote_smart($HTTP_POST_VARS['volume_last_month']):"");
			$volume_prev_30days= (isset($HTTP_POST_VARS['volume_prev_30days'])?quote_smart($HTTP_POST_VARS['volume_prev_30days']):"");
			$volume_prev_60days= (isset($HTTP_POST_VARS['volume_prev_60days'])?quote_smart($HTTP_POST_VARS['volume_prev_60days']):"");
			$totals= (isset($HTTP_POST_VARS['totals'])?quote_smart($HTTP_POST_VARS['totals']):"");
			$forecast_first_month= (isset($HTTP_POST_VARS['forecast_first_month'])?quote_smart($HTTP_POST_VARS['forecast_first_month']):"");
			$forecast_second_month= (isset($HTTP_POST_VARS['forecast_second_month'])?quote_smart($HTTP_POST_VARS['forecast_second_month']):"");
			$forecast_third_month= (isset($HTTP_POST_VARS['forecast_third_month'])?quote_smart($HTTP_POST_VARS['forecast_third_month']):"");
			$ReferenceNumber= (isset($HTTP_POST_VARS['ReferenceNumber'])?quote_smart($HTTP_POST_VARS['ReferenceNumber']):"");
			$cd_orderpage_settings= (isset($HTTP_POST_VARS['cd_orderpage_settings'])?quote_smart($HTTP_POST_VARS['cd_orderpage_settings']):"");
			$cd_approve_timelimit= (isset($HTTP_POST_VARS['cd_approve_timelimit'])?quote_smart($HTTP_POST_VARS['cd_approve_timelimit']):"");
			$cd_orderpage_useraccount= (isset($HTTP_POST_VARS['cd_orderpage_useraccount'])?quote_smart($HTTP_POST_VARS['cd_orderpage_useraccount']):"");
			$cd_fraudscore_limit= (isset($HTTP_POST_VARS['cd_fraudscore_limit'])?quote_smart($HTTP_POST_VARS['cd_fraudscore_limit']):"");

			if($max_ticket_amt == "")
				$max_ticket_amt = 0;
			if($min_ticket_amt == "")
				$min_ticket_amt = 0;

			$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
			$url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
			$url2 = (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
			$url3 = (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
			$url4 = (isset($HTTP_POST_VARS['url4'])?quote_smart($HTTP_POST_VARS['url4']):"");
			$url5 = (isset($HTTP_POST_VARS['url5'])?quote_smart($HTTP_POST_VARS['url5']):"");

			$qry_update_user  = " update cs_companydetails set first_name = '$first_name', family_name = '$family_name', ";
			$qry_update_user .= " job_title = '$job_title', contact_email = '$contact_email', contact_phone = '$contact_phone', how_about_us = '$how_about_us', ";
			$qry_update_user .= " stitle = '$sTitle',sdateofbirth='$sDateOfBirth',ssex='$sSex',sAddress='$sAddress',sPostCode='$sPostCode',sResidenceTelephone='$sResidenceTelephone',sFax='$sFax', ";
			$qry_update_user .=  "username='$username',password='$password',companyname='$companyname',";
			$qry_update_user .= " phonenumber='$phonenumber',address='$address', city='$city',state='$state',ostate='$ostate',";
			$qry_update_user .= " country='$country',zipcode='$zipcode',fax_number='$faxnumber',reseller_other='$how_about_us_other',";
			$qry_update_user .= "company_type = '$company_type', other_company_type = '$other_company_type', customer_service_phone = '$customerservice_phone', ";

			$qry_update_user .= "volumenumber = '$volume', avgticket = '$avgticket', chargebackper = '$chargeper', ";
			$qry_update_user .= "url1='$url1', cd_processing_reason='$cd_processing_reason',cd_previous_processor='$cd_previous_processor', preprocess = '$prepro', recurbilling = '$rebill', currprocessing = '$currpro', ";
			$qry_update_user .= "auto_cancel='$strAutoCancel',time_frame=$iTimeFrame,auto_approve='$strAutoApprove',transaction_type='$transaction_type',shipping_cancel='$strShippingCancel',shipping_timeframe=$iShippingTimeFrame,";
			$qry_update_user .= "setupfees=$setupFees,";

			$qry_update_user .= "legal_name = '$legal_companyname', incorporated_country = '$inc_country', incorporated_number = '$inc_number', fax_dba = '$fax_dba', physical_address = '$physical_address', ";
			$qry_update_user .= "cellular = '$cellular', technical_contact_details = '$tech_contact_details', admin_contact_details = '$admin_contact_details', max_ticket_amt = '$max_ticket_amt', min_ticket_amt = '$min_ticket_amt', ";
			$qry_update_user .= "goods_list = '$goods_list', volume_last_month = '$volume_last_month', volume_prev_30days = '$volume_prev_30days', volume_prev_60days = '$volume_prev_60days', totals = '$totals', ";
			$qry_update_user .= "forecast_volume_1month = '$forecast_first_month', forecast_volume_2month = '$forecast_second_month', forecast_volume_3month = '$forecast_third_month', ";
			$qry_update_user .= "current_anti_fraud_system = '$current_anti_fraud_system', customer_service_program = '$customer_service_program', refund_policy = '$refund_policy', ";
			$qry_update_user .= " email = '$email'";
			$qry_update_user .= "  where userId='$userid' $bank_sql_limit";

			//print($qry_update_user);
			//if ($adminInfo['li_level'] == 'full' || $resellerInfo['isMasterMerchant']) 
			sql_query_write($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>$qry_update_user");

			$cd_notes= (isset($HTTP_POST_VARS['cd_notes'])?"'".quote_smart($HTTP_POST_VARS['cd_notes'])."'":"NULL");
			if($cd_notes) sql_query_write("update cs_companydetails set cd_notes = $cd_notes where userId='$userid'") or dieLog(mysql_errno().": ".mysql_error());
		}
		}
	$company_id = isset($HTTP_GET_VARS['company_id'])?$HTTP_GET_VARS['company_id']:"";
	if ($company_id == "") {
		$company_id = (isset($HTTP_POST_VARS['userid'])?quote_smart($HTTP_POST_VARS['userid']):"");
	}
	if ($company_id == "") {
		$company_id = (isset($_REQUEST['userIdList'])?quote_smart($_REQUEST['userIdList']):"");
	}
	if ($company_id == "") {
		$company_id = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
	}
	$companyname = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
	$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
	$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"";

	$script_display ="";
	$qry_select_companies = "select * from cs_companydetails where userid='$company_id' $bank_sql_limit";
	if($qry_select_companies != "")
	{
		$show_sql =sql_query_read($qry_select_companies) or dieLog(mysql_error()." ~ $qry_select_companies");

	}

	if($companyInfo = mysql_fetch_array($show_sql))
	{


		if($companyInfo[7]=="")
		{
			$state=str_replace("\n",",\t",$companyInfo[12]);
		}
		else
		{
			$state=str_replace("\n",",\t",$companyInfo[7]);
		}
		if($companyInfo[27] == "tele") {
			$script_display ="yes";
			$sendecommerce_diplay = "none";
		}else {
			$script_display ="none";
			$sendecommerce_diplay = "yes";
		}
		if($companyInfo[84] == 1) {
			$sendecommerce_checked = "checked";
		}else {
			$sendecommerce_checked = "";
		}
 ?>
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">

function validator(){
	if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
		document.Frmcompany.ostate.disabled= true;
		document.Frmcompany.ostate.value= "";
		document.Frmcompany.state.disabled = false;
	} else {
		document.Frmcompany.state.disabled = true;
		document.Frmcompany.state.value= "";
		document.Frmcompany.ostate.disabled= false;
	}

	return false;
}

function SelectMerchanttype() {
	if(document.Frmcompany.how_about_us.value=='other') {
		document.Frmcompany.how_about_us_other.disabled=false;
	}else {
		document.Frmcompany.how_about_us_other.value="";
		document.Frmcompany.how_about_us_other.disabled=true;
	}
	if(document.Frmcompany.how_about_us.value =="rsel" ){
		document.Frmcompany.reseller_other.disabled=false;
	} else {
		document.Frmcompany.reseller_other.value="";
		document.Frmcompany.reseller_other.disabled=true;
	}
}
function displayverification(){
  if(document.Frmcompany.rad_trans_type.options[document.Frmcompany.rad_trans_type.selectedIndex].value=="tele") {
	 	document.getElementById('auto_cancel').style.display = "";
	}else {
	 	document.getElementById('auto_cancel').style.display = "none";
	}
	return false;
}
function  validateForm(){
trimSpace(document.Frmcompany.username);
	if(document.Frmcompany.username.value!="" &&(!func_vali_pass(document.Frmcompany.username)))
  	{
  		alert ("Special characters not allowed");
  		document.Frmcompany.username.focus();
  		document.Frmcompany.username.select();
  		return false;
  	}
trimSpace(document.Frmcompany.password);
	if(document.Frmcompany.password.value!="" &&(!func_vali_pass(document.Frmcompany.password)))
  	{
  		alert ("Special characters not allowed");
  		document.Frmcompany.password.focus();
  		document.Frmcompany.password.select();
  		return false;
  	}
}
function func_vali_pass(frmelement)
{
 var invalid="!`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
 var inp=frmelement.value;
 var b_flag=true;
for(var i=0;((i<inp.length)&&b_flag);i++)
{
var temp= inp.charAt(i);
var j=invalid.indexOf(temp);
if(j!=-1)
{

b_flag =false;
return false;
}
}

if (b_flag==true)return true;

}


function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}
</script>
<?php
beginTable();
?>
<form action="editCompanyProfile1.php"  name="Frmcompany" method="post" onSubmit="return validateForm()">
	<table style="margin-top:10" align="center">
	<tr>
	<td align="center">
	<IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
	<a href="editCompanyProfile2.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<a href="editCompanyProfile3.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>

	<a href="editCompanyProfile5.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
	<!--<a href="completeAccounting.php?company_id=<?= $company_id?>&script_display=<?= $script_display?>"><IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>-->
	</td>
	</tr>
	<?php
	$status = $etel_completion_array[intval($companyInfo['cd_completion'])]['txt'];
	$bold = $etel_completion_array[intval($companyInfo['cd_completion'])]['style'];
?>
<?php if(1){// $adminInfo['li_level'] == 'full') { ?>
            <tr align="center" valign="middle">
              <td height="30"align="center">
              <span style="font-size:12px; font-weight:<?=$bold?> "><?=ucfirst($companyInfo['companyname'])?></span> - <span style="font-size:10px; font-weight:<?=$bold?> ">
                <?=$status?>
              </span></td>
              </tr>
	<? } ?>
	</table>

		                <div align="center" style="font-size: 10px">
                <input type="hidden" name="userid" value="<?=$companyInfo['userId']?>">
                </input>
                <input type="hidden" name="update" value="yes">
                </input>

</div>
		<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
		<tr>
		<td align="center" width="50%" valign="top">

		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">
		<tr>
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">
						<?php if(1){// $adminInfo['li_level'] == 'full' || 1) { ?>
						<a href="<?="?username=".$companyInfo['username']."&password=".$companyInfo['password']."&gw_id=".$_SESSION['gw_id']."&company_id=".$companyInfo['userId']?>&loginas=1">Login as
                        <?= $companyInfo['companyname'] ?>

                        </a>
						<?php } ?>
						</td>
					  </tr>
						<tr height='30'>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>
                          &nbsp;Company Name</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'>
                          &nbsp;<input type="text" name="companyname" class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['companyname'])?>">
                        </td>
                      </tr>
						<tr height='30'>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>
                          &nbsp;Legal Company Name</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'>
                          &nbsp;<input type="text" name="legal_companyname" class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['legal_name'])?>">
                        </td>
                      </tr>
						<tr height='30'>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>
                          &nbsp;Company Website</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'>
                          &nbsp;<input type="text" name="url1" class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['url1'])?>">
                        </td>
                      </tr>
						<tr height='30'>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>
                          &nbsp;Company Reference Number</b></font></td>
                        <td height="30" align='left'  width="250" class='cl1'>
                          &nbsp;<input name="ReferenceNumber" type="text" class="normaltext" id="ReferenceNumber" style="width:200px" value="<?=htmlentities($companyInfo['ReferenceNumber'])?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Type
                          Of Company</strong> &nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="company_type"  style="font-family:arial;font-size:10px;width:160px" >
						<option value="">--Choose one --</option>
						<option value="part" <?= $companyInfo['company_type'] == "part" ? "selected" : ""?>>Limited Partnership</option>
						<option value="ltd" <?= $companyInfo['company_type'] == "ltd" ? "selected" : ""?>>Limited Liability Company</option>
						<option value="corp" <?= $companyInfo['company_type'] == "corp" ? "selected" : ""?>>Corporation</option>
						<option value="sole" <?= $companyInfo['company_type'] == "sole" ? "selected" : ""?>>Sole Proprietor</option>
						<option value="other" <?= $companyInfo['company_type'] == "other" ? "selected" : ""?>>Other</option>
						</select>
						</td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;If
                          'Other', please specify:</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="other_company_type" class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['other_company_type'])?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;User
                          Name</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="username" class="normaltext" style="width:200px" value="<?=$companyInfo['username']?>">
                        </td>
                      </tr>
<?php if(1){// $adminInfo['li_level'] == 'full') { ?>
                      <tr>
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Password</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="password" class="normaltext" style="width:200px" value="<?=$companyInfo['password']?>">
                        </td>
                      </tr>
					  <?php } ?>
                      <tr>
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Confirm
                          Password</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="password1" class="normaltext" style="width:200px" value="<?=$companyInfo['password']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Address</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="address" class="normaltext" style="width:200px" value="<?=htmlentities(str_replace("\n",",\t",$companyInfo['address']));?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;City</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="city" class="normaltext" style="width:200px" value="<?=htmlentities(str_replace("\n",",\t",$companyInfo['city']));?>">
                        </td>
                      </tr>

                      <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Country</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<select name="country"  style="font-family:arial;font-size:10px;width:150px" onchange="return validator()">
							<?=func_get_country_select($str_country,1) ?>
						  </select>
						<script language="javascript">
							 document.Frmcompany.country.value='<?=$companyInfo['country']?>';
						</script>
						</td>
                      </tr>
					  <tr>
                        <td height="30" align='left' class='cl1'><font face='verdana' size='1'><b>&nbsp;State</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<select name="state"  style="font-family:arial;font-size:10px;width:150px">
							<?=func_get_state_select($companyInfo['state']) ?>
						  </select>
					    </td>
                      </tr>
					  <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Other
                          State</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="ostate"  class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['ostate'])?>">
                          </input></td>
                      </tr>
						<script language="javascript">
						if(document.Frmcompany.country.value !="" ) {
							if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
								document.Frmcompany.ostate.disabled= true;
								document.Frmcompany.ostate.value= "";
								document.Frmcompany.state.disabled = false;
							} else {
								document.Frmcompany.state.disabled = true;
								document.Frmcompany.state.value= "";
								document.Frmcompany.ostate.disabled= false;
							}
						} else {
							document.Frmcompany.country.value = "United States";
						}
						</script>
                      <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Incorporated Country</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<select name="inc_country"  style="font-family:arial;font-size:10px;width:150px">
							<?=func_get_country_select($resellerInfo[31],1) ?>
								</select>

						</td>
                      </tr>
                       <tr>
                        <td height="30"  align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Incorporated Number</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="inc_number" class="normaltext" style="width:200px" value="<?=$companyInfo['incorporated_number']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Zipcode</b></font></td>
                        <td height="30" align='left'  class='cl1'>
                          &nbsp;<input type="text" name="zipcode" class="normaltext" style="width:200px" value="<?=str_replace("\n",",\t",$companyInfo[zipcode]);?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" align='left'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Physical Company Address</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<textarea name="physical_address" class="normaltext" style="width:200px"><?= htmlentities($companyInfo['physical_address'])?></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Fax
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="faxnumber" class="normaltext" style="width:200px" value="<?=$companyInfo['fax_number']?>">
                        </td>
                      </tr>
                      <tr>
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Fax DBA</b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="fax_dba" class="normaltext" style="width:200px" value="<?=$companyInfo['fax_dba']?>">
                        </td>
                      </tr>
						<tr>
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Phone
                          Number</b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="phonenumber" class="normaltext" style="width:200px" value="<?=$companyInfo['phonenumber']?>">
                        </td>
                      </tr>
						<tr>
                        <td height="30"  align='left'  class='cl1' ><font face='verdana' size='1'><b>&nbsp;Cellular
                          </b></font></td>
                        <td height="30" align='left'  class='cl1' >
                          &nbsp;<input type="text" maxlength="25" name="cellular" class="normaltext" style="width:200px" value="<?=$companyInfo['cellular']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Customer
                          services phone number</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="customerservice_phone" class="normaltext" style="width:200px" value="<?=$companyInfo['customer_service_phone']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Maximum Ticket Amount</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="max_ticket_amt" class="normaltext" style="width:200px" value="<?=$companyInfo['max_ticket_amt']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Minimum Ticket Amount</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="min_ticket_amt" class="normaltext" style="width:200px" value="<?=$companyInfo['min_ticket_amt']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Technical Contact Details</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="tech_contact_details" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['technical_contact_details'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Administrative Contact Details</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="admin_contact_details" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['admin_contact_details'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Goods/Services list and description</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="goods_list" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['goods_list'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Explain Currently used anti fraud<br> &nbsp;system</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="current_anti_fraud_system" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['current_anti_fraud_system'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Explain in detail your customer service<br> &nbsp;program</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="customer_service_program" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['customer_service_program'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Describe your refund policy</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="refund_policy" class="normaltext" style="width:200px"><?=htmlentities($companyInfo['refund_policy'])?></textarea>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="right"  class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#ffffff"><strong>&nbsp;Previous Sales Volume</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;

                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Last Month</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="volume_last_month" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['volume_last_month']); ?>
						  </select>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;30 days previous</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="volume_prev_30days" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['volume_prev_30days']); ?>
						  </select>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;60 days previous</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="volume_prev_60days" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['volume_prev_60days']); ?>
						  </select>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Totals</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="totals" class="normaltext" style="width:200px" value="<?=$companyInfo['totals']?>">
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="right"  class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#ffffff"><strong>&nbsp;Forecasted volume with <?=$_SESSION['gw_title']?></strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >&nbsp;

                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;First Month</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="forecast_first_month" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['forecast_volume_1month']); ?>
						  </select>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Second Month</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="forecast_second_month" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['forecast_volume_2month']); ?>
						  </select>
                        </td>
                      </tr>
					  <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Third Month</strong>&nbsp;&nbsp;</font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="forecast_third_month" style="font-family:arial;font-size:10px;width:120px">
							<?php func_select_merchant_volume($companyInfo['forecast_volume_3month']); ?>
						  </select>
                        </td>
                      </tr>
				    </table>
		  <br>
		</td>
		<td align="center" width="50%" valign="top">
		<table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="center">


					<tr>
                        <td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>User
                          Informations </strong>&nbsp;</font></td>
                        <td height="30" class='cl1' align="left">&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Notes for this Merchant </strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="cd_notes" cols="30" rows="6" class="normaltext" id="cd_notes" style="width:200px"><?=htmlentities($companyInfo['cd_notes'])?></textarea>                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">
                          <strong>&nbsp;Email</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="email" class="normaltext" style="width:215px" value="<?=$companyInfo['email']?>">                        </td>
					  </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">
                          <strong>&nbsp;Your First Name</strong></font></td>
                        <td height="30" align="left"   class='cl1' >
                          &nbsp;<select name="cboTitle" style="font-family:verdana;font-size:10px;width:50px">
						<?php
							funcFillComboWithTitle ( $companyInfo['stitle'] );
						?>
						</select>&nbsp;
						&nbsp;<input type="text" name="first_name" class="normaltext" style="width:100px" value="<?=htmlentities($companyInfo['first_name'])?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">
                          <strong>&nbsp;Your Last Name</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="family_name" class="normaltext" style="width:158px" value="<?=htmlentities($companyInfo['family_name'])?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Date
                          of birth</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          <?php
							$iYear = "";
							$iMonth = "";
							$iDay = "";
							if ($companyInfo['sdateofbirth'] !=""){
								list($iYear,$iMonth,$iDay) = split("-",$companyInfo['sdateofbirth']);
							}
							print("&nbsp;");
							funcFillDate ( $iDay,$iMonth,$iYear );
						?>                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Sex</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="cboSex" style="font-family:verdana;font-size:10px;width:70px">
						<option value='Male' <?= $companyInfo['ssex'] == "Male" ? "selected" : ""?>>Male</option>
						<option value='Female' <?= $companyInfo['ssex'] == "Female" ? "selected" : ""?>>Female</option>
						</select>                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<textarea name="txtAddress" class="normaltext" style="width:200px" rows="4" cols="30"><?=htmlentities($companyInfo['saddress'])?></textarea>                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Zipcode</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="txtPostCode"  maxlength="7" class="normaltext" style="width:200px" value="<?=$companyInfo['spostcode']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1">
                          <strong>&nbsp;What is your job title or position?</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="job_title" class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['job_title'])?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Contact
                          Instant Messenger</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="cd_contact_im" class="normaltext" style="width:200px" value="<?=$companyInfo['cd_contact_im']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Contact
                          email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="contact_email" class="normaltext" style="width:200px" value="<?=$companyInfo['contact_email']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"   class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Please
                          confirm email address</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="confirm_contact_email" class="normaltext" style="width:200px" value="<?=$companyInfo['contact_email']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Telephone
                          number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="contact_phone" class="normaltext" style="width:200px" value="<?=$companyInfo['contact_phone']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Residence
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="residence_telephone" class="normaltext" style="width:200px" value="<?=$companyInfo['sresidencetelephone']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Fax
                          Number</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="fax" class="normaltext" style="width:200px" value="<?=$companyInfo['sfax']?>">                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;Where
                          did you hear about <?=$_SESSION['gw_title']?></strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<select name="how_about_us" style="font-family:verdana;font-size:10px;width:120px" onchange="SelectMerchanttype();">
							<?= func_fill_info_source_combo(1, $companyInfo['how_about_us']) ?>
						</select>						</td>
                      </tr>
						<tr>
                        <td height="30" align="left"  class='cl1' ><font face="verdana" size="1"><strong>&nbsp;If others</strong></font></td>
                        <td height="30" align="left"  class='cl1' >
                          &nbsp;<input type="text" name="how_about_us_other"  class="normaltext" style="width:200px" value="<?=htmlentities($companyInfo['reseller_other'])?>">		                 </td>
                      </tr>
			<tr>
			<td height="30" align="right" valign="center" class='cl1' bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Process
			  Informations </strong>&nbsp;</font></td>
			<td height="30" class='cl1' align="left">&nbsp;</td>
		  </tr>
		  <tr>
			<td height="30" class='cl1'><strong><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Merchant
			  Type </font></strong></td>
			<td height="30" class='cl1'>
			  &nbsp;<select name="rad_trans_type" style="font-family:arial;font-size:10px;width:100px" onChange="displayverification();">
<?php						print func_select_merchant_type($companyInfo['transaction_type']); ?>
			  </select>			  </td>
		  </tr>
		<tr>
			<td width="100" colspan="2"> <div id="auto_cancel" style="display:<?=$script_display?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		  <tr>
			 <td width="206" height="30" align="left" valign="center" class='cl1'><strong><font face="verdana" size="1">&nbsp;Customer
			 Service Cancel(auto)</font></strong></td>
			<td align="left" height="25" class='cl1' width="217"><input name="chk_auto_cancel" type="checkbox" value="Y"  <?=$companyInfo['auto_cancel'] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;&nbsp;<input type="text" name="time_frame" class="normaltext" size="2" value="<?=$companyInfo[25] == -1 ? "" : $companyInfo[25]?>" style="font-family:arial;font-size:10px">			</td>
		  </tr>
		  <tr>
	  <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Shipping
		Cancel(auto)</font></strong></td>
		<td align="left" height="30" width="217" class='cl1'><input name="chk_shipping_cancel" type="checkbox" value="Y" <?=$companyInfo['shipping_cancel'] == "Y" ? "checked" : ""?>>&nbsp;<font face="verdana" size="1">Timeframe in Days</font>&nbsp;&nbsp;<input type="text" name="shipping_time_frame" size="2" value="<?=$companyInfo[32] == -1 ? "" : $companyInfo[32]?>" style="font-family:arial;font-size:10px">		</td>
		  </tr>
		  <tr>
				  <td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Auto
					Approve Pass Orders&nbsp;</font></strong></td>
			<td align="left" height="25" class='cl1'><input name="chk_auto_approve" type="checkbox" value="Y" <?=$companyInfo['auto_approve'] == "Y" ? "checked" : ""?>>			</td>
		  </tr>
		  </table></div>		  </td>
		  </tr>
		 <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Expected
			  Monthly Volume ($)&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			  &nbsp;<select name="volume" style="font-family:arial;font-size:10px;width:120px">
				<?php func_select_merchant_volume($companyInfo['volumenumber']); ?>
			  </select>			</td>
		  </tr>
		  <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Average
			  Ticket&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			  &nbsp;<input type="text" name="avgticket" class="normaltext" style="width:100px" value="<?=$companyInfo['avgticket']?>">			</td>
		  </tr>
		  <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Charge
			  Back %&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			  &nbsp;<input type="text" name="chargeper" class="normaltext" style="width:100px" value="<?=$companyInfo['chargebackper']?>">			</td>
		  </tr>
		  <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Previous
			  Processing&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'><input name="prepro" type="checkbox" value="Yes" <?=$companyInfo['preprocess'] == "Yes" ? "checked" : ""?>>			</td>
		  </tr>

				<tr>
					<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1"> &nbsp; If Previous Processing, who? &nbsp;&nbsp;</font></strong></td>
					<td align="left" height="30" class='cl1'>&nbsp;<input name="cd_previous_processor" type="text" id="cd_previous_processor" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_processor'])?>" maxlength="100">                  </td>
                </tr>
					<tr>
					<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1"> &nbsp; If Previous Processing, why did you leave? or why do you need new or additional processing? &nbsp;&nbsp;</font></strong></td>
					<td align="left" height="30" class='cl1'>&nbsp;<textarea name="cd_processing_reason" id="cd_processing_reason" style="font-family:arial;font-size:10px;width:150px"><?=htmlentities($companyInfo['cd_processing_reason'])?></textarea>                  </td>
                </tr>
				<tr>
					<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1"> &nbsp; Previous Discount Rate &nbsp;&nbsp;</font></strong></td>
					<td align="left" height="30" class='cl1'>&nbsp;<input name="cd_previous_processor" type="text" id="cd_previous_processor" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_discount'])?>" maxlength="100">                  </td>
                </tr>
				<tr>
					<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1"> &nbsp; Previous Transaction Fee &nbsp;&nbsp;</font></strong></td>
					<td align="left" height="30" class='cl1'>&nbsp;<input name="cd_previous_processor" type="text" id="cd_previous_processor" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($companyInfo['cd_previous_transaction_fee'])?>" maxlength="100">                  </td>
                </tr>
		  <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Recurring
			  Billing&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			 <input name="rebill" type="checkbox" value="Yes" <?=$companyInfo['recurbilling'] == "Yes" ? "checked" : ""?>>			</td>
		  </tr>
		  <tr>
			<td align="left" valign="center" height="30" class='cl1'><strong><font face="verdana" size="1">&nbsp;Currently
			  Processing&nbsp;</font></strong></td>
			<td align="left" height="30" class='cl1'>
			  <input name="currpro" type="checkbox" value="Yes" <?=$companyInfo['currprocessing'] == "Yes" ? "checked" : ""?>>			</td>
		  </tr>
			<!--  Bank details integrating starts -->
			<?php
				$qrySelect = "select * from cs_bank_company where company_id =  '$company_id' $bank_sql_limit";
				$rstSelect = sql_query_read($qrySelect,1);
				$iCheckBankId = "";
				$iCreditBankId = "";
				if ( mysql_num_rows($rstSelect) > 0 ) {
					$iCheckBankId = mysql_result($rstSelect,0,2);
					$iCreditBankId = mysql_result($rstSelect,0,3);
				}

			?>
		  </table>
		</td></tr></table>
		<center>
		<table align="center">
		<tr><td align="center" valign="center" height="30" colspan="2" ><a href="viewCompany.php"><img  SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input></td></tr>
		</table>
		</center>

        </form>
			<?php
			endTable("Edit Company Information","");
			?>
<script>
if(document.Frmcompany.how_about_us.value =="other" ){
	document.Frmcompany.how_about_us_other.disabled=false;
} else {
	document.Frmcompany.how_about_us_other.value="";
	document.Frmcompany.how_about_us_other.disabled=true;
}
if(document.Frmcompany.how_about_us.value =="rsel" ){
	document.Frmcompany.reseller_other.disabled=false;
} else {
	document.Frmcompany.reseller_other.value="";
	document.Frmcompany.reseller_other.disabled=true;
}
</script>

<?php
}
else
echo "Not Found";
include("includes/footer.php");
?>