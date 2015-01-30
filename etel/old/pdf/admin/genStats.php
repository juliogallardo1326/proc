<?php

	$allowBank=true;
	if(!$headerInclude) $headerInclude="ledgers";
	include("includes/sessioncheck.php");
	$forceProfitUpdate=1;
	include("includes/header.php");
	include("../includes/completion.php");
	
$timestart = microtime_float();
	
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
	
$str_date_range = " between '$str_from_date 00:00:00' and '$str_to_date 23:59:59' ";
	
$str_from_stamp = strtotime($str_from_date);
$str_to_stamp = strtotime($str_to_date)+60*60*24;


$from_date = ($_REQUEST['from_date']?$_REQUEST['from_date']:date('m/d/Y',$str_from_stamp));
$to_date = ($_REQUEST['to_date']?$_REQUEST['to_date']:date('m/d/Y',$str_to_stamp));

$str_date_stamp_range = " between '$str_from_stamp' and '$str_to_stamp' ";
	
$optdate = "opt_from_month=".date("m",$str_from_stamp)."&opt_from_day=".date("d",$str_from_stamp)."&opt_from_year=".date("Y",$str_from_stamp)."&from_date=&opt_to_month=".date("m",$str_to_stamp)."&opt_to_day=".date("d",$str_to_stamp)."&opt_to_year=".date("Y",$str_to_stamp);
	
