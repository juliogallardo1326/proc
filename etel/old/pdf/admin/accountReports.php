<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// accountReports.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude = "transactions";
include("includes/header.php");

include("includes/message.php");

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$companyid = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";
$str_frequency = (isset($HTTP_POST_VARS["frequency"])?quote_smart($HTTP_POST_VARS["frequency"]):"");
$i_num_days_back = (isset($HTTP_POST_VARS["num_days_back"])?quote_smart($HTTP_POST_VARS["num_days_back"]):"");
$i_from_week_day = (isset($HTTP_POST_VARS["from_week_day"])?quote_smart($HTTP_POST_VARS["from_week_day"]):"");
$i_to_week_day = (isset($HTTP_POST_VARS["to_week_day"])?quote_smart($HTTP_POST_VARS["to_week_day"]):"");
$i_misc_fee = (isset($HTTP_POST_VARS["misc_fee"])?quote_smart($HTTP_POST_VARS["misc_fee"]):"");
if ($i_misc_fee == "") {
	$i_misc_fee = "0.00";
}
if (!is_numeric($i_misc_fee)) {
	$msgtodisplay="Please enter a numeric value for Misc. Fee.";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}
if ($i_num_days_back != "" && !is_numeric($i_num_days_back)) {
	$msgtodisplay="Please enter a numeric value for no: of days back";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
}
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
if ($i_num_days_back != "") {
	$i_num_days_back++;
	$str_current_date_time = func_get_current_date_time();
	$str_year = substr($str_current_date_time,0,4);
	$str_month = substr($str_current_date_time,5,2);
	$str_day = substr($str_current_date_time,8,2);
//	print(date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - $i_num_days_back, $str_year)));
	$i_day_of_week = date("w", mktime (0, 0, 0, $str_month, $str_day - $i_num_days_back, $str_year));
	$i_end_day_diff = $i_day_of_week - $i_to_week_day;
	$i_end_day_diff = $i_end_day_diff < 0 ? (7 + $i_end_day_diff) : $i_end_day_diff;
	$i_to_week_day = ($i_to_week_day == 0) ? 7 : $i_to_week_day;
	$i_start_day_diff = $i_end_day_diff + ($i_to_week_day - $i_from_week_day);
	$str_from_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - ($i_num_days_back + $i_start_day_diff), $str_year));
	$str_to_date = date("Y-m-d", mktime (0, 0, 0, $str_month, $str_day - ($i_num_days_back + $i_end_day_diff), $str_year));
	//print("start= $str_from_date; end= $str_to_date");
