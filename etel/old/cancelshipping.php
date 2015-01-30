<?php
	
	require_once("includes/function.php");
		
	//*********** get the rebilling details to the entered on current date 
	//********************************************************************************
	$str_current_date_time = func_get_current_date_time();
	$i_timeframe_in_days = 10;
	$str_current_year = substr($str_current_date_time,0,4);
	$str_current_month = substr($str_current_date_time,5,2);
	$str_current_day = substr($str_current_date_time,8,2);
	$str_current_date = $str_current_year."-".$str_current_month."-".$str_current_day;
		
	$qry_select = "SELECT A.transactionId,A.approvaldate,B.userId,B.shipping_cancel,B.shipping_timeframe FROM cs_transactiondetails A,cs_companydetails B WHERE A.userId = B.userId and A.status = 'A' and A.cancelstatus = 'N' and A.shippingTrackingno = '' ";
	//echo $qry_select;
	$rst_select = mysql_query($qry_select);
	if(mysql_num_rows($rst_select)>0)
	{
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
		{
			$i_transaction_id = mysql_result($rst_select,$i_loop,0);
			$str_approval_date_time = mysql_result($rst_select,$i_loop,1);
			$i_user_id = mysql_result($rst_select,$i_loop,2);
			$str_auto_cancel = mysql_result($rst_select,$i_loop,3);
			$i_time_frame = mysql_result($rst_select,$i_loop,4);
			if($str_auto_cancel == "Y")
			{
				$str_year = substr($str_approval_date_time,0,4);
				$str_month = substr($str_approval_date_time,5,2);
				$str_day = substr($str_approval_date_time,8,2);
				/*$str_hour = substr($str_auto_cancel,11,2);
				$str_minute = substr($str_auto_cancel,14,2);
				$str_second = substr($str_auto_cancel,17,2);*/

				$str_target_date = date("Y-m-d",mktime(0,0,0,$str_month,($str_day+$i_time_frame),$str_year));
				//print("userid= $i_user_id, TranId= $i_transaction_id, now= $str_current_date, approved= $str_approval_date_time, timeFrame= $i_time_frame, target= $str_target_date<br>");
				if($str_current_date > $str_target_date)
				{
					//print("cancel<br>");
					func_cancel_shipping_order($cnn_cs,$i_transaction_id,$str_current_date_time);	
				}
			}
		}
		//print("count= $i_loop");
	}
	
	function func_cancel_shipping_order($cnn_cs,$i_transaction_id,$str_current_date_time)
	{
		$str_query = "update cs_transactiondetails set cancelstatus ='Y',cancellationDate = '$str_current_date_time',reason='Shipping Cancel',passStatus='ND' where transactionId = $i_transaction_id";
		if(!mysql_query($str_query,$cnn_cs))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("<br>");
			print("Can not execute query");
			exit();
		}

	}
?>
