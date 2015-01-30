<?php
// Bank Integration process function for Bardo.

function func_bank_integration_result($fname,$lname,$tot_amount,$account_type,$account_no,$bank_routing_no) 
{
	$output_transaction = "pg_merchant_id=105308&";
	$output_transaction .= "pg_password=obdt079&";
	$output_transaction .= "pg_transaction_type=26&";
	$output_transaction .= "pg_total_amount=$tot_amount&";
	$output_transaction .= "ecom_billto_postal_name_first=$fname&";
	$output_transaction .= "ecom_billto_postal_name_last=$lname&";
	$output_transaction .= "ecom_payment_check_account_type=$account_type&";
	$output_transaction .= "ecom_payment_check_account=$account_no&";
	$output_transaction .= "ecom_payment_check_trn=$bank_routing_no&";
	$output_transaction .= "endofdata&";
	
	// output url - i.e. the absolute url to the paymentsgateway.net script
	//$output_url = "https://www.paymentsgateway.net/cgi-bin/posttest.pl";
	
	// Uncomment below for live
	$output_url = "https://www.paymentsgateway.net/cgi-bin/postauth.pl";
	
	// start output buffer to catch curl return data
	ob_start();

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $output_transaction);
	//execute curl and return result to STDOUT
		curl_exec ($ch);
	//close curl connection
		curl_close ($ch);

	// set variable eq to output buffer
	$process_result = ob_get_contents();
	
	// close and clean output buffer
	ob_end_clean();
	
	// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
	$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result))));
	
	// parse the string into variablename=variabledata
	parse_str($clean_data);
	
	//echo "Response Data ".$clean_data;
	
	// output some of the variables
	//echo "Response Type = ".$pg_response_type."<br />"; 
	//echo "Response Code = ".$pg_response_code."<br />"; 
	//echo "Response Description = ".$pg_response_description."<br />"; 
	$trans_response_desc = func_response_description($pg_response_description);
	$trans_response = $pg_response_type.",".$trans_response_desc.",".$pg_response_code;
	return($trans_response);
}
// Response code description from bank
function func_response_description($response) 
{
	switch($response) {
		case "MERCH AUTH REVOKED" :
			$return_response_val ="Customer account not accessible";
			break;
		case "DAILY TRANS LIMIT" :
		 	$return_response_val ="Daily limit exceeds";
			break;
		case "MONTHLY TRANS LIMIT" :
		 	$return_response_val ="Monthly limit exceeds";
			break;
		case "DAILY VELOCITY" :
		 	$return_response_val ="Daily velocity limit exceeds";
			break;
		case "VELOCITY WINDOW" :
		 	$return_response_val ="Window velocity limit exceeds";
			break;
		case "DUPLICATE TRANSACTION" :
		 	$return_response_val ="Duplicate transaction";
			break;
		case "ORIG TRANS NOT FOUND" :
		 	$return_response_val ="Original transaction voided";
			break;
		case "UPDATE FAILED" :
			$return_response_val ="Capture data failed";
			break;
		case "INVALID TRN" :
		 	$return_response_val ="Invalid routing number";
			break;
		case "BAD START DATE" :
		 	$return_response_val ="Date is malformed";
			break;
		case "MERCHANT STATUS" :
		 	$return_response_val ="Merchant not live";
			break;
		case "TYPE NOT ALLOWED" :
		 	$return_response_val ="Transaction type not allowed";
			break;
		case "PER TRANS LIMIT" :
		 	$return_response_val ="Transaction amount exceeds the limit";
			break;
		case "INVALID MERCHANT CONFIG" :
		 	$return_response_val ="Merchant configuration not updated";
			break;
		case "PREAUTH DECLINE" :
		 	$return_response_val ="Preauthorization result";
			break;
		case "PREAUTH TIMEOUT" :
		 	$return_response_val ="Preauthorizer not responding";
			break;
		case "PREAUTH ERROR" :
		 	$return_response_val ="Preauthorizer error";
			break;
		case "AUTH DECLINE" :
		 	$return_response_val ="Authorizer declination";
			break;
		case "AUTH TIMEOUT" :
		 	$return_response_val ="Authorizer not responding";
			break;
		case "AUTH ERROR" :
		 	$return_response_val ="Authorizer error";
			break;
		case "AVS FAILURE AUTH" :
		 	$return_response_val ="Authorizer AVS check failed";
			break;
		default :
			$return_response_val ="";
	}
	return $return_response_val;
}
// Decline response code from bank.
function func_decline_responsecode($responsecode) 
{
	switch($responsecode) {
		case "R01" :
			$return_responsecode_val ="Insufficient funds";
			break;
		case "R02" :
		 	$return_responsecode_val ="Account closed";
			break;
		case "R03" :
		 	$return_responsecode_val ="No account/unable to locate account";
			break;
		case "R04" :
		 	$return_responsecode_val ="Invalid account number";
			break;
		case "R06" :
		 	$return_responsecode_val ="Returned per ODFI's request";
			break;
		case "R07" :
		 	$return_responsecode_val ="Authorization revoked by customer";
			break;
		case "R08" :
			$return_responsecode_val ="Payment stopped";
			break;
		case "R09" :
		 	$return_responsecode_val ="Uncollected funds";
			break;
		case "R10" :
		 	$return_responsecode_val ="Customer advices not authorized";
			break;
		case "R11" :
		 	$return_responsecode_val ="Check safekeeping entry return";
			break;
		case "R12" :
		 	$return_responsecode_val ="Branch sold to another DFI";
			break;
		case "R13" :
		 	$return_responsecode_val ="RDFI not qualified to participate";
			break;
		case "R14" :
		 	$return_responsecode_val ="Account holder deceased";
			break;
		case "R16" :
		 	$return_responsecode_val ="Account frozen";
			break;
		case "R17" :
		 	$return_responsecode_val ="File record edit criteria";
			break;
		case "R20" :
		 	$return_responsecode_val ="Non-transaction account";
			break;
		case "R21" :
		 	$return_responsecode_val ="invalid company identification";
			break;
		case "R22" :
		 	$return_responsecode_val ="Invalid individual ID number";
			break;
		case "R23" :
		 	$return_responsecode_val ="Credit refused by receiver";
			break;
		case "R24" :
		 	$return_responsecode_val ="Duplicate entry";
			break;
		case "R29" :
		 	$return_responsecode_val ="Corporate customer advices not authorized";
			break;
		case "R31" :
		 	$return_responsecode_val ="Permissible return entry";
			break;
		case "R33" :
		 	$return_responsecode_val ="Return of XCK entry";
			break;
		case "C01" :
		 	$return_responsecode_val ="Incorrect DFI account number";
			break;
		case "C02" :
		 	$return_responsecode_val ="Incorrect routing number";
			break;
		case "C03" :
		 	$return_responsecode_val ="Incorrect routing number and DFI account number";
			break;
		case "C04" :
		 	$return_responsecode_val ="Incorrect individual/company name";
			break;
		case "C05" :
		 	$return_responsecode_val ="Incorrect transaction code";
			break;
		default :
			$return_responsecode_val ="";
	}
	return $return_responsecode_val;
}

