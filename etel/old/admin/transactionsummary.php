<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// transactionsummary.php:. 
include("includes/sessioncheck.php");


include("../includes/function2.php");
$i_count = (isset($HTTP_POST_VARS["icount"])?quote_smart($HTTP_POST_VARS["icount"]):"");
$companyid = (isset($HTTP_POST_VARS["hidcompanyid"])?quote_smart($HTTP_POST_VARS["hidcompanyid"]):"");
$frequency = (isset($HTTP_POST_VARS["hidfrequency"])?quote_smart($HTTP_POST_VARS["hidfrequency"]):"");
$miscAdd = (isset($HTTP_POST_VARS["hidmiscAdd"])?quote_smart($HTTP_POST_VARS["hidmiscAdd"]):"");
$miscSub = (isset($HTTP_POST_VARS["hidmiscSub"])?quote_smart($HTTP_POST_VARS["hidmiscSub"]):"");
$betweendates_start= (isset($HTTP_POST_VARS["hidbetweendates_start_1"])?quote_smart($HTTP_POST_VARS["hidbetweendates_start_1"]):"");
//echo $betweendates_start."bet"."<BR>";
$betweendates_end= (isset($HTTP_POST_VARS["hidbetweendates_end_1"])?quote_smart($HTTP_POST_VARS["hidbetweendates_end_1"]):"");
//echo $betweendates_end."bet"."<BR>";
$checkexsists=0;
$insert=0;
$startdate="";
$enddate="";
$appstatus="";
// for loop for getting values

