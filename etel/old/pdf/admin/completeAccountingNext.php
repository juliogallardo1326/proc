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
// completeaccountingnext.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude = "companies";
include("includes/header.php");
include("includes/message.php");
//$monthcount=0;
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$rowcount=0;
$nextmonth="";
$firstmonth="";
$lastmonth="";
$listmonth="";
$str_week_date="";
$from_date="";
$to_date="";
$negative=array();
$loopindex=array();
$str_negative="";
$negativeamt=0;
$negativeamt_1=0;
$totalnegativeamt=0;
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$companyid = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$str_frequency = (isset($HTTP_POST_VARS["frequency"])?quote_smart($HTTP_POST_VARS["frequency"]):"");
$i_num_days_back = (isset($HTTP_POST_VARS["num_days_back"])?quote_smart($HTTP_POST_VARS["num_days_back"]):"");
$i_from_week_day = (isset($HTTP_POST_VARS["from_week_day"])?quote_smart($HTTP_POST_VARS["from_week_day"]):"");
$i_to_week_day = (isset($HTTP_POST_VARS["to_week_day"])?quote_smart($HTTP_POST_VARS["to_week_day"]):"");
$i_misc_fee = (isset($HTTP_POST_VARS["misc_fee"])?quote_smart($HTTP_POST_VARS["misc_fee"]):"");
$i_misc_fee_add = (isset($HTTP_POST_VARS["misc_fee_add"])?quote_smart($HTTP_POST_VARS["misc_fee_add"]):"");
$script_display = (isset($HTTP_POST_VARS["script_display"])?quote_smart($HTTP_POST_VARS["script_display"]):"");
if ($i_misc_fee == "" ) {
	$i_misc_fee = "0";
	
}
if ($i_misc_fee_add == "" ) {
	
	$i_misc_fee_add="0";
}

if (!is_numeric($i_misc_fee)) {
	$msgtodisplay="Please enter a numeric value for Misc. Fee-substract";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	
}   
if (!is_numeric($i_misc_fee_add)) {
	$msgtodisplay="Please enter a numeric value for Misc. Fee-Addition";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}
/*if ($i_num_days_back != "" && !is_numeric($i_num_days_back)) {
	$msgtodisplay="Please enter a numeric value for no: of days back";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}*/
if ($str_frequency == "") {
	$msgtodisplay="Please select a frequency";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}
if ($i_from_week_day > $i_to_week_day) {
	$msgtodisplay="Please select a valid week range";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}
$i_from_week_day = ($i_from_week_day == 7) ? 0 : $i_from_week_day;
$i_to_week_day = ($i_to_week_day == 7) ? 0 : $i_to_week_day;
$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
$str_current_date_time = func_get_current_date_time();


//echo $i_from_month."<br>";
//$str_from_date=$i_from_year."-".$i_from_month."-".$i_from_day." 00".":"."00".":"."00";
//$str_to_date=$i_to_year."-".$i_to_month."-".$i_to_day." 24".":"."59".":"."59";
/*if ($i_num_days_back == "") {
	//$i_num_days_back++;
	$str_current_date_time = func_get_current_date_time();
	$str_year = substr($str_current_date_time,0,4);
	$str_month = substr($str_current_date_time,5,2);
	$str_day = substr($str_current_date_time,8,2);
	//print(date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - $i_num_days_back, $str_year)));
	$i_day_of_week = date("w", mktime (0, 0, 0, $str_month, $str_day - $i_num_days_back, $str_year));
	$i_end_day_diff = $i_day_of_week - $i_to_week_day;
	$i_end_day_diff = $i_end_day_diff < 0 ? (7 + $i_end_day_diff) : $i_end_day_diff;
	$i_to_week_day = ($i_to_week_day == 0) ? 7 : $i_to_week_day;
	$i_start_day_diff = $i_end_day_diff + ($i_to_week_day - $i_from_week_day);
	//$str_from_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - ($i_num_days_back + $i_start_day_diff), $str_year));
	//$str_to_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - ($i_num_days_back + $i_end_day_diff), $str_year));
	$str_from_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day , $str_year));
	$str_to_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day, $str_year));
	print("start= $str_from_date; end= $str_to_date");
	exit();
//	print("num days= $i_num_days_back");
//}*/
$str_from_date = date("Y-m-d", mktime (0, 0, 0, $i_from_month, $i_from_day , $i_from_year));
$str_to_date = date("Y-m-d", mktime (0, 0, 0, $i_to_month, $i_to_day , $i_to_year));

