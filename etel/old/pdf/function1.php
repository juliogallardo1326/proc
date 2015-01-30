<?php
	//******************** Function for displaying the ledger details ***************
function func_show_ledger_details_tsr_callcenter($str_query,$cnn_connection,$crorcq,$str_type,$str_company_id,$qrt_voice_select,$qrt_user_select)
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
$i_amt_per_order = 0;
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
if(!($show_user_sql = mysql_query($qrt_user_select,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
if(mysql_num_rows($show_user_sql)>0)
{
	$i_amt_per_order = mysql_result($show_user_sql,0,0);
	$trans_voicefee = mysql_result($show_user_sql,0,1);
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
	//	print($str_query);
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
		 //$trans_amount = $showval[13];
		 $trans_amount = $i_amt_per_order;
		 //$trans_voicefee = $showval[14];
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

	$charge_back_amount = ($charge_back_count * $i_charge_back);
	$credit_amount = ($credit_count * $i_credit);
	$decline_deduction = ($declined * $i_transaction_fee);
	//$nopass_deduction = ($nonpassed * $trans_voicefee);
	/*$i_discount_amt = (($i_discount_rate * $approvedAmt) / 100);
	$i_transaction_amt = ($i_transaction_fee * $totalnum);
	$i_reserve_amt = (($i_reserve * ($approvedAmt + $declinedAmt)) / 100);*/
	$i_discount_amt = 0;
	$i_transaction_amt = 0;
	$i_reserve_amt = 0;
	$i_voiceauth_amt = ($voice_authcount * ($trans_voicefee * 2));
	$i_total_voice_amt = ($voice_count_uploads * $trans_voicefee);
	$deducted_amt =  ($charge_back_amount + $credit_amount + $i_discount_amt + $i_transaction_amt + $i_reserve_amt + $i_voiceauth_amt+$decline_deduction);
	$i_net_amt = ($totamount - $deducted_amt);
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
		   //$voiceauthPre=number_format(($i_total_voice_amt/$totamount)*100,2); 
		   $voiceauthPre="";
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
			   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
			   print("<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>");	
			   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
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
		   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
		   print("<td align='center' class='cl1'><span class='subhd'>Check Details</span></td>");	
		   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
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
	  
	   print("<tr height='30' bgcolor='#CCCCCC'><td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>");	
	   print("<td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
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
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$approved</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($approvedAmt,2)."</font></td>");
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
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".$voiceauthPre."</font></td></tr>");
	   
	   print("<tr bgcolor='#CCCCCC'><td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Deducted Amount</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td></td></tr>");

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($charge_back_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_charge_back,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_credit,2)."</font></td></tr>");	   

	   /*print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Discount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_discount_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_discount_rate,2)."%</font></td></tr>");*/	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Declines</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($decline_deduction,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_transaction_fee,2)."</font></td></tr>");	   

	   /*print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>No pass</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nopass_deduction,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($trans_voicefee,2)."</font></td></tr>");	*/   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Voice Authorization</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_voiceauth_amt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($trans_voicefee,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($i_net_amt,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;</font></td></tr>");	   

	   print("</table>");
	   print("</td>");
	   print("</tr></table>");
	}
	else
	{
		print("<br>");
		if($str_company_id != "A")
		{
			print("<P align='center'><font face='verdana' size='2'><B>".func_get_value_of_field($cnn_connection,"cs_companydetails","companyname","userid",$str_company_id)."</B></font></p>");
		}
		print("<center><font face='verdana' size='1' ><B>No transactions for this period</B></font><center><br>");
	}
	  
}


//*************************************************************
// function to generate random alpha numeric value
//*************************************************************
function get_rand_id($length)
{
  if($length>0) 
  { 
  $rand_id="";
   for($i=1; $i<=$length; $i++)
   {
   mt_srand((double)microtime() * 1000000);
   $num = mt_rand(1,31);
   $rand_id .= assign_rand_value($num);
   }
  }
return $rand_id;
} 
function assign_rand_value($num)
{
// accepts 1 - 31
  switch($num)
  {
    case "1":
     $rand_value = "A";
    break;
    case "2":
     $rand_value = "B";
    break;
    case "3":
     $rand_value = "C";
    break;
    case "4":
     $rand_value = "D";
    break;
    case "5":
     $rand_value = "E";
    break;
    case "6":
     $rand_value = "F";
    break;
    case "7":
     $rand_value = "G";
    break;
    case "8":
     $rand_value = "H";
    break;
    case "9":
     $rand_value = "J";
    break;
    case "10":
     $rand_value = "K";
    break;
    case "11":
     $rand_value = "L";
    break;
    case "12":
     $rand_value = "M";
    break;
    case "13":
     $rand_value = "N";
    break;
    case "14":
     $rand_value = "P";
    break;
    case "15":
     $rand_value = "R";
    break;
    case "16":
     $rand_value = "S";
    break;
    case "17":
     $rand_value = "T";
    break;
    case "18":
     $rand_value = "U";
    break;
    case "19":
     $rand_value = "V";
    break;
    case "20":
     $rand_value = "W";
    break;
    case "21":
     $rand_value = "X";
    break;
    case "22":
     $rand_value = "Y";
    break;
    case "23":
     $rand_value = "Z";
    break;
    case "24":
     $rand_value = "2";
    break;
    case "25":
     $rand_value = "3";
    break;
    case "26":
     $rand_value = "4";
    break;
    case "27":
     $rand_value = "5";
    break;
    case "28":
     $rand_value = "6";
    break;
    case "29":
     $rand_value = "7";
    break;
    case "30":
     $rand_value = "8";
    break;
    case "31":
     $rand_value = "9";
    break;
  }
return $rand_value;
}

	//******************** Function for displaying the invoice details ***************
function func_show_invoicedetails_details($str_query,$cnn_connection,$crorcq,$str_type,$str_company_id,$qrt_voice_select)
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
$credit_transaction_amt=0;
$chargeback_transaction_amt=0;
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
	//	print($str_query);
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
		//cheque calculation is for mastercard
		if ($trans_cardtype =="C") {
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
					$chargeback_transaction_amt = $chargeback_transaction_amt + $trans_amount;
				} else {
					$credit_count = $credit_count + 1;
					$credit_transaction_amt = $credit_transaction_amt + $trans_amount;
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
					$chargeback_transaction_amt = $chargeback_transaction_amt + $trans_amount;
				} else {
					$credit_count = $credit_count + 1;
					$credit_transaction_amt = $credit_transaction_amt + $trans_amount;
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

	$charge_back_amount = ($charge_back_count * $i_charge_back);
	$credit_amount = ($credit_count * $i_credit);
	$i_discount_amt = (($i_discount_rate * $approvedAmt) / 100);
	$i_transaction_amt = ($i_transaction_fee * $totalnum);
	$i_reserve_amt = (($i_reserve * ($approvedAmt + $declinedAmt)) / 100);
	$i_voiceauth_amt = ($voice_authcount * ($trans_voicefee * 2));
	$i_total_voice_amt = ($voice_count_uploads * $trans_voicefee);
	$deducted_amt =  ($charge_back_amount + $credit_amount + $i_discount_amt + $i_transaction_amt + $i_reserve_amt + $i_voiceauth_amt);
	$i_net_amt = ($totamount - $deducted_amt);
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
		   $chargeback_transaction_per = number_format(($chargeback_transaction_amt/$totamount)*100,2); 
		   $credit_transaction_per = number_format(($credit_transaction_amt/$totamount)*100,2); 
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
			   print("<P align='center'><font face='verdana' size='2'><B>Visa Card Summary</span><br>");
			   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#E0E0E0'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#99CCFF'>");
			   print("<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>");	
			   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
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
		   print("<P align='center'><font face='verdana' size='2'><B>MasterCard Summary</span><br>");
		   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#E0E0E0'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#99CCFF'>");
		   print("<td align='center' class='cl1'><span class='subhd'>Check Details</span></td>");	
		   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
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
		   print("</td></tr>");
		}
	   print("<tr><td $valgg  valign='top'><br>");
	   print("<P align='center'><font face='verdana' size='2'><B>Deductions</span><br>");
   	   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#E0E0E0'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#99CCFF'>");
	   print("<td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Deducted Amount</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td></td></tr>");

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($charge_back_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_charge_back,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_credit,2)."</font></td></tr>");	   

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

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($i_net_amt,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_net_Per)."</font>");	   
	   print("</td></tr></table>");
	   print("</td>");
	   
	   print("<td valign='top'><br>");
	   print("<P align='center'><font face='verdana' size='2'><B>Total Summary</span><br>");
	   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#E0E0E0'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>");
	  
	   print("<tr height='30' bgcolor='#99CCFF'><td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>");	
	   print("<td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
	   print("<td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");
	  
  	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending</span></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$pending</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($pendingAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($pendingPer,2)."</font></td></tr>");
	   
	   /*print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$passed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedPer,2)."</font></td></tr>");

	   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$nonpassed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedPer,2)."</font></td></tr>");
		*/
	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$approved</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($approvedAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($approvedPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$declined</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedPer,2)."</font></td></tr>");

	   /*print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$canceled</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledPer,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Voice Upload</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$voice_count_uploads</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_total_voice_amt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($voiceauthPre,2)."</font></td></tr>");
	   */	   
	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Credits</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$credit_count</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_transaction_amt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_transaction_per,2)."</font></td></tr>");

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Chargeback</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$charge_back_count</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chargeback_transaction_amt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chargeback_transaction_per,2)."</font></td></tr>");
	   
	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'><b>&nbsp;$totalnum</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($totamount,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($cardPer,2)."</font>");
	   print("</td></tr></table>"); 
	   
	   print("</td>");
	   print("</tr></table>");
	}
	else
	{
		print("<br>");
		print("<center><font face='verdana' size='1' ><B>No transactions for this period</B></font><center><br>");
	}
	  
}

	//******************** Function for displaying the reseller ledger details ***************
