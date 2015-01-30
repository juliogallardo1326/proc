<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');
include '../includes/function1.php';
$headerInclude="transactions";
include 'includes/header.php';


?>
<?php
$i_num_records_per_page = (isset($HTTP_POST_VARS["cbo_num_records"])?quote_smart($HTTP_POST_VARS["cbo_num_records"]):"20");
$i_lower_limit = (isset($HTTP_POST_VARS["lower_limit"])?quote_smart($HTTP_POST_VARS["lower_limit"]):"0");
if($i_lower_limit < 0)
	$i_lower_limit = 0;
?>
<script language="JavaScript" type="text/JavaScript">
var num_records = "<?= $i_num_records_per_page?>";
var lower_limit = 0;
function func_submit(i_id)
{
	obj_form = document.frmResult;
	obj_form.method="post";
	obj_form.lower_limit.value="<?= $i_lower_limit ?>";
	obj_form.action="viewgatewayreportpage.php?id="+i_id;
	obj_form.submit();
}

function updateTransaction()
{
	obj_form = document.frmResult;
	obj_form.method="post";
	obj_form.lower_limit.value="<?= $i_lower_limit ?>";
	return true;
	//obj_form.submit();
}

function showPreviousPage()
{
	obj_form = document.frmResult;
	num_records = obj_form.cbo_num_records[obj_form.cbo_num_records.selectedIndex].value;
	lower_limit = parseInt(<?= $i_lower_limit?>) - parseInt(num_records);
	obj_form.method="post";
	obj_form.lower_limit.value=lower_limit;
	obj_form.task.value="Previous";
	obj_form.action="viewGatewayTransactions.php";
	obj_form.submit();
}

function showNextPage()
{
	obj_form = document.frmResult;
	lower_limit = parseInt(<?= $i_lower_limit?>) + parseInt(num_records);
	obj_form.method="post";
	obj_form.lower_limit.value=lower_limit;
	obj_form.task.value="Next";
	obj_form.action="viewGatewayTransactions.php";
	obj_form.submit();
}

function setNumRecords()
{
	obj_form = document.frmResult;
}
</script>

<?php

// $txtDate = (isset($HTTP_POST_VARS["txtDate"])?quote_smart($HTTP_POST_VARS["txtDate"]):"");
// $txtDate1 = (isset($HTTP_POST_VARS["txtDate1"])?quote_smart($HTTP_POST_VARS["txtDate1"]):"");

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$is_ecommerce = true;

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$gatewayAdminId = isset($HTTP_POST_VARS['gatewayCompanies'])?$HTTP_POST_VARS['gatewayCompanies']:"";
$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
if($companyid == "") {
	$outhtml="y";
	$msgtodisplay="Please select a company";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
} 
	$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
	$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";

  $trans_total_count=0;
  $i_count = 0;