//Function to get the current date time in sql insert format
	function func_get_current_date_time()
	{
		$str_time_difference = date("O");
		$str_sign = substr($str_time_difference,0,1);
		$str_hour_difference = substr($str_time_difference,1,2);
		$str_minute_difference = substr($str_time_difference,3,2);
		$str_year = date("Y");
		$str_month = date("m");
		$str_day = date("d");
		$str_hour = date("G");
		$str_minute = date("i");
		$str_second = date("s");
		if($str_sign == "+")
		{
			$str_hour = $str_hour - $str_hour_difference;
			$str_minute = $str_minute - $str_minute_difference;
		}
		else
		{
			$str_hour = $str_hour + $str_hour_difference;
			$str_minute = $str_minute + $str_minute_difference;		
		}
		$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		$str_year = substr($str_return_date,0,4);
		$str_month = substr($str_return_date,5,2);
		$str_day = substr($str_return_date,8,2);
		$str_hour = substr($str_return_date,11,2);
		$str_minute = substr($str_return_date,14,2);
		$str_second = substr($str_return_date,17,2);
		$str_current_date_time = $str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_minute.":".$str_second;
		$str_current_date_time = func_format_date_time($str_current_date_time);
		return($str_current_date_time);		
	}
	
	//function to get get date 12 hour format
	function func_get_date_time_12hr($str_date)
	{
		$str_year = substr($str_date,0,4);
		$str_month = substr($str_date,5,2);
		$str_day = substr($str_date,8,2);
		$str_hour = substr($str_date,11,2);
		$str_minute = substr($str_date,14,2);
		$str_second = substr($str_date,17,2);
		$str_return_date = date("m-d-Y h:i:s A",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		return($str_return_date);		
	}
	function func_get_date_inmmddyy($str_date)
	{
		if($str_date != ""){
			$str_year = substr($str_date,0,4);
			$str_month = substr($str_date,5,2);
			$str_day = substr($str_date,8,2);
			$str_return_date = "";
			if($str_day !="00" && $str_month !="00"){
			$str_return_date = date("m-d-Y",mktime(0,0,0,$str_month,$str_day,$str_year));
			}
			return($str_return_date);		
		}else{
			return $str_date;
		}	
	}
	
	function func_get_date_inyyyymmdd_time($str_date)
	{
		if($str_date != ""){
			$str_month = substr($str_date,0,2);
			$str_day = substr($str_date,3,2);
			$str_year = substr($str_date,6,4);
			$str_hour = substr($str_date,11,2);
			$str_min = substr($str_date,14,2);
			$str_sec = substr($str_date,17,2);
		//	print($str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_min.":".$str_sec);
			$str_return_date = "";
			if(is_numeric($str_day) && is_numeric($str_month) && is_numeric($str_year) && checkdate( $str_month, $str_day, $str_year )) {
				$str_return_date = date("Y-m-d H:i:s",mktime($str_hour,$str_min,$str_sec,$str_month,$str_day,$str_year));
			} 
			return($str_return_date);		
		}else{
			return $str_date;
		}	
	}
	
	 function func_format_date_time($str_date)
	 {
		$str_year = substr($str_date,0,4);
		$str_month = substr($str_date,5,2);
		$str_day = substr($str_date,8,2);
		$str_hour = substr($str_date,11,2);
		$str_hour = $str_hour - 5;
		$str_minute = substr($str_date,14,2);
		$str_second = substr($str_date,17,2);
		$str_return_date = "";
		if(is_numeric($str_day) && is_numeric($str_month) && is_numeric($str_year) && checkdate( $str_month, $str_day, $str_year )) {
			$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		} else {
			return $str_return_date;
		}
		$str_year = substr($str_return_date,0,4);
		$str_month = substr($str_return_date,5,2);
		$str_day = substr($str_return_date,8,2);
		$str_hour = substr($str_return_date,11,2);
		$str_minute = substr($str_return_date,14,2);
		$str_second = substr($str_return_date,17,2);
		$str_return_date = $str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_minute.":".$str_second;
		return ($str_return_date);
	 }
		 
	 function func_get_current_date()
	{
		$str_time_difference = date("O");
		$str_sign = substr($str_time_difference,0,1);
		$str_hour_difference = substr($str_time_difference,1,2);
		$str_minute_difference = substr($str_time_difference,3,2);
		$str_year = date("Y");
		$str_month = date("m");
		$str_day = date("d");
		$str_hour = date("G");
		$str_minute = date("i");
		$str_second = date("s");
		if($str_sign == "+")
		{
			$str_hour = $str_hour - $str_hour_difference;
			$str_minute = $str_minute - $str_minute_difference;
		}
		else
		{
			$str_hour = $str_hour + $str_hour_difference;
			$str_minute = $str_minute + $str_minute_difference;		
		}
		$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		$str_year = substr($str_return_date,0,4);
		$str_month = substr($str_return_date,5,2);
		$str_day = substr($str_return_date,8,2);
		$str_hour = substr($str_return_date,11,2);
		$str_minute = substr($str_return_date,14,2);
		$str_second = substr($str_return_date,17,2);
		$str_current_date_time = $str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_minute.":".$str_second;
		$str_current_date_time = func_format_date($str_current_date_time);
		return($str_current_date_time);		
	}
	  function func_format_date($str_date)
	 {
		$str_year = substr($str_date,0,4);
		$str_month = substr($str_date,5,2);
		$str_day = substr($str_date,8,2);
		$str_hour = substr($str_date,11,2);
		$str_hour = $str_hour - 5;
		$str_minute = substr($str_date,14,2);
		$str_second = substr($str_date,17,2);
		$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		
		$str_year = substr($str_return_date,0,4);
		$str_month = substr($str_return_date,5,2);
		$str_day = substr($str_return_date,8,2);
		$str_hour = substr($str_return_date,11,2);
		$str_minute = substr($str_return_date,14,2);
		$str_second = substr($str_return_date,17,2);
		$str_return_date = $str_year."-".$str_month."-".$str_day;
		return ($str_return_date);
	 }

	 function func_show_format_date_time($str_date)
	 {
		$str_year = substr($str_date,0,4);
		$str_month = substr($str_date,5,2);
		$str_day = substr($str_date,8,2);
		$str_hour = substr($str_date,11,2);
		$str_minute = substr($str_date,14,2);
		$str_second = substr($str_date,17,2);
		$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
		
		$str_year = substr($str_return_date,0,4);
		$str_month = substr($str_return_date,5,2);
		$str_day = substr($str_return_date,8,2);
		$str_hour = substr($str_return_date,11,2);
		$str_minute = substr($str_return_date,14,2);
		$str_second = substr($str_return_date,17,2);
		$str_return_date = $str_month."-".$str_day."-".$str_year." ".$str_hour.":".$str_minute.":".$str_second;
		return ($str_return_date);
	 }

	/***************************** function to send emails with attachment ****************************/

	function sendMail($email_from,$email_subject,$email_message,$email_to,$arrFiles,$arrFileNames)
	{
		$headers = "From: ".$email_from; 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		$headers .= "\nMIME-Version: 1.0\n" . "Return-Path: <sales@etelegate.com>\n".
					"Content-Type: multipart/mixed;\n" . 
					" boundary=\"{$mime_boundary}\""; 
		$email_message .= "This is a multi-part message in MIME format.\n\n" . 
						"--{$mime_boundary}\n" . 
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
					   "Content-Transfer-Encoding: 7bit\n\n" . 
		$email_message . "\n\n"; 
		/********************************************** First File ********************************************/ 
		for($iLoop=0;$iLoop<count($arrFiles);$iLoop++)
		{
			$fileattachmnt = trim($arrFiles[$iLoop]);
			if($fileattachmnt != "")
			{
				$fileatt_name = $arrFileNames[$iLoop];
				$file = fopen($fileattachmnt,'rb'); 
				$data = fread($file,filesize($fileattachmnt)); 
				fclose($file);
				$data = chunk_split(base64_encode($data)); 
				$email_message .= "--{$mime_boundary}\n" . 
							  "Content-Type: {application/octet-stream};\n" . 
							  " name=\"{$fileatt_name}\"\n" . 
							  //"Content-Disposition: attachment;\n" . 
							  //" filename=\"{$fileatt_name}\"\n" . 
							  "Content-Transfer-Encoding: base64\n\n" . 
							 $data . "\n\n" . 
							  "--{$mime_boundary}\n"; 
				/********************************************** End of File Config ********************************************/ 
				// To add more files just copy the file section again, but make sure they are all one after the other! If they are not it will not work! 
			}
		}
		//print($email_message);
		$ok = @mail($email_to, $email_subject, $email_message, $headers); 
		if($ok) {
			return true;
		//echo "<font face=verdana size=2>The file was successfully sent!</font>"; 
		} else { 
			return false;
		//die("Sorry but the email could not be sent. Click <a href='Javascript:history.back()'>here</a> to go back and try again!"); 
		}
	}
	
	//******************** Function for getting the value of a field if query is passed***************
	//**********************************************************************************************
	function funcGetValueByQuery($show_sql,$cnnConnection)
	{
		$strReturn = "";
		if(!($rstSelect = mysql_query($show_sql,$cnnConnection)))
		{
			print("Can not execute query");
			exit();
		}
		if(mysql_num_rows($rstSelect)>0)
		{
			$strReturn = mysql_result($rstSelect,0,0);
		}		
		return $strReturn;
	}

	//******************** Function for displaying the ledger details ***************
function func_show_ledger_details($str_query,$cnn_connection,$crorcq,$str_type,$str_company_id,$qrt_voice_select)
{
$strReturn = "";
$pending =0;
$pendingAmt=0;
$pendingPer=0;
$approved=0;
$approvedAmt=0;
$approvedPer=0;
$declined=0;
$declinedAmt=0;
$declinedPer=0;
$canceled=0;
$canceledAmt=0;
$canceledPer=0;
$totamount=0;
$chequepending=0;
$chequependingAmt=0;
$chequependingPer=0;
$chequeapproved=0;
$chequeapprovedAmt=0;
$chequeapprovedPer=0;
$chequedeclined=0;
$chequedeclinedAmt=0;
$chequedeclinedPer=0;
$creditpending=0;
$creditpendingAmt=0;
$creditpendingPer=0;
$creditapproved=0;
$creditapprovedAmt=0;
$creditapprovedPer=0;
$creditdeclined=0;
$creditdeclinedAmt=0;
$creditdeclinedPer=0;
$creditcard=0;
$cheque=0;
$totalnum =0;
$chequeAmt=0;
$chequePer=0;
$creditcardAmt=0;
$creditcardPer=0;
$chequecanceled=0;
$chequecanceledAmt=0;
$chequecanceledPer=0;
$creditcanceled=0;
$creditcanceledAmt=0;
$creditcanceledPer=0;
$deducted_amt = 0;
$credit_count = 0;
$cardPer = 0;
$trans_voicefee = 0;
$charge_back_amount =0;
$charge_back_count = 0;
$str_companyname = "";
$i_charge_back = "";
$cancel_reason ="";
$i_credit = "";
$i_discount_rate = "";
$i_transaction_fee = "";
$i_reserve = "";
$voice_authcount =0;
$i_voiceauth_amt = 0;
$voiceauthPre = 0;
$chequenonpass =0;
$chequenonpassAmt = 0;
$chequepass =0;
$chequepassAmt = 0;
$creditnonpass =0;
$creditnonpassAmt = 0;
$creditpass =0;
$creditpassAmt = 0;
$passedPer=0;
$nonpassedPer=0;
$chequepassedPer=0;
$chequenonpassPer=0;
$creditpassedPer=0;
$creditnonpassPer=0;
$passed=0;
$passedAmt=0;
$nonpassed=0;
$nonpassedAmt=0;
$voice_count_uploads=0;
$i_net_Per="";
$i=0;
if($str_type != "")
{
	if($str_type != "A")
	{
		if($str_type == "S"){
			$str_type = "savings";
		}else if($str_type == "C"){
			$str_type = "checking";
		}else if($str_type == "M"){
			$str_type = "Master";
		}else if($str_type == "V"){
			$str_type = "Visa";
		}

	}
}
else
{
	$str_type = "A";
}
if(!($show_voice_sql = mysql_query($qrt_voice_select,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

if(!($show_sql = mysql_query($str_query,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
	if(mysql_num_rows($show_voice_sql)>0)
	{
		$voice_count_uploads = mysql_result($show_voice_sql,0,0);
	}
	// print $voice_count_uploads;
	// print($str_query);
	while($showval = mysql_fetch_array($show_sql)) {
		 $i=$i+1;
		 $totalnum = $totalnum + 1;
		 $trans_type = $showval[0];
		 $trans_status = $showval[1];
		 $trans_cancelstatus = $showval[2];
		 $trans_companyName = $showval[3];
		 $i_charge_back = $showval[4];
		 $i_credit = $showval[5];
		 $i_discount_rate = $showval[6];
		 $i_transaction_fee = $showval[7];
		 $i_reserve = $showval[8];
		 $trans_accounttype = $showval[9];
		 $trans_cardtype =  $showval[10];
		 $cancel_reason = $showval[11];
		 $trans_passstatus = $showval[12];
		 $trans_amount = $showval[13];
		 $trans_voicefee = $showval[14];
		// Check and credit card calculation.
		
		if ($trans_type =="C") {
			if($trans_cancelstatus =="N") {
				if($trans_passstatus !="PE") {
					if($trans_passstatus == "NP" && $trans_status =="P") {
						$chequenonpass = $chequenonpass + 1;
						$chequenonpassAmt = $chequenonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$chequepass = $chequepass + 1;
						$chequepassAmt = $chequepassAmt + $trans_amount;
					}
					if($trans_status =="A")	{
						$chequeapproved = $chequeapproved + 1;
						$chequeapprovedAmt = $chequeapprovedAmt + $trans_amount;
					}
					if($trans_status =="D")	{
						$chequedeclined = $chequedeclined + 1;
						$chequedeclinedAmt = $chequedeclinedAmt + $trans_amount;
					}
					$voice_authcount = $voice_authcount + 1;
				} else {
					$chequepending = $chequepending + 1;
					$chequependingAmt = $chequependingAmt + $trans_amount;
				}
			}else {
				$voice_authcount = $voice_authcount + 1;
				$chequecanceled = $chequecanceled + 1;
				$chequecanceledAmt = $chequecanceledAmt + $trans_amount;
				if($cancel_reason == "Chargeback") {
					$charge_back_count = $charge_back_count + 1;
				} else {
					$credit_count = $credit_count + 1;
				}
			}
			$cheque = $cheque + 1;
			$chequeAmt = $chequeAmt + $trans_amount;
		} else {
			$creditcard = $creditcard + 1;
			$creditcardAmt = $creditcardAmt + $trans_amount;
			if($trans_cancelstatus =="N") {
				if($trans_passstatus !="PE") {
					if($trans_passstatus == "NP" && $trans_status =="P") {
						$creditnonpass = $creditnonpass + 1;
						$creditnonpassAmt = $creditnonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$creditpass = $creditpass + 1;
						$creditpassAmt = $creditpassAmt + $trans_amount;
					}
					if($trans_status =="A")	{
						$creditapproved = $creditapproved + 1;
						$creditapprovedAmt = $creditapprovedAmt + $trans_amount;
					}
					if($trans_status =="D")	{
						$creditdeclined = $creditdeclined + 1;
						$creditdeclinedAmt = $creditdeclinedAmt + $trans_amount;
					}
					$voice_authcount = $voice_authcount + 1;
				} else {
					$creditpending = $creditpending + 1;
					$creditpendingAmt = $creditpendingAmt + $trans_amount;
				}
			}else {
				$voice_authcount = $voice_authcount + 1;
				$creditcanceled = $creditcanceled + 1;
				$creditcanceledAmt = $creditcanceledAmt + $trans_amount;
				if($cancel_reason == "Chargeback") {
					$charge_back_count = $charge_back_count + 1;
				} else {
					$credit_count = $credit_count + 1;
				}
			}
		}
	}
	
	// Total Amount and Quantity Summary Display.
	
	$pending = $chequepending + $creditpending;
	$pendingAmt= $chequependingAmt + $creditpendingAmt;
	$passed = $chequepass + $creditpass;
	$passedAmt = $chequepassAmt + $creditpassAmt;
	$nonpassed = $chequenonpass + $creditnonpass;
	$nonpassedAmt = $chequenonpassAmt + $creditnonpassAmt;
	$approved= $chequeapproved + $creditapproved;
	$approvedAmt= $chequeapprovedAmt + $creditapprovedAmt;
	$declined= $chequedeclined + $creditdeclined;
	$declinedAmt= $chequedeclinedAmt + $creditdeclinedAmt;
	$canceled=$chequecanceled + $creditcanceled;
	$canceledAmt=$chequecanceledAmt + $creditcanceledAmt;
	$totamount = $creditcardAmt + $chequeAmt ;
	//$totamount = $approvedAmt;

	$charge_back_amount = ($charge_back_count * $i_charge_back);
	$credit_amount = ($credit_count * $i_credit);
	$i_discount_amt = (($i_discount_rate * $approvedAmt) / 100);
	$i_transaction_amt = ($i_transaction_fee * $i);
	$i_reserve_amt = (($i_reserve * $approvedAmt) / 100);
	$i_voiceauth_amt = ($voice_authcount * ($trans_voicefee * 2));
	$i_total_voice_amt = ($voice_count_uploads * $trans_voicefee);
	$deducted_amt =  ($charge_back_amount + $credit_amount + $canceledAmt + $i_discount_amt + $i_transaction_amt + $i_reserve_amt + $i_voiceauth_amt);
	$i_net_amt = ($approvedAmt - $deducted_amt);
//	$i_net_Per =  (($totamount - $deducted_amt)/$totamount);
		if($chequeAmt!=0) {
		   $chequependingPer=number_format(($chequependingAmt/$chequeAmt)*100,2);
		   $chequepassedPer=number_format(($chequepassAmt/$chequeAmt)*100,2);
		   $chequenonpassPer=number_format(($chequenonpassAmt/$chequeAmt)*100,2);
		   $chequeapprovedPer=number_format(($chequeapprovedAmt/$chequeAmt)*100,2);
		   $chequedeclinedPer=number_format(($chequedeclinedAmt/$chequeAmt)*100,2);
		   $chequecanceledPer=number_format(($chequecanceledAmt/$chequeAmt)*100,2);
		   $chequePer =number_format($chequedeclinedPer + $chequeapprovedPer + $chequependingPer+$chequecanceledPer+$chequenonpassPer+$chequepassedPer,2);
		}
		if($creditcardAmt !=0) {
		   $creditpendingPer=number_format(($creditpendingAmt/$creditcardAmt)*100,2);
		   $creditpassedPer=number_format(($creditpassAmt/$creditcardAmt)*100,2);
		   $creditnonpassPer=number_format(($creditnonpassAmt/$creditcardAmt)*100,2);
		   $creditapprovedPer=number_format(($creditapprovedAmt/$creditcardAmt)*100,2);
		   $creditdeclinedPer=number_format(($creditdeclinedAmt/$creditcardAmt)*100,2);
		   $creditcanceledPer=number_format(($creditcanceledAmt/$creditcardAmt)*100,2);
		   $creditcardPer =number_format($creditdeclinedPer + $creditapprovedPer + $creditpendingPer+$creditcanceledPer+ $creditpassedPer+ $creditnonpassPer,2);
		}
		if($totamount !=0) {
		   $pendingPer=number_format(($pendingAmt/$totamount)*100,2);
		   $passedPer=number_format(($passedAmt/$totamount)*100,2);
		   $nonpassedPer=number_format(($nonpassedAmt/$totamount)*100,2);
		   $approvedPer=number_format(($approvedAmt/$totamount)*100,2);
		   $declinedPer=number_format(($declinedAmt/$totamount)*100,2);
		   $canceledPer=number_format(($canceledAmt/$totamount)*100,2);
		   $voiceauthPre=number_format(($i_total_voice_amt/$totamount)*100,2); 
		   $cardPer =number_format($declinedPer + $approvedPer + $pendingPer+$canceledPer+$passedPer+$nonpassedPer,2);
		}
	   if($i>0)
	   {

		   print("<table width='100%' border='0'>");
		   if($str_company_id != "A")
		   {
			print("<tr><td align='center' colspan='3'><br><P align='center'><font face='verdana' size='2'><B>$trans_companyName</B></font><br></td></tr>");
		   }
		   if($crorcq=='H' || $crorcq=='A')
		   {
			   print("<tr><td  valign='top'><br>");
			   print("<P align='center'><font face='verdana' size='2'><B>Credit Card Summary</span><br>");
			   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#78B6C2'>");
			   print("<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>");	
			   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td></tr><tr>");
			   print("<td align='left' class='cl1'><font face='verdana' size='1'><b>Pending</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditpending</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpendingAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpendingPer,2)."</font></td>");

			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditnonpass</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditnonpassAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditnonpassPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditpass</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpassAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpassedPer,2)."</font></td>");

			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditapproved</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditapprovedAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditapprovedPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditdeclined</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditdeclinedAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditdeclinedPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditcanceled</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditcanceledAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditcanceledPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'><b>&nbsp;$creditcard</b></font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($creditcardAmt,2)."</b></font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format(round($creditcardPer),2)."</font>");	   
			   print("</td></tr></table>");
			   print("</td>");
		   }
		  $valgg="";
		  if($crorcq=='C'){
			 $valgg=" colspan=2 ";
		  }
		  if($crorcq=='C'){
			 $valgg=" colspan=2 ";
		  }
		if($crorcq=='C' || $crorcq=='A')
		{
		   $valgg=true;
		   print("<td $valgg  valign='top'><br>");
		   print("<P align='center'><font face='verdana' size='2'><B>Check Summary</span><br>");
		   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#78B6C2'>");
		   print("<td align='center' class='cl1'><span class='subhd'>Check Details</span></td>");	
		   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td></tr><tr>");
		   print("<td align='left' class='cl1'><font face='verdana' size='1'><b>Pending</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequepending</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequependingAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequependingPer,2)."</font></td>");

		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequenonpass</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequenonpassAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequenonpassPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequepass</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequepassAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequepassedPer,2)."</font></td>");

		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequeapproved</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequeapprovedAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequeapprovedPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequedeclined</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequedeclinedAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequedeclinedPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequecanceled</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequecanceledAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequecanceledPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'><b>&nbsp;$cheque</b></font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($chequeAmt,2)."</b></font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format(round($chequePer),2)."</font>");	   
		   print("</td></tr></table>");
		   print("</td>");
		}
	   print("<td $valgg  valign='top'><br>");
	   print("<P align='center'><font face='verdana' size='2'><B>Total Summary</span><br>");
	   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>");
	  
	   print("<tr height='30' bgcolor='#78B6C2'><td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>");	
	   print("<td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
	   print("<td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");
	  
  	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'><b>&nbsp;$totalnum</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($totamount,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($cardPer,2)."</font></td></tr>");	   

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending</span></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$pending</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($pendingAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($pendingPer,2)."</font></td></tr>");
	   
	   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$passed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedPer,2)."</font></td></tr>");

	   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$nonpassed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'><b>&nbsp;$approved</b></font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($approvedAmt,2)."</b></font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($approvedPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$declined</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$canceled</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Voice Upload</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$voice_count_uploads</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_total_voice_amt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($voiceauthPre,2)."</font></td></tr>");
	   
	   print("<tr bgcolor='#78B6C2'><td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Deducted Amount (".func_get_processing_currency($str_company_id).")</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td></td></tr>");

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($charge_back_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_charge_back,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_credit,2)."</font></td></tr>");
	   
//	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Credit</b></font></td>");
//	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledAmt,2)."</font></td>");
//	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Discount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_discount_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_discount_rate,2)."%</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Transaction Fee</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_transaction_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_transaction_fee,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Reserve Fee</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_reserve_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_reserve,2)."%</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Voice Authorization</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_voiceauth_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($trans_voicefee,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Total Deduction</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($deducted_amt,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td></tr>");
	   
	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;");
	   if ($i_net_amt < 0) {
		   print("(");
	   }
	   print(number_format($i_net_amt,2));
	   if ($i_net_amt < 0) {
		   print(")");
	   }  
	   print("</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;-</font></td></tr>");	   

	   print("</table>");
	   print("</td>");
	   print("</tr></table>");
	}
	else
	{
		if($str_company_id == "A")
		{
			print("<br>");
		/*if($str_company_id != "A")
		{
			print("<P align='center'><font face='verdana' size='2'><B>".func_get_value_of_field($cnn_connection,"cs_companydetails","companyname","userid",$str_company_id)."</B></font></p>");
		}*/
			print("<center><font face='verdana' size='1' ><B>No transactions for this period</B></font><center><br>");
		}
	}
	  
}
 //function to get the value of a field if id is passed
 function func_get_value_of_field($cnn_connection,$str_table,$str_get_field,$str_compare_field,$str_value)
 {
	$qry_select = "SELECT ".$str_get_field." FROM ".$str_table." WHERE ".$str_compare_field." = ".$str_value;
	$rst_select = mysql_query($qry_select,$cnn_connection);
	$str_return_value = "";
	if(mysql_num_rows($rst_select)>0)
	{
		$str_return_value = mysql_result($rst_select,0,0);
	}
	return($str_return_value );
 }

function func_isauthorisationno_exists($authorisationno,$telephoneno,$companyid,$cnn_connection)
{
	$i_return_value = "";
	$str_status_tid="";
	$qry_select_no = "Select status,transactionId from cs_transactiondetails where voiceAuthorizationno='".$authorisationno."' and phonenumber = '$telephoneno' and (status != 'D' or passstatus != 'NP')";// userId = $companyid" ;
	// print $qry_select_no;
	$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
	if(mysql_num_rows($rst_select_no)>0)
	{
		$str_status = mysql_result($rst_select_no,0,0);
		$str_status_tid = mysql_result($rst_select_no,0,1);
		switch($str_status)
		{
			case "P" :
				$i_return_value = "Pending";
				break;
			case "A" :
				$i_return_value = "Approved";
				break;
			case "D" :
				$i_return_value = "Declined";
				break;
			default: 
				$i_return_value = "Pending";
		}
	}
	return $str_status_tid;
}
function func_isauthorisationno_existsinrebill($authorisationno,$telephoneno,$companyid,$cnn_connection)
{
	$i_return_value = "";
	$qry_select_no = "Select status,transactionId from cs_rebillingdetails where voiceAuthorizationno='".$authorisationno."' and phonenumber = '$telephoneno' ";// and userId = $companyid" ;
	$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
	if(mysql_num_rows($rst_select_no)>0)
	{
		$i_return_value = "Pending";
	}
	return $i_return_value;
}

	function func_is_nopass_2times($cs_cnn,$str_voice_id,$i_company_id)
	{
		$is_nopass_2times = false;
		$sql_select = mysql_query("select pass_count from cs_transactiondetails where voiceAuthorizationno = '$str_voice_id' and userid = $i_company_id",$cs_cnn);
		if(mysql_num_rows($sql_select)>0)
		{
			$i_pass_count = mysql_result($sql_select,0,0);
			$is_nopass_2times = $i_pass_count > 0 ? true : false;
		}

		return $is_nopass_2times;
	}

	/*function func_is_nopass_2times($cs_cnn,$str_phone_number)
	{
		$is_nopass_2times = false;
		$sql_select = mysql_query("select count(*) from cs_transactiondetails where phonenumber = '$str_phone_number' and ( passStatus = 'NP' or passStatus = 'ND' )",$cs_cnn);
		if(mysql_num_rows($sql_select)>0)
		{
			$i_nopass_count = mysql_result($sql_select,0,0);
			$is_nopass_2times = $i_nopass_count > 1 ? true : false;
		}
		print($is_nopass_2times."<br>");
		print("select count(*) from cs_transactiondetails where phonenumber = '$str_phone_number' and ( passStatus = 'NP' or passStatus = 'ND' )");
		return $is_nopass_2times;
	}*/

	function funcFillCancellationReason($strReason,$strType)
	{
		if($strType != "Check" ){
			$arrReason[0] = "Bank return";
			$arrReason[1] = "Customer cancel";
			$arrReason[2] = "Chargeback";
			$arrReason[3] = "Credit";
			$arrReason[4] = "Closed Account";
			$arrReason[5] = "NSF";
			$arrReason[6] = "Invalid Account #";
			$arrReason[7] = "Invalid Account";
			$arrReason[8] = "Invalid Routing #";
			$arrReason[9] = "Invalid Card";
			$arrReason[10] = "Invalid Card #";
			$arrReason[11] = "AVS Return";
			$arrReason[12] = "Shipping Cancel";
			$arrReason[13] = "Customer Service";						
			$arrReason[14] = "Fraudulent";
			$arrReason[15] = "Stop payment";
			$i_count = 15;
		}else{$arrReason[0] = "Bank return";
			$arrReason[1] = "Customer cancel";
			$arrReason[2] = "Chargeback";
			$arrReason[3] = "Credit";
			$arrReason[4] = "Closed Account";
			$arrReason[5] = "NSF";
			$arrReason[6] = "Invalid Account #";
			$arrReason[7] = "Invalid Account";
			$arrReason[8] = "Invalid Routing #";
			$arrReason[9] = "Invalid Card";
			$arrReason[10] = "Invalid Card #";
			$arrReason[11] = "AVS Return";
			$arrReason[12] = "Shipping Cancel";
			$arrReason[13] = "Customer Service";						
			$arrReason[14] = "Fraudulent";
			$arrReason[15] = "Stop payment";
			$i_count = 15;
		}
		for($iLoop = 0;$iLoop<=$i_count;$iLoop++)
		{
			if($arrReason[$iLoop]==Trim($strReason)){
				echo("<option value='$arrReason[$iLoop]' selected>$arrReason[$iLoop]</option>");
			}else{
				echo("<option value='$arrReason[$iLoop]'>$arrReason[$iLoop]</option>");
			}	
		}
	}

function funcFillDeclineReason($strReason,$strType) {
		if($strType == "Check" ){
			$arrReason[0] = "NSF";
			$arrReason[1] = "Invalid Account";
			$arrReason[2] = "Closed Account";
			$arrReason[3] = "Invalid Account #";
			$arrReason[4] = "Invalid Routing #";
			$arrReason[5] = "Closed Account";
			$arrReason[6] = "NSF";
			$arrReason[7] = "Invalid CC #";
			$arrReason[8] = "Invalid EXP";
			$i_count = 8;
		}else{
			$arrReason[0] = "Invalid Account #";
			$arrReason[1] = "Invalid Routing #";
			$arrReason[2] = "Closed Account";
			$arrReason[3] = "NSF";
			$arrReason[4] = "Invalid CC #";
			$arrReason[5] = "Invalid EXP";
			$i_count = 5;		
		}
		for($iLoop = 0;$iLoop<=$i_count;$iLoop++)
		{
			if(($arrReason[$iLoop]==Trim($strReason)) && ($strReason !="")){
				echo("<option value='$arrReason[$iLoop]' selected>$arrReason[$iLoop]</option>");
			}else{
				echo("<option value='$arrReason[$iLoop]'>$arrReason[$iLoop]</option>");
			}	
		}
}

function func_fill_year($i_year)
	{
	//   print ($i_year);
		for($i_loop=2003;$i_loop<$i_year+10;$i_loop++)
		{
			if($i_year == $i_loop)
			{
				print("<option value='".$i_loop."' selected>".$i_loop."</option>");
			}
			else
			{
				print("<option value='".$i_loop."'>".$i_loop."</option>");
			}
		}
	}

function func_fill_month($i_month)
	{
		for($i_loop=1;$i_loop<13;$i_loop++)
		{
			if($i_month == $i_loop)
			{
				print("<option value='".$i_loop."' selected>".date("F",mktime(0,0,0,$i_loop,1,2000))."</option>");
			}
			else
			{
				print("<option value='".$i_loop."'>".date("F",mktime(0,0,0,$i_loop,1,2000))."</option>");
			}
		}
	}
	
	function func_fill_day($i_day)
	{
		for($i_loop=1;$i_loop<32;$i_loop++)
		{
			if($i_day == $i_loop)
			{
				print("<option value='".$i_loop."' selected>".$i_loop."</option>");
			}
			else
			{
				print("<option value='".$i_loop."'>".$i_loop."</option>");
			}
		}
	}	
	
	//function for sending mail
	function func_send_mail($str_from,$str_to,$str_subject,$str_message)
	{
		if($str_from=="") {
			$str_from = "sales@etelegate.com";
		}
		$headers = "From: $str_from\n";  // Who the email was sent from
	    $headers .= "Reply-To: $str_from\n"; // Reply to address
		$headers .= "X-Mailer: PHP\n"; // mailer
	    $headers .= "X-Priority: 1\n"; // The priority of the mail
		$headers .= "Return-Path: <$str_from>\n";  // Return path for errors
		$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	//    mail($str_to, $str_subject, $str_message, $headers);
		$ok = @mail($str_to, $str_subject, $str_message, $headers); 
		if($ok) {
			return true;
		//echo "<font face=verdana size=2>The file was successfully sent!</font>"; 
		} else { 
			return false;
		//die("Sorry but the email could not be sent. Click <a href='Javascript:history.back()'>here</a> to go back and try again!"); 
		}
}
	
	//function for adding call back
	function funcCallBack($cnn_cs,$iTransactionId)
	{
		if($iTransactionId != "")
		{
			$qryGetUser = "select userid from cs_transactiondetails where transactionId =".$iTransactionId;
			$iUserId = 	funcGetValueByQuery($qryGetUser,$cnn_cs);
			$qryGetUserName = "select  companyname from cs_companydetails where userId =".$iUserId;
			$iUserName = funcGetValueByQuery($qryGetUserName,$cnn_cs);
			$strCurrentDateTime = func_get_current_date_time();
			$qryInsert = "insert into cs_callback (userid,transactionid,dateandtime) values (";
			$qryInsert .= "'$iUserId','$iTransactionId','$strCurrentDateTime')";
			if(!mysql_query($qryInsert))
			{
				print("Can not execute query");
				exit();
			} 	
		}
	}		
	
	 //******************	Function for filling a combo with result from a query ***************
	 //******************************************************************************************

	 function func_fill_combo_conditionally($str_qry,$str_selected_value,$cnn_connection)
	 {
		$rst_select = mysql_query($str_qry,$cnn_connection);
		if(mysql_num_rows($rst_select)>0)
		 {
			for($i_loop=0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
			 {
				$str_value = mysql_result($rst_select,$i_loop,0);
				$str_field = mysql_result($rst_select,$i_loop,1); 
				if($str_selected_value == $str_value)
				{	?>
					<option value="<?php print($str_value); ?>" selected><?php print($str_field); ?></option>
<?php			}
				else
				{ ?>
					<option value="<?php print($str_value); ?>"><?php print($str_field); ?></option>
<?php			}

			 }
		 }
	 }
	 
	 //******************	Function for calculating the total call duration for found or unfound calls ***************

	function func_get_total_call_duration($arr_call_duration)
	{
		$i_hour= 0;
		$i_min = 0;
		$i_sec = 0;
		$str_total_duration = "";
		if (isset($arr_call_duration))
		{
			//print($qry_select);
			$i_temp_hour = 0;
			$i_temp_min = 0;
			$i_temp_sec = 0;
			for($i=0;$i<sizeof($arr_call_duration);$i++)
			{
				$str_duration = $arr_call_duration[$i];
				if($str_duration != "")
				{
					if(strlen($str_duration) == 8)
					{
						$i_temp_hour = substr($str_duration,0,2);
						$i_temp_min = substr($str_duration,3,2);
						$i_temp_sec = substr($str_duration,6,2);

						$i_hour += $i_temp_hour;
						$i_min += $i_temp_min;
						$i_sec += $i_temp_sec;
		
						if($i_sec > 59)
						{
							$i_sec -= 60;
							$i_min += 1;
						}
						if($i_min > 59)
						{
							$i_min -= 60;
							$i_hour += 1;
						}
					}
				}
			}
			if($i_hour < 10)
				$i_hour = "0".$i_hour;
			if($i_min < 10)
				$i_min = "0".$i_min;
			if($i_sec < 10)
				$i_sec = "0".$i_sec;
		}
		$str_total_duration = $i_hour.":".$i_min.":".$i_sec;
		return $str_total_duration;
	} 
	
function func_numbering_list($fill_num) 
{
	for($i_num=1;$i_num<=$fill_num;$i_num++) {
		print("<option value='".$i_num."'>".$i_num."</option>");
	}
}

function func_get_date_diff_from_current_day($str_date)
{
	//This function will tell you the proper integer number of days between today and the given date.
	//dates in the past will be negative, today will be 0, and future dates will be positive
	//$alarmDate is in standard mySQL format of "YYYY-MM-DD HH:MM:SS"

	$year = substr($str_date,0,4);
	$month = substr($str_date,5,2);
	$day = substr($str_date,8,2);


	$Y = intval($year);
	$m = intval($month);
	$d = intval($day);
	
	$date_diff = (  (mktime(0,0,0,date("m"),date("d"),$i_year)-mktime(0,0,0,$m,$d,$Y)) / 86400 ) ;

	if (abs($date_diff) == $date_diff) //it's positive so use ceil
	$date_diff = ceil($date_diff);
	else //it's negative so use floor
	$date_diff = floor($date_diff);
	return $date_diff;
}
// State Abbreviation	
function func_state_abbreviation($str_state) {
	switch($str_state) {
		case "Armed Forces-The Americas" :
			$return_state_val ="AA";
			break;
		case "Armed Forces-Europe" :
			$return_state_val ="AB";
			break;
		case "Armed Forces-Pacific" :
			$return_state_val ="AP";
			break;
		case "Alabama" :
			$return_state_val ="AL";
			break;
		case "Alaska" :
		 	$return_state_val ="AK";
			break;
		case "Arizona" :
			$return_state_val ="AZ";
			break;
		case "Arkansas" :	
			$return_state_val ="AR";
			break;
		case "California" :
			$return_state_val ="CA";
			break;
		case "Colorado" :
			$return_state_val ="CO";
			break;
		case "Connecticut" :
			$return_state_val ="CN";
			break;
		case "District of Columbia" :
			$return_state_val ="DC";
			break;
		case "Delaware" :
			$return_state_val ="DE";
			break;
		case "Florida" :
			$return_state_val ="FL";
			break;
		case "Federated States of Micronesia" :
			$return_state_val ="FM";
			break;
		case "Georgia" :
			$return_state_val ="GA";
			break;
		case "Guam" :
			$return_state_val ="GU";
			break;
		case "Hawaii" :
			$return_state_val ="HI";
			break;
		case "Iowa" :
			$return_state_val ="IA";
			break;
		case "Idaho" :
			$return_state_val ="ID";
			break;
		case "Illinois" :
			$return_state_val ="IL";
			break;
		case "Indiana" :
			$return_state_val ="IN";
			break;
		case "Iowa" :
			$return_state_val ="IO";
			break;
		case "Kansas" :
			$return_state_val ="KS";
			break;
		case "Kentucky" :
			$return_state_val ="KY";
			break;
		case "Louisiana" :
			$return_state_val ="LA";
			break;
		case "Maine" :
			$return_state_val ="ME";
			break;
		case "Maryland" :
			$return_state_val ="MD";
			break;
		case "Massachusetts" :
			$return_state_val ="MA";
			break;
		case "Michigan" :
			$return_state_val ="MI";
			break;
		case "Minnesota" :
			$return_state_val ="MN";
			break;
		case "Mississippi" :
			$return_state_val ="MS";
			break;
		case "Missouri" :
			$return_state_val ="MO";
			break;
		case "Montana" :
			$return_state_val ="MT";
			break;
		case "Nebraska" :
			$return_state_val ="NE";
			break;
		case "Nevada" :
			$return_state_val ="NV";
			break;
		case "New Hampshire" :
			$return_state_val ="NH";
			break;
		case "New Jersey" :
			$return_state_val ="NJ";
			break;
		case "New Mexico" :
			$return_state_val ="NM";
				break;
		case "New York" :
			$return_state_val ="NY";
			break;
		case "North Carolina" :
			$return_state_val ="NC";
			break;
		case "North Dakota" :
			$return_state_val ="NK";
			break;
		case "Ohio" :
			$return_state_val ="OH";
			break;
		case "Oklahoma" :
			$return_state_val ="OK";
			break;
		case "Oregon" :
			$return_state_val ="OR";
			break;
		case "Pennsylvania" :
			$return_state_val ="PA";
			break;
		case "Puerto Rico" :
			$return_state_val ="PR";
			break;
		case "Palau" :
			$return_state_val ="PW";
			break;
		case "Rhode Island" :
			$return_state_val ="RI";
			break;
		case "South Carolina" :
			$return_state_val ="SC";
			break;
		case "South Dakota" :
			$return_state_val ="SD";
			break;
		case "Tennessee" :
			$return_state_val ="TN";
			break;
		case "Texas" :
			$return_state_val ="TX";
			break;
		case "Utah" :
			$return_state_val ="UT";
			break;
		case "Vermont" :
			$return_state_val ="VT";
			break;
		case "Virginia" :
			$return_state_val ="VA";
			break;
		case "Washington" :
			$return_state_val ="WA";
			break;
		case "Washington DC" :
			$return_state_val ="WD";
			break;
		case "West Virginia" :
			$return_state_val ="WV";
			break;
		case "Wisconsin" :
			$return_state_val ="WI";
			break;
		case "Wyoming" :
			$return_state_val ="WY";
			break;
		default :
			$return_state_val ="";
	}
	return $return_state_val;
}

// Country Abbreviation
	
function func_country_abbreviation($str_country) {
	switch($str_country) {
	case "Afghanistan" :
		$return_country_val ="AF";
		break;
	case "Albania" :
		$return_country_val ="AL";
		break;
	case "Algeria" :
		$return_country_val ="DZ";
		break;
	case "Andorra" :
		$return_country_val ="Ad";
		break;
	case "American Samoa" :
		$return_country_val ="AS";
		break;
	case "Angola" :
		$return_country_val ="AO";
		break;
	case "Antigua and Barbuda" :
		$return_country_val ="AG";
		break;
	case "Argentina" :
		$return_country_val ="AR";
		break;
	case "Armenia" :
		$return_country_val ="AM";
		break;
	case "Australia" :
		$return_country_val ="AU";
		break;
	case "Austria" :
		$return_country_val ="AT";
		break;
	case "Azerbaijan" :
		$return_country_val ="AZ";
		break;
	case "Bahamas" :
		$return_country_val ="BS";
		break;
	case "Bahrain" :
		$return_country_val ="BH";
		break;
	case "Bangladesh" :
		$return_country_val ="BD";
		break;
	case "Barbados" :
		$return_country_val ="BB";
		break;
	case "Belarus" :
		$return_country_val ="BY";
		break;
	case "Belgium" :
		$return_country_val ="BE";
		break;
	case "Belize" :
		$return_country_val ="BZ";
		break;
	case "Benin" :
		$return_country_val ="BJ";
		break;
	case "Bhutan" :
		$return_country_val ="BT";
		break;
	case "Bolivia" :
		$return_country_val ="BO";
		break;
	case "Bosnia" :
		$return_country_val ="BA";
		break;
	case "Botswana" :
		$return_country_val ="BW";
		break;
	case "Brazil" :
		$return_country_val ="BR";
		break;
	case "British virgin Islands" :
		$return_country_val ="VG";
		break;
	case "Brunei" :
		$return_country_val ="BN";
		break;
	case "Bulgaria" :
		$return_country_val ="BG";
		break;
	case "Burkina Faso" :
		$return_country_val ="BF";
		break;
	case "Burundi" :
		$return_country_val ="BI";
		break;
	case "Cameroon" :
		$return_country_val ="CM";
		break;
	case "Canada" :
		$return_country_val ="CA";
		break;
	case "Cape Verde" :
		$return_country_val ="CV";
		break;
	case "Central African" :
		$return_country_val ="CF";
		break;
	case "Chad" :
		$return_country_val ="TD";
		break;
	case "Chile" :
		$return_country_val ="CL";
		break;
	case "China" :
		$return_country_val ="CN";
		break;
	case "Colombia" :
		$return_country_val ="CO";
		break;
	case "Comoros" :
		$return_country_val ="KM";
		break;
	case "Congo" :
		$return_country_val ="CG";
		break;
	case "Costa Rica" :
		$return_country_val ="CR";
		break;
	case "Croatia" :
		$return_country_val ="HR";
		break;
	case "Cuba" :
		$return_country_val ="CU";
		break;
	case "Cyprus" :
		$return_country_val ="CY";
		break;
	case "Czech Republic" :
		$return_country_val ="CZ";
		break;
	case "Côte d'Ivoire" :
		$return_country_val ="CI";
		break;
	case "Denmark" :
		$return_country_val ="DK";
		break;
	case "Djibouti" :
		$return_country_val ="DJ";
		break;
	case "Dominica" :
		$return_country_val ="DM";
		break;
	case "Dominican Republic" :
		$return_country_val ="DO";
		break;
	case "East Timor" :
		$return_country_val ="TP";
		break;
	case "Ecuador" :
		$return_country_val ="EC";
		break;
	case "Egypt" :
		$return_country_val ="EG";
		break;
	case "El Salvador" :
		$return_country_val ="SV";
		break;
	case "Equatorial Guinea" :
		$return_country_val ="GQ";
		break;
	case "Eritrea" :
		$return_country_val ="ER";
		break;
	case "Estonia" :
		$return_country_val ="EE";
		break;
	case "Ethiopia" :
		$return_country_val ="ET";
		break;
	case "Fiji" :
		$return_country_val ="FJ";
		break;
	case "Finland" :
		$return_country_val ="FI";
		break;
	case "France" :
		$return_country_val ="FR";
		break;
	case "Gabon" :
		$return_country_val ="GA";
		break;
	case "Gambia" :
		$return_country_val ="GM";
		break;
	case "Georgia" :
		$return_country_val ="GE";
		break;
	case "Germany" :
		$return_country_val ="DE";
		break;
	case "Ghana" :
		$return_country_val ="GH";
		break;
	case "Greece" :
		$return_country_val ="GR";
		break;
	case "Grenada" :
		$return_country_val ="GD";
		break;
	case "Guatemala" :
		$return_country_val ="GT";
		break;
	case "Guinea" :
		$return_country_val ="GN";
		break;
	case "Guyana" :
		$return_country_val ="GY";
		break;
	case "Haiti" :
		$return_country_val ="HT";
		break;
	case "Honduras" :
		$return_country_val ="HN";
		break;
	case "Hungary" :
		$return_country_val ="HU";
		break;
	case "Iceland" :
		$return_country_val ="IS";
		break;
	case "India" :
		$return_country_val ="IN";
		break;
	case "Indonesia" :
		$return_country_val ="ID";
		break;
	case "Iran" :
		$return_country_val ="IR";
		break;
	case "Iraq" :
		$return_country_val ="IQ";
		break;
	case "Ireland" :
		$return_country_val ="IE";
		break;
	case "Israel" :
		$return_country_val ="IL";
		break;
	case "Italy" :
		$return_country_val ="IT";
		break;
	case "Jamaica" :
		$return_country_val ="JM";
		break;
	case "Japan" :
		$return_country_val ="JP";
		break;
	case "Jordan" :
		$return_country_val ="JO";
		break;
	case "Kazakhstan" :
		$return_country_val ="KZ";
		break;
	case "Kenya" :
		$return_country_val ="KE";
		break;
	case "Kiribati" :
		$return_country_val ="KI";
		break;
	case "Korea" :
		$return_country_val ="KP";
		break;
	case "Kuwait" :
		$return_country_val ="KW";
		break;
	case "Kyrgyzstan" :
		$return_country_val ="KG";
		break;
	case "Laos" :
		$return_country_val ="LA";
		break;
	case "Latvia" :
		$return_country_val ="LV";
		break;
	case "Lebanon" :
		$return_country_val ="LB";
		break;
	case "Lesotho" :
		$return_country_val ="LS";
		break;
	case "Liberia" :
		$return_country_val ="LR";
		break;
	case "Libya" :
		$return_country_val ="LY";
		break;
	case "Liechtenstein" :
		$return_country_val ="LI";
		break;
	case "Lithuania" :
		$return_country_val ="LT";
		break;
	case "Luxembourg" :
		$return_country_val ="LU";
		break;
	case "Macedonia" :
		$return_country_val ="MK";
		break;
	case "Madagascar" :
		$return_country_val ="MG";
		break;
	case "Malawi" :
		$return_country_val ="MW";
		break;
	case "Malaysia" :
		$return_country_val ="MY";
		break;
	case "Maldives" :
		$return_country_val ="MV";
		break;
	case "Mali" :
		$return_country_val ="ML";
		break;
	case "Malta" :
		$return_country_val ="MT";
		break;
	case "Marshall Islands" :
		$return_country_val ="MH";
		break;
	case "Mauritania" :
		$return_country_val ="MR";
		break;
	case "Mauritius" :
		$return_country_val ="MU";
		break;
	case "Mexico" :
		$return_country_val ="MX";
		break;
	case "Micronesia" :
		$return_country_val ="FM";
		break;
	case "Moldova" :
		$return_country_val ="MD";
		break;
	case "Monaco" :
		$return_country_val ="MC";
		break;
	case "Mongolia" :
		$return_country_val ="MN";
		break;
	case "Morocco" :
		$return_country_val ="MA";
		break;
	case "Mozambique" :
		$return_country_val ="MZ";
		break;
	case "Myanmar" :
		$return_country_val ="MM";
		break;
	case "Namibia" :
		$return_country_val ="NA";
		break;
	case "Nauru" :
		$return_country_val ="NR";
		break;
	case "Nepal" :
		$return_country_val ="NP";
		break;
	case "Netherlands" :
		$return_country_val ="NL";
		break;
	case "New Zealand" :
		$return_country_val ="NZ";
		break;
	case "Nicaragua" :
		$return_country_val ="NI";
		break;
	case "Niger" :
		$return_country_val ="NE";
		break;
	case "Nigeria" :
		$return_country_val ="NG";
		break;
	case "Norway " :
		$return_country_val ="NO";
		break;
	case "Oman" :
		$return_country_val ="OM";
		break;
	case "Pakistan" :
		$return_country_val ="PK";
		break;
	case "Palau" :
		$return_country_val ="PW";
		break;
	case "Panama" :
		$return_country_val ="PA";
		break;
	case "Papua New Guinea" :
		$return_country_val ="PG";
		break;
	case "Paraguay" :
		$return_country_val ="PY";
		break;
	case "Peru" :
		$return_country_val ="PE";
		break;
	case "Philippines" :
		$return_country_val ="PH";
		break;
	case "Poland" :
		$return_country_val ="PL";
		break;
	case "Portugal" :
		$return_country_val ="PT";
		break;
	case "Qatar" :
		$return_country_val ="QA";
		break;
	case "Romania" :
		$return_country_val ="RO";
		break;
	case "Russia" :
		$return_country_val ="RU";
		break;
	case "Rwanda" :
		$return_country_val ="RW";
		break;
	case "Saint Kitts" :
		$return_country_val ="KN";
		break;
	case "Saint Lucia" :
		$return_country_val ="LC";
		break;
	case "Saint Vincent" :
		$return_country_val ="VC";
		break;
	case "Samoa" :
		$return_country_val ="WS";
		break;
	case "San Marino" :
		$return_country_val ="SM";
		break;
	case "Sao Tome and Principe" :
		$return_country_val ="ST";
		break;
	case "Saudi Arabia " :
		$return_country_val ="SA";
		break;
	case "Senegal" :
		$return_country_val ="SN";
		break;
	case "Serbia and Montenegro" :
		$return_country_val ="SO";
		break;
	case "Seychelles " :
		$return_country_val ="SC";
		break;
	case "Sierra Leone" :
		$return_country_val ="SL";
		break;
	case "Singapore" :
		$return_country_val ="SG";
		break;
	case "Slovakia" :
		$return_country_val ="SK";
		break;
	case "Slovenia" :
		$return_country_val ="SI";
		break;
	case "Solomon Islands" :
		$return_country_val ="SB";
		break;
	case "Somalia" :
		$return_country_val ="SO";
		break;
	case "South Africa" :
		$return_country_val ="ZA";
		break;
	case "Spain" :
		$return_country_val ="ES";
		break;
	case "Sri Lanka" :
		$return_country_val ="LK";
		break;
	case "Sudan" :
		$return_country_val ="SD";
		break;
	case "Suriname" :
		$return_country_val ="SR";
		break;
	case "Swaziland" :
		$return_country_val ="SZ";
		break;
	case "Sweden" :
		$return_country_val ="SE";
		break;
	case "Switzerland" :
		$return_country_val ="CH";
		break;
	case "Syria" :
		$return_country_val ="SY";
		break;
	case "Taiwan" :
		$return_country_val ="TW";
		break;
	case "Tajikistan" :
		$return_country_val ="TJ";
		break;
	case "Tanzania" :
		$return_country_val ="TZ";
		break;
	case "Thailand" :
		$return_country_val ="TH";
		break;
	case "Togo" :
		$return_country_val ="TG";
		break;
	case "Tonga" :
		$return_country_val ="TO";
		break;
	case "Trinidad and Tobago" :
		$return_country_val ="TT";
		break;
	case "Tunisia" :
		$return_country_val ="TN";
		break;
	case "Turkey" :
		$return_country_val ="TR";
		break;
	case "Turkmenistan" :
		$return_country_val ="TM";
		break;
	case "Tuvalu" :
		$return_country_val ="TV";
		break;
	case "Uganda" :
		$return_country_val ="UG";
		break;
	case "Ukraine" :
		$return_country_val ="UA";
		break;
	case "United Arab Emirates" :
		$return_country_val ="AE";
		break;
	case "United Kingdom" :
		$return_country_val ="GB";
		break;
	case "United States" :
		$return_country_val ="US";
		break;
	case "Uruguay" :
		$return_country_val ="UY";
		break;
	case "Uzbekistan" :
		$return_country_val ="UZ";
		break;
	case "Vanuatu" :
		$return_country_val ="VU";
		break;
	case "Vatican City" :
		$return_country_val ="VC";
		break;
	case "Venezuela" :
		$return_country_val ="VE";
		break;
	case "Vietnam" :
		$return_country_val ="VN";
		break;
	case "Western Sahara" :
		$return_country_val ="EH";
		break;
	case "Yemen" :
		$return_country_val ="YE";
		break;
	case "Zambia" :
		$return_country_val ="ZM";
		break;
	case "Zimbabwe" :
		$return_country_val ="ZW";
		break;
	default :
		$return_country_val ="";
	}
	return $return_country_val;
}

// Send Mail Function
function func_sendMail($comp_id,$str_subject,$str_message,$headers)
{
	$qry_select = "SELECT emailaddress FROM cs_orderemail where userid = ".$comp_id;		
	if(!($rssel_qry = mysql_query($qry_select))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
		if (mysql_num_rows($rssel_qry)>0)
		{
			for($i_NumRow=0;$i_NumRow<mysql_num_rows($rssel_qry);$i_NumRow++)
			{
				$str_to = mysql_result($rssel_qry,$i_NumRow,0);				
				$str_to_email = Trim($str_to);
				mail($str_to_email,$str_subject,$str_message,$headers);			
			}
		}
}			
	
function func_is_auto_approved($cnn_connection,$i_user_id)
{
		$is_auto_approved = false;
		$qry_select_no = "Select auto_approve from cs_companydetails where userId = $i_user_id" ;
		$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
		if(mysql_num_rows($rst_select_no)>0)
		{
			if(mysql_result($rst_select_no,0,0) == "Y")
			{
				$is_auto_approved = true;
			}
		}
		return $is_auto_approved;
	}

function func_get_company_ids_for_service_user($cnn_connection,$i_service_user_id)
{
	$str_company_ids = "";
	$qry_select_no = "Select company_ids from cs_customerserviceusers where id = $i_service_user_id" ;
	$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
	if(mysql_num_rows($rst_select_no)>0)
	{
		$str_company_ids = mysql_result($rst_select_no,0,0);
	}
	return $str_company_ids;
}

function func_isauthorisationno_check($authorisationno,$telephoneno,$companyid,$cnn_connection)
{
	$i_return_value = "";
	$str_status_tid="";
	$qry_select_no = "Select status,transactionId from cs_transactiondetails where voiceAuthorizationno='".$authorisationno."' and phonenumber = '$telephoneno' and (status != 'D' or passstatus != 'NP')";// userId = $companyid" ;
	//  print $qry_select_no;
	$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
	if(mysql_num_rows($rst_select_no)>0)
	{
		$str_status = mysql_result($rst_select_no,0,0);
		$str_status_tid = mysql_result($rst_select_no,0,1);
	}
	return $str_status_tid;
}
	
function func_company_select_alltype($mode) {
	$qry_select_company = "select distinct userid,companyname from cs_companydetails order by companyname";
    if(!($show_company_sql =mysql_query($qry_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value=''>Select</option>";
	if($mode=="view"){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_all_val = mysql_fetch_array($show_company_sql)) 
	{
		print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	  
	}

}
function func_company_select_activetype($mode) {
	$qry_select_activecompany = "select distinct userid,companyname from cs_companydetails where activeuser=1 order by companyname";
    if(!($show_active_sql =mysql_query($qry_select_activecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value=''>Select</option>";
	if($mode=="view"){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_active_val = mysql_fetch_array($show_active_sql)) 
	{
		print"<option value='$show_active_val[0]'>$show_active_val[1]</option>";	  
	}

}
function func_company_select_nonactivetype($mode) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where activeuser=0 order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
 	print "<option value=''>Select</option>";
	if($mode=="view"){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_multiselect_alltype() {
	$qrt_select_company = "select distinct userid,companyname from cs_companydetails order by companyname";
   	if(!($show_company_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_all_val = mysql_fetch_array($show_company_sql)) 
	{
		print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	  
	}

}

function func_multiselect_activetype() {
	$qrt_select_company = "select distinct userid,companyname from cs_companydetails where activeuser=1 order by companyname";
   	if(!($show_active_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_active_val = mysql_fetch_array($show_active_sql)) 
	{
		print"<option value='$show_active_val[0]'>$show_active_val[1]</option>";	  
	}
}

function func_multiselect_nonactivetype() {
	$qrt_select_company = "select distinct userid,companyname from cs_companydetails where activeuser=0 order by companyname";
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_multiselect_alltypeemailaddress() {
	$qrt_select_company = "select distinct email,companyname from cs_companydetails order by companyname";
   	if(!($show_company_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_all_val = mysql_fetch_array($show_company_sql)) 
	{
		print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	  
	}

}

function func_multiselect_activetypeemailaddress() {
	$qrt_select_company = "select distinct email,companyname from cs_companydetails where activeuser=1 order by companyname";
   	if(!($show_active_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_active_val = mysql_fetch_array($show_active_sql)) 
	{
		print"<option value='$show_active_val[0]'>$show_active_val[1]</option>";	  
	}
}

function func_multiselect_nonactivetypeemailaddress() {
	$qrt_select_company = "select distinct email,companyname from cs_companydetails where activeuser=0 order by companyname";
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value='A'>All Companies</option>";
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_select_companytype() {
	$all = 0;$active =0;$nonactive =0;
	$qrt_select_company = "select count(userId) from cs_companydetails";
	$qrt_select_company1 = "select count(userId) from cs_companydetails where activeuser=0";
	$qrt_select_company2 = "select count(userId) from cs_companydetails where activeuser=1";
   	if(!($show_all_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company1)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
   	if(!($show_active_sql = mysql_query($qrt_select_company2)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$all = mysql_result($show_all_sql,0,0);
	$active = mysql_result($show_active_sql,0,0);
	$nonactive = mysql_result($show_nonactive_sql,0,0);
	print "<option value='A'>All Companies ($all)</option>";
	print "<option value='AC' selected>Active Companies ($active)</option>";
	print "<option value='NC'>Non active Companies ($nonactive)</option>";
}

function func_select_companytrans_type($Company_transtype) {
	if($Company_transtype=='A')print "<option value='A' selected>All Merchants</option>";else print "<option value='A'>All Merchants</option>";
	if($Company_transtype=='ecom')print "<option value='ecom' selected>Ecommerce</option>";else print "<option value='ecom'>Ecommerce</option>";
	if($Company_transtype=='trvl')print "<option value='trvl' selected>Travel</option>";else print "<option value='trvl'>Travel</option>";
	if($Company_transtype=='phrm')print "<option value='phrm' selected>Pharmacy</option>";else print "<option value='phrm'>Pharmacy</option>";
	if($Company_transtype=='game')print "<option value='game' selected>Gaming</option>";else print "<option value='game'>Gaming</option>";
	if($Company_transtype=='adlt')print "<option value='adlt' selected>Adult</option>";else print "<option value='adlt'>Adult</option>";
	if($Company_transtype=='tele')print "<option value='tele' selected>Telemarketing</option>";else print "<option value='tele'>Telemarketing</option>";
	if($Company_transtype=='pmtg')print "<option value='pmtg' selected>Gateway</option>";else print "<option value='pmtg'>Gateway</option>";
	if($Company_transtype=='crds')print "<option value='crds' selected>Card Swipe</option>";else print "<option value='crds'>Card Swipe</option>";
}

function func_select_merchant_type($Company_transtype) {
	if($Company_transtype=='')print "<option value='' selected>Select</option>";else print "<option value=''>Select</option>";
	if($Company_transtype=='ecom')print "<option value='ecom' selected>Ecommerce</option>";else print "<option value='ecom'>Ecommerce</option>";
	if($Company_transtype=='trvl')print "<option value='trvl' selected>Travel</option>";else print "<option value='trvl'>Travel</option>";
	if($Company_transtype=='phrm')print "<option value='phrm' selected>Pharmacy</option>";else print "<option value='phrm'>Pharmacy</option>";
	if($Company_transtype=='game')print "<option value='game' selected>Gaming</option>";else print "<option value='game'>Gaming</option>";
	if($Company_transtype=='adlt')print "<option value='adlt' selected>Adult</option>";else print "<option value='adlt'>Adult</option>";
	if($Company_transtype=='tele')print "<option value='tele' selected>Telemarketing</option>";else print "<option value='tele'>Telemarketing</option>";
	if($Company_transtype=='pmtg')print "<option value='pmtg' selected>Gateway</option>";else print "<option value='pmtg'>Gateway</option>";
	if($Company_transtype=='crds')print "<option value='crds' selected>Card Swipe</option>";else print "<option value='crds'>Card Swipe</option>";
}

function func_multiselect_transaction($qrt_select_company) {
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($show_nonactive_sql) > 0) {
		print"<option value='A' selected>All Companies</option>";	  
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}
}

function func_select_company_from_query($qrt_select_company) {
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
 	print "<option value=''>Select</option>";
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}
}

function func_multiselect_byquery($qrt_select_company) {
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($show_nonactive_sql) > 0) {
		print"<option value=''>Select</option>";	  
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}
}

function func_select_mailcompanytype($companytype) {
	$all = 0;$active = 0;$nonactive = 0;$reseller_ref = 0;$etelegate_ref = 0;
	$qrt_select_company = "select activeuser, reseller_id from cs_companydetails";
   	if(!($show_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($show_val = mysql_fetch_array($show_sql)) 
	{
		if ($show_val[0] == 1) {
			$active++;
		} else {
			$nonactive++;
		}
		if ($show_val[1] != "") {
			$reseller_ref++;
		} else {
			$etelegate_ref++;
		}
		$all++;
	}
	if($companytype=="A")print "<option value='A' selected>All Companies ($all)</option>";else print "<option value='A'>All Companies ($all)</option>";
	if($companytype=="AC")print "<option value='AC' selected>Active Companies ($active)</option>";else print "<option value='AC'>Active Companies ($active)</option>";
	if($companytype=="NC")print "<option value='NC' selected>Non active Companies ($nonactive)</option>";else print "<option value='NC'>Non active Companies ($nonactive)</option>";
	if($companytype=="RE")print "<option value='RE' selected>Reseller Referrals ($reseller_ref)</option>";else print "<option value='RE'>Reseller Referrals ($reseller_ref)</option>";
	//if($companytype=="ET")print "<option value='ET' selected>Etelegate Referrals ($etelegate_ref)</option>";else print "<option value='ET'>Etelegate Referrals ($etelegate_ref)</option>";
}

function func_check_isnumber($strnum)
{
	//returns 1 if valid number (only numeric string), 0 if not
	if (ereg('^[[:digit:]]+$', $strnum)) 
   		return 1;
 	else 
   		return 0;
}

//returns 1 if valid number (only numeric string including dot val), 0 if not
function func_check_isnumberdot($strnum)
{
   $b_return = true;
   for ($i=0;$i<strlen($strnum);$i++)
   {
       $ascii_code=ord($strnum[$i]);
	  if (($ascii_code >=48 && $ascii_code <=57) || $ascii_code ==46 || $ascii_code ==32 ) {
       } else { 
          $b_return = false;
	  }
   }
	 	return $b_return;
}

// function to fill fontcombo
function func_fillfontcombo()
{
	print "<option value='Arial'>Arial</option>";
	print "<option value='Arial Black'>Arial Black</option>";
	print "<option value='Courier New'>Courier New</option>";
	print "<option value='Times New Roman'>Times New Roman</option>";
	print "<option value='verdana' selected>Verdana</option>";
}
function func_fillfontsizecombo()
{
	print "<option value='1'>1</option>";
	print "<option value='2' selected>2</option>";
	print "<option value='3'>3</option>";
	print "<option value='4'>4</option>";
	print "<option value='5'>5</option>";
}
function func_fillfonttypecombo()
{
	print "<option value='Bold'>Bold</option>";
	print "<option value='Italics'>Italics</option>";
	print "<option value='Normal' selected>Normal</option>";
}

function func_send_DNC_mail($cnn_cs,$str_mail_body) {
	//print("mail body= ".$str_mail_body);
	$str_from_id = "sales@etelegate.com";
	$str_mail_subject = "D.N.C Mail";
	$str_to_ids = array();
	$qry_select = "select dnc_email from cs_dnc_emails";
	if(!($rst_select = mysql_query($qry_select,$cnn_cs))) {
		print("Can not execute query");
		exit();
	}
	if(mysql_num_rows($rst_select))	{
		for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++) {
			$str_to_ids[$i_loop] = mysql_result($rst_select,$i_loop,0);
		}
	}
	if(is_array($str_to_ids) && count($str_to_ids) > 0) {
		for($i=0;$i<count($str_to_ids);$i++) {
			func_send_mail($str_from_id,$str_to_ids[$i],$str_mail_subject,$str_mail_body);
		}
	}
}

function func_read_file_directory_name($mydir) {

while(($file = $mydir->read()) !== false) {
      if(is_dir($mydir->path.$file)) {
         echo "Directory: $file<BR>";
      } else {
         echo "$file<BR>";
      }
   }
   $mydir->close();
}

function func_read_file_name_repeat($mydir) {
   
   while(($file = $mydir->read()) !== false) {
      echo "Filename: $file<BR>";
   }
   $mydir->rewind();
   echo "Displaying the directory list again...<BR>";
   while(($file = $mydir->read()) !== false) {
      echo "Filename: $file<BR>";
   }
   $mydir->close();
}

function func_read_file_uploaded_name($mydir,$company_id) {
$file_name ="";
while(($file = $mydir->read()) !== false) {
      if(is_dir($mydir->path.$file)) {
     //    echo "Directory: $file<BR>";
	  } else {
	  $company= split("_",$file);
		  if($company[0]==$company_id) {
	         if($file_name=="") {
			 	$file_name = $file;
			 } else {
			 	$file_name .= ",".$file;
			 }
		  }
      }
}
   $mydir->close();
   return $file_name;
}

/* The below functions are only used for the gateway users */

function func_gatewaycompany_select_alltype($mode,$sessionGatewaylogin) {
	$qry_select_company = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' order by companyname";
    if(!($show_company_sql =mysql_query($qry_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_company_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_all_val = mysql_fetch_array($show_company_sql)) 
	{
		print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	  
	}

}

function func_gatewaycompany_select_alltype_ledger($mode,$sessionGatewaylogin,$select,$user) {
	$qry_select_company = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' ";

	if($user=="1")
	{
		$qry_select_company .= "and activeuser=1 ";
	}
	if($user=="0")
	{
		$qry_select_company .= "and activeuser=0 ";
	}

	$qry_select_company .=" order by companyname";
    if(!($show_company_sql =mysql_query($qry_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_company_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_all_val = mysql_fetch_array($show_company_sql)) 
	{
		if($select != "")
		{
			if(intval($select) == $show_all_val[0])
			{
				print"<option value='$show_all_val[0]' selected>$show_all_val[1]</option>";	  
			}
			else{
			print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	
			}
		}
		else{
			print"<option value='$show_all_val[0]'>$show_all_val[1]</option>";	
		}
	}

}


function func_gatewaycompany_select_activetype($mode,$sessionGatewaylogin) {
	$qry_select_activecompany = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and activeuser=1 order by companyname";
    if(!($show_active_sql =mysql_query($qry_select_activecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_active_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_active_val = mysql_fetch_array($show_active_sql)) 
	{
		print"<option value='$show_active_val[0]'>$show_active_val[1]</option>";	  
	}

}
function func_gatewaycompany_select_nonactivetype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and activeuser=0 order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
 	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}
function func_gatewaycompany_select_resellertype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and reseller_id <> '' order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
 	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}
function func_gatewaycompany_select_etelegatetype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and reseller_id is null order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
 	print "<option value=''>Select</option>";
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_select_gateway_companytype($sessionGatewaylogin) {
	$all = 0;$active =0;$nonactive =0;
	$qrt_select_company = "select count(userId) from cs_companydetails where transaction_type='pmtg' ";
	$qrt_select_company1 = "select count(userId) from cs_companydetails where transaction_type='pmtg' and activeuser=0";
	$qrt_select_company2 = "select count(userId) from cs_companydetails where transaction_type='pmtg' and activeuser=1";
   	if(!($show_all_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
   	if(!($show_nonactive_sql = mysql_query($qrt_select_company1)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
   	if(!($show_active_sql = mysql_query($qrt_select_company2)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$all = mysql_result($show_all_sql,0,0);
	$active = mysql_result($show_active_sql,0,0);
	$nonactive = mysql_result($show_nonactive_sql,0,0);
	print "<option value='A' selected>All Companies ($all)</option>";
	print "<option value='AC' >Active Companies ($active)</option>";
	print "<option value='NC'>Non active Companies ($nonactive)</option>";
}

function func_select_gateway_companytype_legder($sessionGatewaylogin,$companytype) {
	$all = 0;$active = 0;$nonactive = 0;$reseller_ref = 0;$etelegate_ref = 0;
	$qrt_select_company = "select activeuser, reseller_id from cs_companydetails where transaction_type = 'pmtg' and gateway_id = '$sessionGatewaylogin'";
   	if(!($show_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($show_val = mysql_fetch_array($show_sql)) 
	{
		if ($show_val[0] == 1) {
			$active++;
		} else {
			$nonactive++;
		}
		if ($show_val[1] != "") {
			$reseller_ref++;
		} else {
			$etelegate_ref++;
		}
		$all++;
	}
	if($companytype=="A")print "<option value='A' selected>All Companies ($all)</option>";else print "<option value='A'>All Companies ($all)</option>";
	if($companytype=="AC")print "<option value='AC' selected>Active Companies ($active)</option>";else print "<option value='AC'>Active Companies ($active)</option>";
	if($companytype=="NC")print "<option value='NC' selected>Non active Companies ($nonactive)</option>";else print "<option value='NC'>Non active Companies ($nonactive)</option>";
	if($companytype=="RE")print "<option value='RE' selected>Reseller Referrals ($reseller_ref)</option>";else print "<option value='RE'>Reseller Referrals ($reseller_ref)</option>";
	//if($companytype=="ET")print "<option value='ET' selected>Etelegate Referrals ($etelegate_ref)</option>";else print "<option value='ET'>Etelegate Referrals ($etelegate_ref)</option>";
}


function func_checkUsernameExistInAnyTable($UserName,$cnnConnection)
{
	$str_return=0;
	//in company user table
	$qry_select = "Select userid from cs_companyusers where username='$UserName'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
		return $str_return;
	}
	// in company details table
	$qry_select = "Select userId from cs_companydetails where username='$UserName'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
		return $str_return;
	}
	// in merchant user table
	$qry_select = "Select id from cs_customerserviceusers where username='$UserName'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
		return $str_return;
	}
	// in admin table
	$qry_select = "Select userid from cs_login where username='$UserName'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
		return $str_return;
	}
	//in callcenter user table
	$qry_select = "Select cc_usersid from cs_callcenterusers where user_name='$UserName'";
	$rst_select = mysql_query($qry_select,$cnnConnection);
	if (mysql_num_rows($rst_select)>0)
	{
		$str_return=1;
		return $str_return;
	}
	
	//in tsr users table
	
	$qrySelect = "select tsr_user_id from cs_tsrusers where tsr_user_name ='$UserName'";
	$rstSelect = mysql_query($qrySelect,$cnnConnection);
	if ( mysql_num_rows($rstSelect) > 0 ) {
		$str_return=1;
		return $str_return;
	}
	
	//in reseller details table

	$qrySelect = "select reseller_id from cs_resellerdetails where reseller_username ='$UserName'";
	$rstSelect = mysql_query($qrySelect,$cnnConnection);
	if ( mysql_num_rows($rstSelect) > 0 ) {
		$str_return=1;
		return $str_return;
	}

	return $str_return;
}

function func_get_merchant_name($str_merchant_type) {
	$str_merchant_name = "";
	switch ($str_merchant_type) {
		case "ecom":	$str_merchant_name = "Ecommerce";
						break;
		case "trvl":	$str_merchant_name = "Travel";
						break;
		case "phrm":	$str_merchant_name = "Pharmacy";
						break;
		case "game":	$str_merchant_name = "Gaming";
						break;
		case "adlt":	$str_merchant_name = "Adult";
						break;
		case "tele":	$str_merchant_name = "Telemarketing";
						break;
		case "pmtg":	$str_merchant_name = "Gateway";
						break;
		case "crds":	$str_merchant_name = "Card Swipe";
						break;
		default    : 	$str_merchant_name = "Ecommerce";
						break;
	}
	return $str_merchant_name;
}

function func_gatewaycompany_multiselect_alltype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A' selected>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}
function func_gatewaycompany_multiselect_activetype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where  transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and activeuser=1 order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}
function func_gatewaycompany_multiselect_nonactivetype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where  transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and activeuser=0 order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_gatewaycompany_multiselect_resellertype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where  transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and reseller_id <> '' order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_gatewaycompany_multiselect_etelegatetype($mode,$sessionGatewaylogin) {
	$qry_select_nonactivecompany = "select distinct userid,companyname from cs_companydetails where  transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' and reseller_id is null order by companyname";
    if(!($show_nonactive_sql =mysql_query($qry_select_nonactivecompany)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_nonactive_sql) >0)){
	 	print "<option value='A'>All Companies</option>";
	}
	while($show_nonactive_val = mysql_fetch_array($show_nonactive_sql)) 
	{
		print"<option value='$show_nonactive_val[0]'>$show_nonactive_val[1]</option>";	  
	}

}

function func_send_cancel_mail($iCompanyId,$strTransactionType) {
	$str_trans_type		= $strTransactionType == "C" ? "Check" : "Card";
	$str_from_id		= "sales@etelegate.com";
	$str_mail_subject	= "Cancellation Of Transaction";
	$str_mail_body		= "Please Cancel the Following Transaction";
	/*$str_to_id = func_get_value_of_field($cnn_cs,"cs_bankdetails","bank_email","bank_routing_code",$strBankRoutingCode);*/
	$str_to_id = "";
	$qry = "select bank_email from cs_bankdetails where bank_user_id = $iCompanyId";
	if(!($result_set = mysql_query($qry))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($arr_result = mysql_fetch_row($result_set)) {
		$str_to_id = $arr_result[0];
		/*$str_to_id = func_get_value_of_field($cnn_cs,"cs_bankdetails","bank_email","bank_routing_code",$strBankRoutingCode);*/
		if($str_to_id != "") {
			//print($str_to_id."<br>");
			func_send_mail($str_from_id,$str_to_id,$str_mail_subject,$str_mail_body);
		}
	}
	$qry = "select bank_email from cs_company_bankdetails where bank_user_id = $iCompanyId and bank_transaction_type = '$str_trans_type'";
	if(!($result_set = mysql_query($qry))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($arr_result = mysql_fetch_row($result_set)) {
		$str_to_id = $arr_result[0];
		if($str_to_id != "") {
			//print($str_to_id."<br>");
			func_send_mail($str_from_id,$str_to_id,$str_mail_subject,$str_mail_body);
		}
	}

}

function func_gatewaycompany_multiselect($mode,$arr_company_ids,$sessionGatewaylogin) {
	$qry_select_company = "select distinct userid,companyname from cs_companydetails where transaction_type='pmtg' and gateway_id = '$sessionGatewaylogin' order by companyname";
    if(!($show_sql =mysql_query($qry_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(($mode=="view") && (mysql_num_rows($show_sql) >0)){
		if ($arr_company_ids[0] == "A" || $arr_company_ids[0] == "G") {
			print "<option value='G' selected>All Companies</option>";
		} else {
			print "<option value='G'>All Companies</option>";
		}
	}
	while($show_val = mysql_fetch_array($show_sql)) 
	{
		if (in_array($show_val[0],$arr_company_ids)) {
			print"<option value='$show_val[0]' selected>$show_val[1]</option>";	
		} else {
			print"<option value='$show_val[0]'>$show_val[1]</option>";	
		}
	}

}

function func_gateway_company_exists($cnn_cs, $company_ids, $selected_company_id) {
	$exists = false;
	if ($company_ids != "") {
		if ($company_ids == "A") {
			$exists = true;
		} else if ($company_ids != "G") {
			$qry = "select * from cs_companydetails where userId in ($company_ids) and gateway_id = '$selected_company_id'";
			if (!($result_set = mysql_query($qry))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			} 
			if (mysql_num_rows($result_set) > 0) {
				$exists = true;
			}
		}
	}
	return $exists;
}

function func_is_gateway_company($cnn_cs, $company_id) {
	$is_gateway_company = false;
	$qry = "select companyname from cs_companydetails where userId = $company_id and transaction_type = 'pmtg'";
	if (!($result_set = mysql_query($qry))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if (mysql_num_rows($result_set) > 0) {
		$is_gateway_company = true;
	}
	return $is_gateway_company;
}

function func_fill_info_source_combo($cnn_cs, $str_selected_value) {
	?>
	<option value="">Select</option>
	<option value="http://www.about.com" <?= $str_selected_value == "http://www.about.com" ? "selected" : ""?>>About.com</option>
	<option value="http://www.altavista.com" <?= $str_selected_value == "http://www.altavista.com" ? "selected" : ""?>>AltaVista</option>
	<option value="http://www.alltheweb.com" <?= $str_selected_value == "http://www.alltheweb.com" ? "selected" : ""?>>AllTheWeb.com</option>
	<option value="http://www.aolsearch.aol.com" <?= $str_selected_value == "http://aolsearch.aol.com" ? "selected" : ""?>>AOL Search</option>
	<option value="http://www.askjeeves.com" <?= $str_selected_value == "http://www.askjeeves.com" ? "selected" : ""?>>Ask Jeeves</option>
	<option value="http://www.britannica.com" <?= $str_selected_value == "http://www.britannica.com" ? "selected" : ""?>>Britannica.com</option>
	<option value="http://www.excite.com" <?= $str_selected_value == "http://www.excite.com" ? "selected" : ""?>>Excite</option>
	<option value="http://www.google.com" <?= $str_selected_value == "http://www.google.com" ? "selected" : ""?>>Google</option>
	<option value="http://www.hotbot.com" <?= $str_selected_value == "http://www.hotbot.com" ? "selected" : ""?>>HotBot</option>
	<option value="http://www.inktomi.com" <?= $str_selected_value == "http://www.inktomi.com" ? "selected" : ""?>>Inktomi</option>
	<option value="http://www.iwon.com" <?= $str_selected_value == "http://www.iwon.com" ? "selected" : ""?>>iWon</option>
	<option value="http://www.looksmart.com" <?= $str_selected_value == "http://www.looksmart.com" ? "selected" : ""?>>LookSmart</option>
	<option value="http://www.lycos.com" <?= $str_selected_value == "http://www.lycos.com" ? "selected" : ""?>>Lycos</option>
	<option value="http://www.search.msn.com" <?= $str_selected_value == "http://search.msn.com" ? "selected" : ""?>>MSN Search</option>
	<option value="http://www.search.netscape.com" <?= $str_selected_value == "http://search.netscape.com" ? "selected" : ""?>>Netscape Search</option>
	<option value="http://www.overture.com" <?= $str_selected_value == "http://www.overture.com" ? "selected" : ""?>>Overture</option>
	<option value="http://www.searchking.com" <?= $str_selected_value == "http://www.searchking.com" ? "selected" : ""?>>SearchKing</option>
	<option value="http://www.teoma.com" <?= $str_selected_value == "http://www.teoma.com" ? "selected" : ""?>>Teoma</option>
	<option value="http://www.webcrawler.com" <?= $str_selected_value == "http://www.webcrawler.com" ? "selected" : ""?>>WebCrawler</option>
	<option value="http://www.wisenut.com" <?= $str_selected_value == "http://www.wisenut.com" ? "selected" : ""?>>WiseNut</option>
	<option value="http://www.yahoo.com" <?= $str_selected_value == "http://www.yahoo.com" ? "selected" : ""?>>Yahoo</option>
	<option value="rsel" <?= $str_selected_value == "rsel" ? "selected" : ""?>>Reseller</option>
	<option value="other" <?= $str_selected_value == "other" ? "selected" : ""?>>Others</option>
<?
}

function func_get_weekrange_from_date($str_date) {
	$str_year = substr($str_date,0,4);
	$str_month = substr($str_date,5,2);
	$str_day = substr($str_date,8,2);
	$i_day_of_week = date("w",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_month_without_zero = date("n",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_year = date("Y",mktime(0,0,0,$str_month,$str_day,$str_year));
	//$i_month_with_zero = date("m",mktime(0,0,0,$str_month,$str_day,$str_year));
	$i_day = date("j",mktime(0,0,0,$str_month,$str_day,$str_year));

	if ($i_day_of_week == 0) {
		$i_day_of_week = 7;
	}
	$i_day_diff = $i_day_of_week - 1;
	$str_start_of_week = date("m/d/Y",mktime(0,0,0,$str_month,($i_day - $i_day_diff),$i_year));
	$str_end_of_week = date("m/d/Y",mktime(0,0,0,$str_month,($i_day - $i_day_diff + 6),$i_year));

	/*$str_start_of_week = date("m",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week),$i_year))."/".date("d",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week+1),$i_year))."/".date("Y",mktime(0,0,0,$i_month_without_zero,($i_day-$i_day_of_week),$i_year)); 

	$str_end_of_week = date("m",mktime(23,59,59,$i_month_without_zero,($i_day+(6-$i_day_of_week)),$i_year))."/".date("d",mktime(23,59,59,$i_month_without_zero,($i_day+(7-$i_day_of_week)),$i_year))."/".date("Y",mktime(23,59,59,$i_month_without_zero,($i_day+(6-$i_day_of_week)),$i_year)); */
	
	return ($str_start_of_week ." - ". $str_end_of_week);
}

function func_get_month_from_date($str_date) {
	$str_year = substr($str_date,0,4);
	$str_month = substr($str_date,5,2);
	$str_day = substr($str_date,8,2);
	$str_month_of_year = date("F, Y",mktime(0,0,0,$str_month,$str_day,$str_year));
	return ($str_month_of_year);
}

function funcFillComboWithTitle ( $sTitle ) {
	$arrTitles[0] = "Prof";
	$arrTitles[1] = "Dr";
	$arrTitles[2] = "Mr";
	$arrTitles[3] = "Miss";
	$arrTitles[4] = "Mrs";
	$arrTitles[5] = "Others";
	for ( $iLoop = 0;$iLoop<6;$iLoop++ ) {
		if ( $arrTitles[$iLoop] == $sTitle ) {
			echo ("<option value='$arrTitles[$iLoop]' selected>$arrTitles[$iLoop]</option>");
		}
		else {
			echo ("<option value='$arrTitles[$iLoop]'>$arrTitles[$iLoop]</option>");
		}
	}
}

function funcFillDate ( $iDay,$iMonth,$iYear ) {
	echo("<select name='cboDay' style='font-family:verdana;font-size:10px'>");
	for ( $iLoop = 1 ; $iLoop < 32 ; $iLoop++ ) {
		if ( $iDay == $iLoop ) {
			echo("<option value='$iLoop' selected>$iLoop</option>");
		} else {
			echo("<option value='$iLoop'>$iLoop</option>");				
		}
	}
	echo("</select>");
	echo("<select name='cboMonth' style='font-family:verdana;font-size:10px'>");
	for ( $iLoop = 1 ; $iLoop < 13 ; $iLoop++ ) {
		if ( $iMonth == $iLoop ) {
			echo("<option value='$iLoop' selected>$iLoop</option>");
		} else {
			echo("<option value='$iLoop'>$iLoop</option>");				
		}
	}
	echo("</select>");
	echo("<select name='cboYear' style='font-family:verdana;font-size:10px'>");
	for ( $iLoop = 1900 ; $iLoop < date("Y") ; $iLoop++ ) {
		if ( $iYear == $iLoop ) {
			echo("<option value='$iLoop' selected>$iLoop</option>");
		} else {
			echo("<option value='$iLoop'>$iLoop</option>");				
		}
	}
	echo("</select>");
}

function func_store_bad_email($cnn_cs, $user_id, $company_name, $email_id) {
	$str_query = "select * from cs_bad_emails where user_id = $user_id";
    if(!($result_set =mysql_query($str_query)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($result_set) == 0) {
		$str_query = "insert into cs_bad_emails(user_id, company_name, email_id) values($user_id, '$company_name', '$email_id')";
		if(!($insert_qry =mysql_query($str_query,$cnn_cs))) {
			echo mysql_errno().": ".mysql_error()."<BR>";
			echo "Cannot execute query.";
			exit();
		}
	}
}
function func_select_merchant_volume($volume) {
	if($volume=="") print "<option value='' selected>Select</option>"; else print "<option value='' selected>Select</option>"; 
	if($volume=="0-$5,000") print "<option value='0-$5,000' selected>0-$5,000</option>"; else print "<option value='0-$5,000'>0-$5,000</option>"; 
	if($volume=="$5,000-$10,000") print "<option value='$5,000-$10,000' selected>$5,000-$10,000</option>"; else print "<option value='$5,000-$10,000'>$5,000-$10,000</option>";
	if($volume=="$10,000-$25,000") print "<option value='$10,000-$25,000' selected>$10,000-$25,000</option>"; else print "<option value='$10,000-$25,000'>$10,000-$25,000</option>";
	if($volume=="$25,000-$50,000") print "<option value='$25,000-$50,000' selected>$25,000-$50,000</option>"; else print "<option value='$25,000-$50,000'>$25,000-$50,000</option>";
	if($volume=="$50,000-$100,000") print "<option value='$50,000-$100,000' selected>$50,000-$100,000</option>"; else print "<option value='$50,000-$100,000'>$50,000-$100,000</option>";
	if($volume=="$100,000-$250,000") print "<option value='$100,000-$250,000' selected>$100,000-$250,000</option>"; else print "<option value='$100,000-$250,000'>$100,000-$250,000</option>"; 
	if($volume=="$250,000-$500,000") print "<option value='$250,000-$500,000' selected>$250,000-$500,000</option>"; else print "<option value='$250,000-$500,000'>$250,000-$500,000</option>";
	if($volume=="$500,000-1MIL") print "<option value='$500,000-1MIL' selected>$500,000-1MIL</option>"; else print "<option value='$500,000-1MIL'>$500,000-1MIL</option>";
	if($volume=="1Mil-2Mil") print "<option value='1Mil-2Mil' selected>1Mil-2Mil</option>"; else print "<option value='1Mil-2Mil'>1Mil-2Mil</option>"; 
	if($volume=="2Mil-5Mil") print "<option value='2Mil-5Mil' selected>2Mil-5Mil</option>"; else print "<option value='2Mil-5Mil'>2Mil-5Mil</option>";
	if($volume=="5 Mil+") print "<option value='5 Mil+' selected>5 Mil+</option>"; else print "<option value='5 Mil+'>5 Mil+</option>";
}

function func_canceledTransaction_receipt($user_id, $transactionId,$cnn_connection) {
		$qry_select = "SELECT A.name,A.surname,A.email,B.url1,B.companyname,B.transaction_type,A.reference_number,A.cancel_refer_num FROM cs_transactiondetails as A, cs_companydetails  as B WHERE A.userId = B.userId  and A.transactionId =$transactionId";
		$rst_select = mysql_query($qry_select,$cnn_connection) or dieLog("Error: ". mysql_error());
		$str_return_value = "";
		if(mysql_num_rows($rst_select)>0)
		{
			$str_UserName 		= mysql_result($rst_select,0,0);
			$str_UserSurName 	= mysql_result($rst_select,0,1);
			$str_UserEmail 		= mysql_result($rst_select,0,2);
			$str_URL 			= mysql_result($rst_select,0,3);
			$str_CompanyName 	= mysql_result($rst_select,0,4);
			$str_merchant_type 	= mysql_result($rst_select,0,5);
			$str_RefNum 		= mysql_result($rst_select,0,6);
			$str_CancelNum 		= mysql_result($rst_select,0,7);
	
		}
		if ($str_merchant_type != "tele") {
			$str_from = "customerservice@etelegate.com";
			$str_to = $str_UserEmail;
			$str_subject = "Ecommerce Letter for Canceled Transaction.";
			$str_message = "$str_UserName $str_UserSurName,\r\n\r\n
	Your charge to $str_URL  $str_CompanyName has been cancelled. You will NOT be billed again.\r\n\r\n
	Reference Number: $str_RefNum \r\n\r\n
	Cancelation Number: $str_CancelNum \r\n\r\n
	For any additional questions regarding billing, please contact eTeleGate at customerservice@etelegate.com.";
	
			func_send_mail($str_from,$str_to,$str_subject,$str_message);
		}
	}

function func_select_tele_nontele_companytype($companytype) {
	$all = 0;$tele = 0;$nontele = 0;
	$qrt_select_company = "select transaction_type from cs_companydetails";
   	if(!($show_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	while($show_val = mysql_fetch_array($show_sql)) 
	{
		if ($show_val[0] == "tele") {
			$tele++;
		} else {
			$nontele++;
		}
		$all++;
	}
	if($companytype=="A")print "<option value='A'>All Companies ($all)</option>";else print "<option value='A'>All Companies ($all)</option>";
	if($companytype=="T")print "<option value='T' selected>Telemarketing ($tele)</option>";else print "<option value='T'>Telemarketing ($tele)</option>";
	if($companytype=="E")print "<option value='E' selected>Non Telemarketing ($nontele)</option>";else print "<option value='E'>Non Telemarketing ($nontele)</option>";
}

function func_select_companytrans_type_admin($Company_transtype, $tele) {
	if($Company_transtype=='A')print "<option value='A' selected>All Merchants</option>";else print "<option value='A'>All Merchants</option>";
	if ($tele == "T" || $tele == "A") {
		if($Company_transtype=='tele')print "<option value='tele' selected>Telemarketing</option>";else print "<option value='tele'>Telemarketing</option>";
	}
	if ($tele == "E" || $tele == "A") {
		if($Company_transtype=='ecom')print "<option value='ecom' selected>Ecommerce</option>";else print "<option value='ecom'>Ecommerce</option>";
		if($Company_transtype=='trvl')print "<option value='trvl' selected>Travel</option>";else print "<option value='trvl'>Travel</option>";
		if($Company_transtype=='phrm')print "<option value='phrm' selected>Pharmacy</option>";else print "<option value='phrm'>Pharmacy</option>";
		if($Company_transtype=='game')print "<option value='game' selected>Gaming</option>";else print "<option value='game'>Gaming</option>";
		if($Company_transtype=='adlt')print "<option value='adlt' selected>Adult</option>";else print "<option value='adlt'>Adult</option>";
		if($Company_transtype=='pmtg')print "<option value='pmtg' selected>Gateway</option>";else print "<option value='pmtg'>Gateway</option>";
		if($Company_transtype=='crds')print "<option value='crds' selected>Card Swipe</option>";else print "<option value='crds'>Card Swipe</option>";
	}
}

function func_select_bank($bank_id) {
	$qrt_select_company = "select bank_id, bank_name from cs_bank";
   	if(!($show_sql = mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} else {
		print"<option value='A' selected>All</option>";	
		while($show_val = mysql_fetch_array($show_sql)) 
		{
			if ($bank_id == $show_val[0]) {
				print"<option value='$show_val[0]' selected>$show_val[1]</option>";	
			} else {
				print"<option value='$show_val[0]'>$show_val[1]</option>";	
			}
		}
	}
}

function func_transaction_updatenew($transactionId,$cnn_cs) {
	$qry_update_transactions = "ERROR";
	print $qry_update_transactions;
	exit();
	if ( !mysql_query($qry_update_transactions,$cnn_cs)) {
		print("<br>Can not execute query");
		exit();
	}
	$transaction_insertId = mysql_insert_id();
	return $transaction_insertId;
}

?>
