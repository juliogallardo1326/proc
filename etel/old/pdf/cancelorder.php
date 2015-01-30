<?php
	
	require_once("includes/function.php");
		
	//*********** get the rebilling details to the entered on current date 
	//********************************************************************************
	$str_current_date_time = func_get_current_date_time();
	$i_timeframe_in_days = 10;
	//$str_current_date_time = $str_year."-".$str_month."-".$str_day;
		
	$qry_select = "SELECT A.note_id,A.transaction_id,A.call_date_time,B.cancelstatus,A.solved,B.userId FROM cs_callnotes A,cs_transactiondetails B WHERE A.transaction_id = B.transactionId and A.solved = 0";
	//echo $qry_select;
	$rst_select = mysql_query($qry_select);
	if(mysql_num_rows($rst_select)>0)
	{
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
		{
			$i_note_id = mysql_result($rst_select,$i_loop,0);//print("noteid= "+$i_note_id);
			$i_transaction_id = mysql_result($rst_select,$i_loop,1);
			$str_call_date_time = mysql_result($rst_select,$i_loop,2);
			$str_cancel_status = mysql_result($rst_select,$i_loop,3);
			$str_solved = mysql_result($rst_select,$i_loop,4);
			$i_user_id = mysql_result($rst_select,$i_loop,5);
			if($str_cancel_status != "Y")
			{//print("noteid= "+$i_note_id+" userid= "+$i_user_id);
				if(func_is_auto_cancel($i_user_id))
				{
					$str_year = substr($str_call_date_time,0,4);
					$str_month = substr($str_call_date_time,5,2);
					$str_day = substr($str_call_date_time,8,2);
					$str_hour = substr($str_call_date_time,11,2);
					$str_minute = substr($str_call_date_time,14,2);
					$str_second = substr($str_call_date_time,17,2);

					$str_target_date_time = date("Y-m-d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,($str_day+$i_timeframe_in_days),$str_year));
					if($str_current_date_time > $str_target_date_time)
					{//print("cancel");
						func_cancel_order($i_transaction_id,$str_current_date_time);	
					}
				}
			}
		}
	}
	
	function func_is_auto_cancel($i_user_id)
	{
		$is_auto_cancel = false;
		$str_query = "select auto_cancel from cs_companydetails where userId = $i_user_id";
		$rst_select = mysql_query($str_query,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$is_auto_cancel = mysql_result($rst_select,0,0) == "Y" ? true : false;
		}
		return $is_auto_cancel;
	}

	function func_cancel_order($i_transaction_id,$str_current_date_time)
	{
		$str_query = "update cs_callnotes set cancel_status = '1' where transaction_id = $i_transaction_id";
		if(!mysql_query($str_query,$cnn_cs))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("<br>");
			print("Can not execute query");
			exit();
		}
		$str_query = "update cs_transactiondetails set cancelstatus ='Y',cancellationDate = '$str_current_date_time',reason='Customer Service',passStatus='ND' where transaction_id = $i_transaction_id";
		if(!mysql_query($str_query,$cnn_cs))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("<br>");
			print("Can not execute query");
			exit();
		}

	}
?>
