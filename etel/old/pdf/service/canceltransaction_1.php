<?php
	include("../includes/sessioncheckserviceuser.php");
	
	require_once("../includes/function.php");
	$strReasonCombo = (isset($HTTP_POST_VARS["optReason"])?Trim($HTTP_POST_VARS["optReason"]):"");
	$strOtherReason = (isset($HTTP_POST_VARS["txtReason"])?Trim($HTTP_POST_VARS["txtReason"]):"");
	$iTransactionId = (isset($HTTP_POST_VARS["hdTransactionId"])?Trim($HTTP_POST_VARS["hdTransactionId"]):"");
	$strDateTime = func_get_current_date_time();
	$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);

	$qryUpdate = "update cs_transactiondetails set cancelstatus ='Y',cancellationDate = '$strDateTime',reason='$strReasonCombo',other='$strOtherReason'";
	$qryUpdate.= " where transactionId =".$return_insertId;
	if(!mysql_query($qryUpdate))
	{
		print("Can not execute query");
		exit();
	}
	$user_id = func_get_value_of_field($cnn_cs,"cs_transactiondetails","userId","transactionId",$return_insertId);
	func_canceledTransaction_receipt($user_id, $return_insertId,$cnn_cs);
	header('location:customerservice.php');?>