if($adminInfo['li_level']=="full"){

	beginTable();
	$sql_array= null;
	
	// Bank
	require_once ("../includes/projSetCalc.php");
	$qry_company="SELECT bk.*,
	SUM((`status`='A')*`amount`) as TotalApproves,
	SUM(`status`='A') as TotalApprovesNum,
	SUM(`td_bank_recieved` = 'yes') as TotalTransNum
	FROM `cs_bank` as bk left join `cs_transactiondetails` as td on bk.bank_id = td.bank_id and td.transactionDate $str_date_range $bank_sql_limit group by bk.bank_id"; // WHERE bank_id=15 OR bank_id=19
	
	$bank_details=mysql_query($qry_company,$cnn_cs) or dieLog("Cannot execute query");
	$bi_pay_info_item=NULL;
	$bi_pay_info=NULL;
	$i=0;
	$invoice_options="";
	while($bankInfo=mysql_fetch_assoc($bank_details))
	{
		$calcInfo = null;
		$calcInfo['forceCalc']= true;
		$calcInfo['last_date_hold'] = date("Y-m-d 00:00:00",$str_from_stamp);
		$calcInfo['last_date_delay'] = date("Y-m-d 00:00:00",$str_from_stamp);
		$calcInfo['date_hold'] = date("Y-m-d 00:00:00",$str_to_stamp);
		$calcInfo['date_delay'] = date("Y-m-d 00:00:00",$str_to_stamp);
		//$calcInfo['forceProfitView'] = true;
		$bankInfo['bk_payment_type'] = 'profit';
		$calcInfo['estimateMode'] = true;
		
		$bi_pay_info_item[$i] = calcBankReal(0,$calcInfo);
		$bi_pay_info['TotalProfit'] += $bi_pay_info_item[$i]['TotalProfit'];
		$bi_pay_info['Profit'] += $bi_pay_info_item[$i]['Profit'];
		$bi_pay_info['Deductions'] += $bi_pay_info_item[$i]['Deductions']+$bi_pay_info_item[$i]['WireFee'];
		$bi_pay_info_item[$i]['TotalApproves'] = $bankInfo['TotalApproves'];
		$bi_pay_info_item[$i]['TotalApprovesNum'] = $bankInfo['TotalApprovesNum'];
		$bi_pay_info_item[$i]['TotalTransNum'] = $bankInfo['TotalTransNum'];
		$bi_pay_info['TotalApproves'] += $bankInfo['TotalApproves'];
		$bi_pay_info['TotalApprovesNum'] += $bankInfo['TotalApprovesNum'];
		$bi_pay_info['TotalTransNum'] += $bankInfo['TotalTransNum'];
		$i++;
	}
	//$profitMsg .=  "<BR>Total Daily Profit: $".formatMoney($bi_pay_info['TotalProfit'])."\n";
	
	// Company Stats
	
	$sql = "SELECT 
	
	COUNT(distinct cd.`userId`) as TotalCompanys,
	SUM(date_added $str_date_range) as NewCompanys,
	GROUP_CONCAT(distinct if(date_added $str_date_range,userId,NULL) SEPARATOR '|') as NewCompanyList,
	
	SUM(cd.`cd_completion`=1 AND cd.`transaction_type`='Adult') as AdltApps,
	
	SUM(`cd_completion`=3) as ReqRates,
	
	SUM(`cd_completion`=9) as ReqLive,
	
	SUM(`cd_completion`=3 and gateway_id in (5,6)) as IBillReqRates,

	SUM(`cd_completion`=9 and gateway_id in (5,6)) as IBillReqLive,

	
	SUM(`activeuser`=1 AND `cd_completion`=10) as Live,
	
	SUM(`cd_reseller_rates_request`=1 AND `cd_completion`<4) as ResReqRates,
	
	SUM(`cd_reseller_rates_request`!='0' AND `cd_reseller_rates_request`!='1' AND `cd_completion`<4) as RatesMarkedUp,
	
	CURDATE() as Date
	
	FROM `cs_companydetails` as cd 
	WHERE cd.cd_ignore=0 $bank_sql_limit ";
	//etelPrint($sql);
	$sql_array[] = $sql;
	
	// Log stats
	$sql = "SELECT 
	
	
	SUM(cd.`cd_completion`=1 AND cd.`transaction_type`='Adult' AND lg_action='completedapplication') as AdltAppsNew,
	GROUP_CONCAT(if(cd.`cd_completion`=1 AND cd.`transaction_type`='Adult' AND lg_action='completedapplication',userId,NULL) SEPARATOR '|') as AdltAppsList,
	
	SUM(lg_action='requestrates') as ReqRatesNew,
	GROUP_CONCAT(if(lg_action='requestrates',lg_item_id,NULL) SEPARATOR '|') as ReqRatesList,
	
	SUM(lg_action='requestlive') as ReqLiveNew,
	GROUP_CONCAT(if(lg_action='requestlive',lg_item_id,NULL) SEPARATOR '|') as ReqLiveList,
	
	SUM(lg_action='requestrates' and gateway_id in (5,6)) as IBillReqRatesNew,
	GROUP_CONCAT(if(lg_action='requestrates' and gateway_id in (5,6),lg_item_id,NULL) SEPARATOR '|') as IBillReqRatesList,
	
	SUM(lg_action='requestlive' and gateway_id in (5,6)) as IBillReqLiveNew,
	GROUP_CONCAT(if(lg_action='requestlive' and gateway_id in (5,6),lg_item_id,NULL) SEPARATOR '|') as IBillReqLiveList,

	 
	COUNT(distinct if(`activeuser`=1 AND `cd_completion`=10 AND lg_action='turnedlive',userId,NULL)) as LiveNew,
	GROUP_CONCAT(distinct if(`activeuser`=1 AND `cd_completion`=10 AND lg_action='turnedlive',userId,NULL) SEPARATOR '|') as LiveList,
	
	SUM(`cd_reseller_rates_request`=1 AND lg_action='requestrates' AND `cd_completion`<4) as ResReqRatesNew,
	GROUP_CONCAT(if(`cd_reseller_rates_request`=1 AND lg_action='requestrates' AND `cd_completion`<4,userId,NULL) SEPARATOR '|') as ResReqRatesListNew,
	
	SUM(`cd_reseller_rates_request`!='0' AND `cd_reseller_rates_request`!='1' AND lg_action='resellerrequestrates' AND `cd_completion`<3 ) as RatesMarkedUpNew,
	GROUP_CONCAT(if(`cd_reseller_rates_request`!='0' AND `cd_reseller_rates_request`!='1' AND lg_action='resellerrequestrates' AND `cd_completion`<3 ,userId,NULL) SEPARATOR '|') as RatesMarkedUpList
	
	
	FROM `cs_log` as lg
	left join `cs_companydetails` as cd ON lg_item_id = userId AND cd.cd_ignore=0 
	WHERE 1 $bank_sql_limit AND lg_timestamp $str_date_stamp_range";
	//etelPrint($sql);
	$sql_array[] = $sql;
	
	
	// Websites
	
	$sql = "SELECT 
	GROUP_Concat(distinct if(cd.`transaction_type`='Adult',cs_ID,NULL)) as NewAdltWebsList,
	GROUP_Concat(distinct if(cs_ID,cs_id,NULL)) as NewWebsList,
	COUNT(distinct if(cd.`transaction_type`='Adult',cs_ID,NULL)) as NewAdltWebs,
	COUNT(distinct cs_ID) as NewAllWebs

	FROM `cs_company_sites` as cs
	left join `cs_companydetails` as cd on cs.`cs_company_id`=cd.`userId` 
	left join `cs_log` as lg on lg_item_id = cs_ID
	
	WHERE cs.`cs_verified` = 'pending' AND cs.`cs_hide`=0 AND lg_action = 'pendingwebsite' $bank_sql_limit
	";
	$sql_array[] = $sql;
	
	$sql = "SELECT 
	SUM(cd.`transaction_type`='Adult') as AdltWebs,
	COUNT(*) as AllWebs

	FROM `cs_company_sites` as cs
	left join `cs_companydetails` as cd on cs.`cs_company_id`=cd.`userId` 
	
	WHERE 1 AND cs.`cs_verified` = 'pending' AND cs.`cs_hide`=0 $bank_sql_limit
	";
	$sql_array[] = $sql;
	
	// Wire Transfers
	
	$sql = "SELECT 
	SUM(mi.`mi_status`='WireFailure') as WireFailure,
	SUM(mi.`mi_status`='Pending') as WirePending
	FROM `cs_merchant_invoice` as mi 
	";
	$sql_array[] = $sql;
	
	// Documents 
	
	$sql = "SELECT 
	SUM(ud.`status` = 'P' AND cd.`transaction_type`='Adult') as AdltDocs,
	SUM(ud.`status` = 'P' AND cd.`transaction_type`='Adult' AND `date_uploaded` $str_date_range) as NewAdltDocs,
	GROUP_Concat(distinct if(ud.`status` = 'P' && cd.`transaction_type`='Adult' AND `date_uploaded` $str_date_range ,file_id ,NULL)) as NewAdltDocsList,
	
	SUM(ud.`status` = 'P') as PendDocs,
	SUM(ud.`status` = 'P' AND cd.gateway_id not in (5,6) and `date_uploaded` $str_date_range) as NewPendDocs,
	GROUP_Concat(distinct if(ud.`status` = 'P' AND cd.gateway_id not in (5,6) AND `date_uploaded` $str_date_range ,file_id ,NULL)) as NewPendDocsList,

	SUM(ud.`status` = 'P' AND cd.gateway_id in (5,6) ) as IBillPendDocs,
	SUM(ud.`status` = 'P' AND cd.gateway_id in (5,6) AND `date_uploaded` $str_date_range) as IBillNewPendDocs,
	GROUP_Concat(distinct if(ud.`status` = 'P' AND cd.gateway_id in (5,6) AND `date_uploaded` $str_date_range ,file_id ,NULL)) as IBillNewPendDocsList

	FROM `cs_companydetails` as cd, 
	`cs_uploaded_documents` as ud
	WHERE ud.user_id=cd.userId $bank_sql_limit";
	$sql_array[] = $sql;
		
	// Customer Calls

	$sql = "SELECT 
	SUM(`cn_type` = 'refundrequest' AND `call_date_time` $str_date_range AND td.cancelstatus='N') as RefundRequestsNew,
	SUM(`cn_type` = 'refundrequest' AND td.cancelstatus='N') as RefundRequests,
	
	SUM(`cn_type` = 'foundcall' AND `call_date_time` $str_date_range) as FoundCallsNew,
	SUM(`cn_type` = 'foundcall') as FoundCalls

	FROM `cs_callnotes` as cn 
	left join `cs_transactiondetails` as td on td.transactionId = cn.transaction_id
	WHERE transactionId is not null $bank_sql_limit";
	$sql_array[] = $sql;
			
	// Support Tickets

	$sql = "SELECT 	
		SUM(tickets_admin='Client') as SupportTickets,

		SUM(tickets_source='unfoundcall') as UnfoundSupportTickets,
		SUM(tickets_source='foundcall') as FoundSupportTickets,
		SUM(tickets_source='client') as ClientSupportTickets,

		SUM(tickets_source='unfoundcall' AND tickets_timestamp $str_date_stamp_range) as NewUnfoundSupportTickets,
		SUM(tickets_source='foundcall' AND tickets_timestamp $str_date_stamp_range) as NewFoundSupportTickets,
		SUM(tickets_source='client' AND tickets_timestamp $str_date_stamp_range) as NewClientSupportTickets
		
		FROM `tickets_tickets`		
		";
	//echo "<b>$sql</b>";
	$sql_array[] = $sql;


	$sql = "SELECT AVG(tickets_time) AS UnfoundSupportTicketsTime FROM tickets_tickets WHERE tickets_time <> 0 AND tickets_source='unfoundcall'";
	$sql_array[] = $sql;
	$sql = "SELECT AVG(tickets_time) AS FoundSupportTicketsTime FROM tickets_tickets WHERE tickets_time <> 0 AND tickets_source='foundcall'";
	$sql_array[] = $sql;
	$sql = "SELECT AVG(tickets_time) AS ClientSupportTicketsTime FROM tickets_tickets WHERE tickets_time <> 0 AND tickets_source='client'";
	$sql_array[] = $sql;
	
	
	
	
	// Transactions
	
	$sql = "SELECT count(distinct cd.`userId`) as TestTransComps,
	GROUP_CONCAT(distinct td.`userId` SEPARATOR '|') as TestTransUserList,
	SUM(`transactionId`is NOT NULL) as TestTrans
	FROM `cs_companydetails` as cd 
	left join `cs_test_transactiondetails` as td on td.userId=cd.userId 
	where `transactionId`is NOT NULL and
	td.transactionDate $str_date_range $bank_sql_limit";
	$sql_array[] = $sql;

	//$result = mysql_query($sql) or dieLog(mysql_error());
	//$td_live_stat = mysql_fetch_assoc($result);
	
	//$adminStats = array_merge($ta_adminStats,$cd_logStats,$cd_adminStats,$cs_adminStats,$ud_adminStats,$td_adminStats,$cn_adminStats,$td_live_stat);
	$sql = "Select * from ";
	for($i = 0;$i< sizeof($sql_array);$i++) 
	{
		$sql .= "(".$sql_array[$i].") as a$i";
		if($i< sizeof($sql_array)-1) $sql .= ", ";
	}
	//etelPrint($sql);
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~$sql");
	$adminStats = mysql_fetch_assoc($result);

	
	
?>

<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="javascript" >
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	if (obj_element.name == "from_date"){
		document.getElementById('opt_from_day').value = dateSelected ;
		document.getElementById('opt_from_month').value = monthSelected ;
		document.getElementById('opt_from_year').value = yearSelected ;
	}
	if (obj_element.name == "from_to"){
		document.getElementById('opt_to_day').value = dateSelected ;
		document.getElementById('opt_to_month').value = monthSelected ;
		document.getElementById('opt_to_year').value = yearSelected ;
	}
}

