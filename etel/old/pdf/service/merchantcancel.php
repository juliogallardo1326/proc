<?php
		include("../includes/sessioncheckserviceuser.php");
		
		include("../includes/dbconnection.php");
		require_once("../includes/function.php");
		require_once('../includes/function2.php');
		include("../admin/includes/serviceheader.php");
		$headerInclude = "service";
		$rootdir = "../";
		include("../includes/header.php");

		$str_duration = $_SESSION['duration_start'];
		if(!$str_duration)
		{
			$str_hour = "0";
			$str_min = "00";
			$str_sec = "0";
		}
		else
		{
			$duration_info =getdate(time()-$str_duration);
			$str_hour = floor((time()-$str_duration)/(60*60))%24;
			if (strlen($str_hour) == 1) $str_hour = "0".$str_hour;
			$str_min = floor((time()-$str_duration)/(60))%60;
			if (strlen($str_min) == 1) $str_min = "0".$str_min;
			$str_sec = floor((time()-$str_duration))%60;
			if (strlen($str_sec) == 1) $str_sec = "0".$str_sec;
			
			$strCallDuration = $str_hour.":".$str_min.":".$str_sec;
		}
		$strBillDate = "";
		$i_gateway_id = -1;
		
		
$canceldate = func_get_current_date_time(); 
$cancel = (isset($HTTP_POST_VARS['cancel'])?quote_smart($HTTP_POST_VARS['cancel']):"");
$refund = (isset($HTTP_POST_VARS['refund'])?quote_smart($HTTP_POST_VARS['refund']):"");
$trans_id = (isset($HTTP_POST_VARS['tid'])?quote_smart($HTTP_POST_VARS['tid']):"");
$reference_number = (isset($HTTP_POST_VARS['reference_number'])?quote_smart($HTTP_POST_VARS['reference_number']):"");
$user_id = (isset($HTTP_POST_VARS['user_id'])?quote_smart($HTTP_POST_VARS['user_id']):"");
$crorcq1 = (isset($HTTP_POST_VARS['crorcq1'])?quote_smart($HTTP_POST_VARS['crorcq1']):"");
$str_bill_date = (isset($HTTP_POST_VARS['billDate'])?quote_smart($HTTP_POST_VARS['billDate']):"");
$note_id = (isset($HTTP_POST_VARS['note_id'])?quote_smart($HTTP_POST_VARS['note_id']):"");
$msgtodisplay="";
if($trans_id !="") {
	$ref_no = func_Trans_Ref_No($trans_id);

$table = "`cs_transactiondetails` as t ";

$show_select_val = getTransactionInfo($trans_id,false);

	if($refund!="") 
	{
		$etel_debug_mode = 0;
		$msg = exec_refund_request($trans_id,"Customer Service Refund","$cancelreason:$cancelreasonother");
		$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>$msg. Callback through '$contactmethod'.<br></font></td></tr><tr><td align='center'><a href='startpage.php'><img border='0' src='../images/back.gif'></a></td></tr></table>";
	}
	else if($cancel!="") 
	{
	$cancelreason = (isset($HTTP_POST_VARS['selectReason'])?quote_smart($HTTP_POST_VARS['selectReason']):"");
	$cancelreasonother = (isset($HTTP_POST_VARS['txtNotes'])?quote_smart($HTTP_POST_VARS['txtNotes']):"");

		//$str_is_cancelled = func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancelstatus","transactionId",$return_insertId);
		if($show_select_val['td_enable_rebill'] == "0") 
		{
			$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>This transaction/subscription has been already canceled.<br>You will no longer be rebilled for this subscription.</font></td></tr><tr><td align='center'><a href='startpage.php'><img border='0' src='../images/back.gif'></a></td></tr></table>";
		} 
		else 
		{	
			$strCurrentDateTime = func_get_current_date();		
			$str_approval_status = $show_select_val['status'];
			if($strCurrentDateTime >= $str_bill_date && $str_approval_status == "A") { 
				$qrt_update_details ="Update cs_transactiondetails set td_enable_rebill='0' where transactionId=$trans_id  AND reference_number = '$reference_number'";// and userid=$user_id";
				if(!($qrt_update_run = mysql_query($qrt_update_details)))
				{
					print(mysql_errno().": ".mysql_error()."<BR>");
					die("Cannot execute query '$qrt_update_details'");
				} else {
					$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transaction has been canceled and is waiting for Admin's Approval.</font></td></tr><tr><td align='center'><a href='startpage.php'><img border='0' src='../images/back_tran.gif'></a></td></tr></table>";
				} 
			} else {
				$return_insertId = func_transaction_updatenew($trans_id,$cnn_cs);
				$qryUpdate = "update cs_transactiondetails set td_enable_rebill='0' where transactionId=$trans_id";
				//print($qryUpdate."<br>");
				if(!mysql_query($qryUpdate,$cnn_cs))
				{
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query cancel update query");
					exit();
				} else {
					$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transaction has been canceled.<br>You will no longer be rebilled for this subscription.</font></td></tr><tr><td align='center'><a href='startpage.php'><img border='0' src='../images/back.gif'></a></td></tr></table>";
				
				
				}
				if($crorcq1 == "C")
				{
					//func_send_cancel_mail($user_id,$crorcq1);
				}
				func_cancel_notify_email($user_id, $return_insertId,$cnn_cs);
			}
		}



		$qrt_update_details ="Update cs_transactiondetails set td_enable_rebill='0' where transactionId=$trans_id";
		$qrt_update_details1 = "Update cs_transactiondetails set td_enable_rebill='0' where transactionId=$trans_id";
		if(!($qrt_update_run = mysql_query($qrt_update_details)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		} else {
			$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transaction has been canceled.<br>You will no longer be rebilled for this subscription.</font></td></tr><tr><td align='center'><a href='startpage.php'><img border='0' src='../images/back.gif'></a></td></tr></table>";
		}
		if(!($qrt_update_run1 = mysql_query($qrt_update_details1)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		
		$canceldate = func_get_current_date_time(); 
$cancel = (isset($HTTP_POST_VARS['cancel'])?quote_smart($HTTP_POST_VARS['cancel']):"");
$trans_id = (isset($HTTP_POST_VARS['tid'])?quote_smart($HTTP_POST_VARS['tid']):"");
$user_id = (isset($HTTP_POST_VARS['user_id'])?quote_smart($HTTP_POST_VARS['user_id']):"");
$crorcq1 = (isset($HTTP_POST_VARS['crorcq1'])?quote_smart($HTTP_POST_VARS['crorcq1']):"");
$str_bill_date = (isset($HTTP_POST_VARS['billDate'])?quote_smart($HTTP_POST_VARS['billDate']):"");
$cancelreason = (isset($HTTP_POST_VARS['selectReason'])?quote_smart($HTTP_POST_VARS['selectReason']):"");
if (strtolower($cancelreason) == "other") $cancelreasonother =(isset($HTTP_POST_VARS['txtNotes'])?quote_smart($HTTP_POST_VARS['txtNotes']):"");
		// Log Entry
		$i_customer_service_id = $_SESSION["sessionServiceUserId"];
		$sql="REPLACE INTO `cs_callnotes` (`note_id`, `transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type` )
			VALUES ('$note_id', '$trans_id', NOW() , 'Customer Service Refund', '', '$cancelreason:$cancelreasonother', '' , '', '', '', '', '', 'foundcall');";
		if(!$show_select_val['hasRefundRequest']) $qry_callnotes = mysql_query($sql) or dieLog("Cannot execute query ");
		
	}
}
?>

<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: white;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #999999 
}
.TextBox
{
font-face:verdana;font-size:10px
}
</style>
<script>
function validation() {
	if(document.FrmName.checkcardno.value == "") {
		alert("Please enter the credit card / check #.");
		document.FrmName.checkcardno.focus();
		return false;
	}
	if(document.FrmName.transactionno.value == "" && document.FrmName.voiceauthno.value =="") {
		alert("Please enter the voice authorization # or transaction #.");
		document.FrmName.transactionno.focus();
		return false;
	}
}
</script>

</head>

<body topmargin="0" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1">
<tr>
	<td class="whitebtbd">&nbsp;</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" bgcolor="#658343" class="blkbd1">
<tr>
	<td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="460">
			<tr>
								<td height="25" valign="top" align="center" width="165" bgcolor="#FFFFFF">
					<table border="0" cellpadding="0" width="100%" height="249">
						<tr>
							<td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="100%" height="178" colspan="2" valign="top"><img border="0" src="../images/service_pic.jpg" width="160" height="176"></td>
						</tr>
					</table>
				</td>
    <td height="25" valign="top" align="center" > <br>
	<?php
	if($msgtodisplay !="") {
		print $msgtodisplay;
	}
	?>

    </td>
  </tr>
</table>
<?php
	include("../includes/footer.php");
?>	