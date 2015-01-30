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
// excelout.php:	The admin page functions for downloading transactions to an excel sheet. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');
$headerInclude="transactions";
include 'includes/header.php';

 ?>
<script language="JavaScript">
function funcDownload(){
	document.frmDownload.method="post";
	document.frmDownload.action="downloadreport.php";
	document.frmDownload.submit();
}
</script>
<?php
// $txtDate=$HTTP_POST_VARS['txtDate'];
// $txtDate1 =$HTTP_POST_VARS['txtDate1'];
$export_select_list = "";
$export_select_company = "";
$strCheckCreditCondition = "";
$strConditions  = "";
$strRadConditions = "";
$strStatusCondition = "";
$order_set = "";
$str_qryconcat="";
$qrt_select_users="";
$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"tele";

$exportlist = $HTTP_POST_VARS['exportlist'];
$export_listnum = $HTTP_POST_VARS['listnum'];
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

$trans_ptype = (isset($HTTP_POST_VARS["trans_ptype"])?quote_smart($HTTP_POST_VARS["trans_ptype"]):"");
$trans_pass = (isset($HTTP_POST_VARS["trans_pass"])?quote_smart($HTTP_POST_VARS["trans_pass"]):"");
$trans_nopass = (isset($HTTP_POST_VARS["trans_nopass"])?quote_smart($HTTP_POST_VARS["trans_nopass"]):"");
$trans_atype = (isset($HTTP_POST_VARS["trans_atype"])?quote_smart($HTTP_POST_VARS["trans_atype"]):"");
$trans_dtype = (isset($HTTP_POST_VARS["trans_dtype"])?quote_smart($HTTP_POST_VARS["trans_dtype"]):"");
$trans_ctype = (isset($HTTP_POST_VARS["trans_ctype"])?quote_smart($HTTP_POST_VARS["trans_ctype"]):"");
$date_range = (isset($HTTP_POST_VARS["daterange"])?quote_smart($HTTP_POST_VARS["daterange"]):"");
$trans_entry = (isset($HTTP_POST_VARS["checkorcard"])?quote_smart($HTTP_POST_VARS["checkorcard"]):"");
$trans_order = (isset($HTTP_POST_VARS["order"])?quote_smart($HTTP_POST_VARS["order"]):"");

	if($trans_entry != "")
	{
		$strCheckCreditCondition = "checkorcard = '$trans_entry'";
	}	
	
	if($trans_pass != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or passStatus = 'PA' ";
		}else{
			$strStatusCondition .= " passStatus = 'PA' ";
		}		
	}
	if($trans_nopass != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or passStatus = 'NP' ";
		}else{
			$strStatusCondition .= " passStatus = 'NP' ";
		}		
	}
	
	if($trans_atype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'A' ";	 	
		}else{
			$strStatusCondition .= " status = 'A' ";	 	
		}
	}	
	if($trans_dtype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'D' ";	 	
		}else{
			$strStatusCondition .= " status = 'D' ";	 	
		}
	}
	if($trans_ptype != ""){
		if($strStatusCondition != ""){
			$strStatusCondition .= " or status = 'P' ";	 	
		}else{
			$strStatusCondition .= " status = 'P' ";	 	
		}
	}

	if($strCheckCreditCondition != "")
	  {
		if($strConditions != ""){
			$strConditions .= " and $strCheckCreditCondition ";		
		}else{
			$strConditions .= " $strCheckCreditCondition ";		
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

	if($date_range == "S"){
		$strRadConditions = " (billingDate >= '$dateToEnter' and billingDate <= '$dateToEnter1') ";	
	}
	if($date_range == "A"){
		$strRadConditions = " (approvaldate  >= '$dateToEnter' and approvaldate<= '$dateToEnter1') ";	
	}
	if($date_range == "O"){
		$strRadConditions = " (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ";
	}
	if($strRadConditions != ""){
		if($strConditions != ""){
			$strConditions .= " and $strRadConditions";
		}else{
			$strConditions .= $strRadConditions;
		}
		
	}

	if($trans_ctype != ""){
		if($str_or_query != ""){
			$str_or_query .= " or cancelstatus ='Y' ";
		}else{
			$str_or_query .= " ( cancelstatus ='Y' ";
		}
	}

	if($str_or_query != ""){
		if($strConditions != ""){
			$strConditions .= " and $str_or_query ) ";
		}else{
			$strConditions .= " $str_or_query ) ";
		}
	}
	/*if($trans_pass != "" || $trans_nopass != "" || $trans_ptype != "")
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


if($export_listnum) {
	for($i_list=0;$i_list <= count($export_listnum)-1;$i_list++){
		for($j_list=$i_list+1;$j_list <= count($export_listnum)-1;$j_list++){
			if($export_listnum[$i_list] > $export_listnum[$j_list]){
				$temp = $export_listnum[$i_list];
				$export_listnum[$i_list] = $export_listnum[$j_list];
				$export_listnum[$j_list] = $temp;				
			
				$export_temp = $exportlist[$i_list];
				$exportlist[$i_list] = $exportlist[$j_list];
				$exportlist[$j_list] = $export_temp;
			}
			
		}
	}
}


if($exportlist) {
	for($i_loop=0;$i_loop<count($exportlist);$i_loop++)
	{	//if($companytype=="AC"||$companytype=="NC") {
		if($companytype != "A") {	if($exportlist[$i_loop]=="phonenumber"||$exportlist[$i_loop]=="address"||$exportlist[$i_loop]=="city"||$exportlist[$i_loop]=="state"||$exportlist[$i_loop]=="country"||$exportlist[$i_loop]=="zipcode"||$exportlist[$i_loop]=="email"||$exportlist[$i_loop]=="userId"){
				$export_listval= "a.".$exportlist[$i_loop];
			}else {
				$export_listval= "a.".$exportlist[$i_loop];
			}
		} else {
			if($companytrans_type == "A") {
				$export_listval= "a.".$exportlist[$i_loop];
			} else {
				$export_listval= "a.".$exportlist[$i_loop];
			}
		}
		if($export_listval=="amount") {
			$export_listval = "Format(amount,2) as amount";
		}
		if($export_select_list =="") {
			$export_select_list = $export_listval;
		} else {
			$export_select_list = "$export_select_list,$export_listval";
		}
	}
}
if($trans_order) {
	$order_set = "order by 'surname' ASC";
}
// echo $export_select_list;
if($companyid) {
	if($companytype=="AC"){
		if ($companytrans_type == "A") {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1";
		} else {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 and  transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="NC"){
		if ($companytrans_type == "A") {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0";
		} else {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0 and  transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="RE"){
		if ($companytrans_type == "A") {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> ''";
		} else {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> '' and  transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="ET"){
		if ($companytrans_type == "A") {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null";
		} else {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null and  transaction_type = '$companytrans_type'";
		}
	} else {
		if ($companytrans_type == "A") {
			$str_qryconcat="";
		} else {
			$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and transaction_type = '$companytrans_type'";
		}
	}
	if($companyid[0]=="A") {
		$qrt_select_details = "Select $export_select_list from cs_transactiondetails"; // where transactionDate between '$dateToEnter' and '$dateToEnter1'";
	
		if($strConditions != ""){
			if($str_qryconcat!="") {
				$qrt_select_details .=$str_qryconcat." and b.gateway_id = -1 and ". $strConditions;
			} else {
				$qrt_select_details .=" as a , cs_companydetails as b where a.userid=b.userid and b.gateway_id = -1 and ". $strConditions;
			}
		}
		$qrt_select_details .= " ". $order_set;
//		print $str_qryconcat."<br>";
//		print $qrt_select_details;
//		exit();
		func_export_details($qrt_select_details,$cnn_cs,$exportlist,$trans_entry);
	} else {
		$qrt_select_details = "Select $export_select_list from cs_transactiondetails"; // where transactionDate between '$dateToEnter' and '$dateToEnter1' and userid = $export_select_company";
		for($i_loop=0;$i_loop<count($companyid);$i_loop++)
		{
			$export_select_company = $companyid[$i_loop];
			//if($str_qryconcat!="") {
				if($qrt_select_users =="") {
					$qrt_select_users =" a.userid = $export_select_company";
				}else {
					$qrt_select_users .=" or a.userid = $export_select_company";
				}
			/*} else {
				if($qrt_select_users =="") {
					$qrt_select_users =" userid = $export_select_company";
				}else {
					$qrt_select_users .=" or userid = $export_select_company";
				}
			}*/
		}	
			if($strConditions != ""){
				if($str_qryconcat!="") {
					$qrt_select_details .=$str_qryconcat." and ". $strConditions ."and ($qrt_select_users)";
				} else {
					$qrt_select_details .=" as a where ". $strConditions ."and ($qrt_select_users)";
				}
			}
			$qrt_select_details .= " ". $order_set;
		//	print $qrt_select_details;
//			exit();
			func_export_details($qrt_select_details,$cnn_cs,$exportlist,$trans_entry);
		
	}
} 			//print $strConditions;
?>
<form name="frmDownload" method="post" action="downloadreport.php" onload="document.frmDownload.submit();">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center">
	   <br><br>
		<table width="70%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Export</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" align="center"><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td align="center" valign="middle"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				Please click on the button to download the file. 
                    </font></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle"><a href="javascript:funcDownload()"><img SRC="<?=$tmpl_dir?>/images/download.jpg" width="68" height="20" border="0"></a></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">&nbsp;</td>
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
	</td>
	</tr>