for($i=1;$i<=$i_count;$i++)
	{		
		$updateSuccess="";
		$approvedstatus="";
		$Approvedamt= (isset($HTTP_POST_VARS["hidApprovedamt".$i])?quote_smart($HTTP_POST_VARS["hidApprovedamt".$i]):"");
		//echo $Approvedamt."<BR>"; 
		$declinedamt= (isset($HTTP_POST_VARS["hiddeclinedamt".$i])?quote_smart($HTTP_POST_VARS["hiddeclinedamt".$i]):"");
		//echo $declinedamt."<BR>";
		$cancelamt= (isset($HTTP_POST_VARS["hidcancelamt".$i])?quote_smart($HTTP_POST_VARS["hidcancelamt".$i]):"");
		//echo $cancelamt."<BR>";
		$subtotalamt= (isset($HTTP_POST_VARS["hidsubtotalamt".$i])?quote_smart($HTTP_POST_VARS["hidsubtotalamt".$i]):"");
		//echo $subtotalamt."<BR>";
		$transAmt= (isset($HTTP_POST_VARS["hidtransAmt".$i])?quote_smart($HTTP_POST_VARS["hidtransAmt".$i]):"");
		//echo $transAmt."<BR>";
		$transFees= (isset($HTTP_POST_VARS["hidtransFees".$i])?quote_smart($HTTP_POST_VARS["hidtransFees".$i]):"");
		//echo $transFees."<BR>";
		$voiceAuthFees= (isset($HTTP_POST_VARS["hidvoiceAuthFees".$i])?quote_smart($HTTP_POST_VARS["hidvoiceAuthFees".$i]):"");
		//echo $voiceAuthFees."<BR>";
		$chargeback= (isset($HTTP_POST_VARS["hidchargeback".$i])?quote_smart($HTTP_POST_VARS["hidchargeback".$i]):"");
		//echo $chargeback."<BR>";
		$credit= (isset($HTTP_POST_VARS["hidcredit".$i])?quote_smart($HTTP_POST_VARS["hidcredit".$i]):"");
		//echo $credit."<BR>";
		$discount= (isset($HTTP_POST_VARS["hiddiscount".$i])?quote_smart($HTTP_POST_VARS["hiddiscount".$i]):"");
		//echo $discount."<BR>";
		$reserve= (isset($HTTP_POST_VARS["hidreserve".$i])?quote_smart($HTTP_POST_VARS["hidreserve".$i]):"");
		//echo $reserve."<BR>";
		$totalDeduction= (isset($HTTP_POST_VARS["hidtotalDeduction".$i])?quote_smart($HTTP_POST_VARS["hidtotalDeduction".$i]):"");
		//echo $totalDeduction."<BR>";
		$netAmt= (isset($HTTP_POST_VARS["hidnetAmt".$i])?quote_smart($HTTP_POST_VARS["hidnetAmt".$i]):"");
		//echo $netAmt."<BR>";
		//$weekdates= (isset($HTTP_POST_VARS["hidweekdates".$i])?quote_smart($HTTP_POST_VARS["hidweekdates".$i]):"");
		//echo $weekdates."<BR>";
		$startdates_W= (isset($HTTP_POST_VARS["hidstartdates_W".$i])?quote_smart($HTTP_POST_VARS["hidstartdates_W".$i]):"");
		//echo $startdates_W."<BR>";
		$enddates_W= (isset($HTTP_POST_VARS["hidenddates_W".$i])?quote_smart($HTTP_POST_VARS["hidenddates_W".$i]):"");
		//echo $enddates_W."<BR>";
		$startdates_D= (isset($HTTP_POST_VARS["hidstartdates_D".$i])?quote_smart($HTTP_POST_VARS["hidstartdates_D".$i]):"");
		//echo $startdates_D."<BR>";
		$frequency= (isset($HTTP_POST_VARS["hidfrequency".$i])?quote_smart($HTTP_POST_VARS["hidfrequency".$i]):"");
		//echo $frequency."<BR>";
		$monthstartdates= (isset($HTTP_POST_VARS["hidstartdates".$i])?quote_smart($HTTP_POST_VARS["hidstartdates".$i]):"");
		//echo $monthstartdates."<BR>";
		$monthenddates= (isset($HTTP_POST_VARS["hidenddates".$i])?quote_smart($HTTP_POST_VARS["hidenddates".$i]):"");
		//echo $monthenddates."<BR>";
		$betweendates_start= (isset($HTTP_POST_VARS["hidbetweendates_start".$i])?quote_smart($HTTP_POST_VARS["hidbetweendates_start".$i]):"");
		//echo $betweendates_start."bet"."<BR>";
		$betweendates_end= (isset($HTTP_POST_VARS["hidbetweendates_end".$i])?quote_smart($HTTP_POST_VARS["hidbetweendates_end".$i]):"");
		//echo $betweendates_end."bet"."<BR>";
		$chkapproved= (isset($HTTP_POST_VARS["chkapproved".$i])?quote_smart($HTTP_POST_VARS["chkapproved".$i]):"");
		
		if($chkapproved=="1"){
				$chkapproved="Y";
		}else{
				$chkapproved="N";
		}
		//echo $chkapproved."<BR>";
		if ($netAmt!=""){
				$approvedstatusdate=func_get_current_date_time();
				if($frequency=="M" && $monthstartdates!="" && $monthenddates!="" ){
					$startdate=$monthstartdates;
					$enddate=$monthenddates;
				}//if loop for month freqz ens here 
				if($frequency=="D" && $startdates_D!="" ){
					$startdate=$startdates_D;
					$enddate=$startdates_D;
				}//if loop for daily freqz ens here 
				if($frequency=="W" && $startdates_W!="" && $enddates_W!="" ){
					$startdate=$startdates_W;
					$enddate=$enddates_W;
				}//if loop for weekly freqz ens here 
				// functions returns noof records exsists in the table
				//*****************************************************//
				$checkexsists=func_isexsists($companyid,$startdate,$enddate,$cnn_cs);
				//echo $checkexsists;
				if($checkexsists>0){
					// functions returns approved status
					//***********************************//
					$approvedstatus=func_update_approval($companyid,$startdate,$enddate,$chkapproved,$checkexsists,$cnn_cs);
				}
				// inserted only if there is no exsisting records
				//************************************************//
				if($checkexsists==0){
				$qrt_insert_details = "insert into cs_invoicedetails (userId ,subtotalAmt,totalAmt,approvedAmt ,declinedAmt,creditAmt,chargeBack,credit,discount,transactionFee,voiceAuthorisation_fee,reserveFee,totalDeductions ,miscAdd,miscSub,netAmount,adminApproved,approvedstatusdate,startdate,enddate,frequency) values($companyid,$subtotalamt,$transAmt,$Approvedamt,$declinedamt,$cancelamt,$chargeback,$credit,$discount,$transFees,$voiceAuthFees,$reserve,$totalDeduction,$miscAdd,$miscSub,$netAmt,'$chkapproved','$approvedstatusdate','$startdate','$enddate','$frequency')"; 
								//echo $qrt_insert_details;
								if(!($show_sql =mysql_query($qrt_insert_details,$cnn_cs)))
								{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

								}
								$insert=1;
								//reference no updatation
								//***********************//
								$invoiceid = mysql_insert_id(); 
								$invoice_reference_no=func_Trans_Ref_No($invoiceid );
								$updateSuccess=func_update_single_field('cs_invoicedetails','invoiceReferenceNumber',$invoice_reference_no,'invoiceId',$invoiceid,$cnn_cs);
			       }//checkexsists loop ends here
				   $appstatus=$appstatus."}".$approvedstatus;	
		}//if loop ends here (netAmt)
		
	}//for loop ends here