$selected_company = "";
if($companyid) {
	$qry_select_details ="Select a.transactionDate,a.passStatus,a.status,a.cancelstatus,a.reason,a.amount,b.chargeback,b.transactionfee,b.voiceauthfee,b.companyname,b.credit,b.discountrate,b.reserve from cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and a.userid=$companyid and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;

	$qry_select_details .= " order by b.companyname,a.transactionDate";
	$companytype=func_get_value_of_field($cnn_cs,"cs_companydetails","transaction_type","userId",$companyid);
		
}
	//print($qry_select_details);
	
	if(!($rs_select_details = mysql_query($qry_select_details,$cnn_cs))) {
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("<br>");
		print($qry_select_details);
		print("Cannot execute query");
		exit();

	}
	$i_count = mysql_num_rows($rs_select_details);
	//echo $i_count;

	if ($i_count==0)
	{
		$msgtodisplay="No transactions for this period.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	} 
	$from_date=$str_from_date;
	$to_date=$str_to_date;
	$str_from_date = str_replace("-", "/", $str_from_date);
	$str_to_date = str_replace("-", "/", $str_to_date);
	
	?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="61%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
<br>
<form name="frmtransactionSummary" action="transactionsummary.php" method="post">
<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Complete Accounting</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
<table style="margin-top:10" align="center">
<tr>
<td>
<a href="editCompanyProfile1.php?company_id=<?= $companyid?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
<a href="editCompanyProfile2.php?company_id=<?= $companyid?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
<a href="editCompanyProfile3.php?company_id=<?= $companyid?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
<?= $script_display == "yes" ? "<a href='editCompanyProfile4.php?company_id=". $companyid ."'><IMG SRC='../images/lettertemplate_tab.gif' WIDTH='128' HEIGHT='32' BORDER='0' ALT=''></a>" : "" ?>
<a href="editCompanyProfile5.php?company_id=<?= $companyid?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a>
<IMG SRC="<?=$tmpl_dir?>/images/completeaccounting_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT="">
</td>
</tr>
</table>
	<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center" >
<?php 	$iloop = 0;
		$str_company = "";
		$str_temp_company = "-1";
		$str_pass_status = "";
		$str_status = "";
		$str_cancel_status = "";
		$i_voice_auth_fee = 0;
		$i_trans_fee = 0;
		$i_charge_back_fee = 0;
		$i_credit = 0;
		$i_discount_rate = 0;
		$i_reserve = 0;
		$i_total_amt = 0;
		$i_total_deduction = 0;
		$total_amt=0;
		$i_grand_deduction = 0;
		$i_grand_amt = 0;
		$i_grand_cancel = 0;
		$i_grand_approved=0;
		$i_grand_declined=0;
		$str_date = "";
		$str_temp_date = "";
		//$cancel_count=0;
		//$approved_count=0;
		//$declined_count=0;
		$i_count =0;
		//$i_total_qty=0;
		$i_app_amt=0;
		$i_declined_amt=0;
		$i_credit_amt=0;
		$i_grand_approvedAmt=0;
		$i_grand_declinedAmt=0;
		$i_grand_creditAmt=0;
		$date_pass="";
		$weekstartdate="";
		$weekenddate="";
		$i_total=0;
		while($show_select_details = mysql_fetch_array($rs_select_details)) 
		{
			$i_count = $i_count +1;
			//echo $i_count."<BR>";
			$str_temp_date = $str_date;
			$iloop = $iloop +1;
			//echo $iloop."<BR>";	
			$str_company = $show_select_details[9];
			$arr_day = split(" ", $show_select_details[0]);
			//echo $show_select_details[0];
			$str_day = $arr_day[0];
			if ($str_frequency == "D") {
				$str_date = func_get_date_inmmddyy($str_day);
			} else if ($str_frequency == "W") {
				$str_date = func_get_weekrange_from_date($str_day);
			} else if ($str_frequency == "M") {
				
				$str_date = func_get_month_from_date($str_day);
				//echo $str_date;
							
			}
				//print("day= ".$str_date."; temp= ".$str_temp_date);
			
			if (($str_temp_date != $str_date || $str_temp_company != $str_company) && $str_temp_date != "" ) {
				if($companytype=="tele"){
					$i_total_deduction = ($i_trans_fee + $i_voice_auth_fee + $i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
				}else{
					$i_total_deduction = ($i_trans_fee + $i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
				}
				$sub_total_amt=$i_app_amt + $i_declined_amt + $i_credit_amt;
				//changes----
				//echo ($total_amt=$i_total_amt - $i_total_deduction)."<BR>";
				 
				
					$total_amt=($i_total_amt - $i_total_deduction-(-$negativeamt));
					if($total_amt<0){
					$negativeamt=$total_amt;
					//echo $negativeamt."-ve"."<BR>";
					$total_amt-=$negativeamt;
					//echo $total_amt;
					}else{
						$negativeamt=0;
					}
					if ($str_frequency == "M") {
							//echo $str_date;
							
							$month="";
							
							//echo(($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date);
							//echo (($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date);
							$arrdate=explode("/", $str_from_date);
							
							$fromdate=$arrdate[2]."-".$arrdate[1]."-".$arrdate[0];
							//echo $fromdate;
							$arrtodate=explode("/", $str_to_date);
							$todate=$arrtodate[2]."-".$arrtodate[1]."-".$arrtodate[0];
							$betweendates=func_between_dates($fromdate,$todate);
							//echo $betweendates;
							
							$arrfrom_to_date=explode("+", $betweendates);
							//echo $arrfrom_to_date[0]."<BR>";
							//echo $arrfrom_to_date[1]."<BR>";
							
							//echo $listmonth."gfdg";
							//$month=explode("#",$listmonth);
							//echo $rowcount;
						
						
							//for($i=1;$i<=$rowcount;$i++){
									$startyear=$arrdate[0];
									$startmonth=$arrdate[1];
									$d=explode (",",$str_temp_date);
									
									$trans_start_month= func_month_number($d[0]);
									
									$off=($d[1]-$startyear)*12-($startmonth-$trans_start_month)+1;
									$enddate=explode("*",$arrfrom_to_date[0]);
									$startdate=explode("*",$arrfrom_to_date[1]);
									//echo $startdate[$off]."<BR>";
									//echo $enddate[$off]."<BR>";
									$enddate_1=explode("-",$enddate[$off]);
									$startdate_1=explode("-",$startdate[$off]);
									//echo $enddate_1[0]."<BR>".$enddate_1[1]."<BR>".$enddate_1[2];
									$startdate_2=$startdate_1[2]."-".$startdate_1[1]."-".$startdate_1[0];
									$enddate_2=$enddate_1[2]."-".$enddate_1[1]."-".$enddate_1[0];
									//echo $startdate_2."<BR>";
									//echo $enddate_2;
					}
			?>		
				<tr>
				
				<td class="leftbottomright" height="30"><font size="1" face="Verdana" ><?= ($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_app_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_declined_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($sub_total_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_trans_fee);?></font></td>
	            <?php if($companytype=="tele"){ ?>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_voice_auth_fee);?></font></td>
				<?php } ?>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_charge_back_fee);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_discount_rate);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_reserve);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_deduction);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?= formatMoney(($total_amt));?></font></td>
				<td class="rightbottomtd" align="center"><input type="checkbox" name="chkapproved<?=$i_count?>" value="1"></td>
				</tr> 
				<?php 
				$rowcount=$i_count;
				//echo $rowcount;
				$firstmonth=(($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date);
				//echo $firstmonth;
				if ($str_frequency == "D"){
						$daily_date=explode("-",$firstmonth);
						//echo $enddate_1[0]."<BR>".$enddate_1[1]."<BR>".$enddate_1[2];
						$date_pass=$daily_date[2]."-".$daily_date[0]."-".$daily_date[1];
						//echo $date_pass."<BR>";
						//echo $enddate_2;
						//$nextmonth=$nextmonth."#".$firstmonth;
				}
				if ($str_frequency == "W"){
						$sumdate=explode("-",$firstmonth);
						$format_1=explode("/",$sumdate[0]);
						$format_2=explode("/",$sumdate[1]);
						$weekstartdate=trim($format_1[2])."-".trim($format_1[0])."-".trim($format_1[1]);
						$weekenddate=trim($format_2[2])."-".trim($format_2[0])."-".trim($format_2[1]);
						//echo $weekstartdate."<BR>";
						//echo $weekenddate."<BR>";
				}
				if ($str_frequency == "M"){ ?>
						<input type="hidden" name="hidstartdates<?=$i_count?>" value="<?=$startdate_2;?>">
						<input type="hidden" name="hidenddates<?=$i_count?>" value="<?=$enddate_2;?>">
				<?php }
				if ($str_frequency == "D"){ ?>
						<input type="hidden" name="hidstartdates_D<?=$i_count?>" value="<?=$date_pass;?>">
			    <?php }
				if ($str_frequency == "W"){ ?>
						<input type="hidden" name="hidstartdates_W<?=$i_count?>" value="<?=$weekstartdate;?>">
						<input type="hidden" name="hidenddates_W<?=$i_count?>" value="<?=$weekenddate;?>">
			    <?php }?>
				<input type="hidden" name="hidbetweendates_start<?=$i_count?>" value="<?=$from_date;?>">
				<input type="hidden" name="hidbetweendates_end<?=$i_count?>" value="<?=$to_date;?>">
				<input type="hidden" name="hidfrequency<?=$i_count?>" value="<?=$str_frequency;?>">
				<input type="hidden" name="hidApprovedamt<?=$i_count?>" value="<?=$i_app_amt;?>">
				<input type="hidden" name="hiddeclinedamt<?=$i_count?>" value="<?=$i_declined_amt;?>">
				<input type="hidden" name="hidcancelamt<?=$i_count?>" value="<?=$i_credit_amt;?>">
				<input type="hidden" name="hidsubtotalamt<?=$i_count?>" value="<?=$sub_total_amt;?>">
				<input type="hidden" name="hidtransAmt<?=$i_count?>" value="<?=$i_total_amt;?>">
				<input type="hidden" name="hidtransFees<?=$i_count?>" value="<?=$i_trans_fee;?>">
				<input type="hidden" name="hidvoiceAuthFees<?=$i_count?>" value="<?=$i_voice_auth_fee;?>">
				<input type="hidden" name="hidchargeback<?=$i_count?>" value="<?=$i_charge_back_fee;?>">
				<input type="hidden" name="hidcredit<?=$i_count?>" value="<?=$i_credit;?>">
				<input type="hidden" name="hiddiscount<?=$i_count?>" value="<?=$i_discount_rate;?>">
				<input type="hidden" name="hidreserve<?=$i_count?>" value="<?=$i_reserve;?>">
				<input type="hidden" name="hidtotalDeduction<?=$i_count?>" value="<?=$i_total_deduction;?>">
				<input type="hidden" name="hidnetAmt<?=$i_count?>" value="<?= $total_amt;?>">
				
				
		<?php
				$i_grand_deduction += $i_total_deduction;
				$i_grand_amt += $i_total_amt;
				//$i_grand_cancel += $cancel_count;
				//$i_grand_approved += $approved_count;
				//$i_grand_declined += $declined_count;
				$i_grand_approvedAmt+=$i_app_amt;
				$i_grand_declinedAmt+=$i_declined_amt;
				$i_grand_creditAmt+=$i_credit_amt;
				$i_trans_fee = 0;
				$i_voice_auth_fee = 0;
				$i_charge_back_fee = 0;
				$i_credit = 0;
				$i_discount_rate = 0;
				$i_reserve = 0;
				$i_total_amt = 0;
				//$cancel_count=0;
				//$approved_count=0;
				//$declined_count=0;
				//$i_total_qty=0;
				$i_app_amt=0;
				$i_declined_amt=0;
				$i_credit_amt=0;
				$negative_amt=0;
				
			}
			$str_pass_status = $show_select_details[1];
			$str_status = $show_select_details[2];
			$str_cancel_status = $show_select_details[3];
			$i_total_amt += $show_select_details[5];
			
		
			if ($str_pass_status == "PA" && $show_select_details[7] != "") {
				$i_trans_fee += $show_select_details[7];
			}
			if ($str_pass_status != "PE" && $show_select_details[8] != "") {
				$i_voice_auth_fee += $show_select_details[8];
			}
			
			if ($str_cancel_status == "Y") {
				//changed
				//$cancel_count = $cancel_count + 1;
				$i_credit_amt+=$show_select_details[5];
				if ($show_select_details[4] == "Chargeback") {
					if ($show_select_details[6] != "") {
						$i_charge_back_fee += $show_select_details[6];
					}
				} else if ($show_select_details[4] == "Credit") {
					if ($show_select_details[10] != "") {
						$i_credit += $show_select_details[10];
						
					}
				}
			}
			if ($str_status == "A") {
				//$approved_count=$approved_count+1;
				$i_app_amt+=$show_select_details[5];
								
				if ($show_select_details[11] != "") {
					$i_discount_rate += ($show_select_details[11] * $show_select_details[5]) / 100;
				}
				if ($show_select_details[12] != "") {
					$i_reserve += ($show_select_details[12] * $show_select_details[5]) / 100;
				}
			}elseif($str_status == "D"){
				//$declined_count=$declined_count+1;
				$i_declined_amt+=$show_select_details[5];
			}
			if ($str_temp_company != $str_company) {
				$str_temp_company = $str_company;
				if ($i_grand_amt != 0) {
			?>
				<tr>
				<td colspan="1" class="leftbottomright">&nbsp;</td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_approvedAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_declinedAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_creditAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b><?=formatMoney(($i_grand_approvedAmt + $i_grand_declinedAmt + $i_grand_creditAmt));?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_amt); ?></b></font></td>
				
				<?php if($companytype=="tele"){ ?>
				<td colspan="6" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee-Substract: $<?= formatMoney($i_misc_fee,2,'.',','); ?>&nbsp;&nbsp;Misc. Fee-Add: $<?= number_format($i_misc_fee_add); ?></b></font></td>
				<?php }else{?>
				<td colspan="5" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee-Substract: $<?= formatMoney($i_misc_fee,2,'.',','); ?>&nbsp;&nbsp;Misc. Fee-Add: $<?= number_format($i_misc_fee_add); ?></b></font></td>
				<?php }?>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_deduction); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= ($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee)) < 0 ? "(" : "" ?><?= formatMoney(($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee))); ?><?= ($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee)) < 0 ? ")" : "" ?></b></font></td>
				 <td>&nbsp;</td>
				</tr>

			<?
					$i_grand_deduction = 0;
					$i_grand_amt = 0;
					
				}
			?>
				<tr><td colspan="15" align="center"><br><font size="2" face="Verdana" ><b><?= $str_company ?></b></font></td></tr>
				<tr>
				  <td class="bottom"  height="30" bgcolor="#CCCCCC" width="150"><span class="subhd">Date</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Approved Amt.&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Declined Amt.&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Credit Amt.&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">SubTotal 
                    Amt. &nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Total Trans. Amt. ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Transaction Fee ($)&nbsp;</span></td>
			  	  <?php if($companytype=="tele"){ ?>
				  		<td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Voice Auth. Fee ($)&nbsp;</span></td>
			      <?php }?>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Charge Back ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Credit ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Discount Rate ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Reserve ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Total Deduction ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Net Amount ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Approved&nbsp;</span></td>		 
				</tr>

			<?
			}
		}
		if ($i_total_amt != 0) {
		   if($companytype=="tele"){ 
				$i_total_deduction = ($i_trans_fee + $i_voice_auth_fee + $i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
			}else{
				$i_total_deduction = ($i_trans_fee +$i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
			}
			//changed------
			 $total_amt=($i_total_amt - $i_total_deduction-(-$negativeamt));
			 

			$i_grand_deduction += $i_total_deduction;
			$i_grand_amt += $i_total_amt;
			//$i_grand_cancel += $cancel_count;
			//$i_grand_approved += $approved_count;
			//$i_grand_declined += $declined_count;
			$sub_total_amt=$i_app_amt + $i_declined_amt + $i_credit_amt;
			$i_grand_approvedAmt += $i_app_amt;
			$i_grand_declinedAmt+=$i_declined_amt;
			$i_grand_creditAmt+=$i_credit_amt;
			
			if ($str_frequency == "M") {
					//echo $str_temp_date."temp";
					$month="";
					$arrdate=explode("/", $str_from_date);
					$fromdate=$arrdate[2]."-".$arrdate[1]."-".$arrdate[0];
					//echo $fromdate;
					$arrtodate=explode("/", $str_to_date);
					$todate=$arrtodate[2]."-".$arrtodate[1]."-".$arrtodate[0];
					$betweendates=func_between_dates($fromdate,$todate);
					//echo $betweendates;
					$arrfrom_to_date=explode("+", $betweendates);
					//for($i=1;$i<=$rowcount;$i++){
							$startyear=$arrdate[0];
							$startmonth=$arrdate[1];
							$d=explode (",",$str_date);
							
							$trans_start_month= func_month_number($d[0]);
							
							$off=($d[1]-$startyear)*12-($startmonth-$trans_start_month)+1;
							$enddate=explode("*",$arrfrom_to_date[0]);
							$startdate=explode("*",$arrfrom_to_date[1]);
							//echo $startdate[$off]."<BR>";
							//echo $enddate[$off]."<BR>";
							$enddate_1=explode("-",$enddate[$off]);
							$startdate_1=explode("-",$startdate[$off]);
							//echo $enddate_1[0]."<BR>".$enddate_1[1]."<BR>".$enddate_1[2];
							$startdate_2=$startdate_1[2]."-".$startdate_1[1]."-".$startdate_1[0];
							$enddate_2=$enddate_1[2]."-".$enddate_1[1]."-".$enddate_1[0];
							//echo $startdate_2."<BR>";
							//echo $enddate_2;
			}
			
			
		?>
			<tr>
			
			<td class="leftbottomright" height="30"><font size="1" face="Verdana" ><?= ($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_app_amt);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_declined_amt);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit_amt);?></font></td>
			<!-- changed -->
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($sub_total_amt);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_amt);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_trans_fee);?></font></td>
		    <?php if($companytype=="tele"){ ?>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_voice_auth_fee);?></font></td>
            <?php } ?>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_charge_back_fee);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_discount_rate);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_reserve);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_deduction);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney(($total_amt));?></font></td>
			<td class="rightbottomtd" align="center"><input type="checkbox" name="chkapproved<?=$i_count?>" value="1"></td>
			<?php  
			//if ($i_count<=1){
			
			if ($rowcount==0){
				$rowcount=1;
				//echo $rowcount;
			}
           $lastmonth=(($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date); 
		   //echo $lastmonth;
		   if ($str_frequency == "D"){
					$daily_date=explode("-",$lastmonth);
					//echo $enddate_1[0]."<BR>".$enddate_1[1]."<BR>".$enddate_1[2];
					$date_pass=$daily_date[2]."-".$daily_date[0]."-".$daily_date[1];
					//echo $date_pass."<BR>";
					//echo $enddate_2;
					//$nextmonth=$nextmonth."#".$firstmonth;
			}
			if ($str_frequency == "W"){
					$sumdate=explode("-",$lastmonth);
					$format_1=explode("/",$sumdate[0]);
					$format_2=explode("/",$sumdate[1]);
					$weekstartdate=trim($format_1[2])."-".trim($format_1[0])."-".trim($format_1[1]);
					$weekenddate=trim($format_2[2])."-".trim($format_2[0])."-".trim($format_2[1]);
					//echo $weekstartdate."<BR>";
					//echo $weekenddate."<BR>";
				}
			
		/*	$listmonth=$nextmonth."#".$lastmonth;
			if ($str_frequency == "M") {
					//echo "dfgdfgd";
					$month="";
					//echo(($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date);
					//echo (($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date);
					$arrdate=explode("/", $str_from_date);
					
					$fromdate=$arrdate[2]."-".$arrdate[1]."-".$arrdate[0];
					//echo $fromdate;
					$arrtodate=explode("/", $str_to_date);
					$todate=$arrtodate[2]."-".$arrtodate[1]."-".$arrtodate[0];
					$betweendates=func_between_dates($fromdate,$todate);
					//echo $betweendates;
					
					$arrfrom_to_date=explode("+", $betweendates);
					//echo $arrfrom_to_date[0]."<BR>";
					//echo $arrfrom_to_date[1]."<BR>";
					
					//echo $listmonth."gfdg";
					$month=explode("#",$listmonth);
					//echo $rowcount;
				
				
					for($i=1;$i<=$rowcount;$i++){
							$startyear=$arrdate[0];
							$startmonth=$arrdate[1];
							$d=explode (",",$month[$i]);
							
							$trans_start_month= func_month_number($d[0]);
							
							$off=($d[1]-$startyear)*12-($startmonth-$trans_start_month)+1;
							$enddate=explode("*",$arrfrom_to_date[0]);
							$startdate=explode("*",$arrfrom_to_date[1]);
							//echo $enddate[$off]."<BR>";
							//echo $startdate[$off]."<BR>";
					
					?>
					<input type="hidden" name="hidstartdate<?=$i ?>" value="<?=$startdate[$off];?>">
					
					<input type="hidden" name="hidenddate<?=$i ?>" value="<?=$enddate[$off];?>">
					
					
					
					
					<?php 
					
					}
				}*/
	 
			
			
			
			if ($str_frequency == "M"){ ?>
					<input type="hidden" name="hidstartdates<?=$i_count?>" value="<?=$startdate_2;?>">
					<input type="hidden" name="hidenddates<?=$i_count?>" value="<?=$enddate_2;?>">
			<?php }
			if ($str_frequency == "D"){ ?>
					<input type="hidden" name="hidstartdates_D<?=$i_count?>" value="<?=$date_pass;?>">
			<?php }
			if ($str_frequency == "W"){ ?>
					<input type="hidden" name="hidstartdates_W<?=$i_count?>" value="<?=$weekstartdate;?>">
					<input type="hidden" name="hidenddates_W<?=$i_count?>" value="<?=$weekenddate;?>">
			<?php }?>
					<input type="hidden" name="hidbetweendates_start<?=$i_count?>" value="<?=$from_date;?>">
					<input type="hidden" name="hidbetweendates_end<?=$i_count?>" value="<?=$to_date;?>">
					<input type="hidden" name="hidfrequency<?=$i_count?>" value="<?=$str_frequency;?>">
					<!--<input type="hidden" name="hidweekdates<?=$i_count?>" value="<?=$lastmonth;?>">-->
					<input type="hidden" name="hidApprovedamt<?=$i_count?>" value="<?=$i_app_amt;?>">
					<input type="hidden" name="hiddeclinedamt<?=$i_count?>" value="<?=$i_declined_amt;?>">
					<input type="hidden" name="hidcancelamt<?=$i_count?>" value="<?=$i_credit_amt;?>">
					<input type="hidden" name="hidsubtotalamt<?=$i_count?>" value="<?=$sub_total_amt;?>">
					<input type="hidden" name="hidtransAmt<?=$i_count?>" value="<?=$i_total_amt;?>">
					<input type="hidden" name="hidtransFees<?=$i_count?>" value="<?=$i_trans_fee;?>">
					<input type="hidden" name="hidvoiceAuthFees<?=$i_count?>" value="<?=$i_voice_auth_fee;?>">
					<input type="hidden" name="hidchargeback<?=$i_count?>" value="<?=$i_charge_back_fee;?>">
					<input type="hidden" name="hidcredit<?=$i_count?>" value="<?=$i_credit;?>">
					<input type="hidden" name="hiddiscount<?=$i_count?>" value="<?=$i_discount_rate;?>">
					<input type="hidden" name="hidreserve<?=$i_count?>" value="<?=$i_reserve;?>">
					<input type="hidden" name="hidtotalDeduction<?=$i_count?>" value="<?=$i_total_deduction;?>">
					<input type="hidden" name="hidnetAmt<?=$i_count?>" value="<?=$total_amt;?>">
			</tr>
			<?
			if ($i_grand_amt != "") {
			?>
				<tr>
				<td colspan="1" class="leftbottomright">&nbsp;</td>
								
                 <td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_approvedAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_declinedAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_creditAmt); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?=formatMoney(($i_grand_approvedAmt + $i_grand_declinedAmt + $i_grand_creditAmt));?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_amt); ?></b></font></td>
				<?php if($companytype=="tele"){ ?>
				<td colspan="6" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee-Substract: $<?= formatMoney($i_misc_fee,2,'.',','); ?>&nbsp;&nbsp;Misc. Fee-Add: $<?= number_format($i_misc_fee_add); ?></b></font></td>
				<?php }else{?>
				<td colspan="5" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee-Substract: $<?= formatMoney($i_misc_fee,2,'.',','); ?>&nbsp;&nbsp;Misc. Fee-Add: $<?= number_format($i_misc_fee_add); ?></b></font></td>
				<?php }?>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_deduction); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= ($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee)) < 0 ? "(" : "" ?><?= formatMoney(($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee))); ?><?= ($i_grand_amt+ $i_misc_fee_add - ($i_grand_deduction + $i_misc_fee)) < 0 ? ")" : "" ?></b></font></td>
				  <td>&nbsp;</td>
				</tr>
