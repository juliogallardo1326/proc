<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// CompanyUser.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");

require_once("includes/function.php");
include("includes/header.php");
$headerInclude = "reports";
include("includes/topheader.php");
include("includes/message.php");
?>
<?php
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");
$i_time_frame=0;
$update_trans = (isset($HTTP_POST_VARS["updated"])?Trim($HTTP_POST_VARS["updated"]):"");
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;


// $iCount =  func_sel_count($str_from_date,$str_to_date);
if ($str_from_date != "")
{
   $qry_select_details ="Select transactionId,voiceAuthorizationno,name,surname,checkorcard,amount,status,approvaldate from cs_transactiondetails where userId = ".$_SESSION["sessionlogin"]." and status = 'A' and cancelstatus = 'N' and shippingTrackingno = '' and transactionDate >='$str_from_date' and transactionDate <='$str_to_date'" ;
//   print $qry_select_details;
	$i_shipping_cancel = func_get_value_of_field($cnn_cs,"cs_companydetails","shipping_cancel","userId",$_SESSION["sessionlogin"]);
	if($i_shipping_cancel =="Y") {
		$i_time_frame = func_get_value_of_field($cnn_cs,"cs_companydetails","shipping_timeframe","userId",$_SESSION["sessionlogin"]);
	}
	if(!($rs_select_details = mysql_query($qry_select_details,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");


	}
	$i_count = mysql_num_rows($rs_select_details);

	if ($i_count==0 && $update_trans =="")
	{
		$msgtodisplay="No transactions for this period.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	} elseif($update_trans !="") {
		$msgtodisplay="Transactions Updated.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="61%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
<br>
<form name="update_shipping" action="updateshipping.php" method="post">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Shipping</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5"><br>
	<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center" >
	 <tr>
	  <td class="bottom"  height="30" bgcolor="#78B6C2"><span class="subhd">Transaction ID</span></td>
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">First Name&nbsp;</span></td>
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">Last Name&nbsp;</span></td>
	  <td class="bottom" width="100" bgcolor="#78B6C2"><span class="subhd">Type</span></td>		 
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">Amount ($)&nbsp;</span></td>		 
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">Approval&nbsp;Status</span></td>		 
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">Shipping Tracking No.</span></td>		 
	  <td class="bottom" bgcolor="#78B6C2"><span class="subhd">Days Remaining</span></td>		 
	 </tr>
<?php 	$iloop = 0;
		while($show_select_details = mysql_fetch_array($rs_select_details)) 
		{	
		$iloop = $iloop +1;	
			if ($show_select_details[4] == 'C') {
				$trans_type = "Check";
			} else {
				$trans_type = "Credit Card";
			}
			if($show_select_details[6] = 'A') {
				$trans_status = "Approved";
			}
			$i_days_remaining = $i_time_frame - func_get_date_diff_from_current_day($show_select_details[7]);
		 print"<input type='hidden' name='tid$iloop' value='$show_select_details[0]'>";
?>		 <tr>
		 <td class="leftbottomright" height="30"><font size="1" face="Verdana" ><a href="viewreportpage.php?id=<?=$show_select_details[0]?>" class="link"><?=$show_select_details[1]?></a></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$show_select_details[2]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$show_select_details[3]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$trans_type?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=formatMoney($show_select_details[5]);?></font></td>		 
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$trans_status?></font></td>
		 <td class="rightbottomtd" >&nbsp;<input type="text" name="shipping<?=$iloop?>"  style="font-size:10px;font-face:verdana"></td>
		 <td class="rightbottomtd" >&nbsp;<font size="1" face="Verdana" color="red"><?=$i_days_remaining?></font></td>
		 </tr>
<?php	}
?>	 <tr><td align="center" colspan="8" height="50" valign="middle"><input type="image" src="images/submitcompanydetails.jpg"></td></tr>
	</table>							
</td>
</tr>
<tr>
<td width="1%"><img src="images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif"></td>
</tr>
</table><br>
<input type="hidden" name="icount" value="<?=$iloop?>">
</form>
</td></tr>
</table>							
<?php
}
include("includes/footer.php");
?>