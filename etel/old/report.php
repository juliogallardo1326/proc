<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//report.php:		The  page functions for selecting the type of report view of the company. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'includes/header.php';
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$identity = " `cs_company_id` = ".$companyInfo['userId'];

$log = "";
if (substr(strtolower($_FILES["batchfile_recur"]["name"]), -3) == "csv" && 0)
 {	set_time_limit(500);
 	//$csv = file_get_contents($_FILES["batchfile_recur"]["tmp_name"]);
	//$csv = str_replace("\r", "", $csv);
	//$csv_info = csv_parse($csv);
	
	$CSV_Info = importcsv($_FILES["batchfile_recur"]["tmp_name"],false,",",8192);
	
	$log = "Attempting to recur bills through batch file:\n";
	$success = 0;
	$failed = 0;
	foreach ($csv_info as $info)
	 {	$transactionInfo = getTransactionInfo(quote_smart($info[0]), quote_smart($_GET["test"]), "reference_number", 
	 						"and t.userID = '$sessionlogin'");
		$transactionId = $transactionInfo["transactionId"];
		if (!is_array($transactionInfo))
		 {	$thisLog .= "Could not locate transaction {$info[0]}";
		 	$failed++;
		 } else
		 {	$recur_charge = quote_smart($info[1]);
		 	$recur_date = quote_smart($info[2]);
			$recur_date_ts = strtotime($recur_date);
			$thisLog = "";
			if ($recur_charge < $companyInfo["min_ticket_amt"])
			 {	$thisLog = "Invalid Amount (Ref. {$info[0]}):  $recur_charge is below the minimum accepted value of {$companyInfo["min_ticket_amt"]}.";
			 }
			if ($recur_charge > $companyInfo["max_ticket_amt"])
			 {	$thisLog = "Invalid Amount (Ref. {$info[0]}):  $recur_charge is above the maximum accepted value of {$companyInfo["max_ticket_amt"]}.";
			 }
			 
			if ($recur_date_ts < time())
			 {	$thisLog = "Invalid Date   (Ref. {$info[0]}):  $recur_date appears to be in the past.";
			 }
			if ($recur_date_ts < 1)
			 {	$thisLog = "Invalid Date   (Ref. {$info[0]}):  $recur_date doesn't appear to be a valid date.  Please use the format YYYY-MM-DD.";
			 }
			if ($recur_date_ts > (time() + (86400 * 370))) // gives it enough buffer
			 {	$thisLog = "Invalid Date   (Ref. {$info[0]}):  $recur_date is more than one year in the future.";
			 }
			if (strlen($thisLog) == 0) // nothing happened, 
			 {	$success++;
			 	$query = "UPDATE cs_transactiondetails
						SET td_recur_charge = '$recur_charge',
							td_recur_next_date = FROM_UNIXTIME('$recur_date_ts'),
							td_recur_processed = 0,
							td_enable_rebill = 1
						WHERE transactionId = '$transactionId'";
				$result=mysql_query($query,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot update transaction in batch recur process.");		
						
				$thisLog = "Reference {$info[0]} updated successfully.";
			 } else
			 {	$failed++;
			 }
		 }
		 $log .= $thisLog . "\n";
	 }
 }

if($_FILES['batchfile']['type']=='application/vnd.ms-excel')
{
	set_time_limit(500);
//	$CSV = file_get_contents($_FILES['batchfile']['tmp_name']);
//	$CSV = str_replace("\r", "", $CSV);
//	$CSV_Info = csv_parse($CSV);
	
	$CSV_Info = importcsv($_FILES["batchfile"]["tmp_name"],false,",",8192);
	
	$log = "Attempting to update transactions through batch file:\n";
	$success = 0; $failed = 0;
	foreach($CSV_Info as $info)
	{
				
		$transactionInfo=getTransactionInfo(quote_smart($info[0]),quote_smart($_GET['test']),'reference_number'," and t.userId = '$sessionlogin'");
		$transactionId = $transactionInfo['transactionId'];
		if($transactionInfo == -1) // sph:  shouldn't this be transactionId?
		{
			$log .= " Could not locate transaction '".$info[0]."'\n";
			$failed++;
		}
		else
		{
			//(Reference_ID, Shipping Tracking Number, Date Shipped, Estimate Arrival Time, Shipping Company, 
			//	Optional Tracking Hyperlink, Extra Info)
			$td_tracking_id = quote_smart($info[1]);
			$td_tracking_link = quote_smart($info[5]);
			$td_tracking_company = quote_smart($info[4]);
			$td_tracking_info = quote_smart($info[6]);
			if(!$td_tracking_id) $td_tracking_id = "No Tracking Number Available";
			$td_tracking_ship_date="";
			$td_tracking_ship_est="";
			$ship_timestamp=0;
			$ship_timestamp=0;
			if($info[2]) $ship_timestamp = strtotime(quote_smart($info[2]));
			if($info[3]) $ship_est_timestamp = strtotime(quote_smart($info[3]));
			if($ship_timestamp>0) $td_tracking_ship_date = date("Y-m-d g:i:s",$ship_timestamp);
			if($ship_est_timestamp>0) $td_tracking_ship_est = date("Y-m-d g:i:s",$ship_est_timestamp);
			
			$sql = "update `cs_transactiondetails` set `td_tracking_ship_est` = '$td_tracking_ship_est', `td_tracking_ship_date` = '$td_tracking_ship_date', `td_tracking_id` = '$td_tracking_id', `td_tracking_link` = '$td_tracking_link', `td_tracking_company` = '$td_tracking_company', td_tracking_info = '$td_tracking_info' where transactionId = '$transactionId'";
			
			if(($ship_timestamp<(time()-60*60*24*365) || $ship_timestamp>(time()+60*60*24*365)) && $ship_timestamp>0 ) 
				{$log.="Invalid Ship Date: $td_tracking_ship_date $ship_timestamp. Please format YYYY-MM-DD HH:MM:SS.\n"; $failed++;}
			else if(($ship_est_timestamp<(time()-60*60*24*365) || $ship_est_timestamp>(time()+60*60*24*365)) && $ship_est_timestamp>0) 
				{$log.="Invalid Estimation Date: $td_tracking_ship_est $ship_est_timestamp. Please format YYYY-MM-DD HH:MM:SS.\n"; $failed++;}
			else if(!$transactionInfo['td_tracking_id']) 
			{
				$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot update transaction.");
			
				$transactionInfo['td_tracking_id'] = $td_tracking_id;
				$transactionInfo['td_tracking_link'] = $td_tracking_link;
				$transactionInfo['td_tracking_company'] = $td_tracking_company;
				$transactionInfo['td_tracking_ship_date'] = $td_tracking_ship_date;
				$transactionInfo['td_tracking_ship_est'] = $td_tracking_ship_est;
		
				// Email
				if($td_tracking_id || $td_tracking_ship_date)
				{
					$useEmailTemplate = "customer_tracking_confirmation";
					
					$data['site_URL'] = $transactionInfo['cs_URL'];
					$data['reference_number'] = $transactionInfo['reference_number'];
					$data['full_name'] = $transactionInfo['surname'].", ".$transactionInfo['name'];
					$data['email'] = $transactionInfo['email'];
					$data['tracking_ID'] = $transactionInfo['td_tracking_id'];
					$data['tracking_link'] = $transactionInfo['td_tracking_link'];
					$data['tracking_info'] = ($transactionInfo['td_tracking_info']?$transactionInfo['td_tracking_info']:"None");
					$data['tracking_ship_date'] = ($transactionInfo['td_tracking_ship_date']? date("F j, Y, g:i a",strtotime($transactionInfo['td_tracking_ship_date'])):"No Date Available");
					$data['tracking_ship_est'] = ($transactionInfo['td_tracking_ship_est']? date("F j, Y, g:i a",strtotime($transactionInfo['td_tracking_ship_est'])):"No Estimate Available");
					$data["gateway_select"] = $companyInfo['gateway_id'];
					send_email_template($useEmailTemplate,$data,""); // Send Customer Email.
					if($transactionInfo['cd_recieve_order_confirmations'])
					{	
						$data['email'] = $transactionInfo['cd_recieve_order_confirmations'];
						send_email_template($useEmailTemplate,$data,"( Merchant Copy) ");
					}
					$success++;
				}
				else $failed++;
			}
		
		}
	
	}
}
if (strlen($log) > 0)
 {	$log .= "$success/".($failed+$success)." Transactions Updated Successfully. $failed Failed.\n";
 	echo "<div align='center' style='font-size:10'>".nl2br($log)."</div>";
	include("includes/footer.php");
	die();
 }
/*
$sql = "SELECT * FROM `cs_companydetails` WHERE `userId` = $sessionlogin";
if(!($result = mysql_query($sql,$cnn_cs)))
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print ($qry_update."<br>");
	print("Failed to access company info.");
	exit();
}
else
{
	$companyInfo = mysql_fetch_assoc($result);
}

*/
$companyBlocked = $int_get_permission;


$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$ptype = (isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"");
if($sessionlogin!="")
{
	if($ptype=="s")
	{
		$headerInclude="reports";
	   	$action="reportBottomSummary.php";
	   	$periodhead="Ledgers";
	}
	else
	{
		$headerInclude="transactions";
	   	$action="reportBottom.php";
	    $periodhead="Transactions";
 	}
//include("includes/topheader.php");
	

//	$dayVal=date("d");
//	$monthVal=date("n");
//	$yearVal=date("Y");

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


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
	
	if(!isset($period))
	{ 
		$period="p";
	}
    if($period=="p")
	{ 
		$periodstring="Start Date";
		$endperiodstring = "End Date";
    }
$str_qry_callcenter ="select cc_usersid,comany_name from cs_callcenterusers where company_id=$sessionlogin order by comany_name";
$str_qry_tsr ="select tsr_user_id,tsr_user_name from cs_tsrusers where tsr_added_user_id=$sessionlogin order by tsr_user_name";
// $str_qry_all ="select * from cs_tsrusers,cs_callcenterusers where cs_tsrusers.tsr_added_user_id=cs_callcenterusers.company_id and cs_callcenterusers.company_id=$sessionlogin and cs_tsrusers.tsr_added_user_id=$sessionlogin";
$str_selected_value="";

?>
<!-- <script language="javascript" src="scripts/calendar.js"></script>
<script language="javascript" src="scripts/general.js"> -->
</script>
<script language="JavaScript" src="scripts/general.js"></script>
<script language="javascript">
function datefn() {   
	checkval=true          
	datestring=document.forms[0].txtDate1.value  	
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate1'
	   }
	 datestring=document.forms[0].txtDate.value
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate'
	   }
	  if(!checkval){
		 alert("Please enter correct date") 
		 eval("document.forms[0]." + fname + ".focus()");
		 return false
	  }
	  else{
		return true
	  }
  
}

