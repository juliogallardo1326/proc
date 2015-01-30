<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//
 
include("includes/sessioncheck.php");

require_once("../includes/function.php");
$headerInclude = "customerservice";
include("includes/header.php");
include("includes/message.php");
?>
<?php
$Transtype = isset($HTTP_POST_VARS['trans_type'])?Trim($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?Trim($HTTP_POST_VARS['companytrans_type']):"A";
$company_name = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails order by companyname";

if ($Transtype == "Submit")  {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where $qrt_select_subqry $qrt_select_merchant_qry";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
//print($qrt_select_companies);
}
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_report = (isset($HTTP_POST_VARS["hid_report"])?Trim($HTTP_POST_VARS["hid_report"]):"");


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";
?>
<?php
			 
if ($Transtype != "showResult")
{			  
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_cancel_requests;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
function Displaycompanytype() {
	document.frm_cancel_requests.trans_type.value="Submit";
	document.frm_cancel_requests.action = "cancelrequests.php";
	document.frm_cancel_requests.submit();
}

</script>
			<form name="frm_cancel_requests" action="cancelrequests.php" method="post">
			<input type="hidden" name="trans_type" value="showResult">
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
			      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Cancel Requests</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">  
			<tr>
			   <td   height="10"  valign="middle" align="center" width="50%">
		  </td>
			</tr>
			<tr>
			<td height="30"  valign="middle" align="center" width="50%"> 
			  <font face="verdana" size="1">Start Date</font>&nbsp; 
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select>
		     <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		    <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		    <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,280,document.frm_cancel_requests.from_date)">
		   </td></tr>
		   <tr>
		    <td height="30"  valign="middle" align="center"> <font face="verdana" size="1">End Date&nbsp;</font>&nbsp;  
		  &nbsp;<select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
		  </select>
		  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
		  </select>
		  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
		  </select>
		   <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,320,document.frm_cancel_requests.from_to)">
		  </td>
		  </tr>		  		  
		 <tr>
		    <td height="30" valign="middle" align="center">
			<table align="center" width="70%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			 <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
			<?php print func_select_mailcompanytype($companytype); ?>
			</select></td>
			</tr>
			<tr>
			 <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Merchant Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
			<?php print func_select_companytrans_type($companytrans_type); ?>
			</select></td>
			</tr>
			<tr>
			 <td height="60" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Name </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 150px" multiple>
			<?php print func_multiselect_transaction($qrt_select_companies); ?>
			</select></td>
			</tr>
			</table>
		  </td>
		 </tr>
		 <tr>
		    <td height="35" valign="middle" align="center"><input type="image" id="viewcompany" src="../images/view.jpg"></input>
		</td>
		</tr>
		</table> 
		</td>
		</tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
		</table>
