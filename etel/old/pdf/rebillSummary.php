<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway

include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude="reports";
$periodhead="Ledgers";
include 'includes/header.php';
require_once( 'includes/function.php');


function render_rebills($rebill_details,$link=true)
{
?>
				<table width='100%'  style="border: 1px #000 solid;" cellpadding="0" cellspacing="0" height="60%">
					<tr>
						<td><b>Sub&nbsp;Account</b></td>
						<td>&nbsp;</td>
						<td><b>Description</b></td>
						<td>&nbsp;</td>
						<td><b>#&nbsp;Rebills</b></td>
						<td>&nbsp;</td>
						<td><b>Average&nbsp;Duration</b></td>
					</tr>
<?
			foreach($rebill_details as $rebill)
			{
				$color = $color == "#CCCCCC" ? "#DDDDDD" : "#CCCCCC";
?>
					<tr bgcolor="<?=$color?>">
						<td><? if($link) { ?><a href="rebillSummary.php?view_subaccount=<?=$rebill['rd_subaccount']?>"><?=$rebill['rd_subName']?></a> <? } else { ?> <?=$rebill['rd_subName']?> <? } ?></td>
						<td></td>
						<td><?=$rebill['rd_description']?></td>
						<td></td>
						<td align="center"><?=$rebill['num_rebills']?></td>
						<td></td>
						<td><?=(isset($rebill['avg_duration'])?number_format($rebill['avg_duration'],2)."&nbsp;days":"No&nbsp;Subscriptions")?><?=(isset($rebill['avg_duration'])? "&nbsp;(" . number_format($rebill['avg_duration']/30,2)."&nbsp;months)":"")?></td>
					</tr>
<?
			}
?>				
				</table>
<?
}

$str_adminapproval="";
$sessionlogin = $companyInfo['userId'];
$companyId = $companyInfo['userId'];
if(($_POST['Submit'] == "Cancel Rebill") && (!$display_test_transactions))
{
	foreach($_POST['transid'] as $key=>$id)
	{
		$trans = new transaction_class(false);
		$trans->pull_transaction($id);
		$status = $trans->process_cancel_request(array("actor"=>'Administrator'));
//		exec_cancel_rebill_request_user($id,"Merchant Cancel",$sessionlogin);
	}
}

$qry_details="
	SELECT 
		* 
	FROM 
		`cs_company_sites` 
	WHERE 
		`cs_company_id` = '$sessionlogin' 
		AND `cs_gatewayId` = ".$_SESSION["gw_id"]." 
		AND cs_hide = '0'
	";	
		
	$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteSQL = "";
$siteID = intval($_REQUEST['selectSite']);
if (!$siteID) $siteID = -1;
if ($siteID != -1)  $siteSQL = "AND td_site_ID = $siteID ";

$view_subaccount = isset($_GET['view_subaccount']) ? $_GET['view_subaccount'] : (isset($_POST['view_subaccount']) ? $_POST['view_subaccount'] : "");
$show_declined = isset($_GET['show_declined']) ? $_GET['show_declined'] : (isset($_POST['show_declined']) ? $_POST['show_declined'] : 0);
$summary_display = isset($_GET['summary_display']) ? $_GET['summary_display'] : (isset($_POST['summary_display']) ? $_POST['summary_display'] : "");

$siteList = "";

while($site = mysql_fetch_assoc($rst_details))
	$siteList.= "<option value='".$site['cs_ID']."' ".($site['cs_ID']==$siteID?"selected":"").">".str_replace('http://','',$site['cs_URL'])."</option>";
$stats_list = "";