function show()
{
trimSpace(document.ledger.firstname)
trimSpace(document.ledger.lastname)
trimSpace(document.ledger.firstname)
trimSpace(document.ledger.telephone)
trimSpace(document.ledger.email)
trimSpace(document.ledger.transactionId)
trimSpace(document.ledger.credit_number)
trimSpace(document.ledger.check_number)
trimSpace(document.ledger.account_number)
trimSpace(document.ledger.routing_code)


	var isValid = true;
	var obj_form = document.ledger;
	<?php if ($companyBlocked != 1){ ?>
	if (isValid) {
		if (obj_form.check_number.value != "" || obj_form.credit_number.value != "") {
			if (obj_form.email.value == "" && obj_form.transactionId.value == "") {
				alert("Please enter either the email address or transaction Id");
				obj_form.email.focus();
				isValid = false;
			} else {
				isValid = true;
			}
		}
	}
	if (isValid) {
		if (obj_form.check_number.value != "") {
			if (obj_form.account_number.value == "" || obj_form.routing_code.value == "") {
				alert("Please enter the account number and bank routing code");
				isValid = false;
			} else {
				isValid = true;
			}
		}
	}
	<?php } ?>
	
	
	
	if(isValid){
		if (obj_form.email.value  != "") 
		{
			if (obj_form.email.value .indexOf('@')==-1) 
			{
				alert("Please enter valid email id");
				obj_form.email.focus();
				isValid = false;
			}
		}
	}
	
	if(isValid){		
		if (obj_form.email.value  != "") 
		{
			if (obj_form.email.value .indexOf('.')==-1) 
			{
				alert("Please enter valid email id");
				obj_form.email.focus();
				isValid = false;
			}
		}
	}
		
	if(isValid){	
		if (obj_form.email.value.length > 100)
		{
			alert("Please enter email max upto 100 characters")
			obj_form.email.focus();
			isValid = false;
		}
	}
	
	
	if (isValid) {
		document.ledger.action="<?=$action?>"
		document.ledger.submit();
	}
}
function func_emailprint()
{
	document.ledger.method = "GET";
	document.ledger.action = "printemails.php";
	document.ledger.target = "_blank";
	document.ledger.submit();
}

