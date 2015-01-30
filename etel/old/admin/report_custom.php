<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
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
// Report_customer.php:. 
$allowBank=true;
include("includes/sessioncheck.php");


$headerInclude = "customerservice";
include("includes/header.php");

include("includes/message.php");

$Transtype = isset($_REQUEST['trans_type'])?quote_smart($_REQUEST['trans_type']):"";
$companytype = isset($_REQUEST['companymode'])?$_REQUEST['companymode']:"A";
$companytrans_type = isset($_REQUEST['companytrans_type'])?quote_smart($_REQUEST['companytrans_type']):"A";
$company_name = isset($_REQUEST['companyname'])?$_REQUEST['companyname']:"";
	
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails as C where 1 $bank_sql_limit order by companyname";

if ($Transtype == "Submit")  {
	if($companytype == "AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype == "NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype == "RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype == "ET") {
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
		$str_total_query = "and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails as C where 1 $str_total_query $bank_sql_limit order by companyname";
}
//print($qrt_select_companies);
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>$qrt_select_companies");

}
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_enquires;
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
	document.frm_enquires.trans_type.value="Submit";
	document.frm_enquires.action = "report_custom.php";
	document.frm_enquires.submit();
}

</script>
<?php
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
	
$i_from_year = (isset($_REQUEST["opt_from_year"])?quote_smart($_REQUEST["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_REQUEST["opt_from_month"])?quote_smart($_REQUEST["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_REQUEST["opt_from_day"])?quote_smart($_REQUEST["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_REQUEST["opt_to_year"])?quote_smart($_REQUEST["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_REQUEST["opt_to_month"])?quote_smart($_REQUEST["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_REQUEST["opt_to_day"])?quote_smart($_REQUEST["opt_to_day"]):$i_to_day);
$str_duplicates = (isset($_REQUEST["chk_duplicates"])?quote_smart($_REQUEST["chk_duplicates"]):"");
$str_cancelled_before_billed = (isset($_REQUEST["chk_cancelled_before_billed"])?quote_smart($_REQUEST["chk_cancelled_before_billed"]):"");
$str_cancelled_after_billed = (isset($_REQUEST["chk_cancelled_after_billed"])?quote_smart($_REQUEST["chk_cancelled_after_billed"]):"");

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($_REQUEST["opt_from_year"])?quote_smart($_REQUEST["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_REQUEST["opt_from_month"])?quote_smart($_REQUEST["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_REQUEST["opt_from_day"])?quote_smart($_REQUEST["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_REQUEST["opt_to_year"])?quote_smart($_REQUEST["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_REQUEST["opt_to_month"])?quote_smart($_REQUEST["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_REQUEST["opt_to_day"])?quote_smart($_REQUEST["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";
//$iCount =  func_sel_count($str_from_date,$str_to_date);
?>
<?php
			 
		if ($Transtype != "showResult")
		{
?>
			<form name="frm_enquires" action="report_custom.php" method="post">
			<input type="hidden" name="trans_type" value="showResult">
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Found Calls</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">  
			<tr>
			   <td   height="10"  valign="middle" align="center" width="50%">
		  </td>
			</tr>
			<tr>
			<td   height="30"  valign="middle" align="center" width="50%"> 
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
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,280,document.frm_enquires.from_date)">
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
		  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,320,document.frm_enquires.from_to)">
		  </td>
		  </tr>
		 <tr>
		    <td height="30" valign="middle" align="center">
			<table align="center" width="70%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			 <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
			<?php print func_select_mailcompanytype($companytype); ?>
			</select></td>
			</tr>
			<tr>
			 <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Merchant Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
			<?php print func_select_companytrans_type($companytrans_type); ?>
			</select></td>
			</tr>
			<tr>
			 <td height="60" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Name </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
			<?php print func_multiselect_transaction($qrt_select_companies); ?>
			</select></td>
			</tr>
			</table>
		  </td>
		 </tr>
		 <tr>
		    <td valign="middle" align="left" height="30"> 
			<table style="margin-left:43">
			<tr>
			  <td><font face="verdana" size="1">Refunded Before Billed</font>
				<input type="checkbox" name="chk_cancelled_before_billed" value="Y">
			  </td>
			<td>&nbsp;&nbsp;&nbsp;<font face="verdana" size="1">Refunded After Billed</font>
			  <input type="checkbox" name="chk_cancelled_after_billed" value="Y">
			</td>
		  </tr>
		  </table>
		  </td>
		  </tr>		  		  
		 <tr>
			<td width="318" align="center"  height="30"><font face="verdana" size="1">Duplicates</font>
			<input type="checkbox" name="chk_duplicates" value="Y">
			</td>
		 </tr>
		   <tr>
		    <td height="35" valign="middle" align="center"><input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
		</td>
		</tr>
		</table> 
		</td>
		</tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
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
		$str_where_condition = "where 1";
		if ($str_company_ids) $str_where_condition = "where C.userId in ($str_company_ids)";
	}
	
	if ($str_from_date != "")
		{
		   if($str_duplicates == "Y")
		   {
				$qry_select = "select C.companyname,B.phonenumber, count( B.phonenumber ) as num_calls";
				$qry_select .= " from cs_callnotes A, cs_transactiondetails B, cs_companydetails C ";
				$qry_select .= $str_where_condition == "" ? " where A.cn_type = 'foundcall' $bank_sql_limit and " : $str_where_condition ." and ";
				$qry_select .= " A.transaction_id = B.transactionId and B.userid = C.userid and C.gateway_id = -1";
				$qry_select .= " and A.call_date_time >= '".$str_from_date."'";
				if($str_to_date != "")
				{
					$qry_select .= " and A.call_date_time <= '".$str_to_date."'";
				}
				if($str_cancelled_before_billed == "Y" && $str_cancelled_after_billed == "Y")
			    {
					$qry_select .= " and B.cancelstatus = 'Y'";
				}
				else
			    {
					if($str_cancelled_before_billed == "Y")
					{
						$qry_select .= " and B.cancelstatus = 'Y' and B.cancellationDate < B.billingDate";
					}
					if($str_cancelled_after_billed == "Y")
					{
						$qry_select .= " and B.cancelstatus = 'Y' and B.cancellationDate >= B.billingDate and status = 'A'";
					}
			    }
				$qry_select .= "$bank_sql_limit group by B.phonenumber order by C.companyname, num_calls desc, B.phonenumber asc";

				$rssel_report = mysql_query($qry_select);
				//$i_count = mysql_num_rows($rssel_report);
				$str_report1 = "";
	?>
				<?
					$i_count = 0;
					$str_temp_company = "";
					for($i=0;$i<mysql_num_rows($rssel_report);$i++)
					{
						$str_company = mysql_result($rssel_report,$i,0);
						$str_phone	= mysql_result($rssel_report,$i,1);
						$i_call_count = mysql_result($rssel_report,$i,2);
						if($i_call_count > 1)
						{
							 if ($str_temp_company != $str_company) {
								  $str_temp_company = $str_company;
								  $i_count = 0;
								  $str_report1 .= "<tr><td colspan='4' align='center' height='40'><font face='verdana' size='1'><b>$str_company</b></font></td></tr>";
								  $str_report1 .= "<tr>";
								  $str_report1 .= "<td width='1%' bgcolor='#CCCCCC' height='30'><span class='subhd'>No.</span></td>";
								  $str_report1 .= "<td width='16%' bgcolor='#CCCCCC' height='30'><span class='subhd'>Company</span></td>";
								  $str_report1 .= "<td width='9%' bgcolor='#CCCCCC'><span class='subhd'>Phone Number</span></td>";
								  $str_report1 .= "<td width='9%' bgcolor='#CCCCCC'><span class='subhd'>Number of Calls</span></td>";
								  $str_report1 .= "</tr>";
							 }
							 $i_count++;
							 $str_report1 .= "<tr>";
							 $str_report1 .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$i_count</font></td>";
							 $str_report1 .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana' >$str_company</font></td>";
							 $str_report1 .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_phone</font></td>";
							 $str_report1 .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$i_call_count&nbsp;</font></td>";
							 $str_report1 .= "</tr>";
						}
					}
					if ($str_report1 == "")
					{
						$msgtodisplay="No Found Calls for this period.";
						$outhtml="y";				
						message($msgtodisplay,$outhtml,$headerInclude);									
						exit();	   
					}

			?>
			<form name="frm_enquires" action="report_custom.php" method="post">
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="75%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Duplicate Call Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
			<?= $str_report1;?>
			<tr>
			<td colspan="14" align="center" valign="middle" height="50"><a href="report_custom.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td>
			</tr>
			</table>							
			</td>
			</tr>
			<tr>
			<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
			<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
			<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
			</tr>
			</table>							
		<?php
		   }
		   else
		   {

			   $str_data = "";
			   $arr_total_duration = Array();
			   $qry_select="Select A.note_id ,A.transaction_id ,A.call_date_time,A.service_notes,B.cancelstatus,A.solved,A.is_bill_date_changed,A.call_duration,A.customer_service_id,A.prev_bill_date,B.phonenumber,B.billingDate,B.name,B.surname,C.companyname,B.cancellationDate,B.status,B.reference_number from  cs_callnotes A,cs_transactiondetails B,cs_companydetails C ";
			    $qry_select .= $str_where_condition == "" ? " where " : $str_where_condition ."  and ";
			    $qry_select .= " A.cn_type = 'foundcall' $bank_sql_limit and A.transaction_id = B.transactionId and B.userId = C.userId and C.gateway_id = -1 and A.call_date_time >= '".$str_from_date."'";
			    if($str_to_date != "")
				{
					$qry_select .= " and A.call_date_time <= '".$str_to_date."'";
				}
				if($str_cancelled_before_billed == "Y" && $str_cancelled_after_billed == "Y")
			    {
					$qry_select .= " and B.cancelstatus = 'Y'";
				}
				else
			    {
					if($str_cancelled_before_billed == "Y")
					{
						$qry_select .= " and B.cancelstatus = 'Y' and B.cancellationDate < B.billingDate";
					}
					if($str_cancelled_after_billed == "Y")
					{
						$qry_select .= " and B.cancelstatus = 'Y' and B.cancellationDate >= B.billingDate and status = 'A'";
					}
			    }
				$qry_select .= " order by C.companyname, A.call_date_time desc";
				//print($qry_select);die();
				$rssel_report = mysql_query($qry_select) or dieLog(mysql_error()."<BR>$qry_select");
				$i_count = mysql_num_rows($rssel_report);
				if ($i_count==0)
				{
					$msgtodisplay="No Found Calls for this period.";
					$outhtml="y";				
					message($msgtodisplay,$outhtml,$headerInclude);									
					exit();	   
				}
				if (mysql_num_rows($rssel_report)>0)
				{
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
					$str_approval_status = mysql_result($rssel_report,$i,16);
					$i_transref = mysql_result($rssel_report,$i,17);
					$str_customer_service_rep = "";
					$str_new_date = "";
					$str_prev_bill_date = $str_prev_bill_date == "0000:00:00" ? "" : func_get_date_inmmddyy($str_prev_bill_date);
					if($str_prev_bill_date != "")
					{
						$str_new_date = $str_bill_date_changed == "Y" ? func_get_date_inmmddyy($str_new_bill_date) : "Unchanged"; 

					}
					if($i_customer_service_id == 0)
					{
						$str_customer_service_rep = "service";
					}
					else
					{
						$str_customer_service_rep = func_get_value_of_field($cnn_cs,"cs_customerserviceusers","username","id",$i_customer_service_id);
					}
					$arr_total_duration[$i] = $str_call_duration;
					$str_cancelled_after_bill_date = "";
					$str_call_date = substr($str_calldatetime,0,10);
					if($str_cancel_status == "Cancelled" && ($str_cancellation_date >= $str_new_bill_date))
					{
						if($str_approval_status == "A")
						{
							$str_cancelled_after_bill_date = "style='border:1 solid red'";
						}
						else if($str_approval_status == "D")
						{
							$str_cancelled_after_bill_date = "style='border:1 solid orange'";
						}
						else
						{
							$str_cancelled_after_bill_date = "style='border:1 solid brown'";
						}
					}
					if ($str_temp_company != $str_company) {
						  $str_temp_company = $str_company;
						  $i_num_record = 0;
						  $str_data .= "<tr><td colspan='13' align='center' height='30'><font face='verdana' size='1'><b>$str_company</b></font></td></tr>";
						  $str_data .= "<tr>";						  
						  $str_data .= "<td width='3%' bgcolor='#CCCCCC' height='30'><span class='subhd'>No.</span></td>";			
						  $str_data .= "<td width='4%' bgcolor='#CCCCCC'><span class='subhd'>Tran ID</span></td>";
						  $str_data .= "<td width='5%' bgcolor='#CCCCCC'><span class='subhd'>Customer Name</span></td>";
						  $str_data .= "<td width='6%' bgcolor='#CCCCCC'><span class='subhd'>Phone Number</span></td>";
						  $str_data .= "<td width='12%' bgcolor='#CCCCCC'><span class='subhd'>Call DateTime</span></td>";
						  $str_data .= "<td width='19%' bgcolor='#CCCCCC'><span class='subhd'>Service Notes</span></td>";
						  //$str_data .= "<td width='9%' bgcolor='#CCCCCC'><span class='subhd'>Prev. Bill Date</span></td>";
						  //$str_data .= "<td width='9%' bgcolor='#CCCCCC'><span class='subhd'>New Bill Date</span></td>";
						  $str_data .= "<td width='6%' bgcolor='#CCCCCC'><span class='subhd'>Order Cancel Status</span></td>";		 
						  //$str_data .= "<td width='5%' bgcolor='#CCCCCC'><span class='subhd'>Solved</span></td>";		
						  $str_data .= "<td width='7%' bgcolor='#CCCCCC'><span class='subhd'>Call Duration</span></td>";		 
						  $str_data .= "<td width='5%' bgcolor='#CCCCCC'><span class='subhd'>Customer Service Rep.</span></td>";		 
						 $str_data .= "</tr>";												 
					}
					 $str_data .= "<tr>";
					 $str_data .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana' >".(++$i_num_record)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' ><a href='viewreportpage.php?id=$i_transid'>$i_transref</a></font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_first_name&nbsp;$str_surname</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_phone</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_time_12hr($str_calldatetime)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >";
					 if ($str_notes == "") 
					 {
						$str_data .= "&nbsp;";
					 }
					 else
					 {
						$str_data .= "$str_notes";
					 }								
					 $str_data .= "</font></td>";		 
					 //$str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_prev_bill_date</font></td>";
					 //$str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_new_date</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2' >";
						  $str_data .= "<table cellpadding='0' cellspacing='0'><tr><td $str_cancelled_after_bill_date>";
						  $str_data .= "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$str_cancel_status</font>";
						  $str_data .= "</td></tr>";
						  $str_data .= "</table>";		 
					 $str_data .= "</td>";
					 //$str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_solved</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_call_duration</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_customer_service_rep&nbsp;</font></td>";
					 $str_data .= "</tr>";
					}
					?>

				<form name="frm_enquires" action="report_custom.php" method="post">
				<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
				<tr>
				<td width="95%" valign="top" align="center">

				<table width="98%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
					  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Found Call Details</span></td>
				<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
				<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
				<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
				</tr>
				<tr>
				<td class="lgnbd" colspan="5">
				  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">

					<tr>
					  <td colspan="13">
					  <table cellspacing="0" cellpadding="0" style="margin-top:10px">
					  <tr>
					  <td>
					  <font size="1" face="Verdana" ><strong>Color Code for Orders Refunded after set to bill date:&nbsp;&nbsp;
					  </strong></font>
					  </td>
					  <td style='border:1 solid red' width="50" height="10"><font size='1' face='Verdana'>&nbsp;</font></td>
					  <td><font size="1" face="Verdana">&nbsp;Approved&nbsp;&nbsp;&nbsp;</font></td>
					  <td style='border:1 solid orange' width="50" height="10"><font size='1' face='Verdana'>&nbsp;</font></td>
					  <td><font size="1" face="Verdana">&nbsp;Declined&nbsp;&nbsp;&nbsp;</font></td>
					  <td style='border:1 solid brown' width="50" height="10"><font size='1' face='Verdana'>&nbsp;</font></td>
					  <td><font size="1" face="Verdana">&nbsp;Pending Approval</font></td>
					  </tr>
					  </table>
					  </td>
				   </tr>	  
					<tr>
					  <td colspan="13" height="10"><font size="1" face="Verdana" ><strong>Total Records are :
						<?=$i_count; ?>&nbsp;&nbsp;&nbsp;Total Call Duration : <?php print(func_get_total_call_duration($arr_total_duration)); ?></strong></font>
					  </td>
				   </tr>	  
				
				<?php print($str_data);?>

				<tr>
				<td colspan="14" align="center" valign="middle" height="50"><a href="report_custom.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td>
				</tr>
				</table>							
				</td>
				</tr>
				<tr>
				<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
				<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
				<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
				</tr>
				</table>							
	<?php
				   }
		   }
		 }  
}				 
?>
   </td>
  </tr>
</table> 
	</form>

<?php
include("includes/footer.php");
?>
<?php
	
	function func_sel_count($str_from_date,$str_to_date)
	{
		$qry_count = "SELECT count(*) from  cs_callnotes where  call_date_time >= '".$str_from_date."' AND call_date_time  <= '".$str_to_date."'";
		$rssel_count = mysql_query($qry_count);
		if (mysql_num_rows($rssel_count)>0)
		{
			$i_count = mysql_result($rssel_count,0,0);
			
		}
		else
		{
			$i_count = 0;
		}
		return $i_count;	
			
		  
	}	
				
?>