<?php
	
	require_once("includes/function.php");
		
	$str_current_date = func_get_current_date();
	$str_current_date_time = $str_current_date." 00:00:00";
	$qry_select = "SELECT transactionId FROM cs_transactiondetails A,cs_companydetails B WHERE A.userId = B.userId and A.passStatus = 'PA' and A.cancelstatus = 'N' and A.status = 'P' and A.billingDate = '$str_current_date' and B.auto_approve = 'Y'";
	//echo $qry_select;
	$rst_select = mysql_query($qry_select,$cnn_cs);
	if(mysql_num_rows($rst_select)>0) {
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++) {
			$i_transaction_id = mysql_result($rst_select,$i_loop,0);
			//print($i_transaction_id."<br>");
			func_approve_order($i_transaction_id,$str_current_date_time);
		}
	}
	
	function func_approve_order($i_transaction_id,$str_current_date_time) {
		$str_query = "update cs_transactiondetails set status = 'A', approvaldate = '$str_current_date' where transaction_id = $i_transaction_id";
		if(!mysql_query($str_query,$cnn_cs)) {
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("<br>");
			print("Can not execute query");
			exit();
		}
	}
?>