function showType(){
	if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="C") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Savings Account","S");
		document.ledger.type[2] = new Option("Checking Account","C");
		document.ledger.type.disabled = false;
	} else if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="H") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Master Card","M");
		document.ledger.type[2] = new Option("Visa","V");
		document.ledger.type.disabled = false;
	}
	else{
		document.ledger.type.value= "";
		document.ledger.type.disabled = true;
	}
	return false;
}

function showUsers(){
	if(document.ledger.usertype.options[document.ledger.usertype.selectedIndex].value=="all") {
	 	document.getElementById('callcenter').style.display = "none";
	 	document.getElementById('tsruser').style.display = "none";
	}else if(document.ledger.usertype.options[document.ledger.usertype.selectedIndex].value=="call") {
		document.getElementById('callcenter').style.display = "";
	 	document.getElementById('tsruser').style.display = "none";
	} else if(document.ledger.usertype.options[document.ledger.usertype.selectedIndex].value=="tsr") {
		document.getElementById('tsruser').style.display = "";
	 	document.getElementById('callcenter').style.display = "none";
	}
	return false;
}

function clearCheckCardNumber(type) {
	if (type == "card") {
		if (document.ledger.check_number.value != "") {
			document.ledger.check_number.value = "";
		}
	} else if (type == "check") {
		if (document.ledger.credit_number.value != "") {
			document.ledger.credit_number.value = "";
		}
	}
}