$compSQL = "";
$compID = $sessionlogin;
if (!$compID) $compID = -1;
$compSQL = "AND ( t.`userId` = '$compID')";
$qry_details="SELECT * FROM `cs_companydetails` WHERE `userId` = '$compID'";
$rst_details=sql_query_read($qry_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$companyInfo = mysql_fetch_assoc($rst_details);

if($companyInfo['cd_enable_price_points']==0) die("Rebilling and Price Points are Disabled");

$forcomp = "";
if ($companyInfo['companyname']) $forcomp = " for ".$companyInfo['companyname'];



//echo "sub account: ".$view_subaccount."<br>";
if($view_subaccount == "")
{
			$sql = "
				SELECT 
					*
				FROM
					cs_rebillingdetails
				WHERE
					company_user_id = \"$sessionlogin\"				
					AND rd_enabled=1
				";
			
			$res = sql_query_read($sql) or dieLog("error $sql");
			$rebill_details = array();
			while($row = mysql_fetch_assoc($res))
				$rebill_details[$row['rd_subaccount']] = $row;

			$isrebill = $_REQUEST['rebillstatus']==0 ? "" : " AND td_is_a_rebill = 1";

			$base_sql = "
				SELECT 
					SUM(`recur_charge`*(30/`recur_day`)) as amt, 
					COUNT(`amount`) as cnt 
				FROM
					cs_transactiondetails AS t
				LEFT JOIN cs_rebillingdetails AS r ON t.td_rebillingID = r.rd_subaccount
				WHERE 
					1
					$siteSQL 
					$isrebill
					AND t.td_non_unique = 0
					AND t.userId = \"$sessionlogin\"
			";

			$list_sql = "
				SELECT 
					t.transactionId,
					t.reference_number,
					t.amount,
					t.td_recur_next_date,
					r.recur_day,
					t.name,
					t.surname,
					r.rd_subaccount,
					t.td_enable_rebill,
					t.status,
					t.cancelstatus,
					t.cancel_refer_num,
					t.td_recur_processed,
					t.td_recur_attempts,
					t.td_is_chargeback
				FROM
					cs_transactiondetails AS t
				LEFT JOIN cs_rebillingdetails AS r ON t.td_rebillingID = r.rd_subaccount
				WHERE 
					1
					$siteSQL 
					$isrebill
					AND t.td_non_unique = 0
					AND t.userId = \"$sessionlogin\"
			";			
			$rebill_summary['01|Active Rebills']['sql'] = array("list" => $list_sql, "summary" => $base_sql, "limit" => "AND td_recur_attempts < 3 AND td_enable_rebill = 1 AND t.status = \"A\"");
			$rebill_summary['02|Declined Rebills']['sql'] = array("list" => $list_sql, "summary" => $base_sql, "limit" => "AND td_enable_rebill = 1 AND t.status = 'D'");
			$rebill_summary['03|Cancelled Rebills']['sql'] = array("list" => $list_sql, "summary" => $base_sql, "limit" => "AND t.td_enable_rebill = 0");
			$rebill_summary['04|Gave Up']['sql'] = array("list" => $list_sql, "summary" => $base_sql, "limit" => "AND td_recur_attempts >= 3 AND td_enable_rebill = 1 AND t.status = 'A'");
			
			$sql = "
				SELECT
					t.userId,
					t.td_rebillingID, 
					t.td_subscription_id, 
					MAX(t.transactionDate) AS max,
					MIN(t.transactionDate) AS min,
					SUM(IF(t.td_recur_attempts < 3 AND t.td_enable_rebill = 1 AND t.status = 'A',1,0)) AS num_rebills,
					(MAX(UNIX_TIMESTAMP(t.transactionDate)) - MIN(UNIX_TIMESTAMP(t.transactionDate))) as duration
				FROM
					cs_transactiondetails AS t
				LEFT JOIN cs_rebillingdetails AS r ON t.td_rebillingID = r.rd_subaccount
				WHERE
					t.userId = \"$sessionlogin\"
					AND r.rd_enabled=1
					$isrebill
					AND t.td_non_unique = 0
					$siteSQL
				GROUP BY t.td_subscription_id,t.td_rebillingID
				";
				
			$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");
			$list = array();
			
			while($row = mysql_fetch_assoc($res))
			{
				$row['duration'] = $row['duration']/60/60/24;
				$list[$row['td_rebillingID']][] = $row;
			}
			
			//echo "<pre>";
			//print_r($list);
			//echo "</pre>";
			
			foreach($list as $rebill_id => $details)
			{
				$j=0;
				$days = 0;
				$total_rebills = 0;
				foreach($details as $detail)
				{
					$days += $detail['duration'];
					$total_rebills += $detail['num_rebills'];
					$j++;
				}
				$days /= $j;
				$rebill_details[$rebill_id]['avg_duration'] = $days;
				$rebill_details[$rebill_id]['num_subscriptions'] = $j;
				$rebill_details[$rebill_id]['num_rebills'] = $total_rebills;
			}
			
	beginTable();
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="lgnbd" colspan="5" height="10">
					<select name="selectSite" id="selectSite">
				    <option value="-1">All Sites</option>
				    <?=$siteList?>
				    </select>
					<select name="rebillstatus" id="rebillstatus">
					<option value="0">Processed Rebills</option>
					<option value="1" <?=($_REQUEST['rebillstatus']==1?"selected":"")?>>Pending Rebills</option>
					</select>
					<input type="submit" value="Update"><br />
						Use this Dropdown List to select Rebill Statistics for individual sites. 
				</td>
			</tr>
		</table>
<?
	endTable("Options","rebillSummary.php",NULL,NULL,FALSE);
	echo "<a name='rebill_summary'></a>";
	beginTable();

	echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
	$color = $color == "#CCCCCC" ? "#DDDDDD" : "#CCCCCC";
	echo "<tr bgcolor=\"$color\"><td></td><td><b>Count</b></td><td><b>Value</b></td></tr>";
	ksort($rebill_summary);
	reset($rebill_summary);
	foreach($rebill_summary as $title => $info)
	{
		$res = sql_query_read($info['sql']['summary'] . $info['sql']['limit']) or dieLog("error " . mysql_error() . "<pre>" . $info['sql'] . "</pre>");
		$stats = mysql_fetch_assoc($res);
		$title = explode("|",$title);
		$title = $title[1];
		$color = $color == "#CCCCCC" ? "#DDDDDD" : "#CCCCCC";

		$link = "<a href='rebillSummary.php?view_subaccount=$view_subaccount&show_declined=$show_declined&summary_display=" . urlencode($title) . "#rebill_summary'>$title</a>";
		$closelink = "<a href='rebillSummary.php?view_subaccount=$view_subaccount&show_declined=$show_declined#" . urlencode($title) . "'>$title</a>";

		
		if($stats['cnt'] == 0)
			echo "<tr bgcolor=\"$color\"><td><b>$title</td><td>" . $stats['cnt'] . "</td><td>\$" . number_format($stats['amt'],2) . "</td></tr>";
		else
			if(!strcasecmp($title,$summary_display))
				echo "<tr bgcolor=\"$color\"><td><b>$closelink</td><td>" . $stats['cnt'] . "</td><td>\$" . number_format($stats['amt'],2) . "</td></tr>";
			else
				echo "<tr bgcolor=\"$color\"><td><b>$link</td><td>" . $stats['cnt'] . "</td><td>\$" . number_format($stats['amt'],2) . "</td></tr>";
		
		if($stats['cnt'] > 0)
			if(!strcasecmp($title,$summary_display))
		{
			$res = sql_query_read($info['sql']['list'] . $info['sql']['limit']) or dieLog("error " . mysql_error() . "<pre>" . $info['sql']['list'] . $info['sql']['limit'] . "</pre>");

			echo "<tr><td colspan=3>";
		?>
				<table width='100%'  style="border: 1px #000 solid;" cellpadding="0" cellspacing="0" height="60%">
					<tr>
					<td><b>Reference Number</b></td>
					<td>&nbsp;</td>
					<td><b>Name</b></td>
					<td>&nbsp;</td>
					<td><b>Amount</b></td>
					<td>&nbsp;</td>
					<td><b>Rebills Every</b></td>
					<td>&nbsp;</td>
					<td><b>Next Rebill Date</b></td>
					</tr>
		<?
			while($tran = mysql_fetch_assoc($res))
			{
				$color = $color == "#CCCCCC" ? "#DDDDDD" : "#CCCCCC";
	?>
					<tr bgcolor="<?=$color?>">
					<td>
					<?
						echo "&nbsp;<font face='verdana' size='1'><b><a href='viewreportpage.php?id=" . $tran['transactionId'] . "'>" . $tran['reference_number'] . "</a></b></font>";
	//							echo $tran['reference_number']
					?>
					</td>
					<td>&nbsp;</td>
					<td><?=$tran['surname']?>, <?=$tran['name']?></td>
					<td>&nbsp;</td>
					<td><?=number_format($tran['amount'],2)?></td>
					<td>&nbsp;</td>
					<td><?=$tran['recur_day']?> days</td>
					<td>&nbsp;</td>
					<td><?=$tran['td_recur_next_date']?></td>
					</tr>
	<?
			}
			echo "</td></tr>";
			echo "</table>";
		}
	}
	echo "</table>";

	endTable("Rebill Summary");

	beginTable();
?>

			   <div align="justify"><font face='verdana' size='1'><b>Note: This page provides an estimate of this company's monthly rebilling income. This page also provides a list of the current active rebill accounts. This page does NOT reflect the actual transactions in effect for this company at this time. The Estimation shown on this page is based only on current active rebill accounts and may be affected by customer cancelations, chargebacks, and declines. </b></font></div>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan=5>
<?

			render_rebills($rebill_details);
			?>
				</td>
			</tr>
		</table>
<?
	endTable("Sub Account Summary","rebillSummary.php",NULL,NULL,TRUE);
}
else
{
	beginTable();
?>	
	<input type="hidden" name="view_subaccount" value="<?=$view_subaccount?>">
	<input type="hidden" name="show_declined" value="<?=$show_declined?>">
       	<div align="justify"><font face='verdana' size='1'><b>Note: This page provides an estimate of this company's monthly rebilling income. This page also provides a list of the current active rebill accounts. This page does NOT reflect the actual transactions in effect for this company at this time. The Estimation shown on this page is based only on current active rebill accounts and may be affected by customer cancelations, chargebacks, and declines. </b></font></div>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="lgnbd" colspan="5" height="10">
					<select name="selectSite" id="selectSite">
				    <option value="-1">All Sites</option>
				    <?=$siteList?>
				    </select>
					<select name="rebillstatus" id="rebillstatus">
					<option value="0">Processed Rebills</option>
					<option value="1" <?=($_REQUEST['rebillstatus']==1?"selected":"")?>>Pending Rebills</option>
					</select>
					<input type="submit" value="Update"> | <a href="rebillSummary.php">View All Sub Accounts</a><br />
						Use this Dropdown List to select Rebill Statistics for individual sites. 
				</td>
			</tr>
			<tr>
				<td colspan=5>
<?
			$sql = "
				SELECT 
					*
				FROM
					cs_rebillingdetails
				WHERE
					company_user_id = \"$sessionlogin\"
					AND rd_subaccount = \"$view_subaccount\"
				";
			
			$res = sql_query_read($sql) or dieLog("error $sql");
			$rebill_details = array();
			while($row = mysql_fetch_assoc($res))
				$rebill_details[$row['rd_subaccount']] = $row;
			
			$isrebill = $_REQUEST['rebillstatus']==0 ? "" : " AND td_is_a_rebill = 1";
			
			$sql = "
				SELECT
					t.transactionId,
					t.reference_number,
					t.amount,
					t.td_recur_next_date,
					r.recur_day,
					t.name,
					t.surname,
					r.rd_subaccount,
					t.td_enable_rebill,
					t.status,
					t.cancelstatus,
					t.cancel_refer_num,
					t.td_recur_processed,
					t.td_recur_attempts,
					t.td_is_chargeback
				FROM
					cs_transactiondetails AS t
				LEFT JOIN cs_rebillingdetails AS r ON t.td_rebillingID = r.rd_subaccount
				WHERE
					t.userId = \"$sessionlogin\"
					AND r.rd_subaccount = \"$view_subaccount\"
					AND r.rd_enabled=1
					AND t.td_recur_processed = 0
					AND t.td_non_unique = 0
					$isrebill
					$siteSQL
				ORDER BY t.td_recur_next_date DESC, t.surname ASC
				";
				
				
			$res = sql_query_read($sql) or dieLog("error ". mysql_error() . "<br><pre>$sql</pre>");
			$transactions = array();
			$ref_numbers = array();
			while($row = mysql_fetch_assoc($res))
			{
				if(!isset($ref_numbers[$row['reference_number']]))
				{
					$ref_numbers[$row['reference_number']] = 1;
					
					$isarebill = ($row['td_recur_attempts'] < 3 && $row['td_enable_rebill'] == 1 && $row['status'] == 'A');
					$isgaveup = ($row['td_recur_attempts'] >= 3 && $row['td_enable_rebill'] == 1 && $row['status'] == 'A');
					$iscancelled = ($row['td_enable_rebill'] == "0" ) ;
					$isdeclined = ($row['status'] == 'D' && $row['td_enable_rebill'] == 1);
					$isrefund = (($row['cancelstatus'] == "Y" ));
					$ischargeback = ($row['td_is_chargeback']==1);
					$ispending = ($row['status']=='P');
					
					if($isarebill)
					$transactions[$row['rd_subaccount']]["01|" . "rebill"][] = $row;
					elseif($iscancelled)
					$transactions[$row['rd_subaccount']]["02|" . "cancelled"][] = $row;
					elseif($isdeclined)
					$transactions[$row['rd_subaccount']]["03|" . "declined"][] = $row;
					elseif($isrefund)
					$transactions[$row['rd_subaccount']]["04|" . "refund"][] = $row;
					elseif($ischargeback)
					$transactions[$row['rd_subaccount']]["05|" . "chargeback"][] = $row;
					elseif($isgaveup)
					$transactions[$row['rd_subaccount']]["06|" . "gave up"][] = $row;
					elseif($ispending)
					$transactions[$row['rd_subaccount']]["07|" . "is pending"][] = $row;
					else
					$transactions[$row['rd_subaccount']]["08|" . "unknown"][] = $row;
				}
			}

			//echo "<pre>";
			//print_r($transactions);
			//echo "</pre>";
			
			$sql = "
				SELECT
					t.userId,
					t.td_rebillingID, 
					t.td_subscription_id, 
					MAX(t.transactionDate) AS max,
					MIN(t.transactionDate) AS min,
					SUM(IF(t.td_recur_attempts < 3 AND t.td_enable_rebill = 1 AND t.status = 'A',1,0)) AS num_rebills,
					(MAX(UNIX_TIMESTAMP(t.transactionDate)) - MIN(UNIX_TIMESTAMP(t.transactionDate))) as duration
				FROM
					cs_transactiondetails AS t
				LEFT JOIN cs_rebillingdetails AS r ON t.td_rebillingID = r.rd_subaccount
				WHERE
					t.userId = \"$sessionlogin\"
					AND t.td_rebillingID = $view_subaccount
					AND r.rd_enabled=1
					$isrebill
					AND t.td_non_unique = 0
					$siteSQL
				GROUP BY t.td_subscription_id,t.td_rebillingID
				";

			$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");
			$list = array();
			
			while($row = mysql_fetch_assoc($res))
			{
				$row['duration'] = $row['duration']/60/60/24;
				$list[$row['td_rebillingID']][] = $row;
			}
			
			//echo "<pre>$sql<br>";
			//print_r($list);
			//echo "</pre>";
			
			foreach($list as $rebill_id => $details)
			{
				$j=0;
				$days = 0;
				$total_rebills = 0;
				foreach($details as $detail)
				{
					$days += $detail['duration'];
					$total_rebills += $detail['num_rebills'];
					$j++;
				}
				$days /= $j;
				$rebill_details[$rebill_id]['avg_duration'] = $days;
				$rebill_details[$rebill_id]['num_subscriptions'] = $j;
				$rebill_details[$rebill_id]['num_rebills'] = $total_rebills;
			}
			render_rebills($rebill_details,false);

			if(isset($transactions[$view_subaccount]))
			{
				ksort($transactions[$view_subaccount]);
				reset($transactions[$view_subaccount]);
				
				foreach($transactions[$view_subaccount] as $type => $trans)
				{
					$type = explode("|",$type);
					$type = $type[1];
					//echo $show_declined . " $type<br>";
					if($show_declined==1 || strcasecmp($type,"declined") !=0)
					{
						$total_value = 0;
						echo "<p><b>".ucwords($type)."</b>";
		?>
							<table width='100%'  style="border: 1px #000 solid;" cellpadding="0" cellspacing="0" height="60%">
								<tr>
								<td><b>Reference Number</b></td>
								<td>&nbsp;</td>
								<td><b>Name</b></td>
								<td>&nbsp;</td>
								<td><b>Amount</b></td>
								<td>&nbsp;</td>
								<td><b>Rebills Every</b></td>
								<td>&nbsp;</td>
								<td><b>Next Rebill Date</b></td>
								</tr>
		<?
						foreach($trans as $key => $tran)
						{
							$color = $color == "#CCCCCC" ? "#DDDDDD" : "#CCCCCC";
		?>
								<tr bgcolor="<?=$color?>">
								<td>
								<?
									if($_REQUEST['rebillstatus']==1 && strcasecmp($type,"rebill")==0)
										echo "<input type=\"checkbox\" name=\"transid[]\" value=\"" . $tran['transactionId'] . "\" id=\"transid\">";
									echo "&nbsp;<font face='verdana' size='1'><b><a href='viewreportpage.php?id=" . $tran['transactionId'] . "'>" . $tran['reference_number'] . "</a></b></font>";
		//							echo $tran['reference_number']
								?>
								</td>
								<td>&nbsp;</td>
								<td><?=$tran['surname']?>, <?=$tran['name']?></td>
								<td>&nbsp;</td>
								<td><?=number_format($tran['amount'],2)?></td>
								<td>&nbsp;</td>
								<td><?=$tran['recur_day']?> days</td>
								<td>&nbsp;</td>
								<td><?=$tran['td_recur_next_date']?></td>
								</tr>
		<?
								$total_value += $tran['amount'];
						}
						echo "<tr><td colspan=9 align='right'><b>Total Amount:</b> \$" . number_format($total_value,2) . "</td></tr>";
						if($_REQUEST['rebillstatus']==1 && strcasecmp($type,"rebill")==0)
							echo "<tr><td colspan=9><input type='Submit' name='Submit' value='Cancel Rebill'></td></tr>";
						echo "</table></p>";
					}
				}
			}			
			if(!$show_declined)
				echo "<center><a href='rebillSummary.php?rebillstatus=" . $_REQUEST['rebillstatus'] . "&view_subaccount=$view_subaccount&show_declined=1'>Show Declined Rebills</a></center>";
			else
				echo "<center><a href='rebillSummary.php?rebillstatus=" . $_REQUEST['rebillstatus'] . "&view_subaccount=$view_subaccount&show_declined=0'>Hide Declined Rebills</a></center>";
?>				
				</td>
			</tr>
		</table>
<?
	endTable("Rebilling Summary","rebillSummary.php",NULL,NULL,TRUE);
}
include("includes/footer.php");
?>

