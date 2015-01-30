<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// resellerLedger.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude="ledgers";
$periodhead="Ledgers";
include 'includes/topheader.php';
require_once("includes/function.php");
include 'includes/function1.php';?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
       <td width="100%" valign="top" align="center"  height="333">
    &nbsp;
    <table width="55%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Future Invoice&nbsp;Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
<?php

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$imonth =isset($HTTP_GET_VARS["futmonth"])?$HTTP_GET_VARS["futmonth"]:"";
$iyear =isset($HTTP_GET_VARS["futyear"])?$HTTP_GET_VARS["futyear"]:"";
$iday =isset($HTTP_GET_VARS["futday"])?$HTTP_GET_VARS["futday"]:"";
print("<table width='100%' border='0' align='center' cellspacing='0' cellpadding='0'>");

func_futureamount($cnn_cs,$iyear,$imonth,$iday,$sessionlogin);
?>

<?php 
include("includes/footer.php");
?>
<?php
function func_futureamount($cnn_cs,$str_year_from,$str_month_from,$iDisplayNumber,$sessionlogin){
$netamount=0;  
$qry_bank="select * from cs_bank where bank_paybackday !=-1 ";
if(!$rst_bank=mysql_query($qry_bank,$cnn_cs)){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

else{
$str_year_from.'-'.$str_month_from.'-'.$iDisplayNumber."<br>";
$num=mysql_num_rows($rst_bank);
for($iloop=1;$iloop<=$num;$iloop++){
$rst_bankday=mysql_fetch_array($rst_bank);
 $ibankid=$rst_bankday[0];
$payday=$rst_bankday[7];
$paybackday=$rst_bankday[4];
$ipayweekto=$rst_bankday[6];
$ipayweekfrom=$rst_bankday[5];
 $time = mktime(0,0,0,$str_month_from,$iDisplayNumber,$str_year_from);
$time=$time-($paybackday*86400);
 $ibeforeday=date('Y-m-d',$time);
$iweek=date('w',$time);

$iweek+=1;
$ito=$ipayweekto;
$ifrom=$ipayweekfrom;
 $ipayweekto=func_dayofweek($ipayweekto);
 $ipayweekfrom=func_dayofweek($ipayweekfrom);
if($ifrom==$payday)
{
$todate=$time;
}
else
{
$todate=strtotime ("last $ipayweekfrom",$time);
}
if($ito==$payday)
{
$enddate=$time; 
$enddate= date('Y-m-d',$enddate);
 $enddate.=" 23:59:59";
}
else
{
$enddate=strtotime ("this $ipayweekto",$time);
$enddate= date('Y-m-d',$enddate);
 $enddate.=" 23:59:59";
}
  
  $todate= date('Y-m-d',$todate);
 $todate.=" 00:00:00";
/* $qry_getuserid="select Distinct(userId)from cs_transactiondetails where transactionDate >= '$todate' and transactionDate <= '$enddate' and bank_id=$ibankid Order by cs_transactiondetails.userId";
						if(!($show_sql = mysql_query($qry_getuserid,$cnn_cs))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

						}
						else{
								$inum=mysql_num_rows($show_sql);
								for ($iloop1=1;$iloop1<=$inum;$iloop1++){
									$rst_userid= mysql_fetch_array($show_sql);
									//echo "<br>".$rst_userid[0]."<br>";
									 $iuserid=$rst_userid[0];*/
$qry_transdetails=" SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id ,admin_approval_for_cancellation FROM cs_transactiondetails, cs_companydetails WHERE ";
$qry_transdetails.=" cs_transactiondetails.userid =  $sessionlogin and cs_companydetails.userid= $sessionlogin and transactionDate >='$todate'  and transactionDate <= '$enddate' and bank_id=$ibankid and checkorcard='H' and gateway_id  ='-1' Order by cs_transactiondetails.userid";
//echo $qry_transdetails;
//procedure to calculate the future net amount
if(!($show_transdetails = mysql_query($qry_transdetails,$cnn_cs))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

									} 
									else{	
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
											$chqdeducted_amt = 0;
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
											$trans_companyName="";
											$i_crdnet_amt=0;
											$i_chqnet_amt =0;
											$rej_crdtransaction_amt = 0;
											$rej_creditcanceled =0;
											$rej_creditcanceledAmt = 0;
												
											$rej_crdcharge_back_count = 0;
											$rej_crdchargebackamount=0;
											
											$rej_crdcredit_count = 0;
											$rej_crdcredit_amount=0;										
											$inum1=mysql_num_rows($show_transdetails);
											$iflag=0;
											for ($iloop2=1;$iloop2<=$inum1;$iloop2++){
												
												$rst_transdetails= mysql_fetch_array($show_transdetails);
												if($rst_transdetails[3]!=$trans_companyName)
												$iflag=1;												
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
													if ($trans_type =="C") {																
																if($trans_cancelstatus =="N") {
																			$i_chqtransaction_amt = $i_transaction_fee +$i_chqtransaction_amt;
																			$cheque = $cheque + 1;
																			$chequeAmt = $chequeAmt + $trans_amount;
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
																			$chequeapprovedAmt =$chequeapprovedAmt + $trans_amount;
																			$i_chqdiscount_amt = (($i_discount_rate * $trans_amount) / 100) + $i_chqdiscount_amt;
																			$i_chqreserve_amt = (($i_reserve *$trans_amount) / 100)+$i_chqreserve_amt;

																		}
																		if($trans_status =="D")	{
																			$chequedeclined = $chequedeclined + 1;
																			$chequedeclinedAmt = $chequedeclinedAmt + $trans_amount;
																		}
																		$chqvoice_authcount = $chqvoice_authcount + 1;
																	} else {
																		$chequepending = $chequepending + 1;
																		$chequependingAmt = $chequependingAmt + $trans_amount;
																	}
																}
																
															} else {									
																if($trans_cancelstatus =="N") {
																		 $i_crdtransaction_amt = $i_transaction_fee +$i_crdtransaction_amt;
																		$creditcard = $creditcard + 1;
																		$creditcardAmt = $creditcardAmt + $trans_amount;
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
																			$i_crddiscount_amt = (($i_discount_rate * $trans_amount) / 100)+$i_crddiscount_amt;
                                                                            $i_crdreserve_amt = (($i_reserve * $trans_amount) / 100)+$i_crdreserve_amt ;																		}
																		if($trans_status =="D")	{
																			$creditdeclined = $creditdeclined + 1;
																			$creditdeclinedAmt = $creditdeclinedAmt + $trans_amount;
																		}
																		$chqvoice_authcount = $chqvoice_authcount + 1;
																	} else {
																		$creditpending = $creditpending + 1;
																		$creditpendingAmt = $creditpendingAmt + $trans_amount;
																	}
																}
																}
																
													}//end of for loop
													//modified part
													$qry_bankexit="select * from cs_transactiondetails where bank_id='$ibankid' and userId=$sessionlogin and transactionDate <'$todate'";
																	if(!$rst_bankexist=mysql_query($qry_bankexit,$cnn_cs))
																	{print(mysql_errno().": ".mysql_error()."<BR>");
																		print("cannot execute select query nnnn");
																		exit();
																		print("cannot execute select query nnnn");
																	}
																	else{
																			$num_records=mysql_num_rows($rst_bankexist);
																			if($num_records >0)
																			{
																			$time = mktime(0,0,0,$str_month_from,$iDisplayNumber,$str_year_from);
																				$cancelenddate =strtotime ("-1 day",$time);
																				$cancelenddate= date('Y-m-d',$cancelenddate);																	
																				 $cancelstartdate= strtotime ("-1 week",$time);
																				$cancelstartdate= date('Y-m-d',$cancelstartdate);	
																			}
																			else
																			{
																				$time = mktime(0,0,0,$str_month_from,$iDisplayNumber,$str_year_from);
																				$cancelenddate =strtotime ("-1 day",$time);
																				 $cancelenddate= date('Y-m-d',$cancelenddate);																	
																				  $cancelstartdate=$todate; 	
																			}
																			$cancelstartdate.=" 00:00:00";
																			$cancelenddate.=" 23:59:59";
																			$qry_cancels="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id,admin_approval_for_cancellation,currencytype,cancel_refer_num FROM cs_transactiondetails, cs_companydetails WHERE ";
																			 $qry_cancels.=" cs_transactiondetails.userid =$sessionlogin and cs_companydetails.userid=$sessionlogin and cancellationDate >= '$cancelstartdate' and cancellationDate <= '$cancelenddate'  and bank_id= $ibankid and cancelstatus='Y' and admin_approval_for_cancellation !='R' and checkorcard='H' and gateway_id  ='-1'  Order by cs_transactiondetails.userid ";
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
																						 
																							//splitting based on currency type AUD
																							
																								$creditcardAmt = $creditcardAmt + $trans_amount;
																								$creditcard = $creditcard + 1;
																								$i_crdtransaction_amt = ($i_transaction_fee +$i_crdtransaction_amt);
																								$creditcanceled = $creditcanceled + 1;
																								$creditcanceledAmt = $creditcanceledAmt + $trans_amount;
																								if($cancel_reason == "Chargeback") {
																								$crdcharge_back_count = $crdcharge_back_count + 1;
																								$crdchargebackamount= $i_charge_back+$crdchargebackamount;
																								} else {
																								$crdcredit_count = $crdcredit_count + 1;
																								$crdcredit_amount =$crdcredit_amount+ $i_credit;
																								}
																							
																								
																					}//end of for loop
																			}//cancellationcharges
																			//charges to be added if if admin rejects the cancels
																			$qry_addcancels="SELECT checkorcard, STATUS , cancelstatus, companyname, r_chargeback ,r_credit , r_discountrate , r_transactionfee ,r_reserve , accounttype, cardtype,reason,passstatus,amount,voiceauthfee,gateway_id,reseller_id,admin_approval_for_cancellation,currencytype,cancel_refer_num FROM cs_transactiondetails, cs_companydetails WHERE ";
																			$qry_addcancels.=" cs_transactiondetails.userid =$sessionlogin and cs_companydetails.userid=$sessionlogin and transactionDate >= '$cancelstartdate' and transactionDate <= '$cancelenddate'  and bank_id= $ibankid and cancelstatus='Y' and admin_approval_for_cancellation ='R' and cancellationDate < '$cancelstartdate' and checkorcard='H' and gateway_id  ='-1'  Order by cs_transactiondetails.userid ";
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
																						  
																							//splitting based on currency type AUD
																																											
																							$rej_crdtransaction_amt = ($i_transaction_fee +$rej_crdtransaction_amt);
																							$rej_creditcanceled = $rej_creditcanceled + 1;
																							$rej_creditcanceledAmt = $rej_creditcanceledAmt + $trans_amount;
																								if($cancel_reason == "Chargeback") {
																							$rej_crdcharge_back_count = $rej_crdcharge_back_count + 1;
																							$rej_crdchargebackamount= $i_charge_back+$rej_crdchargebackamount;
																							} else {
																							$rej_crdcredit_count = $rej_crdcredit_count + 1;
																							$rej_crdcredit_amount =$rej_crdcredit_amount+ $i_credit;
																							}
																						
																																												
																				}
																			}
																			//}
													//ends here
													// rounding of check
													$rejectedtotal=$rej_crdtransaction_amt+$rej_creditcanceledAmt+$rej_crdchargebackamount+$rej_crdcredit_amount;
													$chequeapprovedAmt=func_roundidndg( $chequeapprovedAmt, 2 );
													$chequedeclinedAmt=func_roundidndg( $chequedeclinedAmt, 2 );
													$chequecanceledAmt=func_roundidndg( $chequecanceledAmt, 2 );
													$chequeAmt=func_roundidndg( $chequeAmt, 2 );
													$chqchargebackamount=func_roundidndg( $chqchargebackamount, 2 );
													$chqcredit_amount=func_roundidndg( $chqcredit_amount, 2 );
													$i_chqdiscount_amt=func_roundidndg( $i_chqdiscount_amt, 2 );
													$i_chqtransaction_amt=func_roundidndg( $i_chqtransaction_amt, 2 );
													$i_chqreserve_amt=func_roundidndg( $i_chqreserve_amt, 2 );
													//rounding off card
													
													$creditapprovedAmt=func_roundidndg( $creditapprovedAmt, 2 );
													$creditdeclinedAmt=func_roundidndg( $creditdeclinedAmt, 2 );
													$creditcanceledAmt=func_roundidndg( $creditcanceledAmt, 2 );
													$creditcardAmt=func_roundidndg( $creditcardAmt, 2 );
													$crdchargebackamount=func_roundidndg( $crdchargebackamount, 2 );
													$crdcredit_amount=func_roundidndg( $crdcredit_amount, 2 );
													$i_crddiscount_amt=func_roundidndg( $i_crddiscount_amt, 2 );
													$i_crdtransaction_amt=func_roundidndg( $i_crdtransaction_amt, 2 );
													$i_crdreserve_amt=func_roundidndg( $i_crdreserve_amt, 2 );
													$chqdeducted_amt =  ($chqcharge_back_amount + $chqcredit_amount  + $i_chqdiscount_amt + $i_chqtransaction_amt + $i_chqreserve_amt +$chequecanceledAmt);
													 $crddeducted_amt =   ($crdcharge_back_amount + $crdcredit_amount  + $i_crddiscount_amt + $i_crdtransaction_amt + $i_crdreserve_amt +$creditcanceledAmt);
													  $i_chqnet_amt =  $chequeapprovedAmt - $chqdeducted_amt;
													  $i_crdnet_amt = $creditapprovedAmt - $crddeducted_amt+$rejectedtotal;
													$chqdeducted_amt=func_roundidndg( $chqdeducted_amt, 2 );
													$i_chqnet_amt=func_roundidndg( $i_chqnet_amt, 2 );
													$crddeducted_amt=func_roundidndg( $crddeducted_amt, 2 );
													$i_crdnet_amt=func_roundidndg( $i_crdnet_amt, 2 );
													if($iflag==1){
		print("<tr align='center'><td align='center' colspan='14'><br><P align='center'><font face='verdana' size='2'><B>$trans_companyName</B></font><br></td></tr>");}
													if($chequeAmt!='')
													{?>
													<tr align='center'><td align='center' colspan='14'><br><P ><font face='verdana' size='2'><B>Check Details</B></font><br></td></tr>
				<tr height='30' bgcolor='#78B6C2'>
				<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Approved</span></td>
			   <td align='right' class='cl1'><span class='subhd'>Declined </span></td>
				<td align='center' class='cl1'><span class='subhd'>Refunded</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Total</span></td>
				
			   <td align='right' class='cl1'><span class='subhd'>Deduction </span></td>
				
				<td align='center' class='cl1'><span class='subhd'>Charge Back</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Credit</span></td>
			   <td align='right' class='cl1'><span class='subhd'>Discount </span></td>
				<td align='center' class='cl1'><span class='subhd'>Transaction Fees</span></td>
				<td align='center' class='cl1'><span class='subhd'>Reserve Fees</span></td>
					
			   <td align='center' class='cl1'><span class='subhd'>Deduced Amount</span></td>
			   	<td align='right' class='cl1'><span class='subhd'> Net Amount</span></td>
				
				
				
				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$chequeapproved?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$chequedeclined?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$chqcredit_count?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$cheque?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Rate </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_charge_back?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_credit?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_discount_rate?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_transaction_fee?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_reserve?></font></td>
					
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' ><?=$chqdeducted_amt?></font></td>
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' ><?=$i_chqnet_amt?></font></td>
			   
								</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$chequeapprovedAmt?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$chequedeclinedAmt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$chequecanceledAmt?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$chequeAmt?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Amount </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$chqchargebackamount?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$chqcredit_amount?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_chqdiscount_amt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_chqtransaction_amt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_chqreserve_amt?></font></td>	
			   	
				</tr>
													
													<?php }
													if($creditcardAmt!=''){?>
													<tr align='center'><td align='center' colspan='14'><br><P ><font face='verdana' size='2'><B>Credit Card Details</B></font><br></td></tr>
				<tr height='30' bgcolor='#78B6C2' align='center'>
				<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Approved</span></td>
			   <td align='center' class='cl1'><span class='subhd'>Declined </span></td>
				<td align='center' class='cl1'><span class='subhd'>Refunded</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Total</span></td>
				<td align='center' class='cl1'><span class='subhd'>Deduction </span></td>				
				<td align='center' class='cl1'><span class='subhd'>Charge Back</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Credit</span></td>
			   	<td align='center' class='cl1'><span class='subhd'>Discount </span></td>
				<td align='center' class='cl1'><span class='subhd'>Transaction Fees</span></td>
				<td align='center' class='cl1'><span class='subhd'>Reserve Fees</span></td>					
			   <td align='center' class='cl1'><span class='subhd'>Deduced Amount</span></td>
			   <td align='center' class='cl1'><span class='subhd'> Net Amount</span></td>
				
				
				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$creditapproved?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$creditdeclined?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$crdcredit_count?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$creditcard?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Rate </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_charge_back?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$i_credit?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_discount_rate?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_transaction_fee?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_reserve?></font></td>
					
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' ><?=$crddeducted_amt?></font></td>
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' > <?=$i_crdnet_amt?></font></td>	
				
				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$creditapprovedAmt?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$creditdeclinedAmt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$creditcanceledAmt?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$creditcardAmt?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Amount </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$crdchargebackamount?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$crdcredit_amount?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$i_crddiscount_amt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_crdtransaction_amt?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$i_crdreserve_amt?></font></td>	
			   	
				</tr>
													
													<?php }
													 													
													 
													 
													  
													

//}//end of else
 $netamount+=$i_chqnet_amt+$i_crdnet_amt;
}//outer for loop
}//outerelse
//ends here	
}									
}?>

 </td>
                  </tr>
				  <tr><td colspan="14"><table align="center"  ><tr>
		<td align="center" valign="center" height="30" colspan="2"><a href="projectedsettlement.php"><img border="0" src="images/back.jpg"></a>                    </td>
                  </tr>	
</table></td></tr>	
</table>
	
	</td>
      </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table><br>
    </td>
     </tr>
</table>
<?php //fuction ends here
}

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
//function for roundidng of
function func_roundidndg( $float, $precision ) 
{ 
  // $float is really a float value here! 
  // e.g. $float === 3.0249999999; 
  $float = round( $float*100 )/100; 
  return $float; 
} 

?>