</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.ledger;
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
</script>
<style type="text/css">
<!--
.style1 {font-size: 10px;}
-->
</style>
<!-- $companysites -->

<center>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100">
	<tr>
		<td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>images/menucenterbg.gif" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>images/menucenterbg.gif" ><span class="whitehd">Transactions</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>images/menutoprightbg.gif" ><img alt="" src="<?=$tmpl_dir?>images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
		<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
			<form name="ledger"  method="GET"  onsubmit="return show()" target="_self">
			<input type="hidden" name="period" value="<?=$period?>"></input>
			<table align="center" cellpadding="0" cellspacing="0" width="83%" border="0">
				<tr> 
					<td width="102"   height="20" align="left"  valign="middle" ><font face="verdana" size="1"> 
						<?=$periodstring?>
						</font>
					</td>
					<td align="left" width="230">
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
						<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(350,90,document.ledger.from_date)"> 
					</td>
					<td width="324" colspan="2" rowspan="9" valign="top">
						<table width="324" border="0">
							<? 
							if($_SESSION["sessionlogin_type"] == "tele") 
							{ 
							?>
							<tr>
								<td width="152" height="21"><font face="verdana" size="1">Pending</font> 
								</td>
								<td width="160"><font face="verdana" size="1"> 
									<input type="checkbox" name="trans_ptype" value="p">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pass&nbsp;&nbsp;</font> 
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
									<input type="checkbox" name="trans_pass" value="p">
								</td>
							</tr>
							<tr> 
								<td height="23">
									<font face="verdana" size="1">No Pass</font>&nbsp; 
								</td>
								<td>
									<font face="verdana" size="1">
									<input type="checkbox" name="trans_nopass" value="N">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Declined&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									</font> 
									<input type="checkbox" name="trans_dtype" value="D">
								</td>
							</tr>
							<tr>
								<td width="152" height="21">
									<font face="verdana" size="1">Pending</font> 
								</td>
								<td width="160">
									<font face="verdana" size="1"> 
									<input type="checkbox" name="trans_ptype" value="p">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pass&nbsp;&nbsp;</font> 
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
									<input type="checkbox" name="trans_pass" value="p">
								</td>
							</tr>
							<tr> 
								<td height="23">
									<font face="verdana" size="1">Set to billdate</font>&nbsp; 
								</td>
								<td>
									<font face="verdana" size="1">
									<input type="checkbox" name="daterange" value="N">
								</td>
							</tr>
						<? } else { ?>
							<tr>
								<td width="152" height="21">
									<font face="verdana" size="1">Pending&nbsp;Check&nbsp;Transactions</font> 
								</td>
								<td>
									<font face="verdana" size="1">
									<input name="trans_pend_checks" type="checkbox" id="trans_pend_checks" value="C">
									&nbsp;&nbsp;&nbsp;&nbsp;Declined&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> 
									<input type="checkbox" name="trans_dtype" value="D">
								</td>
							</tr>
							<? } ?>
							<tr> 
								<td>
									<font face="verdana" size="1">Refunded</font> 
								</td>
								<td>
									<font face="verdana" size="1"> 
									<input type="checkbox" name="trans_ctype" value="C">
									&nbsp;&nbsp;&nbsp;&nbsp;Approved &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
									<!-- <input type="radio" name="daterange" value="A"> -->
									<input type="checkbox" name="trans_atype" value="A">
									</font>
								</td>
							</tr>
							<tr> 
								<td>
									<font face="verdana" size="1">Recurring Billing</font> 
								</td>
								<td>
									<font face="verdana" size="1"> 
									<input type="checkbox" name="trans_recur" value="C">
									&nbsp;&nbsp;&nbsp;&nbsp;ChargeBack &nbsp;&nbsp;&nbsp; 
									<!-- <input type="radio" name="daterange" value="A"> -->
									<input type="checkbox" name="trans_chargeback" value="A">
									</font>
								</td>
							</tr>
							<? if($_SESSION["sessionlogin_type"] == "tele") { ?>
							<tr> 
								<td>
									<font face="verdana" size="1">Set to billdate</font>
								</td>
								<td>
									<font face="verdana" size="1">
									<input type="checkbox" name="daterange" value="S"></font>
									<!--   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Set to bill date&nbsp; 
									<input type="radio" name="daterange" value="B"> -->
								</td>
							</tr>
							<? } ?>
							<tr> 
								<td>	
									<font face="verdana" size="1">Display Active Subscriptions </font>
								</td>
								<td>
									<font face="verdana" size="1">
									<input name="active_subscriptions" type="checkbox" id="active_subscriptions" value="1">
									</font>
								</td>
							</tr>
							<?php if($companyInfo['cd_enable_tracking']=='on') {?>
							<tr> 
								<td>
									<font face="verdana" size="1" style="font-weight:bold; " >Display UnTracked Orders</font>
								</td>
								<td>
									<font face="verdana" size="1">
									<input name="untracked_orders" type="checkbox" id="untracked_orders" value="1">
									</font>
								</td>
							</tr>
							<? } ?>
							<tr> 
								<td>
									<font face="verdana" size="1">Display Test Transactions </font>
								</td>
								<td>
									<font face="verdana" size="1">
									<input name="display_test_transactions" type="checkbox" id="display_test_transactions" value="1">
									</font>
									<!--   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Set to bill date&nbsp; 
									<input type="radio" name="daterange" value="B"> -->
								</td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">First Name</font></td>
								<td> <input type="text" maxlength="100" name="firstname" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">Last Name</font></td>
								<td><input type="text" maxlength="100" name="lastname" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<?php if ($companyBlocked != 1){ ?>
							<tr> 
								<td><font face="verdana" size="1">Telephone Number</font></td>
								<td><input type="text" maxlength="10" name="telephone" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<? } ?>
							<tr> 
								<td><font face="verdana" size="1">email</font></td>
								<td><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">Reference number</font></td>
								<td><input type="text" maxlength="100" name="transactionId" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<?php if ($companyBlocked != 1){ ?>
							<tr> 
								<td><font face="verdana" size="1">Credit Card Number</font></td>
								<td><input type="text" maxlength="15" name="credit_number" style="font-family:arial;font-size:10px;width:150px" onKeyDown="Javascript:clearCheckCardNumber('card')"></td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">Check Number</font></td>
								<td><input type="text" maxlength="15" name="check_number" style="font-family:arial;font-size:10px;width:150px" onKeyDown="Javascript:clearCheckCardNumber('check')"></td>
							</tr>
							<tr>
								<td><font face="verdana" size="1">If Check,</font></td>
								<td>&nbsp;</td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">Account Number</font></td>
								<td><input type="text" maxlength="15" name="account_number" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<tr> 
								<td><font face="verdana" size="1">Bank Routing Code</font></td>
								<td><input type="text" maxlength="15" name="routing_code" style="font-family:arial;font-size:10px;width:150px"></td>
							</tr>
							<?php } ?>
						</table>
					</td>
				</tr>
				<tr> 
					<td   height="20"  valign="middle" align="left"><font face="verdana" size="1"> 
						<?=$endperiodstring?>
						</font>
					</td>
					<td align="left" width="230">
						<select name="opt_to_month" class="lineborderselect" style="font-size:10px">
						<?php func_fill_month($i_to_month); ?>
						</select> <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
						<?php func_fill_day($i_to_day); ?>
						</select> <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
						<?php func_fill_year($i_to_year); ?>
						</select>
						<input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
						<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(350,90,document.ledger.from_to)"> 
					</td> 
				</tr>   
