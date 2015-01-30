<?php
		include("../includes/sessioncheckserviceuser.php");
		
		require_once("../includes/function.php");
		include("../admin/includes/message.php");
		include("../admin/includes/serviceheader.php");
		$headerInclude = "service";
		include("../admin/includes/topheader.php");

		$str_duration = (isset($HTTP_GET_VARS["duration"])?quote_smart($HTTP_GET_VARS["duration"]):"");
		if($str_duration == "")
		{
			$str_hour = "0";
			$str_min = "00";
			$str_sec = "0";
		}
		else
		{
			$str_hour = intval(substr($str_duration,0,2));
			$str_min = intval(substr($str_duration,3,2));
			$str_sec = intval(substr($str_duration,6,2));
		}
		$strBillDate = "";
		$i_gateway_id = -1;
?>
<div id="time" style="position:absolute; width:370px; height:40px; z-index:1; overflow: hidden;left:15;top:200">
<table><tr><td>
<form name="form_counter"><font face="verdana" color="#999999" size="2"><b>Time Elapsed:&nbsp;</b></font><input type="text" value="" name="counter" style="border:0px;font-face:verdana;font-weight:bold;Color:#999999"></form>
</td></tr></table>
</div>

<script language="javascript" src="../scripts/general.js"></script>
<script language="JavaScript">
function func_validate(obj_form)
{
	var b_correct = true;
	obj_element = obj_form.txtName;
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter name");
		b_correct = false;
		obj_element.focus();
	}	
	obj_element = obj_form.txtPhone
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter phone number");
		b_correct = false;
		obj_element.focus();
	}
	obj_element = obj_form.txtNotes
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter notes");
		b_correct = false;
		obj_element.focus();
	}
	if(b_correct)
	{
		stopTimer();
		obj_form.hid_counter.value = document.form_counter.counter.value;
	}
	return b_correct;
}
function func_add_notes(obj_form,orig_bill_date)
{
	var isValid = true;
	//if((obj_form.hid_cancel.value = "Y") && (obj_form.txtNotes.value == ""))
	if(!obj_form.chkCallBack.checked && !obj_form.chkSolved.checked && obj_form.chkCancel != null && !obj_form.chkCancel.checked)
	{
		isValid = false;
		alert("Please check either the 'Company Call Back', 'Solved', or 'Cancel' Button");
	}
	if(isValid && obj_form.txtNotes.value == "")
	{
		isValid = false;
		alert("Please enter the notes");
		obj_form.txtNotes.focus();
	}
	if(isValid && obj_form.chkChangedBillDate != null)
	{
		if(obj_form.chkChangedBillDate.checked)
		{
			var new_bill_day = obj_form.opt_bill_day[obj_form.opt_bill_day.selectedIndex].value;
			var new_bill_month = obj_form.opt_bill_month[obj_form.opt_bill_month.selectedIndex].value;
			var new_bill_year = obj_form.opt_bill_year[obj_form.opt_bill_year.selectedIndex].value;
			if(new_bill_day.length <= 1)
				new_bill_day = "0" + new_bill_day;
			if(new_bill_month.length <= 1)
				new_bill_month = "0" + new_bill_month;
			var new_bill_date = new_bill_year + "-" +new_bill_month + "-" +new_bill_day;
			if(orig_bill_date == new_bill_date)
			{
				isValid = false;
				alert("Please select a new Bill Date");
				obj_form.txtNotes.focus();
			}

			if(isValid && !ValidateDateBox("billing date",obj_form,new_bill_day,new_bill_month,new_bill_year,1,1,0))
			{
				obj_form.opt_bill_month.focus();
				isValid = false;		
			}
		}
	}
	if(isValid)
	{
		stopTimer();
		obj_form.hid_counter.value = document.form_counter.counter.value;
		obj_form.action = "addnotes.php";
		obj_form.method="post";
		obj_form.submit();
	}	
}
/*function funcShowPreviousCalls(iTransactionId)
{
	window.open("previouscalls.php?id="+iTransactionId,null,"height=300,width=600,status=yes,toolbar=no,menubar=no,location=no,scrollbars=1");
}*/
function funcShowPreviousCalls(iTransactionId,phoneNumber,searchMode,callNoteId)
{
	var duration = document.form_counter.counter.value;
	window.location="previouscalls.php?id="+iTransactionId+"&phoneNumber="+phoneNumber+"&searchMode="+searchMode+"&callNoteId="+callNoteId+"&duration="+duration;
}
function funcCancel(obj_form)
{
	if(confirm("Are you sure you would like to cancel this order?")){
		obj_form.chkCancel.checked = true;
	}
	else{
		obj_form.chkCancel.checked = false;
	}
}
function funcCallBack(iTransactionId)
{
	window.open("callback.php?id="+iTransactionId,null,"height=100,width=400,status=yes,toolbar=no,menubar=no,location=no");
}