function func_show_reseller_ledger_details($str_query,$cnn_connection,$crorcq,$str_type,$str_company_id)
{
$strReturn = "";
$pending =0;
$pendingAmt=0;
$pendingPer=0;
$approved=0;
$approvedAmt=0;
$approvedPer=0;
//$declined=0;
//$declinedAmt=0;
//$declinedPer=0;
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
//$chequedeclined=0;
//$chequedeclinedAmt=0;
//$chequedeclinedPer=0;
$creditpending=0;
$creditpendingAmt=0;
$creditpendingPer=0;
$creditapproved=0;
$creditapprovedAmt=0;
$creditapprovedPer=0;
//$creditdeclined=0;
//$creditdeclinedAmt=0;
//$creditdeclinedPer=0;
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
//$trans_voicefee = 0;
$charge_back_amount =0;
$charge_back_count = 0;
$str_companyname = "";
$i_charge_back = "";
$cancel_reason ="";
$i_credit = "";
//$i_discount_rate = "";
//$i_transaction_fee = "";
//$i_reserve = "";
///$voice_authcount =0;
//$i_voiceauth_amt = 0;
//$chequenonpass =0;
//$chequenonpassAmt = 0;
//$chequepass =0;
//$chequepassAmt = 0;
//$creditnonpass =0;
//$creditnonpassAmt = 0;
//$creditpass =0;
//$creditpassAmt = 0;
//$passedPer=0;
//$nonpassedPer=0;
//$chequepassedPer=0;
//$chequenonpassPer=0;
//$creditpassedPer=0;
//$creditnonpassPer=0;
//$passed=0;
//$passedAmt=0;
//$nonpassed=0;
//$nonpassedAmt=0;
//$voice_count_uploads=0;
$i_net_Per="";
$reseller_fee = 0;
$reseller_check_fee = 0;
$reseller_credit_fee = 0;
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
/*if(!($show_voice_sql = mysql_query($qrt_voice_select,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}*/

if(!($show_sql = mysql_query($str_query,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
	/*if(mysql_num_rows($show_voice_sql)>0)
	{
		$voice_count_uploads = mysql_result($show_voice_sql,0,0);
	}*/
	// print $voice_count_uploads;
	//	print($str_query);
	while($showval = mysql_fetch_array($show_sql)) {
		 $i=$i+1;
		 $trans_type = $showval[0];
		 $trans_status = $showval[1];
		 $trans_cancelstatus = $showval[2];
		 $trans_companyName = $showval[3];
		 $i_charge_back = $showval[4];
		 $i_credit = $showval[5];
		 //$i_discount_rate = $showval[6];
		 //$i_transaction_fee = $showval[7];
		 //$i_reserve = $showval[8];
		 $trans_accounttype = $showval[9];
		 $trans_cardtype =  $showval[10];
		 $cancel_reason = $showval[11];
		 $trans_passstatus = $showval[12];
		 $trans_amount = $showval[13];
		 //$trans_voicefee = $showval[14];
		 $reseller_fee = $showval[15];

		// Check and credit card calculation.
		
		if ($trans_type =="C") {
			if($trans_cancelstatus =="N") {
				if($trans_passstatus !="PE") {
					/*if($trans_passstatus == "NP" && $trans_status =="P") {
						$chequenonpass = $chequenonpass + 1;
						$chequenonpassAmt = $chequenonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$chequepass = $chequepass + 1;
						$chequepassAmt = $chequepassAmt + $trans_amount;
					}*/
					if($trans_status =="A")	{
						$totalnum = $totalnum + 1;
						$cheque = $cheque + 1;
						$chequeAmt = $chequeAmt + $trans_amount;
						$chequeapproved = $chequeapproved + 1;
						$chequeapprovedAmt = $chequeapprovedAmt + $trans_amount;
					}
					/*if($trans_status =="D")	{
						$chequedeclined = $chequedeclined + 1;
						$chequedeclinedAmt = $chequedeclinedAmt + $trans_amount;
					}
					$voice_authcount = $voice_authcount + 1;*/
				} else {
					$totalnum = $totalnum + 1;
					$cheque = $cheque + 1;
					$chequeAmt = $chequeAmt + $trans_amount;
					$chequepending = $chequepending + 1;
					$chequependingAmt = $chequependingAmt + $trans_amount;
				}
			}else {
				//$voice_authcount = $voice_authcount + 1;
				$totalnum = $totalnum + 1;
				$cheque = $cheque + 1;
				$chequeAmt = $chequeAmt + $trans_amount;
				$chequecanceled = $chequecanceled + 1;
				$chequecanceledAmt = $chequecanceledAmt + $trans_amount;
				if($cancel_reason == "Chargeback") {
					$charge_back_count = $charge_back_count + 1;
				} else {
					$credit_count = $credit_count + 1;
				}
			}
		} else {
			if($trans_cancelstatus =="N") {
				if($trans_passstatus !="PE") {
					/*if($trans_passstatus == "NP" && $trans_status =="P") {
						$creditnonpass = $creditnonpass + 1;
						$creditnonpassAmt = $creditnonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$creditpass = $creditpass + 1;
						$creditpassAmt = $creditpassAmt + $trans_amount;
					}*/
					if($trans_status =="A")	{
						$totalnum = $totalnum + 1;
						$creditcard = $creditcard + 1;
						$creditcardAmt = $creditcardAmt + $trans_amount;
						$creditapproved = $creditapproved + 1;
						$creditapprovedAmt = $creditapprovedAmt + $trans_amount;
					}
					/*if($trans_status =="D")	{
						$creditdeclined = $creditdeclined + 1;
						$creditdeclinedAmt = $creditdeclinedAmt + $trans_amount;
					}
					$voice_authcount = $voice_authcount + 1;*/
				} else {
					$totalnum = $totalnum + 1;
					$creditcard = $creditcard + 1;
					$creditcardAmt = $creditcardAmt + $trans_amount;
					$creditpending = $creditpending + 1;
					$creditpendingAmt = $creditpendingAmt + $trans_amount;
				}
			}else {
				//$voice_authcount = $voice_authcount + 1;
				$totalnum = $totalnum + 1;
				$creditcard = $creditcard + 1;
				$creditcardAmt = $creditcardAmt + $trans_amount;
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
	//$passed = $chequepass + $creditpass;
	//$passedAmt = $chequepassAmt + $creditpassAmt;
	//$nonpassed = $chequenonpass + $creditnonpass;
	//$nonpassedAmt = $chequenonpassAmt + $creditnonpassAmt;
	$approved= $chequeapproved + $creditapproved;
	$approvedAmt= $chequeapprovedAmt + $creditapprovedAmt;
	//$declined= $chequedeclined + $creditdeclined;
	//$declinedAmt= $chequedeclinedAmt + $creditdeclinedAmt;
	$canceled=$chequecanceled + $creditcanceled;
	$canceledAmt=$chequecanceledAmt + $creditcanceledAmt;
	$totamount = $creditcardAmt + $chequeAmt ;

	$charge_back_amount = ($charge_back_count * $i_charge_back);
	$credit_amount = ($credit_count * $i_credit);
	//$i_discount_amt = (($i_discount_rate * $approvedAmt) / 100);
	//$i_transaction_amt = ($i_transaction_fee * $totalnum);
	//$i_reserve_amt = (($i_reserve * ($approvedAmt + $declinedAmt)) / 100);
	//$i_voiceauth_amt = ($voice_authcount * ($trans_voicefee * 2));
	//$i_total_voice_amt = ($voice_count_uploads * $trans_voicefee);
	//$deducted_amt =  ($charge_back_amount + $credit_amount + $i_discount_amt + $i_transaction_amt + $i_reserve_amt + $i_voiceauth_amt);
	$reseller_check_fee = number_format($reseller_fee * $chequeapproved);
	$reseller_credit_fee = number_format($reseller_fee * $creditapproved);
	$deducted_amt =  ($charge_back_amount + $credit_amount );
	//$i_net_amt = ($totamount - $deducted_amt);
	$i_net_amt = ($reseller_check_fee + $reseller_credit_fee - $deducted_amt);
//	$i_net_Per =  (($totamount - $deducted_amt)/$totamount);
		if($chequeAmt!=0) {
		   $chequependingPer=number_format(($chequependingAmt/$chequeAmt)*100,2);
		   //$chequepassedPer=number_format(($chequepassAmt/$chequeAmt)*100,2);
		   //$chequenonpassPer=number_format(($chequenonpassAmt/$chequeAmt)*100,2);
		   $chequeapprovedPer=number_format(($chequeapprovedAmt/$chequeAmt)*100,2);
		   //$chequedeclinedPer=number_format(($chequedeclinedAmt/$chequeAmt)*100,2);
		   $chequecanceledPer=number_format(($chequecanceledAmt/$chequeAmt)*100,2);
		   //$chequePer =number_format($chequedeclinedPer + $chequeapprovedPer + $chequependingPer+$chequecanceledPer+$chequenonpassPer+$chequepassedPer,2);
		   $chequePer =number_format($chequeapprovedPer + $chequependingPer + $chequecanceledPer,2);
		}
		if($creditcardAmt !=0) {
		   $creditpendingPer=number_format(($creditpendingAmt/$creditcardAmt)*100,2);
		   //$creditpassedPer=number_format(($creditpassAmt/$creditcardAmt)*100,2);
		   //$creditnonpassPer=number_format(($creditnonpassAmt/$creditcardAmt)*100,2);
		   $creditapprovedPer=number_format(($creditapprovedAmt/$creditcardAmt)*100,2);
		   //$creditdeclinedPer=number_format(($creditdeclinedAmt/$creditcardAmt)*100,2);
		   $creditcanceledPer=number_format(($creditcanceledAmt/$creditcardAmt)*100,2);
		   //$creditcardPer =number_format($creditdeclinedPer + $creditapprovedPer + $creditpendingPer+$creditcanceledPer+ $creditpassedPer+ $creditnonpassPer,2);
		   $creditcardPer =number_format($creditapprovedPer + $creditpendingPer + $creditcanceledPer,2);
		}
		if($totamount !=0) {
		   $pendingPer=number_format(($pendingAmt/$totamount)*100,2);
		   //$passedPer=number_format(($passedAmt/$totamount)*100,2);
		   //$nonpassedPer=number_format(($nonpassedAmt/$totamount)*100,2);
		   $approvedPer=number_format(($approvedAmt/$totamount)*100,2);
		   //$declinedPer=number_format(($declinedAmt/$totamount)*100,2);
		   $canceledPer=number_format(($canceledAmt/$totamount)*100,2);
		   //$voiceauthPre=number_format(($i_total_voice_amt/$totamount)*100,2); 
		   //$cardPer =number_format($declinedPer + $approvedPer + $pendingPer+$canceledPer+$passedPer+$nonpassedPer,2);
		   $cardPer =number_format($approvedPer + $pendingPer + $canceledPer,2);
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
			   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
			   print("<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>");	
			   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
			   print("<td align='right' class='bottom'><span class='subhd'>Percentage (%)</span></td></tr><tr>");
			   print("<td align='left' class='cl1'><font face='verdana' size='1'><b>Pending</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditpending</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpendingAmt,2)."</font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($creditpendingPer,2)."</font></td>");

			   /*print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditnonpass</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditnonpassAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditnonpassPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditpass</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpassAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditpassedPer,2)."</font></td>");*/

			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditapproved</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditapprovedAmt,2)."</font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($creditapprovedPer,2)."</font></td></tr>");

			   /*print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditdeclined</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditdeclinedAmt,2)."</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditdeclinedPer,2)."</font></td></tr>");*/

			   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditcanceled</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($creditcanceledAmt,2)."</font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($creditcanceledPer,2)."</font></td>");
			   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'><b>&nbsp;$creditcard</b></font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($creditcardAmt,2)."</b></font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format(round($creditcardPer),2)."</font></td></tr>");	   

			   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Reseller Fee</b></font></td>");
			   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$creditapproved</font></td>");
			   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($reseller_fee * $creditapproved,2)."</font></td>");
			   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;</font></td></tr>");
			   print("</table>");
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
		   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
		   print("<td align='center' class='cl1'><span class='subhd'>Check Details</span></td>");	
		   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
		   print("<td align='right' class='bottom'><span class='subhd'>Percentage (%)</span></td></tr><tr>");
		   print("<td align='left' class='cl1'><font face='verdana' size='1'><b>Pending</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequepending</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequependingAmt,2)."</font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($chequependingPer,2)."</font></td>");

		   /*print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequenonpass</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequenonpassAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequenonpassPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequepass</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequepassAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequepassedPer,2)."</font></td>");*/

		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequeapproved</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequeapprovedAmt,2)."</font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($chequeapprovedPer,2)."</font></td></tr>");

		   /*print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequedeclined</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequedeclinedAmt,2)."</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequedeclinedPer,2)."</font></td></tr>");*/

		   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequecanceled</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($chequecanceledAmt,2)."</font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($chequecanceledPer,2)."</font></td>");
		   print("</tr><tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'><b>&nbsp;$cheque</b></font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($chequeAmt,2)."</b></font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;".number_format(round($chequePer),2)."</font></td></tr>");	   

		   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Reseller Fee</b></font></td>");
		   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$chequeapproved</font></td>");
		   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($reseller_fee * $chequeapproved,2)."</font></td>");
		   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;</font></td></tr>");
		   print("</table>");
		   print("</td>");

		}
	   print("<td $valgg  valign='top'><br>");
	   print("<P align='center'><font face='verdana' size='2'><B>Total Summary</span><br>");
	   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'>");
	  
	   print("<tr height='30' bgcolor='#CCCCCC'><td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>");	
	   print("<td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount ($)</span></td>");
	   print("<td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");
	  
  	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'><b>&nbsp;$totalnum</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($totamount,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($cardPer,2)."</font></td></tr>");	   

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Pending</span></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$pending</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($pendingAmt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($pendingPer,2)."</font></td></tr>");
	   
	   /*print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Passed</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$passed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($passedPer,2)."</font></td></tr>");

	   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Non Pass</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$nonpassed</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedAmt,2)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($nonpassedPer,2)."</font></td></tr>");*/

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$approved</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($approvedAmt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($approvedPer,2)."</font></td></tr>");

	   /*print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Declined</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;$declined</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedAmt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($declinedPer,2)."</font></td></tr>");*/

	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$canceled</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($canceledAmt,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($canceledPer,2)."</font></td></tr>");

	   /*print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Voice Upload</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'>&nbsp;$voice_count_uploads</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($i_total_voice_amt,2)."</font></td>");
	   print("<td align='right'  class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($voiceauthPre,2)."</font></td></tr>");*/
	   
	   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Reseller Fee</b></font></td>");
	   print("<td align='center' class='cl1'><font face='verdana' size='1'>&nbsp;".($chequeapproved + $creditapproved)."</font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format(($reseller_fee * $chequeapproved + $reseller_fee * $creditapproved),2)."</font></td>");
	   print("<td align='right' class='bottom'><font face='verdana' size='1'>&nbsp;</font></td></tr>");


	   print("<tr bgcolor='#CCCCCC'><td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Deducted Amount</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount per transaction</span></td></td></tr>");

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Charge Back</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($charge_back_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_charge_back,2)."</font></td></tr>");	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Credit</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;".number_format($credit_amount,2)."</font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_credit,2)."</font></td></tr>");	   

	   /*print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Discount</b></font></td>");
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
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($trans_voicefee,2)."</font></td></tr>");*/	   

	   print("<tr><td align='left' class='cl1' colspan='2'><font face='verdana' size='1'><b>Net Amount</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($i_net_amt,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($i_net_Per)."</font></td></tr>");	   

	   print("</table>");
	   print("</td>");
	   print("</tr></table>");
	}
	else
	{
		print("<br>");
		if($str_company_id != "A")
		{
			print("<P align='center'><font face='verdana' size='2'><B>".func_get_value_of_field($cnn_connection,"cs_companydetails","companyname","userid",$str_company_id)."</B></font></p>");
		}
		print("<center><font face='verdana' size='1' ><B>No transactions for this period</B></font><center><br>");
	}
	  
}

/*************************************************************************************/

// Extracts content from XML tag

function GetElementByName ($xml, $start, $end) {

   global $pos;
   $startpos = strpos($xml, $start);
   if ($startpos === false) {
       return false;
   }
   $endpos = strpos($xml, $end);
   $endpos = $endpos+strlen($end);   
   $pos = $endpos;
   $endpos = $endpos-$startpos;
   $endpos = $endpos - strlen($end);
   $tag = substr ($xml, $startpos, $endpos);
   $tag = substr ($tag, strlen($start));

   return $tag;

}


/************************Volpay Integration Process ***********************************/

function func_volpaybank_integration_result($xml_value) 
{
	// Uncomment below for live
	$output_url = "http://62.209.40.97/supermaxxx/gateway_v2.php";
	// start output buffer to catch curl return data
	ob_start();

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS,"xml=$xml_value");
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
	
	// output some of the variables
	return($process_result);
//	print $process_result;
}		