</script>
<table width="100%"  border="1" cellspacing="2" cellpadding="2" class="invoice">
  <tr class="info">
    <td height="22" valign="middle"   align="left" width="124">Start Date</td>
    <td width="228"  height="22" colspan="3" align="left" ><select name="opt_from_month" id="opt_from_month" style="font-size:10px">
        <?php func_fill_month($i_from_month); ?>
      </select>
      <select name="opt_from_day" id="opt_from_day" class="lineborderselect" style="font-size:10px">
        <?php func_fill_day($i_from_day); ?>
      </select>
      <select name="opt_from_year" id="opt_from_year" style="font-size:10px">
        <?php func_fill_year($i_from_year); ?>
      </select>
      <input type="hidden" name="from_date" id="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="<?=$from_date?>">
      <input style="font-family:verdana;font-size:10px;" type="button" value="..." onClick="init(350,90,document.getElementById('from_date'))">
    </td>
  </tr>
  <tr class="info">
    <td height="30" valign="middle" align="left" width="124">End Date</td>
    <td width="228"  height="30" colspan="3" align="left"><select name="opt_to_month" id="opt_to_month" class="lineborderselect" style="font-size:10px">
        <?php func_fill_month($i_to_month); ?>
      </select>
      <select name="opt_to_day" id="opt_to_day" class="lineborderselect" style="font-size:10px">
        <?php func_fill_day($i_to_day); ?>
      </select>
      <select name="opt_to_year" id="opt_to_year" class="lineborderselect" style="font-size:10px">
        <?php func_fill_year($i_to_year); ?>
      </select>
      <input type="hidden" name="from_to" id="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="<?=$from_to?>">
      <input style="font-family:verdana;font-size:10px;" type="button" value="..." onClick="init(350,90,document.getElementById('from_to'))">
    </td>
  </tr>
  <tr class="infoBold">
    <td>From <?=$str_from_date?> to <?=$str_to_date?> Statistics</td>
    <td colspan="3"><input type="submit" name="Submit" value="Choose Date"></td>
  </tr>
  <tr>
    <td>Companys</td>
    <td colspan="3"><a href='viewCompanyNext.php?userIdList=<?=$adminStats['NewCompanyList']?>'>(
      <?=intval($adminStats['NewCompanys'])?>
      ) New</a>, <a href='viewCompany.php'>(
      <?=intval($adminStats['TotalCompanys'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Completed Adult Applications</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['AdltAppsList']?>">(
      <?=intval($adminStats['AdltAppsNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_view=A&companytrans_type=adlt&cd_completion=1'>(
      <?=intval($adminStats['AdltApps'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Pending Adult Websites</td>
    <td colspan="3"><a href="confirmWebsite.php?webList=<?=$adminStats['NewAdltWebsList']?>">(
      <?=intval($adminStats['NewAdltWebs'])?>
      ) New</a>, <a href='confirmWebsite.php?cd_view=A&companytrans_type=adlt&resubmit=1'>(
      <?=intval($adminStats['AdltWebs'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Pending Websites</td>
    <td colspan="3"><a href="confirmWebsite.php?webList=<?=$adminStats['NewWebsList']?>">(
      <?=intval($adminStats['NewAllWebs'])?>
      ) New</a>, <a href='confirmWebsite.php?cd_view=A&resubmit=1'>(
      <?=intval($adminStats['AllWebs'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Pending Adult Uploaded Documents</td>
    <td colspan="3"><a href="confirmUploads.php?companytrans_type=adlt&fileList=<?=$adminStats['NewAdltDocsList']?>">(
      <?=intval($adminStats['NewAdltDocs'])?>
      ) New</a>, <a href='confirmUploads.php?cd_view=A&companytrans_type=adlt&resubmit=1'>(
      <?=intval($adminStats['AdltDocs'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Pending Uploaded Documents</td>
    <td colspan="3"><a href="confirmUploads.php?fileList=<?=$adminStats['NewPendDocsList']?>">(
      <?=intval($adminStats['NewPendDocs'])?>
      ) New</a>, <a href='confirmUploads.php?cd_view=A'>(
      <?=intval($adminStats['PendDocs'])?>
      ) Total</a></td>
  </tr>

  <tr>
    <td>Pending New IBill Uploaded Documents</td>
    <td colspan="3"><a href="confirmUploads.php?fileList=<?=$adminStats['IBillNewPendDocsList']?>">(
      <?=intval($adminStats['IBillNewPendDocs'])?>
      ) New</a>, <a href='confirmUploads.php?cd_view=A'>(
      <?=intval($adminStats['IBillPendDocs'])?>
      ) Total</a></td>
  </tr>

  <tr>
    <td>Requesting Rates and Fees</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['ReqRatesList']?>">(
      <?=intval($adminStats['ReqRatesNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_completion=3'>(
      <?=intval($adminStats['ReqRates'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Requesting to go Live</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['ReqLiveList']?>">(
      <?=intval($adminStats['ReqLiveNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_completion=9'>(
      <?=intval($adminStats['ReqLive'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Transfered IBill Requesting Rates and Fees</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['IBillReqRatesList']?>">(
      <?=intval($adminStats['IBillReqRatesNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_completion=3'>(
      <?=intval($adminStats['IBillReqRates'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Transfered IBill Requesting to go Live</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['IBillReqLiveList']?>">(
      <?=intval($adminStats['IBillReqLiveNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_completion=9'>(
      <?=intval($adminStats['IBillReqLive'])?>
      ) Total</a></td>
  </tr>

  <tr>
    <td>Is Now Live</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['LiveList']?>">(
      <?=intval($adminStats['LiveNew'])?>
      ) New</a>, <a href='viewCompany.php?cd_completion=10'>(
      <?=intval($adminStats['Live'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Has requested Reseller rates and fees Markup</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['ResReqRatesList']?>">(
      <?=intval($adminStats['ResReqRatesNew'])?>
      ) New</a>, <a href='viewCompany.php?resellerRatesRequest=1'>(
      <?=intval($adminStats['ResReqRates'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Reseller has Marked up Rates and Fees</td>
    <td colspan="3"><a href="viewCompanyNext.php?userIdList=<?=$adminStats['RatesMarkedUpList']?>">(
      <?=intval($adminStats['RatesMarkedUpNew'])?>
      ) New</a>, <a href='viewCompany.php?resellerMarkedUp=1'>(
      <?=intval($adminStats['RatesMarkedUp'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Today's Processing</td>
    <td>A/T</td>
    <td>Processing</td>
    <td>Profit</td>
  </tr>
  <?php 
  if(is_array($bi_pay_info_item))
  {
	  foreach($bi_pay_info_item as $info_item){
	  if($info_item['TotalProfit']) {
	  
  ?>
  <tr>
    <td> &nbsp;&nbsp;<?=$info_item['bank_name']?></td>
    <td><?=$info_item['TotalApprovesNum']?>/<?=$info_item['TotalTransNum']?></td>
    <td><a href='reportbottom1.php?hid_companies=&trans_type=&<?=$optdate?>&from_to=&companymode=A&display_test_transactions=0&tele_nontele_type=E&companytrans_type=A&companyname%5B%5D=A&bank_id=<?=$info_item['bank_id']?>&Submit=View+All'>$<?=formatMoney($info_item['TotalApproves'])?></a></td>
    <td>
    <?="<a href='viewBankInvoice.php?bi_pay_info=".serialize($info_item)."&bank_id=".$info_item['bank_id']."&bi_date=".time()."' class='".$info_item['Status']."'>$".formatMoney($info_item['TotalProfit'])."</a>";?></td>
  </tr>
<?php }
  	} 
  }
  ?>
  <tr style="font-weight:bold; ">
    <td> &nbsp;&nbsp;Total</td>
    <td><?=$bi_pay_info['TotalApprovesNum']?>/<?=$bi_pay_info['TotalTransNum']?></td>
    <td><a href='reportbottom1.php?hid_companies=&trans_type=&<?=$optdate?>&from_to=&companymode=A&display_test_transactions=0&tele_nontele_type=E&companytrans_type=A&companyname%5B%5D=A&bank_id=A&Submit=View+All'>$<?=formatMoney($bi_pay_info['TotalApproves'])?></a></td>
    <td><?=formatMoney($bi_pay_info['TotalProfit'])?></td>
  </tr>
  <tr>
    <td>Today's Test Transactions</td>
    <td colspan="3"><a href='reportbottom1.php?hid_companies=&trans_type=&<?=$optdate?>&from_to=&companymode=A&display_test_transactions=1&tele_nontele_type=E&companytrans_type=A&companyname%5B%5D=A&bank_id=A&Submit=View+All'>(
      <?=intval($adminStats['TestTrans'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Today's Test Transaction Companys</td>
    <td colspan="3"><a href='viewCompanyNext.php?userIdList=<?=$adminStats['TestTransUserList']?>'>(
      <?=intval($adminStats['TestTransComps'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Refund Requests</td>
    <td colspan="3"><a href="refundrequests.php?<?=$optdate?>&companymode=A&companytrans_type=A&trans_type=showResult">(
      <?=intval($adminStats['RefundRequestsNew'])?>
      ) New</a>, <a href='refundrequests.php?trans_type=showResult&opt_from_month=8&opt_from_day=12&opt_from_year=2000&from_date=&opt_to_month=8&opt_to_day=12&opt_to_year=2008&companymode=A&companytrans_type=A&companyname%5B%5D=A&x=14&y=11'>(
      <?=intval($adminStats['RefundRequests'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Found Calls</td>
    <td colspan="3"><a href="report_custom.php?<?=$optdate?>&companymode=A&companytrans_type=A&trans_type=showResult">(
      <?=intval($adminStats['FoundCallsNew'])?>
      ) New, <a href='report_custom.php'>(
      <?=intval($adminStats['FoundCalls'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Tickets UnFound Calls</td>
    <td colspan="3">
	( <?=intval($adminStats['NewUnfoundSupportTickets'])?> ) New
	  , ( <?=intval($adminStats['UnfoundSupportTickets'])?> ) Total
	  , ( <?=intval($adminStats['UnfoundSupportTicketsTime'])?> ) Avg Time
	  </td>
  </tr>

  <tr>
    <td>Tickets Found Calls</td>
    <td colspan="3">
	( <?=intval($adminStats['NewFoundSupportTickets'])?> ) New
	  , ( <?=intval($adminStats['FoundSupportTickets'])?> ) Total
	  , ( <?=intval($adminStats['FoundSupportTicketsTime'])?> ) Avg Time
	  </td>
  </tr>

  <tr>
    <td>Tickets Client Calls</td>
    <td colspan="3">
	( <?=intval($adminStats['NewClientSupportTickets'])?> ) New
	  , ( <?=intval($adminStats['ClientSupportTickets'])?> ) Total
	  , ( <?=intval($adminStats['ClientSupportTicketsTime'])?> ) Avg Time
	  </td>
  </tr>

  <tr>
    <td>Support Tickets</td>
    <td colspan="3"><a href='/support/admin/index.php?caseid=home&order=Open&datefrom=<?=$str_to_stamp?>&datefrom=<?=$str_to_stamp?>'>(
      <?=intval($adminStats['SupportTicketsNew'])?>
      ) New</a>, <a href='/support/admin/index.php?caseid=home&order=Open'>(
      <?=intval($adminStats['SupportTickets'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Pending Wires</td>
    <td colspan="3"><a href='/admin/paymentReport.php?mi_status=WirePending#InvoiceHistory'>(
      <?=intval($adminStats['WirePending'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td>Failed Wires</td>
    <td colspan="3"><a href='/admin/paymentReport.php?mi_status=WireFailure#InvoiceHistory'>(
      <?=intval($adminStats['WireFailure'])?>
      ) Total</a></td>
  </tr>
  <tr>
    <td colspan="4" align="center">Generated in <?=(microtime_float()-$timestart)?> Seconds.</td>
</table>
<?php

	endTable("General Stats","");
	
}
	
include("includes/footer.php");
?>