//  print $companyids;
 // exit();

	$str_or_query = "";
	$strConditions = "";

	$qrySelect = "select transactionId,name,surname,checkorcard,amount,a.userid,passStatus,status,cancelstatus,reason,other,voiceAuthorizationno,nopasscomments,declinedReason,transactionDate,service_user_id,bankroutingcode,billingDate,company_usertype,company_user_id,a.reference_number,currencytype from cs_transactiondetails ";		
	$qry_select_total ="select sum(amount),count(*) from cs_transactiondetails ";
	$qry_select_approvedtotal ="select sum(amount),count(*) from cs_transactiondetails ";
	$qry_istemarketing ="select transactionId from cs_transactiondetails";
	
	
	$qrt_select_usd="select sum(amount)from cs_transactiondetails ";
	$qrt_select_aud="select sum(amount)from cs_transactiondetails ";	
	$qrt_select_gbp="select sum(amount)from cs_transactiondetails ";
	$qrt_select_eur="select sum(amount)from cs_transactiondetails ";
	$qrt_select_usdapproved="select sum(amount)from cs_transactiondetails ";
	$qrt_select_audapproved="select sum(amount)from cs_transactiondetails ";	
	$qrt_select_gbpapproved="select sum(amount)from cs_transactiondetails ";
	$qrt_select_eurapproved="select sum(amount)from cs_transactiondetails ";
	$qrt_select_cad="select sum(amount)from cs_transactiondetails ";
	$qrt_select_cadapproved="select sum(amount)from cs_transactiondetails ";
	
	if($companyid !="A" && $gatewayAdminId !="A") {	
		$str_qryconcat	=" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id = $gatewayAdminId and a.userid=$companyid";
		$qrt_concat_total =" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id = $gatewayAdminId and a.userid=$companyid";
		//changed
		
		
		$qrt_concat_approvedtotal =" as a,cs_companydetails as b where a.userid=b.userid and status='A'and cancelstatus='N'and b.gateway_id = $gatewayAdminId and a.userid=$companyid";
		$qry_concat_tele = " as a,cs_companydetails as b where a.userid=b.userid and b.transaction_type='tele' and b.gateway_id = $gatewayAdminId and a.userid=$companyid";		
	} else if($companyid !="A" && $gatewayAdminId =="A"){
		$str_qryconcat	=" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id != -1 and a.userid=$companyid";
		$qrt_concat_total =" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id != -1 and a.userid=$companyid";
				
		//changed
		
		
		
		$qrt_concat_approvedtotal =" as a,cs_companydetails as b where a.userid=b.userid and status='A'and cancelstatus='N'and b.gateway_id != -1 and a.userid=$companyid";
		$qry_concat_tele = " as a,cs_companydetails as b where a.userid=b.userid and b.transaction_type='tele' and b.gateway_id != -1 and a.userid=$companyid";
	} else if($companyid =="A" && $gatewayAdminId !="A"){
		$str_qryconcat	=" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id = $gatewayAdminId ";
		$qrt_concat_total =" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id = $gatewayAdminId ";
		//changed
		
		
		$qrt_concat_approvedtotal =" as a,cs_companydetails as b where a.userid=b.userid and status='A'and cancelstatus='N' and b.gateway_id = $gatewayAdminId ";
		$qry_concat_tele = " as a,cs_companydetails as b where a.userid=b.userid and b.transaction_type='tele' and b.gateway_id = $gatewayAdminId ";
	} else {
		$str_qryconcat	=" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id != -1";
		$qrt_concat_total =" as a,cs_companydetails as b where a.userid=b.userid and b.gateway_id != -1";
		//changed
		
		$qrt_concat_approvedtotal =" as a,cs_companydetails as b where a.userid=b.userid and status='A'and cancelstatus='N' and b.gateway_id != -1";
		$qry_concat_tele =" as a,cs_companydetails as b where a.userid=b.userid and b.transaction_type='tele' and b.gateway_id != -1";
	}

			$qrt_select_usd.=$str_qryconcat." and (currencytype ='USD' or b.processing_currency='USD')  and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_aud.=$str_qryconcat. " and (currencytype ='AUD' or b.processing_currency='AUD')  and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_gbp.=$str_qryconcat. " and (currencytype ='GBP' or b.processing_currency='GBP')  and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_eur.=$str_qryconcat. " and (currencytype ='EUR' or b.processing_currency='EUR')  and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_cad.=$str_qryconcat. " and (currencytype ='CAD' or b.processing_currency='CAD')  and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_cadapproved.=$str_qryconcat."  and  (currencytype ='CAD' or b.processing_currency='CAD')and status='A' and cancelstatus='N'and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_usdapproved.=$str_qryconcat." and (currencytype ='USD' or b.processing_currency='USD')and status='A' and cancelstatus='N'and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_audapproved.=$str_qryconcat." and (currencytype ='AUD' or b.processing_currency='AUD')and status='A' and cancelstatus='N'and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_gbpapproved.=$str_qryconcat." and(currencytype ='GBP' or b.processing_currency='GBP')and status='A' and cancelstatus='N'and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
			$qrt_select_eurapproved.=$str_qryconcat." and (currencytype ='EUR' or b.processing_currency='EUR')and status='A' and cancelstatus='N'and(transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";



	$str_select_query = $qrySelect . $str_qryconcat . " and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC Limit $i_lower_limit,$i_num_records_per_page";
	$qrt_select_total = $qry_select_total . $qrt_concat_total . " and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
	//changed
	$qrt_select_approvedtotal = $qry_select_approvedtotal . $qrt_concat_approvedtotal . " and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC";
	$qry_select_istele = $qry_istemarketing . $qry_concat_tele . " and (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ORDER BY transactionDate DESC Limit $i_lower_limit,$i_num_records_per_page";
	//print($qrt_select_approvedtotal)."<br>";
	//print $qrt_select_total."<br>";
	//print $str_select_query;
