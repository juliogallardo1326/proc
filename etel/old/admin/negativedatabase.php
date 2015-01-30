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
// negativeDatabase.php:. 
include("includes/sessioncheck.php");

$headerInclude = "transactions";
include("includes/header.php");

//$headerInclude="companies";


?>
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
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;

$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?quote_smart($HTTP_POST_VARS['companymode']):"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
$companyname = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
if ($Transtype != "showResult")
{

$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";

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
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != ""){
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
	$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
}
//print($qrt_select_companies);
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
?>

<script language="JavaScript" type="text/JavaScript">
function func_submit(i_id)
{
	obj_form = document.frm_negative_database;
	obj_form.method="post";
	obj_form.action="viewreportpage_negative.php?id="+i_id;
	obj_form.submit();
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_negative_database;
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
	document.frm_negative_database.trans_type.value="Submit";
	document.frm_negative_database.action = "negativedatabase.php";
	document.frm_negative_database.submit();
}

function validation() {
	var obj_element = document.frm_negative_database.elements[13];
		if(obj_element.value == "")
		{
			alert("Please select the company");
			obj_element.focus();
			return false;
		}
		
	return true;
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
<tr>
   <td width="95%" valign="top" align="center" ><br>
<form name="frm_negative_database" action="negativedatabase.php" method="post" onsubmit="return validation();">
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		     <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Negative Database</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
		<input type="hidden" name="trans_type" value="showResult">
	  <table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<br>
	   <tr>
		  <td   height="30" valign="middle"   align="right" width="40%"><font face="verdana" size="1">Bill Start Date</font></td><td align="left" width="60%"  height="30" >&nbsp;
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select></font>
			 <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		   <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(660,280,document.frm_negative_database.from_date)">
		   </td>
		</tr>
        <tr>
		  <td   height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">Bill End Date</font></td><td align="left" width="60%"  height="30"  >&nbsp;
			  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
			  </select>
			<select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
			  </select>
			  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
			  </select>
			  <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
			  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(660,310,document.frm_negative_database.from_to)">
		 </td>   
        </tr>
		<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="0" width="100%">
				<tr>
				 <td height="30" valign="middle" align="right"  width="40%"><font face="verdana" size="1">Company Type</font></td>
				 <td align="left"  width="400">&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
				</select></td>
				</tr>
				<tr>
				 <td height="30" valign="middle" align="right"  width="40%"><font face="verdana" size="1">Merchant Type</font></td>
				 <td align="left"  width="400">&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
				</select></td>
				</tr>
				<tr><td colspan="2" align="center" height="65">
				<table width="100%"  cellpadding="0" cellspacing="0"><tr>
				<td valign="middle" align="right" width="40%"><font face="verdana" size="1">Select Company</font></td>
				 <td align="left" >&nbsp;&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
				</td></tr></table>
				</td></tr>
			</table>
			</td></tr>	
	<input type="hidden" name="hid_enquiry" value="negative">
	<tr>
	 <td  height="50"  valign="middle" align="center"colspan='2'>
		 <input type="image" id="reportview" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
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
<? } else {
if ($str_from_date != "")
{
	$qry_select="select transactionId,name,surname,checkorcard,amount,a.userId,status,cancelstatus,reason,other,voiceAuthorizationno from cs_transactiondetails a, cs_companydetails b where a.userId = b.userId and a.passStatus = 'ND' and b.gateway_id = -1 and a.billingDate >= '".$str_from_date." 00:00:00'";
	if($str_to_date != "")
	{
		$qry_select .= " AND a.billingDate <= '".$str_to_date." 23:59:59'";			
	}
	$qry_select_companies = "";
	if($companyname != "")
	{
		if($companyname[0]== "A" && $companytype== "A") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "";
			} else {
				$qry_select_companies = "and b.transaction_type = '$companytrans_type' ";
			}
		}
		else if($companyname[0]== "A"  && $companytype== "AC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "and b.activeuser=1 ";
			} else {
				$qry_select_companies = "and b.transaction_type = '$companytrans_type' and b.activeuser=1 ";
			}
		}
		else if($companyname[0]== "A" &&  $companytype== "NC") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "and b.activeuser=0 ";
			} else {
				$qry_select_companies = "and b.transaction_type = '$companytrans_type' and b.activeuser=0 ";
			}
		}
		else if($companyname[0]== "A" &&  $companytype== "RE") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "and b.reseller_id <> '' ";
			} else {
				$qry_select_companies = "and b.transaction_type = '$companytrans_type' and b.reseller_id <> '' ";
			}
		}
		else if($companyname[0]== "A" &&  $companytype== "ET") 
		{
			if ($companytrans_type == "A") {
				$qry_select_companies = "and b.reseller_id is null ";
			} else {
				$qry_select_companies = "and b.transaction_type = '$companytrans_type' and b.reseller_id is null ";
			}
		}
		 else 
		{
		   $str_company_ids = "";
		   for($i_loop=0;$i_loop<count($companyname);$i_loop++)
		   {
				$str_company_ids .= $companyname[$i_loop] . ",";
		   }
		   $str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 1);
		   if ($str_company_ids != "") {
			   $qry_select_companies = "and b.userId in ($str_company_ids)";
		   }
		}
	}
		$qry_select .= " ".$qry_select_companies;
		$qry_select .= " Order by a.billingDate asc";
		$rstSelect = mysql_query($qry_select);
		if (mysql_num_rows($rstSelect)==0)
		{					
		$msgtodisplay="No Negative Database Reports for this period.";
		$outhtml="y";				
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();	   
		}

		if (mysql_num_rows($rstSelect)>0)
		{
?>
			<br>
			<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Negative Database Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5"><br>
			<table  cellpadding='0' cellspacing='0' width='100%'  align="center" ID='Table1'>
					<tr height='20' bgcolor='#CCCCCC'>
						<td align='left' class='cl1'><span class="subhd">Transaction Id</span></td>
						<td align='left' class='cl1'><span class="subhd">Company</span></td>
						<td align='left' class='cl1'><span class="subhd">First name</span></td>
						<td align='left' class='cl1'><span class="subhd">Last name</span></td>
						<td align='left' class='cl1'><span class="subhd">Type</span></td>
						<td align='left' class='cl1'><span class="subhd">Amount</span></td>
						<td align='center' class='cl1'><span class="subhd">Approval Status</span></td>
						<td align='left' class='cl1'><span class="subhd">Cancelled </span></td>
						<td align='left' class='cl1'><span class="subhd">Cancellation Reason</span></td>
					</tr>
					<?php
						for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++)
						{ 
							$iTransactionId = mysql_result($rstSelect,$iLoop,0);
							$strFirstName = mysql_result($rstSelect,$iLoop,1);
							$strLastName = mysql_result($rstSelect,$iLoop,2);
							$strType = mysql_result($rstSelect,$iLoop,3);
							if($strType == "C"){
								$strType = "Check";
							}else{
								$strType = "Credit Card";
							}	
							$strAmount = mysql_result($rstSelect,$iLoop,4);
							$strAmount = formatMoney($strAmount);
							$strCompany = mysql_result($rstSelect,$iLoop,5);
							$strCompany = funcGetValueByQuery(" select companyname from cs_companydetails where userId=$strCompany ",$cnn_cs);
							$strPendingStatus = mysql_result($rstSelect,$iLoop,6);
							$strCancelled = mysql_result($rstSelect,$iLoop,7);
							$strCancellReason = mysql_result($rstSelect,$iLoop,8);
							$strCancellOther = mysql_result($rstSelect,$iLoop,9);
							$strVoiceAuthorisation = mysql_result($rstSelect,$iLoop,10);
							?>
							<tr height='30' >
								<td align='center'  class='cl1'><a href="javascript:func_submit('<?=$iTransactionId?>');" class="link1"><font face='verdana' size='1'>&nbsp;<?=$strVoiceAuthorisation ?></font></a></td>
								<td align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<?= $strCompany ?></font></td>
								<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?= $strFirstName ?></font></td>
								<td align='left' class='cl1' ><font face='verdana' size='1'>&nbsp;<?= $strLastName?></font></td>
								<td align='left' class='cl1'><font face='verdana' size='1'>&nbsp;<?= $strType?></font></td>
								<td align='right' class='cl1'><font face='verdana' size='1'>&nbsp;<?= $strAmount ?></font></td>
								<td align='center' class='cl1'>
								<?php 
									if($strPendingStatus == "A")
										print("<font face='verdana' size='1'>Approved</font>");
									else if($strPendingStatus == "D")
										print("<font face='verdana' size='1'>Declined</font>");
									else if($strPendingStatus == "P")
										print("<font face='verdana' size='1'>Pending</font>");
							    ?>
								</td>
								<td align='center' class='cl1'>
								<?php
									if ($strCancelled == "N"){
										echo("&nbsp;");
									}
									else{							
										echo("<font face='verdana' size='1'>Cancelled</font>");
									}
								?>
								</td>
								<td align='left' class='cl1'>
								<?php
									if ($strCancelled == "N"){
										echo("&nbsp;");
									}
									else{							
										 if($strCancellOther != "")
											print("<font face='verdana' size='1'>".$strCancellOther."&nbsp;</font>"); 
										 else
											print("<font face='verdana' size='1'>".$strCancellReason."&nbsp;</font>"); 
									} ?>
								</td>
								</tr>
								<input type="hidden" name="hdId<?=$iLoop?>" value="<?=$iTransactionId?>">
							<?php	
								}
							?>
							<tr>
							<td colspan="14" align="center" valign="middle" height="50"><a href="negativedatabase.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td>
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
	<br>
<?php
         }
		 
	 }
?>
	<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
	<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
	<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
	<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
	<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
	<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
 <?php
 } 
?>
</form>
</td>
</tr>
</table>
	
<?php
include("includes/footer.php");
?>