// function for updating the approval status for credit card transactions

function func_update_approval_status($cnn_cs, $trans_id, $approval_status, $pass_status, $decline_reason) {
	$strCurrentDateTime = func_get_current_date();
	$str_approval_date = "";
	$str_pass_status = "";
	if ($approval_status == "A") {
		$str_approval_date = "approvaldate = '$strCurrentDateTime',";
	}
	if ($pass_status != "") {
		$str_pass_status = "passStatus = '$pass_status',";
	}
	$qryUpdate = "update cs_transactiondetails set status = '$approval_status', $str_approval_date $str_pass_status declinedReason = '$decline_reason' where transactionId=$trans_id";
	if(!mysql_query($qryUpdate,$cnn_cs)){
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Can not execute approval status update query");
		exit();
	}
}

function func_send_cancel_ecommerce_letter($trans_id) {
	$headers = "";	
	$headers .= "From: Companysetup <sales@etelegate.com>\n";
	$headers .= "X-Sender: Admin Companysetup\n"; 
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "Return-Path: <sales@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$subject = "Cancellation of Transaction";

	$str_qry = "select a.companyname, b.transactionId, b.voiceAuthorizationno, b.name, b.surname, b.address,  b.country, b.state, b.city, b.zipcode, b.CCnumber, b.cvv, b.cardtype, b.amount, b.transactionDate, b.validupto, b.misc, b.ipaddress, b.email from cs_companydetails a, cs_transactiondetails b where a.userId = b.userId and b.transactionId = $trans_id";

	if(!($show_sql_run = mysql_query($str_qry)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$company_name = mysql_result($show_sql_run,0,0);
	$trans_id = mysql_result($show_sql_run,0,1);
	$voiceauth = mysql_result($show_sql_run,0,2);
	$firstname = mysql_result($show_sql_run,0,3);
	$lastname = mysql_result($show_sql_run,0,4);
	$address = mysql_result($show_sql_run,0,5);
	$country = mysql_result($show_sql_run,0,6);
	$state = mysql_result($show_sql_run,0,7);
	$city = mysql_result($show_sql_run,0,8);
	$zipcode = mysql_result($show_sql_run,0,9);
	$number = etelDec(mysql_result($show_sql_run,0,10));
	$cvv2 = mysql_result($show_sql_run,0,11);
	$cardtype = mysql_result($show_sql_run,0,12);
	$amount = mysql_result($show_sql_run,0,13);
	$dateToEnter = mysql_result($show_sql_run,0,14);
	$validupto = mysql_result($show_sql_run,0,15);
	$misc = mysql_result($show_sql_run,0,16);
	$domain1 = mysql_result($show_sql_run,0,17);
	$email = mysql_result($show_sql_run,0,18);

	$typeofcard = "";
	if($cardtype == "Master") 
	{	
		$typeofcard = "Master card Order";
	} 
	else 
	{	
		$typeofcard = "Visa card Order";
	}
	$numLen = strlen($number);
	$frNum = $numLen-4;
	$lastFour = substr($number,$frNum,$numLen);
	$message = "The following transaction of $company_name has been cancelled\r\n\r\n";
	$message .= "Transaction ID : $trans_id \r\n\r\n";
	$message .= "Voice Authorization ID : $voiceauth\r\n\r\n";
	$message .= "Name : $firstname  $lastname\r\n\r\n";
	$message .= "Address : $address\r\n\r\n";
	$message .= "Country : $country\r\n\r\n";
	$message .= "State : $state\r\n\r\n";
	$message .= "City : $city\r\n\r\n";
	$message .= "Zipcode : $zipcode\r\n\r\n";
	$message .= "Credit Card No : $lastFour\r\n\r\n";
	$message .= "CVV2 : $cvv2\r\n\r\n";
	$message .= "Card Type : $typeofcard\r\n\r\n";
	$message .= "Amount : $amount\r\n\r\n";
	$message .= "Transaction Date : $dateToEnter\r\n\r\n";
	$message .= "Expiry Date : $validupto\r\n\r\n";
	$message .= "Misc : $misc\r\n\r\n";
	$message .= "IP Address : $domain1\r\n\r\n";

	if($email !="") 
	{
		mail($email,$subject,$message,$headers);
	}
}

function func_send_transaction_success_mail($trans_id) {
	$headers = "";	
	$headers .= "From: Companysetup <sales@etelegate.com>\n";
	$headers .= "X-Sender: Admin Companysetup\n"; 
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "Return-Path: <sales@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$sender ="sales@etelegate.com";

	$str_qry = "select a.companyname, b.transactionId, b.voiceAuthorizationno, b.name, b.surname, b.address,  b.country, b.state, b.city, b.zipcode, b.CCnumber, b.cvv, b.cardtype, b.amount, b.transactionDate, b.validupto, b.misc, b.ipaddress, a.transaction_type, a.billingdescriptor, b.email, a.send_mail, a.send_ecommercemail, a.email, a.userId from cs_companydetails a, cs_transactiondetails b where a.userId = b.userId and b.transactionId = $trans_id";

	if(!($show_sql_run = mysql_query($str_qry)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$company_name = mysql_result($show_sql_run,0,0);
	$trans_id = mysql_result($show_sql_run,0,1);
	$voiceauth = mysql_result($show_sql_run,0,2);
	$firstname = mysql_result($show_sql_run,0,3);
	$lastname = mysql_result($show_sql_run,0,4);
	$address = mysql_result($show_sql_run,0,5);
	$country = mysql_result($show_sql_run,0,6);
	$state = mysql_result($show_sql_run,0,7);
	$city = mysql_result($show_sql_run,0,8);
	$zipcode = mysql_result($show_sql_run,0,9);
	$number = etelDec(mysql_result($show_sql_run,0,10));
	$cvv2 = mysql_result($show_sql_run,0,11);
	$cardtype = mysql_result($show_sql_run,0,12);
	$amount = mysql_result($show_sql_run,0,13);
	$dateToEnter = mysql_result($show_sql_run,0,14);
	$validupto = mysql_result($show_sql_run,0,15);
	$misc = mysql_result($show_sql_run,0,16);
	$domain1 = mysql_result($show_sql_run,0,17);
	$transaction_type = mysql_result($show_sql_run,0,18);
	$billingdescriptor = mysql_result($show_sql_run,0,19);
	$email = mysql_result($show_sql_run,0,20);
	$send_mails = mysql_result($show_sql_run,0,21);
	$send_ecommercemail = mysql_result($show_sql_run,0,22);
	$fromaddress = mysql_result($show_sql_run,0,23);
	$company_id = mysql_result($show_sql_run,0,24);
	$typeofcard = "";
	if($cardtype == "Master") 
	{	
		$typeofcard = "Master card Order";
	} 
	else 
	{	
		$typeofcard = "Visa card Order";
	}
	$subject = "Transaction Confirmation of ".$firstname." ".$lastname;
	$numLen = strlen($number);
	$frNum = $numLen-4;
	$lastFour = substr($number,$frNum,$numLen);
	$message = "Transaction details of $company_name\r\n\r\n";
	$message .= "Transaction ID : $trans_id \r\n\r\n";
	$message .= "Voice Authorization ID : $voiceauth\r\n\r\n";
	$message .= "Name : $firstname  $lastname\r\n\r\n";
	$message .= "Address : $address\r\n\r\n";
	$message .= "Country : $country\r\n\r\n";
	$message .= "State : $state\r\n\r\n";
	$message .= "City : $city\r\n\r\n";
	$message .= "Zipcode : $zipcode\r\n\r\n";
	$message .= "Credit Card No : $lastFour\r\n\r\n";
	$message .= "CVV2 : $cvv2\r\n\r\n";
	$message .= "Card Type : $typeofcard\r\n\r\n";
	$message .= "Amount : $amount\r\n\r\n";
	$message .= "Date : $dateToEnter\r\n\r\n";
	$message .= "Expiry Date : $validupto\r\n\r\n";
	$message .= "Misc : $misc\r\n\r\n";
	$message .= "IP Address : $domain1\r\n\r\n";
	$message .= "Your credit card  has been charged the above amount TODAY\r\n";
	if($send_mails ==1) {
		//$ecommerce_letter = func_get_value_of_field($cnn_cs,"cs_registrationmail","mail_sent","mail_id",2);
		$ecommerce_letter = 1;
		if($email !="" && $transaction_type !="tele" && $ecommerce_letter==1 && $send_ecommercemail == 1) {
				$str_email_content = func_getecommerce_mailbody();
				$str_email_content = str_replace("[customername]", $firstname." ".$lastname, $str_email_content );
				$str_email_content = str_replace("[companyname]", $company_name, $str_email_content );
				$str_email_content = str_replace("[amount]", $amount, $str_email_content );
				$str_email_content = str_replace("[billingdescriptor]", $billingdescriptor, $str_email_content );
				$str_email_content = str_replace("[companyemailaddress]", $fromaddress, $str_email_content );
				$str_email_content = str_replace("[chargeamount]", $amount, $str_email_content );
				$str_email_content = str_replace("[cardtype]", $typeofcard, $str_email_content );
				$str_email_content = str_replace("[name]", $firstname, $str_email_content );
				$str_email_content = str_replace("[address]", $address, $str_email_content );
				$str_email_content = str_replace("[city]", $city, $str_email_content );
				$str_email_content = str_replace("[state]", $state, $str_email_content );
				$str_email_content = str_replace("[zip]", $zipcode, $str_email_content );
				$str_email_content = str_replace("[ccnumber]", substr($number,strlen($number)-4,4) , $str_email_content);
			//	echo $str_email_content;
				$b_mail = func_send_mail($sender,$email,"Ecommerce Transaction Letter",$str_email_content);
		}

		if($email !="") 
		{
			mail($email,$subject,$message,$headers);
		}
		func_sendMail($company_id,$subject,$message,$headers);
	}
}

function func_send_transaction_failure_mail($trans_id, $decline_reason) {
	$headers = "";	
	$headers .= "From: Companysetup <sales@etelegate.com>\n";
	$headers .= "X-Sender: Admin Companysetup\n"; 
	$headers .= "X-Mailer: PHP\n"; // mailer
	$headers .= "X-Priority: 1\n"; // Urgent message!
	$headers .= "Return-Path: <sales@etelegate.com>\n";  // Return path for errors
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Mime type
	$subject = "Declination of Transaction";

	$str_qry = "select a.companyname, b.transactionId, b.voiceAuthorizationno, b.name, b.surname, b.address,  b.country, b.state, b.city, b.zipcode, b.CCnumber, b.cvv, b.cardtype, b.amount, b.transactionDate, b.validupto, b.misc, b.ipaddress, b.email from cs_companydetails a, cs_transactiondetails b where a.userId = b.userId and b.transactionId = $trans_id";

	if(!($show_sql_run = mysql_query($str_qry)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$company_name = mysql_result($show_sql_run,0,0);
	$trans_id = mysql_result($show_sql_run,0,1);
	$voiceauth = mysql_result($show_sql_run,0,2);
	$firstname = mysql_result($show_sql_run,0,3);
	$lastname = mysql_result($show_sql_run,0,4);
	$address = mysql_result($show_sql_run,0,5);
	$country = mysql_result($show_sql_run,0,6);
	$state = mysql_result($show_sql_run,0,7);
	$city = mysql_result($show_sql_run,0,8);
	$zipcode = mysql_result($show_sql_run,0,9);
	$number = etelDec(mysql_result($show_sql_run,0,10));
	$cvv2 = mysql_result($show_sql_run,0,11);
	$cardtype = mysql_result($show_sql_run,0,12);
	$amount = mysql_result($show_sql_run,0,13);
	$dateToEnter = mysql_result($show_sql_run,0,14);
	$validupto = mysql_result($show_sql_run,0,15);
	$misc = mysql_result($show_sql_run,0,16);
	$domain1 = mysql_result($show_sql_run,0,17);
	$email = mysql_result($show_sql_run,0,18);
	$typeofcard = "";
	if($cardtype == "Master") 
	{	
		$typeofcard = "Master card Order";
	} 
	else 
	{	
		$typeofcard = "Visa card Order";
	}
	$numLen = strlen($number);
	$frNum = $numLen-4;
	$lastFour = substr($number,$frNum,$numLen);
	$message = "The following transaction of $company_name has been declined due to the following reason - $decline_reason\r\n\r\n";
	$message .= "Transaction ID : $trans_id \r\n\r\n";
	$message .= "Voice Authorization ID : $voiceauth\r\n\r\n";
	$message .= "Name : $firstname  $lastname\r\n\r\n";
	$message .= "Address : $address\r\n\r\n";
	$message .= "Country : $country\r\n\r\n";
	$message .= "State : $state\r\n\r\n";
	$message .= "City : $city\r\n\r\n";
	$message .= "Zipcode : $zipcode\r\n\r\n";
	$message .= "Credit Card No : $lastFour\r\n\r\n";
	$message .= "CVV2 : $cvv2\r\n\r\n";
	$message .= "Card Type : $typeofcard\r\n\r\n";
	$message .= "Amount : $amount\r\n\r\n";
	$message .= "Transaction Date : $dateToEnter\r\n\r\n";
	$message .= "Expiry Date : $validupto\r\n\r\n";
	$message .= "Misc : $misc\r\n\r\n";
	$message .= "IP Address : $domain1\r\n\r\n";

	if($email !="") 
	{
		mail($email,$subject,$message,$headers);
	}
}

function func_get_processing_currency($i_company_id) {
	$str_currency = "";
	$sql_currency_type = "Select processing_currency from cs_companydetails where userid=$i_company_id";
	if($rst_trans_show = mysql_query($sql_currency_type)) {
		if(mysql_num_rows($rst_trans_show) != 0) {
			$str_currency = mysql_result($rst_trans_show, 0 , 0);
		}
	}
	if ($str_currency == "") {
		$str_currency = "USD";
	} else if ($str_currency == "EUR") {
		$str_currency = "EURO";
	}
	return $str_currency;
}

	//******************** Function for displaying the ledger details ***************
function func_show_ecommerce_ledger_details($str_query,$cnn_connection,$crorcq,$str_type,$str_company_id)
{
$strReturn = "";
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
$chequeapproved=0;
$chequeapprovedAmt=0;
$chequeapprovedPer=0;
$chequedeclined=0;
$chequedeclinedAmt=0;
$chequedeclinedPer=0;
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
$charge_back_amount =0;
$charge_back_count = 0;
$str_companyname = "";
$i_charge_back = "";
$cancel_reason ="";
$i_credit = "";
$i_discount_rate = "";
$i_transaction_fee = "";
$i_reserve = "";
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

if(!($show_sql = mysql_query($str_query,$cnn_connection))){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
	// print $voice_count_uploads;
	//	print($str_query);
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
		 $trans_amount = $showval[13];
		// Check and credit card calculation.
		if ($trans_type =="C") {
			if($trans_cancelstatus =="N") {
/*				if($trans_passstatus !="PE") {
					if($trans_passstatus == "NP" && $trans_status =="P") {
						$chequenonpass = $chequenonpass + 1;
						$chequenonpassAmt = $chequenonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$chequepass = $chequepass + 1;
						$chequepassAmt = $chequepassAmt + $trans_amount;
					}
*/					
					if($trans_status =="A")	{
						$chequeapproved = $chequeapproved + 1;
						$chequeapprovedAmt = $chequeapprovedAmt + $trans_amount;
					}
					if($trans_status =="D")	{
						$chequedeclined = $chequedeclined + 1;
						$chequedeclinedAmt = $chequedeclinedAmt + $trans_amount;
					}
/*					$voice_authcount = $voice_authcount + 1;
				} else {
					$chequepending = $chequepending + 1;
					$chequependingAmt = $chequependingAmt + $trans_amount;
				}
*/				
			}else {
//				$voice_authcount = $voice_authcount + 1;
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
/*				if($trans_passstatus !="PE") {
					if($trans_passstatus == "NP" && $trans_status =="P") {
						$creditnonpass = $creditnonpass + 1;
						$creditnonpassAmt = $creditnonpassAmt + $trans_amount;
					} elseif($trans_passstatus == "PA" && $trans_status =="P") {
						$creditpass = $creditpass + 1;
						$creditpassAmt = $creditpassAmt + $trans_amount;
					}
*/					
					if($trans_status =="A")	{
						$creditapproved = $creditapproved + 1;
						$creditapprovedAmt = $creditapprovedAmt + $trans_amount;
					}else {
						$creditdeclined = $creditdeclined + 1;
						$creditdeclinedAmt = $creditdeclinedAmt + $trans_amount;
					}
/*					$voice_authcount = $voice_authcount + 1;
				} else {
					$creditpending = $creditpending + 1;
					$creditpendingAmt = $creditpendingAmt + $trans_amount;
				}
*/				
			}else {
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
	
	$approved= $chequeapproved + $creditapproved;
	$approvedAmt= $chequeapprovedAmt + $creditapprovedAmt;
	$declined= $chequedeclined + $creditdeclined;
	$declinedAmt= $chequedeclinedAmt + $creditdeclinedAmt;
	$canceled=$chequecanceled + $creditcanceled;
	$canceledAmt=$chequecanceledAmt + $creditcanceledAmt;
	$totamount = $creditcardAmt + $chequeAmt ;

	$charge_back_amount = ($charge_back_count * $i_charge_back);
	$credit_amount = ($credit_count * $i_credit);
	$i_discount_amt = (($i_discount_rate * $approvedAmt) / 100);
	$i_transaction_amt = ($i_transaction_fee * $i);
	$i_reserve_amt = (($i_reserve * ($approvedAmt)) / 100);
	$deducted_amt =  ($charge_back_amount + $credit_amount + $canceledAmt + $i_discount_amt + $i_transaction_amt + $i_reserve_amt);
	//$i_net_amt = ($totamount - $deducted_amt);
	$i_net_amt = ($approvedAmt - $deducted_amt);
//	$i_net_Per =  (($totamount - $deducted_amt)/$totamount);
		if($chequeAmt!=0) {
		   $chequeapprovedPer=number_format(($chequeapprovedAmt/$chequeAmt)*100,2);
		   $chequedeclinedPer=number_format(($chequedeclinedAmt/$chequeAmt)*100,2);
		   $chequecanceledPer=number_format(($chequecanceledAmt/$chequeAmt)*100,2);
		   $chequePer =number_format($chequedeclinedPer + $chequeapprovedPer + $chequecanceledPer,2);
		}
		if($creditcardAmt !=0) {
		   $creditapprovedPer=number_format(($creditapprovedAmt/$creditcardAmt)*100,2);
		   $creditdeclinedPer=number_format(($creditdeclinedAmt/$creditcardAmt)*100,2);
		   $creditcanceledPer=number_format(($creditcanceledAmt/$creditcardAmt)*100,2);
		   $creditcardPer =number_format($creditdeclinedPer + $creditapprovedPer + $creditcanceledPer,2);
		}
		if($totamount !=0) {
		   $approvedPer=number_format(($approvedAmt/$totamount)*100,2);
		   $declinedPer=number_format(($declinedAmt/$totamount)*100,2);
		   $canceledPer=number_format(($canceledAmt/$totamount)*100,2);
		   $cardPer =number_format($declinedPer + $approvedPer + $canceledPer,2);
		}
	   if($totamount>0)
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
			   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
			   print("<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>");	
			   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
			   print("<td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");


			   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
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
		   print("<table class='lefttopright' cellpadding='5' cellspacing='0'  valign='top'  bgColor='#ffffff'  ID='Table1' style=' margin-left: 4; margin-top: 4; margin-bottom: 5'><tr height='30' bgcolor='#CCCCCC'>");
		   print("<td align='center' class='cl1'><span class='subhd'>Check Details</span></td>");	
		   print("<td align='center' class='cl1'><span class='subhd'>Quantity</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
		   print("<td align='right' class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");

		   print("<tr><td align='left' class='cl1'><font face='verdana' size='1'><b>Approved</b></font></td>");
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
	  
	   print("<tr height='30' bgcolor='#CCCCCC'><td align='center'  class='cl1'><span class='subhd'>Total Details</span></td>");	
	   print("<td align='center'  class='cl1'><span class='subhd'>Quantity</span></td>");
	   print("<td align='right' class='cl1'><span class='subhd'>Amount (".func_get_processing_currency($str_company_id).")</span></td>");
	   print("<td align='right'  class='cl1'><span class='subhd'>Percentage (%)</span></td></tr>");
	  
  	   print("<tr><td align='left'  class='cl1'><font face='verdana' size='1'><b>Total</b></font></td>");
	   print("<td align='center'  class='cl1'><font face='verdana' size='1'><b>&nbsp;$totalnum</b></font></td>");
	   print("<td align='right' class='cl1'><font face='verdana' size='1'><b>&nbsp;".number_format($totamount,2)."</b></font></td>");
	   print("<td align='right'  class='bottom'><font face='verdana' size='1'>&nbsp;".number_format($cardPer,2)."</font></td></tr>");	   

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

   
	   print("<tr bgcolor='#CCCCCC'><td align='left' class='cl1' colspan='2'><span class='subhd'>Deductions</span></td>");
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
?>