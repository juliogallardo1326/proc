<?php 
include('includes/dbconnection.php');
require_once('includes/function.php');
include('includes/function2.php');
$proceed=0;
$trans_id="";
$nextdate="";
$time_today="";
$diff=4;
$time_rec="";
$num="";
$date="";
$yyyy="";
$mm="";
$dd="";
$userId="";
$curr_week_day="";
$recur_next_date="";
$recur_date="";
$cardExpire="";
	$cardExpir="";
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


$str_qry ="SELECT  userId,recur_times,recur_mode,recur_day,recur_week,recur_month,recur_start_date ,transactionId ,recur_date 
FROM cs_rebillingdetails WHERE (recur_start_date = '$date' OR recur_date = '$date') and recur_times >=0 ";

$str_result=mysql_query($str_qry,$cnn_cs);
$num_rows=mysql_num_rows ( $str_result );

if($num_rows>0){

	for($i=0;$i<$num_rows;$i++) {
	
		$str_row=mysql_fetch_array($str_result);
		$userId=$str_row[0];
		
		$recur_times=$str_row[1];
		$recur_mode=$str_row[2];
		$recur_day=$str_row[3];
		$recur_week=$str_row[4];
		$recur_month=$str_row[5];
		$recur_start_date=$str_row[6];
		$transactionId  =$str_row[7];
		$recur_date=$str_row[8];
		$date=explode ("-",$recur_start_date);
		$yyyy=$date[0];
		$mm=$date[1];
		$dd=$date[2];
		$d=date('Y-m-d');
		$t1=mktime(0,0,(date('Y-m-d')));
		$t2=mktime(0,0,$recur_start_date);
		echo "time diff".($t1-$t2);
		
		if(($d==$recur_date)){$diff=0;}
		echo "rec date ".$recur_date;
		
		if  ($recur_mode=="D"){
				$time=mktime(0,0,(date('Y-m-d')));
				$time=$time+($recur_day *86400);
				$day=date('Y-m-d',$time);
				$recur_next_date=$day;
		$proceed=1;
		if ($recur_times==1)
				 {$recur_times=-1; }
				 if($recur_times!=0){$recur_times=-1;} 
				
					
				}//if D
		
		
	if ($recur_mode=="Y"){
		
	
		if( ($diff==0)){echo "inif";
				if ($recur_times==1)
				 {
				 $recur_times=-1;
				 }
				 if($recur_times!=0)
				 { $recur_times=$recur_times-1;}
				  
				$time=mktime(0,0,(date('Y-m-d')));
				$time=$time+(365*86400);
				$day=date('Y-m-d',$time);
				$recur_next_date=$day;
				
	
				} //if==0
				else { 
				echo "else"; 
				if($mm!=2)
				{$recur_next_date=($yyyy+1)."-".$recur_month."-".$recur_day;}
				else{}
				if($recur_day>28){
				if ($mm==2)
				{$recur_next_date=($yyyy+1)."-".$recur_month."-"."28";$nextdate="28"; }
				}
				
				 }
				}//mod=y
		
		
		if ($recur_mode=="M"){
		
		$curr_date=date ('j');
		$diff_date= $curr_date-$recur_day;
		if( $diff_date<0){
		echo "in 1 ";
		$num=date('t');
		$nextdate=$dd+(-1)*$diff_date;
		if($nextdate>$num ){$nextdate=$num;}
		$recur_next_date=$yyyy."-".($mm)."-".$nextdate;
	
		//comming..
		}
				if ($diff==0||($diff_date==0)){
				echo "dif".$diff."<br>";
				if ($recur_times==1)
				{ $recur_times=-1; } 
				
				if($recur_times!=0)
				 { $recur_times=$recur_times-1;}
				 $time=mktime(0,0,(date('Y-m-d')));
				$time=$time+(28*86400);
				$day=date('Y-m-d',$time);
				$recur_next_date=$day;
				$len= strlen($recur_next_date);
				 $nextdate=substr($day,$len-2,$len-1);
				 echo "in zero ". $recur_next_date;
				
				
					}//correctdate diff==0
			 
		//upadte database
		if(($mm!=1)&& ($diff==0)&&($diff_date==0)){
		echo "in 2 ";
				$time=mktime(0,0,(date('Y-m-d')));
				$time=$time+(30*86400);
				$day=date('Y-m-d',$time);
				echo "<br>"."entered";
				$recur_next_date=$day;
				}
				else if($mm==1&&($diff_date==0)){
				echo "in 3 ";
				$time=mktime(0,0,(date('Y-m-d')));
				$time=$time+(28*86400);
				$day=date('Y-m-d',$time);
				$recur_next_date=$day;
				}
							
				elseif($diff_date>0){
				echo "in 4";
					if($mm!=1){
				$recur_next_date=$yyyy."-".($mm+1)."-".$recur_day;
				
			
					}
					//if($dd>=30)
					else if(($mm==1)&&($dd>28))
					{echo "in 5 ";
					$recur_next_date=$yyyy."-".($mm+1)."-"."28"; 
					}
				
					}//else dif!=0
		
		}///if =m

	/////////	exit();
		
		if ($recur_mode=="W")
								{
								$time_today=mktime(0,0,(date('Y-m-d')));
								$time_rec=mktime (0,0,$recur_date);
								$curr_week_day=date ('w');
								
								$diff_week=($curr_week_day+1)-$recur_week;
								
								if(($diff!=0)){echo "in  !eql ";}
								
								  if($recur_week==($curr_week_day+1)) 
								  {$proceed=1;
								  if ($recur_times==1)
								  {$recur_times=-1;  }
								   if($recur_times!=0)
								{  $recur_times=$recur_times-1;}
								  
								 	// update database
									$time=mktime(0,0,(date('Y-m-d')));
									$time=$time+((7)*86400);
									$day=date('Y-m-d',$time);
									$recur_next_date= $day;
									
								
									//echo;
									echo "   date correct"; 
								  }//curweektrue
								   else if($diff_week!=0){
								   
									if($diff_week>0){
									$time=mktime(0,0,(date('Y-m-d')));
									$time=$time+((7-$diff_week)*86400);
									$day=date('Y-m-d',$time);
									$recur_next_date= $day;
									
				
					
									//echo ;
									}//>0
									else if($diff_week<0){
									$time=mktime(0,0,(date('Y-m-d')));
									$time=$time+(-1*($diff_week)*86400);
									$day=date('Y-m-d',$time);
									$recur_next_date= $day;
									
	
											
											}//<0
														}
														
	
													
								  }//if w
		
		
		echo "<br>".$recur_next_date."<br>"; 
		if($nextdate!=""){echo $nextdate;}
		if($nextdate==""){
	$str_qry_update ="UPDATE cs_rebillingdetails SET  recur_times='$recur_times',recur_date='$recur_next_date' WHERE    transactionId    = '$transactionId'";
	if(!$str_result_update=mysql_query($str_qry_update,$cnn_cs)){echo "error on update"; exit();}
		} else  {
	$str_qry_update ="UPDATE cs_rebillingdetails SET  recur_times='$recur_times',recur_date='$recur_next_date',recur_day='$nextdate' WHERE    transactionId    = '$transactionId'";
	if(!$str_result_update=mysql_query($str_qry_update,$cnn_cs)){echo "error on update"; exit();}
		}
								  
		
		$userid=$str_row[0];
		$date=date("Y-m-d");
		//if ($recur_date==$date){$go=1;}
		  if(($userId!="")&&($proceed==1)){
  echo $userId;
		$str_qry_select ="SELECT * FROM cs_rebillingdetails WHERE (recur_date='$date' OR recur_start_date='$date') AND userId ='$userId'";
			print $str_qry_select;			
			$str_row1=mysql_query($str_qry_select ,$cnn_cs);
			if (!$str_row1){echo "cannot exec ";}
				$transactionId=$str_row1[0];;
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
			echo "cc  ". $CCnumber;
  			$cardExpir =explode("/",$validupto);
			$year=$cardExpir[0];
			$cardExpire=$cardExpir[1]."/".substr($year,2,3);
			$dat=date("Y-m-d H:i:s");
			
			$str_qry_insert=
			 "INSERT INTO cs_transactiondetails (transactionDate,name,surname,phonenumber,address,CCnumber,cvv,checkorcard,country,city,state,zipcode,amount,memodet,signature,bankname,bankroutingcode,bankaccountnumber,accounttype,misc ,email,cancelstatus,status,userId,Checkto,cardtype,checktype,validupto,reason,other,ipaddress,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,service_user_id) VALUES ('$dat','$name','$surname','$phonenumber','$address','$CCnumber','$cvv','$checkorcard','$country','$city','$state','$zipcode','$amount','$memodet','$signature','$bankname','$bankroutingcode','$bankaccountnumber','$accounttype','$misc','$email','$cancelstatus','$status','$userId','$Checkto','$cardtype','$checktype','$validupto','$reason','$other','$ipaddress','$voiceAuthorizationno','$shippingTrackingno','$socialSecurity','$driversLicense','$billingDate','$service_user_id')";
			$trans_id=mysql_insert_id();
			if(!$str_ins_result=mysql_query($str_qry_insert ,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} else { 
 				$insertionSuccess = "YES";
				$trans_id=mysql_insert_id();
				///*
				
				//*/
				
				
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
				if($processing_currency==""){$processing_currency="USD";}
				$transaction_type=$str_sel_row[6];
			
				$trans_amount = $amount;
				//refnum
			$ref_no=func_Trans_Ref_No($trans_id);
			$str_qry_update_ref="UPDATE  cs_transactiondetails set  reference_number='$ref_no', currencytype ='$processing_currency' WHERE transactionId ='$trans_id'";
			if(!$str_result_update_ref=mysql_query($str_qry_update_ref,$cnn_cs)){echo "error on update"; exit();}
			exit();	
				
				
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