</table>	
</form>
<?php
function func_export_details($qrt_select_details,$cnn_cs,$exportlist,$trans_entry) {
	$i=0;
	$data ="";
	$value = "";
	$header ="";
	if(!($qrt_select_run =mysql_query($qrt_select_details,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} else if(mysql_num_rows($qrt_select_run)== 0 ){
		$msgtodisplay="No transactions for this period";
	}
	if($exportlist)	{
		for($i_loop=0;$i_loop<count($exportlist);$i_loop++)
		{
			$export_select_list = $exportlist[$i_loop];
			$list_header_val = func_set_header($exportlist[$i_loop],$trans_entry);
			if($header =="") {
				$header = '"' ."$list_header_val". '"' ;
			} else {
				$header = "$header ," . '"' . "$list_header_val". '"' ;
			}
		}
		$header = "$header \t";		
	}
						
						
	while($show_select_val = mysql_fetch_array($qrt_select_run)) {
			$export_value="";
			$value1 ="";
			if($exportlist)	{
				for($i_loop=0;$i_loop<count($exportlist);$i_loop++)	{
					$export_value = $show_select_val["$exportlist[$i_loop]"];
					if ($exportlist[$i_loop] =="userId") {
						$qrt_select_company = "Select companyname from cs_companydetails where userid = $export_value";
						$export_value = funcGetValueByQuery($qrt_select_company,$cnn_cs);
					}
					if ($export_value =="PE" || $export_value =="P" ) {
						$export_value = "Pending";
					} elseif($export_value =="PA") {
						$export_value = "Pass";
					} elseif($export_value =="NP") {
						$export_value = "Non Pass";
					} elseif($export_value =="A") {
						$export_value = "Approved";
					} elseif($export_value =="D") {
						$export_value = "Declined";
					}  elseif($export_value =="Y") {
						$export_value = "Cancelled";
					} elseif($export_value =="N") {
						$export_value = "Not Cancelled";
					} elseif($export_value =="C") {
						$export_value = "Check";
					} elseif($export_value =="H") {
						$export_value = "Credit Card";
					}
		// Checking whether the value is number or not, is an ascii check.
					if(func_check_isnumberdot($export_value)) {
						$export_value = "^ ".$export_value;
					}
					$export_value = str_replace(",","",$export_value);
					if($value1 =="") {
						$value1 = '"' . $export_value . '"';
					} else {
						$value1 = $value1 ."," . '"' . $export_value . '"';
					}
				}
			}
			$value = $value1. '"' . "\t";
			if(!isset($value) || $value == ""){
				$value = "\t";
			}else{
				$value = '"' . $value . '"' . "\t";
			}
			$line = '"' . $value;
			$data .= trim($line)."\n";
	}
	
	   $data = str_replace("\r", "", $data);
	   $data = str_replace('"', "", $data);
		if ($data == "") {
			$data = "\n No matching records found\n";
		}
		# this line is needed because returns embedded in the data have "\r"
		# and this looks like a "box character" in Excel
		  $data = str_replace("\t", "", $data);
		  $header = str_replace("\t", "", $header);
		
			
			$str_current_path = "csv/report.csv";
		//	print $str_current_path;
			$create_file = fopen($str_current_path,'w');
		//	print $create_file;
			$file_content =  $header."\n".$data;
			fwrite($create_file,$file_content);
			fclose($create_file);
		
		
		
		# Nice to let someone know that the search came up empty.
		# Otherwise only the column name headers will be output to Excel.

		# This line will stream the file to the user rather than spray it across the screen
//		header("Content-type: application/octet-stream");
//		header("Content-Disposition: attachment; filename=excelfile.htm");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		// echo $header."\n".$data; 

		if(!($file = fopen("csv/report.csv", "r")))
		{
			print("Can not open file");
			exit();
		}	
	/*	$content = fread($file, filesize("csv/excelfile.htm"));
		$content = explode("\r\n", $content);
		fclose($file);
		$file_content = "";
		for($i=0;$i<count($content);$i++)
		{
			$file_content .= $content[$i];
		}
		print($file_content);
		if( file_exists($str_current_path)) {
			//unlink($str_current_path);
		} */
}



function func_set_header($exportlist,$trans_entry) {
	switch ($exportlist) {
		case "transactionId":
			return "Id";
		case "userId":
			return "Company Name";
		case "voiceAuthorizationno":
			return "Voice Authotrization Id";
		case "Invoiceid":
			return "Invoice Id";
		case "transactionDate":
			return "Transaction Date";
		case "billingDate":
			return "Billing Date";
		case "chequedate":
			return "Check Date";
		case "approvaldate":
			return "Approval Date";
		case "cancellationDate":
			return "Cancellation Date";
		case "name":
			return "First Name";
		case "surname":
			return "Last Name";
		case "address":
			return "Address";
		case "city":
			return "City";
		case "state":
			return "State";
		case "country":
			return "Country";
		case "zipcode":
			return "Zipcode";
		case "phonenumber":
			return "Telephone Number";
		case "email":
			return "Email Address";
		case "checkorcard":
			return "Transaction Type";
		case "cardtype":
			return "Card Type";
		case "checktype":
			return "Check Type";
		case "Checkto":
			return "Pay To";
		case "validupto":
			return "Card Expiry Date";
		case "CCnumber":
			if($trans_entry =="C") {
				return "Check";
			} else {
				return "Card Number";
			}
		case "cvv":
			return "CVV Number";
		case "amount":
			return "Amount";
		case "bankname":
			return "Bank Name";
		case "bankroutingcode":
			return "Bank Routing Code";
		case "accounttype":
			return "Account Type";
		case "bankaccountnumber":
			return "Account Number";
		case "memodet":
			return "Memo Details";
		case "misc":
			return "Miscellaneous";
		case "shippingTrackingno":
			return "Shipping Number";
		case "socialSecurity":
			return "Social Security Number";
		case "licensestate":
			return "Licence State";
		case "driversLicense":
			return "Drivers Licence Number";
		case "ipaddress":
			return "I.P. Address";
		case "passStatus":
			return "Pass Status";
		case "status":
			return "Transaction Status";
		case "cancelstatus":
			return "Cancellation Status";
		case "reason":
			return "Cancellation Reason";
		case "other":
			return "Other Cancel Reasons";
		default :
			return "";
	}

}
?>
<?php
	include("includes/footer.php");
?>