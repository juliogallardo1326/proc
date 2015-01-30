<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//
 
include("includes/sessioncheck.php");


$headerInclude = "voicesystem";
include("includes/header.php");


?>
<?php
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
//$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";
$companytrans_type = "tele";
$str_cancelled_shipping = (isset($HTTP_POST_VARS["chk_cancelled_shipping"])?quote_smart($HTTP_POST_VARS["chk_cancelled_shipping"]):"N");
$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
$selected_company = "";
if($companyid) {
	if($companyid[0]=="A") {
		if($str_cancelled_shipping == "Y")
		{
			if($companytype=="AC") {
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="NC"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0 and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="RE"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> '' and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="ET"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else{
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			}
		}
		else
		{
			if($companytype=="AC") {
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=1 and  a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="NC"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.activeuser=0 and  a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="RE"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id <> '' and  a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else if($companytype=="ET"){
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and b.reseller_id is null and  a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
			} else{
				$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'";
				if ($companytrans_type != "A") {
					$qry_select_details .= " and b.transaction_type = '$companytrans_type'";
				}
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
		$selected_company = $selected_company." )"; 
		if($str_cancelled_shipping == "Y")
		{
			$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and $selected_company and a.status = 'A' and a.cancelstatus = 'Y' and reason = 'Shipping Cancel' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
		}
		else
		{
			$qry_select_details ="Select a.transactionId,a.voiceAuthorizationno,a.name,a.surname,a.checkorcard,a.amount,a.status,a.shippingTrackingno,b.companyname from  cs_transactiondetails as a,cs_companydetails as b where a.userid=b.userid and $selected_company and a.status = 'A' and a.cancelstatus = 'N' and a.transactionDate >='$str_from_date' and a.transactionDate <='$str_to_date'" ;
		}
	}
}
$qry_select_details .= " and gateway_id = -1";
//	print($qry_select_details);
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
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="61%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
<br>
<form name="update_shipping" action="updateshipping.php" method="post">
<table width="90%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Shipping</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
	<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center" >
	 <tr>
	  <td class="bottom"  height="30" bgcolor="#CCCCCC"><span class="subhd">Voice Auth. Id/ Trans. Id</span></td>
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">Company Name&nbsp;</span></td>
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">First Name&nbsp;</span></td>
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">Last Name&nbsp;</span></td>
	  <td class="bottom" width="100" bgcolor="#CCCCCC"><span class="subhd">Type</span></td>		 
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">Amount ($)&nbsp;</span></td>		 
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">Approval&nbsp;Status</span></td>		 
	  <td class="bottom" bgcolor="#CCCCCC"><span class="subhd">Shipping Tracking No.</span></td>		 
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
			if($show_select_details[7] =="") {
				$shipping_details = "Not Available";
			} else {
				$shipping_details = $show_select_details[7];
			}
		 print"<input type='hidden' name='tid$iloop' value='$show_select_details[0]'>";
?>		 <tr>
		 <td class="leftbottomright" height="30"><font size="1" face="Verdana" ><?=$show_select_details[1]==""?$show_select_details[0]:$show_select_details[1]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$show_select_details[8]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$show_select_details[2]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$show_select_details[3]?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$trans_type?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=formatMoney($show_select_details[5]);?></font></td>		 
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$trans_status?></font></td>
		 <td class="rightbottomtd" ><font size="1" face="Verdana" ><?=$shipping_details?></font></td>
		 </tr>
<?php	}
?>	 <tr><td align="center" colspan="8" height="50" valign="middle"><a href="shipping.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td></tr>
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