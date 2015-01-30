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
//viewGatewayreportpage.php:	The admin page functions for displaying the company transactions. 
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

if($sessionAdmin!="")
{
$cancel = (isset($HTTP_POST_VARS['cancel'])?quote_smart($HTTP_POST_VARS['cancel']):"");
$cancelreason = (isset($HTTP_POST_VARS['cancelreason'])?quote_smart($HTTP_POST_VARS['cancelreason']):"");
$id = (isset($HTTP_POST_VARS['id'])?quote_smart($HTTP_POST_VARS['id']):"");
$crorcq1 = (isset($HTTP_POST_VARS["crorcq1"])?quote_smart($HTTP_POST_VARS["crorcq1"]):"");
$canceldate = func_get_current_date_time(); 
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
					//print($qryUpdate."<br>");
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
		else 
		{
		  $qrt_select_details ="select b.companyname,a.name,a.surname,a.transactionDate,a.checkorcard,a.CCnumber,a.checkto,a.amount,a.status,a.bankaccountnumber,a.bankroutingcode,a.phonenumber,a.address,a.country,a.city,a.state,a.zipcode,a.memodet,a.signature,a.email,a.transactionId,a.cardtype,a.validupto,a.reason,a.other,a.cvv,a.misc,a.Invoiceid,a.checktype,a.bankname,a.accounttype,a.ipaddress,a.cancelstatus,a.voiceAuthorizationno,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.chequedate,a.billingDate,a.licensestate,a.userid,a.productdescription FROM cs_transactiondetails as a INNER JOIN cs_companydetails as b ON a.userid=b.userid where a.transactionId=$id";
		   
		//   print $qrt_select_details;
		   if(!($show_select_sql =mysql_query($qrt_select_details,$cnn_cs)))
		   {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		   }
		   
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
$declineReasons=(isset($HTTP_POST_VARS['declineReasons'])?($HTTP_POST_VARS['declineReasons']):"");
$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
$cancelReasons=(isset($HTTP_POST_VARS['cancelReasons'])?($HTTP_POST_VARS['cancelReasons']):"");
$iCount = (isset($HTTP_POST_VARS["hdCount"])?quote_smart($HTTP_POST_VARS["hdCount"]):"");
$i_lower_limit = (isset($HTTP_POST_VARS["lower_limit"])?quote_smart($HTTP_POST_VARS["lower_limit"]):"0");
$i_num_records_per_page = (isset($HTTP_POST_VARS["cbo_num_records"])?quote_smart($HTTP_POST_VARS["cbo_num_records"]):"20");
$gatewayAdminId = isset($HTTP_POST_VARS['gatewayCompanies'])?$HTTP_POST_VARS['gatewayCompanies']:"";
$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$companyids = isset($HTTP_POST_VARS['companyids'])?$HTTP_POST_VARS['companyids']:"";
?>
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">
function showDetails(the_sub){
	if(the_sub =="div1" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv1.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv1.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	} else if(the_sub =="div2" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv2.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv2.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
    } else if(the_sub =="div3" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv3.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv3.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	}
}

function cancelvalidation() {
	var recur_mode = "";
	for(i=0;i<document.view.recurdatemode.length;i++)
	{
		if(document.view.recurdatemode[i].checked)
		{
			recur_mode = document.view.recurdatemode[i].value;
			break;
		}
	}
	if(document.view.firstname1.value==""){
		alert("Please enter the First name")
		document.view.firstname1.focus();
		return false;
   } 
	 
	 if(document.view.lastname1.value==""){
		alert("Please enter the Last name")
		document.view.lastname1.focus();
		return false;
	  } 
	  
	  if(document.view.address.value==""){
		alert("Please enter address")
		document.view.address.focus();
		return false;
	  }
	  
	  if(document.view.city.value==""){
		alert("Please enter city")
		document.view.city.focus();
		return false;
	  } 
	  if(document.view.country.selectedIndex==0){
		alert("Please enter country")
		document.view.country.focus();
		return false;
	  }
	  if(document.view.zip.value==""){
		alert("Please enter zip code")
		document.view.zip.focus();
		return false;
	  }

	  if(document.view.country.value == "United States") {
	  if(document.view.zip.value.length!=5 && document.view.zip.value.length!=9){
		alert("Please enter the correct zip code")
		document.view.zip.focus();
		return false;
	  }
	  if(isNaN(document.view.zip.value)){
			alert("Please enter numeric values");
			document.view.zip.focus();
			return false;
		  }
	 }
	  if(document.view.phonenumber.value==""){
		alert("Please enter phone #")
		document.view.phonenumber.focus();
		return false;
	  }
	  if(isNaN(document.view.phonenumber.value)){
		alert("Please enter numeric values")
		document.view.phonenumber.focus();
		return false;
	  }
	if(document.view.amount.value==""){
		alert("Please enter amount")
		document.view.amount.focus();
		return false;
	} 
	if(!(checkAllowedChars(document.view.amount.value,'D'))) {
		alert("Please enter numeric values");
		document.view.amount.focus();
		return false;
	}
	if(document.view.amount.value.indexOf(".")<=0){
	     document.view.amount.value = document.view.amount.value + ".00";
	} 
	if(document.view.chk_recur_date.checked)
	{
		if(recur_mode == "")
		{
			alert("Please select a recurring mode.")
			document.view.recurdatemode[0].focus();
			return false;

		}
		else if(recur_mode == "D"){
			if(document.view.recur_day.value == "")
			{
				alert("Please enter the recurring days.")
				document.view.recur_day.focus();
				return false;
			}
			else if(isNaN(document.view.recur_day.value))
			{
				alert("Please enter numeric values.")
				document.view.recur_day.focus();
				return false;
			}
		}
		if(document.view.recur_charge.value != "")
		{
			if(isNaN(document.view.recur_charge.value)){
				alert("Please enter numeric values");
				document.view.recur_charge.focus();
				return false;
			}
		}
		if(document.view.recur_times.value==""){
			alert("Please enter no: of rebillings")
			document.view.recur_times.focus();
			return false;
		}
		if(isNaN(document.view.recur_times.value)){
			alert("Please enter numeric values");
			document.view.recur_times.focus();
			return false;
		}
	}
	else{
		if(document.view.cancelreason.selectedIndex==0){
			document.view.cancelreason.value="";
		}
		return true;
	}

}

function func_submit()
{
	obj_form = document.view;
	obj_form.method="post";
	obj_form.action="viewGatewayTransactions.php";
	obj_form.submit();
}
</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
 <table border="0" cellpadding="0" width="800" cellspacing="0" align="center">
  <tr>
    <td width="100%" valign="top" align="center">
	<form name="view" action="updategatewayreportpage.php" method="post" onsubmit="return cancelvalidation()">
	<input type="hidden" name="id" value="<?=$id?>"></input>
	<input type="hidden" name="statusdiv1" value="">
	<input type="hidden" name="statusdiv2" value="">
	<input type="hidden" name="statusdiv3" value="">
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
<? 

while($show_select_val = mysql_fetch_array($show_select_sql)) 
{
   $cancel_status = $show_select_val[32];
   $str_bill_date = $show_select_val[38]; 
   $trans_recur_mode = $show_select_val[40];
   $trans_recur_day = $show_select_val[41];
   $trans_recur_week = $show_select_val[42];
   $trans_recur_month = $show_select_val[43];
   $trans_recur_start_date = $show_select_val[44];
   $i_to_month = substr($trans_recur_start_date,5,2);
   $i_to_day = substr($trans_recur_start_date,8,2);
   $i_to_year = substr($trans_recur_start_date,0,4);
   $trans_recur_charge = $show_select_val[45];
   $trans_recur_times = $show_select_val[46];
   $userid = $show_select_val[47];
	if($trans_recur_mode !="") {
		$set_recurring ="checked";
	}
	if($trans_recur_mode =="D"){
		$datevalue=  $trans_recur_day;
		$dayval = "Checked";
	}elseif($trans_recur_mode =="W") {
		$weekvalue= $trans_recur_week; 
		$weekval = "Checked";
	}elseif($trans_recur_mode =="M") {
		$monthvalue=  $trans_recur_day;
		$monthval = "Checked";
	}elseif($trans_recur_mode =="Y") {
		$yearmonthvalue=  $trans_recur_month;
		$yeardayvalue = $trans_recur_day;
		$yearval = "Checked";
	}
?>
<input type="hidden" name="crorcq1" value="<?=$show_select_val[4]?>">
<input type="hidden" name="domain1" value="<?=$show_select_val[31]?>" >
<input type="hidden" name="userid" value="<?=$userid?>" >
<?	
 if($show_select_val[4]=="H")
 {
 ?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" align="center">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp; 
                    Card&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
			<table border="0" cellpadding="0" cellspacing="0" width="750" height="544" align="center" >
         	 <tr>
                      <td width="100%" height="494" valign="top" align="left">
					  <table width="691" height="165"  align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
				<tr> 
                  <td height="11" valign="top" align="left" width="19">&nbsp;</td>
                  <td valign="top" align="left" width="652"  height="11"><img border="0" src="images/cbg.jpg" width="1" height="2"></td>
                  <td height="11" valign="top" align="left" width="28">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="167" valign="top" align="left" width="19">&nbsp;</td>
                  <td height="167" valign="top" align="left" width="100%" > 
                    <table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid black">
						
                      <tr align="center" valign="middle" bgcolor="#CCCCCC"> 
                        <td colspan="2" class="tdbdr"><span class="subhd"><strong>Customer 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td width="47%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
                          Name : </font></td>
					  <td width="53%" valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="firstname1" size="19" maxlength="75" value="<?=$show_select_val[1]?>" >
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
                          Name :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="lastname1" size="19" maxlength="75" value="<?=$show_select_val[2]?>" >
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: 
                          </font><br></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="address"size="45" maxlength="100" value="<?=$show_select_val[12]?>" >
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City 
                          :</font></td>
				  <td valign="middle" class="tdbdr"><font color="#001188"> 
					&nbsp; 
					<input type="text" name="city"  size="35" maxlength="50" value="<?=$show_select_val[14]?>" >
					</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country 
                          :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
					<!--	<input type="text" name="country2" size="20" value="<?=$show_select_val[13]?>" > -->
					<select name="country"  style="font-family:arial;font-size:11px;width:200px"> 
					<option value="Afghanistan">Afghanistan </option>
					<option value="Albania">Albania </option>
					<option value="Algeria">Algeria </option>
					<option value="Andorra">Andorra </option>
					<option value="Angola">Angola</option>
					<option value="Antigua and Barbuda">Antigua and Barbuda </option>
					<option value="Argentina">Argentina </option>
					<option value="Armenia">Armenia </option>
					<option value="Australia">Australia </option>
					<option value="Austria">Austria</option>
					<option value="Azerbaijan">Azerbaijan </option>
					<option value="Bahamas">Bahamas</option>
					<option value="Bahrain">Bahrain </option>
					<option value="Bangladesh">Bangladesh </option>
					<option value="Barbados">Barbados </option>
					<option value="Belarus">Belarus </option>
					<option value="Belgium">Belgium </option>
					<option value="Belize">Belize </option>
					<option value="Benin">Benin </option>
					<option value="Bhutan">Bhutan </option>
					<option value="Bolivia">Bolivia </option>
					<option value="Bosnia">Bosnia</option>
					<option value="Botswana">Botswana </option>
					<option value="Brazil">Brazil </option>
					<option value="Brunei">Brunei </option>
					<option value="Bulgaria">Bulgaria </option>
					<option value="Burkina Faso">Burkina Faso </option>
					<option value="Burundi">Burundi
					<option value="Cameroon">Cameroon </option>
					<option value="Canada">Canada </option>
					<option value="Cape Verde">Cape Verde </option>
					<option value="Central African">Central African </option>
					<option value="Chad">Chad </option>
					<option value="Chile">Chile </option>
					<option value="China">China </option>
					<option value="Colombia">Colombia </option>
					<option value="Comoros">Comoros</option>
					<option value="Congo">Congo </option>
					<option value="Costa Rica">Costa Rica   </option>
					<option value="Croatia">Croatia </option>
					<option value="Cuba">Cuba </option>
					<option value="Cyprus">Cyprus  </option>
					<option value="Czech Republic">Czech Republic </option>
					<option value="Côte d'Ivoire">Côte d'Ivoire </option>
					<option value="Denmark">Denmark</option>
					<option value="Djibouti">Djibouti</option>
					<option value="Dominica">Dominica</option>
					<option value="Dominican Republic">Dominican Republic </option>
					<option value="East Timor">East Timor</option>
					<option value="Ecuador">Ecuador</option>
					<option value="Egypt">Egypt </option>
					<option value="El Salvador">El Salvador</option>
					<option value="Equatorial Guinea">Equatorial Guinea</option>
					<option value="Eritrea">Eritrea</option>
					<option value="Estonia">Estonia </option>
					<option value="Ethiopia">Ethiopia </option>
					<option value="Fiji">Fiji </option>
					<option value="Finland">Finland </option>
					<option value="France">France </option>
					<option value="Gabon">Gabon </option>
					<option value="Gambia">Gambia</option>
					<option value="Georgia">Georgia</option>
					<option value="Germany">Germany </option>
					<option value="Ghana">Ghana </option>
					<option value="Greece">Greece  </option>
					<option value="Grenada">Grenada </option>
					<option value="Guatemala">Guatemala </option>
					<option value="Guinea">Guinea </option>
					<option value="Guyana">Guyana </option>
					<option value="Haiti">Haiti</option>
					<option value="Honduras">Honduras </option>
					<option value="Hungary">Hungary</option>
					<option value="Iceland">Iceland</option>
					<option value="India">India </option>
					<option value="Indonesia">Indonesia</option>
					<option value="Iran">Iran </option>
					<option value="Iraq">Iraq </option>
					<option value="Ireland">Ireland </option>
					<option value="Israel">Israel </option>
					<option value="Italy">Italy </option>
					<option value="Jamaica">Jamaica </option>
					<option value="Japan">Japan </option>
					<option value="Jordan">Jordan </option>
					<option value="Kazakhstan">Kazakhstan</option>
					<option value="Kenya">Kenya  </option>
					<option value="Kiribati">Kiribati </option>
					<option value="Korea">Korea</option>
					<option value="Kuwait">Kuwait </option>
					<option value="Kyrgyzstan">Kyrgyzstan </option>
					<option value="Laos">Laos  </option>
					<option value="Latvia">Latvia  </option>
					<option value="Lebanon">Lebanon </option>
					<option value="Lesotho">Lesotho</option>
					<option value="Liberia">Liberia </option>
					<option value="Libya">Libya </option>
					<option value="Liechtenstein">Liechtenstein </option>
					<option value="Lithuania">Lithuania </option>
					<option value="Luxembourg">Luxembourg </option>
					<option value="Macedonia">Macedonia</option>
					<option value="Madagascar">Madagascar </option>
					<option value="Malawi">Malawi </option>
					<option value="Malaysia">Malaysia </option>
					<option value="Maldives">Maldives </option>
					<option value="Mali">Mali </option>
					<option value="Malta">Malta  </option>
					<option value="Marshall Islands">Marshall Islands </option>
					<option value="Mauritania">Mauritania  </option>
					<option value="Mauritius">Mauritius  </option>
					<option value="Mexico">Mexico   </option>
					<option value="Micronesia">Micronesia</option>
					<option value="Moldova">Moldova </option>
					<option value="Monaco">Monaco  </option>
					<option value="Mongolia">Mongolia  </option>
					<option value="Morocco">Morocco </option>
					<option value="Mozambique">Mozambique </option>
					<option value="Myanmar">Myanmar </option>
					<option value="Namibia">Namibia  </option>
					<option value="Nauru">Nauru  </option>
					<option value="Nepal">Nepal </option>
					<option value="Netherlands">Netherlands  </option>
					<option value="New Zealand">New Zealand  </option>
					<option value="Nicaragua">Nicaragua </option>
					<option value="Niger">Niger </option>
					<option value="Nigeria">Nigeria </option>
					<option value="Norway ">Norway </option>
					<option value="Oman">Oman </option>
					<option value="Pakistan">Pakistan</option>
					<option value="Palau">Palau </option>
					<option value="Panama">Panama </option>
					<option value="Papua New Guinea">Papua New Guinea </option>
					<option value="Paraguay">Paraguay  </option>
					<option value="Peru">Peru   </option>
					<option value="Philippines">Philippines  </option>
					<option value="Poland">Poland  </option>
					<option value="Portugal">Portugal   </option>
					<option value="Qatar">Qatar </option>
					<option value="Romania">Romania  </option>
					<option value="Russia">Russia </option>
					<option value="Rwanda">Rwanda </option>
					<option value="Saint Kitts">Saint Kitts </option>
					<option value="Saint Lucia">Saint Lucia</option>
					<option value="Saint Vincent">Saint Vincent </option>
					<option value="Samoa">Samoa  </option>
					<option value="San Marino">San Marino</option>
					<option value="Sao Tome and Principe">Sao Tome and Principe </option>
					<option value="Saudi Arabia ">Saudi Arabia </option>
					<option value="Senegal">Senegal  </option>
					<option value="Serbia and Montenegro">Serbia and Montenegro </option>
					<option value="Seychelles ">Seychelles </option>
					<option value="Sierra Leone">Sierra Leone </option>
					<option value="Singapore">Singapore  </option>
					<option value="Slovakia">Slovakia </option>
					<option value="Slovenia">Slovenia</option>
					<option value="Solomon Islands">Solomon Islands </option>
					<option value="Somalia">Somalia  </option>
					<option value="South Africa">South Africa </option>
					<option value="Spain">Spain  </option>
					<option value="Sri Lanka">Sri Lanka </option>
					<option value="Sudan">Sudan  </option>
					<option value="Suriname">Suriname </option>
					<option value="Swaziland">Swaziland </option>
					<option value="Sweden">Sweden </option>
					<option value="Switzerland">Switzerland </option>
					<option value="Syria">Syria </option>
					<option value="Taiwan">Taiwan </option>
					<option value="Tajikistan">Tajikistan </option>
					<option value="Tanzania">Tanzania </option>
					<option value="Thailand">Thailand </option>
					<option value="Togo">Togo </option>
					<option value="Tonga">Tonga</option>
					<option value="Trinidad and Tobago">Trinidad and Tobago</option>
					<option value="Tunisia">Tunisia  </option>
					<option value="Turkey">Turkey </option>
					<option value="Turkmenistan">Turkmenistan </option>
					<option value="Tuvalu">Tuvalu </option>
					<option value="Uganda">Uganda </option>
					<option value="Ukraine">Ukraine </option>
					<option value="United Arab Emirates">United Arab Emirates </option>
					<option value="United Kingdom">United Kingdom </option>
					<option value="United States">United States  </option>
					<option value="Uruguay">Uruguay </option>
					<option value="Uzbekistan">Uzbekistan </option>
					<option value="Vanuatu">Vanuatu </option>
					<option value="Vatican City">Vatican City </option>
					<option value="Venezuela">Venezuela </option>
					<option value="Vietnam">Vietnam</option>
					<option value="Western Sahara">Western Sahara </option>
					<option value="Yemen">Yemen </option>
					<option value="Zambia">Zambia </option>
					<option value="Zimbabwe">Zimbabwe </option>
					</select>	
					<script language="javascript">
						 document.view.country.value='<?=$show_select_val[13]?>';	
					</script>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State 
                          :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<!--<input type="text" name="state2" size="20" value="<?=$show_select_val[15]?>" >-->
						<select name="state"  style="font-family:arial;font-size:11px;width:200px"> 
						<option value="select">&nbsp;</option>
						<option value="Alabama">Alabama</option>
						<option value="Alaska"> Alaska</option>
						<option value="Arizona"> Arizona</option>
						<option value="Arkansas"> Arkansas</option>
						<option value="California"> California</option>
						<option value="Colorado"> Colorado</option>
						<option value="Connecticut"> Connecticut</option>
						<option value="Delaware"> Delaware</option>
						<option value="Florida"> Florida</option>
						<option value="Georgia"> Georgia</option>
						<option value="Hawaii"> Hawaii</option>
						<option value="Idaho"> Idaho  </option>
						<option value="Illinois"> Illinois</option>
						<option value="Indiana"> Indiana</option>
						<option value="Iowa"> Iowa</option>
						<option value="Kansas"> Kansas</option>
						<option value="Kentucky"> Kentucky </option>
						<option value="Louisiana"> Louisiana </option>
						<option value="Maine"> Maine</option>
						<option value="Maryland"> Maryland</option>
						<option value="Massachusetts"> Massachusetts</option>
						<option value="Michigan"> Michigan</option>
						<option value="Minnesota"> Minnesota</option>
						<option value="Mississippi"> Mississippi</option>
						<option value="Missouri"> Missouri</option>
						<option value="Montana"> Montana</option>
						<option value="Nebraska"> Nebraska</option>
						<option value="Nevada"> Nevada</option>
						<option value="New Hampshire"> New Hampshire</option>
						<option value="New Jersey"> New Jersey</option>
						<option value="New Mexico"> New Mexico</option>
						<option value="New York"> New York</option>
						<option value="North Carolina"> North Carolina</option>
						<option value="North Dakota"> North Dakota</option>
						<option value="Ohio"> Ohio</option>
						<option value="Oklahoma"> Oklahoma </option>
						<option value="Oregon"> Oregon</option>
						<option value="Pennsylvania"> Pennsylvania</option>
						<option value="Rhode Island"> Rhode Island</option>
						<option value="South Carolina"> South Carolina</option>
						<option value="South Dakota"> South Dakota</option>
						<option value="Tennessee"> Tennessee</option>
						<option value="Texas"> Texas</option>
						<option value="Utah"> Utah</option>
						<option value="Vermont"> Vermont</option>
						<option value="Virginia"> Virginia</option>
						<option value="Washington"> Washington</option>
						<option value="Washington DC">Washington DC </option>
						<option value="West Virginia"> West Virginia</option>
						<option value="Wisconsin"> Wisconsin</option>
						<option value="Wyoming"> Wyoming  </option>
						</select>
						<script language="javascript">
							 document.view.state.value='<?=$show_select_val[15]?>';	
						</script>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip 
                          code :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="zip"  size="15" maxlength="15" value="<?=$show_select_val[16]?>">
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="phonenumber" size="25" maxlength="30" value="<?=$show_select_val[11]?>" >
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
						<input type="text" name="email2" size="40" maxlength="100" value="<?=$show_select_val[19]?>" >
						</font></td>
                      </tr>
                      <tr bgcolor="#CCCCCC"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Payment 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
                          Number :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="number" size="17" maxlength="16" value="<?=$show_select_val[5]?>" >
						</font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("../images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
						<input type="text" name="cvv2" size="3" maxlength="3"  value="<?=$show_select_val[25]?>" >
						</font> </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Type 
                          : </font></td>
						  <td valign="middle" class="tdbdr">&nbsp; 
						  <!-- <input type="text" name="ctype" size="20" value="<?=$show_select_val[21]?>"  > -->
						  <select size="1" name="cardtype" style="font-size: 8pt; font-family: Verdana">
                            <option value="Master">Master Card</option>
                            <option value="Visa">Visa</option>
                          </select>
						<script language="javascript">
							 document.view.cardtype.value='<?=$show_select_val[21]?>';	
						</script>

						  </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration 
                          Date :</font></td>
					  <td valign="middle" class="tdbdr">&nbsp; 
					  <!--<input type="text" name="expdate" size="20" value="<?=$show_select_val[22]?>" > -->
					  <?php 
					  $exp_year="";
					  $exp_mm="";
					  $exp_year = substr($show_select_val[22],0,4);
					  $exp_mm =  substr($show_select_val[22],5,6);
					  ?>
					  <select name="opt_exp_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($exp_mm); ?>
						  </select>
						<select name="opt_exp_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($exp_year); ?>
						  </select>  
					  </td>
                      </tr>
						<tr> 
                        <td  align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
                          of Money :</font><br></td>
					                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                                      <input type="text" name="amount" size="15" maxlength="50" value="<?=$show_select_val[7]?>" >
						</font></td>
                      </tr>					<tr>
					  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
						Date(mm-dd-yyyy) : </font></td>
					  <td class="tdbdr">&nbsp;<font color="#001188"> 
		<!--<input type="text" name="setbilldate" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" > -->
<?php 
				   $trans_recur_start_date = $show_select_val[38];
				   $i_to_month1 = substr($trans_recur_start_date,5,2);
				   $i_to_day1 = substr($trans_recur_start_date,8,2);
				   $i_to_year1 = substr($trans_recur_start_date,0,4);

?>
				   <select name="opt_bill_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($i_to_month1); ?>
						  </select>
						  <select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_day($i_to_day1); ?>	
						  </select>
						  <select name="opt_bill_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($i_to_year1); ?>
						  </select>
						  
						</font></td>
					</tr>
					<tr> 
                                          <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product 
                                            Description # : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="txtproductdescription" size="30" maxlength="200" value="<?=$show_select_val[48]?>" >
                                            </font></td>
                </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
							<td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp; 
							  <?=func_get_date_time_12hr($show_select_val[3])?>
							  </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
					  <td valign="middle" ><font size="1" face="Verdana" color="#000000">&nbsp;
						<?=$show_select_val[31]?>
						</font>
						<img border="0" SRC="<?=$tmpl_dir?>/images/mastercard.jpg"> <img border="0" SRC="<?=$tmpl_dir?>/images/visa.jpg"> 
						</td>
                      </tr>
					  <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set Recurring Date</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
                   <td colspan="2">		
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr> 
                                          <td align="right" valign="middle" class="tdbdr1" width="47%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Set 
                                            Recurring Date : </font></td>
                                          <td valign="middle" class="tdbdr" width="53%">&nbsp;
                                            <input type="checkbox" name="chk_recur_date" value="Y" <?=$set_recurring?> >
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Day <input type="radio" name="recurdatemode" value="D" <?=$dayval?>> </font></td>
                                          <td valign="middle" class="tdbdr" width="53%"> <font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                                            <input type="text" name="recur_day" size="3" value="<?=$datevalue?>" > Days</font>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Week <input type="radio" name="recurdatemode" value="W" <?=$weekval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
						  <select name="recur_week" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                          <option value="0">&nbsp;</option>
						  <option value="1" <?php print($weekvalue == "1" ? "selected" : ""); ?> >Sunday</option>
                          <option value="2" <?php print($weekvalue == "2" ? "selected" : ""); ?> >Monday</option>
                          <option value="3" <?php print($weekvalue == "3" ? "selected" : ""); ?> >Tuesday</option>
                          <option value="4" <?php print($weekvalue == "4" ? "selected" : ""); ?> >Wednesday</option>
                          <option value="5" <?php print($weekvalue == "5" ? "selected" : ""); ?> >Thursday</option>
                          <option value="6" <?php print($weekvalue == "6" ? "selected" : ""); ?> >Friday</option>
                          <option value="7" <?php print($weekvalue == "7" ? "selected" : ""); ?> >Saturday</option>
						  </select>
						  </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Month <input type="radio" name="recurdatemode" value="M" <?=$monthval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font>
                          <select name="recur_month" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_day("$monthvalue");?>
						  </select>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Year <input type="radio" name="recurdatemode" value="Y" <?=$yearval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <select name="recur_year_month" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_month("$yearmonthvalue");?>
						  </select>
                          <select name="recur_year_day" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_day("$yeardayvalue");?>
						  </select>
                          </font></td>
                      </tr>
					<tr> 
					  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Rebilling Start 
						Date : </font></td>
					  <td class="tdbdr">&nbsp;
						<!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
						<select name="opt_recur_month" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_month($i_to_month); ?>
						</select> <select name="opt_recur_day" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_day($i_to_day); ?>
						</select> <select name="opt_recur_year" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_year($i_to_year); ?>
						</select> </td>
					</tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebilling Charge 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_charge"size="10" maxlength="30" value="<?=$trans_recur_charge?>" ><font size="1" face="Verdana" color="#000000">$ (Leave blank if same as original amount)</font>
                          </font></td>
                      </tr>
                      <tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">No: of Rebillings  
                          : </font></td>
                        <td valign="middle"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_times"  size="5" maxlength="15"value="<?=$trans_recur_times?>" >
                          </font></td>
                      </tr>
					</table>
					</td></tr>
                    </table></td>
                  <td height="2" valign="top" align="left" width="1" style="border-right:1px solid white"></td>
                </tr>
               <tr> 
                  <td height="1" valign="top" align="left" width="19">&nbsp;</td>
                  <td height="1" valign="top" align="left" width="652">&nbsp;</td>
                  <td height="1" valign="top" align="left" width="28">&nbsp;</td>
                </tr>
              </table> 
				<table width="100%" align="center" height="50">
				<tr><td valign="middle" align="center"><p style="margin-left: 30"><font size="2" face="Verdana">Cancelation Reason :</font>
					  <select name="cancelreason" style="font-face:verdana;width:130px">
					  <option value="">Select Reason</option>
					  <option value="Bank Return">Bank Return</option>
					  <option value="Customer cancel">Customer cancel</option>
					  <option value="Chargeback">Chargeback</option>
					  <option value="Credit">Credit</option>
					  <option value="NSF">"NSF"</option>
				      <option value="AVS Return">AVS Return</option> 
			  		  <option value="Invalid Account #">Invalid Account #</option>
				      <option value="Invalid Account">Invalid Account</option>
					  <option value="Invalid Routing #">Invalid Routing #</option>
				  	  <option value="Invalid Card">Invalid Card</option>
					  <option value="Invalid Card Number">Invalid Card #</option>
				      <option value="Shipping Cancel">Shipping Cancel</option> 
				      <option value="Fraudulent">Fraudulent</option> 
				      <option value="Customer Service">Customer Service</option> 
					  <option value="Stop payment">Stop payment</option> 
					 </select>
				<script language="javascript">
					document.view.cancelreason.value='<?=$show_select_val[23]?>';	
				</script>	
			
				</td>
				<td><td valign="middle" align="left"><font size="2" face="Verdana">Other :</font>
				<input type="text" name="other" size="40" value="<?=$show_select_val[24]?>"></input></td></tr>
			</table>
		<input type="hidden" name="cancel" value=""></input>
		<table align="center"><tr><td><a href="#" onclick="func_submit()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;<input type="image" id="viewreport" SRC="<?=$tmpl_dir?>/images/submitcompanydetails.jpg"></input></td></tr></table>
	</td>
      </tr>
    </table>
    </td>
     </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	 
</table>
<?	
	} 
	else 
	{
?>
	<table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center">
  <tr>
       <td width="90%" valign="top" align="center" >
    &nbsp;
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		                <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Check&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">

			
        <table border="0" cellpadding="0" cellspacing="0" width="700" align="center">
          <tr>
			<td width="100%" valign="top" align="left">&nbsp; 
			<table width="100%" cellspacing="0" cellpadding="2" style="border:1px solid black">
               <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Customer 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div2')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000"> 
                          First Name : </font></td>
                                          <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="firstname1" size="20" maxlength="75" value="<?=$show_select_val[1]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Last 
                          Name : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="lastname1"size="20" maxlength="75" value="<?=$show_select_val[2]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Address 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="address" size="45" maxlength="100" value="<?=$show_select_val[12]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">City 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
                                            <input type="text" name="city"  size="35" maxlength="50" value="<?=$show_select_val[14]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Country 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<!--<input type="text" name="country" size="15" value="<?=$show_select_val[13]?>" >-->
						<select name="country"  style="font-family:arial;font-size:11px;width:200px"> 
						<option value="Afghanistan">Afghanistan </option>
						<option value="Albania">Albania </option>
						<option value="Algeria">Algeria </option>
						<option value="Andorra">Andorra </option>
						<option value="Angola">Angola</option>
						<option value="Antigua and Barbuda">Antigua and Barbuda </option>
						<option value="Argentina">Argentina </option>
						<option value="Armenia">Armenia </option>
						<option value="Australia">Australia </option>
						<option value="Austria">Austria</option>
						<option value="Azerbaijan">Azerbaijan </option>
						<option value="Bahamas">Bahamas</option>
						<option value="Bahrain">Bahrain </option>
						<option value="Bangladesh">Bangladesh </option>
						<option value="Barbados">Barbados </option>
						<option value="Belarus">Belarus </option>
						<option value="Belgium">Belgium </option>
						<option value="Belize">Belize </option>
						<option value="Benin">Benin </option>
						<option value="Bhutan">Bhutan </option>
						<option value="Bolivia">Bolivia </option>
						<option value="Bosnia">Bosnia</option>
						<option value="Botswana">Botswana </option>
						<option value="Brazil">Brazil </option>
						<option value="Brunei">Brunei </option>
						<option value="Bulgaria">Bulgaria </option>
						<option value="Burkina Faso">Burkina Faso </option>
						<option value="Burundi">Burundi
						<option value="Cameroon">Cameroon </option>
						<option value="Canada">Canada </option>
						<option value="Cape Verde">Cape Verde </option>
						<option value="Central African">Central African </option>
						<option value="Chad">Chad </option>
						<option value="Chile">Chile </option>
						<option value="China">China </option>
						<option value="Colombia">Colombia </option>
						<option value="Comoros">Comoros</option>
						<option value="Congo">Congo </option>
						<option value="Costa Rica">Costa Rica   </option>
						<option value="Croatia">Croatia </option>
						<option value="Cuba">Cuba </option>
						<option value="Cyprus">Cyprus  </option>
						<option value="Czech Republic">Czech Republic </option>
						<option value="Côte d'Ivoire">Côte d'Ivoire </option>
						<option value="Denmark">Denmark</option>
						<option value="Djibouti">Djibouti</option>
						<option value="Dominica">Dominica</option>
						<option value="Dominican Republic">Dominican Republic </option>
						<option value="East Timor">East Timor</option>
						<option value="Ecuador">Ecuador</option>
						<option value="Egypt">Egypt </option>
						<option value="El Salvador">El Salvador</option>
						<option value="Equatorial Guinea">Equatorial Guinea</option>
						<option value="Eritrea">Eritrea</option>
						<option value="Estonia">Estonia </option>
						<option value="Ethiopia">Ethiopia </option>
						<option value="Fiji">Fiji </option>
						<option value="Finland">Finland </option>
						<option value="France">France </option>
						<option value="Gabon">Gabon </option>
						<option value="Gambia">Gambia</option>
						<option value="Georgia">Georgia</option>
						<option value="Germany">Germany </option>
						<option value="Ghana">Ghana </option>
						<option value="Greece">Greece  </option>
						<option value="Grenada">Grenada </option>
						<option value="Guatemala">Guatemala </option>
						<option value="Guinea">Guinea </option>
						<option value="Guyana">Guyana </option>
						<option value="Haiti">Haiti</option>
						<option value="Honduras">Honduras </option>
						<option value="Hungary">Hungary</option>
						<option value="Iceland">Iceland</option>
						<option value="India">India </option>
						<option value="Indonesia">Indonesia</option>
						<option value="Iran">Iran </option>
						<option value="Iraq">Iraq </option>
						<option value="Ireland">Ireland </option>
						<option value="Israel">Israel </option>
						<option value="Italy">Italy </option>
						<option value="Jamaica">Jamaica </option>
						<option value="Japan">Japan </option>
						<option value="Jordan">Jordan </option>
						<option value="Kazakhstan">Kazakhstan</option>
						<option value="Kenya">Kenya  </option>
						<option value="Kiribati">Kiribati </option>
						<option value="Korea">Korea</option>
						<option value="Kuwait">Kuwait </option>
						<option value="Kyrgyzstan">Kyrgyzstan </option>
						<option value="Laos">Laos  </option>
						<option value="Latvia">Latvia  </option>
						<option value="Lebanon">Lebanon </option>
						<option value="Lesotho">Lesotho</option>
						<option value="Liberia">Liberia </option>
						<option value="Libya">Libya </option>
						<option value="Liechtenstein">Liechtenstein </option>
						<option value="Lithuania">Lithuania </option>
						<option value="Luxembourg">Luxembourg </option>
						<option value="Macedonia">Macedonia</option>
						<option value="Madagascar">Madagascar </option>
						<option value="Malawi">Malawi </option>
						<option value="Malaysia">Malaysia </option>
						<option value="Maldives">Maldives </option>
						<option value="Mali">Mali </option>
						<option value="Malta">Malta  </option>
						<option value="Marshall Islands">Marshall Islands </option>
						<option value="Mauritania">Mauritania  </option>
						<option value="Mauritius">Mauritius  </option>
						<option value="Mexico">Mexico   </option>
						<option value="Micronesia">Micronesia</option>
						<option value="Moldova">Moldova </option>
						<option value="Monaco">Monaco  </option>
						<option value="Mongolia">Mongolia  </option>
						<option value="Morocco">Morocco </option>
						<option value="Mozambique">Mozambique </option>
						<option value="Myanmar">Myanmar </option>
						<option value="Namibia">Namibia  </option>
						<option value="Nauru">Nauru  </option>
						<option value="Nepal">Nepal </option>
						<option value="Netherlands">Netherlands  </option>
						<option value="New Zealand">New Zealand  </option>
						<option value="Nicaragua">Nicaragua </option>
						<option value="Niger">Niger </option>
						<option value="Nigeria">Nigeria </option>
						<option value="Norway ">Norway </option>
						<option value="Oman">Oman </option>
						<option value="Pakistan">Pakistan</option>
						<option value="Palau">Palau </option>
						<option value="Panama">Panama </option>
						<option value="Papua New Guinea">Papua New Guinea </option>
						<option value="Paraguay">Paraguay  </option>
						<option value="Peru">Peru   </option>
						<option value="Philippines">Philippines  </option>
						<option value="Poland">Poland  </option>
						<option value="Portugal">Portugal   </option>
						<option value="Qatar">Qatar </option>
						<option value="Romania">Romania  </option>
						<option value="Russia">Russia </option>
						<option value="Rwanda">Rwanda </option>
						<option value="Saint Kitts">Saint Kitts </option>
						<option value="Saint Lucia">Saint Lucia</option>
						<option value="Saint Vincent">Saint Vincent </option>
						<option value="Samoa">Samoa  </option>
						<option value="San Marino">San Marino</option>
						<option value="Sao Tome and Principe">Sao Tome and Principe </option>
						<option value="Saudi Arabia ">Saudi Arabia </option>
						<option value="Senegal">Senegal  </option>
						<option value="Serbia and Montenegro">Serbia and Montenegro </option>
						<option value="Seychelles ">Seychelles </option>
						<option value="Sierra Leone">Sierra Leone </option>
						<option value="Singapore">Singapore  </option>
						<option value="Slovakia">Slovakia </option>
						<option value="Slovenia">Slovenia</option>
						<option value="Solomon Islands">Solomon Islands </option>
						<option value="Somalia">Somalia  </option>
						<option value="South Africa">South Africa </option>
						<option value="Spain">Spain  </option>
						<option value="Sri Lanka">Sri Lanka </option>
						<option value="Sudan">Sudan  </option>
						<option value="Suriname">Suriname </option>
						<option value="Swaziland">Swaziland </option>
						<option value="Sweden">Sweden </option>
						<option value="Switzerland">Switzerland </option>
						<option value="Syria">Syria </option>
						<option value="Taiwan">Taiwan </option>
						<option value="Tajikistan">Tajikistan </option>
						<option value="Tanzania">Tanzania </option>
						<option value="Thailand">Thailand </option>
						<option value="Togo">Togo </option>
						<option value="Tonga">Tonga</option>
						<option value="Trinidad and Tobago">Trinidad and Tobago</option>
						<option value="Tunisia">Tunisia  </option>
						<option value="Turkey">Turkey </option>
						<option value="Turkmenistan">Turkmenistan </option>
						<option value="Tuvalu">Tuvalu </option>
						<option value="Uganda">Uganda </option>
						<option value="Ukraine">Ukraine </option>
						<option value="United Arab Emirates">United Arab Emirates </option>
						<option value="United Kingdom">United Kingdom </option>
						<option value="United States">United States  </option>
						<option value="Uruguay">Uruguay </option>
						<option value="Uzbekistan">Uzbekistan </option>
						<option value="Vanuatu">Vanuatu </option>
						<option value="Vatican City">Vatican City </option>
						<option value="Venezuela">Venezuela </option>
						<option value="Vietnam">Vietnam</option>
						<option value="Western Sahara">Western Sahara </option>
						<option value="Yemen">Yemen </option>
						<option value="Zambia">Zambia </option>
						<option value="Zimbabwe">Zimbabwe </option>
						</select>	
						<script language="javascript">
							 document.view.country.value='<?=$show_select_val[13]?>';	
						</script>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">State 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<!-- <input type="text" name="state" size="15" value="<?=$show_select_val[15]?>" > -->
						<select name="state"  style="font-family:arial;font-size:11px;width:200px"> 
						<option value="select">&nbsp;</option>
						<option value="Alabama">Alabama</option>
						<option value="Alaska"> Alaska</option>
						<option value="Arizona"> Arizona</option>
						<option value="Arkansas"> Arkansas</option>
						<option value="California"> California</option>
						<option value="Colorado"> Colorado</option>
						<option value="Connecticut"> Connecticut</option>
						<option value="Delaware"> Delaware</option>
						<option value="Florida"> Florida</option>
						<option value="Georgia"> Georgia</option>
						<option value="Hawaii"> Hawaii</option>
						<option value="Idaho"> Idaho  </option>
						<option value="Illinois"> Illinois</option>
						<option value="Indiana"> Indiana</option>
						<option value="Iowa"> Iowa</option>
						<option value="Kansas"> Kansas</option>
						<option value="Kentucky"> Kentucky </option>
						<option value="Louisiana"> Louisiana </option>
						<option value="Maine"> Maine</option>
						<option value="Maryland"> Maryland</option>
						<option value="Massachusetts"> Massachusetts</option>
						<option value="Michigan"> Michigan</option>
						<option value="Minnesota"> Minnesota</option>
						<option value="Mississippi"> Mississippi</option>
						<option value="Missouri"> Missouri</option>
						<option value="Montana"> Montana</option>
						<option value="Nebraska"> Nebraska</option>
						<option value="Nevada"> Nevada</option>
						<option value="New Hampshire"> New Hampshire</option>
						<option value="New Jersey"> New Jersey</option>
						<option value="New Mexico"> New Mexico</option>
						<option value="New York"> New York</option>
						<option value="North Carolina"> North Carolina</option>
						<option value="North Dakota"> North Dakota</option>
						<option value="Ohio"> Ohio</option>
						<option value="Oklahoma"> Oklahoma </option>
						<option value="Oregon"> Oregon</option>
						<option value="Pennsylvania"> Pennsylvania</option>
						<option value="Rhode Island"> Rhode Island</option>
						<option value="South Carolina"> South Carolina</option>
						<option value="South Dakota"> South Dakota</option>
						<option value="Tennessee"> Tennessee</option>
						<option value="Texas"> Texas</option>
						<option value="Utah"> Utah</option>
						<option value="Vermont"> Vermont</option>
						<option value="Virginia"> Virginia</option>
						<option value="Washington"> Washington</option>
						<option value="Washington DC">Washington DC </option>
						<option value="West Virginia"> West Virginia</option>
						<option value="Wisconsin"> Wisconsin</option>
						<option value="Wyoming"> Wyoming  </option>
						</select>
						<script language="javascript">
							 document.view.state.value='<?=$show_select_val[15]?>';	
						</script>
						</font></td>
                      </tr>
                      <tr> 
                        <td a align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Zip 
                          code : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="zip" size="15" maxlength="15" value="<?=$show_select_val[16]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Telephone 
                          # : </font></td>
                                          <td class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="phonenumber" size="25" maxlength="30" value="<?=$show_select_val[11]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
						<input type="text" name="email2" size="40" maxlength="100" value="<?=$show_select_val[19]?>" >
						</font></td>
                      </tr>
					  
				<tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><strong><span class="subhd">Transaction 
                    Information</span></strong></td>
                </tr>
<!--                <tr> 
                  <td align="right" width=50% class="tdbdr1"><font size="2" face="Verdana" color="#000000">Invoice/Reference 
                    ID : </font></td>
                  <td width=50% class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="invoiceid" size="20">
                    </font></td>
                </tr>
-->				
                <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                          # : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="chequenumber" size="20" maxlength="50" value="<?=$show_select_val[5]?>" >
                                            </font></td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                    Type : </font></td>
			  <td class="tdbdr" valign="middle">&nbsp;&nbsp;
			<!--	<input type="text" name="typec" size="10"  value="<?=$show_select_val[28]?>" >  -->
			<?PHP $checkb="";
				  $checkp="";
				if($show_select_val[28] =="personal"){
					$checkp="checked";
				} else {
					$checkb="checked";
				}
			?>
				<input type="radio" name="chequetype" value="personal" <?=$checkp?>><font size="1" face="Verdana" color="#000000">Personal</font>&nbsp;&nbsp;<input type="radio" name="chequetype" value="business" <?=$checkb?>><font size="1" face="Verdana" color="#000000">Business</font>
			  </td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Amount(US 
                    Dollars) : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="amount"size="9" maxlength="50" value="<?=$show_select_val[7]?>" >
                                            </font></td>
                </tr>
                <tr> 
                  <td class="tdbdr1" align="right"><font size="2" face="Verdana" color="#000000">Account 
                    Type : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
						<!-- <input type="text" name="account" size="10" value="<?=$show_select_val[30]?>" > -->
						<?php 
						$checks="";
						$checkc="";
						if($show_select_val[30] =="checking") {
							$checkc="Checked";
						} else {
							$checks="Checked";
						}
						?>
						<input type="radio" name="accounttype" value="checking" <?=$checkc?>><font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;<input type="radio" name="accounttype" value="savings" <?=$checks?>><font size="1" face="Verdana" color="#000000">Savings</font>
						</font>
				   </td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
                    Date(mm-dd-yyyy) : </font></td>
				  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188"> 
				<!--	<input type="text" name="setbilldate2" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" > -->
				<?php 
				   $trans_recur_start_date = $show_select_val[38];
				   $i_to_month1 = substr($trans_recur_start_date,5,2);
				   $i_to_day1 = substr($trans_recur_start_date,8,2);
				   $i_to_year1 = substr($trans_recur_start_date,0,4);

?>
				   <select name="opt_bill_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($i_to_month1); ?>
						  </select>
						  <select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_day($i_to_day1); ?>	
						  </select>
						  <select name="opt_bill_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($i_to_year1); ?>
						  </select>
					</font> </td>
                </tr>
				<tr> 
                                          <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product 
                                            Description # : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="txtproductdescription" size="30" maxlength="200" value="<?=$show_select_val[48]?>" >
                                            </font></td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Bank 
                    Information</strong>&nbsp;
                    <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000">Bank 
                          Name : </font></td>
                                                <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankname" size="45" maxlength="75" value="<?=$show_select_val[29]?>" >
                                                  </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Bank 
                          Routing Code : </font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankroutingcode" size="9" maxlength="9" value="<?=$show_select_val[10]?>" >
                                                  </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font size="2" face="Verdana" color="#000000">Bank 
                                Account # : </font></font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankaccountno"size="25" maxlength="15"value="<?=$show_select_val[9]?>" >
                                                  </font></td>
                      </tr>
                    </table></td>
                </tr>
                    </table>
                  </td>
                </tr>
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Shipping 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
              	<td colspan="2"> 
				<table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                                          <td valign="middle" class="tdbdr" width="50%"> <font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="authorizationno"  size="25" maxlength="25" value="<?=$show_select_val[33]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="shippingno"size="20" maxlength="50" value="<?=$show_select_val[34]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="securityno"size="9" maxlength="9" value="<?=$show_select_val[35]?>" >
                                            </font></td>
                      </tr>
						<tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">License 
                          State : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
					<!--    <input type="text" name="licensestate" size="20" value="<?=$show_select_val[39]?>" > -->
					<select name="licensestate"  style="font-family:arial;font-size:11px;width:200px"> 
			<option value="select">&nbsp;</option>
			<option value="Alabama">Alabama</option>
			<option value="Alaska"> Alaska</option>
			<option value="Arizona"> Arizona</option>
			<option value="Arkansas"> Arkansas</option>
			<option value="California"> California</option>
			<option value="Colorado"> Colorado</option>
			<option value="Connecticut"> Connecticut</option>
			<option value="Delaware"> Delaware</option>
			<option value="Florida"> Florida</option>
			<option value="Georgia"> Georgia</option>
			<option value="Hawaii"> Hawaii</option>
			<option value="Idaho"> Idaho  </option>
			<option value="Illinois"> Illinois</option>
			<option value="Indiana"> Indiana</option>
			<option value="Iowa"> Iowa</option>
			<option value="Kansas"> Kansas</option>
			<option value="Kentucky"> Kentucky </option>
			<option value="Louisiana"> Louisiana </option>
			<option value="Maine"> Maine</option>
			<option value="Maryland"> Maryland</option>
			<option value="Massachusetts"> Massachusetts</option>
			<option value="Michigan"> Michigan</option>
			<option value="Minnesota"> Minnesota</option>
			<option value="Mississippi"> Mississippi</option>
			<option value="Missouri"> Missouri</option>
			<option value="Montana"> Montana</option>
			<option value="Nebraska"> Nebraska</option>
			<option value="Nevada"> Nevada</option>
			<option value="New Hampshire"> New Hampshire</option>
			<option value="New Jersey"> New Jersey</option>
			<option value="New Mexico"> New Mexico</option>
			<option value="New York"> New York</option>
			<option value="North Carolina"> North Carolina</option>
			<option value="North Dakota"> North Dakota</option>
			<option value="Ohio"> Ohio</option>
			<option value="Oklahoma"> Oklahoma </option>
			<option value="Oregon"> Oregon</option>
			<option value="Pennsylvania"> Pennsylvania</option>
			<option value="Rhode Island"> Rhode Island</option>
			<option value="South Carolina"> South Carolina</option>
			<option value="South Dakota"> South Dakota</option>
			<option value="Tennessee"> Tennessee</option>
			<option value="Texas"> Texas</option>
			<option value="Utah"> Utah</option>
			<option value="Vermont"> Vermont</option>
			<option value="Virginia"> Virginia</option>
			<option value="Washington">Washington</option>
			<option value="Washington DC">Washington DC </option>
			<option value="West Virginia"> West Virginia</option>
			<option value="Wisconsin"> Wisconsin</option>
			<option value="Wyoming"> Wyoming  </option>
			</select>
			<script language="javascript">
				 document.view.licensestate.value='<?=$show_select_val[39]?>';	
			</script>
						</font></td>
                      </tr>                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="driverlicense"size="25" maxlength="100"value="<?=$show_select_val[36]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="misc" size="35" maxlength="100" value="<?=$show_select_val[17]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
                                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp; 
                                            <?=func_get_date_time_12hr($show_select_val[3])?>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
                                          <td valign="middle"><font size="1" face="Verdana" color="#000000"> 
                                            &nbsp;&nbsp; 
                                            <?=$show_select_val[31]?>
                                            </font> </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <!--Div -->
				<tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set Recurring Date</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Set Recurring Date : </font></td>
                        <td valign="middle" class="tdbdr" width="50%">&nbsp;<input type="checkbox" name="chk_recur_date" value="Y" <?=$set_recurring?> >
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Day <input type="radio" name="recurdatemode" value="D" <?=$dayval?>> </font></td>
                        <td valign="middle" class="tdbdr" width="50%"> <font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <input type="text" name="recur_day"size="3" maxlength="3" value="<?=$datevalue?>" > Days</font>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Week <input type="radio" name="recurdatemode" value="W" <?=$weekval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
						  <select name="recur_week" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                          <option value="0">&nbsp;</option>
						  <option value="1" <?php print($weekvalue == "1" ? "selected" : ""); ?> >Sunday</option>
                          <option value="2" <?php print($weekvalue == "2" ? "selected" : ""); ?> >Monday</option>
                          <option value="3" <?php print($weekvalue == "3" ? "selected" : ""); ?> >Tuesday</option>
                          <option value="4" <?php print($weekvalue == "4" ? "selected" : ""); ?> >Wednesday</option>
                          <option value="5" <?php print($weekvalue == "5" ? "selected" : ""); ?> >Thursday</option>
                          <option value="6" <?php print($weekvalue == "6" ? "selected" : ""); ?> >Friday</option>
                          <option value="7" <?php print($weekvalue == "7" ? "selected" : ""); ?> >Saturday</option>
						  </select>
						  </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Month <input type="radio" name="recurdatemode" value="M" <?=$monthval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font>
                          <select name="recur_month" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_day("$monthvalue");?>
						  </select>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Year <input type="radio" name="recurdatemode" value="Y" <?=$yearval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <select name="recur_year_month" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_month("$yearmonthvalue");?>
						  </select>
                          <select name="recur_year_day" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php func_fill_day("$yeardayvalue");?>
						  </select>
                          </font></td>
                      </tr>
					<tr> 
					  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Rebilling Start 
						Date : </font></td>
					  <td class="tdbdr">&nbsp;
						<!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
						<select name="opt_recur_month" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_month($i_to_month); ?>
						</select> <select name="opt_recur_day" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_day($i_to_day); ?>
						</select> <select name="opt_recur_year" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_year($i_to_year); ?>
						</select> </td>
					</tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebilling Charge 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_charge"  size="10" maxlength="30" value="<?=$trans_recur_charge?>" ><font size="1" face="Verdana" color="#000000">$ (Leave blank if same as original amount)</font>
                          </font></td>
                      </tr>
                      <tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">No: of Rebillings  
                          : </font></td>
                        <td valign="middle"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_times"size="5" maxlength="20" value="<?=$trans_recur_times?>" >
                          </font></td>
                      </tr>
					</table>
				</td>
			 </tr>
              </table>	
							
				<table width="100%" align="center" height="50">
				<tr><td valign="middle" align="center"><p style="margin-left: 30"><font size="2" face="Verdana">Cancelation Reason :</font>
				<select name="cancelreason" style="font-face:verdana;width:130px">
					  <option value="">Select Reason</option>
					  <option value="Bank Return">Bank Return</option>
					  <option value="Customer cancel">Customer cancel</option>
					  <option value="Chargeback">Chargeback</option>
					  <option value="Credit">Credit</option>
					  <option value="NSF">"NSF"</option>
				      <option value="AVS Return">AVS Return</option> 
			  		  <option value="Invalid Account #">Invalid Account #</option>
				      <option value="Invalid Account">Invalid Account</option>
					  <option value="Invalid Routing #">Invalid Routing #</option>
				  	  <option value="Invalid Card">Invalid Card</option>
					  <option value="Invalid Card Number">Invalid Card #</option>
				      <option value="Shipping Cancel">Shipping Cancel</option> 
				      <option value="Fraudulent">Fraudulent</option> 
				      <option value="Customer Service">Customer Service</option> 
					  <option value="Stop payment">Stop payment</option> 

				</select>
					<script language="javascript">
					document.view.cancelreason.value='<?=$show_select_val[23];?>';	
					</script>
				</td>
						  
				  <td><td valign="middle" align="left"><font size="2" face="Verdana">Other :</font>
				<input type="text" name="other" size="40" value="<?=$show_select_val[24]?>"></input></td></tr>
			</table>
            </td>
          </tr>
        </table>
	<input type="hidden" name="cancel" value="cancel"></input>
		<table align="center"><tr><td><a href="#" onclick="func_submit()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;<input type="image" id="viewreport" SRC="<?=$tmpl_dir?>/images/submitcompanydetails.jpg"></input></td></tr></table>
	</td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table>

<?php
			}	
		}

	}
	
?>
		    </td>
     </tr>
</table>
</form>	</td></tr></table>	
<?php
include 'includes/footer.php';
}
?>