<? 			if($_SESSION["sessionlogin_type"] == "tele") { ?>					  
				<tr> 
					<td  height="20"  valign="middle" align="left"> <font face="verdana" size="1">User Type</font></td>
					<td width="230" height="20" align="left">
						<select name="usertype" style="font-family:verdana;font-size:10px;WIDTH:100px" onChange="showUsers();">
						<option value='all' selected>All User</option>
						<option value='call'>Call Center</option>
						<option value='tsr'>TSR User</option>
						</select>
					</td>
				</tr>
				<tr> 
					<td  colspan="2" valign="middle" align="left">
						<div id="callcenter" style="display:None">
							<table width="100%">
								<tr>
									<td  valign="middle" align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Call Centers</font></td>
									<td align="left" width="230">&nbsp;<select name="callcenters[]" style="font-family:verdana;font-size:10px;WIDTH:140px" multiple>
										<option value="A" selected>All Users</option>
					<?php 				print func_fill_combo_conditionally($str_qry_callcenter,$str_selected_value,$cnn_cs); ?>				  
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div id="tsruser" style="display:None">
							<table width="100%">
								<tr>
									<td width="37" align="left" valign="middle">
										<font face="Verdana, Arial, Helvetica, sans-serif" size="1">TSR Users</font>
									</td>
									<td align="left" width="157">&nbsp; 
										<select name="tsrusers[]" style="font-family:verdana;font-size:10px;WIDTH:140px" multiple>
										<option value="A" selected>All Users</option>
										<?php print func_fill_combo_conditionally($str_qry_tsr,$str_selected_value,$cnn_cs); ?>
										</select>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
<?php 		}	?>
				<tr> 
					<td  height="20"  valign="middle" align="left"> <font face="verdana" size="1">Payment Type</font></td>
					<td align="left" width="230"><select name="crorcq" style="font-family:verdana;font-size:10px;WIDTH: 180px" onChange="javascript:showType()">
						<option value='A'  >All</option>
						<option value='C'  >Check</option>
						<option value='H'  >Credit Card</option>
						<option value="W">Web900</option>
						</select> 
					</td>
				</tr>
