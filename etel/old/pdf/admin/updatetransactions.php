<?php
	
	include("../includes/function2.php");
	
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
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$transactionId = (isset($HTTP_POST_VARS['transactionId'])?quote_smart($HTTP_POST_VARS['transactionId']):"");
	$check_number = (isset($HTTP_POST_VARS['check_number'])?quote_smart($HTTP_POST_VARS['check_number']):"");
	$credit_number = (isset($HTTP_POST_VARS['credit_number'])?quote_smart($HTTP_POST_VARS['credit_number']):"");
	$account_number = (isset($HTTP_POST_VARS['account_number'])?quote_smart($HTTP_POST_VARS['account_number']):"");
	$routing_code = (isset($HTTP_POST_VARS['routing_code'])?quote_smart($HTTP_POST_VARS['routing_code']):"");
	$radRange = (isset($HTTP_POST_VARS["radRange"])?quote_smart($HTTP_POST_VARS["radRange"]):"");
	$iCount = (isset($HTTP_POST_VARS["hdCount"])?quote_smart($HTTP_POST_VARS["hdCount"]):"");
	$decline_reason=(isset($HTTP_POST_VARS['decline_reasons'])?($HTTP_POST_VARS['decline_reasons']):"");
	$declineReasons=(isset($HTTP_POST_VARS['declineReasons'])?($HTTP_POST_VARS['declineReasons']):"");
	$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
	$cancelReasons=(isset($HTTP_POST_VARS['cancelReasons'])?($HTTP_POST_VARS['cancelReasons']):"");
	$i_lower_limit = (isset($HTTP_POST_VARS["lower_limit"])?quote_smart($HTTP_POST_VARS["lower_limit"]):"0");
	$i_num_records_per_page = (isset($HTTP_POST_VARS["cbo_num_records"])?quote_smart($HTTP_POST_VARS["cbo_num_records"]):"20");
	$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
	$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
	$companyids = isset($HTTP_POST_VARS['companyids'])?$HTTP_POST_VARS['companyids']:"";
	$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"tele";
	$tele_nontele_type = isset($HTTP_POST_VARS['tele_nontele_type'])?quote_smart($HTTP_POST_VARS['tele_nontele_type']):"T";
	$status="";
	$strCurrentDateTime = func_get_current_date();

	for($iLoop = 0;$iLoop<$iCount;$iLoop++){
		$iTransactionId = (isset($HTTP_POST_VARS["hdId$iLoop"])?quote_smart($HTTP_POST_VARS["hdId$iLoop"]):"");
		$str_payment_type = (isset($HTTP_POST_VARS["hid_payment_type$iLoop"])?quote_smart($HTTP_POST_VARS["hid_payment_type$iLoop"]):"");
		$iCompanyId = (isset($HTTP_POST_VARS["hid_company_id$iLoop"])?quote_smart($HTTP_POST_VARS["hid_company_id$iLoop"]):"");
		$strBillingDate = (isset($HTTP_POST_VARS["hid_billing_date$iLoop"])?quote_smart($HTTP_POST_VARS["hid_billing_date$iLoop"]):"");
		$strPassStatus = (isset($HTTP_POST_VARS["radpass$iLoop"])?quote_smart($HTTP_POST_VARS["radpass$iLoop"]):"");
		$strStatus = (isset($HTTP_POST_VARS["radpending$iLoop"])?quote_smart($HTTP_POST_VARS["radpending$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["chk$iLoop"])?quote_smart($HTTP_POST_VARS["chk$iLoop"]):"");
		$strtxt = (isset($HTTP_POST_VARS["txt$iLoop"])?quote_smart($HTTP_POST_VARS["txt$iLoop"]):"");
		$strCancelReason = (isset($HTTP_POST_VARS["optReason$iLoop"])?quote_smart($HTTP_POST_VARS["optReason$iLoop"]):"");
		$strOtherReason = (isset($HTTP_POST_VARS["txt$iLoop"])?quote_smart($HTTP_POST_VARS["txt$iLoop"]):"");
		$strNoPassReason = (isset($HTTP_POST_VARS["txtarea$iLoop"])?quote_smart($HTTP_POST_VARS["txtarea$iLoop"]):"");
		$strDeclinedReason = (isset($HTTP_POST_VARS["declineReason$iLoop"])?quote_smart($HTTP_POST_VARS["declineReason$iLoop"]):"");
		$td_is_chargeback = (isset($HTTP_POST_VARS["td_is_chargeback$iLoop"])?quote_smart($HTTP_POST_VARS["td_is_chargeback$iLoop"]):"");
		$cancelstatus = (isset($HTTP_POST_VARS["cancelstatus$iLoop"])?quote_smart($HTTP_POST_VARS["cancelstatus$iLoop"]):"");

		$qryUpdate = "";
		if($iTransactionId != "" && 0){
		$status= func_get_value_of_field($cnn_cs,"cs_transactiondetails","status","transactionId",$iTransactionId);
			$qry_select_status = "Select passStatus,status,cancelstatus,pass_count,approval_count from cs_transactiondetails where transactionId=$iTransactionId";
			
			if(!($rst_select_status = mysql_query($qry_select_status,$cnn_cs))){
				print("Can not execute query");
				print("<br>");
				exit();
			}	
			if (mysql_num_rows($rst_select_status)>0){
				$db_passstatus = mysql_result($rst_select_status,0,0);
				$db_status = mysql_result($rst_select_status,0,1);
				$db_cancelstatus = mysql_result($rst_select_status,0,2);
				$db_passcount = mysql_result($rst_select_status,0,3);
				$db_approvalcount = mysql_result($rst_select_status,0,4);
			}
			
			$qrydeclineUpdate = "update cs_transactiondetails set td_is_chargeback = '$td_is_chargeback', cancelstatus = '$cancelstatus',reason='$strtxt' where transactionId=$iTransactionId";
			if(!mysql_query($qrydeclineUpdate,$cnn_cs)){
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Can not execute query pass update query");
				exit();
			}
			func_canceledTransaction_receipt($companyids, $iTransactionId,$cnn_cs);


			if($strDeclinedReason !="") {
				$qrydeclineUpdate = "update cs_transactiondetails set declinedReason = '$strDeclinedReason' where transactionId=$iTransactionId";
				if(!mysql_query($qrydeclineUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query pass update query");
					exit();
				}
			}
			if($strPassStatus != "" && $strPassStatus != $db_passstatus)
			{
				if($strPassStatus == "NP")
				{
					$qryUpdate = "update cs_transactiondetails set td_is_chargeback = '$td_is_chargeback', cancelstatus = '$cancelstatus', nopasscomments = '$strNoPassReason' where transactionId=$iTransactionId";
				}
				else
				{
					if($db_passstatus == "NP")
					{
						$qryUpdate = "update cs_transactiondetails set nopasscomments = '' where transactionId=$iTransactionId";
					}
				}
			}
			else
			{
				if($strPassStatus != "")
				{
					if($strPassStatus == "NP")
					{
						$qryUpdate = "update cs_transactiondetails set nopasscomments = '$strNoPassReason' where transactionId=$iTransactionId";
					}
				}
			}
			if($qryUpdate != "")
			{
				//print($qryUpdate);
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query pass update query");
					exit();
				}
			}

			if($strPassStatus != "" && $strPassStatus != $db_passstatus){
				if ($strPassStatus == "PA"){
					if($str_payment_type == "C" && ($strBillingDate == $strCurrentDateTime) && func_is_auto_approved($cnn_cs,$iCompanyId))
					{
						$qryUpdate = "update cs_transactiondetails set status = 'A',approvaldate = '$strCurrentDateTime',passStatus = '$strPassStatus' where transactionId=$iTransactionId";
					}
					else
					{
						$qryUpdate = "update cs_transactiondetails set passStatus  = '$strPassStatus' where transactionId=$iTransactionId";
					}
				}
				else{
					$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
					$passcount = $db_passcount + 1;
					if ($strPassStatus == "NP" && $db_passcount >= 1){
						$qryUpdate = "update cs_transactiondetails set passStatus  = 'ND',pass_count=".$passcount." where transactionId=$return_insertId";
					}
					else{
						$qryUpdate = "update cs_transactiondetails set passStatus  = '$strPassStatus',pass_count=".$passcount." where transactionId=$return_insertId";
					}
				}
				
				if($qryUpdate != "")
				{
					if(!mysql_query($qryUpdate,$cnn_cs)){
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query pass update query");
						exit();
					}
				}
			}

			if($strStatus != "" && $strStatus != $db_status){
				if($strStatus == "A"){
					$qryUpdate = "update cs_transactiondetails set status  = '$strStatus',approvaldate='$strCurrentDateTime' where transactionId=$iTransactionId";
				}else{
					$approvalcount = $db_approvalcount + 1;
					$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
					if ($strStatus == "D" && $db_approvalcount >= 1){
						$qryUpdate = "update cs_transactiondetails set status  = '$strStatus', passStatus  = 'ND',approval_count=".$approvalcount." where transactionId=$return_insertId";
					}
					else{
						$qryUpdate = "update cs_transactiondetails set status  = '$strStatus', approval_count=".$approvalcount." where transactionId=$return_insertId";
					}
				}
				if($qryUpdate != "")
				{
					if(!mysql_query($qryUpdate,$cnn_cs)){
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query status update");
						exit();
					}
				}
			}

			if($strCancelReason != "")
				{
				$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
								
				$qryUpdate = "update cs_transactiondetails set ,reason='$strCancelReason',other='$strOtherReason',cancellationDate='$strCurrentDateTime' where transactionId=$return_insertId";
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query cancel update query");
					exit();
				}
			}
		}	
	}

?>
<html>
<body onLoad="document.dates1.submit()">
		<form name="dates1" action="reportbottom1.php"  method="POST">
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
			<input type="hidden" name="email" value="<?= $email?>">
			<input type="hidden" name="transactionId" value="<?=$transactionId ?>">
			<input type="hidden" name="radRange" value="<?=$radRange ?>">
			<input type="hidden" name="check_number" value="<?=$check_number ?>">			
			<input type="hidden" name="credit_number" value="<?=$credit_number ?>">			
			<input type="hidden" name="account_number" value="<?=$account_number ?>">			
			<input type="hidden" name="routing_code" value="<?=$routing_code ?>">			
			<input type="hidden" name="decline_reasons" value="<?=$decline_reason ?>">			
			<input type="hidden" name="decline_reasons1" value="<?=$declineReasons ?>">			
			<input type="hidden" name="cancel_reasons" value="<?=$cancel_reason ?>">			
			<input type="hidden" name="cancel_reasons1" value="<?=$cancelReasons ?>">			
			<input type="hidden" name="task" value="<?=$str_task ?>">			
			<input type="hidden" name="lower_limit" value="<?=$i_lower_limit ?>">			
			<input type="hidden" name="cbo_num_records" value="<?=$i_num_records_per_page ?>">			
			<input type="hidden" name="companyname" value="<?= $companyid ?>">
			<input type="hidden" name="companyids" value="<?= $companyids ?>">
			<input type="hidden" name="companymode" value="<?=$companytype?>">
			<input type="hidden" name="companytrans_type" value="<?=$companytrans_type?>">
			<input type="hidden" name="tele_nontele_type" value="<?=$tele_nontele_type?>">

		</form>
<script language="JavaScript" type="text/JavaScript">
function func_submit()
{
	//document.dates1.submit();
}
</script>
		
</body>
</html> 