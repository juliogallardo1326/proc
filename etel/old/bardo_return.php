<?php
	include('includes/dbconnection.php');
	require_once('includes/function.php');
	include('includes/function1.php');
	include('admin/includes/mailbody_replytemplate.php');

	echo "BARDO";
	$data7 		= "";
	$data1 		= (isset($HTTP_GET_VARS["SHOP_NUMBER"])?$HTTP_GET_VARS["SHOP_NUMBER"]:"0");
	$data2 		= (isset($HTTP_GET_VARS["BARDO_NUMBER"])?$HTTP_GET_VARS["BARDO_NUMBER"]:"N");
	$data3 		= (isset($HTTP_GET_VARS["TRANSAC_STATUS"])?$HTTP_GET_VARS["TRANSAC_STATUS"]:"S");
	$data4 		= (isset($HTTP_GET_VARS["STATUS_DETAILLED"])?$HTTP_GET_VARS["STATUS_DETAILLED"]:"D");
	$data5 		= (isset($HTTP_GET_VARS["3DS"])?$HTTP_GET_VARS["3DS"]:"N");
		
	$headers = "From: sales@etelegate.com\n";  // Who the email was sent from
	$headers .= "Reply-To: sales@etelegate.com\n"; // Reply to address
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // The priority of the mail
	$headers .= "Return-Path: <sales@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$message = "Callback is working correctly"."<br>shopnumber :". $data1."<br>bardo number :".$data2."<br>Transaction status :".$data3."<br>Details : ".$data4 ;
	
	$data6 		=  func_get_current_date_time();
	
	if($data3 == "00" ){
		$data7 = "S";
	}else {
		$data7 = "F";
	}
	$approval_status = $data7 == "S" ? "A" : "D";
	$pass_status = "PA";

	$qryInsert = "insert into  cs_bardo (shop_number,bardo_number,transac_status,status_detailed,ds,trans_date,status) values ";
	$qryInsert .= " ($data1,'$data2','$data3','$data4','$data5','$data6','$data7')";
	if(!mysql_query($qryInsert,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}

	func_update_approval_status($cnn_cs, $data1, $approval_status, $pass_status, $data4);

	if($data3 == "00" ){
		func_send_transaction_success_mail($data1);
	}else {
//		func_send_transaction_failure_mail($data1, $data4);
	}
?>