<?php if ($companyBlocked != 1){ ?>
				<tr> 
					<td  height="25"  valign="middle" align="left"> <font face="verdana" size="1">Card/Check Type</font></td>
					<td align="left" width="230">
						<select name="type" style="font-family:verdana;font-size:10px;WIDTH: 180px" disabled>
						</select> 
					</td>
				</tr>
				<tr> 
					<td  height="54"  valign="top" align="left"> <font face="verdana" size="1">Refund Reason Reason</font></td>
					<td align="left" width="230" valign="top">
						<select name="cancel_reasons[]" style="font-family:verdana;font-size:10px;WIDTH: 180px" multiple>
						<?php print(funcFillCancellationReason('',''));?> 
						</select> 
					</td>
				</tr>
				<tr> 
					<td  height="30"  valign="top" align="left"> <font face="verdana" size="1">Decline Reason</font></td>
					<td align="left" width="230" valign="top">
						<select name="decline_reasons[]" style="font-family:verdana;font-size:10px;WIDTH: 180px" multiple>
						<?php print(funcFillDeclineReason('','Check'));?> 
						</select> 
					</td>
				</tr>
<?php } ?>
				<tr> 
					<td  height="30"  valign="top" align="left"> <font face="verdana" size="1">Websites</font></td>
					<td align="left" width="230" valign="top">
						<select name="company_site" style="font-family:verdana;font-size:10px;WIDTH: 180px">
						<option value='-1'>All Sites</option>
	  					<?=get_fill_combo_conditionally("SELECT cs_ID,cs_name FROM `cs_company_sites` WHERE $identity AND cs_hide = '0' ORDER BY `cs_name` ASC",$siteID)?>

						</select> 
					</td>
				</tr>
			</table>