//echo $appstatus."<BR>";	
if ($insert==1){
	$msgtodisplay_1="Invoice Summary added successfully";
	header("location:transactionupdateview.php?msg_1=$msgtodisplay_1 & msgid=$companyid");
	exit();		
}		
if ($approvedstatus!=""){
	header("location:transactionupdateview.php?msg=$appstatus & msgid=$companyid");
			
}	
function func_isexsists($companyid,$betweendates_start,$betweendates_end,$cnn_cs){
$exsist=0;
$qry_select="select  adminApproved,invoiceReferenceNumber,invoiceId from cs_invoicedetails where userId=$companyid and (startdate='$betweendates_start' and enddate='$betweendates_end')";
		//echo $qry_select."<BR>";
		if(!($show_sql =mysql_query($qry_select,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}else{
			$is_exsist=mysql_num_rows($show_sql);
			//echo $is_exsist."<BR>";
			if($is_exsist>0){
				$exsist=$is_exsist;
			}//if is exsist >0 loop closed
		}//else loop for QRY exe closed
	return $exsist;
}		

function func_update_approval($companyid,$betweendates_start,$betweendates_end,$chkapproved,$checkexsists,$cnn_cs){
$updateapprove=0;
$updatetrue=0;
$updatefalse=0;
$str_refno_update="";
$str_refno="";
$alreadyapproved=0;
$str_refno_app="";
$qry_select="select  adminApproved,invoiceReferenceNumber,invoiceId from cs_invoicedetails where userId=$companyid and (startdate='$betweendates_start' and enddate='$betweendates_end')";
		//echo $qry_select."<BR>";
		if(!($show_sql =mysql_query($qry_select,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	for($count=0;$count<$checkexsists;$count++){
			$show_select_details = mysql_fetch_array($show_sql);
			$adapproved=$show_select_details[0];
			$invoiceRefno=$show_select_details[1];
			$invoiceno=$show_select_details[2];
			//echo $adapproved."<BR>";
			//echo $invoiceRefno."<BR>";
			if($adapproved=="N"){
				if($chkapproved=="Y"){
					$updateapprove=func_update_single_field('cs_invoicedetails','adminApproved',$chkapproved,'invoiceId',$invoiceno,$cnn_cs);
					if ($updateapprove==1){
						$updatetrue=1;
						$str_refno_update=$invoiceRefno."-".$str_refno_update;
					}
				}else{
						$updatefalse=1;
						$str_refno=$invoiceRefno."-".$str_refno;
						}
				}elseif($adapproved=="Y"){
						if($chkapproved=="Y"){
							$alreadyapproved=1;
							$str_refno_app=$invoiceRefno."-".$str_refno_app;
						}else{
								if($chkapproved=="N"){
								$updatefalse=1;
								$str_refno=$invoiceRefno."-".$str_refno;
							    }
						}
						
				}
		}//for loop
	if($updatetrue==1){
		 $str_refno_update="Invoice no: $str_refno_update updated";
		return $str_refno_update;
	}if($updatefalse==1){
		 $str_refno="Invoice no :$str_refno aready exsists";
		return $str_refno;
	}
	if($alreadyapproved==1){
		 $str_refno_app="Invoice no: $str_refno_app aready approved";
		return $str_refno_app;
	}
	

}
		
?>