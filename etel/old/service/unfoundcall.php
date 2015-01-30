<?php
	include("../includes/sessioncheckserviceuser.php");
	
	require_once("../includes/dbconnection.php");
	require_once("../includes/function.php");
	$strName = (isset($HTTP_POST_VARS["txtName"])?Trim($HTTP_POST_VARS["txtName"]):"");
	$strAddress = (isset($HTTP_POST_VARS["txtAddress"])?Trim($HTTP_POST_VARS["txtAddress"]):"");
	$strPhone = (isset($HTTP_POST_VARS["txtPhone"])?Trim($HTTP_POST_VARS["txtPhone"]):"");
	$strNotes = (isset($HTTP_POST_VARS["txtNotes"])?Trim($HTTP_POST_VARS["txtNotes"]):"");
	$strCancelStatus = (isset($HTTP_POST_VARS["chk_cancel_call"])?Trim($HTTP_POST_VARS["chk_cancel_call"]):"N");
	$strDNC = (isset($HTTP_POST_VARS["chk_dnc"])?Trim($HTTP_POST_VARS["chk_dnc"]):"N");
	$strDateTime = func_get_current_date_time();
	$str_duration = $_SESSION['duration_start'];

	$str_hour = floor((time()-$str_duration)/(60*60))%24;
	if (strlen($str_hour) == 1) $str_hour = "0".$str_hour;
	$str_min = floor((time()-$str_duration)/(60))%60;
	if (strlen($str_min) == 1) $str_min = "0".$str_min;
	$str_sec = floor((time()-$str_duration))%60;
	if (strlen($str_sec) == 1) $str_sec = "0".$str_sec;
	
	$strCallDuration = $str_hour.":".$str_min.":".$str_sec;
	
	$i_gateway_id = (isset($HTTP_POST_VARS["hid_gateway_id"])?Trim($HTTP_POST_VARS["hid_gateway_id"]):"");
	$i_customer_service_id = -1;
	if(isset($_SESSION["sessionService"]))
	{
		$i_customer_service_id = 0;
	}
	else if(isset($_SESSION["sessionServiceUserId"]))
	{
		$i_customer_service_id = $_SESSION["sessionServiceUserId"];
	}
	$str_table_name = "cs_unfound_calls";
	if ($i_gateway_id != -1) {
		$str_table_name = "cs_gateway_unfound_calls";
	}
	$qry_insert = "INSERT INTO $str_table_name (customerName,customerAddress,customerPhone,notes,currentDateTime,call_duration,customer_service_id,cancel_status,dnc) VALUES (";
	$qry_insert .= "'$strName','$strAddress','$strPhone','$strNotes','$strDateTime','$strCallDuration',$i_customer_service_id,'$strCancelStatus','$strDNC')";
	if(!mysql_query($qry_insert,$cnn_cs))
	{
		print("Can not execute query");
		exit();
	}
	else {
		if($strDNC == "Y") {
			$str_mail_body = "Please Do Not Call the following person:<br><br>";
			$str_mail_body .= "Name: $strName<br><br>";
			$str_mail_body .= "Address: ".nl2br($strAddress)."<br><br>";
			$str_mail_body .= "Telephone: $strPhone<br><br>";
			$str_mail_body .= "Customer Service Notes: $strNotes<br>";
			func_send_DNC_mail($cnn_cs,$str_mail_body);
		}
	}
	header('location:startpage.php');?>