<?php
$ptype = (isset($_GET["ptype"])?quote_smart($_GET["ptype"]):"");
if (!$ptype)
{
?>
			<table>
				<tr> 
					<td align="center" height="30" valign="bottom"><font face="verdana" size="1"><a href="javascript:show();"><img src="<?=$tmpl_dir?>images/view.jpg" border="0"></a></font> 
					</td>
				</tr>
			</table>
<?
}
else
{ 
?>
			<input type="hidden" name="id" value="">
			<input type="hidden" name="cnumber" value="">
<?
}
?>
			</form>
		</td>
	</tr>
	<tr>
		<td width="1%"><img src="<?=$tmpl_dir?>images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="<?=$tmpl_dir?>images/menubtmcenter.gif"><img border="0" src="<?=$tmpl_dir?>images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="<?=$tmpl_dir?>images/menubtmright.gif"></td>
	</tr>
</table>
<br>

<table border="0" cellpadding="0" cellspacing="0" width="80%" height="100">
	<tr>
		<td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>images/menucenterbg.gif" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>images/menucenterbg.gif" ><span class="whitehd">Batch Shipping Order Status</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>images/menutoprightbg.gif" ><img alt="" src="<?=$tmpl_dir?>images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
		<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
			<form action=""  method="POST" enctype="multipart/form-data" name="frmBatch" id="frmBatch">
			<table width="555" border="1">
				<tr>
					<td><font face="verdana" size="1">CSV Batch File: </font></td>
					<td><font face="verdana" size="1">Batch CSV File Format: <br>
					(Reference_ID, Shipping Tracking Number, Date Shipped, Estimate Arrival Time, Shipping Company, Optional Tracking Hyperlink, Extra Info)<br>
					End each line with carriage return. Use quotes where the use of a comma in the text is necessary. Date Format must be <span class="style1">YYYY-MM-DD HH:MM:SS</span>.</font></td>
				</tr>
				<tr>
					<td><font face="verdana" size="1">Batch File </font></td>
					<td><input name="batchfile" type="file" id="batchfile"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="Submit" value="Submit"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td width="1%"><img src="<?=$tmpl_dir?>images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="<?=$tmpl_dir?>images/menubtmcenter.gif"><img border="0" src="<?=$tmpl_dir?>images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="<?=$tmpl_dir?>images/menubtmright.gif"></td>
	</tr>
</table>

<br>

<?  // added by sph, 2005/12/07 

if ($companyInfo["cd_custom_recur"] == 1)
{	?>

<table border="0" cellpadding="0" cellspacing="0" width="80%" height="100">
	<tr>
		<td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>images/menucenterbg.gif" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>images/menucenterbg.gif" ><span class="whitehd">Batch Recur Billing</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>images/menutoprightbg.gif" ><img alt="" src="<?=$tmpl_dir?>images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
		<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
			<form action=""  method="POST" enctype="multipart/form-data" name="frmBatchRecur" id="frmBatchRecur">
			<table width="555" border="1">
				<tr>
					<td><font face="verdana" size="1">CSV Batch File: </font></td>
					<td><font face="verdana" size="1">Batch CSV File Format: <br>
					(Reference ID, Recur Amount, Next Recur Date)<br>
					End each line with carriage return. Use quotes where the use of a comma in the text is necessary. Date Format must be 
					<span class="style1">YYYY-MM-DD</span>. Important: Updating a transaction will cause it to recur, even if it has already been processed, or canceled. </font></td>
				</tr>
				<tr>
					<td><font face="verdana" size="1">Batch File </font></td>
					<td><input name="batchfile_recur" type="file" id="batchfile_recur"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="Submit" value="Submit"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td width="1%"><img src="<?=$tmpl_dir?>images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="<?=$tmpl_dir?>images/menubtmcenter.gif"><img border="0" src="<?=$tmpl_dir?>images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="<?=$tmpl_dir?>images/menubtmright.gif"></td>
	</tr>
</table>
<?
}
?>

</center>


<?php
include("includes/footer.php");
}		
?>