<?php		
				} 		
		}
		
		?>
<tr><td align="center" colspan="15" height="50" valign="middle"><a href="javascript:history.back();"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp;<input type="image" src="images/submit.jpg" border="0"></td></tr>
	</table>							
</td>
</tr>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</table><br>
<input type="hidden" name="icount" value="<?=$iloop?>">

<input type="hidden" name="hidcompanyid" value="<?=$companyid?>">
<input type="hidden" name="hidmiscAdd" value="<?=$i_misc_fee_add?>">
<input type="hidden" name="hidmiscSub" value="<?=$i_misc_fee?>">
<input type="hidden" name="hidbetweendates_start_1" value="<?=$from_date;?>">
<input type="hidden" name="hidbetweendates_end_1" value="<?=$to_date;?>">
<input type="hidden" name="hidfrequency" value="<?=$str_frequency;?>">
 <?php 

 /*if ($str_frequency == "M") {
		$month="";
		//echo(($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date);
		//echo (($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date);
		$arrdate=explode("/", $str_from_date);
		
		$fromdate=$arrdate[2]."-".$arrdate[1]."-".$arrdate[0];
		$arrtodate=explode("/", $str_to_date);
		$todate=$arrtodate[2]."-".$arrtodate[1]."-".$arrtodate[0];
		$betweendates=func_between_dates($fromdate,$todate);
		//echo $betweendates;
		
		$arrfrom_to_date=explode("+", $betweendates);
		//echo $arrfrom_to_date[0]."<BR>";
		//echo $arrfrom_to_date[1]."<BR>";
		
		
		$month=explode("#",$listmonth);
		//echo $rowcount;
		
		
		for($i=1;$i<=$rowcount;$i++){
			$startyear=$arrdate[0];
			$startmonth=$arrdate[1];
			 $d=explode (",",$month[$i]);
			 
			$trans_start_month= func_month_number($d[0]);
			
			$off=($d[1]-$startyear)*12-($startmonth-$trans_start_month)+1;
			$enddate=explode("*",$arrfrom_to_date[0]);
			$startdate=explode("*",$arrfrom_to_date[1]);
			echo $enddate[$off]."<BR>";
			echo $startdate[$off]."<BR>";
		
		?>
			<input type="hidden" name="hidstartdate<?=$i ?>" value="<?=$startdate[$off];?>">
			
			<input type="hidden" name="hidenddate<?=$i ?>" value="<?=$enddate[$off];?>">
			
	<?php }
	 }*/
	 
		?>
