<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// resellerLedger.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';

$headerInclude = "blank";
include 'includes/header.php';

require_once( '../includes/function.php');
include '../includes/function1.php';
$qry_company_type="";
$qry_select_user="";
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$total_numbers=0;
$total_amount=0;
if($resellerLogin!="")
{
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_GET_VARS["opt_from_year"])?trim($HTTP_GET_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_GET_VARS["opt_from_month"])?trim($HTTP_GET_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_GET_VARS["opt_from_day"])?trim($HTTP_GET_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_GET_VARS["opt_to_year"])?trim($HTTP_GET_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_GET_VARS["opt_to_month"])?trim($HTTP_GET_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_GET_VARS["opt_to_day"])?trim($HTTP_GET_VARS["opt_to_day"]):$i_to_day);
$str_type =(isset($HTTP_GET_VARS['type'])?trim($HTTP_GET_VARS['type']):"");
$companytrans_type = isset($HTTP_GET_VARS['merchant_type'])?$HTTP_GET_VARS['merchant_type']:"";
$companyname = (isset($HTTP_GET_VARS['companyname'])?($HTTP_GET_VARS['companyname']):"");
$period =  (isset($HTTP_GET_VARS['period'])?($HTTP_GET_VARS['period']):"p");

if(!$companyname){
	$outhtml="y";
	$msgtodisplay="Select a Company";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
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
		$querystr="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback , r_credit, r_discountrate, r_transactionfee , r_reserve, accounttype, cardtype,reason,passstatus,amount,voiceauthfee, r_reseller_trans_fees,r_reseller_discount_rate FROM cs_transactiondetails, cs_companydetails WHERE ";
	   $querystr1=" cs_transactiondetails.userid = cs_companydetails.userid and transactionDate >= '$dateToEnter' and transactionDate <= '$dateToEnter1' and cs_companydetails.activeuser=1 and cs_companydetails.reseller_id=$resellerLogin"; // GROUP BY checkorcard, STATUS , cancelstatus ORDER BY checkorcard"; 
	}
	
	
	if($period =="p" ){
	  $dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
	  $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

	   $querystr="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback , r_credit, r_discountrate, r_transactionfee , r_reserve, accounttype, cardtype,reason,passstatus,amount,voiceauthfee, r_reseller_trans_fees,r_reseller_discount_rate FROM cs_transactiondetails, cs_companydetails WHERE ";
	   $querystr1="  cs_transactiondetails.userid = cs_companydetails.userid and transactionDate >= '$dateToEnter' and transactionDate <= '$dateToEnter1' and cs_companydetails.activeuser=1 and cs_companydetails.reseller_id=$resellerLogin"; // GROUP BY checkorcard, STATUS , cancelstatus ORDER BY checkorcard"; 
	}
	
	if($period=="p"){	  
		 $periodhead="Periodic  Report";
	}
if($companytrans_type=="A"){
	$qry_select_user="";
}else {
	$qry_select_user = "and transaction_type = '$companytrans_type'";	
}
?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
       <td width="100%" valign="top" align="center"  height="333">
    &nbsp;
    <table width="45%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Ledger&nbsp;Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">

	<form name="summery" action="javascript:window.history.back()">	
<?php   
		if($companyname[0]!="A"){
		   if($companyname)
		   {
			   $str_company_ids = "";
			   for($i_loop=0;$i_loop<count($companyname);$i_loop++)
			   {
					$str_query=$querystr." cs_transactiondetails.userid=" . $companyname[$i_loop] ." and $querystr1";
					//$qrt_voice_select =" select count(*) from cs_voice_system_upload_log where user_id=" . $companyname[$i_loop] ." and upload_date_time >= '$dateToEnter' and upload_date_time <= '$dateToEnter1'";
				//	print $str_query;
					func_show_reseller_ledger_details($str_query,$cnn_cs,"A","",$companyname[$i_loop]);
			   }
		   }
		} else {
			$show_sql1 =mysql_query("select distinct userid,companyname from cs_companydetails where  reseller_id=$resellerLogin and activeuser=1 $qry_select_user",$cnn_cs);
			// print "select distinct userid,companyname from cs_companydetails where  reseller_id=$resellerLogin and activeuser=1 $qry_select_user"."- Abish<br>";
			while($show_val = mysql_fetch_array($show_sql1)) 
			{
				
				$str_query=$querystr." cs_transactiondetails.userid=" . $show_val[0] ." and $querystr1";
				//$qrt_voice_select =" select count(*) from cs_voice_system_upload_log where user_id=" . $show_val[0] ." and upload_date_time >= '$dateToEnter' and upload_date_time <= '$dateToEnter1'";
//				print $str_query;
				func_show_reseller_ledger_details($str_query,$cnn_cs,"A","",$show_val[0]);
			}
		}

?>
	<center>
<table align="center"  ><tr>
		<td align="center" valign="center" height="30" colspan="2"><input type="image" id="backsummery" src="../images/back.jpg" border="0"></input></td></tr>	
</table></center>
	</form>
	</td>
      </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table><br>
    </td>
     </tr>
</table>

<?
include 'includes/footer.php';
}
?>