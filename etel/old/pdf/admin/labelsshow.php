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
// labelsshow.php:	The admin page functions for printing labels
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';

require_once( '../includes/function.php');

set_time_limit(300);
ignore_user_abort(true);
ini_set("max_execution_time",0);

$str_type = (isset($HTTP_GET_VARS["type"])?quote_smart($HTTP_GET_VARS["type"]):"v");
$str_state_val="";
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

//$txtDate = (isset($HTTP_POST_VARS["txtDate"])?quote_smart($HTTP_POST_VARS["txtDate"]):"");
//$txtDate1 = (isset($HTTP_POST_VARS["txtDate1"])?quote_smart($HTTP_POST_VARS["txtDate1"]):"");
$crorcq = (isset($HTTP_POST_VARS["crorcq"])?quote_smart($HTTP_POST_VARS["crorcq"]):"");
//$hid_companies = (isset($HTTP_POST_VARS["hid_companies"])?quote_smart($HTTP_POST_VARS["hid_companies"]):"");
$trans_ptype = (isset($HTTP_POST_VARS["trans_ptype"])?quote_smart($HTTP_POST_VARS["trans_ptype"]):"");
$trans_ctype = (isset($HTTP_POST_VARS["trans_ctype"])?quote_smart($HTTP_POST_VARS["trans_ctype"]):"");
$trans_dtype = (isset($HTTP_POST_VARS["trans_dtype"])?quote_smart($HTTP_POST_VARS["trans_dtype"]):"");
$trans_atype = (isset($HTTP_POST_VARS["trans_atype"])?quote_smart($HTTP_POST_VARS["trans_atype"]):"");
$str_pass = (isset($HTTP_POST_VARS["chk_pass"])?quote_smart($HTTP_POST_VARS["chk_pass"]):"");
$str_nopass = (isset($HTTP_POST_VARS["chk_nopass"])?quote_smart($HTTP_POST_VARS["chk_nopass"]):"");
$str_approved = (isset($HTTP_POST_VARS["trans_atype"])?quote_smart($HTTP_POST_VARS["trans_atype"]):"");
$voiceid = (isset($HTTP_POST_VARS["voiceid"])?quote_smart($HTTP_POST_VARS["voiceid"]):"");
$transactionId = (isset($HTTP_POST_VARS["transactionId"])?quote_smart($HTTP_POST_VARS["transactionId"]):"");
$cnumber = (isset($HTTP_POST_VARS["cnumber"])?quote_smart($HTTP_POST_VARS["cnumber"]):"");
$radRange = (isset($HTTP_POST_VARS["radRange"])?quote_smart($HTTP_POST_VARS["radRange"]):"");
$strType = (isset($HTTP_POST_VARS["type"])?quote_smart($HTTP_POST_VARS["type"]):"");
$decline_reason=(isset($HTTP_POST_VARS['decline_reasons'])?($HTTP_POST_VARS['decline_reasons']):"");
$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$companyname = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";

  $dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
  $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
   $arrCompanies = $companyname;
  // $arrCompanies = split(",",$hid_companies);
  $strCompanyCondition = "";
  $strCheckCreditCondition = "";
  $strPendingCondition = "";
  $strApprovedCondition = "";
  $strDeclineCondition = "";
  $decline_condition="";
  $cancel_condition ="";
  $i_dec=0;
  $i_cancel=0;
  
   if($cancel_reason !=""){
  	for($i_cancel = 0;$i_cancel < count($cancel_reason);$i_cancel++) {
		if($cancel_reason[$i_cancel] !="") {
			if($cancel_condition =="") {
				$cancel_condition = " reason ='".$cancel_reason[$i_cancel]."'";
			} else {
				$cancel_condition = $cancel_condition ." or reason ='".$cancel_reason[$i_cancel]."'";
			}
		}
	}
  }

  if($decline_reason !=""){
  	for($i_dec = 0;$i_dec < count($decline_reason);$i_dec++) {
		if($decline_reason[$i_dec] !="") {
			if($decline_condition =="") {
				$decline_condition = "declinedReason ='".$decline_reason[$i_dec]."'";
			} else {
				$decline_condition = $decline_condition ." or declinedReason ='".$decline_reason[$i_dec]."'";
			}
		}
	}
  }

  for($iLoop = 0;$iLoop<count($arrCompanies);$iLoop++)
  {
  	if(Trim($arrCompanies[$iLoop]) !=""){
	if($arrCompanies[$iLoop] == "A")
	{
		break;
	}
	else
	{	
		//if($companytype!="A" || $companytrans_type != "A") {
			if($strCompanyCondition == ""){
				$strCompanyCondition .= " a.userid = $arrCompanies[$iLoop]";
			}else{
				$strCompanyCondition .= " or a.userid = $arrCompanies[$iLoop]";
			}	
		/*} else{
			if($strCompanyCondition == ""){
				$strCompanyCondition .= " userid = $arrCompanies[$iLoop]";
			}else{
				$strCompanyCondition .= " or userid = $arrCompanies[$iLoop]";
			}	
		}*/	
/*		if($strCompanyCondition == ""){
$strCompanyCondition .= " userid = $arrCompanies[$iLoop]";
}else{
$strCompanyCondition .= " or userid = $arrCompanies[$iLoop]";
}	*/
	}
	}	
  }
  if($crorcq != "")
  {
  	$strCheckCreditCondition = "checkorcard = '$crorcq'";
  }	
   
  $strConditions = "";
  
  if($strCompanyCondition != ""){
  	$strConditions .= "(".$strCompanyCondition.")";
	}
  if($strCheckCreditCondition != "")
  {
  	if($strConditions != ""){
		$strConditions .= " and $strCheckCreditCondition ";		
   	}else{
		$strConditions .= " $strCheckCreditCondition ";		
  	}
  }	
  $strTypeCondition = "";
  if($strType != ""){
	if($crorcq == "C"){
		$strTypeCondition = "accounttype ='$strType' ";
	}
	if($crorcq == "H"){
		$strTypeCondition = "cardtype ='$strType' ";
	}
  }
  if($strTypeCondition != ""){
  	if($strConditions != ""){
		$strConditions .= " and $strTypeCondition ";
	}else{
		$strConditions .= " $strTypeCondition ";
	}
  }

	$strStatusCondition = "";
	
	if($trans_dtype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'D' ";	 	
		}else{
			$strStatusCondition .= " status = 'D' ";	 	
		}
	}

	if($trans_ptype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or passStatus='PA' ";	 	
		}else{
			$strStatusCondition .= " passStatus='PA' ";	 	
		}
	}
	if($trans_atype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'A' ";	 	
		}else{
			$strStatusCondition .= " status = 'A' ";	 	
		}
	}
	if($str_approved != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'A' ";	 	
		}else{
			$strStatusCondition .= " status = 'A' ";	 	
		}
	}
	
	$str_or_query = "";

	if($strStatusCondition != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$strStatusCondition." ";
		}else{
			$str_or_query .= " ( ".$strStatusCondition." ";
		}
	}

	$strPassStatusCondition = "";

	if($str_pass != ""){ 
		if($strPassStatusCondition != ""){
			$strPassStatusCondition .= " or passStatus = 'PA' ";	 	
		}else{
			$strPassStatusCondition .= " passStatus = 'PA' ";	 	
		}
	}

	if($str_nopass != ""){ 
		if($strPassStatusCondition != ""){
			$strPassStatusCondition .= " or passStatus = 'NP' ";	 	
		}else{
			$strPassStatusCondition .= " passStatus = 'NP' ";	 	
		}
	}
		

	if($strPassStatusCondition != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$strPassStatusCondition." ";
		}else{
			$str_or_query .= " ( ".$strPassStatusCondition." ";
		}
	}
	
	if($voiceid != ""){
		if($strConditions != "") {
			$strConditions .= " and voiceAuthorizationno ='$voiceid' ";
		}else{
			$strConditions .= " voiceAuthorizationno ='$voiceid' ";	
		}
	}
	
	if($transactionId != ""){
		if($strConditions != "") {
			$strConditions .= " and transactionId = $transactionId ";
		}else{
			$strConditions .= " transactionId = $transactionId ";
		}		
	}	
	
	if($cnumber != ""){
		if($strConditions != ""){
			$strConditions .= " and CCnumber = '".etelEnc($cnumber)."' ";
		}else{
			$strConditions .= " CCnumber = '".etelEnc($cnumber)."' ";
		}
	}
	$strRadConditions = "";
	if($radRange == "S"){
		$strRadConditions = " (billingDate >= '$dateToEnter' and billingDate <= '$dateToEnter1') ";	
	} else {
		$strRadConditions = " (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ";
	}
	if($strRadConditions != ""){
		if($strConditions != ""){
			$strConditions .= " and $strRadConditions";
		}else{
			$strConditions .= $strRadConditions;
		}
		
	}
	if($decline_condition != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$decline_condition;
		}else{
			$str_or_query .= " ( ".$decline_condition;
		}
	}
	
	if($cancel_condition != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$cancel_condition;
		}else{
			$str_or_query .= " ( ".$cancel_condition;
		}
	}

	if($trans_ctype != ""){
		if($str_or_query != ""){
			$str_or_query .= " or cancelstatus ='Y' ";
		}else{
			$str_or_query .= " ( cancelstatus ='Y' ";
		}
	}


	/*if($str_pass != "" || $str_nopass != "" || $trans_ptype != "")
	{
		if($trans_dtype == "")
		{	
			if($strConditions != ""){
				$strConditions .= " and status <> 'D' ";
			}else{
				$strConditions .= " status <> 'D' ";
			}
		}

		if($trans_ctype == "")
		{	
			if($strConditions != ""){
				$strConditions .= " and cancelstatus = 'N' ";
			}else{
				$strConditions .= " cancelstatus = 'N' ";
			}
		}
	}*/

	if($str_or_query != ""){
		if($strConditions != ""){
			$strConditions .= " and $str_or_query ) ";
		}else{
			$strConditions .= " $str_or_query ) ";
		}
	}
	//print($strConditions);
	//if($companytype != "A" || $companytrans_type != "A") {
	 	$qrySelect = "select surname,name,a.address,a.city,a.state,a.zipcode from cs_transactiondetails as a,cs_companydetails as b";		
	/*}else {
		$qrySelect = "select surname,name,address,city,state,zipcode from cs_transactiondetails  ";		
	}*/
	if($strConditions != ""){
		if($companytype=="AC"){
			if ($companytrans_type == "A") {
				$qrySelect .=" where a.userid=b.userid and b.activeuser=1 and ". $strConditions;
			} else {
				$qrySelect .=" where a.userid=b.userid and b.activeuser=1 and ". $strConditions ." and  transaction_type = '$companytrans_type'";
			}
		} else if($companytype=="NC"){
			if ($companytrans_type == "A") {
				$qrySelect .=" where a.userid=b.userid and b.activeuser=0 and ". $strConditions;
			} else {
				$qrySelect .=" where a.userid=b.userid and b.activeuser=0 and ". $strConditions ." and  transaction_type = '$companytrans_type'";
			}
		} else if($companytype=="RE"){
			if ($companytrans_type == "A") {
				$qrySelect .=" where a.userid=b.userid and b.reseller_id <> '' and ". $strConditions;
			} else {
				$qrySelect .=" where a.userid=b.userid and b.reseller_id <> '' and ". $strConditions ." and  transaction_type = '$companytrans_type'";
			}
		} else if($companytype=="ET"){
			if ($companytrans_type == "A") {
				$qrySelect .=" where a.userid=b.userid and b.reseller_id is null and ". $strConditions;
			} else {
				$qrySelect .=" where a.userid=b.userid and b.reseller_id is null and ". $strConditions ." and  transaction_type = '$companytrans_type'";
			}
		} else {
			if ($companytrans_type == "A") {
				$qrySelect .=" where ". $strConditions;
			} else {
				$qrySelect .=" where a.userid=b.userid and ". $strConditions ." and  transaction_type = '$companytrans_type'";
			}
		}
	}			
	$str_orderby = isset($HTTP_POST_VARS['opt_sortorder'])?$HTTP_POST_VARS['opt_sortorder']:"surname";
	if ($qrySelect != "" ){
		$qrySelect .=" and gateway_id = -1";
	} else {
		$qrySelect .=" where 1 ";
	}
	if ($str_orderby != "" ){
		$qrySelect .=" order by $str_orderby";
	}
	//print($qrySelect);
	if(!($rstSelect = mysql_query($qrySelect,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
<html>
<head>
<link href="../Styles/text.css" rel="stylesheet">
<title>Labels</title>
<body topmargin="30" leftmargin="30" marginheight="3" onLoad="javascript:func_print('<?= $str_type?>');">
<?php
		if(!($rst_select = mysql_query($qrySelect,$cnn_cs)))
		{
			print("Can not execute query");
			exit();
		}	
		if(mysql_num_rows($rst_select)>0)
		{ 
			$i_col_count = 0;		?>
			<table width="585" border="0" cellspacing="0" cellpadding="0">
<?php		$str_col = isset($HTTP_POST_VARS['txt_columns'])?$HTTP_POST_VARS['txt_columns']:"3";
			$str_fname_order = isset($HTTP_POST_VARS['opt_order_fname'])?$HTTP_POST_VARS['opt_order_fname']:"1";
			$str_sname_order = isset($HTTP_POST_VARS['opt_order_sname'])?$HTTP_POST_VARS['opt_order_sname']:"1";
			$str_sname_font = isset($HTTP_POST_VARS['opt_font_sname'])?$HTTP_POST_VARS['opt_font_sname']:"Verdana";
			$str_sname_fontsize = isset($HTTP_POST_VARS['opt_fontsize_sname'])?$HTTP_POST_VARS['opt_fontsize_sname']:"2";
			$str_fname_font = isset($HTTP_POST_VARS['opt_font_fname'])?$HTTP_POST_VARS['opt_font_fname']:"Verdana";
			$str_fname_fontsize = isset($HTTP_POST_VARS['opt_fontsize_fname'])?$HTTP_POST_VARS['opt_fontsize_fname']:"2";
			$str_address_font = isset($HTTP_POST_VARS['opt_font_address'])?$HTTP_POST_VARS['opt_font_address']:"Verdana";
			$str_address_fontsize = isset($HTTP_POST_VARS['opt_fontsize_address'])?$HTTP_POST_VARS['opt_fontsize_address']:"2";
			$str_city_font = isset($HTTP_POST_VARS['opt_font_city'])?$HTTP_POST_VARS['opt_font_city']:"Verdana";
			$str_city_fontsize = isset($HTTP_POST_VARS['opt_fontsize_city'])?$HTTP_POST_VARS['opt_fontsize_city']:"2";	
			$str_state_font = isset($HTTP_POST_VARS['opt_font_state'])?$HTTP_POST_VARS['opt_font_state']:"Verdana";
			$str_state_fontsize = isset($HTTP_POST_VARS['opt_fontsize_state'])?$HTTP_POST_VARS['opt_fontsize_state']:"2";
			$str_pc_font = isset($HTTP_POST_VARS['opt_font_pc'])?$HTTP_POST_VARS['opt_font_pc']:"Verdana";
			$str_pc_fontsize = isset($HTTP_POST_VARS['opt_fontsize_pc'])?$HTTP_POST_VARS['opt_fontsize_pc']:"2";	
			for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
			{
				$i_col_count++;
				$str_first_name = mysql_result($rst_select,$i_loop,0);
				$str_name =  mysql_result($rst_select,$i_loop,1);
				$str_address =  mysql_result($rst_select,$i_loop,2);
				$str_city =  mysql_result($rst_select,$i_loop,3);
				$str_state =  mysql_result($rst_select,$i_loop,4);
				if($str_state !="") {
					$str_state_val = func_state_abbreviation($str_state);
				}
				$str_zip_code =  mysql_result($rst_select,$i_loop,5); 
				if($i_col_count == 1)
				{ 
					print("<table width='585' border='0' cellspacing='0' cellpadding='0'>");
					print("<tr>");
				}	
				print("<td width='186' height='72' align='left' valign='top'>");
				if ($str_fname_order == 1){
					print("<font face='$str_fname_font' size='$str_fname_fontsize'>$str_first_name</font>&nbsp;<font face='$str_sname_font' size='$str_sname_fontsize'>$str_name</font><br>");
				}else{
					print("<font face='$str_sname_font' size='$str_sname_fontsize'>$str_name</font>&nbsp;<font face='$str_fname_font' size='$str_fname_fontsize'>$str_first_name</font><br>");
				}
				print("<font face='$str_address_font' size='$str_address_fontsize'>".$str_address."</font><br>");
				print("<font face='$str_city_font' size='$str_city_fontsize'>".$str_city."</font><br>");
				print("<font face='$str_state_font' size='$str_state_fontsize'>".$str_state_val."</font>&nbsp;&nbsp;");
				print("<font face='$str_pc_font' size='$str_pc_fontsize'>".$str_zip_code."</font><br>");
				print("</td>");
				
				if($i_col_count != $str_col)
				{
					print("<td width='50'>&nbsp;</td>");
				}	
				if($i_col_count == $str_col) 
				{
					 $str_colspan = ($str_col + $str_col - 1);
					 print("</tr>");
					 print("<tr><td colspan='$str_colspan' height='30'>&nbsp;</td></tr>");
					 print("</table>");
					 $i_col_count = 0;
				}
			}
				if($i_col_count < $str_col)
				{
					$str_colspan = ($str_col+($str_col-1)) - ($i_col_count+($i_col_count));
					print("<td colspan='$str_colspan'>&nbsp;</td>");
					print("</tr>");
					$str_colspan1 = ($str_col + $str_col - 1);
					print("<tr><td colspan='$str_colspan1' height='30'>&nbsp;</td></tr>");
					print("</table>");
				}
			 ?>
		</table>
<?php	}
?>
<script language="JavaScript" type="text/JavaScript">
function func_print(str_type)
{
	if (str_type == "p")
	{
		window.print();
		window.close();
	}
}
</script>
	
</body>
</html>