<input type="hidden" name="hidrowcount" value="<?=$rowcount?>">

</form>
</td></tr>
</table>							
<?php





include("includes/footer.php");



function func_between_dates($fromdate,$enddate){
$lst="";
$ifyear="";
$ieyear="";
//$fromdate='20-3-2004';
//$enddate='30-6-2004';
$ifmonth="";
$expfdate=explode("-",$fromdate);
$expenddate=explode("-",$enddate);

$ifday=$expfdate[0];
$ifmonth=$expfdate[1];
$ifyear=$expfdate[2];

$ieday=$expenddate[0];
$iemonth=$expenddate[1];
$ieyear=$expenddate[2];




$fromdatelist="";
$begindatelist="";
//print $totaldays."days"."<BR>";
$returndate="";
//echo $ifday."th day"."<BR>";
$year=$ifyear;
$monthdiff=($iemonth-$ifmonth)+1;
$yeardiff=$ieyear-$ifyear;
$lastdate="";
$last="";
$beginday="";
$begin="";
$iendday="";

//echo $monthdiff."<BR>";
//echo $yeardiff;
if($yeardiff>0){
	$monthdiff=12-$ifmonth;
	$monthdiff+=1;
	while($yeardiff>0){
			$iendmonth=12;
			$iendday=func_noof_days_in_month($iendmonth,$ifyear);
			$iendyear=$ifyear+1;
			$returndate=getdays($ifmonth,$iendmonth,$ifyear,$ifyear,$ifday,$iendday,$monthdiff);
			$dates=explode("+",$returndate);
			$fromdatelist.=$dates[0];
			$begindatelist.=$dates[1];
			$ifyear+=1;
			$ifmonth=1;
			$yeardiff-=1;
			$ifday=1;
			$monthdiff=12;
	}
	$monthdiff=$iemonth;
	$ifmonth=1;
}
	if($yeardiff==0)
	{
		if($monthdiff==1)
				$monthdiff=0;
				$monthdiff;
				$returndate=getdays($ifmonth,$iemonth,$ifyear,$ieyear,$ifday,$ieday,$monthdiff);
				$dates=explode("+",$returndate);
				$fromdatelist.=$dates[0];
				$begindatelist.=$dates[1];
		}
	return $fromdatelist."+".$begindatelist;
	
}

