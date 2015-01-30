<?php
$allowBank=true;
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');
//include '../includes/function1.php';
//require_once('../includes/function2.php');
$headerInclude="transactions";
include 'includes/header.php';
include '../includes/integration.php';


$display_test_transactions = (isset($_GET['display_test_transactions'])?quote_smart($_GET['display_test_transactions']):"");

$tinc = 50;
$curinc = 0;

$trans_table_name = "cs_transactiondetails";
if($display_test_transactions == 1) $trans_table_name = "cs_test_transactiondetails";

if($_POST['tinc']) $tinc = $_POST['tinc'];
if($_POST['curinc']) $curinc = $_POST['curinc'];
if($_POST['next']) $curinc+=$tinc;
if($_POST['last']) $curinc-=$tinc;
if($curinc<0) $curinc=0;

 $reason="";
$i_num_records_per_page = (isset($_GET["cbo_num_records"])?quote_smart($_GET["cbo_num_records"]):"20");
$i_lower_limit = (isset($_GET["lower_limit"])?quote_smart($_GET["lower_limit"]):"0");
if($i_lower_limit < 0)
	$i_lower_limit = 0;
$transID = quote_smart($_POST['TransactionId']);
if(($_POST['Submit'] == "Issue Refund"))
{
	$refund_type = "Administration Refund";
	if($adminInfo['li_level'] == 'bank') $refund_type = ucfirst($adminInfo['username'])." Refund";
	$etel_debug_mode = 0;
	$error_msg = exec_refund_request($transID,$refund_type,"");

}
if(0&&($_POST['Submit'] == "Remove Refund"))
{
	$qry_details="UPDATE $trans_table_name SET `cancelstatus` = 'N', `reason` = '' WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}
if(($_POST['Submit'] == "Set Chargeback") )
{
	$qry_details="UPDATE $trans_table_name SET `td_is_chargeback` = '1' WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}
if(($_POST['Submit'] == "Remove Chargeback"))
{
	$qry_details="UPDATE $trans_table_name SET `td_is_chargeback` = '0'  WHERE `transactionId` = '$transID'";
	$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}
if(($_POST['Submit'] == "Cancel Rebill"))
{
	$trans = new transaction_class(false);
	$trans->pull_transaction($transID);
	$status = $trans->process_cancel_request(array("actor"=>'Administrator'));
}
?>
<script language="JavaScript" type="text/JavaScript">
var num_records = "<?= $i_num_records_per_page?>";
var lower_limit = 0;
function func_submit(i_id)
{
	obj_form = document.frmResult;
	obj_form.method="post";
	obj_form.lower_limit.value="<?= $i_lower_limit ?>";
	obj_form.action="viewreportpage.php?id="+i_id;
	obj_form.submit();
}

function updateTransaction()
{
	obj_form = document.frmResult;
	obj_form.method="post";
	obj_form.lower_limit.value="<?= $i_lower_limit ?>";
	return true;
	//obj_form.submit();
}

function showPreviousPage()
{
	obj_form = document.frmResult;
	num_records = obj_form.cbo_num_records[obj_form.cbo_num_records.selectedIndex].value;
	lower_limit = parseInt(<?= $i_lower_limit?>) - parseInt(num_records);
	obj_form.method="post";
	obj_form.lower_limit.value=lower_limit;
	obj_form.task.value="Previous";
	obj_form.action="reportbottom1.php";
	obj_form.submit();
}

function showNextPage()
{
	obj_form = document.frmResult;
	lower_limit = parseInt(<?= $i_lower_limit?>) + parseInt(num_records);
	obj_form.method="post";
	obj_form.lower_limit.value=lower_limit;
	obj_form.task.value="Next";
	obj_form.action="reportbottom1.php";
	obj_form.submit();
}

function setNumRecords()
{
	obj_form = document.frmResult;
}
</script>
<?php

// $txtDate = (isset($_GET["txtDate"])?quote_smart($_GET["txtDate"]):"");
// $txtDate1 = (isset($_GET["txtDate1"])?quote_smart($_GET["txtDate1"]):"");

$search_date_type = "transactionDate";

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$is_ecommerce = true;

$companytrans_type = "A";
$tele_nontele_type = "A";
$bank_id = isset($_GET['bank_id'])?quote_smart($_GET['bank_id']):"A";


$str_task = (isset($_GET["task"])?quote_smart($_GET["task"]):"");
$i_from_year = (isset($_GET["opt_from_year"])?quote_smart($_GET["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_GET["opt_from_month"])?quote_smart($_GET["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_GET["opt_from_day"])?quote_smart($_GET["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_GET["opt_to_year"])?quote_smart($_GET["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_GET["opt_to_month"])?quote_smart($_GET["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_GET["opt_to_day"])?quote_smart($_GET["opt_to_day"]):$i_to_day);
$str_qryconcat="";
$crorcq = (isset($_GET["crorcq"])?quote_smart($_GET["crorcq"]):"");
$str_type =(isset($_GET['type'])?quote_smart($_GET['type']):"");
$str_firstname =(isset($_GET['firstname'])?quote_smart($_GET['firstname']):"");
$str_lastname =(isset($_GET['lastname'])?quote_smart($_GET['lastname']):"");
$str_telephone =(isset($_GET['telephone'])?quote_smart($_GET['telephone']):"");
$trans_pass =(isset($_GET['trans_pass'])?quote_smart($_GET['trans_pass']):"");
$trans_nopass =(isset($_GET['trans_nopass'])?quote_smart($_GET['trans_nopass']):"");
$hid_companies = (isset($_GET['hid_companies'])?quote_smart($_GET['hid_companies']):"");
$trans_ptype = (isset($_GET['trans_ptype'])?quote_smart($_GET['trans_ptype']):"");
$trans_ctype = (isset($_GET['trans_ctype'])?quote_smart($_GET['trans_ctype']):"");
$trans_atype = (isset($_GET['trans_atype'])?quote_smart($_GET['trans_atype']):"");
$trans_dtype = (isset($_GET['trans_dtype'])?quote_smart($_GET['trans_dtype']):"");
$email = (isset($_GET['email'])?quote_smart($_GET['email']):"");
$transactionId = (isset($_GET['transactionId'])?quote_smart($_GET['transactionId']):"");
$check_number = (isset($_GET['check_number'])?quote_smart($_GET['check_number']):"");
$credit_number = (isset($_GET['credit_number'])?quote_smart($_GET['credit_number']):"");
$account_number = (isset($_GET['account_number'])?quote_smart($_GET['account_number']):"");
$routing_code = (isset($_GET['routing_code'])?quote_smart($_GET['routing_code']):"");
$radRange = (isset($_GET['radRange'])?quote_smart($_GET['radRange']):"");
$decline_reason1 = (isset($_GET['decline_reasons1'])?($_GET['decline_reasons1']):"");
$recur_transaction = (isset($_GET['recur_transaction'])?quote_smart($_GET['recur_transaction']):"");
$untracked_orders = (isset($HTTP_GET_VARS['untracked_orders'])?quote_smart($HTTP_GET_VARS['untracked_orders']):"");


if ($decline_reason1 != "") {
	$decline_reason = split(",", $decline_reason1);
} else {
	$decline_reason = (isset($_GET['decline_reasons'])?($_GET['decline_reasons']):"");
}

$cancel_reason1 = (isset($_GET['cancel_reasons1'])?($_GET['cancel_reasons1']):"");
$trans_chargeback = (isset($_GET['trans_chargeback'])?($_GET['trans_chargeback']):"");

if ($cancel_reason1 != "") {
	$cancel_reason = split(",", $cancel_reason1);
} else {
	$cancel_reason = (isset($_GET['cancel_reasons'])?($_GET['cancel_reasons']):"");
}
$companytype = isset($_GET['companymode'])?$_GET['companymode']:"";

$companyid = explode("|", $_GET['userIdList']);

if($companyid == "") {
	$outhtml="y";
	$msgtodisplay="Please select a company";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
} else {
if ($tele_nontele_type == "T") {
	$is_ecommerce =  false;
} else if ($companytrans_type == "tele") {
	$is_ecommerce = false;
}


$arrCompanies = $companyid;
$companyids = "";   
$cancelReasons = "";   
$declineReasons = "";   
$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
  $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
 // $arrCompanies = split(",",$hid_companies);
  $strCompanyCondition = "";
  $strCheckCreditCondition = "";
  $strPendingCondition = "";
  $strApprovedCondition = "";
  $strDeclineCondition = "";
  $decline_condition="";
  $cancel_condition ="";
  $strBankCondition = "";
$app_aud="";
$app_eur="";
$app_cad="";
$app_usd="";
$app_gbp="";
$tot_aud="";
$tot_eur="";
$tot_cad="";
$tot_usd="";
$tot_gbp="";
  $i_dec=0;
  $i_cancel=0;
  $trans_total_count=0;
  $i_count = 0;
  for($iLoop = 0;$iLoop<count($arrCompanies);$iLoop++)
  {
	if ($companyids1 == "") {
		$companyids .= $arrCompanies[$iLoop] . ",";
	}
	if(Trim($arrCompanies[$iLoop]) !=""){
		if($arrCompanies[$iLoop] == "A")
		{
			if ($companytrans_type == "A" && $tele_nontele_type == "A") {
				$is_ecommerce = false;
			}
			break;
		}
		else
		{	
			if ($i_count == 0) {
				if (func_get_value_of_field(1,"cs_companydetails","transaction_type","userid",$arrCompanies[$iLoop]) == "tele") {
					$is_ecommerce = false;
					$i_count++;
				}
			}
			//if($companytype!="A" || $companytrans_type != "A" || $tele_nontele_type != "A" || $bank_id != "A") {
				if($strCompanyCondition == ""){
					$strCompanyCondition .= " a.userid = '$arrCompanies[$iLoop]'";
				}else{
					$strCompanyCondition .= " or a.userid = '$arrCompanies[$iLoop]'";
				}	
			/*} else{
				if($strCompanyCondition == ""){
					$strCompanyCondition .= " userid = $arrCompanies[$iLoop]";
				}else{
					$strCompanyCondition .= " or userid = $arrCompanies[$iLoop]";
				}	
			}*/
		}
	}	
  }
  if ($companyids1 == "") {
	  $companyids = substr($companyids, 0, strlen($companyids) - 1);
  } else {
	  $companyids = $companyids1;
  }

  if($cancel_reason !=""){
	for($i_cancel = 0;$i_cancel < count($cancel_reason);$i_cancel++) {
		if ($cancel_reason1 == "") {
			$cancelReasons .= $cancel_reason[$i_cancel] . ",";
		}
		if($cancel_reason[$i_cancel] !="") {
			if($cancel_condition =="") {
				$cancel_condition = "reason ='".$cancel_reason[$i_cancel]."'";
			} else {
				$cancel_condition = $cancel_condition ." or reason ='".$cancel_reason[$i_cancel]."'";
			}
		}
	}
	  if ($cancel_reason1 == "") {
		  $cancelReasons = substr($cancelReasons, 0, strlen($cancelReasons) - 1);
	  } else {
		  $cancelReasons = $cancel_reason1;
	  }
  }

$str_or_query = "";
  if($crorcq != "")
  {
	  $strCheckCreditCondition = "checkorcard = '$crorcq'";
	  if($str_type != "A")
	  {
		if($crorcq == "C")
		{
		  if($str_type == "S")
		  {
			$strCheckCreditCondition .= " and accounttype='savings' ";
		  }
		  else if($str_type == "C")
		  {
			$strCheckCreditCondition .= " and accounttype='checking' ";
		  }
		}
		else if($crorcq == "H")
		{
		  if($str_type == "M")
		  {
			$strCheckCreditCondition .= " and cardtype='Master' ";
		  }
		  else if($str_type == "V")
		  {
			$strCheckCreditCondition .= " and cardtype='Visa' ";
		  }
		}
	  }
  }	
  $strConditions = "";
  if($bank_id != 'A' && $bank_id != -1 && $bank_id)
  {

			$strBankCondition = " bank_id = '$bank_id'";
		
  }	
   
  
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
  if($strBankCondition != "")
  {
	if($strConditions != ""){
		$strConditions .= " and ($strBankCondition) ";		
	}else{
		$strConditions .= " ($strBankCondition) ";		
	}
  }	
	
	if($untracked_orders != ""){
		if($strConditions == ""){
			$strConditions .= " a.td_tracking_id is null and `status`= 'A' AND `cancelstatus` = 'N' "; 
		}
		else{
			$strConditions .= " and a.td_tracking_id is null and `status`= 'A' AND `cancelstatus` = 'N' "; 
		}
	}

	if($strStatusCondition != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$strStatusCondition." ";
		}else{
			$str_or_query .= " (".$strStatusCondition." ";
		}
	}
	$strApprovalConditions = "";
	if($trans_dtype != ""){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or status = 'D' ";	 	
		}else{
			$strApprovalConditions .= " status = 'D' ";	 	
		}
	}	
	if($trans_atype != ""){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or status = 'A' ";	 	
		}else{
			$strApprovalConditions .= " status = 'A' ";	 	
		}
	}
	if($trans_ptype != ""){
		if($strApprovalConditions != ""){
			$strApprovalConditions .= " or status = 'P' ";	 	
		}else{
			$strApprovalConditions .= " status = 'P' ";	 	
		}
	}

	if($strApprovalConditions != ""){
		if($str_or_query != ""){
			$str_or_query .= " or ".$strApprovalConditions." ";
		}else{
			$str_or_query .= " (".$strApprovalConditions." ";
		}
	}

	if($str_firstname != ""){
		if($strConditions != "") {
			$strConditions .= " and name ='$str_firstname' ";
		}else{
			$strConditions .= " name ='$str_firstname' ";	
		}
	}

	if($str_lastname != ""){
		if($strConditions != "") {
			$strConditions .= " and surname ='$str_lastname' ";
		}else{
			$strConditions .= " surname ='$str_lastname' ";	
		}
	}

	if($str_telephone != ""){
		//if($companytype!="A") {
			if($strConditions != "") {
				$strConditions .= " and a.phonenumber ='$str_telephone' ";
			}else{
				$strConditions .= " a.phonenumber ='$str_telephone' ";	
			}
		/*} else {
			if($strConditions != "") {
				$strConditions .= " and phonenumber ='$str_telephone' ";
			}else{
				$strConditions .= " phonenumber ='$str_telephone' ";	
			}
		}*/
	}

	if($email != ""){
		if($strConditions != "") {
			$strConditions .= " and a.email ='$email' ";
		}else{
			$strConditions .= " a.email ='$email' ";	
		}
	}
	
	if($transactionId != ""){
		if($strConditions != "") {
			$strConditions .= " and reference_number = '$transactionId' ";
		}else{
			$strConditions .= " reference_number = '$transactionId' ";
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
	
	if($trans_ctype != "" || $_GET['Submit']=="View Refunds"){
		$search_date_type = 'cancellationDate';
		if($strConditions != ""){
			$strConditions .= " and cancelstatus ='Y' ";
		}else{
			$strConditions .= " cancelstatus ='Y' ";
		}
	}
	  if($trans_chargeback || $_GET['Submit']=="View Chargebacks"){
		$search_date_type = 'cancellationDate';
		if($strConditions != ""){
			$strConditions .= " and td_is_chargeback ='1' ";
		}else{
			$strConditions .= " td_is_chargeback ='1' ";
		}
	}
	
	if($recur_transaction == "1")
		{	
			if($strConditions != ""){
				$strConditions .= " and td_is_a_rebill = '1' ";
			}else{
				$strConditions .= " td_is_a_rebill = '1' ";
			}
		}
	if($credit_number != ""){
		if($strConditions != ""){
			$strConditions .= " and checkorcard = 'H' and CCnumber = '".etelEnc($credit_number)."' ";
		}else{
			$strConditions .= " checkorcard = 'H' and CCnumber = '".etelEnc($credit_number)."' ";
		}
	} else if($check_number != ""){
		if ($account_number == "" || $routing_code == "") {
			$outhtml="y";
			$msgtodisplay="Please enter the account number and bank routing code";
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
		if($strConditions != ""){
			$strConditions .= " and checkorcard = 'C' and CCnumber = '".etelEnc($check_number)."' and bankaccountnumber = '$account_number' and bankroutingcode = '$routing_code' ";
		}else{
			$strConditions .= " checkorcard = 'C' and CCnumber = '".etelEnc($check_number)."' and bankaccountnumber = '$account_number' and bankroutingcode = '$routing_code' ";
		}
	}

	$strRadConditions = "";
	if($radRange == "S"){
		$strRadConditions = " (billingDate >= '$dateToEnter' and billingDate <= '$dateToEnter1') ";	
	} else {
		$strRadConditions = " ($search_date_type  >= '$dateToEnter' and  $search_date_type <= '$dateToEnter1') ";
	}
	if($str_firstname == "" && $str_lastname == "" && $str_telephone == "" && $email == "" && $transactionId == "" && $check_number == "" && $credit_number == "" && $account_number == "" && $routing_code == "")
	{
		if($strRadConditions != ""){
			if($strConditions != ""){
				$strConditions .= " and $strRadConditions";
			}else{
				$strConditions .= $strRadConditions;
			}
			
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

	if($str_or_query != ""){
		if($strConditions != ""){
			$strConditions .= " and $str_or_query ) ";
		}else{
			$strConditions .= " $str_or_query ) ";
		}
	}
	//print($strConditions);
	//if($companytype!="A" || $companytrans_type != "A" || $tele_nontele_type != "A" || $bank_id != "A"){
		$qrySelect = "select transactionId from $trans_table_name ";		

	$qrt_select_total ="select sum(amount*(`td_bank_recieved` = 'yes') ),SUM(`td_bank_recieved` = 'yes') from $trans_table_name ";
	$qrt_select_approvedtotal ="select sum(amount),count(*) from $trans_table_name ";
	$qrt_approved_amount=" select sum(amount),currencytype from $trans_table_name "; 
  	 $qrt_total_currselect ="select sum(amount),currencytype  from $trans_table_name ";


	
	if($strConditions != ""){
		if($companytype=="AC"){
			if ($companytrans_type == "A") {
				if ($tele_nontele_type == "A") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 ";
				} else if ($tele_nontele_type == "T") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 ";
				} else if ($tele_nontele_type == "E") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 ";
				}
			} else {
				$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 and  transaction_type = '$companytrans_type'";
			}
		} else if ($companytype=="NC"){
			if ($companytrans_type == "A") {
				if ($tele_nontele_type == "A") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0";
				} else if ($tele_nontele_type == "T") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0";
				} else if ($tele_nontele_type == "E") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0";
				}
			} else {
				$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0 and  transaction_type = '$companytrans_type'";
			}
		} else if ($companytype=="RE"){
			if ($companytrans_type == "A") {
				if ($tele_nontele_type == "A") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> ''";
				} else if ($tele_nontele_type == "T") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> ''";
				} else if ($tele_nontele_type == "E") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> ''";
				}
			} else {
				$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> '' and  transaction_type = '$companytrans_type'";
			}
		} else if ($companytype=="ET"){
			if ($companytrans_type == "A") {
				if ($tele_nontele_type == "A") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null";
				} else if ($tele_nontele_type == "T") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null";
				} else if ($tele_nontele_type == "E") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null";
				}
			} else {
				$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null and  transaction_type = '$companytrans_type'";
			}
		} else {
			if ($companytrans_type == "A") {
				if ($tele_nontele_type == "A") {
					if ($bank_id == "A") {
						$str_qryconcat="";
					} else {
						$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid";
					}
				} else if ($tele_nontele_type == "T") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid";
				} else if ($tele_nontele_type == "E") {
					$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid";
				}
			} else {
				$str_qryconcat=" as a,cs_companydetails as b where a.userid=b.userid and transaction_type = '$companytrans_type'";
			}
		}
		if($str_qryconcat!="") {
			$qrySelect .=$str_qryconcat." $bank_sql_limit and ". $strConditions;
			$qrt_select_total .=$str_qryconcat." $bank_sql_limit and ". $strConditions;
			$qrt_approved_amount .= $str_qryconcat." and status='A' and cancelstatus='N' $bank_sql_limit and ". $strConditions." group by a.currencytype";
			$qrt_currency_totalamount=$qrt_total_currselect.$str_qryconcat." $bank_sql_limit and " . $strConditions." group by a.currencytype";
	
			$qrt_select_approvedtotal.=$str_qryconcat." and status='A' and cancelstatus='N' $bank_sql_limit  and ". $strConditions;
		} else {
			$qrySelect .=" as a,cs_companydetails as b where a.userid=b.userid $bank_sql_limit and ". $strConditions;
			$qrt_select_total .=" as a,cs_companydetails as b where a.userid=b.userid $bank_sql_limit  and ". $strConditions;
			$qrt_approved_amount .= " as a,cs_companydetails as b where a.userid=b.userid and status='A' and cancelstatus='N' $bank_sql_limit  and " . $strConditions." group by a.currencytype";
			$qrt_currency_totalamount =$qrt_total_currselect." as a,cs_companydetails as b where a.userid=b.userid $bank_sql_limit  and " . $strConditions." group by a.currencytype";
			
			//changed
			$qrt_select_approvedtotal.=" as a,cs_companydetails as b where a.userid=b.userid and status='A' and cancelstatus='N' $bank_sql_limit  and ". $strConditions;
		}
		//print($qrt_select_total);
