<?php
	
	require_once("includes/function.php");
		
	//*********** get the rebilling details to the entered on current date 
	//********************************************************************************
	$str_currentdate = func_get_current_date_time();
	//$str_currentdate = "2004-03-02 13:00:00";
	$str_year = substr($str_currentdate,0,4);
	$str_month = substr($str_currentdate,5,2);
	$str_day = substr($str_currentdate,8,2);
	$str_currentdate = $str_year."-".$str_month."-".$str_day;
		
	$qry_select_rebilling = "SELECT A.transactionId,A.recur_mode,A.recur_day,A.recur_week,A.recur_month,A.recur_start_date,A.recur_charge,A.recur_times,B.cancelstatus FROM cs_rebillingdetails A,cs_transactiondetails B WHERE A.transactionId = B.transactionId  and A.recur_start_date <= '$str_currentdate'";
	//echo $qry_select_rebilling;
	$rst_select_rebilling = mysql_query($qry_select_rebilling);
	if(mysql_num_rows($rst_select_rebilling)>0)
	{
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select_rebilling);$i_loop++)
		{
			$i_tansaction_id = mysql_result($rst_select_rebilling,$i_loop,0);
			$str_recur_mode = mysql_result($rst_select_rebilling,$i_loop,1);
			$i_recur_day = mysql_result($rst_select_rebilling,$i_loop,2);
			$i_recur_week = mysql_result($rst_select_rebilling,$i_loop,3);
			$i_recur_month = mysql_result($rst_select_rebilling,$i_loop,4);
			$str_recur_start_date = mysql_result($rst_select_rebilling,$i_loop,5);
			$i_recur_charge = mysql_result($rst_select_rebilling,$i_loop,6);
			$i_recur_times = mysql_result($rst_select_rebilling,$i_loop,7);
			$str_cancel_status = mysql_result($rst_select_rebilling,$i_loop,8);
			if($str_cancel_status == "N")
			{
				if(!func_is_rebilled_today($cnn_cs,$i_tansaction_id,$str_currentdate))
				{
					$is_rebill_date = false;
					if($str_recur_start_date == $str_currentdate)
					{
						$is_rebill_date = true;
					}
					else
					{
						//print($str_recur_mode);
						if($i_recur_times == 0 || func_get_number_of_rebills($cnn_cs,$i_tansaction_id) < $i_recur_times)
						{
							switch($str_recur_mode)
							{
								case "D":
									$str_prev_rebill_date = func_get_prev_rebill_date($cnn_cs,$i_tansaction_id);
									if($str_prev_rebill_date == "")
									{
										$str_prev_rebill_date = $str_recur_start_date;
									}
									$i_year = substr($str_prev_rebill_date,0,4);
									$i_month = substr($str_prev_rebill_date,5,2);
									$i_day = substr($str_prev_rebill_date,8,2);
									$str_next_rebill_date = date("Y-m-d",mktime(0,0,0,$i_month,($i_day+$i_recur_day),$i_year));
									
									if($str_next_rebill_date == $str_currentdate)
									{
										$is_rebill_date = true;
									}
								break;

								case "W":
									$i_day_of_week = date("w",mktime(0,0,0,$str_month,$str_day,$str_year));
									//print($i_day_of_week);
									if($i_day_of_week == ($i_recur_week - 1))
									{
										$is_rebill_date = true;
									}
								break;

								case "M":
									$i_day_of_month = date("j",mktime(0,0,0,$str_month,$str_day,$str_year));
									if($i_day_of_month == $i_recur_day)
									{
										$is_rebill_date = true;
									}
								break;

								case "Y":
									$i_month = date("n",mktime(0,0,0,$str_month,$str_day,$str_year));
									$ = date("j",mktime(0,0,0,$str_month,$str_day,$str_year));
									print("month= $i_month ,day= $i_day_of_month");
									if($i_day_of_month == $i_recur_day && $i_month == $i_recur_month)
									{
										$is_rebill_date = true;
									}
								break;
							}
						}
					}
					if($is_rebill_date)
					{
						//print("rebilling ".$i_tansaction_id);
						func_transfer_data($cnn_cs,$i_tansaction_id,$i_recur_charge);
						func_write_rebill_log($cnn_cs,$i_tansaction_id,$str_currentdate);
					}
				}
			}
		}
	}
	
	//**************** Function for transfering the data from rebilling to the transaction ***********
	//***********************************************************************************************
	function func_transfer_data($cnn_cs,$i_transaction_id,$i_recur_charge)
	{
		$qry_select = "Select transactionid,name,surname,amount,address,country,";
		$qry_select .= " state,city,zipcode,CCnumber,cvv,cardtype,validupto,misc,";
		$qry_select .= " email,userId,ipaddress,phonenumber,checkorcard,cancelstatus,";
		$qry_select .= " status,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,licensestate,";
		$qry_select .= " memodet,signature,bankname,bankroutingcode,bankaccountnumber,accounttype,Checkto,reason,other,checktype";
		$qry_select .= " from cs_rebillingdetails  where transactionid=$i_transaction_id";
		$rst_select = mysql_query($qry_select,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$str_transaction_date = func_get_current_date_time();
			$str_first_name = mysql_result($rst_select,0,1);
			$str_sur_name = mysql_result($rst_select,0,2);
			$str_bill_address = mysql_result($rst_select,0,4);
			$str_card_number = etelDec(mysql_result($rst_select,0,9));
			$str_cvv_number = mysql_result($rst_select,0,10);
			$str_check_or_cart = mysql_result($rst_select,0,18);
			$str_country = mysql_result($rst_select,0,5);
			$str_state = mysql_result($rst_select,0,6);
			$str_city = mysql_result($rst_select,0,7);
			$str_zip_code = mysql_result($rst_select,0,8);
			$i_amt  = $i_recur_charge;
			$str_misc = mysql_result($rst_select,0,13);
			$str_email_address = mysql_result($rst_select,0,14);
			$str_cancel_status = "N";
			$str_status = "P";
			$i_user_id = mysql_result($rst_select,0,15);
			$str_card_type = mysql_result($rst_select,0,11);
			$str_valid_upto = mysql_result($rst_select,0,12);
			$str_ip_address = mysql_result($rst_select,0,16);
			$str_phone_number = mysql_result($rst_select,0,17);
			$str_check_or_card = mysql_result($rst_select,0,18);
			$str_cancel_status = mysql_result($rst_select,0,19);
			$str_status = mysql_result($rst_select,0,20);
			$str_voice_authorization_num = mysql_result($rst_select,0,21);
			$shipping_tracking_no = mysql_result($rst_select,0,22);
			$str_social_security_num = mysql_result($rst_select,0,23);
			$str_drivers_licence = mysql_result($rst_select,0,24);
			$str_billingdate = func_get_current_date_time();
			$str_licence_state = mysql_result($rst_select,0,26);
			$str_memodet = mysql_result($rst_select,0,27);
			$str_signature = mysql_result($rst_select,0,28);
			$str_bankname = mysql_result($rst_select,0,29);
			$str_bankrouting = mysql_result($rst_select,0,30);
			$str_bankaccno = mysql_result($rst_select,0,31);
			$str_accounttype = mysql_result($rst_select,0,32);
			$str_checkto = mysql_result($rst_select,0,33);
			$str_reason = mysql_result($rst_select,0,34);
			$str_other = mysql_result($rst_select,0,35);
			$str_check_type = mysql_result($rst_select,0,36);
			
			 
			$qry_insert = "INSERT INTO cs_transactiondetails (";
			$qry_insert .="transactionDate ,";
			$qry_insert .="name,";
			$qry_insert .="surname,";
			$qry_insert .="address,";
			$qry_insert .="ccnumber,";
			$qry_insert .="cvv,";
			$qry_insert .="checkorcard,";
			$qry_insert .="country,";
			$qry_insert .="state,";
			$qry_insert .="city,";
			$qry_insert .="zipcode,";
			$qry_insert .="amount,";
			$qry_insert .="misc,";
			$qry_insert .="email,";
			$qry_insert .="userid,";
			$qry_insert .="cardtype,";
			$qry_insert .="validupto,";
			$qry_insert .="ipaddress,";
			$qry_insert .="phonenumber,";
			$qry_insert .="cancelstatus,";
			$qry_insert .="status,";
			$qry_insert .="voiceAuthorizationno,";
			$qry_insert .="shippingTrackingno,";
			$qry_insert .="socialSecurity,";
			$qry_insert .="driversLicense,";
			$qry_insert .="licensestate,";
			$qry_insert .="billingDate,";
			$qry_insert .="passStatus,";
			$qry_insert .="memodet,";
			$qry_insert .="signature,";
			$qry_insert .="bankname,";
			$qry_insert .="bankroutingcode,";
			$qry_insert .="bankaccountnumber,";
			$qry_insert .="accounttype,";
			$qry_insert .="Checkto,";
			$qry_insert .="reason,";
			$qry_insert .="other,";
			$qry_insert .="checktype,";
			$qry_insert .="pass_count";
			$qry_insert .=" ) VALUES (";
			$qry_insert .= "'".$str_transaction_date."',";
			$qry_insert .= "'".$str_first_name."',"; 
			$qry_insert .= "'".$str_sur_name."',"; 
			$qry_insert .= "'".$str_bill_address."',"; 
			$qry_insert .= "'".$str_card_number."',"; 
			$qry_insert .= "'".$str_cvv_number."',"; 
			$qry_insert .= "'".$str_check_or_card."',"; 
			$qry_insert .= "'".$str_country."',"; 
			$qry_insert .= "'".$str_state."',"; 
			$qry_insert .= "'".$str_city."',"; 
			$qry_insert .= "'".$str_zip_code."',"; 
			$qry_insert .= $i_amt.",";  
			$qry_insert .= "'".$str_misc."',"; 
			$qry_insert .= "'".$str_email_address."',"; 
			$qry_insert .= $i_user_id.","; 
			$qry_insert .= "'".$str_card_type."',"; 
			$qry_insert .= "'".$str_valid_upto."',"; 
			$qry_insert .= "'".$str_ip_address."',";
			$qry_insert .= "'".$str_phone_number."',";
			$qry_insert .= "'".$str_cancel_status."',";
			$qry_insert .= "'".$str_status."',";
			$qry_insert .= "'".$str_voice_authorization_num."',";
			$qry_insert .= "'".$shipping_tracking_no."',";
			$qry_insert .= "'".$str_social_security_num."',";
			$qry_insert .= "'".$str_drivers_licence."',";
			$qry_insert .= "'".$str_licence_state."',";
			$qry_insert .= "'".$str_billingdate."',";
			$qry_insert .= "'PE',";
			$qry_insert .= "'".$str_memodet."',";
			$qry_insert .= "'".$str_signature."',";
			$qry_insert .= "'".$str_bankname."',";
			$qry_insert .= "'".$str_bankrouting."',";
			$qry_insert .= "'".$str_bankaccno."',";
			$qry_insert .= "'".$str_accounttype."',";
			$qry_insert .= "'".$str_checkto."',";
			$qry_insert .= "'".$str_reason."',";
			$qry_insert .= "'".$str_other."',";
			$qry_insert .= "'".$str_check_type."',";
			$qry_insert .= 0;
			$qry_insert .= ")"; 
			//print($qry_insert);
			if(!mysql_query($qry_insert))
			{
				print(mysql_errno().": ".mysql_error()."<BR>");
				print("<br>");
				print("Can not execute query");
				exit();
			}
		}	
	}

	function func_is_rebilled_today($cnn_cs,$i_transaction_id,$current_date)
	{
		$is_rebilled_today = false;
		$str_query = "select rebill_date from cs_rebill_log where transaction_id = $i_transaction_id and rebill_date = '$current_date'";
		$rst_select = mysql_query($str_query,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$is_rebilled_today = true;
		}
		return $is_rebilled_today;
	}

	function func_get_number_of_rebills($cnn_cs,$i_transaction_id)
	{
		$i_number_of_rebills = 0;
		$str_query = "select count(*) from cs_rebill_log where transaction_id = $i_transaction_id";
		$rst_select = mysql_query($str_query,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$i_number_of_rebills  = mysql_result($rst_select,0,0);
		}
		return $i_number_of_rebills;
	}

	function func_get_prev_rebill_date($cnn_cs,$i_transaction_id)
	{
		$str_previous_rebill_date = "";
		$str_query = "select rebill_date from cs_rebill_log where transaction_id = $i_transaction_id order by rebill_date desc";
		$rst_select = mysql_query($str_query,$cnn_cs);
		if(mysql_num_rows($rst_select)>0)
		{
			$str_previous_rebill_date  = mysql_result($rst_select,0,0);
		}
		return $str_previous_rebill_date;
	}

	function func_write_rebill_log($cnn_cs,$i_transaction_id,$current_date)
	{
		$str_query = "insert into cs_rebill_log(transaction_id,rebill_date) values($i_transaction_id,'$current_date')";
		if(!mysql_query($str_query,$cnn_cs))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("<br>");
			print("Can not execute query");
			exit();
		}
	}
?>