function func_noof_days_in_month($month,$iyear){
	$totaldays="";
		switch ($month) { 
			case 1: 
					$totaldays=31;
					break;	
			case 2: 
					if($iyear%4==0){
						$totaldays=29;
					}else{
						$totaldays=28;
					}break;
			case 3: 
					$totaldays=31;break;
			case 4: 
					$totaldays=30;break;
			case 5: 
					$totaldays=31;break;	
			case 6: 
					$totaldays=30;break;		
			case 7: 
					$totaldays=31;break;		
			case 8: 
					$totaldays=31;break;		
			case 9: 
					$totaldays=30;break;
			case 10: 
					$totaldays=31;break;		
			case 11: 
					$totaldays=30;break;
			case 12: 
					$totaldays=31;break;		
			
			
			}
			return $totaldays;	
}




function getdays($ifmonth,$iemonth,$ifyear,$ieyear,$ifday,$ieday,$monthdiff)
{
$begin="";
$begindatelist="";
$lastdatelist="";
$returndate="";
if($monthdiff==0)
{
$begin="*".$ifday."-".$ifmonth."-".$ifyear;

$last="*".$ieday."-".$iemonth."-".$ieyear;
}
else
{
$fromdate="";
$last="";
$lastdayofmonth="";
$begin="*".$ifday."-".$ifmonth."-".$ifyear;


		if($monthdiff>0){
		for($i=1;$i<=$monthdiff;$i++){
		//echo $ifmonth;
			$daysinmonth=func_noof_days_in_month($ifmonth,$ifyear);
			$remDays=$daysinmonth-$ifday;
			if($iemonth>$ifmonth){
				$lastdayofmonth=$ifday+$remDays;
				$lastdate=$lastdayofmonth."-".$ifmonth."-".$ifyear;
			}else{
				$lastdate=$ieday."-".$iemonth."-".$ieyear;
			}
			$last.="*".$lastdate;
			
			if($i!=1)
			if($lastdayofmonth==31 || $lastdayofmonth==30 || $lastdayofmonth==28 || $lastdayofmonth==29){
				$beginday=01;
				$begindate=$beginday."-".$ifmonth."-".$ifyear;
				$begin.="*".$begindate;
			}
			$ifmonth=$ifmonth+1;
		}
				
		}
	
	}
	$lastdatelist.=$last;
			
	$begindatelist.=$begin;
	$returndate=$lastdatelist."+".$begindatelist;
	return $returndate;	
}


function func_month_number($str_month){
	$monthnumber="";
		switch ($str_month) { 
			case 'January': 
					$monthnumber=1;
					break;	
			case 'Feburary': 
					$monthnumber=2;
					break;
			case 'March': 
					$monthnumber=3;break;
			case 'April': 
					$monthnumber=4;break;
			case 'May': 
					$monthnumber=5;break;	
			case 'June': 
					$monthnumber=6;break;		
			case 'July': 
					$monthnumber=7;break;		
			case 'August': 
					$monthnumber=8;break;		
			case 'September': 
					$monthnumber=9;break;
			case 'October': 
					$monthnumber=10;break;		
			case 'November': 
					$monthnumber=11;break;
			case 'December': 
					$monthnumber=12;break;		
			
			
			}
			return $monthnumber;	
}

?>