//	print("num days= $i_num_days_back");
}
$selected_company = "";
if($companyid) {
	if($companyid[0]=="A") {
		if($companytype=="AC") {
			$qry_select_details ="Select a.transactionDate,a.passStatus,a.status,a.cancelstatus,a.reason,a.amount,b.chargeback,b.transactionfee,b.voiceauthfee,b.companyname,b.credit,b.discountrate,b.reserve from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
			if ($companytrans_type != "A") {
				$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
			}
		}else if($companytype=="NC"){
			$qry_select_details ="Select a.transactionDate,a.passStatus,a.status,a.cancelstatus,a.reason,a.amount,b.chargeback,b.transactionfee,b.voiceauthfee,b.companyname,b.credit,b.discountrate,b.reserve from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0 and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
			if ($companytrans_type != "A") {
				$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
			}
		}else{
			$qry_select_details ="Select a.transactionDate,a.passStatus,a.status,a.cancelstatus,a.reason,a.amount,b.chargeback,b.transactionfee,b.voiceauthfee,b.companyname,b.credit,b.discountrate,b.reserve from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
			if ($companytrans_type != "A") {
				$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
			}
		}
	} else {
		for($i_loop=0;$i_loop<count($companyid);$i_loop++)
		{	
			if($selected_company == ""){
			$selected_company = $selected_company." (a.userid = ".$companyid[$i_loop];
			}else{
			$selected_company = $selected_company."  or a.userid = ".$companyid[$i_loop];
			}
		}
		if($selected_company != "") {
			$selected_company = $selected_company." )"; 
		}
		$qry_select_details ="Select a.transactionDate,a.passStatus,a.status,a.cancelstatus,a.reason,a.amount,b.chargeback,b.transactionfee,b.voiceauthfee,b.companyname,b.credit,b.discountrate,b.reserve from cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and $selected_company and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
	}
	$qry_select_details .= " order by b.companyname,a.transactionDate";
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

	if ($i_count==0)
	{
		$msgtodisplay="No transactions for this period.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	} 
	$str_from_date = str_replace("-", "/", $str_from_date);
	$str_to_date = str_replace("-", "/", $str_to_date);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="61%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
<br>
<form name="update_shipping" action="updateshipping.php" method="post">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Account Reports</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
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
		$i_grand_deduction = 0;
		$i_grand_amt = 0;
		$str_date = "";
		$str_temp_date = "";
		while($show_select_details = mysql_fetch_array($rs_select_details)) 
		{	
			$str_temp_date = $str_date;
			$iloop = $iloop +1;	
			$str_company = $show_select_details[9];
			$arr_day = split(" ", $show_select_details[0]);
			$str_day = $arr_day[0];
			if ($str_frequency == "D") {
				$str_date = func_get_date_inmmddyy($str_day);
			} else if ($str_frequency == "W") {
				$str_date = func_get_weekrange_from_date($str_day);
			} else if ($str_frequency == "M") {
				$str_date = func_get_month_from_date($str_day);
			}
				//print("day= ".$str_day."; temp= ".$str_temp_day);
			if (($str_temp_date != $str_date || $str_temp_company != $str_company) && $str_temp_date != "" ) {
				$i_total_deduction = ($i_trans_fee + $i_voice_auth_fee + $i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
			?>		
				<tr>
				<td class="leftbottomright" height="30"><font size="1" face="Verdana" ><?= ($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_temp_date?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_amt);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_trans_fee);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_voice_auth_fee);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_charge_back_fee);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_discount_rate);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_reserve);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_deduction);?></font></td>
				<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?= ($i_total_amt - $i_total_deduction) < 0 ? "(" : "" ?><?= formatMoney(($i_total_amt - $i_total_deduction));?><?= ($i_total_amt - $i_total_deduction) < 0 ? ")" : "" ?></font></td>
				</tr>
		<?php
				$i_grand_deduction += $i_total_deduction;
				$i_grand_amt += $i_total_amt;
				$i_trans_fee = 0;
				$i_voice_auth_fee = 0;
				$i_charge_back_fee = 0;
				$i_credit = 0;
				$i_discount_rate = 0;
				$i_reserve = 0;
				$i_total_amt = 0;
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
				if ($show_select_details[11] != "") {
					$i_discount_rate += ($show_select_details[11] * $show_select_details[5]) / 100;
				}
				if ($show_select_details[12] != "") {
					$i_reserve += ($show_select_details[12] * $show_select_details[5]) / 100;
				}
			}
			if ($str_temp_company != $str_company) {
				$str_temp_company = $str_company;
				if ($i_grand_amt != 0) {
			?>
				<tr>
				<td colspan="1" class="leftbottomright">&nbsp;</td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_amt); ?></b></font></td>
				<td colspan="6" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee: $<?= formatMoney($i_misc_fee); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_deduction); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b><?= ($i_grand_amt - $i_grand_deduction + $i_misc_fee) < 0 ? "(" : "" ?>$<?= formatMoney(($i_grand_amt - $i_grand_deduction + $i_misc_fee)); ?><?= ($i_grand_amt - $i_grand_deduction + $i_misc_fee) < 0 ? ")" : "" ?></b></font></td>
				</tr>
			<?
					$i_grand_deduction = 0;
					$i_grand_amt = 0;
				}
			?>
				<tr><td colspan="10" align="center"><br><font size="2" face="Verdana" ><b><?= $str_company ?></b></font></td></tr>
				<tr>
				  <td class="bottom"  height="30" bgcolor="#CCCCCC" width="150"><span class="subhd">Date</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Total Trans. Amt. ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Transaction Fee ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Voice Auth. Fee ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Charge Back ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Credit ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Discount Rate ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Reserve ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Total Deduction ($)&nbsp;</span></td>
				  <td class="bottom" bgcolor="#CCCCCC" align="right"><span class="subhd">Net Amount ($)&nbsp;</span></td>		 
				</tr>

			<?
			}
		}
		if ($i_total_amt != 0) {
			$i_total_deduction = ($i_trans_fee + $i_voice_auth_fee + $i_charge_back_fee + $i_credit + $i_discount_rate + $i_reserve);
			$i_grand_deduction += $i_total_deduction;
			$i_grand_amt += $i_total_amt;

		?>
			<tr>
			<td class="leftbottomright" height="30"><font size="1" face="Verdana" ><?= ($i_num_days_back != "" && $str_frequency == "W") ?  ($str_from_date ." - ". $str_to_date) : $str_date?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_amt);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_trans_fee);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_voice_auth_fee);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_charge_back_fee);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_credit);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_discount_rate);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_reserve);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?=formatMoney($i_total_deduction);?></font></td>
			<td class="rightbottomtd" align="right"><font size="1" face="Verdana" ><?= ($i_total_amt - $i_total_deduction) < 0 ? "(" : "" ?><?=formatMoney(($i_total_amt - $i_total_deduction));?><?= ($i_total_amt - $i_total_deduction) < 0 ? ")" : "" ?></font></td>
			</tr>
			<?
			if ($i_grand_amt != "") {
			?>
				<tr>
				<td colspan="1" class="leftbottomright">&nbsp;</td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_amt); ?></b></font></td>
				<td colspan="6" class="rightbottomtd" align="center"><font size="1" face="Verdana"><b>Misc. Fee: $<?= formatMoney($i_misc_fee); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b>$<?= formatMoney($i_grand_deduction); ?></b></font></td>
				<td align="right" class="rightbottomtd"><font size="1" face="Verdana"><b><?= ($i_grand_amt - $i_grand_deduction + $i_misc_fee) < 0 ? "(" : "" ?>$<?= formatMoney(($i_grand_amt - $i_grand_deduction + $i_misc_fee)); ?><?= ($i_grand_amt - $i_grand_deduction + $i_misc_fee) < 0 ? ")" : "" ?></b></font></td>
				</tr>
		<?
			}
		}
		?>
<tr><td align="center" colspan="10" height="50" valign="middle"><a href="accounts.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td></tr>
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
</form>
</td></tr>
</table>							
<?php
include("includes/footer.php");
?>