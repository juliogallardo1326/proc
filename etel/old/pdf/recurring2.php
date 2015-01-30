<?php 
include('includes/dbconnection.php');
require_once('includes/function.php');
$cardExpire="";
$address="";
$city="";
$zipcode="";
$surname="";
$processing_currency="";
$phonenumber="";
$cvv="";
$cardTypeBr="";
$cardTypeVolpay ="";
$ipaddress="";
$trans_id="";
$country="";
$state="";
$year="";
$transaction_type="";
$bank_CreditcardId ="";
$bUserPassword="";
$bUserId="";
$CCnumber="";
$firstnum="";
$userid="";
$insertionSuccess ="";
$recur_times="";
$date=date("Y-m-d");
$str_qry ="SELECT  userId,recur_times,recur_mode,recur_day,recur_week,recur_month,recur_start_date 
FROM cs_rebillingdetails WHERE recur_start_date = '$date' and recur_times >= 0 ";
$str_result=mysql_query($str_qry,$cnn_cs);
$num_rows=mysql_num_rows ( $str_result );

if($num_rows>0){
	for($i=0;$i<$num_rows;$i++) {
	
		$str_row=mysql_fetch_array($str_result);
		$recur_times=$str_row[1];
		$recur_times=$str_row[1];
		$recur_mode=$str_row[2];
		$recur_day=$str_row[3];
		$recur_week=$str_row[4];
		$recur_month=$str_row[5];
		$recur_start_date=$str_row[6];
		$date=explode ("-",$recur_start_date);
		$yyyy=$date[0];
		$mm=$date[1];
		$dd=$date[2];
			echo $yyyy.$mm.$dd; 
		echo "   ".$recur_mode;echo " rec week  ".$recur_week;
		exit();
		
		//$recur_times=$recur_times-1;
		$str_qry_update ="UPDATE cs_rebillingdetails SET  recur_times='$recur_times' WHERE recur_start_date = '$date'";
	
		if(!$str_result_update=mysql_query($str_qry_update,$cnn_cs)){echo "error on update"; exit();}
		
		$userid=$str_row[0];
		  if($userid!=""){
  
			$str_qry_select ="SELECT * FROM cs_rebillingdetails WHERE recur_start_date = '$date' AND userId ='$userid' ";
			
			$str_result1=mysql_query($str_qry_select ,$cnn_cs);
			$str_row1=mysql_fetch_array($str_result1);
  
			$transactionId =$str_row1[0];
			$rebill_transactionid =$str_row1[1];
			$date_dd =$str_row1[2];
			$date_mm =$str_row1[3];
			$Invoiceid=$str_row1[4];
			$name =$str_row1[5];
			$surname =$str_row1[6];
			$phonenumber =$str_row1[7];
			$address=$str_row1[8];
			$CCnumber =$str_row1[9];	
			$cvv =$str_row1[10];
			$checkorcard =$str_row1[11];
			$country =$str_row1[12];
			$city=$str_row1[13];
			$state =$str_row1[14];
			$zipcode =$str_row1[15];
			$amount =$str_row1[16];
			$memodet =$str_row1[17];
			$signature =$str_row1[18];
			$bankname =$str_row1[19];
			$bankroutingcode=$str_row1[20];	
			$bankaccountnumber =$str_row1[21];
			$accounttype =$str_row1[22];
			$misc =$str_row1[23];
			$email =$str_row1[24];
			$cancelstatus =$str_row1[25];
			$status=$str_row1[26];
			$userId =$str_row1[27];
			$Checkto=$str_row1[28];
			$cardtype =$str_row1[29];
			$checktype=$str_row1[30];
			$validupto =$str_row1[31];
			$reason =$str_row1[32];
			$other =$str_row1[33];
			$ipaddress =$str_row1[34];
			$voiceAuthorizationno=$str_row1[35];
			$shippingTrackingno =$str_row1[36];
			$socialSecurity =$str_row1[37];
			$driversLicense =$str_row1[38];
			$billingDate =$str_row1[39];
			$transactionDate =$str_row1[40];
			$licensestate   =$str_row1[41];
			$recur_mode=$str_row1[42];
			$recur_day =$str_row1[43];
			$recur_week =$str_row1[44];
			$recur_month =$str_row1[45];
			$recur_start_date =$str_row1[46];
			$recur_charge=$str_row1[47];
			$recur_times=$str_row1[48];
			$service_user_id =$str_row1[49];
  			$cardExpir =explode("/",$validupto);
			$year=$cardExpir[0];
			$cardExpire=$cardExpir[1]."/".substr($year,2,3);
			$str_qry_insert="INSERT INTO cs_transactiondetails (transactionDate,name,surname,phonenumber,address,CCnumber,cvv,checkorcard,country,city,state,zipcode,amount,memodet,signature,bankname,bankroutingcode,bankaccountnumber,accounttype,misc ,email,cancelstatus,status,userId,Checkto,cardtype,checktype,validupto,reason,other,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,service_user_id) VALUES ('$date','$name','$surname','$phonenumber','$address','$CCnumber','$cvv','$checkorcard','$country','$city','$state','$zipcode','$amount','$memodet','$signature','$bankname','$bankroutingcode','$bankaccountnumber','$accounttype','$misc','$email','$cancelstatus','$status','$userId','$Checkto','$cardtype','$checktype','$validupto','$reason','$other','$ipaddress','$voiceAuthorizationno','$shippingTrackingno','$socialSecurity','$driversLicense','$billingDate','$service_user_id')";
			if(!$str_ins_result=mysql_query($str_qry_insert ,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			} else { 
 				$insertionSuccess = "YES";
				$trans_id=mysql_insert_id();
				
			
				//userid in bankdetails
				//check or card in $checkorcard
			
				$str_qry_select ="select bank_Username,bank_Password,bank_Creditcard,bank_check,bank_shopId,processing_currency,transaction_type  from cs_companydetails where userId =$userid";
			
				$str_sel_result=mysql_query($str_qry_select ,$cnn_cs);
				$str_sel_row=mysql_fetch_array($str_sel_result);
			
				$bank_Username=$str_sel_row[0];
				$bank_Password=$str_sel_row[1];
				$bank_Creditcard=$str_sel_row[2];
				$bank_check=$str_sel_row[3];
				$bank_shopId =$str_sel_row[4];
				$processing_currency=$str_sel_row[5];
				$transaction_type=$str_sel_row[6];
			
				$trans_amount = $amount;
				$trans_amount *= 100;
				if($state ==""){ $state="Others";}
				$abbrCountry = func_country_abbreviation($country);
				$abbrState	 = func_state_abbreviation($state);
				$bank_CreditcardId =$bank_Creditcard;
				if($bank_CreditcardId == 6 || $bank_CreditcardId == 7 || $bank_CreditcardId == 8) {
			
					$bUserId = $bank_Username; 
					$bUserPassword =  $bank_Password ;
					if($bUserId=="") { $bUserId = 8; }
					if($bUserPassword ==""){ $bUserPassword = "volpay"; }
					$firstnum=substr($CCnumber,0,0);
					if($firstnum=="5"){ $cardtype="Visa";} else { $cardtype="Master"; }
					$cardTypeBr = $cardtype == "Visa" ? "V" : "M";
					$cardTypeVolpay = $cardtype == "Visa" ? "Visa" : "Master";
?>
					<body>
					<form method="post" action="batchProcess_Volpay.php" target="_self">
					<input type="hidden" name="COMPANY_ID" value="<?=$company_id?>">
					<input type="hidden" name="USER_ID" value="<?=$bUserId?>">
					<input type="hidden" name="USER_PASSWORD" value="<?=$bUserPassword?>">
					<input type="hidden" name="TRAN_NUMBER" value="<?=$trans_id?>">
					<input type="hidden" name="CUSTOMER_LAST_NAME" value="<?=$surname?>">
					<input type="hidden" name="CUSTOMER_FIRST_NAME" value="<?=$name?>">
					<input type="hidden" name="CUSTOMER_EMAIL" value="<?=$email?>">
					<input type="hidden" name="CUSTOMER_ADRESS" value="<?=$address?>">
					<input type="hidden" name="CUSTOMER_CITY" value="<?=$city?>">
					<input type="hidden" name="CUSTOMER_ZIP_CODE" value="<?=$zipcode?>">
					<input type="hidden" name="CUSTOMER_STATE" value="<?=$abbrState?>">
					<input type="hidden" name="CUSTOMER_COUNTRY" value="<?=$abbrCountry?>">
					<input type="hidden" name="CUSTOMER_PHONE" value="<?=$phonenumber?>">
					<input type="hidden" name="CUSTOMER_IP" value="<?=$ipaddress?>">
					<input type="hidden" name="PRODUCT_NAME" value="Service">
					<input type="hidden" name="TRANSAC_AMOUNT" value="<?=$trans_amount?>">
					<input type="hidden" name="CURRENCY_CODE" value="<?=$processing_currency?>">
					<input type="hidden" name="CB_TYPE" value="<?=$cardTypeVolpay?>">
					<input type="hidden" name="CB_NUMBER" value="<?=$CCnumber?>">
					<input type="hidden" name="CB_EXPIRE" value="<?=$cardExpire?>">
					<input type="hidden" name="CB_CVC" value="<?=$cvv?>">
					<input type="hidden" name="TRANS_TYPE" value="<?=$transaction_type?>">
					</form>
					<script>
						document.forms[0].submit();
					</script>
					</body>
<?php
				} elseif($bank_CreditcardId == 3) {
					$shope_id = $bank_shopId;
					
					if($shope_id =="") { $shope_id = "TELEGATE"; }
?>
					<body>
					<form method="post" action="batchProcess_Bardo.php" target="_self">
					<input type="hidden" name="COMPANY_ID" value="<?=$company_id?>">
					<input type="hidden" name="USER_ID" value="<?=$bUserId?>">
					<input type="hidden" name="USER_PASSWORD" value="<?=$bUserPassword?>">
					<input type="hidden" name="TRAN_NUMBER" value="<?=$trans_id?>">
					<input type="hidden" name="CUSTOMER_LAST_NAME" value="<?=$surname?>">
					<input type="hidden" name="CUSTOMER_FIRST_NAME" value="<?=$name?>">
					<input type="hidden" name="CUSTOMER_EMAIL" value="<?=$email?>">
					<input type="hidden" name="CUSTOMER_ADRESS" value="<?=$address?>">
					<input type="hidden" name="CUSTOMER_CITY" value="<?=$city?>">
					<input type="hidden" name="CUSTOMER_ZIP_CODE" value="<?=$zipcode?>">
					<input type="hidden" name="CUSTOMER_STATE" value="<?=$abbrState?>">
					<input type="hidden" name="CUSTOMER_COUNTRY" value="<?=$abbrCountry?>">
					<input type="hidden" name="CUSTOMER_PHONE" value="<?=$phonenumber?>">
					<input type="hidden" name="CUSTOMER_IP" value="<?=$ipaddress?>">
					<input type="hidden" name="PRODUCT_NAME" value="Service">
					<input type="hidden" name="TRANSAC_AMOUNT" value="<?=$trans_amount?>">
					<input type="hidden" name="CURRENCY_CODE" value="<?=$processing_currency?>">
					<input type="hidden" name="CB_TYPE" value="<?=$cardTypeBr?>">
					<input type="hidden" name="CB_NUMBER" value="<?=$CCnumber?>">
					<input type="hidden" name="CB_EXPIRE" value="<?=$cardExpire?>">
					<input type="hidden" name="CB_CVC" value="<?=$cvv?>">
					<input type="hidden" name="TRANS_TYPE" value="<?=$transaction_type?>">
					</form>
					<script>
						document.forms[0].submit();
					</script>
					</body>
<?php
				}//else credid==3
			}
		 }//if userid!=""
	}//loop
}
?>