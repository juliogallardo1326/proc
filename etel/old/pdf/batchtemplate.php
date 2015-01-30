<?php
	include 'includes/sessioncheck.php';
	require_once("includes/dbconnection.php");
	require_once('includes/function.php');
	$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
	$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","gateway_id",$sessionlogin);
	$trans_type = 	isset($HTTP_GET_VARS['cctype'])?$HTTP_GET_VARS['cctype']:"";
	if($trans_type=="check") {
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=check_report.csv");
		if(!($file = fopen("downloads/check_report.csv", "r")))
		{
			print("Can not open file");
			exit();
		}	
		$content = fread($file, filesize("downloads/check_report.csv"));
	}else if($trans_type=="credit"){
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=creditcard_report.csv");
		if(!($file = fopen("downloads/creditcard_report.csv", "r")))
		{
			print("Can not open file");
			exit();
		}	
		$content = fread($file, filesize("downloads/creditcard_report.csv"));
	}else {
		header("Content-type: application/pdf");
		if($gateway_id==-1) {
				header("Content-Disposition: attachment; filename=EtelegateIntegrationGuide.pdf");
				if(!($file = fopen("downloads/EtelegateIntegrationGuide.pdf", "r")))
				{
					print("Can not open file");
					exit();
				}
				$content = fread($file, filesize("downloads/EtelegateIntegrationGuide.pdf"));
		} else {
				header("Content-Disposition: attachment; filename=PaymentIntegrationGuide.pdf");
				if(!($file = fopen("downloads/PaymentIntegrationGuide.pdf", "r")))
				{
					print("Can not open file");
					exit();
				}
				$content = fread($file, filesize("downloads/PaymentIntegrationGuide.pdf"));		
		}
	}
	$content = explode("\r\n", $content);
	fclose($file);
	$file_content = "";
	for($i=0;$i<count($content);$i++)
	{
		$file_content .= $content[$i];
	}
	print($file_content);
?>