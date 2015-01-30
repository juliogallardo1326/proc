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
// reportBottomSummary.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude = "reports";
include 'includes/topheader.php';
require_once('includes/function.php');
include 'includes/function1.php';

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($sessionlogin!=""){ 
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$str_type =(isset($HTTP_POST_VARS['type'])?Trim($HTTP_POST_VARS['type']):"");
$crorcq = (isset($HTTP_POST_VARS['crorcq'])?Trim($HTTP_POST_VARS['crorcq']):"");
$period = $HTTP_POST_VARS['period'];
if($_GET['period'])$period=$_GET['period'];

$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

if($_GET['opt_from_full'])$dateToEnter=$_GET['opt_from_full'];
if($_GET['opt_to_full'])$dateToEnter1=$_GET['opt_to_full'];

if($crorcq =="") {
	$crorcq = $HTTP_GET_VARS['crorcq'];
}
if($str_type =="") {
	$str_type = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"");
}
if($period =="") {
	$period = $HTTP_GET_VARS['period'];
}
if($crorcq){
	  if($crorcq =="A"){
			  $querycc="";
	  } elseif($crorcq == "C") {
			if($str_type == "A") {
				  $querycc="and checkorcard='C'";
			} elseif($str_type == "S") {
				  $querycc="and checkorcard='C' and accounttype='savings'";
			} elseif($str_type == "C") {
				  $querycc="and checkorcard='C' and accounttype='checking'";
			}
	  } elseif($crorcq =="H") {
			if($str_type == "A") {
				  $querycc="and checkorcard='H'";
			} elseif($str_type == "M") {
				  $querycc="and checkorcard='H' and cardtype='Master'";
			} elseif($str_type == "V") {
				  $querycc="and checkorcard='H' and cardtype='Visa'";
			}
	  } else {
			$querycc= "";
	  }
}
				
if(!$period){
	$i_from_day = date("d");
	$i_from_month = date("m");
	$i_from_year = date("Y");
	$i_to_day = date("d");
	$i_to_month = date("m");
	$i_to_year = date("Y");
	$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
	$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
	$querystr="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback, r_credit, r_discountrate, r_transactionfee, r_reserve, accounttype, cardtype,reason,passstatus,amount,voiceauthfee,admin_approval_for_cancellation FROM cs_transactiondetails, cs_companydetails WHERE ";
    $querystr1=" cs_transactiondetails.userid = cs_companydetails.userid and transactionDate between '$dateToEnter' and '$dateToEnter1' $querycc"; 
}
if($period=="p" ){
	if (!$dateToEnter) $dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
	if (!$dateToEnter1) $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
	$querystr="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback, r_credit, r_discountrate, r_transactionfee, r_reserve, accounttype, cardtype,reason,passstatus,amount,voiceauthfee,admin_approval_for_cancellation FROM cs_transactiondetails, cs_companydetails WHERE ";
	$querystr1="  cs_transactiondetails.userid = cs_companydetails.userid and transactionDate between '$dateToEnter' and '$dateToEnter1' $querycc"; 
}

if($period=="p"){	  
	 $periodhead="Periodic  Report";
}
  
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="100%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="55%">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Ledger&nbsp; 
            Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
        <td width="100%" valign="top" align="left" class="lgnbd" colspan="5">

	<form name="summery" action="javascript:window.history.back()">	
	<?php
		$querystr=$querystr." cs_transactiondetails.userid =$sessionlogin and ".$querystr1;
		$qrt_voice_select =" select count(*) from cs_voice_system_upload_log where user_id=$sessionlogin and upload_date_time between '$dateToEnter' and '$dateToEnter1'";
		$str_merchant_type = func_get_value_of_field($cnn_cs,"cs_companydetails","transaction_type","userid",$sessionlogin);
		if ($str_merchant_type == "tele") {	
			func_show_ledger_details($querystr,$cnn_cs,$crorcq,$str_type,"A",$qrt_voice_select);
		} else {
			func_show_ecommerce_ledger_details($querystr,$cnn_cs,$crorcq,$str_type,"A");
		}
		//echo($querystr);
	?>
	<center>
<table align="center"  ><tr>
		<td align="center" valign="center" height="30" colspan="2"><input type="image" id="backsummery" src="images/back.jpg" border="0"></input></td></tr>	
</table></center>

	</form>
	</td>
      </tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
	</table>
    </td>
     </tr>
</table><br>

<?
include 'includes/footer.php';
}
?>