//	exit();
//changed

	if(!($show_approvedtotal_val =mysql_query($qrt_select_approvedtotal)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	
	if(!($show_total_val =mysql_query($qrt_select_total)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(!($rstSelect = mysql_query($str_select_query,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	
	if(!($rstisteleSelect = mysql_query($qry_select_istele,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(mysql_num_rows($rstisteleSelect) >0) {
		$is_ecommerce = false;
	}
	if(!($rstSelectusd = mysql_query($qrt_select_usd,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelectaud = mysql_query($qrt_select_aud,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelecteur = mysql_query($qrt_select_eur,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelectgbp = mysql_query($qrt_select_gbp,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelectcad = mysql_query($qrt_select_cad,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
//changed

if(!($rstSelectusdapproved = mysql_query($qrt_select_usdapproved,$cnn_cs)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print("Cannot execute query in this");
		
		exit();
	} 
	if(!($rstSelectaudapproved = mysql_query($qrt_select_audapproved,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelecteurapproved = mysql_query($qrt_select_eurapproved,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelectgbpapproved = mysql_query($qrt_select_gbpapproved,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	if(!($rstSelectcadapproved = mysql_query($qrt_select_cadapproved,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	} 
	
	 $trans_total_amount = mysql_result($show_total_val,0,0);
	 $trans_total_count = mysql_result($show_total_val,0,1);
	 //changed
	 
	 $total_usd=mysql_result($rstSelectusd,0,0);
	$total_aud=mysql_result($rstSelectaud,0,0);
	$total_eur=mysql_result($rstSelecteur,0,0);
	$total_gbp=mysql_result($rstSelectgbp,0,0);
	$total_usdapproved=mysql_result($rstSelectusdapproved,0,0);
	$total_audapproved=mysql_result($rstSelectaudapproved,0,0);
	$total_eurapproved=mysql_result($rstSelecteurapproved,0,0);
	$total_gbpapproved=mysql_result($rstSelectgbpapproved,0,0);
	$total_cad=mysql_result($rstSelectcad,0,0);
	$total_cadapproved=mysql_result($rstSelectcadapproved,0,0);
	
	 $trans_approvedtotal_amount = mysql_result($show_approvedtotal_val,0,0);
	 $trans_approvedtotal_count = mysql_result($show_approvedtotal_val,0,1);
	 if($total_aud!=""){
	 	$str_aud="AUD :" .formatMoney($total_audapproved,2,'.',',')."/".number_format($total_aud);
	 }else{
	 	$str_aud="";
	 }
	 if($total_cad!=""){
	 	$str_cad="CAD :" .formatMoney($total_cadapproved,2,'.',',')."/".number_format($total_cad);
	 }else{
	 	$str_cad="";
	 }
	 if($total_eur!=""){
	 	$str_eur="EUR :" .formatMoney($total_eurapproved,2,'.',',')."/".number_format($total_eur);
	 }else{
	 	$str_eur="";
	 }
	 if($total_usd!=""){
	 	$str_usd="USD :" .formatMoney($total_usdapproved,2,'.',',')."/".number_format($total_usd);
	 }else{
	 	$str_usd="";
	 }
	 if($total_gbp!=""){
	 	$str_gbp="GBP :" .formatMoney($total_gbpapproved,2,'.',',')."/".number_format($total_gbp);
	 }else{
	 	$str_gbp="";
	 }
	 $i_upper_limit = ($i_lower_limit + $i_num_records_per_page) > $trans_total_count ? $trans_total_count : ($i_lower_limit + $i_num_records_per_page)
	 
 ?>
      <!-- Report starts from here -->
      <?php		
	if(mysql_num_rows($rstSelect)>0)
	{
	
	?>
<br>
	<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Transaction&nbsp;Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	<form name="frmResult" method="post" action="updategatewaytransactions.php" onSubmit="javascript:return updateTransaction();">
	<table  cellpadding='0' cellspacing='0' width='100%' border="0" valign="left" ID='Table1'>
		<tr><td colspan="15" align="right"><font face="verdana" size="2"><strong>No: of Records per Page:</strong></font> 
		<select style="font-size:9;font-face:verdana" name="cbo_num_records">
		<option value="20" <?= $i_num_records_per_page == 20 ? "selected" : ""?>>20</option>
		<option value="50" <?= $i_num_records_per_page == 50 ? "selected" : ""?>>50</option>
		<option value="100" <?= $i_num_records_per_page == 100 ? "selected" : ""?>>100</option>
		</select>&nbsp;&nbsp;
		</td></tr>
		<tr><td colspan="15"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><strong>
		<?php 	print "&nbsp;Total amount is : ".formatMoney($trans_approvedtotal_amount,2,'.',',')."/".number_format($trans_total_amount)." and total records are: $trans_approvedtotal_count/$trans_total_count are Approved, showing records ".($i_lower_limit + 1)." - $i_upper_limit"; ?>
		</strong></font></td></tr>
		<tr>
            <td colspan="15"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><strong> 
              <!-- changed -->
              <?php 	print "Amount in &nbsp;". $str_aud ."&nbsp;&nbsp;". $str_cad ."&nbsp;&nbsp;".$str_eur. "&nbsp;&nbsp;".$str_gbp. "&nbsp;&nbsp;".$str_usd   ; ?>
              </strong></font></td>
</tr>
					<tr height='20' bgcolor='#CCCCCC'>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Reference Number</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Service / Company</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">First name</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Last name</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Type</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Amount</span></td>
						<? if (!$is_ecommerce) {?>
							<td align='center' class='cl1' colspan='3'><span class="subhd">Voice Auth. status</span></td>
						<? } ?>
						<td align='center' class='cl1' colspan='2'><span class="subhd">Approval Status</span></td>
						<td align='left' class='cl1' rowspan='2'><span class="subhd">Cancelled </span></td>
						<td align='center' class='cl1' colspan="2"><span class="subhd">Reasons</span></td>
						<? if (!$is_ecommerce) {?>
							<td align='left' class='cl1' rowspan='2'><span class="subhd">No Pass Reason&nbsp;&nbsp;</span></td>
						<? } ?>
					</tr>
					<tr height='20' bgcolor='#CCCCCC'>
						<? if (!$is_ecommerce) {?>
							<td align='left'  class='cl1'><span class="subhd">Pend.</span></td>
							<td align='left'  class='cl1'><span class="subhd">Pass</span></td>
							<td align='left'  class='cl1'><span class="subhd">NoPass</span></td>
						<? } ?>
						<td align='left'  class='cl1'><span class="subhd">Appr.</span></td>
						<td align='left'  class='cl1'><span class="subhd">Decl.</span></td>
						<td align='center'  class='cl1'><span class="subhd">Cancelled</span></td>
						<td align='center'  class='cl1' width="100"><span class="subhd">Declined</span></td>
					</tr>
					<?php
						for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++)
						{ 
							$iTransactionId = mysql_result($rstSelect,$iLoop,0);
							$strFirstName = mysql_result($rstSelect,$iLoop,1);
							$strLastName = mysql_result($rstSelect,$iLoop,2);
							$strType = mysql_result($rstSelect,$iLoop,3);
							$str_payment_type = $strType;
							if($strType == "C"){
								$strType = "Check";
							}else{
								$strType = "Credit Card";
							}	
							$strAmount = mysql_result($rstSelect,$iLoop,4);
							$strAmount = formatMoney($strAmount);
							$iCompanyId = mysql_result($rstSelect,$iLoop,5);
							$strCompany = funcGetValueByQuery(" select companyname from cs_companydetails where userId=$iCompanyId ",$cnn_cs);
							$strPassStatus = mysql_result($rstSelect,$iLoop,6);
							$strPendingStatus = mysql_result($rstSelect,$iLoop,7);
							$strCancelled = mysql_result($rstSelect,$iLoop,8);
							$strCancellReason = mysql_result($rstSelect,$iLoop,9);
							$strCancellOther = mysql_result($rstSelect,$iLoop,10);
							$strVoiceAuthorisation = mysql_result($rstSelect,$iLoop,11);
							$strNoPassComment = mysql_result($rstSelect,$iLoop,12);
							$strDeclineComment = mysql_result($rstSelect,$iLoop,13);
							$strTransactiontime = mysql_result($rstSelect,$iLoop,14);
							$iServiceUserId = mysql_result($rstSelect,$iLoop,15);
							$strBillingDate = mysql_result($rstSelect,$iLoop,17);
							$iUserType = mysql_result($rstSelect,$iLoop,18);
							$iUserId = mysql_result($rstSelect,$iLoop,19);
							$ireferenceNumber = mysql_result($rstSelect,$iLoop,20);
							$processingcurrency=mysql_result($rstSelect,$iLoop,21);
							$strTransactionType = func_get_value_of_field($cnn_cs,"cs_companydetails","transaction_type","userid",$iCompanyId);
							if($processingcurrency==""){
							$processingcurrency=func_get_processing_currency($iCompanyId);
							if($processingcurrency=="")
							$processingcurrency="USD";
							}
							
							$str_user_type = "";
							//if($iUserId > 0)	
							//{
								if($iUserType == 1) 	
								{
									$str_user_type = "TSR user";
								}
								else if($iUserType == 2)
								{
									$str_user_type = "Call center";	
								}
								else if($iUserType == 3)
								{
									$str_user_type = "Websites";	
								}
								else if($iUserType == 4)
								{
									$str_user_type = "Website orders";	
								}
								else if($iUserType == 5)
								{
									$str_user_type = "Batch ";								
								}
								else if($iUserType == 6)
								{
									$str_user_type = "Recurring ";								
								}
								else if($iUserType == 7)
								{
									$str_user_type = "Rebilling";								
								}
								
							//}
								else
									$str_user_type = "VT ";

							$strServiceUser = "";
							if($iServiceUserId != "")
							{
								if($iServiceUserId == 0)
								{
									$strServiceUser = "service";
								}
								else
								{
									$strServiceUser = func_get_value_of_field($cnn_cs,"cs_customerserviceusers","username","id",$iServiceUserId);
								}
							}
							
							 ?>
							<tr height='30' >
								<td align='center'  class='cl1'><font face='verdana' size='1'><a href="javascript:func_submit('<?=$iTransactionId?>');" class="link1">&nbsp;<?=$ireferenceNumber ?></a><br><?php print func_get_date_time_12hr($strTransactiontime);?></font></td>
								<td align='left' class='cl1'><font face='verdana' size='1'>
								<?php 
								if($str_user_type)
								{
									echo  $str_user_type."/ <br>" ;
								}
								?>
								 <?= $strServiceUser != "" ? $strServiceUser." / ".$strCompany : $strCompany?> 
															
								</font>&nbsp;</td>
								<td align='left' class='cl1' ><font face='verdana' size='1'><?= $strFirstName ?></font></td>
								<td align='left' class='cl1' ><font face='verdana' size='1'><?= $strLastName?></font></td>
								<td align='left' class='cl1'><font face='verdana' size='1'><?= $strType?></font></td>
								<td align='right' class='cl1'><font face='verdana' size='1'>(<?= $processingcurrency?>)&nbsp;<?= $strAmount ?></font></td>
								<? if (!$is_ecommerce) {?>
									<td align='center' class='cl1'>
									<?php
										if ($strPassStatus != "ND" && $strTransactionType == "tele"){
											if($strPassStatus == "PE"){
												echo("<input type='radio' name='radpass$iLoop' value='PE' checked>");
											}else{
												echo("<input type='radio' name='radpass$iLoop' value='PE'>");
											}
										}
										else{
											echo("&nbsp;");
										}	
									?>
									</td>
									<td align='center' class='cl1'>
									<?php
										if ($strPassStatus != "ND" && $strTransactionType == "tele"){
											if($strPassStatus == "PA"){
												echo("<input type='radio' name='radpass$iLoop' value='PA' checked>");
											}else{
												echo("<input type='radio' name='radpass$iLoop' value='PA'>");
											}
										}
										else{
											echo("&nbsp;");
										}
									?>	
									</td>
									<td align='center' class='cl1'>
									<?php
										if ($strPassStatus != "ND" && $strTransactionType == "tele"){
											if($strPassStatus == "NP"){
												echo("<input type='radio' name='radpass$iLoop' value='NP' checked>");
											}else{
												echo("<input type='radio' name='radpass$iLoop' value='NP'>");
											}
										}
										else{
											echo("&nbsp;");
										}	
									?>
									</td>
								<? } ?>
								<td align='center' class='cl1'>
								<?php
								if ($strPassStatus != "ND"){								
									if($strPassStatus == "PA"){
										if($strPendingStatus == "A"){
											echo("<input type='radio' name='radpending$iLoop' value='A' checked>");
										}else{
											echo("<input type='radio' name='radpending$iLoop' value='A'>");
										}
									}else{
										print("&nbsp;");
									}
								}
								else{
									if($strPendingStatus == "A"){
										echo("<img src='../images/tick.gif'  border='0'>");
									}else{
										echo("&nbsp;");
									}
								}		
								?>
								</td>
								<td align='center' class='cl1'>
								<?php
								if ($strPassStatus != "ND"){
									if($strPassStatus == "PA" || $strPassStatus == "PE"){
										if($strPendingStatus == "D" || $strPendingStatus == "P"){
											echo("<input type='radio' name='radpending$iLoop' value='D' checked>");
										}else{
											echo("<input type='radio' name='radpending$iLoop' value='D'>");
										}
									}else{
										print("&nbsp;");
									}
								}
								else{
									if($strPendingStatus == "D" || $strPendingStatus == "P"){
										echo("<img src='../images/tick.gif'  border='0'>");
									}else{
										echo("&nbsp;");
									}
								}	
								?>
								</td>
								<td align='center' class='cl1'>
								<?php
									if ($strPassStatus == "ND" && $strCancelled == "N"){
										echo("&nbsp;");
									}
									else{							
										if($strCancelled == "Y"){
											echo("<font face='verdana' size='1'>Cancelled</font>");
										}else{
											echo("<input type='checkbox' name='chk$iLoop' value='C'>");
										}	
									}
								?>
								</td>
								<td align='left' valign="top" class='cl1'>
								<?php
									if ($strPassStatus == "ND" && $strCancelled == "N"){
										echo("&nbsp;");
									}
									else{							
								?><select name="optReason<?=$iLoop?>" style="font-size:9;font-face:verdana">
										<option value="">Select Reason</option>
										<?php funcFillCancellationReason($strCancellReason,$strType)?></select><br>
										<textarea name="txt<?=$iLoop ?>" rows="3" cols="17" style="font-size:10;font-family:verdana"><?= $strCancellOther ?></textarea><?php }?></td>
								<td align='left' valign="top" class='cl1' width="100">
								<select name="declineReason<?=$iLoop?>" style="font-size:9;font-face:verdana">
											<option value="">Select Reason</option>
											<?php funcFillDeclineReason($strDeclineComment,$strType)?></select><br><font face='verdana' size='1' color="#FF0000"><?=$strDeclineComment?></font>
								</td>
								<? if (!$is_ecommerce) {?>
									<td align='left' valign="top" class='cl1'>
									<?php
										if ($strPassStatus == "ND" && $strCancelled == "N"){
											echo("&nbsp;");
										}
										else{							
									?>
											<textarea name="txtarea<?=$iLoop ?>" rows="5" cols="13" style="font-size:10;font-family:verdana"><?php print($strNoPassComment);?></textarea>
									<?php } ?>
									</td>
								<? } ?>
								</tr>
								<input type="hidden" name="hdId<?=$iLoop?>" value="<?=$iTransactionId?>">
								<input type="hidden" name="hid_payment_type<?=$iLoop?>" value="<?=$str_payment_type?>">
								<input type="hidden" name="hid_company_id<?=$iLoop?>" value="<?=$iCompanyId?>">
								<input type="hidden" name="hid_billing_date<?=$iLoop?>" value="<?=$strBillingDate?>">
<?php						}
					?>
							<tr>
								<td colspan="15" align="center" valign="bottom"><br><?= 							$i_lower_limit <= 0 ? "" : "&nbsp;<a href='javascript:showPreviousPage()'><img src='../images/previous.gif' border='0'></a>&nbsp;" ?><a href="gatewayList.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp;<input name="imageField" type="image" SRC="<?=$tmpl_dir?>/images/submitcompanydetails.jpg" border="0"><?= (
								$i_lower_limit + $i_num_records_per_page ) >= $trans_total_count ? "" : "&nbsp;<a href='javascript:showNextPage()'><img src='../images/next.gif' border='0'></a>" ?></td>
						</tr><br>
					</table>
				
					<input type="hidden" name="hdCount" value="<?=$iLoop?>">
					<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
					<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
					<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
					<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
					<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
					<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
					<input type="hidden" name="gatewayCompanies" value="<?= $gatewayAdminId ?>">
					<input type="hidden" name="companyname" value="<?= $companyid ?>">
					<input type="hidden" name="companyids" value="<?= $companyid ?>">
					<input type="hidden" name="task" value="">			
					<input type="hidden" name="lower_limit" value="">			
					</form>
  
   		<!-- Reports ends here -->
		<br></td>
  </tr>	
	<?php
		}else {
					$outhtml="y";
					$msgtodisplay="No transactions for this period";
					message($msgtodisplay,$outhtml,$headerInclude);									
					exit();

	}
	?>
  
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
 </table>
 <br>
<?php
include "includes/footer.php";
?>
