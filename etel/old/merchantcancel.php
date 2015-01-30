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
//viewreportpage.php:	The client page functions for viewing the company transaction details. 
include '../includes/dbconnection.php';
require_once( '../includes/function.php');

$canceldate = func_get_current_date_time(); 
$cancel = (isset($HTTP_POST_VARS['cancel'])?Trim($HTTP_POST_VARS['cancel']):"");
$trans_id = (isset($HTTP_POST_VARS['tid'])?Trim($HTTP_POST_VARS['tid']):"");
$user_id = (isset($HTTP_POST_VARS['user_id'])?Trim($HTTP_POST_VARS['user_id']):"");
$crorcq1 = (isset($HTTP_POST_VARS['crorcq1'])?Trim($HTTP_POST_VARS['crorcq1']):"");
$str_bill_date = (isset($HTTP_POST_VARS['billDate'])?Trim($HTTP_POST_VARS['billDate']):"");
$cancelreason = "Customer cancel";
$msgtodisplay="";
if($trans_id !="") {
	if($cancel!="") 
	{
		$str_is_cancelled = func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancelstatus","transactionId",$return_insertId);
		//$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
		if($str_is_cancelled == "Y") 
		{
			$outhtml="y";
			$msgtodisplay="This transaction has been already canceled";
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		} 
		else 
		{	
			$strCurrentDateTime = func_get_current_date();
			$str_approval_status = func_get_value_of_field($cnn_cs,"cs_transactiondetails","status","transactionId",$return_insertId);
			if($strCurrentDateTime >= $str_bill_date && $str_approval_status == "A") { 
				$qrt_update_details ="Update cs_transactiondetails set reason='$cancelreason',cancellationDate='$canceldate',admin_approval_for_cancellation = 'P' where transactionId=$trans_id and userid=$user_id";
				if(!($qrt_update_run = mysql_query($qrt_update_details)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} else {
					$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transaction has been canceled and is waiting for Admin's Approval.</font></td></tr><tr><td align='center'><a href='index.htm'><img border='0' src='images/back_tran.gif'></a></td></tr></table>";
				} 
			} else {
				$return_insertId = func_transaction_updatenew($trans_id,$cnn_cs);
				$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='$cancelreason',cancellationDate='$canceldate' where transactionId=$trans_id and userid=$user_id";
				//print($qryUpdate."<br>");
				if(!mysql_query($qryUpdate,$cnn_cs))
				{
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query cancel update query");
					exit();
				} else {
					$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transactions has been canceled.</font></td></tr><tr><td align='center'><a href='index.htm'><img border='0' src='images/back_tran.gif'></a></td></tr></table>";
				}
				if($crorcq1 == "C")
				{
					func_send_cancel_mail($user_id,$crorcq1);
				}
				func_canceledTransaction_receipt($user_id, $return_insertId,$cnn_cs);
			}
		}



		$qrt_update_details ="Update cs_transactiondetails set cancelstatus='Y', passStatus='ND',reason='$cancelreason' where transactionId=$trans_id";
		$qrt_update_details1 = "Update cs_transactiondetails set cancellationDate='$canceldate' where transactionId=$trans_id";
		if(!($qrt_update_run = mysql_query($qrt_update_details)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		} else {
			$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>Selected transactions has been canceled.</font></td></tr><tr><td align='center'><a href='index.htm'><img border='0' src='images/back_tran.gif'></a></td></tr></table>";
		}
		 if(mysql_affected_rows()==0) 
		 {
			$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'>This transaction has been already canceled.</font></td></tr><tr><td align='center'><a href='index.htm'><img border='0' src='images/back_tran.gif'></a></td></tr></table>";
		 } 
		if(!($qrt_update_run1 = mysql_query($qrt_update_details1)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		
	}
}
?>

<html>

<head>
<title>Company Setup</title>
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
	<td class="whitebtbd"><img border="0" src="images/logo_etelegate.gif" width="108" height="43"><img border="0" src="images/cards_tran.gif" width="199" height="23"></td>
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
<table border="0" cellpadding="0" width="96%" height="249">
<tr>
							<td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="100%" height="178" colspan="2" valign="top"><img border="0" src="images/service_pic.jpg" width="160" height="176"></td>
						</tr>
					</table>
				</td>
				
    <td height="25" valign="top" align="center" width="599"> <br>
	<?php
	if($msgtodisplay !="") {
		print $msgtodisplay;
	}
	?>

    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="20">
			<tr>
				<td height="25" bgcolor="#3D8287"></td>
			</tr>
		</table>
</body>
</html>