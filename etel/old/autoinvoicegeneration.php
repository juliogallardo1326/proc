<?php
 include('includes/dbconnection.php');
 require_once('includes/function.php');
 include('includes/function1.php');
 include('includes/function2.php'); 
 $trans_amount=0;											
 $qry_bankdetails="select * from cs_bank";
if(! $rst_bank=mysql_query($qry_bankdetails,$cnn_cs))
 {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
else
{
	$itotalbank=mysql_num_rows($rst_bank);
	for($iloop=1;$iloop<=$itotalbank;$iloop++)
	{
		$rst_bankdetails=mysql_fetch_array($rst_bank);
		 $ibankid=$rst_bankdetails[0];
		$paybackday=$rst_bankdetails[4];
		$ipayday=$rst_bankdetails[7];
		$ipayweekfrom=$rst_bankdetails[5];
		$ipayweekto=$rst_bankdetails[6];
		if($paybackday >0 && $ipayday>0 && $ipayweekfrom>0 && $ipayweekto>0)
		{
		$time=mktime(0,0,(date('Y-m-d')));
		$time=$time-($paybackday*86400);
		$ibeforeday=date('Y-m-d',$time);
		$iweek=date('w',$time);
		$iweek+=1;
		
		//echo $ipayday."<br>".$iweek."<br>";
			if($ipayday==$iweek)
				{
					
					$ifrom=$ipayweekfrom;
					$ito=$ipayweekto;
 					$ipayweekfrom=func_dayofweek($ipayweekfrom);
 					$ipayweekto=func_dayofweek($ipayweekto);
 					//$ipayday."<br>";
					//echo $ibankid;
					if($ifrom==$ipayday)
						{
							$str_startdate=$time;
							$str_startdate= date('Y-m-d',$str_startdate);
						}
					else{
							 $str_startdate =strtotime ("last $ipayweekfrom",$time);
							 $str_startdate= date('Y-m-d',$str_startdate);
						}
					if($ito==$ipayday)
						{
							$str_afterdate=$time;
							$str_afterdate= date('Y-m-d',$str_afterdate);
						}
					else{
							$str_afterdate =strtotime ("this $ipayweekto",$time);
						$str_afterdate= date('Y-m-d',$str_afterdate)."<br>";
						}
						
						 $qry_getuserid="select Distinct(userId)from cs_transactiondetails where transactionDate >= '$str_startdate' and transactionDate <= '$str_afterdate' and bank_id= $ibankid Order by cs_transactiondetails.userId";
							if(!($show_sql = mysql_query($qry_getuserid,$cnn_cs))){
								print(mysql_errno().": ".mysql_error()."<BR>");
								print("Cannot execute query yyy");
								exit();
							}
							else{
									$inum=mysql_num_rows($show_sql);
									for ($iloop1=1;$iloop1<=$inum;$iloop1++){
										$rst_userid= mysql_fetch_array($show_sql);
										//echo "<br>".$rst_userid[0]."<br>";
										 $iuserid=$rst_userid[0];									
										$qry_transdetails="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id,admin_approval_for_cancellation,currencytype FROM cs_transactiondetails, cs_companydetails WHERE ";
										$qry_transdetails.=" cs_transactiondetails.userid =$iuserid and cs_companydetails.userid=$iuserid and transactionDate >= '$str_startdate' and transactionDate <= '$str_afterdate'  and bank_id= $ibankid and cancelstatus='N' and checkorcard='H'  Order by cs_transactiondetails.userid ";
									//echo $qry_transdetails;
										if(!($show_transdetails = mysql_query($qry_transdetails,$cnn_cs))){
											print(mysql_errno().": ".mysql_error()."<BR>");
											print("Cannot execute query lll ");
											exit();
										} 
										else{
												$wirefee=0;
												$i_discount_rate=0;	
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
												$creditcardAmt=0;
												$creditcardPer=0;																							
												$creditcanceled=0;
												$creditcanceledAmt=0;
												$creditcanceledPer=0;												
												$crddeducted_amt = 0;
												$chqcredit_count = 0;
												$crdcredit_count = 0;
												$cardPer = 0;
												$trans_voicefee = 0;
												$chqcharge_back_amount =0;
												$chqcharge_back_count = 0;
												$crdcharge_back_amount =0;
												$crdcharge_back_count = 0;
												$str_companyname = "";
												$i_charge_back = "";
												$cancel_reason ="";
												$i_credit = "";
												$i_chqdiscount_rate = "";
												$i_crddiscount_rate = "";
												$i_transaction_fee = "";
												$i_reserve = "";
												$chqvoice_authcount =0;
												$crdvoice_authcount =0;
												$i_chqvoiceauth_amt = 0;
												$i_crdvoiceauth_amt = 0;
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
												
												$creditpassedPer=0;
												$creditnonpassPer=0;
												$passed=0;
												$passedAmt=0;
												$nonpassed=0;
												$nonpassedAmt=0;
												$voice_count_uploads=0;
												$i_net_Per="";
												$crdchargebackamount=0;
												$chqchargebackamount=0;
												$i_chqdiscount_amt=0;
												$i_crddiscount_amt=0;
												$i_chqreserve_amt=0;
												$i_crdreserve_amt=0;
												$chqcredit_amount=0;
												$crdcredit_amount=0;
												$i_chqtransaction_amt=0;
												$i_crdtransaction_amt=0;
												$i1=0;
												$i2=0;
												
												$creditpending=$audcreditpending=$cadcreditpending=$eurcreditpending=$gbpcreditpending=$usdcreditpending=0;
												$creditpendingAmt=$audcreditpendingAmt=$cadcreditpendingAmt=$eurcreditpendingAmt=$gbpcreditpendingAmt=$usdcreditpendingAmt=0;												
												$creditapproved=$audcreditapproved=$cadcreditapproved=$eurcreditapproved=$gbpcreditapproved=$usdcreditapproved=0;
												$creditapprovedAmt=$audcreditapprovedAmt=$cadcreditapprovedAmt=$eurcreditapprovedAmt=$gbpcreditapprovedAmt=$usdcreditapprovedAmt=0;												
												$creditdeclined=$audcreditdeclined=$cadcreditdeclined=$eurcreditdeclined=$gbpcreditdeclined=$usdcreditdeclined=0;
												$creditdeclinedAmt=$audcreditdeclinedAmt=$cadcreditdeclinedAmt=$eurcreditdeclinedAmt=$gbpcreditdeclinedAmt=$usdcreditdeclinedAmt=0;												
												$creditcard=$audcreditcard=$cadcreditcard=$eurcreditcard=$gbpcreditcard=$usdcreditcard=0;																							
												$creditcardAmt=$audcreditcardAmt=$cadcreditcardAmt=$eurcreditcardAmt=$gbpcreditcardAmt=$usdcreditcardAmt=0;																																			
												$creditcanceled=$audcreditcanceled=$cadcreditcanceled=$eurcreditcanceled=$gbpcreditcanceled=$usdcreditcanceled=0;
												$creditcanceledAmt=$audcreditcanceledAmt=$cadcreditcanceledAmt=$eurcreditcanceledAmt=$gbpcreditcanceledAmt=$usdcreditcanceledAmt=0;
												$crdcredit_count =$audcrdcredit_count=$cadcrdcredit_count=$eurcrdcredit_count=$gbpcrdcredit_count=$usdcrdcredit_count=0;												
												$crdcharge_back_amount =$audcrdcharge_back_amount=$cadcrdcharge_back_amount=$eurcrdcharge_back_amount=$gbpcrdcharge_back_amount=$usdcrdcharge_back_amount=0;
												$crdcharge_back_count = $audcrdcharge_back_count=$cadcrdcharge_back_count=$eurcrdcharge_back_count=$gbpcrdcharge_back_count=$usdcrdcharge_back_count=0;
												$creditnonpass =$audcreditnonpass=$cadcreditnonpass=$eurcreditnonpass=$gbpcreditnonpass=$usdcreditnonpass=0;
												$creditnonpassAmt = $audcreditnonpassAmt=$cadcreditnonpassAmt=$eurcreditnonpassAmt=$gbpcreditnonpassAmt=$usdcreditnonpassAmt=0;
												$creditpass =$audcreditpass=$cadcreditpass=$eurcreditpass=$gbpcreditpass=$usdcreditpass=0;
												$creditpassAmt = $audcreditpassAmt=$cadcreditpassAmt=$eurcreditpassAmt=$gbpcreditpassAmt=$usdcreditpassAmt=0;
												$crdchargebackamount=$audcrdchargebackamount=$cadcrdchargebackamount=$eurcrdchargebackamount=$gbpchargebackamount=$usdcrdchargebackamount=0;
												$i_crddiscount_amt=$audi_crddiscount_amt=$cadi_crddiscount_amt=$euri_crddiscount_amt=$gbpi_crddiscount_amt=$usdi_crddiscount_amt=0;
												$i_crdreserve_amt=$audi_crdreserve_amt=$cadi_crdreserve_amt=$euri_crdreserve_amt=$gbpi_crdreserve_amt=$usdi_crdreserve_amt=0;
												$crdcredit_amount=$audcrdcredit_amount=$cadcrdcredit_amount=$eurcrdcredit_amount=$gbpcrdcredit_amount=$usdcrdcredit_amount=0;
												$i_crdtransaction_amt=$audi_crdtransaction_amt=$cadi_crdtransaction_amt=$euri_crdtransaction_amt=$gbpi_crdtransaction_amt=$usdi_crdtransaction_amt=0;							
												$rej_crdtransaction_amt = $audrej_crdtransaction_amt=$cadrej_crdtransaction_amt=$eurrej_crdtransaction_amt=$gbprej_crdtransaction_amt=$usdrej_crdtransaction_amt=0;
												$rej_creditcanceled = $audrej_creditcanceled=$cadrej_creditcanceled=$eurrej_creditcanceled=$gbprej_creditcanceled=$usdrej_creditcanceled=0;
												$rej_creditcanceledAmt = $audrej_creditcanceledAmt=$cadrej_creditcanceledAmt=$eurrej_creditcanceledAmt=$gbprej_creditcanceledAmt=$usdrej_creditcanceledAmt=0;												
												$rej_crdcharge_back_count =$audrej_crdcharge_back_count=$cadrej_crdcharge_back_count=$eurrej_crdcharge_back_count=$gbprej_crdcharge_back_count=$usdrej_crdcharge_back_count=0;
												$rej_crdchargebackamount= $audrej_crdchargebackamount=$cadrej_crdchargebackamount=$eurrej_crdchargebackamount=$gbprej_crdchargebackamount=$usdrej_crdchargebackamount=0;												
												$rej_crdcredit_count = $audrej_crdcredit_count=$cadrej_crdcredit_count=$eurrej_crdcredit_count=$gbprej_crdcredit_count=$usdrej_crdcredit_count=0;
												$rej_crdcredit_amount =$audrej_crdcredit_amount=$cadrej_crdcredit_amount=$eurrej_crdcredit_amount=$gbprej_crdcredit_amount=$usdrej_crdcredit_amount=0;
										
												
												$inum1=mysql_num_rows($show_transdetails);
												for ($iloop2=1;$iloop2<=$inum1;$iloop2++){
													$rst_transdetails= mysql_fetch_array($show_transdetails);												
													$trans_type = $rst_transdetails[0];
													 $trans_status = $rst_transdetails[1];
													 $trans_cancelstatus =$rst_transdetails[2];
													 $trans_companyName = $rst_transdetails[3];
													 $i_charge_back = $rst_transdetails[4];
													 $i_credit = $rst_transdetails[5];
													 $i_discount_rate = $rst_transdetails[6];
													 $i_transaction_fee = $rst_transdetails[7];
													 $i_reserve =$rst_transdetails[8];
													 $trans_accounttype = $rst_transdetails[9];
													 $trans_cardtype = $rst_transdetails[10];
													 $cancel_reason =$rst_transdetails[11];
													 $trans_passstatus = $rst_transdetails[12];
													 $trans_amount =$rst_transdetails[13];
													 $trans_voicefee =$rst_transdetails[14];
													 $igatewayid=$rst_transdetails[15];
													 $iresellerid=$rst_transdetails[16];
													 $adminapprovalforcancel=$rst_transdetails[17];
													  $str_processingcurency=$rst_transdetails[18];				  
													  if($str_processingcurency==""){
														$str_processingcurency=func_get_cardcurrency($trans_cardtype,$iuserid,$cnn_cs);
														}//splitting the transaction based on currencytype
														 if  ($str_processingcurency=='AUD') {								 
																	if($trans_cancelstatus =="N") {
																		$audcreditcard = $audcreditcard + 1;
																		$audcreditcardAmt = $audcreditcardAmt + $trans_amount;
																		$audi_crdtransaction_amt = ($i_transaction_fee +$audi_crdtransaction_amt);
																		if($trans_passstatus !="PE") {
																			if($trans_passstatus == "NP" && $trans_status =="P") {
																				$audcreditnonpass = $audcreditnonpass + 1;
																				$audcreditnonpassAmt = $audcreditnonpassAmt + $trans_amount;
																			} elseif($trans_passstatus == "PA" && $trans_status =="P") {
																				$audcreditpass = $audcreditpass + 1;
																				$audcreditpassAmt = $audcreditpassAmt + $trans_amount;
																			}
																			if($trans_status =="A")	{
																				$audcreditapproved = $audcreditapproved + 1;
																				$audcreditapprovedAmt = $audcreditapprovedAmt + $trans_amount;
																				$audi_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$audi_crddiscount_amt;
																				$audi_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$audi_crdreserve_amt ;																		}
																			if($trans_status =="D")	{
																				$audcreditdeclined = $audcreditdeclined + 1;
																				$audcreditdeclinedAmt = $audcreditdeclinedAmt + $trans_amount;
																			}																			
																		} else {
																			$audcreditpending = $audcreditpending + 1;
																			$audcreditpendingAmt = $audcreditpendingAmt + $trans_amount;
																		}
																	}	}
																	//based on CAD
																	 if  ($str_processingcurency=='CAD') {								 
																	if($trans_cancelstatus =="N") {
																		$cadcreditcard = $cadcreditcard + 1;
																		$cadcreditcardAmt = $cadcreditcardAmt + $trans_amount;
																		$cadi_crdtransaction_amt = ($i_transaction_fee +$cadi_crdtransaction_amt);
																		if($trans_passstatus !="PE") {
																			if($trans_passstatus == "NP" && $trans_status =="P") {
																				$cadcreditnonpass = $cadcreditnonpass + 1;
																				$cadcreditnonpassAmt = $cadcreditnonpassAmt + $trans_amount;
																			} elseif($trans_passstatus == "PA" && $trans_status =="P") {
																				$cadcreditpass = $cadcreditpass + 1;
																				$cadcreditpassAmt = $cadcreditpassAmt + $trans_amount;
																			}
																			if($trans_status =="A")	{
																				$cadcreditapproved = $cadcreditapproved + 1;
																				$cadcreditapprovedAmt = $cadcreditapprovedAmt + $trans_amount;
																				$cadi_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$cadi_crddiscount_amt;
																				$cadi_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$cadi_crdreserve_amt ;																		}
																			if($trans_status =="D")	{
																				$cadcreditdeclined = $cadcreditdeclined + 1;
																				$cadcreditdeclinedAmt = $cadcreditdeclinedAmt + $trans_amount;
																			}
																			
																		} else {
																			$cadcreditpending = $cadcreditpending + 1;
																			$cadcreditpendingAmt = $cadcreditpendingAmt + $trans_amount;
																		}
																	}	}
																	//based on EUR
																	 if  ($str_processingcurency=='EUR'||$str_processingcurency=='EURO') {								 
																	if($trans_cancelstatus =="N") {
																		$eurcreditcard = $eurcreditcard + 1;
																		$eurcreditcardAmt = $eurcreditcardAmt + $trans_amount;
																		$euri_crdtransaction_amt = ($i_transaction_fee +$euri_crdtransaction_amt);
																		if($trans_passstatus !="PE") {
																			if($trans_passstatus == "NP" && $trans_status =="P") {
																				$eurcreditnonpass = $eurcreditnonpass + 1;
																				$eurcreditnonpassAmt = $eurcreditnonpassAmt + $trans_amount;
																			} elseif($trans_passstatus == "PA" && $trans_status =="P") {
																				$eurcreditpass = $eurcreditpass + 1;
																				$eurcreditpassAmt = $eurcreditpassAmt + $trans_amount;
																			}
																			if($trans_status =="A")	{
																				$eurcreditapproved = $eurcreditapproved + 1;
																				$eurcreditapprovedAmt = $eurcreditapprovedAmt + $trans_amount;
																				$euri_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$euri_crddiscount_amt;
																				$euri_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$euri_crdreserve_amt ;																		}
																			if($trans_status =="D")	{
																				$eurcreditdeclined = $eurcreditdeclined + 1;
																				$eurcreditdeclinedAmt = $eurcreditdeclinedAmt + $trans_amount;
																			}
																			
																		} else {
																			$eurcreditpending = $eurcreditpending + 1;
																			$eurcreditpendingAmt = $eurcreditpendingAmt + $trans_amount;
																		}
																	}	}
																	//based on GBP
																	 if  ($str_processingcurency=='GBP') {								 
																	if($trans_cancelstatus =="N") {
																		$gbpcreditcard = $gbpcreditcard + 1;
																		$gbpcreditcardAmt = $gbpcreditcardAmt + $trans_amount;
																		$gbpi_crdtransaction_amt = ($i_transaction_fee +$gbpi_crdtransaction_amt);
																		if($trans_passstatus !="PE") {
																			if($trans_passstatus == "NP" && $trans_status =="P") {
																				$gbpcreditnonpass = $gbpcreditnonpass + 1;
																				$gbpcreditnonpassAmt = $gbpcreditnonpassAmt + $trans_amount;
																			} elseif($trans_passstatus == "PA" && $trans_status =="P") {
																				$gbpcreditpass = $gbpcreditpass + 1;
																				$gbpcreditpassAmt = $gbpcreditpassAmt + $trans_amount;
																			}
																			if($trans_status =="A")	{
																				$gbpcreditapproved = $gbpcreditapproved + 1;
																				$gbpcreditapprovedAmt = $gbpcreditapprovedAmt + $trans_amount;
																				$gbpi_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$gbpi_crddiscount_amt;
																				$gbpi_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$gbpi_crdreserve_amt ;																		}
																			if($trans_status =="D")	{
																				$gbpcreditdeclined = $gbpcreditdeclined + 1;
																				$gbpcreditdeclinedAmt = $gbpcreditdeclinedAmt + $trans_amount;
																			}
																			
																		} else {
																			$gbpcreditpending = $gbpcreditpending + 1;
																			$gbpcreditpendingAmt = $gbpcreditpendingAmt + $trans_amount;
																		}
																	}	}
																	//based on USD
																	 if  ($str_processingcurency=='USD') {								 
																	if($trans_cancelstatus =="N") {
																		$usdcreditcard = $usdcreditcard + 1;
																		$usdcreditcardAmt = $usdcreditcardAmt + $trans_amount;
																		$usdi_crdtransaction_amt = ($i_transaction_fee +$usdi_crdtransaction_amt);
																		if($trans_passstatus !="PE") {
																			if($trans_passstatus == "NP" && $trans_status =="P") {
																				$usdcreditnonpass = $usdcreditnonpass + 1;
																				$usdcreditnonpassAmt = $usdcreditnonpassAmt + $trans_amount;
																			} elseif($trans_passstatus == "PA" && $trans_status =="P") {
																				$usdcreditpass = $usdcreditpass + 1;
																				$usdcreditpassAmt = $usdcreditpassAmt + $trans_amount;
																			}
																			if($trans_status =="A")	{
																				$usdcreditapproved = $usdcreditapproved + 1;
																				$usdcreditapprovedAmt = $usdcreditapprovedAmt + $trans_amount;
																				$usdi_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$usdi_crddiscount_amt;
																				$usdi_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$usdi_crdreserve_amt ;																		}
																			if($trans_status =="D")	{
																				$usdcreditdeclined = $usdcreditdeclined + 1;
																				$usdcreditdeclinedAmt = $usdcreditdeclinedAmt + $trans_amount;
																			}
																			
																		} else {
																			$usdcreditpending = $usdcreditpending + 1;
																			$usdcreditpendingAmt = $usdcreditpendingAmt + $trans_amount;
																		}
																	}	}
														}//end of for loop
														$approvedstatusdate=func_get_current_date_time();
														$qry_bankexit="select * from cs_invoicedetails where bank_id='$ibankid' and userId=$iuserid";
														if(!$rst_bankexist=mysql_query($qry_bankexit,$cnn_cs))
														{print(mysql_errno().": ".mysql_error()."<BR>");
															
															exit();
															print("cannot execute select query nnnn");
														}
														else{
																$num_records=mysql_num_rows($rst_bankexist);
																if($num_records >0)
																{
																$datearray=explode("-",$approvedstatusdate);
																	$year=$datearray[0]."<br>";
																	$month=$datearray[1]."<br>";
																	$day=$datearray[2]."<br>";
																	$time=mktime(0,0,0,$month,$day,$year);
																	$cancelenddate =strtotime ("-1 day",$time);
																	$cancelenddate= date('Y-m-d',$cancelenddate);																	
																	 $cancelstartdate= strtotime ("-1 week",$time);
																	$cancelstartdate= date('Y-m-d',$cancelstartdate);	
																}
																else
																{
																	$datearray=explode("-",$approvedstatusdate);
																	$year=$datearray[0]."<br>";
																	$month=$datearray[1]."<br>";
																	$day=$datearray[2]."<br>";
																	$time=mktime(0,0,0,$month,$day,$year);
																	$cancelenddate =strtotime ("-1 day",$time);
																	 $cancelenddate= date('Y-m-d',$cancelenddate);																	
																	  $cancelstartdate=$str_startdate; 	
																}
																$cancelstartdate.=" 00:00:00";
																$cancelenddate.=" 23:59:59";
																$qry_cancels="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id,admin_approval_for_cancellation,currencytype,cancel_refer_num FROM cs_transactiondetails, cs_companydetails WHERE ";
																 $qry_cancels.=" cs_transactiondetails.userid =$iuserid and cs_companydetails.userid=$iuserid and cancellationDate >= '$cancelstartdate' and cancellationDate <= '$cancelenddate'  and bank_id= $ibankid and cancelstatus='Y' and admin_approval_for_cancellation !='R' and checkorcard='H' Order by cs_transactiondetails.userid ";
														 		if(!$rst_cancels=mysql_query($qry_cancels,$cnn_cs))
																{
																	print(mysql_errno().": ".mysql_error()."<BR>");
																	print("cannot execute select query mmm");
																	exit();															
																}//cancellation charge
																else{
																		 $num_cancels=mysql_num_rows($rst_cancels);
																		for($i_cancel=0;$i_cancel<$num_cancels;$i_cancel++)
																		{
																			$rst_canceldetails= mysql_fetch_array($rst_cancels);												
																			$trans_type = $rst_canceldetails[0];
																			 $trans_status = $rst_canceldetails[1];
																			 $trans_cancelstatus =$rst_canceldetails[2];
																			 $trans_companyName = $rst_canceldetails[3];
																			 $i_charge_back = $rst_canceldetails[4];
																			 $i_credit = $rst_canceldetails[5];
																			 $i_discount_rate = $rst_canceldetails[6];
																			 $i_transaction_fee = $rst_canceldetails[7];
																			 $i_reserve =$rst_canceldetails[8];
																			 $trans_accounttype = $rst_canceldetails[9];
																			 $trans_cardtype = $rst_canceldetails[10];
																			 $cancel_reason =$rst_canceldetails[11];
																			 $trans_passstatus = $rst_canceldetails[12];
																			 $trans_amount =$rst_canceldetails[13];
																			 $trans_voicefee =$rst_canceldetails[14];
																			 $igatewayid=$rst_canceldetails[15];
																			 $iresellerid=$rst_canceldetails[16];
																			 $adminapprovalforcancel=$rst_canceldetails[17];
																			  $str_processingcurency=$rst_canceldetails[18];
																			  $cancel_ref_num=$rst_canceldetails[19];
																			  if($cancel_ref_num !="" && $cancel_ref_num !=0){
																			  
																			 $qry_select="select r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve from cs_transactiondetails where reference_number ='$cancel_ref_num '";
																			  if(!$rst_rates = mysql_query($qry_select,$cnn_cs)){
																			  	print("cannot execute query qqq ");
																				print(mysql_errno().": ".mysql_error()."<BR>");
																			  }else{
																			  		$rst_charges=mysql_fetch_array($rst_rates);
																			  		$i_charge_back = $rst_charges[0];
																			 		$i_credit = $rst_charges[1];
																			 		$i_discount_rate = $rst_charges[2];
																					$i_transaction_fee = $rst_charges[2];
																			 		$i_reserve =$rst_charges[4];
																			  }
																			  }
																			 				  
																			  if($str_processingcurency==""){
																			  //echo $trans_cardtype;
																			  // echo $iuserid;
																			    
																				 $str_processingcurency=func_get_cardcurrency($trans_cardtype,$iuserid,$cnn_cs);
																				}//echo $str_processingcurency;
																				//splitting based on currency type AUD
																				if($str_processingcurency=='AUD')
																				{
																					echo $audcreditcardAmt = $audcreditcardAmt + $trans_amount;
																					$audcreditcard = $audcreditcard + 1;
																					$audi_crdtransaction_amt = ($i_transaction_fee +$audi_crdtransaction_amt);
																					$audcreditcanceled = $audcreditcanceled + 1;
																					$audcreditcanceledAmt = $audcreditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																					$audcrdcharge_back_count = $audcrdcharge_back_count + 1;
																					$audcrdchargebackamount= $i_charge_back+$audcrdchargebackamount;
																					} else {
																					$audcrdcredit_count = $audcrdcredit_count + 1;
																					$audcrdcredit_amount =$audcrdcredit_amount+ $i_credit;
																					}
																				}
																				//based on cad
																				if($str_processingcurency=='CAD')
																				{
																					$cadcreditcardAmt = $cadcreditcardAmt + $trans_amount;
																					$cadcreditcard = $cadcreditcard + 1;
																					$cadi_crdtransaction_amt = ($i_transaction_fee +$cadi_crdtransaction_amt);
																					$cadcreditcanceled = $cadcreditcanceled + 1;
																					$cadcreditcanceledAmt = $cadcreditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																					$cadcrdcharge_back_count = $cadcrdcharge_back_count + 1;
																					$cadcrdchargebackamount= $i_charge_back+$cadcrdchargebackamount;
																					} else {
																					$cadcrdcredit_count = $cadcrdcredit_count + 1;
																					$cadcrdcredit_amount =$cadcrdcredit_amount+ $i_credit;
																					}
																				}
																				//based on eur
																				if($str_processingcurency=='EUR' ||$str_processingcurency=='EURO')
																				{
																					 $eurcreditcardAmt = $eurcreditcardAmt + $trans_amount;
																					$eurcreditcard = $eurcreditcard + 1;
																					$euri_crdtransaction_amt = ($i_transaction_fee +$euri_crdtransaction_amt);
																					$eurcreditcanceled = $eurcreditcanceled + 1;
																					$eurcreditcanceledAmt = $eurcreditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																					$eurcrdcharge_back_count = $eurcrdcharge_back_count + 1;
																					$eurcrdchargebackamount= $i_charge_back+$eurcrdchargebackamount;
																					} else {
																					$eurcrdcredit_count = $eurcrdcredit_count + 1;
																					$eurcrdcredit_amount =$eurcrdcredit_amount+ $i_credit;
																					}
																				}
																				//based on gbp
																				if($str_processingcurency=='GBP')
																				{
																					$gbpcreditcardAmt = $gbpcreditcardAmt + $trans_amount;
																					$creditcard = $creditcard + 1;
																					$gbpi_crdtransaction_amt = ($i_transaction_fee +$gbpi_crdtransaction_amt);
																					$gbpcreditcanceled = $gbpcreditcanceled + 1;
																					$gbpcreditcanceledAmt = $gbpcreditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																					$gbpcrdcharge_back_count = $gbpcrdcharge_back_count + 1;
																					$gbpcrdchargebackamount= $i_charge_back+$gbpcrdchargebackamount;
																					} else {
																					$gbpcrdcredit_count = $gbpcrdcredit_count + 1;
																					$gbpcrdcredit_amount =$gbpcrdcredit_amount+ $i_credit;
																					}
																				}
																				//based on usd
																				if($str_processingcurency=='USD')
																				{
																					 $usdcreditcardAmt = $usdcreditcardAmt + $trans_amount;
																					$usdcreditcard = $usdcreditcard + 1;
																					$usdi_crdtransaction_amt = ($i_transaction_fee +$usdi_crdtransaction_amt);
																					$usdcreditcanceled = $usdcreditcanceled + 1;
																					$usdcreditcanceledAmt = $usdcreditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																					$usdcrdcharge_back_count = $usdcrdcharge_back_count + 1;
																					$usdcrdchargebackamount= $i_charge_back+$usdcrdchargebackamount;
																					} else {
																					$usdcrdcredit_count = $usdcrdcredit_count + 1;
																					$usdcrdcredit_amount =$usdcrdcredit_amount+ $i_credit;
																					}
																				}	
																		}//end of for loop
																		
																}//cancellationcharges
																
																//charges to be added if if admin rejects the cancels
																$qry_addcancels="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id,admin_approval_for_cancellation,currencytype,cancel_refer_num FROM cs_transactiondetails, cs_companydetails WHERE ";
																$qry_addcancels.=" cs_transactiondetails.userid =$iuserid and cs_companydetails.userid=$iuserid and transactionDate >= '$cancelstartdate' and transactionDate <= '$cancelenddate'  and bank_id= $ibankid and cancelstatus='Y' and admin_approval_for_cancellation ='R' and cancellationDate < '$cancelstartdate' and checkorcard='H' Order by cs_transactiondetails.userid ";
																if(!$rst_addcancels=mysql_query($qry_addcancels,$cnn_cs))
																{
																	print ("cannot execute select query ppp ");
																																		
																}
																else{
																	$num_add=mysql_num_rows($rst_addcancels);
																	for($i_add=0;$i_add<$num_add;$i_add++)
																	{
																		$rst_canceladd= mysql_fetch_array($rst_addcancels);												
																			$trans_type = $rst_canceladd[0];
																			 $trans_status = $rst_canceladd[1];
																			 $trans_cancelstatus =$rst_canceladd[2];
																			 $trans_companyName = $rst_canceladd[3];
																			 $i_charge_back = $rst_canceladd[4];
																			 $i_credit = $rst_canceladd[5];
																			 $i_discount_rate = $rst_canceladd[6];
																			 $i_transaction_fee = $rst_canceladd[7];
																			 $i_reserve =$rst_canceladd[8];
																			 $trans_accounttype = $rst_canceladd[9];
																			 $trans_cardtype = $rst_canceladd[10];
																			 $cancel_reason =$rst_canceladd[11];
																			 $trans_passstatus = $rst_canceladd[12];
																			 $trans_amount =$rst_canceladd[13];
																			 $trans_voicefee =$rst_canceladd[14];
																			 $igatewayid=$rst_canceladd[15];
																			 $iresellerid=$rst_canceladd[16];
																			 $adminapprovalforcancel=$rst_canceladd[17];
																			  $str_processingcurency=$rst_canceladd[18];
																			  $cancel_ref_num=$rst_canceladd[19];
																			 if($cancel_ref_num !="" && $cancel_ref_num !=0){
																			  $qry_select="select r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve from cs_transactiondetails where reference_number =$cancel_ref_num ";
																			  if(!$rst_rates=mysql_query($qry_select,$cnn_cs)){
																			  	print("cannot execute query rrr ");
																			  }else{
																			  		$rst_charges=mysql_fetch_array($rst_rates);
																			  		$i_charge_back = $rst_charges[0];
																			 		$i_credit = $rst_charges[1];
																			 		$i_discount_rate = $rst_charges[2];
																			 		$i_transaction_fee = $rst_charges[2];
																			 		$i_reserve =$rst_charges[4];
																			  }
																			  }				  
																			  if($str_processingcurency==""){
																				$str_processingcurency=func_get_cardcurrency($trans_cardtype,$iuserid,$cnn_cs);
																				}
																				//splitting based on currency type AUD
																				if($str_processingcurency=="AUD"){																				
																				$audrej_crdtransaction_amt = ($i_transaction_fee +$audrej_crdtransaction_amt);
																				$audrej_creditcanceled = $audrej_creditcanceled + 1;
																				$audrej_creditcanceledAmt = $audrej_creditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																				$audrej_crdcharge_back_count = $audrej_crdcharge_back_count + 1;
																				$audrej_crdchargebackamount= $i_charge_back+$audrej_crdchargebackamount;
																				} else {
																				$audrej_crdcredit_count = $audrej_crdcredit_count + 1;
																				$audrej_crdcredit_amount =$audrej_crdcredit_amount+ $i_credit;
																				}
																			}//based on CAD
																			if($str_processingcurency=="CAD"){																				
																				$cadrej_crdtransaction_amt = ($i_transaction_fee +$cadrej_crdtransaction_amt);
																				$cadrej_creditcanceled = $cadrej_creditcanceled + 1;
																				$cadrej_creditcanceledAmt = $cadrej_creditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																				$cadrej_crdcharge_back_count = $cadrej_crdcharge_back_count + 1;
																				$cadrej_crdchargebackamount= $i_charge_back+$cadrej_crdchargebackamount;
																				} else {
																				$cadrej_crdcredit_count = $cadrej_crdcredit_count + 1;
																				$cadrej_crdcredit_amount =$cadrej_crdcredit_amount+ $i_credit;
																				}
																			}//based on EUR
																			if($str_processingcurency=="EUR" ||$str_processingcurency=='EURO'){																				
																				$eurrej_crdtransaction_amt = ($i_transaction_fee +$eurrej_crdtransaction_amt);
																				$eurrej_creditcanceled = $eurrej_creditcanceled + 1;
																				$eurrej_creditcanceledAmt = $eurrej_creditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																				$eurrej_crdcharge_back_count = $eurrej_crdcharge_back_count + 1;
																				$eurrej_crdchargebackamount= $i_charge_back+$eurrej_crdchargebackamount;
																				} else {
																				$eurrej_crdcredit_count = $eurrej_crdcredit_count + 1;
																				$eurrej_crdcredit_amount =$eurrej_crdcredit_amount+ $i_credit;
																				}
																			}//based on GBP
																			if($str_processingcurency=="GBP"){																				
																				$gbprej_crdtransaction_amt = ($i_transaction_fee +$gbprej_crdtransaction_amt);
																				$gbprej_creditcanceled = $gbprej_creditcanceled + 1;
																				$gbprej_creditcanceledAmt = $gbprej_creditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																				$gbprej_crdcharge_back_count = $gbprej_crdcharge_back_count + 1;
																				$gbprej_crdchargebackamount= $i_charge_back+$gbprej_crdchargebackamount;
																				} else {
																				$gbprej_crdcredit_count = $gbprej_crdcredit_count + 1;
																				$gbprej_crdcredit_amount =$gbprej_crdcredit_amount+ $i_credit;
																				}
																			}//based on USD
																			if($str_processingcurency=="USD"){																				
																				$usdrej_crdtransaction_amt = ($i_transaction_fee +$usdrej_crdtransaction_amt);
																				$usdrej_creditcanceled = $usdrej_creditcanceled + 1;
																				$usdrej_creditcanceledAmt = $usdrej_creditcanceledAmt + $trans_amount;
																					if($cancel_reason == "Chargeback") {
																				$usdrej_crdcharge_back_count = $usdrej_crdcharge_back_count + 1;
																				$usdrej_crdchargebackamount= $i_charge_back+$usdrej_crdchargebackamount;
																				} else {
																				$usdrej_crdcredit_count = $usdrej_crdcredit_count + 1;
																				$usdrej_crdcredit_amount =$usdrej_crdcredit_amount+ $i_credit;
																				}
																			}																						
																	}
																}
															//Total Amount calculation
															
												$creditpending=$audcreditpending+$cadcreditpending+$eurcreditpending+$gbpcreditpending+$usdcreditpending;
												$creditpendingAmt=$audcreditpendingAmt+$cadcreditpendingAmt+$eurcreditpendingAmt+$gbpcreditpendingAmt+$usdcreditpendingAmt;												
												$creditapproved=$audcreditapproved+$cadcreditapproved+$eurcreditapproved+$gbpcreditapproved+$usdcreditapproved;
												$creditapprovedAmt=$audcreditapprovedAmt+$cadcreditapprovedAmt+$eurcreditapprovedAmt+$gbpcreditapprovedAmt+$usdcreditapprovedAmt;												
												$creditdeclined=$audcreditdeclined+$cadcreditdeclined+$eurcreditdeclined+$gbpcreditdeclined+$usdcreditdeclined;
												$creditdeclinedAmt=$audcreditdeclinedAmt+$cadcreditdeclinedAmt+$eurcreditdeclinedAmt+$gbpcreditdeclinedAmt+$usdcreditdeclinedAmt;												
												$creditcard=$audcreditcard+$cadcreditcard+$eurcreditcard+$gbpcreditcard+$usdcreditcard;																							
												$creditcardAmt=$audcreditcardAmt+$cadcreditcardAmt+$eurcreditcardAmt+$gbpcreditcardAmt+$usdcreditcardAmt;																																			
												$creditcanceled=$audcreditcanceled+$cadcreditcanceled+$eurcreditcanceled+$gbpcreditcanceled+$usdcreditcanceled;
												$creditcanceledAmt=$audcreditcanceledAmt+$cadcreditcanceledAmt+$eurcreditcanceledAmt+$gbpcreditcanceledAmt+$usdcreditcanceledAmt;
												$crdcredit_count =$audcrdcredit_count+$cadcrdcredit_count+$eurcrdcredit_count+$gbpcrdcredit_count+$usdcrdcredit_count;												
												$crdcharge_back_amount =$audcrdcharge_back_amount+$cadcrdcharge_back_amount+$eurcrdcharge_back_amount+$gbpcrdcharge_back_amount+$usdcrdcharge_back_amount;
												$crdcharge_back_count = $audcrdcharge_back_count+$cadcrdcharge_back_count+$eurcrdcharge_back_count+$gbpcrdcharge_back_count+$usdcrdcharge_back_count;
												$creditnonpass =$audcreditnonpass+$cadcreditnonpass+$eurcreditnonpass+$gbpcreditnonpass+$usdcreditnonpass;
												$creditnonpassAmt = $audcreditnonpassAmt+$cadcreditnonpassAmt+$eurcreditnonpassAmt+$gbpcreditnonpassAmt+$usdcreditnonpassAmt;
												$creditpass =$audcreditpass+$cadcreditpass+$eurcreditpass+$gbpcreditpass+$usdcreditpass;
												$creditpassAmt = $audcreditpassAmt+$cadcreditpassAmt+$eurcreditpassAmt+$gbpcreditpassAmt+$usdcreditpassAmt;
												$crdchargebackamount=$audcrdchargebackamount+$cadcrdchargebackamount+$eurcrdchargebackamount+$gbpchargebackamount+$usdcrdchargebackamount;
												$i_crddiscount_amt=$audi_crddiscount_amt+$cadi_crddiscount_amt+$euri_crddiscount_amt+$gbpi_crddiscount_amt+$usdi_crddiscount_amt;
												$i_crdreserve_amt=$audi_crdreserve_amt+$cadi_crdreserve_amt+$euri_crdreserve_amt+$gbpi_crdreserve_amt+$usdi_crdreserve_amt;
												$crdcredit_amount=$audcrdcredit_amount+$cadcrdcredit_amount+$eurcrdcredit_amount+$gbpcrdcredit_amount+$usdcrdcredit_amount;
												 $i_crdtransaction_amt=$audi_crdtransaction_amt+$cadi_crdtransaction_amt+$euri_crdtransaction_amt+$gbpi_crdtransaction_amt+$usdi_crdtransaction_amt;							
												$rej_crdtransaction_amt = $audrej_crdtransaction_amt+$cadrej_crdtransaction_amt+$eurrej_crdtransaction_amt+$gbprej_crdtransaction_amt+$usdrej_crdtransaction_amt;
												$rej_creditcanceled = $audrej_creditcanceled+$cadrej_creditcanceled+$eurrej_creditcanceled+$gbprej_creditcanceled+$usdrej_creditcanceled;
												$rej_creditcanceledAmt = $audrej_creditcanceledAmt+$cadrej_creditcanceledAmt+$eurrej_creditcanceledAmt+$gbprej_creditcanceledAmt+$usdrej_creditcanceledAmt;												
												$rej_crdcharge_back_count =$audrej_crdcharge_back_count+$cadrej_crdcharge_back_count+$eurrej_crdcharge_back_count+$gbprej_crdcharge_back_count+$usdrej_crdcharge_back_count;
												$rej_crdchargebackamount= $audrej_crdchargebackamount+$cadrej_crdchargebackamount+$eurrej_crdchargebackamount+$gbprej_crdchargebackamount+$usdrej_crdchargebackamount;												
												$rej_crdcredit_count = $audrej_crdcredit_count+$cadrej_crdcredit_count+$eurrej_crdcredit_count+$gbprej_crdcredit_count+$usdrej_crdcredit_count;
												$rej_crdcredit_amount =$audrej_crdcredit_amount+$cadrej_crdcredit_amount+$eurrej_crdcredit_amount+$gbprej_crdcredit_amount+$usdrej_crdcredit_amount;
															//ends here														
														 //$i_total_voice_amt = ($voice_count_uploads * $trans_voicefee);
														 //rejected total
														 $rej_total=$rej_crdtransaction_amt+$rej_creditcanceledAmt+$rej_crdchargebackamount+$rej_crdcredit_amount;
														 $crddeducted_amt =  ($crdcharge_back_amount + $crdcredit_amount  + $i_crddiscount_amt + $i_crdtransaction_amt + $i_crdreserve_amt +$creditcanceledAmt);
														  $i_crdnet_amt = ($creditapprovedAmt+$rej_total) - $crddeducted_amt;
															//total calculation for each currency AUD
														 $audrej_total=$audrej_crdtransaction_amt+$audrej_creditcanceledAmt+$audrej_crdchargebackamount+$audrej_crdcredit_amount;
														 $audcrddeducted_amt =  ($audcrdcharge_back_amount + $audcrdcredit_amount  + $audi_crddiscount_amt + $audi_crdtransaction_amt + $audi_crdreserve_amt +$audcreditcanceledAmt);
														  $audi_crdnet_amt = ($audcreditapprovedAmt+$audrej_total) - $audcrddeducted_amt;
														  //total for CAD
															$cadrej_total=$cadrej_crdtransaction_amt+$cadrej_creditcanceledAmt+$cadrej_crdchargebackamount+$cadrej_crdcredit_amount;
														 $cadcrddeducted_amt =  ($cadcrdcharge_back_amount + $cadcrdcredit_amount  + $cadi_crddiscount_amt + $cadi_crdtransaction_amt + $cadi_crdreserve_amt +$cadcreditcanceledAmt);
														  $cadi_crdnet_amt = ($cadcreditapprovedAmt+$cadrej_total) - $cadcrddeducted_amt;
														  //total for EUR
														  $eurrej_total=$eurrej_crdtransaction_amt+$eurrej_creditcanceledAmt+$eurrej_crdchargebackamount+$eurrej_crdcredit_amount;
														 $eurcrddeducted_amt =  ($eurcrdcharge_back_amount + $eurcrdcredit_amount  + $euri_crddiscount_amt + $euri_crdtransaction_amt + $euri_crdreserve_amt +$eurcreditcanceledAmt);
														  $euri_crdnet_amt = ($eurcreditapprovedAmt+$eurrej_total) - $eurcrddeducted_amt;
														  //total for GBP
														  $gbprej_total=$gbprej_crdtransaction_amt+$gbprej_creditcanceledAmt+$gbprej_crdchargebackamount+$gbprej_crdcredit_amount;
														 $gbpcrddeducted_amt =  ($gbpcrdcharge_back_amount + $gbpcrdcredit_amount  + $gbpi_crddiscount_amt + $gbpi_crdtransaction_amt + $gbpi_crdreserve_amt +$gbpcreditcanceledAmt);
														  $gbpi_crdnet_amt = ($gbpcreditapprovedAmt+$gbprej_total) - $gbpcrddeducted_amt;
														  //total for USD
														  $usdrej_total=$usdrej_crdtransaction_amt+$usdrej_creditcanceledAmt+$usdrej_crdchargebackamount+$usdrej_crdcredit_amount;
														 $usdcrddeducted_amt =  ($usdcrdcharge_back_amount + $usdcrdcredit_amount  + $usdi_crddiscount_amt + $usdi_crdtransaction_amt + $usdi_crdreserve_amt +$usdcreditcanceledAmt);
														  $usdi_crdnet_amt = ($usdcreditapprovedAmt+$usdrej_total) - $usdcrddeducted_amt;
														  
															$qry_netamount="select netAmount from cs_invoicedetails where userId =$iuserid and checkorcard ='H'  and generateddate <' $approvedstatusdate' order by generateddate desc";
															$rst_execute=mysql_query($qry_netamount,$cnn_cs);
															$rst_netamount=mysql_fetch_array($rst_execute);
															 $pevnetamountcrd=$rst_netamount[0];
															if($pevnetamountcrd < 500 ){
																	$i_crdnet_amt +=$pevnetamountcrd;														
																}
															if($i_crdnet_amt>=500){
																$i_crdnet_amt-=50;
																	$wirefee=50;
																}
														
														if($creditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicedetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount)values('$iuserid','$igatewayid','$creditcardAmt','$creditapprovedAmt','$creditdeclinedAmt','$creditcanceledAmt','$creditpendingAmt','$crdchargebackamount','$crdcredit_amount','$i_crddiscount_amt','$i_crdtransaction_amt',$i_crdvoiceauth_amt,'$i_crdreserve_amt','$crddeducted_amt','$i_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'$str_processingcurency','$creditcard','$creditapproved','$creditdeclined','$creditpending','$crdcredit_count',0,'$crdcharge_back_count','$iresellerid','$creditcanceled'";
														$qry_invoice.=",'$creditnonpassAmt','$creditnonpass','$creditpassAmt','$creditpass','$chqvoice_authcount',$wirefee,$ibankid,$rej_creditcanceled,$rej_creditcanceledAmt,$rej_crdcredit_amount,$rej_crdchargebackamount,$rej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$rej_crdcredit_count,$rej_crdcharge_back_count )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															else{
															$transid = mysql_insert_id(); 
															$ref_number=func_Trans_Ref_No($transid );
															$updateSuccess=func_update_single_field('cs_invoicedetails','invoiceReferenceNumber',$ref_number,'invoiceId',$transid,$cnn_cs);
															}
														}//inserting the currency details AUD
														if($audcreditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicecurrencydetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount,invoiceId)values('$iuserid','$igatewayid','$audcreditcardAmt','$audcreditapprovedAmt','$audcreditdeclinedAmt','$audcreditcanceledAmt','$audcreditpendingAmt','$audcrdchargebackamount','$audcrdcredit_amount','$audi_crddiscount_amt','$audi_crdtransaction_amt',0,'$audi_crdreserve_amt','$audcrddeducted_amt','$audi_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'AUD','$audcreditcard','$audcreditapproved','$audcreditdeclined','$audcreditpending','$audcrdcredit_count',0,'$audcrdcharge_back_count','$iresellerid','$audcreditcanceled'";
														$qry_invoice.=",'$audcreditnonpassAmt','$audcreditnonpass','$audcreditpassAmt','$audcreditpass','0',$wirefee,$ibankid,$audrej_creditcanceled,$audrej_creditcanceledAmt,$audrej_crdcredit_amount,$audrej_crdchargebackamount,$audrej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$audrej_crdcredit_count,$audrej_crdcharge_back_count,$transid )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															
														
													}//CAD
													if($cadcreditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicecurrencydetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount,invoiceId)values('$iuserid','$igatewayid','$cadcreditcardAmt','$cadcreditapprovedAmt','$cadcreditdeclinedAmt','$cadcreditcanceledAmt','$cadcreditpendingAmt','$cadcrdchargebackamount','$cadcrdcredit_amount','$cadi_crddiscount_amt','$cadi_crdtransaction_amt',0,'$cadi_crdreserve_amt','$cadcrddeducted_amt','$cadi_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'CAD','$cadcreditcard','$cadcreditapproved','$cadcreditdeclined','$cadcreditpending','$cadcrdcredit_count',0,'$cadcrdcharge_back_count','$iresellerid','$cadcreditcanceled'";
														$qry_invoice.=",'$cadcreditnonpassAmt','$cadcreditnonpass','$cadcreditpassAmt','$cadcreditpass','0',$wirefee,$ibankid,$cadrej_creditcanceled,$cadrej_creditcanceledAmt,$cadrej_crdcredit_amount,$cadrej_crdchargebackamount,$cadrej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$cadrej_crdcredit_count,$cadrej_crdcharge_back_count,$transid )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															
														
													}//EUR
													if($eurcreditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicecurrencydetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount,invoiceId)values('$iuserid','$igatewayid','$eurcreditcardAmt','$eurcreditapprovedAmt','$eurcreditdeclinedAmt','$eurcreditcanceledAmt','$eurcreditpendingAmt','$eurcrdchargebackamount','$eurcrdcredit_amount','$euri_crddiscount_amt','$euri_crdtransaction_amt',0,'$euri_crdreserve_amt','$eurcrddeducted_amt','$euri_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'EUR','$eurcreditcard','$eurcreditapproved','$eurcreditdeclined','$eurcreditpending','$eurcrdcredit_count',0,'$eurcrdcharge_back_count','$iresellerid','$eurcreditcanceled'";
														$qry_invoice.=",'$eurcreditnonpassAmt','$eurcreditnonpass','$eurcreditpassAmt','$eurcreditpass','0',$wirefee,$ibankid,$eurrej_creditcanceled,$eurrej_creditcanceledAmt,$eurrej_crdcredit_amount,$eurrej_crdchargebackamount,$eurrej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$eurrej_crdcredit_count,$eurrej_crdcharge_back_count,$transid )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															
														
													}//GBP
													if($gbpcreditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicecurrencydetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount,invoiceId)values('$iuserid','$igatewayid','$gbpcreditcardAmt','$gbpcreditapprovedAmt','$gbpcreditdeclinedAmt','$gbpcreditcanceledAmt','$gbpcreditpendingAmt','$gbpcrdchargebackamount','$gbpcrdcredit_amount','$gbpi_crddiscount_amt','$gbpi_crdtransaction_amt',0,'$gbpi_crdreserve_amt','$gbpcrddeducted_amt','$gbpi_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'GBP','$gbpcreditcard','$gbpcreditapproved','$gbpcreditdeclined','$gbpcreditpending','$gbpcrdcredit_count',0,'$gbpcrdcharge_back_count','$iresellerid','$gbpcreditcanceled'";
														$qry_invoice.=",'$gbpcreditnonpassAmt','$gbpcreditnonpass','$gbpcreditpassAmt','$gbpcreditpass','0',$wirefee,$ibankid,$gbprej_creditcanceled,$gbprej_creditcanceledAmt,$gbprej_crdcredit_amount,$gbprej_crdchargebackamount,$gbprej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$gbprej_crdcredit_count,$gbprej_crdcharge_back_count,$transid )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															
														
													}//USD
													if($usdcreditcardAmt!=0){
														$qry_invoice= "insert into cs_invoicecurrencydetails (userId,gatewayid,totalAmt,approvedAmt,declinedAmt,creditAmt,pendingamt ,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions,netAmount,adminApproved,generateddate,startdate,enddate,checkorcard,processingcurrency,transactionno,approvedno,declinedno,pendingno,creditno,voiceuploadfee,chargebackno, resellerid,canceledno,nopass,nopasscount,passed,passedcount,voiceuploadcount,wirefee,bank_id,reject_count,reject_amt,reject_creditamt,reject_chargebackamt,reject_transfee,cancel_startdate,cancel_enddate,reject_creditcount,reject_chargebackcount,invoiceId)values('$iuserid','$igatewayid','$usdcreditcardAmt','$usdcreditapprovedAmt','$usdcreditdeclinedAmt','$usdcreditcanceledAmt','$usdcreditpendingAmt','$usdcrdchargebackamount','$usdcrdcredit_amount','$usdi_crddiscount_amt','$usdi_crdtransaction_amt',0,'$usdi_crdreserve_amt','$usdcrddeducted_amt','$usdi_crdnet_amt','N','$approvedstatusdate','$str_startdate','$str_afterdate','H',";
														$qry_invoice.="'USD','$usdcreditcard','$usdcreditapproved','$usdcreditdeclined','$usdcreditpending','$usdcrdcredit_count',0,'$usdcrdcharge_back_count','$iresellerid','$usdcreditcanceled'";
														$qry_invoice.=",'$usdcreditnonpassAmt','$usdcreditnonpass','$usdcreditpassAmt','$usdcreditpass','0',$wirefee,$ibankid,$usdrej_creditcanceled,$usdrej_creditcanceledAmt,$usdrej_crdcredit_amount,$usdrej_crdchargebackamount,$usdrej_crdtransaction_amt,'$cancelstartdate','$cancelenddate',$usdrej_crdcredit_count,$usdrej_crdcharge_back_count,$transid )";
														
														if(!$rst_result=mysql_query($qry_invoice,$cnn_cs))
															{
															print(mysql_errno().": ".mysql_error()."<BR>");
															print("Cannot execute insert query");
															exit();
															}
															
														
													}//
												}//end of if
	//echo $trans_amount;
												}//end of else
											}//end of for loop
										}//end of else
								//end of innerloop
									}//end of inner if
							}//end of outermostif
							}//end of outermostfor
							}//end of outermostelse
//fuction to find the day of a week
function func_dayofweek($iday)
{
	$strday="";
	switch ($iday) { 
     case 1: 
      $strday="Sunday";
       break; 
     case 2: 
      $strday="Monday";
       break; 
     case 3: 
      $strday="Tuesday";
       break;
	   case 4: 
      $strday="Wednesday";
       break;
	   case 5: 
      $strday="Thursday";
       break; 
     case 6: 
      $strday="Friday";
       break;
	   case 7: 
      $strday="Saturday";
       break;
	   
	} 
	return $strday;
}
?>