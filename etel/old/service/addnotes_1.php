<?php
	include("../includes/sessioncheckserviceuser.php");
	
	require_once("../includes/function.php");
	$i_call_note_id = (isset($HTTP_POST_VARS["hid_call_note_id"])?Trim($HTTP_POST_VARS["hid_call_note_id"]):"");
	$strTransactionId = (isset($HTTP_POST_VARS["hdTransactionId"])?Trim($HTTP_POST_VARS["hdTransactionId"]):"");
	$strNotes = (isset($HTTP_POST_VARS["txtNotes"])?Trim($HTTP_POST_VARS["txtNotes"]):"");
	$str_chk_cancel = (isset($HTTP_POST_VARS["chkCancel"])?Trim($HTTP_POST_VARS["chkCancel"]):"");
	$str_callback = (isset($HTTP_POST_VARS["chkCallBack"])?Trim($HTTP_POST_VARS["chkCallBack"]):"");
	$i_bill_year = (isset($HTTP_POST_VARS["opt_bill_year"])?Trim($HTTP_POST_VARS["opt_bill_year"]):"");
	$i_bill_month = (isset($HTTP_POST_VARS["opt_bill_month"])?Trim($HTTP_POST_VARS["opt_bill_month"]):"");
	$i_bill_day = (isset($HTTP_POST_VARS["opt_bill_day"])?Trim($HTTP_POST_VARS["opt_bill_day"]):"");
	$str_approval_status = (isset($HTTP_POST_VARS["hid_approval_status"])?Trim($HTTP_POST_VARS["hid_approval_status"]):"");
	$str_bill_date = (isset($HTTP_POST_VARS["hid_bill_date"])?Trim($HTTP_POST_VARS["hid_bill_date"]):"");
	$str_orig_bill_date = (isset($HTTP_POST_VARS["hid_orig_bill_date"])?Trim($HTTP_POST_VARS["hid_orig_bill_date"]):"");
	$str_call_duration = (isset($HTTP_POST_VARS["hid_counter"])?Trim($HTTP_POST_VARS["hid_counter"]):"");
	$str_solved = (isset($HTTP_POST_VARS["chkSolved"])?Trim($HTTP_POST_VARS["chkSolved"]):"0");
	$str_dnc = (isset($HTTP_POST_VARS["chkDNC"])?Trim($HTTP_POST_VARS["chkDNC"]):"N");
	$str_changed_bill_date = "N"; 
	//$str_changed_bill_date = (isset($HTTP_POST_VARS["chkChangedBillDate"])?Trim($HTTP_POST_VARS["chkChangedBillDate"]):"N");

	// hidden values for building the body of the DNC Mail.

	$str_first_name = (isset($HTTP_POST_VARS["hid_first_name"])?Trim($HTTP_POST_VARS["hid_first_name"]):"");
	$str_last_name = (isset($HTTP_POST_VARS["hid_last_name"])?Trim($HTTP_POST_VARS["hid_last_name"]):"");
	$str_company_name = (isset($HTTP_POST_VARS["hid_company_name"])?Trim($HTTP_POST_VARS["hid_company_name"]):"");
	$str_telephone_number = (isset($HTTP_POST_VARS["hid_telephone_number"])?Trim($HTTP_POST_VARS["hid_telephone_number"]):"");
	$str_address = (isset($HTTP_POST_VARS["hid_address"])?Trim($HTTP_POST_VARS["hid_address"]):"");
	$str_city = (isset($HTTP_POST_VARS["hid_city"])?Trim($HTTP_POST_VARS["hid_city"]):"");
	$str_state = (isset($HTTP_POST_VARS["hid_state"])?Trim($HTTP_POST_VARS["hid_state"]):"");
	$str_zip = (isset($HTTP_POST_VARS["hid_zip"])?Trim($HTTP_POST_VARS["hid_zip"]):"");
	$str_country = (isset($HTTP_POST_VARS["hid_country"])?Trim($HTTP_POST_VARS["hid_country"]):"");
	$i_user_id = (isset($HTTP_POST_VARS["hid_user_id"])?Trim($HTTP_POST_VARS["hid_user_id"]):"");
	$str_check_or_card = (isset($HTTP_POST_VARS["hid_check_or_card"])?Trim($HTTP_POST_VARS["hid_check_or_card"]):"");

	$i_customer_service_id = -1;
	if(isset($_SESSION["sessionService"]))
	{
		$i_customer_service_id = 0;
	}
	else if(isset($_SESSION["sessionServiceUserId"]))
	{
		$i_customer_service_id = $_SESSION["sessionServiceUserId"];
	}

	$str_new_bill_date = $str_orig_bill_date;
	//else
	//{
	//	$str_new_bill_date = $str_bill_date;
	//}
	$strDateTime = func_get_current_date_time();
	$strCurrentDate1 = func_get_current_date();
	$strCurrentDate1 .= " 00:00:00";
	$strCurrentDate2 = func_get_current_date();
	$strCurrentDate2 .= " 23:59:59";
	$str_cancel_status = "0";
	$str_set_query = "";
	//$str_bill_date_changed = "N";
	if($str_callback == "Y")
	{
		funcCallBack($cnn_cs,$strTransactionId);
	}
	if($i_bill_month != "")
	{
		$i_bill_month = $i_bill_month < 10 ? "0".$i_bill_month : $i_bill_month;
		$i_bill_day = $i_bill_day < 10 ? "0".$i_bill_day : $i_bill_day;
		$str_new_bill_date = $i_bill_year."-".$i_bill_month."-".$i_bill_day;
		if ($str_new_bill_date != $str_orig_bill_date) {
			$str_changed_bill_date = "Y";
			if($str_set_query == "")
				$str_set_query = "billingDate = '$str_new_bill_date'";
			else
				$str_set_query .= ",billingDate = '$str_new_bill_date'";
		}
	}
	if ($str_chk_cancel=="Y")
	{
		//$str_set_query = "cancelstatus ='Y',cancellationDate = '$strDateTime',reason='Customer Service',passStatus='ND'";
		//$str_set_query = "customer_service_cancel ='Y'";
		if($strCurrentDate1 >= $str_new_bill_date && $str_approval_status == "Approved")
		{
			/*if($str_set_query == "")
				$str_set_query = "reason='Customer Service',cancellationDate='$strDateTime',admin_approval_for_cancellation='P'";
			else
				$str_set_query .= ",reason='Customer Service',cancellationDate='$strDateTime',admin_approval_for_cancellation='P'";*/
			$qrt_update_details ="Update cs_transactiondetails set reason='Customer Service',cancellationDate='$strDateTime',admin_approval_for_cancellation = 'P' where transactionId=$strTransactionId and userId = $i_user_id";
			if(!($qrt_update_run = mysql_query($qrt_update_details)))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Cannot execute query");
				exit();
			} 
		}
		else
		{
			/*if($str_set_query == "")
				$str_set_query = "passStatus='ND',cancelstatus ='Y',cancellationDate = '$strDateTime',reason='Customer Service'";
			else
				$str_set_query .= ",passStatus='ND',cancelstatus ='Y',cancellationDate = '$strDateTime',reason='Customer Service'";*/
			$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
			$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='Customer Service',cancellationDate='$strDateTime' where transactionId=$return_insertId and userId = $i_user_id";
			//print($qryUpdate."<br>");
			if(!mysql_query($qryUpdate,$cnn_cs))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("Can not execute query cancel update query");
				exit();
			}
			if($str_check_or_card == "C")
			{
				func_send_cancel_mail($i_user_id,$str_check_or_card);
			}
			func_canceledTransaction_receipt($i_user_id, $return_insertId,$cnn_cs);
		}
		$str_cancel_status = "1";
	}
	if ($str_set_query != "")
	{
		$qryUpdate = "update cs_transactiondetails set ".$str_set_query;
		$qryUpdate.= " where transactionId =".$strTransactionId;
		if(!mysql_query($qryUpdate))
		{
			print("Can not execute query");
			exit();
		}
	}
	/*$qry_select = "SELECT * FROM cs_callnotes WHERE call_date_time >='$strCurrentDate1' and call_date_time<='$strCurrentDate2' and transaction_id=$strTransactionId";
	if(!($rst_select = mysql_query($qry_select)))
	{
		print("Can not execute select query");
		exit();
	}
	if(mysql_num_rows($rst_select)>0)
	{
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
		{
			$iNoteId = mysql_result($rst_select,$i_loop,0);
		}
		$qryUpdate = "update cs_callnotes set service_notes='$strNotes', cancel_status='$str_cancel_status',is_bill_date_changed='$str_changed_bill_date',solved='$str_solved',call_duration='$str_call_duration',customer_service_id=$i_customer_service_id,prev_bill_date='$str_orig_bill_date' where note_id = $iNoteId";
		if(!mysql_query($qryUpdate)){print("Can not execute update query");exit();}	
	}
	else
	{
		$qryInsert = "insert into cs_callnotes (transaction_id,call_date_time,service_notes,cancel_status,is_bill_date_changed,solved,call_duration,customer_service_id,prev_bill_date) values ($strTransactionId,'$strDateTime','$strNotes','$str_cancel_status','$str_changed_bill_date','$str_solved','$str_call_duration',$i_customer_service_id,'$str_orig_bill_date')";
		if(!mysql_query($qryInsert)){ print("Can not execute insert query"); print($qryInsert); exit(); }
	}*/
	$qryUpdate = "update cs_callnotes set service_notes='$strNotes', cancel_status='$str_cancel_status',is_bill_date_changed='$str_changed_bill_date',solved='$str_solved',call_duration='$str_call_duration',customer_service_id=$i_customer_service_id,prev_bill_date='$str_orig_bill_date',dnc='$str_dnc' where note_id = $i_call_note_id";
	if(!mysql_query($qryUpdate)) {
		print("Can not execute update query");
		exit();
	} else {
		if($str_dnc == "Y") {
			$str_mail_body = "Please Do Not Call the following person:<br><br>";
			$str_mail_body .= "Name: $str_first_name $str_last_name<br><br>";
			$str_mail_body .= "Company: $str_company_name<br><br>";
			$str_mail_body .= "Address: $str_address,<br>";
			$str_mail_body .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$str_city,<br>";
			$str_mail_body .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$str_state,<br>";
			$str_mail_body .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$str_zip,<br>";
			$str_mail_body .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$str_country.<br><br>";
			$str_mail_body .= "Telephone: $str_telephone_number<br><br>";
			$str_mail_body .= "Customer Service Notes: $strNotes<br>";
			func_send_DNC_mail($cnn_cs,$str_mail_body);
		}
	}

	header('location:customerservice.php');?>