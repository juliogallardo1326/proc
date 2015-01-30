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
$str_pass = (isset($HTTP_POST_VARS["chk_pass"])?quote_smart($HTTP_POST_VARS["chk_pass"]):"");
$str_nopass = (isset($HTTP_POST_VARS["chk_nopass"])?quote_smart($HTTP_POST_VARS["chk_nopass"]):"");
$voiceid = (isset($HTTP_POST_VARS["voiceid"])?quote_smart($HTTP_POST_VARS["voiceid"]):"");
$transactionId = (isset($HTTP_POST_VARS["transactionId"])?quote_smart($HTTP_POST_VARS["transactionId"]):"");
$cnumber = (isset($HTTP_POST_VARS["cnumber"])?quote_smart($HTTP_POST_VARS["cnumber"]):"");
$radRange = (isset($HTTP_POST_VARS["radRange"])?quote_smart($HTTP_POST_VARS["radRange"]):"");
$strType = (isset($HTTP_POST_VARS["type"])?quote_smart($HTTP_POST_VARS["type"]):"");
$decline_reason=(isset($HTTP_POST_VARS['decline_reasons'])?($HTTP_POST_VARS['decline_reasons']):"");
$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";

$companyname = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
if($companyname=="") {
	$companyname = (isset($HTTP_POST_VARS['activecompanyname'])?($HTTP_POST_VARS['activecompanyname']):"");
}
if($companyname=="") {
	$companyname = (isset($HTTP_POST_VARS['nonactivecompanyname'])?($HTTP_POST_VARS['nonactivecompanyname']):"");
}

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
				$cancel_condition = $cancel_condition ." and reason ='".$cancel_reason[$i_cancel]."'";
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
				$decline_condition = $decline_condition ."and declinedReason ='".$decline_reason[$i_dec]."'";
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
		if($companytype!="A") {
			if($strCompanyCondition == ""){
				$strCompanyCondition .= " a.userid = $arrCompanies[$iLoop]";
			}else{
				$strCompanyCondition .= " or a.userid = $arrCompanies[$iLoop]";
			}	
		} else{
			if($strCompanyCondition == ""){
				$strCompanyCondition .= " userid = $arrCompanies[$iLoop]";
			}else{
				$strCompanyCondition .= " or userid = $arrCompanies[$iLoop]";
			}	
		}	
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
	
	if($trans_ptype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'P' ";
		}else{
			$strStatusCondition .= " status = 'P' ";
		}		
	}
	if($trans_dtype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'D' ";	 	
		}else{
			$strStatusCondition .= " status = 'D' ";	 	
		}
	}

	if($strStatusCondition != ""){
		if($strConditions != ""){
			$strConditions .= " and (".$strStatusCondition.")";
		}else{
			$strConditions .= "(".$strStatusCondition.")";
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
		if($strConditions != ""){
			$strConditions .= " and (".$strPassStatusCondition.")";
		}else{
			$strConditions .= "(".$strPassStatusCondition.")";
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
	}
	if($radRange == "A"){
		$strRadConditions = " (approvaldate  >= '$dateToEnter' and approvaldate<= '$dateToEnter1') ";	
	}
	if($radRange == "O"){
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
		if($strConditions != ""){
			$strConditions .= " and ".$decline_condition;
		}else{
			$strConditions .= $decline_condition;
		}
	}
	
	if($cancel_condition != ""){
		if($strConditions != ""){
			$strConditions .= " and ".$cancel_condition;
		}else{
			$strConditions .= $cancel_condition;
		}
	}

	if($trans_ctype != ""){
		if($strConditions != ""){
			$strConditions .= " and cancelstatus ='Y' ";
		}else{
			$strConditions .= " cancelstatus ='Y' ";
		}
	}
	if($companytype=="AC"||$companytype=="NC") {
	 	$qrySelect = "select surname,name,a.address,a.city,a.state,a.zipcode from cs_transactiondetails as a,cs_companydetails as b";		
	}else {
		$qrySelect = "select surname,name,address,city,state,zipcode from cs_transactiondetails  ";		
	}
	if($strConditions != ""){
		if($companytype=="AC"){
			$qrySelect .=" where a.userid=b.userid and b.activeuser=1 and ". $strConditions;
		} else if($companytype=="NC"){
			$qrySelect .=" where a.userid=b.userid and b.activeuser=0 and ". $strConditions;
		}else {
			$qrySelect .=" where ". $strConditions;
		}
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
			$i_col_count = 1;		?>
			<table width="585" border="0" cellspacing="0" cellpadding="0">
<?php		$str_col = 3;	
			for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
			{
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
					print("<table width='585' border='1' cellspacing='0' cellpadding='0'>");
					print("<tr>");
				}	
				print("<td width='186' height='72' align='left' valign='top'>");
				print("<font face='verdana' size='2'>$str_name&nbsp;$str_first_name</font><br>");
				print("<font face='verdana' size='2'>".$str_address."</font><br>");
				print("<font face='verdana' size='2'>".$str_city."</font><br>");
				print("<font face='verdana' size='2'>".$str_state_val."</font>&nbsp;&nbsp;");
				print("<font face='verdana' size='2'>".$str_zip_code."</font><br>");
				print("</td>");
				$i_col_count++;
				if($i_col_count !=4)
				{
					print("<td width='50'>&nbsp;</td>");
				}	
				if($i_col_count == 4) 
				{
					 print("</tr>");
					 print("<tr><td colspan='5' height='30'>&nbsp;</td></tr>");
					 print("</table>");
					 $i_col_count = 1;
				}
			}
				if($i_col_count ==2)
				{
					print("<td colspan='3'>&nbsp;</td>");
					print("</tr>");
				}
				if($i_col_count ==3)
				{
					print("<td>&nbsp;</td>");
					print("</tr>");
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