<?php
} else {
	$str_where_condition = "";
	$str_company_ids = "";
	if ($company_name[0] == "A") {
		if ($companytype == "A") {
			if ($companytrans_type == "A") {
				$str_where_condition = "";
			} else {
				$str_where_condition = "where C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "AC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.activeuser = 1 ";
			} else {
				$str_where_condition = "where C.activeuser = 1 and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "NC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.activeuser = 0 ";
			} else {
				$str_where_condition = "where C.activeuser = 0 and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "RE") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.reseller_id <> '' ";
			} else {
				$str_where_condition = "where C.reseller_id <> '' and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "ET") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.reseller_id is null ";
			} else {
				$str_where_condition = "where C.reseller_id is null and C.transaction_type = '$companytrans_type' ";
			}
		}
	} else {
		for ($i = 0; $i < count($company_name); $i++) {
			$str_company_ids .= $company_name[$i] . ", ";
		}
		$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 2);
		$str_where_condition = "where C.userId in ($str_company_ids)";
	}
	if ($str_from_date != "")
	{

		   $qry_select="Select A.note_id ,A.transaction_id ,A.call_date_time,A.service_notes,B.cancelstatus,A.solved,A.is_bill_date_changed,A.call_duration,A.customer_service_id,A.prev_bill_date,B.phonenumber,B.billingDate,B.name,B.surname,C.companyname,B.cancellationDate,B.checkorcard,B.bankroutingcode,B.userId from cs_callnotes A,cs_transactiondetails B,cs_companydetails C ";
		   $qry_select .= $str_where_condition == "" ? " where " : $str_where_condition ." and ";
		   $qry_select .= " A.transaction_id = B.transactionId and B.userId = C.userId and A.cancel_status = '1' and B.cancelstatus = 'N' and B.admin_approval_for_cancellation='P' and A.call_date_time >= '".$str_from_date."'";
			if($str_to_date != "")
			{
				$qry_select .= " AND A.call_date_time <= '".$str_to_date."'";
			}
			$qry_select .= " Order by C.companyname, A.call_date_time desc";
			//print($qry_select);
			$rssel_report = mysql_query($qry_select);
			$i_count = mysql_num_rows($rssel_report);
			if ($i_count==0)
			{
				$msgtodisplay="No Cancel Requests for this period.";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	   
			}
			if ($i_count>0)
			{
			  $str_data = "";
			  $str_temp_company = "";
			  $i_num_record = 0;
			  for($i=0;$i<mysql_num_rows($rssel_report);$i++)
			  {
				$i_noteid = mysql_result($rssel_report,$i,0);
				$i_transid = mysql_result($rssel_report,$i,1);
				$str_calldatetime = mysql_result($rssel_report,$i,2);
				$str_notes  = mysql_result($rssel_report,$i,3);
				$str_cancel_status  = mysql_result($rssel_report,$i,4) == "Y" ? "Cancelled" : "";
				$str_solved  = mysql_result($rssel_report,$i,5) == "1" ? "Solved" : "";
				$str_bill_date_changed	= mysql_result($rssel_report,$i,6);
				$str_call_duration = mysql_result($rssel_report,$i,7);
				$i_customer_service_id = mysql_result($rssel_report,$i,8);
				$str_prev_bill_date	= mysql_result($rssel_report,$i,9);
				$str_phone	= mysql_result($rssel_report,$i,10);
				$str_new_bill_date	= mysql_result($rssel_report,$i,11);
				$str_first_name = mysql_result($rssel_report,$i,12);
				$str_surname = mysql_result($rssel_report,$i,13);
				$str_company = mysql_result($rssel_report,$i,14);
				$str_cancellation_date = mysql_result($rssel_report,$i,15);
				$str_payment_type = mysql_result($rssel_report,$i,16);
				$str_bank_routing_code = mysql_result($rssel_report,$i,17);
				$i_company_id = mysql_result($rssel_report,$i,18);
				$str_customer_service_rep = "";
				if($i_customer_service_id == 0)
				{
					$str_customer_service_rep = "service";
				}
				else
				{
					$str_customer_service_rep = func_get_value_of_field($cnn_cs,"cs_customerserviceusers","username","id",$i_customer_service_id);
				}
				if ($str_temp_company != $str_company) {
					  $str_temp_company = $str_company;
					  $i_num_record = 0;
					  $str_data .= "<tr><td colspan='10' align='center' height='40'><font face='verdana' size='1'><b>$str_company</b></font></td></tr>";
					  $str_data .= "<tr>";
					  $str_data .= "<td width='3%' bgcolor='#78B6C2' height='30' rowspan='2'><span class='subhd'>No.</span></td>";
					  $str_data .= "<td width='4%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Tran ID</span></td>";
					  $str_data .= "<td width='5%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Customer Name</span></td>";
					  $str_data .= "<td width='6%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Phone Number</span></td>";
					  $str_data .= "<td width='12%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Call DateTime</span></td>";
					  $str_data .= "<td width='19%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Service Notes</span></td>";
					  $str_data .= "<td width='8%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Bill Date</span></td>";
					  $str_data .= "<td width='5%' bgcolor='#78B6C2' rowspan='2'><span class='subhd'>Customer Service Rep.</span></td>";		 
					  $str_data .= "<td width='5%' bgcolor='#78B6C2' colspan='2' align='center'><span class='subhd'>Cancel</span></td>";		 
					  $str_data .= "</tr>";
					  $str_data .= "<tr>";
					  $str_data .= "<td width='2%' bgcolor='#78B6C2'><span class='subhd'>Accept</span></td>";		$str_data .= "<td width='2%' bgcolor='#78B6C2'><span class='subhd'>Reject</span></td>";		 
					  $str_data .= "</tr>";
			   }
					 $str_data .= "<tr>";
					 $str_data .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana' >". ++$i_num_record ."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$i_transid." </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_first_name." &nbsp;".$str_surname."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_phone." </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_time_12hr($str_calldatetime)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >";
					 if ($str_notes == '') {
						$str_data .= "&nbsp;";
					 } else {
						$str_data .= "$str_notes";
					 }								
					 $str_data .= "</font></td>";		 
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_inmmddyy($str_new_bill_date)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_customer_service_rep ."&nbsp;</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2' align='center'><input type='radio' name='cancel_status".$i."' value='accept'></td>";
					 $str_data .= "<td bgcolor='#E2E2E2' align='center'><input type='radio' name='cancel_status".$i."' value='reject'></td>";
					 $str_data .= "</tr>";
					 $str_data .= "<input type='hidden' name='hid_trans_id".$i."' value='".$i_transid."'>";
					 $str_data .= "<input type='hidden' name='hid_note_id".$i."' value='".$i_noteid."'>";
					 $str_data .= "<input type='hidden' name='payment_type".$i."' value='".$str_payment_type."'>";
					 $str_data .= "<input type='hidden' name='bank_routing_code".$i."' value='".$str_bank_routing_code."'>";
					 $str_data .= "<input type='hidden' name='company_id".$i."' value='".$i_company_id."'>";
		}					
		?>
			<form name="frm_cancel_requests" action="updatecancelrequests.php" method="post">
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="98%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Cancel Requests Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">

				<tr>
				  <td colspan="6"><font size="1" face="Verdana" ><strong>Total Records :
					<?=$i_count; ?></strong></font>
				  </td>
			   </tr>
			   <?= $str_data?>
			   <input type="hidden" name="hid_count" value="<?=$i_count?>">
	<tr>
		<td colspan="14" align="center" valign="middle" height="50"><a href="cancelrequests.php"><img border="0" src="../images/back.jpg"></a>&nbsp;<input type="image" border="0" src="../images/submit.jpg"></td>
		</tr>
		</table>							
		</td>
		</tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
		</table>							
	<?php
		}
	}  
}				 
?>
   </td>
  </tr>
</table> 
<input type="hidden" name="hid_report" value="report">
	</form>

<?php
include("includes/footer.php");
?>