function cancel()
{
	if( document.frm_result.chkCancel.checked == true )
	{
		document.frm_result.chkCancel.checked = false;
		if( confirm("Are you sure you would like to cancel this order?") )
			document.frm_result.chkCancel.checked = true;
	}
}

var hour = "<?=$str_hour?>";
var min = "<?=$str_min?>";
var sec = "<?=$str_sec?>";
var timerId = null;
function timer(){
if ((min < 10) && (min != "00")){
	dismin = "0" + min
}
else{
	dismin = min
}

	dissec = (sec < 10) ? sec = "0" + sec : sec
	dishour = (hour < 10) ? "0" + hour : hour
	document.form_counter.counter.value = dishour + ":" + dismin + ":" + dissec

	if (sec < 59){
		sec++
	}
	else{
		sec = "0"
		min++
		if (min > 59){
			min = "00"
			hour++
		}
	}
timerId = window.setTimeout("timer()",1000); 

}
window.setTimeout("timer()",0); 

function stopTimer()
{
	clearTimeout(timerId);
}

</script>
		<?php
			$txt_cc = (isset($HTTP_GET_VARS["txt_cc"])?quote_smart($HTTP_GET_VARS["txt_cc"]):"");
			$txt_telephone = (isset($HTTP_GET_VARS["txt_telephone"])?quote_smart($HTTP_GET_VARS["txt_telephone"]):"");
			$txt_email = (isset($HTTP_GET_VARS["txt_email"])?quote_smart($HTTP_GET_VARS["txt_email"]):"");
			$txt_reference = (isset($HTTP_GET_VARS["txt_reference"])?quote_smart($HTTP_GET_VARS["txt_reference"]):"");
			$txt_cancel = (isset($HTTP_GET_VARS["txt_cancel"])?quote_smart($HTTP_GET_VARS["txt_cancel"]):"");
			$txt_checkingaccount = (isset($HTTP_GET_VARS["txt_checkingaccount"])?quote_smart($HTTP_GET_VARS["txt_checkingaccount"]):"");
			$txt_bankrouting = (isset($HTTP_GET_VARS["txt_bankrouting"])?quote_smart($HTTP_GET_VARS["txt_bankrouting"]):"");
			$str_qry = "";
			if($txt_cc) $str_qry .= " AND CCnumber = '$txt_cc' ";
			if($txt_telephone) $str_qry .= " AND phonenumber = '$txt_telephone' ";
			if($txt_email) $str_qry .= " AND email = '$txt_email' ";
			if($txt_reference) $str_qry .= " AND reference_number = '$txt_reference' ";
			if($txt_cancel) $str_qry .= " AND cancel_refer_num = '$txt_cancel' ";
			if($txt_checkingaccount) $str_qry .= " AND bankaccountnumber = '$txt_checkingaccount' ";
			if($txt_bankrouting) $str_qry .= " AND bankroutingcode = '$txt_bankrouting' ";
	
				$i_customer_service_id = -1;
				if(isset($_SESSION["sessionService"]))
				{
					$i_customer_service_id = 0;
				}
				else if(isset($_SESSION["sessionServiceUserId"]))
				{
					$i_customer_service_id = $_SESSION["sessionServiceUserId"];
				}
				if($i_customer_service_id == 0)
				{
					$str_company_ids = "A";
				}
				if($str_company_ids != "A")
				{
					$qry_select = "select company_ids, gateway_id from cs_customerserviceusers where id = $i_customer_service_id";
					if(!($rst_select = mysql_query($qry_select,$cnn_cs)))
					{
						print("Can not execute query");
						exit();
					}
					if(mysql_num_rows($rst_select))
					{
						$str_company_ids = mysql_result($rst_select,0,0);
						$i_gateway_id = mysql_result($rst_select,0,1);
					}
				}

				$qry_select = "SELECT * FROM cs_transactiondetails WHERE 1 $str_qry";
				print($qry_select);
				//$qry_select .= " and admin_approval_for_cancellation <> 'P'";
				//print($qry_select);
				$strBillDate = "";
				if(!($rst_select = mysql_query($qry_select,$cnn_cs)))
				{
					print("Can not execute query");
					exit();
				}
				//********** IF THE NUMBER FOUND IN DATABASE ***********************
				if(mysql_num_rows($rst_select))
				{
					for($i_loop = 0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
					{
						$strFirstName = mysql_result($rst_select,$i_loop,3);
						$strLastName = mysql_result($rst_select,$i_loop,4);
						$strTelephoneNumber = mysql_result($rst_select,$i_loop,5);
						$strAddress = mysql_result($rst_select,$i_loop,6);
						$strCity = mysql_result($rst_select,$i_loop,11);
						$strState = mysql_result($rst_select,$i_loop,12);
						$strZip = mysql_result($rst_select,$i_loop,13);
						$strCheckOrCard = mysql_result($rst_select,$i_loop,9);
						$strCountry = mysql_result($rst_select,$i_loop,10);
						$strOrderDate = mysql_result($rst_select,$i_loop,2);
						$strCancelDate = mysql_result($rst_select,$i_loop,33);
						$strMisc = mysql_result($rst_select,$i_loop,21);
						$strBillDate = mysql_result($rst_select,$i_loop,38);
						$strAmount = mysql_result($rst_select,$i_loop,14);
						$strTransactionID= mysql_result($rst_select,$i_loop,0);
						$strVoiceVerificationNumber = mysql_result($rst_select,$i_loop,34);
						$strUserId = mysql_result($rst_select,$i_loop,25);
						$strcancelstatus = mysql_result($rst_select,$i_loop,23);
						$strApprovalStatus = mysql_result($rst_select,$i_loop,24);
						$strTransactionStatus = mysql_result($rst_select,$i_loop,39);
						$str_decline_reason = mysql_result($rst_select,$i_loop,46);
						$i_bill_year = substr($strBillDate,0,4);
						$i_bill_month = substr($strBillDate,5,2);
						$i_bill_day = substr($strBillDate,8,4);
						$str_cancel_status = mysql_result($rst_select,$i_loop,23);

						$strCompanyName = func_get_value_of_field($cnn_cs,"cs_companydetails","companyname","userid",$strUserId);
						$str_order_status = "";
							switch ($strTransactionStatus)
							{
								case  "PE":
									$strTransactionStatus = "Pending";
									$str_order_status = "Pending Voice Authorization";
									break;
								case "PA":
									$strTransactionStatus = "Pass";
									$str_order_status = "Pass";
									break;
								case "NP":
									$strTransactionStatus = "No Pass";
									$str_order_status = "No Pass";
									break;	
								case "ND":
									$strTransactionStatus = "Cancelled";
									$str_order_status = "Negative Database";
									break;	
								default :
									$strTransactionStatus = "";
							}
							switch ($strApprovalStatus)
							{
								case  "P":
									$strApprovalStatus = "";
									break;
								case "A":
									$strApprovalStatus = "Approved";
									$str_order_status = "Approved";
									break;
								case "D":
									$strApprovalStatus = "Declined";
									$str_order_status = "Declined";
									break;	
								default :
									$strApprovalStatus = "";
							}
							if($str_cancel_status == "Y")
							{
								if($strCancelDate < $strBillDate)
								{
									$str_order_status = "Canceled Before Billed"; 
								}
								else
								{
									if($strApprovalStatus == "Approved")
									{
										$str_order_status = "Billed then Canceled";
									}
									else if($strApprovalStatus == "Declined")
									{
										$str_order_status = "Canceled After Bill Date - Declined";
									}
									else
									{
										$str_order_status = "Canceled After Bill Date - Pending Approval"; 
									}
								}
							}

							$qrySelect = "select co.retrievalNumber from cs_companydetails co,cs_transactiondetails tr where ";
							$qrySelect.= " co.userId = tr.userId and tr.transactionId =".$strTransactionID;
							$strRetrivalNumber = funcGetValueByQuery($qrySelect,$cnn_cs);
							$strCurrentDateTime = func_get_current_date_time();
							//********* Logging the entry ********************
								/*$strCurrentDate1 = func_get_current_date();
								$strCurrentDate1 .= " 00:00:00";
								$strCurrentDate2 = func_get_current_date();
								$strCurrentDate2 .= " 23:59:59";
								$qry_select = "SELECT * FROM cs_callnotes WHERE call_date_time >='$strCurrentDate1' and call_date_time<='$strCurrentDate2' and transaction_id=$strTransactionID";
								if(!($rst_select = mysql_query($qry_select,$cnn_cs)))
								{
									print("Can not execute select query");
									exit();
								}
								if(mysql_num_rows($rst_select)>0)
								{
									$iNoteId = mysql_result($rst_select,0,0);
									$qryUpdate = "update cs_callnotes set call_date_time = '$strCurrentDateTime' where note_id = $iNoteId";
									if(!mysql_query($qryUpdate)){print("Can not execute update query");exit();}	
								}
								else
								{
									$qryInsert = "insert into cs_callnotes (transaction_id,call_date_time) values ($strTransactionID,'$strCurrentDateTime')";
									if(!mysql_query($qryInsert)){ print("Can not execute insert query"); exit(); }
								}*/									
							
							
							//***********************************************
							
							 ?>
												
	<?php	
						}

						$isValidUser = false;
						$arr_company_ids = array();
						if($str_company_ids != "")
						{
							if($str_company_ids == "A")
							{
								if ($i_gateway_id == -1) {
									if (func_is_gateway_company($cnn_cs, $strUserId) == -1) {
										$isValidUser = true;
									}
								} else {
									if (func_is_gateway_company($cnn_cs, $strUserId) == $i_gateway_id) {
										$isValidUser = true;
									}
								}
							}
							else if ($str_company_ids == "G") {
								if (func_is_gateway_company($cnn_cs, $strUserId)) {
									$isValidUser = true;
								}
							}
							else
							{
								$arr_company_ids = split(",",$str_company_ids);
								if(sizeof($arr_company_ids) != 0)
								{
									if(in_array($strUserId,$arr_company_ids))
										$isValidUser = true;
								}
							}
						}
						if(!$isValidUser)
						{
							$msgtodisplay="You are not Authorized to attend calls from this Company";
							$outhtml="y";
							print("<br>");
							message($msgtodisplay,$outhtml,$headerInclude);									
							exit();
						}
						else
						{
							$strSecurityNumber = func_get_value_of_field($cnn_cs,"cs_companydetails","securityNumber","userId",$strUserId);
							if($str_back == "")
							{
								$qryInsert = "insert into cs_callnotes (transaction_id,call_date_time,customer_service_id) values ($strTransactionID,'$strCurrentDateTime','$i_customer_service_id')";
								if(!mysql_query($qryInsert,$cnn_cs)){
									print("Can not execute insert query"); exit(); 
								} else {
									$i_call_note_id = mysql_insert_id($cnn_cs);
								}
							}

		?>
		<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
		  <tr>
			   <td width="83%" valign="top" align="center"  >
			&nbsp;
			<table border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tr>
			<td colspan="2" height="25">
			</td>
			</tr>
		<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="20%" background="../images/menucenterbg.gif" ><span class="whitehd">Search&nbsp;Result</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="75%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
			</tr>
			  <tr>
				<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>

		<form name="frm_result" method="post" action="#" target="_self">
		<input type="hidden" name="hdTransactionId" value="<?= $strTransactionID ?>">
		<input type="hidden" name="hid_orig_bill_date" value="<?= $strBillDate ?>">
		<input type="hidden" name="hid_call_note_id" value="<?= $i_call_note_id ?>">
		<input type="hidden" name="hid_approval_status" value="<?= $strApprovalStatus ?>">
		  <table cellpadding="0" cellspacing="0" align="center" width="100%" >
			<tr> 
			  <td width="50%" valign="top">
			   <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" >
				  <tr> 
					<td align="center" bgcolor="#CCCCCC">&nbsp;&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>:: 
					  Personal Details :: </strong></font></td>
				  </tr>
				  <tr> 
					<td align="center" valign="top" bgcolor="#EDF8F2"> <table width="90%%" border="0" cellpadding="2" cellspacing="0">
					<tr> 
						  <td width="33%"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
							  Name </font></div></td>
						  <td width="7%">&nbsp;</td>
						  <td width="60%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strFirstName ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
							  Name </font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strLastName ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Company </font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strCompanyName?>&nbsp;</font></td>
						</tr>
						<tr> 
						<td> 
						<div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Telephone 
							  Number </font></div></td>
						  <td>&nbsp;</td>
						  <td bgcolor="#CCCCCC"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"> 
							<?= $strTelephoneNumber ?>
							&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Address</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strAddress ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">City</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strCity ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">State</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strState ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strZip ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Country</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strCountry ?>&nbsp;</font></td>
						</tr>
					  </table></td>
				  </tr>
				</table>
				<table width="100%" height="211" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
                  <tr> 
					<td bgcolor="#CCCCCC">&nbsp;&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>Transaction 
					  Details :: </strong></font></td>
				  </tr>
				  <tr> 
					<td height="180" align="center" valign="top" bgcolor="#EDF8F2"> 
<table width="90%" border="0" cellspacing="0" cellpadding="2">
						<!--<tr> 
						  <td width="33%"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Transaction 
							  Id</font></div></td>
						  <td width="7%">&nbsp;</td>
						  <td width="60%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strTransactionID ?></font></td>
						</tr>-->
						<tr> 
						  <td width="33%"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Order Status</font></div></td>
						  <td width="7%">&nbsp;</td>
						  <td width="60%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $str_order_status ?></font></td>
						</tr>
						
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">$<?=  number_format($strAmount, 2, '.', ',') ?></font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Order 
							  Date</font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= func_get_date_inmmddyy($strOrderDate) ?></font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bill 
							  Date</font></div></td>
						  <td>&nbsp;</td>
						  <td>
						  <?php
							  if($str_cancel_status == "N" && $strApprovalStatus == "" && func_is_future_date($strBillDate)){?>
							   <select name="opt_bill_month" style="font-size:10px">
								<?php func_fill_month($i_bill_month); ?>
							   </select>
								<select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
								<?php func_fill_day($i_bill_day); ?>
							   </select>
								<select name="opt_bill_year" style="font-size:10px">
								<?php func_fill_year($i_bill_year); ?>
							   </select>
							  <?php 
							  } else {?>
								<font size='1' face='Verdana, Arial, Helvetica, sans-serif'><?php print($i_bill_month)?>-<?php print($i_bill_day)?>-<?php print($i_bill_year)?></font>
								<input type="hidden" name="hid_bill_date" value="<?php print($i_bill_year)?>-<?php print($i_bill_month)?>-<?php print($i_bill_day)?>">
						   <?php } ?>
						  </td>
						</tr>
						<?php
						if($str_cancel_status == "Y")
						{
						?>
							<tr> 
							  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Cancel 
								  Date</font></div></td>
							  <td>&nbsp;</td>
							  <td>
							  <table cellpadding="0" cellspacing="0"><tr><td style="border:1 solid red">
							  <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= func_get_date_inmmddyy($strCancelDate) ?></font>
							  </td></tr>
							  </table>
							  </td>
							</tr>
						<?php	
						}
						?>
						<?php
						if($strApprovalStatus == "Declined")
						{
						?>
							<tr> 
							  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Decline Reason </font></div></td>
							  <td>&nbsp;</td>
							  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php $strApprovalStatus == "Declined" ? print($str_decline_reason) : "" ?></font></td>
							</tr>
						<?php
						}
						?>
					  </table></td>
				  </tr>
				</table>
				
				
				</td>
			  <td width="50%" align="center" valign="top">
			   <table width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
				  <tr> 
					<td align="center" bgcolor="#CCCCCC">&nbsp;&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF"><strong>:: 
					  Voice Authorization Details :: </strong></font></td>
				  </tr>
				  <tr> 
					<td align="center" valign="top" bgcolor="#EDF8F2"> 
					<table width="90%" border="0" cellspacing="0" cellpadding="2">
						<tr> 
						  <td width="33%"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
							  Verification Id</font></div></td>
						  <td width="8%">&nbsp;</td>
						  <td width="59%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strVoiceVerificationNumber ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Retrieval 
							  Number </font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strRetrivalNumber ?>&nbsp;</font></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Security 
							  Code </font></div></td>
						  <td>&nbsp;</td>
						  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?= $strSecurityNumber ?>&nbsp;</font></td>
						</tr>
					  </table></td>
				  </tr>
				</table>
				<table width="100%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" height="335">
				  <tr>
					<td align="center" valign="middle" bgcolor="#CCCCCC">&nbsp;&nbsp;<strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFFF">:: 
					  Call Disposition :: </font></strong></td>
				  </tr>
				  <tr>
					<td align="center" valign="top" bgcolor="#EDF8F2"> 
					<table width="90%" border="0" cellspacing="0" cellpadding="2" height="100%">
						<tr> 
						  <td width="38%"><div align="right"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Company 
							  Call Back</font></font></div></td>
						  <td width="8%">&nbsp;</td>
						  <td width="54%"><input type="checkbox" name="chkCallBack" value="Y"></td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Solved</font></font></div></td>
						  <td>&nbsp;</td>
						  <td>
							<input type="checkbox" name="chkSolved" value="1">
						  </td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Cancel Order</font></font></div></td>
						  <td>&nbsp;</td>
						  <td>
						  <?php if ($strcancelstatus =="Y"){ ?>
							<font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;Cancelled</font>		
							<input type="hidden" name="hid_cancel" value="Y">
						  <?php	}else{ ?>
							<input type="checkbox" name="chkCancel" value="Y" onClick="javascript:cancel();">
						  <?php } ?>
						  </td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">D.N.C</font></font></div></td>
						  <td>&nbsp;</td>
						  <td>
							<input type="checkbox" name="chkDNC" value="Y">
						  </td>
						</tr>
						<tr> 
						  <td><div align="right"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif">Notes</font></font></div></td>
						  <td>&nbsp;</td>
						  <td> &nbsp;<textarea name="txtNotes" cols="30" rows="13" id="txtNotes"></textarea>&nbsp;</td>
						</tr>
					  </table>
					</td>
				  </tr>
				  
				</table>
				</td>
			</tr>
		<tr><td height="40" valign="middle" colspan="2" align="center"><?php if(get_previous_calls_count($cnn_cs,$strTransactionID) > 1){?><a href="javascript:funcShowPreviousCalls('<?=$strTransactionID ?>','<?=$str_phone ?>','<?=$str_search_mode ?>','<?=$i_call_note_id?>')"><img src="../images/showpreviuoscalls.jpg" border="0"></a><?php }?>&nbsp;<a href="javascript:func_add_notes(document.frm_result,'<?= $strBillDate?>');"><img src="../images/submit.jpg" border="0"></a>
		<a href="virtualterminal.php"><img src="../images/orderpage.jpg" border="0"></a>
		</td></tr>  
		</table>

		  <input type="hidden" name="hid_counter" value="">
		  <input type="hidden" name="hid_first_name" value="<?= $strFirstName?>">
		  <input type="hidden" name="hid_last_name" value="<?= $strLastName?>">
		  <input type="hidden" name="hid_company_name" value="<?= $strCompanyName?>">
		  <input type="hidden" name="hid_telephone_number" value="<?= $strTelephoneNumber?>">
		  <input type="hidden" name="hid_address" value="<?= $strAddress?>">
		  <input type="hidden" name="hid_city" value="<?= $strCity?>">
		  <input type="hidden" name="hid_state" value="<?= $strState?>">
		  <input type="hidden" name="hid_zip" value="<?= $strZip?>">
		  <input type="hidden" name="hid_country" value="<?= $strCountry?>">
		  <input type="hidden" name="hid_user_id" value="<?= $strUserId?>">
		  <input type="hidden" name="hid_check_or_card" value="<?= $strCheckOrCard?>">
		</form>	

<?php
		}
	}
				//************* IF THE NUMBER IS NOT FOUND IN DATABASE **********
else
{ 

		if($str_search_mode == "voice_id")
		{
			$msgtodisplay="Voice Authorization Id '$str_phone' not found";
			$outhtml="y";
			print("<br>");
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
		else
		{
?>

		<table border="0" cellpadding="0" width="50%" cellspacing="0" height="80%" align="center">
		  <tr>
			   <td width="83%" valign="top" align="center"  >
			&nbsp;
			<table border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tr>
			<td colspan="2" height="25">
			</td>
			</tr>
		<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="20%" background="../images/menucenterbg.gif" ><span class="whitehd">Search&nbsp;Result</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="75%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
			</tr>
			  <tr>
				<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>

			<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">Not found in database. If you like to record the particular 
              call details please fill the following form and click on submit or hit the &quot;Search Again&quot; Button to retry your search.</font></p>
            <form name="frm_unfound_call" method="post" action="unfoundcall.php" onSubmit="return func_validate(document.frm_unfound_call)">
				
              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                <tr> 
					<td width="35%"><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Name</font></font></div></td>
					
                  <td width="65%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                    <input name="txtName" type="text" id="txtName" size="41" maxlength="250" style="font-family:Verdana;font-size:10px">
                    </font></td>
				  </tr>
				  <tr> 
					<td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Address</font></font></div></td>
					
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
                    <textarea name="txtAddress" cols="40" rows="4" id="txtAddress" style="font-family:Verdana;font-size:10px"></textarea>
                    </font></td>
				  </tr>
				  <tr> 
					<td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Phone 
						Number </font></font></div></td>
					
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                    <input name="txtPhone" type="text" id="txtPhone"  maxlength="10" style="font-family:Verdana;font-size:10px;width:80px" value="<?php print($str_phone);?>">
                    </font></td>
				  </tr>
				  <tr> 
					<td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Notes</font></font></div></td>
					
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                    <textarea name="txtNotes" cols="40" rows="6" id="txtNotes" style="font-family:Verdana;font-size:10px"></textarea>
                    </font></td>
				  </tr>
				  <tr> 
					<td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1"></font></font></div></td>
					
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                    <input name="imageField" type="button" value="Submit" border="0">
					<input name="search" type="button" onClick="javascript:document.location.href='customerservice.php'" value="Search Again">
                    </font></td>
				  </tr>
				</table>
				 <input type="hidden" name="hid_counter" value="">
				 <input type="hidden" name="hid_gateway_id" value="<?= $i_gateway_id ?>">
			</form>	
			
<?php			}
}
		?>
		</td>
      </tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
     </tr>
</table>
<br>
<?php
	include("../admin/includes/footer.php");
?>	
<?php
function get_previous_calls_count($cnn_cs,$iTransactionId)
{
	$qrySelect = "SELECT * FROM cs_callnotes WHERE transaction_id = ".$iTransactionId;
	if(!($rstSelect = mysql_query($qrySelect,$cnn_cs)))
	{
		print("Can not execute query");
		exit();
	}
	return mysql_num_rows($rstSelect);
}

function func_is_future_date($strBillDate)
{	
	$is_future_date = false;
	$str_current_date = func_get_current_date();
	if($strBillDate > $str_current_date)
		$is_future_date = true;
	//print("is future date= ".$is_future_date."*");
	return $is_future_date;
}
?>