//		$qrySelect .=" where ". $strConditions;
//		$qrt_select_total .=" where ". $strConditions;
	}
	$str_select_query = $qrySelect . " ORDER BY $search_date_type DESC limit $curinc, $tinc";
	//print $qrt_select_usd;
	if(!($show_total_val =sql_query_read($qrt_select_total)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>$qrt_select_total");

	}
	if(!($rstSelect = sql_query_read($str_select_query,1)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	//print($str_select_query);
	
	//print($qrt_select_approvedtotal);	
	if(!($show_approvedtotal_val = sql_query_read($qrt_select_approvedtotal,1)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	
	
	 if(!($rst_approved =sql_query_read($qrt_approved_amount)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else{
		$num_select=mysql_num_rows($rst_approved);
		for($iloop=0;$iloop<$num_select;$iloop++)
		{
			$rst_approvedcurrency=mysql_fetch_array($rst_approved);
			$currency_type=$rst_approvedcurrency[1];
			if($currency_type=='AUD')
			{
				$app_aud+=$rst_approvedcurrency[0];
			}
			elseif($currency_type=='GBP')
			{
				$app_gbp+=$rst_approvedcurrency[0];
			}
			elseif($currency_type=='EUR')
			{
				$app_eur+=$rst_approvedcurrency[0];
			}
			elseif($currency_type=='CAD')
			{
				$app_cad+=$rst_approvedcurrency[0];
			}
			else
			{
				$app_usd+=$rst_approvedcurrency[0];
			}
		}
		
	}
	//procedure to find total currency amount
	
	 if(!($rst_currency =sql_query_read($qrt_currency_totalamount)))
	{			
		dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else{
		$num_selecttot=mysql_num_rows($rst_currency);
		for($iloop=0;$iloop<$num_selecttot;$iloop++)
		{
			$rst_currencytot=mysql_fetch_array($rst_currency);
			$currency_type=$rst_currencytot[1];
			if($currency_type=='AUD')
			{
				$tot_aud+=$rst_currencytot[0];
			}
			elseif($currency_type=='GBP')
			{
				$tot_gbp+=$rst_currencytot[0];
			}
			elseif($currency_type=='EUR')
			{
				$tot_eur+=$rst_currencytot[0];
			}
			elseif($currency_type=='CAD')
			{
				$tot_cad+=$rst_currencytot[0];
			}
			else
			{
				$tot_usd+=$rst_currencytot[0];
			}
		}
		
	}
	//procedure to show find the output string
	if($tot_aud!=""){
	 	$str_aud="AUD :" .formatMoney($app_aud)."/".formatMoney($tot_aud);
	 }else{
	 	$str_aud="";
	 }
	 if($tot_cad!=""){
	 	$str_cad="CAD :" .formatMoney($app_cad)."/".formatMoney($tot_cad);
	 }else{
	 	$str_cad="";
	 }
	 if($tot_eur!=""){
	 	$str_eur="EUR :" .formatMoney($app_eur)."/".formatMoney($tot_eur);
	 }else{
	 	$str_eur="";
	 }
	 if($tot_usd!=""){
	 	$str_usd="USD :" .formatMoney($app_usd)."/".formatMoney($tot_usd);
	 }else{
	 	$str_usd="";
	 }
	 if($tot_gbp!=""){
	 	$str_gbp="GBP :" .formatMoney($app_gbp)."/".formatMoney($tot_gbp);
	 }else{
	 	$str_gbp="";
	 }
	
	 $trans_total_amount = mysql_result($show_total_val,0,0);
	 $trans_total_count = mysql_result($show_total_val,0,1);
	 //changed 
	 $trans_approvedtotal_amount = mysql_result($show_approvedtotal_val,0,0);
	 $trans_approvedtotal_count = mysql_result($show_approvedtotal_val,0,1);
	 
	 
	 $i_upper_limit = ($i_lower_limit + $i_num_records_per_page) > $trans_total_count ? $trans_total_count : ($i_lower_limit + $i_num_records_per_page)
 ?>
<!-- Report starts from here -->
<?php		
			if(mysql_num_rows($rstSelect)>0)
			{
	?>
<style type="text/css">
<!--
.style3 {font-size: 1}
.tdbdr {border-bottom:1px solid black;}
-->
      </style>
<br> 
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0" > 
  <tr> 
    <td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>/images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td> 
    <td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>/images/menucenterbg.gif" ><span class="whitehd">Transaction&nbsp;Details</span></td> 
    <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td> 
    <td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>/images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td> 
    <td height="22" align="right" valign="top" background="<?=$tmpl_dir?>/images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td> 
  </tr> 
  <tr> 
    <td class="lgnbd" colspan="5"><table  cellpadding='0' cellspacing='0' width='100%' border="0" valign="left" ID='Table1'> 
        <tr> 
          <td colspan="13" align="right"><div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><strong> 
              <?=$error_msg?> 
              </strong></font>&nbsp;&nbsp; </div></td> 
        </tr> 
        <tr> 
          <td colspan="13"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><strong> 
            <!-- changed --> 
            <?php 	print "&nbsp;Total amount is : ".formatMoney($trans_approvedtotal_amount)."/".formatMoney($trans_total_amount)." and total records are: $trans_approvedtotal_count/$trans_total_count are Approved" ?> 
            </strong></font></td> 
        </tr> 
        <tr> 
          <td colspan="13"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><strong> 
            <!-- changed --> 
            <?php  	print "Amount in &nbsp;". $str_aud ."&nbsp;&nbsp;". $str_cad ."&nbsp;&nbsp;".$str_eur. "&nbsp;&nbsp;".$str_gbp. "&nbsp;&nbsp;".$str_usd   ; ?> 
            </strong></font></td> 
        </tr> 
        <tr height='20' bgcolor='#CCCCCC'> 
          <td align='left' class='cl1'><span class="subhd">Reference Number</span></td> 
          <td align='left' class='cl1'><span class="subhd">Service / Company</span></td> 
          <td align='left' class='cl1'><span class="subhd">Name</span><span class="subhd"></span></td> 
          <td align='left' class='cl1'><span class="subhd">Type</span></td> 
          <td align='left' class='cl1'><span class="subhd">Amount</span></td> 
          <td align='left' class='cl1'><span class="subhd">Tracking </span></td> 
          <td align='center' class='cl1'><span class="subhd">Status </span><span class="subhd"></span></td> 
          <td align='left' class='cl1'><span class="subhd">Cancelled</span></td> 
          <td align='center' class='cl1'><span class="subhd">Refund Reason </span></td> 
          <td width="100" align='center' class='cl1'><span class="subhd">Sent to Bank </span></td> 
        </tr> 
        <?php
						for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++)
						{ 
							$iTransactionId = mysql_result($rstSelect,$iLoop,0);
							$transactionInfo = getTransactionInfo($iTransactionId,$display_test_transactions);
							
							
								$str_processingcurency='USD';
							if($transactionInfo==-1)
							{
							$transactionInfo = array();
							 $transactionInfo['companyname'] = "INVALID TRANSACTION";
							 $transactionInfo['reference_number'] = "INVALID TRANSACTION";
							 $transactionInfo['companyname'] = "INVALID TRANSACTION";
							
							}
							 ?> 
        <tr height='30' > 
          <form id="frmUpdate<?=$iTransactionId ?>" name="frmUpdate<?=$iTransactionId ?>" method="post" action=""> 
            <input name="TransactionId" type="hidden" value="<?=$iTransactionId ?>"> 
            <td align='center'  class='cl1'><font face='verdana' size='1'><a href="viewreportpage.php?id=<?=$iTransactionId?>&test=<?=$display_test_transactions?>" class="link1">&nbsp; 
              <?=$transactionInfo['reference_number']?> 
              </a>&nbsp;<br> 
              <?php print func_get_date_time_12hr($transactionInfo['transactionDate']);?></font></td> 
            <td align='left' class='cl1'><font face='verdana' size='1'> <a href='editCompanyProfile3.php?company_id=<?=$transactionInfo['userId']?>' > 
              <?=$transactionInfo['companyname']?> 
              </a> <br> 
              <?php if ($transactionInfo['from_url']){ echo "<font face='verdana' size='1'>  (<a href='".$transactionInfo['from_url']."'>".substr($transactionInfo['from_url'],0,40)."...</a>)</font>";} ?> 
              <br> 
              <?php if ($transactionInfo['td_username']){ echo "UserName: ".$transactionInfo['td_username']."<br>Password: ".$transactionInfo['td_password'];} ?> 
&nbsp; </font></td> 
            <td align='left' class='cl1' ><font face='verdana' size='1'> 
              <?= $transactionInfo['name'] ?> 
              <?= $transactionInfo['surname']?> 
              </font></td> 
            <td align='left' class='cl1'><font face='verdana' size='1'> 
              <?=$transactionInfo['checkorcard']=="C"?"Check<BR>":($transactionInfo['checkorcard']=="W"?"ETEL900<BR>":"Credit Card<BR>")?> 
              <strong> 
              <?php if($transactionInfo['status']=='A') echo ($transactionInfo['subAcc']['recur_day'] && $transactionInfo['subAcc']['recur_charge']?
		($transactionInfo['td_enable_rebill']?
			($transactionInfo['td_is_a_rebill']?"REBILL<BR>":"Rebilling Enabled<BR>").($transactionInfo['td_recur_next_date']?$transactionInfo['td_recur_next_date']:"")
		:"DISABLED REBILL<BR>")
	:"Not a rebill<BR>");?> 
              </strong> </font></td> 
            <td align='right' class='cl1'><font face='verdana' size='1'>( usd )&nbsp; 
              <?=formatMoney($transactionInfo['amount'])?> 
&nbsp;</font></td> 
            <td align='center' class='cl1'><font face='verdana' size='1'> 
              <? if($transactionInfo['cd_enable_tracking']=='on' && $transactionInfo['td_enable_tracking']=='on' && $transactionInfo['status']=='A' && $transactionInfo['cancelstatus'] == 'N') { 
						$track_status = "Deadline: ".date("m-d-y",$transactionInfo['Tracking_Deadline']);
						if(!$transactionInfo['td_tracking_id'])
						{
						  if($transactionInfo['Tracking_Days_Left']<=0)
							$track_status .= "<BR><font color='#FF0000'>Past due.</font>";
						  else
							$track_status .= "<BR>".$transactionInfo['Tracking_Days_Left']." days left.";
						}
						else
						{
							$track_status .= "<BR><a href='".$transactionInfo['td_tracking_link']."'>".$transactionInfo['td_tracking_id']."</a>";
						}
						echo $track_status;
					}
					else echo "N/A";
					?> 
&nbsp; </font> </td> 
            <td align='center' class='cl1'><font face='verdana' size='1'> 
              <?php

										if($transactionInfo['status']=="A")
											echo("APR");
										else if($transactionInfo['status']=="P")
											echo("PEN");
										
										else
											echo("DEC<BR>".$transactionInfo['td_process_msg']);
										

								?> 
              </font></td> 
            <td width="110" align='center' class='cl1'><strong><font face='verdana' size='1'> 
              <?php 
if($transactionInfo['status']=="A")
{
	if($transactionInfo['cancelstatus']=="Y")
	{
		echo "REFUND<BR>";
		//if(($transactionInfo['status']=="A")) echo "<input name='Submit' type='submit' value='Remove Refund' title='Refund'>";
	}
	else
	{
		if($transactionInfo['td_is_chargeback']=="1")
		{
			echo "CHARGEBACK<BR>";
			if(($transactionInfo['status']=="A")) echo "<input name='Submit' type='submit' value='Remove Chargeback' title='Chargeback'>";
		}
		else
		{
			if($transactionInfo['td_enable_rebill']=="1") echo "<input name='Submit' type='submit' value='Cancel Rebill' title='Cancel'>";
			if(($transactionInfo['status']=="A") && (!$transactionInfo['hasRefundRequest'])) echo "<input name='Submit' type='submit' value='Issue Refund' title='Refund'>";
			
			if(($transactionInfo['status']=="A")) echo "<input name='Submit' type='submit' value='Set Chargeback' title='Chargeback'>";
		}
	}
}
?> 
              <br> 
              </p> 
              <?=$transactionInfo['cancel_refer_num']?$transactionInfo['cancel_refer_num']:""?> 
              <?php print func_get_date_time_12hr($transactionInfo['cancellationDate']);
							  
							  ?>&nbsp;</font></strong></td> 
            <td class='cl1 style3'><font face='verdana' size='1'>&nbsp; 
              <?=$transactionInfo['reason'] ?> 
              <?php if($transactionInfo['hasRefundRequest']) echo "<BR>Refund Requested:<BR>".$transactionInfo['service_notes']; ?> 
              </font> </td> 
            <td class='cl1' width="100"><font face='verdana' size='1'> &nbsp; 
              <?= ucfirst($transactionInfo['td_bank_recieved'])?> 
              <?=($transactionInfo['td_bank_recieved']=='fraudscrubbing'?"<BR>Score: ".$transactionInfo['td_fraud_score']:"")?> 
              <?=($transactionInfo['td_bank_recieved']=='banlist'?"<BR>".$transactionInfo['td_process_result']:"")?> 
              </font> </td> 
          </form> 
        </tr> 
        <?php						}
					?> 
        <tr> 
          <td colspan="13" align="center" valign="bottom"><br> 
            <form name="summaryback" method="post" action=""> 
              <input name="next" type="submit" value="Next <?=$tinc?>"> 
              <input name="last" type="submit" value="Last <?=$tinc?>"> 
              <select name="tinc" id="tinc"> 
                <option value="10" <?=($tinc==10?"selected":"")?>>Show 10</option> 
                <option value="25" <?=($tinc==25?"selected":"")?>>Show 25</option> 
                <option value="50" <?=($tinc==50?"selected":"")?>>Show 50</option> 
                <option value="100 <?=($tinc==100?"selected":"")?>">Show 100</option> 
              </select> 
              <input name="show" type="submit" id="show" value="Show"> 
              <input name="curinc" type="hidden" id="curinc" value="<?=$curinc?>"> 
            </form></td> 
        </tr> 
        <br> 
      </table> 
      <!-- Reports ends here --> 
      <br></td> 
  </tr> 
  <?php
		}else {
					$outhtml="y";
					$msgtodisplay="No transactions for this period";
					message($msgtodisplay,$outhtml,$headerInclude);									
					exit();

	}
	?> 
  <tr> 
    <td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td> 
    <td colspan="3" width="98%" background="<?=$tmpl_dir?>/images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td> 
    <td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td> 
  </tr> 
</table> 
<br> 
<?php
include "includes/footer.php";
}
?> 
